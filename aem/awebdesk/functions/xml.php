<?php
// xml.php

// Following are functions designed either to produce XML, in the form
// of a string, from an associative array, or to produce an associative
// array from XML (again in the form of a string).

// Render $data as being contained within an element labeled by $root.
// If $data is an array, then adesk_xml_write will recurse.  Examples of
// output:
//
//   <abc>123</abc>
//
//   <abc>
//     <def>123</def>
//     <ghi>456</ghi>
//   </abc>
//
// We also handle objects, by treating them as arrays consisting of
// their member variables.  Non-public variables will not be recorded.

$adesk_xml_cdatalist = array();

function adesk_xml_cdatalist_set($elem) {
	$GLOBALS["adesk_xml_cdatalist"][$elem] = $elem;
}

function adesk_xml_cdatalist_remove($elem) {
	if (isset($GLOBALS["adesk_xml_cdatalist"][$elem]))
		unset($GLOBALS["adesk_xml_cdatalist"][$elem]);
}

function adesk_xml_cdatalist_has($elem) {
	return isset($GLOBALS["adesk_xml_cdatalist"][$elem]);
}

function adesk_xml_headers($attach = true) {
    header("Content-Type: text/xml");
    header('Expires: Fri, 1 Jan 1980 20:53:00 GMT');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Cache-Control: no-cache, must-revalidate');
    header('Pragma: no-cache');
#   header("Content-Length: ".$len);

    if ($attach) {
        adesk_http_header_attach("export.xml");
    }
}

function adesk_xml_write_new($data, $docelem = "xml", $filter = null) {
    if (is_object($data)) {
        $type = get_class($data);
        $data = get_object_vars($data);
        $data['__object_type'] = $type;
    }

    if (is_array($data)) {
		$subelem = null;
        $ary  = explode(":", $docelem);
        if (count($ary) == 2) {
            $docelem = $ary[0];
            $subelem = $ary[1];
        } else {
            //$subelem = "row";
        }

        $out  = "";

		reset($data);
		$isnumeric = true;
		if (!is_numeric(key($data))) {
			$isnumeric = false;
			$out = adesk_xml_elawebdesk_open($docelem, true);
		} elseif ($subelem !== null) {
			$out = adesk_xml_elawebdesk_open($docelem, true);
		}

        foreach ($data as $key => $val) {
            if (is_numeric($key)) {
				if ($subelem === null)
					$key = $docelem;
				else
					$key = $subelem;
			}

            if ($key !== '')
	            $out .= adesk_xml_write_new($val, $key, $filter);
        }

		if (!$isnumeric || $subelem !== null)
			$out .= adesk_xml_elawebdesk_close($docelem, true);
    } elseif ($data === null || $data === false || $data === '') {
        $out = adesk_xml_elawebdesk_empty($docelem, true);
    } else {
        $out = adesk_xml_elem($docelem, $data, $filter);
    }

    return $out;
}

function adesk_xml_write($data, $indent = "", $root = "xml", $filter = null) {
    if (is_object($data)) {
        $type = get_class($data);
        $data = get_object_vars($data);
        $data['__object_type'] = $type;
    }

    if (preg_match('/^\d/', $root))
        $root = "row";

    if (is_array($data)) {
        $compress = false;

        if (isset($data['__compress'])) {
            unset($data['__compress']);
            $compress = true;
        }

        $keys = array_keys($data);
        $out  = "";

        if (count($keys) > 0 && preg_match('/^\d/', $keys[0])) {
            if ($compress) {
                $out .= adesk_array_compact($data, "||");
            } else {
                foreach ($data as $key => $val) {
                	if ( $key !== '' )
                    	$out .= adesk_xml_write($val, $indent, $root, $filter);
                }
            }
        } else {
            $out = adesk_xml_elawebdesk_open($root, true);

            if ($compress) {
                $out .= adesk_array_compact($data, "||");
            } else {
                foreach ($data as $key => $val) {
                	if ( $key !== '' )
	                    $out .= adesk_xml_write($val, $indent . "  ", $key, $filter);
                }
            }

            $out .= adesk_xml_elawebdesk_close($root, true);
        }
    } elseif ($data == null /*|| $data == false*/ || $data === '') {
        $out = adesk_xml_elawebdesk_empty($root, true);
    } else {
        $out = adesk_xml_elem($root, $data, $filter);
    }

    return $out;
}

function adesk_xml_elawebdesk_open($elem, $nl = false) {
    if ($nl)
        return "<".$elem.">";
    return "<".$elem.">";
}

function adesk_xml_elawebdesk_close($elem, $nl = false) {
    if ($nl)
        return "</".$elem.">";
    return "</".$elem.">";
}

function adesk_xml_elawebdesk_empty($elem, $nl = false) {
    if ($nl)
        return "<".$elem."/>";
    return "<".$elem."/>";
}

function adesk_xml_elem($elem, $data, $filter = null) {
    if ($filter != null)
        $data = $filter(strval($data));
	else {
		$data = strval($data);

		if (adesk_xml_cdatalist_has($elem) || strpos($data, "&") !== false || strpos($data, "<") !== false)
			$data = "<![CDATA[" . adesk_xml_encodecdata(strval($data)) . "]]>";
	}
    if ($data == '')
        return adesk_xml_elawebdesk_empty($elem, true);
    return adesk_xml_elawebdesk_open($elem).$data.adesk_xml_elawebdesk_close($elem, true);
}

