<?php
/* ========================== FILE INFORMATION =================================
phxEventManager :: reports.php

============================================================================= */

$pagetitle = "Reports";
$navigation = "administration";
$page_access_requirement = "Reports";
$cache_set = array("current_navigation" => "reports");
include_once "../pem-includes/header.php";
$current_report = pem_cache_get("current_report");
switch ($current_report)
{
   case ("list"):
      $datetitle = __("Events List");
      $select_fields = array("d.id", "d.when_begin", "d.allday", "d.when_end", "d.spaces", "e.entry_name", "e.entry_type", "d.date_cancelled", "e.entry_cancelled");
      function echo_data($list)
      {
         global $date_format, $time_format;

         echo '<table cellspacing="0" class="datalist">' . "\n";
         $lastdate = "";
         for ($i = 0; $i < count($list); $i++)
         {
            $thisdate = pem_date($date_format, $list[$i]["when_begin"]);
            if ($thisdate != $lastdate)
            {
               echo '<tr class="' . $row . '"><td class="dateheader"  style="padding-left:5px;" colspan="2">' . "\n";
               echo $thisdate;
               echo '</td></tr>' . "\n";
               $lastdate = $thisdate;
            }
            $row = ($i % 2) ? "row2" : "row1";
            echo '<tr class="' . $row . '"><td style="text-align:right; padding-right:10px; white-space:nowrap;" rowspan="2">' . "\n";
            if ($list[$i]["allday"]) echo __("All Day");
            elseif ($list[$i]["entry_type"] == 3 OR $list[$i]["entry_type"] == 4) echo __("Side Box");
            else echo pem_simplify_times($list[$i]["when_begin"], $list[$i]["when_end"]);
            echo '</td><td style="text-align:left;">' . "\n";
            echo '<a href="../view.php?did=' . $list[$i]["id"] . '">';
            if ($list[$i]["entry_cancelled"] OR $list[$i]["date_cancelled"])
            {
               echo "[" . __("CANCELLED") . "] ";
            }
            echo $list[$i]["entry_name"] . '</a>';
            echo '</td></tr>' . "\n";
            echo '<tr class="' . $row . '"><td style="text-align:left;">' . "\n";
            echo $list[$i]["spaces_text"];
            echo '</td></tr>' . "\n";
         }
         echo '</table>' . "\n";
      }
      break;
   case ("listdesc"):
      $datetitle = __("Events with Descriptions");
      $select_fields = array("d.id", "d.when_begin", "d.when_end", "d.allday", "d.spaces", "e.entry_name", "e.entry_type", "e.entry_description", "d.date_description", "d.date_cancelled", "e.entry_cancelled");
      function echo_data($list)
      {
         global $date_format, $time_format;

         echo '<table cellspacing="0" class="datalist">' . "\n";
         $lastdate = "";
         for ($i = 0; $i < count($list); $i++)
         {
            $thisdate = pem_date($date_format, $list[$i]["when_begin"]);
            if ($thisdate != $lastdate)
            {
               echo '<tr class="' . $row . '"><td class="dateheader"  style="padding-left:5px;" colspan="3">' . "\n";
               echo $thisdate;
               echo '</td></tr>' . "\n";
               $lastdate = $thisdate;
            }
            $row = ($i % 2) ? "row2" : "row1";
            echo '<tr class="' . $row . '"><td style="text-align:right; padding-right:10px; white-space:nowrap;" rowspan="2">' . "\n";
            if ($list[$i]["allday"]) echo __("All Day");
            elseif ($list[$i]["entry_type"] == 3 OR $list[$i]["entry_type"] == 4) echo __("Side Box");
            else echo pem_simplify_times($list[$i]["when_begin"], $list[$i]["when_end"]);
            echo '</td><td style="text-align:left;" rowspan="2">' . "\n";
            echo $list[$i]["spaces_text"];
            echo '</td><td style="text-align:left;">' . "\n";
            echo '<a href="../view.php?did=' . $list[$i]["id"] . '">';
            if ($list[$i]["entry_cancelled"] OR $list[$i]["date_cancelled"])
            {
               echo "[" . __("CANCELLED") . "] ";
            }
            echo $list[$i]["entry_name"] . '</a>';
            echo '</td></tr>' . "\n";
            echo '<tr class="' . $row . '"><td style="text-align:left;">' . "\n";
            echo $list[$i]["entry_description"];
            if (!empty($list[$i]["entry_description"]) AND !empty($list[$i]["date_description"])) echo '<br />';
            echo $list[$i]["date_description"];
            echo '</td></tr>' . "\n";
         }
         echo '</table>' . "\n";
      }
      break;
   case ("regs"):
      $datetitle = __("Events with Registrations");
      $select_fields = array("d.id", "d.when_begin", "d.when_end", "d.allday", "d.spaces", "e.entry_name", "e.entry_type");
      $where_add = " AND (d.date_reg_require = 1 OR e.entry_reg_require = 1)";
      function echo_data($list)
      {
         global $date_format, $time_format;

         echo '<table cellspacing="0" class="datalist">' . "\n";
         $lastdate = "";
         for ($i = 0; $i < count($list); $i++)
         {
            $thisdate = pem_date($date_format, $list[$i]["when_begin"]);
            if ($thisdate != $lastdate)
            {
               echo '<tr class="' . $row . '"><td class="dateheader"  style="padding-left:5px;" colspan="2">' . "\n";
               echo $thisdate;
               echo '</td></tr>' . "\n";
               $lastdate = $thisdate;
            }
            $row = ($i % 2) ? "row2" : "row1";
            echo '<tr class="' . $row . '"><td style="text-align:right; padding-right:10px;" rowspan="2">' . "\n";
            if ($list[$i]["allday"]) echo __("All Day");
            elseif ($list[$i]["entry_type"] == 3 OR $list[$i]["entry_type"] == 4) echo __("Side Box");
            else echo pem_date($time_format, $list[$i]["when_begin"]) . ' - ' . pem_date($time_format, $list[$i]["when_end"]);
            echo '</td><td style="text-align:left;">' . "\n";
            echo '<a href="../view.php?did=' . $list[$i]["id"] . '">';
            if ($list[$i]["entry_cancelled"] OR $list[$i]["date_cancelled"])
            {
               echo "[" . __("CANCELLED") . "] ";
            }
            echo $list[$i]["entry_name"] . '</a>';
            echo '</td></tr>' . "\n";
            echo '<tr class="' . $row . '"><td style="text-align:left;">' . "\n";
            echo $list[$i]["spaces_text"];
            echo '</td></tr>' . "\n";
         }
         echo '</table>' . "\n";
      }
      break;
   case ("notes"):
      $datetitle = __("Events with Private Notes");
      $select_fields = array("d.id", "d.when_begin", "d.when_end", "d.allday", "d.spaces", "e.entry_name", "e.entry_type");
      $where_add = " AND (d.date_priv_notes != '' OR e.entry_priv_notes != '')";
      function echo_data($list)
      {
         global $date_format, $time_format;

         echo '<table cellspacing="0" class="datalist">' . "\n";
         $lastdate = "";
         for ($i = 0; $i < count($list); $i++)
         {
            $thisdate = pem_date($date_format, $list[$i]["when_begin"]);
            if ($thisdate != $lastdate)
            {
               echo '<tr class="' . $row . '"><td class="dateheader"  style="padding-left:5px;" colspan="2">' . "\n";
               echo $thisdate;
               echo '</td></tr>' . "\n";
               $lastdate = $thisdate;
            }
            $row = ($i % 2) ? "row2" : "row1";
            echo '<tr class="' . $row . '"><td style="text-align:right; padding-right:10px;" rowspan="2">' . "\n";
            if ($list[$i]["allday"]) echo __("All Day");
            elseif ($list[$i]["entry_type"] == 3 OR $list[$i]["entry_type"] == 4) echo __("Side Box");
            else echo pem_date($time_format, $list[$i]["when_begin"]) . ' - ' . pem_date($time_format, $list[$i]["when_end"]);
            echo '</td><td style="text-align:left;">' . "\n";
            echo '<a href="../view.php?did=' . $list[$i]["id"] . '">';
            if ($list[$i]["entry_cancelled"] OR $list[$i]["date_cancelled"])
            {
               echo "[" . __("CANCELLED") . "] ";
            }
            echo $list[$i]["entry_name"] . '</a>';
            echo '</td></tr>' . "\n";
            echo '<tr class="' . $row . '"><td style="text-align:left;">' . "\n";
            echo $list[$i]["spaces_text"];
            echo '</td></tr>' . "\n";
         }
         echo '</table>' . "\n";
      }
      break;
   case ("private"):
      $datetitle = __("Private Events");
      $select_fields = array("d.id", "d.when_begin", "d.when_end", "d.allday", "d.spaces", "e.entry_name", "e.entry_type");
      $where_add = " AND (d.date_open_to_public = 0 OR e.entry_open_to_public = 0)";
      function echo_data($list)
      {
         global $date_format, $time_format;

         echo '<table cellspacing="0" class="datalist">' . "\n";
         $lastdate = "";
         for ($i = 0; $i < count($list); $i++)
         {
            $thisdate = pem_date($date_format, $list[$i]["when_begin"]);
            if ($thisdate != $lastdate)
            {
               echo '<tr class="' . $row . '"><td class="dateheader"  style="padding-left:5px;" colspan="2">' . "\n";
               echo $thisdate;
               echo '</td></tr>' . "\n";
               $lastdate = $thisdate;
            }
            $row = ($i % 2) ? "row2" : "row1";
            echo '<tr class="' . $row . '"><td style="text-align:right; padding-right:10px;" rowspan="2">' . "\n";
            if ($list[$i]["allday"]) echo __("All Day");
            elseif ($list[$i]["entry_type"] == 3 OR $list[$i]["entry_type"] == 4) echo __("Side Box");
            else echo pem_date($time_format, $list[$i]["when_begin"]) . ' - ' . pem_date($time_format, $list[$i]["when_end"]);
            echo '</td><td style="text-align:left;">' . "\n";
            echo '<a href="../view.php?did=' . $list[$i]["id"] . '">';
            if ($list[$i]["entry_cancelled"] OR $list[$i]["date_cancelled"])
            {
               echo "[" . __("CANCELLED") . "] ";
            }
            echo $list[$i]["entry_name"] . '</a>';
            echo '</td></tr>' . "\n";
            echo '<tr class="' . $row . '"><td style="text-align:left;">' . "\n";
            echo $list[$i]["spaces_text"];
            echo '</td></tr>' . "\n";
         }
         echo '</table>' . "\n";
      }
      break;
   case ("recurring"):
      $datetitle = __("Recurring Events");
      $select_fields = array("d.id", "d.when_begin", "d.when_end", "d.allday", "d.spaces", "e.entry_name", "e.entry_type", "d.date_cancelled", "e.entry_cancelled");
      function echo_data($list)
      {
         global $date_format, $time_format;

         echo '<table cellspacing="0" class="datalist">' . "\n";
         $lastdate = "";
         for ($i = 0; $i < count($list); $i++)
         {
            $thisdate = pem_date($date_format, $list[$i]["when_begin"]);
            if ($thisdate != $lastdate)
            {
               echo '<tr class="' . $row . '"><td class="dateheader"  style="padding-left:5px;" colspan="2">' . "\n";
               echo $thisdate;
               echo '</td></tr>' . "\n";
               $lastdate = $thisdate;
            }
            $row = ($i % 2) ? "row2" : "row1";
            echo '<tr class="' . $row . '"><td style="text-align:right; padding-right:10px;" rowspan="2">' . "\n";
            if ($list[$i]["allday"]) echo __("All Day");
            elseif ($list[$i]["entry_type"] == 3 OR $list[$i]["entry_type"] == 4) echo __("Side Box");
            else echo pem_date($time_format, $list[$i]["when_begin"]) . ' - ' . pem_date($time_format, $list[$i]["when_end"]);
            echo '</td><td style="text-align:left;">' . "\n";
            echo '<a href="../view.php?did=' . $list[$i]["id"] . '">';
            if ($list[$i]["entry_cancelled"] OR $list[$i]["date_cancelled"])
            {
               echo "[" . __("CANCELLED") . "] ";
            }
            echo $list[$i]["entry_name"] . '</a>';
            echo '</td></tr>' . "\n";
            echo '<tr class="' . $row . '"><td style="text-align:left;">' . "\n";
            echo $list[$i]["spaces_text"];
            echo '</td></tr>' . "\n";
         }
         echo '</table>' . "\n";
      }
      break;
   case ("creation"):
      $datetitle = __("Events by Creation Date");
      $select_fields = array("d.id", "d.when_begin", "d.allday", "d.date_created_by", "d.date_created_stamp", "e.entry_created_by", "e.entry_created_stamp", "e.entry_name", "e.entry_type", "d.date_cancelled", "e.entry_cancelled");
      $where_dates = " AND ((d.date_created_stamp <= :when_begin_before AND d.date_created_stamp >= :when_end_after) OR (e.entry_created_stamp <= :when_begin_before AND e.entry_created_stamp >= :when_end_after))";
      $order_by = "e.entry_created_stamp, e.entry_name";

      $sql = "SELECT user_login, user_nicename FROM " . $table_prefix . "users";
      $pemdb =& mdb2_connect($dsn, $options, "connect");
      $usertmp = pem_exec_sql($sql);
      foreach ($usertmp AS $values) $userlist[$values["user_login"]] = $values["user_nicename"];
      unset($usertmp);

      function echo_data($list)
      {
         global $date_format, $time_format, $userlist;

         echo '<table cellspacing="0" class="datalist">' . "\n";
         $lastdate = "";
         for ($i = 0; $i < count($list); $i++)
         {
            $thisdate = pem_date($date_format, $list[$i]["entry_created_stamp"]);
            if ($thisdate != $lastdate)
            {
               echo '<tr class="' . $row . '"><td class="dateheader"  style="padding-left:5px;" colspan="3">' . "\n";
               echo __("Created on") . " " . $thisdate;
               echo '</td></tr>' . "\n";
               $lastdate = $thisdate;
            }
            $row = ($i % 2) ? "row2" : "row1";
            $dif_date = ($list[$i]["date_created_stamp"] != $list[$i]["entry_created_stamp"]) ? true : false;
            $rowspan = ($dif_date) ? ' rowspan="2"' : "";
            echo '<tr class="' . $row . '"><td style="white-space:nowrap;"' . $rowspan . '>' . "\n";
            if (isset($userlist[$list[$i]["entry_created_by"]])) echo $userlist[$list[$i]["entry_created_by"]];
            else echo $list[$i]["entry_created_by"];
            echo '</td><td style="text-align:left; padding-right:5px;"' . $rowspan . '>' . "\n";
            echo pem_date($date_format . " " . $time_format, $list[$i]["when_begin"]);
            echo '</td><td style="text-align:left;">' . "\n";
            echo '<a href="../view.php?did=' . $list[$i]["id"] . '">';
            if ($list[$i]["entry_cancelled"] OR $list[$i]["date_cancelled"])
            {
               echo "[" . __("CANCELLED") . "] ";
            }
            echo $list[$i]["entry_name"] . '</a>';
            echo '</td></tr>' . "\n";
            if ($dif_date)
            {
               echo '<tr class="' . $row . '"><td style="text-align:left;">' . "\n";
               echo __("Date created on") . " " . pem_date($date_format, $list[$i]["entry_created_stamp"]) . " " . __("by") . " " . $list[$i]["date_created_by"];
               echo '</td></tr>' . "\n";
            }
         }
         echo '</table>' . "\n";
      }
      break;
   case ("activation"):
      $datetitle = __("Events by Activation Date");
      $select_fields = array("d.id", "d.when_begin", "d.allday", "d.date_approved_by", "d.date_approved_stamp", "e.entry_approved_by", "e.entry_approved_stamp", "e.entry_name", "e.entry_type", "d.date_cancelled", "e.entry_cancelled");
      $where_dates = " AND ((d.date_approved_stamp <= :when_begin_before AND d.date_approved_stamp >= :when_end_after) OR (e.entry_approved_stamp <= :when_begin_before AND e.entry_approved_stamp >= :when_end_after))";
      $order_by = "e.entry_approved_stamp, e.entry_name";

      $sql = "SELECT user_login, user_nicename FROM " . $table_prefix . "users";
      $pemdb =& mdb2_connect($dsn, $options, "connect");
      $usertmp = pem_exec_sql($sql);
      foreach ($usertmp AS $values) $userlist[$values["user_login"]] = $values["user_nicename"];
      unset($usertmp);

      function echo_data($list)
      {
         global $date_format, $time_format, $userlist;

         echo '<table cellspacing="0" class="datalist">' . "\n";
         $lastdate = "";
         for ($i = 0; $i < count($list); $i++)
         {
            $thisdate = pem_date($date_format, $list[$i]["entry_approved_stamp"]);
            if ($thisdate != $lastdate)
            {
               echo '<tr class="' . $row . '"><td class="dateheader"  style="padding-left:5px;" colspan="3">' . "\n";
               echo __("Activated on") . " " . $thisdate;
               echo '</td></tr>' . "\n";
               $lastdate = $thisdate;
            }
            $row = ($i % 2) ? "row2" : "row1";
            $dif_date = ($list[$i]["date_approved_stamp"] != $list[$i]["entry_approved_stamp"]) ? true : false;
            $rowspan = ($dif_date) ? ' rowspan="2"' : "";
            echo '<tr class="' . $row . '"><td style="white-space:nowrap;"' . $rowspan . '>' . "\n";
            if (isset($userlist[$list[$i]["entry_approved_by"]])) echo $userlist[$list[$i]["entry_approved_by"]];
            else echo $list[$i]["entry_approved_by"];
            echo '</td><td style="text-align:left; padding-right:5px;"' . $rowspan . '>' . "\n";
            echo pem_date($date_format . " " . $time_format, $list[$i]["when_begin"]);
            echo '</td><td style="text-align:left;">' . "\n";
            echo '<a href="../view.php?did=' . $list[$i]["id"] . '">';
            if ($list[$i]["entry_cancelled"] OR $list[$i]["date_cancelled"])
            {
               echo "[" . __("CANCELLED") . "] ";
            }
            echo $list[$i]["entry_name"] . '</a>';
            echo '</td></tr>' . "\n";
            if ($dif_date)
            {
               echo '<tr class="' . $row . '"><td style="text-align:left;">' . "\n";
               echo __("Date activated on") . " " . pem_date($date_format, $list[$i]["entry_approved_stamp"]) . " " . __("by") . " " . $list[$i]["date_approved_by"];
               echo '</td></tr>' . "\n";
            }
         }
         echo '</table>' . "\n";
      }
      break;
   case ("location"):
      $datetitle = __("Events by Location");
      $select_fields = array("d.id", "d.when_begin", "d.allday", "d.when_end", "d.spaces", "e.entry_name", "e.entry_type", "d.date_cancelled", "e.entry_cancelled");
      $check_options = pem_get_rows("spaces", "", "", "area");
      function echo_data($list)
      {
         global $date_format, $time_format, $location;

         if (!empty($location)) $location_keys = array_keys($location);
         echo '<table cellspacing="0" class="datalist">' . "\n";
         $lastdate = "";
         for ($i = 0; $i < count($list); $i++)
         {
            if (!empty($location))
            {
               $spaces_tmp = unserialize($list[$i]["spaces"]);
               if (is_array($spaces_tmp)) $spaces = $spaces_tmp;
               else $spaces[] = $spaces_tmp;
               $found_spaces = (is_array($spaces)) ? array_intersect($spaces, $location_keys) : "";
            }
            if (empty($location) OR !empty($found_spaces))
            {
               $thisdate = pem_date($date_format, $list[$i]["when_begin"]);
               if ($thisdate != $lastdate)
               {
                  echo '<tr class="' . $row . '"><td class="dateheader"  style="padding-left:5px;" colspan="2">' . "\n";
                  echo $thisdate;
                  echo '</td></tr>' . "\n";
                  $lastdate = $thisdate;
               }
               $row = ($i % 2) ? "row2" : "row1";
               echo '<tr class="' . $row . '"><td style="text-align:right; padding-right:10px; white-space:nowrap;" rowspan="2">' . "\n";
               if ($list[$i]["allday"]) echo __("All Day");
               else echo pem_simplify_times($list[$i]["when_begin"], $list[$i]["when_end"]);
               echo '</td><td style="text-align:left;">' . "\n";
               echo '<a href="../view.php?did=' . $list[$i]["id"] . '">';
               if ($list[$i]["entry_cancelled"] OR $list[$i]["date_cancelled"])
               {
                  echo "[" . __("CANCELLED") . "] ";
               }
               echo $list[$i]["entry_name"] . '</a>';
               echo '</td></tr>' . "\n";
               echo '<tr class="' . $row . '"><td style="text-align:left;">' . "\n";
               echo $list[$i]["spaces_text"];
               echo '</td></tr>' . "\n";
            }
         }
         echo '</table>' . "\n";
      }
      break;
   case ("category"):
      $datetitle = __("Events by Category");
      $select_fields = array("d.id", "d.when_begin", "d.allday", "d.when_end", "d.spaces", "e.entry_name", "e.entry_type", "d.date_cancelled", "e.entry_cancelled");
      if (isset($date_begin_month) AND isset($category))
      {
         $where_add = " AND (";
         $category_keys = array_keys($category);
         for ($i = 0; $i < count($category_keys); $i++)
         {
            $where_add .= "d.date_category = " . $category_keys[$i] . " OR e.entry_category = " . $category_keys[$i];
            if ($i < (count($category_keys) - 1)) $where_add .= " OR ";
         }
         $where_add .= ")";
      }
      $check_options = pem_get_rows("categories");
      function echo_data($list)
      {
         global $date_format, $time_format;

         echo '<table cellspacing="0" class="datalist">' . "\n";
         $lastdate = "";
         for ($i = 0; $i < count($list); $i++)
         {
            $thisdate = pem_date($date_format, $list[$i]["when_begin"]);
            if ($thisdate != $lastdate)
            {
               echo '<tr class="' . $row . '"><td class="dateheader"  style="padding-left:5px;" colspan="2">' . "\n";
               echo $thisdate;
               echo '</td></tr>' . "\n";
               $lastdate = $thisdate;
            }
            $row = ($i % 2) ? "row2" : "row1";
            echo '<tr class="' . $row . '"><td style="text-align:right; padding-right:10px; white-space:nowrap;" rowspan="2">' . "\n";
            if ($list[$i]["allday"]) echo __("All Day");
            else echo pem_simplify_times($list[$i]["when_begin"], $list[$i]["when_end"]);
            echo '</td><td style="text-align:left;">' . "\n";
            echo '<a href="../view.php?did=' . $list[$i]["id"] . '">' . $list[$i]["entry_name"] . '</a>';
            echo '</td></tr>' . "\n";
            echo '<tr class="' . $row . '"><td style="text-align:left;">' . "\n";
            echo $list[$i]["spaces_text"];
            echo '</td></tr>' . "\n";
         }
         echo '</table>' . "\n";
      }
      break;
   case ("creator"):
      $datetitle = __("Events by Creator");
      $select_fields = array("d.id", "d.when_begin", "d.allday", "d.when_end", "d.spaces", "d.date_created_by", "d.date_created_stamp", "e.entry_created_by", "e.entry_created_stamp", "e.entry_name", "e.entry_type", "d.date_cancelled", "e.entry_cancelled");
      if (isset($date_begin_month) AND isset($creator))
      {
         $where_add = " AND (";
         $creator_keys = array_keys($creator);
         for ($i = 0; $i < count($creator_keys); $i++)
         {
            $where_add .= "d.date_created_by = '" . $creator_keys[$i] . "' OR e.entry_created_by = '" . $creator_keys[$i] . "'";
            if ($i < (count($creator_keys) - 1)) $where_add .= " OR ";
         }
         $where_add .= ")";
      }

      $sql = "SELECT id, user_nicename FROM " . $table_prefix . "users ORDER BY user_nicename";
      $pemdb =& mdb2_connect($dsn, $options, "connect");
      $usertmp = pem_exec_sql($sql);
      $longest = 0;
      foreach ($usertmp AS $values)
      {
         $userlist[$values["id"]] = $values["user_nicename"];
         $len = strlen($values["user_nicename"]);
         if ($len > $longest) $longest = $len;
      }
      unset($len);
      unset($usertmp);

      $check_options = $userlist;
      $check_options["length"] = $longest;

      function echo_data($list)
      {
         global $date_format, $time_format, $userlist;

         echo '<table cellspacing="0" class="datalist">' . "\n";
         $lastdate = "";
         for ($i = 0; $i < count($list); $i++)
         {
            $thisdate = pem_date($date_format, $list[$i]["entry_created_stamp"]);
            if ($thisdate != $lastdate)
            {
               echo '<tr class="' . $row . '"><td class="dateheader"  style="padding-left:5px;" colspan="3">' . "\n";
               echo __("Created on") . " " . $thisdate;
               echo '</td></tr>' . "\n";
               $lastdate = $thisdate;
            }
            $row = ($i % 2) ? "row2" : "row1";
            $dif_date = ($list[$i]["date_created_stamp"] != $list[$i]["entry_created_stamp"]) ? true : false;
            $rowspan = ($dif_date) ? ' rowspan="2"' : "";
            echo '<tr class="' . $row . '"><td style="padding-right:10px; white-space:nowrap;"' . $rowspan . '>' . "\n";
            if (isset($userlist[$list[$i]["entry_created_by"]])) echo $userlist[$list[$i]["entry_created_by"]];
            else echo $list[$i]["entry_created_by"];
            echo '</td><td style="text-align:left; padding-right:5px;"' . $rowspan . '>' . "\n";
            echo pem_date($date_format . " " . $time_format, $list[$i]["when_begin"]);
            echo '</td><td style="text-align:left;">' . "\n";
            echo '<a href="../view.php?did=' . $list[$i]["id"] . '">';
            if ($list[$i]["entry_cancelled"] OR $list[$i]["date_cancelled"])
            {
               echo "[" . __("CANCELLED") . "] ";
            }
            echo $list[$i]["entry_name"] . '</a>';
            echo '</td></tr>' . "\n";
            if ($dif_date)
            {
               echo '<tr class="' . $row . '"><td style="text-align:left;">' . "\n";
               echo __("Date created on") . " " . pem_date($date_format, $list[$i]["entry_created_stamp"]) . " " . __("by") . " " . $list[$i]["date_created_by"];
               echo '</td></tr>' . "\n";
            }
         }
         echo '</table>' . "\n";
      }
      break;
   case ("approver"):
      $datetitle = __("Events by Approver");
      $select_fields = array("d.id", "d.when_begin", "d.allday", "d.when_end", "d.spaces", "d.date_approved_by", "d.date_approved_stamp", "e.entry_approved_by", "e.entry_approved_stamp", "e.entry_name", "e.entry_type", "d.date_cancelled", "e.entry_cancelled");
      if (isset($date_begin_month) AND isset($approver))
      {
         $where_add = " AND (";
         $approver_keys = array_keys($approver);
         for ($i = 0; $i < count($approver_keys); $i++)
         {
            $where_add .= "d.date_approved_by = '" . $approver_keys[$i] . "' OR e.entry_approved_by = '" . $approver_keys[$i] . "'";
            if ($i < (count($approver_keys) - 1)) $where_add .= " OR ";
         }
         $where_add .= ")";
      }

      $sql = "SELECT id, user_nicename FROM " . $table_prefix . "users ORDER BY user_nicename";
      $pemdb =& mdb2_connect($dsn, $options, "connect");
      $usertmp = pem_exec_sql($sql);
      $longest = 0;
      foreach ($usertmp AS $values)
      {
         $userlist[$values["id"]] = $values["user_nicename"];
         $len = strlen($values["user_nicename"]);
         if ($len > $longest) $longest = $len;
      }
      unset($len);
      unset($usertmp);

      $check_options = $userlist;
      $check_options["length"] = $longest;

      function echo_data($list)
      {
         global $date_format, $time_format, $userlist;

         echo '<table cellspacing="0" class="datalist">' . "\n";
         $lastdate = "";
         for ($i = 0; $i < count($list); $i++)
         {
            $thisdate = pem_date($date_format, $list[$i]["entry_approved_stamp"]);
            if ($thisdate != $lastdate)
            {
               echo '<tr class="' . $row . '"><td class="dateheader"  style="padding-left:5px;" colspan="3">' . "\n";
               echo __("Approved on") . " " . $thisdate;
               echo '</td></tr>' . "\n";
               $lastdate = $thisdate;
            }
            $row = ($i % 2) ? "row2" : "row1";
            $dif_date = ($list[$i]["date_approved_stamp"] != $list[$i]["entry_approved_stamp"]) ? true : false;
            $rowspan = ($dif_date) ? ' rowspan="2"' : "";
            echo '<tr class="' . $row . '"><td style="padding-right:10px; white-space:nowrap;"' . $rowspan . '>' . "\n";
            if (isset($userlist[$list[$i]["entry_approved_by"]])) echo $userlist[$list[$i]["entry_approved_by"]];
            else echo $list[$i]["entry_approved_by"];
            echo '</td><td style="text-align:left; padding-right:5px;"' . $rowspan . '>' . "\n";
            echo pem_date($date_format . " " . $time_format, $list[$i]["when_begin"]);
            echo '</td><td style="text-align:left;">' . "\n";
            echo '<a href="../view.php?did=' . $list[$i]["id"] . '">';
            if ($list[$i]["entry_cancelled"] OR $list[$i]["date_cancelled"])
            {
               echo "[" . __("CANCELLED") . "] ";
            }
            echo $list[$i]["entry_name"] . '</a>';
            echo '</td></tr>' . "\n";
            if ($dif_date)
            {
               echo '<tr class="' . $row . '"><td style="text-align:left;">' . "\n";
               echo __("Date approved on") . " " . pem_date($date_format, $list[$i]["entry_approved_stamp"]) . " " . __("by") . " " . $list[$i]["date_approved_by"];
               echo '</td></tr>' . "\n";
            }
         }
         echo '</table>' . "\n";
      }
      break;
   case ("cancelled"):
      $datetitle = __("Cancelled Events");
      $select_fields = array("d.id", "d.when_begin", "d.when_end", "d.allday", "d.spaces", "e.entry_name", "e.entry_type", "d.date_cancelled", "e.entry_cancelled");
      $where_add = " AND (d.date_cancelled = 1 OR e.entry_cancelled = 1)";
      function echo_data($list)
      {
         global $date_format, $time_format;

         echo '<table cellspacing="0" class="datalist">' . "\n";
         $lastdate = "";
         for ($i = 0; $i < count($list); $i++)
         {
            $thisdate = pem_date($date_format, $list[$i]["when_begin"]);
            if ($thisdate != $lastdate)
            {
               echo '<tr class="' . $row . '"><td class="dateheader"  style="padding-left:5px;" colspan="2">' . "\n";
               echo $thisdate;
               echo '</td></tr>' . "\n";
               $lastdate = $thisdate;
            }
            $row = ($i % 2) ? "row2" : "row1";
            echo '<tr class="' . $row . '"><td style="text-align:right; padding-right:10px;" rowspan="2">' . "\n";
            if ($list[$i]["allday"]) echo __("All Day");
            elseif ($list[$i]["entry_type"] == 3 OR $list[$i]["entry_type"] == 4) echo __("Side Box");
            else echo pem_date($time_format, $list[$i]["when_begin"]) . ' - ' . pem_date($time_format, $list[$i]["when_end"]);
            echo '</td><td style="text-align:left;">' . "\n";
            echo '<a href="../view.php?did=' . $list[$i]["id"] . '">';
            if ($list[$i]["entry_cancelled"] OR $list[$i]["date_cancelled"])
            {
               echo "[" . __("CANCELLED") . "] ";
            }
            echo $list[$i]["entry_name"] . '</a>';
            echo '</td></tr>' . "\n";
            echo '<tr class="' . $row . '"><td style="text-align:left;">' . "\n";
            echo $list[$i]["spaces_text"];
            echo '</td></tr>' . "\n";
         }
         echo '</table>' . "\n";
      }
      break;


// ICPL HACK - custom hardcoded report handling
   case ("press"):
      $datetitle = __("Press Release");
      $select_fields = array("d.id", "d.when_begin", "d.when_end", "d.allday", "d.spaces", "e.entry_name", "e.entry_type", "d.date_name", "e.entry_description", "d.date_description", "e.entry_open_to_public", "d.date_open_to_public", "e.entry_meta", "d.date_meta", "d.date_cancelled", "e.entry_cancelled");
      $where_add = " AND (d.date_visible_to_public = 1 AND e.entry_visible_to_public = 1) AND (d.date_status = 1 AND e.entry_status = 1)";

      function echo_data($list)
      {
         global $date_format, $time_format;

         echo '<div style="margin:0 10px;">' . "\n";

         $lastdate = "";
         for ($i = 0; $i < count($list); $i++)
         {
            $thisdate = pem_date($date_format, $list[$i]["when_begin"]);
            if ($thisdate != $lastdate)
            {
               echo '<br />---------------------------------------<br />' . "\n";
               echo $thisdate;
               echo '<br />' . "\n";
               $lastdate = $thisdate;
            }
            $row = ($i % 2) ? "row2" : "row1";
            echo '<div style="margin:10px 0;">' . "\n";
            echo '---------------------------------------<br />' . "\n";
            if ($list[$i]["allday"]) echo __("All Day:");
            elseif ($list[$i]["entry_type"] == 3 OR $list[$i]["entry_type"] == 4) echo __("Display:");
            else echo pem_date($time_format, $list[$i]["when_begin"]) . ' - ' . pem_date($time_format, $list[$i]["when_end"]);
            echo ' ';
            if ($list[$i]["entry_cancelled"] OR $list[$i]["date_cancelled"])
            {
               echo "[" . __("CANCELLED") . "] ";
            }
            $title = (!empty($list[$i]["date_name"])) ? $list[$i]["entry_name"] . ': ' . $list[$i]["date_name"] : $list[$i]["entry_name"];
            echo $title . '<br />' . "\n";
            echo $list[$i]["spaces_text"] . '<br />' . "\n";

            $dmeta = unserialize($list[$i]["date_meta"]);
            $meta_text = "";
            $meta_contacts = "";
            if (is_array($dmeta)) foreach($dmeta AS $meta_id => $meta)
               {
                  if ($meta["type"] == "textinput")
                  {
                     $data = pem_get_row("id", $meta_id, "meta");
                     $meta_data = unserialize($data["value"]);
                     if (!empty($meta["data"])) $meta_text .= $meta_data["input_label"] . ' ' . $meta["data"] . '<br />' . "\n";
                  }
                  if ($meta["type"] == "contact")
                  {
                     $data = pem_get_row("id", $meta_id, "meta");
                     $meta_data = unserialize($data["value"]);
//echo "<br />----------------ID: $meta_id ---------------<br />";
//              print_r($meta_data);
//echo "<br />-------------------------<br />";
                     if (!empty($meta["name1"])) $meta_contacts .= $meta_data["name1"][0] . ' ' . $meta["name1"] . '<br />' . "\n";
                     if (!empty($meta["name2"])) $meta_contacts .= $meta_data["name2"][0] . ' ' . $meta["name2"] . '<br />' . "\n";
                     if (!empty($meta["phone1"])) $meta_contacts .= $meta_data["phone1"][0] . ' ' . $meta["phone1"] . '<br />' . "\n";
                     if (!empty($meta["phone2"])) $meta_contacts .= $meta_data["phone2"][0] . ' ' . $meta["phone2"] . '<br />' . "\n";
                     if (!empty($meta["email"])) $meta_contacts .= $meta_data["email"][0] . ' ' . $meta["email"] . '<br />' . "\n";
                  }
               }
            $emeta = unserialize($list[$i]["entry_meta"]);
            if (is_array($emeta)) foreach($emeta AS $meta_id => $meta)
               {
                  if ($meta["type"] == "textinput")
                  {
                     $data = pem_get_row("id", $meta_id, "meta");
                     $meta_data = unserialize($data["value"]);
                     if (!empty($meta["data"]))  $meta_text .= $meta_data["input_label"] . ' ' . $meta["data"] . '<br />' . "\n";
                  }
                  if ($meta["type"] == "contact")
                  {
                     $data = pem_get_row("id", $meta_id, "meta");
                     $meta_data = unserialize($data["value"]);
//echo "<br />----------------ID: $meta_id ---------------<br />";
//              print_r($meta_data);
//echo "<br />-------------------------<br />";
                     if (!empty($meta["name1"])) $meta_contacts .= $meta_data["name1"][0] . ' ' . $meta["name1"] . '<br />' . "\n";
                     if (!empty($meta["name2"])) $meta_contacts .= $meta_data["name2"][0] . ' ' . $meta["name2"] . '<br />' . "\n";
                     if (!empty($meta["phone1"])) $meta_contacts .= $meta_data["phone1"][0] . ' ' . $meta["phone1"] . '<br />' . "\n";
                     if (!empty($meta["phone2"])) $meta_contacts .= $meta_data["phone2"][0] . ' ' . $meta["phone2"] . '<br />' . "\n";
                     if (!empty($meta["email"])) $meta_contacts .= $meta_data["email"][0] . ' ' . $meta["email"] . '<br />' . "\n";
                  }
               }
            if (!empty($meta_text)) echo $meta_text;
            if (!empty($meta_contacts)) echo $meta_contacts;


//echo "<br />";
//$meta = unserialize($list[$i]["date_meta"]);
//print_r($meta);
//$meta = unserialize($list[$i]["entry_meta"]);
//print_r($meta);
//echo "<br />";

            echo ($list[$i]["entry_open_to_public"] AND $list[$i]["date_open_to_public"]) ? __("Open to the public") : __("Closed to the public");
            if (!empty($list[$i]["date_description"]) OR !empty($list[$i]["entry_description"]))
            {
               echo '<br />' . __("Details:") . ' ' . "\n";
               if (!empty($list[$i]["date_description"])) echo $list[$i]["date_description"] . '<br />' . "\n";
               if (!empty($list[$i]["entry_description"])) echo $list[$i]["entry_description"] . '<br />' . "\n";
            }
            echo '</div>' . "\n";

         }
         echo '</div>' . "\n";

      }
      break;
   case ("public-original"):
      $datetitle = __("Public Calendar Posting");
      $select_fields = array("d.id", "d.when_begin", "d.when_end", "d.real_begin", "d.real_end", "d.allday", "d.spaces", "e.entry_name", "e.entry_type", "e.entry_description", "d.date_description", "d.date_cancelled", "e.entry_cancelled");
      $where_add = " AND (d.date_visible_to_public = 1 AND e.entry_visible_to_public = 1)";
      function echo_data($list)
      {
         global $date_format, $time_format;

         echo '<div style="padding:30px; border:1px solid #000;">' . "\n";
         echo '<table cellspacing="0" style="font-size:120%; font-family:arial;">' . "\n";
         $lastdate = "";
         $firstdate = true;
         $displayrows = "";
         $eventrows = "";
         for ($i = 0; $i < count($list); $i++)
         {
            $thisdate = pem_date($date_format, $list[$i]["when_begin"]);
            if ($list[$i]["entry_type"] == 3 OR $list[$i]["entry_type"] == 4)
            {
               $displayrows .= '<tr><td valign="top" colspan="2" style="text-align:left; padding:20px 20px 10px 0; font-weight:bold;">' . "\n";
               $displayrows .= $list[$i]["spaces_text"];
               $displayrows .= '</td><td style="text-align:left; padding:20px 0 10px 0;">' . "\n";
               $displayrows .= '<span style="font-weight:bold;">' . "\n";
               if ($list[$i]["entry_cancelled"] OR $list[$i]["date_cancelled"]) $displayrows .= "[" . __("CANCELLED") . "] ";
               $title = (!empty($list[$i]["date_name"])) ? $list[$i]["entry_name"] . ': ' . $list[$i]["date_name"] : $list[$i]["entry_name"];
               $displayrows .= $title . '</span><br />';
               $displayrows .= $list[$i]["date_description"];
               if (!empty($list[$i]["entry_description"]) AND !empty($list[$i]["date_description"])) $displayrows .= '<br />';
               $displayrows .= $list[$i]["entry_description"];
               $displayrows .= '</td></tr>' . "\n";

            }
            else
            {
               if ($thisdate != $lastdate)
               {
                  $styleadd = (!$firstdate) ? ' style="border-top:1px solid #000; padding:20px 0 0 0;"' : "";
                  $styleadd2 = (!$firstdate) ? ' padding:20px 0 0 0;' : "";
                  echo '<tr' . $styleadd . '><td style="text-align:center; font-size:200%;' . $styleadd2 . '" colspan="3">' . "\n";
                  echo $thisdate;
                  echo '</td></tr>' . "\n";
                  $lastdate = $thisdate;
                  $firstdate = false;
               }
               echo '<tr><td valign="top" style="text-align:right; padding:20px 20px 10px 0; white-space:nowrap; font-weight:bold;">' . "\n";
               if ($list[$i]["allday"]) echo  __("All Day");
               else echo pem_simplify_times($list[$i]["when_begin"], $list[$i]["when_end"]);
               echo '</td><td valign="top" style="text-align:left; padding:20px 20px 10px 0; font-weight:bold;">' . "\n";
               echo $list[$i]["spaces_text"];
               echo '</td><td valign="top" style="text-align:left; padding:20px 0 10px 0;">' . "\n";
               echo '<span style="font-weight:bold;">' . "\n";
               if ($list[$i]["entry_cancelled"] OR $list[$i]["date_cancelled"]) echo "[" . __("CANCELLED") . "] ";
               $title = (!empty($list[$i]["date_name"])) ? $list[$i]["entry_name"] . ': ' . $list[$i]["date_name"] : $list[$i]["entry_name"];
               echo $title . '</span><br />';
               echo $list[$i]["date_description"];
               if (!empty($list[$i]["entry_description"]) AND !empty($list[$i]["date_description"])) echo '<br />';
               echo $list[$i]["entry_description"];
               echo '</td></tr>' . "\n";
            }
         }
         if (!empty($displayrows))
         {
            echo '<tr style="border-top:1px solid #000; padding:20px 0 0 0;"><td style="text-align:center; font-size:200%; padding:20px 0 0 0;" colspan="3">' . "\n";
            echo __("Current Displays");
            echo '</td></tr>' . "\n";
            echo $displayrows;


         }

         echo '</table>' . "\n";
      }
      break;
   case ("public"):
      $datetitle = __("Public Calendar Posting");
      $select_fields = array("d.id", "d.when_begin", "d.when_end", "d.real_begin", "d.real_end", "d.allday", "d.spaces", "e.entry_name", "e.entry_type", "e.entry_description", "d.date_description", "d.date_cancelled", "e.entry_cancelled, d.date_open_to_public, e.entry_open_to_public");
      $where_add = " AND (d.date_visible_to_public = 1 AND e.entry_visible_to_public = 1) AND (d.date_status = 1 AND e.entry_status = 1)";
      function echo_data($list)
      {
         global $date_format, $time_format;

         echo '<div style="padding:30px; border:1px solid #000;">' . "\n";
         echo '<table cellspacing="0" style="font-size:120%; font-family:arial;">' . "\n";
         $lastdate = "";
         $firstdate = true;
         $displayrows = "";
         $eventrows = "";
         for ($i = 0; $i < count($list); $i++)
         {
            $thisdate = pem_date($date_format, $list[$i]["when_begin"]);
            if ($list[$i]["entry_type"] == 3 OR $list[$i]["entry_type"] == 4)
            {
               $displayrows .= '<tr><td valign="top" style="text-align:right; padding:20px 20px 10px 0; font-weight:bold;">' . "\n";
               $displayrows .= $list[$i]["spaces_text"];
               $displayrows .= '</td><td style="text-align:left; padding:20px 0 10px 0;">' . "\n";
               $displayrows .= '<span style="font-weight:bold;">' . "\n";
               if ($list[$i]["entry_cancelled"] OR $list[$i]["date_cancelled"]) $displayrows .= "[" . __("CANCELLED") . "] ";
               $title = (!empty($list[$i]["date_name"])) ? $list[$i]["entry_name"] . ': ' . $list[$i]["date_name"] : $list[$i]["entry_name"];
               $displayrows .= $title . '</span><br />';
               $displayrows .= $list[$i]["date_description"];
               if (!empty($list[$i]["entry_description"]) AND !empty($list[$i]["date_description"])) $displayrows .= '<br />';
               $displayrows .= $list[$i]["entry_description"];
               $displayrows .= '</td></tr>' . "\n";

            }
            else
            {
               if ($thisdate != $lastdate)
               {
                  $styleadd = (!$firstdate) ? ' style="border-top:1px solid #000; padding:20px 0 0 0;"' : "";
                  $styleadd2 = (!$firstdate) ? ' padding:20px 0 0 0;' : "";
                  echo '<tr' . $styleadd . '><td style="text-align:center; font-size:200%;' . $styleadd2 . '" colspan="3">' . "\n";
                  echo $thisdate;
                  echo '</td></tr>' . "\n";
                  $lastdate = $thisdate;
                  $firstdate = false;
               }
               echo '<tr><td valign="top" style="text-align:right; padding:20px 20px 10px 0; white-space:nowrap; font-weight:bold;">' . "\n";
               if ($list[$i]["allday"]) echo  __("All Day");
               else echo pem_simplify_times($list[$i]["when_begin"], $list[$i]["when_end"]);
               echo '<br />' . "\n";
               echo $list[$i]["spaces_text"];
               echo '</td><td valign="top" style="text-align:left; padding:20px 0 10px 0;">' . "\n";
               echo '<span style="font-weight:bold;">' . "\n";
               if ($list[$i]["entry_cancelled"] OR $list[$i]["date_cancelled"]) echo "[" . __("CANCELLED") . "] ";
               $title = (!empty($list[$i]["date_name"])) ? $list[$i]["entry_name"] . ': ' . $list[$i]["date_name"] : $list[$i]["entry_name"];
               echo $title . '</span><br />';
               echo $list[$i]["date_description"];
               if (!empty($list[$i]["entry_description"]) AND !empty($list[$i]["date_description"])) echo '<br />';
               echo $list[$i]["entry_description"];
               if (!$list[$i]["entry_open_to_public"] OR !$list[$i]["date_open_to_public"]) echo " " . __("This event is not open to the public.");
               echo '</td></tr>' . "\n";
            }
         }
         if (!empty($displayrows))
         {
            echo '<tr style="border-top:1px solid #000; padding:20px 0 0 0;"><td style="text-align:center; font-size:200%; padding:20px 0 0 0;" colspan="3">' . "\n";
            echo __("Current Displays");
            echo '</td></tr>' . "\n";
            echo $displayrows;


         }

         echo '</table>' . "\n";
      }
      break;
   case ("notesdaily"):
      $datetitle = __("Maintenance and Pages Daily Listing");
      $select_fields = array("*");
      //$where_add = " AND (d.date_priv_notes != '' OR e.entry_priv_notes != '')";
      function echo_data($list)
      {
         global $date_format, $time_format;

         echo '<div style="padding:30px; border:1px solid #000;">' . "\n";
         echo '<table cellspacing="0" style="font-size:100%; font-family:arial;">' . "\n";
         $lastdate = "";
         $firstdate = true;
         $displayrows = "";
         $eventrows = "";
         for ($i = 0; $i < count($list); $i++)
         {

            $thisdate = pem_date($date_format, $list[$i]["when_begin"]);

            if ($list[$i]["entry_type"] == 3 OR $list[$i]["entry_type"] == 4)
            {
               $displayrows .= '<tr><td valign="top" colspan="2" style="text-align:left; padding:20px 20px 10px 0; font-weight:bold;">' . "\n";
               $displayrows .= $list[$i]["spaces_text"];
               $displayrows .= '</td><td style="text-align:left; padding:20px 0 10px 0;">' . "\n";
               $displayrows .= '<span style="font-weight:bold;">' . "\n";
               if ($list[$i]["entry_cancelled"] OR $list[$i]["date_cancelled"]) $displayrows .= "[" . __("CANCELLED") . "] ";
               $title = (!empty($list[$i]["date_name"])) ? $list[$i]["entry_name"] . ': ' . $list[$i]["date_name"] : $list[$i]["entry_name"];
               $displayrows .= $title . '</span><br />';
               $displayrows .= $list[$i]["date_description"];
               if (!empty($list[$i]["entry_description"]) AND !empty($list[$i]["date_description"])) $displayrows .= '<br />';
               $displayrows .= $list[$i]["entry_description"];

               if (!empty($list[$i]["entry_priv_notes"]) OR !empty($list[$i]["date_priv_notes"])) $displayrows .= '<span style="color:#600; font-weight:bold;">Notes: ';
               $displayrows .= $list[$i]["date_priv_notes"];
               if (!empty($list[$i]["entry_priv_notes"]) AND !empty($list[$i]["date_priv_notes"])) $displayrows .= '<br />';
               $displayrows .= $list[$i]["entry_priv_notes"] . "<br />";
               if (!empty($list[$i]["entry_priv_notes"]) OR !empty($list[$i]["date_priv_notes"])) $displayrows .= '</span>' . "\n";

               $displayrows .= '</td></tr>' . "\n";

            }
            else
            {
               if ($thisdate != $lastdate)
               {
                  $styleadd = (!$firstdate) ? ' style="border-top:1px solid #000; padding:20px 0 0 0;"' : "";
                  $styleadd2 = (!$firstdate) ? ' padding:20px 0 0 0;' : "";
                  echo '<tr' . $styleadd . '><td style="font-size:200%;' . $styleadd2 . '" colspan="3">' . "\n";
                  echo $thisdate;
                  echo '</td></tr>' . "\n";
                  $lastdate = $thisdate;
                  $firstdate = false;
               }
               echo '<tr><td valign="top" style="text-align:right; padding:20px 20px 10px 0; white-space:nowrap; font-weight:bold;">' . "\n";
               if ($list[$i]["allday"]) echo  __("All Day");
               else
               {
                  echo pem_simplify_times($list[$i]["when_begin"], $list[$i]["when_end"]);
                  echo "<br />(";
                  echo pem_simplify_times($list[$i]["real_begin"], $list[$i]["real_end"]);
                  echo ")";
               }
               echo '</td><td valign="top" style="text-align:left; padding:20px 20px 10px 0; font-weight:bold;">' . "\n";
               echo $list[$i]["spaces_text"];
               echo '</td><td valign="top" style="text-align:left; padding:20px 0 10px 0;">' . "\n";
               echo '<span style="font-weight:bold; font-size:130%;">' . "\n";
               if ($list[$i]["entry_cancelled"] OR $list[$i]["date_cancelled"]) echo "[" . __("CANCELLED") . "] ";
               $title = (!empty($list[$i]["date_name"])) ? $list[$i]["entry_name"] . ': ' . $list[$i]["date_name"] : $list[$i]["entry_name"];
               echo $title . '</span><br />';
               echo $list[$i]["date_description"];
               if (!empty($list[$i]["entry_description"]) AND !empty($list[$i]["date_description"])) echo '<br />';
               echo $list[$i]["entry_description"] . "<br />";

               if (is_array($dmeta)) foreach($dmeta AS $meta_id => $meta)
                  {
                     if ($meta["type"] == "textinput")
                     {
                        $data = pem_get_row("id", $meta_id, "meta");
                        $meta_data = unserialize($data["value"]);
                        if (!empty($meta["data"])) echo '<b>' . $meta_data["input_label"] . '</b> ' . $meta["data"] . '<br />' . "\n";
                     }
                     if ($meta["type"] == "contact")
                     {
                        $data = pem_get_row("id", $meta_id, "meta");
                        $meta_data = unserialize($data["value"]);
                        if (!empty($meta["name1"])) echo '<b>' . $meta_data["name1"][0] . '</b> ' . $meta["name1"] . '<br />' . "\n";
                        if (!empty($meta["name2"])) echo '<b>' . $meta_data["name2"][0] . '</b> ' . $meta["name2"] . '<br />' . "\n";
                        if (!empty($meta["phone1"])) echo '<b>' . $meta_data["phone1"][0] . '</b> ' . $meta["phone1"] . '<br />' . "\n";
                        if (!empty($meta["phone2"])) echo '<b>' . $meta_data["phone2"][0] . '</b> ' . $meta["phone2"] . '<br />' . "\n";
                        if (!empty($meta["email"])) echo '<b>' . $meta_data["email"][0] . '</b> ' . $meta["email"] . '<br />' . "\n";
                     }
                  }
               if (is_array($emeta)) foreach($emeta AS $meta_id => $meta)
                  {
                     if ($meta["type"] == "textinput")
                     {
                        $data = pem_get_row("id", $meta_id, "meta");
                        $meta_data = unserialize($data["value"]);
                        if (!empty($meta["data"])) echo '<b>' . $meta_data["input_label"] . '</b> ' . $meta["data"] . '<br />' . "\n";
                     }
                     if ($meta["type"] == "contact")
                     {
                        $data = pem_get_row("id", $meta_id, "meta");
                        $meta_data = unserialize($data["value"]);
                        if (!empty($meta["name1"])) echo '<b>' . $meta_data["name1"][0] . '</b> ' . $meta["name1"] . '<br />' . "\n";
                        if (!empty($meta["name2"])) echo '<b>' . $meta_data["name2"][0] . '</b> ' . $meta["name2"] . '<br />' . "\n";
                        if (!empty($meta["phone1"])) echo '<b>' . $meta_data["phone1"][0] . '</b> ' . $meta["phone1"] . '<br />' . "\n";
                        if (!empty($meta["phone2"])) echo '<b>' . $meta_data["phone2"][0] . '</b> ' . $meta["phone2"] . '<br />' . "\n";
                        if (!empty($meta["email"])) echo '<b>' . $meta_data["email"][0] . '</b> ' . $meta["email"] . '<br />' . "\n";
                     }
                  }
               if (!empty($list[$i]["entry_priv_notes"]) OR !empty($list[$i]["date_priv_notes"])) echo '<span style="color:#600; font-weight:bold;">Notes: ';
               echo $list[$i]["date_priv_notes"];
               if (!empty($list[$i]["entry_priv_notes"]) AND !empty($list[$i]["date_priv_notes"])) echo '<br />';
               echo $list[$i]["entry_priv_notes"] . "<br />";
               if (!empty($list[$i]["entry_priv_notes"]) OR !empty($list[$i]["date_priv_notes"])) echo '</span>' . "\n";

               if (isset($list[$i]["supplies_text"]))
               {
                  echo '<span style="color:#600; font-weight:bold;">' . __("Optional Supplies:") . ' ';
                  if (count($list[$i]["supplies_text"]) > 1) echo '<br />' . "\n";
                  foreach ($list[$i]["supplies_text"] AS $opt_supplies_text)
                  {
                     echo $opt_supplies_text . '<br />' . "\n";
                  }
                  echo '</span>' . "\n";
               }
               echo '</td></tr>' . "\n";
            }
         }
         if (!empty($displayrows))
         {
            echo '<tr style="border-top:1px solid #000; padding:20px 0 0 0;"><td style="text-align:center; font-size:200%; padding:20px 0 0 0;" colspan="3">' . "\n";
            echo __("Current Displays");
            echo '</td></tr>' . "\n";
            echo $displayrows;
         }
         echo '</table>' . "\n";
      }
      break;
   case ("channel"):
      $datetitle = __("Live on The Library Channel");
      $select_fields = array("*");
      $where_add = " AND (d.date_visible_to_public = 1 AND e.entry_visible_to_public = 1) AND (d.date_status = 1 AND e.entry_status = 1)";
      function echo_data($list)
      {
         global $date_format, $time_format;

         echo '<div style="padding:30px; border:1px solid #000;">' . "\n";
         echo '<table cellspacing="0" style="font-size:120%; font-family:arial;">' . "\n";
         $lastdate = "";
         $firstdate = true;
         $displayrows = "";
         $eventrows = "";
         for ($i = 0; $i < count($list); $i++)
         {
            $dmeta = unserialize($list[$i]["date_meta"]);
            $emeta = unserialize($list[$i]["entry_meta"]);

            if (is_array($dmeta) AND isset($dmeta[7]) AND $dmeta[7]["data"])
            {
               $thisdate = pem_date($date_format, $list[$i]["when_begin"]);

               if ($list[$i]["entry_type"] == 3 OR $list[$i]["entry_type"] == 4)
               {
                  $displayrows .= '<tr><td valign="top" colspan="2" style="text-align:left; padding:20px 20px 10px 0; font-weight:bold;">' . "\n";
                  $displayrows .= $list[$i]["spaces_text"];
                  $displayrows .= '</td><td style="text-align:left; padding:20px 0 10px 0;">' . "\n";
                  $displayrows .= '<span style="font-weight:bold;">' . "\n";
                  if ($list[$i]["entry_cancelled"] OR $list[$i]["date_cancelled"]) $displayrows .= "[" . __("CANCELLED") . "] ";
                  $title = (!empty($list[$i]["date_name"])) ? $list[$i]["entry_name"] . ': ' . $list[$i]["date_name"] : $list[$i]["entry_name"];
                  $displayrows .= $title . '</span><br />';
                  $displayrows .= $list[$i]["date_description"];
                  if (!empty($list[$i]["entry_description"]) AND !empty($list[$i]["date_description"])) $displayrows .= '<br />';
                  $displayrows .= $list[$i]["entry_description"];
                  $displayrows .= '</td></tr>' . "\n";
               }
               else
               {
                  if ($thisdate != $lastdate)
                  {
                     $styleadd = (!$firstdate) ? ' style="border-top:1px solid #000; padding:20px 0 0 0;"' : "";
                     $styleadd2 = (!$firstdate) ? ' padding:20px 0 0 0;' : "";
                     echo '<tr' . $styleadd . '><td style="font-size:200%;' . $styleadd2 . '" colspan="3">' . "\n";
                     echo $thisdate;
                     echo '</td></tr>' . "\n";
                     $lastdate = $thisdate;
                     $firstdate = false;
                  }
                  echo '<tr><td valign="top" style="text-align:right; padding:20px 20px 10px 0; white-space:nowrap; font-weight:bold;">' . "\n";
                  if ($list[$i]["allday"]) echo  __("All Day");
                  else
                  {
                     echo pem_simplify_times($list[$i]["when_begin"], $list[$i]["when_end"]);
                     echo "<br />(";
                     echo pem_simplify_times($list[$i]["real_begin"], $list[$i]["real_end"]);
                     echo ")";
                  }
                  echo '</td><td valign="top" style="text-align:left; padding:20px 20px 10px 0; font-weight:bold;">' . "\n";
                  echo $list[$i]["spaces_text"];
                  echo '</td><td valign="top" style="text-align:left; padding:20px 0 10px 0;">' . "\n";
                  echo '<span style="font-weight:bold; font-size:130%;">' . "\n";
                  if ($list[$i]["entry_cancelled"] OR $list[$i]["date_cancelled"]) echo "[" . __("CANCELLED") . "] ";
                  $title = (!empty($list[$i]["date_name"])) ? $list[$i]["entry_name"] . ': ' . $list[$i]["date_name"] : $list[$i]["entry_name"];
                  echo $title . '</span><br />';
                  echo $list[$i]["date_description"];
                  if (!empty($list[$i]["entry_description"]) AND !empty($list[$i]["date_description"])) echo '<br />';
                  echo $list[$i]["entry_description"] . "<br />";

                  if (is_array($dmeta)) foreach($dmeta AS $meta_id => $meta)
                     {
                        if ($meta["type"] == "textinput")
                        {
                           $data = pem_get_row("id", $meta_id, "meta");
                           $meta_data = unserialize($data["value"]);
                           if (!empty($meta["data"])) echo '<b>' . $meta_data["input_label"] . '</b> ' . $meta["data"] . '<br />' . "\n";
                        }
                        if ($meta["type"] == "contact")
                        {
                           $data = pem_get_row("id", $meta_id, "meta");
                           $meta_data = unserialize($data["value"]);
                           if (!empty($meta["name1"])) echo '<b>' . $meta_data["name1"][0] . '</b> ' . $meta["name1"] . '<br />' . "\n";
                           if (!empty($meta["name2"])) echo '<b>' . $meta_data["name2"][0] . '</b> ' . $meta["name2"] . '<br />' . "\n";
                           if (!empty($meta["phone1"])) echo '<b>' . $meta_data["phone1"][0] . '</b> ' . $meta["phone1"] . '<br />' . "\n";
                           if (!empty($meta["phone2"])) echo '<b>' . $meta_data["phone2"][0] . '</b> ' . $meta["phone2"] . '<br />' . "\n";
                           if (!empty($meta["email"])) echo '<b>' . $meta_data["email"][0] . '</b> ' . $meta["email"] . '<br />' . "\n";
                        }
                     }
                  if (is_array($emeta)) foreach($emeta AS $meta_id => $meta)
                     {
                        if ($meta["type"] == "textinput")
                        {
                           $data = pem_get_row("id", $meta_id, "meta");
                           $meta_data = unserialize($data["value"]);
                           if (!empty($meta["data"])) echo '<b>' . $meta_data["input_label"] . '</b> ' . $meta["data"] . '<br />' . "\n";
                        }
                        if ($meta["type"] == "contact")
                        {
                           $data = pem_get_row("id", $meta_id, "meta");
                           $meta_data = unserialize($data["value"]);
                           if (!empty($meta["name1"])) echo '<b>' . $meta_data["name1"][0] . '</b> ' . $meta["name1"] . '<br />' . "\n";
                           if (!empty($meta["name2"])) echo '<b>' . $meta_data["name2"][0] . '</b> ' . $meta["name2"] . '<br />' . "\n";
                           if (!empty($meta["phone1"])) echo '<b>' . $meta_data["phone1"][0] . '</b> ' . $meta["phone1"] . '<br />' . "\n";
                           if (!empty($meta["phone2"])) echo '<b>' . $meta_data["phone2"][0] . '</b> ' . $meta["phone2"] . '<br />' . "\n";
                           if (!empty($meta["email"])) echo '<b>' . $meta_data["email"][0] . '</b> ' . $meta["email"] . '<br />' . "\n";
                        }
                     }
                  echo '</td></tr>' . "\n";
               }
            }
         }
         if (!empty($displayrows))
         {
            echo '<tr style="border-top:1px solid #000; padding:20px 0 0 0;"><td style="text-align:center; font-size:200%; padding:20px 0 0 0;" colspan="3">' . "\n";
            echo __("Current Displays");
            echo '</td></tr>' . "\n";
            echo $displayrows;


         }

         echo '</table>' . "\n";
      }
      break;
   case ("corridor"):
      $datetitle = __("Post on CulturalCorridor.org");
      $select_fields = array("*");
      $where_add = " AND (d.date_visible_to_public = 1 AND e.entry_visible_to_public = 1) AND (d.date_status = 1 AND e.entry_status = 1)";
      function echo_data($list)
      {
         global $date_format, $time_format;

         echo '<div style="margin:0 10px;">' . "\n";
         $lastdate = "";
         $firstdate = true;
         $displayrows = "";
         $eventrows = "";
         for ($i = 0; $i < count($list); $i++)
         {
            $dmeta = unserialize($list[$i]["date_meta"]);
            $emeta = unserialize($list[$i]["entry_meta"]);
            if (is_array($dmeta) AND isset($dmeta[14]) AND $dmeta[14]["data"] == 3)
            {
               $thisdate = pem_date($date_format, $list[$i]["when_begin"]);

               if ($list[$i]["entry_type"] == 3 OR $list[$i]["entry_type"] == 4)
               {
                  $displayrows .= $list[$i]["spaces_text"] . '<br />' . "\n";
                  $displayrows .= '<span style="font-weight:bold;">' . "\n";
                  if ($list[$i]["entry_cancelled"] OR $list[$i]["date_cancelled"]) $displayrows .= "[" . __("CANCELLED") . "] ";
                  $title = (!empty($list[$i]["date_name"])) ? $list[$i]["entry_name"] . ': ' . $list[$i]["date_name"] : $list[$i]["entry_name"];
                  $displayrows .= $title . '</span><br />';
                  $displayrows .= $list[$i]["date_description"];
                  if (!empty($list[$i]["entry_description"]) AND !empty($list[$i]["date_description"])) $displayrows .= '<br />' . "\n";
                  $displayrows .= $list[$i]["entry_description"];
                  $displayrows .= '<br />' . "\n";
               }
               else
               {
                  if ($thisdate != $lastdate)
                  {
                     if (!$firstdate) echo '<br />================================<br />';
                     $styleadd2 = (!$firstdate) ? ' padding:20px 0 0 0;' : "";
                     echo '<span style="font-size:140%; font-weight:bold;">' . $thisdate . '</span><br />' . "\n";
                     $lastdate = $thisdate;
                     $firstdate = false;
                  }
                  echo '<br />================================<br />';


                  echo '<table cellspacing="0" style="font-family:arial;">' . "\n";
                  echo '<tr><td valign="top" style="text-align:left; padding-right:10px;"><b>Event&nbsp;Title:</b></td><td valign="top" style="text-align:left;">' . "\n";
                  if ($list[$i]["entry_cancelled"] OR $list[$i]["date_cancelled"]) echo "[" . __("CANCELLED") . "] ";
                  $title = (!empty($list[$i]["date_name"])) ? $list[$i]["entry_name"] . ': ' . $list[$i]["date_name"] : $list[$i]["entry_name"];
                  echo $title . '</td></tr>' . "\n";

                  echo '<tr><td valign="top" style="text-align:left;"><b>Teaser:</b></td><td valign="top" style="text-align:left;">' . "\n";
                  echo '<i>Create using Content details below.</i></td></tr>' . "\n";

                  echo '<tr><td valign="top" style="text-align:left;"><b>Content:</b></td><td valign="top" style="text-align:left;">' . "\n";
                  echo $list[$i]["date_description"];
                  if (!empty($list[$i]["entry_description"]) AND !empty($list[$i]["date_description"])) echo '<br />';
                  echo $list[$i]["entry_description"] . '<br /><br />' . "\n";

                  echo 'Date: ' . $thisdate . '<br />' . "\n";
                  echo 'Time: ';
                  if ($list[$i]["allday"]) echo  __("All Day");
                  else
                  {
                     echo pem_simplify_times($list[$i]["when_begin"], $list[$i]["when_end"]);
                  }
                  echo '<br />' . "\n";
                  echo 'Location: ' . $list[$i]["spaces_text"] . '<br />' . "\n";
                  echo 'Date: ' . $thisdate . '<br />' . "\n";

                  if (is_array($dmeta)) foreach($dmeta AS $meta_id => $meta)
                     {
                        if ($meta["type"] == "textinput")
                        {
                           $data = pem_get_row("id", $meta_id, "meta");
                           $meta_data = unserialize($data["value"]);
                           if (!empty($meta["data"])) echo $meta_data["input_label"] . ' ' . $meta["data"] . '<br />' . "\n";
                        }
                        if ($meta["type"] == "contact")
                        {
                           $data = pem_get_row("id", $meta_id, "meta");
                           $meta_data = unserialize($data["value"]);
                           if (!empty($meta["name1"])) echo $meta_data["name1"][0] . ' ' . $meta["name1"] . '<br />' . "\n";
                           if (!empty($meta["name2"])) echo $meta_data["name2"][0] . ' ' . $meta["name2"] . '<br />' . "\n";
                           if (!empty($meta["phone1"])) echo $meta_data["phone1"][0] . ' ' . $meta["phone1"] . '<br />' . "\n";
                           if (!empty($meta["phone2"])) echo $meta_data["phone2"][0] . ' ' . $meta["phone2"] . '<br />' . "\n";
                           if (!empty($meta["email"])) echo $meta_data["email"][0] . ' ' . $meta["email"] . '<br />' . "\n";
                        }
                     }
                  if (is_array($emeta)) foreach($emeta AS $meta_id => $meta)
                     {
                        if ($meta["type"] == "textinput")
                        {
                           $data = pem_get_row("id", $meta_id, "meta");
                           $meta_data = unserialize($data["value"]);
                           if (!empty($meta["data"])) echo $meta_data["input_label"] . ' ' . $meta["data"] . '<br />' . "\n";
                        }
                        if ($meta["type"] == "contact")
                        {
                           $data = pem_get_row("id", $meta_id, "meta");
                           $meta_data = unserialize($data["value"]);
                           if (!empty($meta["name1"])) echo $meta_data["name1"][0] . ' ' . $meta["name1"] . '<br />' . "\n";
                           if (!empty($meta["name2"])) echo $meta_data["name2"][0] . ' ' . $meta["name2"] . '<br />' . "\n";
                           if (!empty($meta["phone1"])) echo $meta_data["phone1"][0] . ' ' . $meta["phone1"] . '<br />' . "\n";
                           if (!empty($meta["phone2"])) echo $meta_data["phone2"][0] . ' ' . $meta["phone2"] . '<br />' . "\n";
                           if (!empty($meta["email"])) echo $meta_data["email"][0] . ' ' . $meta["email"] . '<br />' . "\n";
                        }
                     }
                  echo '<br />' . "\n";

                  echo '</td></tr>' . "\n";
                  echo '</table>' . "\n";



               }
            }
         }
         if (!empty($displayrows))
         {
            echo '<tr style="border-top:1px solid #000; padding:20px 0 0 0;"><td style="text-align:center; font-size:200%; padding:20px 0 0 0;" colspan="3">' . "\n";
            echo __("Current Displays");
            echo '</td></tr>' . "\n";
            echo $displayrows;


         }

         echo '<br />' . "\n";
      }
      break;
}

