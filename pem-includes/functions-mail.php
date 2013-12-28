<?php
/* ========================== FILE INFORMATION =================================
phxEventManager :: functions-mail.php


============================================================================= */

//onreg, onreg_wait, waitlist, onchange, or remind

//$result = pem_mail_onreg(1171, 689, "reg");
//$result = pem_mail_onreg(1171, 399, "reg");
//$result = pem_mail_onreg(1171, 407, "reg");
//$result = pem_mail_onreg(1171, 690, "reg");
//$result = pem_mail_onreg(1171, 691, "reg");

//$result = pem_mail_onreg(1171, 1806, "reg", $from_address, $from_name);

//pem_mail_waitlist($date_id, $reg_id, $from_address, $from_name, $format = "plain")
//pem_mail_onchange($date_id, $from_address, $from_name, $format = "plain")

//$result = pem_send_mail($to, $body, $subject, $from_address, $from_name);

// generates an email message
// $format can be plain, html, or both
function pem_send_mail($to, $body, $subject, $from_address, $from_name, $format = "plain", $attachments = false)
{
   $eol = "\r\n";
   $mime_boundary = md5(time());

   // Build header information
   $headers = "From: " . $from_name . "<" . $from_address . ">" . $eol;
   $headers .= "Reply-To: " . $from_name."<" . $from_address . ">" . $eol;
   $headers .= "Return-Path: " . $from_name."<" . $from_address . ">" . $eol;  // these two to set reply address
   $headers .= "Message-ID: <" . time() . "-" . $from_address . ">" . $eol;
   $headers .= "X-Mailer: PHP v" . phpversion() . $eol;          // these two to help avoid spam-filters

   // Boundry for marking the split & Multitype Headers
   $headers .= 'MIME-Version: 1.0'.$eol;
   $headers .= "Content-Type: multipart/mixed; boundary=\"" . $mime_boundary . "\"" . $eol . $eol;

   // Open the first part of the mail
   $msg = "--".$mime_boundary.$eol;

   $htmlalt_mime_boundary = $mime_boundary."_htmlalt";   // define a different MIME boundary for this section
   // Setup for text OR html
   $msg .= "Content-Type: multipart/alternative; boundary=\"" . $htmlalt_mime_boundary . "\"" . $eol . $eol;

   if ($format == "plain" OR $format == "both")
   {
      // Text Version
      $msg .= "--" . $htmlalt_mime_boundary . $eol;
      $msg .= "Content-Type: text/plain; charset=iso-8859-1" . $eol;
      $msg .= "Content-Transfer-Encoding: 8bit" . $eol . $eol;
      $msg .= $body . $eol . $eol;
//      $msg .= strip_tags(str_replace("<br>", "\n", substr($body, (strpos($body, "<body>")+6)))) . $eol . $eol;
   }
   if ($format == "html" OR $format == "both")
   {
      // HTML Version
      $msg .= "--".$htmlalt_mime_boundary.$eol;
      $msg .= "Content-Type: text/html; charset=iso-8859-1" . $eol;
      $msg .= "Content-Transfer-Encoding: 8bit" . $eol . $eol;
      $msg .= $body . $eol . $eol;
   }

   //close the html/plain text alternate portion
   $msg .= "--" . $htmlalt_mime_boundary . "--" . $eol.$eol;

   if ($attachments !== false)
   {
      for($i=0; $i < count($attachments); $i++)
      {
         if (is_file($attachments[$i]["file"]))
         {
            // File for Attachment
            $file_name = substr($attachments[$i]["file"], (strrpos($attachments[$i]["file"], "/")+1));

            $handle=fopen($attachments[$i]["file"], 'rb');
            $f_contents=fread($handle, filesize($attachments[$i]["file"]));
            $f_contents=chunk_split(base64_encode($f_contents));    //Encode the data for transition using base64_encode();
            $f_type=filetype($attachments[$i]["file"]);
            fclose($handle);

            // Attachment
            $msg .= "--" . $mime_boundary.$eol;
            $msg .= "Content-Type: " . $attachments[$i]["content_type"] . "; name=\"" . $file_name . "\"" . $eol;  // sometimes i have to send MS Word, use 'msword' instead of 'pdf'
            $msg .= "Content-Transfer-Encoding: base64" . $eol;
            $msg .= "Content-Description: " . $file_name . $eol;
            $msg .= "Content-Disposition: attachment; filename=\"" . $file_name . "\"" . $eol . $eol;  // !! This line needs TWO end of lines !! IMPORTANT !!
            $msg .= $f_contents . $eol . $eol;
         }
      }
   }

   // Finished
   $msg .= "--".$mime_boundary."--".$eol.$eol;  // finish with two eol's for better security. see Injection.

   // SEND THE EMAIL
   ini_set('sendmail_from',$from_address);  // the INI lines are to force the From Address to be used !
   $mail_sent = mail($to, $subject, $msg, $headers);
   ini_restore('sendmail_from');

   return $mail_sent;
} // END pem_send_mail

