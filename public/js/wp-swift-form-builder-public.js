console.info('wp-swift-form-builder-public.js');
jQuery(document).ready(function($){
// console.log('fdatepicker');
// console.log($.fdatepicker);
// console.log();
// console.log(Datepicker);
// console.log();

// if(jQuery().fdatepicker) {
//     console.log(' //run plugin dependent code');
//  }
// if (typeof $().fdatepicker === "function") { 
	
// }
// console.log('fdatepicker', fdatepicker);
// if ($.isFunction(fdatepicker)) {
// console.log('$().fdatepicker is a function');
// }
// else {
// 	console.log('not a function');
// }
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
			initialDate: today,
			format: 'dd-mm-yyyy',
			endDate: today,
			disableDblClickSelection: true,
			leftArrow:'<<',
			rightArrow:'>>',
			closeIcon:'X',
			closeButton: true
		});
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
			    case 'date_time':
			    	return isValidDateTime(this.value);   	
			    case 'date':
			    	return isValidDate(this.value); 
			    case 'password':
				   	return passwordCheck(this);
			}
			return true;
	    }
	};

	var validateForm = function(form) {
		var errorsInForm = 0;
		for (var i = 0; i < form.length; i++) {
			var input = new FormBuilderInput(form[i]);
			if(!input.isValid()) {
				$(input.id+'-form-group').addClass('has-error');
				errorsInForm++;
			}
			else {
				$(input.id+'-form-group').removeClass('has-error');
			}
		}
		return errorsInForm;		
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

	// When a user leaves a form input
	$('body').on('blur', '.js-form-builder-control', function(e) {	
		var input = new FormBuilderInput($(this).serializeArray()[0]);
		if(!input.isValid()) {
			$(input.id+'-form-group').addClass('has-error');
		}
		else {
			$(input.id+'-form-group').addClass('has-success');
		}
	});

    // When a user enters a form input
	$('body').on('focus', '.js-form-builder-control', function(e) {	
		$('#'+this.id+'-form-group').removeClass('has-error').removeClass('has-success');
	});
});