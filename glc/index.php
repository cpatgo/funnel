<?php
ini_set("display_errors",'off');
session_start();
include("config.php");

$id = $_SESSION['dennisn_user_id'];

?>
<script type="text/javascript">
if(window.console.firebug)  {
     document.body.innerHTML = "PLEASE DO NOT USE FIREBUG"
};
</script>

<?php

//For Language Change Programm Start Here
$lang = $_REQUEST['lang'];
if($lang == 'Spanish')
{
	unset($_SESSION['language']);
	$_SESSION['language']  = 'language/sp.php';
}
elseif($lang == 'French')
{
	unset($_SESSION['language']);
	$_SESSION['language']  = 'language/fr.php';
}
elseif($lang == 'English')
{
	unset($_SESSION['language']);
	$_SESSION['language']  = 'language/en.php';
}
if(!isset($_SESSION['language']))
{
	$_SESSION['language'] = "language/en.php";
}

include $_SESSION['language'];
//For Language Change Programm End Here



if($_SESSION['dennisn_user_login'] != 1)
{
	include("login.php");
	die;
}
if($_SESSION['dennisn_user_type'] == 'C')
{
	 ?>
<font size="+2" style="color:#FF0000;font-family:vardana; padding-top:500px;"><center><strong>You Can't Logged in because, You are a Block User !!!<br />Please Contact to Admin !</strong></center></font>
<?php
	die;
}

if($_SESSION['rbt_client_ip_blocked'] == 1)
{
	 ?>
<font size="+2" style="color:#FF0000;font-family:vardana; padding-top:500px;"><center><strong>You Can't Logged in because, You are Not Authorised !!!<br />Please Contact to Admin !</strong></center></font>
<?php
	die;
}

$qu = mysqli_query($GLOBALS["___mysqli_ston"], "select * from income_process where id = 1 ");
while($r = mysqli_fetch_array($qu))
{
	$process_mode = $r['mode'];
}

if($process_mode == 1)
{ ?>
<strong style="color:#FF0000">Sorry Site is In Maintenance!!!<br />Please Try Again Later!</strong>
<?php }
else
{
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>GLC</title>

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

</head>
<body>
<div id="wrapper">
	<?php include "left.php"; ?>

	<div id="page-wrapper" class="gray-bg">
		<?php include "top.php"; ?>
		<?php include "middle1.php"; ?>
	</div>
</div>

    <!-- Mainly scripts -->
    <script src="js/jquery-2.1.1.js"></script>
    <script type='text/javascript' src='//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js'></script>
    <script src="js/plugins/metisMenu/jquery.metisMenu.js"></script>

    <script src="js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <!-- <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script> -->
<!--<script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>-->
<script src="<?php printf('%s/glc/js/inputmask/inputmask.js', GLC_URL); ?>"></script>
<script src="<?php printf('%s/glc/js/inputmask/jquery.inputmask.js', GLC_URL); ?>"></script>

<!-- Jquery Validate -->
<script src="<?php printf('%s/glc/js/plugins/validate/jquery.validate.min.js', GLC_URL); ?>"></script>

<!-- Jquery Modal -->
<script src="<?php printf('%s/glc/js/modal/jquery.modal.min.js', GLC_URL); ?>"></script>

<!-- jQuery Credit Card Validator -->
<script src="js/jquery.creditCardValidator.js"></script>
<script type="text/javascript" src="<?php printf('%s/glc/js/user.js', GLC_URL); ?>"></script>


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
    <!-- Peity -->
    <script src="js/plugins/peity/jquery.peity.min.js"></script>
    <script src="js/demo/peity-demo.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="js/inspinia.js"></script>
    <script src="js/plugins/pace/pace.min.js"></script>

    <!-- jQuery UI -->
    <script src="js/plugins/jquery-ui/jquery-ui.min.js"></script>

    <!-- Data Tables -->
    <script src="js/plugins/dataTables/jquery.dataTables.js"></script>
    <script src="js/plugins/dataTables/dataTables.bootstrap.js"></script>
    <script src="js/plugins/dataTables/dataTables.responsive.js"></script>
    <script src="js/plugins/dataTables/dataTables.tableTools.min.js"></script>

	<!-- Data picker -->
    <script src="js/plugins/datapicker/bootstrap-datepicker.js"></script>

    <!-- GLC Custom Scripts -->
    <script src="js/validation.js"></script>

	<script>
        $(document).ready(function() {
            $('.dataTables').dataTable({
                responsive: true,
                "iDisplayLength": 100,
                "dom": 'T<"clear">lfrtip',
                "tableTools": {
                    "sSwfPath": "js/plugins/dataTables/swf/copy_csv_xls_pdf.swf"
                }
            });
			 $('#data_1 .input-group.date').datepicker({
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true
            });

            

        });

    </script>
    
	


</body>
</html>
<?php
} ?>