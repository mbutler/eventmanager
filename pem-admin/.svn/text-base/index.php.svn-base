<?php
/* ========================== FILE INFORMATION =================================
phxEventManager :: pem-admin/index.php

This file provides navigation to all administrative tasks for the application.
============================================================================= */

$pagetitle = "Administration";
$navigation = "administration";
include_once "../pem-includes/header.php";

if (!strstr($PHP_SELF, "install.php"))
{
   // Used to guarantee unique hash cookies
   $cookiehash = md5(pem_cache_get("url_base"));
   define("COOKIEHASH", $cookiehash);

   if (!defined("LOGIN_COOKIE"))
      define("LOGIN_COOKIE", "phxeventmanagerlogin". COOKIEHASH);
   if (!defined("PASS_COOKIE"))
      define("PASS_COOKIE", "phxeventmanagerpass". COOKIEHASH);
   if (!defined("SITECOOKIEPATH"))
      define("SITECOOKIEPATH", preg_replace("|https?://[^/]+|i", "", pem_cache_get("url_base") . "/" ) );
   if (!defined("COOKIE_DOMAIN"))
      define("COOKIE_DOMAIN", false);
}

// $check_resources = array("Reports", "Manage Reports");
// if(pem_user_authorized($check_resources, true))

pem_user_required("Manage Any");

