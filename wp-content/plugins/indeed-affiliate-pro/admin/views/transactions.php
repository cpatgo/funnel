<div class="uap-wrapper">
	<div class="uap-page-title">Ultimate Affiliate Pro - <span class="second-text"><?php _e('Transactions', 'uap');?></span></div>
	
		<?php if (!empty($data['subtitle'])):?>
			<h4><?php echo $data['subtitle'];?></h4>
		<?php endif;?>	
	
	<?php if (!empty($data['listing_items'])) : ?>
	<div class="uap-special-box">
	<?php echo $data['filter'];?>
	</div>
	<div style="margin: 10px 0px; text-align: right;">
		<button class="button button-primary button-large" onClick="window.location.href='<?php echo $data['update_payments'];?>'"><?php _e("Check Payments Status", 'uap');?></button>
	</div>
	<form action="" method="post" id="form_payments">
					<table class="wp-list-table widefat fixed tags">
						<thead>
							<tr>
								<th><?php _e('Username', 'uap');?></th>
								<th><?php _e('Amount', 'uap');?></th>
								<th><?php _e('Payment Type', 'uap');?></th>
								<th><?php _e('Create Date', 'uap');?></th>
								<th><?php _e('Update Date', 'uap');?></th>
								<th><?php _e('Payment Service Status', 'uap');?></th>
								<th><?php _e('Status', 'uap');?></th>
								<th style="min-width:250px"><?php _e('Action', 'uap');?></th>								
							</tr>
						</thead>
						<tfoot>
							<tr>	
								<th><?php _e('Username', 'uap');?></th>
								<th><?php _e('Amount', 'uap');?></th>
								<th><?php _e('Payment Type', 'uap');?></th>
								<th><?php _e('Create Date', 'uap');?></th>
								<th><?php _e('Update Date', 'uap');?></th>
								<th><?php _e('Payment Service Status', 'uap');?></th>
								<th><?php _e('Status', 'uap');?></th>
								<th style="min-width:250px"><?php _e('Action', 'uap');?></th>
							</tr>
						</tfoot>
						<tbody class="ui-sortable uap-alternate">
							<?php foreach ($data['listing_items'] as $key => $array): ?>
							<tr>
								<td><div class="uap-list-affiliates-name-label"><?php echo $array['username'];?></div></td>
								<td style="font-weight:bold;"><?php echo $array['amount'] . ' ' . $array['currency'];?></td>
								<td><span class="uap-admin-aff-payment-type uap-payment-type-active-<?php echo $array['payment_type'];?>" style="font-size:12px;"><?php echo $array['payment_type'];?></span></td>
								<td><?php echo uap_convert_date_to_us_format($array['create_date']);?></td>
								<td><?php echo uap_convert_date_to_us_format($array['update_date']);?></td>
								<td><?php if ($array['payment_special_status']) {
									echo $array['payment_special_status'];
								} else {
									echo '-';
								}?></td>
								<td style="font-weight:bold;"><?php 
									switch ($array['status']){
										case 0:
											?>
												<div><?php _e('Fail', 'uap');?></div>
											<?php 											
											break;
										case 1:
											?>
												<div><?php _e('Pending', 'uap');?></div>
											<?php 											
											break;
										case 2:
											?>
												<div><?php _e('Complete', 'uap');?></div>
											<?php 		
											break;
									}
								?></td>
								<td style="min-width:250px">
									<div class="referral-status-verified"><a style="color:#fff;" href="<?php echo $data['view_transaction_url'] . '&id=' . $array['id'];?>"><?php _e('View Details', 'uap');?></a></div>
									<div>
									
									<?php 
										if ($array['status']==2){
											?>
											<span class="refferal-chang-status" onclick="jQuery('#transaction_id').val(<?php echo $array['id'];?>);jQuery('#new_status').val(1);jQuery('#form_payments').submit();"><?php _e('Mark as Pending', 'uap');?></span>								
											<span>|</span> 
											<?php 										
										} else if ($array['status']==1){
											?>
											<span class="refferal-chang-status" onclick="jQuery('#transaction_id').val(<?php echo $array['id'];?>);jQuery('#new_status').val(2);jQuery('#form_payments').submit();"><?php _e('Mark as Complete', 'uap');?></span><span>|</span> <?php 										
										}
									?>
									<span class="refferal-chang-status" onclick="jQuery('#delete_transaction').val(<?php echo $array['id'];?>);jQuery('#form_payments').submit();"><?php _e('Delete', 'uap');?></span>
									</div>
								</td>
							</tr>
							
							<?php endforeach;?>
						</tbody>
					</table>
			<input type="hidden" name="transaction_id" id="transaction_id" value="" />
			<input type="hidden" name="new_status" id="new_status" value="" />
			<input type="hidden" name="delete_transaction" id="delete_transaction" value="" />
	</form>
	
	<?php endif;?>
	<?php if (!empty($data['pagination'])) : ?>
		<?php echo $data['pagination'];?>
	<?php endif;?>
</div>
