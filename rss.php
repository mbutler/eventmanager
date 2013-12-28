<?php
/* ========================== FILE INFORMATION =================================
phxEventManager :: rss.php

RSS feed of the upcoming 7 days
============================================================================= */

include_once "pem-config.php";
include_once ABSPATH . "pem-settings.php";

// ===================== ESTABLISH USER ACCESS =============================
$authorized_internal_calendar = pem_user_authorized("Internal Calendar");
$authorized_external_calendar = pem_user_authorized("External Calendar");
$authorized_internal_sidebox = pem_user_authorized("Internal Side Box");
$authorized_external_sidebox = pem_user_authorized("External Side Box");
$authorized_private = pem_user_authorized("View Private");
$authorized_unapproved = pem_user_authorized("View Unapproved");
$authorized_cancelled = pem_user_authorized("View Cancelled");
$authorized_calendar = ($authorized_internal_calendar AND $authorized_external_calendar);
$authorized_sidebox = ($authorized_internal_sidebox AND $authorized_external_sidebox);

// ====================== CURRENT DATE INFORMATION =============================

$current_year = pem_cache_get("current_year");
$current_month = zeropad(pem_cache_get("current_month"), 2);
$current_day = zeropad(pem_cache_get("current_day"), 2);

if (!isset($current_year)) $current_year = pem_date("Y");
if (!isset($current_month)) $current_month = pem_date("m");
if (!isset($current_day)) $current_day = 1;

$current_date = mktime(0, 0, 0, $current_month, $current_day, $current_year);

$week_begin_date = $current_date;
$week_end_date = $current_date + (7 * 86400);

// ====================== BUILD EVENT INFORMATION ==============================

$pemdb =& mdb2_connect($dsn, $options, "connect");
if (PEAR::isError($pemdb)) die($pemdb->getMessage()); // check for error

MDB2::loadFile("Date"); // load Date helper class

unset($where);
$where["status"] = array("!=", "2");
$spacetmp = pem_get_rows("spaces", $where);
foreach ($spacetmp AS $value) $space_list[$value["id"]] = $value;
unset($spacetmp);

$sql = "SELECT * FROM " . $table_prefix . "entries as e, " . $table_prefix . "dates as d WHERE ";
$sql .= "e.id = d.entry_id AND ";
$sql .= "e.entry_status != 2 AND ";
$sql .= "d.date_status != 2 AND ";
$sql .= "d.when_begin <= :when_begin_before AND ";
$sql .= "d.when_end >= :when_end_after";
if ($authorized_calendar) $sql .= " AND (e.entry_type = 1 OR e.entry_type = 2)";
elseif ($authorized_internal_calendar) $sql .= " AND e.entry_type = 1";
elseif ($authorized_external_calendar) $sql .= " AND e.entry_type = 2";
if (!$authorized_private) $sql .= " AND (e.entry_visible_to_public = 1 AND d.date_visible_to_public = 1)";
if (!$authorized_unapproved) $sql .= " AND (e.entry_status != 0 AND d.date_status != 0)";
if (!$authorized_cancelled) $sql .= " AND (e.entry_cancelled != 1 AND d.date_cancelled != 1)";
$sql .= " ORDER BY d.when_begin, d.when_end, e.entry_name";

$day_num = 0;
for ($cday = $week_begin_date; $cday <= $week_end_date; $cday = $cday + 86400)
{
   $dbegin = MDB2_Date::date2Mdbstamp(0, 0, 0, pem_date("m", $cday), pem_date("d", $cday), pem_date("Y", $cday));
   $dend = MDB2_Date::date2Mdbstamp(23, 59, 59, pem_date("m", $cday), pem_date("d", $cday), pem_date("Y", $cday));
   $sql_values = array("when_begin_before" => $dend, "when_end_after" => $dbegin);
   $list = pem_exec_sql($sql, $sql_values);
   for ($i = 0; $i < count($list); $i++)
   {
      if (!empty($list[$i]["spaces"]))
      {
         $spaces_text = "";
         $spacestmp = unserialize($list[$i]["spaces"]);
         $spaces_count = count($spacestmp);
         for ($j = 0; $j < $spaces_count; $j++)
         {
            $spaces_text .= $space_list[$spacestmp[$j]]["space_name"];
            if ($j < $spaces_count - 1) $spaces_text .= ", ";
         }
         $list[$i]["spaces_text"] = $spaces_text;
         $list[$i]["spaces"] = $spacestmp;
      }
      unset($spacestmp);
   }
   $all_events[$day_num] = $list;
   $day_num++;
}

