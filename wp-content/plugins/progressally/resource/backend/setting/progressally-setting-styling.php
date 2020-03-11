<?php
class ProgressAllySettingStyling {
	const SETTING_KEY_STYLING_GENERAL = '_progressally_setting_styling_general';
	const SETTING_KEY_STYLING_ADVANCE = '_progressally_setting_styling';
	const SETTING_KEY_NUM_SAVED = '_progressally_setting_styling_num_saved';

	public static $default_styling_objectives = array(1 => array('description' => 'Objective 1', 'seek-type' => 'none', 'seek-id' => '0', 'seek-time' => '0'),
		2 => array('description' => 'Video Objective 2', 'seek-type' => 'youtube', 'seek-id' => '0', 'seek-time' => '0'),
	);

	private static $default_styling_settings_general = null;
	private static $default_styling_settings_advance = null;

	public static function initialize_defaults() {
		self::$default_styling_settings_general = array('template' => 'Teal', 'custom-css' => '');
		self::$default_styling_settings_general['custom-template-settings'] = ProgressAllyStylingTemplates::get_template_setting('Teal');
	}
	
	public static function do_activation_actions() {
		delete_transient(self::SETTING_KEY_STYLING_GENERAL);
		delete_transient(self::SETTING_KEY_STYLING_ADVANCE);
		delete_transient(self::SETTING_KEY_NUM_SAVED);
	}

	public static function do_deactivation_actions() {
		delete_transient(self::SETTING_KEY_STYLING_GENERAL);
		delete_transient(self::SETTING_KEY_STYLING_ADVANCE);
		delete_transient(self::SETTING_KEY_NUM_SAVED);
	}

	// because the generation process is rather heavy weight, we only want to generate the code when needed
	private static function get_default_advanced_styling_settings() {
		if (null === self::$default_styling_settings_advance) {
			self::$default_styling_settings_advance = ProgressAllyStylingTemplates::generate_styling_css(ProgressAllyStylingTemplates::get_template_setting('Teal'));
		}
		return self::$default_styling_settings_advance;
	}

	public static function get_styling_settings() {
		$settings_general = self::get_styling_settings_general();
		$template_name = $settings_general['template'];
		if ($template_name === 'Advance') {
			return self::get_styling_settings_advance();
		} else {
			if ($template_name === 'Custom') {
				$template_setting = $settings_general['custom-template-settings'];
			} else {
				$template_setting = ProgressAllyStylingTemplates::get_template_setting($template_name);
			}
			return ProgressAllyStylingTemplates::generate_styling_css($template_setting);
		}
	}
	
	public static function get_custom_css_value() {
		$settings_general = self::get_styling_settings_general();
		if (!empty($settings_general['custom-css'])) {
			return $settings_general['custom-css'];
		}
		return '';
	}

	private static function get_styling_settings_general() {
		$settings = ProgressAllyUtilities::get_settings(self::SETTING_KEY_STYLING_GENERAL, self::$default_styling_settings_general);
		$settings = self::sanitize_custom_template_settings($settings);
		return $settings;
	}

	private static function get_styling_settings_advance() {
		return ProgressAllyUtilities::get_settings(self::SETTING_KEY_STYLING_ADVANCE, self::get_default_advanced_styling_settings());
	}

	public static function show_styling_settings_css() {
		$styling_general = self::get_styling_settings_general();
		$setting_key_general = ProgressAlly::SETTING_KEY_GENERAL . '[' . ProgressAllySettingStyling::SETTING_KEY_STYLING_GENERAL . '][';

		$styling_advance = self::get_styling_settings_advance();
		$setting_key_advance = ProgressAlly::SETTING_KEY_GENERAL . '[' . ProgressAllySettingStyling::SETTING_KEY_STYLING_ADVANCE . '][';
		
		$styling_template = ProgressAllyStylingTemplates::get_template();
		$styling_template_settings = ProgressAllyStylingTemplates::get_template_settings();

		include dirname(__FILE__) . '/progressally-setting-styling-display.php';
	}

	public static function generate_styling_script_file() {
		$css_settings = self::get_styling_settings();
		$code = '';
		foreach ($css_settings as $css) {
			$code .= $css;
		}

		$custom_css = self::get_custom_css_value();
		$code .= $custom_css;

		ProgressAllyUtilities::generate_css_file($code);
	}
	public static function sanitize_styling_settings($input, $selected) {
		$input_general = $input[self::SETTING_KEY_STYLING_GENERAL];
		$input_general = self::sanitize_custom_template_settings($input_general);
		ProgressAllyUtilities::set_settings(self::SETTING_KEY_STYLING_GENERAL, $input_general, self::$default_styling_settings_general);

		$input_advance = $input[self::SETTING_KEY_STYLING_ADVANCE];
		ProgressAllyUtilities::set_settings(self::SETTING_KEY_STYLING_ADVANCE, $input_advance, self::get_default_advanced_styling_settings());

		self::generate_styling_script_file();
		
		self::increment_num_saved();
	}

	private static function increment_num_saved() {
		$num_saved = self::get_num_saved_settings();
		$num_saved += 1;
		update_option(self::SETTING_KEY_NUM_SAVED, $num_saved);
		set_transient(self::SETTING_KEY_NUM_SAVED, $num_saved, ProgressAlly::CACHE_PERIOD);
	}

	public static function get_num_saved_settings() {
		$num = get_transient(self::SETTING_KEY_NUM_SAVED);

		if (false === $num) {
			$num = get_option(self::SETTING_KEY_NUM_SAVED, 0);

			set_transient(self::SETTING_KEY_NUM_SAVED, $num, ProgressAlly::CACHE_PERIOD);
		}
		return $num;
	}
	
	private static function sanitize_custom_template_settings($settings) {
		$settings['custom-template-settings'] = wp_parse_args($settings['custom-template-settings'], self::$default_styling_settings_general['custom-template-settings']);
		return $settings;
	}
}
