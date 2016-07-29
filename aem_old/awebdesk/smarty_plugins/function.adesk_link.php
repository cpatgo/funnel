<?php

function smarty_function_adesk_link($params, &$smarty) {
	$base = null;
	if ( isset($params['_base']) ) {
		$base = $params['_base'];
		unset($params['_base']);
	}
	return adesk_site_rwlink($params, $base);
}

?>