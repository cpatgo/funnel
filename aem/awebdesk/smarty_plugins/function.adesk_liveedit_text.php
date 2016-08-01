<?php

function smarty_function_adesk_liveedit_text($params, $smarty) {
	if (!isset($params["id"]) || !isset($params["text"]) || !isset($params["func"]) || !isset($params["relid"]) || !isset($params["column"]))
		return "";

	require_once dirname(dirname(__FILE__)) . "/smarty/plugins/modifier.escape.php";

	$id     = $params["id"];
	$text   = smarty_modifier_escape($params["text"]);
	$func   = $params["func"];
	$relid  = $params["relid"];
	$column = $params["column"];
	$hook   = "null";

	if (isset($params["hook"]))
		$hook = $params["hook"];

	$out = "<script type='text/javascript'>
		var {$id}_orig = '$text';
		var {$id}_cb = adesk_liveedit_func_cb('$id', $hook);
		var {$id}_api = adesk_liveedit_func_api('$func', {$id}_cb, '$column', '$id', 'get');

		var {$id}_revert = adesk_liveedit_func_revert('$id');
		var {$id}_edit = adesk_liveedit_func_edit('$id');
		var {$id}_save = adesk_liveedit_func_save('$id', $relid, {$id}_revert, {$id}_api);
	</script>";

	# If we were not passed static=x, then we assume static=1.

	if (!isset($params["static"]) || $params["static"] == 1) {
		if (isset($params["dblclick"]) && $params["dblclick"] == 0)
			$out .= "<span class='adesk_liveedit_text' style='display:inline' id='$id'>$params[text]</span>";
		else
			$out .= "<span ondblclick=\"if (adesk_liveedit_enabled) {$id}_edit()\" class='adesk_liveedit_text' style='display:inline' id='$id'>$text</span>";
		$out .= "<div id='{$id}_contain' style='display: none'>";
		$out .= "<form method='POST' onsubmit=\"return {$id}_save()\">";
		$out .= "<input type='text' class='adesk_liveedit_form_text' id='{$id}Editor' value='$text' onblur=\"{$id}_save()\" onkeypress=\"adesk_dom_keypress_doif(event, 27, {$id}_revert)\" />";
		$out .= "</form>";
		$out .= "</div>";
	}

	return $out;
}

?>
