<?php
/* ========================== FILE INFORMATION =================================
phxEventManager :: day.php


============================================================================= */

// include "includes/mincals.inc";

/*

if ($enable_periods) {
   $resolution = 60;
   $morningstarts = 12;
   $eveningends = 12;
   $eveningends_minutes = count($periods)-1;
   }
*/

/*
if ($pview != 1) {
   echo '<table cellspacing="0" cellpadding="0" border="0" style="width:100%;"><tr><td valign="top" style="width:100%; height:2px;">';

   echo '</td><td valign="top" rowspan="2">';
   echo '<img src="images/space.gif" style="width:130px; height:1px;" />';


   $calview = 'month';
   // ======================== UNSCHEDULED SIDE BOX ======================================================
   include "includes/box-unscheduled.inc";

   // ======================== CATEGORY SIDE BOX =============================================
   include "includes/box-category.inc";

   // ======================== AREA/ROOM SIDE BOXES =============================================
   include "includes/box-area-room.inc";

   // ======================== MINI CALS =============================================
   include "includes/box-minical.inc";

   echo '<img src="images/space.gif" style="width:130px; height:1px;" />';

   // ======================== SIDE NOTE =============================================
   include "includes/sidenote.inc";


   echo '</td></tr><tr><td valign="top" style="width:100%;">';
   }
*/


// ======================================================
// ========= MAIN VIEW STARTS ===========================
// ======================================================



// Show Month, Year, Area, Room header:
/*
echo '<h2 style="text-align:center;">' . utf8_strftime("%B %Y", $month_begin);
if ($view_settings["show_area_box"] == 1) echo " - $selected_area_name";
if ($view_settings["show_room_box"] == 1) echo " - $selected_room_name";
echo "</h2>\n";
*/



// Show Go to month before and after links
//y? are year and month of the previous month.
//t? are year and month of the next month.

/*
$i= mktime(12,0,0,$month-1,1,$year);
$yy = date("Y",$i);
$ym = date("n",$i);

$i= mktime(12,0,0,$month+1,1,$year);
$ty = date("Y",$i);
$tm = date("n",$i);
*/

/*
echo '<div class="buttons">';

echo '<table cellspacing="0" cellpadding="0" border="0"><tr><td><img src="/images/button-left.gif" alt="left"><td><td class="button">';
if ($staffcheck)
   echo '<a href="/admin/add-entry.php">Book a Meeting Room</a>';
else
   echo '<a href="/submit-entry.php">Request a Meeting Room</a>';
echo '</td><td><img src="/images/button-right.gif" alt="right"></td></tr></table>';

echo '<table cellspacing="0" cellpadding="0" border="0"><tr><td><img src="/images/button-left.gif" alt="left"><td><td class="button">';
if ($list)
   echo '<a href="'.basename($PHP_SELF).$buildstring.'">' . __("List View") . '</a>';
else
   echo '<a href="'.basename($PHP_SELF).$buildstring.'&list=1'.'">' . __("List View") . '</a>';
echo '</td><td><img src="/images/button-right.gif" alt="right"></td></tr></table>';

echo '</div>';
*/



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

$current_year = pem_cache_get("current_year");
$current_month = zeropad(pem_cache_get("current_month"), 2);
$current_day = zeropad(pem_cache_get("current_day"), 2);

if (!isset($current_year)) $current_year = pem_date("Y");
if (!isset($current_month)) $current_month = pem_date("m");
if (!isset($current_day)) $current_day = 1;

$current_date = mktime(0, 0, 0, $current_month, $current_day, $current_year);

$previous_date = $current_date - (1 * 86400);
$next_date = $current_date + (1 * 86400);

$day_begin_time = $current_date;
$day_end_time = $current_date + (1 * 86400);

$view_increment = 30;
$space_increment = 5;


