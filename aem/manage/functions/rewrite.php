<?php

function rewrite_url() {
	// get config
	$site = adesk_site_get();
	// check for input
	if ( !$site['general_url_rewrite'] ) return;
	if ( !isset($_SERVER['REQUEST_URI']) ) return;
	if ( isset($_GET['action']) ) return;
	// if ErrorDocument is used (404 redirection), gotta set this then
	header("HTTP/1.0 200 OK");
	// use absolute URL?
	$link = $site['p_link'];
	// add trailing slash if it doesn't exists
	if ( substr($link, -1) != '/' ) $link .= '/';
	// break full URL
	$url = parse_url($link);
	// get URI
	$uri = ( ( isset($url['path']) and $url['path'] != '' ) ? $url['path'] : '/' );
	// get internal URI
	if ( $_SERVER['REQUEST_URI'] == $uri ) return;

	# This is a hack to allow ISAPI_Rewrite to function the way we expect mod_rewrite to.
	# Mod_rewrite will retain the REQUEST_URI variable as the user submitted it, while
	# ISAPI_Rewrite already has changed it, preserving a copy in the following variable.
	if (isset($_SERVER["HTTP_X_REWRITE_URL"]))
		$_SERVER["REQUEST_URI"] = $_SERVER["HTTP_X_REWRITE_URL"];

	$base    = substr($_SERVER['REQUEST_URI'], strlen($uri));
	if ( ( $loc = strpos($base, '?') ) !== false ) $base = substr($base, 0, $loc);
	$folders = explode('/', $base);
	$rsskey  = array_search("rss", $folders);

	if ($rsskey !== false) {
		$_GET["rss"] = 1;
		array_splice($folders, $rsskey, 1);
	}

	if ( !count($folders) ) return;
	if ( $folders[0] == 'index.php' ) return;

	// these actions overwrite the action param
	if ( $folders[0] == 'user' and isset($folders[1]) ) {
		array_shift($folders); // dummy, for action
		$id = array_shift($folders); // gets id
		$GLOBALS['seo_url_prefix'] = '/user/' . $id;
		if ( (string)(int)$id == $id ) {
			// get his lists
			$id = (int)$id;
		} else {
			require_once(awebdesk_functions('user.php'));
			$user = adesk_user_select_row_username($id);
			$id = ( $user ? $user['id'] : 0 );
		}
		if ( $id > 0 ) {
			$_GET['ul'] = $id;
		}
	} elseif ($folders[0] == "social" and isset($folders[1])) {
		$_GET["action"] = array_shift($folders);
		$_GET["c"] = array_shift($folders);

		if (isset($folders[0]))
			$_GET["facebook"] = array_shift($folders);
	} elseif ( $folders[0] == 'group' and isset($folders[1]) ) {
		array_shift($folders); // dummy, for action
		$id = array_shift($folders); // gets id
		$GLOBALS['seo_url_prefix'] = '/group/' . $id;
		if ( $id = (int)$id ) {
			if ( $id > 2 ) {
				$_GET['gl'] = $id;
			}
		}
	} elseif ( $folders[0] == 'list' and isset($folders[1]) ) {
		array_shift($folders); // dummy, for action
		$id = array_shift($folders); // gets id
		$GLOBALS['seo_url_prefix'] = '/list/' . $id;
		if ( (string)(int)$id == $id ) {
			$_GET['nl'] = (int)$id;
		} else {
			$_GET['nl'] = (int)list_get_by_stringid($id);
		}
	}

	if ( !count($folders) ) return;

	// assign action
	$_GET['action'] = $action = $folders[0];

	// regular actions
	if ($action == 'archive' || $action == 'subscribe' || $action == 'unsubscribe' || $action == 'account' || $action == 'account_update') {
		rewrite_archive($folders);
	} elseif ( $action == 'form' ) {
		// support for IDs
		if ( count($folders) > 1 ) {
			$_GET['id'] = (int)array_pop($folders);
		}
		if ( count($folders) > 1 ) {
			$_GET['type'] = array_pop($folders);
		}
	} else {
		// support for IDs
		if ( count($folders) == 2 ) {
			$_GET['nl'] = $folders[1];
		}
	}
	//$_GET['action'] = $action;

	//dbg($_GET);
	return;
}

function rewrite_archive($folders) {

	$last = count($folders) - 1;

	if ($folders[0] == "subscribe" || $folders[0] == "unsubscribe") {
		if ( count($folders) > 3 ) {
			$_GET['mode'] = $folders[1];
			$_GET['lists'] = $folders[2];
			$_GET['codes'] = $folders[3];
		}
		return;
	}

	if ($folders[0] == "account" || $folders[0] == "account_update") {
		if ( count($folders) > 3 ) {
			$_GET['mode'] = $folders[1];
			$_GET['p'] = $folders[2];

			$folders3_pieces = explode("&", $folders[3]);

			$_GET['codes'] = $folders3_pieces[0];
		}

		return;
	}

	if ($folders[0] == "archive") {

		// Message ID
		if ( is_numeric($folders[$last]) ) {
			$_GET['action'] = "message";
			$campaignid = $folders[$last];
			$_GET['c'] = $campaignid;

			return;
		}
	}

	$stringid = $folders[$last];
	if ( $stringid == '' )
		return;

	$id = list_get_by_stringid($stringid);

	if ( $id > 0 ) {
		$_GET['nl'] = $id;
		$_GET['list_stringid'] = $stringid;
	}
}

?>
