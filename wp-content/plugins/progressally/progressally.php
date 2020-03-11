<?php
/*
 Plugin Name: ProgressAlly
 Plugin URI: https://accessally.com/
 Description: ProgressAlly is the easy way to add gamification and reward students inside your membership sites. You can easily add quizzes, video bookmarks, progress tracking checklists, and social sharing into any WordPress page or post. Watch your word of mouth marketing increase as you build in rewards for your online courses!
 Version: 1.4.4
 Author: AccessAlly
 Author URI: https://accessally.com/
 */

if (!class_exists('ProgressAlly')) {
	class ProgressAlly {
		/// CONSTANTS
		const VERSION = '1.4.4';
		const HELP_URL = 'https://access.accessally.com/progressally/tutorials/';
		const CSS_FOLDER = 'progressally-css';

		// setting keys
		const SETTING_KEY_GENERAL = '_progressally_setting_general';

		// CACHE
		const CACHE_PERIOD = 86400;

		public static $PLUGIN_URI = '';

		public static function init() {
			self::$PLUGIN_URI = plugin_dir_url(__FILE__);
			self::initialize_defaults();
			ProgressAllySettingLicense::check_license_status();
			self::add_actions();
			self::initialize_database_names();

			register_activation_hook(__FILE__, array(__CLASS__, 'do_activation_actions'));
			register_deactivation_hook(__FILE__, array(__CLASS__, 'do_deactivation_actions'));
		}

		// <editor-fold defaultstate="collapsed" desc="Check database version">
		public static function upgrade_database($force = false) {
			/* must be called first because the database version will be updated by the other initialize_defaults calls */
			if ($force || !ProgressAllyBackendShared::is_database_up_to_date()) {
				self::create_database_tables();
				self::convert_database_tables();
				
				ProgressAllySettingStyling::generate_styling_script_file();
				
				ProgressAllyBackendShared::update_database_version();
				ProgressAllyCertificate::create_certificate_directory();
				ProgressAllyQuiz::initialize_quiz_stats_counter();
			}
		}

		private static function create_database_tables() {
			if (!function_exists('dbDelta')) {
				require_once (ABSPATH . '/wp-admin/includes/upgrade.php');
			}

			$queries = array();

			$queries[] = ProgressAllyNote::create_database_table_query();
			$queries[] = ProgressAllyPostObjective::create_database_table_query();
			$queries[] = ProgressAllyUserProgress::create_database_table_query();
			$queries[] = ProgressAllyUserAccessTimestamp::create_database_tables();
			$queries[] = ProgressAllyProcessEvents::create_event_log_database_table();

			dbDelta($queries);
		}
		
		private static function convert_database_tables() {
			global $wpdb;
			$result = $wpdb->get_results("SELECT id FROM $wpdb->pa_post_objective LIMIT 1", ARRAY_A);

			if (empty($result)) {
				// add dummy entry to prevent double conversion threads
				$result = $wpdb->insert($wpdb->pa_post_objective, array('post_id' => 0,
					'objective_id' => 0,
					'mapped_post_id' => 0,
					'objective_type' => 0,
					'meta' => 'dummy entry'
					));
				if (1 === $wpdb->insert_id) {	// only the thread that added the first entry gets to do the conversion.
					ProgressAllyPostObjective::convert_database_table();
					ProgressAllyUserProgress::convert_database_table();
				}
			}
		}
		// </editor-fold>

		// <editor-fold defaultstate="collapsed" desc="SETUP">
		public static function do_activation_actions() {
			delete_transient(self::SETTING_KEY_GENERAL);
			ProgressAllyTaskDefinition::do_activation_actions();
			ProgressAllySettingStyling::do_activation_actions();
			ProgressAllyNotesEmail::do_activation_actions();
			ProgressAllySettingAdvanced::do_activation_actions();
			ProgressAllySettingAutomation::do_activation_actions();
			ProgressAllySettingLicense::do_activation_actions();
			ProgressAllySettingNotesConfig::do_activation_actions();
			ProgressAllyUserProgress::do_activation_actions();
			ProgressAllyNote::do_activation_actions();
			ProgressAllyCertificate::do_activation_actions();
			self::upgrade_database(true);
		}

		public static function do_deactivation_actions() {
			delete_transient(self::SETTING_KEY_GENERAL);
			ProgressAllyTaskDefinition::do_deactivation_actions();
			ProgressAllySettingStyling::do_deactivation_actions();
			ProgressAllyNotesEmail::do_deactivation_actions();
			ProgressAllySettingAdvanced::do_deactivation_actions();
			ProgressAllySettingAutomation::do_deactivation_actions();
			ProgressAllySettingLicense::do_deactivation_actions();
			ProgressAllySettingNotesConfig::do_deactivation_actions();
			ProgressAllyUserProgress::do_deactivation_actions();
			ProgressAllyNote::do_deactivation_actions();
			ProgressAllyCertificate::do_deactivation_actions();
		}
		private static function initialize_database_names() {
			ProgressAllyNote::initialize_database_names();
			ProgressAllyPostObjective::initialize_database_names();
			ProgressAllyUserProgress::initialize_database_names();
			ProgressAllyUserAccessTimestamp::initialize_database_names();
			ProgressAllyProcessEvents::initialize_database_names();
		}

		public static function initialize_defaults(){
			ProgressAllyTaskDefinition::initialize_defaults();
			ProgressAllySettingSelected::initialize_defaults();
			ProgressAllySettingLicense::initialize_defaults();
			ProgressAllySettingStyling::initialize_defaults();
		}

		private static function add_actions() {
			add_action('plugins_loaded', array(__CLASS__, 'upgrade_database'));
			if (is_admin()) {
				add_action('admin_enqueue_scripts', array(__CLASS__, 'enqueue_administrative_resources'));
				
				// add setting menu
				add_action('admin_menu', array(__CLASS__, 'add_menu_pages'));
				add_action('admin_init', array(__CLASS__, 'register_settings'));

				if (ProgressAllySettingLicense::$progressally_enabled) {
					ProgressAllyTaskDefinition::add_actions();
					ProgressAllyPostObjective::add_actions();
					ProgressAllySettingDashboardPage::add_actions();
					ProgressAllySettingDashboardDetail::add_actions();
					ProgressAllySettingNoteReply::add_actions();
					ProgressAllySettingAdminInitNotes::add_actions();
					ProgressAllyEvents::add_actions();
					ProgressAllyEventLog::add_actions();
				}
			}
			if (ProgressAllySettingLicense::$progressally_enabled) {
				add_action('init', array(__CLASS__, 'add_shortcodes'));
				add_action('init', array(__CLASS__, 'add_filters'));
				add_action('init', array('ProgressAllyMceEditor', 'register_progressally_theme_block'));
				add_action('wp_enqueue_scripts', array(__CLASS__, 'register_plugin_scripts'));

				ProgressAllyQuizEvaluation::add_actions();
				ProgressAllyUserProgress::add_actions();
				ProgressAllyUserProfile::add_actions();
				ProgressAllyQuiz::add_actions();
				ProgressAllyNote::add_actions();
				ProgressAllyNotesEmail::add_actions();
				ProgressAllySocialShareAutomation::add_actions();
				ProgressAllyMembershipUtilities::add_actions();
				ProgressAllyCertificate::add_actions();
				ProgressAllyUserAccessTimestamp::add_actions();
				ProgressAllyExternalInteractions::add_actions();
				ProgressAllyNotesAttachment::add_actions();
				ProgressAllyMceEditor::add_actions();
			}
		}
		
		public static function add_filters() {
			$advanced_settings = ProgressAllySettingAdvanced::get_advanced_settings();
			if ($advanced_settings['select-menu-mode'] !== 'none') {
				add_filter('nav_menu_link_attributes', array(__CLASS__, 'add_menu_link_class'), 99, 3);
				add_filter('nav_menu_css_class', array(__CLASS__, 'add_menu_item_class'), 99, 2);
				add_filter('walker_nav_menu_start_el', array(__CLASS__, 'restore_menu_link_class'), 99, 4);
			}
			ProgressAllyMceEditor::add_filters();
		}

		public static function add_shortcodes() {
			add_shortcode( 'progressally_vimeo_video', array(__CLASS__, 'shortcode_progressally_vimeo_video'));
			add_shortcode( 'progressally_youtube_video', array(__CLASS__, 'shortcode_progressally_youtube_video'));
			add_shortcode( 'progressally_wistia_video', array(__CLASS__, 'shortcode_progressally_wistia_video'));
			add_shortcode( 'progressally_objective_completion', array(__CLASS__, 'shortcode_progressally_objective_completion'));
			ProgressAllyProgressDisplay::add_shortcodes();
			ProgressAllyQuizDisplay::add_shortcodes();
			ProgressAllySocialShare::add_shortcodes();
			ProgressAllyNotesShortcode::add_shortcodes();
			ProgressAllyCertificatesShortcode::add_shortcodes();
			ProgressAllyToggleElementShortcode::add_shortcodes();
			ProgressAllyObjectivesShortcode::add_shortcodes();
			ProgressAllyFootprint::add_shortcodes();
		}

		public static function register_plugin_scripts() {
			wp_enqueue_script('progressally-update', plugin_dir_url(__FILE__) . 'resource/frontend/progressally.min.js', array('jquery'), self::VERSION);

			// do not include the http or https protocol in the ajax url
			$admin_url = preg_replace("/^http:/i", "", admin_url('admin-ajax.php'));
			$admin_url = preg_replace("/^https:/i", "", $admin_url);

			$current_user_id = ProgressAllyUserProgress::get_user_id();
			$is_logged_in = '0';
			$user_meta = array();
			if ($current_user_id > 0) {
				$is_logged_in = '1';
				$user_meta = ProgressAllyUserProgress::get_user_progress_toggle_meta($current_user_id);
			}
			global $post;
			$post_id = 0;
			if ($post && property_exists($post, 'ID')) {
				$post_id = $post->ID;
			}
			$advanced_settings = ProgressAllySettingAdvanced::get_advanced_settings();
			wp_localize_script( 'progressally-update', 'progressally_update',
					array( 'ajax_url' => $admin_url, 
						'progressally_update_nonce' => wp_create_nonce('progressally-update-progress-nonce'),
						'user' => $is_logged_in,
						'user_meta' => $user_meta,
						'post_id' => $post_id,
						'video' => ProgressAllyPostObjective::get_video_frontend_data($post_id),
						'disable_track' => $advanced_settings['checked-disable-tracking']
						));

			$num_saved = ProgressAllySettingStyling::get_num_saved_settings();
			if ($num_saved > 0){
				$css_url = content_url(self::CSS_FOLDER) . '/progressally-style.css';
				wp_enqueue_style('progressally-style', $css_url, false, self::VERSION . '.' . $num_saved);
			}
			if (class_exists('FLBuilderModel') && method_exists('FLBuilderModel', 'is_builder_active') && FLBuilderModel::is_builder_active()) {
				ProgressAllyTaskDefinition::enqueue_administrative_resources($admin_url, false);
			}
		}

		public static function enqueue_administrative_resources($hook) {
			// do not include the http or https protocol in the ajax url
			$admin_url = preg_replace("/^http:/i", "", admin_url('admin-ajax.php'));
			$admin_url = preg_replace("/^https:/i", "", $admin_url);

			wp_enqueue_script('progressally-admin-notice', self::$PLUGIN_URI . 'resource/backend/js/admin-notice.min.js', array('jquery'), self::VERSION);

			wp_localize_script('progressally-admin-notice', 'progressally_admin_notice_data_object',
				array('ajax_url' => $admin_url));

			if (strpos($hook, self::SETTING_KEY_GENERAL) !== false) {
				wp_enqueue_style('wp-color-picker');
				wp_register_script('progressally-setting-backend-color-picker', self::$PLUGIN_URI . 'resource/backend/jscolor/jscolor.js', array('jquery'), self::VERSION);

				wp_enqueue_script('progressally-settings-backend', plugin_dir_url(__FILE__) . 'resource/backend/js/progressally-settings.js', array('jquery', 'progressally-setting-backend-color-picker'), self::VERSION);

				wp_localize_script('progressally-settings-backend', 'progressally_settings_object',
					array('color' => ProgressAllyStylingTemplates::$template_color_attributes,
						'literal' => ProgressAllyStylingTemplates::$template_literal_attributes,
						'ajax_url' => $admin_url,
						'update_nonce' => wp_create_nonce('progressally-update-nonce')));

				wp_enqueue_style('progressally-settings-backend', plugin_dir_url(__FILE__) . 'resource/backend/css/progressally-settings.css', array(), self::VERSION);
			} elseif (strpos($hook, ProgressAllySettingNotes::SETTING_KEY) !== false) {
				wp_enqueue_script('progressally-setting-notes', plugin_dir_url(__FILE__) . 'resource/backend/js/progressally-setting-notes.js', array('jquery'), self::VERSION);

				wp_localize_script('progressally-setting-notes', 'progressally_settings_object',
					array('ajax_url' => $admin_url,
						'update_nonce' => wp_create_nonce('progressally-update-nonce')));

				wp_enqueue_style('progressally-setting-notes', plugin_dir_url(__FILE__) . 'resource/backend/css/progressally-setting-notes.css', array(), self::VERSION);
			} elseif (strpos($hook, ProgressAllySettingDashboard::SETTING_KEY) !== false) {
				wp_enqueue_script('progressally-setting-dashboard', plugin_dir_url(__FILE__) . 'resource/backend/js/progressally-setting-dashboard.js', array('jquery', 'jquery-ui-autocomplete'), self::VERSION);

				wp_localize_script('progressally-setting-dashboard', 'progressally_settings_object',
					array('ajax_url' => $admin_url,
						'update_nonce' => wp_create_nonce('progressally-update-nonce')));

				wp_enqueue_style('progressally-setting-dashboard', plugin_dir_url(__FILE__) . 'resource/backend/css/progressally-setting-dashboard.css', array(), self::VERSION);
			} elseif (strpos($hook, ProgressAllyEvents::SETTING_KEY) !== false) {
				wp_register_script('progressally-event-default-code', self::$PLUGIN_URI . 'resource/backend/js/progressally-event-default-code.js', array(), self::VERSION);
				wp_enqueue_script('progressally-events', plugin_dir_url(__FILE__) . 'resource/backend/js/progressally-events.js', array('jquery', 'jquery-ui-autocomplete', 'progressally-event-default-code'), self::VERSION);

				$post_selection = ProgressAllyBackendShared::get_all_post_page_selection_template();
				$post_selection = preg_replace('/s--selected-.*?--d/', '', $post_selection);

				wp_localize_script('progressally-events', 'progressally_events_object',
					array('ajax_url' => $admin_url,
						'tag_selection_code' => ProgressAllyBackendShared::get_tag_selection_template(),
						'page_selection_code' => $post_selection,
						'nonce' => wp_create_nonce('progressally-update-nonce')));

				wp_enqueue_style('progressally-events', plugin_dir_url(__FILE__) . 'resource/backend/css/progressally-events.css', array(), self::VERSION);
			} elseif (in_array($hook, array('post.php', 'post-new.php', 'edit.php', 'user-edit.php', 'profile.php', ''))) {
				ProgressAllyTaskDefinition::enqueue_administrative_resources($admin_url, $hook);
			}
		}
		// </editor-fold>

		// <editor-fold defaultstate="collapsed" desc="Adding menu indicator">
		public static function add_menu_item_class($classes, $item) {
			$classes []= 'progressally-menu-item';
			return $classes;
		}
		private static $original_link_after = '';
		public static function add_menu_link_class($atts, $item, $args) {
			if (isset($atts['class'])) {
				$atts['class'] .= ' progressally-menu-link';
			} else {
				$atts['class'] = 'progressally-menu-link';
			}
			$advanced_settings = ProgressAllySettingAdvanced::get_advanced_settings();
			if ($advanced_settings['select-menu-mode'] === 'alternative') {
				self::$original_link_after = $args->after;
			} else {
				self::$original_link_after = $args->link_after;
			}
			if (is_a($item, 'WP_Post')) {
				$post_id = $item->object_id;
				$progress = ProgressAllyUserProgress::get_progress($post_id);

				if ($progress >= 0) {
					if ($advanced_settings['select-menu-mode'] === 'alternative') {
						$args->after .= self::generate_menu_progress_indicator($progress, $post_id);
					} else {
						$args->link_after .= self::generate_menu_progress_indicator($progress, $post_id);
					}
				}
			}
			return $atts;
		}
		public static function restore_menu_link_class($item_output, $item, $depth, $args) {
			$advanced_settings = ProgressAllySettingAdvanced::get_advanced_settings();
			if ($advanced_settings['select-menu-mode'] === 'alternative') {
				$args->after = self::$original_link_after;
			} else {
				$args->link_after = self::$original_link_after;
			}
			return $item_output;
		}
		public static function generate_menu_progress_indicator($progress, $post_id) {
			$to_show = $progress >= 1 ? '' : " style='display:none;'";
			$post_id = empty($post_id) ? '' : "post-id='$post_id'";

			$code = "<span $to_show class='progressally-status-display menu-completed-icon' $post_id></span>";
			return $code;
		}
		// </editor-fold>

		// <editor-fold defaultstate="collapsed" desc="Shortcode">
		public static function shortcode_progressally_vimeo_video($atts){
			extract( shortcode_atts( array(
				'vimeo_id' => '0',
				'id' => '0',
				'width' => '0',
				'height' => '0',
				'additional_args' => '',
				'prefix' => '',
			), $atts, 'progressally_vimeo_video' ) );
			$additional_args = str_replace('&#038;', '&', $additional_args);
			$iframe_src = "https://player.vimeo.com/video/$vimeo_id";
			if (!empty($additional_args)) {
				$iframe_src .= '?' . $additional_args;
			}
			$dimension_attr = '';
			$dimension_class = 'progressally-video-container-fluid-dimension';
			if (!empty($width)) {
				$dimension_attr .= ' width="' . $width . '"';
				$dimension_class = 'progressally-video-container-fixed-dimension';
			}
			if (!empty($height)) {
				$dimension_attr .= ' height="' . $height . '"';
				$dimension_class = 'progressally-video-container-fixed-dimension';
			}
			
			return '<div class="' . $prefix . 'progressally-video-container progressally-vimeo-video-container ' . $dimension_class . '" progressally-vimeo-id="' . $id . '">' .
				'<iframe src="' . $iframe_src . '" id="progressally_vimeo_' . $id . '" progressally-vimeo-init="' . esc_attr($id) . '"' . $dimension_attr .
				' frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></div>';
		}
		public static function shortcode_progressally_youtube_video($atts){
			extract( shortcode_atts( array(
				'youtube_id' => '0',
				'id' => '0',
				'width' => '0',
				'height' => '0',
				'additional_args' => 'rel=0&showinfo=0',
				'prefix' => '',
			), $atts, 'progressally_youtube_video' ) );
			$additional_args = str_replace('&#038;', '&', $additional_args);
			$iframe_src = "https://www.youtube.com/embed/$youtube_id?&enablejsapi=1&playerapiid=progressally_youtube_$id";
			if (!empty($additional_args)) {
				$iframe_src .= '&' . $additional_args;
			}
			$dimension_attr = '';
			$dimension_class = 'progressally-video-container-fluid-dimension';
			if (!empty($width)) {
				$dimension_attr .= ' width="' . $width . '"';
				$dimension_class = 'progressally-video-container-fixed-dimension';
			}
			if (!empty($height)) {
				$dimension_attr .= ' height="' . $height . '"';
				$dimension_class = 'progressally-video-container-fixed-dimension';
			}
			return '<div class="' . $prefix . 'progressally-video-container progressally-youtube-video-container ' . $dimension_class . '" progressally-youtube-init="' . esc_attr($youtube_id) .
				'" progressally-youtube-id="' . $id . '"' . $dimension_attr . ' arg="' . esc_attr($additional_args) . '">' .
				'<iframe src="' . esc_attr($iframe_src) . '" id="progressally_youtube_' . $id . '" ' . $dimension_attr .
				' frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></div>';
		}
		public static function shortcode_progressally_wistia_video($atts){
			extract( shortcode_atts( array(
				'wistia_id' => '0',
				'id' => '0',
				'width' => '0',
				'height' => '0',
				'additional_args' => '',
				'prefix' => '',
			), $atts, 'progressally_wistia_video' ) );
			$additional_args = str_replace('&#038;', '&', $additional_args);
			$iframe_src = "//fast.wistia.net/embed/iframe/$wistia_id";
			if (!empty($additional_args)) {
				$iframe_src .= '?' . $additional_args;
			}
			$dimension_attr = '';
			$dimension_class = 'progressally-video-container-fluid-dimension';
			if (!empty($width)) {
				$dimension_attr .= ' width="' . $width . '"';
				$dimension_class = 'progressally-video-container-fixed-dimension';
			}
			if (!empty($height)) {
				$dimension_attr .= ' height="' . $height . '"';
				$dimension_class = 'progressally-video-container-fixed-dimension';
			}
			return '<div class="' . $prefix . 'progressally-video-container progressally-wistia-video-container ' . $dimension_class . '" progressally-wistia-id="' . $id . '">' .
				'<iframe class="wistia_embed" name="wistia_embed" src="' . esc_attr($iframe_src) . '" progressally-wistia-init="' . esc_attr($wistia_id) . '" progressally-wistia-video-id="' . $id . '"' . $dimension_attr .
				' frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></div>';
		}
		public static function shortcode_progressally_objective_completion($atts, $content = null) {
			extract( shortcode_atts( array(
				'post_id' => '',
				'percentage' => '100',
				'request' => '',
				'user_id' => '',
				'context' => 'local'
			), $atts, 'progressally_objective_completion' ) );
			$post_id = intval($post_id);
			$post_id = $post_id <= 0 ? get_the_ID(): $post_id;
			$percentage = floatval($percentage);

			$current_user_id = ProgressAllyUserProgress::get_user_id();
			$display_user_id = ProgressAllyBackendShared::get_current_user_id_for_display($user_id, $request, $context, $current_user_id);
			$display_attribute = '';
			if (ProgressAllyUserProgress::get_progress($post_id, $display_user_id) >= $percentage/100) {
				$display_attribute = '';
			} else {
				$display_attribute = ' style="display:none"';
			}
			$update_parameter = '';
			if (intval($current_user_id) === intval($display_user_id)) {	// can only live update if the display user is the same as the current user
				$update_parameter = ' progressally-objective-completion-update="'. $post_id . '" percentage="' . $percentage . '"';
			}
			return '<div' . $update_parameter . $display_attribute .'>' . do_shortcode($content) . '</div>';
		}
		// </editor-fold>

		// <editor-fold defaultstate="collapsed" desc="Settings">
		public static function register_settings() {
			register_setting('progressally_general_settings', self::SETTING_KEY_GENERAL, array(__CLASS__, 'sanitize_general_settings'));
		}

		public static function add_menu_pages() {
			// Add the top-level admin menu
			$capability = 'manage_options';
			$menu_slug = self::SETTING_KEY_GENERAL;
			$plugin_page = add_menu_page('ProgressAlly Settings', 'ProgressAlly', $capability, $menu_slug, array(__CLASS__, 'show_general_settings'), self::$PLUGIN_URI . 'resource/backend/img/progressally-icon.png');
			$results = add_submenu_page(self::SETTING_KEY_GENERAL, 'ProgressAlly Settings', 'General Settings', 'manage_options', self::SETTING_KEY_GENERAL, array(__CLASS__, 'show_general_settings'));

			if (ProgressAllySettingLicense::$progressally_enabled) {
				$results = add_submenu_page(self::SETTING_KEY_GENERAL, 'ProgressAlly Private Notes', 'Private Notes', 'manage_options', ProgressAllySettingNotes::SETTING_KEY, array('ProgressAllySettingNotes', 'show_settings'));
				$results = add_submenu_page(self::SETTING_KEY_GENERAL, 'ProgressAlly Reports', 'Reports', 'manage_options', ProgressAllySettingDashboard::SETTING_KEY, array('ProgressAllySettingDashboard', 'show_settings'));
				$results = add_submenu_page(self::SETTING_KEY_GENERAL, 'ProgressAlly Events', 'Events', 'manage_options', ProgressAllyEvents::SETTING_KEY, array('ProgressAllyEvents', 'show_settings'));

				add_action('admin_head-'.$plugin_page, array(__CLASS__, 'add_preview_scripts'));
			}
		}

		public static function show_general_settings() {
			if (!current_user_can('manage_options')) {
				wp_die('You do not have sufficient permissions to access this page.');
			}
			$selected = ProgressAllySettingSelected::get_selected_settings();

			include (dirname(__FILE__) . '/resource/backend/setting/progressally-setting-main-display.php');
		}

		public static function sanitize_general_settings($input) {
			if (!isset($input['select'])) {
				return $input;
			}
			$selected = ProgressAllySettingSelected::sanitize_selected_settings($input);
			ProgressAllySettingLicense::sanitize_license_settings($input, $selected);
			if (ProgressAllySettingLicense::$progressally_enabled) {
				ProgressAllySettingStyling::sanitize_styling_settings($input, $selected);
				ProgressAllySettingAutomation::sanitize_automation_settings($input, $selected);
				ProgressAllySettingAdvanced::sanitize_advanced_settings($input, $selected);
				ProgressAllySettingNotesConfig::sanitize_note_config_settings($input, $selected);
			}
			add_settings_error('progressally_general', 'settings_updated', 'Settings saved!', 'updated');

			ProgressAllyUtilities::clear_wp_cache();
			return $selected;
		}
		// </editor-fold>

		// <editor-fold defaultstate="collapsed" desc="Utilities">
		public static function add_preview_scripts() {
			$stylings = ProgressAllySettingStyling::get_styling_settings();
			foreach($stylings as $id => $style) {
				echo '<style id="progressally-preview-css-' . $id . '" type="text/css">';
				echo $style;
				echo '</style>';
			}
			$custom_css = ProgressAllySettingStyling::get_custom_css_value();
			if (!empty($custom_css)) {
				echo '<style id="progressally-preview-css-custom-css" type="text/css">';
				echo $custom_css;
				echo '</style>';
			}
		}
		// </editor-fold>
	}

	require_once('updater.php');
	require_once('resource/backend/post/task-definition.php');
	require_once('resource/backend/post/quiz.php');
	require_once('resource/backend/post/note.php');
	require_once('resource/backend/post/certificate.php');
	require_once('resource/backend/post/post-objective.php');
	require_once('resource/backend/post/social-sharing.php');
	require_once('resource/backend/setting/progressally-setting-selected.php');
	require_once('resource/backend/setting/progressally-setting-license.php');
	require_once('resource/backend/setting/progressally-setting-advanced.php');
	require_once('resource/backend/setting/progressally-setting-styling.php');
	require_once('resource/backend/setting/progressally-styling-templates.php');
	require_once('resource/backend/setting/progressally-setting-notes-config.php');
	require_once('resource/backend/setting/progressally-setting-automation.php');
	require_once('resource/backend/notes/progressally-setting-notes.php');
	require_once('resource/backend/notes/progressally-setting-note-reply.php');
	require_once('resource/backend/notes/progressally-setting-admin-init.php');
	require_once('resource/backend/notes/notes-email.php');
	require_once('resource/backend/notes/notes-attachment.php');
	require_once('resource/backend/notes/notes-shared.php');
	require_once('resource/backend/dashboard/progressally-setting-dashboard.php');
	require_once('resource/backend/dashboard/progressally-setting-dashboard-page.php');
	require_once('resource/backend/dashboard/progressally-setting-dashboard-detail.php');
	require_once('resource/backend/user/user-progress.php');
	require_once('resource/backend/user/user-profile.php');
	require_once('resource/backend/user/user-access-timestamp.php');
	require_once('resource/backend/editor/progressally-mce-editor.php');
	require_once('resource/backend/editor/progressally-gutenberg.php');
	require_once('resource/backend/progressally-utilities.php');
	require_once('resource/backend/shared/progressally-backend-shared.php');
	require_once('resource/backend/automation/social-share-automation.php');
	require_once('resource/backend/events/events.php');
	require_once('resource/backend/events/event-log.php');
	require_once('resource/backend/events/process-events.php');
	require_once('resource/backend/external/external-interactions.php');
	require_once('resource/frontend/progress-display.php');
	require_once('resource/frontend/quiz/quiz-display.php');
	require_once('resource/frontend/quiz/quiz-evaluation.php');
	require_once('resource/frontend/shortcode/social-share.php');
	require_once('resource/frontend/shortcode/notes.php');
	require_once('resource/frontend/shortcode/certificates.php');
	require_once('resource/frontend/shortcode/toggle-element.php');
	require_once('resource/frontend/shortcode/objectives.php');
	require_once('resource/frontend/shortcode/footprint.php');
	require_once('lib/membership-utilities.php');
	require_once('lib/active-campaign-utilities.php');
	require_once('lib/convertkit-utilities.php');
	require_once('lib/ontraport-utilities.php');
	require_once('lib/infusion-utilities.php');
	require_once('lib/drip-utilities.php');
	require_once('lib/pdf/pdf-utilities.php');
	ProgressAlly::init();
}
