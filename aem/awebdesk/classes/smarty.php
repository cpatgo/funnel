<?php
// smarty.php

// Subclassing the Smarty class, similar to how we would do so in our
// product code, but specifically for awebdesk.  This is intended to
// replace OTA/TT/ProductSmarty class references.

if (!defined("awebdesk_USE_OLD_SMARTY"))
    require_once(awebdesk('smarty/Smarty.class.php'));
else
    require_once(adesk_admin('functions/smarty/Smarty.class.php'));

//require_once awebdesk_functions("site.php");
require_once awebdesk_functions("utf.php");
require_once awebdesk_classes("mobdetect.php");

class adesk_Smarty extends Smarty {

    var $_folder      = '';
    var $_smartyPath  = '';
    var $_publicPath  = '';
    var $_adminPath   = '';
    var $_globalPath  = '';
    var $_deskPath    = '';
    var $_pluginsPath = '';

    var $_ct_header_sent = false;

    function adesk_Smarty($folder, $email = false, $mobile = false) {
    	$this->_folder = $folder;
        $this->_smartyPath  = awebdesk('smarty');
        $this->_pluginsPath = awebdesk('smarty_plugins');
        $this->_globalPath  = awebdesk();
        $this->_adminPath   = adesk_admin();
        $this->_publicPath  = adesk_base();

        parent::Smarty();

        $this->compile_check = true;
//      $this->force_compile = true;
//      $this->debugging     = true;

		if ( !isset($GLOBALS['adesk_app_partof']) ) $GLOBALS['adesk_app_partof'] = null;

		$this->template_dir = array();
		if ( $folder != 'global' and $GLOBALS['adesk_app_partof'] ) {
			if ( $folder == 'admin' ) {
				//
				$parentPath = dirname($this->_publicPath) . '/manage';
			} else/*if ( $folder == 'admin' )*/ {
				//
				$parentPath = dirname($this->_publicPath);
			}
			$this->template_dir[] = $parentPath . '/templates';
			$this->template_dir[] = $parentPath . '/js';
		}
		//mob detect
		$detect = new Mobile_Detect;
		
		
		if ( $folder == 'public' ) {
			// public section
		/*				if($detect->isMobile())
			$this->template_dir[] = $this->_publicPath . '/templates/mobile';
         
			else
			$this->template_dir[] = $this->_publicPath . '/templates/desktop';*/
			$this->template_dir[] = $this->_publicPath . '/templates';
			$this->template_dir[] = $this->_publicPath . '/js';
			
			
			
		} elseif ( $folder == 'admin' ) {
			// admin section
			$admin99 = adesk_admin_get();
			
			if($detect->isMobile()){
			$dashtheme = $admin99['default_mobdashboard'];
			$this->template_dir[] = $this->_adminPath . '/templates/'.$dashtheme.'/';
			}
			else {
			   $dashtheme = $admin99['default_dashboard'];
			   $this->template_dir[] = $this->_adminPath . '/templates/'.$dashtheme.'/';
			}
			
			$this->template_dir[] = $this->_adminPath . '/js';
		} elseif ( $folder == 'global' ) {
			// awebdesk is always added, so it doesn't have to be referenced here
			$this->template_dir[] = $this->_globalPath . '/templates';
			$this->template_dir[] = $this->_globalPath . '/js';
/* AHD START */
		} else {
			// desk
			$this->template_dir[] = $this->_publicPath . '/desk/' . $folder . '/templates';
			$this->template_dir[] = $this->_publicPath . '/js';
		}
		if ( is_dir($this->_publicPath . '/kb/templates') ) {
			$prfx = $folder == 'admin' ? '/manage' : '';
			$this->template_dir[] = $this->_publicPath . "/kb$prfx/templates";
			$this->template_dir[] = $this->_publicPath . "/kb$prfx/js";
		}
		if ( $mobile ) {
			$this->template_dir[] = $this->template_dir[0];// move real folder to the end, for the case where mobile folder wants to use parent's
			$this->template_dir[0] .= '/mobile';
		}
/* AHD END */

        if ($email)
            $this->template_dir[0] .= '/emails';
		if ( $folder == 'admin' or $folder == 'public' or $folder == 'global' ) {
			if ( $GLOBALS['adesk_app_partof'] && !isset($GLOBALS['_hosted_account']) ) $GLOBALS['customCachePath'] = dirname($this->_publicPath);
			
	 	$admin99 = adesk_admin_get();
			
			if($detect->isMobile()){
			$dashtheme = $admin99['default_mobdashboard'];
			 
				$this->compile_dir =
				( isset($GLOBALS['customCachePath']) ? $GLOBALS['customCachePath'] : $this->_publicPath ) .
				'/cache/' .
				( $folder == 'global' ? 'public' : $folder.'/'.$dashtheme )
			;
			}
			else {
			   $dashtheme = $admin99['default_dashboard'];
			   
			  	$this->compile_dir =
				( isset($GLOBALS['customCachePath']) ? $GLOBALS['customCachePath'] : $this->_publicPath ) .
				'/cache/' .
				( $folder == 'global' ? 'public' : $folder.'/'.$dashtheme )
			;
			}
			 
			/*$this->compile_dir =
				( isset($GLOBALS['customCachePath']) ? $GLOBALS['customCachePath'] : $this->_publicPath ) .
				'/cache/' .
				( $folder == 'global' ? 'public' : $folder )
			;*/
			
		
			
			
			
		} else {
			$this->compile_dir = $this->_publicPath . '/desk/' . $folder . '/cache';
		}
		// assign final template_dir (array)
        if ( $folder != 'global' ) {// if not awebdesk only
        	$this->template_dir[] = awebdesk('templates'); // add awebdesk templates to the list
        	$this->template_dir[] = awebdesk('js'); // add awebdesk javascript files to the list
        }
//      $this->cache_dir   = $this->_smartyPath . '/cache';
        $this->config_dir  = $this->_smartyPath . '/configs';
        $this->plugins_dir = array($this->_smartyPath . '/plugins', $this->_pluginsPath);
        if ( is_dir($this->_adminPath . '/functions/smarty_plugins') ) {
        	$this->addPluginDir($this->_adminPath . '/functions/smarty_plugins');
        }

		# Set up some custom variables

		$this->assign("globalurl", isset($GLOBALS["awebdesk_url"]) ? $GLOBALS["awebdesk_url"] : "");
		$this->assign('year', date('Y'));

		if (isset($_SESSION["adesk_smarty_message"])) {
			$this->assign("resultMessage", $_SESSION["adesk_smarty_message"]);
			unset($_SESSION["adesk_smarty_message"]);
		}
    }

