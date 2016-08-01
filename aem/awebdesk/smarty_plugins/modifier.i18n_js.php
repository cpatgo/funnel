<?php

require_once dirname(dirname(__FILE__)) . '/smarty/plugins/modifier.escape.php';
require_once dirname(__FILE__) . '/modifier.i18n.php';

function smarty_modifier_i18n_js($string) {
    return smarty_modifier_escape(smarty_modifier_i18n($string), "javascript");
}

?>
