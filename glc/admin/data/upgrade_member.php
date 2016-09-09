<?php
$user_class = getInstance('Class_User');
$membership_class = getInstance('Class_Membership');
$membership = $membership_class->get_memberships();
?>
<?php if(isset($_GET['msg']) && !empty($_GET['msg'])) printf('<div class="alert alert-success">%s</div>', $_GET['msg']); ?>
<div class="loading_message"></div>
<div class="ibox float-e-margins">
    <div class="ibox-title">
        <h5 style="text-align: right">Upgrade User</h5>
    </div>
    <div class="ibox-content">  
    	<form id="upgrade_user_form">
	        <table class="table table-striped table-bordered table-hover">
				<tr>
                    <td>Select Membership</td>
                    <td>
                        <?php                    
                            foreach($membership as $key => $value) {
                            	if($value['membership'] === 'Free') continue;
                                printf('<input type="radio" name="level" value="%d" required> Upgrade to %s <br>', $value['id'], $value['membership']);
                            }
                        ?>
                    </td>            
                </tr>
                <tr>
                	<td>User ID</td>
                	<td>
                		<input type="text" name="user_id" id="user_id">
                		<button id="check_user" class="btn btn-primary">Check User</button>
                		<br>
                		<i>* Check user first by clicking the Check User button</i>
                	</td>
                </tr>
	        </table>
	        <button class="btn btn-primary btn-large" id="upgrade_user" style="display: none">Upgrade</button>
        </form>
    </div>
</div>
<!-- JQUERY -->
<script type="text/javascript">
    $(function() {
        var upgrade_user_url = "<?php printf('%s/glc/admin/index.php?page=upgrade_member', GLC_URL); ?>";
        var ajax_url = "<?php printf('%s/glc/admin/ajax/', GLC_URL); ?>";
        var template_url = "<?php printf('%s/glc/admin/template/', GLC_URL); ?>";

        $('body').on('click', '#check_user', function(e){
            e.preventDefault();
            $('body').find('.userdetails').remove();
            $.ajax({
                method: "post",
                url: ajax_url+"check_user.php",
                data: {
                    'user_id': $('body').find('#user_id').val()
                },
                dataType: 'json',
                success:function(result) {
                    console.log(result);
                    if(result.type == 'error'){
                        alert(result.message);
                    } else {
                    	var userdetails = '<tr class="userdetails">';
                    	userdetails += '<td>User Details</td>';
                    	userdetails += '<td>';
                    	userdetails += 'Name: '+result.message.user.f_name + ' ' + result.message.user.l_name;
                    	userdetails += '<br>Username: '+result.message.user.username;
                    	userdetails += '<br>Current Membership: '+result.message.membership.membership;
                    	userdetails += '</td>';
                    	userdetails += '</tr>';

                        userdetails += '<td>Payment Method</td>';
                        userdetails += '<td>';
                        userdetails += '<select name="payment_method" id="payment_method">';
                        userdetails += '<option value="bank">Bank</option>';
                        userdetails += '<option value="authorize_net_2">NetPay (Tom Pace)</option>';
                        userdetails += '<option value="authorize_net">TrustPay (Alex)</option>';
                        userdetails += '<option value="wire">Wire</option>';
                        userdetails += '<option value="xpressdrafts">XpressDrafts</option>';
                        userdetails += '</select>';
                        userdetails += '</td>';
                        userdetails += '</tr>';

                        userdetails += '<td>Transaction ID</td>';
                        userdetails += '<td>';
                        userdetails += '<input type="text" name="transaction_id" id="transaction_id">';
                        userdetails += '</td>';
                        userdetails += '</tr>';

                    	$('body').find('.table').append(userdetails);
                    	$('body').find('#upgrade_user').show();
                    }
                },
                error: function(errorThrown){
                    console.log(errorThrown);
                }
            });
        });

        $('body').on('click', '#upgrade_user', function(e){
            e.preventDefault();

            $('body').find('.loading_message').append('<div class="alert alert-success">User is being upgraded... Page will reload automatically after the upgrade.</div>');
            var fields = $('body').find('#upgrade_user_form').serialize();
            $.ajax({
                method: "post",
                url: ajax_url+"upgrade_user.php",
                data: {
                    'fields': fields
                },
                dataType: 'json',
                success:function(result) {
                    if(result.type == 'error'){
                        alert(result.message);
                    } else {
                        window.location.href = upgrade_user_url+'&msg='+result.message;
                    }
                },
                error: function(errorThrown){
                    console.log(errorThrown);
                }
            });
        });
    });
</script>
<!-- END JQUERY -->