<?php
/* ========================== FILE INFORMATION ================================= 
phxEventManager :: supplies.php

============================================================================= */

$pagetitle = "Supplies Administration";
$navigation = "administration";
$page_access_requirement = "Manage Meta";
$cache_set = array("current_navigation" => "meta");
include_once "../pem-includes/header.php";

$showaddform = true;
if (isset($datasubmit))
{
   unset($error);
   // The error checks are done here with if and not switch because case(empty($x)) returns a false positive
   if (empty($supply_name)) $error[] = __("Supply Name cannot be empty.");

   switch (true)
   {
      case ($datasubmit == "new" AND isset($error)):
         $showaddform = false;
         pem_fieldset_begin(__("Add New Supply Option"));
         echo_form("", $supply_name, $internal_scheduled, $external_scheduled, $internal_unscheduled, $external_unscheduled, $status, "new", $error);
         pem_fieldset_end();
         break;
      case ($datasubmit == "new"):
         $data = array("supply_name" => $supply_name, "internal_scheduled" => $internal_scheduled, "external_scheduled" => $external_scheduled, "internal_unscheduled" => $internal_unscheduled, "external_unscheduled" => $external_unscheduled, "status" => "$status");
         $types = array("supply_name" => "text", "internal_scheduled" => "boolean", "external_scheduled" => "boolean", "internal_unscheduled" => "boolean", "external_unscheduled" => "boolean", "status" => "text");
         pem_add_row("supplies", $data, $types);
         echo '<p><b>' . __("Supply added.") . '</b></p>' . "\n";
         break;
      case ($datasubmit == "edit"):
         $showaddform = false;
         pem_fieldset_begin(__("Edit Supply"));
         echo '<p>' . __("Change the settings as desired and submit the form to save your changes.") . "</p>\n";
         $data = pem_get_row("id", $typeid, "supplies");
         echo_form($data["id"], $data["supply_name"], $data["internal_scheduled"], $data["external_scheduled"], $data["internal_unscheduled"], $data["external_unscheduled"], $data["status"], "update");
         pem_fieldset_end();
         break;
      case ($datasubmit == "update"):
         $data = array("supply_name" => $supply_name, "internal_scheduled" => $internal_scheduled, "external_scheduled" => $external_scheduled, "internal_unscheduled" => $internal_unscheduled, "external_unscheduled" => $external_unscheduled, "status" => "$status");
         $where = array("id" => $typeid);
         pem_update_row("supplies", $data, $where);
         echo '<p><b>' . __("Supply updated.") . '</b></p>' . "\n";
         break;
      case ($datasubmit == "activate"):
         $data = array("status" => "1");
         $where = array("id" => $typeid);
         pem_update_row("supplies", $data, $where);
         echo '<p><b>' . __("Supply activated globally.") . '</b></p>' . "\n";
         break;
      case ($datasubmit == "deactivate"):
         $data = array("status" => "0");
         $where = array("id" => $typeid);
         pem_update_row("supplies", $data, $where);
         echo '<p><b>' . __("Supply deactivated globally.") . '</b></p>' . "\n";
         break;
      case ($datasubmit == "delete"):
         $where = array("id" => $typeid);
         pem_delete_recycle("supplies", $where);
         echo '<p><b>' . __("Supply deleted to recycle.") . '</b></p>' . "\n";
         break;
   }
}

// list current items in the table
pem_fieldset_begin(__("Current Supplies"));
echo '<p>' . __("Supply availability is set for each event type independently.  This allows you to define specific supplies for each type, simplifying the profile creation and management process.") . "</p>\n";

$fields_header =  array(
        __("Internal<br />Calendar"),
        __("External<br />Calendar"),
        __("Internal<br />Side Box"),
        __("External<br />Side Box"),
);

echo '<table cellspacing="0" class="datalist">' . "\n";
echo '<tr>' . "\n";
echo '<th style="padding-top:0;"></th>' . "\n";
for ($i = 0; $i < count($fields_header); $i++)
{
   echo '<th style="padding-top:0;">' . $fields_header[$i] . '</th>' . "\n";
}
echo '</tr>' . "\n";

