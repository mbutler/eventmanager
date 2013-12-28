<?php
/* ========================== FILE INFORMATION =================================
phxEventManager :: month.php

Main public view of content withing a given month period.
============================================================================= */

//if ($debug_flag)
//  echo "<p>DEBUG: month=$month year=$year start=$weekday_begin range=$month_begin:$month_end\n";

// Used below: localized "all day" text but with non-breaking spaces:
$all_day = ereg_replace(" ", "&nbsp;", __("All day"));

// ====================== ESTABLISH VIEW SETTINGS ==============================
$view_type = pem_cache_get("current_view");
$view_format = pem_cache_get("current_format");
switch (true)
{
case ($view_type == "day" AND $view_format == "calendar"):
   $view_id = 1;
   break;
case ($view_type == "day" AND $view_format == "list"):
   $view_id = 2;
   break;
case ($view_type == "week" AND $view_format == "calendar"):
   $view_id = 3;
   break;
case ($view_type == "week" AND $view_format == "list"):
   $view_id = 4;
   break;
case ($view_type == "month" AND $view_format == "calendar"):
   $view_id = 5;
   break;
case ($view_type == "month" AND $view_format == "list"):
   $view_id = 6;
   break;
case ($view_type == "year" AND $view_format == "calendar"):
   $view_id = 7;
   break;
case ($view_type == "year" AND $view_format == "list"):
   $view_id = 8;
   break;
}
$view_settings = pem_get_row("id", $view_id, "views");

// print_r($view_settings);

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


// ===================== ESTABLISH CATEGORY COLORS =============================
$cat_list = pem_get_rows("categories");
for ($i = 0; $i < count($cat_list); $i++)
{
   $categories[$cat_list[$i]["id"]] = $cat_list[$i]["category_color"];
}

// ====================== CURRENT DATE INFORMATION =============================

if (!isset($current_date))
{
   $current_year = pem_cache_get("current_year");
   $current_month = zeropad(pem_cache_get("current_month"), 2);
   $current_day = zeropad(pem_cache_get("current_day"), 2);
   $current_date = $current_year . "-" . $current_month . "-" . $current_day;
}

if ($current_month == 01)
{
   $previous_month = 12;
   $previous_year = $current_year - 1;
}
else
{
	$previous_month = zeropad($current_month - 1, 2);
   $previous_year = $current_year;
}
if ($current_month == 12)
{
   $next_month = 01;
   $next_year = $current_year + 1;
}
else
{
	$next_month = zeropad(pem_cache_get("current_month") + 1, 2);
   $next_year = $current_year;
}


if (!isset($current_year)) $current_year = pem_date("Y");
if (!isset($current_month)) $current_month = pem_date("m");
if (!isset($current_day)) $current_day = 1;

$month_begin = mktime(0, 0, 0, $current_month, 1, $current_year);
$days_in_month = pem_date("t", $month_begin);
$month_end = mktime(23, 59, 59, $current_month, $days_in_month, $current_year);

// Set column the month starts in: 0 means $week_begin weekday.
$weekday_begin = (pem_date("w", $month_begin) - $week_begin + 7) % 7;


// =============================================================================
// ====================== BUILD EVENT INFORMATION ==============================
// =============================================================================
// possible limits of area, space, and/or category

$pemdb =& mdb2_connect($dsn, $options, "connect");

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

$duration_check_date = strtotime ('-' . $display_duration . ' month', strtotime("now")) ;
for ($day_num = 1; $day_num <= $days_in_month; $day_num++)
{
   $dbegin = MDB2_Date::date2Mdbstamp(0, 0, 0, $current_month, $day_num, $current_year);
   $dend = MDB2_Date::date2Mdbstamp(23, 59, 59, $current_month, $day_num, $current_year);
   $dbegin_check_date = strtotime ($dbegin) ;
   if ($duration_check_date < $dbegin_check_date)
   {
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
   }
   else
   {
      $list = array();
   }
   $all_events[$day_num] = $list;
}

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
$dbegin_check_date = strtotime ($dbegin) ;
if ($duration_check_date > $dbegin_check_date) $dbegin = MDB2_Date::date2Mdbstamp(0, 0, 0, date('m', $duration_check_date), date('d', $duration_check_date), date('Y', $duration_check_date));
$dend_check_date = strtotime ($dend) ;
if ($duration_check_date > $dend_check_date) $dend = MDB2_Date::date2Mdbstamp(23, 59, 59, date('m', $duration_check_date), date('d', $duration_check_date), date('Y', $duration_check_date));
$sql_values = array("when_begin_before" => $dend, "when_end_after" => $dbegin);
$unscheduled_list = pem_exec_sql($sql, $sql_values);

