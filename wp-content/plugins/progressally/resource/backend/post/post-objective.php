<?php
if (!class_exists('ProgressAllyPostObjective')) {
	class ProgressAllyPostObjective {
		const OBJECTIVE_TYPE_TEXT = 1;
		const OBJECTIVE_TYPE_VIMEO = 2;
		const OBJECTIVE_TYPE_YOUTUBE = 3;
		const OBJECTIVE_TYPE_WISTIA = 4;
		const OBJECTIVE_TYPE_QUIZ = 5;
		const OBJECTIVE_TYPE_POST = 6;
		const OBJECTIVE_TYPE_NOTE = 7;
		
		private static $obejective_seek_type_mapping = array(self::OBJECTIVE_TYPE_TEXT => 'none', 
															self::OBJECTIVE_TYPE_VIMEO => 'vimeo', 
															self::OBJECTIVE_TYPE_YOUTUBE => 'youtube',
															self::OBJECTIVE_TYPE_WISTIA => 'wistia',
															self::OBJECTIVE_TYPE_QUIZ => 'quiz',
															self::OBJECTIVE_TYPE_POST => 'post',
															self::OBJECTIVE_TYPE_NOTE => 'note');
		private static $seek_obejective_type_mapping = array('none' => self::OBJECTIVE_TYPE_TEXT, 
			'vimeo' => self::OBJECTIVE_TYPE_VIMEO, 
			'youtube' => self::OBJECTIVE_TYPE_YOUTUBE, 
			'wistia' => self::OBJECTIVE_TYPE_WISTIA, 
			'quiz' => self::OBJECTIVE_TYPE_QUIZ, 
			'post' => self::OBJECTIVE_TYPE_POST,
			'note' => self::OBJECTIVE_TYPE_NOTE);

		public static function add_actions() {
			add_action('delete_post', array(__CLASS__, 'delete_post_callback'));
		}
		
		public static function delete_post_callback($post_id) {
			// Update user progress for the parent posts
			$posts_dependent_on_this_post = self::get_dependency($post_id);

			global $wpdb;
			// remove entries from objective table
			$wpdb->query("DELETE FROM $wpdb->pa_post_objective WHERE post_id = $post_id");

			// remove all user checked progress for this post
			$wpdb->query("DELETE FROM $wpdb->pa_user_progress WHERE post_id = $post_id");

			if (!empty($posts_dependent_on_this_post)) {
				$affected_objectives = $wpdb->get_results("SELECT id, post_id, objective_id FROM $wpdb->pa_post_objective WHERE mapped_post_id = $post_id", ARRAY_A);

				// reset any objectives pointing to this post to empty
				$wpdb->query("UPDATE $wpdb->pa_post_objective SET mapped_post_id=-1 WHERE mapped_post_id = $post_id");
				self::clear_local_cache();

				foreach ($affected_objectives as $row) {
					$affected_post_id = $row['post_id'];
					$affected_objective_id = $row['objective_id'];
					$wpdb->query("DELETE FROM $wpdb->pa_user_progress WHERE post_id = $affected_post_id AND objective_id = $affected_objective_id");
				}
				$active_objective_count_list = self::get_active_objective_count_list();
				$dependency_map = self::get_database_table_map_for_reverse_dependency();
				ProgressAllyUserProgress::batch_update_user_progress_post_parent_objectives(false, $posts_dependent_on_this_post, $dependency_map, $active_objective_count_list);
			}
			ProgressAllyUserProgress::clear_user_checked_cache();
		}

		// <editor-fold defaultstate="collapsed" desc="Database operations">
		public static function initialize_database_names() {
			global $wpdb;

			$wpdb->pa_post_objective = $wpdb->prefix . 'pa_post_objective';
		}
		
		public static function create_database_table_query() {
			global $charset_collate, $wpdb;
			return "CREATE TABLE $wpdb->pa_post_objective (
			  id bigint(20) unsigned NOT NULL auto_increment,
			  post_id bigint(20) unsigned NOT NULL default '0',
			  objective_id bigint(20) unsigned NOT NULL default '0',
			  mapped_post_id bigint(20) NOT NULL default '0',
			  objective_type bigint(20) unsigned NOT NULL default '0',
			  meta longtext NOT NULL default '',
			  PRIMARY KEY  (id),
			  KEY post_id (post_id),
			  KEY objective_id (objective_id),
			  KEY mapped_post_id (mapped_post_id),
			  KEY objective_type (objective_type)
			) $charset_collate;";
		}
		// </editor-fold>
		
		// <editor-fold defaultstate="collapsed" desc="Database conversion">
		public static function convert_database_table() {
			// -1: no objective meta, 0: no objective, 1: has local objective
			$post_objective_status = array();

			$post_parentage_map = self::get_database_table_map_for_parentage();

			$all_legacy_post_meta = ProgressAllyTaskDefinition::get_post_meta_table();
			if (empty($all_legacy_post_meta)) {
				return;
			}

			foreach ($all_legacy_post_meta as $post_id => $post_meta) {
				$post_objective_status = self::convert_post_meta_objectives($post_id, $all_legacy_post_meta, $post_parentage_map, $post_objective_status);
			}
		}
		
		public static function calculate_update_list_for_user_progress_conversion() {
			// Create the update list to calculate user progress conversion. The list is ordered by dependency
			self::clear_local_cache();

			$update_list = array();
			$dependent_post_map = self::get_database_table_map_for_reverse_dependency();
			while (!empty($dependent_post_map)) {
				$database_table_new = array();
				foreach ($dependent_post_map as $post_id => $dependent_posts) {
					$still_dependent = array();
					// check of the dependent posts have already been added to the update list
					foreach($dependent_posts as $dependent_post_id => $dummy) {
						if (!isset($update_list[$dependent_post_id])) {
							$still_dependent[$dependent_post_id] = $dummy;
						}
					}
					if (empty($still_dependent)) {
						$update_list[$post_id] = true;
					} else {
						$database_table_new[$post_id] = $still_dependent;
					}
				}
				$dependent_post_map = $database_table_new;
			}

			$active_objective_list = self::get_active_objective_list();
			$update_structure = array('update_order' => $update_list, 'active_objective_list' => $active_objective_list);
			return $update_structure;
		}
		
		public static $default_objective_setting = array('description' => '', 'seek-type' => 'none',
			'seek-id' => '0', 'seek-time' => '0', 'seek-time-minute' => '0', 'seek-time-second' => '0',
			'checked-complete-video' => 'no', 'complete-time' => '-1', 'complete-time-minute' => '0', 'complete-time-second' => '0',
			'ref-post-id' => '0', 'note-id' => '0');
		private static function convert_post_meta_objectives($post_id, $all_legacy_post_meta, $post_parentage_map, $post_objective_status) {
			// Return value: $post_objective_status[$post_id] is assigned. -1 if not assigned, 0 if no local objective, 1 when has local objective or has children with local objective(s)

			if (isset($post_objective_status[$post_id])) {
				return $post_objective_status;
			}

			$objective_meta = array();

			$post_objective_status[$post_id] = 0;
			if (!isset($all_legacy_post_meta[$post_id])) {	// the post doesn't have any objective configured
				$post_objective_status[$post_id] = -1;
				return $post_objective_status;
			}
			$post_meta = $all_legacy_post_meta[$post_id];

			if (isset($post_meta['checked-use-local-objectives']) && $post_meta['checked-use-local-objectives'] === 'no') {
				// Get child pages to use as objectives
				if (isset($post_parentage_map[$post_id])) {
					$objective_id = 0;
					foreach ($post_parentage_map[$post_id] as $child_page) {
						$child_page_id = $child_page['id'];

						// recursively convert child page objective settings
						if (!isset($post_objective_status[$child_page_id])) {
							$post_objective_status = self::convert_post_meta_objectives($child_page_id, $all_legacy_post_meta, $post_parentage_map, $post_objective_status);
						}
						if ($post_objective_status[$child_page_id] > 0) {
							++$objective_id;
							$objective = self::$default_objective_setting;
							$objective['seek-type'] = 'post';
							$objective['ref-post-id'] = $child_page['id'];
							$objective['description'] = $child_page['title'];

							$objective_meta[] = self::format_objective_setting($post_id, $objective_id, $objective);
						}
					}
				}
			} elseif (isset($post_meta['objectives'])) {
				// Convert local objectives
				foreach ($post_meta['objectives'] as $objective_id => $objective) {
					// Merge defaults
					if (intval($objective_id) > 0) {
						// Preserve backward compatibilty from legacy
						if (isset($objective['seek-type']) && $objective['seek-type'] !== 'none') {
							if (!isset($objective['seek-time'])) {	// saving from post
								$objective['seek-time-minute'] = intval($objective['seek-time-minute']);
								$objective['seek-time-second'] = intval($objective['seek-time-second']);
								$objective['seek-time'] = $objective['seek-time-minute'] * 60 + $objective['seek-time-second'];
							} elseif (!isset($objective['seek-minute'])) { // legacy conversion
								$time_in_second = intval($objective['seek-time']);
								$objective['seek-time-minute'] = floor($time_in_second / 60);
								$objective['seek-time-second'] = intval($time_in_second % 60);
							}
						}
						
						$objective = wp_parse_args($objective, self::$default_objective_setting);
						$objective_meta []= self::format_objective_setting($post_id, $objective_id, $objective);
					}
				}
			}

			if (!empty($objective_meta)) {
				$post_objective_status[$post_id] = 1;
				self::add_objectives($objective_meta);
			}

			return $post_objective_status;
		}

		private static function get_database_table_map_for_parentage() {
			// build a map with post_id as the key and array of child post_id as value.
			$post_parentage = array();
			
			global $wpdb;
			$post_parentage_raw = $wpdb->get_results("SELECT ID, post_title, post_parent FROM $wpdb->posts WHERE post_parent > 0 AND post_status IN ('publish','future','draft','pending')", ARRAY_A);
			if (is_array($post_parentage_raw)) {
				foreach ($post_parentage_raw as $row) {
					if (!isset($post_parentage[$row['post_parent']])) {
						$post_parentage[$row['post_parent']] = array();
					}
					$post_parentage[$row['post_parent']] []= array('id' => $row['ID'], 'title' => $row['post_title']);
				}
			}
			return $post_parentage;
		}
		// </editor-fold>
		
		// <editor-fold defaultstate="collapsed" desc="Update objectives">
		public static function update_objectives($post_id, $objective_meta) {
			if (!is_int($post_id)) {
				$post_id = intval($post_id);
			}
			if ($post_id <= 0) {
				return 'Invalid post ID.';
			}

			$current_active_objective_raw = self::get_objectives_core($post_id);

			// Sanitize and format the input
			$sanitized_input = self::sanitize_objective_input($post_id, $objective_meta);
			$error = $sanitized_input['error'];
			$objective_meta_formatted = $sanitized_input['objective_meta'];

			// update the post objective meta
			self::update_objectives_core($post_id, $objective_meta_formatted);

			self::clear_local_cache();

			// Update user progress for this post: 
			// remove (1) deleted objectives, (2) objectives that changed type, 
			//		(3) objectives that are pointing to a different post/page and (4) objectives that are pointing to a different note
			$removed_objective_list = self::determine_removed_objectives($objective_meta_formatted, $current_active_objective_raw);
			ProgressAllyUserProgress::batch_delete_user_progress_database_objective($post_id, $removed_objective_list);

			// Update user progress for this post: newly created / changed post objective types or referring targets
			$update_objective_list_post = self::determine_update_objectives_post($objective_meta_formatted, $current_active_objective_raw);
			if (!empty($update_objective_list_post)) {
				$active_objective_count_list = self::get_active_objective_count_list();
				ProgressAllyUserProgress::batch_update_user_progress_post_local_objectives($post_id, $update_objective_list_post, $active_objective_count_list);
			}
			$update_objective_list_note = self::determine_update_objectives_note($objective_meta_formatted, $current_active_objective_raw);
			if (!empty($update_objective_list_note)) {
				ProgressAllyUserProgress::batch_update_user_progress_note_local_objectives($post_id, $update_objective_list_note);
			}

			// Update user progress for the parent posts
			$posts_dependent_on_this_post = self::get_dependency($post_id);
			if (!empty($posts_dependent_on_this_post)) {
				$active_objective_count_list = self::get_active_objective_count_list();
				$dependency_map = self::get_database_table_map_for_reverse_dependency();
				ProgressAllyUserProgress::batch_update_user_progress_post_parent_objectives($post_id, $posts_dependent_on_this_post, $dependency_map, $active_objective_count_list);
			}

			return empty($error) ? true : $error;
		}

		private static function update_objectives_core($post_id, $objective_meta) {
			global $wpdb;
			$wpdb->delete($wpdb->pa_post_objective, array('post_id' => $post_id));
			
			self::add_objectives($objective_meta);
		}
		
		private static function add_objectives($objective_meta) {
			global $wpdb;
			$query = "INSERT INTO {$wpdb->pa_post_objective} (post_id, objective_id, mapped_post_id, objective_type, meta) VALUES ";
			$place_holder = "('%d', '%d', '%d', '%d', '%s')";
			$values = array();
			foreach ($objective_meta as $objective) {
				array_push($values, intval($objective['post_id']), intval($objective['objective_id']), intval($objective['mapped_post_id']), intval($objective['objective_type']), $objective['meta']);
			}
			ProgressAllyUtilities::batch_insert_entries_database($query, $place_holder, $values, 5);
		}
		
		private static function determine_removed_objectives($new_objectives, $current_active_objectives) {
			// Note: Keys in $new_objectives should be the objective id, and $new_objectives may contain in valid objectives.
			$result = array();
			foreach ($current_active_objectives as $objective) {
				$objective_id = $objective['objective_id'];
				if (isset($new_objectives[$objective_id])) {
					if (intval($objective['objective_type']) === intval($new_objectives[$objective_id]['objective_type'])) {
						if (intval($objective['objective_type']) === self::OBJECTIVE_TYPE_POST) {
							$old_dependent_post_id = intval($objective['mapped_post_id']);
							$new_dependent_post_id = intval($new_objectives[$objective_id]['mapped_post_id']);
							if ($old_dependent_post_id === $new_dependent_post_id) {
								continue;
							}
							// still referring to invalid post doesn't need to be updated.
							if ($old_dependent_post_id <= 0 && $new_dependent_post_id <= 0) {
								continue;
							}
						} elseif (intval($objective['objective_type']) === self::OBJECTIVE_TYPE_NOTE) {
							$old_meta = unserialize($objective['meta']);
							$new_meta = unserialize($new_objectives[$objective_id]['meta']);
							$old_note_id = intval($old_meta['note-id']);
							$new_note_id = intval($new_meta['note-id']);
							if ($old_note_id === $new_note_id) {
								continue;
							}
						} else {
							continue;
						}
					}
				}
				$result []= $objective_id;
			}
			return $result;
		}
		
		private static function determine_update_objectives_post($new_objectives, $current_active_objectives) {
			// Return: key is objective id, value is mapped_post_id
			// Note: $new_objectives may contain in valid objectives
			$result = array();
			$current_ref_post = array();
			foreach ($current_active_objectives as $objective) {
				if (intval($objective['objective_type']) === self::OBJECTIVE_TYPE_POST) {
					$current_ref_post[$objective['objective_id']] = $objective['mapped_post_id'];
				}
			}
			foreach ($new_objectives as $objective) {
				$objective_id = $objective['objective_id'];
				$new_dependent_post_id = intval($objective['mapped_post_id']);
				if ($new_dependent_post_id > 0) {
					// for valid post type objective
					if (!isset($current_ref_post[$objective_id]) || 
							intval($current_ref_post[$objective_id]) !== $new_dependent_post_id) {
						$result[$objective_id] = $new_dependent_post_id;
					}
				}
			}
			return $result;
		}
		private static function determine_update_objectives_note($new_objectives, $current_active_objectives) {
			// Return: key is objective id, value is note_id
			// Note: $new_objectives may contain in valid objectives
			$result = array();
			$current_ref_note = array();
			foreach ($current_active_objectives as $objective) {
				if (intval($objective['objective_type']) === self::OBJECTIVE_TYPE_NOTE) {
					$current_meta = unserialize($objective['meta']);
					$current_ref_note[$objective['objective_id']] = $current_meta['note-id'];
				}
			}
			foreach ($new_objectives as $objective) {
				$objective_id = $objective['objective_id'];
				if (intval($objective['objective_type']) === self::OBJECTIVE_TYPE_NOTE) {
					$new_meta = unserialize($objective['meta']);
					$new_note_id = intval($new_meta['note-id']);
					if ($new_note_id > 0) {
						// for valid note type objective
						if (!isset($current_ref_note[$objective_id]) || 
								intval($current_ref_note[$objective_id]) !== $new_note_id) {
							$result[$objective_id] = $new_note_id;
						}
					}
				}
			}
			return $result;
		}
		
		private static function clear_local_cache() {
			self::$cached_active_objective_count_list = null;
			self::$cached_database_table_map_for_dependency = null;
			self::$cached_database_table_map_for_reverse_dependency = null;
			self::$cached_all_objectives_for_dependency = null;
			self::$cached_dependency = array();
			self::$cached_dependency_depth = array();
		}
		// </editor-fold>
		
		// <editor-fold defaultstate="collapsed" desc="Get objectives">
		private static $cached_active_objective_count_list = null;
		public static function get_active_objective_count($post_id) {
			self::get_active_objective_count_list();
			
			if (isset(self::$cached_active_objective_count_list[$post_id])) {
				return self::$cached_active_objective_count_list[$post_id];
			}
			return 0;
		}

		// returns the total number of objective indexed by post_id
		public static function get_active_objective_count_list() {
			if (self::$cached_active_objective_count_list === null) {
				$result = array();

				$database_table_raw = self::get_all_objective_database_entries_for_dependency_calc();
				foreach ($database_table_raw as $row) {
					if (!isset($result[$row['post_id']])) {
						$result[$row['post_id']] = 1;
					} else {
						++$result[$row['post_id']];
					}
				}
				self::$cached_active_objective_count_list = $result;
			}
			return self::$cached_active_objective_count_list;
		}

		// only used in user-progress migration
		private static function get_active_objective_list() {
			$active_objective_list = array();
			$objectives_raw = self::get_all_objective_database_entries_for_dependency_calc();
			if (is_array($objectives_raw)) {
				foreach ($objectives_raw as $row) {
					$post_id = $row['post_id'];
					$objective_id = $row['objective_id'];
					if (!isset($active_objective_list[$post_id])) {
						$active_objective_list[$post_id] = array();
					}
					$active_objective_list[$post_id][$objective_id] = $row['mapped_post_id'];
				}
			}
			return $active_objective_list;
		}
		private static $cached_post_objectives = array();
		public static function get_objectives($post_id, $objective_type_filter = false) {
			if (false === $objective_type_filter) {	// only cache if the filter is not set
				if (isset(self::$cached_post_objectives[$post_id])) {
					return self::$cached_post_objectives[$post_id];
				}
			}
			$objectives = array();
			
			$objectives_raw = self::get_objectives_core($post_id, $objective_type_filter);
			foreach ($objectives_raw as $objective_raw) {
				$objective = unserialize($objective_raw['meta']);
				$objective = wp_parse_args($objective, self::$default_objective_setting);
				$objective['ref-post-id'] = $objective_raw['mapped_post_id'];
				$objective['seek-type'] = self::$obejective_seek_type_mapping[$objective_raw['objective_type']];
				$objective['seek-time'] = intval($objective['seek-time-minute']) * 60 + intval($objective['seek-time-second']);

				if (isset($objective['checked-complete-video']) && 'yes' === $objective['checked-complete-video']) {
					$objective['complete-time'] = intval($objective['complete-time-minute']) * 60 + intval($objective['complete-time-second']);
				} else {
					$objective['complete-time'] = -1;
				}
				$objectives[$objective_raw['objective_id']] = $objective;
			}

			if (false === $objective_type_filter) {	// only cache if the filter is not set
				self::$cached_post_objectives[$post_id] = $objectives;
			}
			return $objectives;
		}
		
		private static function get_objectives_core($post_id, $objective_type_filter = false) {
			if ($post_id > 0) {
				global $wpdb;
				$filter = "post_id = $post_id";
				if (!empty($objective_type_filter)) {
					$filter .= ' AND objective_type in (' . implode(',', $objective_type_filter) . ')';
				}
				$objectives_raw = $wpdb->get_results("SELECT * FROM $wpdb->pa_post_objective WHERE $filter ORDER BY id", ARRAY_A);
				if (is_array($objectives_raw)) {
					return $objectives_raw;
				}
			}
 			return array();
		}
		// </editor-fold>

		private static function sanitize_objective_input($post_id, $objective_meta) {
			$error = '';
			$objective_meta_formatted = array();
			
			$dependency_list = self::get_dependency($post_id);
			foreach($objective_meta as $objective_id => $objective) {
				if ($objective['seek-type'] === 'post') {
					$ref_post_id = intval($objective['ref-post-id']);
					if ($ref_post_id === 0 ) {
						// prevent 0 as ref-post-id
						$objective['ref-post-id'] = -1;
					} else {
						// prevent infinite loop
						if ($post_id === $ref_post_id) {
							$objective['ref-post-id'] = -1;
							$error .= 'Invalid configuration for Objective ' . $objective_id . ' because it cannot reference itself.';
						} elseif (isset($dependency_list[$ref_post_id])) {
							$objective['ref-post-id'] = -1;
							$error .= 'Invalid configuration for Objective ' . $objective_id . ' because the post/page is dependent on the progress of the current page.';
						}
					}
				} else {
					$objective['ref-post-id'] = 0;
				}
				
				$objective_meta_formatted[$objective_id] = self::format_objective_setting($post_id, $objective_id, $objective);
			}
			
			return array('error' => $error, 'objective_meta' => $objective_meta_formatted);
		}

		private static function format_objective_setting($post_id, $objective_id, $objective) {
			$objective_type = self::$seek_obejective_type_mapping[$objective['seek-type']];

			return array('post_id' => $post_id,
						'objective_id' => $objective_id,
						'mapped_post_id' => intval($objective['ref-post-id']),
						'objective_type' => $objective_type,
						'meta' => maybe_serialize($objective));
		}

		// <editor-fold defaultstate="collapsed" desc="collect video complete data for frontend script">
		public static function get_video_frontend_data($post_id) {
			$result = array();
			$objectives = self::get_objectives($post_id, array(self::OBJECTIVE_TYPE_YOUTUBE, self::OBJECTIVE_TYPE_VIMEO, self::OBJECTIVE_TYPE_WISTIA));
			foreach ($objectives as $id => $objective_definition) {
				if ($objective_definition['complete-time'] > 0) {
					$key = $objective_definition['seek-type'] . '>' . $objective_definition['seek-id'];
					if (!isset($result[$key])) {
						$result[$key] = array();
					}
					$result[$key] []= array(
						'obj' => $id,
						'time' => $objective_definition['complete-time'],
					);
				}
			}
			return $result;
		}
		// </editor-fold>
		
		// <editor-fold defaultstate="collapsed" desc="Dependency related">
		private static $cached_dependency = array();
		public static function get_dependency($post_id) {
		// Return the flatten dependency tree where the items directly depend on post_id are in the front.
		// Structure: array(id_1 => depth from root, id_2 => depth from root)
			if (!is_int($post_id)) {
				$post_id = intval($post_id);
			}

			if ($post_id <= 0) {
				return array();
			}

			if (!isset(self::$cached_dependency[$post_id])) {
				$database_table_map = self::get_database_table_map_for_dependency();
				$dependency_with_depth = self::get_dependency_core($post_id, $database_table_map);
				asort($dependency_with_depth);
				self::$cached_dependency[$post_id] = $dependency_with_depth;
			}
			return self::$cached_dependency[$post_id];
		}

		private static $cached_dependency_depth = array();
		private static function get_dependency_core($post_id, $database_table_map){
			// Recursive function to get the dependency. The value is the depth of the dependency
			if (isset(self::$cached_dependency_depth[$post_id])) {
				return self::$cached_dependency_depth[$post_id];
			}
			$dependency_result = array();
			if (isset($database_table_map[$post_id])) {
				foreach ($database_table_map[$post_id] as $dependency_post_id => $objective_id) {
					// we don't need to walk a branch that has already been processed
					if (isset($dependency_result[$dependency_post_id])) {
						continue;
					}
					$dependency_result[$dependency_post_id] = 1;

					$dependency_array = self::get_dependency_core($dependency_post_id, $database_table_map);
					foreach ($dependency_array as $parent_post_id => $depth) {
						if (isset($dependency_result[$parent_post_id])) {
							$dependency_result[$parent_post_id] = max($dependency_result[$parent_post_id], $depth + 1);
						} else {
							$dependency_result[$parent_post_id] = $depth + 1;
						}
					}
				}
			}
			self::$cached_dependency_depth[$post_id] = $dependency_result;
			return self::$cached_dependency_depth[$post_id];
		}
		private static $cached_all_objectives_for_dependency = null;
		private static function get_all_objective_database_entries_for_dependency_calc() {
			if (null === self::$cached_all_objectives_for_dependency) {
				global $wpdb;
				// ignore dummy entry
				self::$cached_all_objectives_for_dependency = $wpdb->get_results("SELECT post_id,mapped_post_id,objective_id,objective_type FROM $wpdb->pa_post_objective WHERE post_id > 0", ARRAY_A);
			}
			return self::$cached_all_objectives_for_dependency;
		}

		private static $cached_database_table_map_for_dependency = null;
		private static function get_database_table_map_for_dependency() {
			// build a map with mapped_post_id as the key and array of post_id as value
			// Structure of value: array(id_1 => objective_1, id_2 => objective_2)
			if (self::$cached_database_table_map_for_dependency === null) {
				$database_table_map = array();

				$database_table_raw = self::get_all_objective_database_entries_for_dependency_calc();
				if (is_array($database_table_raw)) {
					foreach ($database_table_raw as $row) {
						$mapped_post_id = $row['mapped_post_id'];
						if (intval($row['objective_type']) === self::OBJECTIVE_TYPE_POST && $mapped_post_id > 0) {
							$post_id = $row['post_id'];
							$objective_id = $row['objective_id'];

							if (!isset($database_table_map[$mapped_post_id])) {
								$database_table_map[$mapped_post_id] = array();
							}
							// use an array to prevent multiple objective referring to the same post from overwriting each other
							if (!isset($database_table_map[$mapped_post_id][$post_id])) {
								$database_table_map[$mapped_post_id][$post_id] = array();
							}
							$database_table_map[$mapped_post_id][$post_id] []= $objective_id;
						}
					}
				}
				self::$cached_database_table_map_for_dependency = $database_table_map;
			}
			return self::$cached_database_table_map_for_dependency;
		}

		// return value: every post with objectives have an array entry. The array entries are keyed by the posts that it depends on
		private static $cached_database_table_map_for_reverse_dependency = null;
		public static function get_database_table_map_for_reverse_dependency() {
			if (null === self::$cached_database_table_map_for_reverse_dependency) {
				$database_table_map = array();

				$database_table_raw = self::get_all_objective_database_entries_for_dependency_calc();
				if (is_array($database_table_raw)) {
					foreach ($database_table_raw as $row) {
						$mapped_post_id = $row['mapped_post_id'];
						$post_id = $row['post_id'];
						$objective_id = $row['objective_id'];

						if (!isset($database_table_map[$post_id])) {
							$database_table_map[$post_id] = array();
						}

						if ($mapped_post_id > 0) {
							if (!isset($database_table_map[$post_id][$mapped_post_id])) {
								$database_table_map[$post_id][$mapped_post_id] = array($objective_id);
							} else {
								$database_table_map[$post_id][$mapped_post_id] []= $objective_id;
							}
						}
					}
				}
				self::$cached_database_table_map_for_reverse_dependency = $database_table_map;
			}
			return self::$cached_database_table_map_for_reverse_dependency;
		}
		// </editor-fold>

		// <editor-fold defaultstate="collapsed" desc="Collect objective stats">
		public static function get_objective_stats() {
			global $wpdb;
			$counts = $wpdb->get_results("SELECT COUNT(id) as count,objective_type FROM $wpdb->pa_post_objective WHERE post_id > 0 GROUP BY objective_type", OBJECT);
			$result = array();
			foreach ($counts as $entry) {
				$result[$entry->objective_type] = $entry->count;
			}
			return $result;
		}
		// </editor-fold>
	}
}