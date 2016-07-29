{jsvar name=importer var=$importer}
{jsvar name=relID var=$relid}
{jsvar name=fields var=$fields}
{jsvar name=multiDestination var=$multiDestination}

var stepID = 1;
var steps = [ 'src', 'map', 'run' ];
var srcID = 'text';
var processID = 0;
//var filename = '';

{literal}

function import_src(src) {
	var oth  = ( src == 'file' ? 'text' : 'file' );
	var tab1 = $('import_src_' + src);
	var tab0 = $('import_src_' + oth);
	//var lnk1 = $('import_tab_' + src);
	//var lnk0 = $('import_tab_' + oth);
	var frm  = $('import_type');
	tab1.className = 'adesk_block';
	tab0.className = 'adesk_hidden';
	//lnk1.className = 'currenttab';
	//lnk0.className = 'othertab';
	frm.value = src;
	srcID = src;
}

function import_back() {
	// clear out something, reset to default?
	import_step_show(stepID - 1);
	// set multiple flag
	if ( multiDestination ) {
		$('relidField').name = 'relid';
	}
}

function import_step_show(step) {
	stepID = step;
	for ( var i = 0; i < steps.length; i++ ) {
		var x = steps[i];
		$('import_' + steps[i]).className = ( i + 1 == step ? 'adesk_block' : 'adesk_hidden' );
		$('step_' + steps[i]).className = ( i + 1 == step ? 'currentstep' : 'otherstep' );
	}
	// back button
	$('import_back').className = ( stepID > 1 ? 'adesk_button_back' : 'adesk_hidden' );
	// test button
	//$('import_test').className = ( stepID == 2 ? 'adesk_button_test' : 'adesk_hidden' );
	$('import_test').className = ( stepID > 1 ? 'adesk_button_test' : 'adesk_hidden' );
	// import button
	//$('import_next').className = ( stepID < 3 ? 'adesk_button_import' : 'adesk_hidden' );
	$('import_next').value = ( stepID > 1 ? jsImport : jsNext );
}

function import_next(isTest) {
	$('relidField').name = 'relid';
	var post = adesk_form_post($('import_area'));
	if ( stepID == 3 ) { // importing
		// all good, send command to initiate step switch
		import_run_callback(isTest);
	} else if ( stepID == 2 ) { // mapping
		// check if there are no duplicate mappings
		var rel = $('mappingTable');
		var selects = rel.getElementsByTagName('select');
		var selected = [ ];
		for ( var i = 0; i < selects.length; i++ ) {
			if ( adesk_array_has(selected, selects[i].value) ) {
				alert(importDuplicateMapping);
				selects[i].focus();
				return false;
			}
			if ( selects[i].value != 'DNI' ) selected.push(selects[i].value);
		}
		// check if all required (standard) fields are mapped
		for ( var i in fields ) {
			var f = fields[i];
			if ( typeof(f) != 'function' ) {
				if ( fields[i].req && !adesk_array_has(selected, fields[i].id) ) {
					alert(sprintf(importMissingMapping, fields[i].name + ' (' + fields[i].id + ')'));
					return false;
				}
			}
		}
		// all good, send command to initiate step switch
		import_map_callback(isTest);
	} else /*if ( stepID == 1 )*/ { // selecting a source
		// check for relation
		var rel = $('relidField');
		if ( rel.value == 0 && rel.nodeName == 'SELECT' ) {
			if ( $('importIntoBox').className != 'adesk_hidden' ) {
				alert(importMissingRelid);
				rel.focus();
				return;
			}
		}
		// check for type
		if ( srcID == 'text' ) {
			var rel = $('import_text');
			if ( adesk_str_trim(rel.value) == '' ) {
				alert(importMissingText);
				rel.focus();
				return;
			}
			post.delimiter = post.delimiter_text;
		} else { // file
			var inputs = $('import_file_list').getElementsByTagName('input');
			var found = 0;
			for ( var i = 0; i < inputs.length; i++ ) {
				if ( inputs[i].type == 'checkbox' && inputs[i].checked ) {
					found++;
				}
			}
			if ( found != 1 ) {
				alert(importMissingFile);
				return;
			}
			post.delimiter = post.delimiter_file;
		}
		// all good, send command to initiate step switch
		adesk_ui_api_call(jsLoading, 60);
		adesk_ajax_post_cb('awebdeskapi.php', importer + '.adesk_import_src', import_src_callback, post);
		//stepID = 2;
	}
}

