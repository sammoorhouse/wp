<?php
class ProgressAllyUtilities{
	public static function get_settings($key, $default) {
		$setting = get_transient($key);

		if (!is_array($setting)) {
			$setting = get_option($key, $default);

			set_transient($key, $setting, ProgressAlly::CACHE_PERIOD);
		}

		if (!is_array($setting)) {
			$setting = $default;
		} else {
			$setting = wp_parse_args($setting, $default);
		}

		return $setting;
	}
	public static function set_settings($key, $settings, $default, $add_timestamp = false) {
		$settings = wp_parse_args($settings, $default);
		if ($add_timestamp) {
			$settings['created'] = date("Y-m-d H:i:s");
		}
		update_option($key, $settings);
		set_transient($key, $settings, ProgressAlly::CACHE_PERIOD);
		return $settings;
	}
	public static function remove_css_newline($str) {
		if (is_array($str)) {
			foreach($str as $key => $value) {
				$str[$key] = self::remove_newline($value);
			}
			return $str;
		}
		$str = str_replace("\r", '', $str);
		$str = str_replace("\n", '', $str);
		return $str;
	}
	public static function generate_css_file($code) {
		if (!function_exists('request_filesystem_credentials')) {
			require_once (ABSPATH . '/wp-admin/includes/file.php');
		}
		if (false === ($creds = request_filesystem_credentials('admin.php', '', false, false, null))) {
			return true;
		}
		if (!WP_Filesystem($creds)) {
			echo 'Cannot initiate WP_Filesystem. Please make sure you have the proper permission on the WordPress Install';
			return true;
		}
		global $wp_filesystem;
		$target_dir = trailingslashit($wp_filesystem->wp_content_dir());
		$target_dir = trailingslashit($target_dir . ProgressAlly::CSS_FOLDER);

		if(!$wp_filesystem->is_dir($target_dir)) {
			$wp_filesystem->mkdir($target_dir);
		}

		$code = self::remove_css_newline($code);
		$wp_filesystem->put_contents($target_dir . 'progressally-style.css', $code, FS_CHMOD_FILE);
	}
	public static function escape_string_csv($input_string) {
		$escape_string = $input_string;
		
		$need_escape = strpos($input_string, '"');
		if ($need_escape === false) {
			$need_escape = strpos($input_string, ',');
		} else {
			$escape_string = str_replace('"', '""', $input_string);
		}
		
		if ($need_escape !== false) {
			$escape_string = '"' . $escape_string . '"';
		}
		return $escape_string;
	}

	public static function replace_json_safe_string($param) {
		foreach ($param as $key => $value) {
			if (is_string($value)) {
				$value = str_replace('&quot;', '"', $value);
				$value = str_replace('&#039;', "'", $value);
				$value = str_replace('&lt;', '<', $value);
				$value = str_replace('&gt;', '>', $value);
				$value = str_replace('&amp;', '&', $value);
				$param[$key] = $value;
			}
		}
		return $param;
	}
	
	public static function batch_insert_entries_database($query_string, $place_holder, $values, $entry_size) {
		// Use when fields contain complex string (e.g. serialized)
		if (empty($values)) {
			return;
		}
		
		global $wpdb;
		$value_batch = array_chunk($values, $entry_size * 250);
		$num_batches = count($value_batch);

		for ($batch_index = 0; $batch_index < $num_batches; ++$batch_index) {
			$batch_size = count($value_batch[$batch_index])/$entry_size;
			$place_holder_batch = array_fill(0, $batch_size, $place_holder);
			$query = $query_string . implode(',', $place_holder_batch);
			$wpdb->query($wpdb->prepare($query, $value_batch[$batch_index]));
		}
	}
	
	public static function batch_insert_entries_database_simple($query_string, $values) {
		// Use when fields do not contain complex string (e.g. serialized)
		if (empty($values)) {
			return;
		}
		
		global $wpdb;
		$total_entry_num = count($values);
		$value_string = '';
		$current_batch_counter = 0;
		for ($i = 0; $i < $total_entry_num; ++$i) {
			$value_string .= "('" . implode("','", $values[$i]) . "')";
			++$current_batch_counter;
			if ($current_batch_counter >= 250) {
				$query = $query_string . $value_string;
				$wpdb->query($query);

				$value_string = '';
				$current_batch_counter = 0;
			} else {
				$value_string .= ',';
			}
		}
		if (!empty($value_string)) {
			$value_string = rtrim($value_string, ',');	// remove the trailing comma
			$query = $query_string . $value_string;
			$wpdb->query($query);
		}
	}
	
	public static function get_optional_post_data($tag, $default) {
		if (isset($_POST[$tag])) {
			return $_POST[$tag];
		}
		return $default;
	}
	
	public static function get_post_titles() {
		// build a map with post_id as the key and post title as value.
		$post_title = array();
		
		global $wpdb;
		$post_title_raw = $wpdb->get_results("SELECT ID, post_title FROM $wpdb->posts WHERE post_status IN ('publish','future','draft','pending')", ARRAY_A);
		if (is_array($post_title_raw)) {
			foreach ($post_title_raw as $row) {
				$post_title[$row['ID']] = $row['post_title'];
			}
		}
		return $post_title;
	}
	public static function clear_wp_cache() {
		// clear WPEngine cache
		if (class_exists('WpeCommon')) {
			if (method_exists('WpeCommon', 'purge_memcached')) { 
				WpeCommon::purge_memcached();
			}
			if (method_exists('WpeCommon', 'clear_maxcdn_cache')) { 
				WpeCommon::clear_maxcdn_cache();
			}
			if (method_exists('WpeCommon', 'purge_varnish_cache')) { 
				WpeCommon::purge_varnish_cache();
			}
		}
		// clear W3 Total Cache cache
		if ( function_exists( 'w3tc_pgcache_flush' ) ) {
			w3tc_pgcache_flush(); 
		}
		// clear WP Super Cache
		if ( function_exists( 'wp_cache_clean_cache' ) ) {
			global $file_prefix;
			wp_cache_clean_cache($file_prefix);
		}
		// clear WP Fastest Cache
		do_action('wp_ajax_wpfc_delete_cache_and_minified');
	}

	public static function get_all_option_with_prefix($prefix) {
		global $wpdb;

		$query = $wpdb->prepare("SELECT option_name, option_value FROM {$wpdb->options} WHERE option_name LIKE %s ORDER BY option_id ASC", $prefix . '%');
		return $wpdb->get_results($query, OBJECT);
	}
}
