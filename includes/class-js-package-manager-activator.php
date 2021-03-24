<?php

/**
 * Fired during plugin activation
 *
 * @link       https://profiles.wordpress.org/mbezuidenhout/
 * @since      1.0.0
 *
 * @package    Js_Package_Manager
 * @subpackage Js_Package_Manager/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Js_Package_Manager
 * @subpackage Js_Package_Manager/includes
 * @author     Marius Bezuidenhout <marius dot bezuidenhout at gmail dot com>
 */
class Js_Package_Manager_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();

		$table_name = $wpdb->base_prefix . JS_PACKAGE_MANAGER_TABLE;

		$sql = "CREATE TABLE ${table_name} (
    version_id bigint(20) unsigned NOT NULL auto_increment,
	src varchar(2048) NOT NULL,
	version varchar(255) NOT NULL,
	pretxt varchar(12) NOT NULL,
	posttxt varchar(12) NOT NULL,
	fingerprint varchar(34) NOT NULL,
	scriptlen int NOT NULL,
	PRIMARY KEY (version_id),
	KEY src (src)
) ${charset_collate};";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}

}
