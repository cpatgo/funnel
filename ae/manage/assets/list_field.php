<?php

require_once awebdesk('assets/custom_field.php');
require_once awebdesk_functions('manage.php');
require_once awebdesk_functions('ajax.php');

class list_field_assets extends Custom_Field_assets {
	var $pageTitle		= '';			# Page title
	var $sideTemplate	= '';			# Side content template

    function list_field_assets() {
        $this->Custom_Field_assets();
		$this->pageTitle      = _a("Subscriber Fields");
		$this->title          = _a("Subscriber Fields");
		//$this->sideTemplate   = 'side.list.htm';
        $this->infoTitle      = _a("Lists");
		$this->mirroring      = false;
		$this->inlist         = true;
		$this->perstag        = true;
		$this->admin          = $GLOBALS["admin"];

		# Set up custom fields in/out character sets.
		adesk_custom_fields_charset(_i18n("utf-8"), "utf-8");
    }

	function process(&$smarty) {
		if (!permission("pg_subscriber_fields")) {
			adesk_smarty_noaccess($smarty, $this);
			return;
		}

		parent::process($smarty);
		$smarty->assign("side_content_template", "side.list.htm");
	}

    function view(&$smarty) {
        $admin = adesk_admin_get();

        if (!$admin['pg_list_edit'])
            return false;

        $listid = (int)adesk_http_param('listid');
		$relid  = (int)adesk_http_param("relid");

        $smarty->assign('custom_row_info', 'list_field_row.inc.htm');
        $smarty->assign('custom_content_include', 'list_field_list.inc.htm');
        $smarty->assign('custom_update_order', 'list_field_list.inc.js');
        $smarty->assign('fields', $this->doSelect('list', $listid));
        //$smarty->assign('listsList', $this->doSelect('list_mirrors'));
        $smarty->assign('sorting', $listid > 0);
        $smarty->assign('listid', $listid);
		$smarty->assign("relid", $relid);
 		$this->setTemplateData($smarty, $listid);
    }

    function add(&$smarty) {
        if ($type = (int)adesk_http_param('type'))
            $smarty->assign('ftype', $type);
        $relid = (int)adesk_http_param('relid');

        $smarty->assign('back_href', 'desk.php?action=list_field' . ( $relid > 0 ? "&id=$relid" : '' ));
        $smarty->assign('isstrio', 1); // use bubble_content

        $smarty->assign('custom_field_form', 'list_field_form.inc.js');

        $smarty->assign('mirror_list', $this->doSelect('list_mirrors', $relid));
        //$smarty->assign('custom_field_include', 'list_field_form.inc.htm');
 		$this->setTemplateData($smarty, $relid);
    }

    function edit(&$smarty) {
        if ($type = (int)adesk_http_param('type'))
            $smarty->assign('ftype', $type);
        $id = (int)adesk_http_param('id');
        $relid = (int)adesk_http_param('relid');
        $mirrors = array();
        if ( $id > 0 ) {
            $field = $this->doSelect('one', $id);
            $rels = $this->doSelect('rel', $id);
            $field['relid'] = $relid;// current($rels);
            $mirrors = $this->doSelect('list_mirrors', $rels);
            $this->breakup_expl($field, $field['expl']);

			if ($field["type"] == 2)
				$this->get_rows_cols($smarty, $field["onfocus"]);

            $smarty->assign('field', $field);
            $smarty->assign('ftype', $field['type']);
        }
        $smarty->assign('isstrio', 1); // use bubble_content

        $smarty->assign('back_href', 'desk.php?action=list_field&relid=' . $relid);

        $smarty->assign('custom_field_form', 'list_field_form.inc.js');

        $smarty->assign('mirror_list', $mirrors);
        //$smarty->assign('custom_field_include', 'list_field_form.inc.htm');
 		$this->setTemplateData($smarty, $id);
    }

    function update(&$smarty) {
    	if ( !is_array(adesk_http_param('mirror')) ) return $this->buildResultMessage($smarty, false, _a('No Lists are selected.'));
		if ($_POST["relid"] == 0 || count(array_intersect(adesk_http_param('mirror'), $this->admin["lists"])) > 0) {
			$r = $this->doUpdate($smarty, $this->ary, $_POST["id"]);
			if ( $r ) $_POST["id"] = $_POST["relid"];
			return $r;
		} else {
	    	return $this->buildResultMessage($smarty, false, _a('Selected List not found.'));
		}
    }

