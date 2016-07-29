<?php /* Smarty version 2.6.12, created on 2016-07-08 15:27:03
         compiled from subscribe.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'plang', 'subscribe.htm', 41, false),array('modifier', 'adesk_field_title', 'subscribe.htm', 75, false),array('function', 'adesk_field_html', 'subscribe.htm', 72, false),)), $this); ?>
<script type="text/javascript">
<!--
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "subscribe.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
-->
</script>



<?php if ($this->_tpl_vars['subscription_message']): ?>
	      <div class="row" style="margin-top:20px;">
      
      
        <div class="col-lg-12">  
	<div class="confirmation_box">
	 <ul><li class="label bg-info" style="font-size:14px;"><?php echo $this->_tpl_vars['subscription_message']; ?>
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
             <h4><?php echo ((is_array($_tmp='Subscribe')) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)); ?>
</h4>
            </header>
            <section style="height:210px" class="panel-body scrollbar scroll-y m-b">

 
	
		<div id="form">

			<form method="post" action="<?php echo $this->_tpl_vars['_']; ?>
/surround.php" id="subscribe_form" onsubmit="return subscribe_validate();">

				<input type="hidden" name="funcml" value="subscribe" />

		 <table border="0" cellspacing="0" cellpadding="5" width="100%">
								<tr>
									<td valign="top" ><?php echo ((is_array($_tmp='Email')) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)); ?>
</td>
									<td ><input type="text" name="email" id="subscribe_email" size="50" style="width:99%;" /></td>
								</tr>

								<tr>
									<td valign="top"><?php echo ((is_array($_tmp='First Name')) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)); ?>
</td>
									<td><input type="text" name="first_name" id="firstnameField" style="width:99%;" /></td>
								</tr>
								<tr>
									<td valign="top"><?php echo ((is_array($_tmp='Last Name')) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)); ?>
</td>
									<td><input type="text" name="last_name" id="lastnameField" style="width:99%;" /></td>
								</tr>
								<tbody id="custom_fields_table">

	<?php $_from = $this->_tpl_vars['custom_fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['field']):
?>
	<?php if ($this->_tpl_vars['field']['type'] == 6): ?>
									<?php echo smarty_function_adesk_field_html(array('field' => $this->_tpl_vars['field']), $this);?>

	<?php else: ?>
									<tr>
										<td width="75" <?php if ($this->_tpl_vars['field']['label'] == 1): ?>valign="top"<?php else: ?>align="left"<?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['field']['title'])) ? $this->_run_mod_handler('adesk_field_title', true, $_tmp, $this->_tpl_vars['field']['type']) : smarty_modifier_adesk_field_title($_tmp, $this->_tpl_vars['field']['type'])); ?>
</td>
										<td><?php echo smarty_function_adesk_field_html(array('field' => $this->_tpl_vars['field']), $this);?>
</td>
									</tr>
	<?php endif; ?>
	<?php endforeach; endif; unset($_from); ?>

								</tbody>
							</table>

							<div id="subscribe_use_captcha" class="<?php if ($this->_tpl_vars['show_captcha']): ?>adesk_block<?php else: ?>adesk_hidden<?php endif; ?>">
								<p><?php echo ((is_array($_tmp="Please also verify yourself by typing the text in the following image into the box below it.")) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)); ?>
</p>
								<br/>
								<?php if ($this->_tpl_vars['site']['gd']): ?>
								<img src="<?php echo $this->_tpl_vars['_']; ?>
/awebdesk/scripts/imgrand.php?rand=<?php echo $this->_tpl_vars['rand']; ?>
" /><br/>
								<?php endif; ?>
								<input type="text" name="imgverify" id="imgverify" />
							</div>
                            </div>
                            </section></section></div>
                         <div class="col-lg-6"> 
          <!-- scrollable inbox widget -->
          <section class="panel">
            <header class="panel-heading">
              <ul class="nav nav-pills pull-right">
                <li>
                  <a href="#" class="panel-toggle text-muted"><i class="fa fa-caret-down fa-lg text-active"></i><i class="fa fa-caret-up fa-lg text"></i></a>
                </li>
              </ul>
             <h4><?php echo ((is_array($_tmp='Subscribe To Lists')) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)); ?>
:</h4>
            </header>
            <section style="height:210px" class="panel-body scrollbar scroll-y m-b">
          
                            
	 
							<?php if (! $this->_tpl_vars['listfilter']): ?>
																<div id="parentsListBox">
									<?php $_from = $this->_tpl_vars['listsList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['l']):
?>
										<?php if (( ! $this->_tpl_vars['l']['private'] )): ?>
											<p>
												<label>
													<input type="checkbox" name="nlbox[]" id="subscribe_list_<?php echo $this->_tpl_vars['l']['id']; ?>
" value="<?php echo $this->_tpl_vars['l']['id']; ?>
" onclick="subscribe_list_loadfields()" />
													<?php echo $this->_tpl_vars['l']['name']; ?>

												</label>
											</p>
										<?php endif; ?>
									<?php endforeach; endif; unset($_from); ?>
								</div>
							<?php else: ?>
																<?php $_from = $this->_tpl_vars['listsList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['l']):
?>
									<?php if (( ! $this->_tpl_vars['l']['private'] )): ?>
										<p>
											<label>
												<input type="checkbox" name="nlbox[]" id="subscribe_list_<?php echo $this->_tpl_vars['l']['id']; ?>
" value="<?php echo $this->_tpl_vars['l']['id']; ?>
" onclick="subscribe_list_loadfields()" />
												<?php echo $this->_tpl_vars['l']['name']; ?>

											</label>
										</p>
									<?php endif; ?>
								<?php endforeach; endif; unset($_from); ?>
							<?php endif; ?>
							
				
						 
				 

 </section></section></div>

			<div class="row"><div class="col-lg-12"><div class="col-lg-6">
				  <input type="submit" value="<?php echo ((is_array($_tmp='Subscribe')) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)); ?>
" class="btn btn-info"/> 
				</div></div></div>


	 
        </div>
     
	  </div>
	  
	  
	 
		
		
		
		
		
		
		
		
		
		
		
		</form>
		

	
<?php endif; ?>