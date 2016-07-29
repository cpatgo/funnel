<?php

function adesk_mail_extract($message_source) {
	require_once awebdesk_pear('mimeDecode.php');
	$params = array(
		'include_bodies' => true,
		'decode_bodies' => true,
		'decode_headers' => true,
		'input' => $message_source,
	);
	$structure = Mail_mimeDecode::decode($params);
	if ( !isset($structure->headers['to']) ) {
		// add \r\n\r\n to the end to make sure that the headers are extracted by mime decode
		$params['input'] .= "\r\n\r\n";
		$structure = Mail_mimeDecode::decode($params);
		if ( !isset($structure->headers['to']) ) return false;
	}

	//Deal with subject char encoding
 	if (preg_match('/Subject: =\?([^?]+)/', $message_source, $matches)) {
		$structure->headers['subject'] = isset($structure->headers['subject']) ? adesk_utf_conv(strtoupper($matches[1]), "UTF-8", $structure->headers['subject']) : '';
	}

	return $structure;
}

function adesk_mail_extract_components($structure, $filter = null) {
	if ( !$structure ) return false;
	if ( !$filter or !is_array($filter) ) {
		// default function call returns these
		$filter = array(
			'subject',
			'body',
			'parts',
			'ctype',
			'charset',
			'from',
			'from_name',
			'from_email',
			'to',
			'to_email',
			'to_name',
			'cc',
			'reply2',
			'reply2_email',
			'reply2_name',
			'attachments',
			'ip',
			'structure',
		);
	}
	// assemble the results according to the filter array
	$r = array();
	// get subject
	if ( in_array('subject', $filter) ) {
		$r['subject'] = ( isset($structure->headers['subject']) ? $structure->headers['subject'] : '' );
	}
	// now get body
	if ( in_array('body',       $filter) ) $r['body']       = adesk_mail_extract_body($structure, in_array('prefertext', $filter));
	// now content-type
	if ( in_array('ctype',      $filter) ) $r['ctype']      = adesk_mail_extract_ctype($structure);
	// now content's character set
	if ( in_array('charset',    $filter) ) $r['charset']    = adesk_mail_extract_charset($structure);
	// now message parts (text,html)
	if ( in_array('parts',      $filter) ) $r['parts']      = adesk_mail_extract_parts($structure);
	// get from
	$from = ( isset($structure->headers['from']) ? $structure->headers['from'] : '' );
	if ( in_array('from',       $filter) ) $r['from']       = $from;
	// get to
	$to = ( isset($structure->headers['to']) ? $structure->headers['to'] : '' );
	if ( in_array('to',         $filter) ) $r['to']         = $to;
	// get cc
	$cc = ( isset($structure->headers['cc']) ? $structure->headers['cc'] : '' );
	if ( in_array('cc',         $filter) ) $r['cc']         = $cc;
	// get reply2
	$reply2 = ( isset($structure->headers['reply-to']) ? $structure->headers['reply-to'] : '' );
	if ( in_array('reply2',     $filter) ) $r['reply2']     = $reply2;
	// get from email
	if ( in_array('from_email', $filter) ) $r['from_email'] = adesk_mail_extract_email($from);
	// get from name
	if ( in_array('from_name',  $filter) ) $r['from_name']  = adesk_mail_extract_name($from);
	// get to email
	if ( in_array('to_email',   $filter) ) $r['to_email']   = adesk_mail_extract_email($to);
	// get to name
	if ( in_array('to_name',    $filter) ) $r['to_name']    = adesk_mail_extract_name($to);
	// get cc email
	if ( in_array('cc_email',   $filter) ) $r['cc_email']   = adesk_mail_extract_email($cc);
	// get cc name
	if ( in_array('cc_name',    $filter) ) $r['cc_name']    = adesk_mail_extract_name($cc);
	// get reply2 email
	if ( in_array('reply2_email', $filter) ) $r['reply2_email'] = adesk_mail_extract_email($reply2);
	// get reply2 name
	if ( in_array('reply2_name',  $filter) ) $r['reply2_name']  = adesk_mail_extract_name($reply2);
	// fetch attachments
	if ( in_array('attachments', $filter) ) {
		$r['attachments'] = adesk_mail_extract_attachments( isset($structure->parts) ? $structure->parts : array($structure) );
	}
	// fetch ip
	if ( in_array('ip',         $filter) ) $r['ip']         = adesk_mail_extract_ip($structure);
	// pass the structure back
	if ( in_array('structure',  $filter) ) $r['structure']  = $structure;
	return $r;
}

