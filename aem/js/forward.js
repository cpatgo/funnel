var forward_alert1 = '{"Invalid email address"|plang|js}';
var forward_alert2 = '{"Please fill out at least one Friend email address and name."|plang|js}';
var forward_alert3 = '{"Please ensure all email addresses are valid."|plang|js}';
var forward_msg1 = '{"Comment from"|plang|js}';

{literal}

function forward_form_submit() {
	var post = adesk_form_post("form");

	var friend_string = "";

	// Grab <tbody> elements
	var inputs = $("forward_friend_info_tbody").getElementsByTagName("input");

	var valid_submission = true;
	var total_rows_with_text = 0;

	// Loop through all <tbody>'s inputs in the table
	for ( var i = 0; i < inputs.length; i++ ) {
		// Only look at tbody's that contain email <input>'s
		if ( inputs[i].name && inputs[i].name == 'to_email[]' ) {

			// Make email <input>s are filled in with something
			if ( inputs[i].value != "" ) {

				total_rows_with_text++;

				// Check for valid email address
				if ( !adesk_str_email(inputs[i].value) ) {
					alert(inputs[i].value + ": " + forward_alert1);
					valid_submission = false;
				}
			}
		}
	}

	if ( total_rows_with_text == 0 ) {
		alert(forward_alert2);
		return false;
	}
	if ( !valid_submission ) {
		alert(forward_alert3);
		return false;
	}

	return true;
}

function forward_update_previewmsg() {

	// Pull the default message from hidden textarea. This value is never changed dynamically.
	var default_message = $("message_default").value;

	// If the Personalized Message textarea is not empty, add additional verbage.
	if ( $("custom_message").value != "" ) {

		// Split the default message string at each new line.
		var default_message_array = default_message.split("\n");

		// For the third item in the array, which begins with "The sender thought the mailing....."
		// Append new text to that item only.
		default_message_array[3] += "\n\n" + forward_msg1 + " " + $("fromnameField").value + ":\n-------------------------\n" + $("custom_message").value + "\n-------------------------";

		var new_message = "";

		// Loop through array items to re-create string. Would prefer "implode"-type function.
		for (var i = 0; i < default_message_array.length; i++) {

			// Push a new line after every array item.
			default_message_array[i] += "\n";

			// Re-create the string by appending each array item.
			new_message += default_message_array[i];
		}

		$("message").value = new_message;
	}
	else {
		// If it is empty, make sure the message reflects the default (in case they add a custom message, then clear it out)

		$("message").value = default_message;
	}
}

{/literal}