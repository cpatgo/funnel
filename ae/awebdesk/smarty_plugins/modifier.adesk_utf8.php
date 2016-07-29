<?php

function smarty_modifier_adesk_utf8($string) {
    $ours = strtoupper(_i18n("utf-8"));
    $utf8 = "UTF-8";

    if ($ours == $utf8)
        return $string;
    else
        return iconv($ours, $utf8 . '//IGNORE', $string);
}

?>
