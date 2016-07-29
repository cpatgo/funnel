<?php





class AWEBP_Page {

	// properties
	var $site			= array();
	var $admin			= false;

	var $pageTitle		= '';			# Page title
	var $sideTemplate	= '';			# Side content template

	// constructor
	function AWEBP_Page() {
		// get site info
		$this->site =& $GLOBALS['site'];
		// get admin info
		$this->admin =& $GLOBALS['admin'];
	}





	function process(&$smarty) {
/*		// check if form is submitted
		$formSubmitted = $_SERVER['REQUEST_METHOD'] == 'POST';
		if ( $formSubmitted ) {
			$submitResult = $this->formProcess();
			$smarty->assign('submitResult', $submitResult);
		}
		$smarty->assign('formSubmitted', $formSubmitted);
*/
		// assign template
		$smarty->assign('content_template', 'noaccess.htm');
		$this->setTemplateData($smarty);
	}


	function setTemplateData(&$smarty) {
		$smarty->assign('pageTitle', $this->pageTitle);
		$smarty->assign('side_content_template', $this->sideTemplate);
	}


	function formProcess() {
		// return result
		return array('name' => '', 'status' => false, 'title' => '');
	}

}



?>
