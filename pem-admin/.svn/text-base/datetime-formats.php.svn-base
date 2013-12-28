<?php
/* ========================== FILE INFORMATION ================================= 
phxEventManager :: datetime-formats.php

This file provides web-based administration of main phxEventManager settings.
Date and time format scrings are applied globally to all selectors and datetime
data.  The display duration is a default used in lieu of offering seperate 
display begin/end options for every event.   
============================================================================= */

$pagetitle = "Date and Time Formats Administration";
$navigation = "administration";
$page_access_requirement = "Admin";
$cache_set = array("current_navigation" => "backend");
include_once "../pem-includes/header.php";

if (isset($datasubmit))
{
   $settings_submit = array(
           "time_format" => $time_format_submit,
           "date_format" => $date_format_submit,
           "week_begin" => $week_begin,
           "minute_increment" => $minute_increment,
           "view_week_number" => $view_week_number,
           "display_duration" => $display_duration
   );
   pem_update_settings($settings_submit);
   $time_format = $time_format_submit;
   $date_format = $date_format_submit;
   echo '<p><b>' . __("Date and Time Formats have been updated.") . '</b></p>' . "\n";
}

pem_fieldset_begin(__("Date and Time Formats"));
echo '<p>' . __("These localization options are used to build the selectors for entering event information and will determine the display of the data in calendars and listings.") . "</p>\n";
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

   $datetime_format_note = __('(Select from the Common Formats to get examples of time and date formatting strings.  For more information on what options are available, see <a href="http://www.php.net/manual/en/function.date.php">http://www.php.net/manual/en/function.date.php</a>.)');
   $week_begin_note = __("(Shifts all calendars and week references to this day)");
   $minute_increment_note = __("(The amount of time between each option in minute select boxes)");
   $display_duration_note = __("(The length of time to display an entry after it ends, in months)");

   pem_error_list($error);

   pem_form_begin(array("nameid" => "submitform", "action" => $PHP_SELF, "class" => "datetimeform"));
   pem_hidden_input(array("name" => "datasubmit", "value" => 1));
   pem_hidden_input(array("name" => "reloadglobal", "value" => 1));

   pem_field_label(array("default" => __("Time Format:"), "for" => "time_format"));
   pem_text_input(array("nameid" => "time_format_submit", "value" => $time_format, "size" => 20, "maxlength" => 20));
   pem_field_label(array("default" => __("Common Formats:"), "for" => "time_format_select", "class" => "sublabel"));
   echo '<div style="float:left; width:150px;">';
   pem_time_format_select(array("name" => "time_format_select", "onchange" => "setTextValue('time_format_submit', this.value);", "default" => $time_format));
   echo '</div><br />' . "\n";
   pem_field_label(array("default" => __("Date Format:"), "for" => "date_format"));
   pem_text_input(array("nameid" => "date_format_submit", "value" => $date_format, "size" => 20, "maxlength" => 20));
   pem_field_label(array("default" => __("Common Formats:"), "for" => "date_format_select", "class" => "sublabel"));
   echo '<div style="float:left; width:150px;">';
   pem_date_format_select(array("name" => "date_format_select", "onchange" => "setTextValue('date_format_submit', this.value);", "default" => $date_format));
   echo '</div><br />' . "\n";
   echo '<div class="note" style="margin:0 0 20px 50px;">' . $datetime_format_note . '</div><br />' . "\n";
//   pem_field_note(array("default" => $datetime_format_note, "style" => "margin:0 0 20px 50px;"));
   pem_field_label(array("default" => __("Beginning Week Day:"), "for" => "week_begin"));
   pem_weekday_select(array("name" => "week_begin", "default" => $week_begin));
   pem_field_note(array("default" => $week_begin_note));
   pem_field_label(array("default" => __("Minute Increment:"), "for" => "minute_increment"));
   pem_minute_increment_select(array("name" => "minute_increment", "default" => $minute_increment));
   pem_field_note(array("default" => $minute_increment_note));
   pem_field_label(array("default" => __("Display Duration:"), "for" => "view_week_number"));
   pem_text_input(array("nameid" => "display_duration", "value" => $display_duration, "size" => 3, "maxlength" => 3));
   pem_field_note(array("default" => $display_duration_note));

   pem_form_submit("submitform");
   pem_form_end();
} // END echo_form

?>