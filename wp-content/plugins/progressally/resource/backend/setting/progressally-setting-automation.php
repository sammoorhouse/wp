<?php
class ProgressAllySettingAutomation {
	const SETTING_KEY_AUTOMATION = '_progressally_setting_automation';

	private static $default_automation_settings = array('select-crm' => 'accessally',
		'active-campaign-url' => '', 'active-campaign-key' => '',
		'convertkit-key' => '', 'convertkit-secret' => '',
		'ontraport-app' => '', 'ontraport-key' => '',
		'infusionsoft-app' => '', 'infusionsoft-key' => '',
		'drip-account' => '', 'drip-key' => '');

	// <editor-fold defaultstate="collapsed" desc="activation setup / deactivation cleanup">
	public static function do_activation_actions(){
		delete_transient(self::SETTING_KEY_AUTOMATION);
	}
	public static function do_deactivation_actions(){
		delete_transient(self::SETTING_KEY_AUTOMATION);
	}
	// </editor-fold>

	private static $cached_automation_settings = null;
	public static function get_automation_settings() {
		if (null === self::$cached_automation_settings) {
			$automation_settings = ProgressAllyUtilities::get_settings(self::SETTING_KEY_AUTOMATION, self::$default_automation_settings);
			$is_accessally_enabled = class_exists('AccessAllySettingLicense') && AccessAllySettingLicense::$accessally_enabled;
			if ('accessally' === $automation_settings['select-crm']) {
				if (!$is_accessally_enabled) {
					$automation_settings['select-crm'] = '';
				}
			}
			self::$cached_automation_settings = $automation_settings;
		}
		return self::$cached_automation_settings;
	}

	public static function show_automation_settings() {
		$automation_settings = self::get_automation_settings();

		$is_accessally_enabled = class_exists('AccessAllySettingLicense') && AccessAllySettingLicense::$accessally_enabled;
		include dirname(__FILE__) . '/progressally-setting-automation-display.php';
	}

	public static function sanitize_automation_settings($input, $selected) {
		$automation_settings = $input[self::SETTING_KEY_AUTOMATION];
		self::$cached_automation_settings = ProgressAllyUtilities::set_settings(self::SETTING_KEY_AUTOMATION, $automation_settings, self::$default_automation_settings);

		ProgressAllyMembershipUtilities::test_client($automation_settings);

		if (ProgressAllyMembershipUtilities::api_is_set()) {
			ProgressAllyMembershipUtilities::refresh_client();
		}
	}
}
