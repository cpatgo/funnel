<?php


//////////////////////////////////////////////////////////////////////////////////////////////////
// INSTANTIATE SWIFT MAILER

// define swift mailer's constants we want to change
if ( !defined('SWIFT_ABS_PATH') ) define('SWIFT_ABS_PATH', dirname(__FILE__));
if ( !defined('PRINT_SENDING_LOG') ) define('PRINT_SENDING_LOG', 0);
if ( !defined('SWIFT_LOG_NORMAL') ) define('SWIFT_LOG_NORMAL', '');

// include swift mailer core class
require_once(SWIFT_ABS_PATH . '/Swift.php');

// include our swift mailer plugin extensions
require_once(dirname(__FILE__) . '/logger.php');
require_once(dirname(__FILE__) . '/encoder.php');
require_once(dirname(__FILE__) . '/iterator.php');
require_once(dirname(__FILE__) . '/rotator.php');
require_once(dirname(__FILE__) . '/embedder.php');
require_once(dirname(__FILE__) . '/decorator.php');
require_once(dirname(__FILE__) . '/sendlistener.php');
require_once(dirname(__FILE__) . '/throttler.php');


/**
 * Handles batch mailing with Swift Mailer with fail-safe support.
 * Restarts the connection if it dies and then continues where it left off.
 * Please read the LICENSE file
 * @copyright Chris Corbyn <chris@w3style.co.uk>
 * @author Chris Corbyn <chris@w3style.co.uk>
 * @package Swift
 * @license GNU Lesser General Public License
 */

class CampaignMailer extends Swift_BatchMailer {

	// properties

	var $campaign = null; // prepared campaign array
	var $process = null; // if set, process to update after sending
	var $iterator = 'mysql'; // iterator to use

	var $action = 'test'; // what action is it performing (send, test, spamcheck (servers preview too), copy, etc)

	var $_campaignUpdater = array(); // what fields in #campaign to update in progress

	var $_orig_lang = null; // what was the original page language
	var $_orig_langtype = null; // what was the original page language type

	var $bounces = array(); // bounce(s)
	var $listheaders = array(); // list custom headers
	var $personalizations = array(); // sender personalizations
	var $mailers = array(); // mailers that will be used
	var $languages = array(); // used languages in lists

	var $messages = array(); // array of prepared message objects
	var $parts = array(); // array of prepared message part objects

	var $isSplit = false; // is split switch

	var $recipients = null; // recipients object
	var $it = null; // iterator object
	var $limiter = 0; // stop after this many

	// contructor
	function CampaignMailer($campaign = null, $process = null, $action = 'send') {
		// set action
		$this->action = $action;
		$this->_campaignUpdater = array(
			'=send_amt' => '`send_amt` + 1',
			'=ldate' => 'NOW()'
		);
		if ( $campaign ) $this->setCampaign($campaign);
		if ( $process ) $this->setProcess($process);
		$this->_orig_lang = ( isset($GLOBALS['__languageName']) ? $GLOBALS['__languageName'] : '' );
		$this->_orig_langtype = ( isset($GLOBALS['__languageType']) ? $GLOBALS['__languageType'] : '' );
		$this->setMaxTries(1);
		$this->recipients = new Swift_RecipientList();
		if ( $campaign ) {
			$GLOBALS['_swift_campaignid'] = $campaign['id'];
			$GLOBALS['_swift_action'] = $action;
		}
	}

	function setCampaign($campaign) {
		$this->campaign = $campaign;
		$this->isSplit = ( $campaign['type'] == 'split' and count($campaign['messages']) > 1 );
		// fetch list based info:
		// bounce, header, languages(?), mailers, attachments, personalizations
		$this->fetchCampaignInfo();
		// prepare campaign
		$this->prepareCampaign();
		// prepare swift mailer
		$this->initMailer();
	}

	function fetchCampaignInfo() {
		// fetch list based info:
		require_once(adesk_admin('functions/bounce_management.php'));
		require_once(adesk_admin('functions/header.php'));
		require_once(adesk_admin('functions/personalization.php'));
		// bounce, header, languages(?), mailers, personalizations
		$listslist = str_replace('-', ',', $this->campaign['listslist']);
		$listslist = str_replace(",", "', '", $listslist);
		$hso = new adesk_Select();
		$bso = new adesk_Select();
		$pso = new adesk_Select();
		$hso->push("AND l.listid IN ('$listslist')");
		if (!isset($GLOBALS["_hosted_account"]))
			$bso->push("AND l.listid IN ('$listslist')");
		$pso->push("AND l.listid IN ('$listslist')");
		// bounces
		$this->bounces = campaign_list_bounces($bso);
		// list headers
		$this->listheaders = campaign_list_headers($hso);
		// sender personalization tags
		$this->personalizations = list_personalizations($pso);
		// languages
		$this->languages = array();
		/* don't have list languages anymore
		foreach ( $this->campaign['lists'] as $l ) {
			$this->languages[$l['lang']] = $l['lang'];
		}
		*/
		// campaign mailers to use
		$GLOBALS['_adesk_mailer_connections'] = array();
		$query = "
			SELECT
				*,
				m.id AS id,
				c.id AS relid
			FROM
				#campaign_mailer c,
				#mailer m
			WHERE
				c.campaignid = '{$this->campaign['id']}'
			AND
				c.mailerid = m.id
			ORDER BY
				m.current DESC,
				m.corder ASC
		";
		$this->mailers =& adesk_mail_connections($query);
		if ( count($this->mailers) == 0 ) {
			// group mailers to use
			if ( !isset($this->campaign['userid']) ) {
				$admin = adesk_admin_get();
				$this->campaign['userid'] = $admin['id'];
			}
			$uid = (int)$this->campaign['userid'];
			$query = "
				SELECT
					m.*,
					0 AS campaignid,
					0 AS relid
				FROM
					#user_group u,
					#group_mailer g,
					#mailer m
				WHERE
					u.userid = '$uid'
				AND
					u.groupid = g.groupid
				AND
					g.mailerid = m.id
				ORDER BY
					m.current DESC,
					m.corder ASC
			";
			$this->mailers =& adesk_mail_connections($query);
			if ( count($this->mailers) == 0 ) {
				// group mailers to use
				if ( !isset($this->campaign['userid']) || $this->campaign['userid']=='0' ) {
					$admin = adesk_admin_get();
					$this->campaign['userid'] = $admin['id'];
				}
				$uid = (int)$this->campaign['userid'];
				$query = user_get_mail_conns_query($uid);
				$this->mailers =& adesk_mail_connections($query);
				if ( count($this->mailers) == 0 ) {
					// default (system) mailers to use
					$this->mailers =& adesk_mail_connections();
				}
			}
		}
		if ( !isset($this->campaign['fields']) ) {
			// fetch all custom fields that will be used
			$this->campaign['fields'] = list_get_fields(explode('-', $this->campaign['listslist']), true); // grab all custom fields
		}
		// prepare campaign?
		// MODIFY MESSAGE IF NEEDED (FETCH@SEND)
		// prepare swift mailer
		// prepare swift mailer message(s) (and attachments...)
	}

