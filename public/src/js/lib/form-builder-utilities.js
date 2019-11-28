import { FormBuilderInput } from './form-builder-object';
var formBuilderUtilities = {
	// msg: function() {
	// 	return 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Obcaecati veritatis pariatur nulla voluptatibus aliquam ';
	// },
	showAndRequireInput: function(id) {
		// console.log('showAndRequireInput() *****');
		// console.log(id);
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
    }         	
}
export { formBuilderUtilities as utils }