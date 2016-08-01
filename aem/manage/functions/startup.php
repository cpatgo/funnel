<?php


function startup_recent_subscribers($limit) {
	$admin   = adesk_admin_get();
	$uid = $GLOBALS["admin"]["id"];
	if($uid != 1 ){
	$lists = adesk_sql_select_list("SELECT distinct listid FROM #user_p WHERE userid = $uid");
	//get the lists of users
	
	@$liststr = implode("','",$lists);
	}
	else
	{
	@$liststr = implode("','", $admin["lists"]);
	}
	return adesk_sql_select_array("
		SELECT
			s.id,
			s.email,
			CONCAT(sl.first_name, ' ', sl.last_name) AS a_fullname,
			l.name AS a_listname,
			sl.sdate
		FROM
			#subscriber_list sl FORCE INDEX (sdate),
			#subscriber s,
			#list l
		WHERE
			s.id = sl.subscriberid
		AND
			l.id = sl.listid
		AND
			sl.listid IN ('$liststr')
		ORDER BY
			sl.sdate DESC
		LIMIT
			$limit
	", array('sdate'));
}

function startup_recent_campaigns($limit) {
	$admin   = adesk_admin_get();
	        $uid = $admin['id'];
	if($uid != 1 ){
	$lists = adesk_sql_select_row("SELECT distinct(listid) FROM #user_p WHERE `userid` = '$uid'");
	//get the lists of users
	
	@$liststr = implode("','",$lists);
	}
	else
	{
	@$liststr = implode("','", $admin["lists"]);
	}
	$status_array = campaign_statuses();

	$ary = adesk_sql_select_array($q = "
		SELECT
			c.id,
			c.name,
			c.status,
			c.sdate
		FROM
			#campaign c
		WHERE
			c.id IN (SELECT cl.campaignid FROM #campaign_list cl WHERE cl.listid IN ('$liststr'))
		ORDER BY
			c.sdate DESC
		LIMIT
			$limit
	", array('sdate'));

	foreach ($ary as $k => $v) {
		$ary[$k]["a_lists"] = implode(", ", adesk_sql_select_list("
			SELECT
				l.name
			FROM
				#list l,
				#campaign_list cl
			WHERE
				l.id = cl.listid
			AND
				cl.campaignid = '$v[id]'
		"));

		$ary[$k]["a_lists_short"] = adesk_str_shorten($ary[$k]["a_lists"], 30);
		if (isset($status_array[$v["status"]])) {
			$ary[$k]["a_statusname"] = $status_array[$v["status"]];
		} else {
			$ary[$k]["a_statusname"] = _p("[Unknown]");
		}
	}

	return $ary;
}

function startup_viable() {
	$plink = adesk_site_plink();
	$port  = 80;

	if (strpos($plink, "http://") !== false) {
		$tmp = substr($plink, 7);
	} elseif (strpos($plink, "https://") !== false) {
		$tmp = substr($plink, 8);
		$port = 443;
	}

	$tmp  = explode("/", $tmp);
	$addr = $tmp[0];

	# Check if $addr has a port number in it...
	if (strpos($addr, ":") !== false) {
		$tmp  = explode(":", $addr);
		$addr = $tmp[0];
	}

	$url  = $plink . "/awebdesk/scripts/readable.php";

	$rval = adesk_http_viable($addr, $port, $url);
	return $rval;
}

function startup_rewrite() {
	$rval = array(
		"result" => true,
		"shortreason" => "",
		"explanation" => "",
	);

	$site = $GLOBALS["site"];

	if ($site['general_url_rewrite']) {
		$plink = adesk_site_plink();
		$url   = $plink . "/rewritetest/104";
		$rval  = adesk_http_testdata($url, "<!-- a:em:rewrite:test -->");
	}

	return $rval;
}

?>
