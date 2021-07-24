<?php

/**
 * Exit if accessed directly!
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'why though?' );
}


/**
 * [submitcontent] callback
 * @param int $id Shortcode id
 * @return String $html outputs shortcode (form)
 */

function submitcontent_shortcode_callback( $atts, $content = NULL ){

    $defaults = [ 'id' => '' ];
    $atts = shortcode_atts( $defaults, $atts );

    ob_start();

    return ob_get_clean();
}