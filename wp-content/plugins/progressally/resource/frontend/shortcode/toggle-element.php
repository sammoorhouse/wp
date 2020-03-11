<?php
if (!class_exists('ProgressAllyToggleElementShortcode')) {
	// advanced functionality for storing persistent state data for a user
	class ProgressAllyToggleElementShortcode {
		public static function add_shortcodes() {
			add_shortcode('progressally_checkbox', array(__CLASS__, 'shortcode_progressally_checkbox'));
			add_shortcode('progressally_check_trigger', array(__CLASS__, 'shortcode_check_trigger'));
		}
		// add a checkbox input element that syncs with the backend value
		public static function shortcode_progressally_checkbox($atts){
			extract( shortcode_atts( array(
				'key' => '',
				'id' => '',
				'class' => '',
				'post_id' => '',
			), $atts ) );
			if (empty($key)) {
				return '';
			}
			$meta = ProgressAllyUserProgress::get_user_progress_meta();
			$post_id = intval($post_id);
			$post_id = $post_id <= 0 ? get_the_ID(): $post_id;
			$checked = isset($meta[$post_id]) && isset($meta[$post_id][$key]) && 'true' === $meta[$post_id][$key] ? 'checked="checked"' : '';
			$id_tag = empty($id) ? '' : "id='$id'";
			$key = esc_attr($key);
			return "<input class='progressally-toggle $class' type='checkbox' post-id='$post_id' key='$key' value='true' $checked $id_tag/>";
		}

		// a parameter element that stores information on what to change when the checkbox value changes. It has no visible display
		public static function shortcode_check_trigger($atts){
			extract( shortcode_atts( array(
				'key' => '',
				'selector' => '',
				'unchecked_class' => '',
				'checked_class' => '',
				'post_id' => '',
			), $atts ) );
			if (empty($key) || empty($selector)) {
				return '';
			}
			$post_id = intval($post_id);
			$post_id = $post_id <= 0 ? get_the_ID(): $post_id;
			$key = esc_attr($key);
			$selector = esc_attr($selector);
			return "<div style='display:none' class='progressally-toggle-trigger' post-id='$post_id' key='$key' selector='$selector' unchecked-class='$unchecked_class' checked-class='$checked_class'></div>";
		}
	}
}