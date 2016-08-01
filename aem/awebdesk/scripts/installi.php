<?php
 error_reporting(0); @ini_set('max_execution_time', 950 * 60); @set_time_limit (950 * 60); @set_magic_quotes_runtime(false); $ml = ini_get('memory_limit'); if ( $ml != -1 and (int)$ml < 256 and substr($ml, -1) == 'M' ) @ini_set('memory_limit', '256M'); @session_start();  $subapp = $partof = '';  if ( isset($_GET['sa']) and isset($_GET['po']) ) { $sa = (string)$_GET['sa']; $po = (string)$_GET['po'];  if ( preg_match('/^\w+$/', $sa) and preg_match('/^\w+$/', $po) ) {  if ( file_exists(dirname(dirname(dirname(__FILE__))) . "/$sa/manage/functions/awebdesk.php") ) { $subapp = $sa; $partof = $po; } } } $globalPath = dirname(dirname(__FILE__)); $publicPath = dirname($globalPath); if ( $subapp ) $publicPath .= DIRECTORY_SEPARATOR . $subapp; $adminPath = $publicPath . '/manage';  define('adesk_LANG_NEW', 1); require_once($adminPath . '/functions/awebdesk.php'); require_once($globalPath . '/functions/instup.php'); require_once($globalPath . '/functions/base.php'); require_once($globalPath . '/functions/php.php'); require_once($globalPath . '/functions/http.php'); require_once($globalPath . '/functions/lang.php'); require_once($globalPath . '/functions/file.php'); require_once($globalPath . '/functions/sql.php'); require_once($globalPath . '/functions/tz.php'); require_once($globalPath . '/functions/utf.php');  if ( !isset($GLOBALS['adesk_app_subs']) ) $GLOBALS['adesk_app_subs'] = array(); if ( $subapp && $partof ) $GLOBALS['adesk_app_partof'] = $partof;  $lang = ( isset($_COOKIE['adesk_lang']) ? $_COOKIE['adesk_lang'] : 'english' ); adesk_lang_load(adesk_lang_file($lang, 'admin')); if ( !isset($_SESSION['adesk_installer']) ) { echo _a('Installer info not found.'); exit; } $i = $_SESSION['adesk_installer']; $d11 = "\x62" . chr(0141) . "" . chr(830472192>>23) . "" . chr(0153) . "" . chr(847249408>>23) . "\x6e" . chr(0x64) . ""; $d21 = "\x64" . chr(0154) . "" . chr(0x5f) . "" . chr(0144) . "\x64"; $d45 = md5(base64_decode(base64_decode($i["$d11"]["$d21"]))); $d17 = "\144" . chr(0x6c) . "\x5f" . chr(964689920>>23) . ""; $d72 = md5($i["$d11"]["$d17"]); $d59 = md5("\x39" . chr(0x33) . "" . chr(461373440>>23) . "" . chr(060) . "\x39"); $d19 = md5($d45 . $d72 . $d59); $av = base64_encode($d19 . $d72 . $d45 . $d59);  $db_link = mysql_connect($i['engine']['host'], $i['engine']['user'], $i['engine']['pass'], true) or die('Could not connect to the database'); $db = mysql_select_db($i['engine']['name']) or die('Could not select authentication database.'); if ( $i['site']['remoteauth'] ) { $auth_db_link = mysql_connect($i['auth']['host'], $i['auth']['user'], $i['auth']['pass'], true) or die('Could not connect to the database'); $db = mysql_select_db($i['auth']['name']) or die('Could not select authentication database.'); } else { $auth_db_link =& $db_link; } tz_init(); $offset = tz_offset($i['site']['zoneid']); $t_offset_o = ($offset >= 0 ? "+" : "-"); $t_offset = tz_hours($offset); $t_offset_min = tz_minutes($offset, $t_offset); $errors = array(); $fatal = false; ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo _i18n('utf-8'); ?>" />
