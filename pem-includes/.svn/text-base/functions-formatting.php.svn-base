<?php
/* ========================== FILE INFORMATION =================================
phxEventManager :: functions-formatting.php

Contains functions which provide formatting information for displayed content.
Content may be small pieces of data like strings and arrays that need sorts,
trims, or pads.  Content to be formatted can also be complete pages, where
these functions provide information on field order and visibility.
============================================================================= */



// =============================================================================
// ======================== LARGE CONTENT FORMATTING ===========================
// =============================================================================


// Provides an array of active fields (standard and meta) corresponding to the $entry_type
function pem_active_fields($entry_type)
{
   // Loop through behaviors to pull list of active fields and their labels
   $where[$entry_type] = array("!=", "0");
   $list = pem_get_rows("field_behavior", $where);
   for ($i = 0; $i < count($list); $i++)
   {
      $fieldbehavior[$list[$i]["name"]]["label"] = $list[$i]["label"];
      $fieldbehavior[$list[$i]["name"]]["required"] = ($list[$i][$entry_type] == 2) ? 1 : 0;
   }
   // Loop through metas to combine active metas with standard fields
   $where = array("status" => "1");
   $where[$entry_type] = array("!=", "0");
   $list = pem_get_rows("meta", $where);
   for ($i = 0; $i < count($list); $i++)
   {
      $thisid = $list[$i]["id"];
      $fieldbehavior["meta" . $thisid]["id"] = $thisid;
      $fieldbehavior["meta" . $thisid]["label"] = $list[$i]["meta_name"];
      $fieldbehavior["meta" . $thisid]["required"] = ($list[$i][$entry_type] == 2) ? 1 : 0;
      $fieldbehavior["meta" . $thisid]["type"] = $list[$i]["meta_type"];
      $fieldbehavior["meta" . $thisid]["parent"] = $list[$i]["meta_parent"];
      $fieldbehavior["meta" . $thisid]["value"] = unserialize($list[$i]["value"]);
   }
   return $fieldbehavior;
}

// Combines the field order information with the current behavior information to
// produce a clean list of fields to display.
function pem_order_fields($fieldbehavior, $entry_type)
{
   // Loop field orders to generate final short list of fields for this form
   $list = pem_get_rows("field_order");
//echo "<br />===================================<br />fieldslist: <pre>";
//print_r($list);
//echo "</pre><br />===================================<br />";

   for ($i = 0; $i < count($list); $i++)
   {

//echo "checking: " . $list[$i]["name"] . "<br />";
      if (array_key_exists($list[$i]["name"], $fieldbehavior))
      {
         $id = (array_key_exists("id", $fieldbehavior[$list[$i]["name"]])) ? $fieldbehavior[$list[$i]["name"]]["id"] : false;
         $type = (array_key_exists("type", $fieldbehavior[$list[$i]["name"]])) ? $fieldbehavior[$list[$i]["name"]]["type"] : false;
         $parent = (array_key_exists("parent", $fieldbehavior[$list[$i]["name"]])) ? $fieldbehavior[$list[$i]["name"]]["parent"] : false;
         $value = (array_key_exists("value", $fieldbehavior[$list[$i]["name"]])) ? $fieldbehavior[$list[$i]["name"]]["value"] : false;
         $fieldslist[] = array(
            $list[$i][$entry_type],
            "id" => $id,
            "name" => $list[$i]["name"],
            "label" => $fieldbehavior[$list[$i]["name"]]["label"],
            "required" => $fieldbehavior[$list[$i]["name"]]["required"],
            "type" => $type,
            "parent" => $parent,
            "value" => $value
         );
      }
      elseif ($list[$i]["name"] == "date_when" OR $list[$i]["name"] == "date_location")
      {
         if ($list[$i]["name"] == "date_when") $label = __("Date:");
         if ($list[$i]["name"] == "date_location") $label = __("Location:");
         $fieldslist[] = array(
            $list[$i][$entry_type],
            "id" => "",
            "name" => $list[$i]["name"],
            "label" => $label,
            "required" => "",
            "type" => "",
            "parent" => $list[$i]["parent"],
            "value" => "",
         );
      }
   }
   // Sort list by first key, the field position
    sort($fieldslist);
//echo "<br />===================================<br />fieldslist: <pre>";
//print_r($fieldslist);
//echo "</pre><br />===================================<br />";

   return $fieldslist;
}















// =============================================================================
// ======================== SMALL CONTENT FORMATTING ===========================
// =============================================================================