function adesk_mail_extract_email($header) {
	if ( is_array($header) ) $header = $header[0];
	preg_match('/&lt;(.*)&gt;/U', htmlspecialchars($header), $match);
	return ( isset($match[1]) ? $match[1] : $header );
}

function adesk_mail_extract_name($header) {
	if ( is_array($header) ) $header = $header[0];
	$name = preg_replace('|<.*>|', '', $header); // strip email and <>
	$name = trim(preg_replace('|"|', '', $name));  // strip quotes
	return $name;
}

function adesk_mail_extract_ip($structure) {
	// we need IP address from email header
	if ( isset($structure->headers['received'][1]) ) {
		$preg_ip = '/by (\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})/';
		preg_match_all($preg_ip, $structure->headers['received'][1], $findIP);
	} else $findIP = array();
	return ( isset($findIP[1][0]) ? $findIP[1][0] : '' );
}

function adesk_mail_extract_parts($structure) {
	$r = array(
		'text' => '',
		'text_charset' => '',
		'html' => '',
		'html_charset' => '',
	);
	adesk_mail_extract_body_recursive($r, $structure);
	return $r;
}

function adesk_mail_extract_body($structure, $prefertext = false) {
	$r = array(
		'text' => '',
		'text_charset' => '',
		'html' => '',
		'html_charset' => '',
	);
	adesk_mail_extract_body_recursive($r, $structure);
	if ( $prefertext ) {
		return ( $r['text'] != '' ? $r['text'] : adesk_str_strip_tags($r['html']) );
	}
	return ( $r['html'] == '' ? $r['text'] : $r['html'] );
}

function adesk_mail_extract_recipients($header, $exclude = array()) {
	$r = array();
	$didExclude = false;
	$parts = explode(',', $header);
	foreach ( $parts as $v ) {
		$v = trim($v);
		preg_match_all("|<(\S*)>$|", $v, $matches);
		if ( isset($matches[1]) and count($matches[1]) > 0 ) {
			foreach ( $exclude as $email ) {
				$x = array_search($email, $matches[1]);
				if ( $x !== false ) {
					unset($matches[1][$x]);
					$didExclude = true;
				}
			}

			if (is_array($matches[1])) {
				foreach ($matches[1] as $m) {
					if (adesk_str_is_email($m))
						$r[] = $m;
				}
			}
			#if ( count($matches[1]) > 0 ) $r = array_merge($r, $matches[1]);
		} elseif ( adesk_str_is_email($v) ) {
			if ( !in_array($v, $exclude) ) $r[] = $v;
			else $didExclude = true;
		}
	}
	$r = array_unique($r);
	if ( count($r) == 1 and count($exclude) and !$didExclude ) $r = array(); // fix for email forwarding on the server
	return $r;
}

function adesk_mail_extract_ctype($structure) {
	$r = array(
		'text' => '',
		'text_charset' => '',
		'html' => '',
		'html_charset' => '',
	);
	adesk_mail_extract_body_recursive($r, $structure);
	return ( $r['html'] == '' ? 'text/plain' : 'text/html' );
}

function adesk_mail_extract_charset($structure) {
	$r = array(
		'text' => '',
		'text_charset' => '',
		'html' => '',
		'html_charset' => '',
	);
	adesk_mail_extract_body_recursive($r, $structure);
	return ( $r['html'] == '' ? $r['text_charset'] : $r['html_charset'] );
}

function adesk_mail_extract_body_recursive(&$r, $structure) {
	if ( isset($structure->body) ) {
		if
		(
			isset($structure->ctype_primary)
		and
			strtolower($structure->ctype_primary) == 'text'
		and
			isset($structure->ctype_secondary)
		and
			strtolower($structure->ctype_secondary) == 'plain'
		and
			$r['text'] == ''
		) {
			$r['text'] = $structure->body;
			$r['text_charset'] = ( isset($structure->ctype_parameters['charset']) ? $structure->ctype_parameters['charset'] : '' );
		}
		if
		(
			isset($structure->ctype_primary)
		and
			strtolower($structure->ctype_primary) == 'text'
		and
			isset($structure->ctype_secondary)
		and
			strtolower($structure->ctype_secondary) == 'html'
		and
			$r['html'] == ''
		and
			!( isset($structure->disposition) and $structure->disposition == 'attachment' )
		) {
			$r['html'] = $structure->body;
			$r['html_charset'] = ( isset($structure->ctype_parameters['charset']) ? $structure->ctype_parameters['charset'] : '' );
		}
	} else {
		// isset parts
		if ( isset($structure->parts) and is_array($structure->parts) ) {
			foreach ( $structure->parts as $p ) {
				adesk_mail_extract_body_recursive($r, $p);
			}
		}
	}
	return;
}


