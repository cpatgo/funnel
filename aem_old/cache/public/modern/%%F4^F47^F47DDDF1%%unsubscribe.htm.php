<?php /* Smarty version 2.6.12, created on 2016-07-08 16:17:42
         compiled from unsubscribe.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'adesk_calendar', 'unsubscribe.htm', 7, false),array('function', 'adesk_field_html', 'unsubscribe.htm', 74, false),array('modifier', 'plang', 'unsubscribe.htm', 39, false),array('modifier', 'adesk_field_title', 'unsubscribe.htm', 77, false),)), $this); ?>
<script type="text/javascript">
<!--
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "unsubscribe.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
-->
</script>
<?php echo smarty_function_adesk_calendar(array('base' => ""), $this);?>



<?php if ($this->_tpl_vars['unsubscription_message']): ?>
 <div class="row" style="margin-top:20px;">
      
      
        <div class="col-lg-12">  
	<div class="confirmation_box">
	 
<ul><li class="label bg-info" style="font-size:14px;"><?php echo $this->_tpl_vars['unsubscription_message']; ?>
</li></ul></div>

 
	 </div>
	</div>

<?php else: ?>


      <div class="row" style="margin-top:20px;">
      
      
        <div class="col-lg-6">  
      
          
          <section class="panel">
            <header class="panel-heading">
              <ul class="nav nav-pills pull-right">
                <li>
                  <a href="#" class="panel-toggle text-muted"><i class="fa fa-caret-down fa-lg text-active"></i><i class="fa fa-caret-up fa-lg text"></i></a>
                </li>
              </ul>
             <h4><?php echo ((is_array($_tmp='Unsubscribe')) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)); ?>
</h4>
            </header>
            <section style="height:180px" class="panel-body scrollbar scroll-y m-b">

 
	 

			<form method="post" action="<?php echo $this->_tpl_vars['_']; ?>
/surround.php" id="unsubscribe_form" onsubmit="return unsubscribe_validate();">

				<input type="hidden" name="funcml" value="unsubscribe" />

				
							<p>
								<label for="email"><?php echo ((is_array($_tmp="Your e-mail address (Required)")) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)); ?>
</label>
								<input type="text" name="email" id="email" size="30" />
							</p>

						  <div id="unsubscribe_use_captcha" class="<?php if ($this->_tpl_vars['show_captcha']): ?>adesk_block<?php else: ?>adesk_hidden<?php endif; ?>">
								<p><?php echo ((is_array($_tmp="Please also verify yourself by typing the text in the following image into the box below it.")) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)); ?>
</p>
								<br/>
								<?php if ($this->_tpl_vars['site']['gd']): ?>
								<img src="<?php echo $this->_tpl_vars['_']; ?>
/awebdesk/scripts/imgrand.php?rand=<?php echo $this->_tpl_vars['rand']; ?>
" /><br/>
								<?php endif; ?>
								<input type="text" name="imgverify" />
							</div>
                            
                            <table width="100%">
					<tr>
						<td width="400" valign="top">
			      <tbody id="custom_fields_table" class="adesk_hidden">

							<?php $_from = $this->_tpl_vars['custom_fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['field']):
?>

								<?php if ($this->_tpl_vars['field']['type'] == 6): ?>
									<?php echo smarty_function_adesk_field_html(array('field' => $this->_tpl_vars['field']), $this);?>

								<?php else: ?>
						      <tr>
						        <td width="75"><?php echo ((is_array($_tmp=$this->_tpl_vars['field']['title'])) ? $this->_run_mod_handler('adesk_field_title', true, $_tmp, $this->_tpl_vars['field']['type']) : smarty_modifier_adesk_field_title($_tmp, $this->_tpl_vars['field']['type'])); ?>
</td>
						        <td><?php echo smarty_function_adesk_field_html(array('field' => $this->_tpl_vars['field']), $this);?>
</td>
						      </tr>
								<?php endif; ?>

							<?php endforeach; endif; unset($_from); ?>

			      </tbody>

				</table>
                            
                    </section></section></div>
                    
                            
                            
                            
                            
                            
                            
                            
<?php if (! $this->_tpl_vars['listfilter']): ?>
<div class="col-lg-6"> 
          <!-- scrollable inbox widget -->
          <section class="panel">
            <header class="panel-heading">
              <ul class="nav nav-pills pull-right">
                <li>
                  <a href="#" class="panel-toggle text-muted"><i class="fa fa-caret-down fa-lg text-active"></i><i class="fa fa-caret-up fa-lg text"></i></a>
                </li>
              </ul>
             <h4><?php echo ((is_array($_tmp='Select Lists To Unsubscribe From')) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)); ?>
</h4>
            </header>
            <section style="height:180px" class="panel-body scrollbar scroll-y m-b">
          
                            
						 
							<div id="parentsListBox">
	<?php $_from = $this->_tpl_vars['listsList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['l']):
?>
		<?php if (( ! $this->_tpl_vars['l']['private'] )): ?>
										<p>
											<label>
												<input type="checkbox" name="nlbox[]" id="unsubscribe_list_<?php echo $this->_tpl_vars['l']['id']; ?>
" value="<?php echo $this->_tpl_vars['l']['id']; ?>
" onclick="unsubscribe_list_loadfields()" />
												<?php echo $this->_tpl_vars['l']['name']; ?>

											</label>
										</p>
		<?php endif; ?>
	<?php endforeach; endif; unset($_from); ?>
							</div>
                            
                            </section></section></div>
                            
<?php else: ?>
	<?php if (is_array ( $this->_tpl_vars['listfilter'] )): ?>
		<?php $_from = $this->_tpl_vars['listfilter']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['l']):
?>
							<input type="hidden" name="nlbox[]" value="<?php echo $this->_tpl_vars['l']; ?>
" />
		<?php endforeach; endif; unset($_from); ?>
	<?php else: ?>
							<input type="hidden" name="nlbox[]" value="<?php echo $this->_tpl_vars['listfilter']; ?>
" />
	<?php endif; ?>
<?php endif; ?>
				





			 <div class="row"><div class="col-lg-12"><div class="col-lg-6">			


 
					<p><input type="submit" value="<?php echo ((is_array($_tmp='Unsubscribe')) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)); ?>
" class="btn btn-info"/></p>
				 
</div></div></div><div>
			</form>

	 

	
<?php endif; ?>

<?php if ($this->_tpl_vars['ask4reason']): ?>
<div class="row"><div class="col-lg-12"><div class="col-lg-6">		
	<hr size="1" width="100%" noshade />

	<form action="<?php echo $this->_tpl_vars['_']; ?>
/surround.php" method="post">
		<?php echo ((is_array($_tmp="(Optional) Why did you decide to unsubscribe?")) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)); ?>

		<br />
		<input type="hidden" name="funcml" value="unsubreason" />
				<input type="hidden" name="nl" value="<?php echo $this->_tpl_vars['lists']; ?>
" />
		<input type="hidden" name="codes" value="<?php echo $this->_tpl_vars['codes']; ?>
" />
		<input type="hidden" name="p" value="<?php echo $this->_tpl_vars['p']; ?>
" />
		<?php if (isset ( $_GET['c'] )): ?>
		<input type="hidden" name="c" value="<?php echo $_GET['c']; ?>
"/>
		<?php endif; ?>
		<?php if (isset ( $_GET['m'] )): ?>
		<input type="hidden" name="m" value="<?php echo $_GET['m']; ?>
"/>
		<?php endif; ?>
		<input type="hidden" name="s" value="<?php echo $this->_tpl_vars['hash']; ?>
" />
		<textarea name="reason"></textarea>
		<br /><div class="row"><div class="col-lg-12"><div class="col-lg-6" style="margin-top:10px;">
		<input type="submit" value="<?php echo ((is_array($_tmp='Send')) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)); ?>
"  class="btn btn-info"/></div></div></div>
	</form>
</div></div></div>
<?php endif; ?>