<?php

function smarty_function_adesk_amchart($params, &$smarty) {
	if (!isset($params["divid"]))
		return "<!-- missing divid -->";

	$html = "";
	$options = "";

	if (isset($params["display"]) && $params["display"] == false)
		$options = "style='display:none'";

	if ( false and !isset($GLOBALS['__smarty_function_adesk_amchart']) ) {
		$GLOBALS['__smarty_function_adesk_amchart'] = true;
		$prfx = isset($params['public']) ? '' : '../';
		$html .= '
<script type="text/javascript" src="' . $prfx . 'awebdesk/amcharts/javascript/amcharts.js"></script>
<script type="text/javascript" src="' . $prfx . 'awebdesk/amcharts/javascript/amfallback.js"></script>
<script type="text/javascript" src="' . $prfx . 'awebdesk/amcharts/javascript/raphael.js"></script>
		';
	}

	$html .= "<div id='$params[divid]' $options></div>\n";
	$html .= "
		<script type='text/javascript'>var p_$params[divid] = {
			type     : '$params[type]',
			divid    : '$params[divid]',
			width    : '$params[width]',
			height   : '$params[height]',
			bgcolor  : '$params[bgcolor]',
			location : '$params[location]',
			url      : '$params[url]',
			origurl  : '$params[url]',
			write    : true
		};
		function refresh_$params[divid](addurl) {
			p_$params[divid].url = sprintf('%s&%s', p_$params[divid].origurl, addurl);
			adesk_amchart(p_$params[divid]);
		}
		adesk_amchart(p_$params[divid]);</script>
	";

	return $html;
}

?>
