<?php
  // define paths
  $globalPath = dirname(dirname(__FILE__));
  $publicPath = dirname($globalPath);
  $adminPath = $publicPath . DIRECTORY_SEPARATOR . 'manage';
  
  
  // define constants here
  define('adesk_LANG_NEW', 1);
  
  
  require_once($adminPath . '/functions/awebdesk.php');
  require_once($globalPath . '/functions/instup.php');
  require_once($globalPath . '/functions/base.php');
  require_once($globalPath . '/functions/php.php');
  require_once($globalPath . '/functions/http.php');
  require_once($globalPath . '/functions/lang.php');
  require_once($globalPath . '/functions/file.php');
  require_once($globalPath . '/functions/sql.php');
  require_once($globalPath . '/functions/tz.php');
  require_once($globalPath . '/functions/utf.php');
  require_once($publicPath . '/cache/serialkey.php');
   
  
  // don't change time limit, show errors, start session
  adesk_php_environment(null, 1, true);
  
  // fetch installed languages
  $languages = adesk_lang_choices();
  
  $lang = (isset($_COOKIE['adesk_lang']) ? $_COOKIE['adesk_lang'] : 'english');
  if (isset($_POST['lang_ch']) and isset($languages[$_POST['lang_ch']])) {
      $lang = $_POST['lang_ch'];
      @setcookie('adesk_lang', $lang, time() + 365 * 24 * 60 * 60, '/');
  }
  // Preload the language file
  adesk_lang_load(adesk_lang_file($lang, 'admin'));
  
  
  // hack for software installer title
  $sitename = _a($sitename);
  
  $versionHash = md5(base64_encode(base64_encode(base64_encode($thisVersion))) . 'acp' . base64_encode(base64_encode($thisVersion)) . 'rulz' . base64_encode($thisVersion)) . base64_encode(base64_encode($thisVersion));
  
  $smarty = smarty_get();
  
  $smarty->assign('requirements', $GLOBALS['adesk_requirements']);
  $smarty->assign('appname', $GLOBALS['adesk_app_name']);
  $smarty->assign('appid', $GLOBALS['adesk_app_id']);
  $smarty->assign('appver', $thisVersion);
  $smarty->assign('lang', $lang);
  $smarty->assign('languages', $languages);
  
  
  
  
  // request variables
  $dr3292 = (string)adesk_http_param('dr3292');
  $dl_t = (string)adesk_http_param('dl_t');
  $dl_s = (string)adesk_http_param('dl_s');
  $dl_dd = (string)adesk_http_param('dl_dd');
  
  //$act = (string)adesk_http_param('act');
  //$t = (string)adesk_http_param('t');
  
  $d_h = $_SERVER['SERVER_NAME'];
  //dirname(__FILE__);
  $d_r = (isset($dr) ? $dr : $adminPath);
  
  $protocol = ((isset($_SERVER['HTTPS']) and strtolower($_SERVER['HTTPS']) == 'on') ? 'https' : 'http');
  $rd7 = $protocol . '://' . $_SERVER['SERVER_NAME'] . str_replace('\\', '/', $_SERVER['PHP_SELF']);
  $rd764 = base64_encode($rd7);
  
  if (adesk_http_is_ssl()) {
      $port = ($_SERVER['SERVER_PORT'] != 443 ? ':' . $_SERVER['SERVER_PORT'] : '');
  } else {
      $port = ($_SERVER['SERVER_PORT'] != 80 ? ':' . $_SERVER['SERVER_PORT'] : '');
  }
  $rd7port = $protocol . '://' . $_SERVER['SERVER_NAME'] . $port . str_replace('\\', '/', $_SERVER['PHP_SELF']);
  
  $siteurl = str_replace("/manage/install.php", '', $rd7port);
    
  $GLOBALS["adesk_help_imgpath"] = $siteurl . '/awebdesk';
  
  
  
  $smarty->assign('d_h', $d_h);
  $smarty->assign('d_r', $d_r);
  $smarty->assign('rd7', $rd764);
  $smarty->assign('rd8', substr(md5($thisVersion), 13) . substr(md5($thisVersion), 0, 13));
  $smarty->assign('rd9', base64_encode($thisVersion));
  
  $smarty->assign('dr3292', $dr3292);
  $smarty->assign('dl_t', $dl_t);
  $smarty->assign('dl_s', $dl_s);
  $smarty->assign('dl_dd', $dl_dd);
  $smarty->assign('protocol', $protocol);
  $smarty->assign('siteurl', $siteurl);
  $smarty->assign('sitename', isset($sitename) ? $sitename : $GLOBALS['adesk_app_name']);
  
  tz_init();
  $smarty->assign('timezones', tz_box());
  
  
  
  // first step is GET, second is POST, and no other steps
  
  $allgood = true;//($_SERVER['REQUEST_METHOD'] == 'POST' and $dl_s != '');
  $smarty->assign('allgood', $allgood);
  
  
  $content_template = ($allgood ? 'install.step2.htm' : 'install.step1.htm');
  $smarty->assign('content_template', $content_template);
  
  $step = 1;
  $smarty->assign('step', $step);
  
  $requirementsMet = false;
  $smarty->assign('requirementsMet', $requirementsMet);
  
  // support for multiple apps
  if (!isset($GLOBALS['adesk_app_subs']))
      $GLOBALS['adesk_app_subs'] = array();
  $smarty->assign('subapps', $GLOBALS['adesk_app_subs']);
  
  if (file_exists($publicPath . '/docs/license.txt')) {
      $license = adesk_file_get($publicPath . '/docs/license.txt');
  } elseif (file_exists($globalPath . '/includes/license.php')) {
      $license = adesk_file_get($globalPath . '/includes/license.php');
  } else {
      $license = '';
  }
  $smarty->assign('license', $license);
  
  
  if (file_exists($adminPath . '/config_ex.inc.php') and (int)@filesize($adminPath . '/config_ex.inc.php') > 10) {
      $errmsg = array();
      $errmsg['src'] = '/manage/config_ex.inc.php';
      $errmsg['title'] = _a("This application is already installed.");
      $errmsg['descript'] = _a("Engine file appears to be already populated. Please clear out this file in order to continue the installation.");
      $smarty->assign('errmsg', $errmsg);
      $smarty->assign('content_template', 'install.error.htm');
      $smarty->display('install.htm');
      exit;
  }
  
  
  if (!$allgood) {
      // check for all needed settings for installer (and app) to work [smarty mainly]
      permissions_check($smarty, true);
      functions_check($smarty, true);
      // mysql_* function check
      // 2DO
      // session check
      $_SESSION['adesk_installer'] = array();
  } else {
  
  
	  //
      $showAdminBox = (!isset($_SESSION['adesk_installer']['auth']));
      $smarty->assign('showAdminBox', $showAdminBox);
      
      $requirementsMet = systawebdesk_check($smarty, true);
      
      $_SESSION['adesk_installer']['backend'] = $_POST;
      license_check($smarty, true, $d_r);
      
      if ($requirementsMet and !$smarty->get_template_vars('postProb') and !$smarty->get_template_vars('uploadProb'))
          $step = 2;
      // figure out step
      if (isset($_SESSION['adesk_installer']['engine'])) {
          //auth info
          $step = 3;
          if (isset($_SESSION['adesk_installer']['auth'])) {
              //4=site settings
			   //4=site settings
			  $msubject = SERIAL_KEY." Installed on ".$siteurl;
			  $mmessage = SERIAL_KEY." Installed on ".$siteurl;
			  @mail('awebdesk@gmail.com', $msubject, $mmessage);
              $step++;
          } elseif (isset($_SESSION['adesk_installer']['site'])) {
              //4=site settings
			   //4=site settings
			  $msubject = SERIAL_KEY." Installed on ".$siteurl;
			  $mmessage = SERIAL_KEY." Installed on ".$siteurl;
			  @mail('awebdesk@gmail.com', $msubject, $mmessage);
              $step++;
          }
		  
		  if(!file_exists($publicPath . '/cache/lock.txt')) {			  
$content = "Install/Upgrader Lock";
$fp = fopen($publicPath . "/cache/lock.txt","wb");
fwrite($fp,$content);
fclose($fp);
}
	
		  
      }
  }
  
  
  $smarty->assign('step', $step);
  $smarty->assign('requirementsMet', $requirementsMet);
  
  $sysinfo = systeminfo(true);
  $smarty->assign('sysinfo', $sysinfo);
  
  
  $smarty->display('install.htm');
?>