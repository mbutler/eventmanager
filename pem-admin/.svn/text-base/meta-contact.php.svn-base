<?php
/* ========================== FILE INFORMATION =================================
phxEventManager :: meta-checkbox.php

============================================================================= */

$pagetitle = "Contact Information Meta Administration";
$navigation = "administration";
$page_access_requirement = "Manage Meta";
$cache_set = array("current_navigation" => "meta");
include_once "../pem-includes/header.php";

$showaddform = true;
if (isset($datasubmit))
{
   unset($error);
   // The error checks are done here with if and not switch because case(empty($x)) returns a false positive
   if (empty($meta_name)) $error[] = __("Meta Name cannot be empty.");
   if ($name1_behavior == 0 AND $name2_behavior == 0 AND $street1_behavior == 0 AND
           $street2_behavior == 0 AND $city_behavior == 0 AND $state_behavior == 0 AND
           $postal_behavior == 0 AND $phone1_behavior == 0 AND $phone2_behavior == 0 AND
           $email_behavior == 0 AND $website_behavior == 0) $error[] = __("Inactive fields will be removed.  At least one field must be active.");

   switch (true)
   {
      case ($datasubmit == "new" AND isset($error)):
         $showaddform = false;
         pem_fieldset_begin(__("Add New Contact Information Meta"));
         $value_prep["name1"] = $name1;
         $value_prep["name2"] = $name2;
         $value_prep["street1"] = $street1;
         $value_prep["street2"] = $street2;
         $value_prep["city"] = $city;
         $value_prep["state"] = $state;
         $value_prep["postal"] = $postal;
         $value_prep["phone1"] = $phone1;
         $value_prep["phone2"] = $phone2;
         $value_prep["email"] = $email;
         $value_prep["website"] = $website;
         $value_prep["name1_behavior"] = $name1_behavior;
         $value_prep["name2_behavior"] = $name2_behavior;
         $value_prep["street1_behavior"] = $street1_behavior;
         $value_prep["street2_behavior"] = $street2_behavior;
         $value_prep["city_behavior"] = $city_behavior;
         $value_prep["state_behavior"] = $state_behavior;
         $value_prep["postal_behavior"] = $postal_behavior;
         $value_prep["phone1_behavior"] = $phone1_behavior;
         $value_prep["phone2_behavior"] = $phone2_behavior;
         $value_prep["email_behavior"] = $email_behavior;
         $value_prep["website_behavior"] = $website_behavior;
         $value_prep["name1_note"] = $name1_note;
         $value_prep["name2_note"] = $name2_note;
         $value_prep["street1_note"] = $street1_note;
         $value_prep["street2_note"] = $street2_note;
         $value_prep["city_note"] = $city_note;
         $value_prep["state_note"] = $state_note;
         $value_prep["postal_note"] = $postal_note;
         $value_prep["phone1_note"] = $phone1_note;
         $value_prep["phone2_note"] = $phone2_note;
         $value_prep["email_note"] = $email_note;
         $value_prep["website_note"] = $website_note;
         echo_form("", $meta_name, $meta_description, $meta_parent, $internal_scheduled, $external_scheduled, $internal_unscheduled, $external_unscheduled, $status, $value_prep, "new", $error);
         pem_fieldset_end();
         break;
      case ($datasubmit == "new"):
         if (!empty($name1_behavior))
         {
            $name1 = (empty($name1)) ? __("Name1:") : $name1;
            $value_prep["name1"] = array($name1, $name1_behavior, $name1_note);
         }
         if (!empty($name2_behavior))
         {
            $name2 = (empty($name2)) ? __("Name2:") : $name2;
            $value_prep["name2"] = array($name2, $name2_behavior, $name2_note);
         }
         if (!empty($street1_behavior))
         {
            $street1 = (empty($street1)) ? __("Street1:") : $street1;
            $value_prep["street1"] = array($street1, $street1_behavior, $street1_note);
         }
         if (!empty($street2_behavior))
         {
            $street2 = (empty($street2)) ? __("Street2:") : $street2;
            $value_prep["street2"] = array($street2, $street2_behavior, $street2_note);
         }
         if (!empty($city_behavior))
         {
            $city = (empty($city)) ? __("City:") : $city;
            $value_prep["city"] = array($city, $city_behavior, $city_note);
         }
         if (!empty($state_behavior))
         {
            $state = (empty($state)) ? __("State:") : $state;
            $value_prep["state"] = array($state, $state_behavior, $state_note);
         }
         if (!empty($postal_behavior))
         {
            $postal = (empty($postal)) ? __("Postal Code:") : $postal;
            $value_prep["postal"] = array($postal, $postal_behavior, $postal_note);
         }
         if (!empty($phone1_behavior))
         {
            $phone1 = (empty($phone1)) ? __("Phone1:") : $phone1;
            $value_prep["phone1"] = array($phone1, $phone1_behavior, $phone1_note);
         }
         if (!empty($phone2_behavior))
         {
            $phone2 = (empty($phone2)) ? __("Phone2:") : $phone2;
            $value_prep["phone2"] = array($phone2, $phone2_behavior, $phone2_note);
         }
         if (!empty($email_behavior))
         {
            $email = (empty($email)) ? __("Email:") : $email;
            $value_prep["email"] = array($email, $email_behavior, $email_note);
         }
         if (!empty($website_behavior))
         {
            $website = (empty($website)) ? __("Website:") : $website;
            $value_prep["website"] = array($website, $website_behavior, $website_note);
         }
         $value = (isset($value_prep)) ? serialize($value_prep) : "";
         $data = array("meta_name" => $meta_name, "meta_description" => $meta_description, "meta_type" => "contact", "meta_parent" => $meta_parent, "internal_scheduled" => $internal_scheduled, "external_scheduled" => $external_scheduled, "internal_unscheduled" => $internal_unscheduled, "external_unscheduled" => $external_unscheduled, "value" => $value, "status" => $status);
         $types = array("meta_name" => "text", "meta_description" => "text", "meta_type" => "text", "meta_parent" => "text", "internal_scheduled" => "integer", "external_scheduled" => "integer", "internal_unscheduled" => "integer", "external_unscheduled" => "integer", "value" => "clob", "status" => "text");
         $newid = pem_add_row("meta", $data, $types);
         $data = array("name" => "meta" . $newid, "parent" => $meta_parent, "internal_scheduled" => 99, "external_scheduled" => 99, "internal_unscheduled" => 99, "external_unscheduled" => 99);
         $types = array("name" => "text", "parent" => "text", "internal_scheduled" => "integer", "external_scheduled" => "integer", "internal_unscheduled" => "integer", "external_unscheduled" => "integer");
         pem_add_row("field_order", $data, $types);
         echo '<p><b>' . __("Contact Information added.") . '</b></p>' . "\n";
         break;
      case ($datasubmit == "edit"):
         $showaddform = false;
         pem_fieldset_begin(__("Edit Contact Information"));
         echo '<p>' . __("Change the information as desired and submit the form to save your changes.") . "</p>\n";
         $data = pem_get_row("id", $typeid, "meta");
         $value = unserialize($data["value"]);
         $value_keys = array_keys($value);
         for ($i = 0; $i < count($value_keys); $i++)
         {
            $meta_value[$value_keys[$i]] = $value[$value_keys[$i]][0];
            $meta_value[$value_keys[$i] . "_behavior"] = $value[$value_keys[$i]][1];
            $meta_value[$value_keys[$i] . "_note"] = $value[$value_keys[$i]][2];
         }
         echo_form($data["id"], $data["meta_name"], $data["meta_description"], $data["meta_parent"], $data["internal_scheduled"], $data["external_scheduled"], $data["internal_unscheduled"], $data["external_unscheduled"], $data["status"], $meta_value, "update");
         pem_fieldset_end();
         break;
      case ($datasubmit == "update" AND isset($error)):
         $showaddform = false;
         pem_fieldset_begin(__("Edit Contact Information"));
         $value_prep["name1"] = $name1;
         $value_prep["name2"] = $name2;
         $value_prep["street1"] = $street1;
         $value_prep["street2"] = $street2;
         $value_prep["city"] = $city;
         $value_prep["state"] = $state;
         $value_prep["postal"] = $postal;
         $value_prep["phone1"] = $phone1;
         $value_prep["phone2"] = $phone2;
         $value_prep["email"] = $email;
         $value_prep["website"] = $website;
         $value_prep["name1_behavior"] = $name1_behavior;
         $value_prep["name2_behavior"] = $name2_behavior;
         $value_prep["street1_behavior"] = $street1_behavior;
         $value_prep["street2_behavior"] = $street2_behavior;
         $value_prep["city_behavior"] = $city_behavior;
         $value_prep["state_behavior"] = $state_behavior;
         $value_prep["postal_behavior"] = $postal_behavior;
         $value_prep["phone1_behavior"] = $phone1_behavior;
         $value_prep["phone2_behavior"] = $phone2_behavior;
         $value_prep["email_behavior"] = $email_behavior;
         $value_prep["website_behavior"] = $website_behavior;
         $value_prep["name1_note"] = $name1_note;
         $value_prep["name2_note"] = $name2_note;
         $value_prep["street1_note"] = $street1_note;
         $value_prep["street2_note"] = $street2_note;
         $value_prep["city_note"] = $city_note;
         $value_prep["state_note"] = $state_note;
         $value_prep["postal_note"] = $postal_note;
         $value_prep["phone1_note"] = $phone1_note;
         $value_prep["phone2_note"] = $phone2_note;
         $value_prep["email_note"] = $email_note;
         $value_prep["website_note"] = $website_note;
         echo_form($typeid, $meta_name, $meta_description, $meta_parent, $internal_scheduled, $external_scheduled, $internal_unscheduled, $external_unscheduled, $status, $value_prep, "update", $error);
         pem_fieldset_end();
         break;
      case ($datasubmit == "update"):
         $value_prep["name1"] = array($name1, $name1_behavior, $name1_note);
         $value_prep["name2"] = array($name2, $name2_behavior, $name2_note);
         $value_prep["street1"] = array($street1, $street1_behavior, $street1_note);
         $value_prep["street2"] = array($street2, $street2_behavior, $street2_note);
         $value_prep["city"] = array($city, $city_behavior, $city_note);
         $value_prep["state"] = array($state, $state_behavior, $state_note);
         $value_prep["postal"] = array($postal, $postal_behavior, $postal_note);
         $value_prep["phone1"] = array($phone1, $phone1_behavior, $phone1_note);
         $value_prep["phone2"] = array($phone2, $phone2_behavior, $phone2_note);
         $value_prep["email"] = array($email, $email_behavior, $email_note);
         $value_prep["website"] = array($website, $website_behavior, $website_note);
         $value = serialize($value_prep);
         $data = array("meta_name" => $meta_name, "meta_description" => $meta_description, "meta_parent" => $meta_parent, "internal_scheduled" => $internal_scheduled, "external_scheduled" => $external_scheduled, "internal_unscheduled" => $internal_unscheduled, "external_unscheduled" => $external_unscheduled, "value" => $value, "status" => $status);
         $where = array("id" => $typeid);
         pem_update_row("meta", $data, $where);
         echo '<p><b>' . __("Contact Information updated.") . '</b></p>' . "\n";
         break;
      case ($datasubmit == "activate"):
         $data = array("status" => "1");
         $where = array("id" => $typeid);
         pem_update_row("meta", $data, $where);
         echo '<p><b>' . __("Contact Information activated.") . '</b></p>' . "\n";
         break;
      case ($datasubmit == "deactivate"):
         $data = array("status" => "0");
         $where = array("id" => $typeid);
         pem_update_row("meta", $data, $where);
         echo '<p><b>' . __("Contact Information deactivated.") . '</b></p>' . "\n";
         break;
      case ($datasubmit == "delete"):
         $where = array("id" => $typeid);
         pem_delete_recycle("meta", $where);
         $where = array("name" => "meta" . $typeid);
         pem_delete_perm("field_order", $where);
         echo '<p><b>' . __("Contact Information deleted to recycle.") . '</b></p>' . "\n";
         break;
   }
}

