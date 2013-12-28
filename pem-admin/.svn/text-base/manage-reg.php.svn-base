<?php
/* ========================== FILE INFORMATION =================================
phxEventManager :: manage-reg.php

============================================================================= */

$pagetitle = "Manage Registrations";
$navigation = "administration";
$cache_set = array("current_navigation" => "events");
include_once "../pem-includes/header.php";

$did = pem_cache_get("current_date");
$eid = pem_cache_get("current_event");

$pemdb =& mdb2_connect($dsn, $options, "connect");

// get this event id and reg toggle
if (empty($eid))
{
   $sql = "SELECT e.entry_name, e.entry_reg_require, d.entry_id FROM " . $table_prefix . "entries as e, " . $table_prefix . "dates as d WHERE ";
   $sql .= "e.id = d.entry_id AND ";
   $sql .= "d.id = :date_id AND ";
   $sql .= "e.entry_status != 2 AND ";
   $sql .= "d.date_status != 2";
   $sql_values = array("date_id" => $did);
   $eventres = pem_exec_sql($sql, $sql_values);
   $entry_name = $eventres[0]["entry_name"];
   $entry_reg_require = (empty($eventres[0]["entry_reg_require"])) ? 0 : 1;
   $eid = $eventres[0]["entry_id"];
}
else
{
   $sql = "SELECT e.entry_name, e.entry_reg_require FROM " . $table_prefix . "entries as e WHERE ";
   $sql .= "e.id = :entry_id AND ";
   $sql .= "e.entry_status != 2";
   $sql_values = array("entry_id" => $eid);
   $eventres = pem_exec_sql($sql, $sql_values);
   $entry_name = $eventres[0]["entry_name"];
   $entry_reg_require = (empty($eventres[0]["entry_reg_require"])) ? 0 : 1;
}
unset($eventres);

