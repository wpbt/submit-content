<?php

/**
 * Exit if accessed directly!
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'why though?' );
}

/**
 * Enqueue scripts in the frontend
 */

function wpbtsc_public_scripts(){

    // style
    wp_register_style( 'wpbtsc-public-style', SUBMIT_CONTENT_DIRECTORY_URL . 'public_assets/css/public-styles.css' );
    wp_enqueue_style( 'wpbtsc-public-style' );
    // script
    wp_register_script( 'wpbtsc-public-script', SUBMIT_CONTENT_DIRECTORY_URL . 'public_assets/js/public-scripts.js', [ 'jquery' ], '', true );
    wp_localize_script(
        'wpbtsc-public-script',
        'scJSOBJ',
        [
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'error_heading' => sprintf( '<h4>%s!</h4>', __( 'please fix following errors', 'submitcontent' ) )
        ]
    );
    wp_enqueue_script( 'wpbtsc-public-script' );

}