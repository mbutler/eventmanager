<?php
/* ========================== FILE INFORMATION =================================
phxEventManager :: search.php

Tool that provides customized search scope for event information and seperate
search form for finding registrants.
============================================================================= */

$pagetitle = "Event Manager Search";
$navigation = "event";
// $page_access_requirement = "Search";
$cache_set = array("current_eview" => "search");
include_once "pem-includes/header.php";

if (isset($datasubmit))
{
   if ($searchtype == "events")
   {
      $s_event_names = (isset($s_event_names)) ? 1 : 0;
      $s_event_descriptions = (isset($s_event_descriptions)) ? 1 : 0;
      $s_event_presenters = (isset($s_event_presenters)) ? 1 : 0;
      $s_event_contacts = (isset($s_event_contacts)) ? 1 : 0;
      if (!$s_event_names AND !$s_event_descriptions AND !$s_event_presenters AND !$s_event_contacts)
      {
         $s_event_names = 1;
         $s_event_descriptions = 1;
         $s_event_presenters = 1;
         $s_event_contacts = 1;
      }
   }
}
if (!isset($searchtype) OR $searchtype == "events")
{
   pem_fieldset_begin(__("Event Content Search"));
   echo '<p>' . __("This search will look for text present in all events and can be limited by customizing the checkboxes below.  Separate terms with a space or use quotes to form phrases.") . "</p>\n";
   echo_form("events");
   pem_fieldset_end();

   if (isset($datasubmit))
   {
      if ($s_event_names)
      {
         $fields[] = "e.entry_name";
         $fields[] = "d.date_name";
      }
      if ($s_event_descriptions)
      {
         $fields[] = "e.entry_description";
         $fields[] = "d.date_description";
      }
      if ($s_event_presenters)
      {
         $fields[] = "e.entry_presenter";
         $fields[] = "d.date_presenter";
      }
      if ($s_event_contacts)
      {
         $fields[] = "e.entry_meta";
         $fields[] = "d.date_meta";
      }

      $terms = pem_parse_string($search_terms);
      $results = search_events($terms, $fields);

      pem_fieldset_begin(__("Search Results"));

      if (empty($results))
      {
         echo '<p><b>' . __("No events found matching the search terms.") . '</b></p>' . "\n";
      }
      else
      {
         echo '<ul class="bullets">' . "\n";
         foreach ($results AS $this_event)
         {
            $title = '<a href="' . $pem_url . 'view.php?e=event&did=' . $this_event["id"] . '">';
            $title .= (!empty($this_event["date_name"])) ? $this_event["entry_name"] . ': ' . $this_event["date_name"] : $this_event["entry_name"];
            $date_begin = pem_date("l, " . $date_format, $this_event["when_begin"]);
            $date_end = pem_date("l, " . $date_format, $this_event["when_end"]);
            $title .= '</a> - ';
            if ($date_begin == $date_end) $title .= $date_begin;
            else $title .= pem_simplify_dates($date_begin, $date_end);
            echo '<li>' . $title . '</li>' . "\n";
         }
         echo '</ul>' . "\n";
      }
      pem_fieldset_end();
   }
}

if ((!isset($searchtype) OR $searchtype == "registrations") AND pem_user_authorized(array("Registrations" => "View")))
{
   pem_fieldset_begin(__("Registrant Search"));
   echo '<p>' . __("Find event registrants by name, address, or email. Separate terms with a space or use quotes to form phrases.") . "</p>\n";
   echo_form("registrations");
   pem_fieldset_end();

   if (isset($datasubmit))
   {
      $terms = pem_parse_string($search_terms);
      $results = search_registrants($terms);

      pem_fieldset_begin(__("Search Results"));

      if (empty($results))
      {
         echo '<p><b>' . __("No registrants found matching the search terms.") . '</b></p>' . "\n";
      }
      else
      {
         echo '<ul class="bullets">' . "\n";
         foreach ($results AS $reg)
         {
            if (empty($reg["entry_id"]))
            {
               $link = 'did=' . $reg["date_id"];
               $id_key = "date";
            }
            else
            {
               $link = 'eid=' . $reg["entry_id"];
               $id_key = "entry";
            }
            $sql = "SELECT e.entry_name, d.date_name, d.when_begin, d.when_end FROM " . $table_prefix . "entries as e, " . $table_prefix . "dates as d WHERE ";
            $sql .= "e.id = d.entry_id AND ";
            if ($id_key = "date")
            {
               $sql .= "d.id = :date_id AND ";
               $sql_values = array("date_id" => $reg["date_id"]);
            }
            else
            {
               $sql .= "e.id = :entry_id AND ";
               $sql_values = array("entry_id" => $reg["entry_id"]);
            }
            $sql .= "e.entry_status != 2 AND ";
            $sql .= "d.date_status != 2";
            $eventret = pem_exec_sql($sql, $sql_values);
            $this_event = $eventret[0];
            unset($eventret);
            $registrant = '<a href="' . $pem_url . 'pem-admin/manage-reg.php?' . $link . '">';
            $registrant .= '<b>' . $reg["name1"] . ' ' . $reg["name2"] . '</b></a>';
            if (!empty($reg["phone1"])) $registrant .= ', ' . $reg["phone1"];
            if (!empty($reg["phone2"])) $registrant .= ', ' . $reg["phone2"];
            if (!empty($reg["email"])) $registrant .= ', <a href="mailto:' . $reg["email"] . '">' . $reg["email"] . '</a>';
            $registrant .= '<br />' . "\n";
            $registrant .= $this_event["entry_name"];
            $registrant .= (!empty($this_event["date_name"])) ? ": " . $this_event["date_name"] . " - " : " - ";
            $date_begin = pem_date("l, " . $date_format, $this_event["when_begin"]);
            $date_end = pem_date("l, " . $date_format, $this_event["when_end"]);
            if ($date_begin == $date_end) $registrant .= $date_begin;
            else $registrant .= pem_simplify_dates($date_begin, $date_end);

            echo '<li>' . $registrant . '</li>' . "\n";
         }
         echo '</ul>' . "\n";
      }
      pem_fieldset_end();
   }
}

