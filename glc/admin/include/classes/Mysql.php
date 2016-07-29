<?php
/************************************************
*
*   File Name: 	 mysql.php
*   Begin: 		 Sunday, Dec, 23, 2005
*   Author: 	 ahmet o�uz mermerkaya 	
*   Email: 		 ahmetmermerkaya@hotmail.com
*   Description: Class to connect mysql database
*	Edit : 		 Sunday, Nov, 18, 2007
*   Version: 	 1.1
*
***********************************************/ 

class MySQL
{	
	private $dbLink;
	private $dbHost;
	private $dbUsername;
    private $dbPassword;
	private $dbName;
	public  $queryCount;
	
	function MySQL($dbHost,$dbUsername,$dbPassword,$dbName)
	{
		$this->dbHost = $dbHost;
		$this->dbUsername = $dbUsername;
		$this->dbPassword = $dbPassword;
		$this->dbName = $dbName;	
		$this->queryCount = 0;		
	}
	function __destruct()
	{
		$this->close();
	}
	//connect to database
	private function connect() {	
		$this->dbLink = ($GLOBALS["___mysqli_ston"] = mysqli_connect($this->dbHost,  $this->dbUsername,  $this->dbPassword));		
		if (!$this->dbLink)	{			
			$this->ShowError();
			return false;
		}
		else if (!((bool)mysqli_query($this->dbLink, "USE " . $this->dbName)))	{
			$this->ShowError();
			return false;
		}
		else {
			mysqli_query($this->dbLink, "set names utf8");
			return true;
		}
		unset ($this->dbHost, $this->dbUsername, $this->dbPassword, $this->dbName);		
	}	
	/*****************************
	 * Method to close connection *
	 *****************************/
	function close()
	{
		@((is_null($___mysqli_res = mysqli_close($this->dbLink))) ? false : $___mysqli_res);
	}
	/*******************************************
	 * Checks for MySQL Errors
	 * If error exists show it and return false
	 * else return true	 
	 *******************************************/
	function ShowError()
	{
		$error = ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false));
		//echo $error;		
	}	
	/****************************
	 * Method to run SQL queries
	 ****************************/
	function  query($sql)
	{	
		if (!$this->dbLink)	
			$this->connect();
			
		if (! $result = mysqli_query($this->dbLink, $sql)) {
			$this->ShowError();			
			return false;
		}
		$this->queryCount++;	
		return $result;
	}
	/************************
	* Method to fetch values*
	*************************/
	function fetchObject($result)
	{
		if (!$Object=mysqli_fetch_object($result))
		{
			$this->ShowError();
			return false;
		}
		else
		{
			return $Object;
		}
	}
	/*************************
	* Method to number of rows
	**************************/
	function numRows($result)
	{
		if (false === ($num = mysqli_num_rows($result))) {
			$this->ShowError();
			return -1;
		}
		return $num;		
	}
	/*******************************
	 * Method to safely escape strings
	 *********************************/
	function escapeString($string)
	{
		if (get_magic_quotes_gpc()) 
		{
			return $string;
		} 
		else 
		{
			$string = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"], $string) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
			return $string;
		}
	}
	
	function free($result)
	{
		if (((mysqli_free_result($result) || (is_object($result) && (get_class($result) == "mysqli_result"))) ? true : false)) {
			$this->ShowError();
			return false;
		}	
		return true;
	}
	
	function lastInsertId()
	{
		return ((is_null($___mysqli_res = mysqli_insert_id($this->dbLink))) ? false : $___mysqli_res);
	}
	
	function getUniqueField($sql)
	{
		$row = mysqli_fetch_row($this->query($sql));
		
		return $row[0];
	}	
}