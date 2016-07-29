<?php /* Smarty version 2.6.12, created on 2016-07-08 14:41:08
         compiled from processes.form.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'processes.form.htm', 8, false),)), $this); ?>
<div id="form" class="adesk_hidden">
  <form method="POST" onsubmit="processes_form_save(processes_form_id); return false">
    <input type="hidden" name="id" id="form_id" />

    <div>
      <h3 id="nameLabel"></h3>
      <p id="descriptLabel" class="adesk_hidden"></p>
      <div><?php echo ((is_array($_tmp="Created: ")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<span id="cdateLabel"></span></div>
      <div><?php echo ((is_array($_tmp="Updated: ")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<span id="ldateLabel"></span></div>
      <div><?php echo ((is_array($_tmp="Completed: ")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<span id="percentageLabel"></span></div>
      <div><?php echo ((is_array($_tmp="Status: ")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<strong id="statusLabel"></strong></div>
      <div><?php echo ((is_array($_tmp="Progress:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
      <div id="progressBar" class="adesk_progressbar"></div>
    </div>

    <div id="restartBox" class="adesk_hidden">
      <label>
        <input type="checkbox" name="restart" id="restartField" value="1" onclick="$('activeField').checked = this.checked;processes_form_active_changed();" />
        <?php echo ((is_array($_tmp="Re-Start")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

      </label>
    </div>
    <div id="activeBox" class="adesk_hidden">
      <label>
        <input type="checkbox" name="active" id="activeField" value="1" onclick="processes_form_active_changed();" />
        <?php echo ((is_array($_tmp='Active')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

      </label>
    </div>
    <div id="activeInBox" class="adesk_hidden">
      <div id="scheduleBox" class="adesk_hidden">
        <label>
          <input type="checkbox" name="schedule" id="scheduleField" value="1" onclick="processes_form_schedule_changed();" />
          <?php echo ((is_array($_tmp='Scheduled')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

        </label>
        <label id="dateBox" class="adesk_hidden">
          &middot;
          <?php echo ((is_array($_tmp="Starting Date/Time:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

          <input type="text" name="ldate" id="ldateField" value="" size="20" />
          <img src="images/date_time.png" id="ldateCalendar" />
        </label>
      </div>
      <div id="spawnBox" class="adesk_hidden">
        <label>
          <input type="checkbox" name="spawn" id="spawnField" value="1" onclick="processes_form_spawn_changed();" />
          <?php echo ((is_array($_tmp="Run Now (ReQueue)")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

        </label>
      </div>
    </div>

    <br />
    <div>
      <input type="button" id="form_submit" class="adesk_button_submit" value="<?php echo ((is_array($_tmp='Submit')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="processes_form_save(processes_form_id)" />
      <input type="button" id="form_back" class="adesk_button_back" value="<?php echo ((is_array($_tmp='Back')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="window.history.go(-1)" />
    </div>
    <input type="submit" style="display:none"/>
  </form>
</div>

<script type="text/javascript">
<?php echo '
Calendar.setup({inputField: "ldateField", ifFormat: \'%Y/%m/%d %H:%M\', button: "ldateCalendar", showsTime: true, timeFormat: "24"});
'; ?>

</script>