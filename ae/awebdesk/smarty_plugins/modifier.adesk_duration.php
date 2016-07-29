<?php

function smarty_modifier_adesk_duration($string, $if_zero = '0s') {
#   require_once dirname(dirname(__FILE__)) . '/functions/base.php';
    require_once dirname(dirname(__FILE__)) . '/functions/date.php';

    $str = adesk_date_duration_span(intval($string));

    if ($str == '0s')
        return $if_zero;

    return $str;
}

?>
