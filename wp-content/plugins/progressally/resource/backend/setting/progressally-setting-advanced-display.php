<div class="progressally-setting-section">
	<div class="progressally-setting-section-header">Menu completed icon option</div>
	<div class="progressally-setting-section-help-text">In rare cases, the completed icon added to menus by ProgressAlly might interfere with the menu text display. Switch to the alternative method ONLY when absolutely necessary.</div>
	<div class="progressally-setting-configure-block">
		<label for="progressally-advanced-select-menu-mode">Completed icon addition method</label>
		<select id="progressally-advanced-select-menu-mode" name="<?php echo ProgressAlly::SETTING_KEY_GENERAL . '[' . ProgressAllySettingAdvanced::SETTING_KEY_ADVANCED; ?>][select-menu-mode]">
			<option value="default" <?php echo ($advanced_settings['select-menu-mode'] === 'default') ? 'selected="selected"' : ''; ?>>Default Method</option>
			<option value="alternative" <?php echo ($advanced_settings['select-menu-mode'] === 'alternative') ? 'selected="selected"' : ''; ?>>Alternative Method</option>
			<option value="none" <?php echo ($advanced_settings['select-menu-mode'] === 'none') ? 'selected="selected"' : ''; ?>>Do not add completed icon</option>
		</select>
	</div>
</div>
<div class="progressally-setting-section">
	<div class="progressally-setting-section-header">Certificate preview loading</div>
	<div class="progressally-setting-section-help-text">Some server setup prevents the certificate preview (PDF) from being loaded using the default method. Only enable this workaround when absolutely necessary.</div>
	<div class="progressally-setting-configure-block">
		<input type="checkbox" id="progressally-advanced-checked-certificate-preview-loading" value="yes"
			<?php echo $advanced_settings['checked-certificate-preview-ajax'] === 'yes' ? 'checked="checked"' : ''; ?>
			name="<?php echo ProgressAlly::SETTING_KEY_GENERAL . '[' . ProgressAllySettingAdvanced::SETTING_KEY_ADVANCED; ?>][checked-certificate-preview-ajax]" />
		<label for="progressally-advanced-checked-certificate-preview-loading">Enable AJAX certificate preview loading</label>
	</div>
</div>
<div class="progressally-setting-section">
	<div class="progressally-setting-section-header">Page view tracking</div>
	<div class="progressally-setting-section-help-text">ProgressAlly tracks when a user visits a page. It is NOT recommended to disable tracking unless you are absolutely certain.</div>
	<div class="progressally-setting-configure-block">
		<input type="checkbox" id="progressally-advanced-checked-disable-tracking" value="yes"
			<?php echo $advanced_settings['checked-disable-tracking'] === 'yes' ? 'checked="checked"' : ''; ?>
			name="<?php echo ProgressAlly::SETTING_KEY_GENERAL . '[' . ProgressAllySettingAdvanced::SETTING_KEY_ADVANCED; ?>][checked-disable-tracking]" />
		<label for="progressally-advanced-checked-disable-tracking">Disable page view tracking</label>
	</div>
</div>