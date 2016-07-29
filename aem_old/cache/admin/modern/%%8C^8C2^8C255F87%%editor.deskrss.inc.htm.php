<?php /* Smarty version 2.6.12, created on 2016-07-08 14:47:32
         compiled from editor.deskrss.inc.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'editor.deskrss.inc.htm', 3, false),)), $this); ?>
<div id="message_deskrss" class="adesk_modal" align="center" style="display:none;">
  <div class="adesk_modal_inner" align="left">
	<h3 class="m-b"><?php echo ((is_array($_tmp='Insert RSS Feed')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>

	<div class="adesk_help_inline"><?php echo ((is_array($_tmp="Dynamically include an RSS feed in your message.  Specify the RSS feed url, how many rows/entries to return, and whether or not to only fetch new rows/entries.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>

	<br />
	<div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  border="0" cellspacing="0" cellpadding="0" width="100%">
      <tr>
        <td><?php echo ((is_array($_tmp='Feed URL')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
        <td width="15">&nbsp;</td>
        <td></td>
      </tr>
      <tr>
        <td>
		  <input type="text" id="deskrssurl" value="http://" style="width:99%;" />
        </td>

      </tr>
      <tr>
        <td>
			<tr><td>&nbsp;</td></tr>

		  <div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  cellpadding="0" cellspacing="0" border="0">
            <tr>
              <td width="30"><input type="text" id="deskrssloop" value="10" size="3" style="width:20px;" onkeypress="form_editor_deskrss_loop_changed();" /></td>
              <td>
                <label for="deskrssall">
                  <?php echo ((is_array($_tmp="Max. number of items to include.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

                </label>
              </td>
            </tr>
			<tr><td>&nbsp;</td></tr>
			<tr><td colspan="2"><?php echo ((is_array($_tmp="Would you like the most recent items or only new items to be included?")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td></tr>
            <tr>
              <td width="30"><input name="deskrss" id="deskrssall" type="radio" value="all" checked="checked" /></td>
              <td>
                <label for="deskrssall">
                  <strong><?php echo ((is_array($_tmp='All')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</strong>
                  <?php echo ((is_array($_tmp="- Include all fetched items.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

                </label>
              </td>
            </tr>
            <tr>
              <td><input name="deskrss" id="deskrssnew" type="radio" value="new" /></td>
              <td>
                <label for="deskrssnew">
                  <strong><?php echo ((is_array($_tmp='Only New')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</strong>
                  <?php echo ((is_array($_tmp="- Only items that were not previously sent for this message.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

                </label>
              </td>
            </tr>
          </table></div>
        </td>
      </tr>
    </table></div>
	<br />

	<div id="deskrsspreviewbox" class="adesk_hidden">
	  <?php echo ((is_array($_tmp="Preview:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

	  <textarea id="deskrsspreview" style="width: 100%;" rows="10" readonly="readonly" disabled="disabled"></textarea>
	  <br />
	</div>

    <div style="margin-top:10px;">
      <input type="button" value='<?php echo ((is_array($_tmp='Insert')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onclick="form_editor_deskrss_insert();" class="adesk_button_ok" />
      <input type="button" value='<?php echo ((is_array($_tmp='Preview')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onclick="form_editor_deskrss_preview();" />
      <input type="button" value='<?php echo ((is_array($_tmp='Cancel')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onclick="adesk_dom_toggle_display('message_deskrss', 'block');" />
      <input type="hidden" value="text" id="deskrss4" />
      <input type="hidden" value="" id="deskrss2" />
    </div>
  </div>
</div>