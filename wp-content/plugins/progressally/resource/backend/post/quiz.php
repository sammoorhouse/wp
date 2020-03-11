<?php
class ProgressAllyQuiz {
	const SETTING_KEY_COUNTER = '_progressally_quiz_counter';
	const META_KEY_COUNTER = '_progressally_quiz_counter_meta';
	
	const DEFAULT_SURVEY_OUTCOME_HTML = '<div class="progressally-quiz-result">Outcome Text</div>';
	const DEFAULT_GRADE_OUTCOME_HTML = '<div class="progressally-quiz-result"><hr/>Congratulations! You scored {[percentage]}!</div>';
	const DEFAULT_SEGMENT_OUTCOME_HTML = '<div class="progressally-quiz-result"><hr/>You scored {[score]}!</div>';
	private static $cached_question_template = null;
	private static $cached_choice_template = null;
	private static $cached_survey_outcome_template = null;
	private static $cached_grade_outcome_template = null;
	private static $cached_segment_outcome_template = null;
	private static $cached_choice_preview_template_vertical = null;
	private static $cached_choice_preview_template_horizontal = null;

	private static $default_choice_settings = array('html' => 'Choice text goes here',
		'checked-correct' => 'no',
		'select-survey-outcome' => '',
		'segment-score' => '1');
	private static $default_survey_outcome_settings = array('name' => 'Survey Outcome',
		'checked-is-open' => 'no',
		'html' => self::DEFAULT_SURVEY_OUTCOME_HTML,
		'select-popup-type' => 'none',
		'optin-popup' => '',
		'access-tag' => '',
		'value-field' => '');
	private static $default_grade_outcome_settings = array('min-score' => 100,
		'checked-is-open' => 'no',
		'html' => self::DEFAULT_GRADE_OUTCOME_HTML,
		'select-popup-type' => 'none',
		'optin-popup' => '');
	private static $default_grade_outcome_settings_fail = array('min-score' => 0,
		'checked-is-open' => 'no',
		'html' => '<div class="progressally-quiz-result"><hr/>You scored {[percentage]}, and did not pass the quiz.</div>',
		'select-popup-type' => 'none',
		'optin-popup' => '');
	private static $default_grade_outcome_settings_pass = array('min-score' => 50,
		'checked-is-open' => 'no',
		'html' => '<div class="progressally-quiz-result"><hr/>Congratulations! You scored {[percentage]} and pass the quiz!</div>',
		'select-popup-type' => 'none',
		'optin-popup' => '');
	private static $default_segment_outcome_settings = array('min-score' => 0,
		'checked-is-open' => 'no',
		'html' => self::DEFAULT_SEGMENT_OUTCOME_HTML,
		'select-popup-type' => 'none',
		'optin-popup' => '',
		'access-tag' => '');
	public static $default_question_settings = null;
	public static $default_quiz_settings = null;
	public static function initialize_defaults() {
		self::$default_question_settings = array('checked-is-open' => 'no', 'question-html' => 'Question text goes here?', 'incorrect-message-html' => 'Incorrect.',
																'radio-correct' => '1',
																'survey-weight' => '1',
																'order' => array('1', '2', '3', '4'),
																'choice' =>
																	array('1' => self::$default_choice_settings,
																		'2' => self::$default_choice_settings,
																		'3' => self::$default_choice_settings,
																		'4' => self::$default_choice_settings,));
		self::$default_quiz_settings = array('select-quiz-type' => 'score', // type: score, survey, segment
			'correct-message-html' => 'Correct!',
			'result-html' => '<div class="progressally-quiz-result"><hr/>Congratulations! You scored {[percentage]}!</div>',
			'submit-button-text' => 'Submit Answers!',
			'retake-button-text' => 'Retake Quiz',
			'back-button-text' => '< Previous',
			'next-button-text' => 'Next >',
			'num-question-per-page' => '0',
			'num-retake' => '0',
			'choice-display' => 'vertical',
			'survey-num-outcome' => '2',
			'survey-outcome' => array(1 => self::$default_survey_outcome_settings, 2 => self::$default_survey_outcome_settings),
			'grade-outcome-threshold-id' => 1,
			'grade-num-outcome' => '2',
			'grade-outcome' => array(2 => self::$default_grade_outcome_settings_pass, 1 => self::$default_grade_outcome_settings_fail), // graded outcomes are listed in reverse order
			'grade-outcome-pass-tag' => '',
			'grade-outcome-fail-tag' => '',
			'segment-num-outcome' => '1',
			'segment-outcome' => array(1 => self::$default_segment_outcome_settings),
			'question' => array('1' => ProgressAllyQuiz::$default_question_settings),
			'question-order' => array('1')
			);
	}
	public static function add_actions() {
		add_action('wp_ajax_progressally_export_quiz_results', array(__CLASS__, 'process_quiz_result_export'));
		add_action('wp_ajax_progressally_clone_question', array(__CLASS__, 'process_clone_question'));
	}
	// <editor-fold defaultstate="collapsed" desc="generate backend setting display">
	private static function get_survey_outcome_template() {
		if (self::$cached_survey_outcome_template === null) {
			self::$cached_survey_outcome_template = file_get_contents(dirname(__FILE__) . '/quiz-survey-outcome-template.php');
		}
		return self::$cached_survey_outcome_template;
	}
	private static function get_grade_outcome_template() {
		if (self::$cached_grade_outcome_template === null) {
			self::$cached_grade_outcome_template = file_get_contents(dirname(__FILE__) . '/quiz-grade-outcome-template.php');
		}
		return self::$cached_grade_outcome_template;
	}
	private static function get_segment_outcome_template() {
		if (self::$cached_segment_outcome_template === null) {
			self::$cached_segment_outcome_template = file_get_contents(dirname(__FILE__) . '/quiz-segment-outcome-template.php');
		}
		return self::$cached_segment_outcome_template;
	}
	private static function generate_outcome_code($outcome_id, $outcome_settings, $outcome_template) {
		$code = $outcome_template;
		$code = ProgressAllyBackendShared::replace_real_values($code, $outcome_settings, '');
		$code = ProgressAllyBackendShared::replace_all_toggle($code, $outcome_settings);
		$code = str_replace('{{outcome-id}}', $outcome_id, $code);
		return $code;
	}
	public static function generate_default_survey_outcome_code() {
		$settings = self::$default_survey_outcome_settings;
		$settings['tag-selection'] = '--tag-selection--';
		$settings['has-valid-tag-selection'] = '--has-valid-tag-selection--';
		$settings['popup-selection'] = '--popup-selection--';
		$settings['has-valid-popup-selection'] = '--has-valid-popup-selection--';
		$settings['field-selection'] = '--field-selection--';
		$settings['has-valid-field-selection'] = '--has-valid-field-selection--';
		$code = self::generate_outcome_code('--outcome-id--', $settings, self::get_survey_outcome_template());
		return $code;
	}
	public static function generate_default_grade_outcome_code() {
		$settings = self::$default_grade_outcome_settings;
		$settings['outcome-opened-class'] = '';
		$settings['score-readonly'] = '';
		$settings['radio-grade-outcome-threshold-id'] = '';
		$settings['popup-selection'] = '--popup-selection--';
		$settings['has-valid-popup-selection'] = '--has-valid-popup-selection--';
		$code = self::generate_outcome_code('--outcome-id--', $settings, self::get_grade_outcome_template());
		return $code;
	}
	public static function generate_default_segment_outcome_code() {
		$settings = self::$default_segment_outcome_settings;
		$settings['outcome-opened-class'] = '';
		$settings['score-readonly'] = '';
		$settings['tag-selection'] = '--tag-selection--';
		$settings['has-valid-tag-selection'] = '--has-valid-tag-selection--';
		$settings['popup-selection'] = '--popup-selection--';
		$settings['has-valid-popup-selection'] = '--has-valid-popup-selection--';
		$code = self::generate_outcome_code('--outcome-id--', $settings, self::get_segment_outcome_template());
		return $code;
	}
	private static function get_choice_template() {
		if (self::$cached_choice_template === null) {
			self::$cached_choice_template = file_get_contents(dirname(__FILE__) . '/quiz-choice-template.php');
		}
		return self::$cached_choice_template;
	}
	private static function generate_survey_outcome_options($quiz_settings) {
		$survey_outcome_options = '';
		$option_template = file_get_contents(dirname(__FILE__) . '/quiz-choice-outcome-option-template.php');
		for ($i = 1; $i <= $quiz_settings['survey-num-outcome']; ++$i) {
			$option_code = str_replace('{{outcome-id}}', $i, $option_template);
			$survey_outcome_options .= str_replace('{{name}}', esc_html($quiz_settings['survey-outcome'][$i]['name']), $option_code);
		}
		return $survey_outcome_options;
	}
	public static function generate_default_survey_outcome_option_code() {
		$option_template = file_get_contents(dirname(__FILE__) . '/quiz-choice-outcome-option-template.php');
		$option_code = str_replace('{{outcome-id}}', '--outcome-id--', $option_template);
		$option_code = str_replace('{{name}}', esc_html(self::$default_survey_outcome_settings['name']), $option_code);
		return $option_code;
	}
	private static function generate_choice_code($question_id, $choice_id, $settings, $question_settings, $survey_outcome_options) {
		$code = self::get_choice_template();
		$code = ProgressAllyBackendShared::replace_real_values($code, $settings, '');
		$code = str_replace('{{question-id}}', $question_id, $code);
		$code = str_replace('{{choice-id}}', $choice_id, $code);
		$code = str_replace('{{radio-correct}}', $question_settings['radio-correct'] === $choice_id ? 'checked="checked"' : '', $code);

		$outcome_choices = str_replace('s--select-survey-outcome-' . $settings['select-survey-outcome'] . '--d', 'selected="selected"', $survey_outcome_options);
		$code = str_replace('{{outcome-selection}}', $outcome_choices, $code);
		return $code;
	}
	public static function generate_default_choice_code() {
		$default_choice_settings = self::$default_choice_settings;
		$default_choice_settings['html'] = '--clabel--';
		$default_choice_settings['segment-score'] = '--segment-score--';
		$code = self::generate_choice_code('--qid--', '--cid--', $default_choice_settings, self::$default_question_settings, '--outcome-options--');
		$code = preg_replace('/s--select-survey-outcome-.*?--d/', '', $code);
		return $code;
	}
	public static function generate_default_choice_label() {
		return esc_html(self::$default_choice_settings['html']);
	}
	private static function get_question_template() {
		if (self::$cached_question_template === null) {
			self::$cached_question_template = file_get_contents(dirname(__FILE__) . '/quiz-question-template.php');
		}
		return self::$cached_question_template;
	}
	private static function get_choice_preview_template($choice_display) {
		if ($choice_display === 'horizontal') {
			if (self::$cached_choice_preview_template_horizontal === null) {
				self::$cached_choice_preview_template_horizontal = file_get_contents(dirname(__FILE__) . '/quiz-choice-preview-template-horizontal.php');
			}
			return self::$cached_choice_preview_template_horizontal;
		}
		if (self::$cached_choice_preview_template_vertical === null) {
			self::$cached_choice_preview_template_vertical = file_get_contents(dirname(__FILE__) . '/quiz-choice-preview-template.php');
		}
		return self::$cached_choice_preview_template_vertical;
	}
	private static function generate_preview_choice_code($question_id, $choice_id, $choice_template) {
		$code = str_replace('{{question-id}}', $question_id, $choice_template);
		$code = str_replace('{{choice-id}}', $choice_id, $code);
		return $code;
	}
	public static function generate_default_vertical_choice_preview_code() {
		$code = self::generate_preview_choice_code('--qid--', '--cid--', self::get_choice_preview_template('vertical'));
		return $code;
	}
	public static function generate_default_horizontal_choice_preview_code() {
		$code = self::generate_preview_choice_code('--qid--', '--cid--', self::get_choice_preview_template('horizontal'));
		return $code;
	}
	private static function generate_preview_code($question_id, $question_settings, $choice_template) {
		$preview_code = '';
		foreach ($question_settings['order'] as $choice_id) {
			$preview_code .= self::generate_preview_choice_code($question_id, $choice_id, $choice_template);
		}
		return $preview_code;
	}
	private static function generate_backend_question_code($question_id, $question_settings, $survey_outcome_options) {
		$choice_code = '';
		$max_choice_id = 0;
		foreach ($question_settings['order'] as $choice_id) {
			$choice_settings = $question_settings['choice'][$choice_id];
			$choice_code .= self::generate_choice_code($question_id, $choice_id, $choice_settings, $question_settings, $survey_outcome_options);
			$max_choice_id = max($max_choice_id, $choice_id);
		}
		$code = self::get_question_template();
		$code = ProgressAllyBackendShared::replace_real_values($code, $question_settings, '');
		$code = ProgressAllyBackendShared::replace_all_toggle($code, $question_settings);

		$code = str_replace('{{choices}}', $choice_code, $code);

		$code = str_replace('{{preview-code-vertical}}',
			self::generate_preview_code($question_id, $question_settings, self::get_choice_preview_template('vertical')),
			$code);

		$code = str_replace('{{preview-code-horizontal}}',
			self::generate_preview_code($question_id, $question_settings, self::get_choice_preview_template('horizontal')),
			$code);

		$code = str_replace('{{max-choice-id}}', $max_choice_id, $code);
		$code = str_replace('{{question-id}}', $question_id, $code);
		$code = str_replace('{{open-class}}', $question_settings['checked-is-open'] === 'yes' ? 'progressally-question-opened' : '', $code);
		return $code;
	}
	public static function generate_default_question_code() {
		$code = self::generate_backend_question_code('--qid--', self::$default_question_settings, '--outcome-options--');
		$code = preg_replace('/s--select-survey-outcome-.*?--d/', '', $code);
		return $code;
	}
	private static function generate_quiz_code($settings) {
		$question_code = '';
		$survey_outcome_options = self::generate_survey_outcome_options($settings);
		foreach ($settings['question-order'] as $question_id) {
			if (isset($settings['question'][$question_id])) {
				$question_settings = $settings['question'][$question_id];
				$question_code .= self::generate_backend_question_code($question_id, $question_settings, $survey_outcome_options);
			}
		}
		$question_code = str_replace('s--quiz-type-' . $settings['select-quiz-type'] . '-w}}', '', $question_code);
		$question_code = preg_replace('/s--quiz-type-.*?--w/', '', $question_code);
		$question_code = preg_replace('/s--select-survey-outcome-.*?--d/', '', $question_code);
		return $question_code;
	}
	private static function generate_quiz_survey_outcome_code($settings) {
		$outcome_code = '';
		$outcome_num = $settings['survey-num-outcome'];
		$outcome_settings_all = $settings['survey-outcome'];
		
		for ($i = 0; $i < $outcome_num; ++$i) {
			$outcome_settings = self::$default_survey_outcome_settings;
			$index = $i+1;
			if (isset($outcome_settings_all[$index])) {
				$outcome_settings = $outcome_settings_all[$index];
			}
			$per_outcome_code = self::generate_outcome_code($index, $outcome_settings, self::get_survey_outcome_template());
			
			$tag_selection_code = ProgressAllyBackendShared::generate_tag_selection_code($outcome_settings['access-tag']);
			$per_outcome_code = str_replace('{{tag-selection}}', $tag_selection_code, $per_outcome_code);

			$popup_selection_code = ProgressAllyBackendShared::generate_popup_selection_code($outcome_settings['optin-popup']);
			$per_outcome_code = str_replace('{{popup-selection}}', $popup_selection_code, $per_outcome_code);
			
			$field_selection_code = ProgressAllyBackendShared::generate_field_selection_code($outcome_settings['value-field']);
			$per_outcome_code = str_replace('{{field-selection}}', $field_selection_code, $per_outcome_code);

			$outcome_code .= $per_outcome_code;
		}
		$hide_tag_selection = '0' === ProgressAllyBackendShared::get_tag_selection_template();
		$outcome_code = ProgressAllyBackendShared::generate_display_code($outcome_code, '{{has-valid-tag-selection}}', !$hide_tag_selection);
		$hide_popup_selection = '0' === ProgressAllyBackendShared::get_popup_selection_template();
		$outcome_code = ProgressAllyBackendShared::generate_display_code($outcome_code, '{{has-valid-popup-selection}}', !$hide_popup_selection);
		$hide_field_selection = '0' === ProgressAllyBackendShared::get_field_selection_template();
		$outcome_code = ProgressAllyBackendShared::generate_display_code($outcome_code, '{{has-valid-field-selection}}', !$hide_field_selection);

		return $outcome_code;
	}
	private static function generate_quiz_grade_outcome_code($settings) {
		$outcome_code = '';

		foreach ($settings['grade-outcome'] as $outcome_id => $outcome_settings) {
			$is_thresold = $outcome_id == $settings['grade-outcome-threshold-id'];
			$outcome_settings['outcome-opened-class'] = ($outcome_settings['checked-is-open'] === 'yes') ? 'progressally-item-opened' : '';
			$outcome_settings['score-readonly'] = ($outcome_id === 1) ? 'readonly="readonly"' : '';
			$outcome_settings['radio-grade-outcome-threshold-id'] = $is_thresold ? 'checked="checked"' : '';

			$per_outcome_code = self::generate_outcome_code($outcome_id, $outcome_settings, self::get_grade_outcome_template());

			$popup_selection_code = ProgressAllyBackendShared::generate_popup_selection_code($outcome_settings['optin-popup']);
			$per_outcome_code = str_replace('{{popup-selection}}', $popup_selection_code, $per_outcome_code);

			$outcome_code .= $per_outcome_code;
		}
		$hide_popup_selection = '0' === ProgressAllyBackendShared::get_popup_selection_template();
		$outcome_code = ProgressAllyBackendShared::generate_display_code($outcome_code, '{{has-valid-popup-selection}}', !$hide_popup_selection);

		return $outcome_code;
	}
	private static function generate_quiz_segment_outcome_code($settings) {
		$outcome_code = '';

		foreach ($settings['segment-outcome'] as $outcome_id => $outcome_settings) {
			$outcome_settings['outcome-opened-class'] = ($outcome_settings['checked-is-open'] === 'yes') ? 'progressally-item-opened' : '';
			$outcome_settings['score-readonly'] = ($outcome_id === 1) ? 'readonly="readonly"' : '';

			$per_outcome_code = self::generate_outcome_code($outcome_id, $outcome_settings, self::get_segment_outcome_template());

			$tag_selection_code = ProgressAllyBackendShared::generate_tag_selection_code($outcome_settings['access-tag']);
			$per_outcome_code = str_replace('{{tag-selection}}', $tag_selection_code, $per_outcome_code);

			$popup_selection_code = ProgressAllyBackendShared::generate_popup_selection_code($outcome_settings['optin-popup']);
			$per_outcome_code = str_replace('{{popup-selection}}', $popup_selection_code, $per_outcome_code);

			$outcome_code .= $per_outcome_code;
		}
		$hide_tag_selection = '0' === ProgressAllyBackendShared::get_tag_selection_template();
		$outcome_code = ProgressAllyBackendShared::generate_display_code($outcome_code, '{{has-valid-tag-selection}}', !$hide_tag_selection);
		$hide_popup_selection = '0' === ProgressAllyBackendShared::get_popup_selection_template();
		$outcome_code = ProgressAllyBackendShared::generate_display_code($outcome_code, '{{has-valid-popup-selection}}', !$hide_popup_selection);

		return $outcome_code;
	}
	public static function show_quiz_meta_box($meta, $post_id) {
		$question_code = self::generate_quiz_code($meta['quiz']);
		$survey_outcome_code = self::generate_quiz_survey_outcome_code($meta['quiz']);

		$grade_outcome_code = self::generate_quiz_grade_outcome_code($meta['quiz']);

		$has_valid_tag_selection = '0' !== ProgressAllyBackendShared::get_tag_selection_template();
		$grade_pass_tagging_code = ProgressAllyBackendShared::generate_tag_selection_code($meta['quiz']['grade-outcome-pass-tag']);
		$grade_fail_tagging_code = ProgressAllyBackendShared::generate_tag_selection_code($meta['quiz']['grade-outcome-fail-tag']);

		$segment_outcome_code = self::generate_quiz_segment_outcome_code($meta['quiz']);
		$max_question_num = 0;
		if (!empty($meta['quiz']['question'])) {
			$max_question_num = max(array_keys($meta['quiz']['question']));
		}
		$quiz_stats_url = self::generate_quiz_stats_url($post_id);

		ob_start();
		include dirname(__FILE__) . '/quiz-display.php';
		return ob_get_clean();
	}
	// </editor-fold>

