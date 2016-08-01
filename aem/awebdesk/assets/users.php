<?php

class Users_assets extends adesk_assets {
    function Users_assets() {
        $this->subject = _a("Admin User");
		$this->title   = _a("Admin Users");
        $this->tpl_view = 'users_view.tpl.htm';
        $this->tpl_edit = 'users_edit.tpl.htm';
        $this->admin = adesk_admin_get();
    }

    function process(&$smarty) {
        if (!adesk_admin_isadmin())
            return adesk_smarty_noaccess($smarty);

        adesk_ajax_dontrun();

        adesk_smarty_load_get($smarty);
        adesk_smarty_submitted($smarty, $this);

        if ($this->error) {
            $_GET["mode"] = "edit";
            $_GET["id"]   = $_POST["id"];
            $_SERVER["REQUEST_METHOD"] = "GET";
        }

        require_once awebdesk_api('manage/users.php');

        $this->handle($smarty);
        adesk_smarty_load_get($smarty);
    }

    function view(&$smarty) {
        $admin = $this->admin;
        require_once(awebdesk_api('manage/users.php'));

        $smarty->assign('show_add', false);
        $smarty->assign('show_edit', false);
        $smarty->assign('show_delete', false);

        adesk_ihook('adesk_users_assets_view', $smarty, $GLOBALS['admin']);

        if (isset($_SESSION['_view_show_edit'])) {
            $smarty->assign('show_edit', $_SESSION['_view_show_edit']);
            unset($_SESSION['_view_show_edit']);
        }
        if (isset($_SESSION['_view_show_delete'])) {
            $smarty->assign('show_delete', $_SESSION['_view_show_delete']);
            unset($_SESSION['_view_show_delete']);
        }
        if (isset($_SESSION['_view_show_add'])) {
            $smarty->assign('show_add', $_SESSION['_view_show_add']);
            unset($_SESSION['_view_show_add']);
        }

        $smarty->assign('content_template', 'users_view.tpl.htm');
        $smarty->assign('users', users_select('list', adesk_admin_parent_of($admin)));

        if (isset($GLOBALS['show_global']))
            $smarty->assign('global', users_select('list_global', 0));
    }

    function delete(&$smarty) {
        if (adesk_http_param('id')) {
            $ret = users_delete(adesk_http_param('id'));
        }
        adesk_smarty_message_delete($smarty, $this->subject);
        adesk_smarty_redirect($smarty, "desk.php?action=admins");
    }

    function add(&$smarty) {
        if (isset($GLOBALS['over_user_limit']) && $GLOBALS['over_user_limit']) {
            adesk_smarty_message($smarty, _a("Sorry!  Your action could not be completed because you have reached your user limit"));
            adesk_smarty_redirect($smarty, "desk.php?action=admins");
        }
		$adminid = 0;
		$absid   = intval(adesk_http_param("id"));

		if ($absid > 0)
			$adminid = adesk_sql_select_one("SELECT id FROM #admin WHERE absid = '$absid'");

        $smarty = adesk_ihook('adesk_users_assets_addedit', $smarty, $adminid);
        $smarty->assign('content_template', 'users_edit.tpl.htm');
    }

    function edit(&$smarty) {
        if (adesk_http_param('id')) {
            require_once awebdesk_api('manage/users.php');
            $smarty->assign('user', users_select('one', adesk_admin_parent_of($this->admin), adesk_http_param('id')));
        }

        $this->add($smarty);
    }

    function update(&$smarty) {
        if (isset($_POST['id'])) {
            $absid = intval($_POST['id']);

			adesk_auth_update($this->ary, $absid);
            $adminid = adesk_sql_select_one("id", "#admin", "`absid` = '$absid'");
            users_update($absid, -1);
            adesk_ihook('adesk_users_assets_update', $adminid);

            adesk_smarty_message_update($smarty, $this->subject);
        }

        adesk_smarty_redirect($smarty, "desk.php?action=admins");
    }

    function insert(&$smarty) {
        if (isset($GLOBALS['over_user_limit']) && $GLOBALS['over_user_limit']) {
            adesk_smarty_message($smarty, _a("Sorry!  Your action could not be completed because you have reached your user limit"));
            adesk_smarty_redirect($smarty, "desk.php?action=admins");
        }

        if (isset($GLOBALS['admin']['acctid']))
            $acctid = intval($GLOBALS['admin']['acctid']);
        else
            $acctid = 0;

        if ($acctid > 0)
            $this->ary["acctid"] = $acctid;

		$id = adesk_auth_create($this->ary["username"], $this->ary["password"], $this->ary["first_name"], $this->ary["last_name"], $this->ary["email"]);
        users_import($id);

        adesk_smarty_message_insert($smarty, $this->subject);
        adesk_smarty_redirect($smarty, "desk.php?action=admins");
    }

    function catchall(&$smarty, $mode) {
        switch ($mode) {
            case 'import':
                if (isset($GLOBALS['over_user_limit']) && $GLOBALS['over_user_limit']) {
                    adesk_smarty_message($smarty, _a("Sorry!  Your action could not be completed because you have reached your user limit"));
                    adesk_smarty_redirect($smarty, "desk.php?action=admins");
                }

                if (adesk_http_param('id')) {
                    $ret = users_import(adesk_http_param('id'));
                    adesk_smarty_message_insert($smarty, $this->subject);
                }

                adesk_smarty_redirect($smarty, "desk.php?action=admins");
                break;

            default:
                break;
        }
    }

    function formProcess(&$smarty) {
        if (!isset($_POST["mode"]))
            return true;

        $this->ary = array(
            "password"      => $_POST["password"],
            "first_name"    => $_POST["first_name"],
            "last_name"     => $_POST["last_name"],
            "email"         => $_POST["email"],
        );

        if (isset($_POST["username"]))
            $this->ary["username"] = $_POST["username"];

        if (function_exists('ishosted') && ishosted()) {
            $hostedid = $GLOBALS['admin']['acctid'];
            if ($hostedid > 0)
                $this->ary["username"] = sprintf("%d_%s", $hostedid, $this->ary["username"]);
            else
                unset($this->ary["username"]);
        }

        if ($this->ary["password"] == "") {
			if (isset($_POST["id"])) {
				adesk_smarty_message($smarty, _a("You must enter a password"));
				$this->error = true;
				return true;
			}
            unset($this->ary["password"]);
        } elseif (!isset($_POST["password_repeat"]) || $_POST["password_repeat"] == "") {
            adesk_smarty_message($smarty, _a("You must retype your password"));
            $this->error = true;
            return true;
        } elseif ($this->ary["password"] != $_POST["password_repeat"]) {
            adesk_smarty_message($smarty, _a("Your password and repeated password do not match"));
            $this->error = true;
            return true;
        } else {
            $this->ary["password"] = md5($this->ary["password"]);
        }

        require_once awebdesk_functions('auth.php');

        if (!adesk_auth_isconnected())
            adesk_auth_connect();

        return true;
    }
}

?>
