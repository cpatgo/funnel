{jsvar var=$socialsharing_enabled name=socialsharing_enabled}
var strFacebook1 = '{"liked this campaign on Facebook on"|alang|js}';
var strTwitter1 = '{"shared this campaign on Twitter on"|alang|js}';
var strOn = '{"on"|alang|js}';

{literal}

var socialsharing_table = new ACTable();
var socialsharing_list_sort_discerned = false;
var report_campaign_list_socialsharing_sort = "01D";
var external_sources = [ "facebook", "twitter" ];

function socialsharing_totals() {
	adesk_ajax_call_cb("awebdeskapi.php?hash=" + report_campaign_list_hash, "socialsharing.socialsharing_select_totals", adesk_ajax_cb(socialsharing_totals_cb), report_campaign_id, $("messageid").value);
}

function socialsharing_totals_cb(ary) {

	$("socialsharing_twitter_total_t").innerHTML = ary.twitter_total;
	// add up all facebook cached shares (saved in #socialshare), and all external shares (shared outside of our UI)
	var facebook_total = ary.facebook_total + ary.total_socialshare_facebook_external;
	$("socialsharing_facebook_total_t").innerHTML = facebook_total;

	// obtain the total from the DOM - for the main "Social Sharing" tab along the top
	var main_tab_total = $("count_tab_socialsharing").innerHTML.substring(1, $("count_tab_socialsharing").innerHTML.length - 1);
	main_tab_total = parseInt(main_tab_total, 10);

	// we only refresh the main tab total count when the page reloads, NOT when switching tabs.
	// so in the case where a share is submitted, but the user does not refresh the campaign reports page, they may see a total less than
	// what is actually in the #socialshare table. In this case, update the DOM to use the correct total
	if ( main_tab_total < (ary.twitter_total + ary.facebook_total) ) {
		$("count_tab_socialsharing").innerHTML = "(" + (ary.twitter_total + ary.facebook_total) + ")";
	}

	if (ary.total_socialshare_facebook_external) {
		// populate the <table> row that says "# of people liked your campaign that are not subscribers"
		$("facebook_external_total").innerHTML = ary.total_socialshare_facebook_external;
		// only if it has 1 external share
		if (ary.total_socialshare_facebook_external == 1) {
			// adjust the language of the text if just 1 result is there
			$("facebook_external_total_people").hide();
			$("facebook_external_total_person").show();
			$("facebook_external_total_are").hide();
			$("facebook_external_total_is").show();
		}
	}
	// below are currently not displayed (more people can share than campaign total sent, so numbers could easily be over 100%)
	// they are still saved in the DOM, but have display=none
	$("socialsharing_facebook_total_p").innerHTML = sprintf("(%.2f%%)", ary.total_amt_sent > 0 ? 100 * ary.facebook_total / ary.total_amt_sent : 0);
	$("socialsharing_twitter_total_p").innerHTML = sprintf("(%.2f%%)", ary.total_amt_sent > 0 ? 100 * ary.twitter_total / ary.total_amt_sent : 0);

	// default source shown - this is paginator
	socialsharing_toggle('all');
}

function socialsharing_toggle(source) {
	paginators[9].paginate(report_campaign_list_offset, source);
	var facebook_external_total = parseInt( $("facebook_external_total").innerHTML, 10 );
	if (source != "all") {
		if (source == "facebook") {
			// only show the row that has "# people that are not subscribers shared this campaign..." if greater than 0
			if (facebook_external_total) $("socialsharing_table_facebook_external").show();
		}
		else {
			$("socialsharing_table_facebook_external").hide();
		}
	}
	else {
		// only show the row that has "# people that are not subscribers shared this campaign..." if greater than 0
		if (facebook_external_total) $("socialsharing_table_facebook_external").show();
	}
}

function socialsharing_source_process_cb(xml) {
	var ary = adesk_dom_read_node(xml);
	adesk_ui_api_callback();

	// the totals along the top - "Facebook Likes" and "Twitter Mentions"
	$("socialsharing_" + ary.source + "_total_t").innerHTML = ary.total;
}

