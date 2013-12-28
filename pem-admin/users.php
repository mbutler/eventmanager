<?php
/* ========================== FILE INFORMATION =================================
phxEventManager :: users.php


============================================================================= */

$pagetitle = "Users Administration";
$navigation = "administration";
$page_access_requirement = "Manage Users";
$cache_set = array("current_navigation" => "users");
include_once "../pem-includes/header.php";

/* example athorization code:
$check_resources = array("Reports", "Manage Reports");
// $check_resources = "Reports";
// $check_access = "Delete";
$check_access = array("Approve Own", "Delete");
echo (pem_user_authorized($check_resources, $check_access)) ? "Authorized" : "Not Authorized";
*/


$user_login_min = pem_get_setting("user_login_min");
$user_pass_min = pem_get_setting("user_pass_min");

$showaddform = true;
if (isset($datasubmit))
{
   unset($error);
   // The error checks are done here with if and not switch because case(empty($x)) returns a false positive

   if (empty($user_login)) $error[] = __("Login field cannot be empty.");
   else
   {
      if (strlen($user_login) < $user_login_min) $error[] = sprintf(__("Login must be at least %s characters."), $user_login_min);
      elseif ($datasubmit == "new" and auth_user_exists($user_login)) $error[] = __("That login is already taken; please select another.");
   }
//   if (empty($user_pass)) $error[] = __("Password cannot be empty.");
   if (!empty($user_pass))
   {
      if (strlen($user_pass) < $user_pass_min) $error[] = sprintf(__("Password must be at least %s characters."), $user_pass_min);
   }
   if ($user_pass != $user_pass2)
   {
      $validation = auth_validate_user($user_login, $user_pass);
      $pairgood = ($validation == "validuser" OR $validation == "inactive");
      if ($pairgood AND empty($user_pass2))
      { /* $error[] = __("good pair"); // debug test */

      }
      elseif (empty($user_pass2)AND !$pairgood)
         $error[] = __("New passwords must be confirmed by typing them twice.");
      else
         $error[] = __("Password fields do not match.");
   }
   if (empty($user_email)) $error[] = __("Email field is empty.");
   else
   {
      if (!is_email($user_email)) $error[] = __("Email format is invalid.");
   }

   switch (true)
   {
      case ($datasubmit == "new" AND isset($error)):
         $showaddform = false;
         pem_fieldset_begin(__("Add New Account"));
         echo_form("", $user_login, $user_pass, $user_pass2, $user_nicename, $user_email, $user_profile, $status, "new", $error);
         pem_fieldset_end();
         break;
      case ($datasubmit == "new"):
         auth_add_user($user_login, $user_pass, $user_nicename, $user_email, $user_profile, $status);
         echo '<p><b>' . __("Account added.") . '</b></p>' . "\n";
         break;
      case ($datasubmit == "edit"):
         $showaddform = false;
         pem_fieldset_begin(__("Edit Account"));
         echo '<p>' . __("Change the information as desired and submit the form to save your changes.") . "</p>\n";
         $data = auth_get_user($typeid);
         echo_form($data["id"], $data["user_login"], "", "", $data["user_nicename"], $data["user_email"], $data["user_profile"], $data["status"], "update");
         pem_fieldset_end();
         break;
      case ($datasubmit == "update" AND isset($error)):
         $showaddform = false;
         pem_fieldset_begin(__("Edit Account"));
         echo_form($typeid, $user_login, $user_pass, $user_pass2, $user_nicename, $user_email, $user_profile, $status, "update", $error);
         pem_fieldset_end();
         break;
      case ($datasubmit == "update"):
         auth_update_user($typeid, $user_login, $user_pass, $user_nicename, $user_email, $user_profile, $status);
         echo '<p><b>' . __("Account updated.") . '</b></p>' . "\n";
         break;
      case ($datasubmit == "activate"):
         auth_update_user_status($typeid, "1");
         echo '<p><b>' . __("Account activated.") . '</b></p>' . "\n";
         break;
      case ($datasubmit == "deactivate"):
         auth_update_user_status($typeid, "0");
         echo '<p><b>' . __("Account deactivated.") . '</b></p>' . "\n";
         break;
      case ($datasubmit == "delete"):
         auth_delete_user($typeid);
         echo '<p><b>' . __("Account deleted to recycle.") . '</b></p>' . "\n";
         break;
   }
}


// list current items in the table
pem_fieldset_begin(__("Current User Accounts"));

$fields_header =  array(
        __("Login"),
        __("Name"),
        __("Access"),
        __("Last On"),
);

$list = auth_get_users();
for ($i = 0; $i < count($list); $i++)
{
   $row = ($i % 2) ? "row2" : "row1";
   ob_start();
   switch (true)
   {
      case ($list[$i]["user_login"] == "admin"):
         echo_data($list[$i], "row1");
         $user_admin_echo = ob_get_clean();
         break;
      case ($list[$i]["user_login"] == "public"):
         echo_data($list[$i], "row2");
         $user_public_echo = ob_get_clean();
         break;
      default:
         echo_data($list[$i], $row);
         $user_list_echo .= ob_get_clean();
   }
}
echo '<table cellspacing="0" class="datalist">' . "\n";
echo '<tr>' . "\n";
for ($i = 0; $i < count($fields_header); $i++)
{
   echo '<th style="padding-top:0;">' . $fields_header[$i] . '</th>' . "\n";
}
echo '</tr>' . "\n";
if (pem_user_authorized("Admin"))
{
   echo $user_admin_echo;
   echo $user_public_echo;
}
echo $user_list_echo;
echo '</table>' . "\n";
pem_fieldset_end();


