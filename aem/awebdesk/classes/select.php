<?php

class adesk_Select {
    var $joins;         # FROM ...
    var $conds;         # WHERE ...
	var $orders;		# ORDER BY ...
	var $groups;		# GROUP BY ...
	var $slist;
	var $limit;
    var $patn;
    var $joinpattern;
    var $greedy;

    function adesk_Select() {
        $this->joins       = array();
        $this->conds       = array('1');
        $this->patn        = '/\[\.\.\.\]/';
        $this->joinpattern = '/\[___\]/';
		$this->orders      = array();
		$this->slist       = array();
		$this->modify      = array();
		$this->limit       = "";
		$this->counting    = false;
		$this->remove      = true;
		$this->greedy      = true;
    }

	function modify($match, $replace) {
		$this->modify[] = array($match, $replace);
	}

	function modreplace($str) {
		foreach ($this->modify as $m) {
			if (strpos($str, $m[0]) !== false)
				$str = str_replace($m[0], $m[1], $str);
		}

		return $str;
	}

    function query($qstr, $clear = false) {
		if (count($this->slist) > 0) {
			$greedy = ( $this->greedy ? '' : 'U' );
			$qstr = preg_replace('/^\s*SELECT.*FROM/s' . $greedy, "SELECT " . implode(", ", $this->slist) . " FROM", $qstr);
			if ( $this->remove ) {
				$qstr = preg_replace('/LEFT JOIN.*WHERE/s', 'WHERE', $qstr);
				$qstr = preg_replace('/GROUP BY.*$/s', '', $qstr);
			}
		}

		if (!$this->counting && count($this->groups) > 0)
			$qstr .= " GROUP BY " . implode(", ", $this->groups);

		if (!$this->counting && count($this->orders) > 0)
			$qstr .= " ORDER BY " . implode(", ", $this->orders);

		if (!$this->counting && $this->limit != "")
			$qstr .= " LIMIT " . $this->limit;

		$this->counting = false;
		$this->slist    = array();
		$this->remove   = true;

		// WHERE
		if (count($this->conds) < 1)
            return preg_replace($this->patn, "", $qstr);

        $where = implode("\n", $this->conds);
        $retn  = preg_replace($this->patn, $where, $qstr);

		// JOIN
		if (count($this->joins) < 1) {
			$retn = $this->modreplace($retn);
            return preg_replace($this->joinpattern, "", $retn);
		}

        $join  = ', ' . implode(', ', $this->joins);
        $retn  = preg_replace($this->joinpattern, $join, $retn);
		$retn  = $this->modreplace($retn);

        if ($clear)
            $this->clear();

        return $retn;
    }

    function push($cond) {
		if (!in_array($cond, $this->conds))
			$this->conds[] = $cond;
    }

	function pop($cond) {
		$key = array_search($cond, $this->conds);

		if ($key === false)
			return;
		else
			unset($this->conds[$key]);
	}

    function clear() {
        unset($this->joins);
        unset($this->conds);
		unset($this->orders);
        $this->joins = array();
        $this->conds = array('1');
		$this->orders = array();
		$this->limit = "";
    }

	function orderby($col) {
		$this->orders[] = $col;
	}

	function groupby($col) {
		$this->groups[] = $col;
	}

	function limit($n) {
		$this->limit = $n;
	}

	function count($col = '*', $as = 'count') {
		$this->slist = array("COUNT($col) AS $as");
		$this->counting = true;
	}

	function dcount($col = '*', $as = 'count') {
		$this->slist = array("COUNT(DISTINCT($col)) AS $as");
		$this->counting = true;
	}

	function join($table, $conds = array()) {
		$this->joins[] = $table;
		foreach ( $conds as $c ) $this->conds[] = $c;
	}

	function select($ary) {
		if (!$this->counting) {
			if (count($this->slist) > 1)
				$this->slist = array_merge($ary, $this->slist);
			else
				$this->slist = $ary;
		}
	}
}

?>
