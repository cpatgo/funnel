var customfields = { };

function sync_form_show(id, step) {
	adesk_ui_api_call(jsLoading);
	var action = ( id == 0 ? 'add' : 'edit' );
	if ( action == 'add' ) {
		sync_form_fill(frmArr);
		sync_form_show_cleanup(id, step);
	} else {
		// make a call, fetch article, then show it
		adesk_ajax_call_cb('awebdeskapi.php', 'sync!adesk_sync_select', sync_form_show_callback, id);
	}
	return false;
}

function sync_form_show_cleanup(id, step) {
	$('syncListPanel').className = 'adesk_hidden';
	$('syncFormPanel').className = 'adesk_block';
	$('syncModeTitle').innerHTML = ' ' + ( id == 0 ? jsTitleAdd : jsTitleEdit );
	sync_step_show(step);
	adesk_ui_api_callback();
	// set anchor
	//adesk_ui_anchor_set([ ( id == 0 ? 'add' : 'edit' ), id ].join('-'));
}





function sync_form_fill(data) {
	// mode
	manageAction = ( data.id == 0 ? 'add' : 'edit' );
	$('modeField').value = manageAction;
	// id
	manageID = data.id;
	$('syncFormIDfield').value = data.id;
	/* step 1 */
	// database fields
	$('titleField').value = ( data.sync_name ? data.sync_name : '' );
	$('dbtypeField').value = ( data.db_type ? data.db_type : 'mysql' );
	$('dbnameField').value = ( data.db_name ? data.db_name : '' );
	$('dbuserField').value = ( data.db_user ? data.db_user : '' );
	$('dbpassField').value = ( data.db_pass ? data.db_pass : '' );
	$('dbhostField').value = ( data.db_host ? data.db_host : '' );
	$('sourcecharsetField').value = (data.sourcecharset ? data.sourcecharset : 'utf-8');
	// relation
	$('relidField').value = ( isNaN(parseInt(data.relid, 10)) ? 0 : parseInt(data.relid, 10) );
	// destination?
	adesk_ihook('adesk_sync_destinations_template', data);
	// reset the 'something has changed'
	somethingChanged = false;
	// set temp array
	syncarray = data;
}

function sync_form_show_callback(xml, txt) {
	var ary = adesk_dom_read_node(xml, ( paginator_b64 ? ( paginator_b64 ? adesk_b64_decode : null ) : null ));
	var s = ary.row[0];
	if ( typeof(s.sync_name) == 'undefined' ) {
		adesk_ui_api_callback();
		somethingChanged = false;
		//sync_form_hide();
		adesk_error_show('Sync not found.');
		adesk_ui_anchor_set('list-' + sortID);
		return;
	}
	ary.id = ( isNaN(parseInt(s.id, 10)) ? 0 : parseInt(s.id, 10) );
	sync_form_fill(s);
	sync_form_show_cleanup(s.id, 1);
}



function sync_step() {
	for ( var i = 0; i < steps.length; i++ ) {
		var holder = $('sync' + steps[i] + 'Holder');
		if ( holder.className == 'h2_wrap' ) return i + 1;
	}
	return 1;
}


function sync_next() {
	var step = sync_step();
	switch ( step ) {
		case 4:
			return sync_save();
			break;
		case 3:
			return sync_map_check();
			break;
		case 2:
			//
			return sync_query_check();
			break;
		default: // step 1
			// run check
			return sync_db_check();
	}
}

function sync_step_show(step) {
	for ( var i = 0; i < steps.length; i++ ) {
		$('sync' + steps[i] + 'Holder').className = ( i + 1 == step ? 'h2_wrap' : 'adesk_hidden' );
		$('step' + steps[i]).className = ( i + 1 == step ? 'currentstep' : 'otherstep' );
	}
	$('syncWizardNext').className = ( step != 4 ? 'adesk_button_next' : 'adesk_hidden' );
	$('syncWizardTest').className = ( step == 4 ? 'adesk_button_test' : 'adesk_hidden' );
	$('syncWizardRun' ).className = ( step == 4 ? 'adesk_button_run'  : 'adesk_hidden' );
	$('syncWizardDone').className = ( step == 4 ? ( manageID == 0 ? 'adesk_button_add' : 'adesk_button_update' ) : 'adesk_hidden' );
	$('syncWizardDone').value = ( manageID == 0 ? jsAdd : jsUpdate );
}


