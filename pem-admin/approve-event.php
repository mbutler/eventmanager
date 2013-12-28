<?php
/* ========================== FILE INFORMATION =================================
phxEventManager :: approve-event.php

============================================================================= */

$pagetitle = "Approve Queued Events";
$navigation = "administration";
//$page_access_requirement = array("Approve");
$cache_set = array("current_navigation" => "events");
include_once "../pem-includes/header.php";

$current_action = pem_cache_get("current_action");
pem_cache_flush("current_action");
if (!empty($current_action)) $datasubmit = $current_action;

if (isset($datasubmit))
{
   switch(true)
   {
      case ($datasubmit == "activate"):
         MDB2::loadFile("Date"); // load Date helper class
         $entry_approved_stamp = MDB2_Date::mdbNow();
         $date_approved_stamp = $entry_approved_stamp;
         $login = pem_get_login();
         $user_id = auth_get_user_id($login);

         $data = array("date_status" => "1", "date_approved_by" => $user_id, "date_approved_stamp" => $date_approved_stamp);
         $where = array("id" => $did);
         pem_update_row("dates", $data, $where);
         $data = array("entry_status" => "1", "entry_approved_by" => $user_id, "entry_approved_stamp" => $entry_approved_stamp);
         $where = array("id" => $eid);
         pem_update_row("entries", $data, $where);
         echo '<p><b>' . __("Event activated.") . '</b></p>' . "\n";

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

         $mail_approved = pem_mail_approved($did);
         pem_fieldset_begin(__("Send Approval Mail"));

         pem_form_begin(array("nameid" => "submitform", "action" => $PHP_SELF, "class" => "neweventform"));
         pem_hidden_input(array("name" => "datasubmit", "value" => "activate-email"));
         pem_hidden_input(array("nameid" => "subject", "value" => $mail_approved["subject"]));
         pem_hidden_input(array("nameid" => "from_address", "value" => $mail_approved["from_address"]));
         pem_hidden_input(array("nameid" => "from_name", "value" => $mail_approved["from_name"]));
         pem_hidden_input(array("nameid" => "format", "value" => $mail_approved["format"]));

         echo '<p>' . __("The default email content is in the field below; it can be edited to customize the message.  If you wish to send an email confirming approval of this event, select the recipient(s) and submit the form.") . '<p>' . "\n";

         pem_textarea_input(array("nameid" => "body", "default" => $mail_approved["body"], "style" => "width:450px; height:200px;"));
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
         break;
      case ($datasubmit == "activate-email"):
         if (isset($email_created) AND !empty($email_created)) pem_send_mail($email_created_address, $body, $subject, $from_address, $from_name);
         if (isset($email_contact1) AND !empty($email_contact1)) pem_send_mail($email_contact1_address, $body, $subject, $from_address, $from_name);
         if (isset($email_contact2) AND !empty($email_contact2)) pem_send_mail($email_contact2_address, $body, $subject, $from_address, $from_name);
// TODO
         pem_send_mail("email@yoursite.com", $body, $subject, $from_address, $from_name);
         echo '<p><b>' . __("Approval email sent.") . '</b></p>' . "\n";
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
      case ($datasubmit == "edit"):
         header('Location: ' . $pem_url . 'view.php?e=edit&did=' . $did);
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
      $page_title = __("Approve Queued Calendar Events");
      break;
   case ($current_event_type == "unscheduled"):
      $page_title = __("Approve Queued Side Box Events");
      break;
   case ($current_event_type == "allday"):
      $page_title = __("Approve Queued All-Day Events");
      break;
}

// list current items in the table
pem_fieldset_begin($page_title);
echo '<p>' . '<p>' . __("The following items are currently awaiting approval for posting.") . "</p>\n";

$pemdb =& mdb2_connect($dsn, $options, "connect");

$sql = "SELECT d.id, d.entry_id, d.when_begin, e.entry_name FROM " . $table_prefix . "entries as e, " . $table_prefix . "dates as d WHERE ";
$sql .= "e.id = d.entry_id AND ";
$sql .= "((e.entry_status = '0' AND d.date_status != '2') OR d.date_status = '0') AND ";
if ($current_event_type == "scheduled") $sql .= "(e.entry_type = '1' OR e.entry_type = '2') AND d.allday = '0'";
elseif ($current_event_type == "unscheduled") $sql .= "(e.entry_type = '3' OR e.entry_type = '4')";
if ($current_event_type == "allday") $sql .= "d.allday = '1'";
$sql .= " ORDER BY d.when_begin, d.when_end, e.entry_name";

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
   pem_field_label(array("default" => pem_date($date_format . " (l)", $when_begin)));
   pem_form_end();
   echo '</td><td style="text-align:left;">' . "\n";
   echo '<a href="/view.php?did=' . $id . '">' . $entry_name . '</a>' . "\n";
   echo '</td><td class="controlbox">' . "\n";
   if ($status == "0") $controls[] = array("label" => __("Activate"), "onclick" => "action_submit('dataform" . $id . "', 'activate');");
   else $controls[] = array("label" => __("Deactivate"), "onclick" => "action_submit('dataform" . $id . "', 'deactivate');");
   $controls[] = array("label" => __("Edit"), "onclick" => "action_submit('dataform" . $id . "', 'edit');");
   $controls[] = array("label" => __("Delete"), "onclick" => "confirm_submit('dataform" . $id . "', 'delete', '" . $delete_confirm . "');");
   pem_controls($controls);
   echo '</td></tr>' . "\n";
} // END echo_data

?>