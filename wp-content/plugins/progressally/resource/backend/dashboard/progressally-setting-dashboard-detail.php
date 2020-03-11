<?php
if (!class_exists('ProgressAllyDashboardDetail')) {
	class ProgressAllySettingDashboardDetail {
		public static function add_actions() {
			if (ProgressAllySettingLicense::$progressally_enabled) {
				add_action( 'wp_ajax_progressally_get_detail_reports', array(__CLASS__, 'ajax_get_detail_reports'));
			}
		}
		public static function ajax_get_detail_reports() {
			$nonce = $_POST['progressally_access_nonce'];

			try {
				if (!wp_verify_nonce( $nonce, 'progressally-update-nonce')) {
					throw new Exception("Unable to validate request identify. Please refresh the page and make sure you are logged in as an administrator.");
				}
				$param = array('post_id' => ProgressAllyUtilities::get_optional_post_data('post_id', 0));
				$post_id = intval($param['post_id']);
				if ($post_id <= 0) {
					throw new Exception('Invalid post id: '. $param['post_id']);
				}
				
				ob_start();
				include dirname(__FILE__) . '/progressally-setting-dashboard-detail-display.php';
				$codes = ob_get_clean();
				
				echo json_encode(array('status' => 'success', 'codes' => $codes));
			} catch (Exception $ex) {
				echo json_encode(array('status' => 'error', 'message' => $ex->getMessage()));
			}
			die();
		}

		// <editor-fold defaultstate="collapsed" desc="Detail reports display">
		public static function show_dashboard_detail_settings() {
			$post_selection = ProgressAllyBackendShared::get_all_post_page_selection_template();
			$post_selection = preg_replace('/s--selected-.*?--d/', '', $post_selection);
			include dirname(__FILE__) . '/progressally-setting-dashboard-detail-setting-display.php';
		}
		// </editor-fold>

		// <editor-fold defaultstate="collapsed" desc="Detail reports display">
		public static function show_stats_meta_box($post_id) {
			$user_progress = ProgressAllyUserProgress::get_post_objective_completion($post_id);
			$total_user_number = ProgressAllyUserAccessTimestamp::get_page_access($post_id);
			$objective_setting = ProgressAllyTaskDefinition::get_post_objectives_in_order($post_id);
			
			$has_objective = count($objective_setting) > 0 ? true : false;
			
			include dirname(__FILE__) . '/progressally-setting-dashboard-detail-progress-display.php';
		}
		public static function show_quiz_stats_meta_box($post_id) {
			$post_meta = ProgressAllyTaskDefinition::get_post_progress_meta($post_id);
			$has_quiz = ($post_meta['checked-enable-quiz'] === 'no') ? false : true;
			
			$quiz_setting = $post_meta['quiz'];
			$total_user_number = ProgressAllyUserAccessTimestamp::get_page_access($post_id);
			$quiz_stats = ProgressAllyQuiz::get_quiz_stats_counter_meta($post_id);
			$quiz_completion_number = $quiz_stats['quiz_completion_counter'];
			
			if ($quiz_completion_number > 0) {
				$outcome_array = self::generate_quiz_result_stats_codes($quiz_setting, $quiz_stats);
				$input_array = self::generate_quiz_input_stats_codes($quiz_setting['question'], $quiz_stats['question']);
			}
			
			$quiz_stats_url = ProgressAllyQuiz::generate_quiz_stats_url($post_id);
			
			include dirname(__FILE__) . '/progressally-setting-dashboard-detail-quiz-display.php';
		}
		
		private static function generate_quiz_result_stats_codes($quiz_setting, $quiz_stats) {
			$quiz_type = $quiz_setting['select-quiz-type'];
			$quiz_completion_number = $quiz_stats['quiz_completion_counter'];
			$outcome_array = array();
			
			if ($quiz_type === 'score') {
				$outcome_number = $quiz_stats['grade_pass_counter'];
				$percentage_bar = ProgressAllyProgressDisplay::generate_progress_bar_for_stats($outcome_number / $quiz_completion_number);
				$outcome_array[] = array('label' => 'PASSED', 'percentage_bar' => $percentage_bar);
			} elseif ($quiz_type === 'survey') {
				foreach ($quiz_setting['survey-outcome'] as $outcome_id => $outcome_settings) {
					$outcome_number = $quiz_stats['survey-outcome'][$outcome_id];
					$percentage_bar = ProgressAllyProgressDisplay::generate_progress_bar_for_stats($outcome_number / $quiz_completion_number);
					$label = $outcome_id . '. ' . $outcome_settings['name'];
					$outcome_array[] = array('label' => $label, 'percentage_bar' => $percentage_bar);
				}
			} elseif ($quiz_type === 'segment') {
				$max_score = -1;
	
				foreach ($quiz_setting['segment-outcome'] as $id => $outcome_settings) {
					$outcome_number = $quiz_stats['segment-outcome'][$id];
					$percentage_bar = ProgressAllyProgressDisplay::generate_progress_bar_for_stats($outcome_number / $quiz_completion_number);
					$min_score = $outcome_settings['min-score'];
					if ($max_score > 0) {
						$label = 'Score range: ' . $min_score . ' - ' . $max_score;
					} else {
						$label = 'Score range: ' . $min_score . '+';
					}
					$outcome_array[] = array('label' => $label, 'percentage_bar' => $percentage_bar);
					$max_score = $min_score;
				}
			}

			return $outcome_array;
		}
		
		private static function generate_quiz_input_stats_codes($question_setting, $question_stats) {
			$input_array = array();
			foreach ($question_setting as $id => $question) {
				$detail = array();
				$stats = $question_stats[$id];
				foreach ($question['order'] as $choice_order) {
					$detail[] = array('choice' => $question['choice'][$choice_order]['html'], 'counter' => $stats[$choice_order]);
				}
				$input_array[] = array('question' => $question['question-html'], 'detail' => $detail);
			}
			return $input_array;
		}
		// </editor-fold>
	}
}