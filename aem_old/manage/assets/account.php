<?PHP
require_once awebdesk_assets("account.php");

class account_assets extends useraccount_assets {


	function account_assets() {
		parent::useraccount_assets();
	}

	function process(&$smarty) {

		/*
			unfortunately we can not include:
			{if $processesCnt or $pausedProcessesCnt}
			into the check, as those are calculated after process() method
		*/
		if ( $this->admin['pg_user_add'] || $this->admin['pg_user_edit'] || $this->admin['pg_user_delete'] || adesk_admin_ismaingroup() ) {
			$this->sideTemplate = 'side.settings.htm';
		}

		parent::process($smarty);

		if ( !isset($GLOBALS['_hosted_account']) ) return;

		// subscribers limit
		if ( !$this->admin['limit_subscriber'] or $this->admin['limit_subscriber'] > $GLOBALS['_hosted_limit_sub'] ) {
			$this->admin['limit_subscriber'] = $GLOBALS['_hosted_limit_sub'];
		}
		// emails limit
		if ( !$this->admin['limit_mail'] or $this->admin['limit_mail'] > $GLOBALS['_hosted_limit_mail'] ) {
			$this->admin['limit_mail'] = $GLOBALS['_hosted_limit_mail'];
		}
		//if ( $this->admin['limit_mail_type'] != 'ever' ) $this->admin['limit_mail_type'] = $row['limit_mail_type'];
		$this->admin['limit_mail_type'] = 'monthcdate';
	}

}
?>