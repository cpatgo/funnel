// cookie.js

function adesk_cookie_create(name, value, hours) {
    if (hours <= 0)
        hours = (24 * 365 * 5);

    var d = new Date();
    d.setTime(d.getTime() + (hours * 60 * 60 * 1000));

    document.cookie =
        name + "=" + value + "; expires=" + d.toGMTString() + "; path=/";
}

function adesk_cookie_lookup(name) {
    var ary = document.cookie.split(";");
    var i;

    for (i = 0; i < ary.length; i++) {
        var pair = adesk_str_trim(ary[i]).split("=");

        if (pair[0] == name)
            return pair[1];
    }

    return "";
}

function adesk_cookie_set(name, value, expires, path, domain, secure) {
	var cookieVar =
		name + "=" + escape(value) +
		( expires ? "; expires=" + expires.toGMTString() : "" ) +
		( path ? "; path=" + path : "" ) +
		( domain ? "; domain=" + domain : "" ) +
		( secure ? "; secure" : "" )
	;
	document.cookie = cookieVar;
}

