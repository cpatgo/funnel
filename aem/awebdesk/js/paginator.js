// paginator.js
if ( paginators === undefined || paginators === null ) {
	var paginators      = new Array();
	var paginator_b64   = true;
}


/* PAGINATOR OBJECT METHODS */
function ACPaginator_init() {
	/*
		provide links to other results
	*/

	if (this.offset >= this.total && this.total > 0)
		this.paginate(this.offset - this.limit);

	// previous page link
	this.hasPrevious = ( this.offset > 0 );
	if ( this.hasPrevious ) {
		var prevOffset = this.offset - this.limit;
		if ( prevOffset <= 0 ) prevOffset = 0;
		this.previousOffset = prevOffset;
	}
	// next page link
	this.hasNext = ( this.total > this.offset + this.fetched );
	if ( this.hasNext ) {
		nextOffset = this.offset + this.fetched;
		this.nextOffset = nextOffset;
	}
	/*
		links to all other pages
	*/
	// here we will hold all pages
	this.links = new Array();
	// how many pages are there
	this.linksCnt = ( this.total == 0 ? 1 : Math.ceil(this.total / this.limit) );
	// where are we now?
	this.thisPage = 1;
	// loop through all
	for ( var i = 1; i <= this.linksCnt; i++ ) {
		this.links[i] = new Array();
		this.lastOffset = ( i - 1 ) * this.limit;
//alert('lastOffset: ' + this.lastOffset + '; currentOffset: ' + this.offset);
		if ( this.offset == this.lastOffset ) this.thisPage = i;
		this.links[i]['thisone'] = ( this.offset == this.lastOffset );
		this.links[i]['offset'] = this.lastOffset;
	}
	// loop through all, here define what to show
	for ( var i = 1; i <= this.linksCnt; i++ ) {
		this.links[i]['showit'] = ( this.showSpan == 0 || ( i > this.thisPage - this.showSpan && i < this.thisPage + this.showSpan ) );
	}
	// fill all HTML elements
	this.populate();
//bible(this);
}

function ACPaginator_rebuild(offset) {
	// passing in new offset
	this.offset = offset;
	this.init();
}

// passing in the DOM object for paginator we will fill
function ACPaginator_populate() {
	if ( !this.box ) return;
	// now fetch all objects we need to fill
	var thisSpan = document.getElementById('paginatorThisPage' + this.id);
	var prevSpan = document.getElementById('paginatorPrevious' + this.id);
	var nextSpan = document.getElementById('paginatorNext' + this.id);
	var firstSpan = document.getElementById('paginatorFirst' + this.id);
	var lastSpan = document.getElementById('paginatorLast' + this.id);
	var pageSpan = document.getElementById('paginatorPages' + this.id);
	//var limitSpan = document.getElementById('paginatorLimitBox' + this.id);
	var limitSelect = document.getElementById('paginatorLimit' + this.id);
	if ( !( prevSpan && nextSpan && firstSpan && lastSpan && pageSpan ) ) return;
	// fill them one by one
	// this page button
	var data = '';
	data = sprintf(jsPaginatorThis, this.thisPage, this.linksCnt);
	this.pushData(thisSpan, data);
	// previous button
	var data = '';
	if ( this.hasPrevious ) {
		data = '<a href="javascript:paginators[' + this.id + '].paginate(' + this.previousOffset + ');">' + jsPaginatorPrevious + '</a>';
	}
	this.pushData(prevSpan, data);
	// next button
	data = '';
	if ( this.hasNext ) {
		data = '<a href="javascript:paginators[' + this.id + '].paginate(' + this.nextOffset + ');">' + jsPaginatorNext + '</a>';
	}
	this.pushData(nextSpan, data);
	// first button
	data = '';
	if ( this.showSpan > 0 && this.thisPage > this.showSpan + 1 ) {
		data = '<a href="javascript:paginators[' + this.id + '].paginate(' + this.links[1].offset + ');">&laquo;</a> ...';
	}
	this.pushData(firstSpan, data);
	// last button
	data = '';
	if ( this.showSpan > 0 && this.thisPage <= this.linksCnt - this.showSpan ) {
		data = '... <a href="javascript:paginators[' + this.id + '].paginate(' + this.lastOffset + ');">&raquo;</a>';
	}
	this.pushData(lastSpan, data);
	// all pages
	data = '';
	for ( var i = 1; i <= this.linksCnt; i++ ) {
		if ( this.links[i]['showit'] ) {
			if ( !this.links[i]['thisone'] ) {
				data = data + '<a class="paginatorPageLink" href="javascript:paginators[' + this.id + '].paginate(' + this.links[i]['offset'] + ');">' + i + '</a>';
			} else if ( this.linksCnt > 1 ) {
				data = data + '<strong>' + i + '</strong>';
			}
		}
	}
	this.pushData(pageSpan, data);
	// limit select
	if ( limitSelect ) limitSelect.value = this.limit;
}

