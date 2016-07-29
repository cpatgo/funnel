<?php
ini_set("display_errors",'off');
session_start();
include("../config.php");
if(isset($_SESSION['dennisn_admin_login']) && $_SESSION['dennisn_admin_login'] != 1)
{
	include("login.php");
	die;
}
?>
<!--<script type="text/javascript">
if(window.console.firebug)  {
     document.body.innerHTML = "PLEASE DO NOT USE FIREBUG"
};
</script>-->
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Global Learning Center</title>

<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="font-awesome/css/font-awesome.css" rel="stylesheet">

<!-- Morris -->
<link href="css/plugins/morris/morris-0.4.3.min.css" rel="stylesheet">

<!-- Data Tables -->
<link href="css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">
<link href="css/plugins/dataTables/dataTables.responsive.css" rel="stylesheet">
<link href="css/plugins/dataTables/dataTables.tableTools.min.css" rel="stylesheet">

<link href="css/plugins/datapicker/datepicker3.css" rel="stylesheet">
<link href="css/plugins/iCheck/custom.css" rel="stylesheet">

<link href="css/animate.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<link href="css/plugins/datapicker/datepicker3.css" rel="stylesheet">
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<!-- custom css -->
<link rel="stylesheet" href="css/custom.css" />
<script src="js/jquery-2.1.1.js"></script>
<script src="js/plugins/datapicker/bootstrap-datepicker.js"></script>

</head>
<body>
<div id="wrapper">
	<?php include "left.php"; ?>

	<div id="page-wrapper" class="gray-bg dashbard-1">
		<?php include "top.php"; ?>
		<?php include "middle.php"; ?>
	</div>
