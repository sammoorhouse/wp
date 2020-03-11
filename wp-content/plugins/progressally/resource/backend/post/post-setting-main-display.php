<?php wp_nonce_field( 'progressally_task_definition_custom_box', 'progressally_task_definition_custom_box_nonce' ); ?>
<div id="progressally-wait-overlay">
	<div class="progressally-wait-content">
		<img src="<?php echo ProgressAlly::$PLUGIN_URI; ?>resource/backend/img/wait.gif" alt="wait" width="128" height="128" />
	</div>
</div>
<div id="progressally-upload-wait-overlay">
	<div class="progressally-wait-content">
		<img src="<?php echo ProgressAlly::$PLUGIN_URI; ?>resource/backend/img/wait.gif" alt="wait" width="128" height="128" />
		<div class="progressally-progress-overlay" id="progressally-upload-progress">0%</div>
		<div class="progressally-cancel-button" id="progressally-upload-stop">Stop Upload</div>
	</div>
</div>

<div id="progressally-post-settings-main-container">
<div id="progressally-post-settings-loading-wait">Loading ProgressAlly Settings...</div>
<div class="progressally-setting-configure-block">
	<div class="progressally-setting-section-help-text">
		<div class="progressally-info-icon"></div>
		Need extra help? View our documentation and tutorials <a class="underline" target="_blank" href="<?php echo ProgressAlly::HELP_URL; ?>">here</a>!
	</div>
</div>

<input type="hidden" name="<?php echo ProgressAllyTaskDefinition::META_KEY_TASK_DEFINITION; ?>" id="progressally-serialize-main-meta" value="" />
<input type="hidden" name="<?php echo ProgressAllyNote::META_KEY; ?>" id="progressally-serialize-note-meta" value="" />
<input type="hidden" name="<?php echo ProgressAllyCertificate::META_KEY; ?>" id="progressally-serialize-certificate-meta" value="" />