function adesk_mail_extract_attachments($parts) {
	$r = array();
	foreach ( $parts as $part ) {
		// save only if it's an attachment
		if ( !isset($part->body) and isset($part->parts) ) {
			$r2 = adesk_mail_extract_attachments($part->parts);
			if ( count($r2) ) $r = array_merge($r, $r2);
		} else {
			if ( isset($part->disposition) and $part->disposition == 'attachment' or isset($part->headers['content-transfer-encoding']) ) {
				$file = array();
				// Set the timestamp
				$file['tstamp'] = adesk_sql_select_one("SELECT NOW()");
				// Set the mimetype
				$mimeType = '';
				if ( isset($part->ctype_primary) and isset($part->ctype_secondary) ) {
					$mimeType = $part->ctype_primary . '/' . $part->ctype_secondary;
				}
				$file['mimetype'] = $mimeType;
				// Set the real name				
				if ( isset($part->ctype_parameters['name']) ) {
					$file['name'] = $part->ctype_parameters['name'];
				}
				elseif ( isset($part->d_parameters['filename']) ) {
					$file['name'] = $part->d_parameters['filename'];
				}
				elseif ( isset($part->ctype_parameters) && count($part->ctype_parameters) > 1 ) {
					$name = "";
					foreach ( $part->ctype_parameters as $k => $v ) {
						if ( preg_match("/\*/", $k) ) {
							$name .= $v;
						}
					}
					$file['name'] = $name;
				}
				else {
					$file['name'] = 'no-name';
				}
				// Set the encoding
				$file['encoding'] = ( isset($part->headers['content-transfer-encoding']) ? $part->headers['content-transfer-encoding'] : '' );
				// Read in the file
				$file['data'] = $part->body;
				$file['size'] = strlen($file['data']);
				// Save it to the database
/*				if ( isset($part->ctype_parameters['name']) or isset($part->d_parameters['filename'])) {
					$r[] = $file;
				}
*/				if ( ( isset($part->disposition) and $part->disposition == 'attachment' ) or ( isset($part->ctype_parameters['name']) or isset($part->d_parameters['filename']) ) ) {
					$r[] = $file;
				}
			}
		}
	}
	return $r;
}


