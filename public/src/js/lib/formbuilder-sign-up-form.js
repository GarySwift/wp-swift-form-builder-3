export default function(session) {
	if(typeof MarketingAjax !== "undefined") {

		if (MarketingAjax.debug)
			console.log('DEBUG formbuilder-sign-up-form.js');
		// var taoglasDownload = null;
		var hasStorage = typeof(Storage) !== "undefined";
		// var session.name = "form-session-details";
		var storedSessionDetails = false;
		var $signUpFormSubmitButton = $('#sign-up-form-inline button[type=submit]');   
		var $checkBoxes = $('input.sign-up');
		var $signupGroup = $('.form-group.sign-up');
		var $form = $('#request-form');
		var type = $form.data('type');

		// console.log('$signupGroup.length', $signupGroup.length);
		// console.log('hasStorage', hasStorage);
		// console.log('1 *');
		if (hasStorage && localStorage.getItem(session.name) !== null) {
			storedSessionDetails = JSON.parse(localStorage.getItem(session.name));
					// storedSessionDetails = JSON.parse(storedSessionDetails);
			if ($signupGroup.length ) {
				// if (typeof(Storage) !== "undefined") {
			    	
					if (storedSessionDetails.subscribed === true) {
						$signupGroup.empty();
						if (type === 'signup') {
							$form.find(":submit").prop('disabled', true);
							alert( 'It looks like you already signed up!');
						}
					}
			}
			// else {
				// console.log('auto fill inputs');
				// console.log('2 storedSessionDetails', storedSessionDetails);
				// for (var i = storedSessionDetails.length - 1; i >= 0; i--) {
				// 	console.log(storedSessionDetails[i]);
				// }
			
			// }			
		}
		// console.log('2 *');
	
		// $signUpFormSubmitButton.prop('disabled', true);

		// $checkBoxes.change(function(e){
	 //  		var checkedCount = checkFields();
	 //  		if (checkedCount) {
	 //  			$signUpFormSubmitButton.prop('disabled', false);  
	 //  		}
	 //  		else {
	 //  			$signUpFormSubmitButton.prop('disabled', true);
	 //  		}
		// });
		
		function checkFields() {
			var checkedCount = 0;
			$checkBoxes.each(function () {
			  if (this.checked) {
			  	checkedCount++;
			  }
			});
			return checkedCount; 		
		}

		function camelCaseToDash(str) {
		    return str
		        .replace(/[^a-zA-Z0-9]+/g, '-')
		        .replace(/([A-Z]+)([A-Z][a-z])/g, '$1-$2')
		        .replace(/([a-z])([A-Z])/g, '$1-$2')
		        .replace(/([0-9])([^0-9])/g, '$1-$2')
		        .replace(/([^0-9])([0-9])/g, '$1-$2')
		        .replace(/-+/g, '-')
		        .toLowerCase();
		}		
	}
}