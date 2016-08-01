<?php

function smarty_function_adesk_js($params, &$smarty) {
    $base = '';
    if ( isset($params['prfx']) ) {
    	$base = (string)$params['prfx'];
    } elseif ( isset($params['base']) ) {
    	$base = (string)$params['base'];
    	if ( $base != '' ) {
    		if ( $base != '/' ) $base .= '/'; // if requesting absolute root (/)
    	}
	} elseif (isset($_SERVER["SCRIPT_FILENAME"])) {
		$path = dirname($_SERVER["SCRIPT_FILENAME"]);
		if (!file_exists("$path/awebdesk")) {
			$path = dirname($path);
			$base = "../";
		}
	} else {
		$path = dirname(__FILE__);
		if (!file_exists("$path/awebdesk")) {
			$path = dirname($path);
			$base = "../";
		}
	}
	if ( function_exists('adesk_site_isstandalone') and !adesk_site_isstandalone() ) {
		if ( !isset($params['src']) ) {
			if (!preg_match('/^(http:|https:)/', $base))
				$base = '../' . $base;
		}
	} elseif ( defined('adesk_KB_STANDALONE') and !adesk_KB_STANDALONE ) {
		if ( !isset($params['src']) ) {
			if (!preg_match('/^(http:|https:)/', $base))
				$base = '../' . $base;
		}
	}

	/*
	// duplicate check
	if ( !isset($smarty->_adesk_js) ) $smarty->_adesk_js = array();
	foreach ( $smarty->_adesk_js as $arr ) {
		if ( $params == $arr ) return ''; // we already printed this out in this smarty instance
	}
	$smarty->_adesk_js[] = $params;
	*/

	if (isset($params['lib'])) {
		return "<script type='text/javascript' src='{$base}awebdesk/$params[lib]'></script>";
	}

    if (isset($params['acglobal'])) {
        return '<script type="text/javascript" src="' . $base .'awebdesk/js/awd.js.php?inc=' . $params['acglobal'] . '"></script>';
    }

    if (isset($params['src'])) {
		if (preg_match('/^(http:|https:)/', $params["src"]))
			$base = "";

        return '<script type="text/javascript" src="' . $base . $params['src'] . '"></script>';
    }

    return '';
}

?>
