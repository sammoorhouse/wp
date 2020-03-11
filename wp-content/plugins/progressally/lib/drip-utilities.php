<?php

class ProgressAllyDripUtilities {
	// <editor-fold defaultstate="collapsed" desc="API core functions">
	const URL_ENDPOINT = 'https://api.getdrip.com/v2/';
	private static function api_core($account_id, $api_token, $method, $action, $data = false) {
		$url = self::URL_ENDPOINT . $account_id . '/' . $action;
		$auth = '';
		if (false !==  $api_token) {
			$auth =  base64_encode($api_token . ':');
		}
		
		$arg = array('method' => $method,
			'redirection' => 5,
			'timeout' => 70,
			'headers' => array('Authorization' => "Basic $auth"));
		if (is_array($data)) {
			$arg['body'] = json_encode($data);
			$arg['headers']['Content-Type'] = 'application/vnd.api+json';
		}

		return wp_remote_post($url, $arg);
	}

	private static function api_with_response_data($account_id, $api_token, $method, $action, $data = false) {
		$response = self::api_core($account_id, $api_token, $method, $action, $data);
		if (self::validate_response($response)) {
			$body = json_decode($response['body'], true);
			return $body;
		} else {
			return null;
		}
	}
	private static function validate_response($response) {
		$response_code = wp_remote_retrieve_response_code($response);
		if ( !(200 == $response_code || 201 == $response_code) ) {
			$response_message = wp_remote_retrieve_response_message($response);
			if ($response_code === 401) { // Invalid API token
				$error_message = 'ERROR ' . $response_code . ': ' . $response_message . ' Please validate your API token.';
			} elseif ($response_code === 404) { // Invalid Account ID
				$error_message = 'ERROR ' . $response_code . ': ' . $response_message . ' Please validate your Account ID.';
			} elseif ($response_code === 500) { // Server internal error
				$error_message = 'ERROR ' . $response_code . ': ' . $response_message . ' This is likely an Drip outage. Please contact Drip customer support.';
			} elseif (!empty($response_message)) {
				$error_message = 'ERROR ' . $response_code . ': ' . $response_message;
			} else {
				$error_message = 'ERROR ' . $response_code . ': Unknown error';
			}
			throw new Exception($error_message);
		}
		if (empty($response['body'])) {
			throw new Exception('Nothing was returned. Do you have a connection to the Drip server?');
		}
		return true;
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="test account info">
	public static function test_client($account_id, $api_token) {
		$response = self::api_core($account_id, $api_token, 'GET', 'forms');
		self::validate_response($response);
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="tag list">
	CONST DRIP_TAG_OPTION_KEY = 'progressally_drip_tag_map';
	public static function refresh_tag_mapping($automation_settings) {
		$tag_mapping = self::api_with_response_data($automation_settings['drip-account'], $automation_settings['drip-key'], 'GET', 'tags');
		$result = array();
		if (is_array($tag_mapping) && isset($tag_mapping['tags']) && is_array($tag_mapping['tags'])) {
			foreach ($tag_mapping['tags'] as $tag) {
				$result []= array('Id' => $tag, 'TagName' => $tag);
			}
		}
		if (!add_option(self::DRIP_TAG_OPTION_KEY, $result, '', 'no')) {
			update_option(self::DRIP_TAG_OPTION_KEY, $result);
		}
		self::$cached_all_tags = $result;
		return $result;
	}
	private static function get_database_tag_mapping() {
		return get_option(self::DRIP_TAG_OPTION_KEY, false);
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
			$all_tags = self::get_all_tags($automation_settings);
			$tag_name = false;
			foreach ($all_tags as $tag) {
				if ($tag_id == $tag['Id']) {
					$tag_name = $tag['TagName'];
					break;
				}
			}
			$result = false;
			if (false !== $tag_name) {
				$result = self::api_with_response_data($automation_settings['drip-account'], $automation_settings['drip-key'], 'POST', 'tags',
					array('tags' => array(array('email' => $userinfo->user_email,
										'tag' => $tag_name))));
			}
			return $result;
		}
		return false;
	}
	// </editor-fold>
}