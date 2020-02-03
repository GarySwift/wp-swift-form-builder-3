import { utils } from './fb-utilities';
import { FormBuilderInput } from './fb-object';
var formBuilderDates = {
    getDate: function(years) {
        var nowTemp = new Date();
        return new Date(nowTemp.getFullYear()-years, nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
    },   
    dateInPast: function(years) {
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
        return  dateInPast; 
    },
    datepickerListener: function (utils, startDate, endDate, dateRangeStart, dateRangeEnd) {
        var $dateRangeStart = $('#' + dateRangeStart);
        var $dateRangeEnd = $('#' + dateRangeEnd);

        var checkin = $dateRangeStart.fdatepicker({
            format: FormBuilderAjax.datePicker.format,
            endDate: endDate,
            onRender: function (date) {
                return date.valueOf() < startDate.valueOf() ? 'disabled' : '';
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
            utils.addClassAfterBlur(input, input.isValid(), 0);
        }).data('datepicker');

        var checkout = $dateRangeEnd.fdatepicker({
            format: FormBuilderAjax.datePicker.format,
            endDate: endDate,
            onRender: function (date) {
                return date.valueOf() <= checkin.date.valueOf() ? 'disabled' : '';
            }
        }).on('changeDate', function (ev) {
            checkout.hide();
        }).on('hide', function (ev) {
            var input = new FormBuilderInput( document.getElementById( dateRangeEnd ) );
            utils.addClassAfterBlur(input, input.isValid(), 0);               
        }).data('datepicker'); 

    },    
    run: function() {
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
            today = dd+'/'+mm+'/'+yyyy;
            // We must set today dependent on the format set on server
            if ( FormBuilderAjax.datePicker.format === 'mm/dd/yyyy' ) {
                // United States
                today = mm+'/'+dd+'/'+yyyy;       
            }

            $('.js-date-picker.past input').fdatepicker({
                // initialDate: today,
                format: FormBuilderAjax.datePicker.format,
                endDate: today,
                disableDblClickSelection: true,
                leftArrow:'<<',
                rightArrow:'>>',
                closeIcon:'X',
                closeButton: true
            }).on('hide', function (ev) {
                var input = new FormBuilderInput(this);
                utils.addClassAfterBlur(input, input.isValid(), 0);
            });


            $('.js-date-picker.future input').fdatepicker({
                format: FormBuilderAjax.datePicker.format,
                startDate: today,
                disableDblClickSelection: true,
                leftArrow:'<<',
                rightArrow:'>>',
                closeIcon:'X',
                closeButton: true
            }).on('hide', function (ev) {
                var input = new FormBuilderInput(this);
                utils.addClassAfterBlur(input, input.isValid(), 0);
            });     

            $('.js-date-picker.all input').fdatepicker({
                format: FormBuilderAjax.datePicker.format,
                disableDblClickSelection: true,
                leftArrow:'<<',
                rightArrow:'>>',
                closeIcon:'X',
                closeButton: true
            }).on('hide', function (ev) {
                var input = new FormBuilderInput(this);
                utils.addClassAfterBlur(input, input.isValid(), 0);
            }); 

            // Range
            // Get the first input and use this to get the second input
            var $datePickerInput = $('input.js-date-picker-range.start');
            if ($datePickerInput.length) {
                $datePickerInput.each(function() {
                    var dateRangeStart = this.id;
                    var dateRangeEnd = dateRangeStart.substring(0, dateRangeStart.length - 6)+'-end';
                    var startDate;
                    var endDate;
                    if ($(this).hasClass('past')) {
                        startDate = formBuilderDates.getDate(100);
                        endDate = formBuilderDates.getDate(0);
                    }
                    else if ($(this).hasClass('future')) {
                        startDate = formBuilderDates.getDate(0);
                    }
                    else {
                        startDate = formBuilderDates.getDate(100);
                    }     
                    formBuilderDates.datepickerListener( utils, startDate, endDate, dateRangeStart, dateRangeEnd );
                });
            }       
        } 
    }      	
}
export { formBuilderDates as dateUtils }