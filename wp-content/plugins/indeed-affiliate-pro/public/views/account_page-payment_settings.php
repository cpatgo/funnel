<div class="uap-ap-wrap">
<?php if (!empty($data['title'])):?>
	<h3><?php echo $data['title'];?></h3>
<?php endif;?>
<?php if (!empty($data['message'])):?>
	<p><?php echo do_shortcode($data['message']);?></p>
<?php endif;?>	

<form action="" method="post" class="uap-change-password-form">
	<div class="uap-ap-field">
		<label class="uap-ap-label"><?php _e("Payment Type", 'uap');?></label>
		<select class="uap-public-form-control" onChange="uap_payment_type();" name="uap_affiliate_payment_type"><?php 
			foreach ($data['payment_types'] as $k=>$v):
				$selected = ($data['metas']['uap_affiliate_payment_type']==$k) ? 'selected' : '';
				?>
				<option value="<?php echo $k;?>" <?php echo $selected;?>><?php echo $v;?></option>
				<?php
			endforeach;	
		?></select>
	</div>	
	<div class="uap-ap-field" id="uap_payment_with_paypal" style="display: none;">
		<label class="uap-ap-label"><?php _e("PayPal E-mail Address", 'uap');?></label>
		<input class="uap-public-form-control" type="text" value="<?php echo $data['metas']['uap_affiliate_paypal_email'];?>" name="uap_affiliate_paypal_email" />
	</div>

	<div class="uap-ap-field" id="uap_payment_with_bt" style="display: none;">
		<label class="uap-ap-label"><?php _e("Bank Transfer Details", 'uap');?></label>
		<textarea style="min-height: 100px;" class="uap-public-form-control" name="uap_affiliate_bank_transfer_data"><?php echo $data['metas']['uap_affiliate_bank_transfer_data'];?></textarea>
	</div>
	
	<div class="uap-ap-field" id="uap_payment_with_stripe" style="display: none;">
		<div>
			<label class="uap-ap-label"><?php _e("Name on Card", 'uap');?></label>
			<input class="uap-public-form-control" type="text" value="<?php echo $data['metas']['uap_affiliate_stripe_name'];?>" name="uap_affiliate_stripe_name" />			
		</div>
		<div>
			<label class="uap-ap-label"><?php _e("Card Number", 'uap');?></label>
			<input class="uap-public-form-control" type="text" value="<?php echo $data['metas']['uap_affiliate_stripe_card_number'];?>" name="uap_affiliate_stripe_card_number" />			
		</div>
		<div>
			<label class="uap-ap-label"><?php _e("CVC", 'uap');?></label>
			<input class="uap-public-form-control" type="text" value="<?php echo $data['metas']['uap_affiliate_stripe_cvc'];?>" name="uap_affiliate_stripe_cvc" />			
		</div>
		<div>
			<label class="uap-ap-label"><?php _e("Expiration", 'uap');?></label>
			<div>
				<div style="display:inline-block;vertical-align: top">
					<select name="uap_affiliate_stripe_expiration_month"><?php
						for ($m=1; $m<13; $m++):
							$selected = ($m==$data['metas']['uap_affiliate_stripe_expiration_month']) ? 'selected' : '';
							?>
							<option value="<?php echo $m;?>" <?php echo $selected;?>><?php echo $m;?></option>
							<?php
						endfor;
					?></select>
				</div>
				<div style="display:inline-block;vertical-align: top">
					<select name="uap_affiliate_stripe_expiration_year"><?php
						$year = date('Y');
						for ($y=$year; $y<$year+10; $y++):
							$selected = ($m==$data['metas']['uap_affiliate_stripe_expiration_year']) ? 'selected' : '';
							?>
							<option value="<?php echo $y;?>" <?php echo $selected;?>><?php echo $y;?></option>
							<?php
						endfor;
					?></select>				
				</div>			
			</div>
		</div>
		<div>
			<label class="uap-ap-label"><?php _e("Type", 'uap');?></label>
			<div>
				<select name="uap_affiliate_stripe_card_type"><?php
					foreach ($data['stripe_card_types'] as $key=>$value):
						$selected = ($key==$data['metas']['uap_affiliate_stripe_card_type']) ? 'selected' : '';
						?>
						<option value="<?php echo $key;?>" <?php echo $selected;?>><?php echo $value;?></option>
						<?php
					endforeach;
				?></select>				
			</div>			
		</div>	
		<div>
			<label class="uap-ap-label"><?php _e("Tax ID", 'uap');?></label>
			<input class="uap-public-form-control" type="text" value="<?php echo $data['metas']['uap_affiliate_stripe_tax_id'];?>" name="uap_affiliate_stripe_tax_id" />			
		</div>			
	</div>
	
	
	<div class="uap-change-password-field-wrap">
		<input type="submit" value="<?php _e("Save", 'uap');?>" name="save_settings" class="button button-primary button-large" />
	</div>
	<?php if (!empty($data['error'])) : ?>
		<div><?php echo $data['error'];?></div>
	<?php elseif (!empty($data['success'])) : ?>
		<div><?php echo $data['success'];?></div>
	<?php endif; ?>
</form>
</div>
<script>
	
	jQuery(document).ready(function(){
		uap_payment_type();
	});
	
</script>