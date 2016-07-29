<?php

function adesk_emailpreview_check($str) {

	$GLOBALS["emailpreview_html_original"] = $str;

	$head_exists = false;
	$body_exists = false;
	$head_style_exists = false;
	$head_link_exists = false;
	$body_style_exists = false;
	$body_link_exists = false;
	$head_style_occurrences = array();
	$head_link_occurrences = array();
	$body_style_occurrences = array();
	$body_link_occurrences = array();

	// pull <head> contents
	preg_match_all("|<head[^>]*>(.*)</head>|iUs", $str, $head);

	// pull <body> contents
	preg_match_all("|<body[^>]*>(.*)</body>|iUs", $str, $body);
	if ( !$body[0] ) {
		$body[0][] = $str;
		$body[1][] = $str;
	}

	// if <head> exists
	if ( isset($head[0]) && count($head[0]) > 0 ) {

		$head_exists = true;

		// match <style> in <head>
		preg_match_all("|(<style[^>]*>)|iUs", $head[1][0], $head_style);

		// match <link> in <head>
		preg_match_all("|(<link[^>]*>)|iUs", $head[1][0], $head_link);

		// if <style> exists in <head>
		if ( isset($head_style[0]) && count($head_style[0]) > 0 ) {

			$head_style_exists = true;

			// pull all css within <style></style>
			preg_match_all("|<style[^>]*>(.*)</style>|iUs", $head[1][0], $head_style_occurrences);
			// $head_style_occurrences[0] is with <style> tags; $head_style_occurrences[1] is without
			$head_style_occurrences = $head_style_occurrences[0];

			// join every array item into one giant string
			// we don't care how many individual <style> blocks there are - it's all one and the same
			$head_style_content = implode(" ", $head_style_occurrences);

			adesk_emailpreview_prepare($head_style_content, "style");
		}

		// if <link> exists in <head>
		if ( isset($head_link[0]) && count($head_link[0]) > 0 ) {

			// loop through all occurrences of <link>
			foreach ($head_link[0] as $link) {

				// if <link rel='stylesheet' /> exists in <head>
				// and href='http...' also exists (absolute URL's only)
				if ( preg_match("|rel=['\"]?stylesheet['\"]?|", $link) && preg_match("|href=['\"]?http|", $link) ) {

					$head_link_exists = true;
					$head_link_occurrences[] = array("element" => $link, "code" => "");
				}
			}

			// reset array of occurrences, after we obtain the actual CSS code. each item will be an array of "element" (<link>) and "code" (actual CSS code)
			// we're also doing the "prepare" stuff here with properties and selectors - recording what we found
			$head_link_occurrences = adesk_emailpreview_prepare_link($head_link_occurrences);

			foreach ($head_link_occurrences as $head_link_occurrence) {
				adesk_emailpreview_prepare($head_link_occurrence["code"], "link");
			}
		}
	}

	// if <body> exists
	if ( isset($body[0]) && count($body[0]) > 0 ) {

		$body_exists = true;

		// match <style> in <body>
		preg_match_all("|(<style[^>]*>)|iUs", $body[1][0], $body_style);

		// match <link> in <body>
		preg_match_all("|(<link[^>]*>)|iUs", $body[1][0], $body_link);

		// if <style> exists in <body>
		if ( isset($body_style[0]) && count($body_style[0]) > 0 ) {

			$body_style_exists = true;

			// pull all css within <style></style>
			preg_match_all("|<style[^>]*>(.*)</style>|iUs", $body[1][0], $body_style_occurrences);

			// $body_style_occurrences[0] is with <style> tags; $body_style_occurrences[1] is without
			$body_style_occurrences = $body_style_occurrences[1];

			// join every array item into one giant string
			// we don't care how many individual <style> blocks there are - it's all one and the same
			$body_style_content = implode(" ", $body_style_occurrences);

			adesk_emailpreview_prepare($body_style_content, "style");
		}

		// if <link> exists in <body>
		if ( isset($body_link[0]) && count($body_link[0]) > 0 ) {

			// loop through all occurrences of <link>
			foreach ($body_link[0] as $link) {

				// if <link rel='stylesheet' /> exists in <body>
				// and href='http...' also exists (absolute URL's only)
				if ( preg_match("|rel=['\"]?stylesheet['\"]?|", $link) && preg_match("|href=['\"]?http|", $link) ) {

					$body_link_exists = true;
					$body_link_occurrences[] = array("element" => $link, "code" => "");
				}
			}

			// reset array of occurrences, after we obtain the actual CSS code. each item will be an array of "element" (<link>) and "code" (actual CSS code)
			// we're also doing the "prepare" stuff here with properties and selectors - recording what we found
			$body_link_occurrences = adesk_emailpreview_prepare_link($body_link_occurrences);

			foreach ($body_link_occurrences as $body_link_occurrence) {
				adesk_emailpreview_prepare($body_link_occurrence["code"], "link");
			}
		}

		// inline styles

		// pull all elements (opening tag only)
	 	preg_match_all("|(<[a-zA-Z]+[^>]*>)|iUs", $str, $doc_elements);

	 	// loop through all elements in <body>
	 	foreach ($doc_elements[1] as $element) {

	 		// look for style="" in element attributes
	 		// this one is tricky. this current test matches anything inside style="", but there has to be a semi-colon at the very end,
	 		// IE: style="background: green" would not work. it has to be style="background: green;"
	 		//preg_match("|style=['\"]?[^'\"]+['\"]?|", $element, $body_element_style);
	 		//preg_match("|style=['\"]?.+;['\"]?|", $element, $body_element_style);
	 		//preg_match("/style=['\"]?.+['\"]?/", $element, $body_element_style);

	 		// pull all element attributes - has to have quotes around the attribute values though, so this would NOT be found: style=background: green;
	 		// if we need to allow attributes without quotes, maybe check for occurrence of any quotes first, and if not, then look for "style="
	 		preg_match_all("/[a-zA-Z]+=['\"]+[^'\"]+['\"]/", $element, $element_attributes);

	 		// get actual element name
	 		// match the first string of a-z characters in an element, ie: <img src="sdasd".. /> would match just "img"
	 		$element_name = preg_match("/[a-zA-Z]+/", $element, $element_name_matches);
			$element_name = $element_name_matches[0];

			$ignore = false;
			if ($element_name == "img") {
			  // ignore images that pertain to open tracking, or analytic stuff
				$link_tracker = ( preg_match("/lt.php/", $element) && preg_match("/&l=open/", $element) );
				$google_tracker = preg_match("/google-analytics.com/", $element);
				if ($link_tracker || $google_tracker) $ignore = true;
			}

			// store element occurrence in global array if it's not an element we are ignoring, such as the link tracker <img> element
			if (!$ignore)
				$GLOBALS["emailpreview_elements"][$element_name]["occurrences"][] = array("element" => $element);

	 		$element_style_attribute = "";

	 		foreach ($element_attributes[0] as $attribute_value) {
	 			// if one of the attributes is style=...
	 			if ( preg_match("/style=/", $attribute_value) ) {
	 				$element_style_attribute = $attribute_value;
	 			}

	 			// for all <a> elements, try to find any with href=#whatever
	 			// we are looking for all anchor links here
	 			if ( $element_name == "a" && preg_match("/href=['\"]+#/", $attribute_value) ) {
          $GLOBALS["emailpreview_elements"][$element_name]["occurrences"][ count($GLOBALS["emailpreview_elements"][$element_name]["occurrences"]) - 1 ]["attribute_href_anchor"] = 1;
	 			}
	 		}

			// if style attribute is present
	 		// $body_element_style looks something like this: style="border: 1px solidasd; background: blue;"
	 		if ($element_style_attribute) {

	 			$quote_exists = preg_match("|['\"]+|", $element_style_attribute);

	 			// style="border: 1px solidasd; background: blue;"
	 			// style='border: 1px solidasd; background: blue;'
	 			if ($quote_exists) {

	 				// pull only css stuff within quotes: border: 1px solidasd; background: blue;
	 				$css = substr($element_style_attribute, 7, strlen($element_style_attribute) - 8 );
	 			}
	 			else {

	 				// style=border: 1px solidasd; background: blue;
					$css = substr($element_style_attribute, 6, strlen($element_style_attribute) - 7 );
	 			}

	 			// break apart each 'property: value' pair from the string
				$doc_element_properties = explode(";", $css);

				// loop through each individual 'property: value;' string, and add to global array
				foreach ($doc_element_properties as $property_value) {

					// if there's only one property/value pair (IE: "display: block;"), it still adds another array item for some reason, so check if it's blank
					// explode does this for some reason
					if ($property_value) {

						$property_value = trim($property_value);

						// store this property/value string in global elements array
						// add to last element that was added further above in "occurrences" key - doing a count() below
						$GLOBALS["emailpreview_elements"][$element_name]["occurrences"][ count($GLOBALS["emailpreview_elements"][$element_name]["occurrences"]) - 1 ]["style_properties_values"][] = $property_value;

						// append to global properties array
						$GLOBALS["emailpreview_style_properties"][] = array( "source" => "inline", "content" => $property_value, "element" => $element_name );
					}
				}
	 		}
	 	}
	}

	//dbg( $GLOBALS["emailpreview_style_properties"] );

	// if neither <head> nor <body> exists
	if (!$head_exists && !$body_exists) {

		// considered <body>
		// it's considered <body> though - if there's no <head> and <body> explicitly designated, then any content is <body>

		// match <style> anywhere - if <head> and <body> do not exist, <style> is just floating anywhere.
		preg_match_all("|(<style[^>]*>)|iUs", $str, $body_style);

		// if <style> exists
		if ( isset($body_style[0]) && count($body_style[0]) > 0 ) {

			$body_style_exists = true;

			// pull all css within <style></style>
			preg_match_all("|<style[^>]*>(.*)</style>|iUs", $str, $body_style_occurrences);

			// $anywhere_style_occurrences[0] is with <style> tags; $anywhere_style_occurrences[1] is without
			$body_style_occurrences = $body_style_occurrences[0];

			// join every array item into one giant string
			// we don't care how many individual <style> blocks there are - it's all one and the same
			$body_style_content = implode(" ", $body_style_occurrences);

			adesk_emailpreview_prepare($body_style_content, "style");
		}
	}
	elseif ($head_exists && !$body_exists) {

		// considered <body>
		// <head> exists, but <body> does not. we need to capture <style> blocks that do not reside in <head>

		// replace <head> with nothing. we've already processed it above, so we just need to look at everything except <head>
		// $head is empty array, so nothing was matched; if anything, remove empty <head> tags
		$str_modified = preg_replace("|<head[^>]*></head>|iUs", "", $str);

		preg_match_all("|(<style[^>]*>)|iUs", $str_modified, $body_style);

		// if <style> exists
		if ( isset($body_style[0]) && count($body_style[0]) > 0 ) {

			$body_style_exists = true;

			// pull all css within <style></style>
			preg_match_all("|<style[^>]*>(.*)</style>|iUs", $str, $body_style_occurrences);

			// $anywhere_style_occurrences[0] is with <style> tags; $anywhere_style_occurrences[1] is without
			$body_style_occurrences = $body_style_occurrences[0];

			// join every array item into one giant string
			// we don't care how many individual <style> blocks there are - it's all one and the same
			$body_style_content = implode(" ", $body_style_occurrences);

			adesk_emailpreview_prepare($body_style_content, "style");
		}
	}
	elseif (!$head_exists && $body_exists) {
		// <head> does not exist, but <body> does
		// would this scenario ever occur?
	}

 	// selector occurrences
 	// $GLOBALS["emailpreview_style_selectors"] is a string of CSS (selectors, properties - anything within block CSS <style> tags)
	// all selectors from block (including <link>) and inline should be here
	// this function does not return anything - it just changes the global array values
	if ($GLOBALS["emailpreview_style_selectors"]) adesk_emailpreview_check_selector($GLOBALS["emailpreview_style_selectors"]);

	// property occurrences
	// $GLOBALS["style_properties"] is an array of individual properties, along with their values.
	// we pass the entire property/value pair as a single string - all we care about is detecting what property is being used
	// all properties from block (including <link>) and inline should be here
	// this function does not return anything - it just changes the global array values
	if ($GLOBALS["emailpreview_style_properties"]) adesk_emailpreview_check_property($GLOBALS["emailpreview_style_properties"]);

	// location occurrences
	$GLOBALS["emailpreview_locations"]["head"]["style"]["exists"] = $head_style_exists;
	$GLOBALS["emailpreview_locations"]["head"]["style"]["occurrences"] = $head_style_occurrences;
	$GLOBALS["emailpreview_locations"]["head"]["link"]["exists"] = $head_link_exists;
	$GLOBALS["emailpreview_locations"]["head"]["link"]["occurrences"] = $head_link_occurrences;
	$GLOBALS["emailpreview_locations"]["body"]["style"]["exists"] = $body_style_exists;
	$GLOBALS["emailpreview_locations"]["body"]["style"]["occurrences"] = $body_style_occurrences;
	$GLOBALS["emailpreview_locations"]["body"]["link"]["exists"] = $body_link_exists;
	$GLOBALS["emailpreview_locations"]["body"]["link"]["occurrences"] = $body_link_occurrences;

	adesk_emailpreview_client_prepare();
}

