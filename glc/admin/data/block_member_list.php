<?php

include("condition.php");
include("../function/functions.php");


$newp = $_GET['p'];
$plimit = "15";

if(isset($_POST['submit']))
{
	$user_id = $_REQUEST['user_id'];
	mysqli_query($GLOBALS["___mysqli_ston"], "update users set type = 'B', description = '' where id_user = '$user_id' ");
	echo '<script type="text/javascript">window.location="/glc/admin/index.php?page=block_member_list";</script>'; 
}
else
{ 
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM users WHERE type = 'C' ");
	$totalrows = mysqli_num_rows($query);
	if($totalrows == '')
	{
		print "<B style=\"color:#ff0000; font-size:12pt;\">There Is No Block Member !</B>";
	}
	else
	{	?>
		<div class="ibox-content">
		<table class="table table-bordered">
		<thead>
		<tr><th>Total Block members :</th><th colspan="3"><?=$totalrows;?></th></tr>
		<tr>
			<th>Name</th>
			<th>User Name</th>
			<th>Discription</th>
			<th>User Name</th>
		</tr>
		</thead>
		<tbody>					
	<?php	
	$pnums = ceil ($totalrows/$plimit);
	if ($newp==''){ $newp='1'; }
		
	$start = ($newp-1) * $plimit;
	$starting_no = $start + 1;
	
	if ($totalrows - $start < $plimit) { $end_count = $totalrows;
	} elseif ($totalrows - $start >= $plimit) { $end_count = $start + $plimit; }
		
			
		
	if ($totalrows - $end_count > $plimit) { $var2 = $plimit;
	} elseif ($totalrows - $end_count <= $plimit) { $var2 = $totalrows - $end_count; }   
	
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM users WHERE type = 'C' LIMIT $start,$plimit ");
	while($id_row = mysqli_fetch_array($query))
	{
		$id = $id_row['id_user'];
		$description = $id_row['description'];
		$username = $id_row['username'];
		$name = $id_row['f_name']." ".$id_row['l_name'];
		
		echo "
			<tr>
				<td>$name</td>
				<td>$username</td>
				<td>$description</td>
				<td>
					<form method=\"post\" action=\"index.php?page=block_member_list\" id=\"block_form\">
					<input type=\"hidden\" name=\"user_name\" value=\"$username\" id=\"user_name\"/>
					<input type=\"hidden\" name=\"user_id\" value=\"$id\" id=\"user_id\"/>
					<input type=\"submit\" name=\"submit\" value=\"Unblock User\" id=\"submit_form\"/>
					</form>
				</td>
			</tr>";
	}
	print "</tbody></table></div>";
	?>
	<div id="DataTables_Table_0_paginate" class="dataTables_paginate paging_simple_numbers">
	<ul class="pagination">
	<?php
	if ($newp>1)
	{ ?>
		<li id="DataTables_Table_0_previous" class="paginate_button previous">
			<a href="<?="index.php?page=block_member_list&p=".($newp-1);?>">Previous</a>
		</li>
	<?php 
	}
	for ($i=1; $i<=$pnums; $i++) 
	{ 
		if ($i!=$newp)
		{ ?>
			<!--<li class="paginate_button " aria-controls="DataTables_Table_0" tabindex="0">-->
			<li class="paginate_button ">
				<a href="<?="index.php?page=block_member_list&p=$i";?>"><?php print_r("$i");?></a>
			</li>
			<?php 
		}
		else
		{ ?><li class="paginate_button active"><a href="#"><?php print_r("$i"); ?></a></li><?php }
	} 
	if ($newp<$pnums) 
	{ ?>
	   <li id="DataTables_Table_0_next" class="paginate_button next">
			<a href="<?="index.php?page=block_member_list&p=".($newp+1);?>">Next</a>
	   </li>
	<?php 
	} 
	?>
	</ul></div>
	<?php define('GLC_URL', sprintf('%s://%s', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http', $_SERVER['HTTP_HOST']));?>
	<script type="text/javascript">
		var mass_payment_url = "<?php printf('%s/glc/admin/index.php?page=mass_payment', GLC_URL); ?>";
        var ajax_url = "<?php printf('%s/glc/admin/ajax/', GLC_URL); ?>";
        var template_url = "<?php printf('%s/glc/admin/template/', GLC_URL); ?>";

		$('body').on('click', '#submit_form', function(e){
			if(confirm('Are you sure you want to unblock '+$(this).closest('td').find('#user_name').val()+'?')){
				$(this).submit();
				return true;
			};
			return false;
		})
	</script>
<?php 
		 
	}
}