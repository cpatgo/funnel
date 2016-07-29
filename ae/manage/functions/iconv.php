<?php

function iconv_process($process) {
	$offset = -1;
	foreach ($process["data"] as $table => $info) {
		$offset++;

		if ($offset < $process["completed"])
			continue;

		$cols = iconv_textcols($table);
		$ary  = array();
		$cols_commas = implode("`, `", $cols);

		adesk_sql_query("SET NAMES 'latin1'");

		$limit = $info["totalrows"] - $info["offset"];

		if (count($cols) > 0)
			$rs = adesk_sql_query($q = "SELECT id, `$cols_commas` FROM $table LIMIT $info[offset], $limit");

		adesk_sql_query("SET NAMES 'utf8'");
		
		if (count($cols) > 0) {
			while ($row = adesk_sql_fetch_assoc($rs)) {
				if (_i18n("utf-8") != "utf-8") {
					foreach ($cols as $col)
						$ary[$col] = iconv(strtoupper(_i18n("utf-8")), "UTF-8//IGNORE", $row[$col]);
				} else {
					foreach ($cols as $col)
						$ary[$col] = $row[$col];
				}

				adesk_sql_update($table, $ary, "`id` = '$row[id]'");
				$process["data"][$table]["offset"]++;
				adesk_process_setdata($process["id"], $process["data"]);
			}
		}

		$process["completed"]++;
		adesk_process_update($process["id"]);
	}
}

function iconv_textcols($table) {
	$rs  = adesk_sql_query("SHOW COLUMNS FROM $table");
	$out = array();

	while ($row = adesk_sql_fetch_assoc($rs)) {
		$type = strtoupper($row["Type"]);

		if (preg_match('/CHAR|TEXT/', $type))
			$out[] = $row["Field"];
	}

	return $out;
}

?>
