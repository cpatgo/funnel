<?php

$campaignid = intval(adesk_http_param("id"));
$messageid  = intval(adesk_http_param("messageid"));

if ($messageid > 0) {
	$open = (int)adesk_sql_select_one("
		SELECT
			COUNT(*)
		FROM
			#link_data ld
		WHERE
			ld.linkid IN
			(
				SELECT
					id
				FROM
					#link l
				WHERE
					l.link = 'open'
				AND l.tracked = 1
				AND l.campaignid = '$campaignid'
				AND l.messageid = '$messageid'
			)
	");
} else {
	$open = (int)adesk_sql_select_one("
		SELECT
			`uniqueopens`
		FROM
			#campaign
		WHERE
			id = '$campaignid'
	");
}

$bcond = "";
if ($messageid > 0)
	$bcond = "AND bd.messageid = '$messageid'";

$bounce = (int)adesk_sql_select_one("
	SELECT
		COUNT(*)
	FROM
		#bounce_data bd
	WHERE
		bd.campaignid = '$campaignid'
		$bcond
");

if ($messageid > 0) {
	$total = (int)adesk_sql_select_one("
		SELECT
			total_amt
		FROM
			#campaign_message
		WHERE
			campaignid = '$campaignid'
		AND
			messageid = '$messageid'
	");
} else {
	$total = (int)adesk_sql_select_one("
		SELECT
			total_amt
		FROM
			#campaign
		WHERE
			id = '$campaignid'
	");
}

# The number of unopened emails equals the total less the number of opens and the number of
# bounces.
$unopen = ($total - $open - $bounce);

$pie = array(
	array(
		"title" => _a("Opened"),
		"val"   => $open,
	),
	array(
		"title" => _a("Unopened"),
		"val"   => $unopen,
	),
	array(
		"title" => _a("Bounced"),
		"val"   => $bounce,
	),
);

$smarty->assign("pie", $pie);

?>
