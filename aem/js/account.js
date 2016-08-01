var account_email_missing = '{"Please include an email address."|plang|js}';
var account_email_invalid = '{"Please include a valid email address."|plang|js}';
var account_captcha_missing = '{"Please include a text from the image."|plang|js}';

{literal}

function account_validate() {
	if ( $('emailField').value == '' ) {
		alert(account_email_missing);
		$('emailField').focus();
		return false;
	}

	if ( !adesk_str_email($('emailField').value) ) {
		alert(account_email_invalid);
		$('emailField').focus();
		return false;
	}

	if ( $('imgverify').value == '' ) {
		alert(account_captcha_missing);
		$('imgverify').focus();
		return false;
	}

	return true;
}

{/literal}