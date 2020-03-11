<style id="progressally-objective-list-placeholder-css" type="text/css"></style>
<div class="progressally-setting-section progressally-setting-border">
	<div class="progressally-setting-section-header">Objectives</div>
	<div class="progressally-setting-section-help-text">Objectives allow you to create checklists, video bookmarks that autoplay your video, and to track when a quiz is completed. Simply add the objective shortcode to your page after adding objectives here.</div>

	<div class="progressally-setting-configure-block">
		<input type="hidden" id="progressally-objective-count" progressally-param="max-objective" value="{{current-max-id}}" />
		<table class="progressally-objective-table">
			<thead>
				<tr valign="top">
					<th class="objective-list-move-col"></th>
					<th class="objective-list-type-col">Type</th>
					<th class="objective-list-description-col">Description / Additional Parameters</th>
					<th class="objective-list-delete-col">Delete Objective</th>
				</tr>
			</thead>
			<tbody id="progressally-objective-list-content">
				{{objectives}}
			</tbody>
			<tfoot>
				<tr>
					<td colspan="4">
						<div class="progressally-objective-error-message">
							{{objective-error-message}}
						</div>
						<div class="progressally-button" id="progressally-add-objective">Add Objective</div>
					</td>
				</tr>
			</tfoot>
		</table>
	</div>
</div>
<div class="progressally-setting-section progressally-setting-border" {{show-completion-custom-operation}}>
	<div class="progressally-setting-configure-block">
		<div class="progressally-setting-section-header">Run AccessAlly Custom Operation on completion</div>
		<div class="progressally-setting-section-help-text">
			You can choose to run an AccessAlly Custom Operation when all the objectives have been completed.
		</div>
	</div>
	<div class="progressally-setting-configure-block">
		<div class="progressally-setting-section-sub-header">Select the AccessAlly Custom Operation to run</div>
		<select class="full-width" progressally-param="completion-custom-operation"
				pa-dep-source="progressally-completion-custom-operation-selection"
				id="progressally-completion-custom-operation-selection">
			<option value=""></option>
			{{completion-custom-operation-selection}}
		</select>
	</div>
	<div class="progressally-setting-configure-block"
		 hide-toggle="completion-custom-operation" pa-dep="progressally-completion-custom-operation-selection" pa-dep-value-not="">
		<div class="progressally-setting-section-sub-header">
			By default, the Custom Operation is only run the FIRST time when all objectives are checked.
		</div>
		<input type="checkbox" progressally-param="checked-completion-custom-operation-always" {{checked-completion-custom-operation-always}}
			   value="yes" id="progressally-checked-completion-custom-operation-always" />
		<label for="progressally-checked-completion-custom-operation-always">
			Run the Custom Operation EVERY time when all the objectives are checked. (Not recommended)
		</label>
	</div>
</div>
<div class="progressally-setting-section">
	<div class="progressally-setting-section-header">Objective Completion Popup</div>
	<div class="progressally-setting-section-help-text">When PopupAlly Pro is enabled, a popup can show after all objectives are completed. Please customize the display (popup) and style settings for the popup in PopupAlly Pro.</div>
	<div class="progressally-setting-configure-block" {{has-valid-popup-selection}}>
		<select progressally-param="completion-popup">
			<option value="">Do not show a popup</option>
			{{completion-popup-selection}}
		</select>
	</div>
	<div class="progressally-setting-configure-block" {{no-valid-popup-selection}}>
		PopupAlly Pro is not installed / enabled on your site. <a target="_blank" href="https://accessally.com/popupally-pro/">Check it out!</a>
	</div>
</div>