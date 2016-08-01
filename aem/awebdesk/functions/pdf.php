<?php

	/*
	taken from a user comment on this page: http://php.net/manual/en/ref.pdf.php
	*/

	function adesk_pdf_extract_text($psData){

	  if (!is_string($psData)) {
	      return '';
	  }

	  $text = '';

	  // Handle brackets in the text stream that could be mistaken for
	  // the end of a text field. I'm sure you can do this as part of the
	  // regular expression, but my skills aren't good enough yet.
	  $psData = str_replace('\)', '##ENDBRACKET##', $psData);
	  $psData = str_replace('\]', '##ENDSBRACKET##', $psData);

	  preg_match_all(
	    '/(T[wdcm*])[\s]*(\[([^\]]*)\]|\(([^\)]*)\))[\s]*Tj/si',
	    $psData,
	    $matches
	  );

	  for ($i = 0; $i < sizeof($matches[0]); $i++) {
	      if ($matches[3][$i] != '') {
	        // Run another match over the contents.
	        preg_match_all('/\(([^)]*)\)/si', $matches[3][$i], $subMatches);
	        foreach ($subMatches[1] as $subMatch) {
	            $text .= $subMatch;
	        }
	      } else if ($matches[4][$i] != '') {
	      	$text .= ($matches[1][$i] == 'Tc' ? ' ' : '') . $matches[4][$i];
	      }
	  }

	  // Translate special characters and put back brackets.
	  $trans = array(
	    '...'                => '…',
	    '\205'                => '…',
	    '\221'                => chr(145),
	    '\222'                => chr(146),
	    '\223'                => chr(147),
	    '\224'                => chr(148),
	    '\226'                => '-',
	    '\267'                => '•',
	    '\('                => '(',
	    '\['                => '[',
	    '##ENDBRACKET##'    => ')',
	    '##ENDSBRACKET##'    => ']',
	    chr(133)            => '-',
	    chr(141)            => chr(147),
	    chr(142)            => chr(148),
	    chr(143)            => chr(145),
	    chr(144)            => chr(146),
	  );

	  $text = strtr($text, $trans);

	  return $text;
	}

?>