<?php
// custom_fields.php

require_once awebdesk_functions("utf.php");

$GLOBALS["adesk_custom_fields_charset_in"]  = "utf-8";
$GLOBALS["adesk_custom_fields_charset_out"] = "utf-8";

function adesk_custom_fields_charset($in, $out) {
	$GLOBALS["adesk_custom_fields_charset_in"]  = $in;
	$GLOBALS["adesk_custom_fields_charset_out"] = $out;
}

function adesk_custom_fields_convert_in($ary) {
	$in  = $GLOBALS["adesk_custom_fields_charset_in"];
	$out = $GLOBALS["adesk_custom_fields_charset_out"];

	if ($in != $out) {
		foreach ($ary as $k => $v)
			$ary[$k] = adesk_utf_conv($in, $out, $v);
	}

	return $ary;
}

function adesk_custom_fields_convert_out($ary) {
	$in  = $GLOBALS["adesk_custom_fields_charset_in"];
	$out = $GLOBALS["adesk_custom_fields_charset_out"];

	if ($in != $out) {
		foreach ($ary as $k => $v)
			$ary[$k] = adesk_utf_conv($out, $in, $v);
	}

	return $ary;
}

function adesk_custom_fields_relate($table, $fields, $relid) {
	# The $fields array is here assumed to have a bunch of fields with no data ids.  What we'll
	# try to do is find a data id for each field, based on $relid, or leave that field's data id
	# as zero if none found.

	$relid = intval($relid);
	$rval  = array();

	foreach ($fields as $fk => $fv) {
		$pair = explode(",", $fk, 2);
		if (count($pair) !== 2)
			continue;

		$fieldid = intval($pair[0]);
		$dataid  = intval($pair[1]);

		if ($dataid == 0) {
			$dataid = (int)adesk_sql_select_one("
				SELECT
					id
				FROM
					$table
				WHERE
					fieldid = '$fieldid'
				AND
					relid = '$relid'
				LIMIT 1
			");
		}

		$rval[sprintf("%d,%d", $fieldid, $dataid)] = $fv;
	}

	return $rval;
}

function adesk_custom_fields_delete_field($tablef, $tabled, $fieldcol, $fieldid) {
	$fieldid = intval($fieldid);
	adesk_sql_query("
		DELETE FROM
			`$tablef`
		WHERE
			`id` = '$fieldid'
	");
	adesk_sql_query("
		DELETE FROM
			`$tabled`
		WHERE
			`$fieldcol` = '$fieldid'
	");
}

function adesk_custom_fields_delete_data($tabled, $relcol, $relid) {
	$relid = intval($relid);
	adesk_sql_query("
		DELETE FROM
			`$tabled`
		WHERE
			`$relcol` = '$relid'
	");
}

function adesk_custom_fields_insert($tablef, $ary) {
	adesk_sql_insert($tablef, adesk_custom_fields_convert_in($ary));
	return adesk_sql_insert_id();
}

function adesk_custom_fields_update_field($tablef, $ary, $fieldid) {
	$fieldid = intval($fieldid);
	adesk_sql_update($tablef, adesk_custom_fields_convert_in($ary), "`id` = '$fieldid'");
}

function adesk_custom_fields_update_data($fields, $tabled, $fieldcol, $extra = array(), $defaults = array()) {
	$ary            = array();
	$insert_default = count($defaults) > 0;
	$dvals          = array();
	$r              = 0;
	$relid          = 0;

	if (isset($extra["relid"]))
		$relid = (int)$extra["relid"];

	if ($insert_default) {
		if (count($fields) < count($defaults)) {
			foreach ($defaults as $id => $def) {
				if (!isset($fields[$id])) {
					$fields[$id] = adesk_custom_fields_default_value($def);
				}
			}
		}
	}
	foreach ($fields as $idlist => $val) {
		if (!preg_match('/\d+,\d+/', $idlist))
		$idlist = $idlist . ",0";

		list($fieldid, $dataid) = explode(",", $idlist);
		$fieldid        = intval($fieldid);
		$dataid         = intval($dataid);
		$ary['val']     = $val;
		$ary[$fieldcol] = $fieldid;

		if (is_array($ary['val'])) {
			if ($ary['val'][0] == "__--blank--__" || $ary['val'][0] == "~|") {
				array_shift($ary['val']);
			}
			$ary['val'] = implode("||", $ary['val']);
		}

		if ($ary['val'] == "") {
			$ary['val'] = "~|";
		}

		if (count($extra) > 0) {
			$ary = array_merge($ary, $extra);
		}

		if ($dataid > 0) {
			$ary['id'] = $dataid;
		} elseif ($relid > 0) {
			$ary['id'] = (int)adesk_sql_select_one("SELECT id FROM $tabled WHERE fieldid = '$fieldid' AND relid = '$relid'"); 
		} else {
			unset($ary['id']);
		}

		$r += (int)adesk_sql_replace($tabled, adesk_custom_fields_convert_in($ary));

		if ($dataid < 1) {
			$dataid = adesk_sql_insert_id();
		}
	}
	return $r;
}

// Return fields (including their data), given:
//  tablef - the field table
//  tabled - the data table
//  join   - the syntax for the ON condition of the left join of tabled to tablef
//  where  - the syntax for the WHERE condition

function adesk_custom_fields_select_data($tablef, $tabled, $join, $where = 1, $valcol = 'val', $addcol = '') {
	$ary = adesk_sql_select_array("
		SELECT
			f.*,
			d.`$valcol`,
			$addcol
			IF(d.`id` IS NULL, 0, d.`id`) AS `dataid`
		FROM
			`$tablef` f
		LEFT JOIN
			`$tabled` d
		ON  $join
		WHERE
			$where
		ORDER BY f.`dorder` ASC
	");

	return adesk_custom_fields_select_handle($ary, $valcol);
}

function adesk_custom_fields_select_data_rel($tablef, $tabler, $tabled, $join, $where = 1, $valcol = 'val', $addcol = '') {
	$qry = "
		SELECT
			f.*,
			d.`$valcol`,
			r.relid,
			$addcol
			IF(d.`id` IS NULL, 0, d.`id`) AS `dataid`
		FROM
			`$tabler` r,
			`$tablef` f
		LEFT JOIN
			`$tabled` d
		ON  $join
		WHERE
			$where
		ORDER BY r.dorder ASC
	";
	//dbg(adesk_prefix_replace($qry));
	$ary = adesk_sql_select_array($qry);

	return adesk_custom_fields_select_handle($ary, $valcol);
}

function adesk_custom_fields_select_data_norel($tablef, $tabled, $where = 1, $valcol = 'val', $addcol = '') {
	$qry = "
		SELECT
			$addcol
			f.*,
			d.`id` AS `dataid`,
			d.val
		FROM
			`$tablef` f,
			`$tabled` d
		WHERE
			$where
		AND
			f.id = d.fieldid
	";

	$ary = adesk_sql_select_array($qry);
	return adesk_custom_fields_select_handle($ary, $valcol);
}

function adesk_custom_fields_select_data_rel_subquery($tablef, $tabler, $subquery = '', $where = 1, $valcol = 'val', $addcol = '') {
	$subquery2 = preg_replace('/^\s*SELECT.*FROM/s', "SELECT id FROM", $subquery);
	$qry = "
		SELECT
			f.*,
			( $subquery ) AS `$valcol`,
			r.relid,
			$addcol
			( $subquery2 ) AS `dataid`
		FROM
			`$tabler` r,
			`$tablef` f
		WHERE
			$where
		ORDER BY r.dorder ASC
	";
	//dbg(adesk_prefix_replace($qry));
	$ary = adesk_sql_select_array($qry);

	return adesk_custom_fields_select_handle($ary, $valcol);
}




function adesk_custom_fields_select_nodata($tablef, $where = 1, $valcol = 'val') {
	$ary = adesk_sql_select_array("
		SELECT
			f.*,
			'' AS `$valcol`,
			0 AS `dataid`
		FROM
			`$tablef` f
		WHERE
			$where
		ORDER BY f.`dorder` ASC
	");

	return adesk_custom_fields_select_handle($ary);
}

function adesk_custom_fields_select_nodata_rel($tablef, $tabler, $where = 1, $dataquery = '') {
	$ary = adesk_sql_select_array($q = "
		SELECT
			f.*,
			r.relid,
			'' AS val,
			0 AS dataid
		FROM
			`$tablef` f,
			`$tabler` r
		WHERE
			f.id = r.fieldid
		AND
		$where
		GROUP BY f.id
		ORDER BY r.dorder ASC
	");

	if ( $dataquery ) {
		return adesk_custom_fields_select_handle($ary, 'val', $dataquery);
	} else {
		return adesk_custom_fields_select_handle($ary);
	}
}


function adesk_custom_fields_select_handle($ary = array(), $valcol = null, $dataquery = '') {
	$ary = adesk_custom_fields_convert_out($ary);
	$len = count($ary);
	for ($i = 0; $i < $len; $i++) {
		//$fld =& $ary[$i];
		// fetch the value here (in case we wanted to fetch nodata, then data separately to avoid a left join on big tables)
		if ( $dataquery ) {
			$ary[$i][$valcol] = adesk_sql_select_one(sprintf($dataquery, $ary[$i]['id']));
			$ary[$i]['dataid'] = (int)adesk_sql_select_one(sprintf(preg_replace('/^\s*SELECT.*FROM/s', "SELECT id FROM", $dataquery), $ary[$i]['id']));
		}
		if ( !is_null($valcol) ) {
			$ary[$i][$valcol] = adesk_custom_fields_check_blank($ary[$i][$valcol]);
		}

		switch ($ary[$i]["type"]) {
			case 1: $ary[$i]["element"] = "text"; break;
			case 2: $ary[$i]["element"] = "textarea"; break;
			case 3: $ary[$i]["element"] = "checkbox"; break;
			case 4: $ary[$i]["element"] = "radio"; break;
			case 5: $ary[$i]["element"] = "select"; break;
			case 6: $ary[$i]["element"] = "hidden"; break;
			case 7: $ary[$i]["element"] = "multiselect"; break;
			case 8: $ary[$i]["element"] = "multicheckbox"; break;
			case 9: $ary[$i]["element"] = "date"; break;
			default:
				$ary[$i]["element"] = "unknown";
				break;
		}

		adesk_custom_fields_assign_options($ary[$i]);
	}

	return $ary;
}


function adesk_custom_fields_assign_options(&$field) {
	if (isset($field["value"]))
	$field["val"] = $field["value"];
	if ($field["type"] == 4 || $field["type"] == 5 || $field["type"] == 7 || $field["type"] == 8) {
		$words = explode("\n",$field["expl"]);
		if (!$field["val"]) { $field["val"] = $field["onfocus"]; }
		$field['options'] = array();
		$field["selected"] = '';
		foreach ($words as $word) {
			$exploded = explode("||",trim($word));
			$name = (isset($exploded[0])) ? $exploded[0] : '';
			$value = (isset($exploded[1])) ? $exploded[1] : '';

			$value_compare1 = preg_replace("/\s/",'',$value); // get rid of \r\n, space, etc...
			$value_compare2 = preg_replace("/\s/",'',$field["val"]); // get rid of \r\n, space, etc...
			$checked = '';
			if ($value_compare1 == $value_compare2) {
				$field["selected"] = $value;
			}
			$field['options'][] = array('name' => $name, 'value' => $value);
		}
	}
	if ( isset($field['perstag']) and $field['perstag'] != '' ) {
		$field['tag'] = '%' . $field['perstag'] . '%';
	} else {
		$field['tag'] = '%PERS_' . $field['id'] . '%';
	}
}

function adesk_custom_fields_default_value(&$field) {
	switch ($field["type"]) {
		case 2: return $field["expl"];
		case 1: # FALLTHRU
		case 3: # .
		case 4: # .
		case 5: # .
		case 6: # .
		case 7: # .
		case 8: return $field["onfocus"];
		case 9:
			if ( $field["onfocus"] == 'now' ) {
				return adesk_CURRENTDATE;
			} elseif ( $field["onfocus"] == 'null' ) {
				return '';
			} else {
				return $field["onfocus"];
			}
		default:
			break;
	}

	return "";
}

function adesk_custom_fields_check_blank($str) {
	if ($str == "__--blank--__" || $str == "~|") return "";
	return $str;
}

function adesk_custom_fields_required_check($reqid, &$fields) {
	$check  = strval($reqid) . ",";
	$len    = strlen($check);

	foreach ($fields as $key => $val) {
		if ($key == $reqid || substr($key, 0, $len) == $check) {
			return $val != "" && $val != "__--blank--__" && $val != "~|" && $val != "unchecked" && $val != array("__--blank--__") && $val != array("~|");
		}
	}

	return true;
}

function adesk_custom_fields_update_rel($tabler, $id, $relations) {
	$id = intval($id);
	adesk_sql_query("DELETE FROM $tabler WHERE fieldid = '$id'");

	foreach ($relations as $relid) {
		$ins = array("fieldid" => $id, "relid" => $relid);
		adesk_sql_insert($tabler, adesk_custom_fields_convert_in($ins));
	}
}

function adesk_custom_fields_types() {
	return array(
        1 => _a("Text Field"),
        2 => _a("Text Box"),
        3 => _a("Checkbox"),
        4 => _a("Radio Button"),
        5 => _a("Dropdown"),
        6 => _a("Hidden Field"),
        7 => _a("List Box"),
        8 => _a("Checkbox Group"),
        9 => _a("Date Field"),
	);
}

function adesk_custom_fields_type($ftype) {
	$types = adesk_custom_fields_types();
	if ( isset($types[$ftype]) ) return $types[$ftype];
    // We shouldn't get here
    return _a("Unknown");
}

?>