// $format must be plain or html
function pem_event_info_prep($this_event, $format = "plain")
{
   global $time_format, $date_format, $pem_url;
   $linefeed = ($format == "plain") ? "\n" : "<br />\n";

   $ret = __("Event:") . ' ';
   if ($this_event["entry_cancelled"] OR $this_event["date_cancelled"])
   {
      $ret .= "[" . __("CANCELLED") . "] ";
   }
   $title = (!empty($this_event["date_name"])) ? $this_event["entry_name"] . ': ' . $this_event["date_name"] : $this_event["entry_name"];
   $ret .= $title . $linefeed;

   $ret .= __("Date:") . ' ';
   $date_begin = pem_date("l, " . $date_format, $this_event["when_begin"]);
   $date_end = pem_date("l, " . $date_format, $this_event["when_end"]);
   if ($date_begin == $date_end) $ret .= $date_begin;
   else $ret .= pem_simplify_dates($date_begin, $date_end);
   $ret .= $linefeed;

   $ret .= __("Time:") . ' ';
   if ($this_event["allday"]) $ret .= __("All Day");
   else $ret .= pem_date($time_format, $this_event["when_begin"]) . ' - ' . pem_date($time_format, $this_event["when_end"]);
   $ret .= $linefeed;
   $ret .= __("Location:") . ' ' . $this_event["spaces_text"] . $linefeed;

   $dmeta = unserialize($this_event["date_meta"]);
   if (is_array($dmeta)) foreach($dmeta AS $meta_id => $meta)
   {
      if ($meta["type"] == "textinput")
      {
         $data = pem_get_row("id", $meta_id, "meta");
         $meta_data = unserialize($data["value"]);
         if (!empty($meta["data"])) $ret .= $meta_data["input_label"] . ' ' . $meta["data"] . $linefeed;
      }
      if ($meta["type"] == "contact")
      {
         $data = pem_get_row("id", $meta_id, "meta");
         $meta_data = unserialize($data["value"]);
         if (!empty($meta["name1"])) $ret .= $meta_data["name1"][0] . ' ' . $meta["name1"] . $linefeed;
         if (!empty($meta["name2"])) $ret .= $meta_data["name2"][0] . ' ' . $meta["name2"] . $linefeed;
         if (!empty($meta["phone1"])) $ret .= $meta_data["phone1"][0] . ' ' . $meta["phone1"] . $linefeed;
         if (!empty($meta["phone2"])) $ret .= $meta_data["phone2"][0] . ' ' . $meta["phone2"] . $linefeed;
         if (!empty($meta["email"])) $ret .= $meta_data["email"][0] . ' ' . $meta["email"] . $linefeed;
      }
   }
   $emeta = unserialize($this_event["entry_meta"]);
   if (is_array($emeta)) foreach($emeta AS $meta_id => $meta)
   {
      if ($meta["type"] == "textinput")
      {
         $data = pem_get_row("id", $meta_id, "meta");
         $meta_data = unserialize($data["value"]);
         if (!empty($meta["data"])) $ret .= $meta_data["input_label"] . ' ' . $meta["data"] . $linefeed;
      }
      if ($meta["type"] == "contact")
      {
         $data = pem_get_row("id", $meta_id, "meta");
         $meta_data = unserialize($data["value"]);
         if (!empty($meta["name1"])) $ret .= $meta_data["name1"][0] . ' ' . $meta["name1"] . $linefeed;
         if (!empty($meta["name2"])) $ret .= $meta_data["name2"][0] . ' ' . $meta["name2"] . $linefeed;
         if (!empty($meta["phone1"])) $ret .= $meta_data["phone1"][0] . ' ' . $meta["phone1"] . $linefeed;
         if (!empty($meta["phone2"])) $ret .= $meta_data["phone2"][0] . ' ' . $meta["phone2"] . $linefeed;
         if (!empty($meta["email"])) $ret .= $meta_data["email"][0] . ' ' . $meta["email"] . $linefeed;
      }
   }
   $ret .= __("Event Link:") . ' ' . $pem_url . 'view.php?did=' . $this_event["id"];
   $ret .= $linefeed;

   if (!empty($this_event["date_description"]) OR !empty($this_event["entry_description"])) $ret .= $linefeed . __("Event Description:") . $linefeed;
   if (!empty($this_event["date_description"])) $ret .= $this_event["date_description"] . $linefeed;
   if (!empty($this_event["entry_description"])) $ret .= $this_event["entry_description"] . $linefeed;
   if (!empty($this_event["date_description"]) OR !empty($this_event["entry_description"])) $ret .= $linefeed;


   return $ret;
} // END pem_event_info_prep



