<?php

require_once(SWIFT_ABS_PATH . '/Swift/Iterator/MySQLResult.php');

class SendingEngineIterator extends Swift_Iterator_MySQLResult {

	/**
	 * PROPERTIES
	 */
	var $emailKey = 0;
	var $nameKey = 1;
	var $assoc = false;
	var $type = 'mysql';

	/**
	 * CONSTRUCTOR
	 */
	function SendingEngineIterator($rs = null, $type = 'mysql', $assoc = false, $emailKey = 0, $nameKey = 0) {
		// check if valid
		if ( !in_array($type, array('array', 'mysql')) ) return false;
		$this->type = $type;
		$this->set($rs, $type);
		$this->assoc = $assoc;
		$this->emailKey = $emailKey;
		$this->nameKey = $nameKey;
		$this->currentRow = array($this->emailKey => null, $this->nameKey => null);
	}

	/**
	 * METHODS
	 */
	/**
	 * Sets the real data list
	 * @return void
	 */
	function set($rs) {
		if ( $rs ) {
			if ( $this->type == 'mysql' ) {
				parent::Swift_Iterator_MySQLResult($rs);
			} elseif ( $this->type == 'array' ) {
				$this->resultSet = $rs;
				$this->numRows = count($rs);
				reset($this->resultSet);
			}
		}
	}

	/**
	 * Moves to the next array element if possible.
	 * @return boolean
	 */
	function next() {
		if ( $this->hasNext() ) {
			if ( $this->type == 'mysql' ) {
				$this->currentRow = ( $this->assoc ? mysql_fetch_assoc($this->resultSet) : mysql_fetch_row($this->resultSet) );
			} elseif ( $this->type == 'array' ) {
				$this->currentRow = $this->resultSet[$this->pos + 1];
			}
			$this->pos++;
			return true;
		}

		return false;
	}


	/**
	 * Goes directly to the given element in the array if possible.
	 * @param int Numeric position
	 * @return boolean
	 */
	function seekTo($pos) {
		if ( $pos >= 0 && $pos < $this->numRows ) {
			if ( $this->type == 'mysql' ) {
				mysql_data_seek($this->resultSet, $pos);
				$this->currentRow = ( $this->assoc ? mysql_fetch_assoc($this->resultSet) : mysql_fetch_row($this->resultSet) );
				mysql_data_seek($this->resultSet, $pos);
			} elseif ( $this->type == 'array' ) {
				$this->currentRow = $this->resultSet[$pos];
			}
			$this->pos = $pos;
			return true;
		}
		return false;
	}



	/**
	 * Returns the value at the current position, or NULL otherwise.
	 * @return mixed.
	 */
	function getValue() {
		$row = $this->currentRow;
		if ( $row[$this->emailKey] !== null) {
			$email = $row[$this->emailKey];
			$name = null;
			// if name is present
			if ( isset($row[$this->nameKey]) ) {
				// if name is not blank
				if ( $row[$this->nameKey] ) {
					// if name is not an email address
					if ( $row[$this->nameKey] != $row[$this->emailKey] ) {
						// assign name
						$name = $row[$this->nameKey];
					}
				}
			}
			// if name is still empty, set some default?
			//if ( !$name ) $name = _a("Subscriber");
			return new Swift_Address($email, $name);
		} else {
			return null;
		}
	}

	function isKey($k) {
		return ( $this->pos == $k );
	}

	function isFirst() {
		return $this->isKey(0);
	}

	function isLast($k) {
		return $this->isKey($this->numRows - 1);
	}
}

?>