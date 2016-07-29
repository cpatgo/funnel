<?php /* Smarty version 2.6.12, created on 2016-07-08 14:18:25
         compiled from sync.form.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'sync.form.htm', 7, false),array('modifier', 'default', 'sync.form.htm', 71, false),array('modifier', 'help', 'sync.form.htm', 193, false),array('function', 'html_options', 'sync.form.htm', 34, false),)), $this); ?>
<div id="syncFormPanel" class="adesk_hidden">

<form action="desk.php?action=sync" method="post" name="addSyncForm" id="addSyncForm">
<input type="hidden" name="id" id="syncFormIDfield" value="<?php echo $this->_tpl_vars['data']['id']; ?>
" />

<div id="syncDBHolder" class="h2_wrap">
<h4><?php echo ((is_array($_tmp="Step 1: Database Info")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h4>
  <div id="syncDBBox" class="h2_content">


    <table border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td colspan="3">
            <b><?php echo ((is_array($_tmp='Name your synchronization')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</b><br />
            <input type="text" style="width:99%;" id="titleField" name="sync_name" value="<?php echo $this->_tpl_vars['data']['sync_name']; ?>
" onchange="somethingChanged = true;" />
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <br /><hr noshade size="1" width="100%" />
                <div><b><?php echo ((is_array($_tmp='External Database')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</b> (<?php echo ((is_array($_tmp='Database you wish to synchronize with')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
)</div>
				<?php if (isset ( $this->_tpl_vars['__ishosted'] ) && $this->_tpl_vars['__ishosted']): ?>
				<div style="margin-top: 10px; margin-bottom: 10px; padding: 10px; background: #FEFFBE">
				  <?php echo ((is_array($_tmp="Your database user must be allowed to connect from host: ")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

				  <?php echo $this->_tpl_vars['__hostedip']; ?>

				</div>
				<?php endif; ?>
            </td>
        </tr>
        <tr>
            <td colspan="3">
            <br /><b><?php echo ((is_array($_tmp='Database')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</b><br />
            <select name="db_type" id="dbtypeField" onchange="somethingChanged = true;" style="width:99%;">
              <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['types']), $this);?>

            </select>
            </td>
        </tr>
        <tr>
            <td>
            <?php echo ((is_array($_tmp='Database Name')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br />
            <input type="text" id="dbnameField" name="db_name" style="width:99%;" value="<?php echo $this->_tpl_vars['data']['db_name']; ?>
" onchange="somethingChanged = true;" />
            </td>
            <td width="4%"></td>
            <td>
            <?php echo ((is_array($_tmp='Database Host')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br />
            <input type="text" id="dbhostField" name="db_host" style="width:99%;" value="<?php echo $this->_tpl_vars['data']['db_host']; ?>
" onchange="somethingChanged = true;" />
            </td>
        </tr>
        <tr>
            <td colspan="3">
            &nbsp;
            </td>
        </tr>
        <tr>
            <td width="46%">
            <?php echo ((is_array($_tmp='Database Username')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br />
            <input type="text" id="dbuserField" name="db_user" style="width:99%;" value="<?php echo $this->_tpl_vars['data']['db_user']; ?>
" onchange="somethingChanged = true;" />
            </td>
            <td width="4%"></td>
            <td width="46%">
            <?php echo ((is_array($_tmp='Database Password')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br />
            <input type="password" id="dbpassField" name="db_pass" style="width:99%;" value="<?php echo $this->_tpl_vars['data']['db_pass']; ?>
" onchange="somethingChanged = true;" />
            </td>
        </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
		<tr>
		  <td width="46%">
			<?php echo ((is_array($_tmp='Source Character Set')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<br/>
			<input type="text" id="sourcecharsetField" name="sourcecharset" style="width: 99%" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['data']['sourcecharset'])) ? $this->_run_mod_handler('default', true, $_tmp, 'utf-8') : smarty_modifier_default($_tmp, 'utf-8')); ?>
" onchange="somethingChanged = true" />
		  </td>
		</tr>
        <tr>
            <td colspan="3">
                <br /><hr noshade size="1" width="100%" />
            </td>
        </tr>
        <tr>
            <td colspan="3">
            <br /><b><?php echo ((is_array($_tmp='Sync Into')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</b><br />
            <select name="relid" id="relidField" onchange="somethingChanged = true;sync_relid_change(adesk_form_select_extract(this));" style="width:99%;">
              <option value="0"><?php echo ((is_array($_tmp='Select One')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
              <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['rels']), $this);?>

            </select>
            </td>
        </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="3">
	            <?php if (isset ( $this->_tpl_vars['sync_destinations_template'] )): ?>
	            	<?php if ($this->_tpl_vars['sync_destinations_template'] != ''): ?>
		            	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['sync_destinations_template'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	               	<?php endif; ?>
	            <?php endif; ?>
            </td>
        </tr>

    </table>
    <br />

  </div>
</div>

<div id="syncTablesHolder" class="adesk_hidden">
  <h4 onclick="adesk_dom_toggle_class('syncTablesBox', 'h2_content', 'h2_content_invis');"><?php echo ((is_array($_tmp="Step 2: Select Table")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h4>
  <div id="syncTablesBox" class="h2_content">

    <div>
      <div style="float: left; width: 45%;">
        <div><?php echo ((is_array($_tmp="Tables found in this database:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
        <div id="syncTables" class="adesk_radio_list">
        </div>
      </div>
      <div style="float: right; width: 45%;">
        <div><?php echo ((is_array($_tmp="Or enter a query you would like to use:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
        <div id="syncCustom" class="adesk_radio_list">
          <label><input id="syncCustomQueryRadio" type="radio" name="db_table" value="" onchange="$('syncQuery').className = ( this.checked ? 'adesk_block' : 'adesk_hidden' );" /> <?php echo ((is_array($_tmp='My Custom Query')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label><br />
	      <div id="syncQuery" class="adesk_hidden">
	        <textarea id="queryField" name="db_query" onchange="somethingChanged = true;" rows="30" style="width: 99%;"><?php if ($this->_tpl_vars['data']['db_table'] == ''):  echo $this->_tpl_vars['data']['rules'];  endif; ?></textarea>
	      </div>
        </div>
      </div>
      <div style="clear: both;"></div>
    </div>

  </div>
</div>


<div id="syncFieldsHolder" class="adesk_hidden">
  <h4><?php echo ((is_array($_tmp='Select Fields to Map')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h4>
  <div id="syncFieldsBox" class="h2_content">

    <?php echo ((is_array($_tmp="Select which fields you wish to synchronize to their destinations.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>


    <table cellpadding="10" width="100%">
      <tr bgcolor="#666666" style="color:white; font-weight:bold;">
        <td width="250"><?php echo ((is_array($_tmp='Your External Database')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
        <td><?php echo ((is_array($_tmp='The Field To Sync In')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
      </tr>
      <tbody id="mappingTable"></tbody>
    </table>

  </div>
</div>


<div id="syncRulesHolder" class="adesk_hidden">
  <h4><?php echo ((is_array($_tmp='Select Query Rules')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h4>
  <div id="syncRuleBox" class="h2_content">

    <div id="queryResults" class="adesk_hidden">
      <h3><?php echo ((is_array($_tmp='Query Info')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>
      <div><?php echo ((is_array($_tmp="Here is the sample query you entered:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
      <div id="queryPreview"></div>
    </div>

    <div id="tableRules" class="adesk_hidden">

      <h3><?php echo ((is_array($_tmp='Current Rules')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>
      <div id="rulesList"></div>
      <div id="noRules" class="adesk_block"><?php echo ((is_array($_tmp="No rules setup.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
      <div id="removeRules" class="adesk_hidden"><a href="#" onclick="return sync_rules_remove();"><?php echo ((is_array($_tmp='Remove All Rules')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></div>

      <h3><?php echo ((is_array($_tmp='Add Rule')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>
      <div id="rulesForm">
       <?php echo ((is_array($_tmp='Where')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

       <select id="rule_field"></select>
       <select id="rule_cond">
         <option value="="><?php echo ((is_array($_tmp="Equals (Is)")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
         <option value="!="><?php echo ((is_array($_tmp="Does Not Equal (Is Not)")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
         <option value="&gt;="><?php echo ((is_array($_tmp='Is Greater Than Or Equal To')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
         <option value="&lt;="><?php echo ((is_array($_tmp='Is Less Than Or Equal To')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
         <option value="&gt;"><?php echo ((is_array($_tmp='Is Greater Than')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
         <option value="&lt;"><?php echo ((is_array($_tmp='Is Less Than')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
         <option value="CSIS"><?php echo ((is_array($_tmp="Case-Sensitive Equals (Is)")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
         <option value="CONTAINS"><?php echo ((is_array($_tmp='Contains')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
         <option value="DCONTAINS"><?php echo ((is_array($_tmp='Does NOT Contain')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
         <option value="CSCONTAINS"><?php echo ((is_array($_tmp="Case-Sensitive Contains")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
         <option value="CSDCONTAINS"><?php echo ((is_array($_tmp="Case-Sensitive Does NOT Contain")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
         <option value="INLIST"><?php echo ((is_array($_tmp="Is in (comma separated) list")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
         <option value="NOTINLIST"><?php echo ((is_array($_tmp="Is NOT in (comma separated) list")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
       </select>
       <input type="text" id="rule_value" />
       <script>$('rule_value').onkeypress = adesk_ui_stopkey_enter;</script>
       <input type="button" value="<?php echo ((is_array($_tmp='Add')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="sync_rules_add();" />
       <?php echo ((is_array($_tmp="You are responsible for escaping! Also, if constructing a LIST, then strings should be encapsulated with quotes.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

      </div>

    </div>

    <div id="syncOptions">
      <h3><?php echo ((is_array($_tmp='Sync Options')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>
      <div id="syncOption_delete_all" class="adesk_block">
        <label>
          <input type="checkbox" name="sync_option_delete_all" id="import_option_field_delete_all" />
          <?php echo ((is_array($_tmp="Delete all items that are not affected by the sync each time the sync runs.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

          <?php echo ((is_array($_tmp="Extra users that were not part of the sync will be removed.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

        </label>
      </div>
<?php $_from = $this->_tpl_vars['opts']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['o']):
?>
      <div id="syncOption_<?php echo $this->_tpl_vars['o']['id']; ?>
" class="<?php if (isset ( $this->_tpl_vars['o']['hidden'] ) && $this->_tpl_vars['o']['hidden']): ?>adesk_hidden<?php else: ?>adesk_block<?php endif; ?>">
        <label>
          <input type="checkbox" name="sync_option_<?php echo $this->_tpl_vars['o']['id']; ?>
" id="import_option_field_<?php echo $this->_tpl_vars['o']['id']; ?>
" value="1" <?php if (isset ( $this->_tpl_vars['o']['checked'] ) && $this->_tpl_vars['o']['checked']): ?>checked="checked"<?php endif; ?> <?php if (isset ( $this->_tpl_vars['o']['disabled'] ) && $this->_tpl_vars['o']['disabled']): ?>disabled="disabled"<?php endif; ?> />
          <?php echo $this->_tpl_vars['o']['name']; ?>

        </label>
<?php if ($this->_tpl_vars['o']['descript'] != ''): ?>
        <?php echo ((is_array($_tmp=$this->_tpl_vars['o']['descript'])) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

<?php endif; ?>
      </div>
<?php endforeach; endif; unset($_from); ?>
    </div>
  </div>
</div>



<div id="stepsList" style="float: right;">
	<?php echo ((is_array($_tmp="Step:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

	<span id="stepDB" class="currentstep">1</span>
	<span id="stepTables" class="otherstep">2</span>
	<span id="stepFields" class="otherstep">3</span>
	<span id="stepRules" class="otherstep">4</span>
</div>

<div class="bottom_nav_options">
	<input name="mode" type="hidden" id="modeField" value="<?php if ($this->_tpl_vars['data']['id'] == 0): ?>add<?php else: ?>edit<?php endif; ?>" />
<?php if (! $this->_tpl_vars['demoMode']): ?>
	<input id="syncWizardDone" class="adesk_hidden" type="button" value="<?php echo ((is_array($_tmp='Save')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="sync_save();" />
	<input id="syncWizardTest" class="adesk_hidden" type="button" value="<?php echo ((is_array($_tmp='Test')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="sync_run(0, true);" />
	<input id="syncWizardRun" class="adesk_hidden" type="button" value="<?php echo ((is_array($_tmp='Run')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="sync_run(0, false);" />
    <input id="syncWizardNext" class="adesk_button_next" type="button" value="<?php echo ((is_array($_tmp="&raquo; Next Step")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="sync_next();" />
<?php else: ?>
	<span class="demoDisabled2"><?php echo ((is_array($_tmp='Disabled in demo')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span>
<?php endif; ?>
	<input type="button" value="<?php echo ((is_array($_tmp='Back')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="window.history.go(-1);" />
</div>

</form>


</div>