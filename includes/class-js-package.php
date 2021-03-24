<?php

/**
 * The file that defines a JavaScript package
 *
 * A class definition that includes attributes and functions used to
 * define a JavaScript package
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
class Js_Package {
	protected $identifier;

	/**
	 * Package name
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $friendly_name
	 */
	protected $friendly_name;

	/**
	 * Package versions
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Js_Package_Version[] $versions   Array of packages versions
	 */
	protected $versions;

	/**
	 * Js_Package constructor.
	 *
	 * @param $identifier
	 * @param $friendly_name
	 */
	public function __construct( $identifier, $friendly_name ) {
		$this->identifier = $identifier;
		$this->friendly_name = $friendly_name;
		$this->versions = array();
	}

	public function add( $version, $filename = null, $load_from_cache = true ) {
		$this->versions[ $version ] = new Js_Package_Version( $version, $this, $filename, $load_from_cache );
	}

	/**
	 * Returns the package's unique identifier.
	 *
	 * @return string
	 */
	public function get_id() {
		return $this->identifier;
	}

	/**
	 * Returns the script's friendly name
	 *
	 * @return mixed
	 */
	public function get_name() {
		return $this->friendly_name;
	}


	/**
	 * Returns a sorted array of package versions
	 *
	 * @return array
	 */
	public function get_versions() {
		$versions = array();
		foreach( $this->versions as $version ) {
			$versions[] = $version->get_ver();
		}
		usort( $versions, 'version_compare' );
		return array_reverse( $versions );
	}

	/**
	 * Match each version of the package against the script and return it's position if found.
	 *
	 * @param string $script
	 *
	 * @return Js_Package_Part|bool
	 */
	public function find_in_script( $script ) {
		foreach( $this->versions as $version ) {
			$part = $version->find_in_script( $script );
			if( $part instanceof Js_Package_Part) {
				return $part;
			}
		}
		return false;
	}
}