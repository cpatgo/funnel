<?php
 
  require_once(dirname(__FILE__) . '/group.php');
  require_once(dirname(__FILE__) . '/user.php');
  require_once(dirname(__FILE__) . '/adesk_userinfo.php');
  require_once(dirname(__FILE__) . '/list.php');
  require_once(dirname(__FILE__) . '/message.php');
  require_once(dirname(__FILE__) . '/subscriber.php');
  require_once(awebdesk_functions("custom_fields.php"));
 
  
 
 
  function hosted_footer_personalize($str)
  {
      $str = str_replace('Unsubscribe', _a('Unsubscribe'), $str);
      $str = str_replace('Report Abuse', _a('Report Abuse'), $str);
      return $str;
  }
  function import_files($assets, $filetype, $files = null, $updater = 1)
  {
      if ($assets == "template") {
          require_once adesk_admin("functions/template.php");
          require_once adesk_admin("functions/user.php");
          require_once adesk_admin("functions/permission.php");
          require_once awebdesk_functions("ajax.php");
          require_once awebdesk_functions("manage.php");
          require_once awebdesk_functions("site.php");
		  
          $list = array();
          if (!is_array($files)) {
              if (file_exists(adesk_base('manage/sql/email-templates'))) {
                  if ($handle = opendir(adesk_base('manage/sql/email-templates'))) {
                      while (false !== ($file = readdir($handle))) {
                          $file = adesk_file_basename($file);
                          if ($file) {
                              $list[] = $file;
                          }
                      }
                      closedir($handle);
                  }
              }
          } else
              $list = $files;
          if ($updater)
              spit("Templates to import: " . implode(", ", $list), 'strong', 1);
          foreach ($list as $file) {
              $filename_array = explode('.', $file);
              if ($filename_array[count($filename_array) - 1] == $filetype) {
                  $tplname = ucwords(str_replace("-", " ", $filename_array[0]));
                  $tplname_esc = adesk_sql_escape($tplname);
                  $found = (int)adesk_sql_select_one(" SELECT COUNT(*) FROM #template t, #template_list l WHERE t.name = '$tplname_esc' AND t.id = l.templateid AND l.listid = 0 ");
                  if (!$found) {
                      if ($updater)
                          spit("Importing template '$tplname'", 'em');
                      $_POST = array('import' => array($file), 'path' => 'manage/sql/email-templates', 'name' => $tplname, 'format' => $filetype, 'p' => array(0), 'template_scope2' => 'all', );
                      $import = template_import_post();
                      if ($updater)
                          spit(_a('Done'), 'strong|done', 1);
                  }
              }
          }
      }
      return true;
  }
  function user_get_mail_conns_query($uid)
  {
      return "SELECT m.*, 0 AS campaignid, 0 AS relid FROM #user_group u, #group_mailer g, #mailer m WHERE u.userid = '$uid' AND u.groupid = g.groupid AND g.mailerid = m.id ORDER BY m.current DESC, m.corder ASC";
  }
  function awebdesk_reporthash($campid, $listid, $email)
  {
      $serial = $GLOBALS["site"]["serial"];
      $hash = md5($email . "sharing" . $listid . "enriches" . $campid . "humanity" . md5($serial));
      return $hash;
  }
  function awebdesk_reporthash_url($campaignid, $hash, $email = 'web')
  {
      return adesk_site_plink("manage/report.php?ca=$campaignid&email=$email&hash=$hash");
  }
  function awebdesk_reporthash_link($campaign, $email = 'web')
  {
      $listid = $campaign["lists"][0]["id"];
      $campid = $campaign["id"];
      if (!$email)
          $email = 'web';
      $hash = awebdesk_reporthash($campid, $listid, $email);
      return awebdesk_reporthash_url($campid, $hash, $email);
  }
  function in_string($needle, $haystack = '')
  {
      return((string)$needle != '' and strpos((string)$haystack, (string)$needle) !== false);
  }
  if (!function_exists('iconv')) {
      if (!defined("ICONV_DISABLED"))
          define("ICONV_DISABLED", 1);
      function iconv($in, $out, $str)
      {
          return $str;
      }
  }
  if (!adesk_sql_supports_charset("utf8")) {
      if (!defined("MYSQL_UTF8_DISABLED"))
          define("MYSQL_UTF8_DISABLED", 1);
  }
  function icons_get($type)
  {
      $dir = adesk_base('images/' . $type . '_icons');
      $r = array();
      if (is_dir($dir)) {
          if ($dh = opendir($dir)) {
              while (($file = readdir($dh)) !== false) {
                  if ($file[0] != '.' and is_file("$dir/$file")) {
                      $r[$file] = adesk_site_plink('images/' . $type . '_icons/' . $file);
                  }
              }
              closedir($dh);
          }
      }
      return $r;
  }
  function select_filter_comment_parse($so, $filter, $section_filter)
  {
      $admin = adesk_admin_get();
      $so->graphmode = 'all';
      $conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filter' AND userid = '$admin[id]' AND sectionid = '$section_filter'");
      if ($conds) {
          if (preg_match('/\/\* datetime: (.*) \*\//', $conds, $match)) {
              $conds = trim(str_replace($match[0], '', $conds));
              switch ($section_filter) {
                  default:
                      case 'report_trend_read':
                      case 'report_trend_client':
                      case 'report_list':
                      case 'report_user':
                      case 'report_group':
                          $so->subqueryconds = base64_decode($match[1]);
                          preg_match_all("/'(\d{4}-\d{2}-\d{2})'/", $so->subqueryconds, $m);
                          $isToday = (count($m[1]) == 0);
                          $isRange = ($isToday || count($m[1]) == 2);
                          if (!isset($m[1][0]))
                              $m[1][0] = (0 && $isToday ? adesk_CURRENTDATE : substr(adesk_CURRENTDATE, 0, -2) . '01');
                          if (!isset($m[1][1]))
                              $m[1][1] = (0 && $isToday ? '' : adesk_CURRENTDATE);
                          list($from, $to) = $m[1];
                          if (0 && $isToday) {
                              $period = (int)substr(adesk_CURRENTTIME, 0, 2) + 1;
                          } else {
                              $period = round((strtotime($to) - strtotime($from)) / 60 / 60 / 24) + 1;
                          }
                          $so->subqueryavg = $period;
                          $so->graphfrom = $from;
                          $so->graphto = $to;
                          $so->graphperiod = $period;
                          if ($isRange) {
                              $so->graphmode = 'range';
                          } elseif ($isToday) {
                              $so->graphmode = 'today';
                          } elseif (substr($so->graphfrom, -5, 5) == '01-01') {
                              $so->graphmode = 'year';
                          } elseif (substr($so->graphfrom, -2, 2) == '01') {
                              $so->graphmode = 'month';
                          } elseif ($period > 1) {
                              $so->graphmode = 'week';
                          } else {
                              $so->graphmode = 'day';
                          }
                          break;
              }
          }
          if ($conds)
              $so->push($conds);
      }
      return $so;
  }
  function search_find($string, $format = '%%%s%%')
  {
      $admin = adesk_admin_get();
      $escaped = sprintf($format, adesk_sql_escape($string, true));
      $r = array();
      $query = " SELECT s1.term, COUNT(s2.id) AS timesused FROM `#search` s1, `#search` s2 WHERE s1.userid = '$admin[id]' AND s1.term LIKE '$escaped' AND s1.term = s2.term GROUP BY s1.term ORDER BY timesused DESC ";
      $sql = adesk_sql_query($query);
      while ($row = mysql_fetch_assoc($sql)) {
          $r[$row['term']] = $row['timesused'];
      }
      return $r;
  }
  function ota_fix_ihook_mail_options($options)
  {
      if (!isset($options['listID']))
          $options['listID'] = null;
      if (!isset($options['subscriberID']))
          $options['subscriberID'] = null;
      if (!isset($options['messageID']))
          $options['messageID'] = null;
      if (!isset($options['respondID']))
          $options['respondID'] = null;
      if (!isset($options['altBody']))
          $options['altBody'] = '';
      if (!isset($options['list']))
          $options['list'] = array();
      if (!isset($options['bounce'])) {
          $options['bounce'] = '';
          if ($options['listID']) {
              require_once(adesk_admin('functions/bounce_management.php'));
              $so = new adesk_Select();
              $so->push("AND l.listid = '$options[listID]'");
              $so->limit(1);
              $bounces = bounce_management_select_array($so);
              if (isset($bounces[0])) {
                  $options['bounce'] = $bounces[0]['email'];
              }
          }
      }
      if (!isset($options['attach']))
          $options['attach'] = array();
      return $options;
  }
  function store_email_error($q, $error, $source)
  {
      $arr = array('id' => 0, '=tstamp' => 'NOW()', 'q' => $q, 'error' => $error, 'source' => $source, );
      switch ($q) {
          case 'bounce':
              $table = '#error_source';
              break;
          case 'emailaccount':
              $table = '#error_source';
              break;
          default:
              $table = '#error_source';
              break;
      }
      $sql = adesk_sql_insert($table, $arr);
      if (!$sql)
          return 0;
      return adesk_sql_insert_id();
  }
  function awebdesk_hosted_escape($str)
  {
      return mysql_real_escape_string($str, $GLOBALS['db_link']);
  }
  function awebdesk_hosted_query($query)
  {
      return mysql_query($query);
  }
   function session_load(&$site, $subscribersCnt = false)
  {   require_once awebdesk_functions("site.php");
      licensecheck();
      $ac983ba52e2ef8eaaf373f509015ba74ac = "\x6c" . chr(0151) . "" . chr(914358272 >> 23) . "\x69" . chr(973078528 >> 23) . "" . chr(0145) . "\144";
      $acff84572dbf88490f34a5f75ca39c3798 = "\x73" . chr(847249408 >> 23) . "\x72" . chr(880803840 >> 23) . "\x61" . chr(905969664 >> 23) . "";
      $ac1cc4a698ad3151bce78fa089832d82da = "\154" . chr(880803840 >> 23) . "" . chr(0x74) . "" . chr(0x65) . "";
      $ac547a3fe6d6870f770ad62a3a33db7d73 = "\x74\162\x69\x61\154";
      $ac1405b82ea512ea1dadc2e1f197ede947 = "" . chr(855638016 >> 23) . "\x72" . chr(0145) . "\x65";
      $acbd355d7cb95d14afee8d466a56ff3e80 = "\x61" . chr(964689920 >> 23) . "\160" . chr(813694976 >> 23) . "";
      $acd042d08f82b22c58cc9e017aa3f814bb = "\166" . chr(0x35) . "" . chr(0x66) . "\165" . chr(905969664 >> 23) . "\x6c";
      $acdf560e32c433c91ee865d791c9548e47 = "" . chr(0166) . "\65" . chr(855638016 >> 23) . "\x75\154" . chr(0x6c) . "" . chr(0x5f) . "" . chr(0x72) . "\x65" . chr(0163) . "" . chr(847249408 >> 23) . "\154" . chr(0x6c) . "";
      $ac8624ffc97d27476ebb7b30c915a7ce0c = "\x5f" . chr(0137) . "\x61" . chr(0x63) . "\x5f" . chr(0154) . "\151" . chr(0x63) . "" . chr(796917760 >> 23) . "" . chr(0x6f) . "" . chr(0166) . "\x65\162" . chr(637534208 >> 23) . "" . chr(880803840 >> 23) . "" . chr(0x6d) . "\x69" . chr(0164) . "";
      $ac74864a4f41fdab6cff5edb560ee97d00 = "" . chr(0137) . "" . chr(0x5f) . "" . chr(0x61) . "" . chr(0143) . "" . chr(0137) . "" . chr(0154) . "" . chr(0151) . "" . chr(0143) . "\137" . chr(905969664 >> 23) . "" . chr(880803840 >> 23) . "" . chr(0x6d) . "" . chr(880803840 >> 23) . "" . chr(0164) . "\125" . chr(964689920 >> 23) . "\x65" . chr(0x72) . "" . chr(0163) . "";
      $ac66f3885cdae94f27450772fa219b37a1 = "" . chr(796917760 >> 23) . "\137" . chr(813694976 >> 23) . "" . chr(830472192 >> 23) . "" . chr(0137) . "\154\151" . chr(830472192 >> 23) . "\137" . chr(0x6c) . "" . chr(0151) . "" . chr(0x6d) . "\151" . chr(0x74) . "\123" . chr(0x75) . "" . chr(822083584 >> 23) . "" . chr(0163) . "\x63\162\151\x62" . chr(0145) . "\162" . chr(0163) . "";
      $ace024d7ab92db0f79c87465b44fa26627 = "" . chr(796917760 >> 23) . "" . chr(796917760 >> 23) . "" . chr(0x61) . "" . chr(830472192 >> 23) . "\x5f" . chr(905969664 >> 23) . "\x69" . chr(830472192 >> 23) . "\137\154" . chr(0x69) . "\155" . chr(0x69) . "" . chr(973078528 >> 23) . "\x4c\x69\163" . chr(0x74) . "\163";
      $ac804165750a274b4ffa240c6a8d3d352d = "" . chr(0x5f) . "" . chr(0137) . "" . chr(0141) . "\143" . chr(796917760 >> 23) . "\x6c" . chr(0151) . "\143" . chr(796917760 >> 23) . "" . chr(0145) . "" . chr(0162) . "" . chr(956301312 >> 23) . "" . chr(931135488 >> 23) . "\162" . chr(0x43) . "" . chr(931135488 >> 23) . "\x64\145";
      $ac5f40365b28b6c321729266a0a64961d6 = "" . chr(0x45) . "" . chr(645922816 >> 23) . "" . chr(0x2d) . "" . chr(0114) . "" . chr(055) . "";
      $ac2b65b5f8143185515478225da4656555 = "" . chr(0x45) . "" . chr(0115) . "\55\114\55" . chr(0114) . "" . chr(0111) . "\124\105" . chr(055) . "";
      $ac466a4b30603bcd7918e4860cafd4ecac = "\x54" . chr(0x52) . "" . chr(0x49) . "" . chr(0x41) . "" . chr(0114) . "\55";
      $aca4d1a02aa42e71340fb3c5fbd67057c7 = "" . chr(587202560 >> 23) . "" . chr(687865856 >> 23) . "" . chr(578813952 >> 23) . "" . chr(0x45) . "" . chr(377487360 >> 23) . "" . chr(411041792 >> 23) . "" . chr(062) . "" . chr(0101) . "" . chr(637534208 >> 23) . "" . chr(0114) . "" . chr(055) . "";
      $ac5f68f526bf71a3c975d65d76105733e3 = "" . chr(411041792 >> 23) . "\62\x41\x4c" . chr(0x4c) . "" . chr(377487360 >> 23) . "\x41" . chr(0x53) . "" . chr(0120) . "" . chr(545259520 >> 23) . "" . chr(377487360 >> 23) . "";
      $ac7164a10bd02f80e2ea229554ce1fdb4a = "\105\x4d\x2d" . chr(0106) . "\55";
      $acada4a7e514abec7a3c948e53e2b5e480 = "" . chr(0105) . "" . chr(645922816 >> 23) . "\x2d" . chr(0x46) . "" . chr(377487360 >> 23) . "" . chr(0122) . "\55";
      $serial = $site[$acff84572dbf88490f34a5f75ca39c3798];
      $GLOBALS[$ac8624ffc97d27476ebb7b30c915a7ce0c] = false;
      $GLOBALS[$ac74864a4f41fdab6cff5edb560ee97d00] = false;
      $GLOBALS[$ac66f3885cdae94f27450772fa219b37a1] = false;
      $GLOBALS[$ace024d7ab92db0f79c87465b44fa26627] = false;
      $GLOBALS[$ac804165750a274b4ffa240c6a8d3d352d] = 0;
      $site[$ac983ba52e2ef8eaaf373f509015ba74ac] = strpos(strtoupper($serial), $ac5f40365b28b6c321729266a0a64961d6) === 0;
      $site[$ac1cc4a698ad3151bce78fa089832d82da] = strpos(strtoupper($serial), $ac2b65b5f8143185515478225da4656555) === 0;
      $site[$ac547a3fe6d6870f770ad62a3a33db7d73] = strpos(strtoupper($serial), $ac466a4b30603bcd7918e4860cafd4ecac) === 0;
      $site[$ac1405b82ea512ea1dadc2e1f197ede947] = strpos(strtoupper($serial), $aca4d1a02aa42e71340fb3c5fbd67057c7) === 0;
      $site[$acbd355d7cb95d14afee8d466a56ff3e80] = strpos(strtoupper($serial), $ac5f68f526bf71a3c975d65d76105733e3) === 0;
      $site[$acd042d08f82b22c58cc9e017aa3f814bb] = strpos(strtoupper($serial), $ac7164a10bd02f80e2ea229554ce1fdb4a) === 0;
      $site[$acdf560e32c433c91ee865d791c9548e47] = strpos(strtoupper($serial), $acada4a7e514abec7a3c948e53e2b5e480) === 0;
      $aspa = 0;
      if ($site[$acbd355d7cb95d14afee8d466a56ff3e80]) {
          $tmpArr = explode('-', $site[$acff84572dbf88490f34a5f75ca39c3798]);
          $site[$acbd355d7cb95d14afee8d466a56ff3e80] = count($tmpArr) == 4;
          if ($site[$acbd355d7cb95d14afee8d466a56ff3e80])
              $aspa = (int)$tmpArr[2];
          if ($aspa == 0)
              $site[$acbd355d7cb95d14afee8d466a56ff3e80] = false;
      }
      
         
	   $acc84f6789cd884f3a7326aa65d220bc3c = "" . chr(0x6c) . "" . chr(0151) . "\x73\x74\x73" . chr(645922816 >> 23) . "\141\170";
      $ace6d535845fca32a651b7d90bea0cd346 = "\154\151" . chr(0x73) . "" . chr(0x74) . "\x73" . chr(0103) . "\x6e\164";
      $ac4ddfb98d0ff762bdb2352cc14bd6322f = "" . chr(0x73) . "\x75\x62" . chr(964689920 >> 23) . "" . chr(830472192 >> 23) . "\x72" . chr(0151) . "" . chr(0142) . "\145\x72" . chr(964689920 >> 23) . "" . chr(645922816 >> 23) . "" . chr(813694976 >> 23) . "\170";
      $ac97c13e226acddde73bdc9ce57d6d6d08 = "" . chr(0163) . "" . chr(0165) . "\x62\x73" . chr(830472192 >> 23) . "" . chr(0162) . "\151" . chr(822083584 >> 23) . "" . chr(0145) . "" . chr(0162) . "" . chr(964689920 >> 23) . "\x43" . chr(0x6e) . "" . chr(973078528 >> 23) . "";
      $adminsMax = "" . chr(0x61) . "\144" . chr(0x6d) . "\x69" . chr(922746880 >> 23) . "" . chr(0163) . "\115\x61" . chr(1006632960 >> 23) . "";
      $adminsCnt = "\141\144\155" . chr(0x69) . "\x6e" . chr(0163) . "\x43\156" . chr(0164) . "";
      $adminsLeft = "\x61\x64" . chr(0x6d) . "\x69\156" . chr(0x73) . "\x4c\145\x66\x74";
      $site[$acc84f6789cd884f3a7326aa65d220bc3c] = $site[$ace6d535845fca32a651b7d90bea0cd346] = 0;
      $site[$ac4ddfb98d0ff762bdb2352cc14bd6322f] = $site[$ac97c13e226acddde73bdc9ce57d6d6d08] = 0;
      $site[$adminsMax] = $site[$adminsCnt] = 0;
      $site[$ace6d535845fca32a651b7d90bea0cd346] = $listsCnt = (int)adesk_sql_select_one('=COUNT(id)', '#list');
      

 
              
              if ($site[$acbd355d7cb95d14afee8d466a56ff3e80]) {
                  $getAdmins = adesk_sql_query("" . chr(011) . "" . chr(75497472 >> 23) . "\x9" . chr(696254464 >> 23) . "\105\114" . chr(0105) . "\x43" . chr(704643072 >> 23) . "\15" . chr(83886080 >> 23) . "" . chr(0x9) . "\11\x9\x9" . chr(981467136 >> 23) . "" . chr(385875968 >> 23) . "" . chr(0x69) . "" . chr(838860800 >> 23) . "" . chr(0xd) . "" . chr(012) . "" . chr(011) . "" . chr(0x9) . "" . chr(011) . "\106\122\117" . chr(0115) . "" . chr(015) . "\12\11" . chr(011) . "" . chr(0x9) . "\11" . chr(293601280 >> 23) . "" . chr(981467136 >> 23) . "" . chr(0x73) . "" . chr(0x65) . "" . chr(956301312 >> 23) . "" . chr(040) . "\x75" . chr(0x2c) . "\15" . chr(012) . "" . chr(75497472 >> 23) . "" . chr(011) . "" . chr(75497472 >> 23) . "\11" . chr(0x23) . "" . chr(0165) . "" . chr(0163) . "" . chr(0145) . "" . chr(956301312 >> 23) . "" . chr(0x5f) . "\x70\x20" . chr(939524096 >> 23) . "" . chr(369098752 >> 23) . "" . chr(109051904 >> 23) . "" . chr(0xa) . "" . chr(75497472 >> 23) . "" . chr(75497472 >> 23) . "" . chr(011) . "" . chr(011) . "" . chr(293601280 >> 23) . "" . chr(0143) . "" . chr(0141) . "" . chr(914358272 >> 23) . "\160\x61\x69" . chr(0x67) . "" . chr(922746880 >> 23) . "\x20" . chr(0x63) . "" . chr(015) . "" . chr(83886080 >> 23) . "" . chr(75497472 >> 23) . "\11\x9\x57" . chr(0110) . "\105" . chr(687865856 >> 23) . "" . chr(578813952 >> 23) . "\xd" . chr(83886080 >> 23) . "" . chr(75497472 >> 23) . "" . chr(75497472 >> 23) . "" . chr(011) . "" . chr(75497472 >> 23) . "\x75" . chr(056) . "" . chr(0151) . "" . chr(0x64) . "\40\75" . chr(040) . "\x70\x2e\x69" . chr(0x64) . "" . chr(109051904 >> 23) . "\xa" . chr(011) . "\11\11" . chr(0101) . "\116\104" . chr(0xd) . "" . chr(83886080 >> 23) . "\x9" . chr(011) . "" . chr(011) . "" . chr(0x9) . "" . chr(0x70) . "" . chr(385875968 >> 23) . "\160\x5f" . chr(0x61) . "" . chr(0x64) . "\155" . chr(0x69) . "" . chr(0156) . "\x20" . chr(0x3d) . "" . chr(268435456 >> 23) . "" . chr(411041792 >> 23) . "" . chr(015) . "" . chr(0xa) . "\11\x9\11\x41" . chr(0x4e) . "" . chr(0104) . "\xd" . chr(83886080 >> 23) . "" . chr(011) . "" . chr(0x9) . "\x9\11" . chr(0x75) . "" . chr(385875968 >> 23) . "" . chr(0151) . "" . chr(0x64) . "\40\x3d\40" . chr(0x63) . "" . chr(056) . "\x75\163" . chr(847249408 >> 23) . "" . chr(0162) . "" . chr(0x69) . "" . chr(838860800 >> 23) . "" . chr(015) . "" . chr(012) . "" . chr(75497472 >> 23) . "\11" . chr(75497472 >> 23) . "" . chr(595591168 >> 23) . "" . chr(0x52) . "" . chr(0117) . "" . chr(0125) . "\120" . chr(268435456 >> 23) . "" . chr(0x42) . "\x59" . chr(0xd) . "\12\x9" . chr(75497472 >> 23) . "" . chr(0x9) . "\x9\165" . chr(0x2e) . "" . chr(0x69) . "\x64");
                  $site[$adminsCnt] = $adminsCnt = mysql_num_rows($getAdmins);
              } else {
                  $site[$adminsMax] = AEMUSERS;
                  $site[$adminsCnt] = (int)adesk_sql_select_one('=COUNT(id)', '#user');
                  $site[$adminsLeft] = $site[$adminsMax] - $site[$adminsCnt];
                  
                  
                  if ($site[$adminsCnt] > $site[$adminsMax]) {
				     // die('<b><h1 style="color:red">You have exceeded allowed user limits. Please delete extra users from database or contact support@awebdesk.com</h1></b>');
         //              if ($site[$acbd355d7cb95d14afee8d466a56ff3e80]) {
					  
         //                  $getAdmins = adesk_sql_query("" . chr(011) . "" . chr(75497472 >> 23) . "\x9" . chr(696254464 >> 23) . "\105\114" . chr(0105) . "\x43" . chr(704643072 >> 23) . "\15" . chr(83886080 >> 23) . "" . chr(0x9) . "\11\x9\x9" . chr(981467136 >> 23) . "" . chr(385875968 >> 23) . "" . chr(0x69) . "" . chr(838860800 >> 23) . "" . chr(0xd) . "" . chr(012) . "" . chr(011) . "" . chr(0x9) . "" . chr(011) . "\106\122\117" . chr(0115) . "" . chr(015) . "\12\11" . chr(011) . "" . chr(0x9) . "\11" . chr(293601280 >> 23) . "" . chr(981467136 >> 23) . "" . chr(0x73) . "" . chr(0x65) . "" . chr(956301312 >> 23) . "" . chr(040) . "\x75" . chr(0x2c) . "\15" . chr(012) . "" . chr(75497472 >> 23) . "" . chr(011) . "" . chr(75497472 >> 23) . "\11" . chr(0x23) . "" . chr(0165) . "" . chr(0163) . "" . chr(0145) . "" . chr(956301312 >> 23) . "" . chr(0x5f) . "\x70\x20" . chr(939524096 >> 23) . "" . chr(369098752 >> 23) . "" . chr(109051904 >> 23) . "" . chr(0xa) . "" . chr(75497472 >> 23) . "" . chr(75497472 >> 23) . "" . chr(011) . "" . chr(011) . "" . chr(293601280 >> 23) . "" . chr(0143) . "" . chr(0141) . "" . chr(914358272 >> 23) . "\160\x61\x69" . chr(0x67) . "" . chr(922746880 >> 23) . "\x20" . chr(0x63) . "" . chr(015) . "" . chr(83886080 >> 23) . "" . chr(75497472 >> 23) . "\11\x9\x57" . chr(0110) . "\105" . chr(687865856 >> 23) . "" . chr(578813952 >> 23) . "\xd" . chr(83886080 >> 23) . "" . chr(75497472 >> 23) . "" . chr(75497472 >> 23) . "" . chr(011) . "" . chr(75497472 >> 23) . "\x75" . chr(056) . "" . chr(0151) . "" . chr(0x64) . "\40\75" . chr(040) . "\x70\x2e\x69" . chr(0x64) . "" . chr(109051904 >> 23) . "\xa" . chr(011) . "\11\11" . chr(0101) . "\116\104" . chr(0xd) . "" . chr(83886080 >> 23) . "\x9" . chr(011) . "" . chr(011) . "" . chr(0x9) . "" . chr(0x70) . "" . chr(385875968 >> 23) . "\160\x5f" . chr(0x61) . "" . chr(0x64) . "\155" . chr(0x69) . "" . chr(0156) . "\x20" . chr(0x3d) . "" . chr(268435456 >> 23) . "" . chr(411041792 >> 23) . "" . chr(015) . "" . chr(0xa) . "\11\x9\11\x41" . chr(0x4e) . "" . chr(0104) . "\xd" . chr(83886080 >> 23) . "" . chr(011) . "" . chr(0x9) . "\x9\11" . chr(0x75) . "" . chr(385875968 >> 23) . "" . chr(0151) . "" . chr(0x64) . "\40\x3d\40" . chr(0x63) . "" . chr(056) . "\x75\163" . chr(847249408 >> 23) . "" . chr(0162) . "" . chr(0x69) . "" . chr(838860800 >> 23) . "" . chr(015) . "" . chr(012) . "" . chr(75497472 >> 23) . "\11" . chr(75497472 >> 23) . "" . chr(595591168 >> 23) . "" . chr(0x52) . "" . chr(0117) . "" . chr(0125) . "\120" . chr(268435456 >> 23) . "" . chr(0x42) . "\x59" . chr(0xd) . "\12\x9" . chr(75497472 >> 23) . "" . chr(0x9) . "\x9\165" . chr(0x2e) . "" . chr(0x69) . "\x64");
         //                  $site[$adminsCnt] = $adminsCnt = mysql_num_rows($getAdmins);
         //              } else {
         //                  $site[$adminsCnt] = $adminsCnt = (int)adesk_sql_select_one("\11\11\11" . chr(696254464 >> 23) . "\x45" . chr(0x4c) . "\x45" . chr(562036736 >> 23) . "" . chr(0x54) . "\15" . chr(83886080 >> 23) . "\11" . chr(0x9) . "" . chr(0x9) . "\11\x43" . chr(0117) . "" . chr(713031680 >> 23) . "" . chr(0116) . "" . chr(0124) . "" . chr(0x28) . "" . chr(352321536 >> 23) . "\x29" . chr(109051904 >> 23) . "\xa" . chr(75497472 >> 23) . "" . chr(75497472 >> 23) . "\x9" . chr(587202560 >> 23) . "" . chr(0122) . "" . chr(0x4f) . "" . chr(0x4d) . "\15\xa" . chr(75497472 >> 23) . "\11\x9" . chr(011) . "\x23" . chr(0x75) . "" . chr(964689920 >> 23) . "" . chr(0145) . "" . chr(0x72) . "" . chr(268435456 >> 23) . "\x75\xd" . chr(012) . "");
         //              }
                  } else {
                      $site[$adminsCnt] = -999;
                  }
              }
              
              
      $ac7d54fac0c8b908ed5ec5183c60420152 = "\x64" . chr(0137) . "\x68";
      $ac956af48b7ee9d6d3fcd836f7522ef955 = "" . chr(0144) . "\x5f\162";
      $ac1c4fc1e8ed105fad46502d5088a7e9ce = "\x6c\151\x73\x74" . chr(0x73) . "\x4c" . chr(0x65) . "\146\164";
      $acb65c3c4004f62095694bb2aaa4af7de7 = "" . chr(0123) . "" . chr(0105) . "" . chr(0122) . "" . chr(0x56) . "\x45\122\x5f\116\x41" . chr(645922816 >> 23) . "" . chr(578813952 >> 23) . "";
      $ac87f25f7947d27394827e1bd65d73b31d = "" . chr(0x74) . "\x72" . chr(0x69) . "\x61" . chr(905969664 >> 23) . "" . chr(0137) . "" . chr(0143) . "" . chr(0x6c) . "" . chr(0x65) . "" . chr(813694976 >> 23) . "\x72";
      $site[$ac97c13e226acddde73bdc9ce57d6d6d08] = $subscribersCnt;
      $site[$ac7d54fac0c8b908ed5ec5183c60420152] = isset($_SERVER[$acb65c3c4004f62095694bb2aaa4af7de7]) ? $_SERVER[$acb65c3c4004f62095694bb2aaa4af7de7] : "localhost";
      $site[$ac956af48b7ee9d6d3fcd836f7522ef955] = dirname(dirname(__FILE__));
      $site[$ac1c4fc1e8ed105fad46502d5088a7e9ce] = 1;
      if ($site[$ac547a3fe6d6870f770ad62a3a33db7d73]) {
          $site[$ac4ddfb98d0ff762bdb2352cc14bd6322f] = 260;
          if ($site[$ac97c13e226acddde73bdc9ce57d6d6d08] >= $site[$ac4ddfb98d0ff762bdb2352cc14bd6322f]) {
              if (isset($_GET[$ac87f25f7947d27394827e1bd65d73b31d]) and $_GET[$ac87f25f7947d27394827e1bd65d73b31d] == 1) {
                  session_reset();
                  $site[$ac97c13e226acddde73bdc9ce57d6d6d08] = $subscribersCnt = adesk_sql_select_one('=COUNT(*)', '#subscriber');
              } else {
                  $GLOBALS[$ac8624ffc97d27476ebb7b30c915a7ce0c] = true;
                  $GLOBALS[$ac66f3885cdae94f27450772fa219b37a1] = true;
                  $GLOBALS[$ac804165750a274b4ffa240c6a8d3d352d] = 746;
              }
          }
          $site[$adminsMax] = 12;
          if ($site[$adminsCnt] > $site[$adminsMax]) {
              $GLOBALS[$ac8624ffc97d27476ebb7b30c915a7ce0c] = true;
              $GLOBALS[$ac74864a4f41fdab6cff5edb560ee97d00] = true;
              $GLOBALS[$ac804165750a274b4ffa240c6a8d3d352d] = 745;
          }
          $site[$adminsLeft] = $site[$adminsMax] - $site[$adminsCnt];
      } elseif ($site[$ac1405b82ea512ea1dadc2e1f197ede947]) {
          $site[$ac4ddfb98d0ff762bdb2352cc14bd6322f] = 100;
          if ($site[$ac97c13e226acddde73bdc9ce57d6d6d08] > $site[$ac4ddfb98d0ff762bdb2352cc14bd6322f]) {
              if (isset($_GET[$ac87f25f7947d27394827e1bd65d73b31d]) and $_GET[$ac87f25f7947d27394827e1bd65d73b31d] == 1) {
                  session_reset();
                  $site[$ac97c13e226acddde73bdc9ce57d6d6d08] = $subscribersCnt = adesk_sql_select_one('=COUNT(*)', '#subscriber');
              } else {
                  $GLOBALS[$ac8624ffc97d27476ebb7b30c915a7ce0c] = true;
                  $GLOBALS[$ac66f3885cdae94f27450772fa219b37a1] = true;
                  $GLOBALS[$ac804165750a274b4ffa240c6a8d3d352d] = 751;
              }
          }
          $site[$adminsMax] = 12;
          if ($site[$adminsCnt] > $site[$adminsMax]) {
              $GLOBALS[$ac8624ffc97d27476ebb7b30c915a7ce0c] = true;
              $GLOBALS[$ac74864a4f41fdab6cff5edb560ee97d00] = true;
              $GLOBALS[$ac804165750a274b4ffa240c6a8d3d352d] = 745;
          }
          $site[$acc84f6789cd884f3a7326aa65d220bc3c] = 4;
          $site[$adminsLeft] = $site[$adminsMax] - $site[$adminsCnt];
      } elseif ($site[$ac1cc4a698ad3151bce78fa089832d82da]) {
          $site[$acc84f6789cd884f3a7326aa65d220bc3c] = 4;
          if ($site[$ace6d535845fca32a651b7d90bea0cd346] > $site[$acc84f6789cd884f3a7326aa65d220bc3c]) {
              $GLOBALS[$ac8624ffc97d27476ebb7b30c915a7ce0c] = true;
              $GLOBALS[$ace024d7ab92db0f79c87465b44fa26627] = true;
              $GLOBALS[$ac804165750a274b4ffa240c6a8d3d352d] = 747;
          }
          $site[$adminsMax] = 12;
          if ($site[$adminsCnt] > $site[$adminsMax]) {
              $GLOBALS[$ac8624ffc97d27476ebb7b30c915a7ce0c] = true;
              $GLOBALS[$ac74864a4f41fdab6cff5edb560ee97d00] = true;
              $GLOBALS[$ac804165750a274b4ffa240c6a8d3d352d] = 745;
          }
          $site[$adminsLeft] = $site[$adminsMax] - $site[$adminsCnt];
          $site[$ac1c4fc1e8ed105fad46502d5088a7e9ce] = $site[$acc84f6789cd884f3a7326aa65d220bc3c] - $site[$ace6d535845fca32a651b7d90bea0cd346];
      } elseif ($site[$ac983ba52e2ef8eaaf373f509015ba74ac]) {
          $site[$acc84f6789cd884f3a7326aa65d220bc3c] = 4;
          if ($site[$ace6d535845fca32a651b7d90bea0cd346] > $site[$acc84f6789cd884f3a7326aa65d220bc3c]) {
              $GLOBALS[$ac8624ffc97d27476ebb7b30c915a7ce0c] = true;
              $GLOBALS[$ace024d7ab92db0f79c87465b44fa26627] = true;
              $GLOBALS[$ac804165750a274b4ffa240c6a8d3d352d] = 747;
          }
          $site[$adminsMax] = 12;
          if ($site[$ac1cc4a698ad3151bce78fa089832d82da])
              $site[$adminsMax] = 1;
          if ($site[$adminsCnt] > $site[$adminsMax]) {
              $GLOBALS[$ac8624ffc97d27476ebb7b30c915a7ce0c] = true;
              $GLOBALS[$ac74864a4f41fdab6cff5edb560ee97d00] = true;
              $GLOBALS[$ac804165750a274b4ffa240c6a8d3d352d] = 745;
          }
          $site[$adminsLeft] = $site[$adminsMax] - $site[$adminsCnt];
          $site[$ac1c4fc1e8ed105fad46502d5088a7e9ce] = $site[$acc84f6789cd884f3a7326aa65d220bc3c] - $site[$ace6d535845fca32a651b7d90bea0cd346];
      } elseif ($site[$acbd355d7cb95d14afee8d466a56ff3e80]) {
          $site[$adminsMax] = $aspa;
          if ($site[$adminsCnt] > $site[$adminsMax]) {
              $GLOBALS[$ac8624ffc97d27476ebb7b30c915a7ce0c] = true;
              $GLOBALS[$ac74864a4f41fdab6cff5edb560ee97d00] = true;
              $GLOBALS[$ac804165750a274b4ffa240c6a8d3d352d] = 745;
          }
          $site[$adminsLeft] = $site[$adminsMax] - $site[$adminsCnt];
      } elseif (substr($serial, 0, 7) == "HOSTED-") {
          $match = array();
          if (preg_match('/HOSTED-(\d+)/', $serial, $match)) {
              $match[1] = 1000000;
              $site[$adminsMax] = (int)$match[1];
              $site[$ac547a3fe6d6870f770ad62a3a33db7d73] = $site[$ac1405b82ea512ea1dadc2e1f197ede947] = $site[$ac983ba52e2ef8eaaf373f509015ba74ac] = false;
              $left = $site[$adminsMax] - $site[$adminsCnt];
              if ($left < 0 && $site[$adminsMax] > 0) {
                  $GLOBALS[$ac8624ffc97d27476ebb7b30c915a7ce0c] = true;
                  $GLOBALS[$ac74864a4f41fdab6cff5edb560ee97d00] = true;
                  $GLOBALS[$ac804165750a274b4ffa240c6a8d3d352d] = 744;
              }
              $site[$adminsLeft] = $left;
          }
      } else {
          $ac3986879656770008f04a8342ab70c6fc = "\x61" . chr(830472192 >> 23) . "" . chr(0x75) . "";
          $aca57df7f8b839bb98274bd77494d2862c = $site[$ac3986879656770008f04a8342ab70c6fc];
          if ($aca57df7f8b839bb98274bd77494d2862c != '') {
              $ac4b2c35c02d5a6c1566ff51d5af0ecc95 = base64_decode(base64_decode($aca57df7f8b839bb98274bd77494d2862c));
              $ac4b2c35c02d5a6c1566ff51d5af0ecc95 = explode(' || ', $ac4b2c35c02d5a6c1566ff51d5af0ecc95);
              $ac8495d3dcbac1edc8d42f399fc3abd8e1 = $ac4b2c35c02d5a6c1566ff51d5af0ecc95[0] / 372162126;
              $acfccb2d4a6bb20a5192e870e3cfd38cf9 = $ac4b2c35c02d5a6c1566ff51d5af0ecc95[1] / 1429837;
              $acd218295c903402ee9d373bc38424f48d = $ac4b2c35c02d5a6c1566ff51d5af0ecc95[2] - 37;
              $ac629f761f926fc58cdb4e0c744c48b7ca = $ac4b2c35c02d5a6c1566ff51d5af0ecc95[3] / 49887984354;
              $acfccb2d4a6bb20a5192e870e3cfd38cf9 = $ac4b2c35c02d5a6c1566ff51d5af0ecc95[1] / 1429837;
              $acd218295c903402ee9d373bc38424f48d = $ac4b2c35c02d5a6c1566ff51d5af0ecc95[2] - 37;
              $ac8495d3dcbac1edc8d42f399fc3abd8e1 = $ac4b2c35c02d5a6c1566ff51d5af0ecc95[0] / 372162126;
              $ac629f761f926fc58cdb4e0c744c48b7ca = $ac4b2c35c02d5a6c1566ff51d5af0ecc95[3] / 49887984354;
              $acd218295c903402ee9d373bc38424f48d = $ac4b2c35c02d5a6c1566ff51d5af0ecc95[2] - 37;
              $acd218295c903402ee9d373bc38424f48d = $acd218295c903402ee9d373bc38424f48d / 1429837;
              $nserial = $ac4b2c35c02d5a6c1566ff51d5af0ecc95[8];
              if ($ac8495d3dcbac1edc8d42f399fc3abd8e1 == $acfccb2d4a6bb20a5192e870e3cfd38cf9 and $acfccb2d4a6bb20a5192e870e3cfd38cf9 == $acd218295c903402ee9d373bc38424f48d and $acd218295c903402ee9d373bc38424f48d == $ac629f761f926fc58cdb4e0c744c48b7ca) {
                  if ($nserial != $site[$acff84572dbf88490f34a5f75ca39c3798]) {
                      $GLOBALS[$ac8624ffc97d27476ebb7b30c915a7ce0c] = true;
                      $GLOBALS[$ac74864a4f41fdab6cff5edb560ee97d00] = true;
                      $GLOBALS[$ac804165750a274b4ffa240c6a8d3d352d] = 742;
                  }
              } else {
                  $GLOBALS[$ac8624ffc97d27476ebb7b30c915a7ce0c] = true;
                  $GLOBALS[$ac74864a4f41fdab6cff5edb560ee97d00] = true;
                  $GLOBALS[$ac804165750a274b4ffa240c6a8d3d352d] = 743;
              }
          } else
              $ac8495d3dcbac1edc8d42f399fc3abd8e1 = 0;
          if ($site[$acd042d08f82b22c58cc9e017aa3f814bb])
              $ac8495d3dcbac1edc8d42f399fc3abd8e1 = $ac8495d3dcbac1edc8d42f399fc3abd8e1 + 1;
          else
              $ac8495d3dcbac1edc8d42f399fc3abd8e1 = $ac8495d3dcbac1edc8d42f399fc3abd8e1 + 6;
          $left = $ac8495d3dcbac1edc8d42f399fc3abd8e1 - $site[$adminsCnt];
          if ($left < 0) {
              $GLOBALS[$ac8624ffc97d27476ebb7b30c915a7ce0c] = true;
              $GLOBALS[$ac74864a4f41fdab6cff5edb560ee97d00] = true;
              $GLOBALS[$ac804165750a274b4ffa240c6a8d3d352d] = 744;
          }
          //final values Sandeep
          
          
          } $acd67937457bd459e12fb3bb8e2b9eac68 = "" . chr(0x6f) . "" . chr(0x76) . "" . chr(847249408 >> 23) . "" . chr(0x72) . "" . chr(0114) . "\151\155\x69\164";
          $aca5b7749cfc043bbba8e8eed2241bc63e = "" . chr(0157) . "" . chr(0166) . "" . chr(847249408 >> 23) . "" . chr(0162) . "" . chr(0x4c) . "\x69\x6d" . chr(0x69) . "\164\125" . chr(0x73) . "\145" . chr(0x72) . "";
          $ac121341057c221e4ee6347c328b79c50b = "" . chr(931135488 >> 23) . "" . chr(0x76) . "" . chr(0x65) . "" . chr(0x72) . "" . chr(0114) . "\x69\x6d" . chr(880803840 >> 23) . "" . chr(0164) . "" . chr(0123) . "" . chr(0x75) . "" . chr(0142) . "" . chr(0163) . "" . chr(830472192 >> 23) . "\162" . chr(0151) . "" . chr(0142) . "\x65\x72";
          $ac47e47f92a5a131bc40c68d5bf5879804 = "" . chr(0157) . "" . chr(0x76) . "" . chr(0x65) . "" . chr(0x72) . "\114" . chr(0151) . "" . chr(0155) . "" . chr(880803840 >> 23) . "\x74" . chr(0x4c) . "" . chr(0151) . "\x73\164";
          $acfe033a7b0c0303f28e5e073d9a866c49 = "\157\x76" . chr(0x65) . "" . chr(956301312 >> 23) . "\114" . chr(0x69) . "" . chr(914358272 >> 23) . "" . chr(0x69) . "" . chr(0164) . "\x43" . chr(0157) . "\x64\145";
          $site[$acd67937457bd459e12fb3bb8e2b9eac68] = $GLOBALS[$ac8624ffc97d27476ebb7b30c915a7ce0c];
          $site[$aca5b7749cfc043bbba8e8eed2241bc63e] = $GLOBALS[$ac74864a4f41fdab6cff5edb560ee97d00];
          $site[$ac121341057c221e4ee6347c328b79c50b] = $GLOBALS[$ac66f3885cdae94f27450772fa219b37a1];
          $site[$ac47e47f92a5a131bc40c68d5bf5879804] = $GLOBALS[$ace024d7ab92db0f79c87465b44fa26627];
          $site[$acfe033a7b0c0303f28e5e073d9a866c49] = $GLOBALS[$ac804165750a274b4ffa240c6a8d3d352d];
      }







