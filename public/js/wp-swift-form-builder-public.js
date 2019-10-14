(function() {
//@start closure
if (typeof FormBuilderAjax !== "undefined") {
    /**
     * Get a date in the past by reducing years from now date.
     * Very basic. Does not include leap years.
     * 
     * @param int       years
     * @return string   date
     */

    if(typeof FormBuilderAjax !== "undefined" && FormBuilderAjax.debug) 
        console.log('DEBUG FormBuilderAjax:', FormBuilderAjax);

    // if(typeof sessionDetailsName === "undefined") {
    //   var sessionDetailsName = "form-session-details";
    // }
    var sessionDetailsName = "form-session-details";
    // console.log('2 sessionDetailsName', sessionDetailsName);
    // console.log(FormBuilderDatePicker);
    var select2Options = {};
    select2Options = {
        maximumSelectionLength: 2
    };

    var dateInPast = function dateInPast(years) {
        var dateNow = new Date();
        var dd = dateNow.getDate();
        var mm = dateNow.getMonth()+1; //January is 0!
        var yyyy = dateNow.getFullYear();

        if(dd<10){
            dd='0'+dd;
        } 
        if(mm<10){
            mm='0'+mm;
        } 
        
        var dateInPast = dd + '-' + mm + '-' + (yyyy-years);
        return  dateInPast; 
    };

    var resetErrorsInForm = function() {
        return {
            count: 0,
            report: ''
        };
    };

    jQuery(document).ready(function($){
        var errorsInForm = resetErrorsInForm(); 
        if(typeof FormBuilderAjax !== "undefined") {
            // console.log(FormBuilderAjax.updated);
        }
        // if the recaptcha is hidden on load
        // $('.captcha-wrapper.hide').removeClass('hide').hide();
        $('form.form-builder.js-has-hidden-recaptcha div.captcha-wrapper').removeClass('hide').hide();
        $('body').on('focus', 'form.form-builder.js-has-hidden-recaptcha', function(e) { 
            $(this).removeClass('js-has-hidden-recaptcha');
            $('#captcha-wrapper-' + $(this).data('id')).show();
        });
        //@end recpatchaå

    // $('input.sign-up').click(function() {
    //  var checked = 0;
    //  $('input.sign-up').each(function () {
    //      console.log($(this).attr("id"), this.checked);
    //      if (this.checked) {
    //          checked++;
    //      } 
    //  });
    //     if (checked > 0) {
    //      $('#submit-request-form').prop("disabled", false);
    //     }
    //     else {
    //      $('#submit-request-form').prop("disabled", true);
    //     }
    // });

        // FormBuilderDatePicker is set on server using wp_localize_script
        // Form Input Object
        var FormBuilderInput = function FormBuilderInput(input) {
            // console.log('input', input);
            this.name = input.name;
            this.value = input.value;
            this.id = '#'+(this.name.replace(/[\[\]']+/g,''));// Remove square brackets
            this.required = $(this.id).prop('required');
            this.type = $(this.id).prop('type');
            this.dataType = $(this.id).data('type');
            var min = parseInt($(this.id).attr('min'));
            if (!isNaN(min)) {
                this.min = min;
            }   
            var max = parseInt($(this.id).attr('max')); 
            if (!isNaN(max)) {
                this.max = max;
            }
            var validation = $(this.id).data('validation');
            if(typeof validation !== "undefined") {
              this.validation = validation;
            }   
        };

        // Instance methods
        FormBuilderInput.prototype = {
            errorCount: 0,
            feedbackMessage: '', 
            isValid: function isValid() {
                var re;
                // var length = this.value.length;
                // console.log('length', length);
                if(this.required && this.value==='') {
                    return false;
                }
                if(!this.required && this.value==='') {
                    return true;
                }               
                if(this.dataType !== "repeat-section" && this.hasOwnProperty('min')) {
                    if (this.value.length < this.min) {
                        return false;
                    }
                }
                if(this.dataType !== "repeat-section" && this.hasOwnProperty('max')) {
                    if (this.value.length > this.max) {
                        return false;
                    }
                }
                // Advanced validation
                switch (this.validation) {
                    case 'alphabetic':// Alphabetic
                        return /^[a-zA-Z]+$/.test(this.value);
                    case 'alphanumeric':// Alphanumeric
                        return /^[0-9a-zA-Z]+$/.test(this.value);   
                    case 'numeric': // Numeric
                        return !isNaN(this.value);
                    case 'uppercase_alphabetic':// Uppercase Alphabetic
                        return /^[A-Z]+$/.test(this.value);     
                    case 'uppercase_alphanumeric':// Uppercase Alphanumeric
                        return /^[0-9A-Z]+$/.test(this.value);                                                      
                }

                switch (this.dataType) {
                    case 'number':
                        return !isNaN(this.value);
                    case 'url': 
                        re = /^(http(?:s)?\:\/\/[a-zA-Z0-9]+(?:(?:\.|\-)[a-zA-Z0-9]+)+(?:\:\d+)?(?:\/[\w\-]+)*(?:\/?|\/\w+\.[a-zA-Z]{2,4}(?:\?[\w]+\=[\w\-]+)?)?(?:\&[\w]+\=[\w\-]+)*)$/i;
                        return re.test(this.value);
                    case 'email':
                        // var fakeEmailDomains = ['@mailinator.net'];
                        // for (var i = 0; i < fakeEmailDomains.length; i++) {                           
                        //     if ( this.value.includes(fakeEmailDomains[i])) {
                        //         //@todo show custom error messages
                        //         this.help = this.value + ' looks fake or invalid, please enter a real email address.';
                        //         return false;
                        //     }
                        // }                    
                        re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
                        return re.test(this.value); 
                    case 'select':  
                        return this.value.toLowerCase().substring(0, 6) !== 'select';       
                    case 'date':
                        return isValidDate(this.value);         
                    case 'checkbox':
                        if (this.required && !$(this.id).prop('checked')) {
                            return false;
                        }
                        return true;
                }
                return true;
            }
        };

        var validateForm = function(form, errorsInForm) {

            for (var i = 0; i < form.length; i++) {
                var input = new FormBuilderInput(form[i]);
                errorsInForm = addClassAfterBlur(input, input.isValid(), errorsInForm);
            }

            var $singleCheckboxes = $('input.js-single-checkbox');

            if ($singleCheckboxes.length) {

                $singleCheckboxes.each(function() {
                    if (!$('#'+this.id).prop('checked')) {
                        var input = new FormBuilderInput(this);
                        errorsInForm = addClassAfterBlur(input, input.isValid(), errorsInForm);
                    }
                });
            }       
            return errorsInForm;        
        };

    var $sectionGuideLink = $('a.js-form-section-guide-link');
    $sectionGuideLink.click(function(e) {
        e.preventDefault();
        if ($(this).hasClass("complete")) {
            $sectionGuideLink.removeClass('active');
            $(this).addClass('active');
            var id = $(this).data("id");
            console.log('#form-section-' + $(this).data("id"));
            $('div.form-section.show-hide-section').removeClass('active-section').addClass('hidden-section'); 
            $('#form-section-' + id ).removeClass('hidden-section').addClass('active-section');
            $('#form-section-' + id + ' select.js-select2-multiple').select2("destroy").select2(select2Options);

            $('div.section-head').removeClass('active');
            $('#form-section-head-'+id).addClass('active');

        }
        else {
            alert("Please complete the current section before you continue.");
        }
    });

    var $section;
    var $prev = $('a.js-form-builder-show-prev');
    var prev;   
    var $next = $('a.js-form-builder-show-next');
    var next;
    var current;
    $next.click(function(e){
        e.preventDefault();
        var input;
        current = $(this).data("current");
        next = $(this).data("next");
        console.log('current', current);
        console.log('next', next);

        $section = $('#form-section-'+current);
        errorsInForm = resetErrorsInForm();
        $('#form-section-' + current + ' .js-form-builder-control').each(function () {
            input = new FormBuilderInput(this);
            errorsInForm = addClassAfterBlur(input, input.isValid(), errorsInForm);
        });
        if (errorsInForm.count === 0) {
            $sectionGuideLink.removeClass('active');
            $('#form-section-guide-link-' + current ).addClass('complete');
            $('#form-section-guide-link-' + next ).addClass('active');

            $('div.section-head').removeClass('active');
            $('#form-section-head-'+next).addClass('active');

            if ($('#form-section-guide-link-' + next ).hasClass('last-section')) {
                // Auto approve the last step
                $('#form-section-guide-link-' + next ).addClass('complete');
            }
            $('#form-section-' + current ).removeClass('active-section').addClass('hidden-section');
            $('#form-section-' + next ).removeClass('hidden-section').addClass('active-section');
            $('#form-section-' + next + ' select.js-select2-multiple').select2("destroy").select2(select2Options);

            $([document.documentElement, document.body]).animate({
                scrollTop: $('#form-section-' + next).offset().top - 100
            }, 800);
        }
        else {
            showModalWithErrors( wrapErrorMessageSection(errorsInForm) );
        }
    });

    $prev.click(function(e){
        e.preventDefault();
        current = $(this).data("current");
        prev = $(this).data("prev");
        $('#form-section-'+current).removeClass('active-section').addClass('hidden-section');
        $('#form-section-'+prev).removeClass('hidden-section').addClass('active-section');
    });
    $('a.js-show-form-group-extra').click(function(e){
        e.preventDefault();
        $('div.form-group-extra').css("display","block");
    });
    $('#form-builder-show-all-sections').click(function(e){
        $sectionGuideLink.removeClass('active');
        $("div.form-section-buttons").hide();
        $('div.form-section.show-hide-section').removeClass('hidden-section').addClass('active-section');  
        $('div.section-head').removeClass('active');
    });
        var resetForm = function(form) {
            for (var i = 0; i < form.length; i++) {
                var input = new FormBuilderInput(form[i]);
                $(input.id+'-form-group').removeClass('has-error').removeClass('has-success');
                $(input.id).val('');
                // this.reset();
            } 
            $('#mail-receipt').prop('checked', false);  
        };
        
        // Validates that the input string is a valid date formatted as "dd-mm-yyyy"
        var isValidDate = function isValidDate(dateString) {
            // First check for the pattern
            if(!dateString.match(/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/)) {
                return false;
            }
            // Parse the date parts to integers
            var parts = dateString.split("/");

            var year = parseInt(parts[2], 10);
            // We must get day and month dependent on the format set on server
            if ( FormBuilderDatePicker.format === 'dd/mm/yyyy' ) {
                // Rest of wordld
                var day = parseInt(parts[0], 10);
                var month = parseInt(parts[1], 10);         
            }
            else if ( FormBuilderDatePicker.format === 'mm/dd/yyyy' ) {
                // United States
                var month = parseInt(parts[0], 10);
                var day = parseInt(parts[1], 10);     
            }

            // Check the ranges of month and year
            var year_now = new Date().getFullYear();

            if(year < 1900 || year > 2100 || month === 0 || month > 12){
                return false;
            }

            var monthLength = [ 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31 ];

            // Adjust for leap years
            if(year % 400 === 0 || (year % 100 !== 0 && year % 4 === 0)){
                monthLength[1] = 29;
            }

            // Check the range of the day
            return day > 0 && day <= monthLength[month - 1];
        };

        var addClassAfterBlur = function addClassAfterBlur(input, valid, errorsInForm) {
            if(!valid) {
                $(input.id+'-form-group').addClass('has-error').removeClass('has-success');
                errorsInForm.count++;

                errorsInForm.report += "<li>" + $(input.id+'-report').html() + "</li>";
            }
            else {
                if (input.value !== '') {
                    $(input.id+'-form-group').removeClass('has-error').addClass('has-success');

                }
            }
            return errorsInForm;
        };
        
        // When a user leaves a form input
        $('body').on('blur', '.js-form-builder-control', function(e) {
            if(typeof $(this).serializeArray()[0] !== "undefined") {
                var input = new FormBuilderInput($(this).serializeArray()[0]);
                // console.log(input);          
                // Datepicker has time delay before blur so we must reset the input value ater 200ms
                // console.log(input.dataType);
                if (input.dataType === 'date') {
                    // setTimeout(function() { 
                    //  input.value = $(input.id).val();
                    //  addClassAfterBlur(input, input.isValid(), 0);
                    // }, 200);
                    //
                    if( !jQuery().fdatepicker ) {
                        addClassAfterBlur(input, input.isValid(), errorsInForm);
                    }
                    else {
                        if (input.isValid()) {
                            $(input.id+'-form-group').removeClass('has-error').addClass('has-success');

                        }
                    }
                }
                else {
                    addClassAfterBlur(input, input.isValid(), errorsInForm);
                }
            }
            else {


            }

        });

        // When a user enters a form input
        $('body').on('focus', '.js-form-builder-control', function(e) { 
            $('#'+this.id+'-form-group').removeClass('has-error').removeClass('has-success');
            $('.captcha-wrapper').show();
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

        if(jQuery().fdatepicker) {
            var today = new Date();
            // console.log('today', today);
            var dd = today.getDate();
            var mm = today.getMonth()+1; //January is 0!
            var yyyy = today.getFullYear();
            
            if(dd<10){
                dd='0'+dd;
            } 
            if(mm<10){
                mm='0'+mm;
            } 
            today = dd+'/'+mm+'/'+yyyy;
            // We must set today dependent on the format set on server
            if ( FormBuilderDatePicker.format === 'mm/dd/yyyy' ) {
                // United States
                today = mm+'/'+dd+'/'+yyyy;       
            }

            $('.js-date-picker.past input').fdatepicker({
                // initialDate: today,
                format: FormBuilderDatePicker.format,
                endDate: today,//dateInPast(13),
                // startDate: dateInPast(13),
                disableDblClickSelection: true,
                leftArrow:'<<',
                rightArrow:'>>',
                closeIcon:'X',
                closeButton: true
            }).on('hide', function (ev) {
                var input = new FormBuilderInput(this);
                addClassAfterBlur(input, input.isValid(), 0);
            });


            $('.js-date-picker.future input').fdatepicker({
                format: FormBuilderDatePicker.format,
                // format: 'mm-dd-yyyy hh:ii',
                startDate: today,
                disableDblClickSelection: true,
                leftArrow:'<<',
                rightArrow:'>>',
                closeIcon:'X',
                closeButton: true
            }).on('hide', function (ev) {
                var input = new FormBuilderInput(this);
                addClassAfterBlur(input, input.isValid(), 0);
            });     

            $('.js-date-picker.all input').fdatepicker({
                format: FormBuilderDatePicker.format,
                disableDblClickSelection: true,
                leftArrow:'<<',
                rightArrow:'>>',
                closeIcon:'X',
                closeButton: true
            }).on('hide', function (ev) {
                var input = new FormBuilderInput(this);
                addClassAfterBlur(input, input.isValid(), 0);
            }); 

            // Range

            var nowTemp = new Date();
            var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

            var datepickerListener = function (dateRangeStart, dateRangeEnd) {

                var $dateRangeStart = $('#' + dateRangeStart);
                var $dateRangeEnd = $('#' + dateRangeEnd);

                var checkin = $dateRangeStart.fdatepicker({
                    format: FormBuilderDatePicker.format,
                    onRender: function (date) {
                        return date.valueOf() < now.valueOf() ? 'disabled' : '';
                    }
                }).on('changeDate', function (ev) {
                    if (ev.date.valueOf() > checkout.date.valueOf()) {
                        var newDate = new Date(ev.date);
                        newDate.setDate(newDate.getDate() + 1);
                        checkout.update(newDate);
                    }
                    checkin.hide();
                    $dateRangeEnd[0].focus();
                }).on('hide', function (ev) {
                    var input = new FormBuilderInput( document.getElementById( dateRangeStart ) );
                    addClassAfterBlur(input, input.isValid(), 0);
                }).data('datepicker');

                var checkout = $dateRangeEnd.fdatepicker({
                    format: FormBuilderDatePicker.format,
                    onRender: function (date) {
                        return date.valueOf() <= checkin.date.valueOf() ? 'disabled' : '';
                    }
                }).on('changeDate', function (ev) {
                    checkout.hide();
                }).on('hide', function (ev) {
                    var input = new FormBuilderInput( document.getElementById( dateRangeEnd ) );
                    addClassAfterBlur(input, input.isValid(), 0);               
                }).data('datepicker'); 

            };

            var $datePickerInput = $('input.js-date-picker-range');
            if ($datePickerInput.length) {
                $datePickerInput.each(function() {
                    var dateRangeStart = this.id;//'#' + 
                    var dateRangeEnd = dateRangeStart.substring(0, dateRangeStart.length - 6)+'-end';
                    datepickerListener( dateRangeStart, dateRangeEnd );
                });
            }       
        }   

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
        
                // $fileInputs.each(function(){
                //     console.log(this.id);
                // });
                // console.log('$fileInputs', $fileInputs);
        // $('body').on('submit', '#request-form.ajax', function(e) {    
        //     e.preventDefault();
        //     editForm();
        // });
        $('body').on('submit', '#request-form.ajax', function(e) {
            e.preventDefault();
            // var result = submitForm(this);
            // console.log('result', result);
            // if (result) {
                
            // }
            // return result;
            return submitFormAjax(this);
        });
        $('body').on('submit', '#request-form.no-ajax', function(event) {
            console.log('#request-form.no-ajax');
            return submitForm(event, this);
        });         
        var submitForm = function(event, form) {
            var formData = new FormData(form);
            var $form = $(form);
            errorsInForm = validateForm( $form.serializeArray(), resetErrorsInForm() ); 
            if (errorsInForm.count === 0) {
                return true;
            }
            event.preventDefault();
            return false;
        };
        var submitFormAjax = function(form) {
            // event.preventDefault();
        // $('#request-form.ajax').submit(function(e) {
            var formData = new FormData(form);
            // console.log(formData);
            // console.log('#request-form');
            
            var $form = $(form);
            var ajax = $form.data('ajax');
            // console.log('ajax', ajax);
            var submit = $form.find(":submit");
            // errorsInForm = resetErrorsInForm();
            errorsInForm = validateForm( $form.serializeArray(), resetErrorsInForm() );
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
                    var ref = getRef();
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
                            saveSessionDetails(sessionDetailsName, JSON.stringify(serverResponse.session) );
                            if (FormBuilderAjax.debug) 
                                console.log('DEBUG [SAVED] serverResponse.session:', serverResponse.session);                          
                        }
                        if (serverResponse.location) {
                            // if (serverResponse.session) {//!== "undefined"
                                // console.log('serverResponse.session');
                                // console.log(serverResponse.session);
                                // console.log(JSON.stringify(serverResponse.session));
                                

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
                            grecaptcha.reset();// Reset global object grecaptcha (Declared via API) 

                            if (serverResponse.error_count === 0 && serverResponse.form_set === true && FormBuilderAjax.type !== "signup") {
                                resetForm( $form.serializeArray() );        
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
                                    saveSessionDetails(sessionDetailsName, JSON.stringify(serverResponse.session) );

                                    // $('.form-builder.groupings').slideUp();
                                    // $('#download-mask').removeClass('masked');   
                                    hideForm();                     
                                }   
                            } 
                            // else if (serverResponse.session !== "undefined") {
                            //     console.log(serverResponse.session);
                            //     saveSessionDetails(sessionDetailsName, JSON.stringify(serverResponse.session) );
                            //     // removeMarketingSignUp(); 
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
                showModalWithErrors( wrapErrorMessage(errorsInForm) );
            }
            return false;
        };

        var showModalWithErrors = function($msg) {
            
            $('#form-builder-reveal-content').html( $msg );
            // var $modal = $('#form-builder-reveal');
            if(typeof modal !== null) {
                // $modal.foundation('open');
                modal.style.display = "block";
            }
            else {
                alert("Please fill in the required fields!");
            } 
        };

        $('a.js-add-row').click(function(e) {

            e.preventDefault();
            // var max = 3;
            var $addButton = $(this);
            // var count = $addButton.data('count');
            // count++;$addButton.text(count);$addButton.data('count', count);
            // var max = $addButton.data('max');
            var $removeButton = $( $addButton.data('remove-button') );
            var $countInput = $( $addButton.data('count-input-id') );
            // $countInput.val(10);
            var count = $countInput.val();
            var min = parseInt($countInput.attr('min'));
            var max = parseInt($countInput.attr('max'));
            // return false;
            disableAddRemoveButtons($addButton, $removeButton); 

            if (count < max) {
                FormBuilderAjax.count = count;
                FormBuilderAjax.tabindex = parseInt( $addButton.attr('tabindex') );
                FormBuilderAjax.formId = parseInt( $addButton.data('form-id') );
                FormBuilderAjax.action = "wp_swift_" + $addButton.data('action');   
                // console.log('ForFmBuilderAjax', FormBuilderAjax);
                // $(this).attr('tabindex', 201);
                $.post(FormBuilderAjax.ajaxurl, FormBuilderAjax, function(response) {
                    // console.log('response', response);
                    var serverResponse = JSON.parse(response);
                    count = serverResponse.count;
                    // console.log('count', serverResponse.count);
                    $( serverResponse.html ).insertBefore( "#" + $addButton.data('group') + "-add-remove-group" );
                    $addButton.attr('tabindex', serverResponse.tabindex);
                    $addButton.data('count', count);
                    $countInput.val(count);
                    // $addButton.text(serverResponse.count);
                    // $removeButton.data('count', serverResponse.count);
                    // $removeButton.text('Remove ' + serverResponse.count);
                    // $removeButton.attr('disabled', false);
                    // if (serverResponse.count === max) {
                    //  $addButton.attr('disabled', true);
                    // }
                    checkAddRemoveButtons($addButton, $removeButton, count, max);   
                });
            }
            else {
                alert("Maximum Reached!\nSorry, you cannot add anymore rows.");
            }
        }); 

        $('a.js-remove-row').click(function(e) {

            e.preventDefault();
            var $removeButton = $(this);
            var $addButton = $($removeButton.data('add-button'));
            disableAddRemoveButtons($addButton, $removeButton);
            // var $countInput = 
            // $addButton.attr('disabled', true);
            // $removeButton.attr('disabled', true);
            // var count = $removeButton.data('count');
            // var count = $addButton.data('count');
            // console.log('count', count);
            var $countInput = $( $addButton.data('count-input-id') );
            // $countInput.val(10);
            var count = $countInput.val();

            // return false; 
            
            if ( count > 0 ) {
                // var max = $addButton.data('max');
            var min = parseInt($countInput.attr('min'));
            var max = parseInt($countInput.attr('max'));            
                var keys = $addButton.data('keys');
                for (var i = 0; i < keys.length; i++) {
                    input = '#'+keys[i] + '-' + count;
                    var $inputGroup = $(input + '-form-group');
                    // var $input = $(input);
                    // $input.attr('disabled', true);
                    $inputGroup.remove();
                    // console.log(input + '-form-group');
                }
                count--;
                // $addButton.data('count', count);
                // console.log('count', count);
                $countInput.val(count);


                // $removeButton.data('count', count);

                // $('#add-row').data('count', count);
                // $('#remove-row').data('count', count);
                // if (count === 0) {
                //  $removeButton.attr('disabled', true);
                //  $addButton.attr('disabled', false);
                // } 
                // // else if (count === 1) {
                // //   $removeButton.attr('disabled', true);
                // //   $addButton.attr('disabled', false);
                // // }
                // else if (count > 0 && count < max){
                //  $addButton.attr('disabled', false);
                // }
                // else if (count > 0 && count === max){
                //  $addButton.attr('disabled', true);
                //  $removeButton.attr('disabled', false);
                // }    
                checkAddRemoveButtons($addButton, $removeButton, count, max);
            }
            else {
                alert("Minimum Reached!\nSorry, you cannot remmove anymore rows.");
                $removeButton.attr('disabled', true);
            }


            // var $removeRow = $('#row-'+count);
            // $removeRow.remove();

            // 
            // 
        
        }); 

        // var $addButton = $('#add-row-parts-list');   
        // var keys = $addButton.data('keys');
        // var input;
        // for (var j = 1; j <= 10; j++) {
        //  for (var i = 0; i < keys.length; i++) {
        //      input = '#'+keys[i] + '-' + j;
        //      // var $inputGroup = $(input + '-form-group');
        //      // var $input = $(input);
        //      // $input.attr('disabled', true);
        //      // $inputGroup.remove();
        //      console.log(input + '-form-group');
        //  }
        //  console.log('');
        // }
    });

    var disableAddRemoveButtons = function($addButton, $removeButton) {
        $addButton.attr('disabled', true);
        $removeButton.attr('disabled', true);   
    };
    var checkAddRemoveButtons = function($addButton, $removeButton, count, max) {
        // console.log('count', count, 'max', max);
        if (count === 0) {
            $removeButton.attr('disabled', true);
            $addButton.attr('disabled', false);
        } 
        // else if (count === 1) {
        //  $removeButton.attr('disabled', true);
        //  $addButton.attr('disabled', false);
        // }
        else if (count > 0 && count < max){
            $addButton.attr('disabled', false);
            $removeButton.attr('disabled', false);
        }
        else if (count > 0 && count === max){
            $addButton.attr('disabled', true);
            $removeButton.attr('disabled', false);
        }
    };

    var saveSessionDetails = function(name, value) {
        // window.sessionStorage
        // console.log('Saving: ', value);
        if (typeof(Storage) !== "undefined") {
            localStorage.setItem(name, value);
        }   
    };

    var getSessionDetails = function(name) {
        // window.sessionStorage
        if (typeof(Storage) !== "undefined") {
            var storedSessionDetails = JSON.parse(localStorage.getItem(name));
            return storedSessionDetails;
        }   
    };

    var resetSessionDetails = function(name) {
        if (typeof(Storage) !== "undefined") {
            localStorage.removeItem(name);
            console.log('Session date cleared');
        }   
    };    

    var wrapErrorMessage = function(errorsInForm) { 
        var $html = '<div id="form-error-message" class="form-message error ajax">';
            $html += '<h3 class="heading">Errors Found</h3>';
            $html += '<div class="error-content">';
                $html += '<p>We\'re sorry, there has been an error with the form input. Please rectify the ' + errorsInForm.count + ' errors below and resubmit.</p>';   
                $html += '<ul>' + errorsInForm.report + '</ul>';
            $html += '</div>';
        $html += '</div>';  
        return $html;
    };

    var wrapErrorMessageSection = function(errorsInForm) {
        var $html = '<div id="form-error-message" class="form-message error ajax">';
            $html += '<h3 class="heading">Errors Found</h3>';
            $html += '<div class="error-content">';
                $html += '<p>We\'re sorry, there has been an error in this section. Please rectify the ' + errorsInForm.count + ' errors below before you can continue.</p>';   
                $html += '<ul>' + errorsInForm.report + '</ul>';
            $html += '</div>';
        $html += '</div>';  
        return $html;
    };

 

    jQuery(document).ready(function($) {
        // $('div.form-builder.wrapper.hide').removeClass('hide').slideDown();
        $('body').on('click', '#js-edit-form', function(e) {    
            e.preventDefault();
            editForm();
        });   
        $('body').on('click', '#js-hide-form', function(e) {    
            e.preventDefault();
            hideForm();
        }); 

        // var details = getSessionDetails(sessionDetailsName);
        // // console.log('details', details);

        // if (details) {
            
        //     if(typeof details.email !== "undefined" ) {
        //         // document.getElementById( "form-email" ).value = details.email;
        //         $('#form-email').val(details.email);
        //     }
        //     if(typeof details.email !== "undefined") {
        //         // document.getElementById( "form-first-name" ).value = details.first_name;
        //         $('#form-first-name').val(details.first_name);
        //     }
        //     if(typeof details.email !== "undefined") {
        //         // document.getElementById( "form-last-name" ).value = details.last_name;
        //         $('#form-last-name').val(details.last_name);
        //     }
        //     if(typeof details.phone !== "undefined") {
        //         // document.getElementById( "form-phone" ).value = details.phone;
        //         $('#form-phone').val(details.phone);
        //     }   

        //     // $('.form-builder.groupings').slideUp();
        //     // $('#download-mask').removeClass('masked');
        //     // localStorage.clear();
        //     // hideForm();
        // }



            // $('#form-taoglas-products').select2();
        if( jQuery().fdatepicker ) {
            $('select.js-select2-multiple').select2(select2Options);
        }
        
        // {
        //    theme: "classic"
        //  }
        //                 
    });

    function showDownloads() {
        $('#download-mask').removeClass('masked');
    }
    function hideDownloads() {
        $('#download-mask').addClass('masked');
    }
    function editForm() {
        $('#signup-area-wrapper').removeClass('hide-form');
        $('#edit-form-wrapper').addClass('hide');
        $('#js-hide-form').show();
        hideDownloads();
    }
    function hideForm() {
        // console.log('hideForm');
        $('#signup-area-wrapper').addClass('hide-form');
        $('#edit-form-wrapper').removeClass('hide');
        $('#js-hide-form').hide();
        showDownloads();
    }
    function removeMarketingSignUp() {
        var $signUpHtml = $('div.form-group.sign-up');
        $signUpHtml.each(function(){
            $(this).remove();
        });
    } 
    var getUrlParameter = function (sParam) {
        var sPageURL = window.location.search.substring(1),
            sURLVariables = sPageURL.split('&'),
            sParameterName,
            i;

        for (i = 0; i < sURLVariables.length; i++) {
            sParameterName = sURLVariables[i].split('=');

            if (sParameterName[0] === sParam) {
                return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
            }
        }
    };
    // And this is how you can use this function assuming the URL is,
    // http://dummy.com/?technology=jquery&blog=jquerybyexample.
    // var ref = getUrlParameter('ref');
    // var blog = getUrlParameter('blog');
    // console.log('ref', ref);
    // console.log('blog', blog);    
    var getRef = function() {
        return getUrlParameter('ref');
    }; 

    // var resModal = function() {
    //     // Get the modal
    //     var modal = document.getElementById("form-builder-reveal");
    //     // var open = function() {
    //     //     console.log('open');
    //     //      modal.style.display = "block";
    //     // };       
    // }; 

        // Get the modal
        var modal = document.getElementById("form-builder-reveal");
        // modal.open = function() {

        // };
        // console.log('modal', modal);

        // resModal.prototype = {
        //     open: function open() {
        //         console.log('open');
        //         modal.style.display = "block";
        //     }
        // };
        // // Get the button that opens the modal
        // var modalBtn = document.getElementById("myBtn");

        // Get the <span> element that closes the modal
        var modalClose = document.getElementsByClassName("fb-modal-close")[0];

        // When the user clicks on the button, open the modal 
        // modalBtn.onclick = function() {
        //   modal.style.display = "block";
        // };
        
        if(modal !== null) {
            // When the user clicks on <span> (x), close the modal
            modalClose.onclick = function() {
              modal.style.display = "none";
            };

            // When the user clicks anywhere outside of the modal, close it
            window.onclick = function(event) {
              if (event.target == modal) {
                modal.style.display = "none";
              }
            }; 
        }      
} 
//@end closure
})();