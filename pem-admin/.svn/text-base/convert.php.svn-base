<?php
/* ========================== FILE INFORMATION =================================
phxEventManager :: convert.php

Performs the conversion operations to create events from dates and dates from
events.  Event information is copied to split a date out from its parent event.
Event informaton is lost when moving its one or more dates under a different
event.
============================================================================= */

$pagetitle = "Convert Event";
$navigation = "administration";
$cache_set = array("current_navigation" => "events");
include_once "../pem-includes/header.php";

$did = pem_cache_get("current_date");
$eid = pem_cache_get("current_event");
$do_action = pem_cache_get("current_action");

if (isset($datasubmit))
{
   $datelist = explode("-", substr($alldates, 1));
   foreach ($datelist AS $date_id)
   {
      $data = array("entry_id" => $new_event_id);
      $where = array("id" => $date_id);
      pem_update_row("dates", $data, $where);
   }
   $data = array("entry_status" => "2");
   $where = array("id" => $entry_id);
   pem_update_row("entries", $data, $where);

   header('Location: ' . $pem_url . 'view.php?e=event&eid=' . $new_event_id);
}

if ($do_action == "convertevent") // split this date to a new event
{
   $pemdb =& mdb2_connect($dsn, $options, "connect");
   $where = array("id" => $did);
   $where["date_status"] = array("!=", "2");
   $date_exists = pem_get_count("dates", $where);

   if (!$date_exists)
   {
      $sql = "SELECT entry_name, entry_description FROM " . $table_prefix . "entries WHERE ";
      $sql .= "id = :entry_id AND ";
      $sql .= "entry_status != 2";
      $sql_values = array("entry_id" => $eid);
      $eventret = pem_exec_sql($sql, $sql_values);
      $thisevent = $eventret[0];
      unset($eventret);

      $where = array("entry_id" => $eid);
      $where["status"] = array("!=", "2");
      $dates_count = pem_get_count("dates", $where);
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
      $thisevent = $eventret[0];
      unset($eventret);

      $where = array("entry_id" => $thisevent["entry_id"]);
//      $where["date_status"] = array("!=", "2");
      $dates_count = pem_get_count("dates", $where);
   }

   if ($dates_count == 1) echo __("This event only has one valid date.  The single date cannot be split to make a new event.") . '<br />' . "\n";
   else
   {
      extract($thisevent);
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

      $data = array("entry_id" => $entry_id);
      $where = array("id" => $did);
      pem_update_row("dates", $data, $where);
      header('Location: ' . $pem_url . 'view.php?e=event&did=' . $did . '&a=converteventmsg');
   }



}
elseif ($do_action == "convertdate") // move this event's dates to a different event
{
   $sql = "SELECT d.id, e.entry_name, d.date_name, d.when_begin, d.when_end FROM " . $table_prefix . "entries AS e, " . $table_prefix . "dates AS d WHERE ";
   $sql .= "e.id = d.entry_id AND ";
   $sql .= "d.entry_id = :entry_id AND ";
   $sql .= "e.entry_status != 2 AND ";
   $sql .= "d.date_status != 2";
   $sql_values = array("entry_id" => $eid);
   $datelist = pem_exec_sql($sql, $sql_values);

   $datecount = count($datelist);
   if ($datecount > 1) $title = sprintf(__("Move %1\$s (%2\$s Dates) to a Different Event"), $datelist[0]["entry_name"], $datecount);
   else $title = sprintf(__("Move %1\$s (%2\$s Date) to a Different Event"), $datelist[0]["entry_name"], $datecount);

   pem_fieldset_begin($title);
   echo '<p>' . __("This operation will delete the global entry information associated with this event, and ALL dates tied to this event will be moved to the other event.  Please be sure to move any global content you wish to save to date-level fields prior to this conversion.") . "</p>\n";


   echo '<b>' . __("The following dates will be converted:")   . '</b>' . "\n";
   echo '<ul class="bullets">' . "\n";

   $alldates = "";
   foreach ($datelist AS $this_date)
   {
      $alldates .= "-" . $this_date["id"];
      $label = (!empty($this_date["date_name"])) ? $this_date["date_name"] . " - " : "";
      $date_begin = pem_date("l, " . $date_format, $this_date["when_begin"]);
      $date_end = pem_date("l, " . $date_format, $this_date["when_end"]);
      if ($date_begin == $date_end) $label .= $date_begin;
      else $label .= pem_simplify_dates($date_begin, $date_end);
      echo '<li>' . $label . '</li>' . "\n";
   }
   echo '</ul>' . "\n";

   $new_event_id_note = __("(The event_id number is found at the bottom of event view pages)");

   pem_form_begin(array("nameid" => "convertform", "action" => $PHP_SELF, "class" => "convertform"));
   pem_hidden_input(array("name" => "datasubmit", "value" => 1));
   pem_hidden_input(array("name" => "entry_id", "value" => $eid));
   pem_hidden_input(array("name" => "alldates", "value" => $alldates));

   pem_field_label(array("default" => __("New Event ID:"), "for" => "new_event_id"));
   pem_text_input(array("nameid" => "new_event_id", "size" => 8, "maxlength" => 11));
   pem_field_note(array("default" => $new_event_id_note));

   pem_form_submit("convertform");
   pem_form_end();

   pem_fieldset_end();

}
else header('Location: ' . $pem_url . '?e=event');
?>