var campaign_template_which = "tdisplay";
var campaign_template_style = "images";
var campaign_template_tag    = "";
var campaign_template_offset = 0;
var campaign_template_length = 25;

var campaign_template_searchkey = "";

var campaign_template_hasmessage = {jsvar var=$hasmessage};
var campaign_template_afterstep = "next";

var preview_timeout = 0;

{literal}

function campaign_template_save(after) {
	if (!campaign_template_hasmessage) {
		campaign_save(after);
		return;
	}

	campaign_template_afterstep = after;
	$("alreadyselected").show();
}

function campaign_template_useexisting() {
	if (campaign_obj.type == "split") {
		window.location.href = sprintf("desk.php?action=campaign_new_splitmessage&id=%s", campaign_obj.id);
	} else if (campaign_obj.type == "text") {
		window.location.href = sprintf("desk.php?action=campaign_new_text&id=%s", campaign_obj.id);
	} else {
		window.location.href = sprintf("desk.php?action=campaign_new_message&id=%s", campaign_obj.id);
	}

	$("alreadyselected").hide();
}

function campaign_template_basetemplate(id) {
	if ($("choice_template_" + id)) {
		// Only do this if we have it--which we may not if we've filtered things down...
		if ($("choice_template_" + $("campaign_basetemplateid").value)) {
			$("choice_template_" + $("campaign_basetemplateid").value).className = "campaign_template_notselected";
		}

		$("campaign_basemessageid").value = 0;
		$("campaign_basetemplateid").value = id;
		$("choice_template_" + id).className = "campaign_template_selected";
	}
}

function campaign_template_basemessage(id) {
	if ($("choice_campaign_" + id)) {
		// Only do this if we have it--which we may not if we've filtered things down...
		if ($("choice_campaign_" + $("campaign_basemessageid").value)) {
			if ($("campaign_basemessageid").value != 0)
				$("choice_campaign_" + $("campaign_basemessageid").value).className = "campaign_template_notselected";
		}
		$("campaign_basetemplateid").value = 0;
		$("campaign_basemessageid").value = id;
		$("choice_campaign_" + id).className = "campaign_template_selected";
	}
}

function campaign_template_clear() {
	adesk_dom_remove_children($("choices"));
}

function campaign_template_setstyle(which) {
	$("span_images").className = "campaign_template_textnotselected";
	$("span_list").className = "campaign_template_textnotselected";

	campaign_template_style = which;
	$("span_" + which).className = "campaign_template_textselected";
}

function campaign_template_switch(which) {
	campaign_template_clear();
	campaign_template_tag = "";

	$A($("taglist").getElementsByTagName("span")).each(function(e) { e.className = "campaign_template_textnotselected"; });

	campaign_template_offset = 0;
	campaign_template_length = 25;
	campaign_template_which = which;

	if (which == "tdisplay")
		campaign_template_setstyle("images");
	else if (which == "cdisplay")
		campaign_template_setstyle("list");

	campaign_template_display();

	$("span_tdisplay").className = "campaign_template_textnotselected";
	$("span_cdisplay").className = "campaign_template_textnotselected";

	$("span_" + which).className = "campaign_template_textselected";
}

function campaign_template_view(style) {
	campaign_template_clear();

	campaign_template_offset = 0;
	campaign_template_length = 25;
	campaign_template_style = style;

	campaign_template_display();

	$("span_images").className = "campaign_template_textnotselected";
	$("span_list").className = "campaign_template_textnotselected";

	$("span_" + style).className = "campaign_template_textselected";
}

function campaign_template_display() {
	var searchkey = campaign_template_searchkey;

	if (campaign_template_which == "cdisplay")
		adesk_ajax_call_cb("awebdeskapi.php", "template.template_selector_cdisplay", adesk_ajax_cb(campaign_template_cdisplay_cb), campaign_template_tag, searchkey, campaign_template_offset, campaign_template_length);
	else
		adesk_ajax_call_cb("awebdeskapi.php", "template.template_selector_tdisplay", adesk_ajax_cb(campaign_template_tdisplay_cb), campaign_template_tag, searchkey, campaign_template_offset, campaign_template_length);
}

