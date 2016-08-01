<?php

class adesk_Iterator {
	var $rs;

	function adesk_Iterator(&$rs, &$link) {
		$this->rs =& $rs;
	}

	function assoc() {
		return adesk_sql_fetch_assoc($this->rs);
	}

	function row() {
		return adesk_sql_fetch_row($this->rs);
	}
}

?>
