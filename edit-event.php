<?php
/* ========================== FILE INFORMATION =================================
phxEventManager :: edit-event.php

============================================================================= */
$inajax = true;
include_once "pem-config.php";
include_once ABSPATH . "/pem-settings.php";
$did = pem_cache_get("current_date");

$XAJAX_DIR = PEMINC . "/xajax/";
include_once $XAJAX_DIR . "xajax_core/xajax.inc.php";
$xajax = new xajax();
// $xajax->setFlag("debug", true);
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
   global $date_format, $time_format, $did;

   $objResponse = new xajaxResponse();

   if (!$formdata["conflicting"]AND !isset($formdata["spaces"]))
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
      if (isset($formdata["multi_day_event"]))
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

      $objResponse->assign("daydisplay", "innerHTML", pem_date(" (l)", $real_begin));

      //$display_begin = "2006-01-01 00:00:00";
      //$display_end = "2008-01-01 00:00:00";

      if (isset($formdata["spaces"]) AND strtotime($when_begin) >= strtotime($when_end))
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

         // all edits are non-recurring
         // if (!isset($formdata["multi_date_event"]))
         // {
            // =========== BEGIN SCHEDULE CONFLICT CHECKS =========================
            if ($current_event_type != "allday" AND !isset($formdata["multi_day_event"]))
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
            if (!isset($formdata["multi_day_event"]))
            {
               $conflict_text .= pem_check_event_conflicts($pemdb, $error, $real_begin, $real_end, $form_spaces, "", $did);
            }
            else
            {
               $check_day = strtotime($date_begin);
               $finish_day = strtotime($date_end);
               while($check_day <= $finish_day) // walk through each day looking for a time conflict
               {
                  $conflict_hold = pem_check_event_conflicts($pemdb, $error, $real_begin, $real_end, $form_spaces, $check_day, $did);
                  if (!empty($conflict_hold))
                  {
                     $conflict_text .= '<li class="head">' . __("Space conflicts for") . ' ' . pem_date($date_format, $check_day) . '</li>' . "\n";
                     $conflict_text .= $conflict_hold;
                  }
                  $check_day = strtotime("+1 day", $check_day);
               }
            }
            // =========== END EVENT CONFLICT CHECKS ==============================
         // }
         /* else // Submission is multi-date (recurring)
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
               if ($current_event_type != "allday" AND !isset($formdata["multi_day_event"]))
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
         */

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
            $objResponse->script("toggleLayer('stage3', 1)");
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


$pagetitle = "Edit Event";
// $page_access_requirement = array("Manage Internal Events", "Manage External Events");
//BASED ON ACCESS ADJUST THE NAVIGATION BAR ACCORDINGLY TO SHOW THE PUB OPTIONS VS THE ADMIN OPTIONS
$navigation = "event";
$cache_set = array("current_eview" => "edit");

$use_thickbox = true;
$use_xajax = true;
include_once "pem-includes/header.php";
extract(pem_get_settings());
extract(pem_scheduling_boundaries());

