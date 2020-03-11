<div class="progressally-setting-section">
	<div class="progressally-setting-section-header">View / reply to user notes</div>

	<div id="progressally-note-reply-input">
		<div class="progressally-setting-configure-block">
			<div class="progressally-setting-section-sub-header">Note filters</div>
			<table class="progressally-filter-selection-table">
				<tbody>
					<tr>
						<th scope="row"><label for='progressally-note-reply-select-status'>Status</label></th>
						<td>
							<select name='<?php echo ProgressAlly::SETTING_KEY_GENERAL . '[' . ProgressAllySettingNoteReply::SETTING_KEY; ?>][select-status]'
									id='progressally-note-reply-select-status' class="progressally-update-note-reply" form-name="status">
								<option value="all">All</option>
								<option selected="selected" value="new">Needs a reply</option>
								<option value="replied">Admin replied</option>
								<option value="ignored">Closed</option>
								<option value="note">Course notes</option>
								<option value="admin-init">Admin-initiated note</option>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for='progressally-note-reply-select-post'>Post/Page</label></th>
						<td>
							<select id='progressally-note-reply-select-post' class="progressally-update-note-reply" form-name="post">
								<option value="all">All</option>
								<?php echo $page_selection_code; ?>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="progressally-note-reply-note-id">Note ID</label>
						</th>
						<td>
							<input id="progressally-note-reply-note-id" class="progressally-update-note-reply" value="" form-name="note-id">
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="progressally-note-reply-contact-input">User email</label>
						</th>
						<td>
							<input id="progressally-note-reply-contact-input" type="text" class="progressally-update-note-reply" value="" form-name="user-email">
						</td>
					</tr>
					<tr>
						<th scope="row"><label for='progressally-note-reply-sort'>Show</label></th>
						<td>
							<select id='progressally-note-reply-sort' class="progressally-update-note-reply" form-name="sort">
								<option selected="selected" value="DESC">The newest notes first</option>
								<option value="ASC">The oldest notes first</option>
							</select>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="progressally-setting-configure-block tablenav">
			<div class="tablenav-pages">
				<span class="pagination-links">
					<a class="first-page" title="Go to the first page" progressally-note-reply-action="first">«</a>
					<a class="prev-page" title="Go to the previous page" progressally-note-reply-action="-1">‹</a>
					<span class="paging-input"><input class="current-page progressally-update-note-reply" title="Current page" type="text" value="1" size="3" form-name="page-num" id='progressally-note-reply-page-input' /> of <span id="progressally-note-reply-max-page">1</span></span>
					<a class="next-page" title="Go to the next page" progressally-note-reply-action="1">›</a>
					<a class="last-page" title="Go to the last page"  progressally-note-reply-action="last">»</a>
				</span>
			</div>
		</div>
	</div>
	<div id="progressally-note-reply-container">
		<div id='progressally-note-reply-message-container'>
			Fetching data. Please wait
		</div>
	</div>
</div>