function ACPaginator_pushData(element, data) {
	element.innerHTML = data;
	element.className = ( data != '' ? '' : 'adesk_hidden' );
}

function ACPaginator_tabelize(rows, offset) {
	alert('Returned ' + rows.length + ' rows starting from offset ' + offset);
}

function ACPaginator_paginate(offset) {
	// fetch new list
	adesk_ajax_call_cb(this.ajaxURL, this.ajaxAction, paginateCB, this.id, offset, limit);
}

function ACPaginator_limitize(limit) {
	// set new limit
	this.limit = limit;
	this.offset = 0;
	// fetch new list
	this.paginate(this.offset);
}



/* PAGINATOR OBJECT */
function ACPaginator(id, total, fetched, limit, offset) {
	// input properties
	this.id = id;
	this.box = document.getElementById('paginatorBox' + id);
	this.total = total;
	this.fetched = fetched;
	this.limit = limit;
	this.offset = offset;

	// internal properties
	this.tabelized = false;
	this.hasPrevious = false;
	this.hasNext = false;
	this.previousOffset = 0;
	this.lastOffset = 0;
	this.nextOffset = limit; // first offset is limit
	this.links = new Array();
	this.linksCnt = 1;
	this.thisPage = 1;
	this.showSpan = 3;
	this.ajaxURL = 'awebdeskapi.php';
	this.ajaxAction = 'paginate';
//	this.baseURL = '';
//	this.offsetName = 'offset';

	this.boxes = [];

	// methods
	this.init = ACPaginator_init;
	this.rebuild = ACPaginator_rebuild;
	this.populate = ACPaginator_populate;
	this.pushData = ACPaginator_pushData;

	this.tabelize = ACPaginator_tabelize;
	this.paginate = ACPaginator_paginate;

	this.limitize = ACPaginator_limitize;

	// init (constructor)
	//this.init();
}






/* PAGINATOR OBJECT HANDLER */
function paginate(paginator, offset) {
	// fetch new list
	paginator.paginate(offset);
}

function paginateCB(xml, text) {
	var ary = adesk_dom_read_node(xml, paginator_b64 ? adesk_b64_decode : null);
	if ( isNaN(parseInt(ary.paginator, 10)) ) return;
	var id = ary.paginator;
	if (paginators[id].offset != ary.offset)
		paginators[id].boxes = [];
	// refill paginator
	paginators[id].offset = ( isNaN(parseInt(ary.offset, 10)) ? 0 : parseInt(ary.offset, 10) );
	//paginators[id].limit = ( isNaN(parseInt(ary.limit, 10)) ? 0 : parseInt(ary.limit, 10) );
	paginators[id].total = ( isNaN(parseInt(ary.total, 10)) ? 0 : parseInt(ary.total, 10) );
	paginators[id].fetched = ( isNaN(parseInt(ary.cnt, 10)) ? 0 : parseInt(ary.cnt, 10) ); /*ary.rows.length*/
	// rebuild paginator
	paginators[id].tabelize(ary.rows, paginators[id].offset, ary);
	paginators[id].tabelized = true;
	// rebuild paginator
	paginators[id].rebuild(( isNaN(parseInt(ary.offset)) ? 0 : parseInt(ary.offset) ));
}

function adesk_paginator_tabelize(table, tbodyid, rows, offset, trfunc) {
	adesk_ui_api_callback();
	if ( typeof(table.dontreuse) != 'undefined' ) {
		// support for "dontreuse" switch
		adesk_dom_remove_children($(tbodyid));
	}

	var trs = $(tbodyid).getElementsByTagName("tr");
	var i;

	table.selection = adesk_form_check_selection_get($(tbodyid), "multi[]");

	for (i = 0; i < rows.length; i++) {
		if (i >= trs.length || typeof(table.dontreuse) != 'undefined' )
			var newRow = $(tbodyid).appendChild(table.newrow(rows[i]));
		else
			var newRow = table.reuserow(rows[i], trs[i]);

		if ( typeof trfunc == 'function' ) {
			trfunc(rows[i], newRow);
		}
	}

	if ( typeof(table.dontreuse) == 'undefined' ) {
		// Unfortunately, we HAVE to use getElements to remove the children.  It seems
		// to be a reference thing.
		while ( $(tbodyid).getElementsByTagName('tr').length > rows.length ) {
			$(tbodyid).removeChild ($(tbodyid).getElementsByTagName('tr')[ rows.length ]);
		}
	}
}
