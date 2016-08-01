var archive_list_offset = "0";
var archive_list_sort_discerned = false;

{literal}

function archive_list_anchor() {
	return sprintf("list-%s-%s-%s", archive_list_sort, archive_list_offset, archive_list_filter);
}

function archive_search_defaults() {
	$("list_search").value = "";
}

function archive_list_chsort(newSortId) {
	var oldSortId = ( archive_list_sort.match(/D$/) ? archive_list_sort.substr(0, 2) : archive_list_sort );
	var oldSortObj = $('list_sorter' + oldSortId);
	var sortObj = $('list_sorter' + newSortId);
	// if sort column didn't change (only direction [asc|desc] did)
	if ( oldSortId == newSortId ) {
		// switching asc/desc
		if ( archive_list_sort.match(/D$/) ) {
			// was DESC
			newSortId = archive_list_sort.substr(0, 2);
			sortObj.className = 'adesk_sort_asc';
		} else {
			// was ASC
			newSortId = archive_list_sort + 'D';
			sortObj.className = 'adesk_sort_desc';
		}
	} else {
		// remove old list_list_sort
		if ( oldSortObj ) oldSortObj.className = 'adesk_sort_other';
		// set sort field
		sortObj.className = 'adesk_sort_asc';
	}
	archive_list_sort = newSortId;
	//adesk_ui_api_call(jsSorting);
	adesk_ui_anchor_set(archive_list_anchor());
	return false;
}

function archive_list_discern_sortclass() {
	if (archive_list_sort_discerned)
		return;

	var elems = $("list_head").getElementsByTagName("a");

	for (var i = 0; i < elems.length; i++) {
		var str = sprintf("list_sorter%s", archive_list_sort.substring(0, 2));

		if (elems[i].id == str) {
			if (archive_list_sort.match(/D$/))
				elems[i].className = "adesk_sort_desc";
			else
				elems[i].className = "adesk_sort_asc";
		} else {
			elems[i].className = "adesk_sort_other";
		}
	}

	archive_list_sort_discerned = true;
}

function archive_process(loc, hist) {
	if (loc == '') {
		loc = 'list-' + archive_list_sort + '-' + archive_list_offset + '-' + archive_list_filter;
		//adesk_ui_rsh_save(loc);
	}

	var args = loc.split("-");

	//$("list").className = "adesk_hidden";
	//$("form").className = "adesk_hidden";

	try {
		var func = eval("archive_process_" + args[0]);

		if (typeof func == "function")
			func(args);
	} catch (e) {
		if (typeof archive_process_list == "function")
			archive_process_list(args);
	}
}

function archive_process_list(args) {
	if (args.length < 2)
		args = ["list", archive_list_sort, archive_list_offset, archive_list_filter];

	archive_list_sort = args[1];
	archive_list_offset = args[2];
	// Do not allow user to overwrite filterid to 0, which would remove public/private List filter
	archive_list_filter = (args[3] == "0") ? $("filterid").value : args[3];

	archive_list_discern_sortclass();

	paginators[1].paginate(archive_list_offset);
}

{/literal}
