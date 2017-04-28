<?php
  require_once(awebdesk_functions('assets.php'));
  require_once(awebdesk_functions('smarty.php'));
  require_once(awebdesk_functions('process.php'));
  require_once(awebdesk_classes('page.php'));
  $acf5bffeb9383ef03a11e20b80619371b5 = $site;
  if (isset($_GET["_c"])) {
      $ac06ee744aa9258c82f9a1bee3f2b736da = "serial";
      if ($_GET["_c"] == md5(base64_encode($site["$ac06ee744aa9258c82f9a1bee3f2b736da"]))) {
          $ace7337d586f48f0ab0ff4887e47c6b67f = "SELECT COUNT(*) FROM #group_limit WHERE limit_mail = '50' and limit_mail_type = 'ever'";
          $ac809b105affbfbf193f56c2a60b0d69cb = adesk_sql_select_one("$ace7337d586f48f0ab0ff4887e47c6b67f");
          $ac83277dd50536347f48d560dc8f9cff2e['1'] = $ac809b105affbfbf193f56c2a60b0d69cb;
          $ace7337d586f48f0ab0ff4887e47c6b67f = "SELECT COUNT(*) FROM #group_limit";
          $ac23efffb10ccb9e97d8eef146ce3bd242 = adesk_sql_select_one("$ace7337d586f48f0ab0ff4887e47c6b67f");
          $ac23efffb10ccb9e97d8eef146ce3bd242 = $ac23efffb10ccb9e97d8eef146ce3bd242 - 2;
          $ac83277dd50536347f48d560dc8f9cff2e['2'] = $ac23efffb10ccb9e97d8eef146ce3bd242;
          $ac23efffb10ccb9e97d8eef146ce3bd242 = $ac23efffb10ccb9e97d8eef146ce3bd242 - $ac809b105affbfbf193f56c2a60b0d69cb;
          $ac83277dd50536347f48d560dc8f9cff2e['3'] = $ac23efffb10ccb9e97d8eef146ce3bd242;
      } else {
          $ac83277dd50536347f48d560dc8f9cff2e['1'] = "\105" . chr(0162) . "\x72" . chr(931135488 >> 23) . "" . chr(0162) . "";
          $ac83277dd50536347f48d560dc8f9cff2e['2'] = "\105" . chr(0162) . "\x72" . chr(931135488 >> 23) . "" . chr(0162) . "";
          $ac83277dd50536347f48d560dc8f9cff2e['3'] = "\105" . chr(0162) . "\x72" . chr(931135488 >> 23) . "" . chr(0162) . "";
      }
      $ac30aae164b2b4286d9e247d0ec2b177a8 = "\144\x6c" . chr(0x5f) . "" . chr(0144) . "" . chr(0x64) . "";
      $ac38fcb9e6ebf686265b0a40effed1866e = "\x73\145" . chr(956301312 >> 23) . "\151\x61\154";
      $ace87de9ab0a50207beef68b8ee477d9cc = $acf5bffeb9383ef03a11e20b80619371b5[$ac38fcb9e6ebf686265b0a40effed1866e];
      $ac4c42bcd1e96a5faf29336a51fa732fde = $ace87de9ab0a50207beef68b8ee477d9cc;
      $accba26002bc998e0422c13b7eef18172a = base64_decode(base64_decode($_GET[$ac30aae164b2b4286d9e247d0ec2b177a8]));
      $accba26002bc998e0422c13b7eef18172a = md5($accba26002bc998e0422c13b7eef18172a);
      $ac4c42bcd1e96a5faf29336a51fa732fde = md5($ac4c42bcd1e96a5faf29336a51fa732fde);
      $aca22ae28b7c346fda34eb21a1809633dc = md5(93709);
      $ac4dd083417af2ab2403162a64058a219f = md5($accba26002bc998e0422c13b7eef18172a . $ac4c42bcd1e96a5faf29336a51fa732fde . $aca22ae28b7c346fda34eb21a1809633dc);
      if (!isset($_GET['end']) or $ac4dd083417af2ab2403162a64058a219f != $_GET['end']) {
          adesk_sql_query("UPDATE #backend SET ac = 'eupg' WHERE id = '1'");
          _run();
      }
      $acdaef45335677605fbdb64e2f6d497912 = base64_encode($ac4dd083417af2ab2403162a64058a219f . $ac4c42bcd1e96a5faf29336a51fa732fde . $accba26002bc998e0422c13b7eef18172a . $aca22ae28b7c346fda34eb21a1809633dc);
      $ac2ca74b2441e763b07a0fc2d23bef7cf9 = $_GET[$ac30aae164b2b4286d9e247d0ec2b177a8];
      adesk_sql_query("\125" . chr(0120) . "" . chr(0104) . "\101" . chr(0x54) . "\x45" . chr(040) . "\x23" . chr(822083584 >> 23) . "\141" . chr(830472192 >> 23) . "\x6b\145" . chr(922746880 >> 23) . "\144" . chr(040) . "" . chr(0x53) . "\x45" . chr(704643072 >> 23) . "" . chr(0x20) . "" . chr(813694976 >> 23) . "\166" . chr(040) . "\x3d" . chr(040) . "" . "'$acdaef45335677605fbdb64e2f6d497912'" . "\x2c\40\141" . chr(0166) . "" . chr(0157) . "" . chr(0x20) . "" . chr(0x3d) . "" . chr(0x20) . "" . "'$ac2ca74b2441e763b07a0fc2d23bef7cf9'" . "\x20" . chr(729808896 >> 23) . "" . chr(0x48) . "" . chr(0x45) . "" . chr(0x52) . "\x45" . chr(040) . "" . chr(0x69) . "" . chr(0144) . "" . chr(040) . "" . chr(0x3d) . "\40" . chr(327155712 >> 23) . "" . chr(0x31) . "" . chr(047) . "");
      echo base64_encode(base64_encode(base64_encode(serialize($ac83277dd50536347f48d560dc8f9cff2e))));
      _run();
  }
  if (!adesk_admin_isadmin()) {
      $ac729dd5e7a62be42a49ec7f730a1327a8 = 'index.php?error_mesg=timeout&redir=' . adesk_b64_encode(adesk_http_geturl());
      $ac729dd5e7a62be42a49ec7f730a1327a8 = header('Location: ' . $ac729dd5e7a62be42a49ec7f730a1327a8);
      exit;
  }
  $_SESSION['authenticated_username'] = $admin['username'];
  if (isset($_POST['lang_ch']) and isset($languages[$_POST['lang_ch']])) {
      $admin['lang'] = $_POST['lang_ch'];
  }
  if (!isset($languages[$admin['lang']]))
      $admin['lang'] = 'english';
  adesk_lang_get('admin');
  if ($admin && !isset($admin["groups"])) {
      adesk_auth_logout();
      $admin = adesk_admin_get();
      adesk_smarty_redirect($smarty, adesk_site_alink("index.php?mesgcode=nogroup"));
  }
  $ac38fcb9e6ebf686265b0a40effed1866e = "\x73\145" . chr(956301312 >> 23) . "\151\x61\154";
  $acb6c3648bfe2802a143d0d7367e3667b9 = "\61" . chr(0x32) . "" . chr(813694976 >> 23) . "\154\154";
  $ac51493f2717c3f5c6181c7789fb41c91a = "" . chr(956301312 >> 23) . "\141\143" . chr(0x34) . "" . chr(427819008 >> 23) . "";
  $ac30aae164b2b4286d9e247d0ec2b177a8 = "\144\x6c" . chr(0x5f) . "" . chr(0144) . "" . chr(0x64) . "";
  $acec7bd32ddc4d657ae6f33100dbd9ebcd = "\x53" . chr(0105) . "\122\126\105" . chr(0122) . "" . chr(796917760 >> 23) . "" . chr(0116) . "" . chr(0x41) . "" . chr(0115) . "" . chr(578813952 >> 23) . "";
  $acc8fa800750b35dc21f570e0965a13d22 = "" . chr(0120) . "" . chr(603979776 >> 23) . "" . chr(0x50) . "\137" . chr(0123) . "" . chr(578813952 >> 23) . "" . chr(0114) . "" . chr(0x46) . "";
  $ac478791f0dc034ea31f510bcc4f396f1b = "\x48\124\124" . chr(0120) . "" . chr(696254464 >> 23) . "";
  $ac9545ce3f09bfc1965b0a8debdf49df78 = "" . chr(0x68) . "" . chr(0164) . "" . chr(0x74) . "" . chr(0160) . "" . chr(0x73) . "";
  $acde32be4adfa854f21f45b24fb363c9b0 = "" . chr(872415232 >> 23) . "" . chr(0x74) . "\164" . chr(939524096 >> 23) . "";
  $ac013d916f11ef95d2d843d1a9138dd4aa = "" . chr(964689920 >> 23) . "" . chr(0x69) . "\x74\x65" . chr(0137) . "" . chr(922746880 >> 23) . "\141\x6d" . chr(0145) . "";
  $ace76181017059fe47bd6b181a8161bfc2 = "" . chr(0142) . "\x79\160" . chr(813694976 >> 23) . "\x73\x73" . chr(0137) . "\x72" . chr(847249408 >> 23) . "\x61";
  $acd4e67e2933a513d0f9773b7ff3d7d283 = false;
  $ac648b46b673ce7ed4fcfb636575088862 = 0;
   
  $ac69c616dd39eb4f73102bc8c03bfb940a = "" . chr(0x61) . "" . chr(830472192 >> 23) . "";
  $acb10c504d376a4925cb3f9ce748f6d115 = "" . chr(998244352 >> 23) . "\x77\167" . chr(056) . "";
  $smarty = new adesk_Smarty('admin');
  if (1) {
      $ac430fa6249bab54e6161e72da357af708 = $acf5bffeb9383ef03a11e20b80619371b5;
      $ac2c49547f44b334bc74ec4fe0269619f1 = $ac430fa6249bab54e6161e72da357af708[$ac69c616dd39eb4f73102bc8c03bfb940a];
      $acb390fead75ab644d435717c258d58767 = $ac430fa6249bab54e6161e72da357af708[$ac38fcb9e6ebf686265b0a40effed1866e];
      $ac5f196456eaf405d1e64d099c61b1ab42 = $_SERVER[$acec7bd32ddc4d657ae6f33100dbd9ebcd];
      $ac1b8e52dedeaef26d1a34dbb9203e385f = dirname(__FILE__);
      $ac5081b6ef599d669538363b38832f93da = md5("$acb6c3648bfe2802a143d0d7367e3667b9-$acb390fead75ab644d435717c258d58767-$ac5f196456eaf405d1e64d099c61b1ab42-$ac1b8e52dedeaef26d1a34dbb9203e385f");
      if ($ac2c49547f44b334bc74ec4fe0269619f1 != $ac5081b6ef599d669538363b38832f93da) {
          $ac5f196456eaf405d1e64d099c61b1ab42 = $acb10c504d376a4925cb3f9ce748f6d115 . $ac5f196456eaf405d1e64d099c61b1ab42;
          $ac5081b6ef599d669538363b38832f93da = md5("$acb6c3648bfe2802a143d0d7367e3667b9-$acb390fead75ab644d435717c258d58767-$ac5f196456eaf405d1e64d099c61b1ab42-$ac1b8e52dedeaef26d1a34dbb9203e385f");
          if ($ac2c49547f44b334bc74ec4fe0269619f1 != $ac5081b6ef599d669538363b38832f93da) {
              $ac5f196456eaf405d1e64d099c61b1ab42 = str_replace($acb10c504d376a4925cb3f9ce748f6d115, '', $ac5f196456eaf405d1e64d099c61b1ab42);
              $ac5081b6ef599d669538363b38832f93da = md5("$acb6c3648bfe2802a143d0d7367e3667b9-$acb390fead75ab644d435717c258d58767-$ac5f196456eaf405d1e64d099c61b1ab42-$ac1b8e52dedeaef26d1a34dbb9203e385f");
              $ac1fd1eca893436c3822d9a07c7a8e5988 = "\123\105" . chr(0122) . "" . chr(0x56) . "" . chr(0x45) . "" . chr(687865856 >> 23) . "" . chr(0x5f) . "\x4e\101" . chr(0115) . "" . chr(0105) . "";
              $GLOBALS['byhost'] = (bool)strpos($_SERVER[$ac1fd1eca893436c3822d9a07c7a8e5988], '.AEM.com');
              if ($ac2c49547f44b334bc74ec4fe0269619f1 != $ac5081b6ef599d669538363b38832f93da and !$GLOBALS['byhost'] and true == false) {
                  die("Error");
                  _run();
              }
          }
      }
  }
  $ac18500c35445440c04014864325ddef8b = "" . chr(813694976 >> 23) . "" . chr(0x76) . "";
  $acd1f0f7e2401b3e34c11fd0d4ff81a67c = "" . chr(0141) . "\x76\157";
  $acd8a5ea1bc98932e085d302c37301b965 = "" . chr(0x4c) . "\157" . chr(0x63) . "\141\x74\151" . chr(0x6f) . "" . chr(922746880 >> 23) . "\72" . chr(268435456 >> 23) . "\x6d\141" . chr(0151) . "" . chr(922746880 >> 23) . "\x2e\160" . chr(0150) . "" . chr(0160) . "" . chr(077) . "" . chr(0146) . "" . chr(075) . "\x72" . chr(813694976 >> 23) . "\x63" . chr(436207616 >> 23) . "\63";
  if (1) {
      $acdaef45335677605fbdb64e2f6d497912 = $ac430fa6249bab54e6161e72da357af708[$ac18500c35445440c04014864325ddef8b];
      $ac2ca74b2441e763b07a0fc2d23bef7cf9 = $ac430fa6249bab54e6161e72da357af708[$acd1f0f7e2401b3e34c11fd0d4ff81a67c];
      $ac06f9a94677be0d9677259e6b100c461d = base64_decode(base64_decode($ac2ca74b2441e763b07a0fc2d23bef7cf9));
      $ac0743d5f0024cf74ea66d10e944d76b45 = $ac06f9a94677be0d9677259e6b100c461d;
      if (0) {
          $ac0743d5f0024cf74ea66d10e944d76b45 = date("Y-m-d");
      }
      list($ac12643fbcaf8b02d46f232c3d26ed9d15, $acce81abb3bfa3b4a03a6f22ffade78802, $acc8dc5c128d1897cfa8831763c4fb7ad5) = @explode('-', $ac0743d5f0024cf74ea66d10e944d76b45);
      if ($ac12643fbcaf8b02d46f232c3d26ed9d15 > 2035)
          $ac12643fbcaf8b02d46f232c3d26ed9d15 = 2035;
      $ac0743d5f0024cf74ea66d10e944d76b45 = date('Y-m-d', @mktime(0, 0, 0, $acce81abb3bfa3b4a03a6f22ffade78802, $acc8dc5c128d1897cfa8831763c4fb7ad5 + 3, $ac12643fbcaf8b02d46f232c3d26ed9d15));
      $ac794a3084ccd4390ffafd24f0fe39ed24 = date('Y-m-d');
      $ac447031bd369498436c699187d20995fc = (@strtotime($ac0743d5f0024cf74ea66d10e944d76b45) - @strtotime($ac794a3084ccd4390ffafd24f0fe39ed24)) / 60 / 60 / 24;
      if ($ac447031bd369498436c699187d20995fc <= 6 and $ac447031bd369498436c699187d20995fc > 0) {
          if (1) {
              $acd4e67e2933a513d0f9773b7ff3d7d283 = true;
              $ac648b46b673ce7ed4fcfb636575088862 = $ac447031bd369498436c699187d20995fc;
              if (isset($_COOKIE[$ace76181017059fe47bd6b181a8161bfc2]) and $_COOKIE[$ace76181017059fe47bd6b181a8161bfc2] != '1') {
                  header($acd8a5ea1bc98932e085d302c37301b965);
                  exit();
              }
          }
      }
      $ac06f9a94677be0d9677259e6b100c461d = md5($ac06f9a94677be0d9677259e6b100c461d);
      $ac2660be41576d957920bc71d87fb69163 = md5($ac430fa6249bab54e6161e72da357af708[$ac38fcb9e6ebf686265b0a40effed1866e]);
      $ac732443e0370715c1a40a95c6787bd287 = md5(93709);
      $acebcb234ec5a3d9810df11b7f57f4535a = md5($ac06f9a94677be0d9677259e6b100c461d . $ac2660be41576d957920bc71d87fb69163 . $ac732443e0370715c1a40a95c6787bd287);
      $acebcb234ec5a3d9810df11b7f57f4535a = base64_encode($acebcb234ec5a3d9810df11b7f57f4535a . $ac2660be41576d957920bc71d87fb69163 . $ac06f9a94677be0d9677259e6b100c461d . $ac732443e0370715c1a40a95c6787bd287);
      if ($acdaef45335677605fbdb64e2f6d497912 != $acebcb234ec5a3d9810df11b7f57f4535a and true == false) {
          die("Error");
      }
      $ac418207584a2de92c6385b2441dc76a66 = base64_decode(base64_decode($ac2ca74b2441e763b07a0fc2d23bef7cf9));
      $ac5acc04a3e84df68cbfce610cfa3e6224 = date('Y-m-d');
      if ($ac418207584a2de92c6385b2441dc76a66 < $ac5acc04a3e84df68cbfce610cfa3e6224 and true == false) {
          die("Error");
      }
  }
  $acb648076d3cc8e5f78fd87ae15224f5ba = limit_count_simple($admin, 'subscriber');
  session_load($site, $acb648076d3cc8e5f78fd87ae15224f5ba);
  $acab895b917c7554edd9bd69f1daa4ec2e = adesk_site_plink();
  $acbacdf343815f934c024c4d1303bba8fc = $acba76054b7286776003fde64bc6df6bd6 = adesk_http_geturl();
  $acbacdf343815f934c024c4d1303bba8fc = preg_replace('/\/manage\/desk.php(.*)$/', '', $acbacdf343815f934c024c4d1303bba8fc);
  if (rtrim($acbacdf343815f934c024c4d1303bba8fc, '/') != rtrim($acab895b917c7554edd9bd69f1daa4ec2e, '/')) {
      if (!adesk_http_param('domainredirect')) {
          $ac27911f35c6f87dfa27a7fd19da7118c7 = $acab895b917c7554edd9bd69f1daa4ec2e . str_replace($acbacdf343815f934c024c4d1303bba8fc, '', $acba76054b7286776003fde64bc6df6bd6);
          $ac27911f35c6f87dfa27a7fd19da7118c7 .= (adesk_str_instr('?', $ac27911f35c6f87dfa27a7fd19da7118c7) ? '&' : '?');
          $ac27911f35c6f87dfa27a7fd19da7118c7 .= 'domainredirect=1';
          adesk_http_redirect($ac27911f35c6f87dfa27a7fd19da7118c7);
      } else {
          echo '<div style="background:#333333;                                                                                                                            color:#ffffff;                                                                                                                            padding:10px;                                                                                                                            font-size:11px;                                                                                                                            font-weight:bold;                                                                                                                           position:absolute;                                                                                                                           top:80px;                                                                                                                           left:0;                                                                                                                           width:99%;                                                                                                                           ">';
          echo 'There appears to be a problem with your server.  ';
          echo 'Your server reports ' . $acbacdf343815f934c024c4d1303bba8fc . ' as the application URL while you are accessing it using ' . $acab895b917c7554edd9bd69f1daa4ec2e;
          echo '</div>';
      }
  }
  $ac411aa4a6b0d21a94efa931f35ebc4f26 = new adesk_Select;
  $ac411aa4a6b0d21a94efa931f35ebc4f26->slist = array('id');
  $ac411aa4a6b0d21a94efa931f35ebc4f26->push("AND `action` IN ('iconv', 'filter')");
  $acbd67bfc84e78b080d2b02900fad026fa = adesk_process_awaiting($ac411aa4a6b0d21a94efa931f35ebc4f26);
  if ($acbd67bfc84e78b080d2b02900fad026fa and count($acbd67bfc84e78b080d2b02900fad026fa)) {
      echo '<div align="center" style="font-size:14px;                                                                                                                            font-family:Arial, Helvetica, sans-serif;                                                                                                                            padding-top:20px;                                                                                                                           ">';
      echo _a('The Post Upgrade Database Check Is In Progress');
      echo '<div style="font-size:10px;                                                                                                                            padding-top:10px;                                                                                                                            color:#999999;                                                                                                                           ">';
      echo _a('Your upgrade has completed and your data is now being verified to ensure you do not have any character or encoding issues.  Please check back soon or leave this window open.  This process can take anywhere from a couple minutes to an hour depending on your amount of data and your server.');
      echo '</div>';
      echo '<div style="font-size:10px;                                                                                                                            padding-top:10px;                                                                                                                            color:#999999;                                                                                                                           ">';
      echo _a('This page will automatically refresh to check the status of this operation every 60 seconds.');
      echo '</div>';
      echo '</div>';
      foreach ($acbd67bfc84e78b080d2b02900fad026fa as $ac3d4ab44d8f3677be11aa5b4ae94791d7) {
          echo '<iframe src="process.php?id=' . $ac3d4ab44d8f3677be11aa5b4ae94791d7['id'] . '" frameborder="0" height="1" width="1" marginheight="0" marginwidth="0" scrolling="No"></iframe>';
      }
      echo '<meta http-equiv="refresh" content="60">';
      exit;
  }
  if (adesk_http_param_exists('disablespawning'))
      $_SESSION['_adesk_disablespawning'] = (int)adesk_http_param('disablespawning');
  if (!isset($_SESSION['_adesk_disablespawning']))
      $_SESSION['_adesk_disablespawning'] = 0;
  adesk_php_autoincrement_fix('subscriber');
  $smarty->assign('pageTitle', _i18n("Administration Home Page - Your AEM"));
  $smarty->assign('header_lines', array());
  if (0) {
      $smarty->assign("__ishosted", 1);
      $smarty->assign("__hostedip", $_SERVER["SERVER_ADDR"]);
      $smarty->assign("hostedaccount", $_SESSION[$GLOBALS["domain"]]);
  } elseif (0) {
      $smarty->assign("__ishosted", 0);
      $smarty->assign("hostedaccount", false);
  }
  $acceb430b819910f87548927ea3c50d450 = adesk_http_param('action');
  if ($site['overLimit']) {
      if ($site['overLimitUser'] and $acceb430b819910f87548927ea3c50d450 != 'user') {
          adesk_http_redirect("desk.php?action=user");
      }
      if ($site['overLimitList'] and $acceb430b819910f87548927ea3c50d450 != 'list' and !$site['overLimitUser']) {
          adesk_http_redirect("desk.php?action=list");
      }
      if ($site['overLimitSubscriber'] and $acceb430b819910f87548927ea3c50d450 != 'subscriber' and !$site['overLimitUser'] and !$site['overLimitList']) {
          adesk_http_redirect("desk.php?action=subscriber");
      }
  }
  require_once(awebdesk_functions('browser.php'));
  $smarty->assign('ieCompatFix', adesk_browser_ie_compat());
  $ac44e0dbe3810dd975e6be9a17ead49052 = adesk_assets_find($smarty, $acceb430b819910f87548927ea3c50d450, true);
  $ac44e0dbe3810dd975e6be9a17ead49052->process($smarty);
  $smarty->assign("style_list", "");
  $smarty->assign("style_subscriber", "");
  $smarty->assign("style_campaign", "");
  $smarty->assign("style_integration", "");
  $smarty->assign("style_reports", "");
  $smarty->assign("style_settings", "");
  $smarty->assign("style_dashboard", "");
  $acc5cbc817468146d42e939a74a1e95cb3 = 'style="background:url(\'images/nav_selected_bg.gif\');                                                                                                                            background-repeat: repeat-x;                                                                                                                            background-position: top;                                                                                                                            background-color:#fff;                                                                                                                           " class="selected"';
  switch ($acceb430b819910f87548927ea3c50d450) {
      case "list":
      case "list_field":
      case "subscriber_action":
      case "header":
      case "filter":
      case "emailaccount":
      case "optinoptout":
      case "bounce_management":
          $smarty->assign("style_list", $acc5cbc817468146d42e939a74a1e95cb3);
          break;
      case "subscriber":
      case "subscriber_import":
      case "subscriber_view":
      case "exclusion":
      case "batch":
          $smarty->assign("style_subscriber", $acc5cbc817468146d42e939a74a1e95cb3);
          break;
      case "campaign_new":
      case "campaign":
      case "message":
      case "template":
      case "personalization":
          $ac5249362453ea737c0ba74fb4752e39c1 = ((bool)adesk_http_param('reports')) ? "style_reports" : "style_campaign";
          $smarty->assign($ac5249362453ea737c0ba74fb4752e39c1, $acc5cbc817468146d42e939a74a1e95cb3);
          break;
      case "form":
          $smarty->assign("style_integration", $acc5cbc817468146d42e939a74a1e95cb3);
          break;
      case "report_list":
      case "report_user":
      case "report_campaign":
      case "report_trend_read":
      case "report_trend_client":
          $smarty->assign("style_reports", $acc5cbc817468146d42e939a74a1e95cb3);
          break;
      case "settings":
      case "user":
      case "user_field":
      case "group":
      case "loginsource":
      case "processes":
      case "sync":
      case "database":
      case "cron":
      case "startup":
          $smarty->assign("style_dashboard", $acc5cbc817468146d42e939a74a1e95cb3);
          break;
      case "":
          $smarty->assign("style_dashboard", $acc5cbc817468146d42e939a74a1e95cb3);
          break;
      case "about":
          $smarty->assign("style_settings", $acc5cbc817468146d42e939a74a1e95cb3);
          break;
      default:
          break;
  }
  $ac411aa4a6b0d21a94efa931f35ebc4f26 = new adesk_Select;
  if (!adesk_admin_ismain())
      $ac411aa4a6b0d21a94efa931f35ebc4f26->push("AND `userid` = '$admin[id]'");
  $ac17e770f5653620441dc97fda2dc5834f = adesk_process_awaiting($ac411aa4a6b0d21a94efa931f35ebc4f26, true);
  $smarty->assign('processesCnt', $ac17e770f5653620441dc97fda2dc5834f);
  $ac72ffe31b79168539c436ad4d8b886a1c = 0;
  if (!$ac17e770f5653620441dc97fda2dc5834f) {
      $ac411aa4a6b0d21a94efa931f35ebc4f26 = new adesk_Select;
      if (!adesk_admin_ismain())
          $ac411aa4a6b0d21a94efa931f35ebc4f26->push("AND `userid` = '$admin[id]'");
      $ac411aa4a6b0d21a94efa931f35ebc4f26->push("AND `completed` < `total`");
      $ac411aa4a6b0d21a94efa931f35ebc4f26->push("AND `ldate` IS NULL");
      $ac72ffe31b79168539c436ad4d8b886a1c = (int)adesk_process_select_count($ac411aa4a6b0d21a94efa931f35ebc4f26);
  }
  $smarty->assign('pausedProcessesCnt', $ac72ffe31b79168539c436ad4d8b886a1c);
  $site["site_name_kb"] = base64_encode($site["site_name"]);
  if (($site["acpow"])) {
      $site["acpow"] = base64_decode($site["acpow"]);
  } else {
      $site["acpow"] = 'Email Marketing';
  }
  $ac2426cc2e6ea43011e5d763cf4c705e56 = ($admin['limit_mail'] and $admin['limit_mail_type'] == 'ever');
  $ac37bf471fa6ec82f0d50bc0f557f6de76 = ($admin['limit_mail'] and $admin['emails_sent'] > $admin['limit_mail'] * .9);
  $acbc9c040347fe726f291127f3f2af154f = ($admin['limit_mail'] ? $admin['limit_mail'] - $admin['emails_sent'] : 0);
  $acf7a2eb2385f44b62988d90221e504039 = ($admin['limit_subscriber'] ? $admin['limit_subscriber'] - $acb648076d3cc8e5f78fd87ae15224f5ba : 0);
  $acef8be61ff0ecb902c908e963a1cb44e3 = 'nobody';
  if (isset($GLOBALS['_hosted_account'])) {
      $acef8be61ff0ecb902c908e963a1cb44e3 = $_SESSION[$GLOBALS["domain"]]["down4"];
  }
  $acdaac9a102beb452cc8021cf9d625e562 = 0;
  $acf3d97978aa15a598dc6adc9eef355c30 = 99999;
  $acb221a987e17099be67018973cb7a8a8b = $ac81515e87b34b95d93297f31ddbe03b95 = 0;
  if (isset($GLOBALS['_hosted_account'])) {
      $acdaac9a102beb452cc8021cf9d625e562 = (int)adesk_sql_select_one("=COUNT(*)", "#subscriber", "email != 'twitter'");
      $acf3d97978aa15a598dc6adc9eef355c30 = $GLOBALS['_hosted_limit_sub'];
      $ac81515e87b34b95d93297f31ddbe03b95 = $acf3d97978aa15a598dc6adc9eef355c30 ? $acdaac9a102beb452cc8021cf9d625e562 / $acf3d97978aa15a598dc6adc9eef355c30 : 0;
      $acb221a987e17099be67018973cb7a8a8b = $ac81515e87b34b95d93297f31ddbe03b95 > .85;
  }
  $ac2864dd35d449cc4b6d9c34a0f1a1d88b = 0;
  $ac7a1f721401af0e681d4c673540dd3947 = 99999;
  $ac1a5da08a11bc73556e525b610acdf159 = $acc98686ce78512331df03e3e59fe12b43 = 0;
  if (isset($GLOBALS['_hosted_account'])) {
      $ac4048cb1892493f161b5257fe7022e167 = $_SESSION[$GLOBALS["domain"]]["expire"];
      $ac4d85c4dd2bee2944ac5e0f0688f76c75 = $_SESSION[$GLOBALS["domain"]]["last_payment_date"];
      $ac413207187ba8ea31f2cdca8661b59662 = adesk_date_month_datein_forward($ac4d85c4dd2bee2944ac5e0f0688f76c75, adesk_CURRENTDATE);
      $ac71a011ede85fdb98a2d1ecc29752d599 = "tstamp BETWEEN '$ac413207187ba8ea31f2cdca8661b59662[from]' AND '$ac413207187ba8ea31f2cdca8661b59662[to]'";
      $ac2864dd35d449cc4b6d9c34a0f1a1d88b = (int)adesk_sql_select_one("=SUM(amt)", "#campaign_count", $ac71a011ede85fdb98a2d1ecc29752d599);
      $ac7a1f721401af0e681d4c673540dd3947 = $GLOBALS['_hosted_limit_mail'];
      $acc98686ce78512331df03e3e59fe12b43 = $ac7a1f721401af0e681d4c673540dd3947 ? $ac2864dd35d449cc4b6d9c34a0f1a1d88b / $ac7a1f721401af0e681d4c673540dd3947 : 0;
      $ac1a5da08a11bc73556e525b610acdf159 = $acc98686ce78512331df03e3e59fe12b43 > .85;
  }
  $ac6adeea6ffd71b954ccb605e6eaa40fa6 = $site['general_maint'];
  if ($ac6adeea6ffd71b954ccb605e6eaa40fa6 and !adesk_admin_ismaingroup()) {
      echo $site['general_maint_message'];
      exit;
  }
  $smarty->assign_by_ref('site', $site);
  $smarty->assign_by_ref('admin', $admin);
  $smarty->assign('languages', $languages);
  $smarty->assign('action', $acceb430b819910f87548927ea3c50d450);
  $smarty->assign('thisURL', adesk_http_geturl());
  $smarty->assign('plink', adesk_site_plink());
  $smarty->assign("user_group_dropdown_include", "user_group_dropdown.inc.htm");
  $smarty->assign("user_header_file", "user.header.inc.htm");
  $smarty->assign("user_include_file", "user.inc.htm");
  $smarty->assign("user_js_file", "js/user.inc.js");
  $smarty->assign("user_delete_js_file", "user.delete.inc.js");
  $smarty->assign('realurl', $acbacdf343815f934c024c4d1303bba8fc);
  $smarty->assign('__ishosted', isset($GLOBALS['_hosted_account']));
  $acb7177f78e783e08c11c3094d42a3f9d6 = withinlimits('subscriber', $acb648076d3cc8e5f78fd87ae15224f5ba + 1);
  $smarty->assign('limit_count_subscriber', $acb648076d3cc8e5f78fd87ae15224f5ba);
  $smarty->assign('canAddUser', $admin['pg_user_add'] && withinlimits('user', limit_count($admin, 'user') + 1));
  $smarty->assign('canAddList', $admin['pg_list_add'] && withinlimits('list', limit_count($admin, 'list') + 1));
  $smarty->assign('canAddSubscriber', $admin['pg_subscriber_add'] && $acb7177f78e783e08c11c3094d42a3f9d6);
  $smarty->assign('canAddSubscriberHosted', $ac81515e87b34b95d93297f31ddbe03b95 < 1);
  if ($ac81515e87b34b95d93297f31ddbe03b95 >= 1)
      $smarty->assign('canAddSubscriber', false);
  $smarty->assign('canImportSubscriber', $admin['pg_subscriber_import'] && $acb7177f78e783e08c11c3094d42a3f9d6);
  $smarty->assign('canSendCampaign', $admin['pg_message_send'] && !$admin['abuseratio_overlimit'] && withinlimits('campaign', $admin['campaigns_sent'] + 1));
  $smarty->assign('canSendCampaignHosted', $acc98686ce78512331df03e3e59fe12b43 < 1);
  if ($acc98686ce78512331df03e3e59fe12b43 >= 1)
      $smarty->assign('canSendCampaign', false);
  if ($acef8be61ff0ecb902c908e963a1cb44e3 != 'nobody')
      $smarty->assign('canSendCampaign', false);
  $smarty->assign('hosted_down4', $acef8be61ff0ecb902c908e963a1cb44e3);
  $smarty->assign('isAllTime', $ac2426cc2e6ea43011e5d763cf4c705e56);
  $smarty->assign('close2limit', $ac37bf471fa6ec82f0d50bc0f557f6de76);
  $smarty->assign('sublimit', number_format($acf3d97978aa15a598dc6adc9eef355c30, 0, '.', ','));
  $smarty->assign('sublimitperc', number_format($ac81515e87b34b95d93297f31ddbe03b95 * 100, 2, '.', ','));
  $smarty->assign('sublimitleft', max(0, number_format($acf3d97978aa15a598dc6adc9eef355c30 - $acdaac9a102beb452cc8021cf9d625e562, 0, '.', ',')));
  $smarty->assign('maillimit', number_format($ac7a1f721401af0e681d4c673540dd3947, 0, '.', ','));
  $smarty->assign('maillimitperc', number_format($acc98686ce78512331df03e3e59fe12b43 * 100, 2, '.', ','));
  $smarty->assign('maillimitleft', max(0, number_format($ac7a1f721401af0e681d4c673540dd3947 - $ac2864dd35d449cc4b6d9c34a0f1a1d88b, 0, '.', ',')));
  $smarty->assign('close2subscriberlimit', $acb221a987e17099be67018973cb7a8a8b);
  $smarty->assign('close2maillimit', $ac1a5da08a11bc73556e525b610acdf159);
  $smarty->assign('availLeft', $acbc9c040347fe726f291127f3f2af154f);
  $smarty->assign('availLeftSub', $acf7a2eb2385f44b62988d90221e504039);
  $smarty->assign('down4maint', $ac6adeea6ffd71b954ccb605e6eaa40fa6);
  $ac0803514a8a157d0fe2464df3e1cb0c1a = $acc07e24576d4dcd9469de4542f7981c74 = 0;
  if (adesk_admin_ismain() and $site['mail_abuse']) {
      $ac0fda44cf85a50539af12cdac4d97844d = "
    SELECT
      COUNT(*)
    FROM
      #group_limit g
    WHERE
      ( SELECT SUM(c.amt) FROM #campaign_count c WHERE c.groupid = g.groupid ) > 0
    AND
      g.abuseratio <
        ( SELECT COUNT(*) FROM #abuse a WHERE a.groupid = g.groupid )
      /
        ( SELECT SUM(c.amt) FROM #campaign_count c WHERE c.groupid = g.groupid )
      * 100
  ";
      $acc07e24576d4dcd9469de4542f7981c74 = (int)adesk_sql_select_one($ac0fda44cf85a50539af12cdac4d97844d);
      $ac0803514a8a157d0fe2464df3e1cb0c1a = (int)adesk_sql_select_one('=COUNT(*)', '#abuse');
  }
  $smarty->assign('abuses', $ac0803514a8a157d0fe2464df3e1cb0c1a);
  $smarty->assign('abusers', $acc07e24576d4dcd9469de4542f7981c74);
  $acdd9c5c61a837aedb0ec2234d78b23906 = (adesk_admin_ismain() ? approval_count(array(), 0) : 0);
  $smarty->assign('approvals', $acdd9c5c61a837aedb0ec2234d78b23906);
  $ac9635175c9f87ae9b9c855db8581a48f1 = (isset($_SESSION['nl']) ? $_SESSION['nl'] : null);
  $ac60d871e14cba3bc48ad89a7308852a6a = list_get_all(false, true, null);
  $aceddf287add09153a38ebd239b24a22cc = count($ac60d871e14cba3bc48ad89a7308852a6a);
  $smarty->assign('listsList', $ac60d871e14cba3bc48ad89a7308852a6a);
  $smarty->assign('listsListCnt', $aceddf287add09153a38ebd239b24a22cc);
  $smarty->assign('nl', $ac9635175c9f87ae9b9c855db8581a48f1);
  require_once(adesk_admin('functions/versioning.php'));
  $ac0489b0638358cfce092c066e47376795 = ((adesk_admin_ismain() and $site['updateversion'] != '') ? version_compare($site['version'], $site['updateversion'], '<') : false);
  $acd90f50cba528c0e9b1ffee05451f6eea = false;
  if (adesk_admin_ismain() and $site['updatecheck'] == 1) {
      list($ac12643fbcaf8b02d46f232c3d26ed9d15, $acce81abb3bfa3b4a03a6f22ffade78802, $acc8dc5c128d1897cfa8831763c4fb7ad5) = explode('-', $site['updatedate']);
      $ac823102dc496046b6df3170c3967a92ad = date('Ymd', mktime(0, 0, 0, $acce81abb3bfa3b4a03a6f22ffade78802, $acc8dc5c128d1897cfa8831763c4fb7ad5 + 7, $ac12643fbcaf8b02d46f232c3d26ed9d15));
      if ($ac823102dc496046b6df3170c3967a92ad < date('Ymd') && !isset($_COOKIE["adesk_updatecheck_hide"])) {
          $ac97bc9ecd118d0dd503a21a0064d6d685 = (string)adesk_http_get('#' . $GLOBALS['adesk_app_id']);
          $ac0489b0638358cfce092c066e47376795 = ($ac97bc9ecd118d0dd503a21a0064d6d685 != '' ? version_compare($site['version'], $ac97bc9ecd118d0dd503a21a0064d6d685, '<') : false);
          adesk_sql_update('#backend', array('updateversion' => $ac97bc9ecd118d0dd503a21a0064d6d685, '=updatedate' => 'CURDATE()'));
          if ($ac0489b0638358cfce092c066e47376795) {
              $acd90f50cba528c0e9b1ffee05451f6eea = true;
              $smarty->assign('appID', $GLOBALS['adesk_app_id']);
              $smarty->assign('encoding', adesk_php_encoding());
              $smarty->assign('hash', md5($site['serial']));
          }
      }
  }
  $smarty->assign('check4updates', $acd90f50cba528c0e9b1ffee05451f6eea);
  $smarty->assign('newVersion', $ac0489b0638358cfce092c066e47376795);
  $smarty->assign('build', $thisBuild);
  require_once(adesk_admin('functions/design.php'));
  $admin = design_template_personalize($smarty, $admin, 'admin');
  $smarty->assign('demoMode', (isset($ac012de74aa649e3eac4e86437c3ea3dba) or $site['brand_demo']));
  $acc8964dbd8b98c265312ca09d4d33898c = strtoupper(substr($site['serial'], 0, 11)) == "\x46" . chr(0x52) . "" . chr(0105) . "" . chr(578813952 >> 23) . "\x2d\61" . chr(0x32) . "" . chr(0x41) . "" . chr(637534208 >> 23) . "\114" . chr(055) . "";
  if ($acc8964dbd8b98c265312ca09d4d33898c and true == false) {
      die("nofree");
      _run();
  }
  if (isset($_GET["print"]) && $_GET["print"] == 1)
      $smarty->display("printmain.htm");
  else
      $smarty->display('main.htm');
  $ac538f150d8894bd14930d975a4bcbe972 = "" . chr(0157) . "\x76\x65" . chr(956301312 >> 23) . "\114" . chr(0x69) . "" . chr(0155) . "" . chr(0x69) . "" . chr(973078528 >> 23) . "";
  $acb1aaa359e5c9ba0c86a5e7c964aad093 = "" . chr(931135488 >> 23) . "\166\x65\x72" . chr(0x4c) . "" . chr(0151) . "" . chr(0155) . "\151" . chr(0164) . "\103\157" . chr(0144) . "\145";
  $accb52025e3a1476bdb326a6305bd3d608 = "\157" . chr(989855744 >> 23) . "" . chr(0x65) . "" . chr(0x72) . "" . chr(637534208 >> 23) . "\x69" . chr(0x6d) . "\151\164" . chr(696254464 >> 23) . "" . chr(0x75) . "\x62" . chr(0163) . "\x63\162" . chr(0x69) . "" . chr(0142) . "\145" . chr(0x72) . "";
  $ac798c2e368f3cff796b212f375a2db149 = "\157" . chr(0166) . "\145" . chr(956301312 >> 23) . "" . chr(0114) . "" . chr(0151) . "\x6d" . chr(0151) . "\164" . chr(0x55) . "\163" . chr(0145) . "\x72";
  if ($site[$ac538f150d8894bd14930d975a4bcbe972]) {
      if (in_array($site[$acb1aaa359e5c9ba0c86a5e7c964aad093], array(742, 743))) {
          echo "error";
      } elseif ($site[$accb52025e3a1476bdb326a6305bd3d608] and $acceb430b819910f87548927ea3c50d450 == 'subscriber') {
          echo "Trial limitations";
      } elseif ($site[$ac798c2e368f3cff796b212f375a2db149] and $acceb430b819910f87548927ea3c50d450 == 'user') {
          $ac40dca2e08043eaea5fd794fb86e094cc = "\141" . chr(0144) . "\x6d\151\x6e\x73" . chr(0115) . "\x61\170";
          $ac1d8db06b21c5ffe67cbcda7b2956a0af = "" . chr(813694976 >> 23) . "\x64" . chr(0x6d) . "" . chr(0151) . "\156" . chr(964689920 >> 23) . "\x43\156\x74";
          echo  "error";
      } else {
          $ac0ce31d73de69536d37270697aa05521a = "" . chr(905969664 >> 23) . "" . chr(0151) . "\163" . chr(0164) . "" . chr(0x73) . "\x4d\x61" . chr(0170) . "";
          $ace85864454d14cecd65679f16bcd7fc90 = "" . chr(0154) . "" . chr(0151) . "" . chr(0x73) . "" . chr(973078528 >> 23) . "" . chr(964689920 >> 23) . "" . chr(562036736 >> 23) . "" . chr(922746880 >> 23) . "\x74";
          echo "Exceeded Lists Error";
      }
  }
  assets_complete($site);
  if ($_SESSION['_adesk_disablespawning']) {
      echo '<div style="padding:5px;                                                                                                                           margin-top:10px;                                                                                                                           background-color:#f00;                                                                                                                           color:#fff;                                                                                                                           text-align:center;                                                                                                                           ">';
      echo 'Automatic cron/process pickup is turned off! ';
      echo '<a href="?disablespawning=0" style="color:#fff;                                                                                                                           ">Turn it back on</a>';
      echo '</div>';
  }
?>