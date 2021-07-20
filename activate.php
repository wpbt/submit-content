<?php
/**
 * Exit if accessed directly!
 */
if ( ! defined( 'ABSPATH' ) ) {
	die( 'why though?' );
}

function wpbt_submitcontent_activate(){
    global $wp_version;

    /**
     * Check WordPress version for compatibality
     */
    if ( version_compare( $wp_version, '4.9', '<' ) ) {
        wp_die( 'This plugin requires WordPress version 4.9 or higher.' );
    }

}

/**
 * create database table
 */

function wpbt_submitcontent_create_table(){
    global $wpdb;

    $table_name = $wpdb->prefix . 'submitcontent';
    $charset_coallate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        shortcode_name text NOT NULL,
        options varchar(255) NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_coallate";

    // require WordPress dbDelta() function
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}