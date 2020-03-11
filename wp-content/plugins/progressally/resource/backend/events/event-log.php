<?php
if (!class_exists('ProgressAllyEventLog')) {
	class ProgressAllyEventLog{
		const NUM_LOG_PER_PAGE = 20;
		public static function add_actions() {
			add_action('wp_ajax_progressally_get_event_log', array(__CLASS__, 'get_log_callback'));
		}

		// <editor-fold defaultstate="collapsed" desc="Backend setting display">
		public static function show_log_settings() {
			$code = file_get_contents(dirname(__FILE__) . '/event-log-display.php');
			return $code;
		}
		// </editor-fold>

		// <editor-fold defaultstate="collapsed" desc="database log retrieval">
		private static function create_log_filter($filter_data) {
			$event_id = $filter_data['event-id'];
			$user_id = $filter_data['user-id'];
			$filters = array();
			if (!empty($event_id)) {
				$filters []= 'event_id = ' . $event_id;
			}
			if (!empty($user_id)) {
				$filters []= 'user_id = ' . $user_id;
			}
			$query_string = '';
			if (!empty($filters)) {
				$query_string = ' WHERE ' . implode(' AND ', $filters);
			}
			return $query_string;
		}
		private static function get_log_entries($start, $max_num, $filter) {
			global $wpdb;

			$query = "SELECT * FROM {$wpdb->pa_event_log}";
			$query .= $filter;
			$query .= ' ORDER BY id DESC';
			$query .= " LIMIT $start,$max_num";
			$result = $wpdb->get_results($query, ARRAY_A);
			return $result;
		}
		private static function get_log_count($filter) {
			global $wpdb;

			$query = "SELECT COUNT(id) AS count FROM {$wpdb->pa_event_log}";
			$query .= $filter;
			$result = $wpdb->get_row($query);

			return intval($result->count);
		}
		// </editor-fold>

		// <editor-fold defaultstate="collapsed" desc="log display code generation">
		private static $log_column_mapping = array('id' => 'ID', 'timestamp' => 'Date', 'user_id' => 'User ID', 'event_id' => 'Event ID');
		private static function generate_log_header_code() {
			$header = '';
			foreach (self::$log_column_mapping as $key => $value) {
				$header .= '<th class="progressally-event-log-' . $key . '-col">' . esc_html($value) . '</th>';
			}
			$header .= '<th class="progressally-event-log-trigger-col">Trigger</th>';
			$header .= '<th class="progressally-event-log-action-col">Action</th>';
			return $header;
		}
		private static function generate_trigger_description($event_settings) {
			$desc = '';
			if ('login' === $event_settings['select-trigger-type']) {
				$desc .= 'Login';
			} elseif ('visit' === $event_settings['select-trigger-type']) {
				$desc .= 'Visit page [' . implode(', ', $event_settings['visit-page']) . ']';
			} elseif ('objective' === $event_settings['select-trigger-type']) {
				$desc .= 'Completed objective [' . implode(', ', $event_settings['trigger-objective-selection']) . '] on page [' . $event_settings['page-template-trigger-objective-page'] . ']';
			} elseif ('accessally' === $event_settings['select-trigger-type']) {
				$desc .= 'AccessAlly Custom Operation';
			}
			return $desc;
		}
		private static function generate_action_description($event_settings) {
			$desc = '';
			if ('tag' === $event_settings['select-action-type']) {
				$desc .= 'Apply tag [' . implode(', ', $event_settings['action-tag']) . ']';
			} elseif ('objective' === $event_settings['select-action-type']) {
				$desc .= 'Check objective [' . implode(', ', $event_settings['action-objective-selection']) . '] on page [' . $event_settings['page-template-action-objective-page'] . ']';
			}
			return $desc;
		}
		private static function generate_log_data_row_code($row) {
			$code = '<tr class="progressally-event-log-content-row">';
			foreach (self::$log_column_mapping as $key => $value) {
				$entry = '';
				if (isset($row[$key])) {
					$entry = $row[$key];
				}
				$code .= '<td class="progressally-event-log-' . $key . '-col">' . $entry . '</td>';
			}
			$details = maybe_unserialize($row['details']);
			if (isset($details['data'])) {
				$event_settings = $details['data'];
				$code .= '<td class="progressally-event-log-trigger-col">' . self::generate_trigger_description($event_settings) . '</td>';
				$code .= '<td class="progressally-event-log-action-col">' . self::generate_action_description($event_settings) . '</td>';
			} else {
				$code .= '<td class="progressally-event-log-trigger-col"></td>';
				$code .= '<td class="progressally-event-log-action-col"></td>';
			}
			$code .= '</tr>';
			return $code;
		}
		// </editor-fold>

		// <editor-fold defaultstate="collapsed" desc="Log retrieval">
		public static function get_log_callback() {
			$result = array('status' => 'error', 'message' => '');
			try {
				if (!isset($_POST['info']) || !isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'progressally-update-nonce')) {
					$result['message'] = "Unable to validate request identify. Please refresh the page and make sure you are logged in as an administrator.";
					echo json_encode($result);
					die();
				}
				$data_array = ProgressAllyBackendShared::convert_setting_string_to_array($_POST['info']);

				$page = $data_array['page'];

				$query = self::create_log_filter($data_array);
				$total = self::get_log_count($query);
				$max_page = max(1, ceil($total / self::NUM_LOG_PER_PAGE));
				$page = max(1, min($page, $max_page));
				$raw_data = self::get_log_entries(($page - 1) * self::NUM_LOG_PER_PAGE, self::NUM_LOG_PER_PAGE, $query);

				$header = self::generate_log_header_code();
				$data = '';
				if (is_array($raw_data) && count($raw_data) > 0) {
					foreach ($raw_data as $row) {
						$data .= self::generate_log_data_row_code($row);
					}
				}
				$result['page'] = $page;
				$result['max'] = $max_page;
				$result['header'] = $header;
				$result['data'] = $data;
				$result['status'] = 'success';
			} catch (Exception $ex) {
				$result = array('status' => 'error', 'message' => $ex->getMessage());
			}
			echo json_encode($result);
			die();
		}
		// </editor-fold>
	}
}