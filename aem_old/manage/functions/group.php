<?php
require_once(dirname(__FILE__) . '/permission.php');

function group_get_users($groups) {
	// fetch users of these groups (array)
	if ( !is_array($groups) or count($groups) == 0 ) return array();
	$groupsList = implode("', '", $groups);
	$query = "
		SELECT
			*,
			u.id AS id
		FROM
			#user u,
			#user_group p
		WHERE
			p.groupid IN ('$groupsList')
		AND
			p.userid = u.id
	";
	$sql = adesk_sql_query($query);
	if ( !$sql or mysql_num_rows($sql) == 0 ) return array();
	$r = array();
	while ( $row = mysql_fetch_assoc($sql) ) {
		$r[$row['id']] = $row;
	}
	return $r;
}


function group_get_all($list = 0, $cond = 1) {
	$r = array();
	// fetches all groups with a list_group relation fields. even if invalid list is provided, all groups are returned
	$query = "
		SELECT
			*,
			g.id AS id
		FROM
			#group g
		LEFT JOIN
			#list_group p
		ON
			g.id = p.groupid
		AND
			p.listid = '$list'
		WHERE
			$cond
		ORDER BY
			title ASC
	";
	$sql = adesk_sql_query($query);
	if ( !$sql or mysql_num_rows($sql) == 0 ) return $r;
	while ( $row = mysql_fetch_assoc($sql) ) {
		foreach ( $row as $k => $v ) {
			if ( substr($k, 0, 2) == 'p_' ) {
				if ( isset($row['pg_' . substr($k, 2)]) ) {
					if ( $v == null ) {
						$row[$k] = $row['pg_' . substr($k, 2)];
					}
				}
			}
		}
		$r[$row['id']] = $row;
	}
	//dbg(adesk_prefix_replace($query), 1);
	//dbg($r);
	return $r;
}

?>
