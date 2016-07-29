<?php
// mailer.php

// functions for utilizing our mailer (old settings_mail) page


//function adesk_api_mailconn_save($id, $type, $host, $port, $user, $pass, $enc, $pop3b4, $thres, $freq, $num, $lim, $span) {
function adesk_api_mailconn_save() {
	extract($_POST); // hack to use post
	require(adesk_admin('functions/awebdesk.php'));
	if ( !isset($GLOBALS['adesk_mail_engine']) ) $GLOBALS['adesk_mail_engine'] = 'mailer';
	$rotator = ( $GLOBALS['adesk_mail_engine'] == 'swift' and $GLOBALS['adesk_mail_table'] != 'backend' );
	$names = array(0 => 'mail()', 1 => 'SMTP', 3 => 'SendMail'); // -1: rotator
	if ( !isset($names[$type]) ) $type = 0;
	$id = (int)adesk_b64_decode($id);
	$host = adesk_b64_decode($host);
	$port = adesk_b64_decode($port);
	$user = adesk_b64_decode($user);
	$pass = adesk_b64_decode($pass);
	$enc = adesk_b64_decode($enc);
	$pop3b4 = adesk_b64_decode($pop3b4);
	$thres = adesk_b64_decode($thres);
	$freq = adesk_b64_decode($freq);
	$num = adesk_b64_decode($num);
	$lim = adesk_b64_decode($lim);
	$span = adesk_b64_decode($span);
	$r = array(
		'name' => ( $id == 0 ? 'add' : 'edit' ),
		'id' => $id,
		'type' => (int)$type,
		'host' => $host,
		'port' => (int)$port,
		'user' => $user,
		'pass' => $pass,
		'encrypt' => (int)$enc,
		'pop3b4smtp' => (int)$pop3b4,
		'threshold' => (int)$thres,
		'frequency' => (int)$freq,
		'pause' => (int)$num,
		'limit' => (int)$lim,
		'limitspan' => ( $span == 'day' ? 'day' : 'hour' ),
		'succeeded' => 0
	);
	if ( !adesk_admin_ismaingroup() ) return $r;
	$site =& $GLOBALS['site'];
	$arr = array();
	if ( $id == 0 ) {
		// add
		$r['name'] = 'add';
		// only for rotator...
		if ( !$rotator ) return $r;
		$arr['id'] = 0;
		$arr['type'] = (int)$type;
		$arr['host'] = $host;
		$arr['port'] = (int)$port;
		$arr['user'] = $user;
		$arr['pass'] = base64_encode($pass);
		$arr['encrypt'] = (int)$enc;
		$arr['pop3b4smtp'] = (int)$pop3b4;
		$arr['threshold'] = (int)$thres;
		// AEM5 only
		if ( adesk_site_isAEM5() ) {
			$arr['frequency'] = (int)$freq;
			$arr['pause'] = (int)$num;
			$arr['limit'] = (int)$lim;
			$arr['limitspan'] = ( $span == 'day' ? 'day' : 'hour' );
			if ( $arr['frequency'] and $arr['pause'] ) $arr['limit'] = 0;
		}
		$arr['corder'] = 99999;
		// do insert
		$r['succeeded'] = adesk_sql_insert('#' . $GLOBALS['adesk_mail_table'], $arr);
		// if done
		if ( $r ) {
			// collect new id
			$r['id'] = adesk_sql_insert_id();
			// run ihooks
			$tmp = adesk_ihook('adesk_mailconn_save', $r['id'], $arr);
			if ( is_array($tmp) ) $r = array_merge($tmp, $r);
		}
		// and done
		return $r;
	} // add stopped here
	// edit
	if ( $GLOBALS['adesk_mail_table'] != 'backend' ) {
		$arr['type'] = (int)$type;
		$arr['host'] = $host;
		$arr['port'] = (int)$port;
		$arr['user'] = $user;
		$arr['pass'] = base64_encode($pass);
		if ( $GLOBALS['adesk_mail_engine'] == 'swift' ) {
			$arr['encrypt'] = (int)$enc;
			$arr['pop3b4smtp'] = (int)$pop3b4;
		}
		if ( $rotator ) {
			$arr['threshold'] = (int)$thres;

			if ( adesk_site_isAEM5() ) {
				$arr['frequency'] = (int)$freq;
				$arr['pause'] = (int)$num;
				$arr['limit'] = (int)$lim;
				$arr['limitspan'] = ( $span == 'day' ? 'day' : 'hour' );
				if ( $arr['frequency'] and $arr['pause'] ) $arr['limit'] = 0;
			}
		}
	} else {
		// UPDATE BACKEND TABLE IN APPS THAT DONT USE MAILER TABLE
		$id = 1;
		$arr['stype'] = (int)$type;
		$arr['smhost'] = $host;
		$arr['smport'] = (int)$port;
		$arr['smuser'] = $user;
		$arr['smpass'] = base64_encode($pass);
		if ( $GLOBALS['adesk_mail_engine'] == 'swift' ) {
			$arr['smenc'] = (int)$enc;
			$arr['smpop3b4'] = (int)$pop3b4;
		}
	}
	// do update
	$r['succeeded'] = adesk_sql_update('#' . $GLOBALS['adesk_mail_table'], $arr, "id = '$id'");
	if ( $r['succeeded'] ) {
		// run ihooks
		$tmp = adesk_ihook('adesk_mailconn_save', $id, $arr);
		if ( is_array($tmp) ) $r = array_merge($tmp, $r);
	}
	return $r;
}

