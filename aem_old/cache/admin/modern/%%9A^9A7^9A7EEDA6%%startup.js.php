<?php /* Smarty version 2.6.12, created on 2016-07-08 14:09:37
         compiled from startup.js */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'startup.js', 1, false),array('modifier', 'js', 'startup.js', 1, false),)), $this); ?>
var startup_nothingfound = '<?php echo ((is_array($_tmp=((is_array($_tmp="Nothing found.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var startup_viewreports  = '<?php echo ((is_array($_tmp=((is_array($_tmp='View Reports')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';

<?php echo '

function startup_load() {
  if ( $(\'startup_help_videos\') )
    adesk_ajax_call_cb("awebdeskapi.php", "settings.settings_help_videos", settings_help_videos_cb, "");
}

function startup_viable() {
  if (typeof adesk_js_admin.groups[3] != "undefined")
	adesk_ajax_call_cb("awebdeskapi.php", "startup.startup_viable", adesk_ajax_cb(startup_viable_cb));
}

function startup_viable_cb(ary) {
  // Everything worked fine!
  if (ary.result == 1)
	return;
  else {
//	$("badhttp").className = "adesk_block";
  }
}

function startup_rewrite() {
  if (typeof adesk_js_admin.groups[3] != "undefined")
	adesk_ajax_call_cb("awebdeskapi.php", "startup.startup_rewrite", adesk_ajax_cb(startup_rewrite_cb));
}

function startup_rewrite_cb(ary) {
  if (ary.result == 1)
	return;
  else {
	//$("badfriendlyurls").className = "adesk_block";
  }
}

function startup_recent_subscribers(limit) {
  adesk_ajax_call_cb("awebdeskapi.php", "startup.startup_recent_subscribers", adesk_ajax_cb(startup_recent_subscribers_cb), limit);
}

function startup_recent_subscribers_cb(ary) {
  if (typeof ary.row != "undefined") {
	var sdate = "";
	for (var i = 0; i < ary.row.length; i++) {
	  if (ary.row[i].sdate != "")
		sdate = sql2date(ary.row[i].sdate).format(datetimeformat);
	  else
		sdate = "N/A";
		if ( $("subTable") ) {
		  $("subTable").appendChild(
			  Builder.node("tr", [
				Builder.node("td", ary.row[i].a_listname),
				Builder.node("td", [
				  Builder.node("a", { href: sprintf("desk.php?action=subscriber_view&id=%s", ary.row[i].id) }, ary.row[i].email)
				  ]),
				Builder.node("td", ary.row[i].a_fullname),
				Builder.node("td", sdate)
				]));
		}
	}
	if ($("subLoadingBar")) $("subLoadingBar").className = "adesk_hidden";
  } else {
	if ($("subLoadingBar")) $("subLoadingBar").innerHTML        = startup_nothingfound;
	if ($("subLoadingBar")) $("subLoadingBar").style.background = "";
  }
}

function startup_recent_campaigns(limit) {
  adesk_ajax_call_cb("awebdeskapi.php", "startup.startup_recent_campaigns", adesk_ajax_cb(startup_recent_campaigns_cb), limit);
}

function startup_recent_campaigns_cb(ary) {
  if (typeof ary.row != "undefined") {
	var sdate = "";
	var viewr = null;
	for (var i = 0; i < ary.row.length; i++) {
	  if (ary.row[i].sdate != "")
		sdate = sql2date(ary.row[i].sdate).format(datetimeformat);
	  else
		sdate = "N/A";

	  if (ary.row[i].status != 0 && adesk_js_admin.pg_reports_campaign)
		viewr = Builder.node("a", { href: sprintf("desk.php?action=report_campaign&id=%s", ary.row[i].id) }, startup_viewreports);
	  else
		viewr = "N/A";

	  $("campTable").appendChild(
		  Builder.node("tr", [
			Builder.node("td", ary.row[i].name),
			Builder.node("td", Builder.node("span", { onmouseover: sprintf("adesk_tooltip_show(\'%s\')", ary.row[i].a_lists), onmouseout: "adesk_tooltip_hide()" }, ary.row[i].a_lists_short)),
			Builder.node("td", ary.row[i].a_statusname),
			Builder.node("td", sdate),
			Builder.node("td", viewr)
			]));
	}
	$("campLoadingBar").className = "adesk_hidden";
  } else {
	$("campLoadingBar").innerHTML        = startup_nothingfound;
	$("campLoadingBar").style.background = "";
  }
}

function settings_help_videos_cb(xml) {
  var ary = adesk_dom_read_node(xml);
  if($("startup_help_videos")!=null) {
    $("startup_help_videos").innerHTML = ary.help;
    $("startup_help_videos").className = "";
  }
}

function startup_gettingstarted_hide(groupids) {
  $("startup_box_getting_started").className = "adesk_hidden";

  adesk_ajax_call_cb("awebdeskapi.php", "settings.settings_gettingstarted_hide", settings_gettingstarted_hide_cb, groupids);
}

function settings_gettingstarted_hide_cb(xml) {
  var ary = adesk_dom_read_node(xml);
}

function campaign_sendlog_switch() {
	adesk_ajax_call_cb(
		"awebdeskapi.php",
		"settings.settings_sendlog_switch",
		adesk_ajax_cb(function(ary) {
			if ( ary.succeeded == 1 ) {
				adesk_result_show(ary.message);
				var rel = $(\'campaign_sendlog_warn\');
				if ( rel ) rel.className = ary.newval > 0 ? \'resultMessage\' : \'adesk_hidden\';
			} else {
				adesk_error_show(ary.message);
			}
		})
	);
	return false;
}

adesk_dom_onload_hook(startup_load);

'; ?>
