<?php
/* ========================== FILE INFORMATION ================================= 
phxEventManager :: meta-checkbox.php

============================================================================= */

$pagetitle = "Checkbox Meta Administration";
$navigation = "administration";
$page_access_requirement = "Manage Meta";
$cache_set = array("current_navigation" => "meta");
include_once "../pem-includes/header.php";

$showaddform = true;
if (isset($datasubmit))
{
   unset($error);
   // The error checks are done here with if and not switch because case(empty($x)) returns a false positive
   if (empty($meta_name)) $error[] = __("Meta Name cannot be empty.");
   if (empty($box_text)) $error[] = __("Checkbox Text cannot be empty.");

   $value_prep["box_text"] = $box_text;
   $value_prep["yes_text"] = $yes_text;
   $value_prep["no_text"] = $no_text;
   $value_prep["box_note"] = $box_note;

   switch (true)
   {
      case ($datasubmit == "new" AND isset($error)):
         $showaddform = false;
         pem_fieldset_begin(__("Add New Checkbox Meta"));
         echo_form("", $meta_name, $meta_description, $meta_parent, $internal_scheduled, $external_scheduled, $internal_unscheduled, $external_unscheduled, $status, $value_prep, "new", $error);
         pem_fieldset_end();
         break;
      case ($datasubmit == "new"):
         $value = serialize($value_prep);
         $data = array("meta_name" => $meta_name, "meta_description" => $meta_description, "meta_type" => "checkbox", "meta_parent" => $meta_parent, "internal_scheduled" => $internal_scheduled, "external_scheduled" => $external_scheduled, "internal_unscheduled" => $internal_unscheduled, "external_unscheduled" => $external_unscheduled, "value" => $value, "status" => $status);
         $types = array("meta_name" => "text", "meta_description" => "text", "meta_type" => "text", "meta_parent" => "text", "internal_scheduled" => "integer", "external_scheduled" => "integer", "internal_unscheduled" => "integer", "external_unscheduled" => "integer", "value" => "clob", "status" => "text");
         $newid = pem_add_row("meta", $data, $types);
         $data = array("name" => "meta" . $newid, "parent" => $meta_parent, "internal_scheduled" => 99, "external_scheduled" => 99, "internal_unscheduled" => 99, "external_unscheduled" => 99);
         $types = array("name" => "text", "parent" => "text", "internal_scheduled" => "integer", "external_scheduled" => "integer", "internal_unscheduled" => "integer", "external_unscheduled" => "integer");
         pem_add_row("field_order", $data, $types);
         echo '<p><b>' . __("Checkbox added.") . '</b></p>' . "\n";
         break;
      case ($datasubmit == "edit"):
         $showaddform = false;
         pem_fieldset_begin(__("Edit Checkbox"));
         echo '<p>' . __("Change the information as desired and submit the form to save your changes.") . "</p>\n";
         $data = pem_get_row("id", $typeid, "meta");
         $value = unserialize($data["value"]);
         echo_form($data["id"], $data["meta_name"], $data["meta_description"], $data["meta_parent"], $data["internal_scheduled"], $data["external_scheduled"], $data["internal_unscheduled"], $data["external_unscheduled"], $data["status"], $value, "update");
         pem_fieldset_end();
         break;
      case ($datasubmit == "update" AND isset($error)):
         $showaddform = false;
         pem_fieldset_begin(__("Edit Checkbox"));
         echo_form($typeid, $meta_name, $meta_description, $meta_parent, $internal_scheduled, $external_scheduled, $internal_unscheduled, $external_unscheduled, $status, $value_prep, "update", $error);
         pem_fieldset_end();
         break;
      case ($datasubmit == "update"):
         $value = serialize($value_prep);
         $data = array("meta_name" => $meta_name, "meta_description" => $meta_description, "meta_parent" => $meta_parent, "internal_scheduled" => $internal_scheduled, "external_scheduled" => $external_scheduled, "internal_unscheduled" => $internal_unscheduled, "external_unscheduled" => $external_unscheduled, "value" => $value, "status" => $status);
         $where = array("id" => $typeid);
         pem_update_row("meta", $data, $where);
         echo '<p><b>' . __("Checkbox updated.") . '</b></p>' . "\n";
         break;
      case ($datasubmit == "activate"):
         $data = array("status" => "1");
         $where = array("id" => $typeid);
         pem_update_row("meta", $data, $where);
         echo '<p><b>' . __("Checkbox activated.") . '</b></p>' . "\n";
         break;
      case ($datasubmit == "deactivate"):
         $data = array("status" => "0");
         $where = array("id" => $typeid);
         pem_update_row("meta", $data, $where);
         echo '<p><b>' . __("Checkbox deactivated.") . '</b></p>' . "\n";
         break;
      case ($datasubmit == "delete"):
         $where = array("id" => $typeid);
         pem_delete_recycle("meta", $where);
         $where = array("name" => "meta" . $typeid);
         pem_delete_perm("field_order", $where);
         echo '<p><b>' . __("Checkbox deleted to recycle.") . '</b></p>' . "\n";
         break;
   }
}

