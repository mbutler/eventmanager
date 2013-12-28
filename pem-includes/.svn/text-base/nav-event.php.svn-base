<?php
/* ========================== FILE INFORMATION =================================
phxEventManager :: nav-event.php

Navigation bar with view tabs; used to browse event infromation.
============================================================================= */

unset($nav);
unset($view);
$nav = "";
$view = "";

if (isset($did)) $eventid = "&did=$did";
elseif (isset($eid)) $eventid = "&eid=$eid";
else $eventid = "";

$nav .= '<li><a href="' . $pem_url . '?e=event" title="' . __("Back to Calendar") . '"';
$nav .= '><span>' . __("Back to Calendar") . '</span></a></li>' . "\n";

if (pem_cache_get("current_eview") == "search")
{
   $nav .= '<li><a href="' . $pem_url . 'search.php" title="' . __("Search") . '" class="on"><span>' . __("Search") . '</span></a></li>' . "\n";
   echo '<div id="navigation">' . "\n";
   echo '<h2>' . __("Event Manager Search") . '</h2>' . "\n";
   echo '<ul>' . "\n";
   echo $nav;
   echo '</ul>' . "\n";
   echo '</div>' . "\n";
}
else
{
   $nav .= '<li><a href="' . $pem_url . 'view.php?e=event' . $eventid . '" title="' . __("This Event") . '"';
   if (pem_cache_get("current_eview") == "event") $nav .= ' class="on"';
   $nav .= '><span>' . __("This Event") . '</span></a></li>' . "\n";

   if (pem_user_authorized(array(
      "Internal Calendar" => array("Edit Own", "Edit Others", "Edit All"),
      "External Calendar" => array("Edit Own", "Edit Others", "Edit All"),
      "Internal Side Box" => array("Edit Own", "Edit Others", "Edit All"),
      "External Side Box" => array("Edit Own", "Edit Others", "Edit All"),
   )))
   {
      $nav .= '<li><a href="' . $pem_url . 'view.php?e=edit' . $eventid . '" title="' . __("Edit") . '"';
      if (pem_cache_get("current_eview") == "edit") $nav .= ' class="on"';
      $nav .= '><span>' . __("Edit") . '</span></a></li>' . "\n";
   }

   if ($hasdates)
   {
      $nav .= '<li><a href="' . $pem_url . 'view.php?e=dates' . $eventid . '" title="' . __("Other Dates") . '"';
      if (pem_cache_get("current_eview") == "dates") $nav .= ' class="on"';
      $nav .= '><span>' . __("Other Dates") . '</span></a></li>' . "\n";
   }

   if (pem_user_authorized(array("Internal Calendar" => "Add", "External Calendar" => "Add", "Internal Side Box" => "Add", "External Side Box" => "Add")))
   {
      $nav .= '<li><a href="' . $pem_url . 'view.php?e=add" title="' . __("Add Date") . '"';
      if (pem_cache_get("current_eview") == "add") $nav .= ' class="on"';
      $nav .= '><span>' . __("Add Date") . '</span></a></li>' . "\n";
   }

   if ($hasregs AND (pem_user_authorized(array("Registrations" => array("View", "Edit", "Delete"))) OR pem_cache_get("current_eview") == "regs"))
   {
      $nav .= '<li><a href="' . $pem_url . 'view.php?e=regs' . $eventid . '" title="' . __("Registrations") . '"';
      if (pem_cache_get("current_eview") == "regs") $nav .= ' class="on"';
      $nav .= '><span>' . __("Registration") . '</span></a></li>' . "\n";
   }

   if (!empty($nav))
   {
      echo '<div id="navigation">' . "\n";
      echo '<h2>' . __("View Event Information") . '</h2>' . "\n";
      echo '<ul>' . "\n";
      echo $nav;
      echo '</ul>' . "\n";
      echo '</div>' . "\n";
   }
}







?>