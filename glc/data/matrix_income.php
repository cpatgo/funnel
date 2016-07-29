<?php
session_start();
include("condition.php");
include("function/setting.php");

$user_id = $_SESSION['dennisn_user_id'];
$income_class = getInstance('Class_Income');

// Check requested documents
$user_class = getInstance('Class_User');
$user = $user_class->get_user($user_id);
$docs = $user_class->get_user_documents($user_id);
$country = $user[0]['country'];
$identification_status = $tax_status = $company_status = $corporate_status = 0;
$is_company = $user_class->glc_usermeta($user_id, 'company_name');
$user_type = (empty($is_company)) ? 'individual' : 'company';
$canrequset = 0;

// Determine whether the user has submitted the correct form 
foreach($docs as $row) {
    if((int)$row['approved'] === 1):
        if($row['doctype'] == 1) $identification_status = 1;
        if($row['doctype'] == 2) $tax_status = 1;
        if($row['doctype'] == 3) $company_status = 1;
        if($row['doctype'] == 4) $corporate_status = 1;
    endif;
}

// Determine whether the user can request or not
if($user_type === 'individual') {
    //If account is individual document must be tax form
    if($tax_status === 1) $canrequset = 1;
} else if($user_type === 'company') {
    if($country === 'United States' or $country == 'US') {
        //If acount is company and within US, document must be corporate tax number
        if($corporate_status == 1) $canrequset = 1; 
    } else {
        //If account is company and outside US, document must be w8ben
        if($company_status == 1) $canrequset = 1;
    }
}

$newp = $_GET['p'];
$plimit = "15";

