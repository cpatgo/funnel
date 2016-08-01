<?php

function adesk_google_oauth_session() {
  require_once(awebdesk_classes('Zend/Loader.php'));
	require_once(awebdesk_classes('Zend/Gdata/AuthSub.php'));

	$admin = adesk_admin_get();

	// logout - Contacts
	if ( adesk_http_param_exists("google_contacts_logout") && (int)adesk_http_param("google_contacts_logout") == 1 ) {
	  $delete_cache = adesk_sql_delete("#subscriber_import_service", "userid = '$admin[id]' AND service = 'google_contacts'");
    if ( isset($_SESSION['google_contacts_token']) ) {
	    $revoke = Zend_Gdata_AuthSub::AuthSubRevokeToken($_SESSION['google_contacts_token']);
      unset($_SESSION['google_contacts_token']);
    }
  }

	// logout - Spreadsheets
	if ( adesk_http_param_exists("google_spreadsheets_logout") && (int)adesk_http_param("google_spreadsheets_logout") == 1 ) {
	  $delete_cache = adesk_sql_delete("#subscriber_import_service", "userid = '$admin[id]' AND service = 'google_spreadsheets'");
	  if ( isset($_SESSION['google_spreadsheets_token']) ) {
      $revoke = Zend_Gdata_AuthSub::AuthSubRevokeToken($_SESSION['google_spreadsheets_token']);
      unset($_SESSION['google_spreadsheets_token']);
	  }
  }

	// logout - Analytics
	if ( adesk_http_param_exists("google_analytics_logout") && (int)adesk_http_param("google_analytics_logout") == 1 ) {
	  if ( isset($_SESSION['google_analytics_token']) ) {
      $revoke = Zend_Gdata_AuthSub::AuthSubRevokeToken($_SESSION['google_analytics_token']);
      unset($_SESSION['google_analytics_token']);
	  }
  }

  $google_contacts_token = $google_spreadsheets_token = $google_analytics_token = "";
  $_SESSION['google_contacts_oauth_url'] = "";
  $_SESSION['google_spreadsheets_oauth_url'] = "";
  $_SESSION['google_analytics_oauth_url'] = "";

  // coming back from Google after authorizing access
  // "token" parameter should be in URL
  if ( adesk_http_param_exists("source") && adesk_http_param_exists("token") ) {
    $source = adesk_http_param("source");
    $token = adesk_http_param('token');
    if ($source == "google_contacts") {
	    $google_contacts_token = $_SESSION['google_contacts_token'] = Zend_Gdata_AuthSub::getAuthSubSessionToken($token);
      $google_contacts_token_save = array("token" => $google_contacts_token);
      $google_contacts_token_save = serialize($google_contacts_token_save);
      // cache to database
      $google_contacts_token_exists = (int)adesk_sql_select_one("=COUNT(*)", "#subscriber_import_service", "userid = '$admin[id]' AND service = 'google_contacts'");
      if (!$google_contacts_token_exists) {
        $insert = array(
          "userid" => $admin["id"],
          "service" => "google_contacts",
          "connection_data" => $google_contacts_token_save,
        );
        $sql = adesk_sql_insert("#subscriber_import_service", $insert);
      }
      else {
        adesk_sql_update_one("#subscriber_import_service", "connection_data", $google_contacts_token_save, "userid = '$admin[id]' AND service = 'google_contacts'");
      }
    }
    elseif ($source == "google_spreadsheets") {
	    $google_spreadsheets_token = $_SESSION['google_spreadsheets_token'] = Zend_Gdata_AuthSub::getAuthSubSessionToken($token);
      $google_spreadsheets_token_save = array("token" => $google_spreadsheets_token);
      $google_spreadsheets_token_save = serialize($google_spreadsheets_token_save);
      // cache to database
      $google_spreadsheets_token_exists = (int)adesk_sql_select_one("=COUNT(*)", "#subscriber_import_service", "userid = '$admin[id]' AND service = 'google_spreadsheets'");
      if (!$google_spreadsheets_token_exists) {
        $insert = array(
          "userid" => $admin["id"],
          "service" => "google_spreadsheets",
          "connection_data" => $google_spreadsheets_token_save,
        );
        $sql = adesk_sql_insert("#subscriber_import_service", $insert);
      }
      else {
        adesk_sql_update_one("#subscriber_import_service", "connection_data", $google_spreadsheets_token_save, "userid = '$admin[id]' AND service = 'google_spreadsheets'");
      }
    }
    elseif ($source == "google_analytics") {
	    $google_analytics_token = $_SESSION['google_analytics_token'] = Zend_Gdata_AuthSub::getAuthSubSessionToken($token);
    }
  }
  else {
    // not in URL, so check if token already exists in database (cached)

	  // google contacts
	  $google_contacts_token_exists = adesk_sql_select_one("connection_data", "#subscriber_import_service", "userid = '$admin[id]' AND service = 'google_contacts'");
	  if ($google_contacts_token_exists) {
      $google_contacts_token_result = unserialize($google_contacts_token_exists);
      if ( isset($google_contacts_token_result["token"]) ) {
        $_SESSION['google_contacts_token'] = $google_contacts_token_result["token"];
      }
    }

    // google spreadsheets
    $google_spreadsheets_token_exists = adesk_sql_select_one("connection_data", "#subscriber_import_service", "userid = '$admin[id]' AND service = 'google_spreadsheets'");
    if ($google_spreadsheets_token_exists) {
      $google_spreadsheets_token_result = unserialize($google_spreadsheets_token_exists);
      if ( isset($google_spreadsheets_token_result["token"]) ) {
        $_SESSION['google_spreadsheets_token'] = $google_spreadsheets_token_result["token"];
      }
    }
  }

  // google contacts
	if ( !isset($_SESSION['google_contacts_token']) || !$_SESSION['google_contacts_token'] ) {
		// no token - require them to log-in and confirm on google site
		$_SESSION['google_contacts_token'] = "";
		$scope = "https://www.google.com/m8/feeds";
		$_SESSION['google_contacts_oauth_url'] = Zend_Gdata_AuthSub::getAuthSubTokenUri($GLOBALS['site']['p_link'] . '/manage/desk.php?action=subscriber_import&source=google_contacts#google_contacts', $scope, 0, 1);
	}
	else {
	  // token exists (either in URL after authorizing, or saved in database)
	  // you should have $_SESSION["google_contacts_token"]
	}

	// google spreadsheets
	if ( !isset($_SESSION['google_spreadsheets_token']) || !$_SESSION['google_spreadsheets_token'] ) {
		// no token - require them to log-in and confirm on google site
		$_SESSION['google_spreadsheets_token'] = "";
		$scope = "https://spreadsheets.google.com/feeds";
		$_SESSION['google_spreadsheets_oauth_url'] = Zend_Gdata_AuthSub::getAuthSubTokenUri($GLOBALS['site']['p_link'] . '/manage/desk.php?action=subscriber_import&source=google_spreadsheets#google_spreadsheets', $scope, 0, 1);
	}
	else {
	  // token exists (either in URL after authorizing, or saved in database)
	  // you should have $_SESSION["google_spreadsheets_token"]
	}

	// google analytics
	if ( !isset($_SESSION['google_analytics_token']) || !$_SESSION['google_analytics_token'] ) {
		// no token - require them to log-in and confirm on google site
		$_SESSION['google_analytics_token'] = "";
		$scope = "https://www.google.com/analytics/feeds";
		$_SESSION['google_analytics_oauth_url'] = Zend_Gdata_AuthSub::getAuthSubTokenUri($GLOBALS['site']['p_link'] . '/manage/desk.php?action=list&source=google_analytics', $scope, 0, 1);
	}
	else {
	  // token exists (either in URL after authorizing, or saved in database)
	  // you should have $_SESSION["google_analytics_token"]
	}
}

