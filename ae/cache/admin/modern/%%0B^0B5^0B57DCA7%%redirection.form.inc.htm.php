<?php /* Smarty version 2.6.12, created on 2016-07-08 17:09:18
         compiled from redirection.form.inc.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'redirection.form.inc.htm', 6, false),)), $this); ?>
        <div id="redirections" class="adesk_block">

          <div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  border="0" cellspacing="0" cellpadding="5">
          	<tbody id="redirection_subscription">
	            <tr>
	              <td valign="top"><strong><?php echo ((is_array($_tmp='Subscription')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</strong></td>
	              <td valign="top">&nbsp;</td>
	            </tr>
	            <tr>
	              <td valign="top"><label for="sub1"><?php echo ((is_array($_tmp='Successful Subscription')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label></td>
	              <td valign="top">
		              <select name="sub1" id="sub1" onchange="form_completion_change('sub1_' + this.value)">
						<option value="default"><?php echo ((is_array($_tmp='Default Message')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
						<option value="custom"><?php echo ((is_array($_tmp='Custom Message')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
						<option value="redirect" selected="selected"><?php echo ((is_array($_tmp='Redirect to URL')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
		              </select>
				  				<div id="sub1EditorDiv">
				  					<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "tinymce.htm", 'smarty_include_vars' => array('id' => 'sub1','name' => 'sub1Editor','ishtml' => 0,'content' => "",'width' => "100%",'height' => '150px')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
									</div>
									<br />
				  				<input name="sub1_redirect" id="sub1_redirect" style="width:500px;" />
				  			</td>
	            </tr>
	            <tr>
	              <td valign="top"><label for="sub2"><?php echo ((is_array($_tmp='Awaiting Confirmation')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label></td>
	              <td valign="top">
		              <select name="sub2" id="sub2" onchange="form_completion_change('sub2_' + this.value)">
						<option value="default"><?php echo ((is_array($_tmp='Default Message')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
						<option value="custom"><?php echo ((is_array($_tmp='Custom Message')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
						<option value="redirect" selected="selected"><?php echo ((is_array($_tmp='Redirect to URL')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
		              </select>
					  			<div id="sub2EditorDiv">
										<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "tinymce.htm", 'smarty_include_vars' => array('id' => 'sub2','name' => 'sub2Editor','ishtml' => 0,'content' => "",'width' => "100%",'height' => '150px')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
									</div>
									<br />
					  			<input name="sub2_redirect" id="sub2_redirect" style="width:500px;" />
	              </td>
	            </tr>
	            <tr>
	              <td valign="top"><label for="sub3"><?php echo ((is_array($_tmp='Confirmed Subscription')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label></td>
	              <td valign="top">
		              <select name="sub3" id="sub3" onchange="form_completion_change('sub3_' + this.value)">
						<option value="default"><?php echo ((is_array($_tmp='Default Message')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
						<option value="custom"><?php echo ((is_array($_tmp='Custom Message')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
						<option value="redirect" selected="selected"><?php echo ((is_array($_tmp='Redirect to URL')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
		              </select>
					  			<div id="sub3EditorDiv">
										<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "tinymce.htm", 'smarty_include_vars' => array('id' => 'sub3','name' => 'sub3Editor','ishtml' => 0,'content' => "",'width' => "100%",'height' => '150px')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
									</div>
									<br />
					  			<input name="sub3_redirect" id="sub3_redirect" style="width:500px;" />
	              </td>
	            </tr>
	            <tr>
	              <td valign="top"><label for="sub4"><?php echo ((is_array($_tmp='Subscription Error')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label></td>
	              <td valign="top">
		              <select name="sub4" id="sub4" onchange="form_completion_change('sub4_' + this.value)">
						<option value="default"><?php echo ((is_array($_tmp='Default Message')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
						<option value="custom"><?php echo ((is_array($_tmp='Custom Message')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
						<option value="redirect" selected="selected"><?php echo ((is_array($_tmp='Redirect to URL')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
		              </select>
					  			<div id="sub4EditorDiv">
										<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "tinymce.htm", 'smarty_include_vars' => array('id' => 'sub4','name' => 'sub4Editor','ishtml' => 0,'content' => "",'width' => "100%",'height' => '150px')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
									</div>
									<br />
					  			<input name="sub4_redirect" id="sub4_redirect" style="width:500px;" />
				  			</td>
	            </tr>
            </tbody>

            <tbody id="redirection_unsubscription">
	            <tr>
	              <td valign="top"><strong><?php echo ((is_array($_tmp='UnSubscription')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</strong></td>
	              <td valign="top">&nbsp;</td>
	            </tr>
	            <tr>
	              <td valign="top"><label for="unsub1"><?php echo ((is_array($_tmp='Successful UnSubscription')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label></td>
	              <td valign="top">
		              <select name="unsub1" id="unsub1" onchange="form_completion_change('unsub1_' + this.value)">
						<option value="default"><?php echo ((is_array($_tmp='Default Message')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
						<option value="custom"><?php echo ((is_array($_tmp='Custom Message')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
						<option value="redirect" selected="selected"><?php echo ((is_array($_tmp='Redirect to URL')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
		              </select>
					  			<div id="unsub1EditorDiv">
										<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "tinymce.htm", 'smarty_include_vars' => array('id' => 'unsub1','name' => 'unsub1Editor','ishtml' => 0,'content' => "",'width' => "100%",'height' => '150px')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
									</div>
									<br />
					  			<input name="unsub1_redirect" id="unsub1_redirect" style="width:500px;" />
	              </td>
	            </tr>
	            <tr>
	              <td valign="top"><label for="unsub2"><?php echo ((is_array($_tmp='Awaiting Confirmation')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label></td>
	              <td valign="top">
		              <select name="unsub2" id="unsub2" onchange="form_completion_change('unsub2_' + this.value)">
						<option value="default"><?php echo ((is_array($_tmp='Default Message')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
						<option value="custom"><?php echo ((is_array($_tmp='Custom Message')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
						<option value="redirect" selected="selected"><?php echo ((is_array($_tmp='Redirect to URL')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
		              </select>
					  			<div id="unsub2EditorDiv">
										<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "tinymce.htm", 'smarty_include_vars' => array('id' => 'unsub2','name' => 'unsub2Editor','ishtml' => 0,'content' => "",'width' => "100%",'height' => '150px')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
									</div>
									<br />
					  			<input name="unsub2_redirect" id="unsub2_redirect" style="width:500px;" />
	              </td>
	            </tr>
	            <tr>
	              <td valign="top"><label for="unsub3"><?php echo ((is_array($_tmp='Confirmed UnSubscription')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label></td>
	              <td valign="top">
		              <select name="unsub3" id="unsub3" onchange="form_completion_change('unsub3_' + this.value)">
						<option value="default"><?php echo ((is_array($_tmp='Default Message')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
						<option value="custom"><?php echo ((is_array($_tmp='Custom Message')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
						<option value="redirect" selected="selected"><?php echo ((is_array($_tmp='Redirect to URL')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
		              </select>
					  			<div id="unsub3EditorDiv">
										<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "tinymce.htm", 'smarty_include_vars' => array('id' => 'unsub3','name' => 'unsub3Editor','ishtml' => 0,'content' => "",'width' => "100%",'height' => '150px')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
									</div>
									<br />
					  			<input name="unsub3_redirect" id="unsub3_redirect" style="width:500px;" />
	              </td>
	            </tr>
	            <tr>
	              <td valign="top"><label for="unsub4"><?php echo ((is_array($_tmp='UnSubscription Error')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label></td>
	              <td valign="top">
		              <select name="unsub4" id="unsub4" onchange="form_completion_change('unsub4_' + this.value)">
						<option value="default"><?php echo ((is_array($_tmp='Default Message')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
						<option value="custom"><?php echo ((is_array($_tmp='Custom Message')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
						<option value="redirect" selected="selected"><?php echo ((is_array($_tmp='Redirect to URL')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
		              </select>
					  			<div id="unsub4EditorDiv">
										<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "tinymce.htm", 'smarty_include_vars' => array('id' => 'unsub4','name' => 'unsub4Editor','ishtml' => 0,'content' => "",'width' => "100%",'height' => '150px')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
									</div>
									<br />
					  			<input name="unsub4_redirect" id="unsub4_redirect" style="width:500px;" />
	              </td>
	            </tr>
            </tbody>

            <tbody id="redirection_other">
	            <tr>
	              <td valign="top"><strong><?php echo ((is_array($_tmp='Other')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</strong></td>
	              <td valign="top">&nbsp;</td>
	            </tr>
	            <tr>
	              <td valign="top"><label for="up1"><?php echo ((is_array($_tmp='Request to update subscription details')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label></td>
	              <td valign="top">
		              <select name="up1" id="up1" onchange="form_completion_change('up1_' + this.value)">
						<option value="default"><?php echo ((is_array($_tmp='Default Message')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
						<option value="custom"><?php echo ((is_array($_tmp='Custom Message')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
						<option value="redirect" selected="selected"><?php echo ((is_array($_tmp='Redirect to URL')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
		              </select>
					  			<div id="up1EditorDiv">
										<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "tinymce.htm", 'smarty_include_vars' => array('id' => 'up1','name' => 'up1Editor','ishtml' => 0,'content' => "",'width' => "100%",'height' => '150px')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
									</div>
									<br />
					  			<input name="up1_redirect" id="up1_redirect" style="width:500px;" />
	              </td>
	            </tr>
	            <tr>
	              <td valign="top"><label for="up2"><?php echo ((is_array($_tmp='Updated subscription details')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label></td>
	              <td valign="top">
		              <select name="up2" id="up2" onchange="form_completion_change('up2_' + this.value)">
						<option value="default"><?php echo ((is_array($_tmp='Default Message')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
						<option value="custom"><?php echo ((is_array($_tmp='Custom Message')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
						<option value="redirect" selected="selected"><?php echo ((is_array($_tmp='Redirect to URL')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
		              </select>
					  			<div id="up2EditorDiv">
										<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "tinymce.htm", 'smarty_include_vars' => array('id' => 'up2','name' => 'up2Editor','ishtml' => 0,'content' => "",'width' => "100%",'height' => '150px')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
									</div>
									<br />
					  			<input name="up2_redirect" id="up2_redirect" style="width:500px;" />
	              </td>
	            </tr>
            </tbody>

  				</table></div>
        </div>