$que = mysqli_query($GLOBALS["___mysqli_ston"], "select * from income where user_id = '$user_id' AND approved <> 2");
$totalrows = mysqli_num_rows($que);
if ($totalrows > 0)
{
    $total_income = 0;
    $approved_income = 0;
    $pending_income = 0;
    $reserved = 0;
    $reserve_month = glc_option('reserve_month');
    $query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from income where user_id = '$user_id' AND approved <> 2");
    while ($row = mysqli_fetch_array($query))
    {
        $date = date("m/d/Y", strtotime($row['date']));
        $time = date('m/d/Y H:i:s', $row['time']);
        $effectiveDate = strtotime("-1 days", time());
        //$status = (($row['time'] > $effectiveDate))?'<span class="label label-danger">pending</span>':'<span class="label label-primary">available</span>';
        $status = (($row['approved'] != 1)) ? '<span class="label label-danger">pending</span>' : '<span class="label label-primary">available</span>';
        $amount = $row['amount'];
        $total_income += $row['amount'];

        //Compute rolling reserve
        $rolling_reserve = $income_class->get_rolling_reserve($row['id']);
        if(!empty($rolling_reserve)):
            $rolling_reserve = $rolling_reserve[0];
            //Check if current date is N months after the commission was created
            $date_created = date('Y-m', strtotime(sprintf('+%d months', $reserve_month), strtotime($rolling_reserve['date_created'])));
            //If current date is equal or less than the reserve created date, deduct the reserve amount
            if(date('Y-m') <= $date_created):
                $row['amount'] = (float)$row['amount'] - $rolling_reserve['reserve'];
                $reserved += $rolling_reserve['reserve'];
            endif;
        endif;
     
        if (($row['approved'] != 1))
        {
            $pending_income += $row['amount'];
        }
        else
        {
            $approved_income += $row['amount'];
        }
        
        $admin_tax = $row['admin_tax'];
        $board_type = $row['board_type'];
        if ($board_type == 1)
            $brd_type = $First_Break;
        else
            $brd_type = $Break_Again;

        $board_naam = $setting_board_name[$row['level']];       
        $j = 1;
    }
    ?>   

    <div class="ibox-content">
        <?//put a table ?>
        <table width="100%">
            <tr>
                <td style="border: 1px solid #DCDCDC;">
                    <table width="100%" border="0" class="table1">
                        <tr>
                            <td align="center">
                        <center>
                            <div class="row white-bg dashboard-header text-success">
                                <div class="col-sm-12">
                                    <h2>
                                        <b>TOTAL SALES COMMISSIONS</b><br>
                                        <b>$<?php echo number_format($total_income,2); ?></b>
                                    </h2>
                                    <ul class="list-group clear-list m-t">
                                        <li class="list-group-item text-success">
                                            <h3>APPROVED COMMISSIONS</h3>
                                            <h2><b>$<?php echo number_format($approved_income,2); ?></b></h2>
                                        </li>
                                        <li class="list-group-item text-success">
                                            <h3>PENDING COMMISSIONS</h3>
                                            <h2><b>$<?php echo number_format($pending_income,2); ?></b></h2>
                                        </li>
                                        <li class="list-group-item text-success">
                                            <h3>RESERVE COMMISSIONS</h3>
                                            <h2><b>$<?php echo number_format($reserved,2); ?></b></h2>
                                        </li>
                                    </ul>
                                </div>
                            </div> 
                        </center>
                </td>
            </tr>
        </table>
    </td>
    <td style="border: 1px solid #DCDCDC;">
        <table width="90%" border="0" align="center">
            <tr>
                <td align="center">	
                    <br />
            <center>
                <div class="alert alert-warning"> 
                    In order to claim your earnings, you must have at least $100.00 in available funds.
                </div></center>
            <br /><br />
            <div align="center">
                <a class="btn btn-primary btn-large" href="index.php?page=request_transfer"><?= $Request_commissions; ?> <i class="fa fa-chevron-right"></i> </a>
            </div>
            <br /><br />
            <?php
            $sql = "select * from documents where doctype = '1' and approved = '1' and user_id = " . $user_id;
            $docs = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
            $r = mysqli_num_rows($docs);

            $sql2 = "select * from documents where doctype = '2' and approved = '1' and user_id = " . $user_id;
            $docs2 = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
            $r2 = mysqli_num_rows($docs2);

            if (!$canrequset) {
                ?>
                <div class="alert alert-danger">
                    <strong>Warning!</strong> Please submit the required verification information explained in the section <a href="index.php?page=documents">Required Documents</a>.
                </div>
        <?php
    }
    ?>
    </td>
    </tr>
    </table>
    </td>
    </tr>
    </table>


    </div>

    <div class="ibox-content">	

        <table class="table table-striped table-bordered table-hover dataTables">
            <thead>
                <tr>
                    <th class="text-center"><?= $Date; ?></th> 
                    <th class="text-center"><?= $Board_Name; ?></th> 
                    <th class="text-center">Board Position</th> 
                    <th class="text-center"><?= $Income; ?></th>
                    <th class="text-center">Reserve</th>
                    <th class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total_income = 0;
                $approved_income = 0;
                $pending_income = 0;
                $reserve = 0;
                $query = mysqli_query($GLOBALS["___mysqli_ston"], "select * from income where user_id = '$user_id' and amount > 0 ");
                while ($row = mysqli_fetch_array($query))
                {
                    //Get rolling reserve 
                    $rolling_reserve = $income_class->get_rolling_reserve($row['id']);
                    if(!empty($rolling_reserve)) $reserve = $rolling_reserve[0]['reserve'];
                 
                    $date = date("m/d/Y", strtotime($row['date']));
                    $time = date('m/d/Y H:i:s', $row['time']);
                    $effectiveDate = strtotime("-1 days", time());
                    //$status = (($row['time'] > $effectiveDate))?'<span class="label label-danger">pending</span>':'<span class="label label-primary">available</span>';
                    $status = (($row['approved'] != 1)) ? '<span class="label label-danger">pending</span>' : '<span class="label label-primary">available</span>';
                    $amount = $row['amount'];
                    if (($row['approved'] != 1))
                    {
                        $pending_income += $row['amount'];
                    }
                    else
                    {
                        $approved_income += $row['amount'];
                    }
                    $total_income += $row['amount'];
                    $admin_tax = $row['admin_tax'];
                    $board_type = $row['board_type'];
                    if ($board_type == 1)
                        $brd_type = $First_Break;
                    else
                        $brd_type = $Break_Again;

                    $board_naam = $setting_board_name[$row['level']];

                    printf('<tr align="center">');
                    printf('<td>%s</td>', $time);
                    printf('<td>%s</td>', $board_naam);
                    printf('<td>%s</td>', ((int)$row['type'] === 3) ? 'Step 2' : 'Step 3');
                    printf('<td>%s</td>', number_format($amount, 2));
                    printf('<td>%s</td>', number_format($reserve, 2));
                    printf('<td>%s</td>', $status);
                    printf('</tr>');
                    
                    $j = 1;
                }

                //funds
                $query = mysqli_query($GLOBALS["___mysqli_ston"], "select t1.amount,t2.date from wallet as t1 
	inner join users as t2 on t1.id = t2.id_user and t1.id = '$id' ");
                while ($row = mysqli_fetch_array($query))
                {
                    $curr_amnt = $row[0];
                    $join_date = $row[1];
                }
                $total_requested = 0;
                ?>
            </tbody></table>
    </div>



    <?php
}
else
{
    print "<B style=\"color:#ff0000; font-size:12pt;\">$No_info_to_show</B>";
}
?>