include ABSPATH . PEMINC . "/footer.php";

// =============================================================================
// =========================== LOCAL FUNCTIONS =================================
// =============================================================================

function echo_form($searchtype, $error = "")
{
   global $PHP_SELF, $search_terms, $s_event_names, $s_event_descriptions, $s_event_presenters, $s_event_contacts;

   pem_error_list($error);

   pem_form_begin(array("nameid" => $searchtype  . "_form", "action" => $PHP_SELF, "class" => "searchform"));
   pem_hidden_input(array("name" => "datasubmit", "value" => 1));
   pem_hidden_input(array("name" => "searchtype", "value" => $searchtype));

   pem_field_label(array("default" => __("Search Terms:"), "for" => "search_terms"));
   pem_text_input(array("name" => "search_terms", "value" => $search_terms, "size" => 40, "maxlength" => 80));
   echo "<br />";

   if ($searchtype == "events")
   {
      $s_event_names  = ($s_event_names === 0) ? false : true;
      $s_event_descriptions  = ($s_event_descriptions === 0) ? false : true;
      $s_event_presenters  = ($s_event_presenters === 0) ? false : true;
      $s_event_contacts  = ($s_event_contacts === 0) ? false : true;

      pem_field_label(array("default" => __("Only Search:")));
      pem_checkbox(array("nameid" => "s_event_names", "status" => $s_event_names, "style" => "float:left;"));
      pem_field_label(array("default" => __("Event Names"), "for" => "s_event_names", "style" => "font-weight:normal; margin-right:20px;"));
      pem_checkbox(array("nameid" => "s_event_descriptions", "status" => $s_event_descriptions, "style" => "float:left;"));
      pem_field_label(array("default" => __("Event Descriptions"), "for" => "s_event_descriptions", "style" => "font-weight:normal; margin-right:20px;"));
      pem_checkbox(array("nameid" => "s_event_presenters", "status" => $s_event_presenters, "style" => "float:left;"));
      pem_field_label(array("default" => __("Presenters"), "for" => "s_event_presenters", "style" => "font-weight:normal; margin-right:20px;"));
      pem_checkbox(array("nameid" => "s_event_contacts", "status" => $s_event_contacts, "style" => "float:left;"));
      pem_field_label(array("default" => __("Contacts"), "for" => "s_event_contacts", "style" => "font-weight:normal;"));
      echo "<br />";
   }

   pem_form_submit($searchtype . "_form");
   pem_form_end();
} // END echo_form

function search_events($terms, $fields)
{
   global $table_prefix;

   $sqlsub1 = "";
   $sql = "SELECT * FROM " . $table_prefix . "entries as e, " . $table_prefix . "dates as d WHERE ";
   $sql .= "e.id = d.entry_id AND (";
   foreach ($terms AS $term)
   {
      $sqlsub1 .= "(";
      $sqlsub2 = "";
      foreach ($fields AS $field) $sqlsub2 .= "$field LIKE '%$term%' OR ";
      $sqlsub1 .= substr($sqlsub2, 0, -4);
      $sqlsub1 .= ") AND ";
   }
   $sql .= substr($sqlsub1, 0, -5);
   $sql .= ") AND ";
   $sql .= "e.entry_status != 2 AND ";
   $sql .= "d.date_status != 2";
   $ret = pem_exec_sql($sql);
   return $ret;
} // END search_events

function search_registrants($terms)
{
   global $table_prefix;
   $fields = array("name1", "name2", "street1", "street2", "email");

   $sqlsub1 = "";
   $sql = "SELECT * FROM " . $table_prefix . "registrants WHERE ";
   $sql .= "(";
   foreach ($terms AS $term)
   {
      $sqlsub1 .= "(";
      $sqlsub2 = "";
      foreach ($fields AS $field) $sqlsub2 .= "$field LIKE '%$term%' OR ";
      $sqlsub1 .= substr($sqlsub2, 0, -4);
      $sqlsub1 .= ") AND ";
   }
   $sql .= substr($sqlsub1, 0, -5);
   $sql .= ")";
   $ret = pem_exec_sql($sql);
   return $ret;
} // END search_events

?>