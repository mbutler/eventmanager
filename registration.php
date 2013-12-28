<?php
/* ========================== FILE INFORMATION =================================
phxEventManager :: registration.php

Handles the user registration for an event that requires it
============================================================================= */

// only one of these should ever have data
$did = pem_cache_get("current_date");
$eid = pem_cache_get("current_event");

//echo "DATE ID: $did <br />";
//echo "EVENT ID: $eid <br />";

$pemdb =& mdb2_connect($dsn, $options, "connect");
if (!empty($did)) // collect the combined date+entry information
{
   $sql = "SELECT * FROM " . $table_prefix . "entries AS e, " . $table_prefix . "dates AS d WHERE ";
   $sql .= "e.id = d.entry_id AND ";
   $sql .= "d.id = :date_id AND ";
   $sql .= "e.entry_status != 2 AND ";
   $sql .= "d.date_status != 2";
   $sql_values = array("date_id" => $did);
   $eventres = pem_exec_sql($sql, $sql_values);
   $this_event = $eventres[0];
   unset($eventres);

   $entry_id = $this_event["entry_id"];
   $where = array("entry_id" => $entry_id);
   $this_event["registrants"] = pem_get_rows("registrants", $where);
   $dates_count = pem_get_count("dates", $where);
   $where = array("date_id" => $did);
   $this_event["date_registrants"] = pem_get_rows("registrants", $where);
   unset($where);

   $sql = "SELECT * FROM " . $table_prefix . "entries AS e, " . $table_prefix . "dates AS d WHERE ";
   $sql .= "e.id = d.entry_id AND ";
   $sql .= "d.entry_id = :entry_id AND ";
   $sql .= "e.entry_status != 2 AND ";
   $sql .= "d.date_status != 2";
   $sql_values = array("entry_id" => $entry_id);
   $datelist = pem_exec_sql($sql, $sql_values);
}

if (!empty($datelist)) // fill out the information with additional tables' data
{
   for ($i = 0; $i < count($datelist); $i++)
   {
      $spaces = unserialize($datelist[$i]["spaces"]);
      $spaces_text = "";
      $sql = "SELECT space_name FROM " . $table_prefix . "spaces WHERE id = :space_id";
      $spaces_count = count($spaces);
      for ($j = 0; $j < $spaces_count; $j++) // build spaces_text
      {
         $sql_values = array("space_id" => $spaces[$j]);
         $sql_prep = $pemdb->prepare($sql);
         if (PEAR::isError($sql_prep)) PEAR_error($sql_prep);
         $result = $sql_prep->execute($sql_values);
         if (PEAR::isError($result)) PEAR_error($result);
         $row = $result->fetchRow(MDB2_FETCHMODE_ASSOC);
         $spaces_text .= $row["space_name"];
         if ($j < $spaces_count - 1) $spaces_text .= ", ";
      }
      $datelist[$i]["spaces_text"] = $spaces_text;

      $where = array("date_id" => $datelist[$i]["id"]);
      $datelist[$i]["registrants"] = pem_get_rows("registrants", $where);
   }
}

$data = pem_get_row("id", $reg_contact_meta, "meta");
$meta = unserialize($data["value"]);
/*
$value_keys = array_keys($value);
for ($i = 0; $i < count($value_keys); $i++)
{
   $meta_value[$value_keys[$i]] = $value[$value_keys[$i]][0];
   $meta_value[$value_keys[$i] . "_behavior"] = $value[$value_keys[$i]][1];
   $meta_value[$value_keys[$i] . "_note"] = $value[$value_keys[$i]][2];
}
*/
mdb2_disconnect($pemdb);

$hasregs = ($this_event["date_reg_require"] == 1 OR $this_event["entry_reg_require"] == 1) ? true : false;
$hasdates = ($dates_count > 1) ? true : false;
if ($navigation == "event") include_once ABSPATH . PEMINC . "/nav-event.php";
echo '<div id="content">' . "\n";

switch (true)
{
   case (0 == $this_event["entry_type"]):
      $entry_type = "internal_scheduled";
      break;
   case (1 == $this_event["entry_type"]):
      $entry_type = "external_scheduled";
      break;
   case (2 == $this_event["entry_type"]):
      $entry_type = "internal_unscheduled";
      break;
   case (3 == $this_event["entry_type"]):
      $entry_type = "external_unscheduled";
      break;
}

