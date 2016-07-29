<?php

function smarty_modifier_adesk_field_type($string) {
    $val = intval($string);
    require_once(awebdesk_functions('custom_fields.php'));
    return adesk_custom_fields_type($val);
}

?>