function adesk_emailpreview_prepare_link($link_occurrences) {

	$r = array();

	foreach ($link_occurrences as $link_occurrence) {

		$result = array();

		// look for href="" as an attribute
		preg_match("|href=['\"]?[^\s]+['\"]?\s|", $link_occurrence["element"], $link_element_href);

		if ($link_element_href) {

			// there will be a trailing space since we match that so we don't pull additional attributes above
			$link_element_href = trim($link_element_href[0]);

			$quote_exists = preg_match("|['\"]+|", $link_element_href);

			// href="url"
			// href='url'
			if ($quote_exists) {

				// pull only href (url) within the quotes
				$href = substr($link_element_href, 6, strlen($link_element_href) - 7 );
			}
			else {

				// href=url
				$href = substr($link_element_href, 5, strlen($link_element_href) - 6 );
			}

			$href_content = adesk_http_get($href);
			$result["element"] = $link_occurrence["element"];
			$result["code"] = "";
			$result["code"] .= "<style>\n/* START retrieved from " . $link_occurrence["element"] . " */\n\n";
			$result["code"] .= $href_content;
			$result["code"] .= "\n\n/* END retrieved from " . $link_occurrence["element"] . " */\n</style>\n\n";

			$r[] = $result;
		}
	}

	return $r;
}

