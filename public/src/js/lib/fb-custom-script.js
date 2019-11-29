export default function(utils) {
	// $('#form1-country').change(function(){ 
	// 	if ($(this).val() === 'United States') { // if US is selected, we must show the states input
	// 		utils.showAndRequireInput('#form1-state-form-group');
	// 		$('#form1-state-form-group select').focus();
	// 	}
	// 	else { // Or make sure it is hidden
	// 		utils.hideAndUnrequireInput('#form1-state-form-group')
	// 	}
	// });
	// Show state when United States is selected
	$('#form-country').change(function(){ 
		var val = $(this).val();
		var countryStates = [
			{ country :'united-states', state: '#form-state' },
			{ country :'australia', state: '#form-australia-state' },
			{ country :'canada', state: '#form-canada-state' },
			{ country :'china', state: '#form-china-state' },
			{ country :'india', state: '#form-india-state' },
			{ country :'japan', state: '#form-japan-state' },
		];
		for (var i = 0; i < countryStates.length; i++) {
			if (countryStates[i].country === val) {
				utils.showAndRequireInput(countryStates[i].state);
			}
			else {
				utils.hideAndUnrequireInput(countryStates[i].state);
			}
		}
	});
	// // show the state input
	// var utils.showAndRequireInput = function (id) {
	// 	$(id+'-form-group').removeClass('hide');
	// 	// $(id+'-form-group').addClass('required');
	// 	$(id).removeClass('hide');
	// 	$(id+'-form-group'+' label').addClass('required'); 
	// 	$(id).attr('disabled', false);
	// 	$(id).attr('required', true);
	// 	$(id).focus();
	// };
	// // Hide the state input
	// var utils.hideAndUnrequireInput = function (id) {
	// 	$(id+'-form-group').addClass('hide');
	// 	// $(id+'-form-group').removeClass('required');
	// 	$(id).addClass('hide');
	// 	$(id+'-form-group'+' label').removeClass('required');
	// 	$(id).attr('disabled', true);
	// 	$(id).attr('required', false);
	// 	$(id).val('');
	// };






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
	var insertButtonHtml = '<div class="form-group" id="form-group-toggle-extra-freq"><a href="#" class="warning button js-toggle-extra-freq" id="toggle-extra-freq">Show All Frequencies</a></div>';
	var insertButtonViewStatus = false;

	$extraFreq.each(function(e) {
		$(this).hide();
	});
	$( insertButtonHtml ).insertAfter( otherFreqID );


	// When a user click the Frequencies button``
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
	// @start Hide show sections
	var $additionalSectionCellularAntenna = $('#form-section-2');
	var $additionalSectionExternalAntenna = $('#form-section-3');
	var $showAdditionalSectionCellularAntenna = $('#form-cellular-antenna-usage');
	var $showAdditionalSectionExternalAntenna = $('#form-external-antenna-usage');
	$additionalSectionCellularAntenna.removeClass('hide').hide();
	$additionalSectionExternalAntenna.removeClass('hide').hide();
	$showAdditionalSectionCellularAntenna.change(function() {
	    if(this.checked) {
	        $additionalSectionCellularAntenna.slideDown();
	    }
	    else {
	    	$additionalSectionCellularAntenna.hide();
	    }
	});
	$showAdditionalSectionExternalAntenna.change(function() {
	    if(this.checked) {
	        $additionalSectionExternalAntenna.slideDown();
	    }
	    else {
	    	$additionalSectionExternalAntenna.hide();
	    }
	});
	// @end Hide show sections
	// 
	//
	

    
}