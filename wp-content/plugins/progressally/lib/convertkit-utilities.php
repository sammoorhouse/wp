<?php

class ProgressAllyConvertkitUtilities {
	// <editor-fold defaultstate="collapsed" desc="API core functions">
	const URL_ENDPOINT = 'https://api.convertkit.com/v3/';
	private static function api_core($api_key, $api_secret, $method, $action, $data = false) {
		$url = self::URL_ENDPOINT . $action;
		if (false !==  $api_key) {
			$url = add_query_arg(array(
				'api_key' => $api_key), $url);
		} elseif (false !==  $api_secret) {
			$url = add_query_arg(array(
				'api_secret' => $api_secret), $url);
		}
		$arg = array('method' => $method,
			'redirection' => 5,
			'timeout' => 70);
		if (is_array($data)) {
			$arg['body'] = $data;
		}

		return wp_remote_post($url, $arg);
	}

	private static function api_with_response_data($api_key, $api_secret, $method, $action, $data = false) {
		$response = self::api_core($api_key, $api_secret, $method, $action, $data);
		if (self::validate_response($response)) {
			$body = json_decode($response['body'], true);
			return $body;
		} else {
			return null;
		}
	}
	private static function validate_response($response) {
		$response_code = wp_remote_retrieve_response_code($response);
		if ( 200 != $response_code ) {
			$response_message = wp_remote_retrieve_response_message($response);
			if ($response_code === 403) { // Invalid App ID & Api key
				$error_message = 'ERROR ' . $response_code . ': ' . $response_message . ' Please validate your API Key and API Secret.';
			} elseif ($response_code === 500) { // Server internal error
				$error_message = 'ERROR ' . $response_code . ': ' . $response_message . ' This is likely an ConvertKit outage. Please contact ConvertKit customer support.';
			} elseif (!empty($response_message)) {
				$error_message = 'ERROR ' . $response_code . ': ' . $response_message;
			} else {
				$error_message = 'ERROR ' . $response_code . ': Unknown error';
			}
			throw new Exception($error_message);
		}
		if (empty($response['body'])) {
			throw new Exception('Nothing was returned. Do you have a connection to the ConvertKit server?');
		}
		return true;
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="test account info">
	public static function test_client($api_key, $api_secret) {
		$result = self::api_with_response_data($api_key, false, 'GET', 'forms');
		if (!isset($result['forms'])) {
			throw new Exception('Invalid API Key. Cannot connect to ConvertKit server');
		}
		$result = self::api_with_response_data(false, $api_secret, 'GET', 'forms');
		if (!isset($result['forms'])) {
			throw new Exception('Invalid API Key. Cannot connect to ConvertKit server');
		}
		return $result;
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="tag list">
	CONST CONVERTKIT_TAG_OPTION_KEY = 'progressally_ck_tag_map';
	public static function refresh_tag_mapping($automation_settings) {
		$tag_mapping = self::api_with_response_data($automation_settings['convertkit-key'], $automation_settings['convertkit-secret'], 'GET', 'tags');
		$result = array();
		if (is_array($tag_mapping) && isset($tag_mapping['tags']) && is_array($tag_mapping['tags'])) {
			foreach ($tag_mapping['tags'] as $tag) {
				$result []= array('Id' => $tag['id'], 'TagName' => $tag['name']);
			}
		}
		if (!add_option(self::CONVERTKIT_TAG_OPTION_KEY, $result, '', 'no')) {
			update_option(self::CONVERTKIT_TAG_OPTION_KEY, $result);
		}
		self::$cached_all_tags = $result;
		return $result;
	}
	private static function get_database_tag_mapping() {
		return get_option(self::CONVERTKIT_TAG_OPTION_KEY, false);
	}
	private static $cached_all_tags = null;
	public static function get_all_tags($automation_settings) {
		if (null === self::$cached_all_tags) {
			self::$cached_all_tags = self::get_database_tag_mapping();
			if (!is_array(self::$cached_all_tags)) {
				self::$cached_all_tags = self::refresh_tag_mapping($automation_settings);
			}
		}
		return self::$cached_all_tags;
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="add tag to contact">
	public static function add_contact_tag($wp_user_id, $tag_id, $automation_settings) {
		$userinfo = get_userdata($wp_user_id);
		if ($userinfo && !empty($userinfo->user_email)) {
			$result = self::api_with_response_data(false, false, 'POST', 'tags/' . $tag_id . '/subscribe',
				array('api_key' => $automation_settings['convertkit-key'],
					'email' => $userinfo->user_email,
					'name' => $userinfo->first_name));
			return $result;
		}
		return false;
	}
	// </editor-fold>
}