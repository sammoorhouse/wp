<?php
class ProgressAllyEvents {
	const SETTING_KEY = '_progressally_setting_events';

	private static $default_overall_settings = array('num-event' => 0);
	private static $default_individual_event_settings = array(
		'name' => 'Event name',
		'select-trigger-type' => 'login',
		'visit-page' => array(),
		'page-template-dummy' => '0',
		'select-trigger-freq' => 'once',
		'select-action-type' => 'tag',
		'action-tag' => array(),
		'tag-template-dummy' => '0',
		'page-template-trigger-objective-page' => '0',
		'trigger-objective-selection' => array(),
		'page-template-action-objective-page' => '0',
		'action-objective-selection' => array(),
		);

	public static function add_actions() {
		add_action('wp_ajax_progressally_save_event_setting', array(__CLASS__, 'save_settings_callback'));
		add_action('wp_ajax_progressally_delete_event_setting', array(__CLASS__, 'delete_settings_callback'));
		add_action('wp_ajax_progressally_get_event_trigger_objectives', array(__CLASS__, 'get_trigger_objectives_callback'));
		add_action('wp_ajax_progressally_get_event_action_objectives', array(__CLASS__, 'get_action_objectives_callback'));
	}

	// <editor-fold defaultstate="collapsed" desc="Backend setting operations">
	private static function get_event_overall_settings() {
		$settings = ProgressAllyUtilities::get_settings(self::SETTING_KEY, self::$default_overall_settings);
		return $settings;
	}
	const EVENT_OPTION_PREFIX = 'progressally_event_';
	private static $cached_all_event_settings = false;
	public static function get_all_event_settings() {
		if (false === self::$cached_all_event_settings) {
			$all_event_db_entries = ProgressAllyUtilities::get_all_option_with_prefix(self::EVENT_OPTION_PREFIX);
			$result = array();
			foreach ($all_event_db_entries as $entry) {
				$setting = maybe_unserialize($entry->option_value);
				$setting = self::merge_default_event_settings($setting);
				$event_id = str_replace(self::EVENT_OPTION_PREFIX, '', $entry->option_name);
				$result[$event_id] = $setting;
			}
			self::$cached_all_event_settings = $result;
		}
		return self::$cached_all_event_settings;
	}
	private static function merge_default_event_settings($setting) {
		$setting = wp_parse_args($setting, self::$default_individual_event_settings);
		return $setting;
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="Save settings via ajax">
	public static function save_settings_callback() {
		$result = array('status' => 'error', 'message' => '');
		try {
			if (!isset($_POST['value']) || !isset($_POST['event_id']) || !isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'progressally-update-nonce')) {
				throw new Exception("Unable to validate request identify. Please refresh the page and make sure you are logged in as an administrator.");
			}
			$event_id = $_POST['event_id'];
			$event_settings = ProgressAllyBackendShared::convert_setting_string_to_array($_POST['value']);
			$event_settings = self::merge_default_event_settings($event_settings);

			$overall_settings = self::get_event_overall_settings();
			$event_id = max($event_id, $overall_settings['num-event'] + 1);

			$option_key = self::EVENT_OPTION_PREFIX . $event_id;
			if (!add_option($option_key, $event_settings, '', 'no')) {
				update_option($option_key, $event_settings);
			}

			$individual_event_template = file_get_contents(dirname(__FILE__) . '/event-template.php');
			$all_page_id_name_mapping = ProgressAllyBackendShared::get_all_post_page_id_name_map();
			$all_tag_id_mapping = ProgressAllyBackendShared::get_tag_id_mapping();
			$result['code'] = self::generate_individual_event_code($individual_event_template, $event_id, $event_settings, $all_page_id_name_mapping, $all_tag_id_mapping);
			$result['status'] = 'success';
		} catch (Exception $ex) {
			$result = array('status' => 'error', 'message' => $ex->getMessage());
		}
		echo json_encode($result);
		die();
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="Delete settings via ajax">
	public static function delete_settings_callback() {
		$result = array('status' => 'error', 'message' => '');
		try {
			if (!isset($_POST['event_id']) || !isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'progressally-update-nonce')) {
				throw new Exception("Unable to validate request identify. Please refresh the page and make sure you are logged in as an administrator.");
			}
			$event_id = $_POST['event_id'];

			$option_key = self::EVENT_OPTION_PREFIX . $event_id;
			delete_option($option_key);

			$result['status'] = 'success';
		} catch (Exception $ex) {
			$result = array('status' => 'error', 'message' => $ex->getMessage());
		}
		echo json_encode($result);
		die();
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="Backend setting display">
	public static function show_settings() {
		if (!current_user_can('manage_options')) {
			wp_die('You do not have sufficient permissions to access this page.');
		}
		$overall_settings = self::get_event_overall_settings();
		include (dirname(__FILE__) . '/events-main-display.php');
	}
	public static function show_setup_settings($overall_settings) {
		$all_event_settings = self::get_all_event_settings();

		$individual_event_template = file_get_contents(dirname(__FILE__) . '/event-template.php');

		$all_page_id_name_mapping = ProgressAllyBackendShared::get_all_post_page_id_name_map();
		$all_tag_id_mapping = ProgressAllyBackendShared::get_tag_id_mapping();

		$max_id = $overall_settings['num-event'];
		$event_code = '';
		foreach ($all_event_settings as $event_id => $event_setting) {
			$event_code .= self::generate_individual_event_code($individual_event_template, $event_id, $event_setting, $all_page_id_name_mapping, $all_tag_id_mapping);
			$max_id = max($max_id, $event_id);
		}

		$setup_code = file_get_contents(dirname(__FILE__) . '/events-setup-display.php');
		$setup_code = str_replace('{{existing-events}}', $event_code, $setup_code);

		$setup_code = str_replace('{{max-event}}', $max_id, $setup_code);
		return $setup_code;
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="generate per event code">
	private static function generate_objective_list_checkbox($checkbox_template, $checkbox_type, $event_id, $page_id, $checked_objectives) {
		$all_objectives = ProgressAllyPostObjective::get_objectives($page_id);

		$code = '';
		if (empty($all_objectives)) {
			return 'No objective is defined for this page.';
		}
		foreach ($all_objectives as $objective_id => $objective_meta) {
			$objective_checkbox = str_replace('{{objective-id}}', $objective_id, $checkbox_template);
			$objective_checkbox = str_replace('{{description}}', esc_html($objective_meta['description']), $objective_checkbox);

			if ('trigger' === $checkbox_type || ProgressAllyTaskDefinition::can_user_update_this_objective($all_objectives, $objective_id)) {
				if (in_array($objective_id, $checked_objectives)) {
					$objective_checkbox = str_replace('{{checkbox-attr}}', 'checked="checked"', $objective_checkbox);
				} else {
					$objective_checkbox = str_replace('{{checkbox-attr}}', '', $objective_checkbox);
				}
				$objective_checkbox = str_replace('{{label-attr}}', '', $objective_checkbox);
				$objective_checkbox = str_replace('{{label-class}}', '', $objective_checkbox);
			} else {
				$objective_checkbox = str_replace('{{checkbox-attr}}', 'disabled="disabled"', $objective_checkbox);
				$objective_checkbox = str_replace('{{label-attr}}', 'progressally-tooltip="This objective cannot be manually checked off"', $objective_checkbox);
				$objective_checkbox = str_replace('{{label-class}}', 'progressally-event-objective-list-disabled-option', $objective_checkbox);
			}
			$code .= $objective_checkbox;
		}
		$code = str_replace('{{event-id}}', $event_id, $code);
		$code = str_replace('{{page-id}}', $page_id, $code);
		$code = str_replace('{{type}}', $checkbox_type, $code);
		return $code;
	}
	private static function generate_page_display($page_id, $page_id_name_map) {
		if (isset($page_id_name_map[$page_id])) {
			return '<div class="progressally-page"><div class="progressally-page-delete">&#x2715;</div><div class="progressally-page-name">' . esc_attr($page_id_name_map[$page_id]) . '</div>' .
			'<input type="hidden" value="' . esc_attr($page_id) . '" progressally-param="visit-page[]" /></div>';
		} else {
			return '<div class="progressally-page progressally-page-invalid" progressally-tooltip="Unknown page: the page has been removed."><div class="progressally-page-delete">&#x2715;</div><div class="progressally-page-name">' . esc_attr($page_id) . '</div>' .
			'<input type="hidden" value="' . esc_attr($page_id) . '" progressally-param="visit-page[]" /></div>';
		}
	}
	private static function generate_tag_display($tag_id, $all_tag_id_mapping) {
		if (isset($all_tag_id_mapping[$tag_id])) {
			return '<div class="progressally-tag"><div class="progressally-tag-delete">&#x2715;</div><div class="progressally-tag-name">' . esc_attr($all_tag_id_mapping[$tag_id]) . '</div>' .
			'<input type="hidden" value="' . esc_attr($tag_id) . '" progressally-param="action-tag[]" /></div>';
		} else {
			return '<div class="progressally-tag progressally-tag-invalid" progressally-tooltip="Invalid tag: tag name does not match existing tags in the system."><div class="progressally-tag-delete">&#x2715;</div><div class="progressally-tag-name">' . esc_attr($tag_id) . '</div>' .
			'<input type="hidden" value="' . esc_attr($tag_id) . '" progressally-param="action-tag[]" /></div>';
		}
	}
	private static function generate_individual_event_code($event_template, $event_id, $event_setting, $all_page_id_name_mapping, $all_tag_id_mapping, $is_default = false) {
		$code = ProgressAllyBackendShared::replace_real_values($event_template, $event_setting, '', $is_default);
		$code = ProgressAllyBackendShared::replace_all_toggle($code, $event_setting);

		$visit_page_code = '';
		foreach ($event_setting['visit-page'] as $page_id) {
			$visit_page_code .= self::generate_page_display($page_id, $all_page_id_name_mapping);
		}
		$code = str_replace('{{visit-pages}}', $visit_page_code, $code);

		$checkbox_template = file_get_contents(dirname(__FILE__) . '/event-objective-checkbox-template.php');
		$trigger_objective_code = '';
		if ($event_setting['page-template-trigger-objective-page'] <= 0) {
			$code = str_replace('{{show-trigger-objective-list}}', 'style="display:none"', $code);
		} else {
			$trigger_objective_code = self::generate_objective_list_checkbox($checkbox_template, 'trigger', $event_id,
				$event_setting['page-template-trigger-objective-page'], $event_setting['trigger-objective-selection']);
			$trigger_objective_code = str_replace('{{variable-name}}', 'trigger-objective-selection', $trigger_objective_code);
			$code = str_replace('{{show-trigger-objective-list}}', '', $code);
		}
		$code = str_replace('{{trigger-objectives}}', $trigger_objective_code, $code);

		$action_objective_code = '';
		if ($event_setting['page-template-action-objective-page'] <= 0) {
			$code = str_replace('{{show-action-objective-list}}', 'style="display:none"', $code);
		} else {
			$action_objective_code = self::generate_objective_list_checkbox($checkbox_template, 'action', $event_id,
				$event_setting['page-template-action-objective-page'], $event_setting['action-objective-selection']);
			$action_objective_code = str_replace('{{variable-name}}', 'action-objective-selection', $action_objective_code);
			$code = str_replace('{{show-action-objective-list}}', '', $code);
		}
		$code = str_replace('{{action-objectives}}', $action_objective_code, $code);

		$action_tag_code = '';
		foreach ($event_setting['action-tag'] as $tag_id) {
			$action_tag_code .= self::generate_tag_display($tag_id, $all_tag_id_mapping);
		}
		$code = str_replace('{{selected-action-tags}}', $action_tag_code, $code);

		$code = str_replace('{{trigger-descripton}}', self::generate_trigger_description($event_setting), $code);
		$code = str_replace('{{action-descripton}}', self::generate_action_description($event_setting), $code);

		if ($is_default) {
			$code = str_replace('{{show-readonly}}', 'style="display:none"', $code);
			$code = str_replace('{{show-edit-view}}', '', $code);
		} else {
			$code = str_replace('{{show-readonly}}', '', $code);
			$code = str_replace('{{show-edit-view}}', 'style="display:none"', $code);
		}
		$code = str_replace('{{id}}', $event_id, $code);
		return $code;
	}
	public static function generate_default_individual_event_code() {
		$individual_event_template = file_get_contents(dirname(__FILE__) . '/event-template.php');
		$code = self::generate_individual_event_code($individual_event_template, '--id--', self::$default_individual_event_settings, array(), array(), true);
		return $code;
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="generate readonly description">
	private static function generate_readonly_page_description($page_id) {
		if (empty($page_id)) {
			return '<span class="progressally-readonly-error">No page is selected</span>';
		}
		$desc = '';
		$page_id_name_mapping = ProgressAllyBackendShared::get_all_post_page_id_name_map();
		if (isset($page_id_name_mapping[$page_id])) {
			$desc .= '<a target="_blank" href="' . get_edit_post_link($page_id) . '">' . esc_html($page_id_name_mapping[$page_id]) . '</a>';
		} else {
			$desc .= '<span class="progressally-readonly-error">Invalid page (' . esc_html($page_id). ')</span>';
		}
		return $desc;
	}
	private static function generate_readonly_objective_list($page_id, $selected_objectives) {
		$post_objectives = ProgressAllyPostObjective::get_objectives($page_id);
		$objective_list_code = '';
		foreach ($selected_objectives as $objective_id) {
			if (isset($post_objectives[$objective_id])) {
				$objective_list_code .= '<li>' . esc_html($post_objectives[$objective_id]['description']) . '</li>';
			}
		}
		// we do the empty check on the generated code, as the selected objectives might include objectives that were deleted.
		if (empty($objective_list_code)) {
			$objective_list_code .= '<li class="progressally-readonly-error">No objective is selected</li>';
		}
		return '<ul>' . $objective_list_code . '</ul>';
	}
	private static function generate_trigger_description($event_settings) {
		$desc = '';
		if ('login' === $event_settings['select-trigger-type']) {
			$desc = 'When the user logs in';
		} elseif ('visit' === $event_settings['select-trigger-type']) {
			$desc = 'When the user visits the following page';
			if (count($event_settings['visit-page']) > 1) {
				$desc .= 's';
			}
			$desc .= ':<ul>';
			$page_id_name_mapping = ProgressAllyBackendShared::get_all_post_page_id_name_map();
			foreach ($event_settings['visit-page'] as $page_id) {
				if (isset($page_id_name_mapping[$page_id])) {
					$desc .= '<li>' . esc_html($page_id_name_mapping[$page_id]) . '</li>';
				} else {
					$desc .= '<li>Invalid page</li>';
				}
			}
			$desc .= "</ul>";
		} elseif ('objective' === $event_settings['select-trigger-type']) {
			$desc = 'When the user marks the follwing objective';
			$objective_count = count($event_settings['trigger-objective-selection']);
			if ($objective_count > 1) {
				$desc .= 's';
			}
			$page_id = $event_settings['page-template-trigger-objective-page'];
			$desc .= ' as completed on ' . self::generate_readonly_page_description($page_id);

			if (!empty($page_id)) {
				$desc .= self::generate_readonly_objective_list($page_id, $event_settings['trigger-objective-selection']);
			}
		} elseif ('accessally' === $event_settings['select-trigger-type']) {
			$desc = 'Through AccessAlly Custom Operation';
		}
		return $desc;
	}
	private static function generate_action_description($event_settings) {
		$desc = '';
		if ('tag' === $event_settings['select-action-type']) {
			$action_tag_count = count($event_settings['action-tag']);
			$desc .= 'Add the following tag';
			if ($action_tag_count > 1) {
				$desc .= 's';
			}
			$desc .= ' in the CRM:<ul>';
			$tag_id_map = ProgressAllyBackendShared::get_tag_id_mapping();
			if ($action_tag_count <= 0) {
				$desc .= '<li class="progressally-readonly-error">No tag is selected</li>';
			} else {
				foreach ($event_settings['action-tag'] as $tag_id) {
					if (isset($tag_id_map[$tag_id])) {
						$desc .= '<li>' . esc_html($tag_id_map[$tag_id]) . '</li>';
					} else {
						$desc .= '<li class="progressally-readonly-error">Invalid tag (' . esc_html($tag_id) . ')</li>';
					}
				}
			}
		} elseif ('objective' === $event_settings['select-action-type']) {
			$desc .= 'Mark objective';
			$objective_count = count($event_settings['action-objective-selection']);
			if ($objective_count > 1) {
				$desc .= 's';
			}
			$page_id = $event_settings['page-template-action-objective-page'];
			$desc .= ' as completed on ' . self::generate_readonly_page_description($page_id);

			if (!empty($page_id)) {
				$desc .= self::generate_readonly_objective_list($page_id, $event_settings['action-objective-selection']);
			}
		}
		return $desc;
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="Get page objective list via ajax">
	public static function get_trigger_objectives_callback() {
		$result = array('status' => 'error', 'message' => '');
		try {
			if (!isset($_POST['event_id']) || !isset($_POST['page_id']) || !isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'progressally-update-nonce')) {
				throw new Exception("Unable to validate request identify. Please refresh the page and make sure you are logged in as an administrator.");
			}
			$event_id = $_POST['event_id'];
			$page_id = $_POST['page_id'];

			$checkbox_template = file_get_contents(dirname(__FILE__) . '/event-objective-checkbox-template.php');
			$code = self::generate_objective_list_checkbox($checkbox_template, 'trigger', $event_id, $page_id, array());

			$code = str_replace('{{variable-name}}', 'trigger-objective-selection', $code);

			$result['code'] = $code;
			$result['status'] = 'success';
		} catch (Exception $ex) {
			$result = array('status' => 'error', 'message' => $ex->getMessage());
		}
		echo json_encode($result);
		die();
	}
	public static function get_action_objectives_callback() {
		$result = array('status' => 'error', 'message' => '');
		try {
			if (!isset($_POST['event_id']) || !isset($_POST['page_id']) || !isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'progressally-update-nonce')) {
				throw new Exception("Unable to validate request identify. Please refresh the page and make sure you are logged in as an administrator.");
			}
			$event_id = $_POST['event_id'];
			$page_id = $_POST['page_id'];

			$checkbox_template = file_get_contents(dirname(__FILE__) . '/event-objective-checkbox-template.php');
			$code = self::generate_objective_list_checkbox($checkbox_template, 'action', $event_id, $page_id, array());

			$code = str_replace('{{variable-name}}', 'action-objective-selection', $code);

			$result['code'] = $code;
			$result['status'] = 'success';
		} catch (Exception $ex) {
			$result = array('status' => 'error', 'message' => $ex->getMessage());
		}
		echo json_encode($result);
		die();
	}
	// </editor-fold>
}
