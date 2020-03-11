<?php
class ProgressAllySettingAdvanced {
	const SETTING_KEY_ADVANCED = '_progressally_setting_advanced';

	private static $default_advanced_settings = array('select-menu-mode' => 'default', 'checked-certificate-preview-ajax' => 'no',
		'checked-disable-tracking' => 'no');

	// <editor-fold defaultstate="collapsed" desc="activation setup / deactivation cleanup">
	public static function do_activation_actions(){
		delete_transient(self::SETTING_KEY_ADVANCED);
	}
	public static function do_deactivation_actions(){
		delete_transient(self::SETTING_KEY_ADVANCED);
	}
	// </editor-fold>

	private static $cached_advanced_settings = null;
	public static function get_advanced_settings() {
		if (null === self::$cached_advanced_settings) {
			self::$cached_advanced_settings = ProgressAllyUtilities::get_settings(self::SETTING_KEY_ADVANCED, self::$default_advanced_settings);
		}
		return self::$cached_advanced_settings;
	}

	public static function show_advanced_settings() {
		$advanced_settings = self::get_advanced_settings();

		include dirname(__FILE__) . '/progressally-setting-advanced-display.php';
	}

	public static function sanitize_advanced_settings($input, $selected) {
		$advanced_settings = $input[self::SETTING_KEY_ADVANCED];
		self::$cached_advanced_settings = ProgressAllyUtilities::set_settings(self::SETTING_KEY_ADVANCED, $advanced_settings, self::$default_advanced_settings);
	}
}
