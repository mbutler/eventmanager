<?php

/* ========================== FILE INFORMATION =================================
phxEventManager :: statistics.php

============================================================================= */

$pagetitle = "Statistics";
$navigation = "administration";
$page_access_requirement = "Statistics";
$cache_set = array (
        "current_navigation" => "statistics"
);
include_once "../pem-includes/header.php";
$current_report = pem_cache_get("current_report");
switch ($current_report)
{
   case ("general") :
      $datetitle = __("General Statistics");
      break;
   case ("location") :
      $datetitle = __("Statistics by Location");
      break;
   case ("category") :
      $datetitle = __("Statistics by Category");
      break;
   case ("creator") :
      $datetitle = __("Statistics by Creator");
      break;
   case ("approver") :
      $datetitle = __("Statistics by Approver");
      break;
}

//echo "report: $current_report <br />";

if ($current_report == "creator" or $current_report == "approver")
{
   $query = "SELECT id, user_nicename FROM " . $table_prefix . "users ORDER BY user_nicename";
   $pemdb = & mdb2_connect($dsn, $options, "connect");
   $user_list = pem_exec_sql($query);
}

if (isset ($date_begin_month))
{
   $checkscheduled = (isset ($checkscheduled)) ? 1 : 0;
   $checkunscheduled = (isset ($checkunscheduled)) ? 1 : 0;
   $checkallday = (isset ($checkallday)) ? 1 : 0;
   $checkinternal = (isset ($checkinternal)) ? 1 : 0;
   $checkexternal = (isset ($checkexternal)) ? 1 : 0;

   $checkpublic = (isset ($checkpublic)) ? 1 : 0;
   $checkprivate = (isset ($checkprivate)) ? 1 : 0;
   $checkvisible = (isset ($checkvisible)) ? 1 : 0;
   $checkcancelled = (isset ($checkcancelled)) ? 1 : 0;
   $checknotcancelled = (isset ($checknotcancelled)) ? 1 : 0;
   $checkpubsubmit = (isset ($checkpubsubmit)) ? 1 : 0;
   $checkregreq = (isset ($checkregreq)) ? 1 : 0;
   $checknotes = (isset ($checknotes)) ? 1 : 0;

   $date_begin = $date_begin_year . "-" . zeropad($date_begin_month, 2) . "-" . zeropad($date_begin_day, 2) . " 00:00:00";
   $date_end = $date_end_year . "-" . zeropad($date_end_month, 2) . "-" . zeropad($date_end_day, 2) . " 23:59:59";
   //echo "begin: $date_begin <br />";
   //echo "end: $date_end <br />";

   $core_sql = "FROM " . $table_prefix . "entries AS e, " . $table_prefix . "dates AS d WHERE ";
   $core_sql .= "e.id = d.entry_id AND ";
   $core_sql .= "e.entry_status != 2 AND ";
   $core_sql .= "d.date_status != 2";
   $core_sql .= " AND d.when_begin <= :when_begin_before AND d.when_end >= :when_end_after";
   $sql_values = array (
           "when_begin_before" => $date_end,
           "when_end_after" => $date_begin
   );

   $add_sql = "";

   if ((!$checkinternal and !$checkexternal) or ($checkinternal and $checkexternal))
   {
      if ($checkallday)
      {
         if ($checkscheduled AND $checkunscheduled)
            $add_sql .= "";
         elseif ($checkunscheduled) $add_sql .= " AND (e.entry_type = 3 OR e.entry_type = 4 OR d.allday = 1)";
         elseif ($checkscheduled) $add_sql .= " AND (e.entry_type = 1 OR e.entry_type = 2)";
         else
            $add_sql .= " AND d.allday = 1";
      } else
      {
         if ($checkscheduled AND $checkunscheduled)
            $add_sql .= " AND d.allday = 0";
         elseif ($checkscheduled) $add_sql .= " AND (e.entry_type = 1 OR e.entry_type = 2) AND d.allday = 0";
         elseif ($checkunscheduled) $add_sql .= " AND (e.entry_type = 3 OR e.entry_type = 4)";
      }
   }
   elseif ($checkinternal)
   {
      if ($checkscheduled AND $checkunscheduled)
         $add_sql .= " AND (e.entry_type = 1 OR e.entry_type = 3)";
      elseif ($checkscheduled) $add_sql .= " AND e.entry_type = 1";
      elseif ($checkunscheduled) $add_sql .= " AND e.entry_type = 3";
      else
         $add_sql .= " AND (e.entry_type = 1 OR e.entry_type = 3)";
      if ($checkallday)
         $add_sql .= " AND d.allday = 1";
   }
   elseif ($checkexternal)
   {
      if ($checkscheduled AND $checkunscheduled)
         $add_sql .= " AND (e.entry_type = 2 OR e.entry_type = 4)";
      elseif ($checkscheduled) $add_sql .= " AND e.entry_type = 2";
      elseif ($checkunscheduled) $add_sql .= " AND e.entry_type = 4";
      else
         $add_sql .= " AND (e.entry_type = 2 OR e.entry_type = 4)";
      if ($checkallday)
         $add_sql .= " AND d.allday = 1";
   }

   if ($checkregreq)
      $add_sql .= " AND (e.entry_reg_require = 1 OR d.date_reg_require = 1)";
   if ($checkpublic and !$checkprivate)
      $add_sql .= " AND (e.entry_open_to_public = 1 AND d.date_open_to_public = 1)";
   if ($checkprivate and !$checkpublic)
      $add_sql .= " AND (e.entry_open_to_public = 0 OR d.date_open_to_public = 0)";
   if ($checkvisible)
      $add_sql .= " AND (e.entry_visible_to_public = 1 AND d.date_visible_to_public = 1)";
   if ($checkpubsubmit)
      $add_sql .= " AND e.entry_created_by = 2";
   if ($checknotes)
      $add_sql .= " AND (e.entry_priv_notes != '' OR d.date_priv_notes != '')";

   if ($checkcancelled and !$checknotcancelled)
      $add_sql .= " AND (e.entry_cancelled = 1 OR d.date_cancelled = 1)";
   if ($checknotcancelled and !$checkcancelled)
      $add_sql .= " AND (e.entry_cancelled = 0 AND d.date_cancelled = 0)";

   if (isset ($category))
   {
      $add_sql .= " AND (";
      $category_keys = array_keys($category);
      for ($i = 0; $i < count($category_keys); $i++)
      {
         $add_sql .= "d.date_category = " . $category_keys[$i] . " OR e.entry_category = " . $category_keys[$i];
         if ($i < (count($category_keys) - 1))
            $add_sql .= " OR ";
      }
      $add_sql .= ")";
   }

   if (isset ($creator))
   {
      $add_sql .= " AND (";
      $creator_keys = array_keys($creator);
      for ($i = 0; $i < count($creator_keys); $i++)
      {
         $add_sql .= "d.date_created_by = '" . $creator_keys[$i] . "' OR e.entry_created_by = '" . $creator_keys[$i] . "'";
         if ($i < (count($creator_keys) - 1))
            $add_sql .= " OR ";
      }
      $add_sql .= ")";
   }

   if (isset ($approver))
   {
      $add_sql .= " AND (";
      $approver_keys = array_keys($approver);
      for ($i = 0; $i < count($approver_keys); $i++)
      {
         $add_sql .= "d.date_approved_by = '" . $approver_keys[$i] . "' OR e.entry_approved_by = '" . $approver_keys[$i] . "'";
         if ($i < (count($approver_keys) - 1))
            $add_sql .= " OR ";
      }
      $add_sql .= ")";
   }

   //   $general_options["checkregreq"] = __("Registration Required");
   //   $general_options["checkpublic"] = __("Open to the Public");
   //   $general_options["checkvisible"] = __("Visible to the Public");
   //   $general_options["checkpubsubmit"] = __("Submitted by Public");

   $sql = "SELECT COUNT(*) AS count " . $core_sql . $add_sql;
   $res = pem_exec_sql($sql, $sql_values);
   $total_count = $res[0]["count"];

   $type_list[__("Calendar Events")] = "((e.entry_type = 1 OR e.entry_type = 2) AND d.allday = 0)";
   $type_list[__("Side Box Events")] = "(e.entry_type = 3 OR e.entry_type = 4)";
   $type_list[__("All-Day Events")] = "(d.allday = 1)";
   $type_list[__("Internal Events")] = "(e.entry_type = 1 OR e.entry_type = 3)";
   $type_list[__("External Events")] = "(e.entry_type = 2 OR e.entry_type = 4)";

   $type_counts = get_stat_count($type_list);

   $general_list[__("Open to the Public")] = "(e.entry_open_to_public = 1 AND d.date_open_to_public = 1)";
   $general_list[__("Private Events")] = "(e.entry_open_to_public = 0 OR d.date_open_to_public = 0)";
   $general_list[__("Visible to the Public")] = "(e.entry_visible_to_public = 1 AND d.date_visible_to_public = 1)";
   $general_list[__("Cancelled Events")] = "(e.entry_cancelled = 1 OR d.date_cancelled = 1)";
   $general_list[__("Not Cancelled Events")] = "(e.entry_cancelled = 0 AND d.date_cancelled = 0)";
   $general_list[__("Submitted by Public")] = "e.entry_created_by = 2";
   $general_list[__("Registration Required")] = "(e.entry_reg_require = 1 OR d.date_reg_require = 1)";
   $general_list[__("Has Private Notes")] = "(e.entry_priv_notes != '' OR d.date_priv_notes != '')";

   $general_counts = get_stat_count($general_list);

   $query = "SELECT id, area_name FROM " . $table_prefix . "areas";
   $res = pem_exec_sql($query);
   foreach ($res as $this_area)
      $areas[$this_area["id"]] = $this_area["area_name"];
   $query = "SELECT id, space_name, area FROM " . $table_prefix . "spaces ORDER By area, space_name";
   $res = pem_exec_sql($query);
   foreach ($res as $this_space)
   {
      $spaces[$this_space["id"]]["space_name"] = $this_space["space_name"];
      $spaces[$this_space["id"]]["area"] = $this_space["area"];
      $spaces[$this_space["id"]]["count"] = 0;
      $spaces[$this_space["id"]]["attendance"] = 0;
   }

   $query = "SELECT d.spaces, e.entry_seats_expected, d.date_seats_expected, e.entry_name  " . $core_sql . $add_sql;
   $res = pem_exec_sql($query, $sql_values);
   $total_seats = 0;
   if (is_array($res))
      foreach ($res as $this_event)
      {
         $date_seats = (empty ($this_event["date_seats_expected"])) ? 0 : $this_event["date_seats_expected"];
         $entry_seats = (empty ($this_event["entry_seats_expected"])) ? 0 : $this_event["entry_seats_expected"];
         $seat_count = ($date_seats > 0) ? $date_seats : $entry_seats;
         $total_seats += $seat_count;

         // echo $this_event["spaces"] . " " . $this_event["entry_name"] . "<br />";

         $this_space = unserialize($this_event["spaces"]);

         if (is_array($this_space))
            foreach ($this_space as $space_id)
            {
               $spaces[$space_id]["count"]++;
               $spaces[$space_id]["attendance"] += $seat_count;
            }
      }

   $general_counts["Total Seats Expected"] = $total_seats;

   $category_list = pem_get_rows("categories");
   foreach ($category_list as $category_item)
      $category_loop[$category_item["category_name"]] = $category_item["id"];
   unset ($category_list);
   $column_target = "e.entry_category";
   foreach ($category_loop as $label => $value)
      $category_list[$label] = "$column_target = $value";
   $category_counts = get_stat_count($category_list);

   $day_loop = array (
           "Sunday" => 1,
           "Monday" => 2,
           "Tuesday" => 3,
           "Wednesday" => 4,
           "Thursday" => 5,
           "Friday" => 6,
           "Saturday" => 7
   );
   $column_target = "DAYOFWEEK(d.when_begin)";
   foreach ($day_loop as $label => $value)
      $day_list[$label] = "$column_target = $value";
   $day_counts = get_stat_count($day_list);

   $hour_loop = array (
           "1 a.m." => 1,
           "2 a.m." => 2,
           "3 a.m." => 3,
           "4 a.m." => 4,
           "5 a.m." => 5,
           "6 a.m." => 6,
           "7 a.m." => 7,
           "8 a.m." => 8,
           "9 a.m." => 9,
           "10 a.m." => 10,
           "11 a.m." => 11,
           "Noon" => 12,
           "1 p.m." => 13,
           "2 p.m." => 14,
           "3 p.m." => 15,
           "4 p.m." => 16,
           "5 p.m." => 17,
           "6 p.m." => 18,
           "7 p.m." => 19,
           "8 p.m." => 20,
           "9 p.m." => 21,
           "10 p.m." => 22,
           "11 p.m." => 23,
           "Midnight" => 0
   );
   $column_target = "HOUR(d.when_begin)";
   foreach ($hour_loop as $label => $value)
      $hour_list[$label] = "$column_target = $value";
   $hour_counts = get_stat_count($hour_list);

   if ($current_report == "creator")
   {
      foreach ($user_list as $user_item)
         $user_loop[$user_item["user_nicename"]] = $user_item["id"];
      $column_target = "e.entry_created_by";
      foreach ($user_loop as $label => $value)
         $user_list_tmp[$label] = "$column_target = $value";
      $user_counts = get_stat_count($user_list_tmp);
      unset ($user_list_tmp);
   }
   elseif ($current_report == "approver")
   {
      foreach ($user_list as $user_item)
         $user_loop[$user_item["user_nicename"]] = $user_item["id"];
      $column_target = "e.entry_approved_by";
      foreach ($user_loop as $label => $value)
         $user_list_tmp[$label] = "$column_target = $value";
      $user_counts = get_stat_count($user_list_tmp);
      unset ($user_list_tmp);
   }

}

