<?php

function smarty_function_adesk_widget_bar($params, &$smarty) {
	require_once(awebdesk_functions('widget.php'));

	if ( adesk_site_isknowledgebuilder() and !adesk_site_isstandalone() ) adesk_prefix_push('hd_');

	if ( !isset($params['section']) ) $params['section'] = 'admin';
	if ( !isset($params['delimiter']) ) $params['delimiter'] = '';

	if ( isset($params['widget']) ) {
		$widgets = array($params['widget']);
	} elseif ( isset($params['widgets']) and is_array($params['widgets']) ) {
		$widgets = $params['widgets'];
	} elseif ( isset($params['bar']) ) {
		$bar = $params['bar'];
		$baresc = adesk_sql_escape($bar);
		$sectionesc = adesk_sql_escape($params['section']);
		$query = "
			SELECT
				*
			FROM
				#widget
			WHERE
			(
				FIND_IN_SET('$baresc', `bars`)
			OR
				`bars` = ''
			OR
				`bars` IS NULL
			)
			AND
				`section` = '$sectionesc'
			ORDER BY
				`sort_order` ASC
		";
		$widgets = adesk_sql_select_array($query);
	} else {
		if ( adesk_site_isknowledgebuilder() and !adesk_site_isstandalone() ) adesk_prefix_pop();
		return "<!-- Widget not provided. -->";
	}

	if ( !isset($bar) ) {
		if ( !isset($GLOBALS['adesk_widgetbar_index']) ) $GLOBALS['adesk_widgetbar_index'] = 1;
		$bar = 'widgetbar_' . $GLOBALS['adesk_widgetbar_index'];
		$GLOBALS['adesk_widgetbar_index']++;
	}
	$r = '';
	foreach ( $widgets as $widget ) {
    	$str = widget_show($widget);
    	$r .= $str;
    	if ( $str and $params['delimiter'] ) $r .= $params['delimiter'];
	}
	if ( adesk_site_isknowledgebuilder() and !adesk_site_isstandalone() ) adesk_prefix_pop();
	return "<div id=\"$bar\" class=\"adesk_widgetbar\">$r</div>";
}

?>