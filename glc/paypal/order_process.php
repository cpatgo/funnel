<?php
$link = sprintf('%s://%s', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http', $_SERVER['HTTP_HOST']); 
//change below, dont forget the sdk_config.ini file to sandbox/live
//sandbox logins
define('CLIENT_ID', 'AUjYo6zQBW4GCa18afB3FhHKIZmSOasQLGOy90WJZ23lUuHlktdkgdDpGGeaPn1IkrcprjBBLZbSZI7d'); //your PayPal client ID
define('CLIENT_SECRET', 'EAyMsp0mvuTCpRwb2JtfDHXE8D93z7zt34_47wgYxxf7m_N1XZscxl7iL6bYOYaD1AEmMfIydlwdXYmU'); //PayPal Secret
//live logins
//define('CLIENT_ID', 'AR0cFrYAHxyh-UN3U828ky3MDrcI8lJep-dK01zxJX1McIkVkg1ZCKKXDt78O6RCk3MkGe5IOWzCB1ie'); //your PayPal client ID
//define('CLIENT_SECRET', 'EL6LUtZn51XkLdSHDyGvCHsbZ3HVjJT54hwgYX649fW24w2l1axmeOEjmUaEVvCC9eNhyoO7vsWwEYO_'); //PayPal Secret

define('RETURN_URL', $link.'/paypal/order_process.php'); //return URL where PayPal redirects user
define('CANCEL_URL', $link.'/index.php'); //cancel URL
define('PP_CURRENCY', 'USD'); //Currency code
define('PP_CONFIG_PATH', __DIR__); //PayPal config path (sdk_config.ini)

include_once __DIR__ . "/vendor/autoload.php"; //include PayPal SDK
include_once __DIR__ . "/functions.inc.php"; //our PayPal functions
//var_dump($_SESSION);die();
#### Prepare for Payment ####
if(isset($_POST["item_code"])){
	$item_price = '1.00'; //get item price
	$item_name = $_POST["item_name"]; //get item code
	$item_code = $_POST["item_code"]; //get item code
	
	$item_qty = $_POST["qty"]; //get quantity
	/* 
	Note: DO NOT rely on item_price you get from products page, in production mode get only "item code" 
	from the products page and then fetch its actual price from Database.
	Example :
	$results = $mysqli->query("SELECT item_name, item_price FROM products WHERE item_code= '$item_code'");
	while($row = $results->fetch_object()) {
		$item_name = $row->item_name;
		$item_price = item_price ;
	}  
	*/

	//set array of items you are selling, single or multiple
	$items = array(
		array('name'=> $item_name, 'quantity'=> $item_qty, 'price'=> $item_price, 'sku'=> $item_code, 'currency'=>PP_CURRENCY)
	);
	
	//calculate total amount of all quantity. 
	$total_amount = ($item_qty * $item_price);
	
	try{ // try a payment request
		//if payment method is paypal
		
		$result = create_paypal_payment($total_amount, PP_CURRENCY, '', $items, RETURN_URL, CANCEL_URL);
		var_dump($result->links[1]->href);	
		//if payment method was PayPal, we need to redirect user to PayPal approval URL
		if($result->state == "created" && $result->payer->payment_method == "paypal"){
			$_SESSION["payment_id"] = $result->id; //set payment id for later use, we need this to execute payment
			header("location: ". $result->links[1]->href); //after success redirect user to approval URL 
			exit();
		}
        
		
	}catch(PPConnectionException $ex) {
		echo parseApiError($ex->getData()); echo "This did not work 1";
	} catch (Exception $ex) {
		echo $ex->getMessage(); echo "This did not work 2";
	}
}


### After PayPal payment method confirmation, user is redirected back to this page with token and Payer ID ###

if(isset($_GET["token"]) && isset($_GET["PayerID"]) && isset($_SESSION["payment_id"])){
	try{
		$result = execute_payment($_SESSION["payment_id"], $_GET["PayerID"]);  //call execute payment function.

		if($result->state == "approved"){ //if state = approved continue..
			//SUCESS
			
			unset($_SESSION["payment_id"]); //unset payment_id, it is no longer needed 
			//var_dump($result);
			//get transaction details
			$transaction_id 		= $result->transactions[0]->related_resources[0]->sale->id;
			$transaction_time 		= $result->transactions[0]->related_resources[0]->sale->create_time;
			$transaction_currency 	= $result->transactions[0]->related_resources[0]->sale->amount->currency;
			$transaction_amount 	= $result->transactions[0]->related_resources[0]->sale->amount->total;
			$transaction_method 	= $result->payer->payment_method;
			$transaction_state 		= $result->transactions[0]->related_resources[0]->sale->state;
			$transaction_sku 		= $result->transactions[0]->item_list->items[0]->sku;
			
			//get payer details
			$payer_first_name 		= $result->payer->payer_info->first_name;
			$payer_last_name 		= $result->payer->payer_info->last_name;
			$payer_email 			= $result->payer->payer_info->email;
			$payer_id				= $result->payer->payer_info->payer_id;
			//die();
			//get shipping details 
			$shipping_recipient		= $result->transactions[0]->item_list->shipping_address->recipient_name;
			$shipping_line1			= $result->transactions[0]->item_list->shipping_address->line1;
			$shipping_line2			= $result->transactions[0]->item_list->shipping_address->line2;
			$shipping_city			= $result->transactions[0]->item_list->shipping_address->city;
			$shipping_state			= $result->transactions[0]->item_list->shipping_address->state;
			$shipping_postal_code	= $result->transactions[0]->item_list->shipping_address->postal_code;
			$shipping_country_code	= $result->transactions[0]->item_list->shipping_address->country_code;
						
			 
				####  AT THIS POINT YOU CAN SAVE INFO IN YOUR DATABASE ###
				//see (http://www.sanwebe.com/2013/03/basic-php-mysqli-usage) for mysqli usage
			/*  
				//Open a new connection to the MySQL server
				$mysqli = new mysqli('10.0.0.2','vgweb','CyZg5LqcvY8T5L8kXguhnDFz','vgweb_usermgr');
			   
				//Output any connection error
				if ($mysqli->connect_error) {
					die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
				}      
					$userid = $_SESSION['id'];
				
				$insert_row = $mysqli->query("INSERT INTO transactions (`payer_id`, `user_id`, `transaction_id`, `transaction_state`, `amount`, `payer_first_name`, `payer_last_name`, `payer_email`, `transaction_method`, `shipping_recipient`, `shipping_line1`, `shipping_line2`, `shipping_city`, `shipping_state`, `shipping_postal_code`, `shipping_country_code`)
					VALUES ('$payer_id', '$userid', '$transaction_id', '$transaction_state', '$transaction_amount', '$payer_first_name', '$payer_last_name', '$payer_email', '$transaction_method', '$shipping_recipient', '$shipping_line1', '$shipping_line2', '$shipping_city', '$shipping_state', '$shipping_postal_code', '$shipping_country_code')");
				
			//Set session for later use, print_r($result); to see what is returned
			$_SESSION["results"]  = array(
					'transaction_id' => $transaction_id, 
					'transaction_time' => $transaction_time,
					'transaction_currency' => $transaction_currency,
					'transaction_amount' => $transaction_amount,
					'transaction_method' => $transaction_method,
					'transaction_state' => $transaction_state
					);
			
			//give user thier vg coins if tran state is completed
			if($transaction_state == 'completed'){
				$update_row = $mysqli->query("UPDATE users_credits SET `purchased_credits` = purchased_credits + $transaction_amount, `available_credits` = available_credits + $transaction_amount, `total_purchases` = total_purchases + 1 WHERE user_id = '".$_SESSION['id']."'");
				if($transaction_sku > 1){
					//register player into tournament
					$product_id = $transaction_sku;
					$register_player_results = buyProduct($userid, $product_id);
					if($product_id == 64 || $product_id == 67 || $product_id ==  68){
						include '../ts3/ts3_functions.php';
						$cldbid = getTeamspeakDatabaseId($_SESSION['id']);
						pre_check_in($cldbid);
					}
				} 
			   	//echo $register_player_results; die();
			}elseif($transaction_state == 'pending'){
				$update_row = $mysqli->query("UPDATE users_credits SET `purchased_credits` = purchased_credits + $transaction_amount, `total_purchases` = total_purchases + 1 WHERE user_id = '".$_SESSION['id']."'");
			}
			header("location: ". RETURN_URL); //$_SESSION["results"] is set, redirect back to order_process.php
			exit();
			*/
		}
		
	}catch(PPConnectionException $ex) {
//		$ex->getData();
        var_dump($ex);
	} catch (Exception $ex) {
       //         var_dump($ex);
		//echo $ex->getData();
        //header("location: ../index.php?error=3");
		//declined or another reason
		echo $ex->getMessage();
	}

}

### Display order confirmation if $_SESSION["results"] is set  ####
if(isset($_SESSION["results"]))

{
//header("location: ../index.php");

	$html = '<!DOCTYPE HTML>';
	$html .= '<html>';
	
	
	$html .= '<head>';
	$html .= '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
	$html .= '<title>Order Confirm Details</title>';
	$html .= '<style type="text/css">';
	$html .= '.transaction_info {margin:0px auto; background:#F2FCFF;; max-width: 750px; color:#555;}';
	$html .= '.transaction_info thead {background: #BCE4FA;font-weight: bold;}';
	$html .= '.transaction_info thead tr th {border-bottom: 1px solid #ddd;}';
	$html .= '</style>';
	$html .= '</head>';
	$html .= '<body>';

	$html .='<div align="center"><h2>Payment Success</h2></div>';
	$html .= '<table border="0" cellpadding="10" cellspacing="0" class="transaction_info">';
	
	$html .= '<thead><tr><td>Transaction ID</td><td>Date</td><td>Currency</td><td>Amount</td><td>Method</td><td>State</td></tr></thead>';
	
	$html .= '<tbody><tr>';
	$html .= '<td>'.$_SESSION["results"]["transaction_id"].'</td>';
	$html .= '<td>'.$_SESSION["results"]["transaction_time"].'</td>';
	$html .= '<td>'.$_SESSION["results"]["transaction_currency"].'</td>';
	$html .= '<td>'.$_SESSION["results"]["transaction_amount"].'</td>';
	$html .= '<td>'.$_SESSION["results"]["transaction_method"].'</td>';
	$html .= '<td>'.$_SESSION["results"]["transaction_state"].'</td>';
	$html .= '</tr>';
	$html .= '<tr><td colspan="6"><div align="center"><a href="../index.php">Thank You We recorded your transaction for the tournament</a></div></td></tr>';
	$html .= '</tbody>';
	$html .= '</table>';
	$html .= '</body>';
	$html .= '</html>';
	
	echo $html;
	
	unset($_SESSION["results"]); 
}
?>
