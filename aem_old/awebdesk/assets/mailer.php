<?PHP

/*
 *
 */
require_once(awebdesk_classes('page.php'));

class mailer_assets extends AWEBP_Page {

	var $table = 'backend';
	var $engine = false;
	var $rotator = false;
	var $connections = array();


	// constructor
	function mailer_assets() {
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


	function process(&$smarty) {
 		$this->setTemplateData($smarty);
		// check for privileges first!
		if ( $this->admin['id'] != 1 || isset($GLOBALS["_hosted_account"]) ) {
			// assign template
			adesk_smarty_noaccess($smarty, $this);
			return;
		}
		// check if form is submitted
		$formSubmitted = $_SERVER['REQUEST_METHOD'] == 'POST';
		if ( $formSubmitted ) {
			$submitResult = $this->formProcess();
			$smarty->assign('submitResult', $submitResult);
		}
		$smarty->assign('formSubmitted', $formSubmitted);

		// fetch all connections if rotator is used
		if ( $this->rotator ) {
			$sql = adesk_sql_query("SELECT * FROM #{$this->table} ORDER BY corder");
			while ( $row = mysql_fetch_assoc($sql) ) {
				$row['pass'] = ( $row['pass'] == '' ? '' : base64_decode($row['pass']) ); // decoding mail password
				if ( adesk_ihook_exists('adesk_mailconn_row') ) {
					$row = adesk_ihook('adesk_mailconn_row', $row);
				}
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
			$cfg['frequency'] = $this->site['sdfreq'];
			$cfg['pause'] = $this->site['sdnum'];
			$cfg['limit'] = $this->site['sdlim'];
			$cfg['limitspan'] = $this->site['sdspan'];
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
			/*
			$blank['frequency'] = $this->site['sdfreq'];
			$blank['pause'] = $this->site['sdnum'];
			$blank['limit'] = $this->site['sdlim'];
			$blank['limitspan'] = $this->site['sdspan'];
			*/
			$blank['frequency'] =
			$blank['pause'] =
			$blank['limit'] = 0;
			$blank['limitspan'] = 'hour';
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

		// assign template vars
		if ( adesk_ihook_exists('adesk_mailconn_vars') ) {
			$smarty = adesk_ihook('adesk_mailconn_vars', $smarty);
		}

		// sending speed
		$smarty->assign('speed', calculateSendingSpeed());

		// assign template
		$smarty->assign('content_template', 'settings_mail.htm');
	}

	/*
		UNUSED
	*/
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
			$arr['frequency'] = ( $_POST['ltype'] == 'sd' ? (int)$_POST['sdfreq'] : 0 );
			$arr['pause'] = ( $_POST['ltype'] == 'sd' ? (int)$_POST['sdnum'] : 0 );
			$arr['limit'] = ( $_POST['ltype'] == 'lim' ? (int)$_POST['sdlim'] : 0 );
			$arr['limitspan'] = ( $_POST['sdspan'] == 'day' ? 'hour' : 'day' );
			$arr['corder'] = 99999;
			// do insert
			$r['succeeded'] = adesk_sql_insert('#' . $this->table, $arr);
			// if done
			if ( $r ) {
				// collect new id
				$r['id'] = adesk_sql_insert_id();
				// run ihooks
				adesk_ihook('adesk_mailconn_save', $r['id'], $arr);
			}
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
			$arr['frequency'] = ( $_POST['ltype'] == 'sd' ? (int)$_POST['sdfreq'] : 0 );
			$arr['pause'] = ( $_POST['ltype'] == 'sd' ? (int)$_POST['sdnum'] : 0 );
			$arr['limit'] = ( $_POST['ltype'] == 'lim' ? (int)$_POST['sdlim'] : 0 );
			$arr['limitspan'] = ( $_POST['sdspan'] == 'day' ? 'hour' : 'day' );
		} else {
			$arr['stype']  = (int)$_POST['send'];
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
				$this->site['stype']  = (int)$_POST['send'];
				$this->site['smhost'] = $_POST['smhost'];
				$this->site['smport'] = (int)$_POST['smport'];
				$this->site['smuser'] = $_POST['smuser'];
				$this->site['smpass'] = base64_encode($_POST['smpass']);
				if ( $this->rotator ) {
					$this->site['sdfreq'] = ( $_POST['ltype'] == 'sd' ? (int)$_POST['sdfreq'] : 0 );
					$this->site['sdnum']  = ( $_POST['ltype'] == 'sd' ? (int)$_POST['sdnum'] : 0 );
					$this->site['sdlim']  = ( $_POST['ltype'] == 'lim' ? (int)$_POST['sdlim'] : 0 );
					$this->site['sdspan'] = ( $_POST['sdspan'] == 'day' ? 'hour' : 'day' );
					// update the backend table with new defaults
					$update = array(
						'sdfreq' => $this->site['sdfreq'],
						'sdnum'  => $this->site['sdnum'],
						'sdlim'  => $this->site['sdlim'],
						'sdspan' => $this->site['sdspan'],
					);
					adesk_sql_update('#backend', $update);
				}
			}
			// run ihooks
			adesk_ihook('adesk_mailconn_save', $id, $arr);
		}
		return $r;
	}
}


function calculateSendingSpeed() {
	$site = adesk_site_get();
	$serverLimit = _a("Server is the limit.");
	$sdnum = $site['sdnum'];
	$sdfreq = $site['sdfreq'];
	$sdepm = $site['sdlim'];
	if ( $sdepm > 0 ) {
		$sdepm = (int)( $sdepm / ( $site['sdspan'] == 'hour' ? 60 : 60 * 24 ) );
	} else $sdepm = 2000;
	// infinite check
	$infinite = false;
	if ( (int)$sdfreq == 0 ) $infinite = true;
	if ( (int)$sdnum == 0 ) $infinite = true;
	// infinite EPM check
	$infiniteEPM = false;
	if ( $sdepm == 0 ) $infiniteEPM = true;
	$perMinEPM = (float)$sdepm;
	// needs markup
	$fixIt = true;
	// if infinite
	if ( $infinite ) {
		// if EPM is also infinite
		if ( $infiniteEPM ) {
			// really infinite
			$fixIt = false;
			$perSec = $serverLimit;
			$perMin = $serverLimit;
			$perHour = $serverLimit;
			$perEml = 0;
		} else {
			// use EPM
			$perMin = $perMinEPM;
			$perSec = $perMin / 60;
			$perHour = $perMin * 60;
			$perEml = 1 / $perSec;
		}
	} else {
		// calculate per second
		$perSec = $sdfreq / $sdnum;
		$perEml = 1 / $perSec;
		// turn into minutes
		$perMin = $perSec * 60;
		// turn into hours
		$perHour = $perMin * 60;
		// check if less than EPM
		if ( $perMin > $perMinEPM && $perMinEPM > 0 ) {
			// use EPM
			$perMin = $perMinEPM;
			$perSec = $perMin / 60;
			$perHour = $perMin * 60;
			$perEml = 1 / $perSec;
		}
	}
	if ( $fixIt ) {
		$perHour = round($perHour, 2);
		$perMin = round($perMin, 2);
		$perSec = round($perSec, 2);
		$perEml = round($perEml, 2);
	}
	// done with calculating
	return array('spe' => $perEml, 'eps' => $perSec, 'epm' => $perMin, 'eph' => $perHour);
}
?>
