<?php

function smarty_function_adesk_liveedit_area($params, $smarty) {
	if (!isset($params["id"]) || !isset($params["text"]) || !isset($params["func"]) || !isset($params["relid"]) || !isset($params["column"]))
		return "";

	require_once dirname(dirname(__FILE__)) . "/smarty/plugins/modifier.escape.php";

	$method = ( isset($params["method"]) ) ? $params["method"] : "get";
	$post_id = "";
	if ($method == "post") $post_id = ( isset($params["post_id"]) ) ? $params["post_id"] : "";
	$id         = $params["id"];
	$text       = addslashes($params["text"]);
	$func       = $params["func"];
	$relid      = $params["relid"];
	$column     = $params["column"];
	$hook       = "null";
	$t_height   = "200px";
	$t_width    = "100%";
	$unsafetext = $params["text"];

	if (isset($params["t_height"]))
		$t_height = $params["t_height"];
	if (isset($params["t_width"]))
		$t_width = $params["t_width"];

	if (isset($params["hook"]))
		$hook = $params["hook"];

	$esctext = str_replace("\n", '\n', $text);
	$esctext = str_replace("\r", '', $esctext);
	# This is causing more problems than it's fixing; don't escape characters now.
	#$esctext = htmlspecialchars($esctext);

	$out = "<script type='text/javascript'>
		var {$id}_orig   = '$esctext';
		var {$id}_cb     = adesk_liveedit_func_cb('$id', $hook);
		var {$id}_api    = adesk_liveedit_func_api('$func', {$id}_cb, '$column', '$id', '$method', '$post_id');

		var {$id}_revert = adesk_liveedit_func_revert('$id');
		var {$id}_edit   = adesk_liveedit_func_edit('$id');
		var {$id}_save   = adesk_liveedit_func_save('$id', $relid, {$id}_revert, {$id}_api);
	</script>";

	if (isset($params["dblclick"]) && $params["dblclick"] == 0)
		$out .= "<span class='adesk_liveedit_text' style='display:inline' id='$id'>$params[text]</span>";
	else
		$out .= "<span ondblclick=\"if (adesk_liveedit_enabled) {$id}_edit()\" class='adesk_liveedit_text' style='display:inline' id='$id'>$params[text]</span>";

	# If we were not passed static=x, then we assume static=1.

	if (!isset($params["static"]) || $params["static"] == 1) {
		$out .= "<div id='{$id}_contain' style='display: none'>";

		$admin        = adesk_admin_get();
		$site         = adesk_site_get();

		$class_html   = $admin["htmleditor"] ? "currenttab" : "othertab";
		$class_text   = !$admin["htmleditor"] ? "currenttab" : "othertab";
		if ( isset($smarty->_folder) and $smarty->_folder == 'admin' ) {
			$text_default = _a("Set as Default");
			$text_content = _a("Content");
			$text_html    = _a("HTML Editor");
			$text_text    = _a("Text Editor");
			$text_ok      = _a("OK");
			$text_cancel  = _a("Cancel");
		} else {
			$text_default = _p("Set as Default");
			$text_content = _p("Content");
			$text_html    = _p("HTML Editor");
			$text_text    = _p("Text Editor");
			$text_ok      = _p("OK");
			$text_cancel  = _p("Cancel");
		}

		$out .= "
			<div id='{$id}_contain'>
				<ul class='navlist'>
					<li id='{$id}EditorLinkDefault' class='disabledtab' style='float: right; text-align: right; width: 100px;'>
						<a href='#' onclick='return setDefaultEditor(\"$id\");'>$text_default</a>
					</li>
					<li id='{$id}EditorLinkOn' class='$class_html'>
						<a href='#' onclick='return toggleEditor(\"$id\", true);'><span>$text_html</span></a>
					</li>
					<li id='{$id}EditorLinkOff' class='$class_text'>
						<a href='#' onclick='return toggleEditor(\"$id\", false);'><span>$text_text</span></a>
					</li>
				</ul>

				<textarea name='{$id}Editor' id='{$id}Editor' style='width: $t_width; height: $t_height;'>$unsafetext</textarea>
				<br/>
				<input type='button' onclick='{$id}_save()' value='$text_ok' />
				<input type='button' onclick='{$id}_revert()' value='$text_cancel' />
			</div>
		";

		if ($admin["htmleditor"])
			$out .= "<script>toggleEditor('$id', true);</script>";
		$out .= "</div>";
	}

	return $out;
}
