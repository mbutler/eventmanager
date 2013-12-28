<?php
/* ========================== FILE INFORMATION =================================
phxEventManager :: event.php

============================================================================= */

// only one of these should ever have data
$did = pem_cache_get("current_date");
$eid = pem_cache_get("current_event");

// establish current user
$login = pem_get_login();
if (!empty($login)) $this_user = auth_get_user($login);
else $this_user = auth_get_user("public");

$pemdb =& mdb2_connect($dsn, $options, "connect");
if (!empty($did)) // collect the combined date+entry information
{
   $where = array("id" => $did);
   $where["date_status"] = array("!=", "2");
   $date_exists = pem_get_count("dates", $where);
// echo "date exists check: $date_exists <br />";

   if (!$date_exists)
   {
      $sql = "SELECT entry_name, entry_description FROM " . $table_prefix . "entries WHERE ";
      $sql .= "id = :entry_id AND ";
      $sql .= "entry_status != 2";
      $sql_values = array("entry_id" => $eid);
      $eventret = pem_exec_sql($sql, $sql_values);
      $this_event = $eventret[0];
      unset($eventret);

      $where = array("entry_id" => $eid);
      $where["date_status"] = array("!=", "2");
      $dates_count = pem_get_count("dates", $where);

      $entry_id = $eid;
   }
   else
   {
      $sql = "SELECT * FROM " . $table_prefix . "entries as e, " . $table_prefix . "dates as d WHERE ";
      $sql .= "e.id = d.entry_id AND ";
      $sql .= "d.id = :date_id AND ";
      $sql .= "e.entry_status != 2 AND ";
      $sql .= "d.date_status != 2";
      $sql_values = array("date_id" => $did);
      $eventret = pem_exec_sql($sql, $sql_values);
      $this_event = $eventret[0];
      unset($eventret);

      $where = array("entry_id" => $this_event["entry_id"]);
      $where["date_status"] = array("!=", "2");
      $dates_count = pem_get_count("dates", $where);

      $entry_id = $this_event["entry_id"];
      $date_id = $entry_id ;
      $date_exists = true;
   }
}
elseif (isset($eid)) // combine entry information with first of corresponding dates
{
   $sql = "SELECT * FROM " . $table_prefix . "entries as e, " . $table_prefix . "dates as d WHERE ";
   $sql .= "e.id = d.entry_id AND ";
   $sql .= "d.entry_id = :entry_id AND ";
   $sql .= "e.entry_status != 2 AND ";
   $sql .= "d.date_status != 2";
   $sql_values = array("entry_id" => $eid);
   $eventret = pem_exec_sql($sql, $sql_values);
   $this_event = $eventret[0];
   unset($eventret);

   $where = array("entry_id" => $this_event["entry_id"]);
   $where["date_status"] = array("!=", "2");
   $dates_count = pem_get_count("dates", $where);

   $entry_id = $eid;
}

pem_cache_set("current_event", $entry_id);

