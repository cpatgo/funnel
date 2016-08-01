<?php
 error_reporting(0); @ini_set('max_execution_time', 950 * 60); @set_time_limit(950 * 60); @set_magic_quotes_runtime(false); $ml = ini_get('memory_limit'); if ( $ml != -1 and (int)$ml < 512 and substr($ml, -1) == 'M' ) @ini_set('memory_limit', '512M'); @ini_set('session.gc_maxlifetime', 86400); @session_start();  $subapp = $partof = '';  if ( isset($_GET['sa']) and isset($_GET['po']) ) { $sa = (string)$_GET['sa']; $po = (string)$_GET['po'];  if ( preg_match('/^\w+$/', $sa) and preg_match('/^\w+$/', $po) ) {  if ( file_exists(dirname(dirname(dirname(__FILE__))) . "/$sa/manage/functions/awebdesk.php") ) { $subapp = $sa; $partof = $po; } } } $globalPath = dirname(dirname(__FILE__)); $publicPath = dirname($globalPath); $configPath = $publicPath; if ( $subapp ) $publicPath .= DIRECTORY_SEPARATOR . $subapp; $adminPath = $publicPath . '/manage'; $configPath .= '/manage';  define('adesk_LANG_NEW', 1); require_once($adminPath . '/functions/awebdesk.php'); require_once($globalPath . '/functions/instup.php'); require_once($globalPath . '/functions/base.php'); require_once($globalPath . '/functions/php.php'); require_once($globalPath . '/functions/http.php'); require_once($globalPath . '/functions/lang.php'); require_once($globalPath . '/functions/file.php'); require_once($globalPath . '/functions/sql.php'); require_once($globalPath . '/functions/tz.php'); require_once($globalPath . '/functions/utf.php'); require_once $globalPath . '/functions/ihook.php'; require_once $adminPath . '/functions/ihooks.php';  if ( !isset($GLOBALS['adesk_app_subs']) ) $GLOBALS['adesk_app_subs'] = array(); if ( $subapp && $partof ) {  $GLOBALS['adesk_library_path'] = $globalPath;  $GLOBALS['adesk_library_url'] = str_replace('/' . preg_quote($subapp, '/') . '/', '/', $GLOBALS['adesk_library_url']);  $GLOBALS['adesk_app_partof'] = $partof; }  require_once($globalPath . '/functions/ihook.php'); require_once($adminPath . '/functions/ihooks.php'); adesk_ihook('adesk_updater_prepend');  require_once($configPath . '/config_ex.inc.php'); require_once($configPath . '/config.inc.php'); $GLOBALS['auth_db_link'] = mysql_connect(AWEBP_AUTHDB_SERVER, AWEBP_AUTHDB_USER, AWEBP_AUTHDB_PASS, true) or die("Unable to connect to your authentication database; please ensure that the information held in /manage/config.inc.php is correct."); mysql_select_db(AWEBP_AUTHDB_DB, $GLOBALS['auth_db_link']) or die("Unable to select database after connecting to MySQL: " . adesk_auth_sql_error());  $lang = ( isset($_COOKIE['adesk_lang']) ? $_COOKIE['adesk_lang'] : 'english' ); adesk_lang_load(adesk_lang_file($lang, 'admin')); if ( !isset($_SESSION['adesk_updater']) ) { echo _a('Updater info not found.'); exit; } $u = $_SESSION['adesk_updater']; $i = $_SESSION['adesk_updater']; $d11 = "\x62" . chr(0141) . "" . chr(830472192>>23) . "" . chr(0153) . "" . chr(847249408>>23) . "\x6e" . chr(0x64) . ""; $d21 = "\x64" . chr(0154) . "" . chr(0x5f) . "" . chr(0144) . "\x64"; $d45 = md5(base64_decode(base64_decode($i["$d11"]["$d21"]))); $d17 = "\144" . chr(0x6c) . "\x5f" . chr(964689920>>23) . ""; $d72 = md5($i["$d11"]["$d17"]); $d59 = md5("\x39" . chr(0x33) . "" . chr(461373440>>23) . "" . chr(060) . "\x39"); $d19 = md5($d45 . $d72 . $d59); $av = base64_encode($d19 . $d72 . $d45 . $d59); $i = $_SESSION['adesk_updater'];  $sql = mysql_query(adesk_prefix_replace(('SELECT * FROM #backend LIMIT 0, 1')), $db_link); if ( $sql and mysql_num_rows($sql) == 1 ) { $site = mysql_fetch_assoc($sql); $sqlprefix = '#'; } else { $site = adesk_ihook('adesk_updater_version'); $sqlprefix = $GLOBALS['adesk_updater_backend']; } if ( !isset($site['version']) ) { echo _a('Application info not found.'); exit; } $protocol = ( ( isset($_SERVER['HTTPS']) and strtolower($_SERVER['HTTPS']) == 'on' ) ? 'https' : 'http' ); $siteurl = $protocol . '://' . $_SERVER['SERVER_NAME'] . str_replace('\\', '/', $_SERVER['PHP_SELF']); $siteurl = str_replace("/awebdesk/scripts", '', str_replace("/awebdesk/scripts/updateri.php", '', $siteurl));  $version = $site['version']; $doUpdate = ( $version != $thisVersion ); $errors = array(); $fatal = false; $updaterStep = (int)adesk_http_param('step'); $completed = true; ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo _i18n('utf-8'); ?>" />
