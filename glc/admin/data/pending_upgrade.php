<?php 
    $user_class = getInstance('Class_User');
    $membership_class = getInstance('Class_Membership');
    $pending_upgrades = $membership_class->get_pending_memberships();
?>
<div class="wrapper wrapper-content animated fadeInRight">
    <?php if(isset($_GET['msg']) && !empty($_GET['msg'])) printf('<div class="alert alert-success">%s</div>', $_GET['msg']); ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Pending Membership Upgrade</h5>
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
                                <th>Transaction ID</th>
                                <th>User ID</th>
                                <th>Username</th>
                                <th>Current Membership</th>
                                <th>Upgrade Membership To</th>
                                <th>Date requested</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pending_upgrades as $key => $value) {
                                $user = $user_class->get_user($value['user_id']);
                                $old_membership = $membership_class->get_membership($value['current_membership']);
                                $new_membership = $membership_class->get_membership($value['upgrade_membership']);
                                $user = $user[0];
                                printf('<tr>');
                                    printf('<td>%s</td>', $value['id']);
                                    printf('<td>%s</td>', $value['user_id']);
                                    printf('<td>%s</td>', $user['username']);
                                    printf('<td>%s</td>', $old_membership[0]['membership']);
                                    printf('<td>%s</td>', $new_membership[0]['membership']);
                                    printf('<td>%s</td>', $value['requested_date']);
                                    printf('<td><a data-upgrade_id="%d" data-user_id="%d" data-level="%d" class="upgrade_membership">Upgrade Membership</a></td>', $value['id'], $value['user_id'], $value['upgrade_membership']);
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
        var upgrade_user_url = "<?php printf('%s/glc/admin/index.php?page=pending_upgrade', GLC_URL); ?>";
        var ajax_url = "<?php printf('%s/glc/admin/ajax/', GLC_URL); ?>";

        $('.upgrade_membership_form').dataTable({
            "iDisplayLength": 100,
            responsive: true,
            "dom": 'T<"clear">lfrtip',
            "tableTools": {
                "sSwfPath": "js/plugins/dataTables/swf/copy_csv_xls_pdf.swf"
            }
        });

        $('body').on('click', '.upgrade_membership', function(e){
            e.preventDefault();
            $(this).prop('disabled', true);
            var fields = '<input type="hidden" name="upgrade_id" value="'+$(this).data('upgrade_id')+'" />';
            fields += '<input type="hidden" name="user_id" value="'+$(this).data('user_id')+'" />';
            fields += '<input type="hidden" name="level" value="'+$(this).data('level')+'" />';
            $.ajax({
                method: "post",
                url: ajax_url+"admin_upgrade_user.php",
                data: {
                    'fields': $(fields).serialize()
                },
                dataType: 'json',
                success:function(result) {
                    if(result.type == 'error'){
                        alert(result.message);
                        $(this).prop('disabled', false);
                    } else {
                        window.location.href = upgrade_user_url+'&msg='+result.message;
                    }
                },
                error: function(errorThrown){
                    $(this).prop('disabled', false);
                    console.log(errorThrown);
                }
            });
        });
    });
</script>