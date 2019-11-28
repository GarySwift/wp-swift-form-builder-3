import { utils } from './form-builder-utilities';
import { FormBuilderInput } from './form-builder-object';
var formBuilderDates = {
    utils: utils,
    getNow: function() {
        var nowTemp = new Date();
        return new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
    },
    // nowTemp: new Date(),
    // now: new Date(this.nowTemp.getFullYear(), this.nowTemp.getMonth(), this.nowTemp.getDate(), 0, 0, 0, 0),
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
    datepickerListener: function (utils, now, dateRangeStart, dateRangeEnd) {
        // console.log('test', test);
        // console.log('2 now, dateRangeStart, dateRangeEnd -- ', now, dateRangeStart, dateRangeEnd);
        var $dateRangeStart = $('#' + dateRangeStart);
        var $dateRangeEnd = $('#' + dateRangeEnd);
        

        var checkin = $dateRangeStart.fdatepicker({
            format: FormBuilderAjax.datePicker.format,
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
            utils.addClassAfterBlur(input, input.isValid(), 0);
        }).data('datepicker');

        var checkout = $dateRangeEnd.fdatepicker({
            format: FormBuilderAjax.datePicker.format,
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
        var utils = this.utils;

if(jQuery().fdatepicker) {
    // console.log('[jQuery().fdatepicker ->-> jQuery().fdatepicker]');
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
    if ( FormBuilderAjax.datePicker.format === 'mm/dd/yyyy' ) {
        // United States
        today = mm+'/'+dd+'/'+yyyy;       
    }
    // console.log('FormBuilderAjax.datePicker.format', FormBuilderAjax.datePicker.format);

    $('.js-date-picker.past input').fdatepicker({
        // initialDate: today,
        format: FormBuilderAjax.datePicker.format,
        endDate: today,//this.dateInPast(13),
        // startDate: this.dateInPast(13),
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
        // format: 'mm-dd-yyyy hh:ii',
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

    // var nowTemp = new Date();
    // var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);



    var $datePickerInput = $('input.js-date-picker-range');
    // console.log('$datePickerInput.length', $datePickerInput.length);
    if ($datePickerInput.length) {
        // Use arrow functions as a reference to the arguments of the enclosing scope
        // var listener = () => this.datepickerListener();

        var listener = this.datepickerListener;
        var now = this.getNow();
        // console.log('now', now);       
        $datePickerInput.each(function() {
            // console.log('this.id', this.id);
            var dateRangeStart = this.id;//'#' + 
            var dateRangeEnd = dateRangeStart.substring(0, dateRangeStart.length - 6)+'-end';
            // console.log('1 now, dateRangeStart, dateRangeEnd -- ', now, dateRangeStart, dateRangeEnd);
            // console.log('utils, now, dateRangeStart, dateRangeEnd', utils, now, dateRangeStart, dateRangeEnd);
            listener( utils, now, dateRangeStart, dateRangeEnd );
        });
    }       
} 

    }      	
}
export { formBuilderDates as dateUtils }