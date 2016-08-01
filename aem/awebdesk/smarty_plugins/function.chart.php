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
 * Purpose:  call Flash Chart with appropriate XML file
 * @param array graph, width, weight, bgcolor, transparent, public, legend
 * @param Smarty
 */
function smarty_function_chart($params, &$smarty)
{
	// include the calling function
	require_once(awebdesk_charts('charts.php'));
        require_once(awebdesk_functions('charts.php'));
	// default params
	$graph = 'show=1';
	$width = 600;
	$height = 250;
	$bgcolor = 'ffffff';
	$transparent = true;
	$publicGraph = false;
	$showLegend = true;
	$update = 0;
	// set all the parameters if they don't exist
	if ( isset($params['graph']) ) $graph = $params['graph'];
	if ( isset($params['width']) AND (int)$params['width'] != 0 ) $width = (int)$params['width'];
	if ( isset($params['height']) AND (int)$params['height'] != 0 ) $height = (int)$params['height'];
	if ( isset($params['bgcolor']) ) $bgcolor = $params['bgcolor'];
	if ( isset($params['transparent']) ) $transparent = (bool)$params['transparent'];
	if ( isset($params['public']) ) $publicGraph = (bool)$params['public'];
	if ( isset($params['legend']) ) $showLegend = (bool)$params['legend'];
	if ( isset($params['update']) ) $update = (int)$params['update'];
	$swf = adesk_charts_url() . '/awebdesk/charts/charts.swf?random=' . md5(microtime());
	$shared = $smarty->get_template_vars('isShared');
    if (defined("awebdesk_CHARTS_NOCHARTDATA")) {
        $php = adesk_charts_url() . ( $publicGraph ? '/index.php?' : ( $shared ? '/manage/' . $smarty->get_template_vars('reportsLink') . '&' : '/manage/desk.php?' ) );
    } else {
        $php = adesk_charts_url() . ( $publicGraph ? '/chartdata.php?random=' : '/manage/chartdata.php?random=' ) . md5(microtime());
    }
	if ( $graph != '' ) $php .= '&' . $graph;
	$php .= '&legend=' . (int)$showLegend;
	$php .= '&update=' . (int)$update;
	$lib = adesk_charts_url() . '/awebdesk/charts/charts_library';
	return InsertChart($swf, $lib, $php, $width, $height, $bgcolor, $transparent);
}



?>