$showaddform = true;
if (isset($datasubmit))
{
   unset($error);
   if ($datasubmit == "finishentry" OR $datasubmit == "finishdate" OR $datasubmit == "updatereg")
   {
      $reminder = (isset($reminder)) ? 1 : 0;
      if (isset($required))
      {
         foreach ($required as $key => $val)  // cycle through required fields to check for data
         {
            $check_name = trim($key, "\x22\x27");
            $check_value = ${$check_name};
            if (empty($check_value))
            {
               $label = (substr($required[$key], -1, 1) == ":") ? substr($required[$key], 0, strlen($required[$key])-1) : $required[$key];
               $error[] = sprintf(__("%s cannot be empty."), $label);
            }
         }
      }
      if ($reminder AND empty($email)) $error[] = __("You must enter an email address to receive a reminder.");
      if (isset($regdates))
      {
         foreach ($regdates as $key => $val) // cycle through dateids to remove quotes
         {
            $regdate_hold[] = trim($key, "\x22\x27");
         }
         $regdates = $regdate_hold;
         unset($regdate_hold);
      }
      $values["name1"] = $name1;
      $values["name2"] = $name2;
      $values["street1"] = $street1;
      $values["street2"] = $street2;
      $values["city"] = $city;
      $values["state"] = $state;
      $values["postal"] = $postal;
      $values["phone1"] = $phone1;
      $values["phone2"] = $phone2;
      $values["email"] = $email;
      $values["reminder"] = $reminder;
      $values["regdates"] = $regdates;
   }

   switch (true)
   {
//   case ($datasubmit == "newentry"):
//      $showaddform = false;
//      $data = pem_get_row("id", $entry_id, "entries");
//      pem_fieldset_begin(sprintf(__("Add Registration for %s"), $data["entry_name"]));
//      echo '<p>' . __("Complete the information and submit the form to add the new registration.") . "</p>\n";
//      echo_form($entry_id, "", "finishentry");
//      pem_fieldset_end();
//      break;
//   case ($datasubmit == "finishentry" AND isset($error)):
//      $showaddform = false;
//      $data = pem_get_row("id", $entry_id, "entries");
//      pem_fieldset_begin(sprintf(__("Add Registration for %s"), $data["entry_name"]));
//      echo '<p>' . $error_instructions . "</p>\n";
//      $values["entry_id"] = $entry_id;
//      echo_form($entry_id, $values, "finishentry", $error);
//      pem_fieldset_end();
//      break;
//   case ($datasubmit == "finishentry"):
//      $data = array("entry_id" => $entry_id, "date_id" => "", "name1" => $name1, "name2" => $name2, "street1" => $street1, "street2" => $street2, "city" => $city, "state" => $state, "postal" => $postal, "phone1" => $phone1, "phone2" => $phone2, "email" => $email, "reminder" => $reminder);
//      $types = array("event_id" => "integer", "date_id" => "integer", "name_first" => "text", "name_last" => "text", "street" => "text", "street2" => "text", "city" => "text", "state" => "text", "postal" => "text", "phone" => "text", "phone2" => "text", "email" => "text", "reminder" => "boolean");
//      pem_add_row("registrants", $data, $types);
//      echo '<p><b>' . __("Registration added.") . '</b></p>' . "\n";
//      break;
      case ($datasubmit == "activateentry"):
         $data = array("entry_reg_require" => "1");
         $where = array("id" => $entry_id);
         pem_update_row("entries", $data, $where);
         echo '<p><b>' . __("Registration is now active for this event.") . '</b></p>' . "\n";
         $entry_reg_require = true;
         break;
      case ($datasubmit == "deactivateentry"):
         $data = array("entry_reg_require" => "0");
         $where = array("id" => $entry_id);
         pem_update_row("entries", $data, $where);
         echo '<p><b>' . __("Registration had been deactivated for this event.  Existing registrations are saved and will be available if registration is reactivated.") . '</b></p>' . "\n";
         $entry_reg_require = false;
         break;
      case ($datasubmit == "editentry"):
         header("Location: /view.php?eid=" . $entry_id . "&e=edit");
         break;
      case ($datasubmit == "viewentrylist"):
         header("Location: /view.php?eid=" . $entry_id . "&e=regs");
         break;


//   case ($datasubmit == "newdate"):
//      $showaddform = false;
//      $data = pem_get_row("id", $date_id, "dates");
//      pem_fieldset_begin(sprintf(__("Add Registration for %s"), pem_date("l, " . $date_format, $data["when_begin"])));
//      echo '<p>' . __("Complete the information and submit the form to add the new registration.") . "</p>\n";
//      echo_form($date_id, "", "finishdate");
//      pem_fieldset_end();
//      break;
//   case ($datasubmit == "finishdate" AND isset($error)):
//      $showaddform = false;
//      $data = pem_get_row("id", $date_id, "dates");
//      pem_fieldset_begin(sprintf(__("Add Registration for %s"), pem_date("l, " . $date_format, $data["when_begin"])));
//      echo '<p>' . $error_instructions . "</p>\n";
//      $values["date_id"] = $date_id;
//      echo_form($date_id, $values, "finishdate", $error);
//      pem_fieldset_end();
//      break;
//   case ($datasubmit == "finishdate"):
//      $data = array("entry_id" => "", "date_id" => $date_id, "name1" => $name1, "name2" => $name2, "street1" => $street1, "street2" => $street2, "city" => $city, "state" => $state, "postal" => $postal, "phone1" => $phone1, "phone2" => $phone2, "email" => $email, "reminder" => $reminder);
//      $types = array("event_id" => "integer", "date_id" => "integer", "name_first" => "text", "name_last" => "text", "street" => "text", "street2" => "text", "city" => "text", "state" => "text", "postal" => "text", "phone" => "text", "phone2" => "text", "email" => "text", "reminder" => "boolean");
//      pem_add_row("registrants", $data, $types);
//      echo '<p><b>' . __("Registration added.") . '</b></p>' . "\n";
//      break;
      case ($datasubmit == "activatedate"):
         $data = array("date_reg_require" => "1");
         $where = array("id" => $date_id);
         pem_update_row("dates", $data, $where);
         echo '<p><b>' . __("Registration is now active for this date.") . '</b></p>' . "\n";
         break;
      case ($datasubmit == "deactivatedate"):
         $data = array("date_reg_require" => "0");
         $where = array("id" => $date_id);
         pem_update_row("dates", $data, $where);
         echo '<p><b>' . __("Registration had been deactivated for this date.  Existing registrations are saved and will be available if registration is reactivated.") . '</b></p>' . "\n";
         break;
      case ($datasubmit == "editdate"):
         header("Location: /view.php?did=" . $date_id . "&e=edit");
         break;
      case ($datasubmit == "viewdatelist"):
         header("Location: /view.php?did=" . $date_id . "&e=regs");
         break;

      case ($datasubmit == "editreg"):
         $showaddform = false;
         pem_fieldset_begin(__("Edit Registration"));
         echo '<p>' . __("Change the information as desired and submit the form to save your changes.") . "</p>\n";
         $data = pem_get_row("id", $reg_id, "registrants");
         echo_form($date_id, $data, "updatereg");
         pem_fieldset_end();
         break;
      case ($datasubmit == "updatereg" AND isset($error)):
         $showaddform = false;
         pem_fieldset_begin(__("Edit Registration"));
         echo '<p>' . $error_instructions . "</p>\n";
         echo_form($date_id, $values, "updatereg", $error);
         pem_fieldset_end();
         break;
      case ($datasubmit == "updatereg"):
         $data = array("name1" => $name1, "name2" => $name2, "street1" => $street1, "street2" => $street2, "city" => $city, "state" => $state, "postal" => $postal, "phone1" => $phone1, "phone2" => $phone2, "email" => $email, "reminder" => $reminder);
         $where = array("id" => $reg_id);
         pem_update_row("registrants", $data, $where);
         echo '<p><b>' . __("Registration updated.") . '</b></p>' . "\n";
         break;
      case ($datasubmit == "movereg"):
         $showaddform = false;
         $data = pem_get_row("id", $reg_id, "registrants");
         pem_fieldset_begin(sprintf(__("Move %s to a Different Date"), $data["name1"] . " " . $data["name2"]));
         echo '<p>' . __("Select the new date for this registrant and submit the form to complete the move.") . "</p>\n";
         echo_move_form($reg_id, $date_id, $entry_id);
         pem_fieldset_end();
         break;
      case ($datasubmit == "finishmove"):
         MDB2::loadFile("Date"); // load Date helper class
         $reg_stamp = MDB2_Date::mdbNow();
         // Move registration
         if ($move_reg_to == "entry")
         {
            $data = array("entry_id" => $eid, "date_id" => "", "reg_stamp" => $reg_stamp);
         }
         else
         {
            $data = array("entry_id" => "", "date_id" => $move_reg_to, "reg_stamp" => $reg_stamp);
         }
         $where = array("id" => $reg_id);
         pem_update_row("registrants", $data, $where);
         // update new location count
         if ($move_reg_to == "entry")
         {
            $sql = "UPDATE " . $table_prefix . "entries SET entry_reg_current = entry_reg_current + 1 WHERE id = :entry_id";
            $sql_values = array("entry_id" => $entry_id);
         }
         else
         {
            $sql = "UPDATE " . $table_prefix . "dates SET date_reg_current = date_reg_current + 1 WHERE id = :date_id";
            $sql_values = array("date_id" => $move_reg_to);
         }
         pem_exec_sql($sql, $sql_values);
         // update old location count
         if (empty($date_id))
         {
            $sql = "UPDATE " . $table_prefix . "entries SET entry_reg_current = entry_reg_current - 1 WHERE id = :entry_id";
            $sql_values = array("entry_id" => $entry_id);
         }
         else
         {
            $sql = "UPDATE " . $table_prefix . "dates SET date_reg_current = date_reg_current - 1 WHERE id = :date_id";
            $sql_values = array("date_id" => $date_id);
         }
         pem_exec_sql($sql, $sql_values);
         echo '<p><b>' . __("Registration moved.") . '</b></p>' . "\n";
         break;
      case ($datasubmit == "copyreg"):
         $showaddform = false;
         $data = pem_get_row("id", $reg_id, "registrants");
         pem_fieldset_begin(sprintf(__("Copy %s to One or More Other Dates"), $data["name1"] . " " . $data["name2"]));
         echo '<p>' . __("Check the additional date(s) for this registrant and submit the form to complete the copy.") . "</p>\n";
         echo_copy_form($reg_id, $date_id, $entry_id);
         pem_fieldset_end();
         break;
      case ($datasubmit == "finishcopy"):
         $regdata = pem_get_row("id", $reg_id, "registrants");
         extract($regdata);
         MDB2::loadFile("Date"); // load Date helper class
         $reg_stamp = MDB2_Date::mdbNow();
         if (isset($regdates)) foreach ($regdates as $key => $value)
            {
               if ($key == "entry")
               {
                  $data = array("entry_id" => $entry_id, "date_id" => "", "name1" => $name1, "name2" => $name2, "street1" => $street1, "street2" => $street2, "city" => $city, "state" => $state, "postal" => $postal, "phone1" => $phone1, "phone2" => $phone2, "email" => $email, "reminder" => $reminder, "reg_stamp" => $reg_stamp);
                  $types = array("event_id" => "integer", "date_id" => "integer", "name_first" => "text", "name_last" => "text", "street" => "text", "street2" => "text", "city" => "text", "state" => "text", "postal" => "text", "phone" => "text", "phone2" => "text", "email" => "text", "reminder" => "boolean", "reg_stamp" => "timestamp");
                  $newid = pem_add_row("registrants", $data, $types);
                  $sql = "UPDATE " . $table_prefix . "entries SET entry_reg_current = entry_reg_current + 1 WHERE id = :entry_id";
                  $sql_values = array("entry_id" => $entry_id);
                  pem_exec_sql($sql, $sql_values);
               }
               else
               {
                  $data = array("entry_id" => "", "date_id" => $key, "name1" => $name1, "name2" => $name2, "street1" => $street1, "street2" => $street2, "city" => $city, "state" => $state, "postal" => $postal, "phone1" => $phone1, "phone2" => $phone2, "email" => $email, "reminder" => $reminder, "reg_stamp" => $reg_stamp);
                  $types = array("event_id" => "integer", "date_id" => "integer", "name_first" => "text", "name_last" => "text", "street" => "text", "street2" => "text", "city" => "text", "state" => "text", "postal" => "text", "phone" => "text", "phone2" => "text", "email" => "text", "reminder" => "boolean", "reg_stamp" => "timestamp");
                  $newid = pem_add_row("registrants", $data, $types);
                  $sql = "UPDATE " . $table_prefix . "dates SET date_reg_current = date_reg_current + 1 WHERE id = :date_id";
                  $sql_values = array("date_id" => $key);
                  pem_exec_sql($sql, $sql_values);
               }
            }
//      if ($copy_reg_to == "entry") $data = array("entry_id" => $eid, "date_id" => "");
//      else $data = array("entry_id" => "", "date_id" => $move_reg_to);
//      $where = array("id" => $reg_id);
//      pem_update_row("registrants", $data, $where);
         echo '<p><b>' . __("Registration copied.") . '</b></p>' . "\n";
         break;
      case ($datasubmit == "deletereg"):
         $data = pem_get_row("id", $reg_id, "registrants");
         // update old location count
         if (!empty($data["entry_id"]))
         {
            $sql = "UPDATE " . $table_prefix . "entries SET entry_reg_current = entry_reg_current - 1 WHERE id = :entry_id";
            $sql_values = array("entry_id" => $data["entry_id"]);
         }
         else
         {
            $sql = "UPDATE " . $table_prefix . "dates SET date_reg_current = date_reg_current - 1 WHERE id = :date_id";
            $sql_values = array("date_id" => $data["date_id"]);
         }
         pem_exec_sql($sql, $sql_values);
         //complete delete
         $where = array("id" => $reg_id);
         pem_delete_perm("registrants", $where);
         echo '<p><b>' . __("Registration deleted.") . '</b></p>' . "\n";
         break;
   }
}

