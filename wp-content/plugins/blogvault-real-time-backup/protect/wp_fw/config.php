<?php

if (!defined('ABSPATH')) exit;
if (!class_exists('BVWPFWConfig')) :
class BVWPFWConfig {
	public $db;
	public $settings;
	public static $requests_table = 'fw_requests';
	public static $allRules = array(108, 112, 114, 115, 132, 133, 145, 146, 155, 156, 165, 167, 168, 169, 171, 172, 173, 174, 175, 176, 177, 178);
	public static $roleLevels = array(
		'administrator' => BVWPFWConfig::ROLE_LEVEL_ADMIN,
		'editor' => BVWPFWConfig::ROLE_LEVEL_EDITOR,
		'author' => BVWPFWConfig::ROLE_LEVEL_AUTHOR,
		'contributor' => BVWPFWConfig::ROLE_LEVEL_CONTRIBUTOR,
		'subscriber' => BVWPFWConfig::ROLE_LEVEL_SUBSCRIBER
	);

	function __construct($db, $settings) {
		$this->db = $db;
		$this->settings = $settings;
	}

	#mode
	const DISABLED = 1;
	const AUDIT    = 2;
	const PROTECT  = 3;

	#Rule Mode
	const DISABLEDRULE = 1;
	const AUDITRULE    = 2;
	const PROTECTRULE  = 3;

	#Request Profiling Mode
	const REQ_PROFILING_MODE_DISABLED = 1;
	const REQ_PROFILING_MODE_NORMAL = 2;
	const REQ_PROFILING_MODE_DEBUG = 3;

	#Cookie Mode
	const COOKIE_MODE_ENABLED = 1;
	const COOKIE_MODE_DISABLED = 2;

	#Role Level
	const ROLE_LEVEL_SUBSCRIBER = 1;
	const ROLE_LEVEL_CONTRIBUTOR = 2;
	const ROLE_LEVEL_AUTHOR = 3;
	const ROLE_LEVEL_EDITOR = 4;
	const ROLE_LEVEL_ADMIN = 5;
	const ROLE_LEVEL_CUSTOM = 6;

	public static function isDisabledRule($mode) {
		return ($mode === BVWPFWConfig::DISABLEDRULE);
	}

	public static function isProtectingRule($mode) {
		return ($mode === BVWPFWConfig::PROTECTRULE);
	}

	public static function isAuditingRule($mode) {
		return ($mode === BVWPFWConfig::AUDITRULE);
	}

	public function isActive() {
		return ($this->getMode() !== BVWPFWConfig::DISABLED);
	}

	public function isProtecting() {
		return ($this->getMode() === BVWPFWConfig::PROTECT);
	}

	public function isAuditing() {
		return ($this->getMode() === BVWPFWConfig::AUDIT);
	}

	public function isReqProfilingModeDebug() {
		return ($this->getReqProfilingMode() === BVWPFWConfig::REQ_PROFILING_MODE_DEBUG);
	}

	public function canProfileReqInfo() {
		return ($this->getReqProfilingMode() !== BVWPFWConfig::REQ_PROFILING_MODE_DISABLED);
	}

	public function canSetCookie() {
		return ($this->getCookieMode() === BVWPFWConfig::COOKIE_MODE_ENABLED);
	}

	public function getRules() {
		$rules = array("audit" => array(), "protect" => array());
		$isAudit = false;
		$rulesMode = $this->getRulesMode();
		if (BVWPFWConfig::isDisabledRule($rulesMode)) {
			return $rules;
		}
		$isAudit = ($this->isAuditing() || BVWPFWConfig::isAuditingRule($rulesMode));
		$rulesInfo = array();
		foreach ($this->getAuditRules() as $rule)
			$rulesInfo[$rule] = BVWPFWConfig::AUDITRULE;
		foreach ($this->getDisabledRules() as $rule)
			$rulesInfo[$rule] = BVWPFWConfig::DISABLEDRULE;
		foreach (BVWPFWConfig::$allRules as $rule) {
			if (isset($rulesInfo[$rule])) {
				if (BVWPFWConfig::isAuditingRule($rulesInfo[$rule])) {
					$rules["audit"][$rule] = BVWPFWConfig::AUDITRULE;
				}
			} else {
				if ($isAudit) {
					$rules["audit"][$rule] = BVWPFWConfig::AUDITRULE;
				} else {
					$rules["protect"][$rule] = BVWPFWConfig::PROTECTRULE;
				}
			}
		}
		return $rules;
	}

