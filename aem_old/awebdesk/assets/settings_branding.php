<?PHP

/*
*
*/
require_once(awebdesk_classes('page.php'));

class settings_branding_assets extends AWEBP_Page {

	var $usingEditor = true;
	var $brandingArray = null;
	var $brandingTable = 'backend';


	// constructor
	function settings_branding_assets() {
		// have to refetch application's awebdesk.php file to ensure we have a reference
		require(adesk_admin('functions/awebdesk.php'));
		// set branding array (BY REF!)
		$this->brandingArray =& $GLOBALS['adesk_branding_array'];
		$this->brandingTable = $GLOBALS['adesk_branding_table'];
		$this->pageTitle = _a("Branding");
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


		// check if form is submitted
		$formSubmitted = $_SERVER['REQUEST_METHOD'] == 'POST';
		if ( $formSubmitted ) {
			$submitResult = $this->formProcess();
			$smarty->assign('submitResult', $submitResult);
		}
		$smarty->assign('formSubmitted', $formSubmitted);

        // check for measurements
		if ( $this->usingEditor ) {
	        $heightMeasure = ( substr($this->brandingArray['brand_editorh'], -1) == '%' ? '%' : 'px' );
	        $widthMeasure = ( substr($this->brandingArray['brand_editorw'], -1) == '%' ? '%' : 'px' );
	        $editorHeight = substr($this->brandingArray['brand_editorh'], 0, (int)( substr($this->brandingArray['brand_editorh'], -1) == '%' ) - 2);
	        $editorWidth = substr($this->brandingArray['brand_editorw'], 0, (int)( substr($this->brandingArray['brand_editorh'], -1) == '%' ) - 2);
	        $smarty->assign('editorHeight', $editorHeight);
	        $smarty->assign('editorWidth', $editorWidth);
	        $smarty->assign('heightMeasure', $heightMeasure);
	        $smarty->assign('widthMeasure', $widthMeasure);
		}


		// assign config array
		$smarty->assign('cfgArray', $this->brandingArray);


		// assign template
		$smarty->assign('content_template', 'settings_branding.htm');
	}









	// form processor method
	function formProcess() {
		// we will return this
		$r = false;
		// prepare vars
		$q = array();
		if ( $this->usingEditor ) {
			$q['brand_editorw'] = (int)$_POST['brand_editorw'] . $_POST['brand_editorw_measure'];
			$q['brand_editorh'] = (int)$_POST['brand_editorh'] . $_POST['brand_editorh_measure'];
		}
		$q['brand_version'] = (int)isset($_POST['brand_version']);
		$q['brand_copyright'] = (int)isset($_POST['brand_copyright']);
		$q['brand_links'] = (int)isset($_POST['brand_links']);
		$q['brand_demo'] = (int)isset($_POST['brand_demo']);
		// run query
		$r = adesk_sql_update('#' . $this->brandingTable, $q);
		if ( $r ) {
			foreach ( $q as $k => $v ) {
				$this->brandingArray[$k] = $v;
			}
		}
		return $r;
	}



}


?>