	function prepareCampaign() {
		// prepare campaign
	}

	function initMailer() {

		// disable backtracking in our trapperr
		if ( !defined('adesk_TRAPPERR_NOBACKTRACE') ) define('adesk_TRAPPERR_NOBACKTRACE', 1);
		if ( !defined('adesk_TRAPPERR_NOVARS'     ) ) define('adesk_TRAPPERR_NOVARS'     , 1);
		// set our trapperr to die on user errors (trigger_error() calls)
		$GLOBALS['_CONFIG']['trapperr']['user_error_is_deadly'] = 1;

		//////////////////////////////////////////////////////////////////////////////////////////////////
		// INSTANTIATE SWIFT MAILER

		// fetch all available connections
		//$connections =& adesk_mail_connections();
		$connections =& $this->mailers;
		// extract the first connection that will be used
		$curr = key($connections);
		if ( is_null($curr) or !isset($connections[$curr]) ) $curr = 0;
		$connection =& $connections[$curr];
		$mailer =& $connection->_info;
		if ( count($connections) == 1 ) {
			// if only one connection is used, no need for rotator then
			$this->swift = new Swift($connection);
			if ( isset($mailer['dotfix']) ) {
				$encoder =& SendingEngineEncoder::instance();
				$encoder->setDotFix($mailer['dotfix']);
			}
		} else {
			// set connection rotator while loading Swift
			$rotator = new SendingEngineRotator($batch, $connections);
			$this->swift = new Swift($rotator);
			// set unique threshold value (minumum set)
			$rotatorPlugin =& $this->swift->getPlugin('_ROTATOR');
			//$rotatorPlugin->setThreshold($GLOBALS['_adesk_mailer_rotator_threshold']);
			$rotatorPlugin->setThreshold($mailer['threshold']);
			if ( $mailer['threshold'] ) $rotatorPlugin->count = $mailer['sent'] % $mailer['threshold'];
		}

		// Set image embedder
		if ( $this->campaign['embed_images'] and !in_array($this->action, array('preview'/*, 'messagesize'*/)) ) {
			$extensions = array(
				0 => 'gif',
				1 => 'jpg',
				2 => 'jpeg',
				3 => 'pjpeg',
				4 => 'png'
			);
			$tags = array(
				'body' => 'background',
				'table' => 'background',
				'td' => 'background',
				'th' => 'background',
			);
			$embedder = new SendingEngineEmbedder($extensions, $tags);
			//$embedder->mimeTypes['php'] = 'image/gif';
			// attach plugin
			$this->swift->attachPlugin($embedder, 'embedder');
		}

		// Load 2 decorator plugins with the extended replacements class
		$personalizator = new Swift_Plugin_Decorator_Replacements();

		// predecorator will save the progress, since it is always the first plugin
		// it's sendPerformed() therefore runs the first after the real send
		$predecorator = new SendingEngineDecorator($this, $personalizator, true);
		$this->swift->attachPlugin($predecorator, 'predecorator');

		$decorator = new SendingEngineDecorator($this, $personalizator, false);
		$this->swift->attachPlugin($decorator, 'decorator');

		if ( $mailer['frequency'] and $mailer['pause'] ) $mailer['limit'] = 0;

		// Set the number of messages per minute limit
		$epm = (int)$mailer['limit'];
		// setting can be either per day or hour
		$throttler = new SendingEngineThrottler($this);
		if ( $epm ) {
			// conv days into hours
			if ( $mailer['limitspan'] == 'day' ) $epm /= 24;
			// conv hours into minutes
			$epm /= 60;
			// this many sent already (counted per second)
			//$throttler->setSent((int)( $mailer['sent'] % ( $epm / 60 ) ));
			$throttler->setSent(0);
		}
		$throttler->setTime();
		// if any limit is given
		$throttler->setEmailsPerMinute($epm); // max of X emails in a minute
		$this->swift->attachPlugin($throttler, 'throttler');

		// Load the plugin to deal with email checks and pausing (extends AntiFlood!!!)
		// this one should be loaded the last, as it performs all double-checks in beforeSendPerformed
		$sendHandler = new SendingEngineHandler($this, $mailer['frequency'], $mailer['pause']);
		if ( $mailer['frequency'] ) $sendHandler->count = $mailer['sent'] % $mailer['frequency'];
		$this->swift->attachPlugin($sendHandler, 'anti-flood');
	}