$showaddform = true;
if (isset($datasubmit))
{
   unset($error);
   if (isset($required) AND $datasubmit != "stage1" AND $source_type != $old_source_type)
   {
      $directions = __("You have changed types and reset field options and behaviors.  Please check over the form to be sure you have completed the necessary fields.");
   }
// TODO confirm the stage changing here, shouldn't need the stage ref on edit, but is there a problem with real conflicts due to changes attempting a submit
//   elseif (isset($required) AND $datasubmit == "stage3")
   elseif (isset($required))
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
               if (empty($meta_value[$meta_keys[$i]]) AND !empty($labels[$i])) $error[] = sprintf(__("%s cannot be empty."), $labels[$i]);
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

   switch (true)
   {
   case ($current_event_type == "scheduled"):
      $source_type = ($_POST["source_type"] == "internal") ? "internal_scheduled" : "external_scheduled";
      $page_title = __("Edit Calendar Event");
      break;
   case ($current_event_type == "unscheduled"):
      $source_type = ($_POST["source_type"] == "internal") ? "internal_unscheduled" : "external_unscheduled";
      $page_title = __("Edit Side Box Event");
      break;
   case ($current_event_type == "allday"):
      $source_type = "internal_scheduled";
      $page_title = __("Edit All-Day Event");
      break;
   }

   switch (true)
   {
// TODO another check for the right branching scheme here
/*   case ($datasubmit == "stage2"):
echo "second branch<br />";
      $showaddform = false;
      pem_fieldset_begin($page_title);
      echo_form("stage2", $error, $directions);
      pem_fieldset_end();
      break;
*/
   case ($datasubmit == "stage2" AND isset($error)):
      $showaddform = false;
      pem_fieldset_begin($page_title);
      echo_form("stage3", $error, $directions);
      pem_fieldset_end();
      break;
   case ($datasubmit == "stage2" OR $datasubmit == "stage3"):
      $date_begin = $_POST["date_begin_year"] . "-" . zeropad($_POST["date_begin_month"], 2) . "-" . zeropad($_POST["date_begin_day"], 2);
      if (isset($_POST["multi_day_event"]))
      {
         $date_end = $_POST["date_end_year"] . "-" . zeropad($_POST["date_end_month"], 2) . "-" . zeropad($_POST["date_end_day"], 2);
      }
      else
      {
         $date_end = $date_begin;
      }
      $time_begin = pem_time($_POST["time_begin_hour"], $_POST["time_begin_minute"], (isset($_POST["time_begin_meridiem"])) ? $_POST["time_begin_meridiem"] : "");
      $time_end = pem_time($_POST["time_end_hour"], $_POST["time_end_minute"], (isset($_POST["time_end_meridiem"])) ? $_POST["time_end_meridiem"] : "");

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

      //if ($conflicting AND !isset($_POST["multi_date_event"]))
      //{
         // =========== BEGIN EVENT CONFLICT CHECKS ============================
         if (!isset($_POST["multi_day_event"]))
         {
            $conflict_text .= pem_check_event_conflicts($pemdb, $error, $real_begin, $real_end, $form_spaces, "", $did);
         }
         else
         {
            $check_day = strtotime($date_begin);
            $finish_day = strtotime($date_end);
            while($check_day <= $finish_day) // walk through each day looking for a time conflict
            {
               $conflict_hold = pem_check_event_conflicts($pemdb, $error, $real_begin, $real_end, $form_spaces, $check_day, $did);
               if (!empty($conflict_hold))
               {
               $conflict_text .= '<li class="head">' . __("Space conflicts for") . ' ' . pem_date($date_format, $check_day) . '</li>' . "\n";
               $conflict_text .= $conflict_hold;
               }
               $check_day = strtotime("+1 day", $check_day);
            }
         }
         // =========== END EVENT CONFLICT CHECKS ==============================
      /*
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
      */
      if (!empty($conflict_text))
      {
         $conflicts = '<p style="margin-bottom:0;"><b>' . __("Conflicts found.  Please adjust your time and location to search for openings.") . '</b></p>';
         $conflicts .= '<ul class="bullets">' . "\n";
         $conflicts .= $conflict_text;
         $conflicts .= '</ul>' . "\n";
         pem_fieldset_begin(__("Edit Calendar Event"));
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
         $when_begin = MDB2_Date::date2Mdbstamp($time_begin_array[0], $time_begin_array[1], $time_begin_array[2], $date_begin_month, $date_begin_day, $date_begin_year);
         $when_end = MDB2_Date::date2Mdbstamp($time_end_array[0], $time_end_array[1], $time_end_array[2], $date_end_month, $date_end_day, $date_end_year);

         $real_begin_time = pem_time_subtract($time_begin, $setup_time_before_hours, $setup_time_before_minutes);
         $real_end_time = pem_time_add($time_end, $cleanup_time_after_hours, $cleanup_time_after_minutes);
         $real_begin_time = pem_time_subtract($real_begin_time, $buffer_time_before_hours, $buffer_time_before_minutes);
         $real_end_time = pem_time_add($real_end_time, $buffer_time_after_hours, $buffer_time_after_minutes);
         $real_begin_array = explode(":", $real_begin_time);
         $real_end_array = explode(":", $real_end_time);
         $real_begin = MDB2_Date::date2Mdbstamp($real_begin_array[0], $real_begin_array[1], $real_begin_array[2], $date_begin_month, $date_begin_day, $date_begin_year);
         $real_end = MDB2_Date::date2Mdbstamp($real_end_array[0], $real_end_array[1], $real_end_array[2], $date_end_month, $date_end_day, $date_end_year);

         $display_begin = $entry_created_stamp;
         $display_end = MDB2_Date::unix2Mdbstamp (strtotime("+" . $display_duration . " month"));

         $entry_type_nums = array("internal_scheduled" => 1, "external_scheduled" => 2, "internal_unscheduled" => 3, "external_unscheduled" => 3);
         $entry_type = $entry_type_nums[$source_type];

         //  ICPL HACK Community for public events
         if (2 == $entry_type) $entry_category = 2; //  Set to Community for public events

         $login = pem_get_login();
         $user_id = auth_get_user_id($login);

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
                  $thismeta[trim($key, "\x22\x27")] = $val;
               }
               break;
            }
         }

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
            "entry_edited_by" => $user_id, "entry_edited_stamp" => $entry_created_stamp
         );
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
            "entry_edited_by" => "text", "entry_edited_stamp" => "timestamp"
         );
         $where = array("id" => $entry_id);
         pem_update_row("entries", $entry_data, $where);
//         $entry_id = pem_add_row("entries", $entry_data, $entry_types);
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
            "date_edited_by" => $user_id, "date_edited_stamp" => $date_created_stamp
         );
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
            "date_edited_by" => "text", "date_edited_stamp" => "timestamp"
         );

         $where = array("id" => $did);
         pem_update_row("dates", $date_data, $where);

//         echo "header('Location: /view.php?e=event&did=' . $did);";
//         exit;
         pem_cache_set("current_email", "edit");

         header('Location: /view.php?e=event&did=' . $did);
      }
      $showaddform = false;
      break;
   }
}

//$did = pem_cache_get("current_date");
//echo "DATE ID: $did <br />";

