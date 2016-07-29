// array.js

function adesk_array_keys( input, search_value, strict ) {
    // Return all the keys of an array
    //
    // +    discuss at: http://kevin.vanzonneveld.net/techblog/article/javascript_equivalent_for_phps_array_keys/
    // +       version: 801.3120
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // *     example 1: array_keys( {firstname: 'Kevin', surname: 'van Zonneveld'} );
    // *     returns 1: {0: 'firstname', 1: 'surname'}

    var tmp_arr = new Array(), strict = !!strict, include = true, cnt = 0;

    for ( key in input ) {
        include = true;
        if ( search_value != undefined ) {
            if ( strict && input[key] !== search_value ) {
                include = false;
            } else if ( input[key] != search_value ) {
                include = false;
            }
        }

        if ( include ) {
            tmp_arr[cnt] = key;
            cnt++;
        }
    }

    return tmp_arr;
}

function adesk_array_indexof(ary, val) {
	var i;

	for (i = 0; i < ary.length; i++) {
		if (ary[i] == val)
			return i;
	}

	return -1;
}

function adesk_array_values( input ) {
    // Return all the values of an array
    //
    // +    discuss at: http://kevin.vanzonneveld.net/techblog/article/javascript_equivalent_for_phps_array_values/
    // +       version: 801.3120
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // *     example 1: array_values( {firstname: 'Kevin', surname: 'van Zonneveld'} );
    // *     returns 1: {0: 'Kevin', 1: 'van Zonneveld'}

    var tmp_arr = new Array(), cnt = 0;

    for ( key in input ){
        tmp_arr[cnt] = input[key];
        cnt++;
    }

    return tmp_arr;
}


function adesk_array_remove(node, arr, renum) {
	var newArr = new Array();
	for ( var i = 0; i < arr.length; i++ ) {
		if ( arr[i] != node ) {
			var j = ( renum ? newArr.length : i );
			newArr[j] = arr[i];
		}
	}
	return newArr;
}

function adesk_array_remove_key(k, arr) {
	var newArr = { };
	for ( var i in arr ) {
		if ( i != k ) {
			newArr[i] = arr[i];
		}
	}
	return newArr;
}
