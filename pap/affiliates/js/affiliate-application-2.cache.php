<?php
// !!! Do not modify, values will be added during build process !!!
$cacheFilePreffix = '';
$basePath = '../../';
$moduleExtension = 'html';
$isInInstallMode = false;
$useTimeStamp = false;

$offset = 31536000;
$now = @getdate(time());
header('Cache-Control: max-age=' . $offset . ', public');
header('Expires: ' . gmdate('D, d M Y H:i:s', @mktime(0, 0, 0, 1, 1, $now['year']+1)) . ' GMT');
header('Connection: Keep-Alive');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s', @mktime(0, 0, 0, 1, 1, 2008)) . ' GMT');
if($moduleExtension == 'js') {
    header('Content-Type: application/javascript; charset=utf-8');
} else {
    header('Content-Type: text/html; charset=utf-8');
}

if(@$_SERVER['PROJECT_ACCOUNTS_PATH'] != '') {
    $path = rtrim($_SERVER['PROJECT_ACCOUNTS_PATH'], '/\\') . '/accounts/lang_cache/';
    if (!@file_exists($path)) {
        @mkdir($path, 0775, true);
    }
} else {
    $path = $basePath . 'accounts/default1/cache/lang/';
}

$fileName = basename(@$_SERVER['SCRIPT_FILENAME'], 'php');
if ($fileName == '') {
    $fileName = basename(@$_SERVER['PHP_SELF'], 'php');
    if ($fileName == '') {
        header((isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0') . ' 500 Internal Server Error');
        die('Can not resolve filename in module js script');
    }
}
$cacheFileSuffix = '-'.strip_tags(@$_GET['ver']).($useTimeStamp ? '-' . $useTimeStamp : '');

$localizedFileName = $path . $cacheFilePreffix . $fileName . strip_tags(@$_GET['l']) . $cacheFileSuffix . '.' . $moduleExtension;
if(@file_exists($localizedFileName) === false) {
    include($basePath . 'scripts/bootstrap.php');
    if (!$isInInstallMode) {
        Gpf_Session::create(new Gpf_System_Module());
    }
    Gpf_Paths::getInstance()->setInstallMode($isInInstallMode);
    try {
        $provider = new Gpf_Lang_ClientModuleProvider(strip_tags(@$_GET['l']), $fileName, $moduleExtension, $cacheFileSuffix, $useTimeStamp, $cacheFilePreffix);
        $content = $provider->getContent();
        header('Content-Length: ' . strlen($content));
        echo $content;
    } catch (Exception $e) {
        header((isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0') . ' 400 Bad Request');
        if (@$_GET['PDebug'] == 'Y') {
            echo $e->getMessage();
        }
    }
    exit(0);
}
header('Content-Length: ' . filesize($localizedFileName));
if (@readfile($localizedFileName) == null) {
  if (strstr(ini_get("disable_functions"), 'fpassthru')) {
    echo file_get_contents($localizedFileName);
  } else {
    $fp = fopen($localizedFileName, 'r');
    fpassthru($fp);
    fclose($fp);
  }
}
