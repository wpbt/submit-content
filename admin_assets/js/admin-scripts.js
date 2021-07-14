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
            ajaxURL: scJSOBJ.ajax_url,
            fields: ['input']
        };

        // allow form data override!
        $.extend( submitContentApp.data, settings );
        
        submitContentApp.disableButton(submitContentApp.data.button);
        submitContentApp.handleFormFields($, submitContentApp.data.form);

    },
    handleFormFields: function($, form){
        $(form).find('input').change(function(){
            let index = $.inArray(this, submitContentApp.fields);
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
        
    },
    handleClick: function(form){
        form.find('input[type="submit"]').click(function(button){
            button.preventDefault();
            submitContentApp.collectFormData(form);
        });
    },
    collectFormData: function(form){
        form.find('input[type="checkbox"]')
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