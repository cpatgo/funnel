<?PHP

require_once(awebdesk_functions('rss.php'));
require_once(awebdesk_classes('page.php'));
require_once adesk_admin("functions/subscriber.php");
require_once adesk_admin("functions/list.php");
require_once adesk_admin("functions/group.php");
require_once adesk_admin("functions/campaign.select.php");

class startup_assets extends AWEBP_Page {



	// constructor
	function startup_assets() {
		$this->pageTitle = _a("Dashboard");
		parent::AWEBP_Page();
		// get parameters
		$this->getParams();
	}




	// try to catch parameter from post, get or session
	function getParams() {
	}





	function process(&$smarty) {
 		$this->setTemplateData($smarty);
		$groupids = implode(",", $this->admin["groups"]);
/*
 		$groupids = implode(",", $this->admin["groups"]);
 		$group_users = group_get_users($this->admin["groups"]);

 		// Lists that group is a part of
		$group_lists_status = ( count($this->admin["lists"]) > 0 ) ? 0 : 1;
*/
	$admin   = adesk_admin_get();
	$uid = $GLOBALS["admin"]["id"];
	if($uid != 1 ){
		//process for each user
		
		
		//get the lists of users
	$lists = adesk_sql_select_list("SELECT distinct listid FROM #user_p WHERE userid = $uid");
	@$group_listids = implode("','",$lists);
	@$group_listidsc = implode(",",$lists);
	if(count($lists) == 0)
	  {
	  $subscriberscount = 0;
	  } 
	  else {
	$subscribersc = adesk_sql_select_one("SELECT count(*) from #subscriber_list where listid in ($group_listidsc)  and status =1");
	$subscriberscount = $subscribersc;
	  }
	$listcounts = count($lists);
//	$subscount = count($subscriberscount);

  //count total emails sent
  
    $totalemailc = adesk_sql_select_one("SELECT sum(total_amt) from #campaign WHERE userid = $uid");
	if(!isset($totalemailc))
	$totalemailc =0;
	$campc = adesk_sql_select_list("SELECT sendid from #campaign  where sendid > 0   and total_amt>0 and userid = $uid ");
	$campcount = count($campc);
	
	@$group_lists_status = ( count($lists) > 0 ) ? 0 : 1;
	}
	else
	{
		
		//process for admin(Shivaji the Boss) :) 
		
		
		
	@$group_listids = implode("','", $admin["lists"]);
	$listcounts = count($this->admin["lists"]);
	@$group_lists_status = ( count($this->admin["lists"]) > 0 ) ? 0 : 1;
	$subscribersc = adesk_sql_select_one("SELECT count(*) from #subscriber_list where status =1");
	$subscriberscount = $subscribersc;
    $totalemailc = adesk_sql_select_one("SELECT sum(total_amt) from #campaign");
	if(!isset($totalemailc))
	$totalemailc =0;
	$campc = adesk_sql_select_list("SELECT sendid from #campaign  where sendid > 0   and total_amt>0   ");
	$campcount = count($campc);
	
	
	
	
	}



	//	$group_listids = implode("','", $this->admin["lists"]);

		// Pull subscribers that are part of this user's group(s) lists
		$group_lists_subscribers = adesk_sql_select_one("SELECT COUNT(*) FROM #subscriber_list WHERE listid IN ('$group_listids')");
		$group_lists_subscribers_status = ( $group_lists_subscribers > 0 ) ? 0 : 1;

		// Pull subscription forms that are part of user's group(s) lists
		$group_lists_forms = adesk_sql_select_one("SELECT COUNT(*) FROM #form_list WHERE listid IN ('$group_listids')");
		$group_lists_forms_status = ( $group_lists_forms > 0 ) ? 0 : 1;

		// Pull campaigns that are part of user's group(s) lists
		$group_lists_campaigns = adesk_sql_select_one("SELECT COUNT(*) FROM #campaign_list WHERE listid IN ('$group_listids')");
		$group_lists_campaigns_status = ( $group_lists_campaigns > 0 ) ? 0 : 1;

		$group_reports_link_status = ($group_lists_campaigns > 0 && $this->admin["pg_startup_reports"]) ? 0 : 1;

		// Add up all the 0 and 1 values. If they're all 0, that means they are all dimmed
		// If just one is 1, then we have to show the entire div still
		$group_all = $group_lists_status + $group_lists_subscribers_status + $group_lists_forms_status + $group_lists_campaigns_status + $group_reports_link_status;

		// 0 = hidden, 1 = show, 2 = hidden no matter what (they clicked the Close link)
		if ($group_all == 0) {
			// Flip it so it hides, if they're all dimmed
			$sql = adesk_sql_update("#group", array("pg_startup_gettingstarted" => 0), "id IN ($groupids) AND pg_startup_gettingstarted = 1");
		}
		else {
			// Or if they're not all dimmed, flip it so it shows
			$sql = adesk_sql_update("#group", array("pg_startup_gettingstarted" => 1), "id IN ($groupids) AND pg_startup_gettingstarted = 0");
		}

		$help_articles = array();
		$rss = adesk_rss_fetch('http://awebdesk.com/email-marketing/index.php?rss=1&filter=mostviewed', 86400);
		if ( $rss and isset($rss['rss']) and isset($rss['rss']->items) ) {
			$help_articles = array_slice($rss['rss']->items, 0, 10);
		}

		# Check on the startup page if innodb memory is too low.
		if (!adesk_sql_compare("innodb_buffer_pool_size", 1024*1024*64) && adesk_sql_supports_engine("InnoDB") && $group_lists_subscribers > 50000)
			$smarty->assign("innodb_lowmem", 64);

		# Check on the startup page if htaccess is being used
		if(isset($_SERVER['PHP_AUTH_USER']))
			$smarty->assign("httpauth_warning", 0);

		if ( function_exists('apache_get_modules') && in_array('mod_suphp', apache_get_modules()) )
			$smarty->assign("suphp_warning", 0);


//new version alert 
  if(NEW_VERSION_ALERT && file_exists(adesk_basedir() . '/cache/newversion.txt')) {			  
  $v = file_get_contents(adesk_basedir() . '/cache/newversion.txt');
   	$vapiurl = "http://customers.awebdesk.com/api/index.php?m=license&q=get_license_info&api_key=6512bd43d9caa6e02c990b0a82652dca&php=y";
		$phpv=@file_get_contents($vapiurl);
        $aemversion=unserialize($phpv);
		$latestversion =   $aemversion['version_decimal'];
		$moreinfo = "<a href=".$aemversion['release_url']." target=\"_blank\">Click here</a> for more info or to upgrade.";
    if($latestversion>$v)
	 {
		 
				$smarty->assign('newversionavailable', 1); 
				$smarty->assign('latestversion', $latestversion);  
				$smarty->assign('moreinfo', $moreinfo);  
	 }
 }
 

//startup chart limits
/*
Max limit
Used limits
used percentage
*/



		$smarty->assign('groupids', $groupids); 
		$smarty->assign('subscriberscount', $subscriberscount);
		$smarty->assign('listcounts', $listcounts);
		$smarty->assign('campcount', $campcount);
		$smarty->assign('totalemailc', $totalemailc);
		$smarty->assign('group_lists_status', $group_lists_status);
		$smarty->assign('group_lists_subscribers_status', $group_lists_subscribers_status);
		$smarty->assign('group_lists_forms_status', $group_lists_forms_status);
		$smarty->assign('group_lists_campaigns_status', $group_lists_campaigns_status);
		$smarty->assign('group_reports_link_status', $group_reports_link_status);
		$smarty->assign('help_articles', $help_articles);
		$smarty->assign("isWindows", strtoupper(substr(PHP_OS, 0, 3)) === 'WIN');
		$smarty->assign('content_template', 'startup.htm');
	}
}

?>