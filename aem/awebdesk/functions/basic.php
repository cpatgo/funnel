<?php
// basic.php

// Include basic PHP files.

require_once dirname(__FILE__) . '/base.php';

require_once dirname(__FILE__) . '/manage.php';
require_once dirname(__FILE__) . '/array.php';
require_once dirname(__FILE__) . '/auth.php';
require_once dirname(__FILE__) . '/b64.php';
require_once dirname(__FILE__) . '/cache.php';
require_once dirname(__FILE__) . '/custom_fields.php';
require_once dirname(__FILE__) . '/date.php'; // this one will call sql.php, so we're cool for escaping and such ;-)
require_once dirname(__FILE__) . '/file.php';
require_once dirname(__FILE__) . '/http.php';
require_once dirname(__FILE__) . '/i18n.php';
require_once dirname(__FILE__) . '/ihook.php';
require_once dirname(__FILE__) . '/interface.php';
require_once dirname(__FILE__) . '/lang.php';
require_once dirname(__FILE__) . '/php.php';
require_once dirname(__FILE__) . '/prefix.php';
require_once dirname(__FILE__) . '/session.php';
require_once dirname(__FILE__) . '/site.php';
require_once dirname(__FILE__) . '/str.php';
require_once dirname(__FILE__) . '/tz.php';
require_once dirname(__FILE__) . '/utf.php';

/*
 * Magic numbers that go across all products should be defined here.
 */
if ( !defined("adesk_GROUP_VISITOR") ) define("adesk_GROUP_VISITOR", 1);
if ( !defined("adesk_GROUP_USER"   ) ) define("adesk_GROUP_USER", 2);
if ( !defined("adesk_GROUP_ADMIN"  ) ) define("adesk_GROUP_ADMIN", 3);

/*
// doesn't (directly) include these files
require_once dirname(__FILE__) . '/ajax.php';
require_once dirname(__FILE__) . '/charts.php';
require_once dirname(__FILE__) . '/assets.php';
require_once dirname(__FILE__) . '/custom_fields.php';
require_once dirname(__FILE__) . '/hook.php';
require_once dirname(__FILE__) . '/interface.php';
require_once dirname(__FILE__) . '/mail.php';
require_once dirname(__FILE__) . '/money.php';
require_once dirname(__FILE__) . '/pagination.php';
require_once dirname(__FILE__) . '/process.php';
require_once dirname(__FILE__) . '/re.php';
require_once dirname(__FILE__) . '/smarty.php';
require_once dirname(__FILE__) . '/sql.php';
require_once dirname(__FILE__) . '/twit.php';
require_once dirname(__FILE__) . '/xml.php';
*/
?>
