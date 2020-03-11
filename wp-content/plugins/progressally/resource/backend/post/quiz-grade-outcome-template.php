<div class="progressally-setting-outcome-block {{outcome-opened-class}}" id="progressally-grade-outcome-{{outcome-id}}">
	<div class="progressally-outcome-header" toggle-target="#progressally-grade-outcome-toggle-{{outcome-id}}" id="progressally-grade-outcome-header-{{outcome-id}}">
		<div class="progressally-view-toggle-block">
			<input progressally-param="quiz[grade-outcome][{{outcome-id}}][checked-is-open]" {{checked-is-open}} type="checkbox" value="yes" toggle-group="progressally-grade-outcome"
				   toggle-class="progressally-item-opened" progressally-toggle-element="#progressally-grade-outcome-{{outcome-id}}" min-height="40" min-height-element="#progressally-grade-outcome-header-{{outcome-id}}"
				   pa-dep-source="progressally-grade-outcome-toggle-{{outcome-id}}" id="progressally-grade-outcome-toggle-{{outcome-id}}">
			<label hide-toggle="checked-is-open" pa-dep="progressally-grade-outcome-toggle-{{outcome-id}}" pa-dep-value="no">&#x25BC;</label>
			<label hide-toggle="checked-is-open" pa-dep="progressally-grade-outcome-toggle-{{outcome-id}}" pa-dep-value="yes">&#x25B2;</label>
		</div>
		<div class="progressally-name-display-block">
			<div class="progressally-name-display">
				<table class="progressally-header-table">
					<tbody>
						<tr>
							<td class="progressally-name-label-col"><div class="progressally-name-label" id="progressally-grade-outcome-{{outcome-id}}-title"></div></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div hide-toggle="checked-is-open" pa-dep="progressally-grade-outcome-toggle-{{outcome-id}}" pa-dep-value="yes">
		<div class='progressally-outcome-section'>
			<strong>If the learner scores <input class="progressally-quiz-outcome-score" progressally-param="quiz[grade-outcome][{{outcome-id}}][min-score]" size="3" type="text"
				   {{score-readonly}} value="{{min-score}}"
				   outcome-id="{{outcome-id}}" id="progressally-grade-outcome-{{outcome-id}}-min" /> % or higher, the outcome code below will show:</strong>
		</div>
		<div class="progressally-outcome-section">
			<input name="quiz[grade-outcome-threshold-id]" {{radio-grade-outcome-threshold-id}} class="progressally-grade-outcome-threshold"
				   type="radio" id="progressally-grade-outcome-{{outcome-id}}-threshold" value="{{outcome-id}}" />
			<label for="progressally-grade-outcome-{{outcome-id}}-threshold">Use this score as pass/fail threshold</label>
			<div class="progressally-setting-section-help-text">A quiz objective can only be fulfilled when the grade passes or equals to the threshold.</div>
		</div>
		<div class="progressally-outcome-section">
			<div class="progressally-setting-section-help-text">
				You can use HTML code in the outcome code. "{[percentage]}" can be used to add the score percentage to display the learner's results.
			</div>
			<textarea class="full-width" progressally-param="quiz[grade-outcome][{{outcome-id}}][html]" rows="5">{{html}}</textarea>
		</div>
		<div class="progressally-outcome-section" {{has-valid-popup-selection}}>
			<div class="progressally-setting-section-sub-header">Show a PopupAlly Pro popup with this outcome</div>
			<div class="progressally-setting-section-help-text">
				<div class="progressally-info-icon"></div>
				Learn more in our <a href="https://access.accessally.com/progressally-quiz-outcome-optin" target="_blank">video tutorial</a>.
			</div>
			<div class="progressally-setting-configure-block">
				<select progressally-param="quiz[grade-outcome][{{outcome-id}}][select-popup-type]"
						pa-dep-source="progressally-grade-outcome-{{outcome-id}}-select-popup-type">
					<option s--select-popup-type--none--d value="none">Do not show a popup</option>
					<option s--select-popup-type--popup--d value="popup">Show the selected popup</option>
					<option s--select-popup-type--embedded--d value="embedded">Add the selected popup as an embedded opt-in at the end of the outcome text</option>
				</select>
			</div>
			<div class="progressally-setting-configure-block"
				 hide-toggle="select-popup-type" pa-dep="progressally-grade-outcome-{{outcome-id}}-select-popup-type" pa-dep-value-not="none">
				Popup to show
				<select progressally-param="quiz[grade-outcome][{{outcome-id}}][optin-popup]">
					<option value=""></option>
					{{popup-selection}}
				</select>
			</div>
		</div>
		<div>
			<div class="progressally-delete-button progressally-float-right progressally-quiz-grade-outcome-delete-button" outcome-id="{{outcome-id}}"
				 progressally-delete-warning="Deleting an outcome cannot be undone. Continue?">[-] Delete Outcome</div>
			<div style="clear:both"></div>
		</div>
	</div>
</div>