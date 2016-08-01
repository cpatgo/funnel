<?php

function smarty_modifier_dateform($string, $format = '') {
    if ($format == '') {
        if (isset($_SESSION["aweb_dateform_format"]))
            $format = $_SESSION["aweb_dateform_format"];
        else
            $format = '%Y-%m-%d %H:%M:%S';
    }

    return strftime($format, $string);
}

?>
