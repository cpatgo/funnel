<?php
session_start();
include("condition.php");
require_once("../config.php");
require_once("../function/setting.php");
require_once("../function/functions.php");
include("../function/binary_layout/display.php");
?>
<div class="ibox-content">
<table class="table table-bordered"> 
	<thead>
	<tr>
		<th class="text-center">No.</th>
		<th class="text-center">User Id</th>
		<th class="text-center">Join Date</th>
		<th class="text-center">Join Time</th>
		<th class="text-center">Level/Board</th>
	</tr>
	</thead>
	<tbody>
<?php
	$my_query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from users order by id_user desc limit 50");
	$num = mysqli_num_rows($my_query);
	$srno = 1;
	while($my_row = mysqli_fetch_array($my_query))
	{
		$username = $my_row['username'];
		$id_user = $my_row['id_user'];
		$date = date("m/d/Y", $my_row['time']);
		$time = date('h:i:s a', $my_row['time']);
		
		$b_b_id = bord_break_id($id_user,$num);
		$new_board = new_board($b_b_id,$num);
		$bd_name = "first_board_name";
		$bord_name_c_1 = my_bords_name($bd_name);
		
		echo "
			<tr class=\"text-center\">
				<td>$srno</td>
				<td>$username</td>
				<td>$date</td>
				<td>$time</td>
				<td>$bord_name_c_1 - $new_board</td>
			</tr>";
		$srno++;	
	}
?>
</tbody>	
</table>
</div>

<?php
function bord_break_id($id , $no)
{
	for($k = 0; $k < $no; $k++)
	{
		$q = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_break where user_id = '$id[$k]' ");
		while($row = mysqli_fetch_array($q))
		{
			 $board_b_1[] = $row['board_b_id'];
		}
	}
	return $board_b_1;
}

function new_board($b_id,$num)
{
	for($i = 0; $i < $num; $i++)
	{
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM board WHERE board_id = '$b_id[$i]' AND mode = 1");	
		while($row = mysqli_fetch_array($query))
		{
			$board_idaa = $row['board_id'];
				
		}	
	}
	return $board_idaa;	
}

function my_bords_name($nme)
{
	$q = mysqli_query($GLOBALS["___mysqli_ston"], "select $nme from setting");
	while($r = mysqli_fetch_array($q))
	{
		$board_name = $r[$nme];
	}
	return $board_name;
}	
	
	 //$q =mysql_query("SELECT t1.board_b_id AS board_b_1, t2.board_b_id AS board_b_2, t3.board_b_id AS board_b_3 FROM board_break AS t1 INNER JOIN board_break_second AS t2 ON t1.user_id = t2.user_id AND t1.user_id ='$id' LEFT JOIN board_break_third AS t3 ON t3.user_id ='$id' GROUP BY t1.board_b_id, t2.board_b_id");?>