<?php

$loginident = "Local";
$loginvars = "";

class LocalLoginSource extends adesk_LoginSource {

	function LocalLoginSource($source) {
		$source["host"] = AWEBP_AUTHDB_SERVER;
		$source["user"] = AWEBP_AUTHDB_USER;
		$source["pass"] = AWEBP_AUTHDB_PASS;
		$source["dbname"] = AWEBP_AUTHDB_DB;
		$this->adesk_LoginSource($source);
	}

	function connect() {
		$source    = $this->source;
		$this->res = mysql_connect($source["host"], $source["user"], $source["pass"], true);
		mysql_select_db($source["dbname"]);
	}

	function authok($user, $pass) {
		$user = mysql_real_escape_string($user, $this->res);
		$pass = md5($pass);
		$rs   = mysql_query("
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
		$source    = $this->source;
		$this->shouldsync = false;

		# We never sync from this connection, so we might as well return an empty array.
		return array();
	}

	function syncinterval() {
		return 300;		# Not really important, since we never sync.
	}

}

?>