// Generates a non-redundant date range based on a range of dates.
// Formats are auto-detected to cater to global date format variable.
// $date_begin and $date_end expected as full stamps
// returns string
function pem_simplify_dates($date_begin = "", $date_end = "")
{
   global $date_format;

   $end_count = strlen($date_format);

   $begin_year = ($date_format{0} == "Y" OR $date_format{0} == "y") ? true : false;
   $end_year = ($date_format{$end_count-1} == "Y" OR $date_format{$end_count-1} == "y") ? true : false;
   $begin_month = ($date_format{0} == "F" OR $date_format{0} == "m" OR $date_format{0} == "M" OR $date_format{0} == "n") ? true : false;
   $end_month = ($date_format{$end_count-1} == "F" OR $date_format{$end_count-1} == "m" OR $date_format{$end_count-1} == "M" OR $date_format{$end_count-1} == "n") ? true : false;
   $same_year = (pem_date("Y", $date_begin) == pem_date("Y", $date_end)) ? true : false;
   $same_month = (pem_date("m", $date_begin) == pem_date("m", $date_end)) ? true : false;

   if ($same_year AND $same_month)
   {
      $ret = "";
   }
   else
   {
      $dbegin = "";
      $dend = "";
   }
   for ($i = 0; $i < $end_count; $i++)
   {
      $isday = ($date_format[$i] == "d" OR $date_format[$i] == "j");
      $ismonth = ($date_format[$i] == "F" OR $date_format[$i] == "m" OR $date_format[$i] == "M" OR $date_format[$i] == "n");
      $isyear = ($date_format[$i] == "Y" OR $date_format[$i] == "y");

      if ($isday AND $same_year AND $same_month)
      {
         $ret .= pem_date($date_format[$i], $date_begin) . "-" . pem_date($date_format[$i], $date_end);
      }
      elseif ($ismonth AND $same_year AND $same_month)
      {
         $ret .= pem_date($date_format[$i], $date_begin);
      }
      elseif ($isyear AND $same_year)
      {
         if ($begin_year) $dbegin .= pem_date($date_format[$i], $date_begin);
         elseif ($end_year)
         {
            if ($same_month) $ret .= pem_date($date_format[$i], $date_begin);
            else $ret = $dbegin . " " . __("to") . " " . $dend . " " . pem_date($date_format[$i], $date_begin);
         }
      }
      elseif (!$isyear AND !$ismonth AND !$isday)
      {
         if ($same_year AND $same_month) $ret .= $date_format[$i];
         elseif ($same_year AND $date_format[$i] == ",")
         {
            if ($begin_year) $dbegin .= pem_date($date_format[$i], $date_begin);
            else $dend .= pem_date($date_format[$i], $date_end);
         }
         elseif ($date_format[$i] == " " OR !$same_year)
         {
            $dbegin .= pem_date($date_format[$i], $date_begin);
            $dend .= pem_date($date_format[$i], $date_end);
         }
      }
      else
      {
         $dbegin .= pem_date($date_format[$i], $date_begin);
         $dend .= pem_date($date_format[$i], $date_end);
      }
   }
   if (!isset($ret)) $ret = $dbegin . " " . __("to") . " " . $dend;
   return $ret;
} // END pem_simplify_dates


// Generates a non-redundant time range based on a range of dates.
// Formats are auto-detected to cater to global date format variable.
// $time_begin and $time_end expected as full stamps
// returns string
function pem_simplify_times($time_begin = "", $time_end = "")
{


   global $time_format;

   $end_count = strlen($time_format);
   $meridem_position = stripos($time_format, "A");
   $ismeridem = ($meridem_position > 0) ? true : false;
   $same_meridem = (pem_date("A", $time_begin) == pem_date("A", $time_end)) ? true : false;
   $tbegin = "";
   $tend = "";

   if (!$ismeridem OR !$same_meridem)
   {
      $ret = pem_date($time_format, $time_begin) . "-" . pem_date($time_format, $time_end);
   }
   else
   {
      $meridem_length = ($time_format{$meridem_position-1} == " ") ? 2 : 1;
      if ($time_format{$meridem_position+1} == ".") $meridem_length++;
      $ret = pem_date(substr($time_format, 0, $end_count-$meridem_length), $time_begin) . "-" . pem_date($time_format, $time_end);
   }
   return $ret;
} // END pem_simplify_times


// Turns two or more consecutive newlines (separated by possible white space) into a <p>...</p>.
// Pass result to regular nl2br() to add <br /> to remaining nl's
function nls2p($str)
{
  return str_replace('<p></p>', '', '<p>' . preg_replace('#([\r\n]\s*?[\r\n]){2,}#', '</p>$0<p>', $str) . '</p>');
}


