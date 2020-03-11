<div id="progressally-insert-backdrop" style="display:none;"></div>
<div id="progressally-insert-wrap" class="progressally-edit-window" style="display:none;">
	<form id="progressally-insert-form" tabindex="-1">
		<div id="progressally-insert-title">
			Insert ProgressAlly Shortcode
			<button type="button" id="progressally-insert-close"></button>
		</div>
		<div id="progressally-insert-content">
			<div class="progressally-insert-options">
				<div class="progressally-insert-row">
					<label class="progressally-insert-label">Shortcode for </label>
					<select class="progressally-insert-value" id="progressally-insert-type-select" pa-dep-source="progressally-insert-type-select" name="shortcode-type">
						<option value="objective-list" additional-input="objective-list">Objective List</option>
						<option value="objective-completion" additional-input="objective-completion">Objective Completion</option>
						<option value="quiz">Quiz</option>
						<option value="progress" additional-input="progress-type">Progress Information</option>
						<option value="video" additional-input="video">Embedded Video</option>
						<option value="social-share" additional-input="social-share">Social Share</option>
						<option value="note" additional-input="note">Private Note</option>
						<option value="certificate" additional-input="certificate">Certificate Download</option>
						<option value="complete-button" additional-input="complete-button">Mark As Done Button</option>
					</select>
					<div>
						<span class="progressally-setting-section-help-text" hide-toggle pa-dep="progressally-insert-type-select" pa-dep-value="objective-list">
							Shortcode to insert a list of objectives that visitors can check-off.
						</span>
						<span class="progressally-setting-section-help-text" hide-toggle pa-dep="progressally-insert-type-select" pa-dep-value="objective-completion">
							Shortcode to insert content showing once all objectives are completed.
						</span>
						<span class="progressally-setting-section-help-text" hide-toggle pa-dep="progressally-insert-type-select" pa-dep-value="quiz">
							Shortcode to insert the quiz.
						</span>
						<span class="progressally-setting-section-help-text" hide-toggle pa-dep="progressally-insert-type-select" pa-dep-value="progress">
							Shortcode to insert a progress percentage / pie chart / bar.
						</span>
						<span class="progressally-setting-section-help-text" hide-toggle pa-dep="progressally-insert-type-select" pa-dep-value="video">
							Shortcode to insert an embedded YouTube/Vimeo/Wistia video.
						</span>
						<span class="progressally-setting-section-help-text" hide-toggle pa-dep="progressally-insert-type-select" pa-dep-value="social-share">
							Shortcode to insert a button to share the progess message on social media.
						</span>
						<span class="progressally-setting-section-help-text" hide-toggle pa-dep="progressally-insert-type-select" pa-dep-value="note">
							Shortcode to insert a note section that allows users to enter private notes / question to the admin.
						</span>
						<span class="progressally-setting-section-help-text" hide-toggle pa-dep="progressally-insert-type-select" pa-dep-value="certificate">
							Shortcode to insert a download button / URL to download the customized certificate.
						</span>
						<span class="progressally-setting-section-help-text" hide-toggle pa-dep="progressally-insert-type-select" pa-dep-value="complete-button">
							Shortcode to insert a button that will mark objective(s) as completed.
						</span>
					</div>
					<div progressally-insert-additional-input="objective-list" style="display:none;">
						<div class="progressally-setting-configure-block">
							Show the objective list for
							<select type="text" id="progressally-mce-objective-list-post-id" class="progressally-autocomplete-add" pa-dep-source="progressally-mce-objective-list-post-id">
								<option value="0" selected="selected">Current Post</option>
								<?php echo $post_selection; ?>
							</select>
						</div>
						<div class="progressally-setting-configure-block" hide-toggle pa-dep="progressally-mce-objective-list-post-id" pa-dep-value="0">
							<input type="checkbox" value="yes" id="progressally-mce-objective-list-partial" pa-dep-source="progressally-mce-objective-list-partial" />
							<label for="progressally-mce-objective-list-partial">Do NOT show all objectives and only show the selected one(s).</label>
							<div class="progressally-mce-objective-selection-container" style="display:none"
								 hide-toggle pa-dep="progressally-mce-objective-list-partial" pa-dep-value="yes">
								<ul id="progressally-mce-objective-list-selection"><?php echo $selection_code['partial-display']; ?></ul>
							</div>
						</div>
					</div>
					<div progressally-insert-additional-input="objective-completion" style="display:none;">
						<div class="progressally-setting-configure-block">
							Show the content after a user has completed
							<input type="text" size="3" id="progressally-mce-objective-completion-percentage" value="100" />
							% of objectives.
						</div>
					</div>
					<div progressally-insert-additional-input="video" style="display:none;">
						<div class="progressally-setting-configure-block">
							<table class="progressally-setting-configure-table">
								<tbody>
									<tr>
										<td style="width:40%;">
											Video type
											<select type="text" id="progressally-post-video-type-select" pa-dep-source="progressally-post-video-type-select">
												<option value="youtube">YouTube</option>
												<option value="vimeo">Vimeo</option>
												<option value="wistia">Wistia</option>
											</select>
										</td>
										<td style="width:30%;">
											Width <input size="5" type="text" id="progressally-post-video-width" value="600" /> px
										</td>
										<td style="width:30%;">
											Height <input size="5" type="text" id="progressally-post-video-height" value="350" /> px
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="progressally-setting-configure-block">
							<table class="progressally-setting-configure-table">
								<tbody>
									<tr>
										<td style="width:25%;">
											<label for="progressally-post-video-id">Video ID</label>
										</td>
										<td style="width:25%;">
											<input size="10" type="text" id="progressally-post-video-id" />
										</td>
										<td style="width:50%;">
											<div class="progressally-inline-help-text">Watch <a target="_blank" href="https://access.accessally.com/progressally-video-bookmark">the tutorials</a> to see where to get the video ID.</div>
										</td>
									</tr>
									<tr>
										<td style="width:25%;">
											<label for="progressally-post-video-progress-id">ProgressAlly ID</label>
										</td>
										<td style="width:25%;">
											<input size="10" type="text" id="progressally-post-video-progress-id" />
										</td>
										<td style="width:50%;">
											<div class="progressally-inline-help-text">Choose an ID for this video and match it to the video objective.</div>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="progressally-setting-configure-block" hide-toggle pa-dep="progressally-post-video-type-select" pa-dep-value="youtube,wistia">
							<input type="checkbox" value="yes" id="progressally-post-video-hide-control" />
							<label for="progressally-post-video-hide-control">Hide playback controls (so visitors can&#39;t skip the video)</label>
						</div>
						<div class="progressally-setting-configure-block" style="display:none" hide-toggle pa-dep="progressally-post-video-type-select" pa-dep-value="vimeo">
							<label>You can hide the playback controls in the video Embed settings on <a target="_blank" href="https://vimeo.com/">Vimeo</a></label>
						</div>
					</div>
					<div progressally-insert-additional-input="progress-type" style="display:none;">
						<div class="progressally-setting-configure-block">
							Show the
							<select type="text" id="progressally-progress-type-select" pa-dep-source="progressally-progress-type-select">
								<option value="text">Progress percentage</option>
								<option value="pie-chart">Progress pie chart</option>
								<option value="bar">Progress bar</option>
								<option value="objective-count">Total objective count</option>
								<option value="objective-completed-count">Completed objective count</option>
							</select>
							for
							<select type="text" id="progressally-post-id" class="progressally-autocomplete-add">
								<option value="0" selected="selected">Current Post</option>
								<?php echo $post_selection; ?>
							</select>
						</div>
						<div class="progressally-setting-configure-block" style="display:none;" hide-toggle pa-dep="progressally-progress-type-select" pa-dep-value="bar">
							<table class="progressally-setting-configure-table">
								<tbody>
									<tr>
										<td style="width:20%">
											<label for="progressally-bar-width">Width</label>
										</td>
										<td style="width:30%;">
											<input style="width:50%;" type="text" id="progressally-bar-width" />
											<select id="progressally-bar-width-postfix">
												<option value="px">px</option>
												<option value="%">%</option>
											</select>
										</td>
										<td rowspan="2"><div class="progressally-inline-help-text">Leave width and height blank to use the default styling.</div></td>
									</tr>
									<tr>
										<td style="width:20%">
											<label for="progressally-bar-height">Height</label>
										</td>
										<td style="width:30%;">
											<input style="width:50%;" type="text" id="progressally-bar-height" /> px
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="progressally-setting-configure-block" style="display:none;" hide-toggle pa-dep="progressally-progress-type-select" pa-dep-value="pie-chart">
							<table class="progressally-setting-configure-table">
								<tbody>
									<tr>
										<td style="width:10%">
											<label for="progressally-pie-chart-size">Diameter</label>
										</td>
										<td>
											<input style="width:50%;" type="text" id="progressally-pie-chart-size" value='100'/> px
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					<div progressally-insert-additional-input="social-share" style="display:none;">
						<div class="progressally-setting-configure-block">
							Share
							<select id="progressally-mce-editor-share-id-select"><?php echo $selection_code['social-share']; ?></select>
							on
							<select id="progressally-social-share-select">
								<option value="facebook">Facebook</option>
								<option value="twitter">Twitter</option>
								<option value="pinterest">Pinterest</option>
								<option value="email">Email</option>
							</select>
						</div>
					</div>
					<div progressally-insert-additional-input="note" style="display:none;">
						<div class="progressally-setting-configure-block">
							<table class="progressally-setting-configure-table">
								<tbody>
									<tr>
										<td style="width:100px">
											<label for="progressally-mce-editor-note-id-select">Note to insert </label>
										</td>
										<td>
											<select id="progressally-mce-editor-note-id-select"><?php echo $selection_code['private-note']; ?></select>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="progressally-setting-configure-block" <?php echo $allow_attachment ? '' : 'style="display:none"'; ?>>
							<input type="checkbox" value="yes" id="progressally-mce-notes-allow-attachment" <?php echo $allow_attachment ? 'checked="checked"' : ''; ?> />
							<label for="progressally-mce-notes-allow-attachment">Allow users to add attachments.</label>
						</div>
					</div>
					<div progressally-insert-additional-input="certificate" style="display:none;">
						<div class="progressally-setting-configure-block">
							<table class="progressally-setting-configure-table">
								<tbody>
									<tr hide-toggle pa-dep="progressally-mce-certificate-post-id" pa-dep-value="0">
										<td style="width:150px">
											<label for="progressally-mce-editor-certificate-id-select">Certificate to insert </label>
										</td>
										<td>
											<select id="progressally-mce-editor-certificate-id-select"></select>
											<span id="progressally-mce-editor-certificate-id-no-option">Please create a certificate before adding the shorcode</span>
										</td>
									</tr>
									<tr hide-toggle pa-dep="progressally-mce-certificate-post-id" pa-dep-value-not="0" style="display:none;">
										<td style="width:150px">
											<label for="progressally-mce-editor-certificate-id-text">Certificate # to insert </label>
										</td>
										<td>
											<input class="full-width" type="text" id="progressally-mce-editor-certificate-id-text" />
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="progressally-setting-configure-block">
							(Optional) configured in this post
							<select type="text" id="progressally-mce-certificate-post-id" pa-dep-source="progressally-mce-certificate-post-id" class="progressally-autocomplete-add">
								<option value="0" selected="selected">Current Post</option>
								<?php echo $post_selection; ?>
							</select>
						</div>
						<div class="progressally-setting-configure-block">
							<strong>Insert the download link as</strong>
							<select type="text" id="progressally-mce-certificate-type-select" pa-dep-source="progressally-mce-certificate-type-select">
								<option value="button">Default button (configured in Style Settings)</option>
								<option value="url">Raw URL (so you can use it with your own styling/code)</option>
							</select>
						</div>
						<div class="progressally-setting-configure-block" hide-toggle pa-dep="progressally-mce-certificate-type-select" pa-dep-value="button">
							<table class="progressally-setting-configure-table">
								<tbody>
									<tr>
										<td style="width:20%">
											<label for="progressally-mce-certificate-text">Button text</label>
										</td>
										<td colspan="2">
											<input style="width:100%;" type="text" id="progressally-mce-certificate-text" value="Download Certificate" />
										</td>
									</tr>
									<tr>
										<td style="width:20%">
											<label for="progressally-mce-certificate-custom-class">Custom class</label>
										</td>
										<td style="width:30%;">
											<input class="full-width" type="text" id="progressally-mce-certificate-custom-class" />
										</td>
										<td><div class="progressally-inline-help-text">used to add your own styling (developers only)</div></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					<div progressally-insert-additional-input="complete-button" style="display:none;">
						<table class="progressally-setting-configure-table">
							<tbody>
								<tr>
									<td style="width:20%">
										<label for="progressally-mce-complete-button-text">Button text</label>
									</td>
									<td colspan="2">
										<input style="width:100%;" type="text" id="progressally-mce-complete-button-text" value="Mark As Done" />
									</td>
								</tr>
							</tbody>
						</table>
						<div class="progressally-setting-configure-block">
							<strong>Clicking this button will</strong>
							<select id="progressally-mce-complete-button-type" pa-dep-source="progressally-mce-complete-button-type">
								<option value="all">mark all objectives as complete</option>
								<option value="select">mark selected objectives as complete</option>
							</select>
							<div style="display:none" class="progressally-mce-objective-selection-container" hide-toggle pa-dep="progressally-mce-complete-button-type" pa-dep-value="select">
								<ul id="progressally-mce-complete-button-objective-selection"><?php echo $selection_code['complete-button']; ?></ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="progressally-insert-submit-row">
			<div class="progressally-insert-cancel-container">
				<a class="progressally-insert-cancel" id="progressally-insert-cancel" href="#">Cancel</a>
			</div>
			<div class="progressally-insert-submit-container">
				<input type="submit" value="Insert Shortcode" class="progressally-insert-button" id="progressally-insert-submit" name="progressally-insert-submit">
			</div>
		</div>
	</form>
</div>