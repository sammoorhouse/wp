<?php
if (!class_exists('ProgressAllyQuizDisplay')) {
	class ProgressAllyQuizDisplay {
		public static function add_shortcodes() {
			add_shortcode( 'progressally_quiz', array(__CLASS__, 'shortcode_progressally_quiz'));
			add_shortcode( 'progressally_quiz_result', array(__CLASS__, 'shortcode_progressally_quiz_result'));
		}

		private static $cached_question_template_vertical = null;
		private static $cached_question_template_horizontal = null;
		private static function get_question_template($display_type) {
			if ($display_type === 'horizontal') {
				if (self::$cached_question_template_horizontal === null) {
					self::$cached_question_template_horizontal = file_get_contents(dirname(__FILE__) . '/quiz-question-template-horizontal.php');
				}
				return self::$cached_question_template_horizontal;
			} else {
				if (self::$cached_question_template_vertical === null) {
					self::$cached_question_template_vertical = file_get_contents(dirname(__FILE__) . '/quiz-question-template.php');
				}
				return self::$cached_question_template_vertical;
			}
		}
		private static $cached_question_choice_template_vertical = null;
		private static $cached_question_choice_template_horizontal = null;
		private static function get_question_choice_template($display_type) {
			if ($display_type === 'horizontal') {
				if (self::$cached_question_choice_template_horizontal === null) {
					self::$cached_question_choice_template_horizontal = file_get_contents(dirname(__FILE__) . '/quiz-question-choice-template-horizontal.php');
				}
				return self::$cached_question_choice_template_horizontal;
			} else {
				if (self::$cached_question_choice_template_vertical === null) {
					self::$cached_question_choice_template_vertical = file_get_contents(dirname(__FILE__) . '/quiz-question-choice-template.php');
				}
				return self::$cached_question_choice_template_vertical;
			}
		}
		private static $quiz_ordinal = 0;
		public static function shortcode_progressally_quiz($atts) {
			extract( shortcode_atts( array(
				'prefix' => '',
				'post_id' => '',
			), $atts, 'progressally_quiz' ) );
			self::$quiz_ordinal += 1;
			$post_id = intval($post_id);
			$post_id = $post_id <= 0 ? get_the_ID(): $post_id;

			$settings = ProgressAllyTaskDefinition::get_post_progress_meta($post_id);
			if ($settings['checked-enable-quiz'] !== 'yes') {
				return false;
			}
			
			$quiz_input = null;
			$quiz_result = null;
			$user_id = ProgressAllyUserProgress::get_user_id();
			$user_meta = ProgressAllyUserProgress::get_user_progress_meta($user_id);
			if (isset($user_meta[$post_id]) && isset($user_meta[$post_id]['quiz'])) {
				if (isset($user_meta[$post_id]['quiz']['input'])) {
					$quiz_input = $user_meta[$post_id]['quiz']['input'];
				}
				if (isset($user_meta[$post_id]['quiz']['result'])) {
					$quiz_result = $user_meta[$post_id]['quiz']['result'];
				}
			}

			$container_code = file_get_contents(dirname(__FILE__) . '/quiz-template.php');

			$question_code = '';
			// Output questions in batches
			$num_question_per_page = intval($settings['quiz']['num-question-per-page']);
			$num_question = count($settings['quiz']['question']);
			if ($num_question_per_page <= 0) {
				$num_question_per_page = $num_question;
			}
			$num_page = ceil($num_question / $num_question_per_page);

			$question_page_template = file_get_contents(dirname(__FILE__) . '/quiz-question-page-template.php');
			for ($page_index = 1; $page_index <= $num_page; ++$page_index) {
				$question_code .= self::generate_frontend_quiz_page($settings['quiz'], $question_page_template, $num_question_per_page, $page_index, $num_page, $quiz_input);
			}
			$container_code = str_replace('{{quiz-questions}}', $question_code, $container_code);
			$container_code = str_replace('{{correct-message}}', do_shortcode($settings['quiz']['correct-message-html']), $container_code);

			list($show_retake_button, $num_rest) = ProgressAllyUserProgress::can_reset_quiz($post_id, $settings, $user_id, $user_meta);
			$show_submit_button = false;
			$result_code = '';
			if ($quiz_result !== null) {
				$result_html = ProgressAllyQuizEvaluation::generate_quiz_result_html($settings, $quiz_result);
				$result_code = $result_html['html'];
			} else {
				$show_submit_button = true;
			}
			$quiz_current_page = $num_page;
			if ($num_page > 1 && $show_submit_button) {
				// place to the first page if not submitted
				$quiz_current_page = 1;
			}
			$container_code = str_replace('s--page-' . $quiz_current_page . '--d', '', $container_code);
			$container_code = preg_replace('/s--page-.*?--d/', 'style="display:none;"', $container_code);

			$container_code = str_replace('{{quiz-current-page}}', $quiz_current_page, $container_code);

			$container_code = str_replace('{{submit-row-display}}', $show_submit_button ? 'style="display:none;"' : '', $container_code);

			// Submit & Retake buttons
			$container_code = str_replace('{{quiz-result}}', $result_code, $container_code);
			$container_code = str_replace('{{retake-button-display}}', $show_retake_button ? '' : 'style="display:none;"', $container_code);
			$container_code = str_replace('{{submit-button-display}}', $show_submit_button ? '' : 'style="display:none;"', $container_code);
			$container_code = str_replace('{{submit-button-text}}', esc_html($settings['quiz']['submit-button-text']), $container_code);
			$container_code = str_replace('{{retake-button-text}}', esc_html($settings['quiz']['retake-button-text']), $container_code);
			$container_code = str_replace('{{back-button-text}}', esc_html($settings['quiz']['back-button-text']), $container_code);
			$container_code = str_replace('{{next-button-text}}', esc_html($settings['quiz']['next-button-text']), $container_code);
			
			$container_code = str_replace('{{prefix}}', $prefix, $container_code);
			$container_code = str_replace('{{post-id}}', $post_id, $container_code);
			$container_code = str_replace('{{ordinal}}', self::$quiz_ordinal, $container_code);

			return $container_code;
		}

		public static function preview_quiz() {
			$preview_code = file_get_contents(dirname(__FILE__) . '/quiz-template.php');

			$code = self::generate_frontend_question_code("1", ProgressAllyQuiz::$default_question_settings, false, 'horizontal', 'preview');

			$preview_code = str_replace('{{quiz-questions}}', $code, $preview_code);

			$preview_code = str_replace('{{correct-message}}', "Correct", $preview_code);
			$preview_code = str_replace('{{submit-button-text}}', 'Submit Answers!', $preview_code);
			$preview_code = str_replace('{{retake-button-text}}', 'Retake Quiz', $preview_code);

			$preview_code = str_replace('{{retake-button-display}}', 'style="display:none;"', $preview_code);
			$preview_code = str_replace('{{submit-button-display}}', 'disabled="disabled"', $preview_code);

			$preview_code = str_replace('{{quiz-result}}', '<div class="progressally-quiz-result"><hr/>Congratulations! You scored 100%!</div>', $preview_code);
			$preview_code = str_replace('{{result-display}}', '', $preview_code);

			$preview_code = str_replace('{{prefix}}', '', $preview_code);
			$preview_code = str_replace('{{post-id}}', '', $preview_code);
			$preview_code = str_replace('{{ordinal}}', '1', $preview_code);
			
			return $preview_code;
		}

		private static function generate_frontend_question_code($question_id, $question_settings, $input_choice, $choice_display, $quiz_type) {
			// $input_choice === false for preview, show both correct and incorrect message
			// $input_choice === '' for unsubmitted quiz
			$code = self::get_question_template($choice_display);
			$code = str_replace('{{question}}', do_shortcode($question_settings['question-html']), $code);
			
			$choices_code = '';
			foreach ($question_settings['order'] as $choice_id) {
				$choice_settings = $question_settings['choice'][$choice_id];
				$choices_code .= self::generate_frontend_choice_code($choice_id, $choice_settings, $input_choice, $choice_display);
			}

			if ($quiz_type === 'preview') {
				$correct_message_display = '';
				$incorrect_message_display = '';
			} elseif ($quiz_type === 'score' && !empty($input_choice)) {
				$correct_message_display = $question_settings['radio-correct'] === $input_choice ? '' : 'style="display:none"';
				$incorrect_message_display = $question_settings['radio-correct'] === $input_choice ? 'style="display:none"' : '';
			} else {
				$correct_message_display = 'style="display:none"';
				$incorrect_message_display = 'style="display:none"';
			}
			$code = str_replace('{{correct-message-display}}', $correct_message_display, $code);
			$code = str_replace('{{incorrect-message-display}}', $incorrect_message_display, $code);
			$code = str_replace('{{incorrect-message}}', do_shortcode($question_settings['incorrect-message-html']), $code);

			$code = str_replace('{{quiz-choices}}', $choices_code, $code);
			$code = str_replace('{{question-id}}', $question_id, $code);
			return $code;
		}

		private static function generate_frontend_choice_code($choice_id, $choice_settings, $input_choice, $choice_display) {
			$code = self::get_question_choice_template($choice_display);
			$code = str_replace('{{choice-id}}', $choice_id, $code);
			$code = str_replace('{{choice}}', do_shortcode($choice_settings['html']), $code);
			
			$additional_status = '';
			if ($input_choice === null || $input_choice === '' || $input_choice === false) {
				$additional_status = '';
			} else {
				$additional_status = $input_choice === $choice_id ? 'checked="checked"' : 'disabled="disabled"';
			}
			$code = str_replace('{{is-checked}}', $additional_status, $code);
			return $code;
		}

		private static function generate_frontend_quiz_page($quiz_settings, $question_page_template, $num_question_per_page, $page_index, $num_page, $user_quiz_input) {
			$question_batch_code = '';
			$question_ordinal = -1;
			$first_question_ordinal = ($page_index - 1) * $num_question_per_page;
			$last_question_ordinal = $first_question_ordinal + $num_question_per_page;
			foreach ($quiz_settings['question-order'] as $question_id) {
				if (!isset($quiz_settings['question'][$question_id])) {
					continue;
				}
				$question_settings = $quiz_settings['question'][$question_id];

				++$question_ordinal;
				if ($question_ordinal < $first_question_ordinal) {
					continue;
				}
				if ($question_ordinal >= $last_question_ordinal) {
					break;
				}
				if ($user_quiz_input === null || !isset($user_quiz_input['progressally-question-' . $question_id])) {
					$input_choice = '';
				} else {
					$input_choice = $user_quiz_input['progressally-question-' . $question_id];
				}
				$question_batch_code .= self::generate_frontend_question_code($question_id, $question_settings, $input_choice, $quiz_settings['choice-display'], $quiz_settings['select-quiz-type']);
			}
			$page_code = $question_page_template;
			$page_code = str_replace('{{quiz-question-batch}}', $question_batch_code, $page_code);

			$footer_code = self::generate_frontend_footer_template($page_index, $num_page);
			$page_code = str_replace('{{quiz-page-footer}}', $footer_code, $page_code);

			$page_code = str_replace('{{page-index}}', $page_index, $page_code);
			return $page_code;
		}
		private static function generate_frontend_footer_template($page_index, $num_page) {
			$footer_code = '';
			if ($page_index > 1) {
				// First page
				$footer_code .= '<div class="progressally-quiz-button progressally-quiz-back-button" progressally-nav-target="progressally-quiz-current-page-{{ordinal}}" progressally-nav-value="' . ($page_index - 1) . '">' .
									'{{back-button-text}}' .
								'</div>';
			}
			if ($page_index < $num_page) {
				$footer_code .= '<div class="progressally-quiz-button progressally-quiz-next-button" progressally-validate="progressally-quiz-page-{{ordinal}}-{{page-index}}" progressally-nav-target="progressally-quiz-current-page-{{ordinal}}" progressally-nav-value="' . ($page_index + 1) . '">' .
									'{{next-button-text}}' .
								'</div>';
			} else {
				// Last page
				$footer_code .= '<input class="progressally-quiz-button progressally-quiz-submit-button progressally-quiz-submit-button-{{post-id}}" type="submit" value="{{submit-button-text}}" {{submit-button-display}} />';
			}
			if ($num_page > 1) {
				// More than one page
				$footer_code .= '<div class="progressally-quiz-nav-progress">' . $page_index . '/' . $num_page . '</div>';
			}

			if (!empty($footer_code)) {
				$footer_code = '<div>' .
									$footer_code .
									'<div style="height:1px;clear:both"></div>' .
								'</div>';
			}
			return $footer_code;
		}

		// <editor-fold defaultstate="collapsed" desc="Show the quiz result">
		public static function shortcode_progressally_quiz_result($atts) {
			extract( shortcode_atts( array(
				'prefix' => '',
				'post_id' => '',
				'user_id' => '',
				'request' => '',
				'context' => 'local'
			), $atts, 'progressally_quiz_result' ) );
			$post_id = intval($post_id);
			$post_id = $post_id <= 0 ? get_the_ID(): $post_id;

			$settings = ProgressAllyTaskDefinition::get_post_progress_meta($post_id);
			if ($settings['checked-enable-quiz'] !== 'yes') {
				return false;
			}
			$quiz_result = null;

			$display_user_id = ProgressAllyBackendShared::get_current_user_id_for_display($user_id, $request, $context, true);

			$user_meta = ProgressAllyUserProgress::get_user_progress_meta($display_user_id);
			if (isset($user_meta[$post_id]) && isset($user_meta[$post_id]['quiz'])) {
				if (isset($user_meta[$post_id]['quiz']['result'])) {
					$quiz_result = $user_meta[$post_id]['quiz']['result'];
				}
			}
			if (empty($quiz_result)) {
				return false;
			}
			$result = ProgressAllyQuizEvaluation::get_quiz_result($settings, $quiz_result);
			return $result;
		}
		// </editor-fold>
	}
}

