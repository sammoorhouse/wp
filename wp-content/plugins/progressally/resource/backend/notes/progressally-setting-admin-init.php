<?php
class ProgressAllySettingAdminInitNotes {
	public static function add_actions() {
		add_action('wp_ajax_progressally_get_admin_init_notes', array(__CLASS__, 'get_admin_init_users_callback'));

		add_action('wp_ajax_progressally_admin_init_notes_update', array(__CLASS__, 'update_notes_callback'));
	}

	// <editor-fold defaultstate="collapsed" desc="Note view display">
	private static $cached_display_template = array();
	private static function get_display_template($template_file) {
		if (!isset(self::$cached_display_template[$template_file])) {
			self::$cached_display_template[$template_file] = file_get_contents(dirname(__FILE__) . '/' . $template_file . '.php');
		}
		return self::$cached_display_template[$template_file];
	}
	private static function generate_user_edit_link_with_email($wp_user_id, $user_email) {
		$userdata = get_userdata($wp_user_id);
		$name = 'Unknown user';
		if ($userdata) {
			$name = $userdata->first_name . ' ' . $userdata->last_name . ' ('. $user_email . ')';
		}
		return '<a target="_blank" href="' . get_edit_user_link($wp_user_id) . '">' . esc_attr($name) . '</a>';
	}
	private static function generate_individual_note_display($user_id, $user_email, $note_values) {
		$code = self::get_display_template('progressally-setting-admin-init-template');

		$exist_notes = ProgressAllySettingNotesShared::generate_individual_user_note_display('admin-init', 0, 0, $user_id, $note_values);
		$code = str_replace('{{existing-notes}}', $exist_notes, $code);

		$code = str_replace('{{size}}', '8', $code);
		$code = str_replace('{{user-link}}', self::generate_user_edit_link_with_email($user_id, $user_email), $code);
		$code = str_replace('{{user-id}}', $user_id, $code);
		$code = str_replace('{{row-id}}', $user_id, $code);	// it is part of the attachment template, which is shared with note reply
		return $code;
	}

