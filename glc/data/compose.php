<?php
session_start();
$id = $_SESSION['dennisn_user_id'];

print "<br>".$_SESSION['error'].$_SESSION['success']."<br>";
$_SESSION['error'] = $_SESSION['success'] = '';

?>
<div class="ibox-content">	
<form name="message" action="index.php?page=compose_post" method="post">
<table class="table table-bordered">
	<input type="hidden" name="id" value=""  />
	<input type="hidden" name="id_user" value=""  />
	<thead><tr><th colspan="2"><?=$Compose_Message;?></th></tr></thead>
	<tbody>
	<tr>
		<td width="38%"><?=$Title;?></td>
		<td><input type="text" name="title" /></td>
	</tr>
	<tr>
		<td><?=$username1?></td>
		<td><input type="text" name="username" value="<?=$Admin;?>" readonly="readonly" /></td>
	</tr>
	<tr>
		<td><?=$Message;?></td>
		<td><textarea name="message" style="width:210px;"></textarea></td>
	</tr>
	<tr>
		<td colspan="2" align="center">
			<input type="submit" value="<?=$Send;?>" name="submit" class="btn btn-primary" />
		</td>
	</tr>
	</tbody>
</table>
</form>