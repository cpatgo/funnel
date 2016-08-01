<?php
/**
 * This function basically provides the PHP rand function to templates
 */
function smarty_function_rand($params, &$smarty){
	if ( !defined('RAND_MAX') ) define('RAND_MAX', 32768);
	$min = ( isset($params['min']) ? $params['min'] : 0);
	$max = ( isset($params['max']) ? $params['max'] : RAND_MAX);
	$rnd = rand($min, $max);
	if ( isset($params['assign']) ) {
		$smarty->assign($params['assign'], $rnd);
	}
	return $rnd;
}

?>
