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
    // Validates that the input string is a valid date formatted as "dd-mm-yyyy"
    isValidDate: function isValidDate(dateString) {
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
    }       	
}
export { formBuilderUtilities as utils }