if (!empty($this_event) AND $date_exists) // fill out the information with additional tables' data
{
   $spaces = unserialize($this_event["spaces"]);
   $spaces_text = "";
   $sql = "SELECT space_name FROM " . $table_prefix . "spaces WHERE id = :space_id";
   $spaces_count = count($spaces);
   for ($i = 0; $i < $spaces_count; $i++) // build spaces_text
   {
      $sql_values = array("space_id" => $spaces[$i]);
      $sql_prep = $pemdb->prepare($sql);
      if (PEAR::isError($sql_prep)) PEAR_error($sql_prep);
      $result = $sql_prep->execute($sql_values);
      if (PEAR::isError($result)) PEAR_error($result);
      $row = $result->fetchRow(MDB2_FETCHMODE_ASSOC);
      $space_names[$spaces[$i]] = $row["space_name"];
      $spaces_text .= $row["space_name"];
      if ($i < $spaces_count - 1) $spaces_text .= ", ";
   }
   $this_event["spaces_text"] = $spaces_text;

   $supplies = unserialize($this_event["supplies"]);
   $sql = "SELECT supply_name FROM " . $table_prefix . "supplies WHERE id = :supply_id";

   if (is_array($supplies)) foreach ($supplies AS $supspace_id => $supspace_supplies)
      {
         $supspace_supply_keys = array_keys($supspace_supplies);
         $supplies_count = count($supspace_supply_keys);
         $supplies_text = "";
         for ($i = 0; $i < $supplies_count; $i++) // build spaces_text
         {
            $sql_values = array("supply_id" => $supspace_supply_keys[$i]);
            $sql_prep = $pemdb->prepare($sql);
            if (PEAR::isError($sql_prep)) PEAR_error($sql_prep);
            $result = $sql_prep->execute($sql_values);
            if (PEAR::isError($result)) PEAR_error($result);
            $row = $result->fetchRow(MDB2_FETCHMODE_ASSOC);
            if (!empty($supspace_supplies[$supspace_supply_keys[$i]]))
            {
               $supplies_text .=  $row["supply_name"] . ' (' . $supspace_supplies[$supspace_supply_keys[$i]] . ')';
               if ($i < $supplies_count - 1) $supplies_text .= ", ";
            }
         }
         if (!empty($space_names[$supspace_id]) AND !empty($supplies_text))
         {
            if (substr($supplies_text, -2) == ", ")
            {
               $this_event["supplies_text"][] = '[' . $space_names[$supspace_id] . '] ' . substr($supplies_text, 0, -2);
            }
            else
            {
               $this_event["supplies_text"][] = '[' . $space_names[$supspace_id] . '] ' . $supplies_text;
            }
         }
      }

   $sql = "SELECT presenter_type FROM " . $table_prefix . "presenters WHERE id = :id";
   if (!empty($this_event["entry_presenter"]))
   {
      $sql_values = array("id" => $this_event["entry_presenter_type"]);
      $sql_prep = $pemdb->prepare($sql);
      if (PEAR::isError($sql_prep)) PEAR_error($sql_prep);
      $result = $sql_prep->execute($sql_values);
      if (PEAR::isError($result)) PEAR_error($result);
      $row = $result->fetchRow(MDB2_FETCHMODE_ASSOC);
      $this_event["entry_presenter_text"] = $row["presenter_type"];
   }
   if (!empty($this_event["date_presenter"]))
   {
      $sql_values = array("id" => $this_event["date_presenter_type"]);
      $sql_prep = $pemdb->prepare($sql);
      if (PEAR::isError($sql_prep)) PEAR_error($sql_prep);
      $result = $sql_prep->execute($sql_values);
      if (PEAR::isError($result)) PEAR_error($result);
      $row = $result->fetchRow(MDB2_FETCHMODE_ASSOC);
      $this_event["date_presenter_text"] = $row["presenter_type"];
   }

   $sql = "SELECT category_name FROM " . $table_prefix . "categories WHERE id = :id";
   if (!empty($this_event["entry_category"]))
   {
      $sql_values = array("id" => $this_event["entry_category"]);
      $sql_prep = $pemdb->prepare($sql);
      if (PEAR::isError($sql_prep)) PEAR_error($sql_prep);
      $result = $sql_prep->execute($sql_values);
      if (PEAR::isError($result)) PEAR_error($result);
      $row = $result->fetchRow(MDB2_FETCHMODE_ASSOC);
      $this_event["entry_category_text"] = $row["category_name"];
   }
   if (!empty($this_event["date_category"]))
   {
      $sql_values = array("id" => $this_event["date_category"]);
      $sql_prep = $pemdb->prepare($sql);
      if (PEAR::isError($sql_prep)) PEAR_error($sql_prep);
      $result = $sql_prep->execute($sql_values);
      if (PEAR::isError($result)) PEAR_error($result);
      $row = $result->fetchRow(MDB2_FETCHMODE_ASSOC);
      $this_event["date_category_text"] = $row["category_name"];
   }

   $entry_meta = (!empty($this_event["entry_meta"])) ? unserialize($this_event["entry_meta"]) : "";
   $date_meta = (!empty($this_event["date_meta"])) ? unserialize($this_event["date_meta"]) : "";

   if (is_array($entry_meta) AND is_array($date_meta)) $meta_data = $entry_meta + $date_meta;
   elseif (is_array($entry_meta)) $meta_data = $entry_meta;
   elseif (is_array($date_meta)) $meta_data = $date_meta;

   $hasregs = ($this_event["date_reg_require"] == 1 OR $this_event["entry_reg_require"] == 1) ? true : false;
   $hasdates = ($dates_count > 1) ? true : false;
   if ($navigation == "event") include_once ABSPATH . PEMINC . "/nav-event.php";
   echo '<div id="content">' . "\n";

   echo '<p><b>' . $message . '</b></p>' . "\n";


   if (1 == 3)
   {
      $pemdb =& mdb2_connect($dsn, $options, "connect");
      $sql = "SELECT e.entry_meta, d.date_meta, d.date_created_by FROM " . $table_prefix . "entries as e, " . $table_prefix . "dates as d WHERE ";
      $sql .= "e.id = d.entry_id AND ";
      $sql .= "d.id = :date_id AND ";
      $sql .= "e.entry_status != 2 AND ";
      $sql .= "d.date_status != 2";
      $sql_values = array("date_id" => $did);
      $eventret = pem_exec_sql($sql, $sql_values);
      $event_contacts = $eventret[0];
      unset($eventret);
      mdb2_disconnect($pemdb);

// TODO need to make the contact meta options dynamic for email usage here

      $mail_edited = pem_mail_edited($did);
      pem_fieldset_begin(__("Send Edit Notification Mail"));

      pem_form_begin(array("nameid" => "submitform", "action" => $PHP_SELF, "class" => "neweventform"));
      pem_hidden_input(array("name" => "datasubmit", "value" => "edit-email"));
      pem_hidden_input(array("nameid" => "subject", "value" => $mail_edited["subject"]));
      pem_hidden_input(array("nameid" => "from_address", "value" => $mail_edited["from_address"]));
      pem_hidden_input(array("nameid" => "from_name", "value" => $mail_edited["from_name"]));
      pem_hidden_input(array("nameid" => "format", "value" => $mail_edited["format"]));

      echo '<p>' . __("The default email content is in the field below; it can be edited to customize the message.  If you wish to send an email confirming approval of this event, select the recipient(s) and submit the form.") . '<p>' . "\n";

      pem_textarea_input(array("nameid" => "body", "default" => $mail_edited["body"], "style" => "width:450px; height:200px;"));
      echo '<br />' . "\n";
      echo '<div style="margin:10px;>"' . "\n";

      $date_created_by = auth_get_user($event_contacts["date_created_by"]);
      if ($date_created_by["id"] != 2)
      {
         pem_checkbox(array("nameid" => "email_created", "status" => false, "style" => "float:left;"));
         pem_field_label(array("default" => __("Event Creator"), "for" => "email_created"));
         pem_field_note(array("default" => '(' . $date_created_by["user_nicename"] . ' - ' . $date_created_by["user_email"] . ')'));
         pem_hidden_input(array("nameid" => "email_created_address", "value" => $date_created_by["user_email"]));
      }
      $entry_meta = unserialize($event_contacts["entry_meta"]);
      if (isset($entry_meta[2]) AND !empty($entry_meta[2]["email"]))
      {
         pem_checkbox(array("nameid" => "email_contact1", "status" => true, "style" => "float:left;"));
         pem_field_label(array("default" => __("First Contact"), "for" => "email_contact1"));
         pem_field_note(array("default" => '(' . $entry_meta[2]["name1"] . ' - ' . $entry_meta[2]["email"] . ')'));
         pem_hidden_input(array("nameid" => "email_contact1_address", "value" => $entry_meta[2]["email"]));
      }
      if (isset($entry_meta[1]) AND !empty($entry_meta[1]["email"]))
      {
         $status = (!isset($entry_meta[2]) OR empty($entry_meta[2]["email"])) ? true : false;
         pem_checkbox(array("nameid" => "email_contact2", "status" => $status, "style" => "float:left;"));
         pem_field_label(array("default" => __("Second Contact"), "for" => "email_contact2"));
         pem_field_note(array("default" => '(' . $entry_meta[1]["name1"] . ' - ' . $entry_meta[1]["email"] . ')'));
         pem_hidden_input(array("nameid" => "email_contact2_address", "value" => $entry_meta[1]["email"]));
      }
      if (isset($entry_meta[3]) AND !empty($entry_meta[3]["email"]))
      {
         pem_checkbox(array("nameid" => "email_contact1", "status" => true, "style" => "float:left;"));
         pem_field_label(array("default" => __("First Contact"), "for" => "email_contact1"));
         pem_field_note(array("default" => '(' . $entry_meta[3]["name1"] . ' - ' . $entry_meta[3]["email"] . ')'));
         pem_hidden_input(array("nameid" => "email_contact1_address", "value" => $entry_meta[3]["email"]));
      }
      if (isset($entry_meta[4]) AND !empty($entry_meta[4]["email"]))
      {
         $status = (!isset($entry_meta[2]) OR empty($entry_meta[3]["email"])) ? true : false;
         pem_checkbox(array("nameid" => "email_contact2", "status" => $status, "style" => "float:left;"));
         pem_field_label(array("default" => __("Second Contact"), "for" => "email_contact2"));
         pem_field_note(array("default" => '(' . $entry_meta[4]["name1"] . ' - ' . $entry_meta[4]["email"] . ')'));
         pem_hidden_input(array("nameid" => "email_contact2_address", "value" => $entry_meta[4]["email"]));
      }
      echo '</div>' . "\n";
      pem_form_submit("submitform", __("Send Approval Email"));
      pem_form_end();
      pem_fieldset_end();
   }

   switch (true)
   {
      case (1 == $this_event["entry_type"]):
         $entry_type = "internal_scheduled";
         $access_type = "Internal Calendar";
         break;
      case (2 == $this_event["entry_type"]):
         $entry_type = "external_scheduled";
         $access_type = "External Calendar";
         break;
      case (3 == $this_event["entry_type"]):
         $entry_type = "internal_unscheduled";
         $access_type = "Internal Side Box";
         break;
      case (4 == $this_event["entry_type"]):
         $entry_type = "external_unscheduled";
         $access_type = "External Side Box";
         break;
   }

//get list of active fields
   $fieldbehavior = pem_active_fields($entry_type);
//order the fields
   $fieldslist = pem_order_fields($fieldbehavior, $entry_type);

}
else
{
   $hasregs = ($this_event["entry_reg_require"] == 1) ? true : false;
   $hasdates = ($dates_count > 1) ? true : false;
   if ($navigation == "event") include_once ABSPATH . PEMINC . "/nav-event.php";
   echo '<div id="content">' . "\n";

   if ($hasdates) echo __("You are currently viewing an invalid date for this event.  Click the Other Dates tab to browse a good date for this event.");
   else echo __("You are currently viewing an event without a valid date.  Click the Add Date tab to add a date to this event.");
}