	public function setMode($mode) {
		if (!$mode) {
			$this->settings->deleteOption('bvfwmode');
		} else {
			$this->settings->updateOption('bvfwmode', intval($mode));
		}
	}

	public function setRulesMode($mode) {
		if (!$mode) {
			$this->settings->deleteOption('bvfwrulesmode');
		} else {
			$this->settings->updateOption('bvfwrulesmode', intval($mode));
		}
	}

	public function setCookieMode($mode) {
		if (!$mode) {
			$this->settings->deleteOption('bvfwcookiemode');
		} else {
			$this->settings->updateOption('bvfwcookiemode', intval($mode));
		}
	}

	public function setCookieKey($key) {
		if (!$key) {
			$this->settings->deleteOption('bvfwcookiekey');
		} else {
			$this->settings->updateOption('bvfwcookiekey', strval($key));
		}
	}

	public function setReqProfilingMode($mode) {
		if (!$mode) {
			$this->settings->deleteOption('bvfwreqprofilingmode');
		} else {
			$this->settings->updateOption('bvfwreqprofilingmode', intval($mode));
		}
	}

	public function setDisabledRules($rules) {
		if (!$rules) {
			$this->settings->deleteOption('bvfwdisabledrules');
		} else {
			$this->settings->updateOption('bvfwdisabledrules', $rules);
		}
	}

	public function setBypassLevel($level) {
		if (!$level) {
			$this->settings->deleteOption('bvfwbypasslevel');
		} else {
			$this->settings->updateOption('bvfwbypasslevel', $level);
		}
	}

	public function setCustomRoles($roles) {
		if (!$roles) {
			$this->settings->deleteOption('bvfwcutomroles');
		} else {
			$this->settings->updateOption('bvfwcustomroles', $roles);
		}
	}

	public function setAuditRules($rules) {
		if (!$rules) {
			$this->settings->deleteOption('bvfwauditrules');
		} else {
			$this->settings->updateOption('bvfwauditrules', $rules);
		}
	}

	public function getMode() {
		$mode = $this->settings->getOption('bvfwmode');
		return intval($mode ? $mode : BVWPFWConfig::DISABLED);
	}

	public function getRulesMode() {
		$mode = $this->settings->getOption('bvfwrulesmode');
		return intval($mode ? $mode : BVWPFWConfig::DISABLED);
	}

	public function getCookieMode() {
		$mode = $this->settings->getOption('bvfwcookiemode');
		return intval($mode ? $mode : BVWPFWConfig::COOKIE_MODE_DISABLED);
	}

	public function getCookieKey() {
		$key = (string) $this->settings->getOption('bvfwcookiekey');
		if ($key === '') {
			$key = BVAccount::randString(32);
			$this->setCookieKey($key);
		}
		return $key;
	}

	public function getReqProfilingMode() {
		$mode = $this->settings->getOption('bvfwreqprofilingmode');
		return intval($mode ? $mode : BVWPFWConfig::REQ_PROFILING_MODE_DISABLED);
	}

	public function getDisabledRules() {
		$rules = $this->settings->getOption('bvfwdisabledrules');
		return ($rules ? $rules : array());
	}

	public function getAuditRules() {
		$rules = $this->settings->getOption('bvfwauditrules');
		return ($rules ? $rules : array());
	}

	public function getBypassLevel() {
		$level = $this->settings->getOption('bvfwbypasslevel');
		return intval($level ? $level : BVWPFWConfig::ROLE_LEVEL_CONTRIBUTOR);
	}

	public function getCustomRoles() {
		$roles = $this->settings->getOption('bvfwcustomroles');
		return ($roles ? $roles : array());
	}

	public function clear() {
		$this->setMode(false);
		$this->setRulesMode(false);
		$this->setBypassLevel(false);
		$this->setCustomRoles(false);
		$this->setCookieMode(false);
		$this->setCookieKey(false);
		$this->setDisabledRules(false);
		$this->setAuditRules(false);
		$this->setReqProfilingMode(false);
		$this->db->dropBVTable(BVWPFWConfig::$requests_table);
		$this->settings->deleteOption('bvptplug');
		return true;
	}
}
endif;