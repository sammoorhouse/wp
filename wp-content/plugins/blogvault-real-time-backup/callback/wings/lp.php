<?php

if (!defined('ABSPATH')) exit;
if (!class_exists('BVLoginProtectCallback')) :
	
require_once dirname( __FILE__ ) . '/../../protect/wp_lp/lp.php';

class BVLoginProtectCallback extends BVCallbackBase {
	public $db;
	public $settings;

	public function __construct($callback_handler) {
		$this->db = $callback_handler->db;
		$this->settings = $callback_handler->settings;
	}
	
	public function unBlockLogins() {
		$this->settings->deleteTransient('bvlp_block_logins');
		$this->settings->setTransient('bvlp_allow_logins', 'true', 1800);
		return $this->settings->getTransient('bvlp_allow_logins');
	}

	public function blockLogins($time) {
		$this->settings->deleteTransient('bvlp_allow_logins');
		$this->settings->setTransient('bvlp_block_logins', 'true', $time);
		return $this->settings->getTransient('bvlp_block_logins');
	}

	public function unBlockIP($ip, $attempts, $time) {
		$transient_name = BVWPLP::$unblock_ip_transient.$ip;
		$this->settings->setTransient($transient_name, $attempts, $time);
		return $this->settings->getTransient($transient_name);
	}
	
	public function process($request) {
		$params = $request->params;
		$config = new BVWPLPConfig($this->db, $this->settings);
		switch ($request->method) {
		case "clrconfig":
			$resp = array("clearconfig" => $config->clear());
			break;
		case "setmode":
			$config->setMode($params['mode']);
			$resp = array("setmode" => $config->getMode());
			break;
		case "setcaptchalimit":
			$config->setCaptchaLimit($params['captcha_limit']);
			$resp = array("captcha_limit" => $config->getCaptchaLimit());
			break;
		case "settmpblklimit":
			$config->setTempBlockLimit($params['temp_block_limit']);
			$resp = array("temp_block_limit" => $config->getTempBlockLimit());
			break;
		case "setblkalllimit":
			$config->setBlockAllLimit($params['block_all_limit']);
			$resp = array("block_all_limit" => $config->getBlockAllLimit());
			break;
		case "unblklogins":
			$resp = array("unblocklogins" => $this->unBlockLogins());
			break;
		case "blklogins":
			$time = array_key_exists('time', $params) ? $params['time'] : 1800;
			$resp = array("blocklogins" => $this->blockLogins($time));
			break;
		case "unblkip":
			$resp = array("unblockip" => $this->unBlockIP($params['ip'], $params['attempts'], $params['time']));
			break;
		default:
			$resp = false;
		}
		return $resp;
	}
}
endif;