<?php
require_once("config.php");
include("condition.php");
include("function/functions.php");
include("function/setting.php");

$id = $_SESSION['dennisn_user_id'];
$dwolla_id = get_user_dwolla_id($id);
$p_mode = get_user_payza_id($id);
$income_class = getInstance('Class_Income');

//payment methods
$method_payza = "Payza";
$method_cheque = "Check";
$method_ach = "ACH";

if (isset($_POST['submit']) && !empty($_REQUEST['request']))
{
    $pay_method = $_POST['pay_mode'];
    if ($_SESSION['sess'] == 1)
    {
        $user_pin = $_REQUEST['user_pin'];
        $current_amount = get_available_funds($id);
        $request_amount = $_REQUEST['request'];
        $request_amount = preg_replace("/([^0-9\\.])/i", "", $request_amount);

        $inc_chk = validate_request_amount($request_amount);
        if ($inc_chk == 1)
        {
            if ($request_amount <= $current_amount)
            {
                $request_date = date('Y-m-d');
                mysqli_query($GLOBALS["___mysqli_ston"], "insert into paid_unpaid (user_id , amount , paid , request_date, paid_date,
				 pay_mode, paid_inform) values ('$id' , '$request_amount' , 0 , '$request_date' , '0000-00-00',
				'$pay_method', '$pay_method') ");

                mysqli_query($GLOBALS["___mysqli_ston"], "update wallet set amount = amount-'$request_amount' where id = '$id' ");

                $w_bal = get_wallet_balance($id);
                insert_wallet_account($id, $id, $request_amount, $request_date, $ac_type[5], $ac_desc[5], 2, $w_bal);

                printf('<script type="text/javascript">window.location="%s/glc/index.php?page=request_transfer&msg=1";</script>', GLC_URL);
                //data_logs($from,$pos,$data_log[6][0],$data_log[6][1],$type_data[1]);
            }
            else
            {
                echo "<div class='alert alert-danger'>$bal_is_not_aval_ur_wallet</div>";
            }
        }
        else
        {
            echo "<div class='alert alert-danger'>$Req_trans_amt</div>";
        }
        $_SESSION['sess'] = 0;
    }
    else
    {
        echo "<script type=\"text/javascript\">";
        echo "window.location = \"index.php?page=request_transfer\"";
        echo "</script>";
    }
}

if(isset($_GET['msg']) && $_GET['msg'] == 1) echo "<div class='alert alert-danger alert-dismissible fade in' role='alert'> <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
    <span aria-hidden='true'>&times;</span>
  </button>$Your_req_send_to_admin</div>";

//Get available funds
$funds = $income_class->get_available_funds($id);
$pending_income = get_pending_payments($id);
$join_date = get_join_date($id);
$payza_set = has_payza_account($id);
$_SESSION['sess'] = 1;

$sql = "select date, user_id from deduct_amount order by id desc limit 1";
$query_date = mysqli_query($GLOBALS["___mysqli_ston"], $sql);
while ($rows = mysqli_fetch_array($query_date))
{
    $deduct_date = $rows[0];
    $ded_user = $rows[1];
    $u_id = explode('-', $ded_user);
}

if ($join_date > $deduct_date)
{
    $withdrwal_mode = 1;
}
elseif ($deduct_date > $join_date)
{
    if (in_array($id, $u_id))
        $withdrwal_mode = 1;
    else
        $withdrwal_mode = 2;
}
else
{
    $withdrwal_mode = 0;
}

// Check requested documents
$user_class = getInstance('Class_User');
$user = $user_class->get_user($id);
$docs = $user_class->get_user_documents($id);
$country = $user[0]['country'];
$identification_status = $tax_status = $company_status = $corporate_status = 0;
$is_company = $user_class->glc_usermeta($id, 'company_name');
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

$required_documents = '<div class="alert alert-danger"><b>Warning!</b> Please submit the required verification information explained in the section <a href="index.php?page=documents"> Required Documents</a>. </div>';
$user_pin = $_REQUEST['user_pin'];

//requests
$pending_payments = sprintf("SELECT * FROM paid_unpaid WHERE amount > 0 AND user_id = %d", $id);
$query_requests = mysqli_query($GLOBALS["___mysqli_ston"], $pending_payments);