mdb2_disconnect($pemdb);

$do_action = pem_cache_get("current_action");
if (!empty($do_action) AND $do_action == "converteventmsg")
{
   echo '<p class="actionmsg">' . __("Date converted to new independent event.") . '</p>' . "\n";
   pem_cache_flush("current_action");
}
elseif (!empty($do_action) AND $do_action == "convertdatemsg")
{
   echo '<p class="actionmsg">' . __("Event converted to date and merged.") . '</p>' . "\n";
   pem_cache_flush("current_action");
}
if ($this_event["entry_cancelled"] OR $this_event["date_cancelled"])
{
   echo '<p class="cancelledmsg">' . __("This event has been cancelled.") . '</p>' . "\n";
}

if (!$date_exists)
{
   pem_fieldset_begin($this_event["entry_name"]);
   pem_event_line(1, $this_event["entry_description"], __("Description:"), true);
}
else
{
   // Loop the list to display event information in order
   for ($i = 0; $i < count($fieldslist); $i++)
   {
      pem_event_data($i, $this_event, $fieldslist[$i]);
   }

   if (isset($this_event["supplies_text"]))
   {
      echo '<span class="viewlabel">' . __("Optional Supplies:") . '</span> ';
      if (count($this_event["supplies_text"]) > 1) echo '<br />' . "\n";
      foreach ($this_event["supplies_text"] AS $opt_supplies_text)
      {
         echo $opt_supplies_text . '<br />' . "\n";
      }
   }

   if ($this_user["user_login"] != "public")
   {
      echo '<div style="margin-top:10px;">' . "\n";
      $entry_edited_by = auth_get_user($this_event["entry_edited_by"]);
      $entry_created_by = auth_get_user($this_event["entry_created_by"]);
      $entry_approved_by = auth_get_user($this_event["entry_approved_by"]);
      if ($this_event["entry_created_stamp"] == $this_event["date_created_stamp"])
      {
         $entry_created_by = auth_get_user($this_event["entry_created_by"]);
         timestamp_line(__("Created"), $entry_created_by["user_nicename"], $this_event["entry_created_stamp"]);
      }
      else
      {
         $entry_created_by = auth_get_user($this_event["entry_created_by"]);
         $date_created_by = auth_get_user($this_event["date_created_by"]);
         timestamp_line(__("Created"), $entry_created_by["user_nicename"], $this_event["entry_created_stamp"], __("Event"));
         timestamp_line(__("Created"), $date_created_by["user_nicename"], $this_event["date_created_stamp"], __("Date"));
      }
      if ($this_event["entry_approved_stamp"] == $this_event["date_approved_stamp"])
      {
         $entry_approved_by = auth_get_user($this_event["entry_approved_by"]);
         timestamp_line(__("Approved"), $entry_approved_by["user_nicename"], $this_event["entry_approved_stamp"]);
      }
      else
      {
         $entry_approved_by = auth_get_user($this_event["entry_approved_by"]);
         $date_approved_by = auth_get_user($this_event["date_approved_by"]);
         timestamp_line(__("Approved"), $entry_approved_by["user_nicename"], $this_event["entry_approved_stamp"], __("Event"));
         timestamp_line(__("Approved"), $date_approved_by["user_nicename"], $this_event["date_approved_stamp"], __("Date"));
      }
      if ($this_event["entry_edited_stamp"] == $this_event["date_edited_stamp"])
      {
         $entry_edited_by = auth_get_user($this_event["entry_edited_by"]);
         timestamp_line(__("Edited"), $entry_edited_by["user_nicename"], $this_event["entry_edited_stamp"]);
      }
      else
      {
         $entry_edited_by = auth_get_user($this_event["entry_edited_by"]);
         $date_edited_by = auth_get_user($this_event["date_edited_by"]);
         timestamp_line(__("Edited"), $entry_edited_by["user_nicename"], $this_event["entry_edited_stamp"], __("Event"));
         timestamp_line(__("Edited"), $date_edited_by["user_nicename"], $this_event["date_edited_stamp"], __("Date"));
      }
      echo '</div>' . "\n";
   }
}

