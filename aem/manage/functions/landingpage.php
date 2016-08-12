<?php
if(!session_id()) session_start();
include_once($_SERVER['DOCUMENT_ROOT'].'/aem/manage/config.inc.php');
$GLOBALS["aem_con"] = mysqli_connect(AWEBP_AUTHDB_SERVER, AWEBP_AUTHDB_USER, AWEBP_AUTHDB_PASS, AWEBP_AUTHDB_DB);

$action = $_POST['action'];
if($action === 'list_insert_post') list_insert_post();

function list_insert_post() {

	$user_id = $_SESSION['awebdesk_aweb_admin']['id'];
	$query = sprintf("INSERT INTO awebdesk_landingpage (title, type, description, user_id, list_id, page_link, date_created) VALUES ( '%s', '%s', '%s', '%d', '%d', '%s', '%s')", 
		$_POST['title'], $_POST['type'], $_POST['description'], $user_id, $_POST['list_id'], $_POST['page_link'], date('Y-m-d H:i:s'));

	mysqli_query($GLOBALS["aem_con"], $query);

	echo "Campaign successfully created.";
}
?>