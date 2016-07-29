<?php

function smarty_modifier_csv_escape($string, $wrapper) {
    return adesk_str_escape_csv($string, $wrapper);
}

?>