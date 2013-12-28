<?php
/* ========================== FILE INFORMATION ================================= 
phxEventManager :: meta-select.php

============================================================================= */

$pagetitle = "Select Option Meta Administration";
$navigation = "administration";
$page_access_requirement = "Manage Meta";
$cache_set = array("current_navigation" => "meta");
include_once "../pem-includes/header.php";

$showaddform = true;
if (isset($datasubmit))
{
   unset($error);
   $hold_submit = false;
   // The error checks are done here with if and not switch because case(empty($x)) returns a false positive
   if (empty($meta_name)) $error[] = __("Meta Name cannot be empty.");
   if (empty($option_count)) $error[] = __("Please enter an Option Count to continue.");
   if (is_array($meta_options))
   {
      $empty_array = true;
      for ($i = 0; $i < count($meta_options); $i++)
      {
         if (!empty($meta_options[$i])) $empty_array = false;
      }
      if ($empty_array) $error[] = __("At least one option field must have content.");
   }
   if (substr($datasubmit, 0, 4) == "hold")
   {
      $datasubmit = substr($datasubmit, 5);
      $hold_submit = true;
   }

   switch(true)
   {
      case ($datasubmit == "new"):
         $showaddform = false;
         pem_fieldset_begin(__("Add New Select Options"));
         echo '<p>' . __("Complete the new select meta by entering the options in the fields below.") . "</p>\n";
         echo_form("", $meta_name, $meta_description, $meta_parent, $internal_scheduled, $external_scheduled, $internal_unscheduled, $external_unscheduled, $status, $option_count, "", $select_label, $select_note, "finish", $error);
         pem_fieldset_end();
         break;
      case ($datasubmit == "finish" AND (isset($error) or $hold_submit)):
         $showaddform = false;
         pem_fieldset_begin(__("Add New Select Options"));
         echo '<p>' . __("Complete the new select meta by entering the options in the fields below.") . "</p>\n";
         echo_form("", $meta_name, $meta_description, $meta_parent, $internal_scheduled, $external_scheduled, $internal_unscheduled, $external_unscheduled, $status, $option_count, $meta_options, $select_label, $select_note, "finish", $error);
         pem_fieldset_end();
         break;
      case ($datasubmit == "finish"):
         $value = array_trim($meta_options);
         sort($value);
         array_unshift($value, "null");
         unset($value[0]);
         $value["select_label"] = $select_label;
         $value["select_note"] = $select_note;
         $value = serialize($value);
         $data = array("meta_name" => $meta_name, "meta_description" => $meta_description, "meta_type" => "select", "meta_parent" => $meta_parent, "internal_scheduled" => $internal_scheduled, "external_scheduled" => $external_scheduled, "internal_unscheduled" => $internal_unscheduled, "external_unscheduled" => $external_unscheduled, "value" => $value, "status" => $status);
         $types = array("meta_name" => "text", "meta_description" => "text", "meta_type" => "text", "meta_parent" => "text", "internal_scheduled" => "integer", "external_scheduled" => "integer", "internal_unscheduled" => "integer", "external_unscheduled" => "integer", "value" => "clob", "status" => "text");
         $newid = pem_add_row("meta", $data, $types);
         $data = array("name" => "meta" . $newid, "parent" => $meta_parent, "internal_scheduled" => 99, "external_scheduled" => 99, "internal_unscheduled" => 99, "external_unscheduled" => 99);
         $types = array("name" => "text", "parent" => "text", "internal_scheduled" => "integer", "external_scheduled" => "integer", "internal_unscheduled" => "integer", "external_unscheduled" => "integer");
         pem_add_row("field_order", $data, $types);
         echo '<p><b>' . __("Select Option added.") . '</b></p>' . "\n";
         break;
      case ($datasubmit == "edit"):
         $showaddform = false;
         pem_fieldset_begin(__("Edit Select Option"));
         echo '<p>' . __("Change the information as desired and submit the form to save your changes.") . "</p>\n";
         $data = pem_get_row("id", $typeid, "meta");
         $meta_options = unserialize($data["value"]);
         $select_label = $meta_options["select_label"];
         $select_note = $meta_options["select_note"];
         unset($meta_options["select_label"]);
         unset($meta_options["select_note"]);
         $option_count = count($meta_options);
         echo_form($data["id"], $data["meta_name"], $data["meta_description"], $data["meta_parent"], $data["internal_scheduled"], $data["external_scheduled"], $data["internal_unscheduled"], $data["external_unscheduled"], $data["status"], $option_count, $meta_options, $select_label, $select_note, "update");
         pem_fieldset_end();
         break;
      case ($datasubmit == "update" AND (isset($error) or $hold_submit)):
         $showaddform = false;
         pem_fieldset_begin(__("Edit Select Option"));
         echo '<p>' . __("Change the information as desired and submit the form to save your changes.") . "</p>\n";
         echo_form($typeid, $meta_name, $meta_description, $meta_parent, $internal_scheduled, $external_scheduled, $internal_unscheduled, $external_unscheduled, $status, $option_count, $meta_options, $select_label, $select_note, "update", $error);
         pem_fieldset_end();
         break;
      case ($datasubmit == "update"):
         $value = array_trim($meta_options);
         sort($value);
         array_unshift($value, "null");
         unset($value[0]);
         $value["select_label"] = $select_label;
         $value["select_note"] = $select_note;
         $value = serialize($value);
         $data = array("meta_name" => $meta_name, "meta_description" => $meta_description, "meta_parent" => $meta_parent, "internal_scheduled" => $internal_scheduled, "external_scheduled" => $external_scheduled, "internal_unscheduled" => $internal_unscheduled, "external_unscheduled" => $external_unscheduled, "value" => $value, "status" => $status);
         $where = array("id" => $typeid);
         pem_update_row("meta", $data, $where);
         echo '<p><b>' . __("Select Option updated.") . '</b></p>' . "\n";
         break;
      case ($datasubmit == "activate"):
         $data = array("status" => "1");
         $where = array("id" => $typeid);
         pem_update_row("meta", $data, $where);
         echo '<p><b>' . __("Select Option activated.") . '</b></p>' . "\n";
         break;
      case ($datasubmit == "deactivate"):
         $data = array("status" => "0");
         $where = array("id" => $typeid);
         pem_update_row("meta", $data, $where);
         echo '<p><b>' . __("Select Option deactivated.") . '</b></p>' . "\n";
         break;
      case ($datasubmit == "delete"):
         $where = array("id" => $typeid);
         pem_delete_recycle("meta", $where);
         $where = array("name" => "meta" . $typeid);
         pem_delete_perm("field_order", $where);
         echo '<p><b>' . __("Select Option deleted to recycle.") . '</b></p>' . "\n";
         break;
   }
}

