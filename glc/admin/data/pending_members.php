<?php
require_once("../config.php");
include("../function/functions.php");
include("../function/join_plan.php");
include("../function/virtual_parent.php");
include("../function/send_mail.php");
include("../function/income.php");;
require_once("../function/get_parent_with_same_level.php");
require_once("../function/insert_board.php");
require_once("../function/insert_board_second.php");
require_once("../function/insert_board_third.php");
require_once("../function/insert_board_fourth.php");
require_once("../function/insert_board_fifth.php");
require_once("../function/find_board.php");	
//approve document
$user_class = getInstance('Class_User');
$paid = (isset($_GET["paid"]))?$_GET["paid"]:"";
$del = (isset($_GET["del"]))?$_GET["del"]:"";
$time = time();
if ($paid != "") 
{
	$query 	= mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM temp_users tu INNER JOIN memberships m ON m.membership = tu.membership WHERE id_user = $paid ");
	$row 	= mysqli_fetch_array($query);
	
	if(!empty($row)):
		$temp_user_id = $row['id_user'];

		//insert into users
		$users_sql = "INSERT INTO users (username ,real_parent , date, activate_date, f_name ,l_name ,email ,phone_no , password , dob ,address , city , country , gender,time,type ,provience ,state,optin_affiliate) VALUES ('".$row["username"]."' , '".$row["real_parent"]."' , '".$row["date"]."', '".$row["activate_date"]."', '".$row["f_name"]."', '".$row["l_name"]."' , '".$row["email"]."' , '".$row["phone"]."', '".$row["password"]."' , '".$row["dob"]."', '".$row["address"]."', '".$row["city"]."', '".$row["country"]."' , '".$row["gender"]."', '".$row["time"]."', 'B' , '".$row["provience"]."',  '".$row["state"]."', '".$row["optin_affiliate"]."') ";

		mysqli_query($GLOBALS["___mysqli_ston"], $users_sql);

		$user_id 	= mysqli_insert_id($GLOBALS["___mysqli_ston"]);
		$real_p		= $row["real_parent"];

		//user membership					
		mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO user_membership (user_id,payment_type,number,initial,current) VALUES ('".$user_id."' , '".$row["reg_way"]."', '".$refund_address."', '".$row["id"]."' , '".$row["id"]."') ");

		//Update user id in payment ipn
		$payment_class = getInstance('Class_Payment');
		if($row["reg_way"] === 'authorize_net_2' || $row["reg_way"] === 'authorize_net'):
			$payment_class->authorize_update_user_id($temp_user_id, $user_id);
		endif;

		if($row["reg_way"] === 'e_data'):
			$payment_class->edata_update_user_id($temp_user_id, $user_id);
		endif;

		if($row["reg_way"] === 'xpressdrafts'):
			$payment_class->echeck_update_user_id($temp_user_id, $user_id);
		endif;

		//Update evoucher with the new user_id if reg_way is E-Pin
		if($row["reg_way"] == 'E-pin'):
			$voucher_class = getInstance('Class_Voucher');
			$voucher_class->update_voucher_used_id($row['id_user'], $user_id);
		endif;

		//Update user_id in usermeta table if company name is present
		if(!empty($user_class->glc_usermeta($temp_user_id, 'company_name'))):
			$user_class->update_user_meta_id($temp_user_id, $user_id);
		endif;

		//Update user_id in payments table
		$payment_class->update_payment_user_id($temp_user_id, $user_id);

		//join palns
		switch ($row["id"]) {
			case '2':
				$plan = 1;
				break;
			case '3':
				$plan = 2;
				break;
			case '4':
				$plan = 3;
				break;					
			case '5':			
				$plan = 4;			
				break;
			case '6':
				$plan = 5;
				break;
		}

		$spill = 0;

		if($plan == 1)
		{
			unset($_SESSION['board_second_breal_id']);
			unset($_SESSION['board_third_breal_id']);
			unset($_SESSION['board_fourth_breal_id']);
			unset($_SESSION['board_fifth_breal_id']);
			unset($_SESSION['board_sixth_breal_id']);
			unset($_SESSION['board_breal_id']);
			$board_break_info = '';

			$board_break_info = insert_into_board($user_id,$real_p,$spill,$real_p);
			join_plan1($board_break_info);
			$membership_type = 2;
		}
		if($plan == 2)
		{
			unset($_SESSION['board_second_breal_id']);
			unset($_SESSION['board_third_breal_id']);
			unset($_SESSION['board_fourth_breal_id']);
			unset($_SESSION['board_fifth_breal_id']);
			unset($_SESSION['board_sixth_breal_id']);
			unset($_SESSION['board_breal_id']);
			$board_break_info = '';
										
			$board_break_info = insert_into_board($user_id,$real_p,$spill,$real_p);
			//var_dump($board_break_info);
			join_plan1($board_break_info);
			unset($_SESSION['board_second_breal_id']);
			unset($_SESSION['board_third_breal_id']);
			unset($_SESSION['board_fourth_breal_id']);
			unset($_SESSION['board_fifth_breal_id']);
			unset($_SESSION['board_sixth_breal_id']);
			unset($_SESSION['board_breal_id']);
			$board_break_info = '';
			
			$board_break_info = insert_into_board_second($user_id,$real_p,$spill,$real_p);
			//var_dump($board_break_info);
			join_plan2($board_break_info);
			$membership_type = 3;
		}
		if($plan == 3)
		{
			unset($_SESSION['board_second_breal_id']);
			unset($_SESSION['board_third_breal_id']);
			unset($_SESSION['board_fourth_breal_id']);
			unset($_SESSION['board_fifth_breal_id']);
			unset($_SESSION['board_sixth_breal_id']);
			unset($_SESSION['board_breal_id']);
			$board_break_info = '';

			$board_break_info = insert_into_board($user_id,$real_p,$spill,$real_p);
			join_plan1($board_break_info);
			
			unset($_SESSION['board_second_breal_id']);
			unset($_SESSION['board_third_breal_id']);
			unset($_SESSION['board_fourth_breal_id']);
			unset($_SESSION['board_fifth_breal_id']);
			unset($_SESSION['board_sixth_breal_id']);
			unset($_SESSION['board_breal_id']);
			$board_break_info = '';

			$board_break_info = insert_into_board_second($user_id,$real_p,$spill,$real_p);
			join_plan2($board_break_info);
			
			unset($_SESSION['board_second_breal_id']);
			unset($_SESSION['board_third_breal_id']);
			unset($_SESSION['board_fourth_breal_id']);
			unset($_SESSION['board_fifth_breal_id']);
			unset($_SESSION['board_sixth_breal_id']);
			unset($_SESSION['board_breal_id']);
			$board_break_info = '';

			$board_break_info = insert_into_board_third($user_id,$real_p,$spill,$real_p);
			join_plan3($board_break_info);
			$membership_type = 4;
		}
		if($plan == 4)
		{
			unset($_SESSION['board_second_breal_id']);
			unset($_SESSION['board_third_breal_id']);
			unset($_SESSION['board_fourth_breal_id']);
			unset($_SESSION['board_fifth_breal_id']);
			unset($_SESSION['board_sixth_breal_id']);
			unset($_SESSION['board_breal_id']);
			$board_break_info = '';

			$board_break_info = insert_into_board($user_id,$real_p,$spill,$real_p);
			join_plan1($board_break_info);

			unset($_SESSION['board_second_breal_id']);
			unset($_SESSION['board_third_breal_id']);
			unset($_SESSION['board_fourth_breal_id']);
			unset($_SESSION['board_fifth_breal_id']);
			unset($_SESSION['board_sixth_breal_id']);
			unset($_SESSION['board_breal_id']);
			$board_break_info = '';
			
			$board_break_info = insert_into_board_second($user_id,$real_p,$spill,$real_p);
			join_plan2($board_break_info);

			unset($_SESSION['board_second_breal_id']);
			unset($_SESSION['board_third_breal_id']);
			unset($_SESSION['board_fourth_breal_id']);
			unset($_SESSION['board_fifth_breal_id']);
			unset($_SESSION['board_sixth_breal_id']);
			unset($_SESSION['board_breal_id']);
			$board_break_info = '';

			$board_break_info = insert_into_board_third($user_id,$real_p,$spill,$real_p);
			join_plan3($board_break_info);
			unset($_SESSION['board_second_breal_id']);
			unset($_SESSION['board_third_breal_id']);
			unset($_SESSION['board_fourth_breal_id']);
			unset($_SESSION['board_fifth_breal_id']);
			unset($_SESSION['board_sixth_breal_id']);
			unset($_SESSION['board_breal_id']);
			$board_break_info = '';

			$board_break_info = insert_into_board_fourth($user_id,$real_p,$spill,$real_p);
			join_plan4($board_break_info);
			$membership_type = 5;
		}
		if($plan == 5)
		{
			unset($_SESSION['board_second_breal_id']);
			unset($_SESSION['board_third_breal_id']);
			unset($_SESSION['board_fourth_breal_id']);
			unset($_SESSION['board_fifth_breal_id']);
			unset($_SESSION['board_sixth_breal_id']);
			unset($_SESSION['board_breal_id']);
			$board_break_info = '';

			$board_break_info = insert_into_board($user_id,$real_p,$spill,$real_p);
			join_plan1($board_break_info);

			unset($_SESSION['board_second_breal_id']);
			unset($_SESSION['board_third_breal_id']);
			unset($_SESSION['board_fourth_breal_id']);
			unset($_SESSION['board_fifth_breal_id']);
			unset($_SESSION['board_sixth_breal_id']);
			unset($_SESSION['board_breal_id']);
			$board_break_info = '';
			
			$board_break_info = insert_into_board_second($user_id,$real_p,$spill,$real_p);
			join_plan2($board_break_info);

			unset($_SESSION['board_second_breal_id']);
			unset($_SESSION['board_third_breal_id']);
			unset($_SESSION['board_fourth_breal_id']);
			unset($_SESSION['board_fifth_breal_id']);
			unset($_SESSION['board_sixth_breal_id']);
			unset($_SESSION['board_breal_id']);
			$board_break_info = '';

			$board_break_info = insert_into_board_third($user_id,$real_p,$spill,$real_p);
			join_plan3($board_break_info);
			unset($_SESSION['board_second_breal_id']);
			unset($_SESSION['board_third_breal_id']);
			unset($_SESSION['board_fourth_breal_id']);
			unset($_SESSION['board_fifth_breal_id']);
			unset($_SESSION['board_sixth_breal_id']);
			unset($_SESSION['board_breal_id']);
			$board_break_info = '';

			$board_break_info = insert_into_board_fourth($user_id,$real_p,$spill,$real_p);
			join_plan4($board_break_info);
			unset($_SESSION['board_second_breal_id']);
			unset($_SESSION['board_third_breal_id']);
			unset($_SESSION['board_fourth_breal_id']);
			unset($_SESSION['board_fifth_breal_id']);
			unset($_SESSION['board_sixth_breal_id']);
			unset($_SESSION['board_breal_id']);
			$board_break_info = '';

			$board_break_info = insert_into_board_fifth($user_id,$real_p,$spill,$real_p);
			join_plan5($board_break_info);
			$membership_type = 6;
		}
		if(chk_real_forth_member($real_p))
		{
			unset($_SESSION['board_second_breal_id']);
			unset($_SESSION['board_third_breal_id']);
			unset($_SESSION['board_fourth_breal_id']);
			unset($_SESSION['board_fifth_breal_id']);
			unset($_SESSION['board_sixth_breal_id']);
			unset($_SESSION['board_breal_id']);
			$board_break_info = '';
			
			$new_user_id = $real_p;
			$new_real_p = get_real_parent($real_p);
			mysqli_query($GLOBALS["___mysqli_ston"], "update users set type='B' where id_user='$real_p' and type='F'");
			$board_break_info = insert_into_board($new_user_id,$new_real_p,$spill,$new_real_p);
			join_plan1($board_break_info);
			$membership_type = 2;
		}

		mysqli_query($GLOBALS["___mysqli_ston"], "delete from temp_users where id_user = ".$row["id_user"]);

		//Do not send email if the payment method is inside the array
		$ignore_payment_methods = array('authorize_net_2', 'authorize_net', 'e_data', 'xpressdrafts');
		if(!in_array($row["reg_way"], $ignore_payment_methods)):
			$mail = getInstance('Class_Email');
			// Send email to user
			$mail_result = $mail->welcome_email(array('email_address' => $row["email"], 'fname' => $row['f_name'], 'lname' => $row["l_name"], 'membership' => $row['reg_way'], 'username' => $row['username']));

			//Send email to enroller about referred user
			$mail_result = $mail->new_affiliate(array('username' => $row['username'], 'membership' => $row['reg_way'], 'email_address' => $row["email"], 'enroller' => $row["real_parent"]));
		endif;
	endif;
}elseif ($del != "") 
{
	mysqli_query($GLOBALS["___mysqli_ston"], "delete from temp_users where id_user = ".$del);	
}

