// utf.js

function adesk_utf_unescape(str) {
	return str.replace(/&#([0-9]+);/g, function(str, mat) { return String.fromCharCode(parseInt(mat, 10)); });
}

function adesk_utf_check(str) {
	for ( var i = 0; i < str.length; i++ ) {
		if ( str.charCodeAt(i) < 0x80 ) {
			// do nothing if 0bbbbbbb
			continue;
		} else if ( ( str.charCodeAt(i) & 0xE0 ) == 0xC0 ) {
			// 110bbbbb
			n = 1;
		} else if ( ( str.charCodeAt(i) & 0xF0 ) == 0xE0 ) {
			// 1110bbbb
			n = 2;
		} else if ( ( str.charCodeAt(i) & 0xF8 ) == 0xF0 ) {
			// 11110bbb
			n = 3;
		} else if ( ( str.charCodeAt(i) & 0xFC ) == 0xF8 ) {
			// 111110bb
			n = 4;
		} else if ( ( str.charCodeAt(i) & 0xFE ) == 0xFC ) {
			// 1111110b
			n = 5;
		} else {
			// it does not match any model
			return false;
		}
		// loop through found bytes offset
		for ( var j = 0; j < n; j++ ) {
			if ( ( ++i == str.length ) || ( ( str.charCodeAt(i) & 0xC0 ) != 0x80 ) ) {
				return false;
			}
		}
	}
	// it is utf8 string, nothing bad found
	return true;
}

function adesk_utf_reinterpret(str) {
	// If we have a UTF-8 string which we don't recognize as UTF-8 (each byte is interpreted
	// separately), put it back together.
	
	var _out = "";
	var a, b, c, d;

	for (var i = 0; i < str.length; i++) {
		a = str.charCodeAt(i);

		switch (adesk_utf_codelen(a)) {
			case 1:
			default:
				_out += str.charAt(i);
				break;

			case 2:
				a = a & 31;
				b = str.charCodeAt(++i) & 63;
				_out += String.fromCharCode((a << 6) | b);
				break;

			case 3:
				a = a & 15;
				b = str.charCodeAt(++i) & 63;
				c = str.charCodeAt(++i) & 63;
				_out += String.fromCharCode((a << 12) | (b << 6) | c);
				break;

			case 4:
				a = a & 7;
				b = str.charCodeAt(++i) & 63;
				c = str.charCodeAt(++i) & 63;
				d = str.charCodeAt(++i) & 63;
				_out += String.fromCharCode((a << 18) | (b << 12) | (c << 6) | d);
				break;
		}
	}

	return _out;
}

function adesk_utf_codelen(b) {
	if ((b & 240) == 240)
		return 4;
	if ((b & 224) == 224)
		return 3;
	if ((b & 192) == 192)
		return 2;
	return 1;
}

function adesk_utf_uri_encode(str, length) {
	// define needed vars
	if ( !length ) length = 0;
	var unicode = '';
	var values = new Array();
	var octets = 1;
	// loop through string
	for ( i = 0; i < str.length; i++ ) {
		var value = str.charCodeAt(i);
		if ( value < 128 ) {
			// if regular char
			if ( length && ( unicode.length + 1 > length ) ) {
				break;
			}
			unicode = unicode + String.fromCharCode(value);
		} else {
			// where is it?
			if ( values.length == 0 ) octets = ( value < 224 ? 2 : 3 );
			values.push(value);
			if ( length && ( unicode.length + octets * 3 > length ) ) {
				break;
			}
			// when found all parts, combine them
			if ( values.length == octets ) {
				unicode = unicode + '%' . values[0].toString(16) + '%' + values[1].toString(16);
				if ( octets == 3 ) unicode = unicode + '%' + values[2].toString(16);
				var values = new Array();
				var octets = 1;
			}
		}
	}
	return unicode;
}
