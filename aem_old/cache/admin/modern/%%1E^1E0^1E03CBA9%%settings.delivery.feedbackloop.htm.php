<?php /* Smarty version 2.6.12, created on 2016-07-08 14:11:51
         compiled from settings.delivery.feedbackloop.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'settings.delivery.feedbackloop.htm', 1, false),)), $this); ?>
        <div style="background:#F3F3F0; padding:5px; padding-left:10px;"><?php echo ((is_array($_tmp='Feedback Loop Processing')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
      </td>
      <td valign="top">
        <div id="mail_feedbackloop_help" style="padding:10px; border: 1px solid #E0DFDC; margin-bottom:20px;">

            <?php echo ((is_array($_tmp="A feedback loop is a service provided by ISPs where they will forward complaints made by email recipients to the original sender.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

            <?php echo ((is_array($_tmp="Joining feedback loops provides a way for you to clean your list and identify a problem that exists in your marketing strategy, e.g. subscribers do not know they subscribed to your list due to deceptive marketing material.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>


<?php if ($this->_tpl_vars['site']['brand_links']): ?>
<br /><br />
            <?php echo ((is_array($_tmp="To setup Feedback Loop Processing, please consult the instructions located at")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

            <a href="http://www.awebdesk.com/feedback-loops/">http://www.awebdesk.com/feedback-loops/</a>

<?php endif; ?>
<?php if ($this->_tpl_vars['fblcnt']): ?>
    <br /><br />
            <a href="#" onclick="$('settings_delivery_viewfbl').show();return false;"><?php echo ((is_array($_tmp='View Recent Feedback Loop Reports')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
<?php endif; ?>
        </div>