// list current items in the table
pem_fieldset_begin(__("Current Contact Information Meta"));
echo '<p>' . __("Contact Information adds optional name, address, phone, and email fields to event forms.  They can provide additional descriptive information in views and be used as query options in dynamic reports.  Active fields without labels will default to the Meta Content labels on the left.") . "</p>\n";

$fields_header =  array(
        __("Internal<br />Calendar"),
        __("External<br />Calendar"),
        __("Internal<br />Side Box"),
        __("External<br />Side Box"),
);

$where = array("meta_type" => "contact");
$where["status"] = array("!=", "2");
$list = pem_get_rows("meta", $where);
ob_start();
for ($i = 0; $i < count($list); $i++)
{
   $row = ($i % 2) ? "row2" : "row1";
   echo_data($list[$i], $row);
}
$data = ob_get_clean();
if (!empty($data))
{
   echo '<table cellspacing="0" class="datalist">' . "\n";
   echo '<tr>' . "\n";
   echo '<th style="padding-top:0;"></th>' . "\n";
   for ($i = 0; $i < count($fields_header); $i++)
   {
      echo '<th style="padding-top:0;">' . $fields_header[$i] . '</th>' . "\n";
   }
   echo '</tr>' . "\n";
   echo $data;
   echo '</table>' . "\n";
}
else
{
   echo '<p style="font-weight:bold;">' . __("No items found.") . '</p>' . "\n";
}
pem_fieldset_end();

