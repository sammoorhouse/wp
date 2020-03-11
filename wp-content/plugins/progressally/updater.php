<?php

if (!class_exists('ProgressAllyUpdater')) {
	class ProgressAllyUpdater {
		const PRODUCT_URL = 'https://accessally.com/';
		const UPDATE_SLUG = 'progressally';
		const UPDATE_URL = 'http://members.ambitionally.com/hosted_plugin/progressally/';
		const ICON_URL = 'https://s3.amazonaws.com/ambitionally/icons/progressally.png';
		const PLUGIN_READABLE_NAME = 'ProgressAlly';
		const PLUGIN_FUNCTION_SLUG = 'progressally';
		const CACHE_PERIOD = 86400;

		const LATEST_UPDATE_CACHE = 'progressally_latest_update';
		const PLUGIN_ERROR_MESSAGE_CACHE = 'progressally_error_message';
		const PLUGIN_ADMIN_MESSAGE_STATUS = 'progressally_admin_message';

		private static $plugin_slug = null;
		private static $admin_messages = array();
		private static $current_version = null;

		public static function init() {
			self::add_actions();
			self::add_filters();
			self::$plugin_slug = basename(dirname(__FILE__)) . '/progressally.php';
			self::$current_version = ProgressAlly::VERSION;
		}

		private static function get_license_info() {
			return ProgressAllySettingLicense::get_license_settings();
		}

		private static function process_serial_status($is_valid) {
			ProgressAllySettingLicense::set_enable_setting($is_valid);
		}

		public static function do_activation_actions() {
			self::clean_database();
		}

		public static function do_deactivation_actions() {
			self::clean_database();
		}

		public static function clean_database() {
			delete_option(self::PLUGIN_ERROR_MESSAGE_CACHE);
			delete_option(self::PLUGIN_ADMIN_MESSAGE_STATUS);
			delete_option(self::LATEST_UPDATE_CACHE);
		}
		private static function add_actions() {
			if (is_admin()) {
				add_action('admin_init', array(__CLASS__, 'auto_check_update_message'));
				add_action('admin_notices', array(__CLASS__, 'show_admin_message'));
				add_action('wp_ajax_' . self::PLUGIN_FUNCTION_SLUG . '_admin_notice_close', array(__CLASS__, 'process_admin_notice_close'));
			}

			add_action('wp_ajax_nopriv_' . self::PLUGIN_FUNCTION_SLUG . '_update_license', array(__CLASS__, 'ajax_force_license_update'));

			// schedule update version check
			add_action(self::PLUGIN_FUNCTION_SLUG . '_check_update_event', array(__CLASS__ ,'check_update_periodically'));
			if (!wp_next_scheduled(self::PLUGIN_FUNCTION_SLUG . '_check_update_event')) {
				wp_schedule_event(current_time('timestamp'), 'daily', self::PLUGIN_FUNCTION_SLUG . '_check_update_event');
			}
		}

		public static function add_filters() {
			// auto update
			add_filter('plugins_api', array(__CLASS__, 'get_plugin_info'), 10, 3);
			add_filter('pre_set_site_transient_update_plugins', array(__CLASS__, 'force_check_update'));
			add_filter('transient_update_plugins', array(__CLASS__, 'check_update'));
			add_filter('site_transient_update_plugins', array(__CLASS__, 'check_update'));
		}

		// <editor-fold defaultstate="collapsed" desc="Collect additional info to send">
		private static function collect_additional_info() {
			try {
				$data = array(
					'objective' => ProgressAllyPostObjective::get_objective_stats(),
					'completed' => ProgressAllyUserProgress::get_user_objective_completion_stats()
				);

				return $data;
			} catch (Exception $e) {
				return array();
			}
		}
		// </editor-fold>

		// <editor-fold defaultstate="collapsed" desc="Auto update">
		public static function force_check_update($transient = false) {
			if (false !== $transient) {
				if (is_array($transient)) {
					if (!isset($transient['checked'])) {
						return $transient;
					}
					if (isset($transient['response']) && isset($transient['response'][self::$plugin_slug])) {
						return $transient;
					}
				} elseif (is_object($transient)) {
					if (!property_exists($transient, 'checked')) {
						return $transient;
					}
					if (property_exists($transient, 'response')) {
						if (isset($transient->response[self::$plugin_slug])) {
							return $transient;
						}
					}
				}
			}
			$update_info = self::get_plugin_update(true);
			if (!$update_info) {
				return $transient;
			}
			$remote_version = $update_info['version'];
			if (version_compare(self::$current_version, $remote_version, '<')) {
				$license = self::get_license_info();
				$obj = new stdClass();
				$obj->slug = self::UPDATE_SLUG;
				$obj->new_version = $remote_version;
				$obj->url = self::PRODUCT_URL;
				$obj->package = esc_url_raw(add_query_arg(array('email' => $license['email'], 'serial' => $license['serial'], 'action' => 'download'), self::UPDATE_URL));
				$obj->plugin = self::$plugin_slug;
				$obj->icons = array('1x' => self::ICON_URL);
				if (isset($update_info['tested'])) {
					$obj->tested = $update_info['tested'];
				}
				$transient->response[self::$plugin_slug] = $obj;
			}
			return $transient;
		}

		public static function check_update($transient) {
			$update_info = self::get_plugin_update();
			if (!$update_info) {
				return $transient;
			}
			$remote_version = $update_info['version'];
			if (version_compare(self::$current_version, $remote_version, '<')) {
				$license = self::get_license_info();
				$obj = new stdClass();
				$obj->slug = self::UPDATE_SLUG;
				$obj->new_version = $remote_version;
				$obj->url = self::PRODUCT_URL;
				$obj->package = esc_url_raw(add_query_arg(array('email' => $license['email'], 'serial' => $license['serial'], 'action' => 'download'), self::UPDATE_URL));
				$obj->plugin = self::$plugin_slug;
				$obj->icons = array('1x' => self::ICON_URL);
				if (isset($update_info['tested'])) {
					$obj->tested = $update_info['tested'];
				}
				$transient->response[self::$plugin_slug] = $obj;
			}
			return $transient;
		}

		public static function check_update_periodically() {
			if (defined('WP_INSTALLING'))
				return false;
			self::get_plugin_update();
		}

		public static function ajax_force_license_update() {
			$update_result = self::get_plugin_update(true);
			echo serialize($update_result);
			die();
		}

		public static function auto_check_update_message() {
			$error_message = get_option(self::PLUGIN_ERROR_MESSAGE_CACHE, false);
			if (is_array($error_message)) {
				if (isset($error_message['message'])) {
					self::$admin_messages []= $error_message;
				}
				return;
			}
			$update_info = self::get_plugin_update();
			if (is_array($update_info)) {
				$messages = $update_info['message'];
				if (is_array($messages)) {
					foreach ($messages as $message) {
						if (is_array($message)) {
							self::$admin_messages [] = $message;
						}
					}
				}
				$remote_version = $update_info['version'];
				if (version_compare(self::$current_version, $remote_version, '<')) {
					if (is_multisite()) {
						$upgrade_url = admin_url() . 'network/update-core.php';
					} else {
						$upgrade_url = admin_url() . 'update-core.php';
					}
					$message = self::PLUGIN_READABLE_NAME . ' ' . $remote_version . ' is available! <a target="_blank" href="' . $upgrade_url . '">Please update now</a>.';
					self::$admin_messages [] = array('is-error' => false,
						'message' => $message,
						'duration' => 3,
						'slug' => 'system-update-' . $remote_version);
				}
			}
		}

		public static function process_admin_notice_close() {
			$message_status = get_option(self::PLUGIN_ADMIN_MESSAGE_STATUS, false);
			if (!is_array($message_status)) {
				$message_status = array();
			}
			$plug = $_POST['plug'];
			$duration = 30;
			if (isset($_POST['duration'])) {
				$duration = intval($_POST['duration']);
			}
			$now = time();
			$message_status[$plug] = $now + $duration * 86400;
			if (!add_option(self::PLUGIN_ADMIN_MESSAGE_STATUS, $message_status, '', 'no')) {
				update_option(self::PLUGIN_ADMIN_MESSAGE_STATUS, $message_status);
			}
		}
		const ERROR_MESSAGE_TEMPLATE_ALWAYS_SHOW = '<div style="padding:11px 15px;" class="{{class}}"><div>{{message}}</div></div>';
		const ERROR_MESSAGE_TEMPLATE_TEMPORARY_SHOW = '<div style="padding:11px 15px;" class="{{class}} {{plugin-slug}}-admin-notice"><div {{plugin-slug}}-admin-notice={{message-slug}} notice-duration={{duration}} style="float:right;width:15px;height:15px;color:#e4e4e4;border:1px solid #e4e4e4;line-height:15px;font-size:12px;text-align:center;cursor:pointer;">&#x2715;</div><div style="margin-right:25px;">{{message}}</div></div>';
		public static function show_admin_message() {
			$notice_status = null;
			$now = 0;
			foreach (self::$admin_messages as $message) {
				if ($message['duration'] <= 0) {
					$code = self::ERROR_MESSAGE_TEMPLATE_ALWAYS_SHOW;
				} else {
					if ($notice_status === null) {
						$now = time();
						$notice_status = get_option(self::PLUGIN_ADMIN_MESSAGE_STATUS);
						if (!is_array($notice_status)) {
							$notice_status = array();
						}
					}
					if (isset($notice_status[$message['slug']])) {
						if ($notice_status[$message['slug']] > $now) {
							continue;
						}
					}
					$code = self::ERROR_MESSAGE_TEMPLATE_TEMPORARY_SHOW;
					$code = str_replace('{{plugin-slug}}', self::UPDATE_SLUG, $code);
					$code = str_replace('{{message-slug}}', $message['slug'], $code);
					$code = str_replace('{{duration}}', $message['duration'], $code);
				}
				if ($message['is-error']) {
					$class = 'error';
				} else {
					$class = 'update-nag';
				}
				$code = str_replace('{{message}}', $message['message'], $code);
				$code = str_replace('{{class}}', $class, $code);
				echo $code;
			}
		}

		public static function get_plugin_update($force = false, $license = false) {
			$update_data = get_option(self::LATEST_UPDATE_CACHE, false);
			$now = time();
			if ($force || !is_array($update_data) || $update_data['time'] < $now) {
				$update_info = self::get_remote_information('update', $license);
				if ($update_info === false) {
					$update_data = array('time' => $now + self::CACHE_PERIOD, 'info' => 0);
					update_option(self::LATEST_UPDATE_CACHE, $update_data);
					return false;
				}
				foreach ($update_info['message'] as $message_id => $message) {
					$update_info['message'][$message_id] = array('is-error' => false,
						'message' => $message,
						'duration' => 5,
						'slug' => md5($message));
				}
				$update_data = array('time' => $now + self::CACHE_PERIOD, 'info' => $update_info);
				update_option(self::LATEST_UPDATE_CACHE, $update_data);
			}
			$update_info = $update_data['info'];
			if (0 === $update_info) {
				return false;
			}
			return $update_info;
		}

		public static function get_plugin_info($false, $action, $arg) {
			if (property_exists($arg, 'slug') && $arg->slug === self::UPDATE_SLUG) {
				$information = self::get_remote_information('info');
				if (false === $information) {
					return false;
				}
				return $information['info'];
			}
			return $false;
		}

		public static function get_remote_information($action, $license = false) {
			if (false === $license) {
				$license = self::get_license_info();
			}
			if (!ProgressAllySettingLicense::$progressally_enabled) {
				// do not ping the update server if the plugin is uninitialized to avoid unnecessary error message
				if (empty($license['email']) && empty($license['serial'])) {
					return false;
				}
			}
			$additional_info = self::collect_additional_info();
			$response = wp_remote_post(self::UPDATE_URL, array(
                'method' => 'POST',
                'timeout' => 70,
                'redirection' => 5,
                'httpversion' => '1.0',
                'blocking' => true,
                'headers' => array(),
                'cookies' => array(),
				'body' => array(
					'action' => $action,
					'email' => $license['email'],
					'serial' => $license['serial'],
					'siteurl' => get_bloginfo('wpurl'),
					'info' => $additional_info
					)
				)
			);
			if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) {
				$message = array('is-error' => true,
					'message' => self::PLUGIN_READABLE_NAME . ": Error while checking for update. Can't reach update server.",
					'duration' => -1,
					'slug' => 'system-critical');
				update_option(self::PLUGIN_ERROR_MESSAGE_CACHE, $message);
				return false;
			}
			$result = unserialize($response['body']);
			if (isset($result['error'])) {
				$message = array('is-error' => true,
					'message' => self::PLUGIN_READABLE_NAME . ': ' . $result['error'],
					'duration' => -1,
					'slug' => 'system-error');
				if (isset($result['error-code']) && $result['error-code'] > 900) {
					self::process_serial_status(false);
				} else {
					$message['duration'] = 30;
					self::process_serial_status(true);
				}
				update_option(self::PLUGIN_ERROR_MESSAGE_CACHE, $message);
				return false;
			}
			self::process_serial_status(true);
			delete_option(self::PLUGIN_ERROR_MESSAGE_CACHE);
			return $result;
		}
		// </editor-fold>
	}
	ProgressAllyUpdater::init();
}