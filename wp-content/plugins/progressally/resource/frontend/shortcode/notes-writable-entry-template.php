<div class="progressally-notes-update-display-container" progressally-notes-update-display-container="progressally-p{{post-id}}t-n{{note-id}}e-{{ordinal}}-{{note-ordinal}}">
	<div class="progressally-notes-update-button" progressally-notes-update="progressally-p{{post-id}}t-n{{note-id}}e-{{ordinal}}-{{note-ordinal}}">{{button-text}}</div>
	<div class="progressally-notes-display progressally-notes-display-{{ordinal}} progressally-notes-display-{{author}}"
		 progressally-placeholder="{{placeholder}}" progressally-placeholder-status="{{placeholder-status}}" post-id="{{post-id}}" note-id="{{note-id}}" ordinal="{{ordinal}}"
		 progressally-notes-update-display="progressally-p{{post-id}}t-n{{note-id}}e-{{ordinal}}-{{note-ordinal}}">
		{{value}}
		{{attachment}}
	</div>
</div>
<div class="progressally-notes-update-input-container progressally-notes-update-input-container-{{ordinal}}" style="display:none;"
	 progressally-notes-input-container="progressally-p{{post-id}}t-n{{note-id}}e-{{ordinal}}-{{note-ordinal}}">
	<textarea class="progressally-notes-update-textarea" rows="{{size}}" progressally-notes-value="progressally-p{{post-id}}t-n{{note-id}}e-{{ordinal}}-{{note-ordinal}}"
			  post-id="{{post-id}}" note-id="{{note-id}}" ordinal="{{ordinal}}" note-ordinal="{{note-ordinal}}">{{raw-value}}</textarea>
	{{add-attachment}}
	{{attachment-with-delete}}
	<div class="progressally-notes-operation-container" progressally-notes-operation-container="progressally-p{{post-id}}t-n{{note-id}}e-{{ordinal}}-{{note-ordinal}}">
		<span class="progressally-notes-cancel-button" progressally-notes-input-cancel="progressally-p{{post-id}}t-n{{note-id}}e-{{ordinal}}-{{note-ordinal}}">{{cancel-text}}</span>
		<span class="progressally-notes-save-button" progressally-notes-input-save="progressally-p{{post-id}}t-n{{note-id}}e-{{ordinal}}-{{note-ordinal}}">{{save-text}}</span>
		<div style="clear:both;"></div>
	</div>
	<div class="{{prefix}}progressally-notes-update-input-wait" progressally-notes-wait="progressally-p{{post-id}}t-n{{note-id}}e-{{ordinal}}-{{note-ordinal}}" style="display:none;"></div>
</div>