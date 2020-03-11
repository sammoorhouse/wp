<?php
class ProgressAllyExternalInteractions {
	// <editor-fold defaultstate="collapsed" desc="action setup">
	public static function add_actions() {
		add_action('accessally_clone_post', array(__CLASS__, 'process_clone_post'), 10, 2);
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="clone ProgressAlly post attributes">
	public static function process_clone_post($source_post_id, $target_post_id) {
		if ($source_post_id <= 0 || $target_post_id <= 0) {
			return;
		}
		$source_objectives = ProgressAllyPostObjective::get_objectives($source_post_id);
		ProgressAllyPostObjective::update_objectives($target_post_id, $source_objectives);
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="Retrieve the list of event names">
	public static function get_event_list() {
		$event_list = array();
		$event_settings = ProgressAllyEvents::get_all_event_settings();
		foreach ($event_settings as $event_id => $event_settings) {
			$event_list[$event_id] = $event_settings['name'];
		}
		return $event_list;
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="Trigger an Event">
	public static function trigger_event($user_id, $event_id) {
		$starting_progress = array('keys' => array(), 'progress' => array(), 'popup' => '');
		$event_settings = ProgressAllyEvents::get_all_event_settings();
		if (isset($event_settings[$event_id])) {
			$event_settings = $event_settings[$event_id];

			if (ProgressAllyProcessEvents::can_event_be_triggered($user_id, $event_id, $event_settings)) {
				$triggered_events = array($event_id => true);

				$starting_progress = ProgressAllyProcessEvents::process_event_action($user_id, $event_id, $event_settings, $triggered_events, $starting_progress);
			}
		}
		return $starting_progress;
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="Trigger an Event">
	public static function get_access_history($user_id) {
		return ProgressAllyUserAccessTimestamp::get_all_user_page_access($user_id);
	}
	// </editor-fold>
}