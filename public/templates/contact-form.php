	
	<!-- @start .contact-form-container -->
	<div class="contact-form-container">
    <?php 
    	if (isset($content)) {
    		echo $content;
    	}
		if ($form_builder != null ) {
            if(isset($_POST[ $form_builder->get_submit_button_name() ])){ //check if form was submitted
                $form_builder->process_form(); 
            }
	        $form_builder->acf_build_form();
	    } 
	?>

	</div><!-- @end .contact-form-container -->
