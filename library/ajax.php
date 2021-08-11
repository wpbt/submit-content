<?php

/**
 * Exit if accessed directly!
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'why though?' );
}

/**
 * AJAX handler to generate and save shortcode
 */

function wpbt_generate_shortcode_ajax_callback(){
    global $wpdb;
    $result = wpbtsc_validate_admin_form( $_POST );
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
    $result = $wpdb->query( 
                    $wpdb->prepare(
                        "INSERT INTO $table_name (shortcode_name, options) VALUES (%s, %s)",
                        $shortcode_name,
                        $shortcode_options
                    )
                );

    if( $result ){
        $shortcode_name = '[submitcontent id="'. $wpdb->insert_id .'"]';
        $update = $wpdb->query( 
                        $wpdb->prepare(
                            "UPDATE $table_name SET shortcode_name=%s WHERE id=%d",
                            $shortcode_name,
                            $wpdb->insert_id
                        )
                    );

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

/**
 * AJAX handler to delete shortcode
 */

function wpbt_delete_shortcode_callback(){

    global $wpdb;

    if( ! wp_verify_nonce( $_POST['securityKey'], 'wpbt_delete_sc' ) ){
        $response = [
            'type' => 'error',
            'data' => __( 'invalid nonce!', 'submitcontent' )
        ];
        wp_send_json( $response );
    } 
    
    // delete the record!

    $table_name = $wpdb->prefix . 'submitcontent';
    $row_id = ( $_POST['id'] ) ? $_POST['id'] : '';

    if( ! $row_id ){
        $response = [ 'type' => 'error', 'data' => __( 'empty shortcode id', 'submitcontent' ) ];
        wp_send_json( $response );
    }

    $result = $wpdb->delete(
        $table_name,
        [ 'id' => $row_id ],
        [ '%d' ]
    );
    if( $result ){
        $menuUrl = get_admin_url() . 'admin.php?page=sc-form-settings';
        $tableEmptyMessage = "<tr class='no-shortcodes'>
                                <td colspan='4'>
                                    <p>". __( 'You haven\'t created any shortcodes yet!', 'submitcontent' ) . "</p>
                                    <p>".
                                        __( 'to create shortcodes, visit: ', 'submitcontent' )
                                        ."<a href='" . $menuUrl ."'>". __( 'Create Shortcodes', 'submitcontent' ) ."</a>
                                    </p>
                                </td>
                            </tr>";
        $response = [
            'type' => 'success',
            'data' => [
                'rowid' => $row_id,
                'message' => __( 'shortcode deleted successfully', 'submitcontent' ),
                'tableEmpty' => $tableEmptyMessage
            ]
        ];
        wp_send_json( $response );
    }
    
}

/**
 * AJAX handler to process user form submission
 */

function wpbtsc_form_submission(){

    $result = wpbtsc_validate_public_form( [
        'wpbtsc_featured_img' => $_FILES['wpbtsc_featured_img'],
        'form_data' => $_POST
    ] );

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

    wp_send_json( $data );
    
}