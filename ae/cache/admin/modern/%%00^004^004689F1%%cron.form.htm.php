<?php /* Smarty version 2.6.12, created on 2016-07-08 14:41:25
         compiled from cron.form.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'cron.form.htm', 6, false),array('modifier', 'help', 'cron.form.htm', 15, false),array('function', 'html_options', 'cron.form.htm', 80, false),)), $this); ?>
<div id="form" class="adesk_hidden">
  <form method="POST" onsubmit="cron_form_save(cron_form_id); return false">
    <input type="hidden" name="id" id="form_id" />

    <div class="h2_wrap_static">
      <h4><?php echo ((is_array($_tmp='Cron Job Information')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h4>
      <div class="h2_content">

        <table border="0" cellspacing="0" cellpadding="5">
          <tr>
            <td><label for="stringidField"><?php echo ((is_array($_tmp='Identifier')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label></td>
            <td>
              <span id="stringidLabel"></span>
              <input type="text" name="stringid" id="stringidField" value="" size="45" />
              <?php echo ((is_array($_tmp="This is the internal name for you to use. Every cron job needs to have a unique identifier.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

            </td>
          </tr>
          <tr>
            <td><label for="nameField"><?php echo ((is_array($_tmp='Name')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label></td>
            <td>
              <span id="nameLabel"></span>
              <input type="text" name="name" id="nameField" value="" size="45" />
            </td>
          </tr>
          <tr valign="top">
            <td><label for="descriptField"><?php echo ((is_array($_tmp='Description')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label></td>
            <td>
              <span id="descriptLabel"></span>
              <textarea name="descript" id="descriptField" style="width:415px; height:65px;"></textarea>
            </td>
          </tr>
          <tbody id="commandRow" class="adesk_hidden" style="display:none;">
          <tr valign="top">
              <td><?php echo ((is_array($_tmp='Cron Line')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</td>
              <td>
                <span id="commandLabel"></span>
              </td>
            </tr>
          </tbody>
        </table>

      </div>
    </div>

    <br />

    <div class="h2_wrap_static">
      <h4><?php echo ((is_array($_tmp='Cron Job Settings')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h4>
      <div class="h2_content">

        <table border="0" cellspacing="0" cellpadding="5">
          <tr>
            <td width="100"><label for="activeField"><?php echo ((is_array($_tmp='Active')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label></td>
            <td>
              <input type="checkbox" name="active" id="activeField" value="1" />
              <?php echo ((is_array($_tmp='This will turn this Cron Job on or off')) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

            </td>
          </tr>
          <tr>
            <td><label for="loglevelField"><?php echo ((is_array($_tmp='Keep Logs')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label></td>
            <td>
              <input type="checkbox" name="loglevel" id="loglevelField" value="1" />
              <?php echo ((is_array($_tmp="Logs will be kept for 30 days.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

            </td>
          </tr>
          <tr>
            <td><label for="filenameField"><?php echo ((is_array($_tmp="File Name / URL")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</label></td>
            <td>
              <input type="text" name="filename" id="filenameField" value="" size="45" />
              <?php echo ((is_array($_tmp="If the URL is used, it has to start with http:// or https:// . Otherwise, a file path will be assumed. If a path starts with './', the application folder will be assumed.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

            </td>
          </tr>

          <tr>
            <td valign="middle">
              <?php echo ((is_array($_tmp='Day of the Week')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

            </td>
            <td>
              <select name="weekday" id="weekdayField" size="1" onchange="$('dayofmonthRow').className = ( this.value == '-1' ? 'adesk_table_rowgroup' : 'adesk_hidden' );">
                <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['weekdays']), $this);?>

              </select>
              <?php echo ((is_array($_tmp="Note: this overrides the 'day of the month' option")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

            </td>
          </tr>
          <tbody id="dayofmonthRow">
            <tr>
              <td valign="middle">
                <?php echo ((is_array($_tmp='Day of the Month')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

              </td>
              <td>
                <select name="day" id="dayField" size="1">
                  <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['monthdays']), $this);?>

                </select>
              </td>
            </tr>
          </tbody>
          <tr>
            <td valign="middle">
              <?php echo ((is_array($_tmp='Hour')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

            </td>
            <td>
              <select name="houroperator" id="houroperatorField" size="1" onchange="cron_form_hours_switch(this.value, $('hourField').value);">
                <option value="at"><?php echo ((is_array($_tmp='On the Hour')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
                <option value="every"><?php echo ((is_array($_tmp='Every')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
              </select>
              <select name="hour" id="hourField" size="1">
                <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['hours']), $this);?>

              </select>
              <span id="otherHoursOperator" class="adesk_hidden"><?php echo ((is_array($_tmp='hours')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span>
              <?php echo ((is_array($_tmp="Set * for 'every hour'.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

            </td>
          </tr>
          <tr>
            <td valign="middle">
              <?php echo ((is_array($_tmp='Minute')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

            </td>
            <td>
              <select name="minuteoperator" id="minuteoperatorField" size="1" onchange="cron_form_minutes_switch(this.value, $('minute1Field').value);">
                <option value="at"><?php echo ((is_array($_tmp='At Minute')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
                <option value="every"><?php echo ((is_array($_tmp='Every')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
              </select>
              <select name="minute1" id="minute1Field" size="1" onchange="cron_form_minutes_switch($('minuteoperatorField').value, this.value);">
                <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['minutes1']), $this);?>

              </select>
              <span id="otherMinutes" class="adesk_hidden">
                <select name="minute2" id="minute2Field" size="1">
                  <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['minutes2']), $this);?>

                </select>
                <select name="minute3" id="minute3Field" size="1">
                  <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['minutes2']), $this);?>

                </select>
                <select name="minute4" id="minute4Field" size="1">
                  <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['minutes2']), $this);?>

                </select>
                <select name="minute5" id="minute5Field" size="1">
                  <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['minutes2']), $this);?>

                </select>
                <select name="minute6" id="minute6Field" size="1">
                  <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['minutes2']), $this);?>

                </select>
              </span>
              <span id="otherMinutesOperator" class="adesk_hidden"><?php echo ((is_array($_tmp='minutes')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span>
              <?php echo ((is_array($_tmp="Set * for 'every minute'.")) ? $this->_run_mod_handler('help', true, $_tmp) : smarty_modifier_help($_tmp)); ?>

            </td>
          </tr>
        </table>

      </div>
    </div>

    <br />
    <div>
      <input type="button" id="form_submit" class="adesk_button_submit" value="<?php echo ((is_array($_tmp='Submit')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="cron_form_save(cron_form_id)" />
      <input type="button" id="form_back" class="adesk_button_back" value="<?php echo ((is_array($_tmp='Back')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="window.history.go(-1)" />
    </div>
    <input type="submit" style="display:none"/>
  </form>
</div>