function adesk_api_mailconn_delete($ids) {
	require(adesk_admin('functions/awebdesk.php'));
	if ( !isset($GLOBALS['adesk_mail_engine']) ) $GLOBALS['adesk_mail_engine'] = 'mailer';
	$rotator = ( $GLOBALS['adesk_mail_engine'] == 'swift' and $GLOBALS['adesk_mail_table'] != 'backend' );
	$r = array('succeeded' => 0, 'name' => 'delete', 'ids' => $ids, 'list' => array());
	if ( !adesk_admin_ismaingroup() ) return $r;
	if ( !$rotator ) return $r;
	$idArr = explode(',', $ids);
	$arr = array();
	foreach ( $idArr as $v ) {
		$v = (int)$v;
		if ( $v > 1 ) $arr[$v] = $v;
	}
	if ( count($arr) == 0 ) return $r;
	$r['list'] = $arr;
	$r['ids'] = implode(',', $arr);
	$list = implode("', '", $arr);
	// do delete
	$succeeded = adesk_sql_delete('#' . $GLOBALS['adesk_mail_table'], "id IN ('$list')");
	adesk_ihook("adesk_mailer_delete", $list);
	return adesk_ajax_api_result($succeeded, _a("Mail connection deleted"), $r);
}

function adesk_api_mailconn_order($ids, $orders) {
	require(adesk_admin('functions/awebdesk.php'));
	if ( !isset($GLOBALS['adesk_mail_engine']) ) $GLOBALS['adesk_mail_engine'] = 'mailer';
	$rotator = ( $GLOBALS['adesk_mail_engine'] == 'swift' and $GLOBALS['adesk_mail_table'] != 'backend' );
	$r = array('succeeded' => 0, 'name' => 'order', 'ids' => $ids, 'orders' => $orders);
	if ( !adesk_admin_ismaingroup() ) return $r;
	if ( !$rotator ) return $r;
	$ary_ids    = explode(',', $ids);
	$ary_orders = explode(',', $orders);
	if ( count($ary_ids) != count($ary_orders) ) {
		return adesk_ajax_error(_a("The ids and order numbers do not match."));
	}
	for ( $i = 0; $i < count($ary_ids); $i++ ) {
		$id     = (int)$ary_ids[$i];
		$ary    = array('corder' => (int)$ary_orders[$i]);
		$r['succeeded'] = adesk_sql_update('#' . $GLOBALS['adesk_mail_table'], $ary, "`id` = '$id'");
	}
	return $r;
}

