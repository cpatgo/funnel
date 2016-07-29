<?php /* Smarty version 2.6.12, created on 2016-07-08 14:15:46
         compiled from subscriber.exportlist.js */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'alang', 'subscriber.exportlist.js', 1, false),array('modifier', 'js', 'subscriber.exportlist.js', 1, false),)), $this); ?>
var subscriber_export_str_cant_export = '<?php echo ((is_array($_tmp=((is_array($_tmp='You do not have permission to export subscribers')) ? $this->_run_mod_handler('alang', true, $_tmp) : smarty_modifier_alang($_tmp)))) ? $this->_run_mod_handler('js', true, $_tmp) : smarty_modifier_js($_tmp)); ?>
';

<?php echo '

var subscriber_exportlist_filterid = "0";

function subscriber_exportlist_check(filterid) {

	if (adesk_js_admin.pg_subscriber_export != 1) {
		adesk_ui_anchor_set(subscriber_list_anchor());
		alert(subscriber_export_str_cant_export);
		return;
	}

	subscriber_exportlist_filterid = filterid;
	$("exportlist").className = "adesk_block";
}

function subscriber_exportlist_close() {
	$("exportlist").className = "adesk_hidden";
	adesk_ui_anchor_set(subscriber_list_anchor());
}

function subscriber_exportlist_export() {
	adesk_ui_api_call(jsLoading);
	var post = adesk_form_post($("exportlist"));
	post.filterid = subscriber_exportlist_filterid;

	// Check to see what limit we should use.
	if (post.howmany == "page")
		post.limit = paginators[1].limit;
	else
		post.limit = 0;

	adesk_ajax_post_cb("awebdeskapi.php", "subscriber.subscriber_exportlist_export", adesk_ajax_cb(subscriber_exportlist_export_cb), post);
}

function subscriber_exportlist_export_cb(ary) {
	adesk_ui_api_callback();
	subscriber_exportlist_close();
}

'; ?>
