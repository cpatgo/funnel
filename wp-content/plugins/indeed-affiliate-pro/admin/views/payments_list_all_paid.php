<div class="uap-wrapper">
	<div class="uap-page-title">Ultimate Affiliate Pro - <span class="second-text"><?php _e('Paid Referrals', 'uap');?></span></div>
	
		<?php if (!empty($data['subtitle'])):?>
			<h4><?php echo $data['subtitle'];?></h4>
		<?php endif;?>
	
	<?php if (!empty($data['listing_items'])) : ?>
		
		<?php 
			if (!empty($data['filter'])):
				echo '<div class="uap-special-box">'.$data['filter'].'</div>';
			endif;	
		?>
		
		<table class="wp-list-table widefat fixed tags">
			<thead>
				<tr>
					<th><?php _e('Affiliate', 'uap');?></th>
					<th><?php _e('Reference', 'uap');?></th>
					<th><?php _e('Amount', 'uap');?></th>
					<th><?php _e('Date', 'uap');?></th>
					<th><?php _e('Paid', 'uap');?></th>
					<?php if (!empty($data['payments_settings']) && $data['payments_settings']['type']=='bt' && $data['payments_settings']['is_active']):?>
						<th><?php _e('Payment Details', 'uap');?></th>
					<?php endif;?>	
				</tr>
			</thead>
			<tfoot>
				<tr>	
					<th><?php _e('Affiliate', 'uap');?></th>
					<th><?php _e('Reference', 'uap');?></th>
					<th><?php _e('Amount', 'uap');?></th>
					<th><?php _e('Date', 'uap');?></th>
					<th><?php _e('Paid', 'uap');?></th>
					<?php if (!empty($data['payments_settings']) && $data['payments_settings']['type']=='bt' && $data['payments_settings']['is_active']):?>
						<th><?php _e('Payment Details', 'uap');?></th>
					<?php endif;?>					
				</tr>
			</tfoot>
			<tbody class="ui-sortable uap-alternate">
				<?php foreach ($data['listing_items'] as $key => $array): ?>
				<tr>
					<td><div class="uap-list-affiliates-name-label"><?php echo $array['username'];?></div></td>
					<td><?php echo $array['reference'];?></td>
					<td><?php echo $array['amount'] . ' ' . $array['currency'];?></td>
					<td><?php echo uap_convert_date_to_us_format($array['date']);?></td>
					<th><?php 
						switch ($array['payment']){
							case 0:
								_e('UnPaid', 'uap');
								break;
							case 1:
								_e('Pending', 'uap');
								break;
							case 2:
								_e('Complete', 'uap');
								break;
						}
					?></th>
					<?php if (!empty($data['payments_settings'])):?>
						<td>
							<?php if (!empty($data['payments_settings']) && $data['payments_settings']['type']=='bt' && $data['payments_settings']['is_active']):?>
								<div class="uap-payment-details-bt-data"><?php echo $data['payments_settings']['settings'];?></div>
							<?php endif;?>								
						</td>
					<?php endif;?>					
				</tr>							
				<?php endforeach;?>
			</tbody>
		</table>
	<?php endif;?>
	<?php if (!empty($data['pagination'])) : ?>
		<?php echo $data['pagination'];?>
	<?php endif;?>
</div>