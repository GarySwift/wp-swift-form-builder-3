export default function() {

    /*
     * Event listeners
     */
    $('a.js-add-row').click(function(e) {
        e.preventDefault();
        addRow($(this));
    });

    $('a.js-remove-row').click(function(e) {
        e.preventDefault();
        removeRow($(this));
    }); 

    /*
     * Functions
     */    
    var disableAddRemoveButtons = function($addButton, $removeButton) {
        $addButton.attr('disabled', true);
        $removeButton.attr('disabled', true);   
    };

    var checkAddRemoveButtons = function($addButton, $removeButton, count, max, min) {
        if (count < max ) {
            $addButton.attr('disabled', false);
        }
        else if (count === max) {
            $addButton.attr('disabled', true);
        }

        if (count > min) {
            $removeButton.attr('disabled', false);
        }
        else if (count === min) {
            $removeButton.attr('disabled', true);
        }
    };

    var addRowAjax = function($addButton, $removeButton, count, max, min, $countInput) {
        FormBuilderAjax.count = count;
        FormBuilderAjax.tabindex = parseInt( $addButton.attr('tabindex') );
        FormBuilderAjax.formId = parseInt( $addButton.data('form-id') );
        FormBuilderAjax.action = "wp_swift_" + $addButton.data('action');   
        $.post(FormBuilderAjax.ajaxurl, FormBuilderAjax, function(response) {
            var serverResponse = JSON.parse(response);
            count = serverResponse.count;
            $( serverResponse.html ).insertBefore( "#" + $addButton.data('group') + "-add-remove-group" );
            $addButton.attr('tabindex', serverResponse.tabindex);
            $addButton.data('count', count);
            $countInput.val(count);
            checkAddRemoveButtons($addButton, $removeButton, count, max, min);   
        });
    };

    var addRow = function($addButton) {
        var $removeButton = $( $addButton.data('remove-button') );
        disableAddRemoveButtons($addButton, $removeButton);
        var $countInput = $( $addButton.data('count-input-id') );
        var count = parseInt($countInput.val());
        var min = parseInt($countInput.attr('min'));
        var max = parseInt($countInput.attr('max'));
        var showAlert = $addButton.data('alert');
        if (count < max) {
            addRowAjax($addButton, $removeButton, count, max, min, $countInput);
        }
        else {
            if (showAlert) alert("Maximum Reached!\nSorry, you cannot add anymore rows.");
            checkAddRemoveButtons($addButton, $removeButton, count, max, min);
        }
    };

    var removeRowAction = function($addButton, $removeButton, count, max, min, $countInput) {        
        var keys = $addButton.data('keys');
        var input;
        for (var i = 0; i < keys.length; i++) {
            input = '#'+keys[i] + '-' + count;
            var $inputGroup = $(input + '-form-group');
            $inputGroup.remove();
        }
        count--;
        $countInput.val(count);   
        checkAddRemoveButtons($addButton, $removeButton, count, max, min);        
    };
    
    var removeRow = function($removeButton) {
        var $addButton = $($removeButton.data('add-button'));
        disableAddRemoveButtons($addButton, $removeButton);
        var $countInput = $( $addButton.data('count-input-id') );
        var count = parseInt($countInput.val());
        var min = parseInt($countInput.attr('min'));
        var max = parseInt($countInput.attr('max'));
        var showAlert = $addButton.data('alert'); 
        if ( count > min ) {
            removeRowAction($addButton, $removeButton, count, max, min, $countInput);
        }
        else {
            if (showAlert) alert("Minimum Reached!\nSorry, you cannot remmove anymore rows.");
            checkAddRemoveButtons($addButton, $removeButton, count, max, min);
        }
    };
}