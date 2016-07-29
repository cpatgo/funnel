<?PHP

/*
 *
 */

require_once(adesk_admin('functions/subscriber_import.php'));
require_once(awebdesk_functions('import.php'));

class subscriber_import_assets extends AWEBP_Page {

	var $configured = false;
	var $cfg = array('lists' => null, 'status' => 0, 'columns' => array());

	var $cfields = array();

	// constructor
	function subscriber_import_assets() {
		$this->AWEBP_Page();
		$this->pageTitle = _a("Subscriber Import Tool");
		$this->configured = false;
	}

	function process(&$smarty) {
		$this->setTemplateData($smarty);

		$admin = adesk_admin_get();

		$canImportSubscriber = $this->admin['pg_subscriber_import'];
		if ( !isset($GLOBALS['_hosted_account']) ) {
			$canImportSubscriber = $canImportSubscriber && withinlimits('subscriber', limit_count($this->admin, 'subscriber') + 1);
		}

		if ( !$canImportSubscriber ) {
			$smarty->assign('content_template', 'noaccess.htm');
			return;
		}

		if ( list_get_cnt() == 0 ) {
			$smarty->assign('content_template', 'nolists.htm');
			return;
		}

		// cleanup
		if ( (int)adesk_sql_select_one('=COUNT(*)', '#subscriber_import') > 100000 ) {
			adesk_sql_query("TRUNCATE TABLE #subscriber_import");
		} else {
			adesk_sql_delete('#subscriber_import', "`tstamp` < SUBDATE(NOW(), INTERVAL 1 DAY)");
		}

		// check if form is submitted
		adesk_smarty_submitted($smarty, $this);

		// get all lists
		$lists = list_get_all();
		$smarty->assign("lists", $lists);

		$require_name = false;
		if ( $this->cfg['lists'] ) {
			$listslist = implode("', '", $this->cfg['lists']);
			$require_name = (bool)adesk_sql_select_one('=SUM(require_name)', '#list', "id IN ('$listslist')");
		}
		$smarty->assign("require_name", $require_name);

		// get all autoresponders
		$so = new adesk_Select();
		$responders = responder_select_bylist($so, ( $this->cfg['lists'] ? implode(',', $this->cfg['lists']) : null ));
		$smarty->assign("responders", $responders);

		// did he send any campaigns in the past (needed for "send last message")
		$campaigns_sent = (int)adesk_sql_select_one('=COUNT(*)', '#campaign', "status != 0 AND cdate < NOW()");
		$smarty->assign("campaigns_sent", $campaigns_sent);

		$smarty->assign('fields', adesk_ihook('adesk_import_fields', $this->cfg['lists'], $this->cfg['status']));
		$smarty->assign('cfields', $this->cfields);
        $smarty->assign('cfield_types', adesk_custom_fields_types());

		// step 1 completed
		$smarty->assign("configured", $this->configured);
		$smarty->assign("config", $this->cfg);

		$smarty->assign("curl", function_exists('curl_init'));
		// array of all external sources, and whether or not they are supported
		$external_sources = subscriber_import_external_sources();
		// array of all server requirements for external sources, and whether or not they are enabled for this server
		$external_sources_check = subscriber_import_external_sources(true);
		$external_sources_supported = $external_sources_check_supported = 0;
		foreach ($external_sources as $source) $external_sources_supported += (int)$source["supported"];
		foreach ($external_sources_check as $source) $external_sources_check_supported += (int)$source["supported"];
		if ( $external_sources_check_supported < count($external_sources_check) ) {
			$smarty->assign("all_external_sources_supported", 0);
		}
		else {
			$smarty->assign("all_external_sources_supported", 1);
		}
		//dbg($external_sources);
		$smarty->assign("external_sources", $external_sources);
		$smarty->assign("external_sources_supported", $external_sources_supported);
		$smarty->assign("external_sources_check", $external_sources_check);

		// google oauth stuff
		$smarty->assign("google_spreadsheets_oauth_url", "");
		$smarty->assign("google_spreadsheets_token", "");
 		$smarty->assign("google_contacts_oauth_url", "");
		$smarty->assign("google_contacts_token", "");
		if ( (float)PHP_VERSION >= 5.1 ) {
		  require_once(awebdesk_functions('google.php'));
      adesk_google_oauth_session();

      // google contacts
      if ($_SESSION["google_contacts_oauth_url"]) {
        // no token - require them to log-in and confirm on google site
        $smarty->assign("google_contacts_oauth_url", $_SESSION["google_contacts_oauth_url"]);
        $smarty->assign("google_contacts_token", "");
      }
      else {
        // token exists (either in URL after authorizing, or saved in database)
        $smarty->assign("google_contacts_token", $_SESSION["google_contacts_token"]);
      }

      // google spreadsheets
		  if ($_SESSION["google_spreadsheets_oauth_url"]) {
        // no token - require them to log-in and confirm on google site
        $smarty->assign("google_spreadsheets_oauth_url", $_SESSION["google_spreadsheets_oauth_url"]);
        $smarty->assign("google_spreadsheets_token", "");
      }
      else {
        // token exists (either in URL after authorizing, or saved in database)
        $smarty->assign("google_spreadsheets_token", $_SESSION["google_spreadsheets_token"]);
      }
		}

		// freshbooks OAuth
    $smarty->assign("freshbooks_account", adesk_http_param("freshbooks_account"));
    $smarty->assign("freshbooks_token", "");
    $smarty->assign("freshbooks_oauth_url", "");
		if ( (int)PHP_VERSION > 4 && function_exists('curl_init') ) {
	    require_once(awebdesk_functions('freshbooks.php'));
		  // get request token
		  $freshbooks_oauth_init = freshbooks_oauth_init();
		  if ($freshbooks_oauth_init["success"]) {
		    if ( isset($freshbooks_oauth_init["user_authorize_url"]) ) {
		      // have not authorized on freshbooks yet - redirect them to freshbooks site
		      header("Location: " . $freshbooks_oauth_init["user_authorize_url"]);
		    }
        else {
          // should have authorized already, and either just coming from freshbooks site, or token is cached
          if ( !adesk_http_param_exists("freshbooks_redirect") && adesk_http_param_exists("freshbooks_account") && adesk_http_param_exists("oauth_verifier") && adesk_http_param_exists("oauth_token") ) {
            // redirect with hash in URL so it loads Freshbooks import option automatically
            header("Location: " . $GLOBALS["site"]["p_link"] . "/manage/desk.php?action=subscriber_import&freshbooks_redirect=0#freshbooks");
          }
          $smarty->assign("freshbooks_token", $freshbooks_oauth_init["oauth_token"]);
        }
		  }
		  else {
		    // success = 0
        $smarty->assign("freshbooks_account", "");
		  }
		}

		$maxfilesize = @ini_get("upload_max_filesize");
		$smarty->assign("maxfilesize", $maxfilesize);

		$smarty->assign("content_template", "subscriber_import.htm");
		$smarty->assign("side_content_template", "side.subscriber.htm");
	}

