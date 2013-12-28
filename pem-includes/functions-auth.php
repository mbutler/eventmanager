<?php
/* ========================== FILE INFORMATION =================================
phxEventManager :: functions-auth.php


============================================================================= */

// ACCESS STRUCTURE RESOURCES
$auth_res["inte"] = "Internal Calendar";
$auth_res["exte"] = "External Calendar";
$auth_res["intu"] = "Internal Side Box";
$auth_res["extu"] = "External Side Box";
$auth_res["regs"] = "Registrations";
$auth_res["reps"] = "Reports";
$auth_res["stat"] = "Statistics";
$auth_res["meta"] = "Meta Data";
$auth_res["usrs"] = "Users";
$auth_res["priv"] = "View Private";
$auth_res["unap"] = "View Unapproved";
$auth_res["cncl"] = "View Cancelled";


// ACCESS STRUCTURE RESOURCE TEXT
$auth_res_text["inte"] = __("Internal Calendar");
$auth_res_text["exte"] = __("External Calendar");
$auth_res_text["intu"] = __("Internal Side Box");
$auth_res_text["extu"] = __("External Side Box");
$auth_res_text["regs"] = __("Registrations");
$auth_res_text["reps"] = __("Reports");
$auth_res_text["stat"] = __("Statistics");
$auth_res_text["meta"] = __("Meta Data");
$auth_res_text["usrs"] = __("Users");
$auth_res_text["priv"] = __("Can view Private events in the calendar and listings");
$auth_res_text["unap"] = __("Can view Unapproved events in the calendar and listings");
$auth_res_text["cncl"] = __("Can view Cancelled events in the calendar and listings");


// ACCESS STRUCTURE KEYS
$auth_keys[0]  = "None";
$auth_keys[10] = "View";
$auth_keys[11] = "Add";
$auth_keys[12] = "Edit";
$auth_keys[13] = "Edit Own";
$auth_keys[14] = "Edit Others";
$auth_keys[15] = "Edit All";
$auth_keys[16] = "Approve Own";
$auth_keys[17] = "Approve Others";
$auth_keys[18] = "Approve All";
$auth_keys[19] = "Delete";
$auth_keys[20] = "Delete Own";
$auth_keys[21] = "Delete Others";
$auth_keys[22] = "Delete All";

// ACCESS STRUCTURE KEY TEXT
$auth_key_text[0]  = __("None");
$auth_key_text[10] = __("View");
$auth_key_text[11] = __("Add");
$auth_key_text[12] = __("Edit");
$auth_key_text[13] = __("Edit Own");
$auth_key_text[14] = __("Edit Others");
$auth_key_text[15] = __("Edit All");
$auth_key_text[16] = __("Approve Own");
$auth_key_text[17] = __("Approve Others");
$auth_key_text[18] = __("Approve All");
$auth_key_text[19] = __("Delete");
$auth_key_text[20] = __("Delete Own");
$auth_key_text[21] = __("Delete Others");
$auth_key_text[22] = __("Delete All");



// include the authentification wrappers
include_once "auth-$auth[type].php";
if (isset($auth["cache"])) include_once "cache-$auth[cache].php";
if (isset($auth["session"])) include_once "session-$auth[session].php";

if (isset($m)) pem_cache_set("current_month", $m);
elseif (!pem_cache_isset("current_month")) pem_cache_set("current_month", pem_date("m"));
if (isset($d)) pem_cache_set("current_day", $d);
elseif (!pem_cache_isset("current_day")) pem_cache_set("current_day", pem_date("d"));
if (isset($y)) pem_cache_set("current_year", $y);
elseif (!pem_cache_isset("current_year")) pem_cache_set("current_year", pem_date("Y"));

if (isset($h)) pem_cache_set("current_hour", $h);
elseif (!pem_cache_isset("current_hour")) pem_cache_set("current_hour", pem_date("H"));
if (isset($i)) pem_cache_set("current_minute", $i);
elseif (!pem_cache_isset("current_minute")) pem_cache_set("current_minute", pem_date("i"));

if (isset($c))
{
   pem_cache_set("current_categories", array($c));
   pem_cache_set("current_filters_any", 1);
}
elseif (!pem_cache_isset("current_categories")) pem_cache_set("current_categories", array(1));