// get all dates for this event
$sql = "SELECT id, date_name, when_begin, date_reg_require, date_reg_current, date_reg_max, date_allow_wait, date_reg_begin, date_reg_end FROM " . $table_prefix . "dates WHERE ";
$sql .= "entry_id = :entry_id AND ";
$sql .= "date_status != 2";
$sql_values = array("entry_id" => $eid);
$eventres = pem_exec_sql($sql, $sql_values);
$datelist = $eventres;
unset($eventres);
$datecount = count($datelist);
$regcount = $datecount + $entry_reg_require;

$title = $entry_name . ' (' . $datecount . ' ';
$title .= ($datecount > 1) ? 'dates)' : 'date)';
pem_fieldset_begin($title);
echo '<div class="fscontrols">' . "\n";
//if ($entry_reg_require) $controls[] = array("label" => __("Add New Registration"), "onclick" => "action_submit('dataformentry', 'newentry');");
if (!$entry_reg_require) $controls[] = array("label" => __("Activate Global Event Registration"), "onclick" => "action_submit('dataformentry', 'activateentry');");
else $controls[] = array("label" => __("Deactivate Global Event Registration"), "onclick" => "action_submit('dataformentry', 'deactivateentry');");
$controls[] = array("label" => __("Edit Event"), "onclick" => "action_submit('dataformentry', 'editentry');");
if ($entry_reg_require) $controls[] = array("label" => __("View List Order or Add New"), "onclick" => "action_submit('dataformentry" . $id . "', 'viewentrylist');");

