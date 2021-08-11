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
        let formData = new FormData(this);
        formData.append( 'action', 'wpbtsc_form_submission' );
        jQuery.ajax({
            url: scJSOBJ.ajax_url,
            data: formData,
            type: 'POST',
            cache: false,
            contentType: false,
            processData: false,
            success: scFromHandler.success,
        });
    },
    success: function( response ){
        console.log( response );
    }
};

jQuery( document ).ready( scFromHandler.init );

