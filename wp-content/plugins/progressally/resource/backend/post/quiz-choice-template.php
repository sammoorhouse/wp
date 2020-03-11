<tr class="progressally-quiz-choice-item progressally-quiz-choice-dependent-{{question-id}}-{{choice-id}}" id="progressally-quiz-choice-{{question-id}}-{{choice-id}}">
	<td class="progressally-quiz-item-icon-col">*</td>
	<td class="progressally-quiz-item-description-col">
		<input type="hidden" progressally-param="quiz[question][{{question-id}}][order][]"
			   value="{{choice-id}}" />
		<input class="full-width" progressally-quiz-update-source="choice-html-{{question-id}}-{{choice-id}}" type="text" progressally-param="quiz[question][{{question-id}}][choice][{{choice-id}}][html]"
			   value="{{html}}" />
	</td>
	<td class="progressally-quiz-item-correct-col" s--quiz-type-score--w hide-toggle pa-dep="progressally-quiz-type" pa-dep-value="score">
		<input class="progressally-quiz-item-correct" type="radio" id="progressally-quiz-correct-checkbox-{{question-id}}-{{choice-id}}"
			   name="quiz[question][{{question-id}}][radio-correct]"
			   {{radio-correct}} value="{{choice-id}}" />
		<label for="progressally-quiz-correct-checkbox-{{question-id}}-{{choice-id}}" class="progressally-quiz-item-correct-label"></label>
	</td>
	<td class="progressally-quiz-item-outcome-col" s--quiz-type-survey--w hide-toggle pa-dep="progressally-quiz-type" pa-dep-value="survey">
		<select class="full-width progressally-outcome-selection"
			   progressally-param="quiz[question][{{question-id}}][choice][{{choice-id}}][select-survey-outcome]">
			<option value="">None</option>
			{{outcome-selection}}
		</select>
	</td>
	<td class="progressally-quiz-item-outcome-col" s--quiz-type-segment--w hide-toggle pa-dep="progressally-quiz-type" pa-dep-value="segment">
		<input class="full-width" type="text" progressally-param="quiz[question][{{question-id}}][choice][{{choice-id}}][segment-score]"
			   value="{{segment-score}}" />
	</td>
	<td class="progressally-quiz-item-delete-col">
		<div class="progressally-quiz-image-add-button progressally-add-choice" question-id="{{question-id}}" choice-id="{{choice-id}}"></div>
		<div class="progressally-quiz-image-delete-button progressally-quiz-choice-delete" question-id="{{question-id}}" choice-id="{{choice-id}}"
			 progressally-delete-warning="Deleting a choice cannot be undone. Continue?"></div>
	</td>
</tr>