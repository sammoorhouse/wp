<div class="progressally-dashboard-page-row-wrapper">
	<div class="progressally-dashboard-page-row">
		<div class="progressally-dashboard-page-attribute">
			<div class="progressally-dashboard-page-detail-view-toggle-block">
				<input type="checkbox" value="yes" style="display:none;" class="progressally-dashboard-page-detail-toggle" id="progressally-dashboard-page-detail-toggle-{{post-id}}" pa-dep-source="progressally-dashboard-page-detail-toggle-{{post-id}}">
				<label for="progressally-dashboard-page-detail-toggle-{{post-id}}" hide-toggle pa-dep="progressally-dashboard-page-detail-toggle-{{post-id}}" pa-dep-value="no">&#x25BC;</label>
				<label for="progressally-dashboard-page-detail-toggle-{{post-id}}" style="display:none;" hide-toggle pa-dep="progressally-dashboard-page-detail-toggle-{{post-id}}" pa-dep-value="yes">&#x25B2;</label>
			</div>
		</div>
		<div class="progressally-dashboard-page-title"><a target="_blank" href="{{edit-link}}">{{title}}</a></div>
		<div style="clear:both"></div>
	</div>
	<div class="progressally-dashboard-page-detail">
		<div class="progressally-dashboard-page-detail-title">Overall Completion</div>
		<div class="progressally-dashboard-page-detail-progress">
			{{completion-rate}}
		</div>
		<div class="progressally-dashboard-page-detail-title">Quiz Statistics</div>
		<div class="progressally-dashboard-page-detail-quiz">
			{{quiz-stats}}
		</div>
		<div class="progressally-dashboard-page-detail-link-container">
			<a href="#" class="progressally-dashboard-page-detailview-link" post-id="{{post-id}}">See more detail on this page &raquo;</a>
		</div>
	</div>
</div>