function sync_db_check() {
	// check title, username and host fields
	if ( !adesk_form_text_value_check('titleField', '', syncEnterTitle, syncMissingTitle) ) return false;
	if ( !adesk_form_text_value_check('dbuserField', '', syncEnterUser, syncMissingUser) ) return false;
	if ( !adesk_form_text_value_check('dbhostField', 'localhost', syncEnterHost, syncMissingHost) ) return false;
	if ( $('relidField').value == 0 ) {
		alert(syncMissingRelid);
		$('relidField').focus();
		return false;
	}
	var post = adesk_form_post('addSyncForm');
	post.id = manageID;
	adesk_ui_api_call(jsConnecting);
	adesk_ajax_post_cb('awebdeskapi.php', 'sync!adesk_sync_db', sync_db_check_callback, post);
	return false;
}

function sync_db_check_callback(xml, txt) {
	var ary = adesk_dom_read_node(xml, ( paginator_b64 ? adesk_b64_decode : null ));
	adesk_ui_api_callback();
	var id  = ( isNaN(parseInt(ary.id, 10)) ? 0 : parseInt(ary.id, 10) );
	var act = ( id == 0 ? 'add' : 'edit' );
	if ( ary.succeeded && ary.succeeded == 1 ) {
		adesk_result_show(ary.message);
		/* step 2 */
		// remove all tables
		var rel = $('syncTables');
		adesk_dom_remove_children(rel);
		if ( typeof ary.tables == 'string' && ary.tables != '' ) {
			ary.tables = [ ary.tables ];
		}
		if ( ary.tables ) {
			for ( var i = 0; i < ary.tables.length; i++ ) {
				var t = ary.tables[i];
				var props = { type: 'radio', name: 'db_table', value: t, onclick: "$('syncQuery').className = 'adesk_hidden';" };
				if ( id > 0 && syncarray.db_table == t ) props.checked = true;
				rel.appendChild(
					Builder.node(
						'label',
						[
							Builder.node('input', props),
							Builder._text(t)
						]
					)
				);
			}
		}
		$('syncCustomQueryRadio').checked = false;
		$('syncQuery').className = 'adesk_hidden';
		$('queryField').value = '';
		if ( id > 0 && syncarray.db_table == '' ) {
			// custom query
			$('syncCustomQueryRadio').checked = true;
			$('syncQuery').className = 'adesk_block';
			$('queryField').value = syncarray.rules;
		}
		// save custom fields
		customfields = ( ary.customfields ? ary.customfields : { } );
		// go to a next step
		adesk_ui_rsh_save(act + '-' + id + '-2', adesk_form_post($('addSyncForm')));
		//window.location.hash = act + '-' + id + '-2';
		sync_step_show(2);
	} else {
		adesk_error_show(ary.message);
		if ( ary.duplicate == 1 ) {
			$('relidField').focus();
		}
	}
}


function sync_query_check() {
	var post = adesk_form_post('addSyncForm');
	// check if either any table is selected, or a query is entered
	if ( typeof post.db_table == 'undefined' ) {
		alert(syncMissingTable);
		return false;
	}
	if ( post.db_table == '' && !post.db_query.match(/select/i) ) {
		alert(syncMissingQuery);
		$('queryField').focus();
		return false;
	}
	post.id = manageID;
	adesk_ui_api_call();
	adesk_ajax_post_cb('awebdeskapi.php', 'sync!adesk_sync_table', sync_query_check_callback, post);
	return false;
}

