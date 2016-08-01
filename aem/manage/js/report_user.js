var report_user_table = new ACTable();

var report_user_list_sort = "01";
var report_user_list_offset = 0;
var report_user_list_filter = {jsvar var=$filterid};
var report_user_list_sort_discerned = false;

var report_user_listfilter = {jsvar var=$listfilter};

var report_user_id = {jsvar var=$gid};

{literal}
function report_user_process(loc, hist) {
	if ( loc == '' ) {
		var args = ["general", report_user_list_sort, report_user_list_offset, report_user_list_filter];
		loc = args.join('-');
		adesk_ui_rsh_save(loc);
	}
	var args = loc.split("-");

	$("general").className = "adesk_hidden";

	var func = null;
	try {
		var func = eval("report_user_process_" + args[0]);

	} catch (e) {
		if (typeof report_user_process_list == "function")
			report_user_process_general(args);
	}
	if (typeof func == "function")
		func(args);
}

function report_user_process_general(args) {
	if (args.length < 4)
		args = ["general", report_user_list_sort, report_user_list_offset, report_user_list_filter];

	report_user_list_sort = args[1];
	report_user_list_offset = args[2];
	report_user_list_filter = args[3];


	if ( report_user_listfilter > 0 ) $('JSListManager').value = report_user_listfilter;

	report_user_list_discern_sortclass();

	//$("general").className = "adesk_block";

	paginators[1].paginate(report_user_list_offset);
}

function report_user_list_anchor() {
	return sprintf("general-%s-%s-%s", report_user_list_sort, report_user_list_offset, report_user_list_filter);
}



function report_user_list_chsort(newSortId) {
	var oldSortId = ( report_user_list_sort.match(/D$/) ? report_user_list_sort.substr(0, 2) : report_user_list_sort );
	var oldSortObj = $('list_sorter' + oldSortId);
	var sortObj = $('list_sorter' + newSortId);
	// if sort column didn't change (only direction [asc|desc] did)
	if ( oldSortId == newSortId ) {
		// switching asc/desc
		if ( report_user_list_sort.match(/D$/) ) {
			// was DESC
			newSortId = report_user_list_sort.substr(0, 2);
			sortObj.className = 'adesk_sort_asc';
		} else {
			// was ASC
			newSortId = report_user_list_sort + 'D';
			sortObj.className = 'adesk_sort_desc';
		}
	} else {
		// remove old report_user_list_sort
		if ( oldSortObj ) oldSortObj.className = 'adesk_sort_other';
		// set sort field
		sortObj.className = 'adesk_sort_asc';
	}
	report_user_list_sort = newSortId;
	adesk_ui_api_call(jsSorting);
	adesk_ui_anchor_set(report_user_list_anchor());
	return false;
}

function report_user_list_discern_sortclass() {
	if (report_user_list_sort_discerned)
		return;

	var elems = $("list_head").getElementsByTagName("a");

	for (var i = 0; i < elems.length; i++) {
		var str = sprintf("list_sorter%s", report_user_list_sort.substring(0, 2));

		if (elems[i].id == str) {
			if (report_user_list_sort.match(/D$/))
				elems[i].className = "adesk_sort_desc";
			else
				elems[i].className = "adesk_sort_asc";
		} else {
			elems[i].className = "adesk_sort_other";
		}
	}

	report_user_list_sort_discerned = true;
}

/*
function report_user_process_form(args) {
	if (args.length < 2)
		args = ["form", "0"];

	var id = parseInt(args[1], 10);

	report_user_form_load(id);
}
*/


function report_user_list_clear() {
	report_user_list_sort = "01";
	report_user_list_offset = 0;
	report_user_list_filter = 0;
	report_user_listfilter = null;
	$("JSListManager").value = 0;
	$("list_search").value = "";
	$('datetimeselect').value = 'all';
	var select = $('datetimeselect');
	var options = select.getElementsByTagName('option');
	var value = options[select.selectedIndex];
	$('datetimelabel').innerHTML = value.innerHTML;
	list_filters_update(0, 0, true);
	//report_user_search_defaults();
	adesk_ui_anchor_set(report_user_list_anchor());
}

function report_user_list_search() {
	//var post = adesk_form_post($("general"));
	var post = adesk_form_post($('admin_content'));
	report_user_listfilter = post.listid;
	list_filters_update(0, post.listid, false);
	var mode = ( report_user_id > 0 ? 'report_user' : 'report_group' );
	adesk_ajax_post_cb("awebdeskapi.php", mode + "." + mode + "_filter_post", report_user_list_search_cb, post);
}

function report_user_list_search_cb(xml) {
	var ary = adesk_dom_read_node(xml);

	// save filter id
	report_user_list_filter = ary.filterid;

	// charts
	var mode = ( report_user_id > 0 ? 'report_user' : 'report_group' );
//prompt('url1', plink + '/manage/graph.php?g=emails_bydate&id=' + report_user_id + '&mode=' + mode + '&filterid=' + report_user_list_filter);
//prompt('url2', plink + '/manage/graph.php?g=campaigns_bydate&id=' + report_user_id + '&mode=' + mode + '&filterid=' + report_user_list_filter);
	// emails chart
	adesk_amchart({
		type     : 'line',
		divid    : 'chart_emails_bydate',
		width    : '100%',
		height   : '175',
		bgcolor  : '#FFFFFF',
		location : 'admin',
		url      : 'graph.php?g=emails_bydate&id=' + report_user_id + '&mode=' + mode + '&filterid=' + report_user_list_filter,
		write    : true
	});
	// campaigns chart
	adesk_amchart({
		type     : 'line',
		divid    : 'chart_campaigns_bydate',
		width    : '100%',
		height   : '175',
		bgcolor  : '#FFFFFF',
		location : 'admin',
		url      : 'graph.php?g=campaigns_bydate&id=' + report_user_id + '&mode=' + mode + '&filterid=' + report_user_list_filter,
		write    : true
	});

	adesk_ui_anchor_set(report_user_list_anchor());
}

function report_user_filter_datetime() {
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
	report_user_listfilter = post.listid;
	list_filters_update(0, post.listid, false);
	var mode = ( report_user_id > 0 ? 'report_user' : 'report_group' );
	adesk_ajax_post_cb("awebdeskapi.php", mode + "." + mode + "_filter_post", report_user_list_search_cb, post);

}

function report_user_showdiv_general(id, label) {
	$("chart_emails_bydate").style.display = "none";
	$("chart_campaigns_bydate").style.display = "none";
	$("general_emailslabel").className = "";
	$("general_campaignslabel").className = "";

	$(id).style.display = "";
	$(label).className  = "startup_selected";
}

function report_user_export() {
	var url = window.location.href.replace(/#.*$/, "");
	url += sprintf("&export=%s&filterid=%d", 1, report_user_list_filter);

	window.location.href = url;
}

function report_user_print() {
	var url = window.location.href.replace(/\?/, "?print=1&");
	window.open(url);
}

{/literal}