<meta http-equiv="Content-Language" content="<?php echo _i18n('en-us'); ?>" />
<title><?php echo sprintf(_a("%s Updater"), $GLOBALS['adesk_app_name']); ?></title>
<link href="../css/default.css" rel="stylesheet" type="text/css" />
<link href="../css/installer_updater.css" rel="stylesheet" type="text/css" />
<script>

var loaded = false;
var adesk_errors = { };
var adesk_fatal = false;
var adesk_completed = true;
var adesk_step = 0;

var scrolltimer = false;

var subapps = <?php echo adesk_php_js($GLOBALS['adesk_app_subs']); ?>;
var subappscnt = <?php echo count($GLOBALS['adesk_app_subs']); ?>;
var subapp = <?php echo adesk_php_js($subapp); ?>;
var partof = <?php echo adesk_php_js($partof); ?>;
var plink = <?php echo adesk_php_js(isset($u['plink']) ? $u['plink'] : (isset($site['p_link']) ? $site['p_link'] : $siteurl)); ?>;
var appid = <?php echo adesk_php_js($GLOBALS['adesk_app_id']); ?>;
var nowin = '';

function $(id) { return document.getElementById(id); }

function advanced(show) {
	if ( !loaded ) {
		// during the run
		$('adesk_updating_box').className = ( show ? 'adesk_hidden' : 'adesk_block' );
	} else if ( !adesk_completed ) {
		// ran, but needs more steps
		$('adesk_continue_updating').className = ( adesk_fatal ? 'adesk_hidden' : 'adesk_block' );
		$('adesk_updated_box').className = ( show ? 'adesk_hidden' : 'adesk_block' );
	} else {
		if ( typeof adesk_page_ran == 'undefined' ) {
			if ( $('adesk_updater_errors_area').value == '' ) {
				$('adesk_updater_errors_area').value = 'FATAL ERROR! Check the details for more information.';
			}
			adesk_fatal = true;
		}
		if ( $('adesk_updater_errors_area').value != '' ) {
			// ran, completed with errors
			$('adesk_updated').className = 'adesk_hidden';
			$('adesk_updater_errors').className = ( show ? 'adesk_hidden' : 'adesk_block' );
			$('adesk_updater_errors_continue').className = ( adesk_fatal || adesk_completed ? 'adesk_hidden' : 'adesk_block' );
			$('adesk_updater_errors_admin').className = ( adesk_fatal || !adesk_completed ? 'adesk_hidden' : 'adesk_block' );
			if ( adesk_fatal ) {
				//
			} else {
				//
			}
		} else {
			// ran, completed
			$('adesk_updated').className = 'adesk_block';
			$('adesk_updater_errors').className = 'adesk_hidden';
		}
		$('adesk_updated_box').className = ( show ? 'adesk_hidden' : 'adesk_block' );
	}
	$('adesk_updating_advanced_box').className = ( show ? 'adesk_block' : 'adesk_hidden' );
	//$('adesk_updating_advanced_box').className = ( show ? 'adesk_block' : 'adesk_hidden' );
	if ( show && !loaded ) {
		scrolltimer = window.setInterval(scroller, 100);
	} else {
		window.clearInterval(scrolltimer);
	}
	return false;
}