function campaign_template_tdisplay_cb(ary) {
	if (typeof ary.row == "undefined")
		return;

	if (ary.loadmore)
		$("loadmore").show();
	else
		$("loadmore").hide();

	for (var i = 0; i < ary.row.length; i++) {
		var clickelem = "a";
		var clickprops = {
			href: "#",
			onclick: sprintf("window.open('htmlawebview.php?t=%s', 'preview', 'height=600,width=800,menubar=no,location=no,resizable=yes,scrollbars=yes,status=yes'); return false", ary.row[i].id)
		};

		var graphic = "images/preview-16-16.gif";

		if (!ary.row[i].haspreview) {
			graphic = "images/dimmed-preview-16-16.gif";
			clickelem = "span";
			clickprops = {};
		}

		if (campaign_template_style == "list") {
			$("choices").appendChild(Builder.node("div", { id: sprintf("choice_template_%s", ary.row[i].id), style: "height: 16px; margin: 5px; padding: 5px;", className: "campaign_template_notselected" }, [
				Builder.node("div", [
					Builder.node(clickelem, clickprops, [
						Builder.node("img", { src: graphic, style: "float: right", border:0 })
					]),
					Builder.node("div", { style: "float: left; width: 95%; cursor: pointer", onclick: sprintf("campaign_template_basetemplate(%s); campaign_different();", ary.row[i].id), ondblclick: sprintf("campaign_template_basetemplate(%s); campaign_save('next');", ary.row[i].id)  }, adesk_str_shorten(ary.row[i].name, 25))
				])
			]));
		} else {
			$("choices").appendChild(Builder.node("div", { id: sprintf("choice_template_%s", ary.row[i].id), style: "margin-bottom:15px; padding: 0px; float: left; margin-right: 15px", className: "campaign_template_notselected" }, [
				Builder.node("a", { href: "#", onclick: sprintf("campaign_template_basetemplate(%s); campaign_different(); return false", ary.row[i].id), ondblclick: sprintf("campaign_template_basetemplate(%s); campaign_save('next'); return false", ary.row[i].id) }, [
					Builder.node("img", { width: "200", height: "250", border:0, src: sprintf("preview_message.php?which=tpl&id=%s", ary.row[i].id) })
				]),
				Builder.node("div", {className: "tpl_selector_name"}, [
					Builder.node(clickelem, clickprops, [
						Builder.node("img", { src: graphic, style: "float: right", border:0 })
					]),
					Builder.node("div", { }, adesk_str_shorten(ary.row[i].name, 25))
					
				])
			]));
		}
	}

	if (ary.row.length == 0) {
		$("loadmore").hide();
		$("emptysearch").show();
	} else {
		$("emptysearch").hide();
	}

	campaign_template_basetemplate($("campaign_basetemplateid").value);
}

function campaign_template_cdisplay_cb(ary) {
	if (typeof ary.row == "undefined")
		return;

	if (ary.loadmore)
		$("loadmore").show();
	else
		$("loadmore").hide();

	for (var i = 0; i < ary.row.length; i++) {
		var clickelem = "a";
		var clickprops = {
			href: "#",
			onclick: sprintf("window.open('htmlawebview.php?m=%s', 'preview', 'height=600,width=800,menubar=no,location=no,resizable=yes,scrollbars=yes,status=yes'); return false", ary.row[i].id)
		};

		var graphic = "images/preview-16-16.gif";

		if (!ary.row[i].haspreview) {
			graphic = "images/dimmed-preview-16-16.gif";
			clickelem = "span";
			clickprops = {};
		}

		if (campaign_template_style == "list") {
			$("choices").appendChild(Builder.node("div", { id: sprintf("choice_campaign_%s", ary.row[i].id), style: "height: 16px; margin: 5px; padding: 5px;", className: "campaign_template_notselected" }, [
				Builder.node("div", [
					Builder.node(clickelem, clickprops, [
						Builder.node("img", { src: graphic, style: "float: right;", border:0 })
					]),
					Builder.node("div", { style: "float: left; width: 95%; cursor: pointer", onclick: sprintf("campaign_template_basemessage(%s); campaign_different(); return false", ary.row[i].id), ondblclick: sprintf("campaign_template_basemessage(%s); campaign_save('next');", ary.row[i].id) }, ary.row[i].subject)
				])
			]));
		} else {
			$("choices").appendChild(Builder.node("div", { id: sprintf("choice_campaign_%s", ary.row[i].id), style: "margin: 5px; padding: 0px; float: left; margin-right: 30px", className: "campaign_template_notselected" }, [
				Builder.node("a", { href: "#", onclick: sprintf("campaign_template_basemessage(%s); campaign_different(); return false", ary.row[i].id), ondblclick: sprintf("campaign_template_basemessage(%s); campaign_save('next'); return false", ary.row[i].id) }, [
					Builder.node("img", { src: sprintf("preview_message.php?which=tpl&id=0"), border:0 })
				]),
				Builder.node("div", [
					Builder.node(clickelem, clickprops, [
						Builder.node("img", { src: graphic, style: "float: right", border:0 })
					]),
										Builder.node("div", { style: "float: left" }, adesk_str_shorten(ary.row[i].subject, 25))

				])
			]));
		}
	}

	if (ary.row.length == 0) {
		$("loadmore").hide();
		$("emptysearch").show();
	} else {
		$("emptysearch").hide();
	}

	if ($("campaign_basemessageid").value > 0)
		campaign_template_basemessage($("campaign_basemessageid").value);
}

function campaign_typesearch(filter) {
	if (preview_timeout > 0)
		window.clearTimeout(preview_timeout);

	preview_timeout = window.setTimeout(sprintf("campaign_template_searchkey = '%s'; campaign_template_clear(); campaign_template_display()", encodeURIComponent(filter)), 500);
}

function campaign_template_usetag(tagid) {
	campaign_template_tag = tagid;
	
	// We had selected past campaigns, then; switch to images when we use the tag.
	if (campaign_template_which != "tdisplay") {
		campaign_template_style = "images";
		$("span_images").className = "campaign_template_textselected";
		$("span_list").className = "campaign_template_textnotselected";
	}

	campaign_template_which = "tdisplay";

	$("searchkey").value = "";
	campaign_template_searchkey = "";

	$("span_tdisplay").className = "campaign_template_textnotselected";
	$("span_cdisplay").className = "campaign_template_textnotselected";

	campaign_template_clear();
	campaign_template_display();

	$A($("taglist").getElementsByTagName("span")).each(function(e) { e.className = "campaign_template_textnotselected"; });
	$("tag_" + tagid).className = "campaign_template_textselected";
}

function campaign_template_loadmore() {
	campaign_template_offset += campaign_template_length - 1;
	campaign_template_display();
}

{/literal}
