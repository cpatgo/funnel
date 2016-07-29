<?php

/*
Section: BOTH
LoginSource Name: LDAP
Version: 1.0
Description: LDAP Login Source lets you use any server that supports LDAP protocol to perform user authentication.
Author: AwebDesk Softwares.
Author URL: http://www.awebdesk.com/
License: GPL
*/

$loginident = "LDAP";
$loginvars = "host,port,basedn";

class LDAPLoginSource extends adesk_LoginSource {

	var $realuser = "";

	function LDAPLoginSource($source) {
		$this->adesk_LoginSource($source);
	}

	function connect() {
		$source = $this->source;
		$this->res = @ldap_connect($source["host"], $source["port"]);
	}

	function authok($user, $pass) {
		$this->realuser = $user;
		if ($this->source["binddn"] != "") {
			$bound = @ldap_bind($this->res, $this->source["binddn"], $this->source["bindpw"]);

			if (!$bound)
				return false;

			$rs  = @ldap_search($this->res, $this->source["basedn"], "({$this->source["userattr"]}=$user)");
			$tmp = @ldap_get_entries($this->res, $rs);

			if ($tmp["count"] < 1)
				return false;

			$ent = $tmp[0];
			if (!isset($ent[$this->source["loginattr"]]))
				return false;

			$this->realuser = $user = $ent[$this->source["loginattr"]][0];
		}

		if ($this->source["loginusesdn"])
			$user = sprintf("%s=%s,%s", $this->source["loginattr"], $user, $this->source["basedn"]);

		return @ldap_bind($this->res, $user, $pass);
	}

	function info($user) {
		if ($user != $this->realuser)
			$user = $this->realuser;
		$rs  = @ldap_search($this->res, $this->source["basedn"], $q = "({$this->source["loginattr"]}=$user)");
		$tmp = @ldap_get_entries($this->res, $rs);

		if ($tmp["count"] < 1)
			return array();

		$ent = $tmp[0];

		if (!isset($ent["givenname"]))
			$ent["givenname"] = array("");
		if (!isset($ent["sn"]))
			$ent["sn"] = array("");
		if (!isset($ent["mail"]))
			$ent["mail"] = array("");

		return array(
			"first_name" => $ent["givenname"][0],
			"last_name"  => $ent["sn"][0],			# surname
			"email"      => $ent["mail"][0],
		);
	}

	function syncinterval() {
		# This is how long, at least, we should wait before attempting to re-sync information
		# from the source to the product's authentication database.  The number is in seconds.

		return 300;		# 5 minutes
	}

}

?>
