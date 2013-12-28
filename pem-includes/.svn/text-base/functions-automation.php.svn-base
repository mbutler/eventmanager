<?php
/* ========================== FILE INFORMATION =================================
phxEventManager :: functions-automation.php

Functions that automate the XHTML coding of repeated elements.
============================================================================= */

$field_labels = array(
   // Options Specific to Entries
   "entry_name" => __("Entry Name:"),
   "entry_type" => __("Entry Type:"),
   "entry_category" => __("Category:"),
   "entry_description" => __("Entry Description:"),
   "entry_presenter" => __("Presenter:"),
   "entry_presenter_type" => __("Presenter Type:"),
   "entry_reg_require" => __("Require Registration:"),
   "entry_reg_current" => __("Current Registrants:"),
   "entry_reg_max" => __("Maximum Registrants:"),
   "entry_allow_wait" => __("Allow Waiting List:"),
   "entry_reg_begin" => __("Registration Begins:"),
   "entry_reg_end" => __("Registration Ends:"),
   "entry_open_to_public" => __("Open to the Public:"),
   "entry_visible_to_public" => __("Visible to the Public:"),
   "entry_seats_expected" => __("Attendance Expected:"),
   "entry_upload_image" => __("Upload Image:"),
   "entry_upload_file" => __("Upload File:"),
   "entry_priv_notes" => __("Private Notes:"),
   "entry_cancelled" => __("Entry Cancelled:"),
   // Options Specific to Dates
   "date_begin" => __("Date Begins:"),
   "date_end" => __("Date Ends:"),
   "time_begin" => __("Time Begins:"),
   "time_end" => __("Time Ends:"),
   "setup_time_before" => __("Setup Time Before:"),
   "cleanup_time_after" => __("Cleanup Time After:"),
   "buffer_time_before" => __("Buffer Time Before:"),
   "buffer_time_after" => __("Buffer Time After:"),
   "display_date_begin" => __("Display Date Begins:"),
   "display_date_end" => __("Display Date Ends:"),
   "display_time_begin" => __("Display Time Begins:"),
   "display_time_end" => __("Display Time Ends:"),
   "areas" => __("Areas:"),
   "spaces" => __("Spaces:"),
   "supplies" => __("Supplies:"),
   "date_name" => __("Date Name:"),
   "date_description" => __("Date Description:"),
   "date_category" => __("Category:"),
   "date_presenter" => __("Presenter:"),
   "date_presenter_type" => __("Presenter Type:"),
   "date_reg_require" => __("Require Registration:"),
   "date_reg_current" => __("Current Registrants:"),
   "date_reg_max" => __("Maximum Registrants:"),
   "date_allow_wait" => __("Allow Waiting List:"),
   "date_reg_begin" => __("Registration Begins:"),
   "date_reg_end" => __("Registration Ends:"),
   "date_open_to_public" => __("Open to the Public:"),
   "date_visible_to_public" => __("Visible to the Public:"),
   "date_seats_expected" => __("Attendance Expected:"),
   "date_upload_image" => __("Upload Image:"),
   "date_upload_file" => __("Upload File:"),
   "date_priv_notes" => __("Private Notes:"),
   "date_cancelled" => __("Date Cancelled:"),
   );


// =============================================================================
// ============================ COMMON TEXT ====================================
// =============================================================================

// global confirmation popup for use with delete buttons
// $delete_confirm = "return confirm('" . __("Are you sure you want to delete this entry?") . "');";

// global confirmation popup text for use with delete buttons
$delete_confirm = __("Are you sure you want to delete this entry?");

// global confirmation popup text for use with delete buttons
$delete_confirm_event = __("Are you sure you want to delete this event?  All dates associated with it will also be deleted.");

// global confirmation popup text for use with delete buttons
$delete_confirm_date = __("Are you sure you want to delete this date? The main event entry will not be deleted, and other dates will remain.");

// global confirmation popup text for use with cancel buttons
$cancel_confirm_event = __("Are you sure you want to cancel this event?  All dates associated with it will also be cancelled.");

// global confirmation popup text for use with event conversion buttons
$convert_confirm_date = __("Are you sure you want to convert this event to a date? The main event entry information will be deleted, and ALL dates tied to this event will be moved to the other event.");

// standard message preceeding the list of known errors in a form submission
$error_instructions = __("Please fix the listed error(s) to complete the form.");

// global confirmation popup text for use with recycle buttons
$restore_confirm = __("Are you sure you want to restore this entry?");

// global sidebar text used in calendar and list views
// TODO provide form edit of this variable
$sidebar_text = '' . "\n";




// =============================================================================
// ============================== DATETIME =====================================
// =============================================================================


// Generates a date selection tool using three drop-down select fields.
// Formats are auto-detected to cater to global date format variable.
// Missing date format information results a span of 10 years centered on today.
// $prefix is used to differentiate variable names in the case of multiple calls to this function.
// $limit_begin and $limit_end will reduce the range of options selectable.
function pem_date_selector($prefix, $extras = "", $year_begin = "", $year_end = "")
{
   global $date_format, $month, $month_abbrev;

   if (!isset($minute_increment)) $minute_increment = pem_get_setting("minute_increment");
   if (!empty($extras) AND array_key_exists("default", $extras))
   {
      $default = explode("-",$extras["default"]);
      $default_year = intval($default[0]);
      $default_month = intval($default[1]);
      $default_day = intval($default[2]);
   }
   $short_format = preg_replace('/[^\w]/', '', $date_format);
   $original_onchange = (array_key_exists("onchange", $extras)) ? $extras["onchange"] : "";
   for ($i = 0; $i < strlen($short_format); $i++)
   {
      if (isset($options)) unset($options);
      switch ($short_format[$i])
      {
      case "d":
         $daylimit = (isset($default_month)) ? pem_date("t", mktime(0, 0, 0, $default_month, $default_day, $default_year)) : pem_date("t");
         for ($j = 1; $j <= $daylimit; $j++)
         {
            $index = $j;
//            $index = zeropad($j, 2);
//            $options[$index] = zeropad($j, 2);
            $options[$index] = $j;
         }
         $extras["default"] = (isset($default_day)) ? $default_day : "";
         $extras["nameid"] = $prefix . "day";
         break;
      case "j":
         $daylimit = (isset($default_month)) ? pem_date("t", mktime(0, 0, 0, $default_month, $default_day, $default_year)) : pem_date("t");
         for ($j = 1; $j <= $daylimit; $j++) { $options[$j] = zeropad($j, 2); }
         $extras["default"] = (isset($default_day)) ? $default_day : "";
         $extras["nameid"] = $prefix . "day";
         break;
      case "F":
         $options = array_flip($month);
         $extras["default"] = (isset($default_month)) ? $default_month : "";
         $extras["nameid"] = $prefix . "month";
         break;
      case "m":
         for ($j = 1; $j <= 12; $j++)
         {
            $index = zeropad($j, 2);
            $options[$index] = zeropad($j, 2);
         }
         $extras["default"] = (isset($default_month)) ? $default_month : "";
         $extras["nameid"] = $prefix . "month";
         break;
      case "M":
         for ($j = 1; $j <= 12; $j++) { $options[$month_abbrev[$month[$j]]] = zeropad($j, 2); }
         $extras["default"] = (isset($default_month)) ? $default_month : "";
         $extras["nameid"] = $prefix . "month";
         break;
      case "n":
         for ($j = 1; $j <= 12; $j++) { $options[$j] = zeropad($j, 2); }
         $extras["default"] = (isset($default_month)) ? $default_month : "";
         $extras["nameid"] = $prefix . "month";
         break;
      case "Y":
         if (empty($year_begin)) $year_begin = pem_date("Y") - 5;
         if (empty($year_end)) $year_end = pem_date("Y") + 5;;
         for ($j = $year_begin; $j <= $year_end; $j++) { $options[$j] = $j; }
         $extras["default"] = $default_year;
         // $extras["default"] = (isset($default_year)) ? pem_date("Y", mktime(0, 0, 0, $default_year, 1, 2007)) : "";
         $extras["nameid"] = $prefix . "year";
         break;
      case "y":
         if (empty($year_begin)) $year_begin = pem_date("Y") - 5;
         if (empty($year_end)) $year_end = pem_date("Y") + 5;;
         for ($j = $year_begin; $j <= $year_end; $j++) { $options[substr($j, 2, 2)] = $j; }
         $extras["default"] = $default_year;
         // $extras["default"] = (isset($default_year)) ? pem_date("Y", mktime(0, 0, 0, $default_year, 1, 2007)) : "";
         $extras["nameid"] = $prefix . "year";
         break;
      }
//      if (!empty($original_onchange)) $extras["onchange"] = "ChangeOptionDays(this.form,'$prefix'); " . $original_onchange;
//      else $extras["onchange"] = "ChangeOptionDays(this.form,'$prefix');";
      pem_select($options, $extras);
   }
} // END pem_date_selector


// Generates a year selection tool using a drop-down select field.
// $limit_begin and $limit_end will reduce the range of options selectable.
function pem_year_selector($extras = "", $year_begin = "", $year_end = "")
{
   if (empty($year_begin)) $year_begin = pem_date("Y") - 5;
   if (empty($year_end)) $year_end = pem_date("Y") + 5;;
   for ($i = $year_begin; $i <= $year_end; $i++) { $options[$i] = $i; }
   pem_select($options, $extras);
} // END pem_year_selector

