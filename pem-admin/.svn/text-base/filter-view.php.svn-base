<?php
/* ========================== FILE INFORMATION =================================
phxEventManager :: filter-view.php

Popup control activated from the view pages and used to filter event
information by category and/or location.
============================================================================= */
include_once "../pem-config.php";
include_once ABSPATH . "pem-settings.php";

$XAJAX_DIR = "../" . PEMINC . "/xajax/";
include_once $XAJAX_DIR . "xajax_core/xajax.inc.php";
$xajax = new xajax();

echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' . "\n";
echo '<html xmlns="http://www.w3.org/1999/xhtml">' . "\n";
echo '<head>' . "\n";
echo '<title>Filter View</title>' . "\n";
echo '<link rel="stylesheet" type="text/css" href="/pem-themes/ruby/css.php" />' . "\n";
echo '<!--[if IE]>' . "\n";
echo '<link rel="stylesheet" type="text/css" href="/pem-themes/ruby/iehacks.css" />' . "\n";
echo '<![endif]-->' . "\n";
echo '<script type="text/javascript" src="/pem-includes/interface.js"></script>' . "\n";
$xajax->printJavascript($sJsURI=$XAJAX_DIR);
echo '</head>' . "\n";
echo '<body style="margin:0 20px 20px 20px;">' . "\n";

echo '<div id="info">';
_e("Choose the information you wish to see using the options below and click the submit button to apply your changes.");
echo '</div>' . "\n";

pem_form_begin(array("nameid" => "submitform", "action" => $PHP_SELF));

// ===================== LOCATION LIST =============================
$current_spaces = pem_cache_get("current_spaces");
$current_spaces_all = pem_cache_get("current_spaces_all");

$security = "internal";
$source_type = ($security == "internal") ? "internal_scheduled" : "external_scheduled";

//get list of active fields
$fieldbehavior = pem_active_fields($source_type);

if (pem_cache_get("current_format") == "calendar" AND pem_cache_get("current_view") == "day")
{

}
else
{
   $jsargs = "";
   $allspaces = "";
   $where = array("status" => 1, $source_type => 1);
   $space_list = pem_get_rows("spaces", $where, "AND", "space_name");
   $area_spaces = "";
   $all_area_spaces = true;
   ob_start();
   if (is_array($space_list)) foreach ($space_list AS $this_space)
   {
      if (!$current_spaces_all AND (!is_array($current_spaces) OR !in_array($this_space["id"], $current_spaces)))
      {
         $all_area_spaces = false;
      }
   }
   for ($j = 0; $j < count($space_list); $j++)
   {
      if (count($space_list) > 10 AND $j == intval(count($space_list)/2)+1) echo '</div><div class="indentsm" style="float:left;">' . "\n";
      $jsargs .= $space_list[$j]["id"] . ", ";
      $allspaces .= "-" . $space_list[$j]["id"];
      $status = ($all_area_spaces) ? false : (!is_array($current_spaces) OR in_array($space_list[$j]["id"], $current_spaces)) ? true : false;
      pem_checkbox(array("name" => "spaces[" . $space_list[$j]["id"] . "]", "id" => "space" . $space_list[$j]["id"], "status" => $status, "style" => "float:left;", "onclick" => "unsetCheckbox('document.submitform.allspaces'); unsetAllFilters();"));
      pem_field_label(array("default" => $space_list[$j]["space_name"], "for" => "space" . $space_list[$j]["id"]));
      echo "<br />\n";
   }
   if (count($space_list) > 10) echo '</div>' . "\n";
   $area_spaces = ob_get_clean();
   if (!empty($area_spaces))
   {
      echo '<div class="filterlist">' . "\n";
      echo '<h5>' . __("Available Locations") . '</h5>' . "\n";
      if (count($space_list) > 10) echo '<div class="indentsm" style="float:left;">' . "\n";
      else echo '<div class="indentsm">' . "\n";
      pem_checkbox(array("name" => "spaces[" . $allspaces . "]", "id" => "allspaces", "status" => $all_area_spaces, "style" => "float:left;", "onclick" => "unsetSpaceFilters(" . substr($jsargs, 0, -2) . "); setAllFilters();"));
      pem_field_label(array("default" => __("All Locations"), "for" => "allspaces"));
      echo "<br />\n";
      echo $area_spaces . "\n";
      pem_hidden_input(array("nameid" => "totalspaces", "value" => $all_area_spaces));
      echo '</div>' . "\n";
   }
}

// ===================== EVENT CATEGORY LIST =============================
$current_cats = pem_cache_get("current_categories");
$cat_list = pem_get_rows("categories");

if (pem_user_authorized("View Unapproved")) $cat_list[] = Array("id" => 100, "category_name" => "Unapproved", "show_boxes" => 1, "status" => 1);
if (pem_user_authorized("View Cancelled")) $cat_list[] = Array("id" => 101, "category_name" => "Cancelled", "show_boxes" => 1, "status" => 1);
if (pem_user_authorized("View Private")) $cat_list[] = Array("id" => 102, "category_name" => "Private", "show_boxes" => 1, "status" => 1);

if (isset($cat_list))
{
   $cat_count = count($cat_list);
   echo '<div class="filterlist">' . "\n";
   echo '<h5>' . __("Event Categories") . '</h5>' . "\n";
   echo '<div class="indentsm">' . "\n";
   if ($cat_count > 10) echo '<div style="float:left; margin-right:10px;">' . "\n";
   for ($i = 0; $i < $cat_count; $i++)
   {
      if ($cat_list[$i]["status"] AND $cat_list[$i]["show_boxes"])
      {
         if (0 == $i) $onclick = "unsetCatFilters(" . ($cat_count - 3) . ");";
         else $onclick = "unsetCheckbox('document.submitform.category1');";
         if ($cat_count > 10 AND $i == intval($cat_count/2)+1) echo '</div><div style="float:left; margin-right:10px;">' . "\n";
         pem_checkbox(array("name" => "categories[" . $cat_list[$i]["id"] . "]", "id" => "category" . $cat_list[$i]["id"], "status" => in_array($cat_list[$i]["id"], $current_cats), "style" => "float:left;", "onclick" => $onclick));
         if ($cat_list[$i]["id"] == 100)
         {
            pem_field_label(array("default" => $cat_list[$i]["category_name"], "for" => "categories[" . $cat_list[$i]["id"] . "]", "class" => "unapproved"));
         }
         elseif ($cat_list[$i]["id"] == 101)
         {
            pem_field_label(array("default" => $cat_list[$i]["category_name"], "for" => "categories[" . $cat_list[$i]["id"] . "]", "class" => "cancelled"));
         }
         elseif ($cat_list[$i]["id"] == 102)
         {
            pem_field_label(array("default" => $cat_list[$i]["category_name"], "for" => "categories[" . $cat_list[$i]["id"] . "]", "class" => "private"));
         }
         else
         {
            pem_field_label(array("default" => $cat_list[$i]["category_name"], "for" => "categories[" . $cat_list[$i]["id"] . "]", "style" => "color:#" . $cat_list[$i]["category_color"] . ";"));
         }
         echo "<br />\n";
      }
   }
   if ($cat_count > 10) echo '</div>' . "\n";
   echo '</div>' . "\n";
   echo '</div>' . "\n";
}

echo '<br />';
pem_submit_filters("submitform", "cancel");
pem_form_end();

echo '</body>' . "\n";
echo '</html>';
?>