function adesk_google_import_contacts($post, $external_options) {
	require_once(awebdesk_classes('Zend/Loader.php'));
	$r = array();

	try {
		Zend_Loader::loadClass('Zend_Gdata');
		Zend_Loader::loadClass('Zend_Gdata_AuthSub');
		Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
		Zend_Loader::loadClass('Zend_Http_Client');
		Zend_Loader::loadClass('Zend_Gdata_Query');
		Zend_Loader::loadClass('Zend_Gdata_Feed');
		Zend_Loader::loadClass('Zend_Uri_Http');

		$fields_filter = array(
      "fullName" => array("label" => "Name", "type" => "textbox"),
    );
	  if ($external_options) return $fields_filter;

		$client = Zend_Gdata_AuthSub::getHttpClient($_SESSION['google_contacts_token']);

		$gdata = new Zend_Gdata($client);
		$gdata->setMajorProtocolVersion(3);

		// perform query and get result feed
		$query = new Zend_Gdata_Query('https://www.google.com/m8/feeds/contacts/default/full');
		$query -> maxResults = 5000;
		$feed = $gdata -> getFeed($query);
    //dbg($feed -> entries);

		$fields_map = array();
		$filter = ( isset($post["external_options_filters"]) ) ? $post["external_options_filters"] : array();
    $people = array();

		// parse feed and extract contact information into simpler objects
		foreach ($feed -> entries as $entry) {
		  $entry = simplexml_load_string( $entry->getXML() );
		  $entry = get_object_vars($entry);

		  //dbg($entry);

		  $person = array();

		  if ( isset($entry["name"]) ) {
		    $entry_name = get_object_vars($entry["name"]);
		    $person["fullName"] = $entry_name["fullName"];
		  }
		  else {
		    $person["fullName"] = "";
		  }

		  if ( isset($entry["email"]) ) {
  		  $entry_email = get_object_vars($entry["email"][0]);
  		  $entry_email = $entry_email["@attributes"]["address"];
  		  $person["email"] = $entry_email;
		  }
		  else {
		    $person["email"] = "";
		  }

		  if ( isset($entry["phoneNumber"]) ) {
		    $person["phoneNumber"] = $entry["phoneNumber"];
		  }
		  else {
		    $person["phoneNumber"] = "";
		  }

			$add = true;
		  foreach ($filter as $field => $value) {
		    if ( trim($value) ) {
		      if ( !preg_match("/" . $value . "/i", $person[$field]) ) $add = false;
		    }
		  }

		  if ($add) $people[] = $person;
		}

		//dbg($people);
		if (!$people) {
			$r['message'] = _a('No records were found.');
			return $r;
		}
		$firstrow = current($people);
		$header = array_keys($fields_map);
		$r['succeeded'] = true;
		$r['data'] = adesk_array_csv($people, $header, $output = array());
	}
	catch (Exception $e) {
	  //dbg( $e->getMessage() );
		$r['succeeded'] = false;
		// provide friendlier error messages
		switch ( $e->getMessage() ) {
			//case 'Security check: Illegal character in filename' :
			//break;
			case 'CAPTCHA challenge issued by server' :
				$error = _a('Incorrect username/password combination');
			break;
			default :
				$error = $e->getMessage();
			break;
		}
		if ( preg_match("/<TITLE>.*<\/TITLE>/", $error, $match) ) {
		  $error = strip_tags($match[0]);
		}
		//dbg($error);
		$r['message'] = $error;
	}

	return $r;
}

