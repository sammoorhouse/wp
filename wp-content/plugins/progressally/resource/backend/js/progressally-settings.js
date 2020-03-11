/* global progressally_settings_object, progressally_jscolor */

jQuery(document).ready(function($) {
	/* --------------------- data-dependency logic ------------------------- */
	function evaluate_dependency(collection, value, match_function, mismatch_function) {
		for (var index = 0; index < collection.length; ++index) {
			var $elem = $(collection[index]),
				dependency_value = $elem.attr('pa-dep-value'),
				dependency_value_not = $elem.attr('pa-dep-value-not'),
				value_parts;
			if (typeof dependency_value !== typeof undefined && dependency_value !== false) {
				value_parts = dependency_value.split(',');
				if ($.inArray(value, value_parts) >= 0) {
					match_function($elem);
				} else {
					mismatch_function($elem);
				}
			}
			if (typeof dependency_value_not !== typeof undefined && dependency_value_not !== false) {
				value_parts = dependency_value_not.split(',');
				if ($.inArray(value, value_parts) < 0) {
					match_function($elem);
				} else {
					mismatch_function($elem);
				}
			}
		}
	}
	$(document).on('change', '[pa-dep-source]', function() {
		var $element = $(this),
			value = 'no',
			dependency_name = $element.attr('pa-dep-source'),
			dependencies = $('[pa-dep="' + dependency_name + '"]');
		if($element.attr('type') === 'checkbox') {
			if ($element.is(':checked')){
				value = 'yes';
			}
		} else {
			value = $element.val();
		}

		if (value){
			value = value.replace(/\"/g, '\\"');
		}
		evaluate_dependency(dependencies.filter('[hide-toggle]'), value, function(elem) { elem.show(); }, function(elem) { elem.hide(); });
		evaluate_dependency(dependencies.filter('[readonly-toggle]'), value, function(elem) { elem.prop('readonly', false); }, function(elem) { elem.prop('readonly', true); });
		evaluate_dependency(dependencies.filter('[disable-toggle]'), value, function(elem) { elem.prop('disabled', false); }, function(elem) { elem.prop('disabled', true); });
	});
	/* --------------------- end data-dependency logic ------------------------- */
	var all_progressally_tab_group = $('[tab-group]'),
		progressally_settings_wait_overlay = $('#progressally-wait-overlay');

	$('.nqpc-picker-input-iyxm').each(function(index, elem) {
		progressally_jscolor.bind_element(elem);
	});
	
	all_progressally_tab_group.click(function(e) {
		var selector = $(this).attr('tab-group'),
			target = $(this).attr('target'),
			active = $(this).attr('active-class'),
			$tabs = $('[' + selector + ']');
		all_progressally_tab_group.filter('[tab-group=' + selector + ']').removeClass(active);
		$(this).addClass(active);
		$tabs.filter('[' + selector + '!=' + target + ']').hide();
		$tabs.filter('[' + selector + '=' + target + ']').show();
	});
	$("[click-target][click-value]").on('click', function(e) {
		var selector = $(this).attr('click-target');
		$(selector).val($(this).attr('click-value')).change();
		return false;
	});
	$('.progressally-setting-submit-button').on('click', function(e){
		progressally_settings_wait_overlay.show();
	});
	$('.styling-template-select').on('change', function(e){
		var template_name = $(this).val();
		if (template_name === 'Advance') {
			$('#progressally-styling-custom-setting').hide();
			$('#progressally-styling-advance-setting').show();
			
			$('[progressally-css-setting="Advance"]').each(function() {
				update_css_advance($(this));
			});
		} else {
			// Need fill template first
			var template_setting = null;
			if (template_name === 'Custom') {
				$('#progressally-styling-custom-setting').show();
				$('#progressally-styling-advance-setting').hide();
				
				template_setting = new Object;
				$('[progressally-css-setting="Custom"]').each(function() {
					var $this = $(this),
					token = $this.attr("progressally-template-token-name");
					template_setting[token] = $this.val();
				});
			} else {
				$('#progressally-styling-custom-setting').hide();
				$('#progressally-styling-advance-setting').hide();
				template_setting = $.parseJSON($(this).attr('template-value'));
			}
			
			$('[progressally-styling-template-group]').each(function() {
				update_css_template($(this), template_setting);
			});
		}
	});
	$('html').on('change', '[progressally-live-css-update]', function(e) {
		var $this = $(this),
		css_setting_type = $this.attr('progressally-css-setting');
		
		if (css_setting_type === 'Advanced') {
			update_css_advance($this);
		} else {
			// Custom type need to fill template first
			var css_group = $this.attr('progressally-css-setting-group'),
			template_element = $('[progressally-styling-template-group="' + css_group + '"]');
			
			var template_setting = new Object;
			$('[progressally-css-setting-group="' + css_group + '"]').each(function() {
				var $this = $(this),
				token = $this.attr("progressally-template-token-name");
				template_setting[token] = $this.val();
			});
			update_css_template(template_element, template_setting);
		}
	});
	$('html').on('change', '[progressally-live-target-update]', function(e) {
		var $this = $(this),
			target_css_selector = $this.attr('progressally-live-target-update');
		$(target_css_selector).html($this.val());
	});
	
	$('html').on('change', "[verify-px-pct-input]", function() {
		var $this = $(this),
			error = $this.attr('verify-px-pct-input'),
			code = $this.val(),
			error_text = '';
		if (code) {
			code = code.toLowerCase();
			if (code.indexOf(' ') >= 0) {
				error_text = 'This value must not container space.';
			} else if (code.indexOf('px') !== code.length - 2 && code.indexOf('%') !== code.length - 1) {
				error_text = 'This value must end with "px" or "%".';
			}
		} else {
			error_text = 'This value must not be empty.';
		}
		$(error).text(error_text);
	});

	function update_css_advance(element) {
		var target_css_selector = element.attr('progressally-live-css-update');
		$(target_css_selector).html(element.val());
	}
	function update_css_template(template_element, template_setting) {
		var template = template_element.val(),
		target_css_selector = template_element.attr('progressally-live-css-update'),
		index, key, value;

		for (index in progressally_settings_object.color) {
			key = progressally_settings_object.color[index];
			value = 'transparent';
			if (key in template_setting && template_setting[key].length > 0) {
				value = template_setting[key];
			}
			template = replace_all(template, '{{' + key + '}}', value);
		}
		for (index in progressally_settings_object.literal) {
			key = progressally_settings_object.literal[index];
			value = '';
			if (key in template_setting && template_setting[key].length > 0) {
				value = template_setting[key];
			}
			template = replace_all(template, '{{' + key + '}}', value);
		}
		$(target_css_selector).html(template);
		
	}
	function replace_all(str, find, replace) {
		return str.replace(new RegExp(find.replace(/([.*+?^=!:${}()|\[\]\/\\])/g, "\\$1"), 'g'), replace);
	}

	/* --------------------- stop preview form from submitting ------------------------- */
	$(document).on('submit', '.progressally-quiz', function(e) {
		e.stopPropagation();
		return false;
	});
	/* --------------------- END stop preview form from submitting ------------------------- */
	/* -------------- preview notes editing ------------------- */
	var num_currently_editing_notes = 0;
	$(document).on('click touchend', '[progressally-notes-update]', function(e) {
		var $this = $(this),
			attr = $this.attr('progressally-notes-update');
		initiate_note_edit(attr);
	});
	$(document).on('click touchend', '[progressally-notes-update-display]', function(e) {
		var $this = $(this),
			attr = $this.attr('progressally-notes-update-display');
		initiate_note_edit(attr);
	});
	// ESC key press
	$(document).on('keydown', '[progressally-notes-value]', function(e) {
		if(e.keyCode == 27){
			e.preventDefault();
			var $this = $(this),
				attr = $this.attr('progressally-notes-value');
			cancel_note_edit(attr);
			$this.blur();
			return false;
		}
	});
	$(document).on('click touchend', '[progressally-notes-input-save]', function(e) {
		var attr = $(this).attr('progressally-notes-input-save');
		commit_note_edit(attr);
	});
	$(document).on('click touchend', '[progressally-notes-input-cancel]', function(e) {
		var attr = $(this).attr('progressally-notes-input-cancel');
		cancel_note_edit(attr);
	});
	function initiate_note_edit(attr) {
		++num_currently_editing_notes;
		$('[progressally-notes-update-display-container="' + attr + '"]').hide();
		var $input_elem = $('[progressally-notes-value="' + attr + '"]'),
			num_lines = count_num_lines($input_elem.val());
		// the text box should have a minimum of 8 lines and a max of 20 lines
		num_lines = Math.min(20, Math.max(num_lines + 1, 8));
		$input_elem.prop('rows', num_lines);
		$('[progressally-notes-input-container="' + attr + '"]').show();
		$('[progressally-notes-value="' + attr + '"]').focus().select();
	}
	function cancel_note_edit(attr) {
		$('[progressally-notes-update-display-container="' + attr + '"]').show();
		$('[progressally-notes-input-container="' + attr + '"]').hide();
		$('[progressally-notes-value="' + attr + '"]').blur();
		--num_currently_editing_notes;
	}
	function commit_note_edit(attr) {
		var $input_elem = $('[progressally-notes-value="' + attr + '"]'),
			val = $input_elem.val(),
			$display_elem = $('[progressally-notes-update-display="' + attr + '"]');
		if (val.length > 0) {
			$display_elem.html(escape_text_to_html(val));
			$display_elem.attr('progressally-placeholder-status', 'hide');
		} else {
			$display_elem.text($display_elem.attr('progressally-placeholder'));
			$display_elem.attr('progressally-placeholder-status', 'show');
		}
		--num_currently_editing_notes;
		$('[progressally-notes-update-display-container="' + attr + '"]').show();
		$('[progressally-notes-input-container="' + attr + '"]').hide();
		$('[progressally-notes-wait="' + attr + '"]').hide();
		$('[progressally-notes-value="' + attr + '"]').blur();
		return false;
	}
	function count_num_lines(str) {
		var new_line_match = str.match(/(\r\n|\n\r|\r|\n)/g);
		// the text box should have a minimum of 8 lines and a max of 20 lines
		if (null === new_line_match) {
			return 1;
		}
		return new_line_match.length + 1;
	}
	function escape_text_to_html(str) {
		str = str.replace(/&/g, '&amp;');
		str = str.replace(/</g, '&lt;');
		str = str.replace(/>/g, '&gt;');
		str = str.replace(/\'/g, '&#39;');
		str = str.replace(/\"/g, '&quot;');
		str = str.replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1<br/>$2');
		return str;
	}
	/* -------------- END preview notes editing ------------------- */
});