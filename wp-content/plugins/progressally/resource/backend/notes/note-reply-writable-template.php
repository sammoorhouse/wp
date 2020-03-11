<div class="progressally-backend-notes-display progressally-backend-notes-display-{{author}}" progressally-notes-reply-display="{{row-id}}" {{display-status}}>
	{{value}}
	{{attachment}}
</div>
<div class="progressally-backend-notes-update-input-container" style="display:none;" progressally-notes-reply-input-container="{{row-id}}">
	<textarea class="progressally-backend-notes-update-textarea" rows="{{size}}" progressally-notes-reply-input="{{row-id}}" post-id="{{post-id}}" note-id="{{note-id}}" ordinal="{{ordinal}}" user-id="{{user-id}}">{{raw-value}}</textarea>
	<input type="checkbox" id="progressally-notes-reply-format-{{row-id}}" {{format-checked}} progressally-notes-reply-format="{{row-id}}" value="html" />
	<label for="progressally-notes-reply-format-{{row-id}}">Enable HTML code</label>
	<div class="progressally-notes-attachment-container" {{allow-add-attachment}} progressally-notes-attachment-container="progressally-notes-reply-{{row-id}}-{{ordinal}}">
		<div class="progressally-notes-attachment-add" progressally-notes-attachment-add="progressally-notes-reply-{{row-id}}-{{ordinal}}">+ add attachment</div>
		<div class="progressally-notes-attachment-clear"></div>
	</div>
	{{attachment-with-delete}}
	<div class="progressally-backend-notes-reply-operation-row">
		<span class="progressally-backend-notes-cancel-button" progressally-notes-reply-cancel="{{row-id}}">Cancel</span>
		<span class="progressally-backend-notes-save-button" progressally-notes-reply-save="{{row-id}}">Save</span>
		<span class="progressally-backend-notes-save-wo-approve-button" progressally-notes-reply-save="{{row-id}}">Reply but not approve</span>
		<span class="progressally-backend-notes-save-approve-button" progressally-notes-reply-save-approve="{{row-id}}">Reply and approve</span>
		<div style="clear:both;"></div>
	</div>
	<div class="progressally-backend-notes-update-input-wait" id="progressally-wait-note-reply-{{row-id}}"></div>
</div>