//if (isset($cat)) pem_cache_set("current_categories", array($cat));


if (isset($s)) pem_cache_set("current_spaces", $s);
elseif (!pem_cache_isset("current_spaces")) pem_cache_set("current_spaces", array(0));

if (isset($a)) pem_cache_set("current_action", $a);
//else
//{
//   $this_action = pem_cache_get("current_action");
//   if (!empty($this_action)) echo "TESTING, ONE MOMENT PLEASE.....   $this_action <br /> ";
//}
elseif (!pem_cache_isset("current_action")) pem_cache_set("current_action", "");

$tmp_view_format = explode("-", pem_get_setting("default_view"));
$tmp_default_view = $tmp_view_format[0];
$tmp_default_format = ($tmp_view_format[1] == "cal") ? "calendar" : "list";

if (isset($n)) pem_cache_set("current_navigation", $n);
elseif (!pem_cache_isset("current_navigation")) pem_cache_set("current_navigation", "events");
if (isset($v)) pem_cache_set("current_view", $v);
elseif (!pem_cache_isset("current_view")) pem_cache_set("current_view", $tmp_default_view);
// elseif (!pem_cache_isset("current_view")) pem_cache_set("current_view", "month");
if (isset($f)) pem_cache_set("current_format", $f);
elseif (!pem_cache_isset("current_format")) pem_cache_set("current_format", $tmp_default_format);
//elseif (!pem_cache_isset("current_format")) pem_cache_set("current_format", "calendar");

if (isset($e)) pem_cache_set("current_eview", $e);
elseif (!pem_cache_isset("current_eview")) pem_cache_set("current_eview", "event");

if (isset($did) AND isset($eid))
{
   pem_cache_set("current_event", $eid);
   pem_cache_set("current_date", $did);
}
elseif (isset($did))
{
   pem_cache_set("current_date", $did);
   pem_cache_flush("current_event");
}
elseif (isset($eid))
{
   pem_cache_set("current_event", $eid);
   pem_cache_flush("current_date");
}





//
//
//echo "<br />pre branch<br />";
//
//if (isset($e))
//{
//echo "<br />branch 1<br />";
//   pem_cache_set("current_eview", $e);
//   if (isset($did) AND isset($eid))
//   {
//      pem_cache_set("current_event", $eid);
//      pem_cache_set("current_date", $did);
//   }
//   elseif (isset($did))
//   {
//echo "<br />branch 1b<br />";
//      pem_cache_set("current_date", $did);
//      pem_cache_flush("current_event");
//   }
//   elseif (isset($eid))
//   {
//echo "<br />branch 1c<br />";
//      pem_cache_set("current_event", $eid);
//      pem_cache_flush("current_date");
//   }
//}
//elseif (isset($did) AND isset($eid))
//{
//echo "<br/>branch 2";
//   pem_cache_set("current_event", $eid);
//   pem_cache_set("current_date", $did);
//   pem_cache_set("current_eview", "event");
//}
//elseif (isset($did))
//{
//   pem_cache_set("current_date", $did);
//   pem_cache_flush("current_event");
//   pem_cache_set("current_eview", "event");
//}
//elseif (isset($eid))
//{
//   pem_cache_set("current_event", $eid);
//   pem_cache_flush("current_date");
//   pem_cache_set("current_eview", "event");
//}
//
//echo "<br />post branch<br />";
//
//
//echo "<br />----------------------------<br />session: ";
//print_r($_SESSION);


//if (isset($e))
//{
//   pem_cache_set("current_eview", $e);
//   if (isset($did) AND isset($eid))
//   {
//      pem_cache_set("current_event", $eid);
//      pem_cache_set("current_date", $did);
//   }
//   elseif (isset($did))
//   {
//      pem_cache_set("current_date", $did);
//      pem_cache_flush("current_event");
//   }
//   elseif (isset($eid))
//   {
//      pem_cache_set("current_event", $eid);
//      pem_cache_flush("current_date");
//   }
//}
//elseif (!pem_cache_isset("current_eview")) pem_cache_set("current_eview", "event");
//else
//{
//   if (isset($did) AND isset($eid))
//   {
//      pem_cache_set("current_event", $eid);
//      pem_cache_set("current_date", $did);
//      pem_cache_set("current_eview", "event");
//   }
//   elseif (isset($did))
//   {
//      pem_cache_set("current_date", $did);
//      pem_cache_flush("current_event");
//      pem_cache_set("current_eview", "event");
//   }
//   elseif (isset($eid))
//   {
//      pem_cache_set("current_event", $eid);
//      pem_cache_flush("current_date");
//      pem_cache_set("current_eview", "event");
//   }
//}

