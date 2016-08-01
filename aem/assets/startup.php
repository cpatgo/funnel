<?PHP

/*
 *
 */

require_once(dirname(__FILE__) . '/subscribe.php');
class startup_assets extends subscribe_assets {
	// constructor
	function startup_assets() {
		$_GET['action'] = 'subscribe';
		parent::subscribe_assets();
	}
}


?>