// takes raw css block content (selectors and properties from <style>), cleans up the string, and appends them to global string/array for selectors and properties
function adesk_emailpreview_prepare($css_content, $source) {

	// remove CSS comments - the comment opening and closing tags, as well everything in between
	$css_content = preg_replace("|/\*[^\*/]*\*/|", "", $css_content);

	// remove HTML comments - the comment opening and closing tags, as well everything in between
	$css_content = preg_replace("/<!--(.|\s)*?-->/", "", $css_content);

	// remove excess whitespace from within the string
	$css_content = preg_replace("/\s+/", " ", $css_content);

	// remove whitespace from beginning and end of string
	$css_content = trim($css_content);

	// append css to global string for selector check
	$GLOBALS["emailpreview_style_selectors"][] = array( "source" => $source, "content" => $css_content );

	// grab all properties/values from the css
	// finds content between { and }
	preg_match_all("|{\s?(.*)\s?}|iUs", $css_content, $css_properties);

	foreach ($css_properties[1] as $property_string) {

		// could be more than one property per string: "border: 1px solid black; text-align: right;"
		$properties = explode(";", $property_string);

		foreach ($properties as $property_value) {

			// append to global array
			$GLOBALS["emailpreview_style_properties"][] = array( "source" => $source, "content" => $property_value );
		}
	}
}

