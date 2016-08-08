<form action="" method="post">
	<div class="uap-stuffbox">
		<h3 class="uap-h3"><?php _e('Stripe - Payouts', 'uap');?></h3>
		<div class="inside">
				<div class="row">
						<div class="col-xs-7">
							<h3><?php _e('Activate/Hold Stripe Gateway', 'uap');?></h3>
							<p><?php _e('Once is activated you can proceed payments to your Affiliate users via Stripe directly from Affiliate System', 'uap');?></p>
							<label class="uap_label_shiwtch" style="margin:10px 0 10px -10px;">
							<?php $checked = ($data['metas']['uap_stripe_enable']) ? 'checked' : '';?>
								<input type="checkbox" class="uap-switch" onClick="uap_check_and_h(this, '#uap_stripe_enable');" <?php echo $checked;?> />
								<div class="switch" style="display:inline-block;"></div>
							</label>
							<input type="hidden" name="uap_stripe_enable" value="<?php echo $data['metas']['uap_stripe_enable'];?>" id="uap_stripe_enable" /> 
						</div>
				</div>
				<div class="uap-line-break"></div>	
				<div class="row">
					<div class="col-xs-4">
						<h4><?php _e('Sandbox', 'uap');?></h4>
						<label class="uap_label_shiwtch" style="margin:10px 0 10px -10px;">
						<?php $checked = ($data['metas']['uap_stripe_sandbox']) ? 'checked' : '';?>
						<input type="checkbox" class="uap-switch" onClick="uap_check_and_h(this, '#uap_stripe_sandbox');" <?php echo $checked;?> />
						<div class="switch" style="display:inline-block;"></div>
						</label>
						<input type="hidden" name="uap_stripe_sandbox" value="<?php echo $data['metas']['uap_stripe_sandbox'];?>" id="uap_stripe_sandbox" /> 	
					</div>
				</div>	
				<div class="uap-line-break"></div>		
				<div class="row">
					<div class="col-xs-6">	
						<div class="uap-form-line">
							<label class="uap-label"><?php _e('Sandbox Secret Key', 'uap');?></label>
							<div>
								<input type="text" name="uap_stripe_sandbox_secret_key" value="<?php echo $data['metas']['uap_stripe_sandbox_secret_key'];?>" />	
							</div>							
						</div>
						<div class="uap-form-line">
							<label class="uap-label"><?php _e('Sandbox Publishable Key', 'uap');?></label>
							<div>
								<input type="text" name="uap_stripe_sandbox_publishable_key" value="<?php echo $data['metas']['uap_stripe_sandbox_publishable_key'];?>" />
							</div>
						</div>								
						<div class="uap-form-line">
							<label class="uap-label"><?php _e('Live Secret Key', 'uap');?></label>
							<div>
								<input type="text" name="uap_stripe_secret_key" value="<?php echo $data['metas']['uap_stripe_secret_key'];?>" />
							</div>
						</div>
						<div class="uap-form-line">
							<label class="uap-label"><?php _e('Live Publishable Key', 'uap');?></label>
							<div>
								<input type="text" name="uap_stripe_publishable_key" value="<?php echo $data['metas']['uap_stripe_publishable_key'];?>" />
							</div>
						</div>
					</div>
				</div>
				<p><?php echo __('Set Your Webhook at: ') . UAP_URL . 'public/stripe-webhook.php';?></p>	
				<div class="uap-submit-form"> 
					<input type="submit" value="<?php _e('Save', 'uap');?>" name="save" class="button button-primary button-large" />
				</div>								
		</div>
	</div>
</form>	