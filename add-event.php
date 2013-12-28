<?php
/* ========================== FILE INFORMATION =================================
phxEventManager :: add-event.php

============================================================================= */
$inajax = true;
include_once "pem-config.php";
include_once ABSPATH . "/pem-settings.php";

$XAJAX_DIR = PEMINC . "/xajax/";
include_once $XAJAX_DIR . "xajax_core/xajax.inc.php";
$xajax = new xajax();
//$xajax->setFlag("debug", true);
$xajax->registerFunction("save_template");
$xajax->registerFunction("do_save_template");
$xajax->registerFunction("check_conflicts");
$xajax->processRequest();

function save_template($formdata)
{
   $objResponse = new xajaxResponse();
   $objResponse->script("toggleLayer('main_form', 0)");
   $objResponse->script("toggleLayer('template_form', 1)");
   $res = '<p>' . __("Enter a name for  the saved template, and click submit.") . '</p>';

   $objResponse->assign("directions", "innerHTML", $res);
   return $objResponse;
}

function do_save_template($formdata)
{
   $objResponse = new xajaxResponse();
   $formoutput = __("Event information saved as template.");
   $objResponse->alert($formoutput);
   $objResponse->script("toggleLayer('template_form', 0)");
   $objResponse->script("toggleLayer('main_form', 1)");
   return $objResponse;
}

function check_conflicts($formdata, $errorbox = "")
{
   global $date_format, $time_format;

   $objResponse = new xajaxResponse();


   if (!$formdata["conflicting"] AND !isset($formdata["spaces"]))
   {
      if (substr($errorbox, 72, 15) != "Required Fields") $objResponse->script("hidePopup('errorbox')");
      $directions = '<div style="float:left;">' . __("This event will not conflict with others in the calendar.  Select a location to complete the event.") . '</div>';
      if (substr($errorbox, 72, 15) == "Required Fields") $directions .= '<a href="#" onclick="showPopup(\'errorbox\');" class="inlinebutton"><span>' . __("Show Errors") . '</span></a>';
      $objResponse->assign("directions", "innerHTML", $directions);
   }
   elseif (!$formdata["conflicting"])
   {
      $objResponse->clear("ajaxbox", "innerHTML");
      if (substr($errorbox, 72, 15) != "Required Fields") $objResponse->script("hidePopup('errorbox')");
      $objResponse->script("toggleLayer('stage3', 1)");
      $objResponse->assign("datasubmit", "value", "stage3");
      $directions = '<div style="float:left;">' . __("This event will not conflict with others in the calendar.  Fill out the remaining fields and click Submit to complete the event.") . '</div>';
      if (substr($errorbox, 72, 15) == "Required Fields") $directions .= '<a href="#" onclick="showPopup(\'errorbox\');" class="inlinebutton"><span>' . __("Show Errors") . '</span></a>';
      $objResponse->assign("directions", "innerHTML", $directions);
   }
   elseif (!isset($formdata["date_begin_month"]))
   {
      if (substr($errorbox, 72, 15) != "Required Fields") $objResponse->script("hidePopup('errorbox')");
      $directions = '<div style="float:left;">' . __("There are no conflicts with your current time and location settings.  Fill out the remaining fields and click Submit to complete the event.") . '</div>';
      if (substr($errorbox, 72, 15) == "Required Fields") $directions .= '<a href="#" onclick="showPopup(\'errorbox\');" class="inlinebutton"><span>' . __("Show Errors") . '</span></a>';
      $objResponse->assign("directions", "innerHTML", $directions);
   }
   else
   {
      $current_event_type = pem_cache_get("current_event_type");
      switch (true)
      {
      case ($current_event_type == "scheduled"):
         $source_type = ($formdata["source_type"] == "internal") ? "internal_scheduled" : "external_scheduled";
         break;
      case ($current_event_type == "unscheduled"):
         $source_type = ($formdata["source_type"] == "internal") ? "internal_unscheduled" : "external_unscheduled";
         break;
      case ($current_event_type == "allday"):
         $source_type = "internal_scheduled";
         break;
      }

      $date_begin = $formdata["date_begin_year"] . "-" . zeropad($formdata["date_begin_month"], 2) . "-" . zeropad($formdata["date_begin_day"], 2);

      if (isset($formdata["multi_day_event"]) AND !empty($formdata["multi_day_event"]))
      {
         $date_end = $formdata["date_end_year"] . "-" . zeropad($formdata["date_end_month"], 2) . "-" . zeropad($formdata["date_end_day"], 2);
      }
      else
      {
         $date_end = $date_begin;
         $objResponse->script("mirrordateselect('date_begin', 'date_end')");
      }

      $time_begin = pem_time($formdata["time_begin_hour"], $formdata["time_begin_minute"], (isset($formdata["time_begin_meridiem"])) ? $formdata["time_begin_meridiem"] : "");
      $time_end = pem_time($formdata["time_end_hour"], $formdata["time_end_minute"], (isset($formdata["time_end_meridiem"])) ? $formdata["time_end_meridiem"] : "");

      $when_begin = $date_begin . " " . $time_begin;
      $when_end = $date_end . " " . $time_end;

      $real_time_begin = pem_time_subtract($time_begin, $formdata["setup_time_before_hours"], $formdata["setup_time_before_minutes"]);
      $real_time_end = pem_time_add($time_end, $formdata["cleanup_time_after_hours"], $formdata["cleanup_time_after_minutes"]);
      $real_time_begin = pem_time_subtract($real_time_begin, $formdata["buffer_time_before_hours"], $formdata["buffer_time_before_minutes"]);
      $real_time_end = pem_time_add($real_time_end, $formdata["buffer_time_after_hours"], $formdata["buffer_time_after_minutes"]);
      $real_begin = $date_begin . " " . $real_time_begin;
      $real_end = $date_end . " " . $real_time_end;

      $setup_time_before = pem_time_quantity_to_real(array("hours" => $formdata["setup_time_before_hours"], "minutes" => $formdata["setup_time_before_minutes"]));
      $cleanup_time_after = pem_time_quantity_to_real(array("hours" => $formdata["cleanup_time_after_hours"], "minutes" => $formdata["cleanup_time_after_minutes"]));
      $buffer_time_before = pem_time_quantity_to_real(array("hours" => $formdata["buffer_time_before_hours"], "minutes" => $formdata["buffer_time_before_minutes"]));
      $buffer_time_after = pem_time_quantity_to_real(array("hours" => $formdata["buffer_time_after_hours"], "minutes" => $formdata["buffer_time_after_minutes"]));

      // $objResponse->assign("daydisplay", "innerHTML", pem_date(" (l)", $real_begin));

      //$display_begin = "2006-01-01 00:00:00";
      //$display_end = "2008-01-01 00:00:00";


      if ($date_begin == "-00-00" and isset($formdata["spaces"]))
      {
         $objResponse->script("toggleLayer('stage3', 0)");
         $objResponse->assign("datasubmit", "value", "stage2");
         $res = '<h2>' . __("Date/Time Error") . '</h2><br />';
         $res .= '<p><b>' . __("You have not selected a date.  Please use the form fields to make a date selection for your event.") . '</b></p>';

         $objResponse->assign("ajaxbox", "innerHTML", $res);
         $objResponse->script("showPopup('errorbox')");

         $directions = '<div style="float:left;">' . __("Please adjust your time and location to search for openings. ") . '</div>';
         $directions .= '<a href="#" onclick="showPopup(\'errorbox\');" class="inlinebutton"><span>' . __("Show Errors") . '</span></a>';
         $objResponse->assign("directions", "innerHTML", $directions);
      }
      elseif ($date_begin == "-00-00" and !isset($formdata["spaces"]))
      {

      }
      elseif (isset($formdata["spaces"]) AND strtotime($when_begin) >= strtotime($when_end))
      {
         $objResponse->script("toggleLayer('stage3', 0)");
         $objResponse->assign("datasubmit", "value", "stage2");
         $res = '<h2>' . __("Date/Time Error") . '</h2><br />';
         $res .= '<p><b>' . __("The event must begin before it ends.  Please check your date and time settings to remedy the issue.") . '</b></p>';

         $objResponse->assign("ajaxbox", "innerHTML", $res);
         $objResponse->script("showPopup('errorbox')");

         $directions = '<div style="float:left;">' . __("Please adjust your time and location to search for openings. ") . '</div>';
         $directions .= '<a href="#" onclick="showPopup(\'errorbox\');" class="inlinebutton"><span>' . __("Show Errors") . '</span></a>';
         $objResponse->assign("directions", "innerHTML", $directions);
      }
      elseif (isset($formdata["spaces"]))
      {
         $form_spaces = array_keys($formdata["spaces"]);
         require "pem-config.php";
         if (!isset($options)) $options = "";
         $pemdb =& MDB2::connect($dsn);
         $error = "";
         $conflict_text = "";
         if (PEAR::isError($pemdb)) $error = $pemdb->getMessage() . ', ' . $pemdb->getDebugInfo();

         if (!isset($formdata["multi_date_event"]) OR empty($formdata["multi_date_event"]))
         {
            // =========== BEGIN SCHEDULE CONFLICT CHECKS =========================
            if ($current_event_type != "allday" AND (!isset($formdata["multi_day_event"]) OR empty($formdata["multi_day_event"])))
            {
               $conflict_text .= pem_check_schedule_conflicts($pemdb, $error, $when_begin, $date_begin, $real_time_begin, $real_time_end, $form_spaces);
            }
            elseif ($current_event_type != "allday")
            {
               $check_day = strtotime($date_begin);
               $finish_day = strtotime($date_end);
               while($check_day <= $finish_day)
               {
                  $conflict_hold = pem_check_schedule_conflicts($pemdb, $error, $check_day, $date_begin, $real_time_begin, $real_time_end, $form_spaces);
                  if (!empty($conflict_hold))
                  {
                     $conflict_text .= '<li class="head">' . __("Time conflicts for") . ' ' . pem_date($date_format, $check_day) . '</li>' . "\n";
                     $conflict_text .= $conflict_hold;
                  }
                  $check_day = strtotime("+1 day", $check_day);
               }
            }
            // =========== END SCHEDULE CONFLICT CHECKS ===========================
            // =========== BEGIN EVENT CONFLICT CHECKS ============================
            if (!isset($formdata["multi_day_event"]) OR empty($formdata["multi_day_event"]))
            {
               $conflict_text .= pem_check_event_conflicts($pemdb, $error, $real_begin, $real_end, $form_spaces);
            }
            else
            {
               $check_day = strtotime($date_begin);
               $finish_day = strtotime($date_end);
               while($check_day <= $finish_day) // walk through each day looking for a time conflict
               {
                  $conflict_hold = pem_check_event_conflicts($pemdb, $error, $real_begin, $real_end, $form_spaces, $check_day);
                  if (!empty($conflict_hold))
                  {
                     $conflict_text .= '<li class="head">' . __("Space conflicts for") . ' ' . pem_date($date_format, $check_day) . '</li>' . "\n";
                     $conflict_text .= $conflict_hold;
                  }
                  $check_day = strtotime("+1 day", $check_day);
               }
            }
            // =========== END EVENT CONFLICT CHECKS ==============================
         }
         else // Submission is multi-date (recurring)
         {
            $recur_when_begin = $when_begin;
            $recur_when_end = $when_end;
            $recur_real_begin = $real_begin;
            $recur_real_end = $real_end;
            $recur_done = false;
            $recur_count = 0;
            $recur_till = $formdata["recur_till_year"] . "-" . zeropad($formdata["recur_till_month"], 2) . "-" . zeropad($formdata["recur_till_day"], 2);
            while (!$recur_done)
            {

               // =========== BEGIN SCHEDULE CONFLICT CHECKS =========================
               if ($current_event_type != "allday" AND (!isset($formdata["multi_day_event"]) OR empty($formdata["multi_day_event"])))
               {
                  $conflict_hold = pem_check_schedule_conflicts($pemdb, $error, $recur_when_begin, pem_date("Y-m-d", $recur_when_begin), $real_time_begin, $real_time_end, $form_spaces);
                  if (!empty($conflict_hold))
                  {
                     $conflict_text .= '<li class="head">' . __("Time conflicts for") . ' ' . pem_date("l, " . $date_format, $recur_when_begin) . '</li>' . "\n";
                     $conflict_text .= $conflict_hold;
                  }
               }
               elseif ($current_event_type != "allday")
               {
                  $check_day = strtotime(pem_date("Y-m-d", $recur_when_begin));
                  $finish_day = strtotime(pem_date("Y-m-d", $recur_when_end));
                  while($check_day <= $finish_day)
                  {
                     $conflict_hold = pem_check_schedule_conflicts($pemdb, $error, $check_day, pem_date("Y-m-d", $recur_when_begin), $real_time_begin, $real_time_end, $form_spaces);
                     if (!empty($conflict_hold))
                     {
                        $conflict_text .= '<li class="head">' . __("Time conflicts for") . ' ' . pem_date("l, " . $date_format, $check_day) . '</li>' . "\n";
                        $conflict_text .= $conflict_hold;
                     }
                     $check_day = strtotime("+1 day", $check_day);
                  }
               }
               // =========== END SCHEDULE CONFLICT CHECKS ===========================
               // =========== BEGIN EVENT CONFLICT CHECKS ============================
               if (!isset($formdata["multi_day_event"]))
               {
                  $conflict_hold = pem_check_event_conflicts($pemdb, $error, $recur_real_begin, $recur_real_end, $form_spaces);
                  if (!empty($conflict_hold))
                  {
                     $conflict_text .= '<li class="head">' . __("Space conflicts for") . ' ' . pem_date("l, " . $date_format, $recur_when_begin) . '</li>' . "\n";
                     $conflict_text .= $conflict_hold;
                  }
               }
               else
               {
                  $check_day = strtotime(pem_date("Y-m-d", $recur_when_begin));
                  $finish_day = strtotime(pem_date("Y-m-d", $recur_when_end));
                  while($check_day <= $finish_day) // walk through each day looking for a time conflict
                  {
                     $conflict_hold = pem_check_event_conflicts($pemdb, $error, $recur_real_begin, $recur_real_end, $form_spaces, $check_day);
                     if (!empty($conflict_hold))
                     {
                        $conflict_text .= '<li class="head">' . __("Space conflicts for") . ' ' . pem_date("l, " . $date_format, $check_day) . '</li>' . "\n";
                        $conflict_text .= $conflict_hold;
                     }
                     $check_day = strtotime("+1 day", $check_day);
                  }
               }
               // =========== END EVENT CONFLICT CHECKS ==============================
               $recur_count++;
               pem_recur_increment($formdata["recurring_event"], $recur_when_begin, $recur_when_end, $recur_real_begin, $recur_real_end);
               if ($formdata["recurring_duration"] == "aftern")
               {
                  $recur_done = ($recur_count < $formdata["recur_times"]) ? false : true;
               }
               else // ($formdata["recurring_duration"] == "bydate")
               {
                  $recur_done = (strtotime("+1 day", strtotime($recur_till)) <= strtotime($recur_when_begin)) ? true : false;
               }
            } // END while (!$recur_done)
         }


         // Collect and write out options for supplies to the form.
         $got_supplies = false;
         ob_start();
         echo '<br /><div class="indent">' . "\n";

         $sql_spaces = "SELECT space_name, supply_profile, optional_supplies FROM " . $table_prefix . "spaces WHERE id = :space_id";
         $sql_profiles = "SELECT description, profile FROM " . $table_prefix . "supply_profiles WHERE id = :profile_id AND status != 2";
         $sql_supplies = "SELECT id, supply_name FROM " . $table_prefix . "supplies WHERE id = :supply_id AND " . $source_type . " = 1 AND status != 2";

         for ($i = 0; $i < count($form_spaces); $i++)
         {
            $sql_values = array("space_id" => $form_spaces[$i]);
            $sql_prep = $pemdb->prepare($sql_spaces);
            if (PEAR::isError($sql_prep)) $error .= $sql_prep->getMessage() . ', ' . $sql_prep->getDebugInfo();
            $result = $sql_prep->execute($sql_values);
            if (PEAR::isError($result)) $error .= $result->getMessage() . ', ' . $result->getDebugInfo();
            $space_row = $result->fetchRow(MDB2_FETCHMODE_ASSOC);

// echo "supply profile: " . $space_row["supply_profile"] . ", optional profile: " . $space_row["optional_supplies"] . "<br />";

            if (!empty($space_row["supply_profile"]))
            {
               $sql_values = array("profile_id" => $space_row["supply_profile"]);
               $sql_prep = $pemdb->prepare($sql_profiles);
               if (PEAR::isError($sql_prep)) $error .= $sql_prep->getMessage() . ', ' . $sql_prep->getDebugInfo();
               $result = $sql_prep->execute($sql_values);
               if (PEAR::isError($result)) $error .= $result->getMessage() . ', ' . $result->getDebugInfo();
               $standard_row = $result->fetchRow(MDB2_FETCHMODE_ASSOC);
               $standard_profile = unserialize($standard_row["profile"]);
            }
            else
            {
               $standard_profile = "";
            }
            if (!empty($space_row["optional_supplies"]))
            {
               $sql_values = array("profile_id" => $space_row["optional_supplies"]);
               $sql_prep = $pemdb->prepare($sql_profiles);
               if (PEAR::isError($sql_prep)) $error .= $sql_prep->getMessage() . ', ' . $sql_prep->getDebugInfo();
               $result = $sql_prep->execute($sql_values);
               if (PEAR::isError($result)) $error .= $result->getMessage() . ', ' . $result->getDebugInfo();
               $optional_row = $result->fetchRow(MDB2_FETCHMODE_ASSOC);
               $optional_profile = unserialize($optional_row["profile"]);
            }
            else
            {
               $optional_profile = "";
            }

            if (!empty($standard_profile))
            {
               $got_supplies = true;
               echo '<b>' . $space_row["space_name"] . ' Standard Supplies:</b><br />' . "\n";
               echo '<p style="margin-bottom:0;">' . $standard_row["description"] . '</p>' . "\n";
               echo '<ul class="bullets">' . "\n";
               $profile_keys = array_keys($standard_profile);
               for ($j = 0; $j < count($profile_keys); $j++)
               {
                  $sql_values = array("supply_id" => $profile_keys[$j]);
                  $sql_prep = $pemdb->prepare($sql_supplies);
                  if (PEAR::isError($sql_prep)) $error .= $sql_prep->getMessage() . ', ' . $sql_prep->getDebugInfo();
                  $result = $sql_prep->execute($sql_values);
                  if (PEAR::isError($result)) $error .= $result->getMessage() . ', ' . $result->getDebugInfo();
                  $supply_row = $result->fetchRow(MDB2_FETCHMODE_ASSOC);
                  echo '<li class="supplylist">' . $supply_row["supply_name"] . ' (' . $standard_profile[$profile_keys[$j]] . ')</li>' . "\n";
               }
               echo '</ul>' . "\n";
            }
            if (!empty($optional_profile))
            {
               $got_supplies = true;
               echo "<b>" . $space_row["space_name"] . " Optional Supplies:</b><br />" . "\n";
               echo '<p>' . $optional_row["description"] . '</p>' . "\n";
               echo '<div class="indent">' . "\n";
               $profile_keys = array_keys($optional_profile);
               for ($j = 0; $j < count($profile_keys); $j++)
               {
                  $sql_values = array("supply_id" => $profile_keys[$j]);
                  $sql_prep = $pemdb->prepare($sql_supplies);
                  if (PEAR::isError($sql_prep)) $error .= $sql_prep->getMessage() . ', ' . $sql_prep->getDebugInfo();
                  $result = $sql_prep->execute($sql_values);
                  if (PEAR::isError($result)) $error .= $result->getMessage() . ', ' . $result->getDebugInfo();
                  $supply_row = $result->fetchRow(MDB2_FETCHMODE_ASSOC);

                  $opt_field_name = "optsupply" . $form_spaces[$i] . "[" . $supply_row["id"] . "]";
                  $opt_field_tmp =  $formdata["optsupply" . $form_spaces[$i]];
                  $opt_default = (isset($opt_field_tmp[$supply_row["id"]])) ? $opt_field_tmp[$supply_row["id"]] : "";
/*
echo "opt_field_name: $opt_field_name, count: " . $optional_profile[$profile_keys[$j]] . " <br />";
echo "_POST/opt_field_name: ". $opt_default . " <br />";
echo "optsupply1[2]: ";
print_r($formdata["optsupply1"]);
echo " <br />";
*/
                  pem_quantity_select(array("nameid" => $opt_field_name, "default" => $opt_default, "style" => "width:50px;"),  $optional_profile[$profile_keys[$j]]);
                  echo ' ' . $supply_row["supply_name"] . '<br />' . "\n";
               }
               echo '</div>' . "\n";
            }

   /*   if (count($space_list) > 10) echo '<div style="float:left; margin-right:10px;">' . "\n";
      for ($j = 0; $j < count($space_list); $j++)
      {
         if (count($space_list) > 10 AND $j == intval(count($space_list)/2)+1) echo '</div><div style="float:left; margin-right:10px;">' . "\n";
         pem_checkbox(array("name" => "spaces[" . $space_list[$j]["id"] . "]", "status" => $spaces[$space_list[$j]["id"]], "style" => "float:left;", "onclick" => "xajax_check_conflicts(xajax.getFormValues('submitform'), xajax.$('errorbox').innerHTML);"));
         pem_field_label(array("default" => $space_list[$j]["space_name"], "for" => "spaces[" . $space_list[$j]["id"] . "]"));
         if (!empty($space_list[$j]["space_popup"]))
         {
            pem_field_note(array(
               "default" => __("(info)"),
               "link" => $pem_url . "pem-content/" . $space_list[$j]["space_popup"] . "?KeepThis=true&TB_iframe=true&height=400&width=600",
               "title" => $space_list[$j]["space_name"],
               "linkclass" => "thickbox"
              ));
         }
         else echo "<br />\n";
      }
      if (count($space_list) > 10) echo '</div>' . "\n";

*/



         }
         echo '</div>' . "\n";
/*
echo '<pre>';
print_r($formdata);
echo '</pre>';
*/
         $supply_text = ob_get_clean();
         if ($got_supplies) $supply_text = '<br />' . "\n" . '<h3 class="ieheight">' . __("Location Supplies") . '</h3>' . "\n" . $supply_text;

         $objResponse->assign("supply_box", "innerHTML", $supply_text);

         $pemdb->disconnect();

         if (!empty($conflict_text))
         {
            $objResponse->script("toggleLayer('stage3', 0)");
            $objResponse->assign("datasubmit", "value", "stage2");

            $res = '<h2>' . __("Date/Time Error") . '</h2><br />';
            $res .= '<p style="margin-bottom:0;"><b>' . __("Conflicts found.  Please adjust your time and location to search for openings.") . '</b></p>';
            $res .= '<ul class="bullets">' . "\n";
            $res .= $conflict_text;
            $res .= '</ul>' . "\n";

            $objResponse->assign("ajaxbox", "innerHTML", $res);
            $objResponse->script("showPopup('errorbox')");

            $directions = '<div style="float:left;">' . __("Please adjust your time and location to search for openings. ") . '</div>';
            $directions .= '<a href="#" onclick="showPopup(\'errorbox\');" class="inlinebutton"><span>' . __("Show Errors") . '</span></a>';
            $objResponse->assign("directions", "innerHTML", $directions);
         }
         else
         {
            $objResponse->clear("ajaxbox", "innerHTML");
            if (substr($errorbox, 72, 15) != "Required Fields") $objResponse->script("hidePopup('errorbox')");
            $objResponse->script("toggleLayer('stage3', 1)");
            $objResponse->assign("datasubmit", "value", "stage3");
            $directions = '<div style="float:left;">' . __("There are no conflicts with your current time and location settings.  Fill out the remaining fields and click Submit to complete the event.") . '</div>';
            if (substr($errorbox, 72, 15) == "Required Fields") $directions .= '<a href="#" onclick="showPopup(\'errorbox\');" class="inlinebutton"><span>' . __("Show Errors") . '</span></a>';
            $objResponse->assign("directions", "innerHTML", $directions);
         }
      }
      else // No spaces selected
      {
         $objResponse->script("hidePopup('errorbox')");
         $objResponse->script("toggleLayer('stage3', 0)");
         $objResponse->assign("datasubmit", "value", "stage2");
         $res = __("Select time, date, and location options to check the calendar for conflicts.");
         $objResponse->assign("directions", "innerHTML", $res);
         $objResponse->assign("supply_box", "innerHTML", "");
      }
   }
   return $objResponse;
} // END check_conflicts

