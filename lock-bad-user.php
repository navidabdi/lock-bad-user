<?php
/**
 * Plugin Name: Lock Bad User
 * Plugin URI: https://wordpress.org/plugins/lock-bad-user/
 * Description: With this plugin you can block bad users without deleting their accounts.
 * Version: 1.0.0
 * Author: Nabi Abdi
 * Author URI: http://Webkima.com
 * Domain Path: /languages/
 * Tested up to: 5.8
 * Text Domain: lock-bad-user
 *
 *
 * @package LockBadUser
 *
 * LockBadUser is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * LockBadUser is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 */

declare(strict_types=1);

namespace Webkima\LockBadUser;

if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}


# Define constants
define('LOCK_BAD_USER_PLUGIN_BASE_URL', plugins_url('',  __FILE__ ));
define('LOCK_BAD_USER_PLUGIN_BASE_PATH', plugin_dir_path( __FILE__ ));

if (!class_exists(LockUser::class) && is_readable(__DIR__ . '/vendor/autoload.php')) {
  require_once __DIR__ . '/vendor/autoload.php';
}
class_exists(LockUser::class) && LockUser::instance();

