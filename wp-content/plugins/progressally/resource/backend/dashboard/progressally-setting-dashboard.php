<?php
class ProgressAllySettingDashboard {
	const SETTING_KEY = '_progressally_setting_dashboard';

	public static function show_settings() {
		if (!current_user_can('manage_options')) {
			wp_die('You do not have sufficient permissions to access this page.');
		}
		include (dirname(__FILE__) . '/progressally-setting-dashboard-main-display.php');
	}
}
