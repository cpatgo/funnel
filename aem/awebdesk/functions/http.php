<?php
// http.php

// Some functions for working with HTTP

function adesk_http_param($id) {
    if (isset($_GET[$id]))
        return $_GET[$id];
    elseif (isset($_POST[$id]))
        return $_POST[$id];
    return null;
}

function adesk_http_param_forcearray($id) {
	# If $id is a form param, but it doesn't seem to be an array, force the return value to be
	# an array.  Useful for cases of $id where one or more of it may be submitted, and we want it
	# to be an array in all cases.
	$rval = adesk_http_param($id);
	if ($rval && !is_array($rval))
		$rval = array($rval);

	if (!$rval)
		return array();
	return $rval;
}

function adesk_http_param_exists($id) {
    if ( isset($_POST[$id]) ) return true;
    if ( isset($_GET[$id]) ) return true;
    return false;
}

function adesk_http_redirect($loc, $stop = 1) {
    header("Location: $loc");
    if ( $stop ) exit; // link trackers are saving after redirection
}

function adesk_http_redirect_301($loc, $stop = 1) {
    header("HTTP/1.1 301 Moved Permanently");
	header("Location: $loc");
    if ( $stop ) exit; // link trackers are saving after redirection
}

function adesk_http_geturl() {
	$URI = ( isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $_SERVER['PHP_SELF'] );

    if (isset($_SERVER["SERVER_NAME"])) {
		$sname = $_SERVER["SERVER_NAME"];

		if (isset($_SERVER["SERVER_PORT"])) {
			$sport = $_SERVER["SERVER_PORT"];
			if ($sport != 80 && $sport != 443)	# Neither HTTP/HTTPS
				$sname .= ":" . $sport;
		}

        if (adesk_http_is_ssl())
            return "https://" . $sname . $URI;
        else
            return "http://" . $sname . $URI;
    }

    return "";
}

function adesk_http_referer() {
    if (isset($_SERVER["HTTP_REFERER"]))
        return $_SERVER["HTTP_REFERER"];
    else
        return "";
}

# Generate the header for an attachment file properly based on the user agent
# string.

function adesk_http_header_attach($fname, $fsize = 0, $mimetype = 'application/octet-stream') {
	header("Content-type: $mimetype");
	if ( $fsize > 0 ) header('Content-length: ' . $fsize);
	if ( !is_null($fname) ) {
		if (isset($_SERVER['HTTP_USER_AGENT']) && preg_match("/MSIE 5.5/", $_SERVER['HTTP_USER_AGENT']))
	        header("Content-Disposition: filename=\"$fname\"");
	    else
	        header("Content-Disposition: attachment; filename=\"$fname\"");
	}
}

function adesk_http_get($url, $charset = '') {
    require_once awebdesk_pear('HTTP/Request.php');

    @ini_set('magic_quotes_runtime', 0);
    unset($req);
    $req = new HTTP_Request($url);
    $req->setMethod(HTTP_REQUEST_METHOD_GET);
	$req->removeHeader("Accept-Encoding");
    $req->sendRequest();

    $body    = $req->getResponseBody();

	if ($charset != '') {
		$ctype   = $req->getResponseHeader("Content-Type");
		$mat     = array();

		if (preg_match('/charset=(\S+)/', $ctype, $mat)) {
			$source = strtoupper($mat[1]);
		} else {
			if (preg_match('/;\s*charset=(\S+)["\']/i', $body, $mat)) {
				# It's not here.  However, some broken web servers will refuse to send the charset
				# parameter even though it's specified in a meta tag--we'll check there too...

				$source = strtoupper($mat[1]);
			} else {
				# If the charset is not given, HTTP/1.1 does dictate that we assume ISO-8859-1.
				$source = "ISO-8859-1";
			}
		}

		$charset = strtoupper($charset);

		if ($source != $charset) {
			return adesk_utf_conv($source, $charset, $body);
		}
	}

	return $body;
}

function adesk_http_post($url, $ary) {
	require_once awebdesk_pear("HTTP/Request.php");

	ini_set("magic_quotes_runtime", 0);
	$req = new HTTP_Request($url);
	$req->setMethod(HTTP_REQUEST_METHOD_POST);

	$impl = array();
	foreach ($ary as $k => $v) {
		# This assumes $ary is flat--it has no sub-arrays.
		$impl[] = urlencode($k) . "=" . urlencode($v);
	}

	$req->setBody(implode("&", $impl));
	$req->removeHeader("Accept-Encoding");
	$req->sendRequest();

	return $req->getResponseBody();
}