// $css is an array with items containing full css code: selectors, properties, and values - in block form ({}), truncated to remove excess inner white space
function adesk_emailpreview_check_selector($css) {

	// $css = array( 0 => array("source" => "external", "content" => "<style> css code..... </style>") );

	$css_source = "";

	foreach ($css as $source_content) {
		// combine all sources of content into single string
		$css_source .= $source_content["content"];
	}

	$selectors_all = array();

	// get all selectors between "}" and "{"
	$selectors_match = preg_match_all("|[>}/]\s?[a-zA-Z0-9#\.,:\s]+{|", $css_source, $css_selectors);

	foreach ($css_selectors[0] as $selectors) {

		// remove any preceding "}" or ">", and any trailing "{"
		// "> div {" - coming after the opening <style> tag: "<style>div {.."
		// "}input{" - coming after another CSS block: "{ ... } input {.."
		// "/ input{" - coming after a comment: " ... */ input{.."
		//$selectors = preg_replace("/>|{|}|\s/", "", $selectors);
		//$selectors = preg_replace("/(>\s?)|{|}/", "", $selectors);
		$selectors = preg_replace("/\/|>|{|}/", "", $selectors);

		// split up comma-separated selectors
		$selectors = explode(",", $selectors);
		foreach ($selectors as $selector) {
			$selectors_all[] = $selector;
		}
	}

	$selectors_all = array_map("trim", $selectors_all);

	// reverse sort so we catch larger strings (nested styles pertaining to same element or selector) first:
	// IE: "#myid table" would be replaced before "#myid", if these two were both standalone selectors
	// if we replaced "#myid" before "#myid table", "#myid table" would become "__REPLACED__ table" in the modified HTML, so it would never find "#myid table"
	rsort($selectors_all);
	//dbg($selectors_all);

	// check for matches
	foreach ($selectors_all as $selector) {

		if ($selector == "*") {
			$GLOBALS["emailpreview_selectors"]["*"]["exists"][ $css[0]["source"] ] = 1;
			$GLOBALS["emailpreview_selectors"]["*"]["occurrences"][ $css[0]["source"] ][] = $selector;
		}
		elseif ( preg_match("/[a-zA-Z]*[.]+[a-zA-Z0-9]+/", $selector) ) {
			$GLOBALS["emailpreview_selectors"]["e.className"]["exists"][ $css[0]["source"] ] = 1;
			$GLOBALS["emailpreview_selectors"]["e.className"]["occurrences"][ $css[0]["source"] ][] = $selector;
		}
		elseif ( preg_match("/[a-zA-Z]*#+[a-zA-Z0-9]+/", $selector) ) {
			$GLOBALS["emailpreview_selectors"]["e#id"]["exists"][ $css[0]["source"] ] = 1;
			$GLOBALS["emailpreview_selectors"]["e#id"]["occurrences"][ $css[0]["source"] ][] = $selector;
		}
		elseif ( preg_match("/[a-zA-Z]*:link/", $selector) ) {
			$GLOBALS["emailpreview_selectors"]["e:link"]["exists"][ $css[0]["source"] ] = 1;
			$GLOBALS["emailpreview_selectors"]["e:link"]["occurrences"][ $css[0]["source"] ][] = $selector;
		}
		elseif ( preg_match("/[a-zA-Z]*:hover/", $selector) ) {
			$GLOBALS["emailpreview_selectors"]["e:hover"]["exists"][ $css[0]["source"] ] = 1;
			$GLOBALS["emailpreview_selectors"]["e:hover"]["occurrences"][ $css[0]["source"] ][] = $selector;
		}
		elseif ( preg_match("/[a-zA-Z]*:active/", $selector) ) {
			$GLOBALS["emailpreview_selectors"]["e:active"]["exists"][ $css[0]["source"] ] = 1;
			$GLOBALS["emailpreview_selectors"]["e:active"]["occurrences"][ $css[0]["source"] ][] = $selector;
		}
		elseif ( preg_match("/[a-zA-Z]*:first-line/", $selector) ) {
			$GLOBALS["emailpreview_selectors"]["e:first-line"]["exists"][ $css[0]["source"] ] = 1;
			$GLOBALS["emailpreview_selectors"]["e:first-line"]["occurrences"][ $css[0]["source"] ][] = $selector;
		}
		elseif ( preg_match("/[a-zA-Z]*:first-letter/", $selector) ) {
			$GLOBALS["emailpreview_selectors"]["e:first-letter"]["exists"][ $css[0]["source"] ] = 1;
			$GLOBALS["emailpreview_selectors"]["e:first-letter"]["occurrences"][ $css[0]["source"] ][] = $selector;
		}
		elseif ( preg_match("/[a-zA-Z0-9]+\s?>\s?[a-zA-Z0-9]+/", $selector) ) {
			$GLOBALS["emailpreview_selectors"]["e > f"]["exists"][ $css[0]["source"] ] = 1;
			$GLOBALS["emailpreview_selectors"]["e > f"]["occurrences"][ $css[0]["source"] ][] = $selector;
		}
		elseif ( preg_match("/[a-zA-Z]*:focus/", $selector) ) {
			$GLOBALS["emailpreview_selectors"]["e:focus"]["exists"][ $css[0]["source"] ] = 1;
			$GLOBALS["emailpreview_selectors"]["e:focus"]["occurrences"][ $css[0]["source"] ][] = $selector;
		}
		elseif ( preg_match("/[a-zA-Z0-9]+\s?\+\s?[a-zA-Z0-9]+/", $selector) ) {
			$GLOBALS["emailpreview_selectors"]["e + f"]["exists"][ $css[0]["source"] ] = 1;
			$GLOBALS["emailpreview_selectors"]["e + f"]["occurrences"][ $css[0]["source"] ][] = $selector;
		}
		elseif ( preg_match("/[a-zA-Z]+\[[a-zA-Z]+\]/", $selector) ) {
			$GLOBALS["emailpreview_selectors"]["e[foo]"]["exists"][ $css[0]["source"] ] = 1;
			$GLOBALS["emailpreview_selectors"]["e[foo]"]["occurrences"][ $css[0]["source"] ][] = $selector;
		}
		else {
			// matches nothing above - it must be an element selector, right?
			$GLOBALS["emailpreview_selectors"]["e"]["exists"][ $css[0]["source"] ] = 1;
			$GLOBALS["emailpreview_selectors"]["e"]["occurrences"][ $css[0]["source"] ][] = $selector;
		}
	}
}

// $css is an array of each individual property/value found amongst all selector blocks
// we don't care what selector the property pertains to - it's either supported as is, or not
function adesk_emailpreview_check_property($css) {

	//dbg("_check_property",1);
	//dbg($css);

	// $css = array( 0 => array("source" => "style", "content" => "margin: 0px", "element" => "img"), 1 => .... );

	// $line is the entire property/value line, IE: "background-color: green" OR "display: block"
	foreach ($css as $source_content) {

		$line = trim($source_content["content"]);

		// make sure it's not a blank line
		if ($line) {

			// separate properties from values
			$property_value = explode(":", $line);

			// remove white space from beginning and end
			$property_value = array_map("trim", $property_value);

			// if this property exists as an array key in $properties
			// (it could be a property that all clients support, so it's not even included as something to check against, so we have to make sure)
			if ( isset($GLOBALS["emailpreview_properties"][ $property_value[0] ]) ) {

				// increment total
				$GLOBALS["emailpreview_properties"][ $property_value[0] ]["total"][ $source_content["source"] ]++;

				// store occurrence
				if ($source_content["source"] == "inline") {
					// if the source is inline, include the element name in the global array
					// used for when certain elements can/can't/should/shouldn't have specific CSS properties applied to them
					$item = array("element" => $source_content["element"], "content" => $line);
				}
				else {
					$item = $line;
				}

				$GLOBALS["emailpreview_properties"][ $property_value[0] ]["occurrences"][ $source_content["source"] ][] = $item;
			}
		}
	}
}

