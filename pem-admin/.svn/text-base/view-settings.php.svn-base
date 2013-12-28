<?php
/* ========================== FILE INFORMATION ================================= 
phxEventManager :: view-settings.php


============================================================================= */

$pagetitle = "View Settings Administration";
$navigation = "administration";
$page_access_requirement = "Admin";
$cache_set = array("current_navigation" => "backend");
include_once "../pem-includes/header.php";

if (isset($datasubmitted))
{
   $datasubmitted = explode("-", $_POST["datasubmitted"]);
   $data = array(
           "show_event_name" => $show_event_name,
           "event_name_length" => $event_name_length,
           "show_time_begin" => $show_time_begin,
           "show_time_end" => $show_time_end,
           "show_area" => $show_area,
           "show_space" => $show_space,
           "show_category" => $show_category,
           "show_image" => $show_image,
           "max_listings" => $max_listings,
           "highlight_today" => $highlight_today,
           "show_minical" => $show_minical,
           "minical_format" => $minical_format,
           "minical_size" => $minical_size,
           "minical_highlight_today" => $minical_highlight_today,
           "show_internal_scheduled" => $show_internal_scheduled,
           "show_external_scheduled" => $show_external_scheduled,
           "show_internal_unscheduled" => $show_internal_unscheduled,
           "show_external_unscheduled" => $show_external_unscheduled,
           "show_category_box" => $show_category_box,
           "show_area_box" => $show_area_box,
           "show_space_box" => $show_space_box,
           "category_box_format" => $category_box_format,
           "area_box_format" => $area_box_format,
           "space_box_format" => $space_box_format,
           "active" => isset($active) ? true : false,
   );
   $where = array("view_name" => $datasubmitted[0]);
   pem_update_row("views", $data, $where);
   echo '<p><b>' . sprintf(__("View Updated:."), $datasubmitted[0]) . '</b></p>' . "\n";
}

$view_list = pem_get_rows("views");
for ($i = 0; $i < count($view_list); $i++)
{
   echo_data($view_list[$i]);
}

include ABSPATH . PEMINC . "/footer.php";

// =============================================================================
// =========================== LOCAL FUNCTIONS =================================
// =============================================================================

