<?php /* Smarty version 2.6.12, created on 2016-07-08 14:41:25
         compiled from cron.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'adesk_js', 'cron.htm', 1, false),array('modifier', 'alang', 'cron.htm', 6, false),)), $this); ?>
<?php echo smarty_function_adesk_js(array('lib' => "really/simplehistory.js",'base' => $this->_tpl_vars['site']['p_link']), $this);?>

<script type="text/javascript">
  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "cron.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</script>

<h3 class="m-b"><?php echo ((is_array($_tmp='Scheduled Tasks')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>


<div class="adesk_help_inline" style="color:#333333; font-size:11px;">
<?php if (! $this->_tpl_vars['isWindows']): ?>
      <div class="question"><?php echo ((is_array($_tmp="I have a UNIX-based server.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 <?php echo ((is_array($_tmp="How do I setup cron jobs?")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
      <div class="answer">
        <p>
          <?php echo ((is_array($_tmp="Typically you will be able to setup your cron with the following format:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

        </p>
        <div class="code">
          */5 * * * * php <?php echo $this->_tpl_vars['cronBasePath']; ?>
/manage/cron.php
        </div>

        <p>
          <?php echo ((is_array($_tmp="If your PHP executable is installed in a custom folder, you may need to specify the PHP folder (such as /usr/local/bin/php) with your custom PHP path in a following command:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

        </p>
        <div class="code">
          */5 * * * * <u>/usr/local/bin/php</u> <?php echo $this->_tpl_vars['cronBasePath']; ?>
/manage/cron.php
        </div>

        <p>
          <?php echo ((is_array($_tmp="If you are having trouble setting up the cron (or do not know the location of PHP on the server) you can use the following format:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

        </p>
        <div class="code">
          */5 * * * * wget <?php echo $this->_tpl_vars['plink']; ?>
/manage/cron.php > /dev/null
        </div>
      </div>

<?php else: ?>
      <div class="question"><?php echo ((is_array($_tmp="I have a Windows-based server.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 <?php echo ((is_array($_tmp="How do I setup cron jobs?")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
      <div class="answer">
        <p>
          <?php echo ((is_array($_tmp="Since Window-based servers do not have cron jobs, you can achieve the same effect by using Scheduled Tasks application (under All Programs->Accessories->System Tools->Scheduled Tasks).")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

          <?php echo ((is_array($_tmp="Simply place a following command that should call that script (Every 5 Minutes) from a web server into a field named 'Run':")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

        </p>

        <p>
          <?php echo ((is_array($_tmp="If your PHP executable is installed in the default folder (C:\PHP) which is specified in the first line of cron.php, you will be able to setup a following command:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

        </p>
        <div class="code">
          C:\PHP\php.exe <?php echo $this->_tpl_vars['cronBasePath']; ?>
\admin\cron.php
        </div>

        <p>
          <?php echo ((is_array($_tmp="If your PHP executable is installed in a custom folder, then replace the default folder (C:\PHP) with your custom PHP path in a following command:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

        </p>
        <div class="code">
          <u>C:\PHP\php.exe</u> <?php echo $this->_tpl_vars['cronBasePath']; ?>
\admin\cron.php
        </div>
      </div>
<?php endif; ?>
</div>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "cron.list.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "cron.form.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "cron.delete.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "cron.search.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "cron.log.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<script type="text/javascript">
  adesk_ui_rsh_init(cron_process, true);
</script>