if (isset($t)) pem_cache_set("current_event_type", $t);
elseif (!pem_cache_isset("current_event_type")) pem_cache_set("current_event_type", "scheduled");

if (isset($r)) pem_cache_set("current_report", $r);
elseif (!pem_cache_isset("current_report")) pem_cache_set("current_report", "");

if (!pem_cache_isset("current_spaces_all") AND 0 !== pem_cache_get("current_spaces_all")) pem_cache_set("current_spaces_all", true);
if (!pem_cache_isset("current_filters_any") AND 0 !== pem_cache_get("current_filters_any")) pem_cache_set("current_filters_any", false);



/*
if (isset($s)) pem_cache_set("current_step", $s);
elseif (!pem_cache_isset("current_step")) pem_cache_set("current_step", 0);
if (isset($e)) pem_cache_set("current_error", $e);
elseif (!pem_cache_isset("current_error")) pem_cache_set("current_error", 0);

if (empty($QUERY_STRING) AND strrpos($PHP_SELF, "install.php"))
{
   pem_cache_flush();
}
else
*/

if (!empty($QUERY_STRING) AND !isset($pem_installing))
{
   if (do_redirect($QUERY_STRING))
   {
      if (isset($did)) header("Location: " . $REDIRECT_URL . "?did=" . $did);
      elseif (isset($eid)) header("Location: " . $REDIRECT_URL . "?eid=" . $eid);
      else header("Location: $REDIRECT_URL");
   }
}
// if (!strstr($PHP_SELF, "pem-admin"))


// determine redirect need
function do_redirect($QUERY_STRING)
{
   $redirect = false;
   $qa = explode("&", $QUERY_STRING);
   foreach ($qa AS $value)
   {
   	$qa2 = explode("=", $value);
      if ($qa2[0] != "did" AND $qa2[0] != "eid") $redirect = true;
   }
   return $redirect;
} // END do_redirect

// check to see if the user is currently logged in
function pem_user_anonymous()
{
   $login = pem_get_login();
   return (empty($login));
} // END pem_user_anonymous


// Determines if the current user is authorized for the given resource and key level
// The user "Public" is used for anonymous checks
// Input must be an array or one of the known shorthand calls: Manage Any, Admin
// $resource is handled as a $name => $access_key requirement where the key can also be an array of acceptable values
// Keys can also be shorthanded with true for a global OR check
// the special resources "Manage Any" and "Admin" allow only true/false keys
// ==========================EXAMPLE USAGE===============================
//if (pem_user_authorized("Internal Side Box"))
//if (pem_user_authorized(array("Internal Calendar", "External Calendar")))
//if (pem_user_authorized(array("Internal Calendar" => "View")))
//if (pem_user_authorized(array("Internal Calendar" => "View", "External Calendar" => "Add")))
//if (pem_user_authorized(array("Internal Calendar" => array("Add", "Edit"))))
//if (pem_user_authorized(array("Internal Calendar" => array("View", "Add"), "External Calendar" => array("View", "Add"))))
//if (pem_user_authorized("Admin"))
//if (pem_user_authorized("Manage Any"))
function pem_user_authorized($resource)
{
   global $auth_res;
   $login = pem_get_login();
   $pass = pem_get_pass();
   $usercheck = auth_validate_user($login, $pass);
   if ($usercheck == "validuser")
   {
      $user = auth_get_user($login);
   }
   else
   {
      $user = auth_get_user("public");
   }
   if (!is_array($resource) AND $resource == "Manage Any")
   {
      $auth_res_keys = array_keys($auth_res);
      for ($i = 0; $i < 4; $i++) $check_resource[$auth_res_keys[$i]] = array("Edit Own", "Edit Others", "Edit All", "Approve Own", "Approve Others", "Approve All", "Delete Own", "Delete Others", "Delete All");
      $check_resource[$auth_res_keys[4]] = array("Edit", "Delete");
      $check_resource[$auth_res_keys[5]] = array("View", "Add", "Edit", "Delete");
      for ($i = 6; $i < 9; $i++) $check_resource[$auth_res_keys[$i]] = array("Add", "Edit", "Delete");
   }
   elseif (!is_array($resource) AND $resource == "Admin")
   {
      $check_resource["Admin"] = true;
   }
   elseif (!is_array($resource))
   {
      $auth_res_flip = array_flip($auth_res);
      $check_resource[$auth_res_flip[$resource]] = true;
   }
   else // is_array($resource)
   {
      $resource_keys = array_keys($resource);
      $auth_res_flip = array_flip($auth_res);
      if ($resource_keys[0] === 0)
      {
         foreach ($resource AS $resource_key) $check_resource[$auth_res_flip[$resource_key]] = true;
      }
      else for ($i = 0; $i < count($resource_keys); $i++)
      {
         $check_resource[$auth_res_flip[$resource_keys[$i]]] = $resource[$resource_keys[$i]];
      }
   }
   return auth_check_access($user["user_access"], $check_resource);
} // END pem_user_authorized

