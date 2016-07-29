<?php /* Smarty version 2.6.12, created on 2016-07-08 14:47:32
         compiled from template.import.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'template.import.htm', 6, false),array('modifier', 'help', 'template.import.htm', 10, false),array('modifier', 'truncate', 'template.import.htm', 46, false),array('function', 'adesk_upload', 'template.import.htm', 69, false),)), $this); ?>
<div id="import" class="adesk_hidden">
  <form method="POST" onsubmit="template_import_save(); return false">
    <div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  border="0" cellspacing="0" cellpadding="5">
      <tr>
        <td>
          <label for="nameImportField"><?php echo ((is_array($_tmp='Template Name')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
        </td>
        <td>
          <input type="text" name="name" id="nameImportField" value="" size="45" />
          <?php echo ((is_array($_tmp="Brief Description for you to recognize. Does NOT affect your actual template.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

        </td>
      </tr>
		  <?php if (adesk_admin_ismaingroup ( )): ?>
			  <tr>
					<td><?php echo ((is_array($_tmp='Visibility')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
:</td>
					<td>
						<input type="radio" name="template_scope2" id="template_scope_all2" value="all" onclick="template_import_lists_toggle_scope(this.value);" />
						<label for="template_scope_all2"><?php echo ((is_array($_tmp='Available for all lists and users')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
						<br />
						<input type="radio" name="template_scope2" id="template_scope_specific2" value="specific" onclick="template_import_lists_toggle_scope(this.value);" />
						<label for="template_scope_specific2"><?php echo ((is_array($_tmp='Available for specific lists')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
					</td>
			  </tr>
		  <?php else: ?>
			  <tr>
					<td></td>
					<td>
						<div style="display: none;">
							<input type="radio" name="template_scope2" id="template_scope_all2" value="all" />
							<input type="radio" name="template_scope2" id="template_scope_specific2" value="specific" />
						</div>
					</td>
			  </tr>
		  <?php endif; ?>
		  <tbody id="template_import_lists">
	      <tr valign="top">
	        <td><?php echo ((is_array($_tmp="Used in Lists:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
	        <td>

						<input id="parentsList2" type="checkbox" value="p[]" checked="checked" style="display: none;" />
						<div class="adesk_checkboxlist">
							<?php $_from = $this->_tpl_vars['listsList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['p']):
?>
								<div>
									<label>
										<input type="checkbox" id="p_<?php echo $this->_tpl_vars['p']['id']; ?>
" name="p[]" value="<?php echo $this->_tpl_vars['p']['id']; ?>
" <?php if (count ( $this->_tpl_vars['listsList'] ) == 1): ?>checked="checked"<?php endif; ?> />
										<?php echo ((is_array($_tmp=$this->_tpl_vars['p']['name'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 50) : smarty_modifier_truncate($_tmp, 50)); ?>

									</label>
								</div>
							<?php endforeach; endif; unset($_from); ?>
						</div>
						<div align="right" style="width: 300px;">
							<a href="javascript: $('parentsList2').checked = true; adesk_form_check_all($('parentsList2'));"><?php echo ((is_array($_tmp='Select All')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
							|
							<a href="javascript: $('parentsList2').checked = false; adesk_form_check_all($('parentsList2'));"><?php echo ((is_array($_tmp='Select None')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
						</div>

	        </td>
	      </tr>
      </tbody>
      <tr>
        <td valign="top">
          <label for="import_file"><?php echo ((is_array($_tmp='Upload File')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
        </td>
        <td id="template_import_upload_td"></td>
      </tr>
    </table></div>

    <div id="template_import_upload_div" class="adesk_hidden">
      <?php echo smarty_function_adesk_upload(array('id' => 'import_file','name' => 'import','action' => 'template_import','limit' => 1), $this);?>

			<div class="external_form_help">
			  <?php echo ((is_array($_tmp="Must be an XML file formatted specifically for importing into this mailing software.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

			</div>
    </div>

    <br />
    <div>
      <input type="button" id="import_submit" class="adesk_button_import" value="<?php echo ((is_array($_tmp='Import')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="template_import_save()" />
      <input type="button" id="import_back" class="adesk_button_back" value="<?php echo ((is_array($_tmp='Back')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="window.history.go(-1)" />
    	<?php if (adesk_admin_ismaingroup ( )): ?>
	    	<span style="float: right;">
	    		<button type="button" onclick="template_import_stock();" style="font-family: Arial,Helvetica,sans-serif; font-size: 12px;"><?php echo ((is_array($_tmp="Re-Import Stock Templates")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</button>
	    		<?php echo ((is_array($_tmp="This button will re-import all stock templates. PLEASE NOTE: This will only import global stock templates you are missing. If you already have some stock templates, but have modified them, it will NOT overwrite customizations made.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

	    	</span>
    	<?php endif; ?>
    </div>
    <input type="submit" style="display:none"/>
  </form>
</div>