// Generates a time selection tool using muliple drop-down select fields.
// Formats are auto-detected to cater to global time format variable.
// Missing time format information results in standard US layout.
// $prefix is used to differentiate variable names in the case of multiple calls to this function.
function pem_time_selector($prefix, $extras = "")
{
   global $time_format, $minute_increment, $meridiem;
   if (!isset($minute_increment)) $minute_increment = pem_get_setting("minute_increment");
   if (!empty($extras) AND array_key_exists("default", $extras))
   {
      $default = explode(":",$extras["default"]);
      $default_hour = intval($default[0]);
      $default_minute = intval($default[1]);
      $default_second = intval($default[2]);
   }
   $short_format = preg_replace('/[^.\w]/', '', $time_format);
   for ($i = 0; $i < strlen($short_format); $i++)
   {
      if (isset($options)) unset($options);
      switch ($short_format[$i])
      {
      case "a":
         $options = (!empty($short_format[$i+1]) AND $short_format[$i+1] == ".") ? array($meridiem["a.m."] => "am", $meridiem["p.m."] => "pm") : array($meridiem["am"] => "am", $meridiem["pm"] => "pm");
         $extras["default"] = (isset($default_hour) AND $default_hour < 12) ? $meridiem["am"] : $meridiem["pm"];
         $extras["nameid"] = $prefix . "meridiem";
         if (!empty($extras) AND array_key_exists("hidden", $extras))
         {
            $extras["value"] = $extras["default"];
            pem_hidden_input($extras);
         }
         else pem_select($options, $extras);
         break;
      case "A":
         $options = (!empty($short_format[$i+1]) AND $short_format[$i+1] == ".") ? array($meridiem["A.M."] => "am", $meridiem["P.M."] => "pm") : array($meridiem["AM"] => "am", $meridiem["PM"] => "pm");
         $extras["default"] = (isset($default_hour) AND $default_hour < 12) ? $meridiem["am"] : $meridiem["pm"];
         $extras["nameid"] = $prefix . "meridiem";
         if (!empty($extras) AND array_key_exists("hidden", $extras))
         {
            $extras["value"] = $extras["default"];
            pem_hidden_input($extras);
         }
         else pem_select($options, $extras);
         break;
      case "g":
         $options = array("1" => 1, "2" => 2, "3" => 3, "4" => 4, "5" => 5, "6" => 6, "7" => 7, "8" => 8, "9" => 9, "10" => 10, "11" => 11, "12" => 12);
         $extras["default"] = (isset($default_hour)) ? $default_hour : "";
         if ($extras["default"] > 12) $extras["default"] -= 12;
         $extras["nameid"] = $prefix . "hour";
         if (!empty($extras) AND array_key_exists("hidden", $extras))
         {
            $extras["value"] = $extras["default"];
            pem_hidden_input($extras);
         }
         else pem_select($options, $extras);
         break;
      case "G":
         $options = array("0" => 0, "1" => 1, "2" => 2, "3" => 3, "4" => 4, "5" => 5, "6" => 6, "7" => 7, "8" => 8, "9" => 9, "10" => 10, "11" => 11, "12" => 12, "13" => 13, "14" => 14, "15" => 15, "16" => 16, "17" => 17, "18" => 18, "19" => 19, "20" => 20, "21" => 21, "22" => 22, "23" => 23, "24" => 24);
         $extras["default"] = (isset($default_hour)) ? $default_hour : "";
         $extras["nameid"] = $prefix . "hour";
         if (!empty($extras) AND array_key_exists("hidden", $extras))
         {
            $extras["value"] = $extras["default"];
            pem_hidden_input($extras);
         }
         else pem_select($options, $extras);
         break;
      case "h":
         $options = array("01" => 1, "02" => 2, "03" => 3, "04" => 4, "05" => 5, "06" => 6, "07" => 7, "08" => 8, "09" => 9, "10" => 10, "11" => 11, "12" => 12);
         $extras["default"] = (isset($default_hour)) ? $default_hour : "";
         if ($extras["default"] > 12) $extras["default"] -= 12;
         if ($extras["default"] < 10) $extras["default"] = "0" . $extras["default"];
         $extras["nameid"] = $prefix . "hour";
         if (!empty($extras) AND array_key_exists("hidden", $extras))
         {
            $extras["value"] = $extras["default"];
            pem_hidden_input($extras);
         }
         else pem_select($options, $extras);
         break;
      case "H":
         $options = array("00" => 0, "01" => 1, "02" => 2, "03" => 3, "04" => 4, "05" => 5, "06" => 6, "07" => 7, "08" => 8, "09" => 9, "10" => 10, "11" => 11, "12" => 12, "13" => 13, "14" => 14, "15" => 15, "16" => 16, "17" => 17, "18" => 18, "19" => 19, "20" => 20, "21" => 21, "22" => 22, "23" => 23, "24" => 24);
         $extras["default"] = (isset($default_hour)) ? $default_hour : "";
         $extras["nameid"] = $prefix . "hour";
         if (!empty($extras) AND array_key_exists("hidden", $extras))
         {
            $extras["value"] = $extras["default"];
            pem_hidden_input($extras);
         }
         else pem_select($options, $extras);
         break;
      case "i":
         for($j = 0; $j <= 55; $j += $minute_increment)
         {
            $index = ($j < 10) ? "0$j" : "$j";
            $options[$index] = $j;
         }
         $extras["default"] = (isset($default_minute)) ? $default_minute : "";
         $extras["nameid"] = $prefix . "minute";
         if (!empty($extras) AND array_key_exists("hidden", $extras))
         {
            $extras["value"] = $extras["default"];
            pem_hidden_input($extras);
         }
         else pem_select($options, $extras);
         break;
      case "s":
         for($j = 0; $j <= 59; $j++)
         {
            $index = ($j < 10) ? "0$j" : "$j";
            $options[$index] = $j;
         }
         $extras["default"] = (isset($default_second)) ? $default_second : "";
         $extras["nameid"] = $prefix . "second";
         if (!empty($extras) AND array_key_exists("hidden", $extras))
         {
            $extras["value"] = $extras["default"];
            pem_hidden_input($extras);
         }
         else pem_select($options, $extras);
         break;
      }
   }
} // END pem_time_selector

// Generates a time quantity selection tool using two drop-down select fields.
// $prefix is used to differentiate variable names in the case of multiple calls to this function.
function pem_time_quantity_selector($prefix, $extras = "")
{
   global $time_format, $minute_increment;

   if (!isset($minute_increment)) $minute_increment = pem_get_setting("minute_increment");
   $hour_begin = 0;
   $hour_end = 23;
   $minute_begin = 0;
   $minute_end = 59;
   if (!empty($extras) AND isset($extras["default"]))
   {
      $default = pem_real_to_time_quantity($extras["default"]);
   }
   for ($i = $hour_begin; $i <= $hour_end; $i++)
   {
      $hour_text = (substr_count(strtolower($time_format), "h") > 0) ? zeropad($i, 2) : $i;
      $options[$hour_text] = $i;
   }
   $extras["default"] = (isset($default["hours"])) ? $default["hours"] : "";
   $extras["name"] = $prefix . "hours";
   pem_select($options, $extras);
   pem_field_label(array("default" => __("Hours"), "for" => $extras["name"], "class" => "sublabel", "style" => "width:auto;"));
   unset($options);
   for($j = $minute_begin; $j <= 55; $j += $minute_increment)
   {
      $index = zeropad($j, 2);
      $options[$index] = $j;
   }
   $extras["default"] = (isset($default["minutes"])) ? $default["minutes"] : "";
   $extras["name"] = $prefix . "minutes";
   $extras["style"] = "margin-left:15px;";
   pem_select($options, $extras);
   pem_field_label(array("default" => __("Minutes"), "for" => $extras["name"], "class" => "sublabel"));
} // END pem_time_quantity_selector


// echos a select of time format examples.
// $extras must contatin either name, id, or nameid to identify the field.
// $target is the name of the field that will be given the selection.
// $default value allows the optional selection of a preferred style.
function pem_time_format_select($extras = "")
{
   extract(pem_field_extras($extras));
   $common_formats = array("g:i a", "g:i a.", "h:i a", "g:i A", "h:i A", "G:i", "H:i");
   if (empty($default)) $options[__("Select for Example")] = "";
   for ($i = 0; $i < count($common_formats); $i++)
   {
     $options[pem_date($common_formats[$i], mktime(9, 5, 2, 7, 1, 2007)) . ', ' . pem_date($common_formats[$i], mktime(15, 8, 5, 7, 1, 2007))] = $common_formats[$i];
   }
   pem_select($options, $extras);
}

// echos a select of date format examples.
// $extras must contatin either name, id, or nameid to identify the field.
// $target is the name of the field that will be given the selection.
// $default value allows the optional selection of a preferred style.
function pem_date_format_select($extras = "")
{
   extract(pem_field_extras($extras));
   $common_formats = array("F j, Y", "m-d-y", "M j", "j F Y", "d F Y");
   if (empty($default)) $options[__("Select for Example")] = "";
   for ($i = 0; $i < count($common_formats); $i++)
   {
     $options[pem_date($common_formats[$i], mktime(9, 5, 2, 7, 1, 2007))] = $common_formats[$i];
   }
   pem_select($options, $extras);
}

