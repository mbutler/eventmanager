<?php
/* ========================== FILE INFORMATION =================================
phxEventManager :: index.php

Main index file for publus use.  It's a hub file that includes others for
content as needed.
============================================================================= */
ini_set("memory_limit","16M");

if (!file_exists("pem-config.php")) //check for existing install
{
   if (strstr($_SERVER["PHP_SELF"], "pem-admin")) $path = "install.php";
   else $path = "pem-admin/install.php";
   header("Location: $path");
   die();
}
$inajax = true;
include_once "pem-config.php";
include_once ABSPATH . "pem-settings.php";

$XAJAX_DIR = PEMINC . "/xajax/";
include_once $XAJAX_DIR . "xajax_core/xajax.inc.php";
$xajax = new xajax();
//$xajax->setFlag("debug", true);
$xajax->registerFunction("set_filters");
$xajax->registerFunction("clear_filters");
$xajax->processRequest();

function set_filters($formdata)
{
   global $pem_url;

   $objResponse = new xajaxResponse();

   if (!empty($formdata))
   {
      if ($formdata["totalspaces"])
      {
         pem_cache_set("current_spaces_all", 1);
         $space_filters_any = false;
      }
      else
      {
         pem_cache_set("current_spaces_all", 0);
         $space_filters_any = true;
      }
      if (isset($formdata["spaces"]))
      {
         $form_spaces = array_keys($formdata["spaces"]);
         $array_hold = array();
         for ($i = 0; $i <= count($form_spaces); $i++)
         {
            if ($form_spaces[$i]{0} == "-")
            {
               $space_array = explode("-", substr($form_spaces[$i], 1));
               $array_hold = array_merge($array_hold, $space_array);
               unset($space_array);
               unset($form_spaces[$i]);
            }
         }
         $form_spaces = array_merge($array_hold, $form_spaces);
         unset($array_hold);
         pem_cache_set("current_spaces", $form_spaces);
      }
      if (isset($formdata["categories"]))
      {
         $form_cats = array_keys($formdata["categories"]);
         pem_cache_set("current_categories", $form_cats);
         if ($form_cats[0] != 1) $cat_filters_any = true;
         else $cat_filters_any = false;
      }
      if ($space_filters_any OR $cat_filters_any) pem_cache_set("current_filters_any", 1);
      else pem_cache_set("current_filters_any", 0);
   }
   $objResponse->redirect($pem_url, $iDelay=0);
   return $objResponse;
}



function clear_filters()
{
   global $pem_url;

   $objResponse = new xajaxResponse();
   pem_cache_set("current_filters_any", 0);
   pem_cache_set("current_spaces_all", 1);
   pem_cache_set("current_categories", array(1));
   $objResponse->redirect($pem_url, $iDelay=0);
   return $objResponse;
}

$inajax = false;

$pagetitle = "";
$navigation = "views";
$use_xajax = true;
include_once "pem-includes/header.php";

if (!strstr($PHP_SELF, "install.php"))
{
   // Used to guarantee unique hash cookies
   $cookiehash = md5(pem_cache_get("url_base"));
   define("COOKIEHASH", $cookiehash);

   if (!defined("LOGIN_COOKIE"))
      define("LOGIN_COOKIE", "phxeventmanagerlogin". COOKIEHASH);
   if (!defined("PASS_COOKIE"))
      define("PASS_COOKIE", "phxeventmanagerpass". COOKIEHASH);
   if (!defined("SITECOOKIEPATH"))
      define("SITECOOKIEPATH", preg_replace("|https?://[^/]+|i", "", pem_cache_get("url_base") . "/" ) );
   if (!defined("COOKIE_DOMAIN"))
      define("COOKIE_DOMAIN", false);
}

// $check_resources = array("Reports", "Manage Reports");
// if(pem_user_authorized($check_resources, true))

// pem_user_required("Manage Any");


// insure proper event view
pem_cache_set("current_eview", "event");
switch(true)
{
   case (pem_cache_get("current_view") == "day"):
      include_once "day.php";
      break;
   case (pem_cache_get("current_view") == "week"):
      include_once "week.php";
      break;
   case (pem_cache_get("current_view") == "month"):
      include_once "month.php";
      break;
   case (pem_cache_get("current_view") == "year"):
   // placeholder for year view
   // include_once "year.php";
      break;
}

include ABSPATH . PEMINC . "/footer.php";
?>