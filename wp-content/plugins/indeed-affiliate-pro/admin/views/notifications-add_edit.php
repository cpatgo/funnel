<?php if (empty($data['id'])):?>
	<script>
		jQuery(document).ready(function(){
			uap_return_notification();
		});
	</script>
<?php endif;?>

<div class="uap-wrapper">
		<div class="uap-stuffbox">
			<form action="<?php echo $data['form_action_url'];?>" method="post">
				<h3 class="uap-h3"><?php _e('Add/Edit Notification', 'uap');?></h3>
				<div class="inside">
					<div class="uap-form-line">
						<label class="uap-labels-special"><?php _e('Action:', 'uap');?></label>
						<select name="type" id="notf_type" onChange="uap_return_notification();">
						<?php foreach ($data['actions_available'] as $k=>$v):?>
							<?php 
								switch ($k){
									case 'admin_user_register':
										echo ' <optgroup label="' . __('Register Process', 'uap') . '">';
										break;	
									case 'affiliate_payment_fail':
										echo ' <optgroup label="' . __('Payments', 'uap') . '">';
										break;
									case 'reset_password_process':
										echo ' <optgroup label="' . __('Password', 'uap') . '">';
										break;	
									case 'affiliate_account_approve':
										echo ' <optgroup label="' . __('Profile Update', 'uap') . '">';
										break;	
									case 'admin_on_aff_change_rank':
										echo ' <optgroup label="' . __('Admin', 'uap') . '">';
										break;
									case 'email_check':
										echo ' <optgroup label="' . __('Double E-mail Verification', 'uap') . '">';
										break;							
								}
							?>
							<?php $selected = ($k==$data['type']) ? 'selected' : '';?>
							<option value="<?php echo $k;?>" <?php echo $selected;?>><?php echo $v;?></option>
							<?php 
								switch ($k){
									case 'register':
									case 'affiliate_payment_complete':
									case 'change_password':
									case 'rank_change':
									case 'admin_affiliate_update_profile':
									case 'email_check_success':
										echo '</optgroup>';
										break;	
								}
							?>							
						<?php endforeach;?>
						</select>						
					</div>
					<div class="uap-form-line">
						<label class="uap-labels-special"><?php _e('Target Rank:', 'uap')?></label>
						<select name="rank_id">
						<?php foreach ($data['ranks_available'] as $k=>$v):?>						
							<?php $selected = ($k==$data['rank_id']) ? 'selected' : '';?>
							<option value="<?php echo $k;?>" <?php echo $selected;?>><?php echo $v;?></option>
						<?php endforeach;?>			
						</select>
					</div>					
					<div class="uap-form-line">
						<label class="uap-labels-special"><?php _e('Subject:', 'uap');?></label>
						<input type="text" value="<?php echo $data['subject'];?>" name="subject" id="notf_subject" />
					</div>	
					<div class="uap-form-line">			
						<label  class="uap-labels-special" style="vertical-align: top;"><?php _e('Content:', 'uap');?></label>	
						<div style="padding-left: 5px; width: 70%;display:inline-block;">
							<?php wp_editor( $data['message'], 'notf_message', array('textarea_name'=>'message', 'quicktags'=>TRUE) );?>
						</div>	
						<div style="width: 20%; display: inline-block; vertical-align: top;margin-left: 10px; color: #333;">
						<?php 
							$constants = array(	"{username}",
												"{first_name}",
												"{last_name}",
												"{user_id}",
												"{user_email}",
												"{account_page}",
												"{login_page}",
												"{blogname}",
												"{blogurl}",
												"{siteurl}",
												'{rank_id}',
												'{rank_name}',
												'{NEW_PASSWORD}',
												'{password_reset_link}',
							);
							$extra_constants = uap_get_custom_constant_fields();
							foreach ($constants as $v){
								?>
								<div><?php echo $v;?></div>
								<?php 	
							}
							echo "<h4>" . __('Custom Fields constants', 'uap') . "</h4>";
							foreach ($extra_constants as $k=>$v){
								?>
								<div><?php echo $k;?></div>
								<?php 	
							}
						?>
						</div>	
					
					<div class="uap-submit-form">
						<input type="submit" value="<?php _e('Save', 'uap');?>" name="save" class="button button-primary button-large">
					</div>										
				</div>	
				<input type="hidden" name="status" value="1" />
				<input type="hidden" name="id" value="<?php echo $data['id'];?>" />		
			</form>
		</div>
</div>


</div><!-- end of uap-dashboard-wrap -->