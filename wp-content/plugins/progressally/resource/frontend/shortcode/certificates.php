<?php
if (!class_exists('ProgressAllyCertificatesShortcode')) {
	class ProgressAllyCertificatesShortcode {
		public static function add_shortcodes() {
			add_shortcode( 'progressally_certificate', array(__CLASS__, 'shortcode_certificate'));
		}
		public static function shortcode_certificate($atts) {
			extract(shortcode_atts(array(
				'post_id' => '',
				'certificate_id' => '',
				'text' => 'Download Certificate',
				'class' => '',
				'link' => 'no'
			), $atts, 'progressally_certificate' ) );

			$user_id = ProgressAllyUserProgress::get_user_id();
			$post_id = intval($post_id);
			$post_id = $post_id <= 0 ? get_the_ID(): $post_id;
			$certificate_meta = ProgressAllyCertificate::get_post_certificate_meta($post_id);
			if (!isset($certificate_meta['cert'][$certificate_id])) {
				return 'Undefined certificate';
			}
			$nonce = wp_create_nonce('progressally-certificate-' . $post_id . '-' . $certificate_id);
			$url = add_query_arg(array('certificate-dl-nonce' => $nonce,
									'post-id' => $post_id,
									'cert-id' => $certificate_id,
									'action' => 'progressally_download_certificate'
								), admin_url('admin-ajax.php'));

			if ($link === 'raw') {
				return $url;
			} elseif ($link === 'yes') {
				return esc_html($url);
			}
			$code = '<a class="progressally-certificate-download ' . $class . '" href="' . esc_attr($url) . '" target="_blank">' . esc_html($text) . '</a>';
			return $code;
		}
		public static function preview_certificate_button() {
			$preview_code = '<span class="progressally-certificate-download">Download Certificate</span>';
			return $preview_code;
		}
	}
}