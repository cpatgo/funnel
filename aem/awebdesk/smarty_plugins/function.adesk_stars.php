<?php

function smarty_function_adesk_stars($params, $smarty = null) {
	if (!isset($GLOBALS["adesk_help_imgpath"])) {
		if ( isset($GLOBALS['adesk_library_url']) ) {
			$GLOBALS['adesk_help_imgpath'] = $GLOBALS['adesk_library_url'];
		} else {
			require_once dirname(dirname(__FILE__)) . "/functions/site.php";
			$GLOBALS["adesk_help_imgpath"] = adesk_site_plink(basename(awebdesk()));
		}
	}

	$global = $GLOBALS["adesk_help_imgpath"];

	if (!isset($params["func"]))
		$params["func"] = "adesk_star_set";

	if (!isset($params["relid"]) /*|| !isset($params["func"])*/ || !isset($params["rating"]))
		die("Missing parameters to adesk_stars (needs at least relid, func and rating)");

	$relid = intval($params["relid"]);
	$func  = $params["func"];
	$rating = sprintf("%.2lf", floatval($params["rating"]));

	$enabled = true;

	if (isset($params["enabled"]))
		$enabled = (bool)$params["enabled"];

	if (isset($params["prefix"]))
		$prefix = $params["prefix"] . "_star$relid";
	else
		$prefix = "star$relid";

	$links = array();
	$count = 5;			# 5 stars

	$cr = $rating;		# rating counter
	$ci = 0;			# index counter
	while ($count--) {
		$ci++;

		$class = "adesk_star_none";
		if ($cr >= 1.0)
			$class = "adesk_star_full";
		elseif ($cr >= 0.5)
			$class = "adesk_star_half";

		$links[] = "<a class=\"$class\" href=\"javascript:void(0)\" "
			. ($enabled ? "onclick=\"$func('$prefix', $relid, $ci)\" onmouseover=\"adesk_star_hover('$prefix', $ci)\" onmouseout=\"adesk_star_render('$prefix', $rating)\">" : "style=\"cursor: default\">")
			. "<img style=\"padding: 0px\" border=\"0\" align=\"absmiddle\" src=\"$global/media/adesk_star_clear.gif\" />"
			. "</a>";

		$cr -= 1.0;
	}

	return "<span id=\"$prefix\">" . implode("", $links)
		. "<span class=\"adesk_hidden\" id=\"{$prefix}_rating\">$rating</span>"
		. "</span>";
}

?>
