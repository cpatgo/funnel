<?php

function bitly_lookup($campaignid, $messageid, $ref = '') {
	$campaignid = (int)$campaignid;
	$messageid  = (int)$messageid;
	$ref        = adesk_sql_escape($ref);
	$rval       = (string)adesk_sql_select_one("SELECT bitly FROM #bitly WHERE campaignid = '$campaignid' AND messageid = '$messageid' AND ref = '$ref'");

	if ($rval == "")
		$rval = bitly_social($campaignid, $messageid);

	return $rval;
}

function bitly_social($cid, $mid) {
	# This function exists to codify the way we compute the social assets.

	return sprintf(adesk_site_plink("index.php?action=social&c=%s.%d"), md5((string)$cid), $mid);
}

?>
