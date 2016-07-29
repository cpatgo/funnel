<?php /* Smarty version 2.6.12, created on 2016-07-08 16:53:15
         compiled from list.copy.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'list.copy.htm', 3, false),)), $this); ?>
<div id="copy" class="adesk_modal_delete" align="center" style="display: none">
  <div class="adesk_modal_inner">
    <h3 class="m-b"><?php echo ((is_array($_tmp='Copy List')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>
    <span id="copy_message"></span>
    <br />
	<br />
	<div id="copy_pref">
	  <input type="checkbox" id="copy_bounce" name="copy_bounce"                   value="1" checked="checked"> <?php echo ((is_array($_tmp='Bounce Settings')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br>
	  <input type="checkbox" id="copy_exclusion" name="copy_exclusion"             value="1" checked="checked"> <?php echo ((is_array($_tmp='Exclusions')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br>
	  <input type="checkbox" id="copy_filter" name="copy_filter"                   value="1" checked="checked"> <?php echo ((is_array($_tmp='Sending Filters')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br>
	  <input type="checkbox" id="copy_header" name="copy_header"                   value="1" checked="checked"> <?php echo ((is_array($_tmp='Custom Email Headers')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br>
	  <input type="checkbox" id="copy_personalization" name="copy_personalization" value="1" checked="checked"> <?php echo ((is_array($_tmp='Personalization')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br>
	  <input type="checkbox" id="copy_template" name="copy_template"               value="1" checked="checked"> <?php echo ((is_array($_tmp='Templates')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br>
	  <input type="checkbox" id="copy_field" name="copy_field"                     value="1" checked="checked"> <?php echo ((is_array($_tmp='Custom Subscriber Fields')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br>
	  <input type="checkbox" id="copy_form" name="copy_form"                       value="1" checked="checked"> <?php echo ((is_array($_tmp='Subscription Forms')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

	  <div style="margin-top:10px;">
	    <input type="checkbox" id="copy_subscriber" name="copy_subscriber"      value="1"> <?php echo ((is_array($_tmp='Subscribers')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br>
	  </div>
	</div>
    <br />
    <div>
      <input type="button" class="adesk_button_ok" value="<?php echo ((is_array($_tmp='OK')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="list_copy(list_copy_id)" />
      <input type="button" class="adesk_button_cancel" value="<?php echo ((is_array($_tmp='Cancel')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="$('copy').style.display = 'none'; adesk_ui_anchor_set(list_list_anchor())" />
    </div>
  </div>
</div>