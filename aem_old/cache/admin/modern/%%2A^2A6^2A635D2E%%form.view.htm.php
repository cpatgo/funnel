<?php /* Smarty version 2.6.12, created on 2016-07-08 17:09:18
         compiled from form.view.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'form.view.htm', 5, false),array('modifier', 'help', 'form.view.htm', 61, false),)), $this); ?>
<div id="view" class="adesk_hidden">


<div id="formview_integration" class="h2_wrap_static">
	<h5><?php echo ((is_array($_tmp="What type of integration would you like?")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h5><div class="line"></div>
	<div class="h2_content">
		<div class="icon_box_selector">
			<a href="#" id="form_select_html" onclick="return form_view_switch('html');" class="selected" style="height:52px; width:250px;">
			<div style="float:left; margin-right:15px;"><img src="images/code_line.png" width="32" height="32" border="0" /></div>
			<strong><?php echo ((is_array($_tmp='HTML')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</strong><br />
			<?php echo ((is_array($_tmp='To paste in your web site')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

			</a>

			<a href="#" id="form_select_link" onclick="return form_view_switch('link');" style="height:52px; width:250px;">
			<div style="float:left; margin-right:15px;"><img src="images/window_next.png" width="32" height="32" border="0" /></div>
			<strong><?php echo ((is_array($_tmp='Link')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</strong><br />
			<?php echo ((is_array($_tmp='Link to your form')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

			</a>

			<a href="#" id="form_select_popup" onclick="return form_view_switch('popup');" style="height:52px; width:250px;">
			<div style="float:left; margin-right:15px;"><img src="images/windows.png" width="32" height="32" border="0" /></div>
			<strong><?php echo ((is_array($_tmp='Popup')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</strong><br />
			<?php echo ((is_array($_tmp='Form opens in a popup')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

			</a>

			<a href="#" id="form_select_xml" onclick="return form_view_switch('xml');" style="display:none; height:52px; width:250px;">
			<div style="float:left; margin-right:15px;"><img src="images/film.png" width="32" height="32" border="0" /></div>
			<strong><?php echo ((is_array($_tmp="SWF/XML")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</strong><br />
			<?php echo ((is_array($_tmp='Embed in a Flash movie')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

			</a>
		</div>
		<br clear="left" />
	</div>
</div>

<br />

<div id="formview_code" class="h2_wrap_static">
    <h5><?php echo ((is_array($_tmp='Get Code')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h5><div class="line"></div>
    <div class="h2_content">
		<div id="integration_details_html" class="integration_details">
			<?php echo ((is_array($_tmp="Copy the HTML source code in the text box below and paste it anywhere into your web site.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

		</div>
		<div id="integration_details_link" class="adesk_hidden">
			<?php echo ((is_array($_tmp="You can direct users to this URL to subscribe and/or unsubscribe.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

		</div>
		<div id="integration_details_popup" class="adesk_hidden">
			<?php echo ((is_array($_tmp="Copy the HTML/JavaScript source code in the text box below and paste it anywhere into your web site.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

		</div>
		<div id="integration_details_xml" class="adesk_hidden">
			<?php echo ((is_array($_tmp="whatever is in flash docs.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

		</div>

		<textarea id="codeBox" readonly="readonly" rows="14" cols="80" style="width: 99%;" onfocus="adesk_form_highlight(this);"></textarea>
		<div style="float:right;">
			<a href="#" id="previewLink"><?php echo ((is_array($_tmp='Preview')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
		</div>
		<?php echo ((is_array($_tmp="Character Set:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

		 <input type="text" id="charsetField" value="" size="6" />
		<input type="button" value="<?php echo ((is_array($_tmp='Update')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="form_view_regenerate();" />
		<?php echo ((is_array($_tmp="Enter the character set that will be used on pages where you wish to put this form.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

	</div>
</div>

<div id="previewBox" class="adesk_hidden"></div>

<div id="formview_default" class="adesk_hidden">
	<h5>Default Redirection Options Set</h5><div class="line"></div>
	<p>Your default redirection options have been saved. Visitors who do not subscribe using a custom subscription form will be
	redirected based off of your saved settings.</p>
</div>

<br />
<div>
	<input type="button" class="adesk_button_back" value="<?php echo ((is_array($_tmp='Edit This Form')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="window.location='desk.php?action=form#form-' + form_view_id;" />
	<input type="button" class="adesk_button_back" value="<?php echo ((is_array($_tmp='Manage Forms')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="window.location='desk.php?action=form'" />
	<input type="button" class="adesk_button_back" value="<?php echo ((is_array($_tmp='Add a New Form')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="window.location='desk.php?action=form#form-0'" />
</div>

</div>