// returns a select of weekday names with localized language.
// $extras must contatin either name, id, or nameid to identify the field.
// $default value allows the optional selection of a default day.
function pem_weekday_select($extras = "")
{
   global $weekday;
   $options = array_flip($weekday);
   if (empty($extras) OR !array_key_exists("default", $extras)) $extras["default"] = 0;
   pem_select($options, $extras);
}

// echos a select of 5, 10, 15, and 30-minute increment options.
// $name is required to define the form element.
// $default value allows the optional selection of a preferred increment.
function pem_minute_increment_select($extras = "")
{
   $options = array("5" => 5, "10" => 10, "15" => 15, "30" => 30);
   if (empty($extras) OR !array_key_exists("default", $extras)) $extras["default"] = 5;
   pem_select($options, $extras);
}


function pem_period_length_select($extras = "")
{
   $options = array("5" => 5, "10" => 10, "15" => 15, "30" => 30);
   if (empty($extras) OR !array_key_exists("default", $extras)) $extras["default"] = 5;
   pem_select($options, $extras);
}



function timestamp_line($what, $who, $when, $type = "")
{
   global $date_format, $time_format;

   if (!empty($who))
   {
      if (empty($type)) echo '<b>' . $what . '</b> '. __("by") . ' ' . $who . ' ' . __("on") . ' ' . pem_date($date_format . ', ' . $time_format, $when) . '<br />' . "\n";
      else echo '<b>' . $type . ' ' . $what . '</b> '. __("by") . ' ' . $who . ' ' . __("on") . ' ' . pem_date($date_format . ', ' . $time_format, $when) . '<br />' . "\n";
   }
   else
   {
      if (empty($type)) echo '<b>' . __("Not") . ' ' . $what . '</b><br />' . "\n";
      else echo '<b>' . $type . ' ' . __("Not") . ' ' . $what . '</b><br />' . "\n";
   }
}



// =============================================================================
// =========================== ORGANIZATION  ===================================
// =============================================================================


// echos a simple header for short pages.
function pem_simple_header($show_logo = false)
{
   global $pem_url, $pem_title, $title_img;

   if ($show_logo)
   {
      $ret = '<div id="header-simple" style="height:20px;">&nbsp;</div>' . "\n";
      $ret .= '<div id="header-simple" style="background-color:#FFF; border-bottom:0;">' . "\n";
      $ret .= '<a href="http://phxeventmanager.com/"><img src="' . $pem_url . 'pem-logo.png" style="width:500px;" /></a>';
      $ret .= '</div>' . "\n";
   }
   else
   {
      $title = (!isset($pem_title) OR empty($pem_title)) ? "phxEventManager" : $pem_title;
      $ret = '<div id="header-simple">' . "\n" . '<div id="header-title">';
      if (isset($pem_url) AND !empty($pem_url)) $ret .= '<a href="' . $pem_url . '" title="' . $pem_title . '">';
      if (isset($title_img) AND isset($pem_url) AND !empty($title_img) AND !empty($pem_url))
      {
         $ret .= '<img src="' . $pem_url . 'pem-images/' . $title_img . '" />';
      }
      else $ret .= $title ;
      if (isset($pem_url) AND !empty($pem_url)) $ret .= '</a>' . "\n";
      $ret .= '</div>' . "\n" . '</div>' . "\n";
   }
   $ret .= '<div id="content-simple">' . "\n";
   echo $ret;
} // END pem_simple_header




// echos a simple fieldset opener.
function pem_fieldset_begin($legend, $extras = "")
{
   if (!empty($extras) AND is_array($extras))
   {
      $class = (array_key_exists("class", $extras)) ? ' class="' . $extras["class"] . '"' : "";
      $style = (array_key_exists("style", $extras)) ? ' style="' . $extras["style"] . '"' : "";
   }
   else
   {
      $class = "";
      $style = "";
   }
   $ret = '<fieldset' . $class . $style . '>' . "\n";
   $ret .= '<legend>' . $legend . '</legend>' . "\n";
   echo $ret;
} // END pem_fieldset_begin

// echos a simple fieldset closer.
function pem_fieldset_end()
{
   $ret = '</fieldset>' . "\n";
   echo $ret;
} // END pem_fieldset_end

// echos a simple name anchor.
function pem_anchor($name)
{
   $ret = '<a name="anchor' . $name . '" id="anchor' . $name . '"></a>' . "\n";
   echo $ret;
} // END pem_anchor



// =============================================================================
// =============================== BUTTONS =====================================
// =============================================================================

// returns the onchange xajax call for form submission.
function pem_xajax_onchange($form, $function)
{
   return "xajax_" . $function . "(xajax.getFormValues('" . $form . "'), xajax.$('errorbox').innerHTML); return false;";
}

// echos an xajax form submission script.
function pem_xajax_submit($form, $function, $label = "")
{
   if ($label == "cancel")
   {
      global $PHP_SELF;
      $cancelbutton = '<li><a href="' . $PHP_SELF . '"><span>' . __("Cancel") . '</span></a></li>' . "\n";
      $label = __("Submit");
   }
   elseif (empty($label)) $label = __("Submit");
   $ret = '<ul class="formsubmit">' . "\n";
   $ret .= '<li><a href="javascript:void(null);" onclick="xajax_' . $function . '(xajax.getFormValues(\'' . $form . '\')); return false;"><span>' . $label . '</span></a></a></li>' . "\n";
   if (isset($cancelbutton)) $ret .= $cancelbutton;
   $ret .= '</ul>' . "\n";
   echo $ret;
}

// echos a simple form submission script.
function pem_form_submit($form, $label = "", $centerthis = "")
{
   $actionadd = "";
   if ($label == "cancel")
   {
      global $PHP_SELF;
      $cancelbutton = '<li><a href="' . $PHP_SELF . '"><span>' . __("Cancel") . '</span></a></li>' . "\n";
      $label = __("Submit");
   }
   elseif ($label == "finish")
   {
      $label = __("Submit");
      $actionadd = "document." . $form . ".datasubmit.value = 'finish'; ";
   }
   elseif (empty($label)) $label = __("Submit");
   $ret = "";
   $ret .= '<ul class="formsubmit">' . "\n";
   $ret .= '<li><a href="javascript:' . $actionadd . 'document.' . $form . '.submit();"><span>' . $label . '</span></a></li>' . "\n";
   if (isset($cancelbutton)) $ret .= $cancelbutton;
   $ret .= '</ul>' . "\n";
   echo $ret;
}

// echos a simple form submission script.
function pem_header_submit($form, $label = "")
{
   if (empty($label)) $label = __("Submit");
   $ret = '<ul class="headersubmit">' . "\n";
   $ret .= '<li><a href="javascript:document.' . $form . '.submit();"><span>' . $label . '</span></a></li>' . "\n";
   $ret .= '</ul>' . "\n";
   echo $ret;
}

// echos a simple form submission script.
function pem_form_update($form, $label = "", $class = "formupdate")
{
   if ($label == "cancel")
   {
      global $PHP_SELF;
      $cancelbutton = '<li><a href="' . $PHP_SELF . '"><span>' . __("Cancel") . '</span></a></li>' . "\n";
      $label = __("Update");
   }
   elseif (empty($label)) $label = __("Update");
   else $label = sprintf(__("Update %s"), $label);
   $ret = '<ul class="' . $class . '">' . "\n";
   $ret .= '<li><a href="javascript:document.' . $form . '.submit();"><span>' . $label . '</span></a></li>' . "\n";
   if (isset($cancelbutton)) $ret .= $cancelbutton;
   $ret .= '</ul>' . "\n";
   echo $ret;
}

// echos a simple name anchor.
function pem_visible_toggle($div, $label, $target = "")
{
   $full_label = sprintf(__("Toggle %s"), $label);
   if (!empty($target)) $target = "anchor" . $target;
   $ret = '<li><a href="#' . $target . '" onclick="toggleVisible(\'' . $div . '\');"><span>' . $full_label . '</span></a></li>' . "\n";
   echo $ret;
}

// echos one or more control buttons based on the keyed array $data.
// valid keys: label, onclick, target
function pem_controls($data, $large = false, $left = false)
{
   $class = ($large) ? "controlslg" : "controls";
   if ($left) $class = "controlsleft";
   $ret = '<ul class=' . $class . '>' . "\n";
   if (array_key_exists("label", $data) OR array_key_exists("onclick", $data) OR array_key_exists("link", $data)) // single item
   {
      if (!array_key_exists("label", $data)) $data["label"] .= __("Toggle");
      if (array_key_exists("target", $data)) $target = ' href="#anchor' . $data["target"] . '"';
      if (array_key_exists("link", $data)) $link = ' href="' . $data["link"] . '"';
      if (array_key_exists("onclick", $data)) $onclick = ' onclick="' . $data["onclick"] . '"';
      $ret .= '<li><a' . $target . $link . $onclick . '><span>' . $data["label"] . '</span></a></li>' . "\n";
   }
   else // array of items
   {
      for ($i = 0; $i < count($data); $i++)
      {
         if (!array_key_exists("label", $data[$i])) $data[$i]["label"] .= __("Toggle");
         if (array_key_exists("target", $data[$i])) $target = ' href="#anchor' . $data[$i]["target"] . '"';
         if (array_key_exists("link", $data[$i])) $link = ' href="' . $data[$i]["link"] . '"';
         if (array_key_exists("onclick", $data[$i])) $onclick = ' onclick="' . $data[$i]["onclick"] . '"';
         $ret .= '<li><a' . $target . $link . $onclick . '><span>' . $data[$i]["label"] . '</span></a></li>' . "\n";
      }
   }
   $ret .= '</ul>' . "\n";
   echo $ret;
} // END pem_controls

