<?php

	// php 5 only

	function adesk_sugarcrm_connect($post) {
		require_once( awebdesk('pear/SOAP/Client.php') );
		$url = rtrim($post['sugarcrm_url'], '/ ');
		$options = array(
	    "location" => $url . '/soap.php',
	    "uri" => 'http://www.sugarcrm.com/sugarcrm',
	    "trace" => 1
	  );
		$client = new SoapClient(NULL, $options);
		$params_login = array(
			'user_name' => $post['sugarcrm_username'],
			'password' => $post['sugarcrm_password'],
			'version' => '.01',
		);
		try {
			$login = $client->login($params_login, 'random string');
		}
		catch (Exception $e) {

			if ( $e->getMessage() == 'Not Found' ) {
				$human = _a('Your connection details are invalid. Please verify you have supplied the right information.');
			}
			else {
				$human = $e->getMessage();
			}

			return array('error' => $human);
		}
		return array('error' => '', 'login' => $login, 'client' => $client);
	}

?>