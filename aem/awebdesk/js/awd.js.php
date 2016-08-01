<?php 

if (!@ini_get("zlib.output_compression"))
	@ob_start("ob_gzhandler");

header("Content-Type: text/javascript");
 
if (isset($_GET['inc'])) {
	if ( $_GET['inc'] == 'all' ) {
		$jsfiles = array();
		$dh  = opendir(dirname(__FILE__));
		while ( false !== ( $filename = readdir($dh) ) ) {
			if ( substr($filename, 0, 1) != '.' and is_file($filename) and preg_match('/\.js$/', $filename) ) {
				@readfile(dirname(__FILE__) . '/' . $filename);
			}
		}
	} else {
    	$jsfiles = explode(",", $_GET['inc']);
	}

    foreach ($jsfiles as $js) {
		$js = str_replace("%00", "", $js);
        $js = dirname(__FILE__) . "/" . urldecode($js) . ".js";

        if (file_exists($js))
            @readfile($js);
    }
}

?>