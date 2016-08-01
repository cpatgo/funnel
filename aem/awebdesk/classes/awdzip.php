<?php

require_once(awebdesk('pclzip/pclzip.lib.php'));

class ACZIPBuilder extends PclZip {

	function ACZIPBuilder($name) {
		return parent::PclZip($name);
	}

}

?>