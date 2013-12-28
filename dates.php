<?php
/* ========================== FILE INFORMATION =================================
phxEventManager :: dates.php

============================================================================= */

$did = pem_cache_get("current_date");
$eid = pem_cache_get("current_event");

/*
echo "DATE ID: $did <br />";
echo "EVENT ID: $eid <br />";
*/


/*
// Perform activation update
if ($act == 1) {
   pemActivateEntryDate($id, $username);
   }
if ($deact == 1) {
   pemDeactivateEntryDate($id, $username);
   }
*/

$pemdb =& mdb2_connect($dsn, $options, "connect");
if (!empty($did)) // collect the combined date+entry information centered on current date
{
   $sql = "SELECT * FROM " . $table_prefix . "entries as e, " . $table_prefix . "dates as d WHERE ";
   $sql .= "e.id = d.entry_id AND ";
   $sql .= "d.id = :date_id AND ";
   $sql .= "e.entry_status != 2 AND ";
   $sql .= "d.date_status != 2";
   $sql_values = array("date_id" => $did);
   $eventres = pem_exec_sql($sql, $sql_values);
   $thisevent = $eventres[0];
   unset($eventres);

   $dates_count = true;
   $entry_id = $thisevent["entry_id"];

   $sql = "SELECT * FROM " . $table_prefix . "entries as e, " . $table_prefix . "dates as d WHERE ";
   $sql .= "e.id = d.entry_id AND ";
   $sql .= "d.entry_id = :entry_id AND ";
   $sql .= "e.entry_status != 2 AND ";
   $sql .= "d.date_status != 2";
   $sql_values = array("entry_id" => $entry_id);
   $datelist = pem_exec_sql($sql, $sql_values);
}
elseif (!empty($eid)) // collect the combined date+entry information without a current date
{
   $sql = "SELECT entry_name FROM " . $table_prefix . "entries WHERE ";
   $sql .= "id = :entry_id AND ";
   $sql .= "entry_status != 2";
   $sql_values = array("entry_id" => $eid);
   $eventret = pem_exec_sql($sql, $sql_values);
   $thisevent = $eventret[0];
   unset($eventret);

   $dates_count = true;
   $entry_id = $eid;

   $sql = "SELECT * FROM " . $table_prefix . "entries as e, " . $table_prefix . "dates as d WHERE ";
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

/* NOT PULLING CATEGORY NAME FOR MULTI-DATE DISPLAY AT THIS TIME
      $sql = "SELECT category_name FROM " . $table_prefix . "categories WHERE id = :id";
      if (!empty($datelist[$i]["entry_category"]))
      {
         $sql_values = array("id" => $datelist[$i]["entry_category"]);
         $sql_prep = $pemdb->prepare($sql);
         if (PEAR::isError($sql_prep)) PEAR_error($sql_prep);
         $result = $sql_prep->execute($sql_values);
         if (PEAR::isError($result)) PEAR_error($result);
         $row = $result->fetchRow(MDB2_FETCHMODE_ASSOC);
         $datelist[$i]["entry_category_text"] = $row["category_name"];
      }
      if (!empty($datelist[$i]["date_category"]))
      {
         $sql_values = array("id" => $datelist[$i]["date_category"]);
         $sql_prep = $pemdb->prepare($sql);
         if (PEAR::isError($sql_prep)) PEAR_error($sql_prep);
         $result = $sql_prep->execute($sql_values);
         if (PEAR::isError($result)) PEAR_error($result);
         $row = $result->fetchRow(MDB2_FETCHMODE_ASSOC);
         $datelist[$i]["date_category_text"] = $row["category_name"];
      }
*/
   }
}
mdb2_disconnect($pemdb);

$hasregs = ($thisevent["date_reg_require"] == 1 OR $thisevent["entry_reg_require"] == 1) ? true : false;
$hasdates = (!empty($dates_count)) ? true : false;
if ($navigation == "event") include_once ABSPATH . PEMINC . "/nav-event.php";
echo '<div id="content">' . "\n";

switch (true)
{
case (0 == $thisevent["entry_type"]):
   $entry_type = "internal_scheduled";
   break;
case (1 == $thisevent["entry_type"]):
   $entry_type = "external_scheduled";
   break;
case (2 == $thisevent["entry_type"]):
   $entry_type = "internal_unscheduled";
   break;
case (3 == $thisevent["entry_type"]):
   $entry_type = "external_unscheduled";
   break;
}

pem_event_data($datelist, $thisevent);

/*
echo "<br />==================================<br /><pre>";
print_r($datelist);
echo "</pre><br />==================================<br />";
*/

pem_fieldset_end();


// =============================================================================
// =========================== LOCAL FUNCTIONS =================================
// =============================================================================

// writes out oneline of event data
// first position lines are used as the fieldset legend
function pem_event_line($data, $label = "")
{
// echo "$position, $data, $label<br />";
   $ret = "";
   if (!empty($label)) $ret .=  '<span class="viewlabel">' . $label . '</span> ';
   $ret .=  nl2br($data) . '<br />' . "\n";
   return $ret;
}

// formats event data based on current field and writes line
function pem_event_data($list, $thisevent)
{
   global $time_format, $date_format;

   $ret = "";
   for ($i = 0; $i < count($list); $i++)
   {
      extract($list[$i]);

      $hold = pem_event_line($date_name, $label);

      $date_begin = pem_date("l, " . $date_format, $when_begin);
      $date_end = pem_date("l, " . $date_format, $when_end);
      if ($date_begin == $date_end) $hold .= pem_event_line($date_begin, __("Date:"));
      else $hold .= pem_event_line($date_begin . ' ' . __("to") . ' ' . $date_end, __("Dates:"));
      $hold .= pem_event_line(pem_date($time_format, $when_begin) . ' to ' . pem_date($time_format, $when_end), __("Time:"));
      $hold .= pem_event_line(pem_date($time_format, $real_begin) . ' to ' . pem_date($time_format, $real_end), __("Reserved Time:"));
      $hold .= pem_event_line($spaces_text, __("Location:"));

      if ($thisevent["id"] == $id)
      {
         $thisret = '<div class="dateitem" onclick="document.location = \'/view.php?e=event&did=' . $id . '\'" style="cursor:pointer;">' . "\n";
         $thisret .= $hold;
         $thisret .= '</div>' . "\n";
      }
      else
      {
         $ret .= '<div class="dateitem" onmouseover="this.className=\'dateitemhover\';" onmouseout="this.className=\'dateitem\';" onclick="document.location = \'/view.php?e=event&did=' . $id . '\'">' . "\n";
         $ret .= $hold;
         $ret .= '</div>' . "\n";
      }
   }

   pem_fieldset_begin($thisevent["entry_name"]);
   if (!empty($thisret)) echo $thisret;
   else echo __("The current date chosen for this event is not valid.  Select from the other dates below to view a complete event.");
   pem_fieldset_end();

   pem_fieldset_begin(__("Other Dates"));
   echo $ret;
   pem_fieldset_end();

}


?>