<?php

$GLOBALS['adesk_ihook_table'] = array();

function adesk_ihook_define($key, $func) {
    global $adesk_ihook_table;

    if (!adesk_ihook_exists($key)) {
        $adesk_ihook_table[$key] = array();
    }

    array_push($adesk_ihook_table[$key], $func);
}

function adesk_ihook_undefine($key, $func) {
    global $adesk_ihook_table;

    if (adesk_ihook_exists($key)) {
        $index = array_search($func, $adesk_ihook_table[$key]);

        if ($index != FALSE) {
            $adesk_ihook_table[$key] =
                array_splice($adesk_ihook_table[$key], $index, 1);
        }
    }
}

function adesk_ihook_exists($key) {
    return array_key_exists($key, $GLOBALS['adesk_ihook_table']);
}

function adesk_ihook($key) {
    global $adesk_ihook_table;

    $ret = null;

    if (adesk_ihook_exists($key)) {
        foreach ($adesk_ihook_table[$key] as $func) {
            $args = func_get_args();
            array_splice($args, 0, 1);
            $ret  = call_user_func_array($func, $args);
        }
	} else {
		# If there is no function, return the first parameter.
		$args = func_get_args();
		array_splice($args, 0, 1);
		if (count($args) > 0)
			return $args[0];
	}

    return $ret;
}

?>
