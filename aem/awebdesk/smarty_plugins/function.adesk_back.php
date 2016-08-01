<?php

function smarty_function_adesk_back($params, &$smarty) {
    if (isset($params['href']))
        return '<input type="button" value="'._a("Back").'" onclick="window.location.href = \''.$params['href'].'\'" />'."\n";
    return "";
}

?>