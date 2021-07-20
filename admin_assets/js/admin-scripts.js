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
    init: function(){
        submitContentApp.data = {
            form: jQuery('#wpbt-sc-generator'),
            button: jQuery('#wpbt-sc-generator').find('input[type="submit"]'),
            action: 'sc_generate_shortcode',
            ajaxURL: scJSOBJ.ajax_url, // scJSOBJ is global variable!
            options: {},
        };

        jQuery(submitContentApp.data.form).find('input').change(submitContentApp.handleInput);
        submitContentApp.data.button.click(submitContentApp.handleSubmit);
    },
    handleInput: function(){
        // 'this' refers to the input field
        let inputType = jQuery(this).attr('type');
        let inputKey = jQuery(this).attr('name');
        let value = '';
        switch(inputType){
            case 'checkbox':
                if(jQuery(this).prop('checked') == true){
                    value = 1;
                } else {
                    value = 0;
                }
                submitContentApp.updateOptions(inputKey, value);
                jQuery('tr.' + inputKey + '_class').toggle();
                break;
            case 'text':
                value = jQuery(this).val();
                submitContentApp.updateOptions(inputKey, value, false);
                // update options!
                break;
            case 'textarea':
                value = jQuery(this).val();
                submitContentApp.updateOptions(inputKey, value, false);
                // update options!
                break;
            case 'selet':
                // update options!
                break;
            case 'radio':
                // nothing on this field
                break;
        }
    },
    updateOptions: function(key, value, remove=true){
        if( key in submitContentApp.data.options ){
            if( remove || ! value ){
                delete submitContentApp.data.options[key];
            } else {
                submitContentApp.data.options[key] = value;
            }
        } else {
            submitContentApp.data.options[key] = value;
        }
    },
    handleSubmit: function(event){
        event.preventDefault();
        jQuery.ajax({
            type: 'post',
            url: submitContentApp.data.ajaxURL,
            data: {
                action: submitContentApp.data.action,
                options: submitContentApp.data.options
            },
            beforeSend: function(xhr, settings){},
            success: function(response){
                console.log('ajax is successful!!!');
                console.log(response);
            },
            error: function(error){},
            complete: function(request, status){}
        });

    }
};

/**
 * initialize the object literal!
 */
jQuery(document).ready(submitContentApp.init);