function pem_field_focus($fieldname)
{
   echo '<script type="text/javascript">' . "\n";
   echo 'function focusit()' . "\n";
   echo '{' . "\n";
   echo '   document.getElementById("' . $fieldname . '").focus();' . "\n";
   echo '}' . "\n";
   echo 'window.onload = focusit;' . "\n";
   echo '</script>' . "\n";
}

function array_trim($array)
{
   $empty_array[0] = "";
   return array_diff($array, $empty_array);
}

function array_unshift_assoc(&$arr, $key, $val)
{
    $arr = array_reverse($arr, true);
    $arr[$key] = $val;
    $arr = array_reverse($arr, true);
    return count($arr);
}


// adds leading zeros when necessary
function zeropad($number, $threshold)
{
   return sprintf('%0'.$threshold.'s', $number);
}

// forms an array from a string of terms deliminated by spaces
// single/double quotes can be used to form phrases
// duplicate terms or phrases are removed
function pem_parse_string($string)
{
   $ret = array();    // Array of Output
   $phrase = null;    // Record of the quote that opened the current phrase
   $phrasesub = null; // Temp storage for the current phrase being built
   // Define some constants
   static $tokens = " \r\n\t";  // Space, Return, Newline, Tab
   static $quotes = "'\"";      // Single and Double Quotes

   // Start the State Machine
   do
   {
      // Get the next token, which may be the first
      $token = isset($token)? strtok($tokens) : strtok($string, $tokens);

      // Are there more tokens?
      if ($token === false)
      {
         // Ensure that the last phrase is marked as ended
         $phrase = null;
      }
      else
      {
         // Are we within a phrase or not?
         if ($phrase !== null)
         {
            // Will the current token end the phrase?
            if (substr($token, -1, 1) === $phrase)
            {
               // Trim the last character and add to the current phrase, with a single leading space if necessary
               if (strlen($token) > 1) $phrasesub .= ((strlen($phrasesub) > 0)? ' ' : null) . substr($token, 0, -1);
               $phrase = null;
            }
            else
            {
               // If not, add the token to the phrase, with a single leading space if necessary
               $phrasesub .= ((strlen($phrasesub) > 0)? ' ' : null) . $token;
            }
         }
         else
         {
            // Will the current token start a phrase?
            if (strpos($quotes, $token[0]) !== false)
            {
               // Will the current token end the phrase?
               if ((strlen($token) > 1) AND ($token[0] === substr($token, -1, 1)))
               {
                  // The current token begins AND ends the phrase, trim the quotes
                  $phrasesub = substr($token, 1, -1);
               }
               else
               {
                  // Remove the leading quote
                  $phrasesub = substr($token, 1);
                  $phrase = $token[0];
               }
            }
            else $phrasesub = $token;
         }
      }

      // If, at this point, we are not within a phrase, the prepared phrase is complete and can be added to the array
      if (($phrase === null) AND ($phrasesub != null))
      {
         $phrasesub = strtolower($phrasesub);
         if (!in_array($phrasesub, $ret)) $ret[] = $phrasesub;
         $phrasesub = null;
      }
   }
   while ($token !== false); // Stop when we receive FALSE from strtok()
   return $ret;
}







// converts html entities to ordinal-value entities
function phx_ordinal_entities($text)
{
   $lookfor = '#(\&[\w]+;)#e';
   $replace = "'&#' . ord(html_entity_decode('$1')) . ';'";
   return preg_replace($lookfor, $replace, $text);
}

// function replacement that doesn't double-encode HTML entities
function pem_specialchars($text, $quotes = 0)
{
   $text = preg_replace("/&([^#])(?![a-z1-4]{1,8};)/", "&#038;$1", $text);
   $text = str_replace("<", "&lt;", $text);
   $text = str_replace(">", "&gt;", $text);
   if ("double" === $quotes )
   {
     $text = str_replace('"', "&quot;", $text);
   }
   elseif ( "single" === $quotes )
   {
     $text = str_replace("'", "&#039;", $text);
   }
   elseif ( $quotes )
   {
     $text = str_replace('"', "&quot;", $text);
     $text = str_replace("'", "&#039;", $text);
   }
   return $text;
}

function utf8_uri_encode( $utf8_string )
{
  $unicode = '';
  $values = array();
  $num_octets = 1;

  for ($i = 0; $i < strlen( $utf8_string ); $i++ ) {

   $value = ord( $utf8_string[ $i ] );

   if ( $value < 128 ) {
     $unicode .= chr($value);
   } else {
     if ( count( $values ) == 0 ) $num_octets = ( $value < 224 ) ? 2 : 3;

     $values[] = $value;

     if ( count( $values ) == $num_octets ) {
   if ($num_octets == 3) {
    $unicode .= '%' . dechex($values[0]) . '%' . dechex($values[1]) . '%' . dechex($values[2]);
   } else {
    $unicode .= '%' . dechex($values[0]) . '%' . dechex($values[1]);
   }

   $values = array();
   $num_octets = 1;
     }
   }
  }
  return $unicode;
}



