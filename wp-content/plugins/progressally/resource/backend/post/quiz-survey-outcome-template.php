<div class="progressally-setting-outcome-block" id="progressally-quiz-outcome-{{outcome-id}}">
	<div class="progressally-outcome-header" toggle-target="#progressally-outcome-toggle-{{outcome-id}}" id="progressally-outcome-header-{{outcome-id}}">
		<div class="progressally-view-toggle-block">
			<input progressally-param="quiz[survey-outcome][{{outcome-id}}][checked-is-open]" {{checked-is-open}} type="checkbox" value="yes" toggle-group="progressally-outcome"
				   toggle-class="progressally-item-opened" progressally-toggle-element="#progressally-quiz-outcome-{{outcome-id}}" min-height="40" min-height-element="#progressally-outcome-header-{{outcome-id}}"
				   pa-dep-source="progressally-outcome-toggle-{{outcome-id}}" id="progressally-outcome-toggle-{{outcome-id}}">
			<label hide-toggle="checked-is-open" pa-dep="progressally-outcome-toggle-{{outcome-id}}" pa-dep-value="no">&#x25BC;</label>
			<label hide-toggle="checked-is-open" pa-dep="progressally-outcome-toggle-{{outcome-id}}" pa-dep-value="yes">&#x25B2;</label>
		</div>
		<div class="progressally-name-display-block">
			<div class="progressally-name-display" progressally-click-edit-show="{{outcome-id}}">
				<table class="progressally-header-table">
					<tbody>
						<tr>
							<td class="progressally-number-col">Outcome {{outcome-id}}. </td>
							<td class="progressally-name-label-col"><div class="progressally-name-label" progressally-click-edit-display="{{outcome-id}}">{{name}}</div></td>
							<td class="progressally-name-edit-col"><div class="progressally-pencil-icon" progressally-click-edit-trigger="{{outcome-id}}"></div></td>
						</tr>
					</tbody>
				</table>
			</div>
			<input progressally-param="quiz[survey-outcome][{{outcome-id}}][name]" class="progressally-name-edit progressally-outcome-name-edit full-width" progressally-click-edit-input="{{outcome-id}}"
				   style="display:none;" value="{{name}}" type="text" />
		</div>
	</div>
	<div hide-toggle="checked-is-open" pa-dep="progressally-outcome-toggle-{{outcome-id}}" pa-dep-value="yes">
		<div class='progressally-outcome-section'>
			<div class="progressally-setting-section-help-text">You can use HTML code in the outcome code.</div>
			<textarea class="full-width" progressally-param="quiz[survey-outcome][{{outcome-id}}][html]" rows="5">{{html}}</textarea>
		</div>
		<div class="progressally-outcome-section" {{has-valid-popup-selection}}>
			<div class="progressally-setting-section-sub-header">Show a PopupAlly Pro popup with this outcome</div>
			<div class="progressally-setting-section-help-text">
				<div class="progressally-info-icon"></div>
				Learn more in our <a href="https://access.accessally.com/progressally-quiz-outcome-optin" target="_blank">video tutorial</a>.
			</div>
			<div class="progressally-setting-configure-block">
				<select progressally-param="quiz[survey-outcome][{{outcome-id}}][select-popup-type]"
						pa-dep-source="progressally-survey-outcome-{{outcome-id}}-select-popup-type">
					<option s--select-popup-type--none--d value="none">Do not show a popup</option>
					<option s--select-popup-type--popup--d value="popup">Show the selected popup</option>
					<option s--select-popup-type--embedded--d value="embedded">Add the selected popup as an embedded opt-in at the end of the outcome text</option>
				</select>
			</div>
			<div class="progressally-setting-configure-block"
				 hide-toggle="select-popup-type" pa-dep="progressally-survey-outcome-{{outcome-id}}-select-popup-type" pa-dep-value-not="none">
				Popup to show
				<select progressally-param="quiz[survey-outcome][{{outcome-id}}][optin-popup]">
					<option value=""></option>
					{{popup-selection}}
				</select>
			</div>
		</div>
		<div class="progressally-outcome-section" {{has-valid-tag-selection}}>
			<div class="progressally-setting-configure-block">
				<div class="progressally-setting-section-sub-header">(Optional) Apply a tag when this outcome is reached:</div>
				<select class="progressally-autocomplete-add progressally-tag-input full-width" progressally-param="quiz[survey-outcome][{{outcome-id}}][access-tag]">
					<option value=""></option>
					{{tag-selection}}
				</select>
			</div>
		</div>
		<div class="progressally-outcome-section" {{has-valid-field-selection}}>
			<div class="progressally-setting-configure-block">
				<div class="progressally-setting-section-sub-header">(Optional) Assign the outcome score to the custom field</div>
				<select class="full-width" progressally-param="quiz[survey-outcome][{{outcome-id}}][value-field]">
					<option value=""></option>
					{{field-selection}}
				</select>
			</div>
		</div>
	</div>
</div>