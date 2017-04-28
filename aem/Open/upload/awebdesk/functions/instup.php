<?php
  function smarty_get()
  {
      require_once(awebdesk('smarty/Smarty.class.php'));
      $smarty = new Smarty();
      $smarty->compile_check = true;
      $smarty->template_dir = array(awebdesk('templates'), awebdesk('js'), adesk_base('templates'), adesk_base('js'));
      $smarty->compile_dir = adesk_base('cache/public');
      $smarty->plugins_dir = array(awebdesk('smarty/plugins'), awebdesk('smarty_plugins'));
      $smarty->assign('year', date('Y'));
      return $smarty;
  }
  function param_get($param)
  {
      if (isset($_POST[$param]))
          return $_POST[$param];
      if (isset($_GET[$param]))
          return $_GET[$param];
      return null;
  }
  
  
  function writeEngine($host, $user, $pass, $db)
  {
      $user = str_replace("'", "\'", $user);
      $pass = str_replace("'", "\'", $pass);
      $path = adesk_admin('config_ex.inc.php');
      if (!is_writable($path))
          return false;
      $file = fopen($path, 'w');
      if (!$file)
          return false;
      $fp = fwrite($file, '<?php');
      $fp = fwrite($file, "\r\n");
      $fp = fwrite($file, "define('AWEBP_DB_HOST', '$host');\r\n");
      $fp = fwrite($file, "define('AWEBP_DB_USER', '$user');\r\n");
      $fp = fwrite($file, "define('AWEBP_DB_PASS', '$pass');\r\n");
      $fp = fwrite($file, "define('AWEBP_DB_NAME', '$db');\r\n");
      $fp = fwrite($file, "\r\n\r\n");
      $fp = fwrite($file, '$GLOBALS["db_link"] = mysql_connect(AWEBP_DB_HOST, AWEBP_DB_USER, AWEBP_DB_PASS, true);');
      $fp = fwrite($file, "\r\n");
      $fp = fwrite($file, '$db_linkdb = mysql_select_db(AWEBP_DB_NAME, $GLOBALS["db_link"]);');
      $fp = fwrite($file, "\r\n");
      $fp = fwrite($file, '?>');
      fclose($file);
      return true;
  }
  function writeAuth($host, $user, $pass, $db)
  {
      $user = str_replace("'", "\'", $user);
      $pass = str_replace("'", "\'", $pass);
      $path = adesk_admin('config.inc.php');
      if (!is_writable($path))
          return false;
      $file = fopen($path, 'w');
      if (!$file)
          return false;
      $fp = fwrite($file, '<?php');
      $fp = fwrite($file, "\r\n");
      $fp = fwrite($file, "define('AWEBP_AUTHDB_SERVER', '$host');\r\n");
      $fp = fwrite($file, "define('AWEBP_AUTHDB_USER', '$user');\r\n");
      $fp = fwrite($file, "define('AWEBP_AUTHDB_PASS', '$pass');\r\n");
      $fp = fwrite($file, "define('AWEBP_AUTHDB_DB', '$db');\r\n");
      $fp = fwrite($file, '?>');
      fclose($file);
      return true;
  }
  function sql_execute($fileName, &$conn, $prefix = '#')
  {
      $noerrors = true;
      $rawStatements = file_get_contents($fileName);
      if ($prefix == '#')
          $prefix = adesk_prefix();
      $rawStatements = str_replace(' `#', ' `' . $prefix, $rawStatements);
      if (strpos($rawStatements, "InnoDB") !== false) {
          if (!adesk_sql_supports_engine("InnoDB"))
              $rawStatements = str_replace("InnoDB", "MyISAM", $rawStatements);
      }
      if (strpos($rawStatements, "utf8") !== false) {
          if (!adesk_sql_supports_charset("utf8"))
              $rawStatements = str_replace("DEFAULT CHARSET=utf8 DEFAULT COLLATE = utf8_general_ci", "", $rawStatements);
      }
      $statements = preg_split("/\r?\n/", $rawStatements);
      foreach ($statements as $statement) {
          $statement = trim($statement);
          if ($statement != '' and substr($statement, 0, 1) != '#' and substr($statement, 0, 2) != '--') {
              $supress = substr($statement, 0, 1) == '|';
              if ($supress) {
                  $statement = substr($statement, 1);
              }
              $retval = mysql_query($statement, $conn);
              if ($supress)
                  continue;
              $action = adesk_sql_query_info($statement);
              spit($action['message'], 'em');
              if ($retval != false) {
                  spit('Done', 'strong|done', 1);
              } else {
                  spit('Error', 'strong|error', 1);
                  $action['fatal'] = true;

                  if (preg_match('/duplicate|already exists|Can\'t DROP/i', mysql_error($conn))) {
                      $action['fatal'] = false;
                  } else {
                      $noerrors = false;
                  }
                  error_save("QUERY FAILED: $statement\n\n ERROR: " . mysql_error($conn), $action['fatal']);
              }
          }
      }
      return $noerrors;
  }
  function sql_indexes($indexes)
  {
      $newIndexes = array();
      foreach ($indexes as $table => $v) {
          $newIndexes[$table] = array();
          $sql = adesk_sql_query("SHOW INDEX FROM `$table`");
          while ($row = mysql_fetch_assoc($sql)) {
              if ($row['Key_name'] != 'PRIMARY') {
                  if (isset($newIndexes[$table][$row['Column_name']])) {
                      adesk_sql_query("ALTER TABLE `$table` DROP INDEX `$row[Column_name]`");
                  } else {
                      $fulltext = (isset($row['Index_type']) ? $row['Index_type'] == 'FULLTEXT' : $row['Comment'] == 'FULLTEXT');
                      if (!isset($v[$row['Column_name']])) {
                          adesk_sql_query("ALTER TABLE `$table` DROP INDEX `$row[Column_name]`");
                      } else {
                          $change = $fulltext != $v[$row['Column_name']];
                          if ($change) {
                              $change2what = ($v[$row['Column_name']] === true ? 'FULLTEXT' : 'INDEX');
                              $length = (is_numeric($v[$row['Column_name']]) ? '( ' . $v[$row['Column_name']] . ' )' : '');
                              adesk_sql_query("ALTER TABLE `$table` DROP INDEX `$row[Key_name]`, ADD $change2what `$row[Column_name]` ( `$row[Column_name]` $length)");
                          }
                          $newIndexes[$table][$row['Column_name']] = $v[$row['Column_name']];
                      }
                  }
              }
          }
          foreach ($v as $field => $fulltext) {
              if (!isset($newIndexes[$table][$field])) {
                  $addwhat = ($fulltext === true ? 'FULLTEXT' : 'INDEX');
                  $length = (is_numeric($fulltext) ? '( ' . $fulltext . ' )' : '');
                  adesk_sql_query("ALTER TABLE `$table` ADD $addwhat `$field` ( `$field` $length)");
              }
          }
      }
  }
  function permissions_check(&$smarty, $installer = false)
  {
      $template = ($installer ? 'install' : 'updater');
      $folders = array();
      $folders[] = adesk_base('cache');
      $folders[] = adesk_base('cache/public');
      if (isset($GLOBALS['adesk_writables'])) {
          $folders = array_merge($folders, $GLOBALS['adesk_writables']);
      }
      $useSmarty = false;
      $scream = false;
      $errmsg = array();
      if (!$scream) {
          foreach ($folders as $f) {
              if (is_dir($f)) {
                  $r = writable_check($f);
                  if (!$f) {
                      $scream = true;
                      $errmsg['src'] = $f;
                      $errmsg['title'] = sprintf(_a("Folder %s does not exist or does not have full write permissions."), $f);
                      $errmsg['descript'] = sprintf(_a("Change the permissions of folder %s so that it has full read/write access (CHMOD 777 on linux) and refresh this page to continue."), $f);
                      break;
                  } elseif ($f == adesk_base('cache/public')) {
                      $useSmarty = true;
                  }
              }
          }
      }
      $engine = adesk_admin('config_ex.inc.php');
      $default = adesk_admin('default.config_ex.inc.php');
      if (!file_exists($engine)) {
          if (!@rename($default, $engine)) {
              $scream = true;
              $errmsg['src'] = $engine;
              $errmsg['title'] = _a("Unable to change the file default.config_ex.inc.php to config_ex.inc.php.");
              $errmsg['descript'] = sprintf(_a("Open /%s/manage folder on your server and change default.config_ex.inc.php to config_ex.inc.php and ensure config_ex.inc.php has full read/write permissions (chmod 777 on linux)."), $GLOBALS['adesk_app_id']);
          }
      }
      if (!$scream) {
          $engine = adesk_admin('config.inc.php');
          $default = adesk_admin('default.config.inc.php');
          if (!file_exists($engine)) {
              if (!@rename($default, $engine)) {
                  $scream = true;
                  $errmsg['src'] = $engine;
                  $errmsg['title'] = _a("Unable to change the file default.config.inc.php to config.inc.php.");
                  $errmsg['descript'] = sprintf(_a("Open /%s/manage folder on your server and change default.config.inc.php to config.inc.php and ensure config.inc.php has full read/write permissions (chmod 777 on linux)."), $GLOBALS['adesk_app_id']);
              }
          }
      }
      if ($scream) {
          if (!$useSmarty) {
              die("
        <html>
          <meta http-equiv='Content-Type' content='text/html; charset=" . _i18n('utf-8') . "' />
          <meta http-equiv='Content-Language' content='" . _i18n('en-us') . "' />
          <title>" . sprintf(_a('%s Setup'), $appname) . "</title> <head> <link href='../awebdesk/css/default.css' rel='stylesheet' type='text/css' /> <link href='../awebdesk/css/instup.css' rel='stylesheet' type='text/css' /> </head> <body> <div class='adesk_install_box'> <div class='errortitle'>$errmsg[title]</div> <div class='errordescript'>$errmsg[descript]</div> <div class='errorsrc'>$errmsg[src]</div> </div> </body> </html> ");
          } else {
              $smarty->assign('errmsg', $errmsg);
              $smarty->assign('content_template', $template . '.error.htm');
              $smarty->display($template . '.htm');
          }
          exit;
      }
  }
  function functions_check(&$smarty, $installer = false)
  {
      $template = ($installer ? 'install' : 'updater');
      $functions = (isset($GLOBALS['adesk_functions']) ? $GLOBALS['adesk_functions'] : array());
      $functions[] = 'mysql_connect';
      $errmsg = array();
      foreach ($functions as $f) {
          if (!function_exists($f)) {
              $errmsg['src'] = $f;
              $errmsg['title'] = sprintf(_a("Function %s does not exist."), $f);
              $errmsg['descript'] = sprintf(_a("Your system does not appear to support a function needed by this application to work. Please contact your server admin to rectify this issue."), $f);
              break;
          }
      }
      if (count($errmsg) == 3) {
          $smarty->assign('errmsg', $errmsg);
          $smarty->assign('content_template', $template . '.error.htm');
          $smarty->display($template . '.htm');
          exit;
      }
  }
  function systawebdesk_check(&$smarty, $installer = false)
  {
      $template = ($installer ? 'installer' : 'updater');
      $sessionProb = (!isset($_SESSION['adesk_' . $template]) or !is_array($_SESSION['adesk_' . $template]));
      $settings = adesk_php_settings();
      $phpProb = (version_compare($GLOBALS['adesk_requirements']['php'], phpversion()) == 1);
      $disabledFunctions = trim((string)ini_get('disable_functions'));
      $uploadAllowed = (bool)ini_get('file_uploads');
      $uploadLimit = adesk_php_inisize(ini_get('upload_max_filesize'));
      $uploadProb = ($uploadLimit != 0 and $uploadLimit < 1024 * 1024);
      $uploadLimit = adesk_file_humansize($uploadLimit);
      $postLimit = adesk_php_inisize(ini_get('post_max_size'));
      $postProb = ($postLimit != 0 and $postLimit < 1024 * 1024);
      $postLimit = adesk_file_humansize($postLimit);
      $safeMode = (bool)ini_get('safe_mode');
      $executionLimit = (int)ini_get('max_execution_time');
      $execProb = ($safeMode and $executionLimit != 0 and $executionLimit < 30);
      $memoryLimit = adesk_php_inisize(ini_get('memory_limit'));
      $memProb = ($safeMode and $memoryLimit != -1 and $memoryLimit < 32 * 1024 * 1024);
      $memoryLimit = adesk_file_humansize($memoryLimit);
      $gdLib = (function_exists('gd_info') ? gd_info() : false);
      $requirementsMet = (!$phpProb and !$sessionProb and !$execProb and !$memProb);
      $smarty->assign('sessionProb', $sessionProb);
      $smarty->assign('systeminfo', $settings);
      $smarty->assign('phpProb', $phpProb);
      $smarty->assign('disabledFunctions', $disabledFunctions);
      $smarty->assign('uploadAllowed', $uploadAllowed);
      $smarty->assign('uploadLimit', $uploadLimit);
      $smarty->assign('uploadProb', $uploadProb);
      $smarty->assign('postLimit', $postLimit);
      $smarty->assign('postProb', $postProb);
      $smarty->assign('executionLimit', $executionLimit);
      $smarty->assign('execProb', $execProb);
      $smarty->assign('memoryLimit', $memoryLimit);
      $smarty->assign('memProb', $memProb);
      $smarty->assign('safeMode', $safeMode);
      $smarty->assign('gdLib', $gdLib);
      return $requirementsMet;
  }
  function writable_check($folder)
  {
      $tf1 = $folder . DIRECTORY_SEPARATOR . 'acp1';
      $tf2 = $folder . DIRECTORY_SEPARATOR . 'acp2';
      if (!file_exists($folder))
          return false;
      if (!is_dir($folder))
          return false;
      if (!is_writable($folder))
          return false;
      $fp = @fopen($tf1, 'a');
      if (!$fp)
          return false;
      if (!fwrite($fp, 'test'))
          return false;
      @fclose($fp);
      if (!file_exists($tf1))
          return false;
      if (!rename($tf1, $tf2)) {
          @unlink($tf1);
          return false;
      }
      if (!file_exists($tf2)) {
          @unlink($tf1);
          return false;
      }
      $fp = @fopen($tf2, 'a');
      if (!$fp)
          return false;
      $data = @fread($fp);
      @fclose($fp);
      if ($data != 'test') {
          @unlink($tf2);
          return false;
      }
      @unlink($tf2);
      return true;
  }
  function verifyVersion($dblink, $version)
  {
      $rs = mysql_query("SELECT VERSION()", $dblink);
      if ($row = mysql_fetch_row($rs)) {
          $parts = explode(".", $row[0]);
          if (count($parts) > 1)
              $float = $parts[0] . "." . $parts[1];
          else
              $float = $parts[0];
          if (floatval($float) < floatval($version)) {
              return sprintf("We require MySQL version %s or higher (you have %s); please upgrade your MySQL server to continue", $version, $float);
          }
          return "";
      }
      return _a("Could not verify MySQL version:") . " " . mysql_error();
  }
  function database_check()
  {
      $type = adesk_http_param("type");
      $host = adesk_http_param("host");
      $user = adesk_http_param("user");
      $pass = adesk_http_param("pass");
      $name = adesk_http_param("name");
      $create = (int)adesk_http_param("create");
      $clear = (int)adesk_http_param("clear");
      $r = array('succeeded' => false, 'message' => '', 'settings' => array(), 'tables' => array(), 'found' => 0);
      if (!isset($_SESSION['adesk_installer'])) {
          $r['message'] = _a('License Error. Please restart installation.');
          return $r;
      }
      $prfx = ($type == 'auth' ? $type . '_' : '');
      $GLOBALS[$prfx . 'db_link'] = @mysql_connect($host, $user, $pass, true);
      if (!$GLOBALS[$prfx . 'db_link']) {
          $r['message'] = sprintf(_a('Error: Could not connect to host %s as user %s.'), $host, $user);
          return $r;
      }
      define("REQUIRE_MYSQLVER", "4.1");
      if (defined("REQUIRE_MYSQLVER")) {
          $r['message'] = verifyVersion($GLOBALS[$prfx . 'db_link'], REQUIRE_MYSQLVER);
          if ($r['message'] != "") {
              return $r;
          }
      }
      if ($create) {
          $db = mysql_real_escape_string($name, $GLOBALS[$prfx . 'db_link']);
          $x = mysql_query("CREATE DATABASE `$db`;", $GLOBALS[$prfx . 'db_link']);
          if (!$x) {
              $r['message'] = sprintf(_a('Error %d: %s'), mysql_errno($GLOBALS[$prfx . 'db_link']), mysql_error($GLOBALS[$prfx . 'db_link']));
              return $r;
          }
          @mysql_query("ALTER DATABASE `$db` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;", $GLOBALS[$prfx . 'db_link']);
      }
      $x = @mysql_select_db($name, $GLOBALS[$prfx . 'db_link']);
      if (!$x) {
          $r['message'] = sprintf(_a('Error %d: %s'), mysql_errno($GLOBALS[$prfx . 'db_link']), mysql_error($GLOBALS[$prfx . 'db_link']));
          return $r;
      }
      $r['settings'] = adesk_php_settings_mysql($GLOBALS[$prfx . 'db_link']);
      $minSQL = (isset($GLOBALS['requirements']['mysql']) ? $GLOBALS['requirements']['mysql'] : 4.1);
      if (version_compare($minSQL, $r['settings']['mysqlversion']) == 1) {
          $r['message'] = sprintf(_a('Minimal MySQL Server requirement not met. Found: %s; Required: %s'), $r['settings']['mysqlversion'], $minSQL);
          return $r;
      }
      $r['succeeded'] = true;
      $r['message'] = _a('Connection successful.');
      $prefix = ($type == 'auth' ? 'aweb_' : $GLOBALS['adesk_prefix_use']);
      $tables = addcslashes(mysql_real_escape_string($prefix, $GLOBALS[$prfx . 'db_link']), '%_');
      $sql = mysql_query("SHOW TABLES LIKE '$tables%'", $GLOBALS[$prfx . 'db_link']);
      while ($row = mysql_fetch_row($sql))
          $r['tables'][] = $row[0];
      if (in_array($GLOBALS['adesk_app_id'], array('ahd', 'awebdeskhd', 'HD'))) {
          $sql = mysql_query("SHOW TABLES LIKE 'kb\_%'", $GLOBALS[$prfx . 'db_link']);
          while ($row = mysql_fetch_row($sql))
              $r['tables'][] = $row[0];
      }
      $r['found'] = count($r['tables']);
      if ($clear) {
          foreach ($r['tables'] as $t) {
              $t = mysql_real_escape_string($t, $GLOBALS[$prfx . 'db_link']);
              mysql_query("DROP TABLE `$t`;");
          }
          $sql = mysql_query("SHOW TABLES LIKE '$tables%'", $GLOBALS[$prfx . 'db_link']);
          while ($row = mysql_fetch_row($sql))
              $r['tables'][] = $row[0];
          $r['found'] = count($r['tables']);
      }
      if ($r['succeeded']) {
          $_SESSION['adesk_installer'][$type] = array('host' => $host, 'user' => $user, 'pass' => $pass, 'name' => $name);
      }
      return $r;
  }
  function admin_check()
  {
      $r = array('succeeded' => false, 'message' => '', );
      if (!isset($_SESSION['adesk_installer'])) {
          $r['message'] = _a('License Error. Please restart installation.');
          return $r;
      }
      if ($_POST['remoteauth']) {
          if (!isset($_SESSION['adesk_installer']['auth'])) {
              $r['message'] = _a('Authentication Info not found. Please restart installation.');
              return $r;
          }
          $_GET = array_merge($_GET, $_SESSION['adesk_installer']['auth']);
          $_GET['type'] = 'auth';
          $db = database_check();
          if (!$db['succeeded'])
              return $db;
          $pass = md5($_POST['password']);
          $sql = mysql_query(" SELECT COUNT(*) FROM `aweb_globalauth` WHERE `id` = '1' AND `username` = 'admin' AND `password` = '$pass' ", $GLOBALS['auth_db_link']);
          if (!$sql) {
              $r['message'] = _a('Authentication Table not found (bad database info?). Please restart installation.');
              return $r;
          }
          list($found) = mysql_fetch_row($sql);
          $r['succeeded'] = ($found == 1);
          if (!$r['succeeded']) {
              $r['message'] = _a('Admin user not authenticated.');
          } else {
              $r['message'] = _a('Admin user authenticated.');
          }
      } else {
          $r['message'] = _a('Admin user authenticated.');
          $r['succeeded'] = true;
      }
      if ($r['succeeded']) {
          $_SESSION['adesk_installer']['site'] = $_POST;
      }
      return $r;
  }
  function auth_check()
  {
      $r = array('succeeded' => false, 'message' => '', 'dl_s' => '', );
      if (!isset($_POST['password'])) {
          $r['message'] = _a("No password given");
          return $r;
      }
      $password = $_POST["password"];
      if (!isset($_SESSION['adesk_updater'])) {
          $r['message'] = _a('License Error. Please restart the updater.');
          return $r;
      }
      if (!file_exists(adesk_admin('config.inc.php'))) {
          $r['succeeded'] = true;
          $r['message'] = _a('Authentication file not found (possibly old version?)');
          return $r;
      }
      require_once(adesk_admin('config_ex.inc.php'));
      if (isset($db_link) and !isset($GLOBALS['db_link']))
          $GLOBALS['db_link'] = $db_link;
      require_once(adesk_admin('config.inc.php'));
      if (!defined('AWEBP_AUTHDB_SERVER')) {
          $r['succeeded'] = true;
          $r['message'] = _a('Authentication info not found (possibly old version?)');
          return $r;
      }
      $GLOBALS['auth_db_link'] = mysql_connect(AWEBP_AUTHDB_SERVER, AWEBP_AUTHDB_USER, AWEBP_AUTHDB_PASS, true) or die("Unable to connect to your authentication database; please ensure that the information held in /manage/config.inc.php is correct.");
      mysql_select_db(AWEBP_AUTHDB_DB, $GLOBALS['auth_db_link']) or die("Unable to select database after connecting to MySQL: " . adesk_auth_sql_error());
      $pass = md5($password);
      $sql = mysql_query(" SELECT COUNT(*) FROM `aweb_globalauth` WHERE `id` = '1' AND `username` = 'admin' AND `password` = '$pass' ", $GLOBALS['auth_db_link']);
      if (!$sql) {
          $r['succeeded'] = true;
          $r['message'] = _a('Authentication Table not found (possibly old version?).');
          return $r;
      }
      list($found) = mysql_fetch_row($sql);
      $r['succeeded'] = ($found == 1);
      if (!$r['succeeded']) {
          $serial = adesk_sql_escape($password);
          $sql = mysql_query(adesk_prefix_replace("SELECT COUNT(*) FROM #backend WHERE serial = '$password'"), $GLOBALS["db_link"]);
          if ($sql) {
              $row = mysql_fetch_row($sql);
              $r["succeeded"] = ($row[0] == 1);
          }
      }
      if (!$r['succeeded']) {
          $r['message'] = _a('Admin user not authenticated.');
          return $r;
      } else {
          $r['message'] = _a('Admin user authenticated.');
          $sql = mysql_query(adesk_prefix_replace(('SELECT * FROM #backend LIMIT 0, 1')), $GLOBALS['db_link']);
          if ($sql and mysql_num_rows($sql) == 1) {
              $site = mysql_fetch_assoc($sql);
          } else {
              $site = adesk_ihook('adesk_updater_version');
          }
          if (isset($site['serial'])) {
              $r['dl_s'] = $site['serial'];
          }
      }
      return $r;
  }
  function plink_send($plink)
  {
      $_SESSION['adesk_updater']['plink'] = $plink;
      return adesk_ajax_api_result(1, _a("Software Settings Saved! Starting the upgrade..."));
  }
  function license_check(&$smarty, $installer = false, $path = null)
  {
      return true;
      if (!$path)
          $path = adesk_basedir() . DIRECTORY_SEPARATOR . 'manage';
      $s = ($installer ? 'adesk_installer' : 'adesk_updater');
      $errmsg = array();
      $db98 = "" . chr(0x62) . "" . chr(813694976 >> 23) . "\x63\x6b\x65" . chr(0x6e) . "\x64";
      $db78 = "" . chr(0x62) . "\x61\x63" . chr(897581056 >> 23) . "" . chr(847249408 >> 23) . "\156" . chr(0x64) . "";
      $db99 = "" . chr(0142) . "" . chr(0x61) . "" . chr(0x63) . "" . chr(897581056 >> 23) . "" . chr(0145) . "" . chr(922746880 >> 23) . "" . chr(838860800 >> 23) . "";
      $ds12 = "" . chr(838860800 >> 23) . "" . chr(0x6c) . "" . chr(796917760 >> 23) . "" . chr(0x73) . "";
      $dr9 = "\x64" . chr(956301312 >> 23) . "" . chr(0x33) . "" . chr(419430400 >> 23) . "" . chr(0x39) . "" . chr(062) . "";
      $act = $GLOBALS['adesk_app_id'] . '-' . $_SESSION[$s]["$db98"]["$ds12"] . '-' . $_SERVER['SERVER_NAME'] . '-' . $path;
      if (md5($act) != $_SESSION[$s]["$db78"]["$dr9"]) {
          $errmsg['src'] = "" . chr(578813952 >> 23) . "\x72" . chr(956301312 >> 23) . "" . chr(0x6f) . "\162\40" . chr(461373440 >> 23) . "" . chr(064) . "\x30";
          $errmsg['title'] = "" . chr(0111) . "" . chr(0156) . "" . chr(0x76) . "" . chr(0x61) . "" . chr(0x6c) . "" . chr(0151) . "\144" . chr(040) . "" . chr(612368384 >> 23) . "" . chr(0x6e) . "\163" . chr(0x74) . "" . chr(0141) . "\154" . chr(0x6c) . "\x61\x74" . chr(880803840 >> 23) . "" . chr(0157) . "" . chr(922746880 >> 23) . "\x2e";
          $errmsg['descript'] = '';
      } elseif (md5($GLOBALS['adesk_app_id']) != $_SESSION[$s]["$db99"]['dl_t']) {
          $errmsg['src'] = "" . chr(578813952 >> 23) . "" . chr(956301312 >> 23) . "\162\x6f\162" . chr(268435456 >> 23) . "\67" . chr(436207616 >> 23) . "\61";
          $errmsg['title'] = "" . chr(612368384 >> 23) . "" . chr(922746880 >> 23) . "" . chr(0166) . "" . chr(813694976 >> 23) . "\154" . chr(0151) . "" . chr(0x64) . "\x20" . chr(0x46) . "" . chr(981467136 >> 23) . "" . chr(0156) . "\143\164" . chr(880803840 >> 23) . "" . chr(0157) . "" . chr(922746880 >> 23) . "" . chr(385875968 >> 23) . "";
          $errmsg['descript'] = '';
      }
      if (count($errmsg) > 0) {
          $smarty->assign('errmsg', $errmsg);
          $smarty->assign('content_template', 'install.error.htm');
          $smarty->display('install.htm');
          exit;
      }
  }
  function spit($text, $tag = '', $br = 0)
  {
      if (isset($GLOBALS['fatal']) && $GLOBALS['fatal'])
          return;
      $arr = explode('|', $tag);
      $tag = $arr[0];
      $cls = (isset($arr[1]) ? ' class="' . $arr[1] . '"' : '');
      if ($tag != '')
          echo $tag == '!' ? '<!-- ' : "<$tag$cls>";
      echo $text;
      if ($text == _a('Done'))
          echo ' <!-- ' . date('Y-m-d H:i:s') . ' -->';
      if ($tag != '')
          echo $tag == '!' ? ' -->' : "</$tag>";
      if ($br)
          echo "<br />\n";
      adesk_flush();
  }
  function spitredirect($url, $auto = true)
  {
      spit('
    <script>
      $("adesk_updated").innerHTML="<div align=\'center\'><a href=\'' . $url . '\'><strong>' . _a('Run next batch') . '</strong></a></div>";
      ' . ($auto ? '// do auto redirect as well
      window.location.href="' . $url . '";' : '') . '
    </script>
  ');
      spit('<a href="' . $url . '">' . _a('Run next batch') . '</a>', 'div');
      exit;
  }
  function error_save($message, $fatal = false)
  {
      if (isset($GLOBALS['fatal']) && $GLOBALS['fatal'])
          return;
      $GLOBALS['errors'][] = array('msg' => $message, 'fatal' => $fatal);
      if ($fatal)
          $GLOBALS['fatal'] = true;
  }
  function systeminfo($installer = false)
  {
      $arr = array('php' => phpversion(), 'os' => PHP_OS, 'sapi' => PHP_SAPI, );
      if (!$installer) {
          $arr['mysql'] = adesk_sql_select_one("SELECT VERSION()");
      }
      return base64_encode(serialize($arr));
  }
?>