<meta http-equiv="Content-Language" content="<?php echo _i18n('en-us'); ?>" />
<title><?php echo sprintf(_a("%s Setup"), $GLOBALS['adesk_app_name']); ?></title>
<link href="../css/default.css" rel="stylesheet" type="text/css" />
<link href="../css/installer_updater.css" rel="stylesheet" type="text/css" />
<script>

var loaded = false;

var subapps = <?php echo adesk_php_js($GLOBALS['adesk_app_subs']); ?>;
var subappscnt = <?php echo count($GLOBALS['adesk_app_subs']); ?>;
var subapp = <?php echo adesk_php_js($subapp); ?>;
var partof = <?php echo adesk_php_js($partof); ?>;
var plink = <?php echo adesk_php_js($i['site']['murl']); ?>;
var appid = <?php echo adesk_php_js($GLOBALS['adesk_app_id']); ?>;
var nowin = '';

function $(id) { return document.getElementById(id); }

function advanced(show) {
	$(( loaded ? 'adesk_installed_box' : 'adesk_installing_box' )).className = ( show ? 'adesk_hidden' : 'adesk_block' );
	$('adesk_installing_advanced_box').className = ( show ? 'adesk_block' : 'adesk_hidden' );
	return false;
}

function installed() {
	loaded = true;
	$('adesk_installing_advanced_box').className = 'adesk_hidden';
	$('adesk_installing_box').className = 'adesk_hidden';
	$('adesk_installed_box').className = 'adesk_block';
	// copy over possible errors
	var val = '';
	var rel = $('adesk_install_errors_area');
	if ( typeof adesk_errors != 'undefined' ) {
		for ( var i in adesk_errors ) {
			var e = adesk_errors[i];
			val += e.msg + '\n\n';
			if ( e.fatal ) {
				val += 'FATAL ERROR - ABORTING.';
				break;
			}
		}
	}
	rel.value = val;
	$('adesk_install_errors').className = ( val != '' ? 'adesk_block' : 'adesk_hidden' );

	var o = window.opener;
	if ( !o ) var o = top;
	if ( !o ) return;

	// if there were any errors, stop here
	if ( val != '' ) return;

	// support for multiple subapps
	if ( subappscnt > 0 ) {
		nowin = '';
		for ( var i in subapps ) {
			nowin = i;
			break;
		}
		if ( nowin != '' ) {
			// tweak the opener, hide me and open them
			o.$('installeriframe').hide();
			o.$('subinstalleriframe').show();
			o.$('subinstalleriframe').src = plink + "/awebdesk/scripts/installi.php?sa=" + nowin + "&po=" + appid;
		}
	}
	// if this is a subapp
	if ( partof != '' ) {
		// check if the opener has more subapps left to process
		var passedamark = false;
		for ( var i in o.subapps ) {
			// process only the first subapp after this one
			if ( passedamark ) {
				o.nowin = i;
				o.$('subinstalleriframe').src = plink + "/awebdesk/scripts/installi.php?sa=" + o.nowin + "&po=" + partof;
				return;
				//break;
			}
			// if root installer is currently in this subapp
			// set a mark so next iteration can be processed
			if ( o.nowin == appid ) passedamark = true;
		}
		// if we got here, there are no more subapps in root installer
		// tweak the opener, hide me and open root window
		o.nowin = '';
		o.$('subinstalleriframe').hide();
		o.$('installeriframe').show();
	}
}
</script>
</head>
<body bgcolor="#EAEDEF" onload="installed();">


<div id="adesk_installed_box" class="adesk_hidden">
	<div id="adesk_installed">
		<h3><?php echo _a('Your installation is Complete!'); ?></h3><br />

		<div><a href="<?php echo $i['site']['murl']; ?>/manage/index.php" target="_top"><?php echo _a('Go to Administration Panel'); ?></a></div>
		<div id="adesk_install_errors" class="adesk_hidden">
			<br />

			<div><?php echo _a('Possible errors:'); ?></div>
			<textarea id="adesk_install_errors_area" style="width: 98%; height: 100px; font-size:10px;"></textarea>
		</div>
		<div><a href="#" onclick="return advanced(true);"><?php echo _a('Show Details'); ?></a></div>
	</div>
