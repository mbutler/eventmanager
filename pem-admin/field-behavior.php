<?php
/* ========================== FILE INFORMATION ================================= 
phxEventManager :: field-behavior.php


============================================================================= */
$pagetitle = "Field Behavior Administration";
$navigation = "administration";
$page_access_requirement = "Admin";
$cache_set = array("current_navigation" => "backend");
include_once "../pem-includes/header.php";

if (isset($datasubmit))
{
   $post_keys = array_keys($_POST);
   for ($i = 0; $i < count($post_keys); $i++)
   {
      if ($post_keys[$i] != "datasubmit")
      {
         $current_data = explode("-", $post_keys[$i]);
         if ($current_data[1] == "label") $field_behavior[$current_data[0]]["label"] = $_POST[$post_keys[$i]];
         if ($current_data[1] == "is") $field_behavior[$current_data[0]]["internal_scheduled"] = $_POST[$post_keys[$i]];
         if ($current_data[1] == "es") $field_behavior[$current_data[0]]["external_scheduled"] = $_POST[$post_keys[$i]];
         if ($current_data[1] == "iu") $field_behavior[$current_data[0]]["internal_unscheduled"] = $_POST[$post_keys[$i]];
         if ($current_data[1] == "eu") $field_behavior[$current_data[0]]["external_unscheduled"] = $_POST[$post_keys[$i]];
      }
   }
   $field_keys = array_keys($field_behavior);
   for ($i = 0; $i < count($field_keys); $i++)
   {
      $data = $field_behavior[$field_keys[$i]];
      $where = array("name" => $field_keys[$i]);
      pem_update_row("field_behavior", $data, $where);
   }
}

pem_fieldset_begin(__("Event Field Behavior"));
echo '<p>' . __("Events consist of one entry and one or more dates.  Content that is shared across all date instances should be contained in entry fields.  Fields set to Required must be completed to submit a form. Visible elements appear on forms but are not required for succssful submition.  Inactive fields do not appear as form options or in the static views of events.  Registration fields are all tied to the Require Registration toggle; it must be visible for the others to show.  Either Submit button will submit this entire page of settings.") . "</p>\n";
echo_form();
pem_fieldset_end();

include ABSPATH . PEMINC . "/footer.php";

// =============================================================================
// =========================== LOCAL FUNCTIONS =================================
// =============================================================================


// $data is a keyed array containing all values needed by the form.
function echo_data($data, $row)
{
   global $field_labels;
   extract($data);
   $restricted = array("entry_type", "date_begin", "date_end", "time_begin", "time_end");

   echo '<tr class="tr' . $row . '">' . "\n" . '<td>';
   pem_field_label(array("default" => $field_labels[$name]));
   echo '</td>' . "\n" . '<td>';
   pem_text_input(array("name" => $name . "-label", "value" => $label, "size" => 20, "maxlength" => 64));
   echo '</td>' . "\n" . '<td>';
   if (!in_array($name, $restricted)) pem_field_behavior_select(array("name" => $name . "-is", "default" => $internal_scheduled));
   echo '</td>' . "\n" . '<td>';
   if (!in_array($name, $restricted)) pem_field_behavior_select(array("name" => $name . "-es", "default" => $external_scheduled));
   echo '</td>' . "\n" . '<td>';
   if (!in_array($name, $restricted)) pem_field_behavior_select(array("name" => $name . "-iu", "default" => $internal_unscheduled));
   echo '</td>' . "\n" . '<td>';
   if (!in_array($name, $restricted)) pem_field_behavior_select(array("name" => $name . "-eu", "default" => $external_unscheduled));
   echo '</td>' . "\n" . '</tr>' . "\n";
} // END echo_data

// $data is a keyed array containing all values needed by the form.
function echo_form()
{
   global $PHP_SELF;
   $fields_header =  array(
           __("Field Label"),
           __("Internal Calendar"),
           __("External Calendar"),
           __("Internal Side Box"),
           __("External Side Box"),
   );

   pem_form_begin(array("nameid" => "submitform", "action" => $PHP_SELF, "class" => "fieldsform"));
   pem_hidden_input(array("name" => "datasubmit", "value" => 1));

   echo '<table cellspacing="0"><tr>' . "\n" . '<th>';
   pem_field_label(array("default" => __("Entry Options"), "class" => "h3"));
   echo '</th>' . "\n";
   for ($i = 0; $i < count($fields_header); $i++)
   {
      echo '<th>' . $fields_header[$i] . '</th>' . "\n";
   }
   echo '</tr>' . "\n";
   $list = pem_get_rows("field_behavior");
   $rowclass = "1";
   for ($i = 0; $i < count($list); $i++)
   {
      if ($list[$i]["name"] == "date_begin")
      {
         echo '</table><br />' . "\n";
         pem_form_submit("submitform");
         echo '<br /><table cellspacing="0"><tr>' . "\n" . '<th>';
         pem_field_label(array("default" => __("Date Options"), "class" => "h3"));
         echo '</th>' . "\n";
         for ($j = 0; $j < count($fields_header); $j++)
         {
            echo '<th>' . $fields_header[$j] . '</th>' . "\n";
         }
         echo '</tr>' . "\n";
         $rowclass = "1";
      }
      echo_data($list[$i], $rowclass);
      $rowclass = ($rowclass == "1") ? "2" : "1";
   }
   echo '</table>' . "\n";

   pem_form_submit("submitform");
   pem_form_end();
} // END echo_form

?>