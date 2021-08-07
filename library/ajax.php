<?php

/**
 * Exit if accessed directly!
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'why though?' );
}

function wpbt_generate_shortcode_ajax_callback(){
    global $wpdb;
    $result = wpbt_submitcontent_validate_form( $_POST );
    $errors = $result['errors'];
    $data = $result['data'];

    // handle errors!
    if( ! empty( $errors ) ){
        $response = [
            'data' => $errors,
            'type' => 'error'
        ];
        wp_send_json( $response );
    }

    // handle save
    $table_name = $wpdb->prefix . 'submitcontent';
    $shortcode_options = '';
    $id = '';

    if( ! is_serialized( $data ) ){
        $shortcode_options = maybe_serialize( $data );
    }
    
    $shortcode_name = '[submitcontent id="'. $id .'"]';
    $sql = "INSERT INTO $table_name (shortcode_name, options) VALUES (%s, %s)";
    $sql_query = $wpdb->prepare( $sql, $shortcode_name, $shortcode_options );
    $result = $wpdb->query( $sql_query );
    if( $result ){
        $shortcode_name = '[submitcontent id="'. $wpdb->insert_id .'"]';
        $update_sql = $wpdb->prepare( "UPDATE $table_name SET shortcode_name=%s WHERE id=%d", $shortcode_name, $wpdb->insert_id );
        $update = $wpdb->query( $update_sql );
        if( $update ){
            $response = [
                'data' => $shortcode_name,
                'type' => 'success'
            ];
            wp_send_json( $response );
        }
    } else {
        $response = [
            'data' => $result,
            'type' => 'error'
        ];
        wp_send_json( $response );
    }
}

function wpbt_delete_shortcode_callback(){

    global $wpdb;

    if( ! wp_verify_nonce( $_POST['securityKey'], 'wpbt_delete_sc' ) ){
        $response = [
            'type' => 'error',
            'data' => __( 'invalid nonce!', 'submitcontent' )
        ];
        wp_send_json( $response );
    } 
    wp_send_json( $_POST );
    
}