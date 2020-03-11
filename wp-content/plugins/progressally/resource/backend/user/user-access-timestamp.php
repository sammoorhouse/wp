<?php
if (!class_exists('ProgressAllyUserAccessTimestamp')) {
	class ProgressAllyUserAccessTimestamp {
		const ACTION_LOGIN = 1;
		const ACTION_ACCESS_PAGE = 2;

		public static function add_actions() {
			add_action('wp_ajax_progressally_track_page_access', array(__CLASS__, 'update_page_access_callback'));
			add_action('wp_login', array(__CLASS__, 'update_login_callback'), 10, 2);
		}

		// <editor-fold defaultstate="collapsed" desc="Database operations">
		public static function initialize_database_names() {
			global $wpdb;

			$wpdb->pa_user_access_timestamp = $wpdb->prefix . 'pa_user_access_timestamp';
		}
		public static function create_database_tables() {
			global $charset_collate, $wpdb;
			return "CREATE TABLE $wpdb->pa_user_access_timestamp (
			  id bigint(20) unsigned NOT NULL auto_increment,
			  user_id bigint(20) unsigned NOT NULL default '0',
			  post_id bigint(20) unsigned NOT NULL default '0',
			  action int(11) NOT NULL default '0',
			  created datetime NOT NULL default '0000-00-00 00:00:00',
			  PRIMARY KEY  (id),
			  KEY user_id (user_id),
			  KEY post_id (post_id),
			  KEY action (action),
			  KEY created (created)
			) $charset_collate;";
		}
		// </editor-fold>

		public static function update_login_callback($user_login, $user) {
			$user_id = $user->ID;
			if ($user_id > 0) {
				global $wpdb;
				$timestamp = ProgressAllyBackendShared::get_sql_time();
				$result = $wpdb->insert($wpdb->pa_user_access_timestamp, array('user_id' => $user_id,
						'action' => self::ACTION_LOGIN,
						'created' => $timestamp
						));
				ProgressAllyProcessEvents::trigger_login_event($user_id);
			}
		}

		public static function update_page_access_callback() {
			$nonce = $_POST['progressally_update_nonce'];
			
			if (!wp_verify_nonce( $nonce, 'progressally-update-progress-nonce')) {
				die();
			}
			if (!isset($_POST['post_id'])) {
				die();
			}
			$user_id = ProgressAllyUserProgress::get_user_id();
			$post_id = intval($_POST['post_id']);
			if ($user_id > 0 && $post_id > 0) {
				global $wpdb;
				$timestamp = ProgressAllyBackendShared::get_sql_time();
				$result = $wpdb->insert($wpdb->pa_user_access_timestamp, array('user_id' => $user_id,
						'post_id' => $post_id,
						'action' => self::ACTION_ACCESS_PAGE,
						'created' => $timestamp
						));
				ProgressAllyProcessEvents::trigger_visit_event($user_id, $post_id);
			}
		}
		
		public static function get_page_access($post_id) {
			global $wpdb;
			$page_access_counts = $wpdb->get_results("SELECT COUNT(id) as count, user_id FROM $wpdb->pa_user_access_timestamp WHERE post_id = $post_id GROUP BY user_id", ARRAY_A);
			if (is_array($page_access_counts)) {
				return count($page_access_counts);
			}
			
			return 0;
		}
		public static function get_all_user_page_access($user_id) {
			if ($user_id <= 0) {
				return array();
			}
			global $wpdb;
			$action = self::ACTION_ACCESS_PAGE;
			$page_access_raw = $wpdb->get_results("SELECT created, post_id FROM $wpdb->pa_user_access_timestamp WHERE user_id = $user_id AND action = $action ORDER BY id DESC", OBJECT);

			return $page_access_raw;
		}
		public static function get_user_page_access($user_id) {
			global $wpdb;
			$action = self::ACTION_ACCESS_PAGE;
			$page_access_raw = $wpdb->get_results("SELECT created, post_id FROM $wpdb->pa_user_access_timestamp WHERE user_id = $user_id AND action = $action ORDER BY created DESC", ARRAY_A);
			if (is_array($page_access_raw)) {
				$page_access = array();
				foreach ($page_access_raw as $row) {
					// Only take the latest access
					if (!isset($page_access[$row['post_id']])) {
						$page_access[$row['post_id']] = $row['created'];
					}
				}
				return $page_access;
			}
			
			return array();
		}
		public static function get_user_page_last_access($user_id) {
			global $wpdb;
			$action = self::ACTION_ACCESS_PAGE;
			$page_access_raw = $wpdb->get_results("SELECT created FROM $wpdb->pa_user_access_timestamp WHERE user_id = $user_id AND action = $action ORDER BY id DESC LIMIT 1");
			if (!empty($page_access_raw)) {
				return $page_access_raw[0]->created;
			}
			
			return false;
		}
		public static function get_user_login_log($user_id) {
			global $wpdb;
			$action = self::ACTION_LOGIN;
			$login_log = $wpdb->get_results("SELECT created FROM $wpdb->pa_user_access_timestamp WHERE user_id = $user_id AND action = $action ORDER BY created DESC", ARRAY_A);
			if (is_array($login_log)) {
				return $login_log;
			}
			return array();
		}
	}
}