// generates an email message for new event submissions
// $format can be plain, html, or both
function pem_mail_submission($date_id, $to, $format = "plain")
{
   global $table_prefix;
   $settings = pem_get_settings();
   $from_address = $settings["notice_email"];
   $from_name = $settings["notice_from"];

   $pemdb =& mdb2_connect($dsn, $options, "connect");
   $where = array("id" => $date_id);
   $where["date_status"] = array("!=", "2");
   $date_exists = pem_get_count("dates", $where);

   if ($date_exists)
   {
      // get event information
      $sql = "SELECT * FROM " . $table_prefix . "entries as e, " . $table_prefix . "dates as d WHERE ";
      $sql .= "e.id = d.entry_id AND ";
      $sql .= "d.id = :date_id AND ";
      $sql .= "e.entry_status != 2 AND ";
      $sql .= "d.date_status != 2";
      $sql_values = array("date_id" => $date_id);
      $eventret = pem_exec_sql($sql, $sql_values);
      $this_event = $eventret[0];
      unset($eventret);

      // get room names
      $spaces = unserialize($this_event["spaces"]);
      $spaces_text = "";

      $field = ($current_report == "press" OR $current_report == "public") ? "space_name" : "space_name_short";
      $sql = "SELECT " . $field . " FROM " . $table_prefix . "spaces WHERE id = :space_id";
      $spaces_count = count($spaces);
      for ($j = 0; $j < $spaces_count; $j++) // build spaces_text
      {
         $sql_values = array("space_id" => $spaces[$j]);
         $sql_prep = $pemdb->prepare($sql);
         if (PEAR::isError($sql_prep)) PEAR_error($sql_prep);
         $result = $sql_prep->execute($sql_values);
         if (PEAR::isError($result)) PEAR_error($result);
         $row = $result->fetchRow(MDB2_FETCHMODE_ASSOC);
         $spaces_text .= $row[$field];
         if ($j < $spaces_count - 1) $spaces_text .= ", ";
      }
      $this_event["spaces_text"] = $spaces_text;

      $title = (!empty($this_event["date_name"])) ? $this_event["entry_name"] . ': ' . $this_event["date_name"] : $this_event["entry_name"];
      $subject = $settings["notice_subject"] . ' ' . $title;
      $event_body = pem_event_info_prep($this_event);

      $body = $settings["notice_submission_msg"] . "\n";
      $body .= "\n-------------------------------------------------\n";
      $body .= "--- IOWA CITY PUBLIC LIBRARY EVENT ---\n";
      $body .= "-------------------------------------------------\n";
      $body .= $event_body;
      pem_send_mail($to, $body, $subject, $from_address, $from_name);
   }
   mdb2_disconnect($pemdb);
} // END pem_mail_submission