// echos a button link back to referrer.
function pem_go_back()
{
   global $HTTP_REFERER;

   $label = __("Return to the Previous Page");
   $link = (isset($HTTP_REFERER)) ? $HTTP_REFERER : "javascript:history.back();";
   pem_button_link($label, $link);
}


// echos a button link
function pem_button_link($label, $link)
{
   $ret = '<ul class="buttonlink">' . "\n";
   $ret .= '<li><a href="' . $link . '"><span>' . $label . '</span></a></li>' . "\n";
   $ret .= '</ul>' . "\n";
   echo $ret;
}





// echos a a button for submission and a button t
function pem_submit_template($form, $label = "")
{
   $actionadd = "";
   if ($label == "cancel")
   {
      global $PHP_SELF;
      $cancelbutton = '<li><a href="' . $PHP_SELF . '"><span>' . __("Cancel") . '</span></a></li>' . "\n";
      $label = __("Submit");
   }
   elseif ($label == "finish")
   {
      $label = __("Submit");
      $actionadd = "document." . $form . ".datasubmit.value = 'finish'; ";
   }
   elseif (empty($label)) $label = __("Submit");
   $ret = '<ul class="formsubmit">' . "\n";
   $ret .= '<li><a href="javascript:' . $actionadd . 'document.' . $form . '.submit();"><span>' . $label . '</span></a></li>' . "\n";
   if (isset($cancelbutton)) $ret .= $cancelbutton;

 //  $ret .= '<li><a href="#" onclick="xajax_save_template(xajax.getFormValues(\'submitform\'));"><span>' . __("Save as Template") . '</span></a></li>' . "\n";
 //  $ret .= '<li><a href="#" onclick="xajax_load_template(xajax.getFormValues(\'submitform\'));"><span>' . __("Load from Template") . '</span></a></li>' . "\n";
   $ret .= '</ul>' . "\n";
   echo $ret;
}

// echos a simple form submission script.
function pem_submit_filters($form, $label = "")
{
   if ($label == "cancel")
   {
      global $PHP_SELF;
      $cancelbutton = '<li><a href="' . $PHP_SELF . '"><span>' . __("Cancel") . '</span></a></li>' . "\n";
      $label = __("Submit");
   }
   elseif (empty($label)) $label = __("Submit");
   $ret = '<ul class="formsubmit">' . "\n";
   $ret .= '<li><a href="#" onclick="javascript:parent.xajax_set_filters(xajax.getFormValues(\'submitform\'));"><span>' . $label . '</span></a></li>' . "\n";
   //if (isset($cancelbutton)) $ret .= $cancelbutton;
   $ret .= '</ul>' . "\n";
   echo $ret;
}



// =============================================================================
// =========================== FORM FIELDS =====================================
// =============================================================================


function pem_field_extras($extras)
{
   if (!empty($extras) AND is_array($extras))
   {
      $ret["name"] = (array_key_exists("name", $extras)) ? ' name="' . $extras["name"] . '"' : "";
      $ret["id"] = (array_key_exists("id", $extras)) ? ' id="' . $extras["id"] . '"' : "";
      $ret["nameid"] = (array_key_exists("nameid", $extras)) ? ' name="' . $extras["nameid"] . '" id="' . $extras["nameid"] . '"' : "";
      if (array_key_exists("noparse", $extras))
      {
         $ret["default"] = (array_key_exists("default", $extras)) ? $extras["default"] : "";
         $ret["value"] = (array_key_exists("value", $extras)) ? ' value="' . $extras["value"] . '"' : "";
      }
      else
      {
         $ret["default"] = (array_key_exists("default", $extras)) ? htmlspecialchars($extras["default"]) : "";
         $ret["value"] = (array_key_exists("value", $extras)) ? ' value="' . htmlspecialchars($extras["value"]) . '"' : "";
      }
      $ret["status"] = (array_key_exists("status", $extras)) ? $extras["status"] : false;
      $ret["class"] = (array_key_exists("class", $extras)) ? ' class="' . $extras["class"] . '"' : "";
      $ret["style"] = (array_key_exists("style", $extras)) ? ' style="' . $extras["style"] . '"' : "";
      $ret["tabindex"] = (array_key_exists("tabindex", $extras)) ? ' tabindex="' . $extras["tabindex"] . '"' : "";
      $ret["method"] = (array_key_exists("method", $extras)) ? ' method="' . $extras["method"] . '"' : ' method="post"';
      $ret["action"] = (array_key_exists("action", $extras)) ? ' action="' . $extras["action"] . '"' : "";
      $ret["for"] = (array_key_exists("for", $extras)) ? ' for="' . $extras["for"] . '"' : "";
      $ret["size"] = (array_key_exists("size", $extras)) ? ' size="' . $extras["size"] . '"' : "";
      $ret["maxlength"] = (array_key_exists("maxlength", $extras)) ? ' maxlength="' . $extras["maxlength"] . '"' : "";
      $ret["cols"] = (array_key_exists("cols", $extras)) ? ' cols="' . $extras["cols"] . '"' : "";
      $ret["rows"] = (array_key_exists("rows", $extras)) ? ' rows="' . $extras["rows"] . '"' : "";
      $ret["onchange"] = (array_key_exists("onchange", $extras)) ? ' onchange="' . $extras["onchange"] . '"' : "";
      $ret["onclick"] = (array_key_exists("onclick", $extras)) ? ' onclick="' . $extras["onclick"] . '"' : "";
      $ret["onsubmit"] = (array_key_exists("onsubmit", $extras)) ? ' onsubmit="' . $extras["onsubmit"] . '"' : "";
      $ret["radiofor"] = (array_key_exists("name", $extras)) ? $extras["name"] : (array_key_exists("nameid", $extras)) ? $extras["nameid"] : "";
      $title = (array_key_exists("title", $extras)) ? ' title="' . $extras["title"] . '"' : "";
      $linkclass = (array_key_exists("linkclass", $extras)) ? ' class="' . $extras["linkclass"] . '"' : "";
      $ret["link"] = (array_key_exists("link", $extras)) ? '<a href="' . $extras["link"] . '"' . $title . $linkclass . '>' : "";
      $ret["required"] = (array_key_exists("required", $extras) AND $extras["required"]) ? '<span class="requirednote">' . __("(Required)") . '</span> ' : "";
      $ret["enctype"] = (isset($extras["enctype"])) ? ' enctype="' . $extras["enctype"] . '"' : "";
      $ret["showprompt"] = (isset($extras["showprompt"])) ? true : false;
      $ret["linebreak"] = (isset($extras["linebreak"])) ? $extras["linebreak"] : true;
      $ret["hidden"] = (isset($extras["hidden"])) ? $extras["hidden"] : false;
   }
   else
   {
      $ret["name"] = "";
      $ret["id"] = "";
      $ret["default"] = "";
      $ret["value"] = "";
      $ret["status"] = false;
      $ret["class"] = "";
      $ret["style"] = "";
      $ret["tabindex"] = "";
      $ret["method"] = ' method="post"';
      $ret["action"] = "";
      $ret["for"] = "";
      $ret["size"] = "";
      $ret["maxlength"] = "";
      $ret["cols"] = "";
      $ret["rows"] = "";
      $ret["onchange"] = "";
      $ret["onclick"] = "";
      $ret["onsubmit"] = "";
      $ret["radiofor"] = "";
      $ret["link"] = "";
      $ret["required"] = "";
      $ret["enctype"] = "";
      $ret["showprompt"] = false;
      $ret["linebreak"] = true;
      $ret["hidden"] = false;
   }
   return $ret;
}

// echos a simple form opener.
// $extras must contatin either name, id, or nameid to identify the form.
// $extras must contatin an action for the form to perform it on submit.
// if a method is not defined in $extras it defaults to post.
function pem_form_begin($extras = "")
{
   extract(pem_field_extras($extras));
   $ret = '<form' . $name . $id . $nameid . $enctype . $method . $action . $onsubmit . $class . $style . '>' . "\n";
   echo $ret;
}

// echos a simple form closer.
function pem_form_end()
{
   $ret = '</form>' . "\n";
   echo $ret;
}

// echos a simple field label.
// $extras must contatin either name, id, or nameid to identify the field.
function pem_field_label($extras = "")
{
   extract(pem_field_extras($extras));
   $ret = '<label' . $for . $class . $style . $tabindex . '>' . $default . '</label>' . "\n";
   echo $ret;
}

// echos a simple span.
// $extras must contatin either name, id, or nameid to identify the field.
function pem_span($extras = "")
{
   extract(pem_field_extras($extras));
   $ret = '<span' . $class . $style . $tabindex . '>' . $default . '</span>' . "\n";
   echo $ret;
}

