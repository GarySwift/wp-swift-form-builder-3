import { FormBuilderInput } from './fb-object';
import SecureLS from 'secure-ls';// https://github.com/softvar/secure-ls
var formBuilderUtilities = {
    modal: null,
    select2Options: {
        maximumSelectionLength: 2
    },
    secureLS: new SecureLS({
        encodingType: 'rabbit', 
        isCompression: false, 
        encryptionSecret: FormBuilderAjax.encryptionSecret
    }),    
	showAndRequireInput: function(id) {
		// var showAndRequireInput = function (id) {
		$(id+'-form-group').removeClass('hide');
		// $(id+'-form-group').addClass('required');
		$(id).removeClass('hide');
		$(id+'-form-group'+' label').addClass('required'); 
		$(id).attr('disabled', false);
		$(id).attr('required', true);
		$(id).focus();
	},
	hideAndUnrequireInput: function(id) {
		// Hide the state input
		// var hideAndUnrequireInput = function (id) {
		$(id+'-form-group').addClass('hide');
		// $(id+'-form-group').removeClass('required');
		$(id).addClass('hide');
		$(id+'-form-group'+' label').removeClass('required');
		$(id).attr('disabled', true);
		$(id).attr('required', false);
		$(id).val('');
	},
    validateForm: function(form, errorsInForm) {

        for (var i = 0; i < form.length; i++) {
            var input = new FormBuilderInput(form[i]);
            errorsInForm = this.addClassAfterBlur(input, input.isValid(), errorsInForm);
        }

        var $singleCheckboxes = $('input.js-single-checkbox');

        if ($singleCheckboxes.length) {

            $singleCheckboxes.each(function() {
                if (!$('#'+this.id).prop('checked')) {
                    var input = new FormBuilderInput(this);
                    errorsInForm = this.addClassAfterBlur(input, input.isValid(), errorsInForm);
                }
            });
        }       
        return errorsInForm;        
    }, 
    addClassAfterBlur: function addClassAfterBlur(input, valid, errorsInForm) {
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
    },
    resetErrorsInForm: function() {
        return {
            count: 0,
            report: ''
        };
    },
    getRef: function() {
        return this.getUrlParameter('ref');
    },
    getUrlParameter: function (sParam) {
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
    },
    showDownloads: function() {
        $('#download-mask').removeClass('masked');
    },
    hideDownloads: function() {
        $('#download-mask').addClass('masked');
    },
    editForm: function() {
        $('#signup-area-wrapper').removeClass('hide-form');
        $('#edit-form-wrapper').addClass('hide');
        $('#js-hide-form').show();
        this.hideDownloads();
    },
    hideForm: function() {
        // console.log('hideForm');
        $('#signup-area-wrapper').addClass('hide-form');
        $('#edit-form-wrapper').removeClass('hide');
        $('#js-hide-form').hide();
        this.showDownloads();
    },
    resetForm: function(form) {
        // grecaptcha.reset();
        for (var i = 0; i < form.length; i++) {
            var input = new FormBuilderInput(form[i]);
            $(input.id+'-form-group').removeClass('has-error').removeClass('has-success');
            $(input.id).val('');
            // this.reset();
        } 
        $('#mail-receipt').prop('checked', false);  
    },  
    setModal: function() {
        // Get the modal
        this.modal = document.getElementById("form-builder-reveal");//this.getModal();
        var modal = this.modal; 
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
    },
    getModal: function() {
        return this.modal;//document.getElementById("form-builder-reveal");
    },
    removeMarketingSignUp: function () {
        var $signUpHtml = $('div.form-group.sign-up');
        $signUpHtml.each(function(){
            $(this).remove();
        });
    },
    showModalWithErrors: function($msg) {
        var modal = this.getModal();
        $('#form-builder-reveal-content').html( $msg );
        // var $modal = $('#form-builder-reveal');
        if(typeof modal !== null) {
            // $modal.foundation('open');
            modal.style.display = "block";
        }
        else {
            alert("Please fill in the required fields!");
        } 
    },
    wrapErrorMessage: function(errorsInForm) { 
        var $html = '<div id="form-error-message" class="form-message error ajax">';
            $html += '<h3 class="heading">Errors Found</h3>';
            $html += '<div class="error-content">';
                $html += '<p>We\'re sorry, there has been an error with the form input. Please rectify the ' + errorsInForm.count + ' errors below and resubmit.</p>';   
                $html += '<ul>' + errorsInForm.report + '</ul>';
            $html += '</div>';
        $html += '</div>';  
        return $html;
    },
    wrapErrorMessageSection: function(errorsInForm) {
        var $html = '<div id="form-error-message" class="form-message error ajax">';
            $html += '<h3 class="heading">Errors Found</h3>';
            $html += '<div class="error-content">';
                $html += '<p>We\'re sorry, there has been an error in this section. Please rectify the ' + errorsInForm.count + ' errors below before you can continue.</p>';   
                $html += '<ul>' + errorsInForm.report + '</ul>';
            $html += '</div>';
        $html += '</div>';  
        return $html;
    },
    getNow: function() {
        var nowTemp = new Date();
        return new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
    }                                           	
}
export { formBuilderUtilities as utils }