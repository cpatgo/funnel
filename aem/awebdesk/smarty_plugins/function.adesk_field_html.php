<?php

function __check_blank($str) {
    if ($str == "__--blank--__" || $str == "~|")
        return "";
    return $str;
}

function smarty_function_adesk_field_html($params, &$smarty) {
    require_once awebdesk("smarty/plugins/modifier.escape.php");
    if (!isset($params['field']) || !is_array($params['field']))
        return "";

    $brackets = "[]";

    # If this is true, then we intend to post the fields back via ajax.  In those cases, we may
    # want to avoid the double-brackets on multi-select fields.
    if (isset($params["ajax"]))
        $brackets = "";

    $isadmin = false;

    if (isset($params['isadmin']))
        $isadmin = true;

    $post = "";

    $field = $params['field'];
    if (!isset($field['dataid']))
        $field['dataid'] = 0;

    if (isset($params['post'])) {
        $pkey = "$field[id],$field[dataid]";
        if (isset($params['post'][$pkey])) {
            if (is_string($params['post'][$pkey]))
                $post = htmlspecialchars($params['post'][$pkey], ENT_QUOTES);
            elseif (is_array($params['post'][$pkey]))
                foreach ($params['post'][$pkey] as $_key => $_val)
                    $params['post'][$pkey][$_key] = htmlspecialchars($_val);
        }
    }

    if (!isset($field['val'])) {
        $field['val'] = "";
    }

    if ($field["val"] == "" && $post != "") {
        $field["val"] = $post;
    }

    $bubble1 = $bubble2 = '';
    if ( ($field['type'] < 6 || $field['type'] >= 7) and isset($field['bubble_content']) and $field['bubble_content'] != '' and !isset($params['nobubbles']) ) {
        $bubble1 = ' onmouseover="adesk_dom_toggle_display(\'field' . $field['id'] . 'bubble\', \'block\')" onmouseout="adesk_dom_toggle_display(\'field' . $field['id'] . 'bubble\', \'block\')"';
        $bubble2 = '<div id="field' . $field['id'] . 'bubble" name="field[' . $field['id'] . ']text"  class="adesk_help" style="display:none;">' . $field['bubble_content'] . '</div>';
    }

    switch ($field['type']) {
        default:
        case 1:     // Text field
            if ($field['val'] == "")
                $field['val'] = $field['onfocus'];
            $field['val'] = __check_blank($field['val']);
            $field['val'] = smarty_modifier_escape($field['val']);
            $rval = "<input class='form-control' type='text' name='field[$field[id],$field[dataid]]' placeholder='$field[title]' value='$field[val]'$bubble1 />$bubble2";
            break;
        case 2:     // Text box
            if ($field['onfocus'] != "")
                list($cols, $rows) = explode("||", $field['onfocus']);
            else
                list($cols, $rows) = array(30, 5);

            if ($field['val'] == '')
                $field['val'] = $field['expl'];
            $field['val'] = __check_blank($field['val']);
            $field['val'] = smarty_modifier_escape($field['val']);
            $rval = "<textarea class='form-control' rows='$rows' cols='$cols' name='field[$field[id],$field[dataid]]' $bubble1>$field[val]</textarea>$bubble2";
            break;
        case 3:     // Checkbox
            $field['val'] = __check_blank($field['val']);

            if ($field['val'] == '')
                $field['val'] = $field['onfocus'];

            $field['val'] = smarty_modifier_escape($field['val']);
            $rval =
                "<input type='hidden' name='field[$field[id],$field[dataid]]' value='unchecked' />" .
                "<input type='checkbox' class='form-control' name='field[$field[id],$field[dataid]]' value='checked' $bubble1 " .
                    ($field["val"] == "checked" || $field["val"] == "yes" || $field["val"] == "on" ? "checked" : "") .
                " />$bubble2";
            break;
        case 4:     // Radio button(s)
            if ($field['val'] == '')
                $field['val'] = $field['onfocus'];
            $field['val'] = __check_blank($field['val']);
            $field['val'] = smarty_modifier_escape($field['val']);
            $html = "<input type='hidden' name='field[$field[id],$field[dataid]]' value='~|' />";
            $ary  = array_map('trim', explode("||", str_replace("\n", "||", $field['expl'])));

            for ($i = 0; $i < count($ary); $i += 2) {
                $ary[$i+1] = smarty_modifier_escape($ary[$i+1]);
                $html .= "<label class='cFieldRadio' $bubble1><input type='radio' name='field[$field[id],$field[dataid]]' value='{$ary[$i+1]}' ".
                    ($ary[$i+1] == $field['val'] ? "checked" : "") . " /> {$ary[$i+0]}</label>$bubble2<br />";
            }

            $rval = $html;
            break;
        case 5:     // Dropdown
            $html = "<select class='form-control' name='field[$field[id],$field[dataid]]' $bubble1>";
            $ary  = array_map('trim', explode("||", str_replace("\n", "||", $field['expl'])));
            if ( count($ary) % 2 ) $ary[] = '';

            if ($field['val'] == '')
                $field['val'] = $field['onfocus'];

            $field['val'] = __check_blank($field['val']);
            $field['val'] = smarty_modifier_escape($field['val']);
            for ($i = 0; $i < count($ary); $i += 2) {
                $html .= "<option value='{$ary[$i+1]}' " .
                    ($ary[$i+1] == $field['val'] ? "selected" : "") . ">{$ary[$i+0]}</option>";
            }

            $rval = $html . "</select>" . $bubble2;
            break;
        case 6:     // Hidden field
            if ($field['val'] == "")
                $field['val'] = $field['onfocus'];
            $field['val'] = __check_blank($field['val']);
            $field['val'] = smarty_modifier_escape($field['val']);
            if (!$isadmin)
                $rval = "<input type='hidden' name='field[$field[id],$field[dataid]]' value='$field[val]' />";
            else
                $rval = "<input type='text' name='field[$field[id],$field[dataid]]' value='$field[val]' />";
            break;
        case 7:     // List box (select with multiple)

            $html = "<input type='hidden' name='field[$field[id],$field[dataid]]$brackets' value='~|' />";
            $html .= "<select class='form-control' name='field[$field[id],$field[dataid]]$brackets' multiple $bubble1>";
            $ary  = array_map('trim', explode("||", str_replace("\n", "||", $field['expl'])));
            $sel  = array();

            if (is_array($field['val']))
                $field['val'] = implode("||", $field['val']);

            if ($field['val'] != "") {
                $field['val'] = __check_blank($field['val']);
                # If it's still not blank, then break it up.
                $field['val'] = smarty_modifier_escape($field['val']);
                if ($field['val'] != "")
                    $sel = explode("||", $field['val']);
            } else {
                $field['onfocus'] = smarty_modifier_escape($field['onfocus']);
                $sel = explode("||", $field['onfocus']);
            }

            for ($i = 0; $i < count($ary); $i += 2) {
                $ary[$i+1] = smarty_modifier_escape($ary[$i+1]);
                $html .= "<option value='{$ary[$i+1]}' " .
                    (in_array($ary[$i+1], $sel) ? "selected" : "") . ">{$ary[$i+0]}</option>";
            }

            $rval = $html . "</select>" . $bubble2;
            break;
        case 8:     // Checkbox group
            $html = "<input type='hidden' name='field[$field[id],$field[dataid]]$brackets' value='~|' />";
            $ary  = array_map('trim', explode("||", str_replace("\n", "||", $field['expl'])));
            $sel  = array();

            if (is_array($field['val']))
                $field['val'] = implode("||", $field['val']);

            if ($field['val'] != "") {
                $field['val'] = __check_blank($field['val']);
                # If it's still not blank, then break it up.
                $field['val'] = smarty_modifier_escape($field['val']);
                if ($field['val'] != "")
                    $sel = explode("||", $field['val']);
            } else {
                $field['onfocus'] = smarty_modifier_escape($field['onfocus']);
                $sel = explode("||", $field['onfocus']);
            }

            for ($i = 0; $i < count($ary); $i += 2) {
                $ary[$i+1] = smarty_modifier_escape($ary[$i+1]);
                $html .= "<label class='cFieldCheckboxGroup' $bubble1><input type='checkbox' value='{$ary[$i+1]}' name='field[$field[id],$field[dataid]]$brackets' "
                    . (in_array($ary[$i+1], $sel) ? "checked" : "") . " />";
                $html .= $ary[$i+0] . "</label>$bubble2<br />";
            }
            $rval = $html;
            break;
        case 9:     // Date field
            if ($field['val'] == "")
                $field['val'] = $field['onfocus'];

            if ( $field['val'] == 'now' ) $field['val'] = adesk_CURRENTDATE;

            $field['val'] = __check_blank($field['val']);
            $field['val'] = smarty_modifier_escape($field['val']);

            $prefixurl = $GLOBALS['adesk_library_url'];
            if ( !preg_match('/^http/i', $prefixurl) ) {
                // protocol
                $prefixurl = ( adesk_http_is_ssl() ? 'https' : 'http' ) . '://';
                // host
                $prefixurl .= ( isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : 'localhost' );
                // port
                $default_port = ( adesk_http_is_ssl() ? 443 : 80 );
                if ( isset($_SERVER['SERVER_PORT']) and $default_port != $_SERVER['SERVER_PORT'] ) $prefixurl .= ':' . $_SERVER['SERVER_PORT'];
                // base uri
                $prefixurl .= $GLOBALS['adesk_library_url'];
            }

            $calendar = '';
            if ( !isset($GLOBALS['adesk_custom_field_calendar_index']) ) $GLOBALS['adesk_custom_field_calendar_index'] = 0;
            if ( !$GLOBALS['adesk_custom_field_calendar_index'] ) {
                // load up the calendar
                require_once(awebdesk_smarty_plugins('function.adesk_calendar.php'));
                $calendar = smarty_function_adesk_calendar(array('acglobal' => $prefixurl, 'lang' => _i18n("en")), $smarty);
            }
            $GLOBALS['adesk_custom_field_calendar_index']++;
            $field_dom_id = $GLOBALS['adesk_custom_field_calendar_index'];
            $calendar .= "<a href='#' onclick='return false;' id='datecbutton$field_dom_id'><img src='$prefixurl/media/calendar.png' border='0' /></a>";
            $calendar .= "<script type='text/javascript'>Calendar.setup({inputField: 'datecfield$field_dom_id', ifFormat: '%Y-%m-%d', button: 'datecbutton$field_dom_id', showsTime: false, timeFormat: '24'});</script>";

            $rval = "<input class='form-control' id='datecfield$field_dom_id' type='text' name='field[$field[id],$field[dataid]]' value='$field[val]'$bubble1 />$bubble2$calendar";
            break;
    }

    if (isset($params['escape']))
        $rval = htmlentities($rval);

    return $rval;
}

?>
