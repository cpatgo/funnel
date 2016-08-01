<?php

require_once dirname(__FILE__) . "/modifier.alang.php";

function smarty_function_adesk_headercol($params, $smarty) {
    #<td align="center" width="100"><a href="#" id="sorter02" onclick="return article_sort('02');" class="{if $artsort == '02'}adesk_sort_asc{elseif $artsort == '02D'}adesk_sort_desc{else}adesk_sort_other{/if}">{"Last Modified"|alang}</a></td>

	$id  = $params["id"];
	$act = $params["action"];
	$lab = $params["label"];

	$cls = "adesk_sort_other";
	$prfx = "list_sorter";
	if (isset($params["class"]))
		$cls = $params["class"];
	if (isset($params["idprefix"]))
		$prfx = $params["idprefix"];


	return "<a href='#' id='$prfx$id' onclick='return {$act}_list_chsort(\"$id\")' class='$cls'>$lab</a>";
}

?>
