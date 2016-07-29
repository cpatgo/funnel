var batch_form_str_error1 = '{"Please include email addresses (one per line) in the textbox"|alang|js}';
var batch_form_str_error2 = '{"Please select at least one list"|alang|js}';
var batch_form_str_error3 = '{"Please include a date in the text box"|alang|js}';
var batch_form_str_confirm1 = '{"Are you sure you want to remove ALL subscribers listed?"|alang|js}';
var batch_form_str_confirm2 = '{"Are you sure you want to remove ALL subscribers from these lists?"|alang|js}';
var batch_form_str_confirm3 = '{"Are you sure you want to remove ALL subscribers prior to "|alang|js}';
var batch_form_str_confirm4 = '{"Are you sure you want to remove ALL subscribers with invalid emails from these lists? Its okay. Go ahead and cleanup them :) This is just ignorable alert."|alang|js}';

var batch_form_id = 0;

{literal}

/*
function batch_process(loc, hist) {
	if ( loc == '' ) {
		loc = 'list-' + batch_list_sort + '-' + batch_list_offset + '-' + batch_list_filter;
		adesk_ui_rsh_save(loc);
	}
	var args = loc.split("-");

	$("list").className = "adesk_hidden";
	$("form").className = "adesk_hidden";
	var func = null;
	try {
		var func = eval("batch_process_" + args[0]);

	} catch (e) {
		if (typeof batch_process_list == "function")
			batch_process_list(args);
	}
	if (typeof func == "function")
		func(args);
}
*/

function batch_exec(section) {

	var post = adesk_form_post($('form'));

	var list_inputs = $("parentsList").getElementsByTagName("input");

	var list_inputs_selected = "";

	for (var i = 0; i < list_inputs.length; i++) {
		if (list_inputs[i].checked) {
			list_inputs_selected += list_inputs[i].value + ",";
		}
	}

	// If no lists selected
	if (list_inputs_selected == "") {
		alert(batch_form_str_error2);
		return;
	}

	$("batch_action").value = section;

	// "Remove a select list of addresses"
	if (section == "batchremovepanel") {

		// If no email addresses in textarea
		if ( $("emailBox").value == "" ) {
			alert(batch_form_str_error1);
			return;
		}

		if ( confirm(batch_form_str_confirm1) ) {
			$("form").submit();
		}
	}

	// "Remove all non-confirmed subscribers from these lists"
	if (section == "batchoptimizepanel") {

		// If no date in textbox
		if ( $("batchoptimizepanel_field").value == "" ) {
			alert(batch_form_str_error3);
			return;
		}

		if ( confirm(batch_form_str_confirm3 + $("batchoptimizepanel_field").value + "?") ) {
			$("form").submit();
		}
	}

	// "Remove all subscribers from these lists"
	if (section == "batchoptimizepanel2") {
		if ( confirm(batch_form_str_confirm2) ) {
			$("form").submit();
		}
	}
	// "Remove all subscribers with invalid emails from these lists"
	if (section == "batchoptimizepanel3") {
		if ( confirm(batch_form_str_confirm4) ) {
			$("form").submit();
		}
	}
}

{/literal}
