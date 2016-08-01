<?php

function smarty_function_adesk_liveedit_icon($params, $smarty) {
	if (!isset($params["id"]) || !isset($params["src"]))
		return "";

	$id  = $params["id"];
	$src = $params["src"];
	$out = "<a onclick=\"if (adesk_liveedit_enabled) {$id}_edit()\" href='#'>click</a>";

	return $out;
}