// display date range selection form
if (!isset ($printview))
{
   pem_fieldset_begin($datetitle);
   echo '<p>' . __("Select a date range using the options below to generate your report.") . "</p>\n";
   echo_form();
   pem_fieldset_end();
}

if (isset ($date_begin_month))
{
   if (!isset ($printview))
      pem_fieldset_begin(__("Report Results"));

   echo '<div style="float:left; margin-right:30px;">' . "\n";
   echo '<h3>' . __("Event Types") . '</h3>' . "\n";
   echo '<table class="stattable">' . "\n";
   echo_stat_line(__("Total Events"), $total_count, 1);
   $row = 2;
   foreach ($type_counts as $label => $count)
   {
      echo_stat_line($label, $count, $row);
      $row = ($row == 1) ? 2 : 1;
   }
   echo '</table>' . "\n";
   echo '<h3>' . __("Event Information") . '</h3>' . "\n";
   echo '<table class="stattable">' . "\n";
   $row = 1;
   foreach ($general_counts as $label => $count)
   {
      echo_stat_line($label, $count, $row);
      $row = ($row == 1) ? 2 : 1;
   }
   echo '</table>' . "\n";
   echo '</div>' . "\n";
   echo '<div style="float:left; margin-right:30px;">' . "\n";
   echo '<h3>' . __("Start Day") . '</h3>' . "\n";
   echo '<table class="stattable">' . "\n";
   $row = 1;
   foreach ($day_counts as $label => $count)
   {
      echo_stat_line($label, $count, $row);
      $row = ($row == 1) ? 2 : 1;
   }
   echo '</table>' . "\n";
   echo '<h3>' . __("Start Hour") . '</h3>' . "\n";
   echo '<table class="stattable">' . "\n";
   $row = 1;
   foreach ($hour_counts as $label => $count)
   {
      echo_stat_line($label, $count, $row);
      $row = ($row == 1) ? 2 : 1;
   }
   echo '</table>' . "\n";
   echo '</div>' . "\n";
   echo '<div style="float:left; margin-right:30px;">' . "\n";
   echo '<h3>' . __("Categories") . '</h3>' . "\n";
   echo '<table class="stattable">' . "\n";
   $row = 1;
   foreach ($category_counts as $label => $count)
   {
      echo_stat_line($label, $count, $row);
      $row = ($row == 1) ? 2 : 1;
   }
   echo '</table>' . "\n";
   echo '</div>' . "\n";

   if ($current_report == "creator" or $current_report == "approver")
   {
      $subtitle = ($current_report == "creator") ? __("as Creators") : __("as Approvers");
      echo '<div style="float:left; margin-right:30px;">' . "\n";
      echo '<h3>' . __("Users") . ' ' . $subtitle . '</h3>' . "\n";
      echo '<table class="stattable">' . "\n";
      $row = 1;
      foreach ($user_counts as $label => $count)
      {
         echo_stat_line($label, $count, $row);
         $row = ($row == 1) ? 2 : 1;
      }
      echo '</table>' . "\n";
      echo '</div>' . "\n";
   } else
   {
      echo '<div style="float:left; margin-right:30px;">' . "\n";
      echo '<table class="stattable">' . "\n";
      $current_area = "";
      foreach ($spaces as $this_space)
         if ($this_space["count"] > 0)
         {
            if ($current_area != $this_space["area"])
            {
               echo '<tr><td colspan="3"><h3>' . $areas[$this_space["area"]] . '</h3></td></tr>' . "\n";
               echo '<tr><td colspan="3" style="font-weight:bold;"><div style="float:right;">' . __("Use/Attendance") . '</div>' . __("Space") . '</td></tr>' . "\n";
               $current_area = $this_space["area"];
               $row = 1;
            }
            echo '<tr class="row' . $row . '"><td>' . $this_space["space_name"] . ':</td><td style="text-align:right;">' . $this_space["count"] . ' / </td><td style="text-align:right;">' . $this_space["attendance"] . '</td></tr>' . "\n";
            $row = ($row == 1) ? 2 : 1;
         }
      echo '</table>' . "\n";
      echo '</div>' . "\n";
   }

   echo '<br class="clear" />' . "\n";

   if (!isset ($printview))
      pem_fieldset_end();
}

