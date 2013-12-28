<?php
/* ========================== FILE INFORMATION =================================
phxEventManager :: functions-datetime.php

Functions dealing with date and/or time.
============================================================================= */

// Load the default text localization domain.
// load_default_textdomain();

setlocale(LC_TIME, 'de_DE');

// The Weekdays
$weekday[0] = __("Sunday");
$weekday[1] = __("Monday");
$weekday[2] = __("Tuesday");
$weekday[3] = __("Wednesday");
$weekday[4] = __("Thursday");
$weekday[5] = __("Friday");
$weekday[6] = __("Saturday");

// The first letter of each day.  The _%day%_initial suffix is a hack to make sure the day initials are unique.
$weekday_initial[__("Sunday")]    = __("S_Sunday_initial");
$weekday_initial[__("Monday")]    = __("M_Monday_initial");
$weekday_initial[__("Tuesday")]   = __("T_Tuesday_initial");
$weekday_initial[__("Wednesday")] = __("W_Wednesday_initial");
$weekday_initial[__("Thursday")]  = __("T_Thursday_initial");
$weekday_initial[__("Friday")]    = __("F_Friday_initial");
$weekday_initial[__("Saturday")]  = __("S_Saturday_initial");

foreach ($weekday_initial as $weekday_ => $weekday_initial_)
{
   $weekday_initial[$weekday_] = preg_replace("/_.+_initial$/", "", $weekday_initial_);
}

// Abbreviations for each day.
$weekday_abbrev[__("Sunday")]    = __("Sun");
$weekday_abbrev[__("Monday")]    = __("Mon");
$weekday_abbrev[__("Tuesday")]   = __("Tue");
$weekday_abbrev[__("Wednesday")] = __("Wed");
$weekday_abbrev[__("Thursday")]  = __("Thu");
$weekday_abbrev[__("Friday")]    = __("Fri");
$weekday_abbrev[__("Saturday")]  = __("Sat");

// The Months
$month["01"] = __("January");
$month["02"] = __("February");
$month["03"] = __("March");
$month["04"] = __("April");
$month["05"] = __("May");
$month["06"] = __("June");
$month["07"] = __("July");
$month["08"] = __("August");
$month["09"] = __("September");
$month["10"] = __("October");
$month["11"] = __("November");
$month["12"] = __("December");

// Abbreviations for each month. Uses the same hack as above to get around the "May" duplication.
$month_abbrev[__("January")]   = __("Jan_January_abbreviation");
$month_abbrev[__("February")]  = __("Feb_February_abbreviation");
$month_abbrev[__("March")]= __("Mar_March_abbreviation");
$month_abbrev[__("April")]= __("Apr_April_abbreviation");
$month_abbrev[__("May")]  = __("May_May_abbreviation");
$month_abbrev[__("June")] = __("Jun_June_abbreviation");
$month_abbrev[__("July")] = __("Jul_July_abbreviation");
$month_abbrev[__("August")]    = __("Aug_August_abbreviation");
$month_abbrev[__("September")] = __("Sep_September_abbreviation");
$month_abbrev[__("October")]   = __("Oct_October_abbreviation");
$month_abbrev[__("November")]  = __("Nov_November_abbreviation");
$month_abbrev[__("December")]  = __("Dec_December_abbreviation");

foreach ($month_abbrev as $month_ => $month_abbrev_)
{
   $month_abbrev[$month_] = preg_replace("/_.+_abbreviation$/", "", $month_abbrev_);
}

// The Meridiems
$meridiem["am"] = __("am");
$meridiem["pm"] = __("pm");
$meridiem["AM"] = __("AM");
$meridiem["PM"] = __("PM");
$meridiem["a.m."] = __("a.m.");
$meridiem["p.m."] = __("p.m.");
$meridiem["A.M."] = __("A.M.");
$meridiem["P.M."] = __("P.M.");