// echos a simple field note.
// $extras must contatin either name, id, or nameid to identify the field.
function pem_field_note($extras = "")
{
   extract(pem_field_extras($extras));
   if (empty($default) AND empty($required)) $ret = "<br />\n";
   else
   {
      $ret = '<div class="note"' . $style . '>' . $required;
      if (!empty($link)) $ret .= $link . $default . '</a>';
      else $ret .= $default;
      $ret .= '</div>';
      if ($linebreak) $ret .= '<br />';
      $ret .= "\n";
   }
   echo $ret;
}

// echos a simple hidden input.
// $extras must contatin either name, id, or nameid to identify the field.
function pem_hidden_input($extras = "")
{
   extract(pem_field_extras($extras));
   $ret = '<input type="hidden"' . $name . $id . $nameid . $value . ' />' . "\n";
   echo $ret;
}

// echos a simple text input.
// $extras must contatin either name, id, or nameid to identify the field.
function pem_text_input($extras = "")
{
   extract(pem_field_extras($extras));
   $ret = '<input' . $name . $id . $nameid . ' type="text"' . $size . $maxlength;
   $ret .= $class . $style . $value . $onchange . $tabindex . ' />' . "\n";
   echo $ret;
}

// echos a simple password input.
// $extras must contatin either name, id, or nameid to identify the field.
function pem_password_input($extras = "")
{
   extract(pem_field_extras($extras));
   $ret = '<input' . $name . $id . $nameid . ' type="password"' . $size . $maxlength;
   $ret .= $class . $style . $value . $onchange . $tabindex . ' />' . "\n";
   echo $ret;
}

// echos a simple textarea input.
// $extras must contatin either name, id, or nameid to identify the field.
function pem_textarea_input($extras = "")
{
   extract(pem_field_extras($extras));
   $ret = '<textarea' . $name . $id . $nameid . $cols . $rows . $class . $style . $onchange . $tabindex . '>';
   $ret .= $default . '</textarea>' . "\n";
   echo $ret;
}

// echos a simple select.
// $pairs is an array with "text => value" used to populate the options
// $extras must contatin either name, id, or nameid to identify the field.
function pem_select($options, $extras = "")
{
   extract(pem_field_extras($extras));

//   if ($hidden)
//   {
//      $ret = '<input type="hidden"' . $name . $id . $nameid . $value . ' />' . "\n";
//   }
//   else
//   {
   $text = array_keys($options);
   $ret = '<select' . $name . $id . $nameid . $class . $style . $onchange . $tabindex . '>' . "\n";
   if ($showprompt)
   {
      $ret .= '<option' . $selected . ' value="">' . __("Select an option") . '</option>';
   }
   for ($i = 0; $i < count($text); $i++)
   {
      // echo "check for: $default = " . $options[$text[$i]] . "<br />"; // Debug line
      $selected = ($default == $options[$text[$i]]) ? ' selected="selected"' : "";
      $ret .= '<option' . $selected . ' value="' . $options[$text[$i]] . '">' . $text[$i] . '</option>';
   }
   $ret .= "\n" . '</select>' . "\n";

   echo $ret;
}

// echos a simple checkbox
// $extras must contatin either name, id, or nameid to identify the field.
function pem_checkbox($extras = "")
{
   extract(pem_field_extras($extras));
   $checked = ($status) ? ' checked="checked"' : "";
   $ret = '<input type="checkbox"' . $name . $id . $nameid . $class . $style . $onclick . $tabindex . $checked . ' />';
   echo $ret;
}

// echos a simple radio option
// $extras must contatin either name, id, or nameid to identify the field.
function pem_radio($extras = "")
{
   extract(pem_field_extras($extras));
   $checked = ($status) ? ' checked="checked"' : "";
   $ret = '<input type="radio"' . $name . $id . $nameid . $onchange . $tabindex . $checked . ' />';
   echo $ret;
}


// returns a series of radio options as label/input sets
// $pairs is an array with "text => value" used to populate the options
// $extras must contatin either name, id, or nameid to identify the field.
function pem_radio_options($pairs, $extras = "")
{
   extract(pem_field_extras($extras));
   $text = array_keys($pairs);
   $ret = "";
   for ($i = 0; $i < count($text); $i++)
   {
      $checked = ($default == $pairs[$text[$i]]) ? ' checked="checked"' : "";
      $ret .= '<input type="radio" ' . $name . $id . $nameid . $pairs[$text[$i]] .'" value="' . $pairs[$text[$i]] . '"' . $checked . ' />';
      $ret .= '&nbsp;<label for="' . $radiofor . $pairs[$text[$i]] . '">' . $text[$i] . '</label>' . "\n";
   }
   return $ret;
}

// echos a simple file upload field
// $extras must contatin either name, id, or nameid to identify the field.
function pem_file_upload($extras = "")
{
   extract(pem_field_extras($extras));
   $ret = '<input' . $name . $id . $nameid . ' type="file"' . $size . $maxlength;
   $ret .= $class . $style . $value . $onchange . $tabindex . ' />' . "\n";
   echo $ret;
}


//   $view_week_number_pairs = array(__("Yes") => 1, __("No") => 0);
//   echo pem_radio_options("view_week_number", $view_week_number_pairs, $view_week_number);


// =======================================================================

// echos a boolean select
// $extras must contatin either name, id, or nameid to identify the field.
function pem_boolean_select($extras= "")
{
   if (isset($extras["showprompt"])) $options = array(__("No") => 1, __("Yes") => 2);
   else $options = array(__("No") => 0, __("Yes") => 1);


   pem_select($options, $extras);
} // END pem_boolean_select







// echos a side select
// $extras must contatin either name, id, or nameid to identify the field.
function pem_side_select($extras= "")
{
   $options = array(__("Right") => 0, __("Left") => 1);
   pem_select($options, $extras);
} // END pem_side_select

// echos a box format select
// $extras must contatin either name, id, or nameid to identify the field.
function pem_box_format_select($extras = "")
{
   $options = array(__("Links") => 0, __("Select Drop-Down") => 1,  __("Checkboxes") => 2);
   pem_select($options, $extras);
} // END pem_box_format_select

// echos a field behavior select
// $extras must contatin either name, id, or nameid to identify the field.
function pem_field_behavior_select($extras = "")
{
   $options = array(__("Inactive") => 0, __("Visible") => 1,  __("Required") => 2);
   pem_select($options, $extras);
} // END pem_field_behavior_select

// echos an parent type select for meta data
// $extras must contatin either name, id, or nameid to identify the field.
function pem_meta_parent_select($extras = "")
{
   $options = array(__("Entries") => 0, __("Dates") => 1);
   pem_select($options, $extras);
} // END pem_meta_parent_select

// echos a book range type select
// $extras must contatin either name, id, or nameid to identify the field.
function pem_book_range_type_select($extras = "")
{
   if (!array_key_exists("default", $extras) OR empty($extras["default"])) $options[__("Select Limit")] = "";
   $options[__("Between Dates")] = "between";
   $options[__("Time in Advance")] = "advance";
   pem_select($options, $extras);
} // END pem_book_range_type_select

// echos an event type select
// $extras must contatin either name, id, or nameid to identify the field.
// arrays declared globally to provide easy parsing of stored data
$entry_types = array(__("Internal Calendar") => 0, __("External Calendar") => 1, __("Internal Side Box") => 2, __("External Side Box") => 3);
$entry_type_fields = array("internal_scheduled", "external_scheduled", "internal_unscheduled", "external_unscheduled");
function pem_entry_type_select($extras = "")
{
   global $entry_types;
   pem_select($entry_types, $extras);
} // END pem_entry_type_select

// echos an event type select for internal/external source selection
// $extras must contatin either name, id, or nameid to identify the field.
// arrays declared globally to provide easy parsing of stored data
function pem_entry_source_type_select($extras = "")
{
   if (!array_key_exists("default", $extras) OR empty($extras["default"])) $options[__("Select to Continue")] = "";
   $options[__("Internal")] = internal;
   $options[__("External")] = external;
   pem_select($options, $extras);
} // END pem_entry_source_type_select

// echos a view select
// $extras must contatin either name, id, or nameid to identify the field.
function pem_view_select($extras = "")
{
   $options = array(
      __("Day Calendar") => "day-cal", __("Day Listing") => "day-list",
      __("Week Calendar") => "week-cal", __("Week Listing") => "week-list",
      __("Month Calendar") => "month-cal", __("Month Listing") => "month-list",
      __("Year Calendar") => "year-cal", __("Year Listing") => "year-list"
      );
   pem_select($options, $extras);
} // END pem_view_select

// echos a theme select based on directories found in pem-themes
/// $extras must contatin either name, id, or nameid to identify the field.
function pem_theme_select($extras = "")
{
   $themes = pem_get_themes();
   if (empty($themes)) _e("No themes found.");
   else
   {
      for ($i = 0; $i < count($themes); $i++)
      {
         $options[ucwords($themes[$i])] = $themes[$i];
      }
      pem_select($options, $extras);
   }
} // END pem_theme_selects

// echos a category select for use during event creation/edit forms
// $extras must contatin either name, id, or nameid to identify the field.
function pem_category_select($extras = "")
{
   extract(pem_field_extras($extras));
   $list = pem_get_rows("categories");
   $ret = '<select ' . $name . $id . $nameid . $class . $style . $onchange . $tabindex . '>' . "\n";
   $ret .= '<option' . $selected . ' value="">' . __("Select a category") . '</option>';
   for ($i = 1; $i < count($list); $i++) // start at 1 to exclude the  default "all" category
   {
      $selected = ($default == $list[$i]["id"]) ? ' selected="selected"' : "";
      // echo "check for: $default = " . $list[$i]["id"] . "<br />"; // Debug line
      $ret .= '<option style="color:#' . $list[$i]["category_color"] . ';"' . $selected . ' value="' . $list[$i]["id"] . '">' . $list[$i]["category_name"] . '</option>';
   }
   $ret .= "\n" . '</select>' . "\n";
   echo $ret;
} // END pem_category_select

