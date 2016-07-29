<?php

function smarty_modifier_js($str) {
    require_once dirname(dirname(__FILE__)) . '/smarty/plugins/modifier.escape.php';
    return smarty_modifier_escape($str, 'javascript');
}

?>