// list current items in the table
pem_fieldset_begin(__("Current Checkbox Meta"));
echo '<p>' . __("Checkboxes add additional true/false field options to event forms.  They can provide additional descriptive information in views and be used as query options in dynamic reports.  There is no functional difference between this meta type and yes/no selects; both are provided for visual preference.") . "</p>\n";

$fields_header =  array(
        __("Internal<br />Calendar"),
        __("External<br />Calendar"),
        __("Internal<br />Side Box"),
        __("External<br />Side Box"),
);

$where = array("meta_type" => "checkbox");
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
   pem_fieldset_begin(__("Add New Checkbox Meta"));
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

function echo_form($id = "", $meta_name = "", $meta_description = "", $meta_parent = "", $internal_scheduled = "", $external_scheduled = "", $internal_unscheduled = "", $external_unscheduled = "", $status = "", $meta = "", $mode = "new", $error = "")
{
   global $PHP_SELF;
   if (!empty($meta)) extract($meta);
   $meta_name_note = __("(The name of the meta item, used for administration only)");
   $meta_description_note = __("(Describe this item for future reference, used for administration only)");
   $meta_parent_note = __("(Each item is associated with either the entry master section or the date instances tied to the entry)");
   $status_note = __("(This meta object must be active for the option to appear in forms and views)");
   $box_text_note = __("(This text will appear next to the checkbox in submit and edit forms)");
   $yes_text_note = __("(This text appears in event views and reports if the box is checked)");
   $no_text_note = __("(This text appears in event views and reports if the box is not checked)");
   $box_note_note = __("(This text is displayed after the field to explain it to users)");

   pem_error_list($error);

   pem_form_begin(array("nameid" => "submitform", "action" => $PHP_SELF, "class" => "metacheckform"));
   pem_hidden_input(array("name" => "datasubmit", "value" => $mode));
   pem_hidden_input(array("name" => "typeid", "value" => $id));
   pem_field_label(array("default" => __("Meta Name:"), "for" => "meta_name"));
   pem_text_input(array("nameid" => "meta_name", "value" => $meta_name, "size" => 20, "maxlength" => 64));
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
   pem_field_label(array("default" => __("Checkbox Text:"), "for" => "box_text"));
   pem_text_input(array("nameid" => "box_text", "value" => $box_text, "size" => 40, "maxlength" => 64));
   pem_field_note(array("default" => $box_text_note));
   pem_field_label(array("default" => __("Yes Text:"), "for" => "yes_text"));
   pem_textarea_input(array("nameid" => "yes_text", "default" => $yes_text, "style" => "width:400px; height:100px;"));
   pem_field_note(array("default" => $yes_text_note));
   pem_field_label(array("default" => __("No Text :"), "for" => "no_text"));
   pem_textarea_input(array("nameid" => "no_text", "default" => $no_text, "style" => "width:400px; height:100px;"));
   pem_field_note(array("default" => $no_text_note));
   pem_field_label(array("default" => __("Checkbox Note:"), "for" => "box_note", "style" => "width:auto;"));
   pem_text_input(array("nameid" => "box_note", "value" => $box_note, "size" => 30, "maxlength" => 40));
   pem_field_note(array("default" => $box_note_note));

   if ($mode != "new") pem_form_update("submitform", "cancel");
   elseif ($mode == "new" AND !empty($error)) pem_form_submit("submitform", "cancel");
   else pem_form_submit("submitform");

   pem_form_end();
} // END echo_form

?>