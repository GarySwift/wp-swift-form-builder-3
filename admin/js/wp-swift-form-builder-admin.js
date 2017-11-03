jQuery(document).ready(function($){
	$('body').on('click', '#shortcode-input', function(e) {
		copy_shortcode( this );


				// var $msg = $('#shortcode-input-copy');
				// $msg.removeClass('hidden');
				// console.log('#shortcode-input-copy');
				// setTimeout(function(){$msg.addClass('hidden'); }, 2000);
				// console.log('#copied-to-clipboard');
	});
	$('body').on('click', '#shortcode-input-copy', function(e) {
		e.preventDefault();
		copy_shortcode( $('#shortcode-input') );
	});
	function copy_shortcode( $input ) {
		$input.focus();
		$input.select();
		// document.execCommand("copy");
		try {  
			// Now that we've selected the anchor text, execute the copy command  
			var successful = document.execCommand('copy');  
			if(successful) {
				var msg = $('#copied-to-clipboard');
				// msg.fadeIn();
				var $msg = $('#shortcode-input-copy');
				$msg.removeClass('hidden');
				setTimeout(function(){$msg.addClass('hidden'); }, 2000);
				console.log('#copied-to-clipboard');
					

			}
		} catch(err) {  
			console.log('Unable to copy'); 
		}		
	}	
});

// [form id="322"]
// jQuery(document).ready(function($){
// 	$('body').on('click', 'a.copy', function(e) {
// 		e.preventDefault();
// 		console.log(this);
// 		copyToClipboard('#p2');
// 	}); 
// 	function copyToClipboard(element) {
// 		var $temp = $("<input>");
// 		$("body").append($temp);
// 		$temp.val($(element).text()).select();
// 		document.execCommand("copy");
// 		$temp.remove();
// 	}
// });
// <center>
// <p id="p1">Hello, I'm TEXT 1</p>
// <p id="p2">Hi, I'm the 2nd TEXT</p><br/>

// <a href="#" class="copy">Copy</a>
  
// </center>

/*
[form id="322"]
[form id="154"]

[form id="322"]
*/	