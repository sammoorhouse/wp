<?php
class ProgressAllyFileUploadRetryException extends Exception {
	private $file_path;
	public function __construct($message, $path) {
		parent::__construct($message, 0, null);
		$this->file_path = $path;
	}
	public function get_file_path() {
		return $this->file_path;
	}
}

class ProgressAllyBackendShared {
	const SETTING_KEY_DATABASE_VERSION = '_progressally_database_version';
	public static function is_initial_install() {
		$version = get_transient(self::SETTING_KEY_DATABASE_VERSION);

		if (!$version) {
			$version = get_option(self::SETTING_KEY_DATABASE_VERSION, false);
		}
		return !$version;
	}
	public static function is_database_up_to_date() {
		$version = get_transient(self::SETTING_KEY_DATABASE_VERSION);

		if (!$version) {
			$version = get_option(self::SETTING_KEY_DATABASE_VERSION, false);
		}
		return $version === ProgressAlly::VERSION;
	}
	public static function update_database_version() {
		set_transient(self::SETTING_KEY_DATABASE_VERSION, ProgressAlly::VERSION, ProgressAlly::CACHE_PERIOD);
		update_option(self::SETTING_KEY_DATABASE_VERSION, ProgressAlly::VERSION);
	}
	private static function evaluate_toggle_variable($match_value, $variable_path, $settings, $true_replace, $false_replace) {
		$variable_path = str_replace('[', ',', $variable_path);
		$variable_path = str_replace(']', '', $variable_path);
		$args = explode(',', $variable_path);
		foreach($args as $arg) {
			if ($arg) {
				if (isset($settings[$arg])) {
					$settings = $settings[$arg];
				} else {
					return $false_replace;
				}
			}
		}
		$match_parts = explode(',', $match_value);
		if (in_array($settings, $match_parts)) {
			return $true_replace;
		}
		return $false_replace;
	}

	private static function replace_specific_toggle($code, $settings, $toggle_name, $true_replace, $false_replace) {
		$matches = array();
		$matches_not = array();
		preg_match_all('/(' . $toggle_name . '="(.*?)")(.*?)pa-dep-value="(.*?)"/', $code, $matches);
		preg_match_all('/(' . $toggle_name . '="(.*?)")(.*?)pa-dep-value-not="(.*?)"/', $code, $matches_not);
		
		if (!empty($matches)) {
			$code = self::replace_toggle_values($code, $settings, $toggle_name, $matches, $true_replace, $false_replace);
		}
		if (!empty($matches_not)) {
			$code = self::replace_toggle_values($code, $settings, $toggle_name, $matches_not, $false_replace, $true_replace);
		}
		return $code;
	}
	
	private static function replace_toggle_values($code, $settings , $toggle_name, $toggle_matches, $true_replace, $false_replace) {
		$length = count($toggle_matches[1]);
		for ($i=0;$i<$length;++$i){
			$new_value = self::evaluate_toggle_variable($toggle_matches[4][$i], $toggle_matches[2][$i], $settings, $true_replace, $false_replace);
			$to_replace = $toggle_matches[0][$i];
			$to_replace = str_replace($toggle_matches[1][$i], $toggle_name . $new_value, $to_replace);
			
			$code = str_replace($toggle_matches[0][$i], $to_replace , $code);
		}
		return $code;
	}

