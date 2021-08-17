<?php
/**
 * Exit if accessed directly!
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'why though?' );
}


/**
 * General section setting field callbacks!
 */

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
 * Security section callback
 */

function wpbtsc_security_section_callback(){
    printf(
        '<p><strong>%s: %s</strong></p>',
        __( 'Note', 'submitcontent' ),
        __( 'to implement Google\'s v3 reCAPTCHA service, enter both site key and security key', 'submitcontent' ),
    );
}

/**
 * Security section setting field callbacks
 */

function wpbtsc_sitekey_callback( $args ){
    $options = get_option( 'submitcontent_options' );

    if( !empty( $options ) && isset( $options[$args['id']] ) ){
        $value = $options[$args['id']];
    } else {
        $value = '';
    }
    ?>
        <input id="<?php echo $args['id']; ?>" type="text" name="submitcontent_options[<?php echo $args['id']; ?>]" value="<?php echo trim( $value ); ?>">
    <?php
}

function wpbtsc_secretkey_callback( $args ){
    $options = get_option( 'submitcontent_options' );

    if( !empty( $options ) && isset( $options[$args['id']] ) ){
        $value = $options[$args['id']];
    } else {
        $value = '';
    }
    ?>
        <input id="<?php echo $args['id']; ?>" type="text" name="submitcontent_options[<?php echo $args['id']; ?>]" value="<?php echo trim( $value ); ?>">
    <?php
}

