<?php
class ProgressAllySettingNotes {
	const SETTING_KEY = '_progressally_setting_notes';

	public static function show_settings() {
		if (!current_user_can('manage_options')) {
			wp_die('You do not have sufficient permissions to access this page.');
		}
		include (dirname(__FILE__) . '/progressally-setting-notes-main-display.php');
	}
}