pem_controls($controls);
echo '</div>' . "\n";
pem_form_begin(array("nameid" => "dataformentry", "action" => $PHP_SELF));
pem_hidden_input(array("name" => "datasubmit", "value" => ""));
pem_hidden_input(array("name" => "entry_id", "value" => $eid));
pem_form_end();

$table_header =  array(
        __("Phone"),
        __("Email"),
        __("Reminder"),
);

$where = array("entry_id" => $eid);
$list = pem_get_rows("registrants", $where, "AND", "name2, name1");
ob_start();
for ($i = 0; $i < count($list); $i++)
{
   unset($controls);
   $row = ($i % 2) ? "row2" : "row1";
   echo '<tr class="' . $row . '"><td>' . "\n";
   pem_form_begin(array("nameid" => "dateform" . $list[$i]["id"], "action" => $PHP_SELF));
   pem_hidden_input(array("name" => "datasubmit", "value" => "editreg"));
   pem_hidden_input(array("name" => "reg_id", "value" => $list[$i]["id"]));
   pem_hidden_input(array("name" => "entry_id", "value" => $eid));
   pem_hidden_input(array("name" => "date_id", "value" => ""));
   pem_field_label(array("default" => $list[$i]["name1"] . ' ' . $list[$i]["name2"]));
   pem_form_end();
   echo '</td><td>' . "\n";
   if (!empty($list[$i]["phone1"])) echo $list[$i]["phone1"];
   if (!empty($list[$i]["phone1"]) AND !empty($list[$i]["phone2"])) echo ', ';
   if (!empty($list[$i]["phone2"])) echo $list[$i]["phone2"];
   echo '</td><td>' . "\n";
   if (!empty($list[$i]["email"])) echo '<a href="mailto:' . $list[$i]["email"] . '">' . $list[$i]["email"] . '</a>';
   echo '</td><td>' . "\n";
   echo ($list[$i]["reminder"]) ? __("Yes") : __("No");
   echo '</td><td class="controlboxwide">' . "\n";
   if ($regcount > 1)
   {
      $controls[] = array("label" => __("Move"), "onclick" => "action_submit('dateform" . $list[$i]["id"] . "', 'movereg');");
      $controls[] = array("label" => __("Copy"), "onclick" => "action_submit('dateform" . $list[$i]["id"] . "', 'copyreg');");
   }
   $controls[] = array("label" => __("Edit"), "onclick" => "action_submit('dateform" . $list[$i]["id"] . "', 'editreg');");
   $controls[] = array("label" => __("Delete"), "onclick" => "confirm_submit('dateform" . $list[$i]["id"] . "', 'deletereg', '" . $delete_confirm . "');");
   pem_controls($controls);
   echo '</td></tr>' . "\n";
}
$reg_data = ob_get_clean();
if (!empty($reg_data))
{
   echo '<table cellspacing="0" class="datalist" >' . "\n";
   echo '<tr>' . "\n";
   echo '<th style="padding-top:0;"></th>' . "\n";
   for ($i = 0; $i < count($table_header); $i++)
   {
      echo '<th style="padding-top:0; text-align:center;">' . $table_header[$i] . '</th>' . "\n";
   }
   echo '</tr>' . "\n";
   echo $reg_data;
   echo '</table>' . "\n";
}
pem_fieldset_end();

