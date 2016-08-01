<?php

require_once(dirname(dirname(__FILE__)) . '/functions/b64.php');


function smarty_modifier_b64d($str) {
	return adesk_b64_decode($str);
}

?>