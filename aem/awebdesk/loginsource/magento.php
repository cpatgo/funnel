<?php

/*
Section: BOTH
LoginSource Name: Magento
Version: 1.0
Description: Magento Login Source lets you use Magento's database for user authentication.
Author: AwebDesk Softwares.
Author URL: http://www.awebdesk.com/
License: GPL
*/

$loginident = "Magento";
$loginvars = "host,dbname,user,pass,tableprefix";

class MagentoLoginSource extends adesk_LoginSource {

	function MagentoLoginSource($source) {
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
		$sq     = mysql_fetch_assoc(mysql_query("SELECT c2.value AS 'password' FROM ".$tbl."customer_entity c1 INNER JOIN ".$tbl."customer_entity_varchar c2 ON c1.entity_id = c2.entity_id AND c1.entity_type_id = c2.entity_type_id AND c2.attribute_id = 12 WHERE c1.email = '$user'",$this->res));

		$parts  = explode( ':', $sq["password"] );
		$crypt  = $parts[0];

		$salt   = '';
		if (isset($parts[1]))
			$salt = $parts[1];

		$seed   = '';

		# This will never happen unless you directly modify the $seed variable above.
		if ($seed != '')
			$salt = $seed;

		$show_encrypt = false;
		$encrypted    = ($salt != '') ? md5($salt.$pass) : md5($pass);
		//$testcrypt    = ($show_encrypt) ? '{MD5}'.$encrypted : $encrypted;

		if ($crypt == $encrypted)
			return true;

		return false;
	}

	function info($user) {
		$user   = mysql_real_escape_string($user, $this->res);
		$source = $this->source;
		$tbl    = $source["tableprefix"];

		$sq = array();
		$result = mysql_query("SELECT c2.* FROM ".$tbl."customer_entity_varchar c2 INNER JOIN ".$tbl."customer_entity c1 ON c2.entity_id = c1.entity_id AND c2.entity_type_id = c1.entity_type_id WHERE c1.email = '$user' AND c2.attribute_id IN (5,7)", $this->res);
		while ( $row = mysql_fetch_array($result) ) {
			if ($row["attribute_id"] == 5) {
				$sq["first_name"] = $row["value"];
			}
			else {
				// attribute_id 7
				$sq["last_name"] = $row["value"];
			}
		}
		//$sq     = mysql_fetch_assoc(mysql_query("SELECT c2.* FROM ".$tbl."customer_entity_varchar c2 INNER JOIN ".$tbl."customer_entity c1 ON c2.entity_id = c1.entity_id AND c2.entity_type_id = c1.entity_type_id WHERE c1.email = '$user'",$this->res));

		/*
		if (strpos($sq["name"], " ") === false)
			$name = array($sq["name"], "");
		else
			$name = explode(' ', $sq["name"], 2);
		*/

		# What is returned should normally be an array of three indexes:
		#  - first_name
		#  - last_name
		#  - email
		#
		# The username is set for us in the auth() method, so you needn't set it here.
		return array(
			"first_name" => $sq["first_name"],
			"last_name"  => $sq["last_name"],
			"email"      => $user,
		);
	}

	function syncinterval() {
		# This is how long, at least, we should wait before attempting to re-sync information
		# from the source to the product's authentication database.  The number is in seconds.

		return 300;		# 5 minutes
	}

}

?>
