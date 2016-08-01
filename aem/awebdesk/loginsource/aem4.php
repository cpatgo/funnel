<?php

$loginident = "aem4";
$loginvars = "host,dbname,user,pass,tableprefix,amsproductid";

class aem4LoginSource extends adesk_LoginSource {

	function aem4LoginSource($source4) {
		$this->adesk_LoginSource($source4);
	}

	function connect() {
		$source    = $this->source;
		$this->res = mysql_connect($source["host"], $source["user"], $source["pass"], true);
		mysql_select_db($source["dbname"]);
	}

	function authok($user, $pass) {
		$user   = mysql_real_escape_string($user, $this->res);
		$source = $this->source;
		$tbl    = $source["tableprefix"];
        
		$sq     =  mysql_query("SELECT member_id FROM ".$tbl."members WHERE login = '$user' and pass='$pass'",$this->res);
		$row = mysql_fetch_array( $sq  );
		
		
		if ($row)
		{
		$memberid = $row['member_id'];
		$q     =  mysql_query("SELECT DISTINCT product_id
        FROM ".$tbl."payments
        WHERE member_id = ".$memberid."
        AND begin_date <= NOW() AND expire_date >= NOW()
        AND completed > 0
        ORDER BY tm_added DESC limit 1",$this->res);
		$row2 = mysql_fetch_array( $q  );
		if($row2['0'] == $source["amsproductid"] ) 
		  return true;
          else
		 return false;
       
	   
	    }
		
 
    

		//return false;
	}

	function info($user) {
		$user   = mysql_real_escape_string($user, $this->res);
		$source = $this->source;
		$tbl    = $source["tableprefix"];
		$sq     = mysql_fetch_assoc(mysql_query("SELECT email, name_f, name_l FROM ".$tbl."members WHERE login = '$user'",$this->res));

		 
		# What is returned should normally be an array of three indexes:
		#  - first_name
		#  - last_name
		#  - email
		#
		# The username is set for us in the auth() method, so you needn't set it here.
		return array(
			"first_name" => $sq["name_f"],
			"last_name"  => $sq["name_l"],
			"email"      => $sq["email"]
		);
	}

	function syncinterval() {
		# This is how long, at least, we should wait before attempting to re-sync information
		# from the source to the product's authentication database.  The number is in seconds.

		return 300;		# 5 minutes
	}

}

?>