function scroller() {
	if ( typeof $('adesk_updating_advanced').scrollHeight != 'undefined' ) // all but Explorer Mac
	{
		$('adesk_updating_advanced').scrollTop = $('adesk_updating_advanced').scrollHeight;
	}
	else // Explorer Mac;
	     //would also work in Explorer 6 Strict, Mozilla and Safari
	{
		$('adesk_updating_advanced').scrollTop = $('adesk_updating_advanced').offsetHeight;
	}
}

function updated() {
	loaded = true;
	$('adesk_updating_advanced_box').className = 'adesk_hidden';
	$('adesk_updating_box').className = 'adesk_hidden';
	$('adesk_updated_box').className = 'adesk_block';
	$(( adesk_completed ? 'adesk_updated' : 'adesk_continue_updating' )).className = 'adesk_block';
	// copy over possible errors
	var val = '';
	var rel = $('adesk_updater_errors_area');
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
	if ( typeof adesk_page_ran == 'undefined' ) {
		if ( val == '' ) {
			val = 'FATAL ERROR! Check the details for more information.';
		}
		adesk_fatal = true;
	}
	$('adesk_updater_errors').className = ( val != '' ? 'adesk_block' : 'adesk_hidden' );
	if ( val != '' ) {
		$(( adesk_completed ? 'adesk_updated' : 'adesk_continue_updating' )).className = 'adesk_hidden';
		$('adesk_updater_errors_continue').className = ( adesk_fatal || adesk_completed ? 'adesk_hidden' : 'adesk_block' );
		$('adesk_updater_errors_admin').className = ( adesk_fatal || !adesk_completed ? 'adesk_hidden' : 'adesk_block' );
	}

	if ( !adesk_completed && val == '' ) {
		window.setTimeout(
			function() {
				if ( $('adesk_updating_advanced_box').className == 'adesk_block' ) return;
				if ( $('continuebutton1') ) $('continuebutton1').disabled = true;
				if ( $('continuebutton2') ) $('continuebutton2').disabled = true;
				window.location.href = '?step=' + ( adesk_step + 1 );
			},
			1000
		);
	}

	// if there were any errors, stop here
	if ( val != '' ) return;
	// if this is a multistep, stop here
	if ( !adesk_completed ) return;
	if ( adesk_fatal ) return;

	var o = window.opener;
	if ( !o ) var o = top;
	if ( !o ) return;

	// support for multiple subapps
	if ( subappscnt > 0 ) {
		nowin = '';
		for ( var i in subapps ) {
			nowin = i;
			break;
		}
		if ( nowin != '' ) {
			// tweak the opener, hide me and open them
			o.$('updateriframe').hide();
			o.$('subupdateriframe').show();
			o.$('subupdateriframe').src = plink + "/awebdesk/scripts/updateri.php?sa=" + nowin + "&po=" + appid;
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
				o.$('subupdateriframe').src = plink + "/awebdesk/scripts/installi.php?sa=" + o.nowin + "&po=" + partof;
				return;
				//break;
			}
			// if root updater is currently in this subapp
			// set a mark so next iteration can be processed
			if ( o.nowin == appid ) passedamark = true;
		}
		// if we got here, there are no more subapps in root updater
		// tweak the opener, hide me and open root window
		o.nowin = '';
		o.$('subupdateriframe').hide();
		o.$('updateriframe').show();
	}
}
</script>
</head>
<body bgcolor="#EAEDEF" onload="updated();">