unset($where);
$where["status"] = array("!=", "2");
$list = pem_get_rows("supplies", $where);
for ($i = 0; $i < count($list); $i++)
{
   $row = ($i % 2) ? "row2" : "row1";
   echo_data($list[$i], $row);
}
echo '</table>' . "\n";
pem_fieldset_end();

// display add new form if edit not in progress
if ($showaddform)
{
   pem_fieldset_begin(__("Add New Supply Option"));
   echo '<p>' . __("Define a new supply resource that can be added to a profile and applied to areas and spaces.") . "</p>\n";
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
   pem_field_label(array("default" => $supply_name));
   pem_form_end();
   echo '</td><td>' . "\n";
   echo ($internal_scheduled) ? __("Yes") : __("No");
   echo '</td><td>' . "\n";
   echo ($external_scheduled) ? __("Yes") : __("No");
   echo '</td><td>' . "\n";
   echo ($internal_unscheduled) ? __("Yes") : __("No");
   echo '</td><td>' . "\n";
   echo ($external_unscheduled) ? __("Yes") : __("No");
   echo '</td><td class="controlbox">' . "\n";
   if ($status == "0") $controls[] = array("label" => __("Activate"), "onclick" => "action_submit('dataform" . $id . "', 'activate');");
   else $controls[] = array("label" => __("Deactivate"), "onclick" => "action_submit('dataform" . $id . "', 'deactivate');");
   $controls[] = array("label" => __("Edit"), "onclick" => "action_submit('dataform" . $id . "', 'edit');");
   $controls[] = array("label" => __("Delete"), "onclick" => "confirm_submit('dataform" . $id . "', 'delete', '" . $delete_confirm . "');");
   pem_controls($controls);
   echo '</td></tr>' . "\n";
} // END echo_data

function echo_form($id = "", $supply_name = "", $internal_scheduled = "", $external_scheduled = "", $internal_unscheduled = "", $external_unscheduled = "", $status = "", $mode = "new", $error = "")
{
   global $PHP_SELF;

   pem_error_list($error);

   pem_form_begin(array("nameid" => "submitform", "action" => $PHP_SELF, "class" => "supplyform"));
   pem_hidden_input(array("name" => "datasubmit", "value" => $mode));
   pem_hidden_input(array("name" => "typeid", "value" => $id));

   pem_field_label(array("default" => __("Supply Name:"), "for" => "supply_name", "style" => "width:auto;"));
   pem_text_input(array("nameid" => "supply_name", "value" => $supply_name, "size" => 30, "maxlength" => 50));
   pem_field_note(array("default" => $supply_name_note));
   pem_field_label(array("default" => __("Available for Internal Calendar:"), "for" => "internal_scheduled"));
   pem_boolean_select(array("nameid" => "internal_scheduled", "default" => $internal_scheduled));
   pem_field_note(array("default" => $internal_scheduled_note));
   pem_field_label(array("default" => __("Available for External Calendar:"), "for" => "external_scheduled"));
   pem_boolean_select(array("nameid" => "external_scheduled", "default" => $external_scheduled));
   pem_field_note(array("default" => $external_scheduled_note));
   pem_field_label(array("default" => __("Available for Internal Side Box:"), "for" => "internal_unscheduled"));
   pem_boolean_select(array("nameid" => "internal_unscheduled", "default" => $internal_unscheduled));
   pem_field_note(array("default" => $internal_unscheduled_note));
   pem_field_label(array("default" => __("Available for External Side Box:"), "for" => "external_unscheduled"));
   pem_boolean_select(array("nameid" => "external_unscheduled", "default" => $external_unscheduled));
   pem_field_note(array("default" => $external_unscheduled_note));
   pem_field_label(array("default" => __("Globaly Active:"), "for" => "status"));
   pem_boolean_select(array("nameid" => "status", "default" => $status));
   pem_field_note(array("default" => $status_note));

   if ($mode != "new") pem_form_update("submitform", "cancel");
   elseif ($mode == "new" AND !empty($error)) pem_form_submit("submitform", "cancel");
   else pem_form_submit("submitform");

   pem_form_end();
} // END echo_form

?>