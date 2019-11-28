/**
 * All of the code for your public-facing JavaScript source should 
 * reside in this file.
 * In general everything should be in a component as much as possible.
 */
'use strict';
import '../../../node_modules/foundation-datepicker/js/foundation-datepicker.min';
import { FormBuilderInput } from './lib/form-builder-object';
import { utils } from './lib/form-builder-utilities';
import { dateUtils } from './lib/formbuilder-dates';
import { session } from './lib/formbuilder-session';
import { submit } from './lib/plugins/form-builder/formbuilder-submit';
import formbuilder from './lib/formbuilder';
import formbuilderSignUp from './lib/formbuilder-sign-up-form';
formbuilder(FormBuilderInput, utils, dateUtils, session, submit);
// formbuilder(FormBuilderInput, utils, session, submit);
formbuilderSignUp(session);    
