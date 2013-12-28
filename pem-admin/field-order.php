<?php
/* ========================== FILE INFORMATION ================================= 
phxEventManager :: field-order.php


============================================================================= */
$pagetitle = "Field Order Administration";
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
         unset($data);
         $current_data = explode("-", $post_keys[$i]);
         $where = array("name" => $current_data[1]);
         if ($current_data[0] == "ins") $data["internal_scheduled"] = $_POST[$post_keys[$i]];
         if ($current_data[0] == "exs") $data["external_scheduled"] = $_POST[$post_keys[$i]];
         if ($current_data[0] == "inu") $data["internal_unscheduled"] = $_POST[$post_keys[$i]];
         if ($current_data[0] == "exu") $data["external_unscheduled"] = $_POST[$post_keys[$i]];
         pem_update_row("field_order", $data, $where);
      }
   }
}

pem_fieldset_begin(__("Event Field Order"));
echo '<p>' . __("Field order options are determined by entry type and <a href=\"field-behavior.php\">behavior settings</a>.  New meta items are given the a default last-place order position.  Any submit button will submit this entire page of settings, and alphabetical comparison is used to determine position when the same number is submitted for two or more items.") . "</p>\n";
echo_form();
pem_fieldset_end();

include ABSPATH . PEMINC . "/footer.php";

// =============================================================================
// =========================== LOCAL FUNCTIONS =================================
// =============================================================================


// $data is a keyed array containing all values needed by the form.
function echo_data($data, $prefix)
{
   $fields_header =  array(
           __("Field Label/Meta Name"),
           __("Position"),
   );

   echo '<table cellspacing="0"><tr>' . "\n";
   for ($i = 0; $i < count($fields_header); $i++)
   {
      echo '<th>' . $fields_header[$i] . '</th>' . "\n";
   }
   echo '</tr>' . "\n";
   $rowclass = "1";
   for ($i = 0; $i < count($data); $i++)
   {
      echo '<tr class="tr' . $row . '">' . "\n" . '<td>';
      pem_field_label(array("default" => $data[$i][2]));
      echo '</td>' . "\n" . '<td>';
      pem_text_input(array("name" => $prefix . "-" . $data[$i][1], "value" => $i+1, "size" => 3, "maxlength" => 2, "style" => "text-align:right;"));
      echo '</td>' . "\n" . '</tr>' . "\n";
      $rowclass = ($rowclass == "1") ? "2" : "1";
   }
   echo '</table>' . "\n";
   pem_form_submit("submitform");
   echo '<br /><br />' . "\n";
} // END echo_data

// $data is a keyed array containing all values needed by the form.
function echo_form()
{
   global $PHP_SELF;

   pem_form_begin(array("nameid" => "submitform", "action" => $PHP_SELF, "class" => "fieldsform"));
   pem_hidden_input(array("name" => "datasubmit", "value" => 1));

   $list = pem_get_rows("field_behavior");
   for ($i = 0; $i < count($list); $i++)
   {
      if ($list[$i]["internal_scheduled"] != 0) $instmp[$list[$i]["name"]] = $list[$i]["label"];
      if ($list[$i]["external_scheduled"] != 0) $exstmp[$list[$i]["name"]] = $list[$i]["label"];
      if ($list[$i]["internal_unscheduled"] != 0) $inutmp[$list[$i]["name"]] = $list[$i]["label"];
      if ($list[$i]["external_unscheduled"] != 0) $exutmp[$list[$i]["name"]] = $list[$i]["label"];
   }
   $list = pem_get_rows("meta");
   for ($i = 0; $i < count($list); $i++)
   {
      if ($list[$i]["internal_scheduled"] != 0) $instmp["meta" . $list[$i]["id"]] = $list[$i]["meta_name"];
      if ($list[$i]["external_scheduled"] != 0) $exstmp["meta" . $list[$i]["id"]] = $list[$i]["meta_name"];
      if ($list[$i]["internal_unscheduled"] != 0) $inutmp["meta" . $list[$i]["id"]] = $list[$i]["meta_name"];
      if ($list[$i]["external_unscheduled"] != 0) $exutmp["meta" . $list[$i]["id"]] = $list[$i]["meta_name"];
   }

   $list = pem_get_rows("field_order");
   for ($i = 0; $i < count($list); $i++)
   {
      $label = $instmp[$list[$i]["name"]];
      if ($list[$i]["name"] == "date_when") $label = __("Date:");
      if ($list[$i]["name"] == "date_location") $label = __("Location:");

      if (array_key_exists($list[$i]["name"], $instmp) OR $list[$i]["name"] == "date_when" OR $list[$i]["name"] == "date_location")
      {
         $ins[] = array($list[$i]["internal_scheduled"], $list[$i]["name"], $label);
      }
      if (array_key_exists($list[$i]["name"], $exstmp) OR $list[$i]["name"] == "date_when" OR $list[$i]["name"] == "date_location")
      {
         $exs[] = array($list[$i]["external_scheduled"], $list[$i]["name"], $label);
      }
      if (array_key_exists($list[$i]["name"], $inutmp) OR $list[$i]["name"] == "date_when" OR $list[$i]["name"] == "date_location")
      {
         $inu[] = array($list[$i]["internal_unscheduled"], $list[$i]["name"], $label);
      }
      if (array_key_exists($list[$i]["name"], $exutmp) OR $list[$i]["name"] == "date_when" OR $list[$i]["name"] == "date_location")
      {
         $exu[] = array($list[$i]["external_unscheduled"], $list[$i]["name"], $label);
      }
   }

   echo '<h3 class="positionhead">' . __("Internal Calendar Fields") . '</h3>' . "\n";
   sort($ins);
   echo_data($ins, "ins");
   echo '<h3 class="positionhead">' . __("External Calendar Fields") . '</h3>' . "\n";
   sort($exs);
   echo_data($exs, "exs");
   echo '<h3 class="positionhead">' . __("Internal Side Box Fields") . '</h3>' . "\n";
   sort($inu);
   echo_data($inu, "inu");
   echo '<h3 class="positionhead">' . __("External Side Box Fields") . '</h3>' . "\n";
   sort($exu);
   echo_data($exu, "exu");

   pem_form_end();
} // END echo_form

?>