<?PHP

/*
 *
 */

class useraccount_assets extends AWEBP_Page {


	// constructor
	function useraccount_assets() {
		$this->pageTitle = _a("Your Account");
		parent::AWEBP_Page();
	}


	function process(&$smarty) {
 		$this->setTemplateData($smarty);
		// check if form is submitted
		adesk_smarty_submitted($smarty, $this);

		if ( isset($this->admin['local_zoneid']) ) {
			$smarty->assign("zones", tz_box());
		} else {
			$offset = ( $this->admin['t_offset_o'] == '-' ? -(int)$this->admin['t_offset'] : (int)$this->admin['t_offset'] );
			$smarty->assign('curDateTime', adesk_date_timeoffset(adesk_getCurrentDateTime(), $offset));
		}

		// var preparation hook
		if ( adesk_ihook_exists('adesk_user_account_display') ) {
			$vars = adesk_ihook('adesk_user_account_display');
			if ( is_array($vars) ) $smarty->assign($vars);
		}
		// settings template hook
		$settings_template = adesk_ihook('adesk_user_account_settings');
		$smarty->assign('settings_template', (string)$settings_template);
		// additional info template hook
		$additional_template = adesk_ihook('adesk_user_account_additional');
		$smarty->assign('additional_template', (string)$additional_template);

		// assign template
		$smarty->assign('content_template', 'account.htm');
	}

	function formProcess(&$smarty) {
		// result is 0 if avatar is not uploaded
		$result = array('status' => 0, 'title' => '', 'message' => '');
		// update array
		$update = array();
		if ( adesk_ihook_exists('adesk_user_account_update') ) {
			$update = adesk_ihook('adesk_user_account_update');
			if ( !is_array($update) ) {
				$result['message'] = (string)$update;
				return $result;
			}
		}
		// deal with standard stuff here
		$update['lang'] = $_POST['lang_ch'];
		// $update['default_dashboard'] = $_POST['default_dashboard'];
		$update['default_dashboard'] = 'classic';
		$update['default_mobdashboard'] = $_POST['default_mobdashboard'];
		if ( isset($this->admin['local_zoneid']) ) {
			$update["local_zoneid"]                 = $_POST["local_zoneid"];
			$offset                                 = tz_offset($update["local_zoneid"]);
			$update["t_offset_o"]                   = ($offset >= 0 ? "+" : "-");
			$update["t_offset"]                     = tz_hours($offset);
			$update["t_offset_min"]                 = tz_minutes($offset, $update["t_offset"]);
		} else {
			// ensure that offset_o is enum
			$update['t_offset_o']                   = ( in_array($_POST['t_offset_o'], array('-', '+')) ?  $_POST['t_offset_o'] : '+' );
			$update['t_offset']                     = (int)$_POST['t_offset'];
		}

		// update the user record
		$result['status'] = adesk_sql_update('#user', $update, "id = '{$this->admin['id']}'");
		// if password has changed, add it to authenticator
		if ( !adesk_auth_isconnected() ) adesk_auth_connect();
		if ( isset($_POST['username']) ) unset($_POST['username']); // can't change his username
		if (preg_match('/^[ \t\r\n]+$/', $_POST['pass'])) {
			$result['status'] = false;
			$result['message'] = _a("You cannot use a password consisting only of spaces");
			return $result;
		}
		$_POST['pass'] = trim($_POST['pass']);
		if ( $_POST['pass'] != '' ) $_POST['password'] = md5($_POST['pass']);
		adesk_auth_update($_POST, $this->admin['absid']);
		// fetch new info
		adesk_session_drop_cache();
		$this->admin = adesk_admin_get_totally_unsafe($this->admin["id"]);
		$GLOBALS["admin"] = $this->admin;
		$smarty->assign("admin", $this->admin);
		return $result;
	}



}

?>