    function insert(&$smarty) {
    	if ( !is_array(adesk_http_param('mirror')) ) return $this->buildResultMessage($smarty, false, _a('No Lists are selected.'));
		if ($_POST["relid"] == 0 || in_array($_POST["relid"], $this->admin["lists"])) {
			$r = $this->doInsert($smarty, $this->ary);
			if ( $r ) $_POST["id"] = $_POST["relid"];
			return $r;
		} else {
	    	return $this->buildResultMessage($smarty, false, _a('Selected List not found.'));
		}
    }

    function delete(&$smarty) {
        if (adesk_http_param('id') && (in_array(adesk_http_param("relid"), $this->admin["lists"]) || adesk_http_param("relid") === "0")) {
            $r = $this->doDelete(adesk_http_param('id'));
            if ( $r ) $_POST["id"] = adesk_http_param('relid');
            return $r;
        }

    	return $this->buildResultMessage($smarty, false, _a('Custom Field not found.'));
    }




	function doSelect($type, $id = 0) {
		$admin   = adesk_admin_get();
	        $uid = $admin['id'];
		$listslist = $this->admin["lists"];
		if ( adesk_admin_ismaingroup() ) $listslist[0] = 0;
	if($uid != 1 ){
	$lists = adesk_sql_select_list("SELECT distinct listid FROM #user_p WHERE userid = $uid");


	//get the lists of users
	
	@$liststr = implode("','",$lists);
	}
	else
	{
	@$liststr = implode("','", $admin["lists"]);
	}

		//$liststr = implode("','", $listslist);
		switch ($type) {
			case 'one':
				$id = intval($id);
				return adesk_sql_select_row("
	                SELECT
	                    f.*
	                FROM
						`#list_field` f,
						#list_field_rel r
	                WHERE
	                    f.`id` = '$id'
					AND r.fieldid = f.id
					AND r.relid IN ('$liststr')
            ");

			case 'rel':
				$id = intval($id);
				return adesk_sql_select_list("
	                SELECT
	                    relid
	                FROM
	                    `#list_field_rel`
	                WHERE
	                    `fieldid` = '$id'
					AND relid IN ('$liststr')
            ");

			case 'list':
				$lists = list_get_all();
				$id = intval($id);
				if ( $id == 0 ) {
					$r = adesk_sql_select_array("
		                SELECT
		                    f.*
		                FROM
		                    `#list_field` f,
							#list_field_rel r
						WHERE
						    r.fieldid = f.id
						AND r.relid IN ('$liststr')
						GROUP BY f.id
		                ORDER BY `title` ASC
		            ");
				} else {
					$global = ( adesk_admin_ismaingroup() ? "'0', " : "" );
					$r = adesk_sql_select_array("
		                SELECT
		                    f.*
		                FROM
		                    `#list_field` f,
		                    `#list_field_rel` r
		                WHERE
		                	f.id = r.fieldid
		                AND
		                	r.relid IN ('$liststr')
						AND r.relid IN ($global '$id')
						GROUP BY f.id
		                ORDER BY r.dorder ASC
	            	");
				}
				foreach ( $r as $k => $v ) {
					// fetch all lists (relations)
					$rels = $this->doSelect('rel', $v['id']);
					// build an array of used lists
					$c = array();
					foreach ( $rels as $rel ) {
						if ( isset($lists[$rel]) ) {
							$c[$rel] = array(
								'title' => $lists[$rel]['name']
							);
						} elseif ( $rel == 0 ) {
							$c[$rel] = array(
								'title' => _a('- All -')
							);
						}
					}
					$r[$k]['lists'] = $c;
				}
				return $r;
			case 'listByType':
				$id = intval($id);
				return adesk_sql_select_array("
	                SELECT
	                    f.*
	                FROM
	                    `#list_field` f,
						#list_field_rel r
					WHERE
						r.fieldid = f.id
					AND r.relid IN ('0', '$liststr')
	                ORDER BY `type` ASC
            ");
			case 'list_mirrors':
				// fetch all lists (for mirrors list)
				$lists = list_get_all();
				$mirrors = array();

				if (isset($this->admin["groups"]) && in_array(adesk_GROUP_ADMIN, $this->admin["groups"])) {
					$mirrors[0] = array(
						'id' => 0,
						'name' => _a('- All -'),
						'selected' => ( is_array($id) ? in_array(0, $id) : $id == 0 ),
						'disabled' => 0
					);
				}

				foreach ( $lists as $k => $v ) {
					$mirrors[$k] = array(
						'id' => $k,
						'name' => $v['name'],
						'selected' => ( is_array($id) ? in_array($k, $id) : $k == $id ),
						'disabled' => 0
					);
				}
				return $mirrors;
			default:
				break;
		}

		return array();
	}


	function doUpdate(&$smarty, &$ary, $id) {
		$id = intval($id);
		$mirror = adesk_http_param('mirror');
		if ( in_array(0, $mirror) ) {
			if ( adesk_admin_ismaingroup() ) {
				$mirror = array(0);
			} else {
				$mirror = array_diff($mirror, array(0));
			}
		}
    	if ( !count($mirror) ) return $this->buildResultMessage($smarty, false, _a('No Lists are selected.'));
    	if ( !trim($ary['title']) ) return $this->buildResultMessage($smarty, false, _a('Field Name not provided.'));

		# Check to see if we have a perstag, and if so, if it's in use.
		if ($ary["perstag"] != "") {
			$e_perstag = adesk_sql_escape($ary["perstag"]);
			$count     = adesk_sql_select_one("SELECT COUNT(*) FROM #list_field WHERE perstag = '$e_perstag' AND id != '$id'");

			if ($count > 0)
				return $this->buildResultMessage($smarty, false, _a("The personalization tag you have selected is already in use; please choose another."));
		}

		$list = implode(', ', $mirror);
		adesk_custom_fields_update_field("#list_field", $ary, $id);
	    //adesk_sql_update("#list_field", $ary, "`id` = '$id'");
	    adesk_sql_delete("#list_field_rel", "`fieldid` = '$id' AND `relid` NOT IN ($list)");
	    foreach ( $mirror as $m ) {
	    	$m = (int)$m;
	    	$found = adesk_sql_select_one("SELECT COUNT(*) FROM #list_field_rel WHERE `fieldid` = '$id' AND `relid` = '$m'");
	    	if ( !$found ) {
	    		adesk_sql_insert("#list_field_rel", array('id' => 0, 'relid' => $m, 'fieldid' => $id));
	    	}
	    }
	    return true;
	}

	function doInsert(&$smarty, &$ary) {
		$mirror = adesk_http_param('mirror');
		if ( in_array(0, $mirror) ) {
			if ( adesk_admin_ismaingroup() ) {
				$mirror = array(0);
			} else {
				$mirror = array_diff($mirror, array(0));
			}
		}
    	if ( !count($mirror) ) return $this->buildResultMessage($smarty, false, _a('No Lists are selected.'));
    	if ( !trim($ary['title']) ) return $this->buildResultMessage($smarty, false, _a('Field Name not provided.'));

		# Check to see if we have a perstag, and if so, if it's in use.
		if ($ary["perstag"] != "") {
			$e_perstag = adesk_sql_escape($ary["perstag"]);
			$count     = adesk_sql_select_one("SELECT COUNT(*) FROM #list_field WHERE perstag = '$e_perstag'");

			if ($count > 0)
				return $this->buildResultMessage($smarty, false, _a("The personalization tag you have selected is already in use; please choose another."));
		}

		adesk_custom_fields_insert("#list_field", $ary);
	    //adesk_sql_insert("#list_field", $ary);
	    $id = adesk_sql_insert_id();
	    foreach ( $mirror as $m ) {
	    	adesk_sql_insert("#list_field_rel", array('id' => 0, 'relid' => (int)$m, 'fieldid' => $id));
	    }
	    return true;
	}

	function doDelete($id) {
	    $id = intval($id);
	    adesk_sql_delete("#list_field", "`id` = '$id'");
	    adesk_sql_delete("#list_field_rel", "`fieldid` = '$id'");
		adesk_sql_delete("#list_field_value", "`fieldid` = '$id'");
	}



	function setTemplateData(&$smarty, $id) {
		$relid = adesk_http_param("relid");
		$smarty->assign('pageTitle', $this->pageTitle);
		$smarty->assign('side_content_template', $this->sideTemplate);
		$smarty->assign('mode', 'field');
		$smarty->assign('parent', ( $relid == 0 ? adesk_sql_default_row('#list') : list_select_row($relid, false) ));
		$smarty->assign('data', array('id' => $id));
	}


	function buildResultMessage(&$smarty, $success, $message) {
		$smarty->assign('resultStatus', $success);
		$smarty->assign('resultMessage', $message);
		return $success;
	}
}

?>
