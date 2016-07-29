<?php	

function message_alert($id)
{
	$messages = "<table width=600 border=0> <tr>
					<th class=\"message tip\" width=200>Title</th>
					<th class=\"message tip\" width=200>Message</th>
					<th class=\"message tip\" width=200>Message By</th></tr>";

	$query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from user_message where message_to = '$id' ");
	$num = mysqli_num_rows($query);
	if($num != 0)
	{
		$pnums = ceil ($totalrows/$plimit);
		if ($newp==''){ $newp='1'; }
			
		$start = ($newp-1) * $plimit;
		$starting_no = $start + 1;
		
		if ($totalrows - $start < $plimit) { $end_count = $totalrows;
		} elseif ($totalrows - $start >= $plimit) { $end_count = $start + $plimit; }
			
			
		
		if ($totalrows - $end_count > $plimit) { $var2 = $plimit;
		} elseif ($totalrows - $end_count <= $plimit) { $var2 = $totalrows - $end_count; }  
	
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from user_message where message_to = '$id' ");
		while($row = mysqli_fetch_array($query))
		{
			$title = $row['title'];
			$message = $row['message'];
			$by = $row['message_by'];
			$messages .="<tr><td class=\"input-small\" style=\"padding-left:80px\">$title</small></td><td class=\"input-small\" style=\"padding-left:70px\">$message</small></td><td class=\"input-small\" style=\"padding-left:70px\">$by</small></td></tr>";
		}
	}
	else
	{
		$messages .="<tr><td class=form_data colspan=3>There Is No Messages to show!</td></tr>";
	}	
	$messages .="</table>";
	return $messages;
}


function request_message($id,$title,$message,$message_to)
{
	mysqli_query($GLOBALS["___mysqli_ston"], "insert into user_message (title , message , message_by , message_to) values ('$title' , '$message' , '$id' , '$message_to' ) ");

}	
	
