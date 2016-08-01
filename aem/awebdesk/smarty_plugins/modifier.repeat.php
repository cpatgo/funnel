<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty replace modifier plugin
 *
 * Type:     modifier<br>
 * Name:     repeat<br>
 * Purpose:  simple repeat
 * @param string
 * @param string
 * @return string
 */
function smarty_modifier_repeat($string, $cnt)
{
    return str_repeat($string, $cnt);
}

/* vim: set expandtab: */

?>
