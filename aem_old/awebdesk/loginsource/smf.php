<?php

/*
Section: BOTH
LoginSource Name: SMF
Version: 1.0
Description: SMF Login Source lets you use SMF's database for user authentication. (SMF=Simple Machines Forum)
Author: AwebDesk Softwares.
Author URL: http://www.awebdesk.com/
License: GPL
*/

$loginident = "Simple_Machines_Forum";
$loginvars = "host,dbname,user,pass,tableprefix";

class Simple_Machines_ForumLoginSource extends adesk_LoginSource {

	function Simple_Machines_ForumLoginSource($source) {
		$this->adesk_LoginSource($source);
	}

	function connect() {
		$source    = $this->source;
		$this->res = mysql_connect($source["host"], $source["user"], $source["pass"], true);
		mysql_select_db($source["dbname"]);
	}

	function authok($user, $pass) {
		$user = mysql_real_escape_string($user, $this->res);
		$source = $this->source;
		$tbl    = $source["tableprefix"];

		$sql = mysql_query("SELECT passwordSalt FROM  ".$tbl."members WHERE BINARY memberName = '$user'",$this->res);
		if ( !$sql or mysql_num_rows($sql) < 1 ) return false;
		$sq = mysql_fetch_array($sql);

		$salt = $sq["passwordSalt"];

		// by default we will not use the hash here.
		$pass = sha1(strtolower($user) . $pass);

		$rs   = mysql_query("
			SELECT
				ID_MEMBER
			FROM
				 ".$tbl."members
			WHERE
				BINARY memberName = '$user'
			AND BINARY passwd = '$pass'
		",$this->res);

		if (mysql_num_rows($rs) === 1)
			return true;
	}

	function info($user) {
		$esc  = mysql_real_escape_string($user, $this->res);
		$source = $this->source;
		$tbl    = $source["tableprefix"];


		$rs   = mysql_query("
			SELECT
				*
			FROM
				 ".$tbl."members
			WHERE
				BINARY memberName = '$esc'
		",$this->res);

		$smf = mysql_fetch_array($rs);



		# What is returned should normally be an array of three indexes:
		#  - first_name
		#  - last_name
		#  - email
		#
		# The username is set for us in the auth() method, so you needn't set it here.
		return array(
			"first_name" => $user,
			"last_name"  => '',
			"email"      => $smf["emailAddress"]
		);


	}

	function syncinterval() {
		# This is how long, at least, we should wait before attempting to re-sync information
		# from the source to the product's authentication database.  The number is in seconds.

		return 300;		# 5 minutes
	}

}

?>
