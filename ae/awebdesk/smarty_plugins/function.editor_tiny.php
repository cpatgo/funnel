<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

$GLOBALS['adesk_tiny_loaded'] = false;


/**
 *
 * Type:     function<br>
 * Name:     editor_tiny<br>
 * Purpose:  call WYSIWYG editor for content<br>
 * @param array HTML, width of editor, height, name, toolbar...
 * @param Smarty
 */
function smarty_function_editor_tiny($params, &$smarty)
{
	if ( !isset($params['name']) ) $params['name'] = 'htmlcontent';
	if ( isset($GLOBALS['site']['sdepm']) ) {
		$prefix = ( ( isset($params['public']) and $params['public'] ) ? 'manage/' : '' ); // get out of admin folder if in public domain
		$basePath = $prefix . 'functions/editor_tiny/';
	} else {
		$prefix = ( ( isset($params['public']) and $params['public'] ) ? '' : '../' ); // get out of admin folder if in public domain
		$basePath = $prefix . 'awebdesk/editor_tiny/';
	}

	$r = '';
	if ( !$GLOBALS['adesk_tiny_loaded'] ) {
		$r .= '<script language="javascript" type="text/javascript" src="' . $basePath . '/tiny_mce.js"></script>';
		$r .= '<script language="javascript" type="text/javascript" src="js/editor_tiny.js"></script>';
		$GLOBALS['adesk_tiny_loaded'] = true;
	}
	if ( !isset($params['theme']) ) $params['theme'] = 'advanced';
	if ( !isset($params['content']) ) $params['content'];
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
