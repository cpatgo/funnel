// ajax.js

var adesk_ajax_debug = true;

function adesk_ajax_request_object() {
    var hreq;

    try {
        hreq = new XMLHttpRequest();
    } catch (e) {
        try {
            hreq = new ActiveXObject("Microsoft.XMLHTTP");
        } catch (__e) {
            hreq = null;
        }
    }

    if (hreq !== null) {
//      try {
//          hreq.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
//      } catch (e) {}
    }

    return hreq;
}

function adesk_ajax_init() {
}

function adesk_ajax_call_url(url, post, cb) {
    var hreq = adesk_ajax_request_object();

    if (hreq !== null) {
        hreq.onreadystatechange = function() {
            try {
                adesk_ajax_handle(hreq, cb);
            } catch (e) {}
        };
	    var method = ( post === null ? 'GET' : 'POST' );
	    var postType = typeof(post);
	    if ( post !== null ) {
		    if ( postType == 'array' || postType == 'object' ) {
		    	var postArr = new Array();
		        for ( var i in post ) {
				    var postType = typeof(post[i]);
				    if ( postType == 'array' || postType == 'object' ) {
				        for ( var j in post[i] ) {
				    		if ( typeof(post[i][j]) != 'function' ) {
			            		postArr.push(i + '[' + ( j == 'undefined' ? '' : j ) + ']=' + encodeURIComponent(post[i][j]));
				    		}
				        }
				    } else if ( postType != 'function' ) {
		            	postArr.push(i + '=' + encodeURIComponent(post[i]));
				    }
			    }
			    post = postArr.join('&');
		    }
	    }
        hreq.open(method, url, true);
        if ( post !== null ) {
        	hreq.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			//hreq.setRequestHeader("Content-length", post.length);		// IE doesn't like these
			//hreq.setRequestHeader("Connection", "close");
        }
        hreq.send(post);
    }
}

function adesk_ajax_proxy_call_url(base, url, post, cb) {
	if (post !== null)
		adesk_ajax_call_url(base + "/awebdesk/functions/ajax_proxy.php?post=1&url=" + adesk_b64_encode(url), post, cb);
	else
		adesk_ajax_call_url(base + "/awebdesk/functions/ajax_proxy.php?url=" + adesk_b64_encode(url), post, cb);
}

function adesk_ajax_proxy_call_cb(base, url, func, cb) {
    if (url.match(/\?/))
        url = url + "&f=" + func;
    else
        url = url + "?f=" + func;

    url += "&rand=" + adesk_b64_encode(Math.random().toString());

    if (arguments.length > 3) {
        for (var i = 4; i < arguments.length; i++)
            url += "&p[]=" + encodeURIComponent(arguments[i]);
    }

    adesk_ajax_proxy_call_url(base, url, null, cb);
}

function adesk_ajax_call(url, func) {
    if (arguments.length < 3)
        adesk_ajax_call_cb(adesk_str_url(url), func, null);
    else {
        adesk_ajax_call_cb(adesk_str_url(url), func, null, adesk_ary_last(arguments, 2));
    }
}

function adesk_ajax_call_cb(url, func, cb) {
    if (func) {
        if (url.match(/\?/))
            url = url + "&f=" + func;
        else
            url = url + "?f=" + func;
    }

    url += "&rand=" + adesk_b64_encode(Math.random().toString());

    if (arguments.length > 3) {
        for (var i = 3; i < arguments.length; i++)
            url += "&p[]=" + encodeURIComponent(arguments[i]);
    }

   if ( cb === null ) cb = function(){};
   adesk_ajax_call_url(url, null, cb);
}

function adesk_ajax_post_cb(url, func, cb, post) {
    if (func) {
        if (url.match(/\?/))
            url = url + "&f=" + func;
        else
            url = url + "?f=" + func;
        }

    url += "&rand=" + adesk_b64_encode(Math.random().toString());

   if ( cb === null ) cb = function(){};
   adesk_ajax_call_url(url, post, cb);
}

function adesk_ajax_handle(hreq, cb) {
    if (hreq !== null) {
        if (hreq.readyState == 4) {
            if (hreq.status == 200) {
                try {
                    var xml = hreq.responseXML;
					if (xml !== null && xml.documentElement !== null) {
						if (cb === null)
							cb = eval("cb_" + xml.documentElement.nodeName);

						if (typeof cb == "function")
							cb(xml.documentElement, hreq.responseText);
					} else {
						if ( hreq.responseText != '' ) {
							if (typeof adesk_ajax_handle_text == 'function')
								adesk_ajax_handle_text(hreq.responseText);
						}
					}
					/*
					var rootNode = ( xml !== null ? xml.documentElement : null );
					if (cb === null && rootNode) {
						cb = eval("cb_" + rootNode.nodeName);
					}
					if (typeof cb == "function")
						cb(rootNode, hreq.responseText);
					*/
                } catch (e) {
					alert(e);
                }
            }
        }
    }
}

function adesk_ajax_cb(cbf) {
	return function(xml, text) {
		window.t_xml  = xml;
		window.t_text = text;
		var ary       = adesk_dom_read_node(xml, null);
		window.t_ary  = ary;

		cbf(ary);
	}
}
