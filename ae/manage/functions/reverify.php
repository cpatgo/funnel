<?php

function reverify_ongoing() {
	return (int)adesk_sql_select_one("
		SELECT
			COUNT(*)
		FROM
			#process
		WHERE
			action = 'reverify'
		AND
			percentage < 100
	");
}

function reverify_finished() {
	return (int)adesk_sql_select_one("
		SELECT
			COUNT(*)
		FROM
			#process
		WHERE
			action = 'reverify'
		AND
			percentage = 100
	");
}

function reverify_percent() {
	return (int)adesk_sql_select_one("
		SELECT
			percentage
		FROM
			#process
		WHERE
			action = 'reverify'
		ORDER BY
			id DESC
		LIMIT
			1
	");
}

function reverify_count() {
	return (int)adesk_sql_select_one("
		SELECT
			COUNT(DISTINCT subscriberid)
		FROM
			#subscriber_list
		WHERE
			status = 0
	");
}

function reverify_process($process) {
	$offset  = $process["completed"];
	$message = $process["data"]["message"];
	$site    = adesk_site_get();
	$lists   = adesk_sql_select_box_array("SELECT id, name FROM #list");

	adesk_process_update($process["id"], false);

	while ($process["completed"] < $process["total"]) {
		$rs      = adesk_sql_query("
			SELECT
				l.subscriberid,
				l.listid,
				l.first_name,
				l.last_name,
				s.email,
				s.hash,
				(SELECT ll.name FROM #list ll WHERE ll.id = l.listid) AS listname
			FROM
				#subscriber_list l
			LEFT JOIN
				#subscriber s
			ON
				s.id = l.subscriberid
			WHERE
				l.status = 0
			LIMIT
				$offset, 100
		");

		if (!$rs || adesk_sql_num_rows($rs) == 0) {
			$process["completed"] = $process["total"];
		} else {
			while ($row = adesk_sql_fetch_assoc($rs)) {
				$fromname  = $_SESSION[$GLOBALS["domain"]]["fname"] . " " . $_SESSION[$GLOBALS["domain"]]["lname"];
				$fromemail = $_SESSION[$GLOBALS["domain"]]["email"];
				$subject   = sprintf(_a("%s List: Please Confirm Your Subscription"), $row["listname"]);
				$toemail   = $row["email"];
				$toname    = $row["first_name"] . " " . $row["last_name"];
				$body      = array();

				$body[0]   = sprintf("%s %s!\n\n", _a("Thank you for subscribing to"), $row["listname"]);
				$body[0]  .= sprintf("%s\n\n", _a("To confirm that you wish to be subscribed, please click the link below:"));
				$body[0]  .= sprintf("%s\n\n%s\n", "$site[p_link]/surround.php?nl=$row[listid]&p=0&s=$row[hash]&funcml=csub", $message);
				$body[1]   = "<div style=\"font-size: 12px; font-family: Arial, Helvetica;\">\n";
				$body[1]  .= sprintf("<strong>%s %s!</strong>\n", _a("Thank you for subscribing to"), $row["listname"]);
				$body[1]  .= "</div>\n\n";
				$body[1]  .= "<div style=\"padding: 15px; font-size: 12px; background: #F2FFD8; border: 3px solid #E4F4C3; margin-bottom: 0px; margin-top: 15px; font-family: Arial, Helvetica;\">\n";
				$body[1]  .= sprintf("%s<br /><br />\n", _a("To confirm that you wish to be subscribed, please click the link below:"));
				$body[1]  .= sprintf("<a href=\"%s\">%s</a>\n", "$site[p_link]/surround.php?nl=$row[listid]&p=0&s=$row[hash]&funcml=csub", nl2br($message));
				$body[1]  .= "</div>\n";

				adesk_mail_send("mime", $fromname, $fromemail, $body, $subject, $toemail, $toname);
				adesk_process_update($process["id"]);
				$process["completed"]++;
			}
		}
	}

	# We SHOULDN'T get here unless the following is true, but just to be sure...
	if ($process["completed"] == $process["total"]) {
		$id = $_SESSION[$GLOBALS["domain"]]["id"];
		adesk_sql_query("UPDATE _account.accounts SET down4 = 'nobody' WHERE id = '$id'");
	}
}

?>