$sql = "SELECT *
FROM temp_users t
INNER JOIN memberships m ON m.membership = t.membership";
$query = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

<style type="text/css">
.ui-dialog{
	width: 380px !important;
}
.ui-dialog-content, .ui-dialog, #dialog-text{
	color: #333 !important;
}
.ui-dialog-titlebar{
	background: rgba(0,0,0,0.3);
	color: #fff;
}
.ui-widget-content {
	border: 1px solid #ccc;
}
.ui-dialog-buttonset>button.ui-button:first-child>span {
	padding: .4em 3.5em; background: #428bca; color:#fff;
}
.ui-widget-overlay
{
	opacity: 0.7 !important;
}
.ui-widget{
	font-family: "Open Sans","Helvetica Neue",Helvetica,Arial,sans-serif;
}
</style>
<div class="ibox-content">	
<table class="table table-striped table-bordered table-hover dataTables">
	<thead>
	<tr>
		<th class="text-center">Id</th>
		<th class="text-center">Order #</th>
		<th class="text-center">Username</th>
		<th class="text-center">Name</th>
		<th class="text-center">Email</th>		
		<th class="text-center">Joining date</th>
		<th class="text-center">Enroller</th>
		<th class="text-center">Membership</th>
		<th class="text-center">Actions</th>
	</tr>
	</thead>