	function &getMessage($id) {
		// is there an already prepared message?
		if ( isset($this->messages[$id]) ) {
			// return it
			return $this->messages[$id];
		}
		// set this requested one
		$this->messages[$id] = $this->setMessage($id);
		// return it
		return $this->messages[$id];
	}

	function setMessage($id) {
		// get message data, prepare it, and return a swift message object
		return ( $messageData =& $this->getMessageData($id) ) ? $this->prepareMessage($messageData) : null;
	}

	function &getMessageData($id) {
		// find the message
		foreach ( $this->campaign['messages'] as $k => $v ) {
			// message found
			if ( $id == $v['id'] ) {
				return $v;
			}
		}
		$r = null;
		return $r;
	}

	function prepareMessage($row) {
		global $site;

		// try to fetch a message from archive (if exists)
		$cached = adesk_sql_select_row("SELECT * FROM #message_archive WHERE `campaignid` = '{$this->campaign['id']}' AND `messageid` = '$row[id]'");
		if ( $cached ) {
			$row = array_merge($row, $cached);
			$GLOBALS['deskrss_items_found'] = $row['rssitemshtml'] = $row['rssitemstext'] = 1;
		} else {
			// prepare swift mailer message(s) (and attachments...)
			// MODIFY MESSAGE IF NEEDED (FETCH@SEND)

			// figure out message settings
			if ( $row['format'] != 'text' and $row['format'] != 'html' ) $row['format'] = 'mime';

			# Always do QP.
			$row['encoding'] = _i18n("quoted-printable");

			if ( $row['charset'] == '' ) $row['charset'] = _i18n("utf-8");
			if ( !$row['priority'] ) $row['priority'] = 3; // default
			if ( $row['fromname'] == '' or $row['fromname'] == $row['fromemail'] ) $row['fromname'] = null; // default
			if ( $row['reply2'] == $row['fromemail'] ) $row['reply2'] = ''; // default

			// prepare and cleanup html version
			if ( $row['format'] != 'text' ) {
				$row = $this->prepareHTML($row);
			}
			$row['rssitemshtml'] = $GLOBALS['deskrss_items_found'];
			// prepare and cleanup text version
			if ( $row['format'] != 'html' ) {
				$row = $this->prepareTEXT($row);
			}
			$row['rssitemstext'] = $GLOBALS['deskrss_items_found'];
			$GLOBALS['deskrss_items_found'] = $row['rssitemshtml'] + $row['rssitemstext'];

			// if real send, and found some rss items, store into archive
			if ( $this->campaign['id'] > 0 ) {
				if ( $this->action == 'send' ) {
					if ( $GLOBALS['deskrss_items_found'] ) {
						$insert = array(
							'id' => 0,
							'campaignid' => $this->campaign['id'],
							'messageid' => $row['id'],
							'fromname' => $row['fromname'],
							'fromemail' => $row['fromemail'],
							'reply2' => $row['reply2'],
							'priority' => $row['priority'],
							'charset' => $row['charset'],
							'encoding' => $row['encoding'],
							'subject' => $row['subject'],
							'text' => $row['text'],
							'html' => $row['html'],
						);
						adesk_sql_insert('#message_archive', $insert);
					}
				}
			}
		}

		if (strtoupper($row['charset']) != "UTF-8")
			$row = adesk_utf_deepconv("UTF-8", $row['charset'], $row);

		if ( isset($GLOBALS['_hosted_account']) ) {
			$row['subject'] = str_replace('"', '', $row['subject']);
		}

		// Create the message
		$message = new Swift_Message($row['subject'], null, "text/plain", $row['encoding'], $row['charset']);

		$txtPart = false;
		$htmPart = false;
		// if mime message
		if ( $row['format'] == 'mime' ) {
			// Add both parts
			$txtMsg = new Swift_Message_Part($row['text'], 'text/plain', '', $row['charset']);
			$txtMsg->setLineWrap(1000000000);
			$txtPart = $message->attach($txtMsg);
			$htmMsg = new Swift_Message_Part($row['html'], 'text/html', $row['encoding'], $row['charset']);
			$htmPart = $message->attach($htmMsg);
		} elseif ( $row['format'] == 'html' ) {
			$htmMsg = new Swift_Message_Part($row['html'], 'text/html', $row['encoding'], $row['charset']);
			$htmPart = $message->attach($htmMsg);
		} else/*if ( $row['format'] == 'text' )*/ {
			$type = 'text';
			$txtMsg = new Swift_Message_Part($row['text'], 'text/plain', '', $row['charset']);
			$txtMsg->setLineWrap(1000000000);
			$txtPart = $message->attach($txtMsg);
			//$message->setBody($row['text']);
		}
		$this->parts[$row['id']] = array('html' => $htmPart, 'text' => $txtPart);

		// set CHARSET/ENCODING
		$message->setEncoding($row['encoding']);
		$message->setCharset($row['charset']);
		$message->headers->setCharset($row['charset']);
		$message->headers->setEncoding($row['encoding']);

		// set PRIORITY
		$message->setPriority((int)$row['priority']);

		// set BOUNCE SETTINGS (return-path)
		// assigned later in run()!

		// set FROM
		$message->_from = new Swift_Address($row['fromemail'], $row['fromname']);
		$message->setFrom($message->_from);

		// set REPLY-TO field
		if ( $row['reply2'] != '' ) $message->setReplyTo($row['reply2']);

		// add attachments
		$attachments = message_attachments($row['files']);
		foreach ( $attachments as $file ) {
			$message->attach(new Swift_Message_Attachment($file['data'], $file['name'], $file['mime_type'], "base64"));
		}
		/*
		// add inline (image) attachments
		if ( isset($row['embed']) ) {
			foreach ( $row['embed'] as $file ) {
			    $att = new Swift_Message_EmbeddedFile($file['data'], $file['name'], $file['mime_type']);
			    $id = $message->attach($att);
			}
		}
		*/
		// set custom headers
		foreach ( $this->listheaders as $header ) {
			$message->headers->set($header['name'], personalization_basic($header['value'], ''));
		}

		$senderheader = false;
		if (isset($GLOBALS["_hosted_account"]))
			$senderheader = true;

		if ($senderheader && $site['onbehalfof'] && isset($GLOBALS["domain"])) {
			$info = $_SESSION[$GLOBALS["domain"]];
			$host = (string)adesk_sql_select_one("SELECT host FROM #mailer WHERE id = '1'");

			if (preg_match('/(astirx.com|acemserv.com|acems\d.com)$/', $host)) {
				$xra = 'Please report abuse at http://www.awebdesk.com/contact/?type=abuse';
				if ( $info['rsid'] ) $xra = 'Please report abuse by forwarding this entire message to abuse@acemserv.com';
				$h = sprintf('<%s@%s>', $info["account"], $host);
				if ( $row['fromname'] ) $h = '"' . trim($row['fromname']) . '" ' . $h;
 				$message->headers->set("Sender", $h);
 				$message->headers->set("X-Sender", $h);
				$message->headers->set("X-Report-Abuse", $xra);
			}
		}

		// set internal custom headers
		$message->headers->set('X-mid', '%X-MID%');
		if ( trim(_i18n('AEM')) != '' ) {
			$message->headers->set('X-Mailer', _i18n('AEM'));
			//$message->headers->set('User-Agent', _i18n('AEM'));
			if (!$senderheader)
				$message->headers->set('X-Sender', '<' . $row['fromemail'] . '>');
		}

		$unsubactions = array();

		if ( isset($GLOBALS['_hosted_account']) ) {
			$hosted_domain = isset($_SESSION[$GLOBALS['domain']]['account']) ? $_SESSION[$GLOBALS['domain']]['account'] : $GLOBALS['domain'];
			#$unsubactions[] = "mailto:unsubscribe@$hosted_domain";
			$unsubactions[] = "mailto:unsubscribe-cmpgnid-subscriberid@$hosted_domain";
		}


		$unsub = adesk_site_plink('surround.php?nl=currentnl&c=cmpgnid&m=currentmesg&s=subscriberid&funcml=unsub2');
		if ( adesk_str_instr($unsub, $row['html']) or adesk_str_instr($unsub, $row['text']) OR isset($GLOBALS['_hosted_account']) ) {
			$unsubactions[] = $unsub . '&luha=1';
		}
		if ( count($unsubactions) ) {
			$message->headers->set('List-Unsubscribe', "<" . implode(">, <", $unsubactions) . ">");
		}

		return $message;
	}