function import_src_callback(xml, txt) {
	var ary = adesk_dom_read_node(xml, ( paginator_b64 ? adesk_b64_decode : null ));
	adesk_ui_api_callback();
	if ( ary.succeeded && ary.succeeded == 1 ) {
		/* step 2 - map fields */
		var f = null;
		var emailFound = false;
		var emailFind = false;
		// remove all field mappings
		var rel = $('mappingTable');
		adesk_dom_remove_children(rel);
		if ( ary.fields && ( ary.standardfields || ary.customfields ) ) {
			// print all foreign fields
			for ( var i = 0; i < ary.fields.length; i++ ) {
				// prepare local fields
				var nodes = [ ];
				if ( ary.standardfields ) {
					for ( var j in ary.standardfields ) {
						var r = ary.standardfields[j];
						if ( typeof r != 'function' ) {
							if ( r.id == 'email' ) emailFind = true;
							nodes.push(
								Builder.node('option', { value: r.id }, [ Builder._text(r.name) ])
							);
						}
					}
				}
				if ( ary.customfields ) {
					var subnodes = [ ];
					for ( var j in ary.customfields ) {
						var r = ary.customfields[j];
						if ( typeof r != 'function' ) {
							subnodes.push(
								Builder.node('option', { value: '_f' + r.id }, [ Builder._text(r.title) ])
							);
						}
					}
					if ( subnodes.length > 0 ) {
						nodes.push(
							Builder.node('optgroup', { label: strPersCustomFields }, subnodes)
						);
					}
				}
				// prepare foreign fields
				f = ary.fields[i];
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
											name: 'dest[' + f.id + ']', id: 'dest_' + f.name
										},
										nodes
									)
								]
							)
						]
					)
				);
				var val = 'DNI';
				if ( emailFind && !emailFound && adesk_str_email(f.name + '') ) {
					val = 'email';
					emailFound = true;
				}
				rel.getElementsByTagName('select')[rel.getElementsByTagName('select').length - 1].value = val;
			}
			//fields = ary.fields;
		}
		// check if permissions are still valid
		if ($("import_limit_warning"))
			$('import_limit_warning').className = ( ary.valid != 1 ? 'adesk_block' : 'adesk_hidden' );
		// save filename hash
		//filename = ary.filename;
		if ( $('report_count') ) $('report_count').innerHTML = ary.rows;
		// go to a next step
		import_step_show(2);
		// show result
		adesk_result_show(ary.message);
	} else {
		adesk_error_show(ary.message);
	}
}

function import_map_callback(isTest) {
	// show result
	adesk_result_show(importSuccessfulMapping);
	// go to a next step
	import_step_show(3);
	// run/test import
	import_run_callback(isTest);
}

function import_run_callback(isTest) {
	adesk_loader_show(jsImporting);
	//adesk_ui_api_call(jsImporting, 60);
	// show iframe
	adesk_progressbar_set("progressBar", 0);
	$('importRunNotice').className = ( isTest ? 'adesk_hidden' : 'adesk_block' );
	$('importRunResult').className = 'adesk_hidden';
	$('importRunFrame').className = 'adesk_block';
	$('importRunFrame').height = ( isTest ? '300' : '1' );
	$('importRunFrame').width = ( isTest ? '100%' : '1' );
	// set test flag
	$('importRunType').value = ( isTest ? 1 : 0 );
	// set multiple flag
	$('relidField').name = 'relid';
	if ( multiDestination ) {
		var rel = $('relidField');
		if ( rel && rel.multiple )
			$('relidField').name = 'relid[]';
	}
	// submit form
	$('importRunForm').submit();
	// show progress bar
	if ( !isTest ) {
		$("buttons").className = "adesk_hidden";
		$("backlink").className = "adesk_block";
	}
	return false;
}


function import_relid_change(newval) {
	if ( typeof(newval.join) == 'function' ) {
		var getval = newval.join(',');
	} else {
		var getval = parseInt(newval, 10);
	}
	$('import_file_iframe').src = $('import_file_iframe').src.replace(/&relid=(.*)&/, '&relid=' + getval + '&');
	adesk_ihook('ihook_import_relid_change', newval);
}

function import_progressbar_callback(ary) {
	if ( parseInt(ary.percentage) == 100 ) {
		// stop the progressbar
		adesk_progressbar_unregister("progressBar");
		$('importRunNotice').className = 'adesk_hidden';
		$('importRunResult').className = 'adesk_block';
		adesk_loader_hide();
	}
}

function import_report() {
	// fetch import logs
	adesk_ui_api_call(jsLoading, 60);
	adesk_ajax_call_cb('awebdeskapi.php', importer + '.adesk_import_report', import_report_cb, processID);
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
	ary.total  = parseInt($('report_count').innerHTML, 10);
	ary.total1 = ary.total - ary.total0;
	for ( var i in ary.counts ) {
		if ( typeof ary.counts[i] != 'function' ) {
			ary.counts[i] = parseInt(ary.counts[i], 10);
		}
	}

	$('report_count0').innerHTML = ary.total0;
	$('report_count1').innerHTML = ary.total1;

	if ( typeof(ihook_import_report) == 'function' ) ihook_import_report(ary);

	// show it
	//adesk_dom_toggle_display('import_report', 'block');
	adesk_dom_display_block('import_report');
}

{/literal}