mdb2_disconnect($pemdb);

// =============================================================================
// ======================== VIEW HEADING AND CONTROLS ==========================
// =============================================================================
echo '<div id="view-month">' . "\n";

echo '<div class="mtitle">' . "\n";
echo '<div id="previous"><a href="'  . $PHP_SELF . '?y=' . $previous_year . '&amp;m=' . $previous_month . '"><span>' . __("&laquo; Previous") . '</span></a></div>' . "\n";
echo '<div id="next"><a href="'  . $PHP_SELF . '?y=' . $next_year . '&amp;m=' . $next_month . '"><span>' . __("Next &raquo;") . '</span></a></div>' . "\n";
echo '<h1 class="date">' . $month["$current_month"] . ' ' . $current_year . ' - ';
$current_cats = pem_cache_get("current_categories");

for ($i = 0; $i < count($current_cats); $i++)
{
   foreach ($cat_list as $this_cat)
   {
   	if ($this_cat["id"] == $current_cats[$i]) echo $this_cat["category_name"];
   }
   if ($i < count($current_cats) - 1) echo ", ";
}
echo ' - ';

$current_allspaces = pem_cache_get("current_spaces_all");
$current_spaces = pem_cache_get("current_spaces");

if ($current_allspaces) echo __("All Locations");
else
{
   for ($i = 0; $i < count($current_spaces); $i++)
   {
      echo $space_list[$current_spaces[$i]]["space_name_short"];
      if ($i < count($current_spaces) - 1) echo ", ";
   }
}
echo '</h1>' . "\n";
echo '</div>' . "\n"; // END mtitle


// =============================================================================
// ============================ BEGIN LIST DISPLAY =============================
// =============================================================================

