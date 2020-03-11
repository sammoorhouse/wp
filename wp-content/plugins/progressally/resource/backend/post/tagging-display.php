<div class="progressally-setting-section" {{no-valid-tag-selection}}>
	<div class="progressally-setting-section-header">Tagging disabled.</div>
	<div class="progressally-setting-configure-block">
		<div class="progressally-setting-section-help-text">Please go to ProgressAlly -&gt; General Settings -&gt; Tagging to configure a valid CRM integration.</div>
	</div>
</div>
<div {{has-valid-tag-selection}}>
	<div class="progressally-setting-section progressally-setting-border">
		<div class="progressally-setting-configure-block">
			<div class="progressally-setting-section-header">Objective completion</div>
			<div class="progressally-setting-section-help-text">You can choose to add tag(s) when all the objectives have been checked off.</div>
		</div>
		<div class="progressally-setting-configure-block">
			<div class="progressally-setting-configure-block">
				<div class="progressally-setting-section-sub-header">Select the tag(s) to add when users have checked off all objectives</div>
				<div class="progressally-tag-container">
					{{existing-complete-tags}}
					<select class="progressally-autocomplete-add progressally-tag-input" variable-name="complete-tag">
						<option value=""></option>
						{{objective-completion-tag-selection}}
					</select>
				</div>
			</div>
		</div>
	</div>
	<div class="progressally-setting-section progressally-setting-border">
		<div class="progressally-setting-configure-block">
			<div class="progressally-setting-section-header">Facebook share</div>
			<div class="progressally-setting-section-help-text">You can choose to add a tag when uses the Social Share link to post a message on Facebook.</div>
		</div>
		<div class="progressally-setting-configure-block">
			<div class="progressally-setting-section-sub-header">Select the tag to add when users share on Facebook</div>
			<select class="progressally-autocomplete-add progressally-tag-input full-width" progressally-param="fb-automation-tag">
				<option value=""></option>
				{{facebook-share-tag-selection}}
			</select>
		</div>
	</div>
</div>