<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://profiles.wordpress.org/mbezuidenhout/
 * @since      1.0.0
 *
 * @package    Js_Package_Manager
 * @subpackage Js_Package_Manager/public/partials
 */
http_response_code( 200 );
$path     = $_SERVER['REQUEST_URI'];
$settings = get_option('jspkgmgr_settings');
$registered_scripts  = get_option('jspkgmgr_scripts');

$path = substr( $path, strlen( JS_PACKAGE_MANAGER_TEMPLATE_PATH) + 1 );
$path = substr( $path, 0, strpos( $path, '?' ) );

$registered_script_parts = $registered_scripts[$path]['parts'];

$table_name = $wpdb->base_prefix . JS_PACKAGE_MANAGER_TABLE;

$cached_scripts = $wpdb->get_results( "SELECT src,handle,name,version,pretxt,posttxt,fingerprint,scriptlen FROM ${table_name}");

function build_sorter($key) {
	return function ($a, $b) use ($key) {
		return strnatcmp($a[$key], $b[$key]);
	};
}

usort($registered_script_parts, build_sorter('start') );
$registered_script_parts = array_reverse( $registered_script_parts );

$output = file_get_contents( $registered_scripts[$path]['src'] );

foreach($registered_script_parts as $part) {
    if( !array_key_exists($part['id'], $settings ) ) {
        continue;
    }
    foreach( $cached_scripts as $cached_script ) {
        if( $cached_script->handle ==  $part['id'] && $cached_script->version == $part['ver'] ) {
            $package_version = $cached_script;
        }
        if( $cached_script->handle == $part['id'] && $cached_script->version == $settings[$part['id']]) {
            $replacement_version = $cached_script;
        }
    }

    $output = substr( $output, 0, $part['start'] ) . file_get_contents($replacement_version->src ) . substr( $output, $part['start'] + $package_version->scriptlen );
}

echo $output;

?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
