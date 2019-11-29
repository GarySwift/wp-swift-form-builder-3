import { utils } from './fb-utilities';
var session = {
	// storedSessionDetails: false,
	name: "form-session-details",
	utils: utils,
	hasStorage: typeof(Storage) !== "undefined",
	options: {
		popup: {
			in: 1000,
			out: 11000
		}
	},
	// saveSessionDetails
    save: function(name, value) {
        
        if (this.hasStorage) {
            // Check if it already exists
            var storedSessionDetails = JSON.parse(localStorage.getItem(name));

            if (storedSessionDetails && storedSessionDetails !== "undefined") {
                Object.assign(storedSessionDetails, value);
                console.log('MERGED storedSessionDetails', storedSessionDetails);
                value = JSON.stringify(storedSessionDetails);
            }
            else {
                value = JSON.stringify(value);
            }
            // return storedSessionDetails;
            
        // }  ///
        // window.sessionStorage
        
        // console.log('Saving: ', value);
        // if (this.hasStorage) {
            localStorage.setItem(name, value);
        }   
    },
    // getSessionDetails
    get: function(name) {
        // window.sessionStorage
        if (this.hasStorage) {
        	// console.log('this.hasStorage', this.hasStorage);
        	// console.log('name', name);
            var storedSessionDetails = JSON.parse(localStorage.getItem(name));
            // console.log('1 storedSessionDetails', storedSessionDetails);
            return storedSessionDetails;
        }
        return false;   
    },
    // resetSessionDetails
    reset: function(name) {
        if (this.hasStorage) {
            localStorage.removeItem(name);
            console.log('Session date cleared');
        }   
    }, 
    subscribed: function() {//storedSessionDetails
    	// console.log('subscribed() -> storedSessionDetails', storedSessionDetails);
    	var storedSessionDetails = this.get(this.name);
    	// console.log('2 storedSessionDetails', storedSessionDetails);
    	//session.hasStorage && 
    	if (storedSessionDetails && typeof storedSessionDetails.subscribed !== "undefined" && storedSessionDetails.subscribed === true) {
    		return true;
    	}
    	return false;
    },      	
	sessionDetailsFill: function() {
		var storedSessionDetails = false;
		var id;
		if (this.hasStorage && localStorage.getItem(this.name) !== null) {
			storedSessionDetails = localStorage.getItem(this.name);
			if (storedSessionDetails && storedSessionDetails !== "undefined") {
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
				}
				if (countInputAutoFills) {
					this.showMsg();
				}					
			}	
		}	
	},
	sessionDetailsEmpty: function() {
		var storedSessionDetails = false;
		var id;
		if (this.hasStorage && localStorage.getItem(this.name) !== null) {
			storedSessionDetails = localStorage.getItem(this.name);
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
		localStorage.removeItem(this.name);		
		console.clear();
	},	
	showMsg: function() {
		// Use arrow functions as a reference to the arguments of the enclosing scope
		var empty = () => this.sessionDetailsEmpty();
        var msg = '<p>Some details have been autofilled. <br>Click <b>Undo</b> button to clear.</p>';
        // + '<input type="checkbox" id="clear-session-data" name="clear-session-data"><label for="clear-session-data">Delete my autofill data</label><br>';
        var button = '<a href="#" id="" class="button tiny js-form-builder-popup-message-close">Undo</a>';
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