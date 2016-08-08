<div class="uap-register-form  <?php echo $data['template'];?>">
	<style><?php echo $data['css'];?></style>
	<form action="<?php echo @$data['action'];?>" method="post" name="<?php echo $data['form_name'];?>" id="<?php echo $data['form_id'];?>" class="uap-form-create-edit" enctype="multipart/form-data" >
		<?php if (!empty($data['form_fields'])):
				$i = 0;
		?>
			<?php foreach ($data['form_fields'] as $form_field): ?>
				<?php 
					$i++;
					if ($data['template']=='uap-register-6'):
						if ($i==1):?>
							<div class="uap-register-col">
						<?php endif;
						if ($i-1== ceil($data['count_register_fields']/2)):	?>							
							</div><div class="uap-register-col">
						<?php endif;?>
					<?php endif;?>
					<?php echo $form_field;?>
			<?php endforeach;?>
		<?php endif;?>
		<?php if ($data['template']=='uap-register-6'):?>
			</div>
		<?php endif;?>
		<?php if ($data['template']=='uap-register-7'):?>
			<div class="impu-temp7-row">
		<?php endif;?>
		<?php if (!empty($data['hiddens'])):?>
			<?php foreach ($data['hiddens'] as $value): ?>
				<?php echo $value;?>
			<?php endforeach;?>
		<?php endif;?>		
		<div class="uap-submit-form">
			<?php echo $data['submit_button'];?>
		</div>
		<?php if ($data['template']=='uap-register-7'):?>
		</div>
		<?php endif;?>
	</form>
</div>
	
<?php if (!empty($data['js'])): ?>
<script><?php echo $data['js'];?></script>
<?php endif;?>