// support for periods in meridiems with only one occurance in format string
// will attmept to convert $unixtimestamp if hyphens are found in the var
function pem_date($dateformatstring, $unixtimestamp = "")
{
   global $meridiem;

   if (stripos($unixtimestamp, "-") > 0) // attempt to convery to timestamp
   {
      MDB2::loadFile("Date"); // load Date helper class
      $unixtimestamp = MDB2_Date::mdbstamp2Unix($unixtimestamp);
   }
   if (substr_count($dateformatstring, "a.") > 0)
   {
      $format_tmp = explode("a.", $dateformatstring);
      $format = (empty($unixtimestamp)) ? date($format_tmp[0]) : date($format_tmp[0], intval($unixtimestamp));
      $meridiem_type = (empty($unixtimestamp)) ? date("a") : date("a", intval($unixtimestamp));
      $format .= ($meridiem_type == "am") ? $meridiem["a.m."] : $meridiem["p.m."];
      $format .= (empty($unixtimestamp)) ? date($format_tmp[1]) : date($format_tmp[1], intval($unixtimestamp));
      return $format;
   }
   elseif (substr_count($dateformatstring, "A.") > 0)
   {
      $format_tmp = explode("A.", $dateformatstring);
      $format = (empty($unixtimestamp)) ? date($format_tmp[0]) : date($format_tmp[0], intval($unixtimestamp));
      $meridiem_type = (empty($unixtimestamp)) ? date("A") : date("A", intval($unixtimestamp));
      $format .= ($meridiem_type == "AM") ? $meridiem["A.M."] : $meridiem["P.M."];
      $format .= (empty($unixtimestamp)) ? date($format_tmp[1]) : date($format_tmp[1], intval($unixtimestamp));
      return $format;
   }
   else
   {
      return (empty($unixtimestamp)) ? date($dateformatstring) : date($dateformatstring, intval($unixtimestamp));
   }
}


// takes seperate values for hour, minute, and optional meridiem
// returns a time string in 24-hour standatd format
function pem_time($hour, $minute, $meridiem = "")
{
   if (!empty($meridiem))
   {
      if ($meridiem == "pm")
      {
         $hour += 12;
         if ($hour == 24) $hour = 12;
      }
      else
      {
         if ($hour == 12) $hour = 0;
      }
   }
   $hour = zeropad($hour, 2);
   $minute = zeropad($minute, 2);
   return $hour . ":" . $minute . ":00";
} // END pem_time


// adds the $hours and $minutes to the $time (00:00:00)
// returns a time string in 24-hour standatd format
function pem_time_add($time, $hours = 0, $minutes = 0)
{
   $timevals = explode(":", $time);
   $hour = $timevals[0] + $hours;

   $minute = $timevals[1] + $minutes;
   if ($minute > 59)
   {
      $minute = $minute - 60;
      $hour++;
   }
   if ($hour > 23) $hour = $hour - 24; // day changed, otherwise unsupported feature

   $hour = zeropad($hour, 2);
   $minute = zeropad($minute, 2);
   return $hour . ":" . $minute . ":00";
} // END pem_time_add

// subtracts the $hours and $minutes to the $time (00:00:00)
// returns a time string in 24-hour standatd format
function pem_time_subtract($time, $hours = 0, $minutes = 0)
{
   $timevals = explode(":", $time);
   $hour = $timevals[0] - $hours;

   $minute = $timevals[1] - $minutes;
   if ($minute < 0)
   {
      $minute = $minute + 60;
      $hour--;
   }
   if ($hour < 0) $hour = $hour + 24; // day changed, otherwise unsupported feature

   $hour = zeropad($hour, 2);
   $minute = zeropad($minute, 2);
   return $hour . ":" . $minute . ":00";
} // END pem_time_subtract


// Get the local day name based on language. Note 2000-01-02 is a Sunday.
function day_name($daynumber) {
    return utf8_strftime("%A", mktime(0,0,0,1,2+$daynumber,2000));
    }

// Converts the real value $time into a quantified time value
// $time should be a real number under 25
// Returns an array of values keyed to "hours" and "minutes."
function pem_real_to_time_quantity($time)
{
   $ret["hours"] = ($time < 10) ? $time % 10 : $time % 100;
   $ret["minutes"] = round(($time - $ret["hours"]) * 60);
   return $ret;
}

// Converts the array values in $time to real equivilant
// $time should be an array of values keyed to "hours" and "minutes."
function pem_time_quantity_to_real($time)
{
   $ret = $time["hours"] + ($time["minutes"] / 60);
   return round($ret, 5);
}

function pem_scheduling_boundaries()
{
   $today = pem_date("Y-m-d");
   $where = array("status" => "1");
   $where[date_begin] = array("<", $today);
   $where[date_end] = array(">", $today);
   $list = pem_get_rows("scheduling_profiles", $where);
   if (!isset($list[1])) $ret = unserialize($list[0]["profile"]);
   else $ret = unserialize($list[1]["profile"]);
   return $ret;
} // END pem_scheduling_boundaries


/*
$time_begin = $begin_hour;
if ($begin_meridiem == "pm") $time_begin = ($time_begin == 12) ? 12 : $time_begin + 12;
else $time_begin = ($time_begin == 12) ? $time_begin + 12 : $time_begin;
$time_begin = $time_begin + ($begin_minute / 60);
$time_end = $end_hour;
if ($end_meridiem == "pm") $time_end = ($time_end == 12) ? 12 : $time_end + 12;
else $time_end = ($time_end == 12) ? $time_end + 12 : $time_end;
$time_end = $time_end + ($end_minute / 60);
if ($time_end == 25) { $time_end = 1; }
*/








