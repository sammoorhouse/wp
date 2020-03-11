<?php

class ProgressAllyOntraportUtilities {
	// <editor-fold defaultstate="collapsed" desc="API core functions">
	const ONTRAPORT_API_URL = 'https://api.ontraport.com/1/';

	private static function api_core($app_id, $api_key, $url, $method, $body = false) {
		$arg = array('method' => $method,
			'timeout' => 70,
			'headers' => array('Api-Appid' => $app_id, 'Api-Key' => $api_key));
		if ($body) {
			$arg['body'] = $body;
		}

		return wp_remote_post($url, $arg);
	}

	private static function api_with_response_data($automation_settings, $url, $method, $body = false) {
		$response = self::api_core($automation_settings['ontraport-app'], $automation_settings['ontraport-key'], $url, $method, $body);
		if (self::validate_response($response)) {
			$body = json_decode($response['body'], true);
			return $body['data'];
		} else {
			return null;
		}
	}

	// by default, only 50 records are returned by the API call, so we will potentially need to make more than 1 call to get all the entries
	private static function api_get_all_data($automation_settings, $url, $body) {
		$body['range'] = 50;
		$body['start'] = 0;
		$final_result = array();
		while (true) {
			$data = self::api_with_response_data($automation_settings, $url, 'GET', $body);
			if (is_array($data)) {
				$final_result = array_merge($final_result, $data);
			} else {
				break;
			}
			if (count($data) < 50) {
				break;
			}
			$body['start'] += 50;
		}
		return $final_result;
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
		return true;
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="test account info">
	public static function test_client($automation_settings) {
		$url = self::ONTRAPORT_API_URL . 'objects/meta?format=byId&objectID=0';
		$response = self::api_core($automation_settings['ontraport-app'], $automation_settings['ontraport-key'], $url, 'GET', false);
		self::validate_response($response);
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="tag list">
	CONST ONTRAPORT_TAG_OPTION_KEY = 'progressally_otp_tag_map';
	public static function refresh_tag_mapping($automation_settings) {
		$url = self::ONTRAPORT_API_URL . 'objects?objectID=14&performAll=true&sort=tag_name&sortDir=asc&listFields=tag_id%2Ctag_name';
		$tag_mapping = self::api_get_all_data($automation_settings, $url, array());
		if (is_array($tag_mapping)) {
			foreach ($tag_mapping as $tag) {
				$result []= array('Id' => $tag['tag_id'], 'TagName' => $tag['tag_name']);
			}
		}
		if (!add_option(self::ONTRAPORT_TAG_OPTION_KEY, $result, '', 'no')) {
			update_option(self::ONTRAPORT_TAG_OPTION_KEY, $result);
		}
		self::$cached_all_tags = $result;
		return $result;
	}
	private static function get_database_tag_mapping() {
		return get_option(self::ONTRAPORT_TAG_OPTION_KEY, false);
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

	// <editor-fold defaultstate="collapsed" desc="search contact by email">
	private static function get_contact_by_email($email, $automation_settings) {
		$url = self::ONTRAPORT_API_URL . "objects?objectID=0&performAll=true&sortDir=asc&condition=" .
			rawurlencode(json_encode(array(
				array(
					'field' => array('field' => 'email'),
					'op' => '=',
					'value' => array('value' => $email)
				)
				)));
		return self::api_with_response_data($automation_settings, $url, 'GET');
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="add tag to contact">
	public static function add_contact_tag($wp_user_id, $tag_id, $automation_settings) {
		$userinfo = get_userdata($wp_user_id);
		if ($userinfo && !empty($userinfo->user_email)) {
			$contact_info = self::get_contact_by_email($userinfo->user_email, $automation_settings);
			$contact_ids = array();
			if (is_array($contact_info)) {
				foreach ($contact_info as $contact) {
					$contact_ids[] = $contact['id'];
				}
			}
			if (!empty($contact_ids)) {
				$url = self::ONTRAPORT_API_URL . 'objects/tag';
				$body = array('objectID' => '0',
					'ids' => implode(',', $contact_ids),
					'add_list' => intval($tag_id));
			}
			return self::api_with_response_data($automation_settings, $url, 'PUT', $body);
		}
		return false;
	}
	// </editor-fold>
}