<?php

function adesk_log($str, $flags = array()) {
	$fp = fopen(adesk_cache_dir("log.txt"), "a");
	if (!$fp)
		return;

	$extra = "";
	if (in_array("place", $flags)) {
		$trace = debug_backtrace();
		$place = $trace[0];
		$extra = sprintf(" (file=%s line=%d func=%s args=%s)", $place["file"], $place["line"], $place["function"], implode(",", $place["args"]));
	}

	fwrite($fp, sprintf("[%s]: %s%s\n", date("Y-m-d H:i:s"), $str, $extra));
	fclose($fp);
}

?>