if ($view_format == "list")
{
   echo '<table border="0" cellspacing="0" cellpadding="0" class="ltable"><tr>' . "\n";
   // Draw the days of the month:
   $today_check = pem_date('m') . pem_date('j') . pem_date('Y');
   for ($cday = 1; $cday <= $days_in_month; $cday++)
   {
      $day_stamp = strtotime($current_month . '/' . $cday . '/' . $current_year);
      if (($view_settings["highlight_today"]) AND ($today_check == $current_month . $cday . $current_year))
      {
         echo '<td class="lhighlight">';
      }
      else
      {
         if ((pem_date("w", $day_stamp) == 0) OR (pem_date("w", $day_stamp) == 6))
         {
            echo '<td class="lweekend">';
         }
         else
         {
            echo '<td>';
         }
      }

      echo '<div class="lhead">';
//    if AUTHORIZED TO ADD A NEW EVENT
//    {
         echo '<a href="/add-event.php?';
         echo "t=scheduled";
         echo "&amp;y=$current_year";
         echo "&amp;m=$current_month";
         echo "&amp;d=" . zeropad($cday, 2);
         echo '" class="maddbutton" title="' . __("Add New Event") . '"><img src="/pem-themes/' . $pem_theme . '/new.gif" alt="Add Event" /></a>' . "\n";
//    }

      echo '<a class="ldate" href="index.php?v=day&amp;y=' . $current_year . '&amp;m=' . $current_month . '&amp;d=' . $cday . '" title="' . __("View Day") . '">';
      echo pem_date("l, " . $date_format, $day_stamp);
      echo '</a>' . "\n";

      echo '</div>' . "\n";

      // Check for events this day
      if (isset($all_events[$cday][0]))
      {
         $event_count = count($all_events[$cday]);
         for ($i = 0; $i < $event_count; $i++)
         {
            if ($i != 0 AND $i == $view_settings["max_listings"]) // stop at the limit and display "[more]" linked to the day view
            {
               echo '<tr><td></td><td><a href="index.php?v=day&amp;y='.$current_year.'&m='.$current_month.'&d='.$cday.$buildstring2.'" title="' . __("View Day") . '">' . __("[more]") . '</a></td></tr>' . "\n";
               break;
            }

            $this_event = $all_events[$cday][$i];
            $cat = (isset($this_event["date_category"])) ? $this_event["date_category"] : $this_event["entry_category"];
            if (empty($cat)) $cat = 1;  // default color if no others set.

            // Apply filters to event
            if ($current_allspaces) $event_space_valid = true;
            else
            {
               $space_check = array_intersect($this_event["spaces"], $current_spaces);
               if (count($space_check) > 0) $event_space_valid = true;
               else $event_space_valid = false;
            }
            if ($event_space_valid AND ( // event category is in filters
                  in_array(1, $current_cats) OR
                  in_array($cat, $current_cats) OR
                  (in_array(100, $current_cats) AND (!$this_event["entry_status"] OR !$this_event["date_status"])) OR
                  (in_array(101, $current_cats) AND ($this_event["entry_cancelled"] OR $this_event["date_cancelled"])) OR
                  (in_array(102, $current_cats) AND (!$this_event["entry_visible_to_public"] OR !$this_event["date_visible_to_public"]))
               ))
            {
               echo '<div class="levent">';
               if (!$this_event["entry_status"] OR !$this_event["date_status"])
               {
                  echo '<a class="unapproved"';
               }
               elseif ($this_event["entry_cancelled"] OR $this_event["date_cancelled"])
               {
                  echo '<a class="cancelled" style="color:#' . $categories[$cat] . ';"';
               }
               elseif (!$this_event["entry_visible_to_public"] OR !$this_event["date_visible_to_public"])
               {
                  echo '<a class="private"';
               }
               else
               {
                  echo '<a style="color:#' . $categories[$cat] . ';"';
               }
               echo ' href="view.php?did=' . $this_event["id"] . '&amp;d=' . $cday . '&amp;m=' . $current_month . '&amp;y=' . $current_year . '">';
               echo '<div class="ltime">';
               if (!$this_event["allday"])
               {
                  if ($view_settings["show_time_begin"]) echo pem_date($time_format, strtotime($this_event["when_begin"]));
                  if ($view_settings["show_time_end"])
                  {
                     if ($view_settings["show_time_begin"]) echo " - ";
                     echo pem_date($time_format, strtotime($this_event["when_end"]));
                  }
               }
               else echo __("All Day");
               echo '</div>';
               if ($this_event["entry_cancelled"] OR $this_event["date_cancelled"])
               {
                  echo "[" . __("CANCELLED") . "] ";
               }
               if ($view_settings["show_event_name"])
               {
//TODO HACK - date name added automatically
                  $full_name = (!empty($this_event["date_name"])) ? $this_event["entry_name"] . ': ' . $this_event["date_name"] : $this_event["entry_name"];
                  if ((!empty($view_settings["event_name_length"])) AND (strlen($full_name) > $view_settings["event_name_length"]))
                  {
                     echo " " . substr($full_name, 0, $view_settings["event_name_length"]) . __("...");
                  }
                  else
                  {
                     echo " " . $full_name;
                  }
               }
               if ($this_event["allday"]) echo '</b>';
               if (($view_settings["show_area"]) OR ($view_settings["show_space"]))
               {
                  echo "; <b>" . __("Location:") . "</b> ". $this_event["spaces_text"];
               }
               echo '</a></div>' . "\n";
            }
         } // END loop of day's events
      } // if day has events
      else
      {
         echo '<div class="lnone">' . __("There are no public events currently scheduled for this day.") . '</div>';
      }
      echo '</td></tr>' . "\n";
   } // END loop through month's days

   echo '</tr></table>' . "\n";

} // END List View

// =============================================================================
// ======================== BEGIN CALENDAR DISPLAY =============================
// =============================================================================