switch(true)
{
   case (pem_cache_get("current_navigation") == "events"):
   // $access_requirement = array("Manage Internal Events", "Manage External Events", "Manage Internal Side Box", "Manage External Side Box");

      if (pem_user_authorized(array(
      "Internal Calendar" => array("Edit Own", "Edit Others", "Edit All", "Approve Own", "Approve Others", "Approve All", "Delete Own", "Delete Others", "Delete All"),
      "External Calendar" => array("Edit Own", "Edit Others", "Edit All", "Approve Own", "Approve Others", "Approve All", "Delete Own", "Delete Others", "Delete All"),
      )))
      {
         pem_fieldset_begin(__("Calendar Events"));
         echo '<p>' . __('Calendar events display in both calendar and list views and are the "standard" event used for most submissions.') . '</p>' . "\n";
         echo '<ul class="bullets">' . "\n";
         if (pem_user_authorized(array("Internal Calendar" => "Add", "External Calendar" => "Add")))
         {
            echo '<li><a href="../add-event.php?t=scheduled">' . __("Add New Event") . '</a> - ' . __("Submit a new item for review") . '</li>' . "\n";
         }
         if (pem_user_authorized(array("Internal Calendar" => array("Approve Own", "Approve Others", "Approve All"), "External Calendar" => array("Approve Own", "Approve Others", "Approve All"))))
         {
            echo '<li><a href="approve-event.php?t=scheduled">' . __("Approve Queued Events") . '</a> - ' . __("All new events must be approved to go live") . '</li>' . "\n";
         }
         // echo '<li><a href="missing-dates.php?t=scheduled">' . __("List Events Missing Dates") . '</a> - ' . __("Add dates to these entries to make them active") . '</li>' . "\n";
         // echo '<li><a href="multiple-dates.php?t=scheduled">' . __("List Events with Multiple Dates") . '</a> - ' . __("Recurring entries with possibly unique information for each date") . '</li>' . "\n";
         // if (pem_user_authorized("Manage Registrations")) echo '<li><a href="list-reg-events.php">' . __("Manage Registrations") . '</a></li>' . "\n";
         echo '<li>' . __("Search for Opening") . '</a></li>' . "\n";
         echo '</ul>' . "\n";
         pem_fieldset_end();
      }
      if (pem_user_authorized(array(
      "Internal Side Box" => array("Edit Own", "Edit Others", "Edit All", "Approve Own", "Approve Others", "Approve All", "Delete Own", "Delete Others", "Delete All"),
      "External Side Box" => array("Edit Own", "Edit Others", "Edit All", "Approve Own", "Approve Others", "Approve All", "Delete Own", "Delete Others", "Delete All"),
      )))
      {
         pem_fieldset_begin(__("Side Box Events"));
         echo '<p>' . __("Side Box events appear as a list next to a view's main calendar or listing.  They are used for highlighted displays or programs that occur over multiple days and are not meant to be shown with other calendar content.") . '</p>' . "\n";
         echo '<ul class="bullets">' . "\n";
         if (pem_user_authorized(array("Internal Side Box" => "Add", "External Side Box" => "Add")))
         {
            echo '<li><a href="../add-event.php?t=unscheduled">' . __("Add New Event") . '</a> - ' . __("Submit a new item for review") . '</li>' . "\n";
         }
         if (pem_user_authorized(array("Internal Side Box" => array("Approve Own", "Approve Others", "Approve All"), "External Side Box" => array("Approve Own", "Approve Others", "Approve All"))))
         {
            echo '<li><a href="approve-event.php?t=unscheduled">' . __("Approve Queued  Events") . '</a> - ' . __("All new events must be approved to go live") . '</li>' . "\n";
         }
         // echo '<li><a href="missing-dates.php?t=unscheduled">' . __("List Events Missing Dates") . '</a> - ' . __("Add dates to these entries to make them active") . '</li>' . "\n";
         // echo '<li><a href="multiple-dates.php?t=unscheduled">' . __("List Events with Multiple Dates") . '</a> - ' . __("Recurring entries with possibly unique information for each date") . '</li>' . "\n";
         // if (pem_user_authorized("Manage Registrations")) echo '<li><a href="list-reg-events.php">' . __("Manage Registrations") . '</a></li>' . "\n";
         echo '<li>' . __("Search for Opening") . '</a></li>' . "\n";
         echo '</ul>' . "\n";
         pem_fieldset_end();

      }
      if (pem_user_authorized(array(
      "Internal Calendar" => array("Edit Own", "Edit Others", "Edit All", "Approve Own", "Approve Others", "Approve All", "Delete Own", "Delete Others", "Delete All"),
      "External Calendar" => array("Edit Own", "Edit Others", "Edit All", "Approve Own", "Approve Others", "Approve All", "Delete Own", "Delete Others", "Delete All"),
      )))
      {
         pem_fieldset_begin(__("All-Day Events"));
         echo '<p>' . __("All-Day events occur over one or more days and display in calendars and listings.  They are primarily designed for holiday and special program labels that don't conflict with events or all-day scheduling blackouts and closings that need to block new submissions.  All-day events are otherwise identical to calendar events.") . '</p>' . "\n";
         echo '<ul class="bullets">' . "\n";
         if (pem_user_authorized(array("Internal Calendar" => "Add", "External Calendar" => "Add")))
         {
            echo '<li><a href="../add-event.php?t=allday">' . __("Add New Event") . '</a> - ' . __("Submit a new item for review") . '</li>' . "\n";
         }
         if (pem_user_authorized(array("Internal Calendar" => array("Approve Own", "Approve Others", "Approve All"), "External Calendar" => array("Approve Own", "Approve Others", "Approve All"))))
         {
            echo '<li><a href="approve-event.php?t=allday">' . __("Approve Queued Events") . '</a> - ' . __("All new events must be approved to go live") . '</li>' . "\n";
         }
         //    echo '<li><a href="multiple-dates.php?t=allday">' . __("List Events with Multiple Dates") . '</a> - ' . __("Recurring entries with possibly unique information for each date") . '</li>' . "\n";
         // echo '<li><a href="multiple-dates.php?t=scheduled">' . __("List Events with Multiple Dates") . '</a> - ' . __("Recurring entries with possibly unique information for each date") . '</li>' . "\n";
         // if (pem_user_authorized("Manage Registrations")) echo '<li><a href="list-reg-events.php">' . __("Manage Registrations") . '</a></li>' . "\n";
         echo '<li>' . __("Search for Opening") . '</a></li>' . "\n";
         echo '</ul>' . "\n";
         pem_fieldset_end();
      }

      // print_r($_SESSION["pem_cache"]);
      break;
   case (pem_cache_get("current_navigation") == "reports"):
      pem_fieldset_begin(__("Standard Reports"));
      echo '<p>' . __("These built-in report profiles provide commonly desired information.  Date range limits are set within a report's view.") . '</p>' . "\n";
      echo '<ul class="bullets">' . "\n";
      echo '<li><a href="reports.php?r=list">' . __("Events by Date") . '</a></li>' . "\n";
      echo '<li><a href="reports.php?r=listdesc">' . __("Events by Date with Descriptions") . '</a></li>' . "\n";
      echo '<li><a href="reports.php?r=regs">' . __("Events with Registrations") . '</a></li>' . "\n";
      echo '<li><a href="reports.php?r=notes">' . __("Events with Private Notes") . '</a></li>' . "\n";
      echo '<li><a href="reports.php?r=private">' . __("Private Events") . '</a></li>' . "\n";
      echo '<li><a href="reports.php?r=cancelled">' . __("Cancelled Events") . '</a></li>' . "\n";
//    echo '<li><a href="reports.php?r=recurring">' . __("Recurring Events (those with multiple dates)") . '</a></li>' . "\n";
      echo '<li><a href="reports.php?r=creation">' . __("Events by Creation Date") . '</a></li>' . "\n";
      echo '<li><a href="reports.php?r=activation">' . __("Events by Activation Date") . '</a></li>' . "\n";
      echo '<li><a href="reports.php?r=location">' . __("Events by Location") . '</a></li>' . "\n";
      echo '<li><a href="reports.php?r=category">' . __("Events by Category") . '</a></li>' . "\n";
      echo '<li><a href="reports.php?r=creator">' . __("Events by Creator") . '</a></li>' . "\n";
      echo '<li><a href="reports.php?r=approver">' . __("Events by Approver") . '</a></li>' . "\n";
      echo '</ul>' . "\n";
      pem_fieldset_end();

// TODO - custom reports hardcoded
      pem_fieldset_begin(__("Custom Reports"));
      echo '<p>' . __("Select specific query critera to generate customized reports.  Queries can be saved for later repeated use.") . '</p>' . "\n";
      echo '<ul class="bullets">' . "\n";
//    echo '<li><a href="reports.php"></a>' . __("Build New Custom Report") . '</li>' . "\n";
      echo '<li><a href="reports.php?r=public">' . __("Public Calendar Posting") . '</a></li>' . "\n";
      echo '<li><a href="reports.php?r=press">' . __("Press Release") . '</a></li>' . "\n";
//    echo '<li><a href="reports.php?r=notesdaily">' . __("Maintenance and Pages Daily Listing") . '</a></li>' . "\n";
//    echo '<li><a href="reports.php?r=channel">' . __("Live on The Library Channel 10") . '</a></li>' . "\n";
//    echo '<li><a href="reports.php?r=corridor">' . __("Post on CulturalCorridor.org") . '</a></li>' . "\n";
      echo '</ul>' . "\n";
      pem_fieldset_end();
      break;
   case (pem_cache_get("current_navigation") == "statistics"):
      pem_fieldset_begin(__("Statistics"));
      echo '<p>' . __("Select an information set to generate live statistics.") . '</p>' . "\n";
      echo '<ul class="bullets">' . "\n";
      echo '<li><a href="statistics.php?r=general">' . __("General Statistics") . '</a></li>' . "\n";
//    echo '<li><a href="statistics.php?r=location">' . __("Statistics by Location") . '</a></li>' . "\n";
      echo '<li><a href="statistics.php?r=category">' . __("Statistics by Category") . '</a></li>' . "\n";
      echo '<li><a href="statistics.php?r=creator">' . __("Statistics by Creator") . '</a></li>' . "\n";
      echo '<li><a href="statistics.php?r=approver">' . __("Statistics by Approver") . '</a></li>' . "\n";
      echo '</ul>' . "\n";
      pem_fieldset_end();
      break;
   case (pem_cache_get("current_navigation") == "meta"):
      pem_fieldset_begin(__("Meta Fields"));
      echo '<p>' . __("These fields can be added to submission forms to provide additional description for events in either the entry or date content areas.") . '</p>' . "\n";
      echo '<ul class="bullets">' . "\n";
      echo '<li><a href="meta-textinput.php">' . __("Text Inputs") . '</a> - ' . __("Single fields for additional content capture") . '</li>' . "\n";
      echo '<li><a href="meta-checkbox.php">' . __("Checkboxes") . '</a> - ' . __("Boolean meta options with dynamic text for event views") . '</li>' . "\n";
      echo '<li><a href="meta-boolean.php">' . __("Yes/No Select") . '</a> - ' . __("Boolean meta options with dynamic text for event views") . '</li>' . "\n";
      echo '<li><a href="meta-select.php">' . __("Select Options") . '</a> - ' . __("Multiple drop-down select field options") . '</li>' . "\n";
      echo '<li><a href="meta-contact.php">' . __("Contact Information") . '</a> - ' . __("Contact-related field options") . '</li>' . "\n";
      echo '</ul>' . "\n";
      pem_fieldset_end();
      pem_fieldset_begin(__("Saved Content"));
      echo '<p>' . __("During the event submission process there are various opportunities to save content for reuse later.  That content can be managed using the options below.") . '</p>' . "\n";
      echo '<ul class="bullets">' . "\n";
      echo '<li><a href="event-templates.php">' . __("Saved Event Templates") . '</a> - ' . __("Snapshot captures of event data for reuse") . '</li>' . "\n";
      echo '<li><a href="field-data.php">' . __("Saved Field Data") . '</a> - ' . __("Simplified field auto-population") . '</li>' . "\n";
      echo '</ul>' . "\n";
      pem_fieldset_end();
      pem_fieldset_begin(__("Built-In Options"));
      echo '<p>' . __("These meta fields are predefined and can provide unique descriptive opportunities not available with generic meta fields.  They are usually populated during the initial setup and require little management thereafter.") . '</p>' . "\n";
      echo '<ul class="bullets">' . "\n";
//    echo '<li>' . __("Time Periods") . '</a> - ' . __("Define common time ranges for quick time entry") . '</li>' . "\n";
      echo '<li><a href="areas-spaces.php">' . __("Areas and Spaces") . '</a> - ' . __("Describe the locations where events are placed") . '</li>' . "\n";
      echo '<li><a href="categories.php">' . __("Categories") . '</a> - ' . __("Manage color-coded categories") . '</li>' . "\n";
      echo '<li><a href="presenter-types.php">' . __("Presenter Types") . '</a> - ' . __("Manage presenter labels for entries") . '</li>' . "\n";
      echo '<li><a href="supplies.php">' . __("Supplies") . '</a> - ' . __("Define the equipment available for use during events") . '</li>' . "\n";
      echo '<li><a href="supply-profiles.php">' . __("Supply Profiles") . '</a> - ' . __("Group equipment into profiles assigned to spaces") . '</li>' . "\n";
      echo '</ul>' . "\n";
      pem_fieldset_end();
      break;
   case (pem_cache_get("current_navigation") == "users"):
      pem_fieldset_begin(__("User Management"));
      echo '<p>' . __('All access to forms and content is managed through user accounts.  The built-in Public account can be used to adjust the settings for anonymous users. The default Admin account is a "back door" that provides global administration access to all features.  Either built-in account can be disabled if desired but neither can be deleted.') . '</p>' . "\n";
      echo '<ul class="bullets">' . "\n";
      echo '<li><a href="users.php">' . __("User Accounts") . '</a> - ' . __("Manage user account information and tie accounts to access profiles") . '</li>' . "\n";
      echo '<li><a href="account-settings.php">' . __("Account Settings") . '</a> - ' . __("Set minimum lengths for logins and passwords") . '</li>' . "\n";
      echo '<li><a href="access-profiles.php">' . __("Access Profiles") . '</a> - ' . __("Generate profiles used to grant permissions to resources") . '</li>' . "\n";
      echo '</ul>' . "\n";
      pem_fieldset_end();
      break;
   case (pem_cache_get("current_navigation") == "backend"):
      pem_fieldset_begin(__("Global Variables and Formats"));
      echo '<p>' . __("These settings effect the entire application.") . '</p>' . "\n";
      echo '<ul class="bullets">' . "\n";
      echo '<li><a href="general-settings.php">' . __("General Settings") . '</a> - ' . __("Identification, pathing, and charset information") . '</li>' . "\n";
      echo '<li><a href="datetime-formats.php">' . __("Date and Time Formats") . '</a> - ' . __("Display strings, weekday start, minute increment, and display duration") . '</li>' . "\n";
      echo '<li><a href="notice-settings.php">' . __("Event Notification Settings") . '</a> - ' . __("Configure the workflow of the event notification system") . '</li>' . "\n";
      echo '<li><a href="reg-settings.php">' . __("Registration Settings") . '</a> - ' . __("Configure the workflow of the registration process") . '</li>' . "\n";
      echo '</ul>' . "\n";
      pem_fieldset_end();
      pem_fieldset_begin(__("Form and Interface Settings"));
      echo '<p>' . __("Manage the visibility, order, and structure of content forms and views.") . '</p>' . "\n";
      echo '<ul class="bullets">' . "\n";
      echo '<li><a href="scheduling-structure.php">' . __("Scheduling Structure and Boundaries") . '</a> - ' . __("Limit bookings by date or time, configure buffers and setup/cleanup times") . '</li>' . "\n";
      echo '<li><a href="interface-defaults.php">' . __("Interface Defaults") . '</a> - ' . __("Select the theme color and default view") . '</li>' . "\n";
      echo '<li><a href="view-settings.php">' . __("View Settings") . '</a> - ' . __("Changes to the CSS may be required if these options are altered") . '</li>' . "\n";
      echo '<li><a href="field-defaults.php">' . __("Field Defaults") . '</a> - ' . __("Fields auto-populate with entered defaults for entry automation or user prompting") . '</li>' . "\n";
      echo '<li><a href="field-behavior.php">' . __("Field Behavior") . '</a> - ' . __("Determine visability and required settings for all entry fields") . '</li>' . "\n";
      echo '<li><a href="field-order.php">' . __("Field Order") . '</a> - ' . __("Set the display order of fields by entry type") . '</li>' . "\n";
      echo '</ul>' . "\n";
      pem_fieldset_end();
      pem_fieldset_begin(__("Content Management"));
      echo '<p>' . __("Most deleted content is not immediately removed and instead is placed in a hold status pending review.") . '</p>' . "\n";
      echo '<ul class="bullets">' . "\n";
      echo '<li><a href="recycle.php">' . __("Recycle Bin") . '</a> - ' . __("Manage previously deleted items") . '</li>' . "\n";
//    echo '<li><a href="import.php"></a>' . __("Import") . '</a> - ' . __("Import data from a file") . '</li>' . "\n";
//    echo '<li><a href="export.php"></a>' . __("Export") . '</a> - ' . __("Export data to a file") . '</li>' . "\n";
      echo '</ul>' . "\n";
      pem_fieldset_end();
      break;
   case (!pem_cache_isset("current_navigation")):
      echo '<p>' . __("Select a tab from the top right to choose which section you need.") . '</p>' . "\n";
}


// <li>Authentication</a> - Define system settings for authentication and session management</li>
// <li>Language Settings</a> - Select the default language for display</li>

include ABSPATH . PEMINC . "/footer.php";
?>