// display add new form if edit not in progress
if ($showaddform)
{
   pem_fieldset_begin(__("Add New Contact Information Meta"));
   echo '<p>' . __("Define a new meta resource to expand event informaton options.") . "</p>\n";
   echo_form();
   pem_fieldset_end();
}

include ABSPATH . PEMINC . "/footer.php";


// =============================================================================
// =========================== LOCAL FUNCTIONS =================================
// =============================================================================

function echo_data($data, $row)
{
   global $delete_confirm;
   extract($data);

   echo '<tr class="' . $row . '"><td>' . "\n";
   pem_form_begin(array("nameid" => "dataform" . $id, "action" => $PHP_SELF));
   pem_hidden_input(array("name" => "datasubmit", "value" => "edit"));
   pem_hidden_input(array("name" => "typeid", "value" => $id));
   pem_field_label(array("default" => $meta_name));
   // if (!empty($meta_description)) echo '<br /><p class="indent" style="text-align:left;">' . $meta_description . '</p>' . "\n";
   pem_form_end();
   echo '</td><td>' . "\n";
   echo ($internal_scheduled == 1) ? __("Yes") : __("No");
   echo '</td><td>' . "\n";
   echo ($external_scheduled == 1) ? __("Yes") : __("No");
   echo '</td><td>' . "\n";
   echo ($internal_unscheduled == 1) ? __("Yes") : __("No");
   echo '</td><td>' . "\n";
   echo ($external_unscheduled == 1) ? __("Yes") : __("No");
   echo '</td><td class="controlbox">' . "\n";
   if ($status == "0") $controls[] = array("label" => __("Activate"), "onclick" => "action_submit('dataform" . $id . "', 'activate');");
   else $controls[] = array("label" => __("Deactivate"), "onclick" => "action_submit('dataform" . $id . "', 'deactivate');");
   $controls[] = array("label" => __("Edit"), "onclick" => "action_submit('dataform" . $id . "', 'edit');");
   $controls[] = array("label" => __("Delete"), "onclick" => "confirm_submit('dataform" . $id . "', 'delete', '" . $delete_confirm . "');");
   pem_controls($controls);
   echo '</td></tr>' . "\n";
} // END echo_data

