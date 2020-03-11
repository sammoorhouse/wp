<?php
if (!class_exists('ProgressAllyObjectivesShortcode')) {
	class ProgressAllyObjectivesShortcode {
		const DEFAULT_COMPLETE_BUTTON_BUTTON = '<span class="progressally-complete-button">{{text}}</span>';
		public static function add_shortcodes() {
			add_shortcode( 'progressally_complete_button', array(__CLASS__, 'shortcode_complete_button'));
		}
		public static function shortcode_complete_button($atts, $content = null) {
			extract( shortcode_atts( array(
				'inline' => 'yes',
				'post_id' => '',
				'objective_id' => '',
				'text' => 'Done'
			), $atts, 'progressally_complete_button' ) );
			if (empty($objective_id)) {
				return '';
			}
			$button_code = self::DEFAULT_COMPLETE_BUTTON_BUTTON;
			if (empty($content)) {
				$button_code = str_replace('{{text}}', $text, $button_code);
			} else {
				$button_code = $content;
			}
			$post_id = intval($post_id);
			$post_id = $post_id <= 0 ? get_the_ID(): $post_id;

			if ($post_id <= 0) {
				return '';
			}

			if ('all' === $objective_id) {
				$objectives = ProgressAllyPostObjective::get_objectives($post_id);
				$ids = array();
				foreach ($objectives as $id => $obj) {
					$ids []= $id;
				}
				if (empty($ids)) {
					return '';
				} else {
					$objective_id = implode(',', $ids);
				}
			}
			$style = '';
			if ('no' !== $inline) {
				$style = 'style="display:inline-block;"';
			}
			return '<div class="progressally-complete-button-container" progressally-complete-button="' . esc_attr($post_id . '|' . $objective_id) . '" '.
				$style . '>' . do_shortcode($button_code) . '</div>';
		}
	}
}