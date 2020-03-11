<?php
if (!class_exists('ProgressAllySocialSharing')) {
	class ProgressAllySocialSharing {
		private static $default_per_share_settings = array('checked-is-open' => 'no', 'name' => 'Social Sharing',
															'sharing-text' => '', 'sharing-image' => '', 'sharing-url' => '');
		private static $default_shares_settings = array('max-shares' => 0, 'shares' => array());
		
		// <editor-fold defaultstate="collapsed" desc="Generate backend settings display">
		public static function show_social_sharing_meta_box($settings) {
			$max_share_num = $settings['social-sharing']['max-shares'];
			$sharings_code = '';
			foreach ($settings['social-sharing']['shares'] as $id => $share_settings) {
				$sharings_code .= self::generate_social_sharing_code($id, $share_settings);
			}
			
			$display_code = file_get_contents(dirname(__FILE__) . '/social-sharing-display.php');
			$display_code = str_replace('{{sharings-section}}', $sharings_code, $display_code);
			$display_code = str_replace('{{max-share-num}}', $max_share_num, $display_code);
			return $display_code;
		}
		private static $cached_social_sharing_template= null;
		private static function get_social_sharing_template() {
			if (self::$cached_social_sharing_template === null) {
				self::$cached_social_sharing_template = file_get_contents(dirname(__FILE__) . '/social-sharing-template.php');
			}
			return self::$cached_social_sharing_template;
		}
		private static function generate_social_sharing_code($id, $settings) {
			$code = self::get_social_sharing_template();
			$code = ProgressAllyBackendShared::replace_real_values($code, $settings, '');
			$code = ProgressAllyBackendShared::replace_all_toggle($code, $settings);
			$code = str_replace('{{open-class}}', $settings['checked-is-open'] === 'yes' ? 'progressally-accordion-opened' : '', $code);
			$code = str_replace('{{share-id}}', $id, $code);
			return $code;
		}
		public static function generate_default_social_sharing_code() {
			$code = self::generate_social_sharing_code('--share-id--', self::$default_per_share_settings);
			return $code;
		}
		// </editor-fold>
		
		public static function merge_default_settings($settings) {
			if (!isset($settings['social-sharing'])) {
				// Backward compatibility
				$settings['social-sharing'] = self::$default_shares_settings;
				if (isset($settings['sharing-url'])) {
					$old_share_setting = self::$default_per_share_settings;
					$old_share_setting['sharing-text'] = $settings['sharing-text'];
					$old_share_setting['sharing-image'] = $settings['sharing-image'];
					$old_share_setting['sharing-url'] = $settings['sharing-url'];
					
					$settings['social-sharing']['max-shares'] = 1;
					$settings['social-sharing']['shares'][1] = $old_share_setting;
				}
			} else {
				if (!isset($settings['social-sharing']['shares'])) {
					$settings['social-sharing']['shares'] = array();
				}
				foreach ($settings['social-sharing']['shares'] as $id => $share_setting) {
					$settings['social-sharing']['shares'][$id] = wp_parse_args($share_setting, self::$default_per_share_settings);
				}
			}
			return $settings;
		}
	}
}