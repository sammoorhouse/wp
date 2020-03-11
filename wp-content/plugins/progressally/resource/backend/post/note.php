<?php
class ProgressAllyNote {
	const META_KEY = '_progressally_notes';

	// <editor-fold defaultstate="collapsed" desc="activation setup / deactivation cleanup">
	public static function do_activation_actions(){
		wp_cache_delete(self::META_KEY);
	}
	public static function do_deactivation_actions(){
		wp_cache_delete(self::META_KEY);
	}
	// </editor-fold>

	private static $default_per_note_settings = null;
	private static $default_notes_settings = array('max-notes' => 0, 'notes' => array());

	public static function add_actions() {
		add_action('wp_ajax_progressally_notes_update', array(__CLASS__, 'update_notes_callback'));
		add_action('wp_ajax_nopriv_progressally_notes_update', array(__CLASS__, 'update_notes_callback'));
	}

	// <editor-fold defaultstate="collapsed" desc="Database operations">
	public static function initialize_database_names() {
		global $wpdb;

		$wpdb->progress_notes = $wpdb->prefix . 'progress_notes';
	}
	const NOTE_APPROVE_STATUS_NOT_APPROVED = -1;
	const NOTE_APPROVE_STATUS_AUTO = 0;
	const NOTE_APPROVE_STATUS_PENDING_APPROVAL = 1;
	const NOTE_APPROVE_STATUS_APPROVED = 2;
	const NOTE_STATUS_UNREPLIED = 0;
	const NOTE_STATUS_REPLIED = 1;
	public static function create_database_table_query() {
	// status: 0 - unreplied, 1 - replied by admin, 2 - closed by admin, 3 - user note, 4 - admin init
	// approve_status: 0 - auto, 1 - waiting approval by admin, 2 - approved by admin
		global $charset_collate, $wpdb;
		return "CREATE TABLE $wpdb->progress_notes (
		  id bigint(20) unsigned NOT NULL auto_increment,
		  user_id bigint(20) unsigned NOT NULL default '0',
		  post_id bigint(20) unsigned NOT NULL default '0',
		  note_id bigint(20) unsigned NOT NULL default '0',
		  note_value longtext NOT NULL default '',
		  status int(11) NOT NULL default '0',
		  approve_status int(11) NOT NULL default '0',
		  created datetime NOT NULL default '0000-00-00 00:00:00',
		  updated datetime default '0000-00-00 00:00:00',
		  PRIMARY KEY  (id),
		  KEY user_id (user_id),
		  KEY post_id (post_id),
		  KEY note_id (note_id),
		  KEY status (status),
		  KEY approve_status (approve_status),
		  KEY updated (updated)
		) $charset_collate;";
	}
	public static function get_note_database($row_id) {
		global $wpdb;
		$user_note = $wpdb->get_row("SELECT * FROM $wpdb->progress_notes WHERE id = $row_id", ARRAY_A);
		if (is_array($user_note)) {
			$user_note['note_value'] = maybe_unserialize($user_note['note_value']);
			return $user_note;
		}

		return null;
	}
	private static function get_user_note_database($post_id, $note_id, $user_id) {
		if ($user_id > 0 && $post_id > 0) {
			global $wpdb;
			$user_note = $wpdb->get_row("SELECT * FROM $wpdb->progress_notes WHERE post_id = $post_id AND note_id = $note_id AND user_id = $user_id", ARRAY_A);
			if (is_array($user_note)) {
				$user_note['note_value'] = maybe_unserialize($user_note['note_value']);
				return $user_note;
			}
		}

		return null;
	}
	private static function set_user_note_database($row_id, $post_id, $note_id, $user_id, $note_value, $status, $approve_status) {
		if ($user_id > 0) {
			global $wpdb;
			$serialized_value = maybe_serialize($note_value);
			$timestamp = ProgressAllyBackendShared::get_sql_time();
			if ($row_id > 0) {	// update existing row
				$result = $wpdb->update($wpdb->progress_notes, array('note_value' => $serialized_value,
					'status' => $status,
					'approve_status' => $approve_status,
					'updated' => $timestamp
					),
					array('id' => $row_id));
			} else {	// insert new row
				$result = $wpdb->insert($wpdb->progress_notes, array('post_id' => $post_id,
					'note_id' => $note_id,
					'user_id' => $user_id,
					'note_value' => $serialized_value,
					'status' => $status,
					'approve_status' => $approve_status,
					'created' => $timestamp,
					'updated' => $timestamp
					));
			}
			return $result;
		}
		return null;
	}
	public static function generate_note_database_query_string($post_id, $note_id, $user_id, $status, $approve_status) {
		$filter = array();
		if ($post_id !== false) {
			$filter []= 'post_id=' . $post_id;
		}
		if ($note_id !== false) {
			$filter []= 'note_id=' . $note_id;
		}
		if ($user_id !== false) {
			$filter []= 'user_id=' . $user_id;
		}
		if ($status !== false) {
			$filter []= 'status=' . $status;
		}
		if ($approve_status !== false) {
			$filter []= 'approve_status=' . $approve_status;
		}
		$filter_string = '';
		if (!empty($filter)) {
			$filter_string = 'WHERE ' . implode(' AND ', $filter);
		}
		return $filter_string;
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="Generate backend settings display">
	public static $note_type_option_mapping = array(
		'note' => array('checked-admin-initiated' => 'no', 'checked-approve' => 'no', 'checked-notify-admin' => 'no', 'checked-notify-user' => 'no', 'num-reply' => -1),
		'qa' => array('checked-admin-initiated' => 'no', 'checked-approve' => 'no', 'checked-notify-admin' => 'yes', 'checked-notify-user' => 'yes', 'num-reply' => 1),
		'admin' => array('checked-admin-initiated' => 'yes', 'checked-approve' => 'no', 'checked-notify-admin' => 'no', 'checked-notify-user' => 'no', 'num-reply' => 0),
		'approve' => array('checked-admin-initiated' => 'no', 'checked-approve' => 'yes', 'checked-notify-admin' => 'yes', 'checked-notify-user' => 'yes', 'num-reply' => -1),
		);
	private static $cached_note_template = null;
	private static function get_note_template() {
		if (self::$cached_note_template === null) {
			self::$cached_note_template = file_get_contents(dirname(__FILE__) . '/note-template.php');
		}
		return self::$cached_note_template;
	}
	private static function generate_note_code($note_id, $note_settings, $referred = false) {
		$code = self::get_note_template();
		$code = ProgressAllyBackendShared::replace_real_values($code, $note_settings, '');
		$code = ProgressAllyBackendShared::replace_all_toggle($code, $note_settings);
		$code = str_replace('{{open-class}}', $note_settings['checked-is-open'] === 'yes' ? 'progressally-accordion-opened' : '', $code);
		$code = str_replace('{{note-id}}', $note_id, $code);
		
		// Hide delete button when note is referred in objective list
		$can_delete = $referred ? 'style="display: none;"' : '';
		$show_delete_help_text = $referred ? '' : 'style="display: none;"';
		$code = str_replace('{{delete-button-display}}', $can_delete, $code);
		$code = str_replace('{{delete-text-display}}', $show_delete_help_text, $code);
		
		return $code;
	}
	public static function generate_default_note_code() {
		$default_settings = self::get_default_per_note_settings('--blog-title--');
		$default_settings['custom-email-content'] = str_replace("\n", '\n', $default_settings['custom-email-content']);
		$code = self::generate_note_code('--note-id--', $default_settings);
		return $code;
	}
	private static function generate_note_settings_code($settings, $referred_notes) {
		$note_code = '';
		foreach ($settings['notes'] as $id => $note_settings) {
			$referred = false;
			if (in_array($id, $referred_notes)) {
				$referred = true;
			}
			$note_code .= self::generate_note_code($id, $note_settings, $referred);
		}
		return $note_code;
	}
	public static function show_note_meta_box($post_id, $progress_meta, $note_meta = false) {
		if ($note_meta === false) {
			$note_meta = self::get_post_note_meta($post_id);
		}
		// List the notes referred in objective list
		$referred_notes = array();
		foreach ($progress_meta['objectives'] as $objective_setting) {
			if ($objective_setting['seek-type'] === 'note' && intval($objective_setting['note-id']) > 0) {
				$referred_notes[] = $objective_setting['note-id'];
			}
		}
		
		$note_code = self::generate_note_settings_code($note_meta, $referred_notes);
		$max_note_num = $note_meta['max-notes'];

		ob_start();
		include dirname(__FILE__) . '/note-display.php';
		return ob_get_clean();
	}
	public static function get_note_selection_template($note_meta) {
		$options = '';
		foreach ($note_meta['notes'] as $id => $note_setting) {
			$options .= '<option s--selected-'.$id.'--d value="' . $id . '">' . $id . '. ' . esc_html($note_setting['name']) . '</option>';
		}
		return $options;
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="Note meta operation">
	public static function save_post_note_meta($post_id) {
		if (isset($_POST[self::META_KEY]) && is_string($_POST[self::META_KEY]) && !empty($_POST[self::META_KEY])) {
			$note_meta = ProgressAllyBackendShared::convert_setting_string_to_array($_POST[self::META_KEY]);
			if (is_array($note_meta) && !empty($note_meta) && isset($note_meta['max-notes']) && $note_meta['max-notes'] > 0) {
				$note_meta = self::merge_default_settings($note_meta);
				update_post_meta($post_id, self::META_KEY, $note_meta);
				wp_cache_set(self::META_KEY, $note_meta, $post_id, time() + ProgressAlly::CACHE_PERIOD);
			}
		}
	}
	private static $cached_post_note_meta = array();
	public static function get_post_note_meta($post_id) {
		if (!isset(self::$cached_post_note_meta[$post_id])) {
			$meta = wp_cache_get(self::META_KEY, $post_id);
			if ($meta === false) {
				$meta = get_post_meta($post_id, self::META_KEY, true);
				if (!is_array($meta)) {
					$meta = self::$default_notes_settings;
				}

				wp_cache_set(self::META_KEY, $meta, $post_id, time() + ProgressAlly::CACHE_PERIOD);
			}
			self::$cached_post_note_meta[$post_id] = self::merge_default_settings($meta);
		}
		return self::$cached_post_note_meta[$post_id];
	}
	public static function get_note_meta($post_id, $note_id) {
		$note_meta = self::get_post_note_meta($post_id);
		if (isset($note_meta['notes'][$note_id])) {
			return $note_meta['notes'][$note_id];
		}
		return false;
	}
	private static function get_default_per_note_settings($blog_title = false) {
		if (null === self::$default_per_note_settings) {
			if (false === $blog_title) {
				$blog_title = get_bloginfo('name');
			}
			self::$default_per_note_settings = array('name' => 'User-specific note', 'checked-is-open' => 'no', 'title' => 'Private Note', 'select-type' => 'note',
				'placeholder' => 'Click here to enter your note',
				'checked-admin-initiated' => 'no', 'checked-notify-admin' => 'no', 'checked-notify-user' => 'no', 'num-reply' => 0,
				'checked-custom-email' => 'no', 'checked-approve' => 'no',
				'custom-email-subject' => '[' . $blog_title . '] You have a new reply!',
				'custom-email-content' => <<<'EOT'
<table cellpadding="0" cellspacing="0" border="0" align="center" style="width:100%;max-width:600px">
	<tbody>
    <tr>
      <td style="font-size:1px;line-height:1px" height="10">&nbsp;</td>
    </tr>
	<tr>
      <td><a target="_blank" href="{[post-raw-link]}">Click here</a> to see the reply</td>
	</tr>
    <tr>
      <td style="font-size:1px;line-height:1px" height="20">&nbsp;</td>
    </tr>
    <tr>
      <td style="color:#000000;font-size:24px">Note Details</td>
    </tr>
    <tr>
      <td style="font-size:1px;line-height:1px" height="20">&nbsp;</td>
    </tr>
	{[note-details]}
	<tr>
      <td><a target="_blank" href="{[post-raw-link]}">Click here</a> to see the reply</td>
	</tr>
  </tbody>
</table>
EOT
				);
		}
		return self::$default_per_note_settings;
	}
	public static function merge_default_settings($settings) {
		if (!isset($settings['notes'])) {
			$settings['notes'] = array();
		}
		foreach ($settings['notes'] as $id => $note_settings) {
			$settings['notes'][$id] = wp_parse_args($note_settings, self::get_default_per_note_settings());
		}
		$settings = wp_parse_args($settings, self::$default_notes_settings);
		return $settings;
	}
	public static function get_max_allowed_user_notes($note_meta) {
		switch($note_meta['select-type']) {
			case 'note':
				return -1;
			case 'qa':
				return 1;
			case 'admin':
				return 0;
			case 'approve':
				return -1;
			case 'custom':
				return $note_meta['num-reply'];
			default:
				return 0;
		}
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="Note value operation">
	private static function generate_note_key($post_id, $note_id) {
		return 'progressally_note_p' . $post_id . 't_n' . $note_id . 'e';
	}
	public static function get_user_notes_raw($post_id, $note_id, $user_id) {
		$user_id = ProgressAllyUserProgress::get_user_id($user_id);

		// Retrieve usr meta from cookie if not logged in
		if ($user_id <= 0) {
			$note_key = self::generate_note_key($post_id, $note_id);
			$meta = ProgressAllyBackendShared::read_cookie($note_key);
			$note_data = array('id' => -1, 'note_value' => $meta, 'status' => 0, 'approve_status' => self::NOTE_APPROVE_STATUS_NOT_APPROVED);
		} else {
			$note_data = self::get_user_note_database($post_id, $note_id, $user_id);
			if (null === $note_data) {
				$note_data = array('id' => 0, 'note_value' => null, 'status' => 0, 'approve_status' => self::NOTE_APPROVE_STATUS_NOT_APPROVED);
			}
		}

		$note_data['note_value'] = self::merge_default_user_note_values($note_data['note_value']);
		return $note_data;
	}
	public static function update_user_note_raw($row_id, $note_value) {
		global $wpdb;
		$serialized_value = maybe_serialize($note_value);
		$timestamp = ProgressAllyBackendShared::get_sql_time();
		if ($row_id > 0) {	// update existing row
			$result = $wpdb->update($wpdb->progress_notes, array('note_value' => $serialized_value,
				'updated' => $timestamp
				),
				array('id' => $row_id));
		}
		return $result;
	}
	public static function get_user_notes($post_id, $note_id, $user_id) {
		$raw_data = self::get_user_notes_raw($post_id, $note_id, $user_id);

		return $raw_data['note_value'];
	}
	private static function merge_default_user_note_values($note_meta) {
		if (!is_array($note_meta)) {
			$note_meta = array();
		}
		return $note_meta;
	}
	private static function set_user_notes($row_id, $post_id, $note_id, $user_id, $note_value, $status, $approve_status) {
		if ($user_id > 0) {
			self::set_user_note_database($row_id, $post_id, $note_id, $user_id, $note_value, $status, $approve_status);
		}
	}
	// $note_status: 0 - unreplied, 1 - replied by admin, 2 - closed by admin, 3 - user note, 4 - admin init
	// $note_source: 0 - user, 1 - admin
	// $approve_status: 0 - auto, 1 - waiting approval by admin, 2 - approved by admin
	public static function add_note_value($post_id, $note_id, $val, $val_format, $existing_attachment_ids, $user_id, $note_author_id, $ordinal, $note_source, $note_status = false, $approve_status = false) {
		$user_id = ProgressAllyUserProgress::get_user_id($user_id);
		$notes_raw_data = self::get_user_notes_raw($post_id, $note_id, $user_id);
		$row_id = $notes_raw_data['id'];
		$notes = $notes_raw_data['note_value'];

		$note_meta = self::get_note_meta($post_id, $note_id);
		if (false === $note_status) {
			$note_status = 0;
			if ($note_source === 0) {
				if ($note_meta['select-type'] === 'note') {
					$note_status = 3;
				}
			} else {
				$note_status = 1;
			}
		}
		if (false === $approve_status) {
			// Inherit approve_status if exists
			if ($row_id > 0) {
				$approve_status = intval($notes_raw_data['approve_status']);
			} else {
				$approve_status = 0;
				// Cookie-based notes always auto-approved
				if ($user_id > 0 && $note_meta['checked-approve'] !== 'no') {
					$approve_status = 1;
				}
			}
		}
		
		$current_time = time();
		$num_notes = count($notes);
		// append a new note
		if ($ordinal < 0 || $ordinal >= $num_notes || $notes[$ordinal]['a'] != $note_author_id) {
			$notes []= array('a' => $note_author_id, 'v' => $val, 's' => $note_source, 't' => $current_time, 'f' => $val_format);

			if (0 === $note_source) {	// user added a new note. send notification to admin
				ProgressAllyNotesEmail::send_notification_to_admin($user_id, $notes, $post_id, $note_id, $note_meta);
			} else {
				ProgressAllyNotesEmail::send_notification_to_user($user_id, $notes, $post_id, $note_meta);
			}
		} else {
			$notes[$ordinal]['v'] = $val;
			$notes[$ordinal]['f'] = $val_format;
			if (isset($notes[$ordinal]['att'])) {
				$attachments_to_keep = array();
				foreach ($existing_attachment_ids as $attachment_id) {
					if (isset($notes[$ordinal]['att'][$attachment_id])) {
						$attachments_to_keep[$attachment_id] = $notes[$ordinal]['att'][$attachment_id];
					}
				}
				$notes[$ordinal]['att'] = $attachments_to_keep;
			}
		}

		self::set_user_notes($row_id, $post_id, $note_id, $user_id, $notes, $note_status, $approve_status);
		$note_key = self::generate_note_key($post_id, $note_id);
		return array('id' => $row_id, 'key' => $note_key, 'notes' => $notes, 'status' => $note_status, 'approve_status' => $approve_status);
	}
	// $note_source: 0 - user, 1 - admin
	public static function remove_note_value($post_id, $note_id, $user_id, $ordinal, $trigger_source) {
		$user_id = ProgressAllyUserProgress::get_user_id($user_id);
		$notes_raw_data = self::get_user_notes_raw($post_id, $note_id, $user_id);
		$row_id = $notes_raw_data['id'];
		$notes = $notes_raw_data['note_value'];
		$note_status = $notes_raw_data['status'];
		$approve_status = $notes_raw_data['approve_status'];

		$note_key = self::generate_note_key($post_id, $note_id);	// used as cookie name. not used for logged in users.

		$num_notes = count($notes);
		if ($ordinal >= $num_notes) {	// invalid ordinal
			return array('key' => $note_key, 'notes' => $notes, 'status' => $note_status, 'approve_status' => $approve_status);
		}
		if ($num_notes <= 1) { // deleting the only note value
			self::delete_note($row_id);
			return array('key' => $note_key, 'notes' => array(), 'status' => 0, 'approve_status' => self::NOTE_APPROVE_STATUS_NOT_APPROVED);
		}
		if ($ordinal < $num_notes - 1) {	// deleting the non-last entry. this should not happen unless there is a lack of refresh
			$notes[$ordinal]['v'] = '';
		} else {
			unset($notes[$ordinal]);
			if ($trigger_source === 0) {	// user deleting the last entry
				$note_status = 1;	// replied by admin
			} else {	// admin deleting the last entry
				$note_meta = self::get_note_meta($post_id, $note_id);
				if ($note_meta['select-type'] === 'note') {
					$note_status = 3;	// user note
				} else {
					$note_status = 0;	// need reply
				}
			}
		}

		self::set_user_notes($row_id, $post_id, $note_id, $user_id, $notes, $note_status, $approve_status);
		return array('id' => $row_id, 'key' => $note_key, 'notes' => $notes, 'status' => $note_status, 'approve_status' => $approve_status);
	}
	public static function update_note_status($row_id, $status) {
		global $wpdb;
		$timestamp = ProgressAllyBackendShared::get_sql_time();
		$result = $wpdb->update($wpdb->progress_notes, array('status' => $status,
			'updated' => $timestamp
			),
			array('id' => $row_id));
		return array('status' => $status);
	}
	public static function update_note_approve_status($row_id, $approve_status) {
		global $wpdb;
		$raw_note_data = self::get_note_database($row_id);
		if (!is_array($raw_note_data)) {
			throw new Exception('Invalid note ID.');
		}
		$timestamp = ProgressAllyBackendShared::get_sql_time();
		$result = $wpdb->update($wpdb->progress_notes, array('approve_status' => $approve_status,
			'updated' => $timestamp
			),
			array('id' => $row_id));
		
		ProgressAllyUserProgress::update_note_completion($raw_note_data['post_id'], $raw_note_data['note_id'], $raw_note_data['user_id'], $approve_status);
		return array('approve_status' => $approve_status);
	}
	private static function delete_note($row_id) {
		global $wpdb;
		$result = $wpdb->delete($wpdb->progress_notes,
			array('id' => $row_id),
			array('%d'));
		return array('status' => $result);
	}
	public static function get_all_user_notes($filter_string, $offset, $num, $sort = false) {
		global $wpdb;
		$limit_string = '';
		if ($num) {
			$limit_string .= ' LIMIT ' . $num;
		}
		if ($offset) {
			$limit_string .= ' OFFSET ' . $offset;
		}
		$sort_order = 'DESC';
		if ($sort) {
			$sort_order = $sort;
		}
		$all_notes = $wpdb->get_results("SELECT * FROM $wpdb->progress_notes $filter_string ORDER BY updated $sort_order $limit_string", ARRAY_A);
		foreach ($all_notes as $index => $note) {
			$all_notes[$index]['note_value'] = maybe_unserialize($note['note_value']);
		}
		return $all_notes;
	}
	public static function get_all_user_note_count($filter_string) {
		global $wpdb;

		$query = "SELECT COUNT(id) AS count FROM {$wpdb->progress_notes} " . $filter_string;
		$result = $wpdb->get_row($query);

		return intval($result->count);
	}
	public static function get_all_user_note_approved_list($post_id, $note_id) {
		// Only return the list of user_id without note meta
		global $wpdb;
		$filter_string = "WHERE post_id = $post_id AND note_id = $note_id AND approve_status in (0,2)";
		$all_approved_notes = $wpdb->get_results("SELECT user_id FROM $wpdb->progress_notes $filter_string", ARRAY_A);
		
		$result = array();
		if (is_array($all_approved_notes)) {
			foreach ($all_approved_notes as $row) {
				$result[] = $row['user_id'];
			}
		}
		return $result;
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="Note update Ajax processing">
	public static function update_notes_callback() {
		$result = array('status' => 'error', 'message' => 'Unknown error. Please refresh the page and try again.');
		try {
			if (!isset($_POST['pid']) || !isset($_POST['nid']) || !isset($_POST['val']) || !isset($_POST['ord']) || !isset($_POST['attr'])) {
				throw new Exception('Invalid data.');
			}
			$val = stripslashes($_POST['val']);
			$user_id = ProgressAllyUserProgress::get_user_id();

			$existing_attachment_ids = array();
			if (isset($_POST['att']) && !empty($_POST['att'])) {
				$existing_attachment_ids = explode(',', $_POST['att']);
			}
			if (empty($val) && empty($existing_attachment_ids)) {
				$updated_notes = self::remove_note_value($_POST['pid'], $_POST['nid'], $user_id, $_POST['ord'], 0);
			} else {
				$updated_notes = self::add_note_value($_POST['pid'], $_POST['nid'], $val, 'text', $existing_attachment_ids, $user_id, $user_id, $_POST['ord'], 0);
			}

			$static_attributes = ProgressAllyNotesShortcode::parse_ajax_serialized_frontend_param($_POST['attr']);
			$frontend_code = ProgressAllyNotesShortcode::generate_frontend_note_code($_POST['pid'], $_POST['nid'], $user_id, $updated_notes['notes'], $static_attributes, true);

			$result = array('status' => 'success', 'message' => 'success', 'data' => $updated_notes, 'code' => $frontend_code);

			$progress = ProgressAllyUserProgress::update_note_completion($_POST['pid'], $_POST['nid'], $user_id, $updated_notes['approve_status']);

			if (!empty($progress)) {
				$result['progress'] = $progress;
			}
		} catch (Exception $e) {
			$result['status'] = 'error';
			$result['message'] = $e->getMessage() . ' Please refresh the page and try again.';
		}
		echo json_encode($result);
		die();
	}
	// </editor-fold>
}