// loops through each client from the $clients array, and appends to the "html_result" array key the total number of issues found in the html
// we then look at that array in the template file to display how many issues
function adesk_emailpreview_client_prepare() {

	// loop through clients array
	foreach ($GLOBALS["emailpreview_clients"] as $k => $v) {

		$head_link_supported = true;
		$body_link_supported = true;

		// make copy of original html
		$html_modified = $GLOBALS["emailpreview_html_original"];

		// if not in "whitelist" array, move to next item
		if (!in_array($k, $GLOBALS["emailpreview_clients2check"]))
			continue;

		// if "exists" (1 or 0) is greater than "support" (1 or 0), then support for that instance FAILS, so we increment "issuescnt" var
		// and add new occurrence to global array for that client

		// <link> in <head> not supported
		if ($GLOBALS["emailpreview_locations"]["head"]["link"]["exists"] > $v["css_support"]["head"]["link"]) {

			$head_link_supported = false;

			foreach ($GLOBALS["emailpreview_locations"]["head"]["link"]["occurrences"] as $occurrence) {

				$GLOBALS["emailpreview_clients"][$k]["html_result"]["issuescnt"]["link"]++;
				$GLOBALS["emailpreview_clients"][$k]["html_result"]["issues"]["locations"]["head"]["link"]["occurrences"][] = array( "code" => $occurrence["element"], "display" => adesk_str_htmlspecialchars($occurrence["element"]) );

				// replace the <link> element with the actual CSS retrieved from that href - this way we can replace selectors and properties
				$html_modified = str_replace($occurrence["element"], $occurrence["code"], $html_modified);

				// check support for <style> in <head>, since we just added it there.
				// if not supported, remove the css that we just added to <head>
				if (!$v["css_support"]["head"]["style"]) {
					$html_modified = str_replace($occurrence["code"], "", $html_modified);
				}
			}
		}
		else {

		}

		// <link> in <body> not supported
		if ($GLOBALS["emailpreview_locations"]["body"]["link"]["exists"] > $v["css_support"]["body"]["link"]) {

			$body_link_supported = false;

			foreach ($GLOBALS["emailpreview_locations"]["body"]["link"]["occurrences"] as $occurrence) {

				$GLOBALS["emailpreview_clients"][$k]["html_result"]["issuescnt"]["link"]++;
				$GLOBALS["emailpreview_clients"][$k]["html_result"]["issues"]["locations"]["body"]["link"]["occurrences"][] = array( "code" => $occurrence["element"], "display" => adesk_str_htmlspecialchars($occurrence["element"]) );

				// replace the <link> element with the actual CSS retrieved from that href - this way we can replace selectors and properties
				$html_modified = str_replace($occurrence["element"], $occurrence["code"], $html_modified);

				// check support for <style> in <body>, since we just added it there.
				// if not supported, remove the css that we just added to <body>
				if (!$v["css_support"]["body"]["style"]) {
					$html_modified = str_replace($occurrence["code"], "", $html_modified);
				}
			}
		}
		else {

		}

		// <style> in <head> not supported
		if ($GLOBALS["emailpreview_locations"]["head"]["style"]["exists"] > $v["css_support"]["head"]["style"]) {

			foreach ($GLOBALS["emailpreview_locations"]["head"]["style"]["occurrences"] as $occurrence) {
				$GLOBALS["emailpreview_clients"][$k]["html_result"]["issuescnt"]["style"]++;
				$GLOBALS["emailpreview_clients"][$k]["html_result"]["issues"]["locations"]["head"]["style"]["occurrences"][] = array( "code" => $occurrence, "display" => adesk_str_htmlspecialchars($occurrence) );
				$html_modified = str_replace($occurrence, "", $html_modified);
			}
		}
		else {

			// <style> in <head> IS supported
		}

		// <style> in <body> not supported
		if ($GLOBALS["emailpreview_locations"]["body"]["style"]["exists"] > $v["css_support"]["body"]["style"]) {

			foreach ($GLOBALS["emailpreview_locations"]["body"]["style"]["occurrences"] as $occurrence) {
				$GLOBALS["emailpreview_clients"][$k]["html_result"]["issuescnt"]["style"]++;
				$GLOBALS["emailpreview_clients"][$k]["html_result"]["issues"]["locations"]["body"]["style"]["occurrences"][] = array( "code" => $occurrence, "display" => adesk_str_htmlspecialchars($occurrence) );
				$html_modified = str_replace($occurrence, "", $html_modified);
			}
		}
		else {

			// <style> in <body> IS supported
		}

//dbg($GLOBALS["emailpreview_selectors"]);

		// selector occurrences
		foreach ($GLOBALS["emailpreview_selectors"] as $selector => $exists_occurrences) {

			// total from each source (style, inline, link)
			$selector_source_exists_total = count($exists_occurrences["occurrences"]["style"]) + count($exists_occurrences["occurrences"]["inline"]) + count($exists_occurrences["occurrences"]["link"]);

			// if the grand total is greater than support for this particular selector in the client...
			if ($selector_source_exists_total	> $v["css_support"]["selectors"][$selector]) {

				foreach ($exists_occurrences["occurrences"] as $source => $occurrences) {

					foreach ($occurrences as $occurrence) {

						// if the source is "link" (<link>), but it's not supported in <head> or <body>, skip recording each issue (we don't want to display each issue)
						// this will have to be fixed if we decide to someday display a client that supports EITHER/OR. right now if we see that <link> is
						// not supported in EITHER <head> or <body>, we don't record issues to display to user. ideally you should record the issues from <head>
						// or <body>, depending on which one is supported
						if ($source == "link" && (!$body_link_supported || !$head_link_supported) )
							continue;

						$GLOBALS["emailpreview_clients"][$k]["html_result"]["issuescnt"][$source]++;
						$GLOBALS["emailpreview_clients"][$k]["html_result"]["issues"]["selectors"][$selector]["occurrences"][$source][] = $occurrence;

						// if it's an element selector, we can't replace throughout the entire html, since it will find REAL elements and replace those
						if ($selector == "e") {

							// pull all <style> blocks in the document
							preg_match_all("|<style[^>]*>(.*)</style>|iUs", $html_modified, $html_style_occurrences);

							foreach ($html_style_occurrences[0] as $block) {
								// replace occurrences within each <style> block, then substitute the modified <style> block into the html
								$block_modified = str_replace($occurrence, "__REPLACED__", $block);
								$html_modified = str_replace($block, $block_modified, $html_modified);
							}
						}
						else {
							//$html_modified = str_replace($occurrence, "__REPLACED__", $html_modified);
						}
					}
				}
			}
		}

//dbg($GLOBALS["emailpreview_properties"]);

		// property occurrences
		foreach ($GLOBALS["emailpreview_properties"] as $property => $total_occurrences) {

			// check for shorthand first - we have to break apart the shorthand values and convert them to their full property name
			if ($property == "background") {

				// check if there are any "background" occurrences
				$background_source_total = $GLOBALS["emailpreview_properties"]["background"]["total"]["style"] + $GLOBALS["emailpreview_properties"]["background"]["total"]["inline"] + $GLOBALS["emailpreview_properties"]["background"]["total"]["link"];
				$background_occurrence_total = ($background_source_total > 0);
				//dbg($k . ": " . $background_occurrence_total,1);

				// check whether client supports "background"
				//dbg($k . ": " . $v["css_support"]["properties"]["background"],1);

				// if there are "background" occurrences, and it's not supported for this client, add to issues count, and add occurrence for display.
				// for this case, we don't care what each individual shorthand property (within "background: ...") is, because if "background" is not
				// supported, the whole line essentially does not exist.
				if ($background_occurrence_total > $v["css_support"]["properties"]["background"]) {

					//dbg($k,1);
					//dbg($total_occurrences["occurrences"],1);

					// loop through each "background: ..." occurrence
					foreach ($total_occurrences["occurrences"] as $source => $occurrences) {

						foreach ($occurrences as $occurrence) {

							// same test we do above for selectors. this is NOT a solution, and could cause problems down the line
							if ($source == "link" && (!$body_link_supported || !$head_link_supported) )
								continue;

							$GLOBALS["emailpreview_clients"][$k]["html_result"]["issuescnt"][$source]++;
							$GLOBALS["emailpreview_clients"][$k]["html_result"]["issues"]["properties"]["background"]["occurrences"][$source][] = $occurrence;
							$html_modified = str_replace($occurrence . ";", "", $html_modified);
						}
					}
				}
				else {

					// otherwise, "background" IS supported for this client, so we need to break apart the individual shorthand properties,
					// and figure out which of those is NOT supported, so we can adjust the modified HTML

					// loop through each source, which contains an array of occurrences
					foreach ($total_occurrences["occurrences"] as $source => $occurrences) {

						foreach ($occurrences as $occurrence) {

							// "inline" is an array, whereas the others are not
							if ($source == "inline") $occurrence = $occurrence["content"];

							// same test we do above for selectors. this is NOT a solution, and could cause problems down the line
							if ($source == "link" && (!$body_link_supported || !$head_link_supported) )
								continue;

							$property_value_shorthand = array();

							// separate properties from values
							// find the first semi-colon in the string (we can't explode because it might break up other semi-colons in the value portion)
							$first_semicolon = strpos($occurrence, ":");
							// find the portion BEFORE the first semi-colon. this is the property
							$property = substr($occurrence, 0, $first_semicolon);
							// grab everything after the first semi-colon (we can assume that this is the value)
							$value = substr($occurrence, $first_semicolon + 1);
							$property_value = array($property, $value);

							// remove white space from beginning and end
							$property_value = array_map("trim", $property_value);

							$background_properties = array("color" => 0, "image" => 0, "repeat" => 0, "attachment" => 0, "position" => array());
							$background_properties_position = array();
							// explode to break apart values separated with a space, IE: background: url(...) no-repeat top left
							$values = explode(" ", $property_value[1]);

							foreach ($values as $value) {

								if ( preg_match("|^(#[a-fA-F0-9]{3,6})|", $value) ) {
									// #ccc or #cccccc
									$property_value_shorthand[] = array("background-color", $value);
									$background_properties["color"] = 1;
								}
								elseif ($value == "transparent") {
									$property_value_shorthand[] = array("background-color", $value);
									$background_properties["color"] = 1;
								}
								elseif ( preg_match("|url(['\"]?.['\"]?)|", $value) ) {
									// "url(...)"
									$property_value_shorthand[] = array("background-image", $value);
									$background_properties["image"] = 1;
								}
								elseif ( preg_match("|^(no-)?repeat|", $value) ) {
									// "no-repeat" or "repeat" at the beginning of string
									$property_value_shorthand[] = array("background-repeat", $value);
									$background_properties["repeat"] = 1;
								}
								elseif ( preg_match("/scroll|fixed|inherit/", $value) ) {
									// "scroll", "fixed", or "inherit" at the beginning of the string
									$property_value_shorthand[] = array("background-attachment", $value);
									$background_properties["attachment"] = 1;
								}
								elseif ( preg_match("/top|center|bottom|left|right|[0-9]+|%$/", $value) ) {
									$background_properties["position"][] = 1;
									$background_properties_position[] = $value;
								}
								elseif ($value != "inherit") {
									// last man standing (but not "inherit") has to be a color name, right?
									$property_value_shorthand[] = array("background-color", $value);
									$background_properties["color"] = 1;
								}
								else {
									// "inherit"
									// find first 0 after the last 1 in $background_properties
									/*
									$background_properties_ones = array_search(1, $background_properties);
									$background_properties_zeros = array_search(0, $background_properties);
									$background_properties_last_1_key = $background_properties_ones[ count($background_properties_ones) - 1 ];
									$background_properties_first_0_key = $background_properties_zeros[0];
									*/

									// we're doing nothing right now for "inherit"
									// it's a mess to figure out - I'm not even sure you can use "inherit" within the "background" property declaration
									// the problem is we don't know which long-hand property "inherit" refers to, if declared in the short-hand "background" property,
									// since all long-hand background properties can accept "inherit"
								}
							}

							//dbg($background_properties,1);

							// if x or y position is set - or both
							if ($background_properties_position) {
								$property_value_shorthand[] = array( "background-position", implode(" ", $background_properties_position) );
							}

							//dbg($k,1);
							//dbg($property_value_shorthand,1);

							// make copy so we can still find/replace the original
							$occurrence_modified = $occurrence;

							// loop through all longhand property/value pairs (that were converted from shorthand)
							foreach ($property_value_shorthand as $property_value2) {

								// make sure it's a property we test against
								if ( isset($GLOBALS["emailpreview_properties"][ $property_value2[0] ]) ) {

									// add new longhand property to globals array total
									$GLOBALS["emailpreview_properties"][ $property_value2[0] ]["total"][$source]++;

									// we just added one to this array, so we know there's at least one. so check if 1 (meaning 'true' here) is greater than (0 or 1).
									// if this property is not supported in the client
									if (1 > $v["css_support"]["properties"][ $property_value2[0] ]) {
										$GLOBALS["emailpreview_clients"][$k]["html_result"]["issuescnt"][$source]++;

										// $occurrence is the full line: "background: ..." - this is just what we show to the user, so they know the exact code
										$GLOBALS["emailpreview_clients"][$k]["html_result"]["issues"]["properties"][ $property_value2[0] ]["occurrences"][$source][] = array("content" => $occurrence);

										// replace the value in "background: ..." with nothing
										// for example - background: url(...)
										// would become - background-image: url(...)
										//$occurrence_modified = str_replace($property_value2[1], "", $occurrence_modified);
										$occurrence_modified = $property_value2[0] . ": " . $property_value2[1];
									}
								}
							}

							//dbg($k,1);

							// replace the original "background: ..." line with the modified version
							$html_modified = str_replace($occurrence . ";", $occurrence_modified . ";", $html_modified);
						}
					}
				}
			}
			else {

				// not "background" or "font" shorthand properties

				// just check if it's greater than 0 - we don't care exactly how many occurrences there are.
				// compare that to whether this particular property is supported for the client (1 or 0)
				// we'll add each individual occurrence separately
				$property_source_total = $GLOBALS["emailpreview_properties"][$property]["total"]["style"] + $GLOBALS["emailpreview_properties"][$property]["total"]["inline"] + $GLOBALS["emailpreview_properties"][$property]["total"]["link"];
				$property_occurrence_total = ($property_source_total > 0);

				// if occurrence total is GREATER THAN 0 (0 means this property is NOT supported), so this is a quick check to see if invalid properties are present
				if ($property_occurrence_total > $v["css_support"]["properties"][$property]) {

					foreach ($total_occurrences["occurrences"] as $source => $occurrences) {

						foreach ($occurrences as $occurrence) {
							$GLOBALS["emailpreview_clients"][$k]["html_result"]["issuescnt"][$source]++;
							$GLOBALS["emailpreview_clients"][$k]["html_result"]["issues"]["properties"][$property]["occurrences"][$source][] = $occurrence;
							$html_modified = str_replace($occurrence["content"] . ";", "", $html_modified);
						}
					}
				}
			}

			// final stuff that you need to do to each - loop through them all once more
			foreach($GLOBALS["emailpreview_clients"][$k]["html_result"]["issues"] as $k4 => $v4) {

				if ($k4 == "selectors" || $k4 == "properties") {

					// loop through each individual selector or property
					foreach ($v4 as $item_key => $item) {

						$item_occurrences_total = 0;

						foreach ($item["occurrences"] as $source => $source_occurrences) {
							$item_occurrences_total += count($source_occurrences);
						}

						//$item_occurrences_total = count($item["occurrences"]["style"]) + count($item["occurrences"]["inline"]) + count($item["occurrences"]["link"]);
						$GLOBALS["emailpreview_clients"][$k]["html_result"]["issues"][$k4][$item_key]["occurrences"]["total"] = $item_occurrences_total;

						// can't figure out why ["total"] is always 1 extra than it should be, so I subtract 1 here
						$GLOBALS["emailpreview_clients"][$k]["html_result"]["issues"][$k4][$item_key]["occurrences"]["total"]--;
					}
				}

				// re-sort occurrences in ABC order, for display purposes
				//sort($GLOBALS["emailpreview_clients"][$k]["html_result"]["issues"]["selectors"][$k4]["occurrences"]);
			}
		}

		//dbg( $GLOBALS["emailpreview_elements"] );

		// loop through all HTML elements for any final checks
		foreach ($GLOBALS["emailpreview_elements"] as $element => $info) {

		  // HTML support - any elements that have limitations/requirements/idiosyncrasies
		  if ( isset($GLOBALS["emailpreview_clients"][$k]["html_support"]["elements"][$element]) ) {
			  // example:  "a" => array( "attribute_href_anchor" => 0 )
			  // above, attribute_href_anchor is the validation/check we've already performed, and now we just see if it's 1 or 0
        foreach ( $GLOBALS["emailpreview_clients"][$k]["html_support"]["elements"][$element] as $check => $supported ) {
          // loop through occurrences of this HTML element
          foreach ($info["occurrences"] as $occurrence) {
            if ( isset($occurrence[$check]) && $occurrence[$check] != $supported ) {
              // there's an occurrence of $check in this HTML element, and it's marked opposite of it's support flag value
              // need to add this to main output array, so template can display alert
            }
          }
        }
		  }

			// if the element exists in the global clients array, for CSS requirements
			if ( isset($GLOBALS["emailpreview_clients"][$k]["css_requirements"]["elements"][$element]) ) {

				//dbg($info);

				// loop through each property/value pair as declared in the array, ie: array("display" => "block")
				foreach ($GLOBALS["emailpreview_clients"][$k]["css_requirements"]["elements"][$element] as $property => $value) {

					// form the CSS syntax: array("display" => "block") becomes "display: block", because that's how each occurrence appears
					$property_value_required = $property . ": " . $value;

					// declaring this here since it seems to need this prior to updating it further down
					$GLOBALS["emailpreview_clients"][$k]["html_result"]["issues"]["elements"][$element][$property_value_required] = array( "total" => 0, "occurrences" => array() );

					// loop through each occurrence of the element in the document
					foreach ($info["occurrences"] as $occurrence) {

						if ( isset($occurrence["style_properties_values"]) ) {

							// finally, loop through each individual style property/value pair that is declared in the inline "style" attribute for the occurrence of the element
							foreach ($occurrence["style_properties_values"] as $property_value) {

								// if the required property/value is part of this elements' style attribute
								if ($property_value == $property_value_required) {

									// nothing needed here - the required stuff is there for this element
								}
								else {

									// "style" attribute is there, but the required property/value pair is not

									// update "html_result" global array item
									$GLOBALS["emailpreview_clients"][$k]["html_result"]["issuescnt"]["inline"]++;
									$GLOBALS["emailpreview_clients"][$k]["html_result"]["issues"]["elements"][$element][$property_value_required]["total"]++;
									$GLOBALS["emailpreview_clients"][$k]["html_result"]["issues"]["elements"][$element][$property_value_required]["occurrences"][] = adesk_str_htmlspecialchars($occurrence);
								}
							}
						}
						else {

							// elements that do not have the "style" attribute at all, but a required inline property/value needed

							// update "html_result" global array item
							$GLOBALS["emailpreview_clients"][$k]["html_result"]["issuescnt"]["inline"]++;
							$GLOBALS["emailpreview_clients"][$k]["html_result"]["issues"]["elements"][$element][$property_value_required]["total"]++;
							$GLOBALS["emailpreview_clients"][$k]["html_result"]["issues"]["elements"][$element][$property_value_required]["occurrences"][] = adesk_str_htmlspecialchars($occurrence);
						}
					}
				}
			}
		}

		$GLOBALS["emailpreview_clients"][$k]["html_result"]["html_modified"] = $html_modified;

		//dbg($k,1);
		//dbg($GLOBALS["emailpreview_clients"][$k]["html_result"],1);
	}
}

