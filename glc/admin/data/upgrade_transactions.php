<?php 
    $payment_class = getInstance('Class_Payment');
    $merchant_class = getInstance('Class_Merchant');
    $user_class = getInstance('Class_User');
    $merchants = $merchant_class->get_all();

    $default_merchant_provider = (!isset($_POST['selected_merchant'])) ? glc_option('default_merchant_provider') : $_POST['selected_merchant'];
    $start_date = (!isset($_POST['start_date']) || empty($_POST['start_date'])) ? '' : date('Y-m-d H:i', strtotime($_POST['start_date']));
    $end_date = (!isset($_POST['end_date']) || empty($_POST['end_date'])) ? '' : date('Y-m-d H:i', strtotime($_POST['end_date']));
    
    $cutoff = $merchant_slug = array();
    foreach ($merchants as $key => $value) {
        $cutoff[$value['id']] = glc_option(sprintf('%s_cutoff', $value['slug']));
        $merchant_slug[$value['id']] = $value['slug'];
    }

    $total_payments = 0;
    $sdate = '';
    $edate = '';
?>
<!-- <script type="text/javascript" src="../js/bower_components/jquery/jquery.min.js"></script> -->
<script type="text/javascript" src="../js/bower_components/moment/min/moment.min.js"></script>
<script type="text/javascript" src="../js/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script type="text/javascript" src="../js/bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
<!-- <link rel="stylesheet" href="../js/bower_components/bootstrap/dist/css/bootstrap.min.css" />
<link rel="stylesheet" href="../js/bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" /> -->

<form method="post" role="form">    
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Select Merchant</h5>
                </div>
                <div class="ibox-content">
                    <div class="row">
                        <div class='col-md-5'>
                            <div class="form-group">
                                <?php
                                    foreach ($merchants as $key => $value) {
                                        $selected = 'checked="checked"';
                                        printf('<input type="radio" name="selected_merchant" value="%d" %s /> <label> %s </label> ', $value['id'], ((int)$value['id'] === (int)$default_merchant_provider) ? $selected : '', $value['merchant']);
                                    }
                                ?>     
                                <br>
                                Start Date
                                <input type='text' id='datetimepicker7' class="form-control" name="start_date" value="<?php (!empty($start_date)) ? $start_date : '' ?>"/>
                                <br>
                                End Date
                                <input type='text' id='datetimepicker6' class="form-control" name="end_date" value="<?php (!empty($end_date)) ? $end_date : '' ?>" />
                                <br>
                                <input type="submit" value="Submit" name="search" class="btn btn-primary">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<div class="ibox float-e-margins">
    <div class="ibox-title">
        <h5>Credit Card Payment Transactions</h5>
    </div>
    <div class="ibox-content">  
        <table class="table table-striped table-bordered table-hover dataTableCreditCardPayment">
            <thead>
                <tr>
                    <th colspan="6"><?php echo !empty($start_date) ? sprintf('%s ~ %s', $start_date, $end_date) : '';?></th>
                </tr>
                <tr>
                    <th class="text-center">Name</th>
                    <th class="text-center">Username</th>
                    <th class="text-center">Transaction Date</th>
                    <th class="text-center">Transaction Amount</th>
                    <th class="text-center">Transaction Number</th>
                    <th class="text-center">Payment Type</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th colspan="6" style="text-align:right">Total:</th>
                </tr>
            </tfoot>
            <tbody>
                <?php foreach($merchants as $key => $value) { 
                    $transaction = array();
                    if((int)$value['id'] !== (int)$default_merchant_provider) continue;

                    if((int)$default_merchant_provider === 2 || (int)$default_merchant_provider === 3):
                        $transaction = $payment_class->upgrade_authorize_ipn($start_date, $end_date, 2);
                    elseif((int)$default_merchant_provider === 1):
                        $transaction = $payment_class->upgrade_edata_ipn($start_date, $end_date, 2);
                    elseif((int)$default_merchant_provider === 4):
                        $transaction = $payment_class->upgrade_echeck_ipn($start_date, $end_date, 2);
                    endif;
                    if(empty($transaction)) continue;
                    $user_id_field = 'user_id';
                    if((int)$default_merchant_provider === 4) $user_id_field = 'customerid';

                    foreach ($transaction as $tkey => $tvalue) {
                        $user = $user_class->get_user($tvalue[$user_id_field]);
                        $user = $user[0];
                        $total_payments += $transaction['amount'];
                        ?>  
                        <tr class="text-center">
                            <td><?php printf('%s %s', $user['f_name'], $user['l_name']) ?></td>
                            <td><?php echo $user['username'] ?></td>
                            <td><?php if(!empty($tvalue)) echo $tvalue['date_created'] ?></td>
                            <td><?php if(!empty($tvalue)) echo number_format($tvalue['amount'], 2) ?></td>
                            <td><?php if(!empty($tvalue)) echo $tvalue['transactionid'] ?></td>
                            <td><?php echo $value['merchant'] ?></td>
                        </tr>
                    <?php } ?>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<!-- JQUERY -->
<script type="text/javascript">
    $(function() {
        var mass_payment_url = "<?php printf('%s/glc/admin/index.php?page=mass_payment', GLC_URL); ?>";
        var ajax_url = "<?php printf('%s/glc/admin/ajax/', GLC_URL); ?>";
        var template_url = "<?php printf('%s/glc/admin/template/', GLC_URL); ?>";

        $('.dataTableCreditCardPayment').DataTable({
            "footerCallback": function ( row, data, start, end, display ) {
                var api = this.api(), data;
     
                // Remove the formatting to get integer data for summation
                var intVal = function ( i ) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '')*1 :
                        typeof i === 'number' ?
                            i : 0;
                };
     
                // Total over all pages
                total = api
                    .column( 3 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
     
                // Total over this page
                pageTotal = api
                    .column( 3, { page: 'current'} )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
     
                // Update footer
                $( api.column( 3 ).footer() ).html(
                    '$'+pageTotal +' ( $'+ total +' total)'
                );
            },
            "iDisplayLength": 100,
            responsive: true,
                "dom": 'T<"clear">lfrtip',
                "tableTools": {
                    "sSwfPath": "js/plugins/dataTables/swf/copy_csv_xls_pdf.swf"
            },
        });
    });

    $('#datetimepicker6').datetimepicker({
        format: 'YYYY-MM-DD HH:mm'
    });
    $('#datetimepicker7').datetimepicker({
        format: 'YYYY-MM-DD HH:mm',
        useCurrent: false //Important! See issue #1075
    });
</script>
<!-- END JQUERY -->