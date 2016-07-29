<?php
/**
 * Tree Nodes Handling Functions
 *
 * FUNCTIONS: Functions that operate over MPTTA Trees.
 *
 * @package ACGLOBAL
 * @subpackage MPTTA
 * @author Milos Srdjevic
 *
 */


function adesk_mptta_browse($tree) {
	switch ( $tree ) {
		case 'category':
			$root = 1;
			break;
/*		case 'pages':
			$root = $GLOBALS['_DOCROOTS'][$lang];
			break;
*/		default:
			die('Unknown MPTTA tree! See documentation for details...');
	}
	return adesk_mptta_browse_tree($tree, $root);
}


function adesk_mptta_browse_tree($tree, $root) {
	$r = array();
	if ( !is_array($root) ) {
		$root = (int)$root;
		if ( !$root ) $root = 1;
		// fetch root node
		$query = "SELECT tleft, tright #$tree WHERE parent.id = '$root'";
		$sql = adesk_sql_query($query);
		if ( !$sql ) return $r;
		$root = mysql_fetch_assoc($sql);
	}
	$query = "
		SELECT
			*,
			ROUND( (`tright` - `tleft` - 1) / 2 ) AS ancestors
		FROM
			#$tree
		WHERE
			`tleft` BETWEEN '$root[tleft]' AND '$root[tright]'
		ORDER BY
			`tleft` ASC
		";
	$sql = adesk_sql_query($query);
	if ( !$sql ) return $r;
	$nodeBefore = $root;
	$levelBefore = 0;
	while ( $row = mysql_fetch_assoc($sql) ) {
		$row['predecessor'] = $nodeBefore;
		$row['tchange'] = $levelBefore - $row['tlevel'];
		$levelBefore = $row['tlevel'];
		$nodeBefore = $row['id'];
		$r[$row['id']] = $row;
	}
	return $r;
}


function adesk_mptta_rebuild($tree) {

	$root = 1;
	$left = 1;
	$level = 0;
	$sortfield = 'corder';
	switch ( $tree ) {
		case 'category':
			$root = 1;
			break;
		case 'savedresponse_category':
		case 'filelibrary_category':
		case 'updates_category':
			$root = 1;
			$sortfield = 'sort_order';
			break;
/*		case 'page':
			$root = $GLOBALS['_DOCROOTS'][$lang];
			break;
*/		default:
			die('Unknown MPTTA tree! See documentation for details...');
	}

	$right = adesk_mptta_rebuild_tree($tree, $root, $left, $level, $sortfield);
	return $right;
}
function adesk_mptta_rebuild_tree($tree, $parent, $left = 1, $level = 0, $sortfield = 'corder') {
	$r = array();
	// the right value of this node is the left value + 1
	$right = $left + 1;
	// get all children of this node
	$query = "
		SELECT
			id,
			$sortfield
		FROM
			#$tree
		WHERE
			parentid = '$parent'
		ORDER BY
			$sortfield ASC
	";
	$sql = adesk_sql_query($query);
	if ( !$sql ) die( nl2br($query . "\nCAUSED ERROR:\n" . adesk_sql_error()) );
	while ( $row = mysql_fetch_assoc($sql) ) {
		// recursive execution of this function for each
		// child of this node
		// $right is the current right value, which is
		// incremented by the rebuild_tree function
		$right = adesk_mptta_rebuild_tree($tree, $row['id'], $right, $level + 1, $sortfield);
		//$r[$row['id']] = $row;
	}
	// we've got the left value, and now that we've processed
	// the children of this node we also know the right value
	$query = "
		UPDATE
			#$tree
		SET
			tleft = '$left',
			tright = '$right',
			tlevel = '$level'
		WHERE
			id = '$parent'
	";
	$done = adesk_sql_query($query);
	if ( !$done ) die( nl2br($query . "\nCAUSED ERROR:\n" . adesk_sql_error()) );
	// return the right value of this node + 1
	return $right + 1;
}



function adesk_mptta_update_order($ids, $orders, $table, $field = 'corder') {
	$ary_ids    = explode(",", $ids);
	$ary_orders = explode(",", $orders);
	if ( count($ary_ids) != count($ary_orders) ) {
		return adesk_ajax_error(_a("The ids and order numbers do not match."));
	}
	for ( $i = 0; $i < count($ary_ids); $i++ ) {
		$id     = (int)$ary_ids[$i];
		$ary    = array($field => (int)$ary_orders[$i]);
		adesk_sql_update('#' . $table, $ary, "`id` = '$id'");
	}
	return adesk_mptta_rebuild($table);
}


?>