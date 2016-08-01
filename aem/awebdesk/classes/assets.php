<?php

# Class: adesk_assets
#
# assetss are used to determine the behavior of an action.  So if you have a URL with action=something in it, then what
# happens in reaction to that URL is determined by a assets that is derived from this class.
#
# All assetss here use secondary modifiers to tell it exactly what it should be doing.  These are defined by the URL
# parameter "mode"; so, for example, you may have action=something and mode=add in your URL, which tells us to use the
# "something" assets with the "add" mode.
#
# There is a list of modes that adesk_assets can handle, and what they generally mean
#
#   - *add*: to add a new item
#   - *edit*: to edit an existing item (conventionally by an "id" URL parameter)
#   - *delete*: to remove an existing item
#   - *view*: to view a list of existing items
#
# If no mode is given, *view* is assumed.  If an unrecognized mode is given, it is sent to the <catchall> function.

require_once awebdesk_classes("select.php");

class adesk_assets {
    var $ary       = array();
	var $admin     = array();
	var $site	   = array();
	var $subject   = "[unknown]";
	var $title	   = "[title]";				# Page title
    var $tpl_edit  = "noaccess.tpl.htm";
    var $tpl_view  = "noaccess.tpl.htm";
	var $tpl_side  = "";					# Side content template
    var $silent    = false;
    var $nocontent = false;
    var $error     = false;
    var $save_goes_back = true;
	var $so        = null;

	var $opt_chkadmin = true;

	function adesk_assets() {
		if (isset($GLOBALS["admin"]))
			$this->admin =& $GLOBALS["admin"];
		if (isset($GLOBALS["site"]))
			$this->site =& $GLOBALS["site"];
		$this->so = new adesk_Select;
	}

    function process(&$smarty) {
		return $this->run($smarty);
    }

    # Function: formProcess
    #
    # Conventionally, this function is used to create $this->ary from the contents of $_POST.

    function formProcess(&$smarty) {
    }

    # Function: handle
    #
    # Have the assets run the member function for the given mode.  If no mode is given, it's retrieved from either GET or
    # POST.  If no mode exists there, *view* is assumed.

    function handle(&$smarty, $mode = '') {
        if ($mode == '')
            $mode = adesk_http_param('mode');

		# Set the side content template and page title

		if ($this->tpl_side != "")
			$smarty->assign("side_content_template", $this->tpl_side);

		$smarty->assign("pageTitle", $this->title);

        # Default to "view" if no mode provided.

        if ($mode == false)
            $mode = 'view';

        switch ($mode) {
            case 'add':
                $this->add($smarty);
                if (!$this->nocontent)
                    $smarty->assign('content_template', $this->tpl_edit);
                break;

            case 'delete':
                if ($this->delete($smarty) && !$this->silent)
                    adesk_smarty_message_delete($smarty, $this->subject);
                $this->handle($smarty, 'view');
                break;

            case 'edit':
                $this->edit($smarty);
                if (!$this->nocontent)
                    $smarty->assign('content_template', $this->tpl_edit);
                break;

            case 'insert':
                $future = 'edit';
                if ($this->insert($smarty) && !$this->silent)
                    adesk_smarty_message_insert($smarty, $this->subject);
                else
                    $future = 'add';

                if ($future != 'add' && $this->save_goes_back)
                    $future = 'view';
                $this->handle($smarty, $future);
                break;

            case 'update':
                $future = 'edit';
				$goback = false;
                if ($this->update($smarty) && !$this->silent)
                    adesk_smarty_message_update($smarty, $this->subject);
                else
                    $goback = true;

                if (!$goback && $this->save_goes_back)
                    $future = 'view';
                $this->handle($smarty, $future);
                break;

            case 'view':
                $this->view($smarty);
                if (!$this->nocontent)
                    $smarty->assign('content_template', $this->tpl_view);
                break;

                # If this mode is unknown, send it to the catch-all function;
                # but it is up to that function to decide what happens with
                # the content template, and to obey $this->nocontent
                # and/or $this->silent.

            default:
                $this->catchall($smarty, $mode);
                break;
        }
    }

	function run(&$smarty) {
		if ($this->opt_chkadmin) {
			if (isset($GLOBALS["admin"]))
				$this->admin = $GLOBALS["admin"];
			else
				$this->admin = adesk_admin_get();

			if (!$this->admin)
				return adesk_smarty_noaccess($smarty);
		}

		adesk_smarty_submitted($smarty, $this);
		$this->handle($smarty);
		adesk_smarty_load_get($smarty);

		return true;
	}

	function set_content(&$smarty, $file) {
		$smarty->assign('content_template', $file);
	}

    function view(&$smarty) {
    }

    function delete(&$smarty) {
    }

    function add(&$smarty, $ignore_perm = false) {
    }

    function edit(&$smarty) {
    }

    function insert(&$smarty) {
    }

    function update(&$smarty) {
    }

    function catchall(&$smarty, $mode) {
    }
}

?>
