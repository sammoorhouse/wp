var progressally_insert;
(function($) {
	var editor,
		inputs = {};
		
	function cleanNameString(inp) {
		inp = inp.replace(/'/g, '').replace(/"/g, '');
		return inp;
	}
	progressally_insert = {
		init: function() {
			inputs.wrap = $('#progressally-insert-wrap');
			inputs.type_selection = $('#progressally-insert-type-select'),
			inputs.input_form = $('#progressally-insert-form'),
			inputs.submit = $('#progressally-insert-submit');
			inputs.backdrop = $( '#progressally-insert-backdrop' );
			inputs.close = $('#progressally-insert-close');

			inputs.close.add($('#progressally-insert-cancel')).add(inputs.backdrop).on('click touchend', function(event) {
				event.preventDefault();
				progressally_insert.close();
			});;
			inputs.submit.click(function(event) {
				event.preventDefault();
				progressally_insert.update();
			});
		},
		open: function(editorId) {
			var ed,
				$body = $(document.body);

			$body.addClass('modal-open');

			this.textarea = $( '#' + window.wpActiveEditor ).get( 0 );
			if ( typeof tinymce !== 'undefined' ) {
				// Make sure the link wrapper is the last element in the body,
				// or the inline editor toolbar may show above the backdrop.
				$body.append(inputs.backdrop, inputs.wrap);
				ed = tinymce.get(editorId);

				if ( ed && ! ed.isHidden() ) {
					editor = ed;
				} else {
					editor = null;
				}

				if ( editor && tinymce.isIE ) {
					editor.windowManager.bookmark = editor.selection.getBookmark();
				}
			}

			if (!progressally_insert.isMCE() && document.selection ) {
				this.textarea.focus();
				this.range = document.selection.createRange();
			}
			// always select the first option
			inputs.type_selection.val(inputs.type_selection.find('option:first').val()).change();
			// clear existing tags
			inputs.input_form.find('.progressally-tag').remove();
			// clear objective checkboxes
			inputs.input_form.find('[progressally-mce-complete-button-objective-checkbox]').prop('checked', false);

			inputs.wrap.show();
			inputs.backdrop.show();
		},
		close: function() {
			$(document.body).removeClass('modal-open');

			inputs.backdrop.hide();
			inputs.wrap.hide();
		},

		isMCE: function() {
			return editor && ! editor.isHidden();
		},

		update: function() {
			if (progressally_insert.isMCE()) {
				progressally_insert.mceUpdate();
			} else {
				progressally_insert.htmlUpdate();
			}
		},
		
		getShortcode: function() {
			var video_type, video_id, video_progress_id, width, height, percentage,
					progress_type, progress_post_id, diameter, bar_width, bar_width_postfix, bar_height, share_id, note_id, temp_text, temp_attr, temp_ids,
					selected_type = inputs.type_selection.val();
			switch (selected_type) {
				case 'objective-list':
					temp_attr = $('#progressally-mce-objective-list-post-id').val();
					if (temp_attr && temp_attr !== '0') {
						temp_attr = ' post_id="' + temp_attr + '"';
					} else {
						temp_attr = '';
						if ($('#progressally-mce-objective-list-partial').is(':checked')) {
							var objective_ids = $('[progressally-mce-objective-list-checkbox]:checked'), i;
							temp_ids = [];
							for (i = 0; i < objective_ids.length; ++i) {
								temp_ids.push($(objective_ids[i]).attr('progressally-mce-objective-list-checkbox'));
							}
							temp_attr += ' objective_id="' + temp_ids.join(',') + '"';
						}
					}

					return ["[progressally_objectives" + temp_attr + "]", ''];
				case 'objective-completion':
					percentage = $('#progressally-mce-objective-completion-percentage').val();
					return ['[progressally_objective_completion percentage="' + percentage + '"]', '[/progressally_objective_completion]'];
				case 'quiz':
					return ["[progressally_quiz]", ''];
				case 'progress':
					progress_type = $('#progressally-progress-type-select').val();
					progress_post_id = $('#progressally-post-id').val();
					progress_post_id = (progress_post_id && progress_post_id !== '0') ?  ' post_id="' + progress_post_id + '"' : '';
					switch (progress_type) {
						case 'text':
							return ["[progressally_progress_text" + progress_post_id + "]", ''];
						case 'pie-chart':
							diameter = $('#progressally-pie-chart-size').val();
							diameter = ' size="' + diameter + '"';
							return ["[progressally_progress_pie_chart" + progress_post_id + diameter + "]", ''];
						case 'bar':
							bar_width = $('#progressally-bar-width').val();
							if (bar_width !== '') {
								bar_width_postfix = $('#progressally-bar-width-postfix').val();
								bar_width = ' width="' + bar_width + bar_width_postfix + '"';
							}
							bar_height = $('#progressally-bar-height').val();
							bar_height = bar_height === '' ? '' : ' height="' + bar_height + '"';
							return ["[progressally_progress_bar" + progress_post_id + bar_width + bar_height + "]", ''];
						case 'objective-count':
							return ["[progressally_objective_count" + progress_post_id + "]", ''];
						case 'objective-completed-count':
							return ["[progressally_objective_completed_count" + progress_post_id + "]", ''];
					}
					break;
				case 'video':
					video_type = $('#progressally-post-video-type-select').val();
					video_id = $('#progressally-post-video-id').val();
					video_progress_id = $('#progressally-post-video-progress-id').val();
					width = $('#progressally-post-video-width').val();
					height = $('#progressally-post-video-height').val();
					temp_attr = $('#progressally-post-video-hide-control').is(':checked');
					temp_text = '';

					if (temp_attr) {
						if ('youtube' === video_type) {
							temp_text = "additional_args='rel=0&showinfo=0&controls=0'";
						} else if ('wistia' === video_type) {
							temp_text = "additional_args='playbar=0'";
						}
					}
					return ["[progressally_" + video_type + "_video id='" + video_progress_id + "' " + video_type + "_id='" + video_id + "' width='" + width + "' height='" + height + "' " + temp_text + "]", ''];
				case 'social-share':
					progress_type = $('#progressally-social-share-select').val();
					share_id = $('#progressally-mce-editor-share-id-select').val();
					return ["[progressally_social_share type='" + progress_type + "' share_id='" + share_id + "']", "[/progressally_social_share]"];
				case 'note':
					note_id = $('#progressally-mce-editor-note-id-select').val();
					temp_attr = $('#progressally-mce-notes-allow-attachment').is(':checked');
					return ["[progressally_note note_id='" + note_id + "' allow_attachment='" + (temp_attr ? 'yes' : 'no') + "']", ""];
				case 'certificate':
					temp_text = '';
					progress_post_id = $('#progressally-mce-certificate-post-id').val();
					if (parseInt(progress_post_id) > 0) {
						temp_text += ' post_id="' + progress_post_id + '"';
						temp_text += ' certificate_id="' + $('#progressally-mce-editor-certificate-id-text').val() + '"';
					} else {
						temp_text += ' certificate_id="' + $('#progressally-mce-editor-certificate-id-select').val() + '"';
					}
					progress_type = $('#progressally-mce-certificate-type-select').val();
					if (progress_type === 'url') {
						temp_text += ' link="yes"';
					} else {
						temp_text += ' text="' + $('#progressally-mce-certificate-text').val() + '"';
						temp_attr = $('#progressally-mce-certificate-custom-class').val();
						if (temp_attr) {
							temp_text += ' class="' + temp_attr + '"';
						}
					}
					return ["[progressally_certificate" + temp_text + "]", ""];
				case 'complete-button':
					var objective_ids = $('[progressally-mce-complete-button-objective-checkbox]:checked'), i;
					temp_text = $('#progressally-mce-complete-button-text').val(),
					progress_type = $('#progressally-mce-complete-button-type').val();
					if ('all' === progress_type) {
						temp_attr = 'all'
					} else {
						temp_attr = [];
						for (i = 0; i < objective_ids.length; ++i) {
							temp_attr.push($(objective_ids[i]).attr('progressally-mce-complete-button-objective-checkbox'));
						}
						temp_attr = temp_attr.join(',');
					}
					return ["[progressally_complete_button text='" + temp_text + "' objective_id='" + temp_attr + "']", ""];
			}
			return ['', ''];
		},

		getTagList: function() {
			var tag_list = inputs.input_form.find('input[name="tag-id[]"]'),
				tags = '', i = 0;
			for (; i < tag_list.length; ++i) {
				tags += tag_list[i].value + ',';
			}
			tags = tags.substr(0, tags.length - 1);
			return tags;
		},

		getTagNameList: function() {
			var tag_list = inputs.input_form.find('div.progressally-tag-name'),
				tags = '', i = 0;
			for (; i < tag_list.length; ++i) {
				tags += cleanNameString(tag_list[i].innerHTML) + ',';
			}
			tags = tags.substr(0, tags.length - 1);
			return tags;
		},

		getCustomFieldOperationId: function() {
			return inputs.input_form.find('#progressally-post-field-operation-select').val();
		},

		getCheckboxInput: function(element_name) {
			return inputs.input_form.find(element_name).is(':checked') ? 'yes' : 'no';
		},

		mceUpdate: function() {
			var shortcode, text;
			progressally_insert.close();
			editor.focus();

			if ( tinymce.isIE ) {
				editor.selection.moveToBookmark( editor.windowManager.bookmark );
			}
			shortcode = progressally_insert.getShortcode();
			text = editor.selection.getContent();
			editor.selection.setContent(shortcode[0] + text + shortcode[1]);
			editor.nodeChanged();
		},
		htmlUpdate: function() {
			var shortcode, begin, end, selection, html, cursor,
				textarea = progressally_insert.textarea;
			shortcode = progressally_insert.getShortcode();
			// Insert HTML
			if (document.selection && progressally_insert.range) {
				// IE
				// Note: If no text is selected, IE will not place the cursor
				//       inside the closing tag.
				textarea.focus();
				progressally_insert.range.text = shortcode[0] + progressally_insert.range.text + shortcode[1];
				progressally_insert.range.moveToBookmark(progressally_insert.range.getBookmark());
				progressally_insert.range.select();

				progressally_insert.range = null;
			} else if ( typeof textarea.selectionStart !== 'undefined' ) {
				// W3C
				begin = textarea.selectionStart;
				end = textarea.selectionEnd;
				selection = textarea.value.substring( begin, end );
				html = shortcode[0] + selection + shortcode[1];
				cursor = begin + html.length;

				// If no text is selected, place the cursor inside the closing tag.
				if (begin === end && !selection) {
					cursor -= shortcode[1].length;
				}

				textarea.value = (
					textarea.value.substring( 0, begin ) +
					html +
					textarea.value.substring( end, textarea.value.length )
				);

				// Update cursor position
				textarea.selectionStart = textarea.selectionEnd = cursor;
			}
			textarea.focus();
			progressally_insert.close();
		},
	};

	$(document).ready(function() {
		var all_additional_inputs = $('[progressally-insert-additional-input]');
		progressally_insert.init();
		$('#progressally-insert-type-select').on('change', function() {
			var $this = $(this),
				selected_option = $this.find('option:selected'),
				additional_input = selected_option.attr('additional-input');
			all_additional_inputs.hide();
			if (typeof additional_input !== typeof undefined && additional_input !== false) {
				all_additional_inputs.filter('[progressally-insert-additional-input="' + additional_input + '"]').show();
			}
		});
	});
})(jQuery);
function progressally_insert_callback(e, c, ed, defaultValue) {
	progressally_insert.open(ed.id);
}