<?php 
    $user_class = getInstance('Class_User');
    $membership_class = getInstance('Class_Membership');
    $upgrades = $membership_class->get_membership_upgrades();
?>
<div class="wrapper wrapper-content animated fadeInRight">
    <?php if(isset($_GET['msg']) && !empty($_GET['msg'])) printf('<div class="alert alert-success">%s</div>', $_GET['msg']); ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Membership Upgrades</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="fa fa-wrench"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li><a href="#">Config option 1</a>
                            </li>
                            <li><a href="#">Config option 2</a>
                            </li>
                        </ul>
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <table class="table table-striped table-bordered table-hover upgrade_membership_form" >
                        <thead>
                            <tr>
                                <th>User ID</th>
                                <th>Username</th>
                                <th>Original Membership</th>
                                <th>Membership Upgrade To</th>
                                <th>Payment Method</th>
                                <th>Transaction ID</th>
                                <th>Date requested</th>
                                <th>Date approved</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($upgrades as $key => $value) {
                                $user = $user_class->get_user($value['user_id']);
                                $old_membership = $membership_class->get_membership($value['current_membership']);
                                $new_membership = $membership_class->get_membership($value['upgrade_membership']);
                                $user = $user[0];
                                printf('<tr>');
                                    printf('<td>%s</td>', $value['user_id']);
                                    printf('<td>%s</td>', $user['username']);
                                    printf('<td>%s</td>', $old_membership[0]['membership']);
                                    printf('<td>%s</td>', $new_membership[0]['membership']);
                                    printf('<td>%s</td>', $value['payment_method']);
                                    printf('<td>%s</td>', $value['transaction_id']);
                                    printf('<td>%s</td>', $value['requested_date']);
                                    printf('<td>%s</td>', $value['upgraded_date']);
                                printf('</tr>');
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Page-Level Scripts -->
<script>
    $(document).ready(function() {
        var upgrade_user_url = "<?php printf('%s/glc/admin/index.php?page=membership_upgrades', GLC_URL); ?>";
        var ajax_url = "<?php printf('%s/glc/admin/ajax/', GLC_URL); ?>";

        $('.upgrade_membership_form').dataTable({
            "iDisplayLength": 100,
            responsive: true,
            "dom": 'T<"clear">lfrtip',
            "tableTools": {
                "sSwfPath": "js/plugins/dataTables/swf/copy_csv_xls_pdf.swf"
            }
        });
    });
</script>