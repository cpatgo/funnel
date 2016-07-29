<?php

function smarty_function_adesk_js_redir($params, &$smarty) {
    $q = '"';

    if (isset($params['single'])) {
        if ($params['single'] == true)
            $q = "'";
    }

    if (isset($params['href']))
        return "window.location.href = $q$params[href]$q";

    return "";
}

?>
