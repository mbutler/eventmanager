<?php
/* ========================== FILE INFORMATION =================================
phxEventManager :: pem-settings.php


============================================================================= */

$pem_version = "2.0 beta 5";

// Change to E_ALL for development/debugging
error_reporting(E_ALL ^ E_NOTICE);

include_once ABSPATH . PEMINC . "/pem-globals.php";

if (function_exists("version_compare") AND version_compare(phpversion(), "5.0","<"))
{
   die("Your server is running PHP version " . phpversion() . ", but PEM requires at least PHP 5.0");
}

/* Need to determine and add PEAR packages test here for dB configured by user
if ( !extension_loaded("mysql") )
   die( "Your PHP installation appears to be missing the MySQL which is required for PEM." );
*/

include_once ABSPATH . PEMINC . "/pem-functions.php";

if ($pem_installing)
{
   $pem_theme = "red";
   $content_charset = "UTF-8";
}
else
{
   $settings_values = pem_get_settings();
   if (is_array($settings_values))
   {
      $settings_keys = array_keys($settings_values);
      for ($i = 0; $i < count($settings_keys); $i++)
      {
         if (isset($reloadglobal) OR !isset(${$settings_keys[$i]}))
         {
            ${$settings_keys[$i]} = $settings_values[$settings_keys[$i]];
         }
      }
   }
   else
   {
      $pem_theme = "red";
      $content_charset = "UTF-8";
   }
}

?>