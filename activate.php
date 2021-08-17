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

    /**
     * install default options
     */

    $defaults = [
        'wpbtsc_saveas' => 'post',
        'wpbtsc_default_status' => 'draft',
        'wpbtsc_send_admin_email' => '1',
        'wpbtsc_requires_login' => '1',
        'wpbtsc_recaptcha_sitekey' => '',
        'wpbtsc_recaptcha_secretkey' => '',
        'wpbtsc_email_override' => '',
    ];
    /**
     * prevent automatic update of options during deactivation and re-activation!
     */
    if( ! get_option( 'submitcontent_options' ) ){
        add_option( 'submitcontent_options', $defaults );
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
        shortcode_name varchar(255) NOT NULL,
        options text NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_coallate";

    // require WordPress dbDelta() function
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}