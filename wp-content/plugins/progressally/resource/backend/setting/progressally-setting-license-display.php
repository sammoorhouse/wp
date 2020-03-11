<div class="progressally-setting-section">
	<input type="hidden" name="<?php echo ProgressAlly::SETTING_KEY_GENERAL . '[' . ProgressAllySettingLicense::SETTING_KEY_LICENSE; ?>][old-email]" value="<?php echo esc_attr($license['email']); ?>"/>
	<input type="hidden" name="<?php echo ProgressAlly::SETTING_KEY_GENERAL . '[' . ProgressAllySettingLicense::SETTING_KEY_LICENSE; ?>][old-serial]" value="<?php echo esc_attr($license['serial']); ?>"/>
	<div class="progressally-setting-section-header">License Information</div>
	<div class="progressally-setting-configure-block">
		<div class="progressally-setting-section-sub-header">Registered Email</div>
		<div class="progressally-setting-configure-block">
			<input class="full-width" type="text" name="<?php echo ProgressAlly::SETTING_KEY_GENERAL . '[' . ProgressAllySettingLicense::SETTING_KEY_LICENSE; ?>][email]" value="<?php echo esc_attr($license['email']); ?>" />
		</div>
	</div>
	<div class="progressally-setting-configure-block">
		<div class="progressally-setting-section-sub-header">Serial Key</div>
		<div class="progressally-setting-configure-block">
			<input class="full-width" type="text" name="<?php echo ProgressAlly::SETTING_KEY_GENERAL . '[' . ProgressAllySettingLicense::SETTING_KEY_LICENSE; ?>][serial]" value="<?php echo esc_attr($license['serial']); ?>" />
		</div>
	</div>
</div>