if ($view_format == "calendar")
{
   echo '<table border="0" cellspacing="0" cellpadding="0" class="mtable"><tr>' . "\n";
   // Weekday name header row:
   for ($i = 0; $i < 7; $i++)
   {
      echo '<th>' . $weekday[($i + $week_begin)%7] . '</th>' . "\n";
   }
   echo '</tr><tr>' . "\n";

   // Skip days in week before start of month:
   for ($weekcol = 0; $weekcol < $weekday_begin; $weekcol++)
   {
      echo '<td class="mspace">&nbsp;</td>' . "\n";
   }

   // Draw the days of the month:
   $today_check = pem_date('m') . pem_date('j') . pem_date('Y');
   for ($cday = 1; $cday <= $days_in_month; $cday++)
   {
      if ($weekcol == 0) echo '</tr><tr>' . "\n";
      if (($view_settings["highlight_today"]) AND ($today_check == $current_month . $cday . $current_year))
      {
         echo '<td class="mhighlight">';
      }
      else
      {
         $day_stamp = strtotime($current_month . '/' . $cday . '/' . $current_year);
         if ((pem_date('w', $day_stamp) == 0) OR (pem_date('w', $day_stamp) == 6))
         {
            echo '<td class="mweekend">';
         }
         else
         {
            echo '<td>';
         }
      }

      echo '<a href="index.php?v=day&amp;y=' . $current_year . '&amp;m=' . $current_month . '&amp;d=' . $cday . '" class="mdate" title="' . __("View Day") . '">' . $cday . '</a>' . "\n";

//    if AUTHORIZED TO ADD A NEW EVENT
//    {
         echo '<a href="/add-event.php?';
         echo "t=scheduled";
         echo "&amp;y=$current_year";
         echo "&amp;m=$current_month";
         echo "&amp;d=" . zeropad($cday, 2);
         echo '" class="maddbutton" title="' . __("Add New Event") . '"><img src="/pem-themes/' . $pem_theme . '/new.gif" alt="Add Event" /></a>' . "\n";
//    }

      // Check for events this day
      if (isset($all_events[$cday][0]))
      {
         $event_count = count($all_events[$cday]);
         for ($i = 0; $i < $event_count; $i++)
         {
            if ($i != 0 AND $i == $view_settings["max_listings"]) // stop at the limit and display "[more]" linked to the day view
            {
               echo '<div><a href="index.php?v=day&amp;y=' . $current_year . '&m=' . $current_month . '&d=' . $cday . '" title="' . __("View Day") . '">' . __("[more]") . '</a></div>' . "\n";
               break;
            }


            $this_event = $all_events[$cday][$i];
            $cat = (isset($this_event["date_category"])) ? $this_event["date_category"] : $this_event["entry_category"];
            if (empty($cat)) $cat = 1;  // default color if no others set.

            // Apply filters to event
            if ($current_allspaces) $event_space_valid = true;
            else
            {
            	if (is_array($this_event["spaces"]))
               {
                  $space_check = array_intersect($this_event["spaces"], $current_spaces);
                  if (count($space_check) > 0) $event_space_valid = true;
                  else $event_space_valid = false;
               }
               else $event_space_valid = false;
            }
            if ($event_space_valid AND ( // event category is in filters
                  in_array(1, $current_cats) OR
                  in_array($cat, $current_cats) OR
                  (in_array(100, $current_cats) AND (!$this_event["entry_status"] OR !$this_event["date_status"])) OR
                  (in_array(101, $current_cats) AND ($this_event["entry_cancelled"] OR $this_event["date_cancelled"])) OR
                  (in_array(102, $current_cats) AND (!$this_event["entry_visible_to_public"] OR !$this_event["date_visible_to_public"]))
               ))
            {
               echo '<div class="mevent">';
               if (!$this_event["entry_status"] OR !$this_event["date_status"])
               {
                  echo '<a class="unapproved"';
               }
               elseif ($this_event["entry_cancelled"] OR $this_event["date_cancelled"])
               {
                  if (empty($cat)) $cat = 1;  // default color if no others set.
                  echo '<a class="cancelled" style="color:#' . $categories[$cat] . ';"';
               }
               elseif (!$this_event["entry_visible_to_public"] OR !$this_event["date_visible_to_public"])
               {
                  echo '<a class="private"';
               }
               else
               {
                  if (empty($cat)) $cat = 1;  // default color if no others set.
                  echo '<a style="color:#' . $categories[$cat] . ';"';
               }
               echo ' href="view.php?did=' . $this_event["id"] . '&amp;d=' . $cday . '&amp;m=' . $current_month . '&amp;y=' . $current_year . '">';
               echo '<b>';
               if (!$this_event["allday"])
               {
                  if ($view_settings["show_time_begin"]) echo pem_date($time_format, strtotime($this_event["when_begin"]));
                  if ($view_settings["show_time_end"])
                  {
                     if ($view_settings["show_time_begin"]) echo "-";
                     echo pem_date($time_format, strtotime($this_event["when_end"]));
                  }
                  echo '</b>';
               }
               if ($this_event["entry_cancelled"] OR $this_event["date_cancelled"])
               {
                  echo " [" . __("CANCELLED") . "] ";
               }
               if ($view_settings["show_event_name"])
               {
// TODO HACK
                  $full_name = (!empty($this_event["date_name"])) ? $this_event["entry_name"] . ': ' . $this_event["date_name"] : $this_event["entry_name"];
                  if ((!empty($view_settings["event_name_length"])) AND (strlen($full_name) > $view_settings["event_name_length"]))
                  {
                     echo " " . substr($full_name, 0, $view_settings["event_name_length"]) . __("...");
                  }
                  else
                  {
                     echo " " . $full_name;
                  }
               }
               if ($this_event["allday"]) echo '</b>';
               if (($view_settings["show_area"]) OR ($view_settings["show_space"]))
               {
                  echo "; " . __("Location:") . " ";
               }
               echo '</a></div>' . "\n";
            } // END in_array($cat, $current_cats)
         } // END loop of day's events
      } // if day has events

      echo '</td>' . "\n";
      if (++$weekcol == 7) $weekcol = 0;
   } // END loop through month's days

   // Skip from end of month to end of week:
   if ($weekcol > 0) for (; $weekcol < 7; $weekcol++)
   {
      echo '<td class="mspace">&nbsp;</td>' . "\n";
   }
   echo '</tr></table>' . "\n";
} // END Calendar View