for ($i = 0; $i < $datecount; $i++)
{
   echo_data($datelist[$i], $regcount);
}

include ABSPATH . PEMINC . "/footer.php";


// =============================================================================
// =========================== LOCAL FUNCTIONS =================================
// =============================================================================

function echo_data($data, $count)
{
   global $date_format, $delete_confirm, $eid;
   extract($data);

   $title = (!empty($date_name)) ? $date_name . " - " : "";
   $title .= pem_date("l, " . $date_format, $when_begin);
   pem_fieldset_begin($title);
   echo '<div class="fscontrols">' . "\n";


//   if ($date_reg_require) $controls[] = array("label" => __("Add New Registration"), "onclick" => "action_submit('dataform" . $id . "', 'newdate');");
   if (!$date_reg_require) $controls[] = array("label" => __("Activate Date Registration"), "onclick" => "action_submit('dataform" . $id . "', 'activatedate');");
   else $controls[] = array("label" => __("Deactivate Date Registration"), "onclick" => "action_submit('dataform" . $id . "', 'deactivatedate');");
   $controls[] = array("label" => __("Edit Date"), "onclick" => "action_submit('dataform" . $id . "', 'editdate');");
   if ($date_reg_require) $controls[] = array("label" => __("View List Order or Add New"), "onclick" => "action_submit('dataform" . $id . "', 'viewdatelist');");
   pem_controls($controls);
   echo '</div>' . "\n";
   pem_form_begin(array("nameid" => "dataform" . $id, "action" => $PHP_SELF));
   pem_hidden_input(array("name" => "datasubmit", "value" => "edit"));
   pem_hidden_input(array("name" => "date_id", "value" => $id));
   pem_hidden_input(array("name" => "entry_id", "value" => $eid));
   pem_form_end();

   // Display current event reg information
   $reg_ended = (!empty($date_reg_begin) AND $date_reg_begin != "0000-00-00" AND pem_date("Y-m-d") > pem_date("Y-m-d", strtotime($date_reg_end))) ? true : false;
   if ($date_reg_current >= $date_reg_max AND !$date_allow_wait) $reg_ended = true;
   echo ($date_allow_wait) ? __("Waiting list sign-up is available after event is full.") : __("There is no waiting list for this event.");

   echo '<br />' . "\n";
   $seatsavail = $date_reg_max - $date_reg_current;
   $waitseats = $date_reg_current - $date_reg_max;
   if ($waitseats < 0) $waitseats = 0;

   if ($reg_ended)
   {
      $show_reg_button = false;
      echo __("Registration is now CLOSED.") . '<br />' . "\n";
      echo '<span class="important">' . $date_reg_current . '</span> ' . __("of") . ' ' . $date_reg_max . ' ' . __("seats taken.") . '<br />' . "\n";
      if ($waitseats > 1)
      {
         printf(__('There are %1$s people currently in the waiting list.'), $waitseats);
         echo  '<br />' . "\n";
      }
      elseif ($waitseats == 0)
      {
         echo __('The waiting list is currently empty.') . '<br />' . "\n";
      }
      else
      {
         printf(__('There is %1$s person currently in the waiting list.'), $waitseats);
         echo  '<br />' . "\n";
      }
      echo  '<br />' . "\n";

   }
   elseif (empty($date_reg_begin) OR $date_reg_begin == "0000-00-00" OR pem_date("Y-m-d", strtotime($date_reg_begin)) <= pem_date("Y-m-d"))
   {
      $show_reg_button = true;
      if (!$date_reg_max)
      {
         // echo '<span class="important">' . $date_reg_current . '</span> ' . __("seats available.") . '<br />' . "\n";;
      }
      else
      {
         if ($seatsavail > 0)
         {
            echo '<span class="important">' . $seatsavail . '</span> ' . __("of") . ' ' . $date_reg_max . ' ' . __("seats available.") . '<br />' . "\n";
            echo  '<span class="important">';
            printf(__('Registration is OPEN till %1$s.'), pem_date("l, " . $date_format, strtotime($date_reg_end)));
            echo '</span><br />' . "\n";
         }
         else
         {
            echo '<span class="important">0</span> ' . __("of") . ' ' . $date_reg_max . ' ' . __("seats available.") . '<br />' . "\n";
            echo '<span class="important">' . __("Waiting List Registration is now OPEN.") . '</span><br />' . "\n";
            if ($waitseats > 1)
            {
               printf(__('There are %1$s people currently in the waiting list.'), $waitseats);
               echo  '<br />' . "\n";
            }
            elseif ($waitseats == 0)
            {
               echo __('The waiting list is currently empty.') . '<br />' . "\n";
            }
            else
            {
               printf(__('There is %1$s person currently in the waiting list.'), $waitseats);
               echo  '<br />' . "\n";
            }
         }
      }
   }
   else
   {
      $show_reg_button = false;
      echo __("Registration available") . ' ';
      echo pem_simplify_dates($date_reg_begin, $date_reg_end);
      echo  '<br />' . "\n";
   }

   $table_header =  array(
           __("Phone"),
           __("Email"),
           __("Reminder"),
   );

   $where = array("date_id" => $id);
   $list = pem_get_rows("registrants", $where, "AND", "name2, name1");
   ob_start();
   for ($i = 0; $i < count($list); $i++)
   {
      unset($controls);
      $row = ($i % 2) ? "row2" : "row1";
      echo '<tr class="' . $row . '"><td>' . "\n";
      pem_form_begin(array("nameid" => "dateform" . $list[$i]["id"], "action" => $PHP_SELF));
      pem_hidden_input(array("name" => "datasubmit", "value" => "editreg"));
      pem_hidden_input(array("name" => "reg_id", "value" => $list[$i]["id"]));
      pem_hidden_input(array("name" => "entry_id", "value" => $eid));
      pem_hidden_input(array("name" => "date_id", "value" => $id));
      pem_field_label(array("default" => $list[$i]["name1"] . ' ' . $list[$i]["name2"]));
      pem_form_end();
      echo '</td><td>' . "\n";
      if (!empty($list[$i]["phone1"])) echo $list[$i]["phone1"];
      if (!empty($list[$i]["phone1"]) AND !empty($list[$i]["phone2"])) echo ', ';
      if (!empty($list[$i]["phone2"])) echo $list[$i]["phone2"];
      echo '</td><td>' . "\n";
      if (!empty($list[$i]["email"])) echo '<a href="mailto:' . $list[$i]["email"] . '">' . $list[$i]["email"] . '</a>';
      echo '</td><td>' . "\n";
      echo ($list[$i]["reminder"]) ? __("Yes") : __("No");
      echo '</td><td class="controlboxwide">' . "\n";
      if ($count > 1)
      {
         $controls[] = array("label" => __("Move"), "onclick" => "action_submit('dateform" . $list[$i]["id"] . "', 'movereg');");
         $controls[] = array("label" => __("Copy"), "onclick" => "action_submit('dateform" . $list[$i]["id"] . "', 'copyreg');");
      }
      $controls[] = array("label" => __("Edit"), "onclick" => "action_submit('dateform" . $list[$i]["id"] . "', 'editreg');");
      $controls[] = array("label" => __("Delete"), "onclick" => "confirm_submit('dateform" . $list[$i]["id"] . "', 'deletereg', '" . $delete_confirm . "');");
      pem_controls($controls);
      echo '</td></tr>' . "\n";
   }
   $reg_data = ob_get_clean();
   if (!empty($reg_data))
   {
      echo '<table cellspacing="0" class="datalist" style="margin-top:10px;">' . "\n";
      echo '<tr>' . "\n";
      echo '<th style="padding-top:0;"></th>' . "\n";
      for ($i = 0; $i < count($table_header); $i++)
      {
         echo '<th style="padding-top:0; text-align:center;">' . $table_header[$i] . '</th>' . "\n";
      }
      echo '</tr>' . "\n";
      echo $reg_data;
      echo '</table>' . "\n";
   }

   pem_fieldset_end();
} // END echo_data

