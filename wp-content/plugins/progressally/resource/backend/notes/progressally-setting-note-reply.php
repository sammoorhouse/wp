<?php
class ProgressAllySettingNoteReply {
	const SETTING_KEY = '_progressally_setting_note_reply';

	public static function add_actions() {
		add_action('wp_ajax_progressally_get_note_reply', array(__CLASS__, 'get_note_reply_callback'));

		add_action('wp_ajax_progressally_admin_notes_update', array(__CLASS__, 'update_admin_notes_callback'));
		add_action('wp_ajax_progressally_admin_notes_close', array(__CLASS__, 'close_admin_notes_callback'));
		add_action('wp_ajax_progressally_admin_notes_approve', array(__CLASS__, 'approve_admin_notes_callback'));
	}

	public static function show_note_reply_settings() {
		$posts_with_notes = ProgressAllyBackendShared::get_all_post_with_notes();
		$page_selection_code = '';
		foreach ($posts_with_notes as $row) {
			$page_selection_code .= '<option value="' . $row['ID'] . '">' . esc_attr($row['post_title']) . ' (' . $row['ID'] . ')</option>';
		}
		include dirname(__FILE__) . '/progressally-setting-note-reply-display.php';
	}

	// <editor-fold defaultstate="collapsed" desc="Note view display">
	private static $cached_note_view_template = null;
	private static function get_note_view_template() {
		if (self::$cached_note_view_template === null) {
			self::$cached_note_view_template = file_get_contents(dirname(__FILE__) . '/progressally-setting-note-reply-template.php');
		}
		return self::$cached_note_view_template;
	}
	private static function generate_individual_note_display($row_id, $post_id, $note_id, $user_id, $note_setting, $note_values, $note_status, $note_approve_status, $note_time) {
		$code = self::get_note_view_template();
		$code = ProgressAllyBackendShared::replace_real_values($code, $note_setting, '');
		$code = ProgressAllyBackendShared::replace_all_toggle($code, $note_setting);

		$exist_notes = ProgressAllySettingNotesShared::generate_individual_user_note_display('note-reply', $post_id, $note_id, $user_id, $note_values);
		$code = str_replace('{{existing-notes}}', $exist_notes, $code);

		$code = str_replace('{{size}}', '8', $code);
		$code = str_replace('{{post-link}}', esc_attr(get_edit_post_link($post_id)), $code);
		$code = str_replace('{{post-name}}', esc_attr(ProgressAllyBackendShared::get_post_name($post_id)), $code);
		
		$note_display_status = self::get_note_status_display_code($note_status, $note_approve_status);
		$display_note_display_status =  empty($note_display_status) ? 'style="display:none;"' : '';
		$code = str_replace('{{note-display-status}}', $note_display_status, $code);
		$code = str_replace('{{display-note-display-status}}', $display_note_display_status, $code);
		
		$code = str_replace('{{note-id}}', $note_id, $code);
		$code = str_replace('{{note-name}}', esc_attr($note_setting['name']), $code);
		$code = str_replace('{{user-link}}', ProgressAllyBackendShared::generate_user_edit_link($user_id), $code);
		$code = str_replace('{{time}}', esc_html($note_time), $code);
		$code = str_replace('{{status-code}}', $note_status, $code);
		$code = str_replace('{{approve-status-code}}', $note_approve_status, $code);
		$code = str_replace('{{row-id}}', $row_id, $code);
		return $code;
	}
	
	private static function get_note_status_display_code($note_status, $note_approve_status) {
		$note_status = intval($note_status);
		$note_approve_status = intval($note_approve_status);
		
		$status_display_code = '';
		if ($note_approve_status === ProgressAllyNote::NOTE_APPROVE_STATUS_APPROVED) {
			$status_display_code = 'Approved';
		} elseif ($note_approve_status === ProgressAllyNote::NOTE_APPROVE_STATUS_PENDING_APPROVAL) {
			$status_display_code = 'Need to approve';
		} else {
			if ($note_status === ProgressAllyNote::NOTE_STATUS_REPLIED) {
				$status_display_code = 'Replied';
			} elseif ($note_status === ProgressAllyNote::NOTE_STATUS_UNREPLIED) {
				$status_display_code = 'Need to reply';
			}
		}
		return $status_display_code;
	}

