<?php
if (!class_exists('ProgressAllyFootprint')) {
	class ProgressAllyFootprint {
		public static function add_shortcodes() {
			add_shortcode('progressally_last_access_time', array(__CLASS__, 'shortcode_last_access_time'));
		}
		public static function shortcode_last_access_time($atts, $content = null) {
			extract( shortcode_atts( array(
				'prefix' => '',
				'user_id' => '',
				'request' => '',
				'context' => 'local'
			), $atts, 'progressally_last_access_time' ) );
			$display_user_id = ProgressAllyBackendShared::get_current_user_id_for_display($user_id, $request, $context, true);
			if ($display_user_id <= 0) {
				return false;
			}
			$last_access_time = ProgressAllyUserAccessTimestamp::get_user_page_last_access($display_user_id);
			if (false === $last_access_time) {
				return false;
			}
			$unix_time = strtotime($last_access_time);
			return '<span class="' . $prefix . 'progressally-last-access-time-iden progressally-last-access-time" progressally-local-time="' . $unix_time . '">' . date(DATE_RFC2822, $unix_time) . '</span>';
		}
	}
}