<?php
/* ========================== FILE INFORMATION =================================
phxEventManager :: header.php

============================================================================= */

if (file_exists("../pem-config.php")) include_once "../pem-config.php";
elseif (file_exists("pem-config.php")) include_once "pem-config.php";
else die("There doesn't seem to be a <code>pem-config.php</code> file.  This file was included in the install package as <code>pem-config-template.php</code>.  You can copy pem-config-template.php from a new install if yours have become damaged or deleted.");
include_once ABSPATH . "pem-settings.php";


if (isset($cache_set))
{
   $cache_keys = array_keys($cache_set);
   for ($i = 0; $i < count($cache_keys); $i++)
   {
      pem_cache_set($cache_keys[$i], $cache_set[$cache_keys[$i]]);
   }
}

// if (!strstr($PHP_SELF, "pem-admin"))

// if (!isset($pview)) $pview = 0;
$pagetitle = (isset($pagetitle)) ? " &raquo; " . $pagetitle : "";

/* Example of IP testing to determing internal (staff) user
$staffcheck = TRUE;
$ip = 0;
if (isset($_SERVER['HTTP_X_FORWARD_FOR']))
   $ip = $_SERVER['HTTP_X_FORWARD_FOR'];
else
   $ip = $_SERVER['REMOTE_ADDR'];
// echo $address;
$address = explode(".", $ip);
// 172.16.33.1-33.27 = catalog (all 33)
// 172.16.21.11 =  outside
if ((($address[0] == 172) AND ($address[1] == 16)) AND (($address[2] == 33) OR ($address[2] == 21)))
   $staffcheck = FALSE;
if (($address[0] != 172) AND ($address[1] != 16))
   $staffcheck = FALSE;
  */

// If we dont know the right date then use today
if(!$day) $day = date("d");
if(!$month) $month = date("m");
if(!$year) $year = date("Y");
if (empty($search_str)) $search_str = "";

nocache_headers();

$pagetitle = __($pagetitle);
echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' . "\n";
echo '<html xmlns="http://www.w3.org/1999/xhtml">' . "\n";
echo '<head>' . "\n";
echo '<title>' . $pem_title . $pagetitle . '</title>' . "\n";
include "pem-style.php";
// thickbox now used globally with help
//if (isset($use_thickbox) OR $navigation == "views")
//{
   echo '<link rel="stylesheet" href="/pem-includes/thickbox.css" type="text/css" media="screen" />' . "\n";
   echo '<script type="text/javascript" src="/pem-includes/jquery.js"></script>' . "\n";
   echo '<script type="text/javascript" src="/pem-includes/thickbox.js"></script>' . "\n";
//}
if (isset($focus_on_field)) pem_field_focus($focus_on_field);
echo '<script type="text/javascript" src="/pem-includes/interface.js"></script>' . "\n";
if (isset($use_calpop))
{
   echo '<script src="mootools.v1.11.js" type="text/javascript"></script>' . "\n";
   echo '<script src="nogray_date_calendar_vs1_min.js" type="text/javascript"></script>' . "\n";
   // echo '<script src="nogray_calendar_pem.js" type="text/javascript"></script>' . "\n";
   echo '<link href="nogray_calendar_vs1.css" rel="stylesheet" type="text/css" />' . "\n";
}
else
{
	echo '<script type="text/javascript" src="/pem-includes/date-selector.js"></script>' . "\n";
}
if (isset($add_javascript))
{
   echo '<script type="text/javascript"><!--' . "\n";
   echo $add_javascript;
   echo '// --></script>' . "\n";
}
if (isset($use_xajax))
{
   $xajax->printJavascript($sJsURI=$XAJAX_DIR);
}
echo '</head>' . "\n";
echo '<body>' . "\n";
if (isset($page_access_requirement)) pem_user_required($page_access_requirement);


// echo "Action: $a <br />ERROR: $login_error <br />";

