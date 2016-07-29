<?php
// pagination.php

require_once(awebdesk_classes('pagination.php'));
require_once(awebdesk_functions('http.php'));

function adesk_pagination_offset($container) {
    $pgindex = 'pagination_'.$container;

    if (!isset($_SESSION[$pgindex]))
        $_SESSION[$pgindex] = array('offset' => 0);

    if (adesk_http_param('offset'))
        $_SESSION[$pgindex]['offset'] = intval(adesk_http_param('offset'));
    else
        $_SESSION[$pgindex]['offset'] = 0;

    return $_SESSION[$pgindex]['offset'];
}

function adesk_pagination_build(&$smarty, $pgvar, $base, &$rows, $limit, $offset, $total) {
    $pg = new Pagination($total, count($rows), $limit, $offset, $base);
    $pg->buildLinks();
    $smarty->assign($pgvar, $pg);
}

?>
