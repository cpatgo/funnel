<!-- Pay Commissions -->
<div class="form-inline">            
    <form method="post" role="form">    
        <input type="hidden" name="pay_comm" value="1">
        <button class="btn btn-primary">Pay Commissions</button>
    </form>
</div>
<br>
<!-- End Pay Commissions -->

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
                                <input type="hidden" id="batch_no" name="batch_no" value="<?php echo isset($_POST['batch_no']) && !empty($_POST['batch_no']) ? $_POST['batch_no'] : '' ?>">
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
<div class="ibox float-e-margins">
    <div class="ibox-title">
        <h5>Mass Payment History</h5>
    </div>
    <div class="ibox-content">  
        <table class="table table-striped table-bordered table-hover dataTablePayzaTrans">
            <thead>
                <tr>
                    <th class="text-center">Transaction Date</th>
                    <th class="text-center">Batch Number</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($batchnumbers as $key => $value) { ?>
                <tr class="text-center">
                    <td><?php echo date('Y-m-d H:i', strtotime($value['ap_transactiondate'])) ?></td>
                    <td><a class="batch_payza" data-batchno="<?php printf('%s', $value['ap_batchnumber']) ?>"><?php echo $value['ap_batchnumber'] ?></a></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>