	private static function generate_user_note_display($user_info, $user_note_map) {
		$user_id = $user_info['ID'];
		$user_email = $user_info['user_email'];

		if (isset($user_note_map[$user_id])) {
			$note_value = $user_note_map[$user_id];
		} else {
			$note_value = array();
		}
		return self::generate_individual_note_display($user_id, $user_email, $note_value);
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="admin init user list ajax code generation">
	private static function get_user_by_id($user_id) {
		global $wpdb;
		$users = $wpdb->get_results("SELECT ID, user_email FROM {$wpdb->users} WHERE ID=$user_id", ARRAY_A);
		return $users;
	}
	private static function get_users($offset, $num) {
		global $wpdb;
		$limit_string = '';
		if ($num) {
			$limit_string .= ' LIMIT ' . $num;
		}
		if ($offset) {
			$limit_string .= ' OFFSET ' . $offset;
		}
		$users = $wpdb->get_results("SELECT ID, user_email FROM {$wpdb->users} $limit_string", ARRAY_A);
		return $users;
	}
	private static function get_existing_notes_for_users($user_ids, $post_id, $note_id) {
		global $wpdb;
		$user_id_string = '(' . implode(',', $user_ids) . ')';
		$all_notes = $wpdb->get_results("SELECT * FROM $wpdb->progress_notes WHERE post_id=$post_id AND note_id=$note_id AND user_id IN $user_id_string", ARRAY_A);
		$result = array();
		foreach ($all_notes as $index => $note) {
			$result[$note['user_id']] = maybe_unserialize($note['note_value']);
		}
		return $result;
	}
	private static function get_user_count() {
		global $wpdb;

		$query = "SELECT COUNT(ID) AS count FROM {$wpdb->users}";
		$result = $wpdb->get_row($query);

		return intval($result->count);
	}
	const NUM_USERS_PER_PAGE = 100;
	public static function get_admin_init_users_callback() {
		try {
			if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'progressally-update-nonce')) {
				throw new Exception("Unable to validate request identify. Please refresh the page and make sure you are logged in as an administrator.");
			}
			if (!isset($_POST['post']) || !isset($_POST['note-id'])) {
				throw new Exception("Invalid input data. Please make sure the selected post/page and note are valid.");
			}
			$post_id = $_POST['post'];
			$note_id = $_POST['note-id'];
			$user_id = false;
			$page = $_POST['page-num'];
			$num_to_fetch = self::NUM_USERS_PER_PAGE;

			$total = 0;
			if (!empty($_POST['user-email'])) {
				$user = get_user_by('email', $_POST['user-email']);
				if (false === $user) {
					throw new Exception('Cannot find a user with email [' . $_POST['user-email'] . ']');
				}
				$user_id = $user->ID;
				$users = self::get_user_by_id($user_id);
				$total = count($users);
			} else {
				$users = self::get_users(($page - 1) * $num_to_fetch, $num_to_fetch);
				$total = self::get_user_count();
			}
			$user_ids = array();
			
			if (empty($users)) {
				$user_notes = array();
			} else {
				foreach ($users as $user_info) {
					$user_ids []= $user_info['ID'];
				}
				$user_notes = self::get_existing_notes_for_users($user_ids, $post_id, $note_id);
			}

			$code = '';
			if (is_array($users) && count($users) > 0) {
				foreach ($users as $user_info) {
					$code .= self::generate_user_note_display($user_info, $user_notes);
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

	// <editor-fold defaultstate="collapsed" desc="Show settings">
	public static function show_admin_init_settings() {
		$posts_with_notes = ProgressAllyBackendShared::get_all_post_with_notes();
		$page_selection_code = '';
		$note_selection_code = '';
		foreach ($posts_with_notes as $row) {
			$note_meta = maybe_unserialize($row['meta_value']);
			$temp_note_code = '';
			if (isset($note_meta['notes']) && is_array($note_meta['notes'])) {
				foreach ($note_meta['notes'] as $note_id => $meta) {
					if ('admin' === $meta['select-type'] || ('custom' === $meta['select-type'] && 'yes' === $meta['checked-admin-initiated'])) {
						$temp_note_code .= '<option value="' . $note_id . '">' . $note_id . '. ' . esc_attr($meta['name']) . '</option>';
					}
				}
			}
			if (!empty($temp_note_code)) {
				$page_selection_code .= '<option value="' . $row['ID'] . '">' . esc_attr($row['post_title']) . ' (' . $row['ID'] . ')</option>';
				$note_selection_code .= '<tr style="display:none;" id="progressally-admin-init-select-note-' . $row['ID'] . '" hide-toggle pa-dep="progressally-admin-init-select-post" pa-dep-value="' . $row['ID'] .
					'"><th scope="row"><label for="progressally-admin-init-select-note-' . $row['ID'] . '">Note</label></th>' .
					'<td><select>' . $temp_note_code . '</select></td></tr>';
			}
		}
		include dirname(__FILE__) . '/progressally-setting-admin-init-display.php';
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="Admin note update Ajax processing">
	public static function update_notes_callback() {
		$result = array('status' => 'error', 'message' => 'Unknown error. Please refresh the page and try again.');
		try {
			if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'progressally-update-nonce')) {
				throw new Exception("Unable to validate request identify. Please refresh the page and make sure you are logged in as an administrator.");
			}
			if (!isset($_POST['pid']) || !isset($_POST['nid']) || !isset($_POST['uid']) || !isset($_POST['val']) || !isset($_POST['format']) || !isset($_POST['ord'])) {
				throw new Exception('Invalid data.');
			}
			$val = stripslashes($_POST['val']);
			$admin_user_id = ProgressAllyUserProgress::get_user_id();

			$existing_attachments = array();
			if (isset($_POST['att'])) {
				$existing_attachments = explode(',', $_POST['att']);
			}

			$user_id = $_POST['uid'];
			if (empty($val)) {
				$updated_notes = ProgressAllyNote::remove_note_value($_POST['pid'], $_POST['nid'], $user_id, $_POST['ord'], 1);
			} else {
				$updated_notes = ProgressAllyNote::add_note_value($_POST['pid'], $_POST['nid'], $val, $_POST['format'], $existing_attachments, $user_id, $admin_user_id, $_POST['ord'], 1, 4);
			}

			ProgressAllyUserProgress::update_note_completion($_POST['pid'], $_POST['nid'], $_POST['uid'], $updated_notes['approve_status']);

			$user_email = 'No email';
			$userinfo = get_userdata($user_id);
			if ($userinfo && !empty($userinfo->user_email)) {
				$user_email = $userinfo->user_email;
			}
			$code = self::generate_individual_note_display($user_id, $user_email, $updated_notes['notes']);

			$result = array('id' => $updated_notes['id'], 'status' => 'success', 'message' => 'success', 'code' => $code);
		} catch (Exception $e) {
			$result['status'] = 'error';
			$result['message'] = $e->getMessage() . ' Please refresh the page and try again.';
		}
		echo json_encode($result);
		die();
	}
	// </editor-fold>
}