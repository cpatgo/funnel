<?PHP

/*
 *
 */

class errorslog_assets extends AWEBP_Page {

	// constructor
	function errorslog_assets() {
		$this->pageTitle = _a("Error Logs");
		parent::AWEBP_Page();
	}


	function process(&$smarty) {
        if (!adesk_admin_isadmin()) {
			// assign template
			$smarty->assign('content_template', 'noaccess.htm');
			return;
		}

		// do we request anything?
		$id    = (int)adesk_http_param('id');
		$delid = (int)adesk_http_param('del');

		if ( $delid ) {
			if ( $delid == -1 ) {
				adesk_sql_query("TRUNCATE TABLE #trapperrlogs");
			} else {
				adesk_sql_delete("#trapperrlogs", "id = '$delid'");
			}
		}


		$errors = array();
		$sql = adesk_sql_query("
			SELECT
				id, tstamp, errnumber, errmessage, filename, url, linenum, session, userid, ip, host, referer
			FROM
				#trapperrlogs
			ORDER BY
				tstamp DESC
		");
		while ( $row = mysql_fetch_assoc($sql)) {
			$errors[] = $row;
		}
		$errorLog = false;
		if ( $id > 0 ) {
			$sql = adesk_sql_query("
				SELECT
					*
				FROM
					#trapperrlogs
				WHERE
					id = '$id'
			");
			if ( mysql_num_rows($sql) > 0 ) {
				$errorLog = mysql_fetch_assoc($sql);
				$errline = (int)$errorLog['linenum'];
				$errorLog['lines'] = array();
				if ( file_exists($errorLog['filename']) ) {
					$file = @file($errorLog['filename']);
					if ( $file ) {
						$lines = count($file);
						for ( $i = 0; $i < $lines; $i++ ) {
							$line = $i + 1;
							if ( $line >= $errline - 5 && $line <= $errline + 5 ) {
								$errorLog['lines'][$line] = array(
									'row' => $file[$i],
									'err'  => $line == $errline,
								);
							}
						}
					}
				}
			}
		}


		$errorTypes = array
		(
			1   =>  'Error',
			2   =>  'Warning',
			4   =>  'Parsing Error',
			8   =>  'Notice',
			16  =>  'Core Error',
			32  =>  'Core Warning',
			64  =>  'Compile Error',
			128 =>  'Compile Warning',
			256 =>  'User Error',
			512 =>  'User Warning',
			1024=>  'User Notice',
			2047=>  'E_ALL',
			2048=>  'E_STRICT'
		);

		// assign errors log
		$smarty->assign('errors', $errors);
		$smarty->assign('errorsCnt', count($errors));
		$smarty->assign('errorLog', $errorLog);
		$smarty->assign('errorTypes', $errorTypes);



		// assign template
		$smarty->assign('content_template', 'errorslog.htm');
		$this->setTemplateData($smarty);
	}

}

?>