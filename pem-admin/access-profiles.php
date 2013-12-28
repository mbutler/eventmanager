<?php
/* ========================== FILE INFORMATION =================================
phxEventManager :: access-profiles.php

============================================================================= */

$pagetitle = "Access Profiles Administration";
$navigation = "administration";
$page_access_requirement = "Manage Users";
$cache_set = array("current_navigation" => "users");
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
         pem_fieldset_begin(__("Add New Access Profile"));
         echo '<p>' . $error_instructions . "</p>\n";
         $profile = build_access();
         echo_form("", $profile_name, $description, $profile, $status, "new", $error);
         pem_fieldset_end();
         break;
      case ($datasubmit == "new"):
         $profile = build_access();
         $profile = (is_array($profile)) ? serialize($profile) : "";
         $data = array("profile_name" => $profile_name, "description" => $description, "profile" => $profile, "status" => $status);
         $types = array("profile_name" => "text", "description" => "text", "profile" => "clob", "status" => "text");
         pem_add_row("access_profiles", $data, $types);
         echo '<p><b>' . __("Access Profile added.") . '</b></p>' . "\n";
         break;
      case ($datasubmit == "copy"):
         $showaddform = false;
         pem_fieldset_begin(__("Add New Access Profile"));
         echo '<p>' . __("The new profile form has been populated with copied settings.  Make additional changes as desired and submit the form to create a new profile.") . "</p>\n";
         $data = pem_get_row("id", $typeid, "access_profiles");
         $profile = unserialize($data["profile"]);
         echo_form($data["id"], $data["profile_name"], $data["description"], $profile, $data["status"], "new");
         pem_fieldset_end();
         break;
      case ($datasubmit == "edit"):
         $showaddform = false;
         pem_fieldset_begin(__("Edit Access Profile"));
         echo '<p>' . __("Change the settings as desired and submit the form to save your changes.") . "</p>\n";
         $data = pem_get_row("id", $typeid, "access_profiles");
         $profile = unserialize($data["profile"]);
         echo_form($data["id"], $data["profile_name"], $data["description"], $profile, $data["status"], "update");
         pem_fieldset_end();
         break;
      case ($datasubmit == "update" AND (isset($error) or $hold_submit)):
         $showaddform = false;
         pem_fieldset_begin(__("Edit Access Profile"));
         echo '<p>' . $error_instructions . "</p>\n";
         $profile = build_access();
         echo_form($typeid, $profile_name, $description, $profile, $status, "update", $error);
         pem_fieldset_end();
         break;
      case ($datasubmit == "update"):
         $profile = build_access();
         $profile = (is_array($profile)) ? serialize($profile) : "";
         $data = array("profile_name" => $profile_name, "description" => $description, "profile" => $profile, "status" => $status);
         $where = array("id" => $typeid);
         pem_update_row("access_profiles", $data, $where);
         echo '<p><b>' . __("Access Profile updated.") . '</b></p>' . "\n";
         break;
      case ($datasubmit == "activate"):
         $data = array("status" => "1");
         $where = array("id" => $typeid);
         pem_update_row("access_profiles", $data, $where);
         echo '<p><b>' . __("Access Profile activated globally.") . '</b></p>' . "\n";
         break;
      case ($datasubmit == "deactivate"):
         $data = array("status" => "0");
         $where = array("id" => $typeid);
         pem_update_row("access_profiles", $data, $where);
         echo '<p><b>' . __("Access Profile deactivated globally.") . '</b></p>' . "\n";
         break;
      case ($datasubmit == "delete"):
         $where = array("id" => $typeid);
         pem_delete_recycle("access_profiles", $where);
         echo '<p><b>' . __("Access Profile deleted to recycle.") . '</b></p>' . "\n";
         break;
   }
}

// list current items in the table
pem_fieldset_begin(__("Current Access Profiles"));
echo '<p>' . __("Deactivated profiles are not selectable in user account administration, but accounts currently using a profile that has just been deactivated will still use the profile until the user account is either edited to use a different profile or the account is disabled.  The Administrator and Public profile types are built-in and can not be deleted.") . "</p>\n";
echo '<table cellspacing="0" class="datalist">' . "\n";
unset($where);
$where["status"] = array("!=", "2");
$list = pem_get_rows("access_profiles", $where, "", "profile_name");
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
   pem_fieldset_begin(__("Add New Access Profile"));
   echo '<p>' . __("Configure a permissions profile using the options below and save it to apply the setting to user accounts.  Access above View assumes View as well for that resource.  All-Day event access is controlled using Internal/External Calendar options.") . "</p>\n";
   echo_form();
   pem_fieldset_end();
}

include ABSPATH . PEMINC . "/footer.php";

