<?php

function widget_available() {
	// we will return this
	$widgets = array();
	// default configuration folder
	$cfg = array(
		'widget' => '',
		'section' => '',
		'name' => '',
		'description' => '',
		'version' => '',
		'url' => '',
		'author' => '',
		'homepage' => '',
		'license' => '',
		'usein' => '',
		'products' => '',
	);
	// allowed bars
	$allbars = widget_bar_available();
	//$bars = array_merge($allbars['admin'], $allbars['public']);
	// we search for first PHP comment
	$commentpattern = '~\/\*(.*)\*\/~sU';
	// fetch all widget folders
	$widget_folders = array(
		adesk_base('widgets'),
		//adesk_base('plugins'),
	);
	$folders = array();
	foreach ( $widget_folders as $folder ) {
		adesk_dir_list_r($folder, $folders);
	}
	foreach ( $folders as $folder ) {
		// find widget name
		$widget = basename($folder);
		// if widget is already loaded
		if ( isset($widgets[$widget]) ) continue;
		// this is the widget file we're looking for
		$file = "$folder/$widget.php";
		// no file, not a widget
		if ( !file_exists($file) ) continue;
		// open the widget file to get info
		$fp = @fopen($file, 'rb');
		if ( !$fp ) continue;
		// keep grabbing file contents til you see the end of the comment
		$comment = '';
		while ( !feof($fp) and !preg_match($commentpattern, $comment, $matches) ) {
			$comment .= fread($fp, 1024);
		}
		@fclose($fp);
		if ( !preg_match($commentpattern, $comment, $matches) ) continue;
		// parse the comment
		$str = trim($matches[1]);
		$lines = preg_split('/\r?\n/', $str);
		// make the configuration array
		$config = $cfg;
		// loop through all lines found in the comment
		foreach ( $lines as $line ) {
			// break key/value pairs
			$pairs = explode(":", $line, 2);
			if ( count($pairs) < 2 ) continue;
			$key = trim($pairs[0]);
			$val = trim($pairs[1]);
			$key = strtolower(preg_replace('/[_\-\s]/', '', $key));
			switch ( $key ) {
				case 'section':
					$val = strtolower($val);
					if ( in_array($val, array('admin', 'public', 'both')) ) $config['section'] = $val;
					break;
				case 'version':
					$config['version'] = $val;
					break;
				case 'widgetname':
					$config['name'] = $val;
					break;
				case 'widgeturl':
					$config['url'] = $val;
					break;
				case 'description':
					if ( $config['description'] ) $config['description'] .= "\n";
					$config['description'] .= $val;
					break;
				case 'author':
					$config['author'] = $val;
					break;
				case 'authorurl':
					$config['homepage'] = $val;
					break;
				case 'license':
					$config['license'] = $val;
					break;
				case 'usein':
					$config['usein'] = preg_replace('/\s+/', '', $val);
					break;
				case 'products':
					$config['products'] = preg_replace('/\s+/', '', $val);
					break;
				default:
					break;
			}
		}
		// if widget doesn't have the very basic description, skip it
		if ( !$config['section'] or !$config['name'] or !$config['version'] ) {
			continue;
		}
		$config['widget'] = $widget;
		if ( !widget_load($widget) ) continue;
		// check products
		if ( $config['products'] ) {
			// can use in some, intersect with defaults
			$products = explode(',', $config['products']);
			if ( adesk_site_isAEM5() and !in_array('em', $products) ) continue;
			if ( adesk_site_isisalient() and !in_array('sv', $products) ) continue;
			if ( adesk_site_isknowledgebuilder() and !in_array('kb', $products) ) continue;
			if ( adesk_site_issupporttrio3() and !in_array('hd', $products) ) continue;
			if ( adesk_site_isvisualedit() and !in_array('ve', $products) ) continue;
		}
		// check bars
		if ( !$config['usein'] ) {
			// can use in all, copy defaults
			$config['bars'] = $allbars;
			if ( $config['section'] == 'admin' ) $config['bars']['public'] = array();
			if ( $config['section'] == 'public' ) $config['bars']['admin'] = array();
		} else {
			// can use in some, intersect with defaults
			$usein = explode(',', $config['usein']);
			$config['bars'] = array('admin' => array(), 'public' => array());
			// admin
			if ( $config['section'] != 'public' ) {
				foreach ( $usein as $v ) {
					if ( isset($allbars['admin'][$v]) ) $config['bars']['admin'][$v] = $allbars['admin'][$v];
				}
			}
			// public
			if ( $config['section'] != 'admin' ) {
				foreach ( $usein as $v ) {
					if ( isset($allbars['public'][$v]) ) $config['bars']['public'][$v] = $allbars['public'][$v];
				}
			}
		}
		if ( !count($config['bars']['admin']) and !count($config['bars']['public']) ) continue;
		// this widget is valid!
		$widgets[$widget] = $config;
	}
	return $widgets;
}

