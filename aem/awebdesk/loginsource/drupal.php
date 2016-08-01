<?php

/*
Section: BOTH
LoginSource Name: Drupal
Version: 1.0
Description: Drupal Login Source lets you use Drupal's database for user authentication.
Author: AwebDesk Softwares.
Author URL: http://www.awebdesk.com/
License: GPL
*/

$loginident = "Drupal";
$loginvars = "host,dbname,user,pass,tableprefix";

class DrupalLoginSource extends adesk_LoginSource {

	function DrupalLoginSource($source) {
		$this->adesk_LoginSource($source);
	}

	function connect() {
		$source    = $this->source;
		$this->res = mysql_connect($source["host"], $source["user"], $source["pass"], true);
		mysql_select_db($source["dbname"]);
	}

	function authok($user, $pass) {
		$user   = mysql_real_escape_string($user, $this->res);
		$source = $this->source;
		$tbl    = $source["tableprefix"];
		$sq     = mysql_fetch_assoc(mysql_query("SELECT pass FROM ".$tbl."users WHERE name = '$user'",$this->res));
		$parts  = explode( ':', $sq["pass"] );
		$crypt  = $parts[0];

		$salt   = '';
		if (isset($parts[1]))
			$salt = $parts[1];

		$seed   = '';

		# This will never happen unless you directly modify the $seed variable above.
		if ($seed != '')
			$salt = $seed;

		$show_encrypt = false;
		$encrypted    = ($salt != '') ? md5($pass.$salt) : md5($pass);
		$testcrypt    = ($show_encrypt) ? '{MD5}'.$encrypted : $encrypted;

		if ($crypt == $testcrypt)
			return true;

		return false;
	}

	function info($user) {
		$user   = mysql_real_escape_string($user, $this->res);
		$source = $this->source;
		$tbl    = $source["tableprefix"];
		$sq     = mysql_fetch_assoc(mysql_query("SELECT mail, name FROM ".$tbl."users WHERE name = '$user'",$this->res));

		if (strpos($sq["name"], " ") === false)
			$name = array($sq["name"], "");
		else
			$name = explode(' ', $sq["name"], 2);

		# What is returned should normally be an array of three indexes:
		#  - first_name
		#  - last_name
		#  - email
		#
		# The username is set for us in the auth() method, so you needn't set it here.
		return array(
			"first_name" => $name[0],
			"last_name"  => "",
			"email"      => $sq["mail"]
		);
	}

	function syncinterval() {
		# This is how long, at least, we should wait before attempting to re-sync information
		# from the source to the product's authentication database.  The number is in seconds.

		return 300;		# 5 minutes
	}

}

?>
