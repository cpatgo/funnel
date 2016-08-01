<?php

// define ihooks

adesk_ihook_define('adesk_process_handler', 'ihook_process_handler');
adesk_ihook_define('adesk_process_info', 'ihook_process_info');
adesk_ihook_define('adesk_process_actions', 'ihook_process_actions');
adesk_ihook_define('adesk_process_update', 'ihook_process_update');


// ihooks functions


function ihook_process_handler($process) {
	if ( $process['action'] == 'subscriber_import' ) {
		// include hooks
		require_once(adesk_admin("functions/subscriber_import.php"));
		// run importer
		$test = ( isset($process['data']['test']) ? $process['data']['test'] : false );
		$_POST = $process['data']; // we gotta simulate post here :(
		return subscriber_import_run($process['data'], $test, $process['completed'], $prepareOnly = false);
		return adesk_import_run($process['data'], $test, $process['completed'], $prepareOnly = false);
	} elseif ( $process['action'] == 'sync' ) {
		// include sync
		require_once(awebdesk_functions("sync.php"));
		// overlap check
		$proc = adesk_process_get($process['id']);
		if ( (int)$proc['stall'] < 4 * 60 and $proc['ldate'] != '0000-00-00 00:00:00' ) {
			// trigger an error
			return false;
			return adesk_sync_run(array(), $process['data']['test'], $full = true, $process['completed']);
		}
		adesk_process_update($process['id'], false);
		// run sync
		$test = ( isset($process['data']['test']) ? $process['data']['test'] : false );
		$_POST = $sync = $process['data']['sync']; // we gotta simulate post here :(
		adesk_sync_log_init($sync);
		adesk_sync_log_store("\nPicking up Cron Job (process #$sync[process_id]) at $process[completed] / $process[total]\n");
		return adesk_sync_run($sync, $test, $full = true, $process['completed']);
	} elseif ($process["action"] == "database") {
		require_once adesk_admin("functions/database.php");
		database_handle($process);
	} elseif ( $process['action'] == 'removebatch' ) {

		// action=batch, "Remove a select list of addresses"

		// Loop through supplied email addresses, and attempt to remove from current list
		foreach ($process['data']['emails'] as $email) {

			// Loop through selected lists
			foreach ($process['data']['lists'] as $listid) {

				$subscriber = subscriber_exists(trim($email), $listid);

				if ($subscriber) {
					subscriber_list_remove($subscriber, $listid);
				}
			}
			// Run the process update X times - whatever the count of supplied emails is
			adesk_process_update($process['id']);
		}
	} elseif ( $process['action'] == 'removenon' || $process['action'] == 'removeall' ) {

		// action=batch, "Remove all non-confirmed subscribers from these lists"
		// action=batch, "Remove all subscribers from these lists"

		$so = new adesk_Select;
		$so->push($process['data']['conds']);
		$so->slist = array('s.id');
		$so->remove = false;
		$subscribers = subscriber_select_array($so);

		if ( !$subscribers ) {
			adesk_process_end($process['id']);
			return;
		}

		// Loop through subscribers
		foreach ($subscribers as $subscriber) {
			// Loop through selected lists
			foreach ($process['data']['lists'] as $listid) {
			 
				
				
				subscriber_list_remove($subscriber, $listid);
			}
			adesk_process_update($process['id']);
			adesk_flush('. ');
		}
		adesk_flush('Completed.');
		if ( !$subscribers ) {
			adesk_process_end($process['id']);
			return;
		}
	} 
	elseif ( $process['action'] == 'removeinvalid' ) {

		// action=batch, "Remove all non-confirmed subscribers from these lists"
		// action=batch, "Remove all subscribers from these lists"

		$so = new adesk_Select;
		$so->push($process['data']['conds']);
		$so->slist = array('s.id');
		$so->remove = false;
		$subscribers = subscriber_select_array($so);

		if ( !$subscribers ) {
			adesk_process_end($process['id']);
			return;
		}

		// Loop through subscribers
		foreach ($subscribers as $subscriber) {
			// Loop through selected lists
			foreach ($process['data']['lists'] as $listid) {
				//sandeep possible list cleanup could take place here 
				
				/*
				if(listcleanup is active)
				 process(listcleanup)
				 if email is valid continue else go to remove from list :)
				
				*/
				 
				if(subscriber_invalid_remove($subscriber['id']))
				   subscriber_list_remove($subscriber, $listid);
				// else
			     //continue;
				
				
				
			}
			adesk_process_update($process['id']);
			adesk_flush('. ');
		}
	
		adesk_flush('Completed.');
			if ( !$subscribers ) {
			adesk_process_end($process['id']);
			return;
		}
	} elseif ( $process['action'] == 'campaign' ) {
		require_once adesk_admin("functions/campaign.php");
		return campaign_process($process);
	} elseif ( $process['action'] == 'filter' ) {
		require_once adesk_admin("functions/filter.php");
		filter_process($process);
	} elseif ($process["action"] == "iconv") {
		require_once adesk_admin("functions/iconv.php");
		iconv_process($process);
	} elseif ($process["action"] == "reverify") {
		require_once adesk_admin("functions/reverify.php");
		reverify_process($process);
	} else {
		// process is unknown, remove it
		adesk_process_remove($process['id']);
		return adesk_ajax_api_result(false, _a('Unknown Process - deleted'));
	}
}

