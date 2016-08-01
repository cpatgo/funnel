<?php

function adesk_re_is_integer($str) {
    return preg_match('/^-?[0-9]+$/', $str);
}

?>