function adesk_google_import_spreadsheets($post, $external_options) {
	require_once(awebdesk_classes('Zend/Loader.php'));
	$r = array();

	try {
		Zend_Loader::loadClass('Zend_Gdata');
		Zend_Loader::loadClass('Zend_Gdata_AuthSub');
		Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
		Zend_Loader::loadClass('Zend_Http_Client');
		Zend_Loader::loadClass('Zend_Gdata_Query');
		Zend_Loader::loadClass('Zend_Gdata_Feed');
		Zend_Loader::loadClass('Zend_Uri_Http');
		Zend_Loader::loadClass('Zend_Gdata_Spreadsheets');

    $client = Zend_Gdata_AuthSub::getHttpClient($_SESSION['google_spreadsheets_token']);

		$spreadsheet_service = new Zend_Gdata_Spreadsheets($client);
		$spreadsheet_service -> setMajorProtocolVersion(3);
		$service_feed = $spreadsheet_service -> getSpreadsheetFeed();

		$fields_map = array();
		$filter = ( isset($post["external_options_filters"]) ) ? $post["external_options_filters"] : array();
		$people = array();

		// loop through spreadsheets
		$spreadsheet_names = array();
		$worksheet_names = array();
    foreach($service_feed -> entries as $spreadsheet) {
      $spreadsheet_id = explode("/", $spreadsheet -> id -> text);
      $spreadsheet_id = $spreadsheet_id[5];
      $spreadsheet_names[$spreadsheet_id] = $spreadsheet -> title -> text;
      $query = new Zend_Gdata_Spreadsheets_DocumentQuery();
      $query -> setSpreadsheetKey($spreadsheet_id);
      $worksheet_feed = $spreadsheet_service -> getWorksheetFeed($query);

      // loop through worksheets
      $worksheet_counter = 1;
      foreach($worksheet_feed -> entries as $worksheet) {
        $worksheet_names[$spreadsheet_id . "_" . $worksheet_counter] = $worksheet -> title -> text;
        $worksheet_id = explode("/", $worksheet -> id -> text);
        $worksheet_id = $worksheet_id[5];

        // cell-based feed
        /*
        $query2 = new Zend_Gdata_Spreadsheets_CellQuery();
        $query2 -> setSpreadsheetKey($spreadsheet_id);
        //$query2 -> setWorksheetId($worksheet_id);
        $cell_feed = $spreadsheet_service -> getCellFeed($query2);
        dbg($cell_feed);
        */

        // only retrieve worksheet data if we are past the filter stage
        if (!$external_options) {
          // list-based feed

          $filter_worksheet_number = explode("_", $filter["worksheet"]);
          $filter_worksheet_number = $filter_worksheet_number[ count($filter_worksheet_number) - 1 ];
          // make sure we are looking at the spreadsheet/worksheet chosen in the filter
          if ($spreadsheet_id == $filter["spreadsheet"] && $worksheet_counter == $filter_worksheet_number) {
            $query2 = new Zend_Gdata_Spreadsheets_ListQuery();
            $query2 -> setSpreadsheetKey($spreadsheet_id);
            $query2 -> setWorksheetId($worksheet_counter); // counter is incremented and should match worksheet number (IE: page number 1, 2, 3, etc)
            $list_feed = $spreadsheet_service -> getListFeed($query2);
            //dbg($list_feed);
            //$worksheet_url = $spreadsheet_service -> adesk_getListFeed($query2); // the URL for the worksheet; helpful when debugging
            //dbg($worksheet_url,1);

            // loop through each worksheet row
            foreach ($list_feed -> entries as $row) {
              $row_data = $row -> getCustom();
              $person = array();
              foreach ($row_data as $custom_entry) {
                //echo $custom_entry -> getColumnName() . " = " . $custom_entry -> getText() . "<br />";
                $person[ $custom_entry -> getColumnName() ] = $custom_entry -> getText();
                if ( !isset($fields_map[ $custom_entry -> getColumnName() ]) ) $fields_map[ $custom_entry -> getColumnName() ] = $custom_entry -> getColumnName();
              }
              $people[] = $person;
            }
          }
        }

        $worksheet_counter++;
      }
    }

    $fields_filter = array(
    	"spreadsheet" => array("label" => "Spreadsheet", "type" => "select", "options" => $spreadsheet_names, "onchange" => "google_spreadsheets_toggle(this.value);"),
    	"worksheet" => array("label" => "Worksheet", "type" => "select", "options" => $worksheet_names),
      //"name" => array("label" => "Name", "type" => "textbox"),
    );
  	if ($external_options) {
  		// save connection details to DB
  		//$connection_data = array( 'google_docs_user' => $post['google_docs_user'], 'google_docs_pass' => base64_encode($post['google_docs_pass']) );
  		//$connection_save = subscriber_import_external_save($post['external'], $connection_data);
  	  return $fields_filter;
  	}

    //dbg($people);

		if (!$people) {
			$r['message'] = _a('No records were found.');
			return $r;
		}
		$firstrow = current($people);
		$header = array_keys($fields_map);
		$r['succeeded'] = true;
		$r['data'] = adesk_array_csv($people, $header, $output = array());
	}
	catch (Exception $e) {
		$r['succeeded'] = false;
		// provide friendlier error messages
		switch ( $e->getMessage() ) {
			//case 'Security check: Illegal character in filename' :
			//break;
			case 'CAPTCHA challenge issued by server' :
				$error = _a('Incorrect username/password combination');
			break;
			default :
				$error = $e->getMessage();
			break;
		}
		if ( preg_match("/<TITLE>.*<\/TITLE>/", $error, $match) ) {
		  $error = strip_tags($match[0]);
		}
		//dbg($error);
		$r['message'] = $error;
	}

	return $r;
}

