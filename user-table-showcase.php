<?php

/**
 * Plugin Name: User Table Showcase
 * Plugin URI:
 * Description: A WordPress plugin that showcases the user data from a third party API into a table.
 * Version: 1.0.0
 * Author: Nabi Abdi
 * Author URI:
 * License: MIT
 * Text Domain: user-table-showcase
 * Domain Path: languages
 */

declare(strict_types=1);

namespace Inpsyde\UserTableShowcase;

if (!class_exists(UserTableShowcase::class) && is_readable(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

add_action('plugins_loaded', static function () {
    $plugin = UserTableShowcase::instance();
    $plugin->init();
});
