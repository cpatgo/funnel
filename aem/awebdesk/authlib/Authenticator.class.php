<?php
require_once(dirname(dirname(__FILE__)) . '/functions/base.php');
require_once(dirname(dirname(__FILE__)) . '/deskrecord/DeskRecord.class.php');
require_once(dirname(__FILE__) . '/AuthlibInflector.class.php');
require_once(dirname(__FILE__) . '/GlobalAuth.class.php');
require_once(dirname(dirname(dirname(__FILE__))) . '/manage/config.inc.php');

/**
 * This is the cross-project authenticator class.
 * This class is able to take a username and password and
 * authenticate the user. After being successfully authenticated
 * a cookie will be set that tracks that user authentication.
 * subsequent calls, from any application in the same domain,
 * to isAuthenticated() will succeed until revokeAuthentication()
 * is called.
 *
 * This class _requires_ the config.inc.php file to be present
 * in the same directory, as it loads it to define the globals for connecting
 * to the database.
 */
class Authenticator {
	var $_dbConn;
	var $_inflectorClass;

	/** Constructor */
	function Authenticator(){
		$this->_dbConn = mysql_connect(AWEBP_AUTHDB_SERVER, AWEBP_AUTHDB_USER, AWEBP_AUTHDB_PASS, true);
		mysql_select_db(AWEBP_AUTHDB_DB, $this->_dbConn);
		$this->_inflectorClass = "AuthlibInflector";
	}

	/**
	 * This function returns the database handle used to access the authentication
	 * database.
	 */
	function getDatabaseHandle(){
		return $this->_dbConn;
	}

	/**
	 * This function closes the database handle used by this class.  You shouldn't
	 * have to do this unless your code is bad.
	 */
	function closeDatabaseHandle(){
		mysql_close($this->_dbConn);
	}

	/**
	 * This function authenticates a username and password against the
	 * global authentication database, returning true or false
	 * based on the status of the authentication operation.
	 *
	 * @return boolean status of authentication
	 */
	function authenticate($userName, $password, $remember = false){
		// Find the user
		$md5Pass = md5($password);

		if (isset($GLOBALS["loginsource"])) {
			if (adesk_auth_login_source($userName, $password, $remember))
				$user = adesk_auth_record_username($userName);
			else
				$user = null;
		} else {
			$user = DeskRecord::FindFirstByAttributes("GlobalAuth",
				array("username", "password"), array($userName, $md5Pass),
				$this->_dbConn, $this->_inflectorClass);
		}

		if($user == null){
			$this->_arSetCookie("tt_tt_aweb_globalauth_cookie", "", time() - 3600, "/");
			return false;
		} else {
			// Generate a string to hash
			$key = "aweb_" . $userName;
			$key = md5($key) . (isset($GLOBALS["loginsource"]) ? $user["id"] : $user->getId());
			$this->_arSetCookie("tt_aweb_globalauth_cookie", $key, ( $remember ? time() + 1296000 : 0 ), "/");
			// Save the data to the db
			if (!isset($GLOBALS["loginsource"]))
				$user->save();
			return true;
		}
	}

	/**
	 * This function revokes the authentication for the person, subsequent
	 * calls to isAuthenticated() will return false.
	 */
	function revokeAuthentication(){
		$this->_arSetCookie("tt_aweb_globalauth_cookie", "", time() - 3600, "/");
	}

	/**
	 * This method returns a boolean indicating whether or not
	 * the person is already authenticated.
	 *
	 * @return boolean Indicating whether or not the person is authenticated.
	 */
	function isAuthenticated(){
		// Basic check to see that we have the cookie
		if(!isset($_COOKIE['tt_aweb_globalauth_cookie'])) { return false; }
		// Get the cookie value
		$eKey = $_COOKIE['tt_aweb_globalauth_cookie'];
		// Get the user ID from that
		$userId = intval(substr($eKey, 32));

		if (isset($GLOBALS["loginsource"])) {
			$user = adesk_auth_record_id($userId);
		} else {
		// Build what the key should be
			$user = DeskRecord::FindById("GlobalAuth", $userId,
				$this->_dbConn, $this->_inflectorClass);
		}
		if($user == null) {
			$this->_arSetCookie("tt_aweb_globalauth_cookie", "", time() - 3600, "/");
			return false;
		}

		if (isset($GLOBALS["loginsource"]))
			$key = md5( "aweb_" . $user["username"]) . $user["id"];
		else
			$key = md5( "aweb_" . $user->getAttribute("username")) . $user->getID();

		if($key == $eKey){
			return true;
		} else {
			$this->_arSetCookie("tt_aweb_globalauth_cookie", "", time() - 3600, "/");
			return false;
		}
	}

	/**
	 * This method returns the user ID of the person currently
	 * authenticated, or -1 in the case that they are not.
	 *
	 * @return int Authenticated user's ID
	 */
	function userId(){
		if($this->isAuthenticated()){
			// Get the cookie value
			$eKey = $_COOKIE['tt_aweb_globalauth_cookie'];
			// Get the user ID from that
			return intval(substr($eKey, 32));
		} else {
			return false;
		}
	}

	function getUserObject(){
		if($this->isAuthenticated()){
			// Get the cookie value
			$eKey = $_COOKIE['tt_aweb_globalauth_cookie'];
			// Get the user ID from that
			$userId = intval(substr($eKey, 32));

			// Build what the key should be
			return DeskRecord::FindById("GlobalAuth", $userId,
				$this->_dbConn, $this->_inflectorClass);
		}
	}

	/**
	 * This method exists to make testing easier
	 */
	function _arSetCookie($name, $value, $expire, $path){
		setcookie($name, $value, $expire, $path);
		$_COOKIE[$name] = $value;
	}
}
?>