<table class="progressally-setting-container">
	<tbody>
		<tr>
			<td class="progressally-setting-left-col progressally-setting-tab-label-col <?php echo $meta['selected-tab']==='objective'?'progressally-setting-tab-active':''; ?>"
				progressally-click-target="#progressally-selected-tab" progressally-click-value="objective" progressally-tab-group="progressally-tab-group-1" target="objective" active-class="progressally-setting-tab-active">
				<div style="background-image: url('<?php echo ProgressAlly::$PLUGIN_URI; ?>resource/backend/img/objective-icon.png');" class="progressally-tab-label">
					Objectives
				</div>
			</td>
			<td rowspan="8" class="progressally-setting-content-cell progressally-setting-right-col">
				<div progressally-meta-serialize="progressally-serialize-main-meta">
				<input type="hidden" progressally-param="selected-tab" id="progressally-selected-tab" value="<?php echo $meta['selected-tab']; ?>" />
				<div class="progressally-setting-content-container" style="display:<?php echo $meta['selected-tab']==='objective'?'block':'none'; ?>;" progressally-tab-group-1="objective" progressally-import-group="objective">
					<?php echo ProgressAllyTaskDefinition::show_objective_meta_box($meta, $note_meta); ?>
				</div>
				<div class="progressally-setting-content-container" style="display:<?php echo $meta['selected-tab']==='social'?'block':'none'; ?>;" progressally-tab-group-1="social" progressally-import-group="social">
					<?php echo ProgressAllySocialSharing::show_social_sharing_meta_box($meta); ?>
				</div>
				<div class="progressally-setting-content-container" style="display:<?php echo $meta['selected-tab']==='tagging'?'block':'none'; ?>;" progressally-tab-group-1="tagging" progressally-import-group="tagging">
					<?php echo ProgressAllyTaskDefinition::show_tagging_meta_box($meta); ?>
				</div>
				<div class="progressally-setting-content-container" style="display:<?php echo $meta['selected-tab']==='quiz'?'block':'none'; ?>;" progressally-tab-group-1="quiz" progressally-import-group="quiz">
					<?php echo ProgressAllyQuiz::show_quiz_meta_box($meta, $post_id); ?>
				</div>
				</div>
				<div progressally-meta-serialize="progressally-serialize-note-meta" class="progressally-setting-content-container" style="display:<?php echo $meta['selected-tab']==='note'?'block':'none'; ?>;" progressally-tab-group-1="note" progressally-import-group="note">
					<?php echo ProgressAllyNote::show_note_meta_box($post_id, $meta); ?>
				</div>
				<div progressally-meta-serialize="progressally-serialize-certificate-meta" class="progressally-setting-content-container" style="display:<?php echo $meta['selected-tab']==='certificate'?'block':'none'; ?>;" progressally-tab-group-1="certificate" progressally-import-group="certificate">
					<?php echo ProgressAllyCertificate::show_certificate_meta_box($post_id); ?>
				</div>
				<div class="progressally-setting-content-container" style="display:<?php echo $meta['selected-tab']==='export'?'block':'none'; ?>;" progressally-tab-group-1="export">
					<?php ProgressAllyTaskDefinition::show_import_export_meta_box($post_id); ?>
				</div>
			</td>
		</tr>
		<tr>
			<td class="progressally-setting-left-col progressally-setting-tab-label-col <?php echo $meta['selected-tab']==='social'?'progressally-setting-tab-active':''; ?>"
				progressally-click-target="#progressally-selected-tab" progressally-click-value="social" progressally-tab-group="progressally-tab-group-1" target="social" active-class="progressally-setting-tab-active">
				<div style="background-image: url('<?php echo ProgressAlly::$PLUGIN_URI; ?>resource/backend/img/social-icon.png');" class="progressally-tab-label">
					Social Sharing
				</div>
			</td>
		</tr>
		<tr>
			<td class="progressally-setting-left-col progressally-setting-tab-label-col <?php echo $meta['selected-tab']==='tagging'?'progressally-setting-tab-active':''; ?>"
				progressally-click-target="#progressally-selected-tab" progressally-click-value="tagging" progressally-tab-group="progressally-tab-group-1" target="tagging" active-class="progressally-setting-tab-active">
				<div style="background-image: url('<?php echo ProgressAlly::$PLUGIN_URI; ?>resource/backend/img/tagging-icon.png');" class="progressally-tab-label">
					Tagging
				</div>
			</td>
		</tr>
		<tr>
			<td class="progressally-setting-left-col progressally-setting-tab-label-col <?php echo $meta['selected-tab']==='quiz'?'progressally-setting-tab-active':''; ?>"
				progressally-click-target="#progressally-selected-tab" progressally-click-value="quiz" progressally-tab-group="progressally-tab-group-1" target="quiz" active-class="progressally-setting-tab-active">
				<div style="background-image: url('<?php echo ProgressAlly::$PLUGIN_URI; ?>resource/backend/img/quiz-icon.png');" class="progressally-tab-label">
					Quiz
				</div>
			</td>
		</tr>
		<tr>
			<td class="progressally-setting-left-col progressally-setting-tab-label-col <?php echo $meta['selected-tab']==='note'?'progressally-setting-tab-active':''; ?>"
				progressally-click-target="#progressally-selected-tab" progressally-click-value="note" progressally-tab-group="progressally-tab-group-1" target="note" active-class="progressally-setting-tab-active">
				<div style="background-image: url('<?php echo ProgressAlly::$PLUGIN_URI; ?>resource/backend/img/note-menu-icon.png');" class="progressally-tab-label">
					Private Notes
				</div>
			</td>
		</tr>
		<tr>
			<td class="progressally-setting-left-col progressally-setting-tab-label-col <?php echo $meta['selected-tab']==='certificate'?'progressally-setting-tab-active':''; ?>"
				progressally-click-target="#progressally-selected-tab" progressally-click-value="certificate" progressally-tab-group="progressally-tab-group-1" target="certificate" active-class="progressally-setting-tab-active">
				<div style="background-image: url('<?php echo ProgressAlly::$PLUGIN_URI; ?>resource/backend/img/certificate-menu-icon.png');" class="progressally-tab-label">
					Certificates
				</div>
			</td>
		</tr>
		<tr>
			<td class="progressally-setting-left-col progressally-setting-tab-label-col <?php echo $meta['selected-tab']==='export'?'progressally-setting-tab-active':''; ?>"
				progressally-click-target="#progressally-selected-tab" progressally-click-value="export" progressally-tab-group="progressally-tab-group-1" target="export" active-class="progressally-setting-tab-active">
				<div style="background-image: url('<?php echo ProgressAlly::$PLUGIN_URI; ?>resource/backend/img/export-icon.png');" class="progressally-tab-label">
					Import/Export
				</div>
			</td>
		</tr>
		<tr class="progressally-setting-filler-row">
			<td class="progressally-setting-left-col"><br/></td>
		</tr>
	</tbody>
</table>
</div>