	private static function merge_default_question_settings($question_settings) {
		if (!isset($question_settings['choice'])) {
			$question_settings['choice'] = array();
		}
		if (!isset($question_settings['order'])) {
			$question_settings['order'] = array();
		}
		$question_settings = wp_parse_args($question_settings, self::$default_question_settings);
		foreach ($question_settings['choice'] as $id => $choice_settings) {
			if (!in_array($id, $question_settings['order'])) {
				$question_settings['order'] []= $id;
			}
			$question_settings['choice'][$id] = wp_parse_args($choice_settings, self::$default_choice_settings);
		}
		return $question_settings;
	}
	public static function merge_default_settings($settings) {
		if (!isset($settings['question'])) {
			$settings['question'] = array();
		}
		if (empty($settings['question-order'])) {
			$settings['question-order'] = array_keys($settings['question']);
		}
		foreach ($settings['question'] as $id => $question_settings) {
			$settings['question'][$id] = self::merge_default_question_settings($question_settings);

			if (!in_array($id, $settings['question-order'])) {
				$settings['question-order'] []= $id;
			}
		}

		if (!isset($settings['survey-outcome'])) {
			$settings['survey-outcome'] = array(1 => self::$default_survey_outcome_settings, 2 => self::$default_survey_outcome_settings);
		} else {
			foreach ($settings['survey-outcome'] as $id => $outcome_settings) {
				// backward compatible
				if (!isset($outcome_settings['select-popup-type']) &&
					isset($outcome_settings['optin-popup']) && !empty($outcome_settings['optin-popup'])) {
					$outcome_settings['select-popup-type'] = 'embedded';
				}
				$settings['survey-outcome'][$id] = wp_parse_args($outcome_settings, self::$default_survey_outcome_settings);
			}
		}
		
		if (!isset($settings['grade-outcome']) && isset($settings['result-html'])) {
			$settings['grade-outcome'] = array(1 => self::$default_grade_outcome_settings_fail);
			$settings['grade-outcome'][1]['html'] = $settings['result-html'];
			$settings['grade-num-outcome'] = 1;
		}
		if (!isset($settings['grade-outcome'])) {
			$settings['grade-outcome'] = array();
		}
		foreach ($settings['grade-outcome'] as $id => $outcome_settings) {
			// backward compatible
			if (!isset($outcome_settings['select-popup-type']) &&
				isset($outcome_settings['optin-popup']) && !empty($outcome_settings['optin-popup'])) {
				$outcome_settings['select-popup-type'] = 'embedded';
			}
			$settings['grade-outcome'][$id] = wp_parse_args($outcome_settings, self::$default_grade_outcome_settings);
		}

		if (!isset($settings['segment-outcome'])) {
			$settings['segment-outcome'] = array(1 => self::$default_segment_outcome_settings);
			$settings['segment-num-outcome'] = 1;
		}
		foreach ($settings['segment-outcome'] as $id => $outcome_settings) {
			// backward compatible
			if (!isset($outcome_settings['select-popup-type']) &&
				isset($outcome_settings['optin-popup']) && !empty($outcome_settings['optin-popup'])) {
				$outcome_settings['select-popup-type'] = 'embedded';
			}
			$settings['segment-outcome'][$id] = wp_parse_args($outcome_settings, self::$default_segment_outcome_settings);
		}

		$settings = wp_parse_args($settings, self::$default_quiz_settings);
		return $settings;
	}
	
