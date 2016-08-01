<?php
/*
 * Class used to return the spam score from the PostMark Spam Check.
 */
class postmarkSpam {
	
	public $_Email;
	public $_Option;
	
	private $_ValuesArray; // built in validate();
	
	private function validate() {
		if ($this->_Email == "" || $this->_Option == "") {
			die('Required data missing.');
		}
		else {
			$this->_ValuesArray = array('email' => $this->_Email,'options' => $this->_Option);
		}
	}
	
	public function checkSpam()
    {
    	$this->validate();
    	// encode the data ready to be sent
    	$json_data = json_encode($this->_ValuesArray);
    	// set the headers in an array to be used by curl
    	$http_headers = array("Accept: application/json","Content-Type: application/json");
    	
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,"http://spamcheck.postmarkapp.com/filter");
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1); // return the data
		curl_setopt($ch,CURLOPT_CUSTOMREQUEST,"POST"); // we're doing a POST
		curl_setopt($ch,CURLOPT_POSTFIELDS,$json_data); // send the data in the json array
		curl_setopt($ch,CURLOPT_HTTPHEADER, $http_headers); // add the headers
		$result = curl_exec($ch); // run the curl
		if (curl_error($ch) != "") {
			die('Curl reported this error: '.curl_error($ch));
		}
		curl_close($ch); // close curl
		$result = json_decode($result,true); // decode the json data contained in the result
		$result['email'] = $this->_Email;
		return $result; // return result.
    }
	
}

/*
 * Function used to convert CSV to XML
 */
function csv_to_xml($csv, $delimiter = '|', $enclosure = '"', $escape = '\\', $terminator = PHP_EOL, $score) { 
    $xmlWriter = new XMLWriter();
	$xmlWriter->openMemory();
	$xmlWriter->setIndent(false);
	$xmlWriter->startDocument('1.0', 'UTF-8');
	$xmlWriter->startElement('emailcheck');
	$xmlWriter->writeElement('max', '8.0');
	$xmlWriter->writeElement('score', $score);
	
    $rows = explode($terminator,trim($csv)); 
    foreach ($rows as $row) { 
        if (trim($row)) { 
			$values = str_getcsv($row,$delimiter);
			$infoPos = strpos($values[2], "Informational"); //Remove infromation messages
			if ($infoPos === false) {
		        $xmlWriter->startElement('rules');
		        $xmlWriter->writeElement('score', $values[0]);
		        $xmlWriter->writeElement('name', $values[1]);
		        $xmlWriter->writeElement('descript', $values[2]);
		        $xmlWriter->endElement();
			}
        } 
    }
	
	$xmlWriter->writeElement('succeeded', '1');
	$xmlWriter->writeElement('message', 'Spam Score: '.$score.'/8.0');
	$xmlWriter->endElement();
	$xmlWriter->endDocument();
	$xmlOut = $xmlWriter->outputMemory(TRUE);
	
    return $xmlOut;
}

/*
 * Function used to convert CSV to XML
 */
function check_spam_postmark($theData) {
	// Call PostmarkSpam class
	$spamCheck = new postmarkSpam();
	$spamCheck->_Email = $theData;
	$spamCheck->_Option = "long";
	$check = $spamCheck->checkSpam();
	$spamXML = "";
	
	if ($check['success'] == true) {
		// Remove headers and unused text
		$report = preg_replace("/^(.*\n){2}/", "", $check['report']);
		$report = str_replace("pts rule name               description", "", $report);
		$report = str_replace("---- ---------------------- --------------------------------------------------", "", $report);
		$report = nl2br($report);
		
		// Replace spaces and breaks
		$report = str_replace("<br />", "", $report);
		$report = str_replace("  ", "|", $report);
		$report = str_replace("||", "|", $report);
		$report = str_replace("||", "|", $report);
		$report = str_replace("||", "|", $report);
		
		// Format the output into CSV | delimted
		$report2 = "";
		foreach(preg_split("/((\r?\n)|(\r\n?))/", $report) as $line) {
			$line = substr_replace($line, "|", 4, 1);
			$line = str_replace("| ", "|", $line);
			$firstchar = substr($line, 0, 1);
			if ($firstchar == " ") {
				$line = substr_replace($line, "", 0, 1);
			}
			if ($firstchar != "|") {
				$report2 .= $line . PHP_EOL;
			}
		}
		
		$spamXML = csv_to_xml($report2, '|', '"', '\\', PHP_EOL, $check['score']);
	}
	
	return $spamXML;
}

?>