socialsharing_table.setcol(0, function(row, td) {
	td.align = 'center';
	if (row.source == "twitter") {
		var imgsrc = typeof row.data[0] == 'undefined' ? 'images/gravatar_default.png' : row.data[0].image;
		var image = Builder.node(
			"img",
			{
				src: imgsrc,
				height: "40",
				width: "40",
				style: "border:2px solid #e6ebf1;"
			}
		);
		return image;
	}
	else {
		// <img src="http://www.gravatar.com/avatar/{$subscriber.md5email}?d={$subscriber.default_gravatar}&s=200" width="200" style="border:3px solid #EDECE7;">
		var image = Builder.node(
			"img",
			{
				src: "http://www.gravatar.com/avatar/" + row.subscriber[0].md5email + "?d=" + encodeURIComponent(adesk_js_site.p_link + "/manage/images/gravatar_default.png") + "&s=40",
				height: "40",
				width: "40",
				style: "border:2px solid #e6ebf1;"
			}
		);
		return image;
	}
});

socialsharing_table.setcol(1, function(row, td) {
	// if there's data, we know we obtained this data from the API
	if (row.source == "twitter") {
		var author = Builder.node(
			"a",
			{ href: row.data[0].author[0].uri, target: "_blank", style: "color: #999" },
			row.data[0].author[0].name
		);
		var author_div = Builder.node("div", {style:"font-size:10px;"}, author);
		var title_pieces = row.data[0].title.split(" ");
		for (var i = 0; i < title_pieces.length; i++) {
			var piece = title_pieces[i];
			if ( adesk_str_is_url(piece) ) {
				title_pieces[i] = adesk_str_replace(piece, "<a href=\"" + piece + "\" target=\"_blank\">" + piece + "</a>", piece);
			}
		}
		var title_display = title_pieces.join(" ");
		var content_span = Builder.node("span", { style:"font-size:12px;", id: "content_" + row.id });
		content_span.innerHTML = title_display;
		content_span.appendChild(author_div);
		return content_span;
	}
	else {
		// otherwise show a canned message (this is usually for shares that we cache locally - as soon as they click, before redirecting to the share site)
		if (row.subscriberid) {
			// if a subscriber shared this -- only way this can happen is if share icon/link was clicked from within the message source (where we have subscriber info)
			if (row.source == "twitter") {
				return row.subscriber[0].name + " " + strTwitter1 + " " + sql2date(row.cdate).format(datetimeformat);
			}
			else {
				// facebook
				var author = Builder.node(
					"a",
					{ href: "desk.php?action=subscriber_view&id=" + row.subscriberid + '#log-03D-0-0', style: "color: #999" },
					row.subscriber[0].name + " (" + row.subscriber[0].email + ")"
				);
				var author_div = Builder.node("div", {style:"font-size:10px;"}, author);
				var content_span = Builder.node("span", {style:"font-size:12px;"}, [ row.data, author_div ]);
				return content_span;
			}
		}
		else {
			// NO subscriber data - this means the share icon/link was clicked from *outside* of the message source, where we DO NOT have subscriber info.
			// no subscriber ID, and no data here

		}
	}
});

socialsharing_table.setcol(2, function(row, td) {
	if (row.data && row.source == "twitter") {
		var published = Builder.node( "a", { target:"_blank", href: row.data[0].link }, sql2date(row.cdate).format(datetimeformat) );
	}
	else {
		var published = sql2date(row.cdate).format(datetimeformat);
	}
	return published;
});

function socialsharing_tabelize(rows, offset) {
	if (report_campaign_list_filter != "0") {
		// if there is a filter ID (assuming that is coming from search), hide the table row that shows external facebook shares
		$("socialsharing_table_facebook_external").hide();
	}
	if (rows.length < 1) {
		// We may have some trs left if we just deleted the last row.
		adesk_dom_remove_children($("socialsharing_table"));

		$("socialsharing_noresults").className = "adesk_block";

		// get the total number of facebook external shares from the DOM (already written when calling socialsharing_select_totals)
		var facebook_external_total = parseInt( $("facebook_external_total").innerHTML, 10 );
		if (facebook_external_total) {
			// hide the "Nothing found" text
			// problem is this happens on the Twitter paginator too - so if there truly IS no results for Twitter, it WON'T show "No results"
			$("socialsharing_noresults").className = "adesk_hidden";
		}

		$("socialsharing_loadingBar").className = "adesk_hidden";
		adesk_ui_api_callback();
		//socialsharing_totals();
		return;
	}
	$("socialsharing_noresults").className = "adesk_hidden";
	window.t_rows = rows;
	adesk_paginator_tabelize(socialsharing_table, "socialsharing_table", rows, offset);
	$("socialsharing_loadingBar").className = "adesk_hidden";

	//socialsharing_totals();
}

