/**
 * All of the code for your public-facing JavaScript source should 
 * reside in this file.
 * In general everything should be in a component as much as possible.
 */
'use strict';

import '../../../node_modules/foundation-datepicker/js/foundation-datepicker.min';
import SlimSelect from 'slim-select';// https://slimselectjs.com/install
// SecureLS is imported from 'secure-ls' and used in './lib/fb-utilities' - https://github.com/softvar/secure-ls

// const secureLS = new SecureLS({
//     encodingType: 'rabbit', 
//     isCompression: false, 
//     encryptionSecret: FormBuilderAjax.encryptionSecret
// });

import { FormBuilderInput } from './lib/fb-object';
import { utils } 			from './lib/fb-utilities';
import { dateUtils } 		from './lib/fb-dates';
import { session } 			from './lib/fb-session';
import { submit } 			from './lib/fb-submit';
import repeater 			from './lib/fb-add-rows';
import events 				from './lib/fb-events';
import sections 			from './lib/fb-sections';
import formBuilder 			from './lib/fb-main';
import signUp 				from './lib/fb-sign-up-form';
import fbMarketingAjax      from './lib/fb-marketing';

formBuilder(FormBuilderInput, events, utils, dateUtils, session, sections, submit, signUp, repeater, fbMarketingAjax);