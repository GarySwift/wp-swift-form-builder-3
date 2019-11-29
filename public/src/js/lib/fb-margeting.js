export default function(session) {
	var storedSessionDetails = false;
    var interceptCssClass;// The CSS class that we intercept (Set with MarketingAjax)
    var $interceptLink;// The DOM elements of that class

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

    // Add event-handler to links
    var setInterceptLinks = function($interceptLink) {
		if (MarketingAjax.debug) {
			console.log('DEBUG setInterceptLinks ()');
		}

		if (!session.subscribed() && $interceptLink) {

			// Change the cursor icon to alias
			$interceptLink.addClass('cursor-alias');

			// Detect left click
		    $interceptLink.click(function(e) {
				if (MarketingAjax.debug)
					console.log('DEBUG $interceptLink.click()');	    	 	
    			e.preventDefault();
	    		return redirect($(this));    
		    });
		    
		    // Detect right click on $interceptLink (datasheet buttons)
		    $interceptLink.contextmenu(function() {
		    	return redirect($(this));
			}); 
		}  	    
    };

    var redirect = function($link) {
    	var location = decodeURIComponent(window.location.href);
    	if ( MarketingAjax.showAlertBeforeRedirect ) {
	    	alert(
	    		"This file requires a sign up!" + "\n\n" +
	    		"You will be now redirected to a subscription page." + "\n\n" +
	    		"Fill in your details there and you will be brought back here and this file will become available to you." + "\n\n" +
	    		"Please click link again if your download does not begin automatically."
	    	);
    	}		    		
		var download = $link.attr('download');
		var downloadData = { id: '#'+$link.attr('id'), href: $link.attr('href'), location: location, download: false };
		if(typeof download !== "undefined") {
		  downloadData.download = true;
		}
	    if (MarketingAjax.debug) {
	    	console.log('DEBUG [Session Storage] downloadData: ', downloadData);
	    	console.log('DEBUG ...redirecting');
	    }		    		
		downloadData = JSON.stringify(downloadData);
		sessionStorage.setItem("taoglas-download", downloadData);		
		window.location.href = signUpURL + '?ref=' + location;  
		return false;     	
    };

	var getStoredSessionDetails = function () {
	    // Check if user is subscribed
	    if (session.hasStorage) {

	    	if ( localStorage.getItem(session.name) !== null ) {

		        storedSessionDetails = localStorage.getItem(session.name);
		        if (storedSessionDetails && storedSessionDetails !== "undefined") {
		        	storedSessionDetails = JSON.parse(storedSessionDetails);
		        }
		        else {
		        	storedSessionDetails = false;
		        }

	    	}

		    // Debug details
        	if (MarketingAjax.debug) {
        		console.log('DEBUG getStoredSessionDetails() storedSessionDetails: ', storedSessionDetails);
        	}
        	return storedSessionDetails;	        
	    } 
	    return false;
	};

    //@start Debug section
    var resetSessionDetails = function(name) {
        if (session.hasStorage) {
            localStorage.removeItem(name);
            console.log('DEBUG Session data cleared');
        }   
    }; 
    var clearUserData = function(storedSessionDetails, MarketingAjax) {
		if (MarketingAjax.debug)
			console.log('DEBUG clearUserData(): Clearing signupDeclined, storedSessionDetails; Set setInterceptLinks ()');    	

	    sessionStorage.removeItem(MarketingAjax.signupDeclined);
	    signupDeclined = sessionStorage.getItem(MarketingAjax.signupDeclined);
	      	
    	if (storedSessionDetails) {
	        resetSessionDetails(session.name);
    	}
    	setInterceptLinks($interceptLink);	    	
    };
    //@end Debug section

	// Source: https://codepen.io/jelmerdemaat/pen/brjKG
	// Source: http://pixelscommander.com/en/javascript/javascript-file-download-ignore-content-type/
	window.downloadFile = function (sUrl) {
	    //iOS devices do not support downloading. We have to inform user about this.
	    if (/(iP)/g.test(navigator.userAgent)) {
	       //alert('Your device does not support files downloading. Please try again in desktop browser.');
	       window.open(sUrl, '_blank');
	       return false;
	    }

	    //If in Chrome or Safari - download via virtual link click
	    if (window.downloadFile.isChrome || window.downloadFile.isSafari) {
	        //Creating new link node.
	        var link = document.createElement('a');
	        link.href = sUrl;
	        link.setAttribute('target','_blank');

	        if (link.download !== undefined) {
	            //Set HTML5 download attribute. This will prevent file from opening if supported.
	            var fileName = sUrl.substring(sUrl.lastIndexOf('/') + 1, sUrl.length);
	            link.download = fileName;
	        }

	        //Dispatching click event.
	        if (document.createEvent) {
	            var e = document.createEvent('MouseEvents');
	            e.initEvent('click', true, true);
	            link.dispatchEvent(e);
	            return true;
	        }
	    }

	    // Force file download (whether supported by server).
	    if (sUrl.indexOf('?') === -1) {
	        sUrl += '?download';
	    }

	    window.open(sUrl, '_blank');
	    return true;
	};

	window.downloadFile.isChrome = navigator.userAgent.toLowerCase().indexOf('chrome') > -1;
	window.downloadFile.isSafari = navigator.userAgent.toLowerCase().indexOf('safari') > -1;  


		    
    // Capture the quick sign-up from email
    // We don't know if they have responded to the 
    // sent email but we will assume that they have.
    var $mailChimpQuickFormSubmit = $('#mc-embedded-subscribe');
    if ($mailChimpQuickFormSubmit.length) {
    	$mailChimpQuickFormSubmit.click(function(e) {
    		var email = $('#mce-EMAIL').val();
    		if (email) {
    			var session = { email: email, subscribed: true };
		        if (session.hasStorage) {
		            localStorage.setItem(session.name, JSON.stringify(session));
		        } 		
    		}
    		else {
    			return false;	
    		}
    	});
    }

    var download = getUrlParameter('download');
    var downloadData;
    if (download && session.hasStorage) {
    	downloadData = sessionStorage.getItem("taoglas-download");
		downloadData = JSON.parse(downloadData);
		
		if ( jQuery.isPlainObject( downloadData ) ) {
			sessionStorage.clear();
			if (downloadData.download) {
				window.downloadFile(downloadData.href);
				history.pushState(null, "", downloadData.location); 
			}
			else {
				// open link
				window.history.replaceState(null, "", downloadData.location);// 'Object', 'Title', 'location'
				// window.open(downloadData.href, '_blank');
				window.location.href = downloadData.href;
			}
		}
    }     	

    if(typeof MarketingAjax !== "undefined") {
    	interceptCssClass = MarketingAjax.interceptCssClass;//The CSS class that we intercept
    	if (interceptCssClass) {
    		$interceptLink = $('a.' + interceptCssClass);
    		if (MarketingAjax.debug)
    			console.log('$interceptLink have been set for ' + interceptCssClass);
    	}
    	storedSessionDetails = getStoredSessionDetails();

		//@start Debug section
	    if (MarketingAjax.debugClearCacheAuto) {
		    setTimeout(function() {
		    	clearUserData(storedSessionDetails, MarketingAjax);
		    }, parseInt(MarketingAjax.debugTimeoutInterval) );
	    }	
	    if (MarketingAjax.debugClearUserData){
	    	console.log('DEBUG clearing user data');
	    	clearUserData(storedSessionDetails, MarketingAjax);
	    }	        
	    //@end Debug section

    	

    	if (MarketingAjax.modal) {
	        if (MarketingAjax.debugClearSignupDeclined) {
	        	console.log("DEBUG Clearing session storage for user signupDeclined");
	        	sessionStorage.removeItem(MarketingAjax.signupDeclined);
	        }
	        var signupDeclined = null;
	    	var $existingForm = $('#request-form');

	    	if ( session.hasStorage ) {
				signupDeclined = sessionStorage.getItem(MarketingAjax.signupDeclined);			
			}

			if (MarketingAjax.debug)
				console.log('DEBUG !storedSessionDetails', !storedSessionDetails, ', !signupDeclined', !signupDeclined, ', !$existingForm.length', !$existingForm.length);

	    	if ( !storedSessionDetails && !signupDeclined && !$existingForm.length ) {
		  		setTimeout(function() {
		  			if (MarketingAjax.debug)
		  				console.log('DEBUG Show modal in ' + MarketingAjax.time + 'ms.');

			        // This Ajax call will get the form HTML, inject it into the DOM, and open the modal
			        $.post(MarketingAjax.ajaxurl, MarketingAjax, function(response) {
			            var serverResponse = JSON.parse(response);
				        var $modal = $('#marketing-reveal');
				        var $modalContent = $('#marketing-reveal-content');
				        $modalContent.html(serverResponse.modal);
		                if(typeof $modal !== "undefined") {
		                    $modal.foundation('open');  

		                    // Detect when the modal is closed and use sessionStorage to record event
							$modal.on('closed.zf.reveal', function () {
								// save session variable
								if ( session.hasStorage ) {
									sessionStorage.setItem(MarketingAjax.signupDeclined, 1);
									signupDeclined = sessionStorage.getItem(MarketingAjax.signupDeclined);
						  			if (MarketingAjax.debug)
						  				console.log('DEBUG Modal has been closed and event has been saved in the session var signupDeclined');
								}
							});                 
		                }
			        });
		  		}, MarketingAjax.time);
	    	}
	    	else {
		    	if (MarketingAjax.debug)
		    		console.log('DEBUG Modal not set because user is already subscribed or has previously declined or this page already has a form with sign-up option');   		
	    	}
    	}
    	if (MarketingAjax.redirect) {

		    var signUpURL = MarketingAjax.signUpURL;

		    if (MarketingAjax.debug)
		    	console.log('DEBUG MarketingAjax', MarketingAjax);

			setInterceptLinks($interceptLink);
	

		    if (MarketingAjax.debug && MarketingAjax.debugClearCacheAuto && typeof storedSessionDetails.subscribed !== "undefined" && storedSessionDetails.subscribed === true) {
		    	console.log('DEBUG User is now subscribed');
		    	console.log('DEBUG User data (storedSessionDetails) will be cleared in ' + (MarketingAjax.debugTimeoutInterval/1000) + ' seconds.');
		    }
    	}
    }
}