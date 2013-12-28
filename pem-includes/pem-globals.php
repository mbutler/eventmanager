<?php
/* ========================== FILE INFORMATION ================================= 
This library unsets unneeded global variables and converts the variable names of 
useful globals to simple formats.  

Original Authors: phpMyAdmin and WordPress projects.
Revisions by: kjhatch for PEM
Version: 1.35
Last Revised: 2007/02/13
============================================================================= */


// ========================= VARIABLE HANDLING ================================= 
// Turn register globals off
function unregister_GLOBALS() 
{
   if (!ini_get("register_globals")) return;
   if (isset($_REQUEST["GLOBALS"])) die("GLOBALS overwrite attempt detected");

   // Variables that shouldn't be unset
   $noUnset = array("GLOBALS", "_COOKIE", "_ENV", "_FILES", "_GET", "_POST", "_REQUEST", "_SERVER", "table_prefix");
   $input = array_merge($_GET, $_POST, $_COOKIE, $_SERVER, $_ENV, $_FILES, isset($_SESSION) AND is_array($_SESSION) ? $_SESSION : array());
   foreach ($input as $k => $v) 
   {
     if (!in_array($k, $noUnset) AND isset($GLOBALS[$k])) unset($GLOBALS[$k]);
   }
}
unregister_GLOBALS(); 

// Fix for IIS, which doesn't set REQUEST_URI
if (empty($_SERVER["REQUEST_URI"])) 
{
   $_SERVER["REQUEST_URI"] = $_SERVER["SCRIPT_NAME"]; // Does this work under CGI?

   // Append the query string if it exists and isn"t null
   if (isset($_SERVER["QUERY_STRING"]) AND !empty($_SERVER["QUERY_STRING"])) 
   {
     $_SERVER["REQUEST_URI"] .= "?" . $_SERVER["QUERY_STRING"];
   }
}

// ======== GET ========
if (!empty($_GET)) 
{
   extract($_GET, EXTR_OVERWRITE);
}
elseif (!empty($HTTP_GET_VARS)) 
{
   extract($HTTP_GET_VARS, EXTR_OVERWRITE);
}

// ======== POST ========
if (!empty($_POST)) 
{
   extract($_POST, EXTR_OVERWRITE);
}
elseif (!empty($HTTP_POST_VARS)) 
{
   extract($HTTP_POST_VARS, EXTR_OVERWRITE);
}

// ======== PHP_SELF ========
if (!empty($_SERVER) AND isset($_SERVER["PHP_SELF"])) 
{
   $PHP_SELF = $_SERVER["PHP_SELF"];
}
else if (!empty($HTTP_SERVER_VARS) AND isset($HTTP_SERVER_VARS["PHP_SELF"])) 
{
   $PHP_SELF = $HTTP_SERVER_VARS["PHP_SELF"];
}
if (empty($PHP_SELF))
{
   $_SERVER["PHP_SELF"] = $PHP_SELF = preg_replace("/(\?.*)?$/","",$_SERVER["REQUEST_URI"]);
}
if (strrpos($PHP_SELF, "index.") != false)
{
   $PHP_SELF = substr($PHP_SELF, 0, strlen($PHP_SELF)-strlen (strstr ($PHP_SELF,"index")));
}

// ======== PHP_AUTH_USER ========
if (!empty($_SERVER) AND isset($_SERVER["PHP_AUTH_USER"])) 
{
   $PHP_AUTH_USER = $_SERVER["PHP_AUTH_USER"];
}
elseif (!empty($HTTP_SERVER_VARS) AND isset($HTTP_SERVER_VARS["PHP_AUTH_USER"])) 
{
   $PHP_AUTH_USER = $HTTP_SERVER_VARS["PHP_AUTH_USER"];
}

// ======== PHP_AUTH_PW ========
if (!empty($_SERVER) AND isset($_SERVER["PHP_AUTH_PW"])) 
{
   $PHP_AUTH_PW = $_SERVER["PHP_AUTH_PW"];
}
elseif (!empty($HTTP_SERVER_VARS) AND isset($HTTP_SERVER_VARS["PHP_AUTH_PW"])) 
{
   $PHP_AUTH_PW = $HTTP_SERVER_VARS["PHP_AUTH_PW"];
}

// ======== REMOTE_USER ========
if (!empty($_SERVER) AND isset($_SERVER["REMOTE_USER"])) 
{
   $REMOTE_USER = $_SERVER["REMOTE_USER"];
}
elseif (!empty($HTTP_SERVER_VARS) AND isset($HTTP_SERVER_VARS["REMOTE_USER"])) 
{
   $REMOTE_USER = $HTTP_SERVER_VARS["REMOTE_USER"];
}

