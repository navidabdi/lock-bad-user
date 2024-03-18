<?php
/**
 * Plugin Name: Lock Bad User
 * Plugin URI: https://wordpress.org/plugins/lock-bad-user/
 * Description: With this plugin you can block / lock bad users without deleting their accounts.
 * Version: 1.1.6
 * Author: ZedKima
 * Author URI: http://zedkima.com
 * Domain Path: /languages/
 * Tested up to: 6.4.3
 * PHP Version: 7.4
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
define('LOCK_BAD_USER_URL', trailingslashit(plugin_dir_url( __FILE__ )));
define('LOCK_BAD_USER_PATH', trailingslashit(plugin_dir_path( __FILE__ )));

//register_activation_hook(__FILE__, __NAMESPACE__ . '\Schema::activate');
//register_deactivation_hook(__FILE__, __NAMESPACE__ . '\Schema::deactivate');
//register_uninstall_hook(__FILE__, __NAMESPACE__ . '\Schema::uninstall');

if (!class_exists(LockUser::class) && is_readable(__DIR__ . '/vendor/autoload.php')) {
  require_once __DIR__ . '/vendor/autoload.php';
}
class_exists(LockUser::class) && LockUser::instance();

