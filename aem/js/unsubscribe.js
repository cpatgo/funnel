var unsubscribe_email_missing = '{"Please include an email address."|plang|js}';
var unsubscribe_email_invalid = '{"Please include a valid email address."|plang|js}';

{literal}

var customFieldsObj = new ACCustomFields({
	sourceType: 'CHECKBOX',
	sourceId: 'parentsListBox',
	sourceName: 'nlbox[]',
	api: 'form.form_list_change',
	responseIndex: 'fields',
	includeGlobals: 1,
	additionalHandler: function(ary) {

		var show_captcha = false;

		for ( var i = 0; i < ary.lists.length; i++ ) {
			if ( ary.lists[i].p_use_captcha == 1 ) {
				show_captcha = true;
			}
		}
		if ( adesk_js_site.gd != 1 ) show_captcha = false;

		// Show the captcha div if the list has 1 for p_use_captcha.
		$("unsubscribe_use_captcha").className = ( show_captcha ? '' : 'adesk_hidden' );
	}
});
customFieldsObj.addHandler('custom_fields_table', 'display');

function unsubscribe_list_loadfields() {
	customFieldsObj.fetch(0);
	//update_custom_fields_checkbox(0);
}

function unsubscribe_validate() {
	if ( $('email').value == '' ) {
		alert(unsubscribe_email_missing);
		$('email').focus();
		return false;
	}

	if ( !adesk_str_email($('email').value) ) {
		alert(unsubscribe_email_invalid);
		$('email').focus();
		return false;
	}

	if ( !adesk_form_check_selection_check($('parentsListBox'), 'nlbox[]', jsNothingSelected, jsNothingFound) ) {
		return false;
	}

	return true;
}

{/literal}