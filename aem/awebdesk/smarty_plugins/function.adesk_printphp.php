<?php

function smarty_function_adesk_printphp($params, &$smarty) {
	// required params
	if ( !isset($params['str']) ) return '';
	if ( !isset($params['type']) ) return '';
	// additional params
	if ( !isset($params['html']) ) $params['html'] = false;
	if ( !isset($params['linenumbers']) ) $params['linenumbers'] = false;
	if ( !isset($params['element']) ) $params['element'] = ( $params['html'] ? 'div' : 'textarea' );
	if ( !isset($params['props']) ) $params['props'] = '';

	// call our function
	return adesk_php_print($params['str'], $params['type'], $params['html'], $params['linenumbers'], $params['element'], $params['props']);
}

?>