	function prepareHTML($row) {
		$origAdmin = adesk_admin_get();
		$admin = adesk_admin_get_totally_unsafe($this->campaign['userid']);
		$GLOBALS['admin'] = $origAdmin;
		$murl = adesk_site_plink('lt.php?nl=currentnl&c=cmpgnid&m=currentmesg&s=subscriberid&l=');
		$unsub = adesk_site_plink('surround.php?nl=currentnl&c=cmpgnid&m=currentmesg&s=subscriberid&funcml=unsub2');
		// fetch content if needed (fetch@send scenario)
		if ( $row['htmlfetchurl'] and $row['htmlfetch'] == 'send' ) {
			// do_basic_personalization
			$row['htmlfetchurl'] = personalization_basic($row['htmlfetchurl'], $row['subject']);
			$row['html'] = adesk_http_get($row['htmlfetchurl'], "UTF-8");
			$row['html'] = message_link_resolve($row['html'], $row['htmlfetchurl']);

			if (!$this->campaign["managetext"])
				$row["text"] = adesk_htmltext_convert($row["html"]);

			if ( $row['subject'] == '' ) {
				// try to find the title
				preg_match('/<title>(.*)<\/title>/i', $row['html'], $matches);
				if ( isset($matches[1]) ) $row['subject'] = $matches[1];
			}
		}
		// add header stuff
		$addOn = '';
		// branding header
		if ( $admin['brand_header_html'] ) {
			$addOn = $admin['brand_header_html_value'];
		}
		// whatever can be added, add it b4 closing BODY tag
		$row['html'] = adesk_str_prepend_html($row['html'], $addOn);
		// add footer stuff
		$addOn = '';
		$addPre = '';
		// if unsub set to be added
		if ( $this->campaign['htmlunsub'] == 1 && !isset($GLOBALS['_hosted_account']) ) {
			// and not in message body
			if ( !adesk_str_instr('%UNSUBSCRIBELINK%', $row['html']) and !adesk_str_instr($unsub, $row['html']) ) {
				if ( adesk_str_instr('%UNSUBSCRIBELINK%', $this->campaign['htmlunsubdata']) or adesk_str_instr($unsub, $this->campaign['htmlunsubdata']) ) {
					$addOn .= $this->campaign['htmlunsubdata'];
				} else {
					$addOn .= _a('<div><a href="%UNSUBSCRIBELINK%">Click here</a> to unsubscribe from future mailings.</div>');
				}
			}
		}
		// add our image tracker
		$messagetracked = adesk_sql_select_one("=COUNT(*)", "#link", "campaignid = '{$this->campaign['id']}' AND messageid = '$row[id]' AND link = 'open' AND tracked = 1");
		if ( $this->campaign['trackreads'] == 1 and $messagetracked ) {
			$imgtag = '<img src="' . $murl . 'open" border="0" />';
			if ( strlen($row['html']) > 100 * 1024 ) {
				$addPre .= $imgtag;
			} else {
				$addOn  .= $imgtag;
			}
		}
		// add analytics image tracker
		if ( $this->campaign['trackreadsanalytics'] == 1 ) {
			$url = message_read_analytics($this->campaign, $row);
			if ( $url ) {
				$imgtag = '<img src="' . $url . 'open" width="1" height="1" border="0" />';
				if ( strlen($row['html']) > 100 * 1024 ) {
					$addPre .= $imgtag;
				} else {
					$addOn  .= $imgtag;
				}
			}
		}
		// branding footer
		if ( $admin['brand_footer_html'] ) {
			$addOn .= '<br />' . $admin['brand_footer_html_value'];
		}

		$tmpcontent = $addPre . $row['html'] . $addOn;

		if ( isset($GLOBALS['__hosted_footer_html']) ) {
			$unsubLink = adesk_site_plink('surround.php?nl=currentnl&c=cmpgnid&m=currentmesg&s=subscriberid&funcml=unsub2');
			//$abuseLink = adesk_site_plink('index.php?action=abuse&nl=currentnl&c=cmpgnid&m=currentmesg&s=subscriberid');
			$hasUnsub = adesk_str_instr($unsubLink, $tmpcontent);
			//$hasAbuse = adesk_str_instr($abuseLink, $tmpcontent);
			$hasSender = adesk_str_instr('%SENDER-INFO%', $tmpcontent);
			if ( !$hasSender or !$hasUnsub ) {
				$addOn .= hosted_footer_personalize($GLOBALS['__hosted_footer_html']);
				$tmpcontent = $addPre . $row['html'] . $addOn;
			}
		}
		if ( user_requires_senderinfo($admin["id"]) ) {
			if ( !adesk_str_instr('%SENDER-INFO%', $tmpcontent) ) {
				$addOn .= '<br /><br />%SENDER-INFO%';
			}
		}
		//$addOn .= free_license_msg(true);
		// whatever can be added, add it b4 closing BODY tag
		$row['html'] = adesk_str_append_html($row['html'], $addOn);
		// if message is over 5MB, image tracker will be at the top
		$row['html'] = adesk_str_prepend_html($row['html'], $addPre, 0);
		// apply ActiveRSS
		$html = deskrss_parse($this->campaign, $row, true, true, $this->action);
		if ( $row['html'] != $html ) {
			$row['html'] = $html;
			// save them into campaign info
			$this->campaign['tlinks'] = campaign_links_get($this->campaign);
		}
		// apply sender personalization
		$row['html'] = personalization_apply($row['html'], $this->personalizations['html']);
		// do_basic_personalization
		$row['html'] = personalization_basic($row['html'], $row['subject']);
		// EMBED IMAGES
		//if ( $this->campaign['embed_images'] ) {
			// swiftmailer now does this (embedder plugin)
			//$row['embed'] = message_parse_images($row['html'], true);
		//}
		// PARSE LINKS
		if ( $this->campaign['tracklinks'] == 'all' or $this->campaign['tracklinks'] == 'mime' or $this->campaign['tracklinks'] == 'html' ) {
			$links = adesk_array_groupby($this->campaign['tlinks'], 'messageid');
			if ( isset($links[$row['id']]) ) {
				message_parse_links($row['html'], $links[$row['id']], 'html');
			}
		}
		// preliminary message cleanup
		if ( !isset($GLOBALS['disableStripHTMLtag']) ) {
			/*
			// remove opening HTML tags (upper/lower cased)
			$row['html'] = preg_replace('/<html.*?>/i', '', $row['html']);
			// remove closing HTML tags (upper/lower cased)
			$row['html'] = preg_replace('/<\/html>/i', '', $row['html']);
			*/
			// remove opening TBODY tags (upper/lower cased)
			$row['html'] = preg_replace('/<tbody.*?>/i', '', $row['html']);
			// remove closing TBODY tags (upper/lower cased)
			$row['html'] = preg_replace('/<\/tbody>/i', '', $row['html']);
		}
		// postliminary message cleanup :)
		$row['html'] = str_replace('<title></title>', '', $row['html']);
		$row['html'] = str_replace('&amp;', '&', $row['html']);
		$row['html'] = trim($row['html']);
		return $row;
	}

