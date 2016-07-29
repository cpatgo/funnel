<?php
$payza = getInstance('Class_Payza');

if(!isset($_POST['start_date']) || !isset($_POST['end_date'])):
    $date_from = (date('d') <= 15) ? date('m/24/Y', strtotime('-1 months')) : date('m/9/Y');
    $date_to = (date('d') <= 15) ? date('m/8/Y') : date('m/23/Y');
else:
    $date_from = $_POST['start_date'];
    $date_to = $_POST['end_date'];
endif;
$pending_payments = $payza->get_pending_payments(date('Y-m-d', strtotime($date_from)), date('Y-m-d', strtotime($date_to)));
?>

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
<button class="btn btn-primary" id="export" data-list="selected">EXPORT SELECTED</button>
<button class="btn btn-primary" id="export" data-list="all">EXPORT ALL</button>
<a href="<?php echo $_SERVER['HTTP_REFERER'] ?>" class="btn btn-primary">CANCEL</a>
<form id="pay_commissions" action="ajax/export_pending_payments.php" method="post">
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
                        <th class="text-center">Payment Method</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($pending_payments as $key => $value) { ?>
                    <tr class="text-center">
                        <?php //$has_payza = empty($value['payza_account']) ? false : true; ?>
                        <td><input type="checkbox" name="pay_user[]" value="<?php echo $value['id'] ?>" <?php //echo ($has_payza) ? 'checked="checked"' : 'disabled';?> checked="checked"></td>
                        <td><?php echo $value['username'] ?></td>
                        <td><?php echo $value['amount'] ?></td>
                        <td><?php echo date('F d, Y', strtotime($value['request_date'])); ?></td>
                        <td><?php echo $value['pay_mode']?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <input type="hidden" name="date_from" value="<?php echo $date_from ?>">
            <input type="hidden" name="date_to" value="<?php echo $date_to ?>">
            <input type="hidden" name="list" id="list" value="">
        </div>
    </div>
</form>
<!-- END OF MONEY REQUEST LIST -->

<!-- JQUERY -->
<script type="text/javascript">
    $(function() {
        var mass_payment_url = "<?php printf('%s/glc/admin/index.php?page=mass_payment', GLC_URL); ?>";
        var ajax_url = "<?php printf('%s/glc/admin/ajax/', GLC_URL); ?>";
        var template_url = "<?php printf('%s/glc/admin/template/', GLC_URL); ?>";
        $('#start_date').datepicker();
        $('#end_date').datepicker();

        $('.dataTablePayzaPay').DataTable({
            "iDisplayLength": 100,
            responsive: true,
                "dom": 'T<"clear">lfrtip',
                "tableTools": {
                    "sSwfPath": "js/plugins/dataTables/swf/copy_csv_xls_pdf.swf"
            },
            columnDefs: [ {
                orderable: false,
                className: 'select-checkbox',
                targets:   0
            } ],
            select: {
                style:    'os',
                selector: 'td:first-child'
            },
            order: [[ 1, 'asc' ]]
        });   

        $('body').on('click', '#pay_comm_button', function(e){
            e.preventDefault();
            $('body').find('#pay_commissions').submit();
        });

        $('body').on('click', '#export', function(e){
            e.preventDefault();
            var list = $(this).data('list');
            $('body').find('#list').val(list);
            $('body').find('#pay_commissions').submit();
        });
    });
</script>
<!-- END JQUERY -->

