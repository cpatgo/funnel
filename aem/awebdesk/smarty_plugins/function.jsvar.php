<?php
/**
 * This function basically provides the adesk_php_js() function to templates
 */
function smarty_function_jsvar($params, &$smarty){
    require_once dirname(dirname(__FILE__)) . '/functions/php.php';
    if ( !isset($params['var']) ) $params['var'] = null;
    return ( isset($params['name']) ? 'var ' . $params['name'] . ' = ' . adesk_php_js($params['var']) . ';' : adesk_php_js($params['var']) );
}

?>