function mysql2date($dateformatstring, $mysqlstring, $translate = true) {
	global $month, $weekday, $month_abbrev, $weekday_abbrev;
	$m = $mysqlstring;
	if ( empty($m) ) {
		return false;
	}
	$i = mktime(substr($m,11,2),substr($m,14,2),substr($m,17,2),substr($m,5,2),substr($m,8,2),substr($m,0,4));

	if ( -1 == $i || false == $i )
		$i = 0;

	if ( !empty($month) && !empty($weekday) && $translate ) {
		$datemonth = $month[date('m', $i)];
		$datemonth_abbrev = $month_abbrev[$datemonth];
		$dateweekday = $weekday[date('w', $i)];
		$dateweekday_abbrev = $weekday_abbrev[$dateweekday];
		$dateformatstring = ' '.$dateformatstring;
		$dateformatstring = preg_replace("/([^\\\])D/", "\\1".backslashit($dateweekday_abbrev), $dateformatstring);
		$dateformatstring = preg_replace("/([^\\\])F/", "\\1".backslashit($datemonth), $dateformatstring);
		$dateformatstring = preg_replace("/([^\\\])l/", "\\1".backslashit($dateweekday), $dateformatstring);
		$dateformatstring = preg_replace("/([^\\\])M/", "\\1".backslashit($datemonth_abbrev), $dateformatstring);

		$dateformatstring = substr($dateformatstring, 1, strlen($dateformatstring)-1);
	}
	$j = @date($dateformatstring, $i);
	if ( !$j ) {
	// for debug purposes
	//	echo $i." ".$mysqlstring;
	}
	return $j;
}



// Formats a given timestamp to the PHP formatstring using L10n variables
function date_i18n($dateformatstring, $unixtimestamp)
{
   global $month, $weekday, $month_abbrev, $weekday_abbrev;

   $i = $unixtimestamp;
   if ((!empty($month)) AND (!empty($weekday)))
   {
     $datemonth = $month[date("m", $i)];
     $datemonth_abbrev = $month_abbrev[$datemonth];
     $dateweekday = $weekday[date("w", $i)];
     $dateweekday_abbrev = $weekday_abbrev[$dateweekday];
     $dateformatstring = " ".$dateformatstring;
     $dateformatstring = preg_replace("/([^\\\])D/", "\\1".backslashit($dateweekday_abbrev), $dateformatstring);
     $dateformatstring = preg_replace("/([^\\\])F/", "\\1".backslashit($datemonth), $dateformatstring);
     $dateformatstring = preg_replace("/([^\\\])l/", "\\1".backslashit($dateweekday), $dateformatstring);
     $dateformatstring = preg_replace("/([^\\\])M/", "\\1".backslashit($datemonth_abbrev), $dateformatstring);
     $dateformatstring = substr($dateformatstring, 1, strlen($dateformatstring)-1);
   }
   $j = @date($dateformatstring, $i);
   return $j;
}
































/** hour_min_format()
 * -------------------------------------------------------
 * FUNCTION DESCRIPTION
 * -------------------------------------------------------
 * Returns the format characters needed to display a time
 * -------------------------------------------------------
*/
function hour_min_format()
{
   global $twentyfourhour_format;
   if ($twentyfourhour_format)
     return "H:i";
   else
     return "g:i a";
}


function get_weekstartend($mysqlstring, $start_of_week)
{
   $my = substr($mysqlstring,0,4);
   $mm = substr($mysqlstring,8,2);
   $md = substr($mysqlstring,5,2);
   $day = mktime(0,0,0, $md, $mm, $my);
   $weekday = date('w',$day);
   $i = 86400;

   if ( $weekday < get_settings('start_of_week') )
     $weekday = 7 - (get_settings('start_of_week') - $weekday);

   while ($weekday > get_settings('start_of_week')) {
     $weekday = date('w',$day);
     if ( $weekday < get_settings('start_of_week') )
      $weekday = 7 - (get_settings('start_of_week') - $weekday);

     $day = $day - 86400;
     $i = 0;
   }
   $week['start'] = $day + 86400 - $i;
   // $week['end'] = $day - $i + 691199;
   $week['end'] = $week['start'] + 604799;
   return $week;
}