if ($date_exists)
{
   $date_id = $did;
}
else
{
   $date_id = 0;
   pem_cache_flush("current_date");
}

pem_form_begin(array("nameid" => "dataform", "action" => $PHP_SELF));
pem_hidden_input(array("name" => "datasubmit", "value" => "edit"));
pem_hidden_input(array("name" => "did", "value" => $date_id));
pem_hidden_input(array("name" => "eid", "value" => $entry_id));
pem_form_end();


unset($controls);
unset($date_controls);
if (pem_user_authorized(array($access_type => "Approve All")) OR
        (pem_user_authorized(array($access_type => "Approve Own")) AND $this_user["id"] == $this_event["entry_created_by"]) OR
        (pem_user_authorized(array($access_type => "Approve Others")) AND $this_user["id"] != $this_event["entry_created_by"]))
{
   if ($this_event["entry_cancelled"] == "0" AND $this_event["date_cancelled"] == "0") $controls[] = array("label" => __("Cancel Event"), "onclick" => "confirm_submit('dataform', 'cancel_event', '" . $cancel_confirm_event . "');");
   elseif ($this_event["entry_cancelled"] == "0") $controls[] = array("label" => __("Cancel Event"), "onclick" => "confirm_submit('dataform', 'cancel_entry', '" . $cancel_confirm_event . "');");
   else $controls[] = array("label" => __("Uncancel Event"), "onclick" => "action_submit('dataform', 'uncancel_event');");
   if ($date_exists)
   {
      if ($this_event["date_cancelled"] == "0") $date_controls[] = array("label" => __("Cancel Date"), "onclick" => "action_submit('dataform', 'cancel_date');");
      else $date_controls[] = array("label" => __("Uncancel Date"), "onclick" => "action_submit('dataform', 'uncancel_date');");
   }
//   if ($this_event["entry_status"] == "0" AND $this_event["date_status"] == "0") $controls[] = array("label" => __("Activate Event"), "onclick" => "action_submit('dataform', 'activate_event');");
//   elseif ($this_event["entry_status"] == "0") $controls[] = array("label" => __("Activate Event"), "onclick" => "action_submit('dataform', 'activate_entry');");
//   else $controls[] = array("label" => __("Deactivate Event"), "onclick" => "action_submit('dataform', 'deactivate_event');");
   if ($this_event["entry_status"] == "0" OR $this_event["date_status"] == "0") $controls[] = array("label" => __("Activate Event"), "onclick" => "action_submit('dataform', 'activate_event');");
   else $controls[] = array("label" => __("Deactivate Event"), "onclick" => "action_submit('dataform', 'deactivate_event');");
   if ($date_exists)
   {
//      if ($this_event["date_status"] == "0") $date_controls[] = array("label" => __("Activate Date"), "onclick" => "action_submit('dataform', 'activate_date');");
//      else $date_controls[] = array("label" => __("Deactivate Date"), "onclick" => "action_submit('dataform', 'deactivate_date');");
      if ($this_event["date_status"] == "1") $date_controls[] = array("label" => __("Deactivate Date"), "onclick" => "action_submit('dataform', 'deactivate_date');");
   }
}
if (pem_user_authorized(array($access_type => "Delete All")) OR
        (pem_user_authorized(array($access_type => "Delete Own")) AND $this_user["id"] == $this_event["entry_created_by"]) OR
        (pem_user_authorized(array($access_type => "Delete Others")) AND $this_user["id"] != $this_event["entry_created_by"]))
{
   $controls[] = array("label" => __("Delete Event"), "onclick" => "confirm_submit('dataform', 'delete_event', '" . $delete_confirm_event . "');");
   if ($date_exists)
   {
      $date_controls[] = array("label" => __("Delete Date"), "onclick" => "confirm_submit('dataform', 'delete_date', '" . $delete_confirm_date . "');");
   }
}

