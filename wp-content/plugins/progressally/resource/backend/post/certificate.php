<?php
class ProgressAllyCertificate {
	const META_KEY = '_progressally_certificate';

	// <editor-fold defaultstate="collapsed" desc="activation setup / deactivation cleanup">
	public static function do_activation_actions(){
		wp_cache_delete(self::META_KEY);
		self::create_certificate_directory();
	}
	public static function do_deactivation_actions(){
		wp_cache_delete(self::META_KEY);
	}
	const CERTIFICATE_FOLDER = 'progressally-certificate';
	private static function get_certificate_folder() {
		$upload_dir = wp_upload_dir();
		return trailingslashit($upload_dir['basedir']) . self::CERTIFICATE_FOLDER;
	}
	private static function get_certificate_preview_url($file_path) {
		$advanced_settings = ProgressAllySettingAdvanced::get_advanced_settings();
		$url = '';
		if ('yes' === $advanced_settings['checked-certificate-preview-ajax']) {
			$admin_url = admin_url('admin-ajax.php');
			$url = add_query_arg(array('action' => 'progressally_certificate_preview', 'path' => $file_path), $admin_url);
		} else {
			$url = self::get_certificate_url() . '/' . urlencode($file_path);
		}
		return $url;
	}
	private static function get_certificate_url() {
		$upload_dir = wp_upload_dir();
		return trailingslashit($upload_dir['baseurl']) . self::CERTIFICATE_FOLDER;
	}
	public static function create_certificate_directory() {
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
		$target_dir = self::get_certificate_folder();

		if(!$wp_filesystem->is_dir($target_dir)) {
			$wp_filesystem->mkdir($target_dir);
		}
	}
	// </editor-fold>

	private static $default_certificate_settings = array('max-cert' => '0', 'cert' => array());
	private static $default_per_certificate_settings = array('checked-is-open' => 'no', 'name' => 'ProgressAlly Certificate',
		'file-path' => '', 'file-name' => '', 'width' => '0', 'height' => '0', 'custom' => array(), 'max-elem' => '0');
	private static $default_certificate_element_settings = array('select-type' => 'full-name', 'select-date-type' => 'current', 'custom-value' => '', 'w' => '50', 'x' => '10', 'y' => '10',
		'color' => '#111111', 'select-font' => 'helvetica', 'font-size' => '20', 'select-align' => 'center');

	public static $FONT_MAPPING = array('helvetica' => 'Arial, Helvetica, sans-serif', 'times' => '"Times New Roman", Times, serif',
		'Georgia' => 'Georgia, serif', 'Tahoma' => 'Tahoma, Geneva, sans-serif', 'TrebuchetMS' => '"Trebuchet MS", Helvetica, sans-serif');
	private static $FONT_MAPPING_DISPLAY = array('helvetica' => 'Arial / Helvetica', 'times' => 'Times New Roman',
		'Georgia' => 'Georgia', 'Tahoma' => 'Tahoma / Geneva', 'TrebuchetMS' => 'Trebuchet MS');

	public static function add_actions() {
		add_action('wp_ajax_progressally_upload_certificate_pdf', array(__CLASS__, 'upload_file_callback'));
		add_action('wp_ajax_progressally_admin_download_certificate', array(__CLASS__, 'process_test_certificate_download'));

		add_action('wp_ajax_progressally_certificate_preview', array(__CLASS__, 'load_raw_pdf_callback'));

		add_action('wp_ajax_progressally_download_certificate', array(__CLASS__, 'process_certificate_download'));
		add_action('wp_ajax_nopriv_progressally_download_certificate', array(__CLASS__, 'process_certificate_download'));
	}