	function prepareTEXT($row) {
		$origAdmin = adesk_admin_get();
		$admin = adesk_admin_get_totally_unsafe($this->campaign['userid']);
		$GLOBALS['admin'] = $origAdmin;
		//$murl = adesk_site_plink();
		$unsub = adesk_site_plink('surround.php?nl=currentnl&c=cmpgnid&m=currentmesg&s=subscriberid&funcml=unsub2');
		// fetch content if needed (fetch@send scenario)
		if ( $row['textfetchurl'] and $row['textfetch'] == 'send' ) {
			// do_basic_personalization
			$row['textfetchurl'] = personalization_basic($row['textfetchurl'], $row['subject']);
			$row['text'] = adesk_http_get($row['textfetchurl'], "UTF-8");
		}
		// add header stuff
		$addOn = '';
		// branding header
		if ( $admin['brand_header_text'] ) {
			$addOn .= $admin['brand_header_text_value'] . "\n";
		}
		// whatever can be added, add it b4 closing BODY tag
		$row['text'] = adesk_str_prepend_text($row['text'], $addOn);
		// add footer stuff
		$addOn = '';
		// if unsub set to be added
		if ( $this->campaign['textunsub'] == 1 && !isset($GLOBALS['_hosted_account']) ) {
			// and not in message body
			if ( !adesk_str_instr('%UNSUBSCRIBELINK%', $row['text']) and !adesk_str_instr($unsub, $row['text']) ) {
				if ( adesk_str_instr('%UNSUBSCRIBELINK%', $this->campaign['textunsubdata']) or adesk_str_instr($unsub, $this->campaign['textunsubdata']) ) {
					$addOn .= $this->campaign['textunsubdata'];
				} else {
					$addOn .= _a('Click here to unsubscribe from future mailings: %UNSUBSCRIBELINK%');
				}
			}
		}
		// branding footer
		if ( $admin['brand_footer_text'] ) {
			$addOn .= "\n" . $admin['brand_footer_text_value'];
		}

		$tmpcontent = $row['text'] . $addOn;

		if ( isset($GLOBALS['__hosted_footer_text']) ) {
			$unsubLink = adesk_site_plink('surround.php?nl=currentnl&c=cmpgnid&m=currentmesg&s=subscriberid&funcml=unsub2');
			//$abuseLink = adesk_site_plink('index.php?action=abuse&nl=currentnl&c=cmpgnid&m=currentmesg&s=subscriberid');
			$hasUnsub = adesk_str_instr($unsubLink, $tmpcontent);
			//$hasAbuse = adesk_str_instr($abuseLink, $tmpcontent);
			$hasSender = adesk_str_instr('%SENDER-INFO%', $tmpcontent);
			if ( !$hasSender or !$hasUnsub ) {
				$addOn .= hosted_footer_personalize($GLOBALS['__hosted_footer_text']);
				$tmpcontent = $row['text'] . $addOn;
			}
		}
		if ( user_requires_senderinfo($admin["id"]) ) {
			if ( !adesk_str_instr('%SENDER-INFO%', $tmpcontent) ) {
				$addOn .= "\n\n%SENDER-INFO%";
			}
		}
		//$addOn .= free_license_msg(false);
		// append additional content
		$row['text'] = adesk_str_append_text($row['text'], $addOn);
		// apply ActiveRSS
		$text = deskrss_parse($this->campaign, $row, false, true, $this->action);
		if ( $row['text'] != $text ) {
			$row['text'] = $text;
			// save them into campaign info
			$this->campaign['tlinks'] = campaign_links_get($this->campaign);
		}
		$row['text'] = adesk_str_clean_word($row['text'], false);
		// apply sender personalization
		$row['text'] = personalization_apply($row['text'], $this->personalizations['text']);
		// do_basic_personalization
		$row['text'] = personalization_basic($row['text'], $row['subject']);
		// PARSE LINKS
		if ( $this->campaign['tracklinks'] == 'all' or $this->campaign['tracklinks'] == 'mime' or $this->campaign['tracklinks'] == 'text' ) {
			$links = adesk_array_groupby($this->campaign['tlinks'], 'messageid');
			if ( isset($links[$row['id']]) ) {
				message_parse_links($row['text'], $links[$row['id']], 'text');
			}
		}
		$row['text'] = str_replace("\r", '', $row['text']);
		return $row;
	}

