<div class="progressally-certificate-customation-details" progressally-certificate-preview-details="{{certificate-id}}-{{element-id}}">
	<div>
		<div class="progressally-small-delete-button progressally-float-right" progressally-certificate-element-delete="{{certificate-id}}-{{element-id}}"
			 progressally-delete-warning="Deleting a customization cannot be undone. Continue?">[-] Delete Customization</div>
		<div class="progressally-setting-section-sub-header">Customization {{element-id}}</div>
		<div style="clear:both"></div>
	</div>
	<input type="hidden" progressally-certificate-preview-element-x="{{certificate-id}}-{{element-id}}" progressally-param="cert[{{certificate-id}}][custom][{{element-id}}][x]" value="{{x}}"
		   progressally-certificate-preview-mm="{{certificate-id}}-{{element-id}}" progressally-certificate-id="{{certificate-id}}" preview-attribute="left" />
	<input type="hidden" progressally-certificate-preview-element-y="{{certificate-id}}-{{element-id}}" progressally-param="cert[{{certificate-id}}][custom][{{element-id}}][y]" value="{{y}}"
		   progressally-certificate-preview-mm="{{certificate-id}}-{{element-id}}" progressally-certificate-id="{{certificate-id}}" preview-attribute="top" />
	<div class="progressally-setting-configure-block">
		<div class="progressally-certificate-parameter-block">
			What text to add?
			<select progressally-param="cert[{{certificate-id}}][custom][{{element-id}}][select-type]" pa-dep-source="progressally-certificate-customization-select-type-{{certificate-id}}-{{element-id}}"
					progressally-certificate-customize-type="{{certificate-id}}-{{element-id}}">
				{{select-type-options}}
				<option s--select-type--custom--d value="custom">Custom (for developers only)</option>
			</select>
		</div>
		<div class="progressally-certificate-parameter-block" 
			 hide-toggle="select-type" pa-dep="progressally-certificate-customization-select-type-{{certificate-id}}-{{element-id}}" pa-dep-value="{{template-additional-input-date}}">
			Show
			<select progressally-param="cert[{{certificate-id}}][custom][{{element-id}}][select-date-type]">
				<option value="current" s--select-date-type--current--d>Download time</option>
				<option value="complete" s--select-date-type--complete--d>Checklist completion time</option>
			</select>
		</div>
		<div class="progressally-certificate-parameter-block" hide-toggle="select-type" pa-dep="progressally-certificate-customization-select-type-{{certificate-id}}-{{element-id}}" pa-dep-value="custom">
			Custom text
			<input size="30" hide-toggle="select-type" pa-dep="progressally-certificate-customization-select-type-{{certificate-id}}-{{element-id}}" pa-dep-value="custom"
				   type="text" progressally-param="cert[{{certificate-id}}][custom][{{element-id}}][custom-value]" value="{{custom-value}}" />
		</div>
	</div>
	<div class="progressally-setting-configure-block">
		<div class="progressally-certificate-parameter-block">
			Width
			<input type="text" size="8" progressally-certificate-preview-element-width="{{certificate-id}}-{{element-id}}" progressally-param="cert[{{certificate-id}}][custom][{{element-id}}][w]" value="{{w}}"
				   progressally-certificate-preview-mm="{{certificate-id}}-{{element-id}}" progressally-certificate-id="{{certificate-id}}" preview-attribute="width" /> mm
		</div>
		<div class="progressally-certificate-parameter-block">
			Text Color
			<input class="nqpc-picker-input-iyxm" progressally-certificate-preview="#progressally-certificate-element-{{certificate-id}}-{{element-id}}" preview-attribute="color"
				   size="8" type="text" progressally-param="cert[{{certificate-id}}][custom][{{element-id}}][color]" value="{{color}}" />
		</div>
		<div class="progressally-certificate-parameter-block">
			Text Font
			<select progressally-param="cert[{{certificate-id}}][custom][{{element-id}}][select-font]" progressally-certificate-preview-font="#progressally-certificate-element-{{certificate-id}}-{{element-id}}">
				{{select-font-options}}
			</select>
		</div>
		<div class="progressally-certificate-parameter-block">
			Font Size
			<input type="text" size="6" progressally-param="cert[{{certificate-id}}][custom][{{element-id}}][font-size]" value="{{font-size}}"
				   progressally-certificate-preview-pt="{{certificate-id}}-{{element-id}}" progressally-certificate-id="{{certificate-id}}" preview-attribute="font-size" />
		</div>
		<div class="progressally-certificate-parameter-block">
			Text Alignment
			<select progressally-param="cert[{{certificate-id}}][custom][{{element-id}}][select-align]" progressally-certificate-preview="#progressally-certificate-element-{{certificate-id}}-{{element-id}}" preview-attribute="text-align">
				<option s--select-align--left--d value="left">Left</option>
				<option s--select-align--center--d value="center">Center</option>
				<option s--select-align--right--d value="right">Right</option>
			</select>
		</div>
	</div>
	<table class="progressally-post-setting-table">
		<tbody>
			<tr>
				<td style="width:200px">
					Enter sample text to test
				</td>
				<td>
					<input type="text" class="full-width" value="{{preview-value}}" progressally-certificate-preview-val="#progressally-certificate-element-{{certificate-id}}-{{element-id}}"
						   progressally-certificate-customize-preview="{{certificate-id}}-{{element-id}}" />
				</td>
			</tr>
		</tbody>
	</table>
</div>