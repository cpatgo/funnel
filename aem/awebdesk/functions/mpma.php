<?php
// mpma.php

// Provide functions that mimic sort of a mini phpMyAdmin.  This doesn't
// actually provide the interface  you would use on a webpage, but it
// does provide the means to execute queries and return their results.

require_once dirname(__FILE__) . '/manage.php';
require_once dirname(__FILE__) . '/sql.php';
require_once dirname(__FILE__) . '/php.php';

$adesk_mpma_response_text = "";

// Run a query string, and set the response text with some meaningful
// summary of what resulted.

function adesk_mpma_query($str) {
	$tstamp = adesk_microtime_get(); 
    $res = adesk_sql_query($str);
	$tstamp = adesk_microtime_get() - $tstamp; 
    
    if ($res == false)
        adesk_mpma_respond("[$tstamp] Query failed: ".mysql_error($GLOBALS['db_link']));
    elseif (!@get_resource_type($res))
        adesk_mpma_respond("[$tstamp] Query succeeded: ".mysql_affected_rows()." rows were inserted or affected.");
    else
        adesk_mpma_respond("[$tstamp] " . adesk_mpma_tablify($res));
}

// Turn a result set into an html table.

function adesk_mpma_tablify($res) {
    $first = mysql_fetch_assoc($res);

    if ($first == false)
        return "Query succeeded, but no results were returned.";

    $cnt = mysql_num_rows($res);
    $out = "Query succeeded, $cnt results were returned.";
    $out .= "<table border='1'><tr>";

    foreach (array_keys($first) as $key)
        $out .= "<th>".$key."</th>";

    $out .= "</tr><tr>";

    foreach (array_values($first) as $val)
        $out .= "<td valign='top'>".htmlentities($val)."</td>";

    $out .= "</tr>";

    while ($row = mysql_fetch_assoc($res)) {
        $out .= "<tr>";
        foreach (array_values($row) as $val)
            $out .= "<td valign='top'>".htmlentities($val)."</td>";

        $out .= "</tr>";
    }

    return $out . "</table>";
}

function adesk_mpma_respond($str) {
    $GLOBALS['adesk_mpma_response_text'] = $str;
}

function adesk_mpma_response() {
    return $GLOBALS['adesk_mpma_response_text'];
}

function adesk_mpma_tables() {
	$GLOBALS["tables"] = array();
	$GLOBALS["tdata"]  = array();

    $sql = adesk_sql_query("SHOW TABLE STATUS");
	while ($row = adesk_sql_fetch_row($sql)) {
		$GLOBALS["tables"][] = $row[0];
		$GLOBALS["tdata"][]  = $row;
	}

	$GLOBALS["ctables"] = implode(", ", $GLOBALS["tables"]);

	return $GLOBALS["tables"];
}

function adesk_mpma_analyze_files() {
	$str   = adesk_file_get(adesk_admin("functions/versioning.filechk.php"));
	$mat   = array();
	$lines = explode("\n", $str);

	foreach ($lines as $line) {
		if (preg_match('/^#(\d+) \/(\S+)$/', trim($line), $mat)) {
			$esize = $mat[1];
			$file  = $mat[2];
			$path  = adesk_base($file);

			# Below are the various checks we'll perform for each file.
			if (!file_exists($path))
				echo "<strong>Error:</strong> $file is missing.<br>\n";
			else {
				$asize = @filesize($path);

				if ($asize == 0)
					echo "<strong>Error:</strong> $file has zero bytes.<br>\n";

				if ($asize < $esize)
					echo "<strong>Error:</strong> $file is smaller than it should be.  It has <b>$asize</b> bytes, but it should have <b>$esize</b>.<br>\n";

				if ($asize > $esize)
					echo "<strong>Error:</strong> $file is larger than it should be.  It has <b>$asize</b> bytes, but it should have <b>$esize</b>.<br>\n";
			}
		}
	}

	echo "<a href='mpma.php'>Return to MPMA.</a>";
}

?>
