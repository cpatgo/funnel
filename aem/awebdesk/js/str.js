// str.js

/*
function adesk_str_trim(str) {
    return str.replace(/^\s*(\S+)\s*$/, "$1");
}
*/
function adesk_str_trim(str, chars) {
	return adesk_str_ltrim(adesk_str_rtrim(str, chars), chars);
}

function adesk_str_ltrim(str, chars) {
	str += '';
	chars = chars || "\\s";
	return str.replace(new RegExp("^[" + chars + "]+", "g"), "");
}

function adesk_str_rtrim(str, chars) {
	str += '';
	chars = chars || "\\s";
	return str.replace(new RegExp("[" + chars + "]+$", "g"), "");
}

function adesk_str_repeat(str, times) {
	var out = "";

	while (times--)
		out += str;

	return out;
}

function adesk_str_shorten(text, chars) {
	if ( !chars || chars == 0 ) return text;
    var textLength = text.length;
    text += ' ';
    text = text.substr(0, chars);
    var lastSpacePos = text.lastIndexOf(' ');
    if ( lastSpacePos != -1 )
        text = text.substr(0, lastSpacePos);
    if ( textLength > text.length )
        text += '...';

    return text;
}

function adesk_str_middleshorten(text, front_chars, back_chars) {
	if ( !front_chars || front_chars == 0 ) return text;
	if ( !back_chars || back_chars == 0 ) return text;
	if ( text.length < front_chars + back_chars ) return text;
    var front = text.substr(0, front_chars);
    var back  = text.substr(text.length - back_chars, back_chars);
    return front + '...' + back;
}


function adesk_str_array(str) {
    var ary = new Array();

    for (var i = 0; i < str.length; i++) {
        if (str[i] == '&') {
            var tmp = "";
            while (i < str.length) {
                tmp += str[i++];
                if (str[i-1] == ';')
                    break;
            }

            ary.push(tmp);
        } else {
            ary.push(str[i]);
        }
    }

    return ary;
}

function adesk_array_has(ary, val) {
    //for (var i = 0; i < ary.length; i++) {
    for ( var i in ary ) {
        if (ary[i] == val)
            return true;
    }

    return false;
}

function adesk_array_extract(str) {
    var ary = new Array();
    var tmp = str.split("||");

    for (var i = 0; i < tmp.length; i++) {
        var ent = tmp[i].split("=", 2);
        ary[ent[0]] = ent[1];
    }

    return ary;
}

function adesk_str_array_len(ary) {
    for (var i = 0, c = 0; i < ary.length; i++)
        c += ary[i].length;
    return c;
}

function adesk_str_array_substr(ary, off, len) {
    var tmp = "";
    for (var i = off; i < ary.length; i++) {
        if (i >= len)
            break;
        tmp += ary[i];
    }

    return tmp;
}

function adesk_str_url(rel) {
    var ary = rel.split("/");
    var url = window.location.href.replace(/\/[^\/]*$/, "");

    for (var i = 0; i < ary.length; i++) {
        if (ary[i] == "..")
            url = url.replace(/\/[^\/]*$/, "");
        else
            url += "/" + ary[i];
    }

    return url;
}

function adesk_ary_last(ary, begin) {
    var nary = new Array();

    for (var i = begin, j = 0; i < ary.length; i++, j++) {
        nary[j] = ary[i];
    }

    return nary;
}

function adesk_str_rand_password(len) {
	var out = "";

	while (len--) {
		out += adesk_str_rand_passchar();
	}

	return out;
}

function adesk_str_rand_passchar() {
	var floor = Math.floor(Math.random() * 10.0);
	var chr;

	if (floor > 6) {
		chr = Math.floor(Math.random() * 10.0);
		chr = chr.toString();
	} else {
		var off = Math.floor(Math.random() * 100.0) % 26;
		chr = "a".charCodeAt(0) + off;
		chr = String.fromCharCode(chr);
	}

	return chr;
}

function adesk_sprintf(fmt, args) {
    var out;
    var argi;

    out     = "";
    argi    = 0;

    for (var i = 0; i < fmt.length; i++) {
        var fmtc = fmt.charAt(i);
        switch (fmtc) {
            case "\\":
                i++;
                break;
            case "%":
                if (argi < args.length) {
                    fmtc = fmt.charAt(i+1);
                    out += adesk_sprintf_spec(fmtc, args[argi]);
                    i++;
                    argi++;
                } else {
                    out += fmtc;
                }
                break;
            default:
                out += fmtc;
                break;
        }
    }

    return out;
}

function adesk_sprintf_spec(ch, arg) {
    switch (ch) {
        case "d":
        case "f":
            return arg.toString();
        case "s":
        default:
            return arg;
    }

    return "";
}