// echos a contact_meta select for use during registration adminsitration
// $extras must contatin either name, id, or nameid to identify the field.
function pem_contact_meta_select($extras = "")
{
   extract(pem_field_extras($extras));
   $where = array("meta_type" => "contact");
   $where["status"] = array("!=", "2");
   $list = pem_get_rows("meta", $where);

   $ret = '<select ' . $name . $id . $nameid . $class . $style . $onchange . $tabindex . '>' . "\n";
   $ret .= '<option' . $selected . ' value="">' . __("Select a contact block") . '</option>';
   for ($i = 0; $i < count($list); $i++)
   {
      $selected = ($default == $list[$i]["id"]) ? ' selected="selected"' : "";
      // echo "check for: $default = " . $list[$i]["id"] . "<br />"; // Debug line
      $ret .= '<option ' . $selected . ' value="' . $list[$i]["id"] . '">' . $list[$i]["meta_name"] . '</option>';
   }
   $ret .= "\n" . '</select>' . "\n";
   echo $ret;
} // END pem_contact_meta_select





function pem_meta_select($options, $extras = "")
{
   extract(pem_field_extras($extras));
   $text = array_keys($options);
   $ret = '<select' . $name . $id . $nameid . $class . $style . $onchange . $tabindex . '>' . "\n";
   $ret .= '<option' . $selected . ' value="">' . __("Select an option") . '</option>';
   for ($i = 0; $i < count($text); $i++)
   {
      // echo "check for: $default = " . $options[$text[$i]] . "<br />"; // Debug line
      $selected = ($default == $options[$text[$i]]) ? ' selected="selected"' : "";
      $ret .= '<option' . $selected . ' value="' . $options[$text[$i]] . '">' . $text[$i] . '</option>';
   }
   $ret .= "\n" . '</select>' . "\n";
   echo $ret;
} // END pem_meta_select

// echos a prestenter type select for use during event creation/edit forms
// $extras must contatin either name, id, or nameid to identify the field.
function pem_prestenter_type_select($extras = "")
{
   $list = pem_get_rows("presenters");
   for ($i = 0; $i < count($list); $i++)
   {
      $options[$list[$i]["presenter_type"]] = $list[$i]["id"];
   }
   pem_select($options, $extras);
} // END pem_prestenter_type_select


// echos a quantity select for use during event creation/edit forms
// $extras must contatin either name, id, or nameid to identify the field.
// $qty_max is the upper end of the numebred select
function pem_quantity_select($extras = "", $qty_max)
{
   extract(pem_field_extras($extras));
   $ret = '<select ' . $name . $id . $nameid . $class . $style . $onchange . $tabindex . '>' . "\n";
   for ($i = 0; $i <= $qty_max; $i++)
   {
      $selected = ($default == $i) ? ' selected="selected"' : "";
      $ret .= '<option ' . $selected . ' value="' . $i . '">' . $i . '</option>';
   }
   $ret .= "\n" . '</select>' . "\n";
   echo $ret;
} // END pem_quantity_select



// echos an recurring_event select for multi-date entry during event creation/edit forms
// $extras must contatin either name, id, or nameid to identify the field.
function pem_recurring_type_select($extras = "")
{
   $options = array(
      __("Daily") => "daily",
      __("Every Weekday (Mon-Fri)") => "weekdays",
      __("Every Mon, Wed, and Fri") => "mwf",
      __("Every Tue and Thurs") => "tth",
      __("Weekly") => "weekly",
      __("Bi-Weekly") => "biweekly",
      __("Monthly") => "monthly",
      __("Yearly") => "yearly"
   );
   pem_select($options, $extras);
} // END pem_recurring_type_select

// echos an recurring_event select for multi-date entry during event creation/edit forms
// $extras must contatin either name, id, or nameid to identify the field.
function pem_recurring_duration_select($extras = "")
{
   $options = array(
      __("Select Recurrance Ending") => 0,
      __("Stop After N Occurances") => "aftern",
      __("Repeat Till a Stop Date") => "bydate",
   );
   pem_select($options, $extras);
} // END pem_recurring_duration_select

// echos an recurring_event select for multi-date entry during event creation/edit forms
// $extras must contatin either name, id, or nameid to identify the field.
function pem_recur_times_select($extras = "")
{
   $options = array(
      __("2 Occurances") => 2,
      __("3 Occurances") => 3,
      __("4 Occurances") => 4,
      __("5 Occurances") => 5,
      __("6 Occurances") => 6,
      __("7 Occurances") => 7,
      __("8 Occurances") => 8,
      __("9 Occurances") => 9,
      __("10 Occurances") => 10,
      __("11 Occurances") => 11,
      __("12 Occurances") => 12,
      __("13 Occurances") => 13,
      __("14 Occurances") => 14,
      __("15 Occurances") => 15
   );
   pem_select($options, $extras);
} // END pem_recur_times_duration_select



// Echos a select with all of the US states.
// $extras must contatin either name, id, or nameid to identify the field.
function pem_echo_state_select($extras = "")
{
   if (!isset($extras["default"])) $extras["default"] = "AL";

   $states = array("AL","AK","AZ","AR","CA","CO","CT","DE","DC","FL","GA","HI","ID","IL","IN","IA","KS","KY","LA","ME","MD","MA","MI","MN","MS","MO","MT","NE","NV","NH","NJ","NM","NY","NC","ND","OH","OK","OR","PA","RI","SC","SD","TN","TX","UT","VT","VA","WA","WV","WI","WY");
   $options = array_combine($states, $states);
   pem_select($options, $extras);

   /*
   $ret = '<select name="'.$name.'" id="'.$name.'">';
   for ($i = 0; $i < count($aStates); $i++)
   {
     $selected = "";
     if ($default == $aStates[$i]) { $selected = ' selected="selected"'; }
     $ret .= '<option'.$selected.' value="'.$aStates[$i].'">'.$aStates[$i].'</option>';
   }
   $ret .= '</select>';
   return $ret;
   */
} // END pem_echo_state_select



// echos an access profile select for selection during user creation/edit forms
// $extras must contatin either name, id, or nameid to identify the field.
function pem_access_profiles_select($extras = "")
{
   $where = array("status" => array("!=", "2"));
   $list = pem_get_rows("access_profiles", $where);
   for ($i = 0; $i < count($list); $i++)
   {
      $options[$list[$i]["profile_name"]] = $list[$i]["id"];
   }
   pem_select($options, $extras);
} // END pem_access_profiles_select





// echos a popup select based on HTML and PHP files found in pem-content
/// $extras must contatin either name, id, or nameid to identify the field.
function pem_popup_select($extras = "")
{
   $popups = pem_get_popups();
   if (empty($popups)) _e("No HTML, PHP, or text files found.");
   else
   {
      $options = array(__("No Popup Selected") => "");
      for ($i = 0; $i < count($popups); $i++)
      {
         $options[$popups[$i]] = $popups[$i];
      }
      pem_select($options, $extras);
   }
} // END pem_popup_select













// =============================================================================
// ======================== AUTHENTICATION =====================================
// =============================================================================



// echos a select of auth resources with localized language.
// $name is requied to define the form element.
function pem_auth_resources_select($extras = "")
{
   global $auth_res_text;

   $reskeys = array_keys($auth_res_text);
   for ($i = 0; $i < count($reskeys); $i++)
   {
     $options[$auth_res_text[$reskeys[$i]]] = $reskeys[$i];
   }
   pem_select($options, $extras);
} // END pem_auth_resources_select



// echos a select of auth keys with localized language.
// $name is requied to define the form element.
// $type is must be Edit, Approve, or Delete.
function pem_auth_keys_select($type, $extras = "")
{
   global $auth_key_text;

   if (!isset($extras["default"])) $extras["default"] = 0;
   $i = 0;
   while ($i < count($auth_key_text) + 9)
   {
     if ($i == 1) $i = 10;
     if (($i == 0 OR strpos($auth_key_text[$i], $type) === 0) AND ($auth_key_text[$i] != $type))
     {
        $options[$auth_key_text[$i]] = $i;
     }
     $i++;
   }
   pem_select($options, $extras);
} // END pem_auth_keys_select


// =============================================================================
// ============================== MESSAGES =====================================
// =============================================================================


function pem_error_list($list)
{
   if (!empty($list))
   {
      $error_count = count($list);
      $error_instructions = ($error_count > 1) ? __("Please fix the listed errors to complete the form.") : __("Please fix the error below to complete the form.");
      echo '<p style="margin-bottom:-3px; font-weight:bold;">' . $error_instructions . '</p>' . "\n";
      $ret = '<ul class="error">' . "\n";
      for ($i = 0; $i < count($list); $i++)
      {
         $ret .= '<li>' . $list[$i] . '</li>' . "\n";
      }
      $ret .= '</ul>' . "\n";
      echo $ret;
      return true;
   }
   return false;
}



