<?php

///////////////////////////////////////////////////////////////
//
//	OVERVIEW
//
//	This file acts as a main switch file for the
//	software.  This file will include a assets
//	file (from the assets/ directory) and a
//	template file (from the templates/) directory.
//
//	Where to find the code to modify:
//		assets/ contains a file for each "action"
//		templates/ contains file(s) for each "action"
//		js/ contains all of the JS files needed
//		css/ contains the CSS file for styles
//
//	How to know which file to modify:
//		Each file in the assets/ and templates/
//		directory has a naming that is similar to
//		the action it is related to.  You can
//		determin the "action" name by looking at the
//		URL of the page you wish to modify.
//			IE: desk.php?action=list
//		With the above example you would want to look
//		for assets and template files that have "list"
//		in the filename.
//
//
//
///////////////////////////////////////////////////////////////
//
//	� GLC HUB. All rights reserved.
//
///////////////////////////////////////////////////////////////


if (!@ini_get("zlib.output_compression")) @ob_start("ob_gzhandler");

// require main include file
require_once(dirname(__FILE__) . '/awebdeskend.inc.php');
 
	include(dirname(__FILE__) . '/assets_init.php');

?>
