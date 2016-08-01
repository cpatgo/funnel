var privatemessage_form_str_cant_insert = '{"You do not have permission to add Private Messages"|alang|js}';
var privatemessage_form_str_cant_update = '{"You do not have permission to edit Private Messages"|alang|js}';
var privatemessage_form_str_cant_find   = '{"Private Message not found."|alang|js}';
var privatemessage_str_thread = '{"Thread"|alang|js}';
var privatemessage_str_reply = '{"Reply"|alang|js}';
var privatemessage_str_compose = '{"Compose"|alang|js}';
var privatemessage_str_sentmessage = '{"Sent Message"|alang|js}';
var privatemessage_str_receivedmessage = '{"Received Message"|alang|js}';
var privatemessage_str_from = '{"From"|alang|js}';
var privatemessage_str_to = '{"To"|alang|js}';
var privatemessage_str_fromto = '{"From/To"|alang|js}';
var privatemessage_str_sent = '{"Sent"|alang|js}';
var privatemessage_str_received = '{"Received"|alang|js}';
var privatemessage_str_subject = '{"Subject"|alang|js}';
var privatemessage_str_message = '{"Message"|alang|js}';
var privatemessage_str_on = '{"On"|alang|js}';
var privatemessage_str_at = '{"at"|alang|js}';
var privatemessage_str_wrote = '{"wrote"|alang|js}';
var privatemessage_str_sendyourself = '{"You cannot send a private message to yourself"|alang|js}';

adesk_editor_init_normal();

{literal}
var privatemessage_form_id = 0;

function privatemessage_form_defaults() {
	$("form_id").value = 0;
	//$("authorFilter").value = "";
	$("form_title").value = "";
	adesk_form_value_set($("contentEditor"), "");
}

function privatemessage_form_load(id) {
	privatemessage_form_defaults();
	privatemessage_form_id = id;
	if (id > 0) {
		// View and Reply view
		/*
		if (adesk_js_admin.pg_privmsg_add != 1) {
			adesk_ui_anchor_set(privatemessage_list_anchor());
			alert(privatemessage_form_str_cant_update);
			return;
		}
		*/

		adesk_ui_api_call(jsLoading);
		$("view").className = "adesk_block";
		//$("reply_h2").innerHTML = privatemessage_str_reply;
		//$("form_submit").className = "adesk_button_update";
		adesk_ajax_call_cb("awebdeskapi.php", "privatemessage!adesk_privatemessage_get", privatemessage_form_load_cb, id);
	} else {
		// Compose view
		/*
		if (adesk_js_admin.pg_privmsg_add != 1) {
			adesk_ui_anchor_set(privatemessage_list_anchor());
			alert(privatemessage_form_str_cant_insert);
			return;
		}
		*/

		$("form_submit").className = "adesk_button_add";
		$("reply").className = "adesk_block";
		$("form").className = "adesk_block";
		$("view").className = "adesk_hidden";
		//$("thread").className = "adesk_hidden";
		//$("reply_h2").innerHTML = privatemessage_str_compose;
	}
}

