<?php

/**
 * Manages parts of a larger package
 *
 * A class definition that includes attributes and functions used to
 * build a package
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
class Js_Package_Part {

	/**
	 * Where the script begins in the script
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      int $start
	 */
	protected $start;

	/**
	 * Instance of Js_Package_Version
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Js_Package_Version $package_version
	 */
	public $package_version;

	/**
	 * Instance of Js_Package_Version
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Js_Package $package
	 */
	public $package;

	public function __construct( $start, $package, $package_version ) {
		$this->start = $start;
		$this->package = $package;
		$this->package_version = $package_version;
	}

	/**
	 * Search package versions and return an instance of this class if found.
	 *
	 * @param Js_Package $package
	 * @param $script
	 *
	 * @return Js_Package_Part|bool
	 */
	public static function get( $package, $script ) {
		$ver_and_pos = $package->find_in_script( $script );
		if( is_array( $ver_and_pos) ) {
			return new self( $ver_and_pos[0], $ver_and_pos[1] );
		}
		return false;
	}

	public function get_pos() {
		return $this->start;
	}
}