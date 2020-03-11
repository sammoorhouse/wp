<?php

if (!defined('ABSPATH')) exit;
if (!class_exists('BVWPFW')) :

require_once dirname( __FILE__ ) . '/config.php';
require_once dirname( __FILE__ ) . '/request.php';

class BVWPFW {
	public $db;
	public $settings;
	public $request;
	public $config;
	public $ipstore;
	public $category;
	public $logger;
	private $currRuleInfo;

	const SQLIREGEX = '/(?:[^\\w<]|\\/\\*\\![0-9]*|^)(?:
		@@HOSTNAME|
		ALTER|ANALYZE|ASENSITIVE|
		BEFORE|BENCHMARK|BETWEEN|BIGINT|BINARY|BLOB|
		CALL|CASE|CHANGE|CHAR|CHARACTER|CHAR_LENGTH|COLLATE|COLUMN|CONCAT|CONDITION|CONSTRAINT|CONTINUE|CONVERT|CREATE|CROSS|CURRENT_DATE|CURRENT_TIME|CURRENT_TIMESTAMP|CURRENT_USER|CURSOR|
		DATABASE|DATABASES|DAY_HOUR|DAY_MICROSECOND|DAY_MINUTE|DAY_SECOND|DECIMAL|DECLARE|DEFAULT|DELAYED|DELETE|DESCRIBE|DETERMINISTIC|DISTINCT|DISTINCTROW|DOUBLE|DROP|DUAL|DUMPFILE|
		EACH|ELSE|ELSEIF|ELT|ENCLOSED|ESCAPED|EXISTS|EXIT|EXPLAIN|EXTRACTVALUE|
		FETCH|FLOAT|FLOAT4|FLOAT8|FORCE|FOREIGN|FROM|FULLTEXT|
		GRANT|GROUP|HAVING|HEX|HIGH_PRIORITY|HOUR_MICROSECOND|HOUR_MINUTE|HOUR_SECOND|
		IFNULL|IGNORE|INDEX|INFILE|INNER|INOUT|INSENSITIVE|INSERT|INTERVAL|ISNULL|ITERATE|
		JOIN|KILL|LEADING|LEAVE|LIMIT|LINEAR|LINES|LOAD|LOAD_FILE|LOCALTIME|LOCALTIMESTAMP|LOCK|LONG|LONGBLOB|LONGTEXT|LOOP|LOW_PRIORITY|
		MASTER_SSL_VERIFY_SERVER_CERT|MATCH|MAXVALUE|MEDIUMBLOB|MEDIUMINT|MEDIUMTEXT|MID|MIDDLEINT|MINUTE_MICROSECOND|MINUTE_SECOND|MODIFIES|
		NATURAL|NO_WRITE_TO_BINLOG|NULL|NUMERIC|OPTION|ORD|ORDER|OUTER|OUTFILE|
		PRECISION|PRIMARY|PRIVILEGES|PROCEDURE|PROCESSLIST|PURGE|
		RANGE|READ_WRITE|REGEXP|RELEASE|REPEAT|REQUIRE|RESIGNAL|RESTRICT|RETURN|REVOKE|RLIKE|ROLLBACK|
		SCHEMA|SCHEMAS|SECOND_MICROSECOND|SELECT|SENSITIVE|SEPARATOR|SHOW|SIGNAL|SLEEP|SMALLINT|SPATIAL|SPECIFIC|SQLEXCEPTION|SQLSTATE|SQLWARNING|SQL_BIG_RESULT|SQL_CALC_FOUND_ROWS|SQL_SMALL_RESULT|STARTING|STRAIGHT_JOIN|SUBSTR|
		TABLE|TERMINATED|TINYBLOB|TINYINT|TINYTEXT|TRAILING|TRANSACTION|TRIGGER|
		UNDO|UNHEX|UNION|UNLOCK|UNSIGNED|UPDATE|UPDATEXML|USAGE|USING|UTC_DATE|UTC_TIME|UTC_TIMESTAMP|
		VALUES|VARBINARY|VARCHAR|VARCHARACTER|VARYING|WHEN|WHERE|WHILE|WRITE|YEAR_MONTH|ZEROFILL)(?=[^\\w]|$)/ix';

	const XSSREGEX = '/(?:
		#tags
		(?:\\<|\\+ADw\\-|\\xC2\\xBC)(script|iframe|svg|object|embed|applet|link|style|meta|\\/\\/|\\?xml\\-stylesheet)(?:[^\\w]|\\xC2\\xBE)|
		#protocols
		(?:^|[^\\w])(?:(?:\\s*(?:&\\#(?:x0*6a|0*106)|j)\\s*(?:&\\#(?:x0*61|0*97)|a)\\s*(?:&\\#(?:x0*76|0*118)|v)\\s*(?:&\\#(?:x0*61|0*97)|a)|\\s*(?:&\\#(?:x0*76|0*118)|v)\\s*(?:&\\#(?:x0*62|0*98)|b)|\\s*(?:&\\#(?:x0*65|0*101)|e)\\s*(?:&\\#(?:x0*63|0*99)|c)\\s*(?:&\\#(?:x0*6d|0*109)|m)\\s*(?:&\\#(?:x0*61|0*97)|a)|\\s*(?:&\\#(?:x0*6c|0*108)|l)\\s*(?:&\\#(?:x0*69|0*105)|i)\\s*(?:&\\#(?:x0*76|0*118)|v)\\s*(?:&\\#(?:x0*65|0*101)|e))\\s*(?:&\\#(?:x0*73|0*115)|s)\\s*(?:&\\#(?:x0*63|0*99)|c)\\s*(?:&\\#(?:x0*72|0*114)|r)\\s*(?:&\\#(?:x0*69|0*105)|i)\\s*(?:&\\#(?:x0*70|0*112)|p)\\s*(?:&\\#(?:x0*74|0*116)|t)|\\s*(?:&\\#(?:x0*6d|0*109)|m)\\s*(?:&\\#(?:x0*68|0*104)|h)\\s*(?:&\\#(?:x0*74|0*116)|t)\\s*(?:&\\#(?:x0*6d|0*109)|m)\\s*(?:&\\#(?:x0*6c|0*108)|l)|\\s*(?:&\\#(?:x0*6d|0*109)|m)\\s*(?:&\\#(?:x0*6f|0*111)|o)\\s*(?:&\\#(?:x0*63|0*99)|c)\\s*(?:&\\#(?:x0*68|0*104)|h)\\s*(?:&\\#(?:x0*61|0*97)|a)|\\s*(?:&\\#(?:x0*64|0*100)|d)\\s*(?:&\\#(?:x0*61|0*97)|a)\\s*(?:&\\#(?:x0*74|0*116)|t)\\s*(?:&\\#(?:x0*61|0*97)|a)(?!(?:&\\#(?:x0*3a|0*58)|\\:)(?:&\\#(?:x0*69|0*105)|i)(?:&\\#(?:x0*6d|0*109)|m)(?:&\\#(?:x0*61|0*97)|a)(?:&\\#(?:x0*67|0*103)|g)(?:&\\#(?:x0*65|0*101)|e)(?:&\\#(?:x0*2f|0*47)|\\/)(?:(?:&\\#(?:x0*70|0*112)|p)(?:&\\#(?:x0*6e|0*110)|n)(?:&\\#(?:x0*67|0*103)|g)|(?:&\\#(?:x0*62|0*98)|b)(?:&\\#(?:x0*6d|0*109)|m)(?:&\\#(?:x0*70|0*112)|p)|(?:&\\#(?:x0*67|0*103)|g)(?:&\\#(?:x0*69|0*105)|i)(?:&\\#(?:x0*66|0*102)|f)|(?:&\\#(?:x0*70|0*112)|p)?(?:&\\#(?:x0*6a|0*106)|j)(?:&\\#(?:x0*70|0*112)|p)(?:&\\#(?:x0*65|0*101)|e)(?:&\\#(?:x0*67|0*103)|g)|(?:&\\#(?:x0*74|0*116)|t)(?:&\\#(?:x0*69|0*105)|i)(?:&\\#(?:x0*66|0*102)|f)(?:&\\#(?:x0*66|0*102)|f)|(?:&\\#(?:x0*73|0*115)|s)(?:&\\#(?:x0*76|0*118)|v)(?:&\\#(?:x0*67|0*103)|g)(?:&\\#(?:x0*2b|0*43)|\\+)(?:&\\#(?:x0*78|0*120)|x)(?:&\\#(?:x0*6d|0*109)|m)(?:&\\#(?:x0*6c|0*108)|l))(?:(?:&\\#(?:x0*3b|0*59)|;)(?:&\\#(?:x0*63|0*99)|c)(?:&\\#(?:x0*68|0*104)|h)(?:&\\#(?:x0*61|0*97)|a)(?:&\\#(?:x0*72|0*114)|r)(?:&\\#(?:x0*73|0*115)|s)(?:&\\#(?:x0*65|0*101)|e)(?:&\\#(?:x0*74|0*116)|t)(?:&\\#(?:x0*3d|0*61)|=)[\\-a-z0-9]+)?(?:(?:&\\#(?:x0*3b|0*59)|;)(?:&\\#(?:x0*62|0*98)|b)(?:&\\#(?:x0*61|0*97)|a)(?:&\\#(?:x0*73|0*115)|s)(?:&\\#(?:x0*65|0*101)|e)(?:&\\#(?:x0*36|0*54)|6)(?:&\\#(?:x0*34|0*52)|4))?(?:&\\#(?:x0*2c|0*44)|,)))\\s*(?:&\\#(?:x0*3a|0*58)|&colon|\\:)|
		#css expression
		(?:^|[^\\w])(?:(?:\\\\0*65|\\\\0*45|e)(?:\\/\\*.*?\\*\\/)*(?:\\\\0*78|\\\\0*58|x)(?:\\/\\*.*?\\*\\/)*(?:\\\\0*70|\\\\0*50|p)(?:\\/\\*.*?\\*\\/)*(?:\\\\0*72|\\\\0*52|r)(?:\\/\\*.*?\\*\\/)*(?:\\\\0*65|\\\\0*45|e)(?:\\/\\*.*?\\*\\/)*(?:\\\\0*73|\\\\0*53|s)(?:\\/\\*.*?\\*\\/)*(?:\\\\0*73|\\\\0*53|s)(?:\\/\\*.*?\\*\\/)*(?:\\\\0*69|\\\\0*49|i)(?:\\/\\*.*?\\*\\/)*(?:\\\\0*6f|\\\\0*4f|o)(?:\\/\\*.*?\\*\\/)*(?:\\\\0*6e|\\\\0*4e|n))[^\\w]*?(?:\\\\0*28|\\()|
		#css properties
		(?:^|[^\\w])(?:(?:(?:\\\\0*62|\\\\0*42|b)(?:\\/\\*.*?\\*\\/)*(?:\\\\0*65|\\\\0*45|e)(?:\\/\\*.*?\\*\\/)*(?:\\\\0*68|\\\\0*48|h)(?:\\/\\*.*?\\*\\/)*(?:\\\\0*61|\\\\0*41|a)(?:\\/\\*.*?\\*\\/)*(?:\\\\0*76|\\\\0*56|v)(?:\\/\\*.*?\\*\\/)*(?:\\\\0*69|\\\\0*49|i)(?:\\/\\*.*?\\*\\/)*(?:\\\\0*6f|\\\\0*4f|o)(?:\\/\\*.*?\\*\\/)*(?:\\\\0*72|\\\\0*52|r)(?:\\/\\*.*?\\*\\/)*)|(?:(?:\\\\0*2d|\\\\0*2d|-)(?:\\/\\*.*?\\*\\/)*(?:\\\\0*6d|\\\\0*4d|m)(?:\\/\\*.*?\\*\\/)*(?:\\\\0*6f|\\\\0*4f|o)(?:\\/\\*.*?\\*\\/)*(?:\\\\0*7a|\\\\0*5a|z)(?:\\/\\*.*?\\*\\/)*(?:\\\\0*2d|\\\\0*2d|-)(?:\\/\\*.*?\\*\\/)*(?:\\\\0*62|\\\\0*42|b)(?:\\/\\*.*?\\*\\/)*(?:\\\\0*69|\\\\0*49|i)(?:\\/\\*.*?\\*\\/)*(?:\\\\0*6e|\\\\0*4e|n)(?:\\/\\*.*?\\*\\/)*(?:\\\\0*64|\\\\0*44|d)(?:\\/\\*.*?\\*\\/)*(?:\\\\0*69|\\\\0*49|i)(?:\\/\\*.*?\\*\\/)*(?:\\\\0*6e|\\\\0*4e|n)(?:\\/\\*.*?\\*\\/)*(?:\\\\0*67|\\\\0*47|g)(?:\\/\\*.*?\\*\\/)*))[^\\w]*(?:\\\\0*3a|\\\\0*3a|:)[^\\w]*(?:\\\\0*75|\\\\0*55|u)(?:\\\\0*72|\\\\0*52|r)(?:\\\\0*6c|\\\\0*4c|l)|
		#properties
		(?:^|[^\\w])(?:on(?:abort|activate|afterprint|afterupdate|autocomplete|autocompleteerror|beforeactivate|beforecopy|beforecut|beforedeactivate|beforeeditfocus|beforepaste|beforeprint|beforeunload|beforeupdate|blur|bounce|cancel|canplay|canplaythrough|cellchange|change|click|close|contextmenu|controlselect|copy|cuechange|cut|dataavailable|datasetchanged|datasetcomplete|dblclick|deactivate|drag|dragend|dragenter|dragleave|dragover|dragstart|drop|durationchange|emptied|encrypted|ended|error|errorupdate|filterchange|finish|focus|focusin|focusout|formchange|forminput|hashchange|help|input|invalid|keydown|keypress|keyup|languagechange|layoutcomplete|load|loadeddata|loadedmetadata|loadstart|losecapture|message|mousedown|mouseenter|mouseleave|mousemove|mouseout|mouseover|mouseup|mousewheel|move|moveend|movestart|mozfullscreenchange|mozfullscreenerror|mozpointerlockchange|mozpointerlockerror|offline|online|page|pagehide|pageshow|paste|pause|play|playing|popstate|progress|propertychange|ratechange|readystatechange|reset|resize|resizeend|resizestart|rowenter|rowexit|rowsdelete|rowsinserted|scroll|search|seeked|seeking|select|selectstart|show|stalled|start|storage|submit|suspend|timer|timeupdate|toggle|unload|volumechange|waiting|webkitfullscreenchange|webkitfullscreenerror|wheel)|formaction|data\\-bind|ev:event)[^\\w]
		)/ix';

	const BYPASS_COOKIE = "bvfw-bypass-cookie";
	const IP_COOKIE = "bvfw-ip-cookie";

	public function __construct($db, $settings, $ip, $ipstore) {
		$this->db = $db;
		$this->settings = $settings;
		$this->config = new BVWPFWConfig($db, $settings);
		$this->request = new BVWPRequest($ip);
		$this->ipstore = $ipstore;
		$this->logger = new BVLogger($db, BVWPFWConfig::$requests_table);
	}

	public function init() {
		if ($this->config->canSetCookie()) {
			add_action('init', array($this, 'setBypassCookie'));
			$this->setIPCookie();
		}
		add_filter('status_header', array($this->request, 'captureRespCode'));
		register_shutdown_function(array($this, 'log'));
	}

	public function setcookie($name, $value, $expire, $path = COOKIEPATH, $domain = COOKIE_DOMAIN) {
		if (version_compare(PHP_VERSION, '5.2.0') >= 0) {
			$secure = function_exists('is_ssl') ? is_ssl() : false;
			@setcookie($name, $value, $expire, $path, $domain, $secure, true);
		} else {
			@setcookie($name, $value, $expire, $path);
		}
	}

	public function setBypassCookie() {
		if (function_exists('is_user_logged_in') && is_user_logged_in() && !$this->hasValidBypassCookie()) {
			$roleLevel = $this->getCurrentRoleLevel();
			$bypassLevel = $this->config->getBypassLevel();
			if ($roleLevel >= $bypassLevel) {
				$cookie = $this->generateBypassCookie();
				$this->setcookie(BVWPFW::BYPASS_COOKIE, $cookie, time() + 43200);
			}
		}
	}

	public function generateBypassCookie() {
		$time = floor(time() / 43200);
		$bypassLevel = $this->config->getBypassLevel();
		$cookiekey = $this->config->getCookieKey();
		return sha1($bypassLevel.$time.$cookiekey);
	}

	public function hasValidBypassCookie() {
		$cookie = (string) $this->request->getCookies(BVWPFW::BYPASS_COOKIE);
		return ($this->config->canSetCookie() && ($cookie === $this->generateBypassCookie()));
	}

	public function setIPCookie() {
		if (!$this->request->getCookies(BVWPFW::IP_COOKIE)) {
			$ip = $this->request->getIP();
			$cookiekey = $this->config->getCookieKey();
			$time = floor(time() / 3600);
			$cookie = sha1($ip.$time.$cookiekey);
			$this->setcookie(BVWPFW::IP_COOKIE, $cookie, time() + 3600);
		}
	}

	public function getBVCookies() {
		$cookies = array();
		$cookies[BVWPFW::IP_COOKIE] = (string) $this->request->getCookies(BVWPFW::IP_COOKIE);
		return $cookies;
	}

	public function getCurrentRoleLevel() {
		if (function_exists('current_user_can')) {
			if (function_exists('is_super_admin') &&  is_super_admin()) {
				return BVWPFWConfig::ROLE_LEVEL_ADMIN;
			}
			foreach ($this->config->getCustomRoles() as $role) {
				if (current_user_can($role)) {
					return BVWPFWConfig::ROLE_LEVEL_CUSTOM;
				}
			}
			foreach (BVWPFWConfig::$roleLevels as $role => $level) {
				if (current_user_can($role)) {
					return $level;
				}
			}
		}
		return 0;
	}

	public function log() {
		if ($this->config->canSetCookie()) {
			$canlog = !$this->hasValidBypassCookie();
		} else {
			$canlog = (!function_exists('is_user_logged_in') || !is_user_logged_in());
		}
		if ($canlog) {
			$this->logger->log($this->request->getDataToLog());
		}
	}

	public function terminateRequest($category = BVWPRequest::NORMAL) {
		$info = new BVInfo($this->settings);
		$this->request->setCategory($category);
		$this->request->setStatus(BVWPRequest::BLOCKED);
		$this->request->setRespCode(403);
		header("Cache-Control: no-cache, no-store, must-revalidate");
		header("Pragma: no-cache");
		header("Expires: 0");
		header('HTTP/1.0 403 Forbidden');
		$brandname = $info->getBrandName();
		die("
				<div style='height: 98vh;'>
					<div style='text-align: center; padding: 10% 0; font-family: Arial, Helvetica, sans-serif;'>
					<div><p><img src=".plugins_url('/../../img/icon.png', __FILE__)."><h2>Firewall</h2><h3>powered by</h3><h2>"
							.$brandname."</h2></p><div>
						<p>Blocked because of Malicious Activities</p>
					</div>
				</div>
			");
	}

	public function isBlacklistedIP() {
		return $this->ipstore->checkIPPresent($this->request->getIP(), BVIPStore::BLACKLISTED, BVIPStore::FW);
	}

	public function isWhitelistedIP() {
		return $this->ipstore->checkIPPresent($this->request->getIP(), BVIPStore::WHITELISTED, BVIPStore::FW);
	}

	public function canBypassFirewall() {
		if ($this->isWhitelistedIP() || $this->hasValidBypassCookie()) {
			$this->request->setCategory(BVWPRequest::WHITELISTED);
			$this->request->setStatus(BVWPRequest::BYPASSED);
			return true;
		}
		return false;
	}
	
	public function execute() {
		if ($this->config->canProfileReqInfo()) {
			$result = array();
			$result += $this->profileRequestInfo($this->request->getBody(),
					$this->config->isReqProfilingModeDebug(), 'BODY_');
			$result += $this->profileRequestInfo($this->request->getQueryString(),
					true, 'GET_');
			$result += $this->profileRequestInfo($this->request->getFiles(),
					true, 'FILES_');
			$result += $this->profileRequestInfo($this->getBVCookies(),
					true, 'COOKIES_');
			if (strpos($this->request->getPath(), 'admin-ajax.php') !== false) {
				$result += array('BODY_ADMIN_AJAX_ACTION' => $this->request->getBody('action'));
				$result += array('GET_ADMIN_AJAX_ACTION' => $this->request->getQueryString('action'));
			}
			if (strpos($this->request->getPath(), 'admin-post.php') !== false) {
				$result += array('BODY_ADMIN_POST_ACTION' => $this->request->getBody('action'));
				$result += array('GET_ADMIN_POST_ACTION' => $this->request->getQueryString('action'));
			}
			$this->request->updateReqInfo($result);
		}
		if (!$this->canBypassFirewall()) {
			$rules = $this->config->getRules();
			$this->matchRules($rules["audit"]);
			if ($this->config->isProtecting()) {
				if ($this->isBlacklistedIP()) {
					$this->terminateRequest(BVWPRequest::BLACKLISTED);
				}
				if ($this->matchRules($rules["protect"], true)) {
					$this->terminateRequest();
				}
			}
		}
	}

	public function getServerValue($key) {
		if (isset($_SERVER) && array_key_exists($key, $_SERVER)) {
			return $_SERVER[$key];
		}
		return null;
	}

	public function match($pattern, $subject, $key = NULL) {
		if (is_array($subject)) {
			foreach ($subject as $k => $v) {
				$k = ($key !== NULL) ? $key.'-'.$k : NULL;
				if ($this->match($pattern, $v, $k)) {
					return true;
				}
			}
		} else {
			if (preg_match((string) $pattern, (string) $subject) > 0) {
				if ($key !== NULL) {
					$this->currRuleInfo[$key] = $this->getLength($subject);
				}
				return true;
			}
		}
		return false;
	}

	public function matchCount($pattern, $subject) {
		$count = 0;
		if (is_array($subject)) {
			foreach ($subject as $val) {
				$count += $this->matchCount($pattern, $val);
			}
			return $count;
		} else {
			$count = preg_match_all((string) $pattern, (string) $subject, $matches);
			return ($count === false ? 0 : $count);
		}
	}

	public function matchMD5($str, $val) {
		return md5((string) $str) === $val;
	}

	public function getLength($val) {
		$length = 0;
		if (is_array($val)) {
			foreach ($val as $v) {
				$length += $this->getLength($v);
			}
			return $length;
		} else {
			return strlen((string) $val);
		}
	}

	public function equals($value, $subject) {
		return $value == $subject;
	}

	public function notEquals($value, $subject) {
		return $value != $subject;
	}

	public function profileRequestInfo($params, $debug = false, $prefix = '') {
		$result = array();
		if (is_array($params)) {
			foreach ($params as $key => $value) {
				$currkey = $prefix . $key;
				if (is_array($value)) {
					$result = $result + $this->profileRequestInfo($value, $debug, $currkey . '_');
				} else {
					$result[$currkey] = array();
					$valsize = $this->getLength($value);
					$result[$currkey]["size"] = $valsize;
					if ($debug === true && $valsize < 256) {
						$result[$currkey]["value"] = $value;
						continue;
					}

					if (preg_match('/^\d+$/', $value)) {
						$result[$currkey]["numeric"] = true;
					} else if (preg_match('/^\w+$/', $value)) {
						$result[$currkey]["regular_word"] = true;
					} else if (preg_match('/^\S+$/', $value)) {
						$result[$currkey]["special_word"] = true;
					} else if (preg_match('/^[\w\s]+$/', $value)) {
						$result[$currkey]["regular_sentence"] = true;
					} else if (preg_match('/^[\w\W]+$/', $value)) {
						$result[$currkey]["special_chars_sentence"] = true;
					}

					if (preg_match('/^\b((25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9]?[0-9])\.){3}
						(25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9]?[0-9])\b$/x', $value)) {
						$result[$currkey]["ipv4"] = true;
					} else if (preg_match('/\b((25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9]?[0-9])\.){3}
						(25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9]?[0-9])\b/x', $value)) {
						$result[$currkey]["embeded_ipv4"] = true;
					} else if (preg_match('/^(([0-9a-fA-F]{1,4}:){7,7}[0-9a-fA-F]{1,4}|
						([0-9a-fA-F]{1,4}:){1,7}:|([0-9a-fA-F]{1,4}:){1,6}:[0-9a-fA-F]{1,4}|
						([0-9a-fA-F]{1,4}:){1,5}(:[0-9a-fA-F]{1,4}){1,2}|([0-9a-fA-F]{1,4}:){1,4}
						(:[0-9a-fA-F]{1,4}){1,3}|([0-9a-fA-F]{1,4}:){1,3}(:[0-9a-fA-F]{1,4}){1,4}|
						([0-9a-fA-F]{1,4}:){1,2}(:[0-9a-fA-F]{1,4}){1,5}|[0-9a-fA-F]{1,4}:((:[0-9a-fA-F]{1,4}){1,6})|
						:((:[0-9a-fA-F]{1,4}){1,7}|:)|fe80:(:[0-9a-fA-F]{0,4}){0,4}%[0-9a-zA-Z]{1,}|
						::(ffff(:0{1,4}){0,1}:){0,1}((25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])\.){3}
						(25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])|([0-9a-fA-F]{1,4}:){1,4}:((25[0-5]|
						(2[0-4]|1{0,1}[0-9]){0,1}[0-9])\.){3}(25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9]))$/x', $value)) {
						$result[$currkey]["ipv6"] = true;
					} else if (preg_match('/(([0-9a-fA-F]{1,4}:){7,7}[0-9a-fA-F]{1,4}|
						([0-9a-fA-F]{1,4}:){1,7}:|([0-9a-fA-F]{1,4}:){1,6}:[0-9a-fA-F]{1,4}|
						([0-9a-fA-F]{1,4}:){1,5}(:[0-9a-fA-F]{1,4}){1,2}|([0-9a-fA-F]{1,4}:){1,4}
						(:[0-9a-fA-F]{1,4}){1,3}|([0-9a-fA-F]{1,4}:){1,3}(:[0-9a-fA-F]{1,4}){1,4}|
						([0-9a-fA-F]{1,4}:){1,2}(:[0-9a-fA-F]{1,4}){1,5}|[0-9a-fA-F]{1,4}:((:[0-9a-fA-F]{1,4}){1,6})|
						:((:[0-9a-fA-F]{1,4}){1,7}|:)|fe80:(:[0-9a-fA-F]{0,4}){0,4}%[0-9a-zA-Z]{1,}|
						::(ffff(:0{1,4}){0,1}:){0,1}((25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])\.){3}
						(25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])|([0-9a-fA-F]{1,4}:){1,4}:((25[0-5]|
						(2[0-4]|1{0,1}[0-9]){0,1}[0-9])\.){3}(25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9]))/x', $value)) {
						$result[$currkey]["embeded_ipv6"] = true;
					}

					if (preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/', $value)) {
						$result[$currkey]["email"] = true;
					} else if (preg_match('/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}/', $value)) {
						$result[$currkey]["embeded_email"] = true;
					}

					if (preg_match('/^(http|ftp)s?:\/\/\S+$/i', $value)) {
						$result[$currkey]["link"] = true;
					} else if (preg_match('/(http|ftp)s?:\/\/\S+$/i', $value)) {
						$result[$currkey]["embeded_link"] = true;
					}

					if (preg_match('/<(html|head|title|base|link|meta|style|picture|source|img|
						iframe|embed|object|param|video|audio|track|map|area|form|label|input|button|
						select|datalist|optgroup|option|textarea|output|progress|meter|fieldset|legend|
						script|noscript|template|slot|canvas)/ix', $value)) {
						$result[$currkey]["embeded_html"] = true;
					}

					if (preg_match('/\.(jpg|jpeg|png|gif|ico|pdf|doc|docx|ppt|pptx|pps|ppsx|odt|xls|zip|gzip|
						xlsx|psd|mp3|m4a|ogg|wav|mp4|m4v|mov|wmv|avi|mpg|ogv|3gp|3g2|php|html|phtml|js|css)/ix', $value)) {
						$result[$currkey]["file"] = true;
					}

					if ($this->matchCount(BVWPFW::SQLIREGEX, $value) >= 2) {
						$result[$currkey]["sql"] = true;
					}
				}
			}
		}
		return $result;
	}

	public function matchRules($rules = array(), $isProtect = false) {
		if (empty($rules)) {
			return false;
		}
		if (isset($rules[108])) {
			$this->currRuleInfo = array();
			if ($this->match(BVWPFW::XSSREGEX, $this->request->getQueryString(), "GET")) {
				$this->request->updateRulesInfo(108, $this->currRuleInfo);
				if ($isProtect) return true;
			}
		}
		if (isset($rules[112])) {
			$this->currRuleInfo = array();
			if ($this->match('/\\/wp\\-admin[\\/]+admin\\-ajax\\.php/', $this->request->getPath()) &&
				(($this->equals('revslider_show_image', $this->request->getQueryString('action')) && $this->match('/\\.php$/i', $this->request->getQueryString('img'), "img")) or
				($this->equals('revslider_show_image', $this->request->getBody('action')) && $this->match('/\\.php$/i', $this->request->getQueryString('img'), "img")))) {
				$this->request->updateRulesInfo(112, $this->currRuleInfo);
				if ($isProtect) return true;
			}
		}
		if (isset($rules[114])) {
			$this->currRuleInfo = array();
			if ($this->match('/<\\!(?:DOCTYPE|ENTITY)\\s+(?:%\\s*)?\\w+\\s+SYSTEM/i', $this->request->getBody(), "BODY") or
				$this->match('/<\\!(?:DOCTYPE|ENTITY)\\s+(?:%\\s*)?\\w+\\s+SYSTEM/i', $this->request->getQueryString(), "GET")) {
				$this->request->updateRulesInfo(114, $this->currRuleInfo);
				if ($isProtect) return true;
			}
		}
		if (isset($rules[115])) {
			$this->currRuleInfo = array();
			if ($this->match('#/wp\\-admin/admin\\-ajax\\.php$#i', $this->getServerValue('SCRIPT_FILENAME')) &&
				($this->equals('update-plugin', $this->request->getBody('action')) or $this->equals('update-plugin', $this->request->getQueryString('action'))) && ($this->match('/(^|\\/|\\\\|%2f|%5c)\\.\\.(\\\\|\\/|%2f|%5c)/i', $this->request->getBody(), "BODY") or
				($this->match('/(^|\\/|\\\\|%2f|%5c)\\.\\.(\\\\|\\/|%2f|%5c)/i', $this->request->getQueryString(), "GET")))) {
				$this->request->updateRulesInfo(115, $this->currRuleInfo);
				if ($isProtect) return true;
			}
		}
		if (isset($rules[132])) {
			$this->currRuleInfo = array();
			if (($this->equals('Y', $this->request->getBody('kentopvc_hidden'))) &&
				((!$this->match('/^1?$/', $this->request->getBody('kento_pvc_hide'), "kento_pvc_hide")) or
				(!$this->match('/^1?$/', $this->request->getBody('kento_pvc_uniq'), "kento_pvc_uniq")) or
				(!$this->match('/^1?$/', $this->request->getBody('kento_pvc_posttype'), "kento_pvc_posttype")) or
				($this->match(BVWPFW::XSSREGEX, $this->request->getBody('kento_pvc_today_text'), "kento_pvc_today_text")) or
				($this->match(BVWPFW::XSSREGEX, $this->request->getBody('kento_pvc_total_text'), "kento_pvc_total_text")) or
				($this->match(BVWPFW::XSSREGEX, $this->request->getBody('kento_pvc_numbers_lang'), "kento_pvc_numbers_lang")))) {
				$this->request->updateRulesInfo(132, $this->currRuleInfo);
				if ($isProtect) return true;
			}
		}
		if (isset($rules[133])) {
			$this->currRuleInfo = array();
			if ((($this->match('#/wp\\-mobile\\-detector[/]+resize\\.php#i', $this->request->getPath())) or
				($this->match('#/wp\\-mobile\\-detector[/]+timthumb\\.php#i', $this->request->getPath()))) &&
				((($this->getLength($this->request->getBody('src')) > 0) &&
				(!$this->match('/\\.(?:png|gif|jpg|jpeg|jif|jfif|svg)$/i', $this->request->getBody('src'), "src"))) or
				(($this->getLength($this->request->getQueryString('src'))) &&
				(!$this->match('/\\.(?:png|gif|jpg|jpeg|jif|jfif|svg)$/i', $this->request->getQueryString('src'), "src"))))) {
				$this->request->updateRulesInfo(133, $this->currRuleInfo);
				if ($isProtect) return true;
			}
		}
		if (isset($rules[145])) {
			$this->currRuleInfo = array();
			if ((($this->match('/Abonti|aggregator|AhrefsBot|asterias|BDCbot|BLEXBot|BuiltBotTough|Bullseye|BunnySlippers|ca\\-crawler|CCBot|Cegbfeieh|CheeseBot|CherryPicker|CopyRightCheck|cosmos|Crescent|discobot|DittoSpyder|DotBot|Download Ninja|EasouSpider|EmailCollector|EmailSiphon|EmailWolf|EroCrawler|Exabot|ExtractorPro|Fasterfox|FeedBooster|Foobot|Genieo|grub\\-client|Harvest|hloader|httplib|HTTrack|humanlinks|ieautodiscovery|InfoNaviRobot|IstellaBot|Java\\/1\\.|JennyBot|k2spider|Kenjin Spider|Keyword Density\\/0\\.9|larbin|LexiBot|libWeb|libwww|LinkextractorPro|linko|LinkScan\\/8\\.1a Unix|LinkWalker|LNSpiderguy|lwp\\-trivial|magpie|Mata Hari|MaxPointCrawler|MegaIndex|Microsoft URL Control|MIIxpc|Mippin|Missigua Locator|Mister PiX|MJ12bot|moget|MSIECrawler|NetAnts|NICErsPRO|Niki\\-Bot|NPBot|Nutch|Offline Explorer|Openfind|panscient\\.com|PHP\\/5\\.\\{|ProPowerBot\\/2\\.14|ProWebWalker|Python\\-urllib|QueryN Metasearch|RepoMonkey|RMA|SemrushBot|SeznamBot|SISTRIX|sitecheck\\.Internetseer\\.com|SiteSnagger|SnapPreviewBot|Sogou|SpankBot|spanner|spbot|Spinn3r|suzuran|Szukacz\\/1\\.4|Teleport|Telesoft|The Intraformant|TheNomad|TightTwatBot|Titan|toCrawl\\/UrlDispatcher|True_Robot|turingos|TurnitinBot|UbiCrawler|UnisterBot|URLy Warning|VCI|WBSearchBot|Web Downloader\\/6\\.9|Web Image Collector|WebAuto|WebBandit|WebCopier|WebEnhancer|WebmasterWorldForumBot|WebReaper|WebSauger|Website Quester|Webster Pro|WebStripper|WebZip|Wotbox|wsr\\-agent|WWW\\-Collector\\-E|Xenu|Zao|Zeus|ZyBORG|coccoc|Incutio|lmspider|memoryBot|SemrushBot|serf|Unknown|uptime files/i', $this->request->getHeader('User-Agent'), "User-Agent")) &&
				($this->match(BVWPFW::XSSREGEX, $this->request->getHeader('User-Agent'), "User-Agent"))) or
				(($this->match('/semalt\\.com|kambasoft\\.com|savetubevideo\\.com|buttons\\-for\\-website\\.com|sharebutton\\.net|soundfrost\\.org|srecorder\\.com|softomix\\.com|softomix\\.net|myprintscreen\\.com|joinandplay\\.me|fbfreegifts\\.com|openmediasoft\\.com|zazagames\\.org|extener\\.org|openfrost\\.com|openfrost\\.net|googlsucks\\.com|best\\-seo\\-offer\\.com|buttons\\-for\\-your\\-website\\.com|www\\.Get\\-Free\\-Traffic\\-Now\\.com|best\\-seo\\-solution\\.com|buy\\-cheap\\-online\\.info|site3\\.free\\-share\\-buttons\\.com|webmaster\\-traffic\\.co/i', $this->request->getHeader('Referer'), "Referer")) &&
				($this->match(BVWPFW::XSSREGEX, $this->request->getHeader('User-Agent'), "User-Agent")))) {
				$this->request->updateRulesInfo(145, $this->currRuleInfo);
				if ($isProtect) return true;
			}
		}
		if (isset($rules[146])) {
			$this->currRuleInfo = array();
			if ($this->match('/sitemap_.*?<.*?(:?_\\d+)?\\.xml(:?\\.gz)?/i', $this->request->getPath())) {
				$this->request->updateRulesInfo(146, $this->currRuleInfo);
				if ($isProtect) return true;
			}
		}
		if (isset($rules[155])) {
			$this->currRuleInfo = array();
			if (($this->match(BVWPFW::XSSREGEX, $this->request->getHeader('Client-IP'), "Client-IP")) or
				($this->match(BVWPFW::XSSREGEX, $this->request->getHeader('X-Forwarded'), "X-Forwarded")) or
				($this->match(BVWPFW::XSSREGEX, $this->request->getHeader('X-Cluster-Client-IP'), "X-Cluster-Client-IP")) or
				($this->match(BVWPFW::XSSREGEX, $this->request->getHeader('Forwarded-For'), "Forwarded-For")) or
				($this->match(BVWPFW::XSSREGEX, $this->request->getHeader('Forwarded'), "Forwarded"))) {
				$this->request->updateRulesInfo(155, $this->currRuleInfo);
				if ($isProtect) return true;
			}
		}
		if (isset($rules[156])) {
			$this->currRuleInfo = array();
			if ($this->match('#/wp\\-admin/admin\\-ajax\\.php$#i', $this->getServerValue('SCRIPT_FILENAME')) and
				(($this->match(BVWPFW::SQLIREGEX, $this->request->getBody('umm_user'), "umm_user")) or
				($this->match(BVWPFW::SQLIREGEX, $this->request->getQueryString('umm_user'), "umm_user")))) {
				$this->request->updateRulesInfo(156, $this->currRuleInfo);
				if ($isProtect) return true;
			}
		}
		if (isset($rules[165])) {
			$this->currRuleInfo = array();
			if ($this->match('/O:\\d+:"(?!stdClass")[^"]+":/', $this->request->getCookies('ecwid_oauth_state'), "ecwid_oauth_state")) {
				$this->request->updateRulesInfo(165, $this->currRuleInfo);
				if ($isProtect) return true;
			}
		}
		if (isset($rules[167])) {
			$this->currRuleInfo = array();
			if ((!$this->match('/\\.(jpe?g|png|mpeg|mov|flv|pdf|docx?|txt|csv|avi|mp3|wma|wav)($|\\.)/i', $this->request->getFileNames())) &&
				($this->getLength($this->request->getBody('save_bepro_listing')) > 0)) {
				$this->request->updateRulesInfo(167, $this->currRuleInfo);
				if ($isProtect) return true;
			}
		}
		if (isset($rules[168])) {
			$this->currRuleInfo = array();
			if (($this->match('#/wp\\-admin/admin\\-ajax\\.php$#i', $this->getServerValue('SCRIPT_FILENAME'))) &&
				($this->equals('master-slider', $this->request->getQueryString('page'))) &&
				($this->getLength($this->request->getBody('page')) > 0) &&
				($this->notEquals('master-slider', $this->request->getBody('page')))) {
				$this->request->updateRulesInfo(168, $this->currRuleInfo);
				if ($isProtect) return true;
			}
		}
		if (isset($rules[169])) {
			$this->currRuleInfo = array();
			if (($this->equals('fancybox-for-wordpress', $this->request->getQueryString('page'))) &&
				($this->match(BVWPFW::XSSREGEX, $this->request->getBody('mfbfw'), "mfbfw"))) {
				$this->request->updateRulesInfo(169, $this->currRuleInfo);
				if ($isProtect) return true;
			}
		}
		if (isset($rules[171])) {
			$this->currRuleInfo = array();
			if ((($this->match('#wp-json/wp/v\\d+/posts/#i', $this->request->getPath())) or
				($this->match('#/wp/v\\d+/posts/#i', $this->request->getQueryString('rest_route'), "rest_route"))) &&
				($this->match('/[^0-9]/', $this->request->getQueryString('id'), "id"))) {
				$this->request->updateRulesInfo(171, $this->currRuleInfo);
				if ($isProtect) return true;
			}
		}
		if (isset($rules[172])) {
			$this->currRuleInfo = array();
			$pattern = '`\b(?i:(?:conf(?:ig(?:ur(?:e|ation)|\.inc|_global)?)?)|settings?(?:\.?inc)?)\.php$`';
			if ((($this->match($pattern, $this->getServerValue('SCRIPT_FILENAME'), "SCRIPT_FILENAME")) or
				($this->match($pattern, $this->request->getQueryString(), "GET")))) {
				$this->request->updateRulesInfo(172, $this->currRuleInfo);
				if ($isProtect) return true;
			}
		}
		if (isset($rules[173])) {
			$this->currRuleInfo = array();
			$pattern = '`(?:\.{2}[\/]+)`';
			if ((($this->match($pattern, $this->request->getBody(), "BODY")) or
				($this->match($pattern, $this->request->getQueryString(), "GET")) or
				($this->match($pattern, $this->request->getCookies(), "COOKIE")) or
				($this->match($pattern, $this->request->getHeader('User-Agent'), "HEADER")))) {
				$this->request->updateRulesInfo(173, $this->currRuleInfo);
				if ($isProtect) return true;
			}
		}
		if (isset($rules[174])) {
			$this->currRuleInfo = array();
			$pattern = '`\\b(?:\\$?_(COOKIE|ENV|FILES|(?:GE|POS|REQUES)T|SE(RVER|SSION))|HTTP_(?:(?:POST|GET)_VARS|RAW_POST_DATA)|GLOBALS)\\s*[=\\[)]|\\W\\$\\{\\s*[\'"]\\w+[\'"]`';
			if ((($this->match($pattern, $this->request->getBody(), "BODY")) or
				($this->match($pattern, $this->request->getQueryString(), "GET")) or
				($this->match($pattern, $this->request->getCookies(), "COOKIE")) or
				($this->match($pattern, $this->request->getHeader('User-Agent'), "User-Agent")) or
				($this->match($pattern, $this->request->getHeader('Referer'), "Referer")) or
				($this->match($pattern, $this->getServerValue('PATH_INFO'), "PATH_INFO")))) {
				$this->request->updateRulesInfo(174, $this->currRuleInfo);
				if ($isProtect) return true;
			}
		}
		if (isset($rules[175])) {
			$this->currRuleInfo = array();
			$pattern = '`\\b(?i:eval)\\s*\\(\\s*(?i:base64_decode|exec|file_get_contents|gzinflate|passthru|shell_exec|stripslashes|system)\\s*\\(`';
			if ((($this->match($pattern, $this->request->getBody(), "BODY")) or
				($this->match($pattern, $this->request->getQueryString(), "GET")) or
				($this->match($pattern, $this->request->getCookies(), "COOKIE")) or
				($this->match($pattern, $this->request->getHeader('User-Agent'), "User-Agent")))) {
				$this->request->updateRulesInfo(175, $this->currRuleInfo);
				if ($isProtect) return true;
			}
		}
		if (isset($rules[176])) {
			$this->currRuleInfo = array();
			$pattern = '`(?:<\\?(?![Xx][Mm][Ll]).*?(?:\\$_?(?:COOKIE|ENV|FILES|GLOBALS|(?:GE|POS|REQUES)T|SE(RVER|SSION))\\s*[=\\[)]|\\b(?i:array_map|assert|base64_(?:de|en)code|curl_exec|eval|(?:ex|im)plode|file(?:_get_contents)?|fsockopen|function_exists|gzinflate|move_uploaded_file|passthru|[ep]reg_replace|phpinfo|stripslashes|strrev|substr|system|(?:shell_)?exec)\\s*(?:/\\*.+?\\*/\\s*)?\\())|#!/(?:usr|bin)/.+?\\s|\\W\\$\\{\\s*[\'"]\\w+[\'"]`';
			if ((($this->match($pattern, $this->request->getBody(), "BODY")) or
				($this->match($pattern, $this->request->getQueryString(), "GET")) or
				($this->match($pattern, $this->request->getCookies(), "COOKIE")) or
				($this->match($pattern, $this->request->getHeader('User-Agent'), "User-Agent")))) {
				$this->request->updateRulesInfo(176, $this->currRuleInfo);
				if ($isProtect) return true;
			}
		}
		if (isset($rules[177])) {
			$this->currRuleInfo = array();
			if ((($this->matchCount(BVWPFW::SQLIREGEX, $this->request->getBody()) > 2) or
				($this->matchCount(BVWPFW::SQLIREGEX, $this->request->getQueryString()) > 2) or
				($this->matchCount(BVWPFW::SQLIREGEX, $this->request->getCookies()) > 2) or
				($this->matchCount(BVWPFW::SQLIREGEX, $this->request->getHeader('User-Agent')) > 2))) {
				$this->request->updateRulesInfo(177, $this->currRuleInfo);
				if ($isProtect) return true;
			}
		}
		if (isset($rules[178])) {
			$this->currRuleInfo = array();
			$pattern = '`(?: \\W(?:background(-image)?|-moz-binding)\\s*:[^}]*?\\burl\\s*\\([^)]+?(https?:)?//\\w|<(?i:applet|div|embed|form|i?frame(?:set)?|i(?:mg|sindex)|link|m(?:eta|arquee)|object|script|textarea)\\b.*=.*?>|\\bdocument\\s*\\.\\s*(?:body|cookie|domain|location|open|write(?:ln)?)\\b|\\blocation\\s*\\.\\s*(?:href|replace)\\b|\\bwindow\\s*\\.\\s*(?:open|location)\\b|\\b(?:alert|confirm|eval|expression|prompt|set(?:Timeout|Interval)|String\\s*\\.\\s*fromCharCode|\\.\\s*substr)\\b\\s*\\(.*?\\)|(?i)<\\s*s\\s*t\\s*y\\s*l\\s*e\\b.*?>.*?<\\s*/\\s*s\\s*t\\s*y\\s*l\\s*e\\b.*?>|(?i)<[a-z].+?\\bon[a-z]{3,29}\\b\\s*=.{5}|(?i)<.+?\\bon[a-z]{3,29}\\b\\s*=\\s*[\'"](?!\\s*return false\\b).*?[\'"].+?>|(?i)<\\s*s\\s*c\\s*r\\s*i\\s*p\\s*t\\b.*?>.*?<\\s*/\\s*s\\s*c\\s*r\\s*i\\s*p\\s*t.*?>|<.+?(?i)\\b(?:href|(?:form)?action|background|code|data|location|name|poster|src|value)\\s*=\\s*[\'"]?(?:(?:f|ht)tps?:)?//\\w+\\.\\w|\\batob\\s*(?:[\'"\\x60]\\s*\\]\\s*)?\\(\\s*([\'"\\x60])[a-zA-Z0-9/+=]+\\1\\s*\\)|<.+?(?i)[a-z]+\\s*=.*?(?:java|vb)script:.+?> |<x:script\\b.*?>.*?</x:script.*?>|\\+A(?:Dw|ACIAPgA8)-.+?\\+AD4(?:APAAi)?-|[{}+[\\]\\s]\\+\\s*\\[\\s*]\\s*\\)\\s*\\[[{!}+[\\]\\s]|(?i)<[a-z]+/[a-z]+.+?=.+?>|\\[\\s*\\]\\s*\\[\\s*[\'"\\x60]filter[\'"\\x60]\\s*\\]\\s*\\[\\s*[\'"\\x60]constructor[\'"\\x60]\\s*\\]\\s*\\(\\s*|\\b(?:document|window|this)\\s*\\[.+?\\]\\s*[\\[(]|(?:(?:\\b(?:self|this|top|window)\\s*\\[.+?\\]|\\(\\s*(?:alert|confirm|eval|expression|prompt)\\s*\\)|\\[.*?\\]\\s*\\.\\s*find)|(?:\\.\\s*(?:re(?:ject|place)|constructor)))\\s*\\(.*?\\)|\\b(\\w+)\\s*=\\s*(?:alert|confirm|eval|expression|prompt)\\s*[;,]\\1\\s*\\(.*?\\))`';
			if ((($this->match($pattern, $this->request->getBody(), "BODY")) or
				($this->match($pattern, $this->request->getQueryString(), "GET")) or
				($this->match($pattern, $this->request->getCookies(), "COOKIE")) or
				($this->match($pattern, $this->request->getHeader('User-Agent'), "User-Agent")) or
				($this->match($pattern, $this->request->getHeader('Referer'), "Referer")))) {
				$this->request->updateRulesInfo(178, $this->currRuleInfo);
				if ($isProtect) return true;
			}
		}
		return false;
	}
}
endif;
