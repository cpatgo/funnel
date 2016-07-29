<?php

if ( !isset($_SESSION['nlp']) or $_SESSION['nlp'] === 0 ) $_SESSION['nlp'] = null;

if ( $_SESSION['nlp'] ) {
	$cond = ( is_array($_SESSION['nlp']) ? implode("', '", $_SESSION['nlp']) : (int)$_SESSION['nlp'] );
	$query = "
		SELECT
			b.*
		FROM
			#user_group ug,
			#list l,
			#branding b
		WHERE
			l.id IN ('$cond')
		AND
			l.userid = ug.userid
		AND
			ug.groupid = b.groupid
		LIMIT 0, 1
	";
	$branding = adesk_sql_select_row($query);
	if ( $branding ) {
		if ( !isset($admin['groups'][$branding['groupid']]) ) {
			unset($branding['id']);unset($branding['groupid']);$branding['version'] = !$branding['version'];
			foreach ( $branding as $k => $v ) $admin['brand_' . $k] = $v;
			$site['site_name'] = $site['brand_site_name'] = $admin['brand_site_name'];
			$site['site_logo'] = $site['brand_site_logo'] = $admin['brand_site_logo'];
		}
	}
}

?>