function sync_query_check_callback(xml, txt) {
	var ary = adesk_dom_read_node(xml, ( paginator_b64 ? adesk_b64_decode : null ));
	adesk_ui_api_callback();
	var id  = ( isNaN(parseInt(ary.id, 10)) ? 0 : parseInt(ary.id, 10) );
	var act = ( id == 0 ? 'add' : 'edit' );
	if ( ary.succeeded && ary.succeeded == 1 ) {
		adesk_result_show(ary.message);
		/* step 3 */
		// remove all field mappings
		var rel = $('mappingTable');
		adesk_dom_remove_children(rel);
		if ( ary.fields ) {
			// print all foreign fields
			for ( var i = 0; i < ary.fields.length; i++ ) {
				// prepare local fields
				var nodes = [ ];
				for ( var j in fields ) {
					var r = fields[j];
					if ( typeof r != 'function' ) {
						nodes.push(
							Builder.node('option', { value: r.id }, [ Builder._text(r.name) ])
						);
					}
				}
				if ( customfields ) {
					var subnodes = [ ];
					for ( var j in customfields ) {
						var r = customfields[j];
						if ( typeof r != 'function' ) {
							subnodes.push(
								Builder.node('option', { value: '_f' + r.id }, [ Builder._text(r.title) ])
							);
						}
					}
					if ( subnodes.length > 0 ) {
						nodes.push(
							Builder.node('optgroup', { label: 'Custom Fields' }, subnodes)
						);
					}
				}
				// prepare foreign fields
				var f = ary.fields[i];
				rel.appendChild(
					Builder.node(
						'tr',
						{ className: 'adesk_table_row' },
						[
							Builder.node('td', [ Builder._text(f.name + ' ( ' + f.type + ' )') ]),
							Builder.node(
								'td',
								[
									Builder.node(
										'select',
										{
											name: 'dest[' + f.name + ']', id: 'dest_' + f.name
										},
										nodes
									)
								]
							)
						]
					)
				);
				var val = 'DNI';
				if ( id > 0 ) {
					var fl = syncarray.fieldslist[0];
					for ( var k in fl ) {
						if ( typeof fl[k] != 'function' ) {
							if ( fl[k] == f.name ) {
								val = k;
							}
						}
					}
				}
				rel.getElementsByTagName('select')[rel.getElementsByTagName('select').length - 1].value = val;
			}
		}
		// go to a next step
		adesk_ui_rsh_save(act + '-' + id + '-3', adesk_form_post($('addSyncForm')));
		//window.location.hash = act + '-' + id + '-3';
		sync_step_show(3);
	} else {
		adesk_error_show(ary.message);
	}
}


function sync_map_check() {
	var post = adesk_form_post('addSyncForm');
	// check if there are no duplicate mappings
	var rel = $('mappingTable');
	var selects = rel.getElementsByTagName('select');
	var selected = [ ];
	for ( var i = 0; i < selects.length; i++ ) {
		if ( adesk_array_has(selected, selects[i].value) ) {
			alert(syncDuplicateMapping);
			selects[i].focus();
			return false;
		}
		if ( selects[i].value != 'DNI' ) selected.push(selects[i].value);
	}
	// check if all required fields are mapped
	for ( var i in fields ) {
		if ( fields[i].req && !adesk_array_has(selected, fields[i].id) ) {
			alert(sprintf(syncMissingMapping, fields[i].name + ' (' + fields[i].id + ')'));
			return false;
		}
	}
	post.id = manageID;
	adesk_ui_api_call();
	adesk_ajax_post_cb('awebdeskapi.php', 'sync!adesk_sync_field', sync_map_check_callback, post);
	return false;
}

function sync_map_check_callback(xml, txt) {
	var ary = adesk_dom_read_node(xml, ( paginator_b64 ? adesk_b64_decode : null ));
	adesk_ui_api_callback();
	var id  = ( isNaN(parseInt(ary.id, 10)) ? 0 : parseInt(ary.id, 10) );
	var act = ( id == 0 ? 'add' : 'edit' );
	if ( ary.succeeded && ary.succeeded == 1 ) {
		adesk_result_show(ary.message);
		/* step 4 */
		$('queryResults').className = ( ary.is_custom ? 'adesk_block' : 'adesk_hidden' );
		$('tableRules').className = ( ary.is_custom ? 'adesk_hidden' : 'adesk_block' );
		if ( ary.is_custom ) {
			// query results
			$('queryPreview').innerHTML = nl2br(ary.query);
		} else {
			// remove all field mappings
			var rel = $('rule_field');
			adesk_dom_remove_children(rel);
			$('noRules').className = ( ary.rules ? 'adesk_hidden' : 'adesk_block' );
			$('removeRules').className = ( ary.rules ? 'adesk_block' : 'adesk_hidden' );
			if ( typeof ary.rules == 'string' && ary.rules != '' ) {
				ary.rules = [ ary.rules ];
			}
			if ( ary.rules ) {
				var holder = $('rulesList');
				adesk_dom_remove_children(holder);
				// print all foreign fields
				for ( var i = 0; i < ary.rules.length; i++ ) {
					var rule = ary.rules[i];
					sync_rules_set(holder, rule);
				}
			}
			if ( ary.fields ) {
				// print all foreign fields
				for ( var i = 0; i < ary.fields.length; i++ ) {
					// prepare foreign fields
					var f = ary.fields[i];
					rel.appendChild(
						Builder.node('option', { value: f.name }, [ Builder._text(f.name) ])
					);
					rel.selectedIndex = 0;
				}
			}
		}
		// go to a next step
		adesk_ui_rsh_save(act + '-' + id + '-4', adesk_form_post($('addSyncForm')));
		//window.location.hash = act + '-' + id + '-3';
		sync_step_show(4);
	} else {
		adesk_error_show(ary.message);
	}
}


