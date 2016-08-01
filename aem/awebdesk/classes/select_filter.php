<?php

require_once(awebdesk_classes('select.php'));


class adesk_Select_Filter extends adesk_Select {

	var $query = 'SELECT * FROM table WHERE [...]';
	var $searchList = array('name');
	var $searchSetList = array();
	var $searchLikeList = array('name');
	var $searchFullTextList = array();
	var $searchExceptions = array();
	var $conds = array();

	function adesk_Select_Filter() {
		parent::adesk_Select();
	}

	function get() {
		//dbg($this->query($this->query), 1);
		return $this->query($this->query);
	}

	function cnt($col = '*') {
		$this->count($col);
		//dbg($this->query($this->query), 1);
		return $this->query($this->query);
	}


	function setFilter($id, $cond) {
		if ( $cond == '' ) {
			if ( isset($this->conds[$id]) ) unset($this->conds[$id]);
		} else {
			$this->conds[$id] = $cond;
		}
	}

	function resetFilter() {
		$this->conds = null;
		$this->conds = array();
	}


	function inputHandler($input, $field, $like = false, $include = true) {
		$cond = '';
		$strings = ( substr($field, -2, 2) != 'id' or $field == 'stringid' );
		if ( $input ) {
			if ( is_array($input) ) {
				$delim = ( $strings ? "', '" : ", " );
				$cond = implode($delim, $input);
				if ( $strings ) $cond = "'$cond'";
				$eval = ( $include ? "IN" : "NOT IN" );
				$cond = "AND $field $eval ($cond)";
			} else {
				$cond = ( $strings ? _esc($input, $like) : (int)$input );
				if ( $like ) $cond = "%$cond%";
				if ( $strings ) $cond = "'$cond'";
				$eval = ( $like ? ( $include ? "LIKE" : "NOT LIKE" ) : ( $include ? "=" : "!=" ) );
				$cond = "AND $field $eval $cond";
			}
		}
		return $cond;
	}

	function inputHandlerInSet($input, $field, $include = true) {
		$cond = '';
		$strings = ( substr($field, -2, 2) != 'id' or $field == 'stringid' );
		if ( $input ) {
			if ( is_array($input) ) {
				$delim = ( $strings ? "', '" : ", " );
				$cond = implode($delim, $input);
				if ( $strings ) $cond = "'$cond'";
				$eval = ( $include ? "IN" : "NOT IN" );
				$cond = "AND $field $eval ($cond)";


				$cond = "AND " . implode(' OR ', $conds);
			} else {
				$cond = ( $strings ? _esc($input) : (int)$input );
				if ( $strings ) $cond = "'$cond'";
				$eval = ( $include ? "" : "NOT" );
				$cond = "AND $eval FIND_IN_SET($cond, $field)";
			}
		}
		return $cond;
	}

	function inputHandlerSubQuery($input, $field, $table, $where = 1, $like = false, $include = true) {
		$cond = '';
		$strings = ( substr($field, -2, 2) != 'id' or $field == 'stringid' );
		if ( $input ) {
			if ( is_array($input) ) {
				$delim = ( $strings ? "', '" : ", " );
				$cond = implode($delim, $input);
				if ( $strings ) $cond = "'$cond'";
				$eval = ( $include ? "IN" : "NOT IN" );
				$cond = "AND $field $eval ($cond)";
			} else {
				$cond = ( $strings ? _esc($input, $like) : (int)$input );
				if ( $like ) $cond = "%$cond%";
				if ( $strings ) $cond = "'$cond'";
				$eval = ( $like ? ( $include ? "LIKE" : "NOT LIKE" ) : ( $include ? "=" : "!=" ) );
				$cond = "AND $field $eval $cond";
			}
		}
		return "AND ( SELECT COUNT(*) FROM $table WHERE $where $cond ) > 0";
	}

	function inputFlag($switch = null, $field) {
		$cond = '';
		if ( is_null($switch) ) return;
		$cond = (int)(bool)$switch;
		$cond = "AND $field = $cond";
		$this->setFilter($field, $cond);
	}

	function filterTimeSpan($dateField = 'cdate', $from = null, $to = null, $eval = '=') {
		$cond = '';
		if ( !is_null($from) and !is_null($to) ) {
			// both used => RANGE
			if ( $from != 'NOW()' ) {
				$from = _esc($from);
				$from = "'$from'";
			}
			if ( $to != 'NOW()' ) {
				$to = _esc($to);
				$to = "'$to'";
			}
			$cond = "AND $dateField BETWEEN $from AND $to";
		} elseif ( !is_null($from) ) {
			// only from is used => EVAL OPERATOR
			if ( !preg_match('/^NOW()/', $from) ) {
				$cond = _esc($from);
				$cond = "'$cond'";
			} else $cond = $from;
			$cond = "AND $dateField $eval $cond";
		} elseif ( !is_null($to) ) {
			// only to is used => EVAL OPERATOR
			if ( !preg_match('/^NOW()/', $to) ) {
				$cond = _esc($to);
				$cond = "'$cond'";
			} else $cond = $to;
			$cond = "AND $dateField $eval $cond";
		}
		$this->setFilter('timespan', $cond);
	}

	function filterSearchField($query = '', $in = '', $fulltext = false) {
		if ( !$query or !$in ) return '';
		// check if IN is in allowed list
		if ( !in_array($in, $this->searchList) ) return '';
		$cond = '';
		// check if IN is list for LIKE escaping
		$like = in_array($in, $this->searchLikeList) and !in_array($in, $this->searchSetList);
		$escFulltext = _esc(str_replace("\\", "\\\\", $query), false);
		//$escFulltext = $query;
		$escLike = _esc($query, true);
		$eval = ( $like ? "LIKE '%$escLike%'" : "= '$escFulltext'" ); // dates, approved, etc supported with this
		if ( isset($this->searchExceptions[$in]) ) {
			$method = $this->searchExceptions[$in];
			$eval = $this->$method($query, $fulltext);
		}
		// set condition
		if ( in_array($in, $this->searchSetList) ) {
			// search SET fields
			$cond = "FIND_IN_SET('$escFulltext', $in)";
		} elseif ( in_array($in, $this->searchFullTextList) ) {
			// search fulltext fields
			$cond = "MATCH($in) AGAINST('$escFulltext')";
		} else {
			// search other fields
			$cond = "$in $eval";
		}
		return $cond;
	}

	function filterSearch($query = '', $in = '', $fulltext = false) {
		if ( !$query ) return;
		$cond = '';
		// check if IN is in allowed list
		if ( !in_array($in, $this->searchList) ) {
			$in = '';
		}
		if ( $in != '' ) {
			$cond = $this->filterSearchField($query, $in, $fulltext);
		} else {
			$conds = array();
			foreach ( $this->searchList as $in ) {
				$cond = $this->filterSearchField($query, $in, $fulltext);
				if ( $cond ) $conds[] = $cond;
			}
			if ( $conds ) $cond = "( " . implode(" OR ", $conds) . " )";
		}
		if ( $cond ) $this->setFilter('search', "AND $cond");
	}

}

?>
