<?php
// money.php

// Some functions useful for the handling of money/currency.

require_once(dirname(__FILE__) . '/date.php');

function adesk_money_prorate($amount, $from, $until) {
    $pro  = 0;
    $days = adesk_date_month_days($from);
    $rate = $amount / $days;
    $i    = (($from - adesk_date_month_first($from)) / adesk_DATE_DAY) + 1;

    while ($from <= $until) {
        if ($i > $days) {
            $i = 1;
            $days = adesk_date_month_days($from);
            $rate = $amount / $days;
        }

        $pro  += $rate;
        $i    += 1;
        $from += adesk_DATE_DAY;
    }

    return round($pro, 2);
}

?>
