export default function() {
    console.log('go');
    var disableAddRemoveButtons = function($addButton, $removeButton) {
        console.log('disableAddRemoveButtons()');
        $addButton.attr('disabled', true);
        $removeButton.attr('disabled', true);   
    }

    var checkAddRemoveButtons = function($addButton, $removeButton, count, max, min) {
        console.log('checkAddRemoveButtons() count', count, 'max', max, 'min', min);
        if (count === 0) {
            $removeButton.attr('disabled', true);
            $addButton.attr('disabled', false);
        } 
        // else if (count === 1) {
        //  $removeButton.attr('disabled', true);
        //  $addButton.attr('disabled', false);
        // }
        else if (count > 0 && count < max){
            $addButton.attr('disabled', false);
            $removeButton.attr('disabled', false);
        }
        else if (count > 0 && count === max){
            $addButton.attr('disabled', true);
            $removeButton.attr('disabled', false);
        }
    }    
        $('a.js-add-row').click(function(e) {

            e.preventDefault();
            // var max = 3;
            var $addButton = $(this);
            // var count = $addButton.data('count');
            // count++;$addButton.text(count);$addButton.data('count', count);
            // var max = $addButton.data('max');
            var $removeButton = $( $addButton.data('remove-button') );
            var $countInput = $( $addButton.data('count-input-id') );
            // $countInput.val(10);
            var count = $countInput.val();
            var min = parseInt($countInput.attr('min'));
            var max = parseInt($countInput.attr('max'));
            // return false;
            disableAddRemoveButtons($addButton, $removeButton); 

            if (count < max) {
                FormBuilderAjax.count = count;
                FormBuilderAjax.tabindex = parseInt( $addButton.attr('tabindex') );
                FormBuilderAjax.formId = parseInt( $addButton.data('form-id') );
                FormBuilderAjax.action = "wp_swift_" + $addButton.data('action');   
                // console.log('ForFmBuilderAjax', FormBuilderAjax);
                // $(this).attr('tabindex', 201);
                $.post(FormBuilderAjax.ajaxurl, FormBuilderAjax, function(response) {
                    // console.log('response', response);
                    var serverResponse = JSON.parse(response);
                    count = serverResponse.count;
                    // console.log('count', serverResponse.count);
                    $( serverResponse.html ).insertBefore( "#" + $addButton.data('group') + "-add-remove-group" );
                    $addButton.attr('tabindex', serverResponse.tabindex);
                    $addButton.data('count', count);
                    $countInput.val(count);
                    // $addButton.text(serverResponse.count);
                    // $removeButton.data('count', serverResponse.count);
                    // $removeButton.text('Remove ' + serverResponse.count);
                    // $removeButton.attr('disabled', false);
                    // if (serverResponse.count === max) {
                    //  $addButton.attr('disabled', true);
                    // }
                    checkAddRemoveButtons($addButton, $removeButton, count, max, min);   
                });
            }
            else {
                console.log('a.js-add-row.click() count', count, 'max', max, 'min', min);
                alert("Maximum Reached!\nSorry, you cannot add anymore rows.");
                if (count > min) {
                    $removeButton.attr('disabled', false);
                }                
            }
        }); 

        $('a.js-remove-row').click(function(e) {

            e.preventDefault();
            var $removeButton = $(this);
            var $addButton = $($removeButton.data('add-button'));
            disableAddRemoveButtons($addButton, $removeButton);
            // var $countInput = 
            // $addButton.attr('disabled', true);
            // $removeButton.attr('disabled', true);
            // var count = $removeButton.data('count');
            // var count = $addButton.data('count');
            // console.log('count', count);
            var $countInput = $( $addButton.data('count-input-id') );
            // console.log('$countInput', $countInput);
            // $countInput.val(10);
            var count = $countInput.val();
            var min = parseInt($countInput.attr('min'));
            var max = parseInt($countInput.attr('max'));    
            // return false; 
            
            if ( count > min ) {
                // var max = $addButton.data('max');
        
                var keys = $addButton.data('keys');
                console.log('keys', keys);
                var input;
                console.log('keys.length', keys.length);
                for (var i = 0; i < keys.length; i++) {
                    console.log('i', i);
                    input = '#'+keys[i] + '-' + count;
                    var $inputGroup = $(input + '-form-group');
                    console.log(input + '-form-group');
                    // var $input = $(input);
                    // $input.attr('disabled', true);
                    $inputGroup.remove();
                    // console.log(input + '-form-group');
                }
                count--;
                // $addButton.data('count', count);
                // console.log('count', count);
                $countInput.val(count);


                // $removeButton.data('count', count);

                // $('#add-row').data('count', count);
                // $('#remove-row').data('count', count);
                // if (count === 0) {
                //  $removeButton.attr('disabled', true);
                //  $addButton.attr('disabled', false);
                // } 
                // // else if (count === 1) {
                // //   $removeButton.attr('disabled', true);
                // //   $addButton.attr('disabled', false);
                // // }
                // else if (count > 0 && count < max){
                //  $addButton.attr('disabled', false);
                // }
                // else if (count > 0 && count === max){
                //  $addButton.attr('disabled', true);
                //  $removeButton.attr('disabled', false);
                // }    
                checkAddRemoveButtons($addButton, $removeButton, count, max, min);
            }
            else {
                console.log('a.js-remove-row.click() count', count, 'max', max, 'min', min);
                alert("Minimum Reached!\nSorry, you cannot remmove anymore rows.");
                $removeButton.attr('disabled', true);
                // var addButton = true;
                if (count < max) {
                    $addButton.attr('disabled', false);
                }
            }


            // var $removeRow = $('#row-'+count);
            // $removeRow.remove();

            // 
            // 
        
        }); 

        // var $addButton = $('#add-row-parts-list');   
        // var keys = $addButton.data('keys');
        // var input;
        // for (var j = 1; j <= 10; j++) {
        //  for (var i = 0; i < keys.length; i++) {
        //      input = '#'+keys[i] + '-' + j;
        //      // var $inputGroup = $(input + '-form-group');
        //      // var $input = $(input);
        //      // $input.attr('disabled', true);
        //      // $inputGroup.remove();
        //      console.log(input + '-form-group');
        //  }
        //  console.log('');
        // }
    // });
}
// Usage (app.js)
// import repeaterFunction from './lib/repeater';
// $(document).ready(function() {
//   repeaterFunction();
// });