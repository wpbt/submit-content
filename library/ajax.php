<?php

/**
 * Exit if accessed directly!
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'why though?' );
}

function wpbt_generate_shortcode_ajax_callback(){
    
    if( ! empty( $_POST ) ){
        wpbt_submitcontent_validate_form( $_POST );
        // print_r( $result );
    } else {
        echo 'nothing selected';
    }

    /**
     * Algorithm for ajax
     * form data available
     * 1. validate data
     * 2. if errors,
     *      display to users.
     * 3. if success
     *      add the shortcode options to database
     * 4. return response.
     */
    
    wp_die();
}