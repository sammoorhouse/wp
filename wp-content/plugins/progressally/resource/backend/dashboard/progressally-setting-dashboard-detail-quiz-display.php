<div class="progressally-setting-configure-block">
	<div class="progressally-setting-section-header">Quiz Statistics</div>
	<div class="progressally-setting-section-help-text">Quiz statistics based on all users.</div>
</div>
<?php if ($has_quiz) { ?>
<div class="progressally-dashboard-detailview-report-block progressally-setting-border">
	<div class="progressally-setting-section-sub-header">Overall Quiz Completion</div>
	<div class="progressally-objective-completion-desc">
		<ul>
			<li><?php echo $quiz_completion_number; ?> users have completed the quiz.</li>
			<li><?php echo $total_user_number; ?> users accessed this page.</li>
		</ul>
	</div>
</div>
	<?php if ($quiz_completion_number > 0) { ?>
<div class="progressally-dashboard-detailview-report-block progressally-setting-border">
	<div class="progressally-setting-section-sub-header">Quiz Result</div>
	<?php foreach ($outcome_array as $outcome_row) { ?>
	<div class="progressally-quiz-result-stats-row">
		<div class="progressally-quiz-result-stats-label"><?php echo $outcome_row['label']; ?></div>
		<div class="progressally-quiz-result-stats-bar"><?php echo $outcome_row['percentage_bar']; ?></div>
	</div>
	<?php } ?>
</div>
<div class="progressally-dashboard-detailview-report-block progressally-setting-border">
	<div class="progressally-setting-section-sub-header">Question details</div>
	<?php foreach ($input_array as $input_row) { ?>
	<div class="progressally-quiz-input-question"><?php echo esc_html($input_row['question']); ?></div>
	<table class="progressally-objective-table">
		<tbody>
			<tr valign="top">
				<th class="objective-list-description-col">Choices</th>
				<th class="objective-list-completion-number-col">Percentage</th>
				<th class="objective-list-completion-number-col">#Users</th>
			</tr>
			<?php foreach ($input_row['detail'] as $input_choice) { ?>
			<tr class="progressally-objective-list-stats-row">
				<td><?php echo esc_html($input_choice['choice']); ?></td>
				<?php $choice_percentage = $input_choice['counter'] / $quiz_completion_number; ?>
				<td class="objective-list-completion-value-col"><?php echo round($choice_percentage * 100) . '%'; ?></td>
				<td class="objective-list-completion-value-col"><?php echo $input_choice['counter']; ?></td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
	<?php } ?>
</div>
<div class="progressally-dashboard-detailview-report-block">
	<div class="progressally-setting-section-sub-header">Download Quiz Results</div>
	<div class="progressally-setting-configure-block">
		<a class="progressally-button" href="<?php echo $quiz_stats_url; ?>" target="_blank">Download All Student Responses</a>
	</div>
</div>
	<?php } ?>
<?php } else { ?>
<div class="progressally-dashboard-detailview-report-block">
	There is no quiz here.
</div>
<?php } ?>