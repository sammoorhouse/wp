/* global progressally_post_default_code, progressally_post, progressally_jscolor, wp */

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
		} else if($element.attr('type') === 'radio') {
			if ($element.is(':checked')){
				value = $element.val();
		} else {
				return;
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
	$('[pa-dep-source]').change();
	/* --------------------- end data-dependency logic ------------------------- */
	$(document).on('touchend click', "[progressally-add-element]", function(e) {
		e.preventDefault();
		var $this = $(this),
			target = $($this.attr('progressally-add-element')),
			count_elem = $(target.attr('max-id')),
			max_id = parseInt(count_elem.val()) + 1,
			id_tag = target.attr('id-tag'),
			before = target.attr('before'),
			after = target.attr('after'),
			new_html = before + target.html() + after,
			new_elem = null;
		new_html = new_html.replace(new RegExp(id_tag, 'g'), max_id);
		count_elem.val(max_id);
		new_elem = $(new_html);
		target.before(new_elem);
		return false;
	});
	/* --------------------- delete elements ------------------------- */
	$(document).on('touchend click', '[progressally-delete-element]', function(e){
		e.preventDefault();
		var $this = $(this),
			warning = $this.attr('progressally-delete-warning'),
			target = $($this.attr('progressally-delete-element'));
		if (warning){
			var conf = confirm(warning);
			if(conf !== true){
				return false;
			}
		}
		target.remove();

		if ($this.hasClass('progressally-certificate-delete')) {
			safe_dispatch_event('progressally_certificate_updated');
		} else if ($this.hasClass('progressally-note-delete')) {
			safe_dispatch_event('progressally_note_updated');
		} else if ($this.hasClass('progressally-share-delete')) {
			safe_dispatch_event('progressally_share_updated');
		} else if ($this.hasClass('progressally-objective-delete')) {
			refresh_shortcode_adder_objective_list();
		}
		return false;
	});
	/* --------------------- END delete elements ------------------------- */
	/* --------------------- add question ------------------------- */
	$(document).on('touchend click', "#progressally-quiz-add-question", function(e) {
		e.preventDefault();
		var max_id = $('#progressally-quiz-max-question'),
			new_id = parseInt(max_id.val()) + 1,
			new_html = progressally_post_default_code['question'];
		max_id.val(new_id);
		new_html = new_html.replace(new RegExp('--qid--', 'g'), new_id);
		new_html = new_html.replace(new RegExp('--outcome-options--', 'g'), generate_outcome_options());
		$('#progressally-quiz-question-container').append(new_html);
		update_dynamic_quiz_html_for_question('#progressally-question-block-' + new_id); // refresh preview html code

		$('#progressally-quiz-choice-display-select').change();
		return false;
	});
	/* --------------------- END add question ------------------------- */
	/* --------------------- add choice ------------------------- */
	$(document).on('touchend click', ".progressally-add-choice", function(e) {
		e.preventDefault();
		var $this = $(this),
			question_id = $this.attr('question-id'),
			choice_id = $this.attr('choice-id'),
			max_id = $('#progressally-max-choice-id-' + question_id),
			target = $('#progressally-quiz-choice-' + question_id + '-' + choice_id),
			preview_target_vertical = $('#progressally-quiz-choice-preview-' + question_id + '-' + choice_id),
			preview_target_horizontal = $('#progressally-quiz-choice-preview-horizontal-' + question_id + '-' + choice_id),
			new_id = parseInt(max_id.val()) + 1,
			choice_label = progressally_post_default_code['choice-label'],
			new_html = get_choice_html(question_id, new_id, choice_label, 1);
		max_id.val(new_id);
		target.after(new_html);

		new_html = get_choice_preview_html(question_id, new_id, choice_label, 'vertical');
		preview_target_vertical.after(new_html);

		new_html = get_choice_preview_html(question_id, new_id, choice_label, 'horizontal');
		preview_target_horizontal.after(new_html);

		update_dynamic_quiz_html_for_question('#progressally-question-block-' + question_id); // refresh preview html code
		return false;
	});
	function get_choice_html(question_id, choice_id, choice_label, segment_score) {
		var new_html = progressally_post_default_code['choice'];
		new_html = new_html.replace(new RegExp('--qid--', 'g'), question_id);
		new_html = new_html.replace(new RegExp('--cid--', 'g'), choice_id);
		new_html = new_html.replace(new RegExp('--clabel--', 'g'), choice_label);
		new_html = new_html.replace(new RegExp('--outcome-options--', 'g'), generate_outcome_options());
		new_html = new_html.replace(new RegExp('--segment-score--', 'g'), segment_score);
		return new_html;
	}
	function get_choice_preview_html(question_id, choice_id, choice_label, preview_type) {
		var new_html = progressally_post_default_code['choice-preview-' + preview_type];

		new_html = new_html.replace(/--qid--/g, question_id);
		new_html = new_html.replace(/--cid--/g, choice_id);
		new_html = new_html.replace(/--clabel--/g, choice_label);
		return new_html;
	}
	/* --------------------- END add choice ------------------------- */
	/* --------------------- add choice set------------------------- */
	$(document).on('touchend click', '[progressally-replace-choice-1-10]', function(e) {
		e.preventDefault();
		var $this = $(this),
			question_id = $this.attr('progressally-replace-choice-1-10'),
			warning = $this.attr('progressally-warning'),
			targets = $('[class*="progressally-quiz-choice-dependent-' + question_id + '-"]');
		if (warning){
			var conf = confirm(warning);
			if(conf !== true){
				return false;
			}
		}
		targets.remove();
		
		var preview_vertical = $('#progressally-quiz-preview-vertical-' + question_id),
			preview_horizontal = $('#progressally-quiz-preview-horizontal-' + question_id),
			choice_container = $('#progressally-quiz-choice-listing-container-' + question_id),
			$max_id_elem = $('#progressally-max-choice-id-' + question_id),
			max_id = parseInt($max_id_elem.val()),
			new_id, new_html;
		for (var i = 1; i <= 10; ++i) {
			new_id = max_id + i;
			new_html = get_choice_html(question_id, new_id, i, i);
			choice_container.append(new_html);

			new_html = get_choice_preview_html(question_id, new_id, i, 'vertical');
			preview_vertical.append(new_html);

			new_html = get_choice_preview_html(question_id, new_id, i, 'horizontal');
			preview_horizontal.append(new_html);
		}
		$max_id_elem.val(max_id + 10);
		update_dynamic_quiz_html_for_question('#progressally-question-block-' + question_id); // refresh preview html code
		return false;
	});
	/* --------------------- END add choice set------------------------- */
	
	// <editor-fold defaultstate="collapsed" desc="on vertical / horizontal quiz choice display selection change, resize the header because the preview would have changed">
	$(document).on('change', '#progressally-quiz-choice-display-select', function() {
		$('.progressally-quiz-question-toggle').change();
	});
	/* --------------------- delete elements ------------------------- */
	$(document).on('touchend click', '.progressally-quiz-choice-delete', function(e){
		e.preventDefault();
		var $this = $(this),
			question_id = $this.attr('question-id'),
			choice_id = $this.attr('choice-id'),
			warning = $this.attr('progressally-delete-warning'),
			target = $('.progressally-quiz-choice-dependent-' + question_id + '-' + choice_id);
		if (warning){
			var conf = confirm(warning);
			if(conf !== true){
				return false;
			}
		}
		target.remove();
		return false;
	});
	/* --------------------- END delete elements ------------------------- */
	/* --------------------- add/delete/change grade outcome ------------------------- */
	function sort_outcome_score($all_outcome_scores, quiz_type) {
		var raw_scores = {}, sorted_outcome_ids = [],
			$elem, outcome_id, min_score = 0, max_score;
		for (i = 0; i < $all_outcome_scores.length; ++i) {
			$elem = $($all_outcome_scores[i]);
			outcome_id = parseInt($elem.attr('outcome-id'));
			sorted_outcome_ids.push(outcome_id);
			raw_scores[outcome_id] = parseInt($elem.val());
		}
		sorted_outcome_ids = sorted_outcome_ids.sort(function(a,b) { return a-b; });
		/* sanitize all the scores to make sure they are valid integers */
		for (i = 0; i < sorted_outcome_ids.length; ++i) {
			outcome_id = sorted_outcome_ids[i];
			if (isNaN(raw_scores[outcome_id])) {
				raw_scores[outcome_id] = min_score;
			} else if (raw_scores[outcome_id] < min_score) {
				raw_scores[outcome_id] = min_score;
			}
			min_score = raw_scores[outcome_id];
		}
		/* make sure the values are in weakly increasing order */
		for (i = 0; i < sorted_outcome_ids.length; ++i) {
			outcome_id = sorted_outcome_ids[i];
			if (i < sorted_outcome_ids.length - 1) {
				max_score = raw_scores[sorted_outcome_ids[i+1]];
				if (raw_scores[outcome_id] > max_score) {
					raw_scores[outcome_id] = max_score;
				}
			} else if (quiz_type === 'score') {
				if (raw_scores[outcome_id] > 100) {
					raw_scores[outcome_id] = 100;
				}
			}
		}
		return [sorted_outcome_ids, raw_scores];
	}
	function refresh_grade_quiz_outcome_score_display() {
		var $all_outcome_scores = $('.progressally-quiz-outcome-score'),
			sorted_result = sort_outcome_score($all_outcome_scores),
			sorted_outcome_ids = sorted_result[0],
			raw_scores = sorted_result[1],
			i, outcome_id, max_score;

		/* refresh titles */
		for (i = 0; i < sorted_outcome_ids.length; ++i) {
			outcome_id = sorted_outcome_ids[i];
			if (i < sorted_outcome_ids.length - 1) {
				max_score = raw_scores[sorted_outcome_ids[i+1]];
			} else {
			}
			if (i < sorted_outcome_ids.length - 1) {
				$('#progressally-grade-outcome-' + outcome_id + '-title').text('Score range: ' + raw_scores[outcome_id] + '% - ' + raw_scores[sorted_outcome_ids[i+1]] + '%');
			} else {
				$('#progressally-grade-outcome-' + outcome_id + '-title').text('Score range: ' + raw_scores[outcome_id] + '% - 100%');
			}
			$all_outcome_scores.filter('[outcome-id="' + outcome_id + '"]').val(raw_scores[outcome_id]);
		}
		/* if not threshold radio box is selected, select the lowest one */
		if ($('.progressally-grade-outcome-threshold:checked').length <= 0) {
			$('.progressally-grade-outcome-threshold[value="1"]').prop('checked', true);
		}
	}
	
	$(document).on('change', '.progressally-quiz-outcome-score', function() {
		refresh_grade_quiz_outcome_score_display();
	});
	$(document).on('touchend click', '#progressally-quiz-add-grade-outcome-button', function(e){
		e.preventDefault();

		var $num_outcome = $('#progressally-quiz-num-grade-outcome'),
			num = parseInt($num_outcome.val()),
			outcome_code = progressally_post_default_code['grade-outcome'];
		outcome_code = outcome_code.replace(/--outcome-id--/g, num + 1);
		if ('0' === progressally_post.quiz_popup_selection_code) {
			outcome_code = outcome_code.replace(/--popup-selection--/g, '');
			outcome_code = outcome_code.replace(/--has-valid-popup-selection--/g, 'style="display:none"');
		} else {
			outcome_code = outcome_code.replace(/--popup-selection--/g, progressally_post.quiz_popup_selection_code);
			outcome_code = outcome_code.replace(/--has-valid-popup-selection--/g, '');
		}
		$num_outcome.val(num + 1);
		$('#progressally-quiz-grade-outcome-container').prepend(outcome_code);

		refresh_grade_quiz_outcome_score_display();

		return false;
	});
	$(document).on('touchend click', '.progressally-quiz-grade-outcome-delete-button', function(e){
		e.preventDefault();
		var $this = $(this),
			outcome_id = $this.attr('outcome-id'),
			warning = $this.attr('progressally-delete-warning'),
			target = $('#progressally-grade-outcome-' + outcome_id);
		if (outcome_id <= 1) {
			alert('The lowest level outcome cannot be deleted.');
			return false;
		}
		if (warning){
			var conf = confirm(warning);
			if(conf !== true){
				return false;
			}
		}
		
		target.remove();

		refresh_grade_quiz_outcome_score_display();

		return false;
	});
	refresh_grade_quiz_outcome_score_display(); // populate the score header on load
	/* --------------------- END add/delete/change grade outcome ------------------------- */
	/* --------------------- update linked text ------------------------- */
	$(document).on('change', "[progressally-quiz-update-source]", function(e) {
		var $this = $(this),
			iden = $this.attr('progressally-quiz-update-source'),
			target = $('[progressally-quiz-update-target="' + iden + '"]');
		target.html($this.val());
	});
	function update_all_dynamic_quiz_html() {
		$('#progressally-quiz-type').change();
		$('.progressally-outcome-name-edit[progressally-click-edit-input]').each(function(index, elem) { commit_name_edit($(elem)); });
		$("[progressally-quiz-update-source]").change();
	}
	function update_dynamic_quiz_html_for_question(selector) {
		$('#progressally-quiz-type').change();

		var $question_container = $(selector);
		$('.progressally-outcome-name-edit[progressally-click-edit-input]').each(function(index, elem) {
			var $elem = $(elem),
				group_id = $elem.attr('progressally-click-edit-input'),
				val = $elem.val();
			$question_container.find("[progressally-outcome-choice=" + group_id + "]").text(group_id + '. ' + val);
		});
		$question_container.find("[progressally-quiz-update-source]").change();
	}
	update_all_dynamic_quiz_html(); // trigger a change at document load, so malformed HTML will not damage the entire document
	/* --------------------- END update linked text ------------------------- */
	/* --------------------- header toggle logic ------------------------- */
	$('html').on('touchend click', ".progressally-quiz-choice-item input, .progressally-quiz-choice-item label", function(e) {	// do not trigger header toggle for radio button selection
		e.stopPropagation();
	});
	$('html').on('touchend click', "[progressally-toggle-target]", function(e) {
		var selector = $(this).attr('progressally-toggle-target');
		$(selector).prop('checked', !$(selector).prop('checked')).change();
	});
	$('html').on('change propertychange keyup input paste', "[progressally-toggle-element]", function(e) {
		var $this = $(this),
			selector = $this.attr('progressally-toggle-element'),
			target = $(selector),
			toggle_class = $this.attr('toggle-class'),
			is_checked = $this.prop('checked'),
			orig_height = target.outerHeight(true);
		if (is_checked) {
			target.animate({height:orig_height + 'px'}, 200, function() {$(selector).css('overflow', 'visible').css('height', 'auto');}).addClass(toggle_class);
		} else {
			hide_toggle_element($this, false);
		}
	});
	function hide_toggle_element($this, immediate) {
		var selector = $this.attr('progressally-toggle-element'),
			target = $(selector),
			toggle_class = $this.attr('toggle-class'),
			min_height = $this.attr('min-height'),
			min_height_element = $this.attr('min-height-element');
		if (typeof min_height_element !== typeof undefined && min_height_element !== false) {
			min_height_element = $(min_height_element).outerHeight(true);
			min_height = Math.max(min_height, min_height_element);
		}
		if (immediate) {
			target.css({ overflow : 'hidden', height : min_height + 'px' }).removeClass(toggle_class);
		} else {
			target.animate({height:min_height + 'px'}, 200, function() {$(selector).css('overflow', 'hidden');}).removeClass(toggle_class);
		}
	}
	/* --------------------- end header logic ------------------------- */
	/* --------------------- combobox auto-complete ------------------------- */
	function highlight_text(target, start, length) {
		var result = $('<span>');
		if (start > 0) {
			result.append($('<span></span>').text(target.substring(0, start)));
		}
		result.append($('<span class="progressally-option-highlight"></span>').text(target.substring(start, start + length)));
		if (start + length < target.length) {
			result.append($('<span></span>').text(target.substring(start + length)));
		}
		return result;
	}
	function render_option(ul, item) {
		if (item) {
			var result = $("<li class='progressally-option' tab-index='-1'></li>")
				.append(item.label)
				.appendTo(ul);
			return result;
		}
	}
	$.widget("custom.progressally_combobox", {
		_create: function () {
			var is_full_width = this.element.hasClass('full-width');

			if (is_full_width) {
				this.wrapper = $("<div class='progressally-option-combobox progressally-option-combobox-full-width'></div>");
			} else {
				this.wrapper = $("<span class='progressally-option-combobox'></span>");
			}
			this.wrapper.insertAfter(this.element);

			this.element.hide();
			this._createAutocomplete();
			if ($(this.element).hasClass('progressally-tag-input')) {
				this._createRefreshTrigger();
			}
		},
		_createRefreshTrigger: function () {
			$("<div class='progressally-refresh-tag-trigger-container' progressally-tooltip='Can&#39;t find a tag? Click here to refresh the tag list!'><div class='progressally-refresh-tag-trigger'></div></div>").appendTo(this.wrapper);
		},
		_createAutocomplete: function () {
			var selected = this.element.children(":selected"),
				value = selected.val() ? selected.text() : "";

			this.variable_name = this.element.attr('variable-name');
			if (typeof this.variable_name === typeof undefined || this.variable_name === false) {
				this.variable_name = false;
			}
			this.input = $("<input>")
					.appendTo(this.wrapper)
					.val(value)
					.attr("title", "")
					.addClass("progressally-option-input")
					.autocomplete({
						delay: 0,
						minLength: 0,
						source: $.proxy(this, "_source")
					})
					.keypress(function(e) {
						if(e.keyCode == 13){
							return false;
						}
					});
			this.input.data("ui-autocomplete")._renderItem = render_option;

			this._on(this.input, {
				autocompleteselect: function (event, ui) {
					if (this.variable_name) {
						if (ui.item.option.label) {
							var name_elem = $('<div class="progressally-tag-name"></div>').text(ui.item.option.label),
								val_elem = $('<input progressally-param="' + this.variable_name + '[]" type="hidden" />').val(ui.item.option.value);
							$('<div class="progressally-tag"><div class="progressally-tag-delete">&#x2715;</div></div>').append(name_elem).append(val_elem).insertBefore(this.element);
						}
						this.input.val('');
						return false;
					}
					this.element.val(ui.item.option.value).change();
					this.input.val(ui.item.option.label);
					return false;
				},
				autocompletechange: "_removeIfInvalid",
				focus: function() { this.input.select(); this.input.keydown(); }
			});
		},
		_source: function (request, response) {
			var search_pattern = request.term.toLowerCase(),
				search_length = search_pattern.length,
				entries = [],
				$source_options = this.element.children("option"),
				i, $elem, text;
			for (i = 0; i < $source_options.length; ++i) {
				$elem = $($source_options[i]);
				text = $elem.text();
				if (text) {
					if (!request.term) {
						entries.push({
							label: $("<span>").text(text),
							value: text,
							option: $source_options[i]
						});
					} else {
						var index = text.toLowerCase().indexOf(search_pattern);
						if (index >= 0) {
							entries.push({
								label: highlight_text(text, index, search_length),
								value: text,
								option: $source_options[i]
							});
						}
					}
				}
			}
			response(entries);
		},
		_removeIfInvalid: function (event, ui) {
			// Selected an item, nothing to do
			if (ui.item) {
				return;
			}

			// Search for a match (case-insensitive)
			var value = this.input.val(),
					valueLowerCase = value.toLowerCase(),
					valid = false;
			this.element.children("option").each(function () {
				if ($(this).text().toLowerCase() === valueLowerCase) {
					this.selected = valid = true;
					return false;
				}
			});

			// Found a match, nothing to do
			if (valid) {
				return;
			}

			// Remove invalid value
			this.input.val("");
			this.element.val("");
			this.input.autocomplete("instance").term = "";
		},
		_destroy: function () {
			this.wrapper.remove();
			this.element.show();
		}
	});
	$(document).on('touchend click', ".progressally-tag-delete", function(e) {
		var $parent = $(this).parent('div.progressally-tag');
		$parent.remove();
	});
	function generate_auto_complete_combobox() {
		$('.progressally-autocomplete-add').each(function(index, elem) {
			$(elem).removeClass('progressally-autocomplete-add').progressally_combobox();
		});
	}
	generate_auto_complete_combobox();
	/* --------------------- END combobox auto-complete ------------------------- */
	/* --------------------- num outcome ------------------------- */
	function generate_outcome_options() {
		var num = parseInt($('#progressally-quiz-num-outcome').val()),
			i, option_code, outcome_option;
		for (i = 1; i <= num; ++i) {
			outcome_option = progressally_post_default_code['outcome-option'];
			outcome_option = outcome_option.replace(/--outcome-id--/g, i);
			option_code += outcome_option;
		}
		return option_code;
	}
	$(document).on('touchend click', '#progressally-quiz-increase-outcome', function(e){
		e.preventDefault();
		var $num_outcome = $('#progressally-quiz-num-outcome'),
			num = parseInt($num_outcome.val()),
			outcome_code = progressally_post_default_code['outcome'];
		if ('0' === progressally_post.quiz_tag_selection_code) {
			outcome_code = outcome_code.replace(/--tag-selection--/g, '');
			outcome_code = outcome_code.replace(/--has-valid-tag-selection--/g, 'style="display:none"');
		} else {
			outcome_code = outcome_code.replace(/--tag-selection--/g, progressally_post.quiz_tag_selection_code);
			outcome_code = outcome_code.replace(/--has-valid-tag-selection--/g, '');
		}
		if ('0' === progressally_post.quiz_popup_selection_code) {
			outcome_code = outcome_code.replace(/--popup-selection--/g, '');
			outcome_code = outcome_code.replace(/--has-valid-popup-selection--/g, 'style="display:none"');
		} else {
			outcome_code = outcome_code.replace(/--popup-selection--/g, progressally_post.quiz_popup_selection_code);
			outcome_code = outcome_code.replace(/--has-valid-popup-selection--/g, '');
		}
		if ('0' === progressally_post.quiz_field_selection_code) {
			outcome_code = outcome_code.replace(/--field-selection--/g, '');
			outcome_code = outcome_code.replace(/--has-valid-field-selection--/g, 'style="display:none"');
		} else {
			outcome_code = outcome_code.replace(/--field-selection--/g, progressally_post.quiz_field_selection_code);
			outcome_code = outcome_code.replace(/--has-valid-field-selection--/g, '');
		}
		outcome_code = outcome_code.replace(/--outcome-id--/g, num + 1);
		$num_outcome.val(num + 1);
		$('#progressally-quiz-outcome-container').append(outcome_code);
		generate_auto_complete_combobox();
		
		outcome_code = progressally_post_default_code['outcome-option'];
		outcome_code = outcome_code.replace(/--outcome-id--/g, num + 1);
		$('.progressally-outcome-selection').append(outcome_code);
		$('#progressally-quiz-decrease-outcome').attr('disabled', false);
		return false;
	});
	$(document).on('touchend click', '#progressally-quiz-decrease-outcome', function(e){
		e.preventDefault();
		var $this = $(this),
			$num_outcome = $('#progressally-quiz-num-outcome'),
			num = parseInt($num_outcome.val()),
			warning = $this.attr('progressally-delete-warning');
		if (num < 2) {
			return false;
		}
		if (warning){
			var conf = confirm(warning);
			if(conf !== true){
				return false;
			}
		}
		if (num > 1) {
			$('#progressally-quiz-outcome-' + num).remove();
			$('option[progressally-outcome-choice="' + num + '"]').remove();
			$num_outcome.val(num - 1);
		}
		if (num <= 2) {
			$(this).attr('disabled', 'disabled');
		}
		return false;
	});
	/* --------------------- END num outcomes ------------------------- */
	/* --------------------- header toggle and edit logic ------------------------- */
	$('html').on('touchend click', ".progressally-outcome-header", function(e) {
		var selector = $(this).attr('toggle-target');
		$(selector).prop('checked', !$(selector).prop('checked')).change();
	});
	$('html').on('touchend click', "[progressally-click-target][progressally-click-value]", function(e) {
		var $this = $(this),
			selector = $this.attr('progressally-click-target'),
			value = $this.attr('progressally-click-value');
		$(selector).val(value).change();
		return false;
	});
	$('html').on('touchend click', "[progressally-click-edit-trigger]", function(e) {
		var group_id = $(this).attr('progressally-click-edit-trigger');
		$('[progressally-click-edit-show="' + group_id + '"]').hide();
		$('[progressally-click-edit-input="' + group_id + '"]').show().focus().select();
		return false;
	});
	function commit_name_edit($input_elem) {
		var group_id = $input_elem.attr('progressally-click-edit-input'),
			val = $input_elem.val();
		$('[progressally-click-edit-display="' + group_id + '"]').text(val);
		if ($input_elem.hasClass('progressally-outcome-name-edit')) {
			$("[progressally-outcome-choice=" + group_id + "]").text(group_id + '. ' + val);
		} else if ($input_elem.hasClass('progressally-certificate-name')) {
			safe_dispatch_event('progressally_certificate_updated');
		} else if ($input_elem.hasClass('progressally-note-name')) {
			safe_dispatch_event('progressally_note_updated');
		} else if ($input_elem.hasClass('progressally-share-name')) {
			safe_dispatch_event('progressally_share_updated');
		}
		$('[progressally-click-edit-show="' + group_id + '"]').show();
		$('[progressally-click-edit-input="' + group_id + '"]').hide();

	}
	function cancel_name_edit($input_elem) {
		var group_id = $input_elem.attr('progressally-click-edit-input');
		$input_elem.val($('[progressally-click-edit-display="' + group_id + '"]').text());	// restore the old text to the input element
		$('[progressally-click-edit-show="' + group_id + '"]').show();
		$('[progressally-click-edit-input="' + group_id + '"]').hide();
	}
	// stop click in the input box from toggling the accordion
	$('html').on('click', "[progressally-click-edit-input]", function(e){
		e.stopPropagation();
		return false;
	});
	$('html').on('focusout', "[progressally-click-edit-input]", function(e){
		commit_name_edit($(this));
		return false;
	});
	// process Enter key
	$(document).on('keypress', '[progressally-click-edit-input]', function(e) {
		if(e.keyCode == 13){
			e.stopPropagation();
			commit_name_edit($(this));
			return false;
		}
	});
	// process Esc key
	$(document).on('keydown', '[progressally-click-edit-input]', function(e) {
		if(e.keyCode == 27){
			e.stopPropagation();
			cancel_name_edit($(this));
			return false;
		}
	});
	/* --------------------- end header toggle and edit logic ------------------------- */
	/* --------------------- add note ------------------------- */
	$(document).on('touchend click', "#progressally-add-note", function(e) {
		e.preventDefault();
		var max_id = $('#progressally-max-note'),
			new_id = parseInt(max_id.val()) + 1,
			new_html = progressally_post_default_code['note'];
		max_id.val(new_id);
		new_html = new_html.replace(new RegExp('--note-id--', 'g'), new_id);
		new_html = new_html.replace(new RegExp('--blog-title--', 'g'), progressally_post.blog_title);
		$(this).before(new_html);

		safe_dispatch_event('progressally_note_updated');
		return false;
	});
	/* --------------------- END add note ------------------------- */
	/* --------------------- tab control ------------------------- */
	var all_progressally_tab_group = $('[progressally-tab-group]');

	all_progressally_tab_group.click(function(e) {
		var $this= $(this),
			selector = $this.attr('progressally-tab-group'),
			target = $this.attr('target'),
			active = $this.attr('active-class'),
			$tabs = $('[' + selector + ']');
		all_progressally_tab_group.filter('[progressally-tab-group=' + selector + ']').removeClass(active);
		$this.addClass(active);
		$tabs.filter('[' + selector + '!=' + target + ']').hide();
		$tabs.filter('[' + selector + '=' + target + ']').show();
	});
	/* --------------------- END tab control ------------------------- */
	/* --------------------- add objective ------------------------- */
	$(document).on('touchend click', "#progressally-add-objective", function(e) {
		e.preventDefault();
		var max_id = $('#progressally-objective-count'),
			new_id = parseInt(max_id.val()) + 1,
			new_html = progressally_post_default_code['objective'];
		max_id.val(new_id);
		new_html = new_html.replace(/--id--/g, new_id);
		new_html = new_html.replace(/--select-page-options--/g, progressally_post.page_selection_code);
		new_html = new_html.replace(/--select-note-options--/g, generate_private_note_selection());
		new_html = new_html.replace(new RegExp('s--selected-.*?--d', 'g'), '');
		$('#progressally-objective-list-content').append(new_html);
		generate_auto_complete_combobox();
		refresh_shortcode_adder_objective_list();
		return false;
	});
	/* --------------------- END add objective ------------------------- */
	/* --------------------- note type switching ------------------------- */
	$(document).on('change', "[progressally-note-type]", function(e) {
		var $this = $(this),
			note_id = $this.attr('progressally-note-type'),
			selected_type = $this.val();
		if (selected_type in progressally_post.note_mapping) {
			$('#progressally-note-admin-initiated-' + note_id).prop('checked', 'yes' === progressally_post.note_mapping[selected_type]['checked-admin-initiated']).change();
			$('#progressally-note-notify-admin-' + note_id).prop('checked', 'yes' === progressally_post.note_mapping[selected_type]['checked-notify-admin']).change();
			$('#progressally-note-notify-user-' + note_id).prop('checked', 'yes' === progressally_post.note_mapping[selected_type]['checked-notify-user']).change();
			$('#progressally-note-approve-' + note_id).prop('checked', 'yes' === progressally_post.note_mapping[selected_type]['checked-approve']).change();
			$('#progressally-note-num-reply-' + note_id).val(progressally_post.note_mapping[selected_type]['num-reply']).change();
		}
	});
	/* --------------------- END note type switching ------------------------- */
	// <editor-fold defaultstate="collapsed" desc="utility function for escaping text to HTML">
	function esc_html(str) {
		return $('<div>').text(str).html();
	}
	// </editor-fold>
	// <editor-fold defaultstate="collapsed" desc="update note selection in the shortcode adder and objective list">
	var $shortcode_adder_note_selection = $('#progressally-mce-editor-note-id-select');
	function generate_private_note_selection() {
		var $all_note_names = $('[progressally-note-name-input]'),
			$elem, note_id,
			i = 0, code = '';
		for (; i < $all_note_names.length; ++i) {
			$elem = $($all_note_names[i]);
			note_id = $elem.attr('progressally-note-name-input');
			code += '<option value="' + note_id + '">' + esc_html(note_id + '. ' + $elem.val()) + '</option>';
		}
		return code;
	}
	function update_private_note_selection() {
		var $update_targets = $('[progressally-objective-note-select]'),
			$target,
			selected,
			selection_code = generate_private_note_selection(),
			i = 0;

		$shortcode_adder_note_selection.html(selection_code);	// we don't need to keep the current selected value in the shortcode adder

		// include the empty option for objective note selection
		selection_code = '<option value="0"></option>' + selection_code;
		for (; i < $update_targets.length; ++i) {
			$target = $($update_targets[i]);
			selected = $target.val();
			$target.html(selection_code).val(selected);
		}
	}
	if (document.addEventListener) {
		document.addEventListener('progressally_note_updated', update_private_note_selection, false);
	} else if (document.attachEvent) {
		document.attachEvent('progressally_note_updated', update_private_note_selection);
	}
	// </editor-fold>
	// <editor-fold defaultstate="collapsed" desc="decide whether a private note is referenced in the objective list">
	function refresh_private_note_in_use_status() {
		var $private_note_delete_buttons = $('[progressally-private-note-delete]'),
			$objective_types = $('[progressally-objective-seek-type]'),
			$note_objectives = $('[progressally-objective-note-select]'),
			$objective,
			objective_note_map = {},
			objective_id,
			usage = {},
			$delete_button,
			$usage_message,
			note_id,
			i = 0;
		for (i = 0; i < $note_objectives.length; ++i) {
			$objective = $($note_objectives[i]);
			note_id = $objective.val();
			if (note_id > 0) {
				objective_id = $objective.attr('progressally-objective-note-select');
				objective_note_map[objective_id] = note_id;
			}
		}
		for (i = 0; i < $objective_types.length; ++i) {
			$objective = $($objective_types[i]);
			if ('note' === $objective.val()) {
				objective_id = $objective.attr('progressally-objective-seek-type');
				if (objective_id in objective_note_map) {
					note_id = objective_note_map[objective_id];
					if (!(note_id in usage)) {
						usage[note_id] = [];
					}
					usage[note_id].push(objective_id);
				}
			}
		}
		for (i = 0; i < $private_note_delete_buttons.length; ++i) {
			$delete_button = $($private_note_delete_buttons[i]);
			note_id = $delete_button.attr('progressally-private-note-delete');
			$usage_message = $('[progressally-private-note-in-use="' + note_id + '"]');
			if (note_id in usage) {
				$delete_button.hide();
				$usage_message.html('Referenced by Objective ' + usage[note_id].join(', ')).show();
			} else {
				$delete_button.show();
				$usage_message.html('').hide();
			}
		}
	}
	$(document).on('change', '[progressally-objective-note-select], [progressally-objective-seek-type]', refresh_private_note_in_use_status);
	refresh_private_note_in_use_status();	// refresh note delete / usage display on load
	// </editor-fold>
	/* --------------------- tag list refresh ------------------------- */
	var progressally_wait_overlay = $('#progressally-wait-overlay');
	function refresh_tag_list_autocomplete(response) {
		try {
			if (!('status' in response)) {
				alert('Refresh tag list failed due to unknown error');
				return;
			}
			if (response['status'] != 'success') {
				alert(response['message']);
				return;
			}
			if ('tags' in response) {
				$('.progressally-tag-input').each(function(index, elem) {
					var $elem = $(elem),
						selected = $elem.val();
					$elem.html(response['tags']).val(selected);
				});
				progressally_post.quiz_tag_selection_code = response['tags'];
			}
		} catch (e) {
			return;
		}
	}
	$(document).on('touchend click', '.progressally-refresh-tag-trigger', function(e) {
		progressally_wait_overlay.show();
		var data = {
				action: 'progressally_refresh_tag',
				nonce: progressally_post.nonce
			};

		$.ajax({
			type: "POST",
			url: progressally_post.ajax_url,
			data: data,
			success: function(response) {
				var result = JSON.parse(response);
				refresh_tag_list_autocomplete(result);
				progressally_wait_overlay.hide();
			}
		});
	});
	/* --------------------- END tag list refresh ------------------------- */
	/* --------------------- tooltip ------------------------- */
	$(document).on('mouseenter', '[progressally-tooltip]', function(e) {
		var $this = $(this);
		$('<div class="progressally-tooltip-display"></div>').text($this.attr('progressally-tooltip')).fadeTo(500, 1).appendTo($this);
	});
	$(document).on('mouseleave', '[progressally-tooltip]', function(e) {
		var $this = $(this),
			$display = $this.find('.progressally-tooltip-display');
		$display.fadeTo(500, 0, function(){ $display.remove(); });
	});
	/* --------------------- END tooltip ------------------------- */
	/* -------------------- import and export -------------------- */
	$(document).on('change', "#progressally-import-file", function(e) {
		if (e.target.files[0]) {
			$('#progressally-import-selection').show();
			$('#progressally-import-button').show();
		} else {
			$('#progressally-import-selection').hide();
			$('#progressally-import-button').hide();
		}
	});
	$(document).on('touchend click', '#progressally-import-button', function(e) {
		var selection = [],
			target = $('#progressally-import-selection');
		target.find('[progressally-import-selection]:checked').each(function(index, elem) {
			var attribute = $(elem).attr('progressally-import-selection');
			selection.push(attribute);
		});
		if (selection.length === 0 ) {
			alert("No section is checked. Nothing to import/overwrite.");
			return false;
		}
		
		var file = document.getElementById('progressally-import-file').files[0],
		file_name = document.getElementById('progressally-import-file').files[0].name,
		post_id = $(this).attr('post-id');
		
		if (file) {
			var conf = confirm("Import operation will overwrite the current settings for selected section(s)\nThis operation cannot be undone. Do you want to continue?");
			if(conf !== true){
				return false;
			}
			progressally_wait_overlay.show();
			var reader = new FileReader();
			reader.onload = function(e) {
				try{
					var all_lines = e.target.result,
					data = {
						action: 'progressally_generate_import_code',
						nonce: progressally_post.nonce,
						setting: encodeURI(all_lines),
						selection: encodeURI(selection.join(',')),
						pid: post_id
					};

					$.ajax({
						type: "POST",
						url: progressally_post.ajax_url,
						data: data,
						success: function(response) {
							process_import_result(response, file_name);
						}
					});
				}catch(e){
					alert("Import failed due to error:\n[" + e + "]\nPlease send the error message to AccessAlly support along with the .progressally file");
					progressally_wait_overlay.hide();
				}
			};
			reader.readAsText(file);
		}
		e.stopPropagation();
		return false;
	});
	function process_import_result(response, file_name) {
		try {
			var result = JSON.parse(response);
			if (!('status' in result)) {
				throw 'Import failed due to unknown error';
			}
			if (!result['status']) {
				throw result['error'];
			}
			for (var group in result['codes']) {
				var codes = result['codes'][group],
					target = $('[progressally-import-group="' + group + '"]');
				target.empty();
				target.append(codes);
			}
			generate_auto_complete_combobox();
			$('[progressally-quiz-update-source]').change();

			refresh_grade_quiz_outcome_score_display();
			refresh_segment_quiz_outcome_score_display();

			bind_color_pickers();

			initialize_objective_drag_and_drop();
			initialize_quiz_question_drag_and_drop();

			alert("Import successful:\n[" + file_name + "]");
		} catch (e) {
			alert("Cannot import settings due to error:\n[" + e + "]\nPlease refresh the page and try again.");
		}finally{
			progressally_wait_overlay.hide();
		}
	}
	/* -------------------- END import and export -------------------- */
	/* --------------------- add/delete/change segment outcome ------------------------- */
	function refresh_segment_quiz_outcome_score_display() {
		var $all_outcome_scores = $('.progressally-quiz-outcome-segment'),
			sorted_result = sort_outcome_score($all_outcome_scores),
			sorted_outcome_ids = sorted_result[0],
			raw_scores = sorted_result[1],
			i, outcome_id, max_score;

		/* refresh titles */
		for (i = 0; i < sorted_outcome_ids.length; ++i) {
			outcome_id = sorted_outcome_ids[i];
			if (i < sorted_outcome_ids.length - 1) {
				max_score = raw_scores[sorted_outcome_ids[i+1]];
			} else {
			}
			if (i < sorted_outcome_ids.length - 1) {
				if (raw_scores[outcome_id] >= raw_scores[sorted_outcome_ids[i+1]]) {
					$('#progressally-segment-outcome-' + outcome_id + '-title').text('Score range: Never');
				} else {
					$('#progressally-segment-outcome-' + outcome_id + '-title').text('Score range: ' + raw_scores[outcome_id] + ' - ' + (raw_scores[sorted_outcome_ids[i+1]] - 1));
				}
			} else {
				$('#progressally-segment-outcome-' + outcome_id + '-title').text('Score range: ' + raw_scores[outcome_id] + '+');
			}
			$all_outcome_scores.filter('[outcome-id="' + outcome_id + '"]').val(raw_scores[outcome_id]);
		}
	}
	$(document).on('change', '.progressally-quiz-outcome-segment', function() {
		refresh_segment_quiz_outcome_score_display();
	});
	$(document).on('touchend click', '#progressally-quiz-add-segment-outcome-button', function(e){
		e.preventDefault();

		var $num_outcome = $('#progressally-quiz-num-segment-outcome'),
			num = parseInt($num_outcome.val()),
			outcome_code = progressally_post_default_code['segment-outcome'];
		if ('0' === progressally_post.quiz_tag_selection_code) {
			outcome_code = outcome_code.replace(/--tag-selection--/g, '');
			outcome_code = outcome_code.replace(/--has-valid-tag-selection--/g, 'style="display:none"');
		} else {
			outcome_code = outcome_code.replace(/--tag-selection--/g, progressally_post.quiz_tag_selection_code);
			outcome_code = outcome_code.replace(/--has-valid-tag-selection--/g, '');
		}
		if ('0' === progressally_post.quiz_popup_selection_code) {
			outcome_code = outcome_code.replace(/--popup-selection--/g, '');
			outcome_code = outcome_code.replace(/--has-valid-popup-selection--/g, 'style="display:none"');
		} else {
			outcome_code = outcome_code.replace(/--popup-selection--/g, progressally_post.quiz_popup_selection_code);
			outcome_code = outcome_code.replace(/--has-valid-popup-selection--/g, '');
		}
		outcome_code = outcome_code.replace(/--outcome-id--/g, num + 1);
		$num_outcome.val(num + 1);
		$('#progressally-quiz-segment-outcome-container').prepend(outcome_code);
		generate_auto_complete_combobox();

		refresh_segment_quiz_outcome_score_display();

		return false;
	});
	$(document).on('touchend click', '.progressally-quiz-segment-outcome-delete-button', function(e){
		e.preventDefault();
		var $this = $(this),
			outcome_id = $this.attr('outcome-id'),
			warning = $this.attr('progressally-delete-warning'),
			target = $('#progressally-segment-outcome-' + outcome_id);
		if (outcome_id <= 1) {
			alert('The lowest level outcome cannot be deleted.');
			return false;
		}
		if (warning){
			var conf = confirm(warning);
			if(conf !== true){
				return false;
			}
		}

		target.remove();

		refresh_segment_quiz_outcome_score_display();

		return false;
	});
	refresh_segment_quiz_outcome_score_display(); // populate the segment score header on load
	/* --------------------- END add/delete/change grade outcome ------------------------- */
	/* --------------------- PDF certificate ------------------------- */
	var mm_scale_factor = {};
	function get_mm_scaling_factor(cert_id) {
		if (!(cert_id in mm_scale_factor)) {
			update_preview_dimension(cert_id);
		}
		return mm_scale_factor[cert_id];
	}
	function update_preview_dimension(cert_id) {
		var pdf_width = $('[progressally-certificate-width="' + cert_id + '"]').val(),
			pdf_height = $('[progressally-certificate-height="' + cert_id + '"]').val();
		if (parseFloat(pdf_width) > 0) {
			mm_scale_factor[cert_id] = 600.0 / parseFloat(pdf_width);
			var height = parseFloat(pdf_height) * mm_scale_factor[cert_id];
			$('[progressally-certificate-preview-container="' + cert_id + '"]').css('width', '600px').css('height', height + 'px').show();
		} else {
			mm_scale_factor[cert_id] = 0;
			$('[progressally-certificate-preview-container="' + cert_id + '"]').hide();
		}
	}
	function show_preview_pdf(result, cert_id) {
		$('[progressally-certificate-switch-customization="' + cert_id + '"]').show();

		$('[progressally-certificate-file-name="' + cert_id + '"]').val(result['file-name']);
		$('[progressally-certificate-file-path="' + cert_id + '"]').val(result['path']);
		$('[progressally-certificate-width="' + cert_id + '"]').val(result['width']);
		$('[progressally-certificate-height="' + cert_id + '"]').val(result['height']);
		update_preview_dimension(cert_id);
		$('[progressally-certificate-pdf-container="' + cert_id + '"]').html('<object data="' + result['url'] + '#toolbar=0&navpanes=0&scrollbar=0" type="application/pdf" width="100%" height="100%"><p>Please install Adobe Acrobat Reader to see the preview</p></object>');

		$('[progressally-certificate-upload-block="' + cert_id + '"]').hide();
		$('[progressally-certificate-customization-block="' + cert_id + '"]').show();
	}
	$(document).on('change', '[progressally-certificate-upload]', ajax_upload_pdf);
	var SLICE_SIZE = 102400,
	progressally_upload_wait_overlay = $('#progressally-upload-wait-overlay');
	function ajax_upload_by_slice(slice_index, file, slice_method, file_size, path, num_retry, cert_id) {
		var start = slice_index * SLICE_SIZE,
			end = start + SLICE_SIZE,
			is_last_piece = '0';
		if (end >= file_size) {
			end = file_size;
			is_last_piece = '1';
		}
		var content = file[slice_method](start, end);
		var data = new FormData();
		data.append('action', 'progressally_upload_certificate_pdf');
		data.append('nonce', progressally_post.nonce);
		data.append('index', slice_index);
		data.append('file_name', file.name);
		data.append('content', content);
		data.append('last', is_last_piece);
		if (path) {
			data.append('path', path);
		}

		$.ajax({
			type: "POST",
			url: progressally_post.ajax_url,
			data: data,
			cache: false,
			contentType: false,
			processData: false,
			success: function(response) {
				try {
					if (!continue_upload) {
						progressally_upload_wait_overlay.hide();
						return;
					}
					var result = JSON.parse(response),
						path = false;
					if ('status' in result) {
						if (result['status'] === 'retry') {
							num_retry += 1;
							if (num_retry > 3) {
								throw "Upload failed 3 times";
							}
							ajax_upload_by_slice(slice_index, file, slice_method, file_size, result['path'], num_retry, cert_id);
							return;
						} else if (result['status'] !== 'success') {
							throw result['message'];
						}
					} else {
						throw "Unable to connect to server";
					}
					path = result['path'];
					if (end < file_size) {
						var percentage = Math.round(end / file_size * 100);
						$('#progressally-upload-progress').text(percentage + '%');
						ajax_upload_by_slice(slice_index + 1, file, slice_method, file_size, path, 0, cert_id);
					} else {
						show_preview_pdf(result, cert_id);
						progressally_upload_wait_overlay.hide();
					}
				} catch (e) {
					alert("Cannot upload file due to error:\n[" + e + "]\nPlease refresh the page and try again.");
					progressally_upload_wait_overlay.hide();
					return;
				}
			}
		});
	}
	var continue_upload = false;
	$(document).on('click touchend', '#progressally-upload-stop', function() {
		continue_upload = false;
	});
	function ajax_upload_pdf() {
		var $this = $(this),
			cert_id = $this.attr('progressally-certificate-upload'),
			slice_method = 'slice',
			file_name = this.files[0].name;
		if (file_name.length < 5) {
			alert('The file name must end in .PDF');
			return;
		}
		if (file_name.substring(file_name.length - 4).toLowerCase() !== '.pdf') {
			alert('The file name must end in .PDF');
			return;
		}
		if ('mozSlice' in this.files[0]) {
			slice_method = 'mozSlice';
		} else if ('webkitSlice' in this.files[0]) {
			slice_method = 'webkitSlice';
		}
		$('#progressally-upload-progress').text('0%');
		progressally_upload_wait_overlay.show();
		continue_upload = true;
		ajax_upload_by_slice(0, this.files[0], slice_method, this.files[0].size, false, 0, cert_id);
		$this.val('');
	}
	/* --------------------- END PDF certificate ------------------------- */
	/* --------------------- PDF certificate text customization ------------------------- */
	$(document).on('change propertychange keyup input paste', '[progressally-certificate-preview-val]', function(e) {
		var target_selector = $(this).attr('progressally-certificate-preview-val'),
			val = $(this).val(),
			$target = $(target_selector);
		$target.find('.progressally-preview-text').text(val);
	});
	$(document).on('change propertychange keyup input paste', '[progressally-certificate-preview-mm]', function(e) {
		var $this = $(this),
			target_selector = $this.attr('progressally-certificate-preview-mm'),
			attribute = $this.attr('preview-attribute'),
			cert_id = $this.attr('progressally-certificate-id'),
			val = $this.val(),
			$target = $('#progressally-certificate-element-' + target_selector);
		val = convert_mm_to_px(val, cert_id) + 'px';
		$target.css(attribute, val);
	});
	$(document).on('change propertychange keyup input paste', '[progressally-certificate-preview-pt]', function(e) {
		var $this = $(this),
			target_selector = $this.attr('progressally-certificate-preview-pt'),
			attribute = $this.attr('preview-attribute'),
			cert_id = $this.attr('progressally-certificate-id'),
			val = $this.val(),
			$target = $('#progressally-certificate-element-' + target_selector);
		val = Math.round(val * 0.352778 * get_mm_scaling_factor(cert_id)) + 'px';

		if (attribute === 'font-size') {
			$target.css('line-height', val);
		}
		$target.css(attribute, val);
	});
	$(document).on('change propertychange keyup input paste', '[progressally-certificate-preview]', function(e) {
		var target_selector = $(this).attr('progressally-certificate-preview'),
			attribute = $(this).attr('preview-attribute'),
			val = $(this).val(),
			$target = $(target_selector);
		$target.css(attribute, val);
	});
	$(document).on('change propertychange keyup input paste', '[progressally-certificate-preview-font]', function(e) {
		var target_selector = $(this).attr('progressally-certificate-preview-font'),
			val = $(this).val(),
			$target = $(target_selector);
		if (val in progressally_post.font_mapping) {
			$target.css('font-family', progressally_post.font_mapping[val]);
		}
	});
	/* --------------------- END PDF certificate text customization ------------------------- */
	/* --------------------- Download PDF certificate ------------------------- */
	$(document).on('touchend click', '[progressally-certificate-download]', function(e) {
		var $this = $(this),
			cert_id = $this.attr('progressally-certificate-download'),
			post_id = $('#progressally-certificate-post-id').val(),
			$parent = $('[progressally-certificate-customization="' + cert_id + '"]'),
			values, cleaned_values, param, needle, url, file_path, file_name, i, temp;
		if ($parent.length === 1) {
			values = serialize_value_in_container($parent);
			cleaned_values = [];
			needle = 'cert[' + cert_id + '][custom]';
			for (i = 0; i < values.length; ++i) {
				temp = values[i];
				if ('name' in temp) {
					temp['name'] = temp['name'].replace(needle, '');
				}
				cleaned_values.push(temp);
			}
			param = JSON.stringify(cleaned_values);

			file_path = encodeURIComponent($('[progressally-certificate-file-path="' + cert_id + '"]').val());
			file_name = encodeURIComponent($('[progressally-certificate-file-name="' + cert_id + '"]').val());
			url = progressally_post.ajax_url + '?action=progressally_admin_download_certificate&post-id=' + post_id + '&path=' + file_path + '&name=' + file_name + '&info=' + encodeURIComponent(param);
			$this.attr('href', url);
		}
	});
	/* --------------------- END Download PDF certificate ------------------------- */
	/* --------------------- Change certificate preview value ------------------------- */
	$(document).on('change', '[progressally-certificate-customize-type]', function(e) {
		var $this = $(this),
		iden = $this.attr('progressally-certificate-customize-type'),
		val = $this.val();
		if (val in progressally_post.cert_template) {
			$('[progressally-certificate-customize-preview="' + iden + '"]').val(progressally_post.cert_template[val]).change();
		}
	});
	/* --------------------- END Change certificate preview value ------------------------- */
	/* --------------------- drag and drop reposition ------------------------- */
	var customization_max_width = 0, customization_max_height = 0, element_info = {};
	function get_customization_area_dimension(cert_id) {
		var $area = $('[progressally-certificate-preview-container="' + cert_id + '"]');
		customization_max_width = $area.outerWidth();
		customization_max_height = $area.outerHeight();
	}
	function get_preview_element_param(element_id) {
		var w = $('[progressally-certificate-preview-element-width="' + element_id + '"]').val(),
			x = $('[progressally-certificate-preview-element-x="' + element_id + '"]').val(),
			y = $('[progressally-certificate-preview-element-y="' + element_id + '"]').val();
		element_info[element_id] = [w, x, y];
	}
	function convert_mm_to_px(mm, cert_id) {
		return Math.round(mm * get_mm_scaling_factor(cert_id));
	}
	function convert_px_to_mm(px, cert_id) {
		var scaling = get_mm_scaling_factor(cert_id);
		if (scaling <= 0) {
			scaling = 1;
		}
		return Math.round(px / scaling * 100) / 100;
	}
	function update_preview_element_param(element_id, cert_id, w, x, y) {
		var temp;
		if (!(element_id in element_info)) {
			get_preview_element_param(element_id);
		}
		if (w !== false) {
			temp = convert_px_to_mm(w, cert_id);
			$('[progressally-certificate-preview-element-width="' + element_id + '"]').val(temp);
			element_info[element_id][0] = temp;
		}
		if (x !== false) {
			temp = convert_px_to_mm(x, cert_id);
			$('[progressally-certificate-preview-element-x="' + element_id + '"]').val(temp);
			element_info[element_id][1] = temp;
		}
		if (y !== false) {
			temp = convert_px_to_mm(y, cert_id);
			$('[progressally-certificate-preview-element-y="' + element_id + '"]').val(temp);
			element_info[element_id][2] = temp;
		}
	}
	function drag_preview_element(e) {
		if (currently_dragging) {
			if (!(currently_dragging_id in element_info)) {
				get_preview_element_param(currently_dragging_id);
			}
			var x = convert_mm_to_px(element_info[currently_dragging_id][1], currently_dragging_certificate_id),
				y = convert_mm_to_px(element_info[currently_dragging_id][2], currently_dragging_certificate_id),
				x_limit = customization_max_width - 0.5 * convert_mm_to_px(element_info[currently_dragging_id][0], currently_dragging_certificate_id);
			x += e.clientX - prev_client_x;
			y += e.clientY - prev_client_y;
			x = Math.min(x_limit, Math.max(0, x));
			y = Math.min(customization_max_height, Math.max(0, y));
			currently_dragging.css('left', x + 'px').css('top', y + 'px');
			update_preview_element_param(currently_dragging_id, currently_dragging_certificate_id, false, x, y);
			prev_client_x = e.clientX;
			prev_client_y = e.clientY;
		}
	}
	var currently_dragging = false, currently_dragging_id = false, currently_dragging_certificate_id = false,
	prev_client_x = 0,
	prev_client_y = 0;
	function reset_current_dragging() {
		if (currently_dragging) {
			currently_dragging = false;
			currently_dragging_id = false;
			currently_dragging_certificate_id = false;
		}
	}
	$(document).on('mousemove', '.progressally-certificate-preview-container', drag_preview_element);
	$(document).on('mousedown', '[progressally-certificate-preview-element]', function(e) {
		var $this = $(this);
		reset_current_dragging();
		currently_dragging = $this;
		currently_dragging_id = $this.attr('progressally-certificate-preview-element');
		currently_dragging_certificate_id = $this.attr('progressally-certificate-id');
		get_preview_element_param(currently_dragging_id);
		get_customization_area_dimension(currently_dragging_certificate_id);

		prev_client_x = e.clientX;
		prev_client_y = e.clientY;
	});
	$(document).mouseup(function(e) {
		reset_current_dragging();
	});
	/* --------------------- END drag and drop reposition ------------------------- */
	/* --------------------- Color picker ------------------------- */
	function bind_color_pickers() {
		$('.nqpc-picker-input-iyxm').each(function(index, elem) {
			progressally_jscolor.bind_element(elem);
			$(elem).removeClass('nqpc-picker-input-iyxm');
		});
	}
	bind_color_pickers();
	/* --------------------- END Color picker ------------------------- */
	/* --------------------- Form value serialization ------------------------- */
	function serialize_value_in_container($container) {
		var values = [];
		$container.find('input[type="hidden"],input[type="text"],textarea').each(function(index, elem) {
			var $elem = $(elem),
				attr = $elem.attr('progressally-param');
			if (typeof attr !== typeof undefined && attr !== false) {
				values.push({ name : attr, value : $elem.val() });
			}
		});
		$container.find('input[type="radio"]:checked').each(function(index, elem) {
			var $elem = $(elem),
				attr = $elem.attr('name');
			if (typeof attr !== typeof undefined && attr !== false) {
				values.push({ name : attr, value : $elem.attr('value') });
			}
		});
		$container.find('input[type="checkbox"]:checked').each(function(index, elem) {
			var $elem = $(elem),
				attr = $elem.attr('progressally-param');
			if (typeof attr !== typeof undefined && attr !== false) {
				values.push({ name : attr, value : $elem.attr('value') });
			}
		});
		$container.find('select').each(function(index, elem) {
			var $elem = $(elem),
				attr = $elem.attr('progressally-param');
			if (typeof attr !== typeof undefined && attr !== false) {
				values.push({ name : attr, value : $elem.children(':selected').val() });
			}
		});
		return values;
	}
	function serialize_form_values() {
		$('[progressally-meta-serialize]').each(function() {
			var $this = $(this),
				target = $this.attr('progressally-meta-serialize'),
				values = serialize_value_in_container($this);

			$('#' + target).val(JSON.stringify(values));
		});
	}
	$(document).on('submit', 'form', serialize_form_values);
	// this is needed to trigger serialization on gutenberg
	if (wp && wp.data && wp.data.subscribe) {
		wp.data.subscribe(function () {
			// fail safe check in case the object is not defined (when both Divi and Yoast are enabled)
			if (!wp.data.select('core/editor')) {
				serialize_form_values();
				return;
			}
			var is_saving_post = wp.data.select('core/editor').isSavingPost(),
				is_autosave = wp.data.select('core/editor').isAutosavingPost();

			if (is_saving_post && !is_autosave) {
				serialize_form_values();
			}
		});
	}

	/* --------------------- END Form value serialization ------------------------- */
	/* --------------------- add certificate ------------------------- */
	$(document).on('touchend click', "#progressally-add-cert", function(e) {
		e.preventDefault();
		var max_id = $('#progressally-max-cert'),
			new_id = parseInt(max_id.val()) + 1,
			new_html = progressally_post_default_code['cert'];
		max_id.val(new_id);
		new_html = new_html.replace(/--certificate-id--/g, new_id);
		new_html = new_html.replace(/--plugin-uri--/g, progressally_post.plugin_uri);
		$(this).before(new_html);

		safe_dispatch_event('progressally_certificate_updated');
		return false;
	});
	/* --------------------- END add certificate ------------------------- */
	/* --------------------- add certificate customization element------------------------- */
	$(document).on('touchend click', "[progressally-certificate-add-element]", function(e) {
		e.preventDefault();
		var cert_id = $(this).attr('progressally-certificate-add-element'),
			max_id = $('[progressally-certificate-element-max="' + cert_id + '"]'),
			new_id = parseInt(max_id.val()) + 1,
			customization_html = progressally_post_default_code['cert-element'],
			preview_html = progressally_post_default_code['cert-preview'];
		max_id.val(new_id);
		customization_html = customization_html.replace(new RegExp('--certificate-id--', 'g'), cert_id);
		customization_html = customization_html.replace(new RegExp('--element-id--', 'g'), new_id);
		$('[progressally-certificate-customization="' + cert_id + '"]').append(customization_html);

		preview_html = preview_html.replace(new RegExp('--certificate-id--', 'g'), cert_id);
		preview_html = preview_html.replace(new RegExp('--element-id--', 'g'), new_id);
		$('[progressally-certificate-pdf-customization="' + cert_id + '"]').append(preview_html);

		bind_color_pickers();

		// trigger change to update the live preview
		$('[progressally-certificate-customize-type="' + cert_id + '-' + new_id +'"]').change();
		$('[progressally-certificate-preview-mm="' + cert_id + '-' + new_id +'"]').change();
		$('[progressally-certificate-preview-pt="' + cert_id + '-' + new_id +'"]').change();
		return false;
	});
	/* --------------------- END add certificate ------------------------- */
	/* --------------------- delete certificate elements ------------------------- */
	$(document).on('touchend click', '[progressally-certificate-element-delete]', function(e){
		e.preventDefault();
		var $this = $(this),
			warning = $this.attr('progressally-delete-warning'),
			iden = $this.attr('progressally-certificate-element-delete');
		if (warning){
			var conf = confirm(warning);
			if(conf !== true){
				return false;
			}
		}
		$('[progressally-certificate-preview-details="' + iden + '"]').remove();
		$('[progressally-certificate-preview-element="' + iden + '"]').remove();
		return false;
	});
	/* --------------------- END delete certificate elements ------------------------- */
	/* --------------------- toggle between certificate upload and preview sections ------------------------- */
	$(document).on('touchend click', '[progressally-certificate-switch-customization]', function(e) {
		e.preventDefault();
		var cert_id =  $(this).attr('progressally-certificate-switch-customization');
		$('[progressally-certificate-upload-block="' + cert_id + '"]').hide();
		$('[progressally-certificate-customization-block="' + cert_id + '"]').show();
		return false;
	});
	$(document).on('touchend click', '[progressally-certificate-switch-upload]', function(e) {
		e.preventDefault();
		var cert_id =  $(this).attr('progressally-certificate-switch-upload');
		$('[progressally-certificate-upload-block="' + cert_id + '"]').show();
		$('[progressally-certificate-customization-block="' + cert_id + '"]').hide();
		return false;
	});
	/* --------------------- END toggle between certificate upload and preview sections ------------------------- */
	/* --------------------- update certificate selection in the shortcode adder ------------------------- */
	var $shortcode_adder_certificate_selection = $('#progressally-mce-editor-certificate-id-select'),
		$shortcode_adder_certificate_no_option_warning = $('#progressally-mce-editor-certificate-id-no-option');
	function update_shortcode_adder_certificate_selection() {
		var $all_cert_names = $('[progressally-certificate-name-input]'),
			$elem, cert_id,
			i = 0;
		$shortcode_adder_certificate_selection.empty();
		if ($all_cert_names.length > 0) {
			for (; i < $all_cert_names.length; ++i) {
				$elem = $($all_cert_names[i]);
				cert_id = $elem.attr('progressally-certificate-name-input');
				$shortcode_adder_certificate_selection.append($('<option></option>')
						.attr('value', cert_id)
						.text(cert_id + '. ' + $elem.val())
						);
			}
			$shortcode_adder_certificate_selection.show();
			$shortcode_adder_certificate_no_option_warning.hide();
		} else {
			$shortcode_adder_certificate_selection.hide();
			$shortcode_adder_certificate_no_option_warning.show();
		}
	}
	if (document.addEventListener) {
		document.addEventListener('progressally_certificate_updated', function(e) { update_shortcode_adder_certificate_selection(); }, false);
	} else if (document.attachEvent) {
		document.attachEvent('progressally_certificate_updated', update_shortcode_adder_certificate_selection);
	}
	update_shortcode_adder_certificate_selection();
	/* --------------------- END update certificate selection in the shortcode adder ------------------------- */
	/* --------------------- add social sharing ------------------------- */
	$(document).on('touchend click', "#progressally-add-share", function(e) {
		e.preventDefault();
		var max_id = $('#progressally-max-share'),
			new_id = parseInt(max_id.val()) + 1,
			new_html = progressally_post_default_code['social-sharing'];
		max_id.val(new_id);
		new_html = new_html.replace(new RegExp('--share-id--', 'g'), new_id);
		$(this).before(new_html);
		
		safe_dispatch_event('progressally_share_updated');
		return false;
	});
	/* --------------------- END add social sharing ------------------------- */
	/* --------------------- update social sharing selection ------------------------- */	
	var $shortcode_adder_share_selection = $('#progressally-mce-editor-share-id-select');
	function generate_social_share_selection() {
		var $all_share_names = $('[progressally-share-name-input]'),
			$elem, share_id,
			i = 0, code = '';
		for (; i < $all_share_names.length; ++i) {
			$elem = $($all_share_names[i]);
			share_id = $elem.attr('progressally-share-name-input');
			code += '<option value="' + share_id + '">' + esc_html(share_id + '. ' + $elem.val()) + '</option>';
		}
		return code;
	}
	function update_social_share_selection() {
		var selection_code = generate_social_share_selection();
		$shortcode_adder_share_selection.html(selection_code);	// we don't need to keep the current selected value in the shortcode adder
	}

	if (document.addEventListener) {
		document.addEventListener('progressally_share_updated', update_social_share_selection, false);
	} else if (document.attachEvent) {
		document.attachEvent('progressally_share_updated', update_social_share_selection);
	}
	/* --------------------- END update social sharing selection ------------------------- */

	// <editor-fold defaultstate="collapsed" desc="refresh the objective list in the shortcode adder">
	function generate_checkbox_elem_for_shortcode_adder_objective_list(objective_id, objective_name) {
		var code = '<li id="progressally-mce-objective-list-row---oid--"><input type="checkbox" ' +
				'id="progressally-mce-objective-list-checkbox---oid--" progressally-mce-objective-list-checkbox="--oid--">' +
				'<label for="progressally-mce-objective-list-checkbox---oid--">--name--</label></li>';
		code = code.replace(/--oid--/g, objective_id);
		code = code.replace(/--name--/g, esc_html(objective_name));
		return $(code);
	}
	function generate_checkbox_elem_for_shortcode_adder_complete_button_objective_list(objective_id, objective_name) {
		var objective_type = $('#progressally-seek-type-' + objective_id).val(),
			can_manually_check = true,
			code = '<li id="progressally-mce-complete-button-objective-row---oid--">' +
			'<input type="checkbox" id="progressally-mce-complete-button-objective-checkbox---oid--" ' +
			'progressally-mce-complete-button-objective-checkbox="--oid--" --checkbox-attr-->' +
			'<label for="progressally-mce-complete-button-objective-checkbox---oid--" --label-attr-->--name--</label></li>';
		if ('quiz' === objective_type || 'post' === objective_type || 'note' === objective_type) {
			can_manually_check = false;
		} else if ('vimeo' === objective_type || 'youtube' === objective_type || 'wistia' === objective_type) {
			if ($('#progressally-checked-complete-video-' + objective_id).is(':checked')) {
				can_manually_check = false;
			}
		}
		code = code.replace(/--oid--/g, objective_id);
		code = code.replace(/--name--/g, esc_html(objective_name));
		if (can_manually_check) {
			code = code.replace(/--checkbox-attr--/g, '');
			code = code.replace(/--label-attr--/g, '');
		} else {
			code = code.replace(/--checkbox-attr--/g, 'disabled="disabled"');
			code = code.replace(/--label-attr--/g, 'class="progressally-mce-complete-button-disabled-option" progressally-tooltip="This objective cannot be manually checked off"');
		}
		return $(code);
	}
	function refresh_shortcode_adder_objective_list() {
		var $objective_names = $('[progressally-objective-name]'),
			$shortcode_adder_complete_button_container = $('#progressally-mce-complete-button-objective-selection'),
			$shortcode_adder_objective_list_container = $('#progressally-mce-objective-list-selection'),
			$elem, objective_id, objective_name, i;

		$shortcode_adder_complete_button_container.empty();
		$shortcode_adder_objective_list_container.empty();

		for (i = 0; i < $objective_names.length; ++i) {
			$elem = $($objective_names[i]);
			objective_id = $elem.attr('progressally-objective-name');
			objective_name = $elem.val();
			$shortcode_adder_complete_button_container.append(generate_checkbox_elem_for_shortcode_adder_complete_button_objective_list(objective_id, objective_name));
			$shortcode_adder_objective_list_container.append(generate_checkbox_elem_for_shortcode_adder_objective_list(objective_id, objective_name));
		}
	}
	function refresh_shortcode_adder_objective_row(objective_id) {
		var $original_complete_button_elem = $('#progressally-mce-complete-button-objective-row-' + objective_id),
			$original_objective_list_elem = $('#progressally-mce-objective-list-row-' + objective_id),
			objective_name = $('[progressally-objective-name="' + objective_id + '"]').val();
		$original_complete_button_elem.after(generate_checkbox_elem_for_shortcode_adder_complete_button_objective_list(objective_id, objective_name));
		$original_objective_list_elem.after(generate_checkbox_elem_for_shortcode_adder_objective_list(objective_id, objective_name));

		$original_complete_button_elem.remove();
		$original_objective_list_elem.remove();
	}
	$(document).on('change', '[progressally-objective-seek-type]', function(e) {
		var objective_id = $(this).attr('progressally-objective-seek-type');
		refresh_shortcode_adder_objective_row(objective_id);
	});
	$(document).on('change', '[progressally-objective-name]', function(e) {
		var objective_id = $(this).attr('progressally-objective-name');
		refresh_shortcode_adder_objective_row(objective_id);
	});
	$(document).on('change', '[progressally-objective-video-complete]', function(e) {
		var objective_id = $(this).attr('progressally-objective-video-complete');
		refresh_shortcode_adder_objective_row(objective_id);
	});
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="trigger event that is compatible with all browsers (including IE)">
	function safe_dispatch_event(event_name) {
		try {
			// trigger the refresh event in case the result HTML code contains interactive elements using other plugins
			if ( typeof window.Event === 'function' ) {
				var event = new Event(event_name);
				document.dispatchEvent(event);
			} else if (typeof document.createEvent === 'function') {	// for compatibility with Internet Explorer
				var event = document.createEvent('Event');
				event.initEvent(event_name, true, true);
				document.dispatchEvent(event);
			}
		} catch (e) {
		}
	}
	// </editor-fold>

	// this must be the last line: only showing the settings when the script has been loaded.
	$('#progressally-post-settings-loading-wait').remove();

	// <editor-fold defaultstate="collapsed" desc="drag-and-drop reorder objectives">
	function process_objective_drag_start(event, ui) {
		$('#progressally-objective-list-placeholder-css').html('.progressally-objective-drop-placeholder{' +
				'height:' + (ui.item.outerHeight() - 11) + 'px}');
	}
	function initialize_objective_drag_and_drop() {
		var $objective_container = $('#progressally-objective-list-content');

		if (!$objective_container.sortable('instance')) {
			$objective_container.sortable(
				{
					items: 'tr.progressally-objective-list-row',
					cursor: 'move',
					forcePlaceholderSize: true,
					placeholder: 'progressally-objective-drop-placeholder',
					tolerance: 'pointer',
					dropOnEmpty: true,
					handle: '.progressally-setting-list-order-move',
					start: process_objective_drag_start,
					stop: refresh_shortcode_adder_objective_list
				});
		} else {
			$objective_container.sortable('refresh');
			$objective_container.sortable('option', 'disabled', false);
		}
	}
	initialize_objective_drag_and_drop();
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="Clone quiz question">
	$(document).on('touchend click', '[progressally-clone-question]', function(e){
		e.preventDefault();
		var question_id = $(this).attr('progressally-clone-question'),
			$question_container = $('#progressally-question-block-' + question_id),
			question_data = serialize_value_in_container($question_container),
			outcome_data = serialize_value_in_container($('#progressally-quiz-outcome-container')),
			data = {
				action: 'progressally_clone_question',
				input: JSON.stringify(question_data),
				question_id: question_id,
				outcome: JSON.stringify(outcome_data),
				nonce: progressally_post.nonce
			};

		progressally_wait_overlay.show();
		$.ajax({
			type: "POST",
			url: progressally_post.ajax_url,
			data: data,
			success: function(response) {
				var result = JSON.parse(response);
				if (typeof result === 'object' && 'status' in result) {
					if ('success' === result['status']) {
						var max_id = $('#progressally-quiz-max-question'),
							new_id = parseInt(max_id.val()) + 1,
							new_html = result['code'];
						max_id.val(new_id);
						new_html = new_html.replace(new RegExp('--qid--', 'g'), new_id);
						$('#progressally-quiz-question-container').append(new_html);
						update_dynamic_quiz_html_for_question('#progressally-question-block-' + new_id); // refresh preview html code

						$('#progressally-quiz-choice-display-select').change();

						var $new_element = $('#progressally-question-block-' + new_id);
						if ($new_element.length > 0) {
							$new_element[0].scrollIntoView();
						}
					} else {
						alert(result['message']);
					}
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert('Cannot communicate with server due to error: ' + thrownError);
			},
			complete: function() {
				progressally_wait_overlay.hide();
			}
		});
	});
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="drag-and-drop quiz questions">
	function process_quiz_question_drag_start(event, ui) {
		$('#progressally-quiz-question-placeholder-css').html('.progressally-question-drop-placeholder{' +
				'height:' + (ui.item.outerHeight() - 11) + 'px}');
	}
	$(document).on('mousedown touchstart', '.progressally-setting-quiz-order-move', function() {
		$('.progressally-quiz-question-toggle:checked').each(function(index, elem) {
			var $elem = $(elem);
			$elem.prop('checked', false);
			hide_toggle_element($elem, true);
		});
	});
	function initialize_quiz_question_drag_and_drop() {
		var $question_container = $('#progressally-quiz-question-container');

		if (!$question_container.sortable('instance')) {
			$question_container.sortable(
				{
					items: '.progressally-setting-question-block',
					cursor: 'move',
					forcePlaceholderSize: true,
					placeholder: 'progressally-question-drop-placeholder',
					tolerance: 'pointer',
					dropOnEmpty: true,
					handle: '.progressally-setting-quiz-order-move',
					start: process_quiz_question_drag_start
				});
		} else {
			$question_container.sortable('refresh');
			$question_container.sortable('option', 'disabled', false);
		}
	}
	initialize_quiz_question_drag_and_drop();
	// </editor-fold>
});