function adesk_google_analytics() {
	require_once(awebdesk_classes('Zend/Loader.php'));
	$r = array();

	try {
		Zend_Loader::loadClass('Zend_Gdata');
		Zend_Loader::loadClass('Zend_Gdata_AuthSub');
		Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
		Zend_Loader::loadClass('Zend_Http_Client');
		Zend_Loader::loadClass('Zend_Gdata_Query');
		Zend_Loader::loadClass('Zend_Gdata_Feed');
		Zend_Loader::loadClass('Zend_Uri_Http');
		Zend_Loader::loadClass('Zend_Gdata_Analytics');

    $client = Zend_Gdata_AuthSub::getHttpClient($_SESSION['google_analytics_token']);

		$service = new Zend_Gdata_Analytics($client);
		//$service -> setMajorProtocolVersion(3);

    $dimensions = array(
      Zend_Gdata_Analytics_DataQuery::DIMENSION_MEDIUM,
      Zend_Gdata_Analytics_DataQuery::DIMENSION_SOURCE,
      Zend_Gdata_Analytics_DataQuery::DIMENSION_BROWSER_VERSION,
      Zend_Gdata_Analytics_DataQuery::DIMENSION_MONTH,
    );

    $query = $service->newDataQuery()
      ->setProfileId("16171696")
      ->addMetric(Zend_Gdata_Analytics_DataQuery::METRIC_BOUNCES)
      ->addMetric(Zend_Gdata_Analytics_DataQuery::METRIC_VISITS)
      ->addDimension(Zend_Gdata_Analytics_DataQuery::DIMENSION_KEYWORD)
      ->addSort(Zend_Gdata_Analytics_DataQuery::METRIC_VISITS, true)
      ->addSort(Zend_Gdata_Analytics_DataQuery::METRIC_BOUNCES, false)
      ->setStartDate('2011-07-17')
      ->setEndDate('2011-08-16')
      ->setMaxResults(100);

    foreach($dimensions as $dim){
      $query->addDimension($dim);
    }

    $result = $service->getDataFeed($query);

    foreach ($result as $row) {
      dbg($row->getMetric('ga:visits')."\t",1);
      dbg($row->getValue('ga:bounces')."\n");
    }
	}
	catch (Exception $e) {
		$r['succeeded'] = false;
		// provide friendlier error messages
		switch ( $e->getMessage() ) {
			//case 'Security check: Illegal character in filename' :
			//break;
			case 'CAPTCHA challenge issued by server' :
				$error = _a('Incorrect username/password combination');
			break;
			default :
				$error = $e->getMessage();
			break;
		}
		if ( preg_match("/<TITLE>.*<\/TITLE>/", $error, $match) ) {
		  $error = strip_tags($match[0]);
		}
		dbg($error);
		$r['message'] = $error;
	}

}

?>