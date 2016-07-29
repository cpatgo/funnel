<?php
// $Id$

function smarty_modifier_adesk_ischecked($string) {
    $val = intval($string);

    if ($val == 0 || $string == '')
        return "";
    else
        return "checked";
}

?>

