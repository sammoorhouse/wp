<?php

if (!class_exists('ProgressAllyGutenberg')) {
	class ProgressAllyGutenberg {
		public static function generate_shortcode_options() {
			$result = array(
				array('label' => '', 'value' => '')
			);
			foreach (self::$shortcode_config as $key => $config) {
				$result []= array('label' => $config['label'], 'value' => $key);
			}
			return $result;
		}
		private static function generate_shortcode_input_mapping() {
			foreach (self::$shortcode_config as $key => $config) {
				$result[$key] = $config['input'];
			}
			return $result;
		}
			
		public static function register_gutenberg_block() {
			wp_register_script('progressally-gutenberg', ProgressAlly::$PLUGIN_URI . 'resource/backend/editor/progressally-gutenberg.js',
				array('wp-blocks', 'wp-element'));
			wp_localize_script('progressally-gutenberg', 'progressally_gutenberg',
				array(
					'shortcode_type_options' => self::generate_shortcode_options(),
					'additional_input_type' => self::$additional_input_type_config,
					'type_mapping' => self::generate_shortcode_input_mapping()
					)
				);

			$num_saved = ProgressAllySettingStyling::get_num_saved_settings();
			if ($num_saved > 0){
				$css_url = content_url(ProgressAlly::CSS_FOLDER) . '/progressally-style.css';
				wp_enqueue_style('progressally-frontend-styling', $css_url, false, ProgressAlly::VERSION . '.' . $num_saved);
			}
			wp_register_style('progressally-gutenberg',
				ProgressAlly::$PLUGIN_URI . 'resource/backend/editor/progressally-gutenberg-backend.css',
				array('wp-edit-blocks', 'progressally-frontend-styling'), ProgressAlly::VERSION);

			$attributes = array(
					'shortcode_type' => array(
						'type' => 'string'
						),
					'current_post_id' => array(
						'type' => 'integer'
						)
					);
			foreach (self::$shortcode_config as $type => $config) {
				foreach ($config['input'] as $key) {
					if (!isset($attributes['param_' . $key])) {
						$attributes['param_' . $key] = array(
								'type' => 'string'
								);
					}
				}
			}
			register_block_type('progressally-gutenberg/shortcode', array(
				'editor_script' => 'progressally-gutenberg',
				'editor_style' => 'progressally-gutenberg',
				'render_callback' => array(__CLASS__, 'render_block_display'),
				'attributes' => $attributes
				));
		}
		public static $additional_input_type_config = array(
			'height' => array(
				'label' => 'Height (in pixels)',
				'type' => 'text'
				),
			'radius' => array(
				'label' => 'Radius (in pixels)',
				'type' => 'text'
				),
			'note_id' => array(
				'label' => 'Select Private Note',
				'type' => 'text'
				),
			'certificate_id' => array(
				'label' => 'Select Certificate',
				'type' => 'text'
				),
			'button_text' => array(
				'label' => 'Button text',
				'type' => 'text'
				),
			'progressally_video_id' => array(
				'label' => 'ProgressAlly video ID (configured in the objective)',
				'type' => 'text'
				),
			'video_id' => array(
				'label' => 'Video ID (part of the video URL)',
				'type' => 'text'
				),
			);

		public static $shortcode_config = array(
			'objective-list' => array(
				'label' => 'Objective list',
				'code' => '[progressally_objectives post_id="{{post-id}}"]',
				'input' => array()
				),
			'quiz' => array(
				'label' => 'Quiz',
				'code' => '[progressally_quiz post_id="{{post-id}}"]',
				'input' => array()
				),
			'progress-bar' => array(
				'label' => 'Progress - bar chart',
				'code' => '[progressally_progress_bar post_id="{{post-id}}" width="100%%" height="%s"]',
				'input' => array('height')
				),
			'progress-pie' => array(
				'label' => 'Progress - pie chart',
				'code' => '[progressally_progress_pie_chart post_id="{{post-id}}" size="%s"]',
				'input' => array('radius')
				),
			'note' => array(
				'label' => 'Private note',
				'code' => '[progressally_note post_id="{{post-id}}" note_id="%s" allow_attachment="yes"]',
				'input' => array('note_id')
				),
			'certificate' => array(
				'label' => 'Certificate download',
				'code' => '[progressally_certificate post_id="{{post-id}}" certificate_id="%s"]',
				'input' => array('certificate_id')
				),
			'complete-button' => array(
				'label' => 'Mark all objectives as complete button',
				'code' => '[progressally_complete_button post_id="{{post-id}}" text="%s" objective_id="all"]',
				'input' => array('button_text')
				),
			'video-youtube' => array(
				'label' => 'Youtube video',
				'code' => '[progressally_youtube_video id="%s" youtube_id="%s"]',
				'input' => array('progressally_video_id', 'video_id')
				),
			'video-vimeo' => array(
				'label' => 'Vimeo video',
				'code' => '[progressally_vimeo_video id="%s" vimeo_id="%s"]',
				'input' => array('progressally_video_id', 'video_id')
				),
			'video-wistia' => array(
				'label' => 'Wistia video',
				'code' => '[progressally_wistia_video id="%s" wistia_id="%s"]',
				'input' => array('progressally_video_id', 'video_id')
				),
			);
		public static function render_block_display($attr, $content) {
			try {
				if (empty($attr['shortcode_type'])) {
					return 'Please select an element type to display';
				}
				$shortcode_type = $attr['shortcode_type'];
				if (isset(self::$shortcode_config[$shortcode_type])) {
					$config = self::$shortcode_config[$shortcode_type];
					$code = $config['code'];
					$code = str_replace('{{post-id}}', $attr['current_post_id'], $code);
					if (!empty($config['input'])) {
						$params = array();
						foreach ($config['input'] as $index) {
							if (isset($attr['param_' . $index])) {
								$params []= $attr['param_' . $index];
							} else {
								$params []= '';
							}
						}
						$code = vsprintf($code, $params);
					}
					$code = do_shortcode($code);
					return $code;
				}
				return 'Please select an element type to display';
			} catch (Exception $ex) {
				return $ex->getMessage();
			}
		}
	}
}