	public static function replace_all_toggle($code, $settings) {
		$code = self::replace_specific_toggle($code, $settings, 'hide-toggle', '', ' style="display:none;"');
		$code = self::replace_specific_toggle($code, $settings, 'disable-toggle', '', ' disabled="disabled"');
		$code = self::replace_specific_toggle($code, $settings, 'readonly-toggle', '', ' readonly="readonly"');

		return $code;
	}
	public static function replace_real_values($template, $settings, $root_key, $is_default = false) {
		foreach($settings as $key => $value) {
			if (!is_array($value)) {
				$is_select = false;
				$is_specific_select = false;
				if (0 === strpos($key, 'checked-')) {
					$value = $value === 'yes' ? 'checked="checked"' : '';
				} elseif (0 === strpos($key, 'select-')) {
					$is_specific_select = true;
				} elseif (0 === strpos($key, 'tag-template-')) {
					$is_select = true;
					if ($is_default) {
						$value = '<option value=""></option>--tag-alphabetic-selection--';
					} else {
						$option = '<option s--selected---d value=""></option>' . self::get_tag_selection_template();
					}
				} elseif (0 === strpos($key, 'page-template-')) {
					$is_select = true;
					if ($is_default) {
						$value = '<option value="0"></option>--page-selection--';
					} else {
						$option = '<option s--selected-0--d value="0"></option>' . self::get_all_post_page_selection_template();
					}
				} else {
					$value = esc_textarea($value);
				}
				if (!$is_default && $is_select) {
					$option = str_replace('s--selected-'.$value.'--d', 'selected="selected"', $option);
					$value = preg_replace('/s--selected-.*?--d/', '', $option);
				}
				$key = $root_key . $key;
				if ($is_specific_select) {
					$template = str_replace('s--' . $key . '--' . $value . '--d', 'selected="selected"', $template);
					$template = preg_replace('/s--' . $key . '--.*?--d/', '', $template);
				} else {
					$template = str_replace('{{' . $key . '}}', $value, $template);
				}
			}
		}
		return $template;
	}
	public static function upload_icon_file($settings, $action_arg, $url_arg, $file_id) {
		try{
			if('upload' === $settings[$action_arg]){
				$settings[$url_arg] = '';
				if (isset($_FILES[$file_id]) && !empty($_FILES[$file_id]['size'])) {
					$file_upload_result = wp_handle_upload($_FILES[$file_id], array('test_form' => false));
					if(!empty($file_upload_result['url'])) {
						$image_size_result = @getimagesize($file_upload_result['file']);
						if(false === $image_size_result) {
							throw new Exception('Please upload a valid image filetype.');
						} else {
							$settings[$url_arg] = $file_upload_result['url'];
						}
					} else if(!empty($file_upload_result['error'])) {
						throw new Exception($file_upload_result['error']);
					}
				}
			}
		} catch (Exception $e) {
		}
		$settings[$action_arg] = 'url';
		return $settings;
	}
	public static function get_all_posts_with_progress_meta() {
		global $wpdb;
		$posts = $wpdb->get_results("SELECT $wpdb->posts.ID as ID, $wpdb->posts.post_title as post_title FROM $wpdb->postmeta LEFT JOIN $wpdb->posts ON $wpdb->postmeta.post_id = $wpdb->posts.ID WHERE $wpdb->postmeta.meta_key = '" . ProgressAllyTaskDefinition::META_KEY_TASK_DEFINITION . "' ORDER BY $wpdb->posts.post_title", OBJECT_K);
		return $posts;
	}
	public static function read_cookie($cookie_name) {
		if (!empty($cookie_name) && isset($_COOKIE[$cookie_name])) {
			$value_string = stripslashes($_COOKIE[$cookie_name]);
			$value = json_decode($value_string, true);
			return $value;
		}
		return null;
	}
	public static function has_admin_privilege($user_id = null) {
		if (is_numeric($user_id)) {
			$user = get_userdata($user_id);
		} else {
			$user = wp_get_current_user();
		}
		if (empty($user) || empty($user->data) || $user->ID <= 0) {
			return false;
		}

		return in_array('administrator', (array) $user->roles ) || in_array('super admin', (array) $user->roles );
	}
	public static function generate_user_edit_link($wp_user_id) {
		$userdata = get_userdata($wp_user_id);
		$name = 'Unknown user';
		if ($userdata) {
			$name = $userdata->first_name . ' ' . $userdata->last_name;
		}
		return '<a target="_blank" href="' . get_edit_user_link($wp_user_id) . '">' . esc_attr($name) . '</a>';
	}
	private static $cached_sql_time = null;
	public static function get_sql_time() {
		if (null === self::$cached_sql_time) {
			$time = time();
			self::$cached_sql_time = gmdate('Y-m-d H:i:s', $time);
		}
		return self::$cached_sql_time;
	}
	private static $cached_post_name = array();
	public static function get_post_name($post_id) {
		if (!isset(self::$cached_post_name[$post_id])) {
			$filter = 'ID=' . $post_id;

			global $wpdb;
			$post = $wpdb->get_row("SELECT ID, post_title FROM $wpdb->posts WHERE $filter");
			self::$cached_post_name[$post_id] = $post->post_title;
		}
		return self::$cached_post_name[$post_id];
	}
	private static $cached_posts_with_notes = null;
	public static function get_all_post_with_notes() {
		if (null === self::$cached_posts_with_notes) {
			global $wpdb;
			self::$cached_posts_with_notes = $wpdb->get_results("SELECT {$wpdb->posts}.ID as ID, {$wpdb->posts}.post_title as post_title, {$wpdb->postmeta}.meta_value as meta_value FROM {$wpdb->posts} INNER JOIN {$wpdb->postmeta} ON "
				. "{$wpdb->posts}.ID = {$wpdb->postmeta}.post_id WHERE {$wpdb->postmeta}.meta_key = '" . ProgressAllyNote::META_KEY . "'", ARRAY_A);
		}
		return self::$cached_posts_with_notes;
	}
	public static function generate_random_string($len) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randstring = '';
		for ($i = 0; $i < $len; $i++) {
			$randstring .= $characters[rand(0, strlen($characters) - 1)];
		}
		return $randstring;
	}
	// <editor-fold defaultstate="collapsed" desc="Post meta processing before save">
	private static function json_name_string_to_keys($name) {
		$a = array();
		$start = strpos($name, '[');
		$end = 0;
		if ($start > 0) {
			$a []= substr($name, 0, $start);
		}
		while($start !== false) {
			$start += 1;
			$end = strpos($name, ']', $start);
			if ($end === false) {
				$end = $start;
				break;
			}
			$a []= substr($name, $start, $end - $start);
			$end += 1;	// skip the ']' symbol
			$start = strpos($name, '[', $end);
		}
		if ($end < strlen($name)) {
			$a []= substr($name, $end);
		}
		return $a;
	}
	public static function convert_setting_string_to_array($str) {
		$str = stripslashes($str);
		$raw_array = json_decode($str, true);
		$a = array();
		if (!is_array($raw_array)) {
			return false;
		}
		foreach ($raw_array as $tuple) {
			if (isset($tuple['name']) && isset($tuple['value'])) {
				$keys = self::json_name_string_to_keys($tuple['name']);
				$v = & $a;
				for($i = 0; $i < count($keys) - 1; ++$i) {
					if (!array_key_exists($keys[$i], $v)) {
						$v[$keys[$i]] = array();
					}
					$v = & $v[$keys[$i]];
				}
				$last_key = end($keys);
				if (empty($last_key)) {
					$v []= $tuple['value'];
				} else {
					$v[end($keys)] = $tuple['value'];
				}
			}
		}
		return $a;
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="page selection template">
	public static function get_all_posts($type, $offset = false, $num_to_fetch = false, $post_name = false) {
		$filter = '';
		if (!empty($post_name)) {
			$filter .= ' AND post_title LIKE "%' . $post_name . '%"';
		}
		$filter .= ' ORDER BY post_title';
		if ($num_to_fetch) {
			$filter .= ' LIMIT ' . $num_to_fetch;
		}
		if ($offset) {
			$filter .= ' OFFSET ' . $offset;
		}

		global $wpdb;
		$posts = $wpdb->get_results("SELECT ID, post_date, post_title, post_parent FROM $wpdb->posts WHERE post_status IN ('publish','draft') AND post_type = '$type' $filter", OBJECT_K);
		return $posts;
	}
	public static function get_post_count($type, $post_name = false) {
		$filter = '';
		if (!empty($post_name)) {
			$filter .= ' AND post_title LIKE "%' . $post_name . '%"';
		}

		global $wpdb;
		$query = "SELECT COUNT(ID) AS count FROM {$wpdb->posts} WHERE post_status IN ('publish','draft') AND post_type = '$type' $filter";
		$result = $wpdb->get_row($query);

		return intval($result->count);
	}
	private static function append_child_posts($all_posts, $children_mapping, $post_id, &$results) {
		if (isset($children_mapping[$post_id])) {
			foreach ($children_mapping[$post_id] as $child_id) {
				$results []= $all_posts[$child_id];
				self::append_child_posts($all_posts, $children_mapping, $child_id, $results);
			}
		}
	}
	public static function get_all_hierarchical_posts($type) {
		$all_posts = self::get_all_posts($type);
		$top_level_posts = array();
		$children_mapping = array();
		foreach ($all_posts as $post_id => $post) {
			if ($post->post_parent > 0) {
				if (!isset($children_mapping[$post->post_parent])) {
					$children_mapping[$post->post_parent] = array();
				}
				$children_mapping[$post->post_parent] []= $post_id;
			} else {
				$top_level_posts [] = $post_id;
			}
		}
		$results = array();

		foreach ($top_level_posts as $post_id) {
			$results []= $all_posts[$post_id];
			self::append_child_posts($all_posts, $children_mapping, $post_id, $results);
		}
		return $results;
	}
	public static function get_all_valid_posts_for_selection() {
		$custom_post_types = get_post_types(array('public' => 'true', '_builtin' => false), 'object');
		$type_names = array('page', 'post');
		foreach($custom_post_types as $post_type) {
			$type_names []= $post_type->name;
		}
		$type_string = "'" . implode("','", $type_names) . "'";
		global $wpdb;
		$all_pages_raw = $wpdb->get_results("SELECT ID, post_title, post_status, post_parent FROM $wpdb->posts WHERE post_type IN ($type_string) ORDER BY post_title", ARRAY_A);
		return $all_pages_raw;
	}
	private static function recursively_get_parent_page_name($parent_id, $post_id_mapping) {
		if ($parent_id <= 0 || !isset($post_id_mapping[$parent_id])) {
			return '';
		}
		$parent_page = $post_id_mapping[$parent_id];
		return self::recursively_get_parent_page_name($parent_page['post_parent'], $post_id_mapping) . $parent_page['post_title'] . ' > ';
	}
	public static function get_all_post_page_id_name_map() {
		self::generate_post_page_selection_data();
		return self::$cached_post_page_id_name_map;
	}
	public static function get_all_post_page_selection_template() {
		self::generate_post_page_selection_data();
		return self::$cached_post_page_selection_template;
	}
	private static $cached_post_page_selection_template = null;
	private static $cached_post_page_id_name_map = null;
	private static function generate_post_page_selection_data() {
		if (null === self::$cached_post_page_selection_template || null === self::$cached_post_page_id_name_map) {
			self::$cached_post_page_selection_template = '';
			self::$cached_post_page_id_name_map = array();
			$all_pages_raw = self::get_all_valid_posts_for_selection();
			if (is_array($all_pages_raw)) {
				$post_id_mapping = array();
				foreach ($all_pages_raw as $row) {
					$post_id_mapping[$row['ID']] = $row;
				}
				foreach ($all_pages_raw as $row) {
					$display_text = self::recursively_get_parent_page_name($row['post_parent'], $post_id_mapping);
					$display_text .= $row['post_title'];
					self::$cached_post_page_selection_template .= '<option s--selected-'.$row['ID'].'--d value="'.$row['ID'].'">' . esc_html($display_text) . ' (' . $row['ID'] . ')</option>';
					self::$cached_post_page_id_name_map[$row['ID']] = $display_text;
				}
			}
		}
	}
	// </editor-fold>
	
	// <editor-fold defaultstate="collapsed" desc="tag selection autocomplete box template">
	public static function generate_tag_selection_code($selected_value) {
		$template_code = self::get_tag_selection_template();
		return self::generate_selection_code($template_code, $selected_value);
	}

	private static $cached_tag_selection_template = null;
	private static $cached_tag_id_mapping = null;
	public static function get_tag_selection_template() {
		self::generate_tag_selection_data();
		return self::$cached_tag_selection_template;
	}
	public static function get_tag_id_mapping() {
		self::generate_tag_selection_data();
		return self::$cached_tag_id_mapping;
	}
	private static function generate_tag_selection_data() {
		if (null === self::$cached_tag_selection_template || null === self::$cached_tag_id_mapping) {
			$all_tags = ProgressAllyMembershipUtilities::get_all_tags();

			self::$cached_tag_selection_template = '0';	// the value is used in Javascript as a magic value, so we can't use 'false', as that will just be shown as an empty string
			self::$cached_tag_id_mapping = array();

			if (is_array($all_tags)) {
				self::$cached_tag_selection_template = '';
				foreach ($all_tags as $tag) {
					self::$cached_tag_selection_template .= '<option s--selected-' . $tag['Id'] . '--d value="' . $tag['Id'] . '">' . esc_html($tag['TagName']) . '</option>';
					self::$cached_tag_id_mapping[$tag['Id']] = $tag['TagName'];
				}
			}
		}
	}
	// </editor-fold>
	
	// <editor-fold defaultstate="collapsed" desc="field selection autocomplete box template">
	public static function generate_field_selection_code($selected_value) {
		$template_code = self::get_field_selection_template();
		return self::generate_selection_code($template_code, $selected_value);
	}

	private static $cached_field_selection_template = null;
	private static $cached_field_id_mapping = null;
	public static function get_field_selection_template() {
		self::generate_field_selection_data();
		return self::$cached_field_selection_template;
	}
	public static function get_field_id_mapping() {
		self::generate_field_selection_data();
		return self::$cached_field_id_mapping;
	}
	private static function generate_field_selection_data() {
		if (null === self::$cached_field_selection_template || null === self::$cached_field_id_mapping) {
			$all_fields = ProgressAllyMembershipUtilities::get_all_fields();

			self::$cached_field_selection_template = '0';	// the value is used in Javascript as a magic value, so we can't use 'false', as that will just be shown as an empty string
			self::$cached_field_id_mapping = array();

			if (is_array($all_fields)) {
				self::$cached_field_selection_template = '';
				foreach ($all_fields as $field) {
					self::$cached_field_selection_template .= '<option s--selected-' . $field['Id'] . '--d value="' . $field['Id'] . '">' . esc_html($field['Label']) . '</option>';
					self::$cached_field_id_mapping[$field['Id']] = $field['Label'];
				}
			}
		}
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="popup selection template">
	public static function generate_popup_selection_code($selected_value) {
		$template_code = self::get_popup_selection_template();
		return self::generate_selection_code($template_code, $selected_value);
	}

	private static $cached_popup_selection_template = null;
	public static function get_popup_selection_template() {
		if (null === self::$cached_popup_selection_template) {
			$popup_selection = '0';	// the value is used in Javascript as a magic value, so we can't use 'false', as that will just be shown as an empty string
			if (class_exists('PopupAllyPro') && PopupAllyPro::$popupally_pro_enabled && method_exists('PopupAllyProAPI', 'get_popup_list')) {
				$all_popups = PopupAllyProAPI::get_popup_list();
				if (!empty($all_popups)) {
					$popup_selection = '';
					foreach ($all_popups as $id => $name) {
						$popup_selection .= '<option s--selected-' . $id . '--d value="' . $id . '">' . $id . '. ' . esc_html($name) . '</option>';
					}
				}
			}
			self::$cached_popup_selection_template = $popup_selection;
		}
		return self::$cached_popup_selection_template;
	}
	// </editor-fold>
	
	private static function generate_selection_code($template_code, $selected_value) {
		if ('0' === $template_code) {	// not connected to a CRM (or the connection info is invalid), so we just preserve the existing value
			return '<option selected="selected" value="' . $selected_value . '">' . $selected_value . '</option>';
		}
		$template_code = str_replace('s--selected-' . $selected_value . '--d', 'selected="selected"', $template_code);
		$template_code = preg_replace('/s--selected-.*?--d/', '', $template_code);
		return $template_code;
	}
	
	public static function generate_display_code($template_code, $display_placeholder, $display) {
		if ($display) {
			$template_code = str_replace($display_placeholder, '', $template_code);
		} else {
			$template_code = str_replace($display_placeholder, 'style="display:none"', $template_code);
		}
		return $template_code;
	}

	// <editor-fold defaultstate="collapsed" desc="popup selection template">
	const SLICE_SIZE = 102400;
	public static function write_uploaded_file($full_path) {
		if (!isset($_FILES['content']) || empty($_FILES['content']['size'])) {
			throw new ProgressAllyFileUploadRetryException('Please retry', $full_path);
		}
		$input_file = false;
		$output_file = false;
		try {
			$num_to_write = $_FILES['content']['size'];
			$input_file = fopen($_FILES['content']["tmp_name"], 'r');
			$content = fread($input_file, self::SLICE_SIZE);
			fclose($input_file);
			$input_file = false;
			if ($num_to_write !== strlen($content)) {
				throw new ProgressAllyFileUploadRetryException('Please retry', $full_path);
			}

			$output_file = fopen($full_path, 'a');
			$num_written = fwrite($output_file, $content);
			if ($num_written === false) {
				throw new ProgressAllyFileUploadRetryException('Please retry', $full_path);
			}
			if ($num_written < $num_to_write) {
				for ($retry = 0; $retry < 10; ++$retry) {
					$write_status = fwrite($output_file, substr($content, $num_written));
					$num_written += $write_status;
					if ($num_written >= $num_to_write) {
						break;
					}
				}
				if ($num_written < $num_to_write) {
					throw new Exception('Writing file to server failed');
				}
			}
			fclose($output_file);
			$output_file = false;
		} catch (Exception $e) {
			if ($input_file) {
				fclose($input_file);
			}
			if ($output_file) {
				fclose($output_file);
			}
			throw $e;
		}
		return $num_written;
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="utility function to retrieve the user id for a shortcode">
	/* Use the AccessAlly function if available.
	 * If not, use the current user id.
	 */
	public static function get_current_user_id_for_display($user_id, $request, $context, $default) {
		if (class_exists('AccessAllySettingLicense') && AccessAllySettingLicense::$accessally_enabled && method_exists('AccessAllyAPI', 'get_current_user_id_for_display')) {
			return AccessAllyAPI::get_current_user_id_for_display($user_id, $request, $context, $default);
		}
		return ProgressAllyUserProgress::get_user_id($user_id);
	}
	// </editor-fold>
}