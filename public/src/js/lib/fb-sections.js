export default function(FormBuilderInput, utils, session) {
    var $sectionGuideLink = $('a.js-form-section-guide-link');
    var $section;
    var $prev = $('a.js-form-builder-show-prev');
    var prev;   
    var $next = $('a.js-form-builder-show-next');
    var next;
    var current;
    var id;

    $sectionGuideLink.click(function(e) {
        e.preventDefault();
        if ($(this).hasClass("complete")) {
            $sectionGuideLink.removeClass('active');
            $(this).addClass('active');
            id = $(this).data("id");
            $('div.form-section.show-hide-section').removeClass('active-section').addClass('hidden-section'); 
            $('#form-section-' + id ).removeClass('hidden-section').addClass('active-section');
            $('#form-section-' + id + ' select.js-select2-multiple').select2("destroy").select2(utils.select2Options);
            $('div.section-head').removeClass('active');
            $('#form-section-head-'+id).addClass('active');
        }
        else {
            alert("Please complete the current section before you continue.");
        }

        $('body').on('click', '#js-edit-form', function(e) {    
            e.preventDefault();
            editForm();
        });   
        
        $('body').on('click', '#js-hide-form', function(e) {    
            e.preventDefault();
            hideForm();
        }); 

        if( jQuery().fdatepicker ) {
            $('select.js-select2-multiple').select2(utils.select2Options);
        }        
    });

    $next.click(function(e){
        e.preventDefault();
        var input;
        var errorsInForm = utils.resetErrorsInForm();
        current = $(this).data("current");
        next = $(this).data("next");
        $section = $('#form-section-'+current);
        
        $('#form-section-' + current + ' .js-form-builder-control').each(function () {
            input = new FormBuilderInput(this);
            errorsInForm = utils.addClassAfterBlur(input, input.isValid(), errorsInForm);
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
            $('#form-section-' + next + ' select.js-select2-multiple').select2("destroy").select2(utils.select2Options);

            $([document.documentElement, document.body]).animate({
                scrollTop: $('#form-section-' + next).offset().top - 100
            }, 800);
        }
        else {
            utils.showModalWithErrors( utils.wrapErrorMessageSection(errorsInForm) );
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
}