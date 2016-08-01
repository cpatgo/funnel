<?php

require_once dirname(__FILE__) . "/base.php";
require_once dirname(__FILE__) . "/log.php";
require_once dirname(__FILE__) . "/mime.php";

define("adesk_SPAM_BODY", "b");
define("adesk_SPAM_SUBJECT", "s");
define("adesk_SPAM_META", "m");

$adesk_spam_delims = array(
	'`', '~', '!', '@', '#',
	'^', '&', '*', '(', ')',
	'-', '_', '=', '+', '\\',
	'|', ']', '}', '[', '{',
	'\'', '"', ';', ':', '/',
	'?', '.', '>', ',', '<',
);

$adesk_spam_ignore = array(
	"a",
	"and",
	"are",
	"as",
	"at",
	"be",
	"by",
	"can",
	"have",
	"i",
	"in",
	"is",
	"nbsp",
	"of",
	"on",
	"or",
	"our",
	"that",
	"the",
	"this",
	"to",
	"will",
	"your",
	"A",
	"And",
	"Are",
	"As",
	"At",
	"Be",
	"By",
	"Can",
	"Have",
	"I",
	"In",
	"Is",
	"Of",
	"On",
	"Or",
	"Our",
	"That",
	"The",
	"This",
	"To",
	"Will",
	"Your",
);

$adesk_spam_dbs = array();
$adesk_spam_dbh = array();

$adesk_spam_ins = array();
$adesk_spam_inh = array();
$adesk_spam_ups = array();
$adesk_spam_uph = array();

$adesk_spam_ns = 1;
$adesk_spam_nh = 1;

function adesk_spam_cache() {
	# Cache the number of spam and ham messages.  Normally run when we open the database, this
	# function may need to be re-run if we add new words and re-use the database connection.
	$GLOBALS['adesk_spam_ns'] = max((int)adesk_sql_select_one("SELECT hits FROM #spam_s WHERE word = 'countm'"), 1);
	$GLOBALS['adesk_spam_nh'] = max((int)adesk_sql_select_one("SELECT hits FROM #spam_h WHERE word = 'countm'"), 1);
}

function adesk_spam_upcount_s() {
	$GLOBALS["adesk_spam_ns"]++;
	adesk_sql_query("UPDATE #spam_s SET hits = hits + 1 WHERE word = 'countm'");
}

function adesk_spam_upcount_h() {
	$GLOBALS["adesk_spam_nh"]++;
	adesk_sql_query("UPDATE #spam_h SET hits = hits + 1 WHERE word = 'countm'");
}

function adesk_spam_downcount_s() {
	$GLOBALS["adesk_spam_ns"]--;
	adesk_sql_query("UPDATE #spam_s SET hits = hits - 1 WHERE word = 'countm' AND hits > 0");
}

function adesk_spam_downcount_h() {
	$GLOBALS["adesk_spam_nh"]--;
	adesk_sql_query("UPDATE #spam_h SET hits = hits - 1 WHERE word = 'countm' AND hits > 0");
}

function adesk_spam_record_s($assets, $word) {
	global $adesk_spam_dbs;
	if (isset($adesk_spam_dbs[$word . $assets]))
		return $adesk_spam_dbs[$word . $assets];
	else
		return false;
}

function adesk_spam_record_h($assets, $word) {
	global $adesk_spam_dbh;
	if (isset($adesk_spam_dbh[$word . $assets]))
		return $adesk_spam_dbh[$word . $assets];
	else
		return false;
}