// =============================================================================
// ===================== DEPRECATED WITH USE OF MDB2 ===========================
// =============================================================================

// Appllies backslash-escape quoting unless PHP is configured to do it
// automatically. Use this for GET/POST form parameters
function pem_addslashes($s)
{
   if (get_magic_quotes_gpc()) return $s;
   else return addslashes($s);
}

// Remove backslash-escape quoting if PHP is configured to do it with
// magic_quotes_gpc.
function pem_stripslashes($s)
{
   if (get_magic_quotes_gpc()) return stripslashes($s);
   else return $s;
}

function backslashit($string)
{
   $string = preg_replace('/^([0-9])/', '\\\\\\\\\1', $string);
   $string = preg_replace('/([a-z])/i', '\\\\\1', $string);
   return $string;
}

function trailingslashit($string)
{
   if ( '/' != substr($string, -1))
   {
      $string .= '/';
   }
   return $string;
}



// =============================================================================
// ========================= CHARACTER CONVERSION ==============================
// =============================================================================

// Returns a string with accents or umlauts without these
function remove_accents($string)
{
   if ( !preg_match('/[\x80-\xff]/', $string) )
     return $string;

   if (seems_utf8($string)) {
     $chars = array(
     // Decompositions for Latin-1 Supplement
     chr(195).chr(128) => 'A', chr(195).chr(129) => 'A',
     chr(195).chr(130) => 'A', chr(195).chr(131) => 'A',
     chr(195).chr(132) => 'A', chr(195).chr(133) => 'A',
     chr(195).chr(135) => 'C', chr(195).chr(136) => 'E',
     chr(195).chr(137) => 'E', chr(195).chr(138) => 'E',
     chr(195).chr(139) => 'E', chr(195).chr(140) => 'I',
     chr(195).chr(141) => 'I', chr(195).chr(142) => 'I',
     chr(195).chr(143) => 'I', chr(195).chr(145) => 'N',
     chr(195).chr(146) => 'O', chr(195).chr(147) => 'O',
     chr(195).chr(148) => 'O', chr(195).chr(149) => 'O',
     chr(195).chr(150) => 'O', chr(195).chr(153) => 'U',
     chr(195).chr(154) => 'U', chr(195).chr(155) => 'U',
     chr(195).chr(156) => 'U', chr(195).chr(157) => 'Y',
     chr(195).chr(159) => 's', chr(195).chr(160) => 'a',
     chr(195).chr(161) => 'a', chr(195).chr(162) => 'a',
     chr(195).chr(163) => 'a', chr(195).chr(164) => 'a',
     chr(195).chr(165) => 'a', chr(195).chr(167) => 'c',
     chr(195).chr(168) => 'e', chr(195).chr(169) => 'e',
     chr(195).chr(170) => 'e', chr(195).chr(171) => 'e',
     chr(195).chr(172) => 'i', chr(195).chr(173) => 'i',
     chr(195).chr(174) => 'i', chr(195).chr(175) => 'i',
     chr(195).chr(177) => 'n', chr(195).chr(178) => 'o',
     chr(195).chr(179) => 'o', chr(195).chr(180) => 'o',
     chr(195).chr(181) => 'o', chr(195).chr(182) => 'o',
     chr(195).chr(182) => 'o', chr(195).chr(185) => 'u',
     chr(195).chr(186) => 'u', chr(195).chr(187) => 'u',
     chr(195).chr(188) => 'u', chr(195).chr(189) => 'y',
     chr(195).chr(191) => 'y',
     // Decompositions for Latin Extended-A
     chr(196).chr(128) => 'A', chr(196).chr(129) => 'a',
     chr(196).chr(130) => 'A', chr(196).chr(131) => 'a',
     chr(196).chr(132) => 'A', chr(196).chr(133) => 'a',
     chr(196).chr(134) => 'C', chr(196).chr(135) => 'c',
     chr(196).chr(136) => 'C', chr(196).chr(137) => 'c',
     chr(196).chr(138) => 'C', chr(196).chr(139) => 'c',
     chr(196).chr(140) => 'C', chr(196).chr(141) => 'c',
     chr(196).chr(142) => 'D', chr(196).chr(143) => 'd',
     chr(196).chr(144) => 'D', chr(196).chr(145) => 'd',
     chr(196).chr(146) => 'E', chr(196).chr(147) => 'e',
     chr(196).chr(148) => 'E', chr(196).chr(149) => 'e',
     chr(196).chr(150) => 'E', chr(196).chr(151) => 'e',
     chr(196).chr(152) => 'E', chr(196).chr(153) => 'e',
     chr(196).chr(154) => 'E', chr(196).chr(155) => 'e',
     chr(196).chr(156) => 'G', chr(196).chr(157) => 'g',
     chr(196).chr(158) => 'G', chr(196).chr(159) => 'g',
     chr(196).chr(160) => 'G', chr(196).chr(161) => 'g',
     chr(196).chr(162) => 'G', chr(196).chr(163) => 'g',
     chr(196).chr(164) => 'H', chr(196).chr(165) => 'h',
     chr(196).chr(166) => 'H', chr(196).chr(167) => 'h',
     chr(196).chr(168) => 'I', chr(196).chr(169) => 'i',
     chr(196).chr(170) => 'I', chr(196).chr(171) => 'i',
     chr(196).chr(172) => 'I', chr(196).chr(173) => 'i',
     chr(196).chr(174) => 'I', chr(196).chr(175) => 'i',
     chr(196).chr(176) => 'I', chr(196).chr(177) => 'i',
     chr(196).chr(178) => 'IJ',chr(196).chr(179) => 'ij',
     chr(196).chr(180) => 'J', chr(196).chr(181) => 'j',
     chr(196).chr(182) => 'K', chr(196).chr(183) => 'k',
     chr(196).chr(184) => 'k', chr(196).chr(185) => 'L',
     chr(196).chr(186) => 'l', chr(196).chr(187) => 'L',
     chr(196).chr(188) => 'l', chr(196).chr(189) => 'L',
     chr(196).chr(190) => 'l', chr(196).chr(191) => 'L',
     chr(197).chr(128) => 'l', chr(197).chr(129) => 'L',
     chr(197).chr(130) => 'l', chr(197).chr(131) => 'N',
     chr(197).chr(132) => 'n', chr(197).chr(133) => 'N',
     chr(197).chr(134) => 'n', chr(197).chr(135) => 'N',
     chr(197).chr(136) => 'n', chr(197).chr(137) => 'N',
     chr(197).chr(138) => 'n', chr(197).chr(139) => 'N',
     chr(197).chr(140) => 'O', chr(197).chr(141) => 'o',
     chr(197).chr(142) => 'O', chr(197).chr(143) => 'o',
     chr(197).chr(144) => 'O', chr(197).chr(145) => 'o',
     chr(197).chr(146) => 'OE',chr(197).chr(147) => 'oe',
     chr(197).chr(148) => 'R',chr(197).chr(149) => 'r',
     chr(197).chr(150) => 'R',chr(197).chr(151) => 'r',
     chr(197).chr(152) => 'R',chr(197).chr(153) => 'r',
     chr(197).chr(154) => 'S',chr(197).chr(155) => 's',
     chr(197).chr(156) => 'S',chr(197).chr(157) => 's',
     chr(197).chr(158) => 'S',chr(197).chr(159) => 's',
     chr(197).chr(160) => 'S', chr(197).chr(161) => 's',
     chr(197).chr(162) => 'T', chr(197).chr(163) => 't',
     chr(197).chr(164) => 'T', chr(197).chr(165) => 't',
     chr(197).chr(166) => 'T', chr(197).chr(167) => 't',
     chr(197).chr(168) => 'U', chr(197).chr(169) => 'u',
     chr(197).chr(170) => 'U', chr(197).chr(171) => 'u',
     chr(197).chr(172) => 'U', chr(197).chr(173) => 'u',
     chr(197).chr(174) => 'U', chr(197).chr(175) => 'u',
     chr(197).chr(176) => 'U', chr(197).chr(177) => 'u',
     chr(197).chr(178) => 'U', chr(197).chr(179) => 'u',
     chr(197).chr(180) => 'W', chr(197).chr(181) => 'w',
     chr(197).chr(182) => 'Y', chr(197).chr(183) => 'y',
     chr(197).chr(184) => 'Y', chr(197).chr(185) => 'Z',
     chr(197).chr(186) => 'z', chr(197).chr(187) => 'Z',
     chr(197).chr(188) => 'z', chr(197).chr(189) => 'Z',
     chr(197).chr(190) => 'z', chr(197).chr(191) => 's',
     // Euro Sign
     chr(226).chr(130).chr(172) => 'E',
     // GBP (Pound) Sign
     chr(194).chr(163) => '');

     $string = strtr($string, $chars);
   }
  else
  {
     // Assume ISO-8859-1 if not UTF-8
     $chars['in'] = chr(128).chr(131).chr(138).chr(142).chr(154).chr(158)
      .chr(159).chr(162).chr(165).chr(181).chr(192).chr(193).chr(194)
      .chr(195).chr(196).chr(197).chr(199).chr(200).chr(201).chr(202)
      .chr(203).chr(204).chr(205).chr(206).chr(207).chr(209).chr(210)
      .chr(211).chr(212).chr(213).chr(214).chr(216).chr(217).chr(218)
      .chr(219).chr(220).chr(221).chr(224).chr(225).chr(226).chr(227)
      .chr(228).chr(229).chr(231).chr(232).chr(233).chr(234).chr(235)
      .chr(236).chr(237).chr(238).chr(239).chr(241).chr(242).chr(243)
      .chr(244).chr(245).chr(246).chr(248).chr(249).chr(250).chr(251)
      .chr(252).chr(253).chr(255);

     $chars['out'] = "EfSZszYcYuAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy";

     $string = strtr($string, $chars['in'], $chars['out']);
     $double_chars['in'] = array(chr(140), chr(156), chr(198), chr(208), chr(222), chr(223), chr(230), chr(240), chr(254));
     $double_chars['out'] = array('OE', 'oe', 'AE', 'DH', 'TH', 'ss', 'ae', 'dh', 'th');
     $string = str_replace($double_chars['in'], $double_chars['out'], $string);
   }
   return $string;
}