// display add new form if edit not in progress
if ($showaddform)
{
   pem_fieldset_begin(__("Add New Account"));
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
   $date_format = pem_get_setting("date_format");
   $time_format = pem_get_setting("time_format");

   $profiles_ret = pem_get_rows("access_profiles", $where);

   for ($i = 0; $i < count($profiles_ret); $i++)
   {
      $access_profiles[$profiles_ret[$i]["id"]] = $profiles_ret[$i]["profile_name"];
   }
   unset($profiles_ret);

   extract($data);
   echo '<tr class="' . $row . '"><td style="text-align:left; padding-right:10px;">' . "\n";
   pem_form_begin(array("nameid" => "dataform" . $id, "action" => $PHP_SELF));
   pem_hidden_input(array("name" => "datasubmit", "value" => "edit"));
   pem_hidden_input(array("name" => "typeid", "value" => $id));
   echo $user_login;
   pem_form_end();
   echo '</td><td style="text-align:left; padding-right:10px;">' . "\n";
   echo $user_nicename;
   echo '</td><td style="text-align:left; padding-right:10px;">' . "\n";
   echo $access_profiles[$user_profile];
   echo '</td><td style="text-align:right;">' . "\n";
   echo (empty($user_activity)) ? __("Never") : pem_date($date_format . " " . $time_format, $user_activity);
   echo '</td><td class="controlbox">' . "\n";
   if ($status == "0") $controls[] = array("label" => __("Activate"), "onclick" => "action_submit('dataform" . $id . "', 'activate');");
   else $controls[] = array("label" => __("Deactivate"), "onclick" => "action_submit('dataform" . $id . "', 'deactivate');");
   $controls[] = array("label" => __("Edit"), "onclick" => "action_submit('dataform" . $id . "', 'edit');");
   if ($id != 1 AND $id != 2) $controls[] = array("label" => __("Delete"), "onclick" => "confirm_submit('dataform" . $id . "', 'delete', '" . $delete_confirm . "');");
   pem_controls($controls);
   echo '</td></tr>' . "\n";
} // END echo_data

function echo_form($id = "", $user_login = "", $user_pass = "", $user_pass2 = "", $user_nicename = "", $user_email = "", $user_profile = "", $status = "", $mode = "new", $error = "")
{
   global $PHP_SELF, $user_login_min, $user_pass_min;
   $user_login_note = sprintf(__("(Must be at least %s characters)"), $user_login_min);
   $user_pass_note = sprintf(__("(Must be at least %s characters)"), $user_pass_min);
   $user_pass2_note = __("(Confirm your password by retyping it a second time)");
   $user_nicename_note = __("(The real name of this user)");
   $user_email_note = __("(Messages will be sent to this address)");
   $user_profile_note = __("(Grant access by choosing an appropriate profile)");
   $status_note = __("(This account must be active for successful login)");

   pem_error_list($error);

   pem_form_begin(array("nameid" => "submitform", "action" => $PHP_SELF, "class" => "adduserform"));
   pem_hidden_input(array("name" => "datasubmit", "value" => $mode));
   pem_hidden_input(array("name" => "typeid", "value" => $id));

   pem_field_label(array("default" => __("User Login:"), "for" => "user_login"));
   pem_text_input(array("name" => "user_login", "value" => $user_login, "size" => 20, "maxlength" => 60));
   pem_field_note(array("default" => $user_login_note));
   pem_field_label(array("default" => __("User Password:"), "for" => "user_pass"));
   pem_password_input(array("name" => "user_pass", "value" => $user_pass, "size" => 20, "maxlength" => 64));
   pem_field_note(array("default" => $user_pass_note));
   pem_field_label(array("default" => __("Confirm Password:"), "for" => "user_pass2"));
   pem_password_input(array("name" => "user_pass2", "value" => $user_pass2, "size" => 20, "maxlength" => 64));
   pem_field_note(array("default" => $user_pass2_note));
   pem_field_label(array("default" => __("Real Name:"), "for" => "user_nicename"));
   pem_text_input(array("name" => "user_nicename", "value" => $user_nicename, "size" => 30, "maxlength" => 80));
   pem_field_note(array("default" => $user_nicename_note));
   pem_field_label(array("default" => __("User Email:"), "for" => "user_email"));
   pem_text_input(array("name" => "user_email", "value" => $user_email, "size" => 30, "maxlength" => 100));
   pem_field_note(array("default" => $user_email_note));
   pem_field_label(array("default" => __("Access Profile:"), "for" => "user_profile"));
   pem_access_profiles_select(array("nameid" => "user_profile", "default" => $user_profile));
   pem_field_note(array("default" => $user_profile_note));
   pem_field_label(array("default" => __("Active:"), "for" => "status"));
   pem_boolean_select(array("nameid" => "status", "default" => $status));
   pem_field_note(array("default" => $status_note));

   if ($mode != "new") pem_form_update("submitform", "cancel");
   elseif ($mode == "new" AND !empty($error)) pem_form_submit("submitform", "cancel");
   else pem_form_submit("submitform");
   pem_form_end();
} // END echo_form

?>