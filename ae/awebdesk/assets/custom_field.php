<?php

class Custom_Field_assets extends adesk_assets {

	var $sorting = true;
	var $mirroring = true;
	var $inlist = false;
	var $perstag = false;
	var $infoTitle = false;

    function Custom_Field_assets() {
		$this->adesk_assets();
        $this->subject  = _a("Field");
        $this->tpl_edit = "custom_field_edit.tpl.htm";
        $this->tpl_view = "custom_field_view.tpl.htm";
        $this->save_goes_back = true;
    }

    function process(&$smarty) {
        if (!adesk_admin_isadmin())
            return adesk_smarty_noaccess($smarty);

		require_once(awebdesk_functions('custom_fields.php'));

        adesk_ajax_dontrun();
        adesk_smarty_submitted($smarty, $this);
        $smarty->assign('sorting', $this->sorting);
        $smarty->assign('mirroring', $this->mirroring);
        $smarty->assign('inlist', $this->inlist);
        $smarty->assign('perstag', $this->perstag);
        $smarty->assign('infoTitle', $this->infoTitle);
        $smarty->assign('types', adesk_custom_fields_types());
        $this->handle($smarty);
        adesk_smarty_load_get($smarty);
    }

    function get_rows_cols(&$smarty, $onfocus) {
        $ary = explode("||", $onfocus);
        if (count($ary) != 2)
            list($cols, $rows) = array(30, 5);
        else
            list($cols, $rows) = $ary;

        $smarty->assign('rows', $rows);
        $smarty->assign('cols', $cols);
    }

    function breakup_mirror($mirror, $query) {
        $list = explode(",", $mirror);
        return adesk_sql_select_array($query . " (" . adesk_sql_in_list($list) . ")");
    }

    function breakup_expl(&$field, $expl) {
        $expl   = str_replace("\r\n", "||", $expl);      # ugly hack
        $ary    = explode("||", $expl);
        $values = array();

        if (($field['type'] == 7 || $field['type'] == 8)) {
            $field['onfocus_array'] = explode("||", $field['onfocus']);
        }

        if ((count($ary) % 2) == 0) {
            for ($i = 0; $i < count($ary); $i += 2) {
                $values[] = array(
                    'label' => $ary[$i+0],
                    'value' => $ary[$i+1],
                );

                if ($field['onfocus'] == $ary[$i+1])
                    $field['onfocus_label'] = $ary[$i+0];
                elseif (isset($field['onfocus_array'])) {
                    if (in_array($ary[$i+1], $field['onfocus_array'])) {
                        if (!isset($field['onfocus_label']))
                            $field['onfocus_label'] = $ary[$i+0];
                        else
                            $field['onfocus_label'] .= "," . $ary[$i+0];
                    }
                }
            }
        }

        $field['values'] = $values;
    }

    function create_expl(&$labels, &$values) {
        if (count($labels) != count($values) || !is_array($labels) || !is_array($values))
            return "";

        $expl = '';
        for ($i = 0; $i < count($labels); $i++) {
            $expl .= $labels[$i] . '||' . $values[$i];
            if ($i < (count($labels) - 1))
                $expl .= "\r\n";
        }

        return $expl;
    }

    function formProcess(&$smarty) {
        if (!isset($_POST["mode"]))
            return true;

        $this->ary = array(
            "title"     => $_POST["title"],
            "type"      => intval($_POST["type"]),
            "req"       => intval(isset($_POST["req"])),
            "onfocus"   => isset($_POST["onfocus"]) ? $_POST["onfocus"] : "",
            "expl"      => isset($_POST["expl"]) ? $_POST["expl"] : "",
            "label"     => $_POST["label"],
        );

        switch ($this->ary['type']) {
            case 3:     // checkbox
                if ($this->ary['onfocus'] == '')
                    $this->ary['onfocus'] = "unchecked";
                break;
            default:
                break;
        }

        if (isset($_POST["rows"]) && isset($_POST["cols"]))
            $this->ary["onfocus"] = $_POST["cols"] . "||" . $_POST["rows"];

        if (isset($_POST["bubble_content"]))
            $this->ary["bubble_content"] = $_POST["bubble_content"];

        if (isset($_POST["labels"]) && isset($_POST["values"]))
            $this->ary['expl'] = $this->create_expl($_POST["labels"], $_POST["values"]);

        if ( $this->mirroring )
            $this->ary['mirror'] = '';
        if ( $this->mirroring && isset($_POST["mirror"]) && is_array($_POST["mirror"]))
            $this->ary['mirror'] = implode(",", $_POST["mirror"]);
        if ( $this->inlist )
	    	$this->ary["show_in_list"] = (int)isset($_POST["show_in_list"]);
        if ( $this->perstag )
    		$this->ary["perstag"] = $_POST["perstag"];

    	return true;
    }
}

?>
