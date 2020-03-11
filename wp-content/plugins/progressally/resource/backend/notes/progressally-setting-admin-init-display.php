<div class="progressally-setting-section">
	<div class="progressally-setting-section-header">Create admin-initiated notes</div>
	<input type="hidden" id="progressally-admin-init-current-step" pa-dep-source="progressally-admin-init-current-step" value="select" />

	<div hide-toggle pa-dep="progressally-admin-init-current-step" pa-dep-value="select">
		<div class="progressally-setting-configure-block">
			<div class="progressally-setting-section-sub-header">Choose the note that you are creating</div>
			<table class="progressally-filter-selection-table">
				<tbody>
					<tr>
						<th scope="row"><label for='progressally-admin-init-select-post'>Post/Page</label></th>
						<td>
							<select id='progressally-admin-init-select-post' pa-dep-source="progressally-admin-init-select-post" form-name="post">
								<option value=""></option>
								<?php echo $page_selection_code; ?>
							</select>
						</td>
					</tr>
					<?php echo $note_selection_code; ?>
				</tbody>
			</table>
			<div class="progressally-backend-admin-init-confirm-row" style="display:none;" hide-toggle pa-dep="progressally-admin-init-select-post" pa-dep-value-not="">
				<div class="progressally-backend-admin-init-confirm-button" id="progressally-backend-admin-init-confirm-selection-button">Create Notes</div>
				<div style="clear:both;"></div>
			</div>
		</div>
	</div>

	<div hide-toggle pa-dep="progressally-admin-init-current-step" pa-dep-value="add" style="display:none;">
		<div class="progressally-setting-admin-init-header-block">
			<div class="progressally-setting-section-sub-header">
				Creating private note for page:
				<a href="#" id="progressally-backend-admin-init-change-selection">Start over and choose a different note</a>
			</div>
			<div id="progressally-admin-init-create-header"></div>
		</div>
		<div class="progressally-setting-configure-block" id="progressally-admin-init-input">
			<input id="progressally-admin-init-post-id-input" type="hidden" value="" form-name="post">
			<input id="progressally-admin-init-note-id-input" type="hidden" value="" form-name="note-id">
			<div class="progressally-setting-configure-block">
				<label for="progressally-admin-init-contact-input"><strong>Find user by email:</strong></label>
				<input id="progressally-admin-init-contact-input" type="text" class="progressally-admin-init-reply" value="" form-name="user-email">
			</div>
			<div class="progressally-setting-configure-block tablenav">
				<div class="tablenav-pages">
					<span class="pagination-links">
						<a class="first-page" title="Go to the first page" progressally-admin-init-action="first">«</a>
						<a class="prev-page" title="Go to the previous page" progressally-admin-init-action="-1">‹</a>
						<span class="paging-input"><input class="current-page progressally-admin-init-reply" title="Current page" type="text" value="1" size="3" form-name="page-num" id='progressally-admin-init-page-input' /> of <span id="progressally-admin-init-max-page">1</span></span>
						<a class="next-page" title="Go to the next page" progressally-admin-init-action="1">›</a>
						<a class="last-page" title="Go to the last page"  progressally-admin-init-action="last">»</a>
					</span>
				</div>
			</div>
		</div>
		<div id="progressally-admin-init-container">
			<div id='progressally-admin-init-message-container'>
				Fetching data. Please wait
			</div>
		</div>
	</div>
</div>