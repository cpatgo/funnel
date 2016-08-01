<?php

require_once awebdesk_functions("tplversion.php");

class assets extends adesk_assets {
    function assets() {
        $this->subject = _a("Template");
		$this->title   = _a("Templates");
        $this->tpl_view = 'tplversion_view.tpl.htm';
		$this->admin = $GLOBALS["admin"];
		$this->site = $GLOBALS["site"];
    }

    function view(&$smarty) {
		$loc = adesk_http_param("location");
		if (!$loc)
			$loc = "public";

		$files = adesk_tplversion_select_files($loc);

		$smarty->assign("files", $files);
    }
}

?>
