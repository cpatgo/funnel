var adesk_calendar_use_link = '';

function adesk_calendar_sanitize(str) {
	str = strip_tags(str);
	str = str.replace(/Untitled document/, '');
	str = str.replace(/^\s+/, '');
	str = str.replace(/\s+$/, '');

	return str;
}

function adesk_calendar_cell_date_display(size, link) {

	if (size == "normal") {

		if (link != '') {

			var node = Builder.node("a", { href: adesk_calendar_use_link + "#day-" + ary.cells[i].year + "-" + month + "-" + day }, ary.cells[i].day);
		}
		else {

			var node = Builder.node( "span", { className: "event" }, Builder.node("a", { href: adesk_calendar_use_link + "#day-" + ary.cells[i].year + "-" + month + "-" + day }, ary.cells[i].day) );
		}
	}
	else {

		if (link != '') {

			var node = Builder.node("a", { href: adesk_calendar_use_link + "#day-" + ary.cells[i].year + "-" + month + "-" + day, className: "event", onmouseover: "adesk_tooltip_show('" + adesk_calendar_sanitize(tooltip) + "', 150, '', false)", onmouseout: "adesk_tooltip_hide()" }, ary.cells[i].day);
		}
		else {

			var node = Builder.node("span", { onmouseover: "adesk_tooltip_show('" + adesk_calendar_sanitize(tooltip) + "', 150, '', false)", className: "event", onmouseout: "adesk_tooltip_hide()" }, ary.cells[i].day);
		}
	}

	return node;
}

function adesk_calendar_display_month(year, month, size, link, filterids) {
	adesk_calendar_use_link = link;
	calendar_filter_type = "event";
	if ($("list")) {
		var post = adesk_form_post($("list"));
		if (typeof post.calendar_filter_type != "undefined") {
			calendar_filter_type = post.calendar_filter_type;
		}
	}
	adesk_ajax_call_cb("awebdeskapi.php", "calendar!adesk_calendar_select_month", adesk_calendar_display_month_cb, year, month, size, filterids, calendar_filter_type);
}

