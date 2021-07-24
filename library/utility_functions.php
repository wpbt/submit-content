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

    print_r( $form['options'] );
    // foreach( $form['options'] as $key => $value ){
    //     if( $key == '')
    // }

}