// ========================= HANDLE FORM SUBMISSION ============================
$values = "";
if (isset($datasubmit))
{
   $reminder = (isset($reminder)) ? 1 : 0;
   unset($error);
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

   if (isset($datasubmit) AND isset($error))
   {
      $values["name1"] = $name1;
      $values["name2"] = $name2;
      $values["street1"] = $street1;
      $values["street2"] = $street2;
      $values["city"] = $city;
      $values["state"] = $state;
      $values["postal"] = $postal;
      $values["phone1"] = ($phone1 == $default_phone) ? "" : $phone1;
      $values["phone2"] = ($phone2 == $default_phone) ? "" : $phone2;
      $values["email"] = $email;
      $values["reminder"] = $reminder;
      $values["regdates"] = $regdates;
   }
   elseif (isset($datasubmit))
   {
      MDB2::loadFile("Date"); // load Date helper class
      $reg_stamp = MDB2_Date::mdbNow();
      $phone1 = ($phone1 == $default_phone) ? "" : $phone1;
      $phone2 = ($phone2 == $default_phone) ? "" : $phone2;
      if ($this_event["entry_reg_require"])
      {
         $data = array("entry_id" => $entry_id, "date_id" => "", "name1" => $name1, "name2" => $name2, "street1" => $street1, "street2" => $street2, "city" => $city, "state" => $state, "postal" => $postal, "phone1" => $phone1, "phone2" => $phone2, "email" => $email, "reminder" => $reminder, "reg_stamp" => $reg_stamp);
         $types = array("event_id" => "integer", "date_id" => "integer", "name_first" => "text", "name_last" => "text", "street" => "text", "street2" => "text", "city" => "text", "state" => "text", "postal" => "text", "phone" => "text", "phone2" => "text", "email" => "text", "reminder" => "boolean", "reg_stamp" => "timestamp");
         $newid = pem_add_row("registrants", $data, $types);
         $sql = "UPDATE " . $table_prefix . "entries SET entry_reg_current = entry_reg_current + 1 WHERE id = :entry_id";
         $sql_values = array("entry_id" => $entry_id);
         pem_exec_sql($sql, $sql_values);
         echo '<p><b>' . __("Registration added.") . '</b></p>' . "\n";
         $this_event["registrants"][] = $data;

         $reg_row = pem_get_row("id", $entry_id, "entries");
         if ($reg_row["entry_reg_current"] > $reg_row["entry_reg_max"]) pem_mail_onreg($did, $newid, "wait");
         else pem_mail_onreg($did, $newid, "reg");
      }
      elseif (!$hasdates)
      {
         $data = array("entry_id" => "", "date_id" => $did, "name1" => $name1, "name2" => $name2, "street1" => $street1, "street2" => $street2, "city" => $city, "state" => $state, "postal" => $postal, "phone1" => $phone1, "phone2" => $phone2, "email" => $email, "reminder" => $reminder, "reg_stamp" => $reg_stamp);
         $types = array("event_id" => "integer", "date_id" => "integer", "name_first" => "text", "name_last" => "text", "street" => "text", "street2" => "text", "city" => "text", "state" => "text", "postal" => "text", "phone" => "text", "phone2" => "text", "email" => "text", "reminder" => "boolean", "reg_stamp" => "timestamp");
         $newid = pem_add_row("registrants", $data, $types);
         $sql = "UPDATE " . $table_prefix . "dates SET date_reg_current = date_reg_current + 1 WHERE id = :date_id";
         $sql_values = array("date_id" => $did);
         pem_exec_sql($sql, $sql_values);
         echo '<p><b>' . __("Registration added.") . '</b></p>' . "\n";
         $this_event["date_registrants"][] = $data;

         $reg_row = pem_get_row("id", $did, "dates");
         if ($reg_row["entry_reg_current"] > $reg_row["entry_reg_max"]) pem_mail_onreg($did, $newid, "wait");
         else pem_mail_onreg($did, $newid, "reg");
      }
      else
      {
         for ($i = 0; $i < count($regdates); $i++)
         {
            $data = array("entry_id" => "", "date_id" => $regdates[$i], "name1" => $name1, "name2" => $name2, "street1" => $street1, "street2" => $street2, "city" => $city, "state" => $state, "postal" => $postal, "phone1" => $phone1, "phone2" => $phone2, "email" => $email, "reminder" => $reminder, "reg_stamp" => $reg_stamp);
            $types = array("event_id" => "integer", "date_id" => "integer", "name_first" => "text", "name_last" => "text", "street" => "text", "street2" => "text", "city" => "text", "state" => "text", "postal" => "text", "phone" => "text", "phone2" => "text", "email" => "text", "reminder" => "boolean", "reg_stamp" => "timestamp");
            $newid = pem_add_row("registrants", $data, $types);
            $sql = "UPDATE " . $table_prefix . "dates SET date_reg_current = date_reg_current + 1 WHERE id = :date_id";
            $sql_values = array("date_id" => $regdates[$i]);
            pem_exec_sql($sql, $sql_values);

            $reg_row = pem_get_row("id", $regdates[$i], "dates");
            if ($reg_row["entry_reg_current"] > $reg_row["entry_reg_max"]) pem_mail_onreg($regdates[$i], $newid, "wait");
            else pem_mail_onreg($regdates[$i], $newid, "reg");
         }
         if (count($regdates) > 1) echo '<p><b>' . __("Registrations added.") . '</b></p>' . "\n";
         else echo '<p><b>' . __("Registration added.") . '</b></p>' . "\n";
         $this_event["date_registrants"][] = $data;
         $this_event["date_reg_current"]++;
      }
   }
}