function adesk_calendar_display_month_cb(xml) {

	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	calendar_list_filter = ary.filterid_event;
	calendar_list_filter_task = ary.filterid_task;
	calendar_list_filter_ticket = ary.filterid_ticket;

	if (ary.calendar_size == "tiny") {

		var calendar_view_id = "calendar_month_view_tiny";

		var days_ths = $("days").getElementsByTagName("th");

		for (var i = 0; i < days_ths.length; i++) {
			days_ths[i].innerHTML = days_ths[i].className.substring(0, 1).toUpperCase();
		}
	}
	else if (ary.calendar_size == "small") {

		var calendar_view_id = "calendar_month_view_small";

		var days_ths = $("days").getElementsByTagName("th");

		for (var i = 0; i < days_ths.length; i++) {
			days_ths[i].innerHTML = days_ths[i].className.capitalize();
		}
	}
	else {

		var calendar_view_id = "calendar_month_view";
	}

	var calendar_tds = $(calendar_view_id).getElementsByTagName("td");
	var calendar_tbodys = $(calendar_view_id).getElementsByTagName("tbody");

	// Clear out all cells first and reset the class names to nothing.
	for (var i = 0; i < calendar_tds.length; i++) {
		calendar_tds[i].className = "";
		calendar_tds[i].innerHTML = "";
		calendar_tbodys[1].className = "";
		calendar_tbodys[2].className = "";
	}

	// Loop through the days
	for (var i = 0; i < ary.cells.length; i++) {

		var year = ary.cells[i].year;
		//var month = (ary.cells[i].month < 10) ? "0" + ary.cells[i].month : ary.cells[i].month;
		var month = ary.cells[i].month;
		var day = (ary.cells[i].day < 10) ? "0" + ary.cells[i].day : ary.cells[i].day;

		if (ary.cells[i].month != ary.month_without_zero) {
			$("cell" + i).className = "prev";
		}

		if (ary.cells[i].today == 1) {
			$("cell" + i).className = "today";
		}

		var cell_date_children = new Array();
		var cell_events = new Array();
		var cell_tasks = new Array();
		var cell_tickets = new Array();
		var div_classname = "event1";

		// Loop through events.
		if (ary.cells[i].events.length) {

			var tooltip = "";

			// If there's less than 3 events for the day
			if (ary.cells[i].events.length < 3) {

				for (var j = 0; j < ary.cells[i].events.length; j++) {

					// If End Date is set.
					if (ary.cells[i].events[j].edate != "") {

						// grab just the day portion of the date/time string
						var sdate_day = sql2date( ary.cells[i].events[j].sdate ).format("%Y-%m-%d");
						var edate_day = sql2date( ary.cells[i].events[j].edate ).format("%Y-%m-%d");

						// If End Date is greater than Start Date (just the day portion)
						if (edate_day > sdate_day) {

							// If the current day in the loop is equal to the Start Date day.
							if (day == ary.cells[i].events[j].sdate.substring(8, 10)) {
								// Beginning day.
								div_classname = "event1 right_arrow";
							}
							else if (day == ary.cells[i].events[j].edate.substring(8, 10)) {
								// If the current day in the loop is equal to the End Date day.

								// End day.
								div_classname = "event1 left_arrow";
							}
							else {
								// Middle days.
								div_classname = "event1 left_right_arrow";
							}
						}
						else {
							div_classname = "event1";
						}
					}
					else {
						div_classname = "event1";
					}

					if (ary.calendar_size == "normal") {

						var event_wrapper_a = Builder.node( "a", { href: "#form-" + ary.cells[i].events[j].id, onmouseover: "adesk_tooltip_show('" + adesk_calendar_sanitize(ary.cells[i].events[j].content) + "', 150, '', false);", onmouseout: "adesk_tooltip_hide();" }, ary.cells[i].events[j].title.substring(0, 15) );
						var event_wrapper_span = Builder.node( "span", { onmouseover: "adesk_tooltip_show('" + adesk_calendar_sanitize(ary.cells[i].events[j].content) + "', 150, '', false);", onmouseout: "adesk_tooltip_hide();" }, ary.cells[i].events[j].title.substring(0, 15) );

						var cell_event = Builder.node(
							"div",
							{ className: div_classname },
							[
								( (adesk_js_admin.pg_calendar_edit != 1) ? event_wrapper_span : event_wrapper_a )
							]
						);

						cell_events.push(cell_event);
					}

					tooltip += adesk_str_htmlescape(ary.cells[i].events[j].title) + "<br />";
				}

				if (ary.calendar_size == "normal") {

					if (adesk_calendar_use_link != '') {

						cell_date_children.push( Builder.node("a", { href: adesk_calendar_use_link + "#day-" + ary.cells[i].year + "-" + month + "-" + day }, ary.cells[i].day) );
					}
					else {

						cell_date_children.push( Builder.node( "span", { className: "event" }, Builder.node("a", { href: adesk_calendar_use_link + "#day-" + ary.cells[i].year + "-" + month + "-" + day }, ary.cells[i].day) ) );
					}
				}
				else {

					if (adesk_calendar_use_link != '') {

						cell_date_children.push( Builder.node("a", { href: adesk_calendar_use_link + "#day-" + ary.cells[i].year + "-" + month + "-" + day, className: "event", onmouseover: "adesk_tooltip_show('" + adesk_calendar_sanitize(tooltip) + "', 150, '', false)", onmouseout: "adesk_tooltip_hide()" }, ary.cells[i].day) );
					}
					else {

						cell_date_children.push( Builder.node("span", { onmouseover: "adesk_tooltip_show('" + adesk_calendar_sanitize(tooltip) + "', 150, '', false)", className: "event", onmouseout: "adesk_tooltip_hide()" }, ary.cells[i].day) );
					}
				}
			}
			else {

				// "2+ Events"
				// 3 or more events for the day

				//tooltip += adesk_str_htmlescape(ary.cells[i].events[j].title) + "<br/>";

				if (ary.calendar_size == "small") {

					if (adesk_calendar_use_link != '') {

						cell_date_children.push( Builder.node("a", { href: adesk_calendar_use_link + "#day-" + ary.cells[i].year + "-" + month + "-" + day, className: "event", onmouseover: "adesk_tooltip_show('" + adesk_calendar_sanitize(tooltip) + "', 150, '', false)", onmouseout: "adesk_tooltip_hide()" }, ary.cells[i].day) );
					}
					else {

						cell_date_children.push( Builder.node("span", { onmouseover: "adesk_tooltip_show('" + adesk_calendar_sanitize(tooltip) + "', 150, '', false)", className: "event", onmouseout: "adesk_tooltip_hide()" }, ary.cells[i].day) );
					}
				}
				else {

					if (adesk_calendar_use_link != '') {

						cell_date_children.push( Builder.node("a", { href: adesk_calendar_use_link + "#day-" + ary.cells[i].year + "-" + month + "-" + day, className: "event", onmouseover: "adesk_tooltip_show('" + adesk_calendar_sanitize(tooltip) + "', 150, '', false)", onmouseout: "adesk_tooltip_hide()" }, ary.cells[i].day) );
					}
					else {

						cell_date_children.push( Builder.node( "span", { className: "event" }, Builder.node("a", { href: adesk_calendar_use_link + "#day-" + ary.cells[i].year + "-" + month + "-" + day }, ary.cells[i].day) ) );
					}

					var cell_event = Builder.node(
						"div",
						{ className: "event1" },
						Builder.node( "a", { href: "#", onclick: "calendar_display_day('" + ary.cells[i].year + "', '" + month + "', '" + day + "')" }, calendar_list_str_moreevents )
					);

					cell_events.push(cell_event);
				}
			}
		}
		else {

			// No events for the day

			/*
			var cell_date_node = adesk_calendar_cell_date_display(ary.calendar_size, adesk_calendar_use_link);
			cell_date_children.push(cell_date_node);
			*/

			if (ary.calendar_size == "small") {

				if ( adesk_calendar_use_link != '' ) {

					cell_date_children.push( Builder.node("a", { href: adesk_calendar_use_link + "#day-" + ary.cells[i].year + "-" + month + "-" + day }, ary.cells[i].day) );
				}
				else {

					cell_date_children.push( Builder.node("span", { }, ary.cells[i].day) );
				}
			}
			else {

				cell_date_children.push( Builder.node("a", { href: "#day-" + ary.cells[i].year + "-" + month + "-" + day }, ary.cells[i].day) );
			}
		}

		// Loop through tasks.
		if (ary.cells[i].tasks.length) {

			if (ary.calendar_size == "tiny" || ary.calendar_size == "small") {

			}
			else {
				if (ary.cells[i].tasks.length < 3) {
					for (var j = 0; j < ary.cells[i].tasks.length; j++) {

						cell_tasks.push(
							Builder.node( "div", { className: "event2" },
								Builder.node("a", { href: "desk.php?action=task#form-" + ary.cells[i].tasks[j].id }, ary.cells[i].tasks[j].title)
							)
						);
					}
				}
				else {

					cell_tasks.push(
						Builder.node( "div", { className: "event2" },
							Builder.node("a", { href: "#", onclick: "adesk_calendar_display_day('" + ary.cells[i].year + "', '" + month + "', '" + day + "')" }, calendar_list_str_moretasks)
						)
					);
				}
			}
		}

		// Loop through tickets.
		if (ary.cells[i].tickets.length) {

			if (ary.calendar_size == "tiny" || ary.calendar_size == "small") {

			}
			else {
				if (ary.cells[i].tickets.length < 3) {
					for (var j = 0; j < ary.cells[i].tickets.length; j++) {

						cell_tickets.push(
							Builder.node( "div", { className: "event3" },
								Builder.node("a", { href: "desk.php?action=ticket&id=" + ary.cells[i].tickets[j].id }, adesk_str_htmlescape(ary.cells[i].tickets[j].subject))
							)
						);
					}
				}
				else {

					cell_tickets.push(
						Builder.node( "div", { className: "event3" },
							Builder.node("a", { href: "#", onclick: "adesk_calendar_display_day('" + ary.cells[i].year + "', '" + month + "', '" + day + "')" }, calendar_list_str_moretickets)
						)
					);
				}
			}
		}

		var cell_date = Builder.node("div", { className: "date" }, cell_date_children);
		$("cell" + i).appendChild(cell_date);

		if (ary.calendar_size == "normal") {
			var cell_events_append = Builder.node("div", { className: "cell_events" }, cell_events);
			$("cell" + i).appendChild(cell_events_append);
		}

		var cell_tasks_append = Builder.node("div", { className: "cell_tasks" }, cell_tasks);
		$("cell" + i).appendChild(cell_tasks_append);

		var cell_tickets_append = Builder.node("div", { className: "cell_tickets" }, cell_tickets);
		$("cell" + i).appendChild(cell_tickets_append);

		//$("cell" + i).ondblclick = function() { calendar_add_event_day(year, month, day) };
	}

	// Remove orphan <table> rows.
	// 42 total possible cells that can be used, minus how many are actually used.
	// This can only equate to one of the three: 0, 7, 14 - or 0 extra rows, 1 extra row, or two extra rows.
	var leftover_cells = 42 - ary.cells.length;

	// IE: 0 / 7 = 0. 7 / 7 = 1. 14 / 7 = 2.
	var rows_to_remove = leftover_cells / 7;

	if (rows_to_remove > 0) {

		// If one row to remove, hide the last <tbody>, which is 2 in the array.
		if (rows_to_remove == 1) {
			calendar_tbodys[2].className = "adesk_hidden";
		}
		else {
			// Hide last two <tbody>'s, which are 1 and 2 in the array.
			calendar_tbodys[1].className = "adesk_hidden";
			calendar_tbodys[2].className = "adesk_hidden";
		}
	}

	$("date_header_display").innerHTML = ary.month_display + " " + ary.year;

	var previous_link_month = (ary.previous_link_month < 10) ? "0" + ary.previous_link_month : ary.previous_link_month;
	var next_link_month = (ary.next_link_month < 10) ? "0" + ary.next_link_month : ary.next_link_month;

	$("previous_link").href = "javascript: adesk_calendar_display_month('" + ary.previous_link_year + "', '" + previous_link_month + "', '" + ary.calendar_size + "', '" + adesk_calendar_use_link + "', '')";
	$("next_link").href = "javascript: adesk_calendar_display_month('" + ary.next_link_year + "', '" + next_link_month + "', '" + ary.calendar_size + "', '" + adesk_calendar_use_link + "', '')";

	if (ary.calendar_size == "tiny" || ary.calendar_size == "small") {
		$("previous_link").innerHTML = "&#171;";
		$("next_link").innerHTML = "&#187;";
	}

	//$("year").value = ary.year;
	//$("month").value = ary.month_without_zero;

	if ( typeof(calendar_list_ihook) == 'function' ) {
		calendar_list_ihook(ary);
	}

	/*
	if ( typeof calendar_list_anchor == 'function' ) {
		adesk_ui_anchor_set(calendar_list_anchor());
		adesk_ui_anchor_set( sprintf("list-%s-%s-%s-%s-%s", calendar_list_sort, calendar_list_offset, calendar_list_filter, calendar_list_filter_task, calendar_list_filter_ticket) );
	}
	*/
}

