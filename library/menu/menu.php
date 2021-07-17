<?php
/**
 * Exit if accessed directly!
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'why though?' );
}


function wpbt_submitcontent_menu(){

    add_menu_page(
        __( 'Submit Content General Settings', 'submitcontent' ),
        __( 'Submit Content', 'submitcontent' ),
        'manage_options',
        'submitcontent',
        'wpbt_submitcontent_settings_page',
        'dashicons-admin-generic'
    );

    add_submenu_page(
        'submitcontent',
        __( 'Submit Content Form Settings', 'submitcontent' ),
        __( 'Form Settings', 'submitcontent' ),
        'manage_options',
        'sc-form-settings',
        'wpbt_submitcontent_form_settings_page'
    );
    
}


/**
 * Settings page callback
 */

function wpbt_submitcontent_settings_page(){

    // exit if user can not manage options!
    if( ! current_user_can( 'manage_options' ) ) exit;

    echo '<h1>' . esc_html( get_admin_page_title() ) . '</h1>';
}

function wpbt_submitcontent_form_settings_page(){

    // exit if user can not manage options!
    if( ! current_user_can( 'manage_options' ) ) exit;

    echo '<h1>' . esc_html( get_admin_page_title() ) . '</h1>';

    ?>
        <form action="" method="post" id="wpbt-sc-generator">
            <table class="form-table" role="presentation">
                <tbody>
                    <?php
                        generate_input_field( 'checkbox', 'add_form_heading', 'Form heading', 'Add the title for the form' );
                        generate_input_field( 'checkbox', 'add_form_description', 'Form description', 'Add the description for the form' );
                        generate_input_field( 'checkbox', 'add_post_title', 'Post title', 'Add the field for post title' );
                        generate_input_field( 'checkbox', 'add_post_content', 'Post content', 'Add the field for post content' );
                        generate_input_field( 'checkbox', 'add_post_featured_image', 'Featured image', 'Add the field for post featured image' );
                        generate_input_field( 'checkbox', 'add_post_categories', 'Post categories', 'Add the multi select field for post categories' );
                        generate_input_field( 'checkbox', 'add_post_tags', 'Post tags', 'Add the multi select field for post tags' );
                    ?>
                </tbody>
            </table>

            <p class="submit">
                <input class="button button-primary" type="submit" name="wpbt_sc_shortcode" value="<?php esc_attr_e( 'Generate Shortcode', 'submitcontent' ); ?>">
            </p>
        </form>
    <?php
}

