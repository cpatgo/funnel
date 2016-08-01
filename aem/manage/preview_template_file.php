<?php

# Despite the name of the file, we handle both messages and templates.

//if (!@ini_get("zlib.output_compression")) @ob_start("ob_gzhandler");

// require main include file
require_once(dirname(__FILE__) . '/awebdeskend.inc.php');

if ( !adesk_admin_isadmin() ) {
	echo 'You are not logged in.';
	exit;
}

$name = adesk_http_param("name");
$expl = explode(".", $name);

if (strpos($name, "..") !== false)
	exit;

if (count($expl) < 2)
	exit;

switch (strtolower($expl[1])) {
	case "png":
		$mime = "image/png";
		break;

	case "gif":
		$mime = "image/gif";
		break;

	case "jpg":
	case "jpeg":
		$mime = "image/jpeg";
		break;

	default:
		exit;
}

if ($GLOBALS["_hosted_account"]) {
	$data = @file_get_contents("/accounts/" . $GLOBALS["_hosted_account"] . "/cache/" . $expl[0] . "." . $expl[1]);
} else {
	$data = @file_get_contents(adesk_base("cache/") . $expl[0] . "." . $expl[1]);
}

header("Content-Type: $mime");
echo $data;
exit;

?>
