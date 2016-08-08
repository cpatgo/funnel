<div class="uap-subtab-menu">
	<?php 
		foreach ($data['submenu'] as $url=>$name){
			?>
			<a href="<?php echo $url;?>" class="uap-subtab-menu-item"><?php echo $name;?></a>
			<?php 
		}
	?>
</div>