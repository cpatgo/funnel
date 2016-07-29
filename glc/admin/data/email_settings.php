<?php
    session_start();
    include("condition.php");
    include("../function/setting.php");

    $query = mysqli_query($GLOBALS["___mysqli_ston"], "select first_email_trigger, second_email_trigger from setting ");

    while($row = mysqli_fetch_array($query))
    {
        // $first_email_reminder = "10";
        // $second_email_reminder = "20";

        $first_email_reminder = $row['first_email_trigger'];
        $second_email_reminder = $row['second_email_trigger'];
    }


    if ( isset( $_REQUEST['submit'] ) ) {

        if( isset( $_POST['submit'] ) ) {

            $first_email_reminder = $_POST['first_email_reminder'];
            $second_email_reminder = $_POST['second_email_reminder'];

            $update_query_string = "UPDATE setting SET first_email_trigger='$first_email_reminder', second_email_trigger='$second_email_reminder'; ";
            var_dump($update_query_string);

            mysqli_query($GLOBALS["___mysqli_ston"], $update_query_string);

            $date = date('Y-m-d');
            include("../function/logs_messages.php");
            data_logs( $id, $data_log[14][0], $data_log[14][1], $log_type[14] );

            // query status
            $p = 1;
        }
        else {
            $p = 0;
        }
    }

?>

<div class="ibox-content">
    <form name="setting" method="post" action="index.php?page=email_settings">
        <table class="table table-bordered">
            <?php if($p == 1) {
                $status_str_builder = '<tr><td colspan="5">';
                $status_str_builder .= 'Updating completed Successfully';
                $status_str_builder .= '</td></tr>';


            } ?>
            <thead>
                <th colspan="2" class="table_heading">Email Referral Reminder</th>
            </thead>

            <tbody>
                <tr>
                    <td class="strong">Number of days for 1st email reminder to trigger <span class="italic">( in relation to Qualify Exp Time setting found under Setting > Network Setting )</span>. <span class="required">*</span></td>
                    <td>
                        <input type="number" min="0" max="99" name="first_email_reminder" value="<?=$first_email_reminder;?>" placeholder="0-99 (in days)" required />
                    </td>
                </tr>

                <tr>
                    <td class="strong">Number of days for 2nd email reminder to trigger <span class="italic">( in relation to Qualify Exp Time setting found under Setting > Network Setting )</span>. <span class="required">*</span></td>
                    <td>
                        <input type="number" min="0" max="99" name="second_email_reminder" value="<?=$second_email_reminder;?>" placeholder="0-99 (in days)" required />
                    </td>
                </tr>

                <tr>
                    <td colspan="2" class="text-center">
                        <input type="submit" name="submit" value="Update Settings!" class="btn btn-primary" />
                    </td>
                </tr>

            </tbody>
        </table>

    </form>
</div>
