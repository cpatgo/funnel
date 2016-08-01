<?php

function adesk_sync_database_types() {
	$rval = array(
		'mysql' => _a('MySQL'),
		'mssql' => _a('MSSQL'),
		'pg'    => _a('PostgreSQL'),
	);

	if (!function_exists("pg_connect"))
		unset($rval["pg"]);

	if (!function_exists("mssql_connect"))
		unset($rval["mssql"]);

	return $rval;
}

/*
armscii8	ARMSCII-8 Armenian	armscii8_general_ci	1
ascii	US ASCII	ascii_general_ci	1
big5	Big5 Traditional Chinese	big5_chinese_ci	2
binary	Binary pseudo charset	binary	1
cp1250	Windows Central European	cp1250_general_ci	1
cp1251	Windows Cyrillic	cp1251_general_ci	1
cp1256	Windows Arabic	cp1256_general_ci	1
cp1257	Windows Baltic	cp1257_general_ci	1
cp850	DOS West European	cp850_general_ci	1
cp852	DOS Central European	cp852_general_ci	1
cp866	DOS Russian	cp866_general_ci	1
cp932	SJIS for Windows Japanese	cp932_japanese_ci	2
dec8	DEC West European	dec8_swedish_ci	1
eucjpms	UJIS for Windows Japanese	eucjpms_japanese_ci	3
euckr	EUC-KR Korean	euckr_korean_ci	2
gb2312	GB2312 Simplified Chinese	gb2312_chinese_ci	2
gbk	GBK Simplified Chinese	gbk_chinese_ci	2
geostd8	GEOSTD8 Georgian	geostd8_general_ci	1
greek	ISO 8859-7 Greek	greek_general_ci	1
hebrew	ISO 8859-8 Hebrew	hebrew_general_ci	1
hp8	HP West European	hp8_english_ci	1
keybcs2	DOS Kamenicky Czech-Slovak	keybcs2_general_ci	1
koi8r	KOI8-R Relcom Russian	koi8r_general_ci	1
koi8u	KOI8-U Ukrainian	koi8u_general_ci	1
latin1	cp1252 West European	latin1_swedish_ci	1
latin2	ISO 8859-2 Central European	latin2_general_ci	1
latin5	ISO 8859-9 Turkish	latin5_turkish_ci	1
latin7	ISO 8859-13 Baltic	latin7_general_ci	1
macce	Mac Central European	macce_general_ci	1
macroman	Mac West European	macroman_general_ci	1
sjis	Shift-JIS Japanese	sjis_japanese_ci	2
swe7	7bit Swedish	swe7_swedish_ci	1
tis620	TIS620 Thai	tis620_thai_ci	1
ucs2	UCS-2 Unicode	ucs2_general_ci	2
ujis	EUC-JP Japanese	ujis_japanese_ci	3
utf8	UTF-8 Unicode	utf8_general_ci	3
 */

function adesk_sync_mysql_charset_mapper($charset) {
	switch (strtolower($charset)) {
		#case '': return 'armscii8';
		#case '': return 'ascii';
		#case '': return 'big5';
		#case '': return 'binary';
		case 'windows-1250': return 'cp1250';
		case 'windows-1251': return 'cp1251';
		case 'windows-1256': return 'cp1256';
		case 'windows-1257': return 'cp1257';
		#case '': return 'cp850';
		#case '': return 'cp852	DOS';
		#case '': return 'cp866';
		#case 'shift-jis': return 'cp932';
		#case '': return 'dec8';
		#case '': return 'eucjpms';
		#case '': return 'euckr';
		#case '': return 'gb2312';
		#case '': return 'gbk';
		#case '': return 'geostd8';
		case 'iso-8859-7': return 'greek';
		case 'iso-8859-8': return 'hebrew';
		#case '': return 'hp8';
		#case '': return 'keybcs2';
		case 'koi8-r': return 'koi8r';
		case 'koi8-u': return 'koi8u';
		case 'iso-8859-1': return 'latin1';
		case 'iso-8859-2': return 'latin2';
		case 'iso-8859-9': return 'latin5';
		case 'iso-8859-13': return 'latin7';
		#case '': return 'macce	Mac';
		#case '': return 'macroman';
		case 'shift-jis': return 'sjis';
		#case '': return 'swe7';
		#case '': return 'tis620';
		#case '': return 'ucs2';
		#case '': return 'ujis';
		case 'utf-8': return "utf8";
		default:
			return 'latin1';
	}

	# We shouldn't get here...
	return 'latin1';
}

