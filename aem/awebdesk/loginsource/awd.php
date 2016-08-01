<?php

$loginident = "AEM";
$loginvars = "host,dbname,user,pass";

class ACLoginSource extends adesk_LoginSource {

	function ACLoginSource($source) {
		$this->adesk_LoginSource($source);
	}

	function connect() {
		$source    = $this->source;
		$this->res = mysql_connect($source["host"], $source["user"], $source["pass"], true);
		mysql_select_db($source["dbname"], $this->res);
	}

	function authok($user, $pass) {
		$user = mysql_real_escape_string($user, $this->res);
		$pass = md5($pass);
		$rs   = mysql_query($q = "
			SELECT
				id
			FROM
				aweb_globalauth
			WHERE
				BINARY username = '$user'
			AND BINARY password = '$pass'
		", $this->res);

		if (mysql_num_rows($rs) === 1)
			return true;

		return false;
	}

	function info($user) {
		$user = mysql_real_escape_string($user, $this->res);
		$rs   = mysql_query("
			SELECT
				first_name,
				last_name,
				email
			FROM
				aweb_globalauth
			WHERE
				BINARY username = '$user'
		", $this->res);

		# What is returned should normally be an array of three indexes:
		#  - first_name
		#  - last_name
		#  - email
		#
		# The username is set for us in the auth() method, so you needn't set it here.
		return mysql_fetch_assoc($rs);
	}

	function syncinterval() {
		# This is how long, at least, we should wait before attempting to re-sync information
		# from the source to the product's authentication database.  The number is in seconds.

		return 300;		# 5 minutes
	}

}

?>
