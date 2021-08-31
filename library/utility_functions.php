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
 * Load plugin textdomain
 */

function wpbtsc_load_plugin_textdomain(){
    load_plugin_textdomain(
        'submit-content',
        false,
        SUBMIT_CONTENT_DIRECTORY . 'languages'
    );
}

/**
 * Generate and display an input field for the form
 * 
 * @param string $type  form field type 
 * @param string $name form field name 
 * @param string $title form field title
 * @param string $value form field value
 * @param array $taxonomy multiple form fields
 * @return void  
 */

function wpbtsc_generate_input_field( $type, $name, $title, $value = '', $taxonomy = NULL ){
    if( $type == 'textarea' ){
        echo "
            <tr class='". esc_attr( $name ) ."'>
                <th scope='row'><label for='". esc_attr( $name ) ."'>". esc_html__( $title, 'submit-content' ) ."</label></th>
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
                <th scope='row'>". esc_html__( $title, 'submit-content' ) ."</th>
                <td>
                    <p>". esc_html__( $message, 'submit-content' ) ."</p>
                </td>
            </tr>
        ";
    } elseif( $taxonomy ) {
        echo "
            <tr class='". esc_attr( $name ) ."'>
            <th scope='row'><label for='". esc_attr( $value ) ."'>". esc_html__( $title, 'submit-content' ) ."</label></th>
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
            <th scope='row'><label for='". esc_attr( $name ) ."'>". esc_html__( $title, 'submit-content' ) ."</label></th>
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

function wpbtsc_validate_admin_form( $form ){

    $errors = [];
    $data = [];

    if( empty( $form ) || empty( $form['options'] ) ) {
        $errors['empty_data'] = __( 'no data passed', 'submit-content' );
        return [
            'errors' => $errors,
            'data' => $data
        ];
    }
    
    if( ! wp_verify_nonce( $form['options']['wpbt_sc_nonce'], 'wpbtsc' ) ){
        $errors['invalid_nonce'] = __( 'invalid nonce', 'submit-content' );
        return [
            'errors' => $errors,
            'data' => $data
        ];
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
            $errors['add_form_heading_text'] = __( 'missing form heading', 'submit-content' );
        } else {
            $data['add_form_heading'] = '1';
            $data['add_form_heading_text'] = __( sanitize_text_field( $for_title_text ), 'submit-content' );
        }
    } else {
        $data['add_form_heading'] = '';
        $data['add_form_heading_text'] = '';
    }

    // validate and sanitize form description
    if( $form_description == 1 ){
        if( ! $form_description_text ){
            $errors['add_form_description_text'] = __( 'missing form description', 'submit-content' );
        } else {
            $data['add_form_description'] = '1';
            $data['add_form_description_text'] = __( sanitize_textarea_field( $form_description_text ), 'submit-content' );
        }
    } else {
        $data['add_form_description'] = '';
        $data['add_form_description_text'] = '';
    }

    // validate post title
    if( $post_title != '1' ){
        $data['add_post_title'] = '';
        $errors['add_post_title'] = __( 'to accept content, at least post title should be enabled', 'submit-content' );
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
 * Validates and Generates a response
 * 
 * @param array @form Form data to velidate
 * @return array @response Contains errors and data as keys
 */

function wpbtsc_validate_public_form( $form ){

    $errors = [];
    $data = [];
    $wpbtsc_options = get_option( 'submitcontent_options' );

    // nonce validation
    if( ! wp_verify_nonce( $form['form_data']['sc_security_id'], 'wpbtsc_form_input' ) ){
        $errors['invalid_nonce'] = __( 'invalid nonce', 'submit-content' );
        return [
            'errors' => $errors,
            'data' => $data
        ];
    }

    // validate reCAPTCHA
    $token = ( $form['form_data']['wpbtsc_token'] ) ? trim( $form['form_data']['wpbtsc_token'] ) : '';

    if( $token ){
        $secret_key = $wpbtsc_options['wpbtsc_recaptcha_secretkey'];
        $user_address = $_SERVER['REMOTE_ADDR'];
        $recaptcha_verify_url = 'https://www.google.com/recaptcha/api/siteverify';
        $args = [
            'body' => [
                'secret' => $secret_key,
                'response' => $token,
                'remoteip' => $user_address,
            ]
        ];
        if( $secret_key ){
            $recaptcha_response = wp_remote_post( $recaptcha_verify_url, $args );
            $response_body = wp_remote_retrieve_body( $recaptcha_response );
            $recaptcha_status = json_decode( $response_body, true );
            $score = apply_filters( 'wpbtsc_score_threshold', 0.5 );

            if(
                ! $recaptcha_status['success'] ||
                ( $recaptcha_status['score'] < $score ) ||
                ( $recaptcha_status['action'] != 'submitcontent' )
            ){
                $errors['recaptcha_status'] = sprintf( __( '%s reCAPTCHA', 'submit-content' ), 'invalid' );
            }
        }
    }

    $post_title = ( $form['form_data']['wpbtsc_posttitle'] ) ? sanitize_text_field( $form['form_data']['wpbtsc_posttitle'] ) : '';
    $post_content = ( $form['form_data']['wpbtsc_postcontent'] ) ? sanitize_textarea_field( $form['form_data']['wpbtsc_postcontent'] ) : '';

    // title and content
    if( array_key_exists( 'wpbtsc_posttitle', $form['form_data'] ) ){
        if( $post_title ){
            if( strlen( trim( $post_title ) ) < $wpbtsc_options['wpbtsc_posttitle_length'] ){
                $errors['post_title'] = sprintf( __( 'post title should be at least %d characters long', 'submit-content' ), $wpbtsc_options['wpbtsc_posttitle_length'] );
            } else {
                $data['post_title'] = __( $post_title, 'submit-content' );
            }
        } else {
            $errors['post_title'] = __( 'post title is required', 'submit-content' );
        }
    }
    if( array_key_exists( 'wpbtsc_postcontent', $form['form_data'] ) ){
        if( $post_content ){
            if( strlen( trim( $post_content ) ) < $wpbtsc_options['wpbtsc_content_length'] ){
                $errors['post_content'] = sprintf( __( 'post content should be at least %d characters long', 'submit-content' ), $wpbtsc_options['wpbtsc_content_length'] );
            } else {
                $data['post_content'] = __( $post_content, 'submit-content' );
            }
        } else {
            $errors['post_content'] = __( 'post content is required', 'submit-content' );
        }
    }

    // categories and tags
    foreach( $form['form_data'] as $key => $value ){
        if( gettype( $value ) == 'array' ){
            $data[$key] = $value;
        }
    }
    /**
     * file (image) handling
     */
    if ( is_null( $form['wpbtsc_featured_img'] ) ){
        return [
            'errors' => $errors,
            'data' => $data
        ];
    } else {
        $image_info = $form['wpbtsc_featured_img'];
        $image_upload_error = $image_info['error'];
        if( $image_upload_error == '1' ){
            $errors['file_size'] = sprintf( __( 'this host does not allow file of the current size. Please reduce the file size to %01.1f Mb', 'submit-content' ), $wpbtsc_options['wpbtsc_max_image_size'] );
        } else {
            $image_name = ( $image_info['name'] ) ? sanitize_file_name( $image_info['name'] ) : '';
            $image_type = ( $image_info['type'] ) ? $image_info['type'] : '';
            $temp_image_location = ( $image_info['tmp_name'] ) ? $image_info['tmp_name'] : '';
            $image_size = ( $image_info['size'] ) ? $image_info['size'] : '';
            $supported_file_types = [ 
                'jpg',
                'jpeg',
                'jpe',
                'png',
                'pdf',
                'webpp',
                'doc',
                'tiff',
                'tif'
            ];
            $allowed_file_types = apply_filters( 'wpbtsc_supported_filetypes', $supported_file_types );
            // size
            if( $image_name && $image_size ){
                $filesize_mb = fdiv( $image_size, pow( 1024, 2 ) );
                if( $filesize_mb > $wpbtsc_options['wpbtsc_max_image_size'] ){
                    $errors['file_size'] = sprintf( __( 'file should be smaller than or equal to %01.1f Mb', 'submit-content' ), $wpbtsc_options['wpbtsc_max_image_size'] );
                }
            }
            // name and type
            if( $image_name ){
                $is_mime_allowed = wp_check_filetype( $image_name );
                if(
                    isset( $is_mime_allowed['ext'] ) &&
                    in_array( strtolower( $is_mime_allowed['ext'] ), $allowed_file_types
                )
                ){
                    $data['featured_image'] = [
                        'error' => $image_info['error'],
                        'name' => $image_name,
                        'size' => $image_size,
                        'tmp_name' => $temp_image_location,
                        'type' => $image_type
                    ];
                } else {
                    $errors['unsupported_file_type'] = __( 'unsupported file type', 'submit-content' );
                }
            } else {
                $errors['featured_image'] = __( 'featured image is required', 'submit-content' );
            }
            /**
             * file (image) handling end
             */
        }
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
 * @return void
 */

function wpbtsc_generate_options( $options ){
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
                <li><?php printf( '%s: <span class="sc-success-badge">%s</span>', __( 'Add form title', 'submit-content' ), __( 'yes', 'submitcontent' ) ); ?></li>
                <li><?php printf( esc_html__( 'Form title: %s', 'submit-content' ), $form_title_text ); ?></li>
            <?php
        } else {
            ?>
                <li><?php printf( '%s: <span class="sc-error-badge">%s</span>', __( 'Add form title', 'submit-content' ), __( 'no', 'submit-content' ) ); ?></li>
            <?php 
        }

        if( $form_description ){
            ?>
                <li><?php printf( '%s: <span class="sc-success-badge">%s</span>', esc_html__( 'Add form description', 'submit-content' ), esc_html__( 'yes', 'submit-content' ) ); ?></li>
                <li><?php printf( __( 'Form description: %s', 'submit-content' ), $form_description_text ); ?></li>
            <?php
        } else {
            ?>
                <li><?php printf( '%s: <span class="sc-error-badge">%s</span>', esc_html__( 'Add form description', 'submit-content' ), esc_html__( 'no', 'submit-content' ) ); ?></li>
            <?php 
        }

        if( $post_title ){
            ?>
                <li><?php printf( '%s: <span class="sc-success-badge">%s</span>', esc_html__( 'Allow post title', 'submit-content' ), esc_html__( 'yes', 'submit-content' ) ); ?></li>
            <?php
        }

        if( $post_content ){
            ?>
                <li><?php printf( '%s: <span class="sc-success-badge">%s</span>', esc_html__( 'Allow post content', 'submit-content' ), esc_html__( 'yes', 'submit-content' ) ); ?></li>
            <?php
        } else {
            ?>
                <li><?php printf( '%s: <span class="sc-error-badge">%s</span>', esc_html__( 'Allow post content', 'submit-content' ), esc_html__( 'no', 'submit-content' ) ); ?></li>
            <?php 
        }

        if( $featured_img ){
            ?>
                <li><?php printf( '%s: <span class="sc-success-badge">%s</span>', esc_html__( 'Set featured image', 'submit-content' ), esc_html__( 'yes', 'submit-content' ) ); ?></li>
            <?php
        } else {
            ?>
                <li><?php printf( '%s: <span class="sc-error-badge">%s</span>', esc_html__( 'Set featured image', 'submit-content' ), esc_html__( 'no', 'submit-content' ) ); ?></li>
            <?php 
        }

        if( isset( $options['category'] ) && !empty( $options['category'] ) ){
            ?>
                <li><?php _e( 'Allowed category:', 'submit-content' ); ?></li>
            <?php 
            echo '<ul>';
            foreach( $options['category'] as $category ){
                ?>
                    <li><?php _e( $category['name'], 'submit-content' ); ?></li>
                <?php
            }
            echo '</ul>';
        } else {
            ?>
                <li><?php printf( '%s: <span class="sc-error-badge">%s</span>', esc_html__( 'Allowed category', 'submit-content' ), esc_html__( 'none', 'submit-content' ) ); ?></li>
            <?php 
        }

        if( isset( $options['tag'] ) && !empty( $options['tag'] ) ){
            ?>
                <li><?php _e( 'Allowed tag(s):', 'submit-content' ); ?></li>
            <?php 
            echo '<ul>';
            foreach( $options['tag'] as $tag ){
                ?>
                    <li><?php _e( $tag['name'], 'submit-content' ); ?></li>
                <?php
            }
            echo '</ul>';
        } else {
            ?>
                <li><?php printf( '%s: <span class="sc-error-badge">%s</span>', esc_html__( 'Allowed tag(s)', 'submit-content' ), esc_html__( 'none', 'submit-content' ) ); ?></li>
            <?php 
        }
        echo '</ul>';

    }
}


/**
 * Generates a form
 * 
 * @param array $options Options array
 * @return void
 */
function wpbtsc_output_form( $options, $form_id ){

    $wpbtsc_options = get_option( 'submitcontent_options' );
    if( $wpbtsc_options['wpbtsc_requires_login'] && ! is_user_logged_in() ){
        $message = ( get_option( 'users_can_register' ) ) ? 
                        sprintf( __( 'To register, please visit: %s', 'submit-content' ), wp_register( '', '', false ) ) :
                        __( 'Registration not allowed at this time.', 'submit-content' );
        return printf(
            '<div><p>%s. %s</p></div>',
            __( 'Sorry, only registered users can submit the form', 'submit-content' ),
            $message
        );
    }
    
    $form_title = ( $options['add_form_heading'] ) ? $options['add_form_heading'] : '';
    $form_title_text = ( $options['add_form_heading_text'] ) ? $options['add_form_heading_text'] : '';

    $form_description = ( $options['add_form_description'] ) ? $options['add_form_description'] : '';
    $form_description_text = ( $options['add_form_description_text'] ) ? $options['add_form_description_text'] : '';

    $post_title = ( $options['add_post_title'] ) ? $options['add_post_title'] : '';
    $post_content = ( $options['add_post_content'] ) ? $options['add_post_content'] : '';
    $featured_img = ( $options['add_post_featured_image'] ) ? $options['add_post_featured_image'] : '';

    $form_type = ( $featured_img ) ? 'enctype="multipart/form-data"' : '';

    $security_key = wp_create_nonce( 'wpbtsc_form_input' );
    $form_id = 'sc-form-' . $form_id;

    ?>
        
        <div class="sc-form">
            <?php
                // form title
                if( $form_title && $form_title_text ):
                    printf( '<h2>%s</h2>', esc_html__( $form_title_text, 'submit-content' ) );
                endif;
                // form description (short)
                if( $form_description && $form_description_text  ):
                    printf( '<p>%s</p>', esc_html__( $form_description_text, 'submit-content' ) );
                endif;
            ?>
            <form action="" id="<?php echo $form_id; ?>" class="wpbtsc-form" method="post" <?php echo $form_type; ?>>
                <input type="hidden" name="sc_security_id" value="<?php echo $security_key; ?>">
                <input type="hidden" name="form_id" value="<?php echo $form_id; ?>">
                <div>
                    <label for="wpbtsc_posttitle">
                        <?php _e( 'Enter post title', 'submit-content' ); ?>
                    </label>
                </div>
                <div>
                    <input type="text" id="wpbtsc_posttitle" name="wpbtsc_posttitle" value="">
                </div>
                <?php
                    if( $post_content ):
                ?>
                        <div>
                            <label for="wpbtsc_postcontent">
                                <?php _e( 'Enter post content', 'submit-content' ); ?>
                            </label>
                        </div>

                        <div>
                            <textarea name="wpbtsc_postcontent" id="wpbtsc_postcontent" cols="30" rows="10"></textarea>
                        </div>
                <?php
                    endif;
                    if( $featured_img ):
                ?>
                        <div>
                            <label for="wpbtsc_featured_img"><?php _e( 'Choose featured image', 'submit-content' ); ?></label>
                        </div>

                        <div>
                            <input type="file" id="wpbtsc_featured_img" name="wpbtsc_featured_img">
                        </div>
                <?php
                    endif;
                    if( isset( $options['category'] ) && !empty( $options['category'] ) ):
                ?>
                    <?php
                        printf( '<p>%s:</p>', esc_html__( 'Select taxonomies for the post', 'submit-content' ) );
                    ?>

                    <?php 
                        foreach( $options['category'] as $category ){
                            printf( '<p>%s: </s>', esc_html__( $category['name'], 'submit-content' ) );
                            $terms = get_terms([
                                'taxonomy' => $category['slug'],
                                'hide_empty' => false
                            ]);
                            if( ! empty( $terms ) ){
                                printf( '<input type="hidden" name="%s[]" value="">', $category['slug'] );
                                foreach( $terms as $term ){
                                    ?>
                                        <div>
                                            <input 
                                                type="checkbox"
                                                id="<?php echo $term->slug; ?>"
                                                name="<?php echo $category['slug']; ?>[]"
                                                value="<?php echo $term->term_id; ?>"
                                                parent="<?php echo $term->parent; ?>"
                                            >
                                            <label for="<?php echo $term->slug; ?>"> <?php _e( $term->name, 'submit-content' ); ?></label>
                                        </div>
                                    <?php
                                }
                            } else {
                                printf( '<p>%s</p>', esc_html__( 'no data available at the moment', 'submit-content' ) );
                            }
                        }
                    ?>
                <?php
                    endif;
                    if( isset( $options['tag'] ) && !empty( $options['tag'] ) ):
                ?>
                        <?php 
                            foreach( $options['tag'] as $tag ){
                                printf( '<p>%s: </s>', esc_html__( $tag['name'], 'submit-content' ) );
                                $terms = get_terms([
                                    'taxonomy' => $tag['slug'],
                                    'hide_empty' => false
                                ]);
                                if( ! empty( $terms ) ){
                                    printf( '<input type="hidden" name="%s[]" value="">', $tag['slug'] );
                                    foreach( $terms as $term ){
                                        ?>
                                            <div>
                                                <input
                                                    type="checkbox"
                                                    id="<?php echo $term->slug; ?>"
                                                    name="<?php echo $tag['slug']; ?>[]"
                                                    value="<?php echo $term->slug; ?>"
                                                >
                                                <label for="<?php echo $term->slug; ?>"> <?php _e( $term->name, 'submit-content' ); ?></label>
                                            </div>
                                        <?php
                                    }
                                } else {
                                    printf( '<p>%s</p>', esc_html__( 'no data available at the moment', 'submit-content' ) );
                                }
                            }
                        ?>
                    <?php
                        endif;
                    ?>
                <p>
                    <input
                        type="submit"
                        name="wpbtsc_submit_content"
                        value="<?php _e( 'Submit', 'submit-content' ); ?>"
                    >
                </p>
            </form>
        </div>
    <?php
}


/**
 * Creates posts array from data and backend options
 * 
 * @param array $data
 * @return array Returns a post array
 */

function wpbtsc_create_posts_array( $data ){
    
    $post_array = [];
    $categories = [];
    $tags = [];
    $keys = array_keys( $data );

    $sc_options = get_option( 'submitcontent_options' );

    $post_type = $sc_options['wpbtsc_saveas'];
    $post_status = $sc_options['wpbtsc_default_status'];
    $admin_email = get_option( 'admin_email' );
    $admin_id = get_user_by( 'email', $admin_email );
    $supported_taxonomies = get_object_taxonomies( $post_type, 'object' );
    
    foreach( $supported_taxonomies as $taxonomy ){
        if( $taxonomy->hierarchical ){
            array_push( $categories, $taxonomy->name );
        } elseif( ! $taxonomy->hierarchical ) {
            // skipping post_format types for post_type = 'post'
            if( $taxonomy->name == 'post_format' ) continue;
            array_push( $tags, $taxonomy->name );
        }
    }

    $post_array = [
        'post_title' => wp_strip_all_tags( $data['post_title'] ),
        'post_status' => $post_status,
        'post_type' => $post_type,
        'post_author' => $admin_id->ID,
        'tax_input' => []
    ];

    if( $data['post_content'] ){
        $post_array['post_content'] = $data['post_content'];
    }
    
    $hierarchical_tax = array_intersect( $categories, $keys );
    if( sizeof( $hierarchical_tax ) != 0 ){
        foreach( $hierarchical_tax as $cat ){
            $post_array['tax_input'] = wpbtsc_create_taxonomy_array( $post_array['tax_input'], $cat, $data[$cat], 'id' );
        }
    }
    
    $non_hierarchical_tax = array_intersect( $tags, $keys );
    if( sizeof( $non_hierarchical_tax ) != 0 ){
        foreach( $non_hierarchical_tax as $tag ){
            $post_array['tax_input'] = wpbtsc_create_taxonomy_array( $post_array['tax_input'], $tag, $data[$tag], 'slug' );
        }
    }

    return $post_array;
}

/**
 * Sends an email to specified address (admin email or custom email).
 * 
 * @param int $post_id
 * @param string $post_title
 * @return void Sends an email or not
 */

function wpbtsc_send_email( $post_id, $post_title ){

    $sc_options = get_option( 'submitcontent_options' );
    
    if( $sc_options['wpbtsc_email_template'] ){

        $admin_email = get_option( 'admin_email' );
        $edit_post_link = get_edit_post_link( $post_id, '&' );
        $to = ( $sc_options['wpbtsc_email_override'] ) ? $sc_options['wpbtsc_email_override'] : $admin_email;
        $user_name = '';
        $body = $sc_options['wpbtsc_email_template'];
        $site_name = get_bloginfo( 'name' );
        $headers = [];

        if( is_user_logged_in() ){
            $current_user = wp_get_current_user();         
            $user_name = $current_user->display_name;
        } else {
            $user_name = __( 'Visitor', 'submit-content' );
        }
        
        $token_values = [
            $user_name,
            $post_title,
            esc_url_raw( $edit_post_link ),
            $site_name
        ];
        $token_ids = [
            '{user_name}',
            '{post_title}',
            '{post_edit_url}',
            '{site_name}'
        ];

        $message_body = str_replace( $token_ids, $token_values, $body );
        $subject = __( 'Submit Content', 'submit-content' );
        $headers[] = 'From: '. $site_name .' <'. $admin_email .'>';
        wp_mail( $to, $subject, $message_body, $headers );
    }
}

/**
 * Creates and validates (existance) taxonomies array
 * 
 * @param array $tax_input
 * @param string $key
 * @param array $val
 * @param bool $get_by
 * 
 * @return array
 */
function wpbtsc_create_taxonomy_array( $tax_input, $key, $val, $get_by ){
    $ids = [];
    if( is_array( $val ) && sizeof( $val ) != 0 ){
        foreach( $val as $id ){
            if( $id ){
                if( $get_by == 'id' ){
                    $term_id = intval( $id );
                    $term_exists = get_term_by( 'ID', $term_id, $key );
                    if( $term_exists ){
                        array_push( $ids, intval( $id ) );
                    }
                }
                if( $get_by == 'slug' ){
                    $term_exists = get_term_by( 'slug', $id, $key );
                    if( $term_exists ){
                        array_push( $ids, $id );
                    }
                }
            }
        }
    }

    $tax_input[$key] = $ids;
    return $tax_input;
}


/**
 * Checks if the shortcode with passed options exists or not
 * 
 * @param string $shortcode_options
 * 
 * @return bool
 */

function wpbtsc_check_duplicate_shortcode( $shortcode_options ){
    global $wpdb;

    $table_name = $wpdb->prefix . 'submitcontent';

    $result = $wpdb->query(
        $wpdb->prepare(
            "SELECT id FROM $table_name WHERE options=%s",
            $shortcode_options
        )
    );
    return $result;
}