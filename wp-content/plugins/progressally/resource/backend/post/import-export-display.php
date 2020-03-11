<div class="progressally-setting-section progressally-setting-border">
	<div class="progressally-setting-configure-block">
		<div class="progressally-setting-section-header">Import Settings</div>
		<div class="progressally-setting-section-help-text">import the settings stored in a .progressally file (CAUTION: the current settings will be overwritten)</div>
	</div>
	<div class="progressally-setting-configure-block">
		<div><input id="progressally-import-file" type="file"></div>
	</div>
	<div class="progressally-setting-configure-block" id="progressally-import-selection" style="display:none;">
		<div><strong>Check the Sections You Would Like to Import/Overwrite:</strong></div>
		<table>
			<tbody>
				<tr><td><input type="checkbox" progressally-import-selection="objective" value='yes' id="progressally-import-objectives" checked="checked"><label for="progressally-import-objectives">Objectives</label></td></tr>
				<tr><td><input type="checkbox" progressally-import-selection="social" value='yes' id="progressally-import-social-share" checked="checked"><label for="progressally-import-social-share">Social Sharing</label></td></tr>
				<tr><td><input type="checkbox" progressally-import-selection="tagging" value='yes' id="progressally-import-tag" checked="checked"><label for="progressally-import-tag">Tagging</label></td></tr>
				<tr><td><input type="checkbox" progressally-import-selection="quiz" value='yes' id="progressally-import-quiz" checked="checked"><label for="progressally-import-quiz">Quiz</label></td></tr>
				<tr><td><input type="checkbox" progressally-import-selection="note" value='yes' id="progressally-import-note" checked="checked"><label for="progressally-import-note">Private Notes</label></td></tr>
				<tr><td><input type="checkbox" progressally-import-selection="certificate" value='yes' id="progressally-import-certificate" checked="checked"><label for="progressally-import-certificate">Certificates</label></td></tr>
			</tbody>
		</table>
	</div>
	<div class="progressally-setting-configure-block">
		<div id="progressally-import-button" style="display:none;" class="progressally-button" post-id="<?php echo $post_id ?>" title="Import display settings from a .progress file. The existing values may be overwritten">Import</div>
	</div>
</div>

<div class="progressally-setting-section">
	<div class="progressally-setting-configure-block">
		<div class="progressally-setting-section-header">Export Settings</div>
		<div class="progressally-setting-section-help-text">export all the ProgressAlly settings for this page/post to a file (please Update / Publish this page/post first if you have made modifications)</div>
	</div>
	<div class="progressally-setting-configure-block">
		<a class="progressally-button" target="_blank" href="<?php echo esc_url($nonce_download_url); ?>" title="Export the ProgressAlly settings to a .progressally file">Export</a>
	</div>
</div>