// =============================================================================
// =========================== LOCAL FUNCTIONS =================================
// =============================================================================

function build_access()
{
   global $_POST, $auth_res;

   $access = "";
   $resource_keys = array_keys($auth_res);
   for ($i = 0; $i < 4; $i++)
   {
      if (isset($_POST[$resource_keys[$i] . "-view"])) $access[$resource_keys[$i]][] = 10;
      if (isset($_POST[$resource_keys[$i] . "-add"])) $access[$resource_keys[$i]][] = 11;
      if ($_POST[$resource_keys[$i] . "-edit"] != "0") $access[$resource_keys[$i]][] = intval($_POST[$resource_keys[$i] . "-edit"]);
      if ($_POST[$resource_keys[$i] . "-approve"] != "0") $access[$resource_keys[$i]][] = intval($_POST[$resource_keys[$i] . "-approve"]);
      if ($_POST[$resource_keys[$i] . "-delete"] != "0") $access[$resource_keys[$i]][] = intval($_POST[$resource_keys[$i] . "-delete"]);
   }
   for ($i = 4; $i < 9; $i++)
   {
      if (isset($_POST[$resource_keys[$i] . "-view"])) $access[$resource_keys[$i]][] = 10;
      if (isset($_POST[$resource_keys[$i] . "-add"])) $access[$resource_keys[$i]][] = 11;
      if (isset($_POST[$resource_keys[$i] . "-edit"])) $access[$resource_keys[$i]][] = 12;
      if (isset($_POST[$resource_keys[$i] . "-delete"])) $access[$resource_keys[$i]][] = 19;
   }
   for ($i = 9; $i < count($resource_keys); $i++)
   {
      if (isset($_POST[$resource_keys[$i] . "-view"])) $access[$resource_keys[$i]][] = 10;
   }
   return $access;
} // END build_access

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
   echo '</td><td class="controlboxwide">' . "\n";
   if ($status == "0") $controls[] = array("label" => __("Activate"), "onclick" => "action_submit('dataform" . $id . "', 'activate');");
   else $controls[] = array("label" => __("Deactivate"), "onclick" => "action_submit('dataform" . $id . "', 'deactivate');");
   if ($id != 1) $controls[] = array("label" => __("Copy to New"), "onclick" => "action_submit('dataform" . $id . "', 'copy');");
   if ($id != 1) $controls[] = array("label" => __("Edit"), "onclick" => "action_submit('dataform" . $id . "', 'edit');");
   if ($id != 1 AND $id != 2) $controls[] = array("label" => __("Delete"), "onclick" => "confirm_submit('dataform" . $id . "', 'delete', '" . $delete_confirm . "');");
   pem_controls($controls);
   echo '</td></tr>' . "\n";
} // END echo_data

