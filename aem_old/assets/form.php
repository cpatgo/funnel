<?php

require_once adesk_admin("functions/form.php");
require_once awebdesk_classes("select.php");
require_once awebdesk_classes("pagination.php");
class form_assets extends AWEBP_Page {

	function form_assets() {
		$this->pageTitle = _a("Subscription Form");
		$this->AWEBP_Page();
	}

	function process(&$smarty) {

		$this->setTemplateData($smarty);

		// get form id
		$id = (int)adesk_http_param('id');
		if ( $id == 0 ) {
			adesk_http_redirect(adesk_site_plink());
		}

		// get form object
		$form = form_select_row($id);
		if ( $id == 0 ) {
			adesk_http_redirect(adesk_site_plink());
		}

		// get code type
		$type = (string)adesk_http_param('type');

		// get source character set
		$_charset = (string)adesk_http_param('_charset');
		if ( !$_charset ) $_charset = _i18n('utf-8');

		// generate the actual subscription form
		$this->site['acpow'] = base64_encode($this->site['acpow']);
		$code = form_generate($form, $type);
		$this->site['acpow'] = base64_decode($this->site['acpow']);

		if ( $_charset != _i18n('utf-8') ) {
			$code = str_replace(
				'<input type="hidden" name="_charset" value="' . _i18n('utf-8') . '" />',
				'<input type="hidden" name="_charset" value="' . $_charset . '" />',
				$code
			);
		}

		// print it out and stop if only form is requested
		if ( in_array($type, array('html', 'xml', 'link', 'popup')) ) {
			echo $code;
			exit;
		}

		$smarty->assign('form', $form);
		$smarty->assign('code', $code);

		// display regular page with form inside
		$smarty->assign("content_template", "form.htm");
	}
}

?>