// display add new form if edit not in progress
if ($showaddform)
{
   $current_event_type = pem_cache_get("current_event_type");
   switch (true)
   {
   case ($current_event_type == "scheduled"):
      $page_title = __("Edit Calendar Event");
      break;
   case ($current_event_type == "unscheduled"):
      $page_title = __("Edit Side Box Event");
      break;
   case ($current_event_type == "allday"):
      $page_title = __("Edit All-Day Event");
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



function echo_form($mode = "stage2", $error = "", $directions = "", $conflicts = "")
{
   global $PHP_SELF, $_POST, $pem_url, $time_format, $date_format, $default_setup_time, $default_buffer_time;
   global $state_select, $default_city, $default_state, $default_phone, $default_email;
   global $table_prefix, $dsn, $options;

   if (!empty($_POST))
   {
      extract($_POST);

      $current_event_type = pem_cache_get("current_event_type");
      switch (true)
      {
      case ($current_event_type == "scheduled"):
         if (isset($_POST["source_type"])) $source_type = ($_POST["source_type"] == "internal") ? "internal_scheduled" : "external_scheduled";
         break;
      case ($current_event_type == "unscheduled"):
         if (isset($_POST["source_type"])) $source_type = ($_POST["source_type"] == "internal") ? "internal_unscheduled" : "external_unscheduled";
         break;
      case ($current_event_type == "allday"):
         if (isset($_POST["source_type"])) $source_type = "internal_scheduled";
         break;
      }

      $date_begin = $date_begin_year . "-" . $date_begin_month . "-" . $date_begin_day;
      $date_end = $date_end_year . "-" . $date_end_month . "-" . $date_end_day;
      $time_begin = pem_time($time_begin_hour, $time_begin_minute, $time_begin_meridiem);
      $time_end = pem_time($time_end_hour, $time_end_minute, $time_end_meridiem);
      if ($date_begin == "--") $date_begin = (pem_cache_isset("current_year")) ? pem_cache_get("current_year") . "-" . pem_cache_get("current_month") . "-" . pem_cache_get("current_day") : pem_date("Y-m-d");
      if ($date_end == "--") $date_end =  (pem_cache_isset("current_year")) ? pem_cache_get("current_year") . "-" . pem_cache_get("current_month") . "-" . pem_cache_get("current_day") : pem_date("Y-m-d");
      if ($time_begin == "00:00:00") $time_begin = (pem_cache_isset("current_hour")) ? pem_cache_get("current_hour") . ":" . pem_cache_get("current_minute") : pem_date("H:i");
      if ($time_end == "00:00:00") $time_end = pem_date("H:i", strtotime(pem_date("Y-m-d") . " " . $time_begin) + 3600);

      $setup_time_before = pem_time_quantity_to_real(array("hours" => $setup_time_before_hours, "minutes" => $setup_time_before_minutes));
      $cleanup_time_after = pem_time_quantity_to_real(array("hours" => $cleanup_time_after_hours, "minutes" => $cleanup_time_after_minutes));
      $buffer_time_before = pem_time_quantity_to_real(array("hours" => $buffer_time_before_hours, "minutes" => $buffer_time_before_minutes));
      $buffer_time_after = pem_time_quantity_to_real(array("hours" => $buffer_time_after_hours, "minutes" => $buffer_time_after_minutes));

      $setup_time_before = ($setup_time_before === "") ? $default_setup_time : $setup_time_before;
      $cleanup_time_after = ($cleanup_time_after === "") ? $default_setup_time : $cleanup_time_after;
      $buffer_time_before = ($buffer_time_before === "") ? $default_buffer_time : $buffer_time_before;
      $buffer_time_after = ($buffer_time_after === "") ? $default_buffer_time : $buffer_time_after;

      $multi_day_event = (isset($multi_day_event)) ? 1 : 0;
      $multi_date_event = (isset($multi_date_event)) ? 1 : 0;

      $entry_reg_begin = $entry_reg_begin_year . "-" . $entry_reg_begin_month . "-" . $entry_reg_begin_day;
      $entry_reg_end = $entry_reg_end_year . "-" . $entry_reg_end_month . "-" . $entry_reg_end_day;
      $date_reg_begin = $date_reg_begin_year . "-" . $date_reg_begin_month . "-" . $date_reg_begin_day;
      $date_reg_end = $date_reg_end_year . "-" . $date_reg_end_month . "-" . $date_reg_end_day;
   }
   else
   {
      global $did;
      $pemdb =& mdb2_connect($dsn, $options, "connect");
      $sql = "SELECT * FROM " . $table_prefix . "entries as e, " . $table_prefix . "dates as d WHERE ";
      $sql .= "e.id = d.entry_id AND ";
      $sql .= "d.id = :date_id AND ";
      $sql .= "e.entry_status != 2 AND ";
      $sql .= "d.date_status != 2";
      $sql_values = array("date_id" => $did);
      $eventret = pem_exec_sql($sql, $sql_values);
      $this_event = $eventret[0];
      unset($eventret);
      extract($this_event);

      $entry_open_to_public = ($this_event["entry_open_to_public"] == 1) ? 2 : 1;
      $entry_visible_to_public = ($this_event["entry_visible_to_public"] == 1) ? 2 : 1;
      $date_open_to_public = ($this_event["date_open_to_public"] == 1) ? 2 : 1;
      $date_visible_to_public = ($this_event["date_visible_to_public"] == 1) ? 2 : 1;

      $spaces_array = unserialize($this_event["spaces"]);
      unset($spaces);
      if (isset($spaces_array)) foreach ($spaces_array AS $value) $spaces[$value] = 1;
      else $spaces = "";
      $supplies = unserialize($this_event["supplies"]);
      $entry_meta = unserialize($this_event["entry_meta"]);
      $date_meta = unserialize($this_event["date_meta"]);

/*
echo "entry_meta:<br />";
print_r($entry_meta);
echo "<br />---------------------------------------------------------<br />";
echo "date_meta:<br />";
print_r($date_meta);
echo "<br />---------------------------------------------------------<br />";
*/
      unset($meta);
      if (!empty($entry_meta)) foreach ($entry_meta AS $key => $value)
      {
         $meta[] = $key;
         switch($value["type"])
         {
         case ("textinput"):
            ${"meta" . $key} = array("type" => $value["type"], "input" => $value["data"]);
            break;
         case ("checkbox"):
            ${"meta" . $key} = array("type" => $value["type"], "box" => $value["data"]);
            break;
         case ("boolean"):
            ${"meta" . $key} = array("type" => $value["type"], "boolean" => $value["data"]);
            break;
         case ("select"):
            ${"meta" . $key} = array("type" => $value["type"], "select" => $value["data"]);
            break;
         case ("contact"):
            ${"meta" . $key} = array("type" => $value["type"]);
            foreach ($value AS $vkey => $vvalue)
            {
               if ($vkey != "type") ${"meta" . $key . "_input"}["'$vkey'"] = $vvalue;
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
            ${"meta" . $key} = array("'type'" => $value["type"], "'input'" => $value["data"]);
            break;
         case ("checkbox"):
            ${"meta" . $key} = array("'type'" => $value["type"], "'box'" => $value["data"]);
            break;
         case ("boolean"):
            $boolval = ($value["data"] == 1) ? 2 : 1;
            ${"meta" . $key} = array("'type'" => $value["type"], "'boolean'" => $boolval);
            break;
         case ("select"):
            ${"meta" . $key} = array("'type'" => $value["type"], "'select'" => $value["data"]);
            break;
         case ("contact"):
            ${"meta" . $key} = array("'type'" => $value["type"]);
            foreach ($value AS $vkey => $vvalue)
            {
               if ($vkey != "type") ${"meta" . $key . "_input"}["'$vkey'"] = $vvalue;
            }
            break;
         }
      }

/*
foreach ($entry_meta AS $key => $value)
{
echo "entry meta" . $key . ":<br />";
print_r(${"meta" . $key});
if ($value["type"] == "contact")
{
echo "entry meta" . $key . "_input:<br />";
print_r(${"meta" . $key . "_input"});

}
echo "<br />---------------------------------------------------------<br />";
}
foreach ($date_meta AS $key => $value)
{
echo "date meta" . $key . ":<br />";
print_r(${"meta" . $key});
echo "<br />---------------------------------------------------------<br />";
}
*/

      /*
[meta2_input] => Array ( ['name1'] => staff contact ['phone1'] => 319-555-5555 ['email'] => contact email )

entry_meta:
[2] => Array ( [type] => contact [name1] => staff contact [phone1] => 319-555.1234 [email] => staff@icpl.org )
[1] => Array ( [type] => contact [name1] => co-sponsor [name2] => co-sponsor contact [phone1] => 319-555.5678 [email] => co-sponsor email ) )
date_meta:
[7] => Array ( [type] => boolean [data] => 0 )
[11] => Array ( [type] => boolean [data] => 1 )
[8] => Array ( [type] => boolean [data] => 1 )
[9] => Array ( [type] => boolean [data] => 1 )
[10] => Array ( [type] => boolean [data] => 1 )


[meta] => Array ( [0] => 2 [1] => 1 [2] => 7 [3] => 11 [4] => 8 [5] => 9 [6] => 10 )
[meta2] => Array ( ['type'] => contact ['parent'] => 0 )
[meta2_input] => Array ( ['name1'] => ['phone1'] => 319- ['email'] => )
[meta1] => Array ( ['type'] => contact ['parent'] => 0 )
[meta1_input] => Array ( ['name1'] => ['name2'] => ['phone1'] => 319- ['email'] => )
[meta7] => Array ( ['boolean'] => ['type'] => boolean ['parent'] => 1 )
[meta11] => Array ( ['boolean'] => ['type'] => boolean ['parent'] => 1 )
[meta13] => Array ( ['box'] => on ['type'] => checkbox ['parent'] => 0 ) )


[meta3_input] => Array ( ['name1'] => ['street1'] => ['city'] => Iowa City ['state'] => IA ['postal'] => ['phone1'] => 319- ['email'] => )
[meta] => Array ( [0] => 3 [1] => 6 [2] => 5 [3] => 4 )
[meta3] => Array ( ['type'] => contact ['parent'] => 0 )
[meta6] => Array ( ['input'] => ['type'] => textinput ['parent'] => 0 )
[meta5] => Array ( ['select'] => ['type'] => select ['parent'] => 0 )
[meta4_input] => Array ( ['name1'] => ['phone1'] => 319- ['email'] => )
[meta4] => Array ( ['type'] => contact ['parent'] => 0 )

['meta3'] => Contact Person:,Contact Address:,City:,Contact Phone:,
['meta6'] => Group Name: ['meta5'] => Group Type:
['meta4'] => Second Contact Name:,Second Contact Phone:
*/

//echo "entry type: $entry_type <br />";
      switch (true)
      {
      case ($allday == 1):
         $source_type = "internal_scheduled";
         $current_event_type = "allday";
         pem_cache_set("current_event_type", "allday");
         break;
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

      $date_begin = pem_date("Y-m-d", $when_begin);
      $date_end = pem_date("Y-m-d", $when_end);
      $time_begin = pem_date("H:i:s", $when_begin);
      $time_end = pem_date("H:i:s", $when_end);

      $multi_day_event = ($date_begin == $date_end) ? 0 : 1;

      if ($date_reg_begin == "0000-00-00") $date_reg_begin = (pem_cache_isset("current_year")) ? pem_cache_get("current_year") . "-" . pem_cache_get("current_month") . "-" . pem_cache_get("current_day") : pem_date("Y") . "-" . pem_date("m") . "-" . pem_date("d");
      if ($date_reg_end == "0000-00-00") $date_reg_end = $date_begin;
   }
/*
echo "_POST:<br />";
print_r($_POST);
echo "<br />---------------------------------------------------------<br />";
*/
/*
echo "optsupply1:<br />";
print_r($optsupply1);
echo "<br />---------------------------------------------------------<br />";
*/
/*
echo "thisevent:<br />";
print_r($this_event);
echo "<br />---------------------------------------------------------<br />";
*/

   $recur_times_note = __("(Includes starting date)");
   $recur_till_note = __("(Last event will be on or before this date)");

   if (empty($directions)) $directions = '<p>' . __("Make changes to the fields below and click submit to edit this event.") . '</p>';
   $source_type_note = __("(Determines field and option visibility through the form)");

   $entry_name_note = __("(Global event name used with all dates)");
   $entry_description_note = __("(Global event description used with all dates)");
   $category_note = __("(Define a color-coded category for this event)");
   $date_name_note = __("(Local name added to the global name to form the full event title)");
   $date_description_note = __("(Local date description, can be edited at each date of a recurring event without affecting other dates' descriptions)");

   $presenter_note = __("(Enter the name of the presenter here)");
   $presenter_type_note = __("(This is the label that will be used to identify the presenter above)");
// ICPL HACK note change
   // $open_to_public_note = __("(Can this event be attended by anyone in the general public?)");
   $open_to_public_note = __("(Can this event be attended by anyone in the general public? <b>NOTE:</b> Programs in Room A must be open to the public)");
   $visible_to_public_note = __("(Is an account required to see this event in the calendar?)");
   $reg_require_note = __("(Does this event require online registration to attend?)");
   $reg_current_note = __("(Set the starting reserved seats)");
   $reg_max_note = __("(If this is left blank, space occupancy is used for a maximum)");
   $allow_wait_note = __("(Registrations past the maximum will automatically fill spaces that open up)");
   $reg_begin_note = __("(When registrations will begin to be taken)");
   $reg_end_note = __("(Registrations past this date are not allowed)");
// ICPL HACK note change
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
   $authorized_internal_calendar = pem_user_authorized(array("Internal Calendar" => "Edit"));
   $authorized_external_calendar = pem_user_authorized(array("External Calendar" => "Edit"));
   $authorized_internal_sidebox = pem_user_authorized(array("Internal Side Box" => "Edit"));
   $authorized_external_sidebox = pem_user_authorized(array("External Side Box" => "Edit"));
   // =========================================================================

   pem_form_begin(array("nameid" => "submitform", "action" => $PHP_SELF, "class" => "neweventform", "enctype" => "multipart/form-data"));
   pem_hidden_input(array("nameid" => "entry_id", "value" => $entry_id));

   if (pem_user_anonymous()) // Anonymous users can only work with external events
   {
      pem_hidden_input(array("nameid" => "datasubmit", "value" => "stage2"));
      pem_hidden_input(array("nameid" => "old_source_type", "value" => "external"));
      pem_hidden_input(array("nameid" => "source_type", "value" => "external"));
      $mode = "stage2";
      $source_type = "external_scheduled";
   }
   elseif ($current_event_type == "allday")
   {
      pem_hidden_input(array("nameid" => "datasubmit", "value" => "stage2"));
      pem_hidden_input(array("nameid" => "old_source_type", "value" => "internal"));
      pem_hidden_input(array("nameid" => "source_type", "value" => "internal"));
      $mode = "stage2";
      $source_type = "internal_scheduled";
   }
// ICPL HACK to prevent external displays
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
      pem_hidden_input(array("nameid" => "datasubmit", "value" => $mode));
      $source_type_sub = explode("_", $source_type);
      pem_hidden_input(array("nameid" => "old_source_type", "value" => (empty($_POST["source_type"])) ? $source_type_sub[0] : $_POST["source_type"]));

      pem_field_label(array("default" => __("Source Type:"), "for" => "source_type"));
      $select_mode = ($mode == "stage1") ? "stage2" : $mode;

      pem_entry_source_type_select(array("nameid" => "source_type", "default" => $source_type_sub[0], "onchange" => "sourcetypeaction();"));
      //pem_entry_source_type_select(array("nameid" => "source_type", "default" => $source_type, "onchange" => "action_submit('submitform', '$select_mode');"));

      pem_field_note(array("default" => $source_type_note));
   }
   pem_hidden_input(array("nameid" => "current_event_type", "value" => $current_event_type));


   //get list of active fields
   $fieldbehavior = pem_active_fields($source_type);
   //order the fields
   $fieldslist = pem_order_fields($fieldbehavior, $source_type);

/*
echo "<pre>";
print_r($fieldbehavior);
echo "</pre><br />";
echo "===========================================================";
echo "<pre>";
print_r($fieldslist);
echo "</pre><br />";
*/


   $cleanup_time_after_note = __("(Setup and cleanup are used to reserve additional non-public room time)");
   $buffer_time_after_note = __("(Buffer times insure events do not conflict with one another)");

   echo '<h3 style="margin-top:10px;">' . __("Event Occurs When") . '</h3>' . "\n";
   echo '<br /><div class="indent">' . "\n";
   pem_field_label(array("default" => __("Date Begins:"), "for" => "date_begin_hour", "class" => "timeoccurs"));
   pem_date_selector("date_begin_", array("default" => $date_begin, "onchange" => pem_xajax_onchange("submitform", "check_conflicts")));
   echo '<div id="daydisplay" class="note"></div>' . "\n";
   pem_field_note(array("default" => $date_begin_note));

   echo '<div id="date_end_select" style="float:left;">' . "\n";
   pem_field_label(array("default" => __("Date Ends:"), "for" => "date_end_hour", "class" => "timeoccurs"));
   pem_date_selector("date_end_", array("default" => $date_end, "onchange" => pem_xajax_onchange("submitform", "check_conflicts")));
   pem_field_note(array("default" => $date_end_note));
   echo '</div>' . "\n";
   pem_checkbox(array("nameid" => "multi_day_event", "status" => $multi_day_event, "onclick" => "toggleLayer('date_end_select', this.checked); xajax_check_conflicts(xajax.getFormValues('submitform'), xajax.$('errorbox').innerHTML);", "style" => "float:left;"));
   pem_field_label(array("default" => __("Multi-Day Event"), "for" => "multi_day_event"));
   echo '<br />' . "\n";

   pem_hidden_input(array("nameid" => "multi_date_event", "value" => ""));
   /*
   echo '<div id="recurring_type_select" style="float:left;">' . "\n";
   pem_field_label(array("default" => __("Repeats:"), "for" => "recurring_event", "class" => "timeoccurs"));
   pem_recurring_type_select(array("nameid" => "recurring_event", "default" => $recurring_event, "onchange" => pem_xajax_onchange("submitform", "check_conflicts")));
   pem_field_note(array("default" => $recurring_event_note));
   echo '</div>' . "\n";
   pem_checkbox(array("nameid" => "multi_date_event", "status" => $multi_date_event, "onclick" => "toggleLayer('recurring_type_select', this.checked); toggleLayer('recurring_duration_select', this.checked); xajax_check_conflicts(xajax.getFormValues('submitform'), xajax.$('errorbox').innerHTML);", "style" => "float:left;"));
   pem_field_label(array("default" => __("Recurring Event"), "for" => "multi_date_event"));
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
   */

   if ($current_event_type != "allday")
   {
      pem_hidden_input(array("nameid" => "conflicting", "value" => 1));
   }
   else
   {
      if (!isset($conflicting)) $conflicting  = (!empty($_POST)) ? 0 : 1;
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
// ICPL HACK all day shortened to 8am-10pm
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
               pem_field_note(array("default" => __("(info)"), "link" => $pem_url . "pem-content/" . $space_list[$j]["space_popup"]));
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

//echo "space list: <pre>";
//print_r($space_list[4]);
//echo "</pre><br />";
//echo "spaces: <pre>";
//print_r($spaces);
//echo "</pre><br />";

   if (!empty($spaces))
   {
      // Collect and write out options for supplies to the form.
      $form_spaces = array_keys($spaces);

//echo "form_spaces: ";
//print_r($form_spaces);
//echo "<br />";


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


//echo "supplies: <pre>";
//print_r($supplies);
//echo "</pre><br />";
//echo "id: " . $space_list[$i]["id"] . "<br />";
//
//echo "hunting for profile: ";
//print_r($space_list[$i]["supply_profile"]);
//echo "<br />";
//echo "sql: $sql_profiles<br />";

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

//echo "opt field: $opt_field_tmp <br />";
//$opt_field_tmp2 = ${$opt_field_tmp};
//echo "opt value: " . $opt_field_tmp2[$supply_row["id"]] . " <br />";

                  $opt_default = (isset($opt_field_tmp[$supply_row["id"]])) ? $opt_field_tmp[$supply_row["id"]] : $supplies[$space_list[$i]["id"]][$supply_row["id"]];



//echo "id: " . $supply_row["id"] . "<br />";
//print_r($supplies[$space_list[$i]["id"]][$supply_row["id"]]);
//echo "<br />";

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
      echo '</div>' . "\n";
      $supply_text = ob_get_clean();
      if ($got_supplies) echo '<br />' . "\n" . '<h3 class="ieheight">' . __("Location Supplies") . '</h3>' . "\n" . $supply_text;
   } // END if (!empty($spaces))

   echo '</div>' . "\n";

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
      case ($fieldslist[$i]["name"] == "entry_priv_notes"):
         pem_field_label(array("default" => $fieldslist[$i]["label"], "for" => "entry_priv_notes", "class" => "desc"));
         pem_textarea_input(array("nameid" => "entry_priv_notes", "default" => $entry_priv_notes, "style" => "width:400px; height:100px;"));
         pem_field_note(array("default" => $priv_notes_note, "required" => $fieldslist[$i]["required"]));
         if ($fieldslist[$i]["required"]) pem_hidden_input(array("name" => "required['" . $fieldslist[$i]["name"] . "']", "value" => $fieldslist[$i]["label"]));
         break;

      // HANDLE ANY DATE FIELDS
      case ($fieldslist[$i]["name"] == "date_name"):
         pem_field_label(array("default" => $fieldslist[$i]["label"], "for" => "date_name", "class" => "desc"));
         pem_text_input(array("nameid" => "date_name", "value" => $date_name, "size" => 30, "maxlength" => 80));
         pem_field_note(array("default" => $date_name_note, "required" => $fieldslist[$i]["required"]));
         if ($fieldslist[$i]["required"]) pem_hidden_input(array("name" => "required['" . $fieldslist[$i]["name"] . "']", "value" => $fieldslist[$i]["label"]));
         break;
      case ($fieldslist[$i]["name"] == "date_description"):
         pem_field_label(array("default" => $fieldslist[$i]["label"], "for" => "date_description", "class" => "desc"));
         pem_textarea_input(array("nameid" => "date_description", "default" => $date_description, "style" => "width:400px; height:100px;"));
         pem_field_note(array("default" => $date_description_note, "required" => $fieldslist[$i]["required"]));
         if ($fieldslist[$i]["required"]) pem_hidden_input(array("name" => "required['" . $fieldslist[$i]["name"] . "']", "value" => $fieldslist[$i]["label"]));
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
            pem_field_label(array("default" => $fieldbehavior["date_reg_begin"]["label"], "for" => "date_reg_begin_hour", "class" => "desc"));
            pem_date_selector("date_reg_begin_", array("default" => $date_reg_begin));
            pem_field_note(array("default" => $reg_begin_note, "required" => $fieldslist[$i]["required"]));
            if ($fieldslist[$i]["required"]) pem_hidden_input(array("name" => "required['date_reg_begin']", "value" => $fieldslist[$i]["label"]));
         }
         if (array_key_exists("date_reg_end", $fieldbehavior))
         {
            pem_field_label(array("default" => $fieldbehavior["date_reg_end"]["label"], "for" => "date_reg_end_hour", "class" => "desc"));
            pem_date_selector("date_reg_end_", array("default" => $date_reg_end));
            pem_field_note(array("default" => $reg_end_note, "required" => $fieldslist[$i]["required"]));
            if ($fieldslist[$i]["required"]) pem_hidden_input(array("name" => "required['date_reg_end']", "value" => $fieldslist[$i]["label"]));
         }
         echo '</div>' . "\n";
         break;
      case ($fieldslist[$i]["name"] == "date_open_to_public"):
         pem_field_label(array("default" => $fieldslist[$i]["label"], "for" => "date_open_to_public", "class" => "desc"));
         // ICPL HACK onchange
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
         pem_field_label(array("default" => $fieldslist[$i]["label"], "for" => "date_priv_notes", "class" => "desc"));
         pem_textarea_input(array("nameid" => "date_priv_notes", "default" => $date_priv_notes, "style" => "width:400px; height:100px;"));
         pem_field_note(array("default" => $priv_notes_note, "required" => $fieldslist[$i]["required"]));
         if ($fieldslist[$i]["required"]) pem_hidden_input(array("name" => "required['" . $fieldslist[$i]["name"] . "']", "value" => $fieldslist[$i]["label"]));
         break;

      // HANDLE ANY META FIELDS
      case (substr($fieldslist[$i]["name"], 0, 4) == "meta"):
         switch(true)
         {
         case ($fieldslist[$i]["type"] == "textinput"):
            $fieldname = $fieldslist[$i]["name"] . "['input']";
            $thisvar = ${$fieldslist[$i]["name"]};
            $value = isset($thisvar["input"]) ? $thisvar["input"] : "";
            pem_field_label(array("default" => $fieldslist[$i]["value"]["input_label"], "for" => $fieldname, "class" => "desc"));
            pem_text_input(array("name" => $fieldname, "value" => $value));
            pem_field_note(array("default" => $fieldslist[$i]["value"]["input_note"], "required" => $fieldslist[$i]["required"]));
            if ($fieldslist[$i]["required"]) pem_hidden_input(array("name" => "required['" . $fieldslist[$i]["name"] . "']", "value" => $fieldslist[$i]["value"]["input_label"]));
            break;
         case ($fieldslist[$i]["type"] == "checkbox"):
            $fieldname = $fieldslist[$i]["name"] . "['box']";
            $thisvar = ${$fieldslist[$i]["name"]};
            $checked = isset($thisvar["'box'"]) ? 1 : 0;
            pem_field_label(array("default" => $fieldslist[$i]["value"]["box_text"], "for" => $fieldname, "class" => "entry", "style" => "width:auto;"));
            pem_checkbox(array("name" => $fieldname, "status" => $checked, "style" => "float:left; margin-right:2px;"));
            pem_field_note(array("default" => $fieldslist[$i]["value"]["box_note"], "required" => $fieldslist[$i]["required"]));
            if ($fieldslist[$i]["required"]) pem_hidden_input(array("name" => "required['" . $fieldslist[$i]["name"] . "']", "value" => $fieldslist[$i]["value"]["input_label"]));
            break;
         case ($fieldslist[$i]["type"] == "boolean"):
            $fieldname = $fieldslist[$i]["name"] . "['boolean']";
            $thisvar = ${$fieldslist[$i]["name"]};
            $selected = isset($thisvar["'boolean'"]) ? $thisvar["'boolean'"] : "";
            $select_label = $fieldslist[$i]["value"]["select_label"];
            $select_note = $fieldslist[$i]["value"]["select_note"];
            unset($fieldslist[$i]["value"]["select_label"]);
            unset($fieldslist[$i]["value"]["select_note"]);
            pem_field_label(array("default" => $select_label, "for" => $fieldname, "class" => "desc", "style" => "width:auto;"));
            pem_boolean_select(array("name" => $fieldname, "default" => $selected, "showprompt" => true));
            pem_field_note(array("default" => $select_note, "required" => $fieldslist[$i]["required"]));
            if ($fieldslist[$i]["required"]) pem_hidden_input(array("name" => "required['" . $fieldslist[$i]["name"] . "']", "value" => $select_label));
            break;
         case ($fieldslist[$i]["type"] == "select"):
            $fieldname = $fieldslist[$i]["name"] . "['select']";
            $thisvar = ${$fieldslist[$i]["name"]};
            $selected = isset($thisvar["select"]) ? $thisvar["select"] : "";
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
            $thisvar = ${$fieldslist[$i]["name"]};
            if (isset($thisvar))
            {
               $postfield = $thisvar;
               $postinput = ${$fieldslist[$i]["name"] . "_input"};
               $post_keys = array_keys($postinput);
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
                     $note = (!empty($fieldslist[$i]["value"][$value_keys[$j]][2])) ? $fieldslist[$i]["value"][$value_keys[$j]][2] : " ";
                     pem_field_label(array("default" => $fieldslist[$i]["value"][$value_keys[$j]][0], "for" => $fieldname, "class" => "desc", "noparse" => true));
                     pem_echo_state_select(array("name" => $fieldname, "default" => $fieldvalue));
                     pem_field_note(array("default" => $note, "linebreak" => false, "style" => "float:left;"));
                  }
                  elseif ($value_keys[$j] == "postal" AND $state_select)
                  {
                     pem_field_label(array("default" => $fieldslist[$i]["value"][$value_keys[$j]][0], "for" => $fieldname, "class" => "desc", "noparse" => true, "style" => "margin-left:20px; width:auto;"));
                     pem_text_input(array("name" => $fieldname, "value" => $fieldvalue, "size" => $size[$value_keys[$j]], "maxlength" => 60));
                     pem_field_note(array("default" => $fieldslist[$i]["value"][$value_keys[$j]][2], "required" => $fieldslist[$i]["value"][$value_keys[$j]][1] == 2));
                  }
                  else
                  {
                     pem_field_label(array("default" => $fieldslist[$i]["value"][$value_keys[$j]][0], "for" => $fieldname, "class" => "desc"));
                     pem_text_input(array("name" => $fieldname, "value" => $fieldvalue, "size" => $size[$value_keys[$j]], "maxlength" => 60));
                     pem_field_note(array("default" => $fieldslist[$i]["value"][$value_keys[$j]][2], "required" => $fieldslist[$i]["value"][$value_keys[$j]][1] == 2));
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
         break;
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
   //echo "toggleLayer('recurring_duration_select', " . $multi_date_event . ");\n";
   //echo "toggleRecurranceDuration();\n";
   if (in_array("entry_reg_require", $javascript_fields)) echo "toggleLayer('entry_registration', document.submitform.entry_reg_require.value);\n";
   if (in_array("date_reg_require", $javascript_fields)) echo "toggleLayer('date_registration', document.submitform.date_reg_require.value);\n";
   if (in_array("entry_add_image", $javascript_fields)) echo "toggleLayer('entry_image', document.submitform.entry_upload_image.value);\n";
   if (in_array("date_add_image", $javascript_fields)) echo "toggleLayer('date_image', document.submitform.date_upload_image.value);\n";
   if (in_array("entry_add_file", $javascript_fields)) echo "toggleLayer('entry_file', document.submitform.entry_upload_file.value);\n";
   if (in_array("date_add_file", $javascript_fields)) echo "toggleLayer('date_file', document.submitform.date_upload_file.value);\n";
   echo "ChangeOptionDays(document.submitform,'date_begin_');\n";
   echo "ChangeOptionDays(document.submitform,'date_end_');\n";
   echo "toggleLayer('date_end_select', " . $multi_day_event . ");\n";
   //echo "toggleLayer('recurring_type_select', " . $multi_date_event . ");\n";

   if (!empty($error)) { echo "showPopup('errorbox');\n"; }

   echo "xajax_check_conflicts(xajax.getFormValues('submitform'), xajax.$('errorbox').innerHTML);\n";
   echo '// --></script>' . "\n";



} // END echo_form

?>