// progressbar.js

var adesk_progress_bars = { };
var adesk_progress_timers = { };
var adesk_progress_ongoing = { };

function adesk_progressbar_register(divid, processid, initialValue, secondInterval, spawn, func) {
	var rel = $(divid);
	if ( !rel ) return;
	if ( !secondInterval || isNaN(secondInterval) ) {
		secondInterval = 0;
	}
	spawn = ( spawn ? 1 : 0 );
	// save this process to this div bar
	adesk_progressbar_init(divid, initialValue);
	adesk_progress_bars[divid] = processid;
	if ( secondInterval > 0 ) {
		if ( typeof adesk_progress_timers[processid] == 'undefined' ) {
			adesk_progress_timers[processid] = window.setInterval(
				function() {
					if ( typeof adesk_progress_ongoing[processid] != 'undefined' ) return;
					adesk_progress_ongoing[processid] = 1;
					// make an ajax call that should set all divs with new value for this process
					adesk_ajax_call_cb(
						apipath,
						"process!adesk_progressbar_update",
						function(xml) {
							var ary = adesk_dom_read_node(xml);
							if ( !ary.id /*|| !ary.percentage*/ ) {
								adesk_progressbar_unregister(divid);
								return;
							}
							if ( typeof adesk_progress_ongoing[processid] != 'undefined' ) {
								delete adesk_progress_ongoing[processid];
							}
							//if ( !ary.id ) return;
							//if ( !ary.percentage ) return;
							for ( var i in adesk_progress_bars ) {
								var rel = $(i);
								if ( !rel ) continue;
								if ( adesk_progress_bars[i] != ary.id ) continue;
								adesk_progressbar_set(i, ary.percentage);
								if ( ary.remaining == 0 ) {
									adesk_progressbar_unregister(i);
								}
							}
							if ( typeof func == 'function' ) {
								func(ary);
							}
						},
						processid,
						spawn
					);
				},
				secondInterval * 1000
			);
		}
	}
}

function adesk_progressbar_unregister(divid) {
	if ( typeof adesk_progress_bars[divid] == 'undefined' ) return;
	var pid = adesk_progress_bars[divid];
	delete adesk_progress_bars[divid];
	if ( typeof adesk_progress_timers[pid] == 'undefined' ) return;
	var found = false;
	for ( var i in adesk_progress_bars ) {
		if ( adesk_progress_bars[i] == pid ) {
			found = true;
			break;
		}
	}
	if ( !found ) {
		window.clearInterval(adesk_progress_timers[pid]);
		delete adesk_progress_timers[pid];
	}
}

function adesk_progressbar_init(divid, val) {
	if ( !val ) val = 0;
	var rel = $(divid);
	if ( !rel ) return;
	var value = ( Math.round(val * 100) / 100 ) + '%';
	adesk_dom_remove_children(rel);
	// add progress label
	rel.appendChild(
		Builder.node(
			'div',
			{ className: 'adesk_progress_label', title: value },
			[
				Builder._text(value)
			]
		)
	);
	// add progress bar
	rel.appendChild(
		Builder.node(
			'div',
			{ className: 'adesk_progress_bar', style: 'width: ' + value, title: value }
		)
	);
}

function adesk_progressbar_set(divid, val) {
	var rel = $(divid);
	if ( !rel ) {
		adesk_progressbar_unregister(divid);
		return;
	}
	if ( !val ) val = 0;
	var divs = rel.getElementsByTagName('div');
	if ( divs.length != 2 ) {
		adesk_progressbar_init(divid);
	}
	// set label
	var lbl = divs[0];
	lbl.title = ( Math.round(val * 100) / 100 ) + '%';
	lbl.innerHTML = ( Math.round(val * 100) / 100 ) + '%';
	// set bar
	var bar = divs[1];
	bar.title = ( Math.round(val * 100) / 100 ) + '%';
	bar.style.width = val + '%';
}