function echo_form($id = "", $values = "", $mode = "", $error = "")
{
   global $time_format, $date_format, $reg_remind_when, $reg_contact_meta;
   global $default_phone, $default_email, $default_city, $default_state, $state_select;
   global $PHP_SELF, $eid;

   pem_error_list($error);

   pem_form_begin(array("nameid" => "submitform", "action" => $PHP_SELF, "class" => "regform"));
   pem_hidden_input(array("name" => "datasubmit", "value" => $mode));
   if (substr($mode, -5) == "entry")   pem_hidden_input(array("name" => "entry_id", "value" => $id));
   else pem_hidden_input(array("name" => "date_id", "value" => $id));
   pem_hidden_input(array("name" => "reg_id", "value" => $values["id"]));

   if (!isset($values["city"])) $values["city"] = $default_city;
   if (!isset($values["state"])) $values["state"] = $default_state;
   if (!isset($values["phone1"])) $values["phone1"] = $default_phone;
   if (!isset($values["phone2"])) $values["phone2"] = $default_phone;
   if (!isset($values["email"])) $values["email"] = $default_email;

   $size["name1"] = 40;
   $size["name2"] = 40;
   $size["street1"] = 40;
   $size["street2"] = 40;
   $size["city"] = 30;
   $size["state"] = 30;
   $size["postal"] = 10;
   $size["phone1"] = 14;
   $size["phone2"] = 14;
   $size["email"] = 40;

   $reminder_option = false;
   $data = pem_get_row("id", $reg_contact_meta, "meta");
   $meta = unserialize($data["value"]);
   $meta_keys = array_keys($meta);

   for ($i = 0; $i < count($meta_keys); $i++)
   {
      if (0 != $meta[$meta_keys[$i]][1] and $meta_keys[$i] != "website")
      {
         if ($meta_keys[$i] == "email") $reminder_option = true;
         if ($meta_keys[$i] == "state" AND $state_select)
         {
            $note = (!empty($meta[$meta_keys[$i]][2])) ? $meta[$meta_keys[$i]][2] : " ";
            pem_field_label(array("default" => $meta[$meta_keys[$i]][0], "for" => $meta_keys[$i], "noparse" => true));
            pem_echo_state_select(array("name" => $meta_keys[$i], "default" => $values[$meta_keys[$i]]));
            pem_field_note(array("default" => $note, "linebreak" => false, "style" => "float:left;"));
         }
         elseif ($meta_keys[$i] == "postal" AND $state_select)
         {
            pem_field_label(array("default" => $meta[$meta_keys[$i]][0], "for" => $meta_keys[$i], "noparse" => true, "style" => "margin-left:20px; width:auto;"));
            pem_text_input(array("name" => $meta_keys[$i], "value" => $values[$meta_keys[$i]], "size" => $size[$meta_keys[$i]], "maxlength" => 60));
            pem_field_note(array("default" => $meta[$meta_keys[$i]][2], "required" => $meta[$meta_keys[$i]][1] == 2));
         }
         else
         {
            pem_field_label(array("default" => $meta[$meta_keys[$i]][0], "for" => $meta_keys[$i], "noparse" => true));
            pem_text_input(array("name" => $meta_keys[$i], "value" => $values[$meta_keys[$i]], "size" => $size[$meta_keys[$i]], "maxlength" => 60));
            pem_field_note(array("default" => $meta[$meta_keys[$i]][2], "required" => $meta[$meta_keys[$i]][1] == 2));
         }
         if ($meta[$meta_keys[$i]][1] == 2) pem_hidden_input(array("name" => "required['" . $meta_keys[$i] . "']", "value" => $meta[$meta_keys[$i]][0]));
      }
   }
   if ($reminder_option)
   {
      pem_field_label(array("default" => __("Get Reminder Email:"), "for" => "reminder", "style" => "width:auto;"));
      pem_checkbox(array("nameid" => "reminder", "status" => $values["reminder"], "style" => "float:left;"));
      $remind_note = ($reg_remind_when > 1) ? sprintf(__("(Will receive an email %s days before the event.)"), $reg_remind_when) : __("(You'll receive an email the day before the event.)");
      pem_field_note(array("default" => $remind_note, "for" => "reminder"));
   }

   if (!empty($ret))
   {
      echo '<h3>' . __("Check boxes to also register for this event's other dates") . '</h3>' . "\n";
      echo $ret . "\n";
   }
   echo "<br/>\n";

   if ($mode == "newentry" OR $mode == "finishentry" OR $mode == "newdate" OR $mode == "finishdate") pem_form_submit("submitform", "cancel");
   else pem_form_update("submitform", "cancel");

   pem_form_end();
} // END echo_form