	// <editor-fold defaultstate="collapsed" desc="quiz result export">
	public static function generate_quiz_stats_url($post_id) {
		$export_nonce = wp_create_nonce("progressally-stats-export");
		return add_query_arg(array('export-stats-nonce' => $export_nonce,
									'post-id' => $post_id,
									'action' => 'progressally_export_quiz_results'
								), admin_url('admin-ajax.php'));
	}
	public static function process_quiz_result_export() {
		if (isset($_REQUEST['post-id']) && isset($_REQUEST['export-stats-nonce']) && wp_verify_nonce($_REQUEST['export-stats-nonce'], "progressally-stats-export")) {
			$post_id = intval($_REQUEST['post-id']);
			set_time_limit(0);
			
			$filename = "ProgressAlly Quiz Stats - post ". $post_id . ".csv";
			header('Content-Type: text/csv');
			header('Content-Disposition: attachment; filename="' . $filename . '"');

			$settings = ProgressAllyTaskDefinition::get_post_progress_meta($post_id);

			$post_title = get_the_title($post_id);
			echo ProgressAllyUtilities::escape_string_csv($post_title) . PHP_EOL;
			echo PHP_EOL;
			
			// Export preamble
			echo "Quiz description" . PHP_EOL;
			self::export_quiz_preamble($settings['quiz']);
			echo PHP_EOL;
			
			// Export question index row (header)
			$n_questions = count($settings['quiz']['question']);
			$row_string = 'User Login';
			for($question_index = 1; $question_index <= $n_questions; ++$question_index) {
				$row_string .= ',Q' . $question_index;
			}
			echo "User Response" . PHP_EOL;
			if ($settings['quiz']['select-quiz-type'] === 'survey') {
				$row_string .= ',Outcome';
			} else {
				$row_string .= ',Score';
			}
			echo $row_string . PHP_EOL;

			// Export quiz result per user
			$users = self::get_quiz_users($post_id);
			foreach ($users as $user_login => $user_meta) {
				self::export_quiz_result($settings['quiz'], $user_login, $user_meta);
			}
			exit;
		}
	}
	private static function export_quiz_preamble($quiz_settings) {
		$question_index = 1;
		foreach ($quiz_settings['question'] as $question_id => $question_settings) {
			$question = 'Q' . $question_index . '. ' . $question_settings['question-html'];
			$row_string = ProgressAllyUtilities::escape_string_csv($question);
			
			$n_choices = count($question_settings['order']);
			for($choice_index = 0; $choice_index < $n_choices; ++$choice_index) {
				$choice_id = $question_settings['order'][$choice_index];
				// order index starts with 0
				$choice = ($choice_index + 1) . '. ' . $question_settings['choice'][$choice_id]['html'];
				$row_string .= ',' . ProgressAllyUtilities::escape_string_csv($choice);
			}
			$question_index++;
			echo $row_string . PHP_EOL;
		}
	}
	private static function export_quiz_result($quiz_settings, $username, $user_meta) {
		$row_string = $username;
		$user_input = $user_meta['input'];
		$user_result = $user_meta['result'];
		
		foreach ($quiz_settings['question'] as $question_id => $question_settings) {
			$choice = '';
			if (isset($user_input['progressally-question-' . $question_id])) {
				$choice_id = $user_input['progressally-question-' . $question_id];
				if (isset($question_settings['choice'][intval($choice_id)])) {
					// order index starts with 0
					$choice = array_search($choice_id, $question_settings['order']) + 1;
				}
			}
			
			$row_string .= ',' . $choice;
		}
		
		if (isset($user_result['type']) && $quiz_settings['select-quiz-type'] === $user_result['type']) {
			if ($user_result['type'] === 'survey') {
				$row_string .= ',' . $user_result['outcome'];
			} else {
				$row_string .= ',' . $user_result['score'];
			}
		} elseif (!isset($user_result['type']) && $quiz_settings['select-quiz-type'] === 'score') {
			// backward compatibility
			$score = isset($user_result['score']) ? $user_result['score'] : 0;
			$row_string .= ',' . $score;
		} else {
			$row_string .= ',Invalid';
		}

		echo $row_string . PHP_EOL;
	}
	private static function get_quiz_users($post_id) {
		global $wpdb;
		$query = $wpdb->prepare("SELECT {$wpdb->users}.user_login as user_login, {$wpdb->usermeta}.meta_value as meta_value FROM {$wpdb->users} INNER JOIN {$wpdb->usermeta} ON "
				. "{$wpdb->users}.ID = {$wpdb->usermeta}.user_id WHERE {$wpdb->usermeta}.meta_key = %s AND "
				. "{$wpdb->usermeta}.meta_value LIKE %s", ProgressAllyUserProgress::USER_META_KEY, '%'. $post_id . '%');
		$result = $wpdb->get_results($query, ARRAY_A);
		$user_inputs = array();
		foreach ($result as $row) {
			$meta_value = unserialize($row['meta_value']);
			if (isset($meta_value[$post_id]) && isset($meta_value[$post_id]['quiz'])) {
				$user_inputs[$row['user_login']]= $meta_value[$post_id]['quiz'];
			}
		}
		return $user_inputs;
	}
	// </editor-fold>
	
