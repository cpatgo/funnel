<?php

function smarty_modifier_adesk_isdisabled($string, $val) {
    if ($string == $val)
        return "disabled";
    else
        return "";
}

?>
