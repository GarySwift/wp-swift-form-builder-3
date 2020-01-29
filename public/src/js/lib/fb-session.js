var session = {
	// storedSessionDetails: false,
	name: "form-session-details",
	utils: null,
	hasStorage: typeof(Storage) !== "undefined",
	options: {
		popup: {
			in: 1000,
			out: 11000
		}
	},
    save: function(name, value) {
        if (this.hasStorage) {
            // Check if it already exists
            var storedSessionDetails = this.utils.secureLS.get(name);
            if (storedSessionDetails) {
                Object.assign(storedSessionDetails, value);
                value = JSON.stringify(storedSessionDetails);
            }
            else {
                value = JSON.stringify(value);
            }
            // localStorage.setItem(name, value);
			this.utils.secureLS.set(name, value);
        }   
    },
    get: function(name) {
        if (this.hasStorage) {
            // var storedSessionDetails = JSON.parse(localStorage.getItem(name));
            var storedSessionDetails = this.utils.secureLS.get(name);
            return storedSessionDetails;
        }
        return false;   
    },
    reset: function(name) {
        if (this.hasStorage) {
            // localStorage.removeItem(name);
            this.utils.secureLS.remove(name);
        }   
    }, 
    subscribed: function() {
    	var storedSessionDetails = this.get(this.name);
    	if (storedSessionDetails && typeof storedSessionDetails.subscribed !== "undefined" && storedSessionDetails.subscribed === true) {
    		return true;
    	}
    	return false;
    },      	
	sessionDetailsFill: function(utils) {
		this.utils = utils;
		var storedSessionDetails = false;
		var id;
		
		if (this.hasStorage && localStorage.getItem(this.name) !== null) {
			var remove = false;
			if (remove) {
				this.utils.secureLS.removeAll(); 
				console.clear(); 
				console.log('// remove all keys'); 
				return;
			}
			storedSessionDetails = this.utils.secureLS.get(this.name);
			if(FormBuilderAjax.debug) {
				console.log('DEBUG Hashed Stored Session Details: ', localStorage.getItem(this.name));
				console.log('DEBUG Stored Session Details: ', storedSessionDetails);
			}
			if (storedSessionDetails && storedSessionDetails !== "undefined") {				
				try {
				  	storedSessionDetails = JSON.parse(storedSessionDetails);
					var countInputAutoFills = 0;
					for (let key in storedSessionDetails) {
				     	id = '#'+key;
				     	if ($(id).length) {			     		
				     		if(typeof storedSessionDetails[key].hidden !== "undefined" && storedSessionDetails[key].val !== '') {
				     			this.utils.showAndRequireInput(id);
				     		}
				     		$(id).val(storedSessionDetails[key].val);
				     		countInputAutoFills++;
				     		$(id+'-form-group').addClass('has-success').removeClass('has-error');
				     	}
				     	else if (storedSessionDetails[key].type = "checkbox") {
				     		if ($(id + '-form-group').length) {
				     			var values = storedSessionDetails[key].val;
				     			$($(id + '-form-group input[type="checkbox"]')).each(function(index, el) {
				     				if (values.includes(this.value)) {
				     					$(this).attr("checked", true);
				     				}
				     			});
				     		}
				     	}
					}
					if (countInputAutoFills) {
						this.showMsg();
					}				  	
				}
				catch(error) {
				  	// console.error(error);
				}					
			}	
		}	
	},
	sessionDetailsEmpty: function() {
		// console.log('sessionDetailsEmpty()');
		var storedSessionDetails = false;
		var id;
		if (this.hasStorage && localStorage.getItem(this.name) !== null) {
			// storedSessionDetails = localStorage.getItem(this.name);
			storedSessionDetails = this.utils.secureLS.get(this.name);
			if (storedSessionDetails && storedSessionDetails !== "undefined") {
				storedSessionDetails = JSON.parse(storedSessionDetails);
				for (let key in storedSessionDetails) {
			     	id = '#'+key;
			     	if ($(id).length) {		     		
			     		if(typeof storedSessionDetails[key].hidden !== "undefined") {
			     			this.utils.hideAndUnrequireInput(id);
			     		}
			     		$(id).val('');
			     		$(id+'-form-group').removeClass('has-success').removeClass('has-error');
			     	}
				}	
			}	
		}	
		// localStorage.removeItem(this.name);	
		this.utils.secureLS.remove(this.name);	
		console.clear();
	},	
	showMsg: function() {
		// Use arrow functions as a reference to the arguments of the enclosing scope
		var empty = () => this.sessionDetailsEmpty();
        var msg = '<p>Some details have been autofilled. <br>Click <b>Undo</b> button to clear.</p>';
        // + '<input type="checkbox" id="clear-session-data" name="clear-session-data"><label for="clear-session-data">Delete my autofill data</label><br>';
        var button = '<a href="#" id="" class="button small js-form-builder-popup-message-close">Undo</a>';
        // Add the html elements and add an event handler to the button
        $('#form-builder-popup-message .inner').append(msg).append(button).click(function(e) {
        	e.preventDefault();
        	$('.form-builder-popup-message .inner').removeClass('pop');
        	empty();
        });	
        // Timers for pop-up
 		setTimeout(function() {
 			$('.form-builder-popup-message .inner').addClass('pop');
 		}, this.options.popup.in)		     		
 		var hideMsg = setTimeout(function(out) {
 			$('.form-builder-popup-message .inner').removeClass('pop');
 		}, this.options.popup.out);	
 		// hideMsg(this.options.popup.out);	
 		$('#form-builder-popup-message').hover(function() {
 			clearTimeout(hideMsg);
 		}, function() {
 			// hideMsg(1000);
 			// $('.form-builder-popup-message .inner').removeClass('pop');
 			setTimeout(function(out) {
 			$('.form-builder-popup-message .inner').removeClass('pop');
 		}, 2000);
 		});
	}	
}
export { session }