<?php

function smarty_modifier_adesk_datetime($string, $prefix) {
    $tindex = $prefix . '_timeformat';
    $dindex = $prefix . '_dateformat';
    $offset = $prefix . '_t_offset_secs';

    # See adesk_date's modifier function for more documentation on this function, as much of its behavior
    # is similar except for the treatment of the timestamp.

    if (!is_numeric($string)) {
        require_once dirname(dirname(__FILE__)) . '/functions/date.php';
        $new = adesk_date_parse($string);

        if ($new === $string)
            return $string;
        
        $string = $new;
    }

    if (!isset($_SESSION[$dindex]) || !isset($_SESSION[$tindex]) || !isset($_SESSION[$offset])) {
        require_once dirname(dirname(__FILE__)) . '/functions/site.php';
        $site = adesk_site_get();
    }

    $string = intval($string) + $_SESSION[$offset];

    # The session variables should be set up at this point; if not, revert to the default format.

    if (isset($_SESSION[$dindex]) && isset($_SESSION[$tindex]))
        $format = $_SESSION[$dindex] . " " . $_SESSION[$tindex];
    else
        $format = '%Y-%m-%d %H:%M:%S';

    return strftime($format, $string);
}

?>
