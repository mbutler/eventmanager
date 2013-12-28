<?php
/* ========================== FILE INFORMATION =================================
phxEventManager :: meta-textinput.php

============================================================================= */

$pagetitle = "Text Input Meta Administration";
$navigation = "administration";
$page_access_requirement = "Manage Meta";
$cache_set = array("current_navigation" => "meta");
include_once "../pem-includes/header.php";

$showaddform = true;
if (isset($datasubmit))
{
   if (isset($custom_schedule))
   {
      $scheduling_profile = array("custom_schedule" => $custom_schedule);
      for ($i = 0; $i < 7; $i++)
      {
         $scheduling_profile["time_before_open_" . $i] = pem_time_quantity_to_real(array("hours" => ${"time_before_open_" . $i . "_hours"}, "minutes" => ${"time_before_open_" . $i . "_minutes"}));
         $scheduling_profile["time_after_closed_" . $i] = pem_time_quantity_to_real(array("hours" => ${"time_after_closed_" . $i . "_hours"}, "minutes" => ${"time_after_closed_" . $i . "_minutes"}));
         $scheduling_profile["start_before_closed_" . $i] = pem_time_quantity_to_real(array("hours" => ${"start_before_closed_" . $i . "_hours"}, "minutes" => ${"start_before_closed_" . $i . "_minutes"}));
      }
   }

   unset($error);
   // The error checks are done here with if and not switch because case(empty($x)) returns a false positive
   if ($datasubmit == "new" OR $datasubmit == "edit" OR $datasubmit == "update")
   {
      if (empty($area_name)) $error[] = __("Area Name cannot be empty.");
   }
   if ($datasubmit == "finishspace" OR $datasubmit == "editspace" OR $datasubmit == "updatespace")
   {
      if (empty($space_name)) $error[] = __("Space Name cannot be empty.");
      if (empty($space_name_short)) $error[] = __("Short Name cannot be empty.");
   }

   switch (true)
   {
      case ($datasubmit == "new" AND isset($error)):
         $showaddform = false;
         pem_fieldset_begin(__("Add New Area "));
         echo '<p>' . $error_instructions . "</p>\n";
         echo_form("", $area_name, $area_description, $area_popup, $area_contact, $status, "new", $error);
         pem_fieldset_end();
         break;
      case ($datasubmit == "new"):
         $data = array("area_name" => $area_name, "area_description" => $area_description, "area_popup" => $area_popup, "area_contact" => $area_contact, "status" => $status);
         $types = array("area_name" => "text", "area_description" => "text", "area_popup" => "text", "area_contact" => "text", "status" => "text");
         pem_add_row("areas", $data, $types);
         echo '<p><b>' . __("Area added.") . '</b></p>' . "\n";
         break;
      case ($datasubmit == "edit"):
         $showaddform = false;
         pem_fieldset_begin(__("Edit Area"));
         echo '<p>' . __("Change the information as desired and submit the form to save your changes.") . "</p>\n";
         $data = pem_get_row("id", $typeid, "areas");
         echo_form($data["id"], $data["area_name"], $data["area_description"], $data["area_popup"], $data["area_contact"], $data["status"], "update");
         pem_fieldset_end();
         break;
      case ($datasubmit == "update" AND isset($error)):
         $showaddform = false;
         pem_fieldset_begin(__("Edit Area"));
         echo '<p>' . $error_instructions . "</p>\n";
         echo_form($typeid, $area_name, $area_description, $area_popup, $area_contact, $status, "update", $error);
         pem_fieldset_end();
         break;
      case ($datasubmit == "update"):
         $data = array("area_name" => $area_name, "area_description" => $area_description, "area_popup" => $area_popup, "area_contact" => $area_contact, "status" => $status);
         $where = array("id" => $typeid);
         pem_update_row("areas", $data, $where);
         echo '<p><b>' . __("Area updated.") . '</b></p>' . "\n";
         break;
      case ($datasubmit == "activate"):
         $data = array("status" => "1");
         $where = array("id" => $typeid);
         pem_update_row("areas", $data, $where);
         echo '<p><b>' . __("Area activated.") . '</b></p>' . "\n";
         break;
      case ($datasubmit == "deactivate"):
         $data = array("status" => "0");
         $where = array("id" => $typeid);
         pem_update_row("areas", $data, $where);
         echo '<p><b>' . __("Area deactivated.  Please note this change only effects area availability for new events.") . '</b></p>' . "\n";
         break;
      case ($datasubmit == "delete"):
         $where = array("area" => $typeid);
         $where["status"] = array("!=", "2");
         $list = pem_get_rows("spaces", $where);
         for ($i = 0; $i < count($list); $i++)
         {
            $where = array("id" => $list[$i]["id"]);
            pem_delete_recycle("spaces", $where);
         }
         $where = array("id" => $typeid);
         pem_delete_recycle("areas", $where);
         if (count($list) > 0) echo '<p><b>' . __("Area and its spaces deleted to recycle.") . '</b></p>' . "\n";
         else echo '<p><b>' . __("Area deleted to recycle.") . '</b></p>' . "\n";
         break;
      case ($datasubmit == "newspace"):
         $showaddform = false;
         $data = pem_get_row("id", $typeid, "areas");
         pem_fieldset_begin(sprintf(__("Add Space for %s"), $data["area_name"]));
         echo '<p>' . __("Complete the information and submit the form to add the new space.") . "</p>\n";
         echo_space_form("", $space_name, $space_name_short, $space_description, $space_popup, $space_contact, $typeid, $supply_profile, $optional_supplies, $capacity, $show_boxes, $show_day_view, $scheduling_profile, $internal_scheduled, $external_scheduled, $internal_unscheduled, $external_unscheduled, $status, "finishspace");
         pem_fieldset_end();
         break;
      case ($datasubmit == "finishspace" AND isset($error)):
         $showaddform = false;
         $data = pem_get_row("id", $typeid, "areas");
         pem_fieldset_begin(sprintf(__("Add Space in %s"), $data["area_name"]));
         echo '<p>' . $error_instructions . "</p>\n";
         echo_space_form("", $space_name, $space_name_short, $space_description, $space_popup, $space_contact, $area, $supply_profile, $optional_supplies, $capacity, $show_boxes, $show_day_view, $scheduling_profile, $internal_scheduled, $external_scheduled, $internal_unscheduled, $external_unscheduled, $status, "finishspace", $error);
         pem_fieldset_end();
         break;
      case ($datasubmit == "finishspace"):
         $data = array("space_name" => $space_name, "space_name_short" => $space_name_short, "space_description" => $space_description, "space_popup" => $space_popup, "space_contact" => $space_contact, "area" => $area, "supply_profile" => $supply_profile, "optional_supplies" => $optional_supplies, "capacity" => $capacity, "show_boxes" => $show_boxes, "show_day_view" => $show_day_view, "scheduling_profile" => serialize($scheduling_profile), "internal_scheduled" => $internal_scheduled, "external_scheduled" => $external_scheduled, "internal_unscheduled" => $internal_unscheduled, "external_unscheduled" => $external_unscheduled, "status" => $status);
         $types = array("space_name" => "text", "space_name_short" => "text", "space_description" => "text", "space_popup" => "text", "space_contact" => "text", "area" => "integer", "supply_profile" => "integer", "optional_supplies" => "integer", "capacity" => "integer", "show_boxes" => "boolean", "show_day_view" => "boolean", "scheduling_profile" => "clob", "internal_scheduled" => "boolean", "external_scheduled" => "boolean", "internal_unscheduled" => "boolean", "external_unscheduled" => "boolean", "status" => "text");
         pem_add_row("spaces", $data, $types);
         echo '<p><b>' . __("Space added.") . '</b></p>' . "\n";
         break;
      case ($datasubmit == "editspace"):
         $showaddform = false;
         pem_fieldset_begin(__("Edit Space"));
         echo '<p>' . __("Change the information as desired and submit the form to save your changes.") . "</p>\n";
         $data = pem_get_row("id", $typeid, "spaces");
         echo_space_form($data["id"], $data["space_name"], $data["space_name_short"], $data["space_description"], $data["space_popup"], $data["space_contact"], $data["area"], $data["supply_profile"], $data["optional_supplies"], $data["capacity"], $data["show_boxes"], $data["show_day_view"], unserialize($data["scheduling_profile"]), $data["internal_scheduled"], $data["external_scheduled"], $data["internal_unscheduled"], $data["external_unscheduled"], $data["status"], "updatespace");
         pem_fieldset_end();
         break;
      case ($datasubmit == "updatespace" AND isset($error)):
         $showaddform = false;
         pem_fieldset_begin(__("Edit Space"));
         echo '<p>' . $error_instructions . "</p>\n";
         echo_space_form($typeid, $space_name, $space_name_short, $space_description, $space_popup, $space_contact, $area, $supply_profile, $optional_supplies, $capacity, $show_boxes, $show_day_view, $scheduling_profile, $internal_scheduled, $external_scheduled, $internal_unscheduled, $external_unscheduled, $status, "updatespace", $error);
         pem_fieldset_end();
         break;
      case ($datasubmit == "updatespace"):
         $data = array("space_name" => $space_name, "space_name_short" => $space_name_short, "space_description" => $space_description, "space_popup" => $space_popup, "space_contact" => $space_contact, "area" => $area, "supply_profile" => $supply_profile, "optional_supplies" => $optional_supplies, "capacity" => $capacity, "show_boxes" => $show_boxes, "show_day_view" => $show_day_view, "scheduling_profile" => serialize($scheduling_profile), "internal_scheduled" => $internal_scheduled, "external_scheduled" => $external_scheduled, "internal_unscheduled" => $internal_unscheduled, "external_unscheduled" => $external_unscheduled, "status" => $status);
         $where = array("id" => $typeid);
         pem_update_row("spaces", $data, $where);
         echo '<p><b>' . __("Space updated.") . '</b></p>' . "\n";
         break;
      case ($datasubmit == "movespace"):
         $showaddform = false;
         $data = pem_get_row("id", $typeid, "spaces");
         pem_fieldset_begin(sprintf(__("Move %s to New Area"), $data["space_name"]));
         echo '<p>' . __("Select the new area for this space and submit the form to complete the move.") . "</p>\n";
         echo_move_form($typeid, $area);
         pem_fieldset_end();
         break;
      case ($datasubmit == "finishmove"):
         $data = array("area" => $move_space_to);
         $where = array("id" => $typeid);
         pem_update_row("spaces", $data, $where);
         echo '<p><b>' . __("Space moved.") . '</b></p>' . "\n";
         break;
      case ($datasubmit == "activatespace"):
         $data = array("status" => "1");
         $where = array("id" => $typeid);
         pem_update_row("spaces", $data, $where);
         echo '<p><b>' . __("Space activated.") . '</b></p>' . "\n";
         break;
      case ($datasubmit == "deactivatespace"):
         $data = array("status" => "0");
         $where = array("id" => $typeid);
         pem_update_row("spaces", $data, $where);
         echo '<p><b>' . __("Space deactivated.  Please note this change only effects space availability for new events.") . '</b></p>' . "\n";
         break;
      case ($datasubmit == "deletespace"):
         $where = array("id" => $typeid);
         pem_delete_recycle("spaces", $where);
         echo '<p><b>' . __("Space deleted to recycle.") . '</b></p>' . "\n";
         break;
   }
}

