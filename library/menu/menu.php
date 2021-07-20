<?php
/**
 * Exit if accessed directly!
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'why though?' );
}


function wpbt_submitcontent_menu(){

    add_menu_page(
        __( 'Submit Content Settings', 'submitcontent' ),
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

    echo '<h1>' . esc_html__( get_admin_page_title(), 'submitcontent' ) . '</h1>';
    ?>
        <form action="options.php" method="post">
            <?php
                // output security fields for the registered setting "wporg_options"
                settings_fields( 'submitcontent_options' );
                do_settings_sections( 'submitcontent' );
                submit_button( __( 'Save Settings', 'submitcontent' ) );
            ?>
        </form>
    <?php
}

function wpbt_submitcontent_form_settings_page(){

    // exit if user can not manage options!
    if( ! current_user_can( 'manage_options' ) ) exit;

    echo '<h1>' . esc_html__( get_admin_page_title(), 'submitcontent' ) . '</h1>';

    ?>
        <form action="" method="post" id="wpbt-sc-generator">
            <table class="form-table" role="presentation">
                <tbody>
                    <?php
                        generate_input_field( 'checkbox', 'add_form_heading', 'Form heading', 'Add the title for the form', 1 );
                        generate_input_field( 'text', 'add_form_heading_class', '', 'Add the class for form heading field (optional)' );
                        generate_input_field( 'checkbox', 'add_form_description', 'Form description', 'Add the description for the form', 1 );
                        generate_input_field( 'text', 'add_form_description_class', '', 'Add the class for form description field (optional)' );
                        generate_input_field( 'checkbox', 'add_post_title', 'Post title', 'Add the field for post title', 1 );
                        generate_input_field( 'text', 'add_post_title_class', '', 'Add the class for post title field (optional)' );
                        generate_input_field( 'checkbox', 'add_post_content', 'Post content', 'Add the field for post content', 1 );
                        generate_input_field( 'text', 'add_post_content_class', '', 'Add the class for post content field (optional)' );
                        generate_input_field( 'checkbox', 'add_post_featured_image', 'Featured image', 'Add the field for post featured image', 1 );
                        generate_input_field( 'text', 'add_post_featured_image_class', '', 'Add the class for featured image field (optional)' );
                        generate_input_field( 'checkbox', 'add_post_categories', 'Post categories', 'Add the multi select field for post categories', 1 );
                        generate_input_field( 'text', 'add_post_categories_class', '', 'Add the class for post categories field (optional)' );
                        generate_input_field( 'checkbox', 'add_post_tags', 'Post tags', 'Add the multi select field for post tags', 1 );
                        generate_input_field( 'text', 'add_post_tags_class', '', 'Add the class for post tag field (optional)' );
                    ?>
                </tbody>
            </table>

            <p class="submit">
                <input class="button button-primary" type="submit" name="wpbt_sc_shortcode" value="<?php esc_attr_e( 'Generate Shortcode', 'submitcontent' ); ?>">
            </p>
        </form>
    <?php
}