	function formProcess(&$smarty) {

		if (!isset($GLOBALS["_hosted_account"])) {
			// turning off some php limits
			@ignore_user_abort(1);
			@ini_set('max_execution_time', 950 * 60);
			@set_time_limit(950 * 60);
			$ml = ini_get('memory_limit');
			if ( $ml != -1 and (int)$ml < 128 and substr($ml, -1) == 'M') @ini_set('memory_limit', '128M');
			set_include_path('.');
			@set_magic_quotes_runtime(0);
		}

		// result is 0 if rows are not uploaded
		$r = array('status' => 0, 'section' => 'generic', 'message' => _a('Import '));
		$this->cfg['lists'] = adesk_http_param('into');
		$this->cfg['external'] = adesk_http_param('external');
		$smarty->assign("external", $this->cfg['external']);
		$external_options = false;
		if ( !is_array($this->cfg['lists']) ) {
			$this->cfg['lists'] = null;
		} else {
			$this->cfg['lists'] = array_diff(array_map('intval', $this->cfg['lists']), array(0));
		}
		if ( !$this->cfg['lists'] ) {
			$r['message'] = _a('Lists not selected.');
			return $r;
		}
		$smarty->assign("list_checked", $this->cfg['lists']); // the lists checked from step 1
		$this->cfg['status'] = (int)adesk_http_param('status');

		$this->cfg['update'] = (int)adesk_http_param_exists('update');
		$this->cfg['skipbounced'] = (int)adesk_http_param_exists('skipbounced');
		$this->cfg['sendlast'] = (int)adesk_http_param_exists('sendlast');
		$this->cfg['sendresponders'] = (int)adesk_http_param_exists('sendresponders');
		$this->cfg['sentresponders'] = (int)adesk_http_param_exists('sentresponders');

		# If hosted, force sendlast to zero; imported subscribers should never have the last
		# campaign sent to them.
		if (isset($GLOBALS["_hosted_account"]))
			$this->cfg['sendlast'] = 0;

		$this->cfg['from'] = trim((string)adesk_http_param('from'));
		if ( !in_array($this->cfg['from'], array('file', 'text', 'external')) ) {
			$this->cfg['from'] = 'text';
		}

		/* handle file upload / text parsing / external service connection here */
		$path = adesk_cache_dir() . "/";
		$charset = _i18n("utf-8");
		$this->cfg['columns'] = array();

		$fileiscsv = false;

		if ( $this->cfg['from'] == 'file' ) {
			// handle file upload
			if ( !isset($_FILES['file']) ) {
  		  $r['message'] = _a('There was an error uploading your file. Please try again.');
  			return $r;
  		}
			if ( is_array($_FILES['file']['tmp_name']) ) {
				$r['message'] = _a('It seems like you tried to upload multiple files. You can import only one file at a time.');
				return $r;
			}
			if ( $_FILES['file']['error'] ) {
				if ($_FILES['file']['error'] == UPLOAD_ERR_INI_SIZE) {
					$r['message'] = sprintf(_a("The file you've uploaded is too large for the server to process; it must be less than %d megabyte(s).", @ini_get("upload_max_filesize")));
					$r['section'] = "importfrom_file";
				} else {
					$r['message'] = _a('There was an error while uploading your file.');
				}
				return $r;
			}
			if ( !file_exists($_FILES['file']['tmp_name']) ) {
				$r["message"] = _a("The file was not uploaded successfully.  Your web server may not have the proper permission to save uploaded files, or there may not be space on the disk where those files are saved.  Please contact your ISP for help concerning this upload failure.");
				#$r['message'] = _a('The uploaded file can not be imported. Please contact support for details.');
				return $r;
			}

			$file_extension = explode('.', $_FILES['file']['name']);
			$file_extension = strtolower($file_extension[ count($file_extension) - 1 ]);

			// prevent PHP file uploads
			if ( substr($file_extension, 0, 3) == 'php' || $_FILES['file']['type'] == 'application/x-httpd-php' ) {
				$r['message'] = _a('You cannot upload PHP files.');
				$r['section'] = "importfrom_file";
				return $r;
			}

			if ( in_array($file_extension, array('xls', 'xlsm', 'xlsb')) ) {
				$r['message'] = _a('We were unable to read that file. Please save that file as a CSV (comma separated) file. You can do so by going to File > Save As and choosing CSV as the file type.');
				$r['section'] = "importfrom_file";
				return $r;
			}
			elseif ($file_extension == 'xlsx') {
				$file_content = adesk_file_upload_read($_FILES['file']['type'], $_FILES['file']['tmp_name'], adesk_file_get($_FILES['file']['tmp_name']), "xlsx", "csv");
				if ( is_array($file_content) && isset($file_content['error']) && $file_content['error'] ) {
					$r['section'] = "importfrom_file";
					if ( !class_exists('ZipArchive') || !function_exists('simplexml_load_string') ) {
						$r['message'] = _a('We were unable to read that file. Please save that file as a CSV (comma separated) file. You can do so by going to File > Save As and choosing CSV as the file type.');
					} else {
						$r['message'] = $file_content['error'];
					}
					return $r;
				}
				else {
					$text = $file_content;
				}
			}
			/*
			elseif ($file_extension == 'vcf') {
				require_once(awebdesk_classes('vcard.php'));
				require_once(awebdesk_functions('vcard.php'));
				$lines = file($_FILES['file']['tmp_name']);
				$card = parse_vcards($lines);
				foreach ($card as $entry) {
					dbg($entry,1);
					$entry = print_vcard($entry, array());
					//dbg($entry,1);
				}
				dbg('stop');
			}
			*/
			else {
				$fileiscsv = true;
				$text = (string)@file_get_contents($_FILES['file']['tmp_name']);
			}
			if ( !$text ) {
				$r['message'] = _a('The uploaded file seems to be empty, or could not be read. Please verify that the file is in CSV format.');
				$r['section'] = "importfrom_file";
				return $r;
			}
		} elseif ( $this->cfg['from'] == 'text' ) {
			// handle text parsing
			$text = trim((string)adesk_http_param('text'));
			$this->cfg['from'] = 'file';
		} elseif ( $this->cfg['from'] == 'external' ) {
			// handle external service connection test
			$external_options = adesk_http_param('external_options'); // whether or not to show modal with filters/options
			if ($external_options) {
			  // should always get here on first form submit
			  // just grabbing external fields/columns here
			  $res = subscriber_import_external($this->cfg, $_POST, 1);
			  //dbg($res);
			  if ( isset($res['succeeded']) && !$res['succeeded'] ) {
    			$r['message'] = $res['message'];
    			return $r;
			  }
			  $smarty->assign('external_options_fields', $res);
			  $text = false;
			}
			else {
			  $res = subscriber_import_external($this->cfg, $_POST);
			  // second form submit from modal (import filters)
			  // this is where we actually have external data returned
      		  if ( !$res['succeeded'] ) {
      		    $r['message'] = $res['message'];
      		    return $r;
      		  }
      		  if (!$res['data']) {
			    $r['message'] = _a('No data was returned from the external source. Please make sure there is actual data for the external source, and that any filters applied are not restricting the data returned.');
			    return $r;
      		  }
      		  $text = $res['data'];
      		  $charset = 'utf-8';
			}
		}
		// they save the output to a temp file
		if ( !$text && $this->cfg['from'] != 'file' && !$external_options ) {
			$r['message'] = _a('You did not enter any data into a text box. Please add data first...');
			return $r;
		}

		if (!isset($external_options) || !$external_options) {
      if ($text) {
    		//$r['delimiter_file'] = $delimiter = (string)adesk_http_param("delimiter_text");
    		$r['delimiter_file'] = adesk_import_delimiter_guess(substr($text, 0, 1000));
    		unset($_POST['text']);
    		//$text = adesk_utf_conv("utf-8", _i18n("utf-8"), $text);
    		$text = adesk_utf_conv($charset, "utf-8", trim($text));

    		if (isset($_FILES['file'])) {
    			$filename = 'csvimport-' . $this->admin['id'] . sprintf('-%s.csv', md5($_FILES['file']['name']));
    		} else {
    			$filename = 'csvimport-' . $this->admin['id'] . '-tmpfile.csv';
    		}

    		if ( !@file_put_contents($path . $filename, $text) ) {
    			$r['message'] = _a('Could not save the content to import.');
    			return $r;
    		}

    		// do stuff with $text (data) string variable
    		// get array from CSV file
			$csv = array();
			$off = 0;

			adesk_array_parsecsv($csv, $off, $text, adesk_import_delimiter($r['delimiter_file']), true);
    		#$csv = adesk_import_csv2array($text, $r['delimiter_file']);
    		unset($text);
    		// get fields
    		$this->cfg['columns'] = adesk_import_columns($csv);
    		$this->cfg['import_file'] = $filename;
    		// save rows count
    		$this->cfg['rows'] = count($csv);
    		unset($csv);
    		if ( !count($this->cfg['columns']) ) {
    			$r['message'] = _a('No columns could be matched. Please try using different settings.');
    			return $r;
    		}
    	}
    	else {
    		# We got here because we uploaded a csv file; let's move it to the right location.
    		$filename = 'csvimport-' . $this->admin['id'] . '-' . md5($_FILES['file']['name']) . '.csv';
    		$this->cfg['import_file'] = $filename;

    		move_uploaded_file($_FILES['file']['tmp_name'], $path . $filename);
    		$fp = @fopen($path . $filename, "r");
    		$this->cfg = array_merge($this->cfg, subscriber_import_fileinfo($fp));
    		$this->cfg['rows'] = $this->cfg['lines'];
    		fclose($fp);
    	}

    	// get custom fields
    	$this->cfields = list_get_fields($this->cfg['lists'], true);
    	// count required fields
    	$required = 1; // email is required
    	// 2do: check if any list requires name, then increment
    	$require_name = false;
    	if ( $require_name ) $required++;
    	foreach ( $this->cfields as $row ) {
    		if ( $row['req'] ) $required++;
    	}
    	if ( count($this->cfg['columns']) < $required ) {
    		$r['message'] = sprintf(_a('The data  does not have enough columns to complete the import. It needs to have at least %d columns.'), $required);
    		return $r;
    	}

      $this->cfg['valid'] = ( isset($GLOBALS['_hosted_account']) || withinlimits('subscriber', limit_count($this->admin, 'subscriber') + $this->cfg['rows']) );
		}

		if ($this->cfg['external'] && $external_options) {
        $smarty->assign('external_options', 1);
		}
		else {
		  // if NOT external source, set to true so we move to the next page
		  $this->configured = true;
		}

		$r['status'] = true;
		$r['message'] = _a('Your content was successfully parsed.');

		$_SESSION['subscriber_importer'] = $this->cfg;
		return $r;
	}
}

?>
