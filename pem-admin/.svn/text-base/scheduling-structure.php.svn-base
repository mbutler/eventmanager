<?php
/* ========================== FILE INFORMATION ================================= 
phxEventManager :: scheduling-structure.php

This file provides web-based administration of main phxEventManager settings.
============================================================================= */

$pagetitle = "Scheduling Structure and Boundaries Administration";
$navigation = "administration";
$page_scheduling_requirement = "Admin";
$cache_set = array("current_navigation" => "backend");
include_once "../pem-includes/header.php";

$showaddform = true;
if (isset($datasubmit))
{
   if ($typeid == 1)
   {
      $profile_name = __("Default");
      $date_begin = "2000-01-01";
      $date_end = "2050-01-01";
      $description = __("Default scheduling profile.");
   }
   else
   {
      $date_begin = $date_begin_year . "-" . $date_begin_month . "-" . $date_begin_day;
      $date_end = $date_end_year . "-" . $date_end_month . "-" . $date_end_day;
   }
   for ($i = 0; $i < 7; $i++)
   {
      $open_begin_var = "open_begin_" . $i;
      $begin_hour_var = "open_begin_" . $i . "_hour";
      $begin_minute_var = "open_begin_" . $i . "_minute";
      $begin_meridiem_var = "open_begin_" . $i . "_meridiem";
      $open_end_var = "open_end_" . $i;
      $end_hour_var = "open_end_" . $i . "_hour";
      $end_minute_var = "open_end_" . $i . "_minute";
      $end_meridiem_var = "open_end_" . $i . "_meridiem";
      $open_begin_hour = (isset(${$begin_hour_var})) ? ${$begin_hour_var} : 0;
      $open_begin_minute = (isset(${$begin_minute_var})) ? ${$begin_minute_var} : 0;
      $open_begin_meridiem = (isset(${$begin_meridiem_var})) ? ${$begin_meridiem_var} : "";
      $open_end_hour = (isset(${$end_hour_var})) ? ${$end_hour_var} : 0;
      $open_end_minute = (isset(${$end_minute_var})) ? ${$end_minute_var} : 0;
      $open_end_meridiem = (isset(${$end_meridiem_var})) ? ${$end_meridiem_var} : "";
      ${$open_begin_var} = pem_time($open_begin_hour, $open_begin_minute, $open_begin_meridiem);
      ${$open_end_var} = pem_time($open_end_hour, $open_end_minute, $open_end_meridiem);
   }

   $default_buffer_time = pem_time_quantity_to_real(array("hours" => $default_buffer_time_hours, "minutes" => $default_buffer_time_minutes));
   $default_setup_time = pem_time_quantity_to_real(array("hours" => $default_setup_time_hours, "minutes" => $default_setup_time_minutes));

   $profile = array(
           "limit_book_range" => $limit_book_range,
           "book_range_type" => $book_range_type,
           "book_begin" => $book_begin,
           "book_end" => $book_end,
           "book_advance" => $book_advance,
           "restrict_to_open" => $restrict_to_open,
           "blackout_0" => (isset($blackout_0)) ? 1 : 0,
           "blackout_1" => (isset($blackout_1)) ? 1 : 0,
           "blackout_2" => (isset($blackout_2)) ? 1 : 0,
           "blackout_3" => (isset($blackout_3)) ? 1 : 0,
           "blackout_4" => (isset($blackout_4)) ? 1 : 0,
           "blackout_5" => (isset($blackout_5)) ? 1 : 0,
           "blackout_6" => (isset($blackout_6)) ? 1 : 0,
           "open_begin_0" => $open_begin_0,
           "open_end_0" => $open_end_0,
           "open_begin_1" => $open_begin_1,
           "open_end_1" => $open_end_1,
           "open_begin_2" => $open_begin_2,
           "open_end_2" => $open_end_2,
           "open_begin_3" => $open_begin_3,
           "open_end_3" => $open_end_3,
           "open_begin_4" => $open_begin_4,
           "open_end_4" => $open_end_4,
           "open_begin_5" => $open_begin_5,
           "open_end_5" => $open_end_5,
           "open_begin_6" => $open_begin_6,
           "open_end_6" => $open_end_6,
           "default_buffer_time" => $default_buffer_time,
           "buffer_min" => ($buffer_min) ? $default_buffer_time : "",
           "buffer_overlap" => $buffer_overlap,
           "buffer_inline" => $buffer_inline,
           "default_setup_time" => $default_setup_time,
           "setup_min" => ($setup_min) ? $default_setup_time : "",
           "setup_inline" => $setup_inline,
   );

   unset($error);
   // The error checks are done here with if and not switch because case(empty($x)) returns a false positive
   if (empty($profile_name)) $error[] = __("Profile Name cannot be empty.");

   switch (true)
   {
      case ($datasubmit == "new" AND isset($error)):
         $showaddform = false;
         pem_fieldset_begin(__("Add New Scheduling Profile"));
         echo '<p>' . $error_instructions . "</p>\n";
         echo_form("", $profile_name, $date_begin, $date_end, $description, $status, $profile, "new", $error);
         pem_fieldset_end();
         break;
      case ($datasubmit == "new"):
         $data = array("profile_name" => $profile_name, "date_begin" => $date_begin, "date_end" => $date_end, "description" => $description, "status" => $status, "profile" => serialize($profile));
         $types = array("profile_name" => "text", "date_begin" => "date", "date_end" => "date", "description" => "text", "profile" => "clob", "status" => "text");
         pem_add_row("scheduling_profiles", $data, $types);
         echo '<p><b>' . __("Scheduling Profile added.") . '</b></p>' . "\n";
         break;
      case ($datasubmit == "copy"):
         $showaddform = false;
         pem_fieldset_begin(__("Add New Scheduling Profile"));
         echo '<p>' . __("The new profile form has been populated with copied settings.  Make additional changes as desired and submit the form to create a new profile.") . "</p>\n";
         $data = pem_get_row("id", $typeid, "scheduling_profiles");
         $profile = unserialize($data["profile"]);
         echo_form("", __("New Profile"), "", "", "", $data["status"], $profile, "new");
         pem_fieldset_end();
         break;
      case ($datasubmit == "edit"):
         $showaddform = false;
         pem_fieldset_begin(__("Edit Scheduling Profile"));
         echo '<p>' . __("Change the settings as desired and submit the form to save your changes.") . "</p>\n";
         $data = pem_get_row("id", $typeid, "scheduling_profiles");
         $profile = unserialize($data["profile"]);
         echo_form($data["id"], $data["profile_name"], $data["date_begin"], $data["date_end"], $data["description"], $data["status"], $profile, "update");
         pem_fieldset_end();
         break;
      case ($datasubmit == "update" AND (isset($error) or $hold_submit)):
         $showaddform = false;
         pem_fieldset_begin(__("Edit Scheduling Profile"));
         echo '<p>' . $error_instructions . "</p>\n";
         echo_form($typeid, $profile_name, $date_begin, $date_end, $description, $profile, $status, "update", $error);
         pem_fieldset_end();
         break;
      case ($datasubmit == "update"):
         $data = array("profile_name" => $profile_name, "date_begin" => $date_begin, "date_end" => $date_end, "description" => $description, "profile" => serialize($profile), "status" => $status);
         $where = array("id" => $typeid);
         pem_update_row("scheduling_profiles", $data, $where);
         echo '<p><b>' . __("Scheduling Profile updated.") . '</b></p>' . "\n";
         break;
      case ($datasubmit == "activate"):
         $data = array("status" => "1");
         $where = array("id" => $typeid);
         pem_update_row("scheduling_profiles", $data, $where);
         echo '<p><b>' . __("Scheduling Profile activated globally.") . '</b></p>' . "\n";
         break;
      case ($datasubmit == "deactivate"):
         $data = array("status" => "0");
         $where = array("id" => $typeid);
         pem_update_row("scheduling_profiles", $data, $where);
         echo '<p><b>' . __("Scheduling Profile deactivated globally.") . '</b></p>' . "\n";
         break;
      case ($datasubmit == "delete"):
         $where = array("id" => $typeid);
         pem_delete_recycle("scheduling_profiles", $where);
         echo '<p><b>' . __("Scheduling Profile deleted to recycle.") . '</b></p>' . "\n";
         break;
   }
}

