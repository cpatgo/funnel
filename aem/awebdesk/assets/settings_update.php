<?PHP

/*
 *
 */
require_once(awebdesk_classes('page.php'));

class settings_update_assets extends AWEBP_Page {

	// constructor
	function settings_update_assets() {
		if ( adesk_site_isknowledgebuilder() ) {
			$this->pageTitle = sprintf(_a("About %s"), $GLOBALS['adesk_app_name']);
		} else {
			$this->pageTitle = _a("Check for Updates");
		}
		$this->sideTemplate = $GLOBALS['adesk_sidemenu_settings'];
		parent::AWEBP_Page();
	}



	function process(&$smarty) {
 		$this->setTemplateData($smarty);
		// check for privileges first!
		if ( $this->admin['id'] != 1 ) {
			// assign template
			$smarty->assign('content_template', 'noaccess.htm');
			return;
		}


		$fetched = false;
		$latest =  6.3.0;
	 
		$shouldUpdate = ( ( $latest != 0 and $latest != '' ) ? version_compare($GLOBALS['site']['version'], $latest, '<' ) : false );
		$smarty->assign('latest', $latest);
		$smarty->assign('fetched', $fetched);
		$smarty->assign('shouldUpdate', $shouldUpdate);

		$smarty->assign('appID', $GLOBALS['adesk_app_id']);
		$smarty->assign('encoding', adesk_php_encoding());
		$smarty->assign('hash', md5($this->site['serial']));

		// assign template
		$smarty->assign('content_template', 'settings_update.htm');
	}


}

?>