/*
	database function interfaces
*/

function adesk_sync_connect($sync) {
	$pass = base64_decode($sync['db_pass']);
	switch ( $sync['db_type'] ) {
		default:
		case 'mysql':
			$db = @mysql_connect($sync['db_host'], $sync['db_user'], $pass, true);
			if (isset($sync["sourcecharset"]) && $sync["sourcecharset"] != "") {
				$names = adesk_sync_mysql_charset_mapper($sync["sourcecharset"]);
				mysql_query("SET NAMES '$names'", $db);
			}
			return $db;
		case 'mssql':
			return @mssql_connect($sync['db_host'], $sync['db_user'], $pass, true);
		case 'pg':
			$host = explode(':', $sync['db_host']);
			$port = ( isset($host[1]) ? ' port=' . (int)$host[1] : '');
			return @pg_connect("host=$host[0]$port dbname=$sync[db_name] user=$sync[db_user] password=$pass");
	}
}

function adesk_sync_select_db($sync) {
	switch ( $sync['db_type'] ) {
		case 'mysql':
			return @mysql_select_db($sync['db_name'], $GLOBALS['sync_link']);
		case 'mssql':
			return @mssql_select_db($sync['db_name'], $GLOBALS['sync_link']);
		case 'pg':
			return true; // pg did it @connect
	}
}

function adesk_sync_error($sync) {
	switch ( $sync['db_type'] ) {
		case 'mysql':
			return sprintf(_a('Error %d: %s'), mysql_errno($GLOBALS['sync_link']), mysql_error($GLOBALS['sync_link']));
		case 'mssql':
			return sprintf(_a('Error: %s'), mssql_get_last_message());
		case 'pg':
			return sprintf(_a('Error: %s'), pg_last_error($GLOBALS['sync_link']));
	}
}

function adesk_sync_query($query, $sync) {
	if ( !isset( $sync['db_type'] ) ) dbg(debug_backtrace());
	switch ( $sync['db_type'] ) {
		case 'mysql':
			return mysql_query($query, $GLOBALS['sync_link']);
		case 'mssql':
			return mssql_query($query, $GLOBALS['sync_link']);
		case 'pg':
			//dbg($query,1);
			return pg_query($GLOBALS['sync_link'], $query);
	}
}

function adesk_sync_num_rows($result, $sync) {
	switch ( $sync['db_type'] ) {
		case 'mysql':
			return mysql_num_rows($result);
		case 'mssql':
			return mssql_num_rows($result);
		case 'pg':
			return pg_num_rows($result);
	}
}

function adesk_sync_fetch_row($result, $sync) {
	switch ( $sync['db_type'] ) {
		case 'mysql':
			return mysql_fetch_row($result);
		case 'mssql':
			return mssql_fetch_row($result);
		case 'pg':
			return pg_fetch_row($result);
	}
}

function adesk_sync_fetch_assoc($result, $sync) {
	switch ( $sync['db_type'] ) {
		case 'mysql':
			return mysql_fetch_assoc($result);
		case 'mssql':
			return mssql_fetch_assoc($result);
		case 'pg':
			return pg_fetch_assoc($result);
	}
}

function adesk_sync_wrap($field, $sync) {
	switch ( $sync['db_type'] ) {
		case 'mysql':
			return "`$field`";
		case 'mssql':
			return "[$field]";
		case 'pg':
			return "$field";
	}
}


?>