/* =============================================================================
============================= END AJAX SECTION =================================
============================================================================= */
$inajax = false;

$pagetitle = "Add Event";
$page_access_requirement = array("Internal Calendar" => "Add", "External Calendar" => "Add", "Internal Side Box" => "Add", "External Side Box" => "Add");
//BASED ON ACCESS ADJUST THE NAVIGATION BAR ACCORDINGLY TO SHOW THE PUB OPTIONS VS THE ADMIN OPTIONS
$navigation = "administration";
$cache_set = array("current_navigation" => "events");

$use_thickbox = true;
$use_xajax = true;
$use_calpop = true;
include_once "pem-includes/header.php";
extract(pem_get_settings());
extract(pem_scheduling_boundaries());

//echo "<pre>";
//print_r($_POST);
//echo "</pre><br />";



$showaddform = true;
if (isset($datasubmit))
{
   unset($error);
   if (isset($required) AND $datasubmit != "stage1" AND $source_type != $old_source_type)
   {
      $directions = __("You have changed types and reset field options and behaviors.  Please check over the form to be sure you have completed the necessary fields.");
   }
   elseif (isset($required) AND $datasubmit == "stage3")
   {
      foreach ($required as $key => $val)  // cycle through required fields to check for data
      {
         $check_name = trim($key, "\x22\x27");
         $check_value = ${$check_name};
         switch(true)
         {
         case (empty($check_value)):
            $label = (substr($required[$key], -1, 1) == ":") ? substr($required[$key], 0, strlen($required[$key])-1) : $required[$key];
            $error[] = sprintf(__("%s cannot be empty."), $label);
            break;
         case ($check_value["'type'"] == "textinput"):
            $label = (substr($required[$key], -1, 1) == ":") ? substr($required[$key], 0, strlen($required[$key])-1) : $required[$key];
            $meta_value = ${$check_name};
            if (empty($meta_value["'input'"])) $error[] = sprintf(__("%s cannot be empty."), $label);
            break;
         case ($check_value["'type'"] == "checkbox"):
            $label = (substr($required[$key], -1, 1) == ":") ? substr($required[$key], 0, strlen($required[$key])-1) : $required[$key];
            $meta_value = ${$check_name};
            if (isset($meta_value["'box'"])) $error[] = sprintf(__("%s cannot be empty."), $label);
            break;
         case ($check_value["'type'"] == "boolean"):
            $label = (substr($required[$key], -1, 1) == ":") ? substr($required[$key], 0, strlen($required[$key])-1) : $required[$key];
            $meta_value = ${$check_name};
            if (empty($meta_value["'boolean'"])) $error[] = sprintf(__("%s cannot be empty."), $label);
            break;
         case ($check_value["'type'"] == "select"):
            $label = (substr($required[$key], -1, 1) == ":") ? substr($required[$key], 0, strlen($required[$key])-1) : $required[$key];
            $meta_value = ${$check_name};
            if (empty($meta_value["'select'"])) $error[] = sprintf(__("%s cannot be empty."), $label);
            break;
         case ($check_value["'type'"] == "contact"):
            $meta_value = ${$check_name . "_input"};
            $labels = explode(",", $required[$key]);
            for ($i = 0; $i < count($labels); $i++)
            {
               $labels[$i] = (substr($labels[$i], -1, 1) == ":") ? substr($labels[$i], 0, strlen($labels[$i])-1) : $labels[$i];
            }
            $meta_keys = array_keys($meta_value);
            for ($i = 0; $i < count($meta_keys); $i++)
            {

//echo $meta_keys[$i] . " <br />";

// ICPL HACK contact requirement
               if ($check_name == "meta4")
               {
                  if ($meta5["'select'"] == 3)
                  {
                     if ($meta_keys[$i] == "'name1'" AND empty($meta_value["'name1'"])) $error[] = sprintf(__("%s cannot be empty."), $labels[$i]);
//                     if ($meta_keys[$i] == "'phone1'" AND empty($meta_value["'phone1'"]) OR $meta_value[$meta_keys[$i]] == $default_phone) $error[] = sprintf(__("%s cannot be empty."), $labels[$i]);
                     if ($meta_keys[$i] == "'phone1'" AND empty($meta_value["'phone1'"])) $error[] = sprintf(__("%s cannot be empty."), $labels[$i]);
                  }
               }
//               elseif ((empty($meta_value[$meta_keys[$i]]) AND !empty($labels[$i])) OR
//                  (strlen($default_phone) < 7 AND substr($meta_keys[$i], 1, -2) == "phone" AND $meta_value[$meta_keys[$i]] == $default_phone))
//               {
//                  $error[] = __("Contact Phone cannot be empty.");
//               }
            }
            break;
         }
      }
   }
   if (isset($validatenum) AND $datasubmit == "stage3")// special section for special required attributes.
   {
      foreach ($validatenum as $key => $val)  // cycle through required fields to check for data
      {
         $check_name = trim($key, "\x22\x27");
         $check_value = ${$check_name};

         if ($check_value != "" AND !is_numeric($check_value))
         {
            $label = (substr($required[$key], -1, 1) == ":") ? substr($required[$key], 0, strlen($required[$key])-1) : $required[$key];
            $error[] = sprintf(__("%s must be a number."), $label);
         }
      }
   }
   if (isset($entry_seats_expected) OR isset($date_seats_expected)) // Combined room capacity must be equal or larger than the expected audience
   {
      $total_capacity = 0;
      $unlimited = false;
// ICPL HACK 400 capacity for A/B/C
      if (isset($spaces[1]) AND isset($spaces[2]) AND isset($spaces[3]))
      {
         $total_capacity = 400;
      }
      elseif (isset($spaces)) foreach ($spaces AS $key => $value)
      {
         if (!$capacity[$key]) $unlimited = true;
         $total_capacity += $capacity[$key];
      }
      if (!$unlimited AND $total_capacity != 0 AND $entry_seats_expected > $total_capacity) $error[] = sprintf(__("Expected attendance of %s is greater than the total selected location capacity %s."), $entry_seats_expected, $total_capacity);
      if (!$unlimited AND $total_capacity != 0 AND $date_seats_expected > $total_capacity) $error[] = sprintf(__("Expected attendance of %s is greater than the total selected location capacity %s."), $date_seats_expected, $total_capacity);
   }

   if (isset($date_begin_year))
   {
      $recurring_event = (isset($recurring_event)) ? 1 : 0;

      // ====== Compile multi-field date/time variables =======
      $date_begin = $date_begin_year . "-" . $date_begin_month . "-" . $date_begin_day;
      $date_end = $date_end_year . "-" . $date_end_month . "-" . $date_end_day;
      $time_begin = pem_time($time_begin_hour, $time_begin_minute, $time_begin_meridiem);
      $time_end = pem_time($time_end_hour, $time_end_minute, $time_end_meridiem);

      $entry_reg_begin = (isset($entry_reg_begin_year)) ? $entry_reg_begin_year . "-" . $entry_reg_begin_month . "-" . $entry_reg_begin_day : "";
      $entry_reg_end = (isset($entry_reg_end_year)) ? $entry_reg_end_year . "-" . $entry_reg_end_month . "-" . $entry_reg_end_day : "";
      $date_reg_begin = (isset($date_reg_begin_year)) ? $date_reg_begin_year . "-" . $date_reg_begin_month . "-" . $date_reg_begin_day : "";
      $date_reg_end = (isset($date_reg_end_year)) ? $date_reg_end_year . "-" . $date_reg_end_month . "-" . $date_reg_end_day : "";

      $setup_time_before = pem_time_quantity_to_real(array("hours" => $setup_time_before_hours, "minutes" => $setup_time_before_minutes));
      $cleanup_time_after = pem_time_quantity_to_real(array("hours" => $cleanup_time_after_hours, "minutes" => $cleanup_time_after_minutes));
      $buffer_time_before = pem_time_quantity_to_real(array("hours" => $buffer_time_before_hours, "minutes" => $buffer_time_before_minutes));
      $buffer_time_after = pem_time_quantity_to_real(array("hours" => $buffer_time_after_hours, "minutes" => $buffer_time_after_minutes));

      // ====== Minimum padding required error checking =======
      $setup_min_array = pem_real_to_time_quantity($setup_min);
      $buffer_min_array = pem_real_to_time_quantity($buffer_min);

      if (empty($setup_min_array["hours"])) $setup_min_text = sprintf(__("%s minutes"), $setup_min_array["minutes"]);
      elseif ($setup_min_array["hours"] == 1) $setup_min_text = sprintf(__("1 hour and %s minutes"), $setup_min_array["minutes"]);
      else $setup_min_text = sprintf(__("%1\$s hours and %2\$s minutes"), $setup_min_array["hours"], $setup_min_array["minutes"]);
      if (empty($buffer_min_array["hours"])) $buffer_min_text = sprintf(__("%s minutes"), $buffer_min_array["minutes"]);
      elseif ($buffer_min_array["hours"] == 1) $buffer_min_text = sprintf(__("1 hour and %s minutes"), $buffer_min_array["minutes"]);
      else $buffer_min_text = sprintf(__("%1\$s hours and %2\$s minutes"), $buffer_min_array["hours"], $buffer_min_array["minutes"]);

      if ($setup_time_before < $setup_min) $error[] = sprintf(__("Setup Time Before must be at least the set minimum of %s."), $setup_min_text);
      if ($cleanup_time_after < $setup_min) $error[] = sprintf(__("Cleanup Time After must be at least the set minimum of %s."), $setup_min_text);
      if ($buffer_time_before < $buffer_min) $error[] = sprintf(__("Buffer Time Before must be at least the set minimum of %s."), $buffer_min_text);
      if ($buffer_time_after < $buffer_min) $error[] = sprintf(__("Buffer Time After must be at least the set minimum of %s."), $buffer_min_text);
   }

   $current_event_type = pem_cache_get("current_event_type");
   switch (true)
   {
   case ($current_event_type == "scheduled"):
      $source_type = ($_POST["source_type"] == "internal") ? "internal_scheduled" : "external_scheduled";
      $page_title = __("Add Calendar Event");
      break;
   case ($current_event_type == "unscheduled"):
      $source_type = ($_POST["source_type"] == "internal") ? "internal_unscheduled" : "external_unscheduled";
      $page_title = __("Add Side Box Event");
      break;
   case ($current_event_type == "allday"):
      $source_type = "internal_scheduled";
      $page_title = __("Add All-Day Event");
      break;
   }

//echo "datasubmit: $datasubmit";
//exit;


   switch (true)
   {
   case ($datasubmit == "stage2"):
      $showaddform = false;
      pem_fieldset_begin($page_title);
      echo_form("stage2", $error, $directions);
      pem_fieldset_end();
      break;
   case ($datasubmit == "stage3" AND isset($error)):
      $showaddform = false;
      pem_fieldset_begin($page_title);
      echo_form("stage3", $error, $directions);
      pem_fieldset_end();
      break;
   case ($datasubmit == "stage3"):
      $date_begin = $_POST["date_begin_year"] . "-" . zeropad($_POST["date_begin_month"], 2) . "-" . zeropad($_POST["date_begin_day"], 2);
      if ((isset($_POST["multi_date_event"]) and $_POST["multi_date_event"] != 0) or (isset($_POST["multi_day_event"]) and $_POST["multi_day_event"] != 0))
      {
         $date_end = $_POST["date_end_year"] . "-" . zeropad($_POST["date_end_month"], 2) . "-" . zeropad($_POST["date_end_day"], 2);
      }
      else
      {
         $date_end = $date_begin;
      }
      $time_begin = pem_time($_POST["time_begin_hour"], $_POST["time_begin_minute"], (isset($_POST["time_begin_meridiem"])) ? $_POST["time_begin_meridiem"] : "");
      $time_end = pem_time($_POST["time_end_hour"], $_POST["time_end_minute"], (isset($_POST["time_end_meridiem"])) ? $_POST["time_end_meridiem"] : "");

//echo "begin: $date_begin<br />";
//echo "end: $date_end<br />";
//exit;

      $when_begin = $date_begin . " " . $time_begin;
      $when_end = $date_end . " " . $time_end;

      $real_time_begin = pem_time_subtract($time_begin, $_POST["setup_time_before_hours"], $_POST["setup_time_before_minutes"]);
      $real_time_end = pem_time_add($time_end, $_POST["cleanup_time_after_hours"], $_POST["cleanup_time_after_minutes"]);
      $real_time_begin = pem_time_subtract($real_time_begin, $_POST["buffer_time_before_hours"], $_POST["buffer_time_before_minutes"]);
      $real_time_end = pem_time_add($real_time_end, $_POST["buffer_time_after_hours"], $_POST["buffer_time_after_minutes"]);
      $real_begin = $date_begin . " " . $real_time_begin;
      $real_end = $date_end . " " . $real_time_end;

      $setup_time_before = pem_time_quantity_to_real(array("hours" => $_POST["setup_time_before_hours"], "minutes" => $_POST["setup_time_before_minutes"]));
      $cleanup_time_after = pem_time_quantity_to_real(array("hours" => $_POST["cleanup_time_after_hours"], "minutes" => $_POST["cleanup_time_after_minutes"]));
      $buffer_time_before = pem_time_quantity_to_real(array("hours" => $_POST["buffer_time_before_hours"], "minutes" => $_POST["buffer_time_before_minutes"]));
      $buffer_time_after = pem_time_quantity_to_real(array("hours" => $_POST["buffer_time_after_hours"], "minutes" => $_POST["buffer_time_after_minutes"]));

      $form_spaces = array_keys($_POST["spaces"]);
      if (!isset($options)) $options = "";
      $pemdb =& MDB2::connect($dsn);
      $error = "";
      $conflict_text = "";
      if (PEAR::isError($pemdb)) $error = $pemdb->getMessage() . ', ' . $pemdb->getDebugInfo();

      if ($conflicting AND (!isset($_POST["multi_date_event"]) OR $_POST["multi_date_event"] == 0))
      {
         // =========== BEGIN EVENT CONFLICT CHECKS ============================
         if (!isset($_POST["multi_day_event"]) OR $_POST["multi_day_event"] == 0)
         {
            $conflict_text .= pem_check_event_conflicts($pemdb, $error, $real_begin, $real_end, $form_spaces);
         }
         else
         {
            $check_day = strtotime($date_begin);
            $finish_day = strtotime($date_end);
            while($check_day <= $finish_day) // walk through each day looking for a time conflict
            {
               $conflict_hold = pem_check_event_conflicts($pemdb, $error, $real_begin, $real_end, $form_spaces, $check_day);
               if (!empty($conflict_hold))
               {
               $conflict_text .= '<li class="head">' . __("Space conflicts for") . ' ' . pem_date($date_format, $check_day) . '</li>' . "\n";
               $conflict_text .= $conflict_hold;
               }
               $check_day = strtotime("+1 day", $check_day);
            }
         }
         // =========== END EVENT CONFLICT CHECKS ==============================
      }
      elseif ($conflicting) // Submission is multi-date (recurring)
      {
         $recur_when_begin = $when_begin;
         $recur_when_end = $when_end;
         $recur_real_begin = $real_begin;
         $recur_real_end = $real_end;
         $recur_done = false;
         $recur_count = 0;
         $recur_till = $_POST["recur_till_year"] . "-" . zeropad($_POST["recur_till_month"], 2) . "-" . zeropad($_POST["recur_till_day"], 2);
         while (!$recur_done)
         {
            // =========== BEGIN EVENT CONFLICT CHECKS ============================
            if (!isset($_POST["multi_day_event"]))
            {
               $conflict_hold = pem_check_event_conflicts($pemdb, $error, $recur_real_begin, $recur_real_end, $form_spaces);
               if (!empty($conflict_hold))
               {
                  $conflict_text .= '<li class="head">' . __("Space conflicts for") . ' ' . pem_date("l, " . $date_format, $recur_when_begin) . '</li>' . "\n";
                  $conflict_text .= $conflict_hold;
               }
            }
            else
            {
               $check_day = strtotime(pem_date("Y-m-d", $recur_when_begin));
               $finish_day = strtotime(pem_date("Y-m-d", $recur_when_end));
               while($check_day <= $finish_day) // walk through each day looking for a time conflict
               {
                  $conflict_hold = pem_check_event_conflicts($pemdb, $error, $recur_real_begin, $recur_real_end, $form_spaces, $check_day);
                  if (!empty($conflict_hold))
                  {
                     $conflict_text .= '<li class="head">' . __("Space conflicts for") . ' ' . pem_date("l, " . $date_format, $check_day) . '</li>' . "\n";
                     $conflict_text .= $conflict_hold;
                  }
                  $check_day = strtotime("+1 day", $check_day);
               }
            }
            // =========== END EVENT CONFLICT CHECKS ==============================
            $recur_count++;
            pem_recur_increment($_POST["recurring_event"], $recur_when_begin, $recur_when_end, $recur_real_begin, $recur_real_end);
            if ($_POST["recurring_duration"] == "aftern")
            {
               $recur_done = ($recur_count < $_POST["recur_times"]) ? false : true;
            }
            else // ($_POST["recurring_duration"] == "bydate")
            {
               $recur_done = (strtotime("+1 day", strtotime($recur_till)) <= strtotime($recur_when_begin)) ? true : false;
            }
         } // END while (!$recur_done)
      }

//      $conflict_text = "multi day: " . $_POST["multi_day_event"];


      if (!empty($conflict_text))
      {
//echo "watch point: " . $conflict_text;

         $conflicts = '<p style="margin-bottom:0;"><b>' . __("Conflicts found.  Please adjust your time and location to search for openings.") . '</b></p>';
         $conflicts .= '<ul class="bullets">' . "\n";
         $conflicts .= $conflict_text;
         $conflicts .= '</ul>' . "\n";

         pem_fieldset_begin(__("Add Calendar Event"));
         echo_form("stage3", $error, $directions, $conflicts);
         pem_fieldset_end();
      }
      else
      {
         MDB2::loadFile("Date"); // load Date helper class
         $entry_created_stamp = MDB2_Date::mdbNow();
         $date_created_stamp = $entry_created_stamp;


         $time_begin_array = explode(":", $time_begin);
         $time_end_array = explode(":", $time_end);
         if ($_POST["multi_date_event"] == 0 and $_POST["multi_day_event"] == 0)
         {
            $when_begin = MDB2_Date::date2Mdbstamp($time_begin_array[0], $time_begin_array[1], $time_begin_array[2], $date_begin_month, $date_begin_day, $date_begin_year);
            $when_end = MDB2_Date::date2Mdbstamp($time_end_array[0], $time_end_array[1], $time_end_array[2], $date_begin_month, $date_begin_day, $date_begin_year);
         }
         else
         {
            $when_begin = MDB2_Date::date2Mdbstamp($time_begin_array[0], $time_begin_array[1], $time_begin_array[2], $date_begin_month, $date_begin_day, $date_begin_year);
            $when_end = MDB2_Date::date2Mdbstamp($time_end_array[0], $time_end_array[1], $time_end_array[2], $date_end_month, $date_end_day, $date_end_year);
         }

         $real_begin_time = pem_time_subtract($time_begin, $setup_time_before_hours, $setup_time_before_minutes);
         $real_end_time = pem_time_add($time_end, $cleanup_time_after_hours, $cleanup_time_after_minutes);
         $real_begin_time = pem_time_subtract($real_begin_time, $buffer_time_before_hours, $buffer_time_before_minutes);
         $real_end_time = pem_time_add($real_end_time, $buffer_time_after_hours, $buffer_time_after_minutes);
         $real_begin_array = explode(":", $real_begin_time);
         $real_end_array = explode(":", $real_end_time);

         if ($_POST["multi_date_event"] == 0 and $_POST["multi_day_event"] == 0)
         {
            $real_begin = MDB2_Date::date2Mdbstamp($real_begin_array[0], $real_begin_array[1], $real_begin_array[2], $date_begin_month, $date_begin_day, $date_begin_year);
            $real_end = MDB2_Date::date2Mdbstamp($real_end_array[0], $real_end_array[1], $real_end_array[2], $date_begin_month, $date_begin_day, $date_begin_year);
         }
         else
         {
            $real_begin = MDB2_Date::date2Mdbstamp($real_begin_array[0], $real_begin_array[1], $real_begin_array[2], $date_begin_month, $date_begin_day, $date_begin_year);
            $real_end = MDB2_Date::date2Mdbstamp($real_end_array[0], $real_end_array[1], $real_end_array[2], $date_end_month, $date_end_day, $date_end_year);
         }

         $display_begin = $entry_created_stamp;
         $display_end = MDB2_Date::unix2Mdbstamp (strtotime("+" . $display_duration . " month"));

         $entry_type_nums = array("internal_scheduled" => 1, "external_scheduled" => 2, "internal_unscheduled" => 3, "external_unscheduled" => 3);
         $entry_type = $entry_type_nums[$source_type];

         $login = pem_get_login();
         $user_id = auth_get_user_id($login);
         if (empty($user_id)) $user_id = 2;

//echo "TESTING DATA: <br /><pre>";
//print_r($_POST);
//echo "</pre><br />";


         if (isset($meta)) foreach ($meta as $key => $meta_id)
         {
            $meta_field_name = ${"meta" . $meta_id};
            $thismeta =& $meta_data[$meta_field_name["'parent'"]][$meta_id];

            switch(true)
            {
            case ($meta_field_name["'type'"] == "textinput"):
               $thismeta["type"] = $meta_field_name["'type'"];
               $thismeta["data"] = $meta_field_name["'input'"];
               break;
            case ($meta_field_name["'type'"] == "checkbox"):
               if (isset($meta_field_name["'box'"]))
               {
                  $thismeta["type"] = $meta_field_name["'type'"];
                  $thismeta["data"] = 1;
               }
               break;
            case ($meta_field_name["'type'"] == "boolean"):
               $thismeta["type"] = $meta_field_name["'type'"];
               if (!isset($meta_field_name["'boolean'"]) OR empty($meta_field_name["'boolean'"])) $thismeta["data"] = 0;
               else $thismeta["data"] = ($meta_field_name["'boolean'"] == 1) ? 0 : 1;
               break;
            case ($meta_field_name["'type'"] == "select"):
               $thismeta["type"] = $meta_field_name["'type'"];
               $thismeta["data"] = $meta_field_name["'select'"];
               break;
            case ($meta_field_name["'type'"] == "contact"):
               $thismeta["type"] = $meta_field_name["'type'"];
               $meta_input_name = ${"meta" . $meta_id . "_input"};
               foreach ($meta_input_name as $key => $val)
               {
// ICPL HACK dropping default phone
                  $meta_value = ($val == '319-') ? "" : $meta_value;
                  $thismeta[trim($key, "\x22\x27")] = $val;
               }
               break;
            }
         }

//echo "--------------------------<br /><pre>";
//print_r($meta_data);
//echo "</pre><br />";

         // reset the 1/2 booleans back to the 0/1 standard
         $e_open_to_public = ($entry_open_to_public == 1) ? 0 : 1;
         $e_visible_to_public = ($entry_visible_to_public == 1) ? 0 : 1;
         $d_open_to_public = ($date_open_to_public == 1) ? 0 : 1;
         $d_visible_to_public = ($date_visible_to_public == 1) ? 0 : 1;

         $conflicting = (isset($conflicting)) ? 1 : 0;
         $allday = ($current_event_type == "allday") ? 1 : 0;
/*
echo "spaces for supply build: ";
print_r($spaces);
echo "<br />";
*/
         foreach ($spaces AS $key => $value)
         {
            $opt_field =  ${"optsupply" . $key};
            if (isset($opt_field)) $supplies[$key] = $opt_field;
         }
/*
echo "supply build result: ";
print_r($supplies);
echo "<br />";
*/
//echo "<pre>";
         $entry_data = array(
            "entry_name" => $entry_name, "entry_type" => $entry_type,
            "entry_description" => $entry_description, "entry_category" => $entry_category,
            "entry_presenter" => $entry_presenter, "entry_presenter_type" => $entry_presenter_type,
            "entry_reg_require" => $entry_reg_require, "entry_reg_current" => $entry_reg_current,
            "entry_reg_max" => $entry_reg_max, "entry_allow_wait" => $entry_allow_wait,
            "entry_reg_begin" => $entry_reg_begin, "entry_reg_end" => $entry_reg_end,
            "entry_open_to_public" => $e_open_to_public, "entry_visible_to_public" => $e_visible_to_public,
            "entry_seats_expected" => $entry_seats_expected, "entry_priv_notes" => $entry_priv_notes,
            "entry_meta" => serialize($meta_data[0]),
            "entry_created_by" => $user_id, "entry_created_stamp" => $entry_created_stamp
         );
//print_r($entry_data);
         $entry_types = array(
            "entry_name" => "text", "entry_type" => "text",
            "entry_description" => "text", "entry_category" => "integer",
            "entry_presenter" => "text", "entry_presenter_type" => "integer",
            "entry_reg_require" => "boolean", "entry_reg_current" => "integer",
            "entry_reg_max" => "integer", "entry_allow_wait" => "boolean",
            "entry_reg_begin" => "date", "entry_reg_end" => "date",
            "entry_open_to_public" => "boolean", "entry_visible_to_public" => "boolean",
            "entry_seats_expected" => "integer", "entry_priv_notes" => "text",
            "entry_meta" => "clob",
            "entry_created_by" => "text", "entry_created_stamp" => "timestamp"
         );
        $entry_id = pem_add_row("entries", $entry_data, $entry_types);
         $date_data = array(
            "entry_id" => $entry_id, "when_begin" => $when_begin, "when_end" => $when_end,
            "setup_time_before" => $setup_time_before, "cleanup_time_after" => $cleanup_time_after,
            "buffer_time_before" => $buffer_time_before, "buffer_time_after" => $buffer_time_after,
            "real_begin" => $real_begin, "real_end" => $real_end,
            "conflicting" => $conflicting, "allday" => $allday,
            "display_begin" => $display_begin, "display_end" => $display_end,
            "spaces" => serialize(array_keys($spaces)), "supplies" => serialize($supplies),
            "date_name" => $date_name,
            "date_description" => $date_description, "date_category" => $date_category,
            "date_presenter" => $date_presenter, "date_presenter_type" => $date_presenter_type,
            "date_reg_require" => $date_reg_require, "date_reg_current" => $date_reg_current,
            "date_reg_max" => $date_reg_max, "date_allow_wait" => $date_allow_wait,
            "date_reg_begin" => $date_reg_begin, "date_reg_end" => $date_reg_end,
            "date_open_to_public" => $d_open_to_public, "date_visible_to_public" => $d_visible_to_public,
            "date_seats_expected" => $date_seats_expected, "date_priv_notes" => $date_priv_notes,
            "date_meta" => serialize($meta_data[1]),
            "date_created_by" => $user_id, "date_created_stamp" => $date_created_stamp
         );
//print_r($date_data);
//exit;
         $date_types = array(
            "entry_id" => "integer", "when_begin" => "timestamp", "when_end" => "timestamp",
            "setup_time_before" => "float", "cleanup_time_after" => "float",
            "buffer_time_before" => "float", "buffer_time_after" => "float",
            "real_begin" => "timestamp", "real_end" => "timestamp",
            "conflicting" => "boolean", "allday" => "boolean",
            "display_begin" => "timestamp", "display_end" => "timestamp",
            "spaces" => "clob", "supplies" => "clob",
            "date_name" => "text",
            "date_description" => "text", "date_category" => "integer",
            "date_presenter" => "text", "date_presenter_type" => "integer",
            "date_reg_require" => "boolean", "date_reg_current" => "integer",
            "date_reg_max" => "integer", "date_allow_wait" => "boolean",
            "date_reg_begin" => "date", "date_reg_end" => "date",
            "date_open_to_public" => "boolean", "date_visible_to_public" => "boolean",
            "date_seats_expected" => "integer", "date_priv_notes" => "text",
            "date_meta" => "clob",
            "date_created_by" => "text", "date_created_stamp" => "timestamp"
         );
//echo "</pre><br />======== END TEST ====================<br />";
/*
echo "when_begin: $when_begin <br />";
echo "when_end: $when_end <br />";
echo "real_begin: $real_begin <br />";
echo "real_end: $real_end <br />";

echo "setup_time_before: $setup_time_before <br />";
echo "cleanup_time_after: $cleanup_time_after <br />";
echo "buffer_time_before: $buffer_time_before <br />";
echo "buffer_time_after: $buffer_time_after <br />";
*/

         if (!isset($_POST["multi_date_event"]) OR $_POST["multi_date_event"] == 0)
         {
            $date_id = pem_add_row("dates", $date_data, $date_types);
//            header('Location: /view.php?e=event&did=' . $date_id);
            echo '<p><b>' . __("New event added.") . '</b></p>' . "\n";
            echo '<ul class="bullets">' . "\n";
            echo '<li><a href="/view.php?did=' . $date_id . '">' . __("View New Event") . '</a></li>' . "\n";
//            echo '<li><a href="/view.php?did=' . $date_id . '"></a>' . __("Add Another Date to New Event") . '</a></li>' . "\n";
            echo '<li><a href="/">' . __("Return to Calendar") . '</a></li>' . "\n";
            echo '</ul>' . "\n";
         }
         else // Submission is multi-date (recurring)
         {
            $date_data["when_begin"] = $when_begin;
            $date_data["when_end"] = $when_end;
            $date_data["real_begin"] = $real_begin;
            $date_data["real_end"] = $real_end;

            $recur_done = false;
            $recur_count = 0;
            $recur_till = $_POST["recur_till_year"] . "-" . zeropad($_POST["recur_till_month"], 2) . "-" . zeropad($_POST["recur_till_day"], 2);

            while (!$recur_done)
            {
               $date_id[] = array(pem_add_row("dates", $date_data, $date_types), $date_data["when_begin"]);
               $recur_count++;
               pem_recur_increment($_POST["recurring_event"], $date_data["when_begin"], $date_data["when_end"], $date_data["real_begin"], $date_data["real_end"]);
               if ($_POST["recurring_duration"] == "aftern")
               {
                  $recur_done = ($recur_count < $_POST["recur_times"]) ? false : true;
               }
               else // ($_POST["recurring_duration"] == "bydate")
               {
                  $recur_done = (strtotime("+1 day", strtotime($recur_till)) <= strtotime($date_data["real_end"])) ? true : false;
               }
            } // END while (!$recur_done)

            echo '<p><b>' . __("New event(s) added.") . '</b></p>' . "\n";
            echo '<ul class="bullets">' . "\n";
            for ($i = 0; $i < count($date_id); $i++)
            {
               echo '<li><a href="/view.php?did=' . $date_id[$i][0] . '">' . __("View New Event") . '</a> (' .  pem_date($date_format, $date_id[$i][1]) . ')</li>' . "\n";
            }
            echo '<li><a href="/">' . __("Return to Calendar") . '</a></li>' . "\n";
            echo '</ul>' . "\n";

//ICPL HACK post-submission text
echo '<p>The new event submission completed with no apparent conflicts or problems. Library staff will review your request before it is added to the calendar. If you have any questions or need to change your event information, contact the Fiction Desk at 319.356.5200 option 4 or <a href="mailto:calendar@icpl.org">calendar@icpl.org</a>.</p>' . "\n";
echo '<p>If you entered an email address, a confirmation message will be mailed to you when the event is approved.</p>' . "\n";
echo '<p>To view your submission information, click on View New Event above.</p>' . "\n";



         }

      }
      $showaddform = false;
      break;
   }
}
elseif (isset($did)) // Action is Copy to New Event
{
   $pemdb =& mdb2_connect($dsn, $options, "connect");
   $sql = "SELECT * FROM " . $table_prefix . "entries as e, " . $table_prefix . "dates as d WHERE ";
   $sql .= "e.id = d.entry_id AND ";
   $sql .= "d.id = :date_id AND ";
   $sql .= "e.entry_status != 2 AND ";
   $sql .= "d.date_status != 2";
   $sql_values = array("date_id" => $did);
   $eventret = pem_exec_sql($sql, $sql_values);
   $source_event = $eventret[0];

   $source_event["entry_open_to_public"] = ($eventret[0]["entry_open_to_public"] == 1) ? 2 : 1;
   $source_event["entry_visible_to_public"] = ($eventret[0]["entry_visible_to_public"] == 1) ? 2 : 1;
   $source_event["date_open_to_public"] = ($eventret[0]["date_open_to_public"] == 1) ? 2 : 1;
   $source_event["date_visible_to_public"] = ($eventret[0]["date_visible_to_public"] == 1) ? 2 : 1;

   $spaces_array = unserialize($eventret[0]["spaces"]);
   unset($spaces);
   foreach ($spaces_array AS $value) $spaces_array_tmp[$value] = 1;
   $source_event["spaces"] = $spaces_array_tmp;
   unset($spaces_array_tmp);
   $source_event["supplies"] = unserialize($eventret[0]["supplies"]);
   $entry_meta = unserialize($eventret[0]["entry_meta"]);
   $date_meta = unserialize($eventret[0]["date_meta"]);

   unset($meta);
   if (!empty($entry_meta)) foreach ($entry_meta AS $key => $value)
   {
      $meta[] = $key;
      switch($value["type"])
      {
      case ("textinput"):
         $source_event["meta" . $key] = array("type" => $value["type"], "input" => $value["data"]);
         break;
      case ("checkbox"):
         $source_event["meta" . $key] = array("type" => $value["type"], "box" => $value["data"]);
         break;
      case ("boolean"):
         $source_event["meta" . $key] = array("type" => $value["type"], "boolean" => $value["data"]);
         break;
      case ("select"):
         $source_event["meta" . $key] = array("type" => $value["type"], "select" => $value["data"]);
         break;
      case ("contact"):
         $source_event["meta" . $key] = array("type" => $value["type"]);
         foreach ($value AS $vkey => $vvalue)
         {
            if ($vkey != "type") $source_event["meta" . $key . "_input"]["'$vkey'"] = $vvalue;
         }
         break;
      }
   }
   if (!empty($date_meta)) foreach ($date_meta AS $key => $value)
   {
      $meta[] = $key;
      switch($value["type"])
      {
      case ("textinput"):
         $source_event["meta" . $key] = array("'type'" => $value["type"], "'input'" => $value["data"]);
         break;
      case ("checkbox"):
         $source_event["meta" . $key] = array("'type'" => $value["type"], "'box'" => $value["data"]);
         break;
      case ("boolean"):
         $boolval = ($value["data"] == 1) ? 2 : 1;
         $source_event["meta" . $key] = array("'type'" => $value["type"], "'boolean'" => $boolval);
         break;
      case ("select"):
         $source_event["meta" . $key] = array("'type'" => $value["type"], "'select'" => $value["data"]);
         break;
      case ("contact"):
         $source_event["meta" . $key] = array("'type'" => $value["type"]);
         foreach ($value AS $vkey => $vvalue)
         {
            if ($vkey != "type") $source_event["meta" . $key . "_input"]["'$vkey'"] = $vvalue;
         }
         break;
      }
   }
   if (isset($meta)) $source_event["meta"] = $meta;
   unset($meta);

   switch (true)
   {
   case ($eventret[0]["entry_type"] == 1):
      $source_event["source_type"] = "internal_scheduled";
      $source_event["current_event_type"] = "scheduled";
      break;
   case ($eventret[0]["entry_type"] == 2):
      $source_event["source_type"] = "external_scheduled";
      $source_event["current_event_type"] = "scheduled";
      break;
   case ($eventret[0]["entry_type"] == 3):
      $source_event["source_type"] = "internal_unscheduled";
      $source_event["current_event_type"] = "unscheduled";
      break;
   case ($eventret[0]["entry_type"] == 4):
      $source_event["source_type"] = "external_unscheduled";
      $source_event["current_event_type"] = "unscheduled";
      break;
   }

   $source_event["date_begin"] = pem_date("Y-m-d", $eventret[0]["when_begin"]);
   $source_event["date_end"] = pem_date("Y-m-d", $eventret[0]["when_end"]);
   $source_event["time_begin"] = pem_date("H:i:s", $eventret[0]["when_begin"]);
   $source_event["time_end"] = pem_date("H:i:s", $eventret[0]["when_end"]);

   $source_event["multi_day_event"] = ($source_event["date_begin"] == $source_event["date_end"]) ? 0 : 1;

   if ($eventret[0]["date_reg_begin"] == "0000-00-00") $source_event["date_reg_begin"] = (pem_cache_isset("current_year")) ? pem_cache_get("current_year") . "-" . pem_cache_get("current_month") . "-" . pem_cache_get("current_day") : pem_date("Y") . "-" . pem_date("m") . "-" . pem_date("d");
   if ($eventret[0]["date_reg_end"] == "0000-00-00") $source_event["date_reg_end"] = $source_event["date_begin"];

   unset($eventret);
}