function adesk_api_mailconn_dotfix($id) {
	$id = (int)$id;
	require(adesk_admin('functions/awebdesk.php'));
	if ( !isset($GLOBALS['adesk_mail_engine']) ) $GLOBALS['adesk_mail_engine'] = 'mailer';
	$rotator = ( $GLOBALS['adesk_mail_engine'] == 'swift' and $GLOBALS['adesk_mail_table'] != 'backend' );
	$table = $GLOBALS['adesk_mail_table'];
	$r = array('succeeded' => 0, 'name' => 'dotfix', 'id' => $id, 'dotfix' => 0);
	if ( !adesk_admin_ismaingroup() ) return $r;
	if ( !$rotator ) {
		return $r;
		/*
		// assign config array
		$cfg = array(
			'id' => 1,
			'type' => $this->site['stype'],
			'host' => $this->site['smhost'],
			'port' => (int)$this->site['smport'],
			'user' => $this->site['smuser'],
			'pass' => ( $this->site['smpass'] == '' ? '' : base64_decode($this->site['smpass']) ), // decoding mail password
		);
		if ( $this->engine == 'swift' ) {
			$cfg['encrypt'] = $this->site['smenc'];
			$cfg['pop3b4smtp'] = $this->site['smpop3b4'];
			//$cfg['pop3b4smtp'] = $this->site['smpop3b4'];
		}
		if ( $this->rotator ) {
			$cfg['threshold'] = $this->site['smthres'];
			$cfg['frequency'] = $this->site['sdfreq'];
			$cfg['pause'] = $this->site['sdnum'];
			$cfg['limit'] = $this->site['sdlim'];
			$cfg['limitspan'] = $this->site['sdspan'];
		}
		$conn = $cfg;
		*/
	} else {
		if ( !$id ) return $r;
		$conn = adesk_sql_select_row("SELECT * FROM #$table WHERE id = '$id'");
		if ( !$conn ) return $r;
	}

	// try to grab the source code
	$site  =& $GLOBALS['site'];
	$admin =& $GLOBALS['admin'];
	$to_name = $to_email = $admin['email'];
	if ( isset($site['site_name']) ) {
		$from_name = $site['site_name'];
	} elseif ( isset($site['sname']) ) {
		$from_name = $site['sname'];
	} else {
		$from_name = $_SERVER['SERVER_NAME'];
	}
	if ( isset($site['emfrom']) ) {
		$from_email = $site['emfrom'];
	} elseif ( isset($site['awebdesk_from']) ) {
		$from_email = $site['awebdesk_from'];
	} else {
		$from_email = 'test@' . $_SERVER['SERVER_NAME'];
	}
	$bounce_email = $site['awebdesk_bounce'];
	$subject = _a("Mail Sending Options Test");
	$message = "Testing dots problem:\n.dotfix\n";

	$options = array(
		'bounce' => $site['awebdesk_bounce'],
		'attach' => array(),
		'headers' => array(),
		'reply2' => '',
		'priority' => 3, // 3-normal, 1-low, 5-high
		'encoding' => _i18n("8bit"),
		'charset' => _i18n("utf-8"),

		'altbody' => $message,
		//'getsource' => 1
	);

	$source = adesk_mail_send_raw(
		'text',
		$from_name,
		$from_email,
		$message,
		$subject,
		$to_email,
		$to_name,
		$conn['type'],
		$conn['host'],
		$conn['port'],
		$conn['user'],
		$conn['pass'],
		$conn['encrypt'],
		$conn['pop3b4smtp'],
		$options
	);
	//dbg($source);

	if ( !$source or is_array($source) ) return $r;

	$dotFix = (int)( adesk_str_instr('..dotfix', $source) or !adesk_str_instr('.dotfix', $source) );
	$r['dotfix'] = $dotFix;

	// do update
	if ( !$rotator ) {
		$r['succeeded'] = adesk_sql_update_one('#' . $GLOBALS['adesk_mail_table'], 'sddotfix', $dotFix, '0');
	} else {
		$r['succeeded'] = adesk_sql_update_one('#' . $GLOBALS['adesk_mail_table'], 'dotfix', $dotFix, "0 AND id = '$id'");
	}

	return adesk_ajax_api_result($r['succeeded'], _a("Mail connection tested OK."), $r);
}

?>