// ===================== ESTABLISH SCHEDULE BOUNDARIES =============================

   $sql = "SELECT profile FROM " . $table_prefix . "scheduling_profiles WHERE ";
   $sql .= "date_begin <= :current_date AND ";
   $sql .= "date_end >= :current_date";
   $sql_values = array("current_date" => pem_date("Y-m-d", $current_date));
   $sql_prep = $pemdb->prepare($sql);
   if (PEAR::isError($sql_prep)) $error .= $sql_prep->getMessage() . ', ' . $sql_prep->getDebugInfo();
   $result = $sql_prep->execute($sql_values);
   if (PEAR::isError($result)) $error .= $result->getMessage() . ', ' . $result->getDebugInfo();
   $schedule_row = $result->fetchRow(MDB2_FETCHMODE_ASSOC);
   if (!empty($schedule_row["profile"]))
   {
      $when_weekday = pem_date("w", $current_date);
      $schedule_profile = unserialize($schedule_row["profile"]);
      $open_begin = $schedule_profile["open_begin_" . $when_weekday];
      $open_end = $schedule_profile["open_end_" . $when_weekday];

      $open_begin = strtotime(pem_date("Y-m-d", $current_date) . " " . $open_begin);
      $open_end = strtotime(pem_date("Y-m-d", $current_date) . " " . $open_end);
    }




// =============================================================================
// ======================== VIEW HEADING AND CONTROLS ==========================
// =============================================================================


echo '<div class="dtitle">' . "\n";
echo '<div id="previous"><a href="'  . $PHP_SELF . '?y=' . pem_date("Y", $previous_date) . '&amp;m='.pem_date("m", $previous_date) . '&amp;d=' . pem_date("d", $previous_date) . '"><span>' . __("&laquo; Previous") . '</span></a></div>' . "\n";
echo '<div id="next"><a href="'  . $PHP_SELF . '?y=' . pem_date("Y", $next_date) . '&amp;m='.pem_date("m", $next_date) . '&amp;d=' . pem_date("d", $next_date) . '"><span>' . __("Next &raquo;") . '</span></a></div>' . "\n";
echo '<h1 class="date">' . pem_date("l, " . $date_format, $current_date) . ' - ' . "\n";

$current_cats = pem_cache_get("current_categories");
for ($i = 0; $i < count($current_cats); $i++)
{
   echo $cat_list[$current_cats[$i]-1]["category_name"];
   if ($i < count($current_cats) - 1) echo ", ";
}
if (pem_cache_get("current_format") == "list")
{
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
}
echo '</h1>' . "\n";
echo '</div>' . "\n"; // END dtitle


// print_r($view_settings);


// =============================================================================
// ============================ BEGIN LIST DISPLAY =============================
// =============================================================================

// ====================== BUILD EVENT INFORMATION ==============================
// possible limits of area, space, and/or category

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
$dbegin = MDB2_Date::date2Mdbstamp(0, 0, 0, $current_month, $current_day, $current_year);
$dend = MDB2_Date::date2Mdbstamp(23, 59, 59, $current_month, $current_day, $current_year);
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
$dbegin = MDB2_Date::date2Mdbstamp(0, 0, 0, $current_month, $current_day, $current_year);
$dend = MDB2_Date::date2Mdbstamp(23, 59, 59, $current_month, $current_day, $current_year);
$sql_values = array("when_begin_before" => $dend, "when_end_after" => $dbegin);
$unscheduled_list = pem_exec_sql($sql, $sql_values);

mdb2_disconnect($pemdb);

// ====================== DISPLAY LIST ==============================

