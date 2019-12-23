export default function(FormBuilderInput, utils, session, submit) {
    var errorsInForm = utils.resetErrorsInForm();
    
    // if the recaptcha is hidden on load
    // $('.captcha-wrapper.hide').removeClass('hide').hide();
    $('form.form-builder.js-has-hidden-recaptcha div.captcha-wrapper').removeClass('hide').hide();
    $('body').on('focus', 'form.form-builder.js-has-hidden-recaptcha', function(e) { 
        $(this).removeClass('js-has-hidden-recaptcha');
        $('#captcha-wrapper-' + $(this).data('id')).show();
    });
    //@end recpatcha

    // When a user leaves a form input
    $('body').on('blur', '.js-form-builder-control', function(e) {
        if(typeof $(this).serializeArray()[0] !== "undefined") {
            var input = new FormBuilderInput($(this).serializeArray()[0]);
            // console.log('input', input);
            // Datepicker has time delay before blur so we must reset the input value ater 200ms
            if (input.dataType === 'date') {

                if( !jQuery().fdatepicker ) {
                    utils.addClassAfterBlur(input, input.isValid(), errorsInForm);
                }
                else {
                    if (input.isValid()) {
                        $(input.id+'-form-group').removeClass('has-error').addClass('has-success');

                    }
                }
            }
            else {
                utils.addClassAfterBlur(input, input.isValid(), errorsInForm);
            }
        }
        else {
            // This will be a checkbox
            var input = new FormBuilderInput(this);
            utils.addClassAfterBlur(input, input.isValid(), errorsInForm);
        }
    });

    // When a user leaves a form input
    $('body').on('blur', '._js-form-builder-checkbox-control', function(e) {
        var input = new FormBuilderInput(this);
        // console.log('input', input);
// var $siblings = $(input.siblings);
// var count = 0;
// $siblings.each(function(index, el) {
    
//     if(this.checked) {
//         count++;
//     }            
// });
// console.log('count', count);
        // console.log('inputGroup', inputGroup);
        // console.log('blur:', input.id, input.isValid());
        console.log('input.isValid()', input.isValid());
        utils.addClassAfterBlur(input, input.isValid(), errorsInForm);
        // console.log(input.id.substring(3)+'-form-group');
        // $( input.id.substring(3)+'-form-group').addClass('has-error').removeClass('has-success');
        // var $formGroup =  $(input.formGroup);//$('#'+(this.name.replace(/[\[\]']+/g,''))+'-form-group');// Remove square brackets

        // console.log('formGroup', $formGroup);
        // console.log('input.isValid()', input.isValid());
        
        // setTimeout(function() {
        //     $formGroup.addClass('has-error').removeClass('has-success');
        // }, 100);
        // $formGroup.addClass('has-error').removeClass('has-success');
    });
    $('body').on('focus', '._js-form-builder-checkbox-control', function(e) {
        // console.log('#'+(this.name.replace(/[\[\]']+/g,''))+'-form-group');
        // var $formGroup = $('#'+(this.name.replace(/[\[\]']+/g,''))+'-form-group');// Remove square brackets
        // console.log('formGroup remove class', $formGroup);  
        // $formGroup.removeClass('has-error').removeClass('has-success');      
        // $('#'+this.id+'-form-group').removeClass('has-error').removeClass('has-success');
        var input = new FormBuilderInput(this);
        console.log('input.formGroup', input.formGroup);
        $(input.formGroup).removeClass('has-error').removeClass('has-success').addClass('has-focus');   
        // console.log('focus:', input.id, input.isValid());
    });        

    // When a user enters a form input
    $('body').on('focus', '.js-form-builder-control', function(e) { 
        var input = new FormBuilderInput(this);
        // console.log('this.formGroup', this.formGroup);
        $(input.formGroup).removeClass('has-error').removeClass('has-success').addClass('has-focus');
        // $('.captcha-wrapper').show();
    });

    $("input.js-single-checkbox").change(function() {
        if(this.checked) {
            $('#'+this.id+'-form-group').removeClass('has-error').removeClass('has-success');
        }
        else {
            if (this.required) {
                $('#'+this.id+'-form-group').addClass('has-error').removeClass('has-success');
            }
            else {
                $('#'+this.id+'-form-group').removeClass('has-error').addClass('has-success');
            }
            
        }
    }); 

    $('.js-other-value').removeClass('hide').hide();

    $('.js-other-value-event select').change(function() {
        var input = new FormBuilderInput( $(this).serializeArray()[0] );
        if (input.value === 'other') {
            $(input.id + '-other').attr('disabled', false);
            $(input.id + '-other-form-group').slideDown();
        }
        else {
            $(input.id + '-other-form-group').slideUp();
            $(input.id + '-other').val('');
            $(input.id + '-other').attr('disabled', true);
        }
    });

    $('.js-other-value-event input[type=checkbox]').change(function() {
        var input = new FormBuilderInput( this );
        if (input.value === 'other') {
            
            if (this.checked) {
                $(input.id + '-other').attr('disabled', false);
                $(input.id + '-other-form-group').slideDown();
            }
            else {
                $(input.id + '-other-form-group').slideUp();
                $(input.id + '-other').val('');
                $(input.id + '-other').attr('disabled', true);
            }
        }       
    }); 

    $('body').on('submit', '#request-form.ajax', function(event) {
        event.preventDefault();
        return submit.formAjax(this, session);
    });

    $('body').on('submit', '#request-form.no-ajax', function(event) {
        return submit.form(this, event);
    });
}