// $data is a keyed array containing all values needed by the form.
function echo_data($data)
{
   global $PHP_SELF;
   extract($data);
   $show_event_name_note = "";
   $event_name_length_note = __("(Set to 0 or leave blank for no limit)");
   $show_time_begin_note = __("(Display the time when the entry starts)");
   $show_time_end_note = __("(Display the time when the entry is finished)");
   $show_area_note = __("(Display the area for the scheduled spaces)");
   $show_space_note = __("(Display the spaces scheduled for a given event)");
   $show_category_note = __("show_category_note");
   $show_image_note = __("show_image_note");
   $max_listings_note = __("(Set to 0 or leave blank for no limit. Additional entries over this maximum will be signified by)");
   $highlight_today_note = __("(Applies the highlight CSS classes in non-day views)");

   $show_minical_note = __("(Sets the visibility of the Mini Calendar for a given view)");
   $minical_format_note = __("(Positioning of the three calendar months)");
   $minical_size_note = "";
   $minical_highlight_today_note = __("(Applies the minical highlight CSS classes)");

   $show_unscheduled_note = __("(Sets the visibility of the Display entries box for a given view)");
   $show_category_box_note = __("(Sets the visibility of the Categories box for a given view)");
   $show_area_box_note = __("(Sets the visibility of the Areas box for a given view)");
   $show_space_box_note = __("(Sets the visibility of the Spaces box for a given view)");
   $category_box_format_note = __("(Checkboxes allow multiple category selections at once, only single selections are possible with links)");
   $area_box_format_note = __("(The drop-down select box takes up less space, but links show all options at once)");
   $space_box_format_note = __("(The drop-down select box takes up less space, but links show all options at once)");

   pem_anchor($id);
   pem_fieldset_begin($view_name . ' ' . __("View"));
   pem_form_begin(array("nameid" => "viewform". $id, "action" => $PHP_SELF, "class" => "viewform"));
   pem_hidden_input(array("name" => "datasubmitted", "value" => $view_name . "-" . $id));

   pem_form_update("viewform" . $id, $view_name, "rightupdate");
   echo '<div class="status">' . __("Active:");
   pem_checkbox(array("name" => "active", "status" => $status));
   echo '</div>' . "\n";

   pem_field_label(array("default" => __("Show Event Name:"), "for" => "show_event_name"));
   pem_boolean_select(array("name" => "show_event_name", "default" => $show_event_name));
   pem_field_note($show_event_name_note);
   pem_field_label(array("default" => __("Event Name Length:"), "for" => "event_name_length"));
   pem_text_input(array("name" => "event_name_length", "value" => $event_name_length, "size" => 4, "maxlength" => 3));
   pem_field_note($event_name_length_note);
   pem_field_label(array("default" => __("Show Beginning Time:"), "for" => "show_time_begin"));
   pem_boolean_select(array("name" => "show_time_begin", "default" => $show_time_begin));
   pem_field_note($show_time_begin_note);
   pem_field_label(array("default" => __("Show Ending Time:"), "for" => "show_time_end"));
   pem_boolean_select(array("name" => "show_time_end", "default" => $show_time_end));
   pem_field_note($show_time_end_note);
   pem_field_label(array("default" => __("Show Area:"), "for" => "show_area"));
   pem_boolean_select(array("name" => "show_area", "default" => $show_area));
   pem_field_note($show_area_note);
   pem_field_label(array("default" => __("Show Space:"), "for" => "show_space"));
   pem_boolean_select(array("name" => "show_space", "default" => $show_space));
   pem_field_note($show_space_note);
   pem_field_label(array("default" => __("Show Category:"), "for" => "show_category"));
   pem_boolean_select(array("name" => "show_category", "default" => $show_category));
   pem_field_note($show_event_category);
   pem_field_label(array("default" => __("Show Image:"), "for" => "show_image"));
   pem_boolean_select(array("name" => "show_image", "default" => $show_image));
   pem_field_note($show_image_note);
   pem_field_label(array("default" => __("Max Listings per Day:"), "for" => "max_listings"));
   pem_text_input(array("name" => "max_listings", "value" => $max_listings, "size" => 4, "maxlength" => 3));
   pem_field_note($max_listings_note);
   pem_field_label(array("default" => __("Highlight Today:"), "for" => "highlight_today"));
   pem_boolean_select("highlight_today", array("default" => $highlight_today));
   pem_field_note($highlight_today_note);

   $controls[] = array("label" => __("Event Type Availability"), "target" => $id, "onclick" => "toggleVisible('type-" . $id . "');");
   $controls[] = array("label" => __("Box Options"), "target" => $id, "onclick" => "toggleVisible('box-" . $id . "');");
   $controls[] = array("label" => __("MiniCal Options"), "target" => $id, "onclick" => "toggleVisible('minical-" . $id . "');");
   pem_controls($controls);

   echo '<div class="type" id="type-' . $data["id"] . '">' . "\n";
   echo '<h3>' . __("Event Type Availability") . '</h3>';
   pem_field_label(array("default" => __("Internal Calendar:"), "for" => "show_internal_scheduled"));
   pem_boolean_select(array("name" => "show_internal_scheduled", "default" => $show_internal_scheduled));
   pem_field_note($show_internal_scheduled_note);
   pem_field_label(array("default" => __("External Calendar:"), "for" => "show_external_scheduled"));
   pem_boolean_select(array("name" => "show_external_scheduled", "default" => $show_external_scheduled));
   pem_field_note($show_external_scheduled_note);
   pem_field_label(array("default" => __("Internal Side Box:"), "for" => "show_internal_unscheduled"));
   pem_boolean_select(array("name" => "show_internal_unscheduled", "default" => $show_internal_unscheduled));
   pem_field_note($show_internal_unscheduled_note);
   pem_field_label(array("default" => __("External Side Box:"), "for" => "show_external_unscheduled"));
   pem_boolean_select(array("name" => "show_external_unscheduled", "default" => $show_external_unscheduled));
   pem_field_note($show_external_unscheduled_note);

   echo '</div>' . "\n";

   echo '<div class="box" id="box-' . $data["id"] . '">' . "\n";
   echo '<h3>' . __("Box Options") . '</h3>';
   pem_field_label(array("default" => __("Show Category Box:"), "for" => "show_category_box"));
   pem_boolean_select("show_category_box", array("default" => $show_category_box));
   pem_field_note($show_category_box_note);
   pem_field_label(array("default" => __("Show Area Box:"), "for" => "show_area_box"));
   pem_boolean_select("show_area_box", array("default" => $show_area_box));
   pem_field_note($show_area_box_note);
   pem_field_label(array("default" => __("Show Space Box:"), "for" => "show_space_box"));
   pem_boolean_select("show_space_box", array("default" => $show_space_box));
   pem_field_note($show_space_box_note);
   pem_field_label(array("default" => __("Category Box Format:"), "for" => "category_box_format"));
   pem_box_format_select("category_box_format", array("default" => $category_box_format));
   pem_field_note($category_box_format_note);
   pem_field_label(array("default" => __("Area Box Format:"), "for" => "area_box_format"));
   pem_box_format_select("area_box_format", array("default" => $area_box_format));
   pem_field_note($area_box_format_note);
   pem_field_label(array("default" => __("Space Box Format:"), "for" => "space_box_format"));
   pem_box_format_select("space_box_format", array("default" => $space_box_format));
   pem_field_note($space_box_format_note);
   echo '</div>' . "\n";

   echo '<div class="minical" id="minical-' . $data["id"] . '">' . "\n";
   echo '<h3>' . __("MiniCal Options") . '</h3>';
   pem_field_label(array("default" => __("Show MiniCal:"), "for" => "show_minical"));
   pem_boolean_select("show_minical", array("default" => $show_minical));
   pem_field_note($show_minical_note);

   pem_field_label(array("default" => __("MiniCal Format:"), "for" => "minical_format"));
   $minical_format_options = array(__("Vertical") => 0, __("Horizontal") => 1);
   pem_select($minical_format_options, array("name" => "minical_format", "default" => $minical_format));
   pem_field_note($minical_format_note);

   pem_field_label(array("default" => __("MiniCal Size:"), "for" => "minical_size"));
   $minical_size_options = array(__("Large") => 0, __("Small") => 1);
   pem_select($minical_size_options, array("name" => "minical_size", "default" => $minical_size));
   pem_field_note($minical_size_note);

   pem_field_label(array("default" => __("Highlight Today:"), "for" => "minical_highlight_today"));
   pem_boolean_select("minical_highlight_today", array("default" => $minical_highlight_today));
   pem_field_note($minical_highlight_today_note);
   echo '</div>' . "\n";

   pem_form_end();
   pem_fieldset_end();
} // END echo_data
?>