function sync_rules_set(holder, rule) {
	holder.appendChild(
		Builder.node(
			'div',
			{
				className: 'adesk_div_list'
			},
			[
				Builder.node('input', { type: 'hidden', name: 'rules[]', value: rule }),
				Builder.node('a', { href: '#', onclick: 'return sync_rules_remove(this.parentNode);', style: 'margin-right: 10px;' }, [ Builder._text(jsOptionDelete) ]),
				Builder._text('WHERE ' + rule)
			]
		)
	);
}

function sync_rules_add() {
	var holder = $('rulesList');
	var field = $('rule_field').value;
	var cond  = $('rule_cond').value;
	var value = $('rule_value').value;
	if ( field == '' ) {
		adesk_error_show("All fields are required. Please try again.");
		return false;
	}
	// construct new rule
	var rule = '';
	if ( field.indexOf('(') == -1 ) {
		if ( $('dbtypeField').value == 'mssql' ) {
			field = '[' + field + ']';
		} else {
			field = '`' + field + '`';
		}
	}
	if ( cond == 'CONTAINS' ) {
		rule = field + " LIKE '%" + value + "%'";
	} else if ( cond == 'DCONTAINS' ) {
		rule = field + " NOT LIKE '%" + value + "%'";
	} else if ( cond == 'CSCONTAINS' ) {
		rule = "BINARY " + field + " LIKE '%" + value + "%'";
	} else if ( cond == 'CSDCONTAINS' ) {
		rule = "BINARY " + field + " NOT LIKE '%" + value + "%'";
	} else if ( cond == 'CSIS' ) {
		rule = "BINARY " + field + " = '" + value + "'";
	} else if ( cond == 'INLIST' ) {
		rule = field + " IN (" + value + ")";
	} else if ( cond == 'NOTINLIST' ) {
		rule = field + " NOT IN (" + value + ")";
	} else {
		rule = field + " " + cond + " '" + value + "'";
	}
	// check to see if rule already exists
	var rules = holder.getElementsByTagName('input');
	for ( var i = 0; i < rules.length; i++ ) {
		if ( rules[i].value == rule ) {
			adesk_error_show("This rule already exists.");
			return false;
		}
	}
	// save new rule
	sync_rules_set(holder, rule);
	// put delete button
	$('removeRules').className = 'adesk_block';
	// remove 'no rules' message
	$('noRules').className = 'adesk_hidden';
	adesk_result_show("Rule has been added.");
	return false;
}

function sync_rules_remove(thisOne) {
	var holder = $('rulesList');
	if ( !confirm(jsAreYouSure) ) return false;
	if ( thisOne ) {
		// just do this one
		holder.removeChild(thisOne);
		if ( holder.childNodes.length > 0 ) return false; // still have some rules left
	} else {
		// do all
		adesk_dom_remove_children(holder);
	}
	// remove delete button
	$('removeRules').className = 'adesk_hidden';
	// put 'no rules' message
	$('noRules').className = 'adesk_block';
	return false;
}





function sync_save() {
	//if ( !somethingChanged && !confirm(syncNothingChanged) ) return false;
	var post = adesk_form_post('addSyncForm');
	post.id = manageID;
	adesk_ui_api_call(jsSaving);
	adesk_ajax_post_cb('awebdeskapi.php', 'sync!adesk_sync_save', sync_save_callback, post);
	// then return FALSE! (form will be submitted only in case of error)
	return false;
}

function sync_save_callback(xml, txt) {
	var ary = adesk_dom_read_node(xml, ( paginator_b64 ? adesk_b64_decode : null ));
	adesk_ui_api_callback();
	manageID = ( isNaN(parseInt(ary.id, 10)) ? 0 : parseInt(ary.id, 10) );
	if ( ary.succeeded && ary.succeeded > 0 ) {
		somethingChanged = false;
		adesk_result_show(ary.message);
		// flip back to list
		adesk_ui_anchor_set('list-' + sortID);
	} else {
		adesk_error_show(ary.message);
	}
}

function sync_relid_change(newval) {
	if ( typeof(newval.join) == 'function' ) {
		var getval = newval.join(',');
	} else {
		var getval = parseInt(newval, 10);
	}
	adesk_ihook('ihook_import_relid_change', newval);
}


