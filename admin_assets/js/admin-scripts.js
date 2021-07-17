'use strict';

/**
 * create object literal
 */

let submitContentApp = {
    /**
     * Setup
     */
    fields: [],
    data: {},
    /**
     * app initialize
     */
    init: function($, settings){
        submitContentApp.data = {
            form: $('#wpbt-sc-generator'),
            button: $('#wpbt-sc-generator').find('input[type="submit"]'),
            action: 'sc_generate_shortcode',
            ajaxURL: scJSOBJ.ajax_url,
            jquery: $,
            options: {},
            fields: ['input'] // haven't used it yet!
        };

        // allow form data override!
        submitContentApp.data.jquery.extend( submitContentApp.data, settings );
        
        submitContentApp.disableButton(submitContentApp.data.button);
        submitContentApp.handleFormFields(submitContentApp.data.form);
        submitContentApp.data.button.click(
                                        submitContentApp.handleSubmit(
                                            submitContentApp.data.button,
                                            submitContentApp.data.options
                                        )
                                    );
    },
    handleFormFields: function(form){
        submitContentApp.data.jquery(form).find('input').change(function(){
            let index = submitContentApp.data.jquery.inArray(this, submitContentApp.fields);
            if( index === -1 ){
                submitContentApp.fields.push(this);
            } else {
                submitContentApp.fields.splice(index, 1);
            }
            submitContentApp.handleFieldChange(this);
        });
    },
    handleFieldChange: function(input){
        let length = submitContentApp.fields.length;
        if( length !== 0 ){
            submitContentApp.enableButton(submitContentApp.data.button);
        } else {
            submitContentApp.disableButton(submitContentApp.data.button);
        }
        // collect current fields info (which is key & value)
        let key = submitContentApp.data.jquery(input).attr('name');
        let value = '';
        // value of checkbox doesn't depend upon the checked or unchecked.
        if(submitContentApp.data.jquery(input).prop('checked') == true){
            value = 1;
        } else {
            value = 0;
        }
        // save the key or remove the key in options property
        submitContentApp.updateOptions(key, value);
        // send ajax request for current field change
        submitContentApp.updateFields(key, value);
    },
    updateOptions: function(key, value){
        if( key in submitContentApp.data.options ){
            delete submitContentApp.data.options[key];
        } else {
            submitContentApp.data.options[key] = value;
        }
    },
    updateFields: function(key, value){
        submitContentApp.data.jquery.ajax({
            type: 'post',
            url: submitContentApp.data.ajaxURL,
            data: {
                action: submitContentApp.data.action,
                type: 'fieldchange',
                input_key: key,
                input_value: value},
            beforeSend: function(xhr, settings){
                // nothing to do atm!!!
            },
            success: function(response){
                console.log('ajax succeeded!!!');
                console.log(response);
            },
            error: function(error){
                // nothing to do atm!!!
            },
            complete: function(request, status){
                // nothing to do atm!!!
            }
        });
    },
    handleSubmit: function(button, options){
        button.click(function(button){
            button.preventDefault();
            // send ajax request with collected data.
            submitContentApp.data.jquery.ajax({
                type: 'post',
                url: submitContentApp.data.ajaxURL,
                data: {
                    action: submitContentApp.data.action,
                    type: 'formsubmit',
                    options: options
                },
                beforeSend: function(xhr, settings){
                    // nothing to do atm!!!
                },
                success: function(response){
                    console.log('ajax is success!!!');
                    console.log(response);
                },
                error: function(error){
                    // nothing to do atm!!!
                },
                complete: function(request, status){
                    // nothing to do atm!!!
                }
            });
        });
    },
    disableButton: function(button){
        button.prop('disabled',true);
    },
    enableButton: function(button){
        button.removeAttr('disabled');
    }
};

/**
 * initialize the object literal!
 */

(function($){
    $(document).ready(submitContentApp.init($));
})(jQuery);