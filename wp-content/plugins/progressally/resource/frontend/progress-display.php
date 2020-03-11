<?php
if (!class_exists('ProgressAllyProgressDisplay')) {
	class ProgressAllyProgressDisplay {
		const PROGRESS_PIE_CHART_TEMPLATE = '<div class="{{prefix}}progressally_progress_pie_chart" {{update-attr}} style="width:{{size}}px;height:{{size}}px;position:relative;border-radius:{{half-size}}px;">
<div style="position:absolute;top:0;left:0;width:100%;height:100%;clip:rect(0 {{size}}px {{size}}px {{half-size}}px);">
<div class="progressally_progress_pie_overlay progressally_progress_pie_right" style="position:absolute;top:0;left:0;width:100%;height:100%;{{background}}clip:rect(0 {{size}}px {{half-size}}px 0);border-radius:50%;-moz-transform:rotate({{right-angle}}deg);-webkit-transform:rotate({{right-angle}}deg);transform:rotate({{right-angle}}deg);">
</div>
</div>
<div style="position:absolute;top:0;left:0;width:100%;height:100%;clip:rect(0 {{half-size}}px {{size}}px 0);">
<div class="progressally_progress_pie_overlay progressally_progress_pie_left" style="position:absolute;top:0;left:0;width:100%;height:100%;{{background}}clip:rect({{half-size}}px {{size}}px {{size}}px 0);border-radius:50%;-moz-transform:rotate({{left-angle}}deg);-webkit-transform:rotate({{left-angle}}deg);transform:rotate({{left-angle}}deg);">
</div>
</div>
<div class="progressally_progress_percentage progressally_progress_pie_percentage" style="position:absolute;margin:0;width:{{half-size}}px;word-wrap:normal;height:{{half-size}}px;line-height:{{half-size}}px;border-radius:{{4tr-size}}px;font-size:{{5th-size}}px;top:{{4tr-size}}px;left:{{4tr-size}}px;">{{percentage}}</div>
<div class="progressally_progress_pie_border_overlay" style="position:absolute;top:0;width:100%;height:100%;border-radius:50%;-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;"></div>
</div>';

		const PROGRESS_BAR_TEMPLATE = '<div class="{{prefix}}progressally_progress_bar_chart" {{update-attr}} style="{{width}}{{height}}position:relative;">
<div class="progressally_progress_bar_overlay" style="position:absolute;top:0;left:0;width:{{percentage}};height:100%;{{background}}"></div>
<div class="progressally_progress_percentage progressally_progress_bar_percentage" style="position:absolute;{{font-data}}">{{percentage}}</div>
<div class="progressally_progress_bar_border_overlay" style="position:absolute;top:0;width:100%;height:100%;-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;"></div>
</div>';

		const PROGRESS_TEXT_TEMPLATE = '<span progressally-text-update="{{post-id}}">{{percentage}}</span>';

		const PROGRESS_COMPLETED_COUNT_TEMPLATE = '<span progressally-count-update="{{post-id}}">{{count}}</span>';

		public static function add_shortcodes() {
			add_shortcode( 'progressally_objectives', array(__CLASS__, 'shortcode_progressally_objectives'));
			add_shortcode( 'progressally_progress_overview', array(__CLASS__, 'shortcode_progressally_objectives'));
			add_shortcode( 'progressally_progress_text', array(__CLASS__, 'shortcode_progressally_progress_text'));
			add_shortcode( 'progressally_progress_pie_chart', array(__CLASS__, 'shortcode_progressally_progress_pie_chart'));
			add_shortcode( 'progressally_progress_bar', array(__CLASS__, 'shortcode_progressally_progress_bar'));
			add_shortcode( 'progressally_objective_count', array(__CLASS__, 'shortcode_progressally_objective_count'));
			add_shortcode( 'progressally_objective_completed_count', array(__CLASS__, 'shortcode_progressally_objective_completed_count'));
		}
		private static $objective_list_ordinal = 0;
		public static function shortcode_progressally_objectives($atts) {
			extract( shortcode_atts( array(
				'prefix' => '',
				'post_id' => '',
				'objective_id' => 'all',
				'user_id' => '',
				'request' => '',
				'context' => 'local'
			), $atts, 'progressally_objectives' ) );
			$post_id = intval($post_id);
			$post_id = $post_id <= 0 ? get_the_ID(): $post_id;
			$objectives = ProgressAllyTaskDefinition::get_post_objectives_in_order($post_id);

			$objective_ids_to_show = true;
			if ('all' !== $objective_id) {
				$objective_ids_to_show = array();
				$to_show_ids = explode(',', $objective_id);
				foreach ($to_show_ids as $to_show_id) {
					$objective_ids_to_show []= intval($to_show_id);
				}
			}
			++self::$objective_list_ordinal;
			$ordinal = self::$objective_list_ordinal;
			if (!empty($objectives)) {
				$current_user_id = ProgressAllyUserProgress::get_user_id();
				$current_user_id = intval($current_user_id);
				$display_user_id = ProgressAllyBackendShared::get_current_user_id_for_display($user_id, $request, $context, $current_user_id);
				$display_user_id = intval($display_user_id);

				$checked_objectives = ProgressAllyUserProgress::get_checked_objectives($post_id, $display_user_id);
				$post_id = intval($post_id);
				$code = "<table class='" . $prefix . "objective-table progressally-objective-table' progressally-post-id='$post_id'><tbody>";
				$order = 0;
				$can_edit = ($current_user_id === $display_user_id);
				foreach($objectives as $id => $value) {
					++$order;

					$to_show = false;
					if (true === $objective_ids_to_show || in_array($id, $objective_ids_to_show)) {
						$to_show = true;
					}

					$code .= self::generate_objective_table_row($ordinal, $order, $id, $post_id, $value, $checked_objectives, $to_show, $prefix, $can_edit);
				}
				$code .= "</tbody></table>";
				return $code;
			}
			return '';
		}
		public static function generate_objective_table_row($ordinal, $order, $id, $post_id, $value, $checked_objectives, $to_show, $prefix = '', $can_edit = true) {
			$seek_class = '';
			$description = $value['description'];
			$seek_param = '';
			$seek_class = '';
			$additional_input_attribute = '';
			$update_class = '';
			if ($can_edit) {
				$update_class = 'progressally-update';
				if ('youtube' === $value['seek-type'] || 'vimeo' === $value['seek-type'] || 'wistia' === $value['seek-type']){
					$seek_class = 'progressally-time-target';
					$seek_param = ' progressally-video-target="' . $value['seek-type'] . '" progressally-video-id="' . $value['seek-id'] . '" progressally-video-time="' . $value['seek-time'] . '" ';

					if (isset($value['checked-complete-video']) && 'yes' === $value['checked-complete-video']) {
						$additional_input_attribute = 'disabled="disabled"';
					}
				} elseif ($value['seek-type'] === 'quiz' || $value['seek-type'] === 'post' || $value['seek-type'] === 'note'){
					$additional_input_attribute = 'disabled="disabled"';
				}
			} else {
				$additional_input_attribute = 'disabled="disabled"';
			}
			$checked = isset($checked_objectives[$id]) ? 'checked="checked"' : '';
			$code = '';
			if ($to_show) {
				$code .= "<tr>";
			} else {
				$code .= "<tr style='display:none'>";
			}
			$code .= "<td class='objective-number $seek_class' $seek_param>$order</td>" .
"<td class='objective-description $seek_class' $seek_param>$description</td>" .
"<td class='objective-completion'><input type='checkbox' $additional_input_attribute class='completion-checkbox $update_class' post-id='$post_id' key='$id' value='true' $checked id='{$prefix}progressally-objective-completion-checkbox-$ordinal-$post_id-$id'" . " />" .
"<label for='" . $prefix . "progressally-objective-completion-checkbox-$ordinal-$post_id-$id'></label></td>" .
"</tr>";
			return $code;
		}
		public static function shortcode_progressally_progress_text($atts){
			extract( shortcode_atts( array(
				'post_id' => '',
				'user_id' => '',
				'request' => '',
				'context' => 'local'
			), $atts, 'progressally_progress_text' ) );
			$post_id = intval($post_id);
			$post_id = $post_id <= 0 ? get_the_ID(): $post_id;
			$post = get_post($post_id);
			if ($post_id <= 0 || empty($post)) {
				return 'Invalid post id: [' . $post_id . ']';
			}

			$current_user_id = ProgressAllyUserProgress::get_user_id();
			$current_user_id = intval($current_user_id);
			$display_user_id = ProgressAllyBackendShared::get_current_user_id_for_display($user_id, $request, $context, $current_user_id);
			$display_user_id = intval($display_user_id);

			$percentage = self::get_progress($post_id, $display_user_id);
			$code = '';
			if ($current_user_id === $display_user_id) {	// can live update
				$code = str_replace('{{percentage}}', round($percentage*100) . '%', self::PROGRESS_TEXT_TEMPLATE);
				$code = str_replace('{{post-id}}', $post_id, $code);
			} else {
				$code = round($percentage*100) . '%';
			}
			return $code;
		}
		public static function shortcode_progressally_progress_pie_chart($atts){
			extract( shortcode_atts( array(
				'post_id' => '',
				'background' => '',
				'size' => '100',
				'prefix' => '',
				'user_id' => '',
				'request' => '',
				'context' => 'local'
			), $atts, 'progressally_progress_pie_chart' ) );
			$post_id = intval($post_id);
			$post_id = $post_id <= 0 ? get_the_ID(): $post_id;
			$post = get_post($post_id);
			if ($post_id <= 0 || empty($post)) {
				return 'Invalid post id: [' . $post_id . ']';
			}

			$current_user_id = ProgressAllyUserProgress::get_user_id();
			$current_user_id = intval($current_user_id);
			$display_user_id = ProgressAllyBackendShared::get_current_user_id_for_display($user_id, $request, $context, $current_user_id);
			$display_user_id = intval($display_user_id);

			$progress = self::get_progress($post_id, $display_user_id);
			return self::generate_progress_pie_chart($progress, $size, $background, $prefix, $post_id, $current_user_id === $display_user_id);
		}
		public static function shortcode_progressally_progress_bar($atts){
			extract( shortcode_atts( array(
				'post_id' => '',
				'background' => '',
				'width' => '',
				'height' => '',
				'prefix' => '',
				'user_id' => '',
				'request' => '',
				'context' => 'local'
			), $atts, 'progressally_progress_bar' ) );
			$post_id = intval($post_id);
			$post_id = $post_id <= 0 ? get_the_ID(): $post_id;
			$post = get_post($post_id);
			if ($post_id <= 0 || empty($post)) {
				return 'Invalid post id: [' . $post_id . ']';
			}

			$current_user_id = ProgressAllyUserProgress::get_user_id();
			$current_user_id = intval($current_user_id);
			$display_user_id = ProgressAllyBackendShared::get_current_user_id_for_display($user_id, $request, $context, $current_user_id);
			$display_user_id = intval($display_user_id);

			$progress = self::get_progress($post_id, $display_user_id);
			return self::generate_progress_bar($progress, $width, $height, $background, $prefix, $post_id, $current_user_id === $display_user_id);
		}
		public static function preview_progress_pie_chart($percentage) {
			return self::generate_progress_pie_chart($percentage, '100', '', '', 0, true);
		}
		public static function preview_progress_bar($percentage) {
			return self::generate_progress_bar($percentage, '', '', '', '', 0, true);
		}
		public static function generate_progress_bar_for_stats($percentage) {
			return self::generate_progress_bar($percentage, '', '', '', 'progress_stats_', 0, true);
		}
		// utilities
		private static function get_progress($post_id, $user_id){
			/* note: return 0 when no objectives */
			$percentage = ProgressAllyUserProgress::get_progress($post_id, $user_id);
			return $percentage == -1 ? 0 : $percentage;
		}
		// code generaters
		private static function generate_progress_pie_chart($percentage, $size, $background_color, $prefix, $post_id = 0, $can_live_update = true) {
			if ($percentage > 0.5) {
				$right_angle = 90;
				$left_angle = round(($percentage-0.5) * 360) - 90;
			} else {
				$right_angle = round($percentage * 360) - 90;
				$left_angle = -90;
			}
			if ($background_color) {
				$background_color = 'background-color:' . $background_color . ' !important;';
			}
			$update_attr = '';
			if ($can_live_update) {
				$update_attr = 'progressally-pie-update="' . $post_id . '"';
			}
			$code = self::PROGRESS_PIE_CHART_TEMPLATE;
			$code = str_replace('{{right-angle}}', $right_angle, $code);
			$code = str_replace('{{left-angle}}', $left_angle, $code);
			$code = str_replace('{{background}}', $background_color, $code);
			$code = str_replace('{{size}}', $size, $code);
			$code = str_replace('{{half-size}}', floor($size/2), $code);
			$code = str_replace('{{4tr-size}}', floor($size/4), $code);
			$code = str_replace('{{5th-size}}', floor($size/5), $code);
			$code = str_replace('{{percentage}}', round($percentage*100) . '%', $code);
			$code = str_replace('{{update-attr}}', $update_attr, $code);
			$code = str_replace('{{prefix}}', $prefix, $code);
			return $code;
		}
		private static function generate_progress_bar($percentage, $width, $height, $background_color, $prefix, $post_id = 0, $can_live_update = true) {
			$code = self::PROGRESS_BAR_TEMPLATE;
			if ($background_color) {
				$background_color = 'background-color:' . $background_color . ' !important;';
			}
			$width_css = '';
			if ($width) {
				$width = strtolower($width);
				if (strlen($width > 2) && substr($width, -2) === 'px') {
					$width_css = 'width:' . $width . ';';
				} elseif (strlen($width > 1) && substr($width, -1) === '%') {
					$width_css = 'width:' . $width . ';';
				} else {	// no postfix
					$width_css = 'width:' . $width . 'px;';
				}
			}
			$height_css = '';
			$font_data = '';
			if ($height) {
				$height_css = 'height:' . $height . 'px;';
				$font_data = 'font-size:' . $height . 'px;line-height:' . $height . 'px;';
			}
			$update_attr = '';
			if ($can_live_update) {
				$update_attr = 'progressally-bar-update="' . $post_id . '"';
			}
			$code = str_replace('{{background}}', $background_color, $code);
			$code = str_replace('{{width}}', $width_css, $code);
			$code = str_replace('{{height}}', $height_css, $code);
			$code = str_replace('{{font-data}}', $font_data, $code);
			$code = str_replace('{{percentage}}', round($percentage*100) . '%', $code);
			$code = str_replace('{{update-attr}}', $update_attr, $code);
			$code = str_replace('{{prefix}}', $prefix, $code);
			return $code;
		}
		
		public static function shortcode_progressally_objective_completed_count($atts){
			extract( shortcode_atts( array(
				'post_id' => '',
				'user_id' => '',
				'request' => '',
				'context' => 'local'
			), $atts, 'progressally_objective_completed_count' ) );
			$post_id = intval($post_id);
			$post_id = $post_id <= 0 ? get_the_ID(): $post_id;
			$post = get_post($post_id);
			if ($post_id <= 0 || empty($post)) {
				return 'Invalid post id: [' . $post_id . ']';
			}
			$current_user_id = ProgressAllyUserProgress::get_user_id();
			$current_user_id = intval($current_user_id);
			$display_user_id = ProgressAllyBackendShared::get_current_user_id_for_display($user_id, $request, $context, $current_user_id);
			$display_user_id = intval($display_user_id);

			$completed_count = count(ProgressAllyUserProgress::get_checked_objectives($post_id, $display_user_id));
			$code = '';
			if ($current_user_id === $display_user_id) {
				$code = str_replace('{{count}}', $completed_count, self::PROGRESS_COMPLETED_COUNT_TEMPLATE);
				$code = str_replace('{{post-id}}', $post_id, $code);
			} else {
				$code = $completed_count;
			}
			return $code;
		}
		public static function shortcode_progressally_objective_count($atts){
			extract( shortcode_atts( array(
				'post_id' => '',
			), $atts, 'progressally_objective_count' ) );
			$post_id = intval($post_id);
			$post_id = $post_id <= 0 ? get_the_ID(): $post_id;
			$post = get_post($post_id);
			if ($post_id <= 0 || empty($post)) {
				return 'Invalid post id: [' . $post_id . ']';
			}
			return ProgressAllyPostObjective::get_active_objective_count($post_id);
		}
	}
}

