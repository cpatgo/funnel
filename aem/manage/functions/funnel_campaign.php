<?php
if(!session_id()) session_start();
include_once($_SERVER['DOCUMENT_ROOT'].'/aem/manage/config.inc.php');
$GLOBALS["aem_con"] = mysqli_connect(AWEBP_AUTHDB_SERVER, AWEBP_AUTHDB_USER, AWEBP_AUTHDB_PASS, AWEBP_AUTHDB_DB);

$action = $_POST['action'];
if($action === 'list_insert_post') list_insert_post();
if($action === 'save_list_to_session') save_list_to_session();
if($action === 'destroy_list_session') destroy_list_session();
if($action === 'sales_insert_post') sales_insert_post();

function list_insert_post() {
	parse_str($_POST['fields'], $fields);
	$list_id = $_SESSION['selected_list_id'];
	$user_id = $_SESSION['awebdesk_aweb_admin']['id'];
	$query = sprintf("INSERT INTO awebdesk_funnel_campaign (title, type, description, user_id, list_id, page_link, date_created) VALUES ( '%s', '%s', '%s', '%d', '%d', '%s', '%s')", 
		$fields['landing-page-name'], $fields['landing-page-type'], $fields['landing-page-description'], $user_id, $list_id, $fields['landing-page-url'], date('Y-m-d H:i:s'));

	mysqli_query($GLOBALS["aem_con"], $query);

	die(json_encode(array('type' => 'success', 'link' => sprintf('%s',$fields['landing-page-url']))));
}

function sales_insert_post() {
	parse_str($_POST['fields'], $fields);
	$user_id = $_SESSION['awebdesk_aweb_admin']['id'];
	$query = sprintf("INSERT INTO awebdesk_funnel_campaign (title, type, description, user_id, list_id, page_link, date_created) VALUES ( '%s', '%s', '%s', '%d', '%d', '%s', '%s')", 
		$fields['landing-page-name'], $fields['landing-page-type'], $fields['landing-page-description'], $user_id, 0, $fields['landing-page-url'], date('Y-m-d H:i:s'));

	mysqli_query($GLOBALS["aem_con"], $query);

	die(json_encode(array('type' => 'success', 'link' => sprintf('%s',$fields['landing-page-url']))));
}

function save_list_to_session() {
	$_SESSION['selected_list_id'] = $_POST['list_id'];
}

function destroy_list_session() {
	unset($_SESSION['selected_list_id']);
}
?>