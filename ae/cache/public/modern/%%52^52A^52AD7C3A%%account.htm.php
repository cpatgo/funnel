<?php /* Smarty version 2.6.12, created on 2016-07-08 16:17:37
         compiled from account.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'plang', 'account.htm', 20, false),)), $this); ?>
<script type="text/javascript">
<!--
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "account.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
-->
</script>
        <div class="row" style="margin-top:20px;"><div class="col-lg-6">  
      
          
          <section class="panel">
            <header class="panel-heading">
              <ul class="nav nav-pills pull-right">
                <li>
                  <a href="#" class="panel-toggle text-muted"><i 

class="fa fa-caret-down fa-lg text-active"></i><i class="fa fa-

caret-up fa-lg text"></i></a>
                </li>
              </ul>
             <h4><?php echo ((is_array($_tmp='Update Account')) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)); ?>
</h4>
            </header>
            <section   class="panel-body m-b">

 

<?php if ($this->_tpl_vars['account_message']): ?>
	<?php echo $this->_tpl_vars['account_message']; ?>

<?php else: ?>

	
		<form method="post" action="<?php echo $this->_tpl_vars['_']; ?>
/surround.php" onsubmit="return account_validate();">

			<input type="hidden" name="funcml" value="account" />

			<p>
				<label>
					<?php echo ((is_array($_tmp="Your e-mail address")) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)); ?>

					<input type="text" name="email" id="emailField" size="30" />
				</label>
			</p>

			<?php if ($this->_tpl_vars['site']['gd']): ?>
			<p>
				<?php echo ((is_array($_tmp="Please also verify yourself by typing the text in the following image into the box next to it.")) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)); ?>

				<br/>
				<img src="<?php echo $this->_tpl_vars['_']; ?>
/awebdesk/scripts/imgrand.php?rand=<?php echo $this->_tpl_vars['rand']; ?>
" align="absmiddle" />
				<input type="text" name="imgverify" id="imgverify" />
			</p>
			<?php endif; ?>

			<p>
				<input type="submit" value="<?php echo ((is_array($_tmp='Modify Account')) ? $this->_run_mod_handler('plang', true, $_tmp) : smarty_modifier_plang($_tmp)); ?>
" class="btn btn-info" />
			</p>

		</form>
</section></section></div></div>
	
<?php endif; ?>