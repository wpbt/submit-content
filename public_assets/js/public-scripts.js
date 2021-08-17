'use strict';

/**
 * application setup
 */

let scFromHandler = {
    init: function(){
        jQuery( 'form.wpbtsc-form' ).submit( scFromHandler.handleForm );
    },

    handleForm: function(event){
        event.preventDefault();
        let formID = jQuery( this ).find( 'input[name="form_id"').val();
        let formData = new FormData(this);
        formData.append( 'action', 'wpbtsc_form_submission' );

        if( typeof grecaptcha != 'undefined' ){

            grecaptcha.ready( function() {
                grecaptcha.execute( scJSOBJ.site_key, { action: 'submitcontent' } )
                            .then( function( token ){
                                    let recaptchInput = '<input type="hidden" name="g-recaptcha-response" value="' + token + '">';
                                    jQuery( 'form#' + formID ).find( 'input[name="g-recaptcha-response"]' ).remove();
                                    jQuery( 'form#' + formID ).prepend( recaptchInput );
                                    formData.append( 'wpbtsc_token', token );
                                    scFromHandler.handleAjax( formData );
                                }
                            );
            } );

        } else {
            scFromHandler.handleAjax( formData );
        }
    },
    handleAjax: function( formData ){
        jQuery.ajax({
            url: scJSOBJ.ajax_url,
            data: formData,
            type: 'POST',
            cache: false,
            contentType: false,
            processData: false,
            success: scFromHandler.success
        });
    },
    success: function( response ){
        if( response.type  == 'error' ) scFromHandler.showErrorMessage( response.data, response.form_id );
        if( response.type  == 'success' ) scFromHandler.showSuccessMessage( response.data, response.form_id );
        console.log( response );
    },
    showErrorMessage: function( data, form_id ){

        let li = '';
        let ulOpen = '<div class="sc-errors">' + scJSOBJ.error_heading + '<ul>';       
        let ulClose = '</ul></div>';
        let formWrapper = jQuery( '#' + form_id );

        jQuery.each( data, function( element ){
            li += '<li>' + data[element] + '</li>';
        });

        jQuery( '.sc-errors' ).remove();
        jQuery( formWrapper ).before( ulOpen + li + ulClose );

    },
    showSuccessMessage: function( data, form_id ){

        let element = '<div class="sc-success">' + data + '</div>';       
        let formWrapper = jQuery( '#' + form_id );

        jQuery( '.sc-errors' ).remove();
        jQuery( formWrapper ).before( element );
        jQuery( formWrapper ).fadeIn( function(){
            jQuery( this ).remove();
        } );

    },

};

jQuery( document ).ready( scFromHandler.init );

