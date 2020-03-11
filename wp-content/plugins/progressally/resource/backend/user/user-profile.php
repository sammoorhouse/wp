<?php
if (!class_exists('ProgressAllyUserProfile')) {
	class ProgressAllyUserProfile {
		public static function add_actions() {
			if (is_admin()) {
				if (ProgressAllySettingLicense::$progressally_enabled) {
					add_action('show_user_profile', array(__CLASS__, 'show_user_progress'));
					add_action('edit_user_profile', array(__CLASS__, 'show_user_progress'));
					
					add_action('wp_ajax_progressally_export_user_progress', array(__CLASS__, 'process_user_progress_export'));
				}
			}
		}
		
		public static function show_user_progress($user) {
			$code = '';
			if (self::has_admin_privilege()) {
				$user_progress = ProgressAllyUserProgress::get_user_progress($user->ID);
				$all_posts = ProgressAllyBackendShared::get_all_valid_posts_for_selection();
				$user_progress_code = '';
				foreach ($all_posts as $post_row) {
					$post_id = $post_row['ID'];
					if (isset($user_progress[$post_id]) && $user_progress[$post_id] > 0) {
						$user_progress_code .= '<tr class="progressally-progress-table-content-row"><td><a target="_blank" href="' . esc_attr(get_edit_post_link($post_id, 'display')) .
							'">' . esc_html($post_row['post_title']) . ' (' . $post_id . ')</a></td><td>' . number_format($user_progress[$post_id] * 100, 0) . '%</td></tr>';
					}
				}
				$code = file_get_contents(dirname(__FILE__) . '/user-profile-display.php');
				$code = str_replace('{{completed-status}}', $user_progress_code, $code);

				$export_link = self::generate_user_progress_url($user->ID);
				$code = str_replace('{{user-progress-export-link}}', $export_link, $code);

				$user_page_access = ProgressAllyUserAccessTimestamp::get_user_page_access($user->ID);
				$user_page_access_code = '';
				foreach ($user_page_access as $post_id => $time) {
					$post_id = intval($post_id);
					$edit_link = get_edit_post_link($post_id, 'display');
					if ($edit_link !== null) {
						$title = get_the_title($post_id);
						$user_page_access_code .= '<tr class="progressally-progress-table-content-row"><td><a target="_blank" href="' . esc_attr($edit_link) .
							'">' . esc_html($title) . ' (' . $post_id . ')</a></td><td>' . $time . '</td></tr>';
					}
				}
				$code = str_replace('{{user-page-access}}', $user_page_access_code, $code);
				
				$user_login_log = ProgressAllyUserAccessTimestamp::get_user_login_log($user->ID);
				$user_login_log_code = '';
				foreach ($user_login_log as $entry) {
					$user_login_log_code .= '<tr class="progressally-progress-table-content-row"><td>' . $entry['created'] . '</td></tr>';
				}
				$code = str_replace('{{user-login-log}}', $user_login_log_code, $code);
			}
			echo $code;
		}

		public static function has_admin_privilege($user_id = null) {
			if (is_numeric($user_id)) {
				$user = get_userdata($user_id);
			} else {
				$user = wp_get_current_user();
			}
			if (empty($user) || empty($user->data) || $user->ID <= 0) {
				return false;
			}

			return in_array('administrator', (array) $user->roles ) || in_array('super admin', (array) $user->roles );
		}
		
		// <editor-fold defaultstate="collapsed" desc="export user progress">
		private static function generate_user_progress_url($user_id) {
			$export_nonce = wp_create_nonce("progressally-user-progress-export");
			return add_query_arg(array('export-user-progress-nonce' => $export_nonce,
										'user-id' => $user_id,
										'action' => 'progressally_export_user_progress'
									), admin_url('admin-ajax.php'));
		}

		public static function process_user_progress_export() {
			if (isset($_REQUEST['user-id']) && isset($_REQUEST['export-user-progress-nonce']) && wp_verify_nonce($_REQUEST['export-user-progress-nonce'], "progressally-user-progress-export")) {
				$user_id = intval($_REQUEST['user-id']);
				set_time_limit(0);

				$filename = "ProgressAlly User Progress - UserID ". $user_id . ".csv";
				header('Content-Type: text/csv');
				header('Content-Disposition: attachment; filename="' . $filename . '"');

				// Export header line
				echo "Post ID,Title,Progress" . PHP_EOL;

				// Export progress
				$user_progress = ProgressAllyUserProgress::get_user_progress($user_id);
				$post_title_list = ProgressAllyUtilities::get_post_titles();
				foreach ($user_progress as $post_id => $progress) {
					if ($progress > 0) {
						$title = isset($post_title_list[$post_id]) ? $post_title_list[$post_id] : '';
						echo $post_id .  ',' . ProgressAllyUtilities::escape_string_csv($title) . ',' . round($progress * 100, 2) . '%' . PHP_EOL;
					}
				}
				exit;
			}
		}
		// </editor-fold>
	}
}