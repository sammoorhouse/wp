<div class="wrap">
<h2 style="display:none;"><?php _e('ProgressAlly Settings'); ?></h2>
<div id="progressally-wait-overlay">
	<img src="<?php echo ProgressAlly::$PLUGIN_URI; ?>resource/backend/img/wait.gif" alt="wait" width="128" height="128" />
</div>
<?php settings_errors('progressally_general'); ?>
<form method="post" action="options.php">
<?php settings_fields( 'progressally_general_settings' ); ?>
<input type="hidden" name="<?php echo ProgressAlly::SETTING_KEY_GENERAL; ?>[select][selected-tab]" id="selected-tab" value="<?php echo $selected['selected-tab']; ?>" />

<table class="progressally-setting-container">
	<tbody>
		<tr>
			<td class="progressally-setting-left-col"/>
			<td class="progressally-setting-title-cell progressally-setting-right-col">
				<div style="display:inline-block;">
					<div class="progressally-setting-title">ProgressAlly Settings</div>

					<div class="progressally-setting-section-help-text"><div class="progressally-info-icon"></div>Need extra help? View our documentation and tutorials <a class="underline" href="<?php echo ProgressAlly::HELP_URL; ?>">here</a>!</div>
				</div>
				<input class="progressally-setting-submit-button" type="submit" value="Save Changes" />
			</td>
		</tr>
		<?php if(ProgressAllySettingLicense::$progressally_enabled) { ?>
		<tr>
			<td class="progressally-setting-left-col progressally-setting-tab-label-col <?php echo $selected['selected-tab']==='styling'?'progressally-setting-tab-active':''; ?>" click-target="#selected-tab" click-value="styling" tab-group="progressally-tab-group-1" target="styling" active-class="progressally-setting-tab-active">
				<div style="background-image: url('<?php echo ProgressAlly::$PLUGIN_URI; ?>resource/backend/img/styling-icon.png');" class="progressally-tab-label">
					Styling
				</div>
			</td>
			<td rowspan="7" class="progressally-setting-content-cell progressally-setting-right-col">
				<div class="progressally-setting-content-container" style="display:<?php echo $selected['selected-tab']==='styling'?'block':'none'; ?>;" progressally-tab-group-1="styling">
					<?php ProgressAllySettingStyling::show_styling_settings_css(); ?>
				</div>
				<div class="progressally-setting-content-container" style="display:<?php echo $selected['selected-tab']==='automation'?'block':'none'; ?>;" progressally-tab-group-1="automation">
					<?php ProgressAllySettingAutomation::show_automation_settings(); ?>
				</div>
				<div class="progressally-setting-content-container" style="display:<?php echo $selected['selected-tab']==='notes'?'block':'none'; ?>;" progressally-tab-group-1="notes">
					<?php ProgressAllySettingNotesConfig::show_notes_config_settings(); ?>
				</div>
				<div class="progressally-setting-content-container" style="display:<?php echo $selected['selected-tab']==='advanced'?'block':'none'; ?>;" progressally-tab-group-1="advanced">
					<?php ProgressAllySettingAdvanced::show_advanced_settings(); ?>
				</div>
				<div class="progressally-setting-content-container" style="display:<?php echo $selected['selected-tab']==='toolkit'?'block':'none'; ?>;" progressally-tab-group-1="toolkit">
					<?php include dirname(__FILE__) . '/progressally-setting-toolkit.php'; ?>
				</div>
				<div class="progressally-setting-content-container" style="display:<?php echo $selected['selected-tab']==='license'?'block':'none'; ?>;" progressally-tab-group-1="license">
					<?php ProgressAllySettingLicense::show_license_settings(); ?>
				</div>
			</td>
		</tr>
		<tr>
			<td class="progressally-setting-left-col progressally-setting-tab-label-col <?php echo $selected['selected-tab']==='automation'?'progressally-setting-tab-active':''; ?>" click-target="#selected-tab" click-value="automation" tab-group="progressally-tab-group-1" target="automation" active-class="progressally-setting-tab-active">
				<div style="background-image: url('<?php echo ProgressAlly::$PLUGIN_URI; ?>resource/backend/img/automation-setting-icon.png');" class="progressally-tab-label">
					Tagging
				</div>
			</td>
		</tr>
		<tr>
			<td class="progressally-setting-left-col progressally-setting-tab-label-col <?php echo $selected['selected-tab']==='notes'?'progressally-setting-tab-active':''; ?>" click-target="#selected-tab" click-value="notes" tab-group="progressally-tab-group-1" target="notes" active-class="progressally-setting-tab-active">
				<div style="background-image: url('<?php echo ProgressAlly::$PLUGIN_URI; ?>resource/backend/img/notes-setting-icon.png');" class="progressally-tab-label">
					Private Notes
				</div>
			</td>
		</tr>
		<tr>
			<td class="progressally-setting-left-col progressally-setting-tab-label-col <?php echo $selected['selected-tab']==='advanced'?'progressally-setting-tab-active':''; ?>" click-target="#selected-tab" click-value="advanced" tab-group="progressally-tab-group-1" target="advanced" active-class="progressally-setting-tab-active">
				<div style="background-image: url('<?php echo ProgressAlly::$PLUGIN_URI; ?>resource/backend/img/advanced-icon.png');" class="progressally-tab-label">
					Advanced
				</div>
			</td>
		</tr>
		<tr>
			<td class="progressally-setting-left-col progressally-setting-tab-label-col <?php echo $selected['selected-tab']==='toolkit'?'progressally-setting-tab-active':''; ?>" click-target="#selected-tab" click-value="toolkit" tab-group="progressally-tab-group-1" target="toolkit" active-class="progressally-setting-tab-active">
				<div style="background-image: url('<?php echo ProgressAlly::$PLUGIN_URI; ?>resource/backend/img/toolbox-icon.png');" class="progressally-tab-label">
					Toolkit
				</div>
			</td>
		</tr>
		<tr>
			<td class="progressally-setting-left-col progressally-setting-tab-label-col <?php echo $selected['selected-tab']==='license'?'progressally-setting-tab-active':''; ?>" click-target="#selected-tab" click-value="license" tab-group="progressally-tab-group-1" target="license" active-class="progressally-setting-tab-active">
				<div style="background-image: url('<?php echo ProgressAlly::$PLUGIN_URI; ?>resource/backend/img/license-icon.png');" class="progressally-tab-label">
					License
				</div>
			</td>
		</tr>
		<?php } else { ?>
		<tr>
			<td class="progressally-setting-left-col progressally-setting-tab-label-col <?php echo $selected['selected-tab']==='license'?'progressally-setting-tab-active':''; ?>" click-target="#selected-tab" click-value="license" tab-group="progressally-tab-group-1" target="license" active-class="progressally-setting-tab-active">
				<div style="background-image: url('<?php echo ProgressAlly::$PLUGIN_URI; ?>resource/backend/img/license-icon.png');" class="progressally-tab-label">
					License
				</div>
			</td>
			<td rowspan="2" class="progressally-setting-content-cell progressally-setting-right-col">
				<div class="progressally-setting-content-container" style="display:<?php echo $selected['selected-tab']==='license'?'block':'none'; ?>;" progressally-tab-group-1="license">
					<?php ProgressAllySettingLicense::show_license_settings(); ?>
				</div>
			</td>		
		</tr>
		<?php } ?>
		<tr class="progressally-setting-filler-row">
			<td class="progressally-setting-left-col"><br/></td>
		</tr>
		<tr class="progressally-setting-last-row">
			<td class="progressally-setting-left-col" />
			<td class="progressally-setting-right-col">
				<input class="progressally-setting-submit-button" type="submit" value="Save Changes" />
			</td>
		</tr>
	</tbody>
</table>
</form>
</div>