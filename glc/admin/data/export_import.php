<?php
	require_once(dirname(dirname(dirname(__FILE__))).'/function/functions.php');
	$user_class = getInstance('Class_User');
	$users = $user_class->get_users();
?>
<div class="ibox-content">
	<form action="ajax/import_members.php" method="post" enctype="multipart/form-data">
		<div class="alert alert-success"><b>NOTE: User will not be imported if the username or email is already existing in the database.</b></div>
		<input type="file" name="csv_file">
		<br>
  		<input type="submit" name="csv_submit" value="Import XLS File" class="btn btn-primary btn-large">
	</form>
</div>
<div class="ibox-content">
	
	<br>
	<form action="ajax/export_members.php" method="post">
		<input type="submit" name="Excel" value="Export Users" class="btn btn-primary btn-large" />
		<br>
		<br>
		<table class="table table-striped table-bordered table-hover dataTableExport">
			<thead>
				<tr>
					<th><input type="checkbox" id="check_all" value="1" checked="checked"></th>
					<th class="text-center">User Id</th>
					<th class="text-center">Username</th>
					<th class="text-center">Email</th>
					<th class="text-center">Parent</th>
					<th class="text-center">First Name</th>
					<th class="text-center">Last Name</th>
					<th class="text-center">Date Registered</th>
					<th class="text-center">Gender</th>
					<th class="text-center">Phone No.</th>
					<th class="text-center">City</th>
					
					<!-- <th class="text-center">Password</th>
					<th class="text-center">Date of Birth</th>
					<th class="text-center">Address</th>
					<th class="text-center">Country</th>
					<th class="text-center">Status</th>
					<th class="text-center">Payza Account</th>
					<th class="text-center">Pin Code</th>
					<th class="text-center">District</th>
					<th class="text-center">State</th>
					<th class="text-center">Province</th>
					<th class="text-center">Affiliate Option</th>
					<th class="text-center">Payment Type</th>
					<th class="text-center">Membership</th> -->
				</tr>
			</thead>
			<tbody>
				<?php 
					foreach ($users as $key => $value) {
						printf('<tr>');
						printf('<td><input type="checkbox" name="export_user[]" value="%s" checked="checked"></td>', $value['id_user']);
						printf('<td>%s</td>', $value['id_user']);
						printf('<td>%s</td>', $value['username']);
						printf('<td>%s</td>', $value['email']);
						printf('<td>%s</td>', get_user_name($value['real_parent']));
						printf('<td>%s</td>', $value['f_name']);
						printf('<td>%s</td>', $value['l_name']);
						printf('<td>%s</td>', date('Y-m-d H:i', $value['time']));
						printf('<td>%s</td>', $value['gender']);
						printf('<td>%s</td>', $value['phone_no']);
						printf('<td>%s</td>', $value['city']);
						
						// printf('<td>%s</td>', $value['password']);
						// printf('<td>%s</td>', $value['dob']);
						// printf('<td>%s</td>', $value['address']);
						// printf('<td>%s</td>', $value['country']);
						// printf('<td>%s</td>', $value['type']);
						// printf('<td>%s</td>', $value['payza_account']);
						// printf('<td>%s</td>', $value['pin_code']);
						// printf('<td>%s</td>', $value['district']);
						// printf('<td>%s</td>', $value['state']);
						// printf('<td>%s</td>', $value['provience']);
						// printf('<td>%s</td>', $value['optin_affiliate']);
						// printf('<td>%s</td>', $value['payment_type']);
						// printf('<td>%s</td>', $value['initial']);
						printf('</tr>');
					}
				?>
			</tbody>
		</table>
	</form>
</div>
<script type="text/javascript">
$(document).ready(function() {
	$('body').on('click', '#check_all', function(){
		if($(this).is(':checked') == true){
			$('input[name="export_user[]').prop("checked" , this.checked);
		} else {
			$('input[name="export_user[]"]').attr('checked', false);
		}
	})

	$('.dataTableExport').DataTable({
        "iDisplayLength": 100,
        columnDefs: [ {
            orderable: false,
            className: 'select-checkbox',
            targets:   0
        } ],
        select: {
            style:    'os',
            selector: 'td:first-child'
        },
        order: [[ 1, 'asc' ]]
    });   
});
</script>