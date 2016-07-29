<?php
ini_set("display_errors",'off');
$name = $_REQUEST['name'];
$date = $_REQUEST['date'];
$left_child = $_REQUEST['left'];
$right_child = $_REQUEST['right'];
$mode = $_REQUEST['mode'];
$position = $_REQUEST['position'];
$user_name = $_REQUEST['user_name'];
$gender = $_REQUEST['gender'];
$sponser_name = $_REQUEST['sponser_name'];
?>

<table hspace=0 vspace=0 cellspacing=0 cellpadding=0 border=0 >
<tr><td width=100><span style="color:#990000">Sponser</span></td><td width=100><span style="color:#009900"><?php print $sponser_name; ?></span></td></tr>
<!--<tr><td width=100><span style="color:#990000">Right Position</span></td><td width=100><span style="color:#009900"><?php print $right_child; ?></span></td></tr>
<tr><td width=100><span style="color:#990000">Leg</span></td><td width=100><span style="color:#009900"><?php print $position; ?></span></td></tr>
<tr><td width=100><span style="color:#990000">Mode</span></td><td width=100><span style="color:#009900"><?php print $mode; ?></span></td></tr>-->
<tr><td width=100><span style="color:#990000">Gender</span></td><td width=100><span style="color:#009900"><?php print $gender; ?></span></td></tr>
<tr><td width=100><span style="color:#990000">Date</span></td><td width=100><span style="color:#009900"><?php print $date; ?></span></td></tr>

</table>

