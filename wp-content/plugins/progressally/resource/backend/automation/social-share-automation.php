<?php
class ProgressAllySocialShareAutomation {
	// <editor-fold defaultstate="collapsed" desc="action setup">
	public static function add_actions() {
		add_action('template_redirect', array(__CLASS__, 'process_share_callback'), 0);
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="process social share post callback">
	const CALLBACK_SOURCE = "progressally-share";
	const CALLBACK_POST = "progressally-post";
	const CALLBACK_USER = "progressally-user";
	public static function process_share_callback() {
		if (isset($_REQUEST[self::CALLBACK_SOURCE]) && isset($_REQUEST[self::CALLBACK_POST]) && isset($_REQUEST[self::CALLBACK_USER])) {
			if ('fb' === $_REQUEST[self::CALLBACK_SOURCE]) {
				self::process_fb_share_callback();
			}
			exit;
		}
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="Generate FB redirect link (used for share automation tagging)">
	public static function generate_facebook_redirect_link($post_id, $user_id) {
		$redirect = add_query_arg(array(
			self::CALLBACK_SOURCE => 'fb',
			self::CALLBACK_POST => $post_id,
			self::CALLBACK_USER => self::encrypt_user_id($user_id),
			), site_url());
		return $redirect;
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="process FB callback">
	const FB_FINAL_URL = "https://www.facebook.com/";
	const ADD_TAG_ERROR_KEY = 'progressally-fb-add-tag-error';
	private static function process_fb_share_callback() {
		// always add the tag, because it cannot be checked that the user actually submitted a post or just cancelled
		$post_id = $_REQUEST[self::CALLBACK_POST];
		$post_meta = ProgressAllyTaskDefinition::get_post_progress_meta($post_id);
		if (is_array($post_meta) && !empty($post_meta['fb-automation-tag'])) {
			$request_user_id = intval(self::decrypt_user_id($_REQUEST[self::CALLBACK_USER]));
			$current_user_id = ProgressAllyUserProgress::get_user_id();
			if ($request_user_id === $current_user_id) {
				try {
					ProgressAllyMembershipUtilities::add_contact_tag($current_user_id, $post_meta['fb-automation-tag']);
				} catch (Exception $e) {
					$message = time() . ': ' . $e->getMessage() . '=>' . $e->getTraceAsString() . ';';
					if (!add_option(self::ADD_TAG_ERROR_KEY, $message, '', 'no')) {
						update_option(self::ADD_TAG_ERROR_KEY, $message);
					}
				}
			}
		}
		wp_redirect(self::FB_FINAL_URL);
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="process user id in REQUEST">
	public static function encrypt_user_id($user_id) {
		$user = get_userdata($user_id);
		if ($user) {
			$md5 = md5($user->user_login . $user_id);
		} else {
			$md5 = md5($user_id);
		}
		return substr($md5, 0, 8) . $user_id;
	}
	private static function decrypt_user_id($request) {
		if (strlen($request) < 8) {
			return false;
		}
		$user_id = substr($request, 8);
		$user_id = intval($user_id);
		if ($user_id <= 0) {
			return false;
		}
		$user = get_userdata($user_id);
		if ($user) {
			$md5 = md5($user->user_login . $user_id);
			if (substr($md5, 0, 8) === substr($request, 0, 8)) {
				return $user_id;
			}
		}
		return false;
	}
	// </editor-fold>
}