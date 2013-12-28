<?php
/* ========================== FILE INFORMATION =================================
phxEventManager :: cache-php.php

Caches information through the use of PHP session variables.
============================================================================= */

function pem_cache_set($key, $data, $expire = "")
{
   $_SESSION["pem_cache"][$key] = $data;
   return true;
}

function pem_cache_delete($key)
{
   $_SESSION["pem_cache"][$key] = "";
   return true;
}

function pem_cache_flush($key = "")
{
   if (!empty($key))
   {
      unset($_SESSION["pem_cache"][$key]);
   }
   else
   {
      $_SESSION["pem_cache"] = "";
   }
   return true;
}

function pem_cache_get($key)
{
   return (isset($_SESSION["pem_cache"][$key])) ? $_SESSION["pem_cache"][$key] : "";
}

function pem_cache_isset($key)
{
   return (isset($_SESSION["pem_cache"][$key]) AND $_SESSION["pem_cache"][$key] != "") ? true : false;
}

function pem_cache_echo($key)
{
   echo (isset($_SESSION["pem_cache"][$key])) ? $_SESSION["pem_cache"][$key] : "";
}

?>