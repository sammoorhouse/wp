<?php
if (!class_exists('ProgressAllyNotesShortcode')) {
	class ProgressAllyNotesShortcode {
		public static function add_shortcodes() {
			add_shortcode( 'progressally_note', array(__CLASS__, 'shortcode_note'));
		}
		private static $note_ordinal = 0;
		public static function shortcode_note($atts) {
			extract(shortcode_atts(array(
				'prefix' => '',
				'size' => '8',
				'post_id' => '',
				'note_id' => '',
				'button_text' => '',
				'save_text' => 'Save',
				'cancel_text' => 'Cancel',
				'add_attachment_text' => '+ add attachment',
				'allow_attachment' => 'no',
				'user_id' => '',
				'request' => '',
				'context' => 'local'
			), $atts, 'progressally_note' ) );
			$note_id = intval($note_id);
			if (empty($note_id)) {
				return 'Invalid Private Note ID';
			}
			++self::$note_ordinal;
			$current_user_id = ProgressAllyUserProgress::get_user_id();
			$display_user_id = ProgressAllyBackendShared::get_current_user_id_for_display($user_id, $request, $context, $current_user_id);

			$post_id = intval($post_id);
			$post_id = $post_id <= 0 ? get_the_ID(): $post_id;

			$note_value = ProgressAllyNote::get_user_notes($post_id, $note_id, $display_user_id);

			$static_attributes = array(
				'prefix' => $prefix,
				'note-ordinal' => self::$note_ordinal,
				'post-id' => $post_id,
				'note-id' => $note_id,
				'button-text' => $button_text,
				'save-text' => $save_text,
				'cancel-text' => $cancel_text,
				'allow-attachment' => $allow_attachment,
				'add-attachment-text' => $add_attachment_text,
				'size' => $size,
				);

			$can_edit = intval($current_user_id) === intval($display_user_id);
			try {
				$code = self::generate_frontend_note_code($post_id, $note_id, $display_user_id, $note_value, $static_attributes, $can_edit);
				return $code;
			} catch (Exception $e) {
			}
			return '';
		}

		const HEADER_TEMPLATE = '<div class="{{prefix}}progressally-notes-update-label">{{label}}</div>';

		const DISPLAY_TEMPLATE = '<div class="{{prefix}}progressally-notes-display progressally-notes-display-{{ordinal}} progressally-notes-display-{{author}}">{{value}}{{attachment}}</div>';

		// <editor-fold defaultstate="collapsed" desc="Generate attachment display code">
		private static $cached_attachment_display_template = false;
		private static function get_attachment_display_template() {
			if (false === self::$cached_attachment_display_template) {
				self::$cached_attachment_display_template = file_get_contents(dirname(__FILE__) . '/notes-attachment-display-template.php');
			}
			return self::$cached_attachment_display_template;
		}
		private static $cached_attachment_with_delete_template = false;
		private static function get_attachment_with_delete_template() {
			if (false === self::$cached_attachment_with_delete_template) {
				self::$cached_attachment_with_delete_template = file_get_contents(dirname(__FILE__) . '/notes-attachment-with-delete-template.php');
			}
			return self::$cached_attachment_with_delete_template;
		}
		private static function generate_attachment_display_code($post_id, $note_id, $ordinal, $note_value, $template) {
			$code = '';
			if (isset($note_value['att']) && is_array($note_value['att'])) {
				foreach ($note_value['att'] as $attachment_id => $attachment) {
					$note_code = $template;
					$note_code = str_replace('{{attachment-path}}', esc_attr(ProgressAllyNotesAttachment::get_attachment_url($post_id, $note_id, $ordinal, $attachment_id)), $note_code);
					$note_code = str_replace('{{attachment-name}}', esc_attr($attachment['name']), $note_code);
					$note_code = str_replace('{{attachment-id}}', $attachment_id, $note_code);
					$code .= $note_code;
				}
			}
			return $code;
		}
		// </editor-fold>

		// <editor-fold defaultstate="collapsed" desc="Generate individual note code">
		private static $cached_writeable_note_template = false;
		private static function get_writeable_note_template() {
			if (false === self::$cached_writeable_note_template) {
				self::$cached_writeable_note_template = file_get_contents(dirname(__FILE__) . '/notes-writable-entry-template.php');
			}
			return self::$cached_writeable_note_template;
		}
		private static $cached_add_attachment_template = false;
		private static function get_add_attachment_template() {
			if (false === self::$cached_add_attachment_template) {
				self::$cached_add_attachment_template = file_get_contents(dirname(__FILE__) . '/notes-add-attachment-template.php');
			}
			return self::$cached_add_attachment_template;
		}
		private static function generate_individual_note_code($note_value, $post_id, $note_id, $ordinal, $author_iden, $editable, $allow_upload_attachment, $placeholder) {
			$editable_index = -1;
			$note_code = self::DISPLAY_TEMPLATE;
			if ($editable) {
				$note_code = self::get_writeable_note_template();

				if ($allow_upload_attachment) {
					$note_code = str_replace('{{add-attachment}}', self::get_add_attachment_template(), $note_code);
				} else {
					$note_code = str_replace('{{add-attachment}}', '', $note_code);
				}
			}

			$frontend_val = '';
			if (empty($note_value['v']) && $editable) {
				$note_code = str_replace('{{placeholder-status}}', 'show', $note_code);
				$frontend_val = esc_html($placeholder);
			} else {
				$note_code = str_replace('{{placeholder-status}}', 'hide', $note_code);
				$frontend_val = esc_attr($note_value['v']);
				if (isset($note_value['f']) && $note_value['f'] === 'html') {
					$frontend_val = $note_value['v'];
					// render shortcode only for admin messages
					if ('admin' === $author_iden) {
						$frontend_val = do_shortcode($frontend_val);
					}
				} else {
					$frontend_val = esc_attr($note_value['v']);
					$frontend_val = nl2br($frontend_val);
				}
			}
			$note_code = str_replace('{{value}}', $frontend_val, $note_code);
			$note_code = str_replace('{{raw-value}}', esc_attr($note_value['v']), $note_code);
			$note_code = str_replace('{{attachment}}', self::generate_attachment_display_code($post_id, $note_id, $ordinal, $note_value, self::get_attachment_display_template()), $note_code);
			$note_code = str_replace('{{attachment-with-delete}}', self::generate_attachment_display_code($post_id, $note_id, $ordinal, $note_value, self::get_attachment_with_delete_template()), $note_code);

			$note_code = str_replace('{{ordinal}}', $ordinal, $note_code);
			$note_code = str_replace('{{author}}', $author_iden, $note_code);
			return $note_code;
		}
		// </editor-fold>

		// <editor-fold defaultstate="collapsed" desc="Generate frontend note code">
		private static $frontend_static_attributes = array(
			'prefix',
			'note-ordinal',
			'post-id',
			'note-id',
			'button-text',
			'save-text',
			'cancel-text',
			'add-attachment-text',
			'size',
			);
		private static $default_frontend_parameters = array('allow-attachment' => 'no');
		private static function parse_serialized_frontend_param($param_string) {
			$param_string = stripslashes($param_string);
			$nonce_index = strrpos($param_string, '{nonce=');
			if ($nonce_index <= 0) {
				return self::$default_frontend_parameters;
			}
			$serialized_param = substr($param_string, 0, $nonce_index);
			$nonce = substr($param_string, $nonce_index + strlen('{nonce='));
			$nonce = substr($nonce, 0, strlen($nonce) - 1);	// remove the "}"
			if (!wp_verify_nonce($nonce, $serialized_param)) {
				return self::$default_frontend_parameters;
			}
			$param = unserialize($serialized_param);
			if (!is_array($param) || empty($param)) {
				return self::$default_frontend_parameters;
			}
			return wp_parse_args($param, self::$default_frontend_parameters);
		}
		public static function parse_ajax_serialized_frontend_param($param_string) {
			$static_attributes = self::parse_serialized_frontend_param($param_string);
			foreach (self::$frontend_static_attributes as $key) {
				$static_attributes[$key] = '--' . $key . '--';
			}
			return $static_attributes;
		}
		private static function extract_frontend_param($static_attributes) {
			$result = array();
			foreach (self::$default_frontend_parameters as $key => $default_value) {
				if (isset($static_attributes[$key])) {
					$result[$key] = $static_attributes[$key];
				} else {
					$result[$key] = $default_value;
				}
			}
			return $result;
		}
		public static function generate_frontend_note_code($post_id, $note_id, $user_id, $note_value, $static_attributes, $can_edit) {
			$note_meta = ProgressAllyNote::get_note_meta($post_id, $note_id);
			if (!is_array($note_meta)) {
				throw new Exception('Invalid Private Note');
			}

			$serialized_param = serialize(self::extract_frontend_param($static_attributes));
			$nonce = wp_create_nonce($serialized_param);

			$note_ordinal = $static_attributes['note-ordinal'];
			$code_preamble = '<div class="{{prefix}}progressally-note-block" progressally-private-note-p' . $post_id . 't-n' . $note_id . 'e="' . $note_ordinal . '" ' .
				'id="progressally-private-note-' . $note_ordinal . '" ' .
				'progressally-note-param="' . esc_attr($serialized_param . '{nonce=' . $nonce . '}') . '" ' .
				'progressally-note-customize="' . esc_attr(json_encode($static_attributes)) . '">';

			$static_attributes['post-id'] = $post_id;
			$static_attributes['note-id'] = $note_id;
			$static_attributes['label'] = $note_meta['title'];
			$static_attributes['placeholder'] = $note_meta['placeholder'];
			$static_attributes['plugin-uri'] = ProgressAlly::$PLUGIN_URI;

			$max_allowed_user_notes = ProgressAllyNote::get_max_allowed_user_notes($note_meta);
			
			$allow_attachment = ($user_id > 0) && ('yes' === $static_attributes['allow-attachment']) && ProgressAllyNotesAttachment::is_attachment_allowed();

			$code = self::generate_note_code($post_id, $note_id, $note_value, $max_allowed_user_notes, $allow_attachment, $static_attributes, $can_edit);

			$code = $code_preamble . $code . '</div>';

			$code = str_replace('{{prefix}}', $static_attributes['prefix'], $code);
			return $code;
		}
		private static function generate_note_code($post_id, $note_id, $note_value, $max_allowed_user_notes, $allow_attachment, $static_attributes, $can_edit) {
			$display_code = '';
			$count = count($note_value);
			$editable_index = -1;
			$num_user_notes = 0;
			
			if ($count > 0) {
				for ($i = 0; $i < $count; ++$i) {
					$author_iden = 'admin';
					$editable = false;
					if ($note_value[$i]['s'] == 0) {
						$author_iden = 'user';
						++$num_user_notes;
						if ($can_edit && $i === $count - 1 && ($max_allowed_user_notes < 0 || $num_user_notes <= $max_allowed_user_notes)) {	// last entry, so updates will be made to this entry
							$editable = true;
							$editable_index = $count - 1;
						}
					}
					$temp_note_code = self::generate_individual_note_code($note_value[$i], $post_id, $note_id, $i, $author_iden, $editable, $allow_attachment, $static_attributes['placeholder']);

					$display_code .= $temp_note_code;
				}
			}
			// add new editable block of the last entry in existing note is not made by the current user
			if ($can_edit && $editable_index < 0 && ($max_allowed_user_notes < 0 || $num_user_notes < $max_allowed_user_notes)) {
				$temp_note_code = self::generate_individual_note_code(array('v' => '', 'f' => 'text'), $post_id, $note_id, $count, 'user', true, $allow_attachment, $static_attributes['placeholder']);
				$display_code .= $temp_note_code;
			}

			// add the header section
			$code = self::HEADER_TEMPLATE . $display_code;

			foreach ($static_attributes as $key => $value) {
				if ('label' === $key) {
					$code = str_replace('{{' . $key . '}}', $value, $code);
				} else {
					$code = str_replace('{{' . $key . '}}', esc_html($value), $code);
				}
			}
			return $code;
		}
		// </editor-fold>

		public static function preview_notes() {
			$note_value = array(
				0 => array('a' => 0, 'v' => 'User note', 's' => 0, 't' => 0, 'f' => 'text'),
				1 => array('a' => 0, 'v' => 'Admin note', 's' => 1, 't' => 0, 'f' => 'text'),
			);
			$post_id = 'demo-post';
			$note_id = 'demo-note';
			$static_attributes = array(
				'label' => 'Note title',
				'note-ordinal' => '0',
				'post-id' => $post_id,
				'note-id' => $note_id,
				'placeholder' => 'Click here to enter your note',
				'button-text' => '',
				'save-text' => 'Save',
				'cancel-text' => 'Cancel',
				'add-attachment-text' => '+ add attachment',
				'size' => 8,
				'plugin-uri' => ProgressAlly::$PLUGIN_URI,
				);
			$preview_code = self::generate_note_code($post_id, $note_id, $note_value, -1, true, $static_attributes, true);

			$preview_code = str_replace('{{prefix}}', '', $preview_code);
			return $preview_code;
		}
	}
}