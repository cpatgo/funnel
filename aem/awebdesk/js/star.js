function adesk_star_clear(starObjId) {
	var elems = $(starObjId).getElementsByTagName("a");

	for (var i = 0; i < elems.length; i++)
		elems[i].className = "adesk_star_none";

	return elems;
}

function adesk_star_hover(starObjId, limit) {
	var elems = adesk_star_clear(starObjId);

	for (var i = 0; i < elems.length; i++) {
		elems[i].className = "adesk_star_hover";

		if ((i+1) >= limit)
			break;
	}
}

function adesk_star_render(starObjId) {
	var rating = $(starObjId + "_rating").innerHTML;
	var elems = adesk_star_clear(starObjId);
	var cr = rating;		// Ratings counter
	var cls = "";

	for (var i = 0; i < elems.length; i++) {

		cls = "adesk_star_none";
		if (cr >= 1.0)
			cls = "adesk_star_full";
		else if (cr >= 0.5)
			cls = "adesk_star_half";

		elems[i].className = cls;
		cr -= 1.0;
	}
}

function adesk_star_callback(xml) {
	var ary = adesk_dom_read_node(xml, null);

	adesk_star_set(ary.prefix, 0, ary.rating);
}

function adesk_star_set(starObjId, relid, val) {
	var rateid = starObjId + "_rating";

	if ($(rateid) !== null) {
		$(rateid).innerHTML = val;
		adesk_star_render(starObjId);
	}
}

function adesk_star_get(starObjId) {
	var rateid = starObjId + "_rating";

	if ($(rateid) !== null) {
		return $(rateid).innerHTML;
	}
	return 0;
}

function adesk_stars(rating) {
	var count = 5;
	var links = "";
	var cr = parseFloat(rating);
	var ci = 0;
	var cls;

	while (count--) {
		ci++;

		cls = "adesk_star_none";
		if (cr >= 1.0)
			cls = "adesk_star_full";
		else if (cr >= 0.5)
			cls = "adesk_star_half";

		links += sprintf("<a class=\"%s\" href=\"javascript:void(0)\" style=\"cursor:default\">", cls);
		links += sprintf("<img style=\"padding: 0px\" border=\"0\" align=\"absmiddle\" src=\"%s/media/adesk_star_clear.gif\" />", acgpath);
		links += "</a>";

		cr -= 1.0;
	}

	return "<span>" + links + "</span>";
}

function adesk_star_disable(starObjId) {
	var rel = $(starObjId);
	if ( !rel ) return;
	var val = $(starObjId + '_rating').innerHTML;
	var stars = adesk_stars(val);
	rel.innerHTML = stars;
}
