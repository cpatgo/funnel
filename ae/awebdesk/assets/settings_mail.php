<?PHP

/*
 *
 */
require_once(awebdesk_classes('page.php'));
require_once awebdesk_functions("site.php");

class settings_mail_assets extends AWEBP_Page {

	var $table = 'backend';
	var $engine = false;
	var $rotator = false;
	var $connections = array();


	// constructor
	function settings_mail_assets() {
		// have to refetch application's awebdesk.php file to ensure we have a reference
		require(adesk_admin('functions/awebdesk.php'));
		if ( !isset($GLOBALS['adesk_mail_engine']) ) $GLOBALS['adesk_mail_engine'] = 'mailer';
		$this->rotator = ( $GLOBALS['adesk_mail_engine'] == 'swift' and $GLOBALS['adesk_mail_table'] != 'backend' );
		$this->engine = $GLOBALS['adesk_mail_engine'];
		$this->table = $GLOBALS['adesk_mail_table'];
		$this->pageTitle = _a("Mail Sending Options");
		$this->sideTemplate = $GLOBALS['adesk_sidemenu_settings'];
		parent::AWEBP_Page();
	}


	function process(&$smarty, $forcenopost = false) {
 		$this->setTemplateData($smarty);
		// check for privileges first!
		if ( !adesk_admin_ismaingroup() ) {
			// assign template
			$smarty->assign('content_template', 'noaccess.htm');
			return;
		}
		// check if form is submitted
		$formSubmitted = $_SERVER['REQUEST_METHOD'] == 'POST';
		if ( $formSubmitted && !$forcenopost ) {
			$submitResult = $this->formProcess();
			$smarty->assign('submitResult', $submitResult);
		}
		$smarty->assign('formSubmitted', $formSubmitted);

		// fetch all connections if rotator is used
		if ( $this->rotator ) {
			$sql = adesk_sql_query("SELECT * FROM #$GLOBALS[adesk_mail_table] ORDER BY corder");
			while ( $row = mysql_fetch_assoc($sql) ) {
				$row['pass'] = ( $row['pass'] == '' ? '' : base64_decode($row['pass']) ); // decoding mail password
				$this->connections[$row['id']] = $row;
			}
		}

		// assign config array
		$cfg = array(
			'id' => 1,
			'type' => $this->site['stype'],
			'host' => $this->site['smhost'],
			'port' => (int)$this->site['smport'],
			'user' => $this->site['smuser'],
			'pass' => ( $this->site['smpass'] == '' ? '' : base64_decode($this->site['smpass']) ), // decoding mail password
		);
		if ( $this->engine == 'swift' ) {
			$cfg['encrypt'] = $this->site['smenc'];
			$cfg['pop3b4smtp'] = $this->site['smpop3b4'];
		}
		if ( $this->rotator ) {
			$cfg['threshold'] = $this->site['smthres'];
		}
		$smarty->assign('cfg', $cfg);

		// assign "new" array
		$blank = array(
			'id' => 0,
			'type' => 0,
			'host' => '',
			'port' => 25,
			'user' => '',
			'pass' => '',
		);
		if ( $this->engine == 'swift' ) {
			$blank['encrypt'] = 8;
			$blank['pop3b4smtp'] = 0;
		}
		if ( $this->rotator ) {
			$blank['threshold'] = ( isset($this->site['smthres']) ? $this->site['smthres'] : 50 );
		}
		$smarty->assign('blank', $blank);

		// assign connections used (only in case of connection rotation)
		$smarty->assign('mailconnections', $this->connections);
		$smarty->assign('mailconnCnt', count($this->connections));

		// AEM demo complement (in branding)
		if ( isset($this->admin['brand_demo']) ) {
			// assign demoMode variable
			$smarty->assign('demoMode', $this->admin['brand_demo']);
		}
		$smarty->assign('plink', adesk_site_plink());

		// assign mailer used
		$smarty->assign('mailer', $this->engine);

		// assign rotator switch
		$smarty->assign('rotator', $this->rotator);

		// default sorting
		$smarty->assign('mailconnsort', '01');

		// assign inner template
		$smarty->assign('innertemplate', adesk_ihook('adesk_mailconn_form'));

		// assign template
		$smarty->assign('content_template', 'settings_mail.htm');
	}

	function formProcess() {
		$id = (int)$_POST['id'];
		$r = array('name' => 'edit', 'succeeded' => false, 'id' => $id);
		if ( $id == 0 ) {
			// add
			$r['name'] = 'add';
			// only for rotator...
			if ( !$this->rotator ) return $r;
			$arr['id'] = 0;
			$arr['type'] = (int)$_POST['send'];
			$arr['host'] = $_POST['smhost'];
			$arr['port'] = (int)$_POST['smport'];
			$arr['user'] = $_POST['smuser'];
			$arr['pass'] = base64_encode($_POST['smpass']);
			$arr['encrypt'] = (int)$_POST['smenc'];
			$arr['pop3b4smtp'] = (int)isset($_POST['smpop3b4']);
			$arr['threshold'] = (int)$_POST['smthres'];
			$arr['corder'] = 99999;
			// do insert
			$r['succeeded'] = adesk_sql_insert('#' . $this->table, $arr);
			// collect new id if done
			if ( $r ) $r['id'] = adesk_sql_insert_id();
			// and done
			return $r;
		}
		// edit
		if ( $this->rotator ) {
			$arr['type'] = (int)$_POST['send'];
			$arr['host'] = $_POST['smhost'];
			$arr['port'] = (int)$_POST['smport'];
			$arr['user'] = $_POST['smuser'];
			$arr['pass'] = base64_encode($_POST['smpass']);
			$arr['encrypt'] = (int)$_POST['smenc'];
			$arr['pop3b4smtp'] = (int)isset($_POST['smpop3b4']);
			$arr['threshold'] = (int)$_POST['smthres'];
		} else {
			$arr['stype'] = (int)$_POST['send'];
			$arr['smhost'] = $_POST['smhost'];
			$arr['smport'] = (int)$_POST['smport'];
			$arr['smuser'] = $_POST['smuser'];
			$arr['smpass'] = base64_encode($_POST['smpass']);
			if ( $this->engine == 'swift' ) {
				$arr['smenc'] = (int)$_POST['smenc'];
				$arr['smpop3b4'] = (int)isset($_POST['smpop3b4']);
			}
		}
		// do update
		$r['succeeded'] = adesk_sql_update('#' . $this->table, $arr, "id = '$id'");
		if ( $r['succeeded'] ) {
			if ( $id == 1 ) {
				$this->site['stype'] = (int)$_POST['send'];
				$this->site['smhost'] = $_POST['smhost'];
				$this->site['smport'] = (int)$_POST['smport'];
				$this->site['smuser'] = $_POST['smuser'];
				$this->site['smpass'] = base64_encode($_POST['smpass']);
			}
		}
		return $r;
	}
}

?>
