/**
 * All of the code for your public-facing JavaScript source should 
 * reside in this file.
 * In general everything should be in a component as much as possible.
 */
'use strict';

import '../../../node_modules/foundation-datepicker/js/foundation-datepicker.min';

import { FormBuilderInput } from './lib/fb-object';
import { utils } 			from './lib/fb-utilities';
import { dateUtils } 		from './lib/fb-dates';
import { session } 			from './lib/fb-session';
import { submit } 			from './lib/fb-submit';
import repeater 			from './lib/fb-add-rows';
import events 				from './lib/fb-events';
import sections 			from './lib/fb-sections';
import formbuilder 			from './lib/fb-main';
import signUp 				from './lib/fb-sign-up-form';
// import marketingAjax        from './lib/fb-margeting';

formbuilder(FormBuilderInput, events, utils, dateUtils, session, sections, submit, signUp, repeater);

import SlimSelect from 'slim-select';// https://slimselectjs.com/install
jQuery(document).ready(function($) {
	new SlimSelect({
	  select: '.js-select2-multiple'
	});
	new SlimSelect({
	  select: '#form-northern-ireland-county'
	});
	console.log('formbuilderjs-select2-multiple')	
});
