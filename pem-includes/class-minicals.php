<?php
/* ========================== FILE INFORMATION =================================
phxEventManager :: class-minicals.php

Builds and prints one or more small calendars for use as navigation elements.
Some source based on "PHP Calendar Class" by David Wilkinson, 2000

Add these example styles to your CSS:
.minicalmonth { border:0px; font-size:12px; text-align:center; vertical-align:top; font-weight:bold; }
.minical      { border:1px solid #F00; }
.minical th  { border:1px solid #F00; font-size:12px; text-align:center; vertical-align:top; }
.minical td  { border:0px; font-size:10px; text-align:center; vertical-align:top; }
.minicalhighlight { color:#880000; }

$format currently only checks for "horizontal" value. Any other input produces a vertical layout.
$size toggles the display size. Use value "small" for a tighter, smaller display.
============================================================================= */

class PEM_MiniCal
{
   var $year;
   var $month;
   var $day;
   var $dmy;
   var $format;
   var $size;
   var $highlight;

   function PEM_MiniCal($date, $format = "vertical", $size = "large", $highlight = false)
   {
     $this->year      = pem_date("Y", $date);
     $this->month     = pem_date("m", $date);
     $this->day       = pem_date("d", $date);
     $this->dmy       = $dmy;
     $this->format    = $format;
     $this->size      = $size;
     $this->highlight = $highlight;
   }

   function monthdays($month, $year)
   {
      if ($month < 1 || $month > 12) return 0;
      $days = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
      $d = $days[$month - 1];
      if ($month == 2) // Check for leap year
      {
         if ($year % 4 == 0)
         {
            if ($year % 100 == 0)
            {
               if ($year % 400 == 0)
               {
                  $d = 29;
               }
            }
            else
            {
               $d = 29;
            }
         }
      }
      return $d;
   }

   function buildlink($day, $month, $year, $view)
   {
      global $PHP_SELF;

      $link = $PHP_SELF . '?y=' . $year . '&amp;m=' . $month;
      if ($view == "day") $link .= '&amp;d=' . $day;
      $link .= '&amp;v=' . $view;
      return $link;
   }

   function buildheader($size)
   {
      global $week_begin, $weekday, $weekday_initial, $weekday_abbrev;
      for ($i = 0, $ret = ""; $i < 7; $i++)
      {
         $ret .= '<th>';
         $ret .= ($size == "small")? $weekday_initial[$weekday[($i + $week_begin)%7]] : $weekday_abbrev[$weekday[($i + $week_begin)%7]];
         $ret .= '</th>' . "\n";
      }
      return $ret;
   }

