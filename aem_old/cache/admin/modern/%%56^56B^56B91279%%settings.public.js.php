<?php /* Smarty version 2.6.12, created on 2016-07-08 14:11:51
         compiled from settings.public.js */ ?>
<?php echo '

function settings_public_load() {
	$("settings_public").className = "adesk_block";
}

function public_rewrite_check(checked) {
	if (checked)
		$("public_rewrite_tbody").className = "adesk_table_rowgroup";
	else
		$("public_rewrite_tbody").className = "adesk_hidden";
}

function public_rewrite_htaccess() {
	$(\'htaccess\').style.display = \'block\';
}

'; ?>