if (!isset ($date_begin_month))
{
   $current_event_type = pem_cache_get("current_event_type");
   switch ($current_event_type)
   {
      case ("scheduled") :
         $checkscheduled = 1;
         $checkunscheduled = 0;
         $checkallday = 0;
         break;
      case ("unscheduled") :
         $checkscheduled = 0;
         $checkunscheduled = 1;
         $checkallday = 0;
         break;
      case ("allday") :
         $checkscheduled = 0;
         $checkunscheduled = 0;
         $checkallday = 1;
         break;
      default :
         $checkscheduled = 1;
         $checkunscheduled = 0;
         $checkallday = 0;
         break;
   }
}

mdb2_disconnect($pemdb);
include ABSPATH . PEMINC . "/footer.php";

// =============================================================================
// =========================== LOCAL FUNCTIONS =================================
// =============================================================================

function echo_stat_line($label, $count, $row = 1)
{
   echo '<tr class="row' . $row . '"><td class="label">' . $label . ':</td><td style="text-align:right;">' . $count . '</td></tr>' . "\n";
}

function get_stat_count($list)
{
   global $table_prefix, $sql, $sql_values;
   foreach ($list as $label => $sql_add)
   {
      $query = $sql . " AND " . $sql_add;
      $res = pem_exec_sql($query, $sql_values);
      $ret[$label] = $res[0]["count"];
   }
   return $ret;
}

