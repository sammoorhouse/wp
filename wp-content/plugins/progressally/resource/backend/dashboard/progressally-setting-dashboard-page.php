<?php
class ProgressAllySettingDashboardPage {
	public static function add_actions() {
		if (ProgressAllySettingLicense::$progressally_enabled) {
			add_action( 'wp_ajax_progressally_get_page_overview', array(__CLASS__, 'ajax_get_pages'));
			add_action( 'wp_ajax_progressally_get_access_pages_by_id', array(__CLASS__, 'ajax_search_page'));
		}
	}

	// <editor-fold defaultstate="collapsed" desc="Page tree display">
	public static function ajax_get_pages() {
		$nonce = $_POST['progressally_access_nonce'];
		try {
			if (!wp_verify_nonce( $nonce, 'progressally-update-nonce')) {
				throw new Exception("Unable to validate request identify. Please refresh the page and make sure you are logged in as an administrator.");
			}

			$filter_data = ProgressAllyBackendShared::convert_setting_string_to_array($_POST['param']);
			$page_type = $filter_data['page-type'];
			$num = 20;
			$page = max(0, $filter_data['page-num'] - 1);
			$name_filter = $filter_data['page-name'];
			$offset = $num * $page;
			$total = 0;
			$code = '<div class="progressally-dashboard-page-row-wrapper"><div class="progressally-dashboard-page-row"><div class="progressally-dashboard-page-title">Cannot find any page/post</div></div></div>';

			if (empty($name_filter) && is_post_type_hierarchical($page_type)) {
				$all_pages = ProgressAllyBackendShared::get_all_hierarchical_posts($page_type);
				$total = count($all_pages);
				if ($total > 0) {
					// if the offset (starting point) exceeds the length, then just show the beginning
					if ($offset > $total) {
						$offset = 0;
						$page = 0;
					}
					$code = self::construct_hierarchical_list($all_pages, $offset, $num);
				}
			} else {
				$all_posts = ProgressAllyBackendShared::get_all_posts($page_type, $offset, $num, $name_filter);
				$total = ProgressAllyBackendShared::get_post_count($page_type, $name_filter);
				if (!empty($all_posts)) {
					$code = self::construct_flat_list($all_posts);
				}
			}
			$max = max(1, ceil($total / $num));
			$page = min($max, $page + 1);
			echo json_encode(array('status' => 'success', 'code' => $code, 'page-num' => $page, 'max' => $max));
		} catch (Exception $ex) {
			echo json_encode(array('status' => 'error', 'message' => $ex->getMessage()));
		}
		die();
	}