	// <editor-fold defaultstate="collapsed" desc="quiz counter stats system">
	public static function initialize_quiz_stats_counter() {
		$is_initialized = get_option(ProgressAllyQuiz::SETTING_KEY_COUNTER, 'false');
		if ($is_initialized === 'true') {
			return;
		}

		if (add_option(self::SETTING_KEY_COUNTER, 'true', '', 'no')) {
			update_option(self::SETTING_KEY_COUNTER, 'true');
		}
		self::initialize_quiz_stats_counter_core();
	}
	private static function initialize_quiz_stats_counter_core() {
		$post_meta_table = ProgressAllyTaskDefinition::get_post_meta_table();
		$user_meta_table = ProgressAllyUserProgress::get_usermeta_database_table_for_conversion();
		
		foreach ($user_meta_table as $post_id => $user_meta_array) {
			if (isset($post_meta_table[$post_id]) && isset($post_meta_table[$post_id]['quiz'])) {
				$post_meta = $post_meta_table[$post_id];
				$post_meta['quiz'] = self::merge_default_settings($post_meta['quiz']);
				$quiz_counter_meta = self::generate_quiz_stats_counter_defaults($post_meta['quiz']);
				
				foreach ($user_meta_array as $user_id => $user_meta) {
					if (isset($user_meta['quiz']) && isset($user_meta['quiz']['result'])) {
						$user_input = $user_meta['quiz'];
						
						// Deal with legacy score test result format
						if (!is_array($user_input['result'])) {
							if ($quiz_counter_meta['select-quiz-type'] === 'score') {
								$user_input['result'] = array('type' => 'score', 'score' => $user_input['result']);
							} else {
								$user_input['result'] = array('type' => 'invalid');
							}
						}
						if (isset($user_input['result']['score'])) {
							if (!isset($user_input['result']['type'])) {
								$user_input['result']['type'] = 'score';
							}
							if (!isset($user_input['result']['pass'])) {
								$user_input['result']['pass'] = ProgressAllyQuizEvaluation::evaluate_score_quiz_pass($post_meta, floatval($user_input['result']['score']));
							}
						}
						
						// Process the counters
						$quiz_counter_meta = self::update_quiz_stats_counter_core($quiz_counter_meta, $user_input, 1, $post_meta);
					}
				}
				// Set the new meta
				self::set_quiz_stats_counter_meta($quiz_counter_meta, $post_id);
			}
		}
	}
	
