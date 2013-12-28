<?php
/* ========================== FILE INFORMATION =================================
phxEventManager :: nav-admin.php

Navigation bar with administrative tabs.
============================================================================= */

unset($nav);
$nav = "";

if (!pem_cache_isset("current_navigation")) pem_cache_set("current_navigation", "events");

// echo "test: " . pem_cache_get("current_navigation") . "<br />";

if (isset($did))
{
   $nav .= '<li><a href="' . $pem_url . 'view.php?did=' . $did . '" title="' . __("Back to Event") . '"><span>' . __("Back to Event") . '</span></a></li>' . "\n";
}
if (pem_user_authorized(array(
   "Internal Calendar" => array("Edit Own", "Edit Others", "Edit All", "Approve Own", "Approve Others", "Approve All", "Delete Own", "Delete Others", "Delete All"),
   "External Calendar" => array("Edit Own", "Edit Others", "Edit All", "Approve Own", "Approve Others", "Approve All", "Delete Own", "Delete Others", "Delete All"),
   "Internal Side Box" => array("Edit Own", "Edit Others", "Edit All", "Approve Own", "Approve Others", "Approve All", "Delete Own", "Delete Others", "Delete All"),
   "External Side Box" => array("Edit Own", "Edit Others", "Edit All", "Approve Own", "Approve Others", "Approve All", "Delete Own", "Delete Others", "Delete All"),
)))
{
   $nav .= '<li><a href="' . $pem_url . 'pem-admin/?n=events" title="' . __("Events") . '"';
   if (pem_cache_get("current_navigation") == "events") $nav .= ' class="on"';
   $nav .= '><span>' . __("Events") . '</span></a></li>' . "\n";
}
if (pem_user_authorized("Reports"))
{
   $nav .= '<li><a href="' . $pem_url . 'pem-admin/?n=reports" title="' . __("Reports") . '"';
   if (pem_cache_get("current_navigation") == "reports") $nav .= ' class="on"';
   $nav .= '><span>' . __("Reports") . '</span></a></li>' . "\n";
}
if (pem_user_authorized("Statistics"))
{
   $nav .= '<li><a href="' . $pem_url . 'pem-admin/?n=statistics" title="' . __("Statistics") . '"';
   if (pem_cache_get("current_navigation") == "statistics") $nav .= ' class="on"';
   $nav .= '><span>' . __("Statistics") . '</span></a></li>' . "\n";
}
if (pem_user_authorized("Meta Data"))
{
   $nav .= '<li><a href="' . $pem_url . 'pem-admin/?n=meta" title="' . __("Meta") . '"';
   if (pem_cache_get("current_navigation") == "meta") $nav .= ' class="on"';
   $nav .= '><span>' . __("Meta") . '</span></a></li>' . "\n";
}
if (pem_user_authorized("Users"))
{
   $nav .= '<li><a href="' . $pem_url . 'pem-admin/?n=users" title="' . __("Users") . '"';
   if (pem_cache_get("current_navigation") == "users") $nav .= ' class="on"';
   $nav .= '><span>' . __("Users") . '</span></a></li>' . "\n";
}
if (pem_user_authorized("Admin"))
{
   $nav .= '<li><a href="' . $pem_url . 'pem-admin/?n=backend" title="' . __("Backend") . '"';
   if (pem_cache_get("current_navigation") == "backend") $nav .= ' class="on"';
   $nav .= '><span>' . __("Backend") . '</span></a></li>' . "\n";
}
if (!empty($nav))
{
   echo '<div id="navigation">' . "\n";
   echo '<h2>' . __("Administration") . '</h2>' . "\n";
   echo '<ul>' . "\n";
   echo $nav;
   echo '</ul>' . "\n";
   echo '</div>' . "\n";
}


/*

switch (true)
{
case (pem_cache_get("current_navigation") == "events"):
   $page_access_requirement = array("Manage Internal Events", "Manage External Events", "Manage Internal Unscheduled", "Manage External Unscheduled", "Manage Registrations");
   break;
case (pem_cache_get("current_navigation") == "reports"):
   $page_access_requirement = "Manage Reports";
   break;
case (pem_cache_get("current_navigation") == "statistics"):
   $page_access_requirement = "Manage Statistics";
   break;
case (pem_cache_get("current_navigation") == "meta"):
   $page_access_requirement = "Manage Meta";
   break;
case (pem_cache_get("current_navigation") == "users"):
   $page_access_requirement = "Manage Users";
   break;
case (pem_cache_get("current_navigation") == "backend"):
   $page_access_requirement = "Admin";
   break;
}


echo '<ul class="pem-tab">' . "\n";
echo '<li';
if (pem_cache_get("current_navigation") == "events") echo ' class="selected"';
echo '><a href="' . $PHP_SELF . '?n=events">' . __("Events") . '</a></li>' . "\n";
echo '<li';
if (pem_cache_get("current_navigation") == "reports") echo ' class="selected"';
echo '><a href="' . $PHP_SELF . '?n=reports">' . __("Reports") . '</a></li>' . "\n";
echo '<li';
if (pem_cache_get("current_navigation") == "statistics") echo ' class="selected"';
echo '><a href="' . $PHP_SELF . '?n=statistics">' . __("Statistics") . '</a></li>' . "\n";
echo '<li';
if (pem_cache_get("current_navigation") == "dynamic") echo ' class="selected"';
echo '><a href="' . $PHP_SELF . '?n=dynamic">' . __("Dynamic Content") . '</a></li>' . "\n";
echo '<li';
if (pem_cache_get("current_navigation") == "users") echo ' class="selected"';
echo '><a href="' . $PHP_SELF . '?n=users">' . __("Users") . '</a></li>' . "\n";
echo '<li';
if (pem_cache_get("current_navigation") == "settings") echo ' class="selected"';
echo '><a href="' . $PHP_SELF . '?n=settings">' . __("Settings") . '</a></li>' . "\n";
echo '<li';
if (pem_cache_get("current_navigation") == "backend") echo ' class="selected"';
echo '><a href="' . $PHP_SELF . '?n=backend">' . __("Backend") . '</a></li>' . "\n";
echo '</ul>' . "\n";
*/


?>