<?php
/* ========================== FILE INFORMATION ================================= 
phxEventManager :: recycle.php

Provides management options via the backend tab to undelete items.
============================================================================= */

$pagetitle = "Manage Deleted Events";
$navigation = "administration";
$page_access_requirement = array("Manage Deleted Events");
$cache_set = array("current_navigation" => "backend");
include_once "../pem-includes/header.php";

if (isset($datasubmit))
{
   switch(true)
   {
      case ($restoretype == "event"):
         $data = array("date_status" => "1");
         $where = array("id" => $did);
         pem_update_row("dates", $data, $where);
         $data = array("entry_status" => "1");
         $where = array("id" => $eid);
         pem_update_row("entries", $data, $where);
         echo '<p><b>' . __("Event restored.") . '</b>';
         echo ' (<a href="/view.php?did=' . $did . '">' . __("View") . '</a>)';
         echo '</p>' . "\n";
         break;
      case ($restoretype == "date"):
         $data = array("date_status" => "1");
         $where = array("id" => $did);
         pem_update_row("dates", $data, $where);
         echo '<p><b>' . __("Date restored.") . '</b>';
         echo ' (<a href="/view.php?did=' . $did . '">' . __("View") . '</a>)';
         echo '</p>' . "\n";
         break;
   }
}

$current_event_type = pem_cache_get("current_event_type"); 
switch (true)
{
   case ($current_event_type == "scheduled"):
      $page_title = __("Approve Queued Calendar Events");
      break;
   case ($current_event_type == "unscheduled"):
      $page_title = __("Approve Queued Side Box Events");
      break;
   case ($current_event_type == "allday"):
      $page_title = __("Approve Queued All-Day Events");
      break;
}


pem_fieldset_begin(__("Deleted Events"));
// echo '<p>' . __("Restoring an event from recycle that has only one associated date will also undelete the date.") . "</p>\n";

$pemdb =& mdb2_connect($dsn, $options, "connect");
$sql = "SELECT d.id, d.entry_id, d.when_begin, e.entry_name FROM " . $table_prefix . "entries as e, " . $table_prefix . "dates as d WHERE ";
$sql .= "e.id = d.entry_id AND ";
$sql .= "e.entry_status = '2' AND ";
$sql .= "d.date_status = '2'";
$sql .= " ORDER BY d.when_begin, d.when_end, e.entry_name";
$list = pem_exec_sql($sql);
mdb2_disconnect($pemdb);
ob_start();
for ($i = 0; $i < count($list); $i++)
{
   $row = ($i % 2) ? "row2" : "row1";
   echo_data($list[$i], $row, "event");
}
$data = ob_get_clean();
if (!empty($data))
{
   $fields_header =  array(
           __("Date"),
           __("Name")
   );

   echo '<table cellspacing="0" class="datalist">' . "\n";
   echo '<tr>' . "\n";
   for ($i = 0; $i < count($fields_header); $i++)
   {
      echo '<th style="padding-top:0;">' . $fields_header[$i] . '</th>' . "\n";
   }
   echo '</tr>' . "\n";
   echo $data;
   echo '</table>' . "\n";
}
else
{
   echo '<p style="font-weight:bold;">' . __("No items found.") . '</p>' . "\n";
}
pem_fieldset_end();


pem_fieldset_begin(__("Deleted Dates"));
// echo '<p>' . __("Restoring a date from recycle that has only one associated date will also undelete the date.") . "</p>\n";

$pemdb =& mdb2_connect($dsn, $options, "connect");
$sql = "SELECT d.id, d.entry_id, d.when_begin, e.entry_name FROM " . $table_prefix . "entries as e, " . $table_prefix . "dates as d WHERE ";
$sql .= "e.id = d.entry_id AND ";
$sql .= "e.entry_status != '2' AND ";
$sql .= "d.date_status = '2'";
$sql .= " ORDER BY d.when_begin, d.when_end, e.entry_name";
$list = pem_exec_sql($sql);
mdb2_disconnect($pemdb);
ob_start();
for ($i = 0; $i < count($list); $i++)
{
   $row = ($i % 2) ? "row2" : "row1";
   echo_data($list[$i], $row, "date");
}
$data = ob_get_clean();
if (!empty($data))
{
   $fields_header =  array(
           __("Date"),
           __("Name")
   );

   echo '<table cellspacing="0" class="datalist">' . "\n";
   echo '<tr>' . "\n";
   for ($i = 0; $i < count($fields_header); $i++)
   {
      echo '<th style="padding-top:0;">' . $fields_header[$i] . '</th>' . "\n";
   }
   echo '</tr>' . "\n";
   echo $data;
   echo '</table>' . "\n";
}
else
{
   echo '<p style="font-weight:bold;">' . __("No items found.") . '</p>' . "\n";
}
pem_fieldset_end();

include ABSPATH . PEMINC . "/footer.php";


// =============================================================================
// =========================== LOCAL FUNCTIONS =================================
// =============================================================================

function echo_data($data, $row, $restoretype)
{
   global $restore_confirm, $date_format;
   extract($data);

   $status = (0 == $date_status OR 0 == $entry_status) ? 0 : 1;

   echo '<tr class="' . $row . '"><td style="text-align:left;">' . "\n";
   pem_form_begin(array("nameid" => "dataform" . $id, "action" => $PHP_SELF));
   pem_hidden_input(array("name" => "datasubmit", "value" => "edit"));
   pem_hidden_input(array("name" => "did", "value" => $id));
   pem_hidden_input(array("name" => "eid", "value" => $entry_id));
   pem_hidden_input(array("name" => "restoretype", "value" => $restoretype));
   pem_field_label(array("default" => pem_date($date_format . " (l)", $when_begin)));
   pem_form_end();
   echo '</td><td style="text-align:left;">' . "\n";
   echo '<a href="/view.php?did=' . $id . '">' . $entry_name . '</a>' . "\n";
   echo '</td><td class="controlbox">' . "\n";
   $controls[] = array("label" => __("Undelete"), "onclick" => "confirm_submit('dataform" . $id . "', 'restore', '" . $restore_confirm . "');");
   pem_controls($controls);
   echo '</td></tr>' . "\n";
} // END echo_data

?>