// =============================================================================
// ========================== EVENT SUBMISSION =================================
// =============================================================================

function pem_check_schedule_conflicts(&$pemdb, &$error, $when_begin, $date_begin, $real_time_begin, $real_time_end, $form_spaces)
{

   global $table_prefix, $date_format, $time_format;

   $sql = "SELECT profile FROM " . $table_prefix . "scheduling_profiles WHERE ";
   $sql .= "date_begin <= :date_begin AND ";
   $sql .= "date_end >= :date_begin";
   $conflict_text = "";
// $conflict_text .= "<li>DEBUG: when_begin: " . pem_date("Y-m-d " . $time_format, $when_begin) . ", date_begin: $date_begin, real_time_begin: $real_time_begin, real_time_end: $real_time_end </li>";

   $when_weekday = pem_date("w", $when_begin);
   $sql_values = array("date_begin" => $date_begin);
   $sql_prep = $pemdb->prepare($sql);
   if (PEAR::isError($sql_prep)) $error .= $sql_prep->getMessage() . ', ' . $sql_prep->getDebugInfo();
   $result = $sql_prep->execute($sql_values);
   if (PEAR::isError($result)) $error .= $result->getMessage() . ', ' . $result->getDebugInfo();
   $schedule_row = $result->fetchRow(MDB2_FETCHMODE_ASSOC);
   if (!empty($schedule_row["profile"]))
   {
      $schedule_profile = unserialize($schedule_row["profile"]);
      $restrict_to_open = $schedule_profile["restrict_to_open"];
      $open_begin = $schedule_profile["open_begin_" . $when_weekday];
      $open_end = $schedule_profile["open_end_" . $when_weekday];
      $blackout = $schedule_profile["blackout_" . $when_weekday];
   }

   if ($restrict_to_open)
   {
      $sql2 = "SELECT space_name, scheduling_profile FROM " . $table_prefix . "spaces WHERE id = :space_id";
      $spaces_count = count($form_spaces);
      $spaces_text = "";
      for ($i = 0; $i < $spaces_count; $i++)
      {
         $sql_values2 = array("space_id" => $form_spaces[$i]);
         $sql_prep2 = $pemdb->prepare($sql2);
         if (PEAR::isError($sql_prep2)) $error .= $sql_prep2->getMessage() . ', ' . $sql_prep2->getDebugInfo();
         $result2 = $sql_prep2->execute($sql_values2);
         if (PEAR::isError($result2)) $error .= $result2->getMessage() . ', ' . $result2->getDebugInfo();
         $space_row = $result2->fetchRow(MDB2_FETCHMODE_ASSOC);
         if (!empty($space_row["scheduling_profile"]))
         {
            $spaces_profile = unserialize($space_row["scheduling_profile"]);
            $time_before_open = pem_real_to_time_quantity($spaces_profile["time_before_open_" . $when_weekday]);
            $time_after_closed = pem_real_to_time_quantity($spaces_profile["time_after_closed_" . $when_weekday]);
            $start_before_closed = pem_real_to_time_quantity($spaces_profile["start_before_closed_" . $when_weekday]);
            $space_open_begin = pem_time_subtract($open_begin, $time_before_open["hours"], $time_before_open["minutes"]);
            $space_open_end = pem_time_add($open_end, $time_after_closed["hours"], $time_after_closed["minutes"]);
            $space_must_start = pem_time_subtract($open_end, $start_before_closed["hours"], $start_before_closed["minutes"]);

            if ($blackout)
            {
               $conflict_text .= "<li>";
               $conflict_text .= sprintf(__("Scheduling on %s is not allowed."), pem_date("l", $date_begin . " " . $real_time_begin));
               $conflict_text .= "</li>\n";
            }
            if ($real_time_begin < $space_open_begin)
            {
               $conflict_text .= "<li>";
               $conflict_text .= sprintf(__("Begin Time of %s is too early. %s events must start after %s on %s."), pem_date($time_format, $date_begin . " " . $real_time_begin), $space_row["space_name"], pem_date($time_format, $date_begin . " " . $space_open_begin), pem_date("l", $date_begin . " " . $space_open_begin));
               $conflict_text .= "</li>\n";
            }
            if ($real_time_end > $space_open_end)
            {
               $conflict_text .= "<li>";
               $conflict_text .= sprintf(__("End Time of %s is too late. %s events must end before %s on %s."), pem_date($time_format, $date_begin . " " . $real_time_end), $space_row["space_name"], pem_date($time_format, $date_begin . " " . $space_open_end), pem_date("l", $date_begin . " " . $space_open_begin));
               $conflict_text .= "</li>\n";
            }
            if ($real_time_begin > $space_must_start)
            {
               $conflict_text .= "<li>";
               $conflict_text .= sprintf(__("Begin Time of %s is too late. %s events must start before %s on %s."), pem_date($time_format, $date_begin . " " . $real_time_begin), $space_row["space_name"], pem_date($time_format, $date_begin . " " . $space_must_start), pem_date("l", $date_begin . " " . $space_open_begin));
               $conflict_text .= "</li>\n";
            }
         }
      }
   }
   return $conflict_text;
} // END pem_check_schedule_conflicts

function pem_check_event_conflicts(&$pemdb, &$error, $real_begin, $real_end, $form_spaces, $check_day = "", $thisdid = "")
{

   global $table_prefix, $date_format, $time_format;
   $authorized_private = pem_user_authorized("View Private");

   $sql = "SELECT d.id, d.entry_id, e.entry_name, d.when_begin, d.when_end, d.real_begin, d.real_end, d.spaces, e.entry_visible_to_public, d.date_visible_to_public FROM " . $table_prefix . "entries as e, " . $table_prefix . "dates as d WHERE ";
   if (!empty($thisdid)) $sql .= "d.id != '$thisdid' AND ";
   $sql .= "e.id = d.entry_id AND ";
   $sql .= "e.entry_status != '2' AND ";
   $sql .= "d.date_status != '2' AND ";
   $sql .= "e.entry_cancelled != '1' AND ";
   $sql .= "d.date_cancelled != '1' AND ";
   $sql .= "d.conflicting = '1' AND ";
//   $sql .= "d.conflicting != '0' AND ";
   $sql .= "d.real_begin < :when_begin_before AND ";
   $sql .= "d.real_end > :when_end_after AND ";
   $sql .= "e.entry_type != '3' AND ";
   $sql .= "e.entry_type != '4'";
   $sql .= " ORDER BY d.when_begin, d.when_end, e.entry_name";
   $conflict_text = "";

// $conflict_text .= "<li>DEBUG: check_day: $check_day, real_begin: $real_begin, real_end: $real_end, thisdid: $thisdid </li>";

   MDB2::loadFile("Date"); // load Date helper class
   if (empty($check_day))
   {
      $dbegin = MDB2_Date::date2Mdbstamp(pem_date("G", $real_begin), pem_date("i", $real_begin), pem_date("s", $real_begin), pem_date("m", $real_begin), pem_date("d", $real_begin), pem_date("Y", $real_begin));
      $dend = MDB2_Date::date2Mdbstamp(pem_date("G", $real_end), pem_date("i", $real_end), pem_date("s", $real_end), pem_date("m", $real_end), pem_date("d", $real_end), pem_date("Y", $real_end));
   }
   else
   {
      $dbegin = MDB2_Date::date2Mdbstamp(pem_date("G", $real_begin), pem_date("i", $real_begin), pem_date("s", $real_begin), pem_date("m", $check_day), pem_date("d", $check_day), pem_date("Y", $check_day));
      $dend = MDB2_Date::date2Mdbstamp(pem_date("G", $real_end), pem_date("i", $real_end), pem_date("s", $real_end), pem_date("m", $check_day), pem_date("d", $check_day), pem_date("Y", $check_day));
   }
   $sql_values = array("when_begin_before" => $dend, "when_end_after" => $dbegin);

   $sql_prep = $pemdb->prepare($sql);
   if (PEAR::isError($sql_prep)) $error .= $sql_prep->getMessage() . ', ' . $sql_prep->getDebugInfo();
   $result = $sql_prep->execute($sql_values);
   if (PEAR::isError($result)) $error .= $result->getMessage() . ', ' . $result->getDebugInfo();
   while ($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC))
   {
      $space_matches = "";
      $spaces = unserialize($row["spaces"]);
      $space_matches = array_intersect($form_spaces, $spaces);

      if (!empty($space_matches)) // no need to look up room names
      {
         $spaces_text = "";
         $sql2 = "SELECT space_name FROM " . $table_prefix . "spaces WHERE id = :space_id";
         $spaces_count = count($spaces);
         for ($i = 0; $i < $spaces_count; $i++) // build spaces_text
         {
            $sql_values2 = array("space_id" => $spaces[$i]);
            $sql_prep2 = $pemdb->prepare($sql2);
            if (PEAR::isError($sql_prep2)) $error .= $sql_prep2->getMessage() . ', ' . $sql_prep2->getDebugInfo();
            $result2 = $sql_prep2->execute($sql_values2);
            if (PEAR::isError($result2)) $error .= $result2->getMessage() . ', ' . $result2->getDebugInfo();
            $space_row = $result2->fetchRow(MDB2_FETCHMODE_ASSOC);
            $spaces_text .= $space_row["space_name"];
            if ($i < $spaces_count - 1) $spaces_text .= ", ";
         }
         if (($row["entry_visible_to_public"] AND $row["date_visible_to_public"]) OR $authorized_private)
         {
            $event_name = (!empty($row["date_name"])) ? $row["entry_name"] . ': ' . $row["date_name"] : $row["entry_name"];
            $conflict_text .= '<li><a href="/view.php?did=' . $row["id"] . '">' . $event_name . '</a> - ' . pem_date($time_format, $row["when_begin"]) . ' ' . __("to") . ' ' . pem_date($time_format, $row["when_end"]) . ' (' . pem_date($time_format, $row["real_begin"]) . ' ' . __("to") . ' ' . pem_date($time_format, $row["real_end"]) . ') - ' . $spaces_text . '</li>' . "\n";
         }
         else
         {
            $event_name = __("Private Event");
            $conflict_text .= '<li><b>' . $event_name . '</b> - ' . pem_date($time_format, $row["when_begin"]) . ' ' . __("to") . ' ' . pem_date($time_format, $row["when_end"]) . ' (' . pem_date($time_format, $row["real_begin"]) . ' ' . __("to") . ' ' . pem_date($time_format, $row["real_end"]) . ') - ' . $spaces_text . '</li>' . "\n";
         }
      }

/*ob_start();
echo "<b>looking for:</b> ";
print_r($form_spaces);
echo " <b>within:</b> ";
print_r($spaces);
$conflict_text .= ob_get_clean();
*/
   }
   return $conflict_text;
} // END pem_check_event_conflicts


