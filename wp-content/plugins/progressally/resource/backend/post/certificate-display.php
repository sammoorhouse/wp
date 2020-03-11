<div class="progressally-setting-configure-block">
	<div class="progressally-setting-section-header">Certificates</div>
	<div class="progressally-setting-section-help-text">Create personalized certificates for your members.</div>
</div>
<input type="hidden" id="progressally-certificate-post-id" value="<?php echo $post_id; ?>" />
<div class="progressally-setting-configure-block">
	<input type="hidden" progressally-param="max-cert" id="progressally-max-cert" value="<?php echo $max_cert_num; ?>" />
	<?php echo $certificate_code; ?>
	<div class="progressally-button" id="progressally-add-cert">[+] Add New Certificate</div>
</div>