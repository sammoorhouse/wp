<?php

class ProgressAllyInfusionUtilities {
	// <editor-fold defaultstate="collapsed" desc="API core functions">
	private static $cached_client = null;
	public static function get_client($automation_settings, $force = false) {
		if ($force || null == self::$cached_client) {
			require_once("infusionsoft-SDK/isdk.php");
			$client = new ProgressAllyiSDK;
			if (!$client->cfgCon($automation_settings['infusionsoft-app'], $automation_settings['infusionsoft-key'])) {
				throw new Exception('Cannot connect to Infusionsoft. Please check API settings');
			}
			self::$cached_client = $client;
		}

		return self::$cached_client;
	}
	private static function get_all_query_values($client, $table, $query, $returnFields, $order_by = false, $ascending = true) {
		$page = 0;
		if ($order_by === false) {
			$result = $client->dsQuery($table, 1000, $page, $query, $returnFields);
		} else {
			$result = $client->dsQueryOrderBy($table, 1000, $page, $query, $returnFields, $order_by, $ascending);
		}
		++$page;

		$final = $result;

		while(count($result) == 1000) {
			if ($order_by === false) {
				$result = $client->dsQuery($table, 1000, $page, $query, $returnFields);
			} else {
				$result = $client->dsQueryOrderBy($table, 1000, $page, $query, $returnFields, $order_by, $ascending);
			}

			++$page;

			$final = array_merge($final, $result);
		}

		return $final;
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="test account info">
	public static function test_client($automation_settings) {
		require_once("infusionsoft-SDK/isdk.php");
		$client = new ProgressAllyiSDK;
		if (!$client->cfgCon($automation_settings['infusionsoft-app'], $automation_settings['infusionsoft-key'])) {
			throw new Exception('Cannot connect to Infusionsoft. Please check API settings');
		}

		return $client;
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="tag list">
	CONST INFUSIONSOFT_TAG_OPTION_KEY = 'progressally_ifs_tag_map';
	private static $cached_all_tags = null;
	public static function refresh_tag_mapping($automation_settings) {
		$client = self::get_client($automation_settings);

		$returnFields = array('Id', 'GroupName', 'GroupCategoryId');
		$query = array('Id' => '%');
		$tag_mapping = self::get_all_query_values($client, 'ContactGroup', $query, $returnFields, 'GroupName');
		$result = array();
		foreach ($tag_mapping as $tag) {
			if (isset($tag['GroupCategoryId'])) {
				$result []= array('Id' => $tag['Id'], 'TagName' => $tag['GroupName'], 'CategoryId' => $tag['GroupCategoryId']);
			} else {
				$result []= array('Id' => $tag['Id'], 'TagName' => $tag['GroupName']);
			}
		}
		if (!add_option(self::INFUSIONSOFT_TAG_OPTION_KEY, $result, '', 'no')) {
			update_option(self::INFUSIONSOFT_TAG_OPTION_KEY, $result);
		}
		self::$cached_all_tags = $result;
		return $result;
	}
	private static function get_database_tag_mapping() {
		return get_option(self::INFUSIONSOFT_TAG_OPTION_KEY, false);
	}
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
		$client = self::get_client($automation_settings);

		$returnFields = array('Id', 'FirstName', 'LastName');
		$query = array('Email' => $email );
		$conDat = $client->findByEmail($email, $returnFields);

		return $conDat;
	}
	// </editor-fold>

	// <editor-fold defaultstate="collapsed" desc="add tag to contact">
	public static function add_contact_tag($wp_user_id, $tag_id, $automation_settings) {
		$result = false;
		$userinfo = get_userdata($wp_user_id);
		if ($userinfo && !empty($userinfo->user_email)) {
			$contact_info = self::get_contact_by_email($userinfo->user_email, $automation_settings);
			if (is_array($contact_info)) {
				$client = self::get_client($automation_settings);
				foreach ($contact_info as $contact) {
					$result &= $client->grpAssign($contact['Id'], $tag_id);
				}
			}
		}

		return $result;
	}
	// </editor-fold>
}
