var campaign_share_str_noaddrto   = '{"You must supply a recipient address."|alang|js}';
var campaign_share_str_noaddrfrom = '{"You must supply an address to send from."|alang|js}';
var campaign_share_str_nosubject  = '{"You must supply a subject."|alang|js}';
var campaign_share_str_nomessage  = '{"You must supply a message and make sure that message contains %REPORTLINK% somewhere inside it."|alang|js}';
var campaign_share_str_defmessage = '{"Please view your mailing campaign reports at"|alang|js}';
{literal}
var campaign_share_id = 0;

function campaign_share_check(id) {
	adesk_ajax_call_cb("awebdeskapi.php", "campaign.campaign_share_get", campaign_share_check_cb, id);
}

function campaign_share_defaults() {
	$("share_addrto").value   = "";
	$("share_nameto").value   = "";
	$("share_addrfrom").value = adesk_js_admin.email;
	$("share_namefrom").value = adesk_js_admin.fullname;
	$("share_subject").value  = "";
	$("share_message").value  = sprintf("%s\n\n%%REPORTLINK%%", campaign_share_str_defmessage);
}

function campaign_share_check_cb(xml) {
	var ary = adesk_dom_read_node(xml);

	campaign_share_defaults();
	campaign_share_id = ary.id;
	
	$("share_link").value = ary.sharelink;
	adesk_dom_display_block("share");	// can't use toggle here in IE
}

function campaign_share(id) {
	var post = adesk_form_post("share");

	if ( !adesk_str_email(post.addrto) ) {
		alert(campaign_share_str_noaddrto);
		return;
	}

	if ( !adesk_str_email(post.addrfrom) ) {
		alert(campaign_share_str_noaddrfrom);
		return;
	}

	if (post.subject == "") {
		alert(campaign_share_str_nosubject);
		return;
	}

	if (post.message == "" || !post.message.match(/%REPORTLINK%/)) {
		alert(campaign_share_str_nomessage);
		return;
	}

	post.campaignid = campaign_share_id;
	adesk_ajax_post_cb("awebdeskapi.php", "campaign.campaign_share", campaign_share_cb, post);
}

function campaign_share_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	if (ary.succeeded != "0") {
		adesk_result_show(ary.message);
		adesk_ui_anchor_set(report_campaign_list_anchor());
	} else {
		adesk_error_show(ary.message);
	}

	adesk_dom_toggle_display("share", "block");
}
{/literal}
