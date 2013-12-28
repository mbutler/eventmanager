<?php
/* ========================== FILE INFORMATION =================================
phxEventManager :: reg-settings.php

If registration capability is enabled in entries or dates, and if the option is
selected during event creation, these settings are used to determine how the
registration process is handled.
============================================================================= */

$pagetitle = "Registration Settings Administration";
$navigation = "administration";
$page_access_requirement = "Admin";
$cache_set = array("current_navigation" => "backend");
include_once "../pem-includes/header.php";

if (isset($datasubmit))
{
   $settings_submit = array(
           "reg_contact_meta" => $reg_contact_meta,
           "reg_from" => $reg_from,
           "reg_email" => $reg_email,
           "reg_subject" => $reg_subject,
           "reg_onreg_notify" => $reg_onreg_notify,
           "reg_onreg_msg" => $reg_onreg_msg,
           "reg_onreg_waitmsg" => $reg_onreg_waitmsg,
           "reg_waitlist_notify" => $reg_waitlist_notify,
           "reg_waitlist_msg" => $reg_waitlist_msg,
           "reg_onchange_notify" => $reg_onchange_notify,
           "reg_onchange_msg" => $reg_onchange_msg,
           "reg_remind_when1" => $reg_remind_when1,
           "reg_remind_when2" => $reg_remind_when2,
           "reg_remind_msg" => $reg_remind_msg,
   );
   pem_update_settings($settings_submit);
}

pem_fieldset_begin(__("Registration Settings"));
echo '<p>' . __("If registration capability is enabled in entries or dates, and if the option is selected during event creation, these settings are used to determine how the registration process is handled.") . "</p>\n";
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

   $reg_contact_meta_note = __("(Select from existing contact meta to determine which fields are used for registrations)");
   $reg_from_note = __("(Identify the sender of the emails.)");
   $reg_email_note = __("(Notifications emailed about registrations will come from this address.)");
   $reg_subject_note = __("(Subject line to use for registration-related correspondence.)");
   $reg_onreg_notify_note = __("(Sends confirmation email to registrants with full event information)");
   $reg_onreg_msg_note = __("(Text emailed to successful registrants)");
   $reg_onreg_waitmsg_note = __("(Text emailed to overflow registrants placed on a waitlist)");
   $reg_waitlist_notify_note = __("(Sends email to waitlist registrants when they are moved from waitlist to registration status)");
   $reg_waitlist_msg_note = __("(Text emailed to waitlist registrants)");
   $reg_onchange_notify_note = __("(Sends email to registrants when event information is updated)");
   $reg_onchange_msg_note = __("(Text emailed to registrants on change)");
   $reg_remind_when1_note = __("(How many days before event date to email a reminder to registrants)");
   $reg_remind_when2_note = __("(Set either reminder duration to 0 to disable it)");
   $reg_remind_msg_note = __("(Text emailed to registrants as a reminder)");

   pem_error_list($error);

   pem_form_begin(array("nameid" => "settingsform", "action" => $PHP_SELF, "class" => "regsettingsform"));
   pem_hidden_input(array("name" => "datasubmit", "value" => 1));

   pem_field_label(array("default" => __("Registration Form:"), "for" => "reg_contact_meta"));
   pem_contact_meta_select(array("nameid" => "reg_contact_meta", "default" => $reg_contact_meta));
   pem_field_note(array("default" => $reg_contact_meta_note));
   pem_field_label(array("default" => __("Return Email Name:"), "for" => "reg_from"));
   pem_text_input(array("nameid" => "reg_from", "value" => $reg_from, "size" => 40, "maxlength" => 60));
   pem_field_note(array("default" => $reg_from_note));
   pem_field_label(array("default" => __("Return Email Address:"), "for" => "reg_email"));
   pem_text_input(array("nameid" => "reg_email", "value" => $reg_email, "size" => 40, "maxlength" => 60));
   pem_field_note(array("default" => $reg_email_note));
   pem_field_label(array("default" => __("Email Subject:"), "for" => "reg_subject"));
   pem_text_input(array("nameid" => "reg_subject", "value" => $reg_subject, "size" => 40, "maxlength" => 60));
   pem_field_note(array("default" => $reg_subject_note));
   pem_field_label(array("default" => __("Email Registration Confirmation:"), "for" => "reg_onreg_notify"));
   pem_boolean_select(array("nameid" => "reg_onreg_notify", "default" => $reg_onreg_notify));
   pem_field_note(array("default" => $reg_onreg_notify_note));
   pem_field_label(array("default" => __("Notify Message for Registration:"), "for" => "reg_onreg_msg"));
   pem_textarea_input(array("nameid" => "reg_onreg_msg", "default" => $reg_onreg_msg, "style" => "width:400px; height:100px;"));
   pem_field_note(array("default" => $reg_onreg_msg_note));
   pem_field_label(array("default" => __("Notify Message for Waitlist:"), "for" => "reg_onreg_waitmsg"));
   pem_textarea_input(array("nameid" => "reg_onreg_waitmsg", "default" => $reg_onreg_waitmsg, "style" => "width:400px; height:100px;"));
   pem_field_note(array("default" => $reg_onreg_waitmsg_note));
   pem_field_label(array("default" => __("Email Waitlist-to-Registration Change:"), "for" => "reg_waitlist_notify"));
   pem_boolean_select(array("nameid" => "reg_waitlist_notify", "default" => $reg_waitlist_notify));
   pem_field_note(array("default" => $reg_waitlist_notify_note));
   pem_field_label(array("default" => __("Notify Message for Waitlist Conversion:"), "for" => "reg_waitlist_msg"));
   pem_textarea_input(array("nameid" => "reg_waitlist_msg", "default" => $reg_waitlist_msg, "style" => "width:400px; height:100px;"));
   pem_field_note(array("default" => $reg_waitlist_msg_note));
   pem_field_label(array("default" => __("Email on Event Change:"), "for" => "reg_onchange_notify"));
   pem_boolean_select(array("nameid" => "reg_onchange_notify", "default" => $reg_onchange_notify));
   pem_field_note(array("default" => $reg_onchange_notify_note));
   pem_field_label(array("default" => __("Notify Message on Change:"), "for" => "reg_onchange_msg"));
   pem_textarea_input(array("nameid" => "reg_onchange_msg", "default" => $reg_onchange_msg, "style" => "width:400px; height:100px;"));
   pem_field_note(array("default" => $reg_onchange_msg_note));
   pem_field_label(array("default" => __("Days Before to Send Reminder 1:"), "for" => "reg_remind_when1"));
   pem_text_input(array("nameid" => "reg_remind_when1", "value" => $reg_remind_when1, "size" => 3, "maxlength" => 2));
   pem_field_note(array("default" => $reg_remind_when1_note));
   pem_field_label(array("default" => __("Days Before to Send Reminder 2:"), "for" => "reg_remind_when2"));
   pem_text_input(array("nameid" => "reg_remind_when2", "value" => $reg_remind_when2, "size" => 3, "maxlength" => 2));
   pem_field_note(array("default" => $reg_remind_when2_note));
   pem_field_label(array("default" => __("Notify Reminder Message:"), "for" => "reg_remind_msg"));
   pem_textarea_input(array("nameid" => "reg_remind_msg", "default" => $reg_remind_msg, "style" => "width:400px; height:100px;"));
   pem_field_note(array("default" => $reg_remind_msg_note));

   pem_form_submit("settingsform");
   pem_form_end();
} // END field_defaults_form

?>