<?php

function smarty_modifier_adesk_ischecked_radio($string, $val) {
    if ($string == $val)
        return "checked";
    else
        return "";
}

?>
