<!-- PAYMENT DETAILS -->
<form id="submit_payment_form" method="post">
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>Please check the list and submit the form if everything is carefully verified.</h5>
        </div>
        <div class="ibox-content">  
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="text-center">Username</th>
                        <th class="text-center">Amount</th>
                        <th class="text-center">Requested Date</th>
                        <th class="text-center">Payza Account</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($_POST['pay_user'] as $key => $value) { ?>
                    <?php 
                        $user = $payza->get_user_via_paid_unpaid_id($value); 
                        $user = $user[0];
                    ?>
                    <tr class="text-center">
                        <td>
                            <?php echo $user['username'] ?>
                            <input type="hidden" name="ids[]" value="<?php printf('%s', $value);?>">
                        </td>
                        <td><?php echo $user['amount'] ?></td>
                        <td><?php echo date('F d, Y', strtotime($user['request_date'])); ?></td>
                        <td><?php echo $user['payza_account']; ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <span id="payza_error" style="color:red;"></span><br>
            <input type="email" name="payza_admin_account" id="payza_admin_account" placeholder="Payza Email Address">
            <input type="password" name="payza_admin_password" id="payza_admin_password" placeholder="Payza API Password">
            <button class="btn btn-primary" id="submit_payment">SUBMIT PAYMENT</button>
            <a href="<?php printf('%s/glc/admin/index.php?page=mass_payment', GLC_URL) ?>" class="btn btn-primary">CANCEL</a>
        </div>
    </div>
</form>
<!-- PAYMENT DETAILS -->