<?php
session_start();

include("condition.php");
require_once("../config.php");
require_once("../function/setting.php");
require_once("../function/functions.php");
include("function/binary_layout/display.php");

switch ($_POST['search_level']) {
    case "board":
        $level = 1;
        break;
    case "board_second":
        $level = 2;
        break;
     case "board_third":
        $level = 3;
        break;
	 case "board_fourth":
        $level = 4;
        break;
	 case "board_fifth":
        $level = 5;
        break;
}
?>
<style type="text/css">
	.right-side{
		float: right;
	}
	.right-side a{
		padding: 8px;
	}
</style>
<div class="ibox-title">
	<h5>Active Boards - Stage <?php echo $level; ?></h5>
	<span class="right-side">
		<a href="" data-board="board" class="btn-primary lv">Stage 1</a>
		<a href="" data-board="board_second" class="btn-primary lv">Stage 2</a>
		<a href="" data-board="board_third" class="btn-primary lv">Stage 3</a>
		<a href="" data-board="board_fourth" class="btn-primary lv">Stage 4</a>
		<a href="" data-board="board_fifth" class="btn-primary lv">Stage 5</a>
		<br>
	</span>
</div>
<div class="ibox-content">
<form action="index.php?page=active_board" method="post" id="search_level_form"></form>
<?php
if(isset($_POST['submit']) || isset($_POST['search_level_link']))
{
	if(isset($_POST['submit']) || isset($_POST['search_level_link']))
		$board_level = $_SESSION['session_search_level'] = $_POST['search_level'];
	else
		$board_level = $_SESSION['session_search_level'];

	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM ".$board_level." WHERE mode = 1 ");
	$totalrows = mysqli_num_rows($query);
	if($totalrows == 0)
	{
		echo "<B style=\"color:#FF0000; font-size:12pt;\">There is no information to show !</B>"; 
	}
	else {
	echo "
		<table class=\"table table-striped table-bordered dataTablesActiveBoards\">
			<thead>	
			<tr><th>Total Active Boards:</th>	<th colspan=2>$totalrows Boards</th></tr>
			<tr>
				<th>Board Id</th>
				<th>Members</th>		
				<th>Date</th>
			</tr>
			</thead><tbody>"; 
		 	
		$lvl_brd = $board_level+1;
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * from ".$board_level." WHERE mode = 1");	
		
		while($id_row = mysqli_fetch_array($query))
		{
			$board_id = $id_row['board_id'];
			$top_username = get_user_name($id_row['pos1']);
			$username = get_user_name($id);
			$date = $id_row['date'];
			$time = date('H:i:s m-d-Y' , strtotime($id_row['time']));
?>
			<tr>
				<td>
					<form action="index.php?page=active_board" method="post">
						<input type="hidden" name="board_id" value="<?=$board_id; ?>"  />
						<input type="hidden" name="board_type" value="<?=$board_level; ?>" />
						<input type="submit" name="show_brd" style="background:none; color: #0066BF; border:none; text-decoration:underline;" value="8SV00i00<?=$board_id; ?>"  />
					</form>	
				</td>
				<td>
				.:
				<?=$top_username; ?>
				<?php 
				//addon by Virginia
				echo " :. &nbsp;&nbsp;&nbsp;<span style='color: #1ab394;'>.:";
				echo get_user_name($id_row['pos2']);
				echo " :: ";
				echo get_user_name($id_row['pos3']);
				echo " :.</span>&nbsp;&nbsp;&nbsp;<span style='color: #0e9aef;'>.: ";
				echo get_user_name($id_row['pos4']);
				echo " :: ";
				echo get_user_name($id_row['pos5']);
				echo " :: ";
				echo get_user_name($id_row['pos6']);
				echo " :: ";
				echo get_user_name($id_row['pos7']);
				echo " :.</span> ";
				?>
				</td>
				<td><?=$date; ?></td>
			</tr>
<?php	}
		echo "</tbody></table></div>";	
		
	}	
}	
elseif(isset($_POST['show_brd']) || isset($_POST['search_level_link']))
{
	$board_id = $_POST['board_id'];
	$board_type = $_POST['board_type'];
	if(isset($_POST['search_level_link'])) $board_type = $_POST['search_level_link'];
	$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM ".$board_type." WHERE board_id = '$board_id' ");
	while($row = mysqli_fetch_array($query))
	{
		$bb_level = $row['level']; 
		?>
			
			<div style="float:right; padding-right:0px; padding-right:50px;">
				<form action="data/print_board.php" target="_new" method="post">
					<input type="hidden" name="bbid" value="<?php print $board_id; ?>"  />
					<input type="submit" name="Submit" class="button" value="Print" />
				</form>
			</div>
				 <a href="index.php?page=active_board"><img src="images/b.png" style="height:50px; width:50px" /></a><br />
			<?php 
		for($i=1 ; $i <8; $i++)
		{
			$pos[$i] = $bbid = $row['pos'.$i];
			$id_query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM users WHERE id_user = '$bbid' ");
			while($id_row = mysqli_fetch_array($id_query))
			{
				$type = $id_row['type'];
				$position[$i] = get_user_pos($id);
				$date[$i] = $id_row['date'];
				$gender[$i] = $id_row['gender'];
				$user_name[$i] = $id_row['username'];
				$real_parent_u[$i] = get_user_name($id_row['real_parent']);
				$chld = get_qualification_summary($bbid,$bb_level,$board_id);
				$cnt = count($chld);
				for($k = 0 ; $k < $cnt; $k++)
				{
					$real_child[$i][$k] = $chld[$k][0];
					$real_info_time[$i][$k] = $chld[$k][1];
				}
				$img[$i] = get_img($type);
				$name[$i] = $id_row['f_name']." ".$id_row['l_name']; 
				$parent_u_name[$i] = get_user_name($id_row['parent_id']);	
			}
		}
	}		
	
	$page = "index.php?page=active_board";
	display($pos,$page,$img,$user_name,$parent_u_name,$name,$position,$date,$gender,$real_parent_u,$real_child,$bb_level,$real_info_time,$inserting_board,$rt,$board_type);	
}
else
{  ?> 
<form action="index.php?page=active_board" method="post">
<table class="table table-bordered">
	<thead><tr><th>Board Name</th>
		<td>
			<select name="search_level" id="search_level">
				<option value="board"><?=$setting_board_name[1]; ?></option>
				<option value="board_second"><?=$setting_board_name[2]; ?></option>
				<option value="board_third"><?=$setting_board_name[3]; ?></option>
				<option value="board_fourth"><?=$setting_board_name[4]; ?></option>
				<option value="board_fifth"><?=$setting_board_name[5]; ?></option>
				<option value="board_sixth"><?=$setting_board_name[6]; ?></option>
			</select>	
		</td>
	</tr>
	</thead>
	<tr>
		<td colspan="2" class="text-center">
			<input type="submit" name="submit" value="Show" class="btn btn-primary" />
		</td>
	</tr>
</table>
</form>
</div>
<?php
}


