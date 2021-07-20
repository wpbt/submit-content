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
 * @param String $type Type of the form field
 * @param String $name class and id of the form field
 * @param String $title Field title
 * @param String $content Field content
 * @return String Returns the field in table row structure.
 */

function generate_input_field( $type, $name, $title, $content, $value = '' ){
    echo "
        <tr class='". esc_attr( $name ) ."'>
            <th scope='row'>". esc_html__( $title, 'submitcontent' ) ."</th>
            <td>
                <label for='". esc_attr( $name ) ."'>
                <input name='". esc_attr( $name ) ."' type='$type' id='". esc_attr( $name ) ."' value='". esc_attr( $value ) ."' />
                    ". esc_html__( $content, 'submitcontent' ) ."
                </label>
            </td>
        </tr>
    ";
}
