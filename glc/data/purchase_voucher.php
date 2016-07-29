<?php 
	require_once(dirname(dirname(__FILE__)).'/function/functions.php');
	$id = $_SESSION['dennisn_user_id'];
	$purchase_class = getInstance('Class_Purchase');
	$memberships = $purchase_class->get_memberships();
	$pay_modes = $purchase_class->get_pay_modes();
	$available_commission = get_available_funds($id);
?>
<div class="ibox-content">
	<div class="alert alert-danger" id="error_message" style="display:none;"></div>
	<div class="alert alert-success" id="success_message" style="display:none;"></div>
	<form method="post" id="purchase_voucher_form" class="clearfix">
		<h3>Select the VIP packages you want to purchase.</h3>
		<table class="table table-bordered">
			<thead>
				<tr>
					<th></th>
					<th>e-Voucher Type</th>
					<th>Amount</th>
					<th>Available</th>
					<th>Number of e-Vouchers</th>
				</tr>
			</thead>
			<tbody>
				<?php 
					$available_vouchers = 0;
					// Show vip memberships
					foreach ($memberships as $key => $value) {
						$membership_id = $value['id'] - 1;
						$available_voucher = $purchase_class->check_availability($membership_id);
						$available_vouchers += $available_voucher;
						printf('<tr>');
						if((int)$available_voucher < 1):
							printf('<td></td>');
						else:
							printf('<td><input type="checkbox" name="select_voucher[%1$d]" data-membership="%1$d"></td>', $membership_id);
						endif;
						
						printf('<td>%s</td>', $value['membership']);
						printf('<td class="amount">%s</td>', $value['amount']);
						printf('<td>%s</td>', $available_voucher);
						if((int)$available_voucher < 1):
							printf('<td><div class="alert alert-danger">Not available</div></td>');
						else:
							printf('<td><input type="text" name="voucher_count[%d]" value="0"></td>', $membership_id);
						endif;
						printf('</tr>');
					}
					printf('<tr><td colspan="5"><h4>TOTAL AMOUNT: $<span id="total_amount">%d</span></h4></td></tr>', 0);
				?>
			</tbody>
		</table>

		<hr></hr>

		<?php if($available_vouchers > 0): ?>

			<h3>Choose Your Payment Method</h3>
			<p>You can divide the total amount and pay using different payment methods.</p>
			<div class="alert alert-warning">Available Commission: <span id="available_commission"><?php echo $available_commission; ?></span></div>
			<div class="cc-selector clearfix">
				<?php 
					// Show payment methods
					foreach ($pay_modes as $key => $value) {
						printf('<div class="payment_block"><input id="%1$s" type="checkbox" name="payment_method[%2$d]" data-mode_id="%2$d" value="%1$s" />', strtolower($value['pay_mode']), $value['id']);
						printf('<label class="drinkcard-cc %1$s" for="%1$s"></label>', strtolower($value['pay_mode']));
						printf('</div>');
					}
				?>
		    </div>
	  		<input type="submit" name="submit_purchase" value="Purchase" class="btn btn-primary btn-large pull-right">
  		<?php endif; ?>
	</form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	var purchase_voucher_url = "<?php printf('%s/glc/index.php?page=purchase_voucher', GLC_URL); ?>";
    var ajax_url = "<?php printf('%s/glc/admin/ajax/', GLC_URL); ?>";
    var template_url = "<?php printf('%s/glc/admin/template/', GLC_URL); ?>";

	$('table input[type=text]').prop('disabled', true);

	$('body').on('click', '[type=checkbox]', function(){
		var membership = $(this).data('membership');
		var vcount = $(this).closest('tr').find('input[type=text]');

		if($(this).prop('checked') == false){	
			vcount.val('0');
			vcount.prop('disabled', true);
		} else {
			vcount.prop('disabled', false);
			vcount.val('1');
		}
		$('table input[type=text]').trigger('change');
	});

	$('body').on('click', 'input[type=checkbox]', function(e){
		if($(this).prop('checked')){
			$(this).closest('.payment_block').append('<input class="divide_payment" type="text" value="" placeholder="Enter amount" name="partial_amount['+$(this).data("mode_id")+']">');
		} else {
			$(this).closest('.payment_block').find('input[type=text]').remove();
		}
	});

	$('body').on('change', 'table input[type=text]', function(e){
		var total = 0;
		$('table input[type=text]').each(function(){
		    var price = $(this).closest('tr').find('.amount').text();
		    var count = $(this).val();
		    total += price*count;
		});
		$('#total_amount').text(total);
	});

	$('body').on('click', 'input[name=submit_purchase]', function(e){
		e.preventDefault();
		$('#error_message').text('').hide();

		var total = 0;
		$('.divide_payment').each(function(){
			if(!isNaN(parseInt($(this).val()))){
				total += parseInt($(this).val());
			}
		});
		//Check if the user is paying the correct amount.
		if(total != parseInt($('#total_amount').text())){
			alert('Please pay the correct amount.');
			return false;
		}

		if(confirm('Are you sure you want to proceed with the purchase?')){
			var purchase_form = $('body').find('#purchase_voucher_form').serialize();

			$.ajax({
	            method: "post",
	            url: ajax_url+"purchase_voucher.php",
	            data: {
	                'fields': purchase_form
	            },
	            dataType: 'json',
	            success:function(result) {
	                if(result.type == 'success'){
	                	alert(result.message);
	                	window.location.href = purchase_voucher_url;
	                } else {
	                    $('#error_message').text(result.message).show();
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