if ($withdrwal_mode == 1)
{
    ?>
    <?php
    $msg = $_REQUEST[mg];
    echo "<center>" . $msg . "</center>";
    ?> 

<head>
<style>
span.question {
  cursor: pointer;
  display: inline-block;
  width: 16px;
  height: 16px;
  background-color: #89A4CC;
  line-height: 16px;
  color: White;
  font-size: 13px;
  font-weight: bold;
  border-radius: 8px;
  text-align: center;
  position: relative;
}
span.question:hover { background-color: #3D6199; }
div.tooltip {
  background-color: #3D6199;
  color: White;
  position: absolute;
  left: 25px;
  top: -25px;
  z-index: 1000000;
  width: 250px;
  border-radius: 5px;
}
div.tooltip:before {
  border-color: transparent #3D6199 transparent transparent;
  border-right: 6px solid #3D6199;
  border-style: solid;
  border-width: 6px 6px 6px 0px;
  content: "";
  display: block;
  height: 0;
  width: 0;
  line-height: 0;
  position: absolute;
  top: 40%;
  left: -6px;
}

</style>
</head>

    <div class="row">
        <div class="col-lg-6">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Request Money</h5>
                </div>
                <div class="ibox-content">
                    <div class="alert alert-warning">In order to claim your earnings, you must have at least $100.00 in available funds.</div>
                    <form class="form-horizontal" name="money" action="index.php?page=request_transfer" method="post">
                        <div class="form-group"><label class="col-lg-5 control-label"><?= $Amount; ?></label>

                            <div class="col-lg-5"><input type="text" class="form-control" name="request" value="">
                            </div>
                        </div>
                        <div class="form-group"><label class="col-lg-5 control-label">Request Method</label>
                            <div class="col-lg-5">
                                <div class="radio i-checks">
                                    <!-- <label> <input type="radio" checked="" value="<?php echo $method_payza; ?>" name="pay_mode" required> <i></i> <?php echo $method_payza; ?></label> -->
                                    <label> <input type="radio" checked="" value="<?php echo $method_cheque; ?>" name="pay_mode" required> <i></i> Send a Check </label>
                                    <!-- <label> <input type="radio" checked="" value="<?php echo $method_ach; ?>" name="pay_mode" required> <i></i> Send an ACH </label> -->
                                    <?php
                                    if ($p_mode == "")
                                    {
                                        ?>                                        
                                                        <!--<a href="<?php printf('%s/glc/index.php?page=user_profile', GLC_URL) ?>">
                                                            <span class="label label-danger">!!! Submit Your Payza ID</span>
                                                        </a>-->                                        
                                        <?php
                                    }
                                    else
                                    {
                                        //echo $p_mode;
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>


                        <!-- Payza account -->

                        <?php
                        // if ($p_mode == "")
                        // {
                            ?>
                            <!-- <div class="form-group">
                                <label class="col-lg-5 control-label">Payza Account</label>
                                <div class="col-lg-5">
                                    <input type="email" name="payza" id="inp-payza-account" class="form-control" value="" required />
                                    <br>
                                    <a href="javascript:void(0);">
                                        <span class="label label-danger" id="btn-submit-payza">Submit Payza ID</span>
                                    </a>
                                    <br>
                                    <?php
                                    if (empty($payza)):
                                        printf('<br>Create a free payza account <a href="%s" target="_blank">here</a>.', $payza_referral_link);
                                    endif;
                                    ?> -->
                                    
                                    <!--<a href="<?php printf('%s/glc/index.php?page=user_profile', GLC_URL) ?>">
                                        <span class="label label-danger">!!! Submit Your Payza ID</span>
                                    </a>-->

                                <!-- </div> -->
                            <!-- </div> -->
                            <?php
                        // }
                        ?>
                        <!-- End Payza account -->


                        
                        <div class="form-group">
                            <div class="col-lg-offset-5 col-lg-5">
                                <?php if($canrequset): ?>
                                    <input type="submit" name="submit" id="btn-submit-request" value="Request My Commissions"  class="btn btn-primary" />
                                <?php endif; ?>
                            </div>                            
                        </div>
                        <div>
                        
                            <?php
                            if (!$canrequset)
                            {
                                echo $required_documents;
                            }
                            ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Available Funds &nbsp;</h5>
                    <p title="These are the total funds you can request to be paid. 
It is the sum of all approved funds less all funds 
previously requested to be paid."><span class="question">?</span></p>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins text-success">$<?php echo number_format($funds, 2); ?></h1>
                    <div class="stat-percent font-bold text-success">USD</div>

                </div>
            </div>
            <div class="ibox float-e-margins m-t">
                <div class="ibox-title" style="height: 68px">
                    <h5>Requested Payment(s) &nbsp;</h5>
                    <p title="These are the funds requested to be paid."><span class="question">?</span></p>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins text-warning">$<?php echo number_format($pending_income, 2); ?></h1>
                    <div class="stat-percent font-bold text-warning">USD</div>

                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label pull-right">note</span>
                    <h5>Information</h5>
                </div>
                <div class="ibox-content">
                    <p>Requests for payment in the current month must be received by the 14th of the month. Once your request for payment is approved, you will be paid on the 30th, or if this is not a banking day, the next banking day.</p>
                    <p>In order to claim your earnings, you must have at least $100.00 in available funds.</p>
                    <!-- <p>Your commission(s) payment will be made into your Payza account.</p> -->
                </div>
            </div>
        </div>
    </div>

    <!-- MONEY REQUEST TABLE -->
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>Money Requests</h5>
        </div>
        <div class="ibox-content">  
            <table class="table table-striped table-bordered table-hover dataTableMoneyRequest">
                <thead>
                    <tr>
                        <th scope="text-center">ID</th>
                        <th scope="text-center">Requested Amount</th>
                        <th scope="text-center">Date Requested</th>
                        <th scope="text-center">Payment Type</th>
                        <th scope="text-center">Date Paid</th>
                        <th scope="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($rowr = mysqli_fetch_array($query_requests))
                    {
                        ?>
                        <tr>
                            <td><?php echo $rowr['id']; ?></td>
                            <td><?php echo $rowr['amount']; ?></td>
                            <td><?php echo date('Y-m-d', strtotime($rowr['request_date'])); ?></td>
                            <td><?php echo $rowr['pay_mode']; ?></td>
                            <td><?php echo ($rowr['paid'] == 1) ? date('Y-m-d', strtotime($rowr['paid_date'])) : ""; ?></td>
                            <td><?php echo ((int) $rowr['paid'] == 1) ? 'Paid' : 'Pending'; ?></td>
                        </tr>
                        <?php
                    }
                    ?>	
                </tbody>
            </table>
        </div>
    </div>
    <!-- END MONEY REQUEST TABLE -->
    <?php
}
else
{
    echo "<B style=\"color:#FF0000; font-size:12pt;\">At the present time you do not have any commissions approved.  Please allow 5 business days for any pending commission to be approved.  If you believe that there is an error in approving your commissions within the 5 day administrative period allowed, please contact us at info@globallearningcenter.net.  Please be sure to provide the facts about your question and we will send you a response within 3 business days.  In the meantime if you want to speed up your payment, please go to the documents section and upload your required documents. This will speed up the process of processing your commissions. Note: No Commissions can be requested for payment until the required documents have been received and approved.</B>";
}
?>
<!-- JQUERY -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script type="text/javascript">
    $(function () {
        $('.dataTableMoneyRequest').DataTable({
            "iDisplayLength": 100,
            responsive: true,
            "dom": 'T<"clear">lfrtip',
            "tableTools": {
                "sSwfPath": "js/plugins/dataTables/swf/copy_csv_xls_pdf.swf"
            }
        });

        $("#btn-submit-payza").click(function () {
            // do an ajax submission for payza account
            var ajax_url = "<?php printf('%s/glc/admin/ajax/', GLC_URL); ?>";

            $.ajax({
                url: ajax_url + "payza_account.php",
                data: {payza_account: $("#inp-payza-account").val()},
                type: "post",
                dataType: 'json',
                success: function (data) {
                    if (data.success)
                    {
                        alert("Payza submitted successfully!");
                        location.reload();
                    }
                    else
                    {
                        alert(data.error_message)
                    }
                },
                error: function () {

                }
            });
        });
        
        // $("#btn-submit-request").click(function(){
        //     var is_set = '<?=$payza_set?>';
        //     if (is_set !== '')
        //     {
        //         return true;
        //     }
        //     else
        //     {
        //         return false;
        //     }
        // });
    });
</script>
<!-- END JQUERY -->