<?php
	while($row = mysqli_fetch_array($query))
	{	
		$id = $row['id_user'];
		$order = $id."-".$row['time'];
		$username = $row['username'];
		$email = $row['email'];
		$membership = $row['membership'];
		$real_parent = get_user_name($row['real_parent']);
		if($real_parent == '')
			$real_parent = "Top Member";
		else
			$real_parent = $real_parent;
		$name = $row['f_name']." ".$row['l_name'];
		$time = date('m/d/Y H:i:s' ,  $row['time'] );
		?>
		<tr class="text-center">
			<td><?php echo $id; ?></td>
			<td><?php echo $order; ?></td>
			<td><?php echo $username; ?></td>
			<td><?php echo $name; ?></td>
			<td><?php echo $email; ?></td>
			<td><?php echo $time; ?></td>
			<td><?php echo $real_parent; ?></td>
			<td><?php echo $membership; ?></td>
			<td> 
				<strong>
					<a onclick="return confirm('Approve payment for order #<?php echo $order; ?>?');" title="Paid" class="icon-5 info-tooltip float-left text-info" href="index.php?page=pending_members&paid=<?php echo $id; ?>"><i class="fa fa-square-o"></i> Paid</a>
					&nbsp;&nbsp;&nbsp;&nbsp; 
					<a title="Change Enroller" class="icon-5 info-tooltip float-left text-info" data-id="<?php echo $id; ?>" href="#" id="change_enroller"><i class="fa fa-square-o"></i> Change Enroller</a>
					&nbsp;&nbsp;&nbsp;&nbsp; 
					<a onclick="return confirm('Delete Pending Member <?php echo $row['username']; ?>?');" title="Delete" class="icon-5 info-tooltip float-left text-danger" href="index.php?page=pending_members&del=<?php echo $id; ?>"><i class="fa fa-square-o"></i> Delete</a>
					

				</strong>
			</td>
		</tr>
	<?php } ?>
	</tbody>
	</table>