if (isset($controls) OR isset($date_controls)) echo '<div style="margin:10px 0; clear:both;">' . "\n";
if (isset($controls))
{
   pem_controls($controls, true);
   echo '<br />' . "\n";
}
if (isset($date_controls))
{
   pem_controls($date_controls, true);
   echo '<br />' . "\n";
}
if (isset($controls) OR isset($date_controls)) echo '</div>' . "\n";



unset($controls);
unset($date_controls);
// TODO prevent anonymous from adding complex dates
if (!pem_user_anonymous() AND pem_user_authorized(array($access_type => "Add")))
{
   $controls[] = array("label" => __("Copy to New Event"), "onclick" => "action_submit('dataform', 'copy_event');");
   if ($date_exists)
   {
      $date_controls[] = array("label" => __("Copy to Additional Date"), "onclick" => "action_submit('dataform', 'copy_date');");
   }
}
if (pem_user_authorized(array($access_type => "Edit All")) OR
        (pem_user_authorized(array($access_type => "Edit Own")) AND $this_user["id"] == $this_event["entry_created_by"]) OR
        (pem_user_authorized(array($access_type => "Edit Others")) AND $this_user["id"] != $this_event["entry_created_by"]))
{
   if ($hasdates)
   {
      $controls[] = array("label" => __("Convert Date to New Independent Event"), "onclick" => "action_submit('dataform', 'convert_to_event');");
      $date_controls[] = array("label" => __("Convert Event to Dates in Other Event"), "onclick" => "confirm_submit('dataform', 'convert_to_date', '" . $convert_confirm_date . "');");
   }
   elseif ($date_exists)
   {
      $date_controls[] = array("label" => __("Convert Event to a Date in Other Event"), "onclick" => "confirm_submit('dataform', 'convert_to_date', '" . $convert_confirm_date . "');");
   }
}

