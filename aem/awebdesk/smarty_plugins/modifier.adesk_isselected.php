<?php

function smarty_modifier_adesk_isselected($string, $val) {
    if ($string == $val)
        return "selected";
    else
        return "";
}

?>