function echo_form()
{
   global $PHP_SELF, $_POST, $table_prefix, $date_format, $date_begin, $date_end, $current_report, $check_options;
   extract($_POST);

   //print_r($_POST);

   if (empty ($date_begin))
      $date_begin = pem_date("Y-m-d");
   if (empty ($date_end))
      $date_end = pem_date("Y-m-d");

   pem_form_begin(array (
           "nameid" => "dateform",
           "action" => $PHP_SELF,
           "class" => "reportdateform"
   ));

   echo '<div class="datebox">';
   echo '<h3>' . __("Manually Select Date Range") . '</h3>' . "\n";
   pem_field_label(array (
           "default" => __("Date Begins:"
           ), "for" => "date_begin_month"));
   pem_date_selector("date_begin_", array (
           "default" => $date_begin
   ));
   echo '<br />';
   pem_field_label(array (
           "default" => __("Date Ends:"
           ), "for" => "date_end_month"));
   pem_date_selector("date_end_", array (
           "default" => $date_end
   ));
   echo '</div>' . "\n";

   echo '<div class="ordivider"></div>';

   echo '<div class="datebox">' . "\n";
   echo '<h3>' . __("Automatically Set Date Range To") . '</h3>' . "\n";
   echo '<table><tr>' . "\n";
   echo '<td>';
   $set_select = "setdateselect('date_begin', " . pem_date("Y", strtotime("-1 day")) . ", " . pem_date("m", strtotime("-1 day")) . ", " . pem_date("d", strtotime("-1 day")) . ");";
   $set_select .= " setdateselect('date_end', " . pem_date("Y", strtotime("-1 day")) . ", " . pem_date("m", strtotime("-1 day")) . ", " . pem_date("d", strtotime("-1 day")) . ");";
   echo '<input type="button" value="' . __("Yesterday's Date") . '" onclick="' . $set_select . '" />' . "\n";
   echo "</td>\n<td>";
   $set_select = "setdateselect('date_begin', " . pem_date("Y") . ", " . pem_date("m") . ", " . pem_date("d") . ");";
   $set_select .= " setdateselect('date_end', " . pem_date("Y") . ", " . pem_date("m") . ", " . pem_date("d") . ");";
   echo '<input type="button" value="' . __("Today's Date") . '" onclick="' . $set_select . '" />' . "\n";
   echo "</td>\n<td>";
   $set_select = "setdateselect('date_begin', " . pem_date("Y", strtotime("+1 day")) . ", " . pem_date("m", strtotime("+1 day")) . ", " . pem_date("d", strtotime("+1 day")) . ");";
   $set_select .= " setdateselect('date_end', " . pem_date("Y", strtotime("+1 day")) . ", " . pem_date("m", strtotime("+1 day")) . ", " . pem_date("d", strtotime("+1 day")) . ");";
   echo '<input type="button" value="' . __("Tomorrow's Date") . '" onclick="' . $set_select . '" />' . "\n";
   echo "</td>\n</tr><tr>\n<td>";
   $set_select = " setdateselect('date_begin', " . pem_date("Y", strtotime("-1 week")) . ", " . pem_date("m", strtotime("-1 week")) . ", " . pem_date("d", strtotime("-1 week")) . ");";
   $set_select .= "setdateselect('date_end', " . pem_date("Y") . ", " . pem_date("m") . ", " . pem_date("d") . ");";
   echo '<input type="button" value="' . __("Previous 7 Days") . '" onclick="' . $set_select . '" />' . "\n";
   echo "</td>\n<td>";
   $set_select = "setdateselect('date_begin', " . pem_date("Y", strtotime("last Sunday -7 days")) . ", " . pem_date("m", strtotime("last Sunday -7 days")) . ", " . pem_date("d", strtotime("last Sunday -7 days")) . ");";
   $set_select .= " setdateselect('date_end', " . pem_date("Y", strtotime("last Sunday -1 day")) . ", " . pem_date("m", strtotime("last Sunday -1 day")) . ", " . pem_date("d", strtotime("last Sunday -1 day")) . ");";
   echo '<input type="button" value="' . __("Previous Full Week") . '" onclick="' . $set_select . '" />' . "\n";
   echo "</td>\n<td>";
   $set_select = "setdateselect('date_begin', " . pem_date("Y", strtotime("last Sunday -14 days")) . ", " . pem_date("m", strtotime("last Sunday -14 days")) . ", " . pem_date("d", strtotime("last Sunday -14 days")) . ");";
   $set_select .= " setdateselect('date_end', " . pem_date("Y", strtotime("last Sunday -1 day")) . ", " . pem_date("m", strtotime("last Sunday -1 day")) . ", " . pem_date("d", strtotime("last Sunday -1 day")) . ");";
   echo '<input type="button" value="' . __("Previous Full 2 Weeks") . '" onclick="' . $set_select . '" />' . "\n";
   echo "</td>\n<td>";
   $set_select = "setdateselect('date_begin', " . pem_date("Y", strtotime("-1 month")) . ", " . pem_date("m", strtotime("-1 month")) . ", 01);";
   $set_select .= " setdateselect('date_end', " . pem_date("Y", strtotime("-1 month")) . ", " . pem_date("m", strtotime("-1 month")) . ", " . pem_date("t", strtotime("-1 month")) . ");";
   echo '<input type="button" value="' . __("Previous Month") . '" onclick="' . $set_select . '" />' . "\n";
   echo "</td>\n</tr><tr>\n<td>";
   $set_select = "setdateselect('date_begin', " . pem_date("Y") . ", " . pem_date("m") . ", " . pem_date("d") . ");";
   $set_select .= " setdateselect('date_end', " . pem_date("Y", strtotime("+1 week")) . ", " . pem_date("m", strtotime("+1 week")) . ", " . pem_date("d", strtotime("+1 week")) . ");";
   echo '<input type="button" value="' . __("Next 7 Days") . '" onclick="' . $set_select . '" />' . "\n";
   echo "</td>\n<td>";
   $set_select = "setdateselect('date_begin', " . pem_date("Y", strtotime("next Sunday")) . ", " . pem_date("m", strtotime("next Sunday")) . ", " . pem_date("d", strtotime("next Sunday")) . ");";
   $set_select .= " setdateselect('date_end', " . pem_date("Y", strtotime("next Sunday +6 days")) . ", " . pem_date("m", strtotime("next Sunday +6 days")) . ", " . pem_date("d", strtotime("next Sunday +6 days")) . ");";
   echo '<input type="button" value="' . __("Next Full Week") . '" onclick="' . $set_select . '" />' . "\n";
   echo "</td>\n<td>";
   $set_select = "setdateselect('date_begin', " . pem_date("Y", strtotime("next Sunday")) . ", " . pem_date("m", strtotime("next Sunday")) . ", " . pem_date("d", strtotime("next Sunday")) . ");";
   $set_select .= " setdateselect('date_end', " . pem_date("Y", strtotime("next Sunday +13 days")) . ", " . pem_date("m", strtotime("next Sunday +13 days")) . ", " . pem_date("d", strtotime("next Sunday +13 days")) . ");";
   echo '<input type="button" value="' . __("Next Full 2 Weeks") . '" onclick="' . $set_select . '" />' . "\n";
   echo "</td>\n<td>";
   $set_select = "setdateselect('date_begin', " . pem_date("Y", strtotime("+1 month")) . ", " . pem_date("m", strtotime("+1 month")) . ", 01);";
   $set_select .= " setdateselect('date_end', " . pem_date("Y", strtotime("+1 month")) . ", " . pem_date("m", strtotime("+1 month")) . ", " . pem_date("t", strtotime("+1 month")) . ");";
   echo '<input type="button" value="' . __("Next Month") . '" onclick="' . $set_select . '" />' . "\n";
   echo "</td>\n</tr></table>\n";
   echo '</div>' . "\n";

   echo '<br />';

   echo '<div class="datebox">' . "\n";
   pem_fieldset_begin(__("Only Show:"), array (
           "class" => "sub"
   ));

   $type_options["checkscheduled"] = __("Calendar Events");
   $type_options["checkunscheduled"] = __("Side Box Events");
   $type_options["checkallday"] = __("All-Day Events");
   $type_options["checkinternal"] = __("Internal Events");
   $type_options["checkexternal"] = __("External Events");

   $general_options["checkpublic"] = __("Open to the Public");
   $general_options["checkprivate"] = __("Private Events");
   $general_options["checkvisible"] = __("Visible to the Public");
   $general_options["checkcancelled"] = __("Cancelled Events");
   $general_options["checknotcancelled"] = __("Not Cancelled Events");
   $general_options["checkpubsubmit"] = __("Submitted by Public");
   $general_options["checkregreq"] = __("Registration Required");
   $general_options["checknotes"] = __("Has Private Notes");

   foreach ($type_options as $checkkey => $checkval)
   {
      $labelwidth = strlen($checkval) * 6;
      echo '<div style="float:left; white-space:nowrap;">';
      pem_checkbox(array (
              "nameid" => $checkkey,
              "status" => ${ $checkkey },
              "style" => "float:left;"
      ));
      pem_field_label(array (
              "default" => $checkval,
              "for" => $checkkey,
              "style" => "font-weight:normal; margin-right:20px; width:" . $labelwidth . "px;"
      ));
      echo '</div>';
   }
   echo '<br class="clear" />';
   foreach ($general_options as $checkkey => $checkval)
   {
      $labelwidth = strlen($checkval) * 6;
      echo '<div style="float:left; white-space:nowrap;">';
      pem_checkbox(array (
              "nameid" => $checkkey,
              "status" => ${ $checkkey },
              "style" => "float:left;"
      ));
      pem_field_label(array (
              "default" => $checkval,
              "for" => $checkkey,
              "style" => "font-weight:normal; margin-right:20px; width:" . $labelwidth . "px;"
      ));
      echo '</div>';
   }

   //   pem_checkbox(array("nameid" => "checkscheduled", "status" => $checkscheduled, "style" => "float:left;"));
   //   pem_field_label(array("default" => __("Calendar Events"), "for" => "checkscheduled", "style" => "font-weight:normal; margin-right:20px;"));
   //   pem_checkbox(array("nameid" => "checkunscheduled", "status" => $checkunscheduled, "style" => "float:left;"));
   //   pem_field_label(array("default" => __("Side Box Events"), "for" => "checkunscheduled", "style" => "font-weight:normal; margin-right:20px;"));
   //   pem_checkbox(array("nameid" => "checkallday", "status" => $checkallday, "style" => "float:left;"));
   //   pem_field_label(array("default" => __("All-Day Events"), "for" => "checkallday", "style" => "font-weight:normal;"));
   //

   if (isset ($current_report))
   {
      //pem_field_label(array("default" => " "));
      if ($current_report == "category")
      {
         echo '<br style="margin-bottom:20px;" />' . "\n";
         $check_options = pem_get_rows("categories");
         $longest = 0;
         foreach ($check_options AS $this_option)
         {
            $len = strlen($this_option["category_name"]);
            if ($len > $longest)
               $longest = $len;
         }
         unset ($len);
         $labelwidth = $longest * 7;
         for ($i = 1; $i < count($check_options); $i++)
         {
            $checked = (isset ($category[$check_options[$i]["id"]])) ? 1 : 0;
            // $labelwidth = strlen($check_options[$i]["category_name"]) * 6;
            echo '<div style="float:left; white-space:nowrap;">';
            pem_checkbox(array (
                    "name" => "category[" . $check_options[$i]["id"] . "]",
                    "id" => "category" . $check_options[$i]["id"],
                    "status" => $checked,
                    "style" => "float:left;"
            ));
            pem_field_label(array (
                    "default" => $check_options[$i]["category_name"],
                    "for" => "category" . $check_options[$i]["id"],
                    "style" => "font-weight:normal; margin-right:20px; width:" . $labelwidth . "px; color:#" . $check_options[$i]["category_color"] . ";"
            ));
            echo '</div>';
         }
      }
      if ($current_report == "location")
      {
         echo '<br style="margin-bottom:20px;" />' . "\n";
         $current_area = "";
         $check_options = pem_get_rows("spaces", "", "", "area");
         $longest = 0;
         foreach ($check_options AS $this_option)
         {
            $len = strlen($this_option["space_name_short"]);
            if ($len > $longest)
               $longest = $len;
         }
         unset ($len);
         $labelwidth = $longest * 7;
         for ($i = 0; $i < count($check_options); $i++)
         {
            $checked = (isset ($location[$check_options[$i]["id"]])) ? 1 : 0;
            // $labelwidth = strlen($check_options[$i]["space_name_short"]) * 6;
            echo '<div style="float:left; white-space:nowrap;">';
            pem_checkbox(array (
                    "name" => "location[" . $check_options[$i]["id"] . "]",
                    "id" => "location" . $check_options[$i]["id"],
                    "status" => $checked,
                    "style" => "float:left;"
            ));
            pem_field_label(array (
                    "default" => $check_options[$i]["space_name_short"],
                    "for" => "location" . $check_options[$i]["id"],
                    "style" => "font-weight:normal; margin-right:20px; width:" . $labelwidth . "px;"
            ));
            echo '</div>';
            if ($current_area != $check_options[$i]["area"] AND $i !== 0)
               echo '<br />' . "\n";
            $current_area = $check_options[$i]["area"];
         }
      }
      if ($current_report == "creator")
      {
         echo '<br style="margin-bottom:20px;" />' . "\n";
         global $user_list;
         $longest = 0;
         foreach ($user_list AS $this_option)
         {
            $check_options[$this_option["id"]] = $this_option["user_nicename"];
            $len = strlen($this_option["user_nicename"]);
            if ($len > $longest)
               $longest = $len;
         }
         unset ($len);
         $labelwidth = $longest * 7;

         $option_keys = array_keys($check_options);
         for ($i = 0; $i < count($option_keys); $i++)
         {
            $checked = (isset ($creator[$option_keys[$i]])) ? 1 : 0;
            echo '<div style="float:left; white-space:nowrap;">';
            pem_checkbox(array (
                    "name" => "creator[" . $option_keys[$i] . "]",
                    "id" => "creator" . $option_keys[$i],
                    "status" => $checked,
                    "style" => "float:left;"
            ));
            pem_field_label(array (
                    "default" => $check_options[$option_keys[$i]],
                    "for" => "creator" . $option_keys[$i],
                    "style" => "font-weight:normal; margin-right:20px; width:" . $labelwidth . "px"
            ));
            echo '</div>';
         }
      }
      if ($current_report == "approver")
      {
         echo '<br style="margin-bottom:20px;" />' . "\n";
         global $user_list;
         $longest = 0;
         foreach ($user_list AS $this_option)
         {
            $check_options[$this_option["id"]] = $this_option["user_nicename"];
            $len = strlen($this_option["user_nicename"]);
            if ($len > $longest)
               $longest = $len;
         }
         unset ($len);
         $labelwidth = $longest * 7;

         $option_keys = array_keys($check_options);
         for ($i = 0; $i < count($option_keys); $i++)
         {
            $checked = (isset ($approver[$option_keys[$i]])) ? 1 : 0;
            echo '<div style="float:left; white-space:nowrap;">';
            pem_checkbox(array (
                    "name" => "approver[" . $option_keys[$i] . "]",
                    "id" => "approver" . $option_keys[$i],
                    "status" => $checked,
                    "style" => "float:left;"
            ));
            pem_field_label(array (
                    "default" => $check_options[$option_keys[$i]],
                    "for" => "approver" . $option_keys[$i],
                    "style" => "font-weight:normal; margin-right:20px; width:" . $labelwidth . "px"
            ));
            echo '</div>';
         }
      }
   }
   pem_fieldset_end();
   echo '</div>' . "\n";

   echo '<br />';

   pem_form_submit("dateform", __("Get Statistics"));

   $labelwidth = strlen(__("Print View:")) * 6;
   pem_checkbox(array (
           "nameid" => "printview",
           "status" => false,
           "style" => "float:left; margin-left:30px;"
   ));
   pem_field_label(array (
           "default" => __("Print View"
           ), "style" => "width:" . $labelwidth . "px"));
   echo '<br />';

   pem_form_end();

} // END echo_form
?>