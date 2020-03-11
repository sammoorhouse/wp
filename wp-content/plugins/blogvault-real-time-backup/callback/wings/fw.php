<?php

if (!defined('ABSPATH')) exit;
if (!class_exists('BVFirewallCallback')) :
	
require_once dirname( __FILE__ ) . '/../../protect/wp_fw/config.php';

class BVFirewallCallback {
	public $db;
	public $settings;

	public function __construct($callback_handler) {
		$this->db = $callback_handler->db;
		$this->settings = $callback_handler->settings;
	}

	public function process($request) {
		$params = $request->params;
		$config = new BVWPFWConfig($this->db, $this->settings);
		switch ($request->method) {
		case "clrconfig":
			$resp = array("clearconfig" => $config->clear());
			break;
		case "setmode":
			$config->setMode($params['mode']);
			$resp = array("setmode" => $config->getMode());
			break;
		case "dsblrules":
			$config->setDisabledRules($params['disabled_rules']);
			$resp = array("disabled_rules" => $config->getDisabledRules());
			break;
		case "adtrls":
			$config->setAuditRules($params['audit_rules']);
			$resp = array("audit_rules" => $config->getAuditRules());
			break;
		case "setrulesmode":
			$config->setRulesMode($params['rules_mode']);
			$resp = array("rules_mode" => $config->getRulesMode());
			break;
		case "setreqprofilingmode":
			$config->setReqProfilingMode($params['req_profiling_mode']);
			$resp = array("req_profiling_mode" => $config->getReqProfilingMode());
			break;
		case "stbypslevl":
			$config->setBypassLevel($params['bypslevl']);
			$resp = array("bypslevl" => $config->getBypassLevel());
			break;
		case "stcstmrls":
			$config->setCustomRoles($params['cstmrls']);
			$resp = array("cstmrls" => $config->getCustomRoles());
			break;
		case "stcookiemode":
			$config->setCookieMode($params['mode']);
			$resp = array("mode" => $config->getCookieMode());
			break;
		default:
			$resp = false;
		}
		return $resp;
	}
}
endif;