if (isset($controls) OR isset($date_controls)) echo '<div style="margin:10px 0; clear:both;">' . "\n";
if (isset($controls))
{
   pem_controls($controls, true);
   echo '<br />' . "\n";
}
if (isset($date_controls))
{
   pem_controls($date_controls, true);
   echo '<br />' . "\n";
}
if (isset($controls) OR isset($date_controls)) echo '</div>' . "\n";


// if (pem_user_authorized("Admin"))
// if (pem_user_authorized(array("Internal Calendar" => "Edit", "External Calendar" => "Edit")))
// if (pem_user_authorized(array($access_type => "Edit")))
if (pem_user_authorized(array($access_type => "Edit All")) OR pem_user_authorized(array($access_type => "Edit Others")) OR pem_user_authorized(array($access_type => "Edit Own")))
{
   echo '<br />' . "\n";
   echo '<p style="margin-top:20px;"><i>Ticket Reference IDs: date_id = ' . $date_id . ', entry_id = ' . $entry_id . '</i></p><br />';
}

pem_fieldset_end();

// =============================================================================
// =========================== LOCAL FUNCTIONS =================================
// =============================================================================

// writes out oneline of event data
// first position lines are used as the fieldset legend
function pem_event_line($position, $data, $label = "", $spacing = false, $lineend = "")
{
// echo "$position, $data, $label<br />";

   if (!empty($data))
   {
      if ($position == 0) pem_fieldset_begin($data);
      else
      {
//        if ($spacing) echo '<div style="margin:10px 0;">' . "\n";
         if (!empty($label)) echo '<span class="viewlabel">' . $label . '</span> ';
         if ($spacing) echo nl2br(nls2p($data)) . $lineend . '<br />' . "\n";
         else echo $data . $lineend . '<br />' . "\n";
//        if ($spacing) echo '</div>' . "\n";
      }
   }
}

