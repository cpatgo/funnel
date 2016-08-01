<?php

function smarty_function_adesk_upload($params, &$smarty) {
	// required params
	if ( !isset($params['id']) ) return '';
	if ( !isset($params['action']) ) return '';
	// additional params
	if ( !isset($params['name']) ) $params['name'] = $params['id'];
	if ( !isset($params['files']) or !is_array($params['files']) ) $params['files'] = array();
	if ( !isset($params['uploader']) ) $params['uploader'] = 'upload.php';
	if ( !isset($params['relid']) ) $params['relid'] = 0;
	if ( !isset($params['limit']) ) $params['limit'] = 0;
	$r  = '<div id="' . $params['id'] . '_holder" class="adesk_upload_box">';
	//$r .= '<input id="' . $params['id'] . '_hidden" name="' . $params['name'] . '" type="hidden" value="' . $params['value'] . '" />';
	$r .= '<div id="' . $params['id'] . '_list" class="adesk_upload_list">';
	foreach ( $params['files'] as $k => $v ) {
		$r .= '<div id="upload_check_holder_' . $k . '" class="adesk_upload_list_item">';
		$r .= '<input id="upload_check_' . $k . '" name="' . $params['name'] . '[]" type="checkbox" value="' . $k . '" checked="checked" onchange="adesk_form_upload_remove(this, \'' . $params['action'] . '_remove\');" />';
		$r .= '<spanid="upload_label_' . $k . '" class="filename">' . $v['name'] . '</span> - <span class="filesize">' . $v['humansize'] . '</span>';
		$r .= '</div>';
	}
	$r .= '</div>';
	$r .= '<iframe id="' . $params['id'] . '_iframe" class="adesk_upload_frame" src="' . $params['uploader'] . '?action=' . $params['action'] . '&id=' . $params['id'] . '&relid=' . $params['relid'] . '&limit=' . $params['limit'] . '&name=' . $params['name'] . '" frameborder="0"></iframe>';
	$r .= '</div>';
	return $r;
}

?>