function adesk_emailpreview_share_email() {

	require_once(awebdesk_functions('mail.php'));
	require_once(awebdesk("scripts/emailawebview.php"));

	$email_to = adesk_http_param("emailpreview_message_email");
	if ( !adesk_str_is_email($email_to) ) return adesk_ajax_api_result(false, _a("Email is not valid."), array('email' => $email_to));

	$GLOBALS["emailpreview_clients2check"] = explode(',', (string)adesk_http_param("clients2check"));
	$html_original = adesk_http_param("html_original");

	adesk_emailpreview_check($html_original);

	require_once(awebdesk_functions('smarty.php'));
	$smarty = new adesk_Smarty('admin', true);

	$smarty->assign("clients2check", $GLOBALS["emailpreview_clients2check"]);
	$smarty->assign("clients", $GLOBALS["emailpreview_clients"]);
	$smarty->assign("locations", $GLOBALS["emailpreview_locations"]);
	$smarty->assign("selectors", $GLOBALS["emailpreview_selectors"]);
	$smarty->assign("properties", $GLOBALS["emailpreview_properties"]);
	$text = $smarty->fetch("emailpreview2.htm");

	adesk_mail_send("html", $GLOBALS["site"]["site_name"], $GLOBALS["site"]["emfrom"], $text, _a("Email Marketing Inbox Preview"), $email_to);

	return adesk_ajax_api_result(true, _a("Email Sent"), array('email' => $email_to));
}

function adesk_emailpreview_sendfeedback() {

	require_once(awebdesk_functions('mail.php'));

	$clients = adesk_str_strip_tags((string)adesk_http_param('clients'));
	$message = adesk_str_strip_tags((string)adesk_http_param('message'));
	$content = trim((string)adesk_http_param('content'));

	// checks
	if ( !$clients or !$message or !$content ) {
		return adesk_ajax_api_result(false, _a("Feedback information not provided."));
	}

	$toemail = 'inboxpreview@awebdesk.com';
	$subject = "Inbox Preview Feedback";
	$body    = "CLIENTS:\n$clients\n\nMESSAGE:\n$message";

	// send an email
	$options = array(
		'attach' => array(
			array(
				'name' => 'email.htm',
				'data' => $content,
				'mime_type' => 'application/octet-stream'

			)
		)
	);
	adesk_mail_send("text", $GLOBALS["site"]["site_name"], $GLOBALS["site"]["emfrom"], $body, $subject, $toemail, '', $options);

	return adesk_ajax_api_result(true, _a("Email Sent"));
}

?>