<?php /* Smarty version 2.6.12, created on 2016-07-08 14:17:52
         compiled from subscriber_import.js */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'jsvar', 'subscriber_import.js', 1, false),array('modifier', 'alang', 'subscriber_import.js', 6, false),array('modifier', 'js', 'subscriber_import.js', 6, false),)), $this); ?>
<?php echo smarty_function_jsvar(array('name' => 'configured','var' => $this->_tpl_vars['configured']), $this);?>

<?php echo smarty_function_jsvar(array('name' => 'fields','var' => $this->_tpl_vars['fields']), $this);?>

<?php echo smarty_function_jsvar(array('name' => 'cfields','var' => $this->_tpl_vars['cfields']), $this);?>

<?php echo smarty_function_jsvar(array('name' => 'require_name','var' => $this->_tpl_vars['require_name']), $this);?>


var subscriber_import_no_lists = '<?php echo ((is_array($_tmp=((is_array($_tmp="You did not select any lists to import your subscribers to.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var subscriber_import_no_data = '<?php echo ((is_array($_tmp=((is_array($_tmp="You did not enter any data to import.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var subscriber_import_no_external = '<?php echo ((is_array($_tmp=((is_array($_tmp="You did not enter an External Service.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var subscriber_import_no_source = '<?php echo ((is_array($_tmp=((is_array($_tmp="You did not complete setting up the External Service.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var subscriber_import_no_name = '<?php echo ((is_array($_tmp=((is_array($_tmp="Your list(s) require that all subscribers have a name.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
\n\n<?php echo ((is_array($_tmp=((is_array($_tmp="Either map a field to the first or last name or edit your list settings to not require a name.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var subscriber_import_hd_no_url = '<?php echo ((is_array($_tmp=((is_array($_tmp="You did not enter the URL of your Help Desk install.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var subscriber_import_hd_no_user = '<?php echo ((is_array($_tmp=((is_array($_tmp="You did not enter the username of your Help Desk admin account.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var subscriber_import_hd_no_pass = '<?php echo ((is_array($_tmp=((is_array($_tmp="You did not enter the password of your Help Desk admin account.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var subscriber_import_hr_no_url = '<?php echo ((is_array($_tmp=((is_array($_tmp="You did not enter the URL of your Highrise service.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var subscriber_import_hr_no_token = '<?php echo ((is_array($_tmp=((is_array($_tmp="You did not enter the API token of your Highrise user account.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var subscriber_import_cfield_no_title = '<?php echo ((is_array($_tmp=((is_array($_tmp="You did not enter the name of your new custom field.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var subscriber_import_google_contacts1 = '<?php echo ((is_array($_tmp=((is_array($_tmp="You did not enter the username of your Google Account.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var subscriber_import_google_contacts2 = '<?php echo ((is_array($_tmp=((is_array($_tmp="You did not enter the password of your Google Account.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var subscriber_import_google_contacts3 = '<?php echo ((is_array($_tmp=((is_array($_tmp="Please include your full Gmail address as the username.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var subscriber_import_freshbooks1 = '<?php echo ((is_array($_tmp=((is_array($_tmp="You did not enter your Freshbooks account.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var subscriber_import_freshbooks2 = '<?php echo ((is_array($_tmp=((is_array($_tmp="You did not enter your Freshbooks API key.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var subscriber_import_salesforce1 = '<?php echo ((is_array($_tmp=((is_array($_tmp="You did not enter your Salesforce username.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var subscriber_import_salesforce2 = '<?php echo ((is_array($_tmp=((is_array($_tmp="You did not enter your Salesforce password.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var subscriber_import_salesforce3 = '<?php echo ((is_array($_tmp=((is_array($_tmp="You did not enter your Salesforce token.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var subscriber_import_sugarcrm1 = '<?php echo ((is_array($_tmp=((is_array($_tmp="You did not enter your SugarCRM URL.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var subscriber_import_sugarcrm2 = '<?php echo ((is_array($_tmp=((is_array($_tmp="You did not enter your SugarCRM username.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var subscriber_import_sugarcrm3 = '<?php echo ((is_array($_tmp=((is_array($_tmp="You did not enter your SugarCRM password.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var subscriber_import_zohocrm1 = '<?php echo ((is_array($_tmp=((is_array($_tmp="You did not enter your Zoho CRM API key.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var subscriber_import_zohocrm2 = '<?php echo ((is_array($_tmp=((is_array($_tmp="You did not enter your Zoho CRM username.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var subscriber_import_zohocrm3 = '<?php echo ((is_array($_tmp=((is_array($_tmp="You did not enter your Zoho CRM password.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var subscriber_import_microsoftcrm1 = '<?php echo ((is_array($_tmp=((is_array($_tmp="You did not enter your Microsoft CRM username.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var subscriber_import_microsoftcrm2 = '<?php echo ((is_array($_tmp=((is_array($_tmp="You did not enter your Microsoft CRM password.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var subscriber_import_microsoftcrm3 = '<?php echo ((is_array($_tmp=((is_array($_tmp="You did not enter your Microsoft CRM domain.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var subscriber_import_microsoftcrm4 = '<?php echo ((is_array($_tmp=((is_array($_tmp="You did not enter your Microsoft CRM organization.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var subscriber_import_capsule1 = '<?php echo ((is_array($_tmp=((is_array($_tmp="You did not enter your Capsule application name.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var subscriber_import_capsule2 = '<?php echo ((is_array($_tmp=((is_array($_tmp="You did not enter your Capsule API token.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var subscriber_import_tactile1 = '<?php echo ((is_array($_tmp=((is_array($_tmp="You did not enter your Tactile CRM application name.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var subscriber_import_tactile2 = '<?php echo ((is_array($_tmp=((is_array($_tmp="You did not enter your Tactile CRM API token.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var subscriber_import_zendesk1 = '<?php echo ((is_array($_tmp=((is_array($_tmp="Please enter your Zendesk account name.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var subscriber_import_zendesk2 = '<?php echo ((is_array($_tmp=((is_array($_tmp="Please enter your Zendesk username.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';
var subscriber_import_zendesk3 = '<?php echo ((is_array($_tmp=((is_array($_tmp="Please enter your Zendesk password.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';

var cfield_column_index = null;

<?php echo '

function import_process(loc, hist) {
	if (loc) {
		if ( $("external_div_" + loc) ) {
			$("from_external").checked = "checked";
			import_set_from( $("from_external") );
			set_external(loc);
		}
		else {
			if ( $("from_" + loc) ) {
				$("from_" + loc).checked = "checked";
				import_set_from( $("from_" + loc) );
			}
		}
	}
}

function goToLocation(val){
 if(val == "external_db")
     window.location = "desk.php?action=sync";
    

}

function import_submit_step1() {
	var post = adesk_form_post($(\'importCfgForm\'));

	if ( typeof post.into == \'undefined\' ) {
		alert(subscriber_import_no_lists);
		return false;
	}

	if ( post.from == \'file\' ) {
		// don\'t do any checks if file, let server handle it
	} else if ( post.from == \'text\' ) {
		// check if text field is left empty
		if ( adesk_str_trim(post.text) == \'\' ) {
			alert(subscriber_import_no_data);
			return false;
		}
	} else if ( post.from == \'external\' ) {
		// check the global external service settings
		if ( post.external == \'\' ) {
			alert(subscriber_import_no_external);
			return false;
		}
		if ( post.source == \'\' ) {
			alert(subscriber_import_no_source);
			return false;
		}
		// check specific connectors
		if ( post.external == \'hd\' && adesk_js_admin.brand_links == 1 ) {
			// acp help desk
			if ( !adesk_str_is_url(post.hd_url) ) {
				alert(subscriber_import_hd_no_url);
				return false;
			}
			if ( adesk_str_trim(post.hd_user) == \'\' ) {
				alert(subscriber_import_hd_no_user);
				return false;
			}
			if ( adesk_str_trim(post.hd_pass) == \'\' ) {
				alert(subscriber_import_hd_no_pass);
				return false;
			}
		} else if ( post.external == \'hr\' ) {
			// highrise
			if ( !adesk_str_is_url(post.hr_url) ) {
				alert(subscriber_import_hr_no_url);
				return false;
			}
			if ( adesk_str_trim(post.hr_api) == \'\' ) {
				alert(subscriber_import_hr_no_token);
				return false;
			}
		} else if ( post.external == \'tactile\' ) {
			if ( adesk_str_trim(post.tactile_app) == \'\' ) {
				alert(subscriber_import_tactile1);
				return false;
			}
			if ( adesk_str_trim(post.tactile_token) == \'\' ) {
				alert(subscriber_import_tactile2);
				return false;
			}
		} else if ( post.external == \'capsule\' ) {
			if ( adesk_str_trim(post.capsule_app) == \'\' ) {
				alert(subscriber_import_capsule1);
				return false;
			}
			if ( adesk_str_trim(post.capsule_token) == \'\' ) {
				alert(subscriber_import_capsule2);
				return false;
			}
		} else if ( post.external == \'microsoftcrm\' ) {
			if ( adesk_str_trim(post.microsoftcrm_username) == \'\' ) {
				alert(subscriber_import_microsoftcrm1);
				return false;
			}
			if ( adesk_str_trim(post.microsoftcrm_password) == \'\' ) {
				alert(subscriber_import_microsoftcrm2);
				return false;
			}
			if ( adesk_str_trim(post.microsoftcrm_organization) == \'\' ) {
				alert(subscriber_import_microsoftcrm4);
				return false;
			}
			if ( adesk_str_trim(post.microsoftcrm_domain) == \'\' ) {
				alert(subscriber_import_microsoftcrm3);
				return false;
			}
		} else if ( post.external == \'zohocrm\' ) {
			if ( adesk_str_trim(post.zohocrm_apikey) == \'\' ) {
				alert(subscriber_import_zohocrm1);
				return false;
			}
			if ( adesk_str_trim(post.zohocrm_username) == \'\' ) {
				alert(subscriber_import_zohocrm2);
				return false;
			}
			if ( adesk_str_trim(post.zohocrm_password) == \'\' ) {
				alert(subscriber_import_zohocrm3);
				return false;
			}
		} else if ( post.external == \'sugarcrm\' ) {
			if ( adesk_str_trim(post.sugarcrm_url) == \'\' ) {
				alert(subscriber_import_sugarcrm1);
				return false;
			}
			if ( adesk_str_trim(post.sugarcrm_username) == \'\' ) {
				alert(subscriber_import_sugarcrm2);
				return false;
			}
			if ( adesk_str_trim(post.sugarcrm_password) == \'\' ) {
				alert(subscriber_import_sugarcrm3);
				return false;
			}
		} else if ( post.external == \'salesforce\' ) {
			if ( adesk_str_trim(post.salesforce_username) == \'\' ) {
				alert(subscriber_import_salesforce1);
				return false;
			}
			if ( adesk_str_trim(post.salesforce_password) == \'\' ) {
				alert(subscriber_import_salesforce2);
				return false;
			}
			if ( adesk_str_trim(post.salesforce_token) == \'\' ) {
				alert(subscriber_import_salesforce3);
				return false;
			}
		} else if (post.external == \'zendesk\') {
			if (adesk_str_trim(post.zendesk_account) == \'\') {
				alert(subscriber_import_zendesk1);
				return false;
			}
			if (adesk_str_trim(post.zendesk_username) == \'\') {
				alert(subscriber_import_zendesk2);
				return false;
			}
			if (adesk_str_trim(post.zendesk_password) == \'\') {
				alert(subscriber_import_zendesk3);
				return false;
			}
		} else if ( post.external == \'google_spreadsheets\' ) {

		}
		
		if ( $(\'import_loader_external_options\') ) {
			$(\'import_loader_filters\').style.display = \'none\';
			$(\'import_loader\').style.display = \'\';
		}
		else {
			$(\'import_loader_filters\').style.display = \'\';
			$(\'import_loader\').style.display = \'none\';
		}
		
		return true;
	}

	adesk_dom_toggle_display(\'import_loader\', \'block\');
	return true;
}

function import_back() {
	if ( $(\'step3\') && $(\'step3\').style.display != "none" ) {
		$(\'step2\').show();
		$(\'step3\').hide();
	} else {
		window.location.href = "desk.php?action=subscriber_import";
	}
}

function import_set_from(obj) {
	var newval = obj.value;
	var post = adesk_form_post($(\'importCfgForm\'));
	
	if (newval == "file")
		$("import_file").show();
	else
		$("import_file").hide();

	if (newval == "text")
		$("import_text").show();
	else
		$("import_text").hide();

	if (newval == "external") {
		$("import_external").show();
		// But don\'t show the external_box_configs div yet...
	} else {
		$("import_external").hide();
		$(\'external_box_configs\').hide();
	}
}

function set_external(newval) {
	$("external").value = newval;
	var allsources = [ \'hr\', \'google_contacts\', \'google_spreadsheets\', \'freshbooks\', \'salesforce\', \'sugarcrm\', \'zohocrm\', /*\'microsoftcrm\',*/ \'capsule\', \'tactile\', \'batchbook\' , \'zendesk\'];
	if ( adesk_js_admin.brand_links == 1 ) allsources.push(\'hd\');
	for ( var i = 0; i < allsources.length; i++ ) {
		var s = allsources[i];
		if ( !$(\'external_div_\' + s) ) continue;
		$(\'external_div_\' + s).className = ( newval == s ? \'import_external_source_selected\' : \'import_external_source_notselected\' );
		if (newval == s)
			$(\'external_box_\' + s).show();
		else
			$(\'external_box_\' + s).hide();
	}
	$(\'external_form_\' + newval).show();
	$(\'external_config_\' + newval).hide();
	$(\'external_box_configs\').show();
}

/*
	not used yet
*/
function connect2external(ext) {
	var post = adesk_form_post($(\'importCfgForm\'));
	post.external = ext;

	adesk_ui_api_call(jsLoading, 600);
	adesk_ajax_call_cb(
		\'awebdeskapi.php\',
		\'subscriber_import.subscriber_import_connect\',
		function (xml) {
			var ary = adesk_dom_read_node(xml);
			adesk_ui_api_callback();
			if ( ary.succeeded == 1 ) {
				adesk_result_show(ary.message);
				$(\'external_form_\' + ext).hide();
				$(\'external_config_\' + ext).show();
			} else {
				adesk_error_show(ary.message);
			}
		},
		post
	);
}


function import_run(isTest) {
	// check if there are no duplicate mappings
	var selects = $$(\'#importRunForm select\');
	var selected = [ ];
	for ( var i = 0; i < selects.length; i++ ) {
		if ( adesk_array_has(selected, selects[i].value) ) {
			alert(importDuplicateMapping);
			selects[i].focus();
			return false;
		}
		if ( selects[i].value != \'DNI\' ) selected.push(selects[i].value);
	}
	// check if all required (standard) fields are mapped
	for ( var i in fields ) {
		var f = fields[i];
		if ( typeof(f) != \'function\' ) {
			if ( fields[i].req && !adesk_array_has(selected, fields[i].id) ) {
				alert(sprintf(importMissingMapping, fields[i].name + \' (\' + fields[i].id + \')\'));
				return false;
			}
		}
	}
	if ( require_name ) {
		if ( !adesk_array_has(selected, fields[2].id) && !adesk_array_has(selected, fields[3].id) ) {
			alert(subscriber_import_no_name);
			return false;
		}
	}
	$(\'step2\').hide();

	adesk_loader_show(jsImporting);
	//adesk_ui_api_call(jsImporting, 60);
	// show iframe
	adesk_progressbar_set("progressBar", 0);
	if (isTest)
		$(\'importRunNotice\').hide();
	else
		$(\'importRunNotice\').show();

	$(\'step3\').show();

	$(\'importRunResult\').hide();
	$(\'importRunFrame\').show();
	$(\'importRunFrame\').height = ( isTest ? \'300\' : \'1\' );
	$(\'importRunFrame\').width = ( isTest ? \'100%\' : \'1\' );
	// set test flag
	$(\'importRunType\').value = ( isTest ? 1 : 0 );
	// submit form
	$(\'importRunForm\').submit();
	// show progress bar
	if ( !isTest ) {
		$("buttons").hide();
		//$("backlink").className = "adesk_block";
	}
	return false;
}

function import_progressbar_callback(ary) {
	if ( parseInt(ary.percentage) == 100 ) {
		// stop the progressbar
		adesk_progressbar_unregister("progressBar");
		$(\'importRunNotice\').hide();
		$(\'importRunResult\').show();
		adesk_loader_hide();
	}
}


function import_report() {
	// fetch import logs
	adesk_ui_api_call(jsLoading, 60);
	adesk_ajax_call_cb(\'awebdeskapi.php\', \'subscriber_import.adesk_import_report\', import_report_cb, processID);
	return false;
}

function import_report_cb(xml) {
	var ary = adesk_dom_read_node(xml, ( paginator_b64 ? adesk_b64_decode : null ));
	adesk_ui_api_callback();

	// hack?
	if ( ary.counts[0] ) ary.counts = ary.counts[0];
	if ( ary.lists[0]  ) ary.lists  = ary.lists[0];

	// fill the modal panel

	// set counts
	ary.total0 = parseInt(ary.total, 10);
	ary.total  = parseInt($(\'report_count\').innerHTML, 10);
	ary.total1 = ary.total - ary.total0;
	for ( var i in ary.counts ) {
		if ( typeof ary.counts[i] != \'function\' ) {
			ary.counts[i] = parseInt(ary.counts[i], 10);
		}
	}

	$(\'report_count0\').innerHTML = ary.total0;
	$(\'report_count1\').innerHTML = ary.total1;

	if ( typeof(ihook_import_report) == \'function\' ) ihook_import_report(ary);

	// show it
	//adesk_dom_toggle_display(\'import_report\', \'block\');
	adesk_dom_display_block(\'import_report\');
}

function cfield_open(obj) {
	if ( obj.value != \'NEW\' ) return;
	var cfield_column_index = obj.id.replace(\'column_\', \'\');
	obj.selectedIndex = 0;
	$(\'cfield_select_source\').value = obj.id; // save the select list ID that triggered this
	adesk_dom_display_block(\'import_cfield\');
	$(\'cfield_title\').focus();
}

function cfield_add() {
	var cfield_title = $(\'cfield_title\').value;
	var cfield_type  = $(\'cfield_type\').value;

	if ( adesk_str_trim(cfield_title) == \'\' ) {
		alert(subscriber_import_cfield_no_title);
		return;
	}

	adesk_ui_api_call(jsLoading, 60);
	adesk_ajax_call_cb(
		\'awebdeskapi.php\',
		\'subscriber_import.subscriber_import_cfield_add\',
		function(xml) {
			var ary = adesk_dom_read_node(xml);
			adesk_ui_api_callback();

			if ( ary.succeeded == 1 ) {
				var selects = $$(\'#importRunForm select\');
				for ( var i = 0; i < selects.length; i++ ) {
					var s = selects[i];
					var s_current = s.value;
					var colid = s.id.replace(\'column_\', \'\');
					var rel = $(\'customfieldsoptgroup_\' + colid);
					rel.appendChild(Builder.node(\'option\', { value: ary.id }, [ Builder._text(cfield_title) ]));
					// if this select ID is NOT equal to the select ID that triggered this process
					if ( s.id != $(\'cfield_select_source\').value ) {
						s.value = s_current; // make sure it remains selected on whatever was selected
					}
					else {
						// select the new custom field <option>
						s.value = ary.id;
					}
				}
				adesk_result_show(ary.message);
				$(\'cfield_title\').value = \'\'; // clear out the textbox
				$(\'cfield_select_source\').value = \'\'; // clear out the hidden field that saves the select list ID that triggers the process
				$(\'import_cfield\').hide();
			} else {
				adesk_error_show(ary.message);
			}
		},
		cfield_title,
		cfield_type,
		cfield_column_index
	);
}

function advanced_options_toggle() {
	if ( $(\'advanced\').style.display == \'none\' ) {
		$(\'advanced\').show();
	}
	else {
		$(\'advanced\').hide();
	}
}

function google_spreadsheets_toggle(spreadsheet_id) {
  var selects = $("import_loader_external_options").getElementsByTagName("select");
  var worksheet_select = selects[1];
  var worksheet_select_options = worksheet_select.getElementsByTagName("option");
  for (var i = 0; i < worksheet_select_options.length; i++) {
    var current_option = worksheet_select_options[i];
    if (current_option.value.substring(0, spreadsheet_id.length) == spreadsheet_id) {
      // show the <option>s that belong to selected spreadsheet
      current_option.style.display = "";
      current_option.selected = true;
    }
    else {
      // hide the <option> if it does not belong to chosen spreadsheet
      current_option.style.display = "none";
    }
  }
}

function import_freshbooks_authorize() {
	if ( !$(\'freshbooks_account\').value ) {
		alert(subscriber_import_freshbooks1);
		return;
	}
	window.location = adesk_js_site.p_link + \'/manage/desk.php?action=subscriber_import&freshbooks_account=\' + $(\'freshbooks_account\').value;
}

'; ?>