if ($view_format == "list")
{
   echo '<table border="0" cellspacing="0" cellpadding="0" class="ltable"><tr>' . "\n";
   echo '<td>';

   echo '<div class="lhead">';
// if AUTHORIZED TO ADD A NEW EVENT
// {
      echo '<a href="/add-event.php?';
      echo "&amp;y=" . pem_date("Y", $day_stamp);
      echo "&amp;m=" . pem_date("m", $day_stamp);
      echo "&amp;d=" . pem_date("d", $day_stamp);
      echo '" class="waddbutton" title="' . __("Add New Event") . '"><img src="/pem-themes/' . $pem_theme . '/new.gif" alt="Add Event" /></a>' . "\n";
// }

//   echo '<a class="ldate" href="index.php?n=day&amp;y=' . pem_date("Y", $day_stamp) . '&amp;m=' . pem_date("m", $day_stamp) . '&amp;d=' . pem_date("d", $day_stamp) . '" title="' . __("View Day") . '">';
//   echo pem_date("l, " . $date_format, $day_stamp);
//   echo '</a>' . "\n";

   echo '</div>' . "\n";

   // Check for events this day
   if (isset($list[0]))
   {
      $event_count = count($list);
      for ($i = 0; $i < $event_count; $i++)
      {
         if ($i != 0 AND $i == $view_settings["max_listings"]) // stop at the limit and display "[more]" linked to the day view
         {
            echo '<tr><td></td><td><a href="index.php?n=day&amp;y=' . pem_date("Y", $day_stamp) . '&m='.pem_date("m", $day_stamp) . '&d=' . pem_date("d", $day_stamp) . '" title="' . __("View Day") . '">' . __("[more]") . '</a></td></tr>' . "\n";
            break;
         }

         $this_event = $list[$i];
         $cat = (isset($this_event["date_category"])) ? $this_event["date_category"] : $this_event["entry_category"];
         if (empty($cat)) $cat = 1;  // default color if no others set.
//  print_r($this_event);

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
            if (0 == $this_event["entry_status"] OR 0 == $this_event["date_status"])
            {
               echo '<a class="unapproved"';
            }
            elseif ($this_event["entry_cancelled"] OR $this_event["date_cancelled"])
            {
               $cat = (isset($this_event["date_category"])) ? $this_event["date_category"] : $this_event["entry_category"];
               if (empty($cat)) $cat = 1;  // default color if no others set.
               echo '<a class="cancelled" style="color:#' . $categories[$cat] . ';"';
            }
            elseif (!$this_event["entry_visible_to_public"] OR !$this_event["date_visible_to_public"])
            {
               echo '<a class="private"';
            }
            else
            {
               $cat = (isset($this_event["date_category"])) ? $this_event["date_category"] : $this_event["entry_category"];
               if (empty($cat)) $cat = 1;  // default color if no others set.
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
            if (($view_settings["show_area"]) OR ($view_settings["show_space"]))
            {
              echo "; <b>" . __("Location:") . "</b> ". $this_event["spaces_text"];
            }
            echo '</a></div>' . "\n";
         }
      }
   } // if day has events
   else
   {
      echo '<div class="lnone">' . __("There are no public events currently scheduled for this day.") . '</div>';
   }
   echo '</td>' . "\n";
   echo '</tr></table>' . "\n";

// =============================================================================
// ======================== BEGIN SIDEBAR DISPLAY =============================
// =============================================================================

echo '<div id="sidebar-day-list">' . "\n";


if (isset($unscheduled_list))
{
   echo '<div id="unscheduled-box" style="float:left;">' . "\n";
   echo '<h4>' . __("Library Displays") . '</h4>';
   pem_unscheduled($unscheduled_list);
   if (pem_user_authorized(array(
      "Internal Side Box" => array("Edit Own", "Edit Others", "Edit All", "Approve Own", "Approve Others", "Approve All", "Delete Own", "Delete Others", "Delete All"),
      "External Side Box" => array("Edit Own", "Edit Others", "Edit All", "Approve Own", "Approve Others", "Approve All", "Delete Own", "Delete Others", "Delete All"),
   )))
   {
      echo '<div style="float:left; margin-left:10px;"><a href="' . $pem_url . 'add-event.php?t=unscheduled" title="' . __("Add Side Event") . '"';
      echo '><span>' . __("Add Side Event") . '</span></a></div>' . "\n";
   }
   echo '</div>' . "\n"; // END unscheduled-box
}



echo '<div id="legend-box">' . "\n";
echo '<h4>' . __("Color Key") . '</h4>' . "\n";
for ($i = 1; $i < count($cat_list); $i++)
{
   echo '<div><div class="key" style="background-color:#' . $cat_list[$i]["category_color"] . '"></div>';
   echo '<span class="label">' . $cat_list[$i]["category_name"] . '</span></div><br />' . "\n";

}
echo '</div>' . "\n"; // END legend-box



echo '<div id="sidebar-message" style="float:right; width:200px;">' . "\n";
echo $sidebar_text;
echo '</div>' . "\n"; // END sidebar-message


include_once ABSPATH . PEMINC . "/class-minicals.php";
//echo "show_minical: " . $view_settings["show_minical"] . "<br />";
//echo "minical_format: " . $view_settings["minical_format"] . "<br />";
//echo "minical_size: " . $view_settings["minical_size"] . "<br />";
//echo "highlight_today_minical: " . $view_settings["highlight_today_minical"] . "<br />";
//print_r($view_settings);

//Draw the three month calendars
// ICPL HACK fix after view settings admin page
//if ($view_settings["show_minical"] == 1)
if (1 == 1)
{
   $minical_format = ($view_settings["minical_format"] == 1) ? "horizantal" : "vertical";
   $minical_size = ($view_settings["minical_size"] == 1) ? "large" : "small";
   $highlight_today = ($view_settings["highlight_today_minical"] == 1) ? true : false;

   minicals($current_year, $current_month, $current_day, $minical_format, $minical_size, $highlight_today);
}

echo '</div>' . "\n"; // END sidebar-day




} // END List View

