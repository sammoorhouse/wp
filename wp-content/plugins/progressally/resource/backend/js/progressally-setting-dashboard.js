/* global progressally_settings_object */

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
	// <editor-fold defaultstate="collapsed" desc="tab control">
	function change_tab($elem) {
		var selector = $elem.attr('tab-group'),
			target = $elem.attr('target'),
			active = $elem.attr('active-class'),
			$tabs = $('[' + selector + ']');
		$('[tab-group=' + selector + ']').removeClass(active);
		$elem.addClass(active);
		$tabs.filter('[' + selector + '!=' + target + ']').hide();
		$tabs.filter('[' + selector + '=' + target + ']').show();
	}
	$(document).on('click touchend', '[tab-group]', function(e) {
		change_tab($(this));
	});
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="load page overview table">
	var progressally_access_wait_row = $('#progressally-dashboard-page-wait-row'),
		progressally_access_content_container = $('#progressally-dashboard-page-content-container'),
		progressally_access_error_row = $('#progressally-dashboard-page-error-row'),
		progressally_access_max_page = $('#progressally-access-max-page'),
		progressally_access_type_selection = $('#progressally-access-type-selection'),
		progressally_access_current_page_input = $('#progressally-access-page-input');
	function refresh_overview_page_list() {
		var data = {
			action: 'progressally_get_page_overview',
			param: serialize_value_in_container($('#progressally-dashboard-overview-search')),
			progressally_access_nonce : progressally_settings_object.update_nonce
		};
		$('.progressally-dashboard-page-content-wrapper').hide();
		progressally_access_content_container.empty();
		progressally_access_error_row.hide();
		progressally_access_wait_row.show();
		$.ajax({
			type: "POST",
			url: progressally_settings_object.ajax_url,
			data: data,  
			success: function(response) {
				try {
					var result = JSON.parse(response);
					if (!('status' in result)) {
						throw 'Unknown error';
					}
					if ('success' != result['status']) {
						throw result['message'];
					}
					progressally_access_content_container.html(result['code']);
					progressally_access_current_page_input.val(result['page-num']);
					progressally_access_max_page.text(result['max']);
					$('.progressally-dashboard-page-content-wrapper').show();
				} catch (e) {
					progressally_access_error_row.show().text('Fetch rows failed due to error: '+e);
					return;
				}
			},
			complete: function(jqXHR, textStatus) {
				progressally_access_wait_row.hide();
			}
		});
	}
	// refresh list on load
	refresh_overview_page_list();
	$('[progressally-access-action]').on('click', function(e) {
		var max = parseInt(progressally_access_max_page.text()),
			action = $(this).attr('progressally-access-action'),
			target = parseInt(progressally_access_current_page_input.val());
		if (action === 'first') {
			target = 1;
		} else if (action === 'last') {
			target = max;
		} else {
			target += parseInt(action);
			target = Math.min(max, Math.max(1, target));
		}
		progressally_access_current_page_input.val(target);
		refresh_overview_page_list();
		e.preventDefault();
		return false;
	});
	// trigger refresh on enter key press
	$(document).on('keypress', '#progressally-dashboard-page-search, #progressally-access-page-input', function(e){
		if(e.keyCode == 13){
			refresh_overview_page_list();
			e.preventDefault();
			return false;
		}
	});
	progressally_access_type_selection.on('change', function(e){
		// when the page type is changed, always reset to show page 1
		progressally_access_current_page_input.val(1);
		refresh_overview_page_list();
	});
	$(document).on('touchend click', '#progressally-dashboard-overview-page-search', refresh_overview_page_list);
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
			this.wrapper = $("<span>")
					.addClass("progressally-option-combobox")
					.insertAfter(this.element);

			this.element.hide();
			this._createAutocomplete();
			if ($(this.element).hasClass('progressally-tag-input')) {
				this._createRefreshTrigger();
			}
			var combobox_this = this;
			$(this.element).on('change', function() {
				var selected_label = $(combobox_this.element).find('option:selected').text();
				combobox_this.input.val(selected_label);
			});
		},
		_createRefreshTrigger: function () {
			$("<div class='progressally-refresh-tag-trigger-container' progressally-tooltip='Can&#39;t find a tag? Click here to refresh the tag list!'><div class='progressally-refresh-tag-trigger'></div></div>").appendTo(this.wrapper);
		},
		_createAutocomplete: function () {
			var selected = this.element.children(":selected"),
				value = selected.val() ? selected.text() : "";

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
	function generate_auto_complete_combobox() {
		$('.progressally-autocomplete-add').each(function(index, elem) {
			$(elem).removeClass('progressally-autocomplete-add').progressally_combobox();
		});
	}
	generate_auto_complete_combobox();
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="admin dashboard header toggle">
	$(document).on('change', '.progressally-dashboard-page-detail-toggle', function(e) {
		var $this = $(this),
			is_checked = $this.is(':checked'),
			$parent = $this.parents('.progressally-dashboard-page-row-wrapper');
		if (is_checked) {
			$parent.addClass('progressally-dashboard-page-row-wrapper-active');
		} else {
			$parent.removeClass('progressally-dashboard-page-row-wrapper-active');
		}
	});
	$(document).on('click touchend', '.progressally-dashboard-page-row', function(e) {
		var $toggle_element = $(this).find('.progressally-dashboard-page-detail-toggle');
		$toggle_element.prop('checked', !$toggle_element.is(':checked')).change();
	});
	// </editor-fold>
	
	/* --------------------- page specific detail view ------------------------- */
	$(document).on('click touchend', '.progressally-dashboard-page-detailview-link', function(e) {
		var post_id = $(this).attr('post-id');
		change_tab($('[tab-group="progressally-tab-group-1"][target="detail"]'));
		$('#progressally-detail-page-selection').val(post_id).change();
	});

	$(document).on('change', '#progressally-detail-page-selection', function(e) {
		var selected = $(this).val();
		if (selected !== '0') {
			refresh_page_detail(selected);
		}
	});
	function refresh_page_detail(post_id) {
		var	wait_element = $('#progressally-dashboard-page-detailview-wait-row'),
			target = $('#progressally-dashboard-page-detailview-report');
		target.empty().hide();
		wait_element.show();
		
		var data = {
				action: 'progressally_get_detail_reports',
				post_id: post_id,
				progressally_access_nonce : progressally_settings_object.update_nonce
			};

		$.ajax({
			type: "POST",
			url: progressally_settings_object.ajax_url,
			data: data,
			success: function(response) {
				try {
					var result = JSON.parse(response);
					if (!('status' in result)) {
						throw 'Unknown error';
					}
					if (result['status'] != 'success') {
						throw result['message'];
					}
					if ('codes' in result) {
						target.html(result['codes']).show();
					}
				} catch (e) {
					target.html('Fetch detail failed due to error: ' + e).show();
				}
			},
			complete: function(jqXHR, textStatus) {
				wait_element.hide();
			}
		});
	}
	/* --------------------- END page specific detail view ------------------------- */

	// <editor-fold defaultstate="collapsed" desc="serialize form value">
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
		return JSON.stringify(values);
	}
	// </editor-fold>
});