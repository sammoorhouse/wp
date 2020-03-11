<?php
if (!class_exists('ProgressAllyUserProgress')) {
	class ProgressAllyUserProgress {
		const USER_META_KEY = '_progressally_user_meta';
		const USER_SECONDARY_DATA_META_KEY = '_progressally_user_secondary_progress';
		const COOKIE_NAME = 'progressally-progress-info';
		const OBJECTIVE_COOKIE_NAME = 'progressally-objective-info';

		// <editor-fold defaultstate="collapsed" desc="Database operations">
		public static function initialize_database_names() {
			global $wpdb;

			$wpdb->pa_user_progress = $wpdb->prefix . 'pa_user_progress';
		}
		public static function create_database_table_query() {
			global $charset_collate, $wpdb;
			return "CREATE TABLE $wpdb->pa_user_progress (
			  id bigint(20) unsigned NOT NULL auto_increment,
			  user_id bigint(20) unsigned NOT NULL default '0',
			  post_id bigint(20) unsigned NOT NULL default '0',
			  objective_id bigint(20) unsigned NOT NULL default '0',
			  created datetime NOT NULL default '0000-00-00 00:00:00',
			  PRIMARY KEY  (id),
			  KEY user_id (user_id),
			  KEY post_id (post_id),
			  KEY objective_id (objective_id),
			  KEY created (created)
			) $charset_collate;";
		}
		
		public static function convert_database_table() {
			$usermeta_database_table = self::get_usermeta_database_table_for_conversion();
			if (empty($usermeta_database_table)) {
				return;
			}

			$post_objective_update_structure = ProgressAllyPostObjective::calculate_update_list_for_user_progress_conversion();

			$checked_objective_table = array();
			$active_objective_list = $post_objective_update_structure['active_objective_list'];
			$update_order = $post_objective_update_structure['update_order'];
			foreach ($update_order as $post_id => $value) {
				$checked_objective_table[$post_id] = array();
				$active_objectives = $active_objective_list[$post_id];

				// Import the local progress meta for objectives without reference
				if (isset($usermeta_database_table[$post_id])) {
					$user_meta = $usermeta_database_table[$post_id];
					foreach ($user_meta as $user_id => $meta) {
						$checked_objectives = array();
						foreach ($meta as $objective_id => $checked_value) {
							if (isset($active_objectives[$objective_id]) && intval($active_objectives[$objective_id]) === 0) {
								$checked_objectives[$objective_id] = true;
							}
						}
						$checked_objective_table[$post_id][$user_id] = $checked_objectives;
					}
				}
				// Calculate the objective with reference
				foreach ($active_objectives as $objective_id => $ref_post_id) {
					if (intval($ref_post_id) > 0) {
						$user_meta = $checked_objective_table[$ref_post_id];
						$ref_active_objective_count = count($active_objective_list[$ref_post_id]);
						foreach ($user_meta as $user_id => $meta) {
							if (count($meta) === $ref_active_objective_count) {
								if (!isset($checked_objective_table[$post_id][$user_id])) {
									$checked_objective_table[$post_id][$user_id] = array();
								}
								$checked_objective_table[$post_id][$user_id][$objective_id] = true;
							}
						}
					}
				}
			}

			self::convert_database_table_core($checked_objective_table);
		}
		
		private static function convert_database_table_core($checked_objective_table) {
			$timestamp = ProgressAllyBackendShared::get_sql_time();
			global $wpdb;
			$query = "INSERT INTO {$wpdb->pa_user_progress} (post_id, user_id, objective_id, created) VALUES ";
			$values = array();
			foreach ($checked_objective_table as $post_id => $user_checked_objectives) {
				foreach ($user_checked_objectives as $user_id => $checked_objectives) {
					foreach ($checked_objectives as $objective_id => $value) {
						$values []= array($post_id, $user_id, $objective_id, $timestamp);
					}
				}
			}
			ProgressAllyUtilities::batch_insert_entries_database_simple($query, $values);
		}
		
		public static function get_usermeta_database_table_for_conversion() {
			$usermeta_database_table = array();
			$meta_key = self::USER_META_KEY;

			global $wpdb;
			$usermeta_raw = $wpdb->get_results("SELECT user_id,meta_value FROM $wpdb->usermeta WHERE meta_key = '$meta_key'", ARRAY_A);
			if (is_array($usermeta_raw)) {
				foreach ($usermeta_raw as $row) {
					$user_id = $row['user_id'];
					$meta_value = unserialize($row['meta_value']);
					foreach ($meta_value as $post_id => $progress_meta) {
						if (!isset($usermeta_database_table[$post_id])) {
							$usermeta_database_table[$post_id] = array();
						}
						$usermeta_database_table[$post_id][$user_id] = $progress_meta;
					}
				}
			}
			return $usermeta_database_table;
		}
		// </editor-fold>

		// <editor-fold defaultstate="collapsed" desc="activation setup / deactivation cleanup">
		public static function do_activation_actions(){
			wp_cache_delete(self::USER_META_KEY);
		}
		public static function do_deactivation_actions(){
			wp_cache_delete(self::USER_META_KEY);
		}
		// </editor-fold>

		public static function add_actions() {
			add_action('wp_ajax_progressally_update_progress', array(__CLASS__, 'update_progress_callback'));
			add_action('wp_ajax_nopriv_progressally_update_progress', array(__CLASS__, 'update_progress_callback'));
			add_action('wp_ajax_progressally_update_toggle', array(__CLASS__, 'update_toggle_callback'));
			add_action('wp_ajax_nopriv_progressally_update_toggle', array(__CLASS__, 'update_toggle_callback'));
		}

		// <editor-fold defaultstate="collapsed" desc="progress calculation">
		public static function get_progress($post_id, $user_id = false) {
			/* return values: -1 - no objectives; other - progress */
			$user_id = self::get_user_id($user_id);

			$total_objective_count = ProgressAllyPostObjective::get_active_objective_count($post_id);
			if ($total_objective_count === 0) {
				return -1;
			}
			$checked_objectives = self::get_checked_objectives($post_id, $user_id);
			return count($checked_objectives)/$total_objective_count;
		}
		public static function get_user_progress($user_id = false) {
			$user_id = self::get_user_id($user_id);
			if (intval($user_id) === 0) {
				return array();
			}
			
			$user_progress = array();
			$user_checked_objectives = self::get_user_checked_objectives($user_id);
			$active_objective_count_list = ProgressAllyPostObjective::get_active_objective_count_list();
			foreach ($active_objective_count_list as $post_id => $active_objective_count) {
				if (isset($user_checked_objectives[$post_id])) {
					$progress = count($user_checked_objectives[$post_id])/$active_objective_count;
				} else {
					$progress = 0;
				}
				$user_progress[$post_id] = $progress;
			}
			return $user_progress;
		}
		public static function get_post_objective_completion($post_id) {
			$post_objective_completion = array('total' => 0, 'detail' => array());
			$user_checked_objectives = self::batch_get_post_checked_objectives(array($post_id));
			$active_objectives = ProgressAllyPostObjective::get_objectives($post_id);
			$active_objective_count = count($active_objectives);
			
			foreach ($active_objectives as $objective_id => $objectives) {
				$post_objective_completion['detail'][$objective_id] = 0;
			}
			
			if (isset($user_checked_objectives[$post_id])) {
				foreach ($user_checked_objectives[$post_id] as $user_id => $objectives) {
					if (count($objectives) === $active_objective_count) {
						$post_objective_completion['total']++;
					}
					foreach ($objectives as $objective_id => $value) {
						$post_objective_completion['detail'][$objective_id]++;
					}
				}
			}
			return $post_objective_completion;
		}
		public static function batch_get_post_completion($post_list = false) {
			// Temporary: return array uses post_id as key, value contains full completion number and partial completion
			$post_completion = array();
			$user_checked_objectives = self::batch_get_post_checked_objectives($post_list);
			$active_objective_count_list = ProgressAllyPostObjective::get_active_objective_count_list();
			foreach ($active_objective_count_list as $post_id => $active_objective_count) {
				$completion = 0;
				$n_user = 0;
				if (isset($user_checked_objectives[$post_id])) {
					foreach ($user_checked_objectives[$post_id] as $user_id => $objectives) {
						if (count($objectives) === $active_objective_count) {
							$completion++;
						}
						// Temporary: get the number of users who checked at least one objective
						$n_user++;
					}
				}
				$post_completion[$post_id] = array('full' => $completion, 'partial' => $n_user);
			}
			return $post_completion;
		}
		// </editor-fold>

		// <editor-fold defaultstate="collapsed" desc="retrieve the checklist completion time for a specific post">
		public static function get_completion_time($post_id, $user_id = false) {
			$user_id = self::get_user_id($user_id);
			if ($user_id <= 0) {
				return false;
			}

			$total_objective_count = ProgressAllyPostObjective::get_active_objective_count($post_id);
			if ($total_objective_count === 0) {
				return false;
			}
			$checked_objectives = self::get_checked_objectives_timestamp_database($post_id, $user_id);
			if (empty($checked_objectives) || count($checked_objectives) < $total_objective_count) {
				return false;
			}
			// because the database entries are retrieved in reverse-chronological order, the newest created time is the first entry
			$sql_time = $checked_objectives[0]['created'];
			$unix_time = strtotime($sql_time);
			return $unix_time;
		}
		// </editor-fold>

		// <editor-fold defaultstate="collapsed" desc="retrieve user checked objectives">
		public static function get_checked_objectives($post_id, $user_id) {
			$all_user_checked_objective = self::get_user_checked_objectives($user_id);
			if (isset($all_user_checked_objective[$post_id])) {
				return $all_user_checked_objective[$post_id];
			}
			return array();
		}
		public static function clear_user_checked_cache() {
			self::$cached_user_checked_objectives = array();
			self::$cached_cookie_checked_objective = null;
		}
		private static $cached_user_checked_objectives = array();
		private static function get_user_checked_objectives($user_id) {
			if ($user_id <= 0) {
				return self::get_user_checked_objectives_cookie();
			}
			if (!isset(self::$cached_user_checked_objectives[$user_id])) {
				self::$cached_user_checked_objectives[$user_id] = self::get_user_checked_objective_database($user_id);
			}
			return self::$cached_user_checked_objectives[$user_id];
		}
		private static function get_user_checked_objective_database($user_id) {
			$result = array();

			global $wpdb;
			$checked_objectives_raw = $wpdb->get_results("SELECT id, post_id, objective_id FROM $wpdb->pa_user_progress WHERE user_id = $user_id", ARRAY_A);
			if (is_array($checked_objectives_raw)) {
				foreach ($checked_objectives_raw as $row) {
					$post_id = $row['post_id'];
					$objective_id = $row['objective_id'];
					if (!isset($result[$post_id])) {
						$result[$post_id] = array();
					}
					$result[$post_id][$objective_id] = $row['id'];
				}
			}
			return $result;
		}
		private static $cached_cookie_checked_objective = null;	// we only need a single cached variable beceause there is no chance of another user cookie
		private static function get_user_checked_objectives_cookie() {
			if (null === self::$cached_cookie_checked_objective) {
				$meta = ProgressAllyBackendShared::read_cookie(self::OBJECTIVE_COOKIE_NAME);
				if (!is_array($meta)) {
					$meta = array();
				}
				self::$cached_cookie_checked_objective = $meta;
			}
			return self::$cached_cookie_checked_objective;
		}
		private static function get_checked_objectives_timestamp_database($post_id, $user_id) {
			global $wpdb;
			$checked_objectives_raw = $wpdb->get_results("SELECT id, objective_id, created FROM $wpdb->pa_user_progress WHERE user_id = $user_id AND post_id = $post_id ORDER BY created DESC", ARRAY_A);
			if (is_array($checked_objectives_raw)) {
				return $checked_objectives_raw;
			}
			return array();
		}
		// </editor-fold>

		// <editor-fold defaultstate="collapsed" desc="retrieve user secondary progress data">
		private static $cached_user_progress_secondary_data = array();
		private static function get_user_progress_secondary_data($user_id) {
			if (isset(self::$cached_user_progress_secondary_data[$user_id])) {
				return self::$cached_user_progress_secondary_data[$user_id];
			}
			$result = get_user_meta($user_id, self::USER_SECONDARY_DATA_META_KEY, true);
			if (!is_array($result)) {
				$result = array();
			}
			self::$cached_user_progress_secondary_data[$user_id] = $result;
			return $result;
		}
		private static function set_user_progress_secondary_data($user_id, $new_data) {
			update_user_meta($user_id, self::USER_SECONDARY_DATA_META_KEY, $new_data);
			unset(self::$cached_user_progress_secondary_data[$user_id]);
		}
		private static function get_user_progress_completion_custom_operation_count($user_id, $post_id) {
			$data = self::get_user_progress_secondary_data($user_id);
			if (!isset($data[$post_id])) {
				return 0;
			}
			if (!isset($data[$post_id]['completion-custom-operation-count'])) {
				return 0;
			}
			return $data[$post_id]['completion-custom-operation-count'];
		}
		private static function update_user_progress_completion_custom_operation_count($user_id, $post_id, $new_count) {
			$data = self::get_user_progress_secondary_data($user_id);
			if (!isset($data[$post_id])) {
				$data[$post_id] = array();
			}
			$data[$post_id]['completion-custom-operation-count'] = $new_count;
			self::set_user_progress_secondary_data($user_id, $data);
		}
		// </editor-fold>

		// <editor-fold defaultstate="collapsed" desc="batch retrieve user checked objectives from database">
		private static function batch_get_user_checked_objectives_database($post_list = false) {
			// When $post_list is false, fetch for all posts
			global $wpdb;
			if ($post_list === false) {
				// ignore the dummy entry
				$checked_objectives_raw = $wpdb->get_results("SELECT id, post_id, user_id, objective_id FROM $wpdb->pa_user_progress WHERE post_id > 0", ARRAY_A);
			} elseif (empty($post_list)) {
				$checked_objectives_raw = array();
			} else {
				$post_list_string = implode(',', $post_list);
				$checked_objectives_raw = $wpdb->get_results("SELECT id, post_id, user_id, objective_id FROM $wpdb->pa_user_progress WHERE post_id IN ($post_list_string)", ARRAY_A);
			}
			return $checked_objectives_raw;
		}
		private static function batch_get_post_checked_objectives($post_list = false) {
			// When $post_list is false, fetch for all posts
			$result = array();
			
			$checked_objectives_raw = self::batch_get_user_checked_objectives_database($post_list);
			if (is_array($checked_objectives_raw)) {
				foreach ($checked_objectives_raw as $row) {
					$post_id = $row['post_id'];
					$user_id = $row['user_id'];
					$objective_id = $row['objective_id'];
					if (!isset($result[$post_id])) {
						$result[$post_id] = array();
					}
					if (!isset($result[$post_id][$user_id])) {
						$result[$post_id][$user_id] = array();
					}
					$result[$post_id][$user_id][$objective_id] = $row['id'];
				}
			}
			return $result;
		}
		// </editor-fold>

		private static $cached_current_user_id = null;
		public static function get_user_id($user_id = false) {
			if (!is_numeric($user_id)) {
				if (self::$cached_current_user_id === null) {
					$user = wp_get_current_user();
					if (empty($user) || empty($user->data) || $user->ID <= 0) {
						self::$cached_current_user_id = 0;
					} else {
						self::$cached_current_user_id = $user->ID;
					}
				}
				return self::$cached_current_user_id;
			}
			return $user_id;
		}

		// <editor-fold defaultstate="collapsed" desc="non-objective user progress meta: stored in usermeta rather than its own table">
		private static function set_user_progress_meta($meta, $user_id) {
			if ($user_id > 0) {
				update_user_meta($user_id, self::USER_META_KEY, $meta);
				wp_cache_set(self::USER_META_KEY, $meta, $user_id, time() + ProgressAlly::CACHE_PERIOD);
			}
		}
		// retrieves the user progress meta with non-objective data. The objective checked status is stored in its own database table rather than the usermeta.
		public static function get_user_progress_meta($user_id = false) {
			$user_id = self::get_user_id($user_id);
			
			// Retrieve user meta from cookie if not logged in
			$meta = array();
			if ($user_id <= 0) {
				$meta = ProgressAllyBackendShared::read_cookie(self::COOKIE_NAME);
				if (!is_array($meta)) {
					$meta = array();
				}
			} else {
				$meta = self::get_user_progress_meta_database($user_id);
			}

			return $meta;
		}
		public static function get_user_progress_toggle_meta($user_id) {
			// only include the base meta, not the quiz info
			$user_meta = self::get_user_progress_meta_database($user_id);
			foreach ($user_meta as $post_id => $meta_data) {
				unset($user_meta[$post_id]['quiz']);
			}
			return $user_meta;
		}
		private static function get_user_progress_meta_database($user_id) {
			$meta = wp_cache_get(self::USER_META_KEY, $user_id);
			if (!is_array($meta)) {
				$meta = get_user_meta($user_id, self::USER_META_KEY, true);
				if (!is_array($meta)) {
					$meta = array();
				}

				wp_cache_set(self::USER_META_KEY, $meta, $user_id, time() + ProgressAlly::CACHE_PERIOD);
			}
			return $meta;
		}
		// </editor-fold>

		// <editor-fold defaultstate="collapsed" desc="progress update">
		// save user meta to data base and execute the add tag operation for the current post and the parents.
		private static function update_user_progress($post_id, $user_id, $objectives_to_update, $result_for_frontend) {
			$all_checked_objectives = self::get_user_checked_objectives($user_id);
			$current_post_checked_objectives = array();
			if (isset($all_checked_objectives[$post_id])) {
				$current_post_checked_objectives = $all_checked_objectives[$post_id];
			}
			$previous_checked_count = count($current_post_checked_objectives);

			$to_delete = array();
			$to_add = array();
			$timestamp = ProgressAllyBackendShared::get_sql_time();
			foreach ($objectives_to_update as $objective_id => $val) {
				if ($val) {
					if (!isset($current_post_checked_objectives[$objective_id])) {
						$to_add []= array($post_id, $user_id, $objective_id, $timestamp);
						$current_post_checked_objectives[$objective_id] = 0;
					}
				} else {
					if (isset($current_post_checked_objectives[$objective_id])) {
						$to_delete []= $current_post_checked_objectives[$objective_id];
						unset($current_post_checked_objectives[$objective_id]);
					}
				}
			}
			$all_checked_objectives[$post_id] = $current_post_checked_objectives;

			$new_checked_count = count($current_post_checked_objectives);
			$total_objective_count = ProgressAllyPostObjective::get_active_objective_count($post_id);
			$current_post_progress = $new_checked_count / $total_objective_count;

			$previous_completed_status = $previous_checked_count >= $total_objective_count;
			$new_completed_status = $new_checked_count >= $total_objective_count;

			// Add tag and show popup if completed
			$popup = $result_for_frontend['popup'];
			if ($current_post_progress >= 1) {
				$popup = self::progress_post_complete_actions($user_id, $post_id, $popup);
			}
			$changed_objectives = $result_for_frontend['keys'];
			$post_progress = $result_for_frontend['progress'];

			$post_progress[$post_id] = array('pct' => $current_post_progress, 'count' => $new_checked_count);

			// update dependent post objective when the completed status changes
			if ($previous_completed_status !== $new_completed_status) {
				$posts_dependent_on_this_post = ProgressAllyPostObjective::get_dependency($post_id);
				if (!empty($posts_dependent_on_this_post)) {
					$active_objective_count_list = ProgressAllyPostObjective::get_active_objective_count_list();
					$dependency_map = ProgressAllyPostObjective::get_database_table_map_for_reverse_dependency();

					$affected_posts = $posts_dependent_on_this_post;
					$affected_posts[$post_id] = 0;
					$update_result = array('checked' => $all_checked_objectives, 'delete' => $to_delete, 'add' => $to_add, 'change' => $changed_objectives);
					foreach ($posts_dependent_on_this_post as $parent_post_id => $parent_depth) {
						$update_result = self::update_user_progress_for_parent_post($user_id, $parent_post_id, $dependency_map, $active_objective_count_list,
							$affected_posts, $timestamp, $update_result);

						$parent_progress = $update_result['progress'];
						$post_progress[$parent_post_id] = array('pct' => $parent_progress, 'count' => $update_result['count']);
						if ($parent_progress >= 1) {
							$popup = self::progress_post_complete_actions($user_id, $parent_post_id, $popup);
						}
					}
					$to_delete = $update_result['delete'];
					$to_add = $update_result['add'];
					$changed_objectives = $update_result['change'];
				}
			}
			if ($user_id > 0) {
				self::update_user_progress_database($to_add, $to_delete);
			}

			// convert the checked status to format that is handled by JS. We need to return all the input states for extra redundancy
			$current_post_objectives = array();
			if (isset($changed_objectives[$post_id])) {
				$current_post_objectives = $changed_objectives[$post_id];
			}
			foreach ($current_post_checked_objectives as $objective_id => $dummy) {
				$current_post_objectives[$objective_id] = 'true';
			}
			// include not-checked objectives as well
			foreach ($objectives_to_update as $objective_id => $value) {
				$current_post_objectives[$objective_id] = $value ? 'true' : 'false';
			}
			$changed_objectives[$post_id] = $current_post_objectives;
			$result = array('keys' => $changed_objectives, 'progress' => $post_progress, 'popup' => $popup, 'added' => $to_add);
			return $result;
		}

		private static function progress_post_complete_actions($user_id, $post_id, $current_popup) {
			$post_meta = ProgressAllyTaskDefinition::get_post_progress_meta($post_id);
			if ($user_id > 0) {
				self::add_user_tags($user_id, $post_meta['complete-tag']);
			}
			if (!empty($post_meta['completion-custom-operation'])) {
				$run_count = self::get_user_progress_completion_custom_operation_count($user_id, $post_id);
				if ($run_count < 1 || 'yes' === $post_meta['checked-completion-custom-operation-always']) {
					if (class_exists('AccessAllyAPI') && method_exists('AccessAllyAPI', 'run_custom_operation')) {
						try {
							$contact_id = AccessAllyAPI::get_crm_contact_id($user_id);
							AccessAllyAPI::run_custom_operation($contact_id, $post_meta['completion-custom-operation'], 'ProgressAlly');
						} catch (Exception $ex) {
						}
						++$run_count;
						self::update_user_progress_completion_custom_operation_count($user_id, $post_id, $run_count);
					}
				}
			}
			if ('' !== $post_meta['completion-popup']) {
				if ('' === $current_popup) {
					$current_popup = $post_meta['completion-popup'];
				} else {
					$current_popup = min($current_popup, $post_meta['completion-popup']);	// show the lower ordinal popup
				}
			}
			return $current_popup;
		}
		const ADD_TAG_ERROR_KEY = 'progressally-add-tag-error';
		private static function add_user_tag($user_id, $tag_id) {
			if ($user_id > 0) {
				// add tag if defined
				if (!empty($tag_id)) {
					try {
						ProgressAllyMembershipUtilities::add_contact_tag($user_id, $tag_id);
					} catch (Exception $e) {
						$message = time() . ': ' . $e->getMessage() . '=>' . $e->getTraceAsString() . ';';
						if (!add_option(self::ADD_TAG_ERROR_KEY, $message, '', 'no')) {
							update_option(self::ADD_TAG_ERROR_KEY, $message);
						}
					}
				}
			}
		}
		private static function add_user_tags($user_id, $tag_ids) {
			if ($user_id > 0) {
				// add tag if defined
				if (!empty($tag_ids)) {
					try {
						ProgressAllyMembershipUtilities::add_contact_tags($user_id, $tag_ids);
					} catch (Exception $e) {
						$message = time() . ': ' . $e->getMessage() . '=>' . $e->getTraceAsString() . ';';
						if (!add_option(self::ADD_TAG_ERROR_KEY, $message, '', 'no')) {
							update_option(self::ADD_TAG_ERROR_KEY, $message);
						}
					}
				}
			}
		}
		const UPDATE_FIELD_ERROR_KEY = 'progressally-update-field-error';
		private static function update_user_field_values($user_id, $fields_to_update) {
			if ($user_id > 0) {
				// add tag if defined
				if (!empty($fields_to_update)) {
					try {
						ProgressAllyMembershipUtilities::update_contact_data($user_id, $fields_to_update);
					} catch (Exception $e) {
						$message = time() . ': ' . $e->getMessage() . '=>' . $e->getTraceAsString() . ';';
						update_user_meta($user_id, self::UPDATE_FIELD_ERROR_KEY, $message);
					}
				}
			}
		}
		private static function update_user_progress_database($to_add, $to_delete) {
			global $wpdb;
			if (!empty($to_delete)) {
				$to_delete_string = implode(',', $to_delete);
				$wpdb->query( "DELETE FROM $wpdb->pa_user_progress WHERE id IN ($to_delete_string)" );
			}
			if (!empty($to_add)) {
				$insert_query = "INSERT INTO {$wpdb->pa_user_progress} (post_id, user_id, objective_id, created) VALUES ";
				ProgressAllyUtilities::batch_insert_entries_database_simple($insert_query, $to_add);
			}
			self::clear_user_checked_cache();
		}

		public static function batch_delete_user_progress_database_objective($post_id, $objective_delete_list) {
			if (!empty($objective_delete_list)) {
				global $wpdb;
				$to_delete_string = implode(',', $objective_delete_list);
				$wpdb->query( "DELETE FROM $wpdb->pa_user_progress WHERE post_id = $post_id AND objective_id IN ($to_delete_string)" );
			}
			self::clear_user_checked_cache();
		}
		// </editor-fold>

		// <editor-fold defaultstate="collapsed" desc="user progress update due to post objective list change">
		// update [$post_id]'s objectives referencing other posts
		public static function batch_update_user_progress_post_local_objectives($post_id, $objective_update_list, $active_objective_count_list) {
			// Note: Only recalculate the local objectives ('post' type). No propagation.
			if (empty($objective_update_list)) {
				return;
			}
			$referenced_post = array();
			foreach($objective_update_list as $objective_id => $ref_post_id) {
				if ($ref_post_id > 0) {
					if (!isset($referenced_post[$ref_post_id])) {
						$referenced_post[$ref_post_id] = array();
					}
					$referenced_post[$ref_post_id] []= $objective_id;
				}
			}
			if (empty($referenced_post)) {
				return;
			}
			$referenced_post_ids = array_keys($referenced_post);

			global $wpdb;
			$post_list_string = implode(',', $referenced_post_ids);
			$checked_objectives_counts = $wpdb->get_results("SELECT COUNT(id) as count, post_id, user_id FROM $wpdb->pa_user_progress WHERE post_id IN ($post_list_string) GROUP BY post_id, user_id", ARRAY_A);

			$post_id_int = intval($post_id);
			$new_entry_values = array();
			$timestamp = ProgressAllyBackendShared::get_sql_time();
			foreach ($checked_objectives_counts as $row) {
				if (isset($active_objective_count_list[$row['post_id']]) &&
						intval($row['count']) >= $active_objective_count_list[$row['post_id']]) {
					foreach ($referenced_post[$row['post_id']] as $objective_id) {
						$new_entry_values []= array($post_id_int, $row['user_id'], $objective_id, $timestamp);
					}
					if (intval($row['count']) > $active_objective_count_list[$row['post_id']]) {
						error_log('Something is wrong: ' . $row['user_id']. ' ' . $row['post_id']);
					}
				}
			}
			if (empty($new_entry_values)) {
				return;
			}
			$insert_query = "INSERT INTO {$wpdb->pa_user_progress} (post_id, user_id, objective_id, created) VALUES ";
			ProgressAllyUtilities::batch_insert_entries_database_simple($insert_query, $new_entry_values);

			self::clear_user_checked_cache();
		}
		// update [$post_id]'s objectives referencing notes
		public static function batch_update_user_progress_note_local_objectives($post_id, $objective_update_list) {
			// Note: Only recalculate the local objectives ('note' type). No propagation.
			if (empty($objective_update_list)) {
				return;
			}
			
			$new_entry_values = array();
			$timestamp = ProgressAllyBackendShared::get_sql_time();
			$post_id_int = intval($post_id);
			foreach($objective_update_list as $objective_id => $ref_note_id) {
				if ($ref_note_id > 0) {
					$user_note_approved_list = ProgressAllyNote::get_all_user_note_approved_list($post_id_int, $ref_note_id);
					foreach ($user_note_approved_list as $user_id) {
						$new_entry_values []= array($post_id_int, intval($user_id), $objective_id, $timestamp);
					}
				}
			}
			
			if (empty($new_entry_values)) {
				return;
			}
			global $wpdb;
			$insert_query = "INSERT INTO {$wpdb->pa_user_progress} (post_id, user_id, objective_id, created) VALUES ";
			ProgressAllyUtilities::batch_insert_entries_database_simple($insert_query, $new_entry_values);

			self::clear_user_checked_cache();
		}

		private static function calc_user_progress_for_post($post_id, $user_checked_objectives, $active_objective_count_list) {
			// the post has no objective, so default to not completed
			if (!isset($active_objective_count_list[$post_id])) {
				return array('count' => 0, 'pct' => 0);
			}
			if ($active_objective_count_list[$post_id] <= 0) {
				return array('count' => 0, 'pct' => 0);
			}
			if (!isset($user_checked_objectives[$post_id])) {
				return array('count' => 0, 'pct' => 0);
			}
			if (!is_array($user_checked_objectives[$post_id])) {
				return array('count' => 0, 'pct' => 0);
			}
			$completed_count = count($user_checked_objectives[$post_id]);
			return array('count' => $completed_count, 'pct' => $completed_count / $active_objective_count_list[$post_id]);
		}
		private static function update_user_progress_for_parent_post($user_id, $parent_post_id, $dependency_map, $active_objective_count_list,
			$affected_posts, $timestamp, $state_parameters) {
			$user_checked_objectives = $state_parameters['checked'];
			$to_delete = $state_parameters['delete'];
			$to_add = $state_parameters['add'];
			if (isset($state_parameters['change'][$parent_post_id])) {
				$changed_objectives = $state_parameters['change'][$parent_post_id];
			} else {
				$changed_objectives = array();
			}
			$source_posts = $dependency_map[$parent_post_id];
			foreach ($source_posts as $source_post_id => $affected_objective_ids) {
				if (!isset($affected_posts[$source_post_id])) {
					// only update objectives linked to posts that are affected by the change.
					continue;
				}
				$source_post_user_progress = self::calc_user_progress_for_post($source_post_id, $user_checked_objectives, $active_objective_count_list);
				// if completed, add to database if not already done so
				if ($source_post_user_progress['pct'] >= 1) {
					if (!isset($user_checked_objectives[$parent_post_id])) {
						$user_checked_objectives[$parent_post_id] = array();
					}
					foreach ($affected_objective_ids as $objective_id) {
						if (!isset($user_checked_objectives[$parent_post_id][$objective_id])) {
							$to_add []= array($parent_post_id, $user_id, $objective_id, $timestamp);
							$user_checked_objectives[$parent_post_id][$objective_id] = 0;	// dummy value
							$changed_objectives[$objective_id] = 'true';
						}
					}
				} else {	// if not complete, then remove from database if marked as complete
					if (isset($user_checked_objectives[$parent_post_id])) {
						foreach ($affected_objective_ids as $objective_id) {
							if (isset($user_checked_objectives[$parent_post_id][$objective_id])) {
								$to_delete []= $user_checked_objectives[$parent_post_id][$objective_id];
								unset($user_checked_objectives[$parent_post_id][$objective_id]);
								$changed_objectives[$objective_id] = 'false';
							}
						}
						if (empty($user_checked_objectives[$parent_post_id])) {
							unset($user_checked_objectives[$parent_post_id]);
						}
					}
				}
			}
			$parent_post_user_progress = self::calc_user_progress_for_post($parent_post_id, $user_checked_objectives, $active_objective_count_list);
			if (!empty($changed_objectives)) {
				$state_parameters['change'][$parent_post_id] = $changed_objectives;
			}
			return array('checked' => $user_checked_objectives, 'delete' => $to_delete, 'add' => $to_add,
						'change' => $state_parameters['change'], 'progress' => $parent_post_user_progress['pct'], 'count' => $parent_post_user_progress['count']);
		}
		// update post objectives that depends on $post_id
		public static function batch_update_user_progress_post_parent_objectives($post_id, $dependency_list, $dependency_map, $active_objective_count_list) {
			$affected_posts = $dependency_list;
			if ($post_id > 0) {
				$affected_posts[$post_id] = 0;
			}
			$post_ids_to_fetch = array_keys($affected_posts);

			global $wpdb;
			$post_list_string = implode(',', $post_ids_to_fetch);
			$checked_objectives = $wpdb->get_results("SELECT id, post_id, user_id, objective_id FROM $wpdb->pa_user_progress WHERE post_id IN ($post_list_string)", ARRAY_A);

			$checked_objective_map = array();
			foreach ($checked_objectives as $row) {
				$entry_post_id = $row['post_id'];
				$entry_objective_id = $row['objective_id'];
				$entry_user_id = $row['user_id'];
				if (!isset($checked_objective_map[$entry_user_id])) {
					$checked_objective_map[$entry_user_id] = array();
				}
				if (!isset($checked_objective_map[$entry_user_id][$entry_post_id])) {
					$checked_objective_map[$entry_user_id][$entry_post_id] = array();
				}
				$checked_objective_map[$entry_user_id][$entry_post_id][$entry_objective_id] = $row['id'];
			}

			$timestamp = ProgressAllyBackendShared::get_sql_time();
			$update_result = array('delete' => array(), 'add' => array(), 'change' => array());
			foreach ($checked_objective_map as $user_id => $user_checked_objectives) {
				$update_result['checked'] = $user_checked_objectives;
				foreach ($dependency_list as $parent_post_id => $parent_depth) {
					$update_result = self::update_user_progress_for_parent_post($user_id, $parent_post_id, $dependency_map, $active_objective_count_list,
						$affected_posts, $timestamp, $update_result);
				}
			}
			$to_delete = $update_result['delete'];
			$to_add = $update_result['add'];
			self::update_user_progress_database($to_add, $to_delete);
		}
		// </editor-fold>
		
		public static function can_reset_quiz($post_id, $post_meta, $user_id, $user_meta) {
			$num_resetted = 0;
			if (isset($user_meta[$post_id]['quiz']) && isset($user_meta[$post_id]['quiz']['num-reset'])) {
				$num_resetted = $user_meta[$post_id]['quiz']['num-reset'];
			}
			$allow_reset = false;
			if (isset($user_meta[$post_id]['quiz']['input'])) {	// only allow quiz reset if already submitted
				if (ProgressAllyBackendShared::has_admin_privilege($user_id)) {
					$allow_reset = true;
				} elseif (isset($post_meta['quiz']['num-retake'])) {
					$allow_reset = $num_resetted < $post_meta['quiz']['num-retake'];
				}
			}
			return array($allow_reset, $num_resetted);
		}
		public static function reset_quiz($post_id) {
			$user_id = self::get_user_id();
			$user_meta = self::get_user_progress_meta($user_id);
			if (!isset($user_meta[$post_id])) {
				$user_meta[$post_id] = array();
			}

			$post_meta = ProgressAllyTaskDefinition::get_post_progress_meta($post_id);
			list($allow_reset, $num_resetted) = self::can_reset_quiz($post_id, $post_meta, $user_id, $user_meta);
			if ($allow_reset) {
				// update quiz stats counters
				if ($user_id > 0 && isset($user_meta[$post_id]['quiz'])) {
					$user_input = $user_meta[$post_id]['quiz'];
					ProgressAllyQuiz::update_quiz_stats_counter($post_id, $user_input, -1);
				}

				// update progress
				$objectives_to_update = array();
				if (!empty($post_meta['objectives'])) {
					foreach ($post_meta['objectives'] as $id => $objective) {
						if ($objective['seek-type'] === 'quiz') {
							$objectives_to_update[$id] = false;
						}
					}
				}
				$user_meta[$post_id]['quiz'] = array('num-reset' => ($num_resetted + 1));
				self::set_user_progress_meta($user_meta, $user_id);
				$progress = array();
				if (!empty($objectives_to_update)) {
					$starting_progress = array('keys' => array(), 'progress' => array(), 'popup' => '');
					$progress = self::update_user_progress($post_id, $user_id, $objectives_to_update, $starting_progress);

					// reset quiz cannot mark objectives as complete, so there is no objective entry being added
					unset($progress['added']);
				}
				$progress['reset'] = 'true';
				
				// send back additional data for cookie storage
				if ($user_id <= 0) {
					$progress['cookie'] = $user_meta[$post_id]['quiz'];
				}
			} else {
				$progress = array('reset' => 'false');
			}
			return $progress;
		}

		public static function update_quiz_completion($post_id, $post_meta, $quiz_result, $user_input) {
			$user_id = self::get_user_id();
			self::add_user_tag($user_id, $quiz_result['tag']);

			if (!empty($quiz_result['field-update'])) {
				self::update_user_field_values($user_id, $quiz_result['field-update']);
			}
			$user_meta = self::get_user_progress_meta($user_id);
			if (!isset($user_meta[$post_id])) {
				$user_meta[$post_id] = array();
			}
			$objectives_to_update = array();
			if (!isset($quiz_result['pass']) || $quiz_result['pass']) {
				if (!empty($post_meta['objectives'])) {
					foreach ($post_meta['objectives'] as $id => $objective) {
						if ($objective['seek-type'] === 'quiz') {
							$objectives_to_update[$id] = true;
						}
					}
				}
			}
			if (!isset($user_meta[$post_id]['quiz'])) {
				$user_meta[$post_id]['quiz'] = array();
			}
			$user_meta[$post_id]['quiz']['input'] = $user_input;
			$user_meta[$post_id]['quiz']['result'] = $quiz_result;
			list($allow_reset, $num_resetted) = self::can_reset_quiz($post_id, $post_meta, $user_id, $user_meta);

			$user_meta[$post_id]['quiz']['num-reset'] = $num_resetted;
			self::set_user_progress_meta($user_meta, $user_id);
			$progress = array();
			if (!empty($objectives_to_update)) {
				$starting_progress = array('keys' => array(), 'progress' => array(), 'popup' => '');
				$progress = self::update_user_progress($post_id, $user_id, $objectives_to_update, $starting_progress);

				$progress = ProgressAllyProcessEvents::trigger_objective_checked_event($user_id, $progress['added'], array(), $progress);
				unset($progress['added']);	// remove the added parameter to prevent private data from being sent to browser
			}

			$quiz_result_html = ProgressAllyQuizEvaluation::generate_quiz_result_html($post_meta, $quiz_result);
			$quiz_result['result-html'] = $quiz_result_html['html'];
			$quiz_result['result-popup'] = $quiz_result_html['popup'];
			$progress['quiz'] = $quiz_result;

			$progress['reset'] = $allow_reset ? 'true' : 'false';

			// update quiz stats counters
			if ($user_id > 0) {
				ProgressAllyQuiz::update_quiz_stats_counter($post_id, $user_meta[$post_id]['quiz'], 1);
			}
			
			// send back additional data for cookie storage
			if ($user_id <= 0) {
				$progress['cookie'] = $user_meta[$post_id]['quiz'];
			}
			return $progress;
		}
		
		public static function update_note_completion($post_id, $note_id, $user_id, $note_approve_status) {
			$is_objective_completed = false;
			if (Progressallynote::NOTE_APPROVE_STATUS_AUTO === $note_approve_status || Progressallynote::NOTE_APPROVE_STATUS_APPROVED === $note_approve_status) {
				$is_objective_completed = true;
			}
			$objectives = ProgressAllyPostObjective::get_objectives($post_id);
			$objectives_to_update = array();
			foreach ($objectives as $id => $objective_setting) {
				if ($objective_setting['seek-type'] === 'note' &&
						$objective_setting['note-id'] === $note_id) {
					$objectives_to_update[$id] = $is_objective_completed;
				}
			}
			
			$progress = array();
			if (!empty($objectives_to_update)) {
				$starting_progress = array('keys' => array(), 'progress' => array(), 'popup' => '');
				$progress = self::update_user_progress($post_id, $user_id, $objectives_to_update, $starting_progress);

				$progress = ProgressAllyProcessEvents::trigger_objective_checked_event($user_id, $progress['added'], array(), $progress);
				unset($progress['added']);	// remove the added parameter to prevent private data from being sent to browser
			}
			
			return $progress;
		}

		// p: post-id, k: objective key, b: is checkbox
		public static function update_progress_callback() {
			$nonce = $_POST['progressally_update_nonce'];
			
			if (!wp_verify_nonce( $nonce, 'progressally-update-progress-nonce')) {
				die();
			}
			if (!isset($_POST['p']) || !isset($_POST['v'])) {
				die();
			}
			$post_id = intval($_POST['p']);
			if (isset($_POST['k'])) {	// for rare case where the plugin is updated, but the visitor is still using the old JS
				$value_map = array($_POST['k'] => $_POST['v']);
			} else {
				$value_map = $_POST['v'];
			}

			$user_id = self::get_user_id();
			$post_meta = ProgressAllyTaskDefinition::get_post_progress_meta($post_id);
			$objectives_to_update = array();
			foreach ($value_map as $key => $val) {
				if (ProgressAllyTaskDefinition::can_ajax_update_this_objective($post_meta['objectives'], $key)) {
					$objectives_to_update[$key] = ($val === 'true');
				}
			}

			$starting_progress = array('keys' => array(), 'progress' => array(), 'popup' => '');
			$result = self::update_user_progress($post_id, $user_id, $objectives_to_update, $starting_progress);

			$result = ProgressAllyProcessEvents::trigger_objective_checked_event($user_id, $result['added'], array(), $result);
			unset($result['added']);	// remove the added parameter to prevent private data from being sent to browser

			echo json_encode($result);
			die();
		}

		// <editor-fold defaultstate="collapsed" desc="process toggle change">
		public static function update_toggle_callback() {
			if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'progressally-update-progress-nonce')) {
				echo json_encode(array('error' => 'Outdated page'));
				die();
			}
			if (!isset($_POST['p']) || !isset($_POST['v'])) {
				echo json_encode(array('error' => 'Invalid request'));
				die();
			}
			$post_id = intval($_POST['p']);
			$value_map = $_POST['v'];

			$user_id = self::get_user_id();
			$user_meta = self::get_user_progress_meta($user_id);
			$meta_for_post = array();
			if (isset($user_meta[$post_id])) {
				$meta_for_post = $user_meta[$post_id];
			}
			foreach ($value_map as $key => $value) {
				if ($value === 'true') {
					$meta_for_post[$key] = 'true';
				} else {
					unset($meta_for_post[$key]);
				}
			}
			$user_meta[$post_id] = $meta_for_post;
			self::set_user_progress_meta($user_meta, $user_id);
			unset($meta_for_post['quiz']);
			echo json_encode(array('meta' => $meta_for_post));
			die();
		}
		// </editor-fold>

		// <editor-fold defaultstate="collapsed" desc="process event objective action">
		public static function update_event_objective_action($post_id, $user_id, $objectives_to_check, $triggered_events, $result_for_frontend) {
			$all_objectives = ProgressAllyPostObjective::get_objectives($post_id);

			$verified_objectives_to_check = array();
			foreach ($objectives_to_check as $objective_id => $val) {
				if (ProgressAllyTaskDefinition::can_user_update_this_objective($all_objectives, $objective_id)) {
					$verified_objectives_to_check[$objective_id] = $val;
				}
			}
			if (empty($verified_objectives_to_check)) {
				return $result_for_frontend;
			}

			$result_for_frontend = self::update_user_progress($post_id, $user_id, $verified_objectives_to_check, $result_for_frontend);
			$result_for_frontend = ProgressAllyProcessEvents::trigger_objective_checked_event($user_id, $result_for_frontend['added'], $triggered_events, $result_for_frontend);
			return $result_for_frontend;
		}
		// </editor-fold>

		// <editor-fold defaultstate="collapsed" desc="Collect objective stats">
		public static function get_user_objective_completion_stats() {
			global $wpdb;
			$num_completed = $wpdb->get_results("SELECT COUNT(*) as count FROM $wpdb->pa_user_progress", OBJECT);
			$result = array('total' => 0, 'num-users' => 0);
			foreach ($num_completed as $entry) {
				$result['total'] = $entry->count;
			}
			$num_users = $wpdb->get_results("SELECT COUNT(id) as count, user_id FROM $wpdb->pa_user_progress GROUP BY user_id", OBJECT);
			if (is_array($num_users)) {
				$result['num-users'] = count($num_users);
			}
			return $result;
		}
		// </editor-fold>
	}
}