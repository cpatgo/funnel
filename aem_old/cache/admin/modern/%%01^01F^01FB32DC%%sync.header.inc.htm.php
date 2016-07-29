<?php /* Smarty version 2.6.12, created on 2016-07-08 14:18:25
         compiled from sync.header.inc.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'sync.header.inc.htm', 67, false),)), $this); ?>

<script>
//<!--
<?php echo '
var resultCodes = {
	succeeded: 0,
	failed: 2,
	bounced: 4,
	duplicated: 8,
	unsubscribed: 16,
	excluded: 32,
	blocked: 64
};
function ihook_adesk_sync_report(ary) {

	// set counts
	for ( var res in resultCodes ) {
		var code = resultCodes[res];
		var cnt = ( ary.counts[res] && ary.counts[res] > 0 ? ary.counts[res] : 0 );
		// set count
		if ( $(\'report_\' + res) ) $(\'report_\' + res).innerHTML = cnt;
		if ( $(\'report_\' + res + \'_box\') ) $(\'report_\' + res + \'_box\').className = \'adesk_hidden\';
		if ( $(\'sync_report_\' + res) ) $(\'sync_report_\' + res).className = ( cnt > 0 ? \'adesk_block\' : \'adesk_hidden\' );
		if ( $(\'report_\' + res + \'_list\') ) {
			adesk_dom_remove_children($(\'report_\' + res + \'_list\'));
			if ( cnt > 0 ) {
				// show it
				for ( var i = 0; i < cnt; i++ ) {
					var row = ary.lists[res][i];
					var txt = row.email;
					if ( res == \'failed\' ) {
						// this one varies, show message
						txt += \' (\' + row.msg + \')\';
					}
					$(\'report_\' + res + \'_list\').appendChild(
						Builder.node(
							\'li\',
							{ className: \'sync_report_row\' },
							[
								Builder._text(txt)
							]
						)
					);
				}
			} else {
				// hide it
			}
		}
	}
}
'; ?>

//-->
</script>

<div id="sync_report" class="adesk_modal" align="center" style="display: none">
  <div class="adesk_modal_inner">
    <h3 class="m-b"><?php echo ((is_array($_tmp='Synchronization Report')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
</h3>
    
	<p>
		<?php echo ((is_array($_tmp="This page will show you the activity for the synchronization process performed.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

		<?php echo ((is_array($_tmp="By looking at this information you can see if your emails are synchronized properly.")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

	</p>

    <div style="font-weight:bold;">
    	<?php echo ((is_array($_tmp="Total Items Found:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

    	<span id="report_count">0</span>
    </div>

    <div style="font-weight:bold;">
    	<?php echo ((is_array($_tmp="Synchronized:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

    	<span id="report_count1">0</span>
    </div>

    <div style="font-weight:bold;">
    	<?php echo ((is_array($_tmp="Not Synchronized:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

    	<span id="report_count0">0</span>
    </div>

    <hr />

    <div id="sync_report_failed" class="adesk_hidden">
	    <div>
	    	<a href="#" onclick="adesk_dom_toggle_class('report_failed_box', 'adesk_scroller', 'adesk_hidden');return false;">
	    		<?php echo ((is_array($_tmp="Failed Rows:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

	    		<span id="report_failed">0</span>
	    	</a>
	    </div>
	    <div id="report_failed_box" class="adesk_hidden">
		    <ol id="report_failed_list"></ol>
	    </div>
    </div>

    <div id="sync_report_bounced" class="adesk_hidden">
	    <div>
	    	<a href="#" onclick="adesk_dom_toggle_class('report_bounced_box', 'adesk_scroller', 'adesk_hidden');return false;">
	    		<?php echo ((is_array($_tmp="Bounced in the past:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

	    		<span id="report_bounced">0</span>
	    	</a>
	    </div>
	    <div id="report_bounced_box" class="adesk_hidden">
		    <ol id="report_bounced_list"></ol>
	    </div>
    </div>

    <div id="sync_report_unsubscribed" class="adesk_hidden">
	    <div>
	    	<a href="#" onclick="adesk_dom_toggle_class('report_unsubscribed_box', 'adesk_scroller', 'adesk_hidden');return false;">
		    	<?php echo ((is_array($_tmp="Unsubscribed in the past:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

		    	<span id="report_unsubscribed">0</span>
		    </a>
	    </div>
	    <div id="report_unsubscribed_box" class="adesk_hidden">
		    <ol id="report_unsubscribed_list"></ol>
	    </div>
    </div>

    <div id="sync_report_excluded" class="adesk_hidden">
	    <div>
	    	<a href="#" onclick="adesk_dom_toggle_class('report_excluded_box', 'adesk_scroller', 'adesk_hidden');return false;">
	    		<?php echo ((is_array($_tmp="On Exclusion List:")) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>

	    		<span id="report_excluded">0</span>
	    	</a>
	    </div>
	    <div id="report_excluded_box" class="adesk_hidden">
		    <ol id="report_excluded_list"></ol>
	    </div>
    </div>

    <br />

    <div>
      <input type="button" class="adesk_button_close" value="<?php echo ((is_array($_tmp='Close')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)); ?>
" onclick="adesk_dom_toggle_display('sync_report', 'block');adesk_dom_toggle_display('syncRunPanel', 'block');" />
    </div>
  </div>
</div>