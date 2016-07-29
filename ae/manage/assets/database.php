<?php

require_once adesk_admin("functions/database.php");
require_once awebdesk_classes("select.php");
require_once awebdesk_classes("pagination.php");
require_once awebdesk_functions("file.php");

class database_assets extends AWEBP_Page {
	function database_assets() {
		$this->pageTitle = _a("Database Utilities");
		$this->sideTemplate = "side.settings.htm";
		$this->AWEBP_Page();
		$this->admin = $GLOBALS["admin"];
	}

	function process(&$smarty) {
		if (isset($GLOBALS["_hosted_account"])) {
			adesk_smarty_noaccess($smarty, $this);
			return;
		}

		$smarty->assign("content_template", "database.htm");
		$this->setTemplateData($smarty);

		adesk_smarty_submitted($smarty, $this);

		if ($this->admin["id"] == 1 && adesk_http_param("backup")) {
			$gz = adesk_http_param("gz");
			$file = sprintf("backup-%s.", date("Ymd-His"));
			adesk_http_header_attach($file . ($gz ? 'gz' : 'sql'));	# Assumes appliation/octet-stream
			database_backup($gz);
			exit;
		}
	}

	function formProcess(&$smarty) {
		if ($this->admin["id"] == 1 && isset($_FILES["restore"])) {
#			if ($_FILES["restore"]["size"] > 5000000) {
			if ($_FILES["restore"]["size"] > 30000) {
				adesk_smarty_message($smarty, _a("Database backup file to restore is too large.  Consult with your MySQL administrator about restoring from the command line."));
				return;
			}
			$sql = adesk_file_get($_FILES["restore"]["tmp_name"]);
			adesk_sql_restore($sql, true, 'comment');
		}
	}
}

?>
