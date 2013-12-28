<?php
/* ========================== FILE INFORMATION ================================= 
phxEventManager :: supply-profiles.php

============================================================================= */

$pagetitle = "Supply Profiles Administration";
$navigation = "administration";
$page_access_requirement = "Manage Meta";
$cache_set = array("current_navigation" => "meta");
include_once "../pem-includes/header.php";

$showaddform = true;
if (isset($datasubmit))
{
   unset($error);
   // The error checks are done here with if and not switch because case(empty($x)) returns a false positive
   if (empty($profile_name)) $error[] = __("Profile Name cannot be empty.");

   switch (true)
   {
      case ($datasubmit == "new" AND isset($error)):
         $showaddform = false;
         pem_fieldset_begin(__("Add New Supply Profile"));
         $supply_keys = array_keys($qty);
         $supply_profile = "";
         for ($i = 0; $i < count($supply_keys); $i++)
         {
            if (!empty($qty[$supply_keys[$i]])) $supply_profile[$supply_keys[$i]] = intval($qty[$supply_keys[$i]]);
         }
         echo_form("", $profile_name, $description, $supply_profile, $status, "new", $error);
         pem_fieldset_end();
         break;
      case ($datasubmit == "new"):
         $supply_keys = array_keys($qty);
         $supply_profile = "";
         for ($i = 0; $i < count($supply_keys); $i++)
         {
            if (!empty($qty[$supply_keys[$i]])) $supply_profile[$supply_keys[$i]] = intval($qty[$supply_keys[$i]]);
         }
         $profile = (is_array($supply_profile)) ? serialize($supply_profile) : "";
         $data = array("profile_name" => $profile_name, "description" => $description, "profile" => $profile, "status" => $status);
         $types = array("profile_name" => "text", "description" => "text", "profile" => "clob", "status" => "text");
         pem_add_row("supply_profiles", $data, $types);
         echo '<p><b>' . __("Supply Profile added.") . '</b></p>' . "\n";
         break;
      case ($datasubmit == "edit"):
         $showaddform = false;
         pem_fieldset_begin(__("Edit Supply Profile"));
         echo '<p>' . __("Change the settings as desired and submit the form to save your changes.") . "</p>\n";
         $data = pem_get_row("id", $typeid, "supply_profiles");
         $supply_profile = unserialize($data["profile"]);
         echo_form($data["id"], $data["profile_name"], $data["description"], $supply_profile, $data["status"], "update");
         pem_fieldset_end();
         break;
      case ($datasubmit == "update" AND (isset($error) or $hold_submit)):
         $showaddform = false;
         pem_fieldset_begin(__("Edit Supply Profile"));
         $supply_keys = array_keys($qty);
         $supply_profile = "";
         for ($i = 0; $i < count($supply_keys); $i++)
         {
            if (!empty($qty[$supply_keys[$i]])) $supply_profile[$supply_keys[$i]] = intval($qty[$supply_keys[$i]]);
         }
         echo_form($typeid, $profile_name, $description, $supply_profile, $status, "update", $error);
         pem_fieldset_end();
         break;
      case ($datasubmit == "update"):
         $supply_keys = array_keys($qty);
         $supply_profile = "";
         for ($i = 0; $i < count($supply_keys); $i++)
         {
            if (!empty($qty[$supply_keys[$i]])) $supply_profile[$supply_keys[$i]] = intval($qty[$supply_keys[$i]]);
         }
         $profile = (is_array($supply_profile)) ? serialize($supply_profile) : "";
         $data = array("profile_name" => $profile_name, "description" => $description, "profile" => $profile, "status" => $status);
         $where = array("id" => $typeid);
         pem_update_row("supply_profiles", $data, $where);
         echo '<p><b>' . __("Supply Profile updated.") . '</b></p>' . "\n";
         break;
      case ($datasubmit == "activate"):
         $data = array("status" => "1");
         $where = array("id" => $typeid);
         pem_update_row("supply_profiles", $data, $where);
         echo '<p><b>' . __("Supply Profile activated globally.") . '</b></p>' . "\n";
         break;
      case ($datasubmit == "deactivate"):
         $data = array("status" => "0");
         $where = array("id" => $typeid);
         pem_update_row("supply_profiles", $data, $where);
         echo '<p><b>' . __("Supply Profile deactivated globally.") . '</b></p>' . "\n";
         break;
      case ($datasubmit == "delete"):
         $where = array("id" => $typeid);
         pem_delete_recycle("supply_profiles", $where);
         echo '<p><b>' . __("Supply Profile deleted to recycle.") . '</b></p>' . "\n";
         break;
   }
}

