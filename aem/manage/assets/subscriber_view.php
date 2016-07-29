<?PHP

require_once awebdesk_classes("pagination.php");
require_once(awebdesk_functions('widget.php'));
require_once adesk_admin("functions/campaign.php");

class subscriber_view_assets extends AWEBP_Page {

	// constructor
	function subscriber_view_assets() {
		$this->pageTitle = _a("View Subscriber");
		$this->campaigns = array();
		parent::AWEBP_Page();
	}

	function process(&$smarty) {
 		$this->setTemplateData($smarty);

		// get subscriber
 		$id = (int)adesk_http_param('id');
 		if ( $id == 0 ) {
 			adesk_http_redirect('desk.php?action=subscriber');
 		}

 		$subscriber = subscriber_view($id);

 		if ( !$subscriber ) {
 			adesk_http_redirect('desk.php?action=subscriber');
 		}

		$subscriber["md5email"]         = md5($subscriber["email"]);	# For gravatars
		$subscriber["default_gravatar"] = urlencode(adesk_site_plink("manage/images/gravatar_default.png"));

		if (count($subscriber["lists"]) > 0) {
			$tmp = current($subscriber["lists"]);
			$listid = (int)$tmp["id"];
			$subscriber["default_name"] = (string)adesk_sql_select_one("SELECT to_name FROM #list WHERE id = '$listid'");
		} else {
			$subscriber["default_name"] = _a("Subscriber");
		}

		$smarty->assign('subscriber', $subscriber);

		$smarty->assign("listcount", count($subscriber["lists"]));

		# Future
		$smarty->assign("future", $this->future($id));

		# Actions
		$smarty->assign("actions", $this->actions($id));

		# Bounces
		$smarty->assign("bounces", $this->bounces($id));

		// set widget bar slots
		$widget_bar = widget_bar_get('admin_subscriber', 'admin');
		$smarty->assign("widget_bar", $widget_bar);

		$paginators = array();
		$panels = array('mailing', 'responder', 'log');
		foreach ( $panels as $p ) {
			$paginators[$p] = new Pagination(0, 0, $this->admin['messages_per_page'], 0, 'desk.php?action=subscriber_view');
			$paginators[$p]->allowLimitChange = true;
			$paginators[$p]->ajaxAction = 'subscriber.subscriber_stats_array_paginator';
			//$paginators[$p]->id = $p;
		}
		$smarty->assign('paginators', $paginators);

		adesk_smarty_submitted($smarty, $this);
		$smarty->assign('content_template', 'subscriber_view.htm');
	}

	function formProcess(&$smarty) {
		return adesk_ajax_api_result(0, _a("The script could not save subscriber settings on this page. Please edit the subscriber instead."));
	}

