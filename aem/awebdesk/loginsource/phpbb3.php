<?php

/*
Section: BOTH
LoginSource Name: phpBB3
Version: 1.0
Description: phpBB Login Source lets you use phpBB's database for user authentication. Works with phpBB 3.
Author: AwebDesk Softwares.
Author URL: http://www.awebdesk.com/
License: GPL
*/

$loginident = "PHPBB3";
$loginvars = "host,dbname,user,pass,tableprefix";

class PHPBB3LoginSource extends adesk_LoginSource {

	function PHPBB3LoginSource($source) {
		$this->adesk_LoginSource($source);
	}

	function connect() {
		$source    = $this->source;
		$this->res = mysql_connect($source["host"], $source["user"], $source["pass"], true);
		mysql_select_db($source["dbname"]);
	}

	function authok($user, $pass) {
		$source = $this->source;
		$user   = mysql_real_escape_string($user, $this->res);
		$tbl    = $source["tableprefix"];

		/**
		*
		* @version Version 0.1 / slightly modified for phpBB 3.0.x (using $H$ as hash type identifier)
		*
		* Portable PHP password hashing framework.
		*
		* Written by Solar Designer <solar at openwall.com> in 2004-2006 and placed in
		* the public domain.
		*
		* There's absolutely no warranty.
		*
		* The homepage URL for this framework is:
		*
		*	http://www.openwall.com/phpass/
		*
		* Please be sure to update the Version line if you edit this file in any way.
		* It is suggested that you leave the main version number intact, but indicate
		* your project name (after the slash) and add your own revision information.
		*
		* Please do not change the "private" password hashing method implemented in
		* here, thereby making your hashes incompatible.  However, if you must, please
		* change the hash type identifier (the "$P$") to something different.
		*
		* Obviously, since this code is in the public domain, the above are not
		* requirements (there can be none), but merely suggestions.
		*
		*
		* Hash the password
		*/
		/**
		* Encode hash
		*/
		if(!function_exists('_hash_encode64')) {
			function _hash_encode64($input, $count, &$itoa64)
			{
				$output = '';
				$i = 0;

				do
				{
					$value = ord($input[$i++]);
					$output .= $itoa64[$value & 0x3f];

					if ($i < $count)
					{
						$value |= ord($input[$i]) << 8;
					}

					$output .= $itoa64[($value >> 6) & 0x3f];

					if ($i++ >= $count)
					{
						break;
					}

					if ($i < $count)
					{
						$value |= ord($input[$i]) << 16;
					}

					$output .= $itoa64[($value >> 12) & 0x3f];

					if ($i++ >= $count)
					{
						break;
					}

					$output .= $itoa64[($value >> 18) & 0x3f];
				}
				while ($i < $count);

				return $output;
			}
			function _hash_crypt_private($password, $setting, &$itoa64)
			{
				$output = '*';
				if (substr($setting, 0, 3) != '$H$')
				{
					return $output;
				}

				$count_log2 = strpos($itoa64, $setting[3]);

				if ($count_log2 < 7 || $count_log2 > 30)
				{
					return $output;
				}

				$count = 1 << $count_log2;
				$salt = substr($setting, 4, 8);

				if (strlen($salt) != 8)
				{
					return $output;
				}
				if (PHP_VERSION >= 5)
				{
					$hash = md5($salt . $password, true);
					do
				{
					$hash = md5($hash . $password, true);
				}
					while (--$count);
				}
				else
				{
					$hash = pack('H*', md5($salt . $password));
					do
				{
					$hash = pack('H*', md5($hash . $password));
				}
					while (--$count);
				}

				$output = substr($setting, 0, 12);
				$output .= _hash_encode64($hash, 16, $itoa64);

				return $output;
			}
			function phpbb_check_hash($password, $hash)
			{
				$itoa64 = './0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
				if (strlen($hash) == 34)
				{
					return (_hash_crypt_private($password, $hash, $itoa64) === $hash) ? true : false;
				}

				return (md5($password) === $hash) ? true : false;
			}
		}

		$sq = mysql_fetch_assoc(mysql_query("SELECT user_password FROM ".$tbl."users WHERE username = '$user'",$this->res));
		if(phpbb_check_hash($pass, $sq['user_password'])){
			return true;
		}

		return false;
	}

	function info($user) {
		$user   = mysql_real_escape_string($user, $this->res);
		$source = $this->source;
		$tbl    = $source["tableprefix"];

		$sql = mysql_query("SELECT user_email FROM ".$tbl."users WHERE username = '$user'",$this->res);
		if ( !$sql or mysql_num_rows($sql) < 1 ) return false;
		$sq = mysql_fetch_array($sql);

		# What is returned should normally be an array of three indexes:
		#  - first_name
		#  - last_name
		#  - email
		#
		# The username is set for us in the auth() method, so you needn't set it here.
		return array(
			"first_name" => $user,
			"last_name"  => '',
			"email"      => $sq["user_email"]
		);


	}

	function syncinterval() {
		# This is how long, at least, we should wait before attempting to re-sync information
		# from the source to the product's authentication database.  The number is in seconds.

		return 300;		# 5 minutes
	}

}

?>