function get_img($type)	
{ 
	if($type == 'B') { $imges = "b"; }
	if($type == 'C') { $imges = "d"; }
	return $imges;
}
	
			

function get_board_break_id($id)
{
	$q = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_break where user_id = '$id' order by id desc limit 1 ");
	while($r = mysqli_fetch_array($q))
	{
		$b_b_id[0] = $r['board_b_id'];
		$b_b_id[1] = $r['level'];
	}
	return $b_b_id;
}

function get_send_level_board($id)
{
	$q = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board_break where user_id = '$id'  order by id desc limit 1 ");
	while($r = mysqli_fetch_array($q))
	{
		$second_level_board = $r['board_b_id'];
	}
	return $second_level_board;
}

function get_qualification_summary($id,$level,$u_bbb_id)
{
	$h = 0;
	if($id != 0)
	{
		$q = mysqli_query($GLOBALS["___mysqli_ston"], "select * from board where board_id = '$u_bbb_id' ");
		while($rrr = mysqli_fetch_array($q))
		{
			$time = $rrr['time'];
			$mode = $rrr['mode'];
			if($mode == 0)
				$time_qur = "and time <= '$time'";
			else
				$time_qur = "";	
		}	
			
		$query = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM board_break WHERE qualified_id = '$id' and 
		level = '$level' $time_qur group by user_id  order by time ");
		while($row = mysqli_fetch_array($query))
		{
			$username_id = $row['user_id'];
			$username[$h][0] = get_user_name($username_id);
			$username[$h][1] = $row['time'];
			$h++;
		}
	}
	return $username;	
}		
?>
<script type="text/javascript">
	$(document).ready(function(){
		$('body').on('click', '.lv', function(e){
			e.preventDefault();
			var board = $(this).data('board');
			var lv_form = $('body').find('#search_level_form');

			lv_form.append('<input type="hidden" name="search_level" value="'+board+'">');
			lv_form.append('<input type="hidden" name="search_level_link" value="'+1+'">');
			lv_form.submit();

		});
	});	
</script>
