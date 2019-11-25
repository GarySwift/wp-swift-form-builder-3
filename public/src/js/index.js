/**
 * All of the code for your public-facing JavaScript source should 
 * reside in this file.
 * In general everything should be in a component as much as possible.
 */
// import component from './lib/component';
import { FormBuilderInput } from './lib/form-builder-object';
import { utils } from './lib/form-builder-utilities';
import { session } from './lib/formbuilder-session';
import formbuilder from './lib/formbuilder';
// import formbuilderCustomScript from './lib/formbuilder-custom-script';
import formbuilderSignUp from './lib/formbuilder-sign-up-form';
// (function( $ ) {
    'use strict';
    // component($);
	formbuilder(FormBuilderInput, utils, session);
    // formbuilderCustomScript(utils);
    formbuilderSignUp(session);    
// })( jQuery );