function pem_recur_increment($increment, &$recur_when_begin, &$recur_when_end, &$recur_real_begin, &$recur_real_end)
{
   switch (true)
   {
   case ($increment == "daily"):
      $recur_when_begin = pem_date("Y-m-d H:i:s", strtotime("+1 day", strtotime($recur_when_begin)));
      $recur_when_end = pem_date("Y-m-d H:i:s", strtotime("+1 day", strtotime($recur_when_end)));
      $recur_real_begin = pem_date("Y-m-d H:i:s", strtotime("+1 day", strtotime($recur_real_begin)));
      $recur_real_end = pem_date("Y-m-d H:i:s", strtotime("+1 day", strtotime($recur_real_end)));
      break;
   case ($increment == "weekdays"):
      if (pem_date("w", $recur_when_begin) < 5) $recur_jump = "+1 day";
      else $recur_jump = "next Monday";
      $recur_when_begin = pem_date("Y-m-d H:i:s", strtotime($recur_jump, strtotime($recur_when_begin)));
      $recur_when_end = pem_date("Y-m-d H:i:s", strtotime($recur_jump, strtotime($recur_when_end)));
      $recur_real_begin = pem_date("Y-m-d H:i:s", strtotime($recur_jump, strtotime($recur_real_begin)));
      $recur_real_end = pem_date("Y-m-d H:i:s", strtotime($recur_jump, strtotime($recur_real_end)));
      break;
   case ($increment == "mwf"):
      if (pem_date("w", $recur_when_begin) == 1) $recur_jump = "next Wednesday";
      elseif (pem_date("w", $recur_when_begin) == 3) $recur_jump = "next Friday";
      else $recur_jump = "next Monday";
      $recur_when_begin = pem_date("Y-m-d H:i:s", strtotime($recur_jump, strtotime($recur_when_begin)));
      $recur_when_end = pem_date("Y-m-d H:i:s", strtotime($recur_jump, strtotime($recur_when_end)));
      $recur_real_begin = pem_date("Y-m-d H:i:s", strtotime($recur_jump, strtotime($recur_real_begin)));
      $recur_real_end = pem_date("Y-m-d H:i:s", strtotime($recur_jump, strtotime($recur_real_end)));
      break;
   case ($increment == "tth"):
      if (pem_date("w", $recur_when_begin) == 2) $recur_jump = "next Thursday";
      else $recur_jump = "next Tuesday";
      $recur_when_begin = pem_date("Y-m-d H:i:s", strtotime($recur_jump, strtotime($recur_when_begin)));
      $recur_when_end = pem_date("Y-m-d H:i:s", strtotime($recur_jump, strtotime($recur_when_end)));
      $recur_real_begin = pem_date("Y-m-d H:i:s", strtotime($recur_jump, strtotime($recur_real_begin)));
      $recur_real_end = pem_date("Y-m-d H:i:s", strtotime($recur_jump, strtotime($recur_real_end)));
      break;
   case ($increment == "weekly"):
      $recur_when_begin = pem_date("Y-m-d H:i:s", strtotime("+1 week", strtotime($recur_when_begin)));
      $recur_when_end = pem_date("Y-m-d H:i:s", strtotime("+1 week", strtotime($recur_when_end)));
      $recur_real_begin = pem_date("Y-m-d H:i:s", strtotime("+1 week", strtotime($recur_real_begin)));
      $recur_real_end = pem_date("Y-m-d H:i:s", strtotime("+1 week", strtotime($recur_real_end)));
      break;
   case ($increment == "biweekly"):
      $recur_when_begin = pem_date("Y-m-d H:i:s", strtotime("+2 week", strtotime($recur_when_begin)));
      $recur_when_end = pem_date("Y-m-d H:i:s", strtotime("+2 week", strtotime($recur_when_end)));
      $recur_real_begin = pem_date("Y-m-d H:i:s", strtotime("+2 week", strtotime($recur_real_begin)));
      $recur_real_end = pem_date("Y-m-d H:i:s", strtotime("+2 week", strtotime($recur_real_end)));
      break;
   case ($increment == "monthly"):
      $recur_when_begin = pem_date("Y-m-d H:i:s", strtotime("+1 month", strtotime($recur_when_begin)));
      $recur_when_end = pem_date("Y-m-d H:i:s", strtotime("+1 month", strtotime($recur_when_end)));
      $recur_real_begin = pem_date("Y-m-d H:i:s", strtotime("+1 month", strtotime($recur_real_begin)));
      $recur_real_end = pem_date("Y-m-d H:i:s", strtotime("+1 month", strtotime($recur_real_end)));
      break;
   case ($increment == "yearly"):
      $recur_when_begin = pem_date("Y-m-d H:i:s", strtotime("+1 year", strtotime($recur_when_begin)));
      $recur_when_end = pem_date("Y-m-d H:i:s", strtotime("+1 year", strtotime($recur_when_end)));
      $recur_real_begin = pem_date("Y-m-d H:i:s", strtotime("+1 year", strtotime($recur_real_begin)));
      $recur_real_end = pem_date("Y-m-d H:i:s", strtotime("+1 year", strtotime($recur_real_end)));
      break;
   }
} // END pem_recur_increment





// =============================================================================
// ============================ CALENDAR VIEWS =================================
// =============================================================================

function pem_unscheduled($list)
{
   global $view_settings, $categories; // assumes the categories have been pulled for colors

   if (isset($list))
   {
      echo '<ul id="unscheduled-list">' . "\n";
      foreach ($list AS $this_event)
      {
         echo '<li>' . "\n";
         if (0 == $this_event["entry_status"] OR 0 == $this_event["date_status"])
         {
            echo '<a class="unapproved"';
         }
         elseif ($this_event["entry_cancelled"] OR $this_event["date_cancelled"])
         {
            $cat = (isset($this_event["date_category"])) ? $this_event["date_category"] : $this_event["entry_category"];
            if (empty($cat)) $cat = 1;  // default color if no others set.
            echo '<a class="cancelled" style="color:#' . $categories[$cat] . ';"';
         }
         elseif (0 == $this_event["entry_visible_to_public"] OR 0 == $this_event["date_visible_to_public"])
         {
            echo '<a class="private"';
         }
         else
         {
            $cat = (isset($this_event["date_category"])) ? $this_event["date_category"] : $this_event["entry_category"];
            if (empty($cat)) $cat = 1;  // default color if no others set.
            echo '<a style="color:#' . $categories[$cat] . ';"';
         }
         echo ' href="view.php?did=' . $this_event["id"] . '">';
         if ($this_event["entry_cancelled"] OR $this_event["date_cancelled"])
         {
            echo " [" . __("CANCELLED") . "] ";
         }

         $full_name = (!empty($this_event["date_name"])) ? $this_event["entry_name"] . ': ' . $this_event["date_name"] : $this_event["entry_name"];
         if ((!empty($view_settings["event_name_length"])) AND (strlen($full_name) > $view_settings["event_name_length"]))
         {
            echo " " . substr($full_name, 0, $view_settings["event_name_length"]) . __("...");
         }
         else
         {
            echo " " . $full_name;
         }
         echo '</a></li>' . "\n";
      }
      echo '</ul">' . "\n";
   }
} // END pem_unscheduled


function pem_category_legend($cat_list)
{
   if (!empty($cat_list) and is_array($cat_list) and count($cat_list) > 1)
   {
      echo '<div id="legend-box">' . "\n";
      echo '<h4>' . __("Color Key") . '</h4>' . "\n";
      for ($i = 1; $i < count($cat_list); $i++)
      {
         echo '<div><div class="key" style="background-color:#' . $cat_list[$i]["category_color"] . '"></div>';
         echo '<span class="label">' . $cat_list[$i]["category_name"] . '</span></div><br />' . "\n";

      }
      echo '</div>' . "\n"; // END legend-box
   }
}

?>