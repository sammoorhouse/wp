<?php
class ProgressAllySettingNotesShared {
	// <editor-fold defaultstate="collapsed" desc="generate note code with all the entries">
	public static function generate_individual_user_note_display($template_type, $post_id, $note_id, $user_id, $user_values) {
		$code = '';
		$count = count($user_values);
		$editable_index = -1;
		if ($count > 0) {
			for ($i = 0; $i < $count; ++$i) {
				$author_iden = 'user';
				$editable = false;
				if ($user_values[$i]['s'] == 1) {
					$author_iden = 'admin';
					if ($i === $count - 1) {	// last entry, so updates will be made to this entry
						$editable_index = $count - 1;
						$editable = true;
					}
				}
				$temp_note_code = self::generate_individual_note_entry($template_type, $post_id, $note_id, $user_id, $user_values[$i], $i, $author_iden, $editable, false);
				$code .= $temp_note_code;
			}
		}
			// add new editable block of the last entry in existing note is not made by the current user
		if ($editable_index < 0) {
			$temp_note_code = self::generate_individual_note_entry($template_type, $post_id, $note_id, $user_id, array('v' => '', 'f' => 'text'), $count, 'admin', true, true);
			$code .= $temp_note_code;
		}

		$code = str_replace('{{post-id}}', $post_id, $code);
		$code = str_replace('{{note-id}}', $note_id, $code);
		$code = str_replace('{{user-id}}', $user_id, $code);
		return $code;
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="generate each note entry">
	private static $cached_display_template = array();
	private static function get_display_template($template_file) {
		if (!isset(self::$cached_display_template[$template_file])) {
			self::$cached_display_template[$template_file] = file_get_contents(dirname(__FILE__) . '/' . $template_file . '.php');
		}
		return self::$cached_display_template[$template_file];
	}
	private static function generate_individual_note_entry($template_type, $post_id, $note_id, $user_id, $note_value, $ordinal, $author_iden, $editable, $is_new_entry) {
		$note_code = self::get_display_template($template_type . '-display-template');
		if ($editable) {
			$note_code = self::get_display_template($template_type . '-writable-template');
			if ($is_new_entry) {
				$note_code = str_replace('{{display-status}}', 'style="display:none"', $note_code);
			} else {
				$note_code = str_replace('{{display-status}}', '', $note_code);
			}
		}

		$esc_val = esc_attr($note_value['v']);
		$note_code = str_replace('{{raw-value}}', $esc_val, $note_code);
		if (isset($note_value['f']) && 'html' === $note_value['f']) {
			$note_code = str_replace('{{value}}', $note_value['v'], $note_code);
			$note_code = str_replace('{{format-checked}}', 'checked="checked"', $note_code);
		} else {
			$note_code = str_replace('{{value}}', nl2br($esc_val), $note_code);
			$note_code = str_replace('{{format-checked}}', '', $note_code);
		}

		$allow_attachment = ProgressAllyNotesAttachment::is_attachment_allowed();
		if ($allow_attachment) {
			$note_code = str_replace('{{allow-add-attachment}}', '', $note_code);
		} else {
			$note_code = str_replace('{{allow-add-attachment}}', 'style="display:none"', $note_code);
		}
		$note_code = str_replace('{{attachment}}', self::generate_attachment_display_code($template_type, $post_id, $note_id, $user_id, $ordinal, $note_value, 'notes-attachment-display-template'), $note_code);
		$note_code = str_replace('{{attachment-with-delete}}', self::generate_attachment_display_code($template_type, $post_id, $note_id, $user_id, $ordinal, $note_value, 'notes-attachment-with-delete-template'), $note_code);

		$note_code = str_replace('{{ordinal}}', $ordinal, $note_code);
		$note_code = str_replace('{{author}}', $author_iden, $note_code);
		return $note_code;
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="generate attachment display">
	private static function generate_attachment_display_code($template_type, $post_id, $note_id, $user_id, $ordinal, $note_value, $template_file) {
		$code = '';
		if (isset($note_value['att']) && is_array($note_value['att'])) {
			foreach ($note_value['att'] as $attachment_id => $attachment) {
				$note_code = self::get_display_template($template_file);
				$note_code = str_replace('{{attachment-path}}', esc_attr(ProgressAllyNotesAttachment::get_admin_attachment_url($post_id, $note_id, $user_id, $ordinal, $attachment_id)), $note_code);
				$note_code = str_replace('{{attachment-name}}', esc_attr($attachment['name']), $note_code);
				$note_code = str_replace('{{attachment-id}}', $attachment_id, $note_code);
				$code .= $note_code;
			}
		}
		$code = str_replace('{{type}}', $template_type, $code);
		return $code;
	}
	// </editor-fold>
}