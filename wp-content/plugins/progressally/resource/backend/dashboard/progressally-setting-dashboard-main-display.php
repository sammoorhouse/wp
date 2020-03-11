<div class="wrap">
<h2 style="display:none;"><?php _e('ProgressAlly Reports'); ?></h2>

<table class="progressally-setting-container">
	<tbody>
		<tr>
			<td class="progressally-setting-left-col"/>
			<td class="progressally-setting-title-cell progressally-setting-right-col">
				<div style="display:inline-block;">
					<div class="progressally-setting-title">ProgressAlly Reports</div>

					<div class="progressally-setting-section-help-text"><div class="progressally-info-icon"></div>Need extra help? View our documentation and tutorials <a class="underline" href="<?php echo ProgressAlly::HELP_URL; ?>">here</a>!</div>
				</div>
			</td>
		</tr>
		<tr>
			<td class="progressally-setting-left-col progressally-setting-tab-label-col progressally-setting-tab-active" tab-group="progressally-tab-group-1" target="dashboard" active-class="progressally-setting-tab-active">
				<div style="background-image: url('<?php echo ProgressAlly::$PLUGIN_URI; ?>resource/backend/img/dashboard-icon.png');" class="progressally-tab-label">
					Overview
				</div>
			</td>
			<td rowspan="3" class="progressally-setting-content-cell progressally-setting-right-col">
				<div class="progressally-setting-content-container" progressally-tab-group-1="dashboard">
					<?php ProgressAllySettingDashboardPage::show_dashboard_page_settings(); ?>
				</div>
				<div class="progressally-setting-content-container" progressally-tab-group-1="detail" style="display:none;">
					<?php ProgressAllySettingDashboardDetail::show_dashboard_detail_settings(); ?>
				</div>
			</td>
		</tr>
		<tr>
			<td class="progressally-setting-left-col progressally-setting-tab-label-col" tab-group="progressally-tab-group-1" target="detail" active-class="progressally-setting-tab-active">
				<div style="background-image: url('<?php echo ProgressAlly::$PLUGIN_URI; ?>resource/backend/img/dashboard-detail-icon.png');" class="progressally-tab-label">
					Detail
				</div>
			</td>
		</tr>
		<tr class="progressally-setting-filler-row">
			<td class="progressally-setting-left-col"><br/></td>
		</tr>
	</tbody>
</table>
</div>