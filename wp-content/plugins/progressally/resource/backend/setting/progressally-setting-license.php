<?php
class ProgressAllySettingLicense {
	const SETTING_KEY_LICENSE = '_progressally_setting_license';
	const SETTING_KEY_ENABLED = '_progressally_setting_enabled';

	// <editor-fold defaultstate="collapsed" desc="activation setup / deactivation cleanup">
	public static function do_activation_actions(){
		delete_transient(self::SETTING_KEY_LICENSE);
		delete_transient(self::SETTING_KEY_ENABLED);
	}
	public static function do_deactivation_actions(){
		delete_transient(self::SETTING_KEY_LICENSE);
		delete_transient(self::SETTING_KEY_ENABLED);
	}
	// </editor-fold>

	public static $progressally_enabled = false;
	private static $default_license_settings = null;
	private static $default_enabled_settings = null;

	public static function initialize_defaults() {
		self::$default_license_settings = array('email' => '', 'serial' => '');
		self::$default_enabled_settings = array('enabled' => false);
	}

	public static function check_license_status() {
		$enabled = self::get_enabled_settings();
		if (!is_array($enabled)) {
			ProgressAllyUpdater::get_plugin_update(true);
			$enabled = self::get_enabled_settings();
		}
		if (!is_array($enabled)) {
			self::$progressally_enabled = false;
		} else {
			self::$progressally_enabled = $enabled['enabled'];
		}
	}

	public static function get_license_settings() {
		return ProgressAllyUtilities::get_settings(self::SETTING_KEY_LICENSE, self::$default_license_settings);
	}

	public static function get_enabled_settings() {
		return ProgressAllyUtilities::get_settings(self::SETTING_KEY_ENABLED, self::$default_enabled_settings);
	}

	public static function show_license_settings() {
		$license = self::get_license_settings();

		include dirname(__FILE__) . '/progressally-setting-license-display.php';
	}

	public static function sanitize_license_settings($input, $selected) {
		$force = $selected['selected-tab'] === 'license';
		$input = $input[self::SETTING_KEY_LICENSE];
		$input['email'] = trim($input['email']);
		$input['serial'] = trim($input['serial']);
		if ($force || $input['old-email'] !== $input['email'] || $input['old-serial'] != $input['serial']) {
			ProgressAllyUpdater::get_plugin_update(true, $input);
		}
		unset($input['old-email']);
		unset($input['old-serial']);
		ProgressAllyUtilities::set_settings(self::SETTING_KEY_LICENSE, $input, self::$default_license_settings);
	}

	public static function set_enable_setting($is_enabled = false) {
		$enabled = array('enabled' => $is_enabled);
		ProgressAllyUtilities::set_settings(self::SETTING_KEY_ENABLED, $enabled, self::$default_enabled_settings);
	}
}
