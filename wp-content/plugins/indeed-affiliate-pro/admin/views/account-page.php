<div class="uap-page-title">Ultimate Affiliates Pro - 
	<span class="second-text">
		<?php _e('Accont Page', 'uap');?>
	</span>
</div>
<div class="uap-stuffbox">
	<div class="uap-shortcode-display">
		[uap-account-page]
	</div>
</div>		

<div class="metabox-holder indeed">			
<form action="" method="post">
	<div class="uap-stuffbox">
		<h3 class="uap-h3"><?php _e('Account Page Settings:', 'uap');?></h3>
		<div class="inside">
		
			<div class="uap-register-select-template" style="padding:20px 0 35px 20px;">
				<?php _e('Select Template:', 'uap');?>
				<select name="uap_ap_theme"  style="min-width:300px; margin-left:10px;"><?php 
					foreach ($data['themes'] as $k=>$v){
						$selected = ($k==$data['metas']['uap_ap_theme']) ? 'selected' : '';
						?>
						<option value="<?php echo $k;?>" <?php echo $selected;?>><?php echo $v;?></option>
						<?php 
					}
				?></select>
			</div>	
			
			<div class="inside">
				<h2><?php _e('Top Section:', 'uap');?></h2>
				<label><?php _e('Show Avatar Image:', 'uap');?></label>
				<label class="uap_label_shiwtch uap-onbutton">
					<?php $checked = ($data['metas']['uap_ap_edit_show_avatar']) ? 'checked' : '';?>
					<input type="checkbox" class="uap-switch" onClick="uap_check_and_h(this, '#uap_ap_edit_show_avatar');" <?php echo $checked;?> />
					<div class="switch" style="display:inline-block;"></div>
				</label>
				<input type="hidden" value="<?php echo $data['metas']['uap_ap_edit_show_avatar'];?>" name="uap_ap_edit_show_avatar" id="uap_ap_edit_show_avatar" /> 				
			</div>		
			
			<div class="inside" style="padding-bottom:30px;">
				<label><?php _e('Welcome Message:', 'uap');?></label>
				<div class="uap-wp_editor" style="width:65%; display: inline-block; vertical-align: top;">
				<?php wp_editor(stripslashes($data['metas']['uap_ap_welcome_msg']), 'uap_ap_welcome_msg', array('textarea_name'=>'uap_ap_welcome_msg', 'editor_height'=>200));?>
				</div>
				<div style="width: 20%; display: inline-block; vertical-align: top; margin-left: 10px; color: #333;">
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
											'{rank_name}'
							);
						$extra_constants = uap_get_custom_constant_fields();
						foreach ($constants as $v){
							?>
							<div><?php echo $v;?></div>
							<?php 	
						}
						?>
							<h4><?php _e('Custom Fields constants', 'uap');?></h4>
						<?php 		
						foreach ($extra_constants as $k=>$v){
							?>
							<div><?php echo $k;?></div>
							<?php 	
						}
					?>
							</div>
				</div>	
				<div class="uap-clear"></div>
			</div>				
			
			<div class="uap-special-line">
			  <div class="inside">
				<h2><?php _e('Tabs To Show:', 'uap');?></h2>
				<div style="display: inline-block; vertical-align: top">
					<div class="uap-ap-tabs-list">
						<?php foreach ($data['available_tabs'] as $k=>$v):?>						
							<div class="uap-ap-tabs-list-item" onClick="uap_ap_make_visible('<?php echo $k;?>', this);" id="<?php echo 'uap_tab-' . $k;?>"><?php echo $v;?></div>	
						<?php endforeach;?>
					</div>
					<div class="uap-ap-tabs-settings">
						<?php 
	
						$tabs = explode(',', $data['metas']['uap_ap_tabs']);
						foreach ($data['available_tabs'] as $k=>$v):?>				
							<div class="uap-ap-tabs-settings-item" id="<?php echo 'uap_tab_item_' . $k;?>">
								<div class="input-group">
									<span class="uap-labels-onbutton" style="font-weight:400; min-width:100px;"><?php echo $v;?></span>
									<label class="uap_label_shiwtch  uap-onbutton">
										<?php $checked = (in_array($k, $tabs)) ? 'checked' : '';?>
										<input type="checkbox" class="uap-switch" onClick="uap_make_inputh_string(this, '<?php echo $k;?>', '#uap_ap_tabs');" <?php echo $checked;?> />
										<div class="switch" style="display:inline-block;"></div>
									</label>						
								</div>	
								<?php if (isset($data['metas']['uap_tab_' . $k . '_menu_label'])) : ?>
									<div class="input-group">
										<span class="input-group-addon" id="basic-addon1"><?php _e('Menu Label', 'uap');?></span>
										<input type="text" class="form-control" placeholder="" value="<?php echo $data['metas']['uap_tab_' . $k . '_menu_label'];?>" name="<?php echo 'uap_tab_' . $k . '_menu_label';?>">
									</div>				
								<?php endif;?>									
								<?php if (isset($data['metas']['uap_tab_' . $k . '_title'])) : ?>								
									<div class="input-group">
										<span class="input-group-addon" id="basic-addon1"><?php _e('Title', 'uap');?></span>
										<input type="text" class="form-control" placeholder="" value="<?php echo $data['metas']['uap_tab_' . $k . '_title'];?>" name="<?php echo 'uap_tab_' . $k . '_title';?>">
									</div>
								<?php endif;?>
								<?php if (isset($data['metas']['uap_tab_' . $k . '_content'])) : ?>			
									<div class="input-group">
										<div style="width: 70%; display: inline-block; vertical-align: top;"><?php 
											wp_editor(stripslashes($data['metas']['uap_tab_' . $k . '_content']), 'uap_tab_' . $k . '_content', array('textarea_name' => 'uap_tab_' . $k . '_content', 'editor_height'=>200));
										?></div>	
										<div style="width: 20%; display: inline-block; vertical-align: top; margin-left: 10px; color: #333;">
											<?php 
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
									</div>
								<?php endif;?>
							</div>
						<?php endforeach;?>					
					</div>					
				</div>
				<input type="hidden" value="<?php echo $data['metas']['uap_ap_tabs'];?>" id="uap_ap_tabs" name="uap_ap_tabs" />
			   </div>
			</div>	
			
			<div class="uap-form-line">
				<h2><?php _e('Custom CSS:', 'uap');?></h2>
					<textarea id="uap_account_page_custom_css"  name="uap_account_page_custom_css" class="uap-dashboard-textarea-full"  style="width: 100%; height: 120px;"><?php echo $data['metas']['uap_account_page_custom_css'];?></textarea>
					<div class="uap-wrapp-submit-bttn">
						<input type="submit" value="<?php _e('Save', 'uap');?>" name="save" class="button button-primary button-large"  style="min-width:50px;" />
					</div>	
			</div>
			
		</div>
	</div>
</form>
</div>
<script>
jQuery(document).ready(function(){
	uap_ap_make_visible('overview', '#uap_tab-overview');
});
</script>
