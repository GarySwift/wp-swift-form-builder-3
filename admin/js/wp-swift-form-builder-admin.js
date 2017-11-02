console.log('1 test');
(function( $ ) {
	'use strict';

	$('a.copy-shortcode').click(function(e) {
		e.preventDefault();
		console.log('test');
		console.log(this.data('shortcode'));
		return false;
	});
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

// (function($) {
// 	// $('#copied-to-clipboard').hide();
// 	$('body').on('click', 'pre.copy', function(event) {
// 		var range = document.createRange();
// 		var sel = window.getSelection();
// 		range.setStartBefore(this.firstChild);
// 		range.setEndAfter(this.lastChild);
// 		sel.removeAllRanges();
// 		sel.addRange(range);
// 		try {  
// 			// Now that we've selected the anchor text, execute the copy command  
// 			var successful = document.execCommand('copy');  
// 			if(successful) {
// 				var msg = $('#copied-to-clipboard');
// 				msg.fadeIn();
// 				setTimeout(function(){ msg.fadeOut(); }, 3000);			
// 			}
// 		} catch(err) {  
// 			console.log('Unable to copy'); 
// 		} 
// 	}); 
// }) (jQuery);