function echo_move_form($id, $date_id = "", $entry_id = "")
{
   global $PHP_SELF, $table_prefix, $date_format, $eid, $entry_name, $entry_reg_require;

   pem_form_begin(array("nameid" => "submitform", "action" => $PHP_SELF, "class" => "moveform"));
   pem_hidden_input(array("name" => "datasubmit", "value" => "finishmove"));
   pem_hidden_input(array("name" => "reg_id", "value" => $id));
   pem_hidden_input(array("name" => "entry_id", "value" => $entry_id));
   pem_hidden_input(array("name" => "date_id", "value" => $date_id));

   if (empty($date_id)) $date_id = 0;
   unset($options);
   // get all dates for this event
   $sql = "SELECT id, when_begin, when_end, date_name FROM " . $table_prefix . "dates WHERE ";
   $sql .= "entry_id = :entry_id AND ";
   $sql .= "id != :date_id AND ";
   $sql .= "date_reg_require = 1 AND ";
   $sql .= "date_status != 2";
   $sql_values = array("entry_id" => $eid, "date_id" => $date_id);
   $eventres = pem_exec_sql($sql, $sql_values);
   $list = $eventres;
   unset($eventres);

   if ($entry_reg_require AND !empty($date_id)) $options[__("Move from date to global event: ") . " " . $entry_name] = "entry";
   foreach ($list AS $this_date)
   {
      $title = (!empty($this_date["date_name"])) ? $this_date["date_name"] . " - " : "";
      $date_begin = pem_date("l, " . $date_format, $this_date["when_begin"]);
      $date_end = pem_date("l, " . $date_format, $this_date["when_end"]);
      if ($date_begin == $date_end) $title .= $date_begin;
      else $title .= pem_simplify_dates($date_begin, $date_end);
      $options[$title] = $this_date["id"];
   }
   pem_field_label(array("default" => __("New Date:"), "for" => "move_reg_to"));
   pem_select($options, array("nameid" => "move_reg_to"));
   echo "<br/>\n";

   pem_form_update("submitform", "cancel");
   pem_form_end();
} // END echo_move_form


