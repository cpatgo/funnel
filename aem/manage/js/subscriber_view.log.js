{literal}
var log_table = new ACTable();
log_table.dontreuse = true;

log_table.setcol(0, function(row, td) {
	td.vAlign = 'top';
	return Builder._text(row.listname);
});

log_table.setcol(1, function(row, td) {
	td.vAlign = 'top';
	// building log comment text
	var logcomment = Builder.node(
		'div',
		{ style: 'margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #ccc;' }
	);
	logcomment.innerHTML = nl2br(row.comment);
	// building info table
	var table = Builder.node('table', { cellspacing: 0, cellpadding: 0, border: 0, width: '100%' } );
	// reads
	if ( row.reads.length && row.reads.length > 0 ) {
		table.appendChild( // header
			Builder.node(
				'tr',
				{ className: 'adesk_table_rowheader' },
				[
					Builder.node('th', [ Builder._text(strMessageReadsTitle) ]),
					Builder.node('th', [ Builder._text(strMessageReadsTimes) ]),
				]
			)
		);
		for ( var i = 0; i < row.reads.length; i++ ) {
			table.appendChild( // header
				Builder.node(
					'tr',
					{ className: 'adesk_table_row' },
					[
						Builder.node('td', [ Builder._text(sql2date(row.reads[i].tstamp).format(datetimeformat)) ]),
						Builder.node('td', [ Builder._text(row.reads[i].times) ]),
					]
				)
			);
		}
	}
	// clicks
	if ( row.links.length && row.links.length > 0 ) {
		table.appendChild( // header
			Builder.node(
				'tr',
				{ className: 'adesk_table_rowheader' },
				[
					Builder.node('th', [ Builder._text(strMessageClicksTitle) ]),
					Builder.node('th', [ Builder._text(strMessageClicksTimes) ]),
				]
			)
		);
		for ( var i = 0; i < row.links.length; i++ ) {
			if ( row.links[i].name != '' ) {
				var link = Builder.node(
					'span',
					{
						style: "cursor: pointer;",
						onmouseover: "adesk_tooltip_show('" + row.links[i].link + "', 200);",
						onmouseout: "adesk_tooltip_hide();"
					},
					[
						Builder._text(row.links[i].name)
					]
				);
			} else {
				var link = Builder._text(row.links[i].link);
			}
			table.appendChild( // header
				Builder.node(
					'tr',
					{ className: 'adesk_table_row' },
					[
						Builder.node('td', [ link ]),
						Builder.node('td', [ Builder._text(row.links[i].times) ]),
					]
				)
			);
		}
	}
	// forwards
	if ( row.links.length && row.links.length > 0 ) {
		table.appendChild( // header
			Builder.node(
				'tr',
				{ className: 'adesk_table_rowheader' },
				[
					Builder.node('th', [ Builder._text(strMessageForwardsTo) ]),
					Builder.node('th', [ Builder._text(strMessageForwardsDate) ]),
				]
			)
		);
		for ( var i = 0; i < row.forwards.length; i++ ) {
			if ( row.forwards[i].brief_message != '' ) {
				var link = Builder.node(
					'span',
					{
						style: "cursor: pointer;",
						onmouseover: "adesk_tooltip_show('" + adesk_b64_encode(row.forwards[i].brief_message) + "', 200, '', true);",
						onmouseout: "adesk_tooltip_hide();"
					},
					[
						Builder._text(row.forwards[i].email_to)
					]
				);
			} else {
				var link = Builder._text(row.forwards[i].email_to);
			}
			table.appendChild( // header
				Builder.node(
					'tr',
					{ className: 'adesk_table_row' },
					[
						Builder.node('td', [ link ]),
						Builder.node('td', [ Builder._text(row.forwards[i].tstamp) ]),
					]
				)
			);
		}
	}
	// building link to message
	var viewfull = Builder.node(
		'div',
		[
			Builder.node(
				'a',
				{
					href: "desk.php?action=report_campaign&id=" + row.id + '&s=' + subscriber_view_hash
				},
				[
					Builder._text(strMailingFullStats)
				]
			)
		]
	);
	// building info box
	var info = Builder.node('div', { className: 'adesk_inrow_info', id: 'log' + row.id + 'info', style: 'display: none;' }, [ logcomment, table, viewfull ]);
	// building link to open info box
	var txt = Builder.node(
		'a',
		{
			href: "#",
			onclick: "adesk_dom_toggle_display('log" + row.id + "info', 'block');return false;"//,
			//onmouseover: "adesk_tooltip_show('From: \"" + row.fromname + "\" <" + row.fromemail + ">', 200);",
			//onmouseout: "adesk_tooltip_hide();"
		},
		[
			Builder._text(row.campaignname)
		]
	);
	// render link and box
	return Builder.node('div', [ txt, info ]);
});
/*
log_table.setcol(2, function(row, td) {
	td.vAlign = 'top';
	return Builder._text( row.successful ? jsYes : jsNo );
});
*/
log_table.setcol(2, function(row, td) {
	td.vAlign = 'top';
	return Builder._text(sql2date(row.tstamp).format(datetimeformat));
});



function subscriber_view_log_load(offset) {
	//$("log").className = "adesk_block";
	subscriber_view_discern_sortclass();
	paginators[3].paginate(offset);
}
{/literal}
