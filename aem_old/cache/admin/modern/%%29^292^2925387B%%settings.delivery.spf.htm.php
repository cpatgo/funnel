<?php /* Smarty version 2.6.12, created on 2016-07-08 14:11:51
         compiled from settings.delivery.spf.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'settings.delivery.spf.htm', 1, false),)), $this); ?>
        <div style="background:#F3F3F0; padding:5px; padding-left:10px;"><?php echo ((is_array($_tmp='SPF Records')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>




        <div id="mail_fbl_help" style="padding:10px; border: 1px solid #E0DFDC; margin-bottom:10px;">

		<div class="adesk_help_inline"><?php echo ((is_array($_tmp="SPF records need to be setup on a server level, and not within this application. The administrator of your DNS server needs to make adjustments to your DNS zone.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
<?php if ($this->_tpl_vars['__ishosted']): ?>
          <p>
            <?php echo ((is_array($_tmp="To improve delivery, here is your SPF information to add to your DNS settings:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

          </p>

          <blockquote>
            <p>
            <?php echo ((is_array($_tmp="SPF record:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br />
            <textarea readonly="readonly" style="background:#F8F8D3; width:90%; height:40px; font-size:11px; font-family:Arial, Helvetica, sans-serif;">* IN TXT v=spf1 a mx include:acemserv.com include:s100.acemserv.com include:senderservacspf.smtp.com&nbsp;~all</textarea></p>
          </blockquote>

          <p>
            <?php echo ((is_array($_tmp="Here's a tool you can use to test the records:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 <a href="http://network-tools.com/nslook/" target="_blank">http://network-tools.com/nslook/</a>
          </p>

          <blockquote>
            <p>
              <?php echo ((is_array($_tmp="If you have any questions OR need assistance please contact whoever manages your domain name.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

            </p>
          </blockquote>


<?php else: ?>
<!--<?php if ($this->_tpl_vars['site']['brand_links']): ?>
          <p>
            <?php echo ((is_array($_tmp="To setup SPF Records, please consult the instructions located at")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

            <a href="http://www.awebdesk.com/articles/spf/">http://www.awebdesk.com/articles/spf/</a>
          </p>
<?php endif; ?> -->
          </p>
            <?php echo ((is_array($_tmp="The DNS syntax for an SPF record may look like this:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

          </p>

          <blockquote>
            </p>
              <strong>YOURMAILSERVER.COM. IN TXT "v=spf1 a mx –all"</strong>
            </p>
          </blockquote>

          <p>
            <?php echo ((is_array($_tmp="Some set up wizards for setting up valid SPF DNS entries can be found here:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

          </p>

          <ul>
            <li><a href="http://old.openspf.org/wizard.html" target="_blank"><?php echo ((is_array($_tmp='Record Setup Wizard')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></li>
            <li><a href="http://www.microsoft.com/mscorp/safety/content/technologies/senderid/wizard/" target="_blank"><?php echo ((is_array($_tmp='Sender ID Framework SPF Record Wizard')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></li>
          </ul>


            <?php echo ((is_array($_tmp="We strongly suggest to use the above wizards and follow their instructions for completing your SPF setup.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

<?php endif; ?>
        </div>
