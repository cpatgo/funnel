var ACTable = Class.create();

ACTable.prototype = {
initialize:
	function() {
		this.cols = [];
		this.selection = [];
	},

setcol:
	function(i, cb) {
		if ( !cb && typeof(i) == 'function' ) cb = i;
		if ( isNaN(parseInt(i)) ) i = this.cols.length;
		this.cols[i] = cb;
	},

addcol:
	function(cb) {
		this.cols.push(cb);
	},

	// Unset column index.  Use this function if there is a case that you would
	// want to omit a certain column from being displayed.

unsetcol:
	function(index) {
		if (typeof this.cols[index] != "undefined")
			this.cols.splice(index, 1);
	},

newrow:
	function(row) {
		var tds = [];
		var td  = null;
		var sub = null;

		for (var i = 0; i < this.cols.length; i++) {
			td = Builder.node("td");

			sub = this.cols[i](row, td);
			if ( typeof sub == "string" || typeof sub == "number" )
				sub = Builder._text(sub);

			td.appendChild(sub);
			tds.push(td);
		}

		return Builder.node("tr", { className: "adesk_table_row" }, tds);
	},

reuserow:
	function(row, tr) {
		var sub = null;
		var tds = tr.getElementsByTagName("td");
		for (var i = 0; i < this.cols.length; i++) {
			// Can't set any columns if they don't exist -- although maybe
			// in this case we should add them.
			if (i >= tds.length)
				break;

			adesk_dom_remove_children(tds[i]);

			sub = this.cols[i](row, tds[i]);
			if ( typeof sub == "string" || typeof sub == "number" )
				sub = Builder._text(sub);

			tds[i].appendChild(sub);
		}

		// Just to be safe, make sure the row is visible
		row.className = "adesk_table_row";
		return row;
	}
};