// list current items in the table
pem_fieldset_begin(__("Current Select Option Meta"));
echo '<p>' . __("Select Option adds optional name, address, phone, and email fields to event forms.  They can provide additional descriptive information in views and be used as query options in dynamic reports.") . "</p>\n";

$fields_header =  array(
        __("Internal<br />Calendar"),
        __("External<br />Calendar"),
        __("Internal<br />Side Box"),
        __("External<br />Side Box"),
);

$where = array("meta_type" => "select");
$where["status"] = array("!=", "2");
$list = pem_get_rows("meta", $where);
ob_start();
for ($i = 0; $i < count($list); $i++)
{
   $row = ($i % 2) ? "row2" : "row1";
   echo_data($list[$i], $row);
}
$data = ob_get_clean();
if (!empty($data))
{
   echo '<table cellspacing="0" class="datalist">' . "\n";
   echo '<tr>' . "\n";
   echo '<th style="padding-top:0;"></th>' . "\n";
   for ($i = 0; $i < count($fields_header); $i++)
   {
      echo '<th style="padding-top:0;">' . $fields_header[$i] . '</th>' . "\n";
   }
   echo '</tr>' . "\n";
   echo $data;
   echo '</table>' . "\n";
}
else
{
   echo '<p style="font-weight:bold;">' . __("No items found.") . '</p>' . "\n";
}
pem_fieldset_end();