// =============================================================================
// ======================== BEGIN CALENDAR DISPLAY =============================
// =============================================================================


if ($view_format == "calendar")
{

   ini_set('memory_limit', '16M');


   // ====================== BUILD EVENT INFORMATION ===========================

   $pemdb =& mdb2_connect($dsn, $options, "connect");
   if (PEAR::isError($pemdb)) die($pemdb->getMessage()); // check for error

   MDB2::loadFile("Date"); // load Date helper class

   unset($where);
   $where["status"] = 1;
   $area_list = pem_get_rows("areas", $where, "AND", "area_name");

   for ($i = 0; $i < count($area_list); $i++)
   {
      $sql = "SELECT id, space_name, space_name_short FROM " . $table_prefix . "spaces WHERE ";
      $sql .= "area = :area_id AND ";
      $sql .= "show_day_view = 1 AND ";
      $sql .= "(internal_scheduled = 1 OR external_scheduled = 1) AND ";
      $sql .= "status != 2 ";
      $sql .= " ORDER BY :order_field";
      $sql_values = array("area_id" => $area_list[$i]["id"], "order_field" => "space_name");
      $area_list[$i]["spaces"] = pem_exec_sql($sql, $sql_values);
   }
// =============================================================================
// MAKE COLUMN COUNTER HERE FOR AUTOMATED SPLIT INTO MULTIPLE TABLES
   echo $table_hold = '<table border="0" cellspacing="0" cellpadding="0" class="dtable"><tr>' . "\n";
   $space_increment_count = 0;


   // Space name header row:
   $area_row = '<th class="area"></th>';
   $space_row = '<th>&nbsp;</th>';
   for ($i = 0; $i < count($area_list); $i++)
   {
      $space_count = (isset($area_list[$i]["spaces"])) ? count($area_list[$i]["spaces"]) : 0;
      if (!empty($space_count))
      {
         $col_width = (100 / ($space_count + 1)) . "%";
         $area_row .= '<th class="area" colspan="' . $space_count . '">';
         $area_row .= $area_list[$i]["area_name"];
         $area_row .= '</th>' . "\n";
         for ($j = 0; $j < $space_count; $j++)
         {
            $space_name = (!empty($area_list[$i]["spaces"][$j]["space_name_short"])) ? $area_list[$i]["spaces"][$j]["space_name_short"] : $area_list[$i]["spaces"][$j]["space_name"];
            $space_row .= '<th style="width:' . $col_width .';">' . $space_name . "</th>" . "\n";
            $space_order[] = $area_list[$i]["spaces"][$j]["id"];
         }
      }
   }
//   /*if ($show_area)*/ echo $area_row . '</tr><tr>' . "\n";
   echo $space_row . '</tr>' . "\n";

   $current_time = $day_begin_time;
   $display_time = $day_begin_time;
   $sql = "SELECT * FROM " . $table_prefix . "entries as e, " . $table_prefix . "dates as d WHERE ";
   $sql .= "e.id = d.entry_id AND ";
   $sql .= "e.entry_status != 2 AND ";
   $sql .= "d.date_status != 2 AND ";
   $sql .= "e.entry_cancelled != 1 AND ";
   $sql .= "d.date_cancelled != 1 AND ";
//   $sql .= "d.real_begin = :real_begin AND ";
   $sql .= "d.real_begin < :when_begin_before AND ";
   $sql .= "d.real_end >= :when_end_after";
   if ($authorized_calendar) $sql .= " AND (e.entry_type = 1 OR e.entry_type = 2)";
   elseif ($authorized_internal_calendar) $sql .= " AND e.entry_type = 1";
   elseif ($authorized_external_calendar) $sql .= " AND e.entry_type = 2";
   if (!$authorized_private) $sql .= " AND (e.entry_visible_to_public = 1 AND d.date_visible_to_public = 1)";
   if (!$authorized_unapproved) $sql .= " AND (e.entry_status != 0 AND d.date_status != 0)";
   if (!$authorized_cancelled) $sql .= " AND (e.entry_cancelled != 1 AND d.date_cancelled != 1)";
   $sql .= " ORDER BY d.when_begin, d.when_end, e.entry_name";

   $increment_display_span = $view_increment / $minute_increment;
   $increment_display_count = 1;
   while ($current_time < $day_end_time)
   {
      $row_written = true;
// echo "<td>count: $increment_display_count, span: $increment_display_span</td>" . "\n";

      if ($increment_display_count == 1)
      {
         $row_hold = '<td class="dtime" rowspan="' . $increment_display_span . '">' .  pem_date($time_format, $current_time) . '</td>' . "\n";
      }
      else
      {
         $row_hold = "";
// $row_hold = "<td>count: $increment_display_count, span: $increment_display_span</td>";
      }

      $increment_time = $current_time + ($minute_increment * 60);
      $dbegin = MDB2_Date::date2Mdbstamp(pem_date("H", $current_time), pem_date("i", $current_time), pem_date("s", $current_time), pem_date("m", $current_time), pem_date("d", $current_time), pem_date("Y", $current_time));
      $dend = MDB2_Date::date2Mdbstamp(pem_date("H", $increment_time), pem_date("i", $increment_time), pem_date("s", $increment_time), pem_date("m", $increment_time), pem_date("d", $increment_time), pem_date("Y", $increment_time));
//      $sql_values = array("real_begin" => $dbegin);
      $sql_values = array("when_begin_before" => $dend, "when_end_after" => $dbegin);
      $list = pem_exec_sql($sql, $sql_values);

      $alldayrow = false;
      for ($i = 0; $i < count($space_order); $i++)
      {
         if (!empty($list))
         {
            $event_found = false;
            for ($j = 0; $j < count($list); $j++)
            {

               $cat = (isset($list[$j]["date_category"])) ? $list[$j]["date_category"] : $list[$j]["entry_category"];
               if (empty($cat)) $cat = 1;  // default color if no others set.

               // Apply filters to event
               if (in_array(1, $current_cats) OR in_array($cat, $current_cats) OR
                     (in_array(100, $current_cats) AND (!$list[$j]["entry_status"] OR !$list[$j]["date_status"])) OR
                     (in_array(101, $current_cats) AND ($list[$j]["entry_cancelled"] OR $$ist[$j]["date_cancelled"])) OR
                     (in_array(102, $current_cats) AND (!$list[$j]["entry_visible_to_public"] OR !$list[$j]["date_visible_to_public"]))
                  )
               {
                  if (is_array(unserialize($list[$j]["spaces"])) AND in_array($space_order[$i], unserialize($list[$j]["spaces"])))
                  {
                     if ($list[$j]['allday'] AND ($current_time < $open_begin OR $current_time >= $open_end))
                     {
                       $alldayrow = true; // All-Day Events don't need to show before/after opening
                     }
                     if ($current_time < strtotime($list[$j]["when_begin"]) OR ($current_time >= strtotime($list[$j]["when_end"]) AND $list[$j]["when_end"] != $list[$j]["real_end"] AND $current_time < strtotime($list[$j]["real_end"])))
                     {
                        $event_found = true;
                        $row_hold .= '<td class="reserved">';

//$row_hold .=  "current time: " . $current_time . ", " .
//              "<br />end time: " . strtotime($list[$j]["when_end"]) .
//              "<br />end time: " . (strtotime($list[$j]["when_end"]) + 900);
                        $row_hold .= "";
                        $row_hold .= '</td>' . "\n";
                     }
                     elseif ($current_time >= strtotime($list[$j]["when_end"]) AND $list[$j]["when_end"] != $list[$j]["real_end"] AND $current_time == strtotime($list[$j]["real_end"]))
                     {
                        $event_found = true;
                        //    if AUTHORIZED TO ADD A NEW EVENT
                        $row_hold .= '<td onclick="location.href=\'add-event.php?t=scheduled&amp;l=' . $space_order[$i] . '&amp;h=' . pem_date("H", $current_time) . '&amp;i=' . pem_date("i", $current_time) . '\';">';
//$row_hold .= 'sdfsdfasdfas' . "\n";
                        $row_hold .= '</td>' . "\n";
                     }
                     else
                     {
                        $event_found = true;

                        if (($current_time == strtotime($list[$j]["when_begin"]) AND !$list[$j]['allday']) OR ($list[$j]['allday'] AND !$alldayrow AND $current_time == $open_begin))
                        {
                           $event_span = (strtotime($list[$j]["when_end"]) - strtotime($list[$j]["when_begin"])) / ($minute_increment * 60);
                           if ($list[$j]['allday']) $event_span = ($open_end - $open_begin) / ($minute_increment * 60);

                           if (!$list[$j]["entry_status"] OR !$list[$j]["date_status"])
                           {
                              $row_hold .= '<td class="unapproved" rowspan="' . $event_span . '" onclick="location.href=\'view.php?did=' . $list[$j]["id"] . '\';">';
                           }
                           elseif ($list[$j]["entry_cancelled"] OR $list[$j]["date_cancelled"])
                           {
                              $cat = (isset($list[$j]["date_category"])) ? $list[$j]["date_category"] : $list[$j]["entry_category"];
                              if (empty($cat)) $cat = 1;  // default color if no others set.
                              $row_hold .= '<td class="cancelled" style="border-color:#' . $categories[$cat] . ';" rowspan="' . $event_span . '" onclick="location.href=\'view.php?did=' . $list[$j]["id"] . '\';">';
                           }
                           elseif (!$list[$j]["entry_visible_to_public"] OR !$list[$j]["date_visible_to_public"])
                           {
                              $row_hold .= '<td class="private" rowspan="' . $event_span . '" onclick="location.href=\'view.php?did=' . $list[$j]["id"] . '\';">';
                           }
                           else
                           {
                              $cat = (isset($list[$j]["date_category"])) ? $list[$j]["date_category"] : $list[$j]["entry_category"];
                              if (empty($cat)) $cat = 1;  // default color if no others set.
                              $row_hold .= '<td class="devent" style="border-color:#' . $categories[$cat] . ';" rowspan="' . $event_span . '" onclick="location.href=\'view.php?did=' . $list[$j]["id"] . '\';">';
                           }
                           $row_hold .= '<div style="border-color:#' . $categories[$cat] . ';">';
                           if (!$list[$j]["entry_status"] OR !$list[$j]["date_status"])
                           {
                              $styling = ' class="unapproved"';
                           }
                           elseif ($list[$j]["entry_cancelled"] OR $list[$j]["date_cancelled"])
                           {
                              $styling = ' class="cancelled" style="color:#' . $categories[$cat] . ';"';
                           }
                           elseif (!$list[$j]["entry_visible_to_public"] OR !$list[$j]["date_visible_to_public"])
                           {
                              $styling =  ' class="private"';
                           }
                           else
                           {
                              $styling = ' style="color:#' . $categories[$cat] . ';"';
                           }
                           $row_hold .= '<a' . $styling . ' href="view.php?did=' . $list[$j]["id"] . '">';

                           if ($this_event["entry_cancelled"] OR $this_event["date_cancelled"])
                           {
                              echo " [" . __("CANCELLED") . "] ";
                           }

                           $full_name = (!empty($list[$j]["date_name"])) ? $list[$j]["entry_name"] . ': ' . $list[$j]["date_name"] : $list[$j]["entry_name"];
                           if ((!empty($view_settings["event_name_length"])) AND (strlen($full_name) > $view_settings["event_name_length"]))
                           {
                              $row_hold .= " " . substr($full_name, 0, $view_settings["event_name_length"]) . __("...");
                           }
                           else
                           {
                              $row_hold .= " " . $full_name;
                           }
                           $row_hold .= '</a></div></td>' . "\n";
                        }
                     }
                  }
               } // END filtering
            } // END $list loop
            if (!$event_found) // write open block
            {

            //    if AUTHORIZED TO ADD A NEW EVENT
            $row_hold .= '<td onclick="location.href=\'add-event.php?t=scheduled&amp;l=' . $space_order[$i] . '&amp;h=' . pem_date("H", $current_time) . '&amp;i=' . pem_date("i", $current_time) . '\';">';
//$row_hold .=  "current time: " . $current_time;
            $row_hold .= '</td>' . "\n";

            }
         } // END !empty($list)
         elseif ($current_time >= $open_begin AND $current_time < $open_end)
         {
            //    if AUTHORIZED TO ADD A NEW EVENT
            $row_hold .= '<td onclick="location.href=\'add-event.php?t=scheduled&amp;l=' . $space_order[$i] . '&amp;h=' . pem_date("H", $current_time) . '&amp;i=' . pem_date("i", $current_time) . '\';">';
//$row_hold .=  "current time: " . $current_time;
            $row_hold .= '</td>' . "\n";
         }
         else
         {
            // $row_hold = "";
            $row_written = false;
         }
      }

      if ($alldayrow AND ($current_time < $open_begin OR $current_time >= $open_end))
      {
         $row_hold = ""; // All-Day Events don't need to show before/after opening
      }
      elseif ($row_written) echo '<tr>' . $row_hold . '</tr>' . "\n";

      $current_time += ($minute_increment * 60);
      if ($increment_display_count == $increment_display_span)
      {
         $display_time += ($view_increment * 60);
         $increment_display_count = 1;
      }
      else $increment_display_count++;
   }

mdb2_disconnect($pemdb);



   echo '</table>' . "\n";


   echo '<table border="0" cellspacing="0" cellpadding="0" class="dtable" style="width:20px; height:20px; float:left; border:1px solid #000;"><tr>' . "\n";
   echo '<td class="reserved" style="width:20px; height:20px; border:none;"></td></tr></table>' . "\n";
   echo '<div style="float:left; margin-left:5px;">' . __("Reserved Time") . '</div>';


 /*

      // Check for events this day
      if (isset($all_events[$cday][0]))
      {
         $event_count = count($all_events[$cday]);
         for ($i = 0; $i < $event_count; $i++)
         {
            if ($i != 0 AND $i == $view_settings["max_listings"]) // stop at the limit and display "[more]" linked to the day view
            {
               echo '<div><a href="index.php?n=day&amp;y=' . pem_date("Y", $day_stamp) . '&m=' . pem_date("m", $day_stamp) . '&d=' . pem_date("d", $day_stamp) . '" title="' . __("View Day") . '">' . __("[more]") . '</a></div>' . "\n";
               break;
            }

            $this_event = $all_events[$cday][$i];
//  print_r($this_event);
            echo '<div class="devent">';
            if (0 == $this_event["entry_status"] OR 0 == $this_event["date_status"])
            {
               echo '<a class="unapproved"';
            }
            else
            {
               $cat = (isset($this_event["date_category"])) ? $this_event["date_category"] : $this_event["entry_category"];
               if (empty($cat)) $cat = 1;  // default color if no others set.
               echo '<a style="color:#' . $categories[$cat] . ';"';
            }

            echo ' href="view.php?did=' . $this_event["id"] . '&amp;d=' . pem_date("d", $day_stamp) . '&amp;m=' . pem_date("m", $day_stamp) . '&amp;y=' . pem_date("Y", $day_stamp) . '">';
            echo '<b>';

            if ($view_settings["show_time_begin"]) echo pem_date($time_format, strtotime($this_event["when_begin"]));
            if ($view_settings["show_time_end"])
            {
               if ($view_settings["show_time_begin"]) echo "-";
               echo pem_date($time_format, strtotime($this_event["when_end"]));
            }
            echo '</b>';
            if ($view_settings["show_event_name"])
            {
               if ((!empty($view_settings["event_name_length"])) AND (strlen($this_event["entry_name"]) > $view_settings["event_name_length"]))
               {
                  echo " " . substr($this_event["entry_name"], 0, $view_settings["event_name_length"]) . __("...");
               }
               else
               {
                  echo " " . $this_event["entry_name"];
               }
            }
            if (($view_settings["show_area"]) OR ($view_settings["show_space"]))
            {
               echo "; " . __("Location:") . " ";
            }
            echo '</a></div>' . "\n";
         } // END loop of day's events
      } // if day has events

      echo '</td>' . "\n";
      $day_stamp = $day_stamp + 86400;
   } // END loop through week's days

   echo '</tr></table>' . "\n";
*/



// =============================================================================
// ======================== BEGIN SIDEBAR DISPLAY =============================
// =============================================================================

echo '<div id="sidebar-day-calendar">' . "\n";

if (isset($unscheduled_list))
{
   echo '<div id="unscheduled-box" style="float:left;">' . "\n";
   echo '<h4>' . __("Library Displays") . '</h4>';
   pem_unscheduled($unscheduled_list);
   if (pem_user_authorized(array(
      "Internal Side Box" => array("Edit Own", "Edit Others", "Edit All", "Approve Own", "Approve Others", "Approve All", "Delete Own", "Delete Others", "Delete All"),
      "External Side Box" => array("Edit Own", "Edit Others", "Edit All", "Approve Own", "Approve Others", "Approve All", "Delete Own", "Delete Others", "Delete All"),
   )))
   {
      echo '<div style="float:left; margin-left:10px;"><a href="' . $pem_url . 'add-event.php?t=unscheduled" title="' . __("Add Side Event") . '"';
      echo '><span>' . __("Add Side Event") . '</span></a></div>' . "\n";
   }
   echo '</div>' . "\n"; // END unscheduled-box
}

echo '<div id="legend-box">' . "\n";
echo '<h4>' . __("Color Key") . '</h4>' . "\n";
for ($i = 1; $i < count($cat_list); $i++)
{
   echo '<div><div class="key" style="background-color:#' . $cat_list[$i]["category_color"] . '"></div>';
   echo '<span class="label">' . $cat_list[$i]["category_name"] . '</span></div><br />' . "\n";

}
echo '</div>' . "\n"; // END legend-box

echo '<div id="sidebar-message" style="float:right; width:200px;">' . "\n";
echo $sidebar_text;
echo '</div>' . "\n"; // END sidebar-message


include_once ABSPATH . PEMINC . "/class-minicals.php";
//echo "show_minical: " . $view_settings["show_minical"] . "<br />";
//echo "minical_format: " . $view_settings["minical_format"] . "<br />";
//echo "minical_size: " . $view_settings["minical_size"] . "<br />";
//echo "highlight_today_minical: " . $view_settings["highlight_today_minical"] . "<br />";
//print_r($view_settings);

//Draw the three month calendars
// ICPL HACK fix after view settings admin page
//if ($view_settings["show_minical"] == 1)
if (1 == 1)
{
   $minical_format = ($view_settings["minical_format"] == 1) ? "horizantal" : "vertical";
   $minical_size = ($view_settings["minical_size"] == 1) ? "large" : "small";
   $highlight_today = ($view_settings["highlight_today_minical"] == 1) ? true : false;

   minicals($current_year, $current_month, $current_day, $minical_format, $minical_size, $highlight_today);
}


echo '</div>' . "\n"; // END sidebar-day


} // END Calendar View

?>
