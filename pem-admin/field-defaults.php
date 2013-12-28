<?php
/* ========================== FILE INFORMATION ================================= 
phxEventManager :: field-defaults.php

This file provides web-based administration of main phxEventManager settings.
============================================================================= */

$pagetitle = "Field Defaults Administration";
$navigation = "administration";
$page_access_requirement = "Admin";
$cache_set = array("current_navigation" => "backend");
include_once "../pem-includes/header.php";

if (isset($datasubmit))
{
   $settings_submit = array(
           "default_phone" => $default_phone,
           "default_email" => $default_email,
           "default_city" => $default_city,
           "state_select" => $state_select,
           "default_state" => $default_state
   );
   pem_update_settings($settings_submit);
}

pem_fieldset_begin(__("Field Defaults"));
echo '<p>' . __("These fields can be used to auto-populate forms with commonly used data.  You may also find them useful in prompting users with suggested content or formats.") . "</p>\n";
field_defaults_form();
pem_fieldset_end();

include ABSPATH . PEMINC . "/footer.php";

// =============================================================================
// =========================== LOCAL FUNCTIONS =================================
// =============================================================================

function field_defaults_form($error = "")
{
   global $PHP_SELF;
   extract(pem_get_settings());

   $default_phone_note = __("(The local area code or default phone number used regularly)");
   $default_email_note = __("(The default @domain suffix or main email address)");
   $default_city_note = __("(The local city name where all or most events will occur)");
   $state_select_note = __("Use a drop-down select field listing US state options");
   $default_state_note = __("The state/province where all or most events will occur");

   pem_error_list($error);

   pem_form_begin(array("nameid" => "settingsform", "action" => $PHP_SELF, "class" => "defaultsform"));
   pem_hidden_input(array("name" => "datasubmit", "value" => 1));

   pem_field_label(array("default" => __("Default Phone Value:"), "for" => "default_phone"));
   pem_text_input(array("name" => "default_phone", "value" => $default_phone, "size" => 15, "maxlength" => 14));
   pem_field_note(array("default" => $default_phone_note));
   pem_field_label(array("default" => __("Default Email Value:"), "for" => "default_email"));
   pem_text_input(array("name" => "default_email", "value" => $default_email, "size" => 45, "maxlength" => 128));
   pem_field_note(array("default" => $default_email_note));
   pem_field_label(array("default" => __("Default City Value:"), "for" => "default_city"));
   pem_text_input(array("name" => "default_city", "value" => $default_city, "size" => 45, "maxlength" => 50));
   pem_field_note(array("default" => $default_city_note));
   pem_field_label(array("default" => __("Use State Select:"), "for" => "state_select"));
   pem_boolean_select(array("name" => "state_select", "default" => $state_select));
   pem_field_note(array("default" => $state_select_note));
   pem_field_label(array("default" => __("Default State Value:"), "for" => "default_state"));
   pem_text_input(array("name" => "default_state", "value" => $default_state, "size" => 30, "maxlength" => 50));
   pem_field_note(array("default" => $default_state_note));

   pem_form_submit("settingsform");
   pem_form_end();
} // END field_defaults_form

?>