export default function(FormBuilderInput, events, utils, dateUtils, session, sections, submit, signUp, repeater) {
    if (typeof FormBuilderAjax !== "undefined") {
        if(FormBuilderAjax.debug) 
            console.log('DEBUG FormBuilderAjax:', FormBuilderAjax);
        jQuery(document).ready(function($) {
            utils.setModal();
            session.sessionDetailsFill();
            dateUtils.run();
            sections(FormBuilderInput, utils, session);
            events(FormBuilderInput, utils, session, submit);
            signUp(session); 
            repeater();
        });
    }//@endif FormBuilderAjax
}