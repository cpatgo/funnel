<?php

function smarty_function_adesk_gantt_normal($params, &$smarty) {
    if (!isset($params['start']) || !isset($params['stop']) || !isset($params['from']))
        return "";

    $start = adesk_date_parse($params['start']);
    $stop  = adesk_date_parse($params['stop']);
    $from  = $params['from'];
    $fg    = '#ff0000';
    $inc   = 60;
    $colstart = "start";
    $colstop  = "stop";

    if (isset($params['fgcolor']))
        $fg = $params['fgcolor'];

    if (isset($params['unit']))
        $inc = adesk_date_duration_parse($params['unit']);

    if (isset($params['colstart']))
        $colstart = $params['colstart'];

    if (isset($params['colstop']))
        $colstop = $params['colstop'];

    require_once dirname(dirname(__FILE__)) . '/classes/gantt.php';

    $gantt = new adesk_Gantt(($stop - $start) / 60, 500);
    $i     = 0;

    foreach ($from as $row) {
        $grow = $gantt->create_row();

        for ($unit = $row[$colstart]; $unit < $row[$colstop] && $i < $gantt->width; $unit += 60, $i++) {
            if ($unit >= $start && $unit <= $stop)
                $grow[$i] = $fg;
        }

        $gantt->add_row($grow);
    }

    $html = $gantt->render();
    unset($gantt);

    return $html;
}

?>