// generates an email message for new event submissions
// $format can be plain, html, or both
function pem_mail_approved($date_id, $format = "plain")
{
   global $table_prefix;
   $settings = pem_get_settings();
   $from_address = $settings["notice_email"];
   $from_name = $settings["notice_from"];

   $pemdb =& mdb2_connect($dsn, $options, "connect");
   $where = array("id" => $date_id);
   $where["date_status"] = array("!=", "2");
   $date_exists = pem_get_count("dates", $where);

   if ($date_exists)
   {
      // get event information
      $sql = "SELECT * FROM " . $table_prefix . "entries as e, " . $table_prefix . "dates as d WHERE ";
      $sql .= "e.id = d.entry_id AND ";
      $sql .= "d.id = :date_id AND ";
      $sql .= "e.entry_status != 2 AND ";
      $sql .= "d.date_status != 2";
      $sql_values = array("date_id" => $date_id);
      $eventret = pem_exec_sql($sql, $sql_values);
      $this_event = $eventret[0];
      unset($eventret);

      // get room names
      $spaces = unserialize($this_event["spaces"]);
      $spaces_text = "";

      $field = ($current_report == "press" OR $current_report == "public") ? "space_name" : "space_name_short";
      $sql = "SELECT " . $field . " FROM " . $table_prefix . "spaces WHERE id = :space_id";
      $spaces_count = count($spaces);
      for ($j = 0; $j < $spaces_count; $j++) // build spaces_text
      {
         $sql_values = array("space_id" => $spaces[$j]);
         $sql_prep = $pemdb->prepare($sql);
         if (PEAR::isError($sql_prep)) PEAR_error($sql_prep);
         $result = $sql_prep->execute($sql_values);
         if (PEAR::isError($result)) PEAR_error($result);
         $row = $result->fetchRow(MDB2_FETCHMODE_ASSOC);
         $spaces_text .= $row[$field];
         if ($j < $spaces_count - 1) $spaces_text .= ", ";
      }
      $this_event["spaces_text"] = $spaces_text;

      $title = (!empty($this_event["date_name"])) ? $this_event["entry_name"] . ': ' . $this_event["date_name"] : $this_event["entry_name"];
      $subject = $settings["notice_subject"] . ' ' . $title;
      $event_body = pem_event_info_prep($this_event);

      $body = $settings["notice_approved_msg"] . "\n";
      $body .= "\n-------------------------------------------------\n";
      $body .= "--- IOWA CITY PUBLIC LIBRARY EVENT ---\n";
      $body .= "-------------------------------------------------\n";
      $body .= $event_body;
//      pem_send_mail($to, $body, $subject, $from_address, $from_name);
   }
   mdb2_disconnect($pemdb);
   $ret["body"] = $body;
   $ret["subject"] = $subject;
   $ret["from_address"] = $from_address;
   $ret["from_name"] = $from_name;
   $ret["format"] = $format;
   return $ret;
} // END pem_mail_approved


