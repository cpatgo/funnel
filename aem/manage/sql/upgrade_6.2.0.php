<?php 
/*v 6.2.0 upgrade release date:09/23/2012 */
require_once(dirname(dirname(__FILE__)) . '/functions/aem.php');
require_once adesk_admin("functions/template.php");
require_once awebdesk_functions("ajax.php");

// message templates import
$templates_import = import_files("template", "xml", array('book1.xml'));
$templates_import = import_files("template", "xml", array('boutique_right_sidebar.xml'));
$templates_import = import_files("template", "xml", array('colordirect_full_width.xml'));
$templates_import = import_files("template", "xml", array('cool_full_width.xml'));
$templates_import = import_files("template", "xml", array('cool2_left_sidebar.xml'));
$templates_import = import_files("template", "xml", array('foods1.xml'));
$templates_import = import_files("template", "xml", array('geometric_full_width.xml'));
$templates_import = import_files("template", "xml", array('greetings1.xml'));
$templates_import = import_files("template", "xml", array('helvetica_right_sidebar.xml'));
$templates_import = import_files("template", "xml", array('holidays1.xml'));
$templates_import = import_files("template", "xml", array('impact_full_width.xml'));
$templates_import = import_files("template", "xml", array('impact_left_sidebar.xml'));
$templates_import = import_files("template", "xml", array('impact_right_sidebar.xml'));
$templates_import = import_files("template", "xml", array('modern_full_width.xml'));
$templates_import = import_files("template", "xml", array('modern_left_width.xml'));
$templates_import = import_files("template", "xml", array('modern_right_sidebar.xml'));
$templates_import = import_files("template", "xml", array('natural_full_width.xml'));
$templates_import = import_files("template", "xml", array('oldornament_full_width.xml'));
$templates_import = import_files("template", "xml", array('orange1.xml'));
$templates_import = import_files("template", "xml", array('retrogreen_full_width.xml'));
$templates_import = import_files("template", "xml", array('spring_full_width.xml'));
$templates_import = import_files("template", "xml", array('survey1.xml'));
$templates_import = import_files("template", "xml", array('survey2.xml'));
$templates_import = import_files("template", "xml", array('tech_full_width.xml'));
$templates_import = import_files("template", "xml", array('typographic_full_width.xml'));
 

$tags = array(
    "travel" => 0,
    "sale" => 0,
    "technology" => 0,
    "corporate" => 0,
    "services" => 0,
    "education" => 0,
    "real estate" => 0,
    "holiday" => 0,
    "simple" => 0,
    "creative" => 0,
    "event" => 0,
    "entertainment" => 0,
  );

  // insert tags if they are not there already
  foreach ($tags as $tag => $id) {
    $id = (int)adesk_sql_select_one("SELECT id FROM #tag WHERE tag = '$tag'");
    if ($id) {
      $tags[$tag] = $id;
    }
    else {
      $insert = array(
        "tag" => $tag,
      );
      $sql = adesk_sql_insert("#tag", $insert);
      $tags[$tag] = adesk_sql_insert_id();
    }
  }

  $templates = adesk_sql_select_array("SELECT * FROM #template");

  foreach ($templates as $template) {

    $tags_to_add = array();

    switch ($template["name"]) {
      
	  case "book1" :
        $tags_to_add = array("services");
      break;
      case "boutique_right_sidebar" :
        $tags_to_add = array("sale");
      break;
      case "colordirect_full_width" :
        $tags_to_add = array("services");
      break;
	  case "cool_full_width" :
        $tags_to_add = array("technology");
      break;
	  case "cool2_left_sidebar" :
        $tags_to_add = array("technology");
      break;
	  case "foods1" :
        $tags_to_add = array("sale");
      break;
      case "geometric_full_width" :
        $tags_to_add = array("creative");
      break;
      case "greetings1" :
        $tags_to_add = array("holiday");
      break;
	  case "helvetica_right_sidebar" :
        $tags_to_add = array("creative");
      break;
	  case "holidays1" :
        $tags_to_add = array("holiday");
      break;
	  case "impact_full_width" :
        $tags_to_add = array("real estate");
      break;
      case "impact_left_sidebar" :
        $tags_to_add = array("real estate");
      break;
      case "impact_right_sidebar" :
        $tags_to_add = array("real estate");
      break;
	  case "modern_full_width" :
        $tags_to_add = array("corporate");
      break;
	  case "modern_left_sidebar" :
        $tags_to_add = array("corporate");
      break;
	  case "modern_right_sidebar" :
        $tags_to_add = array("corporate");
      break;
      case "natural_full_width" :
        $tags_to_add = array("creative");
      break;
      case "oldornament_full_width" :
        $tags_to_add = array("simple");
      break;
	  case "orange1" :
        $tags_to_add = array("simple");
      break;
	  case "retrogreen_full_width" :
        $tags_to_add = array("technology");
      break;
	  case "spring_full_width" :
        $tags_to_add = array("simple");
      break;
      case "survey1" :
        $tags_to_add = array("event");
      break;
      case "survey2" :
        $tags_to_add = array("event");
      break;
	  case "tech_full_width" :
        $tags_to_add = array("technology");
      break;
	  case "typographic_full_width" :
        $tags_to_add = array("corporate");
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