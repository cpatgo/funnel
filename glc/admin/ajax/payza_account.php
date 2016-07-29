<?php

session_start();
// This file will perform ajax requests for Payza
if (!isset($_POST))
    printf('<script type="text/javascript">window.location="%s/glc/admin";</script>', GLC_URL);
require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');


$payza = (isset($_POST['payza_account'])) ? $_POST['payza_account'] : '';
$id = $_SESSION['dennisn_user_id'];

if ($payza !== '')
{
    $sql = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT payza_account FROM users WHERE payza_account = '$payza'");
    $count = mysqli_num_rows($sql);
    
    if ($count > 0)
    {
        echo json_encode(["success" => false, "error_message" => "Payza account already used by other member"]);
        exit;
    }
    else
    {
        mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE users SET payza_account = '$payza' WHERE id_user = '$id' ");
        echo json_encode(["success" => true, "error_message" => ""]);
        exit;
    }
}

echo json_encode(["success" => false, "error_message" => "Please enter a valid Payza Email"]);
exit;