// generates an email message for new event submissions
// $format can be plain, html, or both
function pem_mail_edited($date_id, $format = "plain")
{
   global $table_prefix;
   $settings = pem_get_settings();
   $from_address = $settings["notice_email"];
   $from_name = $settings["notice_from"];

   $pemdb =& mdb2_connect($dsn, $options, "connect");
   $where = array("id" => $date_id);
   $where["date_status"] = array("!=", "2");
   $date_exists = pem_get_count("dates", $where);

   if ($date_exists)
   {
      // get event information
      $sql = "SELECT * FROM " . $table_prefix . "entries as e, " . $table_prefix . "dates as d WHERE ";
      $sql .= "e.id = d.entry_id AND ";
      $sql .= "d.id = :date_id AND ";
      $sql .= "e.entry_status != 2 AND ";
      $sql .= "d.date_status != 2";
      $sql_values = array("date_id" => $date_id);
      $eventret = pem_exec_sql($sql, $sql_values);
      $this_event = $eventret[0];
      unset($eventret);

      // get room names
      $spaces = unserialize($this_event["spaces"]);
      $spaces_text = "";

      $field = ($current_report == "press" OR $current_report == "public") ? "space_name" : "space_name_short";
      $sql = "SELECT " . $field . " FROM " . $table_prefix . "spaces WHERE id = :space_id";
      $spaces_count = count($spaces);
      for ($j = 0; $j < $spaces_count; $j++) // build spaces_text
      {
         $sql_values = array("space_id" => $spaces[$j]);
         $sql_prep = $pemdb->prepare($sql);
         if (PEAR::isError($sql_prep)) PEAR_error($sql_prep);
         $result = $sql_prep->execute($sql_values);
         if (PEAR::isError($result)) PEAR_error($result);
         $row = $result->fetchRow(MDB2_FETCHMODE_ASSOC);
         $spaces_text .= $row[$field];
         if ($j < $spaces_count - 1) $spaces_text .= ", ";
      }
      $this_event["spaces_text"] = $spaces_text;

      $title = (!empty($this_event["date_name"])) ? $this_event["entry_name"] . ': ' . $this_event["date_name"] : $this_event["entry_name"];
      $subject = $settings["notice_subject"] . ' ' . $title;
      $event_body = pem_event_info_prep($this_event);

      $body = $settings["notice_edited_msg"] . "\n";
      $body .= "\n-------------------------------------------------\n";
      $body .= "--- IOWA CITY PUBLIC LIBRARY EVENT ---\n";
      $body .= "-------------------------------------------------\n";
      $body .= $event_body;
//      pem_send_mail($to, $body, $subject, $from_address, $from_name);
   }
   mdb2_disconnect($pemdb);
   $ret["body"] = $body;
   $ret["subject"] = $subject;
   $ret["from_address"] = $from_address;
   $ret["from_name"] = $from_name;
   $ret["format"] = $format;
   $ret["format"] = $format;
   return $ret;
} // END pem_mail_edited


// generates an email message for new registrants
// $type can be "reg" or "wait"
// $format can be plain, html, or both
function pem_mail_onreg($date_id, $reg_id, $type, $format = "plain")
{
   global $table_prefix;
   $settings = pem_get_settings();
   $from_address = $settings["reg_email"];
   $from_name = $settings["reg_from"];

   $pemdb =& mdb2_connect($dsn, $options, "connect");
   $where = array("id" => $date_id);
   $where["date_status"] = array("!=", "2");
   $date_exists = pem_get_count("dates", $where);

   if ($date_exists)
   {
      // get event information
      $sql = "SELECT * FROM " . $table_prefix . "entries as e, " . $table_prefix . "dates as d WHERE ";
      $sql .= "e.id = d.entry_id AND ";
      $sql .= "d.id = :date_id AND ";
      $sql .= "e.entry_status != 2 AND ";
      $sql .= "d.date_status != 2";
      $sql_values = array("date_id" => $date_id);
      $eventret = pem_exec_sql($sql, $sql_values);
      $this_event = $eventret[0];
      unset($eventret);

      // get room names
      $spaces = unserialize($this_event["spaces"]);
      $spaces_text = "";

      $field = ($current_report == "press" OR $current_report == "public") ? "space_name" : "space_name_short";
      $sql = "SELECT " . $field . " FROM " . $table_prefix . "spaces WHERE id = :space_id";
      $spaces_count = count($spaces);
      for ($j = 0; $j < $spaces_count; $j++) // build spaces_text
      {
         $sql_values = array("space_id" => $spaces[$j]);
         $sql_prep = $pemdb->prepare($sql);
         if (PEAR::isError($sql_prep)) PEAR_error($sql_prep);
         $result = $sql_prep->execute($sql_values);
         if (PEAR::isError($result)) PEAR_error($result);
         $row = $result->fetchRow(MDB2_FETCHMODE_ASSOC);
         $spaces_text .= $row[$field];
         if ($j < $spaces_count - 1) $spaces_text .= ", ";
      }
      $this_event["spaces_text"] = $spaces_text;

      $title = (!empty($this_event["date_name"])) ? $this_event["entry_name"] . ': ' . $this_event["date_name"] : $this_event["entry_name"];
      $subject = $settings["reg_subject"] . ' ' . $title;
      $event_body = pem_event_info_prep($this_event);

      $where = array("id" => $reg_id);
      $reg_list = pem_get_rows("registrants", $where);

      foreach ($reg_list AS $this_reg)
      {
         if (!empty($this_reg["email"]))
         {
            $to = $this_reg["email"];
            $body = $this_reg["name1"] . ' ' . $this_reg["name2"] . ",\n";
            if ($type == "reg") $body .= $settings["reg_onreg_msg"] . "\n";
            if ($type == "wait") $body .= $settings["reg_onreg_waitmsg"] . "\n";
            $body .= "\n-------------------------------------------------\n";
            $body .= "--- IOWA CITY PUBLIC LIBRARY EVENT ---\n";
            $body .= "-------------------------------------------------\n";
            $body .= $event_body;
            pem_send_mail($to, $body, $subject, $from_address, $from_name);
         }
      }
   }
   mdb2_disconnect($pemdb);
} // END pem_mail_onreg


