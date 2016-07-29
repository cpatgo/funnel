<!-- DATE FILTER -->
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>Filters</h5>
            </div>
            <div class="ibox-content">
            <div class="row">
                <form method="post" role="form" id="payza_form">    
                    <div class="form-inline">                   
                        <div id="data_1" class="form-group">    
                            <label>From </label>
                            <div class="input-group date">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input type="text" value="<?php echo (isset($_POST['start_date'])) ? $_POST['start_date'] : $date_from ?>" class="form-control" name="start_date" id="start_date">
                            </div>
                        </div>
                        <div id="data_2" class="form-group">    
                            <label>To </label>
                            <div class="input-group date">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input type="text" value="<?php echo (isset($_POST['end_date'])) ? $_POST['end_date'] : $date_to ?>" class="form-control" name="end_date" id="end_date">
                                <input type="hidden" name="pay_comm" value="1">
                            </div>
                        </div>
                        <input type="submit" value="Search" name="search" class="btn btn-primary">
                    </div>
                </form>
            </div>
            </div>
        </div>
    </div>
</div>
<!-- END DATE FILTER -->

<!-- MONEY REQUEST LIST -->
<button class="btn btn-primary" id="pay_comm_button">PROCESS PAYMENT</button>
<a href="<?php echo $_SERVER['HTTP_REFERER'] ?>" class="btn btn-primary">CANCEL</a>
<form id="pay_commissions" method="post">
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>Pending Payments</h5>
        </div>
        <div class="ibox-content">  
            <table class="table table-striped table-bordered table-hover dataTablePayzaPay">
                <thead>
                    <tr>
                        <th></th>
                        <th class="text-center">Username</th>
                        <th class="text-center">Amount</th>
                        <th class="text-center">Requested Date</th>
                        <th class="text-center">Payza Account</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($pending_payments as $key => $value) { ?>
                    <tr class="text-center">
                        <?php $has_payza = empty($value['payza_account']) ? false : true; ?>
                        <td><input type="checkbox" name="pay_user[]" value="<?php echo $value['id'] ?>" <?php echo ($has_payza) ? 'checked="checked"' : 'disabled';?> ></td>
                        <td><?php echo $value['username'] ?></td>
                        <td><?php echo $value['amount'] ?></td>
                        <td><?php echo date('F d, Y', strtotime($value['request_date'])); ?></td>
                        <td><?php echo ($has_payza) ? $value['payza_account'] : 'No payza account'; ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <input type="hidden" name="date_from" value="<?php echo $date_from ?>">
            <input type="hidden" name="date_to" value="<?php echo $date_to ?>">
            <input type="hidden" name="submit_payment" value="1">
        </div>
    </div>
</form>
<!-- END OF MONEY REQUEST LIST -->