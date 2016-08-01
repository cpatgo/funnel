<?php

# Despite the name of the file, we handle both messages and templates.

//if (!@ini_get("zlib.output_compression")) @ob_start("ob_gzhandler");

// require main include file
require_once(dirname(__FILE__) . '/awebdeskend.inc.php');

if ( !adesk_admin_isadmin() ) {
	echo 'You are not logged in.';
	exit;
}

$which = (string)adesk_http_param("which");
$id    = (int)adesk_http_param("id");

switch ($which) {
	case "new":
		$row = adesk_sql_select_row("SELECT * FROM #message WHERE id = '$id'");
		if ($row["preview_data"] == "") {
			$row["preview_mime"] = "image/gif";
			$row["preview_data"] = @file_get_contents(adesk_admin("images/blank_message.gif"));
		}
		break;
	case "msg":
	case "tpl":
		$row = adesk_sql_select_row("SELECT * FROM #template WHERE id = '$id'");
		if ($row["preview_data"] == "") {
			$row["preview_mime"] = "image/gif";
			$row["preview_data"] = @file_get_contents(adesk_admin("images/default_message.gif"));
		}
		break;

	case "cam":
		break;
	default:
		$row = false;
		break;
}

if (!$row) {
	header("Content-Type: image/gif");
	exit;
}

$mime = $row["preview_mime"];
$data = $row["preview_data"];

header("Content-Type: $mime");
echo $data;
exit;

?>