	public static function maybe_reset_quiz_stats_counter($post_meta, $post_id) {
		$quiz_counter_meta = self::get_quiz_stats_counter_meta($post_id);
		$quiz_settings = $post_meta['quiz'];
		
		// Merge meta
		$new_quiz_counters = self::generate_quiz_stats_counter_defaults($quiz_settings);
		if ($quiz_counter_meta['select-quiz-type'] === $quiz_settings['select-quiz-type']) {
			$new_quiz_counters['quiz_completion_counter'] = $quiz_counter_meta['quiz_completion_counter'];

			if ($quiz_settings['select-quiz-type'] === 'score') {
				$new_quiz_counters['grade_pass_counter'] = $quiz_counter_meta['grade_pass_counter'];
			} elseif ($quiz_settings['select-quiz-type'] === 'survey') {
				foreach ($quiz_counter_meta['survey-outcome'] as $id => $outcome_counter) {
					if (isset($new_quiz_counters['survey-outcome'][$id])) {
						$new_quiz_counters['survey-outcome'][$id] = $outcome_counter;
					}
				}
			} elseif ($quiz_settings['select-quiz-type'] === 'segment') {
				foreach ($quiz_counter_meta['segment-outcome'] as $id => $outcome_counter) {
					if (isset($new_quiz_counters['segment-outcome'][$id])) {
						$new_quiz_counters['segment-outcome'][$id] = $outcome_counter;
					}
				}
			}

			foreach ($quiz_counter_meta['question'] as $id => $question) {
				if (isset($new_quiz_counters['question'][$id])) {
					foreach ($question as $choice_id => $choice_counter) {
						if (isset($new_quiz_counters['question'][$id][$choice_id])) {
							$new_quiz_counters['question'][$id][$choice_id] = $choice_counter;
						}
					}
				}
			}
		}

		// Set the new meta
		self::set_quiz_stats_counter_meta($new_quiz_counters, $post_id);
	}
	