echo '</div>' . "\n"; // END view-month

// =============================================================================
// ======================== BEGIN SIDEBAR DISPLAY =============================
// =============================================================================

echo '<div id="sidebar-month">' . "\n";

if (isset($unscheduled_list))
{
   echo '<div id="unscheduled-box">' . "\n";
   echo '<h4>' . __("Library Displays") . '</h4>';
   pem_unscheduled($unscheduled_list);

   if (pem_user_authorized(array(
      "Internal Side Box" => array("Edit Own", "Edit Others", "Edit All", "Approve Own", "Approve Others", "Approve All", "Delete Own", "Delete Others", "Delete All"),
      "External Side Box" => array("Edit Own", "Edit Others", "Edit All", "Approve Own", "Approve Others", "Approve All", "Delete Own", "Delete Others", "Delete All"),
   )))
   {
      echo '<div class="inlinebutton"><a href="' . $pem_url . 'add-event.php?t=unscheduled" title="' . __("Add Side Event") . '"';
      echo '><span>' . __("Add Side Event") . '</span></a></div>' . "\n";
   }

   echo '</div>' . "\n"; // END unscheduled-box
   echo '<br />' . "\n";
}


echo '<div id="legend-box">' . "\n";
echo '<h4>' . __("Color Key") . '</h4>' . "\n";
for ($i = 1; $i < count($cat_list); $i++)
{
   echo '<div><div class="key" style="background-color:#' . $cat_list[$i]["category_color"] . '"></div>';
   echo '<span class="label">' . $cat_list[$i]["category_name"] . '</span></div><br />' . "\n";

}
echo '</div>' . "\n"; // END legend-box
echo '<br />' . "\n";


include_once ABSPATH . PEMINC . "/class-minicals.php";

//Draw the three month calendars
if ($view_settings["show_minical"] == 1)
{
   $minical_format = ($view_settings["minical_format"] == 1) ? "horizantal" : "vertical";
   $minical_size = ($view_settings["minical_size"] == 1) ? "large" : "small";
   $highlight_today = ($view_settings["highlight_today_minical"] == 1) ? true : false;

   minicals($current_year, $current_month, $current_day, $minical_format, $minical_size, $highlight_today);
}

echo '<div id="sidebar-message">' . "\n";
echo $sidebar_text;
echo '</div>' . "\n"; // END sidebar-message

echo '</div>' . "\n"; // END sidebar-month

?>