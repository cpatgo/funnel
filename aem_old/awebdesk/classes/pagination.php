<?php
/*
	This object will set all the necessary variables
	for smarty to handle Next/Previous buttons
*/

$GLOBALS['paginator_index'] = 0;

class Pagination {
	// properties
	var $id = 0;
	var $offset = 0;
	var $limit = 0;
	var $total = 0;
	var $fetched = 0;
	var $links = array();
	var $linksCnt = 1;
	var $hasPrevious = false;
	var $hasNext = false;
	var $previousLink = '';
	var $nextLink = '';
	var $thisPage = 1;
	var $showSpan = 3;
	var $baseURL = '';
	var $offsetName = 'offset';

	var $ajaxURL = 'awebdeskapi.php';
	var $ajaxAction = 'paginate';


	var $allowLimitChange = false;

	// constructor
	function Pagination($total, $count, $limit = 10, $offset = 0, $baseURL = '') {
		$GLOBALS['paginator_index']++;
		$this->id = $GLOBALS['paginator_index'];
		// assign all properties
		$this->total = $total;
		$this->fetched = $count;
		$this->limit = intval($limit);
		$this->offset = intval($offset);
		$this->baseURL = $baseURL;
	}


	// methods
	function buildLinks() {
		/*
			provide links to other results
		*/
		// previous page link
		$this->hasPrevious = $this->offset > 0;
		if ( $this->hasPrevious ) {
			$prevOffset = $this->offset - $this->limit;
			if ( $prevOffset <= 0 ) {
				$params = array();
			} else {
				$params = array($this->offsetName => $prevOffset);
			}
			$this->previousLink = $this->makeLink($params);
		}
		// next page link
		$this->hasNext = $this->total > ( $this->offset + $this->fetched );
		if ( $this->hasNext ) {
			$nextOffset = $this->offset + $this->fetched;
			$params = array($this->offsetName => $nextOffset);
			$this->nextLink = $this->makeLink($params);
		}
		/*
			links to all other pages
		*/
		// here we will hold all pages
		$this->links = array();
		// how many pages are there
		$this->linksCnt = ( $this->total == 0 ? 1 : ceil($this->total / $this->limit) );
		// where are we now?
		$this->thisPage = 1;
		// loop through all
		for ( $i = 1; $i <= $this->linksCnt; $i++ ) {
			$params = array();
			$tmpOffset = ($i - 1) * $this->limit;
			if ( $tmpOffset > 0 ) $params[$this->offsetName] = $tmpOffset;
			if ( $this->offset == $tmpOffset ) $this->thisPage = $i;
			$this->links[$i]['this'] = $this->offset == $tmpOffset;
			$this->links[$i]['link'] = $this->makeLink($params);
		}
		// loop through all, here define what to show
		foreach ( $this->links as $k => $v ) {
			$this->links[$k]['show'] = ( $this->showSpan == 0 or ( $k > $this->thisPage - $this->showSpan and $k < $this->thisPage + $this->showSpan ) );
		}
	}


	function makeLink($params = array()) {
		if ( count($params) == 0 ) return $this->baseURL;
		$paramsStr = '';
		$paramsArr = array();
		foreach ( $params as $k => $v ) {
			$paramsArr[] = $k . '=' . urlencode($v);
		}
		$paramsStr = implode('&', $paramsArr);
		return $this->baseURL . ( strpos($this->baseURL, '?') !== false ? '&' : '?' ) . $paramsStr;
	}
}

?>