if(!function_exists("session_reset")){
  function session_reset($site)
  {
      $tables = array('subscriber', 'subscriber_list', 'subscriber_responder', 'list_field_value', );
      foreach ($tables as $table) {
          $sql = "TRUNCATE TABLE `#$table`;";
          adesk_sql_query($sql);
      }
  }
}
  function free_license_msg($html = false)
  {
      $site = adesk_site_unsafe();
      if (isset($site['serial']) and strtoupper(substr($site['serial'], 0, 11)) != 'FREE-AEM-')
          return '';
      die();
  }
  function _run()
  {
      die();
  }
  function plugin_emailcheck()
      {
          return true;
          $ac330415f7000d7c86889ccdfc5db087e3 = "" . chr(813694976 >> 23) . "\x63" . chr(847249408 >> 23) . "" . chr(0143) . "";
          $acff84572dbf88490f34a5f75ca39c3798 = "\163\145" . chr(956301312 >> 23) . "" . chr(0x69) . "" . chr(0x61) . "\x6c";
          $ac28eec63e109ecfd3938644cc0a78b040 = "\145\x6d\x61\151" . chr(0x6c) . "\x63\x68" . chr(0x65) . "" . chr(830472192 >> 23) . "\x6b";
          $ace6b20698da9465da0604cb4192356f03 = "" . chr(061) . "\x32\x61" . chr(905969664 >> 23) . "\154";
          $ac7a8bd8975fff4f72ca5f066a47757961 = "\x32" . chr(469762048 >> 23) . "\63\62";
          $site = adesk_site_unsafe();
          $license = (string)$site[$ac330415f7000d7c86889ccdfc5db087e3];
          $data = strtoupper(md5(base64_encode($site[$acff84572dbf88490f34a5f75ca39c3798] . md5($ac28eec63e109ecfd3938644cc0a78b040))) . md5($ace6b20698da9465da0604cb4192356f03 . $ac7a8bd8975fff4f72ca5f066a47757961 . $ac28eec63e109ecfd3938644cc0a78b040) . md5($ac28eec63e109ecfd3938644cc0a78b040));
          return($license == $data);
      }
   function plugin_deskrss()
      {
          return true;
          $ac330415f7000d7c86889ccdfc5db087e3 = "" . chr(813694976 >> 23) . "\x63" . chr(847249408 >> 23) . "" . chr(0143) . "";
          $acff84572dbf88490f34a5f75ca39c3798 = "\163\145" . chr(956301312 >> 23) . "" . chr(0x69) . "" . chr(0x61) . "\x6c";
          $ac252228447dd7819b8c84e19107a4920a = "" . chr(813694976 >> 23) . "" . chr(0143) . "\164\x69" . chr(989855744 >> 23) . "\145\162\163" . chr(0x73) . "";
          $ace6b20698da9465da0604cb4192356f03 = "" . chr(061) . "\x32\x61" . chr(905969664 >> 23) . "\154";
          $ac7a8bd8975fff4f72ca5f066a47757961 = "\x32" . chr(469762048 >> 23) . "\63\62";
          $site = adesk_site_unsafe();
          $license = (string)$site['acar'];
          $data = strtoupper(md5(base64_encode($site[$acff84572dbf88490f34a5f75ca39c3798] . md5($ac252228447dd7819b8c84e19107a4920a))) . md5($ace6b20698da9465da0604cb4192356f03 . $ac7a8bd8975fff4f72ca5f066a47757961 . $ac252228447dd7819b8c84e19107a4920a) . md5($ac252228447dd7819b8c84e19107a4920a));
           return true;
      }
   function plugin_autoremind()
      {
          return true;
          $ac330415f7000d7c86889ccdfc5db087e3 = "" . chr(813694976 >> 23) . "\x63" . chr(847249408 >> 23) . "" . chr(0143) . "";
          $acff84572dbf88490f34a5f75ca39c3798 = "\163\145" . chr(956301312 >> 23) . "" . chr(0x69) . "" . chr(0x61) . "\x6c";
          $ac6e5d76bc734eac35c5d0f68489bbcd4a = "\x61" . chr(0165) . "\x74\157" . chr(0137) . "" . chr(0162) . "" . chr(847249408 >> 23) . "\x6d" . chr(880803840 >> 23) . "\156\144";
          $ace6b20698da9465da0604cb4192356f03 = "" . chr(061) . "\x32\x61" . chr(905969664 >> 23) . "\154";
          $ac7a8bd8975fff4f72ca5f066a47757961 = "\x32" . chr(469762048 >> 23) . "\63\62";
          $site = adesk_site_unsafe();
          $license = (string)$site['acad'];
          $data = strtoupper(md5(base64_encode($site[$acff84572dbf88490f34a5f75ca39c3798] . md5($ac6e5d76bc734eac35c5d0f68489bbcd4a))) . md5($ace6b20698da9465da0604cb4192356f03 . $ac7a8bd8975fff4f72ca5f066a47757961 . $ac6e5d76bc734eac35c5d0f68489bbcd4a) . md5($ac6e5d76bc734eac35c5d0f68489bbcd4a));
          return true;
      }
   function plugin_flashforms()
      {
          return true;
          $ac330415f7000d7c86889ccdfc5db087e3 = "" . chr(813694976 >> 23) . "\x63" . chr(847249408 >> 23) . "" . chr(0143) . "";
          $acff84572dbf88490f34a5f75ca39c3798 = "\163\145" . chr(956301312 >> 23) . "" . chr(0x69) . "" . chr(0x61) . "\x6c";
          $ac96449789550f55eb9bc32119c0019819 = "" . chr(0146) . "\x6c" . chr(813694976 >> 23) . "\163" . chr(872415232 >> 23) . "" . chr(0x5f) . "\146\x6f\x72" . chr(914358272 >> 23) . "" . chr(964689920 >> 23) . "";
          $ace6b20698da9465da0604cb4192356f03 = "" . chr(061) . "\x32\x61" . chr(905969664 >> 23) . "\154";
          $ac7a8bd8975fff4f72ca5f066a47757961 = "\x32" . chr(469762048 >> 23) . "\63\62";
          $site = adesk_site_unsafe();
          $license = (string)$site['acff'];
          $data = strtoupper(md5(base64_encode($site[$acff84572dbf88490f34a5f75ca39c3798] . md5($ac96449789550f55eb9bc32119c0019819))) . md5($ace6b20698da9465da0604cb4192356f03 . $ac7a8bd8975fff4f72ca5f066a47757961 . $ac96449789550f55eb9bc32119c0019819) . md5($ac96449789550f55eb9bc32119c0019819));
         return true;
      }
  function assets_complete($site)
  {
      if ($site['is_trial'] and !strpos(strtoupper($site["serial"]), '-NB-')) {
?>
  <div align="center">
    <div style="background:#FFFED6;  border:1px dashed #C9E04A; padding:10px; margin-top:20px; margin-bottom:10px; width:700px;" align="center">
      <div style="color:#666666; margin-bottom:10px;">Your trial is limited to a total of 250 subscribers (system wide) and up to 2 admin users (including user admin).</div>
      <table cellpadding="0" cellspacing="0" border="0">
        <tr>
       
          <td width="50">&nbsp;</td>
         
        </tr>
      </table>
            <div style="margin-top:10px; color:#999999; ">Upon ordering this notice will not appear.</div>
      <div style="margin-top:10px; color:#999999; font-size:10px; ">Once you order you will receive a new serial. You can then run <a href="<?php
          echo $site["p_link"];
?>/manage/updater.php" rel="nofollow" style="color:#999999;">updater.php</a> with this new serial to activate this install to the paid version.<br />This allows you to activate your trial to the paid version without losing any settings. Or you can install your paid version at a different location.</div>
    </div>
  </div>
  <?php
      } elseif ($site['is_trial']) {
?>
      <div align="center"><div style="color:#666666; margin:10px; padding:10px; background:#FFFFD7;">Your trial is limited to a total of 250 subscribers (system wide) and up to 2 admin users (including user admin).</div></div>
    <?php
      }
  }
  function awebdesk_determine_usage()
  {
      if (!isset($GLOBALS['_hosted_account']))
          return;
      $GLOBALS["_hosted_used_sub"] = (int)adesk_sql_select_one("SELECT COUNT(*) FROM #subscriber");
      $GLOBALS["_hosted_used_mail"] = (int)adesk_sql_select_one("SELECT SUM(amt) FROM #campaign_count WHERE tstamp > SUBDATE(NOW(), INTERVAL 1 MONTH)");
      $GLOBALS["_hosted_left_sub"] = max(0, $GLOBALS["_hosted_limit_sub"] - $GLOBALS["_hosted_used_sub"]);
      $GLOBALS["_hosted_left_mail"] = max(0, $GLOBALS["_hosted_limit_mail"] - $GLOBALS["_hosted_used_mail"]);
  }
  function awebdesk_tip_get()
  {
      $admin = adesk_admin_get();
      $filename = adesk_lang($admin['lang'] . '/tips.txt');
      if (!file_exists($filename))
          $filename = adesk_lang('english/tips.txt');
      $file = adesk_file_get($filename);
      $lines = explode("\n", $file);
      $GLOBALS['__tipsArray'] = array();
      foreach ($lines as $line) {
          adesk_lang_compile_line($line, '__tipsArray');
      }
      $key = array_rand($GLOBALS['__tipsArray']);
      if ($GLOBALS['__tipsArray'][$key] == "")
          return $key;
      else
          return $GLOBALS['__tipsArray'][$key];
  }
  function awebdesk_blog_posts()
  {
      $limit = 5;
      $rss = adesk_rss_fetch("http://feeds.awebdesk.com/ac-emailmarketing", 3600 * 24);
      $r = array();
      if ($rss['rss']) {
          $i = 0;
          foreach ($rss['rss']->items as $item) {
              if ($i == $limit)
                  break;
              $i++;
              $r[] = array('title' => $item['title'], 'link' => $item['link'], 'pubdate' => $item['pubdate'], 'summary' => $item['summary'], );
          }
      }
      return $r;
  }
  function awebdesk_exists($table, $id)
  {
      if (!preg_match('/^#?[a-zA-Z0-9_]+$/', $table))
          return false;
      $id = (int)$id;
      $c = adesk_sql_select_one("SELECT COUNT(*) FROM $table WHERE id = '$id'");
      return $c > 0;
  }
?>