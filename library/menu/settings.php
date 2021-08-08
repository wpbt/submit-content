<?php
/**
 * Exit if accessed directly!
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'why though?' );
}

function wpbt_submitcontent_settings(){
    register_setting(
        'submitcontent_options',
        'submitcontent_options',
        'wpbtsc_validate'
    );

    add_settings_section(
        'wpbt_submitcontent_general_section',
        __( 'General settings', 'submitcontent' ),
        null, // no need to display anything at the top of this section!
        'submitcontent'
    );

    add_settings_field(
        'wpbtsc_saveas',
        __( 'Save content as: ', 'submitcontent' ),
        'wpbtsc_saveas_callback', 
        'submitcontent',
        'wpbt_submitcontent_general_section',
        [
            'id' =>  'wpbtsc_saveas',
            'label_for' =>  'wpbtsc_saveas',
        ]
    );

    add_settings_field(
        'wpbtsc_default_status',
        __( 'Default status of the content', 'submitcontent' ),
        'wbptsc_default_status_callback',
        'submitcontent',
        'wpbt_submitcontent_general_section',
        [
            'id' =>  'wpbtsc_default_status',
            'label_for' =>  'wpbtsc_default_status',
        ]
    );

    add_settings_field(
        'wpbtsc_send_admin_email',
        __( 'Send email to admin whenever content is submitted', 'submitcontent' ),
        'wpbtsc_email_callback',
        'submitcontent',
        'wpbt_submitcontent_general_section',
        [
            'id' => 'wpbtsc_send_admin_email',
            'label_for' =>  'wpbtsc_send_admin_email',
        ]
    );

    add_settings_field(
        'wpbtsc_requires_login',
        __( 'Only loggedin users can submit', 'submitcontent' ),
        'wpbtsc_requires_login_callback',
        'submitcontent',
        'wpbt_submitcontent_general_section',
        [
            'id' => 'wpbtsc_requires_login',
            'label_for' =>  'wpbtsc_requires_login',
        ]
    );
}

function wpbtsc_saveas_callback( $args ){

    $options = get_option( 'submitcontent_options' );
    
    if( !empty( $options ) && isset( $options[$args['id']] ) ){
        $value = $options[$args['id']];
    } else {
        $value = '';
    }

    $post_types = get_post_types( 
        [
            'public' => true,
            '_builtin' => false
        ],
        'names',
        'and'
    );

    ?>
        <select name="submitcontent_options[<?php echo $args['id']; ?>]" id="<?php echo $args['id']; ?>">
            <option value="post" <?php echo selected( $value, 'post' ); ?>><?php esc_html_e( 'post', 'submitcontent' ) ?></option>
            <?php
                foreach( $post_types as $post_type ){
                    ?>
                        <option value="<?php echo $post_type; ?>" <?php echo selected( $value, $post_type ); ?>><?php esc_html_e( $post_type, 'submitcontent' ); ?></option>
                    <?php
                }
            ?>
        </select>
    <?php
}

function wbptsc_default_status_callback( $args ){

    $options = get_option( 'submitcontent_options' );

    if( !empty( $options ) && isset( $options[$args['id']] ) ){
        $value = $options[$args['id']];
    } else {
        $value = '';
    }

    $statuses = [
        'draft' => __( 'Draft', 'submitcontent' ),
        'pending' => __( 'Pending', 'submitcontent' ),
        'publish' => __( 'Publish', 'submitcontent' ),
    ];
    ?>
        <select name="submitcontent_options[<?php echo $args['id']; ?>]" id="<?php echo $args['id'] ?>">
            <?php
                foreach( $statuses as $key => $name ){
                    ?>
                        <option value="<?php echo $key; ?>" <?php echo selected( $value, $key ); ?>><?php esc_html_e( $name, 'submitcontent' ) ?></option>
                    <?php
                }
            ?>
        </select>
    <?php
}

function wpbtsc_email_callback( $args ){

    $options = get_option( 'submitcontent_options' );

    if( !empty( $options ) && isset( $options[$args['id']] ) ){
        $value = $options[$args['id']];
    } else {
        $value = '';
    }

    ?>
        <input id="<?php echo $args['id']; ?>" type="checkbox" name="submitcontent_options[<?php echo $args['id']; ?>]" value="1" <?php echo checked( $value, 1 ); ?> />
    <?php
}

function wpbtsc_requires_login_callback( $args ){

    $options = get_option( 'submitcontent_options' );

    if( !empty( $options ) && isset( $options[$args['id']] ) ){
        $value = $options[$args['id']];
    } else {
        $value = '';
    }

    ?>
        <input id="<?php echo $args['id']; ?>" type="checkbox" name="submitcontent_options[<?php echo $args['id']; ?>]" value="1" <?php echo checked( $value, 1 ); ?> />
    <?php
}

/**
 * validation callback
 */

function wpbtsc_validate( $input ){
    // get default options
    $option = get_option( 'submitcontent_options' );

    if( ! $input['wpbtsc_saveas'] ){
        // set default option
        $input['wpbtsc_saveas'] = $option['wpbtsc_saveas'];
    }

    if( ! $input['wpbtsc_default_status'] ){
        // set default option
        $input['wpbtsc_default_status'] = $option['wpbtsc_default_status'];
    }

    if( ! $input['wpbtsc_send_admin_email'] ){
        $input['wpbtsc_send_admin_email'] = '0';
    }

    if( ! $input['wpbtsc_requires_login'] ){
        $input['wpbtsc_requires_login'] = '0';
    }

    return $input;
}