<?php

class ProgressAllyStylingTemplates {

	public static $template_color_attributes = array('progress-pie-chart-color', 'progress-bar-color', 'quiz-bgcolor', 'quiz-button-color', 'quiz-button-hover', 'border-color',
		'quiz-correct-message-bgcolor', 'quiz-incorrect-message-bgcolor', 'note-save-button-color', 'note-save-button-hover');
	public static $template_literal_attributes = array('objective-icon', 'time-target-icon', 'checkbox-unchecked', 'checkbox-checked', 'menu-completed-icon', 'menu-completed-icon-left', 'progress-bar-text-left', 'note-edit-icon', 'note-attachment-icon');

	private static $template_settings = array(
				'Teal' => array('progress-pie-chart-color' => '#00a5b3',
								'progress-bar-color' => '#00a5b3',
								'quiz-bgcolor' => '#f2f2f2',
								'quiz-button-color' => '#00a5b3',
								'quiz-button-hover' => '#C34491',
								'note-save-button-color' => '#00a5b3',
								'note-save-button-hover' => '#C34491'),
				'Health & Wellness' => array('progress-pie-chart-color' => '#4069c1',
								'progress-bar-color' => '#4069c1',
								'quiz-bgcolor' => '#eeeeee',
								'quiz-button-color' => '#4069c1',
								'quiz-button-hover' => '#D43637',
								'note-save-button-color' => '#4069c1',
								'note-save-button-hover' => '#D43637'),
				'Business Dark' => array('progress-pie-chart-color' => '#E0534A',
								'progress-bar-color' => '#E0534A',
								'quiz-bgcolor' => '#f0f0f0',
								'quiz-button-color' => '#E0534A',
								'quiz-button-hover' => '#3E98C5',
								'note-save-button-color' => '#E0534A',
								'note-save-button-hover' => '#3E98C5'),
				'Business Light' => array('progress-pie-chart-color' => '#e1e1e1',
								'progress-bar-color' => '#e1e1e1',
								'quiz-bgcolor' => '#ffffff',
								'quiz-button-color' => '#e1e1e1',
								'quiz-button-hover' => '#E0534A',
								'note-save-button-color' => '#e1e1e1',
								'note-save-button-hover' => '#E0534A'),
				'Spirituality' => array('progress-pie-chart-color' => '#612366',
								'progress-bar-color' => '#612366',
								'quiz-bgcolor' => '#eeeeee',
								'quiz-button-color' => '#612366',
								'quiz-button-hover' => '#Eb66F5',
								'note-save-button-color' => '#612366',
								'note-save-button-hover' => '#Eb66F5'),
				'Spring' => array('progress-pie-chart-color' => '#ADC83D',
								'progress-bar-color' => '#ADC83D',
								'quiz-bgcolor' => '#eeeeee',
								'quiz-button-color' => '#ADC83D',
								'quiz-button-hover' => '#D70141',
								'note-save-button-color' => '#ADC83D',
								'note-save-button-hover' => '#D70141'),
				'Summer' => array('progress-pie-chart-color' => '#81E2E8',
								'progress-bar-color' => '#81E2E8',
								'quiz-bgcolor' => '#eeeeee',
								'quiz-button-color' => '#81E2E8',
								'quiz-button-hover' => '#F2D225',
								'note-save-button-color' => '#81E2E8',
								'note-save-button-hover' => '#F2D225'),
				'Fall' => array('progress-pie-chart-color' => '#C75E31',
								'progress-bar-color' => '#C75E31',
								'quiz-bgcolor' => '#eeeeee',
								'quiz-button-color' => '#C75E31',
								'quiz-button-hover' => '#442E30',
								'note-save-button-color' => '#C75E31',
								'note-save-button-hover' => '#442E30'),
				'Winter' => array('progress-pie-chart-color' => '#96C1BA',
								'progress-bar-color' => '#96C1BA',
								'quiz-bgcolor' => '#eeeeee',
								'quiz-button-color' => '#96C1BA',
								'quiz-button-hover' => '#BBF0E7',
								'note-save-button-color' => '#96C1BA',
								'note-save-button-hover' => '#BBF0E7'),
			);
	private static $TEMPLATE_NAME_PATH_MAPPING = array(
		'Teal' => 'Teal',
		'Health & Wellness' => 'Health_and_Wellness',
		'Business Dark' => 'Business_Dark',
		'Business Light' => 'Business_Light',
		'Spirituality' => 'Spirituality',
		'Spring' => 'Spring',
		'Summer' => 'Summer',
		'Fall' => 'Fall',
		'Winter' => 'Winter',
		);
	public static function get_template_setting($template_name) {
		$result = self::$template_settings[$template_name];
			
		$plugin_uri = ProgressAlly::$PLUGIN_URI;
		$plugin_uri = preg_replace("/^http:\/\//i", "//", $plugin_uri);
		$plugin_uri = preg_replace("/^https:\/\//i", "//", $plugin_uri);
		$folder_name = self::$TEMPLATE_NAME_PATH_MAPPING[$template_name];

		$result['objective-icon'] = $plugin_uri . 'resource/template/' . $folder_name . '/objective-number-background.png';
		$result['time-target-icon'] = $plugin_uri . 'resource/template/' . $folder_name . '/time-target.png';
		$result['checkbox-unchecked'] = $plugin_uri . 'resource/template/' . $folder_name . '/objective-unchecked.png';
		$result['checkbox-checked'] = $plugin_uri . 'resource/template/' . $folder_name . '/objective-checked.png';
		$result['menu-completed-icon'] = $plugin_uri . 'resource/template/' . $folder_name . '/menu-completed-icon.png';
		$result['note-edit-icon'] = $plugin_uri . 'resource/template/' . $folder_name . '/note-edit-icon.png';
		$result['note-attachment-icon'] = $plugin_uri . 'resource/backend/img/attachment-icon.png';

		$result['menu-completed-icon-left'] = '84%';
		$result['progress-bar-text-left'] = '10px';
		$result['quiz-correct-message-bgcolor'] = '#dff0d8';
		$result['quiz-incorrect-message-bgcolor'] = '#f2dede';
		$result['border-color'] = '#eeeeee';
		return $result;
	}
	public static function get_template_settings() {
		$result = array();
		foreach (self::$TEMPLATE_NAME_PATH_MAPPING as $template_name => $path) {
			$result[$template_name] = self::get_template_setting($template_name);
		}
		return $result;
	}
	public static $STYLING_TEMPLATE_VARIABLES = array(
		'objective-table' => 'Objective Table',
		'menu-completed' => 'Menu Items',
		'progress-pie-chart' => 'Progress Pie Chart',
		'progress-bar' => 'Progress Bar',
		'quiz' => 'Quiz',
		'social-share' => 'Social Share',
		'video' => 'Video',
		'notes' => 'Notes',
		'certificate' => 'Certificate Download',
		'complete-button' => 'Complete Button'
		);
	public static function get_template() {
		$code = array();
		foreach (self::$STYLING_TEMPLATE_VARIABLES as $variable_name => $display_text) {
			$code[$variable_name . '-css'] = file_get_contents(dirname(__FILE__) . '/styling-templates/' . $variable_name . '.css');
		}
		return $code;
	}
	public static function generate_styling_css($settings) {
		$code = self::get_template();

		foreach (self::$template_color_attributes as $attribute_names) {
			$value = 'transparent';
			if (!empty($settings[$attribute_names])) {
				$value = $settings[$attribute_names];
			}
			foreach ($code as $id => $individual_code) {
				$code[$id] = str_replace('{{'. $attribute_names . '}}', $value, $individual_code);
			}
		}
		foreach (self::$template_literal_attributes as $attribute_names) {
			$value = '';
			if (!empty($settings[$attribute_names])) {
				$value = $settings[$attribute_names];
			}
			foreach ($code as $id => $individual_code) {
				$code[$id] = str_replace('{{'. $attribute_names . '}}', $value, $individual_code);
			}
		}
		return $code;
	}
	
}
