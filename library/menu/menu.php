<?php
/**
 * Exit if accessed directly!
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'why though?' );
}


function wpbt_submitcontent_menu(){

    add_menu_page(
        __( 'Submit Content Settings', 'submitcontent' ),
        __( 'Submit Content', 'submitcontent' ),
        'manage_options',
        'submitcontent',
        'wpbt_submit_content_settings_page',
        'dashicons-admin-generic'
    );
    
}


/**
 * Settings page callback
 */

function wpbt_submit_content_settings_page(){

    // exit if user can not manage options!
    if( ! current_user_can( 'manage_options' ) ) exit;

    echo '<h1>' . esc_html( get_admin_page_title() ) . '</h1>';
    
}