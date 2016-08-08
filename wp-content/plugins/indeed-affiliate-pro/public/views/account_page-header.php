<div class="uap-user-page-wrapper <?php echo $data['uap_ap_theme'];?>">
<div class="uap-user-page-details">
	<?php if (!empty($data['avatar'])):?>
		<div class="uap-user-page-avatar"><img src="<?php echo $data['avatar'];?>" class="uap-member-photo"/></div>
	<?php endif;?>
	<div class="uap-ap-top-message"><?php echo do_shortcode($data['message']);?></div>
	<div class="uap-clear"></div>
</div>
<?php 
$data['tabs'] = array(
					  array('tab', __('Overview', 'uap'), 'overview'),
					  array('tab', __('Profile', 'uap'), array(
					  								array('subtab', __('Edit Account', 'uap'),'edit_account'),
					  							  	array('subtab', __('Change Password', 'uap'),'change_pass'),
					  							  	array('subtab', __('Payments Settings', 'uap'), 'payments_settings'),
												  )
					  ),
					  array('tab', __('Marketing', 'uap'), array(
					  								array('subtab', __('Affiliate Links', 'uap'), 'affiliate_link'),
					  							  	array('subtab', __('Campaigns', 'uap'), 'campaigns'),
												  	array('subtab', __('Banners', 'uap'), 'banners'),												  	
												   )
					  ),
					  array('tab', __('Statements', 'uap'), 'referrals'),			
					  array('tab', __('Earnings', 'uap'), 'payments'),
					  array('tab', __('Wallet', 'uap'), 'wallet'),
					  array('tab', __('Reports', 'uap'), array(
					  								array('subtab', __('OverAll', 'uap'), 'reports'),
					  							  	array('subtab', __('Traffic Log', 'uap'), 'visits'),
												  	array('subtab', __('Campaign Reports', 'uap'), 'campaign_reports'),
												  	array('subtab', __('Referrals History', 'uap'), 'referrals_history'),
												 )
					  ),
					  array('tab', __('Referral Notifications & Reports', 'uap'), 'referral_notifications'),
					  array('tab', __('Help', 'uap'), 'help'),
					  array('tab', __('LogOut', 'uap'),'logout')			
);

if (isset($data['label']['coupons'])){
	//$data['tabs'][2][2][] = array('subtab', __('Coupons', 'uap'), 'coupons');
	$data['tabs'][2][2][] = array('subtab', $data['label']['coupons'], 'coupons');
}
if (isset($data['label']['custom_affiliate_slug'])){
	$temp_v = $data['tabs'][1][2][2];
	$data['tabs'][1][2][2] = array('subtab', $data['label']['custom_affiliate_slug'], 'custom_affiliate_slug');
	$data['tabs'][1][2][3] = $temp_v;
}
if (isset($data['label']['mlm'])){
	$data['tabs'][6][2][4] = array('subtab', $data['label']['mlm'], 'mlm');	
}

if (isset($data['label']['wallet'])){
	$data['tabs'][5][1] = $data['label']['wallet'];	
} else {
	unset($data['tabs'][5]);
}

if (isset($data['label']['referral_notifications'])){
	$data['tabs'][7][1] = $data['label']['referral_notifications'];	
} else {
	unset($data['tabs'][7]);
}

$selected_parent = '';

foreach ($data['tabs'] as $key=>$arr){
	if (is_array($arr[2])){
		foreach ($arr[2] as $second_key=>$second_arr){
			if (!in_array($second_arr[2], $data['show_tab_list'])){
				unset($data['tabs'][$key][2][$second_key]);
			} else if ($data['selected_tab']==$second_arr[2]){
				$selected_parent = $arr[1];
			}
		}
		if (empty($data['tabs'][$key][2])){
			unset($data['tabs'][$key]);
		}
	} else {
		if (!in_array($arr[2], $data['show_tab_list'])){
			unset($data['tabs'][$key]);
		}
	}
}


?>

<?php if (!empty($data['tabs']) && is_array($data['tabs'])) : ?>

		<div class="uap-ap-menu">
			<ul>
				<?php foreach ($data['tabs'] as $v) : ?>
			        <?php if ($v[0] == 'tab'):
							if(is_array($v[2])){ 
			        			if ($selected_parent==$v[1]){
			        				$extra_style = 'display: block';
			        				$i_class = 'fa-account-down-uap';			        				
			        			} else {
			        				$extra_style = '';	
			        				$i_class = 'fa-account-right-uap';
			        			}
								if ($data['uap_ap_theme']=='uap-ap-theme-1'){
									$action = "onClick=uap_show_subtabs('" . $v[1] . "');";
								} else {
									$action = "";
								}
			        			?>							
								<li class="uap-ap-submenu-item"><div class="uap-ap-menu-tab-item" <?php echo $action;?> ><a href="javascript:void(0);"><i class="uap-ap-menu-sign fa-uap <?php echo $i_class;?>" id="<?php echo 'uap_fa_sign-' . $v[1];?>"></i><?php echo $v[1]; ?></a></div>
									<ul class="uap-public-ap-menu-subtabs" style="<?php echo $extra_style;?>" id="<?php echo 'uap_public_ap_' . $v[1];?>">
										<?php foreach ($v[2] as $sub) : ?>
											<?php $extra_class = ($data['selected_tab']==$sub[2]) ? 'uap-ap-menu-item-selected' : '';?>
											<li class="uap-ap-menu-item <?php echo $extra_class;?>"><a href="<?php echo $data['urls'][$sub[2]];?>"><i class="fa-uap fa-<?php echo $sub[2]; ?>-account-uap"></i><?php 
			        						  	if (!empty($data['labels'][$sub[2]])){
						  							echo $data['labels'][$sub[2]];
						  						} else {
						  							echo $sub[1];
						  						}	
											?></a></li>
										<?php endforeach;?>	
									</ul>
								</li>
					  <?php } else { ?>
					  		<?php $extra_class = ($data['selected_tab']==$v[2]) ? 'uap-ap-menu-tab-item-selected' : '';?>
						  	<li class="uap-ap-menu-tab-item <?php echo $extra_class;?>"><a href="<?php echo $data['urls'][$v[2]];?>"><i class="fa-uap fa-<?php echo $v[2]; ?>-account-uap"></i><?php 
						  		if (!empty($data['labels'][$v[2]])){
						  			echo $data['labels'][$v[2]];
						  		} else {
						  			echo $v[1];
						  		}						  		
						  	?></a></li>
						<?php }?>
							
					<?php endif; ?>	
					
				<?php endforeach;?>	
			</ul>
		</div>
		
<?php endif;?>
<div class="uap-user-page-content">
	
