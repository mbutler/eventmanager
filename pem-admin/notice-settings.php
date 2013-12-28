<?php
/* ========================== FILE INFORMATION =================================
phxEventManager :: notice-settings.php

These settings are used to determine when and how notifications are handled
with regard to event creation, approval, and changes.
============================================================================= */

$pagetitle = "Event Notification Settings Administration";
$navigation = "administration";
$page_access_requirement = "Admin";
$cache_set = array("current_navigation" => "backend");
include_once "../pem-includes/header.php";

if (isset($datasubmit))
{
   $settings_submit = array(
      "notice_from" => $notice_from,
      "notice_email" => $notice_email,
      "notice_subject" => $notice_subject,
      "notice_submission_notify" => $notice_submission_notify,
      "notice_submission_msg" => $notice_submission_msg,
      "notice_approved_notify" => $notice_approved_notify,
      "notice_approved_msg" => $notice_approved_msg,
      "notice_edited_notify" => $notice_edited_notify,
      "notice_edited_msg" => $notice_edited_msg,
      );
   pem_update_settings($settings_submit);
}

pem_fieldset_begin(__("Event Notification Settings"));
echo '<p>' . __("These settings are used to determine when and how notifications are handled with regard to event creation, approval, and changes.") . "</p>\n";
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

   $notice_email_note = __("(Notifications emailed about events will come from this address.)");
   $notice_from_note = __("(Identify the sender of the emails.)");
   $notice_subject_note = __("(Subject line to use for event-related correspondence.)");
   $notice_submission_notify_note = __("(Sends confirmation email to creator and/or contacts with full event information)");
   $notice_submission_msg_note = __("(Text emailed to creators and/or contacts)");
   $notice_approved_notify_note = __("(Sends notification email to creator and/or contacts when event is approved)");
   $notice_approved_msg_note = __("(Text emailed to creator and/or contacts upon approval)");
   $notice_edited_notify_note = __("(Sends email to creator and/or contacts when event information is updated)");
   $notice_edited_msg_note = __("(Text emailed to creator and/or contacts on change)");

   pem_error_list($error);

   pem_form_begin(array("nameid" => "settingsform", "action" => $PHP_SELF, "class" => "regsettingsform"));
   pem_hidden_input(array("name" => "datasubmit", "value" => 1));

   pem_field_label(array("default" => __("Return Email Name:"), "for" => "notice_from"));
   pem_text_input(array("nameid" => "notice_from", "value" => $notice_from, "size" => 40, "maxlength" => 60));
   pem_field_note(array("default" => $notice_from_note));
   pem_field_label(array("default" => __("Return Email Address:"), "for" => "notice_email"));
   pem_text_input(array("nameid" => "notice_email", "value" => $notice_email, "size" => 40, "maxlength" => 60));
   pem_field_note(array("default" => $notice_email_note));
   pem_field_label(array("default" => __("Email Subject:"), "for" => "notice_subject"));
   pem_text_input(array("nameid" => "notice_subject", "value" => $notice_subject, "size" => 40, "maxlength" => 60));
   pem_field_note(array("default" => $notice_subject_note));

   pem_field_label(array("default" => __("Email New Event Submission:"), "for" => "notice_submission_notify"));
   pem_boolean_select(array("nameid" => "notice_submission_notify", "default" => $notice_submission_notify));
   pem_field_note(array("default" => $notice_submission_notify_note));
   pem_field_label(array("default" => __("Notify Message for New Events:"), "for" => "notice_submission_msg"));
   pem_textarea_input(array("nameid" => "notice_submission_msg", "default" => $notice_submission_msg, "style" => "width:400px; height:100px;"));
   pem_field_note(array("default" => $notice_submission_msg_note));

   pem_field_label(array("default" => __("Email on Event Approval:"), "for" => "notice_approved_notify"));
   pem_boolean_select(array("nameid" => "notice_approved_notify", "default" => $notice_approved_notify));
   pem_field_note(array("default" => $notice_approved_notify_note));
   pem_field_label(array("default" => __("Notify Message for Approved Events:"), "for" => "notice_approved_msg"));
   pem_textarea_input(array("nameid" => "notice_approved_msg", "default" => $notice_approved_msg, "style" => "width:400px; height:100px;"));
   pem_field_note(array("default" => $notice_approved_msg_note));

   pem_field_label(array("default" => __("Email on Event Change:"), "for" => "notice_edited_notify"));
   pem_boolean_select(array("nameid" => "notice_edited_notify", "default" => $notice_edited_notify));
   pem_field_note(array("default" => $notice_edited_notify_note));
   pem_field_label(array("default" => __("Notify Message for Edited Events:"), "for" => "notice_edited_msg"));
   pem_textarea_input(array("nameid" => "notice_edited_msg", "default" => $notice_edited_msg, "style" => "width:400px; height:100px;"));
   pem_field_note(array("default" => $notice_edited_msg_note));

   pem_form_submit("settingsform");
   pem_form_end();
} // END field_defaults_form

?>