// display add new form if edit not in progress
if ($showaddform)
{
   pem_fieldset_begin(__("Add New Select Option Meta"));
   echo '<p>' . __("Define a new meta resource to expand event informaton options.") . "</p>\n";
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
   pem_field_label(array("default" => $meta_name));
   // if (!empty($meta_description)) echo '<br /><p class="indent" style="text-align:left;">' . $meta_description . '</p>' . "\n";
   pem_form_end();
   echo '</td><td>' . "\n";
   if ($internal_scheduled == 2) echo __("Required");
   else echo ($internal_scheduled == 1) ? __("Visible") : __("Inactive");
   echo '</td><td>' . "\n";
   if ($external_scheduled == 2) echo __("Required");
   else echo ($external_scheduled == 1) ? __("Visible") : __("Inactive");
   echo '</td><td>' . "\n";
   if ($internal_unscheduled == 2) echo __("Required");
   else echo ($internal_unscheduled == 1) ? __("Visible") : __("Inactive");
   echo '</td><td>' . "\n";
   if ($external_unscheduled == 2) echo __("Required");
   else echo ($external_unscheduled == 1) ? __("Visible") : __("Inactive");
   echo '</td><td class="controlbox">' . "\n";
   if ($status == "0") $controls[] = array("label" => __("Activate"), "onclick" => "action_submit('dataform" . $id . "', 'activate');");
   else $controls[] = array("label" => __("Deactivate"), "onclick" => "action_submit('dataform" . $id . "', 'deactivate');");
   $controls[] = array("label" => __("Edit"), "onclick" => "action_submit('dataform" . $id . "', 'edit');");
   $controls[] = array("label" => __("Delete"), "onclick" => "confirm_submit('dataform" . $id . "', 'delete', '" . $delete_confirm . "');");
   pem_controls($controls);
   echo '</td></tr>' . "\n";
} // END echo_data