function widget_load($widget) {
	$file = adesk_base();
	if ( adesk_site_isknowledgebuilder() and !adesk_site_isstandalone() ) $file = dirname($file);
	$file .= "/widgets/$widget/$widget.php";
	$class = widget_classname($widget);

	require_once(awebdesk_classes('widget.php'));
	if ( file_exists($file) ) require_once($file);
	if ( !class_exists($class) ) return false;
	return true;
}

//function widget_init() {}

function widget_install($widgetid, $section) {
	if ( $section != 'admin' ) $section = 'public';
	$r = array(
		'id' => 0,
		'widget' => $widgetid,
		'section' => $section,
		'title' => '',
	);

	if ( !adesk_admin_ismaingroup() ) {
		return adesk_ajax_api_result(false, _a("You do not have permission to do this."), $r);
	}

	$widgets = widget_available();
	if ( !isset($widgets[$widgetid]) ) {
		return adesk_ajax_api_result(false, _a("Widget not found."), $r);
	}

	$widget = $widgets[$widgetid];
	if ( $widget['section'] != 'both' and $widget['section'] != $section ) {
		return adesk_ajax_api_result(false, _a("Widget was not made to run in this section."), $r);
	}
	$r['title'] = $widget['name'];

	// add the widget
	$insert = array(
		'id' => 0,
		'section' => $section,
		'widget' => $widgetid,
		'title' => $widget['name'],
		'=bars' => 'NULL',
		'config' => serialize(array()),
		'sort_order' => 999,
	);
	$done = adesk_sql_insert("#widget", $insert);
	if ( !$done ) {
		return adesk_ajax_api_result(false, _a("Widget was not made to run in this section."), $r);
	}
	$r['id'] = $id = $insert['id'] = (int)adesk_sql_insert_id();

	// install script
	unset($insert['=bars']);
	$insert['bars'] = '';
	if ( widget_load($insert['widget']) ) {
		$class = widget_classname($insert['widget']);
		$obj = new $class($insert);
		if ( $obj ) {
			$done = $obj->installWidget();
			if ( !$done ) {
				return adesk_ajax_api_result(false, _a("Widget Installer failed to execute properly."), $r);
			}
		}
	}

	return adesk_ajax_api_result(true, _a("Widget installed."), $r);
}

function widget_uninstallall($section) {
	if ( $section != 'admin' ) $section = 'public';
	$r = array(
		'section' => $section,
	);

	if ( !adesk_admin_ismaingroup() ) {
		return adesk_ajax_api_result(false, _a("You do not have permission to do this."), $r);
	}

	// uninstall script
	$widgets = adesk_sql_select_array("SELECT * FROM #widget WHERE `section` = '$section'");
	foreach ( $widgets as $widget ) {
		if ( widget_load($widget['widget']) ) {
			$class = widget_classname($widget['widget']);
			$obj = new $class($widget);
			if ( $obj ) {
				$done = $obj->uninstallWidget();
				if ( !$done ) {
					return adesk_ajax_api_result(false, _a("Widget Uninstaller failed to execute properly."), $r);
				}
			}
		}
	}


	// remove all widgets from this section
	adesk_sql_delete("#widget", "`section` = '$section'");

	return adesk_ajax_api_result(true, _a("All widgets have been uninstalled."), $r);
}

function widget_uninstall($id, $section) {
	if ( $section != 'admin' ) $section = 'public';
	$r = array(
		'id' => $id,
		'section' => $section,
	);

	if ( !adesk_admin_ismaingroup() ) {
		return adesk_ajax_api_result(false, _a("You do not have permission to do this."), $r);
	}

	// 2do: uninstall script
	$widgets = adesk_sql_select_array("SELECT * FROM #widget WHERE `id` = '$id' AND `section` = '$section'");
	foreach ( $widgets as $widget ) {
		if ( widget_load($widget['widget']) ) {
			$class = widget_classname($widget['widget']);
			$obj = new $class($widget);
			if ( $obj ) {
				$done = $obj->uninstallWidget();
				if ( !$done ) {
					return adesk_ajax_api_result(false, _a("Widget Uninstaller failed to execute properly."), $r);
				}
			}
		}
	}

	// remove this widget from this section
	adesk_sql_delete("#widget", "`id` = '$id' AND `section` = '$section'");

	return adesk_ajax_api_result(true, _a("Widget uninstalled."), $r);
}

