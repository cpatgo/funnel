<?php
/**
 * This function basically provides the PHP implode function to templates
 */
function smarty_function_join($params, &$smarty) {
	if ( !isset($params['arr']) or !is_array($params['arr']) ) $params['arr'] = array();
	if ( !isset($params['separator']) or !is_string($params['separator']) ) $params['separator'] = '';
	return implode($params['separator'], $params['arr']);
}

?>
