// console.info('wp-swift-form-builder-public.js');
/**
 * Get a date in the past by reducing years from now date.
 * Very basic. Does not include leap years.
 * 
 * @param int 		years
 * @return string 	date
 */

var sessionDetailsName = "form-session-details";
// console.log('sessionDetailsName', sessionDetailsName);

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
	return 	dateInPast;	
};
jQuery(document).ready(function($){
		var errorsInForm = {
			count: 0,
			report: ''
		};	
	if(typeof FormBuilderAjax !== "undefined") {
		// console.log(FormBuilderAjax.updated);
	}
	$('#captcha-wrapper').removeClass('hide').hide();



// $('input.sign-up').click(function() {
// 	var checked = 0;
// 	$('input.sign-up').each(function () {
// 		console.log($(this).attr("id"), this.checked);
// 	    if (this.checked) {
// 	    	checked++;
// 	    } 
// 	});
//     if (checked > 0) {
//     	$('#submit-request-form').prop("disabled", false);
//     }
//     else {
//     	$('#submit-request-form').prop("disabled", true);
//     }
// });

	// FormBuilderDatePicker is set on server using wp_localize_script
	// Form Input Object
	var FormBuilderInput = function FormBuilderInput(input) {
		this.name = input.name;
		this.value = input.value;
		this.id = '#'+(this.name.replace(/[\[\]']+/g,''));
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

	var validateForm = function(form) {

		for (var i = 0; i < form.length; i++) {
			var input = new FormBuilderInput(form[i]);
			// console.log('input', input);
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

	var resetForm = function(form) {
		for (var i = 0; i < form.length; i++) {
			var input = new FormBuilderInput(form[i]);
			$(input.id+'-form-group').removeClass('has-error').removeClass('has-success');
			$(input.id).val('');
		    // this.reset();
		}	
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
	    // console.log('day', day);
	    // console.log('month', month);
	    // console.log('year', year);
	    // console.log(day > 0 && day <= monthLength[month - 1]);

	    // Check the range of the day
	    return day > 0 && day <= monthLength[month - 1];
	};

	var addClassAfterBlur = function addClassAfterBlur(input, valid, errorsInForm) {
		if(!valid) {
			$(input.id+'-form-group').addClass('has-error').removeClass('has-success');
			console.log('errorsInForm', errorsInForm);
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
		var input = new FormBuilderInput($(this).serializeArray()[0]);
		// Datepicker has time delay before blur so we must reset the input value ater 200ms
		// console.log(input.dataType);
		if (input.dataType === 'date') {
			// setTimeout(function() { 
			// 	input.value = $(input.id).val();
			// 	addClassAfterBlur(input, input.isValid(), 0);
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
	});

    // When a user enters a form input
	$('body').on('focus', '.js-form-builder-control', function(e) {	
		$('#'+this.id+'-form-group').removeClass('has-error').removeClass('has-success');
		$('#captcha-wrapper').show();
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


	$('#request-form.ajax').submit(function(e) {

		e.preventDefault();
		var $form = $(this);
		var submit = $form.find(":submit");
		var errorsInForm = validateForm( $form.serializeArray() );
		submit.prop('disabled', true);

		// errorsInForm.count = 0;
		if (errorsInForm.count === 0) {
			// FormBuilderAjax is set on server using wp_localize_script
			if(typeof FormBuilderAjax !== "undefined") {
				FormBuilderAjax.form = $form.serializeArray();
				FormBuilderAjax.id = $form.data('id');
				FormBuilderAjax.post = $form.data('post-id');
				FormBuilderAjax.action = "wp_swift_submit_" + $form.data('type') + "_form";//"wp_swift_submit_request_form";
				// FormBuilderAjax.type = $form.data('type');
				// var type = document.getElementById( "form-type" );
				// if (type) {
				// 	FormBuilderAjax.type = type.value;
				// }


				$.post(FormBuilderAjax.ajaxurl, FormBuilderAjax, function(response) {
					// console.log('response', response);
					var serverResponse = JSON.parse(response);
					console.log(serverResponse);

					if (serverResponse.location) {
						window.location = serverResponse.location;
					}
					else {
						$('#form-builder-reveal-content').html(serverResponse.html);
						var $modal = $('#form-builder-reveal');
						submit.prop('disabled', false);

						if (serverResponse.error_count === 0 && serverResponse.form_set === true && FormBuilderAjax.type !== "signup") {
							resetForm( $form.serializeArray() );		
						}

						if(typeof $modal !== "undefined") {
							$modal.foundation('open');	
						}
						if (FormBuilderAjax.type === "signup") {
							if (serverResponse.session) {//!== "undefined"
								saveSessionDetails(sessionDetailsName, JSON.stringify(serverResponse.session) );

								// $('.form-builder.groupings').slideUp();
								// $('#download-mask').removeClass('masked');	
								hideForm();						
							}	
						}	
					}					
				});	
			}
		}
		else {
			
			submit.prop('disabled', false);	
			$('#form-builder-reveal-content').html( wrapErrorMessage(errorsInForm) );
			var $modal = $('#form-builder-reveal');
			if(typeof $modal !== "undefined") {
				$modal.foundation('open');	
			}
			else {
				alert("Please fill in the required fields!");
			}				
		}
		return false;
	});	

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
				// 	$addButton.attr('disabled', true);
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
			// 	$removeButton.attr('disabled', true);
			// 	$addButton.attr('disabled', false);
			// } 
			// // else if (count === 1) {
			// // 	$removeButton.attr('disabled', true);
			// // 	$addButton.attr('disabled', false);
			// // }
			// else if (count > 0 && count < max){
			// 	$addButton.attr('disabled', false);
			// }
			// else if (count > 0 && count === max){
			// 	$addButton.attr('disabled', true);
			// 	$removeButton.attr('disabled', false);
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
	// 	for (var i = 0; i < keys.length; i++) {
	// 		input = '#'+keys[i] + '-' + j;
	// 		// var $inputGroup = $(input + '-form-group');
	// 		// var $input = $(input);
	// 		// $input.attr('disabled', true);
	// 		// $inputGroup.remove();
	// 		console.log(input + '-form-group');
	// 	}
	// 	console.log('');
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
	// 	$removeButton.attr('disabled', true);
	// 	$addButton.attr('disabled', false);
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
}

var getSessionDetails = function(name) {
	// window.sessionStorage
	if (typeof(Storage) !== "undefined") {
		var storedSessionDetails = JSON.parse(localStorage.getItem(name));
		return storedSessionDetails;
	}	
}

var wrapErrorMessage = function(errorsInForm) {
	// console.log('errorsInForm', errorsInForm);
	$html = '<div id="form-error-message" class="form-message error ajax">';
	    $html += '<h3 class="heading">Errors Found</h3>';
	    $html += '<div class="error-content">';
	        $html += '<p>We\'re sorry, there has been an error with the form input. Please rectify the ' + errorsInForm.count + ' errors below and resubmit.</p>';   
	        $html += '<ul>' + errorsInForm.report + '</ul>';
	    $html += '</div>';
	$html += '</div>';	
	return $html;
}

jQuery(document).ready(function($){
	$('body').on('click', '#js-edit-form', function(e) {	
		e.preventDefault();
		editForm();
	});	  
	$('body').on('click', '#js-hide-form', function(e) {	
		e.preventDefault();
		hideForm();
	});	

var details = getSessionDetails(sessionDetailsName);
// console.log('details', details);

if (details) {
	
	if(typeof details.email !== "undefined" ) {
		// document.getElementById( "form-email" ).value = details.email;
		$('#form-email').val(details.email);
	}
	if(typeof details.email !== "undefined") {
		// document.getElementById( "form-first-name" ).value = details.first_name;
		$('#form-first-name').val(details.first_name);
	}
	if(typeof details.email !== "undefined") {
		// document.getElementById( "form-last-name" ).value = details.last_name;
		$('#form-last-name').val(details.last_name);
	}
	if(typeof details.phone !== "undefined") {
		// document.getElementById( "form-phone" ).value = details.phone;
		$('#form-phone').val(details.phone);
	}	

	// $('.form-builder.groupings').slideUp();
	// $('#download-mask').removeClass('masked');
	// localStorage.clear();
	hideForm();
}	  	  
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