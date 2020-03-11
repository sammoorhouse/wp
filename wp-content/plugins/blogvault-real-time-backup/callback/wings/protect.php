<?php

if (!defined('ABSPATH')) exit;
if (!class_exists('BVProtectCallback')) :

require_once dirname( __FILE__ ) . '/../../protect/protect.php';

class BVProtectCallback extends BVCallbackBase {
	public $db;
	public $settings;

	public function __construct($callback_handler) {
		$this->db = $callback_handler->db;
		$this->settings = $callback_handler->settings;
	}

	public function process($request) {
		$bvinfo = new BVInfo($this->settings);
		$protect = new BVProtect($this->db, $this->settings);
		$params = $request->params;
		switch ($request->method) {
		case "gtipprobeinfo":
			$resp = array();
			$headers = $params['hdrs'];
			$hdrsinfo = array();
			if ($headers && is_array($headers)) {
				foreach($headers as $hdr) {
					if (array_key_exists($hdr, $_SERVER)) {
						$hdrsinfo[$hdr] = $_SERVER[$hdr];
					}
				}
			}
			$resp["hdrsinfo"] = $hdrsinfo;
			if ($iphdr = $this->settings->getOption($bvinfo->ip_header_option)) {
				$resp["iphdr"] = $iphdr;
			}
			break;
		case "gtraddr":
			$raddr = array_key_exists('REMOTE_ADDR', $_SERVER) ? $_SERVER['REMOTE_ADDR'] : false;
			$resp = array("raddr" => $raddr);
			break;
		case "gtallhdrs":
			$data = (function_exists('getallheaders')) ? getallheaders() : false;
			$resp = array("allhdrs" => $data);
			break;
		case "gtsvr":
			$resp = array("svr" => $_SERVER);
			break;
		case "gtip":
			$resp = array("ip" => $protect->getIP());
			break;
		case "stiphdr":
			$option_name = $bvinfo->ip_header_option;
			$iphdr = array('hdr' => $params['hdr'], 'pos' => $params['pos']);
			$this->settings->updateOption($option_name, $iphdr);
			$resp = array("iphdr" => $this->settings->getOption($option_name));
			break;
		case "gtiphdr":
			$resp = array("iphdr" => $this->settings->getOption($bvinfo->ip_header_option));
			break;
		case "rmiphdr":
			$option_name = $bvinfo->ip_header_option;
			$this->settings->deleteOption($option_name);
			$resp = array("iphdr" => $this->settings->getOption($option_name));
			break;
		default:
			$resp = false;
		}
		return $resp;
	}
}
endif;