// list current items in the table
pem_fieldset_begin(__("Current Scheduling Profiles"));
echo '<p>' . __("Deactivated profiles are not selectable in user account administration, but accounts currently using a profile that has just been deactivated will still use the profile until the user account is either edited to use a different profile or the account is disabled.  The Administrator and Public profile types are built-in and can not be deleted.") . "</p>\n";

$fields_header =  array(
        __("Begin Date"),
        __("End Date"),
);

echo '<table cellspacing="0" class="datalist">' . "\n";
echo '<tr>' . "\n";
echo '<th style="padding-top:0;"></th>' . "\n";
for ($i = 0; $i < count($fields_header); $i++)
{
   echo '<th style="padding-top:0;">' . $fields_header[$i] . '</th>' . "\n";
}
echo '</tr>' . "\n";
unset($where);
$where["status"] = array("!=", "2");
$list = pem_get_rows("scheduling_profiles", $where);
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
   pem_fieldset_begin(__("Add New Scheduling Profile"));
   echo '<p>' . __("Use the date and weekday boundary options to create limits around when events can be booked.  Buffers are used to prevent unplanned conflicts when events run past their scheduled times or to make it easier for early arrivals.  Setup/cleanup provides events with additional reserved time for organizers that is not advertised on the calendar.") . "</p>\n";
   echo_form();
   pem_fieldset_end();
}
include ABSPATH . PEMINC . "/footer.php";

