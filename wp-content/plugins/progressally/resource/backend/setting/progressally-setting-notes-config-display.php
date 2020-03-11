<div class="progressally-setting-section">
	<div class="progressally-setting-section-header">Private Note Attachments</div>
	<div class="progressally-setting-configure-block">
		<div class="progressally-setting-section-sub-header">Where to store the attachments</div>
		<select name="<?php echo ProgressAlly::SETTING_KEY_GENERAL . '[' . ProgressAllySettingNotesConfig::SETTING_KEY_NOTES_CONFIG; ?>][select-attachment-location]">
			<option value="none" <?php echo ($notes_config['select-attachment-location'] === 'none') ? 'selected="selected"' : ''; ?>>Do not allow attachments</option>
			<option value="local" <?php echo ($notes_config['select-attachment-location'] === 'local') ? 'selected="selected"' : ''; ?>>Store attachments on the local WordPress server</option>
		</select>
	</div>
</div>
<div class="progressally-setting-section">
	<div class="progressally-setting-section-header">Get notified when a member sends you a private note</div>
	<div class="progressally-setting-configure-block">
		<div class="progressally-setting-section-sub-header">How often to send the notification email</div>
		<select name="<?php echo ProgressAlly::SETTING_KEY_GENERAL . '[' . ProgressAllySettingNotesConfig::SETTING_KEY_NOTES_CONFIG; ?>][select-admin-email-freq]">
			<option value="live" <?php echo ($notes_config['select-admin-email-freq'] === 'live') ? 'selected="selected"' : ''; ?>>Send notification as soon as the note is created</option>
			<option value="daily" <?php echo ($notes_config['select-admin-email-freq'] === 'daily') ? 'selected="selected"' : ''; ?>>Send notification once a day</option>
		</select>
	</div>
	<div class="progressally-setting-configure-block">
		<div class="progressally-setting-section-sub-header">Admin emails</div>
		<div class="progressally-setting-section-help-text">delimit multiple email addresses by comma.</div>
		<input type="text" class="full-width" name="<?php echo ProgressAlly::SETTING_KEY_GENERAL . '[' . ProgressAllySettingNotesConfig::SETTING_KEY_NOTES_CONFIG; ?>][admin-email-address]"
			   value="<?php echo esc_attr($notes_config['admin-email-address']); ?>" />
	</div>
	<div class="progressally-setting-email-customization-block">
		<div class="progressally-setting-configure-block">
			<div class="progressally-setting-section-sub-header">Email subject prefix</div>
			<input type="text" class="full-width" name="<?php echo ProgressAlly::SETTING_KEY_GENERAL . '[' . ProgressAllySettingNotesConfig::SETTING_KEY_NOTES_CONFIG; ?>][admin-email-prefix]"
				   value="<?php echo esc_attr($notes_config['admin-email-prefix']); ?>" />
		</div>
	</div>
</div>
<div class="progressally-setting-section">
	<div class="progressally-setting-section-header">Default user notification email</div>
	<div class="progressally-setting-email-customization-block">
		<div class="progressally-setting-configure-block">
			<div class="progressally-setting-section-sub-header">Email subject</div>
			<input type="text" class="full-width" name="<?php echo ProgressAlly::SETTING_KEY_GENERAL . '[' . ProgressAllySettingNotesConfig::SETTING_KEY_NOTES_CONFIG; ?>][user-email-subject]"
				   value="<?php echo esc_attr($notes_config['user-email-subject']); ?>" />
		</div>
		<div class="progressally-setting-configure-block">
			<div class="progressally-setting-section-sub-header">Email content</div>
			<textarea rows="20" class="full-width" name="<?php echo ProgressAlly::SETTING_KEY_GENERAL . '[' . ProgressAllySettingNotesConfig::SETTING_KEY_NOTES_CONFIG; ?>][user-email-content]"><?php echo esc_html($notes_config['user-email-content']); ?></textarea>
		</div>
	</div>
</div>