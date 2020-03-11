<div class="progressally-setting-accordion-block {{open-class}}" id="progressally-share-block-{{share-id}}">
	<div class="progressally-setting-accordion-header" progressally-toggle-target="#progressally-share-toggle-{{share-id}}" id="progressally-setting-share-header-{{share-id}}">
		<div class="progressally-view-toggle-block">
			<input progressally-param="social-sharing[shares][{{share-id}}][checked-is-open]" {{checked-is-open}} type="checkbox" value="yes"
				   toggle-class="progressally-accordion-opened" progressally-toggle-element="#progressally-share-block-{{share-id}}" min-height="40"
				   min-height-element="#progressally-setting-share-header-{{share-id}}"
				   pa-dep-source="progressally-share-toggle-{{share-id}}" id="progressally-share-toggle-{{share-id}}">
			<label hide-toggle="checked-is-open" pa-dep="progressally-share-toggle-{{share-id}}" pa-dep-value="no">&#x25BC;</label>
			<label hide-toggle="checked-is-open" pa-dep="progressally-share-toggle-{{share-id}}" pa-dep-value="yes">&#x25B2;</label>
		</div>
		<div class="progressally-name-display-block">
			<div class="progressally-name-display" progressally-click-edit-show="share-name-{{share-id}}">
				<table class="progressally-header-table">
					<tbody>
						<tr>
							<td class="progressally-share-number-col">{{share-id}}. </td>
							<td class="progressally-name-label-col"><div class="progressally-name-label" progressally-click-edit-display="share-name-{{share-id}}">{{name}}</div></td>
							<td class="progressally-name-edit-col"><div class="progressally-pencil-icon" progressally-click-edit-trigger="share-name-{{share-id}}"></div></td>
						</tr>
					</tbody>
				</table>
			</div>
			<input progressally-param="social-sharing[shares][{{share-id}}][name]" class="progressally-name-edit progressally-share-name full-width" progressally-share-name-input="{{share-id}}" progressally-click-edit-input="share-name-{{share-id}}"
				   style="display:none;" value="{{name}}" type="text" />
		</div>
		<div style="clear:both;"></div>
	</div>
	<div class="progressally-setting-accordion-setting-section" hide-toggle="checked-is-open" pa-dep="progressally-share-toggle-{{share-id}}" pa-dep-value="yes">
		<table class="progressally-setting-configure-table" >
			<tbody>
				<tr class="progressally-setting-configure-table-row">
					<th scope="row" class="progressally-setting-configure-table-header-col">
						Link To Share on Social
					</th>
					<td>
						<input class="full-width" type="text" progressally-param="social-sharing[shares][{{share-id}}][sharing-url]" value="{{sharing-url}}" />
					</td>
				</tr>
				<tr class="progressally-setting-configure-table-row">
					<th scope="row" class="progressally-setting-configure-table-header-col">
						Link Description
					</th>
					<td>
						<input class="full-width" type="text" progressally-param="social-sharing[shares][{{share-id}}][sharing-text]" value="{{sharing-text}}" />
					</td>
				</tr>
				<tr class="progressally-setting-configure-table-row">
					<th scope="row" class="progressally-setting-configure-table-header-col">
						Image URL To Accompany Your Link
					</th>
					<td>
						<input class="full-width" type="text" progressally-param="social-sharing[shares][{{share-id}}][sharing-image]" value="{{sharing-image}}" />
					</td>
				</tr>
			</tbody>
		</table>
		<div>
			<div class="progressally-delete-button progressally-share-delete progressally-float-right" progressally-delete-element="#progressally-share-block-{{share-id}}"
				 progressally-delete-warning="Deleting a sharing cannot be undone. Continue?" progressally-social-sharing-delete="{{share-id}}">[-] Delete Sharing</div>
			<div style="clear:both"></div>
		</div>
	</div>
</div>