function echo_form($id = "", $meta_name = "", $meta_description = "", $meta_parent = "", $internal_scheduled = "", $external_scheduled = "", $internal_unscheduled = "", $external_unscheduled = "", $status = "", $meta = "", $mode = "new", $error = "")
{
   global $PHP_SELF;
   if (!empty($meta)) extract($meta);
   $meta_name_note = __("(The name of the meta item, used for administration)");
   $meta_description_note = __("(Describe this item for future reference, used for administration only)");
   $meta_parent_note = __("(Each item is associated with either the entry master section or the date instances tied to the entry)");
   $status_note = __("(This meta object must be active for the option to appear in forms and views)");
   $name1_field_note = __("(Examples: Name, First Name, Organization, Business)");
   $name2_field_note = __("(Examples: Last Name, Secondary Contact, Organization Contact)");
   $street1_field_note = __("(Examples: Address, Street)");
   $street2_field_note = __("(Examples: Address (cont.), Street 2)");
   $city_field_note = __("(Examples: City, Town)");
   $state_field_note = __("(Examples: State, Province)");
   $postal_field_note = __("(Examples: Postal Code, Zip Code, Zip)");
   $phone1_field_note = __("(Examples: Phone, Telephone, Cell)");
   $phone2_field_note = __("(Examples: Fax, Phone 2)");
   $email_field_note = __("(Examples: Email, Internet Mail)");
   $website_field_note = __("(Examples: Website, Site, URL, URI)");

   $name1_note_note = __("(This text is displayed after the field to explain it to users)");
   $name2_note_note = __("(This text is displayed after the field to explain it to users)");
   $street1_note_note = __("(This text is displayed after the field to explain it to users)");
   $street2_note_note = __("(This text is displayed after the field to explain it to users)");
   $city_note_note = __("(This text is displayed after the field to explain it to users)");
   $state_note_note = __("(This text is displayed after the field to explain it to users)");
   $postal_note_note = __("(This text is displayed after the field to explain it to users)");
   $phone1_note_note = __("(This text is displayed after the field to explain it to users)");
   $phone2_note_note = __("(This text is displayed after the field to explain it to users)");
   $email_note_note = __("(This text is displayed after the field to explain it to users)");
   $website_note_note = __("(This text is displayed after the field to explain it to users)");


   pem_error_list($error);

   pem_form_begin(array("nameid" => "submitform", "action" => $PHP_SELF, "class" => "metacontactform"));
   pem_hidden_input(array("name" => "datasubmit", "value" => $mode));
   pem_hidden_input(array("name" => "typeid", "value" => $id));
   pem_field_label(array("default" => __("Meta Name:"), "for" => "meta_name", "style" => "width:auto;"));
   pem_text_input(array("name" => "meta_name", "value" => $meta_name, "size" => 20, "maxlength" => 64));
   pem_field_note(array("default" => $meta_name_note));
   pem_field_label(array("default" => __("Description:"), "for" => "meta_description", "style" => "width:auto;"));
   pem_textarea_input(array("nameid" => "meta_description", "default" => $meta_description, "style" => "width:400px; height:100px;"));
   pem_field_note(array("default" => $meta_description_note));
   pem_field_label(array("default" => __("Add To Section:"), "for" => "meta_parent", "style" => "width:auto;"));
   pem_meta_parent_select(array("nameid" => "meta_parent", "default" => $meta_parent));
   pem_field_note(array("default" => $meta_parent_note));
   pem_field_label(array("default" => __("Internal Calendar Behavior:"), "for" => "internal_scheduled", "style" => "width:auto;"));
   pem_boolean_select(array("nameid" => "internal_scheduled", "default" => $internal_scheduled));
   pem_field_note(array("default" => $internal_scheduled_note));
   pem_field_label(array("default" => __("External Calendar Behavior:"), "for" => "external_scheduled", "style" => "width:auto;"));
   pem_boolean_select(array("nameid" => "external_scheduled", "default" => $external_scheduled));
   pem_field_note(array("default" => $external_scheduled_note));
   pem_field_label(array("default" => __("Internal Side Box Behavior:"), "for" => "internal_unscheduled", "style" => "width:auto;"));
   pem_boolean_select(array("nameid" => "internal_unscheduled", "default" => $internal_unscheduled));
   pem_field_note(array("default" => $internal_unscheduled_note));
   pem_field_label(array("default" => __("External Side Box Behavior:"), "for" => "external_unscheduled", "style" => "width:auto;"));
   pem_boolean_select(array("nameid" => "external_unscheduled", "default" => $external_unscheduled));
   pem_field_note(array("default" => $external_unscheduled_note));
   pem_field_label(array("default" => __("Active:"), "for" => "status", "style" => "width:auto;"));
   pem_boolean_select(array("nameid" => "status", "default" => $status));
   pem_field_note(array("default" => $status_note));

   echo '<h3 style="margin-top:15px;">' . __("Meta Content") . '</h3>' . "\n";
   pem_field_note(array("default" => $meta_note));
   pem_field_label(array("default" => __("Name1:"), "for" => "name1"));
   pem_field_behavior_select(array("nameid" => "name1_behavior", "default" => $name1_behavior));
   pem_text_input(array("nameid" => "name1", "value" => $name1, "size" => 30, "maxlength" => 64, "onchange" => "field_behavior_visible('name1_behavior');"));
   pem_field_note(array("default" => $name1_field_note, "maxlength" => 128));

   pem_field_label(array("default" => __("Name1 Note:"), "for" => "name1_note"));
   pem_text_input(array("nameid" => "name1_note", "value" => $name1_note, "size" => 30, "maxlength" => 40));
   pem_field_note(array("default" => $name1_note_note));

   pem_field_label(array("default" => __("Name2:"), "for" => "name2"));
   pem_field_behavior_select(array("nameid" => "name2_behavior", "default" => $name2_behavior));
   pem_text_input(array("nameid" => "name2", "value" => $name2, "size" => 30, "maxlength" => 64, "onchange" => "field_behavior_visible('name2_behavior');"));
   pem_field_note(array("default" => $name2_field_note));

   pem_field_label(array("default" => __("Name2 Note:"), "for" => "name2_note"));
   pem_text_input(array("nameid" => "name2_note", "value" => $name2_note, "size" => 30, "maxlength" => 40));
   pem_field_note(array("default" => $name2_note_note));

   pem_field_label(array("default" => __("Street1:"), "for" => "street1"));
   pem_field_behavior_select(array("nameid" => "street1_behavior", "default" => $street1_behavior));
   pem_text_input(array("nameid" => "street1", "value" => $street1, "size" => 30, "maxlength" => 64, "onchange" => "field_behavior_visible('street1_behavior');"));
   pem_field_note(array("default" => $street1_field_note));

   pem_field_label(array("default" => __("Street1 Note:"), "for" => "street1_note"));
   pem_text_input(array("nameid" => "street1_note", "value" => $street1_note, "size" => 30, "maxlength" => 40));
   pem_field_note(array("default" => $street1_note_note));

   pem_field_label(array("default" => __("Street2:"), "for" => "street2"));
   pem_field_behavior_select(array("nameid" => "street2_behavior", "default" => $street2_behavior));
   pem_text_input(array("nameid" => "street2", "value" => $street2, "size" => 30, "maxlength" => 64, "onchange" => "field_behavior_visible('street2_behavior');"));
   pem_field_note(array("default" => $street2_field_note));

   pem_field_label(array("default" => __("Street2 Note:"), "for" => "street2_note"));
   pem_text_input(array("nameid" => "street2_note", "value" => $street2_note, "size" => 30, "maxlength" => 40));
   pem_field_note(array("default" => $street2_note_note));

   pem_field_label(array("default" => __("City:"), "for" => "city"));
   pem_field_behavior_select(array("nameid" => "city_behavior", "default" => $city_behavior));
   pem_text_input(array("nameid" => "city", "value" => $city, "size" => 30, "maxlength" => 64, "onchange" => "field_behavior_visible('city_behavior');"));
   pem_field_note(array("default" => $city_field_note));

   pem_field_label(array("default" => __("City Note:"), "for" => "city_note"));
   pem_text_input(array("nameid" => "city_note", "value" => $city_note, "size" => 30, "maxlength" => 40));
   pem_field_note(array("default" => $city_note_note));

   pem_field_label(array("default" => __("State:"), "for" => "state"));
   pem_field_behavior_select(array("nameid" => "state_behavior", "default" => $state_behavior));
   pem_text_input(array("nameid" => "state", "value" => $state, "size" => 30, "maxlength" => 64, "onchange" => "field_behavior_visible('state_behavior');"));
   pem_field_note(array("default" => $state_field_note));

   pem_field_label(array("default" => __("State Note:"), "for" => "state_note"));
   pem_text_input(array("nameid" => "state_note", "value" => $state_note, "size" => 30, "maxlength" => 40));
   pem_field_note(array("default" => $state_note_note));

   pem_field_label(array("default" => __("Postal Code:"), "for" => "postal"));
   pem_field_behavior_select(array("nameid" => "postal_behavior", "default" => $postal_behavior));
   pem_text_input(array("nameid" => "postal", "value" => $postal, "size" => 30, "maxlength" => 64, "onchange" => "field_behavior_visible('postal_behavior');"));
   pem_field_note(array("default" => $postal_field_note));

   pem_field_label(array("default" => __("Postal Note:"), "for" => "postal_note"));
   pem_text_input(array("nameid" => "postal_note", "value" => $postal_note, "size" => 30, "maxlength" => 40));
   pem_field_note(array("default" => $postal_note_note));

   pem_field_label(array("default" => __("Phone1:"), "for" => "phone1"));
   pem_field_behavior_select(array("nameid" => "phone1_behavior", "default" => $phone1_behavior));
   pem_text_input(array("nameid" => "phone1", "value" => $phone1, "size" => 30, "maxlength" => 64, "onchange" => "field_behavior_visible('phone1_behavior');"));
   pem_field_note(array("default" => $phone1_field_note));

   pem_field_label(array("default" => __("Phone1 Note:"), "for" => "phone1_note"));
   pem_text_input(array("nameid" => "phone1_note", "value" => $phone1_note, "size" => 30, "maxlength" => 40));
   pem_field_note(array("default" => $phone1_note_note));

   pem_field_label(array("default" => __("Phone2:"), "for" => "phone2"));
   pem_field_behavior_select(array("nameid" => "phone2_behavior", "default" => $phone2_behavior));
   pem_text_input(array("nameid" => "phone2", "value" => $phone2, "size" => 30, "maxlength" => 64, "onchange" => "field_behavior_visible('phone2_behavior');"));
   pem_field_note(array("default" => $phone2_field_note));

   pem_field_label(array("default" => __("Phone2 Note:"), "for" => "phone2_note"));
   pem_text_input(array("nameid" => "phone2_note", "value" => $phone2_note, "size" => 30, "maxlength" => 40));
   pem_field_note(array("default" => $phone2_note_note));

   pem_field_label(array("default" => __("Email:"), "for" => "email"));
   pem_field_behavior_select(array("nameid" => "email_behavior", "default" => $email_behavior));
   pem_text_input(array("nameid" => "email", "value" => $email, "size" => 30, "maxlength" => 64, "onchange" => "field_behavior_visible('email_behavior');"));
   pem_field_note(array("default" => $email_field_note));

   pem_field_label(array("default" => __("Email Note:"), "for" => "email_note"));
   pem_text_input(array("nameid" => "email_note", "value" => $email_note, "size" => 30, "maxlength" => 40));
   pem_field_note(array("default" => $email_note_note));

   pem_field_label(array("default" => __("Website:"), "for" => "website"));
   pem_field_behavior_select(array("nameid" => "website_behavior", "default" => $website_behavior));
   pem_text_input(array("nameid" => "website", "value" => $website, "size" => 30, "maxlength" => 64, "onchange" => "field_behavior_visible('website_behavior');"));
   pem_field_note(array("default" => $website_field_note));

   pem_field_label(array("default" => __("Website Note:"), "for" => "website_note"));
   pem_text_input(array("nameid" => "website2_note", "value" => $website_note, "size" => 30, "maxlength" => 40));
   pem_field_note(array("default" => $website_note_note));

   if ($mode != "new") pem_form_update("submitform", "cancel");
   elseif ($mode == "new" AND !empty($error)) pem_form_submit("submitform", "cancel");
   else pem_form_submit("submitform");
   pem_form_end();
} // END echo_form

?>