<?php
/* ========================== FILE INFORMATION =================================
phxEventManager :: pem-functions.php

Controlling file for functions distributed among other includes by function.
============================================================================= */
include_once ABSPATH . PEMINC . "/functions-compat.php"; // deprecated
include_once ABSPATH . PEMINC . "/functions-L10n.php";
include_once ABSPATH . PEMINC . "/functions-db.php";
include_once ABSPATH . PEMINC . "/functions-datetime.php";
include_once ABSPATH . PEMINC . "/functions-admin.php"; // potentially deprecated
include_once ABSPATH . PEMINC . "/functions-auth.php";

include_once ABSPATH . PEMINC . "/functions-file.php";
include_once ABSPATH . PEMINC . "/functions-automation.php";
include_once ABSPATH . PEMINC . "/functions-formatting.php";
include_once ABSPATH . PEMINC . "/functions-kses.php";
include_once ABSPATH . PEMINC . "/functions-query.php";
include_once ABSPATH . PEMINC . "/functions-mail.php";
include_once ABSPATH . PEMINC . "/class-user.php";


function nocache_headers()
{
   @ header("Pragma: no-cache");                       // HTTP 1.0
   @ header("Expires: Mon, 1 Jan 2001 12:00:00 GMT");  // Date in the past
   @ header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
   @ header("Cache-Control: no-cache, must-revalidate, max-age=0");
}

function status_header($header)
{
   if ($header == 200) $text = "OK";
   elseif ($header == 301) $text = "Moved Permanently";
   elseif ($header == 302) $text = "Moved Temporarily";
   elseif ($header == 304) $text = "Not Modified";
   elseif ($header == 404) $text = "Not Found";
   elseif ($header == 410) $text = "Gone";
   @header("HTTP/1.1 $header $text");
   @header("Status: $header $text");
}

function pem_mail($to, $subject, $message, $headers = "")
{
   if($headers == "")
   {
     $charset = (get_settings("content_charset"));
     $headers = "MIME-Version: 1.0\n" .
     "From: pem@" . preg_replace("#^www\.#", "", strtolower($_SERVER["SERVER_NAME"])) . "\n" .
     "Content-Type: text/plain; charset=\"" . $charset . "\"\n";
   }
   return @mail($to, $subject, $message, $headers);
}

function pem_die($message, $title = "")
{
   $pagetitle = (empty($title)) ? __("Error") : $title;
   $cache_charset = pem_cache_get("content_charset");
   header("Content-Type: text/html; charset=" . $cache_charset);
   echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' . "\n";
   echo '<html xmlns="http://www.w3.org/1999/xhtml">' . "\n";
   echo '<head>' . "\n";
   echo '<title>' . __("phxEventManager") . "&rsaquo;" . $pagetitle . '</title>' . "\n";
   // include "style.php";
   echo '</head>' . "\n";
   echo '<body>' . "\n";
   echo '<div id="header">' . "\n";
   echo '<div id="header-title"><a href="' . pem_cache_get("url_base") . '" title="' . pem_cache_get("pem_title") . '">';
   $title_img = pem_cache_get("title_img");
   if (!$title_img)
   {
      echo pem_cache_get("pem_title");
   }
   else
   {
      echo '<img src="' . pem_cache_get("url_base") . 'pem-images/' . $title_img . '" />';
   }
   echo '</a></div>' . "\n";
   echo '</div>' . "\n";  // END id="header"
   echo '<p>' . $message . '</p>' . "\n";
   echo '</body>' . "\n";
   echo '</html>';
   die();
}
