// formats event data based on current field and writes line
function pem_event_data($position, $event, $field)
{
   global $time_format, $date_format, $meta_data, $this_user;
   extract($event);
   extract($field);

   switch(true)
   {
      // HANDLE ANY ENTRY FIELDS
      case ($name == "entry_name"):
         $title = (!empty($event["date_name"])) ? $entry_name . ': ' . $event["date_name"] : $entry_name;
         pem_event_line($position, $title, $label);
         break;
      case ($name == "entry_description"):
         pem_event_line($position, $entry_description, $label, true);
// TODO - to remove description label use the line below in place of thelive above
//      pem_event_line($position, $entry_description, "", true);
         break;
      case ($name == "entry_category"):
         pem_event_line($position, $entry_category_text, $label);
         break;
      case ($name == "entry_presenter"):
         pem_event_line($position, $entry_presenter, $entry_presenter_text);
         break;
      case ($name == "entry_reg_require"):
         if ($entry_reg_require)
         {
            global $this_event;
            echo '<div style="margin:10px 0;"><h4>' . __("Event Registration:") . '</h4>' . "\n";
            echo '<div class="indent">' . "\n";
            echo __("Registration is required for this event.") . '<br />' . "\n";

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
                     echo  '<span class="important">' . __("Registration is now OPEN.") . '</span><br />' . "\n";
                  }
                  else
                  {
                     $waitseats = $this_event["entry_reg_current"] - $this_event["entry_reg_max"];
                     echo '<span class="important">0</span> ' . __("of") . ' ' . $this_event["entry_reg_max"] . ' ' . __("seats available.") . '<br />' . "\n";
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
               // pem_event_line(99, $this_event["entry_reg_current"], __("Current Registration:"));
               // pem_event_line(99, $this_event["entry_reg_max"], __("Max Registration:"));
            }

            unset($controls);
            if ($show_reg_button AND pem_user_authorized(array("Registrations" => "View")) AND pem_user_authorized(array("Registrations" => "Add")))
            {
               $controls[] = array("label" => __("Register New or View List"), "link" => "view.php?e=regs");
            }
            elseif ($show_reg_button AND pem_user_authorized(array("Registrations" => "Add")))
            {
               $controls[] = array("label" => __("Register Now"), "link" => "view.php?e=regs");
            }
            elseif (pem_user_authorized(array("Registrations" => "View")))
            {
               $controls[] = array("label" => __("View Registration List"), "link" => "view.php?e=regs");
            }
            if (pem_user_authorized(array("Registrations" => array("Edit", "Delete"))))
            {
               global $did;
               $controls[] = array("label" => __("Manage Registrations"), "link" => "pem-admin/manage-reg.php?did=" . $did);
            }
            if (isset($controls))
            {
               pem_controls($controls, false, true);
               echo '<br />' . "\n";
            }
            echo '</div>' . "\n";
         }
         break;
      case ($name == "entry_open_to_public"):
         $data = ($entry_open_to_public) ? __("This event is open to the public.") : __("This event is not open to the public.");
         pem_event_line($position, $data);
         break;
      case ($name == "entry_visible_to_public"):
         $data = ($entry_visible_to_public) ? __("This event is visible to the public.") : __("This event is not visible to the public.");
         pem_event_line($position, $data);
         break;
      case ($name == "entry_seats_expected"):
         if ($this_user["user_login"] != "public") pem_event_line($position, $entry_seats_expected, $label);
         break;
      case ($name == "entry_priv_notes"):
         if ($this_user["user_login"] != "public") pem_event_line($position, $entry_priv_notes, $label, true);
         break;

      // HANDLE ANY DATE FIELDS
      case ($name == "date_name"):
         pem_event_line($position, $date_name, $label);
         break;
      case ($name == "date_description"):
         pem_event_line($position, $date_description, $label, true);
// TODO to remove description label use the line below in place of the one above and comment out the date_name case
//      pem_event_line($position, $date_description, "", true);
         break;
      case ($name == "date_when"):
         $date_begin = pem_date("l, " . $date_format, $when_begin);
         $date_end = pem_date("l, " . $date_format, $when_begin);
         $datenote = ($allday) ?  " " . __("(All Day)") : "";
         if ($date_begin == $date_end) pem_event_line($position, $date_begin . $datenote, __("Date:"));
         else pem_event_line($position, pem_simplify_dates($when_begin, $when_end) . $datenote, __("Dates:"));

         if ($entry_type < 3 AND !$allday)
         {
            // pem_event_line($position, pem_date($time_format, $when_begin) . ' to ' . pem_date($time_format, $when_end), __("Time:"));
            // pem_event_line($position, pem_date($time_format, $real_begin) . ' to ' . pem_date($time_format, $real_end), __("Reserved Time:"));
            pem_event_line($position, pem_simplify_times($when_begin, $when_end), __("Time:"));
            if ($this_user["user_login"] != "public") pem_event_line($position, pem_simplify_times($real_begin, $real_end), __("Reserved Time:"));
         }
         break;
      case ($name == "date_location"):
         pem_event_line($position, $spaces_text, __("Location:"));
         break;
      case ($name == "date_category"):
      // ICPL HACK - hide category
         if ($this_user["user_login"] != "public") pem_event_line($position, $date_category_text, $label);
         break;
      case ($name == "date_presenter"):
         pem_event_line($position, $date_presenter, $date_presenter_text);
         break;
      case ($name == "date_reg_require"):
         if ($date_reg_require)
         {
            global $this_event;
            echo '<div style="margin:10px 0;"><h4>' . __("Date Registration:") . '</h4>' . "\n";
            echo '<div class="indent">' . "\n";
            echo __("Registration is required for this event date.") . '<br />' . "\n";

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
                     echo  '<span class="important">' . __("Registration is now OPEN.") . '</span><br />' . "\n";
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
               // pem_event_line(99, $this_event["date_reg_current"], __("Current Registration:"));
               // pem_event_line(99, $this_event["date_reg_max"], __("Max Registration:"));
            }

            unset($controls);
            if ($show_reg_button AND pem_user_authorized(array("Registrations" => "View")) AND pem_user_authorized(array("Registrations" => "Add")))
            {
               $controls[] = array("label" => __("Register New or View List"), "link" => "view.php?e=regs");
            }
            elseif ($show_reg_button AND pem_user_authorized(array("Registrations" => "Add")))
            {
               $controls[] = array("label" => __("Register Now"), "link" => "view.php?e=regs");
            }
            elseif (pem_user_authorized(array("Registrations" => "View")))
            {
               $controls[] = array("label" => __("View Registration List"), "link" => "view.php?e=regs");
            }
            if (pem_user_authorized(array("Registrations" => array("Edit", "Delete"))))
            {
               global $did;
               $controls[] = array("label" => __("Manage Registrations"), "link" => "pem-admin/manage-reg.php?did=" . $did);
            }
            if (isset($controls))
            {
               pem_controls($controls, false, true);
               echo '<br />' . "\n";
            }
            echo '</div>' . "\n";
         }
         break;
      case ($name == "date_open_to_public"):
         $data = ($date_open_to_public) ? __("This event is open to the public.") : __("This event is not open to the public.");
         pem_event_line($position, $data, "", true);
         break;
      case ($name == "date_visible_to_public"):
         $data = ($date_visible_to_public) ? __("This event is visible to the public.") : __("This event is not visible to the public.");
         if ($this_user["user_login"] != "public") pem_event_line($position, $data, "", true);
         break;
      case ($name == "date_seats_expected"):
         if ($this_user["user_login"] != "public") pem_event_line($position, $date_seats_expected, $label);
         break;
      case ($name == "date_priv_notes"):
         if ($this_user["user_login"] != "public") pem_event_line($position, $date_priv_notes, $label, true);
         break;

      // HANDLE ANY META FIELD
      case (substr($name, 0, 4) == "meta"):
         $meta_id = (substr($name, 4));

         switch(true)
         {
            case ($type == "textinput"):
               pem_event_line($position, $meta_data[$meta_id]["data"], $value["input_label"], true);
               break;
            case ($type == "checkbox"):
               if ($meta_data[$meta_id]["data"]) pem_event_line($position, $value["yes_text"], "", true);
               else pem_event_line($position, $value["no_text"], "", true);
               break;
            case ($type == "boolean"):
               if ($meta_data[$meta_id]["data"]) pem_event_line($position, $value["yes_text"], "", true);
               else pem_event_line($position, $value["no_text"], "", true);
               break;
            case ($type == "select"):
               pem_event_line($position, $value[$meta_data[$meta_id]["data"]], $value["select_label"], true);
               break;
            case ($type == "contact"):
               echo '<div style="margin:10px 0;">' . "\n";
               foreach ($value as $key => $val) if ($val[1])
                  {
                     if ($key == "email" AND !empty($meta_data[$meta_id][$key])) $this_value = '<a href="mailto:' . $meta_data[$meta_id][$key] . '">' . $meta_data[$meta_id][$key] . '</a>';
                     else $this_value = $meta_data[$meta_id][$key];
                     pem_event_line($position, $this_value, $val[0]);
                  }
               echo '</div>' . "\n";
               break;
         }
         break;
   }
}

?>