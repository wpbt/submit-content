<?php

/**
 * Exit if accessed directly!
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'why though?' );
}

function wpbt_generate_shortcode_ajax_callback(){
    
    $result = wpbt_submitcontent_validate_form( $_POST );
    print_r( $result );
    wp_die();
}