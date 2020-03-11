<style id="progressally-quiz-question-placeholder-css" type="text/css"></style>
<div class="progressally-setting-configure-block">
	<input type="checkbox" progressally-param="checked-enable-quiz" id="progressally-enable-quiz" value='yes'
		   pa-dep-source="progressally-enable-quiz" <?php checked($meta['checked-enable-quiz'], 'yes'); ?> />
	<label for="progressally-enable-quiz">Activate Quiz & Create Your Questions</label>
</div>
<div hide-toggle pa-dep="progressally-enable-quiz" pa-dep-value="yes" <?php echo ($meta['checked-enable-quiz'] === 'yes') ? '' : 'style="display:none;"'; ?>>
	<div class="progressally-setting-section progressally-setting-border">
		<div class="progressally-setting-section-header">Quiz Type</div>
		<table class="progressally-setting-configure-table">
			<tbody>
				<tr>
					<td style="width:100px;">
						<select progressally-param="quiz[select-quiz-type]"
								id="progressally-quiz-type" pa-dep-source="progressally-quiz-type" >
							<option <?php selected($meta['quiz']['select-quiz-type'], 'score'); ?> value="score">Graded Quiz</option>
							<option <?php selected($meta['quiz']['select-quiz-type'], 'survey'); ?> value="survey">Personality Test</option>
							<option <?php selected($meta['quiz']['select-quiz-type'], 'segment'); ?> value="segment">Scoring Test</option>
						</select>
					</td>
					<td>
						<div class="progressally-inline-help-text" hide-toggle pa-dep="progressally-quiz-type" pa-dep-value="score"
							<?php echo ($meta['quiz']['select-quiz-type'] === 'score') ? '' : 'style="display:none;"'; ?>>
							A graded quiz will evaluate the answers and calculate a score out of 100%.
						</div>
						<div class="progressally-inline-help-text" hide-toggle pa-dep="progressally-quiz-type" pa-dep-value="survey"
							<?php echo ($meta['quiz']['select-quiz-type'] === 'survey') ? '' : 'style="display:none;"'; ?>>
							A personality test will assign an outcome based on how someone answers the questions.
						</div>
						<div class="progressally-inline-help-text" hide-toggle pa-dep="progressally-quiz-type" pa-dep-value="segment"
							<?php echo ($meta['quiz']['select-quiz-type'] === 'segment') ? '' : 'style="display:none;"'; ?>>
							A scoring test allows you to assign a different score to each answer, and the final outcome is based on the sum of all the scores.
						</div>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="progressally-setting-section progressally-setting-border" id="progressally-quiz-outcome-container" hide-toggle pa-dep="progressally-quiz-type" pa-dep-value="survey"
		<?php echo ($meta['quiz']['select-quiz-type'] === 'survey') ? '' : 'style="display:none;"'; ?>>
		<div class="progressally-setting-section-header">Personality Test Outcomes</div>
		<div class="progressally-setting-section-help-text">A personality test will lead to one outcome based on how the questions are answered.</div>
		<div class="progressally-setting-configure-block">
			<table class="progressally-quiz-settings-table">
				<tbody>
					<tr>
						<td>Number of outcomes</td>
						<td>
							<input type="text" progressally-param="quiz[survey-num-outcome]"
								   id="progressally-quiz-num-outcome" size="4" readonly="readonly" value="<?php echo $meta['quiz']['survey-num-outcome']; ?>" />
						</td>
						<td>
							<div class="progressally-quiz-image-add-button" id="progressally-quiz-increase-outcome"></div>
							<div class="progressally-quiz-image-delete-button" id="progressally-quiz-decrease-outcome"
								 progressally-delete-warning="Removing an outcome will permanently delete the outcome HTML code. Continue?"></div>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<?php echo $survey_outcome_code; ?>
	</div>
	<div class="progressally-setting-section progressally-setting-border" hide-toggle pa-dep="progressally-quiz-type" pa-dep-value="score"
		<?php echo ($meta['quiz']['select-quiz-type'] === 'score') ? '' : 'style="display:none;"'; ?>>
		<div>
			<div class="progressally-button progressally-float-right" id="progressally-quiz-add-grade-outcome-button">[+] Add New Outcome</div>
			<div class="progressally-setting-section-header">Graded Quiz Outcomes</div>
			<div class="progressally-setting-section-help-text">A grade quiz will display different messages based on the score.</div>
			<div style="clear:both"></div>
		</div>
		<input type="hidden" id="progressally-quiz-num-grade-outcome" progressally-param="quiz[grade-num-outcome]" value="<?php echo $meta['quiz']['grade-num-outcome']; ?>" />
		<div id="progressally-quiz-grade-outcome-container">
			<?php echo $grade_outcome_code; ?>
		</div>
		<div class="progressally-outcome-section" <?php echo $has_valid_tag_selection ? '' : 'style="display:none"'; ?>>
			<div class="progressally-setting-configure-block">
				<div class="progressally-setting-section-sub-header">Apply a tag when user <span style="color:#00a5b3">passes</span> the quiz:</div>
				<select class="progressally-autocomplete-add progressally-tag-input full-width" progressally-param="quiz[grade-outcome-pass-tag]">
					<option value=""></option>
					<?php echo $grade_pass_tagging_code;?>
				</select>
			</div>
			<div class="progressally-setting-configure-block">
				<div class="progressally-setting-section-sub-header">Apply a tag when user <span style="color:#ff0000">fails</span> the quiz:</div>
				<select class="progressally-autocomplete-add progressally-tag-input full-width" progressally-param="quiz[grade-outcome-fail-tag]">
					<option value=""></option>
					<?php echo $grade_fail_tagging_code;?>
				</select>
			</div>
		</div>
	</div>
	<div class="progressally-setting-section progressally-setting-border" hide-toggle pa-dep="progressally-quiz-type" pa-dep-value="segment"
		<?php echo ($meta['quiz']['select-quiz-type'] === 'segment') ? '' : 'style="display:none;"'; ?>>
		<div>
			<div class="progressally-button progressally-float-right" id="progressally-quiz-add-segment-outcome-button">[+] Add New Outcome</div>
			<div class="progressally-setting-section-header">Scoring outcomes</div>
			<div class="progressally-setting-section-help-text">A different message is shown based on the total score.</div>
			<div style="clear:both"></div>
		</div>
		<input type="hidden" id="progressally-quiz-num-segment-outcome" progressally-param="quiz[segment-num-outcome]" value="<?php echo $meta['quiz']['segment-num-outcome']; ?>" />
		<div id="progressally-quiz-segment-outcome-container">
			<?php echo $segment_outcome_code; ?>
		</div>
	</div>
	<div class="progressally-setting-section progressally-setting-border">
		<div class="progressally-setting-section-header">Questions</div>
		<input type="hidden" id="progressally-quiz-max-question" value="<?php echo $max_question_num; ?>" />
		<div id="progressally-quiz-question-container" class="progressally-setting-configure-block">
		<?php echo $question_code; ?>
		</div>
		<div>
			<div class="progressally-button progressally-float-right" id="progressally-quiz-add-question">[+] Add New Question</div>
			<div style="clear:both"></div>
		</div>
	</div>
	<div class="progressally-setting-section progressally-setting-border" hide-toggle pa-dep="progressally-quiz-type" pa-dep-value="score"
		<?php echo ($meta['quiz']['select-quiz-type'] === 'score') ? '' : 'style="display:none;"'; ?>>
		<div class="progressally-setting-section-header">&quot;Correct&quot; Answer Message Text</div>
		<div class="progressally-setting-section-help-text">This message is shown when a question is answered correctly. You can use HTML code in the message for styling.</div>
		<div class="progressally-setting-configure-block">
			<textarea class="full-width" progressally-param="quiz[correct-message-html]" rows="5"><?php echo esc_html($meta['quiz']['correct-message-html']); ?></textarea>
		</div>
	</div>
	<div class="progressally-setting-section progressally-setting-border">
		<div class="progressally-setting-section-header">How Many Questions To Show At One Time</div>
		<div class="progressally-quiz-settings-row">
			Show
			<input progressally-param="quiz[num-question-per-page]" type="text" size="2" value="<?php echo esc_attr($meta['quiz']['num-question-per-page']); ?>" />
			questions at one time (the default &quot;0&quot; is to show all questions immediately).
		</div>
	</div>
	<div class="progressally-setting-section progressally-setting-border">
		<div class="progressally-setting-section-header">Number of allowed quiz re-takes</div>
		<div class="progressally-setting-section-help-text">When set to 0, the quiz cannot be re-taken after submitting the answers. A WordPress administrator can always re-take the quiz.</div>
		<div class="progressally-setting-configure-block">
			<input type="text" progressally-param="quiz[num-retake]"
				   size="4" value="<?php echo $meta['quiz']['num-retake']; ?>" />
		</div>
	</div>
	<div class="progressally-setting-section progressally-setting-border">
		<div class="progressally-setting-section-header">How to display question choices</div>
		<div class="progressally-quiz-settings-row">
			<select id="progressally-quiz-choice-display-select" progressally-param="quiz[choice-display]" pa-dep-source="progressally-quiz-choice-display">
				<option value="vertical" <?php echo $meta['quiz']['choice-display'] === 'vertical' ? 'selected="selected"' : ''; ?> >Show the choices as a list (radio button to the LEFT of the text)</option>
				<option value="horizontal" <?php echo $meta['quiz']['choice-display'] === 'horizontal' ? 'selected="selected"' : ''; ?> >Show all choices in the same row (radio button ABOVE the text)</option>
			</select>
		</div>
	</div>
	<div class="progressally-setting-section progressally-setting-border">
		<div class="progressally-setting-section-header">Customize button text (advanced)</div>
		<table class="progressally-setting-configure-block progressally-quiz-customize-table">
			<tbody>
				<tr>
					<td class="progressally-setting-customize-header">Submit</td>
					<td class="progressally-setting-customize-content">
						<input progressally-param="quiz[submit-button-text]" type="text" value="<?php echo esc_attr($meta['quiz']['submit-button-text']); ?>" />
					</td>
					<td class="progressally-setting-customize-header">Retake</td>
					<td class="progressally-setting-customize-content">
						<input progressally-param="quiz[retake-button-text]" type="text" value="<?php echo esc_attr($meta['quiz']['retake-button-text']); ?>" />
					</td>
				</tr>
				<tr>
					<td class="progressally-setting-customize-header">Previous</td>
					<td class="progressally-setting-customize-content">
						<input progressally-param="quiz[back-button-text]" type="text" value="<?php echo esc_attr($meta['quiz']['back-button-text']); ?>" />
					</td>
					<td class="progressally-setting-customize-header">Next</td>
					<td class="progressally-setting-customize-content">
						<input progressally-param="quiz[next-button-text]" type="text" value="<?php echo esc_attr($meta['quiz']['next-button-text']); ?>" />
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="progressally-setting-section">
		<div class="progressally-setting-section-header">Download Quiz Results</div>
		<div class="progressally-setting-section-help-text">Download the quiz results to review how the members have answered each question.</div>
		<div class="progressally-setting-configure-block">
			<a class="progressally-button" href="<?php echo $quiz_stats_url; ?>" target="_blank">Download All Student Responses</a>
		</div>
	</div>
</div>