// generates an email message when moving from waitlist to registration
// $format can be plain, html, or both
function pem_mail_waitlist($date_id, $reg_id, $format = "plain")
{
   global $table_prefix;
   $settings = pem_get_settings();
   $from_address = $settings["reg_email"];
   $from_name = $settings["reg_from"];

   $pemdb =& mdb2_connect($dsn, $options, "connect");
   $where = array("id" => $date_id);
   $where["date_status"] = array("!=", "2");
   $date_exists = pem_get_count("dates", $where);

   if ($date_exists)
   {
      // get event information
      $sql = "SELECT * FROM " . $table_prefix . "entries as e, " . $table_prefix . "dates as d WHERE ";
      $sql .= "e.id = d.entry_id AND ";
      $sql .= "d.id = :date_id AND ";
      $sql .= "e.entry_status != 2 AND ";
      $sql .= "d.date_status != 2";
      $sql_values = array("date_id" => $date_id);
      $eventret = pem_exec_sql($sql, $sql_values);
      $this_event = $eventret[0];
      unset($eventret);

      // get room names
      $spaces = unserialize($this_event["spaces"]);
      $spaces_text = "";

      $field = ($current_report == "press" OR $current_report == "public") ? "space_name" : "space_name_short";
      $sql = "SELECT " . $field . " FROM " . $table_prefix . "spaces WHERE id = :space_id";
      $spaces_count = count($spaces);
      for ($j = 0; $j < $spaces_count; $j++) // build spaces_text
      {
         $sql_values = array("space_id" => $spaces[$j]);
         $sql_prep = $pemdb->prepare($sql);
         if (PEAR::isError($sql_prep)) PEAR_error($sql_prep);
         $result = $sql_prep->execute($sql_values);
         if (PEAR::isError($result)) PEAR_error($result);
         $row = $result->fetchRow(MDB2_FETCHMODE_ASSOC);
         $spaces_text .= $row[$field];
         if ($j < $spaces_count - 1) $spaces_text .= ", ";
      }
      $this_event["spaces_text"] = $spaces_text;

      $title = (!empty($this_event["date_name"])) ? $this_event["entry_name"] . ': ' . $this_event["date_name"] : $this_event["entry_name"];
      $subject = $settings["reg_subject"] . ' ' . $title;
      $event_body = pem_event_info_prep($this_event);

      $where = array("id" => $reg_id);
      $reg_list = pem_get_rows("registrants", $where);

      foreach ($reg_list AS $this_reg)
      {
         if (!empty($this_reg["email"]))
         {
            $to = $this_reg["email"];
            $body = $this_reg["name1"] . ' ' . $this_reg["name2"] . ",\n";
            $body .= $settings["reg_waitlist_msg"] . "\n";
            $body .= "\n-------------------------------------------------\n";
            $body .= "--- IOWA CITY PUBLIC LIBRARY EVENT ---\n";
            $body .= "-------------------------------------------------\n";
            $body .= $event_body;
            pem_send_mail($to, $body, $subject, $from_address, $from_name);
         }
      }
   }
   mdb2_disconnect($pemdb);
} // END pem_mail_waitlist

