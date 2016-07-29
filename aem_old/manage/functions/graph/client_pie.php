<?php

$campaignid = intval(adesk_http_param("id"));
//$messageid  = intval(adesk_http_param("messageid"));
$listid     = intval(adesk_http_param("listid"));


$cond = $subcond = '';
if ( $campaignid ) {
	$cond    .= "AND cl.campaignid = '$campaignid' ";
	$subcond .= "AND subl.campaignid = '$campaignid' ";
}

$listarr = array();
if ( adesk_admin_ismain() ) {
	if ( $listid ) $listarr[] = $listid;
} else {
	$admin = adesk_admin_get();
	if ( $listid ) {
		if ( isset($admin['lists'][$listid]) ) {
			$listarr[] = $listid;
		} else {
			$listarr = array(0);
		}
	} else {
		$listarr = $admin['lists'];
	}
}

if ( $listarr ) {
	$liststr = implode("', '", $listarr);
	$cond    .= "AND cl.listid IN ('$liststr') ";
	$subcond .= "AND subl.listid IN ('$liststr') ";
}

$query = "
		SELECT
			d.ua AS `name`,
			d.uasrc AS `ua`,
			COUNT(d.id) AS `hits`,
			100 * COUNT(d.id) / ( SELECT SUM(subc.uniqueopens) AS `cnt` FROM #campaign subc, #campaign_list subl WHERE subc.id = subl.campaignid $subcond ) AS `perc`,
			( SELECT SUM(subc.uniqueopens) AS `cnt` FROM #campaign subc, #campaign_list subl WHERE subc.id = subl.campaignid $subcond ) AS `cnt`
		FROM
#			#campaign c,
			#campaign_list cl,
			#link l,
			#link_data d
		WHERE
		1
		$cond
		AND
			l.messageid = 0
		AND
			l.link = 'open'
		AND
			l.tracked = 1
		AND
			( d.ua != '' OR d.uasrc != '' )
		AND
			cl.campaignid = l.campaignid
#		AND
#			cl.campaignid = c.id
		AND
			l.id = d.linkid
		GROUP BY
			d.ua #, d.uasrc
";
//dbg(adesk_prefix_replace($query));
$sql = adesk_sql_query($query);


$pie = array();
while ( $row = adesk_sql_fetch_assoc($sql) ) {
	if ( !$row['name'] ) $row['name'] = _a("Unknown");
	$percent = number_format($row['perc'], 2);
	$pie[ $percent ] = array(
		"title" => $row['name'],
		"val"   => $percent,
	);
}

krsort($pie);
$i = 1;
$pie_top10 = array();
$other_percent = 0;
foreach ($pie as $client) {
  if ($i > 10) {
    $other_percent += $client["val"];
  }
  else {
    $pie_top10[] = $client;
  }
  $i++;
}
if ($other_percent) {
  $pie_top10[] = array(
    "title" => _a("Other"),
    "val"   => $other_percent,
  );
}

//dbg($pie,1);
//dbg($pie_top10);

$smarty->assign("pie", $pie_top10);

?>
