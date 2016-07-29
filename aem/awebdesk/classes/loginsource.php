<?php

class adesk_LoginSource {

	# Connections to both a database and to an LDAP server generally require a resource
	# identifier, which we store below.
	var $res = null;

	# The source record we're using to connect with.
	var $source = null;

	# A cache of the user info.
	var $c_info = array();

	# If this is marked false, we won't bother syncing after we authenticate.
	var $shouldsync = true;

	function connect($source) {
	}

	function authok($user, $pass) {
	}

	function info($user) {
		return $this->info;
	}

	function syncinterval() {
		# This is how long, at least, we should wait before attempting to re-sync information
		# from the source to the product's authentication database.  The number is in seconds.

		return 300;		# 5 minutes
	}

	# Don't modify anything below this line.

	function adesk_LoginSource($source) {
		$this->source = $source;

		# If someone puts random SQL into their table prefix, this code below should save us.
		if (isset($this->source["tableprefix"]) && $this->source["tableprefix"] != "") {
			if (!preg_match('/[a-zA-Z0-9_]*/', $this->source["tableprefix"]))
				$this->source["tableprefix"] = "";
		}
	}

	function auth($user, $pass) {
		$source = $this->source;
		$this->connect($source);

		$authok = $this->authok($user, $pass);

		if (!$authok)
			return false;

		$record = adesk_auth_record_username($user);
		$record_user = adesk_sql_select_row("SELECT id, approved FROM #user WHERE absid = '$record[id]'");

		// if either the aweb_globalauth record does not exist, or the #user record does not exist
		// (remember aweb_globalauth record could remain even after deleting the user through the admin interface)
		if (!$record || !$record_user) {
			$info = $this->c_info = $this->info($user);
			if (!$record) {
			  $id = adesk_auth_create($user, "", $info["first_name"], $info["last_name"], $info["email"], false);
			}
			else {
			  $id = $record["id"];
			}

			require_once awebdesk_functions("user.php");
			require_once awebdesk_functions("ajax.php");

			$gset = explode(",", $this->source["groupset"]);
			adesk_user_global_import($id, true, $gset);

			adesk_ihook("adesk_loginsource_auth_import_after", $id);
		} else {
			# If the source was recently updated within the last 5 minutes, don't sync it again.
			if (isset($record["sourceupdated"]) && $record["sourceupdated"] != "") {
				$then = strtotime($record["sourceupdated"]);
				$now  = strtotime($record["a_now"]);

				if (($now - $then) < $this->syncinterval())
					$this->shouldsync = false;
			}

			$id = $record["id"];
		}

		if ($this->c_info == array())
			$this->c_info = $this->info($user);

		$this->c_info["id"]       = $id;
		$this->c_info["username"] = $user;
		$this->c_info["sourceid"] = $source["id"];

		if ($this->shouldsync)
			adesk_auth_update($this->c_info, $id);

		# We're not updating the password, but we will be using it later in its md5 form.
		$this->c_info["password"] = md5($pass);
		return true;
	}
}

?>
