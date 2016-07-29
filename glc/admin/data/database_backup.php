<?php

include("condition.php");
include("function/export_all_database_into_sql.php");

if(isset($_POST['submit']))
{
	backup_tables($backup_path);
	print "<font color=\"#003A75\" size=\"+2\">Database Backup Completed Successfully</font>";
}
else
{ ?>

<table width="50%" border="0">
<form name="myform" action="index.php?page=database_backup" method="post">
  <tr>
    <td colspan="2">&nbsp;</td>
  
  </tr>
  <tr>
    <td colspan="2"><p align="center"><input type="submit" name="submit" value="Get Backup" class="button"  /></p></td>
  </tr>
</table>

<?php  } ?>


