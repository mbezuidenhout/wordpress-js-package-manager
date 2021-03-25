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
	protected $path;

	public function __construct ( $path ) {
		$this->path = $path;
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
		$settings = get_option('jspkgmgr_settings');
		$scripts  = get_option('jspkgmgr_scripts');
		if ( array_key_exists( $script->handle, $scripts ) ) {
			foreach( $scripts[ $script->handle ]['parts'] as $parts ) {
				if( array_key_exists( $parts['id'], $settings ) && !empty( $settings[ $parts['id'] ] ) ) {
					return new self( $script->handle );
				}
			}
		}
		return false;
	}

	public static function get_by_path( $path ) {
		if( strpos( $path, JS_PACKAGE_MANAGER_TEMPLATE_PATH ) === 0 ) {
			$path = substr( $path, strlen( JS_PACKAGE_MANAGER_TEMPLATE_PATH) + 1 );
			$path = substr( $path, 0, strpos( $path, '?' ) );
			$scripts = get_option( 'jspkgmgr_scripts' );
			if ( array_key_exists( $path, $scripts ) ) {
				return new self( $path );
			}
		}
		return false;
	}

	public function get_template() {
		return plugin_dir_path( __DIR__ ) . 'public/partials/js-package-manager-public-display.php';
	}

	public function get_path() {
		return JS_PACKAGE_MANAGER_TEMPLATE_PATH . '/' . $this->path;
	}
}