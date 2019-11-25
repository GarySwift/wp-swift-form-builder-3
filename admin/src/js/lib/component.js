export default function($) {
	jQuery(document).ready(function($){
		// If a copy clickable input is clicked
		$('body').on('click', 'input.js-click-to-copy-input', function(e) {
			copy_input( this );
		});
		// If a button to copy an input is clicked
		$('body').on('click', 'a.js-click-to-copy-link', function(e) {
			e.preventDefault();
			var copy_id = $(this).data('copy-id');
			copy_input( $('#'+copy_id) );

		});
		// If copy clickable text is clicked
		$('body').on('click', '.js-click-to-copy-text', function(e) {
			var range = document.createRange();
			var sel = window.getSelection();
			range.setStartBefore(this.firstChild);
			range.setEndAfter(this.lastChild);
			sel.removeAllRanges();
			sel.addRange(range);
			try {  
				var successful = document.execCommand('copy');  
			} catch(err) {  
				console.error('Unable to copy'); 
			} 		
		});	
	});
	function copy_input( $input ) {
		$input.focus();
		$input.select();
		try {  
			var successful = document.execCommand('copy');  
		} catch(err) {  
			console.error('Unable to copy'); 
		}		
	}	
}