	private static function generate_quiz_stats_counter_defaults($quiz_settings) {
		$default_quiz_counters = array('select-quiz-type' => $quiz_settings['select-quiz-type'],
										'quiz_completion_counter' => 0);
		
		if ($quiz_settings['select-quiz-type'] === 'score') {
			$default_quiz_counters['grade_pass_counter'] = 0;
		} elseif ($quiz_settings['select-quiz-type'] === 'survey') {
			$default_quiz_counters['survey-outcome'] = array();
			foreach ($quiz_settings['survey-outcome'] as $id => $outcome) {
				$default_quiz_counters['survey-outcome'][$id] = 0;
			}
		} elseif ($quiz_settings['select-quiz-type'] === 'segment') {
			$default_quiz_counters['segment-outcome'] = array();
			foreach ($quiz_settings['segment-outcome'] as $id => $outcome) {
				$default_quiz_counters['segment-outcome'][$id] = 0;
			}
		}
		
		$default_quiz_counters['question'] = array();
		foreach ($quiz_settings['question'] as $id => $question) {
			$default_quiz_counters['question'][$id] = array();
			foreach ($question['choice'] as $choice_id => $choice) {
				$default_quiz_counters['question'][$id][$choice_id] = 0;
			}
		}
		
		return $default_quiz_counters;
	}
	public static function get_quiz_stats_counter_meta($post_id) {
		$meta = get_post_meta($post_id, self::META_KEY_COUNTER, true);
		if (!is_array($meta)) {
			$post_meta = ProgressAllyTaskDefinition::get_post_progress_meta($post_id);
			$meta = self::generate_quiz_stats_counter_defaults($post_meta['quiz']);
		}
		return $meta;
	}
	private static function set_quiz_stats_counter_meta($meta, $post_id) {
		update_post_meta($post_id, self::META_KEY_COUNTER, $meta);
		wp_cache_set(self::META_KEY_COUNTER, $meta, $post_id, time() + ProgressAlly::CACHE_PERIOD);
	}
	
