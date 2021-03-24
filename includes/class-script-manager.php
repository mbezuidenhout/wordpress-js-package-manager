<?php

/**
 * Manages a script script.
 *
 * Build a new script with managed versions
 *
 * @link       https://profiles.wordpress.org/mbezuidenhout/
 * @since      1.0.0
 *
 * @package    Js_Package_Manager
 * @subpackage Js_Package_Manager/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define JavaScript packages
 *
 * @since      1.0.0
 * @package    Js_Package_Manager
 * @subpackage Js_Package_Manager/includes
 * @author     Marius Bezuidenhout <marius dot bezuidenhout at gmail dot com>
 */
class Script_Manager {

	public function __construct () {

	}

	/**
	 * If this script is managed by the package manager then return a new instance of this class
	 *
	 * @param _WP_Dependency $script
	 * @return Script_Manager|bool
	 */
	public static function get_by_dependency( $script ) {
		if( !$script instanceof _WP_Dependency ) {
			return false;
		}
		return false;
		return new self( $script );
	}

	public static function get_by_path( $path ) {
		return false;
		return new self( $path );
	}

	public function output() {

	}

	public function get_template() {
		return plugin_dir_path( __DIR__ ) . 'public/partials/js-package-manager-public-display.php';
	}

	public function get_path() {
		return '/wp-includes/js/jquery/jquery.min.js';
	}
}