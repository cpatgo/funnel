<?php

function site_updateacu() {
	if (!isset($_POST["msg"]) || !isset($_POST["acu"]))
		return adesk_ajax_api_result(false, _a("Internal error: did not receive correct parameters"));

	$acu = adesk_sql_escape($_POST["acu"]);
	adesk_sql_query("UPDATE #backend SET acu = '$acu' WHERE id = 1");

	return adesk_ajax_api_result(true, $_POST["msg"]);
}

?>
