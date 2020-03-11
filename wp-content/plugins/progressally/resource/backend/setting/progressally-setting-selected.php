<?php
class ProgressAllySettingSelected {
	private static $default_selected_settings = null;

	public static function initialize_defaults() {
		self::$default_selected_settings = array('selected-tab' => 'license');
	}

	public static function get_selected_settings() {
		return ProgressAllyUtilities::get_settings(ProgressAlly::SETTING_KEY_GENERAL, self::$default_selected_settings);
	}
	public static function sanitize_selected_settings($input) {
		return ProgressAllyUtilities::set_settings(ProgressAlly::SETTING_KEY_GENERAL, $input['select'], self::$default_selected_settings);
	}
}