// This code is in the public domain. Feel free to link back to http://jan.moesen.nu/
function sprintf() {
	if (!arguments || arguments.length < 1 || !RegExp)
	{
		return;
	}
	var str = arguments[0];
	var re = /([^%]*)%('.|0|\x20)?(-)?(\d+)?(\.\d+)?(%|b|c|d|u|f|o|s|x|X)(.*)/; // '
	var a = b = [], numSubstitutions = 0, numMatches = 0;
	while (a = re.exec(str))
	{
		var leftpart = a[1], pPad = a[2], pJustify = a[3], pMinLength = a[4];
		var pPrecision = a[5], pType = a[6], rightPart = a[7];

		//alert(a + '\n' + [a[0], leftpart, pPad, pJustify, pMinLength, pPrecision);

		numMatches++;
		if (pType == '%')
		{
			subst = '%';
		}
		else
		{
			numSubstitutions++;
			if (numSubstitutions >= arguments.length)
			{
				//alert('Error! Not enough function arguments (' + (arguments.length - 1) + ', excluding the string)\nfor the number of substitution parameters in string (' + numSubstitutions + ' so far).\n\nString in question:\n' + str);
				return;
			}
			var param = arguments[numSubstitutions];
			var pad = '';
			       if (pPad && pPad.substr(0,1) == "'") pad = leftpart.substr(1,1);
			  else if (pPad) pad = pPad;
			var justifyRight = true;
			       if (pJustify && pJustify === "-") justifyRight = false;
			var minLength = -1;
			       if (pMinLength) minLength = parseInt(pMinLength);
			var precision = -1;
			       if (pPrecision && pType == 'f') precision = parseInt(pPrecision.substring(1));
			var subst = param;
			       if (pType == 'b') subst = parseInt(param).toString(2);
			  else if (pType == 'c') subst = String.fromCharCode(parseInt(param));
			  else if (pType == 'd') subst = parseInt(param) ? parseInt(param) : 0;
			  else if (pType == 'u') subst = Math.abs(param);
			  else if (pType == 'f') subst = (precision > -1) ? Math.round(parseFloat(param) * Math.pow(10, precision)) / Math.pow(10, precision): parseFloat(param);
			  else if (pType == 'o') subst = parseInt(param).toString(8);
			  else if (pType == 's') subst = param;
			  else if (pType == 'x') subst = ('' + parseInt(param).toString(16)).toLowerCase();
			  else if (pType == 'X') subst = ('' + parseInt(param).toString(16)).toUpperCase();
		}
		str = leftpart + subst + rightPart;
	}
	return str;
}


/*
 * This is the function that actually highlights a text string by
 * adding HTML tags before and after all occurrences of the search
 * term. You can pass your own tags if you'd like, or if the
 * highlightStartTag or highlightEndTag parameters are omitted or
 * are empty strings then the default <font> tags will be used.
 */
function adesk_str_highlight(bodyText, searchTerm, highlightStartTag, highlightEndTag)
{
  // the highlightStartTag and highlightEndTag parameters are optional
  if ((!highlightStartTag) || (!highlightEndTag)) {
    highlightStartTag = "<font style='color:blue; background-color:yellow;'>";
    highlightEndTag = "</font>";
  }

  // find all occurences of the search term in the given text,
  // and add some "highlight" tags to them (we're not using a
  // regular expression search, because we want to filter out
  // matches that occur within HTML tags and script blocks, so
  // we have to do a little extra validation)
  var newText = "";
  var i = -1;
  var lcSearchTerm = searchTerm.toLowerCase();
  var lcBodyText = bodyText.toLowerCase();

  while (bodyText.length > 0) {
    i = lcBodyText.indexOf(lcSearchTerm, i+1);
    if (i < 0) {
      newText += bodyText;
      bodyText = "";
    } else {
      // skip anything inside an HTML tag
      if (bodyText.lastIndexOf(">", i) >= bodyText.lastIndexOf("<", i)) {
        if (
	      // skip anything inside a <script> block
          (lcBodyText.lastIndexOf("/script>", i) >= lcBodyText.lastIndexOf("<script", i))
        ||
    	  // skip anything inside a <style> block
          (lcBodyText.lastIndexOf("/style>", i) >= lcBodyText.lastIndexOf("<style", i))
        ) {
          newText += bodyText.substring(0, i) + highlightStartTag + bodyText.substr(i, searchTerm.length) + highlightEndTag;
          bodyText = bodyText.substr(i + searchTerm.length);
          lcBodyText = bodyText.toLowerCase();
          i = -1;
        }
      }
    }
  }

  return newText;
}


/*
 * This is sort of a wrapper function to the adesk_str_highlight function.
 * It takes the searchText that you pass, optionally splits it into
 * separate words, transforms the text and returns it.
 * Only the "bodyText" and "searchText" parameters are required; all other parameters
 * are optional and can be omitted.
 */
function adesk_str_highlight_phrase(bodyText, searchText, treatAsPhrase, customColorIndex)
{
  // if the treatAsPhrase parameter is true, then we should search for
  // the entire phrase that was entered; otherwise, we will split the
  // search string so that each word is searched for and highlighted
  // individually
  if (treatAsPhrase) {
    var searchArray = [searchText];
  } else {
    var searchArray = searchText.split(" ");
    if ( searchArray.length == 1 ) {
      var treatAsPhrase = true;
    }
  }

  var colors = [ 'yellow', '#99FF99', '#FFCCFF', '#CC99FF', '#99CCFF', '#FFCC99', '#CCCCFF', '#66CCFF' ];
  for (var i = 0; i < searchArray.length; i++) {
    // choose color
    if (!customColorIndex && customColorIndex != 0) {
      if (treatAsPhrase) {
        var colorIndex = 0;
      } else {
        var colorIndex = ( i % 7 ) + 1;
      }
    } else {
      colorIndex = customColorIndex;
    }
    var color = colors[colorIndex];
    highlightStartTag = '<font class="__highlight" style="background-color: ' + color + ';">';
    highlightEndTag = '</font>';
    bodyText = adesk_str_highlight(bodyText, searchArray[i], highlightStartTag, highlightEndTag);
  }

  return bodyText;
}



/*
var __adesk_highlight_tags = [];
var __adesk_highlight_tag = '';
var __adesk_highlight_i = 0;

function adesk_str_highlight(str, terms, tag) {
	if ( tag == null || tag == undefined )
		var tag = '<b style="color: #000; background-color: #%s;">%s</b>';
	var orig = str;
	var colors = [ 'ff0', '0ff', 'f0f' ];
	var i = 0;
	if ( terms.length == 0 || ( terms.length == 1 && adesk_str_trim(terms[0]) == '' ) ) return str;
	__adesk_highlight_tags = [];
	for ( var i = 0; i < terms.length; i++ ) {
		// choose color
		var colorIndex = i % 3;
		var color = colors[colorIndex];
		if ( terms[i].length > 1 ) {
			// escape term
			var q = preg_quote(terms[i]);
			// If there are tags, we need to stay outside them
			__adesk_highlight_tag = tag;
			__adesk_highlight_i = i;
			if ( !str.match(/<.+>/) ) {
				// text
				str = str.replace(
					/(\b + q + \b)/ig,
					function(m) {
						alert(m);return m;
						var found = sprintf(__adesk_highlight_tag, __adesk_highlight_i, m[1]);
						var r = adesk_b64_encode(found);
						__adesk_highlight_tags[r] = found;
						return r;
					}
				);
			} else {
				// html
				str = str.replace(
					/(?<=>)([^<]+)?(\b/ + q + /\b)/ig,
					function(m) {
						var found = m[1] + sprintf(__adesk_highlight_tag, __adesk_highlight_i, m[2]);
						var r = adesk_b64_encode(found);
						__adesk_highlight_tags[r] = found;
						return r;
					}
				);
			}
		}
	}
	// do final replacements
	if ( __adesk_highlight_tags.length > 0 ) {
		str = str.replace(adesk_array_keys(__adesk_highlight_tags), adesk_array_values(__adesk_highlight_tags));
	}
	return str;
}
*/

function nl2br(str) {
	if ( typeof(str) == "string" )
		return str.replace(/(\r\n)|(\n\r)|\r|\n/g,'<br />'); // '
	else
		return str;
}

function preg_quote( str ) {
	// Quote regular expression characters
	//
	// +    discuss at: http://kevin.vanzonneveld.net/techblog/article/javascript_equivalent_for_phps_preg_quote/
	// +       version: 801.2320
	// +   original by: booeyOH
	// +   improved by: Ates Goral (http://magnetiq.com)
	// *     example 1: preg_quote("$40");
	// *     returns 1: "\\\$40"
	// *     example 2: preg_quote("*RRRING* Hello?");
	// *     returns 2: "\\*RRRING\\* Hello\\?"
	// *     example 3: preg_quote("\\.+*?[^]$(){}=!<>|:");
	// *     returns 3: "\\\\\\.\\+\\*\\?\\[\\^\\]\\$\\(\\)\\{\\}\\=\\!\\<\\>\\|\\:"

	return str.replace(/([\\\.\+\*\?\[\^\]\$\(\)\{\}\=\!\<\>\|\:])/g, "\\$1");
}

function adesk_str_urlsafe(str) {
	// strip all tags first
	str = strip_tags(str);
	// encode escaped octets
	str = str.replace(/%([a-fA-F0-9][a-fA-F0-9])/, '-=-$1-=-');
	// remove percent signs
	str = str.replace('%', '');
	// decode found octets
	str = str.replace(/-=-([a-fA-F0-9][a-fA-F0-9])-=-/g, '%$1');
	// do your best to mask all weird chars
	str = adesk_str_remove_accents(str);
	// if string is in utf8
	if ( adesk_utf_check(str) ) {
		// encode string for usage in url
		str = adesk_utf_uri_encode(str, 200);
	}
	// paths should be lowercased
	str = str.toLowerCase();
	// remove all entities,
	str = str.replace(/&.+?;/g, '');
	// harmfull chars,
	str = str.replace(/[^%a-z0-9 _-]/g, '');
	// whitespaces
	str = str.replace(/\s+/g, '-');
	// and other...
	str = str.replace(/-+/g, '-');
	str = adesk_str_trim(str, '-');
	// return clean string
	return str;
}



function adesk_str_remove_accents(string) {
	// if none found, return the string right away
	if ( !string.match(/[\u80-\uff]/) ) {
		return string;
	}
	if ( adesk_utf_check(string) ) {
		// if string is in utf8
		var chars = new Array();
		chars[((195 << 6) | 128).toString()] = 'A';
		chars[((195 << 6) | 129).toString()] = 'A';
		chars[((195 << 6) | 130).toString()] = 'A';
		chars[((195 << 6) | 131).toString()] = 'A';
		chars[((195 << 6) | 132).toString()] = 'A';
		chars[((195 << 6) | 133).toString()] = 'A';
		chars[((195 << 6) | 135).toString()] = 'C';
		chars[((195 << 6) | 136).toString()] = 'E';
		chars[((195 << 6) | 137).toString()] = 'E';
		chars[((195 << 6) | 138).toString()] = 'E';
		chars[((195 << 6) | 139).toString()] = 'E';
		chars[((195 << 6) | 140).toString()] = 'I';
		chars[((195 << 6) | 141).toString()] = 'I';
		chars[((195 << 6) | 142).toString()] = 'I';
		chars[((195 << 6) | 143).toString()] = 'I';
		chars[((195 << 6) | 145).toString()] = 'N';
		chars[((195 << 6) | 146).toString()] = 'O';
		chars[((195 << 6) | 147).toString()] = 'O';
		chars[((195 << 6) | 148).toString()] = 'O';
		chars[((195 << 6) | 149).toString()] = 'O';
		chars[((195 << 6) | 150).toString()] = 'O';
		chars[((195 << 6) | 153).toString()] = 'U';
		chars[((195 << 6) | 154).toString()] = 'U';
		chars[((195 << 6) | 155).toString()] = 'U';
		chars[((195 << 6) | 156).toString()] = 'U';
		chars[((195 << 6) | 157).toString()] = 'Y';
		chars[((195 << 6) | 159).toString()] = 's';
		chars[((195 << 6) | 160).toString()] = 'a';
		chars[((195 << 6) | 161).toString()] = 'a';
		chars[((195 << 6) | 162).toString()] = 'a';
		chars[((195 << 6) | 163).toString()] = 'a';
		chars[((195 << 6) | 164).toString()] = 'a';
		chars[((195 << 6) | 165).toString()] = 'a';
		chars[((195 << 6) | 167).toString()] = 'c';
		chars[((195 << 6) | 168).toString()] = 'e';
		chars[((195 << 6) | 169).toString()] = 'e';
		chars[((195 << 6) | 170).toString()] = 'e';
		chars[((195 << 6) | 171).toString()] = 'e';
		chars[((195 << 6) | 172).toString()] = 'i';
		chars[((195 << 6) | 173).toString()] = 'i';
		chars[((195 << 6) | 174).toString()] = 'i';
		chars[((195 << 6) | 175).toString()] = 'i';
		chars[((195 << 6) | 177).toString()] = 'n';
		chars[((195 << 6) | 178).toString()] = 'o';
		chars[((195 << 6) | 179).toString()] = 'o';
		chars[((195 << 6) | 180).toString()] = 'o';
		chars[((195 << 6) | 181).toString()] = 'o';
		chars[((195 << 6) | 182).toString()] = 'o';
		chars[((195 << 6) | 182).toString()] = 'o';
		chars[((195 << 6) | 185).toString()] = 'u';
		chars[((195 << 6) | 186).toString()] = 'u';
		chars[((195 << 6) | 187).toString()] = 'u';
		chars[((195 << 6) | 188).toString()] = 'u';
		chars[((195 << 6) | 189).toString()] = 'y';
		chars[((195 << 6) | 191).toString()] = 'y';
		chars[((196 << 6) | 128).toString()] = 'A';
		chars[((196 << 6) | 129).toString()] = 'a';
		chars[((196 << 6) | 130).toString()] = 'A';
		chars[((196 << 6) | 131).toString()] = 'a';
		chars[((196 << 6) | 132).toString()] = 'A';
		chars[((196 << 6) | 133).toString()] = 'a';
		chars[((196 << 6) | 134).toString()] = 'C';
		chars[((196 << 6) | 135).toString()] = 'c';
		chars[((196 << 6) | 136).toString()] = 'C';
		chars[((196 << 6) | 137).toString()] = 'c';
		chars[((196 << 6) | 138).toString()] = 'C';
		chars[((196 << 6) | 139).toString()] = 'c';
		chars[((196 << 6) | 140).toString()] = 'C';
		chars[((196 << 6) | 141).toString()] = 'c';
		chars[((196 << 6) | 142).toString()] = 'D';
		chars[((196 << 6) | 143).toString()] = 'd';
		chars[((196 << 6) | 144).toString()] = 'D';
		chars[((196 << 6) | 145).toString()] = 'd';
		chars[((196 << 6) | 146).toString()] = 'E';
		chars[((196 << 6) | 147).toString()] = 'e';
		chars[((196 << 6) | 148).toString()] = 'E';
		chars[((196 << 6) | 149).toString()] = 'e';
		chars[((196 << 6) | 150).toString()] = 'E';
		chars[((196 << 6) | 151).toString()] = 'e';
		chars[((196 << 6) | 152).toString()] = 'E';
		chars[((196 << 6) | 153).toString()] = 'e';
		chars[((196 << 6) | 154).toString()] = 'E';
		chars[((196 << 6) | 155).toString()] = 'e';
		chars[((196 << 6) | 156).toString()] = 'G';
		chars[((196 << 6) | 157).toString()] = 'g';
		chars[((196 << 6) | 158).toString()] = 'G';
		chars[((196 << 6) | 159).toString()] = 'g';
		chars[((196 << 6) | 160).toString()] = 'G';
		chars[((196 << 6) | 161).toString()] = 'g';
		chars[((196 << 6) | 162).toString()] = 'G';
		chars[((196 << 6) | 163).toString()] = 'g';
		chars[((196 << 6) | 164).toString()] = 'H';
		chars[((196 << 6) | 165).toString()] = 'h';
		chars[((196 << 6) | 166).toString()] = 'H';
		chars[((196 << 6) | 167).toString()] = 'h';
		chars[((196 << 6) | 168).toString()] = 'I';
		chars[((196 << 6) | 169).toString()] = 'i';
		chars[((196 << 6) | 170).toString()] = 'I';
		chars[((196 << 6) | 171).toString()] = 'i';
		chars[((196 << 6) | 172).toString()] = 'I';
		chars[((196 << 6) | 173).toString()] = 'i';
		chars[((196 << 6) | 174).toString()] = 'I';
		chars[((196 << 6) | 175).toString()] = 'i';
		chars[((196 << 6) | 176).toString()] = 'I';
		chars[((196 << 6) | 177).toString()] = 'i';
		chars[((196 << 6) | 178).toString()] = 'IJ';
		chars[((196 << 6) | 179).toString()] = 'ij';
		chars[((196 << 6) | 180).toString()] = 'J';
		chars[((196 << 6) | 181).toString()] = 'j';
		chars[((196 << 6) | 182).toString()] = 'K';
		chars[((196 << 6) | 183).toString()] = 'k';
		chars[((196 << 6) | 184).toString()] = 'k';
		chars[((196 << 6) | 185).toString()] = 'L';
		chars[((196 << 6) | 186).toString()] = 'l';
		chars[((196 << 6) | 187).toString()] = 'L';
		chars[((196 << 6) | 188).toString()] = 'l';
		chars[((196 << 6) | 189).toString()] = 'L';
		chars[((196 << 6) | 190).toString()] = 'l';
		chars[((196 << 6) | 191).toString()] = 'L';
		chars[((197 << 6) | 128).toString()] = 'l';
		chars[((197 << 6) | 129).toString()] = 'L';
		chars[((197 << 6) | 130).toString()] = 'l';
		chars[((197 << 6) | 131).toString()] = 'N';
		chars[((197 << 6) | 132).toString()] = 'n';
		chars[((197 << 6) | 133).toString()] = 'N';
		chars[((197 << 6) | 134).toString()] = 'n';
		chars[((197 << 6) | 135).toString()] = 'N';
		chars[((197 << 6) | 136).toString()] = 'n';
		chars[((197 << 6) | 137).toString()] = 'N';
		chars[((197 << 6) | 138).toString()] = 'n';
		chars[((197 << 6) | 139).toString()] = 'N';
		chars[((197 << 6) | 140).toString()] = 'O';
		chars[((197 << 6) | 141).toString()] = 'o';
		chars[((197 << 6) | 142).toString()] = 'O';
		chars[((197 << 6) | 143).toString()] = 'o';
		chars[((197 << 6) | 144).toString()] = 'O';
		chars[((197 << 6) | 145).toString()] = 'o';
		chars[((197 << 6) | 146).toString()] = 'OE';
		chars[((197 << 6) | 147).toString()] = 'oe';
		chars[((197 << 6) | 148).toString()] = 'R';
		chars[((197 << 6) | 149).toString()] = 'r';
		chars[((197 << 6) | 150).toString()] = 'R';
		chars[((197 << 6) | 151).toString()] = 'r';
		chars[((197 << 6) | 152).toString()] = 'R';
		chars[((197 << 6) | 153).toString()] = 'r';
		chars[((197 << 6) | 154).toString()] = 'S';
		chars[((197 << 6) | 155).toString()] = 's';
		chars[((197 << 6) | 156).toString()] = 'S';
		chars[((197 << 6) | 157).toString()] = 's';
		chars[((197 << 6) | 158).toString()] = 'S';
		chars[((197 << 6) | 159).toString()] = 's';
		chars[((197 << 6) | 160).toString()] = 'S';
		chars[((197 << 6) | 161).toString()] = 's';
		chars[((197 << 6) | 162).toString()] = 'T';
		chars[((197 << 6) | 163).toString()] = 't';
		chars[((197 << 6) | 164).toString()] = 'T';
		chars[((197 << 6) | 165).toString()] = 't';
		chars[((197 << 6) | 166).toString()] = 'T';
		chars[((197 << 6) | 167).toString()] = 't';
		chars[((197 << 6) | 168).toString()] = 'U';
		chars[((197 << 6) | 169).toString()] = 'u';
		chars[((197 << 6) | 170).toString()] = 'U';
		chars[((197 << 6) | 171).toString()] = 'u';
		chars[((197 << 6) | 172).toString()] = 'U';
		chars[((197 << 6) | 173).toString()] = 'u';
		chars[((197 << 6) | 174).toString()] = 'U';
		chars[((197 << 6) | 175).toString()] = 'u';
		chars[((197 << 6) | 176).toString()] = 'U';
		chars[((197 << 6) | 177).toString()] = 'u';
		chars[((197 << 6) | 178).toString()] = 'U';
		chars[((197 << 6) | 179).toString()] = 'u';
		chars[((197 << 6) | 180).toString()] = 'W';
		chars[((197 << 6) | 181).toString()] = 'w';
		chars[((197 << 6) | 182).toString()] = 'Y';
		chars[((197 << 6) | 183).toString()] = 'y';
		chars[((197 << 6) | 184).toString()] = 'Y';
		chars[((197 << 6) | 185).toString()] = 'Z';
		chars[((197 << 6) | 186).toString()] = 'z';
		chars[((197 << 6) | 187).toString()] = 'Z';
		chars[((197 << 6) | 188).toString()] = 'z';
		chars[((197 << 6) | 189).toString()] = 'Z';
		chars[((197 << 6) | 190).toString()] = 'z';
		chars[((197 << 6) | 191).toString()] = 's';
		chars[((226 << 12) | (130 << 6) | 172).toString()] = 'E';
		chars[((194 << 6) | 163).toString()] = '';
		// do the replacements
		for (var i = 0; i < string.length; i++) {
			var code = string.charCodeAt(i).toString();
			var chr = string[i];
			if (chars[code]) {
				string[i] = chars[code];
			}
		}
	} else {
		// assume it is ISO-8859-1 if not UTF-8
		var chars = {
			'in' :
				String.fromCharCode(128) + String.fromCharCode(131) + String.fromCharCode(138) + String.fromCharCode(142) + String.fromCharCode(154) + String.fromCharCode(158) + String.fromCharCode(159) +
				String.fromCharCode(162) + String.fromCharCode(165) + String.fromCharCode(181) + String.fromCharCode(192) + String.fromCharCode(193) + String.fromCharCode(194) + String.fromCharCode(195) +
				String.fromCharCode(196) + String.fromCharCode(197) + String.fromCharCode(199) + String.fromCharCode(200) + String.fromCharCode(201) + String.fromCharCode(202) + String.fromCharCode(203) +
				String.fromCharCode(204) + String.fromCharCode(205) + String.fromCharCode(206) + String.fromCharCode(207) + String.fromCharCode(209) + String.fromCharCode(210) + String.fromCharCode(211) +
				String.fromCharCode(212) + String.fromCharCode(213) + String.fromCharCode(214) + String.fromCharCode(216) + String.fromCharCode(217) + String.fromCharCode(218) + String.fromCharCode(219) +
				String.fromCharCode(220) + String.fromCharCode(221) + String.fromCharCode(224) + String.fromCharCode(225) + String.fromCharCode(226) + String.fromCharCode(227) + String.fromCharCode(228) +
				String.fromCharCode(229) + String.fromCharCode(231) + String.fromCharCode(232) + String.fromCharCode(233) + String.fromCharCode(234) + String.fromCharCode(235) + String.fromCharCode(236) +
				String.fromCharCode(237) + String.fromCharCode(238) + String.fromCharCode(239) + String.fromCharCode(241) + String.fromCharCode(242) + String.fromCharCode(243) + String.fromCharCode(244) +
				String.fromCharCode(245) + String.fromCharCode(246) + String.fromCharCode(248) + String.fromCharCode(249) + String.fromCharCode(250) + String.fromCharCode(251) + String.fromCharCode(252) +
				String.fromCharCode(253) + String.fromCharCode(255),
			'out' :
				'EfSZszYcYuAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy',
			'inin' :
				[ String.fromCharCode(140), String.fromCharCode(156), String.fromCharCode(198), String.fromCharCode(208), String.fromCharCode(222), String.fromCharCode(223), String.fromCharCode(230), String.fromCharCode(240), String.fromCharCode(254) ],
			'outout' :
				[ 'OE', 'oe', 'AE', 'DH', 'TH', 'ss', 'ae', 'dh', 'th' ]
		};
		// replace single characters
		string = adesk_str_strtr(string, chars['in'], chars['out']);
		// replace double characters
		string = adesk_str_replace(chars['inin'], chars['outout'], string);
	}
	// return a clean string
	return string;
}

function adesk_str_file_humansize(size) {
	var count = 0;
	var format = new Array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
	while( ( size / 1024 ) > 1 && count < 8 ) {
		size = size / 1024;
		count++;
	}
	//var decimals = size < 10;

	return Math.round(size) + ' ' + format[count];
}

// Always pass adesk_strings.js vars decimalDelim and commaDelim if default
function adesk_number_format(nStr, decimalDelim, commaDelim) {
	nStr += '';
	x = nStr.split('.');
	x1 = x[0];
	x2 = x.length > 1 ? decimalDelim + x[1] : '';
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + commaDelim + '$2');
	}
	return x1 + x2;
}

