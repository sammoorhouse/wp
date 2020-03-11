<?php

if (!defined('ABSPATH')) exit;
if (!class_exists('BVMonitCallback')) :

class BVMonitCallback extends BVCallbackBase {
	public $db;
	public $settings;

	public function __construct($callback_handler) {
		$this->db = $callback_handler->db;
		$this->settings = $callback_handler->settings;
	}

	public function getData($table, $limit = 0, $filter = "") {
		$result = array();
		$data = array();
		$rows = $this->db->getTableContent($table, '*', $filter, $limit);
		$last_id = 0;
		foreach ($rows as $row) {
			$result[] = $row;
			$last_id = $row['id'];
		}
		$data['last_id'] = $last_id;
		$data['rows'] = $result;
		return $data;
	}

	public function deleteBvDynamicEvents($filter = "") {
		$name = BVWPDynSync::$dynsync_table;
		return $this->db->deleteBVTableContent($name, $filter);
	}

  public function setMonitTime() {
    return $this->settings->updateOption('bvmonittime', time());
	}

	public function process($request) {
		$db = $this->db;
		$settings = $this->settings;
		$this->setMonitTime();
		$params = $request->params;
		switch ($request->method) {
		case "getdata":
			$resp = array();
			if (array_key_exists('lp', $params)) {
				require_once dirname( __FILE__ ) . '/../../protect/wp_lp/config.php';
				$lp_params = $params['lp'];
				$limit = intval(urldecode($lp_params['limit']));
				$filter = urldecode($lp_params['filter']);
				$db->deleteBVTableContent(BVWPLPConfig::$requests_table, $lp_params['rmfilter']);
				$table = $db->getBVTable(BVWPLPConfig::$requests_table);
				$resp["lplogs"] = $this->getData($table, $limit, $filter);
			}
			if (array_key_exists('fw', $params)) {
				require_once dirname( __FILE__ ) . '/../../protect/wp_fw/config.php';
				$fw_params = $params['fw'];
				$limit = intval(urldecode($fw_params['limit']));
				$filter = urldecode($fw_params['filter']);
				$db->deleteBVTableContent(BVWPFWConfig::$requests_table, $fw_params['rmfilter']);
				$table = $db->getBVTable(BVWPFWConfig::$requests_table);
				$resp["fwlogs"] = $this->getData($table, $limit, $filter);
			}
			if (array_key_exists('dynevent', $params)) {
				require_once dirname( __FILE__ ) . '/../../wp_dynsync.php';
				$isdynsyncactive = $settings->getOption('bvDynSyncActive');
				if ($isdynsyncactive == 'yes') {
					$limit = intval(urldecode($params['limit']));
					$filter = urldecode($params['filter']);
					$this->deleteBvDynamicEvents($params['rmfilter']);
					$table = $db->getBVTable(BVWPDynSync::$dynsync_table);
					$data = $this->getData($table, $limit, $filter);
					$resp['last_id'] = $data['last_id'];
					$resp['events'] = $data['rows'];
					$resp['timestamp'] = time();
					$resp["status"] = true;
				}
			}
			$resp["status"] = "done";
			break;
		case "rmdata":
			require_once dirname( __FILE__ ) . '/../../wp_dynsync.php';
			$filter = urldecode($params['filter']);
			$resp = array("status" => $this->deleteBvDynamicEvents($filter));
			break;
		default:
			$resp = false;
		}
		return $resp;
	}
}
endif;