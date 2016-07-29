<?php
include("../function/setting.php");
include("../function/functions.php");
include("../function/send_mail.php");
include("../function/wallet_message.php");

if(isset($_POST['submit']))
{

	$req_id = $_REQUEST['id'];
	$u_id = $_REQUEST['u_id'];
	$pay_to = $_REQUEST['pay_to'];
	$payment_method = $_REQUEST['payment_method'];
	$req_amount = $_REQUEST['req_amount'];
	$success_payment = false; 
/*	$query = mysql_query("select * from wallet where id = '$u_id' ");
	while($row = mysql_fetch_array($query))
	{
		$total_amount = $row['amount'];
	}
	if($req_amount > $total_amount)
	{
		header("location:requested_funds.php?mg=Requested amount $req_amount is not available in the wallet of User ID : <strong>$u_id</strong> !<br> Tour current Balance is $total_amount.");
	}
	else
	{*/
	if($payment_method == 'Bitcoin') {
		$query1 = mysqli_query($GLOBALS["___mysqli_ston"], "select number, payment_type from user_membership where user_id = '$u_id' and payment_type = 'Bitcoin'");
		$num = mysqli_num_rows($query1);
		if($num == 1)
		{
			$row1 = mysqli_fetch_array($query1);
		    //$payment_type 	= $row['payment_type'];
			$payment_number = $row1['number'];
			//bitocoin payment
			require_once("../coinbase_API/Coinbase.php");

			/*--API--*/
			$coinbaseAPIKey = 'OIA5512ezDiB9o46';
			$coinbaseAPISecret = 'OKorUGg5x64Q0geumk7USytCVjap4vxS';

			$coinbase = Coinbase::withApiKey($coinbaseAPIKey, $coinbaseAPISecret);
				  
			new Coinbase('OIA5512ezDiB9o46OKorUGg5x64Q0geumk7USytCVjap4vxS');

			//$account_id 		= '55f99179b0e9d662cb00000d';
			$account_id 		= '55fd86854e60763a240001af';
			$to 		  		= $payment_number;
			$amount     		= number_format($req_amount, 2);
			$response 			= $coinbase->sendMoney($account_id, $to, $amount, null, null, 'USD');
			$success_payment 	= $response->success;
		}
	} else {
		$success_payment = true; 
	}
	if($success_payment) {
		$accept_date= date('Y-m-d');
		if($pay_to == 0)
		{
			mysqli_query($GLOBALS["___mysqli_ston"], "update paid_unpaid set paid_date = '$accept_date' , paid = 1 where id = '$req_id' ");
			$pos = get_user_position($req_id);
			data_logs($req_id,$pos,$data_log[6][0],$data_log[6][1],$log_type[5]);
			
			$position = get_user_position($u_id);
			$requested_user = 0;
			$payee_user = get_user_name($u_id);
			$wallet_message[0] = request_approval_message(0,$payee_user,$req_amount,$requested_user);
			data_logs($u_id,$position,$data_log[5][1],$wallet_message,$log_type[5]);
			
		}
		else 
		{
			mysqli_query($GLOBALS["___mysqli_ston"], "update paid_unpaid set paid_date = '$accept_date' , paid = 1 where id = '$req_id' ");
			$pos = get_user_position($req_id);
			data_logs($req_id,$pos,$data_log[6][0],$data_log[6][1],$log_type[5]);
			update_member_wallet($pay_to,$req_amount,$data_log,$log_type);
			
			$position = get_user_position($id);
			$requested_user = get_user_name($pay_to);
			$payee_user = get_user_name($u_id);
			$wallet_message[0] = request_approval_message(1,$payee_user,$req_amount,$requested_user);
			data_logs($u_id,$position,$data_log[5][5],$wallet_message,$log_type[5]);
				
		}
		$bal_amount = $total_amount-$req_amount;		
		//mysql_query("update wallet set amount = '$bal_amount' , date = '$accept_date' where id = '$u_id' ");
		//$w_bal = get_wallet_balance($u_id);
		//insert_wallet_account($u_id , 0 , $req_amount , $date , $ac_type[5] , $ac_desc[5], 2 , $w_bal);	
		
			$pos = get_user_position($u_id);
			$wall_msg = "Request from ".$payee_user." of amount $ ".$req_amount." USD has accepted";
			data_logs($u_id,$pos,$data_log[5][1],$wall_msg,$log_type[4]);

		$pay_request_username = get_user_name($u_id);
		$request_amount = $req_amount;
		$to = get_user_email($u_id);
		$title = "Payment Transfer Message";
		$db_msg = $payment_transfer_message;
		
		include("../function/full_message.php");
		$SMTPMail = new SMTPClient($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from, $to, $title, $full_message);
		$SMTPChat = $SMTPMail->SendMail();

		printf('<script type="text/javascript">window.location="%s/glc/admin/index.php?page=withdrawal_balance_request&msg=Request Accepted!";</script>', GLC_URL);
	} else {
		print "Payment Unsuccessful!";
	}
	/*}*/
}
else
{
	$mg = $_REQUEST[mg]; echo $mg;
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from paid_unpaid where paid = 0 and amount > 0 ");
	$num = mysqli_num_rows($query);
	if($num != 0)
	{ ?>
	<?php if(isset($_GET['msg']) && !empty($_GET['msg'])) printf('<div class="alert alert-success">%s</div>', $_GET['msg']); ?>
		<div class="ibox-content">
		<table class="table table-bordered table-striped dataTablesePins"> 
			<thead>
			<tr>
				<th class="text-center">Id</th>
				<th class="text-center">Username</th>
				<th class="text-center">Request Amount</th>
				<th class="text-center">Payment Mode</th>
				<th class="text-center">Date</th>
				<th class="text-center">Note</th>
				<th class="text-center">Information</th>
			</tr>
			</thead>
			<tbody>
		<?php		
		while($row = mysqli_fetch_array($query))
		{
			$id = $row['id'];
			$u_id = $row['user_id'];
			$username = get_user_name($u_id);
			$pay_to = $row['pay_to'];
			$request_amount = $row['amount'];
			$request_date = date("m/d/Y", strtotime($row['request_date']));
			$payment_mode = $row['payment_mode'];
			$pay_mode = $row['pay_mode'];
			
			switch ($pay_mode) {
				case '1':
					$p_mode = "Cash";
					break;
				case '2':
					$p_mode = "PayPal";
					break;
				case '3':
					$p_mode = "Bitcoin";
					break;
			}
			
				
			echo "
				<tr class=\"text-center\">
					<td>$id</td>
					<td>$username</td>
					<td>$ $request_amount USD</small></td>
					<td>$p_mode</small></td>
					<td>$request_date</small></td>
					<td>
						<form action=\"index.php?page=withdrawal_balance_request\" method=\"post\">
						<textarea name=\"information\" style=\"width:150px; height:50px;\"> </textarea>
					</td>
					<td>
						<input type=\"hidden\" name=\"id\" value=\"$id\" />
						<input type=\"hidden\" name=\"u_id\" value=\"$u_id\" />
						<input type=\"hidden\" name=\"pay_to\" value=\"$pay_to\" />
						<input type=\"hidden\" name=\"payment_method\" value=\"$p_mode\" />
						<input type=\"hidden\" name=\"req_amount\" value=\"$request_amount\" />
						<input type=\"submit\" name=\"submit\" value=\"Pay Member\" class=\"btn btn-primary\" />			
						</form>				
					</td></tr>";
		}
		print "</table></div>";	
	}
	else{ print "<B style=\"color:#FF0000; font-size:12pt;\">There are no request !</B>"; }
 }  ?>
 
 