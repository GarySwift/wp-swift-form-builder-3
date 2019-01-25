jQuery(document).ready(function($){

	// $('#form1-country').change(function(){ 
	// 	if ($(this).val() === 'United States') { // if US is selected, we must show the states input
	// 		showAndRequireInput('#form1-state-form-group');
	// 		$('#form1-state-form-group select').focus();
	// 	}
	// 	else { // Or make sure it is hidden
	// 		hideAndUnrequireInput('#form1-state-form-group')
	// 	}
	// });
	// Show state when United States is selected
	$('#form-country').change(function(){ 
		console.log('$(this).val()', $(this).val());
		var us = 'united-states';//United States
		if ($(this).val() === us) { // if US is selected, we must show the states input
			showAndRequireInput('#form-state');
			
		}
		else { // Or make sure it is hidden
			hideAndUnrequireInput('#form-state')
		}
	});
	// show the state input
	var showAndRequireInput = function (id) {
		console.log('show id', id);
		$(id+'-form-group').removeClass('hide');
		// $(id+'-form-group').addClass('required');
		$(id).removeClass('hide');
		$(id+'-form-group'+' label').addClass('required'); 
		$(id).attr('disabled', false);
		$(id).attr('required', true);
		$(id).focus();
	};
	// Hide the state input
	var hideAndUnrequireInput = function (id) {
		console.log('hide id', id);
		$(id+'-form-group').addClass('hide');
		// $(id+'-form-group').removeClass('required');
		$(id).addClass('hide');
		$(id+'-form-group'+' label').removeClass('required');
		$(id).attr('disabled', true);
		$(id).attr('required', false);
		$(id).val('');
	};






	/**
	 * This is the summary for a DocBlock.
	 *
	 * This is the description for a DocBlock. This text may contain
	 * multiple lines and even some _markdown_.
	 *
	 * * Markdown style lists function too
	 * * Just try this out once
	 *
	 * The section after the description contains the tags; which provide
	 * structured meta-data concerning the given element.
	 *
	 * @author  		Gary Swift <gary@brightlight.ie>
	 *
	 * @since 			1.0
	 * 
	 * @link 			http://docs.phpdoc.org/references/phpdoc/basic-syntax.html
	 *
	 * @param int    	$example  This is an example function/method parameter description.
	 * @param string 	$example2 This is a second example.
	 */
	
	var $extraFreq = $('.form-group.extra-frequencies');
	var otherFreqID = '#form-other-frequencies-needed-form-group';
	var insertButtonHtml = '<div class="form-group"><a href="#" class="warning button js-toggle-extra-freq" id="toggle-extra-freq">Show All Frequencies</a></div>';
	var insertButtonViewStatus = false;

	$extraFreq.each(function(e) {
		$(this).css('display','block').hide();
	});
	$( insertButtonHtml ).insertAfter( otherFreqID );


	// When a user click the Frequencies button
	$('body').on('click', '.js-toggle-extra-freq', function(e) {	
		e.preventDefault();
		insertButtonViewStatus = !insertButtonViewStatus;
		var $link = $(this);
		if (insertButtonViewStatus) {
			$extraFreq.slideDown();
			$link.html('Hide Extra Frequencies');
		} else {
			$extraFreq.slideUp();
			$link.html('Show All Frequencies');
		}
		// 
		// $extraFreq.each(function(e) {
		// 	$(this).toggle();
		// 	if ($(this).is(':visible')) {
		// 		$link.html('Hide Extra Frequencies');
		// 	}
		// 	else {
		// 		$link.html('Show All Frequencies');
		// 	}
		// });
	});	


		// $('#form-taoglas-products').select2();
	$('select.js-select2-multiple').select2();
	// {
	// 	  theme: "classic"
	// 	}	
});