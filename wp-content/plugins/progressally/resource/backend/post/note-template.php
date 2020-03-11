<div class="progressally-setting-accordion-block {{open-class}}" id="progressally-note-block-{{note-id}}">
	<div class="progressally-setting-accordion-header" progressally-toggle-target="#progressally-note-toggle-{{note-id}}" id="progressally-setting-note-header-{{note-id}}">
		<div class="progressally-view-toggle-block">
			<input progressally-param="notes[{{note-id}}][checked-is-open]" {{checked-is-open}} type="checkbox" value="yes"
				   toggle-class="progressally-accordion-opened" progressally-toggle-element="#progressally-note-block-{{note-id}}" min-height="40"
				   min-height-element="#progressally-setting-note-header-{{note-id}}"
				   pa-dep-source="progressally-note-toggle-{{note-id}}" id="progressally-note-toggle-{{note-id}}">
			<label hide-toggle="checked-is-open" pa-dep="progressally-note-toggle-{{note-id}}" pa-dep-value="no">&#x25BC;</label>
			<label hide-toggle="checked-is-open" pa-dep="progressally-note-toggle-{{note-id}}" pa-dep-value="yes">&#x25B2;</label>
		</div>
		<div class="progressally-name-display-block">
			<div class="progressally-name-display" progressally-click-edit-show="note-name-{{note-id}}">
				<table class="progressally-header-table">
					<tbody>
						<tr>
							<td class="progressally-note-number-col">{{note-id}}. </td>
							<td class="progressally-name-label-col"><div class="progressally-name-label" progressally-click-edit-display="note-name-{{note-id}}">{{name}}</div></td>
							<td class="progressally-name-edit-col"><div class="progressally-pencil-icon" progressally-click-edit-trigger="note-name-{{note-id}}"></div></td>
						</tr>
					</tbody>
				</table>
			</div>
			<input progressally-param="notes[{{note-id}}][name]" class="progressally-name-edit progressally-note-name full-width" progressally-note-name-input="{{note-id}}" progressally-click-edit-input="note-name-{{note-id}}"
				   style="display:none;" value="{{name}}" type="text" />
		</div>
		<div style="clear:both;"></div>
	</div>
	<div class="progressally-setting-accordion-setting-section" hide-toggle="checked-is-open" pa-dep="progressally-note-toggle-{{note-id}}" pa-dep-value="yes">
		<div class="progressally-setting-configure-block">
			<div class="progressally-setting-configure-block">
				<div class="progressally-setting-section-header">Give your note some context</div>
				<div class="progressally-setting-section-help-text">Ask a question or start with a prompt (HTML code allowed).</div>
			</div>
			<div class="progressally-setting-configure-block">
				<textarea class="full-width" progressally-param="notes[{{note-id}}][title]" rows="5">{{title}}</textarea>
			</div>
		</div>
		<div class="progressally-setting-configure-block">
			<div class="progressally-setting-configure-block">
				<div class="progressally-setting-section-header">Placeholder text</div>
			</div>
			<div class="progressally-setting-configure-block">
				<input class="full-width" type="text" progressally-param="notes[{{note-id}}][placeholder]" value="{{placeholder}}" />
			</div>
		</div>
		<div class="progressally-setting-configure-block">
			<div class="progressally-setting-configure-block">
				<div class="progressally-setting-section-header">Type</div>
			</div>
			<div class="progressally-setting-configure-block">
				<select progressally-param="notes[{{note-id}}][select-type]" progressally-note-type="{{note-id}}" pa-dep-source="progressally-note-select-type-{{note-id}}">
					<option s--select-type--note--d value="note">Private course note</option>
					<option s--select-type--qa--d value="qa">User-specific question and answer</option>
					<option s--select-type--admin--d value="admin">Admin-initiated comment</option>
					<option s--select-type--approve--d value="approve">Admin reviewed answer</option>
					<option s--select-type--custom--d value="custom">Custom</option>
				</select>
			</div>
			<div class="progressally-inline-help-text" hide-toggle="select-type" pa-dep="progressally-note-select-type-{{note-id}}" pa-dep-value="note">
				<p><strong>Private course note</strong> allows a user to write down notes specific to the module / course.</p>
				<ul class="progressally-list">
					<li>The admin(s) will not be notified when the user writes down a note.</li>
					<li>Users can update their own notes as often as needed.</li>
				</ul>
			</div>
			<div class="progressally-setting-configure-block" hide-toggle="select-type" pa-dep="progressally-note-select-type-{{note-id}}" pa-dep-value="qa">
				<div class="progressally-inline-help-text">
					<p><strong>User-specific question and answer</strong> allows admin(s) to reply to user-specific questions.</p>
					<ul class="progressally-list">
						<li>Admin(s) will be notified when the user writes down a question.</li>
						<li>Users will be notified when the admin(s) reply.</li>
						<li>Users cannot change the question once the admin(s) have replied.</li>
					</ul>
				</div>
			</div>
			<div class="progressally-inline-help-text" hide-toggle="select-type" pa-dep="progressally-note-select-type-{{note-id}}" pa-dep-value="admin">
				<p><strong>Admin-initiated comment</strong> allows admins to add a comment / content to a page that is specific to each user.</p>
				<ul class="progressally-list">
					<li>Users will not be notified when the comment is created.</li>
					<li>Users cannot reply to the comment.</li>
				</ul>
			</div>
			<div class="progressally-inline-help-text" hide-toggle="select-type" pa-dep="progressally-note-select-type-{{note-id}}" pa-dep-value="approve">
				<p><strong>Admin reviewed answer</strong> is used with the <strong>Note</strong> objective, which is only marked as completed when the admin(s) approve the answer.</p>
				<ul class="progressally-list">
					<li>Admin(s) will be notified when the user submits a note.</li>
					<li>Users will be notified when the admin(s) reply.</li>
					<li>Users cannot change the note once the admin(s) have replied.</li>
				</ul>
			</div>
			<div class="progressally-setting-configure-block" hide-toggle="select-type" pa-dep="progressally-note-select-type-{{note-id}}" pa-dep-value="custom">
				<div class="progressally-setting-configure-block">
					<input type="checkbox" progressally-param="notes[{{note-id}}][checked-admin-initiated]" {{checked-admin-initiated}} value="yes" id="progressally-note-admin-initiated-{{note-id}}" />
					<label for="progressally-note-admin-initiated-{{note-id}}">Admin-initiated</label>
				</div>
				<div class="progressally-setting-configure-block">
					<input type="checkbox" progressally-param="notes[{{note-id}}][checked-approve]" {{checked-approve}} value="yes" id="progressally-note-approve-{{note-id}}" />
					<label for="progressally-note-approve-{{note-id}}">Require Admin approval to complete the corresponding objective.</label>
				</div>
				<div class="progressally-setting-configure-block">
					<input type="text" progressally-param="notes[{{note-id}}][num-reply]" size="4" value="{{num-reply}}" id="progressally-note-num-reply-{{note-id}}" />
					<label for="progressally-note-num-reply-{{note-id}}">Max number of user replies (enter -1 for unlimited)</label>
				</div>
				<div class="progressally-setting-configure-block">
					<input type="checkbox" progressally-param="notes[{{note-id}}][checked-notify-admin]" {{checked-notify-admin}} value="yes" id="progressally-note-notify-admin-{{note-id}}" />
					<label for="progressally-note-notify-admin-{{note-id}}">Notify admins when users create a comment / reply</label>
				</div>
				<div class="progressally-setting-configure-block">
					<input type="checkbox" progressally-param="notes[{{note-id}}][checked-notify-user]" {{checked-notify-user}} value="yes" id="progressally-note-notify-user-{{note-id}}" pa-dep-source="progressally-note-notify-user-{{note-id}}" />
					<label for="progressally-note-notify-user-{{note-id}}">Notify users when admins reply (does not apply to the first admin-initiated comment)</label>
				</div>
			</div>
			<div class="progressally-setting-email-customization-block" hide-toggle="checked-notify-user" pa-dep="progressally-note-notify-user-{{note-id}}" pa-dep-value="yes">
				<input type="checkbox" progressally-param="notes[{{note-id}}][checked-custom-email]" id="progressally-custom-email-{{note-id}}" value='yes'
					   pa-dep-source="progressally-custom-email-{{note-id}}" {{checked-custom-email}} />
				<label for="progressally-custom-email-{{note-id}}">Customize the notification email</label>
				<div hide-toggle="checked-custom-email" pa-dep="progressally-custom-email-{{note-id}}" pa-dep-value="yes">
					<div class="progressally-setting-configure-block">
						<div class="progressally-setting-section-sub-header">Email subject</div>
						<input type="text" class="full-width" progressally-param="notes[{{note-id}}][custom-email-subject]"
							   value="{{custom-email-subject}}" />
					</div>
					<div class="progressally-setting-configure-block">
						<div class="progressally-setting-section-sub-header">Email content</div>
						<textarea rows="20" class="full-width" progressally-param="notes[{{note-id}}][custom-email-content]"">{{custom-email-content}}</textarea>
					</div>
				</div>
			</div>
		</div>
		<div>
			<div class="progressally-delete-button progressally-note-delete progressally-float-right" progressally-delete-element="#progressally-note-block-{{note-id}}"
				 progressally-delete-warning="Deleting a note cannot be undone. Continue?" progressally-private-note-delete="{{note-id}}">[-] Delete Note</div>
			<div style="display:none" class="progressally-setting-note-usage progressally-float-right" progressally-private-note-in-use="{{note-id}}"></div>
			<div style="clear:both"></div>
		</div>
	</div>
</div>