// =============================================================================
// =========================== LOCAL FUNCTIONS =================================
// =============================================================================


function echo_data($data, $row)
{
   global $date_format, $delete_confirm, $entry_types;
   extract($data);
   $type_text = array_flip($entry_types);

   echo '<tr class="' . $row . '"><td>' . "\n";
   pem_form_begin(array("nameid" => "dataform" . $id, "action" => $PHP_SELF));
   pem_hidden_input(array("name" => "datasubmit", "value" => "edit"));
   pem_hidden_input(array("name" => "typeid", "value" => $id));
   pem_field_label(array("default" => $profile_name));
   pem_form_end();
   echo '</td><td style="padding-left:20px;">' . "\n";
   if ($id == 1) _e("n/a");
   else echo pem_date($date_format, $date_begin);
   echo '</td><td style="padding-left:20px;">' . "\n";
   if ($id == 1) _e("n/a");
   else echo pem_date($date_format, $date_end);
   echo '</td><td class="controlboxwide" rowspan="2">' . "\n";
   if ($id != 1)
   {
      if ($status == "0") $controls[] = array("label" => __("Activate"), "onclick" => "action_submit('dataform" . $id . "', 'activate');");
      else $controls[] = array("label" => __("Deactivate"), "onclick" => "action_submit('dataform" . $id . "', 'deactivate');");
   }
   $controls[] = array("label" => __("Copy to New"), "onclick" => "action_submit('dataform" . $id . "', 'copy');");
   $controls[] = array("label" => __("Edit"), "onclick" => "action_submit('dataform" . $id . "', 'edit');");
   if ($id != 1) $controls[] = array("label" => __("Delete"), "onclick" => "confirm_submit('dataform" . $id . "', 'delete', '" . $delete_confirm . "');");
   pem_controls($controls);
   echo '</td></tr>' . "\n";
   if (!empty($description))
   {
      echo '<tr class="' . $row . '"><td colspan="3">' . "\n";
      echo '<p class="indent" style="text-align:left;">' . $description . '</p>' . "\n";
      echo '</td></tr>' . "\n";
   }
} // END echo_data

