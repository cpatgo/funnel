<?php

function smarty_modifier_html($str) {
    require_once dirname(dirname(__FILE__)) . '/smarty/plugins/modifier.escape.php';
    return smarty_modifier_escape($str, 'html');
}

?>
