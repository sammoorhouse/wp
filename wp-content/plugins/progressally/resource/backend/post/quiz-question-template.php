<div class="progressally-setting-question-block {{open-class}}" id="progressally-question-block-{{question-id}}">
	<input type="hidden" progressally-param="quiz[question-order][]" value="{{question-id}}" />
	<div class="progressally-setting-question-header" progressally-toggle-target="#progressally-question-toggle-{{question-id}}" id="progressally-setting-question-header-{{question-id}}">
		<div class="progressally-view-toggle-block">
			<input progressally-param="quiz[question][{{question-id}}][checked-is-open]" {{checked-is-open}} type="checkbox" value="yes"
				   toggle-class="progressally-question-opened" progressally-toggle-element="#progressally-question-block-{{question-id}}" min-height="40"
				   min-height-element="#progressally-setting-question-header-{{question-id}}" class="progressally-quiz-question-toggle"
				   pa-dep-source="progressally-question-toggle-{{question-id}}" id="progressally-question-toggle-{{question-id}}">
			<label hide-toggle="checked-is-open" pa-dep="progressally-question-toggle-{{question-id}}" pa-dep-value="no">&#x25BC;</label>
			<label hide-toggle="checked-is-open" pa-dep="progressally-question-toggle-{{question-id}}" pa-dep-value="yes">&#x25B2;</label>
		</div>
		<div class="progressally-setting-quiz-order-move"></div>
		<div class="progressally-quiz-question" progressally-quiz-update-target="question-html-{{question-id}}"></div>
		<div style="clear:both;"></div>
		<div class="progressally-setting-configure-block">
			<ul class="progressally-quiz-display" id="progressally-quiz-preview-vertical-{{question-id}}"
				hide-toggle="choice-display" pa-dep="progressally-quiz-choice-display" pa-dep-value="vertical">
				{{preview-code-vertical}}
			</ul>
			<table class="progressally-quiz-display-horizontal" hide-toggle="choice-display" pa-dep="progressally-quiz-choice-display" pa-dep-value="horizontal">
				<tbody>
					<tr id="progressally-quiz-preview-horizontal-{{question-id}}">
						{{preview-code-horizontal}}
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<div hide-toggle="checked-is-open" pa-dep="progressally-question-toggle-{{question-id}}" pa-dep-value="yes">
		<div class="progressally-setting-configure-block">
			<div class="progressally-setting-configure-block progressally-quiz-header">Question Text (HTML code allowed)</div>
			<textarea class="full-width" progressally-quiz-update-source="question-html-{{question-id}}" progressally-param="quiz[question][{{question-id}}][question-html]" rows="5">{{question-html}}</textarea>
		</div>
		<div class="progressally-setting-configure-block" s--quiz-type-survey--w hide-toggle pa-dep="progressally-quiz-type" pa-dep-value="survey">
			<div class="progressally-setting-configure-block progressally-quiz-header">Outcome weight</div>
			<div class="progressally-setting-section-help-text">You can assign higher weight to a question so the answer will count for more.</div>
			<input size="4" type="text" progressally-param="quiz[question][{{question-id}}][survey-weight]" value="{{survey-weight}}"/>
		</div>
		<div class="progressally-setting-configure-block">
			<div class="progressally-setting-configure-block">
				<span class="progressally-quiz-header">Choices</span>
				<div class="progressally-replace-choice-link"
					 progressally-warning="The existing choices will be removed. Continue?" progressally-replace-choice-1-10="{{question-id}}"
					 hide-toggle="choice-display" pa-dep="progressally-quiz-choice-display" pa-dep-value="horizontal">Replace existing choices with an &#39;1&#39; - &#39;10&#39; scale</div>
				<div style="clear:both"></div>
			</div>
			<input type="hidden" id="progressally-max-choice-id-{{question-id}}" value="{{max-choice-id}}" />
			<div class="progressally-quiz-choice-container">
				<table class="progressally-quiz-choice-listing">
					<tbody id="progressally-quiz-choice-listing-container-{{question-id}}">
						<tr class="progressally-quiz-choice-header">
							<td class="progressally-quiz-item-icon-col"></td>
							<td class="progressally-quiz-item-icon-description-col"></td>
							<td class="progressally-quiz-item-correct-col" s--quiz-type-score--w hide-toggle pa-dep="progressally-quiz-type" pa-dep-value="score">
								MARK CORRECT ANSWER
							</td>
							<td class="progressally-quiz-item-outcome-col" s--quiz-type-survey--w hide-toggle pa-dep="progressally-quiz-type" pa-dep-value="survey">
								OUTCOME
							</td>
							<td class="progressally-quiz-item-outcome-col" s--quiz-type-segment--w hide-toggle pa-dep="progressally-quiz-type" pa-dep-value="segment">
								SCORE
							</td>
							<td class="progressally-quiz-item-delete-col"></td>
						</tr>
						{{choices}}
					</tbody>
				</table>
			</div>
		</div>
		<div class="progressally-setting-configure-block" s--quiz-type-score--w hide-toggle pa-dep="progressally-quiz-type" pa-dep-value="score">
			<div class="progressally-setting-configure-block progressally-quiz-header">Incorrect Answer Message Text (HTML code allowed)</div>
			<textarea class="full-width" progressally-param="quiz[question][{{question-id}}][incorrect-message-html]" rows="5">{{incorrect-message-html}}</textarea>
		</div>
		<div>
			<div class="progressally-clone-button" progressally-clone-question="{{question-id}}">Clone Question</div>
			<div class="progressally-delete-button progressally-float-right" progressally-delete-element="#progressally-question-block-{{question-id}}"
				 progressally-delete-warning="Deleting a question cannot be undone. Continue?">[-] Delete Question</div>
			<div style="clear:both"></div>
		</div>
	</div>
</div>