// custom_fields.js

var field_dom_id = 0;
function adesk_custom_fields_title(field, showhidden) {
	if (typeof showhidden == "undefined")
		showhidden = false;

    if (field.type != "6" || showhidden)
        return field.title;
}

function adesk_custom_fields_bubble(node, field) {
	if ( !field.bubble_content ) return node;
	if ( field.bubble_content == '' ) return node;
	return Builder.node(
		"span",
		[
			node,
			Builder.node("div", { id: 'field' + field.id + 'bubble', className: 'adesk_help', style: 'display: none;' }, [ Builder._text(field.bubble_content) ])
		]
	);
}

function adesk_custom_fields_cons(field, showhidden) {
	if (typeof showhidden == "undefined")
		showhidden = false;

    var f_name = "field[" + field.id + "," + field.dataid + "]";
    var f_type = parseInt(field.type, 10);
   	var props = {};
    if ( field.bubble_content && field.bubble_content != '' ) {
		props.onmouseover = "adesk_dom_toggle_display('field" + field.id + "bubble', 'block');";
		props.onmouseout  = "adesk_dom_toggle_display('field" + field.id + "bubble', 'block');";
    }
    switch (f_type) {
        case 1:     // Text field
            if (field.val === "")
                field.val = field.onfocus;
            // properties
            props.type = "text";
            props.name = f_name;
            props.value = field.val;
            
			props.onKeyUp = "if (typeof custom_field_text_onkeyup == 'function' && window.event && window.event.keyCode) custom_field_text_onkeyup(window.event.keyCode)";
            return adesk_custom_fields_bubble(Builder.node("input", props), field);

        case 2:     // Text box
            var f_cols;
            var f_rows;
            if (field.onfocus !== '') {
                var dim = field.onfocus.split("||");
                f_cols = dim[0];
                f_rows = dim[1];
            } else {
                f_cols = 30;
                f_rows = 5;
            }
            if (field.val === '')
                field.val = field.expl;
            // properties
            props.rows = f_rows;
            props.cols = f_cols;
            props.name = f_name;
            return adesk_custom_fields_bubble(Builder.node("textarea", props, [ Builder._text(field.val) ]), field);

        case 3:     // Checkbox
            if (field.val === '')
                field.val = field.onfocus;
            // properties
            props.type = "checkbox";
            props.name = f_name;
            props.value = "checked";
            if (field.val == "checked")
                props.checked = "true";
            return Builder.node(
            	"span",
            	[
                    Builder.node("input", { type: "hidden", name: f_name, value: "unchecked" }),
                    adesk_custom_fields_bubble(Builder.node("input", props), field)
                ]
            );

        case 4:     // Radio button(s)
            if (field.val === '')
                field.val = field.onfocus;
            var f_ary    = new Array();
            f_ary.push(Builder.node("input", { type: "hidden", name: f_name, value: "unchecked" }));

            field.expl = field.expl.replace(/\r?\n/g, "||");
            var list = field.expl.split("||");

			// This isn't a valid field; skip it.
			if ((list.length % 2) == 1)
				return Builder._text(" ");

            for (var i = 0; i < list.length; i += 2) {
				props = {};
                // properties
                props.type = "radio";
                props.name = f_name;
                props.value = list[i+1];
                if (field.val == list[i+1])
                    props.checked = "true";
                f_ary.push(Builder.node("input", props));
                f_ary.push(Builder._text(list[i+0]));
            }

            return Builder.node("div", f_ary);

        case 5:     // Dropdown
            if (field.val === '')
                field.val = field.onfocus;

            var f_ary = new Array();
            field.expl = field.expl.replace(/\r?\n/g, "||");
            var list = field.expl.split("||");

			// This isn't a valid field; skip it.
			if ((list.length % 2) == 1)
				return Builder._text(" ");

            var found = false;
            for (var i = 0; i < list.length; i += 2) {
                var f_opt = { value: list[i+1] };

                if (field.val == list[i+1]) {
                    f_opt.selected = "true";
                    found = true;
                }

                f_ary.push(Builder.node("option", f_opt, [ Builder._text(list[i+0]) ]));
            }

            // properties
            props.name = f_name;
            props.size = 1;
            var elem = Builder.node("select", props, f_ary);
            if ( found ) {
            	elem.value = field.val;
            }
            return adesk_custom_fields_bubble(elem, field);

        case 6:     // Hidden field
            if (field.val === '')
                field.val = field.onfocus;

			if (showhidden)
				return Builder.node("input", { type: "text", name: f_name, value: field.val });
			else
				return Builder.node("input", { type: "hidden", name: f_name, value: field.val });

        case 7:     // List box (select with multiple)
			var div    = Builder.node("div");
			var input  = Builder.node("input", { type: "hidden", name: f_name, value: "~|" });
            field.expl = field.expl.replace(/\r?\n/g, "||");
            var list   = field.expl.split("||");
			var f_ary  = new Array();

			// This isn't a valid field; skip it.
			if ((list.length % 2) == 1)
				return Builder._text(" ");

            for (var i = 0; i < list.length; i += 2) {
                var f_opt = { value: list[i+1] };

                f_ary.push(Builder.node("option", f_opt, [ Builder._text(list[i+0]) ]));
            }

			var select = Builder.node("select", { name: f_name, multiple: true }, f_ary);
			div.appendChild(input);
			div.appendChild(adesk_custom_fields_bubble(select, field));
			select.value = field.val;
			return div;
        case 8:     // Checkbox group
			var input  = Builder.node("input", { type: "hidden", name: f_name + "[]", value: "~|" });
			field.expl = field.expl.replace(/\r?\n/g, "||");
            var list   = field.expl.toString().split("||");
			var f_ary  = new Array();
			var values = field.val.toString().split("||");

			f_ary.push(input);

			// This isn't a valid field; skip it.
			if ((list.length % 2) == 1)
				return Builder._text(" ");

            for (var i = 0; i < list.length; i += 2) {
                var f_opt = { type: "checkbox", name: f_name + "[]", value: list[i+1] };

                if (values.indexOf(list[i+1]) > -1) {
                    f_opt.checked = "true";
                    found = true;
                }

				f_ary.push(adesk_custom_fields_bubble(Builder.node("label", { className: "cFieldCheckboxGroup" }, [
					Builder.node("input", f_opt),
					Builder._text(list[i])
				]), field));
				f_ary.push(Builder.node("br"));
            }
			return Builder.node("div", f_ary);

        case 9:     // Date field
            field_dom_id++;
            if (field.val === "")
                field.val = field.onfocus;
            // properties
            props.id = 'datecfield' + field_dom_id;
            props.type = "text";
            props.name = f_name;
            props.value = field.val;
            if ( field.val == 'now' ) {
				var dteNow = new Date();
				sMonth = dteNow.getMonth() + 1;
				sDay = dteNow.getDate();
				sYear = dteNow.getFullYear();
				sHours = dteNow.getHours();
				sActDate = sYear + "-" + ( sMonth < 10 ? '0' : '') + sMonth + "-" + ( sDay < 10 ? '0' : '') + sDay;
            	props.value = sActDate;
            }
            var nodes = [
            	Builder.node("input", props),
            	Builder._text(" "),
            	Builder.node(
            		"a",
            		{ href: '#', onclick: 'return false;', id: 'datecbutton' + field_dom_id},
            		[ Builder.node('img', { src: acgpath + '/media/calendar.png', border: 0 }) ]
            	)
            ];
            window.setTimeout(
	            function() {
					if ($('datecfield' + field_dom_id)) {
						Calendar.setup({
							inputField: 'datecfield' + field_dom_id,
							ifFormat: '%Y-%m-%d',
							button: 'datecbutton' + field_dom_id,
							showsTime: false,
							timeFormat: '24'
						});
					}
	            },
	            1000
            );
            return adesk_custom_fields_bubble(Builder.node("span", nodes), field);

        default:
            break;
    }

    return Builder._text("Sorry!  Unknown field");
}