// generates an email message when a registered event is edited
// $format can be plain, html, or both
function pem_mail_onchange($date_id, $format = "plain")
{
   global $table_prefix;
   $settings = pem_get_settings();
   $from_address = $settings["reg_email"];
   $from_name = $settings["reg_from"];

   $pemdb =& mdb2_connect($dsn, $options, "connect");
   $where = array("id" => $date_id);
   $where["date_status"] = array("!=", "2");
   $date_exists = pem_get_count("dates", $where);

   if ($date_exists)
   {
      // get event information
      $sql = "SELECT * FROM " . $table_prefix . "entries as e, " . $table_prefix . "dates as d WHERE ";
      $sql .= "e.id = d.entry_id AND ";
      $sql .= "d.id = :date_id AND ";
      $sql .= "e.entry_status != 2 AND ";
      $sql .= "d.date_status != 2";
      $sql_values = array("date_id" => $date_id);
      $eventret = pem_exec_sql($sql, $sql_values);
      $this_event = $eventret[0];
      unset($eventret);

      // get room names
      $spaces = unserialize($this_event["spaces"]);
      $spaces_text = "";

      $field = ($current_report == "press" OR $current_report == "public") ? "space_name" : "space_name_short";
      $sql = "SELECT " . $field . " FROM " . $table_prefix . "spaces WHERE id = :space_id";
      $spaces_count = count($spaces);
      for ($j = 0; $j < $spaces_count; $j++) // build spaces_text
      {
         $sql_values = array("space_id" => $spaces[$j]);
         $sql_prep = $pemdb->prepare($sql);
         if (PEAR::isError($sql_prep)) PEAR_error($sql_prep);
         $result = $sql_prep->execute($sql_values);
         if (PEAR::isError($result)) PEAR_error($result);
         $row = $result->fetchRow(MDB2_FETCHMODE_ASSOC);
         $spaces_text .= $row[$field];
         if ($j < $spaces_count - 1) $spaces_text .= ", ";
      }
      $this_event["spaces_text"] = $spaces_text;

      $title = (!empty($this_event["date_name"])) ? $this_event["entry_name"] . ': ' . $this_event["date_name"] : $this_event["entry_name"];
      $subject = $settings["reg_subject"] . ' ' . $title;
      $event_body = pem_event_info_prep($this_event);

      $where = array("date_id" => $this_event["id"]);
      $reg_list = pem_get_rows("registrants", $where);

      foreach ($reg_list AS $this_reg)
      {
         if (!empty($this_reg["email"]))
         {
            $to = $this_reg["email"];
            $body = $this_reg["name1"] . ' ' . $this_reg["name2"] . ",\n";
            $body .= $settings["reg_onchange_msg"] . "\n";
            $body .= "\n-------------------------------------------------\n";
            $body .= "--- IOWA CITY PUBLIC LIBRARY EVENT ---\n";
            $body .= "-------------------------------------------------\n";
            $body .= $event_body;
            pem_send_mail($to, $body, $subject, $from_address, $from_name);
         }
      }
   }
   mdb2_disconnect($pemdb);
} // END pem_mail_onchange