$days_in_month = pem_date("t", $month_begin);
$sql = "SELECT d.id, e.entry_name, d.date_name, e.entry_category, e.entry_status, d.date_status, e.entry_cancelled, d.date_cancelled, e.entry_visible_to_public, d.date_visible_to_public FROM " . $table_prefix . "entries as e, " . $table_prefix . "dates as d WHERE ";
$sql .= "e.id = d.entry_id AND ";
$sql .= "e.entry_status != 2 AND ";
$sql .= "d.date_status != 2 AND ";
$sql .= "d.when_begin <= :when_begin_before AND ";
$sql .= "d.when_end >= :when_end_after";
if ($authorized_sidebox) $sql .= " AND (e.entry_type = 3 OR e.entry_type = 4)";
elseif ($authorized_internal_sidebox) $sql .= " AND e.entry_type = 3";
elseif ($authorized_external_sidebox) $sql .= " AND e.entry_type = 4";
if (!$authorized_private) $sql .= " AND (e.entry_visible_to_public = 1 AND d.date_visible_to_public = 1)";
if (!$authorized_unapproved) $sql .= " AND (e.entry_status != 0 AND d.date_status != 0)";
if (!$authorized_cancelled) $sql .= " AND (e.entry_cancelled != 1 AND d.date_cancelled != 1)";
$sql .= " ORDER BY e.entry_name";
$dbegin = MDB2_Date::date2Mdbstamp(0, 0, 0, $current_month, 1, $current_year);
$dend = MDB2_Date::date2Mdbstamp(23, 59, 59, $current_month, $days_in_month, $current_year);
$sql_values = array("when_begin_before" => $dend, "when_end_after" => $dbegin);
$unscheduled_list = pem_exec_sql($sql, $sql_values);

mdb2_disconnect($pemdb);

// =============================================================================
// ============================= RSS FEED HEADING ==============================
// =============================================================================

header("Content-Type: application/xml; charset=ISO-8859-1");
echo '<rss version="2.0">' . "\n";
echo '<channel>' . "\n";
echo '<title>' . $pem_title . ' - ' . pem_simplify_dates($week_begin_date, $week_end_date) . '</title>' . "\n";
echo '<link>' . $pem_url . '</link>' . "\n";
echo '<description>Events coming up over the next seven days</description>' . "\n";
echo '<language>en-us</language>' . "\n";
echo '<copyright>Copyright (c) 2009 ' . $pem_title . '</copyright>' . "\n";
echo '<webMaster>webmaster@icpl.org</webMaster>' . "\n";
echo '<pubDate>';
echo date("D, j M Y H:i:s T");
echo '</pubDate>' . "\n";
echo '<ttl>60</ttl>' . "\n";

// =============================================================================
// ============================ BEGIN LIST DISPLAY =============================
// =============================================================================