function echo_form($id = "", $meta_name = "", $meta_description = "", $meta_parent = "", $internal_scheduled = "", $external_scheduled = "", $internal_unscheduled = "", $external_unscheduled = "", $status = "", $option_count = "", $meta = "", $select_label = "", $select_note = "", $mode = "new", $error = "")
{
   global $PHP_SELF;

   $meta_name_note = __("(The name of the meta item, used for administration)");
   $meta_description_note = __("(Describe this item for future reference, used for administration only)");
   $meta_parent_note = __("(Each item is associated with either the entry master section or the date instances tied to the entry)");
   $status_note = __("(This meta object must be active for the option to appear in forms and views)");
   $option_count_note = __("(Enter the number of options you want in the select)");
   $meta_options_note = __("(Empty option fields will be ignored. Options are alphabetized on submit)");
   $select_label_note = __("(This text will label the select field in submit and edit forms)");
   $select_note_note = __("(This text is displayed after the field to explain it to users)");

   pem_error_list($error);

   pem_form_begin(array("nameid" => "submitform", "action" => $PHP_SELF, "class" => "metaselectform"));
   pem_hidden_input(array("name" => "datasubmit", "value" => $mode));
   pem_hidden_input(array("name" => "typeid", "value" => $id));
   pem_field_label(array("default" => __("Meta Name:"), "for" => "meta_name"));
   pem_text_input(array("name" => "meta_name", "value" => $meta_name, "size" => 20, "maxlength" => 64));
   pem_field_note(array("default" => $meta_name_note));
   pem_field_label(array("default" => __("Description:"), "for" => "meta_description"));
   pem_textarea_input(array("nameid" => "meta_description", "default" => $meta_description, "style" => "width:400px; height:100px;"));
   pem_field_note(array("default" => $meta_description_note));
   pem_field_label(array("default" => __("Add To Section:"), "for" => "meta_parent"));
   pem_meta_parent_select(array("nameid" => "meta_parent", "default" => $meta_parent));
   pem_field_note(array("default" => $meta_parent_note));
   pem_field_label(array("default" => __("Internal Calendar Behavior:"), "for" => "internal_scheduled"));
   pem_field_behavior_select(array("nameid" => "internal_scheduled", "default" => $internal_scheduled));
   pem_field_note(array("default" => $internal_scheduled_note));
   pem_field_label(array("default" => __("External Calendar Behavior:"), "for" => "external_scheduled"));
   pem_field_behavior_select(array("nameid" => "external_scheduled", "default" => $external_scheduled));
   pem_field_note(array("default" => $external_scheduled_note));
   pem_field_label(array("default" => __("Internal Side Box Behavior:"), "for" => "internal_unscheduled"));
   pem_field_behavior_select(array("nameid" => "internal_unscheduled", "default" => $internal_unscheduled));
   pem_field_note(array("default" => $internal_unscheduled_note));
   pem_field_label(array("default" => __("External Side Box Behavior:"), "for" => "external_unscheduled"));
   pem_field_behavior_select(array("nameid" => "external_unscheduled", "default" => $external_unscheduled));
   pem_field_note(array("default" => $external_unscheduled_note));
   pem_field_label(array("default" => __("Active:"), "for" => "status"));
   pem_boolean_select(array("nameid" => "status", "default" => $status));
   pem_field_note(array("default" => $status_note));

   echo '<h3 style="margin-top:15px;">' . __("Meta Content") . '</h3>' . "\n";
   if ($mode == "new" OR empty($option_count))
   {
      pem_field_label(array("default" => __("Number of Options:"), "for" => "option_count", "style" => "width:auto;"));
      pem_text_input(array("nameid" => "option_count", "value" => $option_count, "size" => 2, "maxlength" => 2));
      pem_field_note(array("default" => $option_count_note));
      pem_field_label(array("default" => __("Select Label:"), "for" => "select_label", "style" => "width:auto;"));
      pem_text_input(array("nameid" => "select_label", "value" => $select_label, "size" => 30, "maxlength" => 40));
      pem_field_note(array("default" => $select_label_note));
      pem_field_label(array("default" => __("Select Note:"), "for" => "select_note", "style" => "width:auto;"));
      pem_text_input(array("nameid" => "select_note", "value" => $select_note, "size" => 30, "maxlength" => 40));
      pem_field_note(array("default" => $select_note_note));
      if (!empty($error)) pem_form_submit("submitform", "cancel");
      else pem_form_submit("submitform");
   }
   else
   {
      pem_hidden_input(array("name" => "option_count", "value" => $option_count));
      $raiseoptions = $option_count + 1;
      $control = array("label" => __("Add Option"), "onclick" => "count_submit('submitform', '" . $mode . "', " . $raiseoptions . ");");
      echo '<div style="width:180px; float:left; margin-right:2px;">' . "\n";
      pem_controls($control);
      echo '</div>' . "\n";
      pem_field_note(array("default" => $meta_options_note));
      for ($i = 1; $i <= $option_count; $i++)
      {
         $row = ($i % 2) ? "row2" : "row1";
         $value = (is_array($meta) AND array_key_exists($i, $meta)) ? $meta[$i] : "";
         pem_field_label(array("default" => sprintf(__("Option %s:"), $i), "for" => "options"));
         pem_text_input(array("name" => "meta_options[]", "value" => $value, "size" => 30, "maxlength" => 64));
         echo "<br />\n";
      }
      pem_field_label(array("default" => __("Select Label:"), "for" => "select_label", "style" => "width:auto;"));
      pem_text_input(array("nameid" => "select_label", "value" => $select_label, "size" => 30, "maxlength" => 40));
      pem_field_note(array("default" => $select_label_note));
      pem_field_label(array("default" => __("Select Note:"), "for" => "select_note", "style" => "width:auto;"));
      pem_text_input(array("nameid" => "select_note", "value" => $select_note, "size" => 30, "maxlength" => 40));
      pem_field_note(array("default" => $select_note_note));

      if ($mode == "finish")
      {
         pem_form_submit("submitform", "cancel");
      }
      else pem_form_update("submitform", "cancel");
   }
   pem_form_end();
} // END echo_form

?>