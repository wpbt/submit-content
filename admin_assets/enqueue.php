<?php

/**
 * Exit if accessed directly!
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'why though?' );
}

/**
 * Enqueue scripts in the WordPress admin on plugin settings pages!
 *
 * @param String $hook current page slug.
 * @return Null 
 */

function wpbt_submitcontent_admin_scripts( $hook ){

    if( 
        ( 'toplevel_page_submitcontent' == $hook )
        ||
        ( 'submit-content_page_sc-form-settings' == $hook )
    ){
        // style
        wp_register_style( 'wpbt-sc-admin-style', SUBMIT_CONTENT_DIRECTORY_URL . 'admin_assets/css/admin-styles.css', [], '', true );
        wp_enqueue_style( 'wpbt-sc-admin-style' );
        // script
        wp_register_script( 'wpbt-sc-admin-script', SUBMIT_CONTENT_DIRECTORY_URL . 'admin_assets/js/admin-scripts.js', [], '', true );
        wp_localize_script( 'wpbt-sc-admin-script', 'scJSOBJ', [ 'ajax_url' => admin_url( 'admin-ajax.php' ) ] );
        wp_enqueue_script( 'wpbt-sc-admin-script' );
    }

}