<?php

/**
 * Exit if accessed directly!
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'why though?' );
}

/**
 * Enqueue a script in the WordPress admin on plugin setting page!
 *
 * @param int $hook current page slug.
 */

function wpbt_submitcontent_admin_scripts( $hook ){
    if( 'toplevel_page_submitcontent' !== $hook ) return;

    wp_register_style( 'wpbt-sc-admin-style', SUBMIT_CONTENT_DIRECTORY_URL . 'admin_assets/css/admin-styles.css', [], true );
    wp_enqueue_style( 'wpbt-sc-admin-style' );

    wp_register_script( 'wpbt-sc-admin-script', SUBMIT_CONTENT_DIRECTORY_URL . 'admin_assets/js/admin-scripts.js', [ 'jquery' ], true );
    wp_enqueue_script( 'wpbt-sc-admin-script' );
}