</div>

    <!-- Mainly scripts -->
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <!-- Flot -->
    <script src="js/plugins/flot/jquery.flot.js"></script>
    <script src="js/plugins/flot/jquery.flot.tooltip.min.js"></script>
    <script src="js/plugins/flot/jquery.flot.spline.js"></script>
    <script src="js/plugins/flot/jquery.flot.resize.js"></script>
    <script src="js/plugins/flot/jquery.flot.pie.js"></script>

    <!-- Peity -->
    <script src="js/plugins/peity/jquery.peity.min.js"></script>
    <script src="js/demo/peity-demo.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="js/inspinia.js"></script>
    <script src="js/plugins/pace/pace.min.js"></script>

    <!-- jQuery UI -->
    <script src="js/plugins/jquery-ui/jquery-ui.min.js"></script>

	<!-- Data Tables -->
    <script src="https://cdn.datatables.net/1.10.9/js/jquery.dataTables.min.js"></script>
    <script src="js/plugins/dataTables/dataTables.bootstrap.js"></script>
    <script src="js/plugins/dataTables/dataTables.responsive.js"></script>
    <script src="js/plugins/dataTables/dataTables.tableTools.min.js"></script>

	<!-- Data picker -->
   <script src="js/plugins/datapicker/bootstrap-datepicker.js"></script>

	<script>
        $(document).ready(function() {
			$('.dataTables').DataTable( {
				responsive: true,
				"iDisplayLength": 100,
                "dom": '<"top"p><"clear"><"bottom"Tlfrtip>',
                "tableTools": {
                    "sSwfPath": "js/plugins/dataTables/swf/copy_csv_xls_pdf.swf"
                },
				"order": [[ 2, "desc" ]],
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

			$('.pendingCommDatatable').DataTable({
				 responsive: true,
				 "iDisplayLength": 100,
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

					// Total over all pages
					total = api
						.column( 7 )
						.data()
						.reduce( function (a, b) {
							return intVal(a) + intVal(b);
						} );

					// Total over this page
					pageTotal = api
						.column( 7, { page: 'current'} )
						.data()
						.reduce( function (a, b) {
							return intVal(a) + intVal(b);
						}, 0 );

					// Update footer
					$( api.column( 7 ).footer() ).html(
						'$'+pageTotal +' ( $'+ total +' total)'
					);
					// Total over all pages
					total = api
						.column( 8 )
						.data()
						.reduce( function (a, b) {
							return intVal(a) + intVal(b);
						} );

					// Total over this page
					pageTotal = api
						.column( 8, { page: 'current'} )
						.data()
						.reduce( function (a, b) {
							return intVal(a) + intVal(b);
						}, 0 );

					// Update footer
					$( api.column( 8 ).footer() ).html(
						'$'+pageTotal +' ( $'+ total +' total)'
					);
					// Total over all pages
					total = api
						.column( 9 )
						.data()
						.reduce( function (a, b) {
							return intVal(a) + intVal(b);
						} );

					// Total over this page
					pageTotal = api
						.column( 9, { page: 'current'} )
						.data()
						.reduce( function (a, b) {
							return intVal(a) + intVal(b);
						}, 0 );

					// Update footer
					$( api.column( 9 ).footer() ).html(
						'$'+pageTotal +' ( $'+ total +' total)'
					);
					// Total over all pages
					total = api
						.column( 10 )
						.data()
						.reduce( function (a, b) {
							return intVal(a) + intVal(b);
						} );

					// Total over this page
					pageTotal = api
						.column( 10, { page: 'current'} )
						.data()
						.reduce( function (a, b) {
							return intVal(a) + intVal(b);
						}, 0 );

					// Update footer
					$( api.column( 10 ).footer() ).html(
						'$'+pageTotal +' ( $'+ total +' total)'
					);
					// Total over all pages
					total = api
						.column( 11 )
						.data()
						.reduce( function (a, b) {
							return intVal(a) + intVal(b);
						} );

					// Total over this page
					pageTotal = api
						.column( 11, { page: 'current'} )
						.data()
						.reduce( function (a, b) {
							return intVal(a) + intVal(b);
						}, 0 );

					// Update footer
					$( api.column( 11 ).footer() ).html(
						'$'+pageTotal +' ( $'+ total +' total)'
					);
					// Total over all pages
					total = api
						.column( 12 )
						.data()
						.reduce( function (a, b) {
							return intVal(a) + intVal(b);
						} );

					// Total over this page
					pageTotal = api
						.column( 12, { page: 'current'} )
						.data()
						.reduce( function (a, b) {
							return intVal(a) + intVal(b);
						}, 0 );

					// Update footer
					$( api.column( 12 ).footer() ).html(
						'$'+pageTotal +' ( $'+ total +' total)'
					);
					// Total over all pages
					total = api
						.column( 13 )
						.data()
						.reduce( function (a, b) {
							return intVal(a) + intVal(b);
						} );

					// Total over this page
					pageTotal = api
						.column( 13, { page: 'current'} )
						.data()
						.reduce( function (a, b) {
							return intVal(a) + intVal(b);
						}, 0 );

					// Update footer
					$( api.column( 13 ).footer() ).html(
						'$'+pageTotal +' ( $'+ total +' total)'
					);

					// Total over all pages
					total = api
						.column( 14 )
						.data()
						.reduce( function (a, b) {
							return intVal(a) + intVal(b);
						} );

					// Total over this page
					pageTotal = api
						.column( 14, { page: 'current'} )
						.data()
						.reduce( function (a, b) {
							return intVal(a) + intVal(b);
						}, 0 );

					// Update footer
					$( api.column( 14 ).footer() ).html(
						'$'+pageTotal +' ( $'+ total +' total)'
					);
				}
			});

			$('.dataTablesMovement').DataTable( {
				 responsive: true,
				 "iDisplayLength": 100,
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

					// Total over all pages
					total = api
						.column( 7 )
						.data()
						.reduce( function (a, b) {
							return intVal(a) + intVal(b);
						} );

					// Total over this page
					pageTotal = api
						.column( 7, { page: 'current'} )
						.data()
						.reduce( function (a, b) {
							return intVal(a) + intVal(b);
						}, 0 );

					// Update footer
					$( api.column( 7 ).footer() ).html(
						'$'+pageTotal +' ( $'+ total +' total)'
					);
					// Total over all pages
					total = api
						.column( 8 )
						.data()
						.reduce( function (a, b) {
							return intVal(a) + intVal(b);
						} );

					// Total over this page
					pageTotal = api
						.column( 8, { page: 'current'} )
						.data()
						.reduce( function (a, b) {
							return intVal(a) + intVal(b);
						}, 0 );

					// Update footer
					$( api.column( 8 ).footer() ).html(
						'$'+pageTotal +' ( $'+ total +' total)'
					);
					// Total over all pages
					total = api
						.column( 9 )
						.data()
						.reduce( function (a, b) {
							return intVal(a) + intVal(b);
						} );

					// Total over this page
					pageTotal = api
						.column( 9, { page: 'current'} )
						.data()
						.reduce( function (a, b) {
							return intVal(a) + intVal(b);
						}, 0 );

					// Update footer
					$( api.column( 9 ).footer() ).html(
						'$'+pageTotal +' ( $'+ total +' total)'
					);
					// Total over all pages
					total = api
						.column( 10 )
						.data()
						.reduce( function (a, b) {
							return intVal(a) + intVal(b);
						} );

					// Total over this page
					pageTotal = api
						.column( 10, { page: 'current'} )
						.data()
						.reduce( function (a, b) {
							return intVal(a) + intVal(b);
						}, 0 );

					// Update footer
					$( api.column( 10 ).footer() ).html(
						'$'+pageTotal +' ( $'+ total +' total)'
					);
					// Total over all pages
					total = api
						.column( 11 )
						.data()
						.reduce( function (a, b) {
							return intVal(a) + intVal(b);
						} );

					// Total over this page
					pageTotal = api
						.column( 11, { page: 'current'} )
						.data()
						.reduce( function (a, b) {
							return intVal(a) + intVal(b);
						}, 0 );

					// Update footer
					$( api.column( 11 ).footer() ).html(
						'$'+pageTotal +' ( $'+ total +' total)'
					);
					// Total over all pages
					total = api
						.column( 12 )
						.data()
						.reduce( function (a, b) {
							return intVal(a) + intVal(b);
						} );

					// Total over this page
					pageTotal = api
						.column( 12, { page: 'current'} )
						.data()
						.reduce( function (a, b) {
							return intVal(a) + intVal(b);
						}, 0 );

					// Update footer
					$( api.column( 12 ).footer() ).html(
						'$'+pageTotal +' ( $'+ total +' total)'
					);
					// Total over all pages
					total = api
						.column( 13 )
						.data()
						.reduce( function (a, b) {
							return intVal(a) + intVal(b);
						} );

					// Total over this page
					pageTotal = api
						.column( 13, { page: 'current'} )
						.data()
						.reduce( function (a, b) {
							return intVal(a) + intVal(b);
						}, 0 );

					// Update footer
					$( api.column( 13 ).footer() ).html(
						'$'+pageTotal +' ( $'+ total +' total)'
					);

					// Total over all pages
					total = api
						.column( 14 )
						.data()
						.reduce( function (a, b) {
							return intVal(a) + intVal(b);
						} );

					// Total over this page
					pageTotal = api
						.column( 14, { page: 'current'} )
						.data()
						.reduce( function (a, b) {
							return intVal(a) + intVal(b);
						}, 0 );

					// Update footer
					$( api.column( 14 ).footer() ).html(
						'$'+pageTotal +' ( $'+ total +' total)'
					);
				}
			} );

			$('.dataTablesNewMembers').DataTable( {
				"iDisplayLength": 100,
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

					// Total 1 over all pages
					total = api
						.column( 1 )
						.data()
						.reduce( function (a, b) {
							return intVal(a) + intVal(b);
						} );


					// Update footer
					$( api.column( 1 ).footer() ).html(total);

					// Total 2 over all pages
					total = api
						.column( 2 )
						.data()
						.reduce( function (a, b) {
							return intVal(a) + intVal(b);
						} );


					// Update footer
					$( api.column( 2 ).footer() ).html(total);

					// Total 3 over all pages
					total = api
						.column( 3 )
						.data()
						.reduce( function (a, b) {
							return intVal(a) + intVal(b);
						} );


					// Update footer
					$( api.column( 3 ).footer() ).html(total);

					// Total 4 over all pages
					total = api
						.column( 4 )
						.data()
						.reduce( function (a, b) {
							return intVal(a) + intVal(b);
						} );


					// Update footer
					$( api.column( 4 ).footer() ).html(total);

					// Total 5 over all pages
					total = api
						.column( 5 )
						.data()
						.reduce( function (a, b) {
							return intVal(a) + intVal(b);
						} );


					// Update footer
					$( api.column( 5 ).footer() ).html(total);

					// Total 6 over all pages
					total = api
						.column( 6 )
						.data()
						.reduce( function (a, b) {
							return intVal(a) + intVal(b);
						} );


					// Update footer
					$( api.column( 6 ).footer() ).html(total);

					// Total 7 over all pages
					total = api
						.column( 7 )
						.data()
						.reduce( function (a, b) {
							return intVal(a) + intVal(b);
						} );


					// Update footer
					$( api.column( 7 ).footer() ).html(total);

					// Total 8 over all pages
					total = api
						.column( 8 )
						.data()
						.reduce( function (a, b) {
							return intVal(a) + intVal(b);
						} );


					// Update footer
					$( api.column( 8 ).footer() ).html(total);

					// Total 9 over all pages
					total = api
						.column( 9 )
						.data()
						.reduce( function (a, b) {
							return intVal(a) + intVal(b);
						} );


					// Update footer
					$( api.column( 9 ).footer() ).html(total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "'"));

					// Total 10 over all pages
					total = api
						.column( 10 )
						.data()
						.reduce( function (a, b) {
							return intVal(a) + intVal(b);
						} );


					// Update footer
					$( api.column( 10 ).footer() ).html(total);

             	// Total 11 over all pages
					total = api
						.column( 11 )
						.data()
						.reduce( function (a, b) {
							return intVal(a) + intVal(b);
						} );


					// Update footer
					$( api.column( 11 ).footer() ).html(total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
                  	// Total 11 over all pages
					total = api
						.column( 12 )
						.data()
						.reduce( function (a, b) {
							return intVal(a) + intVal(b);
						} );


					// Update footer
					$( api.column( 12 ).footer() ).html(total);

                  	// Total 11 over all pages
					total = api
						.column( 13 )
						.data()
						.reduce( function (a, b) {
							return intVal(a) + intVal(b);
						} );


					// Update footer
					$( api.column( 13 ).footer() ).html(total);

				}
			} );

			$('.dataTablesCompletedBoards').DataTable( {
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

					// Total 1 over all pages
					total = api
						.column( 1 )
						.data()
						.reduce( function (a, b) {
							return intVal(a) + intVal(b);
						} );


					// Update footer
					$( api.column( 1 ).footer() ).html(total);

					// Total 2 over all pages
					total = api
						.column( 2 )
						.data()
						.reduce( function (a, b) {
							return intVal(a) + intVal(b);
						} );


					// Update footer
					$( api.column( 2 ).footer() ).html(total);

					// Total 3 over all pages
					total = api
						.column( 3 )
						.data()
						.reduce( function (a, b) {
							return intVal(a) + intVal(b);
						} );


					// Update footer
					$( api.column( 3 ).footer() ).html(total);

					// Total 4 over all pages
					total = api
						.column( 4 )
						.data()
						.reduce( function (a, b) {
							return intVal(a) + intVal(b);
						} );


					// Update footer
					$( api.column( 4 ).footer() ).html(total);

					// Total 5 over all pages
					total = api
						.column( 5 )
						.data()
						.reduce( function (a, b) {
							return intVal(a) + intVal(b);
						} );


					// Update footer
					$( api.column( 5 ).footer() ).html(total);
				}
			} );

			$('.dataTablesTotalUniqueMembers').DataTable( {
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

				}
			} );

			$('.dataTablesActiveBoards').DataTable( {
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

				}
			} );

			$('.dataTablesInactiveBoards').DataTable( {
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

				}
			} );

			$('.dataTablesePins').DataTable( {
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

				}
			} );

			$('.dataTableseDocuments').DataTable( {
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

				}
			} );

			 $('#data_1 .input-group.date').datepicker({
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true
            });
        });

    </script>

	<!-- iCheck -->
    <script src="js/plugins/iCheck/icheck.min.js"></script>
        <script>
            $(document).ready(function () {
                $('.i-checks').iCheck({
                    checkboxClass: 'icheckbox_square-green',
                    radioClass: 'iradio_square-green',
                });


            });
    </script>
</body>
</html>