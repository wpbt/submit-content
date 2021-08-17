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
        null,
        'submitcontent'
    );

    add_settings_section(
        'wpbt_submitcontent_security_section',
        __( 'Form security settings', 'submitcontent' ),
        'wpbtsc_security_section_callback',
        'submitcontent'
    );

    // general section fields

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

    // security fields

    add_settings_field(
        'wpbtsc_recaptcha_sitekey',
        __( 'Enter reCAPTCHA v3 site key', 'submitcontent' ),
        'wpbtsc_sitekey_callback',
        'submitcontent',
        'wpbt_submitcontent_security_section',
        [
            'id' => 'wpbtsc_recaptcha_sitekey',
            'label_for' =>  'wpbtsc_recaptcha_sitekey',
        ]
    );

    add_settings_field(
        'wpbtsc_recaptcha_secretkey',
        __( 'Enter reCAPTCHA v3 secret key', 'submitcontent' ),
        'wpbtsc_secretkey_callback',
        'submitcontent',
        'wpbt_submitcontent_security_section',
        [
            'id' => 'wpbtsc_recaptcha_secretkey',
            'label_for' =>  'wpbtsc_recaptcha_secretkey',
        ]
    );

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

    if( ! $input['wpbtsc_recaptcha_sitekey'] ){
        $input['wpbtsc_recaptcha_sitekey'] = $option['wpbtsc_recaptcha_sitekey'];
    } else {
        $input['wpbtsc_recaptcha_sitekey'] = sanitize_text_field( $input['wpbtsc_recaptcha_sitekey'] );
    }

    if( ! $input['wpbtsc_recaptcha_secretkey'] ){
        $input['wpbtsc_recaptcha_secretkey'] = $option['wpbtsc_recaptcha_secretkey'];
    } else {
        $input['wpbtsc_recaptcha_secretkey'] = sanitize_text_field( $input['wpbtsc_recaptcha_secretkey'] );
    }

    if( ! $input['wpbtsc_send_admin_email'] ){
        $input['wpbtsc_send_admin_email'] = '0';
    }

    if( ! $input['wpbtsc_requires_login'] ){
        $input['wpbtsc_requires_login'] = '0';
    }

    return $input;
}