// Translation of invalid Unicode references range to valid range
function convert_chars($content, $flag = 'obsolete')
{
   // Translation of invalid Unicode references range to valid range
   $wp_htmltranswinuni = array(
   '&#128;' => '&#8364;', // the Euro sign
   '&#129;' => '',
   '&#130;' => '&#8218;', // these are Windows CP1252 specific characters
   '&#131;' => '&#402;',  // they would look weird on non-Windows browsers
   '&#132;' => '&#8222;',
   '&#133;' => '&#8230;',
   '&#134;' => '&#8224;',
   '&#135;' => '&#8225;',
   '&#136;' => '&#710;',
   '&#137;' => '&#8240;',
   '&#138;' => '&#352;',
   '&#139;' => '&#8249;',
   '&#140;' => '&#338;',
   '&#141;' => '',
   '&#142;' => '&#382;',
   '&#143;' => '',
   '&#144;' => '',
   '&#145;' => '&#8216;',
   '&#146;' => '&#8217;',
   '&#147;' => '&#8220;',
   '&#148;' => '&#8221;',
   '&#149;' => '&#8226;',
   '&#150;' => '&#8211;',
   '&#151;' => '&#8212;',
   '&#152;' => '&#732;',
   '&#153;' => '&#8482;',
   '&#154;' => '&#353;',
   '&#155;' => '&#8250;',
   '&#156;' => '&#339;',
   '&#157;' => '',
   '&#158;' => '',
   '&#159;' => '&#376;'
   );

   // Remove metadata tags
   $content = preg_replace('/<title>(.+?)<\/title>/','',$content);
   $content = preg_replace('/<category>(.+?)<\/category>/','',$content);

   // Converts lone & characters into &#38; (a.k.a. &amp;)
   $content = preg_replace('/&([^#])(?![a-z1-4]{1,8};)/i', '&#038;$1', $content);

   // Fix Word pasting
   $content = strtr($content, $wp_htmltranswinuni);

   // Just a little XHTML help
   $content = str_replace('<br>', '<br />', $content);
   $content = str_replace('<hr>', '<hr />', $content);

   return $content;
}