// display add new form if edit not in progress
if ($showaddform)
{
   $current_event_type = pem_cache_get("current_event_type");
   switch (true)
   {
   case ($current_event_type == "scheduled"):
      $page_title = __("Add Calendar Event");
      break;
   case ($current_event_type == "unscheduled"):
      $page_title = __("Add Side Box Event");
      break;
   case ($current_event_type == "allday"):
      $page_title = __("Add All-Day Event");
      break;
   }

   pem_fieldset_begin($page_title);
   echo_form();
   pem_fieldset_end();
}
include ABSPATH . PEMINC . "/footer.php";

// =============================================================================
// =========================== LOCAL FUNCTIONS =================================
// =============================================================================



function echo_form($mode = "stage1", $error = "", $directions = "", $conflicts = "")
{
   global $PHP_SELF, $_POST, $source_event, $pem_url, $time_format, $date_format;
   global $default_setup_time, $default_buffer_time;
   global $state_select, $default_city, $default_state, $default_phone, $default_email;
   global $table_prefix, $dsn, $options;

   $copyevent = false;
   if (!empty($_POST)) extract($_POST);
   elseif (!empty($source_event))
   {
      $copyevent = true;
      extract($source_event);
      $mode = "stage3";

//echo "TESTING: source_event:<br /><pre>";
//print_r($source_event);
//echo "</pre><br />---------------------------------------------------------<br />";


   }



//echo "_POST:<br />";
//print_r($_POST);
//echo "<br />---------------------------------------------------------<br />";
//
//echo "optsupply1:<br />";
//print_r($optsupply1);
//echo "<br />---------------------------------------------------------<br />";


   if (isset($_POST["setup_time_before_hours"]))
   {
      $setup_time_before = pem_time_quantity_to_real(array("hours" => $setup_time_before_hours, "minutes" => $setup_time_before_minutes));
      $cleanup_time_after = pem_time_quantity_to_real(array("hours" => $cleanup_time_after_hours, "minutes" => $cleanup_time_after_minutes));
      $buffer_time_before = pem_time_quantity_to_real(array("hours" => $buffer_time_before_hours, "minutes" => $buffer_time_before_minutes));
      $buffer_time_after = pem_time_quantity_to_real(array("hours" => $buffer_time_after_hours, "minutes" => $buffer_time_after_minutes));
   }
   else
   {
      $setup_time_before = $default_setup_time;
      $cleanup_time_after = $default_setup_time;
      $buffer_time_before = $default_buffer_time;
      $buffer_time_after = $default_buffer_time;
   }


   if ($copyevent) switch (true)
   {
      case ($entry_type == 1):
         $source_type = "internal_scheduled";
         $current_event_type = "scheduled";
         break;
      case ($entry_type == 2):
         $source_type = "external_scheduled";
         $current_event_type = "scheduled";
         break;
      case ($entry_type == 3):
         $source_type = "internal_unscheduled";
         $current_event_type = "unscheduled";
         break;
      case ($entry_type == 4):
         $source_type = "external_unscheduled";
         $current_event_type = "unscheduled";
         break;
   }
   else
   {
      if (!isset($current_event_type)) $current_event_type = pem_cache_get("current_event_type");
      switch (true)
      {
      case ($current_event_type == "scheduled"):
         if (isset($source_type)) $source_type = ($source_type == "internal") ? "internal_scheduled" : "external_scheduled";
         break;
      case ($current_event_type == "unscheduled"):
         if (isset($source_type)) $source_type = ($source_type == "internal") ? "internal_unscheduled" : "external_unscheduled";
         break;
      case ($current_event_type == "allday"):
         if (isset($source_type)) $source_type = "internal_scheduled";
         break;
      }
   }

   if (empty($source_event))
   {
      $multi_day_event = (isset($multi_day_event) and $multi_day_event != 0) ? 1 : 0;
   }
   $multi_date_event = (isset($multi_date_event) and $multi_date_event != 0) ? 1 : 0;

   if (!isset($date_begin)) $date_begin = $date_begin_year . "-" . $date_begin_month . "-" . $date_begin_day;
   if (!isset($date_end)) $date_end = $date_end_year . "-" . $date_end_month . "-" . $date_end_day;
   if (!isset($time_begin)) $time_begin = pem_time($time_begin_hour, $time_begin_minute, $time_begin_meridiem);
   if (!isset($time_end)) $time_end = pem_time($time_end_hour, $time_end_minute, $time_end_meridiem);
   if ($date_begin == "--") $date_begin = (pem_cache_isset("current_year")) ? pem_cache_get("current_year") . "-" . pem_cache_get("current_month") . "-" . pem_cache_get("current_day") : pem_date("Y-m-d");
   if ($date_end == "--") $date_end =  (pem_cache_isset("current_year")) ? pem_cache_get("current_year") . "-" . pem_cache_get("current_month") . "-" . pem_cache_get("current_day") : pem_date("Y-m-d");
   if ($time_begin == "00:00:00") $time_begin = (pem_cache_isset("current_hour")) ? pem_cache_get("current_hour") . ":" . pem_cache_get("current_minute") : pem_date("H:i");
   if ($time_end == "00:00:00") $time_end = pem_date("H:i", strtotime(pem_date("Y-m-d") . " " . $time_begin) + 3600);

   $recur_times_note = __("(Includes starting date)");
   $recur_till_note = __("(Last event will be on or before this date)");


   if (empty($directions)) $directions = '<p>' . __("Select the event type using the drop-down list below.") . '</p>';
   $source_type_note = __("(Determines field and option visibility through the form)");

   $entry_name_note = __("(Global event name used with all dates)");
   $entry_description_note = __("(Global event description used with all dates)");
   $category_note = __("(Define a color-coded category for this event)");
   $date_name_note = __("(Local name added to the global name to form the full event title)");
   $date_description_note = __("(Local date description, can be edited at each date of a recurring event without affecting other dates' descriptions)");

   $presenter_note = __("(Enter the name of the presenter here)");
   $presenter_type_note = __("(This is the label that will be used to identify the presenter above)");
// TODO ICPL HACK
   // $open_to_public_note = __("(Can this event be attended by anyone in the general public?)");
   $open_to_public_note = __("(Can this event be attended by anyone in the general public? <b>NOTE:</b> Programs in Room A must be open to the public)");
   $visible_to_public_note = __("(Is an account required to see this event in the calendar?)");
   $reg_require_note = __("(Does this event require online registration to attend?)");
   $reg_current_note = __("(Set the starting reserved seats)");
   $reg_max_note = __("(If this is left blank, space occupancy is used for a maximum)");
   $allow_wait_note = __("(Registrations past the maximum will automatically fill spaces that open up)");
   $reg_begin_note = __("(When registrations will begin to be taken)");
   $reg_end_note = __("(Registrations past this date are not allowed)");
// TODO ICPL HACK
   //$seats_expected_note = __("(Expected attendance)");
   $seats_expected_note = __("(Expected attendance.  If unsure, predict a number)");


   $add_image_note = __("(this field is not fully implemented yet)");
   $add_file_note = __("(this field is not fully implemented yet)");

   $priv_notes_note = __("(For administrative use and never visible to the public)");





   echo '<div id="directions">' . $directions . '</div>' . "\n";

   echo '<div id="errorbox">' . "\n";
   echo '<a href="#" onclick="hidePopup(\'errorbox\');" class="hide">Close</a>';

   ob_start();
   pem_error_list($error);
   $error_list = ob_get_clean();
   if (!empty($error_list))
   {
      echo '<h2>Required Fields</h2><br />' . "\n";
      echo $error_list . "\n";
   }

   echo '<div id="ajaxbox">' . $conflicts . '</div>' . "\n";
   echo '</div>' . "\n";


   echo '<div id="mainform">' . "\n";

   // ===================== ESTABLISH USER ACCESS =============================
   $authorized_internal_calendar = pem_user_authorized(array("Internal Calendar" => "Add"));
   $authorized_external_calendar = pem_user_authorized(array("External Calendar" => "Add"));
   $authorized_internal_sidebox = pem_user_authorized(array("Internal Side Box" => "Add"));
   $authorized_external_sidebox = pem_user_authorized(array("External Side Box" => "Add"));
   // =========================================================================

   pem_form_begin(array("nameid" => "submitform", "action" => $PHP_SELF, "class" => "neweventform", "enctype" => "multipart/form-data"));
   echo '<div id="stage1">' . "\n";

   if ($current_event_type == "allday")
   {
      pem_hidden_input(array("nameid" => "datasubmit", "value" => "stage2"));
      pem_hidden_input(array("nameid" => "old_source_type", "value" => "internal"));
      pem_hidden_input(array("nameid" => "source_type", "value" => "internal"));
      $mode = "stage2";
      $source_type = "internal_scheduled";
   }
// TODO ICPL HACK to prevent external displays
   elseif ($current_event_type == "unscheduled")
   {
      pem_hidden_input(array("nameid" => "datasubmit", "value" => "stage2"));
      pem_hidden_input(array("nameid" => "old_source_type", "value" => "internal"));
      pem_hidden_input(array("nameid" => "source_type", "value" => "internal"));
      $mode = "stage2";
      $source_type = "internal_unscheduled";
   }
   else
   {
      if (!$authorized_internal_calendar)
      {
         pem_hidden_input(array("nameid" => "datasubmit", "value" => "stage2"));
         pem_hidden_input(array("nameid" => "old_source_type", "value" => "external"));
         pem_hidden_input(array("nameid" => "source_type", "value" => "external"));
         $mode = "stage2";
         $source_type = "external_scheduled";
      }
      elseif (!$authorized_external_calendar)
      {
         pem_hidden_input(array("nameid" => "datasubmit", "value" => "stage2"));
         pem_hidden_input(array("nameid" => "old_source_type", "value" => "internal"));
         pem_hidden_input(array("nameid" => "source_type", "value" => "internal"));
         $mode = "stage2";
         $source_type = "internal_scheduled";
      }
      else
      {
         pem_hidden_input(array("nameid" => "datasubmit", "value" => $mode));
         pem_hidden_input(array("nameid" => "old_source_type", "value" => $_POST["source_type"]));
         pem_field_label(array("default" => __("Source Type:"), "for" => "source_type"));
         $select_mode = ($mode == "stage1") ? "stage2" : $mode;
         $source_type_sub = explode("_", $source_type);
         pem_entry_source_type_select(array("nameid" => "source_type", "default" => $source_type_sub[0], "onchange" => "sourcetypeaction();"));
         //pem_entry_source_type_select(array("nameid" => "source_type", "default" => $source_type, "onchange" => "action_submit('submitform', '$select_mode');"));
         pem_field_note(array("default" => $source_type_note));
      }
   }
   echo '</div>' . "\n"; // END Stage1



// echo "source type: " . $source_type . "<br />";

if ($mode == "stage2" OR $mode == "stage3")
{

   echo '<script language="javascript" charset="utf-8">' . "\n";
   echo 'window.addEvent("domready", function(){' . "\n";
   echo 'var today = new Date();' . "\n";
   echo "var calendarbegin = new Calendar(\"calendarbegin\", \"calbegin_toggler\", {inputField:{date:'date_begin_day',\n";
   echo "                                           month:'date_begin_month',\n";
   echo "                                           year:'date_begin_year'},\n";
   echo "                                           inputType:'select',\n";
   echo "                                           allowWeekendSelection:true,\n";
   echo "                                           allowDaysOffSelection:true,\n";
   echo "                                           selectedDate:'" . $date_begin . "',\n";
   if (pem_user_anonymous()) echo "                                           offset:{x:-300, y:-110},\n";
   else if ($source_type == "internal_scheduled") echo "                                           offset:{x:-300, y:-165},\n";
   else if ($source_type == "internal_unscheduled") echo "                                           offset:{x:-300, y:-95},\n";
   else if ($source_type == "external_scheduled") echo "                                           offset:{x:-300, y:-140},\n";
//   else echo "                                           offset:{x:-300, y:-243},\n";
   echo "                                           idPrefix:'calbegin',\n";
   echo "                                           closeLinkHTML:'erase',\n";
   echo "                                           numMonths:6\n";
   echo "                                           });\n";
   echo "var calendarend = new Calendar(\"calendarend\", \"calend_toggler\", {inputField:{date:'date_end_day',\n";
   echo "                                           month:'date_end_month',\n";
   echo "                                           year:'date_end_year'},\n";
   echo "                                           inputType:'select',\n";
   echo "                                           allowWeekendSelection:true,\n";
   echo "                                           allowDaysOffSelection:true,\n";
   echo "                                           selectedDate:'" . $date_end . "',\n";
   if (pem_user_anonymous()) echo "                                           offset:{x:-300, y:-215},\n";
   else if ($source_type == "internal_scheduled") echo "                                           offset:{x:-250, y:-125},\n";
   else if ($source_type == "internal_unscheduled") echo "                                           offset:{x:-250, y:-120},\n";
   else if ($source_type == "external_scheduled") echo "                                           offset:{x:-250, y:-125},\n";
//  echo "                                           offset:{x:-250, y:-120},\n";
   echo "                                           idPrefix:'calend',\n";
   echo "                                           closeLinkHTML:'erase',\n";
   echo "                                           numMonths:6\n";
   echo "                                           });\n";
   echo '});' . "\n";
   echo "function toggle_cal_msg(buttonspan)\n";
   echo "{\n";
   echo "   if (buttonspan.innerHTML == 'Open Date Picker') buttonspan.innerHTML = 'Close Date Picker';\n";
   echo "   else buttonspan.innerHTML = 'Open Date Picker';\n";
   echo "}\n";
   echo '</script>' . "\n";

   //get list of active fields
   $fieldbehavior = pem_active_fields($source_type);
   //order the fields
   $fieldslist = pem_order_fields($fieldbehavior, $source_type);

/*
 * echo "<pre>";
print_r($fieldbehavior);
echo "</pre><br />";
echo "===========================================================";
echo "<pre>";
print_r($fieldslist);
echo "</pre><br />";
*/


   echo '<div id="stage2">' . "\n";

//echo "date_begin: $date_begin <br />";
//echo "date_end: $date_end <br />";

   $multi_day_note = __("(Event is longer than one day)");
   $recurring_note = __("(Occurs more than once on different days)");

   $cleanup_time_after_note = __("(Setup and cleanup are used to reserve additional non-public room time)");
   $buffer_time_after_note = __("(Buffer times insure events do not conflict with one another)");

   echo '<h3 style="margin-top:10px;">' . __("Event Occurs When") . '</h3>' . "\n";
   echo '<br /><div class="indent">' . "\n";
   pem_field_label(array("default" => __("Date Begins:"), "for" => "date_begin_hour", "class" => "timeoccurs"));
//   pem_date_selector("date_begin_", array("default" => $date_begin, "onchange" => pem_xajax_onchange("submitform", "check_conflicts")));

   if (!isset($date_begin_month))
   {
      $date_begin_year = pem_date("Y", $date_begin);
      $date_begin_month = pem_date("m", $date_begin);
      $date_begin_day = pem_date("d", $date_begin);
      $date_end_year = pem_date("Y", $date_end);
      $date_end_month = pem_date("m", $date_end);
      $date_end_day = pem_date("d", $date_end);
   }

   echo '<div style="position:relative;">' . "\n";
//   echo '<select name="date_begin_month" id="date_begin_month" onchange="xajax_check_conflicts(xajax.getFormValues(\'submitform\'), xajax.$(\'errorbox\').innerHTML); return false;"></select>' . "\n";
//   echo '<select name="date_begin_day" id="date_begin_day" onchange="xajax_check_conflicts(xajax.getFormValues(\'submitform\'), xajax.$(\'errorbox\').innerHTML); return false;"></select>' . "\n";
//   echo '<select name="date_begin_year" id="date_begin_year" onchange="xajax_check_conflicts(xajax.getFormValues(\'submitform\'), xajax.$(\'errorbox\').innerHTML); return false;"></select>' . "\n";
   echo '<select name="date_begin_month" id="date_begin_month" onchange="xajax_check_conflicts(xajax.getFormValues(\'submitform\'), xajax.$(\'errorbox\').innerHTML); return false;"><option selected="selected">' . $date_begin_month . '</option></select>' . "\n";
   echo '<select name="date_begin_day" id="date_begin_day" onchange="xajax_check_conflicts(xajax.getFormValues(\'submitform\'), xajax.$(\'errorbox\').innerHTML); return false;"><option selected="selected">' . $date_begin_day . '</option></select>' . "\n";
   echo '<select name="date_begin_year" id="date_begin_year" onchange="xajax_check_conflicts(xajax.getFormValues(\'submitform\'), xajax.$(\'errorbox\').innerHTML); return false;"><option selected="selected">' . $date_begin_year . '</option></select>' . "\n";
//   echo '<a href="#" id="calbegin_toggler" class="inlinebutton" onclick="toggle_cal_msg(\'calbegin\'); xajax_check_conflicts(xajax.getFormValues(\'submitform\'), xajax.$(\'errorbox\').innerHTML); return false;"><span>Open Date Picker</span></a>' . "\n";
   echo '<a href="#" id="calbegin_toggler" class="inlinebutton" onclick="xajax_check_conflicts(xajax.getFormValues(\'submitform\'), xajax.$(\'errorbox\').innerHTML); return false;"><span onclick="toggle_cal_msg(this);">Open Date Picker</span></a>' . "\n";
   echo '<div id="calendarbegin"></div>' . "\n";
   echo '</div>' . "\n";

//   echo '<div id="daydisplay" class="note"></div>' . "\n";
   pem_field_note(array("default" => $date_begin_note));

// ICPL HACK limiting the date ranges
   if (!pem_user_anonymous()) // Anonymous users cannot create multi-day events
   {
      echo '<div id="date_end_select" style="float:left;">' . "\n";
      pem_field_label(array("default" => __("Date Ends:"), "for" => "date_end_hour", "class" => "timeoccurs"));
//      pem_date_selector("date_end_", array("default" => $date_end, "onchange" => pem_xajax_onchange("submitform", "check_conflicts")));
//      echo '<select name="date_end_month" id="date_end_month" onchange="xajax_check_conflicts(xajax.getFormValues(\'submitform\'), xajax.$(\'errorbox\').innerHTML); return false;"></select>' . "\n";
//      echo '<select name="date_end_day" id="date_end_day" onchange="xajax_check_conflicts(xajax.getFormValues(\'submitform\'), xajax.$(\'errorbox\').innerHTML); return false;"></select>' . "\n";
//      echo '<select name="date_end_year" id="date_end_year" onchange="xajax_check_conflicts(xajax.getFormValues(\'submitform\'), xajax.$(\'errorbox\').innerHTML); return false;"></select>' . "\n";
      echo '<select name="date_end_month" id="date_end_month" onchange="xajax_check_conflicts(xajax.getFormValues(\'submitform\'), xajax.$(\'errorbox\').innerHTML); return false;"><option selected="selected">' . $date_end_month . '</option></select>' . "\n";
      echo '<select name="date_end_day" id="date_end_day" onchange="xajax_check_conflicts(xajax.getFormValues(\'submitform\'), xajax.$(\'errorbox\').innerHTML); return false;"><option selected="selected">' . $date_end_day . '</option></select>' . "\n";
      echo '<select name="date_end_year" id="date_end_year" onchange="xajax_check_conflicts(xajax.getFormValues(\'submitform\'), xajax.$(\'errorbox\').innerHTML); return false;"><option selected="selected">' . $date_end_year . '</option></select>' . "\n";
      echo '<a href="#" id="calend_toggler" class="inlinebutton" onclick="xajax_check_conflicts(xajax.getFormValues(\'submitform\'), xajax.$(\'errorbox\').innerHTML); return false;"><span onclick="toggle_cal_msg(this);">Open Date Picker</span></a>' . "\n";
      echo '<div id="calendarend"></div>' . "\n";
      pem_field_note(array("default" => $date_end_note));
      echo '</div>' . "\n";

      pem_checkbox(array("nameid" => "multi_day_event", "status" => $multi_day_event, "onclick" => "toggleLayer('date_end_select', this.checked); xajax_check_conflicts(xajax.getFormValues('submitform'), xajax.$('errorbox').innerHTML);", "style" => "float:left;"));
      pem_field_label(array("default" => __("Multi-Day Event"), "for" => "multi_day_event"));
      pem_field_note(array("default" => $multi_day_note));
   }
   else
   {
      echo '<div id="date_end_select" style="float:left; visibility:hidden;">' . "\n";
//      pem_date_selector("date_end_", array("default" => $date_end, "style" => "visibility:hidden; display:none;"));
//      echo '<select name="date_end_month" id="date_end_month" onchange="xajax_check_conflicts(xajax.getFormValues(\'submitform\'), xajax.$(\'errorbox\').innerHTML); return false;"></select>' . "\n";
//      echo '<select name="date_end_day" id="date_end_day" onchange="xajax_check_conflicts(xajax.getFormValues(\'submitform\'), xajax.$(\'errorbox\').innerHTML); return false;"></select>' . "\n";
//      echo '<select name="date_end_year" id="date_end_year" onchange="xajax_check_conflicts(xajax.getFormValues(\'submitform\'), xajax.$(\'errorbox\').innerHTML); return false;"></select>' . "\n";
//      echo '<select name="date_end_month" id="date_end_month" onchange="xajax_check_conflicts(xajax.getFormValues(\'submitform\'), xajax.$(\'errorbox\').innerHTML); return false;"><option selected="selected">' . $date_end_month . '</option></select>' . "\n";
//      echo '<select name="date_end_day" id="date_end_day" onchange="xajax_check_conflicts(xajax.getFormValues(\'submitform\'), xajax.$(\'errorbox\').innerHTML); return false;"><option selected="selected">' . $date_end_day . '</option></select>' . "\n";
//      echo '<select name="date_end_year" id="date_end_year" onchange="xajax_check_conflicts(xajax.getFormValues(\'submitform\'), xajax.$(\'errorbox\').innerHTML); return false;"><option selected="selected">' . $date_end_year . '</option></select>' . "\n";
      pem_hidden_input(array("nameid" => "date_end_month", "value" => $date_end_month));
      pem_hidden_input(array("nameid" => "date_end_day", "value" => $date_end_day));
      pem_hidden_input(array("nameid" => "date_end_year", "value" => $date_end_year));
      pem_hidden_input(array("nameid" => "calend_toggler", "value" => 0));
      echo '<div id="calendarend"></div>' . "\n";
      pem_hidden_input(array("nameid" => "multi_day_event", "value" => 0));
      echo '</div>' . "\n";
   }
   echo '<br />' . "\n";

// ICPL HACK limiting the date ranges
   if (!pem_user_anonymous()) // Anonymous users cannot create multi-date events
   {
      echo '<div id="recurring_type_select" style="float:left;">' . "\n";
      pem_field_label(array("default" => __("Repeats:"), "for" => "recurring_event", "class" => "timeoccurs"));
      pem_recurring_type_select(array("nameid" => "recurring_event", "default" => $recurring_event, "onchange" => pem_xajax_onchange("submitform", "check_conflicts")));
      pem_field_note(array("default" => $recurring_event_note));
      echo '</div>' . "\n";
      pem_checkbox(array("nameid" => "multi_date_event", "status" => $multi_date_event, "onclick" => "toggleLayer('recurring_type_select', this.checked); toggleLayer('recurring_duration_select', this.checked); xajax_check_conflicts(xajax.getFormValues('submitform'), xajax.$('errorbox').innerHTML);", "style" => "float:left;"));
      pem_field_label(array("default" => __("Recurring Event"), "for" => "multi_date_event"));
      pem_field_note(array("default" => $recurring_note));
      echo '<br />' . "\n";
      echo '<div id="recurring_duration_select" class="indent">' . "\n";
      pem_field_label(array("default" => __("Duration:"), "for" => "recurring_duration", "class" => "timeoccurs"));
      pem_recurring_duration_select(array("nameid" => "recurring_duration", "default" => $recurring_duration, "onchange" => "toggleRecurranceDuration(); clearSelectPrompt(this); xajax_check_conflicts(xajax.getFormValues('submitform'), xajax.$('errorbox').innerHTML);"));
      pem_field_note(array("default" => $recurring_duration_note));
      echo '<div id="recur_times_select">' . "\n";
      pem_field_label(array("default" => __("End after:"), "for" => "recur_times", "class" => "timeoccurs"));
      pem_recur_times_select(array("nameid" => "recur_times", "default" => $recur_times, "onchange" => pem_xajax_onchange("submitform", "check_conflicts")));
      pem_field_note(array("default" => $recur_times_note));
      echo '</div>' . "\n";
      echo '<div id="recur_till_select">' . "\n";
      if (!isset($recur_till)) $recur_till = $date_begin;
      pem_field_label(array("default" => __("End by:"), "for" => "recur_till", "class" => "timeoccurs"));
      pem_date_selector("recur_till_", array("default" => $recur_till, "onchange" => pem_xajax_onchange("submitform", "check_conflicts")));
      pem_field_note(array("default" => $recur_till_note));
      echo '</div>' . "\n";
      echo '</div>' . "\n";
   }
   else
   {
      echo '<div id="recurring_type_select" style="float:left; visibility:hidden;">' . "\n";
      pem_recurring_type_select(array("nameid" => "recurring_event", "default" => $recurring_event, "style" => "visibility:hidden; display:none;"));
      pem_recurring_duration_select(array("nameid" => "recurring_duration", "default" => $recurring_duration, "style" => "visibility:hidden; display:none;"));
      pem_recur_times_select(array("nameid" => "recur_times", "default" => $recur_times, "style" => "visibility:hidden; display:none;"));
      pem_date_selector("recur_till_", array("default" => $recur_till, "style" => "visibility:hidden; display:none;"));
      pem_hidden_input(array("nameid" => "multi_date_event", "value" => 0));
      echo '</div>' . "\n";
   }
   if ($current_event_type != "allday")
   {
      pem_hidden_input(array("nameid" => "conflicting", "value" => 1));
   }
   else
   {
      $conflicting = (!isset($conflicting) AND !empty($_POST)) ? 0 : 1;
      pem_checkbox(array("nameid" => "conflicting", "status" => $conflicting, "onclick" => "xajax_check_conflicts(xajax.getFormValues('submitform'), xajax.$('errorbox').innerHTML);", "style" => "float:left;"));
      pem_field_label(array("default" => __("Conflicts with Other Calendar Events"), "for" => "conflicting"));
      echo '<br />' . "\n";
   }

   $time_begin_array = array("default" => $time_begin, "onchange" => pem_xajax_onchange("submitform", "check_conflicts"));
   $time_end_array = array("default" => $time_end, "onchange" => pem_xajax_onchange("submitform", "check_conflicts"));
   if ($current_event_type == "scheduled")
   {
      pem_field_label(array("default" => __("Time Begins:"), "for" => "time_begin_hour", "class" => "timeoccurs"));
      pem_time_selector("time_begin_", $time_begin_array);
      pem_field_note(array("default" => $time_begin_note));
      pem_field_label(array("default" => __("Time Ends:"), "for" => "time_end_hour", "class" => "timeoccurs"));
      pem_time_selector("time_end_", $time_end_array);
      pem_field_note(array("default" => $time_end_note));
   }
   elseif ($current_event_type == "unscheduled")
   {
      $time_begin_array["hidden"] = true;
      $time_end_array["hidden"] = true;
      $time_begin_array["default"] = "00:00";
      $time_end_array["default"] = "23:59";
      pem_time_selector("time_begin_", $time_begin_array);
      pem_time_selector("time_end_", $time_end_array);
   }
   else
   {
      $time_begin_array["hidden"] = true;
      $time_end_array["hidden"] = true;
      $time_begin_array["default"] = "08:00";
      $time_end_array["default"] = "22:00";
      pem_time_selector("time_begin_", $time_begin_array);
      pem_time_selector("time_end_", $time_end_array);
   }
   echo '</div>' . "\n";

   if ($current_event_type == "scheduled")
   {
      echo '<h3>' . __("Additional Time Needs") . '</h3>' . "\n";
      echo '<br /><div class="indent">' . "\n";
      pem_field_label(array("default" => __("Setup Time Before:"), "for" => "setup_time_before_hours", "class" => "timeneeds"));
      pem_time_quantity_selector("setup_time_before_", array("default" => $setup_time_before, "onchange" => "xajax_check_conflicts(xajax.getFormValues('submitform'), xajax.$('errorbox').innerHTML)"));
      pem_field_note(array("default" => $setup_time_before_note));
      pem_field_label(array("default" => __("Cleanup Time After:"), "for" => "cleanup_time_after_hours", "class" => "timeneeds"));
      pem_time_quantity_selector("cleanup_time_after_", array("default" => $cleanup_time_after, "onchange" => "xajax_check_conflicts(xajax.getFormValues('submitform'), xajax.$('errorbox').innerHTML)"));
      pem_field_note(array("default" => $cleanup_time_after_note));
      if (!pem_user_anonymous()) // Anonymous users cannot set internal-use buffers
      {
         pem_field_label(array("default" => __("Buffer Time Before:"), "for" => "buffer_time_before_hours", "class" => "timeneeds"));
         pem_time_quantity_selector("buffer_time_before_", array("default" => $buffer_time_before, "onchange" => "xajax_check_conflicts(xajax.getFormValues('submitform'), xajax.$('errorbox').innerHTML)"));
         pem_field_note(array("default" => $buffer_time_before_note));
         pem_field_label(array("default" => __("Buffer Time After:"), "for" => "buffer_time_after_hours", "class" => "timeneeds"));
         pem_time_quantity_selector("buffer_time_after_", array("default" => $buffer_time_after, "onchange" => "xajax_check_conflicts(xajax.getFormValues('submitform'), xajax.$('errorbox').innerHTML)"));
         pem_field_note(array("default" => $buffer_time_after_note));
      }
      else
      {
         $buffer_time_before_array = pem_real_to_time_quantity($buffer_time_before);
         $buffer_time_after_array = pem_real_to_time_quantity($buffer_time_after);
         pem_hidden_input(array("nameid" => "buffer_time_before_hours", "value" => $buffer_time_before_array["hours"]));
         pem_hidden_input(array("nameid" => "buffer_time_before_minutes", "value" => $buffer_time_before_array["minutes"]));
         pem_hidden_input(array("nameid" => "buffer_time_after_hours", "value" => $buffer_time_after_array["hours"]));
         pem_hidden_input(array("nameid" => "buffer_time_after_minutes", "value" => $buffer_time_after_array["minutes"]));
// ICPL HACK buffer msg
         echo '<p>All events automatically have an additional 30-minute buffer applied before and after their scheduled times.  If you need assistance scheduling your event please contact the Fiction Desk at 319.356.5200 option 4 or email <a href="mailto:calendar@icpl.org">calendar@icpl.org</a>.</p>' . "\n";
      }
      echo '</div>' . "\n";
   }

   if (isset($fieldbehavior["areas"]))
   {
      $where = array("status" => 1);
      $area_list = pem_get_rows("areas", $where, "AND", "area_name");
      for ($i = 0; $i < count($area_list); $i++)
      {
         $where = array("area" => $area_list[$i]["id"]);
         $where["status"] = 1;
         $where[$source_type] = 1;
         $space_list = pem_get_rows("spaces", $where, "AND", "space_name");
         $area_spaces = "";
         ob_start();
         for ($j = 0; $j < count($space_list); $j++)
         {
            pem_checkbox(array("name" => "spaces[" . $space_list[$j]["id"] . "]", "id" => "space" . $space_list[$j]["id"], "status" => $spaces[$space_list[$j]["id"]], "style" => "float:left;", "onclick" => "xajax_check_conflicts(xajax.getFormValues('submitform'), xajax.$('errorbox').innerHTML);"));
            pem_field_label(array("default" => $space_list[$j]["space_name"], "for" => "spaces[" . $space_list[$j]["id"] . "]"));
            if (!empty($space_list[$j]["space_popup"]))
            {
               pem_field_note(array(
                  "default" => __("(info)"),
                  "link" => $pem_url . "pem-content/" . $space_list[$j]["space_popup"] . "?KeepThis=true&TB_iframe=true&height=400&width=600",
                  "title" => $space_list[$j]["space_name"],
                  "linkclass" => "thickbox"
                 ));
               // pem_field_note(array("default" => __("(info)"), "link" => $pem_url . "pem-content/" . $space_list[$j]["space_popup"]));
            }
            pem_hidden_input(array("name" => "capacity[" . $space_list[$j]["id"] . "]", "value" => (empty($space_list[$j]["capacity"])) ? 0 : $space_list[$j]["capacity"]));
            echo "<br />\n";
         }
         $area_spaces = ob_get_clean();
         if (!empty($area_spaces))
         {
            echo '<div style="float:left; margin-right:10px;">' . "\n";
            echo '<h3>' . $area_list[$i]["area_name"] . '</h3>' . "\n";
            echo $area_spaces . "\n";
            echo '</div>' . "\n";
         }
      }
   }
   else
   {
      $where = array("status" => 1, $source_type => 1);
      $space_list = pem_get_rows("spaces", $where, "AND", "space_name");
      echo '<h3>' . __("Available Locations") . '</h3>' . "\n";
      echo '<br /><div class="indent">' . "\n";
      if (count($space_list) > 10) echo '<div style="float:left; margin-right:10px;">' . "\n";
      for ($j = 0; $j < count($space_list); $j++)
      {
         if (count($space_list) > 10 AND $j == intval(count($space_list)/2)+1) echo '</div><div style="float:left; margin-right:10px;">' . "\n";
         pem_checkbox(array("name" => "spaces[" . $space_list[$j]["id"] . "]", "id" => "space" . $space_list[$j]["id"], "status" => $spaces[$space_list[$j]["id"]], "style" => "float:left;", "onclick" => "xajax_check_conflicts(xajax.getFormValues('submitform'), xajax.$('errorbox').innerHTML);"));
         pem_field_label(array("default" => $space_list[$j]["space_name"], "for" => "spaces[" . $space_list[$j]["id"] . "]"));
         pem_hidden_input(array("name" => "capacity[" . $space_list[$j]["id"] . "]", "value" => (empty($space_list[$j]["capacity"])) ? 0 : $space_list[$j]["capacity"]));
         if (!empty($space_list[$j]["space_popup"]))
         {
            pem_field_note(array(
               "default" => __("(info)"),
               "link" => $pem_url . "pem-content/" . $space_list[$j]["space_popup"] . "?KeepThis=true&TB_iframe=true&height=400&width=600",
               "title" => $space_list[$j]["space_name"],
               "linkclass" => "thickbox"
              ));
         }
         else echo "<br />\n";
      }
      if (count($space_list) > 10) echo '</div>' . "\n";
      echo '</div>' . "\n";
   }

   echo '<br />' . "\n";

   echo '<div id="supply_box">' . "\n";
/*
echo "space list: <pre>";
print_r($space_list[4]);
echo "</pre><br />";
echo "spaces: <pre>";
print_r($spaces);
echo "</pre><br />";
*/
   if (!empty($spaces))
   {
      // Collect and write out options for supplies to the form.
      $form_spaces = array_keys($spaces);
/*
echo "form_spaces: ";
print_r($form_spaces);
echo "<br />";
*/

      $got_supplies = false;
      ob_start();
      echo '<br /><div class="indent">' . "\n";
      $sql_profiles = "SELECT description, profile FROM " . $table_prefix . "supply_profiles WHERE id = :profile_id AND status != 2";
      $sql_supplies = "SELECT id, supply_name FROM " . $table_prefix . "supplies WHERE id = :supply_id AND " . $source_type . " = 1 AND status != 2";
      for ($i = 0; $i < count($space_list); $i++)
      {
         $pemdb =& mdb2_connect($dsn, $options, "connect");

         if (in_array($space_list[$i]["id"], $form_spaces))
         {
            if (!empty($space_list[$i]["supply_profile"]))
            {
/*
echo "hunting for profile: ";
print_r($space_list[$i]["supply_profile"]);
echo "<br />";
echo "sql: $sql_profiles<br />";
*/
               $sql_values = array("profile_id" => $space_list[$i]["supply_profile"]);
               $sql_prep = $pemdb->prepare($sql_profiles);
               if (PEAR::isError($sql_prep)) $error .= $sql_prep->getMessage() . ', ' . $sql_prep->getDebugInfo();
               $result = $sql_prep->execute($sql_values);
               if (PEAR::isError($result)) $error .= $result->getMessage() . ', ' . $result->getDebugInfo();
               $standard_row = $result->fetchRow(MDB2_FETCHMODE_ASSOC);
               $standard_profile = unserialize($standard_row["profile"]);
            }
            else
            {
               $standard_profile = "";
            }
            if (!empty($space_list[$i]["optional_supplies"]))
            {
               $sql_values = array("profile_id" => $space_list[$i]["optional_supplies"]);
               $sql_prep = $pemdb->prepare($sql_profiles);
               if (PEAR::isError($sql_prep)) $error .= $sql_prep->getMessage() . ', ' . $sql_prep->getDebugInfo();
               $result = $sql_prep->execute($sql_values);
               if (PEAR::isError($result)) $error .= $result->getMessage() . ', ' . $result->getDebugInfo();
               $optional_row = $result->fetchRow(MDB2_FETCHMODE_ASSOC);
               $optional_profile = unserialize($optional_row["profile"]);
            }
            else
            {
               $optional_profile = "";
            }
// echo "supply profile: " . $standard_row["profile"] . ", optional profile: " . $optional_row["profile"] . "<br />";
            if (!empty($standard_profile))
            {
               $got_supplies = true;
               echo '<b>' . $space_list[$i]["space_name"] . ' Standard Supplies:</b><br />' . "\n";
               echo '<p style="margin-bottom:0;">' . $standard_row["description"] . '</p>' . "\n";
               echo '<ul class="bullets">' . "\n";
               $profile_keys = array_keys($standard_profile);
               for ($j = 0; $j < count($profile_keys); $j++)
               {
                  $sql_values = array("supply_id" => $profile_keys[$j]);
                  $sql_prep = $pemdb->prepare($sql_supplies);
                  if (PEAR::isError($sql_prep)) $error .= $sql_prep->getMessage() . ', ' . $sql_prep->getDebugInfo();
                  $result = $sql_prep->execute($sql_values);
                  if (PEAR::isError($result)) $error .= $result->getMessage() . ', ' . $result->getDebugInfo();
                  $supply_row = $result->fetchRow(MDB2_FETCHMODE_ASSOC);
                  echo '<li class="supplylist">' . $supply_row["supply_name"] . ' (' . $standard_profile[$profile_keys[$j]] . ')</li>' . "\n";
               }
               echo '</ul>' . "\n";
            }
            if (!empty($optional_profile))
            {
               $got_supplies = true;
               echo "<b>" . $space_list[$i]["space_name"] . " Optional Supplies:</b><br />" . "\n";
               echo '<p>' . $optional_row["description"] . '</p>' . "\n";
               echo '<div class="indent">' . "\n";
               $profile_keys = array_keys($optional_profile);
               for ($j = 0; $j < count($profile_keys); $j++)
               {
                  $sql_values = array("supply_id" => $profile_keys[$j]);
                  $sql_prep = $pemdb->prepare($sql_supplies);
                  if (PEAR::isError($sql_prep)) $error .= $sql_prep->getMessage() . ', ' . $sql_prep->getDebugInfo();
                  $result = $sql_prep->execute($sql_values);
                  if (PEAR::isError($result)) $error .= $result->getMessage() . ', ' . $result->getDebugInfo();
                  $supply_row = $result->fetchRow(MDB2_FETCHMODE_ASSOC);
                  $opt_field_name = "optsupply" . $space_list[$i]["id"] . "[" . $supply_row["id"] . "]";
                  $opt_field_tmp =  ${"optsupply" . $space_list[$i]["id"]};
/*
echo "opt field: $opt_field_tmp <br />";
$opt_field_tmp2 = ${$opt_field_tmp};
echo "opt value: " . $opt_field_tmp2[$supply_row["id"]] . " <br />";
*/
                  $opt_default = (isset($opt_field_tmp[$supply_row["id"]])) ? $opt_field_tmp[$supply_row["id"]] : "";

//echo "opt_field_name: $opt_field_name, count: " . $optional_profile[$profile_keys[$j]] . " <br />";
//echo "_POST[$opt_field_name]: " . $optsupply[$supply_row["id"]] . " <br />";
                  pem_quantity_select(array("name" => $opt_field_name, "default" => $opt_default, "style" => "width:50px;"),  $optional_profile[$profile_keys[$j]]);
                  echo ' ' . $supply_row["supply_name"] . '<br />' . "\n";
               }
               echo '</div>' . "\n";
            }
         }
         mdb2_disconnect($pemdb);
      }
      $supply_text = ob_get_clean();
      if ($got_supplies) echo '<br />' . "\n" . '<h3 class="ieheight">' . __("Location Supplies") . '</h3>' . "\n" . $supply_text;
   } // END if (!empty($spaces))

   echo '</div>' . "\n";

   echo '</div>' . "\n"; //  END Stage2
   echo '<div id="stage3">' . "\n";

   $cleanup_time_after_note = __("(Setup and cleanup are used to reserve additional non-public room time)");
   $buffer_time_after_note = __("(Buffer times ensure events do not conflict with one another)");

   // Fields after this point are controlled by the behavior and order settings in Backend Administration

   echo '<br />' . "\n";
   echo '<h3 class="ieheight">' . __("Event Information") . '</h3>' . "\n";
   echo '<br /><div class="indent">' . "\n";

   $javascript_fields[] = "";

   // Loop the short list to display form fields in order
   for ($i = 0; $i < count($fieldslist); $i++)
   {
      switch(true)
      {

      // HANDLE ANY ENTRY FIELDS
      case ($fieldslist[$i]["name"] == "entry_name"):
         pem_field_label(array("default" => $fieldslist[$i]["label"], "for" => "entry_name", "class" => "desc"));
         pem_text_input(array("nameid" => "entry_name", "value" => $entry_name, "size" => 30, "maxlength" => 80));
         pem_field_note(array("default" => $entry_name_note, "required" => $fieldslist[$i]["required"]));
         if ($fieldslist[$i]["required"]) pem_hidden_input(array("name" => "required['" . $fieldslist[$i]["name"] . "']", "value" => $fieldslist[$i]["label"]));
         break;
      case ($fieldslist[$i]["name"] == "entry_description"):
         pem_field_label(array("default" => $fieldslist[$i]["label"], "for" => "entry_description", "class" => "desc"));
         pem_textarea_input(array("nameid" => "entry_description", "default" => $entry_description, "style" => "width:400px; height:100px;"));
         pem_field_note(array("default" => $entry_description_note, "required" => $fieldslist[$i]["required"]));
         if ($fieldslist[$i]["required"]) pem_hidden_input(array("name" => "required['" . $fieldslist[$i]["name"] . "']", "value" => $fieldslist[$i]["label"]));
         break;
      case ($fieldslist[$i]["name"] == "entry_category"):
         pem_field_label(array("default" => $fieldslist[$i]["label"], "for" => "entry_category", "class" => "desc"));
         pem_category_select(array("nameid" => "entry_category", "default" => $entry_category));
         pem_field_note(array("default" => $category_note, "required" => $fieldslist[$i]["required"]));
         if ($fieldslist[$i]["required"]) pem_hidden_input(array("name" => "required['" . $fieldslist[$i]["name"] . "']", "value" => $fieldslist[$i]["label"]));
         break;
      case ($fieldslist[$i]["name"] == "entry_presenter"):
         pem_field_label(array("default" => $fieldslist[$i]["label"], "for" => "entry_presenter", "class" => "desc"));
         pem_text_input(array("nameid" => "entry_presenter", "value" => $entry_presenter, "size" => 30, "maxlength" => 80));
         pem_field_note(array("default" => $presenter_note, "required" => $fieldslist[$i]["required"]));
         if ($fieldslist[$i]["required"]) pem_hidden_input(array("name" => "required['" . $fieldslist[$i]["name"] . "']", "value" => $fieldslist[$i]["label"]));
         break;
      case ($fieldslist[$i]["name"] == "entry_presenter_type"):
         pem_field_label(array("default" => $fieldslist[$i]["label"], "for" => "entry_presenter_type", "class" => "desc"));
         pem_prestenter_type_select(array("nameid" => "entry_presenter_type", "default" => $entry_presenter_type, "showprompt" => true));
         pem_field_note(array("default" => $presenter_type_note, "required" => $fieldslist[$i]["required"]));
         if ($fieldslist[$i]["required"]) pem_hidden_input(array("name" => "required['" . $fieldslist[$i]["name"] . "']", "value" => $fieldslist[$i]["label"]));
         break;
      case ($fieldslist[$i]["name"] == "entry_reg_require"):
         $javascript_fields[] = "entry_reg_require";
         pem_field_label(array("default" => $fieldslist[$i]["label"], "for" => "entry_reg_require", "class" => "desc"));
         pem_boolean_select(array("nameid" => "entry_reg_require", "default" => $entry_reg_require, "onchange" => "toggleLayer('entry_registration', this.value);"));
         pem_field_note(array("default" => $reg_require_note, "required" => $fieldslist[$i]["required"]));
         if ($fieldslist[$i]["required"]) pem_hidden_input(array("name" => "required['" . $fieldslist[$i]["name"] . "']", "value" => $fieldslist[$i]["label"]));
         echo '<div id="entry_registration" class="indent">' . "\n";
         if (array_key_exists("entry_reg_current", $fieldbehavior))
         {
            pem_field_label(array("default" => $fieldbehavior["entry_reg_current"]["label"], "for" => "entry_reg_current", "class" => "desc"));
            pem_text_input(array("nameid" => "entry_reg_current", "value" => $entry_reg_current, "size" => 6, "maxlength" => 6));
            pem_field_note(array("default" => $reg_current_note, "required" => $fieldslist[$i]["required"]));
            if ($fieldslist[$i]["required"]) pem_hidden_input(array("name" => "required['entry_reg_current']", "value" => $fieldslist[$i]["label"]));
         }
         if (array_key_exists("entry_reg_max", $fieldbehavior))
         {
            pem_field_label(array("default" => $fieldbehavior["entry_reg_max"]["label"], "for" => "entry_reg_max", "class" => "desc"));
            pem_text_input(array("nameid" => "entry_reg_max", "value" => $entry_reg_max, "size" => 6, "maxlength" => 6));
            pem_field_note(array("default" => $reg_max_note, "required" => $fieldslist[$i]["required"]));
            if ($fieldslist[$i]["required"]) pem_hidden_input(array("name" => "required['entry_reg_max']", "value" => $fieldslist[$i]["label"]));
         }
         if (array_key_exists("entry_allow_wait", $fieldbehavior))
         {
            pem_field_label(array("default" => $fieldbehavior["entry_allow_wait"]["label"], "for" => "entry_allow_wait", "class" => "desc"));
            pem_boolean_select(array("nameid" => "entry_allow_wait", "default" => $entry_allow_wait));
            pem_field_note(array("default" => $allow_wait_note, "required" => $fieldslist[$i]["required"]));
            if ($fieldslist[$i]["required"]) pem_hidden_input(array("name" => "required['entry_allow_wait']", "value" => $fieldslist[$i]["label"]));
         }
         if (array_key_exists("entry_reg_begin", $fieldbehavior))
         {
            $entry_reg_begin = $entry_reg_begin_year . "-" . $entry_reg_begin_month . "-" . $entry_reg_begin_day;
            if ($entry_reg_begin == "--") $entry_reg_begin = (pem_cache_isset("current_year")) ? pem_cache_get("current_year") . "-" . pem_cache_get("current_month") . "-" . pem_cache_get("current_day") : pem_date("Y") . "-" . pem_date("m") . "-" . pem_date("d");
            pem_field_label(array("default" => $fieldbehavior["entry_reg_begin"]["label"], "for" => "entry_reg_begin_month", "class" => "desc"));
            pem_date_selector("entry_reg_begin_", array("default" => $entry_reg_begin));
            pem_field_note(array("default" => $reg_begin_note, "required" => $fieldslist[$i]["required"]));
            if ($fieldslist[$i]["required"]) pem_hidden_input(array("name" => "required['entry_reg_begin']", "value" => $fieldslist[$i]["label"]));
         }
         if (array_key_exists("entry_reg_end", $fieldbehavior))
         {
            $entry_reg_end = $entry_reg_end_year . "-" . $entry_reg_end_month . "-" . $entry_reg_end_day;
            if ($entry_reg_end == "--")
            {
               $tmpdate = explode("-", $date_begin);
               if ($tmpdate[2] > 1) $tmpdate[2]--;
               else
               {
                  if ($tmpdate[1] > 1) $tmpdate[2] = pem_date("t", mktime(0, 0, 0, $tmpdate[1]-1, 1, $tmpdate[0]));
                  else
                  {
                     $tmpdate[0]--;
                     $tmpdate[1] = 12;
                     $tmpdate[2] = 31;
                  }
               }
               $entry_reg_end = $tmpdate[0] . "-" . $tmpdate[1] . "-" . $tmpdate[2];
             }
            pem_field_label(array("default" => $fieldbehavior["entry_reg_end"]["label"], "for" => "entry_reg_end_month", "class" => "desc"));
            pem_date_selector("entry_reg_end_", array("default" => $entry_reg_end));
            pem_field_note(array("default" => $reg_end_note, "required" => $fieldslist[$i]["required"]));
            if ($fieldslist[$i]["required"]) pem_hidden_input(array("name" => "required['entry_reg_end']", "value" => $fieldslist[$i]["label"]));
         }
         echo '</div>' . "\n";
         break;
      case ($fieldslist[$i]["name"] == "entry_open_to_public"):
         pem_field_label(array("default" => $fieldslist[$i]["label"], "for" => "entry_open_to_public", "class" => "desc"));
         pem_boolean_select(array("nameid" => "entry_open_to_public", "default" => $entry_open_to_public, "showprompt" => true));
         pem_field_note(array("default" => $open_to_public_note, "required" => $fieldslist[$i]["required"]));
         if ($fieldslist[$i]["required"]) pem_hidden_input(array("name" => "required['" . $fieldslist[$i]["name"] . "']", "value" => $fieldslist[$i]["label"]));
         break;
      case ($fieldslist[$i]["name"] == "entry_visible_to_public"):
         pem_field_label(array("default" => $fieldslist[$i]["label"], "for" => "entry_visible_to_public", "class" => "desc"));
         pem_boolean_select(array("nameid" => "entry_visible_to_public", "default" => $entry_visible_to_public, "showprompt" => true));
         pem_field_note(array("default" => $visible_to_public_note, "required" => $fieldslist[$i]["required"]));
         if ($fieldslist[$i]["required"]) pem_hidden_input(array("name" => "required['" . $fieldslist[$i]["name"] . "']", "value" => $fieldslist[$i]["label"]));
         break;
      case ($fieldslist[$i]["name"] == "entry_seats_expected"):
         pem_field_label(array("default" => $fieldslist[$i]["label"], "for" => "entry_seats_expected", "class" => "desc"));
         pem_text_input(array("nameid" => "entry_seats_expected", "value" => $entry_seats_expected, "size" => 6, "maxlength" => 20));
         pem_field_note(array("default" => $seats_expected_note, "required" => $fieldslist[$i]["required"]));
         if ($fieldslist[$i]["required"]) pem_hidden_input(array("name" => "required['" . $fieldslist[$i]["name"] . "']", "value" => $fieldslist[$i]["label"]));
         pem_hidden_input(array("name" => "validatenum['" . $fieldslist[$i]["name"] . "']", "value" => $fieldslist[$i]["label"]));
         break;

/*
      case ($fieldslist[$i]["name"] == "entry_upload_image"):
         $javascript_fields[] = "entry_add_image";
         pem_field_label(array("default" => $fieldslist[$i]["label"], "for" => "entry_add_image", "class" => "desc"));
         pem_boolean_select(array("nameid" => "entry_add_image", "default" => $entry_add_image, "onchange" => "toggleLayer('entry_image', this.value);"));
         pem_field_note(array("default" => $add_image_note));

         echo '<div id="entry_image" class="indent">' . "\n";
         pem_field_label(array("default" => $fieldslist[$i]["label"], "for" => "entry_upload_image", "class" => "desc"));
         pem_file_upload(array("nameid" => "entry_upload_image", "value" => $entry_upload_image, "size" => 30));
         pem_field_note(array("default" => $upload_image_note, "required" => $fieldslist[$i]["required"]));

         pem_field_label(array("default" => __("Image Border:"), "for" => "entry_upload_image_border", "class" => "desc"));
         pem_checkbox(array("nameid" => "entry_upload_image_border", "status" => $entry_upload_image_border, "style" => "float:left; margin-right:2px;"));
         pem_field_note(array("default" => $upload_image_border_note));

         pem_field_label(array("default" => __("Use Magnifier:"), "for" => "entry_upload_image_zoom", "class" => "desc"));
         pem_checkbox(array("nameid" => "entry_upload_image_zoom", "status" => $entry_upload_image_zoom, "style" => "float:left; margin-right:2px;"));
         pem_field_note(array("default" => $upload_image_zoom_note));

         pem_field_label(array("default" => __("Image Side:"), "for" => "entry_upload_image_side", "class" => "desc"));
         pem_side_select(array("nameid" => "entry_upload_image_side", "default" => $entry_upload_image_side));
         pem_field_note(array("default" => $upload_image_side_note));
         echo '</div>' . "\n";

         if ($fieldslist[$i]["required"]) pem_hidden_input(array("name" => "required['" . $fieldslist[$i]["name"] . "']", "value" => $fieldslist[$i]["label"]));
         break;
*/

/*
<td class="fieldlabel">Image:</td><td><input type="hidden" name="imagefilemaxsize" value="2000000000000" />
<input type="file" name="imagefile" id="imagefile" />
</td></tr><tr>
<td class="fieldlabel">Image Border:</td><td><div style="float:left;"><input type="checkbox" name="imageborder" id="imageborder" value="1" /></div></td></tr><tr>
<td class="fieldlabel">Image Side:</td><td><select name="imageloc" id="imageloc"><option value="0">left</option><option value="1">right</option></select></td>
</tr><tr>
*/

/*
      case ($fieldslist[$i]["name"] == "entry_upload_file"):
         $javascript_fields[] = "entry_add_file";
         pem_field_label(array("default" => $fieldslist[$i]["label"], "for" => "entry_add_file", "class" => "desc"));
         pem_boolean_select(array("nameid" => "entry_add_file", "default" => $entry_add_file, "onchange" => "toggleLayer('entry_file', this.value);"));
         pem_field_note(array("default" => $add_file_note));

         echo '<div id="entry_file" class="indent">' . "\n";
         pem_field_label(array("default" => $fieldslist[$i]["label"], "for" => "entry_upload_file", "class" => "desc"));
         pem_file_upload(array("nameid" => "entry_upload_file", "value" => $entry_upload_file, "size" => 30));
         pem_field_note(array("default" => $upload_file_note, "required" => $fieldslist[$i]["required"]));

         pem_field_label(array("default" => __("File Link Text:"), "for" => "entry_upload_file_text", "class" => "desc"));
         pem_text_input(array("nameid" => "entry_upload_file_text", "value" => $entry_upload_file_text, "size" => 30, "maxlength" => 30));
         pem_field_note(array("default" => $upload_file_text_note, "required" => $fieldslist[$i]["required"]));
         echo '</div>' . "\n";

         if ($fieldslist[$i]["required"]) pem_hidden_input(array("name" => "required['" . $fieldslist[$i]["name"] . "']", "value" => $fieldslist[$i]["label"]));
         break;
*/

      case ($fieldslist[$i]["name"] == "entry_priv_notes"):
         if (!pem_user_anonymous()) // Anonymous users cannot add private notes
         {
            pem_field_label(array("default" => $fieldslist[$i]["label"], "for" => "entry_priv_notes", "class" => "desc"));
            pem_textarea_input(array("nameid" => "entry_priv_notes", "default" => $entry_priv_notes, "style" => "width:400px; height:100px;"));
            pem_field_note(array("default" => $priv_notes_note, "required" => $fieldslist[$i]["required"]));
            if ($fieldslist[$i]["required"]) pem_hidden_input(array("name" => "required['" . $fieldslist[$i]["name"] . "']", "value" => $fieldslist[$i]["label"]));
         }
         break;



      // HANDLE ANY DATE FIELDS
      case ($fieldslist[$i]["name"] == "date_name"):
// ICPL HACK limiting the date ranges
   if (!pem_user_anonymous()) // Anonymous users cannot create multi-day events
   {
         pem_field_label(array("default" => $fieldslist[$i]["label"], "for" => "date_name", "class" => "desc"));
         pem_text_input(array("nameid" => "date_name", "value" => $date_name, "size" => 30, "maxlength" => 80));
         pem_field_note(array("default" => $date_name_note, "required" => $fieldslist[$i]["required"]));
         if ($fieldslist[$i]["required"]) pem_hidden_input(array("name" => "required['" . $fieldslist[$i]["name"] . "']", "value" => $fieldslist[$i]["label"]));
   }
         break;
      case ($fieldslist[$i]["name"] == "date_description"):
// ICPL HACK limiting the date ranges
   if (!pem_user_anonymous()) // Anonymous users cannot create multi-day events
   {
         pem_field_label(array("default" => $fieldslist[$i]["label"], "for" => "date_description", "class" => "desc"));
         pem_textarea_input(array("nameid" => "date_description", "default" => $date_description, "style" => "width:400px; height:100px;"));
         pem_field_note(array("default" => $date_description_note, "required" => $fieldslist[$i]["required"]));
         if ($fieldslist[$i]["required"]) pem_hidden_input(array("name" => "required['" . $fieldslist[$i]["name"] . "']", "value" => $fieldslist[$i]["label"]));
   }
         break;
      case ($fieldslist[$i]["name"] == "date_category"):
         pem_field_label(array("default" => $fieldslist[$i]["label"], "for" => "date_category", "class" => "desc"));
         pem_category_select(array("nameid" => "date_category", "default" => $date_category));
         pem_field_note(array("default" => $category_note, "required" => $fieldslist[$i]["required"]));
         if ($fieldslist[$i]["required"]) pem_hidden_input(array("name" => "required['" . $fieldslist[$i]["name"] . "']", "value" => $fieldslist[$i]["label"]));
         break;
      case ($fieldslist[$i]["name"] == "date_presenter"):
         pem_field_label(array("default" => $fieldslist[$i]["label"], "for" => "date_presenter", "class" => "desc"));
         pem_text_input(array("nameid" => "date_presenter", "value" => $date_presenter, "size" => 30, "maxlength" => 80));
         pem_field_note(array("default" => $presenter_note, "required" => $fieldslist[$i]["required"]));
         if ($fieldslist[$i]["required"]) pem_hidden_input(array("name" => "required['" . $fieldslist[$i]["name"] . "']", "value" => $fieldslist[$i]["label"]));
         break;
      case ($fieldslist[$i]["name"] == "date_presenter_type"):
         pem_field_label(array("default" => $fieldslist[$i]["label"], "for" => "date_presenter_type", "class" => "desc"));
         pem_prestenter_type_select(array("nameid" => "date_presenter_type", "default" => $date_presenter_type, "showprompt" => true));
         pem_field_note(array("default" => $presenter_type_note, "required" => $fieldslist[$i]["required"]));
         if ($fieldslist[$i]["required"]) pem_hidden_input(array("name" => "required['" . $fieldslist[$i]["name"] . "']", "value" => $fieldslist[$i]["label"]));
         break;
      case ($fieldslist[$i]["name"] == "date_presenter"):
         pem_field_label(array("default" => $fieldslist[$i]["label"], "for" => "date_reg_require", "class" => "desc"));
         pem_boolean_select(array("nameid" => "date_reg_require", "default" => $date_reg_require));
         pem_field_note(array("default" => $reg_require_note, "required" => $fieldslist[$i]["required"]));
         if ($fieldslist[$i]["required"]) pem_hidden_input(array("name" => "required['" . $fieldslist[$i]["name"] . "']", "value" => $fieldslist[$i]["label"]));
         break;
      case ($fieldslist[$i]["name"] == "date_reg_require"):
         $javascript_fields[] = "date_reg_require";
         pem_field_label(array("default" => $fieldslist[$i]["label"], "for" => "date_reg_require", "class" => "desc"));
         pem_boolean_select(array("nameid" => "date_reg_require", "default" => $date_reg_require, "onchange" => "toggleLayer('date_registration', this.value);"));
         pem_field_note(array("default" => $reg_require_note, "required" => $fieldslist[$i]["required"]));
         if ($fieldslist[$i]["required"]) pem_hidden_input(array("name" => "required['" . $fieldslist[$i]["name"] . "']", "value" => $fieldslist[$i]["label"]));
         echo '<div id="date_registration" class="indent">' . "\n";
         if (array_key_exists("date_reg_current", $fieldbehavior))
         {
            pem_field_label(array("default" => $fieldbehavior["date_reg_current"]["label"], "for" => "date_reg_current", "class" => "desc"));
            pem_text_input(array("nameid" => "date_reg_current", "value" => $date_reg_current, "size" => 6, "maxlength" => 6));
            pem_field_note(array("default" => $reg_current_note, "required" => $fieldslist[$i]["required"]));
            if ($fieldslist[$i]["required"]) pem_hidden_input(array("name" => "required['date_reg_current']", "value" => $fieldslist[$i]["label"]));
         }
         if (array_key_exists("date_reg_max", $fieldbehavior))
         {
            pem_field_label(array("default" => $fieldbehavior["date_reg_max"]["label"], "for" => "date_reg_max", "class" => "desc"));
            pem_text_input(array("nameid" => "date_reg_max", "value" => $date_reg_max, "size" => 6, "maxlength" => 6));
            pem_field_note(array("default" => $reg_max_note, "required" => $fieldslist[$i]["required"]));
            if ($fieldslist[$i]["required"]) pem_hidden_input(array("name" => "required['date_reg_max']", "value" => $fieldslist[$i]["label"]));
         }
         if (array_key_exists("date_allow_wait", $fieldbehavior))
         {
            pem_field_label(array("default" => $fieldbehavior["date_allow_wait"]["label"], "for" => "date_allow_wait", "class" => "desc"));
            pem_boolean_select(array("nameid" => "date_allow_wait", "default" => $date_allow_wait));
            pem_field_note(array("default" => $allow_wait_note, "required" => $fieldslist[$i]["required"]));
            if ($fieldslist[$i]["required"]) pem_hidden_input(array("name" => "required['date_allow_wait']", "value" => $fieldslist[$i]["label"]));
         }
         if (array_key_exists("date_reg_begin", $fieldbehavior))
         {
            $date_reg_begin = $date_reg_begin_year . "-" . $date_reg_begin_month . "-" . $date_reg_begin_day;
            if ($date_reg_begin == "--") $date_reg_begin = (pem_cache_isset("current_year")) ? pem_cache_get("current_year") . "-" . pem_cache_get("current_month") . "-" . pem_cache_get("current_day") : pem_date("Y") . "-" . pem_date("m") . "-" . pem_date("d");
            pem_field_label(array("default" => $fieldbehavior["date_reg_begin"]["label"], "for" => "date_reg_begin_hour", "class" => "desc"));
            pem_date_selector("date_reg_begin_", array("default" => $date_reg_begin));
            pem_field_note(array("default" => $reg_begin_note, "required" => $fieldslist[$i]["required"]));
            if ($fieldslist[$i]["required"]) pem_hidden_input(array("name" => "required['date_reg_begin']", "value" => $fieldslist[$i]["label"]));
         }
         if (array_key_exists("date_reg_end", $fieldbehavior))
         {
            $date_reg_end = $date_reg_end_year . "-" . $date_reg_end_month . "-" . $date_reg_end_day;
            if ($date_reg_end == "--")
            {
               $tmpdate = explode("-", $date_begin);
               if ($tmpdate[2] > 1) $tmpdate[2]--;
               else
               {
                  if ($tmpdate[1] > 1) $tmpdate[2] = pem_date("t", mktime(0, 0, 0, $tmpdate[1]-1, 1, $tmpdate[0]));
                  else
                  {
                     $tmpdate[0]--;
                     $tmpdate[1] = 12;
                     $tmpdate[2] = 31;
                  }
               }
               $date_reg_end = $tmpdate[0] . "-" . $tmpdate[1] . "-" . $tmpdate[2];
             }
            pem_field_label(array("default" => $fieldbehavior["date_reg_end"]["label"], "for" => "date_reg_end_hour", "class" => "desc"));
            pem_date_selector("date_reg_end_", array("default" => $date_reg_end));
            pem_field_note(array("default" => $reg_end_note, "required" => $fieldslist[$i]["required"]));
            if ($fieldslist[$i]["required"]) pem_hidden_input(array("name" => "required['date_reg_end']", "value" => $fieldslist[$i]["label"]));
         }
         echo '</div>' . "\n";
         break;
      case ($fieldslist[$i]["name"] == "date_open_to_public"):
         pem_field_label(array("default" => $fieldslist[$i]["label"], "for" => "date_open_to_public", "class" => "desc"));
         // TODO ICPL HACK onchange
         if (pem_user_authorized("Admin") OR $source_type == "internal_scheduled")
         {
            pem_boolean_select(array("nameid" => "date_open_to_public", "default" => $date_open_to_public, "showprompt" => true));
         }
         else
         {
            pem_boolean_select(array("nameid" => "date_open_to_public", "default" => $date_open_to_public, "showprompt" => true, "onchange" => "if (document.getElementById('space1').checked) { this.selectedIndex = 2; }"));
         }
         pem_field_note(array("default" => $open_to_public_note, "required" => $fieldslist[$i]["required"], "noparse" => true));
         if ($fieldslist[$i]["required"]) pem_hidden_input(array("name" => "required['" . $fieldslist[$i]["name"] . "']", "value" => $fieldslist[$i]["label"]));
         break;
      case ($fieldslist[$i]["name"] == "date_visible_to_public"):
         pem_field_label(array("default" => $fieldslist[$i]["label"], "for" => "date_visible_to_public", "class" => "desc"));
         pem_boolean_select(array("nameid" => "date_visible_to_public", "default" => $date_visible_to_public, "showprompt" => true));
         pem_field_note(array("default" => $visible_to_public_note, "required" => $fieldslist[$i]["required"]));
         if ($fieldslist[$i]["required"]) pem_hidden_input(array("name" => "required['" . $fieldslist[$i]["name"] . "']", "value" => $fieldslist[$i]["label"]));
         break;
      case ($fieldslist[$i]["name"] == "date_seats_expected"):
         pem_field_label(array("default" => $fieldslist[$i]["label"], "for" => "date_seats_expected", "class" => "desc"));
         pem_text_input(array("nameid" => "date_seats_expected", "value" => $date_seats_expected, "size" => 6, "maxlength" => 20));
         pem_field_note(array("default" => $seats_expected_note, "required" => $fieldslist[$i]["required"]));
         if ($fieldslist[$i]["required"]) pem_hidden_input(array("name" => "required['" . $fieldslist[$i]["name"] . "']", "value" => $fieldslist[$i]["label"]));
         pem_hidden_input(array("name" => "validatenum['" . $fieldslist[$i]["name"] . "']", "value" => $fieldslist[$i]["label"]));
         break;
      case ($fieldslist[$i]["name"] == "date_priv_notes"):
         if (!pem_user_anonymous()) // Anonymous users cannot add private notes
         {
            pem_field_label(array("default" => $fieldslist[$i]["label"], "for" => "date_priv_notes", "class" => "desc"));
            pem_textarea_input(array("nameid" => "date_priv_notes", "default" => $date_priv_notes, "style" => "width:400px; height:100px;"));
            pem_field_note(array("default" => $priv_notes_note, "required" => $fieldslist[$i]["required"]));
            if ($fieldslist[$i]["required"]) pem_hidden_input(array("name" => "required['" . $fieldslist[$i]["name"] . "']", "value" => $fieldslist[$i]["label"]));
         }
         break;

      // HANDLE ANY META FIELDS
      case (substr($fieldslist[$i]["name"], 0, 4) == "meta"):
         switch(true)
         {
         case ($fieldslist[$i]["type"] == "textinput"):

//echo "-------- TESTING ----------<br /><pre>";
//print_r($entry_meta);
//echo "</pre><br />------------------------------<br /><pre>";
//print_r($date_meta);
//echo "</pre><br />------------------------------<br />";
//echo "name: " . ${$fieldslist[$i]["name"]}["input"];

            $fieldname = $fieldslist[$i]["name"] . "['input']";
            if (isset($_POST[$fieldslist[$i]["name"]]["'input'"])) $value = $_POST[$fieldslist[$i]["name"]]["'input'"];
            elseif (isset(${$fieldslist[$i]["name"]}["input"])) $value = ${$fieldslist[$i]["name"]}["input"];
            else $value = "";
            pem_field_label(array("default" => $fieldslist[$i]["value"]["input_label"], "for" => $fieldname, "class" => "desc"));
            pem_text_input(array("name" => $fieldname, "value" => $value));
            pem_field_note(array("default" => $fieldslist[$i]["value"]["input_note"], "required" => $fieldslist[$i]["required"]));
            if ($fieldslist[$i]["required"]) pem_hidden_input(array("name" => "required['" . $fieldslist[$i]["name"] . "']", "value" => $fieldslist[$i]["value"]["input_label"]));
            break;
         case ($fieldslist[$i]["type"] == "checkbox"):
            $fieldname = $fieldslist[$i]["name"] . "['box']";
            if (isset($_POST[$fieldslist[$i]["name"]]["'box'"])) $checked = 1;
            elseif (isset(${$fieldslist[$i]["name"]}["box"])) $checked = ${$fieldslist[$i]["name"]}["box"];
            else $checked = 0;
            pem_field_label(array("default" => $fieldslist[$i]["value"]["box_text"], "for" => $fieldname, "class" => "entry", "style" => "width:auto;"));
            pem_checkbox(array("name" => $fieldname, "status" => $checked, "style" => "float:left; margin-right:2px;"));
            pem_field_note(array("default" => $fieldslist[$i]["value"]["box_note"], "required" => $fieldslist[$i]["required"]));
            if ($fieldslist[$i]["required"]) pem_hidden_input(array("name" => "required['" . $fieldslist[$i]["name"] . "']", "value" => $fieldslist[$i]["value"]["input_label"]));
            break;
         case ($fieldslist[$i]["type"] == "boolean"):

// ICPL HACK live on the chanel vis
$login = pem_get_login();
$pass = pem_get_pass();
$this_user = pem_get_row("user_login", $login, "users");
if (!$fieldslist[$i]["id"] == 7 OR $source_type != "external_scheduled" OR $this_user["user_profile"] == 13 OR pem_user_authorized("Admin"))
{
            $fieldname = $fieldslist[$i]["name"] . "['boolean']";
//echo "-------- TESTING ----------<br /><pre>";
//print_r(unserialize($entry_meta));
//echo "</pre><br />------------------------------<br /><pre>";
//print_r(unserialize($date_meta));
//echo "</pre><br />------------------------------<br />";
//echo "name: " . ${$fieldslist[$i]["name"]}["'boolean'"];
            if (isset($_POST[$fieldslist[$i]["name"]]["'boolean'"])) $selected = $_POST[$fieldslist[$i]["name"]]["'boolean'"];
            elseif (isset(${$fieldslist[$i]["name"]}["'boolean'"])) $selected = ${$fieldslist[$i]["name"]}["'boolean'"];
            else $selected = "";
            $select_label = $fieldslist[$i]["value"]["select_label"];
            $select_note = $fieldslist[$i]["value"]["select_note"];
            unset($fieldslist[$i]["value"]["select_label"]);
            unset($fieldslist[$i]["value"]["select_note"]);
            pem_field_label(array("default" => $select_label, "for" => $fieldname, "class" => "desc", "style" => "width:auto;"));
            pem_boolean_select(array("name" => $fieldname, "default" => $selected, "showprompt" => true));
            pem_field_note(array("default" => $select_note, "required" => $fieldslist[$i]["required"]));
            if ($fieldslist[$i]["required"]) pem_hidden_input(array("name" => "required['" . $fieldslist[$i]["name"] . "']", "value" => $select_label));
}
            break;
         case ($fieldslist[$i]["type"] == "select"):
            $fieldname = $fieldslist[$i]["name"] . "['select']";
            if (isset($_POST[$fieldslist[$i]["name"]]["'select'"])) $selected = $_POST[$fieldslist[$i]["name"]]["'select'"];
            elseif (isset(${$fieldslist[$i]["name"]}["select"])) $selected = ${$fieldslist[$i]["name"]}["select"];
            else $selected = "";
            $select_label = $fieldslist[$i]["value"]["select_label"];
            $select_note = $fieldslist[$i]["value"]["select_note"];
            unset($fieldslist[$i]["value"]["select_label"]);
            unset($fieldslist[$i]["value"]["select_note"]);
            $options = array_flip($fieldslist[$i]["value"]);
            pem_field_label(array("default" => $select_label, "for" => $fieldname, "class" => "desc"));
            pem_meta_select($options, array("name" => $fieldname, "default" => $selected));
            pem_field_note(array("default" => $select_note, "required" => $fieldslist[$i]["required"]));
            if ($fieldslist[$i]["required"]) pem_hidden_input(array("name" => "required['" . $fieldslist[$i]["name"] . "']", "value" => $select_label));
            break;
         case ($fieldslist[$i]["type"] == "contact"):
            $default_values["city"] = $default_city;
            $default_values["state"] = $default_state;
            $default_values["phone1"] = $default_phone;
            $default_values["phone2"] = $default_phone;
            $default_values["email"] = $default_email;
            $size["name1"] = 40;
            $size["name2"] = 40;
            $size["street1"] = 40;
            $size["street2"] = 40;
            $size["city"] = 30;
            $size["state"] = 30;
            $size["postal"] = 10;
            $size["phone1"] = 18;
            $size["phone2"] = 18;
            $size["email"] = 40;

//print_r($fieldslist[$i]["name"]);
//
            if (isset($_POST[$fieldslist[$i]["name"]]))
            {
               $postfield = $_POST[$fieldslist[$i]["name"]];
               $postinput = $_POST[$fieldslist[$i]["name"] . "_input"];
               $post_keys = array_keys($postinput);
            }
            elseif (isset(${$fieldslist[$i]["name"]}))
            {
               $postfield = $fieldslist[$i]["name"];
               $postinput = ${$fieldslist[$i]["name"] . "_input"};
               $post_keys = array_keys($postinput);
//echo "name: " . $postfield . "<br />";
//echo "name: ";
            }
            $value_keys = array_keys($fieldslist[$i]["value"]);
            $required_labels = "";
            for ($j = 0; $j < count($value_keys); $j++)
            {
               if (!empty($postinput))
               {
                  $checkname = "'" . $value_keys[$j] . "'";
                  for ($k = 0; $k < count($post_keys); $k++)
                  {
                     if ($post_keys[$k] == $checkname) $fieldvalue = $postinput[$post_keys[$k]];
                  }
               }
               else $fieldvalue = $default_values[$value_keys[$j]];
               $fieldname = $fieldslist[$i]["name"] . "_input['" . $value_keys[$j] . "']";
               if ($fieldslist[$i]["value"][$value_keys[$j]][1])
               {

                  if ($value_keys[$j] == "state" AND $state_select)
                  {
                     $note = (!empty($fieldslist[$i]["value"][$value_keys[$j]][2])) ? '(' . $fieldslist[$i]["value"][$value_keys[$j]][2] . ')' : " ";
                     pem_field_label(array("default" => $fieldslist[$i]["value"][$value_keys[$j]][0], "for" => $fieldname, "class" => "desc", "noparse" => true));
                     pem_echo_state_select(array("name" => $fieldname, "default" => $fieldvalue));
                     pem_field_note(array("default" => $note, "linebreak" => false, "style" => "float:left;"));
                  }
                  elseif ($value_keys[$j] == "postal" AND $state_select)
                  {
                     $note = (!empty($fieldslist[$i]["value"][$value_keys[$j]][2])) ? '(' . $fieldslist[$i]["value"][$value_keys[$j]][2] . ')' : " ";
                     pem_field_label(array("default" => $fieldslist[$i]["value"][$value_keys[$j]][0], "for" => $fieldname, "class" => "desc", "noparse" => true, "style" => "margin-left:20px; width:auto;"));
                     pem_text_input(array("name" => $fieldname, "value" => $fieldvalue, "size" => $size[$value_keys[$j]], "maxlength" => 60));
                     pem_field_note(array("default" => $note, "required" => $fieldslist[$i]["value"][$value_keys[$j]][1] == 2));
                  }
                  else
                  {
                     $note = (!empty($fieldslist[$i]["value"][$value_keys[$j]][2])) ? '(' . $fieldslist[$i]["value"][$value_keys[$j]][2] . ')' : " ";
                     pem_field_label(array("default" => $fieldslist[$i]["value"][$value_keys[$j]][0], "for" => $fieldname, "class" => "desc"));
                     pem_text_input(array("name" => $fieldname, "value" => $fieldvalue, "size" => $size[$value_keys[$j]], "maxlength" => 60));
                     pem_field_note(array("default" => $note, "required" => $fieldslist[$i]["value"][$value_keys[$j]][1] == 2));
                     if ($fieldslist[$i]["value"][$value_keys[$j]][1] == 2) $required_labels .= $fieldslist[$i]["value"][$value_keys[$j]][0] . ",";
                  }
               }
            }
            if (!empty($required_labels)) pem_hidden_input(array("name" => "required['" . $fieldslist[$i]["name"] . "']", "value" => $required_labels));
            break;
         }
         pem_hidden_input(array("name" => "meta[]", "value" => $fieldslist[$i]["id"]));
         pem_hidden_input(array("name" => $fieldslist[$i]["name"] . "['type']", "value" => $fieldslist[$i]["type"]));
         pem_hidden_input(array("name" => $fieldslist[$i]["name"] . "['parent']", "value" => $fieldslist[$i]["parent"]));
         break; // END meta case
      }
   }

   echo '</div>' . "\n";  // END Stage3 Indent
   pem_submit_template("submitform");
   echo '</div>' . "\n"; // END Stage3

   pem_form_end();
   echo '</div>' . "\n"; //  END main_form

   echo '<br />' . "\n";

   echo '<div id="template_form">' . "\n";
   pem_form_begin(array("nameid" => "templateform", "action" => $PHP_SELF, "class" => "neweventform", "enctype" => "multipart/form-data"));

   pem_field_label(array("default" => __("Template Name:"), "for" => "template_name", "class" => "desc"));
   pem_text_input(array("nameid" => "template_name", "value" => $template_name, "size" => 30, "maxlength" => 80));
   pem_field_note(array("default" => $template_name_note));
   // pem_submit_template("submitform");

   pem_form_end();
   echo '</div>' . "\n"; //  END template_form

   echo '<script type="text/javascript"><!--' . "\n";
   echo "toggleLayer('template_form', 0);\n";
// ICPL HACK limiting the date ranges
   if (!pem_user_anonymous()) // Anonymous users cannot create multi-date events
   {
      echo "toggleLayer('recurring_duration_select', " . $multi_date_event . ");\n";
      echo "toggleRecurranceDuration();\n";
   }
   if (in_array("entry_reg_require", $javascript_fields)) echo "toggleLayer('entry_registration', document.submitform.entry_reg_require.value);\n";
   if (in_array("date_reg_require", $javascript_fields)) echo "toggleLayer('date_registration', document.submitform.date_reg_require.value);\n";
   if (in_array("entry_add_image", $javascript_fields)) echo "toggleLayer('entry_image', document.submitform.entry_upload_image.value);\n";
   if (in_array("date_add_image", $javascript_fields)) echo "toggleLayer('date_image', document.submitform.date_upload_image.value);\n";
   if (in_array("entry_add_file", $javascript_fields)) echo "toggleLayer('entry_file', document.submitform.entry_upload_file.value);\n";
   if (in_array("date_add_file", $javascript_fields)) echo "toggleLayer('date_file', document.submitform.date_upload_file.value);\n";
  // echo "ChangeOptionDays(document.submitform,'date_begin_');\n";
  // echo "ChangeOptionDays(document.submitform,'date_end_');\n";
   echo "toggleLayer('date_end_select', " . $multi_day_event . ");\n";
   echo "toggleLayer('recurring_type_select', " . $multi_date_event . ");\n";

   if (!empty($error)) { echo "showPopup('errorbox');\n"; }

//   echo pem_xajax_onchange("submitform", "check_conflicts");
   echo "xajax_check_conflicts(xajax.getFormValues('submitform'), xajax.$('errorbox').innerHTML);\n";
   echo '// --></script>' . "\n";

   } // END if Stage 2 or Stage3

} // END echo_form

?>