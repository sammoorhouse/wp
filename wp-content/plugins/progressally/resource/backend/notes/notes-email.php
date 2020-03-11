<?php
class ProgressAllyNotesEmail {
	// <editor-fold defaultstate="collapsed" desc="activation setup / deactivation cleanup">
	public static function do_activation_actions(){
		self::create_schedule_email_event();
	}
	public static function do_deactivation_actions(){
		delete_transient(self::SETTING_KEY_LAST_UPDATED);
		wp_clear_scheduled_hook(self::EMAIL_ACTION_SLUG);
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="Send batched notification">
	const EMAIL_ACTION_SLUG = 'progressally_note_email';
	const SETTING_KEY_LAST_UPDATED = '_progressally_note_notified';
	const SECONDS_IN_A_DAY = 86400;

	private static $default_last_updated = array('admin' => false, 'checked' => false);
	public static function create_schedule_email_event() {
		if (!wp_next_scheduled(self::EMAIL_ACTION_SLUG)) {
			wp_schedule_event(time(), 'hourly', self::EMAIL_ACTION_SLUG);
		}
	}

	public static function add_actions() {
		add_action(self::EMAIL_ACTION_SLUG, array(__CLASS__, 'send_batch_notification_email'));
	}
	public static function send_batch_notification_email() {
		$note_config = ProgressAllySettingNotesConfig::get_settings();
		if ('live' !== $note_config['select-admin-email-freq']) {
			$last_updated = ProgressAllyUtilities::get_settings(self::SETTING_KEY_LAST_UPDATED, self::$default_last_updated);
			$now = time();
			$last_updated['checked'] = $now;
			if ('daily' === $note_config['select-admin-email-freq']) {
				if (false === $last_updated['admin'] || $now - $last_updated['admin'] >= self::SECONDS_IN_A_DAY) {
					self::compose_and_send_batch_admin_email($note_config);
					$last_updated['admin'] = $now;
				}
			}
			ProgressAllyUtilities::set_settings(self::SETTING_KEY_LAST_UPDATED, $last_updated, self::$default_last_updated);
		}
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="Send notification public trigger">
	public static function send_notification_to_admin($source_user_id, $note_value, $post_id, $note_id, $note_meta) {
		if ('yes' === $note_meta['checked-notify-admin']) {
			$note_config = ProgressAllySettingNotesConfig::get_settings();
			if ('live' === $note_config['select-admin-email-freq']) {
				self::compose_and_send_live_admin_notification($source_user_id, $note_value, $note_config, $post_id, $note_id, $note_meta);
			}
		}
	}
	public static function send_notification_to_user($user_id, $note_value, $post_id, $note_meta) {
		if ('yes' === $note_meta['checked-notify-user']) {
			$note_config = ProgressAllySettingNotesConfig::get_settings();
			self::compose_and_send_user_notification($user_id, $note_value, $post_id, $note_meta, $note_config);
		}
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="compose and send user email">
	private static function customize_user_email($source, $user_id, $note_value, $userdata, $post_id) {
		if (strpos($source, '{[post-link]}')) {
			$post_link = get_permalink($post_id);
			$post_name = ProgressAllyBackendShared::get_post_name($post_id);
			$source = str_replace('{[post-link]}', '<a target="_blank" href="' . esc_attr($post_link) . '">' . esc_html($post_name) . '</a>', $source);
		}
		if (strpos($source, '{[post-raw-link]}')) {
			$post_link = get_permalink($post_id);
			$source = str_replace('{[post-raw-link]}', esc_attr($post_link), $source);
		}
		if (strpos($source, '{[first-name]}')) {
			$source = str_replace('{[firstname]}', esc_attr($userdata->first_name), $source);
		}
		if (strpos($source, '{[last-name]}')) {
			$source = str_replace('{[last-name]}', esc_attr($userdata->last_name), $source);
		}
		if (strpos($source, '{[user-login]}')) {
			$source = str_replace('{[user-login]}', esc_attr($userdata->user_login), $source);
		}
		if (strpos($source, '{[note-details]}')) {
			$note_detail = self::generate_user_note_display($note_value);
			$source = str_replace('{[note-details]}', $note_detail, $source);
		}
		return $source;
	}
	private static function compose_and_send_user_notification($user_id, $note_value, $post_id, $note_meta, $note_config) {
		if ('yes' === $note_meta['checked-custom-email']) {
			$email_subject = $note_meta['custom-email-subject'];
			$email_content = $note_meta['custom-email-content'];
		} else {
			$email_subject = $note_config['user-email-subject'];
			$email_content = $note_config['user-email-content'];
		}
		$userdata = get_userdata($user_id);
		if ($userdata) {
			$email_subject = self::customize_user_email($email_subject, $user_id, $note_value, $userdata, $post_id);
			$email_content = self::customize_user_email($email_content, $user_id, $note_value, $userdata, $post_id);

			$headers = array('Content-Type: text/html; charset=UTF-8');
			wp_mail($userdata->user_email, $email_subject, $email_content, $headers);
		}
	}
	const USER_EMAIL_DISPLAY_TEMPLATE = <<<'EOT'
<tr>
  <td>
	<table cellpadding="0" cellspacing="0" border="0" align="center" style="width:100%;border:1px solid #e4e4e4">
	  <tbody>
		<tr>
		  <td style="font-size:1px;line-height:1px" colspan="3" height="20">&nbsp;</td>
		</tr>
		<tr>
		  <td style="width:10px;display:block">&nbsp;</td>
		  <td>
			<table cellpadding="0" cellspacing="0" border="0" align="center" style="width:100%">
			  <tbody>
				{[existing-notes]}
			  </tbody>
			</table>
		  </td>
		  <td style="width:10px;display:block">&nbsp;</td>
		</tr>
		<tr>
		  <td style="font-size:1px;line-height:1px" colspan="3" height="20">&nbsp;</td>
		</tr>
	  </tbody>
	</table>
  </td>
</tr>
<tr>
  <td style="font-size:1px;line-height:1px" height="20">&nbsp;</td>
</tr>
EOT;
	const USER_EMAIL_ADMIN_NOTE_TEMPLATE = <<<'EOT'
<tr>
  <td style="font-size:1px;line-height:1px" height="10">&nbsp;</td>
</tr>
<tr>
  <td style="font-size:12px;color:#000000"><div style="padding:10px;border-radius:5px;border:1px solid #e4e4e4;width:90%;margin-left:5%;background-color:#f8f8f8">{[note]}</div></td>
</tr>
EOT;
	const USER_EMAIL_ADMIN_NOTE_LATEST_TEMPLATE = <<<'EOT'
<tr>
  <td style="font-size:1px;line-height:1px" height="10">&nbsp;</td>
</tr>
<tr>
  <td style="font-size:12px;color:#000000"><div style="padding:10px;border-radius:5px;border:1px solid #e4e4e4;width:90%;margin-left:5%;background-color:#ffff99">{[note]}</div></td>
</tr>
EOT;
	const USER_EMAIL_USER_NOTE_TEMPLATE = <<<'EOT'
<tr>
  <td style="font-size:1px;line-height:1px" height="10">&nbsp;</td>
</tr>
<tr>
  <td style="font-size:12px;color:#000000"><div style="padding:10px;border-radius:5px;border:1px solid #e4e4e4;width:90%">{[note]}</div></td>
</tr>
EOT;
	const USER_EMAIL_USER_NOTE_LATEST_TEMPLATE = <<<'EOT'
<tr>
  <td style="font-size:1px;line-height:1px" height="10">&nbsp;</td>
</tr>
<tr>
  <td style="font-size:12px;color:#000000"><div style="padding:10px;border-radius:5px;border:1px solid #e4e4e4;width:90%;background-color:#ffff99">{[note]}</div></td>
</tr>
EOT;
	private static function generate_user_note_display($note_values) {
		$code = self::USER_EMAIL_DISPLAY_TEMPLATE;

		$exist_notes = '';
		$num_notes = count($note_values);
		foreach ($note_values as $ordinal => $val) {
			if ($ordinal === $num_notes - 1) {	// the latest message
				$temp_note = self::USER_EMAIL_USER_NOTE_LATEST_TEMPLATE;
				if ($val['s'] == 1) {
					$temp_note = self::USER_EMAIL_ADMIN_NOTE_LATEST_TEMPLATE;
				}
			} else {
				$temp_note = self::USER_EMAIL_USER_NOTE_TEMPLATE;
				if ($val['s'] == 1) {
					$temp_note = self::USER_EMAIL_ADMIN_NOTE_TEMPLATE;
				}
			}
			if (isset($val['f']) && 'html' === $val['f']) {
				$exist_notes .= str_replace('{[note]}', esc_html($val['v']), $temp_note);
			} else {
				$exist_notes .= str_replace('{[note]}', $val['v'], $temp_note);
			}
		}
		$code = str_replace('{[existing-notes]}', $exist_notes, $code);
		return $code;
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="compose and send admin email">
	const ADMIN_EMAIL_DISPLAY_TEMPLATE = <<<'EOT'
<tr>
  <td>
	<table cellpadding="0" cellspacing="0" border="0" align="center" style="width:100%;border:1px solid #e4e4e4">
	  <tbody>
		<tr>
		  <td style="font-size:1px;line-height:1px" colspan="3" height="20">&nbsp;</td>
		</tr>
		<tr>
		  <td style="width:10px;display:block">&nbsp;</td>
		  <td>
			<table cellpadding="0" cellspacing="0" border="0" align="center" style="width:100%">
			  <tbody>
				<tr>
				  <td style="font-size:12px;color:#000000">{[post-name]} - {[note-name]}</td>
				</tr>
				<tr>
				  <td style="font-size:1px;line-height:1px" height="30">&nbsp;</td>
				</tr>
				<tr>
				  <td style="font-size:12px;color:#000000">{[name]} Added</td>
				</tr>
				{[existing-notes]}
			  </tbody>
			</table>
		  </td>
		  <td style="width:10px;display:block">&nbsp;</td>
		</tr>
		<tr>
		  <td style="font-size:1px;line-height:1px" colspan="3" height="20">&nbsp;</td>
		</tr>
	  </tbody>
	</table>
  </td>
</tr>
<tr>
  <td style="font-size:1px;line-height:1px" height="20">&nbsp;</td>
</tr>
EOT;
	const ADMIN_EMAIL_ADMIN_NOTE_TEMPLATE = <<<'EOT'
<tr>
  <td style="font-size:1px;line-height:1px" height="10">&nbsp;</td>
</tr>
<tr>
  <td style="font-size:12px;color:#000000"><div style="padding:10px;border-radius:5px;border:1px solid #e4e4e4;width:90%;margin-left:5%;background-color:#f8f8f8">{[note]}</div></td>
</tr>
EOT;
	const ADMIN_EMAIL_ADMIN_NOTE_LATEST_TEMPLATE = <<<'EOT'
<tr>
  <td style="font-size:1px;line-height:1px" height="10">&nbsp;</td>
</tr>
<tr>
  <td style="font-size:12px;color:#000000"><div style="padding:10px;border-radius:5px;border:1px solid #e4e4e4;width:90%;margin-left:5%;background-color:#ffff99">{[note]}</div></td>
</tr>
EOT;
	const ADMIN_EMAIL_USER_NOTE_TEMPLATE = <<<'EOT'
<tr>
  <td style="font-size:1px;line-height:1px" height="10">&nbsp;</td>
</tr>
<tr>
  <td style="font-size:12px;color:#000000"><div style="padding:10px;border-radius:5px;border:1px solid #e4e4e4;width:90%">{[note]}</div></td>
</tr>
EOT;
	const ADMIN_EMAIL_USER_NOTE_LATEST_TEMPLATE = <<<'EOT'
<tr>
  <td style="font-size:1px;line-height:1px" height="10">&nbsp;</td>
</tr>
<tr>
  <td style="font-size:12px;color:#000000"><div style="padding:10px;border-radius:5px;border:1px solid #e4e4e4;width:90%;background-color:#ffff99">{[note]}</div></td>
</tr>
EOT;
	private static function generate_note_display($user_id, $note_values, $post_id, $note_id, $note_meta) {
		$code = self::ADMIN_EMAIL_DISPLAY_TEMPLATE;
		$name = 'Unknown User';

		$userdata = get_userdata($user_id);
		if ($userdata) {
			$name = $userdata->first_name . ' ' . $userdata->last_name;
		}
		$code = str_replace('{[name]}', esc_html($name), $code);

		$post_name = ProgressAllyBackendShared::get_post_name($post_id);
		$code = str_replace('{[post-name]}', esc_html($post_name), $code);

		if (false === $note_meta) {
			$note_meta = ProgressAllyNote::get_note_meta($post_id, $note_id);
		}
		$note_name = $note_meta['name'];
		$code = str_replace('{[note-name]}', esc_html($note_name), $code);

		$exist_notes = '';
		$num_notes = count($note_values);
		foreach ($note_values as $ordinal => $val) {
			if ($ordinal === $num_notes - 1) {	// the latest message
				$temp_note = self::ADMIN_EMAIL_USER_NOTE_LATEST_TEMPLATE;
				if ($val['s'] == 1) {
					$temp_note = self::ADMIN_EMAIL_ADMIN_NOTE_LATEST_TEMPLATE;
				}
			} else {
				$temp_note = self::ADMIN_EMAIL_USER_NOTE_TEMPLATE;
				if ($val['s'] == 1) {
					$temp_note = self::ADMIN_EMAIL_ADMIN_NOTE_TEMPLATE;
				}
			}
			if (isset($val['f']) && 'html' === $val['f']) {
				$exist_notes .= str_replace('{[note]}', esc_html($val['v']), $temp_note);
			} else {
				$exist_notes .= str_replace('{[note]}', $val['v'], $temp_note);
			}
		}
		$code = str_replace('{[existing-notes]}', $exist_notes, $code);
		return $code;
	}

	const ADMIN_TEMPLATE = <<<'EOT'
<table cellpadding="0" cellspacing="0" border="0" align="center" style="width:100%;max-width:600px">
	<tbody>
    <tr>
      <td style="font-size:1px;line-height:1px" height="10">&nbsp;</td>
    </tr>
	<tr>
      <td>You can view and reply to the notes in the <a href="{[note-reply-link]}">ProgressAlly Notes Center</a></td>
	</tr>
    <tr>
      <td style="font-size:1px;line-height:1px" height="20">&nbsp;</td>
    </tr>
    <tr>
      <td style="color:#000000;font-size:24px">Private Note Details</td>
    </tr>
    <tr>
      <td style="font-size:1px;line-height:1px" height="20">&nbsp;</td>
    </tr>
	{[note-details]}
	<tr>
      <td>You can view and reply to the notes in the <a href="{[note-reply-link]}">ProgressAlly Notes Center</a></td>
	</tr>
  </tbody>
</table>
EOT;
	
	const ADDITIONAL_COUNT_TEMPLATE = <<<'EOT'
<tr>
  <td style="color:#000000;font-size:18px">and {[count]} more notes.</td>
</tr>
<tr>
  <td style="font-size:1px;line-height:1px" height="20">&nbsp;</td>
</tr>
EOT;
	const MAX_NOTE_DETAIL_TO_SHOW = 3;
	private static function compose_and_send_batch_admin_email($note_config) {
		if (empty($note_config['admin-email-address'])) {
			return;
		}
		$filter_string = 'WHERE status=0';
		$raw_data = ProgressAllyNote::get_all_user_notes($filter_string, false, 3);
		$total = ProgressAllyNote::get_all_user_note_count($filter_string);
		$subject = $note_config['admin-email-prefix'] . 'You have ' . $total . ' notes that need to be replied to';
		$content = self::ADMIN_TEMPLATE;
		if (strpos($content, '{[note-reply-link]}')) {
			$note_reply_link = admin_url( 'admin.php?page=' . ProgressAllySettingNotes::SETTING_KEY );
			$content = str_replace('{[note-reply-link]}', esc_attr($note_reply_link), $content);
		}
		if (strpos($content, '{[note-details]}')) {
			$note_detail = '';
			foreach ($raw_data as $note) {
				$note_detail .= self::generate_note_display($note['user_id'], $note['note_value'], $note['post_id'], $note['note_id'], false);
			}
			if ($total > count($raw_data)) {
				$note_detail .= str_replace('{[count]}', $total - count($raw_data), self::ADDITIONAL_COUNT_TEMPLATE);
			}
			$content = str_replace('{[note-details]}', $note_detail, $content);
		}

		$headers = array('Content-Type: text/html; charset=UTF-8');
		wp_mail($note_config['admin-email-address'], $subject, $content, $headers);
	}
	private static function compose_and_send_live_admin_notification($user_id, $note_value, $note_config, $post_id, $note_id, $note_meta) {
		if (empty($note_config['admin-email-address'])) {
			return;
		}

		$name = 'Unknown User';

		$userdata = get_userdata($user_id);
		if ($userdata) {
			$name = $userdata->first_name . ' ' . $userdata->last_name;
		}
		$subject = $note_config['admin-email-prefix'] . $name . ' created a note that needs replying';
		$content = self::ADMIN_TEMPLATE;
		if (strpos($content, '{[note-reply-link]}')) {
			$note_reply_link = admin_url( 'admin.php?page=' . ProgressAllySettingNotes::SETTING_KEY );
			$content = str_replace('{[note-reply-link]}', esc_attr($note_reply_link), $content);
		}
		if (strpos($content, '{[note-details]}')) {
			$note_detail = self::generate_note_display($user_id, $note_value, $post_id, $note_id, $note_meta);
			$content = str_replace('{[note-details]}', $note_detail, $content);
		}

		$headers = array('Content-Type: text/html; charset=UTF-8');
		wp_mail($note_config['admin-email-address'], $subject, $content, $headers);
	}
	// </editor-fold>
}