/*

// =======================================================================
// =======================================================================
// ======== NEEDS CONVERSION ======================
// =======================================================================
// =======================================================================

function get_profile($field, $user = false)
{
   global $wpdb;
   if (!$user)
     $user = $wpdb->escape($_COOKIE[USER_COOKIE]);
   return $wpdb->get_var("SELECT $field FROM $wpdb->users WHERE user_login = '$user'");
}

function user_pass_ok($user_login,$user_pass) {
   global $cache_userdata;
   if ( empty($cache_userdata[$user_login]) ) {
     $userdata = get_userdatabylogin($user_login);
   } else {
     $userdata = $cache_userdata[$user_login];
   }
   return (md5($user_pass) == $userdata->user_pass);
}



// Send a Trackback
function trackback($trackback_url, $title, $excerpt, $ID) {
   global $wpdb, $wp_version;

   if ( empty($trackback_url) )
     return;

   $title = urlencode($title);
   $excerpt = urlencode($excerpt);
   $blog_name = urlencode(get_settings('blogname'));
   $tb_url = $trackback_url;
   $url = urlencode(get_permalink($ID));
   $query_string = "title=$title&url=$url&blog_name=$blog_name&excerpt=$excerpt";
   $trackback_url = parse_url($trackback_url);
   $http_request = 'POST ' . $trackback_url['path'] . ($trackback_url['query'] ? '?'.$trackback_url['query'] : '') . " HTTP/1.0\r\n";
   $http_request .= 'Host: '.$trackback_url['host']."\r\n";
   $http_request .= 'Content-Type: application/x-www-form-urlencoded; charset='.get_settings('blog_charset')."\r\n";
   $http_request .= 'Content-Length: '.strlen($query_string)."\r\n";
   $http_request .= "User-Agent: WordPress/" . $wp_version;
   $http_request .= "\r\n\r\n";
   $http_request .= $query_string;
   if ( '' == $trackback_url['port'] )
     $trackback_url['port'] = 80;
   $fs = @fsockopen($trackback_url['host'], $trackback_url['port'], $errno, $errstr, 4);
   @fputs($fs, $http_request);
/#
   $debug_file = 'trackback.log';
   $fp = fopen($debug_file, 'a');
   fwrite($fp, "\n*****\nRequest:\n\n$http_request\n\nResponse:\n\n");
   while(!@feof($fs)) {
     fwrite($fp, @fgets($fs, 4096));
   }
   fwrite($fp, "\n\n");
   fclose($fp);
#/
   @fclose($fs);

   $tb_url = addslashes( $tb_url );
   $wpdb->query("UPDATE $wpdb->posts SET pinged = CONCAT(pinged, '\n', '$tb_url') WHERE ID = '$ID'");
   return $wpdb->query("UPDATE $wpdb->posts SET to_ping = TRIM(REPLACE(to_ping, '$tb_url', '')) WHERE ID = '$ID'");
}

function make_url_footnote($content) {
   preg_match_all('/<a(.+?)href=\"(.+?)\"(.*?)>(.+?)<\/a>/', $content, $matches);
   $j = 0;
   for ($i=0; $i<count($matches[0]); $i++) {
     $links_summary = (!$j) ? "\n" : $links_summary;
     $j++;
     $link_match = $matches[0][$i];
     $link_number = '['.($i+1).']';
     $link_url = $matches[2][$i];
     $link_text = $matches[4][$i];
     $content = str_replace($link_match, $link_text.' '.$link_number, $content);
     $link_url = ((strtolower(substr($link_url,0,7)) != 'http://') && (strtolower(substr($link_url,0,8)) != 'https://')) ? get_settings('home') . $link_url : $link_url;
     $links_summary .= "\n".$link_number.' '.$link_url;
   }
   $content = strip_tags($content);
   $content .= $links_summary;
   return $content;
}

function xmlrpc_getposttitle($content) {
   global $post_default_title;
   if ( preg_match('/<title>(.+?)<\/title>/is', $content, $matchtitle) ) {
     $post_title = $matchtitle[0];
     $post_title = preg_replace('/<title>/si', '', $post_title);
     $post_title = preg_replace('/<\/title>/si', '', $post_title);
   } else {
     $post_title = $post_default_title;
   }
   return $post_title;
}

function xmlrpc_getpostcategory($content) {
   global $post_default_category;
   if ( preg_match('/<category>(.+?)<\/category>/is', $content, $matchcat) ) {
     $post_category = trim($matchcat[1], ',');
     $post_category = explode(',', $post_category);
   } else {
     $post_category = $post_default_category;
   }
   return $post_category;
}

function xmlrpc_removepostdata($content) {
   $content = preg_replace('/<title>(.+?)<\/title>/si', '', $content);
   $content = preg_replace('/<category>(.+?)<\/category>/si', '', $content);
   $content = trim($content);
   return $content;
}




function wp_nonce_url($actionurl, $action = -1) {
   return wp_specialchars(add_query_arg('_wpnonce', wp_create_nonce($action), $actionurl));
}

function wp_nonce_field($action = -1) {
   echo '<input type="hidden" name="_wpnonce" value="' . wp_create_nonce($action) . '" />';
   wp_referer_field();
}

function wp_referer_field() {
   $ref = wp_specialchars($_SERVER['REQUEST_URI']);
   echo '<input type="hidden" name="_wp_http_referer" value="'. $ref . '" />';
   if ( wp_get_original_referer() ) {
     $original_ref = wp_specialchars(stripslashes(wp_get_original_referer()));
     echo '<input type="hidden" name="_wp_original_http_referer" value="'. $original_ref . '" />';
   }
}

function wp_original_referer_field() {
   echo '<input type="hidden" name="_wp_original_http_referer" value="' . wp_specialchars(stripslashes($_SERVER['REQUEST_URI'])) . '" />';
}

function wp_get_referer() {
   foreach ( array($_REQUEST['_wp_http_referer'], $_SERVER['HTTP_REFERER']) as $ref )
     if ( !empty($ref) )
      return $ref;
   return false;
}

function wp_get_original_referer() {
   if ( !empty($_REQUEST['_wp_original_http_referer']) )
     return $_REQUEST['_wp_original_http_referer'];
   return false;
}

function wp_explain_nonce($action) {
   if ( $action !== -1 && preg_match('/([a-z]+)-([a-z]+)(_(.+))?/', $action, $matches) ) {
     $verb = $matches[1];
     $noun = $matches[2];

     $trans = array();
     $trans['update']['attachment'] = array(__('Are you sure you want to edit this attachment: &quot;%s&quot;?'), 'get_the_title');

     $trans['add']['category'] = array(__('Are you sure you want to add this category?'), false);
     $trans['delete']['category'] = array(__('Are you sure you want to delete this category: &quot;%s&quot;?'), 'get_catname');
     $trans['update']['category'] = array(__('Are you sure you want to edit this category: &quot;%s&quot;?'), 'get_catname');

     $trans['delete']['comment'] = array(__('Are you sure you want to delete this comment: &quot;%s&quot;?'), 'use_id');
     $trans['unapprove']['comment'] = array(__('Are you sure you want to unapprove this comment: &quot;%s&quot;?'), 'use_id');
     $trans['approve']['comment'] = array(__('Are you sure you want to approve this comment: &quot;%s&quot;?'), 'use_id');
     $trans['update']['comment'] = array(__('Are you sure you want to edit this comment: &quot;%s&quot;?'), 'use_id');
     $trans['bulk']['comments'] = array(__('Are you sure you want to bulk modify comments?'), false);
     $trans['moderate']['comments'] = array(__('Are you sure you want to moderate comments?'), false);

     $trans['add']['bookmark'] = array(__('Are you sure you want to add this bookmark?'), false);
     $trans['delete']['bookmark'] = array(__('Are you sure you want to delete this bookmark: &quot;%s&quot;?'), 'use_id');
     $trans['update']['bookmark'] = array(__('Are you sure you want to edit this bookmark: &quot;%s&quot;?'), 'use_id');
     $trans['bulk']['bookmarks'] = array(__('Are you sure you want to bulk modify bookmarks?'), false);

     $trans['add']['page'] = array(__('Are you sure you want to add this page?'), false);
     $trans['delete']['page'] = array(__('Are you sure you want to delete this page: &quot;%s&quot;?'), 'get_the_title');
     $trans['update']['page'] = array(__('Are you sure you want to edit this page: &quot;%s&quot;?'), 'get_the_title');

     $trans['edit']['plugin'] = array(__('Are you sure you want to edit this plugin file: &quot;%s&quot;?'), 'use_id');
     $trans['activate']['plugin'] = array(__('Are you sure you want to activate this plugin: &quot;%s&quot;?'), 'use_id');
     $trans['deactivate']['plugin'] = array(__('Are you sure you want to deactivate this plugin: &quot;%s&quot;?'), 'use_id');

     $trans['add']['post'] = array(__('Are you sure you want to add this post?'), false);
     $trans['delete']['post'] = array(__('Are you sure you want to delete this post: &quot;%s&quot;?'), 'get_the_title');
     $trans['update']['post'] = array(__('Are you sure you want to edit this post: &quot;%s&quot;?'), 'get_the_title');

     $trans['add']['user'] = array(__('Are you sure you want to add this user?'), false);
     $trans['delete']['users'] = array(__('Are you sure you want to delete users?'), false);
     $trans['bulk']['users'] = array(__('Are you sure you want to bulk modify users?'), false);
     $trans['update']['user'] = array(__('Are you sure you want to edit this user: &quot;%s&quot;?'), 'get_author_name');
     $trans['update']['profile'] = array(__('Are you sure you want to modify the profile for: &quot;%s&quot;?'), 'get_author_name');

     $trans['update']['options'] = array(__('Are you sure you want to edit your settings?'), false);
     $trans['update']['permalink'] = array(__('Are you sure you want to change your permalink structure to: %s?'), 'use_id');
     $trans['edit']['file'] = array(__('Are you sure you want to edit this file: &quot;%s&quot;?'), 'use_id');
     $trans['edit']['theme'] = array(__('Are you sure you want to edit this theme file: &quot;%s&quot;?'), 'use_id');
     $trans['switch']['theme'] = array(__('Are you sure you want to switch to this theme: &quot;%s&quot;?'), 'use_id');

     if ( isset($trans[$verb][$noun]) ) {
      if ( !empty($trans[$verb][$noun][1]) ) {
      $lookup = $trans[$verb][$noun][1];
      $object = $matches[4];
      if ( 'use_id' != $lookup )
         $object = call_user_func($lookup, $object);
      return sprintf($trans[$verb][$noun][0], $object);
      } else {
      return $trans[$verb][$noun][0];
      }
     }
   }

   return __('Are you sure you want to do this?');
}

*/



?>