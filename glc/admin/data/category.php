<?php
require_once("../config.php");


if(isset($_POST['Edit']))
{
	$id = $_REQUEST['id'];
	$catg_name = $_REQUEST['catg_name'];
	$point = $_REQUEST['point'];
?>
	<div class="ibox-content">
	<form method="post">
	<table class="table table-bordered"> 
		<input type="hidden" name="id" value="<?=$id; ?>" />
		<tr>	
			<th>Category Name</th>
			<td><input type="text" name="catg_name" value="<?=$catg_name; ?>" /></td>
		</tr>
		<tr>	
			<th>Description</th>
			<td><input type="text" name="point" value="<?=$point; ?>" /></td>
		</tr>
		<tr>	
			<td></td>
			<td><input type="submit" name="Update" value="Update" class="btn btn-primary"  /></td>
		</tr>			
	</table>		
	</form>
	</div>
<?php	
}
elseif(isset($_REQUEST['Update']))
{
	 $id = $_REQUEST['id'];
	 $catg_name = $_REQUEST['catg_name'];
	 $point = $_REQUEST['point'];
	
	mysqli_query($GLOBALS["___mysqli_ston"], "update ads_category set catg_name = '$catg_name', point = '$point' where id = '$id'");
	
	echo "<script type=\"text/javascript\">";
	echo "window.location = \"index.php?page=category\"";
	echo "</script>"; 
}
elseif($_REQUEST['action'] == 'delete')
{
 	$id = $_REQUEST['sr'];
	mysqli_query($GLOBALS["___mysqli_ston"], "delete from ads_category where id = '$id'");
	echo "<script type=\"text/javascript\">";
	echo "window.location = \"index.php?page=category\"";
	echo "</script>";	
}
/*elseif(isset($_REQUEST['add_new_category']))
{
	echo "<script type=\"text/javascript\">";
	echo "window.location = \"index.php?page=add_category1\"";
	echo "</script>"; 
}*/
else
{
	$qur_cnt = mysqli_query($GLOBALS["___mysqli_ston"], "select * from ads_category");
	$num = mysqli_num_rows($qur_cnt);
	if($num  > 0)
	{
?>
	<!--<div align="right" style="padding:20px;">
		<form method="post">
			<input type="submit" name="add_new_category" value="Add New Category"  class="button"/>
		</form>
	</div>-->
	<div class="ibox-content">
	<table class="table table-bordered"> 
		<thead>
		<tr>
			<th>Sr.</th>
			<th>Name</th>
			<th>Point</th>		
			<th>Edit</th>		
			<th>Delete</th>								
		</tr>
		</thead>
		<?php
		$qurt = mysqli_query($GLOBALS["___mysqli_ston"], "select * from ads_category");
		$sr = 1;
		while($myrow = mysqli_fetch_array($qurt))
		{
			$id = $myrow['id'];
			$catg_name = $myrow['catg_name'];
			$point = $myrow['point'];
		?>
		<form method="post" action="index.php?page=category">
			<input type="hidden" name="id" value="<?=$id; ?>" required/>
			<tr>
				<td><?=$sr; $sr++; ?></td>
				<td><input type="hidden" name="catg_name" value="<?=$catg_name;?>" /><?=$catg_name;?></td>
				<td><input type="hidden" name="point" value="<?=$point; ?>" /><?="$point";?></td>
				<td><input type="submit" name="Edit" value="Edit" class="button"></td>
				<td>
					<a href="index.php?page=category&action=delete&sr=<?=$id; ?>" title="Delete This">
						<img src="images/delete.png">
					</a>
				</td>						
			</tr>
		</form>
<?php 	} ?>		
	</table>
	</div>
<?php 
	}
	else
	{ echo "<B style=\"color:#FF0000; font-size:12pt;\">There Are No Category</B>"; }
} ?>
