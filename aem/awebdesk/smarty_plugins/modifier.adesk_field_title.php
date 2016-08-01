<?php

function smarty_modifier_adesk_field_title($string, $param) {
    if (intval($param) != 6)
        return $string;
}

?>