// ======== REMOTE_ADDR ========
if (!empty($_SERVER) AND isset($_SERVER["REMOTE_ADDR"])) 
{
   $REMOTE_ADDR = $_SERVER["REMOTE_ADDR"];
}
elseif (!empty($HTTP_SERVER_VARS) AND isset($HTTP_SERVER_VARS["REMOTE_ADDR"])) 
{
   $REMOTE_ADDR = $HTTP_SERVER_VARS["REMOTE_ADDR"];
}

// ======== QUERY_STRING ========
if (!empty($_SERVER) AND isset($_SERVER["QUERY_STRING"])) 
{
   $QUERY_STRING = $_SERVER["QUERY_STRING"];
}
elseif (!empty($HTTP_SERVER_VARS) AND isset($HTTP_SERVER_VARS["QUERY_STRING"])) 
{
   $QUERY_STRING = $HTTP_SERVER_VARS["QUERY_STRING"];
}

// ======== REQUEST_URI_STRING ========
if (!empty($_SERVER) AND isset($_SERVER["REQUEST_URI"])) 
{
   $REQUEST_URI = $_SERVER["REQUEST_URI"];
}
elseif (!empty($HTTP_SERVER_VARS) AND isset($HTTP_SERVER_VARS["REQUEST_URI"])) 
{
   $REQUEST_URI = $HTTP_SERVER_VARS["REQUEST_URI"];
}

// ======== HTTP_ACCEPT_LANGUAGE ========
if (!empty($_SERVER) AND isset($_SERVER["HTTP_ACCEPT_LANGUAGE"])) 
{
   $HTTP_ACCEPT_LANGUAGE = $_SERVER["HTTP_ACCEPT_LANGUAGE"];
}
elseif (!empty($HTTP_SERVER_VARS) AND isset($HTTP_SERVER_VARS["HTTP_ACCEPT_LANGUAGE"])) 
{
   $HTTP_ACCEPT_LANGUAGE = $HTTP_SERVER_VARS["HTTP_ACCEPT_LANGUAGE"];
}

// ======== HTTP_REFERER ========
if (!empty($_SERVER) AND isset($_SERVER["HTTP_REFERER"])) 
{
   $HTTP_REFERER = $_SERVER["HTTP_REFERER"];
}
elseif (!empty($HTTP_SERVER_VARS) AND isset($HTTP_SERVER_VARS["HTTP_REFERER"])) 
{
   $HTTP_REFERER = $HTTP_SERVER_VARS["HTTP_REFERER"];
}

// ======== HTTP_HOST ========
if (!empty($_SERVER) AND isset($_SERVER["HTTP_HOST"])) 
{
   $HTTP_HOST = $_SERVER["HTTP_HOST"];
}
elseif (!empty($HTTP_SERVER_VARS) AND isset($HTTP_SERVER_VARS["HTTP_HOST"])) 
{
   $HTTP_HOST = $HTTP_SERVER_VARS["HTTP_HOST"];
}

// ======== HTTPS ========
if (!empty($_SERVER) AND isset($_SERVER["HTTPS"])) 
{
   $HTTPS = $_SERVER["HTTPS"];
}
elseif (!empty($HTTP_SERVER_VARS) AND isset($HTTP_SERVER_VARS["HTTPS"])) 
{
   $HTTPS = $HTTP_SERVER_VARS["HTTPS"];
}

// Fix for PHP as CGI hosts that set SCRIPT_FILENAME to something ending in php.cgi for all requests
if (isset($_SERVER["SCRIPT_FILENAME"]) AND (strpos($_SERVER["SCRIPT_FILENAME"], "php.cgi") == strlen($_SERVER["SCRIPT_FILENAME"]) - 7 ))
{
   $_SERVER["SCRIPT_FILENAME"] = $_SERVER["PATH_TRANSLATED"];
}

// Fix for Dreamhost and other PHP as CGI hosts
if (strstr($_SERVER["SCRIPT_NAME"], "php.cgi" )) 
{ 
   unset($_SERVER["PATH_INFO"]); 
}

// Prep for URI redirection
if (!empty($QUERY_STRING))
{
   $REDIRECT_URL = (isset($HTTPS) AND strtolower($HTTPS) == "on") ? "https://" : "http://";
   // $REDIRECT_URL = ($_SERVER["SERVER_PORT"] != 443) ? "http://" : "https://"; // $HTTPS should provide a more universal solution
   /*
   $self_temp = $PHP_SELF;
   if (strrpos($PHP_SELF, "index.") != false)
   {
      $self_temp = substr($PHP_SELF, 0, strlen($PHP_SELF)-strlen (strstr ($PHP_SELF,"index")));
   }
   */
   $REDIRECT_URL .= $HTTP_HOST . $PHP_SELF;
   unset($self_temp);
}

// Provide cleaned path variable

$PEM_PATH = $REQUEST_URI;
$PEM_PATH = dirname($PEM_PATH);
if (strrpos($PEM_PATH, "pem-admin") != false)
{
   $PEM_PATH = substr($PEM_PATH, 0, strlen($PEM_PATH)-strlen (strstr ($PEM_PATH,"pem-admin")));
}   
?>
