<?php
require_once("../config.php");


if(isset($_POST['submit']))
{
	$category_name = $_POST['catg_name'];
	$point = $_POST['point'];
	
	$sql = "INSERT into ads_category(catg_name , point) VALUES ('$category_name' , '$point') ";		
	mysqli_query($GLOBALS["___mysqli_ston"], $sql);
	print "<B style=\"color:#015A08; font-size:12pt;\">Category Add successfully!</B>";	
	
	echo "<script type=\"text/javascript\">";
	echo "window.location = \"index.php?page=add_category\"";
	echo "</script>";
	
}
else
{?>
	<div class="ibox-content">
	<form name="ads_category" action="index.php?page=add_category" method="post" >
	<table class="table table-bordered">
		<thead><tr><th colspan=2>Add New Category</th></tr></thead>
		<tr>
			<th width="40%">Category Name</th>
			<td><input type="text" name="catg_name" /> </td>
		</tr>
		<tr>
			<th>Point</th>
			<td><input type="text" name="point" /> </td>
		</tr>
		<tr>
			<td colspan="2" class="text-center">
				<input type="submit" name="submit" value="Submit" class="btn btn-primary" />
			</td>
		</tr>
	</table>
	</form>
	</div>

<?php }?>
