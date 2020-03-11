<?php
class ProgressAllyTaskDefinition {
	const META_KEY_TASK_DEFINITION = '_progressally_post_meta';
	public static $default_tasks_meta = null;

	// <editor-fold defaultstate="collapsed" desc="activation setup / deactivation cleanup">
	public static function do_activation_actions(){
		wp_cache_delete(self::META_KEY_TASK_DEFINITION);
	}
	public static function do_deactivation_actions(){
		wp_cache_delete(self::META_KEY_TASK_DEFINITION);
	}
	// </editor-fold>

	public static function add_actions() {
		add_action('add_meta_boxes', array(__CLASS__, 'add_meta_box'));
		add_action('save_post', array(__CLASS__, 'save_postdata') );
		add_action('wp_ajax_progressally_task_definition_export', array(__CLASS__, 'process_export'));
		add_action('wp_ajax_progressally_generate_import_code', array(__CLASS__, 'generate_import_code_callback'));
	}

	public static function add_meta_box($post_type) {
		if (post_type_supports($post_type, 'editor')) {
			add_meta_box(
				'progressally_task_definition_box',
				 'ProgressAlly Page Settings',
				array( __CLASS__, 'show_post_task_definition_meta_box' ),
				$post_type,
				'normal'
			);
		}
	}

	public static function initialize_defaults() {
		ProgressAllyQuiz::initialize_defaults();
		self::$default_tasks_meta = array('selected-tab' => 'objective',
			'max-objective' => 0, 'objectives' => array(), 'objective-error' => '', 'objective-order' => array(),
			'complete-tag' => array(), 'completion-popup' => '',
			'checked-enable-quiz' => 'no', 'quiz' => ProgressAllyQuiz::$default_quiz_settings,
			'fb-automation-tag' => '',
			'completion-custom-operation' => '', 'checked-completion-custom-operation-always' => 'no'
			);
	}
	public static function enqueue_administrative_resources($admin_url, $hook) {
		wp_enqueue_script('progressally-post-editor-insert', ProgressAlly::$PLUGIN_URI . 'resource/backend/editor/progressally-editor.js', array('quicktags'), ProgressAlly::VERSION);
		wp_register_script('progressally-post-default-code', ProgressAlly::$PLUGIN_URI . 'resource/backend/js/progressally-post-default-code.js', array(), ProgressAlly::VERSION);
		wp_register_script('progressally-post-color-picker', ProgressAlly::$PLUGIN_URI . 'resource/backend/jscolor/jscolor.js', array('jquery'), ProgressAlly::VERSION);
		wp_enqueue_script('progressally-post-backend', ProgressAlly::$PLUGIN_URI . 'resource/backend/js/progressally-post.js',
			array('jquery', 'jquery-ui-autocomplete', 'jquery-ui-sortable', 'progressally-post-default-code', 'progressally-post-color-picker'), ProgressAlly::VERSION);

		global $post;
		$post_id = 0;
		if ($post && property_exists($post, 'ID')) {
			$post_id = $post->ID;
		}

		$post_selection = ProgressAllyBackendShared::get_all_post_page_selection_template();
		$post_selection = preg_replace('/s--selected-.*?--d/', '', $post_selection);

		wp_localize_script('progressally-post-backend', 'progressally_post',
			array('ajax_url' => $admin_url,
				'plugin_uri' => ProgressAlly::$PLUGIN_URI,
				'blog_title' => esc_html(get_bloginfo('name')),
				'note_mapping' => ProgressAllyNote::$note_type_option_mapping,
				'font_mapping' => ProgressAllyCertificate::$FONT_MAPPING,
				'cert_template' => ProgressAllyCertificate::generate_template_options(),
				'page_selection_code' => $post_selection,
				'quiz_tag_selection_code' => ProgressAllyBackendShared::get_tag_selection_template(),
				'quiz_popup_selection_code' => ProgressAllyBackendShared::get_popup_selection_template(),
				'quiz_field_selection_code' => ProgressAllyBackendShared::get_field_selection_template(),
				'nonce' => wp_create_nonce('progressally-update-nonce')
				));

		wp_enqueue_style('progressally-post-backend', ProgressAlly::$PLUGIN_URI . 'resource/backend/css/progressally-post.css', array(), ProgressAlly::VERSION);

		if (!empty($hook)) {
			add_action('admin_head-'.$hook, array('ProgressAlly', 'add_preview_scripts'));	// the preview script is used to show quiz preview
		}
	}

