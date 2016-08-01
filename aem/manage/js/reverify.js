var reverify_str_length_exceeded = '{"Your message may not be longer than three-hundred (300) characters."|alang|js}';

{literal}

function reverify_message_next() {
	var msg = $("textmessage").value;

	msg = strip_tags(msg);
	msg = msg.replace(/\n\n\n+/, "\n\n");

	if (msg.length > 300) {
		alert(reverify_str_length_exceeded);
		return;
	}

	$("message").value = msg;
	$("emailmessage").innerHTML = msg;

	$("step_message").hide();
	$("step_email").show();
}

{/literal}