function echo_copy_form($id, $date_id = "", $entry_id = "")
{
   global $PHP_SELF, $table_prefix, $date_format, $eid, $entry_name, $entry_reg_require;

   pem_form_begin(array("nameid" => "submitform", "action" => $PHP_SELF, "class" => "copyform"));
   pem_hidden_input(array("name" => "datasubmit", "value" => "finishcopy"));
   pem_hidden_input(array("name" => "reg_id", "value" => $id));
   pem_hidden_input(array("name" => "entry_id", "value" => $entry_id));
   pem_hidden_input(array("name" => "date_id", "value" => $date_id));

   if (empty($date_id)) $date_id = 0;
   unset($options);
   // get all dates for this event
   $sql = "SELECT id, when_begin, when_end, date_name FROM " . $table_prefix . "dates WHERE ";
   $sql .= "entry_id = :entry_id AND ";
   $sql .= "id != :date_id AND ";
   $sql .= "date_reg_require = 1 AND ";
   $sql .= "date_status != 2";
   $sql_values = array("entry_id" => $eid, "date_id" => $date_id);
   $eventres = pem_exec_sql($sql, $sql_values);
   $list = $eventres;
   unset($eventres);

   if ($entry_reg_require AND !empty($date_id))
   {
      $label = __("Copy to global event: ") . " " . $entry_name;
      pem_checkbox(array("name" => "regdates['entry']", "id" => "regdatesentry", "status" => $status, "style" => "float:left;"));
      pem_field_label(array("default" => $label, "for" => "regdatesentry", "style" => "width:auto;", "noparse" => true));
      echo "<br/>\n";
   }
   foreach ($list AS $this_date)
   {
      $label = (!empty($this_date["date_name"])) ? $this_date["date_name"] . " - " : "";
      $date_begin = pem_date("l, " . $date_format, $this_date["when_begin"]);
      $date_end = pem_date("l, " . $date_format, $this_date["when_end"]);
      if ($date_begin == $date_end) $label .= $date_begin;
      else $label .= pem_simplify_dates($date_begin, $date_end);
      pem_checkbox(array("name" => "regdates[" . $this_date["id"] . "]", "id" => "regdates" . $this_date["id"], "status" => false, "style" => "float:left;"));
      pem_field_label(array("default" => $label, "for" => "regdates" . $this_date["id"], "style" => "width:auto;", "noparse" => true));
      echo "<br/>\n";
   }
   pem_form_update("submitform", "cancel");
   pem_form_end();
} // END echo_copy_form

?>