function ent2ncr($text)
{
   $to_ncr = array(
     '&quot;' => '&#34;',
     '&amp;' => '&#38;',
     '&frasl;' => '&#47;',
     '&lt;' => '&#60;',
     '&gt;' => '&#62;',
     '|' => '&#124;',
     '&nbsp;' => '&#160;',
     '&iexcl;' => '&#161;',
     '&cent;' => '&#162;',
     '&pound;' => '&#163;',
     '&curren;' => '&#164;',
     '&yen;' => '&#165;',
     '&brvbar;' => '&#166;',
     '&brkbar;' => '&#166;',
     '&sect;' => '&#167;',
     '&uml;' => '&#168;',
     '&die;' => '&#168;',
     '&copy;' => '&#169;',
     '&ordf;' => '&#170;',
     '&laquo;' => '&#171;',
     '&not;' => '&#172;',
     '&shy;' => '&#173;',
     '&reg;' => '&#174;',
     '&macr;' => '&#175;',
     '&hibar;' => '&#175;',
     '&deg;' => '&#176;',
     '&plusmn;' => '&#177;',
     '&sup2;' => '&#178;',
     '&sup3;' => '&#179;',
     '&acute;' => '&#180;',
     '&micro;' => '&#181;',
     '&para;' => '&#182;',
     '&middot;' => '&#183;',
     '&cedil;' => '&#184;',
     '&sup1;' => '&#185;',
     '&ordm;' => '&#186;',
     '&raquo;' => '&#187;',
     '&frac14;' => '&#188;',
     '&frac12;' => '&#189;',
     '&frac34;' => '&#190;',
     '&iquest;' => '&#191;',
     '&Agrave;' => '&#192;',
     '&Aacute;' => '&#193;',
     '&Acirc;' => '&#194;',
     '&Atilde;' => '&#195;',
     '&Auml;' => '&#196;',
     '&Aring;' => '&#197;',
     '&AElig;' => '&#198;',
     '&Ccedil;' => '&#199;',
     '&Egrave;' => '&#200;',
     '&Eacute;' => '&#201;',
     '&Ecirc;' => '&#202;',
     '&Euml;' => '&#203;',
     '&Igrave;' => '&#204;',
     '&Iacute;' => '&#205;',
     '&Icirc;' => '&#206;',
     '&Iuml;' => '&#207;',
     '&ETH;' => '&#208;',
     '&Ntilde;' => '&#209;',
     '&Ograve;' => '&#210;',
     '&Oacute;' => '&#211;',
     '&Ocirc;' => '&#212;',
     '&Otilde;' => '&#213;',
     '&Ouml;' => '&#214;',
     '&times;' => '&#215;',
     '&Oslash;' => '&#216;',
     '&Ugrave;' => '&#217;',
     '&Uacute;' => '&#218;',
     '&Ucirc;' => '&#219;',
     '&Uuml;' => '&#220;',
     '&Yacute;' => '&#221;',
     '&THORN;' => '&#222;',
     '&szlig;' => '&#223;',
     '&agrave;' => '&#224;',
     '&aacute;' => '&#225;',
     '&acirc;' => '&#226;',
     '&atilde;' => '&#227;',
     '&auml;' => '&#228;',
     '&aring;' => '&#229;',
     '&aelig;' => '&#230;',
     '&ccedil;' => '&#231;',
     '&egrave;' => '&#232;',
     '&eacute;' => '&#233;',
     '&ecirc;' => '&#234;',
     '&euml;' => '&#235;',
     '&igrave;' => '&#236;',
     '&iacute;' => '&#237;',
     '&icirc;' => '&#238;',
     '&iuml;' => '&#239;',
     '&eth;' => '&#240;',
     '&ntilde;' => '&#241;',
     '&ograve;' => '&#242;',
     '&oacute;' => '&#243;',
     '&ocirc;' => '&#244;',
     '&otilde;' => '&#245;',
     '&ouml;' => '&#246;',
     '&divide;' => '&#247;',
     '&oslash;' => '&#248;',
     '&ugrave;' => '&#249;',
     '&uacute;' => '&#250;',
     '&ucirc;' => '&#251;',
     '&uuml;' => '&#252;',
     '&yacute;' => '&#253;',
     '&thorn;' => '&#254;',
     '&yuml;' => '&#255;',
     '&OElig;' => '&#338;',
     '&oelig;' => '&#339;',
     '&Scaron;' => '&#352;',
     '&scaron;' => '&#353;',
     '&Yuml;' => '&#376;',
     '&fnof;' => '&#402;',
     '&circ;' => '&#710;',
     '&tilde;' => '&#732;',
     '&Alpha;' => '&#913;',
     '&Beta;' => '&#914;',
     '&Gamma;' => '&#915;',
     '&Delta;' => '&#916;',
     '&Epsilon;' => '&#917;',
     '&Zeta;' => '&#918;',
     '&Eta;' => '&#919;',
     '&Theta;' => '&#920;',
     '&Iota;' => '&#921;',
     '&Kappa;' => '&#922;',
     '&Lambda;' => '&#923;',
     '&Mu;' => '&#924;',
     '&Nu;' => '&#925;',
     '&Xi;' => '&#926;',
     '&Omicron;' => '&#927;',
     '&Pi;' => '&#928;',
     '&Rho;' => '&#929;',
     '&Sigma;' => '&#931;',
     '&Tau;' => '&#932;',
     '&Upsilon;' => '&#933;',
     '&Phi;' => '&#934;',
     '&Chi;' => '&#935;',
     '&Psi;' => '&#936;',
     '&Omega;' => '&#937;',
     '&alpha;' => '&#945;',
     '&beta;' => '&#946;',
     '&gamma;' => '&#947;',
     '&delta;' => '&#948;',
     '&epsilon;' => '&#949;',
     '&zeta;' => '&#950;',
     '&eta;' => '&#951;',
     '&theta;' => '&#952;',
     '&iota;' => '&#953;',
     '&kappa;' => '&#954;',
     '&lambda;' => '&#955;',
     '&mu;' => '&#956;',
     '&nu;' => '&#957;',
     '&xi;' => '&#958;',
     '&omicron;' => '&#959;',
     '&pi;' => '&#960;',
     '&rho;' => '&#961;',
     '&sigmaf;' => '&#962;',
     '&sigma;' => '&#963;',
     '&tau;' => '&#964;',
     '&upsilon;' => '&#965;',
     '&phi;' => '&#966;',
     '&chi;' => '&#967;',
     '&psi;' => '&#968;',
     '&omega;' => '&#969;',
     '&thetasym;' => '&#977;',
     '&upsih;' => '&#978;',
     '&piv;' => '&#982;',
     '&ensp;' => '&#8194;',
     '&emsp;' => '&#8195;',
     '&thinsp;' => '&#8201;',
     '&zwnj;' => '&#8204;',
     '&zwj;' => '&#8205;',
     '&lrm;' => '&#8206;',
     '&rlm;' => '&#8207;',
     '&ndash;' => '&#8211;',
     '&mdash;' => '&#8212;',
     '&lsquo;' => '&#8216;',
     '&rsquo;' => '&#8217;',
     '&sbquo;' => '&#8218;',
     '&ldquo;' => '&#8220;',
     '&rdquo;' => '&#8221;',
     '&bdquo;' => '&#8222;',
     '&dagger;' => '&#8224;',
     '&Dagger;' => '&#8225;',
     '&bull;' => '&#8226;',
     '&hellip;' => '&#8230;',
     '&permil;' => '&#8240;',
     '&prime;' => '&#8242;',
     '&Prime;' => '&#8243;',
     '&lsaquo;' => '&#8249;',
     '&rsaquo;' => '&#8250;',
     '&oline;' => '&#8254;',
     '&frasl;' => '&#8260;',
     '&euro;' => '&#8364;',
     '&image;' => '&#8465;',
     '&weierp;' => '&#8472;',
     '&real;' => '&#8476;',
     '&trade;' => '&#8482;',
     '&alefsym;' => '&#8501;',
     '&crarr;' => '&#8629;',
     '&lArr;' => '&#8656;',
     '&uArr;' => '&#8657;',
     '&rArr;' => '&#8658;',
     '&dArr;' => '&#8659;',
     '&hArr;' => '&#8660;',
     '&forall;' => '&#8704;',
     '&part;' => '&#8706;',
     '&exist;' => '&#8707;',
     '&empty;' => '&#8709;',
     '&nabla;' => '&#8711;',
     '&isin;' => '&#8712;',
     '&notin;' => '&#8713;',
     '&ni;' => '&#8715;',
     '&prod;' => '&#8719;',
     '&sum;' => '&#8721;',
     '&minus;' => '&#8722;',
     '&lowast;' => '&#8727;',
     '&radic;' => '&#8730;',
     '&prop;' => '&#8733;',
     '&infin;' => '&#8734;',
     '&ang;' => '&#8736;',
     '&and;' => '&#8743;',
     '&or;' => '&#8744;',
     '&cap;' => '&#8745;',
     '&cup;' => '&#8746;',
     '&int;' => '&#8747;',
     '&there4;' => '&#8756;',
     '&sim;' => '&#8764;',
     '&cong;' => '&#8773;',
     '&asymp;' => '&#8776;',
     '&ne;' => '&#8800;',
     '&equiv;' => '&#8801;',
     '&le;' => '&#8804;',
     '&ge;' => '&#8805;',
     '&sub;' => '&#8834;',
     '&sup;' => '&#8835;',
     '&nsub;' => '&#8836;',
     '&sube;' => '&#8838;',
     '&supe;' => '&#8839;',
     '&oplus;' => '&#8853;',
     '&otimes;' => '&#8855;',
     '&perp;' => '&#8869;',
     '&sdot;' => '&#8901;',
     '&lceil;' => '&#8968;',
     '&rceil;' => '&#8969;',
     '&lfloor;' => '&#8970;',
     '&rfloor;' => '&#8971;',
     '&lang;' => '&#9001;',
     '&rang;' => '&#9002;',
     '&larr;' => '&#8592;',
     '&uarr;' => '&#8593;',
     '&rarr;' => '&#8594;',
     '&darr;' => '&#8595;',
     '&harr;' => '&#8596;',
     '&loz;' => '&#9674;',
     '&spades;' => '&#9824;',
     '&clubs;' => '&#9827;',
     '&hearts;' => '&#9829;',
     '&diams;' => '&#9830;'
   );
   return str_replace(array_keys($to_ncr), array_values($to_ncr), $text);
}

?>
