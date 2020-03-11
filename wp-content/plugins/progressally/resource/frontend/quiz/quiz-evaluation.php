<?php
if (!class_exists('ProgressAllyQuizEvaluation')) {
	class ProgressAllyQuizEvaluation {
		public static function add_actions() {
			add_action('wp_ajax_progressally_submit_quiz', array(__CLASS__, 'submit_quiz_callback'));
			add_action('wp_ajax_nopriv_progressally_submit_quiz', array(__CLASS__, 'submit_quiz_callback'));

			add_action('wp_ajax_progressally_reset_quiz', array(__CLASS__, 'reset_quiz_callback'));
			add_action('wp_ajax_nopriv_progressally_reset_quiz', array(__CLASS__, 'reset_quiz_callback'));
		}
		private static function evaluate_score_quiz($settings, $input) {
			$result = array('type' => 'score', 'question' => array(), 'score' => 0, 'pass' => true);
			$question_count = count($settings['quiz']['question']);
			$correct_count = 0;
			foreach ($settings['quiz']['question'] as $id => $question) {
				$question_id = 'progressally-question-' . $id;
				if ($question['radio-correct'] === $input[$question_id]) {
					$correct_count++;
					$result['question'][$question_id] = 'yes';
				} else {
					$result['question'][$question_id] = 'no';
				}
			}
			if ($question_count !== 0) {
				$result['score'] = $correct_count/$question_count;
			}
			
			$result['pass'] = self::evaluate_score_quiz_pass($settings, $result['score']);
			$result['tag'] = $result['pass'] ? $settings['quiz']['grade-outcome-pass-tag'] : $settings['quiz']['grade-outcome-fail-tag'];
			return $result;
		}
		public static function evaluate_score_quiz_pass($settings, $score) {
			$pass = true;
			$threshold_id = $settings['quiz']['grade-outcome-threshold-id'];
			if (isset($settings['quiz']['grade-outcome'][$threshold_id])) {
				$threshold = $settings['quiz']['grade-outcome'][$threshold_id]['min-score'] / 100;
				if ($score < $threshold) {
					$pass = false;
				}
			}
			return $pass;
		}
		private static function evaluate_survey_quiz($settings, $input) {
			$result = array('type' => 'survey', 'outcome' => '', 'tag' => '', 'field-update' => array());
			$num_outcome = $settings['quiz']['survey-num-outcome'];
			$outcome_weights = array_fill(0, $num_outcome, 0);
			foreach ($settings['quiz']['question'] as $id => $question) {
				$question_id = 'progressally-question-' . $id;
				$selected_choice = $input[$question_id];
				if (isset($question['choice'][$selected_choice])) {
					$target_outcome = $question['choice'][$selected_choice]['select-survey-outcome'];
					if ($target_outcome > 0 && $target_outcome <= $num_outcome) {
						// the outcome ID starts at 1, so we need to subtract 1 to be 0-offset
						$outcome_weights[$target_outcome - 1] += $question['survey-weight'];
					}
				}
			}
			$max_weight = 0;
			$max_outcome_ordinal = 1;
			foreach ($settings['quiz']['survey-outcome'] as $outcome_ordinal => $outcome_config) {
				if (isset($outcome_weights[$outcome_ordinal - 1])) {
					$weight = $outcome_weights[$outcome_ordinal - 1];
					if ($weight > $max_weight) {
						$max_weight = $weight;
						$max_outcome_ordinal = $outcome_ordinal;
					}
					if (!empty($outcome_config['value-field'])) {
						$result['field-update'][$outcome_config['value-field']] = $weight;
					}
				}
			}
			if (isset($settings['quiz']['survey-outcome'][$max_outcome_ordinal])) {
				$result['outcome'] = $max_outcome_ordinal;
				if (!empty($settings['quiz']['survey-outcome'][$max_outcome_ordinal]['access-tag'])) {
					$result['tag'] =  $settings['quiz']['survey-outcome'][$max_outcome_ordinal]['access-tag'];
				}
			}
			return $result;
		}
		private static function evaluate_segment_quiz($settings, $input) {
			$result = array('type' => 'segment', 'score' => '');
			$outcome_score = 0;
			foreach ($settings['quiz']['question'] as $id => $question) {
				$question_id = 'progressally-question-' . $id;
				$selected_choice = $input[$question_id];
				if (isset($question['choice'][$selected_choice])) {
					if (isset($question['choice'][$selected_choice]['segment-score'])) {
						$outcome_score += intval($question['choice'][$selected_choice]['segment-score']);
					}
				}
			}
			$result['score'] = $outcome_score;
			
			$outcome = self::get_segement_quiz_outcome($settings, $outcome_score);
			$result['tag'] = isset($settings['quiz']['segment-outcome'][$outcome]) ? $settings['quiz']['segment-outcome'][$outcome]['access-tag'] : '';
			return $result;
		}
		
		private static function evaluate_quiz($settings, $input) {
			if ($settings['quiz']['select-quiz-type'] === 'survey') {
				return self::evaluate_survey_quiz($settings, $input);
			} elseif ($settings['quiz']['select-quiz-type'] === 'segment') {
				return self::evaluate_segment_quiz($settings, $input);
			}
			return self::evaluate_score_quiz($settings, $input);
		}
		public static function submit_quiz_callback() {
			$nonce = $_REQUEST['progressally_update_nonce'];
			
			if (!wp_verify_nonce($nonce, 'progressally-update-progress-nonce')) {
				die();
			}
			if (!isset($_REQUEST['p']) || !isset($_REQUEST['input'])) {
				die();
			}
			$post_id = intval($_REQUEST['p']);
			$post_meta = ProgressAllyTaskDefinition::get_post_progress_meta($post_id);

			$form_data = $_REQUEST['input'];
			$form_data = str_replace('|-|', '=', $form_data);
			$input = array();
			parse_str($form_data, $input);

			$result = self::evaluate_quiz($post_meta, $input);

			$progress_result = ProgressAllyUserProgress::update_quiz_completion($post_id, $post_meta, $result, $input);

			echo json_encode($progress_result);
			die();
		}
		public static function reset_quiz_callback() {
			$nonce = $_POST['progressally_update_nonce'];
			
			if (!wp_verify_nonce($nonce, 'progressally-update-progress-nonce')) {
				die();
			}
			if (!isset($_POST['p'])) {
				die();
			}
			$post_id = intval($_POST['p']);

			$progress_result = ProgressAllyUserProgress::reset_quiz($post_id);

			echo json_encode($progress_result);
			die();
		}
		public static function generate_quiz_result_html($settings, $quiz_result) {
			$result_popup = '';
			$result_code = '';
			if ($settings['quiz']['select-quiz-type'] === 'survey') {
				if (isset($quiz_result['type']) && $quiz_result['type'] === 'survey' && isset($quiz_result['outcome'])) {
					if (isset($settings['quiz']['survey-outcome'][$quiz_result['outcome']])) {
						$outcome_setting = $settings['quiz']['survey-outcome'][$quiz_result['outcome']];
						$result_code = $outcome_setting['html'];
						$result_code .= self::generate_quiz_optin_code($outcome_setting);
						$result_popup = self::get_quiz_popup($outcome_setting);
					}
				}
			} elseif ($settings['quiz']['select-quiz-type'] === 'segment') {
				$score = 0;
				if (isset($quiz_result['type']) && $quiz_result['type'] === 'segment' && isset($quiz_result['score'])) {
					$score = $quiz_result['score'];
				}
				$outcome = self::get_segement_quiz_outcome($settings, $score);
				if (isset($settings['quiz']['segment-outcome'][$outcome])) {
					$outcome_setting = $settings['quiz']['segment-outcome'][$outcome];
					$result_code = $outcome_setting['html'];
					$result_code .= self::generate_quiz_optin_code($outcome_setting);
					$result_popup = self::get_quiz_popup($outcome_setting);
				}
				if (strpos($result_code, '{[score]}') !== false) {
					$result_code = str_replace('{[score]}', $score, $result_code);
				}
			} else {
				$score = 0;
				if (isset($quiz_result['type']) && $quiz_result['type'] !== 'score') {	// for backwards compatibility (before the type is included in the result
					$score = 0;
				} else {
					if (isset($quiz_result['score'])) {
						$score = $quiz_result['score'];
					}
				}

				// default the result code to the lowest tier (0%)
				$outcome = 0;
				if (isset($settings['quiz']['grade-outcome']['1'])) {
					$outcome = 1;
				}
				// The outcome array is in increasing ordered, ie. higher the index, higher the score
				$max_outcome = $settings['quiz']['grade-num-outcome'];
				for ($i = $max_outcome; $i > 0; --$i ) {
					if (isset($settings['quiz']['grade-outcome'][$i])) {
						if ($score >= $settings['quiz']['grade-outcome'][$i]['min-score']/100) {
							$outcome = $i;
							break;
						}
					}
				}
				if ($outcome > 0) {
					$outcome_setting = $settings['quiz']['grade-outcome'][$outcome];
					$result_code = $outcome_setting['html'];
					$result_code .= self::generate_quiz_optin_code($outcome_setting);
					$result_popup = self::get_quiz_popup($outcome_setting);
				}
				if (strpos($result_code, '{{percentage}}') !== false) {	// legacy replacement
					$result_code = str_replace('{{percentage}}', number_format($score*100, 0) . '%', $result_code);
				}
				if (strpos($result_code, '{[percentage]}') !== false) {
					$result_code = str_replace('{[percentage]}', number_format($score*100, 0) . '%', $result_code);
				}
			}

			// this is needed for BeaverBuilder saved row / module, or the necessary styling file won't be included
			$wp_actions_wp_enqueue_scripts_modified = false;
			global $wp_actions;
			if (!isset($wp_actions['wp_enqueue_scripts'])) {
				$wp_actions['wp_enqueue_scripts'] = 1;
				$wp_actions_wp_enqueue_scripts_modified = true;
			}

			$result_code = do_shortcode($result_code);

			if ($wp_actions_wp_enqueue_scripts_modified) {
				unset($wp_actions['wp_enqueue_scripts']);
			}

			return array('html' => $result_code, 'popup' => $result_popup);
		}

		// <editor-fold defaultstate="collapsed" desc="Get just the quiz result as a simple string (percentage for grading. the outcome name for personality test and scoring)">
		public static function get_quiz_result($settings, $quiz_result) {
			$result = false;
			if ($settings['quiz']['select-quiz-type'] === 'survey') {
				if (isset($quiz_result['type']) && $quiz_result['type'] === 'survey' && isset($quiz_result['outcome'])) {
					if (isset($settings['quiz']['survey-outcome'][$quiz_result['outcome']])) {
						$outcome_setting = $settings['quiz']['survey-outcome'][$quiz_result['outcome']];
						$result = $outcome_setting['name'];
					}
				}
			} elseif ($settings['quiz']['select-quiz-type'] === 'segment') {
				if (isset($quiz_result['type']) && $quiz_result['type'] === 'segment' && isset($quiz_result['score'])) {
					$result = $quiz_result['score'];
				}
			} else {
				$score = 0;
				if (isset($quiz_result['type']) && $quiz_result['type'] !== 'score') {	// for backwards compatibility (before the type is included in the result
					$score = 0;
				} else {
					if (isset($quiz_result['score'])) {
						$score = $quiz_result['score'];
					}
				}
				$result = number_format($score * 100, 0) . '%';
			}
			return $result;
		}
		// </editor-fold>
		
		public static function get_segement_quiz_outcome($settings, $score) {
			$outcome = 0;
			// default the result code to the lowest tier (0)
			if (isset($settings['quiz']['segment-outcome']['1'])) {
				$outcome = 1;
			}
			// The outcome array is in increasing ordered, ie. higher the index, higher the score
			$max_outcome = $settings['quiz']['segment-num-outcome'];
			for ($i = $max_outcome; $i > 0; --$i ) {
				if (isset($settings['quiz']['segment-outcome'][$i])) {
					if ($score >= $settings['quiz']['segment-outcome'][$i]['min-score']) {
						$outcome = $i;
						break;
					}
				}
			}

			return $outcome;
		}
		
		private static function generate_quiz_optin_code($outcome_settings) {
			$popup_type = $outcome_settings['select-popup-type'];
			$popup_id = $outcome_settings['optin-popup'];
			$code = '';
			if ($popup_type === 'embedded' && !empty($popup_id) && 
				class_exists('PopupAllyPro') && PopupAllyPro::$popupally_pro_enabled && method_exists('PopupAllyProAPI', 'get_popup_code')) {
				$popup_code = PopupAllyProAPI::get_popup_code($popup_id);
				if (!empty($popup_code)) {
					$code = '<div class="progressally-quiz-result-optin-container">' . $popup_code . '</div>';
				}
			}
			return $code;
		}
		private static function get_quiz_popup($outcome_settings) {
			$popup_type = $outcome_settings['select-popup-type'];
			$popup_id = $outcome_settings['optin-popup'];
			if ($popup_type === 'popup') {
				return $popup_id;
			}
			return '';
		}
	}
}