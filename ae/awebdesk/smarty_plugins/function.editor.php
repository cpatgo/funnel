<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 *
 * Type:     function<br>
 * Name:     editor<br>
 * Purpose:  call WYSIWYG editor for content<br>
 * @param array HTML, width of editor, height, name, toolbar...
 * @param Smarty
 */
function smarty_function_editor($params, &$smarty)
{
	if ( !class_exists('FCKeditor') ) {
		//if ( isset($GLOBALS['site']['sdepm']) ) {
			//require_once(adesk_admin('functions/editor/fckeditor.php'));
		//} else {
			require_once(awebdesk('editor/fckeditor.php'));
		//}
	}
	if ( !isset($params['name']) ) $params['name'] = 'htmlcontent';
	$editor = new FCKeditor($params['name']);
	//if ( isset($GLOBALS['site']['sdepm']) ) {
		//$prefix = ( ( isset($params['public']) and $params['public'] ) ? 'manage/' : '' ); // get out of admin folder if in public domain
		//$editor->BasePath = $prefix . 'functions/editor/';
	//} else {
		$prefix = ( ( isset($params['public']) and $params['public'] ) ? '' : '../' ); // get out of admin folder if in public domain
		$editor->BasePath = $prefix . 'awebdesk/editor/';
	//}

	if ( isset($params['toolbar']) ) $editor->ToolbarSet = $params['toolbar'];
	if ( isset($params['content']) ) $editor->Value = $params['content'];
	if ( isset($params['width']) AND (int)$params['width'] != 0 ) $editor->Width = $params['width'];
	if ( isset($params['height']) AND (int)$params['height'] != 0 ) $editor->Height = $params['height'];
	if ( isset($params['customcfg']) ) $editor->Config['CustomConfigurationsPath'] = $params['customcfg'];
	$editor->Config['FullPage'] = ( isset($params['fullpage']) ? (bool)$params['fullpage'] : true );
	$editor->Config['AutoDetectLanguage'] = false;
	$editor->Config['DefaultLanguage'] = _i18n('en-us');
	$editor->Config['BaseHref'] = '';
	if ( isset($params['href']) ) {
		$editor->Config['BaseHref'] = $params['href'];
		//if ( !preg_match('/\/$/', $params['href']) )
			//$editor->Config['BaseHref'] .= '/';
	}
	return $editor->Create();
}



?>
