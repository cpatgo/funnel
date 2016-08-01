<?php

function assemble_error_codes($error_lists_string, $error_codes_string) {
	$legend = array (
		'0'       => _p("Unknown response code. Please resubmit the subscription form."),
		'1'       => _p("This list is currently not accepting subscribers. This list has met its top number of allowed subscribers."),
		'2'       => _p("Your subscription request for this list could not be processed as you are missing required fields."),
		'3'       => _p("This e-mail address is already subscribed to this mailing list."),
		'4'       => _p("This e-mail address has been processed in the past to be subscribed, however your subscription was never confirmed."),
		'5'       => _p("This e-mail address cannot be added to list."),
		'6'       => _p("This e-mail address has been processed. Please check your email to confirm your subscription."),
		'7'       => _p("This e-mail address has subscribed to the list."),
		'8'       => _p("E-mail address is invalid."),
		'9'       => _p("Subscription could not be processed since you did not select a list. Please select a list and try again."),
		'10'      => _p("This e-mail address has been processed. Please check your email to confirm your unsubscription."),
		'11'      => _p("This e-mail address has been unsubscribed from the list."),
		'12'      => _p("This e-mail address was not subscribed to the list."),
		'13'      => _p("Thank you for confirming your subscription."),
		'14'      => _p("Thank you for confirming your unsubscription."),
		'15'      => _p("Your changes have been saved."),
		'16'      => _p("Your subscription request for this list could not be processed as you must type your name."),
		'17'      => _p("This e-mail address is on the global exclusion list."),
		'18'      => _p("Please type the correct text that appears in the image."),
		'19'      => _p("Subscriber ID is invalid."),
		'20'      => _p("You are unable to be added to this list at this time."),
		'21'      => _p("You selected a list that does not allow duplicates. This email is in the system already, please edit that subscriber instead."),
		'22'      => _p("This e-mail address could not be unsubscribed."),
		'23'      => _p("This subscriber does not exist."),
		'24'      => _p("The link to modify your account has been sent. Please check your email."),
		'25'      => _p("The image text you typed did not register. Please go back, reload the page, and try again."),
	);

	$error_lists = explode(',', $error_lists_string);
	$error_codes = explode(',', $error_codes_string);

	$message = "";

	foreach ( $error_lists as $k => $listid ) {
		$code = ( isset($error_codes[$k]) ? (int)$error_codes[$k] : 0 );
		if ( isset($legend[$code]) ) {
			/* internal */
			$list = list_select_row($listid);

			if ($list) {
				if (adesk_http_param("mode") == "subscribe_error") {
					$list_reference = "<b>ERROR:</b> " . $list["name"] . ": ";
				}
				else {
					$list_reference = "<b>" . $list["name"] . ":</b> ";
				}
			}
			else {
				if (adesk_http_param("mode") == "subscribe_error") {
					$list_reference = "<b>ERROR:</b> ";
				}
				else {
					$list_reference = "";
				}
			}

			$message .= $list_reference . $legend[$code] . '<br />';
			/* internal */
			//$message .= 'List ID: ' . $listid . '; Message: ' . $legend[$code] . '<br />';
		}
	}

	return $message;
}

?>