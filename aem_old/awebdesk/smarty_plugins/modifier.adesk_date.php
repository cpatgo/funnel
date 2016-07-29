<?php

function smarty_modifier_adesk_date($string, $prefix) {
    $index = $prefix . '_dateformat';

    # If this isn't a unix timestamp, then try to parse it; but adesk_date_parse() can only handle
    # MySQL datetime formats (%Y-%m-%d %H:%M:%S for strftime); anything else will be returned
    # unmodified.

    if (!is_numeric($string)) {
        require_once dirname(dirname(__FILE__)) . '/functions/date.php';
        $new = adesk_date_parse($string);

        # adesk_date_parse() will literally return its input if it couldn't translate the string, so to avoid
        # a byte-for-byte match use the identity equality operator.

        if ($new === $string)
            return $string;
        
        $string = $new;
    }

    if (isset($_SESSION[$index]))
        $format = $_SESSION[$index];
    else
        $format = '%Y-%m-%d';

    return strftime($format, $string);
}

?>