	function bounces($id) {
		$admin   = adesk_admin_get();
	        $uid = $admin['id'];
	if($uid != 1 ){
	$lists = adesk_sql_select_list("SELECT distinct listid FROM #user_p WHERE userid = $uid");


	//get the lists of users
	
	@$liststr = implode("','",$lists);
	}
	else
	{
	@$liststr = implode("','", $admin["lists"]);
	}


		
		
		$ary  = adesk_sql_select_array("
			SELECT
				b.*,
				(SELECT c.name FROM #campaign c WHERE c.id = b.campaignid) AS a_campaigntitle
			FROM
				#bounce_data b
			WHERE
				b.subscriberid = '$id'
			AND
				b.listid IN ('$liststr')
		");

		$rval = array();
		foreach ($ary as $bounce) {
			$date   = strftime($GLOBALS["site"]["dateformat"], strtotime($bounce["tstamp"]));
			$url    = adesk_site_plink("manage/desk.php?action=report_campaign&id=$bounce[campaignid]#general-01-0-0");
			$rval[] = sprintf(_a("There was a %s bounce on %s for campaign <a href='%s'>%s</a>."), $bounce["type"], $date, $url, $bounce["a_campaigntitle"]);
		}

		return $rval;
	}

	function campaign($id) {
		$id = (int)$id;

		if (!isset($this->campaigns[$id])) {
			$this->campaigns[$id] = array(
				"name" => (string)adesk_sql_select_one("SELECT name FROM #campaign WHERE id = '$id'"),
				"link" => adesk_site_plink("manage/desk.php?action=report_campaign&id=" . $id . "#general-01-0-0"),
			);
		}

		$link = $this->campaigns[$id]["link"];
		$name = $this->campaigns[$id]["name"];
		return "<a href='$link'>$name</a>";
	}

	function actions($id) {
		$rval = array();
		$id   = (int)$id;

		$admin = adesk_admin_get();
		@$liststr = implode("','", $admin['lists']);

		$rs = adesk_sql_query("
			SELECT
				l.campaignid,
				ld.tstamp
			FROM
				#link l,
				#link_data ld,
				#campaign_list cl
			WHERE
				ld.subscriberid = '$id'
			AND l.id = ld.linkid
			AND l.campaignid = cl.campaignid
			AND cl.listid IN ('$liststr')
			AND l.link = 'open'
			AND l.messageid != '0'
			AND l.tracked = 1
			AND ld.tstamp > (NOW() - INTERVAL 1 MONTH)
		");

		while ($row = adesk_sql_fetch_assoc($rs)) {
			$rval[$row["tstamp"]] = sprintf(_a("Opened \"%s\""), $this->campaign($row["campaignid"]));
		}

		$rs = adesk_sql_query("
			SELECT
				l.campaignid,
				l.link,
				ld.tstamp
			FROM
				#link l,
				#link_data ld,
				#campaign_list cl
			WHERE
				ld.subscriberid = '$id'
			AND l.id = ld.linkid
			AND l.campaignid = cl.campaignid
			AND cl.listid IN ('$liststr')
			AND l.link != 'open'
			AND l.tracked = 1
			AND ld.tstamp > (NOW() - INTERVAL 1 MONTH)
		");

		while ($row = adesk_sql_fetch_assoc($rs)) {
			$rval[$row["tstamp"]] = sprintf(_a("Clicked on a link in \"%s\""), $this->campaign($row["campaignid"]));
		}

		$rs = adesk_sql_query("
			SELECT
				f.campaignid,
				f.tstamp
			FROM
				#forward f,
				#campaign_list cl
			WHERE
				f.subscriberid = '$id'
			AND f.campaignid = cl.campaignid
			AND cl.listid IN ('$liststr')
			AND tstamp > (NOW() - INTERVAL 1 MONTH)
		");

		while ($row = adesk_sql_fetch_assoc($rs)) {
			$rval[$row["tstamp"]] = sprintf(_a("Forwarded \"%s\""), $this->campaign($row["campaignid"]));
		}

		krsort($rval);
		return $rval;
	}

	function future($id) {
		$id      = (int)$id;
		$lists   = adesk_sql_select_list("SELECT listid FROM #subscriber_list WHERE subscriberid = '$id' AND status = '1'");
		if (!$lists)
			return _a("No future campaigns.");

		$admin   = adesk_admin_get();
		$listary = array();

		foreach ($lists as $key => $val) {
			if (in_array($val, $admin['lists']))
				$listary[] = $val;
		}
		@$liststr = implode("','", $listary);
		$rval    = array();

		$rs = adesk_sql_query("
			SELECT
				c.*
			FROM
				#campaign c,
				#campaign_list l
			WHERE
				(c.ldate IS NULL OR c.`type` IN ('responder', 'reminder'))
			AND
				l.listid IN ('$liststr')
			AND
				c.id = l.campaignid
		");

		while ($row = adesk_sql_fetch_assoc($rs)) {
			$filterid = (int)$row["filterid"];
			$segsql   = "";
			$url      = adesk_site_plink("manage/desk.php?action=report_campaign&id=$row[id]#general-01-0-0");

			# If this is an auto-responder that sends immediately, don't bother.
			if ($row["type"] == "responder" && $row["responder_offset"] == 0)
				continue;

			# If this is an auto-remind that isn't strictly date-based, then skip it.
			if ($row["type"] == "reminder" && $row["reminder_field"] != "sdate" && $row["reminder_field"] != "cdate")
				continue;

			# If they don't match the filter, then skip.
			if ($filterid > 0) {
				$segsql = filter_compile($filterid);
				$so = new adesk_Select;
				$so->push("AND " . $segsql);
				$so->push("AND s.id = '$id'");

				if (count(subscriber_select_array($so)) == 0)
					continue;
			}

			if ($row["type"] == "deskrss") {
				$rval[$row["sdate"]] = sprintf(_a("The camapaign <a href='%s'>%s</a> will be sent whenever an RSS feed is updated"), $url, $row["name"]);
			} else {
				$time  = $this->future_time($row, $id);

				# Not a valid future item -- skip it.
				if ($time == "")
					continue;

				$email = (string)adesk_sql_select_one("SELECT email FROM #subscriber WHERE id = '$id'");
				$type  = $this->future_type($row);
				$rval[$row["sdate"]] = sprintf(_a("In %s %s will be sent the %s <a href='%s'>%s</a>"), $time, $email, $type, $url, $row["name"]);
			}
		}

		krsort($rval);
		return $rval;
	}

	function future_type($campaign) {
		switch ($campaign["type"]) {
			case "deskrss":
				# We shouldn't get here, but whatever.
				return _a("Active RSS campaign");

			case "responder":
				return _a("auto-responder campaign");

			case "single":
				return _a("scheduled campaign");

			case "reminder":
				return _a("date-based campaign");

			case "recurring":
				return _a("recurring campaign");
		}
	}

	function future_time_reformat($campaign, $subscriberid) {
		# This function is mainly for reminder date fields, which can look very different, so that we
		# can produce a date that is close enough for strtotime() to work with.

		$format = $campaign["reminder_format"];
		$date   = 0;

		# There's a possibility a user has set their own format for this kind of field, but we ignore
		# them; it has to be yyyy-mm-dd.
		if ($campaign["reminder_field"] == "sdate" || $campaign["reminder_field"] == "cdate") {
			$format = "yyyy-mm-dd";
			$date   = (string)adesk_sql_select_one("SELECT sdate FROM #subscriber_list WHERE subscriberid = '$subscriberid'");
			if ($date != "")
				$date = strtotime($date . $this->future_time_remindoffset($campaign));
			else
				$date = 0;
		} elseif ($campaign["reminder_field"] == "cdate") {
			$format = "yyyy-mm-dd";
			$date   = (string)adesk_sql_select_one("SELECT cdate FROM #subscriber WHERE id = '$subscriberid'");
			if ($date != "")
				$date = strtotime($date . $this->future_time_remindoffset($campaign));
			else
				$date = 0;
		} else {
			$date   = (string)adesk_sql_select_one("SELECT val FROM #list_field_value WHERE fieldid = '$campaign[reminder_field]' AND relid = '$subscriberid'");

			switch ($format) {
				case "yyyy-mm-dd":
				case "yyyymmdd":
				case "mm/dd/yyyy":
					break;

				case "dd/mm/yyyy":
					$tmp = explode("/", $date);
					$date = "$tmp[2]-$tmp[1]-$tmp[0]";
					break;

				case "dd.mm.yyyy":
					$tmp = explode(".", $date);
					$date = "$tmp[2]-$tmp[1]-$tmp[0]";
					break;
			}

			$date = strtotime($date . $this->future_time_remindoffset($campaign));
		}

		return $date;
	}

	function future_time_remindoffset($campaign) {
		$rval = 0;
		$type = $campaign["reminder_offset_type"];

		switch ($type) {
			default:
				$type = "day";
			case "day":
				break;
			case "week":
				$type = "weeks";
				break;
			case "month":
				break;
			case "year":
				break;
		}

		return " {$campaign["reminder_offset_sign"]}{$campaign["reminder_offset"]} $type";
	}

	function future_time($campaign, $subscriberid) {
		$now  = time();
		$then = strtotime($campaign["sdate"]);

		$days = 0;
		$hrs  = 0;
		$mins = 0;
		$diff = $then - $now;

		if ($campaign["type"] == "responder") {
			$sdate = (string)adesk_sql_select_one("SELECT sdate FROM awebdesk_subscriber_list WHERE subscriberid = '$subscriberid'");
			if ($sdate != "") {
				$then = strtotime($sdate) + ($campaign["responder_offset"] * 3600);
				$diff = $then - $now;

				while ($diff > 86400) {
					$diff -= 86400;
					$days++;
				}

				while ($diff > 3600) {
					$diff -= 3600;
					$hrs++;
				}

				while ($diff > 60) {
					$diff -= 60;
					$mins++;
				}
			}
		} else {
			if ($campaign["type"] == "reminder") {
				$then = $this->future_time_reformat($campaign, $subscriberid);
				$diff = $then - $now;
			}

			while ($diff > 86400) {
				$diff -= 86400;
				$days++;
			}

			while ($diff > 3600) {
				$diff -= 3600;
				$hrs++;
			}

			while ($diff > 60) {
				$diff -= 60;
				$mins++;
			}
		}

		$rval = array();

		# Let's not get too crazy here.
		if ($days > 30)
			return "";

		if ($days > 0)
			$rval[] = sprintf(_a("%d days", $days));
		if ($hrs > 0)
			$rval[] = sprintf(_a("%d hrs", $hrs));
		if ($mins > 0)
			$rval[] = sprintf(_a("%d mins", $mins));

		if (count($rval) == 0)
			return "";

		return implode(", ", $rval);
	}

}

?>
