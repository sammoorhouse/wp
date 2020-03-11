<div class="progressally-setting-section">
	<div class="progressally-setting-section-header">Styling template settings</div>
	<div class="progressally-setting-configure-block">
		<?php foreach ($styling_template as $group_id => $template) { ?>
			<input type="hidden" progressally-styling-template-group="<?php echo $group_id; ?>" progressally-live-css-update="<?php echo '#progressally-preview-css-'. $group_id; ?>" value="<?php echo esc_attr($template); ?>" />
		<?php } ?>
		<div class="progressally-setting-selection">
			<?php foreach ($styling_template_settings as $template_id => $template_setting) { ?>
			<div class="progressally-setting-selection-item">
				<input type="radio" class="styling-template-select" name="<?php echo $setting_key_general . 'template]'; ?>" value="<?php echo $template_id; ?>" id="<?php echo 'styling-template-' . $template_id; ?>"
					<?php echo ($styling_general['template'] === $template_id) ? 'checked' : ''; ?>
					   template-value="<?php echo esc_attr(json_encode($styling_template_settings[$template_id])); ?>"/>
				<label for="<?php echo 'styling-template-' . $template_id; ?>"><?php echo $template_id; ?></label>
			</div>
			<?php } ?>
			<div class="progressally-setting-selection-item">
				<input type="radio" class="styling-template-select" name="<?php echo $setting_key_general . 'template]'; ?>" value="Custom" id="styling-template-custom"
					<?php echo ($styling_general['template'] === 'Custom') ? 'checked' : ''; ?> />
				<label for="styling-template-custom"><strong>Custom</strong></label>
			</div>
			<div class="progressally-setting-selection-item">
				<input type="radio" class="styling-template-select" name="<?php echo $setting_key_general . 'template]'; ?>" value="Advance" id="styling-template-advance"
					<?php echo ($styling_general['template'] === 'Advance') ? 'checked' : ''; ?>/>
				<label for="styling-template-advance"><strong>Advanced (for developer's use only)</strong></label>
			</div>
		</div>
	</div>
	<div id="progressally-styling-custom-setting" <?php echo ($styling_general['template'] === 'Custom') ? '' : 'style="display:none;"'; ?>>
		<div class="progressally-setting-section-sub-header">Customization</div>
		<div class="progressally-configure-element">
			<div class="progressally-setting-configure-block">
				<div class="progressally-setting-section-sub-header">Objective Table</div>
				<table class="progressally-setting-styling-table"><tbody>
					<tr>
						<td class="progressally-setting-styling-col">Objective number icon</td>
						<td class="progressally-setting-styling-content"><input class="full-width" progressally-live-css-update progressally-css-setting="Custom" progressally-template-token-name="objective-icon" progressally-css-setting-group="objective-table-css"
									type="text" name="<?php echo $setting_key_general . 'custom-template-settings][objective-icon]'; ?>" value="<?php echo ($styling_general['custom-template-settings']['objective-icon']); ?>"/></td>
					</tr>
					<tr>
						<td class="progressally-setting-styling-col">Time target icon</td>
						<td class="progressally-setting-styling-content"><input class="full-width" progressally-live-css-update progressally-css-setting="Custom" progressally-template-token-name="time-target-icon" progressally-css-setting-group="objective-table-css"
									type="text" name="<?php echo $setting_key_general . 'custom-template-settings][time-target-icon]'; ?>" value="<?php echo ($styling_general['custom-template-settings']['time-target-icon']); ?>"/></td>
					</tr>
					<tr>
						<td class="progressally-setting-styling-col">Completed checkbox icon</td>
						<td class="progressally-setting-styling-content"><input class="full-width" progressally-live-css-update progressally-css-setting="Custom" progressally-template-token-name="checkbox-checked" progressally-css-setting-group="objective-table-css"
								   type="text" name="<?php echo $setting_key_general . 'custom-template-settings][checkbox-checked]'; ?>" value="<?php echo ($styling_general['custom-template-settings']['checkbox-checked']); ?>"/></td>
					</tr>
					<tr>
						<td class="progressally-setting-styling-col">Uncompleted checkbox icon</td>
						<td class="progressally-setting-styling-content"><input class="full-width" progressally-live-css-update progressally-css-setting="Custom" progressally-template-token-name="checkbox-unchecked" progressally-css-setting-group="objective-table-css"
								   type="text" name="<?php echo $setting_key_general . 'custom-template-settings][checkbox-unchecked]'; ?>" value="<?php echo ($styling_general['custom-template-settings']['checkbox-unchecked']); ?>"/></td>
					</tr>
					<tr>
						<td class="progressally-setting-styling-col">Objective table border color</td>
						<td class="progressally-setting-styling-content"><input class="nqpc-picker-input-iyxm" progressally-live-css-update progressally-css-setting="Custom" progressally-template-token-name="border-color" progressally-css-setting-group="objective-table-css"
																				size="8" type="text" name="<?php echo $setting_key_general . 'custom-template-settings][border-color]'; ?>" value="<?php echo ($styling_general['custom-template-settings']['border-color']); ?>"/></td>
					</tr>
				</tbody></table>
			</div>
		</div>
		<div class="progressally-configure-element">
			<div class="progressally-setting-configure-block">
				<div class="progressally-setting-section-sub-header">Menu Items</div>
				<table class="progressally-setting-styling-table"><tbody>
					<tr>
						<td class="progressally-setting-styling-col">Menu completed icon</td>
						<td class="progressally-setting-styling-content"><input class="full-width" progressally-live-css-update progressally-css-setting="Custom" progressally-template-token-name="menu-completed-icon" progressally-css-setting-group="menu-completed-css"
																				type="text" name="<?php echo $setting_key_general . 'custom-template-settings][menu-completed-icon]'; ?>" value="<?php echo ($styling_general['custom-template-settings']['menu-completed-icon']); ?>"/></td>
					</tr>
					<tr>
						<td class="progressally-setting-styling-col">Menu completed icon horizontal offset</td>
						<td class="progressally-setting-styling-content"><input progressally-live-css-update progressally-css-setting="Custom" progressally-template-token-name="menu-completed-icon-left" progressally-css-setting-group="menu-completed-css" 
																				verify-px-pct-input="#px-pct-input-error-menu-completed-icon-left"
																				size="8" type="text" name="<?php echo $setting_key_general . 'custom-template-settings][menu-completed-icon-left]'; ?>" value="<?php echo ($styling_general['custom-template-settings']['menu-completed-icon-left']); ?>"/>
							 (Please enter 'px' or '%')<br/>
							<small class="progressally-setting-error" id="px-pct-input-error-menu-completed-icon-left"></small>
						</td>
					</tr>
				</tbody></table>
			</div>
		</div>
		<div class="progressally-configure-element">
			<div class="progressally-setting-configure-block">
				<div class="progressally-setting-section-sub-header">Progress items</div>
				<table class="progressally-setting-styling-table"><tbody>
					<tr>
						<td class="progressally-setting-styling-col">Progress pie chart color</td>
						<td class="progressally-setting-styling-content"><input class="nqpc-picker-input-iyxm" progressally-live-css-update progressally-css-setting="Custom" progressally-template-token-name="progress-pie-chart-color" progressally-css-setting-group="progress-pie-chart-css"
																				size="8" type="text" name="<?php echo $setting_key_general . 'custom-template-settings][progress-pie-chart-color]'; ?>" value="<?php echo ($styling_general['custom-template-settings']['progress-pie-chart-color']); ?>"/></td>
					</tr>
					<tr>
						<td class="progressally-setting-styling-col">Progress bar color</td>
						<td class="progressally-setting-styling-content"><input class="nqpc-picker-input-iyxm" progressally-live-css-update progressally-css-setting="Custom" progressally-template-token-name="progress-bar-color" progressally-css-setting-group="progress-bar-css"
																				size="8" type="text" name="<?php echo $setting_key_general . 'custom-template-settings][progress-bar-color]'; ?>" value="<?php echo ($styling_general['custom-template-settings']['progress-bar-color']); ?>"/></td>
					</tr>
					<tr>
						<td class="progressally-setting-styling-col">Progress bar percentage text horizontal offset</td>
						<td class="progressally-setting-styling-content"><input progressally-live-css-update progressally-css-setting="Custom" progressally-template-token-name="progress-bar-text-left" progressally-css-setting-group="progress-bar-css" 
																				verify-px-pct-input="#px-pct-input-error-progress-bar-text-left"
																				size="8" type="text" name="<?php echo $setting_key_general . 'custom-template-settings][progress-bar-text-left]'; ?>" value="<?php echo ($styling_general['custom-template-settings']['progress-bar-text-left']); ?>"/>
							 (Please enter 'px' or '%')<br/>
							<small class="progressally-setting-error" id="px-pct-input-error-progress-bar-text-left"></small>
						</td>
					</tr>
				</tbody></table>
			</div>
		</div>
		<div class="progressally-configure-element">
			<div class="progressally-setting-configure-block">
				<div class="progressally-setting-section-sub-header">Quiz</div>
				<table class="progressally-setting-styling-table"><tbody>
					<tr>
						<td class="progressally-setting-styling-col">Background color</td>
						<td class="progressally-setting-styling-content"><input class="nqpc-picker-input-iyxm" progressally-live-css-update progressally-css-setting="Custom" progressally-template-token-name="quiz-bgcolor" progressally-css-setting-group="quiz-css"
																				size="8" type="text" name="<?php echo $setting_key_general . 'custom-template-settings][quiz-bgcolor]'; ?>" value="<?php echo ($styling_general['custom-template-settings']['quiz-bgcolor']); ?>"/></td>
					</tr>
					<tr>
						<td class="progressally-setting-styling-col">Submit button color</td>
						<td class="progressally-setting-styling-content"><input class="nqpc-picker-input-iyxm" progressally-live-css-update progressally-css-setting="Custom" progressally-template-token-name="quiz-button-color" progressally-css-setting-group="quiz-css"
																				size="8" type="text" name="<?php echo $setting_key_general . 'custom-template-settings][quiz-button-color]'; ?>" value="<?php echo ($styling_general['custom-template-settings']['quiz-button-color']); ?>"/></td>
					</tr>
					<tr>
						<td class="progressally-setting-styling-col">Submit button color on cursor hover</td>
						<td class="progressally-setting-styling-content"><input class="nqpc-picker-input-iyxm" progressally-live-css-update progressally-css-setting="Custom" progressally-template-token-name="quiz-button-hover" progressally-css-setting-group="quiz-css"
																				size="8" type="text" name="<?php echo $setting_key_general . 'custom-template-settings][quiz-button-hover]'; ?>" value="<?php echo ($styling_general['custom-template-settings']['quiz-button-hover']); ?>"/></td>
					</tr>
					<tr>
						<td class="progressally-setting-styling-col">Correct message background color</td>
						<td class="progressally-setting-styling-content"><input class="nqpc-picker-input-iyxm" progressally-live-css-update progressally-css-setting="Custom" progressally-template-token-name="quiz-correct-message-bgcolor" progressally-css-setting-group="quiz-css"
																				size="8" type="text" name="<?php echo $setting_key_general . 'custom-template-settings][quiz-correct-message-bgcolor]'; ?>" value="<?php echo ($styling_general['custom-template-settings']['quiz-correct-message-bgcolor']); ?>"/></td>
					</tr>
					<tr>
						<td class="progressally-setting-styling-col">Incorrect message background color</td>
						<td class="progressally-setting-styling-content"><input class="nqpc-picker-input-iyxm" progressally-live-css-update progressally-css-setting="Custom" progressally-template-token-name="quiz-incorrect-message-bgcolor" progressally-css-setting-group="quiz-css"
																				size="8" type="text" name="<?php echo $setting_key_general . 'custom-template-settings][quiz-incorrect-message-bgcolor]'; ?>" value="<?php echo ($styling_general['custom-template-settings']['quiz-incorrect-message-bgcolor']); ?>"/></td>
					</tr>
					</tbody></table>
			</div>
		</div>
		<div class="progressally-configure-element">
			<div class="progressally-setting-configure-block">
				<div class="progressally-setting-section-sub-header">Notes</div>
				<table class="progressally-setting-styling-table"><tbody>
					<tr>
						<td class="progressally-setting-styling-col">Note edit icon</td>
						<td class="progressally-setting-styling-content">
							<input class="full-width" progressally-live-css-update progressally-css-setting="Custom" progressally-template-token-name="note-edit-icon" progressally-css-setting-group="notes-css"
								   type="text" name="<?php echo $setting_key_general . 'custom-template-settings][note-edit-icon]'; ?>" value="<?php echo ($styling_general['custom-template-settings']['note-edit-icon']); ?>"/></td>
					</tr>
					<tr>
						<td class="progressally-setting-styling-col">Save button color</td>
						<td class="progressally-setting-styling-content"><input class="nqpc-picker-input-iyxm" progressally-live-css-update progressally-css-setting="Custom" progressally-template-token-name="note-save-button-color" progressally-css-setting-group="notes-css"
																				size="8" type="text" name="<?php echo $setting_key_general . 'custom-template-settings][note-save-button-color]'; ?>" value="<?php echo ($styling_general['custom-template-settings']['note-save-button-color']); ?>"/></td>
					</tr>
					<tr>
						<td class="progressally-setting-styling-col">Save button color on cursor hover</td>
						<td class="progressally-setting-styling-content"><input class="nqpc-picker-input-iyxm" progressally-live-css-update progressally-css-setting="Custom" progressally-template-token-name="note-save-button-hover" progressally-css-setting-group="notes-css"
																				size="8" type="text" name="<?php echo $setting_key_general . 'custom-template-settings][note-save-button-hover]'; ?>" value="<?php echo ($styling_general['custom-template-settings']['note-save-button-hover']); ?>"/></td>
					</tr>
				</tbody></table>
			</div>
		</div>
	</div>
	<div id="progressally-styling-advance-setting"
		 class="progressally-setting-configure-block" <?php echo ($styling_general['template'] === 'Advance') ? '' : 'style="display:none;"'; ?>>
		<div class="progressally-setting-section-sub-header">Advanced CSS Editor</div>
		<?php foreach (ProgressAllyStylingTemplates::$STYLING_TEMPLATE_VARIABLES as $variable_name => $display_text) { ?>
		<div class="progressally-configure-element">		
			<div class="progressally-setting-configure-block">
				<div class="progressally-setting-section-sub-header"><?php echo esc_html($display_text); ?></div>
				<div><textarea progressally-css-setting="Advance" progressally-live-css-update="#progressally-preview-css-<?php echo esc_attr($variable_name); ?>-css" rows="6" class="full-width" name="<?php echo $setting_key_advance . $variable_name . '-css]'; ?>" ><?php echo esc_html($styling_advance[$variable_name . '-css']); ?></textarea></div>
			</div>
		</div>
		<?php } ?>
	</div>
	<div class="progressally-setting-section-header">Preview</div>
	<div class="progressally-setting-configure-block">
		<table class="progressally-setting-preview-table"><tbody>
			<tr>
				<td class="progressally-setting-preview-cell">
					<div class="progressally-setting-preview-text">Objective Table</div>
					<div class="progressally-setting-preview">
						<table class='objective-table'>
							<tbody>
								<?php foreach (ProgressAllySettingStyling::$default_styling_objectives as $id => $values) {
									echo ProgressAllyProgressDisplay::generate_objective_table_row(0, $id, $id, 0, $values, array(), true, '', true); 
								}?>
							</tbody>
						</table>
					</div>
				</td>
				<td class="progressally-setting-preview-cell">
					<div class="progressally-setting-preview-text">Menu Items (menu item with objectives)</div>
					<div class="progressally-setting-preview">
						<ul style="width:30%;">
							<li>
								<a href="#" class="progressally-menu-link" style="display:block;height:auto;line-height:40px;">Menu text 1<?php echo ProgressAlly::generate_menu_progress_indicator(1, ''); ?></a>
							</li>
							<li>
								<a href="#" class="progressally-menu-link" style="display:block;height:auto;line-height:40px;">Menu text 2<?php echo ProgressAlly::generate_menu_progress_indicator(1, ''); ?></a>
							</li>
						</ul>
					</div>
				</td>
			</tr>
			<tr>
				<td class="progressally-setting-preview-cell">
					<div class="progressally-setting-preview-text">Progress Pie Chart</div>
					<div class="progressally-setting-preview">
						<div style="float:left;margin-right:20px;">
							<?php echo ProgressAllyProgressDisplay::preview_progress_pie_chart(0.3); ?>
						</div>
						<div style="float:left;">
							<?php echo ProgressAllyProgressDisplay::preview_progress_pie_chart(0.8); ?>
						</div>
						<div style="height:1px;clear:both;"></div>
					</div>
				</td>
				<td class="progressally-setting-preview-cell">
					<div class="progressally-setting-preview-text">Progress Bar</div>
					<div class="progressally-setting-preview">
						<div style="margin-bottom:20px;">
							<?php echo ProgressAllyProgressDisplay::preview_progress_bar(0.3); ?>
						</div>
						<div>
							<?php echo ProgressAllyProgressDisplay::preview_progress_bar(0.8); ?>
						</div>
					</div>
				</td>
			</tr>
			<tr>
				<td colspan="2" class="progressally-setting-preview-cell">
					<div class="progressally-setting-preview-text">Quiz</div>
					<div class="progressally-setting-preview">
						<div style="margin-bottom:20px;">
							<?php echo ProgressAllyQuizDisplay::preview_quiz(); ?>
						</div>
					</div>
				</td>
			</tr>
			<tr>
				<td colspan="2" class="progressally-setting-preview-cell">
					<div class="progressally-setting-preview-text">Notes</div>
					<div class="progressally-setting-preview">
						<div style="margin-bottom:20px;">
							<?php echo ProgressAllyNotesShortcode::preview_notes(); ?>
						</div>
					</div>
				</td>
			</tr>
			<tr>
				<td colspan="2" class="progressally-setting-preview-cell">
					<div class="progressally-setting-preview-text">Certificate Download Button</div>
					<div class="progressally-setting-preview">
						<div style="margin-bottom:20px;">
							<?php echo ProgressAllyCertificatesShortcode::preview_certificate_button(); ?>
						</div>
					</div>
				</td>
			</tr>
		</tbody></table>
	</div>
	<div class="progressally-setting-configure-block">
		<div class="progressally-setting-section-header">Custom CSS</div>
		<div class="progressally-setting-section-help-text">Ideal for overwriting existing ProgressAlly styling without switching to the <strong>Advanced</strong> option.</div>
		<div class="progressally-setting-configure-block">
			<textarea progressally-live-target-update="#progressally-preview-css-custom-css" rows="6" class="full-width" name="<?php echo $setting_key_general . 'custom-css]';?>" ><?php echo esc_textarea($styling_general['custom-css']); ?></textarea>
		</div>
	</div>
</div>