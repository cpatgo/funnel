<div class="uap-wrapper">
	<div class="uap-stuffbox">
		<h3 class="uap-h3"><?php _e('Affilaite Payment Settings');?></h3>
		<div class="inside">
			<?php
			if (!empty($data['metas']['uap_affiliate_payment_type'])):
				$types = array('stripe'=>'Stripe', 'paypal'=>'PayPal', 'bt'=>'Bank Transfer');
				echo "<div><label>" . __('Payment Type:', 'uap') . "</label> " . $types[$data['metas']['uap_affiliate_payment_type']] . "</div>";
				switch ($data['metas']['uap_affiliate_payment_type']){
					case 'stripe':
						?>
						<div><label><?php echo __("Name on Card:", 'uap');?></label> <?php echo $data['metas']['uap_affiliate_stripe_name'];?></div>
						<div><label><?php echo __("Card Number:", 'uap');?></label> <?php  echo $data['metas']['uap_affiliate_stripe_card_number'];?></div>
						<div><label><?php echo __("CVC:", 'uap');?></label> <?php echo $data['metas']['uap_affiliate_stripe_cvc'];?></div>
						<div><label><?php echo __("Expiration:", 'uap');?></label> <?php echo $data['metas']['uap_affiliate_stripe_expiration_month'] . '/'. $data['metas']['uap_affiliate_stripe_expiration_year'];?></div>
						<div><label><?php echo __("Type:", 'uap');?></label> <?php echo $data['metas']['uap_affiliate_stripe_card_type'];?></div>
						<div><label><?php echo __("Tax ID:", 'uap');?></label> <?php echo $data['metas']['uap_affiliate_stripe_tax_id'];?></div>
						<?php	
						break;
					case 'bt':
						?>
						<div><label><?php echo __("Bank Transfer Details:", 'uap');?></label> <?php echo $data['metas']['uap_affiliate_bank_transfer_data'];?></div>
						<?php
						break;
					case 'paypal':
						?>
						<div><label><?php echo __("PayPal E-mail Address:", 'uap');?></label> <?php echo $data['metas']['uap_affiliate_paypal_email'];?></div>
						<?php
						break;
				}
			endif;
			?>			
		</div>
	</div>
</div>

