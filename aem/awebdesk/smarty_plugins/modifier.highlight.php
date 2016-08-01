<?php
/*
* Smarty plugin
* -------------------------------------------------------------
* Type: modifier
* Name: highlight
* Version: 0.5
* Date: 03-27
* Author: Pavel Prishivalko, aloner#telephone.ru
* Purpose: Highlight search term in text
* Install: Drop into the plugin directory
*
* Extended To 0.5 By: Alexey Kulikov <alex@pvl.at>
* Strips Tags for nice output, allows multiple term for highlight
* Modified and simplified to high light b2 searches by Donncha O Caoimh
* Added google search highlight using code from http://www.textism.com/tools/google_hilite/
* -------------------------------------------------------------
*/

function smarty_modifier_highlight($text, $terms = array(), $tag = '<b style="color: #000; background-color: #%s;">%s</b>') {

	$orig = $text;
	$colors = array('ff0', '0ff', 'f0f');
	$i = 0;

        if (false) {
	// try to find GoogleSearch if terms are not provided
	if ( count($terms) == 0 and isset($_SERVER['HTTP_REFERER']) ) {
		$ref = urldecode($_SERVER['HTTP_REFERER']);
		// let's see if the referrer is google
		if ( preg_match('/^http:\/\/w?w?w?\.?google.*/i', $ref) ) {
			// if so, tweezer out the search query
			$query = preg_replace('/^.*q=([^&]+)&?.*$/i', '$1', $ref);
			// scrub away nasty quote marks
			$query = preg_replace('/\'|"/', '', $query);
			// chop the search terms into an array
			$terms = preg_split("/[\s,\+\.]+/", $query);
		}
	}
        }

	// if needed, do replacements
	if ( count($terms) > 0 ) {
		$GLOBALS['___tags'] = array();
		if ( !preg_match('/<.+>/', $text) ) {
			foreach ( $terms as $k => $v ) {
				if ( strlen($k) != 3 and strlen($k) != 6 ) {
					// choose random color
					$k = $colors[$i];
					$i = ( $i == 2 ? 0 : $i + 1 );
				}
				if ( strlen($v) > 1 ) {
					// escape term
					$b = preg_quote($v, '/');
					// If there are no tags in the text, we'll just do a simple search and replace
					$GLOBALS['__tag'] = $tag;
					$GLOBALS['__k'] = $k;
					$text = preg_replace_callback('/(\b' . $b . '\b)/i', 'replacer_text', $text);
					//$text = preg_replace('/(\b' . $b . '\b)/i', sprintf($tag, $k, '$1'), $text);
				}
			}
		} else {
			foreach ( $terms as $k => $v ) {
				if ( strlen($k) != 3 and strlen($k) != 6 ) {
					// choose random color
					$k = $colors[$i];
					$i = ( $i == 2 ? 0 : $i + 1 );
				}
				if ( strlen($v) > 1 ) {
					// escape term
					$b = preg_quote($v, '/');
					// If there are tags, we need to stay outside them
					$GLOBALS['__tag'] = $tag;
					$GLOBALS['__k'] = $k;
					$text = preg_replace_callback('/(?<=>)([^<]+)?(\b' . $b . '\b)/i', 'replacer_html', $text);
					//$text = preg_replace('/(?<=>)([^<]+)?(\b' . $b . '\b)/i', '$1' . sprintf($tag, $k, '$2'), $text);
				}
			}
		}
		// do final replacements
		if ( count($GLOBALS['___tags']) > 0 ) {
			$text = str_replace(array_keys($GLOBALS['___tags']), array_values($GLOBALS['___tags']), $text);
		}
	}

	return $text;
}

function replacer_html($matches) {
	$found = $matches[1] . sprintf($GLOBALS['__tag'], $GLOBALS['__k'], $matches[2]);
	if ( !isset($GLOBALS['___tags']) ) $GLOBALS['___tags'] = array();
	$r = base64_encode($found);
	$GLOBALS['___tags'][$r] = $found;
	return $r;
}
function replacer_text($matches) {
	$found = sprintf($GLOBALS['__tag'], $GLOBALS['__k'], $matches[1]);
	if ( !isset($GLOBALS['___tags']) ) $GLOBALS['___tags'] = array();
	$r = base64_encode($found);
	$GLOBALS['___tags'][$r] = $found;
	return $r;
}
?>
