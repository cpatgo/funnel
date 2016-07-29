<?php /* Smarty version 2.6.12, created on 2016-07-08 14:21:03
         compiled from design.form.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'design.form.htm', 7, false),array('modifier', 'help', 'design.form.htm', 187, false),array('function', 'adesk_upload', 'design.form.htm', 26, false),)), $this); ?>
<div id="form" class="adesk_hidden">
  <form method="POST" onsubmit="design_form_save(design_form_id); return false">
    <input type="hidden" name="id" id="form_id" />

    <div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td width="100" valign="top"><?php echo ((is_array($_tmp='Software name')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
				<td valign="top">
					<div class="adesk_help_inline"><?php echo ((is_array($_tmp="The software name is used for the title of the application and on some default & notification style emails.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
					<input type="text" name="site_name" id="site_name" style="width:400px;" />			  </td>
			</tr>
			<tbody id="design_logo_row" class="adesk_table_rowgroup">
			<tr>
			  <td valign="top">&nbsp;</td>
			  <td valign="top">&nbsp;</td>
			  </tr>
			<tr>
				<td valign="top"><?php echo ((is_array($_tmp='Logo')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
				<td valign="top">
					<div class="adesk_help_inline"><?php echo ((is_array($_tmp="The logo is only shown on the main login page for logging into the admin area of your software.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
					<select name="logo_source" id="logo_source" onchange="design_toggle_source(this.value);" <?php if ($this->_tpl_vars['__ishosted']): ?>style="display:none;"<?php endif; ?>>
						<option value="upload"><?php echo ((is_array($_tmp='Upload')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
						<option value="url"><?php echo ((is_array($_tmp='URL')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
					</select>
					<span id="design_upload_div">
						<?php echo smarty_function_adesk_upload(array('id' => '_attachments_','action' => 'design_upload','relid' => 0,'limit' => 1), $this);?>
					</span>
					<span id="design_url_div">
						<input type="text" name="design_url" id="design_url" onblur="design_preview_url();" style="width:327px;" />

							<div id="design_image_div">
		<img id="design_image" /><!--src="images/logo.gif"-->
	</div>
					</span>			  </td>
			</tr>
			</tbody>
			<tr>
			  <td valign="top">&nbsp;</td>
			  <td valign="top">&nbsp;</td>
	  </tr>
			<tr>
				<td valign="top"><?php echo ((is_array($_tmp="E-mail Header")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
				<td valign="top">
				<div class="adesk_help_inline"><?php echo ((is_array($_tmp="You are able to specify a block of content that will be included at the top of all emails sent for this user group.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
					<div>
						<input name="header_text" id="header_text" type="checkbox" value="text" onclick="design_toggle_editor('header', 'text')" /> <label for="header_text"><?php echo ((is_array($_tmp="Enable non-removable header for text messages")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
					</div>

					<div id="header_text_div" style="margin-left:25px;">
						<textarea name="header_text_value" id="header_text_value" class="text_ruler" cols="" rows="" style="width:97%; height:150px;"></textarea>
					</div>

					<div>
						<input name="header_html" id="header_html" type="checkbox" value="html" onclick="design_toggle_editor('header', 'html')" /> <label for="header_html"><?php echo ((is_array($_tmp="Enable non-removable header for HTML messages")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
					</div>

					<div id="header_html_div" style="margin-left:25px;">
						<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "tinymce.htm", 'smarty_include_vars' => array('id' => 'header_html_value','name' => 'header_html_valueEditor','ishtml' => $this->_tpl_vars['admin']['htmleditor'],'content' => "",'width' => "100%",'height' => '150px')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>					</div>			  </td>
			</tr>
			<tr>
			  <td valign="top">&nbsp;</td>
			  <td valign="top">&nbsp;</td>
	  </tr>
			<tr>
				<td valign="top"><?php echo ((is_array($_tmp="E-mail Footer")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
				<td valign="top">
				<div class="adesk_help_inline"><?php echo ((is_array($_tmp="You are able to specify a block of content that will be included at the bottom of all emails sent for this user group.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
					<div>
						<input name="footer_text" id="footer_text" type="checkbox" value="text" onclick="design_toggle_editor('footer', 'text')" /> <label for="footer_text"><?php echo ((is_array($_tmp="Enable non-removable footer for text messages")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
					</div>

					<div id="footer_text_div" style="margin-left:25px;">
						<textarea name="footer_text_value" id="footer_text_value" class="text_ruler" cols="" rows="" style="width:97%; height:150px;"></textarea>
					</div>

					<div>
						<input name="footer_html" id="footer_html" type="checkbox" value="html" onclick="design_toggle_editor('footer', 'html')" /> <label for="footer_html"><?php echo ((is_array($_tmp="Enable non-removable footer for HTML messages")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
					</div>

					<div id="footer_html_div" style="margin-left:25px;">
						<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "tinymce.htm", 'smarty_include_vars' => array('id' => 'footer_html_value','name' => 'footer_html_valueEditor','ishtml' => $this->_tpl_vars['admin']['htmleditor'],'content' => "",'width' => "100%",'height' => '150px')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>					</div>			  </td>
			</tr>

			<tr>
			  <td valign="top">&nbsp;</td>
			  <td valign="top">&nbsp;</td>
	  </tr>
			<tr>
				<td valign="top"><?php echo ((is_array($_tmp="Templates & Styles")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
				<td valign="top">
<div class="adesk_help_inline"><span style="color:red; font-weight:bolder;">For security reasons , we have removed html/css customisation through admin. You must update it by editing html files inside templates directory</span></div>
    <div>
      <label>
        <input type="hidden" id="admin_form_template_show" name="admin_template_show" value="1" onclick="$('admin_box_template').className=this.checked?'adesk_blockquote':'adesk_hidden';" />
        <?php echo ((is_array($_tmp='Customize Admin Section Template')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
      </label>
    </div>
    <div id="admin_box_template" class="adesk_hidden">
      <div style="background:#F0F0F0; padding:5px; border:1px solid #CCCCCC; border-bottom:0px;">
        <select size="1" style="float: right; margin:0px; margin-top:-3px;" onclick="adesk_form_insert_cursor($('admin_form_template'), this.value);this.selectedIndex=0;">
          <option value="" selected="selected"><?php echo ((is_array($_tmp="Insert Tag...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
          <option value="%PAGECONTENT%"><?php echo ((is_array($_tmp='Page Content')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
          <option value="%HEADERNAV%"><?php echo ((is_array($_tmp='Header Navigation')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
          <option value="%SITENAME%"><?php echo ((is_array($_tmp='Site Name')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
          <option value="%SITEURL%"><?php echo ((is_array($_tmp='Site URL')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
          <option value="%SEARCHBAR%"><?php echo ((is_array($_tmp='Search Bar')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
		  <option value="%FOOTER%"><?php echo ((is_array($_tmp='Footer')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
        </select>
        <a href="#" onclick="var obj=$('admin_form_template');obj.value=admin_template_htm;obj.focus();return false;"><?php echo ((is_array($_tmp='Reset to default template')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>      </div>
      <div style="border:1px solid #CCCCCC;">
        <textarea id="admin_form_template" name="admin_template" style="width:100%; height:5px; border:0px;" disabled="disabled"></textarea>
      </div>
    </div>

    <div>
      <label>
        <input type="hidden" id="admin_form_style_show" name="admin_style_show" value="1" onclick="$('admin_box_style').className=this.checked?'adesk_blockquote':'adesk_hidden';" />
        <?php echo ((is_array($_tmp='Customize Admin Section CSS Styles')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
      </label>
    </div>
    <div id="admin_box_style" class="adesk_hidden">
      <div class="adesk_help_inline"><?php echo ((is_array($_tmp="All CSS styles added will override any existing styles.  For instance if you wish to change the background color to red you would put")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
: <?php echo '<em>body{ background-color:red; }'; ?>
</em></div>
      <div style="background:#F0F0F0; padding:5px; border:1px solid #CCCCCC; border-bottom:0px;">
        <a href="#" onclick="var obj=$('admin_form_style');obj.value='';obj.focus();return false;"><?php echo ((is_array($_tmp='Clear all custom CSS styles')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>      </div>
      <div style="border:1px solid #CCCCCC;">
        <textarea id="admin_form_style" name="admin_style" style="width:100%; height:5px; border:0px;" disabled="disabled"></textarea>
      </div>
    </div>

    <div>
      <label>
        <input type="hidden" id="public_form_template_show" name="public_template_show" value="1" onclick="$('public_box_template').className=this.checked?'adesk_blockquote':'adesk_hidden';" />
        <?php echo ((is_array($_tmp='Customize Public Section Template')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
      </label>
    </div>
    <div id="public_box_template" class="adesk_hidden">
      <div style="background:#F0F0F0; padding:5px; border:1px solid #CCCCCC; border-bottom:0px;">
        <select size="1" style="float: right; margin:0px; margin-top:-3px;" onclick="adesk_form_insert_cursor($('public_form_template'), this.value);this.selectedIndex=0;">
          <option value="" selected="selected"><?php echo ((is_array($_tmp="Insert Tag...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
          <option value="%PAGECONTENT%"><?php echo ((is_array($_tmp='Page Content')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
          <option value="%HEADERNAV%"><?php echo ((is_array($_tmp='Header Navigation')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
          <option value="%FOOTERNAV%"><?php echo ((is_array($_tmp='Footer Navigation')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
          <option value="%SITENAME%"><?php echo ((is_array($_tmp='Site Name')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
          <option value="%SITEURL%"><?php echo ((is_array($_tmp='Site URL')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
          <option value="%LANGSELECT%"><?php echo ((is_array($_tmp='Language Selector')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
        </select>
        <a href="#" onclick="var obj=$('public_form_template');obj.value=public_template_htm;obj.focus();return false;"><?php echo ((is_array($_tmp='Reset to default template')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>      </div>
      <div style="border:1px solid #CCCCCC;">
        <textarea id="public_form_template" name="public_template" style="width:100%; height:5px; border:0px;" disabled="disabled"></textarea>
      </div>
    </div>

    <div>
      <label>
        <input type="hidden" id="public_form_style_show" name="public_style_show" value="1" onclick="$('public_box_style').className=this.checked?'adesk_blockquote':'adesk_hidden';" />
        <?php echo ((is_array($_tmp='Customize Public Section CSS Styles')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
      </label>
    </div>
    <div id="public_box_style" class="adesk_hidden">
      <div class="adesk_help_inline"><?php echo ((is_array($_tmp="All CSS styles added will override any existing styles.  For instance if you wish to change the background color to red you would put")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
: <?php echo '<em>body{ background-color:red; }'; ?>
</em></div>
      <div style="background:#F0F0F0; padding:5px; border:1px solid #CCCCCC; border-bottom:0px;">
        <a href="#" onclick="var obj=$('public_form_style');obj.value='';obj.focus();return false;"><?php echo ((is_array($_tmp='Clear all custom CSS styles')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>      </div>
      <div style="border:1px solid #CCCCCC;">
        <textarea id="public_form_style" name="public_style" style="width:100%; height:5px; border:0px;" disabled="disabled"></textarea>
      </div>
    </div>

    	</td>
			</tr>

		<tr>
		  <td valign="top">&nbsp;</td>
		  <td valign="top">&nbsp;</td>
		</tr>

		<tr>
		  <td valign="top"><a href="javascript: design_advanced_toggle();"><?php echo ((is_array($_tmp='Advanced Options')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></td>
		  <td valign="top">&nbsp;</td>
		</tr>

		<tr>
		  <td valign="top">&nbsp;</td>
		  <td valign="top">&nbsp;</td>
		</tr>

		<tbody id="design_advanced_tbody" style="display: none;">
			<tr>
				<td valign="top"><?php echo ((is_array($_tmp='Other Options')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
				<td valign="top">
					<div class="adesk_help_inline"><?php echo ((is_array($_tmp="You can hide application information such as the company who developed the application, links to external sites, etc.. This is useful if you are reselling the software as a service to your clients and want to keep all branding only referencing your company.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
				  <label><input type="checkbox" name="copyright" id="copyright" /> <?php echo ((is_array($_tmp='Hide copyright notice')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
				  <?php echo ((is_array($_tmp="License agreement does not allow you to set your own copyright, but you may opt to have the copyright invisibe to public/non-code view. Copyrights may never be removed from the actual code.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>
<br />
				  <label><input type="checkbox" name="version" id="version" /> <?php echo ((is_array($_tmp='Hide version number')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label><br />
				  <label><input type="checkbox" name="license" id="license" /> <?php echo ((is_array($_tmp='Hide license information')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label><br />
				  <label><input type="checkbox" name="links" id="links" /> <?php echo ((is_array($_tmp='Hide product links')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label><br />
				  <label><input type="checkbox" name="help" id="help" /> <?php echo ((is_array($_tmp='Hide external help')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label><br />
				  <?php if (! $this->_tpl_vars['__ishosted']): ?>
				  	<label><input type="checkbox" name="demo" id="demo" onclick="design_demomode_alert();" /> <?php echo ((is_array($_tmp='Enable demo mode')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label>
				  	<?php echo ((is_array($_tmp="If selected, the software will disable most functions such as deleting, sending and more.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

					<?php else: ?>
				  	<input type="hidden" name="demo" id="demo" />
				  <?php endif; ?>
				</td>
			</tr>

		</tbody>

		<tr>
		  <td valign="top">&nbsp;</td>
		  <td valign="top">&nbsp;</td>
	  </tr>

    </table></div>

    <br />
    <div>
	  <?php if (! $this->_tpl_vars['demoMode']): ?>
      <input type="button" id="form_submit" class="adesk_button_submit" value="<?php echo ((is_array($_tmp='Submit')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="design_form_save(design_form_id)" />
	  <?php else: ?>
	  <span class="demoDisabled2"><?php echo ((is_array($_tmp='Disabled in demo')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span>
	  <?php endif; ?>
      <input type="button" id="form_back" class="adesk_button_back" value="<?php echo ((is_array($_tmp='Back')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="window.history.go(-1)" />
          </div>
    <input type="submit" style="display:none"/>
  </form>
</div>