   function buildcal($size)
   {
      global $week_begin, $day, $month, $view_type;

      if (empty($view_type)) $view_type = "month";
      if (!isset($week_begin)) $week_begin = 0;
      $ret = "";

      $daysInMonth = $this->monthdays($this->month, $this->year);
      // $prevYear is the current year unless the previous month is
      // December then you need to decrement the year
      if ($this->month - 1 > 0)
      {
         $prevMonth = $this->month - 1;
         $prevYear = $this->year;
      }
      else
      {
         $prevMonth = 12;
         $prevYear = $this->year - 1;
      }

      $daysInPrevMonth = $this->monthdays($prevMonth, $prevYear);
      $date = mktime(12, 0, 0, $this->month, 1, $this->year);

      $first = (strftime("%w", $date) + 7 - $week_begin) % 7;

      //$prevMonth = $this->getCalendarLink($this->month - 1 >   0 ? $this->month - 1 : 12, $this->month - 1 >   0 ? $this->year : $this->year - 1);
      //$nextMonth = $this->getCalendarLink($this->month + 1 <= 12 ? $this->month + 1 :  1, $this->month + 1 <= 12 ? $this->year : $this->year + 1);
      //$ret .= '<td align="center" valign="top">' . (($prevMonth == "") ? "&nbsp;" : '<a href="' . $prevMonth . '">&lt;&lt;</a>')  . '</td>' . "\n";
      //$ret .= '<td align="center" valign="top">' . (($nextMonth == "") ? "&nbsp;" : '<a href="' . $nextMonth . '">&gt;&gt;</a>')  . '</td>' . "\n";

      $link = $this->buildlink(1, $this->month, $this->year, "month");
      $ret .= '<div class="minicalmonth"><a href="' . $link . '">' . $month["$this->month"] . '&nbsp;' . $this->year . '</a></div>' . "\n";
      $ret .= '<table class="minical"><tr>' . "\n";
      $ret .= $this->buildheader($size);
      $ret .= '</tr>' . "\n";
      $d = 1 - $first;

      // this is used to highlight days in upcoming month
      $days_to_highlight = ($d + 7);

      $tcheck = pem_date('m') . pem_date('j') . pem_date('Y');
      while ($d <= $daysInMonth)
      {
         $ret .= '<tr>' . "\n";
         for ($i = 0; $i < 7; $i++)
         {
            $dcheck = $this->month . $d . $this->year;
            if ($this->highlight AND $tcheck == $dcheck)
            {
// TODO use the bold highlighting to mark the current day,week, or month given the view.
               $link = $this->buildlink($d, $this->month, $this->year, "day");
            	$ret .= '<td class="minicalhighlight" style="background-color:#AC0D0F;" >';
               //if ($view_type == "week" AND $this->month
               $ret .= '<a href="' . $link . '">' . $d . '</a>';
               $ret .= '</td>' . "\n";
            }
            elseif ($d > 0 AND $d <= $daysInMonth)
            {
               $link = $this->buildlink($d, $this->month, $this->year, "day");
               $ret .= '<td>';
               $ret .= '<a href="' . $link . '">' . $d . '</a>';
               $ret .= '</td>' . "\n";
            }
            else $ret .= '<td class="space">&nbsp;</td>' . "\n";
/*

               $link = $this->buildlink($d, $this->month, $this->year);
               $d_week = ($d - 7);
               $ret .= '<a href="' . $link . '">' . $d . '</a>';
 Old version made a distinction for day/week/month.
               if ($link == "") $ret .= $d;
               elseif (preg_match("/day/i", basename($PHP_SELF)))
               {
                  if ($d == $this->day AND $this->highlight) $ret .= '<a href="' . $link . '"><span class="minicalcurrent">' . $d . '</span></a>';
                  else $ret .= '<a href="' . $link . '">' . $d . '</a>';
               }
               elseif (preg_match("/week/i", basename($PHP_SELF)))
               {
                  // echo "((".$this->day." < $days_to_highlight) AND ($d < $days_to_highlight) AND (($day - $daysInMonth) > (-6)) AND (".$this->month." == ($month + 1)) AND ($first != 0))<br>";
                  if ($this->day <= $d AND $this->day > $d_week AND $this->highlight) $ret .= '<a href="' . $link . '"><span class="minicalcurrent">' . $d . '</span></a>';
                  elseif ($this->day < $days_to_highlight AND $d < $days_to_highlight AND ($day - $daysInPrevMonth) > -6 AND $this->month == (($month + 1)%12) AND $first != 0)
                  {
                     $ret .= '<a href="' . $link . '"><span class="minicalcurrent">' . $d . '</span></a>';
                  }
                  else $ret .= '<a href="' . $link . '">' . $d . '</a>';
               }
               elseif (preg_match("/month/i", basename($PHP_SELF)))
               {
                  if ($this->highlight) $ret .= '<a href="' . $link . '"><span class="minicalcurrent">' . $d . '</span></a>';
                  else $ret .= '<a href="' . $link . '">' . $d . '</a>';
               }
*/

            $d++;
         }
         $ret .= '</tr>' . "\n";
      }
      $ret .= '</table>' . "\n";
      return $ret;
   }
}


function minicals($year, $month, $day, $format = "vertical", $size = "large", $highlight = false)
{
   global $view_type, $view_format;

   $lastmonth = mktime(12, 0, 0, $month-1, 1, $year);
   $thismonth = mktime(12, 0, 0, $month,  $day, $year);
   $nextmonth = mktime(12, 0, 0, $month+1, 1, $year);
   $nextnextmonth = mktime(12, 0, 0, $month+2, 1, $year);
//echo "=============================";
//echo "minical_format: " . $format . "<br />";
//echo "minical_size: " . $size . "<br />";
//echo "highlight_today_minical: " . $highlight . "<br />";

   echo '<table class="minicalstrip"><tr>' . "\n";
   echo '<td>';
   $cal = new PEM_MiniCal($lastmonth, $dmy, $format, $size, false);
   echo $cal->buildcal($size);
   echo '</td>' . "\n";
   if ($format == "vertical") echo '</tr><tr>' . "\n";
   if ($view_format == "list" OR $view_type != "month")
   {
      echo '<td>';
      $cal = new PEM_MiniCal($thismonth, $dmy, $format, $size, $highlight);
      echo $cal->buildcal($size);
      echo '</td>' . "\n";
      if ($format == "vertical") echo '</tr><tr>' . "\n";
   }
   echo '<td>';
   $cal = new PEM_MiniCal($nextmonth, $dmy, $format, $size, false);
   echo $cal->buildcal($size);
   echo '</td>' . "\n";
   if ($format == "vertical") echo '</tr><tr>' . "\n";
   echo '<td>';
   $cal = new PEM_MiniCal($nextnextmonth, $dmy, $format, $size, false);
   echo $cal->buildcal($size);
   echo '</td>' . "\n";
   echo '</tr></table>';
}



?>
