<?php

/**
 * The file that defines a JavaScript package versions
 *
 * A class definition that includes attributes and functions used to
 * define a version of a JavaScript package
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
class Js_Package_Version {
	protected $version;
	protected $start;
	protected $end;
	protected $fingerprint;
	protected $length;
	protected $src;

	/**
	 * @var Js_Package $package
	 */
	public $package;

	public function __construct( $version, $package, $src = null, $load_from_cache = true ) {
		global $wpdb;
		$table_name = $wpdb->base_prefix . JS_PACKAGE_MANAGER_TABLE;
		$this->package = $package;
		$this->version = $version;
		if ( $src !== null ) {
			$in_cache = false;
			if( $load_from_cache ) {
				$cached_version = $wpdb->get_row("SELECT src, version, pretxt, posttxt, fingerprint, scriptlen FROM ${table_name} WHERE src = '${src}'");
				if( property_exists( $cached_version, 'src') && $cached_version->src == $src) {
					$this->set_props(
						$cached_version->pretxt,
						$cached_version->posttxt,
						$cached_version->scriptlen,
						$cached_version->fingerprint,
						$cached_version->src,
					);
					$in_cache = true;
				}
			}
			if( !$in_cache ) {
				$this->load_from_file( $src );
			}
		}
	}

	/**
	 * Set all the properties for this version of the package.
	 *
	 * @param string $start  First 10 characters of script
	 * @param string $end    Last 10 characters of script
	 * @param int    $length Length of script in bytes
	 * @param string $fingerprint MD5 fingerprint
	 * @param string $src    Script URL source
	 */
	public function set_props( $start, $end, $length, $fingerprint, $src = '' ) {
		$this->start       = $start;
		$this->end         = $end;
		$this->length      = $length;
		$this->fingerprint = $fingerprint;
		$this->src         = $src;
	}

	/**
	 * Load package from source
	 *
	 * @param $src
	 */
	public function load_from_file( $src ) {
		global $wpdb;
		$table_name = $wpdb->base_prefix . JS_PACKAGE_MANAGER_TABLE;
		$this->src         = $src;
		if ( filter_var( $src, FILTER_VALIDATE_URL ) ) {
			$response     = wp_remote_get( $src );
			$file_content = $response['body'];
		} else {
			$file_content = file_get_contents( $src );
		}
		$this->fingerprint = md5( $file_content );
		$this->length      = strlen( $file_content );
		$this->start       = substr( $file_content, 0, 10 );
		$this->end         = substr( $file_content, -10 );
		$wpdb->insert( $table_name, array(
			'handle'      => $this->package->get_id(),
			'name'        => $this->package->get_name(),
			'src'         => $src,
			'version'     => $this->get_ver(),
			'pretxt'      => $this->start,
			'posttxt'     => $this->end,
			'fingerprint' => $this->fingerprint,
			'scriptlen'   => $this->length,
		) );
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
	 * Return position in script
	 *
	 * @param string $script
	 *
	 * @return Js_Package_Part|bool
	 */
	public function find_in_script( $script ) {
		if (strlen( $script ) < $this->length ) {
			return false;
		}
		$pos = $this->pos($script);
		if ( $pos !== false ) {
			return new Js_Package_Part( $pos, $this->package, $this );
		}
		return false;
	}

	/**
	 * Returns the position of the package in the string or false if not found
	 *
	 * @param $string
	 * @return int|bool
	 */
	public function pos( $string ) {
		if ( strlen( $string ) > $this->length ) {
			$start = strpos ( $string, $this->start );
			if( $start !== false ) {
				$string = substr( $string, $start, $this->length );
				if ( strpos( $this->end, substr( $string, -10 ) ) === 0 && $this->fingerprint == md5( $string ) ) {
					return $start; // We have found a match
				}
			}
		}
		return false;
	}

	public function get_len() {
		return $this->length;
	}
}