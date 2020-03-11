<div class="progressally-event-container" id="progressally-event-container-{{id}}">
	<div id="progressally-event-container-readonly-view-{{id}}" {{show-readonly}}>
		<div class="progressally-event-header">
			<div class="progressally-edit-button" progressally-event-edit="{{id}}">Edit</div>
			<div class="progressally-event-header-desc">{{id}}. {{name}}</div>
			<div style="clear:both"></div>
		</div>
		<div class="progressally-event-readonly-view">
			<table class="progressally-setting-configure-table">
				<tr>
					<td class="progressally-event-readonly-desc-trigger-col">
						<div class="progressally-event-readonly-desc">{{trigger-descripton}}</div>
					</td>
					<td class="progressally-event-readonly-desc-arrow-col">
						<div class="progressally-event-readonly-arrow"></div>
					</td>
					<td class="progressally-event-readonly-desc-action-col">
						<div class="progressally-event-readonly-desc">{{action-descripton}}</div>
					</td>
				</tr>
			</table>
		</div>
	</div>
	<div id="progressally-event-container-edit-view-{{id}}" {{show-edit-view}}>
		<div class="progressally-event-header">
			<span class="progressally-setting-section-header">{{id}}. </span>
			<input type="text" progressally-param="name" size="50" value="{{name}}" />
		</div>
		<div class="progressally-event-edit-view">
			<div class="progressally-setting-section">
				<div class="progressally-setting-section-header">Event trigger condition</div>
				<div class="progressally-setting-configure-block">
					<select progressally-param="select-trigger-type" pa-dep-source="progressally-event-select-trigger-type-{{id}}">
						<option s--select-trigger-type--login--d value="login">when user logs in</option>
						<option s--select-trigger-type--visit--d value="visit">when user visits a page</option>
						<option s--select-trigger-type--objective--d value="objective">when user completes objective(s)</option>
						<option s--select-trigger-type--accessally--d value="accessally">through AccessAlly custom operation</option>
					</select>
				</div>

				<div class="progressally-setting-configure-block" hide-toggle="select-trigger-type" pa-dep="progressally-event-select-trigger-type-{{id}}" pa-dep-value="visit">
					<div>Select the pages</div>
					<div class="progressally-page-container" id="progressally-event-page-list-container-{{id}}">
						{{visit-pages}}
						<select variable-name="visit-page" class="progressally-autocomplete-add" entry-type="page">
							{{page-template-dummy}}
						</select>
					</div>
				</div>
				<div class="progressally-setting-configure-block" hide-toggle="select-trigger-type" pa-dep="progressally-event-select-trigger-type-{{id}}" pa-dep-value="objective">
					<div>Select the page</div>
					<select progressally-param="page-template-trigger-objective-page" class="progressally-autocomplete-add full-width" progressally-trigger-objective-update="{{id}}">
						{{page-template-trigger-objective-page}}
					</select>
					<div class="progressally-event-objective-list-container" id="progressally-event-trigger-objective-list-{{id}}" {{show-trigger-objective-list}}>
						{{trigger-objectives}}
					</div>
				</div>
			</div>
			<div class="progressally-setting-section">
				<div class="progressally-setting-section-header">How often can this event be triggered?</div>
				<div class="progressally-setting-configure-block">
					<select progressally-param="select-trigger-freq">
						<option s--select-trigger-freq--once--d value="once">Once</option>
						<option s--select-trigger-freq--infinite--d value="infinite">Each time the event happens</option>
					</select>
				</div>
			</div>
			<div class="progressally-setting-section">
				<div class="progressally-setting-section-header">What action will take place when the event is triggered?</div>
				<div class="progressally-setting-configure-block">
					<select progressally-param="select-action-type" pa-dep-source="progressally-event-select-action-type-{{id}}">
						<option s--select-action-type--tag--d value="tag">Add tag(s)</option>
						<option s--select-action-type--objective--d value="objective">Check objectives as complete</option>
					</select>
				</div>
				<div class="progressally-setting-configure-block" hide-toggle="select-action-type" hide-toggle pa-dep="progressally-event-select-action-type-{{id}}" pa-dep-value="tag">
					<div class="progressally-setting-section-sub-header">Add the following tag(s) to the contact</div>
					<div class="progressally-tag-container" id="progressally-event-action-tag-list-container-{{id}}">
						{{selected-action-tags}}
						<select variable-name="action-tag" class="progressally-autocomplete-add progressally-tag-input" entry-type="tag">
							{{tag-template-dummy}}
						</select>
					</div>
				</div>
				<div class="progressally-setting-configure-block" hide-toggle="select-action-type" pa-dep="progressally-event-select-action-type-{{id}}" pa-dep-value="objective">
					<div class="progressally-setting-section-sub-header">Select the page and objective(s) to mark as completed</div>
					<div class="progressally-setting-configure-block">
						<select class="progressally-autocomplete-add full-width" progressally-param="page-template-action-objective-page" progressally-action-objective-update="{{id}}">
							{{page-template-action-objective-page}}
						</select>
					</div>
					<div class="progressally-event-objective-list-container" id="progressally-event-action-objective-list-{{id}}" {{show-action-objective-list}}>
						{{action-objectives}}
					</div>
				</div>
			</div>
			<div class="progressally-setting-section">
				<div class="progressally-delete-button" progressally-event-delete="{{id}}">[-] Delete Event</div>
				<div class="progressally-save-button" progressally-event-save="{{id}}">Save</div>
				<div class="progressally-cancel-button" progressally-event-cancel="{{id}}">Cancel</div>
				<div style="clear:both"></div>
			</div>
		</div>
	</div>
</div>