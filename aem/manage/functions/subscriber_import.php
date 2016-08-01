<?php

require_once(awebdesk_functions('import.php'));
require_once(adesk_admin('functions/subscriber.php'));
require_once awebdesk_functions("log.php");

// these are using sync's ihooks

require_once(awebdesk_functions('sync.php'));

adesk_ihook_define('adesk_import_tplvars',  'ihook_adesk_sync_tplvars');
adesk_ihook_define('adesk_import_header_template',  'ihook_adesk_import_header_template');
adesk_ihook_define('adesk_import_destinations_template',  'ihook_adesk_sync_destinations_template');
adesk_ihook_define('adesk_import_relations',  'ihook_adesk_sync_relations');
adesk_ihook_define('adesk_import_fields',  'ihook_adesk_sync_fields');
adesk_ihook_define('adesk_import_custom_fields',  'ihook_adesk_sync_custom_fields');
adesk_ihook_define('adesk_import_options',  'ihook_adesk_sync_options');
adesk_ihook_define('adesk_import_valid_check',  'ihook_adesk_import_valid_check');
adesk_ihook_define('adesk_import_valid_row',  'ihook_adesk_import_valid_row');

adesk_ihook_define('adesk_import_row',  'ihook_adesk_import_row');
adesk_ihook_define('adesk_import_delete_all',  'ihook_adesk_sync_delete_all');

adesk_ihook_define('adesk_import_row_report',  'ihook_adesk_sync_row_report');

function ihook_adesk_import_row($cfg, $row, $test = false) {
	$cfg["isimported"] = 1;
	return ihook_adesk_sync_row($cfg, $row, $test);
}

function import_relid_change($relids, $type = 'subscribe') {
	$offer = (int)( $type == 'unsubscribe' or $type == 'subscribe' );
	if ( $type != 'unsubscribe' ) $type = 'subscribe';
	if ( !$relids ) $relids = null;
	$lists = list_select_array(null, $relids, 'optinout');
	$so = new adesk_Select();
	$so->push("AND `responder_type` = '$type'");
	//$so->push("AND `status` = 1");
	$r = array(
		'responders' => responder_select_bylist($so, $relids),
		'offeroptin' => 0,
		'offeroptout' => 0,
		'offerresponders' => $offer,
		'offersentresponders' => 0,
	);
	foreach ( $lists as $v ) {
		if ( $v['optin_confirm'] ) $r['offeroptin'] = 1;
		if ( $v['optout_confirm'] ) $r['offeroptout'] = 1;
	}
	$r['offersentresponders'] = count($r['responders']);
/*
	foreach ( $r['responders'] as $v ) {
		if ( $v['responder_offset'] > 0 ) $r['offerResponders'] = 1;
	}
*/
	return $r;
}


function ihook_adesk_import_header_template() {
	if ( (int)adesk_sql_select_one('=COUNT(*)', '#subscriber_import') > 100000 ) {
		adesk_sql_query("TRUNCATE TABLE #subscriber_import");
	} else {
		adesk_sql_delete('#subscriber_import', "`tstamp` < SUBDATE(NOW(), INTERVAL 1 DAY)");
	}
	return 'subscriber_import.header.inc.htm';
}

function ihook_adesk_import_valid_check($r) {
	return isset($GLOBALS['_hosted_account']) || withinlimits('subscriber', limit_count($GLOBALS['admin'], 'subscriber') + $r['rows']);
}

function ihook_adesk_import_valid_row($row) {
	return subscriber_add_valid($row);
}

/* NEW IMPORTER */

function subscriber_import_fileinfo($fp, $delimiter) {
	$rval = array();

	if (feof($fp))
		return $rval;

	$rval["lines"] = 0;
	$five          = array();

	while (!feof($fp)) {
		if (count($five) < 5)
			$five[] = trim(adesk_file_readline($fp));
		else
			adesk_file_readline($fp);

		$rval["lines"]++;
	}

	$rval["delimiter_file"] = "comma";
	if (count($five) > 0)
		$rval["delimiter_file"] = adesk_import_delimiter_guess($five[0]);

	# Now do validation, check for fields.
	$csv = adesk_import_csv2array(implode("\n", $five), $rval["delimiter_file"]);
	$rval["fields"] = adesk_import_columns($csv);
	$rval["valid"] = true;

	if (count($csv) > 0) {
		$first = count($csv[0]);
		foreach ($csv as $csvrow) {
			if (count($csvrow) != $first)
				$rval["valid"] = false;
		}
	}

	rewind($fp);
	return $rval;
}

function responder_select_bylist($so = null, $ids = null) {
	if ($so === null || !is_object($so))
		$so = new adesk_Select;

	if ($ids !== null) {
		$tmp = array_map("intval", explode(",", $ids));
		$ids = implode("', '", $tmp);
		$so->push("AND l.listid IN ('$ids')");
	}
	$so->push("AND c.type = 'responder'");
	$so->push("AND c.status IN (1, 5)");
	return adesk_sql_select_array(campaign_select_query($so), array('cdate', 'sdate', 'ldate'));
}

