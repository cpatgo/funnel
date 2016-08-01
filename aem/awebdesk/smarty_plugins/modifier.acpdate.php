<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty acpdate modifier plugin
 *
 * Type:     modifier<br>
 * Name:     otadate<br>
 * Purpose:  format dates/times<br>
 * Input:<br>
 *         - string: input MySQL date(/time) string
 *         - format: format for output
 * @param string
 * @param string
 * @return string|void
 * @uses smarty_modifier_i18n()
 */

require_once(awebdesk_functions('ihook.php'));
require_once(awebdesk_functions('date.php'));

function smarty_modifier_acpdate($string, $format = '', $offset = 0) {
	if ( $string == '-' ) return $string;
	if (adesk_ihook_exists('smarty_acpdate_return'))
		return adesk_ihook('smarty_acpdate_return', $string, $format, $offset);
    else {
        $tstamp = strtotime($string) + ($offset * 3600);

        if ($format == "")
            $format = "%Y-%m-%d %H:%M:%S";

        return strftime($format, $tstamp);
    }
}

/* vim: set expandtab: */

?>
