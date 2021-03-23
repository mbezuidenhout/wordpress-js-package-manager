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
	protected $start;
	public $package;

	public function __construct( $start, $package ) {
		$this->start = $start;
		$this->package = $package;
	}
}