function adesk_http_is_ssl() {
    return ( isset($_SERVER['HTTPS']) and strtolower($_SERVER['HTTPS']) == 'on' );
}


function adesk_http_spawn($url) {

	$debug = (bool)adesk_http_param('debugspawn');
	$arr = parse_url($url);
	if ( !isset($arr['host']) ) $arr['host'] = ( isset( $_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : ( isset( $_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : 'localhost' ) );
	if ( !isset($arr['port']) ) $arr['port'] = ( isset( $_SERVER['SERVER_PORT']) ? $_SERVER['SERVER_PORT'] : ( ( isset($arr['scheme']) and strtolower($arr['scheme']) == 'https' ) ? '443' : '80' ) );
	if ( !isset($arr['path']) ) return false;
	$uri = $arr['path'];
	if ( isset($arr['query']) ) $uri .= '?' . $arr['query'];
	$serverName = $arr['host'];
	$serverPort = $arr['port'];
	$serverHost = $arr['host'];
	if ( $serverPort == 443 or ( isset($arr['scheme']) and strtolower($arr['scheme']) == 'https' ) ) {
		if ( !adesk_str_instr('ssl://', strtolower($serverName)) ) {
			$serverName = 'ssl://' . $serverName;
		}
	}
	$cbSock = @fsockopen($serverName, $serverPort, $errno, $errstr, 5);
	if ( !$cbSock ) {
		if ( $debug ) adesk_flush("[+] Could not open the connection to $serverName:$serverPort !");
		return false;
	}
	$str = '';
	if ( isset($GLOBALS['site']['stream_set_blocking']) and $GLOBALS['site']['stream_set_blocking'] ) {
		@stream_set_blocking($cbSock, 0);
	}
	@stream_set_timeout($cbSock, 5);
	$command =
		"GET {$uri} HTTP/1.0\r\n" .
		"Host: {$serverHost}\r\n" .
		"Connection: Close\r\n\r\n"
	;
	$x = fwrite($cbSock, $command);
	if ( $x !== strlen($command) ) {
		if ( $debug ) adesk_flush("[+] Could not write the request for $url:\n$command");
		return false;
	}
	while ( !feof($cbSock) ) {
		$ret = fgets($cbSock, ( $debug ? 1024 : 16 ));
		if ( $ret === false ) {
			if ( $debug ) adesk_flush("[+] Could not get the response from $url:\n$command");
			$ret = 'No data found in live stream';
			//return false;
		}
		$info = stream_get_meta_data($cbSock);
		$str .= $ret;
		if ( !$ret ) {
			// if timed out, assume "all good"
			if ( !$info['timed_out'] and !$info['eof'] ) {
				if ( $debug ) adesk_flush("[+] Returned nothing, but not timed out!\n\n$url returned:\n-\n$str\n-");
				return false; // page didn't time out, dunno what happened
			}
		} else {
			if ( preg_match('/HTTP\/\d\.\d 404/', $ret) ) {
				if ( $debug ) adesk_flush("[+] Page not found?!\n\n$url returned:\n-\n$str\n-");
				return false; // page not found!
			}
		}
		break;
	}
	//dbg($info,1);dbg($str);
	fclose($cbSock);
	if ( $debug ) adesk_flush("\n\n$url returned:\n-\n$str\n-");//return $str;
	return $str;
}

function adesk_http_resolves($addr) {
	# Doesn't really belong here, but oh well.  Return true if $addr resolves.
	if(is_numeric(str_replace('.','',$addr))){
		return true;
	}
	else{
		return gethostbyname($addr) != $addr;
	}
}

function adesk_http_reachable($addr, $port, &$errno, &$errstr, $timeout = 2.0) {
	# Return true if the host ($addr) is reachable via $port.  There is a two second timeout by
	# default (it's given as a floating point number).
	#
	# We may one day do something with $errno/$errstr.
	$rval   = @fsockopen($addr, $port, $errno, $errstr, $timeout);

	if ($rval === false)
		return $rval;
	else {
		fclose($rval);
		return true;
	}
}

function adesk_http_viable($addr, $port, $url) {
	# Return an array explaining if it is viable to contact $addr via $port from this machine.
	$rval = array(
		"result" => true,
		"shortreason" => "",
		"explanation" => "",
	);
	if (!adesk_http_resolves($addr)) {
		$rval["result"]      = false;
		$rval["shortreason"] = "dns";
		$rval["explanation"] = sprintf(_a("The host '%s' could not be resolved"), $addr);
		return $rval;
	}

	$errno  = -1;
	$errstr = "";

	if (!adesk_http_reachable($addr, $port, $errno, $errstr)) {
		$rval["result"]      = false;
		$rval["shortreason"] = "connect";
		$rval["explanation"] = sprintf(_a("We could not connect to host '%s' on port %d (%s (%d))"), $addr, $port, $errstr, $errno);
		return $rval;
	}

	$data = adesk_http_get($url);

	if ($data == "") {
		$rval["result"]      = false;
		$rval["shortreason"] = "data";
		$rval["explanation"] = sprintf(_a("Data could not be retrieved through URL '%s'"), $url);
		return $rval;
	}

	return $rval;
}

function adesk_http_testdata($url, $checkstr) {
	$rval = array(
		"result" => true,
		"shortreason" => "",
		"explanation" => "",
	);

	$data = adesk_http_get($url);

	if ($data == "" || strpos($data, $checkstr) === false) {
		$rval["result"]      = false;
		$rval["shortreason"] = "data";
		$rval["explanation"] = sprintf(_a("URL rewrites could not be verified"));
	}

	return $rval;
}

function adesk_http_unparse_url($parsed) {
	if ( !is_array($parsed) ) {
		return false;
	}

	$uri  = isset($parsed['scheme']) ? $parsed['scheme'] . ':' . ( strtolower($parsed['scheme']) == 'mailto' ? '' : '//' ) : '';
	$uri .= isset($parsed['user'])   ? $parsed['user'] . ( isset($parsed['pass']) ? ':' . $parsed['pass'] : '' ) . '@' : '';
	$uri .= isset($parsed['host'])   ? $parsed['host'] : '';
	$uri .= isset($parsed['port'])   ? ':' . $parsed['port'] : '';

	if (isset($parsed['path'])) {
		$uri .= substr($parsed['path'], 0, 1) == '/' ? $parsed['path'] : ( ( !empty($uri) ? '/' : '' ) . $parsed['path'] );
	}

	$uri .= isset($parsed['query'])    ? '?' . $parsed['query'] : '';
	$uri .= isset($parsed['fragment']) ? '#' . $parsed['fragment'] : '';

	return $uri;
}

function adesk_http_resolve_url($base, $url) {
	if ( !strlen($base) ) return $url;
	// Step 2
	if ( !strlen($url) ) return $base;
	// Step 3
	if ( preg_match('!^[a-z]+:!i', $url) ) return $url;
	$base = parse_url($base);
	$firstchar = substr($url, 0, 1);
	if ( $firstchar == "#" ) {
		// Step 2 (fragment)
		$base['fragment'] = substr($url, 1);
		return adesk_http_unparse_url($base);
	}
	unset($base['fragment']);
	if ( $firstchar == "?" ) {
		// Step 3 (query)
		$base['query'] = substr($url, 1);
		return adesk_http_unparse_url($base);
	}
	unset($base['query']);
	if ( substr($url, 0, 2) == "//" ) {
		// Step 4
		return adesk_http_unparse_url(
			array(
				'scheme' => $base['scheme'],
				'path'   => substr($url, 2),
			)
		);
	} elseif ( $firstchar == "/" ) {
		// Step 5
		$base['path'] = $url;
	} else {
		// Step 6
		if(!isset($base['path'])) $base['path'] = "/";
		$path = explode('/', $base['path']);
		$url_path = explode('/', $url);
		// Step 6a: drop file from base
		array_pop($path);
		// Step 6b, 6c, 6e: append url while removing "." and ".." from
		// the directory portion
		$end = array_pop($url_path);
		foreach ( $url_path as $segment ) {
			if ( $segment == '.' ) {
				// skip
			} elseif ( $segment == '..' && $path && $path[count($path)-1] != '..' ) {
				array_pop($path);
			} else {
				$path[] = $segment;
			}
		}
		// Step 6d, 6f: remove "." and ".." from file portion
		if ( $end == '.' ) {
			$path[] = '';
		} elseif ( $end == '..' && $path && $path[count($path)-1] != '..' ) {
			$path[count($path)-1] = '';
		} else {
			$path[] = $end;
		}
		// Step 6h
		$base['path'] = implode('/', $path);
	}
	// Step 7
	return adesk_http_unparse_url($base);
}

function adesk_http_query_prefix($url, $params) {
	$arr = parse_url($url);
	if ( !isset($arr['query']) ) {
		$arr['query'] = $params;
	} else {
		$arr['query'] = $params . '&' . $arr['query'];
	}
	return adesk_http_unparse_url($arr);
}

?>