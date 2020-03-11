<div class="progressally-backend-admin-init-display progressally-backend-admin-init-display-{{author}}"
	 progressally-admin-init-reply-display="{{user-id}}" {{display-status}}>
	{{value}}
	{{attachment}}
</div>
<div class="progressally-backend-admin-init-input-container" style="display:none;" progressally-admin-init-input-container="{{user-id}}">
	<textarea class="progressally-backend-admin-init-textarea" rows="{{size}}" progressally-admin-init-input="{{user-id}}" ordinal="{{ordinal}}"
			  user-id="{{user-id}}">{{raw-value}}</textarea>
	<input type="checkbox" id="progressally-admin-init-format-{{user-id}}" {{format-checked}} progressally-admin-init-format="{{user-id}}" value="html" />
	<label for="progressally-admin-init-format-{{user-id}}">Enable HTML code</label>
	<div class="progressally-notes-attachment-container" {{allow-add-attachment}} progressally-notes-attachment-container="progressally-admin-init-{{row-id}}-{{ordinal}}">
		<div class="progressally-notes-attachment-add" progressally-notes-attachment-add="progressally-admin-init-{{row-id}}-{{ordinal}}">+ add attachment</div>
		<div class="progressally-notes-attachment-clear"></div>
	</div>
	{{attachment-with-delete}}
	<div class="progressally-backend-admin-init-operation-row">
		<span class="progressally-backend-admin-init-cancel-button" progressally-admin-init-cancel="{{user-id}}">Cancel</span>
		<span class="progressally-backend-admin-init-save-button" progressally-admin-init-save="{{user-id}}">Save</span>
		<div style="clear:both;"></div>
	</div>
	<div class="progressally-backend-notes-update-input-wait" id="progressally-wait-admin-init-{{user-id}}"></div>
</div>