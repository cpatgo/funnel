<?php 
	if(file_exists(dirname(__FILE__).'/database.php')) require_once(dirname(__FILE__).'/database.php');
	$merchant_class = getInstance('Class_Merchant');
	$merchants = $merchant_class->get_all();

	//Check the current setting
	$current_methods = $current_packages = array();
	$current_merchant = glc_option('default_merchant_provider');
    $current_environment = glc_option('default_merchant_environment');

	if(!empty($current_merchant)):
		$current_methods = $merchant_class->get_payment_methods($current_merchant);
		$current_packages = $merchant_class->get_packages($current_merchant);
        // var_dump($current_merchant);
        // var_dump($current_environment);
        $current_merchant_settings = $merchant_class->get_selected_merchant_settings((int)$current_merchant, $current_environment);
        // die(var_dump($current_merchant_settings));
        $available_environment = $merchant_class->get_distinct_environments();
        $current_merchant_name = $merchant_class->get_merchant_name((int)$current_merchant);
        // var_dump($environments);
	endif;
?>
<style type="text/css">
.nav > li.active {
    border-left: none; 
    background: none; 
}
</style>
<div class="ibox float-e-margins">
    <div class="ibox-title">
        <h5 style="text-align: right">Merchant Settings</h5>
    </div>
    <div class="ibox-content">
        <form id="merchant_form">
            <div class="panel blank-panel">
                <div class="panel-heading">
                    <div class="panel-options">
                        <ul class="nav nav-tabs">
                            <?php printf('<li class="active"><a href="#tab-1" data-toggle="tab">General</a></li>'); ?>
                            <?php foreach ($merchants as $key => $value) {
                                printf('<li><a href="#tab-%d" data-toggle="tab">%s</a></li>', $key+2, $value['merchant']);
                            } ?>
                        </ul>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="tab-content">
                        <!-- GENERAL TAB -->
                        <?php printf('<div class="tab-pane active" id="tab-1">'); ?>
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td>Select Environment Mode:</td>
                                        <td>
                                            <?php                            
                                                foreach($available_environment as $key => $value) {
                                                    $selected = "checked='checked'";
                                                    printf('<input type="radio" name="environment" value="%1$s" %2$s>&nbsp;&nbsp; %1$s <br>', $value['environment'], $value['environment'] === $current_environment ? $selected : '');
                                                }
                                            ?>
                                        </td>            
                                    </tr>
                                </tbody>
                            </table>
                            <button class="btn btn-primary btn-large" id="submit_changes">Update</button>
                        <?php printf('</div>');?>
    
                        <!-- MERCHANTS TAB -->
                        <?php foreach ($merchants as $key => $value) {
                            printf('<div class="tab-pane" id="tab-%d">', $key+2);
                        ?>
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td>Enable or Disable Merchant</td>
                                        <td>
                                            <?php 
                                                printf('<input type="radio" name="enabledisable[%d][]" value="false" %s>&nbsp;&nbsp; Disabled <br>', $value['id'], ((int)$value['status'] == 0) ? 'checked="checked"' : '');
                                                printf('<input type="radio" name="enabledisable[%d][]" value="true" %s>&nbsp;&nbsp; Enabled <br>', $value['id'], ((int)$value['status'] == 1) ? 'checked="checked"' : '');
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Enable or disable the payment method for the selected merchant</td>
                                        <td>
                                            <?php 
                                                foreach ($merchant_class->get_payment_methods($value['id']) as $mkey => $mvalue) {
                                                    printf("<input type='checkbox' name='merchant_payment_methods[%d][]' value='%s' %s>&nbsp;&nbsp; %s<br>", $value['id'], $mvalue['id'], ((int)$mvalue['status'] === 1) ? 'checked="checked"' : '', $mvalue['method']);
                                                }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Enable or disable the packages under the selected merchant</td>
                                        <td>
                                            <?php 
                                                foreach ($merchant_class->get_packages($value['id']) as $pkey => $pvalue) {
                                                    printf("<input type='checkbox' name='merchant_packages[%d][]' value='%s' %s>&nbsp;&nbsp; %s<br>", $value['id'], $pvalue['id'], ((int)$pvalue['status'] === 1) ? 'checked="checked"' : '', $pvalue['membership']);
                                                }
                                            ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <button class="btn btn-primary btn-large" id="submit_changes">Update</button>
                        <?php 
                            printf('</div>');
                        } ?>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>Summary</h5>
            </div>
            <div class="ibox-content">
                <table class="table table-bordered">
                    <tr>
                        <td>Environment Mode</td>
                        <td>
                            <?php echo ucfirst($current_environment); ?>
                        </td>            
                    </tr>
                    <tr>
                        <td>Active Payment Methods</td>
                        <td>
                            <?php 
                                foreach ($merchant_class->get_all_active_payment_methods() as $key => $value) {
                                    $merchant = $merchant_class->get_one($value['merchant_id']);
                                    printf('Merchant: <b>%s</b><br>Payment Method: <b>%s</b><br><br>', $merchant[0]['merchant'], $value['method']);
                                }
                            ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- JQUERY -->
<script type="text/javascript">
    $(function() {
        var merchant_setting_url = "<?php printf('%s/glc/admin/index.php?page=merchant_settings', GLC_URL); ?>";
        var ajax_url = "<?php printf('%s/glc/admin/ajax/', GLC_URL); ?>";
        var template_url = "<?php printf('%s/glc/admin/template/', GLC_URL); ?>";

        $('body').on('click', '#submit_changes', function(e){
        	e.preventDefault();
        	var fields = $('#merchant_form').serialize();
        	$.ajax({
                method: "post",
                url: ajax_url+"merchant.php",
                data: {
                	'fields' 	: fields,
                    'action'	: 'update_merchant_settings'
                },
                dataType: 'json',
                success:function(result) {
                    console.log(result);
                    if(result.type == 'success'){
                    	alert(result.message);
                    	window.location.href = merchant_setting_url;
                    } else {
                     	console.log(result);
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