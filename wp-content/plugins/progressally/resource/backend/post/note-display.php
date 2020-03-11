<div class="progressally-setting-configure-block">
	<div class="progressally-setting-section-header">Private Notes</div>
	<div class="progressally-setting-section-help-text">Allow users to write down notes, or questions / comments which the admin can respond to.</div>
</div>
<div class="progressally-setting-configure-block">
	<input type="hidden" progressally-param="max-notes"
		   id="progressally-max-note" value="<?php echo $max_note_num; ?>" />
	<?php echo $note_code; ?>
	<div class="progressally-button" id="progressally-add-note">[+] Add New Note</div>
</div>