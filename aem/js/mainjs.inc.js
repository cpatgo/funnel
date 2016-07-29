paginator_b64 = false;	  // don't base64 encode
// define vars
{* global js vars *}
{include file="strings.js"}
{jsvar name=datetimeformat var=$site.datetimeformat}
{jsvar name=dateformat var=$site.dateformat}
{jsvar name=timeformat var=$site.timeformat}
{jsvar name=adesk_js_site var=$jsSite}
{jsvar name=adesk_js_admin var=$jsAdmin}
{jsvar name=adesk_action var=$action}
{jsvar name=plink var=$plink}

var apipath = "{$_}/awebdeskapi.php";
var acgpath = "{$plink}/awebdesk";

adesk_tooltip_init();
adesk_editor_init_mid_object.content_css = adesk_js_site.p_link + adesk_editor_init_mid_object.content_css;

{literal}
adesk_liveedit_onclose = function(id) {
	return;
	if (id == "acontent") {
		// Article content
		window.setTimeout(function() {
			main_highlight_def($("article_content"), glossary, "article_highlight");
			main_highlight_def($("article_content"), glossary_s, "article_highlight");
		}, 200);
	} else if (id == "category_descript") {
		// Category description
		window.setTimeout(function() {
			main_highlight_def($("category_descript"), glossary, "article_highlight");
			main_highlight_def($("category_descript"), glossary_s, "article_highlight");
		}, 200);
	}
}

function main_highlight_def(elem, dict, cls) {
	adesk_dom_highlight(elem, dict, true);
	adesk_dom_highlight_definition(elem, dict, cls);
}

function main_highlight(elem, dict, cls) {
	adesk_dom_highlight(elem, dict, false);
	adesk_dom_highlight_replace(elem, dict, cls);
}

{/literal}
