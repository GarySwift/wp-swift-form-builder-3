import { utils } from './form-builder-utilities';
var submit = {
    form: function(event, form) {
        var formData = new FormData(form);
        var $form = $(form);
        errorsInForm = utils.validateForm( $form.serializeArray(), utils.resetErrorsInForm() ); 
        if (errorsInForm.count === 0) {
            return true;
        }
        event.preventDefault();
        return false;
    },
    formAjax: function(form, session) {
        // event.preventDefault();
    // $('#request-form.ajax').submit(function(e) {
        var formData = new FormData(form);
        // console.log(formData);
        // console.log('#request-form');
        
        var $form = $(form);
        var ajax = $form.data('ajax');
        // console.log('ajax', ajax);
        var submit = $form.find(":submit");
        // errorsInForm = utils.resetErrorsInForm();
        var errorsInForm = utils.validateForm( $form.serializeArray(), utils.resetErrorsInForm() );
        // todo - handle file uploads with ajax
        var $fileInputs = $('input.js-file-upload');
        var files = {};
        if ($fileInputs.length > 0) {
            $fileInputs.each(function(){
                files[this.id] = this.files;
            });     
        }
        // @end todo - handle file uploads with ajax

        submit.prop('disabled', true);
        // errorsInForm.count = 0;
        if (errorsInForm.count === 0) {
            // FormBuilderAjax is set on server using wp_localize_script
            if(typeof FormBuilderAjax !== "undefined") {
                // event.preventDefault();
                FormBuilderAjax.form = $form.serializeArray();
                FormBuilderAjax.id = $form.data('id');
                FormBuilderAjax.post = $form.data('post-id');
                // FormBuilderAjax.files = files;
                // FormBuilderAjax.action = "wp_swift_submit_" + $form.data('type') + "_form";//"wp_swift_submit_request_form";

                FormBuilderAjax.type = $form.data('type');//"wp_swift_submit_request_form";
                FormBuilderAjax.action = "wp_swift_submit_request_form";
                var ref = utils.getRef();
                if(typeof ref !== "undefined") {
                   FormBuilderAjax.ref = ref;
                }
                // FormBuilderAjax.type = $form.data('type');
                // var type = document.getElementById( "form-type" );
                // if (type) {
                //  FormBuilderAjax.type = type.value;
                // }
                // console.log('FormBuilderAjax', FormBuilderAjax);


                $.post(FormBuilderAjax.ajaxurl, FormBuilderAjax, function(response) {
                    var serverResponse = JSON.parse(response);
                    if (FormBuilderAjax.debug)
                        console.log('serverResponse:', serverResponse);                       
                    
                    if (serverResponse.session) {
                        session.save(session.name, serverResponse.session );
                        if (FormBuilderAjax.debug) 
                            console.log('DEBUG [SAVED] serverResponse.session:', serverResponse.session);                          
                    }
                    if (serverResponse.location) {
                        // if (serverResponse.session) {//!== "undefined"
                            // console.log('serverResponse.session');
                            // console.log(serverResponse.session);
                            // console.log(serverResponse.session);
                            

                            // $('.form-builder.groupings').slideUp();
                            // $('#download-mask').removeClass('masked');   
                            // hideForm();                     
                        // }                              
                        // window.location = serverResponse.location;
                        window.location.replace(serverResponse.location);
                    }
                    else {
                        
                        

                        // if(typeof serverResponse.modal !== "undefined") {
                        //   $modal.toggleClass('large');
                        // }
                        submit.prop('disabled', false);
                        if(typeof grecaptcha !== "undefined") {
                            grecaptcha.reset();// Reset global object grecaptcha (Declared via API)
                        }

                        if (serverResponse.error_count === 0 && serverResponse.form_set === true && FormBuilderAjax.type !== "signup") {
                            utils.resetForm( $form.serializeArray() );        
                        }

                        if (typeof serverResponse.error_fields !== "undefined") {
                            // console.log('serverResponse.error_fields', serverResponse.error_fields);
                            for (var i = 0; i < serverResponse.error_fields.length; i++) {                                    
                                $('#'+serverResponse.error_fields[i]+'-form-group').addClass('has-error').removeClass('has-success');
                                // console.log(serverResponse.error_fields[i]+'-form-group');
                            }
                        }

                        var responseDisplayModal = true;
                        if(typeof serverResponse.displaying_results !== "undefined") {
                          responseDisplayModal = serverResponse.displaying_results.results_modal;
                        }
                        // var displaying_results
                        if ( responseDisplayModal ) {
                            var $modal = $('#form-builder-reveal');
                            $('#form-builder-reveal-content').html(serverResponse.html);
                            var modal = utils.getModal();
                            if(typeof modal !== null) {
                                // $modal.foundation('open');
                                modal.style.display = "block";  
                            }
                        }
                        else {
                            if (serverResponse.displaying_results.dom_element_to_remove !== '') {
                                $(serverResponse.displaying_results.dom_element_to_remove).empty();
                            }                                
                            if (serverResponse.displaying_results.dom_element_to_inject !== '') {
                                $(serverResponse.displaying_results.dom_element_to_inject).html(serverResponse.html);
                            }
                        }

                        if (FormBuilderAjax.type === "signup") {
                            if (serverResponse.session) {//!== "undefined"
                                session.save(session.name, serverResponse.session );

                                // $('.form-builder.groupings').slideUp();
                                // $('#download-mask').removeClass('masked');   
                                utils.hideForm();                     
                            }   
                        } 
                        // else if (serverResponse.session !== "undefined") {
                        //     console.log(serverResponse.session);
                        //     session.save(session.name, serverResponse.session );
                        //     // utils.removeMarketingSignUp(); 
                        // }
                    }                   
                }); 
            }
            // else {
            //     // $form.off('submit', submitForm);
            //     console.log('ajax skipped');
            //     // form.submit();
            //     console.log('form.submit();');
            //     return true;
            // }
        }
        else {
            $('a.form-builder-show-prev-next').hide();
            $('.show-hide-section').each(function(){
                $(form).removeClass('hidden-section').addClass('active-section');
            });
            submit.prop('disabled', false);
            utils.showModalWithErrors( utils.wrapErrorMessage(errorsInForm) );

        }
        return false;
    }  
}
export { submit }