// list current items in the table
pem_fieldset_begin(__("Current Supply Profiles"));
echo '<table cellspacing="0" class="datalist">' . "\n";
unset($where);
$where["status"] = array("!=", "2");
$list = pem_get_rows("supply_profiles", $where);
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
   pem_fieldset_begin(__("Add New Supply Profile"));
   echo '<p>' . __("Placing supplies into named profiles allows you to apply the groupings to specific spaces to provide standard and optional event supplies.  Enter qantities for each of the resources below that will be part of the profile.  When a space is booked for an event, the space's supply profiles are displayed.  During booking only supplies available to that event type will show.") . "</p>\n";
   echo_form();
   pem_fieldset_end();
}

include ABSPATH . PEMINC . "/footer.php";

// =============================================================================
// =========================== LOCAL FUNCTIONS =================================
// =============================================================================

function echo_data($data, $row)
{
   global $delete_confirm, $entry_types;
   extract($data);
   $type_text = array_flip($entry_types);

   echo '<tr class="' . $row . '"><td>' . "\n";
   pem_form_begin(array("nameid" => "dataform" . $id, "action" => $PHP_SELF));
   pem_hidden_input(array("name" => "datasubmit", "value" => "edit"));
   pem_hidden_input(array("name" => "typeid", "value" => $id));
   pem_field_label(array("default" => $profile_name));
   if (!empty($description)) echo '<br /><p class="indent" style="text-align:left;">' . $description . '</p>' . "\n";
   pem_form_end();
   echo '</td><td class="controlbox">' . "\n";
   if ($status == "0") $controls[] = array("label" => __("Activate"), "onclick" => "action_submit('dataform" . $id . "', 'activate');");
   else $controls[] = array("label" => __("Deactivate"), "onclick" => "action_submit('dataform" . $id . "', 'deactivate');");
   $controls[] = array("label" => __("Edit"), "onclick" => "action_submit('dataform" . $id . "', 'edit');");
   $controls[] = array("label" => __("Delete"), "onclick" => "confirm_submit('dataform" . $id . "', 'delete', '" . $delete_confirm . "');");
   pem_controls($controls);
   echo '</td></tr>' . "\n";
} // END echo_data

function echo_form($id = "", $profile_name = "", $description = "", $supply_profile = "", $status = "", $mode = "new", $error = "")
{
   global $PHP_SELF;
   $profile_name_note = __("(This name is used to attach the profile to user accounts)");
   $description_note = __("(Describe this profile for for administrative use)");
   $status_note = __("(This profile must be active for the option to appear in forms and views)");

   pem_error_list($error);

   pem_form_begin(array("nameid" => "submitform", "action" => $PHP_SELF, "class" => "supplyprofform"));
   pem_hidden_input(array("name" => "datasubmit", "value" => $mode));
   pem_hidden_input(array("name" => "typeid", "value" => $id));
   pem_field_label(array("default" => __("Profile Name:"), "for" => "profile_name"));
   pem_text_input(array("nameid" => "profile_name", "value" => $profile_name, "size" => 30, "maxlength" => 50));
   pem_field_note(array("default" => $profile_name_note));
   pem_field_label(array("default" => __("Description:"), "for" => "description"));
   pem_textarea_input(array("nameid" => "description", "default" => $description, "style" => "width:400px; height:100px;"));
   pem_field_note(array("default" => $description_note));

   pem_field_label(array("default" => __("Active:"), "for" => "status"));
   pem_boolean_select(array("nameid" => "status", "default" => $status));
   pem_field_note(array("default" => $status_note));

   unset($where);
   $where["status"] = array("!=", "2");
   $list = pem_get_rows("supplies", $where);
   if (!empty($list))
   {
      echo '<div class="indent">' . "\n";
      pem_field_label(array("default" => __("Qty.")));
      echo "<br />\n";
      for ($i = 0; $i < count($list); $i++)
      {
         $value = (is_array($supply_profile) AND array_key_exists($list[$i]["id"], $supply_profile)) ? $supply_profile[$list[$i]["id"]] : 0;
         pem_text_input(array("nameid" => "qty[" . $list[$i]["id"] . "]", "value" => $value, "size" => 2, "style" => "text-align:right;"));
         pem_field_label(array("default" => $list[$i]["supply_name"], "for" => "qty[" . $list[$i]["id"] . "]"));
         echo "<br />\n";
      }
      echo '</div>' . "\n";
   }

   if ($mode != "new") pem_form_update("submitform", "cancel");
   elseif ($mode == "new" AND !empty($error)) pem_form_submit("submitform", "cancel");
   else pem_form_submit("submitform");

   pem_form_end();
} // END echo_form

?>