function subscriber_import_external($config, $post, $external_options = 0) {
	$r = array(
		'succeeded' => false,
		'message' => _a('Unknown Error'),
		'data' => '',
	);
	if ( !$post['external'] ) {
		$r['message'] = _a('External Source not provided.');
		return $r;
	}
	$connection_data = array();
	if ( $post['external'] == 'hd' ) {
		if ( !function_exists('curl_init') ) {
			$r['message'] = _a('PHP cURL extension required.');
			return $r;
		}
		// check input first
		if ( !isset($post['hd_url']) or !adesk_str_is_url($post['hd_url']) ) {
			$r['message'] = _a('Help Desk URL is not valid.');
			return $r;
		}
		if ( !isset($post['hd_user']) or !trim($post['hd_user']) or !isset($post['hd_pass']) ) {
			$r['message'] = _a('Help Desk login credentials must be provided.');
			return $r;
		}

		// just get admin user, so we can obtain the fields
		$params = array(
			'api_user'     => $post['hd_user'],
			'api_pass'     => $post['hd_pass'],
			'api_action'   => 'user_list_group',
			'api_output'   => 'serialize',
			'ids'          => '2',
		);
		$result = adesk_import_helpdesk_users($post, $params);
		if ( isset($result['message']) ) {
		  $r['message'] = $result['message'];
		  return $r;
		}
		//dbg($result);
		$fields_map = array();
		if ( isset($result["rows"][0]) ) {
		  // only looking at the first user returned
		  foreach ($result["rows"][0] as $field => $value) {
        if ( !isset($fields_map[$field]) ) $fields_map[$field] = $field;
		  }
		}
		//dbg($fields_map);
		if ($external_options) {
    	// save connection details to DB
    	$connection_data = array( 'hd_url' => $post['hd_url'], 'hd_user' => $post['hd_user'], 'hd_pass' => base64_encode($post['hd_pass']) );
    	$connection_save = subscriber_import_external_save($post['external'], $connection_data);
		  return $fields_map;
		}
		$filter = ( isset($post["external_options_filters"]) ) ? $post["external_options_filters"] : array();
		$people = array();
    foreach ($result["rows"] as $row) {
      $add = true;
      foreach ($filter as $field => $value) {
        if ( trim($value) ) {
          if ( !preg_match("/" . $value . "/i", $row[$field]) ) $add = false;
        }
      }
      if ($add) $people[] = $row;
    }
    //dbg($people);
		$firstrow = current($people);
		$header = array_keys($fields_map);
		$r['data'] = adesk_array_csv($people, $header, $output = array());
	} elseif ( $post['external'] == 'tactile' ) {
		if ( !function_exists('curl_init') ) {
			$r['message'] = _a('PHP cURL extension required.');
			return $r;
		}
		require_once( awebdesk_functions("json.php") );
		$perpage = 1; // maximum allowed for tactile API - default is 30
		$useragent = 'AwebDesk Email Marketing software';
		$filter = ( isset($post["external_options_filters"]) ) ? $post["external_options_filters"] : "";
		$object = adesk_import_tactile_people($post['tactile_app'], $post['tactile_token'], 1, $perpage, $useragent, $filter);
		//dbg($object);
		// capture any error messages
		if (is_array($object) && $object["message"]) {
			$r['message'] = $object["message"];
			return $r;
		}
		$fields_map = array();
		if ( isset($object -> people) ) {
		  foreach ($object -> people as $person) {
		    $person = get_object_vars($person);
		    if ($person["custom_fields"]) {
		      // get the custom field names to display so users can map to them
		      foreach ($person["custom_fields"] as $field_object) {
                if ( !isset($person[$field_object->name]) ) $person[$field_object->name] = $field_object->name;
		      }
		    }
		    foreach ($person as $field => $value) {
		      if ( !in_array($field, $fields_map) && $field != "custom_fields" && $field != "custom_values" ) {
		        $fields_map[$field] = $field;
		      }
		    }
		  }
		}
		//dbg($fields_map);
		// tactile api only allows certain fields to be filtered upon
		$fields_filter = array( "name" => _a("Name"), "firstname" => _a("First name"), "surname" => _a("Last name") );
		if ($external_options) {
    	// save connection details to DB
    	$connection_data = array( 'tactile_app' => $post['tactile_app'], 'tactile_token' => base64_encode($post['tactile_token']) );
    	$connection_save = subscriber_import_external_save($post['external'], $connection_data);
		  return $fields_filter;
		}
		$people = array();

		if ($object -> people) {
		  $total_pages = $object -> num_pages;
	    $page = 1;
			while ($page <= $total_pages) {
			  foreach ($object -> people as $person) {
					$person = get_object_vars($person);
					//dbg($person,1);
					$person2 = array();
					if ( isset($person["tags"]) ) {
					  $person["tags"] = implode(", ", $person["tags"]);
					}
					else {
					  $person["tags"] = "";
					}
					if ($person["custom_fields"]) {
					  foreach ($person["custom_fields"] as $field_object) {
					    if ( !isset($person[$field_object->name]) ) {
					      // get custom field value
					      $field_object_value = "";
					      if ( isset($person["custom_values"]) && $person["custom_values"] ) {
					        foreach ($person["custom_values"] as $value_object) {
					           if ($field_object -> id == $value_object -> field_id) {
					             $field_object_value = $value_object -> value;
					           }
					           else {
					             $field_object_value = "";
					           }
					        }
					      }
					      $person[$field_object->name] = $field_object_value;
					    }
					  }
					}
					foreach ($person as $field => $value) {
					  if ( !isset($person2[$field]) && $field != "custom_fields" && $field != "custom_values" ) {
					    $person2[$field] = $value;
					  }
					}
					//dbg($person2,1);
					$people[] = $person2;
			  }
				// if there are more pages to process
				if ($page < $total_pages) {
					// get the next page data
					// reset $object to the data from the next page
					$object = adesk_import_tactile_people($post['tactile_app'], $post['tactile_token'], $page + 1, $perpage, $useragent, $filter);
					//dbg($object);
					// capture any error messages
					if (is_array($object) && $object["message"]) {
						$r['message'] = $object["message"];
						return $r;
					}
				}
			  $page += 1;
			}
	  }

		//dbg($people);
		$firstrow = current($people);
		$header = array_keys($fields_map);
		$r['data'] = adesk_array_csv($people, $header, $output = array());
	} elseif ( $post['external'] == 'capsule' ) {
		if ( !function_exists('curl_init') ) {
			$r['message'] = _a('PHP cURL extension required.');
			return $r;
		}
		if ( !function_exists('simplexml_load_string') ) {
			$r['message'] = _a('PHP SimpleXML extension required.');
			return $r;
		}
		if ( !isset($post['capsule_app']) or !trim($post['capsule_app']) ) {
			$r['message'] = _a('Capsule application must be provided.');
			return $r;
		}
		if ( !isset($post['capsule_token']) or !trim($post['capsule_token']) ) {
			$r['message'] = _a('Capsule token must be provided.');
			return $r;
		}
		$filter = "";
		if ( isset($post["external_options_filters"]) && $post["external_options_filters"] ) {
		  foreach ($post["external_options_filters"] as $field => $value) {
		    if ( trim($value) ) {
		      $filter = "?q=" . urlencode($value);
		    }
		  }
		}
		$url = 'https://' . $post['capsule_app'] . '.capsulecrm.com/api/party' . $filter;
		$request = curl_init($url);
		curl_setopt($request, CURLOPT_HEADER, 0);
		curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($request, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($request, CURLOPT_USERAGENT, 'AwebDesk Email Marketing software');
		curl_setopt($request, CURLOPT_USERPWD, $post['capsule_token'] . ':x');
		$response = curl_exec($request);
		curl_close($request);
		// any errors returned?
		if ( preg_match('/Error report/', $response) ) {
			// grab first <h1> from the result string (its HTML returned)
			// try to grab the error from the HTML return string
			$h1 = preg_match('|<h1>[^</]*|', $response, $h1_matches);
			if ($h1_matches && $h1_matches[0] && $h1_matches[0] != '') {
				$error = substr($h1_matches[0], 4);
			}
			$r['message'] = $error;
			return $r;
		}
		$object = simplexml_load_string($response);
		//dbg($object);
		$fields_map = array();
		// this will obtain all unique fields used - loops through all records and saves the unique ones so we capture all
		foreach ($object -> person as $person) {
		  if ( isset($person->contacts->email->emailAddress) ) $person->email = $person->contacts->email->emailAddress;
		  foreach ($person as $field => $value) {
		    if ( !in_array($field, $fields_map) ) $fields_map[$field] = $field;
		  }
		}
		ksort($fields_map);
		// only show the fields the API actually searches
		// normally we'd show the same as $fields_map for filters, but the API only searches certain fields
		$fields_filter = array( "search" => _a("Name, Phone, or custom fields") );
		if ($external_options) {
    	// save connection details to DB
    	$connection_data = array( 'capsule_app' => $post['capsule_app'], 'capsule_token' => base64_encode($post['capsule_token']) );
    	$connection_save = subscriber_import_external_save($post['external'], $connection_data);
		  return $fields_filter;
		}
		$people = array();
		foreach ($object -> person as $person) {
		    if ( isset($person->contacts->email->emailAddress) ) $person->email = $person->contacts->email->emailAddress;
			$person = get_object_vars($person);
			foreach ($fields_map as $k => $v) {
			   if ( !isset($person[$k]) ) $person[$k] = ""; // set to blank if not there
			}
			ksort($person); // to ensure that the keys/columns line up in CSV
			$people[] = $person;
		}
		//dbg($people);
		$firstrow = current($people);
		$header = array_keys($fields_map);
		$r['data'] = adesk_array_csv($people, $header, $output = array());
	} elseif ( $post['external'] == 'microsoftcrm' ) {
		if ( !isset($post['microsoftcrm_username']) or !trim($post['microsoftcrm_username']) ) {
			$r['message'] = _a('Microsoft CRM username must be provided.');
			return $r;
		}
		if ( !isset($post['microsoftcrm_password']) or !trim($post['microsoftcrm_password']) ) {
			$r['message'] = _a('Microsoft CRM password must be provided.');
			return $r;
		}
		if ( !isset($post['microsoftcrm_organization']) or !trim($post['microsoftcrm_organization']) ) {
			$r['message'] = _a('Microsoft CRM organization must be provided.');
			return $r;
		}
		if ( !isset($post['microsoftcrm_domain']) or !trim($post['microsoftcrm_domain']) ) {
			$r['message'] = _a('Microsoft CRM domain must be provided.');
			return $r;
		}
		require_once( awebdesk_classes('microsoft.crm.php') );
		$service = new MSCrmIFD();
		$service->usr = $post['microsoftcrm_username'];
		$service->pwd = $post['microsoftcrm_password'];
		$service->domain = $post['microsoftcrm_domain'];
		$service->org = $post['microsoftcrm_organization'];
		//$service->crmHost = 'crm.example.ex:5555';
		$service->crmHost = $post['microsoftcrm_domain'];
		$service->getAccess();
	} elseif ( $post['external'] == 'zohocrm' ) {
		if ( !function_exists('curl_init') ) {
			$r['message'] = _a('PHP cURL extension required.');
			return $r;
		}
		if ( !function_exists('simplexml_load_string') ) {
			$r['message'] = _a('PHP SimpleXML extension required.');
			return $r;
		}
		if ( !isset($post['zohocrm_apikey']) or !trim($post['zohocrm_apikey']) ) {
			$r['message'] = _a('Zoho CRM API key must be provided.');
			return $r;
		}
		if ( !isset($post['zohocrm_username']) or !trim($post['zohocrm_username']) ) {
			$r['message'] = _a('Zoho CRM username must be provided.');
			return $r;
		}
		if ( !isset($post['zohocrm_password']) or !trim($post['zohocrm_password']) ) {
			$r['message'] = _a('Zoho CRM password must be provided.');
			return $r;
		}
		$request_login = curl_init('https://accounts.zoho.com/login?servicename=ZohoCRM&FROM_AGENT=true&LOGIN_ID=' . $post['zohocrm_username'] . '&PASSWORD=' . $post['zohocrm_password']);
		curl_setopt($request_login, CURLOPT_HEADER, 0);
		curl_setopt($request_login, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($request_login, CURLOPT_SSL_VERIFYPEER, TRUE);
		$response_login = curl_exec($request_login);
		curl_close($request_login);
		//dbg($response_login);
		$response_login_lines = explode("\n", $response_login);
		foreach ($response_login_lines as $line) {
			if ( preg_match("/RESULT=/", $line) ) {
				$response_login_result = substr($line, 7);
			}
			if ( preg_match("/TICKET=/", $line) ) {
				$response_login_ticket = substr($line, 7);
			}
		}
		if ($response_login_result == "TRUE") {
		  $perpage = 200;
		  $filter = ( isset($post["external_options_filters"]) ) ? $post["external_options_filters"] : "";
		  $fields_map = array();
		  $object = adesk_import_zohocrm_records($post['zohocrm_apikey'], $response_login_ticket, 1, $perpage, $filter, $fields_map, false);
			//dbg($object);
			if ($object -> result -> Contacts) {
			  foreach ($object -> result -> Contacts -> row as $row) {
			    $row = get_object_vars($row);
			    foreach ($row["FL"] as $fieldid => $value) {
            if ( is_object($value) ) {
              if ( $value -> attributes() ) {
                $value = get_object_vars($value);
                if ( !isset($fields_map[ $value["@attributes"]["val"] ]) ) {
                  $fields_map[$fieldid] = $value["@attributes"]["val"];
                }
              }
            }
			    }
			  }
			}
			//dbg($fields_map);
			if ($external_options) {
				// save connection details to DB
				$connection_data = array( 'zohocrm_username' => $post['zohocrm_username'], 'zohocrm_password' => base64_encode($post['zohocrm_password']), 'zohocrm_apikey' => base64_encode($post['zohocrm_apikey']) );
				$connection_save = subscriber_import_external_save($post['external'], $connection_data);
				return $fields_map;
			}
			// re-declare while passing LIBXML_NOCDATA flag to simplexml_load_string()
			$object = adesk_import_zohocrm_records($post['zohocrm_apikey'], $response_login_ticket, 1, $perpage, $filter, $fields_map);
			//dbg($object);
			$people = array();
			if ($object -> result -> Contacts) {
				foreach ($object -> result -> Contacts -> row as $row) {
					$row = get_object_vars($row);
					//dbg($row);
					$person = array();
					foreach ($row["FL"] as $fieldid => $value) {
            if ( isset($fields_map[$fieldid]) ) $person[$fieldid] = $value;
					}
					$people[] = $person;
				}
			}
			else {
				$r['message'] = _a('There was an error attempting to retrieve the data. Please try again.');
				return $r;
			}
			//dbg($people);
			if (!$people) {
				$r['message'] = _a('No records were found.');
				return $r;
			}
			$firstrow = current($people);
			$header = $fields_map;
			$r['data'] = adesk_array_csv($people, $header, $output = array());
		}
		else {
			$r['message'] = _a("Login failed. Please verify your username and password.");
			return $r;
		}

	} elseif ( $post['external'] == 'sugarcrm' ) {
		if ( (int)PHP_VERSION < 5 ) {
			$r['message'] = _a("This external source requires PHP 5");
			return $r;
		}
		if ( !class_exists('SoapClient') ) {
			$r['message'] = _a("This external source requires PHP SOAP extension");
			return $r;
		}
		if ( !isset($post['sugarcrm_url']) or !trim($post['sugarcrm_url']) ) {
			$r['message'] = _a('SugarCRM URL must be provided.');
			return $r;
		}
		if ( !isset($post['sugarcrm_username']) or !trim($post['sugarcrm_username']) ) {
			$r['message'] = _a('SugarCRM username must be provided.');
			return $r;
		}
		if ( !isset($post['sugarcrm_password']) or !trim($post['sugarcrm_password']) ) {
			$r['message'] = _a('SugarCRM password must be provided.');
			return $r;
		}
		require_once(awebdesk_functions('sugarcrm.php'));
		$connection = adesk_sugarcrm_connect($post);
		if ($connection['error']) {
			$r['message'] = $connection['error'];
			return $r;
		}
		//dbg($connection);
		$contacts_fields = array('id');
		$object = $connection['client']->get_entry_list($connection['login']->id, 'Contacts', '', '', 0, $contacts_fields, 1, false);
		$fields_map = array();
		foreach ($object->field_list as $field) {
      if ( !in_array($field->label, $fields_map) ) $fields_map[$field->name] = preg_replace("/:/", "", $field->label);
		}
		if ($external_options) {
			// save connection details to DB
			$connection_data = array( 'sugarcrm_url' => $post['sugarcrm_url'], 'sugarcrm_username' => $post['sugarcrm_username'], 'sugarcrm_password' => base64_encode($post['sugarcrm_password']) );
			$connection_save = subscriber_import_external_save($post['external'], $connection_data);
		  return $fields_map;
		}
		ksort($fields_map);
		//dbg($fields_map,1);
		$contacts_fields = array_keys($fields_map);
		$filter = ( isset($post["external_options_filters"]) ) ? $post["external_options_filters"] : array();
		$object = $connection['client']->get_entry_list($connection['login']->id, 'Contacts', '', '', 0, $contacts_fields, 500, false);
		$people = array();
		foreach ($object->entry_list as $entry) {
		  //dbg($entry);
		  $person = array();
		  foreach ($entry->name_value_list as $name_value_object) {
		     // make sure it's in the mapped fields array before inserting as a field for each row
		     if ( isset($fields_map[$name_value_object->name]) ) {
           $person[$name_value_object->name] = $name_value_object->value;
		     }
		  }
		  foreach ($fields_map as $field => $label) {
		     // check that all fields from $fields_map are present in person row - if not, add in blank value so CSV columns line up
		     if ( !isset($person[$field]) ) {
		       $person[$field] = "";
		     }
		  }
		  $add = true;
		  foreach ($filter as $field => $value) {
		    if ( trim($value) ) {
		      if ( !preg_match("/" . $value . "/i", $person[$field]) ) $add = false;
		    }
		  }
		  ksort($person);
		  if ($add) $people[] = $person;
		}
    //dbg($people[0]);
		if (!$people) {
			$r['message'] = _a('No records were found.');
			return $r;
		}
		$firstrow = current($people);
		$header = array_keys($fields_map);
		$r['data'] = adesk_array_csv($people, $header, $output = array());
	} elseif ($post['external'] == 'salesforce') {
		if ( (int)PHP_VERSION < 5 ) {
			$r['message'] = _a("This external source requires PHP 5");
			return $r;
		}
		if ( !class_exists('SoapClient') ) {
			$r['message'] = _a("This external source requires PHP SOAP extension");
			return $r;
		}
		require_once(awebdesk_functions('salesforce.php'));
		$connection = adesk_salesforce_connect($post);
		if ( is_array($connection) ) {
			$r['message'] = $connection['message'];
			return $r;
		}
		$object = $connection->describeSObject('Contact');
		$fields_map = array();
		foreach ($object -> fields as $field) {
		  if ( !in_array($field->name, $fields_map) ) $fields_map[$field->name] = $field->name;
		}
		//dbg($fields);
		if ($external_options) {
    	  // save connection details to DB
    	  $connection_data = array(
    			'salesforce_username' => $post['salesforce_username'],
    			'salesforce_password' => base64_encode($post['salesforce_password']),
					'salesforce_token' => base64_encode($post['salesforce_token']),
    	  );
		  $connection_save = subscriber_import_external_save($post['external'], $connection_data);
		  return $fields_map;
		}
		$where = array();
		if ( isset($post["external_options_filters"]) && $post["external_options_filters"] ) {
  		  $filters = $post["external_options_filters"];
  		  foreach ($filters as $field => $value) {
  		    if ($value) {
  		      $value = str_replace("'", "\'", $value);
  		      $where[] = $field . " = '" . $value . "'";
  		    }
  		  }
		}
		$query = "SELECT " . implode(", ", $fields_map) . " FROM Contact";
		if ($where) $query .= " WHERE " . implode(" AND ", $where);
		//dbg($query);
		$response = $connection->query($query);
		$queryResult = new QueryResult($response);
		$people = array();
		foreach ($queryResult->records as $record) {
			$contact = get_object_vars($record);
			$contact2 = array("Id" => $contact["Id"]); // Id appears outside of the fields array
			$contact = get_object_vars($contact["fields"]);
			$contact = array_merge($contact2, $contact);
			$people[] = $contact;
		}
		//dbg($people);
		if (!$people) {
			$r['message'] = _a('No records were found.');
			return $r;
		}
		$firstrow = current($people);
		$header = array_keys($fields_map);
		$r['data'] = adesk_array_csv($people, $header, $output = array());
	} elseif ( $post['external'] == 'freshbooks' ) {
		if ( !function_exists('curl_init') ) {
			$r['message'] = _a('PHP cURL extension required.');
			return $r;
		}
		if ( !function_exists('simplexml_load_string') ) {
			$r['message'] = _a('PHP SimpleXML extension required.');
			return $r;
		}
		$perpage = 100; // maximum allowed for freshbooks API
		$useragent = 'AwebDesk Email Marketing software';
		$filter = ( isset($post["external_options_filters"]) ) ? $post["external_options_filters"] : array();
		// obtain page 1 of data
		$object = adesk_import_freshbooks_clients("", "", 1, $perpage, $useragent, $filter);
		//dbg($object);
		// capture any error messages
		if (is_array($object) && $object["message"]) {
			$r['message'] = $object["message"];
			return $r;
		}
		$fields_map = array();
		if ( isset($object->clients->client[0]) ) {
		  // just use the first person to obtain the fields
		  $person0 = get_object_vars($object->clients->client[0]);
		  foreach ($person0 as $field => $value) {
		    $fields_map[$field] = $field;
		  }
		}
		// freshbooks API only allows certain fields to be filtered on
		$fields_filter = array("email" => "Email", "username" => "Username");
		if ($external_options) {
		  return $fields_filter;
		}
		$people = array();
		if ( isset($object->clients) && isset($object->clients->client) ) {
			$object = get_object_vars($object->clients);
			$attributes = $object["@attributes"];
			$page = 1;
			while ($page <= $attributes["pages"]) {
				if ( is_array($object["client"]) ) {
					// more than one contact returned for this page
					// $object["client"] is an array here
					foreach ($object["client"] as $person) {
						$person = get_object_vars($person);
						$people[] = $person;
					}
				}
				else {
					// just one contact returned for this page - IE: either perpage is 1, or we are on the last page, with only one contact remaining
					// $object["client"] is an object here
					$person = get_object_vars($object["client"]);
					$people[] = $person;
				}
				// if there are more pages to process
				if ($page < $attributes["pages"]) {
					// get the next page data
					// reset $object to the data from the next page
					$object = adesk_import_freshbooks_clients("", "", $page + 1, $perpage, $useragent, $filter);
					// capture any error messages
					if (is_array($object) && $object["message"]) {
						$r['message'] = $object["message"];
						return $r;
					}
					if ( isset($object->clients) && isset($object->clients->client) ) {
						$object = get_object_vars($object->clients);
					}
				}
				// always increment so we know when to stop this loop
				$page = $page + 1;
			}
		}
		//dbg($people);
		if (!$people) {
			$r['message'] = _a('No records were found.');
			return $r;
		}
		$firstrow = current($people);
		$header = array_keys($fields_map);
		$r['data'] = adesk_array_csv($people, $header, $output = array());
	} elseif ( $post['external'] == 'google_spreadsheets' ) {
	  if ( (float)PHP_VERSION < 5.2 ) {
			$r['message'] = _a("This external source requires PHP 5.2 or greater");
			return $r;
		}
    require_once( awebdesk('functions/google.php') );
		$import_submit = adesk_google_import_spreadsheets($post, $external_options);
		if ($external_options) return $import_submit;
		if (!$import_submit['succeeded']) return $import_submit;
    $r['data'] = $import_submit['data'];
	} elseif ( $post['external'] == 'batchbook' ) {
		if ( !function_exists('curl_init') ) {
			$r['message'] = _a('PHP cURL extension required.');
			return $r;
		}
		$import_submit = adesk_import_batchbook($post, $external_options);
		if ($external_options) return $import_submit;
    $r['data'] = $import_submit;
	} elseif ($post['external'] == 'zendesk') {
		if ( !function_exists('curl_init') ) {
			$r['message'] = _a('PHP cURL extension required.');
			return $r;
		}
		$import_submit = adesk_import_zendesk_users($post, $external_options);
		if ( isset($import_submit["error"]) && (int)$import_submit["error"] ) {
			$r['message'] = $import_submit["message"];
			return $r;
		}
		if ($external_options) return $import_submit;
    $r['data'] = $import_submit;
	} elseif ( $post['external'] == 'google_contacts' ) {
	  if ( (float)PHP_VERSION < 5.2 ) {
			$r['message'] = _a("This external source requires PHP 5.2 or greater");
			return $r;
		}
		require_once( awebdesk('functions/google.php') );
		$import_submit = adesk_google_import_contacts($post, $external_options);
		if ($external_options) return $import_submit;
		if (!$import_submit['succeeded']) return $import_submit;
    $r['data'] = $import_submit['data'];
	} elseif ( $post['external'] == 'hr' ) {
		// check input first
		if ( !isset($post['hr_url']) or !adesk_str_is_url($post['hr_url']) ) {
			$r['message'] = _a('Highrise URL is not valid.');
			return $r;
		}
		if ( !isset($post['hr_api']) or !trim($post['hr_api']) ) {
			$r['message'] = _a('Highrise API token must be provided.');
			return $r;
		}
		// connect and fetch here
		if ( preg_match('/^http:\/\//i', $post['hr_url']) ) {
			$post['hr_url'] = preg_replace('/^http:\/\//i', 'https://', $post['hr_url']);
		}

		if ( !class_exists('SimpleXMLElement') ) return;

		$escurl = adesk_sql_escape($post['hr_url']);

		// typical REST request
		require(awebdesk_classes('Highrise.class.php'));
		$highrise = new Highrise($post['hr_url'], $post['hr_api'], 'x', 'xml');
		$response = $highrise->getPeople();

		if ( $response['status'] == '401 Unauthorized' ) {
			$r['message'] = _a('Highrise data could not be returned:') . ' ' . $response['status'];
			return $r;
		} elseif ( $response['status'] == '403 Forbidden' ) {
			$r['message'] = _a('Highrise data could not be returned:') . ' ' . $response['status'];
			return $r;
		} elseif ( $response['status'] == '404 Not Found' ) {
			$r['message'] = _a('Highrise data could not be returned:') . ' ' . $response['status'];
			return $r;
		} elseif ( !adesk_str_instr('<people', $response['body']) ) {
			$r['message'] = _a('Highrise data could not be found.');
			return $r;
		}
		$highrise->setFormat('simplexml');

		$response = $highrise->getPeople();
		if ( !isset($response['body']) || !is_object($response['body']) ) {
			$r['message'] = _a('Highrise data could not be returned. Please try again shortly.');
			return $r;
		}
		$fields_contactdata_default = array("instant-messengers", "twitter-accounts", "addresses", "phone-numbers", "email-addresses", "web-addresses");
		$fields_custom = array();
		$fields_map = array();
		//dbg($response['body']);
		foreach ( $response['body']->person as $person ) {
			$person = get_object_vars($person);
			//dbg($person);
			foreach ($person as $field => $value) {
				// there are sub-fields here
				if ($field == "contact-data") {
					$contact_data = get_object_vars($value);
					foreach ($contact_data as $field2 => $value2) {
						$value2 = get_object_vars($value2);
					  if ( in_array($field2, $fields_contactdata_default) ) {
					    // default contact fields
						  $value2_keys = array_keys($value2);
						  if ( isset($value2_keys[1]) ) {
						    if ( !isset($fields_map[ $value2_keys[1] ]) ) $fields_map[ $value2_keys[1] ] = $value2_keys[1];
						  }
						  else {
						    // just "@attributes" array key is present
						    //if ( !isset($fields_map[$field2]) ) $fields_map[$field2] = $field2;
						  }
					  }
					  else {
					    // $field2 is a custom field
					    $fields_custom[] = $field2;
					    if ( !isset($fields_map[$field2]) ) $fields_map[$field2] = $field2;
					  }
					}
				}
				// other array keys outside of "contact-data"
				if ( !isset($fields_map[$field]) && $field != "contact-data" ) $fields_map[$field] = $field;
			}
		}
		ksort($fields_map);
		//dbg($fields_map);

		// these are the allowed search fields to use with Highrise API, "List By Search Criteria"
		$fields_filter = array( "city" => "City", "state" => "State", "zip" => "Zip Code", "country" => "Country", "phone" => "Phone", "email" => "Email", );
		if ($external_options) {
			// save connection details to DB
			$connection_data = array( 'hr_url' => $post['hr_url'], 'hr_api' => base64_encode($post['hr_api']) );
			$connection_save = subscriber_import_external_save($post['external'], $connection_data);
		  return $fields_filter;
		}

		$filter = ( isset($post["external_options_filters"]) ) ? $post["external_options_filters"] : "";
		if ($filter) {
			$response = $highrise->getPeopleSearchCriteriaResults($filter);
		}
		else {
			$response = $highrise->getPeople();
		}

		$people = array();
		foreach ( $response['body']->person as $v ) {
			$tmp  = get_object_vars($v);
			//dbg($tmp,1);
			$rval = array();

			foreach ($tmp as $field => $value) {
				if ( is_object($value) ) {
					if ($field == "contact-data") {
						foreach ($value as $field2 => $value2) {
							$value2 = get_object_vars($value2);
						  if ( in_array($field2, $fields_contactdata_default) ) {
						    // default contact fields
								$value2_keys = array_keys($value2);
								if ( isset($value2_keys[1]) ) {
  								switch ($value2_keys[1]) {
  									case "instant-messenger" :
  									  $instant_messenger = get_object_vars($value2["instant-messenger"]);
  										$rval["instant-messenger"] = $instant_messenger["address"];
  									break;
  									case "twitter-account" :
  									  $twitter_account = get_object_vars($value2["twitter-account"]);
  										$rval["twitter-account"] = $twitter_account["username"];
  									break;
  									case "address" :
  										$rval["address"] = preg_replace("/\n+/", ", ", $value2["address"]->street) . ", " . $value2["address"]->city . ", " . $value2["address"]->state . " " . $value2["address"]->zip;
  									break;
  									case "phone-number" :
  									  if ( is_object($value2["phone-number"]) ) {
  									    // only one phone number saved for this contact
  									    $phone_number = get_object_vars($value2["phone-number"]);
  									  }
  									  elseif ( is_array($value2["phone-number"]) ) {
  									    // more than one phone number saved for this contact.
  									    // use the first one found - (need to add filter for this)
  									    $phone_number = get_object_vars($value2["phone-number"][0]);
  									  }
  										$rval["phone-number"] = $phone_number["number"];
  									break;
  									case "email-address" :
  								  	if ( is_object($value2["email-address"]) ) {
  									    // only one email address saved for this contact
  									    $email_address = get_object_vars($value2["email-address"]);
  									  }
  									  elseif ( is_array($value2["email-address"]) ) {
  									    // more than one email address saved for this contact.
  									    // use the first one found - (need to add filter for this)
  									    $email_address = get_object_vars($value2["email-address"][0]);
  									  }
  										$rval["email-address"] = $email_address["address"];
  									break;
  									case "web-address" :
  									  $web_address = get_object_vars($value2["web-address"]);
  										$rval["web-address"] = $web_address["url"];
  									break;
  								}
								}
								else {
  						    // just "@attributes" array key is present
  						    if ( !isset($rval[$field2]) ) $rval[$field2] = "";
								}
						  }
						  else {
						    // custom fields
						    $rval[$field2] = $value2["value"];
						  }
						}
					}
					else {
						$rval[$field] = "";
					}
				}
				else {
					$rval[$field] = $value;
				}
				// handle custom fields - insert into array any missing ones (if they dont have a value for it, it wont show up in row)
				foreach ($fields_custom as $field_custom) {
				  if ( !isset($rval[$field_custom]) ) $rval[$field_custom] = "";
				}
			}
      ksort($rval);
			$people[] = $rval;
		}
		//dbg( array_keys($fields_map), 1 );
		//dbg($people);
		if ( !$people ) {
			$r['message'] = _a('No records were found.');
			return $r;
		}
		$firstrow = current($people);
		$header = array_keys($fields_map);
		$r['data'] = adesk_array_csv($people, $header, $output = array());
	} else {
		$r['message'] = _a('External Source not found.');
		return $r;
	}

	$r['succeeded'] = true;
	$r['message'] = _a('External Source successfully fetched.');

	return $r;
}

// detects various server requirements and returns the supported external connectors
function subscriber_import_external_sources($justcheck = false) {
	$admin = adesk_admin_get();
	// start out supporting them all
	// "form_values_decode" is the fields that we encode when saving to database, so we know which ones to decode on the way out
	$r = array(
		//"hd" => array( "supported" => 1, "image" => "ac-help.gif", "form_values_decode" => array("hd_pass") ),
		"capsule" => array( "supported" => 1, "image" => "capsule.jpg", "form_values_decode" => array("capsule_token") ),
		"freshbooks" => array( "supported" => 1, "image" => "freshbooks.gif", "form_values_decode" => array("freshbooks_apikey") ),
		"google_contacts" => array( "supported" => 1, "image" => "google.gif", "form_values_decode" => array() ),
		"google_spreadsheets" => array( "supported" => 1, "image" => "google-docs.jpg", "form_values_decode" => array() ),
		"hr" => array( "supported" => 1, "image" => "highrise.gif", "form_values_decode" => array("hr_api") ),
		"salesforce" => array( "supported" => 1, "image" => "salesforce.gif", "form_values_decode" => array("salesforce_password", "salesforce_token") ),
		"sugarcrm" => array( "supported" => 1, "image" => "sugar.gif", "form_values_decode" => array("sugarcrm_password") ),
		"zohocrm" => array( "supported" => 1, "image" => "zohocrm.jpg", "form_values_decode" => array("zohocrm_password", "zohocrm_apikey") ),
		"tactile" => array( "supported" => 1, "image" => "tactile.jpg", "form_values_decode" => array("tactile_token") ),
		"batchbook" => array( "supported" => 1, "image" => "batchbook.jpg", "form_values_decode" => array("batchbook_token") ),
		"zendesk" => array( "supported" => 1, "image" => "zendesk.png", "form_values_decode" => array("zendesk_password") ),
	);
	foreach ($r as $k => $v) {
		$connection_saved = adesk_sql_select_row("SELECT * FROM #subscriber_import_service WHERE userid = '" . $GLOBALS["admin"]["id"] . "' AND service = '$k'");
		if ($connection_saved) {
			$connection_data = unserialize($connection_saved["connection_data"]);
			foreach ($connection_data as $field => $value) {
				if ( in_array($field, $v["form_values_decode"]) ) $value = base64_decode($value);
				$r[$k]["form_values"][$field] = $value;
			}
		}
	}
	// this array is just for individual server requirements
	// this way if a user wants to know which things they are missing, amongst ALL external source requirements
	$server = array(
		"php5" => array( "supported" => 1, "name" => _a("PHP 5") ),
		"curl" => array( "supported" => 1, "name" => _a("cURL") ),
		"simplexml" => array( "supported" => 1, "name" => _a("SimpleXML") ),
		"soap" => array( "supported" => 1, "name" => _a("SOAP extension") ),
		"dom" => array( "supported" => 1, "name" => _a("DOM extension") ),
		"brand_links" => array( "supported" => 1, "name" => _a("Product links") . " " . "(" . _a("on") . " <a href='desk.php?action=design'>" . _a("Design Settings page") . "</a> - '" . _a("Hide product links") . "' " . _a("checkbox") . ")" ),
	);
	if ( adesk_site_hosted_rsid() ) {
		$server["brand_links"]["name"] = _a("Product links");
	}
	// check for requirements
	if ( !function_exists('curl_init') ) {
		$server["curl"]["supported"] = $r["google_contacts"]["supported"] = $r["google_spreadsheets"]["supported"] = $r["batchbook"]["supported"] = $r["tactile"]["supported"] = $r["hd"]["supported"] = $r["capsule"]["supported"] = $r["freshbooks"]["supported"] = $r["zohocrm"]["supported"] = 0;
	}
	if ( (int)PHP_VERSION < 5 ) {
		$server["php5"]["supported"] = $r["google_contacts"]["supported"] = $r["google_spreadsheets"]["supported"] = $r["salesforce"]["supported"] = $r["sugarcrm"]["supported"] = 0;
	}
	if ( !function_exists('simplexml_load_string') ) {
		$server["simplexml"]["supported"] = $r["capsule"]["supported"] = $r["freshbooks"]["supported"] = $r["hr"]["supported"] = $r["zohocrm"]["supported"] = 0;
	}
	if ( !class_exists('SoapClient') ) {
		$server["soap"]["supported"] = $r["salesforce"]["supported"] = $r["sugarcrm"]["supported"] = 0;
	}
	if ( !$admin["brand_links"] ) {
		$server["brand_links"]["supported"] = $r["hd"]["supported"] = 0;
	}
	if ( !defined('XML_ELEMENT_NODE') ) {
    $server["dom"]["supported"] = $r["google_spreadsheets"]["supported"] = 0;
	}
	if ($justcheck) {
		return $server;
	}
	else {
		return $r;
	}
}

function subscriber_import_external_save($service, $connection_data) {
	// save API connection details to DB
	$exists = adesk_sql_select_one("SELECT id FROM #subscriber_import_service WHERE userid = '" . $GLOBALS["admin"]["id"] . "' AND service = '$service'");
	if (!$exists) {
		// add
		$insert = array(
			"userid" => $GLOBALS["admin"]["id"],
			"service" => $service,
			"connection_data" => serialize($connection_data),
		);
		$insert = adesk_sql_insert("#subscriber_import_service", $insert);
	}
	else {
		// update
		$update = array(
			"connection_data" => serialize($connection_data),
		);
		$update = adesk_sql_update("#subscriber_import_service", $update, "id = '$exists'");
	}
}

function subscriber_import_run($post, $test = false, $offset = 0, $prepareOnly = false) {

	adesk_import_log_init($post);
	$admin = adesk_admin_get();
	$oldadmin = null;

	if ( isset($post['userid']) ) {
		if ( $admin['id'] != $post['userid'] ) {
			$oldadmin = $admin;
			$GLOBALS['admin'] = $admin = adesk_admin_get_totally_unsafe($post['userid']);
		}
	}

	if ( isset($post['process_id']) ) {
		adesk_process_update($post['process_id'], false);
		adesk_import_log_store("\nPicking up Import Job (process #$post[process_id]) by user $admin[username] at $offset\n");
	} else {
		$date = date('Y-m-d H:i:s');
		adesk_import_log_store("\nStarting Import Job at $date by user $admin[username]\n");
	}

	// set output to true
	if ( !defined('adesk_IMPORT_PRINT') ) define('adesk_IMPORT_PRINT', 1);
	// print javascript
	$charset = _i18n("utf-8");
	$prehtml = "<meta http-equiv='Content-Type' content='text/html; charset=$charset' />\n";
	$prehtml .= '
		<script>
			function adesk_dom_toggle_display(id, val) {
				document.getElementById(id).style.display = ( document.getElementById(id).style.display == val ? "none" : val );
			}
		</script>
		<style>
		div.adesk_help {
			z-index: 999;
			/*display: none;*/
			position:absolute;
			border: 1px solid #B4CDE6;
			padding: 10px;
			width:200px;
			margin-top:6px;
			font-size:10px;
			background:#F0F6FB;
			color:#333333;
		}
		.adesk_mapped_column {
			background-color: #ccc;
		}
		</style>
		<link href="css/default.css" rel="stylesheet" type="text/css" />

	';
	adesk_import_log_comment($prehtml);
	if ( !( defined('adesk_IMPORT_PRINT') and adesk_IMPORT_PRINT ) ) adesk_flush($prehtml);
	if ( $test ) {
		//adesk_import_log_comment(_a('Testing Import'));
	} else {
		//adesk_import_log_comment(_a('Starting Import'));
	}

	$r = subscriber_import_src($post, true);
	// if didn't even connect, return
	if ( !$r['succeeded'] ) {
		if ( $oldadmin ) $GLOBALS['admin'] = $oldadmin;
		return $r;
	}

	if (isset($_POST["import_file"])) {
		$post["import_file"] = $_POST["import_file"];
		//$post["type"]        = $_POST["type"];
	}

	// default values
	$r['succeeded'] = false;
	$r['failed'] = 0;
	$r['found'] = $total = $r['lines'];
	$r['imported'] = 0;
	$r['failedrows'] = array();
	$r['importedrows'] = array();

	$useProcesses = function_exists('adesk_process_create');
	// this process id
	if ( !isset($post['process_id']) ) {
		if ( $useProcesses ) {
			// comming from form submission, has action param here
			if (isset($r['delimiter_file']))
				$post['delimiter_file'] = $r['delimiter_file'];
			$post['process_id'] = adesk_process_create(adesk_http_param('action'), $r['found'], $post, false, '0000-00-00 00:00:00');
			adesk_process_setdata($post['process_id'], $post);
			/*
			if ( !$test and $prepareOnly ) {
				adesk_process_spawn(array('id' => $post['process_id'], 'stall' => 5 * 60));
			}
			*/
		} else {
			// old style - KB3
			$post['process_id'] = rand('100000', '900000');// setting a random process id
		}
	}
	$r['process_id'] = $post['process_id'];

	if ( $useProcesses ) {
		// autoupdate
		//$admin = adesk_admin_get();
		//$secondInterval = ( isset($admin['autoupdate']) ? $admin['autoupdate'] : 60 );
		$secondInterval = 3;

		$callback = ( !$test and $prepareOnly ) ? "parent.import_progressbar_callback" : "null";
		adesk_import_log_comment(
			'
				<script>//alert(\'process: ' . $r['process_id'] . '\');
					if (parent && parent.adesk_progressbar_register && parent.document.getElementById("progressBar")){
						parent.adesk_progressbar_register("progressBar", "' . $r['process_id'] . '", 0, ' . $secondInterval . ', true, ' . $callback . ');
						parent.processID = "' . $r['process_id'] . '";
						if ( parent.document.getElementById("report_count") ) {
							parent.document.getElementById("report_count").innerHTML = "' . $total . '";
						}
					}
				</script>
			'
		);
	}

	if ( !$test and $prepareOnly ) {
		if ( $useProcesses ) {
			adesk_process_spawn(array('id' => $post['process_id'], 'stall' => 5 * 60));
		}
		$r['message'] = _a("The process has been initiated.");
		$r['succeeded'] = true;
		if ( $oldadmin ) $GLOBALS['admin'] = $oldadmin;
		return $r;
	}

	// options
	$post['delete_all'] = false;

	if ( !isset($post['column']) ) {
		$r['message'] = _a('Fields not mapped properly. Aborting...');
		if ( $oldadmin ) $GLOBALS['admin'] = $oldadmin;
		return $r;
	}
	$post['fieldslist'] = adesk_import_mapping_get($post['column']);

	// 2do: check against required fields again

	if ( isset($GLOBALS['_adesk_sync_lists']) ) unset($GLOBALS['_adesk_sync_lists']);

	adesk_import_log_comment(sprintf(_a('Found %d results.  Starting import now...'), $r['found']));

	// if we are starting it
	if ( !$test and !$offset ) {
		// turn off indexes
		adesk_import_log_comment(_a('Turning off database indexes'));
		if ( $post['destination'] == 3 ) {
			adesk_sql_tablekeys("#exclusion", false);
			adesk_sql_tablekeys("#exclusion_list", false);
		} else {
			adesk_sql_tablekeys("#subscriber", false);
			adesk_sql_tablekeys("#subscriber_list", false);
		}
	}

	switch ($r["delimiter_file"]) {
		default:
		case "comma":
			$delim = ",";
			break;
		case "semicolon":
			$delim = ";";
			break;
		case "tab":
			$delim = "\t";
			break;
	}

	$i = 0;
	while ($i < $r["lines"]) {
		$i++;
		$line = adesk_file_readline($r["fp"]);
		$line = str_replace(chr(0), "", $line);
		$tmp  = array();
		$off  = 0;
		adesk_array_parsecsv($tmp, $off, $line, $delim, true);

 		if (count($tmp) == 0) {
 			# We still want to increment the completed/percentage fields.
 			if ($useProcesses)
 				adesk_process_update($post['process_id']);

 			continue;
 		}

		$row = $tmp[0];

		if ( $i > $offset ) {
			$rs = ihook_adesk_import_row($post, $row, $test);
			if ( $useProcesses ) adesk_process_update($post['process_id']);
			if ( $rs['succeeded'] ) {
				$r['importedrows'][] = $row;
			} else {
				$r['failedrows'][] = $row;
			}
			$r['imported'] += $rs['succeeded'];
			adesk_import_log_row($post, adesk_utf_deepconv("UTF-8", _i18n("utf-8"), $row), $rs);
		}
	}

	fclose($r["fp"]);

	// cleanup if not a test
	if ( !$test ) {
		adesk_import_log_comment(_a('Turning database indexes back on'));
		// turn off indexes
		if ( $post['destination'] == 3 ) {
			adesk_sql_tablekeys("#exclusion", true);
			adesk_sql_tablekeys("#exclusion_list", true);
		} else {
			adesk_sql_tablekeys("#subscriber", true);
			adesk_sql_tablekeys("#subscriber_list", true);
		}
		// delete all check
		if ( $post['delete_all'] ) adesk_ihook('adesk_import_delete_all', $post);
		//adesk_ihook('adesk_import_cleanup', $post, $r);
	}

	$r['failed'] = $r['found'] - $r['imported'] - $offset;
	// done
	$r['succeeded'] = ( $r['found'] == $r['imported'] );
	$r['message'] = sprintf(_a('Import Completed. %d items found, %d items imported.'), $r['found'], $r['imported']);
	$jsfunc = ( $r['succeeded'] ? 'adesk_result_show' : 'adesk_error_show' );
	if ( $r['found'] > 0 ) adesk_import_log_comment('</table>');


	adesk_import_log_comment(
		'
			<script>
				if (parent && parent.adesk_ui_api_callback)
					parent.adesk_ui_api_callback();
				if (parent && parent.' . $jsfunc . ')
					parent.' . $jsfunc . '("' . htmlentities($r['message']) . '");
			</script>
		'
	);

	if ( $useProcesses ) {
		adesk_import_log_comment(
			'
				<script>
					if (parent && parent.adesk_progressbar_register && parent.document.getElementById("progressBar")) {
						parent.adesk_progressbar_set("progressBar", 100);
						parent.adesk_progressbar_unregister("progressBar");
					}
				</script>
			'
		);
	}


	if ( !$test ) {
		// fix all newly added custom fields
		if ( isset($post['newfields']) and count($post['newfields']) ) {
			foreach ( $post['newfields'] as $field ) {
				// add remaining custom fields stuff (such as multiple options for radios/dropdowns)
				$values = adesk_sql_select_list("
					SELECT
						CONCAT(val, '||', val)
					FROM
						#list_field_value
					WHERE
						fieldid = '$field[fieldid]'
					GROUP BY val
				");
				$vals = implode("\n", $values);
				/*
				if ( $field['type'] == 4 ) { // radio
				} elseif ( $field['type'] == 5 ) { // dropdown
				} elseif ( $field['type'] == 7 ) { // list box
				} elseif ( $field['type'] == 8 ) { // checkbox group
				}
				*/
				// radio, dropdown, list box, checkbox group
				if ( in_array($field['type'], array(4, 5, 7, 8)) ) {
					// save as "expl"
					adesk_sql_update_one("#list_field", "expl", $vals, "id = '$field[fieldid]'");
				}
			}
		}

		if ( isset($GLOBALS['_hosted_account']) ) {
			require(dirname(dirname(__FILE__)) . '/manage/import.inc.php');
		}
	}


	if ( $test ) {
		adesk_import_log_comment(_a('Import Test Completed'));
	} else {
		adesk_import_log_comment(_a('Import Completed'));
		// remove the import file
		if ( $r['filename'] and file_exists(adesk_cache_dir($r['filename'])) ) {
			@unlink(adesk_cache_dir($r['filename']));
		}
	}

	adesk_import_log_comment(_a('Imported: ') . $r['imported']);
	adesk_import_log_comment(_a('Failed: ') . $r['failed']);

	if ( $oldadmin ) $GLOBALS['admin'] = $oldadmin;
	return $r;
}

function subscriber_import_src($post, $running = false) {
	// gather input
	$admin = adesk_admin_get();
	$uid = ( $admin['id'] == 0 ? 1 : $admin['id'] );
	$relid = isset($post['lists']) ? $post['lists'] : 0;
	$r = array('message' => '', 'succeeded' => false);
	if ( is_array($relid) ) {
		$relid = array_diff(array_map('intval', $relid), array(0)); // don't allow zeros
		//$relid = array_map('intval', $relid); // allow zeros
		if ( !$relid ) {
			$r['message'] = _a('List not selected. Please re-run the import.');
			return $r;
		}
	} else {
		$relid = (int)$relid;
		if ( !$relid ) {
			$r['message'] = _a('List not selected. Please re-run the import.');
			return $r;
		}
	}
	$destination = (int)$post['status'];
	$delimiter = 'comma';
	// define result
	$r = array(
		'relid' => $relid,
		'valid' => false,
		'succeeded' => false,
		'message' => '',
		'filename' => '',
		'rows' => 0,
		'fields' => array(),
		'standardfields' => adesk_ihook('adesk_import_fields', $relid, $destination),
		'customfields' => adesk_ihook('adesk_import_custom_fields', $relid, $destination),
		'delimiter_file' => $delimiter,
	);
	/*
	if ( !adesk_admin_isadmin() ) {
		$r['message'] = _a('Only admin users can import files.');
		return $r;
	}
	*/
	if ( !$r['customfields'] ) $r['customfields'] = array();
	// if input type is textarea, save the file for future use
	$path = adesk_cache_dir() . "/";
	$filename = $post['import_file'];
	$r['filename'] = $filename;
	// do stuff with $text (data) string variable
	if ( !file_exists("$path/$filename") ) {
		$r['message'] = _a('Import file could not be found.');
		return $r;
	}

	$fp      = @fopen("$path/$filename", "r");
	$r       = array_merge($r, subscriber_import_fileinfo($fp, $delimiter));
	$r["fp"] = $fp;

	if ($r["valid"]) {
		# If valid so far, count required fields
		$required = 0;
		foreach ( $r['standardfields'] as $row ) {
			if ( $row['req'] ) $required++;
		}
		foreach ( $r['customfields'] as $row ) {
			if ( $row['req'] ) $required++;
		}
		if ( count($r['fields']) < $required ) {
			$r['message'] = sprintf(_a('This CSV file does not have enough columns to complete the import. It needs to have at least %d columns.'), $required);
			return $r;
		}
		$r['valid'] = true;
		if ( adesk_ihook_exists('adesk_import_valid_check') ) {
			$r['valid'] = (bool)adesk_ihook('adesk_import_valid_check', $r);
		}
	}

	$r["succeeded"] = true;
	$r['message'] = _a('Import content successfully parsed.');
	return $r;

	#--

	$text = file_get_contents("$path/$filename");
	// get array from CSV file
	$csv = adesk_import_csv2array($text, $delimiter);
	unset($text);
	// get fields
	$r['fields'] = adesk_import_columns($csv);
	// save CSV data if running
	$r['rows'] = count($csv);
	if ( $running ) $r['data'] = $csv;
	if ( count($r['fields']) == 0 ) {
		$r['message'] = _a('This is either not a CSV file, or no columns could be matched. Please try using different settings.');
		return $r;
	}
	// count required fields
	$required = 0;
	foreach ( $r['standardfields'] as $row ) {
		if ( $row['req'] ) $required++;
	}
	foreach ( $r['customfields'] as $row ) {
		if ( $row['req'] ) $required++;
	}
	if ( count($r['fields']) < $required ) {
		$r['message'] = sprintf(_a('This CSV file does not have enough columns to complete the import. It needs to have at least %d columns.'), $required);
		return $r;
	}
	$r['valid'] = true;
	if ( adesk_ihook_exists('adesk_import_valid_check') ) {
		$r['valid'] = (bool)adesk_ihook('adesk_import_valid_check', $r);
	}
	$r['succeeded'] = true;
	$r['message'] = _a('Import content successfully parsed.');
	return $r;
}

function subscriber_import_cfield_add($title, $type, $column = 0) {
	$lists = $_SESSION['subscriber_importer']['lists'];
	$r = array(
		'title' => trim((string)$title),
		'type' => (int)$type,
		'column' => (int)$column,
		'lists' => implode(',', $lists),
		'id' => 0,
	);

	if ( !$r['title'] ) {
		return adesk_ajax_api_result(false, _a("Custom Field Title cannot be left empty."), $r);
	}

	// "Text Box" type can't have 0 for onfocus, for some reason; it throws a JS error when trying to load the custom field.
	// the rest seem to work fine with 0 for onfocus
	//$onfocus = ($r['type'] == 2) ? '||' : 0;
	$onfocus = '';

	$insert = array(
		'id' => 0,
		'title' => $r['title'],
		'type' => $r['type'],
		'expl' => '',
		'req' => 0,
		'onfocus' => $onfocus,
		'bubble_content' => '',
		'label' => 0,
		'show_in_list' => 1,
		'perstag' => '',
	);
	$sql = adesk_sql_insert('#list_field', $insert);
	if ( !$sql ) {
		return adesk_ajax_api_result(false, _a("Custom Field could not be added."), $r);
	}
	$r['id'] = $id = (int)adesk_sql_insert_id();
	foreach ( $lists as $lid ) {
		$insert = array(
			'id' => 0,
			'fieldid' => $id,
			'relid' => $lid,
			'dorder' => 999,
		);
		$sql = adesk_sql_insert('#list_field_rel', $insert);
		if ( !$sql ) {
			return adesk_ajax_api_result(false, _a("Custom Field could not be added to a list."), $r);
		}
	}

	if ( !isset($_SESSION['subscriber_importer']['newfields']) ) $_SESSION['subscriber_importer']['newfields'] = array();
	$_SESSION['subscriber_importer']['newfields'][] = array('fieldid' => $id, 'columnid' => $r['column'], 'type' => $r['type']);

	return adesk_ajax_api_added(_a("Custom Field"), $r);
}

function subscriber_import_is_email($email) {
	return adesk_str_is_email($email);
}

?>
