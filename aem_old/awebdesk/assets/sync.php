<?PHP

/*
 *
 */

require_once(awebdesk_functions('sync.php'));
require_once(awebdesk_classes('page.php'));

class sync_assets extends AWEBP_Page {

	// we need admin info
	//var $sort = '01';


	// private variables neccessary for page output and all methods to work
	var $table = 'sync';
	var $sort = '01';

	var $multiDestination = false;

	// constructor
	function sync_assets() {
		// have to refetch application's awebdesk.php file to ensure we have a reference
		require(adesk_admin('functions/awebdesk.php'));
		$this->pageTitle = _a("Database Sync");
		$this->sideTemplate = $GLOBALS['adesk_sidemenu_settings'];
		if ( isset($GLOBALS['adesk_sync_table']) ) {
			$this->table = $GLOBALS['adesk_sync_table'];
		}
		parent::AWEBP_Page();
	}

	// try to catch parameter from post, get or session
	function getParams() {
		// sorts
		if ( isset($_GET['syncsort']) ) $_SESSION['syncsort'] = $_GET['syncsort'];
		if ( isset($_SESSION['syncsort']) ) $this->sort = $_SESSION['syncsort'];
	}



	function process(&$smarty) {
 		$this->setTemplateData($smarty);
		// check for privileges first!
		if ( adesk_ihook_exists('adesk_sync_permission') ) {
			if ( !adesk_ihook_exists('adesk_sync_permission') ) {
				// assign template
				$smarty->assign('content_template', 'noaccess.htm');
				return;
			}
		}

		// get sorting order
		$sort = adesk_sync_sort($this->sort);

		$smarty->assign('syncsort', $this->sort);

		if (adesk_ihook_exists("adesk_sync_sidemenu"))
			$smarty->assign("side_content_template", adesk_ihook("adesk_sync_sidemenu"));

		// all accessible syncs list (we need this always, not just on 'list')
		$syncs = adesk_sync_get_all($sort, $this->table);
		$syncsCnt = count($syncs);
		$smarty->assign('syncs', $syncs);
		$smarty->assign('syncsCnt', $syncsCnt);

		$id = (int)adesk_http_param('relid');
		if ( !isset($syncs[$id]) ) $id = 0;

		/*
			get new syncs info (blank array)
		*/
		$data = adesk_sync_new($id, $this->table);
		// assign blank sync
		$smarty->assign('data', $data);

		// relation ids (categories, departments, lists)
		$rels = adesk_ihook('adesk_sync_relations');
		if ( !is_array($rels) ) $rels = array();
		$smarty->assign('rels', $rels);


		// destinations template?
		$tpl = adesk_ihook('adesk_sync_destinations_template');
		if ( $tpl ) $smarty->assign('sync_destinations_template', $tpl);

		// header template?
		$tpl = adesk_ihook('adesk_sync_header_template');
		if ( $tpl ) $smarty->assign('sync_header_template', $tpl);

		// additional import vars
		$vars = adesk_ihook('adesk_sync_tplvars', 0); // returns associative array, keys are vars in smarty
		if ( is_array($vars) ) $smarty->assign($vars);

		// fields
		$fields = adesk_ihook('adesk_sync_fields', 0);
		if ( !is_array($fields) ) $fields = array();
		$smarty->assign('fields', $fields);

		// sync options
		$opts = adesk_ihook('adesk_sync_options');
		if ( !is_array($opts) ) $opts = array();
		$smarty->assign('opts', $opts);

		// allowed database types
		$types = adesk_sync_database_types();
		$smarty->assign('types', $types);


		// this page's link
		$this_link = 'desk.php?action=sync&';
		$smarty->assign('this_link', $this_link);

		// assign action/mode
		$smarty->assign('mode', 'list');

		$smarty->assign('multiDestination', $this->multiDestination);

		$smarty->assign('app_url', isset($this->site['p_link2']) ? $this->site['p_link2'] : $this->site['p_link'] );

		// assign template
		$smarty->assign('content_template', 'sync.htm');
	}



}

?>
