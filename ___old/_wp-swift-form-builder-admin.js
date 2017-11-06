console.log('3 test');
(function( $ ) {
	'use strict';

	$('a.copy-shortcode').click(function(e) {
		e.preventDefault();
		console.log('test');
		console.log(this.data('shortcode'));
		return false;
	});
	// $('#form_shortcode_test').focus()
	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

})( jQuery );

// document.getElementById("form-builder-shortcode-text").onclick = function() {
//     this.select();
//     document.execCommand('copy');
//     // alert('This is a test...');
//     console.log('This is a test...');
// }

(function($) {
	// $('#copied-to-clipboard').hide();

}) (jQuery);



console.info('wp-swift-form-builder-public.js');
jQuery(document).ready(function($){
    // When a user enters a form input
	$('body').on('focus', '#form_shortcode_test', function(e) {	
		// $('#'+this.id+'-form-group').removeClass('has-error').removeClass('has-success');
		console.log('#form_shortcode_test');
		console.log( $(this).val() );
		// document.execCommand("copy");
	});

	$('a.copy-shortcode').click(function(e) {
		e.preventDefault();
		console.log('test');
		console.log(this);
		var id = $(this).data('id');
		console.log('[form id="'+id+'"]');
		return false;
	});

	document.getElementById("copyButton").addEventListener("click", function() {
	    copyToClipboard(document.getElementById("copyTarget"));
	});	

	$('body').on('click', 'pre.copy', function(event) {
		var range = document.createRange();
		var sel = window.getSelection();
		range.setStartBefore(this.firstChild);
		range.setEndAfter(this.lastChild);
		sel.removeAllRanges();
		sel.addRange(range);
		try {  
			// Now that we've selected the anchor text, execute the copy command  
			var successful = document.execCommand('copy');  
			if(successful) {
				var msg = $('#copied-to-clipboard');
				// msg.fadeIn();
				// setTimeout(function(){ msg.fadeOut(); }, 3000);
				console.log(msg);			
			}
		} catch(err) {  
			console.log('Unable to copy'); 
		} 
	}); 	
});




function copyToClipboard(elem) {
	  // create hidden text element, if it doesn't already exist
    var targetId = "_hiddenCopyText_";
    var isInput = elem.tagName === "INPUT" || elem.tagName === "TEXTAREA";
    var origSelectionStart, origSelectionEnd;
    if (isInput) {
        // can just use the original source element for the selection and copy
        target = elem;
        origSelectionStart = elem.selectionStart;
        origSelectionEnd = elem.selectionEnd;
    } else {
        // must use a temporary form element for the selection and copy
        target = document.getElementById(targetId);
        if (!target) {
            var target = document.createElement("textarea");
            target.style.position = "absolute";
            target.style.left = "-9999px";
            target.style.top = "0";
            target.id = targetId;
            document.body.appendChild(target);
        }
        target.textContent = elem.textContent;
    }
    // select the content
    var currentFocus = document.activeElement;
    target.focus();
    target.setSelectionRange(0, target.value.length);
    
    // copy the selection
    var succeed;
    try {
    	  succeed = document.execCommand("copy");
    } catch(e) {
        succeed = false;
    }
    // restore original focus
    if (currentFocus && typeof currentFocus.focus === "function") {
        currentFocus.focus();
    }
    
    if (isInput) {
        // restore prior selection
        elem.setSelectionRange(origSelectionStart, origSelectionEnd);
    } else {
        // clear temporary content
        target.textContent = "";
    }
    return succeed;
}



function GetValue()
{
    var myarray= new Array("item1","item2","item3");
    var random = myarray[Math.floor(Math.random() * myarray.length)];
    //alert(random);
    document.getElementById("message").innerHTML=random;
}

function copyToClipboard(elementId) {


  var aux = document.createElement("input");
  aux.setAttribute("value", document.getElementById(elementId).innerHTML);
  document.body.appendChild(aux);
  aux.select();
  document.execCommand("copy");

  document.body.removeChild(aux);

}