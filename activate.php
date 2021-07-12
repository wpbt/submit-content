<?php
/**
 * Exit if accessed directly!
 */
if ( ! defined( 'ABSPATH' ) ) {
	die( 'why though?' );
}

function wpbt_submitcontent_activate(){
    global $wp_version;
    if ( version_compare( $wp_version, '4.9', '<' ) ) {
        wp_die( 'This plugin requires WordPress version 4.9 or higher.' );
    }
}