	// <editor-fold defaultstate="collapsed" desc="Generate backend settings display - certificate section">
	private static $cached_certificate_template= null;
	private static function get_certificate_template() {
		if (self::$cached_certificate_template === null) {
			self::$cached_certificate_template = file_get_contents(dirname(__FILE__) . '/certificate-template.php');
		}
		return self::$cached_certificate_template;
	}
	private static function generate_certificate_section_code($certificate_id, $certificate_settings) {
		$code = self::get_certificate_template();

		$code = ProgressAllyBackendShared::replace_real_values($code, $certificate_settings, '');
		$code = ProgressAllyBackendShared::replace_all_toggle($code, $certificate_settings);

		if (!empty($certificate_settings['file-path'])) {
			$url = self::get_certificate_preview_url($certificate_settings['file-path']) . '#toolbar=0&navpanes=0&scrollbar=0&view=FitH';
			$code = str_replace('{{pdf-preview}}',
				'<object data="' . esc_attr($url) . '" type="application/pdf" width="100%" height="100%"><p>Please install Adobe Acrobat Reader to see the preview</p></object>',
				$code);
			$code = str_replace('{{has-existing-show}}', '', $code);
			$code = str_replace('{{has-existing-hide}}', 'style="display:none;"', $code);
		} else {
			$code = str_replace('{{pdf-preview}}', '', $code);
			$code = str_replace('{{has-existing-show}}', 'style="display:none;"', $code);
			$code = str_replace('{{has-existing-hide}}', '', $code);
		}

		$preview_width = 600;
		$preview_height = 0;
		if (floatval($certificate_settings['width']) > 0) {
			$scale_factor = $preview_width / floatval($certificate_settings['width']);
			$preview_height = floatval($certificate_settings['height']) * $scale_factor;
		}
		$code = str_replace('{{preview-width}}', $preview_width, $code);
		$code = str_replace('{{preview-height}}', $preview_height, $code);

		$element_customization_code = '';
		$element_preview_code = '';

		if (isset($certificate_settings['custom']) && is_array($certificate_settings['custom'])) {
			foreach ($certificate_settings['custom'] as $element_id => $element_settings) {
				$element_customization_code .= self::generate_element_customization_code($element_id, $element_settings);
				$element_preview_code .= self::generate_element_preview_code($element_id, $element_settings, $scale_factor);
			}
		}
		$code = str_replace('{{element-customizations}}', $element_customization_code, $code);
		$code = str_replace('{{element-previews}}', $element_preview_code, $code);

		$code = str_replace('{{open-class}}', $certificate_settings['checked-is-open'] === 'yes' ? 'progressally-accordion-opened' : '', $code);
		$code = str_replace('{{certificate-id}}', $certificate_id, $code);

		if ($certificate_id === '--certificate-id--') {
			$code = str_replace('{{plugin-uri}}', '--plugin-uri--', $code);
		} else {
			$code = str_replace('{{plugin-uri}}', ProgressAlly::$PLUGIN_URI, $code);
		}
		return $code;
	}
	private static function generate_all_certificate_code($settings) {
		$certificate_code = '';
		foreach ($settings['cert'] as $id => $certificate_settings) {
			$certificate_code .= self::generate_certificate_section_code($id, $certificate_settings);
		}
		return $certificate_code;
	}
	public static function generate_default_certificate_section_code() {
		$code = self::generate_certificate_section_code('--certificate-id--', self::$default_per_certificate_settings);
		return $code;
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="Generate backend settings display - element preview">
	private static $cached_element_preview_template= null;
	private static function get_element_preview_template() {
		if (self::$cached_element_preview_template === null) {
			self::$cached_element_preview_template = file_get_contents(dirname(__FILE__) . '/certificate-element-preview-template.php');
		}
		return self::$cached_element_preview_template;
	}
	private static function generate_element_preview_code($element_id, $element_settings, $mm_scaling_factor, $preview_value = false) {
		$code = self::get_element_preview_template();

		$style_code = 'width:' . round($mm_scaling_factor * $element_settings['w']) . 'px;';
		$style_code .= 'left:' . round($mm_scaling_factor * $element_settings['x']) . 'px;';
		$style_code .= 'top:' . round($mm_scaling_factor * $element_settings['y']) . 'px;';
		$font_size = round(0.352778 * $mm_scaling_factor * $element_settings['font-size']) . 'px;';
		$style_code .= 'font-size:' . $font_size;
		$style_code .= 'line-height:' . $font_size;
		$style_code .= 'color:' . $element_settings['color'] . ';';
		$style_code .= 'text-align:' . $element_settings['select-align'] . ';';
		if (isset(self::$FONT_MAPPING[$element_settings['select-font']])) {
			$style_code .= 'font-family:' . self::$FONT_MAPPING[$element_settings['select-font']] . ';';
		}
		$code = str_replace('{{style}}', $style_code, $code);

		if (false === $preview_value) {
			$preview_value = self::generate_dynamic_values($element_settings, -1);
		}
		$code = str_replace('{{preview-value}}', $preview_value, $code);
		$code = str_replace('{{element-id}}', $element_id, $code);
		return $code;
	}
	public static function generate_default_element_preview_code() {
		$code = self::generate_element_preview_code('--element-id--', self::$default_certificate_element_settings, 0, '');
		$code = str_replace('{{certificate-id}}', '--certificate-id--', $code);
		return $code;
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="Generate backend settings display - element customization">
	private static $cached_element_customization_select_type_options = null;
	private static function get_element_select_type_options() {
		if (self::$cached_element_customization_select_type_options === null) {
			self::$cached_element_customization_select_type_options = '';
			foreach (self::$TEMPLATE_OPTIONS as $key => $param) {
				self::$cached_element_customization_select_type_options .= '<option s--select-type--' . $key . '--d value="' . esc_attr($key) . '">' . esc_html($param[0]) . '</option>';
			}
		}
		return self::$cached_element_customization_select_type_options;
	}
	private static $cached_element_customization_select_font_options = null;
	private static function get_element_select_font_options() {
		if (self::$cached_element_customization_select_font_options === null) {
			self::$cached_element_customization_select_font_options = '';
			foreach (self::$FONT_MAPPING_DISPLAY as $key => $label) {
				self::$cached_element_customization_select_font_options .= '<option s--select-font--' . $key . '--d value="' . esc_attr($key) . '">' . esc_html($label) . '</option>';
			}
		}
		return self::$cached_element_customization_select_font_options;
	}
	private static $cached_element_customization_template= null;
	private static function get_element_customization_template() {
		if (self::$cached_element_customization_template === null) {
			self::$cached_element_customization_template = file_get_contents(dirname(__FILE__) . '/certificate-element-customization-template.php');
		}
		return self::$cached_element_customization_template;
	}
	private static function generate_element_customization_additional_input_dependency_val($code) {
		$additional_inputs = array();
		foreach (self::$TEMPLATE_OPTIONS as $key => $param) {
			if (!empty($param[1])) {
				if (!isset($additional_inputs[$param[1]])) {
					$additional_inputs[$param[1]] = array($key);
				} else {
					$additional_inputs[$param[1]] []= $key;
				}
			}
		}
		foreach ($additional_inputs as $type => $dependency_values) {
			$code = str_replace('{{template-additional-input-' . $type . '}}', implode(',', $dependency_values), $code);
		}
		return $code;
	}
	private static function generate_element_customization_code($element_id, $element_settings, $preview_value = false) {
		$code = self::get_element_customization_template();
		
		$code = self::generate_element_customization_additional_input_dependency_val($code);

		$code = str_replace('{{select-type-options}}', self::get_element_select_type_options(), $code);	// add in the selections values which will be selected in 'replace_real_values'
		$code = str_replace('{{select-font-options}}', self::get_element_select_font_options(), $code);	// add in the selections values which will be selected in 'replace_real_values'
		$code = ProgressAllyBackendShared::replace_real_values($code, $element_settings, '');
		$code = ProgressAllyBackendShared::replace_all_toggle($code, $element_settings);

		if (false === $preview_value) {
			$preview_value = self::generate_dynamic_values($element_settings, -1);
		}
		if (!is_string($preview_value)) {
			$preview_value = '';
		}
		$code = str_replace('{{preview-value}}', esc_attr($preview_value), $code);
		$code = str_replace('{{element-id}}', $element_id, $code);
		return $code;
	}
	public static function generate_default_element_customization_code() {
		$code = self::generate_element_customization_code('--element-id--', self::$default_certificate_element_settings, '');
		$code = str_replace('{{certificate-id}}', '--certificate-id--', $code);
		return $code;
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="Generate backend settings display">
	public static function show_certificate_meta_box($post_id, $certificate_meta = false) {
		if ($certificate_meta === false) {
			$certificate_meta = self::get_post_certificate_meta($post_id);
		}
		$certificate_code = self::generate_all_certificate_code($certificate_meta);
		$max_cert_num = $certificate_meta['max-cert'];

		ob_start();
		include dirname(__FILE__) . '/certificate-display.php';
		return ob_get_clean();
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="Note meta operation">
	public static function save_post_certificate_meta($post_id) {
		if (isset($_POST[self::META_KEY]) && is_string($_POST[self::META_KEY]) && !empty($_POST[self::META_KEY])) {
			$certificate_meta = ProgressAllyBackendShared::convert_setting_string_to_array($_POST[self::META_KEY]);
			if (is_array($certificate_meta) && !empty($certificate_meta) && isset($certificate_meta['max-cert'])) {
				$certificate_meta = self::merge_default_settings($certificate_meta);
				update_post_meta($post_id, self::META_KEY, $certificate_meta);
				wp_cache_set(self::META_KEY, $certificate_meta, $post_id, time() + ProgressAlly::CACHE_PERIOD);
			}
		}
	}
	private static $cached_post_certificate_meta = array();
	public static function get_post_certificate_meta($post_id) {
		if (!isset(self::$cached_post_certificate_meta[$post_id])) {
			$meta = wp_cache_get(self::META_KEY, $post_id);
			if ($meta === false) {
				$meta = get_post_meta($post_id, self::META_KEY, true);
				if (!is_array($meta)) {
					$meta = self::$default_certificate_settings;
				}

				wp_cache_set(self::META_KEY, $meta, $post_id, time() + ProgressAlly::CACHE_PERIOD);
			}
			self::$cached_post_certificate_meta[$post_id] = self::merge_default_settings($meta);
		}
		return self::$cached_post_certificate_meta[$post_id];
	}
	public static function merge_default_settings($settings) {
		if (!isset($settings['cert'])) {
			$settings['cert'] = array();
		}
		foreach ($settings['cert'] as $certificate_id => $certificate_setting) {
			if (!isset($certificate_setting['custom'])) {
				$certificate_setting['custom'] = array();
			}
			foreach ($certificate_setting['custom'] as $element_id => $element_setting) {
				$certificate_setting['custom'][$element_id] = wp_parse_args($element_setting, self::$default_certificate_element_settings);
			}
			$settings['cert'][$certificate_id] = wp_parse_args($certificate_setting, self::$default_per_certificate_settings);
		}
		$settings = wp_parse_args($settings, self::$default_certificate_settings);
		return $settings;
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="Upload PDF file">
	const SLICE_SIZE = 102400;
	public static function upload_file_callback() {
		$nonce = $_POST['nonce'];

		if (!wp_verify_nonce($nonce, 'progressally-update-nonce')) {
			echo json_encode(array('status' => 'error', 'message' => 'Setting page is outdated/not valid'));
			die();
		}
		if (!function_exists('request_filesystem_credentials')) {
			require_once (ABSPATH . '/wp-admin/includes/file.php');
		}
		if (false === ($creds = request_filesystem_credentials('admin.php', '', false, false, null))) {
			echo json_encode(array('status' => 'error', 'message' => 'Cannot initiate WP_Filesystem. Please make sure you have the proper permission on the WordPress Install'));
			die();
		}
		if (!WP_Filesystem($creds)) {
			echo json_encode(array('status' => 'error', 'message' => 'Cannot initiate WP_Filesystem. Please make sure you have the proper permission on the WordPress Install'));
			die();
		}
		global $wp_filesystem;
		$target_dir = self::get_certificate_folder();
		$target_dir = trailingslashit($target_dir);
		try{
			if (!empty($_POST['path'])) {
				$file_name = $_POST['path'];
			} else {
				$file_name = urlencode($_POST['file_name']);
				if (file_exists($target_dir . $file_name)) {
					for ($i = 0; $i < 10 && file_exists($target_dir . $file_name); ++$i) {
						$file_name = ProgressAllyBackendShared::generate_random_string(1) . $file_name;
					}
					if (file_exists($target_dir . $file_name)) {
						echo json_encode(array('status' => 'error', 'message' => 'File update failed because the server folder is full.'));
						die();
					}
				}
			}
			$readable_file_name = $_POST['file_name'];
			$file_name_length = strlen($readable_file_name);
			$readable_file_name = substr($readable_file_name, 0, $file_name_length - 4);

			$full_path = $target_dir . $file_name;
			try {
				ProgressAllyBackendShared::write_uploaded_file($full_path);
			} catch (ProgressAllyFileUploadRetryException $e) {
				echo json_encode(array('status' => 'retry', 'path' => $file_name));
				die();
			}

			if (isset($_POST['last']) && $_POST['last'] === '1') {
				$one_page_full_path = substr($full_path, 0, strlen($full_path) - 4) . '-one-page.pdf';
				$final_full_path = ProgressAllyPdfUtilities::extract_page_one($full_path, $one_page_full_path);
				if ($final_full_path === $one_page_full_path) {
					$file_name = substr($file_name, 0, strlen($file_name) - 4) . '-one-page.pdf';
				}
				$dimension = ProgressAllyPdfUtilities::get_file_dimension($final_full_path);
				$url = self::get_certificate_preview_url($file_name);
				echo json_encode(array('status' => 'success', 'path' => $file_name, 'full-path' => $final_full_path, 'file-name' => $readable_file_name,
					'url' => $url, 'width' => $dimension[0], 'height' => $dimension[1]));
			} else {
				echo json_encode(array('status' => 'success', 'path' => $file_name, 'full-path' => $full_path, 'file-name' => $readable_file_name));
			}
		} catch (Exception $e) {
			echo json_encode(array('status' => 'error', 'message' => $e->getMessage()));
		}
		die();
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="Certificate Generation">
	private static $cached_current_user_info = false;
	private static function get_current_user_info() {
		if (false === self::$cached_current_user_info) {
			$current_user_id = ProgressAllyUserProgress::get_user_id();
			if ($current_user_id > 0) {
				self::$cached_current_user_info = get_userdata($current_user_id);
			} else {
				self::$cached_current_user_info = null;
			}
		}
		return self::$cached_current_user_info;
	}
	private static $cached_current_time = false;
	private static function get_current_time() {
		if (false === self::$cached_current_time) {
			self::$cached_current_time = time();
		}
		return self::$cached_current_time;
	}
	// the parameter for each template option is [0]: label, [1]: additional parameter type, [2]: additional parameters
	public static $TEMPLATE_OPTIONS = array('full-name' => array('Full Name', '', ''),
		'first-name' => array('First Name', '', ''),
		'last-name' => array('Last Name', '', ''),
		'full-date' => array('Date (YYYY-MM-DD)', 'date', 'Y-m-d'),
		'year' => array('Year (YYYY)', 'date', 'Y'),
		'month' => array('Month (MM)', 'date', 'm'),
		'lmonth' => array('Month (January - December)', 'date', 'F'),
		'day' => array('Day (DD)', 'date', 'd'),
		'days' => array('Day (1st - 31st)', 'date', 'jS'),
		'dayw' => array('Day of the week (Monday - Sunday)', 'date', 'l'),
		);
	public static function generate_template_options() {
		$result = array();
		$current_time = self::get_current_time();
		foreach (self::$TEMPLATE_OPTIONS as $key => $param) {
			$result[$key] = self::generate_simple_dynamic_value($key, $current_time);
		}
		return $result;
	}
	private static function generate_simple_dynamic_value($type, $display_time) {
		if (isset(self::$TEMPLATE_OPTIONS[$type]) && 'date' === self::$TEMPLATE_OPTIONS[$type][1]) {
			if (empty($display_time)) {
				return false;
			}
			return date(self::$TEMPLATE_OPTIONS[$type][2], $display_time);
		}
		switch ($type) {
			case 'full-name':
				$userdata = self::get_current_user_info();
				$result = '';
				if ($userdata) {
					$result = $userdata->first_name;
					if (!empty($result) && !empty($userdata->last_name)) {
						$result .= ' ';
					}
					$result .= $userdata->last_name;
				}
				return $result;
			case 'first-name':
				$userdata = self::get_current_user_info();
				if ($userdata) {
					return $userdata->first_name;
				}
				return '';
			case 'last-name':
				$userdata = self::get_current_user_info();
				if ($userdata) {
					return $userdata->last_name;
				}
				return '';
			default:
				return false;
		}
		return false;
	}
	private static function generate_custom_dynamic_value($custom_text, $post_id) {
		$custom_date = array();
		$current_time = self::get_current_time();
		preg_match_all("/\{\[date (.*?)\]\}/", $custom_text, $custom_date);
		if (isset($custom_date[1]) && !empty($custom_date[1])) {
			for ($i = 0; $i < count($custom_date[1]); ++$i) {
				$custom_text = str_replace($custom_date[0][$i], date($custom_date[1][$i], $current_time), $custom_text);
			}
		}

		preg_match_all("/\{\[complete_date (.*?)\]\}/", $custom_text, $custom_date);
		if (isset($custom_date[1]) && !empty($custom_date[1])) {
			$complete_time = false;
			if ($post_id <= 0) {	// generating a preview
				$complete_time = $current_time;
			} else {
				$complete_time = ProgressAllyUserProgress::get_completion_time($post_id);
			}
			for ($i = 0; $i < count($custom_date[1]); ++$i) {
				if ($complete_time > 0) {
					$custom_text = str_replace($custom_date[0][$i], date($custom_date[1][$i], $complete_time), $custom_text);
				} else {
					$custom_text = str_replace($custom_date[0][$i], '', $custom_text);
				}
			}
		}

		foreach (self::$TEMPLATE_OPTIONS as $key => $param) {
			$tag = '{[' . $key . ']}';
			if (strpos($custom_text, $tag) !== false) {
				$custom_text = str_replace($tag, self::generate_simple_dynamic_value($key, $current_time), $custom_text);
			}
		}
		return $custom_text;
	}
	private static function generate_dynamic_values($customization, $post_id) {
		$value = false;
		if ($customization['select-type'] === 'custom' && !empty($customization['custom-value'])) {
			$value = self::generate_custom_dynamic_value($customization['custom-value'], $post_id);
			$value = do_shortcode($value);
		} else {
			// default to showing current time, including for preview value (post_id = -1)
			$display_time = self::get_current_time();
			if ($post_id > 0) {
				if ('complete' === $customization['select-date-type']) {
					$display_time = ProgressAllyUserProgress::get_completion_time($post_id);
				}
			}
			$value = self::generate_simple_dynamic_value($customization['select-type'], $display_time);
		}
		return $value;
	}
	private static function process_dynamic_values($customizations, $post_id) {
		$result = array();
		foreach ($customizations as $custom) {
			if (isset($custom['select-type'])) {
				$value = self::generate_dynamic_values($custom, $post_id);
				if (false !== $value) {
					$custom['value'] = $value;
					$result []= $custom;
				}
			}
		}
		return $result;
	}
	public static function process_certificate_download() {
		try {
			if (isset($_REQUEST['post-id']) && isset($_REQUEST['cert-id']) && isset($_REQUEST['certificate-dl-nonce'])) {
				$post_id = intval($_REQUEST['post-id']);
				$certificate_id = intval($_REQUEST['cert-id']);
				if (!wp_verify_nonce($_REQUEST['certificate-dl-nonce'], 'progressally-certificate-' . $post_id . '-' . $certificate_id)) {
					echo 'The page is outdated. Please refresh and try again';
					exit;
				}
				set_time_limit(0);

				$certificate_meta = self::get_post_certificate_meta($post_id);
				if (!isset($certificate_meta['cert'][$certificate_id])) {
					throw new Exception('Invalid certificate');
				}
				$certificate_settings = $certificate_meta['cert'][$certificate_id];
				$path = $certificate_settings['file-path'];
				$file_name = $certificate_settings['file-name'] . '.pdf';
				$customizations = $certificate_settings['custom'];
				set_time_limit(0);

				$customized = self::process_dynamic_values($customizations, $post_id);
				$path = self::get_certificate_folder() . '/' . $path;
				ProgressAllyPdfUtilities::generate_customized_pdf($path, $file_name, $customized);
			}
		} catch (Exception $e) {
			echo '<html><head></head><body>Something went wrong. Please send the error message to the site admin so we can get this figured out!';
			echo '<div>' . esc_html($e->getMessage()) . '</div>';
			echo '<div>' . esc_html($e->getTraceAsString()) . '</div></body></html>';
		}
		exit;
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="Admin Test Certificate Generation">
	public static function process_test_certificate_download() {
		if (isset($_REQUEST['post-id']) && isset($_REQUEST['path']) && isset($_REQUEST['info'])) {
			try {
				$post_id = intval($_REQUEST['post-id']);
				$path = $_REQUEST['path'];
				$file_name = $_REQUEST['name'] . '.pdf';
				$customizations = ProgressAllyBackendShared::convert_setting_string_to_array($_REQUEST['info']);
				set_time_limit(0);

				$customized = self::process_dynamic_values($customizations, -1);
				$path = self::get_certificate_folder() . '/' . $path;
				ProgressAllyPdfUtilities::generate_customized_pdf($path, $file_name, $customized);
			} catch (Exception $e) {
				echo '<html><head></head><body>Something went wrong. Please send the error message to the site admin so we can get this figured out!';
				echo '<div>' . esc_html($e->getMessage()) . '</div>';
				echo '<div>' . esc_html($e->getTraceAsString()) . '</div></body></html>';
			}
			exit;
		}
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="Load raw PDF file for preview (workaround if the direct URL doesn't work)">
	public static function load_raw_pdf_callback() {
		if (isset($_REQUEST['path'])) {
			try {
				$path = $_REQUEST['path'];
				set_time_limit(0);

				header('Content-Type: application/pdf');
				header('Content-Disposition: inline; '. urlencode($path));
				header('Cache-Control: private, max-age=0, must-revalidate');
				header('Pragma: public');
				$dir = self::get_certificate_folder();
				$file_path = trailingslashit($dir) . $path;
				echo file_get_contents($file_path);
			} catch (Exception $ex) {

			}
			exit;
		}
	}
	// </editor-fold>
}