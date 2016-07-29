<?php /* Smarty version 2.6.12, created on 2016-07-08 14:20:31
         compiled from subscriber_view.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'adesk_js', 'subscriber_view.htm', 1, false),array('function', 'adesk_calendar', 'subscriber_view.htm', 6, false),array('function', 'jsvar', 'subscriber_view.htm', 25, false),array('function', 'adesk_headercol', 'subscriber_view.htm', 166, false),array('modifier', 'escape', 'subscriber_view.htm', 14, false),array('modifier', 'alang', 'subscriber_view.htm', 15, false),array('modifier', 'acpdate', 'subscriber_view.htm', 50, false),array('modifier', 'default', 'subscriber_view.htm', 106, false),array('modifier', 'truncate', 'subscriber_view.htm', 156, false),)), $this); ?>
<?php echo smarty_function_adesk_js(array('lib' => "really/simplehistory.js"), $this);?>

<script type="text/javascript">
  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "subscriber_view.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</script>
<?php echo smarty_function_adesk_calendar(array('base' => ".."), $this);?>


<?php $this->assign('name', $this->_tpl_vars['subscriber']['first_name']); ?>
<?php if ($this->_tpl_vars['name'] == ""): ?>
<?php $this->assign('name', $this->_tpl_vars['subscriber']['default_name']); ?>
<?php endif; ?><div class="panel bg bg-primary" style="padding:5px; margin-top:5px">
               <h3 class="m-b">
	<span id="subscriber_email_label">
		<span id="subscriber_email_title"><?php echo ((is_array($_tmp=$this->_tpl_vars['subscriber']['email'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</span>
		<span id="subscriber_email_editlink"><a class="btn btn-white btn-xs" href="#" onClick="$('subscriber_email_label').hide();$('subscriber_email_form').show();$('subscriber_email_field').value=subscriber_view_email;return false;"><?php echo ((is_array($_tmp='Edit')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></span>
	</span>
	<span id="subscriber_email_form" style="display:none;">
		<input type="text" name="email" id="subscriber_email_field" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['subscriber']['email'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" onKeyPress="adesk_dom_keypress_doif(event, 13, subscriber_email_update);" />
		<input type="button" value="<?php echo ((is_array($_tmp='Save')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onClick="subscriber_email_update();" />
		<input type="button" value="<?php echo ((is_array($_tmp='Cancel')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onClick="$('subscriber_email_form').hide();$('subscriber_email_label').show();" />
	</span>
</h3>
<?php if ($this->_tpl_vars['formSubmitted']): ?>
<script>
<?php echo smarty_function_jsvar(array('var' => $this->_tpl_vars['submitResult']['message'],'name' => 'r'), $this);?>

<?php if ($this->_tpl_vars['submitResult']['succeeded']): ?>
adesk_result_show(r);
<?php else: ?>
adesk_error_show(r);
<?php endif; ?>
</script>
<?php endif; ?>

                </div>

<section class="content-sidebar bg-white" id="content" style="margin-left:-20px;">

    <!-- .sidebar -->
    <aside class="sidebar bg-lighter sidebar">
      <div class="text-center clearfix bg-white">
       <img src="http://www.gravatar.com/avatar/<?php echo $this->_tpl_vars['subscriber']['md5email']; ?>
?d=<?php echo $this->_tpl_vars['subscriber']['default_gravatar']; ?>
&s=128" width="128" style="border:3px solid #EDECE7;">
      </div>
      <div class="bg-white padder padder-v">
         <span class="h4"><?php echo $this->_tpl_vars['subscriber']['first_name']; ?>
 <?php echo $this->_tpl_vars['subscriber']['last_name']; ?>
</span>
         <?php if (count ( $this->_tpl_vars['actions'] ) > 0): ?>
  <div>
	<div style="margin-bottom:10px;"><?php echo ((is_array($_tmp='Recent Actions')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>

	  <?php $_from = $this->_tpl_vars['actions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['e']):
?>
	  <div style="margin-bottom:4px;">(<?php echo ((is_array($_tmp=$this->_tpl_vars['k'])) ? $this->_run_mod_handler('acpdate', true, $_tmp) : smarty_modifier_acpdate($_tmp)); ?>
) <?php echo $this->_tpl_vars['e']; ?>
</div>
	  <?php endforeach; endif; unset($_from); ?>

  </div>
  <?php endif; ?>
   <div>
	<small class="pull-right text-muted"> <?php echo ((is_array($_tmp='Subscribed on ')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 <span id="subscribedate"></span><br>
	 <?php echo ((is_array($_tmp='from IP')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 <span id="subscriberip"></span><br></small>
  </div>
     <div>
     <h4><?php echo ((is_array($_tmp="Geo Data(Auto fill)")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h4><small class="pull-right text-muted"><?php echo ((is_array($_tmp="This info prefills and is an approximation when subscriber  subscribers/updates his info using subscription form based on his/her IP")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 </small> <br />
     
	 <?php echo ((is_array($_tmp='Country')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 :&nbsp;<span id="geocountry"></span><br>
     <?php echo ((is_array($_tmp="State/Region ")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 :&nbsp;<span id="geostate"></span><br>
     <?php echo ((is_array($_tmp='City')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 :&nbsp;<span id="geocity"></span><br>
 
  </div>
  
  <div id="unsubscribebox" style="display: none; margin-top: 15px; padding: 10px; border: 1px solid #E0DFDC; color:#999999;">
	 <?php echo ((is_array($_tmp='Unsubscribed on ')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 <span id="unsubscribedate"></span><br>
  </div>
      </div>
       
    </aside>
    <div id="listmodal" class="adesk_modal" style="display:none">
  <div class="adesk_modal_inner">
	<h3 class="m-b"><?php echo ((is_array($_tmp='Add Subscription')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>

	<p>
	  <?php echo ((is_array($_tmp="Subscribe to one of the lists below by choosing it from the dropdown and clicking the \"Add\" button.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

	</p>

	<div id="listmodaldiv">
	</div>

	<br>

	<div>
	  <input type="button" class="adesk_button_ok" value='<?php echo ((is_array($_tmp='Add')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onclick="subscriber_view_subscribe()">
	  <input type="button" class="adesk_button_cancel" value='<?php echo ((is_array($_tmp='Cancel')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onclick="$('listmodal').hide()">
	</div>
  </div>
</div>
    <!-- /.sidebar -->
    <!-- .sidebar -->
    <section class="main" style="padding-left:10px;">
       
      <ul class="nav nav-tabs m-b-none no-radius">
        <li class="active"><a data-toggle="tab" href="#maindiv"><?php echo ((is_array($_tmp='Lists')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></li>
        <li class=""><a data-toggle="tab" href="#campaignhistory"><?php echo ((is_array($_tmp='Recent Campaign History')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></li>
        <li class=""><a data-toggle="tab" href="#future"><?php echo ((is_array($_tmp='Future')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></li>
        <li class=""><a data-toggle="tab" href="#bounce"><?php echo ((is_array($_tmp='Bounces')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a></li>
      </ul>
      <div class="tab-content">
        <div id="maindiv" class="tab-pane active">
        <h3 class="m-b"><?php echo ((is_array($_tmp='Lists')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>  <p>
  <?php echo ((is_array($_tmp=$this->_tpl_vars['name'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
 <?php echo ((is_array($_tmp='is subscribed to')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 <strong><span id="listcount"><?php echo ((is_array($_tmp=@$this->_tpl_vars['listcount'])) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
</span></strong> <?php echo ((is_array($_tmp="mailing lists.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

	<?php echo ((is_array($_tmp="Select a list to view list specific data:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

  </p>

  <p>
	<span id="listdiv">
	  <select id="listid" onchange="subscriber_view_load_fields(0)">
	  </select>
	</span>
	<span id="subscribelink" style="display:none">
	  <?php if ($this->_tpl_vars['admin']['pg_subscriber_edit']): ?>
	  <em>-<?php echo ((is_array($_tmp='or')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
-</em>
	  <a href="#" onclick="subscriber_view_unlists(); return false"><?php echo ((is_array($_tmp=((is_array($_tmp="add %s to another list")) ? $this->_run_mod_handler('alang', true, $_tmp, $this->_tpl_vars['name']) : smarty_modifier_alang($_tmp, $this->_tpl_vars['name'])))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a>
	  <?php endif; ?>
	</span>
  </p>  <div id="details">
	<div style="float: right">
	  <span id="details_fields_editlink">
		<?php if ($this->_tpl_vars['admin']['pg_subscriber_edit']): ?>
		<a href="#" onclick="subscriber_view_load_fields(1); return false"><?php echo ((is_array($_tmp='Edit')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
		<?php endif; ?>
	  </span>
	  <span id="details_fields_unsubscribelink">
		<?php if ($this->_tpl_vars['admin']['pg_subscriber_edit'] && $this->_tpl_vars['admin']['pg_subscriber_delete']): ?>
		<a style="margin-left: 10px" href="#" onclick="subscriber_view_unsubscribe(); return false"><?php echo ((is_array($_tmp='Unsubscribe')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</a>
		<?php endif; ?>
	  </span>
	</div>
	<div>
	  <h3 class="m-b"><?php echo ((is_array($_tmp="Subscriber Details (List:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
 <span id="details_listname"></span>)</h3>
	</div>

	<div id="details_fields" style="clear: right; margin-top: 10px">
	</div>

	<div id="details_fields_updatebutton" style="display:none">
	  <input type="button" value='<?php echo ((is_array($_tmp='Update')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onclick="subscriber_view_save_fields()"/>
	  <input type="button" value='<?php echo ((is_array($_tmp='Cancel')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
' onclick="subscriber_view_load_fields(0)"/>
	</div>
  </div>
        </div>
        <div id="campaignhistory" class="tab-pane">
       <h3 class="m-b"><?php echo ((is_array($_tmp='Recent Campaign History')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
<span style="color:#ccc;"> <?php echo ((is_array($_tmp="(Last 90 days)")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</span></h3>

	  <div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  cellspacing="0" cellpadding="0" width="100%">
		<tr class="adesk_table_header_options">
		  <td>
			<select name="listid" id="logListManager" size="1" onchange="subscriber_view_filter(this.value);">
			  <option value="0"><?php echo ((is_array($_tmp="List Filter...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</option>
			  <?php $_from = $this->_tpl_vars['listsList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['p']):
?>
			  <option value="<?php echo $this->_tpl_vars['p']['id']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['p']['name'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 50) : smarty_modifier_truncate($_tmp, 50)); ?>
</option>
			  <?php endforeach; endif; unset($_from); ?>
			</select>
		  </td>
		</tr>
	  </table></div>

	  <div class=" table-responsive"><table class="table table-striped m-b-none dataTable"  border="0" cellspacing="0" cellpadding="1" width="100%s">
		<thead id="log_head">
		  <tr class="adesk_table_header">
			<td width="150"><?php echo smarty_function_adesk_headercol(array('action' => 'subscriber_view','id' => '01','idprefix' => 'log_sorter','label' => ((is_array($_tmp='List')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp))), $this);?>
</td>
			<td><?php echo smarty_function_adesk_headercol(array('action' => 'subscriber_view','id' => '02','idprefix' => 'log_sorter','label' => ((is_array($_tmp='Campaign')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp))), $this);?>
</td>
			<td width="120"><?php echo smarty_function_adesk_headercol(array('action' => 'subscriber_view','id' => '03','idprefix' => 'log_sorter','label' => ((is_array($_tmp='Date Sent')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp))), $this);?>
</td>
		  </tr>
		</thead>
		<tbody id="log_table">
		</tbody>
	  </table></div>
	  <div id="log_noresults" class="adesk_hidden">
		<div align="center"><?php echo ((is_array($_tmp="Nothing found.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</div>
	  </div>
	  <div style="float:right">
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "pagination.js.tpl.htm", 'smarty_include_vars' => array('tabelize' => 'subscriber_view_tabelize','paginate' => 'subscriber_view_paginate','limitize' => 'subscriber_view_limitize','paginator' => $this->_tpl_vars['paginators']['log'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	  </div>
	  <div id="logLoadingBar" class="adesk_hidden" style="background: url(../awebdesk/media/loader.gif); background-repeat: no-repeat; padding: 5px; padding-left: 20px; padding-top: 2px; color: #999999; font-size: 10px; margin: 5px">
		<?php echo ((is_array($_tmp="Loading. Please wait...")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

	  </div>
        </div>
        <div id="future" class="tab-pane">
         	<h3 class="m-b"><?php echo ((is_array($_tmp='Future')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>
	<ul>
	  <?php $_from = $this->_tpl_vars['future']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['e']):
?>
	  <li><?php echo $this->_tpl_vars['e']; ?>
</li>
	  <?php endforeach; else: ?>
	  <?php echo ((is_array($_tmp=((is_array($_tmp="There are no campaigns that are scheduled to send to %s in the near future.")) ? $this->_run_mod_handler('alang', true, $_tmp, $this->_tpl_vars['name']) : smarty_modifier_alang($_tmp, $this->_tpl_vars['name'])))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

	  <?php endif; unset($_from); ?>
	</ul>
        </div>
     <div id="bounce" class="tab-pane">
          <h3 class="m-b"><?php echo ((is_array($_tmp='Bounces')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>
	<ul>
	  <?php $_from = $this->_tpl_vars['bounces']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['e']):
?>
	  <li><?php echo $this->_tpl_vars['e']; ?>
</li>
	  <?php endforeach; else: ?>
	  <?php echo ((is_array($_tmp=((is_array($_tmp="No mailing to %s has ever bounced.")) ? $this->_run_mod_handler('alang', true, $_tmp, $this->_tpl_vars['name']) : smarty_modifier_alang($_tmp, $this->_tpl_vars['name'])))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

	  <?php endif; unset($_from); ?>
	</ul>
        </div>
      </div>
    </section>
    <!-- /.sidebar -->
    <script type="text/javascript">
  subscriber_view_lists(0);
  //subscriber_view_process_mailing(["log", subscriber_view_sort, "0"]);
  subscriber_view_process_log(["log", subscriber_view_sort, "0"]);
  adesk_ui_rsh_init(subscriber_view_process, true);
</script>

  </section>