<?php
/**
 *   @copyright Copyright (c) 2007 Quality Unit s.r.o.
 *   @author Matej Kendera
 *   @since Version 4.0.0
 *
 *   Licensed under the Quality Unit, s.r.o. Standard End User License Agreement,
 *   Version 1.0 (the "License"); you may not use this file except in compliance
 *   with the License. You may obtain a copy of the License at
 *   http://www.qualityunit.com/licenses/license
 *
 */

require_once '../scripts/bootstrap.php';
Gpf_ModuleBase::getStaticPageContent(new Pap_Signup(), 'samples_tests.stpl', 'main_static_html_doc.stpl');
