<?php

function smarty_modifier_timeago($date, $now = null, $howmany = 0) {
    require_once dirname(dirname(__FILE__)) . '/functions/date.php';
    return adesk_date_timeago($date, $now, $howmany);
}

?>
