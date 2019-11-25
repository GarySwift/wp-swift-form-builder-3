export default function(FormBuilderInput, utils, session) {

    var $sectionGuideLink = $('a.js-form-section-guide-link');
    $sectionGuideLink.click(function(e) {
        e.preventDefault();
        if ($(this).hasClass("complete")) {
            $sectionGuideLink.removeClass('active');
            $(this).addClass('active');
            var id = $(this).data("id");
            console.log('#form-section-' + $(this).data("id"));
            $('div.form-section.show-hide-section').removeClass('active-section').addClass('hidden-section'); 
            $('#form-section-' + id ).removeClass('hidden-section').addClass('active-section');
            $('#form-section-' + id + ' select.js-select2-multiple').select2("destroy").select2(select2Options);

            $('div.section-head').removeClass('active');
            $('#form-section-head-'+id).addClass('active');

        }
        else {
            alert("Please complete the current section before you continue.");
        }

        // $('div.form-builder.wrapper.hide').removeClass('hide').slideDown();
        $('body').on('click', '#js-edit-form', function(e) {    
            e.preventDefault();
            editForm();
        });   
        $('body').on('click', '#js-hide-form', function(e) {    
            e.preventDefault();
            hideForm();
        }); 

        // var details = getSessionDetails(session.name);
        // // console.log('details', details);

        // if (details) {
            
        //     if(typeof details.email !== "undefined" ) {
        //         // document.getElementById( "form-email" ).value = details.email;
        //         $('#form-email').val(details.email);
        //     }
        //     if(typeof details.email !== "undefined") {
        //         // document.getElementById( "form-first-name" ).value = details.first_name;
        //         $('#form-first-name').val(details.first_name);
        //     }
        //     if(typeof details.email !== "undefined") {
        //         // document.getElementById( "form-last-name" ).value = details.last_name;
        //         $('#form-last-name').val(details.last_name);
        //     }
        //     if(typeof details.phone !== "undefined") {
        //         // document.getElementById( "form-phone" ).value = details.phone;
        //         $('#form-phone').val(details.phone);
        //     }   

        //     // $('.form-builder.groupings').slideUp();
        //     // $('#download-mask').removeClass('masked');
        //     // localStorage.clear();
        //     // hideForm();
        // }



            // $('#form-taoglas-products').select2();
        if( jQuery().fdatepicker ) {
            $('select.js-select2-multiple').select2(select2Options);
        }
        
        // {
        //    theme: "classic"
        //  }
        //           
    });

    var $section;
    var $prev = $('a.js-form-builder-show-prev');
    var prev;   
    var $next = $('a.js-form-builder-show-next');
    var next;
    var current;
    $next.click(function(e){
        e.preventDefault();
        var input;
        current = $(this).data("current");
        next = $(this).data("next");
        console.log('current', current);
        console.log('next', next);

        $section = $('#form-section-'+current);
        errorsInForm = resetErrorsInForm();
        $('#form-section-' + current + ' .js-form-builder-control').each(function () {
            input = new FormBuilderInput(this);
            errorsInForm = addClassAfterBlur(input, input.isValid(), errorsInForm);
        });
        if (errorsInForm.count === 0) {
            $sectionGuideLink.removeClass('active');
            $('#form-section-guide-link-' + current ).addClass('complete');
            $('#form-section-guide-link-' + next ).addClass('active');

            $('div.section-head').removeClass('active');
            $('#form-section-head-'+next).addClass('active');

            if ($('#form-section-guide-link-' + next ).hasClass('last-section')) {
                // Auto approve the last step
                $('#form-section-guide-link-' + next ).addClass('complete');
            }
            $('#form-section-' + current ).removeClass('active-section').addClass('hidden-section');
            $('#form-section-' + next ).removeClass('hidden-section').addClass('active-section');
            $('#form-section-' + next + ' select.js-select2-multiple').select2("destroy").select2(select2Options);

            $([document.documentElement, document.body]).animate({
                scrollTop: $('#form-section-' + next).offset().top - 100
            }, 800);
        }
        else {
            showModalWithErrors( wrapErrorMessageSection(errorsInForm) );
        }
    });

    $prev.click(function(e){
        e.preventDefault();
        current = $(this).data("current");
        prev = $(this).data("prev");
        $('#form-section-'+current).removeClass('active-section').addClass('hidden-section');
        $('#form-section-'+prev).removeClass('hidden-section').addClass('active-section');
    });
    $('a.js-show-form-group-extra').click(function(e){
        e.preventDefault();
        $('div.form-group-extra').css("display","block");
    });
    $('#form-builder-show-all-sections').click(function(e){
        $sectionGuideLink.removeClass('active');
        $("div.form-section-buttons").hide();
        $('div.form-section.show-hide-section').removeClass('hidden-section').addClass('active-section');  
        $('div.section-head').removeClass('active');
    });
