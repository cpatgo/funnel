<?php /* Smarty version 2.6.12, created on 2016-07-08 14:11:51
         compiled from settings.general.js */ ?>
<?php echo '

function settings_general_load() {
	$("settings_general").className = "adesk_block";
}

function general_maint_check(checked) {
	if (checked)
		$("general_maint_tbody").className = "adesk_table_rowgroup";
	else
		$("general_maint_tbody").className = "adesk_hidden";
}

'; ?>