if (!isset($date_begin_month))
{
   $current_event_type = pem_cache_get("current_event_type");
   switch ($current_event_type)
   {
      case ("scheduled"):
         $checkscheduled = 1;
         $checkunscheduled = 0;
         $checkallday = 0;
         break;
      case ("unscheduled"):
         $checkscheduled = 0;
         $checkunscheduled = 1;
         $checkallday = 0;
         break;
      case ("allday"):
         $checkscheduled = 0;
         $checkunscheduled = 0;
         $checkallday = 1;
         break;
      default:
         $checkscheduled = 1;
         $checkunscheduled = 0;
         $checkallday = 0;
         break;
   }
}

if (isset($date_begin_month))
{
   $checkscheduled = (isset($checkscheduled)) ? 1 : 0;
   $checkunscheduled = (isset($checkunscheduled)) ? 1 : 0;
   $checkallday = (isset($checkallday)) ? 1 : 0;

   $date_begin = $date_begin_year . "-" . zeropad($date_begin_month, 2) . "-" . zeropad($date_begin_day, 2) . " 00:00:00";
   $date_end = $date_end_year . "-" . zeropad($date_end_month, 2) . "-" . zeropad($date_end_day, 2) . " 23:59:59";

   $fields = "";
   if (!isset($select_fields)) $fields = "*";
   else foreach ($select_fields AS $value) $fields .= $value . ", ";
   if (substr($fields, -2) == ", ") $fields = substr($fields, 0, strlen($fields)-2);
   $sql = "SELECT $fields FROM " . $table_prefix . "entries AS e, " . $table_prefix . "dates AS d WHERE ";
   $sql .= "e.id = d.entry_id AND ";
   $sql .= "e.entry_status != 2 AND ";
   $sql .= "d.date_status != 2";


   if ($checkallday)
   {
      if ($checkscheduled AND $checkunscheduled) $sql .= "";
      elseif ($checkunscheduled) $sql .= " AND (e.entry_type = 3 OR e.entry_type = 4 OR d.allday = 1)";
      elseif ($checkscheduled) $sql .= " AND (e.entry_type = 1 OR e.entry_type = 2)";
      else $sql .= " AND d.allday = 1";
   }
   else
   {
      if ($checkscheduled AND $checkunscheduled)  $sql .= " AND d.allday = 0";
      elseif ($checkscheduled) $sql .= " AND (e.entry_type = 1 OR e.entry_type = 2) AND d.allday = 0";
      elseif ($checkunscheduled) $sql .= " AND (e.entry_type = 3 OR e.entry_type = 4)";
   }

   if (isset($where_dates)) $sql .= $where_dates;
   else $sql .= " AND d.when_begin <= :when_begin_before AND d.when_end >= :when_end_after";
   if (isset($where_add)) $sql .= $where_add;
   $sql .= " ORDER BY ";
   if (isset($order_by)) $sql .= $order_by;
   else $sql .= "d.when_begin, d.when_end, e.entry_name";

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
            $spaces_text .= '<span style="white-space:nowrap;">' . $row[$field] . '</span>';
            if ($j < $spaces_count - 1) $spaces_text .= ", ";
            if ($spaces_count > 2 AND (($j+1) % 2) == 0) $spaces_text .= "<br />";

            $space_names[$spaces[$j]] = $row[$field];

         }
         $list[$i]["spaces_text"] = $spaces_text;
      }
   }

   if (isset($select_fields) AND (in_array("d.supplies", $select_fields) OR in_array("*", $select_fields)))
   {
      $sql = "SELECT supply_name FROM " . $table_prefix . "supplies WHERE id = :supply_id";
      for ($i = 0; $i < count($list); $i++)
      {

         $supplies = unserialize($list[$i]["supplies"]);
         $supplies_text = "";

         if (is_array($supplies)) foreach ($supplies AS $supspace_id => $supspace_supplies)
            {
               $supspace_supply_keys = array_keys($supspace_supplies);
               $supplies_count = count($supspace_supply_keys);

               $supplies_text = "";
               for ($j = 0; $j < $supplies_count; $j++) // build spaces_text
               {
                  $sql_values = array("supply_id" => $supspace_supply_keys[$j]);
                  $sql_prep = $pemdb->prepare($sql);
                  if (PEAR::isError($sql_prep)) PEAR_error($sql_prep);
                  $result = $sql_prep->execute($sql_values);
                  if (PEAR::isError($result)) PEAR_error($result);
                  $row = $result->fetchRow(MDB2_FETCHMODE_ASSOC);

                  if (!empty($supspace_supplies[$supspace_supply_keys[$j]]))
                  {
                     $supplies_text .=  $row["supply_name"] . ' (' . $supspace_supplies[$supspace_supply_keys[$j]] . ')';
                     if ($j < $supplies_count - 1) $supplies_text .= ", ";
                  }
               }
               if (!empty($space_names[$supspace_id]) AND !empty($supplies_text))
               {
                  if (substr($supplies_text, -2) == ", ")
                  {
                     $list[$i]["supplies_text"][] = '[' . $space_names[$supspace_id] . '] ' . substr($supplies_text, 0, -2);
                  }
                  else
                  {
                     $list[$i]["supplies_text"][] = '[' . $space_names[$supspace_id] . '] ' . $supplies_text;
                  }
               }
            }
      }
   }

   mdb2_disconnect($pemdb);
}


