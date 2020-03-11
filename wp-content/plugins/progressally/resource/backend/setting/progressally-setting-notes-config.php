<?php
class ProgressAllySettingNotesConfig {
	const SETTING_KEY_NOTES_CONFIG = '_progressally_setting_notes';

	// <editor-fold defaultstate="collapsed" desc="activation setup / deactivation cleanup">
	public static function do_activation_actions(){
		delete_transient(self::SETTING_KEY_NOTES_CONFIG);
	}
	public static function do_deactivation_actions(){
		delete_transient(self::SETTING_KEY_NOTES_CONFIG);
	}
	// </editor-fold>

	private static $default_notes_config_settings = null;

	private static function get_default_per_note_settings() {
		if (null === self::$default_notes_config_settings) {
			$blog_title = get_bloginfo('name');
			self::$default_notes_config_settings = array(
				'select-attachment-location' => 'none',
				'select-admin-email-freq' => 'live',
				'admin-email-address' => '',
				'admin-email-prefix' => '[' . $blog_title . ' Notes] ',
				'user-email-subject' => '[' . $blog_title . '] You have a new reply!',
				'user-email-content' => <<<'EOT'
<table cellpadding="0" cellspacing="0" border="0" align="center" style="width:100%;max-width:600px">
  <tbody>
    <tr>
      <td style="font-size:1px;line-height:1px" height="10">&nbsp;</td>
    </tr>
    <tr>
      <td><a target="_blank" href="{[post-raw-link]}">Click here</a> to see the reply</td>
    </tr>
    <tr>
      <td style="font-size:1px;line-height:1px" height="20">&nbsp;</td>
    </tr>
    <tr>
      <td style="color:#000000;font-size:24px">New Private Note</td>
    </tr>
    <tr>
      <td style="font-size:1px;line-height:1px" height="20">&nbsp;</td>
    </tr>
    {[note-details]}
    <tr>
      <td><a target="_blank" href="{[post-raw-link]}">Click here</a> to see the reply</td>
    </tr>
  </tbody>
</table>
EOT
				,
				);
		}
		return self::$default_notes_config_settings;
	}
	private static $cached_notes_config = null;
	public static function get_settings() {
		if (null === self::$cached_notes_config) {
			self::$cached_notes_config = ProgressAllyUtilities::get_settings(self::SETTING_KEY_NOTES_CONFIG, self::get_default_per_note_settings());
		}
		return self::$cached_notes_config;
	}

	// <editor-fold defaultstate="collapsed" desc="Show settings">
	public static function show_notes_config_settings() {
		$notes_config = self::get_settings();

		include dirname(__FILE__) . '/progressally-setting-notes-config-display.php';
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="save settings">
	public static function sanitize_note_config_settings($input, $selected) {
		$notes_config = $input[self::SETTING_KEY_NOTES_CONFIG];
		if ('local' === $notes_config['select-attachment-location']) {
			try {
				ProgressAllyNotesAttachment::create_note_attachment_directory();
			} catch (Exception $e) {
			}
		}
		self::$cached_notes_config = ProgressAllyUtilities::set_settings(self::SETTING_KEY_NOTES_CONFIG, $notes_config, self::get_default_per_note_settings());

		ProgressAllyNotesEmail::create_schedule_email_event();
	}
	// </editor-fold>
}