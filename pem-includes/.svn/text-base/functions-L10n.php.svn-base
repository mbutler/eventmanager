<?php
if (!defined("PEMINC")) define("PEMINC", "pem-includes");
if (defined("PEMLANG") AND constant("PEMLANG") != "")
{
   include_once ABSPATH . PEMINC . "/class-streams.php";
   include_once ABSPATH . PEMINC . "/class-gettext.php";
}

function get_locale()
{
   global $locale;

   if (isset($locale)) return $locale;
   if (defined("PEMLANG")) $locale = PEMLANG; // PEMLANG is defined in pem-config.
   if (empty($locale)) $locale = "en_US";
   return $locale;
}

// Return a translated string.    
function __($text, $domain = "default") 
{
   global $l10n;
   
   if (isset($l10n[$domain]))
      return $l10n[$domain]->translate($text);
   else
      return $text;
}

// Echo a translated string.
function _e($text, $domain = "default") 
{
   global $l10n;

   if (isset($l10n[$domain]))
      echo $l10n[$domain]->translate($text);
   else
      echo $text;
}

// Return the plural form.
function __ngettext($single, $plural, $number, $domain = "default") 
{
   global $l10n;

   if (isset($l10n[$domain])) 
   {
      return $l10n[$domain]->ngettext($single, $plural, $number);
   }
   else 
   {
      if ($number != 1) return $plural;
      else return $single;
   }
}

function load_textdomain($domain, $mofile) 
{
   global $l10n;

   if (isset($l10n[$domain])) return;
   if (is_readable($mofile))
      $input = new CachedFileReader($mofile);
   else
      return;
   $l10n[$domain] = new gettext_reader($input);
}

function load_default_textdomain() 
{
   global $l10n;

   $locale = get_locale();
   $mofile = ABSPATH . "/pem-language/$locale.mo";
   load_textdomain("default", $mofile);
}
?>