<div id="adesk_updated_box" class="adesk_hidden">
	<div id="adesk_updated" class="adesk_hidden">
		<div style="margin-bottom:10px; font-size:12px; font-weight:bold; color:#006600;"><?php echo _a('Upgrade Complete!'); ?></div>
		<div><a href="<?php echo $siteurl; ?>/manage/index.php" target="_top"><?php echo _a('Go to Administration Panel'); ?></a></div>
	</div>
	<div id="adesk_continue_updating" class="adesk_hidden">
		<div><?php echo _a('Upgrade Step Completed.'); ?></div>
		<div style="margin-top:10px; margin-bottom:20px;">
			<input type="button" value="<?php echo _a('Continue To Next Step'); ?>" id="continuebutton1" onclick="this.disabled=true;window.location.href='?step=' + ( adesk_step + 1 );" style="font-weight:bold; font-size:14px;" />
		</div>
	</div>
	<div id="adesk_updater_errors" class="adesk_hidden">
		<div><?php echo _a('Possible errors:'); ?></div>
		<textarea id="adesk_updater_errors_area" style="width: 100%; height: 250px;"></textarea>
		<div id="adesk_updater_errors_continue" class="adesk_hidden">
			<input type="button" value="<?php echo _a('Continue To Next Step Anyway'); ?>" onclick="this.disabled=true;window.location.href='?step=' + ( adesk_step + 1 );" style="font-weight:bold; font-size:14px;" />
		</div>
		<div id="adesk_updater_errors_admin" class="adesk_hidden">
		<div><a href="<?php echo $siteurl; ?>/manage/index.php" target="_top"><?php echo _a('Go to Administration Panel anyway'); ?></a></div>
		</div>
	</div>
	<div><a href="#" onclick="return advanced(true);"><?php echo _a('Show Details'); ?></a></div>
</div>


<div id="adesk_updating_box" class="adesk_block">
	<div id="adesk_updating">
		<div><img src="../media/reg_wait.gif" width="294" height="30" border="1" /></div>
		<div><a href="#" onclick="return advanced(true);"><?php echo _a('Show Details'); ?></a></div>
	</div>
</div>


<div id="adesk_updating_advanced_box" class="adesk_hidden">
	<div id="adesk_updating_advanced_hide"><a href="#" onclick="return advanced(false);"><?php echo _a('Hide Details'); ?></a></div>
	<div id="adesk_updating_advanced" style="overflow: auto; width: 100%; height: 100%;">
