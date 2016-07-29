<?php /* Smarty version 2.6.12, created on 2016-07-08 15:27:04
         compiled from mainjs.inc.js */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'jsvar', 'mainjs.inc.js', 5, false),)), $this); ?>
paginator_b64 = false;	  // don't base64 encode
// define vars
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "strings.js", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php echo smarty_function_jsvar(array('name' => 'datetimeformat','var' => $this->_tpl_vars['site']['datetimeformat']), $this);?>

<?php echo smarty_function_jsvar(array('name' => 'dateformat','var' => $this->_tpl_vars['site']['dateformat']), $this);?>

<?php echo smarty_function_jsvar(array('name' => 'timeformat','var' => $this->_tpl_vars['site']['timeformat']), $this);?>

<?php echo smarty_function_jsvar(array('name' => 'adesk_js_site','var' => $this->_tpl_vars['jsSite']), $this);?>

<?php echo smarty_function_jsvar(array('name' => 'adesk_js_admin','var' => $this->_tpl_vars['jsAdmin']), $this);?>

<?php echo smarty_function_jsvar(array('name' => 'adesk_action','var' => $this->_tpl_vars['action']), $this);?>

<?php echo smarty_function_jsvar(array('name' => 'plink','var' => $this->_tpl_vars['plink']), $this);?>


var apipath = "<?php echo $this->_tpl_vars['_']; ?>
/awebdeskapi.php";
var acgpath = "<?php echo $this->_tpl_vars['plink']; ?>
/awebdesk";

adesk_tooltip_init();
adesk_editor_init_mid_object.content_css = adesk_js_site.p_link + adesk_editor_init_mid_object.content_css;

<?php echo '
adesk_liveedit_onclose = function(id) {
	return;
	if (id == "acontent") {
		// Article content
		window.setTimeout(function() {
			main_highlight_def($("article_content"), glossary, "article_highlight");
			main_highlight_def($("article_content"), glossary_s, "article_highlight");
		}, 200);
	} else if (id == "category_descript") {
		// Category description
		window.setTimeout(function() {
			main_highlight_def($("category_descript"), glossary, "article_highlight");
			main_highlight_def($("category_descript"), glossary_s, "article_highlight");
		}, 200);
	}
}

function main_highlight_def(elem, dict, cls) {
	adesk_dom_highlight(elem, dict, true);
	adesk_dom_highlight_definition(elem, dict, cls);
}

function main_highlight(elem, dict, cls) {
	adesk_dom_highlight(elem, dict, false);
	adesk_dom_highlight_replace(elem, dict, cls);
}

'; ?>
