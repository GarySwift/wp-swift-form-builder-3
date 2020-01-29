export default function(FormBuilderInput, events, utils, dateUtils, session, sections, submit, signUp, repeater, fbMarketingAjax) {
    if (typeof FormBuilderAjax !== "undefined") {
        if(FormBuilderAjax.debug) 
            console.log('DEBUG FormBuilderAjax:', FormBuilderAjax);
        jQuery(document).ready(function($) {
            utils.setModal();
            utils.setSecureLS(FormBuilderAjax.encryptionSecret);            
            session.sessionDetailsFill(utils);
            dateUtils.run();
            sections(FormBuilderInput, utils, session);
            events(FormBuilderInput, utils, session, submit);
            signUp(session, utils); 
            repeater();
            fbMarketingAjax(session);
        });
    }//@endif FormBuilderAjax
}