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

    add_submenu_page(
        'submitcontent',
        __( 'Manage Shortcodes', 'submitcontent' ),
        __( 'Shortcodes', 'submitcontent' ),
        'manage_options',
        'sc-shortcodes',
        'wpbt_submitcontent_shortcodes_page'
    );
    
}


/**
 * Settings page callback
 */

function wpbt_submitcontent_settings_page(){

    // exit if user can not manage options!
    if( ! current_user_can( 'manage_options' ) ) exit;

    echo '<h1>' . esc_html__( get_admin_page_title(), 'submitcontent' ) . '</h1>';

    if( isset( $_GET['settings-updated'] ) ){
        add_settings_error( 
            'submitcontent',
            'submitcontent',
            __( 'options updated', 'submitcontent' ),
            'success' 
        );
    }
    settings_errors( 'submitcontent' );

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

    $sc_options = get_option( 'submitcontent_options' );
    $wpbtsc_saveas = $sc_options['wpbtsc_saveas'];
    $supported_taxonomies = get_object_taxonomies( $wpbtsc_saveas, 'object' );
    $categories = [];
    $tags = [];
    foreach( $supported_taxonomies as $taxonomy ){
        if( $taxonomy->hierarchical ){
            $category_arr = [
                'slug' => $taxonomy->name,
                'name' => $taxonomy->label
            ];
            array_push( $categories, $category_arr );
        } elseif( ! $taxonomy->hierarchical ) {
            // skipping post_format types for post_type = 'post'
            if( $taxonomy->name == 'post_format' ) continue;
            $tag_arr = [
                'slug' => $taxonomy->name,
                'name' => $taxonomy->label 
            ];
            array_push( $tags, $tag_arr );
        }
    }

    ?>
        <form action="" method="post" id="wpbt-sc-generator">
            <table class="form-table" role="presentation">
                <tbody>
                    <?php
                        // generate security field!
                        generate_input_field( 'hidden', 'wpbt_sc_nonce', '', wp_create_nonce( 'wpbtsc' ) );
                        generate_input_field( 'checkbox', 'add_form_heading', 'Form heading', 1 );
                        generate_input_field( 'text', 'add_form_heading_text', 'Heading text', '' );
                        generate_input_field( 'checkbox', 'add_form_description', 'Form description', 1 );
                        generate_input_field( 'textarea', 'add_form_description_text', 'Description text', '' );

                        if( post_type_supports( $wpbtsc_saveas, 'title' ) ){
                            generate_input_field( 'checkbox', 'add_post_title', 'Post title', 1 );
                        } else {
                            generate_input_field( 'notice', 'notice', 'Post title', 'not supported for selected post type' );
                        }

                        if( post_type_supports( $wpbtsc_saveas, 'editor' ) ){
                            generate_input_field( 'checkbox', 'add_post_content', 'Post content', 1 );
                        } else {
                            generate_input_field( 'notice', 'notice', 'Post content', 'not supported for selected post type' );
                        }

                        if( post_type_supports( $wpbtsc_saveas, 'thumbnail' ) ){
                            generate_input_field( 'checkbox', 'add_post_featured_image', 'Featured image', 1 );
                        } else {
                            generate_input_field( 'notice', 'notice', 'Featured image', 'not supported for selected post type' );
                        }

                        if( !empty( $categories ) ){
                            foreach( $categories as $category ){
                                $name = $category['name'];
                                $slug = $category['slug'];
                                // this value is translated in generate_input_field() function.
                                $content = 'Allow users to add ' . $wpbtsc_saveas . ' ' . $category['name'];
                                generate_input_field( 'checkbox', $name, $content, $slug,  [ 'type' => 'category' ] );
                            }
                        }

                        if( !empty( $tags ) ){
                            foreach( $tags as $tag ){
                                $name = $tag['name'];
                                $slug = $tag['slug'];
                                // this value is translated in generate_input_field() function.
                                $content = 'Allow users to add ' . $wpbtsc_saveas . ' ' . $tag['name'];
                                generate_input_field( 'checkbox', $name, $content, $slug,  [ 'type' => 'tag' ] );
                            }
                        }

                    ?>
                </tbody>
            </table>

            <p class="submit">
                <input class="button button-primary" type="submit" name="wpbt_sc_shortcode" value="<?php esc_attr_e( 'Generate Shortcode', 'submitcontent' ); ?>">
            </p>
        </form>
    <?php
}

function wpbt_submitcontent_shortcodes_page(){
    global $wpdb;
    // exit if user can not manage options!
    if( ! current_user_can( 'manage_options' ) ) exit;

    echo '<h1>' . esc_html__( get_admin_page_title(), 'submitcontent' ) . '</h1>';

    /**
     * Querying the database for displaying shortcodes.
     */

    $table_name = $wpdb->prefix . 'submitcontent';
    $shortcodes = $wpdb->get_results( "SELECT id, shortcode_name, options FROM $table_name" );
    
    ?>
        <table class="sc-table">
            <thead>
                <tr>
                    <th><?php _e( 'S.N.', 'submitcontent' ); ?></th>
                    <th><?php _e( 'Shortcode', 'submitcontent' ); ?></th>
                    <th><?php _e( 'Options', 'submitcontent' ); ?></th>
                    <th><?php _e( 'Action', 'submitcontent' ); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if( ! empty( $shortcodes ) ):
                        $count = 1;
                        foreach( $shortcodes as $shortcode ){
                            $options = maybe_unserialize( $shortcode->options );
                            ?>
                                <tr id="<?php echo esc_attr( $shortcode->id ); ?>">
                                    <td class="sc-sn"><?php _e( $count, 'submitcontent' ); ?></td>
                                    <td class="wpbtsc-copy"><?php echo $shortcode->shortcode_name; ?></td>
                                    <td><?php wpbt_submitcontent_generate_options( $options ); ?></td>
                                    <td>
                                        <a 
                                            nonceKey="<?php echo wp_create_nonce( 'wpbt_delete_sc' ); ?>"
                                            class="wpbt-delete-sc button button-primary"
                                            href="#" scid="<?php echo esc_attr( $shortcode->id ); ?>"
                                        >
                                            <?php _e( 'Delete', 'submitcontent' ); ?>
                                        </a>
                                    </td>
                                </tr> 
                            <?php
                            $count++;
                        }
                    else:
                        ?>
                            <tr class="no-shortcodes">
                                <td colspan="4">
                                    <p><?php _e( 'You haven\'t created any shortcodes yet!', 'submitcontent' ); ?></p>
                                    <p>
                                        <?php _e( 'to create shortcodes, visit: ', 'submitcontent' ); ?>
                                        <a href="<?php menu_page_url( 'sc-form-settings', true ); ?>"><?php _e( 'Create Shortcodes', 'submitcontent' ); ?></a>
                                    </p>
                                </td>
                            </tr>
                        <?php
                    endif;
                ?>
            </tbody>
        </table>
    <?php
}