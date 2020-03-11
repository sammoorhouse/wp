<?php
class ProgressAllyProcessEvents {
	// <editor-fold defaultstate="collapsed" desc="Database creation operations">
	public static function initialize_database_names() {
		global $wpdb;

		$wpdb->pa_event_log = $wpdb->prefix . 'pa_event_log';
	}
	public static function create_event_log_database_table() {
		global $charset_collate, $wpdb;
		return "CREATE TABLE $wpdb->pa_event_log (
			id bigint(20) unsigned NOT NULL auto_increment,
			timestamp datetime NOT NULL default '0000-00-00 00:00:00',
			user_id bigint(20) unsigned NOT NULL default '0',
			event_id bigint(20) unsigned NOT NULL default '0',
			details longtext NOT NULL default '',
			PRIMARY KEY  (id),
			KEY user_id (user_id),
			KEY event_id (event_id)
		) $charset_collate;";
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="Event log database operation">
	private static function get_log_entry_for_user($user_id, $event_id) {
		global $wpdb;

		$query = $wpdb->prepare("SELECT timestamp FROM {$wpdb->pa_event_log} WHERE user_id = %d AND event_id = %d", $user_id, $event_id);
		return $wpdb->get_results($query, OBJECT);
	}
	private static function add_log_entry($user_id, $event_id, $details) {
		global $wpdb;
		$timestamp = ProgressAllyBackendShared::get_sql_time();
		$result = $wpdb->insert($wpdb->pa_event_log, array('user_id' => $user_id,
				'event_id' => $event_id,
				'timestamp' => $timestamp,
				'details' => maybe_serialize($details)
				));
		return $result;
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="decide if an event can be triggered">
	public static function can_event_be_triggered($user_id, $event_id, $event_settings) {
		if ('once' === $event_settings['select-trigger-freq']) {
			$existing_entry = self::get_log_entry_for_user($user_id, $event_id);
			if (count($existing_entry) > 0) {
				return false;
			}
		}
		return true;
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="Process login trigger">
	public static function trigger_login_event($user_id) {
		$all_events = ProgressAllyEvents::get_all_event_settings();
		
		$triggered_events = array();
		foreach ($all_events as $event_id => $event_settings) {
			if ('login' === $event_settings['select-trigger-type']) {
				if (self::can_event_be_triggered($user_id, $event_id, $event_settings)) {
					$triggered_events[$event_id] = true;
					$starting_progress = array('keys' => array(), 'progress' => array(), 'popup' => '');
					self::process_event_action($user_id, $event_id, $event_settings, $triggered_events, $starting_progress);
				}
			}
		}
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="Process page visit trigger">
	public static function trigger_visit_event($user_id, $post_id) {
		$all_events = ProgressAllyEvents::get_all_event_settings();

		$triggered_events = array();
		foreach ($all_events as $event_id => $event_settings) {
			if ('visit' === $event_settings['select-trigger-type'] && in_array($post_id, $event_settings['visit-page'])) {
				if (self::can_event_be_triggered($user_id, $event_id, $event_settings)) {
					$triggered_events[$event_id] = true;
					$starting_progress = array('keys' => array(), 'progress' => array(), 'popup' => '');
					self::process_event_action($user_id, $event_id, $event_settings, $triggered_events, $starting_progress);
				}
			}
		}
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="Process page objective checked trigger">
	private static function evaluate_objective_trigger_condition($post_id, $user_id, $event_settings, $objective_checked) {
		$post_id = intval($post_id);
		foreach ($objective_checked as $tuple) {
			if (intval($tuple[0]) === $post_id) {
				if (in_array($tuple[2], $event_settings['trigger-objective-selection'])) {	// only trigger if the checked objective is one of the required trigger
					$checked_objectives_for_post = ProgressAllyUserProgress::get_checked_objectives($post_id, $user_id);
					foreach ($event_settings['trigger-objective-selection'] as $objective_id) {
						if (!isset($checked_objectives_for_post[$objective_id])) {
							return false;
						}
					}
					return true;
				}
			}
		}
		return false;
	}
	public static function trigger_objective_checked_event($user_id, $objective_checked, $triggered_events, $result_for_frontend) {
		if (empty($objective_checked)) {
			return $result_for_frontend;
		}
		$all_events = ProgressAllyEvents::get_all_event_settings();
		foreach ($all_events as $event_id => $event_settings) {
			if (isset($triggered_events[$event_id])) {
				continue;
			}
			if ('objective' === $event_settings['select-trigger-type'] && $event_settings['page-template-trigger-objective-page'] > 0) {
				if (self::evaluate_objective_trigger_condition($event_settings['page-template-trigger-objective-page'], $user_id, $event_settings, $objective_checked)) {
					if (self::can_event_be_triggered($user_id, $event_id, $event_settings)) {
						$triggered_events[$event_id] = true;
						$result_for_frontend = self::process_event_action($user_id, $event_id, $event_settings, $triggered_events, $result_for_frontend);
					}
				}
			}
		}
		return $result_for_frontend;
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="Process event action">
	public static function process_event_action($user_id, $event_id, $event_settings, $triggered_events, $result_for_frontend) {
		try {
			if ('tag' === $event_settings['select-action-type']) {
				if (!empty($event_settings['action-tag'])) {
					ProgressAllyMembershipUtilities::add_contact_tags($user_id, $event_settings['action-tag']);
					$message = 'Added tag [' . implode(',' , $event_settings['action-tag']) . ']';
					self::add_log_entry($user_id, $event_id, array('data' => $event_settings));
				}
			} elseif ('objective' === $event_settings['select-action-type']) {
				if ($event_settings['page-template-action-objective-page'] > 0) {
					$objectives_to_update = array();
					foreach ($event_settings['action-objective-selection'] as $objective_id) {
						$objectives_to_update[$objective_id] = true;
					}
					$result_for_frontend = ProgressAllyUserProgress::update_event_objective_action($event_settings['page-template-action-objective-page'], $user_id, $objectives_to_update, $triggered_events, $result_for_frontend);
					self::add_log_entry($user_id, $event_id, array('data' => $event_settings));
				}
			}
		} catch (Exception $ex) {
			$message = 'Error: ' . $ex->getMessage();
			self::add_log_entry($user_id, $event_id, array('message' => $message));
		}
		return $result_for_frontend;
	}
	// </editor-fold>
}