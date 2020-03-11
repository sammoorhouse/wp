<?php

class ProgressAllyActiveCampaignUtilities {
	// <editor-fold defaultstate="collapsed" desc="API core functions">
	private static function api_core($access_url, $access_key, $method, $action, $data = false) {
		$url = rtrim($access_url, '/ ');
		$url = $url . '/admin/api.php';
		$url = add_query_arg(array(
			'api_key' => $access_key,
			'api_action' => $action,
			'api_output' => 'json'), $url);
		$arg = array('method' => $method,
			'redirection' => 5,
			'timeout' => 70);
		if (is_array($data)) {
			$arg['body'] = $data;
		}

		return wp_remote_post($url, $arg);
	}

	private static function api_with_response_data($access_url, $access_key, $method, $action, $data = false) {
		$response = self::api_core($access_url, $access_key, $method, $action, $data);
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
				$error_message = 'ERROR ' . $response_code . ': ' . $response_message . ' Please validate your App ID and API key.';
			} elseif ($response_code === 500) { // Server internal error
				$error_message = 'ERROR ' . $response_code . ': ' . $response_message . ' This is likely an Ontraport outage. Please contact Ontraportcustomer support.';
			} elseif (!empty($response_message)) {
				$error_message = 'ERROR ' . $response_code . ': ' . $response_message;
			} else {
				$error_message = 'ERROR ' . $response_code . ': Unknown error';
			}
			throw new Exception($error_message);
		}
		if (empty($response['body'])) {
			throw new Exception('Nothing was returned. Do you have a connection to the Active Campaign server?');
		}
		return true;
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="test account info">
	public static function test_client($access_url, $access_key) {
		$result = self::api_with_response_data($access_url, $access_key, 'GET', 'user_me');
		if (!isset($result['result_code']) || $result['result_code'] === 0) {
			throw new Exception($body['result_message']);
		}
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="tag list">
	CONST ACTIVE_CAMPAIGN_TAG_OPTION_KEY = 'progressally_ac_tag_map';
	public static function refresh_tag_mapping($automation_settings) {
		$tag_mapping = self::api_with_response_data($automation_settings['active-campaign-url'], $automation_settings['active-campaign-key'], 'GET', 'tags_list');
		$result = array();
		if (is_array($tag_mapping)) {
			foreach ($tag_mapping as $tag) {
				$result []= array('Id' => $tag['id'], 'TagName' => $tag['name']);
			}
		}
		if (!add_option(self::ACTIVE_CAMPAIGN_TAG_OPTION_KEY, $result, '', 'no')) {
			update_option(self::ACTIVE_CAMPAIGN_TAG_OPTION_KEY, $result);
		}
		self::$cached_all_tags = $result;
		return $result;
	}
	private static function get_database_tag_mapping() {
		return get_option(self::ACTIVE_CAMPAIGN_TAG_OPTION_KEY, false);
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
				$result = self::api_with_response_data($automation_settings['active-campaign-url'], $automation_settings['active-campaign-key'], 'POST', 'contact_tag_add',
					array('email' => $userinfo->user_email,
						'tags' => $tag_name));
			}
			return $result;
		}
		return false;
	}
	// </editor-fold>
}