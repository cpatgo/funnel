<?php

/*
Section: BOTH
LoginSource Name: vBulletin 3
Version: 1.0
Description: vBulletin Login Source lets you use vBulletin's database for user authentication. Works with vBulletin 3.
Author: AwebDesk Softwares.
Author URL: http://www.awebdesk.com/
License: GPL
*/

$loginident = "vBulletin";
$loginvars = "host,dbname,user,pass,tableprefix";

class vBulletinLoginSource extends adesk_LoginSource {

	function vBulletinLoginSource($source) {
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

		$sql = mysql_query("SELECT salt FROM ".$tbl."user WHERE BINARY username = '$user'",$this->res);
		if ( !$sql or mysql_num_rows($sql) < 1 ) return false;
		$sq = mysql_fetch_array($sql);

		$salt = $sq["salt"];

		$pass = md5(md5($pass).$salt);

		$rs   = mysql_query("
			SELECT
				userid
			FROM
				".$tbl."user
			WHERE
				BINARY username = '$user'
			AND BINARY password = '$pass'
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
				".$tbl."user
			WHERE
				BINARY username = '$esc'
		",$this->res);

		$vb = mysql_fetch_array($rs);



		# What is returned should normally be an array of three indexes:
		#  - first_name
		#  - last_name
		#  - email
		#
		# The username is set for us in the auth() method, so you needn't set it here.
		return array(
			"first_name" => $user,
			"last_name"  => '',
			"email"      => $vb["email"]
		);


	}

	function syncinterval() {
		# This is how long, at least, we should wait before attempting to re-sync information
		# from the source to the product's authentication database.  The number is in seconds.

		return 300;		# 5 minutes
	}

}

?>
