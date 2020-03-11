<div class="{{prefix}}progressally-quiz-container progressally-quiz-container">
	<form class="progressally-quiz progressally-quiz-{{post-id}}" progressally-ajax-action="progressally_submit_quiz" post-id="{{post-id}}">
		{{quiz-questions}}
	</form>
	<div class="progressally-quiz-result-container progressally-quiz-result-container-{{post-id}}" {{submit-row-display}}>
		{{quiz-result}}
	</div>
	<input type="hidden" id="progressally-quiz-current-page-{{ordinal}}" class="progressally-quiz-current-page-{{post-id}}" value="{{quiz-current-page}}" pa-dep-source="progressally-quiz-current-page-{{ordinal}}"/>
	<div class="progressally-quiz-reset-button-container progressally-quiz-reset-button-container-{{post-id}}" {{retake-button-display}}>
		<div class="progressally-quiz-button progressally-quiz-reset-button">{{retake-button-text}}</div>
		<div style="height:1px;clear:both"></div>
	</div>
	<div class="progressally-quiz-wait-overlay"></div>
</div>