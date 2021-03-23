<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://profiles.wordpress.org/mbezuidenhout/
 * @since             1.0.0
 * @package           Js_Package_Manager
 *
 * @wordpress-plugin
 * Plugin Name:       JS Package Manager
 * Plugin URI:        https://github.com/mbezuidenhout/wordpress-js-package-manager
 * Description:       Manage JavaScript packages of all your WordPress plugins. Improve the security of your site by replacing JavaScript packages with the version of your choice.
 * Version:           1.0.0
 * Tested up to:      5.7.0
 * Author:            Marius Bezuidenhout
 * Author URI:        https://profiles.wordpress.org/mbezuidenhout/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       js-package-manager
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'JS_PACKAGE_MANAGER_VERSION', '1.0.0' );

/**
 * The plugin file used in WordPress
 */
define( 'JS_PACKAGE_MANAGER_PLUGIN_FILE', __FILE__ );

/**
 * The template path where this plugin will intervene
 */
define( 'JS_PACKAGE_MANAGER_TEMPLATE_PATH', '/jspkgmgr' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-js-package-manager-activator.php
 */
function activate_js_package_manager() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-js-package-manager-activator.php';
	Js_Package_Manager_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-js-package-manager-deactivator.php
 */
function deactivate_js_package_manager() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-js-package-manager-deactivator.php';
	Js_Package_Manager_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_js_package_manager' );
register_deactivation_hook( __FILE__, 'deactivate_js_package_manager' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-js-package-manager.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_js_package_manager() {

	$plugin = new Js_Package_Manager();
	$plugin->run();

}
run_js_package_manager();
