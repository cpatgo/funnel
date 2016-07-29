<?php
require_once dirname(__FILE__) . '/manage.php';
require_once dirname(__FILE__) . '/prefix.php';
require_once dirname(__FILE__) . '/sql.php';
require_once dirname(__FILE__) . '/session.php';
require_once dirname(__FILE__) . '/ihook.php';
require_once(adesk_basedir() . '/manage/functions/censor.php');
require_once(adesk_basedir() . '/cache/serialkey.php');
if (!defined('adesk_TRIAL_PREFIX'))
    define('adesk_TRIAL_PREFIX', '/^TRIAL-/');
if (!defined('adesk_FREE_PREFIX')) {
    define('adesk_FREE_PREFIX', "FREE-");
    define('adesk_FREE_PREFIX_LEN', 5);
}

function licensecheck() {
	
 
  $liecensor=new liecensor;
  $liecensor->license_key = SERIAL_KEY;
  $liecensor->api_server='http://customers.awebdesk.com/api/index.php';
  $liecensor->secret_key='AEMM#AWEBDESK';
  $liecensor->validate_download_access=true; 
  $liecensor->release_date='25th June 2014';
  $liecensor->remote_port=80;  
  $liecensor->remote_timeout=10;  
  $liecensor->local_key_grace_period='30';  
  $liecensor->local_key_storage='filesystem';  
  $liecensor->local_key_path= adesk_basedir() .'/cache/'; // use relative paths  
  $liecensor->local_key_name = 'key.php'; 
  $liecensor->valid_for_product_tiers='38,39,40';   
  if (isset($_GET['clear_local_key_cache']))   
    {   
      $liecensor->clear_cache_local_key();   
    }  
  $liecensor->validate();
  $key_data=$liecensor->key_data;  
  $custom=$liecensor->key_data['custom_fields'];  
  if ($liecensor->errors) { die($liecensor->errors); }
  unset($liecensor);
  if(isset($custom['users']))
  define('AEMUSERS', $custom['users']);
  else
  define('AEMUSERS', 0);
  define('AEMCOPYRIGHT', $custom['copyright']);
  define('AEMVERSION', $custom['version']);
 	
	
}





