<?php
$commission_class = getInstance('Class_Income');
$start = (!isset($_POST['start']) || empty($_POST['start'])) ? '' : DateTime::createFromFormat('m/d/Y', $_POST['start']);
$end = (!isset($_POST['end']) || empty($_POST['end'])) ? '' : DateTime::createFromFormat('m/d/Y', $_POST['end']);

if(!empty($start)) $start = $start->format('Y-m-d');
if(!empty($end)) $end = $end->format('Y-m-d');

$commissions = array();
foreach (range(1,5) as $key => $value) {
	$commissions[$value]['commission'] = $commission_class->get_income_by_level($value, $start, $end);
	$commissions[$value]['total_sales'] = $commission_class->get_total_sales($value, $start, $end);
}
?>
<!-- Date Filter -->
<div class="ibox-title">
    <h5>Date Filter</h5>
</div>
<div class="ibox-content">
	<div class="row">
		<form method="post" role="form">	
			<div class="form-inline">					
				<div id="data_1" class="form-group">	<label>From</label>
					<div class="input-group date">
					
						<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
						<input type="text" value="<?php if(!empty($start)) echo date('m/d/Y', strtotime($start)) ?>" class="form-control" name="start">
					</div>
				</div>
				<div id="data_1" class="form-group">	<label>To</label>
					<div class="input-group date">
					
						<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
						<input type="text" value="<?php if(!empty($end)) echo date('m/d/Y', strtotime($end)) ?>" class="form-control" name="end">
					</div>
				</div>
				<input type="submit" value="Search" name="search" class="btn btn-primary">
			</div>
		</form>
	</div>
</div>

<!-- Commission summary table -->
<?php if(isset($_POST['msg']) && !empty($_POST['msg'])) printf('<div class="alert alert-success">%s</div>', $_POST['msg']); ?>
<div class="ibox-title">
    <h5>Commission Summary <?php if(!empty($start) && !empty($end)) printf('- %s to %s', date('F d, Y', strtotime($start)), date('F d, Y', strtotime($end))) ?></h5>
</div>
<div class="ibox-content">
    <table class="table table-striped table-bordered table-hover CommDataTables" >
        <thead>
            <tr>
                <th></th>
                <th>Step 2 Commissions</th>
                <th>Step 3 Commissions</th>
                <th>Total Sales</th>
                <th>Total Commissions</th>
                <th>Total Sales - Total Commission<br></th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </tfoot>
        <tbody>
            <?php foreach ($commissions as $level => $commission_level) {
            	$step2_total = $step3_total = $total_comm = 0;
            	$total_sales = $commission_level['total_sales'][0]['amount'];

            	printf("<tr>");
            	printf('<td>Stage %d</td>', $level);

                foreach ($commission_level['commission'] as $key => $levelvalues) {
                	$total_comm += $levelvalues['amount'];
                	if((int)$levelvalues['type'] === 3):
                	 	$step2_total += $levelvalues['amount'];
                	else:
                		$step3_total += $levelvalues['amount'];
                	endif;
                }

                printf('<td>$%0.2f</td>', $step2_total);
                printf('<td>$%0.2f</td>', $step3_total);
                printf('<td>$%0.2f</td>', $total_sales);
                printf('<td>$%0.2f</td>', $total_comm);
                printf('<td %s>$%0.2f</td>', ($total_sales - $total_comm < 0) ? 'class="red-text"' : "", $total_sales - $total_comm);
                printf("</tr>");
            } ?>
        </tbody>
    </table>
</div>
<!-- Page-Level Scripts -->
<script>
    $(document).ready(function() {
        var upgrade_user_url = "<?php printf('%s/glc/admin/index.php?page=commission_summary', GLC_URL); ?>";
        var ajax_url = "<?php printf('%s/glc/admin/ajax/', GLC_URL); ?>";

        $('#start').datepicker({dateFormat: "mm/dd/yy"});
        $('#end').datepicker({dateFormat: "mm/dd/yy"});

        $('.CommDataTables').DataTable( {
			"iDisplayLength": 100,
			 responsive: true,
            "dom": 'T<"clear">lfrtip',
            "tableTools": {
                "sSwfPath": "js/plugins/dataTables/swf/copy_csv_xls_pdf.swf"
            },
			"footerCallback": function ( row, data, start, end, display ) {
	            var api = this.api(), data;
	 
	            // Remove the formatting to get integer data for summation
	            var intVal = function ( i ) {
	                return typeof i === 'string' ?
	                    i.replace(/[\$,]/g, '')*1 :
	                    typeof i === 'number' ?
	                        i : 0;
	            };

	            $( api.column( 0 ).footer() ).html(
	                'TOTAL'
	            );

	            // Total over all pages
				total = api
					.column( 1 )
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					} );
				// Total over this page
				pageTotal = api
					.column( 1, { page: 'current'} )
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					}, 0 );
				// Update footer
				$( api.column( 1 ).footer() ).html(
					'$'+pageTotal +' ( $'+ total +' total)'
				);

				// Total over all pages
				total = api
					.column( 2 )
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					} );
				// Total over this page
				pageTotal = api
					.column( 2, { page: 'current'} )
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					}, 0 );
				// Update footer
				$( api.column( 2 ).footer() ).html(
					'$'+pageTotal +' ( $'+ total +' total)'
				);

				// Total over all pages
				total = api
					.column( 3 )
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					} );
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

				// Total over all pages
				total = api
					.column( 4 )
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					} );
				// Total over this page
				pageTotal = api
					.column( 4, { page: 'current'} )
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					}, 0 );
				// Update footer
				$( api.column( 4 ).footer() ).html(
					'$'+pageTotal +' ( $'+ total +' total)'
				);

				// Total over all pages
				total = api
					.column( 5 )
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					} );
				// Total over this page
				pageTotal = api
					.column( 5, { page: 'current'} )
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					}, 0 );
				// Update footer
				$( api.column( 5 ).footer() ).html(
					'$'+pageTotal +' ( $'+ total +' total)'
				);
	        }
		} );
    });
</script>