	function setProcess($process) {
		require_once(awebdesk_functions('process.php'));
		if ( isset($process['id']) ) $this->process = $process;
	}

	function update() {
		if ( $this->process and $this->action == 'send' ) {
			campaign_sender_log("Updating the process...");
			adesk_process_update($this->process['id']);
			$this->process['completed']++;
			campaign_sender_log("Process updated.");
		}
		if ( $this->campaign['id'] > 0 and count($this->_campaignUpdater) > 0 ) {
			// print out debugging: completed the subscriber update
			campaign_sender_log("Updating the campaign...");
			if ( !adesk_sql_update('#campaign', $this->_campaignUpdater, "`id` = '{$this->campaign['id']}'") ) {
				$err = '#' . adesk_sql_error_number() . ' - ' . adesk_sql_error();
				campaign_sender_log("\n\n[+] COULD NOT UPDATE CAMPAIGN!!!\n\nError $err\n\n\n\n");
				campaign_log_save($this->campaign['id'], $this->action);
				die('could not update campaign.');
			}
			if ( @adesk_sql_affected_rows($GLOBALS["db_link"]) == 0 ) {
				campaign_sender_log("\n\n[+] CAMPAIGN NOT UPDATED!!!\n\n\n\n");
				campaign_log_save($this->campaign['id'], $this->action);
				die('campaign not updated.');
			}
			if ( isset($this->_campaignUpdater['send_amt']) or isset($this->_campaignUpdater['=send_amt']) ) {
				$this->campaign['send_amt']++;
			}
			if ( isset($this->_campaignUpdater['total_amt']) or isset($this->_campaignUpdater['=total_amt']) ) {
				$this->campaign['total_amt']++;
			}
			campaign_update_splittotal($this->campaign["id"], 0);
			campaign_update_splitsend($this->campaign["id"], 0);
			campaign_sender_log("Campaign updated.");
		}
	}

