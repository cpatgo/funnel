<?php


class Snoopy {

	var $error = '';
	var $response_code = 'NOCODE';
	var $status = 200;

	var $results = '';

	var $headers = array();

	function fetch($url) {
		$this->results = (string)@file_get_contents($url);
		if ( !$this->results ) {
			$this->status = '-100';
			$this->error = 'not fetched!';
		}
	}
}


?>