<div class="progressally-setting-accordion-block {{open-class}}" id="progressally-certificate-block-{{certificate-id}}">
	<div class="progressally-setting-accordion-header" progressally-toggle-target="#progressally-certificate-toggle-{{certificate-id}}" id="progressally-setting-certificate-header-{{certificate-id}}">
		<div class="progressally-view-toggle-block">
			<input progressally-param="cert[{{certificate-id}}][checked-is-open]" {{checked-is-open}} type="checkbox" value="yes"
				   toggle-class="progressally-accordion-opened" progressally-toggle-element="#progressally-certificate-block-{{certificate-id}}" min-height="40"
				   min-height-element="#progressally-setting-certificate-header-{{certificate-id}}"
				   pa-dep-source="progressally-certificate-toggle-{{certificate-id}}" id="progressally-certificate-toggle-{{certificate-id}}">
			<label hide-toggle="checked-is-open" pa-dep="progressally-certificate-toggle-{{certificate-id}}" pa-dep-value="no">&#x25BC;</label>
			<label hide-toggle="checked-is-open" pa-dep="progressally-certificate-toggle-{{certificate-id}}" pa-dep-value="yes">&#x25B2;</label>
		</div>
		<div class="progressally-name-display-block">
			<div class="progressally-name-display" progressally-click-edit-show="certificate-name-{{certificate-id}}">
				<table class="progressally-header-table">
					<tbody>
						<tr>
							<td class="progressally-certificate-number-col">{{certificate-id}}. </td>
							<td class="progressally-name-label-col"><div class="progressally-name-label" progressally-click-edit-display="certificate-name-{{certificate-id}}">{{name}}</div></td>
							<td class="progressally-name-edit-col"><div class="progressally-pencil-icon" progressally-click-edit-trigger="certificate-name-{{certificate-id}}"></div></td>
						</tr>
					</tbody>
				</table>
			</div>
			<input progressally-param="cert[{{certificate-id}}][name]" class="progressally-name-edit progressally-certificate-name full-width" progressally-certificate-name-input="{{certificate-id}}" progressally-click-edit-input="certificate-name-{{certificate-id}}"
				   style="display:none;" value="{{name}}" type="text" />
		</div>
		<div style="clear:both;"></div>
	</div>
	<div class="progressally-setting-accordion-setting-section" hide-toggle="checked-is-open" pa-dep="progressally-certificate-toggle-{{certificate-id}}" pa-dep-value="yes">
		<div class="progressally-setting-configure-block" progressally-certificate-upload-block="{{certificate-id}}" {{has-existing-hide}}>
			<div class="progressally-setting-section-sub-header">
				Upload a PDF template
			</div>
			<div>
				<input type="file" progressally-certificate-upload="{{certificate-id}}" accept=".pdf" />
			</div>
			<a href="#" progressally-certificate-switch-customization="{{certificate-id}}" {{has-existing-show}}>Customize current template</a>
		</div>
		<div progressally-certificate-customization-block="{{certificate-id}}" {{has-existing-show}}>
			<a href="#" progressally-certificate-switch-upload="{{certificate-id}}">Upload a new template</a>
			<div class="progressally-setting-certficiate-header-block">
				<input type="hidden" progressally-certificate-file-path="{{certificate-id}}" progressally-param="cert[{{certificate-id}}][file-path]"
					   value="{{file-path}}"/>
				<input type="hidden" progressally-certificate-width="{{certificate-id}}" progressally-param="cert[{{certificate-id}}][width]"
					   value="{{width}}"/>
				<input type="hidden" progressally-certificate-height="{{certificate-id}}" progressally-param="cert[{{certificate-id}}][height]"
					   value="{{height}}"/>
				<table class="progressally-setting-configure-table" style="margin:0 0 15px 0">
					<tbody>
						<tr>
							<td style="width:180px"><div class="progressally-setting-section-sub-header">Certificate File Name</div></td>
							<td>
								<input type="text" class="full-width" progressally-certificate-file-name="{{certificate-id}}" progressally-param="cert[{{certificate-id}}][file-name]"
									   value="{{file-name}}"/>
							</td>
							<td style="width:30px">.pdf</td>
						</tr>
					</tbody>
				</table>
				<input type="hidden" progressally-param="cert[{{certificate-id}}][max-elem]" progressally-certificate-element-max="{{certificate-id}}" value="{{max-elem}}" />
				<a class="progressally-button progressally-float-right" target="_blank" href="#" progressally-certificate-download="{{certificate-id}}">Download Test Certificate</a>
				<div class="progressally-button" progressally-certificate-add-element="{{certificate-id}}">[+] Add New Customization</div>
				<div style="clear:both"></div>
			</div>
			<div class="progressally-setting-configure-block" id="progressally-certificate-preview-block">
				<div class="progressally-setting-configure-block">
					<div class="progressally-setting-section-sub-header">
						Preview
					</div>
					<div class="progressally-setting-section-help-text">The preview is designed to assist you with positioning the text. Please see the resulting PDF by clicking on the download link.</div>
				</div>
				<div progressally-certificate-customization="{{certificate-id}}">
					{{element-customizations}}
				</div>
				<div class="progressally-setting-configure-block">
					<span style="line-height:30px;">Please press the</span>
					<img src="{{plugin-uri}}/resource/backend/img/cert-zoom-button.png" width="30" height="30" style="vertical-align:bottom;" />
					<span style="line-height:30px;">button to resize the PDF document in the preview window before adding customizations.</span>
				</div>
				<div class="progressally-setting-configure-block">
					<div class="progressally-certificate-preview-container" progressally-certificate-preview-container="{{certificate-id}}" style="width:{{preview-width}}px;height:{{preview-height}}px;">
						<div class="progressally-certificate-pdf-container" progressally-certificate-pdf-container="{{certificate-id}}">
							{{pdf-preview}}
						</div>
						<div class="progressally-certificate-pdf-customization" progressally-certificate-pdf-customization="{{certificate-id}}">
							{{element-previews}}
						</div>
					</div>
				</div>
			</div>
		</div>
		<div>
			<div class="progressally-delete-button progressally-certificate-delete progressally-float-right" progressally-delete-element="#progressally-certificate-block-{{certificate-id}}"
				 progressally-delete-warning="Deleting a certificate cannot be undone. Continue?">[-] Delete Certificate</div>
			<div style="clear:both"></div>
		</div>
	</div>
</div>