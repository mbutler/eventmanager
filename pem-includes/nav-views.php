<?php
/* ========================== FILE INFORMATION =================================
phxEventManager :: nav-views.php

Navigation bar with view tabs; used on common public calendar and list views.
============================================================================= */

unset($nav);
unset($view);
$nav = "";
$view = "";

$nav .= '<li><a href="' . $pem_url . '?v=day" title="' . __("Day") . '"';
if (pem_cache_get("current_view") == "day") $nav .= ' class="on"';
$nav .= '><span>' . __("Day") . '</span></a></li>' . "\n";

$nav .= '<li><a href="' . $pem_url . '?v=week" title="' . __("Week") . '"';
if (pem_cache_get("current_view") == "week") $nav .= ' class="on"';
$nav .= '><span>' . __("Week") . '</span></a></li>' . "\n";

$nav .= '<li><a href="' . $pem_url . '?v=month" title="' . __("Month") . '"';
if (pem_cache_get("current_view") == "month") $nav .= ' class="on"';
$nav .= '><span>' . __("Month") . '</span></a></li>' . "\n";

/*
$nav .= '<li><a href="' . $pem_url . '?v=year" title="' . __("Year") . '"';
if (pem_cache_get("current_view") == "year") $nav .= ' class="on"';
$nav .= '><span>' . __("Year") . '</span></a></li>' . "\n";
*/

if (pem_cache_get("current_format") == "list")
{
   $view .= '<div><a href="' . $pem_url . '?f=calendar" title="' . __("Calendar View") . '"';
   $view .= '><span>' . __("Calendar View") . '</span></a></div>' . "\n";
}
else
{
   $view .= '<div><a href="' . $pem_url . '?f=list" title="' . __("List View") . '"';
   $view .= '><span>' . __("List View") . '</span></a></div>' . "\n";
}


if (!isset($current_date))
{
   $current_year = pem_cache_get("current_year");
   $current_month = zeropad(pem_cache_get("current_month"), 2);
   $current_day = zeropad(pem_cache_get("current_day"), 2);
   $current_date = $current_year . "-" . $current_month . "-" . $current_day;
}
if (!isset($today_date))
{
   $today_year = pem_date('Y');
   $today_month = pem_date('m');
   $today_day = pem_date('j');
   $today_date = $current_year . "-" . $current_month . "-" . $current_day;
}

if (!empty($nav))
{
   echo '<div id="navigation">' . "\n";
   echo '<h2>' . __("Jump To:") . '</h2>' . "\n";
   echo '<div id="navdate">' . "\n";
   pem_form_begin(array("nameid" => "jumptoform", "action" => $PHP_SELF));
   pem_date_selector("jump_to_", array("default" => $current_date, "onchange" => "JumpTo('jump_to_', '" . $PHP_SELF . "')"));
   pem_form_end();
   echo '<a href="'  . $PHP_SELF . '?y=' . $today_year . '&amp;m=' . $today_month . '&amp;d=' . $today_day . '"><span>' . __("Today") . '</span></a>' . "\n";
   echo '</div>' . "\n";


   $current_filters_any = pem_cache_get("current_filters_any");
   if ($current_filters_any) echo '<div><a href="javascript:void(null);" onclick="xajax_clear_filters(); return false;"><span>' . __("Clear Filters") . '</span></a></div>' . "\n";
   echo '<div><a href="' . $pem_url . 'pem-admin/filter-view.php?KeepThis=true&TB_iframe=true&height=450&width=720" title="Filter View" class="thickbox"><span>' . __("Add Filter") . '</span></a></div>' . "\n";
   echo $view;
   echo '<ul>' . "\n";
   echo $nav;
   echo '</ul>' . "\n";

   echo '<div style="float:left; margin-left:10px;"><a href="' . $pem_url . 'add-event.php?t=scheduled" title="' . __("Book a Meeting Room") . '"';
   echo '><span>' . __("Book a Meeting Room") . '</span></a></div>' . "\n";


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