function adesk_spam_learn_s($assets, $word) {
	global $adesk_spam_ups;
	global $adesk_spam_ns;
	$record = adesk_spam_record_s($assets, $word);

	if ($record === false) {
		adesk_sql_query("
			INSERT INTO #spam_s (word, hits) VALUES ('{$word}{$assets}', '1')
		");
		$id = adesk_sql_insert_id();
	} else {
		if (!isset($adesk_spam_ups[$word . $assets]))
			$adesk_spam_ups[$word . $assets] = 1;
		else
			$adesk_spam_ups[$word . $assets]++;
	}
}

function adesk_spam_learn_h($assets, $word) {
	global $adesk_spam_dbh;
	global $adesk_spam_uph;
	global $adesk_spam_nh;
	$record = adesk_spam_record_h($assets, $word);

	if ($record === false) {
		adesk_sql_query("
			INSERT INTO #spam_h (word, hits) VALUES ('{$word}{$assets}', '1')
		");
		$id = adesk_sql_insert_id();
	} else {
		if (!isset($adesk_spam_uph[$word . $assets]))
			$adesk_spam_uph[$word . $assets] = 1;
		else
			$adesk_spam_uph[$word . $assets]++;
	}
}

function adesk_spam_unlearn_s($assets, $word) {
	$record = adesk_spam_record_s($assets, $word);
	$combo  = adesk_sql_escape($word . $assets);

	if ($record === false)
		return;

	if ($record == 1) {
		adesk_sql_query("DELETE FROM #spam_s WHERE word = '$combo'");
	} else {
		adesk_sql_query("UPDATE #spam_s SET hits = hits - 1 WHERE word = '$combo'");
	}

	adesk_spam_learn_h($assets, $word);
}

function adesk_spam_unlearn_h($assets, $word) {
	$record = adesk_spam_record_h($assets, $word);
	$combo  = adesk_sql_escape($word . $assets);

	if ($record === false)
		return;

	if ($record == 1) {
		adesk_sql_query("DELETE FROM #spam_h WHERE word = '$combo'");
	} else {
		adesk_sql_query("UPDATE #spam_h SET hits = hits - 1 WHERE word = '$combo'");
	}

	adesk_spam_learn_s($assets, $word);
}

function adesk_spam_update_s() {
	global $adesk_spam_ups;
	adesk_sql_query("ALTER TABLE #spam_s DISABLE KEYS");
	foreach ($adesk_spam_ups as $word => $hits) {
		$b = microtime(true);
		$word = adesk_sql_escape($word);
		$hits = (int)$hits;
		adesk_sql_query("UPDATE #spam_s SET hits = hits + $hits WHERE BINARY word = '$word'");
	}
	adesk_sql_query("ALTER TABLE #spam_s ENABLE KEYS");

	$adesk_spam_ups = array();
}

function adesk_spam_update_h() {
	global $adesk_spam_uph;
	adesk_sql_query("ALTER TABLE #spam_h DISABLE KEYS");
	foreach ($adesk_spam_uph as $word => $hits) {
		$word = adesk_sql_escape($word);
		$hits = (int)$hits;
		adesk_sql_query("UPDATE #spam_h SET hits = hits + $hits WHERE BINARY word = '$word'");
	}
	adesk_sql_query("ALTER TABLE #spam_h ENABLE KEYS");

	$adesk_spam_uph = array();
}

function adesk_spam_prefetch_s($assets, $words) {
	global $adesk_spam_dbs;

	if (is_string($words))
		$words = adesk_spam_words($words);

	for ($i = 0, $len = count($words); $i < $len; $i += 20) {
		if (($i + 20) < $len)
			$ary = array_slice($words, $i, 20);
		else
			$ary = array_slice($words, $i);

		for ($j = 0; $j < count($ary); $j++) {
			$ary[$j] .= $assets;
			if (isset($adesk_spam_dbs[$ary[$j]]))
				unset($ary[$j]);
			else
				$ary[$j]  = adesk_sql_escape($ary[$j]);
		}

		$str = implode("','", $ary);

		$rs  = adesk_sql_query("
			SELECT word, hits FROM #spam_s WHERE word IN ('$str')
		");

		while ($row = adesk_sql_fetch_assoc($rs))
			$adesk_spam_dbs[$row["word"]] = $row["hits"];
	}
}

function adesk_spam_prefetch_h($assets, $words) {
	global $adesk_spam_dbh;

	if (is_string($words))
		$words = adesk_spam_words($words);

	for ($i = 0, $len = count($words); $i < $len; $i += 20) {
		if (($i + 20) < $len)
			$ary = array_slice($words, $i, 20);
		else
			$ary = array_slice($words, $i);

		for ($j = 0; $j < count($ary); $j++) {
			$ary[$j] .= $assets;

			if (isset($adesk_spam_dbh[$ary[$j]]))
				unset($ary[$j]);
			else
				$ary[$j]  = adesk_sql_escape($ary[$j]);
		}

		$str = implode("','", $ary);

		$rs  = adesk_sql_query("
			SELECT word, hits FROM #spam_h WHERE word IN ('$str')
		");

		while ($row = adesk_sql_fetch_assoc($rs))
			$adesk_spam_dbh[$row["word"]] = $row["hits"];
	}
}

function adesk_spam_word_s($assets, $word) {
	global $adesk_spam_dbs;
	global $adesk_spam_ns;
	if (isset($adesk_spam_dbs[$word . $assets]))
		return (float)$adesk_spam_dbs[$word . $assets] / (float)$adesk_spam_ns;
	else
		return 0.0;
}

function adesk_spam_word_h($assets, $word) {
	global $adesk_spam_dbh;
	global $adesk_spam_nh;
	if (isset($adesk_spam_dbh[$word . $assets]))
		return (float)$adesk_spam_dbh[$word . $assets] / (float)$adesk_spam_nh;
	else
		return 0.0;
}

function adesk_spam_message_s($assets, $word) {
	# What follows is a basic adaptation of Paul Graham's seminal spam algorithm in his essay A
	# Plan for Spam (http://www.paulgraham.com/spam.html).
	$prh      = adesk_spam_word_h($assets, $word);
	$prs      = adesk_spam_word_s($assets, $word);

	if ($prh == $prs && $prh == 0.0)
		return 0.5;

	$g        = 2.0 * $prh;
	$b        = $prs;
	$ngood    = $GLOBALS['adesk_spam_nh'];
	$nbad     = $GLOBALS['adesk_spam_ns'];
	$dividend = min(1.0, $b / $nbad);
	$divisor  = min(1.0, $g / $ngood) + $dividend;
	$prob     = min(0.99, $dividend / $divisor);

	return max(0.01, $prob);
}

function adesk_spam_cb_oneminus($x) {
	return 1.0 - (float)$x;
}

function adesk_spam_words($message) {
	$words = str_replace($GLOBALS['adesk_spam_delims'], ' ', $message);
	$words = preg_replace('/\s+/m', ' ', $words);
	return explode(" ", trim($words));
}

function adesk_spam_probability($assets, $message) {
	global $adesk_spam_ignore;

	$words = adesk_spam_words($message);
	$probs = array();

	foreach ($words as $w) {
		# If the word is on the ignore list, we assume it's too common to really want to consider
		# it for our spam score.
		if (!isset($adesk_spam_ignore[$w]))
			$probs[] = adesk_spam_message_s($assets, $w);
	}

	if (count($probs) == 0)
		return 0.01;

	# prod  = a * b * c ... N
	for ($prod = $probs[0], $i = 1; $i < count($probs); $i++)
		$prod *= $probs[$i];

	# prodp = (1-a) * (1-b) * (1-c) ... (1-N)
	$probs = array_map('adesk_spam_cb_oneminus', $probs);
	for ($prodp = $probs[0], $i = 1; $i < count($probs); $i++)
		$prodp *= $probs[$i];

	return min(0.99, max(0.01, $prod / max(0.01, $prod + $prodp)));
}

function adesk_spam_mark_s($assets, $message) {
	$words = adesk_spam_words($message);

	foreach ($words as $word) {
		adesk_spam_learn_s($assets, $word);
	}
}

function adesk_spam_unmark_s($assets, $message) {
	$words = adesk_spam_words($message);

	foreach ($words as $word) {
		adesk_spam_unlearn_s($assets, $word);
	}
}

function adesk_spam_unmark_h($assets, $message) {
	$words = adesk_spam_words($message);

	foreach ($words as $word) {
		adesk_spam_unlearn_h($assets, $word);
	}
}

function adesk_spam_mark_h($assets, $message) {
	$words = adesk_spam_words($message);

	foreach ($words as $word) {
		adesk_spam_learn_h($assets, $word);
	}
}

function adesk_spam_email($email) {
	# First check our whitelist and blacklist.

	if (isset($email->headers["from"])) {
		$from   = adesk_mail_extract_recipients($email->headers["from"]);

		foreach ($from as $addr) {
			$tmp = explode("@", $addr);

			if (count($tmp) > 1) {
				$domain = adesk_sql_escape($tmp[1]);

				if ((int)adesk_sql_select_one("SELECT COUNT(*) FROM #spam_whitelist WHERE domain = '$domain'") > 0)
					return 0.1;

				if ((int)adesk_sql_select_one("SELECT COUNT(*) FROM #spam_blacklist WHERE domain = '$domain'") > 0)
					return 0.99;
			}
		}
	}

	# Process an email object like what would be produced by adesk_mail_extract().

	$probs = array(
		"body"    => 0.0,
		"subject" => 0.0,
	);

	if (isset($email->body)) {
		$body = adesk_str_strip_tags($email->body);
		adesk_spam_prefetch_s(adesk_SPAM_BODY, $body);
		adesk_spam_prefetch_h(adesk_SPAM_BODY, $body);
		$probs["body"] = adesk_spam_probability(adesk_SPAM_BODY, $body);
	}

	if (isset($email->headers["subject"])) {
		if ( $email->headers["subject"] ) {
			if ( is_array($email->headers["subject"]) ) $email->headers["subject"] = implode(' ', $email->headers["subject"]);
			adesk_spam_prefetch_s(adesk_SPAM_SUBJECT, $email->headers["subject"]);
			adesk_spam_prefetch_h(adesk_SPAM_SUBJECT, $email->headers["subject"]);
			$probs["subject"] = adesk_spam_probability(adesk_SPAM_SUBJECT, $email->headers["subject"]);
		} else {
			$probs["subject"] = .99;
		}
	}

	if (isset($GLOBALS["__log_spam"])) {
		$ins = array(
			"body"         => $probs["body"],
			"subject"      => $probs["subject"],
			"subject_text" => isset($email->headers["subject"]) ? $email->headers["subject"] : '',
			"headers"      => var_export($email->headers, true),
		);

		adesk_sql_insert("#logspam", $ins);
	}

	if ($probs["subject"] == 0.99 || $probs["body"] == 0.99)
		return 0.99;

	$rval  = $probs["body"] * 0.7;

	if ($rval <= 0.1)
		$rval += ($probs["subject"] * 0.3) * 2;
	else
		$rval += ($probs["subject"] * 0.3);

	$rval += adesk_spam_points($email);

	return min(0.99, $rval);
}

function adesk_spam_points($email) {
	$rval = 0.0;

	if (!isset($email->body) || trim($email->body) == "")
		$rval += 0.9;

	return $rval;
}

?>
