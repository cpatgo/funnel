<?php
require_once(awebdesk('deskrecord/InflectorInterface.class.php'));
class AuthlibInflector extends InflectorInterface {
	function Classify($table){
		// We only have one table and class
		if(strtolower($table) == "aweb_globalauth"){
			require_once(awebdesk('authlib/GlobalAuth.class.php'));
			return "GlobalAuth";
		} else {
			return "";
		}
	}

	function Tableize($class){
		if(strtolower($class) == "globalauth"){
			return "aweb_globalauth";
		} else {
			return "";
		}
	}
}
?>
