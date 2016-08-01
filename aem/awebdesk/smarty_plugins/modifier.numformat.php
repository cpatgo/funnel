<?php

function smarty_modifier_numformat($str, $num_decimal_places = null, $dec_separator = null, $thoursands_separator = null) {
	return number_format($str, $num_decimal_places, $dec_separator, $thoursands_separator);
}

?>