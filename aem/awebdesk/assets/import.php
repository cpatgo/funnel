<?PHP

/*
 *
 */

require_once(awebdesk_functions('import.php'));
require_once(awebdesk_classes('page.php'));

class import_assets extends AWEBP_Page {

	var $importer = 'import';
	var $backlink = '';

	var $multiDestination = false;

	// constructor
	function import_assets() {
		// have to refetch application's awebdesk.php file to ensure we have a reference
		require(adesk_admin('functions/awebdesk.php'));
		$this->pageTitle = _a("Import Tool");
		parent::AWEBP_Page();
	}




	function process(&$smarty) {
 		$this->setTemplateData($smarty);

		// relation ids (categories, departments, lists)
		$rels = adesk_ihook('adesk_import_relations');
		if ( !is_array($rels) ) $rels = array();
		$smarty->assign('rels', $rels);


		// header template? (for permission checks, notices, explanations, help, etc...)
		$tpl = adesk_ihook('adesk_import_header_template');
		if ( $tpl ) $smarty->assign('import_header_template', $tpl);

		// destinations template?
		$tpl = adesk_ihook('adesk_import_destinations_template');
		if ( $tpl ) $smarty->assign('import_destinations_template', $tpl);

		// additional import vars
		$vars = adesk_ihook('adesk_import_tplvars', 0); // returns associative array, keys are vars in smarty
		if ( is_array($vars) ) $smarty->assign($vars);

		// fields
		$fields = adesk_ihook('adesk_import_fields', 0, null);
		if ( !is_array($fields) ) $fields = array();
		$smarty->assign('fields', $fields);

		// import options
		$opts = adesk_ihook('adesk_import_options', 0);
		if ( !is_array($opts) ) $opts = array();//dbg($opts);
		$smarty->assign('opts', $opts);

		// relid could be provided
		$relid = (int)adesk_http_param('relid');
		$smarty->assign('relid', $relid);

		// assign action/mode
		$smarty->assign('mode', 'import');

		// assign importer id
		$smarty->assign('importer', $this->importer);

		$smarty->assign('backlink', $this->backlink);

		$smarty->assign('multiDestination', $this->multiDestination);

		// assign template
		$smarty->assign('content_template', 'import.htm');
	}


}


?>