	function pretend($email) {
		$decorator =& $this->swift->getPlugin('decorator');
		$subscriber = $decorator->replacements->getReplacementsFor($email);
		$eid = $subscriber['%PERS_ID%'];
		$tbl_id = $subscriber['%%PERS_TBLID%%'];
		$msg_id = $subscriber['%MESSAGEID%'];
		$nl = $subscriber['currentnl'];

		if ( !adesk_sql_update_one('#x' . $this->campaign['sendid'], 'sent', 1, "id = '$tbl_id'") ) {
			$err = '#' . adesk_sql_error_number() . ' - ' . adesk_sql_error();
			campaign_sender_log("\n\n[+] COULD NOT UPDATE SUBSCRIBER $eid (#$tbl_id) !!!\n\nError $err\n\n\n\n");
			campaign_log_save($this->campaign['id'], $this->action);
			die("update subscriber failed ($tbl_id): $err");
		}
		if ( @adesk_sql_affected_rows($GLOBALS["db_link"]) == 0 ) {
			campaign_sender_log("\n\n[+] DID NOT UPDATE SUBSCRIBER $eid (#$tbl_id) !!!\n\nError $err\n\n\n\n");
			campaign_log_save($this->batch->campaign['id'], $this->batch->action);
			die("did not update subscriber $k");
		}

		$this->update();

		if ( $eid > 0 ) {
			// if responder or reminder
			if ( in_array($this->campaign['type'], array('responder', 'reminder')) ) {
				// add this responder to this subscriber (save it)
				//subscriber_responder_log($eid, $nl, $this->batch->campaign['id'], $msg_id);
				subscriber_responder_log($eid, $this->campaign['listslist'], $this->campaign['id'], $msg_id);
			} elseif ( $this->campaign['type'] == 'special' and $this->campaign['realcid'] ) {
				// add REAL responder to this subscriber (save it)
				//subscriber_responder_log($eid, $nl, $this->batch->campaign['realcid'], $msg_id);
				subscriber_responder_log($eid, $this->campaign['listslist'], $this->campaign['realcid'], $msg_id);
			}
			// log message only if failed
			$cid = ( $this->campaign['realcid'] ? $this->campaign['realcid'] : $this->campaign['id'] );
			// save to database log
			campaign_database_log($cid, $msg_id, $eid, 1, 'sent');
			$logged = true;
		}
	}

	function setIterator($iterator) {
		// check if valid
		if ( !in_array($iterator, array('array', 'mysql')) ) return false;
		// set the iterator switch
		$this->iterator = $iterator;
		// initialize the object
		$this->it = new SendingEngineIterator(null, $iterator, true, 'email', 'name');
		// assign it to recipients object
		$this->recipients->setIterator($this->it, 'to'); //or cc, bcc
	}



	function setPersonalizations($subscriber) {
		// get the personalization array (pers tag => value)
		$personalizations = subscriber_personalize_get($subscriber, $this->campaign);
		// assign it to both predecorator and (post)decorator
		$predecorator =& $this->swift->getPlugin('predecorator');
		if ( $predecorator ) {
			$predecorator->replacements->setReplacements(array(trim($subscriber['email']) => $personalizations));
		}
		$decorator =& $this->swift->getPlugin('decorator');
		if ( $decorator ) {
			$decorator->replacements->setReplacements(array(trim($subscriber['email']) => $personalizations));
		}
	}



	/*
		SWITCH LANGUAGES IF NEEDED
	*/
	function switchLanguage($nl) {
		$langs = adesk_lang_choices();
		// if list language exists in app
		if ( !isset($this->languages[$nl]) or !isset($langs[$this->languages[$nl]]) ) return;
		// if current language different than main language (or language not set yet)
		if ( !isset($GLOBALS['__languageName']) or $GLOBALS['__languageName'] != $this->languages[$nl] or $GLOBALS['__languageType'] != 'public' ) {
			// load the language strings
			adesk_lang_load(adesk_lang_file($this->languages[$nl], 'public'), true);
		}
	}

	function restoreErrorHandler() {
		restore_error_handler();
		//if ( in_array($this->action, array('send', 'test', 'copy')) ) {
			$GLOBALS['_CONFIG']['trapperr']['user_error_is_deadly'] = 0;
		//}
	}