function ihook_process_info($process) {
	$r = array();
	$actions = adesk_ihook('adesk_process_actions');
	if ( !isset($actions[$process['action']]) ) return;
	$r['name'] = $actions[$process['action']];
	if ( $process['action'] == 'subscriber_import' ) {
		// stuff for importer
	} elseif ( $process['action'] == 'sync' ) {
		// stuff for sync
	} elseif ( $process['action'] == 'removebatch' ) {
		//
	} elseif ( $process['action'] == 'removenon' ) {
		//
	} elseif ( $process['action'] == 'removeall' ) {
		//
	} elseif ( $process['action'] == 'database' ) {
		//
	} elseif ( $process['action'] == 'campaign' ) {
		//
	} elseif ( $process['action'] == 'filter' ) {
		//
	} elseif ( $process['action'] == 'iconv' ) {
		//
	}  elseif ( $process['action'] == 'removeinvalid' ) {
		//
	}
	return $r;
}

function ihook_process_actions() {
	return array(
		'subscriber_import' => _a('Import Subscribers'),
		'sync'              => _a('Database Synchronization'),
		'removeall'         => _a('Remove All Subscribers'),
		'removebatch'       => _a('Batch Remove Subscribers'),
		'removenon'         => _a('Remove Non-Confirmed Subscribers'),
		'database'          => _a('Database Utility'),
		'campaign'          => _a('Sending Engine'),
		'filter'            => _a('Subscriber Filtering'),
		'iconv'				=> _a("Convert to UTF-8"),
		'removeinvalid'		=> _a("Remove invalid emails and subscribers"),
	);
}

function ihook_process_update($process, $data) {
	$processdata = @unserialize($process['data']);
	if ( $process['action'] == 'campaign' ) {
		$sendid = (int)$processdata;
		$cid = (int)adesk_sql_select_one("campaignid", "#campaign_count", "id = '$sendid'");
		// campaigns have their own run/pause switch
		$newstatus = ( isset($data['active']) ? 2 : 3 );
		adesk_sql_update_one('#campaign', 'status', $newstatus, "`id` = '$cid'");
		//$oldstatus = ( $process['ldate'] ? 2 : 3 );
		//adesk_sql_update_one('#campaign', 'status', $newstatus, "( `id` = '$cid' OR `processid` = '$process[id]' ) AND `status` = '$oldstatus'");
	}
}

?>
