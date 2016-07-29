<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * AwebDesk {chart} function plugin
 *
 * Type:     function<br>
 * Name:     editor<br>
 * Purpose:  call WYSIWYG editor for content<br>
 * @param array HTML, width of editor, height, name, toolbar...
 * @param Smarty
 */
function smarty_function_adesk_chart($params, &$smarty)
{
	// include the calling function
	require_once awebdesk_charts('charts.php');
	require_once awebdesk_functions('charts.php');
	require_once awebdesk_functions('site.php');
	// default params
	$width          = 600;
	$height         = 250;
	$bgcolor        = 'ffffff';
	$transparent    = true;
	$publicGraph    = false;
	$export         = true;
	$func			= "";
	$path			= "/graph.php";
	// set all the parameters if they don't exist
	if ( isset($params['width']) AND (int)$params['width'] != 0 ) $width = (int)$params['width'];
	if ( isset($params['height']) AND (int)$params['height'] != 0 ) $height = (int)$params['height'];
	if ( isset($params['bgcolor']) ) $bgcolor = $params['bgcolor'];
	if ( isset($params['transparent']) ) $transparent = (bool)$params['transparent'];

	if (isset($params['export']))
		$export = $params['export'];

	$swf = adesk_charts_url() . '/awebdesk/charts/charts.swf?random=' . md5(microtime());

	if (isset($params["func"]))
		$func = $params["func"];

	if (isset($params["path"]))
		$path = $params["path"];

	unset($params['width']);
	unset($params['height']);
	unset($params['bgcolor']);
	unset($params['transparent']);
	unset($params["func"]);
	unset($params["path"]);

	$params = array_merge($params, $_GET);
	unset($params['action']);
	unset($params['mode']);

	$extra = "";

	foreach ($params as $key => $val) {
		$extra .= "&$key=$val";
	}

	$php = adesk_charts_url() . "$path?func=$func$extra";
	$lib = adesk_charts_url() . '/awebdesk/charts/charts_library';

	return InsertChart($swf, $lib, $php, $width, $height, $bgcolor, $transparent);
}
?>
