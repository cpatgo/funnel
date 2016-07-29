<?php

#require_once dirname(__FILE__) . '/product.php';

function adesk_cache_dir($file = '') {
	if (isset($GLOBALS["customCachePath"])) {
		$base = $GLOBALS["customCachePath"] . "/cache";
	} else {
		$base = adesk_base("cache");
	}

	if ($file)
		return $base . "/" . $file;
	else
		return $base;
}

function adesk_cache_load($fname, $filter = null) {
	if ( $filter and !function_exists($filter) ) $filter = null;
	$data  = null;
	$cfile = adesk_cache_dir($fname);
	if ( file_exists($cfile) ) $data = adesk_file_get($cfile);
	if ( !$data ) return null;
	if ( $filter ) $data = $filter($data);
	return $data;
}

function adesk_cache_save($fname, $data, $filter = null) {
	if ( $filter and !function_exists($filter) ) $filter = null;
	if ( $filter ) $data = $filter($data);
	$cfile = adesk_cache_dir($fname);
	return adesk_file_put($cfile, (string)$data);
}

?>
