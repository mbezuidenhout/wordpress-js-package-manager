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
	protected $start;
	protected $length;
	protected $identifier;
	protected $friendly_name;
	protected $version;
	protected $fingerprint;
	protected $end;

	public function __construct( $identifier, $friendly_name, $version, $filename ) {
		$this->identifier = $identifier;
		$this->friendly_name = $friendly_name;
		$this->version = $version;
		$file_content = file_get_contents( $filename );
		$this->fingerprint = md5( $file_content );
		$this->length = strlen( $file_content );
		$this->start = substr( $file_content, 0, 10 );
		$this->end = substr( $file_content, -10 );
	}

	/**
	 * Returns the position of the package in the string or false if not found
	 *
	 * @param $string
	 * @return bool
	 */
	public function pos( $string ) {
		if ( strlen( $string) > $this->length &&
		     strpos ( $string, $this->start ) !== false ) {
			$start = strpos ( $string, $this->start );
			$string = substr( $string, $start, $this->length);
			if ( strpos( $this->end, substr( $string, -10 ) ) === 0 && $this->fingerprint == md5( $string ) ) {
				return strpos ( $string, $this->start );
			}
		}
		return false;
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
	 * Returns the script length
	 *
	 * @return int
	 */
	public function get_len() {
		return $this->length;
	}

	/**
	 * Returns the script version
	 *
	 * @return string
	 */
	public function get_ver() {
		return $this->version;
	}

	/**
	 * Returns the script's friendly name
	 *
	 * @return mixed
	 */
	public function get_name() {
		return $this->friendly_name;
	}
}