$cat_list = pem_get_rows("categories");
for ($i = 0; $i < count($cat_list); $i++)
{
   $categories[$cat_list[$i]["id"]] = $cat_list[$i]["category_name"];
}
$today_check = strtotime("now");
$day_stamp = $week_begin_date;
for ($cday = 0; $cday < 7; $cday++)
{
   // Check for events this day
   if (isset($all_events[$cday][0]))
   {
      $event_count = count($all_events[$cday]);
      for ($i = 0; $i < $event_count; $i++)
      {
         $this_event = $all_events[$cday][$i];
         echo '<item>' . "\n";
         echo '<title>';
         if ($this_event["entry_cancelled"] OR $this_event["date_cancelled"])
         {
            echo "[" . __("CANCELLED") . "] ";
         }
         $full_name = (!empty($this_event["date_name"])) ? $this_event["entry_name"] . ': ' . $this_event["date_name"] : $this_event["entry_name"];
         echo $full_name;
         if (!$this_event["entry_status"] OR !$this_event["date_status"])
         {
            echo ' (unapproved)';
         }
         echo '</title>' . "\n";
         echo '<link>' . $pem_url . 'view.php?did=' . $this_event["id"] . '&amp;d=' . $cday . '&amp;m=' . $current_month . '&amp;y=' . $current_year . '</link>' . "\n";
         echo '<description>' . "\n";
         echo __("Location:") . ' ' . $this_event["spaces_text"] . "\n";
         echo __("Time:") . ' ';
         if (!$this_event["allday"])
         {
            echo pem_date($time_format, strtotime($this_event["when_begin"]));
            echo " - ";
            echo pem_date($time_format, strtotime($this_event["when_end"]));
         }
         else echo __("All Day");
         echo "\n";

         $dmeta = unserialize($list[$i]["date_meta"]);
         $meta_text = "";
         $meta_contacts = "";
         if (is_array($dmeta)) foreach($dmeta AS $meta_id => $meta)
            {
               if ($meta["type"] == "textinput")
               {
                  $data = pem_get_row("id", $meta_id, "meta");
                  $meta_data = unserialize($data["value"]);
                  if (!empty($meta["data"])) $meta_text .= $meta_data["input_label"] . ' ' . $meta["data"] . "\n";
               }
               if ($meta["type"] == "contact")
               {
                  $data = pem_get_row("id", $meta_id, "meta");
                  $meta_data = unserialize($data["value"]);
                  if (!empty($meta["name1"])) $meta_contacts .= $meta_data["name1"][0] . ' ' . $meta["name1"] . "\n";
                  if (!empty($meta["name2"])) $meta_contacts .= $meta_data["name2"][0] . ' ' . $meta["name2"] . "\n";
                  if (!empty($meta["phone1"])) $meta_contacts .= $meta_data["phone1"][0] . ' ' . $meta["phone1"] . "\n";
                  if (!empty($meta["phone2"])) $meta_contacts .= $meta_data["phone2"][0] . ' ' . $meta["phone2"] . "\n";
                  if (!empty($meta["email"])) $meta_contacts .= $meta_data["email"][0] . ' ' . $meta["email"] . "\n";
               }
            }
         $emeta = unserialize($list[$i]["entry_meta"]);
         if (is_array($emeta)) foreach($emeta AS $meta_id => $meta)
            {
               if ($meta["type"] == "textinput")
               {
                  $data = pem_get_row("id", $meta_id, "meta");
                  $meta_data = unserialize($data["value"]);
                  if (!empty($meta["data"]))  $meta_text .= $meta_data["input_label"] . ' ' . $meta["data"] . "\n";
               }
               if ($meta["type"] == "contact")
               {
                  $data = pem_get_row("id", $meta_id, "meta");
                  $meta_data = unserialize($data["value"]);
                  if (!empty($meta["name1"])) $meta_contacts .= $meta_data["name1"][0] . ' ' . $meta["name1"] . "\n";
                  if (!empty($meta["name2"])) $meta_contacts .= $meta_data["name2"][0] . ' ' . $meta["name2"] . "\n";
                  if (!empty($meta["phone1"])) $meta_contacts .= $meta_data["phone1"][0] . ' ' . $meta["phone1"] . "\n";
                  if (!empty($meta["phone2"])) $meta_contacts .= $meta_data["phone2"][0] . ' ' . $meta["phone2"] . "\n";
                  if (!empty($meta["email"])) $meta_contacts .= $meta_data["email"][0] . ' ' . $meta["email"] . "\n";
               }
            }
         if (!empty($meta_text)) echo $meta_text;
         if (!empty($meta_contacts)) echo $meta_contacts;
         if (!empty($this_event["entry_description"]) OR !empty($this_event["date_description"]))
         {
            echo __("Description:") . ' ';
            if (!empty($this_event["entry_description"])) echo $this_event["entry_description"] . "\n";
            if (!empty($this_event["date_description"])) echo $this_event["date_description"] . "\n";
         }
         echo ($list[$i]["entry_open_to_public"] AND $list[$i]["date_open_to_public"]) ? __("Open to the public.") : __("Closed to the public.");
         echo "\n" . '</description>' . "\n";
         echo '<category>' . $categories[$this_event["entry_category"]] . '</category>' . "\n";
         echo '<pubDate>';
         echo date("D, j M Y H:i:s T", strtotime($this_event["when_begin"]));
         echo '</pubDate>' . "\n";
         echo '</item>' . "\n\n";
      }
      $day_stamp = $day_stamp + 86400;
   }
} // END loop through week's days
echo '</channel>' . "\n";
echo '</rss>';
?>