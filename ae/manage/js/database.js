{literal}

function database_repair() {
	adesk_ajax_call_cb("awebdeskapi.php", "database.database_repair", adesk_ajax_cb(database_repair_cb));
}

function database_optimize() {
	adesk_ajax_call_cb("awebdeskapi.php", "database.database_optimize", adesk_ajax_cb(database_optimize_cb));
}

function database_repair_cb(ary) {
	alert(ary.message);
}

function database_optimize_cb(ary) {
	alert(ary.message);
}

{/literal}
