<?php

/**
 * functions used thourghout the plugin!
 */

/**
 * Exit if accessed directly!
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'why though?' );
}

/**
 * Generate an input field for the form!
 */

function generate_input_field( $type, $name, $title, $value = '', $taxonomy = NULL ){
    if( $type == 'textarea' ){
        echo "
            <tr class='". esc_attr( $name ) ."'>
                <th scope='row'><label for='". esc_attr( $name ) ."'>". esc_html__( $title, 'submitcontent' ) ."</label></th>
                <td>
                    <label for='". esc_attr( $name ) ."'>
                        <textarea name='". esc_attr( $name ) ."' id='". esc_attr( $name ) ."' rows='6' cols='40'>". esc_attr( $value ) ."</textarea> 
                    </label>
                </td>
            </tr>
        ";
    } elseif( $type == 'notice' ){
        $message = $title . ' not supported for selected post type!';
        echo "
            <tr>
                <th scope='row'>". esc_html__( $title, 'submitcontent' ) ."</th>
                <td>
                    <p>". esc_html__( $message, 'submitcontent' ) ."</p>
                </td>
            </tr>
        ";
    } elseif( $taxonomy ) {
        echo "
            <tr class='". esc_attr( $name ) ."'>
            <th scope='row'><label for='". esc_attr( $value ) ."'>". esc_html__( $title, 'submitcontent' ) ."</label></th>
                <td>
                    <label for='". esc_attr( $name ) ."'>
                    <input name='". esc_attr( $taxonomy['type'] ) ."' type='$type' id='". esc_attr( $value ) ."' value='". esc_attr( $value ) ."' label='". esc_attr( $name ) ."' />
                    </label>
                </td>
            </tr>
        ";
    } else {
        echo "
            <tr class='". esc_attr( $name ) ."'>
            <th scope='row'><label for='". esc_attr( $name ) ."'>". esc_html__( $title, 'submitcontent' ) ."</label></th>
                <td>
                    <label for='". esc_attr( $name ) ."'>
                    <input name='". esc_attr( $name ) ."' type='$type' id='". esc_attr( $name ) ."' value='". esc_attr( $value ) ."' />
                    </label>
                </td>
            </tr>
        ";
    }
}

/**
 * Validate form data
 */

function wpbt_submitcontent_validate_form( $form ){
    $errors = [];
    $data = [];
    $nonce = wp_create_nonce( 'wpbtsc' );

    if( empty( $form ) || empty( $form['options'] ) ) {
        $errors['empty_data'] = __( 'no data passed', 'submitcontent' );
        return $errors;
    }
    if( wp_verify_nonce( $nonce, $form['options']['wpbt_sc_nonce'] ) ){
        $errors['invalid_nonce'] = __( 'invalid nonce', 'submitcontent' );
        return $errors;
    }

    // create variales.
    $form_title = ( $form['options']['add_form_heading'] ) ? $form['options']['add_form_heading'] : '';
    $for_title_text = ( $form['options']['add_form_heading_text'] ) ? $form['options']['add_form_heading_text'] : '';
    $form_description = ( $form['options']['add_form_description'] ) ? $form['options']['add_form_description'] : '';
    $form_description_text = ( $form['options']['add_form_description_text'] ) ? $form['options']['add_form_description_text'] : '';

    $post_title = ( $form['options']['add_post_title'] ) ? $form['options']['add_post_title'] : '';
    $data['add_post_content'] = ( $form['options']['add_post_content'] ) ? $form['options']['add_post_content'] : '';
    $data['add_post_featured_image'] = ( $form['options']['add_post_featured_image'] ) ? $form['options']['add_post_featured_image'] : '';

    // validate and sanitize form heading
    if( $form_title == 1 ){
        if( ! $for_title_text ){
            $errors['add_form_heading_text'] = __( 'missing form heading', 'submitcontent' );
        } else {
            $data['add_form_heading'] = '1';
            $data['add_form_heading_text'] = __( sanitize_text_field( $for_title_text ), 'submitcontent' );
        }
    } else {
        $data['add_form_heading'] = '';
        $data['add_form_heading_text'] = '';
    }

    // validate and sanitize form description
    if( $form_description == 1 ){
        if( ! $form_description_text ){
            $errors['add_form_description_text'] = __( 'missing form description', 'submitcontent' );
        } else {
            $data['add_form_description'] = '1';
            $data['add_form_description_text'] = __( sanitize_text_field( $form_description_text ), 'submitcontent' );
        }
    } else {
        $data['add_form_description'] = '';
        $data['add_form_description_text'] = '';
    }

    // validate post title
    if( $post_title != '1' ){
        $data['add_post_title'] = '';
        $errors['add_post_title'] = __( 'post title should be enabled', 'submitcontent' );
    } else {
        $data['add_post_title'] = '1';
    }

    // category
    if( isset( $form['options']['category'] ) && ! empty(  $form['options']['category'] ) ){
        $data['category'] = $form['options']['category'];
    } else {
        $data['category'] = [];
    }

    // tag
    if( isset( $form['options']['tag'] ) && ! empty(  $form['options']['tag'] ) ){
        $data['tag'] = $form['options']['tag'];
    } else {
        $data['tag'] = [];
    }

    return [
        'errors' => $errors,
        'data' => $data
    ];

}