// list current items in the table
unset($where);
$where["status"] = array("!=", "2");
$list = pem_get_rows("areas", $where, "AND", "area_name");
for ($i = 0; $i < count($list); $i++)
{
   echo_data($list[$i]);
}

// display add new form if edit not in progress
if ($showaddform)
{
   pem_fieldset_begin(__("Add New Area"));
   echo '<p>' . __("Areas manage spaces by grouping them under a single title.  Space options can be made available in events or views by area if desired.") . "</p>\n";
   echo_form();
   pem_fieldset_end();
}

include ABSPATH . PEMINC . "/footer.php";


// =============================================================================
// =========================== LOCAL FUNCTIONS =================================
// =============================================================================

function echo_data($data)
{
   global $delete_confirm;
   extract($data);

   pem_fieldset_begin($area_name);
   echo '<div class="fscontrols">' . "\n";
   $controls[] = array("label" => __("Add Space"), "onclick" => "action_submit('dataform" . $id . "', 'newspace');");
   if ($status == "0") $controls[] = array("label" => __("Activate"), "onclick" => "action_submit('dataform" . $id . "', 'activate');");
   else $controls[] = array("label" => __("Deactivate"), "onclick" => "action_submit('dataform" . $id . "', 'deactivate');");
   $controls[] = array("label" => __("Edit"), "onclick" => "action_submit('dataform" . $id . "', 'edit');");
   $controls[] = array("label" => __("Delete"), "onclick" => "confirm_submit('dataform" . $id . "', 'delete', '" . $delete_confirm . "');");
   pem_controls($controls);
   echo '</div>' . "\n";
   if (!empty($area_description)) echo '<p>' . $area_description . '</p>' . "\n";
   pem_form_begin(array("nameid" => "dataform" . $id, "action" => $PHP_SELF));
   pem_hidden_input(array("name" => "datasubmit", "value" => "edit"));
   pem_hidden_input(array("name" => "typeid", "value" => $id));
   pem_form_end();

   $table_header =  array(
           __("Internal<br />Calendar"),
           __("External<br />Calendar"),
           __("Internal<br />Side Box"),
           __("External<br />Side Box"),
   );

//   echo '<table cellspacing="0" class="datalist" >' . "\n";
   $where = array("area" => $id);
   $where["status"] = array("!=", "2");
   $list = pem_get_rows("spaces", $where, "AND", "space_name");
   ob_start();
   for ($i = 0; $i < count($list); $i++)
   {
      unset($controls);
      $row = ($i % 2) ? "row2" : "row1";
      echo '<tr class="' . $row . '"><td>' . "\n";
      pem_form_begin(array("nameid" => "spaceform" . $list[$i]["id"], "action" => $PHP_SELF));
      pem_hidden_input(array("name" => "datasubmit", "value" => "editspace"));
      pem_hidden_input(array("name" => "typeid", "value" => $list[$i]["id"]));
      pem_hidden_input(array("name" => "area", "value" => $id));
      pem_field_label(array("default" => $list[$i]["space_name"]));
      pem_form_end();
      echo '</td><td>';
      echo (empty($list[$i]["internal_scheduled"])) ? "No" : "Yes";
      echo '</td><td>';
      echo (empty($list[$i]["external_scheduled"])) ? "No" : "Yes";
      echo '</td><td>';
      echo (empty($list[$i]["internal_unscheduled"])) ? "No" : "Yes";
      echo '</td><td>';
      echo (empty($list[$i]["external_unscheduled"])) ? "No" : "Yes";
      echo '</td><td class="controlboxwide">' . "\n";
      if ($list[$i]["status"] == "0") $controls[] = array("label" => __("Activate"), "onclick" => "action_submit('spaceform" . $list[$i]["id"] . "', 'activatespace');");
      else $controls[] = array("label" => __("Deactivate"), "onclick" => "action_submit('spaceform" . $list[$i]["id"] . "', 'deactivatespace');");
      $controls[] = array("label" => __("Move Space"), "onclick" => "action_submit('spaceform" . $list[$i]["id"] . "', 'movespace');");
      $controls[] = array("label" => __("Edit"), "onclick" => "action_submit('spaceform" . $list[$i]["id"] . "', 'editspace');");
      $controls[] = array("label" => __("Delete"), "onclick" => "confirm_submit('spaceform" . $list[$i]["id"] . "', 'deletespace', '" . $delete_confirm . "');");
      pem_controls($controls);
      echo '</td></tr>' . "\n";
   }
//echo '</table>' . "\n";
   $space_data = ob_get_clean();
   if (!empty($space_data))
   {
      echo '<table cellspacing="0" class="datalist" >' . "\n";
      echo '<tr>' . "\n";
      echo '<th style="padding-top:0;"></th>' . "\n";
      for ($i = 0; $i < count($table_header); $i++)
      {
         echo '<th style="padding-top:0;">' . $table_header[$i] . '</th>' . "\n";
      }
      echo '</tr>' . "\n";
      echo $space_data;
      echo '</table>' . "\n";
   }

   pem_fieldset_end();
} // END echo_data