    function addPluginDir($path) {
    	if ( !in_array($path, $this->plugins_dir) )
    		$this->plugins_dir[] = $path;
    }

    function sendCTheader($str) {
    	$this->_ct_header_sent = true;
    	if ( !headers_sent() ) header($str);
    }

	function assign($key, $val = null, $convert = true) {
		if ($convert && $val !== null && isset($GLOBALS["adesk_app_utf8"]) && $GLOBALS["adesk_app_utf8"] && function_exists("_i18n"))
			$val = adesk_utf_deepconv("UTF-8", _i18n("utf-8"), $val);

		parent::assign($key, $val);
	}

	function getvar($key) {
		# Shorthand for get_template_vars if you want a single variable.
		return $this->get_template_vars($key);
	}

	function setConstants() {
		$consts = get_defined_constants();

		foreach ($consts as $ck => $cv) {
			if (substr($ck, 0, 3) == "adesk_")
				$this->assign($ck, $cv);
		}
	}

    function display($tpl, $cache_id = null, $compile_id = null) {
		$this->setConstants();
    	if ( !function_exists('smarty_modifier_i18n') )
    		require_once(dirname(dirname(__FILE__)) . '/smarty_plugins/modifier.i18n.php');
   		if ( !$this->_ct_header_sent )
			$this->sendCTheader('Content-Type: text/html; charset=' . smarty_modifier_i18n("utf-8"));
/*			if (function_exists('headers_list')) {
				$list = headers_list();
				$slist = strtolower(implode("--", $list));
				if (strpos($slist, "content-type:") === false) {
					header('Content-Type: text/html; charset=' . smarty_modifier_i18n("utf-8"));
				}
			} else {
				# Maybe we can do something else here.
			}
*/
    	$r = parent::display($tpl, $cache_id, $compile_id);
		adesk_smarty_message_clear($this);
    	return $r;
    }

    function fetch($resource_name, $cache_id = null, $compile_id = null, $display = false) {
		$this->setConstants();
		$r = parent::fetch($resource_name, $cache_id, $compile_id, $display);
		return $r;
	}
}

?>
