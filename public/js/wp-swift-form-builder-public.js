console.info('wp-swift-form-builder-public.js');
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
	console.log(dateInPast);
	return 	dateInPast;	
};
jQuery(document).ready(function($){
	// FormBuilderDatePicker is set on server using wp_localize_script

	// dateInPast(13);

	if(jQuery().fdatepicker) {

		var today = new Date();
		var dd = today.getDate();
		var mm = today.getMonth()+1; //January is 0!
		var yyyy = today.getFullYear();
		
		if(dd<10){
		    dd='0'+dd;
		} 
		if(mm<10){
		    mm='0'+mm;
		} 
		today = dd+'-'+mm+'-'+yyyy;		

		$('.js-date-picker input').fdatepicker({
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
			console.log('FormBuilderDatePicker');
		});

		// jQuery().fdatepicker.hide(function(e) {
			
		// });
		// }).hide(function() {
		// 	console.log('FormBuilderDatePicker');
		// });
		// Range

		var nowTemp = new Date();
		var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

		var datepickerListener = function (dateRangeStart, dateRangeEnd) {

			var checkin = $(dateRangeStart).fdatepicker({
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
				$(dateRangeEnd)[0].focus();
			}).data('datepicker');

			var checkout = $(dateRangeEnd).fdatepicker({
				format: FormBuilderDatePicker.format,
				onRender: function (date) {
					return date.valueOf() <= checkin.date.valueOf() ? 'disabled' : '';
				}
			}).on('changeDate', function (ev) {
				checkout.hide();
			}).data('datepicker'); 

		};

		var $datePickerInput = $('input.js-date-picker-range');
		if ($datePickerInput.length) {
			$datePickerInput.each(function() {
				var dateRangeStart = '#' + this.id;
				var dateRangeEnd = dateRangeStart.substring(0, dateRangeStart.length - 6)+'-end';
				datepickerListener( dateRangeStart, dateRangeEnd );
			});
		}		
	}	

	$('.js-other-value').removeClass('css-hide').hide();

	$('.js-other-value-event select').change(function() {
		var input = new FormBuilderInput( $(this).serializeArray()[0] );
		if (input.value === 'other') {
			$(input.id + '-other-form-group').slideDown();
		}
		else {
			$(input.id + '-other-form-group').slideUp();
			$(input.id + '-other').val('');
		}
	});


	$('.js-other-value-event input[type=checkbox]').change(function() {
		var input = new FormBuilderInput( this );
		if (input.value === 'other') {
			
			if (this.checked) {
				$(input.id + '-other-form-group').slideDown();
			}
			else {
				$(input.id + '-other-form-group').slideUp();
				$(input.id + '-other').val('');
			}
		}		
	});

	// Form Input Object
	var FormBuilderInput = function FormBuilderInput(input) {
		this.name = input.name;
		this.value = input.value;
		this.id = '#'+(this.name.replace(/[\[\]']+/g,''));
		this.required = $(this.id).prop('required');
		this.type = $(this.id).prop('type');
		this.dataType = $(this.id).data('type');	
	};

	// Instance methods
	FormBuilderInput.prototype = {
		errorCount: 0,
		feedbackMessage: '', 
		isValid: function isValid() {
			// console.log('this.dataType', this.dataType);
			// console.log('this.required', this.required);
			// console.log('this.value', this.value);
			// console.log('');
			console.log(this);
		  	var re;
		  	if(this.required && this.value==='') {
		  		return false;
		  	}
			switch (this.dataType) {
				case 'number':
					return !isNaN(this.value);
			    case 'url':
			        re = /^(http(?:s)?\:\/\/[a-zA-Z0-9]+(?:(?:\.|\-)[a-zA-Z0-9]+)+(?:\:\d+)?(?:\/[\w\-]+)*(?:\/?|\/\w+\.[a-zA-Z]{2,4}(?:\?[\w]+\=[\w\-]+)?)?(?:\&[\w]+\=[\w\-]+)*)$/i;
			        return re.test(this.value);
			  	case 'email':
			      	re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
			      	return re.test(this.value); 
			    case 'select':	
			    	return this.value.toLowerCase().substring(0, 6) !== 'select';		
			    case 'date':
			    	if(!jQuery().fdatepicker) {
			    		return isValidDate(this.value);
			    	}
			    	
			    case 'checkbox':
			    	console.log(this.id);
			    	if (this.required && !$(this.id).prop('checked')) {
			    		return false;
			    	}
			    	
			}
			return true;
	    }
	};

	var validateForm = function(form) {
		var errorsInForm = 0;
		for (var i = 0; i < form.length; i++) {
			var input = new FormBuilderInput(form[i]);
			// if(!input.isValid()) {
			// 	$(input.id+'-form-group').addClass('has-error');
			// 	errorsInForm++;
			// }
			// else {
			// 	$(input.id+'-form-group').removeClass('has-error');
			// }
			errorsInForm = addClassAfterBlur(input, input.isValid());
		}
		console.log('');
		var $singleCheckboxes = $('input.js-single-checkbox');

		if ($singleCheckboxes.length) {

			$singleCheckboxes.each(function() {
				if (!$('#'+this.id).prop('checked')) {
					var input = new FormBuilderInput(this);
					errorsInForm = addClassAfterBlur(input, input.isValid());
				}




				// console.log('#'+this.id);
				// console.log($('#'+this.id).prop('checked'));
				// console.log( $('#'+this.id).prop('required') );

				// errorsInForm++;

			});
		}		
		return errorsInForm;		
	};
	
	// Validates that the input string is a valid date formatted as "dd-mm-yyyy"
	var isValidDate = function isValidDate(dateString) {
	    // First check for the pattern
	    if(!dateString.match(/^(\d{1,2})-(\d{1,2})-(\d{4})$/)) {
	    	return false;
	    }
	    // Parse the date parts to integers
	    var parts = dateString.split("-");

	    var day = parseInt(parts[0], 10);
	    var month = parseInt(parts[1], 10);
	    var year = parseInt(parts[2], 10);

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

	$('#request-form').submit(function(e){	
		e.preventDefault();
		var errorsInForm = validateForm( $(this).serializeArray() );
		var form = $(this);
		var submit = form.find(":submit");
		submit.prop('disabled', true);

		if (errorsInForm === 0) {
			// FormBuilderAjax is set on server using wp_localize_script
			if(typeof FormBuilderAjax !== "undefined") {
				FormBuilderAjax.form = $(this).serializeArray();
				FormBuilderAjax.id = form.data('id');
				FormBuilderAjax.action = "wp_swift_submit_request_form";

				$.post(FormBuilderAjax.ajaxurl, FormBuilderAjax, function(response) {
					var serverResponse = JSON.parse(response);
					$('#form-builder-reveal-content').html(serverResponse.html);
					var $modal = $('#form-builder-reveal');
					
					if(typeof $modal !== "undefined") {
						$modal.foundation('open');
						submit.prop('disabled', false);	
					}
				});	
			}
		}
		else {
			alert("Please fill in the required fields!");
			submit.prop('disabled', false);
		}
		return false;
	});

	var addClassAfterBlur = function addClassAfterBlur(input, valid, errorsInForm) {
		if(!valid) {
			$(input.id+'-form-group').addClass('has-error');
			errorsInForm++;
		}
		else {
			$(input.id+'-form-group').addClass('has-success');
		}
		return errorsInForm;
	};
	// When a user leaves a form input
	$('body').on('blur', '.js-form-builder-control', function(e) {	
		var input = new FormBuilderInput($(this).serializeArray()[0]);
		// Datepicker has time delay before blur so we must reset the input value ater 200ms
		if (input.dataType === 'date') {
			setTimeout(function() { 
				input.value = $(input.id).val();
				addClassAfterBlur(input, input.isValid(), 0);
			}, 200);
		}
		else {
			addClassAfterBlur(input, input.isValid(), 0);
		}
	});

    // When a user enters a form input
	$('body').on('focus', '.js-form-builder-control', function(e) {	
		$('#'+this.id+'-form-group').removeClass('has-error').removeClass('has-success');
	});
	$("input.js-single-checkbox").change(function() {
	    if(this.checked) {
	        $('#'+this.id+'-form-group').removeClass('has-error').removeClass('has-success');
	    }
	    else {
	    	$('#'+this.id+'-form-group').addClass('has-error').removeClass('has-success');
	    }
	});	
});