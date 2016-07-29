var campaign_obj = {jsvar var=$campaign};

var campaign_step = {jsvar var=$step};

var campaign_header_str_type = '{"Type"|alang|js}';
var campaign_header_str_list = '{"Lists"|alang|js}';
var campaign_header_str_message = '{"Message"|alang|js}';
var campaign_header_str_messages = '{"Messages"|alang|js}';
var campaign_header_str_summary = '{"Summary & Options"|alang|js}';
var campaign_header_str_done = '{"Done"|alang|js}';
var campaign_header_str_noname = '{"Create a New Campaign"|alang|js}';
var campaign_header_str_name = '{"Campaign: %s"|alang|js}';

{literal}

// Set this variable to the milliseconds in which we'll try to automatically save the campaign.
var campaign_save_autotime = 30000;

// If this variable is true, we should not perform any auto-save.  Very handy
// in avoiding race conditions when beginning to actually POST (by clicking
// Next, Save, etc).
var campaign_save_noautosave = false;

// This can be set to true with the campaign_different function.
var campaign_obviously_changed = false;

// This should be set to true when we save with any action.  If this is false,
// campaign_changed() can be used to trigger warnings.
var campaign_doing_something = false;

function campaign_save(aftersave) {
	campaign_doing_something = true;
	campaign_save_noautosave = true;
	$("campaign_aftersave").value = aftersave;

	if (aftersave == "next") {
		if (typeof campaign_validate == "function") {
			if (!campaign_validate(aftersave))
				return;
		}
	}

	if (typeof campaign_fixpost == "function")
		campaign_fixpost();

	campaign_safe();
	$("campaignform").submit();
}

function campaign_save_auto() {
	if (campaign_save_noautosave)
		return;

	var post = $("campaignform").serialize(true);

	// No auto-saves on page 1 or on the tpl selector.
	if (post.action == "campaign_new" || post.action == "campaign_new_template")
		return;

	// Don't save anything if we don't have a name.
	if (post.id == 0 && typeof post.name != "undefined" && post.name == "") {
		campaign_save_auto_runagain();
		return;
	}

	if (typeof post["attach[]"] != "undefined") {
		post.attach = post["attach[]"];
	}

	post.aftersave = "nothing";
	adesk_ajax_post_cb("awebdeskapi.php", "campaign.campaign_save", adesk_ajax_cb(campaign_save_auto_cb), post);
}

function campaign_save_auto_runagain() {
	window.setTimeout('campaign_save_auto()', campaign_save_autotime);
}

function campaign_save_auto_cb(ary) {
	campaign_safe();
	campaign_save_auto_runagain();
}

// Header
function campaign_header_build(highlight, id) {
	var divs = [];

	var div;
	var inner;
	var max = campaign_header_max();

	if (campaign_obj.name == "")
		div = Builder.node("h3", campaign_header_str_noname);
	else
		div = Builder.node("h3", sprintf(campaign_header_str_name, campaign_obj.name));
div.className = "m-b";
	divs.push(div);

	// Turn off the site name div; kind of a hack.  We hope they are using our
	// theme_header_software class, but it's possible they aren't.
	var sitename = $$(".theme_header_software");

	if (typeof sitename[0] != "undefined") {
		sitename[0].innerHTML = "&nbsp;";
	}

	if (max < 4)
		inner = Builder.node("a", { href: sprintf("desk.php?action=campaign_new%s", id > 0 ? "&id=" + id : "") }, [ campaign_header_str_type ]);
	else
		inner = campaign_header_str_type;
	div = Builder.node("div", inner);
	if (highlight == 0)
		div.className = "label bg-success";
		else
		div.className = "label bg-default";
	divs.push(div);

	if (max >= 1 && max < 4)
		inner = Builder.node("a", { href: sprintf("desk.php?action=campaign_new_list&id=%s", id) }, [ campaign_header_str_list ]);
	else
		inner = campaign_header_str_list;

	div = Builder.node("div", inner);
	if (highlight == 1)
		div.className = "label bg-success";
		else
		div.className = "label bg-default";
	divs.push(div);

	// Link to template; if we already chose a template, that will move us to message (or text, if a text based campaign).
	if (max >= 2 && max < 4) {
		if (campaign_obj.type == "split") {
			inner = Builder.node("a", { href: sprintf("desk.php?action=campaign_new_splitmessage&id=%s", id) }, [ campaign_header_str_messages ]);
		} else {
			if (campaign_step == "template")
				inner = Builder.node("a", { href: sprintf("desk.php?action=campaign_new_template&id=%s", id) }, [ campaign_header_str_message ]);
			else {
				if (campaign_obj.type == "text")
					inner = Builder.node("a", { href: sprintf("desk.php?action=campaign_new_text&id=%s", id) }, [ campaign_header_str_message ]);
				else
					inner = Builder.node("a", { href: sprintf("desk.php?action=campaign_new_message&id=%s", id) }, [ campaign_header_str_message ]);
			}
		}
	}
	else
		inner = campaign_header_str_message;

	div = Builder.node("div", inner);
	if (highlight == 2)
		div.className = "label bg-success";
else
		div.className = "label bg-default";
	divs.push(div);

	if (max >= 3 && max < 4)
		inner = Builder.node("a", { href: sprintf("desk.php?action=campaign_new_summary&id=%s", id) }, [ campaign_header_str_summary ]);
	else
		inner = campaign_header_str_summary;

	div = Builder.node("div", inner);
	if (highlight == 3)
		div.className = "label bg-success";
		else
		div.className = "label bg-default";
	divs.push(div);

	inner = campaign_header_str_done;

	div = Builder.node("div", inner);
	if (highlight == 4) {
		div.className = "label bg-success";
		div.style.color = "white";
	} else
		div.className = "label bg-default";
	divs.push(div);

	return divs;
}

function campaign_header(highlight, id) {
	var divs = campaign_header_build(highlight, id);

	adesk_dom_remove_children($("campaign_new_progress"));
	for (var i = 0; i < divs.length; i++) {
		$("campaign_new_progress").appendChild(divs[i]);
	}
}

function campaign_header_max() {
	switch (campaign_obj.laststep) {
		case "type":
		default:
			return 0;

		case "list":
			return 1;

		case "template":
			return 2;

		case "message":
			return 2;

		case "splitmessage":
			return 2;

		case "splittext":
			return 2;

		case "text":
			return 2;

		case "summary":
			return 3;

		case "result":
			// Hack: in cases where you can click "edit" to get into a
			// campaign, pretend our last step was the summary page.
			if ( adesk_array_has([1, 3, 6], campaign_obj.status) )
				return 3;

			return 4;
	}
}

// Change tracking
function campaign_different() {
	campaign_obviously_changed = true;
}

function campaign_safe() {
	campaign_obviously_changed = false;
	if (typeof campaign_changed_safe == "function")
		campaign_changed_safe();
}

function campaign_unload() {
	if (campaign_obviously_changed)
		return messageLP;

	if (!campaign_doing_something) {
		if (typeof campaign_changed == "function" && campaign_changed())
			return messageLP;
	}
}

// set unload
adesk_dom_unload_hook(campaign_unload);

{/literal}
