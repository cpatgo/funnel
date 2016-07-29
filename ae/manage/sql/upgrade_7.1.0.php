<?php 
/*v 7.1.0 upgrade release date:06/25/2014 */
require_once(dirname(dirname(__FILE__)) . '/functions/aem.php');
require_once adesk_admin("functions/template.php");
require_once awebdesk_functions("ajax.php");
//alter #user
adesk_sql_query("alter table #user add column `default_dashboard` varchar(250) NOT NULL DEFAULT 'modern' after lists_per_page");
adesk_sql_query("alter table #user add column `default_mobdashboard` varchar(250) NOT NULL DEFAULT 'modern' after default_dashboard");
//alter #list
adesk_sql_query("alter table #list add column `additional_owners` varchar(250) NOT NULL DEFAULT ''");
//update template db cache. not used any more :)
adesk_sql_query("update #branding set admin_template_htm='',admin_template_css='',public_template_htm='',public_template_css=''");
//add template tag responsive
adesk_sql_query("insert into #tag values(14,'responsive')");

//delete retarded templates first
adesk_sql_query("delete from #template where id in (2,3,10,12,13,14,19,20,27,28,29,33,36,41,44,45,49,50)");
adesk_sql_query("delete from #template_list where templateid in (2,3,10,12,13,14,19,20,27,28,29,33,36,41,44,45,49,50)");
adesk_sql_query("delete from #template_tag where templateid in (2,3,10,12,13,14,19,20,27,28,29,33,36,41,44,45,49,50)");


//insert new templates
 
$templates_import = import_files("template", "xml", array('c-orange-responsive-layout1.xml'));
$templates_import = import_files("template", "xml", array('c-orange-responsive-layout2.xml'));
$templates_import = import_files("template", "xml", array('c-orange-responsive-layout3.xml'));
$templates_import = import_files("template", "xml", array('c-orange-responsive-layout4.xml'));
$templates_import = import_files("template", "xml", array('c-orange-responsive-layout5.xml'));
$templates_import = import_files("template", "xml", array('c-orange-responsive-layout6.xml'));

$templates_import = import_files("template", "xml", array('e-blue-responsive-blue1.xml'));
$templates_import = import_files("template", "xml", array('e-blue-responsive-blue2.xml'));
$templates_import = import_files("template", "xml", array('e-blue-responsive-blue3.xml'));
$templates_import = import_files("template", "xml", array('e-blue-responsive-blue4.xml'));
$templates_import = import_files("template", "xml", array('e-blue-responsive-blue5.xml'));
$templates_import = import_files("template", "xml", array('e-blue-responsive-blue6.xml'));
$templates_import = import_files("template", "xml", array('e-blue-responsive-blue7.xml'));
$templates_import = import_files("template", "xml", array('e-blue-responsive-blue8.xml'));

 

$templates_import = import_files("template", "xml", array('e-orange-responsive-orange1.xml'));
$templates_import = import_files("template", "xml", array('e-orange-responsive-orange2.xml'));
$templates_import = import_files("template", "xml", array('e-orange-responsive-orange3.xml'));
$templates_import = import_files("template", "xml", array('e-orange-responsive-orange4.xml'));
$templates_import = import_files("template", "xml", array('e-orange-responsive-orange5.xml'));
$templates_import = import_files("template", "xml", array('e-orange-responsive-orange6.xml'));
$templates_import = import_files("template", "xml", array('e-orange-responsive-orange7.xml'));
$templates_import = import_files("template", "xml", array('e-orange-responsive-orange8.xml'));

 $templates = adesk_sql_select_array("SELECT * FROM #template");

  foreach ($templates as $template) {

    $tags_to_add = array();

    switch ($template["name"]) {
case "C Orange Responsive Layout1" :
case "C Orange Responsive Layout2" :
case "C Orange Responsive Layout3" :
case "C Orange Responsive Layout4" :
case "C Orange Responsive Layout5" :
case "C Orange Responsive Layout6" :
case "E Blue Responsive Blue1" :
case "E Blue Responsive Blue2" :
case "E Blue Responsive Blue3" :
case "E Blue Responsive Blue4" :
case "E Blue Responsive Blue5" :
case "E Blue Responsive Blue6" :
case "E Blue Responsive Blue7" :
case "E Blue Responsive Blue8" :
case "E Orange Responsive Orange1" :
case "E Orange Responsive Orange2" :
case "E Orange Responsive Orange3" :
case "E Orange Responsive Orange4" :
case "E Orange Responsive Orange5" :
case "E Orange Responsive Orange6" :
case "E Orange Responsive Orange7" :
case "E Orange Responsive Orange8" :
        $tags_to_add = array("responsive");
      break;
 
	        
	   
    }

    foreach ($tags_to_add as $tag) {
      $rel_exists = (int)adesk_sql_select_one("SELECT COUNT(*) FROM #template_tag WHERE templateid = '$template[id]' AND tagid = '$tags[$tag]'");
      if (!$rel_exists) {
        $insert = array(
          "templateid" => $template["id"],
          "tagid" => $tags[$tag],
        );
        $sql = adesk_sql_insert("#template_tag", $insert);
        $template_tag_id = adesk_sql_insert_id();
      }
    }
  }
 
 
?>