<?php
$payza = getInstance('Class_Payza');

if(!isset($_POST['start_date']) || !isset($_POST['end_date'])):
    $date_from = (date('d') <= 15) ? date('m/24/Y', strtotime('-1 months')) : date('m/9/Y');
    $date_to = (date('d') <= 15) ? date('m/8/Y') : date('m/23/Y');
else:
    $date_from = $_POST['start_date'];
    $date_to = $_POST['end_date'];
endif;

if(isset($_POST['batch_no']) && !empty($_POST['batch_no'])):
    // SHOW TRANSACTIONS BASED ON BATCH NO
    $batch_no = $_POST['batch_no'];
    $transactions = $payza->get_batch_transaction($batch_no, date('Y-m-d', strtotime($date_from)), date('Y-m-d', strtotime($date_to)));
    ?>
    <a href="<?php echo $_SERVER['HTTP_REFERER'] ?>" class="btn btn-primary">BACK TO MASS PAY TRANSACTIONS</a>
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5 style="text-align: right">Batch Payment Transactions</h5>
        </div>
        <div class="ibox-content">  
            <table class="table table-striped table-bordered table-hover dataTablePayzaBatch">
                <caption><h4>Batch Number: <?php echo $transactions[0]['ap_batchnumber'] ?></h4></caption>
                <thead>
                    <tr>
                        <th class="text-center">Reference #</th>
                        <th class="text-center">Username</th>
                        <th class="text-center">Receiver Email</th>
                        <th class="text-center">Amount Paid</th>
                        <th class="text-center">Transaction Status</th>
                        <th class="text-center">Transaction Code</th>
                        <th class="text-center">Transaction Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($transactions as $key => $value) { ?>
                    <tr class="text-center">
                        <td><?php echo $value['ap_referencenumber'] ?></td>
                        <td><?php echo $value['username'] ?></td>
                        <td><?php echo $value['ap_receiveremail'] ?></td>
                        <td><?php echo number_format($value['ap_amount'], 2) ?></td>
                        <td><?php echo $value['ap_returncodedescription'] ?></td>
                        <td><?php echo $value['ap_returncode'] ?></td>
                        <td><?php echo date('Y-m-d H:i', strtotime($value['ap_transactiondate'])) ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php
    // END SHOW TRANSACTIONS BASED ON BATCH NO
elseif(isset($_POST['pay_comm']) && $_POST['pay_comm'] == '1'):
    $pending_payments = $payza->get_pending_payments(date('Y-m-d', strtotime($date_from)), date('Y-m-d', strtotime($date_to)));
    include_once('mass_payment_pay.php');
elseif(isset($_POST['submit_payment']) && $_POST['submit_payment'] == 1):
    include_once('mass_payment_submit.php');
else:
    // SHOW PAYZA MASS PAY TRANSACTIONS
    $batchnumbers = $payza->get_batchnumbers(date('Y-m-d', strtotime($date_from)), date('Y-m-d', strtotime($date_to)));
    include_once('mass_payment_history.php');
    // END SHOW PAYZA MASS PAY TRANSACTIONS
endif; 
?>

<!-- JQUERY -->
<script type="text/javascript">
    $(function() {
        var mass_payment_url = "<?php printf('%s/glc/admin/index.php?page=mass_payment', GLC_URL); ?>";
        var ajax_url = "<?php printf('%s/glc/admin/ajax/', GLC_URL); ?>";
        var template_url = "<?php printf('%s/glc/admin/template/', GLC_URL); ?>";
        $('#start_date').datepicker();
        $('#end_date').datepicker();

        $("body").on('click', '.batch_payza', function(e){
            e.preventDefault();
            var batch_no = $(this).data('batchno');
            $('body').find('#batch_no').val(batch_no);
            $('body').find('#payza_form').submit();
        });

        $('.dataTablePayzaBatch').DataTable({
            "iDisplayLength": 100,
            responsive: true,
                "dom": 'T<"clear">lfrtip',
                "tableTools": {
                    "sSwfPath": "js/plugins/dataTables/swf/copy_csv_xls_pdf.swf"
            }
        });

        $('.dataTablePayzaTrans').DataTable({
            "iDisplayLength": 100,
            responsive: true,
                "dom": 'T<"clear">lfrtip',
                "tableTools": {
                    "sSwfPath": "js/plugins/dataTables/swf/copy_csv_xls_pdf.swf"
            }
        });        

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

        $('body').on('click', '#submit_payment', function(e){
            e.preventDefault();
            $('#payza_error').text('');
            var fields = $('body').find('#submit_payment_form').serialize();

            if(typeof $('#payza_admin_account').val() === 'undefined' || $('#payza_admin_account').val() === ''){ 
                $('#payza_error').text('Please enter your payza account.'); 
            } else if(typeof $('#payza_admin_password').val() === 'undefined' || $('#payza_admin_password').val() === ''){ 
                $('#payza_error').text('Please enter your payza password.'); 
            } else {
                $.ajax({
                    method: "post",
                    url: ajax_url+"payza.php",
                    data: {
                        'action':'submit_mass_payment',
                        'fields': fields
                    },
                    dataType: 'json',
                    success:function(result) {
                        console.log(result);
                        console.log(result);
                        if(result.RETURNCODE == 100){
                            alert(result.DESCRIPTION);
                            window.location.href = mass_payment_url;
                        } else {
                            alert(result.DESCRIPTION);    
                        }
                    },
                    error: function(errorThrown){
                        console.log(errorThrown);
                    }
                });
            }
        });
    });
</script>
<!-- END JQUERY -->