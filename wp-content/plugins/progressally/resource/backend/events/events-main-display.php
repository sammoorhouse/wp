<div class="wrap">
<h2 style="display:none;"><?php _e('ProgressAlly Events'); ?></h2>

<div id="progressally-wait-overlay">
	<div class="progressally-wait-content">
		<img src="<?php echo ProgressAlly::$PLUGIN_URI; ?>resource/backend/img/wait.gif" alt="wait" width="128" height="128" />
	</div>
</div>
<table class="progressally-setting-container">
	<tbody>
		<tr>
			<td class="progressally-setting-left-col"/>
			<td class="progressally-setting-title-cell progressally-setting-right-col">
				<div style="display:inline-block;">
					<div class="progressally-setting-title">ProgressAlly Events</div>

					<div class="progressally-setting-section-help-text"><div class="progressally-info-icon"></div>Need extra help? View our documentation and tutorials <a class="underline" href="<?php echo ProgressAlly::HELP_URL; ?>">here</a>!</div>
				</div>
			</td>
		</tr>
		<tr>
			<td class="progressally-setting-left-col progressally-setting-tab-label-col progressally-setting-tab-active" tab-group="progressally-tab-group-1" target="setup" active-class="progressally-setting-tab-active">
				<div style="background-image: url('<?php echo ProgressAlly::$PLUGIN_URI; ?>resource/backend/img/note-reply-icon.png');" class="progressally-tab-label">
					Setup
				</div>
			</td>
			<td rowspan="3" class="progressally-setting-content-cell progressally-setting-right-col">
				<div class="progressally-setting-content-container" progressally-tab-group-1="setup">
					<?php echo ProgressAllyEvents::show_setup_settings($overall_settings); ?>
				</div>
				<div class="progressally-setting-content-container" style="display:none;" progressally-tab-group-1="log">
					<?php echo ProgressAllyEventLog::show_log_settings(); ?>
				</div>
			</td>
		</tr>
		<tr>
			<td class="progressally-setting-left-col progressally-setting-tab-label-col" tab-group="progressally-tab-group-1" target="log" active-class="progressally-setting-tab-active">
				<div style="background-image: url('<?php echo ProgressAlly::$PLUGIN_URI; ?>resource/backend/img/admin-init-icon.png');" class="progressally-tab-label">
					Logs
				</div>
			</td>
		</tr>
		<tr class="progressally-setting-filler-row">
			<td class="progressally-setting-left-col"><br/></td>
		</tr>
	</tbody>
</table>
</div>