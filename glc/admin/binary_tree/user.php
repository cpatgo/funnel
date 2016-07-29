<?php

$name = $_REQUEST['name'];
$date = $_REQUEST['date'];
$left_child = $_REQUEST['left'];
$right_child = $_REQUEST['right'];
$mode = $_REQUEST['mode'];
$position = $_REQUEST['position'];
$user_name = $_REQUEST['user_name'];
$gender = $_REQUEST['gender'];
?>
      <div id="pop-up">

<table class="MyTable" border="1" bordercolor="#FFFFFF" style="border-collapse:collapse; margin:6px;" cellpadding="0" cellspacing="0" width="400">
          <tr>
            <td width="113">Distributor ID </td>
            <td colspan="3">2</td>
            </tr>
          <tr>
            <td>Distributor Name</td>
            <td colspan="3"><?php print $name; ?></td>
            </tr>
          <tr>
            <td height="25" colspan="4" bgcolor="#E3E8EC"><p><strong>Date Of Joining : </strong><strong>27/07/2011 </strong></p></td>
            </tr>
          <tr>
            <td>Sponsor ID </td>
            <td colspan="3"><?php print $user_name; ?></td>
            </tr>
          <tr>
            <td>Sponsor Name</td>
            <td colspan="3">Edata online.biz</td>
            </tr>
          <tr>
            <td>Binary ID</td>
            <td colspan="3">1</td>
            </tr>
          <tr>
            <td>Binary Name</td>
            <td colspan="3">Edata online.biz</td>
            </tr>
          <tr>
            <td>Total Left ID</td>
            <td width="115"><?php print $left_child; ?></td>
            <td width="119">Total Right ID </td>
            <td width="53"><?php print $right_child; ?></td>
          </tr>
          <tr>
            <td height="25" colspan="4" bgcolor="#E3E8EC"><p><strong>SelfTopUp&nbsp; :</strong><strong> 10000 </strong></p></td>
            </tr>
          <tr>
            <td>Total Left TopUpAmount </td>
            <td>20000</td>
            <td>Total Right TopUpAmount</td>
            <td>20000</td>
          </tr>
          <tr>
            <td>Total Left Alpha TopUpAmount </td>
            <td>10000.00</td>
            <td>Total Right Alpha TopUpAmount</td>
            <td>10000.00</td>
          </tr>
          <tr>
            <td>Total Left Beta TopUpAmount </td>
            <td>10000.00</td>
            <td>Total Right Beta TopUpAmount</td>
            <td>10000.00</td>
          </tr>
        </table>
		


      </div>
