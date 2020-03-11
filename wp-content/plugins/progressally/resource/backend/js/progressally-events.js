/* global progressally_events_object, progressally_event_default_code */

jQuery(document).ready(function($) {
	// <editor-fold defaultstate="collapsed" desc="data-dependency logic">
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
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="Tab change operation">
	var all_progressally_tab_group = $('[tab-group]');
	
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
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="combobox auto-complete">
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
			this.entry_type = this.element.attr('entry-type');
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
							if ('tag' === this.entry_type) {
								var name_elem = $('<div class="progressally-tag-name"></div>').text(ui.item.option.label),
									val_elem = $('<input progressally-param="' + this.variable_name + '[]" type="hidden" />').val(ui.item.option.value);
								$('<div class="progressally-tag"><div class="progressally-tag-delete">&#x2715;</div></div>').append(name_elem).append(val_elem).insertBefore(this.element);
							} else if ('page' === this.entry_type) {
								var name_elem = $('<div class="progressally-page-name"></div>').text(ui.item.option.label),
									val_elem = $('<input progressally-param="' + this.variable_name + '[]" type="hidden" />').val(ui.item.option.value);
								$('<div class="progressally-page"><div class="progressally-page-delete">&#x2715;</div></div>').append(name_elem).append(val_elem).insertBefore(this.element);
							}
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
	$(document).on('touchend click', ".progressally-page-delete", function(e) {
		var $parent = $(this).parent('div.progressally-page');
		$parent.remove();
	});
	function generate_auto_complete_combobox() {
		$('.progressally-autocomplete-add').each(function(index, elem) {
			$(elem).removeClass('progressally-autocomplete-add').progressally_combobox();
		});
	}
	generate_auto_complete_combobox();
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="tag list refresh">
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
				progressally_events_object.tag_selection_code = response['tags'];
			}
		} catch (e) {
			return;
		}
	}
	$(document).on('touchend click', '.progressally-refresh-tag-trigger', function(e) {
		progressally_wait_overlay.show();
		var data = {
				action: 'progressally_refresh_tag',
				nonce: progressally_events_object.nonce,
			};

		$.ajax({
			type: "POST",
			url: progressally_events_object.ajax_url,
			data: data,
			success: function(response) {
				var result = JSON.parse(response);
				refresh_tag_list_autocomplete(result);
				progressally_wait_overlay.hide();
			}
		});
	});
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="tooltips">
	$(document).on('mouseenter', '[progressally-tooltip]', function(e) {
		var $this = $(this);
		$('<div class="progressally-tooltip-display"></div>').text($this.attr('progressally-tooltip')).fadeTo(500, 1).appendTo($this);
	});
	$(document).on('mouseleave', '[progressally-tooltip]', function(e) {
		var $this = $(this),
			$display = $this.find('.progressally-tooltip-display');
		$display.fadeTo(500, 0, function(){$display.remove()});
	});
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="add new event section">
	$(document).on('touchend click', '#progressally-add-event', function(e) {
		e.preventDefault();
		var max_id = $('#progressally-max-event'),
			new_id = parseInt(max_id.val()) + 1,
			new_html = progressally_event_default_code['event'];
		max_id.val(new_id);
		new_html = new_html.replace(/--id--/g, new_id);
		new_html = new_html.replace(/--tag-alphabetic-selection--/g, progressally_events_object.tag_selection_code);
		new_html = new_html.replace(/--page-selection--/g, progressally_events_object.page_selection_code);
		$('#progressally-setup-events').append(new_html);

		generate_auto_complete_combobox();
		return false;
	});
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="serialize input values on submit">
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
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="save event settings">
	$(document).on('touchend click', '[progressally-event-save]', function(e) {
		var event_id = $(this).attr('progressally-event-save'),
			setting_values = serialize_value_in_container($('#progressally-event-container-' + event_id)),
			data = {
				action: 'progressally_save_event_setting',
				value : JSON.stringify(setting_values),
				event_id : event_id,
				nonce: progressally_events_object.nonce,
			};

		progressally_wait_overlay.show();
		$.ajax({
			type: "POST",
			url: progressally_events_object.ajax_url,
			data: data,
			success: function(response) {
				try {
					var result = JSON.parse(response);
					if (typeof result !== 'object') {
						throw 'Invalid response: ' + response;
					}
					if ('code' in result) {
						var $current_elem = $('#progressally-event-container-' + event_id);
						$current_elem.after(result['code']);
						$current_elem.remove();
						generate_auto_complete_combobox();
						scroll_element_into_view($('#progressally-event-container-' + event_id), false);
					} else {
						throw result['message'];
					}
				} catch (e) {
					alert('Save failed due to error: ' + e);
					return;
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert('Server error. Please contact site admin/owner.');
			},
			complete: function(jqXHR, textStatus) {
				progressally_wait_overlay.hide();
			}
		});
	});
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="delete event settings">
	$(document).on('touchend click', '[progressally-event-delete]', function(e) {
		var conf = confirm('Deleting this event cannot be undone. Continue?');
		if(conf !== true){
			return false;
		}
		var event_id = $(this).attr('progressally-event-delete'),
			data = {
				action: 'progressally_delete_event_setting',
				event_id : event_id,
				nonce: progressally_events_object.nonce,
			};

		progressally_wait_overlay.show();
		$.ajax({
			type: "POST",
			url: progressally_events_object.ajax_url,
			data: data,
			success: function(response) {
				try {
					var result = JSON.parse(response);
					if (typeof result !== 'object') {
						throw 'Invalid response: ' + response;
					}
					if ('status' in result && 'success' === result['status']) {
						var $current_elem = $('#progressally-event-container-' + event_id);
						$current_elem.remove();
					} else {
						throw result['message'];
					}
				} catch (e) {
					alert('Delete failed due to error: ' + e);
					return;
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert('Server error. Please contact site admin/owner.');
			},
			complete: function(jqXHR, textStatus) {
				progressally_wait_overlay.hide();
			}
		});
	});
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="edit event settings">
	$(document).on('touchend click', '[progressally-event-edit]', function(e) {
		var event_id = $(this).attr('progressally-event-edit'),
			$edit_view = $('#progressally-event-container-edit-view-' + event_id);
		$('#progressally-event-container-readonly-view-' + event_id).hide();
		$edit_view.show();
		scroll_element_into_view($edit_view, false);
	});
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="cancel event settings edit">
	$(document).on('touchend click', '[progressally-event-cancel]', function(e) {
		var event_id = $(this).attr('progressally-event-cancel'),
			$readonly_view = $('#progressally-event-container-readonly-view-' + event_id);
		$('#progressally-event-container-edit-view-' + event_id).hide();
		$readonly_view.show();
		scroll_element_into_view($readonly_view, false);
	});
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="get objective list for selected page">
	$(document).on('change', '[progressally-trigger-objective-update]', function(e) {
		var $this = $(this),
			event_id = $this.attr('progressally-trigger-objective-update'),
			page_id = parseInt($this.val()),
			$objective_list_container = $('#progressally-event-trigger-objective-list-' + event_id);
		get_objective_list_for_selected_page(event_id, page_id, 'progressally_get_event_trigger_objectives', $objective_list_container);
	});
	$(document).on('change', '[progressally-action-objective-update]', function(e) {
		var $this = $(this),
			event_id = $this.attr('progressally-action-objective-update'),
			page_id = parseInt($this.val()),
			$objective_list_container = $('#progressally-event-action-objective-list-' + event_id);
		get_objective_list_for_selected_page(event_id, page_id, 'progressally_get_event_action_objectives', $objective_list_container);
	});
	function get_objective_list_for_selected_page(event_id, page_id, ajax_action, $target_container) {
		$target_container.empty()
		if (page_id <= 0) {
			$target_container.hide();
			return;
		}
		var data = {
				action: ajax_action,
				event_id : event_id,
				page_id : page_id,
				nonce: progressally_events_object.nonce,
			};

		progressally_wait_overlay.show();
		$.ajax({
			type: "POST",
			url: progressally_events_object.ajax_url,
			data: data,
			success: function(response) {
				try {
					var result = JSON.parse(response);
					if (typeof result === 'object' && 'code' in result) {
						$target_container.html(result['code']).show();
					} else {
						throw 'Invalid response: ' + response;
					}
				} catch (e) {
					alert('Save failed due to error: ' + e);
					return;
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert('Server error. Please contact site admin/owner.');
			},
			complete: function(jqXHR, textStatus) {
				progressally_wait_overlay.hide();
			}
		});
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="Scroll the window so that the element is in view">
	function scroll_element_into_view($elem, $parent) {
		if (false === $parent) {
			var offset = $elem.offset();
			document.body.scrollTop = offset.top - 60;
		} else {
			var position = $elem.position(),
				current_scroll = $parent.scrollTop();
			$parent.scrollTop(current_scroll + position.top - 30);
		}
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="log retrieval utility functions">
	function process_log_retrieval_result(response, $header_row, current_page_elem, max_page_elem) {
		try {
			var result = JSON.parse(response);
			if (typeof result === 'object' && 'status' in result) {
				if (result['status'] === 'success') {
					if ('data' in result && 'header' in result) {
						if (!('data' in result) || result['data'].length === 0) {
							$header_row.html('<td></td>').children().text('No log entry');
						} else {
							$header_row.empty().html(result['header']).after(result['data']);
						}
					}
					current_page_elem.val(result['page']);
					max_page_elem.text(result['max']);
				} else {
					if ('message' in result) {
						$header_row.html('<td></td>').children().text('Fetch rows failed due to error: ' + result['message']);
					} else {
						$header_row.html('<td></td>').children().text('Fetch rows failed due to unknown error');
					}
				}
			} else {
				$header_row.html('<td></td>').children().text('Fetch rows failed due to unknown error');
			}
		} catch (e) {
			$header_row.html('<td></td>').children().text('Fetch rows failed due to error: '+e);
			return;
		}
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="retrieve event log">
	function update_event_logs() {
		var $form = $('#progressally-event-log-container'),
			serialized_info = serialize_value_in_container($form),
			data = {
				action: 'progressally_get_event_log',
				nonce: progressally_events_object.nonce,
				info: JSON.stringify(serialized_info)
			};

		$progressally_event_log_header_row.html('<td>Fetching data. Please wait</td>');
		$('.progressally-event-log-content-row').remove();
		$.ajax({
			type: "POST",
			url: progressally_events_object.ajax_url,
			data: data,
			success: function(response) {
				process_log_retrieval_result(response, $progressally_event_log_header_row, $progressally_event_log_current_page, $progressally_event_log_max_page);
				$('#progressally-event-log-table').resize();
			}
		});
	}
	var $progressally_event_log_header_row = $('#progressally-event-log-header-row'),
	$progressally_event_log_current_page = $('#progressally-event-log-current-page'),
	$progressally_event_log_max_page = $('#progressally-event-log-max-page');
	$(document).on('touchend click', '[progressally-event-log-action]', function() {
		var $this = $(this),
			change = $this.attr('progressally-event-log-action'),
			max = parseInt($progressally_event_log_max_page.text()),
			current = parseInt($progressally_event_log_current_page.val());
		if (change === 'first') {
			$progressally_event_log_current_page.val('1');
		} else if (change === '1') {
			$progressally_event_log_current_page.val(Math.min(current + 1, max));
		} else if (change === '-1') {
			$progressally_event_log_current_page.val(Math.max(current - 1, 1));
		} else if (change === 'last') {
			$progressally_event_log_current_page.val(max);
		}
		update_event_logs();
	});
	$(document).on('keydown', 'input.progressally-update-event-log', function(e){
		if(e.keyCode == 13) {
			$(this).focusout();
			update_event_logs();
			e.preventDefault();
			return false;
		}
	});
	update_event_logs();
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="duplicate scroll bar">
	$('[progressally-duplicate-scroll-width]').on('resize', function(){
		var $this = $(this),
			$target = $($this.attr('progressally-duplicate-scroll-width')),
			parent_width = $this.parent().width(),
			element_width = $this.width();
		$target.children().width(element_width);
		$target.scrollLeft(0);
		if (element_width >= parent_width) {
			$target.show();
		} else {
			$target.hide();
		}
	}).resize();
	$('[progressally-duplicate-scroll]').on('scroll', function() {
		var $this = $(this),
			$target = $($this.attr('progressally-duplicate-scroll'));
		$target.scrollLeft($this.scrollLeft());
	});
	$('[progressally-duplicate-scroll-target]').on('scroll', function() {
		var $this = $(this),
			$target = $($this.attr('progressally-duplicate-scroll-target'));
		$target.scrollLeft($this.scrollLeft());
	});
	// </editor-fold>
});