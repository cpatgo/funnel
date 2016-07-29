<?php

function smarty_modifier_adesk_isselected_mult($string, $val) {
	$val = explode(",", $val);
    if (in_array($string, $val))
        return "selected";
    else
        return "";
}

?>