	private static function generate_individual_user_note_display($post_id, $note_id, $user_id, $user_values) {
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
				$temp_note_code = self::generate_individual_note_entry($user_values[$i], $i, $author_iden, $editable, false);
				$code .= $temp_note_code;
			}
		}
			// add new editable block of the last entry in existing note is not made by the current user
		if ($editable_index < 0) {
			$temp_note_code = self::generate_individual_note_entry(array('v' => '', 'f' => 'text'), $count, 'admin', true, true);
			$code .= $temp_note_code;
		}

		$code = str_replace('{{post-id}}', $post_id, $code);
		$code = str_replace('{{note-id}}', $note_id, $code);
		$code = str_replace('{{user-id}}', $user_id, $code);
		return $code;
	}
	public static function generate_user_note_display($note_value_record) {
		$row_id = $note_value_record['id'];
		$post_id = $note_value_record['post_id'];
		$note_id = $note_value_record['note_id'];
		$user_id = $note_value_record['user_id'];

		$note_setting = ProgressAllyNote::get_note_meta($post_id, $note_id);
		if ($note_setting) {
			return self::generate_individual_note_display($row_id, $post_id, $note_id, $user_id, $note_setting, $note_value_record['note_value'], $note_value_record['status'], $note_value_record['approve_status'], $note_value_record['updated']);
		} else {
			return '';
		}
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="note reply ajax code generation">
	const NUM_NOTE_PER_PAGE = 10;
	private static $STATUS_MAPPING = array('all' => false, 'new' => 0, 'replied' => 1, 'ignored' => 2, 'note' => 3, 'admin-init' => 4);
	public static function get_note_reply_callback() {
		try {
			if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'progressally-update-nonce')) {
				throw new Exception("Unable to validate request identify. Please refresh the page and make sure you are logged in as an administrator.");
			}
			$post_id = false;
			$note_id = false;
			$user_id = false;
			$status = false;
			$sort = false;
			if (isset(self::$STATUS_MAPPING[$_POST['status']])) {
				$status = self::$STATUS_MAPPING[$_POST['status']];
			}
			if (!empty($_POST['user-email'])) {
				$user = get_user_by('email', $_POST['user-email']);
				if (false === $user) {
					throw new Exception('Cannot find a user with email [' . $_POST['user-email'] . ']');
				}
				$user_id = $user->ID;
			}
			if (!empty($_POST['post']) && 'all' !== $_POST['post']) {
				$post_id = $_POST['post'];
			}
			if (!empty($_POST['note-id'])) {
				$note_id = $_POST['note-id'];
			}
			if (!empty($_POST['sort'])) {
				$sort = $_POST['sort'];
			}

			$filter_string = ProgressAllyNote::generate_note_database_query_string($post_id, $note_id, $user_id, $status, false);
			$page = $_POST['page-num'];
			$num_to_fetch = self::NUM_NOTE_PER_PAGE;

			$raw_data = ProgressAllyNote::get_all_user_notes($filter_string, ($page - 1) * $num_to_fetch, $num_to_fetch, $sort);
			$total = ProgressAllyNote::get_all_user_note_count($filter_string);

			$code = '';
			if (is_array($raw_data) && count($raw_data) > 0) {
				foreach ($raw_data as $row) {
					$code .= self::generate_user_note_display($row);
				}
			} else {
				throw new Exception('No entry.');
			}
			$result = array('status' => 'success', 'code' => $code, 'max' => max(1, ceil($total / $num_to_fetch)));
		} catch (Exception $ex) {
			$result = array('status' => 'error', 'message' => $ex->getMessage());
		}
		echo json_encode($result);
		die();
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="Admin note update Ajax processing">
	public static function update_admin_notes_callback() {
		$result = array('status' => 'error', 'message' => 'Unknown error. Please refresh the page and try again.');
		try {
			if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'progressally-update-nonce')) {
				throw new Exception("Unable to validate request identify. Please refresh the page and make sure you are logged in as an administrator.");
			}
			if (!isset($_POST['pid']) || !isset($_POST['nid']) || !isset($_POST['uid']) || !isset($_POST['rid']) || !isset($_POST['val']) || !isset($_POST['format']) || !isset($_POST['ord']) || !isset($_POST['approve'])) {
				throw new Exception('Invalid data.');
			}
			$val = stripslashes($_POST['val']);
			$admin_user_id = ProgressAllyUserProgress::get_user_id();
			$existing_attachments = array();
			if (isset($_POST['att']) && !empty($_POST['att'])) {
				$existing_attachments = explode(',', $_POST['att']);
			}
			if (empty($val) && empty($existing_attachments)) {
				$updated_notes = ProgressAllyNote::remove_note_value($_POST['pid'], $_POST['nid'], $_POST['uid'], $_POST['ord'], 1);
			} else {
				$approve_status = ($_POST['approve'] === 'true') ? ProgressAllyNote::NOTE_APPROVE_STATUS_APPROVED : false;
				$updated_notes = ProgressAllyNote::add_note_value($_POST['pid'], $_POST['nid'], $val, $_POST['format'], $existing_attachments, $_POST['uid'], $admin_user_id, $_POST['ord'], 1, ProgressAllyNote::NOTE_STATUS_REPLIED, $approve_status);
			}

			ProgressAllyUserProgress::update_note_completion($_POST['pid'], $_POST['nid'], $_POST['uid'], $updated_notes['approve_status']);

			// re-assign all the value to emulate the database entry. This avoid querying the database to improve performance.
			$updated_notes['id'] = $_POST['rid'];
			$updated_notes['post_id'] = $_POST['pid'];
			$updated_notes['note_id'] = $_POST['nid'];
			$updated_notes['user_id'] = $_POST['uid'];
			$updated_notes['note_value'] = $updated_notes['notes'];
			$updated_notes['updated'] = ProgressAllyBackendShared::get_sql_time();

			$code = self::generate_user_note_display($updated_notes);
			$result = array('status' => 'success', 'message' => 'success', 'code' => $code);
		} catch (Exception $e) {
			$result['status'] = 'error';
			$result['message'] = $e->getMessage() . ' Please refresh the page and try again.';
		}
		echo json_encode($result);
		die();
	}
	public static function close_admin_notes_callback() {
		$result = array('status' => 'error', 'message' => 'Unknown error. Please refresh the page and try again.');
		try {
			if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'progressally-update-nonce')) {
				throw new Exception("Unable to validate request identify. Please refresh the page and make sure you are logged in as an administrator.");
			}
			if (!isset($_POST['rid'])) {
				throw new Exception('Invalid data.');
			}
			$updated_notes = ProgressAllyNote::update_note_status($_POST['rid'], 2);
			$result = array('status' => 'success', 'message' => 'success', 'data' => $updated_notes);
		} catch (Exception $e) {
			$result['status'] = 'error';
			$result['message'] = $e->getMessage() . ' Please refresh the page and try again.';
		}
		echo json_encode($result);
		die();
	}
	public static function approve_admin_notes_callback() {
		$result = array('status' => 'error', 'message' => 'Unknown error. Please refresh the page and try again.');
		try {
			if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'progressally-update-nonce')) {
				throw new Exception("Unable to validate request identify. Please refresh the page and make sure you are logged in as an administrator.");
			}
			if (!isset($_POST['rid'])) {
				throw new Exception('Invalid data.');
			}
			$updated_notes = ProgressAllyNote::update_note_approve_status($_POST['rid'], ProgressAllyNote::NOTE_APPROVE_STATUS_APPROVED);
			$updated_notes['display_status'] = self::get_note_status_display_code(false, ProgressAllyNote::NOTE_APPROVE_STATUS_APPROVED);

			$result = array('status' => 'success', 'message' => 'success', 'data' => $updated_notes);
		} catch (Exception $e) {
			$result['status'] = 'error';
			$result['message'] = $e->getMessage() . ' Please refresh the page and try again.';
		}
		echo json_encode($result);
		die();
	}
	// </editor-fold>
}
