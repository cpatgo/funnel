<?php
// charts.php

// Some helper functions for our flash charts

require_once awebdesk('charts/charts.php');

function adesk_charts_url() {
    require_once(awebdesk_functions('site.php'));
    $protocol = ((isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on' ) ? 'https' : 'http');
    $plink    = preg_replace('/^(http|https):\/\/[^\/]+\//', $protocol . '://' . $_SERVER['HTTP_HOST'] . '/', adesk_site_plink());
    return $plink;
}

function get_secondary_flash_url($query_string,$mode, $custom = '') {
	$swf = adesk_charts_url() . '/awebdesk/charts/charts.swf?random=' . md5(microtime());
    if (defined("awebdesk_CHARTS_NOCHARTDATA"))
        $php = adesk_charts_url() . ( ($mode == 'public') ? '/index.php?' : ($mode == 'shared' ? $custom : '/manage/desk.php?' ) ) . $query_string;
    else
        $php = adesk_charts_url() . ( ($mode == 'public') ? '/chartdata.php?random=' : '/manage/chartdata.php?random=' ) . md5(microtime());
	if ( $query_string != '' ) $php .= '&' . $query_string;
	$lib = adesk_charts_url() . '/awebdesk/charts/charts_library';
	return  $swf.'&library_path='.urlencode($lib).'&php_source='.urlencode($php);
}

function adesk_charts_secondary_flash_url($query_string, $mode) {
    return get_secondary_flash_url($query_string, $mode);
}

// --
// Functions to help construct the chart and display it

function adesk_charts_make() {
    $ch = array();

    $ch['license']          = 'C1XI6HMEW9L.HSK5T4Q79KLYCK07EK';
    $ch['legend_label']     = array('size' => '10', 'alpha' => '000', 'bold' => false, 'color' => 'red');
    $ch['chart_transition'] = array(
        'type'      =>  "zoom",
        'delay'     =>  0,
        'duration'  =>  1,
        'order'     =>  "series",
    );
    $ch['series_color']     = array("2AD747", "850001", "F5D039", "7F109B", "0B8DE5", "000E71", "008B17", "FFABAB", "C97B00", "C3B8A7", "9099AB");
    $ch['series_explode']   = array(10);

    return $ch;
}

function adesk_charts_make_area() {
    $ch = adesk_charts_make();
    $ch['chart_type']       = 'area';
    $ch['axis_category']    = array(
        'size'          => 8,
        'alpha'         => 75,
        'orientation'   => "diagonal_down",
        'skip'          => $skip
    );
    $ch['axis_value']       = array(
        'size'          => 12,
        'bold'          => false,
        'min'           => 0,
        'max'           => $maxValue
    );

    return $ch;
}

function adesk_charts_make_pie() {
    $ch = adesk_charts_make();
    $ch['chart_type']   = '3d pie';
    $ch['legend_rect']  = array('x' =>  -175, 'y' =>  0, 'fill_alpha' => 0);
    $ch['chart_rect']   = array('x' =>  0,   'y' =>  0);

    return $ch;
}

function adesk_charts_enable_legend(&$ch) {
	$ch['legend_rect'] = array('x' => 350, 'y' => 0, 'width' => 250, 'fill_alpha' => 0);
}

function adesk_charts_select_array(&$ch, $query, $value_text_blank = true) {
    $rs = adesk_sql_query($query);
    $at = array('');
    $av = array('');
    $a_ = array('');
    $ad = array();

	if (!$rs) {
		return null;
	}

    while ($row = adesk_sql_fetch_row($rs)) {
        $at[] = $row[0];
        $av[] = $row[1];
        $a_[] = '';
        $ad[] = array($row[1], $row[0]);
    }

    adesk_sql_free_result($rs);

    $ch['chart_data'] = array($av, $at);

    if ($value_text_blank)
        $ch['chart_value_text'] = array($av, $a_);

    $_SESSION['adesk_chart_data'] = $ad;
    return $ch['chart_data'];
}

function adesk_charts_send(&$ch) {
    require_once awebdesk_charts('charts.php');
    SendChartData($ch);
}

function adesk_charts_xml(&$ch) {
    require_once awebdesk_charts('charts.php');
    return ActualChartData($ch);
}

function adesk_charts_export(&$ch) {
    $_SESSION['adesk_chart_export'] = $ch;
}

function adesk_charts_dispatch($prefix) {
    if (isset($GLOBALS['adesk_chart_graph'])) {
        $func = $prefix . adesk_sql_escape($GLOBALS['adesk_chart_graph']);

        if (function_exists($func))
            call_user_func($func);
    }
}

?>