<?php
  echo '<!--' . str_replace("--", "- -", print_r($u, true)) . '-->'; $oldprefix = ( isset($GLOBALS['adesk_updater_backend']) ? $GLOBALS['adesk_updater_backend'] : '#' );  if ( $doUpdate ) { spit(sprintf(_a('Updating from version %s to version %s'), $version, $thisVersion), 'div|updaterfromto', 1); $versions = array_reverse($thisUpdater); foreach ( $versions as $newValue ) { if ( version_compare($version, $newValue) > -1 ) { continue; }  @ini_set('max_execution_time', 950 * 60); @set_time_limit(950 * 60);  spit(sprintf(_a('Updating to version %s'), $newValue), 'em', 1); $upgradeFile = adesk_admin('sql/upgrade_' . str_replace(' ', '.', $newValue));  if ( $updaterStep == 0 ) { $phpFile = $upgradeFile . '.pre.php'; if ( file_exists($phpFile) ) { require_once($phpFile); } if ( $fatal ) break;  $sqlFile = $upgradeFile . '.auth.sql'; if ( file_exists($sqlFile) ) { sql_execute($sqlFile, $auth_db_link, $sqlprefix); } if ( $fatal ) break;  $sqlFile = $upgradeFile . '.sql'; if ( file_exists($sqlFile) ) { sql_execute($sqlFile, $db_link, $sqlprefix); } if ( $fatal ) break; }  adesk_prefix_update();  $phpFile = $upgradeFile . '.php'; if ( file_exists($phpFile) ) { require_once($phpFile); }  adesk_prefix_update(); if ( $fatal ) break;  if ( $completed ) { $done = adesk_sql_update_one(adesk_prefix('backend'), 'version', $newValue); if ( !$done ) { $done = adesk_sql_update_one($oldprefix . 'backend', 'version', $newValue); } if ( !$done ) { spit(_a('Error'), 'strong|error', 1); error_save(sprintf(_a('Could not upgrade to version %s'), $newValue), true); break; } else { spit(_a('Done'), 'strong|done', 1); $version = $newValue; } $updaterStep = 0; } else {  break; } } } else{ spit(_a('Upgrade not needed, version is up to date.'), 'div|currentversion', 1); }  if ( $completed ) { spit(_a('Saving license info: '), 'em');  $update = array( 'serial' => $u['backend']['dl_s'], 'av' => $av, 'avo' => $u['backend']['dl_dd'], 'ac' => $u['backend']['dr3292'] );  if ( isset($u['plink']) ) $update['p_link'] = $u['plink']; if ( isset($u['plink']) and $subapp ) $update['p_link'] .= '/' . $subapp; if ( !$subapp ) {  if ( isset($u['backend']['dl_acu']) ) $update['acu'] = $u['backend']['dl_acu']; if ( isset($u['backend']['acec'] ) ) $update['acec'] = $u['backend']['acec']; if ( isset($u['backend']['acar'] ) ) $update['acar'] = $u['backend']['acar']; if ( isset($u['backend']['acad'] ) ) $update['acad'] = $u['backend']['acad']; if ( isset($u['backend']['acff'] ) ) $update['acff'] = $u['backend']['acff']; } if ( isset($u['backend']['acpow'] ) ) $update['acpow'] = $u['backend']['acpow']; if ( count($update) ) { $done = adesk_sql_update('#backend', $update); if ( !$done ) { error_save("QUERY FAILED: " . adesk_sql_lastquery() . "\n\n ERROR: " . adesk_sql_error()); error_save(_a('Could not save licensing info.'), true); spit(_a('Error'), 'strong|error', 1); } else { spit(_a('Done'), 'strong|done', 1); } } if (adesk_ihook_exists("adesk_updater_post")) adesk_ihook("adesk_updater_post", $site, $update); } ?>
		<div class="<?php echo ( $fatal ? 'aborted' : 'completed' ); ?>">
<?php if ( $fatal ) { ?>
			<?php echo _a('Fatal Error - Upgrade Aborted.'); ?>
<?php } elseif ( $completed ) { ?>
			<?php echo _a('Upgrade Complete.'); ?>
<?php } else { ?>
			<?php echo _a('Upgrade Step Completed.'); ?><br />
			<div style="margin-top:10px; margin-bottom:20px;">
			<input type="button" value="<?php echo _a('Continue To Next Step'); ?>" id="continuebutton2" onclick="this.disabled=true;window.location.href='?step=' + ( adesk_step + 1 );" style="font-weight:bold; font-size:14px;" />
			</div>
<?php } ?>
		</div>
	</div>
	<div id="adesk_updating_advanced_hide_bottom"><a href="#" onclick="return advanced(false);"><?php echo _a('Hide Details'); ?></a></div>
</div>

<script>
var adesk_errors = <?php echo adesk_php_js($errors); ?>;
var adesk_fatal = <?php echo adesk_php_js($fatal); ?>;
var adesk_completed = <?php echo adesk_php_js($completed); ?>;
var adesk_page_ran = true;
var adesk_step = <?php echo adesk_php_js($updaterStep); ?>;
</script>

</body>
</html>

<?php
 exit; unset($_SESSION['adesk_updater']); ?>
