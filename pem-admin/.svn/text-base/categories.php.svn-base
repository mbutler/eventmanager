<?php
/* ========================== FILE INFORMATION =================================
phxEventManager :: categories.php

============================================================================= */

$pagetitle = "Category Administration";
$navigation = "administration";
$page_access_requirement = "Manage Category";
$cache_set = array("current_navigation" => "meta");
include_once "../pem-includes/header.php";

$showaddform = true;
if (isset($datasubmit))
{
   unset($error);
   // The error checks are done here with if and not switch because case(empty($x)) returns a false positive
   if (empty($category_name)) $error[] = __("Category Name cannot be empty.");

   switch (true)
   {
      case ($datasubmit == "new" AND isset($error)):
         $showaddform = false;
         pem_fieldset_begin(__("Add New Category"));
         echo_form("", $category_name, $category_description, $category_color, $show_boxes, $internal_scheduled, $external_scheduled, $internal_unscheduled, $external_unscheduled, $status, "new", $error);
         pem_fieldset_end();
         break;
      case ($datasubmit == "new"):
         $value_prep["show_boxes"] = $show_boxes;
         $value = serialize($value_prep);
         $data = array("category_name" => $category_name, "category_description" => $category_description, "category_color" => $category_color, "show_boxes" => $show_boxes, "internal_scheduled" => $internal_scheduled, "external_scheduled" => $external_scheduled, "internal_unscheduled" => $internal_unscheduled, "external_unscheduled" => $external_unscheduled, "status" => "0");
         $types = array("category_name" => "text", "category_description" => "text", "category_color" => "text", "show_boxes" => "boolean", "internal_scheduled" => "boolean", "external_scheduled" => "boolean", "internal_unscheduled" => "boolean", "external_unscheduled" => "boolean", "status" => "text");
         pem_add_row("categories", $data, $types);
         echo '<p><b>' . __("Category added.") . '</b></p>' . "\n";
         break;
      case ($datasubmit == "edit"):
         $showaddform = false;
         pem_fieldset_begin(__("Edit Category"));
         echo '<p>' . __("Change the information as desired and submit the form to save your changes.") . "</p>\n";
         $data = pem_get_row("id", $typeid, "categories");
         $value = unserialize($data["value"]);
         echo_form($data["id"], $data["category_name"], $data["category_description"], $data["category_color"], $data["show_boxes"], $data["internal_scheduled"], $data["external_scheduled"], $data["internal_unscheduled"], $data["external_unscheduled"], "update");
         pem_fieldset_end();
         break;
      case ($datasubmit == "update" AND isset($error)):
         $showaddform = false;
         pem_fieldset_begin(__("Edit Category"));
         echo_form($typeid, $category_name, $category_description, $category_color, $show_boxes, $internal_scheduled, $external_scheduled, $internal_unscheduled, $external_unscheduled, "update", $error);
         pem_fieldset_end();
         break;
      case ($datasubmit == "update"):
         $value_prep["show_boxes"] = $show_boxes;
         $value = serialize($value_prep);
         $data = array("category_name" => $category_name, "category_description" => $category_description, "category_color" => $category_color, "show_boxes" => $show_boxes, "internal_scheduled" => $internal_scheduled, "external_scheduled" => $external_scheduled, "internal_unscheduled" => $internal_unscheduled, "external_unscheduled" => $external_unscheduled);
         $where = array("id" => $typeid);
         pem_update_row("categories", $data, $where);
         echo '<p><b>' . __("Category updated.") . '</b></p>' . "\n";
         break;
      case ($datasubmit == "activate"):
         $data = array("status" => "1");
         $where = array("id" => $typeid);
         pem_update_row("categories", $data, $where);
         echo '<p><b>' . __("Category activated.") . '</b></p>' . "\n";
         break;
      case ($datasubmit == "deactivate"):
         $data = array("status" => "0");
         $where = array("id" => $typeid);
         pem_update_row("categories", $data, $where);
         echo '<p><b>' . __("Category deactivated.") . '</b></p>' . "\n";
         break;
      case ($datasubmit == "delete"):
         $where = array("id" => $typeid);
         pem_delete_perm("categories", $where);
         echo '<p><b>' . __("Category deleted.") . '</b></p>' . "\n";
         break;
   }
}

// list current items in the table
pem_fieldset_begin(__("Current Categories "));
$list = pem_get_rows("categories");
ob_start();
for ($i = 0; $i < count($list); $i++)
{
   $row = ($i % 2) ? "row2" : "row1";
   echo_data($list[$i], $row);
}
$data = ob_get_clean();
if (!empty($data))
{
   echo '<table cellspacing="0" class="datalist" >' . "\n";
   echo $data;
   echo '</table>' . "\n";
}
else
{
   echo '<p>' . __("No items found.") . '</p>' . "\n";
}
pem_fieldset_end();

// display add new form if edit not in progress
if ($showaddform)
{
   pem_fieldset_begin(__("Add New Category"));
   echo '<p>' . __("Categories colorize event links in calendar and list views.") . "</p>\n";
   echo_form();
   pem_fieldset_end();
}

include ABSPATH . PEMINC . "/footer.php";


