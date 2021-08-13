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
 * @param array $taxonomy multiple form fields
 * @return void  
 */

function wpbtsc_generate_input_field( $type, $name, $title, $value = '', $taxonomy = NULL ){
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

function wpbtsc_validate_admin_form( $form ){

    $errors = [];
    $data = [];

    if( empty( $form ) || empty( $form['options'] ) ) {
        $errors['empty_data'] = __( 'no data passed', 'submitcontent' );
        return [
            'errors' => $errors,
            'data' => $data
        ];
    }
    
    if( ! wp_verify_nonce( $form['options']['wpbt_sc_nonce'], 'wpbtsc' ) ){
        $errors['invalid_nonce'] = __( 'invalid nonce', 'submitcontent' );
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
 * Validates and Generates a response
 * 
 * @param array @form Form data to velidate
 * @return array @response Contains errors and data as keys
 */

function wpbtsc_validate_public_form( $form ){

    $errors = [];
    $data = [];

    // nonce validation
    if( ! wp_verify_nonce( $form['form_data']['sc_security_id'], 'wpbtsc_form_input' ) ){
        $errors['invalid_nonce'] = __( 'invalid nonce', 'submitcontent' );
        return [
            'errors' => $errors,
            'data' => $data
        ];
    }

    $post_title = ( $form['form_data']['wpbtsc_posttitle'] ) ? sanitize_text_field( $form['form_data']['wpbtsc_posttitle'] ) : '';
    $post_content = ( $form['form_data']['wpbtsc_postcontent'] ) ? sanitize_textarea_field( $form['form_data']['wpbtsc_postcontent'] ) : '';

    // title and content
    if( array_key_exists( 'wpbtsc_posttitle', $form['form_data'] ) ){
        if( $post_title ){
            $data['post_title'] = __( $post_title, 'submitcontent' );
        } else {
            $errors['post_title'] = __( 'post title is required', 'submitcontent' );
        }
    }
    if( array_key_exists( 'wpbtsc_postcontent', $form['form_data'] ) ){
        if( $post_content ){
            $data['post_content'] = __( $post_content, 'submitcontent' );
        } else {
            $errors['post_content'] = __( 'post content is required', 'submitcontent' );
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
            $filesize_mb = ( $image_size / pow( 1024, 2 ) );
            if( $filesize_mb > 5 ){
                $errors['file_size'] = __( 'file too large', 'submitcontent' );
            }
        }
        // name and type
        if( $image_name ){
            $is_mime_allowed = wp_check_filetype( $image_name );
            if(
                isset( $is_mime_allowed['ext'] ) &&
                in_array(
                    strtolower( $is_mime_allowed['ext'] ),
                    $allowed_file_types
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
                $errors['unsupported_file_type'] = __( 'unsupported file type', 'submitcontent' );
            }
        } else {
            $errors['featured_image'] = __( 'no featured image set', 'submitcontent' );
        }
        /**
         * file (image) handling end
         */

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


/**
 * Generates a form
 * 
 * @param array $options Options array
 * @return void
 */
function wpbtsc_output_form( $options, $form_id ){

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
        
        <div class="form">
            <?php
                // form title
                if( $form_title && $form_title_text ):
                    printf( '<h2>%s</h2>', __( $form_title_text, 'submitcontent' ) );
                endif;
                // form description (short)
                if( $form_description && $form_description_text  ):
                    printf( '<p>%s</p>', __( $form_description_text, 'submitcontent' ) );
                endif;
            ?>
            <form action="" id="<?php echo $form_id; ?>" class="wpbtsc-form" method="post" <?php echo $form_type; ?>>
                <input type="hidden" name="sc_security_id" value="<?php echo $security_key; ?>">
                <input type="hidden" name="form_id" value="<?php echo $form_id; ?>">
                <div>
                    <label for="wpbtsc_posttitle">
                        <?php _e( 'Enter post title', 'submitcontent' ); ?>
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
                                <?php _e( 'Enter post content', 'submitcontent' ); ?>
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
                            <label for="wpbtsc_featured_img"><?php _e( 'Choose featured image', 'submitcontent' ); ?></label>
                        </div>

                        <div>
                            <input type="file" id="wpbtsc_featured_img" name="wpbtsc_featured_img">
                        </div>
                <?php
                    endif;
                    if( isset( $options['category'] ) && !empty( $options['category'] ) ):
                ?>
                    <?php
                        printf( '<p>%s:</p>', __( 'Select taxonomies for the post', 'submitcontent' ) );
                    ?>

                    <?php 
                        foreach( $options['category'] as $category ){
                            printf( '<p>%s: </s>', __( $category['name'], 'submitcontent' ) );
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
                                            <label for="<?php echo $term->slug; ?>"> <?php _e( $term->name, 'submitcontent' ); ?></label>
                                        </div>
                                    <?php
                                }
                            } else {
                                printf( '<p>%s</p>', __( 'no data available at the moment', 'submitcontent' ) );
                            }
                        }
                    ?>
                <?php
                    endif;
                    if( isset( $options['tag'] ) && !empty( $options['tag'] ) ):
                ?>
                        <?php 
                            foreach( $options['tag'] as $tag ){
                                printf( '<p>%s: </s>', __( $tag['name'], 'submitcontent' ) );
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
                                                <label for="<?php echo $term->slug; ?>"> <?php _e( $term->name, 'submitcontent' ); ?></label>
                                            </div>
                                        <?php
                                    }
                                } else {
                                    printf( '<p>%s</p>', __( 'no data available at the moment', 'submitcontent' ) );
                                }
                            }
                        ?>
                    <?php
                        endif;
                    ?>
                <p><input type="submit" name="wpbtsc_submit_content" value="<?php _e( 'Submit', 'submitcontent' ); ?>"></p>
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
        'post_author' => $admin_id->ID
    ];

    if( $data['post_content'] ){
        $post_array['post_content'] = $data['post_content'];
    }
    
    $hierarchical_tax = array_intersect( $categories, $keys );
    $cats = [];
    if( sizeof( $hierarchical_tax ) != 0 ){
        foreach( $hierarchical_tax as $cat ){
            $cats = array_merge( $cats, $data[$cat] ); 
        }

        // so, converting them to integers
        $result = array_filter( $cats, function( $id ){
            if( $id ) return intval( $id );
        } );

        if( $result ){
            if( $post_type == 'post' ){
                $post_array['post_category'] = $result;
            } else {
                $post_array['tax_input']['hierarchical_tax'] = $result;
            }
        }
    }
    
    $non_hierarchical_tax = array_intersect( $tags, $keys );
    $t = [];
    unset( $result );
    if( sizeof( $non_hierarchical_tax ) != 0 ){
        foreach( $non_hierarchical_tax as $tag ){
            $t = array_merge( $t, $data[$tag] );
        }
        // so, converting them to integers
        $result = array_filter( $t );
        if( $post_type == 'post' ){
            $post_array['tags_input'] = $result;
        } else {
            $post_array['tax_input']['hierarchical_tax'] = $result;
        }
    }

    return $post_array;
}