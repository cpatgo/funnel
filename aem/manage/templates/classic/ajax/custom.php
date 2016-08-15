<?php
if(!session_id()) session_start();
include_once($_SERVER['DOCUMENT_ROOT'].'/aem/manage/config.inc.php');
$GLOBALS["aem_con"] = mysqli_connect(AWEBP_AUTHDB_SERVER, AWEBP_AUTHDB_USER, AWEBP_AUTHDB_PASS, AWEBP_AUTHDB_DB);

$user_id = $_SESSION['awebdesk_aweb_admin']['id'];
$action = $_POST['action'];
if($action === 'get_lists') get_lists($user_id);

function get_lists() {
	$query = sprintf("SELECT * FROM awebdesk_list WHERE userid = %d", $user_id);
	$lists = aem_select($query);
	die(json_encode(array('type' => 'success', 'data' => $lists)));
}

function aem_select($query){
    $data = array();
    $result = $GLOBALS["aem_con"]->query($query);
    if(!$result) return aem_response(false, $GLOBALS["aem_con"]->error);
    if($result->num_rows < 1) return array();
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    return $data;
}

function aem_insert($query){
    if(!$GLOBALS["aem_con"]->query($query)) return aem_response(false, $GLOBALS["aem_con"]->error);
    return aem_response(true, $GLOBALS["aem_con"]->insert_id);
}

function aem_update($query){
    if(!$GLOBALS["aem_con"]->query($query)) return aem_response(false, $GLOBALS["aem_con"]->error);
    return aem_response(true, true);
}

function aem_delete($query){
    if(!$GLOBALS["aem_con"]->query($query)) return aem_response(false, $GLOBALS["aem_con"]->error);
    return aem_response(true, true);
}

function aem_response($type, $message)
{
    $type = (!$type) ? false : true;
    return array('type' => $type, 'message' => $message);
}
?>