function adesk_calendar_display_month_cb_small(xml) {
	var ary = adesk_dom_read_node(xml, null);
	adesk_ui_api_callback();

	$("calendar_month_view_small").innerHTML = ary.row[0];
}

function adesk_calendar_display_day(year, month, day) {
	adesk_ajax_call_cb("awebdeskapi.php", "calendar!adesk_calendar_select_day", adesk_calendar_display_day_cb, year, month, day);
}

function adesk_calendar_display_day_cb(xml) {
	var ary = adesk_dom_read_node(xml, null);
	adesk_ui_api_callback();

	var calendar_tds = $("calendar_day_view").getElementsByTagName("td");

	// Clear out all cells first and reset the class names to nothing.
	for (var i = 0; i < calendar_tds.length; i++) {
		calendar_tds[i].innerHTML = "&nbsp;";
	}

	calendar_tds[0].innerHTML = "<strong>" + jsEvents + ":</strong>";
	calendar_tds[1].innerHTML = "<strong>" + jsTasks + ":</strong>";

	$("calendar_day_view_dayname").innerHTML = ary.day_view_dayname;

	$("calendar_day_view").className = "";
	$("calendar_month_view").className = "adesk_hidden";
	$("list").className = "adesk_hidden";

	for (var i = 0; i < ary.row.length; i++) {

		if ( typeof(ary.row[i]) == "object" ) {

			for (var key in ary.row[i]) {

				if (key == "events") {

					for (var e = 0; e < ary.row[i]["events"].length; e++) {

						var this_event = ary.row[i]["events"][e];

						// if the event start date has no time (all day event)
						if (this_event.sdate.substring(11, 19) == "00:00:00") {

							var event_label = Builder.node(
								"span",
								{ className: "event1" },
								Builder.node("a", { href: "#form-" + this_event.id }, this_event.title )
							);

							$("events00").appendChild(event_label);
						}
						else {

							var top_bottom = (this_event.sdate.substring(14, 16) < 30) ? "top" : "bottom";

							var event_label = Builder.node(
								"span",
								{ className: "event1" },
								Builder.node("a", { href: "#form-" + this_event.id }, this_event.title)
							);

							$(top_bottom + i).appendChild(event_label);
						}
					}
				}
				else if (key == "tasks") {

					for (var t = 0; t < ary.row[i]["tasks"].length; t++) {

						var this_task = ary.row[i]["tasks"][t];

						// if the task due date has no time
						if (this_task.ddate.substring(11, 19) == "00:00:00") {

							var task_label = Builder.node(
								"span",
								{ className: "event2" },
								Builder.node("a", { href: "desk.php?action=task#form-" + this_task.id }, this_task.title)
							);

							$("tasks00").appendChild(task_label);
						}
						else {

							var top_bottom = (this_task.ddate.substring(14, 16) < 30) ? "top" : "bottom";

							var task_label = Builder.node(
								"span",
								{ className: "event2" },
								Builder.node("a", { href: "desk.php?action=task#form-" + this_task.id }, this_task.title)
							);

							$(top_bottom + i).appendChild(task_label);
						}
					}
				}
			}
		}
	}

	// Loop through hours.
	for (var i = 0; i < 24; i++) {

		/*
		// Loop through events for this hour.
		for (var j = 0; j < ary.row[i].events.length; j++) {

			// Show events with no time along the top "All day" section.
			if (ary.row[i].events[j].sdate.substring(11, 19) == "00:00:00") {
				$("events00").innerHTML += "<span class='event1'><a href='#form-" + ary.row[i].events[j].id + "'>" + ary.row[i].events[j].title + "</a></span>";
			}
			else {
				var top_bottom = (ary.row[i].events[j].sdate.substring(14, 16) < 30) ? "top" : "bottom";

				$(top_bottom + i).innerHTML += "<span class='event1'><a href='#form-" + ary.row[i].events[j].id + "'>" + ary.row[i].events[j].title + "</a></span>";
			}
		}

		// Loop through tasks for this hour.
		for (var j = 0; j < ary.row[i].tasks.length; j++) {

			// Show tasks with no time along the top "All day" section.
			if (ary.row[i].tasks[j].ddate.substring(11, 19) == "00:00:00") {
				$("tasks00").innerHTML += "<span class='event2'><a href='desk.php?action=task#form-" + ary.row[i].tasks[j].id + "'>" + ary.row[i].tasks[j].title + "</a></span>";
			}
			else {
				var top_bottom = (ary.row[i].tasks[j].ddate.substring(14, 16) < 30) ? "top" : "bottom";

				$(top_bottom + i).innerHTML += "<span class='event2'><a href='desk.php?action=task#form-" + ary.row[i].tasks[j].id + "'>" + ary.row[i].tasks[j].title + "</a></span>";
			}
		}
		*/
	}
}
