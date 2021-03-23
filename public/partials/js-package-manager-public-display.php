<?php

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
$handle = fopen( ABSPATH . '/wp-includes/js/jquery/jquery.min.js', 'r' );
fpassthru( $handle );
fclose( $handle );
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