function echo_form($id = "", $profile_name = "", $description = "", $profile = "", $status = "", $mode = "new", $error = "")
{
   global $auth_res, $auth_res_text, $PHP_SELF;
   $users_header =  array(
           __("View"),
           __("Add"),
           __("Edit"),
           __("Approve"),
           __("Delete"),
   );

   $profile_name_note = __("(This name is used to attach the profile to spaces)");
   $description_note = __("(Describe this profile for for administrative use)");
   $status_note = __("(This profile must be active for the option to appear in forms and views)");

   pem_error_list($error);

   pem_form_begin(array("nameid" => "submitform", "action" => $PHP_SELF, "class" => "accessprofform"));
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

   $resource_keys = array_keys($auth_res);
   $rowclass = "row1";

   echo '<table cellspacing="0" class="authtable">' . "\n";
   echo '<tr>';
   echo '<th>&nbsp;</th>' . "\n";
   for ($i = 0; $i < count($users_header); $i++)
   {
      echo '<th>' . $users_header[$i] . '</th>' . "\n";
   }
   echo '</tr>' . "\n";
   for ($i = 0; $i < 4; $i++)
   {
      $user_view = false;
      $user_add = false;
      $user_edit = 0;
      $user_approve = 0;
      $user_delete = 0;
      if (isset($profile[$resource_keys[$i]]))
      {
         $user_view = in_array(10, $profile[$resource_keys[$i]]);
         $user_add = in_array(11, $profile[$resource_keys[$i]]);
         if (in_array(13, $profile[$resource_keys[$i]])) $user_edit = 13;
         elseif (in_array(14, $profile[$resource_keys[$i]])) $user_edit = 14;
         elseif (in_array(15, $profile[$resource_keys[$i]])) $user_edit = 15;
         if (in_array(16, $profile[$resource_keys[$i]])) $user_approve = 16;
         elseif (in_array(17, $profile[$resource_keys[$i]])) $user_approve = 17;
         elseif (in_array(18, $profile[$resource_keys[$i]])) $user_approve = 18;
         if (in_array(20, $profile[$resource_keys[$i]])) $user_delete = 20;
         elseif (in_array(21, $profile[$resource_keys[$i]])) $user_delete = 21;
         elseif (in_array(22, $profile[$resource_keys[$i]])) $user_delete = 22;
      }
      echo '<tr class="' . $rowclass . '">' . "\n";
      echo '<td style="text-align:left; font-weight:bold;">' . $auth_res_text[$resource_keys[$i]] . '</td>' . "\n";
      echo '<td>';
      pem_checkbox(array("name" => $resource_keys[$i] . "-view", "status" => $user_view));
      echo '</td>' . "\n" . '<td>';
      pem_checkbox(array("name" => $resource_keys[$i] . "-add", "status" => $user_add));
      echo '</td>' . "\n" . '<td>';
      pem_auth_keys_select("Edit", array("name" => $resource_keys[$i] . "-edit", "default" => $user_edit));
      echo '</td>' . "\n" . '<td>';
      pem_auth_keys_select("Approve", array("name" => $resource_keys[$i] . "-approve", "default" => $user_approve));
      echo '</td>' . "\n" . '<td>';
      pem_auth_keys_select("Delete", array("name" => $resource_keys[$i] . "-delete", "default" => $user_delete));
      echo '</td>' . "\n" . '</tr>' . "\n" ;
      $rowclass = ($rowclass == "row1") ? "row2" : "row1";
   }
   echo '</table>' . "\n";

   echo '<table cellspacing="0" class="authtable">' . "\n";
   echo '<tr>';
   echo '<th>&nbsp;</th>' . "\n";
   for ($i = 0; $i < count($users_header); $i++)
   {
      if ($i != 3) echo '<th>' . $users_header[$i] . '</th>' . "\n";
   }
   echo '</tr>' . "\n";
   for ($i = 4; $i < 9; $i++)
   {
      $user_view = false;
      $user_add = false;
      $user_edit = false;
      $user_delete = false;
      if (isset($profile[$resource_keys[$i]]))
      {
         $user_view = in_array(10, $profile[$resource_keys[$i]]);
         $user_add = in_array(11, $profile[$resource_keys[$i]]);
         $user_edit = in_array(12, $profile[$resource_keys[$i]]);
         $user_delete = in_array(19, $profile[$resource_keys[$i]]);
      }
      echo '<tr class="' . $rowclass . '">' . "\n";
      echo '<td style="text-align:left; font-weight:bold;">' . $auth_res_text[$resource_keys[$i]] . '</td>' . "\n";
      echo '<td>';
      pem_checkbox(array("name" => $resource_keys[$i] . "-view", "status" => $user_view));
      echo '</td>' . "\n" . '<td>';
      pem_checkbox(array("name" => $resource_keys[$i] . "-add", "status" => $user_add));
      echo '</td>' . "\n" . '<td>';
      pem_checkbox(array("name" => $resource_keys[$i] . "-edit", "status" => $user_edit));
      echo '</td>' . "\n" . '<td>';
      pem_checkbox(array("name" => $resource_keys[$i] . "-delete", "status" => $user_delete));
      echo '</td>' . "\n" . '</tr>' . "\n" ;
      $rowclass = ($rowclass == "row1") ? "row2" : "row1";
   }
   echo '</table>' . "\n";

   echo '<table cellspacing="0" class="authtable" style="margin:20px 0;">' . "\n";
   for ($i = 9; $i < count($resource_keys); $i++)
   {
      $user_view = false;
      $user_add = false;
      $user_edit = false;
      $user_delete = false;
      if (isset($profile[$resource_keys[$i]]))
      {
         $user_view = in_array(10, $profile[$resource_keys[$i]]);
         $user_add = in_array(11, $profile[$resource_keys[$i]]);
         $user_edit = in_array(12, $profile[$resource_keys[$i]]);
         $user_delete = in_array(19, $profile[$resource_keys[$i]]);
      }
      echo '<tr class="' . $rowclass . '">' . "\n";
      echo '<td>';
      pem_checkbox(array("name" => $resource_keys[$i] . "-view", "status" => $user_view));
      echo '</td>' . "\n";
      echo '<td style="text-align:left; font-weight:bold;">' . $auth_res_text[$resource_keys[$i]] . '</td>' . "\n";
      echo '</tr>' . "\n" ;
      $rowclass = ($rowclass == "row1") ? "row2" : "row1";
   }
   echo '</table>' . "\n";


   if ($mode != "new") pem_form_update("submitform", "cancel");
   elseif ($mode == "new" AND !empty($error)) pem_form_submit("submitform", "cancel");
   else pem_form_submit("submitform");

   pem_form_end();
} // END echo_form


?>