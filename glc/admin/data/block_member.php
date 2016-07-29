<?php
include("condition.php");

if(isset($_POST['submit']))
{
	$username = $_POST['username'];
	$discription = $_POST['discription'];
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from users where username = '$username' ");
	$num = mysqli_num_rows($query);
	if($num > 0)
	{
		while($row = mysqli_fetch_array($query))
		{
			// echo "<pre>";
			// print_r($row);
			$id_user = $row['id_user'];
			$user_type = $row['type'];
		}
		if($user_type != 'C')
		{
			mysqli_query($GLOBALS["___mysqli_ston"], "update users set description = '$discription' , type = 'C' where id_user = '$id_user' ");
			
			$date = date('Y-m-d');
			$log_username = $username;
			include("../function/logs_messages.php");
			data_logs($id_user,$data_log[17][0],$data_log[17][1],$log_type[17]);
			
			echo "<B style=\"color:#ff0000; font-size:12pt;\">User ".$username." Blocked !</B>";
		}
		else
		{ echo "<B style=\"color:#ff0000; font-size:12pt;\">User ".$username." already Block !</B>"; }
	}
	else
	{ echo "<B style=\"color:#ff0000; font-size:12pt;\">Please enter correct username !</B>"; }
}
else
{?>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
	<script src="//code.jquery.com/jquery-1.10.2.js"></script>
	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

	<div class="ibox-content">	
	<form name="franchisee" action="" method="post" id="block_form">
	<table class="table table-bordered">
		<thead><tr><th colspan="2">Block User</th></tr></thead>
		<tr>
			<th>Username :</th>
			<td>
				<input type="text" name="username" id="username" />
			</td>
		</tr>
		<tr>
			<th>Note:</th>
			<td><textarea name="discription" style=""></textarea></td>
		</tr>
		<tr>
			<td colspan="2" class="text-center">
				<input type="submit" name="submit" value="Block User" class="btn btn-primary" id="submit"/>
			</td>
		</tr>
	</table>
	</form>
	</div>
	<?php define('GLC_URL', sprintf('%s://%s', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http', $_SERVER['HTTP_HOST']));?>
	<script type="text/javascript">
		var mass_payment_url = "<?php printf('%s/glc/admin/index.php?page=mass_payment', GLC_URL); ?>";
        var ajax_url = "<?php printf('%s/glc/admin/ajax/', GLC_URL); ?>";
        var template_url = "<?php printf('%s/glc/admin/template/', GLC_URL); ?>";

		$('body').on('keyup', '#username', function(){
			var username = $(this).val();
			$.ajax({
                method: "post",
                url: ajax_url+"username.php",
                data: {
                    'username': username,
                },
                dataType: 'json',
                success:function(result) {
                    $( "#username" ).autocomplete({
				      source: result.message
				    });
                },
                error: function(errorThrown){
                    console.log(errorThrown);
                }
            });
		});

		$('body').on('click', '#submit', function(e){
			if(confirm('Are you sure you want to block '+$('#username').val()+'?')){
				$(this).submit();
				return true;
			};
			return false;
		})
	</script>
<?php 
} 
?>