	public static function update_quiz_stats_counter($post_id, $user_input, $increment) {
		$post_meta = ProgressAllyTaskDefinition::get_post_progress_meta($post_id);
		$quiz_counters = self::get_quiz_stats_counter_meta($post_id);
		$quiz_counters = self::update_quiz_stats_counter_core($quiz_counters, $user_input, $increment, $post_meta);
		
		// Set the new meta
		self::set_quiz_stats_counter_meta($quiz_counters, $post_id);
	}

	private static function update_quiz_stats_counter_core($quiz_counters, $user_input, $increment, $post_meta) {
		// Unlikely case: the quiz type and user input/result type do not match
		if ($quiz_counters['select-quiz-type'] !== $user_input['result']['type']) {
			return $quiz_counters;
		}
		if ($quiz_counters['quiz_completion_counter'] <= 0 && $increment < 0) {
			return $quiz_counters;
		}
		
		// Update completion counter
		$quiz_counters['quiz_completion_counter'] += $increment;
		
		// Update outcome counters
		if ($quiz_counters['select-quiz-type'] == 'score') {
			if ($user_input['result']['pass'] === true) {
				$quiz_counters['grade_pass_counter'] += $increment;
			}
		} elseif ($quiz_counters['select-quiz-type'] == 'survey') {
			$outcome = $user_input['result']['outcome'];
			if (isset($quiz_counters['survey-outcome'][$outcome])) {
				$quiz_counters['survey-outcome'][$outcome] += $increment;
			}
		} elseif ($quiz_counters['select-quiz-type'] == 'segment') {
			$score = $user_input['result']['score'];
			$outcome = ProgressAllyQuizEvaluation::get_segement_quiz_outcome($post_meta, $score);
			if (isset($quiz_counters['segment-outcome'][$outcome])) {
				$quiz_counters['segment-outcome'][$outcome] += $increment;
			}
		}
		
		// Update question choice counters
		foreach ($quiz_counters['question'] as $question_id => $question) {
			$id = 'progressally-question-' . $question_id;
			if (isset($user_input['input'][$id]) ) {
				$selected_choice = $user_input['input'][$id];
				if (isset($question[$selected_choice])) {
					$quiz_counters['question'][$question_id][$selected_choice] += $increment;
				}
			}
		}
		
		return $quiz_counters;
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="Ajax callback: clone quiz question">
	public static function process_clone_question() {
		try{
			if (!isset($_POST['input']) || !isset($_POST['question_id']) || !isset($_POST['outcome']) ||
				!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'progressally-update-nonce')) {
				throw new Exception('The setting page is outdated. Please reload this page.');
			}
			$input = ProgressAllyBackendShared::convert_setting_string_to_array($_POST['input']);

			$question_id = $_POST['question_id'];
			if (!isset($input['quiz']['question'][$question_id])) {
				throw new Exception('The question setting is missing. Please reload this page.');
			}
			$question_settings = $input['quiz']['question'][$question_id];
			$question_settings = self::merge_default_question_settings($question_settings);

			$outcome_settings = ProgressAllyBackendShared::convert_setting_string_to_array($_POST['outcome']);
			if (!isset($outcome_settings['quiz'])) {
				$outcome_settings = self::$default_quiz_settings;
			} else {
				$outcome_settings = $outcome_settings['quiz'];
			}
			$outcome_settings = self::merge_default_settings($outcome_settings);
			$survey_outcome_options = self::generate_survey_outcome_options($outcome_settings);
			$new_code = self::generate_backend_question_code('--qid--', $question_settings, $survey_outcome_options);

			$new_code = preg_replace('/s--quiz-type-.*?--w/', '', $new_code);
			$new_code = preg_replace('/s--select-survey-outcome-.*?--d/', '', $new_code);

			echo json_encode(array(
					'status' => 'success',
					'code' => $new_code
				));
		} catch (Exception $ex) {
			$error = array('status' => 'error', 'message' => $ex->getMessage());
			echo json_encode($error);
		}
		die();
	}
	// </editor-fold>
}