// This will return the appropriate value for isdst for mktime().
// The order of the arguments was chosen to match those of mktime.
// hour is added so that this function can when necessary only be
// run if the time is between midnight and 3am (all DST changes
// occur in this period.
function is_dst ( $month, $day, $year, $hour="-1" ) {

   if( $hour != -1  && $hour > 3)
     return( -1 );

   # entering DST
   if( !date( "I", mktime(12, 0, 0, $month, $day-1, $year)) &&
     date( "I", mktime(12, 0, 0, $month, $day, $year)))
     return( 0 );

   # leaving DST
   elseif( date( "I", mktime(12, 0, 0, $month, $day-1, $year)) &&
     !date( "I", mktime(12, 0, 0, $month, $day, $year)))
     return( 1 );
   else
     return( -1 );
   }

// if crossing dst determine if you need to make a modification
// of 3600 seconds (1 hour) in either direction
function cross_dst ( $start, $end ) {

   # entering DST
   if( !date( "I", $start) &&  date( "I", $end))
     $modification = -3600;

   # leaving DST
   elseif(  date( "I", $start) && !date( "I", $end))
     $modification = 3600;
   else
     $modification = 0;

   return $modification;
   }





// ======================= TIMER FUNCTIONS =====================================

// functions to count the page generation time (from phpBB2)
// ( or just any time between timer_start() and timer_stop() )
function timer_start()
{
   global $timestart;
   $mtime = explode(" ", microtime());
   $mtime = $mtime[1] + $mtime[0];
   $timestart = $mtime;
   return true;
}
//if called like timer_stop(1), will echo $timetotal
function timer_stop($display = 0, $precision = 3)
{
   global $timestart, $timeend;
   $mtime = microtime();
   $mtime = explode(" ",$mtime);
   $mtime = $mtime[1] + $mtime[0];
   $timeend = $mtime;
   $timetotal = $timeend-$timestart;
   $r = number_format($timetotal, $precision);
   if ($display) echo $r;
   return $r;
}














// give it a date, it will give you the same date as GMT
function get_gmt_from_date($string) {
  // note: this only substracts $time_difference from the given date
  preg_match('#([0-9]{1,4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})#', $string, $matches);
  $string_time = gmmktime($matches[4], $matches[5], $matches[6], $matches[2], $matches[3], $matches[1]);
  $string_gmt = gmdate('Y-m-d H:i:s', $string_time - get_settings('gmt_offset') * 3600);
  return $string_gmt;
}

// give it a GMT date, it will give you the same date with $time_difference added
function get_date_from_gmt($string) {
  // note: this only adds $time_difference to the given date
  preg_match('#([0-9]{1,4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})#', $string, $matches);
  $string_time = gmmktime($matches[4], $matches[5], $matches[6], $matches[2], $matches[3], $matches[1]);
  $string_localtime = gmdate('Y-m-d H:i:s', $string_time + get_settings('gmt_offset')*3600);
  return $string_localtime;
}

// computes an offset in seconds from an iso8601 timezone
function iso8601_timezone_to_offset($timezone) {
  // $timezone is either 'Z' or '[+|-]hhmm'
  if ($timezone == 'Z') {
   $offset = 0;
  } else {
   $sign    = (substr($timezone, 0, 1) == '+') ? 1 : -1;
   $hours   = intval(substr($timezone, 1, 2));
   $minutes = intval(substr($timezone, 3, 4)) / 60;
   $offset  = $sign * 3600 * ($hours + $minutes);
  }
  return $offset;
}

// converts an iso8601 date to MySQL DateTime format used by post_date[_gmt]
function iso8601_to_datetime($date_string, $timezone = USER) {
  if ($timezone == GMT) {
   preg_match('#([0-9]{4})([0-9]{2})([0-9]{2})T([0-9]{2}):([0-9]{2}):([0-9]{2})(Z|[\+|\-][0-9]{2,4}){0,1}#', $date_string, $date_bits);
   if (!empty($date_bits[7])) { // we have a timezone, so let's compute an offset
     $offset = iso8601_timezone_to_offset($date_bits[7]);
   } else { // we don't have a timezone, so we assume user local timezone (not server's!)
     $offset = 3600 * get_settings('gmt_offset');
   }
   $timestamp = gmmktime($date_bits[4], $date_bits[5], $date_bits[6], $date_bits[2], $date_bits[3], $date_bits[1]);
   $timestamp -= $offset;
   return gmdate('Y-m-d H:i:s', $timestamp);
  } elseif ($timezone == USER) {
   return preg_replace('#([0-9]{4})([0-9]{2})([0-9]{2})T([0-9]{2}):([0-9]{2}):([0-9]{2})(Z|[\+|\-][0-9]{2,4}){0,1}#', '$1-$2-$3 $4:$5:$6', $date_string);
  }
}


?>