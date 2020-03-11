<?php
if (!class_exists('ProgressAllySocialShare')) {
	class ProgressAllySocialShare {
		const REDIRECTOR_PARAM = 'ambitionally_redirect';
		const REDIRECT_SITE = 'http://redirect.ambitionally.com/';

		private static $DEFAULT_SOCIAL_SHARE_BUTTON = array('facebook' => '<span class="progressally-facebook-share">Share on Facebook</span>',
			'twitter' => '<span class="progressally-twitter-share">Share on Twitter</span>',
			'gplus' => '<span class="progressally-gplus-share">Share on Google+</span>',
			'pinterest' => '<span class="progressally-pinterest-share">Share on Pinterest</span>',
			'email' => '<span class="progressally-email-share">Share via Email</span>',
			);
		public static function add_shortcodes() {
			add_shortcode( 'progressally_facebook_share', array(__CLASS__, 'shortcode_progressally_facebook_share'));
			add_shortcode( 'progressally_social_share', array(__CLASS__, 'shortcode_progressally_facebook_share'));
		}
		private static function generate_social_share_link($post_id, $media_type, $text, $image, $url) {
			$base_link = '';
			$params = array();
			$text = rawurlencode($text);
			$image = rawurlencode($image);
			$url = rawurlencode($url);
			if ($media_type === 'facebook') {
				$base_link = 'https://www.facebook.com/dialog/feed';
				$user_id = ProgressAllyUserProgress::get_user_id();
				$redirect = ProgressAllySocialShareAutomation::generate_facebook_redirect_link($post_id, $user_id);

				// the redirection is necessary because Facebook can only redirect to the specific domain
				$final_redirect_url = add_query_arg(self::REDIRECTOR_PARAM, urlencode($redirect), self::REDIRECT_SITE);
				$params = array('app_id' => '872575766124641', 'display' => 'page', 'caption' => rawurlencode('via ProgressAlly'), 'redirect_uri' => urlencode($final_redirect_url));
				if (!empty($text)) {
					$params['name'] = $text;
				}
				if (!empty($image)) {
					$params['picture'] = $image;
				}
				if (!empty($url)) {
					$params['link'] = $url;
				}
			} elseif ($media_type === 'twitter') {
				$base_link = 'https://twitter.com/share';
				if (!empty($url)) {
					$params['url'] = $url;
				}
				if (!empty($text)) {
					$params['text'] = $text;
				}
			} elseif ($media_type === 'gplus') {
				$base_link = 'https://plus.google.com/share';
				if (!empty($url)) {
					$params['url'] = $url;
				}
			} elseif ($media_type === 'pinterest') {
				$base_link = 'https://pinterest.com/pin/create/button/';
				if (!empty($url)) {
					$params['url'] = $url;
				}
				if (!empty($image)) {
					$params['media'] = $image;
				}
				if (!empty($text)) {
					$params['description'] = $text;
				}
			} elseif ($media_type === 'email') {
				$base_link = 'mailto:';
				if (!empty($text)) {
					$params['subject'] = $text;
				}
				if (!empty($url)) {
					$params['body'] = $text . '%0A%0A' . $url;
				}
			}
			$link = add_query_arg($params, $base_link);
			return $link;
		}
		public static function shortcode_progressally_facebook_share($atts, $content = null) {
			extract( shortcode_atts( array(
				'type' => 'facebook',
				'post_id' => '',
				'share_id' => '1',
			), $atts, 'progressally_social_share' ) );
			$type = strtolower($type);
			if (!isset(self::$DEFAULT_SOCIAL_SHARE_BUTTON[$type])) {
				return false;
			}
			if (empty($content)) {
				$content = self::$DEFAULT_SOCIAL_SHARE_BUTTON[$type];
			}
			$post_id = intval($post_id);
			$post_id = $post_id <= 0 ? get_the_ID(): $post_id;
			$post_meta = ProgressAllyTaskDefinition::get_post_progress_meta($post_id);

			$share_id = intval($share_id);
			if (!isset($post_meta['social-sharing']['shares'][$share_id])) {
				return false;
			}
			$share_settings = $post_meta['social-sharing']['shares'][$share_id];
			$link = self::generate_social_share_link($post_id, $type, $share_settings['sharing-text'], $share_settings['sharing-image'], $share_settings['sharing-url']);
			return '<a target="_blank" href="'. esc_attr($link) . '">' . do_shortcode($content) . '</a>';
		}
	}
}