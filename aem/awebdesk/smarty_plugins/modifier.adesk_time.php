<?php

function smarty_modifier_adesk_time($string, $prefix) {
    $index = $prefix . '_timeformat';

    # See adesk_date's modifier function for more documentation on this function, as much of its behavior
    # is similar except for the treatment of the timestamp.

    if (!is_numeric($string)) {
        require_once dirname(dirname(__FILE__)) . '/functions/date.php';
        $new = adesk_date_parse($string);

        if ($new === $string)
            return $string;
        
        $string = $new;
    }

    if (isset($_SESSION[$index]))
        $format = $_SESSION[$index];
    else
        $format = '%H:%M:%S';

    return strftime($format, $string);
}

?>