function echo_form($id = "", $area_name = "", $area_description = "", $area_popup = "", $area_contact = "", $status = "", $mode = "new", $error = "")
{
   global $PHP_SELF;
   $area_name_note = __("(The name used in forms and views to identify this area)");
   $area_description_note = __("(Describe this area for for administrative use)");
   $area_popup_note = __("(Create HTML, PHP, or text files in pem-content to publically describe the area)");
   $area_contact_note = __("(If entered, this email will be used for questions about the area.)");
   $status_note = __("(This area must be active for the option to appear in forms and views)");

   pem_error_list($error);

   pem_form_begin(array("nameid" => "submitform", "action" => $PHP_SELF, "class" => "areasform"));
   pem_hidden_input(array("name" => "datasubmit", "value" => $mode));
   pem_hidden_input(array("name" => "typeid", "value" => $id));
   pem_field_label(array("default" => __("Area Name:"), "for" => "area_name"));
   pem_text_input(array("nameid" => "area_name", "value" => $area_name, "size" => 20, "maxlength" => 64));
   pem_field_note(array("default" => $area_name_note));
   pem_field_label(array("default" => __("Area Description:"), "for" => "area_description"));
   pem_textarea_input(array("nameid" => "area_description", "default" => $area_description, "style" => "width:400px; height:100px;"));
   pem_field_note(array("default" => $area_description_note));

   pem_field_label(array("default" =>__("Area Popup:"), "for" => "area_popup"));
   pem_popup_select(array("name" => "area_popup", "default" =>$area_popup));
   pem_field_note(array("default" =>$area_popup_note));

   pem_field_label(array("default" => __("Contact:"), "for" => "area_contact"));
   pem_text_input(array("nameid" => "area_contact", "value" => $area_contact, "size" => 30, "maxlength" => 64));
   pem_field_note(array("default" => $area_contact_note));
   pem_field_label(array("default" => __("Active:"), "for" => "status"));
   pem_boolean_select(array("nameid" => "status", "default" => $status));
   pem_field_note(array("default" => $status_note));

   if ($mode != "new") pem_form_update("submitform", "cancel");
   elseif ($mode == "new" AND !empty($error)) pem_form_submit("submitform", "cancel");
   else pem_form_submit("submitform");

   pem_form_end();
} // END echo_form

