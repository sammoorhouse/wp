<?php
class ProgressAllyNotesAttachment {
	public static function add_actions() {
		add_action('wp_ajax_progressally_notes_add_attachment', array(__CLASS__, 'add_attachment_callback'));
		add_action('wp_ajax_nopriv_progressally_notes_add_attachment', array(__CLASS__, 'add_attachment_callback'));

		add_action('wp_ajax_progressally_admin_notes_reply_add_attachment', array(__CLASS__, 'add_admin_attachment_callback'));

		add_action('wp_ajax_progressally_get_attachment', array(__CLASS__, 'get_attachment_callback'));
		add_action('wp_ajax_nopriv_progressally_get_attachment', array(__CLASS__, 'get_attachment_callback'));

		add_action('wp_ajax_progressally_get_admin_attachment', array(__CLASS__, 'get_admin_attachment_callback'));
	}

	// <editor-fold defaultstate="collapsed" desc="helper function on whether attachment is enabled">
	public static function is_attachment_allowed() {
		$note_settings = ProgressAllySettingNotesConfig::get_settings();

		return 'none' !== $note_settings['select-attachment-location'];
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="Create note attachment folder">
	const NOTE_ATTACHMENT_FOLDER = 'progressally-note-attachment';
	public static function create_note_attachment_directory() {
		if (!function_exists('request_filesystem_credentials')) {
			require_once (ABSPATH . '/wp-admin/includes/file.php');
		}
		if (false === ($creds = request_filesystem_credentials('admin.php', '', false, false, null))) {
			return true;
		}
		if (!WP_Filesystem($creds)) {
			echo 'Cannot initiate WP_Filesystem. Please make sure you have the proper permission on the WordPress Install';
			return true;
		}
		global $wp_filesystem;
		$target_dir = trailingslashit($wp_filesystem->wp_content_dir());
		$target_dir = trailingslashit($target_dir . self::NOTE_ATTACHMENT_FOLDER);

		if(!$wp_filesystem->is_dir($target_dir)) {
			$wp_filesystem->mkdir($target_dir);
		}
		if (!file_exists($target_dir . '.htaccess')) {
			$htaccess_content = file_get_contents(dirname(__FILE__) . '/note-htaccess-template.php');
			$wp_filesystem->put_contents($target_dir . '.htaccess', $htaccess_content, FS_CHMOD_FILE);
		}
	}
	// </editor-fold>
	
	// <editor-fold defaultstate="collapsed" desc="Get frontend Note attachment URL path">
	public static function get_attachment_url($post_id, $note_id, $ordinal, $attachment_id) {
		$admin_url = admin_url('admin-ajax.php');
		$param = array(
			'action' => 'progressally_get_attachment',
			'pid' => $post_id,
			'nid' => $note_id,
			'ord' => $ordinal,
			'aid' => $attachment_id,
			'nonce' => wp_create_nonce('progressally-attachment-' . $post_id . '-' . $note_id . '-' . $ordinal . '-' . $attachment_id)
		);
		$url = add_query_arg($param, $admin_url);
		return $url;
	}
	// </editor-fold>
	
	// <editor-fold defaultstate="collapsed" desc="Get backend Note attachment URL path">
	public static function get_admin_attachment_url($post_id, $note_id, $user_id, $ordinal, $attachment_id) {
		$admin_url = admin_url('admin-ajax.php');
		$param = array(
			'action' => 'progressally_get_admin_attachment',
			'pid' => $post_id,
			'nid' => $note_id,
			'ord' => $ordinal,
			'aid' => $attachment_id,
			'uid' => $user_id,
			'nonce' => wp_create_nonce('progressally-admin-attachment-' . $post_id . '-' . $note_id . '-' . $ordinal . '-' . $attachment_id . '-' . $user_id)
		);
		$url = add_query_arg($param, $admin_url);
		return $url;
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="create new attachment entry">
	private static function generate_upload_file_name($file_name_no_extension, $post_id, $note_id, $user_id) {
		$display_time = date('Ymd', time());
		$file_name_no_extension = implode('_', array($display_time, $user_id, $post_id, $note_id, $file_name_no_extension));
		
		// restrict file name length
		if (strlen($file_name_no_extension) >= 255) {
			$file_name_no_extension = substr($file_name_no_extension, 0, 253); // leave two characters for name duplication
		}
		return $file_name_no_extension;
	}
	private static function generate_unique_file_name($target_dir, $post_id, $note_id, $user_id, $file_name) {
		$file_name_parts = pathinfo($file_name);
		if (strcasecmp($file_name_parts['extension'], 'php') === 0 ) {
			throw new Exception('PHP file is not allowed as attachment.');
		}

		$file_name_no_extension = self::generate_upload_file_name($file_name_parts['filename'], $post_id, $note_id, $user_id);
		$file_name = $file_name_no_extension . '.' . $file_name_parts['extension'];

		if (file_exists($target_dir . $file_name)) {
			for ($i = 0; $i < 10 && file_exists($target_dir . $file_name); ++$i) {
				$file_name = $file_name_no_extension . ProgressAllyBackendShared::generate_random_string(2) . '.' . $file_name_parts['extension'];
			}
			if (file_exists($target_dir . $file_name)) {
				throw new Exception('File update failed because the server folder is full.');
			}
		}
		return $file_name;
	}
	private static function create_new_attachment($target_dir, $post_id, $note_id, $user_id, $file_name) {
		$file_path = self::generate_unique_file_name($target_dir, $post_id, $note_id, $user_id, $file_name);

		$attachment_info = array('name' => $file_name, 'path' => $file_path, 'type' => 'local');
		return $attachment_info;
	}
	// </editor-fold>
	
	// <editor-fold defaultstate="collapsed" desc="Note attachment operation">
	private static function add_attachment_to_database($row_id, $post_id, $note_id, $user_id, $ordinal, $user_notes, $attachment_id, $file_name) {
		if (!function_exists('request_filesystem_credentials')) {
			require_once (ABSPATH . '/wp-admin/includes/file.php');
		}
		if (false === ($creds = request_filesystem_credentials('admin.php', '', false, false, null))) {
			throw new Exception('Cannot save file to server. Please contact the site administrator.');
		}
		if (!WP_Filesystem($creds)) {
			throw new Exception('Cannot save file to server. Please contact the site administrator.');
		}
		global $wp_filesystem;
		$target_dir = trailingslashit($wp_filesystem->wp_content_dir());
		$target_dir = trailingslashit($target_dir . ProgressAllyNotesAttachment::NOTE_ATTACHMENT_FOLDER);

		$current_conversation = $user_notes[$ordinal];
		if (!isset($current_conversation['att'])) {
			$current_conversation['att'] = array();
		}

		$attachment_info = false;
		if ($attachment_id < 0  || !isset($current_conversation['att'][$attachment_id])) {
			$attachment_info = self::create_new_attachment($target_dir, $post_id, $note_id, $user_id, $file_name);
			$attachment_id = 1;
			if (!empty($current_conversation['att'])) {
				$attachment_id = max(array_keys($current_conversation['att'])) + 1;
			}
			$current_conversation['att'][$attachment_id] = $attachment_info;
			$user_notes[$ordinal] = $current_conversation;
			ProgressAllyNote::update_user_note_raw($row_id, $user_notes);
		} else {
			$attachment_info = $current_conversation['att'][$attachment_id];
		}

		$full_path = $target_dir . $attachment_info['path'];
		ProgressAllyBackendShared::write_uploaded_file($full_path);
		return array('notes' => $user_notes, 'attachment-id' => $attachment_id);
	}
	public static function add_attachment_callback() {
		try{
			if (!isset($_POST['index']) || !isset($_POST['file_name']) || !isset($_POST['pid']) || !isset($_POST['nid']) || !isset($_POST['ord']) || !isset($_POST['attr']) ||
				!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'progressally-update-progress-nonce')) {
				throw new Exception('Page outdated. Please refresh and try again.');
			}
			$user_id = ProgressAllyUserProgress::get_user_id();
			if ($user_id <= 0) {
				throw new Exception('File attachment is only available to members. Please login first.');
			}

			$post_id = $_POST['pid'];
			$note_id = $_POST['nid'];
			$ordinal = $_POST['ord'];
			$attachment_id = -1;
			if (isset($_POST['aid'])) {
				$attachment_id = $_POST['aid'];
			}
			$user_note_data = ProgressAllyNote::get_user_notes_raw($post_id, $note_id, $user_id);
			if (empty($user_note_data['note_value']) || !isset($user_note_data['note_value'][$ordinal])) {
				throw new Exception('Invalid note conversation. Please refresh and try again.');
			}

			$add_result = self::add_attachment_to_database($user_note_data['id'], $post_id, $note_id, $user_id, $ordinal, $user_note_data['note_value'], $attachment_id, $_POST['file_name']);

			$static_attributes = ProgressAllyNotesShortcode::parse_ajax_serialized_frontend_param($_POST['attr']);
			$frontend_code = ProgressAllyNotesShortcode::generate_frontend_note_code($post_id, $note_id, $user_id, $add_result['notes'], $static_attributes, true);
			echo json_encode(array('status' => 'success', 'aid' => $add_result['attachment-id'], 'code' => $frontend_code));
		} catch (ProgressAllyFileUploadRetryException $e) {
			echo json_encode(array('status' => 'retry', 'name' => $_POST['file_name']));
		} catch (Exception $e) {
			echo json_encode(array('status' => 'error', 'message' => $e->getMessage()));
		}
		die();
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="Note attachment operation for admin reply">
	public static function add_admin_attachment_callback() {
		try{
			if (!isset($_POST['index']) || !isset($_POST['file_name']) || !isset($_POST['rid']) || !isset($_POST['ord']) || !isset($_POST['uid']) ||
				!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'progressally-update-nonce')) {
				throw new Exception('Page outdated. Please refresh and try again.');
			}
			if (!function_exists('request_filesystem_credentials')) {
				require_once (ABSPATH . '/wp-admin/includes/file.php');
			}
			if (false === ($creds = request_filesystem_credentials('admin.php', '', false, false, null))) {
				throw new Exception('Cannot save file to server. Please contact the site administrator.');
			}
			if (!WP_Filesystem($creds)) {
				throw new Exception('Cannot save file to server. Please contact the site administrator.');
			}
			$user_id = $_POST['uid'];
			if ($user_id <= 0) {
				throw new Exception('Invalid user.');
			}

			$row_id = $_POST['rid'];
			$ordinal = $_POST['ord'];
			$attachment_id = -1;
			if (isset($_POST['aid'])) {
				$attachment_id = $_POST['aid'];
			}
			$user_note_data = ProgressAllyNote::get_note_database($row_id);
			if (empty($user_note_data['note_value']) || !isset($user_note_data['note_value'][$ordinal])) {
				throw new Exception('Invalid note conversation. Please refresh and try again.');
			}
			$post_id = $user_note_data['post_id'];
			$note_id = $user_note_data['note_id'];
			$user_notes = $user_note_data['note_value'];

			$add_result = self::add_attachment_to_database($row_id, $post_id, $note_id, $user_id, $ordinal, $user_notes, $attachment_id, $_POST['file_name']);

			$user_note_data['note_value'] = $add_result['notes'];

			$code = ProgressAllySettingNoteReply::generate_user_note_display($user_note_data);
			echo json_encode(array('status' => 'success', 'aid' => $add_result['attachment-id'], 'code' => $code));
		} catch (ProgressAllyFileUploadRetryException $e) {
			echo json_encode(array('status' => 'retry', 'name' => $_POST['file_name']));
		} catch (Exception $e) {
			echo json_encode(array('status' => 'error', 'message' => $e->getMessage()));
		}
		die();
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="load local server file in chunk">
	public static function readfile_chunked($filename) { 
		$chunksize = 1*(1024*1024);
		$buffer = '';
		$handle = fopen($filename, 'rb');
		if ($handle === false) {
			return false;
		}
		while (!feof($handle)) {
			$buffer = fread($handle, $chunksize);
			echo $buffer;
			ob_flush();
			flush();
		}
		$status = fclose($handle); 
		return $status;
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="serve local file">
	private static $inline_content_type = array('jpg' => 'image/jpeg',
		'jpeg' => 'image/jpeg',
		'png' => 'image/png',
		'tif' => 'image/tiff',
		'tiff' => 'image/tiff',
		'pdf' => 'application/pdf',
		'gif' => 'image/gif');
	private static function serve_content_as_inline($path) {
		$index = strrpos($path, '.');
		if ($index > 0) {
			$extension = substr($path, $index + 1);
			$extension = strtolower($extension);
			if (isset(self::$inline_content_type[$extension])) {
				return self::$inline_content_type[$extension];
			}
		}
		return false;
	}
	private static function serve_local_attachment_file($attachment_info) {
		$target_dir = trailingslashit(WP_CONTENT_DIR);
		$target_dir = trailingslashit($target_dir . self::NOTE_ATTACHMENT_FOLDER);
		$full_path = $target_dir . $attachment_info['path'];

		header('Cache-Control: public');
		header('Content-Description: File Transfer');
		$content_type = self::serve_content_as_inline($attachment_info['path']);
		if (false === $content_type) {
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename="' . $attachment_info['name'] . '"');
			header('Content-Length: ' . filesize($full_path));
		} else {
			header('Content-Type: ' . $content_type);
			header('Content-Disposition: inline; filename="' . $attachment_info['name'] . '"');
		}
		self::readfile_chunked($full_path);
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="serve attachment on the frontend">
	public static function get_attachment_callback() {
		try{
			if (!isset($_REQUEST['pid']) || !isset($_REQUEST['nid']) || !isset($_REQUEST['ord']) || !isset($_REQUEST['aid']) ||
				!isset($_REQUEST['nonce']) ||
				!wp_verify_nonce($_REQUEST['nonce'], 'progressally-attachment-' . $_REQUEST['pid'] . '-' . $_REQUEST['nid'] . '-' . $_REQUEST['ord'] . '-' . $_REQUEST['aid'])) {
				throw new Exception('Page outdated. Please refresh and try again.');
			}
			if (!function_exists('request_filesystem_credentials')) {
				require_once (ABSPATH . '/wp-admin/includes/file.php');
			}
			if (false === ($creds = request_filesystem_credentials('admin.php', '', false, false, null))) {
				throw new Exception('Cannot save file to server. Please contact the site administrator.');
			}
			if (!WP_Filesystem($creds)) {
				throw new Exception('Cannot save file to server. Please contact the site administrator.');
			}
			$user_id = ProgressAllyUserProgress::get_user_id();
			if ($user_id <= 0) {
				throw new Exception('Invalid user.');
			}

			$post_id = $_REQUEST['pid'];
			$note_id = $_REQUEST['nid'];
			$ordinal = $_REQUEST['ord'];
			$attachment_id = $_REQUEST['aid'];

			$user_note = ProgressAllyNote::get_user_notes($post_id, $note_id, $user_id);
			if (empty($user_note) || !isset($user_note[$ordinal])) {
				throw new Exception('Invalid note conversation. Please refresh and try again.');
			}
			$current_conversation = $user_note[$ordinal];
			if (!isset($current_conversation['att']) || !isset($current_conversation['att'][$attachment_id])) {
				throw new Exception('Invalid attachment. Please refresh and try again.');
			}
			$attachment_info = $current_conversation['att'][$attachment_id];
			if (!isset($attachment_info['type']) || 'local' === $attachment_info['type']) {
				self::serve_local_attachment_file($current_conversation['att'][$attachment_id]);
			}
		} catch (Exception $e) {
			echo $e->getMessage();
		}
		die();
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="serve attachment on the backend">
	public static function get_admin_attachment_callback() {
		try{
			if (!isset($_REQUEST['pid']) || !isset($_REQUEST['nid']) || !isset($_REQUEST['ord']) || !isset($_REQUEST['aid']) || !isset($_REQUEST['uid']) ||
				!isset($_REQUEST['nonce']) ||
				!wp_verify_nonce($_REQUEST['nonce'], 'progressally-admin-attachment-' . $_REQUEST['pid'] . '-' . $_REQUEST['nid'] . '-' . $_REQUEST['ord'] . '-' . $_REQUEST['aid'] . '-' . $_REQUEST['uid'])) {
				throw new Exception('Page outdated. Please refresh and try again.');
			}
			if (!function_exists('request_filesystem_credentials')) {
				require_once (ABSPATH . '/wp-admin/includes/file.php');
			}
			if (false === ($creds = request_filesystem_credentials('admin.php', '', false, false, null))) {
				throw new Exception('Cannot save file to server. Please contact the site administrator.');
			}
			if (!WP_Filesystem($creds)) {
				throw new Exception('Cannot save file to server. Please contact the site administrator.');
			}
			$user_id = $_REQUEST['uid'];
			if ($user_id <= 0) {
				throw new Exception('Invalid user.');
			}

			$post_id = $_REQUEST['pid'];
			$note_id = $_REQUEST['nid'];
			$ordinal = $_REQUEST['ord'];
			$attachment_id = $_REQUEST['aid'];

			$user_note = ProgressAllyNote::get_user_notes($post_id, $note_id, $user_id);
			if (empty($user_note) || !isset($user_note[$ordinal])) {
				throw new Exception('Invalid note conversation. Please refresh and try again.');
			}
			$current_conversation = $user_note[$ordinal];
			if (!isset($current_conversation['att']) || !isset($current_conversation['att'][$attachment_id])) {
				throw new Exception('Invalid attachment. Please refresh and try again.');
			}
			$attachment_info = $current_conversation['att'][$attachment_id];
			if (!isset($attachment_info['type']) || 'local' === $attachment_info['type']) {
				self::serve_local_attachment_file($current_conversation['att'][$attachment_id]);
			}
		} catch (Exception $e) {
			echo $e->getMessage();
		}
		die();
	}
	// </editor-fold>
}