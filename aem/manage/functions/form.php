<?php

require_once awebdesk_classes("select.php");

function form_select_query(&$so) {
	if ( !adesk_admin_ismain() ) {
		$admin = adesk_admin_get();
		$uid = $admin['id'];
		if ( $admin['id'] > 1 ) {
			if ( !isset($so->permsAdded) ) {
				$so->permsAdded = 1;
				
				if($uid != 1 ) {
				$lists2 = adesk_sql_select_list("SELECT distinct listid FROM #user_p WHERE userid = $uid");
					$so->push("AND l.listid IN ('" . implode("', '", $lists2) . "')");
					
				}
				else
				{
					
					$so->push("AND l.listid IN ('" . implode("', '", $admin['lists']) . "')");
				}
			
			
			
			}
		}
	}
	return $so->query("
		SELECT
			f.*,
			COUNT(l.id) as lists
		FROM
			#form f
		LEFT JOIN
			#form_list l
		ON
			f.id = l.formid
		WHERE
			[...]
		GROUP BY
			f.id
	");
}

function form_select_row($id, $generate = null) {
	$id = intval($id);
	if ( !$id ) return false;
	$so = new adesk_Select;
	$so->push("AND f.id = '$id'");

	$r = adesk_sql_select_row(form_select_query($so));
	if ( $r ) {
		$cond = '';
		if ( !adesk_admin_ismain() ) {
			$admin = adesk_admin_get();
		$uid = $admin['id'];
		     if($uid != 1 ) {
				 
				$lists3 = adesk_sql_select_list("SELECT distinct listid FROM #user_p WHERE userid = $uid");
$cond = "AND f.listid IN ('" . implode("', '", $lists3) . "')";

                    } else {

$cond = "AND f.listid IN ('" . implode("', '", $admin['lists']) . "')";

                          }	
		
		 
		}
		$r['require_name'] = false;
		$r['lists'] = adesk_sql_select_array("SELECT l.* FROM #form_list f, #list l WHERE f.formid = '$id' AND f.listid = l.id $cond");
		$lists = array();
		foreach ( $r['lists'] as $l ) {
			$lists[] = $l['id'];
			if ( $l['require_name'] ) $r['require_name'] = true;
		}
		$r['listslist'] = implode('-', $lists);
		$r['fieldslist'] = $r['fields']; // table has fields field, gotta save it
		$r['fieldsarray'] = ( !is_null($r['fields']) && $r['fields'] ) ? adesk_sql_select_array("SELECT * FROM #list_field WHERE id IN (" . $r['fields'] . ")") : array();
		$r['fields'] = list_get_fields($lists, true);
		if ( $generate ) {
			$r['html'] = form_generate($r, 'html');
			$r['htmllink'] = adesk_site_rwlink(array('action' => 'form', 'id' => $id, 'type' => 'html'));
			$r['xml'] = form_generate($r, 'xml');
			$r['xmllink'] = adesk_site_rwlink(array('action' => 'form', 'id' => $id, 'type' => 'xml'));
		}
	}
	return $r;
}

function form_select_array($so = null, $ids = null) {
	if ($so === null || !is_object($so))
		$so = new adesk_Select;

	if ($ids !== null) {
		if ( !is_array($ids) ) $ids = explode(',', $ids);
		$tmp = array_diff(array_map("intval", $ids), array(0));
		$ids = implode("','", $tmp);
		$so->push("AND f.id IN ('$ids')");
	}
	return adesk_sql_select_array(form_select_query($so));
}

function form_select_array_paginator($id, $sort, $offset, $limit, $filter) {
	$admin = adesk_admin_get();
	$so = new adesk_Select;

	$filter = intval($filter);
	if ($filter > 0) {
		$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filter' AND userid = '$admin[id]' AND sectionid = 'form'");
		$so->push($conds);
	}

	$so->count();
	$total = (int)adesk_sql_select_one(form_select_query($so));

	switch ($sort) {
		default:
		case "01":
			$so->orderby("name"); break;
		case "01D":
			$so->orderby("name DESC"); break;
		case "02":
			$so->orderby("lists"); break;
		case "02D":
			$so->orderby("lists DESC"); break;
	}

	if ( (int)$limit == 0 ) $limit = 999999999;
	$limit  = (int)$limit;
	$offset = (int)$offset;
	$so->limit("$offset, $limit");
	$rows = form_select_array($so);

	return array(
		"paginator"   => $id,
		"offset"      => $offset,
		"limit"       => $limit,
		"total"       => $total,
		"cnt"         => count($rows),
		"rows"        => $rows,
	);
}

function form_select_list($ids) {

	if ( !$ids ) return $ids;

	if ($ids == "all") {
		$ids = adesk_sql_select_list("SELECT id FROM #form");
	}
	else {
		$ids = array_diff(array_map('intval', explode(',', $ids)), array(0));
	}

	$r = array();

	foreach ( $ids as $id ) {
		if ( $v = form_select_row($id) ) $r[] = $v;
	}

	return $r;
}

function form_filter_post() {
	$whitelist = array("name");

	$admin = adesk_admin_get();
		$uid = $admin['id'];

	$ary = array(
		"userid" => $uid,//$GLOBALS['admin']['id'],
		"sectionid" => "form",
		"conds" => "",
		"=tstamp" => "NOW()",
	);

	if (isset($_POST["qsearch"]) && !isset($_POST["content"])) {
		$_POST["content"] = $_POST["qsearch"];
	}

	if (isset($_POST["content"]) and $_POST["content"] != "") {
		$content = adesk_sql_escape($_POST["content"], true);
		$conds = array();

		if (!isset($_POST["section"]) || !is_array($_POST["section"]))
			$_POST["section"] = $whitelist;

		foreach ($_POST["section"] as $sect) {
			if (!in_array($sect, $whitelist))
				continue;
			$conds[] = "$sect LIKE '%$content%'";
		}

		$conds = implode(" OR ", $conds);
		$ary["conds"] = "AND ($conds) ";
	}

	if ( isset($_POST['listid']) ) {
		if ( defined('AWEBVIEW') ) {
			$_SESSION['nlp'] = $_POST['listid'];
		} else {
			$_SESSION['nla'] = $_POST['listid'];
		}
	}
	$nl = null;
	if ( isset($_SESSION['nlp']) and defined('AWEBVIEW') ) {
		$nl = $_SESSION['nlp'];
	} elseif ( isset($_SESSION['nla']) ) {
		$nl = $_SESSION['nla'];
	}
	if ( $nl ) {
		if ( is_array($nl) ) {
			if ( count($nl) > 0 ) {
				$ids = implode("', '", array_map('intval', $nl));
				$ary['conds'] .= "AND l.listid IN ('$ids') ";
			} else {
				if ( defined('AWEBVIEW') ) {
					unset($_SESSION['nlp']);
				} else {
					unset($_SESSION['nla']);
				}
			}
		} else {
			$listid = (int)$nl;
			if ( $listid > 0 ) {
				$ary['conds'] .= "AND l.listid = '$listid' ";
			} else {
				if ( defined('AWEBVIEW') ) {
					unset($_SESSION['nlp']);
				} else {
					unset($_SESSION['nla']);
				}
			}
		}
	}
	if ( $ary['conds'] == '' ) return array('filterid' => 0);

	$conds_esc = adesk_sql_escape($ary["conds"]);
	$filterid = adesk_sql_select_one("
		SELECT
			id
		FROM
			#section_filter
		WHERE
			userid = '$ary[userid]'
		AND
			sectionid = 'form'
		AND
			conds = '$conds_esc'
	");

	if (intval($filterid) > 0)
		return array("filterid" => $filterid);
	adesk_sql_insert("#section_filter", $ary);
	return array("filterid" => adesk_sql_insert_id());
}

function form_insert_post() {
	// find parents
	if ( isset($_POST['p']) and is_array($_POST['p']) and count($_POST['p']) > 0 ) {
		$lists = array_map('intval', $_POST['p']);
	} else {
		return adesk_ajax_api_result(false, _a("You did not select any lists."));
	}

	$ary = form_prepare_post(0);

	// perform checks
	if ( $ary['name'] == '' ) {
		return adesk_ajax_api_result(false, _a("Subscription Form Name not entered. Please name this Subscription Form."));
	}

	$sql = adesk_sql_insert("#form", $ary);
	if ( !$sql ) {
		return adesk_ajax_api_result(false, _a("Subscription Form could not be added."));
	}
	$id = adesk_sql_insert_id();

	// list relations
	foreach ( $lists as $l ) {
		if ( $l > 0 ) adesk_sql_insert('#form_list', array('id' => 0, 'formid' => $id, 'listid' => $l));
	}

	return adesk_ajax_api_added(_a("Subscription Form"), array('id' => $id));
}

function form_update_post() {

	if ( isset($_POST['p']) and is_array($_POST['p']) and count($_POST['p']) > 0 ) {
		$lists = array_map('intval', $_POST['p']);
	} else {
		return adesk_ajax_api_result(false, _a("You did not select any lists."));
	}

	$id = intval($_POST["id"]);

	$ary = form_prepare_post($id);

	// perform checks
	if ( $ary['name'] == '' ) {
		return adesk_ajax_api_result(false, _a("Subscription Form Name not entered. Please name this Subscription Form."));
	}

	$sql = adesk_sql_update("#form", $ary, "id = '$id'");
	if ( !$sql ) {
		return adesk_ajax_api_result(false, _a("Subscription Form could not be updated."));
	}

	// list relations
	$cond = implode("', '", $lists);
	$admincond = '';
	if ( !adesk_admin_ismain() ) {
		$admin = adesk_admin_get();
		$uid = $admin['id'];
		if($uid != 1 ) {
$lists4 = adesk_sql_select_list("SELECT distinct listid FROM #user_p WHERE userid = $uid");
$admincond = "AND listid IN ('" . implode("', '", $lists4) . "')";
} else {

$admincond = "AND listid IN ('" . implode("', '", $admin['lists']) . "')";

}
		
	
	
	
	}

	adesk_sql_delete('#form_list', "formid = '$id' AND listid NOT IN ('$cond') $admincond");

	foreach ( $lists as $l ) {
		if ( $l > 0 ) {
			// Make sure the row relationship doesn't already exist in the DB.
			if ( !adesk_sql_select_one('=COUNT(*)', '#form_list', "formid = '$id' AND listid = '$l'") ) {
				adesk_sql_insert('#form_list', array('id' => 0, 'formid' => $id, 'listid' => $l));
			}
		}
	}

	return adesk_ajax_api_updated(_a("Subscription Form"), array('id' => $id));
}

function form_update_charset($id, $charset) {
	$id = (int)$id;
	$up = array("charset" => $charset);

	adesk_sql_update("#form", $up, "id = '$id'");
}

function form_delete($id) {
	$id = intval($id);
	$admincond = '';
	if ( !adesk_admin_ismain() ) {
		$admin = adesk_admin_get();
	 	
		
		
		
		
		
		$uid = $admin['id'];
		if($uid != 1 ) {
$lists5 = adesk_sql_select_list("SELECT distinct listid FROM #user_p WHERE userid = $uid");
$admincond = "AND listid IN ('" . implode("', '", $lists5) . "')";
} else {

$admincond = "AND listid IN ('" . implode("', '", $admin['lists']) . "')";

}
		
		
		
		
		
	}
	adesk_sql_delete('#form_list', "formid = '$id' $admincond");
	if ( adesk_sql_select_one('=COUNT(*)', '#form_list', "formid = '$id'") == 0 ) {
		adesk_sql_delete('#form', "id = '$id'");
	}
	return adesk_ajax_api_deleted(_a("Subscription Form"));
}

function form_delete_multi($ids, $filter = 0) {
	if ( $ids == '_all' ) {
		$tmp = array();
		$so = new adesk_Select();
		$filter = intval($filter);
		if ($filter > 0) {
			$admin = adesk_admin_get();
			$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filter' AND userid = '$admin[id]' AND sectionid = 'form'");
			$so->push($conds);
		}
		$all = form_select_array($so);
		foreach ( $all as $v ) {
			$tmp[] = $v['id'];
		}
	} else {
		$tmp = array_map("intval", explode(",", $ids));
	}
	foreach ( $tmp as $id ) {
		$r = form_delete($id);
	}
	return $r;
}


function form_prepare_post($id) {

	$fieldsV = implode(',', (array)adesk_http_param('fields'));
	if ( $fieldsV == '' ) {
		$fieldsK = '=fields';
		$fieldsV = 'NULL';
	} else {
		$fieldsK = 'fields';
	}

	$r = array(
		'id' => $id,
		'name' => trim((string)adesk_http_param('name')),
		'type' => trim((string)adesk_http_param('type')),
		'allowselection' => (int)(bool)adesk_http_param('allowselection'),
		'emailconfirmations' => (int)(bool)adesk_http_param('emailconfirmations'),
		'ask4fname' => (int)adesk_http_param_exists('ask4fname'),
		'ask4lname' => (int)adesk_http_param_exists('ask4lname'),
		$fieldsK => $fieldsV,
		'optinoptout' => (int)adesk_http_param('optid'),
		'captcha' => (int)adesk_http_param_exists('captcha'),
	);

	$f = array("sub1","sub2","sub3","sub4","unsub1","unsub2","unsub3","unsub4","up1","up2");

	foreach ($f as $v) {
		// Select list values
		$r[$v . '_type'] = trim((string)adesk_http_param($v));

		// Decide which form element to extract content from
		// "redirect" uses the textbox. "custom" uses the textarea. "default" should clear out any values
		if ( (string)adesk_http_param($v) == 'redirect' ) {
			// Use the textbox for "redirect"
			// Also, don't allow just "http://" to be entered for "redirect"
			$r[$v . '_value'] = ( (string)adesk_http_param($v . '_redirect') == 'http://' ) ? '' : (string)adesk_http_param($v . '_redirect');
		}
		else if ( (string)adesk_http_param($v) == 'custom' ) {
			// Use the textarea for "custom"
			$r[$v . '_value'] = (string)adesk_http_param($v . 'Editor');
		}
		else {
			// Set the value to nothing for "default"
			$r[$v . '_value'] = "";
		}
	}

	if ( $r['type'] == 'unsubscribe' ) {
		$r['ask4fname'] = $r['ask4lname'] = 0;
	}

	return $r;
}

function form_generate($form, $type) {
	require_once(awebdesk_functions('smarty.php'));
	if ( !in_array($type, array('html', 'xml'/*, 'link', 'popup'*/)) ) $type = 'html';

	if ( $form['type'] == 'unsubscribe' ) {
		$form['ask4fname'] = $form['ask4lname'] = 0;
	}

	// prepare for xml
	if ( $type == 'xml' ) {
		//$form[''] = (string)adesk_http_param('');
		$form['background_color'] = 'FFFFFF';
		$form['font_size'] = 12;
		$form['font_family'] = 'Arial';
		$form['font_color'] = '000000';
		$form['input_color'] = '000000';
/*
		if ( $type == 'xml' ) {
			//$form[''] = (string)adesk_http_param('');
			$form['background_color'] = (string)adesk_http_param('background_color');
			$form['font_size'] = (int)adesk_http_param('font_size');
			$form['font_family'] = (string)adesk_http_param('font_family');
			$form['font_color'] = (string)adesk_http_param('font_color');
			$form['input_color'] = (string)adesk_http_param('input_color');
		}
*/
		$form = form_generate_positions($form);
	}


	$smarty = new adesk_Smarty('admin');
	$site = adesk_site_get();
	if(($site["acpow"])){ $site["acpow"] = base64_decode($site["acpow"]); } else { $site["acpow"] = 'Email Marketing'; }
	$smarty->assign('site', $site);
	$smarty->assign('admin', adesk_admin_get());
	$smarty->assign('form', $form);
	$smarty->assign('type', $type);
	$template = 'form.code.' . $type . '.htm';
	return $smarty->fetch($template);
}

function form_generate_positions($form) {
	$next = 110;
	$current = 110;
	foreach ( $form['fields'] as $k => $v ) {
		switch ($v['element']) {
			case 'text':
				$form['fields'][$k]['ypos'] = $current;
				$next += 20;
				break;
			case 'textarea':
				$form['fields'][$k]['ypos'] = $current;
				$textarea_rows_cols = explode("||", $v["onfocus"]);
				// check to make sure there are TWO values resulting from the explode, otherwise LIST() won't work below
				$textarea_rows_cols = ( $textarea_rows_cols && count($textarea_rows_cols) > 1 ) ? $textarea_rows_cols : array("", "");
				list($form['fields'][$k]["rows"], $form['fields'][$k]["cols"]) = $textarea_rows_cols;
				$next += ($form['fields'][$k]['rows'] * 10);
				break;
			case 'checkbox':
				$form['fields'][$k]['ypos'] = $current;
				$next += 20;
				break;
			case 'select':
				$form['fields'][$k]['ypos'] = $current;
				$next += 20;
				break;
			case 'radio':
				$first = true;
				foreach ( $v['options'] as $k2 => $item ) {
					/*
					$form['fields'][$k]['options'] = array(
						'value' => $item["value"],
						'name' => $item["name"],
						'title' => ( $first ? $v['title'] : '' ),
						'ypos' => $next,
						'checked' => $item["value"] == $v['val']
					);
					*/
					if ( $first ) { // only assign the title to the first one
						$form['fields'][$k]['options'][$k2]['title'] = $v['title'];
					} else {
						$form['fields'][$k]['options'][$k2]['title'] = '';
					}
					$form['fields'][$k]['options'][$k2]['ypos'] = $next;
					$form['fields'][$k]['options'][$k2]['checked'] = $item["value"] == $v['val'];
					$next += 20; // add height for the options
					$first = false;
				}
				break;
			case 'multicheckbox':
			case 'multiselect':
				$form['fields'][$k]['ypos'] = $current;
				if ( $form['fields'][$k]['element'] == "multiselect" ) {
					$next += 80;
					$form['fields'][$k]["_size"] = 80;
				} else {
					$next += 20 * count($v['options']);
					$form['fields'][$k]["_size"] = 20 * count($v['options']);
					$form['fields'][$k]['_height'] = 20 * $form['fields'][$k]['_size'];
				}

				$ary  = array_map('trim', explode("||", str_replace("\n", "||", $v['expl'])));
				$sel  = array();
				for ( $j = 0; $j < count($ary); $j += 2 ) {
					if ( in_array($ary[$j+1], $sel) )
						$form['fields'][$k]['_selected'] = "true";
					else
						$form['fields'][$k]['_selected'] = "false";
				}
				break;
			default:
				break;
		}
		if ( $v['element'] != 'hidden' ) {
			$next += 30;
			$current = $next; // prepare current for the next iteration
		}
	}
	if ( $form['allowselection'] ) {
		$first = true;
		foreach ( $form['lists'] as $k => $v ) {
			if ( $first ) { // only assign the title to the first one
				$form['lists'][$k]['header'] = _a("Please Choose a List");
			} else {
				$form['lists'][$k]['header'] = '';
			}
			$form['lists'][$k]['ypos'] = $current;
			$current += 20; // add height for the options
			$first = false;
		}
		$current += 16; // give some extra room before showing subscribe radios
	}
	$form['subscribe_ypos'] = $current;
	$form['unsubscribe_ypos'] = $form['subscribe_ypos'] + 20;
	$form['submit_ypos'] = $form['unsubscribe_ypos'] + 40;
	return $form;
}

function form_list_change($formID, $lists, $global, $subscriberid=0) {
	if($subscriberid != '0') {
		$subscriber = subscriber_exists($subscriberid, $lists, "hash");
		$subscriberid = $subscriber['id'];
	}
	// get stuff that's always used (custom fields)
	$r = list_field_update($subscriberid, $lists, $global);
	// and also get lists info
	$r['lists'] = list_select_array(null, explode('-', $lists), 'optinout');
	// do something with list info here?
	return $r;
}

function form_redirect($form, $action, $codes = null, $lists = null, $forceInternal = false, $extra = array()) {
	$field = 'sub4';
	//$url = 'index.php?action=subscribe&mode=subscribe_error';
	$arr = array('action' => 'subscribe', 'mode' => 'subscribe_error');

	$site = adesk_site_get();
	$good = false;
	switch ( $action ) {
		case 'account':
			$field = 'up1';
			//$url = "index.php?action=account&mode=confirm&p=" . $form["id"] . /*"&lists=" . $lists .*/ "&codes=" . $codes;
			$arr = array('action' => 'account', 'mode' => 'confirm', 'p' => $form['id'], 'codes' => $codes);
			break;

		case 'account_update':
			$field = 'up2';
			//$url = "index.php?action=account_update&mode=update&p=" . $form["id"] . /*"&lists=" . $lists .*/ "&codes=" . $codes;
			$arr = array('action' => 'account_update', 'mode' => 'update', 'p' => $form['id'], 'codes' => $codes);
			break;

		case 'add':
		case 'subscribe':
			$goodcodes = array('7'/* list good codes here*/);
			$confcodes = array('6'/* list conf codes here*/);
			$realcodes = explode(',', $codes);
			$good = count(array_diff($realcodes, array_merge($goodcodes, $confcodes))) == 0;
			$conf = count(array_diff($realcodes, $confcodes)) < count($realcodes);
			if ( $good ) {
				if ( $conf ) {
					$field = 'sub2';
					//$url = 'index.php?action=subscribe&mode=subscribe_confirm&lists=' . $lists . '&codes=' . $codes;
					$arr = array('action' => 'subscribe', 'mode' => 'subscribe_confirm', 'lists' => $lists, 'codes' => $codes);
				} else {
					$field = 'sub1';
					//$url = 'index.php?action=subscribe&mode=subscribe_success&lists=' . $lists . '&codes=' . $codes;
					$arr = array('action' => 'subscribe', 'mode' => 'subscribe_success', 'lists' => $lists, 'codes' => $codes);
				}
			} else {
				$field = 'sub4';
				//$url = 'index.php?action=subscribe&mode=subscribe_error&lists=' . $lists . '&codes=' . $codes;
				$arr = array('action' => 'subscribe', 'mode' => 'subscribe_error', 'lists' => $lists, 'codes' => $codes);
			}
			break;

		case 'csub':
			$goodcodes = array('13'/* list good codes here*/);
			$realcodes = explode(',', $codes);
			$good = count(array_diff($realcodes, $goodcodes)) == 0;
			if ( $good ) {
				$field = 'sub3';
				//$url = 'index.php?action=subscribe&mode=subscribe_success&lists=' . $lists . '&codes=' . $codes;
				$arr = array('action' => 'subscribe', 'mode' => 'subscribe_success', 'lists' => $lists, 'codes' => $codes);
			} else {
				$field = 'sub4';
				//$url = 'index.php?action=subscribe&mode=subscribe_error&lists=' . $lists . '&codes=' . $codes;
				$arr = array('action' => 'subscribe', 'mode' => 'subscribe_error', 'lists' => $lists, 'codes' => $codes);
			}
			break;

		case 'unsub2':
		case 'unsubscribe':
		case 'unsubreason':
			$goodcodes = array('11'/* list good codes here*/);
			$confcodes = array('10'/* list conf codes here*/);
			$realcodes = explode(',', $codes);
			$good = count(array_diff($realcodes, array_merge($goodcodes, $confcodes))) == 0;
			$conf = count(array_diff($realcodes, $confcodes)) < count($realcodes);
			if ( $good ) {
				if ( $conf ) {
					$field = 'unsub2';
					//$url = 'index.php?action=unsubscribe&mode=unsubscribe_confirm&lists=' . $lists . '&codes=' . $codes;
					$arr = array('action' => 'unsubscribe', 'mode' => 'unsubscribe_confirm', 'lists' => $lists, 'codes' => $codes);
				} else {
					$field = 'unsub1';
					//$url = 'index.php?action=unsubscribe&mode=unsubscribe_success&lists=' . $lists . '&codes=' . $codes;
					$arr = array('action' => 'unsubscribe', 'mode' => 'unsubscribe_success', 'lists' => $lists, 'codes' => $codes);
				}
			} else {
				$field = 'unsub4';
				//$url = 'index.php?action=unsubscribe&mode=unsubscribe_error&lists=' . $lists . '&codes=' . $codes;
				$arr = array('action' => 'unsubscribe', 'mode' => 'unsubscribe_error', 'lists' => $lists, 'codes' => $codes);
			}
			break;

		case 'cunsub':
			$goodcodes = array('14'/* list good codes here*/);
			$realcodes = explode(',', $codes);
			$good = count(array_diff($realcodes, $goodcodes)) == 0;
			if ( $good ) {
				$field = 'unsub3';
				//$url = 'index.php?action=unsubscribe&mode=unsubscribe_success&lists=' . $lists . '&codes=' . $codes;
				$arr = array('action' => 'unsubscribe', 'mode' => 'unsubscribe_success', 'lists' => $lists, 'codes' => $codes);
			} else {
				$field = 'unsub4';
				//$url = 'index.php?action=unsubscribe&mode=unsubscribe_error&lists=' . $lists . '&codes=' . $codes;
				$arr = array('action' => 'unsubscribe', 'mode' => 'unsubscribe_error', 'lists' => $lists, 'codes' => $codes);
			}
			break;
	}

	if (count($extra) > 0)
		$arr = array_merge($arr, $extra);

	// make $r out of $field and $url
	if ( !isset($form[$field . '_type']) ) return adesk_site_rwlink($arr);

	// if forcing internal link
	// Opt-in/Out set to confirm
	if ( $forceInternal ) $form[$field . '_type'] = 'custom';

	switch ( $form[$field . '_type'] ) {
		case 'redirect':
			// get the redirect url from form value
			$r = $form[$field . '_value'];
			if ( !adesk_str_is_url($r) ) $r = adesk_site_rwlink($arr);
			// append URL params ONLY IF THERE WAS AN ERROR
			$r = adesk_http_query_prefix($r, 'lists=' . $lists . '&codes=' . $codes);
			break;

		case 'custom':
			// force formid so we can find the custom message
			$url = adesk_site_rwlink($arr);
			if ( !adesk_str_instr('&p=', $url) and !adesk_str_instr('&amp;p=', $url) ) {
				$url .= ( adesk_str_instr('?', $url) ? '&' : '?' ) . 'p=' . $form['id'];
			}
			$r = $url;
			break;

		case 'default':
		default:

			// use our default internal link
			$r = adesk_site_rwlink($arr);
			break;
	}
	return $r;
}

// load API example doc file content
function form_list_other_api_load($filename) {
	// just load the file and return the content
	$file = adesk_file_get( adesk_base('docs/api-examples/' . $filename) );
	$file = str_replace('YOUR_USERNAME', $GLOBALS['admin']['username'], $file);
	$file = str_replace('http://yourdomain.com/path/to/AEM', $GLOBALS['site']['p_link'], $file);
	$file = str_replace('AwebDesk Email Marketing', $GLOBALS['admin']['brand_site_name'], $file);
	//dbg($file);
	return $file;
}

?>