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
	/* --------------- note retrieval utilities --------------- */
	function serialize_input_values($form) {
		var $inputs = $form.find('[form-name]'),
			data = {},
			i;
		for (i = 0; i < $inputs.length; ++i) {
			data[$inputs[i].attributes['form-name'].value] = $inputs[i].value;
		}
		return data;
	}
	function process_note_retrieval_result(response, max_page_string, message_row_string, message_header) {
		var max_page = $(max_page_string),
			message_row = $(message_row_string);
		try {
			var result = JSON.parse(response);
		} catch (e) {
			message_row.text(message_header + ' failed due to error: '+e);
			return;
		}
		if (typeof result === 'object' && 'status' in result) {
			if (result['status'] === 'success') {
				if ('code' in result) {
					message_row.after(result['code']);
				}
				message_row.hide();
				max_page.text(result['max']);
			} else {
				if ('message' in result) {
					message_row.text(result['message']);
				} else {
					message_row.text(message_header + ' failed due to unknown error');
				}
				max_page.text(1);
			}
		} else {
			message_row.text(message_header + ' failed due to unknown error');
		}
	}
	/* --------------- END note retrieval utilities --------------- */
	/* --------------- note reply retrieval --------------- */
	var progressally_note_reply_current_page = $('#progressally-note-reply-page-input');
	function update_note_reply() {
		if (num_notes_current_editing > 0) {
			if(confirm('You are currently editing an reply. Any unsaved changes will be lost on refresh. Continue?') !== true){
				return false;
			}
			num_notes_current_editing = 0;
		}
		var $form = $('#progressally-note-reply-input'),
			data = serialize_input_values($form);
		data['action'] = 'progressally_get_note_reply';
		data['nonce'] = progressally_settings_object.update_nonce;

		$('#progressally-note-reply-message-container').text('Fetching data. Please wait').show();
		$('.progressally-note-reply-content-row').remove();
		$.ajax({
			type: "POST",
			url: progressally_settings_object.ajax_url,
			data: data,
			success: function(response) {
				process_note_retrieval_result(response, '#progressally-note-reply-max-page', '#progressally-note-reply-message-container', 'Fetch notes');
			}
		});
	}
	$(document).on('touchend click', '[progressally-note-reply-action]', function() {
		var $this = $(this),
			change = $this.attr('progressally-note-reply-action'),
			max = parseInt($('#progressally-note-reply-max-page').text()),
			current = parseInt(progressally_note_reply_current_page.val());
		if (change === 'first') {
			progressally_note_reply_current_page.val('1');
		} else if (change === '1') {
			progressally_note_reply_current_page.val(Math.min(current + 1, max));
		} else if (change === '-1') {
			progressally_note_reply_current_page.val(Math.max(current - 1, 1));
		} else if (change === 'last') {
			progressally_note_reply_current_page.val(max);
		}
		update_note_reply();
	});
	$('select.progressally-update-note-reply').on('change', function(){
		progressally_note_reply_current_page.val('1');
		update_note_reply();
	});
	// stop Enter key in the note reply section from submitting the form
	$(document).on('keypress', 'input.progressally-update-note-reply', function(e) {
		if(e.keyCode == 13){
			update_note_reply();
			e.preventDefault();
			return false;
		}
	});
	update_note_reply();
	/* --------------- END note reply retrieval --------------- */
	/* -------------- notes editing ------------------- */
	var num_notes_current_editing = 0,
		is_display_originally_hidden = {};
	$(document).on('click touchend', '[progressally-notes-update]', function(e) {
		var $this = $(this),
			row_id = $this.attr('progressally-notes-update'),
			$display_element = $('[progressally-notes-reply-display="' + row_id + '"]'),
			$input = $('[progressally-notes-reply-input="' + row_id + '"]');
		is_display_originally_hidden[row_id] = $display_element.is(':hidden');
		$('[progressally-notes-operation="' + row_id + '"]').hide();
		$display_element.hide();
		$('[progressally-notes-reply-input-container="' + row_id + '"]').show();

		$input.show().focus().select();
		++num_notes_current_editing;
	});
	$(document).on('click touchend', '[progressally-notes-ignore]', function(e) {
		var $this = $(this),
			row_id = $this.attr('progressally-notes-ignore');
		close_note(row_id);
	});
	$(document).on('click touchend', '[progressally-notes-reply-save]', function(e) {
		var $this = $(this),
			row_id = $this.attr('progressally-notes-reply-save');
		commit_note_edit(row_id, false);
	});
	$(document).on('click touchend', '[progressally-notes-reply-save-approve]', function(e) {
		var $this = $(this),
			row_id = $this.attr('progressally-notes-reply-save-approve');
		commit_note_edit(row_id, true);
	});
	$(document).on('click touchend', '[progressally-notes-reply-cancel]', function(e) {
		var row_id = $(this).attr('progressally-notes-reply-cancel');
		cancel_note_edit(row_id);
	});
	$(document).on('keydown', '[progressally-notes-reply-input]', function(e) {
		if(e.keyCode == 27){
			e.preventDefault();
			var $this = $(this),
				row_id = $this.attr('progressally-notes-reply-input');
			cancel_note_edit(row_id);
			$this.blur();
			return false;
		}
	});
	$(document).on('click touchend', '[progressally-notes-approve]', function(e) {
		var row_id = $(this).attr('progressally-notes-approve');
		approve_note(row_id);
	});
	function cancel_note_edit(row_id) {
		--num_notes_current_editing;
		$('[progressally-notes-operation="' + row_id + '"]').show();
		if (row_id in is_display_originally_hidden && !is_display_originally_hidden[row_id]) {
			$('[progressally-notes-reply-display="' + row_id + '"]').show();
		}
		$('[progressally-notes-reply-input-container="' + row_id + '"]').hide();
	}
	function commit_note_edit(row_id, approve) {
		var $input_elem = $('[progressally-notes-reply-input="' + row_id + '"]'),
			val = $input_elem.val(),
			post_id = $input_elem.attr('post-id'),
			note_id = $input_elem.attr('note-id'),
			user_id = $input_elem.attr('user-id'),
			format = $('[progressally-notes-reply-format="' + row_id + '"]').is(':checked') ? 'html' : 'text',
			ordinal = $input_elem.attr('ordinal'),
			$files_to_upload = $('[progress-note-attachment-file="progressally-notes-reply-' + row_id + '-' + ordinal + '"]'),
			existing_attachments = collect_existing_attachment_info('note-reply', row_id, ordinal),
			valid_files = filter_valid_files_to_upload($files_to_upload);
		$('#progressally-wait-note-reply-' + row_id).show();
		if (val.length <= 0 && valid_files.length > 0) {
			val = ' ';	// assign dummy value when there are valid attachments, so the note is not consider as empty and be deleted.
		}
		var data = {
				action: 'progressally_admin_notes_update',
				val: val,
				pid: post_id,
				nid: note_id,
				uid: user_id,
				rid: row_id,
				format: format,
				ord: ordinal,
				att: existing_attachments.join(','),
				approve: approve,
				nonce: progressally_settings_object.update_nonce
			};

		$.ajax({
			type: "POST",
			url: progressally_settings_object.ajax_url,
			data: data,
			success: function(response) {
				try {
					process_notes_update_result(response, row_id, ordinal, user_id, valid_files);
				} catch (e) {
					alert(e);
					$('#progressally-wait-note-reply-' + row_id).hide();
				}
			}
		});
		return false;
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
	function process_notes_update_result(response, row_id, ordinal, user_id, valid_files) {
		var result = JSON.parse(response);
		if ('status' in result) {
			if (result['status'] === 'error') {
				throw result['message'];
			}
			if (valid_files.length > 0) {	// no file to upload
				add_note_attachment_files(row_id, ordinal, user_id, 0, valid_files, 'note-reply-' + row_id);
			} else {
				// don't need to hide the wait overlay because the entire code block is replaced
				process_update_note_display_code(result, 'note-reply-' + row_id);	// only update the code if there is no file to upload
				--num_notes_current_editing;
			}
		} else {
			throw 'Invalid response. Please refresh the page and try again.';
		}
	}
	function close_note(row_id) {
		var data = {
				action: 'progressally_admin_notes_close',
				rid: row_id,
				nonce: progressally_settings_object.update_nonce
			};

		$('[progressally-notes-operation-wait="' + row_id + '"]').show();
		$.ajax({
			type: "POST",
			url: progressally_settings_object.ajax_url,
			data: data,
			success: function(response) {
				try {
					process_notes_close_result(response, row_id);
				} catch (e) {
					alert(e);
				} finally {
					$('[progressally-notes-operation-wait="' + row_id + '"]').hide();
				}
			}
		});
		return false;
	}
	function process_notes_close_result(response, row_id) {
		var result = JSON.parse(response);
		if ('status' in result) {
			if (result['status'] === 'error') {
				throw result['message'];
			}
			if ('data' in result) {
				$('[progressally-notes-container="' + row_id + '"]').attr('progressally-note-status', result['data']['status']);
			}
		} else {
			throw 'Invalid response. Please refresh the page and try again.';
		}
	}
	function approve_note(row_id) {
		var data = {
				action: 'progressally_admin_notes_approve',
				rid: row_id,
				nonce: progressally_settings_object.update_nonce
			};

		$('[progressally-notes-operation-wait="' + row_id + '"]').show();
		$.ajax({
			type: "POST",
			url: progressally_settings_object.ajax_url,
			data: data,
			success: function(response) {
				try {
					process_notes_approve_result(response, row_id);
				} catch (e) {
					alert(e);
				} finally {
					$('[progressally-notes-operation-wait="' + row_id + '"]').hide();
				}
			}
		});
		return false;
	}
	function process_notes_approve_result(response, row_id) {
		var result = JSON.parse(response);
		if ('status' in result) {
			if (result['status'] === 'error') {
				throw result['message'];
			}
			if ('data' in result) {
				$('[progressally-notes-container="' + row_id + '"]').attr('progressally-note-approve-status', result['data']['approve_status']);
				$('[progressally-notes-container="' + row_id + '"]').find('.progressally-backend-note-reply-time').hide();
				update_display_status(result['data']['display_status'], row_id);
			}
		} else {
			throw 'Invalid response. Please refresh the page and try again.';
		}
	}
	function update_display_status(display_status, row_id) {
		$('[progressally-notes-container="' + row_id + '"]').find('.progressally-backend-note-reply-note-status-text').html(display_status);
		if (display_status === '') {
			$('[progressally-notes-container="' + row_id + '"]').find('.progressally-backend-note-reply-note-status').hide();
		} else {
			$('[progressally-notes-container="' + row_id + '"]').find('.progressally-backend-note-reply-note-status').show();
		}
	}
	/* -------------- END notes editing ------------------- */
	/* -------------- admin init note post selection ------------------- */
	var $admin_init_note_input_post_id = $('#progressally-admin-init-post-id-input'),
	$admin_init_note_input_note_id = $('#progressally-admin-init-note-id-input');
	$('html').on('touchend click', "#progressally-backend-admin-init-confirm-selection-button", function(e) {
		var $post_selection = $('#progressally-admin-init-select-post option:selected'),
			selected_post_id = $post_selection.val(),
			$note_selection = $('#progressally-admin-init-select-note-' + selected_post_id + ' option:selected');
		$('#progressally-admin-init-create-header').text($post_selection.text() + ' - ' + $note_selection.text());
		$('#progressally-admin-init-current-step').val('add').change();
		$admin_init_note_input_post_id.val(selected_post_id);
		$admin_init_note_input_note_id.val($note_selection.val());
		update_admin_init_note_user_list();
	});
	$(document).on('touchend click', '#progressally-backend-admin-init-change-selection', function(e) {
		$('#progressally-admin-init-current-step').val('select').change();
		e.stopPropagation();
		return false;
	});
	/* -------------- END admin init note post selection ------------------- */
	/* --------------- admin init user retrieval --------------- */
	var progressally_admin_init_current_page = $('#progressally-admin-init-page-input');
	function update_admin_init_note_user_list() {
		if (num_admin_init_notes_current_editing > 0) {
			if(confirm('You are currently editing an admin-initiated note. Any unsaved changes will be lost on refresh. Continue?') !== true){
				return false;
			}
			num_admin_init_notes_current_editing = 0;
		}
		var $form = $('#progressally-admin-init-input'),
			data = serialize_input_values($form);
		data['action'] = 'progressally_get_admin_init_notes';
		data['nonce'] = progressally_settings_object.update_nonce;

		$('#progressally-admin-init-message-container').text('Fetching data. Please wait').show();
		$('.progressally-admin-init-content-row').remove();
		$.ajax({
			type: "POST",
			url: progressally_settings_object.ajax_url,
			data: data,
			success: function(response) {
				process_note_retrieval_result(response, '#progressally-admin-init-max-page', '#progressally-admin-init-message-container', 'Retrieving user list');
			}
		});
	}
	$(document).on('touchend click', '[progressally-admin-init-action]', function() {
		var $this = $(this),
			change = $this.attr('progressally-admin-init-action'),
			max = parseInt($('#progressally-admin-init-max-page').text()),
			current = parseInt(progressally_admin_init_current_page.val());
		if (change === 'first') {
			progressally_admin_init_current_page.val('1');
		} else if (change === '1') {
			progressally_admin_init_current_page.val(Math.min(current + 1, max));
		} else if (change === '-1') {
			progressally_admin_init_current_page.val(Math.max(current - 1, 1));
		} else if (change === 'last') {
			progressally_admin_init_current_page.val(max);
		}
		update_admin_init_note_user_list();
	});
	// stop Enter key in the note reply section from submitting the form
	$(document).on('keypress', 'input.progressally-admin-init-reply', function(e) {
		if(e.keyCode == 13){
			update_admin_init_note_user_list();
			e.preventDefault();
			return false;
		}
	});
	/* --------------- admin init user retrieval --------------- */
	
	// <editor-fold defaultstate="collapsed" desc="admin init notes editing">
	var num_admin_init_notes_current_editing = 0,
		is_admin_init_display_originally_hidden = {};
	$(document).on('click touchend', '[progressally-admin-init-update]', function(e) {
		var $this = $(this),
			user_id = $this.attr('progressally-admin-init-update'),
			$display_element = $('[progressally-admin-init-reply-display="' + user_id + '"]'),
			$input = $('[progressally-admin-init-input="' + user_id + '"]');
		is_admin_init_display_originally_hidden[user_id] = $display_element.is(':hidden');
		$this.hide();
		$display_element.hide();
		$('[progressally-admin-init-input-container="' + user_id + '"]').show();

		$input.show().focus().select();
		++num_admin_init_notes_current_editing;
	});
	$(document).on('click touchend', '[progressally-admin-init-save]', function(e) {
		var user_id = $(this).attr('progressally-admin-init-save');
		commit_admin_init_note_edit(user_id);
	});
	$(document).on('click touchend', '[progressally-admin-init-cancel]', function(e) {
		var user_id = $(this).attr('progressally-admin-init-cancel');
		cancel_admin_init_note_edit(user_id);
	});
	$(document).on('keydown', '[progressally-admin-init-input]', function(e) {
		if(e.keyCode == 27){
			e.preventDefault();
			var $this = $(this),
				user_id = $this.attr('progressally-admin-init-input');
			cancel_admin_init_note_edit(user_id);
			$this.blur();
			return false;
		}
	});
	function cancel_admin_init_note_edit(user_id) {
		--num_admin_init_notes_current_editing;
		$('[progressally-admin-init-update="' + user_id + '"]').show();
		if (user_id in is_admin_init_display_originally_hidden && !is_admin_init_display_originally_hidden[user_id]) {
			$('[progressally-admin-init-reply-display="' + user_id + '"]').show();
		}
		$('[progressally-admin-init-input-container="' + user_id + '"]').hide();
	}
	function commit_admin_init_note_edit(user_id) {
		var $input_elem = $('[progressally-admin-init-input="' + user_id + '"]'),
			val = $input_elem.val(),
			post_id = $admin_init_note_input_post_id.val(),
			note_id = $admin_init_note_input_note_id.val(),
			user_id = $input_elem.attr('user-id'),
			format = $('[progressally-admin-init-format="' + user_id + '"]').is(':checked') ? 'html' : 'text',
			ordinal = $input_elem.attr('ordinal'),
			$files_to_upload = $('[progress-note-attachment-file="progressally-admin-init-' + user_id + '-' + ordinal + '"]'),
			existing_attachments = collect_existing_attachment_info('admin-init', ordinal, ordinal),
			valid_files = filter_valid_files_to_upload($files_to_upload);
		$('#progressally-wait-admin-init-' + user_id).show();
		if (val.length <= 0 && valid_files.length > 0) {
			val = ' ';	// assign dummy value when there are valid attachments, so the note is not consider as empty and be deleted.
		}
		var data = {
				action: 'progressally_admin_init_notes_update',
				val: val,
				pid: post_id,
				nid: note_id,
				uid: user_id,
				format: format,
				ord: ordinal,
				att: existing_attachments.join(','),
				nonce: progressally_settings_object.update_nonce
			};

		$.ajax({
			type: "POST",
			url: progressally_settings_object.ajax_url,
			data: data,
			success: function(response) {
				try {
					process_admin_init_notes_update_result(response, user_id, ordinal, valid_files);
				} catch (e) {
					alert(e);
					$('#progressally-wait-admin-init-' + user_id).hide();
				}
			}
		});
		return false;
	}
	function process_admin_init_notes_update_result(response, user_id, ordinal, valid_files) {
		var result = JSON.parse(response);
		if ('status' in result && 'id' in result) {
			if (result['status'] === 'error') {
				throw result['message'];
			}
			if (valid_files.length > 0) {	// no file to upload
				add_note_attachment_files(result['id'], ordinal, user_id, 0, valid_files, 'admin-init-' + user_id);
			} else {
				// don't need to hide the wait overlay because the entire code block is replaced
				process_update_note_display_code(result, 'admin-init-' + user_id);	// only update the code if there is no file to upload
				--num_admin_init_notes_current_editing;
			}
		} else {
			throw 'Invalid response. Please refresh the page and try again.';
		}
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="gather existing attachment info on note update">
	function collect_existing_attachment_info(note_type, row_id, ordinal) {
		var result = [],
			target_attribute = 'progressally-notes-existing-attachment-' + note_type + '-' + row_id + '-' + ordinal,
			$existing_attachments = $('[' + target_attribute + ']'),
			i = 0, $elem;
		for (; i < $existing_attachments.length; ++i) {
			$elem = $($existing_attachments[i]);
			result.push($elem.attr(target_attribute));
		}
		return result;
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="Filter out empty file input">
	function filter_valid_files_to_upload($files_to_upload) {
		var result = [],
			i = 0;
		for (; i < $files_to_upload.length; ++i) {
			if ($files_to_upload[i].files.length > 0) {
				result.push($files_to_upload[i].files[0]);
			}
		}
		return result;
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="Replace old note display with new code">
	function process_update_note_display_code(result, display_postfix) {
		if (!('code' in result)) {
			throw 'Unable to update note display';
		}
		var $target = $('#progressally-view-block-' + display_postfix);
		$target.after(result['code']);
		$target.remove();
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="Upload attachment file on save">
	function add_note_attachment_files(row_id, ordinal, user_id, progress_index, valid_files, display_postfix) {
		if (progress_index >= valid_files.length) {
			return;
		}
		var file_to_upload = valid_files[progress_index],
			slice_method = 'slice';
		if ('mozSlice' in file_to_upload) {
			slice_method = 'mozSlice';
		} else if ('webkitSlice' in file_to_upload) {
			slice_method = 'webkitSlice';
		}
		ajax_upload_by_slice(0, file_to_upload, slice_method, row_id, ordinal, user_id, -1, 0, display_postfix, function(result) {
			if (progress_index === valid_files.length - 1) {	// last file uploaded
				process_update_note_display_code(result, display_postfix);	// don't need to hide the wait overlay because the entire code block is replaced

				if (display_postfix.indexOf('note-reply') === 0) {
					--num_notes_current_editing;
				} else if (display_postfix.indexOf('admin-init') === 0) {
					--num_admin_init_notes_current_editing;
				}
			} else {
				add_note_attachment_files(row_id, ordinal, user_id, progress_index + 1, valid_files, display_postfix);
			}
		});
	}
	var SLICE_SIZE = 102400;
	function ajax_upload_by_slice(slice_index, file, slice_method, row_id, ordinal, user_id, attachment_id, num_retry, display_postfix, success_function) {
		var start = slice_index * SLICE_SIZE,
			end = start + SLICE_SIZE,
			file_size = file.size;
		if (end > file_size) {
			end = file_size;
		}
		var content = file[slice_method](start, end),
			data = new FormData();
		data.append('action', 'progressally_admin_notes_reply_add_attachment');
		data.append('nonce', progressally_settings_object.update_nonce);
		data.append('index', slice_index);
		data.append('file_name', file.name);
		data.append('content', content);
		data.append('rid', row_id);
		data.append('ord', ordinal);
		data.append('uid', user_id);
		data.append('nonce', progressally_settings_object.update_nonce);

		if (attachment_id) {
			data.append('aid', attachment_id);
		}

		$.ajax({
			type: "POST",
			url: progressally_settings_object.ajax_url,
			data: data,
			cache: false,
			contentType: false,
			processData: false,
			success: function(response) {
				process_add_attachment_file_success(response, end, slice_index, file, slice_method, file_size, row_id, ordinal, user_id, attachment_id, num_retry, display_postfix, success_function);
			}
		});
	}
	function process_add_attachment_file_success(response, end, slice_index, file, slice_method, file_size, row_id, ordinal, user_id, attachment_id, num_retry, display_postfix, success_function) {
		try {
			var result = JSON.parse(response);
			if ('status' in result) {
				if (result['status'] === 'retry') {
					num_retry += 1;
					if (num_retry > 3) {
						throw "Upload failed 3 times";
					}
					ajax_upload_by_slice(slice_index, file, slice_method, row_id, ordinal, user_id, attachment_id, num_retry, display_postfix, success_function);
					return;
				} else if (result['status'] !== 'success') {
					throw result['message'];
				}
			} else {
				throw "Unable to connect to server";
			}
			if (end < file_size) {
				ajax_upload_by_slice(slice_index + 1, file, slice_method, row_id, ordinal, user_id, result['aid'], 0, display_postfix, success_function);
			} else {
				success_function(result);
			}
		} catch (e) {
			alert("Cannot upload file due to error:\n[" + e + "]\nPlease refresh the page and try again.");

			$('#progressally-wait-' + display_postfix).hide();
		}
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="Add new attachment file select for private note">
	$(document).on('click touchend', '[progressally-notes-attachment-add]', function() {
		var target = $(this).attr('progressally-notes-attachment-add');
		$('[progressally-notes-attachment-container="' + target + '"]').append('<input class="progress-note-attachment-file-input" type="file" progress-note-attachment-file="' + target + '" />');
	});
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="Do not allow PHP files to be uploaded for private note attachment">
	var FILE_MAX_SIZE = 10485760;
	$(document).on('change', '[progress-note-attachment-file]', function() {
		if (this.files.length > 0) {
			var name = this.files[0].name.toLowerCase()
			if (name.substring(name.length - 4) === '.php') {
				alert('PHP files cannot be uploaded');
				$(this).val('');
			}
			if (this.files[0].size > FILE_MAX_SIZE){
				alert('The attachment file cannot exceed 10MB in size.');
				$(this).val('');
			}
		}
	});
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="Delete existing attachment">
	$(document).on('click touchend', '[progressally-notes-attachment-delete]', function() {
		var target = $(this).attr('progressally-notes-attachment-delete');
		$('#progressally-note-attachment-' + target).remove();
	});
	// </editor-fold>
});