pem_registration($datelist, $this_event, $meta, $values, $error);

// =============================================================================
// =========================== LOCAL FUNCTIONS =================================
// =============================================================================

// returns a registration record
function pem_reg_line($reg)
{
   global $date_format, $time_format;

   $ret = '<b>' . $reg["name1"] . ' ' . $reg["name2"] . '</b>';
   if (!empty($reg["phone1"])) $ret .= ', ' . $reg["phone1"];
   if (!empty($reg["phone2"])) $ret .= ', ' . $reg["phone2"];
   if (!empty($reg["email"])) $ret .= ', <a href="mailto:' . $reg["email"] . '">' . $reg["email"] . '</a>';
   $ret .= "<br />\n";
   $line2 = $reg["street1"];
   if (!empty($reg["street2"])) $line2t .= ', ' . $reg["street2"];
   if (!empty($reg["city"])) $line2 .= ', ' . $reg["city"];
   if (!empty($reg["state"])) $line2 .= ', ' . $reg["state"];
   if (!empty($reg["postal"])) $line2 .= ' ' . $reg["postal"];
   if (!empty($line2)) $ret .= "$line2<br />\n";

   if (!empty($reg["reg_stamp"]) AND $reg["reg_stamp"] != "0000-00-00 00:00:00") $ret .= "Registered: " . pem_date("l, " . $date_format . ", " . $time_format, $reg["reg_stamp"]);
   if ($reg["reminder"]) $ret .= '<i>' . __("*Reminder before event.") . '</i>';
   $ret .= "<br />\n";
   return $ret;
}

