<?php 
    $payment_class = getInstance('Class_Payment');
    $merchant_class = getInstance('Class_Merchant');
    $merchants = $merchant_class->get_all();

    $default_merchant_provider = (!isset($_POST['selected_merchant'])) ? glc_option('default_merchant_provider') : $_POST['selected_merchant'];
    $start_date = (!isset($_POST['start_date']) || empty($_POST['start_date'])) ? '' : date('Y-m-d H:i', strtotime($_POST['start_date']));
    $end_date = (!isset($_POST['end_date']) || empty($_POST['end_date'])) ? '' : date('Y-m-d H:i', strtotime($_POST['end_date']));
    
    $cutoff = $merchant_slug = array();
    foreach ($merchants as $key => $value) {
        $cutoff[$value['id']] = glc_option(sprintf('%s_cutoff', $value['slug']));
        $merchant_slug[$value['id']] = $value['slug'];
    }
    $memberships = $payment_class->get_credit_card_payments($merchant_slug[$default_merchant_provider]);

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
                <?php foreach($memberships as $key => $value) { 
                    if($value['payment_type'] === 'authorize_net' || $value['payment_type'] === 'authorize_net_2'):
                        $transaction = $payment_class->get_authorize_ipn($value['user_id'], $start_date, $end_date, 1);
                    elseif($value['payment_type'] === 'e_data'):
                        $transaction = $payment_class->get_edata_ipn($value['user_id'], $start_date, $end_date, 1);
                    elseif($value['payment_type'] === 'xpressdrafts'):
                        $transaction = $payment_class->get_echeck_ipn($value['user_id'], $start_date, $end_date, 1);
                    endif;
                    if(empty($transaction)) continue;
                    $transaction = $transaction[0];

                    $pricing = array(2 => 'first_board_join', 3 => 'second_board_join', 4 => 'third_board_join', 5 => 'fourth_board_join');
                    $total_payments += $transaction['amount'];
                ?>    
                <tr class="text-center">
                    <td><?php printf('%s %s', $value['f_name'], $value['l_name']) ?></td>
                    <td><?php echo $value['username'] ?></td>
                    <td><?php if(!empty($transaction)) echo $transaction['date_created'] ?></td>
                    <td><?php if(!empty($transaction)) echo number_format($transaction['amount'], 2) ?></td>
                    <td><?php if(!empty($transaction)) echo $transaction['transactionid'] ?></td>
                    <td><?php echo $payment_class->get_merchant_name($value['payment_type']) ?></td>
                </tr>
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