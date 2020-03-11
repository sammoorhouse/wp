<div class="progressally-setting-configure-block">
	<div class="progressally-setting-section-header">Progress Statistics</div>
	<div class="progressally-setting-section-help-text">Objective completion statistics based on all users.</div>
</div>
<?php if ($has_objective) { ?>
<div class="progressally-dashboard-detailview-report-block progressally-setting-border">
	<div class="progressally-setting-section-sub-header">Overall Objective Completion</div>
	<div class="progressally-objective-completion-desc">
		<ul>
			<li><?php echo $user_progress['total']; ?> users have completed all objectives.</li>
			<li><?php echo $total_user_number; ?> users accessed this page.</li>
		</ul>
	</div>
</div>
<div class="progressally-dashboard-detailview-report-block">
	<div class="progressally-setting-section-sub-header">Objective Completion Details</div>
		<table class="progressally-objective-table" id="progressally-objective-list-table">
			<tbody>
				<tr valign="top">
					<th class="objective-list-id-col">ID</th>
					<th class="objective-list-description-col">Description</th>
					<th class="objective-list-completion-number-col">#Users completed</th>
				</tr>
			<?php foreach ($user_progress['detail'] as $objective_id => $progress_value) { ?>
			<tr class="progressally-objective-list-stats-row">
				<td class="objective-list-completion-value-col"><?php echo $objective_id; ?></td>
				<td><?php echo esc_html($objective_setting[$objective_id]['description']); ?></td>
				<td class="objective-list-completion-value-col"><?php echo $progress_value; ?></td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
</div>
<?php } else { ?>
<div class="progressally-dashboard-detailview-report-block">
	There is no active objective defined in this page.
</div>
<?php } ?>