// This function should only be run through a paginator (e.g., paginators[n].paginate(offset))
function socialsharing_paginate(offset, source) {
	if (typeof(source) == "undefined") source = "all";
	if (!adesk_loader_visible() && !adesk_result_visible() && !adesk_error_visible())
		adesk_ui_api_call(jsLoading);

	if (report_campaign_list_filter > 0)
		$("socialsharing_clear").style.display = "inline";
	else
		$("socialsharing_clear").style.display = "none";

	report_campaign_list_offset = parseInt(offset, 10);

	adesk_ui_anchor_set(report_campaign_list_anchor());
	$("socialsharing_loadingBar").className = "adesk_block";
	adesk_ajax_call_cb(this.ajaxURL, this.ajaxAction, paginateCB, this.id, report_campaign_list_socialsharing_sort, report_campaign_list_offset, this.limit, report_campaign_list_filter, report_campaign_id, source);

	$("socialsharing").className = "adesk_block";
}

function socialsharing_list_search() {
	var post = adesk_form_post($("socialsharing"));
	report_campaign_list_filter = post.listid;
	list_filters_update(0, post.listid, false);
	adesk_ajax_post_cb("awebdeskapi.php", "socialsharing.socialsharing_filter_post", socialsharing_list_search_cb, post);
}

function socialsharing_list_search_cb(xml) {
	var ary = adesk_dom_read_node(xml);

	report_campaign_list_filter = ary.filterid;
	adesk_ui_anchor_set(report_campaign_list_anchor());
}

function socialsharing_list_chsort(newSortId) {
	var oldSortId = ( report_campaign_list_sort.match(/D$/) ? report_campaign_list_sort.substr(0, 2) : report_campaign_list_sort );
	var oldSortObj = $('socialsharing_list_sorter' + oldSortId);
	var sortObj = $('socialsharing_list_sorter' + newSortId);
	// if sort column didn't change (only direction [asc|desc] did)
	if ( oldSortId == newSortId ) {
		// switching asc/desc
		if ( report_campaign_list_sort.match(/D$/) ) {
			// was DESC
			newSortId = report_campaign_list_sort.substr(0, 2);
			sortObj.className = 'adesk_sort_asc';
		} else {
			// was ASC
			newSortId = report_campaign_list_sort + 'D';
			sortObj.className = 'adesk_sort_desc';
		}
	} else {
		// remove old report_campaign_list_sort
		if ( oldSortObj ) oldSortObj.className = 'adesk_sort_other';
		// set sort field
		sortObj.className = 'adesk_sort_asc';
	}
	report_campaign_list_sort = newSortId;
	report_campaign_list_socialsharing_sort = newSortId;
	adesk_ui_api_call(jsSorting);
	adesk_ui_anchor_set(report_campaign_list_anchor());
	return false;
}

function socialsharing_list_discern_sortclass() {
	if (socialsharing_list_sort_discerned)
		return;

	var elems = $("list_head").getElementsByTagName("a");

	for (var i = 0; i < elems.length; i++) {
		var str = sprintf("socialsharing_list_sorter%s", report_campaign_list_sort.substring(0, 2));

		if (elems[i].id == str) {
			if (report_campaign_list_sort.match(/D$/))
				elems[i].className = "adesk_sort_desc";
			else
				elems[i].className = "adesk_sort_asc";
		} else {
			elems[i].className = "adesk_sort_other";
		}
	}

	socialsharing_list_sort_discerned = true;
}

function socialsharing_list_clear() {
	report_campaign_list_sort = "01";
	report_campaign_list_offset = "0";
	report_campaign_list_filter = "0";
	$("socialsharing_search").value = "";
	adesk_ui_anchor_set(report_campaign_list_anchor());
}

{/literal}