	private static function construct_flat_list($all_posts) {
		$post_id_list = array();
		foreach($all_posts as $post) {
			$post_id_list []= $post->ID;
		}
		
		$completion_data = ProgressAllyUserProgress::batch_get_post_completion($post_id_list);
		$code = '';
		foreach($all_posts as $post) {
			$code .= self::generate_page_row_codes($post->ID, $post->post_title, $completion_data);
		}
		return $code;
	}
	private static function construct_hierarchical_list($all_pages, $start, $num) {
		$list = array();
		$post_id_list = array();
		$length = count($all_pages);
		if ($start >= $length) {
			return $list;
		}
		$end = min($start + $num, $length);
		
		$preceeding_pages = array();
		for ($i = 0; $i < $start; ++$i) {
			$preceeding_pages[$all_pages[$i]->ID] = $all_pages[$i];
		}
		$depth = array();
		$page = $all_pages[$start];
		/* include all the parents */
		while(0 != $page->post_parent) {
			$depth []= $page->post_parent;
			$page = $preceeding_pages[$page->post_parent];
		}
		$depth = array_reverse($depth);
		$depth_count = count($depth);
		for ($i = 0; $i < $depth_count; ++$i) {
			$page_id = $depth[$i];
			$title = $preceeding_pages[$page_id]->post_title . ' (' . $page_id . ')';
			$list []= array('id' => $page_id,
				'title' => $title,
				'depth' => 0);
			$post_id_list[] = $page_id;
		}
		/* construct the tree */
		for ($i = $start; $i < $end; ++$i) {
			$page = $all_pages[$i];
			if (0 == $page->post_parent) {
				if (count($depth) > 0) {
					$depth = array();
					$depth_count = 0;
				}
			} elseif (end($depth) === $page->post_parent) {
			} elseif (in_array($page->post_parent, $depth)) {
				while(end($depth) !== $page->post_parent) {
					array_pop($depth);
					--$depth_count;
				}
			} else {
				$depth []= $page->post_parent;
				++$depth_count;
			}
			$list []= array('id' => $page->ID,
				'title' => $page->post_title,
				'depth' => $depth_count);
			$post_id_list[] = $page->ID;
		}

		$completion_data = ProgressAllyUserProgress::batch_get_post_completion($post_id_list);
		$code = '';
		$current_depth = 0;
		foreach ($list as $row) {
			if ($row['depth'] > $current_depth) {
				for ($depth = $current_depth; $depth < $row['depth']; ++$depth) {
					$code .= '<div class="progressally-page-tree-indent">';
				}
			} else if ($row['depth'] < $current_depth) {
				for ($depth = $row['depth']; $depth < $current_depth; ++$depth) {
					$code .= '</div>';
				}
			}
			$current_depth = $row['depth'];
			$code .= self::generate_page_row_codes($row['id'], $row['title'], $completion_data);
		}
		for ($depth = 0; $depth < $current_depth; ++$depth) {
			$code .= '</div>';
		}
		return $code;
	}
	private static $cached_page_template = null;
	private static function generate_page_row_codes($post_id, $title, $completion_data) {
		if (null === self::$cached_page_template) {
			self::$cached_page_template = file_get_contents(dirname(__FILE__) . '/progressally-setting-dashboard-page-template.php');
		}
		$codes = str_replace('{{post-id}}', $post_id, self::$cached_page_template);
		$codes = str_replace('{{title}}', esc_html($title), $codes);
		$codes = str_replace('{{edit-link}}', esc_attr(get_edit_post_link($post_id)), $codes);
		
		$total_user_number = ProgressAllyUserAccessTimestamp::get_page_access($post_id);
		$completion = isset($completion_data[$post_id]) ? $completion_data[$post_id] : -1;

		$progress_codes = self::generate_page_row_progress_codes($completion, $total_user_number);
		$codes = str_replace('{{completion-rate}}', $progress_codes, $codes);

		$quiz_codes = self::generate_page_row_quiz_codes($post_id, $total_user_number);
		$codes = str_replace('{{quiz-stats}}', $quiz_codes, $codes);
		
		return $codes;
	}
	private static function generate_page_row_progress_codes($completion, $total_user_number) {
		if (is_array($completion)) {
			// Generate progress bar and numbers
			$total_user = '<li>' . $total_user_number . ' users accessed this page.</li>';
			$progress = '<li>' . $completion['full'] . ' users have completed all objectives.</li><li>' . $completion['partial'] . ' users have completed at least one objective.</li>';
			$codes = '<ul>' .  $progress . $total_user .'</ul>';
		} else {
			// No objective defined in the page/post
			$codes = 'No objective defined in this page/post.';
		}
		
		return $codes;
	}
	private static function generate_page_row_quiz_codes($post_id, $total_user_number) {
		$post_meta = ProgressAllyTaskDefinition::get_post_progress_meta($post_id);
		if ($post_meta['checked-enable-quiz'] === 'yes') {
			$quiz_stats = ProgressAllyQuiz::get_quiz_stats_counter_meta($post_id);
			// Generate numbers for completion
			$completion_number = $quiz_stats['quiz_completion_counter'];
			$total_user = '<li>' . $total_user_number . ' users accessed this page.</li>';
			$progress = '<li>' . $completion_number . ' users have completed the quiz.</li>';
			$codes = '<ul>' . $progress . $total_user . '</ul>';
			
			// Generate progress bar and numbers for graded quiz
			if ($completion_number > 0 && $post_meta['quiz']['select-quiz-type'] === 'score') {
				$pass_number = $quiz_stats['grade_pass_counter'];
				$progress_bar_pass = ProgressAllyProgressDisplay::generate_progress_bar_for_stats($pass_number / $completion_number);
				$progress_bar_pass = '<div class="progressally-dashboard-page-detail-quiz-pass-bar-chart">' . $progress_bar_pass . '</div><div class="progressally-dashboard-page-detail-quiz-pass-label">passed</div>';
				$pass = '<ul><li>' . $pass_number . ' users have passed the quiz.</li></ul>';
				$codes .= $progress_bar_pass . $pass;
			}
		} else {
			// No objective defined in the page/post
			$codes = 'No quiz here.';
		}

		return $codes;
	}
	
	public static function show_dashboard_page_settings() {
		$page_types = array('page' => 'Pages', 'post' => 'Posts');

		$custom_post_types = get_post_types(array('public' => 'true', '_builtin' => false), 'object');
		foreach($custom_post_types as $post_type) {
			$page_types[$post_type->name] = $post_type->label;
		}

		include dirname(__FILE__) . '/progressally-setting-dashboard-page-display.php';
	}
	// </editor-fold>
}