function adesk_xml_encodecdata($str) {
	# All we really need to do here is encode the end of a CDATA sequence, so that it won't
	# terminate our own CDATA sequence wrapping around it.
	return str_replace("]]>", "__--acenc:endcdata--__", $str);
}

function adesk_xml_decodecdata($str) {
	return str_replace("__--acenc:endcdata--__", "]]>", $str);
}

// -------------------------------------------------------------------
// read functions

function adesk_xml_read($xml, $skipdoc = false) {
    $parser = xml_parser_create();
    $vals   = array();
    $ary    = array();

    # Remove xml header if present.

    $xml = preg_replace('/\<\?xml.*\?\>/i', '', $xml);

    // if we don't set the SKIP_WHITE option, we may have random cdata
    // sections containing newlines, even from xml we generate.

    xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, true);

    // case folding will make all tag names upper-case, which we don't
    // want.

    xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, false);

    if (!xml_parse_into_struct($parser, $xml, $vals)) {
        $code = xml_get_error_code($parser);

        if ($code == 3)
            return "Error: syntax error at line " . xml_get_current_line_number($parser);
        else
            return "Error: code $code received at line " . xml_get_current_line_number($parser);
    }

    xml_parser_free($parser);

    // xml_parse_into_struct() will create a flat array, where the tags
    // are all stored in one level.  adesk_xml_struct_assoc will turn that
    // into an associative nested array.

    adesk_xml_struct_assoc($ary, $vals, 0, count($vals));

    if ($skipdoc) {
        $ary = array_values($ary);
        return $ary[0];
    }

    return $ary;
}

function adesk_xml_read_html($html) {
    $parser = xml_parser_create();
    $vals   = array();
    $out    = "";
    $open   = array();

    xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, false);
    xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, true);

    xml_parse_into_struct($parser, "<XML>".$html."</XML>", $vals);
    xml_parser_free($parser);

    for ($i = 0; $i < count($vals); $i++) {
        $node =& $vals[$i];
        $tag  =  $node["tag"];

        if (isset($node["attributes"])) {
            foreach ($node["attributes"] as $attr => $aval)
                $out .= adesk_xml_faketag_params("ATTR:".$attr, $aval);
        }

        if ($tag == "XML" && isset($node["value"]))
            $out .= $node["value"];
        else {
            switch ($node["type"]) {
                case "open":
                    $open[$tag][] = $i;
                    $out .= adesk_xml_faketag_open($tag);
                    if (isset($node["value"]))
                        $out .= $node["value"];
                    break;
                case "close":
                    $out .= adesk_xml_faketag_close($tag);
                    break;
                case "complete":
                    if (isset($node["value"]))
                        $out .= adesk_xml_faketag($tag, $node["value"]);
                    break;
                default:
                    break;
            }
        }
    }

    return $out;
}

function adesk_xml_struct_assoc(&$ary, &$vals, $i, $len, $filter = null) {
    while ($i < $len) {
        $node = $vals[$i];
        $name = $node['tag'];

        if ($node['type'] == 'complete') {
            if (!isset($node['value']))
                $node['value'] = '';
            if ($filter != null)
                $data = $filter(strval($node['value']));
            else
                $data = strval($node['value']);

			$data = adesk_xml_decodecdata($data);

            if (isset($ary[$name])) {
                if (!is_array($ary[$name]))
                    $ary[$name] = array($ary[$name]);
                $ary[$name][] = $data;
            } else {
                $ary[$name] = $data;
            }

            $i++;

            if ($name == '__object_type') {
                $ary['__object'] = new $data;
                foreach ($ary as $key => $val) {
                    if (substr($key, 0, 2) != "__")
                        $ary['__object']->{$key} = $val;
                }
            }
        } elseif ($node['type'] == 'open') {
            $tmp = array();
            $i   = adesk_xml_struct_assoc($tmp, $vals, $i + 1, $len);

            if (isset($ary[$name]) && is_array($ary[$name])) {
                if (isset($ary[$name][0]))
                    $ary[$name][] = $tmp;
                else
                    $ary[$name] = array($ary[$name], $tmp);
            } else {
                $ary[$name] = $tmp;
            }
        } elseif ($node['type'] == 'close') {
            return $i + 1;
        } elseif ($node['type'] == 'cdata') {
            $ary['__cdata'] = strval($node['value']);
            $i++;
        }
    }
}

function adesk_xml_faketag_open($tag) {
    return "<!--".$tag."-->";
}

function adesk_xml_faketag_close($tag) {
    return "<!--/".$tag."-->";
}

function adesk_xml_faketag($tag, $val) {
    return adesk_xml_faketag_open($tag) . $val . adesk_xml_faketag_close($tag);
}

function adesk_xml_faketag_params($tag) {
    $args = func_get_args();
    $out  = "<!--".$tag;

    for ($i = 1; $i < count($args); $i++)
        $out .= "{".$args[$i]."}";

    return $out . "-->";
}

# If what we're passed isn't an array of arrays, make it into one.

function adesk_xml_normalize($ary) {
    if (is_array($ary)) {
        if (isset($ary[0]))
            return $ary;
        else
            return array($ary);
    }

    return $ary;
}

/*
$x = "testing.<hey>..<b something='hi'>one</b> <i>hi<b>two</b></i> <u>three</u>...";

echo "<pre>";
echo (adesk_xml_read_html($x));
echo "</pre>";
 */

?>