var ACCustomFields = null;
var ACCustomFieldsObj = null;
var ACCustomFieldsResult = {};


/* CUSTOM FIELDS OBJECT */
if (typeof Class != "undefined") {
	ACCustomFields = Class.create();
	ACCustomFields.prototype = {
		// Make this true if you want hidden fields to be displayed (as text fields).
		showhidden: false,

		initialize:
			function(props) {
				if ( !props ) props = { };
				// if checkboxes are used, it will preserve the selection in this array
				this.selection = [];
				// this array holds the current relations list (RELIDs)
				this.rels = ( !props.rels ? [] : props.rels );
				// this array holds all handlers for ajax response
				// index is updating object id, and value is the type of list we'll build there
				// options for type are:
				// display (shows fields),
				// list (gives a list of fields with checkboxes),
				// pers (builds a personalization dropdown)
				this.handlers = {};
				// sourceType is determining what is holding the RELIDs.
				// can be SELECT or CHECKBOX
				// default: SELECT
				this.sourceType = ( !props.sourceType ? 'SELECT' : props.sourceType );
				// which SELECT object is holding the list of RELIDs
				// which DIV object is holding the list of RELID checkboxes
				this.sourceId = ( !props.sourceId ? 'parentsList' : props.sourceId );
				// what is the name of CHECKBOXES that hold RELIDs
				this.sourceName = ( !props.sourceName ? 'p[]' : props.sourceName );
				// which API function to call
				this.api = ( !props.api ? 'list.list_field_update' : props.api );
				// which index in API response holds fields array
				this.responseIndex = ( !props.responseIndex ? 'row' : props.responseIndex );
				// any additional handlers (for some other data)?
				this.additionalHandler = ( !props.additionalHandler ? null : props.additionalHandler );
				// if global custom fields should be fetched or not
				this.includeGlobals = ( !props.includeGlobals ? 0 : props.includeGlobals );
				// if some custom param should be sent
				this.apiParam = ( !props.apiParam? '' : props.apiParam );
			},

		addHandler:
			function(targetId, type) {
				this.handlers[targetId] = type;
			},

		addCustomHandler:
			function(targetId, func, responseIndex) {
				this.handlers[targetId] = { func: func, responseIndex: responseIndex};
			},

		removeHandler:
			function(targetId) {
				if (typeof this.handlers[targetId] != "undefined")
					delete this.handlers[targetId];
			},

		fetch:
			function(id) {
				// fetch relation ids
				if ( this.sourceType == 'SELECT' ) {
					if ($(this.sourceId))
						this.rels = adesk_form_select_extract($(this.sourceId));
					else
						this.rels = adesk_dom_boxchoice(this.sourceId);
				} else if ( this.sourceType == 'CHECKBOX' ) {
					if ($(this.sourceId))
						this.rels = adesk_form_check_selection_get($(this.sourceId), this.sourceName);
					else
						this.rels = adesk_dom_boxchoice(this.sourceId);
				} else if ( this.sourceType != 'STATIC' ) {
					this.rels = [];
				}
				ACCustomFieldsObj = this;
				adesk_ui_api_call(jsLoading);
				adesk_ajax_call_cb('awebdeskapi.php', this.api, this.handle, id, this.rels.join('-'), this.includeGlobals, this.apiParam);
				somethingChanged = true;
			},

		handle:
			function(xml) {
				// need to use ACCustomFieldsObj instead of this ( a copy used for callback )
				var ary = adesk_dom_read_node(xml);
				adesk_ui_api_callback();
				ACCustomFieldsResult = ary[ACCustomFieldsObj.responseIndex];
				for ( var i in ACCustomFieldsObj.handlers ) {
					var type = ACCustomFieldsObj.handlers[i];
					if ( typeof type != 'function' ) {
						var targetObj = $(i);
						if ( !targetObj ) targetObj = i;
						if ( typeof type.func == 'function' ) {
							if ( !type.responseIndex ) type.responseIndex = ACCustomFieldsObj.responseIndex;
							if ( !type.targetObj ) type.targetObj = targetObj;
							type.func(ary[type.responseIndex], type.targetObj);
						} else if ( type == 'list' ) {
							ACCustomFieldsObj.handleList(ACCustomFieldsResult, targetObj);
						} else if ( type == 'links' ) {
							ACCustomFieldsObj.handlePersonalizationLinks(ACCustomFieldsResult, targetObj);
						} else if ( type == 'pers' ) {
							ACCustomFieldsObj.handlePersonalization(ACCustomFieldsResult, targetObj, 'tag');
						} else if ( type == 'pers-with-id-values' ) {
							ACCustomFieldsObj.handlePersonalization(ACCustomFieldsResult, targetObj, 'id');
						} else if ( type == 'display' ) {
							ACCustomFieldsObj.handleDisplay(ACCustomFieldsResult, targetObj);
						} else if ( typeof(type) == 'function' ) {
							type(ACCustomFieldsResult, targetObj);
						}
					}
				}
				if ( typeof(ACCustomFieldsObj.additionalHandler) == 'function') {
					ACCustomFieldsObj.additionalHandler(ary);
				}
			},



		/* HANDLERS */


		handleList:
			function(ary, rel) {
				adesk_dom_remove_children(rel);
				var total = 0;
				if ( ary ) {
					for ( var i = 0; i < ary.length; i++ ) {
						var row = ary[i];
						var props = { name: 'fields[]', id: 'custom' + row.id + 'Field', type: 'checkbox', value: row.id };
						if ( !this.selection || adesk_array_has(this.selection, row.id) ) {
							props.checked = 'checked';
						}
						rel.appendChild(
							Builder.node(
								"tr",
								[
									Builder.node("td", [ Builder._text(" ") ]),
									Builder.node(
										"td",
										[
											Builder.node(
												'label',
												[
													Builder.node(
														'input',
														props
													),
													Builder._text(row.title)
												]
											)
										]
									)
								]
							)
						);
						total++;
					}
				}
			},

		handlePersonalization:
			function(ary, rel, elem) {
				if ( !elem ) elem = 'tag';
				// custom fields
				var nodesin  = [];
				// check if there is an existing group
				// if yes, we'll remove it first
				var optgroups = rel.getElementsByTagName('optgroup');
				for ( var i = 0; i < optgroups.length; i++ ) {
					if ( optgroups[i].label == strPersListFields ) {
						rel.removeChild(optgroups[i]);
						break;
					}
				}
				for ( var i in ary ) {
					var f = ary[i];
					if ( typeof f != 'function' ) {
						if ( !f.tag ) {
							if ( !f.perstag || f.perstag == '' ) {
								f.perstag = 'PERS_' + f.id;
							}
							f.tag = '%' + f.perstag + '%';
						}
						nodesin.push( Builder.node('option', { value: f[elem] }, [ Builder._text(f.title) ]));
					}
				}
				if ( nodesin.length > 0 ) {
					rel.appendChild(Builder.node('optgroup', { label: strPersListFields }, nodesin));
				}
				rel.selectedIndex = 0;
				//alert('handle personalization now!' + nodesin.length + rel.id);
			},

		handlePersonalizationLinks:
			function(ary, rel) {
				// custom fields
				var nodesin  = [];
				// check if there is an existing group
				// if yes, we'll remove it first
				var divgroups = $$('#' + rel.id + ' div.personalizelisttitle a');
				for ( var i = 0; i < divgroups.length; i++ ) {
					if ( divgroups[i].innerHTML == strPersListFields ) {
						rel.removeChild(divgroups[i].parentNode.parentNode);
						break;
					}
				}
				for ( var i in ary ) {
					var f = ary[i];
					if ( typeof f != 'function' ) {
						if ( !f.tag ) {
							if ( !f.perstag || f.perstag == '' ) {
								f.perstag = 'PERS_' + f.id;
							}
							f.tag = '%' + f.perstag + '%';
						}
						nodesin.push(
							Builder.node(
								'li',
								[
									Builder.node(
										'a', {
											href: '#',
											onclick: "form_editor_personalize_insert('" + f.tag + "');return false;",
											style: 'font-weight:bold;'
										},
										[ Builder._text(f.title) ]
									)
								]
							)
						);
					}
				}
				if ( nodesin.length > 0 ) {
					adesk_dom_remove_children($("personalize_subinfo_field"));
					form_editor_personalization_push(nodesin, "personalize_subinfo_field");
				}
				//alert('handle personalization now!' + nodesin.length + rel.id);
			},

		handleDisplay:
			function(ary, targetId) {
				var rel = $(targetId);
				adesk_dom_remove_children(rel);
				var total = 0;
				var visible = 0;
				if ( ary ) {
					for ( var i = 0; i < ary.length; i++ ) {
						var row = ary[i];
						var node = adesk_custom_fields_cons(row, this.showhidden);

						if (typeof node.innerHTML != "undefined") {
							if ( parseInt(row.type, 10) != 6 || this.showhidden ) {
								rel.appendChild(Builder.node(
									"tr",
									[
										Builder.node("td", { valign: 'top'/*, width: "75"*/ }, [ Builder._text(adesk_custom_fields_title(row, this.showhidden)) ]),
										Builder.node("td", [ node ])
									]
								));
							} else {
								rel.appendChild(node);
								/*rel.appendChild(Builder.node(
									"tr",
									[
										Builder.node("td", [ Builder._text(" ") ]),
										Builder.node("td", [ node ])
									]
								));*/
							}
						}
						total++;
						if ( parseInt(row.type, 10) != 6 ) visible++;
					}
				}
			}
	};
}