// formats event data based on current field and writes line
function pem_registration($list, $this_event, $meta, $values = "", $errors = "")
{
   global $time_format, $date_format, $reg_remind_when1, $reg_remind_when2;
   global $default_phone, $default_email, $default_city, $default_state, $state_select;

   $full_name = (!empty($this_event["date_name"])) ? $this_event["entry_name"] . ': ' . $this_event["date_name"] : $this_event["entry_name"];
   pem_fieldset_begin(sprintf(__("New Registration for %s"), $full_name));

   if (pem_user_authorized(array("Registrations" => "Add")))
   {
      pem_error_list($errors);

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

      pem_form_begin(array("nameid" => "submitform", "action" => $PHP_SELF, "class" => "regform"));
      pem_hidden_input(array("name" => "datasubmit", "value" => 1));
      $reminder_option = false;
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
      if ($reminder_option AND ($reg_remind_when1 > 0 OR $reg_remind_when2 > 0))
      {
         pem_field_label(array("default" => __("Get Reminder Email:"), "for" => "reminder", "style" => "width:auto;"));
         pem_checkbox(array("nameid" => "reminder", "status" => $values["reminder"], "style" => "float:left;"));

         if ($reg_remind_when1 > 0 AND $reg_remind_when2 > 0)
         {
            $remind_note = sprintf(__("(You'll receive email %1\$s and %2\$s days before the event.)"), $reg_remind_when1, $reg_remind_when2);
         }
         elseif ($reg_remind_when1 > 0)
         {
            $remind_note = ($reg_remind_when1 > 1) ? sprintf(__("(You'll receive an email %s days before the event.)"), $reg_remind_when1) : __("(You'll receive an email the day before the event.)");
         }
         else
         {
            $remind_note = ($reg_remind_when2 > 1) ? sprintf(__("(You'll receive an email %s days before the event.)"), $reg_remind_when2) : __("(You'll receive an email the day before the event.)");
         }
         pem_field_note(array("default" => $remind_note, "for" => "reminder"));
      }

      echo '<h3>' . __("Register for this event") . '</h3>' . "\n";
      echo '<div class="dateitem" style="clear:both;">';

      $label = (!empty($this_event["date_name"])) ? $this_event["date_name"] . " - " : "";
      $date_begin = pem_date("l, " . $date_format, $this_event["when_begin"]);
      $date_end = pem_date("l, " . $date_format, $this_event["when_end"]);
      if ($date_begin == $date_end) $label .= $date_begin;
      else $label .= pem_simplify_dates($date_begin, $date_end);

      $label .= '<span style="font-weight:normal">';
      $seatsavail = $this_event["date_reg_max"] - $this_event["date_reg_current"];
      if ($seatsavail > 0)
      {
         $label .= ' (' . $seatsavail . ' ' . __("of") . ' ' . $this_event["date_reg_max"] . ' ' . __("seats available") . '.)';
      }
      else
      {
         $waitseats = $this_event["date_reg_current"] - $this_event["date_reg_max"];
         $label .= ' (0 ' . __("of") . ' ' . $this_event["date_reg_max"] . ' ' . __("seats available");
         if ($waitseats > 0)
         {
            $label .= ', ' . $waitseats . ' ' . __("in the waiting list");
         }
         $label .= '.)';
      }

      $label .= ' <a href="view.php?did=' . $this_event["id"] . '">' . __("[View Event]") . '</a></span>';

      $this_id = ($this_event["entry_reg_require"]) ? $this_event["entry_id"] : $this_event["id"];
      if (isset($values["regdates"])) $status = (in_array($this_id, $values["regdates"]));
      else $status = true;
      pem_checkbox(array("name" => "regdates['" . $this_id . "']", "id" => "regdates" . $this_id, "status" => $status, "style" => "float:left;"));
      pem_field_label(array("default" => $label, "for" => "regdates" . $this_id, "style" => "width:auto;", "noparse" => true));
      echo "<br/>\n";

      echo '</div>' . "\n";

      if (!empty($list) AND count($list) > 1)
      {
         echo '<h3>' . __("Check boxes to also register for this event's other dates") . '</h3>' . "\n";
         echo __("The following event dates may be new unique events associated with the event you selected or could be repeats of the same event held on additional dates.  Please be certain of the events for which you are registering.") . "\n";
         echo '<div class="dateitem" style="clear:both;">';
         foreach ($list AS $this_date)
         {

            $reg_ended = (!empty($this_event["date_reg_begin"]) AND $this_event["date_reg_begin"] != "0000-00-00" AND pem_date("Y-m-d") > pem_date("Y-m-d", strtotime($this_event["date_reg_end"]))) ? true : false;
            if ($this_event["date_reg_current"] >= $this_event["date_reg_max"] AND !$this_event["date_allow_wait"]) $reg_ended = true;

            $label = "";
            if ($this_date["id"] != $this_event["id"] AND $this_date["date_reg_require"])
            {
               if (!empty($this_date["date_name"])) $label .= $this_date["date_name"] . " - ";

               $date_begin = pem_date("l, " . $date_format, $this_date["when_begin"]);
               $date_end = pem_date("l, " . $date_format, $this_date["when_end"]);
               if ($date_begin == $date_end) $label .= $date_begin;
               else $label .= pem_simplify_dates($date_begin, $date_end);

               $label .= '<span style="font-weight:normal">';
               $seatsavail = $this_date["date_reg_max"] - $this_date["date_reg_current"];
               if ($reg_ended)
               {
                  $label .= ' (' . __("Registration is now CLOSED.") . ')' . "\n";
               }
               elseif ($seatsavail > 0)
               {
                  $label .= ' (' . $seatsavail . ' ' . __("of") . ' ' . $this_date["date_reg_max"] . ' ' . __("seats available") . '.)';
               }
               else
               {
                  $waitseats = $this_date["date_reg_current"] - $this_date["date_reg_max"];
                  $label .= ' (0 ' . __("of") . ' ' . $this_date["date_reg_max"] . ' ' . __("seats available");
                  if ($waitseats > 0)
                  {
                     $label .= ', ' . $waitseats . ' ' . __("in the waiting list");
                  }
                  $label .= '.)';
               }

               $label .= ' <a href="view.php?e=event&did=' . $this_date["id"] . '">' . __("[View Event]") . '</a></span>';

               $status = (isset($values["regdates"]) AND in_array($this_date["id"], $values["regdates"]));
               if (!$reg_ended) pem_checkbox(array("name" => "regdates['" . $this_date["id"] . "']", "id" => "regdates" . $this_date["id"], "status" => $status, "style" => "float:left;"));
               pem_field_label(array("default" => $label, "for" => "regdates" . $this_date["id"], "style" => "width:auto;", "noparse" => true));
               echo "<br/>\n";
            }
         }
         echo '</div>' . "\n";
      }

//      elseif ($date_reg_require)
//      {
//         $ret .= '<div class="dateitem" style="clear:both;">';
//         $status = (isset($values["regdates"]) AND in_array($id, $values["regdates"]));
//         if ($date_begin == $date_end) $label = $date_begin;
//         else $label = $date_begin . ' ' . __("to") . ' ' . $date_end;
//         $label .= ' <a href="view.php?did=' . $id . '">' . __("[View Event]") . '</a>';
//         ob_start();
//         pem_checkbox(array("name" => "regdates['$id']", "id" => "regdates$id", "status" => $status, "style" => "float:left;"));
//         pem_field_label(array("default" => $label, "for" => "regdates$id", "style" => "width:auto;", "noparse" => true));
//         $ret .= ob_get_clean();
//         $ret .= '</div>' . "\n";
//      }


      pem_form_submit("submitform");
      pem_form_end();
   }
   else
   {
      echo __("You are not authorized to add new registrations.") . "<br />\n";
   }
   pem_fieldset_end();

   if (pem_user_authorized(array("Registrations" => "View")))
   {
      $regfound = false;
      if (!empty($this_event["registrants"]))
      {
         $regfound = true;
         pem_fieldset_begin(__("Existing Registrations for this Event"));
         echo '<div style="float:right;">' . "\n";
         pem_button_link(__("Manage Registrations"), "pem-admin/manage-reg.php?did=" . $this_event["id"]);
         echo "</div><br />\n";

         // Display current event reg information
         $reg_ended = (!empty($this_event["entry_reg_begin"]) AND $this_event["entry_reg_begin"] != "0000-00-00" AND pem_date("Y-m-d") > pem_date("Y-m-d", strtotime($this_event["entry_reg_end"]))) ? true : false;
         if ($this_event["entry_reg_current"] >= $this_event["entry_reg_max"] AND !$this_event["entry_allow_wait"]) $reg_ended = true;
         echo ($this_event["entry_allow_wait"]) ? __("Waiting list sign-up is available after event is full.") : __("There is no waiting list for this event.");
         echo '<br />' . "\n";
         if ($reg_ended)
         {
            $show_reg_button = false;
            echo __("Registration is now CLOSED.") . '<br />' . "\n";
         }
         elseif (empty($this_event["entry_reg_begin"]) OR $this_event["entry_reg_begin"] == "0000-00-00" OR pem_date("Y-m-d", strtotime($this_event["entry_reg_begin"])) <= pem_date("Y-m-d"))
         {
            $show_reg_button = true;
            if (!$this_event["entry_reg_max"])
            {
               // echo '<span class="important">' . $this_event["entry_reg_current"] . '</span> ' . __("seats available.") . '<br />' . "\n";;
            }
            else
            {
               $seatsavail = $this_event["entry_reg_max"] - $this_event["entry_reg_current"];
               if ($seatsavail > 0)
               {
                  echo '<span class="important">' . $seatsavail . '</span> ' . __("of") . ' ' . $this_event["entry_reg_max"] . ' ' . __("seats available.") . '<br />' . "\n";
                  ;
                  echo  '<span class="important">' . __("Registration is now OPEN.") . '</span><br />' . "\n";
               }
               else
               {
                  $waitseats = $this_event["entry_reg_current"] - $this_event["entry_reg_max"];
                  echo '<span class="important">0</span> ' . __("of") . ' ' . $this_event["entry_reg_max"] . ' ' . __("seats available.") . '<br />' . "\n";
                  ;
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
            echo pem_simplify_dates($this_event["entry_reg_begin"], $this_event["entry_reg_end"]);
            echo  '<br />' . "\n";
         }

         echo '<ol class="numbers" style="margin-top:10px;">' . "\n";
         for ($i = 0; $i < count($this_event["registrants"]); $i++)
         {
            if ($i == $this_event["date_reg_max"]) echo '</ol><h3>' . __("Waiting List Begins Here:") . '</h3><ol class="numbers">' . "\n";
            echo '<li class="reg">';
            echo pem_reg_line($this_event["registrants"][$i]);
            echo '</li>' . "\n";
         }
         echo '</ol>' . "\n";
         pem_fieldset_end();
      }
      if (!empty($this_event["date_registrants"]))
      {
         $regfound = true;
         pem_fieldset_begin(__("Existing Registrations for this Date"));
         echo '<div style="float:right;">' . "\n";
         pem_button_link(__("Manage Registrations"), "pem-admin/manage-reg.php?did=" . $this_event["id"]);
         echo "</div><br />\n";

         // Display current event reg information
         $reg_ended = (!empty($this_event["date_reg_begin"]) AND $this_event["date_reg_begin"] != "0000-00-00" AND pem_date("Y-m-d") > pem_date("Y-m-d", strtotime($this_event["date_reg_end"]))) ? true : false;
         if ($this_event["date_reg_current"] >= $this_event["date_reg_max"] AND !$this_event["date_allow_wait"]) $reg_ended = true;
         echo ($this_event["date_allow_wait"]) ? __("Waiting list sign-up is available after event is full.") : __("There is no waiting list for this event.");
         echo '<br />' . "\n";
         if ($reg_ended)
         {
            $show_reg_button = false;
            echo __("Registration is now CLOSED.") . '<br />' . "\n";
         }
         elseif (empty($this_event["date_reg_begin"]) OR $this_event["date_reg_begin"] == "0000-00-00" OR pem_date("Y-m-d", strtotime($this_event["date_reg_begin"])) <= pem_date("Y-m-d"))
         {
            $show_reg_button = true;
            if (!$this_event["date_reg_max"])
            {
               // echo '<span class="important">' . $this_event["date_reg_current"] . '</span> ' . __("seats available.") . '<br />' . "\n";;
            }
            else
            {
               $seatsavail = $this_event["date_reg_max"] - $this_event["date_reg_current"];
               if ($seatsavail > 0)
               {
                  echo '<span class="important">' . $seatsavail . '</span> ' . __("of") . ' ' . $this_event["date_reg_max"] . ' ' . __("seats available.") . '<br />' . "\n";
                  ;
                  echo  '<span class="important">';
                  printf(__('Registration is OPEN till %1$s.'), pem_date("l, " . $date_format, strtotime($this_event["date_reg_end"])));
                  echo '</span><br />' . "\n";
               }
               else
               {
                  $waitseats = $this_event["date_reg_current"] - $this_event["date_reg_max"];
                  echo '<span class="important">0</span> ' . __("of") . ' ' . $this_event["date_reg_max"] . ' ' . __("seats available.") . '<br />' . "\n";
                  ;
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
            echo pem_simplify_dates($this_event["date_reg_begin"], $this_event["date_reg_end"]);
            echo  '<br />' . "\n";
         }
         echo '<ol class="numbers" style="margin-top:10px;">' . "\n";
         for ($i = 0; $i < count($this_event["date_registrants"]); $i++)
         {
            if ($i == $this_event["date_reg_max"]) echo '</ol><h3>' . __("Waiting List Begins Here:") . '</h3><ol class="numbers">' . "\n";
            echo '<li class="reg">';
            echo pem_reg_line($this_event["date_registrants"][$i]);
            echo '</li>' . "\n";
         }
         echo '</ol>' . "\n";
         pem_fieldset_end();
      }
      if (!$regfound) echo __("Currently no registrations for this event.");
   }
}

?>