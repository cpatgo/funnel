<?php

function smarty_modifier_adesk_clear_prefix($string, $type) {
    switch ($type) {
        case 'md5':
            return preg_replace('/^[0-9a-f]{32}_/', '', $string);

        case 'num':
            return preg_replace('/^[0-9]+_/', '', $string);

        default:
            return $string;
    }
}

?>
