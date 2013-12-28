<?php
/* ========================== FILE INFORMATION ================================= 
phxEventManager :: presenter-types.php

============================================================================= */

$pagetitle = "Presenter Types Administration";
$navigation = "administration";
$page_access_requirement = "Manage Meta";
$cache_set = array("current_navigation" => "meta");
include_once "../pem-includes/header.php";

$showaddform = true;
if (isset($datasubmit))
{
   unset($error);
   // The error checks are done here with if and not switch because case(empty($x)) returns a false positive
   if (empty($presenter_type)) $error[] = __("Presenter Type cannot be empty.");

   switch (true)
   {
      case ($datasubmit == "new" AND isset($error)):
         $showaddform = false;
         pem_fieldset_begin(__("Add New Presenter Type"));
         echo_form("", $presenter_type, $internal_scheduled, $external_scheduled, $internal_unscheduled, $external_unscheduled, $status, "new", $error);
         pem_fieldset_end();
         break;
      case ($datasubmit == "new"):
         $data = array("presenter_type" => $presenter_type, "internal_scheduled" => $internal_scheduled, "external_scheduled" => $external_scheduled, "internal_unscheduled" => $internal_unscheduled, "external_unscheduled" => $external_unscheduled, "status" => 1);
         $types = array("presenter_type" => "text", "internal_scheduled" => "boolean", "external_scheduled" => "boolean", "internal_unscheduled" => "boolean", "external_unscheduled" => "boolean", "status" => "text");
         pem_add_row("presenters", $data, $types);
         echo '<p><b>' . __("Presenter Type added.") . '</b></p>' . "\n";
         break;
      case ($datasubmit == "edit"):
         $showaddform = false;
         pem_fieldset_begin(__("Edit Presenter Type"));
         echo '<p>' . __("Change the type as desired and submit the form to save your changes.") . "</p>\n";
         $data = pem_get_row("id", $typeid, "presenters");
         echo_form($data["id"], $data["presenter_type"], $data["internal_scheduled"], $data["external_scheduled"], $data["internal_unscheduled"], $data["external_unscheduled"], $data["status"], "update");
         pem_fieldset_end();
         break;
      case ($datasubmit == "update" AND isset($error)):
         $showaddform = false;
         pem_fieldset_begin(__("Edit Presenter Type"));
         echo_form($typeid, $presenter_type, $internal_scheduled, $external_scheduled, $internal_unscheduled, $external_unscheduled, $status, "update", $error);
         pem_fieldset_end();
         break;
      case ($datasubmit == "update"):
         $data = array("presenter_type" => $presenter_type, "internal_scheduled" => $internal_scheduled, "external_scheduled" => $external_scheduled, "internal_unscheduled" => $internal_unscheduled, "external_unscheduled" => $external_unscheduled, "status" => $status);
         $where = array("id" => $typeid);
         pem_update_row("presenters", $data, $where);
         echo '<p><b>' . __("Presenter Type updated.") . '</b></p>' . "\n";
         break;
      case ($datasubmit == "delete"):
         $where = array("id" => $typeid);
         pem_delete_perm("presenters", $where);
         echo '<p><b>' . __("Presenter Type deleted.") . '</b></p>' . "\n";
         break;
   }
}

// list current items in the table
pem_fieldset_begin(__("Current Presenter Types"));
$list = pem_get_rows("presenters");
echo '<table cellspacing="0" class="datalist" >' . "\n";
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
   pem_fieldset_begin(__("Add New Presenter Type"));
   echo '<p>' . __("Enter an additional title or label that can be applied to event presenters.  Presenter Types are presented as options using a drop-down select field.") . "</p>\n";
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
   pem_form_begin(array("nameid" => "presenterform" . $id, "action" => $PHP_SELF));
   pem_hidden_input(array("name" => "datasubmit", "value" => "edit"));
   pem_hidden_input(array("name" => "typeid", "value" => $id));
   pem_field_label(array("default" => $presenter_type));
   pem_form_end();
   echo '</td><td>' . "\n";
   $controls[] = array("label" => __("Edit"), "onclick" => "action_submit('presenterform" . $id . "', 'edit');");
   $controls[] = array("label" => __("Delete"), "onclick" => "confirm_submit('presenterform" . $id . "', 'delete', '" . $delete_confirm . "');");
   pem_controls($controls);
   echo '</td></tr>' . "\n";
} // END echo_data

function echo_form($id = "", $presenter_type = "", $internal_scheduled = "", $external_scheduled = "", $internal_unscheduled = "", $external_unscheduled = "", $status = "", $mode = "new", $error = "")
{
   global $PHP_SELF;
   extract(pem_get_rows("presenters"));
   $presenter_type_note = __('(Enter a label like "Presenter," "Instructor," or "Special Guest")');
   $internal_scheduled_note = __("(Select yes to make this an option in internal calendar event forms)");
   $external_scheduled_note = __("(Select yes to make this an option in external calendar event forms)");
   $internal_unscheduled_note = __("(Select yes to make this an option in internal side box event forms)");
   $external_unscheduled_note = __("(Select yes to make this an option in external side box event forms)");

   pem_error_list($error);

   pem_form_begin(array("nameid" => "submitform", "action" => $PHP_SELF, "class" => "presenterform"));
   pem_hidden_input(array("name" => "datasubmit", "value" => $mode));
   pem_hidden_input(array("name" => "typeid", "value" => $id));
   pem_field_label(array("default" => __("Type Name:"), "for" => "presenter_type"));
   pem_text_input(array("name" => "presenter_type", "value" => $presenter_type, "size" => 20, "maxlength" => 50));
   pem_field_note(array("default" => $presenter_type_note));

   echo '<h3>' . __("Presenter Type Availability") . '</h3>';
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
   echo '</div>' . "\n";

   if ($mode != "new") pem_form_update("submitform", "cancel");
   elseif ($mode == "new" AND !empty($error)) pem_form_submit("submitform", "cancel");
   else pem_form_submit("submitform");

   pem_form_end();
} // END echo_form

?>