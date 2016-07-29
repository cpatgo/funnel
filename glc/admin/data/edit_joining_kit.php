<?php
session_start();
require_once("../config.php");
include("../condition.php");

?>
<p></p>
<?php
if(isset($_POST['edit']))
{

	$kit_id = $_POST['kit_id'];
	$kit_name = $_POST['kit_name'];
	$kit_amount = $_POST['kit_amount'];
	
	mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE products SET products_name = '$kit_name' , prod_amount = '$kit_amount' WHERE id = '$kit_id' ");

	echo "<B><p></p>Joining Kit Update Successfully !</B><p></p>";
}
	 ?>
	<table id=table-example class=table width="500" border="0">  
	<tr>
    <td style="width:110px;" class="message tip"><strong>Joining Kit nane</strong></td>
    <th style="width:110px;" class="message tip">Joining Kit Amount</small></th>
    <th style="width:150px;" class="message tip">Operation</small></th>
  </tr>	
 <?php		
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from products ");
	while($row = mysqli_fetch_array($query))
	{
		$kit_id = $row['id'];
		$kit_name = $row['products_name'];
		$kit_amount = $row['prod_amount'];
?>			
		
		<form name="money" action="index.php?page=edit_joining_kit" method="post">
		<input type="hidden" name="kit_id" value="<?php print $kit_id; ?>"  />
		<tr>
		<td style="width:190px;" class="input-medium"><input style="width:180px;" type="text" name="kit_name" class="input-medium" value="<?php print $kit_name; ?>" /></td>
		<td style="width:120px;" class="input-medium"><input style="width:110px; text-align:right; padding-right:10px;" type="text" name="kit_amount" class="input-small" value="<?php print $kit_amount; ?>" /></td>
		<td class="input-medium"  style="width:100px; text-align:center">
		<input style="width:80px; " id="send" type="submit" name="edit" value="Edit" class="normal-button" /></td>
		</tr>
		</form>
<?php } ?>		
			
	</table>

