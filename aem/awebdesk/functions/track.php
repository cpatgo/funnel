<?php

function adesk_track_pageaccess() {
	$ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1';
	$ary = array(
		'id' => 0,
		'=tstamp' => "NOW()",
		'=ip' => "INET_ATON('$ip')",
		'sessionid' => session_id(),
		'url' => adesk_http_geturl(),
		'post' => serialize(isset($_POST) ? $_POST : array()),
	);
	adesk_sql_insert("#track_access", $ary);
}

function adesk_track_action($action, $comment, $userid = null) {
	global $admin;
	if ( is_null($userid) ) $userid = isset($admin['id']) ? $admin['id'] : 0;
	$ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1';
	$ary = array(
		'id' => 0,
		'=tstamp' => "NOW()",
		'=ip' => "INET_ATON('$ip')",
		'userid' => $userid,
		'action' => $action,
		'comment' => $comment,
	);
	adesk_sql_insert("#track_action", $ary);
}

?>