function widget_sort($section, $ids, $orders) {
	if ( $section != 'admin' ) $section = 'public';
	$r = array(
		'succeeded' => 0,
		'name' => 'order',
		'ids' => $ids,
		'orders' => $orders,
		'section' => $section,
	);

	if ( !adesk_admin_ismaingroup() ) {
		return adesk_ajax_api_result(false, _a("You do not have permission to do this."), $r);
	}

	$ary_ids    = explode(',', $ids);
	$ary_orders = explode(',', $orders);
	if ( count($ary_ids) != count($ary_orders) ) {
		return adesk_ajax_api_result(false, _a("The ids and order numbers do not match."), $r);
	}
	for ( $i = 0; $i < count($ary_ids); $i++ ) {
		$id     = (int)$ary_ids[$i];
		$r['succeeded'] = adesk_sql_update_one("#widget", "sort_order", (int)$ary_orders[$i], "`id` = '$id' AND `section` = '$section'");
	}
	return adesk_ajax_api_result(true, _a("Widget list updated."), $r);
}

function widget_options($id) {
	// define result
	$id = (int)$id;
	$r = array(
		'id' => $id,
		'html' => '',
	);

	// fetch the widget
	$widget = adesk_sql_select_row("SELECT * FROM #widget WHERE `id` = '$id'");
	if ( !$widget ) {
		return adesk_ajax_api_result(false, _a("Widget not found."), $r);
	}

	if ( widget_load($widget['widget']) ) {
		$class = widget_classname($widget['widget']);
		$obj = new $class($widget);
		if ( $obj ) {
			$r['html'] = (string)$obj->getForm();
		}
	}

	return adesk_ajax_api_result(true, _a("Widget Options fetched."), $r);
}

function widget_save() {
	// define result
	$id = (int)adesk_http_param('id');
	$r = array(
		'id' => $id,
	);

	// fetch the widget
	$widget = adesk_sql_select_row("SELECT * FROM #widget WHERE `id` = '$id'");
	if ( !$widget ) {
		return adesk_ajax_api_result(false, _a("Widget not found."), $r);
	}

	// load the widget
	if ( !widget_load($widget['widget']) ) {
		return adesk_ajax_api_result(false, _a("Widget not loaded."), $r);
	}
	$class = widget_classname($widget['widget']);
	$obj = new $class($widget);
	if ( !$obj ) {
		return adesk_ajax_api_result(false, _a("Widget object not loaded."), $r);
	}

	// save it
	if ( !$obj->saveWidget() ) {
		return adesk_ajax_api_result(false, _a("Widget could not be saved."), $r);
	}
	return adesk_ajax_api_result(true, _a("Widget Saved."), $r);
}

function widget_classname($widget) {
	return 'adesk_Widget_' . preg_replace('/[^a-zA-Z0-9_]/', '', str_replace('-', '_', $widget));
}

function widget_show($widget) {
	$r = '';

	if ( widget_load($widget['widget']) ) {
		$class = widget_classname($widget['widget']);
		$obj = new $class($widget);
		if ( $obj ) {
			$str = (string)$obj->showWidget();
			if ( $str ) {
				if ( $obj->display ) {
					$r .= '<div id="widget_' . $obj->id . '" class="side_box_border"><div class="side_box">';
					if ( $obj->title ) $r .= '<div class="side_box_header">' . $obj->title . '</div>';
					$r .= $str;
					$r .= '</div></div>';
				} else {
					$r .= $str;
				}
			}
		}
	}

	return $r;
}

function widget_bar_available() {
	// allowed bars
	$allbars = adesk_ihook('adesk_widget_bars');
	if ( !is_array($allbars) or !count($allbars) ) {
		return array('admin' => array(), 'public' => array());
	}
	if ( !isset($allbars['admin']) and !isset($allbars['public']) ) {
		$allbars = array('admin' => $allbars);
	}
	if ( !isset($allbars['admin']) ) $allbars['admin'] = array();
	if ( !isset($allbars['public']) ) $allbars['public'] = array();
	return $allbars;
}

function widget_bar_get($bar, $section) {
	$baresc = adesk_sql_escape($bar);
	$sectionesc = adesk_sql_escape($section);
	$cnt = adesk_sql_select_one(
		"=COUNT(*)",
		"#widget",
		"( FIND_IN_SET('$baresc', `bars`) OR `bars` = '' OR `bars` IS NULL ) AND `section` = '$sectionesc'"
	);
	return $cnt ? $bar : '';
}

?>