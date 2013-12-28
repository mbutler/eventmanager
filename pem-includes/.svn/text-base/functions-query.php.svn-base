<?php
/* ========================== FILE INFORMATION =================================
phxEventManager :: functions-query.php

General utility functions that perform checks not already supported by PHP.
These functions are similar to those found in functions-format.php but do not 
return changed variable content. 
============================================================================= */


/* =============================================================================
The following code is executed without function call to establish general 
query variables used throughout.  
============================================================================= */

// Determine the current page
if (preg_match("#([^/]+\.php)$#", $PHP_SELF, $self_matches)) 
{
   $pagenow = $self_matches[1];
}
else if (strstr($PHP_SELF, '?')) 
{
   $pagenow = explode("/", $PHP_SELF);
   $pagenow = trim($pagenow[(sizeof($pagenow)-1)]);
   $pagenow = explode("?", $pagenow);
   $pagenow = $pagenow[0];
}
else 
{
   $pagenow = "index.php";
}

// Simple browser detection
$is_gecko = false; $is_opera = false;  $is_NS4 = false; $is_winIE = false; $is_macIE = false; $is_lynx = false; 
if (preg_match("/Lynx/", $_SERVER["HTTP_USER_AGENT"])) { $is_lynx = 1; }
elseif (preg_match("/Gecko/", $_SERVER["HTTP_USER_AGENT"])) { $is_gecko = 1; }
elseif ((preg_match("/MSIE/", $_SERVER["HTTP_USER_AGENT"])) && (preg_match('/Win/', $_SERVER['HTTP_USER_AGENT']))) { $is_winIE = 1; } 
elseif ((preg_match("/MSIE/", $_SERVER["HTTP_USER_AGENT"])) && (preg_match('/Mac/', $_SERVER['HTTP_USER_AGENT']))) { $is_macIE = 1; }
elseif (preg_match("/Opera/", $_SERVER["HTTP_USER_AGENT"])) { $is_opera = 1; }
elseif ((preg_match("/Nav/", $_SERVER["HTTP_USER_AGENT"]) ) || (preg_match('/Mozilla\/4\./', $_SERVER['HTTP_USER_AGENT']))) {   $is_NS4 = 1;
}
$is_IE    = (($is_macIE) || ($is_winIE));

// Server detection
$is_apache = ( strstr($_SERVER['SERVER_SOFTWARE'], 'Apache') || strstr($_SERVER['SERVER_SOFTWARE'], 'LiteSpeed') ) ? 1 : 0;
$is_IIS = strstr($_SERVER['SERVER_SOFTWARE'], 'Microsoft-IIS') ? 1 : 0;

// On OS X Server, $_SERVER['REMOTE_ADDR'] is the server's address. Workaround this 
// by using $_SERVER['HTTP_PC_REMOTE_ADDR'], which *is* the remote address.
if ( isset($_SERVER['HTTP_PC_REMOTE_ADDR']) )
   $_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_PC_REMOTE_ADDR'];



// Checks the string for basic email structure
function is_email($email) 
{
   $chars = "/^([a-z0-9+_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.)+[a-z]{2,6}\$/i";
   if(strstr($email, "@") AND strstr($email, ".")) 
   {
      if (preg_match($chars, $email)) return true;
      else return false;
   } 
   else 
   {
      return false;
   }
}


// Cookie safe redirect.  Works around IIS Set-Cookie bug.
// http://support.microsoft.com/kb/q176113/
function wp_redirect($location, $status = 302) 
{
   global $is_IIS;

   $location = preg_replace("|[^a-z0-9-~+_.?#=&;,/:%]|i", "", $location);
   $strip = array("%0d", "%0a");
   $location = str_replace($strip, "", $location);
   if ($is_IIS) header("Refresh: 0;url=$location");
   else 
   {
     status_header($status); // This causes problems on IIS
     header("Location: $location");
   }
}




if ( !function_exists('auth_redirect') ) :
function auth_redirect() {
   // Checks if a user is logged in, if not redirects them to the login page
   if ( (!empty($_COOKIE[USER_COOKIE]) && 
      !wp_login($_COOKIE[USER_COOKIE], $_COOKIE[PASS_COOKIE], true)) ||
       (empty($_COOKIE[USER_COOKIE])) ) {
     nocache_headers();
   
     wp_redirect(get_settings('siteurl') . '/wp-login.php?redirect_to=' . urlencode($_SERVER['REQUEST_URI']));
     exit();
   }
}
endif;





// Round time down to the nearest resolution
function round_t_down($t, $resolution, $am7) 
{
   return (int)$t - (int)abs(((int)$t-(int)$am7) % $resolution);
}

// Round time up to the nearest resolution
function round_t_up($t, $resolution, $am7) 
{
   if (($t-$am7) % $resolution != 0)
   {
     return $t + $resolution - abs(((int)$t-(int) $am7) % $resolution);
   }
   else
   {
     return $t;
   }
}

   
   


