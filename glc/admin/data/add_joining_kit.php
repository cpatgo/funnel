<?php
session_start();
require_once("../config.php");
include("condition.php");

if(isset($_POST['submit']))
{
	$kit_name = $_POST['kit_name'];
	$kit_amount = $_POST['kit_amount'];

	mysqli_query($GLOBALS["___mysqli_ston"], "insert into products (products_name , prod_amount) values ('$kit_name' , '$kit_amount') ");
	
	echo "<B>Joining Kit Successfully Added</B>";
}
else
{	 		
?>
<center>
	<table width="400" border="0" height="200" cellspacing="2" cellpadding="2">
	<form name="my_edit_form" action="index.php?page=add_joining_kit" method="post">
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td><strong>Kit Name</strong></td>
    <td><input type="text" name="kit_name"  /></td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td width="200"><strong>Kit Amount</strong></td>
    <td><input type="text" name="kit_amount"  /></td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="center"><input type="submit" name="submit" value="submit" class="button" /></td>
  </tr>
  </form>
</table>
<?php	
}	
?>


