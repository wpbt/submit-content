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
 * Generate and display an input field for the form
 * 
 * @param string $type  form field type 
 * @param string $name form field name 
 * @param string $title form field title
 * @param string $value form field value
 * @param srray $taxonomy multiple form fields
 * @return void  
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
 * Validates and Generates a response
 * 
 * @param array @form Form data to velidate
 * @return array @response Contains errors and data as keys
 */

function wpbt_submitcontent_validate_form( $form ){
    $errors = [];
    $data = [];


    if( empty( $form ) || empty( $form['options'] ) ) {
        $errors['empty_data'] = __( 'no data passed', 'submitcontent' );
        return $errors;
    }
    if( ! wp_verify_nonce( $form['options']['wpbt_sc_nonce'], 'wpbtsc' ) ){
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
        $errors['add_post_title'] = __( 'to accept content, at least post title should be enabled', 'submitcontent' );
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

/**
 * Generates a options' list
 * 
 * @param array $options Options array
 * @return string echoes out lists built from options array
 */

function wpbt_submitcontent_generate_options( $options ){
    if( $options && ! empty( $options ) ){

        $form_title = ( $options['add_form_heading'] ) ? $options['add_form_heading'] : '';
        $form_title_text = ( $options['add_form_heading_text'] ) ? $options['add_form_heading_text'] : '';

        $form_description = ( $options['add_form_description'] ) ? $options['add_form_description'] : '';
        $form_description_text = ( $options['add_form_description_text'] ) ? $options['add_form_description_text'] : '';

        $post_title = ( $options['add_post_title'] ) ? $options['add_post_title'] : '';
        $post_content = ( $options['add_post_content'] ) ? $options['add_post_content'] : '';
        $featured_img = ( $options['add_post_featured_image'] ) ? $options['add_post_featured_image'] : '';

        echo '<ul>';
        if( $form_title ){
            ?>
                <li><?php _e( 'Add form title: yes', 'submitcontent' ); ?></li>
                <li><?php printf( esc_html__( 'Form title: %s', 'submitcontent' ), $form_title_text ); ?></li>
            <?php
        } else {
            ?>
                <li><?php _e( 'Add form title: no', 'submitcontent' ); ?></li>
            <?php 
        }

        if( $form_description ){
            ?>
                <li><?php _e( 'Add form description: yes', 'submitcontent' ); ?></li>
                <li><?php printf( esc_html__( 'Form description: %s', 'submitcontent' ), $form_description_text ); ?></li>
            <?php
        } else {
            ?>
                <li><?php _e( 'Add form description: no', 'submitcontent' ); ?></li>
            <?php 
        }

        if( $post_title ){
            ?>
                <li><?php _e( 'Allow post title: yes', 'submitcontent' ); ?></li>
            <?php
        }

        if( $post_content ){
            ?>
                <li><?php _e( 'Allow post content: yes', 'submitcontent' ); ?></li>
            <?php
        } else {
            ?>
                <li><?php _e( 'Allow post content: no', 'submitcontent' ); ?></li>
            <?php 
        }

        if( $featured_img ){
            ?>
                <li><?php _e( 'Set featured image: yes', 'submitcontent' ); ?></li>
            <?php
        } else {
            ?>
                <li><?php _e( 'Set featured image: no', 'submitcontent' ); ?></li>
            <?php 
        }

        if( isset( $options['category'] ) && !empty( $options['category'] ) ){
            ?>
                <li><?php _e( 'Allowed categorie(s):', 'submitcontent' ); ?></li>
            <?php 
            echo '<ul>';
            foreach( $options['category'] as $category ){
                ?>
                    <li><?php _e( $category['name'], 'submitcontent' ); ?></li>
                <?php
            }
            echo '</ul>';
        } else {
            ?>
                <li><?php _e( 'Allowed categorie(s): none', 'submitcontent' ); ?></li>
            <?php 
        }

        if( isset( $options['tag'] ) && !empty( $options['tag'] ) ){
            ?>
                <li><?php _e( 'Allowed tag(s):', 'submitcontent' ); ?></li>
            <?php 
            echo '<ul>';
            foreach( $options['tag'] as $tag ){
                ?>
                    <li><?php _e( $tag['name'], 'submitcontent' ); ?></li>
                <?php
            }
            echo '</ul>';
        } else {
            ?>
                <li><?php _e( 'Allowed tag(s): none', 'submitcontent' ); ?></li>
            <?php 
        }
        echo '</ul>';

    }
}