<?php /* Smarty version 2.6.12, created on 2016-07-18 12:02:28
         compiled from bounce_management.log.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'bounce_management.log.htm', 4, false),)), $this); ?>
<div id="log" class="adesk_modal" align="center" style="display: none">
  <div class="adesk_modal_inner">
    <div>
      <input type="button" class="adesk_button_close" value="<?php echo ((is_array($_tmp='Close')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="adesk_dom_toggle_display('log', 'block'); adesk_ui_anchor_set(bounce_management_list_anchor())" />
    </div>
    <div id="log_result_box" class="adesk_hidden">
      <div>
        <?php echo ((is_array($_tmp="Date:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

        <span id="log_date"></span>
      </div>
      <div>
        <?php echo ((is_array($_tmp="E-mail:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

        <span id="log_email"></span>
      </div>
      <div id="log_campaign_box">
        <?php echo ((is_array($_tmp="Campaign:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

        <span id="log_campaign"></span>
      </div>
      <div>
        <?php echo ((is_array($_tmp="Result:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

        <span id="log_result"></span>
      </div>
      <div>
        <?php echo ((is_array($_tmp="Email source:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br />
        <textarea id="log_source" style="width: 99%; height: 200px;"></textarea>
      </div>
      <div style="text-align: right;">
        <a href="#" onclick="return bounce_management_log_hide();"><?php echo ((is_array($_tmp='Back...')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
      </div>
    </div>
    <div id="log_list_box" class="adesk_block">
      <p>
        <?php echo ((is_array($_tmp="This page will show you the last 50 emails that arrived and got parsed by the system.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

        <?php echo ((is_array($_tmp="By looking at this information you can see if your Bounce Management is setup properly.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

        <?php echo ((is_array($_tmp="Every time the email arrives, whether a legal bounce email or not, is stored and listed here.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

      </p>
      <div><?php echo ((is_array($_tmp="Log Entries:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 <span id="log_count"></span></div>
      <br />
      <ul id="log_list" class="adesk_hidden"></ul>
      <div id="log_empty" class="adesk_block"><?php echo ((is_array($_tmp="This Bounce Settings has never processed bounces.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
    </div>
    <br />
    <p>
      <em><?php echo ((is_array($_tmp="Please note that not more than one bounce per subscriber email will be logged in one day.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</em><br />
      <?php echo ((is_array($_tmp="This restricts the system from accidentally removing the subscriber if his server/network is offline for an extended period of time.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

    </p>
    <div>
      <input type="button" class="adesk_button_close" value="<?php echo ((is_array($_tmp='Close')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="adesk_dom_toggle_display('log', 'block'); adesk_ui_anchor_set(bounce_management_list_anchor())" />
    </div>
  </div>
</div>