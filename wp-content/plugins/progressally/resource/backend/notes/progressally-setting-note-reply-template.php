<div class="progressally-note-reply-content-row" progressally-note-status="{{status-code}}" progressally-note-approve-status="{{approve-status-code}}" progressally-notes-container="{{row-id}}" id="progressally-view-block-note-reply-{{row-id}}">
	<div class="progressally-note-reply-inner-border">
		<div class="progressally-backend-note-reply-post-name"><a href="{{post-link}}" target="_blank">{{post-name}}</a></div>
		<div class="progressally-backend-note-reply-note-status" {{display-note-display-status}}>
			<div class="progressally-backend-note-reply-note-status-label"></div>
			<div class="progressally-backend-note-reply-note-status-text">{{note-display-status}}</div>
		</div>
		<div class="progressally-backend-note-reply-note-name">Note {{note-id}}. {{note-name}}</div>
		<div class="progressally-backend-note-reply-user-link">User: {{user-link}}</div>
		<div class="progressally-backend-note-reply-time">Updated: {{time}}</div>
		<div class="progressally-backend-note-reply-content">
			{{existing-notes}}
			<div class="progressally-backend-note-operation-container" progressally-notes-operation="{{row-id}}">
				<div class="progressally-backend-notes-ignore-button" progressally-notes-ignore="{{row-id}}">Close</div>
				<div class="progressally-backend-notes-reply-button" progressally-notes-update="{{row-id}}">Reply</div>
				<div class="progressally-backend-notes-approve-button" progressally-notes-approve="{{row-id}}">Approve without reply</div>
				<div style="clear:both;"></div>
			</div>
		</div>
	</div>
	<div class="progressally-backend-notes-update-input-wait" progressally-notes-operation-wait="{{row-id}}"></div>
</div>