function echo_space_form($id = "", $space_name = "", $space_name_short = "", $space_description = "", $space_popup = "", $space_contact = "", $area = "", $supply_profile = "", $optional_supplies = "", $capacity = "", $show_boxes = "", $show_day_view = "", $scheduling_profile = "", $internal_scheduled = "", $external_scheduled = "", $internal_unscheduled = "", $external_unscheduled = "", $status = "", $mode = "finishspace", $error = "")
{
   global $PHP_SELF, $weekday;
   $space_name_note = __("(The name used in forms and views to identify this space)");
   $space_name_short_note = __("(Version of the space name that can be used in needed areas like the day view)");
   $space_description_note = __("(Describe this space for administrative use)");
   $space_popup_note = __("(Create HTML, PHP, or text files in pem-content directory to publically describe the space)");
   $space_contact_note = __("(If entered, this email will be used for questions about the space.)");
   $supply_profile_note = __("(Select the supplies that are always available with this space)");
   $optional_supplies_note = __("(Optional supplies can be individually selected in addition to the default profile)");
   $capacity_note = __("(The maximum occupancy or units of storage, used for registrations)");
   $show_boxes_note = __("(Will list spaces in a location side box and allow event views by space)");
   $show_day_view_note = __("(Toggles display of this space as a column in the day view, requires visiblity in calendar views)");
   $internal_scheduled_note = __("(Select yes if this space is available for internal calendar events)");
   $external_scheduled_note = __("(Select yes if this space is available for external calendar events)");
   $internal_unscheduled_note = __("(Select yes if this space is available for internal side box events)");
   $external_unscheduled_note = __("(Select yes if this space is available for external side box events)");
   $status_note = __("(This space must be active for the option to appear in forms and views)");

   pem_error_list($error);

   pem_form_begin(array("nameid" => "submitform", "action" => $PHP_SELF, "class" => "spacesform"));
   pem_hidden_input(array("name" => "datasubmit", "value" => $mode));
   pem_hidden_input(array("name" => "typeid", "value" => $id));
   pem_hidden_input(array("name" => "area", "value" => $area));

   pem_field_label(array("default" => __("Space Name:"), "for" => "space_name"));
   pem_text_input(array("nameid" => "space_name", "value" => $space_name, "size" => 20, "maxlength" => 64));
   pem_field_note(array("default" => $space_name_note));
   pem_field_label(array("default" => __("Short Name:"), "for" => "space_name_short"));
   pem_text_input(array("nameid" => "space_name_short", "value" => $space_name_short, "size" => 20, "maxlength" => 24));
   pem_field_note(array("default" => $space_name_short_note));
   pem_field_label(array("default" => __("Space Description:"), "for" => "space_description"));
   pem_textarea_input(array("nameid" => "space_description", "default" => $space_description, "style" => "width:400px; height:100px;"));
   pem_field_note(array("default" => $space_description_note));
   pem_field_label(array("default" =>__("Space Popup:"), "for" => "space_popup"));
   pem_popup_select(array("name" => "space_popup", "default" =>$space_popup));
   pem_field_note(array("default" =>$space_popup_note));
   pem_field_label(array("default" => __("Contact:"), "for" => "space_contact"));
   pem_text_input(array("nameid" => "space_contact", "value" => $space_contact, "size" => 30, "maxlength" => 64));
   pem_field_note(array("default" => $space_contact_note));

   unset($where);
   $options = array(__("No Profile Selected") => "");
   $where["status"] = array("!=", "2");
   $list = pem_get_rows("supply_profiles", $where);
   for ($i = 0; $i < count($list); $i++)
   {
      $options[$list[$i]["profile_name"]] = $list[$i]["id"];
   }
   pem_field_label(array("default" => __("Standard Suppies:"), "for" => "supply_profilee"));
   pem_select($options, array("nameid" => "supply_profile", "default" => $supply_profile));
   pem_field_note(array("default" => $supply_profile_note));
   pem_field_label(array("default" => __("Optional Supplies:"), "for" => "optional_supplies"));
   pem_select($options, array("nameid" => "optional_supplies", "default" => $optional_supplies));
   pem_field_note(array("default" => $optional_supplies_note));

   pem_field_label(array("default" => __("Capacity:"), "for" => "capacity"));
   pem_text_input(array("nameid" => "capacity", "value" => $capacity, "size" => 5, "maxlength" => 5));
   pem_field_note(array("default" => $capacity_note));

   pem_field_label(array("default" => __("List in View Boxes:"), "for" => "show_boxes"));
   pem_boolean_select(array("nameid" => "show_boxes", "default" => $show_boxes));
   pem_field_note(array("default" => $show_boxes_note));

   pem_field_label(array("default" => __("Show in Day View:"), "for" => "show_day_view"));
   pem_boolean_select(array("nameid" => "show_day_view", "default" => $show_day_view));
   pem_field_note(array("default" => $show_day_view_note));

   echo '<h3>' . __("Scheduling Adjustment") . '</h3>';
   echo '<div class="indent">' . "\n";
   pem_field_label(array("default" => __("Use custom schedule boundaries for this room:"), "for" => "custom_schedule", "style" => "width:auto;"));
   pem_boolean_select(array("nameid" => "custom_schedule", "default" => $scheduling_profile["custom_schedule"], "onchange" => "toggleLayer('schedulediv', this.value);"));
   pem_field_note(array("default" => $custom_schedule_note));

   echo '<div id="schedulediv">' . "\n";
   for ($i = 0; $i < 7; $i++)
   {
      pem_field_label(array("default" => $weekday[$i], "for" => "time_before_open_" . $i . "_hours"));
      echo '<br /><div class="indent">' . "\n";
      pem_field_label(array("default" => __("Allow Before Open:"), "for" => "time_before_open_" . $i . "_hours"));
      pem_time_quantity_selector("time_before_open_" . $i . "_", array("default" => $scheduling_profile["time_before_open_" . $i]));
      pem_field_note(array("default" => $time_before_open_note));
      pem_field_label(array("default" => __("Allow After Closed:"), "for" => "time_after_closed_" . $i . "_hours"));
      pem_time_quantity_selector("time_after_closed_" . $i . "_", array("default" => $scheduling_profile["time_after_closed_" . $i]));
      pem_field_note(array("default" => $time_after_closed_note));
      pem_field_label(array("default" => __("Required Start Before Closed:"), "for" => "start_before_closed_" . $i . "_hours", "style" => "width:auto;"));
      pem_time_quantity_selector("start_before_closed_" . $i . "_", array("default" => $scheduling_profile["start_before_closed_" . $i]));
      pem_field_note(array("default" => $start_before_closed_note));
      echo '</div>' . "\n";
   }
   echo '</div>' . "\n";

   echo '</div>' . "\n";

   echo '<h3>' . __("Event Type Availability") . '</h3>';
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

   pem_field_label(array("default" => __("Active:"), "for" => "status", "style" => "width:auto;"));
   pem_boolean_select(array("nameid" => "status", "default" => $status));
   pem_field_note(array("default" => $status_note));

   if ($mode == "newspace" OR $mode == "finishspace") pem_form_submit("submitform", "cancel");
   else pem_form_update("submitform", "cancel");

   echo '<script type="text/javascript"><!--' . "\n";
   echo "toggleLayer('schedulediv', '" . $scheduling_profile["custom_schedule"] . "');\n";
   echo '// --></script>' . "\n";

   pem_form_end();
} // END echo_space_form

function echo_move_form($id = "", $area = "")
{
   global $PHP_SELF;
   // $move_space_to_note = __("(Select the new area for this space.)");

   pem_error_list($error);

   pem_form_begin(array("nameid" => "submitform", "action" => $PHP_SELF, "class" => "areasform"));
   pem_hidden_input(array("name" => "datasubmit", "value" => "finishmove"));
   pem_hidden_input(array("name" => "typeid", "value" => $id));

   unset($options);
   unset($where);
   $where["status"] = array("!=", "2");
   $list = pem_get_rows("areas", $where);
   for ($i = 0; $i < count($list); $i++)
   {
      if ($area != $list[$i]["id"]) $options[$list[$i]["area_name"]] = $list[$i]["id"];
   }
   pem_field_label(array("default" => __("New Area Name:"), "for" => "move_space_to"));
   pem_select($options, array("nameid" => "move_space_to"));
   pem_field_note(array("default" => $move_space_to_note));

   pem_form_update("submitform", "cancel");

   pem_form_end();
} // END echo_move_form


?>