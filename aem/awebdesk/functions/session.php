<?php

require_once dirname(__FILE__) . '/prefix.php';

function adesk_session_need_update() {
    $key = adesk_prefix_first("aweb_need_update");
    return isset($_SESSION[$key]) && $_SESSION[$key] == true;
}

function adesk_session_drop_cache() {
    $_SESSION[adesk_prefix_first("aweb_need_update")] = true;
}

function adesk_session_has($key) {
    if (adesk_session_need_update())
        adesk_session_unset($key);

    return isset($_SESSION['adesk_' . $key]);
}

function adesk_session_get($key) {
    return $_SESSION['adesk_' . $key];
}

function adesk_session_set($key, $val) {
    $_SESSION['adesk_' . $key] = $val;
}

function adesk_session_unset($key) {
    unset($_SESSION['adesk_' . $key]);
}

?>
