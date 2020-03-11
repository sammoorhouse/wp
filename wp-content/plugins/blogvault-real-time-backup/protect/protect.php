<?php
if (!defined('ABSPATH')) exit;
if (!class_exists('BVProtect')) :
	
require_once dirname( __FILE__ ) . '/logger.php';
require_once dirname( __FILE__ ) . '/ipstore.php';
require_once dirname( __FILE__ ) . '/wp_fw/fw.php';
require_once dirname( __FILE__ ) . '/wp_lp/lp.php';

class BVProtect {
	public $db;
	public $settings;
	
	function __construct($db, $settings) {
		$this->settings = $settings;
		$this->db = $db;
	}

	public function init() {
		$bvipstore = new BVIPStore($this->db);
		$bvipstore->init();
		$ip = $this->getIP();
		$fw = new BVWPFW($this->db, $this->settings, $ip, $bvipstore);
		if ($fw->config->isActive()) {
			$fw->init();
			$fw->execute();
		}
		add_action('clear_fw_config', array($fw->config, 'clear'));
		$lp = new BVWPLP($this->db, $this->settings, $ip, $bvipstore);
		if ($lp->isActive()) {
			$lp->init();
		}
		add_action('clear_lp_config', array($lp->config, 'clear'));
	}

	public function getIP() {
		$ip = '127.0.0.1';
		$bvinfo = new BVInfo($this->settings);
		if (($ipHeader = $this->settings->getOption($bvinfo->ip_header_option)) && is_array($ipHeader)) {
			if (array_key_exists($ipHeader['hdr'], $_SERVER)) {
				$_ips = preg_split("/(,| |\t)/", $_SERVER[$ipHeader['hdr']]);
				if (array_key_exists(intval($ipHeader['pos']), $_ips)) {
					$ip = $_ips[intval($ipHeader['pos'])];
				}
			}
		} else if (array_key_exists('REMOTE_ADDR', $_SERVER)) {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		$ip = trim($ip);
		if (preg_match('/^\[([0-9a-fA-F:]+)\](:[0-9]+)$/', $ip, $matches)) {
			$ip = $matches[1];
		} elseif (preg_match('/^([0-9.]+)(:[0-9]+)$/', $ip, $matches)) {
			$ip = $matches[1];
		}
		return $ip;
	}
}
endif;
