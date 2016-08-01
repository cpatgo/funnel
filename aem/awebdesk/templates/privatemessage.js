{include file="privatemessage.list.js"}
{include file="privatemessage.form.js"}
{include file="privatemessage.delete.js"}
{include file="privatemessage.search.js"}

{literal}
function privatemessage_process(loc, hist) {
	if ( loc == '' ) {
		loc = 'list-' + privatemessage_list_sort + '-' + privatemessage_list_offset + '-' + privatemessage_list_filter;
		adesk_ui_rsh_save(loc);
	}
	var args = loc.split("-");

	$("list").className = "adesk_hidden";
	$("form").className = "adesk_hidden";
	var func = null;
	try {
		var func = eval("privatemessage_process_" + args[0]);

	} catch (e) {
		if (typeof privatemessage_process_list == "function")
			privatemessage_process_list(args);
	}
	if (typeof func == "function")
		func(args);

	var feed_url = $("privatemessage_feed_link").href;

	if ($("privatemessage_filter").value == "user_to") {

		// Inbox view

		$("from_to_td").innerHTML = privatemessage_str_from;
		$("list_sorter02").innerHTML = privatemessage_str_received;

		$("privatemessage_feed_link").style.display = "inline";

		/*
		if (feed_url.substr(-6, 5) == "&rss=") {
			$("privatemessage_feed_link").href = feed_url.substr(0, feed_url.length - 1) + "1";
		}
		else {
			$("privatemessage_feed_link").href = feed_url + "&rss=1";
		}
		*/

		$("privatemessage_export_link").href = "desk.php?action=privatemessage&export=1";
	}
	else if ($("privatemessage_filter").value == "user_from") {

		// Sent view

		$("from_to_td").innerHTML = privatemessage_str_to;
		$("list_sorter02").innerHTML = privatemessage_str_sent;

		$("privatemessage_feed_link").style.display = "none";

		/*
		if (feed_url.substr(-6, 5) == "&rss=") {
			$("privatemessage_feed_link").href = feed_url.substr(0, feed_url.length - 1) + "2";
		}
		else {
			$("privatemessage_feed_link").href = feed_url + "&rss=2";
		}
		*/

		$("privatemessage_export_link").href = "desk.php?action=privatemessage&export=2";
	}
	else {
		// All view
		$("from_to_td").innerHTML = privatemessage_str_fromto;
	}
}

function privatemessage_process_list(args) {
	if (args.length < 2)
		args = ["list", privatemessage_list_sort, privatemessage_list_offset, privatemessage_list_filter];

	privatemessage_list_sort = args[1];
	privatemessage_list_offset = args[2];
	privatemessage_list_filter = args[3];

	if (privatemessage_list_filter == 0) {
		$("privatemessage_filter").value = "user_to";
	}

	privatemessage_list_discern_sortclass();

	paginators[1].paginate(privatemessage_list_offset);
}

function privatemessage_process_form(args) {
	if (args.length < 2)
		args = ["form", "0"];

	var id = parseInt(args[1], 10);

	privatemessage_form_load(id);
}

function privatemessage_process_delete(args) {
	if (args.length < 2) {
		privatemessage_process_list(["list", "0"]);
		return;
	}

	$("list").className = "adesk_block";
	var id = parseInt(args[1], 10);

	privatemessage_delete_check(id);
}

function privatemessage_process_delete_multi(args) {
	$("list").className = "adesk_block";
	privatemessage_delete_check_multi();
}

function privatemessage_process_search(args) {
	$("list").className = "adesk_block";
	privatemessage_search_check();
}
{/literal}