</div>
<script type="text/javascript">
$(document).ready(function(){
	var pending_members_url = "<?php printf('%s/glc/admin/index.php?page=pending_members', GLC_URL); ?>";
	var ajax_url = "<?php printf('%s/glc/admin/ajax/', GLC_URL); ?>";
	$('body').on('click', '#change_enroller', function(e){
		e.preventDefault();
		var uid = $(this).data('id');
		var dialog_box = '<div id="dialog-confirm" title="Please confirm"><label>New Enroller: </label> <input type="text" id="new_enroller" placeholder="Username" value="" /></div>';
		$(dialog_box).dialog({
	      	resizable: true,
	      	modal: true,
	      	buttons: {
	        	"Confirm": function() {
	        		$( this ).dialog( "close" );
	        		$.ajax({
		                method: "post",
		                url: ajax_url+"change_enroller.php",
		                data: {
		                	id : uid,
		                	enroller : $('body').find('#new_enroller').val()
		                },
		                dataType: 'json',
		                success:function(result) {
		                    if(result.type == 'success'){
		                    	window.location.href = pending_members_url;
		                    } else {
		                        alert(result.message);    
		                        $('body').find('#dialog-confirm').remove();
		                    }
		                },
		                error: function(errorThrown){
		                    console.log(errorThrown);
		                }
		            });
	        	},
	        	"Cancel": function() {
	        		$( this ).dialog( "close" );
	          		return false;
	        	}
	      	}
	    });	
	});	
});
</script>