<?php

function smarty_modifier_help($string) {
	$args = func_get_args();
	$string = call_user_func_array('_i18n', $args);
	if (defined("LANGFILES_UTF8") && isset($GLOBALS['__languageArray']['utf-8']) && $GLOBALS['__languageArray']['utf-8']) {
		$to = $GLOBALS['__languageArray']['utf-8'];
		$string = @iconv("UTF-8", $to . "//IGNORE", $string);
	}

	$string = htmlspecialchars($string);
	if ( !trim($string) ) return '';
	$string = nl2br($string);
	if ( !isset($GLOBALS['adesk_help_imgpath']) ) {
		if ( !isset($GLOBALS['adesk_library_url']) ) {
			require_once adesk_admin('functions/awebdesk.php');
		}
		$GLOBALS['adesk_help_imgpath'] = $GLOBALS['adesk_library_url'];
		/*
		if ( isset($GLOBALS['adesk_library_url']) ) {
			$GLOBALS['adesk_help_imgpath'] = $GLOBALS['adesk_library_url'];
		} else {
			require_once dirname(dirname(__FILE__)) . "/functions/site.php";
			$GLOBALS["adesk_help_imgpath"] = adesk_site_plink(basename(awebdesk()));
		}
		*/
	}

	if (!isset($GLOBALS["adesk_help_c"]))
		$GLOBALS["adesk_help_c"] = 0;

	$count  = intval($GLOBALS["adesk_help_c"]);
	$global = $GLOBALS["adesk_help_imgpath"];
	$divid  = "adesk_help_div$count";
	$elawebdesk_a =
		"<a href='#' class='adesk_help' onmouseover='adesk_dom_toggle_display(\"$divid\", \"inline\")' onmouseout='adesk_dom_toggle_display(\"$divid\", \"inline\")' onfocus='adesk_dom_toggle_display(\"$divid\", \"inline\")' onblur='adesk_dom_toggle_display(\"$divid\", \"inline\")' onclick='return false;'>";
	$elawebdesk_img =
		"<img src='$global/media/adesk_help_clear.gif' border='0' align='absmiddle' /></a>";
	$elawebdesk_div =
		"<div class='adesk_help' id='$divid' style='display:none;'>$string</div>";

	$GLOBALS["adesk_help_c"] = $count+1;

	return
		$elawebdesk_a . $elawebdesk_img . $elawebdesk_div;
}

?>
