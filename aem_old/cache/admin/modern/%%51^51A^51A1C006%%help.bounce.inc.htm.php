<?php /* Smarty version 2.6.12, created on 2016-07-18 12:02:28
         compiled from help.bounce.inc.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'help.bounce.inc.htm', 3, false),)), $this); ?>
    <div style="padding-top: 10px; margin-top: 10px; border-top: 1px solid #ccc;">
      <div class="h2_wrap">
        <h2 onclick="adesk_dom_toggle_class('popfaqpanel', 'h2_content_invis', 'h2_content');"><?php echo ((is_array($_tmp='POP Frequently Asked Questions')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h5><div class="line"></div>
        <div id="popfaqpanel" class="h2_content_invis">
          <div class="question"><?php echo ((is_array($_tmp="Do the bounced e-mails stay on my POP server even after I check for Bounced mail via the control panel?")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
          <div class="answer"><?php echo ((is_array($_tmp="No. If the bounced e-mail message is detected, the system will flag that e-mail address and delete that message.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>

          <div class="question"><?php echo ((is_array($_tmp="Does this process affect any other mail in my pop account?")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
          <div class="answer"><?php echo ((is_array($_tmp="Yes. It will delete all emails in your pop account.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>

          <div class="question"><?php echo ((is_array($_tmp="What does flagging mean or what does it mean when you say the system will flag an e-mail address?")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
          <div class="answer">
            <?php echo ((is_array($_tmp="An e-mail address is flagged when it is found to have bounced.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

            <?php echo ((is_array($_tmp="Every time that e-mail address bounces, the system keeps track of the flags.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

            <?php echo ((is_array($_tmp="Upon being flagged 3 times, the e-mail address is removed from the system.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

            <?php echo ((is_array($_tmp="The amount of flags before removal may be modified.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

          </div>

          <div class="question"><?php echo ((is_array($_tmp="It does not appear to be working, what should I do?")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
          <div class="answer"><?php echo ((is_array($_tmp="Ensure that your POP3 information is entered correctly.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
        </div>
      </div>
    </div>