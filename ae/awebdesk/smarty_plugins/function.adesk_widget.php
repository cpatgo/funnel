<?php

function smarty_function_adesk_widget($params, &$smarty) {
	if ( !isset($params['widget']) or !is_array($params['widget']) ) {
		return "<!-- Widget not provided. -->";
	}

	require_once(awebdesk_functions('widget.php'));
    return widget_show($widget);
}

?>