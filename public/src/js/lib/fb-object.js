import { utils } from './fb-utilities';
// Form Input Object
var FormBuilderInput = function FormBuilderInput(input) {
    this.name = input.name;
    this.value = input.value;
    var id =  input.id;
    if(typeof id !== "undefined") {
        this.id = '#'+id;
        this.formGroup = this.id+'-form-group';
    }
    else {
        var name = (this.name.replace(/[\[\]']+/g,''));// Remove square brackets
        this.id = '#'+name;
        this.formGroup = this.id+'-form-group';
    }
    this.report = this.id+'-report';
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
    var count = parseInt($(this.id).data('count')); 
    if (!isNaN(count)) {
        this.count = count;
    }    
    var validation = $(this.id).data('validation');
    if(typeof validation !== "undefined") {
      this.validation = validation;
    } 
    var siblings = $(this.id).data('siblings');
    if(typeof siblings !== "undefined") {
      this.siblings = 'input.'+siblings;
    } 
    // var report = 
    // In case a name has been set as a data attribute (Used by checkboxes)
    var name = $(this.id).data('name');
    if(typeof name !== "undefined") {
      this.formGroup = '#'+name+'-form-group';
      this.report =  '#'+name+'-report';
    }       
};

// Instance methods
FormBuilderInput.prototype = {
    utils: utils,
    errorCount: 0,
    feedbackMessage: '', 
    isValid: function isValid() {
        var re;
        if(this.required && this.value==='') {
            return false;
        }
        if(!this.required && this.value==='') {
            return true;
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
                // var fakeEmailDomains = ['@mailinator.net'];
                // for (var i = 0; i < fakeEmailDomains.length; i++) {                           
                //     if ( this.value.includes(fakeEmailDomains[i])) {
                //         //@todo show custom error messages
                //         this.help = this.value + ' looks fake or invalid, please enter a real email address.';
                //         return false;
                //     }
                // }                    
                re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
                return re.test(this.value); 
            case 'select':  
                return this.value.toLowerCase().substring(0, 6) !== 'select';       
            case 'date':
                return this.isValidDate(this.value);        
            case 'checkbox':
                var count = 0;
                if ( this.required ) {//&& !$(this.id).prop('checked')
                    $(this.siblings).each(function() {
                        if(this.checked) count++; 
                    });
                    return count;
                }
                return true;
        }
        return true;
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
        if ( FormBuilderAjax.datePicker.format === 'dd/mm/yyyy' ) {
            // Rest of wordld
            var day = parseInt(parts[0], 10);
            var month = parseInt(parts[1], 10);         
        }
        else if ( FormBuilderAjax.datePicker.format === 'mm/dd/yyyy' ) {
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
    } 
};

export { FormBuilderInput }