// if (pem_cache_isset("current_action")) switch(pem_cache_get("current_action"))
if (isset($a))
{
   switch($a)
   {
   case "login":
      pem_echo_login_form();
      break;
   case "dologin":
      if (isset($login_error))
      pem_echo_login_form(array($login_error), $new_user_login, $new_user_password);
      break;
   }
}
if ($pem_installing)
{
   pem_simple_header(true);
}
elseif (!isset($printview))
{
   echo '<div id="header">' . "\n";
   // Need to set up an admin toggle setting to allow public accounts to

   // Login is optional and respective to the session type
   if (function_exists("pem_echo_login"))
   {

// TODO to remove login button by IP use the following lines
//      $staffcheck = true;
//      $ip = (isset($_SERVER['HTTP_X_FORWARD_FOR'])) ? $_SERVER['HTTP_X_FORWARD_FOR'] : $_SERVER['REMOTE_ADDR'];
//      $address = explode(".", $ip);
//
//      if ((($address[0] == 172) AND ($address[1] == 16)) AND (($address[2] == 33) OR ($address[2] == 21))) $staffcheck = false;
//      if (($address[0] != 172) AND ($address[1] != 16)) $staffcheck = false;
//      if ($staffcheck) pem_echo_login();

      // echo '<div id="header-login">';
      pem_echo_login();
      // echo '</div>' . "\n";
   }

   echo '<div id="header-nav"><span>';
   echo '<a href="' . $pem_url . '?e=event">' . __("Calendar") . '</a>';
   echo ' : <a href="' . $pem_url . 'search.php">' . __("Search") . '</a>';
   if (pem_user_authorized("Manage Any")) echo ' : <a href="' . $pem_url . 'pem-admin/">' . __("Admin") . '</a>';
   echo ' : <a href="' . $pem_url . 'help.php?KeepThis=true&TB_iframe=true&height=200&width=400" title="Help" class="thickbox">' . __("Help") . '</a>';
// TODO automate this with form-based entry
// you can hardcode your own links to the header navigation here in the meantime
//   echo ' : <a href="http://www.mysite.org/">mysite</a>';
   echo '</span></div>' . "\n";


/*
echo '<div id="header-search">';
echo '<form method="get" action="search.php"><span class="searchlabel">' . __("Search:") . '</span>&nbsp;';
echo '<input type="text" name="search_str" value="'.$search_str.'" size="20" />&nbsp;&nbsp;<a href="search.php?advanced=1" class="searchlink">' . __("[advanced]") . '</a>';
echo '</form>';
echo '</div>' . "\n";
*/





   if (empty($title_img)) echo '<div id="header-title"><a href="' . $pem_url . '" title="' . $pem_title . '">' . $pem_title . '</a></div>' . "\n";
   else echo '<div id="header-image"><a href="' . $pem_url . '" title="' . $pem_title . '"><img src="' . $pem_url . 'pem-themes/' . $pem_theme . '/' . $title_img . '" alt="' . $pem_title . '" /></a></div>' . "\n";

   echo '</div>' . "\n";  // END id="header"
   $header_complete = true;

   if (isset($navigation) AND $navigation == "administration")
   {
      include_once ABSPATH . PEMINC . "/nav-admin.php";
      echo '<div id="content">' . "\n";
   }
   elseif (isset($navigation) AND $navigation == "views")
   {
      include_once ABSPATH . PEMINC . "/nav-views.php";
      echo '<div id="content">' . "\n";
   }
   elseif (isset($navigation) AND $navigation == "event")
   {
      $current_eview = pem_cache_get("current_eview");
      if ($current_eview == "add" OR $current_eview == "edit" OR $current_eview == "search")
      {
         $eid = pem_cache_get("current_event");
         $did = pem_cache_get("current_date");
         if (!empty($eid))
         {
            $where = array("entry_id" => $eid);
            $dates_count = pem_get_count("dates", $where);
            $hasdates = ($dates_count > 1) ? true : false;
//            $where = array("id" => $did);
//            $regreqdate = pem_get_value("dates", "date_reg_require", $where);
//            $where = array("id" => $eid);
//            $regreqevent = pem_get_value("events", "entry_reg_require", $where);
//            $hasregs = ($regreqdate OR $regreqevent) ? true : false;
         }
         include_once ABSPATH . PEMINC . "/nav-event.php";
         echo '<div id="content">' . "\n";
      }
   }
   else
   {
      echo '<div id="content">' . "\n";
   }
}
else
   {
      echo '<div id="content-print">' . "\n";
   }

?>