<tr class="progressally-objective-list-row" id="progressally-objective-{{id}}" element-id="{{id}}">
	<td class="objective-list-move-col">
		<input type='hidden' progressally-param='objective-order[]' value="{{id}}" />
		<div class="progressally-setting-list-order-move"></div>
	</td>
	<td class="objective-list-type-col">
		<div class="objective-list-description-container">
			<select id="progressally-seek-type-{{id}}" progressally-objective-seek-type="{{id}}" progressally-param="objectives[{{id}}][seek-type]" pa-dep-source="progressally-seek-type-{{id}}">
				{{seek-type-selection}}
			</select>
		</div>
	</td>
	<td class="objective-list-description-col">
		<div class="objective-list-description-container">
			<input type="text" class="full-width" progressally-objective-name="{{id}}" progressally-param="objectives[{{id}}][description]" value="{{description}}" />
			<div hide-toggle="seek-type" pa-dep="progressally-seek-type-{{id}}" pa-dep-value="vimeo,youtube,wistia">
				<div class="objective-list-seek-time-container">
					<label for="progressally-seek-video-id-{{id}}">Play Video #</label>
					<input id="progressally-seek-video-id-{{id}}" size="2" type='text' progressally-param='objectives[{{id}}][seek-id]' value="{{seek-id}}" />
					At
					<input size="3" type='text' progressally-param='objectives[{{id}}][seek-time-minute]' value="{{seek-time-minute}}" />
					minutes
					<input size="2" type='text' progressally-param='objectives[{{id}}][seek-time-second]' value="{{seek-time-second}}" />
					seconds
				</div>
				<div class="objective-list-seek-time-container">
					<input id="progressally-checked-complete-video-{{id}}" progressally-objective-video-complete="{{id}}" pa-dep-source="progressally-checked-complete-video-{{id}}" type="checkbox"
						   progressally-param='objectives[{{id}}][checked-complete-video]' {{checked-complete-video}} value="yes" />
					<label for="progressally-checked-complete-video-{{id}}">This objective will be marked complete when the video is watched to...</label>
				</div>
				<div class="objective-list-seek-time-container" hide-toggle="checked-complete-video" pa-dep="progressally-checked-complete-video-{{id}}" pa-dep-value="yes">
					<input size="3" type='text' progressally-param='objectives[{{id}}][complete-time-minute]' value="{{complete-time-minute}}" />
					minutes
					<input size="2" type='text' progressally-param='objectives[{{id}}][complete-time-second]' value="{{complete-time-second}}" />
					seconds
				</div>
			</div>
			<div class="objective-list-post-select-container" hide-toggle="seek-type" pa-dep="progressally-seek-type-{{id}}" pa-dep-value="post">
				<select progressally-param="objectives[{{id}}][ref-post-id]" class="progressally-autocomplete-add full-width">
					<option value="-1"></option>
					{{select-page-options}}
				</select>
			</div>
			<div class="objective-list-note-select-container" hide-toggle="seek-type" pa-dep="progressally-seek-type-{{id}}" pa-dep-value="note">
				<select progressally-param="objectives[{{id}}][note-id]" progressally-objective-note-select="{{id}}" class="full-width">
					<option value="0"></option>
					{{select-note-options}}
				</select>
			</div>
		</div>
	</td>
	<td class="objective-list-delete-col"><div class="progressally-delete-button progressally-objective-delete" progressally-delete-element="#progressally-objective-{{id}}">Delete</div></td>
</tr>