function privatemessage_form_load_cb(xml) {
	var ary = adesk_dom_read_node(xml, null);
	adesk_ui_api_callback();
	if ( !ary.id ) {
		adesk_error_show(privatemessage_form_str_cant_find);
		adesk_ui_anchor_set(privatemessage_list_anchor());
		return;
	}
	privatemessage_form_id = ary.id;

	$("form_id").value = ary.id;
	$("form_from_view").innerHTML = ary.author_from[0].first_name + " " + ary.author_from[0].last_name + " (" + ary.author_from[0].username + ")";
	$("form_to_view").innerHTML = ary.author_to[0].first_name + " " + ary.author_to[0].last_name + " (" + ary.author_to[0].username + ")";
	$("form_sent_view").innerHTML = sql2date(ary.cdate).format(datetimeformat);
	$("form_title_view").innerHTML = ary.title;
	$("form_content_view").innerHTML = ary.content;

	// If it's a message that was *sent* from this user.
	if (ary.user_from == adesk_js_admin["id"]) {
		//$("view_h2").innerHTML = privatemessage_str_sentmessage;
		$("reply").className = "adesk_hidden";
		$("form_submit").className = "adesk_hidden";
	}
	else {
		// If it was a message that was *received* by the user, we're in "Reply assets"
		//$("view_h2").innerHTML = privatemessage_str_receivedmessage;
		$("reply").className = "adesk_block";
		$("form_submit").className = "adesk_button_submit";
		$("authorFilter").value = ary.author_from[0].username;

		var contentEditor_message = "<br /><br /><br /><div>" + sql2date(ary.cdate).format(privatemessage_str_on + " %a, %b %e, %Y " + privatemessage_str_at + " %H:%M") + ", " + ary.author_from[0].first_name + " " + ary.author_from[0].last_name + " (" + ary.author_from[0].username + ") " + privatemessage_str_wrote + ":</div><div style='padding-left: 20px;'>" + ary.content + "</div>";
		adesk_form_value_set($("contentEditor"), contentEditor_message);

		if (ary.title.substring(0, 4) == "Re: ") {
			$("form_title").value = ary.title;
		}
		else {
			$("form_title").value = "Re: " + ary.title;
		}

		if (!ary.is_read) {
			adesk_ajax_call_cb("awebdeskapi.php", "privatemessage!adesk_privatemessage_update_post", privatemessage_update_post_cb, ary.id);
		}
	}

	/*
	var threadid_elements = xml.getElementsByTagName("threadid");

	if (threadid_elements.length > 1) {
		$("thread").className = "";
		adesk_dom_remove_children($("thread"));

		var thread_h2 = Builder.node("h2", {id: "thread_h2"}, privatemessage_str_thread);
		$("thread").appendChild(thread_h2);
	}
	else {
		$("thread").className = "adesk_hidden";
	}

	for (var i = 0; i < threadid_elements.length; i++) {

		if (threadid_elements[i].firstChild.firstChild != null) {

			var thread_ary = adesk_dom_read_node(threadid_elements[i], null);

			var thread_div = document.createElement("div");

			var thread_content = "<table border='0' cellspacing='0' cellpadding='5' class='privatemessage_view'>";
			thread_content += "<tr><th>" + privatemessage_str_from + "</th><td>" + thread_ary.author_from[0].first_name + " " + thread_ary.author_from[0].last_name + "</td></tr>";
     	thread_content += "<tr><th>" + privatemessage_str_to + "</th><td>" + thread_ary.author_to[0].first_name + " " + thread_ary.author_to[0].last_name + "</td></tr>";
     	thread_content += "<tr><th>" + privatemessage_str_sent + "</th><td>" + thread_ary.cdate + "</td></tr>";
      thread_content += "<tr><th>" + privatemessage_str_subject + "</th><td><a href='desk.php?action=privatemessage#form-" + thread_ary.id + "'>" + thread_ary.title + "</a></td></tr>";
      thread_content += "<tr><th>" + privatemessage_str_message + "</th><td>" + thread_ary.content + "</td></tr>";
    	thread_content += "</table>";

			thread_div.id = "message_" + thread_ary.id;

			thread_div.innerHTML = thread_content;

			$("thread").appendChild(thread_div);
		}
	}
	*/

	$("form").className = "adesk_block";
}

function privatemessage_update_post_cb() {

}

function privatemessage_form_save(id) {
	var post = adesk_form_post($("form"));

	// Dis-allow sending private messages to yourself
	if ( post.author_autocomplete == adesk_js_admin["username"] ) {

		alert(privatemessage_str_sendyourself);

		return;
	}

	adesk_ui_api_call(jsSaving);

	adesk_ajax_post_cb("awebdeskapi.php", "privatemessage!adesk_privatemessage_insert_post", privatemessage_form_save_cb, post);
}

function privatemessage_form_save_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.succeeded != "0") {
		adesk_result_show(ary.message);
		adesk_ui_anchor_set(privatemessage_list_anchor());
	} else {
		adesk_error_show(ary.message);
	}
}

function privatemessage_form_history_back() {
	//var b = browser_ident();

	//if (b == "Explorer 7" || b == "Explorer 8") {
	//	window.history.go(-4);
	//}
	//else {
		window.history.go(-1);
	//}
}

{/literal}