function adesk_mail_embed_images(&$html, $embed = false, $css2 = false) {
	$r = array();
	$url = adesk_site_plink();
	$arr = array(
		'img' => 'src',
		'table' => 'background',
		'td' => 'background',
		'th' => 'background',
		'body' => 'background',
	);
	foreach ( $arr as $tag => $attrib ) {
		$urlPatternA = '/<' . $tag . ' (.*?)>/si';
		preg_match_all($urlPatternA, $html, $anchors);
		/* DOUBLE QUOTES */
		foreach ( $anchors[0] as $a ) {
			$urlPatternHREF =
				'/((' . $attrib . '=\"http|' . $attrib . '=\"https|' . $attrib . '=\"ftp):\/\/|www)' . // line 1
				'[a-z0-9\-\._]+\/?[a-z0-9_\.\-\?\+\/~=&#%;:\|,\[\]]*' . // line 2
				'[a-z0-9\/=?&;%]{1}/si' // line 3
			;
			preg_match_all($urlPatternHREF, $a, $matches);
			foreach ( $matches[0] as $v ) {
				$old_link = $new_link = '';
				if ( $v == '' ) continue;
				if ( $v == "$attrib=\"" ) continue;
				if ( !adesk_str_instr($attrib.'=', $v) ) continue;
				if ( adesk_str_instr('http', $v) ) {
					if ( substr($v, 0, 1) == '#' ) continue;
					if ( adesk_str_instr('mailto:', $v) ) continue;
					if ( adesk_str_instr($url . '/lt/t_go.php', $v) ) continue;
					if ( adesk_str_instr($url . '/lt.php', $v) ) continue;
				}
				$old_link = $new_link = $v;
				$old_link = str_replace($attrib.'=', '', $old_link);
				$new_link = str_replace($attrib.'=', '', $new_link);
				$new_link = str_replace('"', '', $new_link);
				if ( $new_link != '' and $old_link != '' ) {
					$hash = md5($new_link);
					$extPos = strrpos($new_link, '.');
					if ( $extPos ) $hash .= substr($new_link, $extPos);
					if ( $embed ) {
						$body = adesk_http_get($new_link);
//d b g ("URL: $new_link\n\nHASH: $hash\n\nBODY:\n$body", true);
						if ( !adesk_str_instr($new_link, $body) ) {
							$new_link = "\"cid:$hash\"";
							$html = str_replace($old_link . '"', $new_link, $html);
							$r[$hash] = $body;
						}
					} else {
						$r[$new_link] = $hash;
					}
					//print "found 1<br>old - $old_link<br>new - $new_link";
					//print $new_link;
				}
			}
			$urlPatternHREF =
				"/(($attrib=\'http|$attrib=\'https|$attrib=\'ftp):\/\/|www)" . // line 1
				"[a-z0-9\-\._]+\/?[a-z0-9_\.\-\?\+\/~=&#%;:\|,\[\]]*" . // line 2
				"[a-z0-9\/=?&;%]{1}/si" // line 3
			;
			preg_match_all($urlPatternHREF, $a, $matches);
			/* SINGLE QUOTES */
			foreach ( $matches[0] as $v ) {
				$old_link = $new_link = '';
				if ( $v == '' ) continue;
				if ( $v == "$attrib='" ) continue;
				if ( !adesk_str_instr($attrib.'=', $v) ) continue;
				if ( adesk_str_instr('http', $v) ) {
					if ( substr($v, 0, 1) == '#' ) continue;
					if ( adesk_str_instr('mailto:', $v) ) continue;
					if ( adesk_str_instr($url . '/lt/t_go.php', $v) ) continue;
					if ( adesk_str_instr($url . '/lt.php', $v) ) continue;
				}
				//$something = str_replace("\" target=\"_", "", $something);
				//$something = str_replace("?", "\?", $something);
				$old_link = $new_link = $v;
				$old_link = str_replace($attrib.'=', '', $old_link);
				$new_link = str_replace($attrib.'=', '', $new_link);
				$new_link = str_replace("'", '', $new_link);
				if ( $new_link != '' and $old_link != '' ) {
					$hash = md5($new_link);
					$extPos = strrpos($new_link, '.');
					if ( $extPos ) $hash .= substr($new_link, $extPos);
					if ( $embed ) {
						$body = adesk_http_get($new_link);
//d b g ("URL: $new_link\n\nHASH: $hash\n\nBODY:\n$body", true);
						if ( !adesk_str_instr($new_link, $body) ) {
							$new_link = "'cid:$hash'";
							$html = str_replace($old_link . "'", $new_link, $html);
							$r[$hash] = $body;
						}
					} else {
						$r[$new_link] = $hash;
					}
					//print "found 1<br>old - $old_link<br>new - $new_link";
					//print $new_link;
				}
			}
		}
	}
	if ( !$css2 ) return $r;

	$patterns = array(
		'/<style(.*?)>(.*)<\/style>/si' => 2,
		'/ style="(.*)"/si' => 1,
		'/ style=\'(.*)\'/si' => 1,
	);
	foreach ( $patterns as $pattern => $matchindex ) {
		preg_match_all($pattern, $html, $anchors);
		if ( !isset($anchors[$matchindex]) ) continue;
		foreach ( $anchors[$matchindex] as $a ) {
			$urlPatternHREF =
				'/((url\(http|url\(https|url\(ftp):\/\/|www)' . // line 1
				'[a-z0-9\-\._]+\/?[a-z0-9_\.\-\?\+\/~=&#%;:\|,\[\]]*' . // line 2
				'[a-z0-9\/=?&;%]{1}/si' // line 3
			;
			preg_match_all($urlPatternHREF, $a, $matches);
			foreach ( $matches[0] as $v ) {
				$old_link = $new_link = '';
				if ( $v == '' ) continue;
				if ( $v == "url(" ) continue;
				if ( $v == "url()" ) continue;
				if ( !adesk_str_instr('url(', $v) ) continue;

				$old_link = $new_link = $v;
				$old_link = trim(trim(substr($old_link, 3), '('), ')');
				$new_link = trim(trim(substr($new_link, 3), '('), ')');
				if ( $new_link != '' and $old_link != '' ) {
					$hash = md5($new_link);
					$extPos = strrpos($new_link, '.');
					if ( $extPos ) $hash .= substr($new_link, $extPos);
					if ( $embed ) {
						$body = adesk_http_get($new_link);
//d b g ("URL: $new_link\n\nHASH: $hash\n\nBODY:\n$body", true);
						if ( !adesk_str_instr($new_link, $body) ) {
							$new_link = "cid:$hash";
							$html = str_replace($old_link, $new_link, $html);
							$r[$hash] = $body;
						}
					} else {
						$r[$new_link] = $hash;
					}
					//print "found 1<br>old - $old_link<br>new - $new_link";
					//print $new_link;
				}
			}
		}
	}
	return $r;
}

?>