// Returns true if given string seems like it is UTF8-encoded
function seems_utf8($Str) 
{ 
   for ($i=0; $i<strlen($Str); $i++) 
   {
      if (ord($Str[$i]) < 0x80) continue; // 0bbbbbbb
      elseif ((ord($Str[$i]) & 0xE0) == 0xC0) $n=1; // 110bbbbb
      elseif ((ord($Str[$i]) & 0xF0) == 0xE0) $n=2; // 1110bbbb
      elseif ((ord($Str[$i]) & 0xF8) == 0xF0) $n=3; // 11110bbb
      elseif ((ord($Str[$i]) & 0xFC) == 0xF8) $n=4; // 111110bb
      elseif ((ord($Str[$i]) & 0xFE) == 0xFC) $n=5; // 1111110b
      else return false; // Does not match any model
      for ($j=0; $j<$n; $j++) // n bytes matching 10bbbbbb follow ? 
      { 
         if ((++$i == strlen($Str)) || ((ord($Str[$i]) & 0xC0) != 0x80))
         return false;
      }
   }
   return true;
}    
   
   
   

/* =============================================================================    
================================================================================    
======================== FUNCTIONS FROM WORDPRESS ==============================    
================================================================================    
============================================================================= */    




function maybe_unserialize($original) {
   if ( is_serialized($original) ) // don't attempt to unserialize data that wasn't serialized going in
     if ( false !== $gm = @ unserialize($original) )
      return $gm;
   return $original;
}

function maybe_serialize($data) {
   if ( is_string($data) )
     $data = trim($data);
   elseif ( is_array($data) || is_object($data) )
     return serialize($data);
   if ( is_serialized($data) )
     return serialize($data);
   return $data;
}

function is_serialized($data) {
   if ( !is_string($data) ) // if it isn't a string, it isn't serialized
     return false;
   $data = trim($data);
   if ( preg_match("/^[adobis]:[0-9]+:.*[;}]/si",$data) ) // this should fetch all legitimately serialized data
     return true;
   return false;
}

function is_serialized_string($data) {
   if ( !is_string($data) ) // if it isn't a string, it isn't a serialized string
     return false;
   $data = trim($data);
   if ( preg_match("/^s:[0-9]+:.*[;}]/si",$data) ) // this should fetch all serialized strings
     return true;
   return false;
}

function gzip_compression() {
   if ( !get_settings('gzipcompression') ) return false;

   if ( extension_loaded('zlib') ) {
     ob_start('ob_gzhandler');
   }
}

function debug_fopen($filename, $mode) {
   global $debug;
   if ( $debug == 1 ) {
     $fp = fopen($filename, $mode);
     return $fp;
   } else {
     return false;
   }
}

function debug_fwrite($fp, $string) {
   global $debug;
   if ( $debug == 1 ) {
     fwrite($fp, $string);
   }
}

function debug_fclose($fp) {
   global $debug;
   if ( $debug == 1 ) {
     fclose($fp);
   }
}


function wp_get_http_headers( $url, $red = 1 ) {
   global $wp_version;
   @set_time_limit( 60 );

   if ( $red > 5 )
     return false;

   $parts = parse_url( $url );
   $file = $parts['path'] . ($parts['query'] ? '?'.$parts['query'] : '');
   $host = $parts['host'];
   if ( !isset( $parts['port'] ) )
     $parts['port'] = 80;

   $head = "HEAD $file HTTP/1.1\r\nHOST: $host\r\nUser-Agent: WordPress/" . $wp_version . "\r\n\r\n";

   $fp = @fsockopen($host, $parts['port'], $err_num, $err_msg, 3);
   if ( !$fp )
     return false;

   $response = '';
   fputs( $fp, $head );
   while ( !feof( $fp ) && strpos( $response, "\r\n\r\n" ) == false )
     $response .= fgets( $fp, 2048 );
   fclose( $fp );
   preg_match_all('/(.*?): (.*)\r/', $response, $matches);
   $count = count($matches[1]);
   for ( $i = 0; $i < $count; $i++) {
     $key = strtolower($matches[1][$i]);
     $headers["$key"] = $matches[2][$i];
   }

   preg_match('/.*([0-9]{3}).*/', $response, $return);
   $headers['response'] = $return[1]; // HTTP response code eg 204, 200, 404

   $code = $headers['response'];
   if ( ('302' == $code || '301' == $code) && isset($headers['location']) )
     return wp_get_http_headers( $headers['location'], ++$red );

   return $headers;
}


function add_magic_quotes($array) {
   foreach ($array as $k => $v) {
     if ( is_array($v) ) {
      $array[$k] = add_magic_quotes($v);
     } else {
      $array[$k] = escape($v);
     }
   }
   return $array;
}






?>