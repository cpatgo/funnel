<?php
if(!session_id()) session_start();
include_once($_SERVER['DOCUMENT_ROOT'].'/aem/manage/config.inc.php');
$GLOBALS["aem_con"] = mysqli_connect(AWEBP_AUTHDB_SERVER, AWEBP_AUTHDB_USER, AWEBP_AUTHDB_PASS, AWEBP_AUTHDB_DB);

$user_id = $_SESSION['awebdesk_aweb_admin']['id'];
$action = $_POST['action'];
if($action === 'get_lists') get_lists($user_id);
if($action === 'get_forms') get_forms($user_id);
if($action === 'create_landing_page') create_landing_page($user_id);
if($action === 'get_dfy_template_by_id') get_dfy_template_by_id($template_id);

function create_landing_page($user_id) {
    include_once($_SERVER['DOCUMENT_ROOT'].'/glc/config.php');
    $content = $_POST['landing_page_html'];
    $form = $_POST['form'];
    $path = $_SERVER['DOCUMENT_ROOT'].'/builder/elements';
    $filename = "preview_".generateRandomString(20).".html";

    $content = str_replace('<div id="user_form_div"></div>', $form, $content);
    file_put_contents(sprintf('%s/%s', $path, $filename), $content);

    if(file_put_contents(sprintf('%s/%s', $path, $filename), $content) != false):
        die(json_encode(array('type' => 'success', 'message' => sprintf('%s/builder/elements/%s', GLC_URL, $filename))));
    else:
        die(json_encode(array('type' => 'success', 'message' => 'Cannot create landing page file.')));
    endif;
}

function get_lists($user_id) {
	$query = sprintf("SELECT id, name FROM awebdesk_list WHERE userid = %d ORDER BY name", $user_id);
	$lists = aem_select($query);
	die(json_encode(array('type' => 'success', 'data' => $lists)));
}

function get_forms($user_id) {
	$query = sprintf("SELECT DISTINCT(af.id), af.name
				FROM awebdesk_form af
				INNER JOIN awebdesk_form_list afl
				ON af.id = afl.formid
				INNER JOIN awebdesk_list al
				ON afl.listid = al.id
				WHERE al.userid = %d ORDER BY af.name", $user_id);
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

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function get_dfy_template_by_id($template_id) {
  $query = sprintf("SELECT * FROM templates WHERE template_id = %d ORDER BY name", $template_id);
  $lists = aem_select($query);
  die(json_encode(array('type' => 'success', 'data' => $lists)));
}