// =============================================================================
// =========================== LOCAL FUNCTIONS =================================
// =============================================================================

function echo_data($data, $row)
{
   global $delete_confirm;
   extract($data);

   echo '<tr class="' . $row . '"><td>' . "\n";
   pem_form_begin(array("nameid" => "dataform" . $id, "action" => $PHP_SELF));
   pem_hidden_input(array("name" => "datasubmit", "value" => "edit"));
   pem_hidden_input(array("name" => "typeid", "value" => $id));
   if ($id == 1) $category_name .= " " . __("(Default All)");
   pem_field_label(array("default" => $category_name, "style" => "color:#" . $category_color . ";"));
   if (!empty($category_description)) echo '<br /><p class="indent" style="text-align:left;">' . $category_description . '</p>' . "\n";
   pem_form_end();
   echo '</td><td class="controlbox">' . "\n";
   if ($id != 1)
   {
      if ($status == "0") $controls[] = array("label" => __("Activate"), "onclick" => "action_submit('dataform" . $id . "', 'activate');");
      else $controls[] = array("label" => __("Deactivate"), "onclick" => "action_submit('dataform" . $id . "', 'deactivate');");
   }
   $controls[] = array("label" => __("Edit"), "onclick" => "action_submit('dataform" . $id . "', 'edit');");
   if ($id != 1) $controls[] = array("label" => __("Delete"), "onclick" => "confirm_submit('dataform" . $id . "', 'delete', '" . $delete_confirm . "');");
   pem_controls($controls);
   echo '</td></tr>' . "\n";
} // END echo_data

function echo_form($id = "", $category_name = "", $category_description = "", $category_color = "", $show_boxes = "", $internal_scheduled = "", $external_scheduled = "", $internal_unscheduled = "", $external_unscheduled = "", $mode = "new", $error = "")
{
   global $PHP_SELF;
   $category_name_note = __("(The name of the category, used in form selects and filters)");
   $category_description_note = __("(Describe this category for future reference, used for administration only)");
   $category_color_note = __("(3 or 6-digit Hex format without leading #)");
   $internal_scheduled_note = __("(Select yes to make this an option in internal calendar event forms)");
   $external_scheduled_note = __("(Select yes to make this an option in external calendar event forms)");
   $internal_unscheduled_note = __("(Select yes to make this an option in internal side box event forms)");
   $external_unscheduled_note = __("(Select yes to make this an option in external side box event forms)");
   $show_boxes_note = __("(The caregories side box allows users to filter the current event view)");

   pem_error_list($error);

   pem_form_begin(array("nameid" => "submitform", "action" => $PHP_SELF, "class" => "categoryform"));
   pem_hidden_input(array("name" => "datasubmit", "value" => $mode));
   pem_hidden_input(array("name" => "typeid", "value" => $id));

   pem_field_label(array("default" => __("Category Name:"), "for" => "category_name"));
   pem_text_input(array("nameid" => "category_name", "value" => $category_name, "size" => 20, "maxlength" => 50));
   pem_field_note(array("default" => $category_name_note));

   pem_field_label(array("default" => __("Description:"), "for" => "category_description"));
   pem_textarea_input(array("nameid" => "category_description", "default" => $category_description, "style" => "width:400px; height:100px;"));
   pem_field_note(array("default" => $category_description_note));

   pem_field_label(array("default" => __("Color:"), "for" => "category_color"));
   pem_text_input(array("nameid" => "category_color", "value" => $category_color, "size" => 6, "maxlength" => 6));
   pem_field_note(array("default" => $category_color_note));

   echo '<h3>' . __("Category Availability") . '</h3>';
   echo '<div class="indent">' . "\n";
   pem_field_label(array("default" => __("Internal Calendar:"), "for" => "internal_scheduled"));
   pem_boolean_select(array("nameid" => "internal_scheduled", "default" => $internal_scheduled));
   pem_field_note(array("default" => $internal_scheduled_note));
   pem_field_label(array("default" => __("External Calendar:"), "for" => "external_scheduled"));
   pem_boolean_select(array("nameid" => "external_scheduled", "default" => $external_scheduled));
   pem_field_note(array("default" => $external_scheduled_note));
   pem_field_label(array("default" => __("Internal Side Box:"), "for" => "internal_unscheduled"));
   pem_boolean_select(array("nameid" => "internal_unscheduled", "default" => $internal_unscheduled));
   pem_field_note(array("default" => $internal_unscheduled_note));
   pem_field_label(array("default" => __("External Side Box:"), "for" => "external_unscheduled"));
   pem_boolean_select(array("nameid" => "external_unscheduled", "default" => $external_unscheduled));
   pem_field_note(array("default" => $external_unscheduled_note));
   pem_field_label(array("default" => __("Show in Side Boxes:"), "for" => "show_boxes"));
   pem_boolean_select(array("nameid" => "show_boxes", "default" => $show_boxes));
   pem_field_note(array("default" => $show_boxes_note));
   echo '</div>' . "\n";


   if ($mode != "new") pem_form_update("submitform", "cancel");
   elseif ($mode == "new" AND !empty($error)) pem_form_submit("submitform", "cancel");
   else pem_form_submit("submitform");

   pem_form_end();
} // END echo_form


?>