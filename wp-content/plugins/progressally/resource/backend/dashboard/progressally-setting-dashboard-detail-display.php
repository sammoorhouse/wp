<table class="progressally-sub-setting-container">
	<tbody>
		<tr class="progressally-sub-setting-top-row">
			<td class="progressally-sub-setting-tab-label-col progressally-sub-setting-tab-active" tab-group="progressally-tab-group-2" target="progress" active-class="progressally-sub-setting-tab-active">
				<div class="progressally-sub-tab-label">
					Progress
				</div>
			</td>
			<td class="progressally-sub-setting-tab-label-col" tab-group="progressally-tab-group-2" target="quiz" active-class="progressally-sub-setting-tab-active">
				<div class="progressally-sub-tab-label">
						Quiz
				</div>
			</td>
			<td class="progressally-sub-setting-filler-col"></td>
		</tr>
		<tr>
			<td colspan="3" class="progressally-sub-setting-content-cell">
				<div class="progressally-sub-setting-content-container" progressally-tab-group-2="progress">
					<?php echo ProgressAllySettingDashboardDetail::show_stats_meta_box($post_id); ?>
				</div>
				<div class="progressally-sub-setting-content-container" style="display:none;" progressally-tab-group-2="quiz">
					<?php echo ProgressAllySettingDashboardDetail::show_quiz_stats_meta_box($post_id); ?>
				</div>
			</td>
		</tr>
	</tbody>
</table>
