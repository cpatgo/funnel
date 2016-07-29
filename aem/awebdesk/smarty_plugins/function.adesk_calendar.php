<?php

function smarty_function_adesk_calendar($params, &$smarty) {
	if (isset($params['base']))
		$base = $params['base'];
	else
		$base = ".";

	if (isset($params['theme']))
		$theme = $params['theme'];
	else
		$theme = "calendar-win2k-1.css";

	if (isset($params['lang']))
		$lang = $params['lang'];
	else
		$lang = "en";

	if (isset($params['acglobal']))
		$acglobal = $params['acglobal'];
	else
		$acglobal = $base . '/awebdesk';

	$result = "
<style type='text/css'>@import url($acglobal/jscalendar/$theme);</style>
<script type='text/javascript' src='$acglobal/jscalendar/calendar.js'></script>
<script type='text/javascript' src='$acglobal/jscalendar/lang/calendar-$lang.js'></script>
<script type='text/javascript' src='$acglobal/jscalendar/calendar-setup.js'></script>
";

	if ( isset($smarty->adesk_calendar_data) and $smarty->adesk_calendar_data == $result ) return '';

	$smarty->adesk_calendar_data = $result;

	return $result;
}

?>
