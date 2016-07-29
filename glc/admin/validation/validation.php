    <?php  
        function validateName($name){  
            //if it's NOT valid  
            if(strlen($name) < 2)  
                return false;  
            //if it's valid  
            else  
                return true;  
        }  
		function validateLname($l_name){  
            //if it's NOT valid n 
            if(strlen($l_name) < 2)  
                return false;  
            //if it's valid  
            else  
                return true;  
        } 
		function validateDate($date){  
            //if it's NOT valid  
            if(strlen($date) <1)  
                return false;  
            //if it's valid  
            else  
                return true;  
        } 
		function validateAdderss($address){  
            //if it's NOT valid  
            if(strlen($address) < 1)  
                return false;  
            //if it's valid  
            else  
                return true;    
        } 
		function validateCity($city){  
            //if it's NOT valid  
            if(strlen($city) < 1)  
                return false;  
            //if it's valid  
            else  
                return true;    
        } 
		function validateCountry($country){  
            //if it's NOT valid  
            if(strlen($country) < 1)  
                return false;  
            //if it's valid  
            else  
                return true;    
        } 
		function validateProvience($provience){  
            //if it's NOT valid  
            if(strlen($provience) < 1)  
                return false;  
            //if it's valid  
            else  
                return true;    
        } 
		function validatePhone($phone)
		{  
        	return ereg("^([0-9]{10})$", $phone);
        } 
		
		function validateUsername($username){  
            //if it's NOT valid  
            if(strlen($username) < 1)  
                return false;  
            //if it's valid  
            else  
                return true;    
        } 
		
		function validateDateOfBirth($year,$month,$day){  
            //if it's NOT valid  
            if(strlen($year) < 1 or strlen($month) < 1 or strlen($day) < 1)  
                return false;  
            //if it's valid  
            else 
			{
				$date1 = $year.'-'.$month.'-'.$day;
				$date2 = date('Y-m-d');
				$diff = abs(strtotime($date2) - strtotime($date1));
				$years = floor($diff / (365*60*60*24)); 
                if($years < 18)  
					return false;  
				//if it's valid  
				else  
					return true; 
			}		
        } 
		
        function validateEmail($email){  
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)) return false;
            return true;
            // return ereg("^([a-zA-Z0-9.\-\]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$", $email);  
        }  
        function validatePasswords($pass1, $pass2) {  
            //if DOESN'T MATCH 
			if(strlen($pass1) > 0)   
			{
				$res = strcmp($pass1,$pass2); 
				 if($res == 0)
					return true;
				else	 
				return false; 
			}
			else	 
				return false; 
        }
		
		function validateJoiningSr($joining_Sr){  
            //if it's NOT valid  
            if(strlen($joining_Sr) < 1)  
                return false;  
            //if it's valid  
            else  
                return true;  
        }  
		  
        function validateMessage($message){  
            //if it's NOT valid  
            if(strlen($message) < 1)  
                return false;  
            //if it's valid  
            else  
                return true;  
        }  
    ?>  