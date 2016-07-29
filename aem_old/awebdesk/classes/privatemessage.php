<?php

require_once(awebdesk_classes('select.php'));

class adesk_Privatemessages extends adesk_Select {

	var $selectAddon = ', 0 AS score';

	function adesk_Privatemessages() {
		parent::adesk_Select();
		$this->conds    = array(
		/*
		'category' => '',
		'approved' => '',
		'author' => '',
		'timespan' => '',
		'published' => '',
		'search' => '',
		'stats' => ''
		*/
		);
	}
	
	function getTemplate($count = false) {	
		$template = "
			SELECT
				p.*,
				u1.absid AS 'user_from_moreinfo',
				u2.absid AS 'user_to_moreinfo'
				$this->selectAddon
			FROM
				#privmsg p,
				#user u1,
				#user u2
			WHERE
				[...]
				AND p.user_from = u1.id
				AND p.user_to = u2.id
		";
		if ( $count ) $this->count("p.id");
		return $this->query($template);
	}
	
	function getList($reset = false) {
		//dbg(adesk_prefix_replace($this->getTemplate(false)), 1);
		return adesk_sql_query($this->getTemplate(false), $reset);
	}

	function getCount($reset = false) {
		//dbg(adesk_prefix_replace($this->getTemplate(true)), 1);
		return adesk_sql_query($this->getTemplate(true), $reset);
	}

	function inputHandler($input, $field, $like = false, $include = true) {
		$cond = '';
		$strings = ( substr($field, -2, 2) != 'id' );
		if ( $input ) {
			if ( is_array($input) ) {
				$delim = ( $strings ? "', '" : ", " );
				$cond = implode($delim, $input);
				if ( $strings ) $cond = "'$cond'";
				$eval = ( $include ? "IN" : "NOT IN" );
				//$cond = "AND $field $eval ($cond)";
				$cond = "$field $eval ($cond)";
			} else {
				$cond = ( $strings ? adesk_sql_escape($input, $like) : (int)$input );
				if ( $like ) $cond = "%$cond%";
				if ( $strings ) $cond = "'$cond'";
				$eval = ( $like ? ( $include ? "LIKE" : "NOT LIKE" ) : ( $include ? "=" : "!=" ) );
				//$cond = "AND $field $eval $cond";
				$cond = "$field $eval $cond";
			}
		}
		return $cond;
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

	function filterInbox($id) {
		$this->setFilter('privatemessage', $this->inputHandler($id, 'p.user_to', false, true));
	}
	
	function filterSent($id) {
		$this->setFilter('privatemessage', $this->inputHandler($id, 'p.user_from', false, true));
	}

	function filterSearch($query = '', $in = '', $fulltext = false) {
		$cond = '';
		if ( $query != '' ) {
			// check if IN is in allowed list
			if ( !in_array($in, array('user_to', 'user_from', 'title', 'content')) ) {
				$in = '';
			}
			// check if IN is list for LIKE escaping
			$like = in_array($in, array('title', 'content'));
			$escFulltext = adesk_sql_escape($query, true);
			$escLike = adesk_sql_escape($query, false);
			$eval = ( $like ? "LIKE '%$escLike%'" : "= '$escFulltext'" ); // dates, approved, published, etc supported with this
			if ( $in != '' ) {
			// author is an exception, they type in username, so we gotta find matches first
				if ( $in == 'author' ) {
					// we gotta find matches first
					$r = user_search($query);
					$str = implode(', ', array_keys($r));
					// then reset the fields for search in here
					$query = 'userid';
					$in = 'userid';
					$eval = "IN ($str)";
//				} elseif ( substr($in, 1) == 'date' ) {
//					$eval = "= '$escFulltext'";
				}
				// set condition
				$cond = "AND c.$in $eval";
			} else {
				// author is an exception, they type in username, so we gotta find matches first
				$r = user_search($query);
				$str = implode(', ', array_keys($r));
				$usercond = ( count($r) > 0 ? "OR c.userid IN ($str)" : "" );
				// search all
				if ( $fulltext ) {
					$cond = "
						AND
						(
							MATCH(c.subject, c.comment) AGAINST ('$escFulltext')
							$usercond
						)
					";
					$this->selectAddon = ", MATCH(c.subject, c.comment) AGAINST ('$escFulltext') AS score";
				} else {
					$cond = "
						AND
						(
							c.subject LIKE '%$escLike%'
						OR
							c.comment LIKE '%$escLike%'
							$usercond
						)
					";
				}
			}
		}
		$this->setFilter('search', $cond);
	}

}

?>
