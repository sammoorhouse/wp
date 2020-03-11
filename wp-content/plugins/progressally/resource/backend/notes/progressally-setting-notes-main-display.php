<div class="wrap">
<h2 style="display:none;"><?php _e('ProgressAlly Notes'); ?></h2>

<input type="hidden" id="selected-tab" value="note-reply" />
<table class="progressally-setting-container">
	<tbody>
		<tr>
			<td class="progressally-setting-left-col"/>
			<td class="progressally-setting-title-cell progressally-setting-right-col">
				<div style="display:inline-block;">
					<div class="progressally-setting-title">ProgressAlly Private Notes</div>

					<div class="progressally-setting-section-help-text"><div class="progressally-info-icon"></div>Need extra help? View our documentation and tutorials <a class="underline" href="<?php echo ProgressAlly::HELP_URL; ?>">here</a>!</div>
				</div>
			</td>
		</tr>
		<tr>
			<td class="progressally-setting-left-col progressally-setting-tab-label-col progressally-setting-tab-active" click-target="#selected-tab" click-value="note-reply" tab-group="progressally-tab-group-1" target="note-reply" active-class="progressally-setting-tab-active">
				<div style="background-image: url('<?php echo ProgressAlly::$PLUGIN_URI; ?>resource/backend/img/note-reply-icon.png');" class="progressally-tab-label">
					Reply
				</div>
			</td>
			<td rowspan="3" class="progressally-setting-content-cell progressally-setting-right-col">
				<div class="progressally-setting-content-container" progressally-tab-group-1="note-reply">
					<?php ProgressAllySettingNoteReply::show_note_reply_settings(); ?>
				</div>
				<div class="progressally-setting-content-container" style="display:none;" progressally-tab-group-1="admin-init">
					<?php ProgressAllySettingAdminInitNotes::show_admin_init_settings(); ?>
				</div>
			</td>
		</tr>
		<tr>
			<td class="progressally-setting-left-col progressally-setting-tab-label-col" click-target="#selected-tab" click-value="admin-init" tab-group="progressally-tab-group-1" target="admin-init" active-class="progressally-setting-tab-active">
				<div style="background-image: url('<?php echo ProgressAlly::$PLUGIN_URI; ?>resource/backend/img/admin-init-icon.png');" class="progressally-tab-label">
					Admin notes
				</div>
			</td>
		</tr>
		<tr class="progressally-setting-filler-row">
			<td class="progressally-setting-left-col"><br/></td>
		</tr>
	</tbody>
</table>
</div>