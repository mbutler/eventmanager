<?php
/* ========================== FILE INFORMATION ================================= 
phxEventManager :: general-settings.php

This file provides web-based administration of main phxEventManager settings.
The general set of settings includes application and owner identification,
root and theme pathing, and the global content charset.
============================================================================= */

$pagetitle = "General Settings Administration";
$navigation = "administration";
$page_access_requirement = "Admin";
$cache_set = array("current_navigation" => "backend");
include_once "../pem-includes/header.php";

if (isset($datasubmit))
{
   $settings_submit = array(
           "pem_title" => $pem_title_submit,
           "pem_owner" => $pem_owner_submit,
           "title_img" => $title_img_submit,
           "admin_name" => $admin_name_submit,
           "admin_email" => $admin_email_submit,
           "pem_url" => $pem_url_submit,
           "content_charset" => $content_charset_submit
   );
   pem_update_settings($settings_submit);
   $pem_title = $pem_title_submit;
   $pem_owner = $pem_owner_submit;
   $title_img = $title_img_submit;
   $admin_name = $admin_name_submit;
   $admin_email = $admin_email_submit;
   $pem_url = $pem_url_submit;
   $content_charset = $content_charset_submit;
   echo '<p><b>' . __("General Settings have been updated.") . '</b></p>' . "\n";
}

pem_fieldset_begin(__("General Settings"));
echo '<p>' . __("Updates to this information may not be immediately apparent in header or footer content for this page.  Browse to other pages to see your changes after saving.") . "</p>\n";
echo_form();
pem_fieldset_end();

include ABSPATH . PEMINC . "/footer.php";

// =============================================================================
// =========================== LOCAL FUNCTIONS =================================
// =============================================================================

function echo_form($error = "")
{
   global $PHP_SELF, $pem_title, $pem_owner, $title_img, $admin_name, $admin_email, $pem_url, $pem_theme, $content_charset;

   $pem_title_note = __("(Displayed in the upper left corner of every page and in browser title)");
   $pem_owner_note = __("(Owner information is displayed in the footer)");
   $title_img_note = __("(Place an image in the theme folder and enter its name here to replace the Title text)");
   $admin_name_note = __("(Used in automated messages from the system)");
   $admin_email_note = __("(The address to receive administrative messages)");
   $pem_url_note = __("(The complete URL path to your install)");
   $content_charset_note = "";

   if ($title_img === 0) $title_img = "";

   pem_error_list($error);

   pem_form_begin(array("nameid" => "submitform", "action" => $PHP_SELF, "class" => "generalform"));
   pem_hidden_input(array("name" => "datasubmit", "value" => 1));
   pem_hidden_input(array("name" => "reloadglobal", "value" => 1));

   pem_field_label(array("default" => __("Title:"), "for" => "pem_title"));
   pem_text_input(array("nameid" => "pem_title_submit", "value" => $pem_title, "size" => 30, "maxlength" => 60));
   pem_field_note(array("default" =>$pem_title_note));
   pem_field_label(array("default" =>__("Owner Name:"), "for" => "pem_owner"));
   pem_text_input(array("nameid" => "pem_owner_submit", "value" => $pem_owner, "size" => 30, "maxlength" => 64));
   pem_field_note(array("default" =>$pem_owner_note));
   pem_field_label(array("default" =>__("Title Image:"), "for" => "title_img"));
   pem_text_input(array("nameid" => "title_img_submit", "value" => $title_img, "size" => 30, "maxlength" => 64));
   pem_field_note(array("default" =>$title_img_note));
   pem_field_label(array("default" =>__("Admin Name:"), "for" => "admin_name"));
   pem_text_input(array("nameid" => "admin_name_submit", "value" => $admin_name, "size" => 40, "maxlength" => 80));
   pem_field_note(array("default" =>$admin_name_note));
   pem_field_label(array("default" =>__("Admin Email:"), "for" => "admin_email"));
   pem_text_input( array("nameid" => "admin_email_submit","value" => $admin_email, "size" => 30, "maxlength" => 100));
   pem_field_note(array("default" =>$admin_email_note));
   pem_field_label(array("default" =>__("Base URL:"), "for" => "pem_url"));
   pem_text_input(array("nameid" => "pem_url_submit", "value" => $pem_url, "size" => 40, "maxlength" => 100));
   pem_field_note(array("default" =>$pem_url_note));
   pem_field_label(array("default" =>__("Content Charset:"), "for" => "content_charset"));
   pem_text_input(array("nameid" => "content_charset_submit", "value" => $content_charset, "size" => 20, "maxlength" => 50));
   pem_field_note(array("default" =>$content_charset_note));

   pem_form_submit("submitform");
   pem_form_end();
} // END echo_form

?>