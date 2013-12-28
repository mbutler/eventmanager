<?php
/* ========================== FILE INFORMATION ================================= 
phxEventManager :: missing-dates.php

============================================================================= */

$pagetitle = "Events Missing Dates";
$navigation = "administration";
$page_access_requirement = array("Manage Internal Events", "Manage External Events");
$cache_set = array("current_navigation" => "events");
include_once "../pem-includes/header.php";


if (isset($datasubmit))
{
   switch(true)
   {
      case ($datasubmit == "activate"):
         MDB2::loadFile("Date"); // load Date helper class
         $entry_approved_stamp = MDB2_Date::mdbNow();
         $date_approved_stamp = $entry_approved_stamp;
         $login = pem_get_login();

         $data = array("date_status" => "1", "date_approved_by" => $login, "date_approved_stamp" => $date_approved_stamp);
         $where = array("id" => $did);
         pem_update_row("dates", $data, $where);
         $data = array("entry_status" => "1", "entry_approved_by" => $login, "entry_approved_stamp" => $entry_approved_stamp);
         $where = array("id" => $eid);
         pem_update_row("entries", $data, $where);
         echo '<p><b>' . __("Event activated.") . '</b></p>' . "\n";
         break;
      case ($datasubmit == "deactivate"):
         $data = array("date_status" => "0");
         $where = array("id" => $did);
         pem_update_row("dates", $data, $where);
         $data = array("entry_status" => "0");
         $where = array("id" => $eid);
         pem_update_row("entries", $data, $where);
         echo '<p><b>' . __("Event deactivated.") . '</b></p>' . "\n";
         break;
      case ($datasubmit == "delete"):
         $data = array("date_status" => "2");
         $where = array("id" => $did);
         pem_update_row("dates", $data, $where);
         $data = array("entry_status" => "2");
         $where = array("id" => $eid);
         pem_update_row("entries", $data, $where);
         echo '<p><b>' . __("Event deleted to recycle.") . '</b></p>' . "\n";
         break;
   }
}

$current_event_type = pem_cache_get("current_event_type"); 
switch (true)
{
   case ($current_event_type == "scheduled"):
      $page_title = __("Calendar Events Missing Dates");
      break;
   case ($current_event_type == "unscheduled"):
      $page_title = __("Side Box Events Missing Dates");
      break;
   case ($current_event_type == "allday"):
      $page_title = __("All-Day Events Missing Dates");
      break;
}

// list current items in the table
pem_fieldset_begin($page_title);
echo '<p>' . '<p>' . __("The following events do not have dates associated with them.") . "</p>\n";

$pemdb =& mdb2_connect($dsn, $options, "connect");


$sql = "SELECT e.id, e.entry_name FROM " . $table_prefix . "entries AS e LEFT JOIN " . $table_prefix . "dates AS d ON e.id = d.entry_id WHERE ";
$sql .= "d.entry_id IS NULL AND ";
$sql .= "e.entry_status != '2' AND "; 
if ($current_event_type == "scheduled") $sql .= "(e.entry_type = '1' OR e.entry_type = '2')";
elseif ($current_event_type == "unscheduled") $sql .= "(e.entry_type = '3' OR e.entry_type = '4')";
$sql .= " ORDER BY e.entry_name";


// echo "Query: $sql <br />";
$list = pem_exec_sql($sql);
mdb2_disconnect($pemdb);

ob_start();
for ($i = 0; $i < count($list); $i++)
{
   $row = ($i % 2) ? "row2" : "row1";
   echo_data($list[$i], $row);
}
$data = ob_get_clean();
if (!empty($data))
{
   $fields_header =  array(
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

function echo_data($data, $row)
{
   global $delete_confirm, $date_format;
   extract($data);

   $status = (0 == $date_status OR 0 == $entry_status) ? 0 : 1;

   echo '<tr class="' . $row . '"><td style="text-align:left;">' . "\n";
   pem_form_begin(array("nameid" => "dataform" . $id, "action" => $PHP_SELF));
   pem_hidden_input(array("name" => "datasubmit", "value" => "edit"));
   pem_hidden_input(array("name" => "did", "value" => $id));
   pem_hidden_input(array("name" => "eid", "value" => $entry_id));
   echo '<a href="/view.php?eid=' . $id . '">' . $entry_name . '</a>' . "\n";
   pem_form_end();
   echo '</td><td class="controlbox">' . "\n";
   if ($status == "0") $controls[] = array("label" => __("Activate"), "onclick" => "action_submit('dataform" . $id . "', 'activate');");
   else $controls[] = array("label" => __("Deactivate"), "onclick" => "action_submit('dataform" . $id . "', 'deactivate');");
   $controls[] = array("label" => __("Edit"), "onclick" => "action_submit('dataform" . $id . "', 'edit');");
   $controls[] = array("label" => __("Delete"), "onclick" => "confirm_submit('dataform" . $id . "', 'delete', '" . $delete_confirm . "');");
   pem_controls($controls);
   echo '</td></tr>' . "\n";
} // END echo_data

?>