<div class="uap-wrapper">
	<div class="uap-page-title">Ultimate Affiliate Pro - <span class="second-text"><?php _e('Payment Form', 'uap');?></span></div>
	<form method="post" action="<?php echo $data['submit_link'];?>">
		<div class="row">
				<div class="col-xs-4">
					<div class="payment-box">
						<h3><?php _e('Pay With', 'uap');?></h3>
						<p><?php _e('Choose one of the Payment Gateway Option. "Bank Transfer" is an offline alternative payment.', 'uap');?></p>
						<?php if (!empty($data['paypal'])):?>
						<div style="margin:20px 0 10px 0;" class="uap-list-affiliates-name-label">
							<input style="vertical-align: bottom;" type="radio" value="paypal" name="paywith" onClick="uap_payment_form_payment_status(this.value);"/> <?php _e('PayPal', 'uap');?>
						</div>
						<?php endif;?>
						<div style="margin: 0px 0 10px 0;"  class="uap-list-affiliates-name-label">
							<input style="vertical-align: bottom;" type="radio" value="bank_transfer" name="paywith" onClick="uap_payment_form_payment_status(this.value);" checked/> <?php _e('Bank Transfer', 'uap');?>
						</div>
						<?php if (!empty($data['stripe'])):?>
						<div class="uap-list-affiliates-name-label">
							<input style="vertical-align: bottom;" type="radio" value="stripe" name="paywith" onClick="uap_payment_form_payment_status(this.value);" /> <?php _e('Stripe', 'uap');?>
						</div>	
						<?php endif;?>					
					</div>
				</div>
			
				<div class="col-xs-4">
					<div class="payment-box" id="payment_status_div">
						<h3><?php _e('Payment Status', 'uap');?></h3>
						<p><?php _e('As "Bank Transfer" payment option you can set for now the a temporary Payment status.', 'uap');?></p>
						<div style="margin:20px 0 10px 0;"  class="uap-list-affiliates-name-label">
							<input style="vertical-align: bottom;" type="radio" value="1" name="payment_status" /> <?php _e('Pending', 'uap');?>
						</div>
						<div class="uap-list-affiliates-name-label">
							<input style="vertical-align: bottom;" type="radio" value="2" name="payment_status" checked/> <?php _e('Complete', 'uap');?>
						</div>
					</div>
				</div>				
			</div>		
			<div style="margin-top: 10px;">
				<input type="submit" value="<?php _e('Submit', 'uap');?>" name="do_payment" class="button button-primary button-large" />
				<button class="button button-primary button-large" onClick="window.location.href='<?php echo $data['return_url'];?>'"><?php _e('Cancel', 'uap');?></button>
			</div>
		<?php if (!empty($data['affiliate_pay'])) : ?>
		<table class="wp-list-table widefat fixed tags" style="margin-top:30px;">
						<thead>
							<tr>
								<th><?php _e('Username', 'uap');?></th>
								<th><?php _e('Name', 'uap');?></th>
								<th><?php _e('Rank', 'uap');?></th>
								<th><?php _e('E-mail', 'uap');?></th>
								<th><?php _e('Amount', 'uap');?></th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<th><?php _e('Username', 'uap');?></th>
								<th><?php _e('Name', 'uap');?></th>
								<th><?php _e('Rank', 'uap');?></th>
								<th><?php _e('E-mail', 'uap');?></th>
								<th><?php _e('Amount', 'uap');?></th>
							</tr>
						</tfoot>
				<tbody class="ui-sortable uap-alternate">
				<tr>
					<td><?php echo $data['affiliate_pay']['username'];?></td>
					<td><?php echo $data['affiliate_pay']['name'];?></td>	
					<td><?php echo $data['affiliate_pay']['rank'];?></td>	
					<td><?php echo $data['affiliate_pay']['email'];?>
					<input type="hidden" value="<?php echo $data['affiliate_pay']['email'];?>" name="email" /></td>	
					<td style="font-weight:bold"><?php echo $data['affiliate_pay']['amount'] . $data['currency'];?>
					
				<input type="hidden" value="<?php echo $data['affiliate_pay']['amount'];?>" name="amount" />
			<input type="hidden" value="<?php echo $data['currency'];?>" name="currency" />
			<input type="hidden" value="<?php echo $data['affiliate_pay']['referrals_in'];?>" name="referrals_in" />	
			<input type="hidden" value="<?php echo $data['affiliate_pay']['affiliate_id'];?>" name="affiliate_id" />	
					</td>	
				</tr>	
				
				</tbody>	
				</table>	
			
			
		<?php elseif (!empty($data['multiple_affiliates'])) :?>
			<table class="wp-list-table widefat fixed tags" style="margin-top:30px;">
						<thead>
							<tr>
								<th><?php _e('Username', 'uap');?></th>
								<th><?php _e('Name', 'uap');?></th>
								<th><?php _e('Rank', 'uap');?></th>
								<th><?php _e('E-mail', 'uap');?></th>
								<th><?php _e('Amount', 'uap');?></th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<th><?php _e('Username', 'uap');?></th>
								<th><?php _e('Name', 'uap');?></th>
								<th><?php _e('Rank', 'uap');?></th>
								<th><?php _e('E-mail', 'uap');?></th>
								<th><?php _e('Amount', 'uap');?></th>
							</tr>
						</tfoot>
				<tbody class="ui-sortable uap-alternate">
			<?php foreach ($data['multiple_affiliates'] as $id => $array): ?>
				<?php $affiliates[] = $id;?>
				<tr>
					<td><?php echo $array['username'];?></td>
					<td><?php echo $array['name'];?></td>	
					<td><?php echo $array['rank'];?></td>	
					<td><?php echo $array['email'];?></td>	
					<td style="font-weight:bold"><?php echo $array['amount'] . $data['currency'];?>
					
				<input type="hidden" value="<?php echo $array['referrals'];?>" name="referrals[<?php echo $id;?>]" />
				<input type="hidden" value="<?php echo $array['amount'];?>" name="amount[<?php echo $id;?>]" />
				<input type="hidden" value="<?php echo $data['currency'];?>" name="currency[<?php echo $id;?>]" />	
					</td>	
				</tr>	
			<?php endforeach;?>
			
						</tbody>	
				</table>	
			<?php $affiliates = implode(',', $affiliates)?>		
			<input type="hidden" value="<?php echo $affiliates;?>" name="affiliates" />		
		<?php endif;?>
			
	</form>
</div>