	// original Swift_BatchMailer copy, modified "wrapper"
	/**
	 * Run a batch send in a fail-safe manner.
	 * This operates as Swift::batchSend() except it deals with errors itself.
	 * @param Swift_Message To send
	 * @param Swift_RecipientList Recipients (To: only)
	 * @param Swift_Address The sender's address
	 * @return int The number sent to
	 */
	function run($recipients, $action = 'send') {
		// set action
		$this->action = $action;
		// push recipients to iterator
		$this->it->set($recipients);
		$i = 0;
		$sent = 0;
		$str = '';
		$successive_fails = 0;
		$breakedOut = false;
		$log =& Swift_LogContainer::getLog();


		// set batch's error handler that can set it to restart the connection
		set_error_handler(array(&$this, "handleError"));

		$it = $this->recipients->getIterator("to");
		while ($it->hasNext())
		{
			//if($this->process){if(isset($GLOBALS['asdf1234']))dbg('stop');else $GLOBALS['asdf1234']=1;}// stop on second (every time script runs, increment a counter)
			//if($this->process)dbg('stop');// stop on first
			$it->next();
			$recipient = $it->getValue();
			/* AwebDesk*/
			$i++;
			// clear out log
			campaign_log_save($this->campaign, $this->action);
			// figure out subscriber
			$subscriber = $it->currentRow;
			$tbl_id = $subscriber['id'];
			$eid = $subscriber['subscriberid'];
			$nl = $subscriber['listid'];
			campaign_sender_log("\n\nPREPARING AN EMAIL FOR SUBSCRIBER $subscriber[email] ($tbl_id=$eid@$nl):");
			// figure out list (languages, personalizations)
			$this->switchLanguage($nl);
			$message =& $this->getMessage($subscriber['messageid']);
			// deskrss check
			if ( $this->campaign['type'] == 'deskrss' ) {
				if ( !plugin_deskrss() ) {
					// stop this mailing
					$GLOBALS['stopTHISmailing'] = 1;
					campaign_sender_log("\n\n[+] ACTIVERSS CAMPAIGNS NOT ALLOWED!!!\n\n\n\n");
				}
				// if we found 0 items (total! in both html and text, that is)
				if ( $GLOBALS['deskrss_items_found'] == 0 ) {
					// stop this mailing
					$GLOBALS['stopTHISmailing'] = 1;
					campaign_sender_log("\n\n[+] THERE ARE NO NEW RSS FEEDS !!!\n\n\n\n");
				}
				$found = $GLOBALS['deskrss_items_found'] / 2;
				campaign_sender_log("An RSS Triggered Campaign will be sent with $found RSS item(s).");
			}
			$from = $message->_from;
			// set BOUNCE SETTINGS (return-path)
			if ( $this->campaign['bounceid'] != 0 and count($this->bounces) > 0 ) {
				if ( $this->campaign['bounceid'] == -1 ) {
					// assign random one
					$rand = array_rand($this->bounces);
					$return_path = $this->bounces[$rand]['email'];
				} else {
					// find the specific one
					$return_path = '';
					foreach ( $this->bounces as $k => $v ) {
						if ( $v['id'] == $this->campaign['bounceid'] ) {
							$return_path = $v['email'];
							break;
						}
					}
				}
				if ( $return_path != '' and $return_path != $from->getAddress() ) {
					# If we're hosted, we should append the campaignid.  There's one exception:
					# if someone is still using the old bounce-account@AEM.com address, then
					# leave that be.
					if (isset($GLOBALS["_hosted_account"]) && strpos($return_path, "@AEM.com") === false) {
						$message->setReturnPath(str_replace("@", "-" . $this->campaign["id"] . "@", $return_path));
					} else {
						$message->setReturnPath($return_path);
					}
				}
			}
			// fetch all personalization tags for this subscriber
			$this->setPersonalizations($subscriber);

			/* AwebDesk*/
			$loop = true;
			$tries = 0;
			while ($loop && $tries < $this->getMaxTries())
			{
				$tries++;
				$loop = false;
				$this->copyMessageHeaders($message);
				/* AwebDesk*/
				// our "get message source" switch
				//$this->swift->getsource = ( $this->action == 'spamcheck' );
				$this->swift->getsource = ( in_array($this->action, array('spamcheck', 'preview', 'messagesize', 'source')) );
				// before it calls the send method, we should check if mailing has stopped
				if ( !isset($GLOBALS['stopTHISmailing']) ) $GLOBALS['stopTHISmailing'] = 0;
				if ( $GLOBALS['stopTHISmailing'] ) {
					$GLOBALS['stopTHISmailing'] = 0;
					campaign_log_save($this->campaign, $this->action);
					$this->restoreErrorHandler();
					return ( $this->swift->getsource ? $str : $sent );
				}
				if ( $this->swift->getsource ) {
					if (!isset($GLOBALS["_hosted_account"])) {
						if (!subscriber_delayed($recipient->getAddress())) {
							$str .= ($n = $this->swift->send($message, $recipient, $from));
							$sent++;
						} else {
							$sent++;
							$n = 1;
							$this->pretend($recipient->getAddress());
						}
					} else {
						$str .= ($n = $this->swift->send($message, $recipient, $from));
						$sent++;
					}
				} else {
					// original "send!" function
					if (!isset($GLOBALS["_hosted_account"])) {
						if (!subscriber_delayed($recipient->getAddress()))
							$sent += ($n = $this->swift->send($message, $recipient, $from));
						else {
							$sent += ($n = 1);
							$this->pretend($recipient->getAddress());
						}
					} else {
						$sent += ($n = $this->swift->send($message, $recipient, $from));
					}
				}
				/* AwebDesk*/
				if (!$n) $this->addFailedRecipient($recipient->getAddress());
				if ($this->doRestart)
				{
					$successive_fails++;
					$this->restoreMessageHeaders($message);
					if (($max = $this->getMaxSuccessiveFailures()) && $successive_fails > $max)
					{
						campaign_log_save($this->campaign, $this->action);
						$this->restoreErrorHandler();
						Swift_Errors::trigger(
							new Swift_Exception(
								"Too many successive failures. BatchMailer is configured to allow no more than " . $max .
								" successive failures."
							)
						);
						return 0;
					}
					$loop = true;
					//Give it one more shot
					if ($t = $this->getSleepTime()) sleep($t);
					$this->forceRestartSwift();
				}
				else
				{
					$successive_fails = 0;
				}
			}
			if ( $this->limiter != 0 and $this->limiter <= $i ) {
				$breakedOut = true;
				break(1);
			}
			//if($this->process)dbg('stop'); // stop after first
		}
		$this->restoreErrorHandler();

		// old "if completed" switch
		if ( (!$breakedOut or $it->numRows == $i) and !( isset($GLOBALS['stopTHISmailing']) and $GLOBALS['stopTHISmailing'] ) ) {
			$GLOBALS['completed'] = 1;
		}

		// if current language different than main language
		if ( !isset($GLOBALS['__languageName']) or $GLOBALS['__languageName'] != $this->_orig_lang or $GLOBALS['__languageType'] != $this->_orig_langtype ) {
			// load the language strings
			adesk_lang_load(adesk_lang_file($this->_orig_lang, $this->_orig_langtype), true);
		}
		campaign_log_save($this->campaign, $this->action);
		return ( $this->swift->getsource ? $str : $sent );
	}
}


?>