function adesk_str_strtr(str, list) {
	if ( arguments[2] ) {
		var r = arguments[2];
		for ( var i = 0; i < list.length; i++ ) {
			str = str.replace( new RegExp(list.charAt(i), "g"), r.charAt(i) );
		}
	} else {
		for ( var c in list ) {
			str = str.replace( new RegExp(c, "g"), list[c] );
		}
	}
	return str;
}

function adesk_str_replace(search, replace, subject) {
	if ( typeof(search) == "string" ) return subject.replace(RegExp(search, "g"), replace);
	for ( var i in search ) {
		if ( replace[i] && typeof(search[i]) + typeof(replace[i]) == "stringstring" )
			subject = subject.replace(RegExp(search[i], "g"), replace[i]);
	}
	return subject;
}

function strip_tags(str, trim) {
	str += '';
	var r = str.replace(/<\/?[^>]+>/gi, '');
	r = r.replace(/&nbsp;/g, ' ');
	if ( trim ) r = adesk_str_trim(r);
	return r;
}

function adesk_str_escapeq(str) {
	str += '';
	str = str.replace(/\\/g, '\\\\');
	str = str.replace(/'/g, "\\'");
	str = str.replace(/"/g, '\\"');
	return str;
}

function adesk_str_htmlescape(str) {
	str += '';
	str = str.replace(/&/g, "&amp;");
	str = str.replace(/</g, "&lt;");
	str = str.replace(/>/g, "&gt;");
	str = str.replace(/'/g, "&#039;"); //'
	str = str.replace(/"/g, "&quot;"); //"

	return str;
}

function adesk_str_jsescape(str) {
	str += '';
	str = str.replace(/'/g, "\\'"); //"
	str = str.replace(/"/g, '\\"'); //'

	return str;
}

function adesk_str_email(email) {
	email += '';
    return email.match( /^[\+_a-z0-9-'&=]+(\.[\+_a-z0-9-']+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,17})$/i );
}

function adesk_str_is_url(url) {
	url += '';
    return url.match( /((http|https|ftp):\/\/|www)[a-z0-9\-\._]+\/?[a-z0-9_\.\-\?\+\/~=&#%;:\|,\[\]]*[a-z0-9\/=?&;%\[\]]{1}/i );
}


/**
*
*  Javascript sprintf
*  http://www.webtoolkit.info/
*
*
**/

sprintfWrapper = {

	init : function () {

		if (typeof arguments == "undefined") { return null; }
		if (arguments.length < 1) { return null; }
		if (typeof arguments[0] != "string") { return null; }
		if (typeof RegExp == "undefined") { return null; }

		var string = arguments[0];
		var exp = new RegExp(/(%([%]|(\-)?(\+|\x20)?(0)?(\d+)?(\.(\d)?)?([bcdfosxX])))/g);
		var matches = new Array();
		var strings = new Array();
		var convCount = 0;
		var stringPosStart = 0;
		var stringPosEnd = 0;
		var matchPosEnd = 0;
		var newString = '';
		var match = null;

		while (match = exp.exec(string)) {
			if (match[9]) { convCount += 1; }

			stringPosStart = matchPosEnd;
			stringPosEnd = exp.lastIndex - match[0].length;
			strings[strings.length] = string.substring(stringPosStart, stringPosEnd);

			matchPosEnd = exp.lastIndex;
			matches[matches.length] = {
				match: match[0],
				left: match[3] ? true : false,
				sign: match[4] || '',
				pad: match[5] || ' ',
				min: match[6] || 0,
				precision: match[8],
				code: match[9] || '%',
				negative: parseInt(arguments[convCount]) < 0 ? true : false,
				argument: String(arguments[convCount])
			};
		}
		strings[strings.length] = string.substring(matchPosEnd);

		if (matches.length == 0) { return string; }
		if ((arguments.length - 1) < convCount) { return null; }

		var code = null;
		var match = null;
		var i = null;

		for (i=0; i<matches.length; i++) {

			if (matches[i].code == '%') { substitution = '%' }
			else if (matches[i].code == 'b') {
				matches[i].argument = String(Math.abs(parseInt(matches[i].argument)).toString(2));
				substitution = sprintfWrapper.convert(matches[i], true);
			}
			else if (matches[i].code == 'c') {
				matches[i].argument = String(String.fromCharCode(parseInt(Math.abs(parseInt(matches[i].argument)))));
				substitution = sprintfWrapper.convert(matches[i], true);
			}
			else if (matches[i].code == 'd') {
				matches[i].argument = String(Math.abs(parseInt(matches[i].argument)));
				substitution = sprintfWrapper.convert(matches[i]);
			}
			else if (matches[i].code == 'f') {
				matches[i].argument = String(Math.abs(parseFloat(matches[i].argument)).toFixed(matches[i].precision ? matches[i].precision : 6));
				substitution = sprintfWrapper.convert(matches[i]);
			}
			else if (matches[i].code == 'o') {
				matches[i].argument = String(Math.abs(parseInt(matches[i].argument)).toString(8));
				substitution = sprintfWrapper.convert(matches[i]);
			}
			else if (matches[i].code == 's') {
				matches[i].argument = matches[i].argument.substring(0, matches[i].precision ? matches[i].precision : matches[i].argument.length)
				substitution = sprintfWrapper.convert(matches[i], true);
			}
			else if (matches[i].code == 'x') {
				matches[i].argument = String(Math.abs(parseInt(matches[i].argument)).toString(16));
				substitution = sprintfWrapper.convert(matches[i]);
			}
			else if (matches[i].code == 'X') {
				matches[i].argument = String(Math.abs(parseInt(matches[i].argument)).toString(16));
				substitution = sprintfWrapper.convert(matches[i]).toUpperCase();
			}
			else {
				substitution = matches[i].match;
			}

			newString += strings[i];
			newString += substitution;

		}
		newString += strings[i];

		return newString;

	},

	convert : function(match, nosign){
		if (nosign) {
			match.sign = '';
		} else {
			match.sign = match.negative ? '-' : match.sign;
		}
		var l = match.min - match.argument.length + 1 - match.sign.length;
		var pad = new Array(l < 0 ? 0 : l).join(match.pad);
		if (!match.left) {
			if (match.pad == "0" || nosign) {
				return match.sign + pad + match.argument;
			} else {
				return pad + match.sign + match.argument;
			}
		} else {
			if (match.pad == "0" || nosign) {
				return match.sign + match.argument + pad.replace(/0/g, ' ');
			} else {
				return match.sign + match.argument + pad;
			}
		}
	}
}

sprintf = sprintfWrapper.init;
