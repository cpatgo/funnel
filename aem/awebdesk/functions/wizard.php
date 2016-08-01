<?php

function adesk_wizard_array($str) {
    return 'adesk_wizard_array_'.$str;
}

function adesk_wizard_pos($str) {
    return 'adesk_wizard_pos_'.$str;
}

function adesk_wizard($ident) {
    return $GLOBALS[adesk_wizard_array($ident)];
}

function adesk_wizard_set($ident, $ary) {
    $GLOBALS[adesk_wizard_array($ident)] = $ary;
    $GLOBALS[adesk_wizard_pos($ident)] = 0;
}

function adesk_wizard_mark($ident, $text) {
    $ary = adesk_wizard($ident);
    for ($i = 0; $i < count($ary); $i++) {
        if ($ary[$i]['text'] == $text)
            $GLOBALS[adesk_wizard_pos($ident)] = $i;
    }
}

function adesk_wizard_set_subitems($ident, $text, $items, $open) {
    $index = adesk_wizard_array($ident);

    for ($i = 0; $i < count($GLOBALS[$index]); $i++) {
        if ($GLOBALS[$index][$i]['text'] == $text) {
            $GLOBALS[$index][$i]['subitems'] = $items;
            $GLOBALS[$index][$i]['subopen']  = $open;
        }
    }
}

function adesk_wizard_subitems_empty($ident, $text) {
    $index = adesk_wizard_array($ident);

    for ($i = 0; $i < count($GLOBALS[$index]); $i++) {
        if ($GLOBALS[$index][$i]['text'] == $text) {
            return count($GLOBALS[$index][$i]['subitems']) < 1;
        }
    }

    return true;
}

function adesk_wizard_menu(&$smarty, $ident) {
    $args = func_get_args();
    $wiz  = $GLOBALS[adesk_wizard_array($ident)];

    if (count($args) == 2) {
        for ($i = 0; $i < count($wiz); $i++) 
            $wiz[$i]['href'] = '';
    } else {
        for ($i = 2, $j = 1; $i < count($args); $i++, $j++) {
            for ($wizidx = 0; $wizidx < count($wiz); $wizidx++)
                $wiz[$wizidx]['href'] = preg_replace('/\*'.$j.'/', $args[$i], $wiz[$wizidx]['href']);
        }
    }

    $smarty->assign('wizard_array_'.$ident, $wiz);
    $smarty->assign('wizard_pos_'.$ident, $GLOBALS[adesk_wizard_pos($ident)]);
    $smarty->assign('side_content_template', 'side_menu_wizard.tpl.htm');
}

?>
