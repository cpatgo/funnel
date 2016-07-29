<?PHP

/*
 *
 */
require_once(awebdesk_classes('page.php'));

class about_assets extends AWEBP_Page {

	var $thisVersion = '1.0';
	var $thisBuild = 1;

	// constructor
	function about_assets() {
		require(adesk_admin('functions/versioning.php'));
		$this->thisVersion  = $thisVersion;
		$this->thisBuild    = $thisBuild;
		$this->pageTitle    = sprintf(_a("About %s"), $GLOBALS['adesk_app_name']);
		$this->sideTemplate = $GLOBALS['adesk_sidemenu_settings'];
		parent::AWEBP_Page();
	}



	function process(&$smarty) {
 		$this->setTemplateData($smarty);
		// check for privileges first!
		if ( !adesk_admin_ismaingroup()) {
			// assign template
			adesk_smarty_noaccess($smarty, $this);
			return;
		}
		
		$vapiurl = "http://customers.awebdesk.com/api/index.php?m=license&q=get_license_info&api_key=6512bd43d9caa6e02c990b0a82652dca&php=y";
		$phpv=file_get_contents($vapiurl);
        $aemversion=unserialize($phpv);
		$latestversion =  $aemversion['version_decimal'];

		if (adesk_ihook_exists("adesk_about_assets_pre"))
			$smarty = adesk_ihook("adesk_about_assets_pre", $smarty);

		$fetched = false;
           
		$smarty->assign('appID', $GLOBALS['adesk_app_id']);
		$smarty->assign('encoding', adesk_php_encoding());
		$smarty->assign('hash', md5($this->site['serial']));

		$smarty->assign('build', $this->thisBuild);
		$smarty->assign('latestv', $latestversion);

		// assign template
		$smarty->assign('content_template', 'about.htm');
	}


}

?>
