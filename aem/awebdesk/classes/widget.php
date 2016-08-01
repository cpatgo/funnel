<?php

class adesk_Widget {

	var $id = 0;
	var $section = 'both';
	var $title = '';
	var $bars = '';

	var $info = array();
	var $config = array();
	//var $options = array();

	var $display = true;

	function adesk_Widget($widget) { return $this->__construct($widget); }

	function __construct($widget) {
		$this->id = $widget['id'];
		$this->section = $widget['section'];
		$this->info = $widget;
		$tmpvar = @unserialize($widget['config']);
		if ( is_array($tmpvar) ) {
			$this->config = $tmpvar;
		}
		/*
		$tmpvar = @unserialize($widget['options']);
		if ( is_array($tmpvar) ) {
			$this->options = $tmpvar;
		}
		*/
		$this->title = $widget['title'];
		$this->bars = $widget['bars'];
	}

	function runFunc($func) {
		ob_start();
		$r2 = $this->$func();
		$r1 = ob_get_contents();
		ob_end_clean();
		return ( !$r1 and $r2 ) ? $r2 : $r1; // allow them to return the HTML, or just print it. printed one takes presedence
	}

	function getForm() {
		return $this->runFunc('form');
	}

	function showWidget() {
		return $this->runFunc('show');
	}

	function saveWidget() {
		// don't save it if we don't have an id already
		if ( !$this->id ) return false;
		// first save our (internal) stuff
		// save in which bars can it appear
		$bars = adesk_http_param('widget_bars');
		if ( !is_array($bars) ) $bars = array();
		// get all available bars
		$allbars = widget_bar_available();
		// if we have all bars here
		if ( count(array_intersect(array_keys($allbars[$this->section]), $bars)) == count($allbars[$this->section]) ) {
			// reset it to all
			$bars = array();
		}
		$update = array(
			'title' => adesk_http_param('widget_title'),
			'bars' => implode(',', $bars),
		);
		adesk_sql_update("#widget", $update, "`id` = '{$this->id}'");
		// then prepare their array for saving
		$config = $this->save();
		if ( $config === false ) return false;
		if ( !is_array($config) ) $config = array();
		// save their array
		return adesk_sql_update_one("#widget", "config", serialize($config), "`id` = '{$this->id}'");
	}

	function installWidget() {
		// don't install it if we don't have an id already
		if ( !$this->id ) return false;
		// first install our (internal) stuff
		// ...
		// then prepare their array for installing
		$done = $this->install();
		if ( $done === false ) return false;
		return true;
	}

	function uninstallWidget() {
		// don't uninstall it if we don't have an id already
		if ( !$this->id ) return false;
		// first uninstall our (internal) stuff
		// ...
		// then prepare their array for uninstalling
		$done = $this->uninstall();
		if ( $done === false ) return false;
		return true;
	}

	function getSmartyVar($varname, $smarty = null) {
		if ( is_null($smarty) and !isset($GLOBALS['smarty']) ) return null;
		if ( is_null($smarty) ) $smarty = $GLOBALS['smarty'];
		return $smarty->get_template_vars($varname);
	}

	// these should be overloaded
	function form() {}
	function save() {}
	function show() {}
	function install() {}
	function uninstall() {}

}

?>