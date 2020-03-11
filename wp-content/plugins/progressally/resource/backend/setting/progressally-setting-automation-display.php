<div class="progressally-setting-section">
	<div class="progressally-setting-section-header">Customer Relationship Management (CRM) Integration</div>
	<div class="progressally-setting-section-help-text">ProgressAlly can trigger automation through tagging in your CRM platform if configured.</div>
	<div class="progressally-setting-configure-block">
		<label for="progressally-automation-select-crm">CRM System</label>
		<select id="progressally-automation-select-crm" name="<?php echo ProgressAlly::SETTING_KEY_GENERAL . '[' . ProgressAllySettingAutomation::SETTING_KEY_AUTOMATION; ?>][select-crm]"
				pa-dep-source="progressally-automation-select-crm">
			<option value="">None</option>
			<option value="accessally" <?php echo ($automation_settings['select-crm'] === 'accessally') ? 'selected="selected"' : ''; ?>>AccessAlly</option>
			<option value="ontraport" <?php echo ($automation_settings['select-crm'] === 'ontraport') ? 'selected="selected"' : ''; ?>>Ontraport</option>
			<option value="infusionsoft" <?php echo ($automation_settings['select-crm'] === 'infusionsoft') ? 'selected="selected"' : ''; ?>>Infusionsoft</option>
			<option value="active-campaign" <?php echo ($automation_settings['select-crm'] === 'active-campaign') ? 'selected="selected"' : ''; ?>>Active Campaign</option>
			<option value="convertkit" <?php echo ($automation_settings['select-crm'] === 'convertkit') ? 'selected="selected"' : ''; ?>>ConvertKit</option>
			<option value="drip" <?php echo ($automation_settings['select-crm'] === 'drip') ? 'selected="selected"' : ''; ?>>Drip</option>
		</select>
	</div>
	<div class="progressally-setting-configure-block" <?php echo ($automation_settings['select-crm'] === 'accessally') ? '' : 'style="display:none;"'; ?>
		 hide-toggle pa-dep="progressally-automation-select-crm" pa-dep-value="accessally">
		<?php if ($is_accessally_enabled) { ?>
		<div class="progressally-inline-help-text">Please go to AccessAlly -> General Settings -> Initial Setup -> System Integration to configure the integration details.</div>
		<?php } else { ?>
		<div class="progressally-inline-help-text">AccessAlly is not installed / enabled. You can find out more information about AccessAlly <a target="_blank" href="https://accessally.com/features/">here</a>.</div>
		<?php } ?>
	</div>
	<div <?php echo ($automation_settings['select-crm'] === 'active-campaign') ? '' : 'style="display:none;"'; ?>
		 hide-toggle pa-dep="progressally-automation-select-crm" pa-dep-value="active-campaign">
		<div class="progressally-setting-configure-block">
			<table class="progressally-setting-configure-table">
				<tbody>
					<tr>
						<td style="width:60%;">
							<div class="progressally-setting-section-sub-header">API Access URL</div>
							<input type="text" class="full-width" name="<?php echo ProgressAlly::SETTING_KEY_GENERAL . '[' . ProgressAllySettingAutomation::SETTING_KEY_AUTOMATION; ?>][active-campaign-url]"
								   value="<?php echo esc_attr($automation_settings['active-campaign-url']); ?>" />
							<div class="progressally-setting-section-sub-header">API Access Key</div>
							<input type="text" class="full-width" name="<?php echo ProgressAlly::SETTING_KEY_GENERAL . '[' . ProgressAllySettingAutomation::SETTING_KEY_AUTOMATION; ?>][active-campaign-key]"
									value="<?php echo esc_attr($automation_settings['active-campaign-key']); ?>" />
						</td>
						<td>
							<div class="progressally-inline-help-text">See <a href="https://help.activecampaign.com/hc/en-us/articles/207317590-Getting-started-with-the-API" target="_blank">this</a> ActiveCampaign article to find out how to get your API information</div>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<div <?php echo ($automation_settings['select-crm'] === 'convertkit') ? '' : 'style="display:none;"'; ?>
		 hide-toggle pa-dep="progressally-automation-select-crm" pa-dep-value="convertkit">
		<div class="progressally-setting-configure-block">
			<table class="progressally-setting-configure-table">
				<tbody>
					<tr>
						<td style="width:60%;">
							<div class="progressally-setting-section-sub-header">API Key</div>
							<input type="text" class="full-width" name="<?php echo ProgressAlly::SETTING_KEY_GENERAL . '[' . ProgressAllySettingAutomation::SETTING_KEY_AUTOMATION; ?>][convertkit-key]"
								   value="<?php echo esc_attr($automation_settings['convertkit-key']); ?>" />
							<div class="progressally-setting-section-sub-header">API Secret</div>
							<input type="text" class="full-width" name="<?php echo ProgressAlly::SETTING_KEY_GENERAL . '[' . ProgressAllySettingAutomation::SETTING_KEY_AUTOMATION; ?>][convertkit-secret]"
									value="<?php echo esc_attr($automation_settings['convertkit-secret']); ?>" />
						</td>
						<td>
							<div class="progressally-inline-help-text">See <a href="http://help.convertkit.com/article/74-convertkit-settings" target="_blank">this</a> ConvertKit article to find out how to get your API key and secret</div>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<div <?php echo ($automation_settings['select-crm'] === 'ontraport') ? '' : 'style="display:none;"'; ?>
		 hide-toggle pa-dep="progressally-automation-select-crm" pa-dep-value="ontraport">
		<div class="progressally-setting-configure-block">
			<table class="progressally-setting-configure-table">
				<tbody>
					<tr>
						<td style="width:60%;">
							<div class="progressally-setting-section-sub-header">Application ID</div>
							<input type="text" class="full-width" name="<?php echo ProgressAlly::SETTING_KEY_GENERAL . '[' . ProgressAllySettingAutomation::SETTING_KEY_AUTOMATION; ?>][ontraport-app]"
								   value="<?php echo esc_attr($automation_settings['ontraport-app']); ?>" />
							<div class="progressally-setting-section-sub-header">API Key</div>
							<input type="text" class="full-width" name="<?php echo ProgressAlly::SETTING_KEY_GENERAL . '[' . ProgressAllySettingAutomation::SETTING_KEY_AUTOMATION; ?>][ontraport-key]"
									value="<?php echo esc_attr($automation_settings['ontraport-key']); ?>" />
						</td>
						<td>
							<div class="progressally-inline-help-text">See <a href="https://support.ontraport.com/hc/en-us/articles/217882248-API-in-ONTRAPORT" target="_blank">this</a> Ontraport article to find out how to get your Application ID and API key</div>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<div <?php echo ($automation_settings['select-crm'] === 'infusionsoft') ? '' : 'style="display:none;"'; ?>
		 hide-toggle pa-dep="progressally-automation-select-crm" pa-dep-value="infusionsoft">
		<div class="progressally-setting-configure-block">
			<table class="progressally-setting-configure-table">
				<tbody>
					<tr>
						<td style="width:60%;">
							<div class="progressally-setting-section-sub-header">Application ID</div>
							https://
							<input type="text" size="10" name="<?php echo ProgressAlly::SETTING_KEY_GENERAL . '[' . ProgressAllySettingAutomation::SETTING_KEY_AUTOMATION; ?>][infusionsoft-app]"
								   value="<?php echo esc_attr($automation_settings['infusionsoft-app']); ?>" />
							.infusionsoft.com
							<div class="progressally-setting-section-sub-header">API Key</div>
							<input type="text" class="full-width" name="<?php echo ProgressAlly::SETTING_KEY_GENERAL . '[' . ProgressAllySettingAutomation::SETTING_KEY_AUTOMATION; ?>][infusionsoft-key]"
									value="<?php echo esc_attr($automation_settings['infusionsoft-key']); ?>" />
						</td>
						<td>
							<div class="progressally-inline-help-text">See <a href="http://help.infusionsoft.com/userguides/get-started/tips-and-tricks/api-key" target="_blank">this</a> Infusionsoft article to find out how to get your API key</div>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<div <?php echo ($automation_settings['select-crm'] === 'drip') ? '' : 'style="display:none;"'; ?>
		 hide-toggle pa-dep="progressally-automation-select-crm" pa-dep-value="drip">
		<div class="progressally-setting-configure-block">
			<table class="progressally-setting-configure-table">
				<tbody>
					<tr>
						<td style="width:60%;">
							<div class="progressally-setting-section-sub-header">Account ID</div>
							<input type="text" class="full-width" name="<?php echo ProgressAlly::SETTING_KEY_GENERAL . '[' . ProgressAllySettingAutomation::SETTING_KEY_AUTOMATION; ?>][drip-account]"
								   value="<?php echo esc_attr($automation_settings['drip-account']); ?>" />
							<div class="progressally-setting-section-sub-header">API Token</div>
							<input type="text" class="full-width" name="<?php echo ProgressAlly::SETTING_KEY_GENERAL . '[' . ProgressAllySettingAutomation::SETTING_KEY_AUTOMATION; ?>][drip-key]"
									value="<?php echo esc_attr($automation_settings['drip-key']); ?>" />
						</td>
						<td>
							<div class="progressally-inline-help-text">See <a href="http://docs.leadquizzes.com/integrations/how-can-i-find-my-drip-account-id-and-api-token" target="_blank">this</a> Drip article to find out how to get your account ID and API token</div>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>