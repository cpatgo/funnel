<?php

require_once awebdesk_classes("select.php");

function personalization_select_query(&$so) {
	if ( !adesk_admin_ismain() ) {
		$admin = adesk_admin_get();
		if ( $admin['id'] != 1 ) {
			if ( !isset($so->permsAdded) ) {
				$so->permsAdded = 1;
				$so->push("AND l.listid IN ('" . implode("', '", $admin['lists']) . "')");
			}
		}
	}
	return $so->query("
		SELECT
			t.*,
			COUNT(l.id) as lists
		FROM
			#personalization t
		LEFT JOIN
			#personalization_list l
		ON
			t.id = l.persid
		WHERE
			[...]
		GROUP BY
			t.id
	");
}

function personalization_select_row($id) {
	$id = intval($id);
	$so = new adesk_Select;
	$so->push("AND t.id = '$id'");

	$r = adesk_sql_select_row(personalization_select_query($so));
	if ( $r ) {
		$cond = '';
		if ( !adesk_admin_ismain() ) {
			$admin = adesk_admin_get();
			if ( $admin['id'] != 1 ) {
				//$admin['lists'][0] = 0;
				$cond = "AND listid IN ('" . implode("', '", $admin['lists']) . "')";
			}
		}
		$r['lists'] = implode('-', adesk_sql_select_list("SELECT listid FROM #personalization_list WHERE `persid` = '$id' $cond"));
	}
	return $r;
}

function personalization_select_array($so = null, $ids = null) {
	if ($so === null || !is_object($so))
		$so = new adesk_Select;

	if ($ids !== null) {
		if ( !is_array($ids) ) $ids = explode(',', $ids);
		$tmp = array_diff(array_map("intval", $ids), array(0));
		$ids = implode("','", $tmp);
		$so->push("AND t.id IN ('$ids')");
	}
	return adesk_sql_select_array(personalization_select_query($so));
}

function personalization_select_array_paginator($id, $sort, $offset, $limit, $filter) {
	$admin = adesk_admin_get();
	$so = new adesk_Select;

	$filter = intval($filter);
	if ($filter > 0) {
		$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filter' AND userid = '$admin[id]' AND sectionid = 'personalization'");
		$so->push($conds);
	}

	$so->count();
	$total = (int)adesk_sql_select_one(personalization_select_query($so));

	switch ($sort) {
		case "01":
			$so->orderby("tag"); break;
		case "01D":
			$so->orderby("tag DESC"); break;
		default:
		case "02":
			$so->orderby("name"); break;
		case "02D":
			$so->orderby("name DESC"); break;
		case "02":
			$so->orderby("format"); break;
		case "02D":
			$so->orderby("format DESC"); break;
		case "03":
			$so->orderby("lists"); break;
		case "03D":
			$so->orderby("lists DESC"); break;
		case "04":
			$so->orderby("format"); break;
		case "04D":
			$so->orderby("format DESC"); break;
	}

	if ( (int)$limit == 0 ) $limit = 999999999;
	$limit  = (int)$limit;
	$offset = (int)$offset;
	$so->limit("$offset, $limit");
	$rows = personalization_select_array($so);

	return array(
		"paginator"   => $id,
		"offset"      => $offset,
		"limit"       => $limit,
		"total"       => $total,
		"cnt"         => count($rows),
		"rows"        => $rows,
	);
}

function personalization_filter_post() {
	$whitelist = array(
		"tag",
		"name",
		"format",
		"content",
	);

	$ary = array(
		"userid" => $GLOBALS['admin']['id'],
		"sectionid" => "personalization",
		"conds" => "",
		"=tstamp" => "NOW()",
	);

	if (isset($_POST["qsearch"]) && !isset($_POST["content"])) {
		$_POST["content"] = $_POST["qsearch"];
	}

	if (isset($_POST["content"]) and $_POST["content"] != "") {
		$content = adesk_sql_escape($_POST["content"], true);
		$conds = array();

		if (!isset($_POST["section"]) || !is_array($_POST["section"]))
			$_POST["section"] = $whitelist;

		foreach ($_POST["section"] as $sect) {
			if (!in_array($sect, $whitelist))
				continue;
			$conds[] = "$sect LIKE '%$content%'";
		}

		$conds = implode(" OR ", $conds);
		$ary["conds"] = "AND ($conds) ";
	}

	if ( isset($_POST['listid']) ) {
		if ( defined('AWEBVIEW') ) {
			$_SESSION['nlp'] = $_POST['listid'];
		} else {
			$_SESSION['nla'] = $_POST['listid'];
		}
	}
	$nl = null;
	if ( isset($_SESSION['nlp']) and defined('AWEBVIEW') ) {
		$nl = $_SESSION['nlp'];
	} elseif ( isset($_SESSION['nla']) ) {
		$nl = $_SESSION['nla'];
	}
	if ( $nl ) {
		if ( is_array($nl) ) {
			if ( count($nl) > 0 ) {
				$ids = implode("', '", array_map('intval', $nl));
				$ary['conds'] .= "AND l.listid IN ('$ids') ";
			} else {
				if ( defined('AWEBVIEW') ) {
					unset($_SESSION['nlp']);
				} else {
					unset($_SESSION['nla']);
				}
			}
		} else {
			$listid = (int)$nl;
			if ( $listid > 0 ) {
				$ary['conds'] .= "AND l.listid = '$listid' ";
			} else {
				if ( defined('AWEBVIEW') ) {
					unset($_SESSION['nlp']);
				} else {
					unset($_SESSION['nla']);
				}
			}
		}
	}
	if ( $ary['conds'] == '' ) return array('filterid' => 0);

	$conds_esc = adesk_sql_escape($ary["conds"]);
	$filterid = adesk_sql_select_one("
		SELECT
			id
		FROM
			#section_filter
		WHERE
			userid = '$ary[userid]'
		AND
			sectionid = 'personalization'
		AND
			conds = '$conds_esc'
	");

	if (intval($filterid) > 0)
		return array("filterid" => $filterid);
	adesk_sql_insert("#section_filter", $ary);
	return array("filterid" => adesk_sql_insert_id());
}

function personalization_insert_post() {
	// find parents
	if ( isset($_POST['p']) and is_array($_POST['p']) and count($_POST['p']) > 0 ) {
		$lists = array_map('intval', $_POST['p']);
	} else {
		return adesk_ajax_api_result(false, _a("You did not select any lists."));
	}

	$admin = adesk_admin_get();
	$ary = personalization_post_prepare(0);
	$ary['id'] = 0;
	$ary['userid'] = (int)$admin['id'];

	if ( $ary['name'] == '' ) {
		return adesk_ajax_api_result(false, _a("Personalization Name can not be left empty. Please name this personalization."));
	}

	$sql = adesk_sql_insert("#personalization", $ary);
	if ( !$sql ) {
		return adesk_ajax_api_result(false, _a("Personalization could not be added."));
	}
	$id = adesk_sql_insert_id();

	// if tag was not provided, set it now
	if ( $ary['tag'] == '' ) adesk_sql_update_one('#personalization', 'tag', $ary['tag'], "`id` = '$id'");

	// list relations
	foreach ( $lists as $l ) {
		if ( $l > 0 ) adesk_sql_insert('#personalization_list', array('id' => 0, 'persid' => $id, 'listid' => $l));
	}
	return adesk_ajax_api_added(_a("Personalization"));
}

function personalization_update_post() {
	if ( isset($_POST['p']) and is_array($_POST['p']) and count($_POST['p']) > 0 ) {
		$lists = array_map('intval', $_POST['p']);
	} else {
		return adesk_ajax_api_result(false, _a("You did not select any lists."));
	}

	$id = intval($_POST["id"]);
	$ary = personalization_post_prepare($id);

	if ( $ary['name'] == '' ) {
		return adesk_ajax_api_result(false, _a("Personalization Name can not be left empty. Please name this personalization."));
	}

	$sql = adesk_sql_update("#personalization", $ary, "id = '$id'");
	if ( !$sql ) {
		return adesk_ajax_api_result(false, _a("Personalization could not be updated."));
	}

	// list relations
	$cond = implode("', '", $lists);
	$admincond = '';
	if ( !adesk_admin_ismain() ) {
		$admin = adesk_admin_get();
		$admincond = "AND listid IN ('" . implode("', '", $admin['lists']) . "')";
	}
	adesk_sql_delete('#personalization_list', "persid = '$id' AND listid NOT IN ('$cond') $admincond");
	foreach ( $lists as $l ) {
		if ( $l > 0 ) {
			if ( !adesk_sql_select_one('=COUNT(*)', '#personalization_list', "persid = '$id' AND listid = '$l'") )
				adesk_sql_insert('#personalization_list', array('id' => 0, 'persid' => $id, 'listid' => $l));
		}
	}

	return adesk_ajax_api_updated(_a("Personalization"));
}

function personalization_delete($id) {
	$id = intval($id);
	adesk_sql_query("DELETE FROM #personalization WHERE id = '$id'");
	personalization_delete_relations(array($id));
	return adesk_ajax_api_deleted(_a("Personalization"));
}

function personalization_delete_multi($ids, $filter = 0) {
	if ( $ids == '_all' ) $ids = null;
	$so = new adesk_Select();
	$so->slist = array('p.id');
	$so->remove = false;
	$filter = intval($filter);
	if ($filter > 0) {
		$admin = adesk_admin_get();
		$conds = adesk_sql_select_one("SELECT conds FROM #section_filter WHERE id = '$filter' AND userid = '$admin[id]' AND sectionid = 'personalization'");
		$so->push($conds);
	}
	$tmp = personalization_select_array($so, $ids);
	$idarr = array();
	foreach ( $tmp as $v ) {
		$idarr[] = $v['id'];
	}
	$ids = implode("','", $idarr);
	adesk_sql_query("DELETE FROM #personalization WHERE id IN ('$ids')");
	personalization_delete_relations($ids);
	return adesk_ajax_api_deleted(_a("Personalization"));
}

function personalization_delete_relations($ids) {
	$admincond = 1;
	if ( !adesk_admin_ismain() ) {
		$admin = adesk_admin_get();
		$admincond = "listid IN ('" . implode("', '", $admin['lists']) . "')";
	}
	if ($ids === null) {		# delete all
		adesk_sql_delete('#personalization_list', $admincond);
	} else {
		adesk_sql_delete('#personalization_list', "`persid` IN ('$ids') AND $admincond");
	}
}






function personalization_post_prepare($id) {
	// personalization
	$where = ( $id > 0 ? "AND `id` != '$id'" : '' );
	$ary = array();
	$ary['name'] = (string)adesk_http_param('name');
	$ary['tag'] = trim((string)adesk_http_param('tag'));
	if ( $ary['tag'] == '' ) $ary['tag'] = $ary['name'];
	$ary['tag'] = adesk_sql_find_next_index('#personalization', 'tag', adesk_str_urlsafe($ary['tag']), $where);
	$ary['format'] = (string)adesk_http_param('format');
	if ( $ary['format'] != 'html' ) $ary['format'] = 'text';
	$ary['content'] = (string)adesk_http_param( $ary['format'] == 'html' ? 'html' : 'text' );
	return $ary;
}

/*
function personalization_tag_check($tag, $id = 0) {
	if ( $tag == '' ) return true; // will set autoinc for it
	$tagEsc = adesk_sql_escape($tag);
	$cond = ( $id > 0 ? "AND `id` != '$id'" : '' );
	$found = adesk_sql_select_one('=COUNT(*)', '#personalization', "`tag` = '$tagEsc' $cond");
	return $found == 0;
}
*/

// do_basic_personalization
function personalization_basic($content, $subject = '') {
	global $site;
	$murl = $site['p_link'];
	// update profile link
	$content = str_replace('%PERS_UP%'   , $murl . '/forward2.php?nl=currentnl&c=cmpgnid&m=currentmesg&s=subscriberid', $content);
	$content = str_replace('%UPDATELINK%', $murl . '/forward2.php?nl=currentnl&c=cmpgnid&m=currentmesg&s=subscriberid', $content);
	// web copy link
	$content = str_replace('%PERS_WCOPY%', $murl . '/forward3.php?l=currentnl&c=cmpgnid&m=currentmesg&s=subscriberid', $content);
	$content = str_replace('%WEBCOPY%'   , $murl . '/forward3.php?l=currentnl&c=cmpgnid&m=currentmesg&s=subscriberid', $content);

	$content = str_replace('%SOCIAL-FACEBOOK-LIKE%', '<a target="_blank" href="' . $murl . '/index.php?action=social&c=cmpgnhash.currentmesg&facebook=like" aclinkname="Social: Facebook Like Button"><img src="' . $murl . '/images/social_facebook_like.gif" border="0" height="24" width="48" alt="' . _a("Like") . '" style="border: none;" /></a>', $content);

	// forward to a friend link
	$content = str_replace('%PERS_FRIEND%'   , $murl . '/forward.php?nl=currentnl&c=cmpgnid&m=currentmesg&s=subscriberid', $content);
	$content = str_replace('%FORWARD2FRIEND%', $murl . '/forward.php?nl=currentnl&c=cmpgnid&m=currentmesg&s=subscriberid', $content);
	// unsubscribe link
	$content = str_replace('%PERS_UNSUB%'     , $murl . '/surround.php?nl=currentnl&c=cmpgnid&m=currentmesg&s=subscriberid&funcml=unsub2', $content);
	$content = str_replace('%UNSUBSCRIBELINK%', $murl . '/surround.php?nl=currentnl&c=cmpgnid&m=currentmesg&s=subscriberid&funcml=unsub2', $content);

	$content = str_replace('%PERS_TODAY%', adesk_date_format(adesk_getCurrentDate(), $site['dateformat']), $content);
	$content = str_replace('%TODAY%'     , adesk_date_format(adesk_getCurrentDate(), $site['dateformat']), $content);

	// social share link
	if ( preg_match_all('/%SOCIALSHARE-?([^%]*)?%/', $content, $matches) ) {//dbg(debug_backtrace());
		require_once(awebdesk_functions('smarty.php'));
		$smarty = new adesk_Smarty('global', false);
		$smarty->assign('site', $site);
		//$smarty->assign('shareURL', $murl . '/index.php?action=social&c=cmpgnhash.currentmesg');
		$smarty->assign('shareURL', $murl . '/we.php?c=cmpgnid&m=currentmesg&s=subscriberid');
		//$smarty->assign('shareTitle', $subject);
		foreach ( $matches[1] as $k => $perstag ) {
			$smarty->assign('filter', strtolower($perstag));
			$socialshare = $smarty->fetch('social.share.inc.htm');
			$content = str_replace($matches[0][$k], $socialshare, $content);
		}
	}

	//if ( $site['mail_abuse'] )
		$content = str_replace('%REPORTABUSE%', $murl . '/index.php?action=abuse&nl=currentnl&c=cmpgnid&m=currentmesg&s=subscriberid', $content);

	// %TODAY+1% / %TODAY-1%
	preg_match_all('/%TODAY([+-]\d+)%/', $content, $matches);
	if ( isset($matches[0]) and count($matches[0]) > 0 ) {
		foreach ( $matches[1] as $k => $v ) {
			// today tag
			$matches[1][$k] = adesk_date_format(adesk_getCurrentDate(), $site['dateformat'], (int)$v * 24);
		}
		$content = str_replace($matches[0], $matches[1], $content);
	}

	return $content;
}

function personalization_form($content) {
	// Subscriber-specific personalization tags are sometimes invalid
	// Always call this function after you do the subscriber-specific replacing
	$content = str_replace("%EMAIL%", "", $content);
	$content = str_replace("%FIRSTNAME%", "", $content);
	$content = str_replace("%LISTNAME%", "", $content);
	$content = str_replace("%LASTNAME%", "", $content);
	$content = str_replace("%FULLNAME%", "", $content);
	$content = str_replace("%SUBSCRIBERIP%", "", $content);
	//$content = str_replace("%SENDDATE%", "", $content);
	$content = str_replace("%SUBDATE%", "", $content);
	//$content = str_replace("%SENDTIME%", "", $content);
	$content = str_replace("%SUBTIME%", "", $content);
	//$content = str_replace("%SENDDATETIME%", "", $content);
	$content = str_replace("%SUBDATETIME%", "", $content);
	$content = str_replace("%SUBSCRIBERID%", "", $content);
	$content = str_replace("%SUBSCRIBER_RATING%", "", $content);
	$content = str_replace("%CONFIRMLINK%", "", $content);
	$content = str_replace("%SUBSCRIBELINK%", "", $content);
	$content = str_replace("%UNSUBSCRIBELINK%", "", $content);
	$content = str_replace("%FORWARD2FRIEND%", "", $content);
	$content = str_replace("%UPDATELINK%", "", $content);
	$content = str_replace("%SOCIALSHARE%", "", $content);
	$socnets = array_map('strtoupper', personalization_social_networks());
	foreach ( $socnets as $sn ) $content = str_replace("%SOCIALSHARE-$sn%", '', $content);
	$content = str_replace("%SOCIAL-FACEBOOK-LIKE%", "", $content);

	$content = personalization_basic($content, '');

	return $content;
}

// apply given sender personalizations ($ary)
// don't care for $format, assume it's filtered array;
// use list_personalizations($so) or a variation to get the $ary to apply
function personalization_apply($body, $ary = array()) {
	if ( !is_string($body) or !$body ) return $body;
	if ( !is_array($ary) or count($ary) == 0 ) return $body;
	foreach ( $ary as $v ) {
		$body = str_replace("%$v[tag]%", trim($v['content']), $body);
	}
	return $body;
}

function personalization_conditional($replacements, $value, $inCampaign = false) {
	// conditional content present, gotta run Smarty parser
	if ( $inCampaign ) campaign_sender_log("Conditional Content detected! Running an extended message parser...");
	$hash = md5($value);
	$orig = $value;
	// set the smarty variables array, and replace content to be more smarty-like
	$vars = array();
	$filename = adesk_cache_dir($hash.'.msg');
	$doReplace = ( !file_exists($filename) );
	if ( $doReplace ) {
		$value = str_replace('{', '[*[*[*', $value);
		$value = str_replace('{', '*]*]*]', $value);
		$value = str_replace('[*[*[*', '{literal}{{/literal}', $value);
		$value = str_replace('*]*]*]', '{literal}}{/literal}', $value);
	}
	foreach ( $replacements as $k => $v ) {
		// strip dashes into underscores, and remove our % signs to make a real key varname
		$key = trim(str_replace('-', '_', $k), '%');
		// assign it to smarty vars array
		$vars[$key] = $v;
		// if smarty template has not yet been created
		if ( $doReplace ) {
			// replace our internal personalization tag with smarty code
			$value = str_replace($k, '{$' . $key . '}', $value);
		}
	}
	// if cache file was not yet created
	if ( $doReplace ) {
		// convert our tags
		$mapkeys = array(' &gt; ', ' &gt;= ', ' &lt; ', ' &lt;= ');
		$mapvals = array(' > ', ' >= ', ' < ', ' <= ');
		if ( preg_match_all('/%(ELSE)?IF ([^%]*)%/', $value, $m) ) {
			foreach ( $m[0] as $k => $v ) {
				$n = str_replace($mapkeys, $mapvals, $v);
				$value = str_replace($v, $n, $value);
			}
		}
		$value = str_replace('%ELSE%', '{else}', $value);
		$value = str_replace('%/IF%', '{/if}', $value);
		$value = preg_replace('/%IF ([^%]*)%/', '{if $1}', $value);
		$value = preg_replace('/%ELSEIF ([^%]*)%/', '{elseif $1}', $value);
		$value = str_replace('~PERCENT~', '%', $value);
		// create it now
		adesk_file_put(adesk_cache_dir($hash.'.msg'), $value);
	}
	// if smarty was not initialized earlier, initialize it now
	if ( !isset($GLOBALS['_mailsmarty']) ) {
		require_once(awebdesk_functions('smarty.php'));
		// initialize smarty
		$GLOBALS['_mailsmarty'] = new adesk_Smarty('admin');
		// include our cache folder
		$GLOBALS['_mailsmarty']->template_dir = array(adesk_cache_dir());
	}
	// assign personalization tags as smarty vars
	$GLOBALS['_mailsmarty']->assign($vars);
	// get the personalized content
	if ( $inCampaign ) campaign_sender_log("Loading the message content from extended message parser...");
	$value = @$GLOBALS['_mailsmarty']->fetch($hash.'.msg');
	if ( !$value ) return $orig;
	return $value;
}

function personalization_senderinfo($list) {
	$keys = array();
	$vals = array();
	$isEmpty = true;
	foreach ( $list as $k => $v ) {
		if ( substr($k, 0, strlen('sender_')) == 'sender_' ) {
			$key = '{' . substr($k, strlen('sender_')) . '}';
			$keys[] = $key;
			$vals[] = $v;
			if ( $v ) $isEmpty = false;
		}
	}
	if ( $isEmpty ) return '';
	$format = _a("{name}<br />{addr1}, {addr2}<br />{city}, {state} {zip}<br />{country}");
	return str_replace($keys, $vals, $format);
}

function personalization_social_networks() {
	return array('facebook', 'twitter', 'digg', 'reddit', 'delicious', 'greader', 'stumbleupon');
}

?>