// Used as a all-or-nothing check to completely block access if needed, such as at the page level
// Failure to have access results in a denied message
function pem_user_required($resource, $key = true)
{
   $authorized_to_view = (pem_user_authorized($resource, $key));
   $user_is_anonymous = pem_user_anonymous();

   if (!$authorized_to_view AND !empty($user_is_anonymous))
   {
      echo '<p class="important">' . __("This page requires an account login with appropriate access.  Please login to continue.") . '</p>' . "\n";
      pem_echo_login_form($error);
   }
   elseif (!$authorized_to_view)
   {
      global $header_complete;
      if (!isset($header_complete)) pem_simple_header();
      $ret = '<div style="text-align:center; margin-bottom:50px;">';
      $ret .= '<h1>' . __("==== ACCESS DENIED ====") . '</h1>';
      $ret .= '<p>' . __("You do not have rights to this area.  Contact your administrator to remedy the issue.") . '</p>';
      $ret .= '</div>' . "\n";
      echo $ret;
//      $login_url = pem_cache_get("url_base");
      pem_echo_login("/pem-admin/","denied");
//      pem_echo_login($login_url,"denied");
//      pem_echo_login($PHP_SELF, "denied");
      pem_go_back();

      include ABSPATH . PEMINC . "/footer.php";
      exit;
   }
} // END pem_user_required




/* getWritable($creator, $user)
 *
 * Determines if a user is able to modify an entry
 *
 * $creator - The creator of the entry
 * $user    - Who wants to modify it
 *
 * Returns:
 *   0        - The user does not have the required access
 *   non-zero - The user has the required access
 */
function getWritable($creator, $user) {
   global $auth;

   // Always allowed to modify your own stuff
   if($creator == $user)
    return 1;

   if(authGetUserLevel($user, $auth["admin"]) >= 2)
    return 1;

   // Unathorised access
   return 0;
   }


// Checks if a user is logged in, if not redirects them to the login page
function login_redirect()
{
   if ((!empty($_COOKIE[LOGIN_COOKIE]) AND !pem_login($_COOKIE[LOGIN_COOKIE], $_COOKIE[PASS_COOKIE], true)) OR (empty($_COOKIE[LOGIN_COOKIE])))
   {
      nocache_headers();
      pem_redirect(pem_cache_get("url_base") . "pem-login.php?redirect_to=" . urlencode($_SERVER["REQUEST_URI"]));
      exit();
   }
}

function pem_redirect($location, $status = 302)
{
   global $is_IIS;

   $location = preg_replace("|[^a-z0-9-~+_.?#=&;,/:%]|i", "", $location);
   $strip = array("%0d", "%0a");
   $location = str_replace($strip, "", $location);
   if (!$is_IIS) status_header($status);
   header("Refresh: 0;url=$location");
}


?>
