<?PHP

require_once awebdesk_functions("widget.php");

class widget_assets extends AWEBP_Page {

	// constructor
	function widget_assets() {
		$this->pageTitle = _a("Widgets");
		parent::AWEBP_Page();
	}

	// processor
	function process(&$smarty) {

		// assign template
		$smarty->assign('content_template', 'widget.htm');
		// assign standard template data
 		$this->setTemplateData($smarty);
		// check if form is submitted
		//adesk_smarty_submitted($smarty, $this);

		if ( !adesk_admin_ismaingroup() ) {
			return adesk_smarty_noaccess($smarty);
		}

		$allbars = adesk_ihook('adesk_widget_bars');
		if ( !is_array($allbars) or !count($allbars) ) {
			return adesk_smarty_noaccess($smarty);
		}
		if ( !isset($allbars['admin']) and !isset($allbars['public']) ) {
			$allbars = array('admin' => $allbars);
		}
		$smarty->assign('allbars', $allbars);

		$allwidgets = widget_available();
		if ( !count($allwidgets) ) {
			return adesk_smarty_noaccess($smarty);
		}
		$smarty->assign('allwidgets', $allwidgets);

		$publicwidgets = array();
		$adminwidgets = array();
		foreach ( $allwidgets as $k => $v ) {
			if ( $v['section'] != 'admin' ) $publicwidgets[$k] = $v;
			if ( $v['section'] != 'public' ) $adminwidgets[$k] = $v;
		}
		$smarty->assign('publicwidgets', $publicwidgets);
		$smarty->assign('adminwidgets', $adminwidgets);

		//$publicinstalled = adesk_sql_select_array("SELECT * FROM #widget WHERE `section` = 'public' ORDER BY `sort_order`");
		//$admininstalled = adesk_sql_select_array("SELECT * FROM #widget WHERE `section` = 'admin' ORDER BY `sort_order`");
		$publicinstalled = $admininstalled = array();
		$sql = adesk_sql_query("SELECT * FROM #widget WHERE 1 ORDER BY `sort_order`");
		while ( $row = adesk_sql_fetch_assoc($sql) ) {
			if ( $row['section'] == 'admin' ) {
				$admininstalled[$row['id']] = $row;
			} else {
				$publicinstalled[$row['id']] = $row;
			}
		}
		$smarty->assign('publicinstalled', $publicinstalled);
		$smarty->assign('admininstalled', $admininstalled);
	}

}

?>