function adesk_site_istrial($serial)
{
    return preg_match(adesk_TRIAL_PREFIX, $serial);
}
function adesk_site_isfree($serial)
{
    return substr($serial, 0, adesk_FREE_PREFIX_LEN) == adesk_FREE_PREFIX;
}
function adesk_site_triolive_limit($serial)
{
    if (preg_match('/^TRIOLIVE-ASP25/', $serial))
        return 25;
    else
        return 0;
}
function adesk_site_get()
{
    if (isset($GLOBALS['site']))
        return $GLOBALS['site'];
    $key = adesk_prefix_first("aweb_site");
    if (!isset($_SESSION[$key]) || adesk_session_need_update()) {
        $versionfinder = adesk_sql_query("SELECT * FROM `#backend` LIMIT 0, 1");
        if (!$versionfinder or mysql_num_rows($versionfinder) == 0)
            return false;
        $site               = array_map('adesk_sql_unescape', mysql_fetch_assoc($versionfinder));
        $site['is_trial']   = adesk_site_istrial($site['serial']);
        $site['is_free']    = adesk_site_isfree($site['serial']);
        $site['live_limit'] = adesk_site_triolive_limit($site['serial']);
        if (isset($GLOBALS['__is_hosted'])) {
            if (isset($GLOBALS["admin"]))
                $admin = $GLOBALS["admin"];
            else
                $admin = adesk_admin_get();
            if ($admin['parentid'] == 0)
                $site['lang'] = $admin['lang'];
            else
                $site['lang'] = adesk_sql_select_one("SELECT `lang` FROM `#admin` WHERE `id` = '$admin[parentid]'");
        }
        adesk_site_check_https($site);
        $_SESSION[$key] = $site;
    } else {
        $site = $_SESSION[$key];
    }
    if (adesk_ihook_exists("adesk_site_get_post"))
        $site = adesk_ihook('adesk_site_get_post', $site);
    if (isset($site['serial']))
        unset($site['serial']);
    if (isset($site['ac']))
        unset($site['ac']);
    if (isset($site['av']))
        unset($site['av']);
    if (isset($site['avo']))
        unset($site['avo']);
    return $site;
}
function adesk_site_check_https(&$site)
{
    require_once awebdesk_functions("http.php");
}
function adesk_site_var($key)
{
    $site = adesk_site_get();
    if ($key == "p_link" && !isset($site['p_link']) && isset($site['sdepm'])) {
        return $site["murl"];
    }
    return $site[$key];
}
function adesk_site_plink($url = "")
{
    if ($url == "")
        return adesk_site_var('p_link');
    else
        return adesk_site_var('p_link') . '/' . $url;
}
function adesk_site_alink($url = "")
{
    return adesk_site_plink() . (adesk_site_isvisualedit() ? '' : "/manage") . "/" . $url;
}
function adesk_site_unsafe()
{
    $key           = adesk_prefix_first("aweb_site");
    $versionfinder = adesk_sql_query("SELECT * FROM `#backend` LIMIT 0, 1");
    if (!$versionfinder or mysql_num_rows($versionfinder) == 0)
        return false;
    $site               = array_map('adesk_sql_unescape', mysql_fetch_assoc($versionfinder));
    $site['is_trial']   = adesk_site_istrial($site['serial']);
    $site['is_free']    = adesk_site_isfree($site['serial']);
    $site['live_limit'] = adesk_site_triolive_limit($site['serial']);
    if (isset($GLOBALS['__is_hosted'])) {
        $admin = adesk_admin_get();
        if ($admin['parentid'] == 0)
            $site['lang'] = $admin['lang'];
        else
            $site['lang'] = adesk_sql_select_one("SELECT `lang` FROM `#admin` WHERE `id` = '$admin[parentid]'");
    }
    adesk_site_check_https($site);
    if (adesk_ihook_exists('adesk_site_get_post'))
        $site = adesk_ihook('adesk_site_get_post', $site);
    return $site;
}
function adesk_site_rwlink($params, $base = null)
{
    $site = adesk_site_get();
    $rw   = (isset($site['general_url_rewrite']) and $site['general_url_rewrite'] == 1);
    $url  = ($base ? $base : $site['p_link']);
    if (isset($GLOBALS['seo_url_prefix']))
        $url .= $GLOBALS['seo_url_prefix'];
    $url .= '/';
    if (!isset($params['action']))
        return $url . 'index.php';
    if (!$rw)
        $url .= 'index.php?action=';
    $url .= $params['action'];
    unset($params['action']);
    $idaddon = '';
    if (isset($params['id'])) {
        $idaddon .= ($rw ? '/' : '&id=') . $params['id'];
        unset($params['id']);
    }
    if (count($params) > 0) {
        if ($rw) {
            $url .= '/' . implode('/', array_map('urlencode', $params));
        } else {
            foreach ($params as $k => $v)
                $params[$k] = $k . '=' . urlencode($v);
            $url .= '&' . implode('&', $params);
        }
    }
    return $url . $idaddon;
}
function adesk_site_isisalient()
{
    $site = adesk_site_get();
    return isset($site['blocked_ips']);
}
function adesk_site_isAEM()
{
    $site = adesk_site_get();
    return isset($site['sdepm']);
}
function adesk_site_isAEM5()
{
    $site = adesk_site_get();
    return isset($site['acec']);
}
function adesk_site_isvisualedit()
{
    $site = adesk_site_get();
    return isset($site['companylogin']);
}
function adesk_site_issupporttrio()
{
    $site = adesk_site_get();
    return isset($site['grouptickets']);
}
function adesk_site_issupporttrio3()
{
    $site = adesk_site_get();
    return isset($site['spam_threshold']);
}
function adesk_site_isknowledgebuilder()
{
    $site = adesk_site_get();
    return isset($site['articles_attachments_location']);
}
function adesk_site_ismodern()
{
    $site = adesk_site_get();
    return isset($site['general_maint']);
}
function adesk_site_isstandalone()
{
    if (adesk_site_issupporttrio3()) {
    } elseif (adesk_site_isknowledgebuilder()) {
        if (!defined('adesk_KB_STANDALONE'))
            return true;
        return (bool) adesk_KB_STANDALONE;
    } elseif (adesk_site_isAEM()) {
    }
    return true;
}
function adesk_site_hosted_rsid()
{
    if (!isset($GLOBALS['_hosted_account']))
        return false;
    if (!isset($GLOBALS['domain']))
        return false;
    if (!isset($_SESSION[$GLOBALS['domain']]))
        return false;
    $account = $_SESSION[$GLOBALS["domain"]];
    if (!isset($account['rsid']))
        return false;
    return $account['rsid'];
}
?>