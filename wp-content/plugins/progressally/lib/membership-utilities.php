<?php

class ProgressAllyMembershipUtilities {
	// <editor-fold defaultstate="collapsed" desc="API setup & validation">
	public static function add_actions() {
		if (is_admin()) {
			add_action( 'wp_ajax_progressally_refresh_tag', array(__CLASS__, 'ajax_refresh_tag_selection'));
		}
		// schedule daily tag and custom field refresh
		add_action('progressally_refresh_selection_event', array(__CLASS__, 'refresh_selection_periodically'));
		if (!wp_next_scheduled('progressally_refresh_selection_event')) {
			wp_schedule_event(current_time('timestamp'), 'daily', 'progressally_refresh_selection_event');
		}
	}
	public static function refresh_selection_periodically() {
		self::refresh_tag_mapping();
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="AJAX callbacks">
	public static function ajax_refresh_tag_selection() {
		try {
			$nonce = $_POST['nonce'];

			if (!wp_verify_nonce($nonce, 'progressally-update-nonce')) {
				throw new Exception("Unable to validate request identify. Please refresh the page and make sure you are logged in as an administrator.");
			}
			$all_tags = self::refresh_tag_mapping();
			$all_tag_selection = '<option value=""></option>';
			if (is_array($all_tags)) {
				foreach ($all_tags as $tag) {
					$all_tag_selection .= '<option value="' . esc_attr($tag['Id']) . '">' . esc_attr($tag['TagName']) . '</option>';
				}
			}
			echo json_encode(array('status' => 'success', 'tags' => $all_tag_selection));
		} catch (Exception $ex) {
			echo json_encode(array('status' => 'error', 'message' => $ex->getMessage()));
		}
		die();
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="API setup & validation">
	public static function api_is_set() {
		$automation_settings = ProgressAllySettingAutomation::get_automation_settings();
		if ($automation_settings['select-crm'] === 'accessally') {
			return true;
		} elseif ($automation_settings['select-crm'] === 'active-campaign') {
			if (!empty($automation_settings['active-campaign-url']) && !empty($automation_settings['active-campaign-key'])) {
				return true;
			}
		} elseif ($automation_settings['select-crm'] === 'convertkit') {
			if (!empty($automation_settings['convertkit-key']) && !empty($automation_settings['convertkit-secret'])) {
				return true;
			}
		} elseif ($automation_settings['select-crm'] === 'ontraport') {
			if (!empty($automation_settings['ontraport-app']) && !empty($automation_settings['ontraport-key'])) {
				return true;
			}
		} elseif ($automation_settings['select-crm'] === 'infusionsoft') {
			if (!empty($automation_settings['infusionsoft-app']) && !empty($automation_settings['infusionsoft-key'])) {
				return true;
			}
		} elseif ($automation_settings['select-crm'] === 'drip') {
			if (!empty($automation_settings['drip-account']) && !empty($automation_settings['drip-key'])) {
				return true;
			}
		}
		return false;
	}

	public static function test_client($automation_settings) {
		if ($automation_settings['select-crm'] === 'accessally') {
		} elseif ($automation_settings['select-crm'] === 'active-campaign') {
			try {
				ProgressAllyActiveCampaignUtilities::test_client($automation_settings['active-campaign-url'], $automation_settings['active-campaign-key']);
			} catch (Exception $ex) {
				$message = $ex->getMessage();
				add_settings_error('progressally_general', 'progressally-active-campaign', $message);
				add_settings_error('progressally_general', 'progressally-active-campaign-key', 'Check that the API Access URL and Key are copied from your Active Campaign account info.', 'error');
			}
		} elseif ($automation_settings['select-crm'] === 'convertkit') {
			try {
				ProgressAllyConvertkitUtilities::test_client($automation_settings['convertkit-key'], $automation_settings['convertkit-secret']);
			} catch (Exception $ex) {
				$message = $ex->getMessage();
				add_settings_error('progressally_general', 'progressally-convertkit', $message);
				add_settings_error('progressally_general', 'progressally-convertkit-key', 'Check that the API Key and Secret are copied from your ConvertKit account settings.', 'error');
			}
		} elseif ($automation_settings['select-crm'] === 'ontraport') {
			try {
				ProgressAllyOntraportUtilities::test_client($automation_settings);
			} catch (Exception $ex) {
				$message = $ex->getMessage();
				add_settings_error('progressally_general', 'progressally-ontraport', $message);
				add_settings_error('progressally_general', 'progressally-ontraport-key', 'Check that the App ID and API key are copied from the Ontraport Page.', 'error');
			}
		} elseif ($automation_settings['select-crm'] === 'infusionsoft') {
			try {
				ProgressAllyInfusionUtilities::test_client($automation_settings);
			} catch (Exception $ex) {
				$message = $ex->getMessage();
				add_settings_error('progressally_general', 'progressally-infusionsoft', $message);
				add_settings_error('progressally_general', 'progressally-infusionsoft-key', 'Check that the Application ID and API key are copied from your Infusionsoft account.', 'error');
			}
		} elseif ($automation_settings['select-crm'] === 'drip') {
			try {
				ProgressAllyDripUtilities::test_client($automation_settings['drip-account'], $automation_settings['drip-key']);
			} catch (Exception $ex) {
				$message = $ex->getMessage();
				add_settings_error('progressally_general', 'progressally-dirp', $message);
				add_settings_error('progressally_general', 'progressally-drip-key', 'Check that the Account ID and API token are copied from your Drip account.', 'error');
			}
		}
	}

	public static function refresh_client() {
		$automation_settings = ProgressAllySettingAutomation::get_automation_settings();
		if ($automation_settings['select-crm'] === 'accessally') {
		} elseif ($automation_settings['select-crm'] === 'active-campaign') {
			try {
				ProgressAllyActiveCampaignUtilities::refresh_tag_mapping($automation_settings);
			} catch (Exception $ex) {
				$message = $ex->getMessage();
				add_settings_error('progressally_general', 'progressally-active-campaign', $message);
				add_settings_error('progressally_general', 'progressally-active-campaign-key', 'Check that the API Access URL and Key are copied from your Active Campaign account info.', 'error');
			}
		} elseif ($automation_settings['select-crm'] === 'convertkit') {
			try {
				ProgressAllyConvertkitUtilities::refresh_tag_mapping($automation_settings);
			} catch (Exception $ex) {
				$message = $ex->getMessage();
				add_settings_error('progressally_general', 'progressally-convertkit', $message);
				add_settings_error('progressally_general', 'progressally-convertkit-key', 'Check that the API Key and Secret are copied from your ConvertKit account info.', 'error');
			}
		} elseif ($automation_settings['select-crm'] === 'ontraport') {
			try {
				ProgressAllyOntraportUtilities::refresh_tag_mapping($automation_settings);
			} catch (Exception $ex) {
				$message = $ex->getMessage();
				add_settings_error('progressally_general', 'progressally-ontraport', $message);
				add_settings_error('progressally_general', 'progressally-ontraport-key', 'Check that the App ID and API key are copied from the Ontraport Page.', 'error');
			}
		} elseif ($automation_settings['select-crm'] === 'infusionsoft') {
			try {
				ProgressAllyInfusionUtilities::refresh_tag_mapping($automation_settings);
			} catch (Exception $ex) {
				$message = $ex->getMessage();
				add_settings_error('progressally_general', 'progressally-infusionsoft', $message);
				add_settings_error('progressally_general', 'progressally-infusionsoft-key', 'Check that the Application ID and API key are copied from your Infusionsoft account.', 'error');
			}
		} elseif ($automation_settings['select-crm'] === 'drip') {
			try {
				ProgressAllyDripUtilities::refresh_tag_mapping($automation_settings);
			} catch (Exception $ex) {
				$message = $ex->getMessage();
				add_settings_error('progressally_general', 'progressally-drip', $message);
				add_settings_error('progressally_general', 'progressally-drip-key', 'Check that the Account ID and API token are copied from your Drip account.', 'error');
			}
		}
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="tag list">
	public static function refresh_tag_mapping() {
		try {
			$automation_settings = ProgressAllySettingAutomation::get_automation_settings();
			if ($automation_settings['select-crm'] === 'accessally') {
				if (class_exists('AccessAllySettingLicense') && AccessAllySettingLicense::$accessally_enabled && method_exists('AccessAllyAPI', 'refresh_tag_mapping')) {
					return AccessAllyAPI::refresh_tag_mapping();
				}
			} elseif ($automation_settings['select-crm'] === 'active-campaign') {
				return ProgressAllyActiveCampaignUtilities::refresh_tag_mapping($automation_settings);
			} elseif ($automation_settings['select-crm'] === 'convertkit') {
				return ProgressAllyConvertkitUtilities::refresh_tag_mapping($automation_settings);
			} elseif ($automation_settings['select-crm'] === 'ontraport') {
				return ProgressAllyOntraportUtilities::refresh_tag_mapping($automation_settings);
			} elseif ($automation_settings['select-crm'] === 'infusionsoft') {
				return ProgressAllyInfusionUtilities::refresh_tag_mapping($automation_settings);
			} elseif ($automation_settings['select-crm'] === 'drip') {
				return ProgressAllyDripUtilities::refresh_tag_mapping($automation_settings);
			}
		} catch (Exception $ex) {
		}
		return false;
	}
	public static function get_all_tags() {
		try {
			$automation_settings = ProgressAllySettingAutomation::get_automation_settings();
			if ($automation_settings['select-crm'] === 'accessally') {
				if (class_exists('AccessAllySettingLicense') && AccessAllySettingLicense::$accessally_enabled && method_exists('AccessAllyAPI', 'get_all_tags')) {
					return AccessAllyAPI::get_all_tags();
				}
			} elseif ($automation_settings['select-crm'] === 'active-campaign') {
				return ProgressAllyActiveCampaignUtilities::get_all_tags($automation_settings);
			} elseif ($automation_settings['select-crm'] === 'convertkit') {
				return ProgressAllyConvertkitUtilities::get_all_tags($automation_settings);
			} elseif ($automation_settings['select-crm'] === 'ontraport') {
				return ProgressAllyOntraportUtilities::get_all_tags($automation_settings);
			} elseif ($automation_settings['select-crm'] === 'infusionsoft') {
				return ProgressAllyInfusionUtilities::get_all_tags($automation_settings);
			} elseif ($automation_settings['select-crm'] === 'drip') {
				return ProgressAllyDripUtilities::get_all_tags($automation_settings);
			}
		} catch (Exception $ex) {
		}
		return null;
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="field list">
	public static function get_all_fields() {
		try {
			$automation_settings = ProgressAllySettingAutomation::get_automation_settings();
			if ($automation_settings['select-crm'] === 'accessally') {
				if (class_exists('AccessAllySettingLicense') && AccessAllySettingLicense::$accessally_enabled &&
					method_exists('AccessAllyAPI', 'get_crm_custom_field_list')) {
					$text_fields = AccessAllyAPI::get_crm_custom_field_list('Text');
					$integer_fields = AccessAllyAPI::get_crm_custom_field_list('Integer');
					$unique_field_ids = array();
					$results = $text_fields;
					foreach ($text_fields as $field_config) {
						$unique_field_ids[$field_config['Id']] = $field_config['Label'];
					}
					foreach ($integer_fields as $field_config) {
						if (!isset($unique_field_ids[$field_config['Id']])) {
							$unique_field_ids[$field_config['Id']] = $field_config['Label'];
							$results []= $field_config;
						}
					}
					return $results;
				}
			}
		} catch (Exception $ex) {
		}
		return null;
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="add tag to contact">
	public static function add_contact_tag($wp_user_id, $tag_id) {
		$automation_settings = ProgressAllySettingAutomation::get_automation_settings();
		if ($automation_settings['select-crm'] === 'accessally') {
			if (class_exists('AccessAllySettingLicense') && AccessAllySettingLicense::$accessally_enabled && method_exists('AccessAllyAPI', 'add_tag_by_wp_user_id')) {
				$result = AccessAllyAPI::add_tag_by_wp_user_id($tag_id, $wp_user_id);
				if (method_exists('AccessAllyAPI', 'sync_user_info_from_crm')) {
					AccessAllyAPI::sync_user_info_from_crm($wp_user_id);
				}
				return $result['status'];
			}
		} elseif ($automation_settings['select-crm'] === 'active-campaign') {
			$result = ProgressAllyActiveCampaignUtilities::add_contact_tag($wp_user_id, $tag_id, $automation_settings);
			return $result['result_code'];
		} elseif ($automation_settings['select-crm'] === 'convertkit') {
			$result = ProgressAllyConvertkitUtilities::add_contact_tag($wp_user_id, $tag_id, $automation_settings);
			return $result;
		} elseif ($automation_settings['select-crm'] === 'ontraport') {
			$result = ProgressAllyOntraportUtilities::add_contact_tag($wp_user_id, $tag_id, $automation_settings);
			return $result;
		} elseif ($automation_settings['select-crm'] === 'infusionsoft') {
			$result = ProgressAllyInfusionUtilities::add_contact_tag($wp_user_id, $tag_id, $automation_settings);
			return $result;
		} elseif ($automation_settings['select-crm'] === 'drip') {
			$result = ProgressAllyDripUtilities::add_contact_tag($wp_user_id, $tag_id, $automation_settings);
			return $result;
		}
		return false;
	}
	public static function add_contact_tags($wp_user_id, $tag_ids) {
		$automation_settings = ProgressAllySettingAutomation::get_automation_settings();
		if ($automation_settings['select-crm'] === 'accessally') {
			if (class_exists('AccessAllySettingLicense') && AccessAllySettingLicense::$accessally_enabled && method_exists('AccessAllyAPI', 'add_tag_by_wp_user_id')) {
				foreach ($tag_ids as $tag_id) {
					AccessAllyAPI::add_tag_by_wp_user_id($tag_id, $wp_user_id);
				}
				if (method_exists('AccessAllyAPI', 'sync_user_info_from_crm')) {
					AccessAllyAPI::sync_user_info_from_crm($wp_user_id);
				}
			}
		} elseif ($automation_settings['select-crm'] === 'active-campaign') {
			foreach ($tag_ids as $tag_id) {
				ProgressAllyActiveCampaignUtilities::add_contact_tag($wp_user_id, $tag_id, $automation_settings);
			}
		} elseif ($automation_settings['select-crm'] === 'convertkit') {
			foreach ($tag_ids as $tag_id) {
				$result = ProgressAllyConvertkitUtilities::add_contact_tag($wp_user_id, $tag_id, $automation_settings);
			}
		} elseif ($automation_settings['select-crm'] === 'ontraport') {
			foreach ($tag_ids as $tag_id) {
				$result = ProgressAllyOntraportUtilities::add_contact_tag($wp_user_id, $tag_id, $automation_settings);
			}
		} elseif ($automation_settings['select-crm'] === 'infusionsoft') {
			foreach ($tag_ids as $tag_id) {
				$result = ProgressAllyInfusionUtilities::add_contact_tag($wp_user_id, $tag_id, $automation_settings);
			}
		} elseif ($automation_settings['select-crm'] === 'drip') {
			foreach ($tag_ids as $tag_id) {
				$result = ProgressAllyDripUtilities::add_contact_tag($wp_user_id, $tag_id, $automation_settings);
			}
		}
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="add tag to contact">
	public static function update_contact_data($wp_user_id, $field_data_to_update) {
		$automation_settings = ProgressAllySettingAutomation::get_automation_settings();
		if ($automation_settings['select-crm'] === 'accessally') {
			if (class_exists('AccessAllySettingLicense') && AccessAllySettingLicense::$accessally_enabled &&
				method_exists('AccessAllyAPI', 'set_crm_contact_data') && method_exists('AccessAllyAPI', 'get_crm_contact_id')) {

				$contact_id = AccessAllyAPI::get_crm_contact_id($wp_user_id);
				if (!empty($contact_id)) {
					AccessAllyAPI::set_crm_contact_data($contact_id, $field_data_to_update);

					if (method_exists('AccessAllyAPI', 'sync_user_info_from_crm')) {
						AccessAllyAPI::sync_user_info_from_crm($wp_user_id);
					}
				}
			}
		}
	}
	// </editor-fold>
}