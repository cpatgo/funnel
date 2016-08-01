// ihook.js

var adesk_ihook_table = { };

function adesk_ihook_define(key, func) {
	if ( !adesk_ihook_exists(key) ) {
		adesk_ihook_table[key] = [ ];
	}
	adesk_ihook_table[key].push(func);
}

function adesk_ihook_undefine(key, func) {
	if ( adesk_ihook_exists(key) ) {
		for ( var i = 0; adesk_ihook_table[key].length; i++ ) {
			if ( adesk_ihook_table[key][i] == func ) {
				adesk_ihook_table[key].splice(i, 1);
				return;
			}
		}
	}
}

function adesk_ihook_exists(key) {
	return typeof adesk_ihook_table[key] != 'undefined';
}

function adesk_ihook(key, param) {
	var r = null;
	if ( adesk_ihook_exists(key) ) {
		for ( var i in adesk_ihook_table[key] ) {
			if ( !isNaN(parseInt(i)) ) {
	    		var func = adesk_ihook_table[key][i];
	    		if ( typeof func == 'function' ) {
		            r = func(param);
	    		}
			}
		}
	}
	return r;
}

