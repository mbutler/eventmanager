<?php
/* ========================== FILE INFORMATION ================================= 
phxEventManager :: interface-defaults.php

This file provides web-based administration of main phxEventManager settings.
============================================================================= */

$pagetitle = "Interface Defaults Administration";
$navigation = "administration";
$page_access_requirement = "Admin";
$cache_set = array("current_navigation" => "backend");
include_once "../pem-includes/header.php";

if (isset($datasubmit))
{
   $settings_submit = array(
           "pem_theme" => (empty($pem_theme_submit)) ? "ruby" : $pem_theme_submit,
           "default_view" => $default_view,
   );
   pem_update_settings($settings_submit);
   $pem_theme = $pem_theme_submit;
   echo '<p><b>' . __("Interface Defaults have been updated.") . '</b></p>' . "\n";
}

pem_fieldset_begin(__("Interface Defaults"));
echo '<p>' . __("Updates to this information may not be immediately apparent in header or footer content for this page.  Browse to other pages to see your changes after saving.") . "</p>\n";
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

   $pem_theme_note = __("(Options based on the pem-themes directory)");
   ;
   $default_view_note = __("(View provided to a session prior to user selection)");

   pem_error_list($error);

   pem_form_begin(array("nameid" => "submitform", "action" => $PHP_SELF, "class" => "interfaceform"));
   pem_hidden_input(array("name" => "datasubmit", "value" => 1));

   pem_field_label(array("default" =>__("Theme:"), "for" => "pem_theme"));
   pem_theme_select(array("name" => "pem_theme_submit", "default" =>$pem_theme));
   pem_field_note(array("default" =>$pem_theme_note));
   pem_field_label(array("default" => __("Default View:"), "for" => "default_view"));
   pem_view_select(array("name" => "default_view", "default" => $default_view));
   pem_field_note(array("default" => $default_view_note));

   pem_form_submit("submitform");
   pem_form_end();
} // END echo_form

?>