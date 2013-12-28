<?php
/* ========================== FILE INFORMATION ================================= 
phxEventManager :: account-settings.php

This file provides web-based administration of main phxEventManager settings.
Sets limits on how user account logins and passwords are chosen
============================================================================= */

$pagetitle = "Account Settings Administration";
$navigation = "administration";
$page_access_requirement = "Admin";
$cache_set = array("current_navigation" => "users");
include_once "../pem-includes/header.php";

if (isset($datasubmit))
{
   $settings_submit = array(
      "user_login_min" => $user_login_min,
      "user_pass_min" => $user_pass_min
      );
   pem_update_settings($settings_submit);
   echo '<p><b>' . __("Account Settings have been updated.") . '</b></p>' . "\n";
}

pem_fieldset_begin(__("Account Settings"));
echo '<p>' . __("") . "</p>\n";
echo_form();
pem_fieldset_end();

include ABSPATH . PEMINC . "/footer.php";

// =============================================================================
// =========================== LOCAL FUNCTIONS =================================
// =============================================================================

function echo_form($error = "")
{
   global $PHP_SELF;
   extract(pem_get_settings());
   
   $user_login_min_note = __("(User will be prompted if submitted login is shorter than the minimum)");
   $user_pass_min_note = __("(User will be prompted if submitted password is shorter than the minimum)");
   $user_custom_theme_note = __("(Users will have the option to select a theme to use when they are logged in to their account.)");
   
   pem_error_list($error);
   
   pem_form_begin(array("nameid" => "submitform", "action" => $PHP_SELF, "class" => "accountform"));
   pem_hidden_input(array("name" => "datasubmit", "value" => 1)); 

   pem_field_label(array("default" => __("Login Minimum Length:"), "for" => "user_login_min"));
   pem_text_input(array("name" => "user_login_min", "value" => $user_login_min, "size" => 4, "maxlength" => 3));
   pem_field_note(array("default" => $user_login_min_note));
   pem_field_label(array("default" => __("Password Minimum Length:"), "for" => "user_pass_min"));
   pem_text_input(array("name" => "user_pass_min", "value" => $user_pass_min, "size" => 4, "maxlength" => 3));
   pem_field_note(array("default" => $user_pass_min_note));

   pem_field_label(array("default" => __("Allow Theme Selection:"), "for" => "user_custom_theme"));
   pem_boolean_select(array("nameid" => "user_custom_theme", "default" => $user_custom_theme));
   pem_field_note(array("default" => $user_custom_theme_note));


   
   pem_form_submit("submitform");
   pem_form_end();
} // END echo_form

?>