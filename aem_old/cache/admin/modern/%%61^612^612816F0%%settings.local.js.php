<?php /* Smarty version 2.6.12, created on 2016-07-08 14:11:51
         compiled from settings.local.js */ ?>
<?php echo '

function settings_local_load() {
	$("settings_local").className = "adesk_block";
}

function settings_local_timezone_change(current_value, new_value) {
	if (new_value != current_value) {
		$("local_zoneid_old_div").show();
	}
	else {
		$("local_zoneid_old_check").checked = false;
		$("local_zoneid_old_div").hide();
	}
}

function settings_local_lang_change(current_value, new_value) {
	if (new_value != current_value) {
		$("local_lang_old_div").show();
	}
	else {
		$("local_lang_old_check").checked = false;
		$("local_lang_old_div").hide();
	}
}

'; ?>