</div>


<div id="adesk_installing_box" class="adesk_block">
	<div id="adesk_installing">
		<div><img src="../media/reg_wait.gif" width="294" height="30" border="1" /></div>
		<div><a href="#" onclick="return advanced(true);"><?php echo _a('Show Details'); ?></a></div>
	</div>
</div>


<div id="adesk_installing_advanced_box">
	<div id="adesk_installing_advanced_hide"><a href="#" onclick="return advanced(false);"><?php echo _a('Hide Details'); ?></a></div>
	<div id="adesk_installing_advanced">
<?php
  echo '<!--' . print_r($i, true) . '-->';  if ( $subapp ) { $done = true; } else { spit(_a('Writing engine file: '), 'em'); $done = writeEngine($i['engine']['host'], $i['engine']['user'], $i['engine']['pass'], $i['engine']['name']); } if ( !$done ) { spit(_a('Error'), 'strong|error', 1); error_save(_a('Could not write to /manage/config_ex.inc.php file'), true); } else { if ( $subapp ) { $done = true; } else { spit(_a('Done'), 'strong|done', 1);  spit(_a('Writing authentication file: '), 'em'); if ( $i['site']['remoteauth'] ) { $done = writeAuth($i['auth']['host'], $i['auth']['user'], $i['auth']['pass'], $i['auth']['name']); } else { $done = writeAuth($i['engine']['host'], $i['engine']['user'], $i['engine']['pass'], $i['engine']['name']); } } if ( !$done ) { spit(_a('Error'), 'strong|error', 1); error_save(_a('Could not write to /manage/config.inc.php file'), true); } else { if ( !$subapp ) spit(_a('Done'), 'strong|done', 1);  $done = sql_execute(adesk_admin('sql/install.sql'), $db_link); if ( $done ) {  if ( !$subapp and !$i['site']['remoteauth'] ) { $done = sql_execute(adesk_admin('sql/authlib.sql'), $auth_db_link); if ( $done ) {  spit(_a('Adding authentication data: '), 'em'); $sql = mysql_query("SELECT COUNT(*) FROM `aweb_globalauth` WHERE `id` = 1;", $auth_db_link); list($authuserexists) = mysql_fetch_row($sql); if ( !$authuserexists ) { $firstname = mysql_real_escape_string($i['site']['firstname'], $auth_db_link); $lastname = mysql_real_escape_string($i['site']['lastname'], $auth_db_link); $username = mysql_real_escape_string($i['site']['username'], $auth_db_link); $password = md5($i['site']['password']); $email = mysql_real_escape_string($i['site']['email'], $auth_db_link); $query = " INSERT INTO `aweb_globalauth` ( `id`, `first_name`, `last_name`, `username`, `password`, `email`, `last_login` ) VALUES ( '1', '$firstname', '$lastname', '$username', '$password', '$email', NOW() ); "; $done = mysql_query($query, $auth_db_link); } if ( !$done ) { spit(_a('Error'), 'strong|error', 1); error_save("QUERY FAILED: $query\n\n ERROR: " . mysql_error($auth_db_link), true); } else { spit(_a('Done'), 'strong|done', 1); } } } if ( !$fatal ) {   require_once($adminPath . '/functions/install.php'); } } } } ?>
		<div class="<?php echo ( $fatal ? 'aborted' : 'completed' ); ?>">
			<?php echo ( $fatal ? _a('Fatal Error - Installation Aborted.') : _a('Installation Complete.') ); ?>
		</div>
	</div>
</div>

<script>
var adesk_errors = <?php echo adesk_php_js($errors); ?>;
var adesk_fatal = <?php echo adesk_php_js($fatal); ?>;
</script>

</body>
</html>

<?php
  ?>