// display date range selection form
if (!isset($printview))
{
   pem_fieldset_begin($datetitle);
   echo '<p>' . __("Select a date range using the options below to generate your report.") . "</p>\n";
   echo_form();
   pem_fieldset_end();
}

if (isset($date_begin_month))
{
   if (!isset($printview)) pem_fieldset_begin(__("Report Results") . ' (' . sprintf(__('%1$s found'), count($list)) . ')');
   if (!empty($list)) echo_data($list);
   else echo __("No Results Found.");
   if (!isset($printview)) pem_fieldset_end();
}

include ABSPATH . PEMINC . "/footer.php";


// =============================================================================
// =========================== LOCAL FUNCTIONS =================================
// =============================================================================


function echo_form()
{
   global $PHP_SELF, $_POST, $date_format, $date_begin, $date_end, $current_report, $check_options;
   extract($_POST);

//print_r($_POST);

   if (empty($date_begin)) $date_begin = pem_date("Y-m-d");
   if (empty($date_end)) $date_end = pem_date("Y-m-d");


   pem_form_begin(array("nameid" => "dateform", "action" => $PHP_SELF, "class" => "reportdateform"));

   echo '<div class="datebox">';
   echo '<h3>' . __("Manually Select Date Range") . '</h3>' . "\n";
   pem_field_label(array("default" => __("Date Begins:"), "for" => "date_begin_month"));
   pem_date_selector("date_begin_", array("default" => $date_begin));
   echo '<br />';
   pem_field_label(array("default" => __("Date Ends:"), "for" => "date_end_month"));
   pem_date_selector("date_end_", array("default" => $date_end));
   echo '</div>' . "\n";

   echo '<div class="ordivider"></div>';

   echo '<div class="datebox">' . "\n";
   echo '<h3>' . __("Automatically Set Date Range To") . '</h3>' . "\n";
   echo '<table><tr>' . "\n";
   echo '<td>';
   $set_select = "setdateselect('date_begin', " . pem_date("Y") . ", " . pem_date("m") . ", " . pem_date("d") . ");";
   $set_select .= " setdateselect('date_end', " . pem_date("Y") . ", " . pem_date("m") . ", " . pem_date("d") . ");";
   echo '<input type="button" value="Today\'s Date" onclick="' . $set_select . '" />' . "\n";
   echo "</td>\n<td>";
   $set_select = "setdateselect('date_begin', " . pem_date("Y", strtotime("+1 day")) . ", " . pem_date("m", strtotime("+1 day")) . ", " . pem_date("d", strtotime("+1 day")) . ");";
   $set_select .= " setdateselect('date_end', " . pem_date("Y", strtotime("+1 day")) . ", " . pem_date("m", strtotime("+1 day")) . ", " . pem_date("d", strtotime("+1 day")) . ");";
   echo '<input type="button" value="Tomorrow\'s Date" onclick="' . $set_select . '" />' . "\n";
   echo "</td>\n<td>";
   $set_select = "setdateselect('date_begin', " . pem_date("Y") . ", " . pem_date("m") . ", " . pem_date("d") . ");";
   $set_select .= " setdateselect('date_end', " . pem_date("Y", strtotime("+1 week")) . ", " . pem_date("m", strtotime("+1 week")) . ", " . pem_date("d", strtotime("+1 week")) . ");";
   echo '<input type="button" value="Next 7 Days" onclick="' . $set_select . '" />' . "\n";
   echo "</td>\n</tr><tr>\n<td>";
   $set_select = "setdateselect('date_begin', " . pem_date("Y", strtotime("next Sunday")) . ", " . pem_date("m", strtotime("next Sunday")) . ", " . pem_date("d", strtotime("next Sunday")) . ");";
   $set_select .= " setdateselect('date_end', " . pem_date("Y", strtotime("next Sunday +6 days")) . ", " . pem_date("m", strtotime("next Sunday +6 days")) . ", " . pem_date("d", strtotime("next Sunday +6 days")) . ");";
   echo '<input type="button" value="Next Full Week" onclick="' . $set_select . '" />' . "\n";
   echo "</td>\n<td>";
   $set_select = "setdateselect('date_begin', " . pem_date("Y", strtotime("next Sunday")) . ", " . pem_date("m", strtotime("next Sunday")) . ", " . pem_date("d", strtotime("next Sunday")) . ");";
   $set_select .= " setdateselect('date_end', " . pem_date("Y", strtotime("next Sunday +13 days")) . ", " . pem_date("m", strtotime("next Sunday +13 days")) . ", " . pem_date("d", strtotime("next Sunday +13 days")) . ");";
   echo '<input type="button" value="Next Full 2 Weeks" onclick="' . $set_select . '" />' . "\n";
   echo "</td>\n<td>";
   $set_select = "setdateselect('date_begin', " . pem_date("Y", strtotime("+1 month")) . ", " . pem_date("m", strtotime("+1 month")) . ", 01);";
   $set_select .= " setdateselect('date_end', " . pem_date("Y", strtotime("+1 month")) . ", " . pem_date("m", strtotime("+1 month")) . ", " . pem_date("t", strtotime("+1 month")) . ");";
   echo '<input type="button" value="Next Month" onclick="' . $set_select . '" />' . "\n";
   echo "</td>\n</tr></table>\n";
   echo '</div>' . "\n";

   echo '<br />';

   echo '<div class="datebox">' . "\n";
   pem_fieldset_begin(__("Only Show:"), array("class" => "sub"));


   pem_checkbox(array("nameid" => "checkscheduled", "status" => $checkscheduled, "style" => "float:left;"));
   pem_field_label(array("default" => __("Calendar Events"), "for" => "checkscheduled", "style" => "font-weight:normal; margin-right:20px;"));
   pem_checkbox(array("nameid" => "checkunscheduled", "status" => $checkunscheduled, "style" => "float:left;"));
   pem_field_label(array("default" => __("Side Box Events"), "for" => "checkunscheduled", "style" => "font-weight:normal; margin-right:20px;"));
   pem_checkbox(array("nameid" => "checkallday", "status" => $checkallday, "style" => "float:left;"));
   pem_field_label(array("default" => __("All-Day Events"), "for" => "checkallday", "style" => "font-weight:normal;"));
   if (!empty($check_options))
   {
      echo '<br style="margin-bottom:20px;" />' . "\n";
      //pem_field_label(array("default" => " "));
      if ($current_report == "category")
      {
         for ($i = 1; $i < count($check_options); $i++)
         {
            $checked = (isset($category[$check_options[$i]["id"]])) ? 1 : 0;
            pem_checkbox(array("name" => "category[" . $check_options[$i]["id"] . "]", "id" => "category" . $check_options[$i]["id"], "status" => $checked, "style" => "float:left;"));
            pem_field_label(array("default" => $check_options[$i]["category_name"], "for" => "category" . $check_options[$i]["id"], "style" => "font-weight:normal; margin-right:20px; color:#" . $check_options[$i]["category_color"] . ";"));
         }
      }
      if ($current_report == "location")
      {
         $current_area = "";
         for ($i = 0; $i < count($check_options); $i++)
         {
            $checked = (isset($location[$check_options[$i]["id"]])) ? 1 : 0;
            echo '<div style="float:left; white-space:nowrap;">';
            pem_checkbox(array("name" => "location[" . $check_options[$i]["id"] . "]", "id" => "location" . $check_options[$i]["id"], "status" => $checked, "style" => "float:left;"));
            pem_field_label(array("default" => $check_options[$i]["space_name_short"], "for" => "location" . $check_options[$i]["id"], "style" => "font-weight:normal; margin-right:20px;"));
            echo '</div>';
            if ($current_area != $check_options[$i]["area"] AND $i !== 0) echo '<br />' . "\n";
            $current_area = $check_options[$i]["area"];
         }
      }
      if ($current_report == "creator")
      {
         $length = $check_options["length"] * 7;
         unset($check_options["length"]);
         $option_keys = array_keys($check_options);
         for ($i = 0; $i < count($option_keys); $i++)
         {
            $checked = (isset($creator[$option_keys[$i]])) ? 1 : 0;
            echo '<div style="float:left; white-space:nowrap;">';
            pem_checkbox(array("name" => "creator[" . $option_keys[$i] . "]", "id" => "creator" . $option_keys[$i], "status" => $checked, "style" => "float:left;"));
            pem_field_label(array("default" => $check_options[$option_keys[$i]], "for" => "creator" . $option_keys[$i], "style" => "font-weight:normal; margin-right:20px; width:" . $length . "px"));
            echo '</div>';
         }
      }
      if ($current_report == "approver")
      {
         $length = $check_options["length"] * 7;
         unset($check_options["length"]);
         $option_keys = array_keys($check_options);
         for ($i = 0; $i < count($option_keys); $i++)
         {
            $checked = (isset($approver[$option_keys[$i]])) ? 1 : 0;
            echo '<div style="float:left; white-space:nowrap;">';
            pem_checkbox(array("name" => "approver[" . $option_keys[$i] . "]", "id" => "approver" . $option_keys[$i], "status" => $checked, "style" => "float:left;"));
            pem_field_label(array("default" => $check_options[$option_keys[$i]], "for" => "approver" . $option_keys[$i], "style" => "font-weight:normal; margin-right:20px; width:" . $length . "px"));
            echo '</div>';
         }
      }
   }
   echo '</div>' . "\n";

   echo '<br />';

   pem_form_submit("dateform", __("Get Report"));

   $labelwidth = strlen(__("Print View:")) * 6;
   pem_checkbox(array("nameid" => "printview", "status" => false, "style" => "float:left; margin-left:30px;"));
   pem_field_label(array("default" => __("Print View"), "style" => "width:" . $labelwidth . "px"));
   echo '<br />';

   pem_form_end();


} // END echo_form

?>