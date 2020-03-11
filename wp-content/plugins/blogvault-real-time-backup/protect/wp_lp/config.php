<?php

if (!defined('ABSPATH')) exit;
if (!class_exists('BVWPLPConfig')) :
class BVWPLPConfig {
	public $db;
	public $settings;
	public static $requests_table = 'lp_requests';
	
	#mode
	const DISABLED = 1;
	const AUDIT    = 2;
	const PROTECT  = 3;

	public function __construct($db, $settings) {
		$this->db = $db;
		$this->settings = $settings;
	}

	public function setMode($mode) {
		if (!$mode) {
			$this->settings->deleteOption('bvlpmode');
		} else {
			$this->settings->updateOption('bvlpmode', intval($mode));
		}
	}

	public function setCaptchaLimit($count) {
		if (!$count) {
			$this->settings->deleteOption('bvlpcaptchaLimit');
		} else {
			$this->settings->updateOption('bvlpcaptchaLimit', intval($count));
		}
	}

	public function setTempBlockLimit($count) {
		if (!$count) {
			$this->settings->deleteOption('bvlptempblocklimit');
		} else {
			$this->settings->updateOption('bvlptempblocklimit', intval($count));
		}
	}

	public function setBlockAllLimit($count) {
		if (!$count) {
			$this->settings->deleteOption('bvlpblockalllimit');
		} else {
			$this->settings->updateOption('bvlpblockalllimit', intval($count));
		}
	}
	
	public function getMode() {
		$mode = $this->settings->getOption('bvlpmode');
		return intval($mode ? $mode : BVWPLPConfig::DISABLED);
	}

	public function getCaptchaLimit() {
		$limit = $this->settings->getOption('bvlpcaptchalimit');
		return ($limit ? $limit : 3);
	}

	public function getTempBlockLimit() {
		$limit = $this->settings->getOption('bvlptempblocklimit');
		return ($limit ? $limit : 10);
	}

	public function getBlockAllLimit() {
		$limit = $this->settings->getOption('bvlpblockAlllimit');
		return ($limit ? $limit : 100);
	}

	public function clear() {
		$this->setMode(false);
		$this->setCaptchaLimit(false);
		$this->setTempBlockLimit(false);
		$this->setBlockAllLimit(false);
		$this->db->dropBVTable(BVWPLPConfig::$requests_table);
		$this->settings->deleteOption('bvptplug');
		return true;
	}
}
endif;