var adesk_liveedit_active_id = null;
var adesk_liveedit_enabled = false;

var adesk_liveedit_revert_after = function() {
	// do nothing
};

function adesk_liveedit_func_api(fcall, fcb, extra, id, method, post_id) {
	return function(anon_relid, anon_text) {
		if (method == "get") {
			adesk_ajax_call_cb(apipath, fcall, fcb, anon_relid, anon_text, extra);
		}
		else {
			var post = adesk_form_post($(post_id));
			adesk_ajax_post_cb(apipath, fcall, fcb, post, anon_relid, anon_text, extra);
		}
		if (adesk_editor_is(id + "Editor"))
			$(id).innerHTML = anon_text;
		else
			$(id).innerHTML = adesk_str_htmlescape(anon_text);
	};
}

function adesk_liveedit_func_cb(id, hook) {
	return function(xml) {
		var ary = adesk_dom_read_node(xml, null);
		adesk_ui_api_callback();

		if (ary.succeeded != "0") {
			adesk_result_show(ary.message);
			adesk_dom_liveedit_showtext(id);

			if (typeof adesk_liveedit_onclose != 'undefined')
				adesk_liveedit_onclose(id);

			/*
			if (adesk_editor_is(id + "Editor")) {
				var ed = tinyMCE.get(id + "Editor");
				$(id).innerHTML = ed.getContent();
			} else {
				$(id).innerHTML = $(id + "Editor").value;
			}
			*/

			if (hook !== null && typeof hook == "function")
				hook(ary);
		} else {
			adesk_error_show(ary.message);
			$(id).innerHTML = eval(sprintf("%s_orig", id));
			$(id + "Editor").value = $(id).innerHTML;
		}
	};
}

function adesk_liveedit_func_edit(id) {
	return function() {
		adesk_dom_liveedit_showform(id);
		if (!adesk_editor_is(id + "Editor"))
			$(id + "Editor").select();
		adesk_liveedit_active_id = id;
	};
}

function adesk_liveedit_func_save(id, relid, rev, api) {
	return function() {
		if (adesk_liveedit_active_id === null) {
			return rev();
		}

		var orig = eval(id + "_orig");
		var newstr;

		if (adesk_editor_is(id + "Editor")) {
			var ed = tinyMCE.get(id + "Editor");
			newstr = ed.getContent();
		} else {
			newstr = $(id + "Editor").value;
		}

		if (orig == newstr || newstr == '')
			return rev();

		eval(id + "_orig = newstr;");
		api(relid, newstr);
		adesk_liveedit_active_id = null;
		return false;
	};
}

function adesk_liveedit_func_revert(id) {
	return function() {
		var orig = eval(id + "_orig");
		$(id + 'Editor').value = orig;
		adesk_form_value_set($(id + 'Editor'), orig);
		if (adesk_editor_is(id + "Editor"))
			$(id).innerHTML = orig;
		else
			$(id).innerHTML = adesk_str_htmlescape(orig);
		adesk_dom_liveedit_showtext(id);
		adesk_liveedit_active_id = null;

		if (typeof adesk_liveedit_onclose != 'undefined')
			adesk_liveedit_onclose(id);
		return false;
	};
}

function adesk_liveedit_setparams(tid, text, func, column, relid) {
	window[tid + "_orig"] = text;

	// We may be called multiple times on a single page (e.g. from a paginator), so we want to
	// avoid recreating these functions.

	if (typeof window[tid + "_cb"] == "undefined") {
		window[tid + "_cb"]     = adesk_liveedit_func_cb(tid);
		window[tid + "_api"]    = adesk_liveedit_func_api(func, window[tid + "_cb"], column, tid);
		window[tid + "_revert"] = adesk_liveedit_func_revert(tid);
		window[tid + "_edit"]   = adesk_liveedit_func_edit(tid);
		window[tid + "_save"]   = adesk_liveedit_func_save(tid, relid, window[tid + "_revert"], window[tid + "_api"]);
	}
}

function adesk_liveedit_text(tid, text) {
	var out = sprintf("<div ondblclick=\"if (adesk_liveedit_enabled) %s_edit()\" class='adesk_liveedit_text' style='display:block' id='%s'>%s</div>", tid, tid, text);
	out += sprintf("<div id='%s_contain' style='display: none'>", tid);
	out += sprintf("<form method='POST' onsubmit=\"return %s_save()\">", tid);
	out += sprintf("<input type='text' class='adesk_liveedit_form_text' id='%sEditor' value='%s' onblur=\"%s_save()\" onkeypress=\"adesk_dom_keypress_doif(event, 27, %s_revert)\" />", tid, text, tid, tid);
	out += "</form>";
	out += "</div>";
	return out;
}

function adesk_liveedit_area(tid, text) {
	var class_html   = adesk_js_admin["htmleditor"] ? "currenttab" : "othertab";
	var class_text   = !adesk_js_admin["htmleditor"] ? "currenttab" : "othertab";

	var out = sprintf("<div ondblclick=\"if (adesk_liveedit_enabled) %s_edit()\" class='adesk_liveedit_text' style='display:block' id='%s'>%s</div>", tid, tid, text);
	out += sprintf("<div id='%s_contain' style='display: none'>", tid);
	out += "<ul class='navlist'>";
	out += sprintf("<li id='%sEditorLinkDefault' class='disabledtab' style='float:right; text-align:right; width:100px'>", tid);
	out += sprintf("<a href='#' onclick='return setDefaultEditor(\"%s\");'>%s</a>", tid, jsSetAsDefault);
	out += "</li>";
	out += "<li class='notatab'>" + jsContent + "</li>";
	out += sprintf("<li id='%sEditorLinkOn' class='%s'>", tid, class_html);
	out += sprintf("<a href='#' onclick='return toggleEditor(\"%s\", true);'><span>%s</span></a>", tid, jsHtmlEditor);
	out += "</li>";
	out += "</ul>";
	// Can't use sprintf here since it chokes on the percent signs in the width/height
	out += "<textarea id='" + tid + "Editor' style='width:" + adesk_js_site.brand_editorw + "; height:" + adesk_js_site.brand_editorh + "'>" + text + "</textarea>";
	out += "<br/>";
	out += sprintf("<input type='button' onclick='%s_save()' value='%s' />", tid, jsOK);
	out += sprintf("<input type='button' onclick='%s_revert()' value='%s' />", tid, jsCancel);
	out += "</div>";

	// space for toggle editor

	return out;
}
