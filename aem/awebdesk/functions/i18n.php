<?php

require_once(dirname(__FILE__) . '/lang.php');

/**
 * This modifier takes in a string, looks it up in the translation file and returns
 * either the translated string, or if none is available, the original string
 * It uses a global array to keep the string loaded so that the file does not
 * need to be parsed over and over again.
 */
if (!function_exists('_i18n')) {
	if (defined("adesk_LANG_NOTRANS")) {
		function _i18n($string) {
			return $string;
		}
	} else {
		function _i18n($string){
			if(!isset($GLOBALS["__languageArray"]) || !is_array($GLOBALS['__languageArray'])){
				$type = 'lang';
				if ( isset($GLOBALS['smarty']) and isset($GLOBALS['smarty']->_folder) ) {
					$f = $GLOBALS['smarty']->_folder;
					if ( in_array($f, array('public', 'admin')) ) $type = $f;
				}
				/*$GLOBALS['__languageArray'] = */adesk_lang_get($type);
			}

			$args = func_get_args();
			if (!defined("adesk_LANG_NEW"))
				$string = str_replace('"', '\\"', $args[0]);
			$haslf  = strpos($string, "\n") !== false;

			if ($haslf)
				$string = str_replace("\n", "\\n", $string);

			$templateString = $string;
			if(isset($GLOBALS['__languageArray'][$string]) && ($GLOBALS['__languageArray'][$string] != "")) {
				$templateString =  $GLOBALS['__languageArray'][$string];
			}

			$templateString = str_replace('\\"', '"', $templateString);
			if(func_num_args() > 1){
				$args = func_get_args();
				$args[0] = $templateString;
				$templateString = call_user_func_array("sprintf", $args);
			}

			if ($haslf)
				$templateString = str_replace("\\n", "\n", $templateString);
			return $templateString;
		}
	}
}

require_once dirname(dirname(__FILE__)) . '/smarty_plugins/modifier.i18n.php';

function _p($str) {
	$args = func_get_args();
	return ( call_user_func_array('_i18n', $args) );
}

function _a($str) {
	$args = func_get_args();
	return ( call_user_func_array('_i18n', $args) );
}

function _d($str) {
	$args = func_get_args();
	return ( call_user_func_array('_i18n', $args) );
}

function _h($str) {
	$args = func_get_args();
	return ( call_user_func_array('_i18n', $args) );
}
?>