function echo_form($id = "", $profile_name = "", $date_begin = "", $date_end = "", $description = "", $status = "", $profile = "", $mode = "new", $error = "")
{
   global $PHP_SELF, $weekday;

   $profile_name_note = __("(The name of the scheduling profile, used for administration only)");
   $date_begin_note = __("(Set when this profile will go into effect)");
   $date_end_note = __("(Set when this profile will end)");
   $description_note = __("(Describe this item for future reference, used for administration only)");
   $status_note = __("(This profile must be active to be checked for scheduling boundary conflicts)");

   $limit_book_range_note = __("(Create boundaries for submission dates)");
   $book_range_type_note = __("(Define begin and end years or how far into the future bookings can be made)");
   $book_begin_note = __("(Select from what year bookings can be made)");
   $book_end_note = __("(Select in what year bookings must finish)");
   $book_advance_note = __("(Number of months in the future when bookings can be made)");
   $restrict_to_open_note = __("(Define the Open Schedule with times set for each day of the week)");
   // $enable_periods_note = __("(If active, periods are an additional optional way to enter times.  Create and name periods through the separate period administration system)");
   // $all_days_note = __("Opening time for all days");
   for ($i = 0; $i < 7; $i++)
   {
      $open_begin_note[$i] = sprintf(__("(Opening time for %s)"), $weekday[$i]);
      $open_end_note[$i] = sprintf(__("(Closing time for %s)"), $weekday[$i]);
   }
   $buffer_time_note = __("(Buffer times exist to space events from one another.  Buffers are placed outside setup/cleanup time and can overlap buffers of a neighboring event.)");
   $default_buffer_time_note = __("(Default time suggested when setting a buffer)");
   $buffer_min_note = __("(If true the default suggestion becomes a required minimum)");
   $buffer_overlap_note = __("(Close-proximity events will share buffer time)");
   $buffer_inline_note = __("(Staff display in same line as event times or on new line)");
   $setup_time_note = __("(Setup times exist to provide setup and/or cleanup time just before or after an event's advertised running time.  Setup times do not overlap.)");
   $default_setup_time_note = __("(Default time suggested when setting additional setup time)");
   $setup_min_note = __("(If true the default suggestion becomes a required minimum)");
   $setup_inline_note = __("(Staff display in same line as event times or on new line)");

   pem_error_list($error);

   pem_form_begin(array("nameid" => "submitform", "action" => $PHP_SELF, "class" => "schedulingform"));
   pem_hidden_input(array("name" => "datasubmit", "value" => $mode));
   pem_hidden_input(array("name" => "typeid", "value" => $id));

   if ($id != 1)
   {
      pem_field_label(array("default" => __("Profile Name:"), "for" => "profile_name", "class" => "profilefields"));
      pem_text_input(array("nameid" => "profile_name", "value" => $profile_name, "size" => 30, "maxlength" => 50));
      pem_field_note(array("default" => $profile_name_note));
      pem_field_label(array("default" => __("Date Begins:"), "for" => "date_begin_hour", "class" => "profilefields"));
      pem_date_selector("date_begin_", array("default" => $date_begin, "onchange" => "ChangeOptionDays(this.form,'date_begin_')"));
      pem_field_note(array("default" => $date_begin_note));
      pem_field_label(array("default" => __("Date Ends:"), "for" => "date_end_hour", "class" => "profilefields"));
      pem_date_selector("date_end_", array("default" => $date_end, "onchange" => "ChangeOptionDays(this.form,'date_end_')"));
      pem_field_note(array("default" => $date_end_note));
      pem_field_label(array("default" => __("Description:"), "for" => "description", "class" => "profilefields"));
      pem_textarea_input(array("nameid" => "description", "default" => $description, "style" => "width:400px; height:100px;"));
      pem_field_note(array("default" => $description_note));
   }
   else
   {
      pem_field_label(array("default" => __("Profile Name:"), "for" => "profile_name", "class" => "profilefields"));
      echo '<div>' . __("Default") . '</div><br />' . "\n";
   }

   pem_field_label(array("default" => __("Active:"), "for" => "status", "style" => "width:auto;"));
   pem_boolean_select(array("nameid" => "status", "default" => $status));
   pem_field_note(array("default" => $status_note));

   echo '<h3 style="margin-top:10px;">' . __("Date Boundaries") . '</h3>';
   echo '<div class="indent">' . "\n";


   pem_field_label(array("default" => __("Limit Booking Dates:"), "for" => "limit_book_range"));
   pem_boolean_select(array("nameid" => "limit_book_range", "default" => $profile["limit_book_range"], "onchange" => "toggleLayer('limitbookings', this.value);"));
   pem_field_note(array("default" => $limit_book_range_note));

   $class = ($limit_book_range) ? "" : ' class="hidden"';
   echo '<div id="limitbookings"' . $class . '>' . "\n";

   pem_field_label(array("default" => __("Bookings Limited To:"), "for" => "book_range_type"));
   pem_book_range_type_select(array("nameid" => "book_range_type", "default" => $profile["book_range_type"], "onchange" => "toggleBookLimit();"));
   pem_field_note(array("default" => $book_range_type_note));

   $class = ($book_range_type == "between") ? "" : ' class="hidden"';
   echo '<div id="limitbyrange"' . $class . '>' . "\n";
   pem_field_label(array("default" => __("Bookings Begin:"), "for" => "book_begin"));
   pem_year_selector(array("nameid" => "book_begin", "default" => $profile["book_begin"]));
   pem_field_note(array("default" => $book_begin_note));
   pem_field_label(array("default" => __("Bookings End:"), "for" => "book_end"));
   pem_year_selector(array("nameid" => "book_end", "default" => $profile["book_end"]));
   pem_field_note(array("default" => $book_end_note));
   echo '</div>' . "\n";


   $class = ($book_range_type == "advance") ? "" : ' class="hidden"';
   echo '<div id="limitbyadvance"' . $class . '>' . "\n";
   pem_field_label(array("default" => __("Book in Advance:"), "for" => "book_advance"));
   pem_text_input(array("nameid" => "book_advance", "value" => $profile["book_advance"], "size" => 5, "maxlength" => 3));
   pem_field_note(array("default" => $book_advance_note));
   echo '</div>' . "\n";

   echo '</div>' . "\n";

   /*
   pem_field_label(array("default" => __("Enable Periods:"), "for" => "enable_periods"));
   pem_boolean_select("enable_periods", $enable_periods);
   pem_field_note(array("default" => $enable_periods_note));
   default_period_length
   */

   echo '</div>' . "\n";
   echo '<h3>' . __("Weekday Boundaries") . '</h3>';
   echo '<div class="indent">' . "\n";
   pem_field_label(array("default" => __("Limit Times to Schedule:"), "for" => "restrict_to_open"));
   pem_boolean_select(array("nameid" => "restrict_to_open", "default" => $profile["restrict_to_open"], "onchange" => "toggleLayer('limittimes', this.value);"));
   pem_field_note(array("default" => $restrict_to_open_note));

   /*
   pem_field_label(array("default" => __("Different Open Times by Day:"), "for" => "restrict_to_open"));
   pem_boolean_select("restrict_to_open", $restrict_to_open);
   pem_field_note(array("default" => $restrict_to_open_note));

   pem_field_label(array("default" => __("Open Begin:"), "for" => "open_begin-"));
   pem_time_selector("open_begin-");
   pem_field_note(array("default" => $open_begin_note . $all_days_note));
   */

   $class = ($restrict_to_open) ? "" : ' class="hidden"';
   echo '<div id="limittimes"' . $class . '>' . "\n";
   for ($i = 0; $i < 7; $i++)
   {
      pem_field_label(array("default" => $weekday[$i], "for" => "open_begin_" . $i . "_hour", "class" => "timeboundary"));
      pem_checkbox(array("nameid" => "blackout_" . $i, "status" => $profile["blackout_" . $i], "onclick" => "toggleLayer('limitday" . $i . "', this.checked == false);", "style" => "float:left;"));
      pem_field_label(array("default" => __("No Scheduling Allowed for this Day"), "for" => "blackout_" . $i));
      echo '<br /><div id="limitday' . $i . '" class="indent">' . "\n";
      pem_field_label(array("default" => __("Open Begin:"), "for" => "open_begin_" . $i . "_hour", "class" => "timeboundary"));
      pem_time_selector("open_begin_" . $i . "_", array("default" => $profile["open_begin_" . $i]));
      pem_field_note(array("default" => $open_begin_note[$i]));
      pem_field_label(array("default" => __("Open End:"), "for" => "open_end_" . $i . "_hour", "class" => "timeboundary"));
      pem_time_selector("open_end_" . $i . "_", array("default" => $profile["open_end_" . $i]));
      pem_field_note(array("default" => $open_end_note[$i]));
      echo '</div>' . "\n";
   }
   echo '</div>' . "\n";

   echo '</div>' . "\n";
   echo '<h3>' . __("Between-Event Buffers") . '</h3>';
   echo '<div class="indent">' . "\n";
   pem_field_label(array("default" => __("Default Buffer Time:"), "for" => "default_buffer_time"));
   pem_time_quantity_selector("default_buffer_time_", array("default" => $profile["default_buffer_time"]));
   pem_field_note(array("default" => $default_buffer_time_note));
   pem_field_label(array("default" => __("Default Buffer is Minimum:"), "for" => "buffer_min"));
   pem_boolean_select(array("name" => "buffer_min", "default" => (!empty($profile["buffer_min"]))));
   pem_field_note(array("default" => $buffer_min_note));
   /*
   pem_field_label(array("default" => __("Allow Buffer Overlap:"), "for" => "buffer_overlap"));
   pem_boolean_select(array("name" => "buffer_overlap", "default" => $profile["buffer_overlap"]));
   pem_field_note(array("default" => $buffer_overlap_note));
   pem_field_label(array("default" => __("Display Buffers Inline:"), "for" => "buffer_inline"));
   pem_boolean_select(array("name" => "buffer_inline", "default" => $profile["buffer_inline"]));
   pem_field_note(array("default" => $buffer_inline_note));
   pem_field_note(array("default" => $buffer_time_note, "style" => "margin:0 0 10px 50px;"));
   */


   echo '</div>' . "\n";
   echo '<h3>' . __("Additional Setup/Cleanup Time") . '</h3>';
   echo '<div class="indent">' . "\n";
   pem_field_label(array("default" => __("Default Setup Time:"), "for" => "default_setup_time"));
   pem_time_quantity_selector("default_setup_time_", array("default" => $profile["default_setup_time"]));
   pem_field_note(array("default" => $default_setup_time_note));
   pem_field_label(array("default" => __("Default Setup is Minimum:"), "for" => "setup_min"));
   pem_boolean_select(array("name" => "setup_min", "default" => (!empty($profile["setup_min"]))));
   pem_field_note(array("default" => $setup_min_note));
   /*
   pem_field_label(array("default" => __("Display Setup Inline:"), "for" => "setup_inline"));
   pem_boolean_select(array("name" => "setup_inline", "default" => $profile["setup_inline"]));
   pem_field_note(array("default" => $setup_inline_note));
   pem_field_note(array("default" => $setup_time_note, "style" => "margin:0 0 10px 50px;"));
   */
   echo '</div>' . "\n";

   if ($mode != "new") pem_form_update("submitform", "cancel");
   elseif ($mode == "new" AND !empty($error)) pem_form_submit("submitform", "cancel");
   else pem_form_submit("submitform");

   echo '<script type="text/javascript"><!--' . "\n";
   echo "toggleLayer('limitbookings', '" . $profile["limit_book_range"] . "');\n";
   echo "toggleBookLimit();\n";
   echo "toggleLayer('limittimes', '" . $profile["restrict_to_open"] . "');\n";
   echo "toggleLayer('limitday0', " . $profile["blackout_0"] . " == false);\n";
   echo "toggleLayer('limitday1', " . $profile["blackout_1"] . " == false);\n";
   echo "toggleLayer('limitday2', " . $profile["blackout_2"] . " == false);\n";
   echo "toggleLayer('limitday3', " . $profile["blackout_3"] . " == false);\n";
   echo "toggleLayer('limitday4', " . $profile["blackout_4"] . " == false);\n";
   echo "toggleLayer('limitday5', " . $profile["blackout_5"] . " == false);\n";
   echo "toggleLayer('limitday6', " . $profile["blackout_6"] . " == false);\n";
   echo '// --></script>' . "\n";

   pem_form_end();
} // END echo_form

?>