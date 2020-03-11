<?php

if (!class_exists('ProgressAllyMceEditor')) {
	class ProgressAllyMceEditor {
		public static function add_actions() {
			if (is_admin()) {
				add_action('admin_print_footer_scripts', array( __CLASS__, 'insert_html' ), 50);
			}
			add_action('wp_print_footer_scripts', array(__CLASS__, 'conditional_print_footer_script'));

			// Elementor widget registration
			add_action('elementor/widgets/widgets_registered', array(__CLASS__, 'register_elementor_widget'));
			add_action('elementor/init', array(__CLASS__, 'register_elementor_category'));
			add_action('elementor/editor/after_enqueue_scripts', array(__CLASS__, 'enqueue_elementor_scripts'));
		}
		public static function register_progressally_theme_block() {
			// register Gutenberg block
			if (function_exists('register_block_type')) {
				ProgressAllyGutenberg::register_gutenberg_block();
			}
			// register BeaverBuilder module
			if (class_exists('FLBuilder')) {
				require_once('progressally-beaverbuilder.php');
				self::register_beaverbuilder_module();
			}
		}
		public static function add_filters() {
			add_filter('mce_external_plugins', array(__CLASS__, 'add_buttons'));
			add_filter('mce_buttons', array(__CLASS__, 'register_buttons'));
			add_filter('mce_external_plugins', array(__CLASS__, 'ensure_progressally_button_is_included'), 99999);	// Beaver Builder filter priority is 9999
		}
		public static function add_buttons($plugin_array) {
			$plugin_array['progressally'] = ProgressAlly::$PLUGIN_URI . 'resource/backend/editor/progressally-mce.js';
			return $plugin_array;
		}
		public static function register_buttons($buttons) {
			array_push($buttons, 'progressally');
			return $buttons;
		}
		public static function ensure_progressally_button_is_included($plugins) {
			foreach ($plugins as $key => $val) {
				if ('progressally' === $key) {
					return $plugins;
				}
			}
			return self::add_buttons($plugins);
		}
		public static function conditional_print_footer_script() {
			if (class_exists('FLBuilderModel') && method_exists('FLBuilderModel', 'is_builder_active') && FLBuilderModel::is_builder_active()) {
				self::insert_html();
			}
		}

		// <editor-fold defaultstate="collapsed" desc="generate objective checkbox list code">
		const OBJECTIVE_CHECKBOX_PARTIAL_DISPLAY_TEMPLATE = '<li id="progressally-mce-objective-list-row-{{oid}}"><input type="checkbox" id="progressally-mce-objective-list-checkbox-{{oid}}" progressally-mce-objective-list-checkbox="{{oid}}"><label for="progressally-mce-objective-list-checkbox-{{oid}}">{{name}}</label></li>';
		private static function generate_individual_partial_display_objective_checkbox_list($objective_id, $objective_settings) {
			$individual_code = self::OBJECTIVE_CHECKBOX_PARTIAL_DISPLAY_TEMPLATE;
			$individual_code = str_replace('{{oid}}', $objective_id, $individual_code);
			$individual_code = str_replace('{{name}}', esc_html($objective_settings['description']), $individual_code);
			return $individual_code;
		}
		const OBJECTIVE_CHECKBOX_COMPLETE_BUTTON_TEMPLATE = '<li id="progressally-mce-complete-button-objective-row-{{oid}}"><input type="checkbox" id="progressally-mce-complete-button-objective-checkbox-{{oid}}" progressally-mce-complete-button-objective-checkbox="{{oid}}" {{checkbox-attr}}><label for="progressally-mce-complete-button-objective-checkbox-{{oid}}" {{label-attr}}>{{name}}</label></li>';
		private static function generate_individual_complete_button_objective_checkbox_list($objective_id, $objective_settings) {
			$individual_code = self::OBJECTIVE_CHECKBOX_COMPLETE_BUTTON_TEMPLATE;
			$individual_code = str_replace('{{oid}}', $objective_id, $individual_code);
			$individual_code = str_replace('{{name}}', esc_html($objective_settings['description']), $individual_code);
			$can_manually_check = true;
			$objective_type = $objective_settings['seek-type'];
			if ('quiz' === $objective_type || 'post' === $objective_type || 'note' === $objective_type) {
				$can_manually_check = false;
			} else if ('vimeo' === $objective_type || 'youtube' === $objective_type || 'wistia' === $objective_type) {
				if (isset($objective_settings['checked-complete-video']) && 'yes' === $objective_settings['checked-complete-video']) {
					$can_manually_check = false;
				}
			}
			if ($can_manually_check) {
				$individual_code = str_replace('{{checkbox-attr}}', '', $individual_code);
				$individual_code = str_replace('{{label-attr}}', '', $individual_code);
			} else {
				$individual_code = str_replace('{{checkbox-attr}}', 'disabled="disabled"', $individual_code);
				$individual_code = str_replace('{{label-attr}}', 'class="progressally-mce-complete-button-disabled-option" progressally-tooltip="This objective cannot be manually checked off"', $individual_code);
			}
			return $individual_code;
		}
		private static function generate_post_objectve_selection_code($post_id) {
			$post_meta = ProgressAllyTaskDefinition::get_post_progress_meta($post_id);
			$complete_button_checkbox_code = '';
			$partial_display_checkbox_code = '';
			foreach ($post_meta['objectives'] as $objective_id => $objective_settings) {
				$complete_button_checkbox_code .= self::generate_individual_complete_button_objective_checkbox_list($objective_id, $objective_settings);
				$partial_display_checkbox_code .= self::generate_individual_partial_display_objective_checkbox_list($objective_id, $objective_settings);
			}
			$social_share_selection_code = '';
			if (!empty($post_meta['social-sharing']['shares'])) {
				foreach ($post_meta['social-sharing']['shares'] as $share_id => $share_setting) {
					$social_share_selection_code .= '<option value="' . $share_id . '">' . $share_id . '. ' . esc_html($share_setting['name']) . '</option>';
				}
			}
			$private_note_selection_code = '';
			$note_meta = ProgressAllyNote::get_post_note_meta($post_id);
			if (!empty($note_meta['notes'])) {
				foreach ($note_meta['notes'] as $note_id => $note_setting) {
					$private_note_selection_code .= '<option value="' . $note_id . '">' . $note_id . '. ' . esc_html($note_setting['name']) . '</option>';
				}
			}

			return array('partial-display' => $partial_display_checkbox_code,
				'complete-button' => $complete_button_checkbox_code,
				'social-share' => $social_share_selection_code,
				'private-note' => $private_note_selection_code);
		}
		// </editor-fold>

		public static function insert_html() {
			$post_selection = '';
			$posts = ProgressAllyBackendShared::get_all_posts_with_progress_meta();
			foreach ($posts as $post) {
				$post_selection .= '<option value="' . $post->ID . '">' . esc_html($post->post_title) . ' (' . $post->ID . ')</option>';
			}
			$allow_attachment = ProgressAllyNotesAttachment::is_attachment_allowed();

			$post_id = get_the_ID();
			$selection_code = array('partial-display' => '', 'complete-button' => '', 'social-share' => '', 'private-note' => '');
			if ($post_id > 0) {
				// generate the code in PHP, because the progressally-post.js script is not loaded on page builders such as BeaverBuilder
				$selection_code = self::generate_post_objectve_selection_code($post_id);
			}
			include(dirname(__FILE__) . '/progressally-mce-insert-dialog.php');
			if (wp_script_is('quicktags')){
			?>
				<script type="text/javascript">
					if (typeof(progressally_insert_callback) === 'function') {
				QTags.addButton('progressally_insert', 'progressally', progressally_insert_callback);
					}
				</script>
			<?php
			}
		}

		// <editor-fold defaultstate="collapsed" desc="Register widget for the Elementor theme">
		public static function register_elementor_widget() {
			$widgets_manager = \Elementor\Plugin::instance()->widgets_manager;

			require_once('progressally-elementor-widget.php');
			$widgets_manager->register_widget_type( new ProgressAllyElementorWidget() );
		}
		public static function register_elementor_category() {
			\Elementor\Plugin::instance()->elements_manager->add_category( 'accessally-widgets', array(
				'title' => __( 'AccessAlly Widgets', 'accessally' ),
				'icon'  => '',
			), 1 );
		}
		public static function enqueue_elementor_scripts() {
			wp_enqueue_style('progressally-elementor-backend', ProgressAlly::$PLUGIN_URI . 'resource/backend/editor/progressally-elementor-backend.css',
				false, ProgressAlly::VERSION);
		}
		// </editor-fold>

		// <editor-fold defaultstate="collapsed" desc="Register module for the BeaverBuilder theme">
		private static function generate_beaverbuilder_type_selection_field() {
			$options = array('' => '');
			$toggle = array();
			foreach (ProgressAllyGutenberg::$shortcode_config as $key => $config) {
				$options[$key] = $config['label'];
				if (!empty($config['input'])) {
					$field_dependency = array();
					foreach ($config['input'] as $additional_input_key) {
						$field_dependency []= 'progressally_additional_input_' . $additional_input_key;
					}
					$toggle[$key]['fields'] = $field_dependency;
				}
			}
			return array(
					'type' => 'select',
					'label' => 'Select the ProgressAlly element to display',
					'default' => '',
					'options' => $options,
					'toggle' => $toggle,
				);
		}
		private static function generate_beaverbuilder_input_fields() {
			$field_param = array(
					'progressally_shortcode_type' => self::generate_beaverbuilder_type_selection_field(),
				);
			foreach (ProgressAllyGutenberg::$additional_input_type_config as $key => $config) {
				$field_options = array(
					'type' => 'text',
					'label' => $config['label'],
					'default' => '',
				);
				$field_param['progressally_additional_input_' . $key] = $field_options;
			}
			return $field_param;
		}
		private static function register_beaverbuilder_module() {
			FLBuilder::register_module('ProgressAllyBeaverBuilderModule', array(
				'setup' => array( // Tab
					'title' => 'Setup', // Tab title
					'sections' => array( // Tab Sections
						'progressally-customization' => array( // Section
							'title' => 'Customize the ProgressAlly element', // Section Title
							'fields' => self::generate_beaverbuilder_input_fields(),
							)
						),
					)
				)
			);
		}
		public static function render_beaverbuilder_module_frontend($settings) {
			try {
				if (empty($settings->progressally_shortcode_type)) {
					return 'Please select an element type to display';
				}
				$shortcode_type = $settings->progressally_shortcode_type;
				if (isset(ProgressAllyGutenberg::$shortcode_config[$shortcode_type])) {
					$config = ProgressAllyGutenberg::$shortcode_config[$shortcode_type];
					$code = $config['code'];
					$current_post_id = get_the_ID();
					$code = str_replace('{{post-id}}', $current_post_id, $code);
					if (!empty($config['input'])) {
						$params = array();
						foreach ($config['input'] as $index) {
							if (property_exists($settings, 'progressally_additional_input_' . $index)) {
								$params []= $settings->{'progressally_additional_input_' . $index};
							} else {
								$params []= '';
							}
						}
						$code = vsprintf($code, $params);
					}
					$code = do_shortcode($code);
					return $code;
				}
				return 'Please select an element type to display';
			} catch (Exception $ex) {
				return $ex->getMessage();
			}
		}
		// </editor-fold>
	}
}