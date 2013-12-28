<?php
/* ========================== FILE INFORMATION ================================= 
phxEventManager :: functions-admin.php

Functions that manage or automate global administrative settings.
============================================================================= */



// returns single value from settings table
function pem_get_setting($setting)
{
   $ret = pem_get_row("name", $setting, "settings");
   return unserialize($ret["value"]);
} // END pem_get_setting

// returns keyed array from settings table
function pem_get_settings()
{
   $res = pem_get_rows("settings");
   for ($i = 0; $i < count($res); $i++) 
   {
      $ret[$res[$i]["name"]] = unserialize($res[$i]["value"]);
   }
   return $ret;
} // END pem_get_settings




// updates values in the settings table based on the keyed input pairs
// $settings is an array with "field => value"
function pem_update_settings($settings)
{
   $setting_keys = array_keys($settings);
   for ($i = 0; $i < count($setting_keys); $i++)
   {
      $data = array("value" => serialize($settings[$setting_keys[$i]]));
      $where = array("name" => $setting_keys[$i]);
      pem_update_row("settings", $data, $where);
   }
} // END pem_update_settings

// returns keyed array from settings table
function pem_get_themes()
{
   $list = pem_get_directory_list(ABSPATH . "pem-themes/");
   if (!empty($list)) return $list;
   return false;
} // END pem_get_themes

// returns keyed array from settings table
function pem_get_popups()
{
   $list = pem_get_file_list(ABSPATH . "pem-content/");
   for ($i = 0; $i < count($list); $i++)
   {
      if ($list[$i] != "index.php" AND 
          substr($list[$i], "-3") == "php" OR 
          substr($list[$i], "-4") == "html" OR 
          substr($list[$i], "-3") == "htm" OR 
          substr($list[$i], "-3") == "txt" OR 
          substr($list[$i], "-5") == "xhtml" OR 
          substr($list[$i], "-3") == "PHP" OR 
          substr($list[$i], "-4") == "HTML" OR 
          substr($list[$i], "-3") == "HTM" OR 
          substr($list[$i], "-3") == "TXT" OR  
          substr($list[$i], "-5") == "XHTML") 
       {
          $goodlist[] = $list[$i];
       }   
   }
   if (!empty($goodlist)) return $goodlist;
   return false;
} // END pem_get_popups





/* =============================================================================    
================================================================================    
======================== EXAMPLE FUNCTIONS FROM WORDPRESS ======================    
================================================================================    
============================================================================= */    



function form_option($option) {
   echo wp_specialchars( get_option($option), 1 );
}

function get_alloptions() {
   global $wpdb, $wp_queries;
   $wpdb->hide_errors();
   if ( !$options = $wpdb->get_results("SELECT option_name, option_value FROM $wpdb->options WHERE autoload = 'yes'") ) {
     $options = $wpdb->get_results("SELECT option_name, option_value FROM $wpdb->options");
   }
   $wpdb->show_errors();

   foreach ($options as $option) {
     // "When trying to design a foolproof system,
     //  never underestimate the ingenuity of the fools :)" -- Dougal
     if ( 'siteurl' == $option->option_name )
      $option->option_value = preg_replace('|/+$|', '', $option->option_value);
     if ( 'home' == $option->option_name )
      $option->option_value = preg_replace('|/+$|', '', $option->option_value);
     if ( 'category_base' == $option->option_name )
      $option->option_value = preg_replace('|/+$|', '', $option->option_value);
     $value = maybe_unserialize($option->option_value);
     $all_options->{$option->option_name} = apply_filters('pre_option_' . $option->option_name, $value);
   }
   return apply_filters('all_options', $all_options);
}

function update_option($option_name, $newvalue) {
   global $wpdb;

   if ( is_string($newvalue) )
     $newvalue = trim($newvalue);

   // If the new and old values are the same, no need to update.
   $oldvalue = get_option($option_name);
   if ( $newvalue == $oldvalue ) {
     return false;
   }

   if ( false === $oldvalue ) {
     add_option($option_name, $newvalue);
     return true;
   }

   $_newvalue = $newvalue;
   $newvalue = maybe_serialize($newvalue);

   wp_cache_set($option_name, $newvalue, 'options');

   $newvalue = $wpdb->escape($newvalue);
   $option_name = $wpdb->escape($option_name);
   $wpdb->query("UPDATE $wpdb->options SET option_value = '$newvalue' WHERE option_name = '$option_name'");
   if ( $wpdb->rows_affected == 1 ) {
     do_action("update_option_{$option_name}", array('old'=>$oldvalue, 'new'=>$_newvalue));
     return true;
   }
   return false;
}


?>