	// <editor-fold defaultstate="collapsed" desc="backend objective settings">
	private static function generate_per_objective_code($id, $setting, $objective_template, $seek_type_template, $select_page_option_template, $select_note_option_template){
		$objective = str_replace('{{id}}', $id, $objective_template);
		foreach($setting as $key => $val){
			if ($key === 'seek-type'){
				$seek_type = str_replace('s--selected-'.$val.'--d', 'selected="selected"', $seek_type_template);
				$seek_type = preg_replace('/s--selected-.*?--d/', '', $seek_type);
				$objective = str_replace("{{seek-type-selection}}", $seek_type, $objective);
			}elseif ($key === 'ref-post-id') {
				$page_options = str_replace('s--selected-'.$val.'--d', 'selected="selected"', $select_page_option_template);
				$page_options = preg_replace('/s--selected-.*?--d/', '', $page_options);
				$objective = str_replace("{{select-page-options}}", $page_options, $objective);
			}elseif ($key === 'note-id') {
				$note_options = str_replace('s--selected-'.$val.'--d', 'selected="selected"', $select_note_option_template);
				$note_options = preg_replace('/s--selected-.*?--d/', '', $note_options);
				$objective = str_replace("{{select-note-options}}", $note_options, $objective);
			}elseif ($key === 'checked-complete-video') {
				if ('yes' === $val) {
					$objective = str_replace("{{checked-complete-video}}", 'checked="checked"', $objective);
				} else {
					$objective = str_replace("{{checked-complete-video}}", '', $objective);
				}
			}else{
				$objective = str_replace("{{{$key}}}", esc_attr($val), $objective);
			}
		}
		$objective = ProgressAllyBackendShared::replace_all_toggle($objective, $setting);
		return $objective;
	}
	public static function generate_default_per_objective_code() {
		$objective_template = file_get_contents(dirname(__FILE__) . '/task-definition-template.php');
		$seek_type_template = file_get_contents(dirname(__FILE__) . '/task-definition-seek-type-template.php');
		$code = self::generate_per_objective_code('--id--', ProgressAllyPostObjective::$default_objective_setting, $objective_template, $seek_type_template, '--select-page-options--', '--select-note-options--');
		$code = str_replace('{{setting-key}}', 'objectives', $code);
		return $code;
	}
	public static function show_objective_meta_box($meta, $note_meta) {
		$objective_template = file_get_contents(dirname(__FILE__) . '/task-definition-template.php');
		$seek_type_template = file_get_contents(dirname(__FILE__) . '/task-definition-seek-type-template.php');
		$select_page_options = ProgressAllyBackendShared::get_all_post_page_selection_template();
		$select_note_options = ProgressAllyNote::get_note_selection_template($note_meta);
		$objectives = '';
		foreach ($meta['objective-order'] as $id) {
			$setting = $meta['objectives'][$id];
			$objectives .= self::generate_per_objective_code($id, $setting, $objective_template, $seek_type_template, $select_page_options, $select_note_options);
		}
		$objectives = str_replace('{{setting-key}}', 'objectives', $objectives);
		$current_max_id = 0;
		if (!empty($meta['objectives'])) {
			$current_max_id = max(array_keys($meta['objectives']));
			$current_max_id = max($current_max_id, $meta['max-objective']);
		}
		$objective_error_message = $meta['objective-error'];
		$completion_popup_selection = ProgressAllyBackendShared::generate_popup_selection_code($meta['completion-popup']);
		
		$display_code = file_get_contents(dirname(__FILE__) . '/task-definition-display.php');

		$display_code = ProgressAllyBackendShared::replace_real_values($display_code, $meta, '', false);
		$display_code = ProgressAllyBackendShared::replace_all_toggle($display_code, $meta);

		$display_code = str_replace('{{current-max-id}}', $current_max_id, $display_code);
		$display_code = str_replace('{{objectives}}', $objectives, $display_code);
		$display_code = str_replace('{{objective-error-message}}', $objective_error_message, $display_code);
		$display_code = str_replace('{{completion-popup-selection}}', $completion_popup_selection, $display_code);

		$hide_popup_selection = '0' === ProgressAllyBackendShared::get_popup_selection_template();
		$display_code = ProgressAllyBackendShared::generate_display_code($display_code, '{{has-valid-popup-selection}}', !$hide_popup_selection);
		$display_code = ProgressAllyBackendShared::generate_display_code($display_code, '{{no-valid-popup-selection}}', $hide_popup_selection);

		// default to preserve the existing value, so that the settings won't be affected if AccessAlly was (temporarily) disabled
		$custom_operation_selection = '<option selected="selected" value="' . esc_attr($meta['completion-custom-operation']) . '"></option>';
		if (class_exists('AccessAllyAPI') && method_exists('AccessAllyAPI', 'get_all_custom_operations')) {
			$display_code = str_replace('{{show-completion-custom-operation}}', '', $display_code);

			$selected_field_operation = intval($meta['completion-custom-operation']);
			$custom_operation_selection = '';
			$all_custom_operation_settings = AccessAllyAPI::get_all_custom_operations();
			foreach ($all_custom_operation_settings['operations'] as $operation_id => $operation_config) {
				$custom_operation_selection .= '<option value="' . esc_attr($operation_id) . '"';
				if (intval($operation_id) === $selected_field_operation) {
					$custom_operation_selection .= ' selected="selected"';
				}
				$custom_operation_selection .= '>' . esc_attr($operation_id . '. ' . $operation_config['name']) . '</option>';
			}
		}
		$display_code = str_replace('{{show-completion-custom-operation}}', 'style="display:none"', $display_code);

		$display_code = str_replace('{{completion-custom-operation-selection}}', $custom_operation_selection, $display_code);

		return $display_code;
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="backend tagging settings">
	public static function show_tagging_meta_box($meta) {
		$display_code = file_get_contents(dirname(__FILE__) . '/tagging-display.php');

		if ('0' === ProgressAllyBackendShared::get_tag_selection_template()) {	// not linked to a valid CRM
			$display_code = str_replace('{{no-valid-tag-selection}}', '', $display_code);
			$display_code = str_replace('{{has-valid-tag-selection}}', 'style="display:none"', $display_code);
		} else {
			$display_code = str_replace('{{no-valid-tag-selection}}', 'style="display:none"', $display_code);
			$display_code = str_replace('{{has-valid-tag-selection}}', '', $display_code);
		}
		$tag_selection = ProgressAllyBackendShared::get_tag_selection_template();
		$display_code = str_replace('{{objective-completion-tag-selection}}', $tag_selection, $display_code);

		$all_tag_id_mapping = ProgressAllyBackendShared::get_tag_id_mapping();

		$existing_tags = '';
		foreach ($meta['complete-tag'] as $tag_id) {
			if (isset($all_tag_id_mapping[$tag_id])) {
				$existing_tags .= '<div class="progressally-tag"><div class="progressally-tag-delete">&#x2715;</div><div class="progressally-tag-name">' . esc_attr($all_tag_id_mapping[$tag_id]) . '</div>' .
				'<input type="hidden" value="' . esc_attr($tag_id) . '" progressally-param="[complete-tag][]" /></div>';
			} else {
				$existing_tags .= '<div class="progressally-tag progressally-tag-invalid" progressally-tooltip="Invalid tag: tag name does not match existing tags in the system."><div class="progressally-tag-delete">&#x2715;</div><div class="progressally-tag-name">' . esc_attr($tag_id) . '</div>' .
				'<input type="hidden" value="' . esc_attr($tag_id) . '" progressally-param="[complete-tag][]" /></div>';
			}
		}
		$display_code = str_replace('{{existing-complete-tags}}', $existing_tags, $display_code);

		$display_code = str_replace('{{facebook-share-tag-selection}}', ProgressAllyBackendShared::generate_tag_selection_code($meta['fb-automation-tag']), $display_code);

		$display_code = preg_replace('/s--selected-.*?--d/', '', $display_code);
		return $display_code;
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="generate backend main settings">
	public static function show_post_task_definition_meta_box($post) {
		$post_id = $post->ID;
		$meta = self::get_post_progress_meta($post_id);
		$note_meta = ProgressAllyNote::get_post_note_meta($post_id);

		include dirname(__FILE__) . '/post-setting-main-display.php';
	}
	// </editor-fold>

	public static function save_postdata($post_id) {
		// Check if our nonce is set.
		if ((defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE) ||
				wp_is_post_revision($post_id) ||
				!isset($_POST['progressally_task_definition_custom_box_nonce']) ||
				!wp_verify_nonce($_POST['progressally_task_definition_custom_box_nonce'], 'progressally_task_definition_custom_box')) {
			return $post_id;
		}

		if (isset($_POST[self::META_KEY_TASK_DEFINITION]) && is_string($_POST[self::META_KEY_TASK_DEFINITION]) && !empty($_POST[self::META_KEY_TASK_DEFINITION])) {
			$post_meta = ProgressAllyBackendShared::convert_setting_string_to_array($_POST[self::META_KEY_TASK_DEFINITION]);
			if (is_array($post_meta) && !empty($post_meta) && isset($post_meta['max-objective'])) {
				self::set_post_progress_meta($post_meta, $post_id);
			}
		}

		ProgressAllyNote::save_post_note_meta($post_id);
		ProgressAllyCertificate::save_post_certificate_meta($post_id);
	}

	public static function get_post_meta_table() {
		global $wpdb;
		$meta_key = self::META_KEY_TASK_DEFINITION;
		$post_meta_raw = $wpdb->get_results("SELECT meta_id, post_id, meta_value FROM $wpdb->postmeta WHERE meta_key = '$meta_key'", ARRAY_A);
		if (!is_array($post_meta_raw)) {
			return null;
		}
		
		$post_meta_table = array();
		foreach($post_meta_raw as $post_meta) {
			$post_meta_table[$post_meta['post_id']] = maybe_unserialize($post_meta['meta_value']);
		}
		return $post_meta_table;
	}

	private static $cached_progress_meta = array();
	public static function get_post_progress_meta($post_id) {
		if (!isset(self::$cached_progress_meta[$post_id])) {
			$meta = wp_cache_get(self::META_KEY_TASK_DEFINITION, $post_id);
			if ($meta === false) {
				$meta = get_post_meta($post_id, self::META_KEY_TASK_DEFINITION, true);
				if (!is_array($meta)) {
					$meta = self::$default_tasks_meta;
				}

				wp_cache_set(self::META_KEY_TASK_DEFINITION, $meta, $post_id, time() + ProgressAlly::CACHE_PERIOD);
			}
			$objectives_meta = ProgressAllyPostObjective::get_objectives($post_id);
			$meta['objectives'] = $objectives_meta;
			self::$cached_progress_meta[$post_id] = self::merge_default_settings($meta);
		}

		return self::$cached_progress_meta[$post_id];
	}
	private static function merge_default_settings($settings) {
		if (!isset($settings['quiz'])) {
			$settings['quiz'] = ProgressAllyQuiz::$default_quiz_settings;
		} else {
			$settings['quiz'] = ProgressAllyQuiz::merge_default_settings($settings['quiz']);
		}
		$settings = ProgressAllySocialSharing::merge_default_settings($settings);

		$settings = wp_parse_args($settings, self::$default_tasks_meta);
		// backwards compatbility: if the use access tag checkbox is not checked, then set the access tag selection to empty.
		if (isset($settings['checked-use-access-tag']) && 'yes' !== $settings['checked-use-access-tag']) {
			$settings['access-tag'] = '';
		}
		// backwards compatibility: if access-tag is set, then add it to the complete-tag array
		if (isset($settings['access-tag']) && !empty($settings['access-tag']) && empty($settings['complete-tag'])) {
			$settings['complete-tag'] = array($settings['access-tag']);
		}

		// decide whether to create / reset the order to the default order (purely based on objective id in increase order)
		$need_to_reset_order = false;
		if (!isset($settings['objective-order']) || !is_array($settings['objective-order']) || count($settings['objective-order']) !== count($settings['objectives'])) {
			$need_to_reset_order = true;
		}
		if (!$need_to_reset_order) {
			foreach($settings['objective-order'] as $objective_id) {
				if (!isset($settings['objectives'][$objective_id])) {
					$need_to_reset_order = true;
					break;
				}
			}
		}
		if ($need_to_reset_order) {
			$all_objective_ids = array_keys($settings['objectives']);
			sort($all_objective_ids);
			$settings['objective-order'] = $all_objective_ids;
		}
		return $settings;
	}
	public static function get_post_objectives_in_order($post_id) {
		$post_meta = self::get_post_progress_meta($post_id);
		$result = array();
		foreach($post_meta['objective-order'] as $objective_id) {
			$result[$objective_id] = $post_meta['objectives'][$objective_id];
		}
		return $result;
	}
	private static function set_post_progress_meta($meta, $post_id) {
		$meta = self::merge_default_settings($meta);
		unset($meta['objectives']['--id--']);
		
		// Update wp_post_objective database table
		$result = ProgressAllyPostObjective::update_objectives($post_id, $meta['objectives']);
		if ($result !== true) {
			$meta['objective-error'] = $result;
		}
		unset($meta['objectives']);
		
		// Update quiz counters if needed
		ProgressAllyQuiz::maybe_reset_quiz_stats_counter($meta, $post_id);
		
		update_post_meta($post_id, self::META_KEY_TASK_DEFINITION, $meta);
		wp_cache_set(self::META_KEY_TASK_DEFINITION, $meta, $post_id, time() + ProgressAlly::CACHE_PERIOD);
	}
	
	// <editor-fold defaultstate="collapsed" desc="Export and import">
	public static function show_import_export_meta_box($post_id) {
		$nonce_download_url = add_query_arg(array('export-progressally-nonce' => wp_create_nonce("progressally-export"),
									'post-id' => $post_id,
									'action' => 'progressally_task_definition_export'
								), admin_url('admin-ajax.php'));
		include (dirname(__FILE__) . '/import-export-display.php');
	}
	public static function process_export() {
		if (isset($_REQUEST['post-id']) && isset($_REQUEST['export-progressally-nonce']) && wp_verify_nonce($_REQUEST['export-progressally-nonce'], "progressally-export")) {
			$post_id = intval($_REQUEST['post-id']);
			set_time_limit(0);

			$progress_meta = self::get_post_progress_meta($post_id);
			$note_meta = ProgressAllyNote::get_post_note_meta($post_id);
			$certificate_meta = ProgressAllyCertificate::get_post_certificate_meta($post_id);
			$filename = "ProgressAlly Task Definition - " . $post_id . ".progressally";

			header('Content-Type: text/csv');
			header(sprintf('Content-Disposition: attachment; filename="%s"', $filename));

			$result = array('progress_meta' => $progress_meta, 'note_meta' => $note_meta, 'certificate_meta' => $certificate_meta);
			echo json_encode(ProgressAllyUtilities::replace_json_safe_string($result));
			exit;
		}
	}
	public static function generate_import_code_callback() {
		$nonce = $_POST['nonce'];

		if (!wp_verify_nonce( $nonce, 'progressally-update-nonce')) {
			echo json_encode(array('error' => 'Setting page is outdated/not valid'));
			die();
		}
		try{
			$setting_string = $_POST['setting'];
			$setting_string = urldecode($setting_string);
			$setting_string = str_replace("\\'", "'", $setting_string);

			$setting = json_decode($setting_string, true);
			if (json_last_error()) {
				throw new Exception("Invalid .progressally file. Please make sure the file was not modified after exporting from ProgressAlly.");
			}
			$progress_meta = $note_meta = $certificate_meta = false;
			if (isset($setting['progress_meta'])) {
				$progress_meta = self::merge_default_settings($setting['progress_meta']);
			}
			if (isset($setting['note_meta'])) {
				$note_meta = ProgressAllyNote::merge_default_settings($setting['note_meta']);
			}
			if (isset($setting['certificate_meta'])) {
				$certificate_meta = ProgressAllyCertificate::merge_default_settings($setting['certificate_meta']);
			}
			
			$post_id = $_POST['pid'];
			$selection_string = $_POST['selection'];
			$selection_string = urldecode($selection_string);
			$selection = explode(',', $selection_string);

			$result = self::generate_import_code_core($selection, $progress_meta, $note_meta, $certificate_meta, $post_id);
			echo json_encode(array('status' => true, 'codes' => $result));
		} catch (Exception $e) {
			echo json_encode(array('status' => false, 'error' => $e->getMessage()));
		}
		die();
	}
	private static function generate_import_code_core($selection, $progress_meta, $note_meta, $certificate_meta, $post_id) {
		$result = array();
		if (is_array($progress_meta)) {
			if (in_array('objective', $selection)) {
				$result['objective'] = self::show_objective_meta_box($progress_meta, $note_meta);
			}
			if (in_array('social', $selection)) {
				$result['social'] = ProgressAllySocialSharing::show_social_sharing_meta_box($progress_meta);
			}
			if (in_array('tagging', $selection)) {
				$result['tagging'] = self::show_tagging_meta_box($progress_meta);
			}
			if (in_array('quiz', $selection)) {
				$result['quiz'] = ProgressAllyQuiz::show_quiz_meta_box($progress_meta, $post_id);
			}
		}
		if (is_array($note_meta)) {
			if (in_array('note', $selection)) {
				$result['note'] = ProgressAllyNote::show_note_meta_box($post_id, $progress_meta, $note_meta);
			}
		}
		if (is_array($certificate_meta)) {
			if (in_array('certificate', $selection)) {
				$result['certificate'] = ProgressAllyCertificate::show_certificate_meta_box($post_id, $certificate_meta);
			}
		}
		return $result;
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="Evaluate whether an objective can be updated by user">
	public static function can_user_update_this_objective($all_objectives, $key) {
		if (isset($all_objectives[$key])) {
			$objective_type = $all_objectives[$key]['seek-type'];
			if ('none' === $objective_type) {
				return true;
			}
			if ('youtube' === $objective_type || 'vimeo' === $objective_type || 'wistia' === $objective_type){
				if (isset($all_objectives[$key]['checked-complete-video']) && 'yes' === $all_objectives[$key]['checked-complete-video']) {
					return false;
				}
				return true;
			}
		}
		return false;
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="Evaluate whether an objective can be updated via ajax">
	public static function can_ajax_update_this_objective($all_objectives, $key) {
		// allows video completion objectives to be updated via ajax
		if (isset($all_objectives[$key])) {
			$objective_type = $all_objectives[$key]['seek-type'];
			if ('none' === $objective_type) {
				return true;
			}
			if ('youtube' === $objective_type || 'vimeo' === $objective_type || 'wistia' === $objective_type){
				return true;
			}
		}
		return false;
	}
	// </editor-fold>
}