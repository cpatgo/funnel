<?php

require_once(dirname(dirname(__FILE__)) . '/functions/lang.php');


function smarty_modifier_plang($str) {
	$args = func_get_args();
	$templateString = call_user_func_array('_i18n', $args);
	if (defined("LANGFILES_UTF8") && isset($GLOBALS['__languageArray']['utf-8']) && $GLOBALS['__languageArray']['utf-8']) {
		$to = $GLOBALS['__languageArray']['utf-8'];
		$templateString = @iconv("UTF-8", $to . "//IGNORE", $templateString);
	}

	return $templateString;
}

?>