// generates email messages to remind registrants of an upcoming event
// $format can be plain, html, or both
function pem_mail_remind($format = "plain")
{
   global $table_prefix;
   $settings = pem_get_settings();
   $from_address = $settings["reg_email"];
   $from_name = $settings["reg_from"];

   $date_begin_year = pem_date("Y");
   $date_begin_month = 10;
   $date_begin_day = 1;
   $date_end_year = 2007;
   $date_end_month = 10;
   $date_end_day = 1;

   $date_begin = $date_begin_year . "-" . zeropad($date_begin_month, 2) . "-" . zeropad($date_begin_day, 2) . " 00:00:00";
   $date_end = $date_end_year . "-" . zeropad($date_end_month, 2) . "-" . zeropad($date_end_day, 2) . " 23:59:59";
//   echo "begin: $date_begin <br />";
//   echo "end: $date_end <br />";

   $select_fields = array("d.id", "d.when_begin", "d.when_end", "d.allday", "d.spaces", "e.entry_name", "e.entry_type");
   $where_add = " AND (d.date_reg_require = 1 OR e.entry_reg_require = 1)";

   $fields = "";
   if (!isset($select_fields)) $fields = "*";
   else foreach ($select_fields AS $value) $fields .= $value . ", ";
   if (substr($fields, -2) == ", ") $fields = substr($fields, 0, strlen($fields)-2);
   $sql = "SELECT $fields FROM " . $table_prefix . "entries AS e, " . $table_prefix . "dates AS d WHERE ";
   $sql .= "e.id = d.entry_id AND ";
   $sql .= "e.entry_status != 2 AND ";
   $sql .= "d.date_status != 2";

   if (isset($where_dates)) $sql .= $where_dates;
   else $sql .= " AND d.when_begin <= :when_begin_before AND d.when_end >= :when_end_after";
   if (isset($where_add)) $sql .= $where_add;
   $sql .= " ORDER BY ";
   if (isset($order_by)) $sql .= $order_by;
   else $sql .= "d.when_begin, d.when_end, e.entry_name";

//echo "query: $sql <br />===================<br />";

   $pemdb =& mdb2_connect($dsn, $options, "connect");
   $sql_values = array("when_begin_before" => $date_end, "when_end_after" => $date_begin);
   $list = pem_exec_sql($sql, $sql_values);

   if (isset($select_fields) AND (in_array("d.spaces", $select_fields) OR in_array("*", $select_fields)))
   {
      for ($i = 0; $i < count($list); $i++)
      {
         $spaces = unserialize($list[$i]["spaces"]);
         $spaces_text = "";

         $field = ($current_report == "press" OR $current_report == "public") ? "space_name" : "space_name_short";
         $sql = "SELECT " . $field . " FROM " . $table_prefix . "spaces WHERE id = :space_id";
         $spaces_count = count($spaces);
         for ($j = 0; $j < $spaces_count; $j++) // build spaces_text
         {
            $sql_values = array("space_id" => $spaces[$j]);
            $sql_prep = $pemdb->prepare($sql);
            if (PEAR::isError($sql_prep)) PEAR_error($sql_prep);
            $result = $sql_prep->execute($sql_values);
            if (PEAR::isError($result)) PEAR_error($result);
            $row = $result->fetchRow(MDB2_FETCHMODE_ASSOC);
            $spaces_text .= $row[$field];
            if ($j < $spaces_count - 1) $spaces_text .= ", ";
         }
         $list[$i]["spaces_text"] = $spaces_text;
      }
   }
   mdb2_disconnect($pemdb);

   echo "results: <pre>";
   print_r($list);
   echo "</pre>";

   foreach ($list AS $this_event)
   {
      $title = (!empty($this_event["date_name"])) ? $this_event["entry_name"] . ': ' . $this_event["date_name"] : $this_event["entry_name"];
      $subject = $settings["reg_subject"] . ' ' . $title;
      $event_body = pem_event_info_prep($this_event);

      $where = array("date_id" => $this_event["id"], "reminder" => 1);
      $reg_list = pem_get_rows("registrants", $where);

      foreach ($reg_list AS $this_reg)
      {
         $to = $this_reg["email"];
         $body = $this_reg["name1"] . ' ' . $this_reg["name2"] . ",\n";
         $body .= $settings["reg_onreg_msg"] . "\n\n\n";

         $body .= $event_body;
         pem_send_mail($to, $body, $subject, $from_address, $from_name);
      }
//      print_r($reg_list);
   }

} // END pem_mail_reg







?>