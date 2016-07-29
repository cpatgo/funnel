<?php

require_once awebdesk_functions("process.php");
require_once awebdesk_functions("http.php");

function database_backup($gz) {
	// turning off some php limits
	@ignore_user_abort(1);
	@ini_set('max_execution_time', 950 * 60);
	@set_time_limit(950 * 60);
	$ml = ini_get('memory_limit');
	if ( (int)$ml != -1 ) @ini_set('memory_limit', '-1');

	// setting the db backup to echo
	if ( isset($GLOBALS["sqlstream"]) ) unset($GLOBALS["sqlstream"]);
	$GLOBALS['sqlstreamecho'] = 1;
	if ( $gz ) $GLOBALS['gzip_sql'] = 1;
	//$GLOBALS["sqlstream"] = "";
	# Perform the backup including aweb_globalauth (handled by the first true) and including DROP
	# TABLE commands for each table prior to their creation (the second true).
	adesk_sql_backup_all(true, false, false, '', true);
	//return $GLOBALS["sqlstream"];
}

function database_repair() {
	if ($GLOBALS["admin"]["id"] != 1)
		return adesk_ajax_api_nopermission(_a("Repair tables"));

	$rs     = adesk_sql_query("SHOW TABLE STATUS");
	$data   = array();
	$tables = array();

	while ($row = adesk_sql_fetch_assoc($rs)) {
		$row["Name"] = str_replace("Aawebdesk_", "_", $row["Name"]);
		$tables[$row["Name"]] = array($row["Engine"], 0);
	}

	$data["operation"] = "repair";
	$data["tables"]    = $tables;

	adesk_process_create("database", count($tables), $data, false);

	return adesk_ajax_api_result(true, _a("Database repair has begun"));
}

function database_optimize() {
	if ($GLOBALS["admin"]["id"] != 1)
		return adesk_ajax_api_nopermission(_a("Optimize tables"));

	$rs     = adesk_sql_query("SHOW TABLE STATUS");
	$data   = array();
	$tables = array();

	while ($row = adesk_sql_fetch_assoc($rs)) {
		$row["Name"] = str_replace("Aawebdesk_", "_", $row["Name"]);
		$tables[$row["Name"]] = array($row["Engine"], 0);
	}

	$data["operation"] = "optimize";
	$data["tables"]    = $tables;

	adesk_process_create("database", count($tables), $data, false);

	return adesk_ajax_api_result(true, _a("Database optimization has begun"));
}

function database_safestring($str) {
	return preg_match('/^[a-zA-Z0-9_]+$/', $str);
}

function database_handle($proc) {
	if (isset($proc["data"])) {
		$data   = $proc["data"];
		$oper   = $data["operation"];
		$tables = $data["tables"];

		$offset = -1;
		foreach ($tables as $tab => $props) {
			$offset++;
			if ( $offset < $proc['completed'] ) continue;
			$eng  = $props[0];
			$stat = $props[1];
			if (!$stat && database_safestring($tab) && database_safestring($eng)) {
				switch ($oper) {
					case "repair":
						# Actually re-write the table, which performs the dual purpose
						# of rewriting its indexes and removing any fragmentation observed
						# in the original.
						adesk_sql_query("ALTER TABLE `AEM$tab` ENGINE=`$eng`");
						break;
					case "optimize":
						adesk_sql_query("OPTIMIZE TABLE `AEM$tab`");
						break;
					default:
						break;
				}
			}

			adesk_process_update($proc["id"]);
		}
	}
}

?>
