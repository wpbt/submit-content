<?php

/**
 * Exit if accessed directly!
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'why though?' );
}

function wpbt_generate_shortcode_ajax_callback(){
    
    /**
     * This callback handles two types of AJAX requets
     * 1. Field Change ( $_POST['type'] == 'fieldchange' )
     * 2. Form Submit ( $_POST['type'] == 'formsubmit' )
     */

    if( ( $_POST['type'] == 'fieldchange' ) ){
        $key = $_POST['input_key'];
        $value = $_POST['input_value'];
        
    } elseif( ( $_POST['type'] == 'formsubmit' ) ){
        
    }
    

    wp_die();
}