var report_list_subscription_table = new ACTable();

var report_list_subscription_list_sort = "01";
var report_list_subscription_list_offset = 0;
var report_list_subscription_list_filter = {jsvar var=$filterid};
var report_list_subscription_list_sort_discerned = false;

var report_list_subscription_id = {jsvar var=$lid};

{literal}
function report_list_subscription_process(loc, hist) {
	if ( loc == '' ) {
		var args = ["general", report_list_subscription_list_sort, report_list_subscription_list_offset, report_list_subscription_list_filter];
		loc = args.join('-');
		adesk_ui_rsh_save(loc);
	}
	var args = loc.split("-");

	$("general").className = "adesk_hidden";

	var func = null;
	try {
		var func = eval("report_list_subscription_process_" + args[0]);

	} catch (e) {
		if (typeof report_list_subscription_process_list == "function")
			report_list_subscription_process_general(args);
	}
	if (typeof func == "function")
		func(args);
}

function report_list_subscription_process_general(args) {
	if (args.length < 4)
		args = ["general", report_list_subscription_list_sort, report_list_subscription_list_offset, report_list_subscription_list_filter];

	report_list_subscription_list_sort = args[1];
	report_list_subscription_list_offset = args[2];
	report_list_subscription_list_filter = args[3];


	report_list_subscription_list_discern_sortclass();

	//$("general").className = "adesk_block";

	paginators[1].paginate(report_list_subscription_list_offset);
}

function report_list_subscription_list_anchor() {
	return sprintf("general-%s-%s-%s", report_list_subscription_list_sort, report_list_subscription_list_offset, report_list_subscription_list_filter);
}



function report_list_subscription_list_chsort(newSortId) {
	var oldSortId = ( report_list_subscription_list_sort.match(/D$/) ? report_list_subscription_list_sort.substr(0, 2) : report_list_subscription_list_sort );
	var oldSortObj = $('list_sorter' + oldSortId);
	var sortObj = $('list_sorter' + newSortId);
	// if sort column didn't change (only direction [asc|desc] did)
	if ( oldSortId == newSortId ) {
		// switching asc/desc
		if ( report_list_subscription_list_sort.match(/D$/) ) {
			// was DESC
			newSortId = report_list_subscription_list_sort.substr(0, 2);
			sortObj.className = 'adesk_sort_asc';
		} else {
			// was ASC
			newSortId = report_list_subscription_list_sort + 'D';
			sortObj.className = 'adesk_sort_desc';
		}
	} else {
		// remove old report_list_subscription_list_sort
		if ( oldSortObj ) oldSortObj.className = 'adesk_sort_other';
		// set sort field
		sortObj.className = 'adesk_sort_asc';
	}
	report_list_subscription_list_sort = newSortId;
	adesk_ui_api_call(jsSorting);
	adesk_ui_anchor_set(report_list_subscription_list_anchor());
	return false;
}

function report_list_subscription_list_discern_sortclass() {
	if (report_list_subscription_list_sort_discerned)
		return;

	var elems = $("list_head").getElementsByTagName("a");

	for (var i = 0; i < elems.length; i++) {
		var str = sprintf("list_sorter%s", report_list_subscription_list_sort.substring(0, 2));

		if (elems[i].id == str) {
			if (report_list_subscription_list_sort.match(/D$/))
				elems[i].className = "adesk_sort_desc";
			else
				elems[i].className = "adesk_sort_asc";
		} else {
			elems[i].className = "adesk_sort_other";
		}
	}

	report_list_subscription_list_sort_discerned = true;
}

/*
function report_list_subscription_process_form(args) {
	if (args.length < 2)
		args = ["form", "0"];

	var id = parseInt(args[1], 10);

	report_list_subscription_form_load(id);
}
*/


function report_list_subscription_list_clear() {
	report_list_subscription_list_sort = "01";
	report_list_subscription_list_offset = 0;
	report_list_subscription_list_filter = 0;
	$("list_search").value = "";
	$('datetimeselect').value = 'all';
	var select = $('datetimeselect');
	var options = select.getElementsByTagName('option');
	var value = options[select.selectedIndex];
	$('datetimelabel').innerHTML = value.innerHTML;
	//report_list_subscription_search_defaults();
	adesk_ui_anchor_set(report_list_subscription_list_anchor());
}

function report_list_subscription_list_search() {
	//var post = adesk_form_post($("general"));
	var post = adesk_form_post($('admin_content'));
	report_list_subscription_list_filter = post.listid;
	adesk_ajax_post_cb("awebdeskapi.php", "report_list_subscription.report_list_subscription_filter_post", report_list_subscription_list_search_cb, post);
}

function report_list_subscription_list_search_cb(xml) {
	var ary = adesk_dom_read_node(xml);

	// save filter id
	report_list_subscription_list_filter = ary.filterid;

	// charts
	//var mode = ( report_list_subscription_id > 0 ? 'report_list_subscription' : 'report_list_subscription' );
	var mode = 'report_list_subscription';

	adesk_ui_anchor_set(report_list_subscription_list_anchor());
}

function report_list_subscription_filter_datetime() {
	//var post = adesk_form_post($("general"));
	var post = adesk_form_post($('admin_content'));
	// datetime panel
	var select = $('datetimeselect');
	var options = select.getElementsByTagName('option');
	var value = options[select.selectedIndex];
	if ( value.value == 'range' ) {
		$('datetimelabel').innerHTML = post.from + ' - ' + post.to;
	} else {
		$('datetimelabel').innerHTML = value.innerHTML;
	}
	adesk_dom_toggle_class('datetimefilter', 'adesk_block', 'adesk_hidden');

	// submit the filter to narrow down the search
	adesk_ajax_post_cb("awebdeskapi.php", "report_list_subscription.report_list_subscription_filter_post", report_list_subscription_list_search_cb, post);

}

function report_list_subscription_showdiv_general(id, label) {
	$("chart_subscribed_bydate").style.display = "none";
	$("chart_unsubscribed_bydate").style.display = "none";
	$("chart_totalsubscribed_bydate").style.display = "none";
	$("chart_totalunsubscribed_bydate").style.display = "none";
	$("chart_read_byhour").style.display = "none";
	$("general_subscribelabel").className = "";
	$("general_unsubscribelabel").className = "";
	$("general_totalsubscribelabel").className = "";
	$("general_totalunsubscribelabel").className = "";
	$("general_readlabel").className = "";

	$(id).style.display = "";
	$(label).className  = "startup_selected";
}

function report_list_subscription_export() {
	var url = window.location.href.replace(/#.*$/, "");
	url += sprintf("&export=%s&filterid=%d", 1, report_list_subscription_list_filter);

	window.location.href = url;
}

{/literal}
