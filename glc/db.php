<?php
/**
* Base class for accessing the database
* Author: Sarah Gregorio <sarahgregorio29@gmail.com>
*/
class DB
{
	private $data = array();
	public static $glc_url;

	protected function include_config(){
		if(file_exists('config.php')) require_once(dirname(__FILE__).'/config.php'); 
		if($con->connect_error) {
		    die(sprintf('Connection failed: %s', $con->connect_error));
		} 
		self::$glc_url = GLC_URL;
		return $con;
	}

	protected function query($query){
		$con = $this->include_config();
		$result = $con->query($query);
		return $result;
	}

	protected function select($query){
		$con = $this->include_config();
		$result = $con->query($query);
		if($result->num_rows < 1) return array();
		while($row = $result->fetch_assoc()) {
        	$this->data[] = $row;
    	}
		return $this->data;
	}

	protected function insert($query){
		$con = $this->include_config();
		$result = $con->query($query);
		if($result === true) return $result->insert_id;
		return $result->error;
	}

	protected function update($query){
		$con = $this->include_config();
		$result = $con->query($query);
		if($result === true) return true;
		return $result->error;
	}

	protected function delete($query){
		$con = $this->include_config();
		$result = $con->query($query);
		if($result === true) return true;
		return $result->error;
	}
}