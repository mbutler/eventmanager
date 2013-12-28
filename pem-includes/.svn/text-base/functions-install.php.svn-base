<?php
/* ========================== FILE INFORMATION =================================
phxEventManager :: functions-install.php

Functions unique to the install process.
============================================================================= */


// Creates the default table structure using given conection.
function pem_make_tables()
{
   global $pemdb;
   global $table_prefix;

   $tables[$table_prefix."access_profiles"] = array(
   "id" => array(
      "type" => "integer",
      "length" => 11,
      "notnull" => true,
      "unsigned"  => true,
      "autoincrement"  => true
   ),
   "profile_name" => array(
      "type" => "text",
      "length" => 30,
      "notnull" => false
   ),
   "description" => array(
      "type" => "text"
   ),
   "profile" => array(
      "type" => "clob"
   ),
   "status" => array(
      "type" => "text",
      "length" => 1,
      "fixed" => "fixed",
      "notnull" => true,
      "default" => 0 // 0=inactive, 1=active, 2=deleted
   ));
   $tables[$table_prefix."areas"] = array(
   "id" => array(
      "type" => "integer",
      "length" => 11,
      "notnull" => true,
      "unsigned"  => true,
      "autoincrement"  => true
   ),
   "area_name" => array(
      "type" => "text",
      "length" => 64,
      "notnull" => true
   ),
   "area_description" => array(
      "type" => "text"
   ),
   "area_popup" => array(
      "type" => "text",
      "length" => 64
   ),
   "area_contact" => array(
      "type" => "text",
      "length" => 64
   ),
   "status" => array(
      "type" => "text",
      "length" => 1,
      "fixed" => "fixed",
      "notnull" => true,
      "default" => 0 // 0=inactive, 1=active, 2=deleted
   ));
   $tables[$table_prefix."categories"] = array(
   "id" => array(
      "type" => "integer",
      "length" => 11,
      "notnull" => true,
      "unsigned"  => true,
      "autoincrement"  => true
   ),
   "category_name" => array(
      "type" => "text",
      "length" => 50,
      "notnull" => false
   ),
   "category_description" => array(
      "type" => "text"
   ),
   "category_color" => array(
      "type" => "text",
      "length" => 6,
      "notnull" => false
   ),
   "show_boxes" => array(
      "type" => "boolean",
      "notnull" => true,
      "default" => true
   ),
   "internal_scheduled" => array(
      "type" => "boolean",
      "notnull" => true,
      "default" => true
   ),
   "external_scheduled" => array(
      "type" => "boolean",
      "notnull" => true,
      "default" => true
   ),
   "internal_unscheduled" => array(
      "type" => "boolean",
      "notnull" => true,
      "default" => true
   ),
   "external_unscheduled" => array(
      "type" => "boolean",
      "notnull" => true,
      "default" => true
   ),
   "status" => array(
      "type" => "text",
      "length" => 1,
      "fixed" => "fixed",
      "notnull" => true,
      "default" => 0 // 0=inactive, 1=active, 2=deleted
   ));
   $tables[$table_prefix."dates"] = array(
   "id" => array(
      "type" => "integer",
      "length" => 11,
      "notnull" => true,
      "unsigned"  => true,
      "autoincrement"  => true
   ),
   "entry_id" => array(
      "type" => "integer",
      "length" => 11
   ),
   "when_begin" => array(
      "type" => "timestamp"
   ),
   "when_end" => array(
      "type" => "timestamp"
   ),
   "setup_time_before" => array(
      "type" => "float"
   ),
   "cleanup_time_after" => array(
      "type" => "float"
   ),
   "buffer_time_before" => array(
      "type" => "float"
   ),
   "buffer_time_after" => array(
      "type" => "float"
   ),
   "real_begin" => array(
      "type" => "timestamp"
   ),
   "real_end" => array(
      "type" => "timestamp"
   ),
   "conflicting" => array(
      "type" => "boolean",
      "notnull" => true,
      "default" => true
   ),
   "allday" => array(
      "type" => "boolean",
      "notnull" => true,
      "default" => false
   ),
   "display_begin" => array(
      "type" => "timestamp"
   ),
   "display_end" => array(
      "type" => "timestamp"
   ),
   "spaces" => array(
      "type" => "clob"
   ),
   "supplies" => array(
      "type" => "clob"
   ),
   "date_name" => array(
      "type" => "text",
      "length" => 80
   ),
   "date_description" => array(
      "type" => "text"
   ),
   "date_category" => array(
      "type" => "integer",
      "length" => 11,
      "default" => 0
   ),
   "date_presenter" => array(
      "type" => "text",
      "length" => 80
   ),
   "date_presenter_type" => array(
      "type" => "integer",
      "length" => 11
   ),
   "date_reg_require" => array(
      "type" => "boolean",
      "default" => false
   ),
   "date_reg_current" => array(
      "type" => "integer",
      "length" => 4,
      "default" => false
   ),
   "date_reg_max" => array(
      "type" => "integer",
      "length" => 4
   ),
   "date_allow_wait" => array(
      "type" => "boolean",
      "default" => false
   ),
   "date_reg_begin" => array(
      "type" => "date"
   ),
   "date_reg_end" => array(
      "type" => "date"
   ),
   "date_open_to_public" => array(
      "type" => "boolean",
      "default" => true
   ),
   "date_visible_to_public" => array(
      "type" => "boolean",
      "default" => true
   ),
   "date_seats_expected" => array(
      "type" => "integer",
      "length" => 4
   ),
   "date_priv_notes" => array(
      "type" => "text"
   ),
   "date_meta" => array(
      "type" => "clob"
   ),
   "date_created_by" => array(
      "type" => "text",
      "length" => 25
   ),
   "date_created_stamp" => array(
      "type" => "timestamp",
      "default" => "0000-00-00 00:00:00"
   ),
   "date_approved_by" => array(
      "type" => "text",
      "length" => 25
   ),
   "date_approved_stamp" => array(
      "type" => "timestamp",
      "default" => "0000-00-00 00:00:00"
   ),
   "date_edited_by" => array(
      "type" => "text",
      "length" => 25
   ),
   "date_edited_stamp" => array(
      "type" => "timestamp",
      "default" => "0000-00-00 00:00:00"
   ),
   "date_cancelled" => array(
      "type" => "boolean",
      "default" => false
   ),
   "date_status" => array(
      "type" => "text",
      "length" => 1,
      "fixed" => "fixed",
      "notnull" => true,
      "default" => 0 // 0=inactive, 1=active, 2=deleted
   ));
   $tables[$table_prefix."entries"] = array(
   "id" => array(
      "type" => "integer",
      "length" => 11,
      "notnull" => true,
      "unsigned"  => true,
      "autoincrement"  => true
   ),
   "entry_name" => array(
      "type" => "text",
      "length" => 80,
      "notnull" => true
   ),
   "entry_type" => array(
      "type" => "text",
      "length" => 1,
      "fixed" => "fixed",
      "notnull" => true,
      "default" => "0"
   ),
   "entry_description" => array(
      "type" => "text"
   ),
   "entry_category" => array(
      "type" => "integer",
      "length" => 11,
      "default" => 0
   ),
   "entry_presenter" => array(
      "type" => "text",
      "length" => 80
   ),
   "entry_presenter_type" => array(
      "type" => "integer",
      "length" => 11
   ),
   "entry_reg_require" => array(
      "type" => "boolean",
      "default" => false
   ),
   "entry_reg_current" => array(
      "type" => "integer",
      "length" => 4,
      "default" => false
   ),
   "entry_reg_max" => array(
      "type" => "integer",
      "length" => 4
   ),
   "entry_allow_wait" => array(
      "type" => "boolean",
      "default" => false
   ),
   "entry_reg_begin" => array(
      "type" => "date"
   ),
   "entry_reg_end" => array(
      "type" => "date"
   ),
   "entry_open_to_public" => array(
      "type" => "boolean",
      "default" => true
   ),
   "entry_visible_to_public" => array(
      "type" => "boolean",
      "default" => true
   ),
   "entry_seats_expected" => array(
      "type" => "integer",
      "length" => 11
   ),
   "entry_priv_notes" => array(
      "type" => "text"
   ),
   "entry_meta" => array(
      "type" => "clob"
   ),
   "entry_created_by" => array(
      "type" => "text",
      "length" => 25
   ),
   "entry_created_stamp" => array(
      "type" => "timestamp",
      "default" => "0000-00-00 00:00:00"
   ),
   "entry_approved_by" => array(
      "type" => "text",
      "length" => 25
   ),
   "entry_approved_stamp" => array(
      "type" => "timestamp",
      "default" => "0000-00-00 00:00:00"
   ),
   "entry_edited_by" => array(
      "type" => "text",
      "length" => 25
   ),
   "entry_edited_stamp" => array(
      "type" => "timestamp",
      "default" => "0000-00-00 00:00:00"
   ),
   "entry_cancelled" => array(
      "type" => "boolean",
      "default" => false
   ),
   "entry_status" => array(
      "type" => "text",
      "length" => 1,
      "fixed" => "fixed",
      "notnull" => true,
      "default" => 0 // 0=inactive, 1=active, 2=deleted
   ));
   $tables[$table_prefix."field_behavior"] = array(
   "id" => array(
      "type" => "integer",
      "length" => 11,
      "notnull" => true,
      "unsigned"  => true,
      "autoincrement" => true
   ),
   "name" => array(
      "type" => "text",
      "length" => 64,
      "notnull" => true
   ),
   "label" => array(
      "type" => "text",
      "length" => 64,
      "notnull" => true
   ),
   "parent" => array(
      "type" => "text",
      "length" => 1,
      "fixed" => "fixed",
      "notnull" => true,
      "default" => 0 // 0=entry, 1=date
   ),
   "internal_scheduled" => array(
      "type" => "integer",
      "length" => 2,
      "notnull" => true,
      "default" => 1
   ),
   "external_scheduled" => array(
      "type" => "integer",
      "length" => 2,
      "notnull" => true,
      "default" => 1
   ),
   "internal_unscheduled" => array(
      "type" => "integer",
      "length" => 2,
      "notnull" => true,
      "default" => 1
   ),
   "external_unscheduled" => array(
      "type" => "integer",
      "length" => 2,
      "notnull" => true,
      "default" => 1,
   ));
   $tables[$table_prefix."field_order"] = array(
   "id" => array(
      "type" => "integer",
      "length" => 11,
      "notnull" => true,
      "unsigned"  => true,
      "autoincrement" => true
   ),
   "name" => array(
      "type" => "text",
      "length" => 64,
      "notnull" => true
   ),
   "parent" => array(
      "type" => "text",
      "length" => 1,
      "fixed" => "fixed",
      "notnull" => true,
      "default" => 0 // 0=entry, 1=date
   ),
   "internal_scheduled" => array(
      "type" => "integer",
      "length" => 2
   ),
   "external_scheduled" => array(
      "type" => "integer",
      "length" => 2
   ),
   "internal_unscheduled" => array(
      "type" => "integer",
      "length" => 2
   ),
   "external_unscheduled" => array(
      "type" => "integer",
      "length" => 2
   ));
   $tables[$table_prefix."meta"] = array(
   "id" => array(
      "type" => "integer",
      "length" => 11,
      "notnull" => true,
      "unsigned"  => true,
      "autoincrement"  => true
   ),
   "meta_name" => array(
      "type" => "text",
      "length" => 64,
      "notnull" => true
   ),
   "meta_description" => array(
      "type" => "text"
   ),
   "meta_type" => array(
      "type" => "text",
      "length" => 32,
      "fixed" => "fixed",
      "notnull" => true
   ),
   "meta_parent" => array(
      "type" => "text",
      "length" => 1,
      "fixed" => "fixed",
      "notnull" => true,
      "default" => 0 // 0=entry, 1=date
   ),
   "internal_scheduled" => array(
      "type" => "integer",
      "length" => 2
   ),
   "external_scheduled" => array(
      "type" => "integer",
      "length" => 2
   ),
   "internal_unscheduled" => array(
      "type" => "integer",
      "length" => 2
   ),
   "external_unscheduled" => array(
      "type" => "integer",
      "length" => 2
   ),
   "value" => array(
      "type" => "clob"
   ),
   "status" => array(
      "type" => "text",
      "length" => 1,
      "fixed" => "fixed",
      "notnull" => true,
      "default" => 0 // 0=inactive, 1=active, 2=deleted
   ));
   $tables[$table_prefix."presenters"] = array(
   "id" => array(
      "type" => "integer",
      "length" => 11,
      "notnull" => true,
      "unsigned"  => true,
      "autoincrement"  => true
   ),
   "presenter_type" => array(
      "type" => "text",
      "length" => 50,
      "notnull" => false,
   ),
   "internal_scheduled" => array(
      "type" => "boolean",
      "notnull" => true,
      "default" => true
   ),
   "external_scheduled" => array(
      "type" => "boolean",
      "notnull" => true,
      "default" => true
   ),
   "internal_unscheduled" => array(
      "type" => "boolean",
      "notnull" => true,
      "default" => true
   ),
   "external_unscheduled" => array(
      "type" => "boolean",
      "notnull" => true,
      "default" => true
   ),
   "status" => array(
      "type" => "text",
      "length" => 1,
      "fixed" => "fixed",
      "notnull" => true,
      "default" => 0 // 0=inactive, 1=active, 2=deleted
   ));
   $tables[$table_prefix."registrants"] = array(
   "id" => array(
      "type" => "integer",
      "length" => 11,
      "notnull" => true,
      "unsigned"  => true,
      "autoincrement"  => true
   ),
   "entry_id" => array(
      "type" => "integer",
      "length" => 11,
      "notnull" => false
   ),
   "date_id" => array(
      "type" => "integer",
      "length" => 11,
      "notnull" => false
   ),
   "name1" => array(
      "type" => "text",
      "length" => 50,
      "notnull" => false
   ),
   "name2" => array(
      "type" => "text",
      "length" => 50,
      "notnull" => false
   ),
   "street1" => array(
      "type" => "text",
      "length" => 80,
      "notnull" => false
   ),
   "street2" => array(
      "type" => "text",
      "length" => 80,
      "notnull" => false
   ),
   "city" => array(
      "type" => "text",
      "length" => 80,
      "notnull" => false
   ),
   "state" => array(
      "type" => "text",
      "fixed" => "fixed",
      "length" => 20,
      "notnull" => false
   ),
   "postal" => array(
      "type" => "text",
      "length" => 10,
      "notnull" => false
   ),
   "phone1" => array(
      "type" => "text",
      "length" => 20,
      "notnull" => false
   ),
   "phone2" => array(
      "type" => "text",
      "length" => 20,
      "notnull" => false
   ),
   "email" => array(
      "type" => "text",
      "length" => 50,
      "notnull" => false
   ),
   "reminder" => array(
      "type" => "boolean",
      "notnull" => true,
      "default" => false,
   ),
   "reg_stamp" => array(
      "type" => "timestamp",
      "default" => "0000-00-00 00:00:00"
   ));
   $tables[$table_prefix."scheduling_profiles"] = array(
   "id" => array(
      "type" => "integer",
      "length" => 11,
      "notnull" => true,
      "unsigned"  => true,
      "autoincrement"  => true
   ),
   "profile_name" => array(
      "type" => "text",
      "length" => 40,
      "notnull" => false
   ),
   "date_begin" => array(
      "type" => "date",
      "notnull" => false
   ),
   "date_end" => array(
      "type" => "date",
      "notnull" => false
   ),
   "description" => array(
      "type" => "text"
   ),
   "profile" => array(
      "type" => "clob"
   ),
   "status" => array(
      "type" => "text",
      "length" => 1,
      "fixed" => "fixed",
      "notnull" => true,
      "default" => 0 // 0=inactive, 1=active, 2=deleted
   ));
   $tables[$table_prefix."settings"] = array(
   "id" => array(
      "type" => "integer",
      "length" => 11,
      "notnull" => true,
      "unsigned"  => true,
      "autoincrement"  => true
   ),
   "name" => array(
      "type" => "text",
      "length" => 64,
      "notnull" => true
   ),
   "value" => array(
      "type" => "clob",
   ));
   $tables[$table_prefix."spaces"] = array(
   "id" => array(
      "type" => "integer",
      "length" => 11,
      "notnull" => true,
      "unsigned"  => true,
      "autoincrement"  => true
   ),
   "space_name" => array(
      "type" => "text",
      "length" => 64,
      "notnull" => true
   ),
   "space_name_short" => array(
      "type" => "text",
      "length" => 24,
      "notnull" => true
   ),
   "space_description" => array(
      "type" => "text"
   ),
   "space_popup" => array(
      "type" => "text",
      "length" => 64
   ),
   "space_contact" => array(
      "type" => "text",
      "length" => 64
   ),
   "area" => array(
      "type" => "integer",
      "length" => 11,
      "notnull" => true,
      "default" => false
   ),
   "supply_profile" => array(
      "type" => "integer",
      "length" => 11,
      "notnull" => false
   ),
   "optional_supplies" => array(
      "type" => "integer",
      "length" => 11,
      "notnull" => false
   ),
   "capacity" => array(
      "type" => "integer",
      "length" => 5,
      "default" => 0
   ),
   "show_boxes" => array(
      "type" => "boolean",
      "notnull" => true,
      "default" => true
   ),
   "scheduling_profile" => array(
      "type" => "clob"
   ),
   "show_day_view" => array(
      "type" => "boolean",
      "notnull" => true,
      "default" => true
   ),
   "internal_scheduled" => array(
      "type" => "boolean",
      "notnull" => true,
      "default" => true
   ),
   "external_scheduled" => array(
      "type" => "boolean",
      "notnull" => true,
      "default" => true
   ),
   "internal_unscheduled" => array(
      "type" => "boolean",
      "notnull" => true,
      "default" => true
   ),
   "external_unscheduled" => array(
      "type" => "boolean",
      "notnull" => true,
      "default" => true
   ),
   "status" => array(
      "type" => "text",
      "length" => 1,
      "fixed" => "fixed",
      "notnull" => true,
      "default" => 0 // 0=inactive, 1=active, 2=deleted
   ));
   $tables[$table_prefix."supplies"] = array(
   "id" => array(
      "type" => "integer",
      "length" => 11,
      "notnull" => true,
      "unsigned"  => true,
      "autoincrement"  => true
   ),
   "supply_name" => array(
      "type" => "text",
      "length" => 50,
      "notnull" => true
   ),
   "internal_scheduled" => array(
      "type" => "boolean",
      "notnull" => true,
      "default" => false
   ),
   "external_scheduled" => array(
      "type" => "boolean",
      "notnull" => true,
      "default" => false
   ),
   "internal_unscheduled" => array(
      "type" => "boolean",
      "notnull" => true,
      "default" => false
   ),
   "external_unscheduled" => array(
      "type" => "boolean",
      "notnull" => true,
      "default" => false
   ),
   "status" => array(
      "type" => "text",
      "length" => 1,
      "fixed" => "fixed",
      "notnull" => true,
      "default" => 0 // 0=inactive, 1=active, 2=deleted
   ));
   $tables[$table_prefix."supply_profiles"] = array(
   "id" => array(
      "type" => "integer",
      "length" => 11,
      "notnull" => true,
      "unsigned"  => true,
      "autoincrement"  => true
   ),
   "profile_name" => array(
      "type" => "text",
      "length" => 40,
      "notnull" => false
   ),
   "description" => array(
      "type" => "text"
   ),
   "entry_type" => array(
      "type" => "text",
      "length" => 1,
      "fixed" => "fixed",
      "notnull" => true,
      "default" => "0"
   ),
   "profile" => array(
      "type" => "clob"
   ),
   "status" => array(
      "type" => "text",
      "length" => 1,
      "fixed" => "fixed",
      "notnull" => true,
      "default" => 0 // 0=inactive, 1=active, 2=deleted
   ));
   $tables[$table_prefix."users"] = array(
   "id" => array(
      "type" => "integer",
      "length" => 11,
      "notnull" => true,
      "unsigned"  => true,
      "autoincrement"  => true
   ),
   "user_login" => array(
      "type" => "text",
      "length" => 60,
      "notnull" => false
   ),
   "user_pass" => array(
      "type" => "text",
      "length" => 64,
      "notnull" => false
   ),
   "user_nicename" => array(
      "type" => "text",
      "length" => 80,
      "notnull" => false
   ),
   "user_email" => array(
      "type" => "text",
      "length" => 100,
      "notnull" => false
   ),
   "user_registered" => array(
      "type" => "timestamp",
      "notnull" => false
   ),
   "user_activity" => array(
      "type" => "timestamp",
      "notnull" => false
   ),
   "user_profile" => array(
      "type" => "integer",
      "length" => 11,
      "notnull" => false
   ),
   "status" => array(
      "type" => "text",
      "length" => 1,
      "fixed" => "fixed",
      "notnull" => true,
      "default" => 0 // 0=inactive, 1=active, 2=deleted
   ));
   $tables[$table_prefix."views"] = array(
   "id" => array(
      "type" => "integer",
      "length" => 11,
      "notnull" => true,
      "unsigned"  => true,
      "autoincrement"  => true
   ),
   "view_name" => array(
      "type" => "text",
      "length" => 50,
      "notnull" => false
   ),
   "show_event_name" => array(
      "type" => "boolean",
      "notnull" => true,
      "default" => true
   ),
   "event_name_length" => array(
      "type" => "integer",
      "length" => 3,
      "notnull" => false
   ),
   "show_time_begin" => array(
      "type" => "boolean",
      "notnull" => true,
      "default" => true
   ),
   "show_time_end" => array(
      "type" => "boolean",
      "notnull" => true,
      "default" => true
   ),
   "show_area" => array(
      "type" => "boolean",
      "notnull" => true,
      "default" => false
   ),
   "show_space" => array(
      "type" => "boolean",
      "notnull" => true,
      "default" => true
   ),
   "show_category" => array(
      "type" => "boolean",
      "notnull" => true,
      "default" => false
   ),
   "show_image" => array(
      "type" => "boolean",
      "notnull" => true,
      "default" => false
   ),
   "max_listings" => array(
      "type" => "integer",
      "length" => 2,
      "notnull" => true,
      "default" => 10
   ),
   "highlight_today" => array(
      "type" => "boolean",
      "notnull" => true,
      "default" => true
   ),
   "show_minical" => array(
      "type" => "boolean",
      "notnull" => true,
      "default" => true
   ),
   "minical_format" => array(
      "type" => "integer",
      "length" => 2,
      "notnull" => true,
      "default" => 0
   ),
   "minical_size" => array(
      "type" => "integer",
      "length" => 2,
      "notnull" => true,
      "default" => 0
   ),
   "minical_highlight_today" => array(
      "type" => "boolean",
      "notnull" => true,
      "default" => true
   ),
   "show_internal_scheduled" => array(
      "type" => "boolean",
      "notnull" => true,
      "default" => false
   ),
   "show_external_scheduled" => array(
      "type" => "boolean",
      "notnull" => true,
      "default" => false
   ),
   "show_internal_unscheduled" => array(
      "type" => "boolean",
      "notnull" => true,
      "default" => false
   ),
   "show_external_unscheduled" => array(
      "type" => "boolean",
      "notnull" => true,
      "default" => false
   ),
   "show_category_box" => array(
      "type" => "boolean",
      "notnull" => true,
      "default" => true
   ),
   "show_area_box" => array(
      "type" => "boolean",
      "notnull" => true,
      "default" => false
   ),
   "show_space_box" => array(
      "type" => "boolean",
      "notnull" => true,
      "default" => true
   ),
   "category_box_format" => array(
      "type" => "integer",
      "length" => 2,
      "notnull" => true,
      "default" => 0
   ),
   "area_box_format" => array(
      "type" => "integer",
      "length" => 2,
      "notnull" => true,
      "default" => 0
   ),
   "space_box_format" => array(
      "type" => "integer",
      "length" => 2,
      "notnull" => true,
      "default" => 0
   ),
   "status" => array(
      "type" => "text",
      "length" => 1,
      "fixed" => "fixed",
      "notnull" => true,
      "default" => 0 // 0=inactive, 1=active, 2=deleted
   ));

   $tablenames = array_keys($tables);
   for($i=0;$i<count($tablenames);$i++)
   {
     $currenttable = $tablenames[$i];
     $pemdb->mgCreateTable($currenttable, $tables[$currenttable]);
   }
   if (PEAR::isError($pemdb)) die($pemdb->getMessage()); // check for error
} // END pem_make_tables



function pem_populate_access_profiles($install_seed = false)
{
   $admin_access = array(
     "admin" => true,
   );
   $public_access = array(
     "inte" => array(10,11),
     "exte" => array(10,11),
     "intu" => array(10,11),
     "extu" => array(10,11),
     "regs" => array(11),
   );
   $data[] = array("profile_name" => __("Administrator"), "description" => __("Global administrator with complete access to all areas of the application."), "profile" => serialize($admin_access), "status" => "1");
   $data[] = array("profile_name" => __("Public"), "description" => __("Anonymous users not logged in are granted access based on this profile."), "profile" => serialize($public_access), "status" => "1");
   if ($install_seed)
   {
      $data[] = array("profile_name" => __("Example Staff"), "description" => "", "profile" => 'a:9:{s:4:"inte";a:5:{i:0;i:10;i:1;i:11;i:2;i:15;i:3;i:18;i:4;i:22;}s:4:"exte";a:5:{i:0;i:10;i:1;i:11;i:2;i:15;i:3;i:18;i:4;i:22;}s:4:"intu";a:5:{i:0;i:10;i:1;i:11;i:2;i:13;i:3;i:16;i:4;i:20;}s:4:"extu";a:1:{i:0;i:10;}s:4:"regs";a:4:{i:0;i:10;i:1;i:11;i:2;i:12;i:3;i:19;}s:4:"reps";a:1:{i:0;i:10;}s:4:"priv";a:1:{i:0;i:10;}s:4:"unap";a:1:{i:0;i:10;}s:4:"cncl";a:1:{i:0;i:10;}}', "status" => "1");
      $data[] = array("profile_name" => __("Example Assistant"), "description" => "", "profile" => 'a:9:{s:4:"inte";a:1:{i:0;i:10;}s:4:"exte";a:2:{i:0;i:10;i:1;i:11;}s:4:"intu";a:1:{i:0;i:10;}s:4:"extu";a:1:{i:0;i:10;}s:4:"regs";a:2:{i:0;i:10;i:1;i:11;}s:4:"reps";a:1:{i:0;i:10;}s:4:"priv";a:1:{i:0;i:10;}s:4:"unap";a:1:{i:0;i:10;}s:4:"cncl";a:1:{i:0;i:10;}}', "status" => "1");
   }
   $types = array("profile_name" => "text", "description" => "text", "profile" => "clob", "status" => "text");
   pem_add_rows("access_profiles", $data, $types);
} // END pem_populate_access_profiles

function pem_populate_areas()
{
   $data[] = array("area_name" => "Meeting Rooms", "status" => "1");
   $data[] = array("area_name" => "Display Spaces", "status" => "1");
   $types = array("area_name" => "text", "status" => "text");
   pem_add_rows("areas", $data, $types);
} // END pem_populate_areas

function pem_populate_categories($install_seed = false)
{
   $data[] = array("category_name" => __("All Events"), "category_color" => "000", "show_boxes" => true, "internal_scheduled" => 1, "external_scheduled" => 1, "internal_unscheduled" => 0, "external_unscheduled" => 0, "status" => "1");
   if ($install_seed)
   {
      $data[] = array("category_name" => __("General"), "category_color" => "000", "show_boxes" => true, "internal_scheduled" => 1, "external_scheduled" => 1, "internal_unscheduled" => 0, "external_unscheduled" => 0, "status" => "1");
      $data[] = array("category_name" => __("Adults"), "category_color" => "900", "show_boxes" => true, "internal_scheduled" => 1, "external_scheduled" => 1, "internal_unscheduled" => 0, "external_unscheduled" => 0, "status" => "1");
      $data[] = array("category_name" => __("Teens"), "category_color" => "090", "show_boxes" => true, "internal_scheduled" => 1, "external_scheduled" => 1, "internal_unscheduled" => 0, "external_unscheduled" => 0, "status" => "1");
      $data[] = array("category_name" => __("Kids"), "category_color" => "009", "show_boxes" => true, "internal_scheduled" => 1, "external_scheduled" => 1, "internal_unscheduled" => 0, "external_unscheduled" => 0, "status" => "1");
      $data[] = array("category_name" => __("Holidays"), "category_color" => "606", "show_boxes" => true, "internal_scheduled" => 1, "external_scheduled" => 1, "internal_unscheduled" => 0, "external_unscheduled" => 0, "status" => "1");
   }
   $types = array("category_name" => "text",
      "category_color" => "text", "show_boxes" => "boolean",
      "internal_scheduled" => "boolean", "external_scheduled" => "boolean",
      "internal_unscheduled" => "boolean", "external_unscheduled" => "boolean",
      "status" => "text");
   pem_add_rows("categories", $data, $types);
} // END pem_populate_areas

function pem_populate_field_behavior()
{
   // Options Specific to Entries
   $data[] = array("name" => "entry_type", "label" => __("Entry Type:"), "parent" => "0", "internal_scheduled" => 1, "external_scheduled" => 1, "internal_unscheduled" => 1, "external_unscheduled" => 1);
   $data[] = array("name" => "entry_name", "label" => __("Event Name:"), "parent" => "0", "internal_scheduled" => 2, "external_scheduled" => 2, "internal_unscheduled" => 2, "external_unscheduled" => 2);
   $data[] = array("name" => "entry_description", "label" => __("Event Description:"), "parent" => "0", "internal_scheduled" => 1, "external_scheduled" => 1, "internal_unscheduled" => 1, "external_unscheduled" => 1);
   $data[] = array("name" => "entry_category", "label" => __("Category:"), "parent" => "0", "internal_scheduled" => 1, "external_scheduled" => 0, "internal_unscheduled" => 1, "external_unscheduled" => 0);
   $data[] = array("name" => "entry_presenter", "label" => __("Presenter:"), "parent" => "0", "internal_scheduled" => 0, "external_scheduled" => 0, "internal_unscheduled" => 0, "external_unscheduled" => 0);
   $data[] = array("name" => "entry_presenter_type", "label" => __("Presenter Label:"), "parent" => "0", "internal_scheduled" => 0, "external_scheduled" => 0, "internal_unscheduled" => 0, "external_unscheduled" => 0);
   $data[] = array("name" => "entry_reg_require", "label" => __("Require Registration:"), "parent" => "0", "internal_scheduled" => 0, "external_scheduled" => 0, "internal_unscheduled" => 0, "external_unscheduled" => 0);
   $data[] = array("name" => "entry_reg_current", "label" => __("Current Registrants:"), "parent" => "0", "internal_scheduled" => 0, "external_scheduled" => 0, "internal_unscheduled" => 0, "external_unscheduled" => 0);
   $data[] = array("name" => "entry_reg_max", "label" => __("Maximum Registrants:"), "parent" => "0", "internal_scheduled" => 0, "external_scheduled" => 0, "internal_unscheduled" => 0, "external_unscheduled" => 0);
   $data[] = array("name" => "entry_allow_wait", "label" => __("Allow Waiting List:"), "parent" => "0", "internal_scheduled" => 0, "external_scheduled" => 0, "internal_unscheduled" => 0, "external_unscheduled" => 0);
   $data[] = array("name" => "entry_reg_begin", "label" => __("Registration Begin:"), "parent" => "0", "internal_scheduled" => 0, "external_scheduled" => 0, "internal_unscheduled" => 0, "external_unscheduled" => 0);
   $data[] = array("name" => "entry_reg_end", "label" => __("Registration End:"), "parent" => "0", "internal_scheduled" => 0, "external_scheduled" => 0, "internal_unscheduled" => 0, "external_unscheduled" => 0);
   $data[] = array("name" => "entry_open_to_public", "label" => __("Open to the Public:"), "parent" => "0", "internal_scheduled" => 0, "external_scheduled" => 0, "internal_unscheduled" => 0, "external_unscheduled" => 0);
   $data[] = array("name" => "entry_visible_to_public", "label" => __("Visible to the Public:"), "parent" => "0", "internal_scheduled" => 0, "external_scheduled" => 0, "internal_unscheduled" => 0, "external_unscheduled" => 0);
   $data[] = array("name" => "entry_seats_expected", "label" => __("Attendance Expected:"), "parent" => "0", "internal_scheduled" => 1, "external_scheduled" => 1, "internal_unscheduled" => 0, "external_unscheduled" => 0);
//   $data[] = array("name" => "entry_upload_image", "label" => __("Upload Image:"), "parent" => "0", "internal_scheduled" => 1, "external_scheduled" => 1, "internal_unscheduled" => 1, "external_unscheduled" => 1);
//   $data[] = array("name" => "entry_upload_file", "label" => __("Upload File:"), "parent" => "0", "internal_scheduled" => 1, "external_scheduled" => 1, "internal_unscheduled" => 0, "external_unscheduled" => 0);
   $data[] = array("name" => "entry_priv_notes", "label" => __("Private Notes:"), "parent" => "0", "internal_scheduled" => 0, "external_scheduled" => 0, "internal_unscheduled" => 0, "external_unscheduled" => 0);
//   $data[] = array("name" => "entry_cancelled", "label" => __("Entry Cancelled:"), "parent" => "0", "internal_scheduled" => 1, "external_scheduled" => 1, "internal_unscheduled" => 1, "external_unscheduled" => 1);
   // Options Specific to Dates
   $data[] = array("name" => "date_begin", "label" => __("Date Begins:"), "parent" => "1", "internal_scheduled" => 1, "external_scheduled" => 1, "internal_unscheduled" => 1, "external_unscheduled" => 1);
   $data[] = array("name" => "date_end", "label" => __("Date Ends:"), "parent" => "1", "internal_scheduled" => 1, "external_scheduled" => 1, "internal_unscheduled" => 1, "external_unscheduled" => 1);
   $data[] = array("name" => "time_begin", "label" => __("Time Begins:"), "parent" => "1", "internal_scheduled" => 1, "external_scheduled" => 1, "internal_unscheduled" => 0, "external_unscheduled" => 0);
   $data[] = array("name" => "time_end", "label" => __("Time Ends:"), "parent" => "1", "internal_scheduled" => 1, "external_scheduled" => 1, "internal_unscheduled" => 0, "external_unscheduled" => 0);
   $data[] = array("name" => "setup_time_before", "label" => __("Setup Time Before:"), "parent" => "1", "internal_scheduled" => 1, "external_scheduled" => 1, "internal_unscheduled" => 0, "external_unscheduled" => 0);
   $data[] = array("name" => "cleanup_time_after", "label" => __("Cleanup Time After:"), "parent" => "1", "internal_scheduled" => 1, "external_scheduled" => 1, "internal_unscheduled" => 0, "external_unscheduled" => 0);
   $data[] = array("name" => "buffer_time_before", "label" => __("Buffer Time Before:"), "parent" => "1", "internal_scheduled" => 1, "external_scheduled" => 1, "internal_unscheduled" => 0, "external_unscheduled" => 0);
   $data[] = array("name" => "buffer_time_after", "label" => __("Buffer Time After:"), "parent" => "1", "internal_scheduled" => 1, "external_scheduled" => 1, "internal_unscheduled" => 0, "external_unscheduled" => 0);
   $data[] = array("name" => "display_date_begin", "label" => __("Display Date Begins:"), "parent" => "1", "internal_scheduled" => 1, "external_scheduled" => 1, "internal_unscheduled" => 1, "external_unscheduled" => 1);
   $data[] = array("name" => "display_date_end", "label" => __("Display Date Ends:"), "parent" => "1", "internal_scheduled" => 1, "external_scheduled" => 1, "internal_unscheduled" => 1, "external_unscheduled" => 1);
   $data[] = array("name" => "display_time_begin", "label" => __("Display Time Begins:"), "parent" => "1", "internal_scheduled" => 1, "external_scheduled" => 0, "internal_unscheduled" => 0, "external_unscheduled" => 0);
   $data[] = array("name" => "display_time_end", "label" => __("Display Time Ends:"), "parent" => "1", "internal_scheduled" => 1, "external_scheduled" => 0, "internal_unscheduled" => 0, "external_unscheduled" => 0);
   $data[] = array("name" => "areas", "label" => __("Areas:"), "parent" => "1", "internal_scheduled" => 0, "external_scheduled" => 0, "internal_unscheduled" => 0, "external_unscheduled" => 0);
   $data[] = array("name" => "spaces", "label" => __("Spaces:"), "parent" => "1", "internal_scheduled" => 1, "external_scheduled" => 1, "internal_unscheduled" => 1, "external_unscheduled" => 1);
   $data[] = array("name" => "supplies", "label" => __("Supplies:"), "parent" => "1", "internal_scheduled" => 1, "external_scheduled" => 1, "internal_unscheduled" => 1, "external_unscheduled" => 1);
   $data[] = array("name" => "date_name", "label" => __("Date Name:"), "parent" => "1", "internal_scheduled" => 1, "external_scheduled" => 1, "internal_unscheduled" => 1, "external_unscheduled" => 1);
   $data[] = array("name" => "date_description", "label" => __("Date Description:"), "parent" => "1", "internal_scheduled" => 1, "external_scheduled" => 0, "internal_unscheduled" => 1, "external_unscheduled" => 0);
   $data[] = array("name" => "date_category", "label" => __("Category:"), "parent" => "1", "internal_scheduled" => 0, "external_scheduled" => 0, "internal_unscheduled" => 0, "external_unscheduled" => 0);
   $data[] = array("name" => "date_presenter", "label" => __("Presenter:"), "parent" => "1", "internal_scheduled" => 1, "external_scheduled" => 1, "internal_unscheduled" => 1, "external_unscheduled" => 1);
   $data[] = array("name" => "date_presenter_type", "label" => __("Presenter Label:"), "parent" => "1", "internal_scheduled" => 1, "external_scheduled" => 1, "internal_unscheduled" => 1, "external_unscheduled" => 1);
   $data[] = array("name" => "date_reg_require", "label" => __("Require Registration:"), "parent" => "1", "internal_scheduled" => 1, "external_scheduled" => 0, "internal_unscheduled" => 0, "external_unscheduled" => 0);
   $data[] = array("name" => "date_reg_current", "label" => __("Current Registrants:"), "parent" => "1", "internal_scheduled" => 1, "external_scheduled" => 0, "internal_unscheduled" => 0, "external_unscheduled" => 0);
   $data[] = array("name" => "date_reg_max", "label" => __("Maximum Registrants:"), "parent" => "1", "internal_scheduled" => 1, "external_scheduled" => 0, "internal_unscheduled" => 0, "external_unscheduled" => 0);
   $data[] = array("name" => "date_allow_wait", "label" => __("Allow Waiting List:"), "parent" => "1", "internal_scheduled" => 1, "external_scheduled" => 0, "internal_unscheduled" => 0, "external_unscheduled" => 0);
   $data[] = array("name" => "date_reg_begin", "label" => __("Registration Begin:"), "parent" => "1", "internal_scheduled" => 1, "external_scheduled" => 0, "internal_unscheduled" => 0, "external_unscheduled" => 1);
   $data[] = array("name" => "date_reg_end", "label" => __("Registration End:"), "parent" => "1", "internal_scheduled" => 1, "external_scheduled" => 0, "internal_unscheduled" => 0, "external_unscheduled" => 1);
   $data[] = array("name" => "date_open_to_public", "label" => __("Open to the Public:"), "parent" => "1", "internal_scheduled" => 1, "external_scheduled" => 1, "internal_unscheduled" => 0, "external_unscheduled" => 0);
   $data[] = array("name" => "date_visible_to_public", "label" => __("Visible to the Public:"), "parent" => "1", "internal_scheduled" => 1, "external_scheduled" => 0, "internal_unscheduled" => 1, "external_unscheduled" => 1);
   $data[] = array("name" => "date_seats_expected", "label" => __("Attendance Expected:"), "parent" => "1", "internal_scheduled" => 0, "external_scheduled" => 0, "internal_unscheduled" => 0, "external_unscheduled" => 0);
//   $data[] = array("name" => "date_upload_image", "label" => __("Upload Image:"), "parent" => "0", "internal_scheduled" => 0, "external_scheduled" => 0, "internal_unscheduled" => 0, "external_unscheduled" => 0);
//   $data[] = array("name" => "date_upload_file", "label" => __("Upload File:"), "parent" => "0", "internal_scheduled" => 0, "external_scheduled" => 0, "internal_unscheduled" => 0, "external_unscheduled" => 0);
   $data[] = array("name" => "date_priv_notes", "label" => __("Private Notes:"), "parent" => "1", "internal_scheduled" => 1, "external_scheduled" => 1, "internal_unscheduled" => 1, "external_unscheduled" => 1);
//   $data[] = array("name" => "date_cancelled", "label" => __("Date Cancelled:"), "parent" => "1", "internal_scheduled" => 1, "external_scheduled" => 1, "internal_unscheduled" => 1, "external_unscheduled" => 1);

   $types = array("name" => "text", "label" => "text", "parent" => "text",
      "internal_scheduled" => "integer", "external_scheduled" => "integer",
      "internal_unscheduled" => "integer", "external_unscheduled" => "integer");
   pem_add_rows("field_behavior", $data, $types);
} // END pem_populate_field_behavior

function pem_populate_field_order()
{
   $data[] = array("name" => "entry_name", "parent" => "0", "internal_scheduled" => 1, "external_scheduled" => 1, "internal_unscheduled" => 1, "external_unscheduled" => 1);
   $data[] = array("name" => "date_name", "parent" => "1", "internal_scheduled" => 2, "external_scheduled" => 2, "internal_unscheduled" => 2, "external_unscheduled" => 2);
   $data[] = array("name" => "entry_description", "parent" => "0", "internal_scheduled" => 3, "external_scheduled" => 3, "internal_unscheduled" => 3, "external_unscheduled" => 3);
   $data[] = array("name" => "date_description", "parent" => "1", "internal_scheduled" => 4, "external_scheduled" => 4, "internal_unscheduled" => 4, "external_unscheduled" => 4);

   $data[] = array("name" => "date_when", "parent" => "1", "internal_scheduled" => 5, "external_scheduled" => 4, "internal_unscheduled" => 5, "external_unscheduled" => 4);
   $data[] = array("name" => "date_location", "parent" => "1", "internal_scheduled" => 6, "external_scheduled" => 5, "internal_unscheduled" => 6, "external_unscheduled" => 5);

   $data[] = array("name" => "entry_category", "parent" => "0", "internal_scheduled" => 7, "external_scheduled" => 6, "internal_unscheduled" => 7, "external_unscheduled" => 6);
   $data[] = array("name" => "date_presenter", "parent" => "1", "internal_scheduled" => 8, "external_scheduled" => 7, "internal_unscheduled" => 8, "external_unscheduled" => 7);
   $data[] = array("name" => "date_presenter_type", "parent" => "1", "internal_scheduled" => 9, "external_scheduled" => 8, "internal_unscheduled" => 9, "external_unscheduled" => 8);
   $data[] = array("name" => "date_open_to_public", "parent" => "1", "internal_scheduled" => 12, "external_scheduled" => 13, "internal_unscheduled" => 99, "external_unscheduled" => 99);
   $data[] = array("name" => "date_visible_to_public", "parent" => "1", "internal_scheduled" => 13, "external_scheduled" => 14, "internal_unscheduled" => 14, "external_unscheduled" => 13);
   $data[] = array("name" => "meta7", "parent" => "1", "internal_scheduled" => 14, "external_scheduled" => 15, "internal_unscheduled" => 99, "external_unscheduled" => 99);
   $data[] = array("name" => "meta11", "parent" => "1", "internal_scheduled" => 15, "external_scheduled" => 99, "internal_unscheduled" => 99, "external_unscheduled" => 99);
   $data[] = array("name" => "date_reg_require", "parent" => "1", "internal_scheduled" => 16, "external_scheduled" => 16, "internal_unscheduled" => 99, "external_unscheduled" => 99);
   $data[] = array("name" => "entry_seats_expected", "parent" => "0", "internal_scheduled" => 17, "external_scheduled" => 17, "internal_unscheduled" => 99, "external_unscheduled" => 99);
//   $data[] = array("name" => "entry_upload_image", "parent" => "0", "internal_scheduled" => 18, "external_scheduled" => 18, "internal_unscheduled" => 15, "external_unscheduled" => 14);
//   $data[] = array("name" => "entry_upload_file", "parent" => "0", "internal_scheduled" => 19, "external_scheduled" => 19, "internal_unscheduled" => 99, "external_unscheduled" => 99);
   $data[] = array("name" => "meta8", "parent" => "1", "internal_scheduled" => 20, "external_scheduled" => 20, "internal_unscheduled" => 16, "external_unscheduled" => 15);
   $data[] = array("name" => "meta9", "parent" => "1", "internal_scheduled" => 21, "external_scheduled" => 21, "internal_unscheduled" => 17, "external_unscheduled" => 16);
   $data[] = array("name" => "meta10", "parent" => "1", "internal_scheduled" => 22, "external_scheduled" => 22, "internal_unscheduled" => 18, "external_unscheduled" => 17);
   $data[] = array("name" => "date_priv_notes", "parent" => "1", "internal_scheduled" => 23, "external_scheduled" => 23, "internal_unscheduled" => 19, "external_unscheduled" => 18);
   $data[] = array("name" => "entry_presenter", "parent" => "0", "internal_scheduled" => 99, "external_scheduled" => 99, "internal_unscheduled" => 99, "external_unscheduled" => 99);
   $data[] = array("name" => "entry_presenter_type", "parent" => "0", "internal_scheduled" => 99, "external_scheduled" => 99, "internal_unscheduled" => 99, "external_unscheduled" => 99);
   $data[] = array("name" => "entry_reg_require", "parent" => "0", "internal_scheduled" => 99, "external_scheduled" => 99, "internal_unscheduled" => 99, "external_unscheduled" => 99);
   $data[] = array("name" => "entry_open_to_public", "parent" => "0", "internal_scheduled" => 99, "external_scheduled" => 99, "internal_unscheduled" => 99, "external_unscheduled" => 99);
   $data[] = array("name" => "entry_visible_to_public", "parent" => "0", "internal_scheduled" => 99, "external_scheduled" => 99, "internal_unscheduled" => 99, "external_unscheduled" => 99);
   $data[] = array("name" => "entry_priv_notes", "parent" => "0", "internal_scheduled" => 99, "external_scheduled" => 99, "internal_unscheduled" => 99, "external_unscheduled" => 99);
//   $data[] = array("name" => "entry_cancelled", "parent" => "0", "internal_scheduled" => 99, "external_scheduled" => 99, "internal_unscheduled" => 99, "external_unscheduled" => 99);
   $data[] = array("name" => "date_category", "parent" => "1", "internal_scheduled" => 99, "external_scheduled" => 99, "internal_unscheduled" => 99, "external_unscheduled" => 99);
   $data[] = array("name" => "date_seats_expected", "parent" => "1", "internal_scheduled" => 99, "external_scheduled" => 99, "internal_unscheduled" => 99, "external_unscheduled" => 99);
//   $data[] = array("name" => "date_upload_image", "parent" => "1", "internal_scheduled" => 99, "external_scheduled" => 99, "internal_unscheduled" => 99, "external_unscheduled" => 99);
//   $data[] = array("name" => "date_upload_file", "parent" => "1", "internal_scheduled" => 99, "external_scheduled" => 99, "internal_unscheduled" => 99, "external_unscheduled" => 99);
//   $data[] = array("name" => "date_cancelled", "parent" => "1", "internal_scheduled" => 99, "external_scheduled" => 99, "internal_unscheduled" => 99, "external_unscheduled" => 99);

   $types = array("name" => "text", "parent" => "text",
      "internal_scheduled" => "integer", "external_scheduled" => "integer",
      "internal_unscheduled" => "integer", "external_unscheduled" => "integer");
   pem_add_rows("field_order", $data, $types);
} // END pem_populate_field_order

function pem_populate_meta()
{
   $cosponsor = array(
      "name1" => array(__("Co-Sponsor:"), 1, ""),
      "name2" => array(__("Co-Sponsor Contact:"), 1, ""),
      "street1" => array("", 0, ""),
      "street2" => array("", 0, ""),
      "city" => array("", 0, ""),
      "state" => array("", 0, ""),
      "postal" => array("", 0, ""),
      "phone1" => array(__("Co-Sponsor Phone:"), 1, ""),
      "phone2" => array("", 0, ""),
      "email" => array(__("Co-Sponsor Email:"), 1, ""),
      "website" => array("", 0, "")
   );
   $staffcontact = array(
      "name1" => array(__("Staff or Desk Contact:"), 2, ""),
      "name2" => array("", 0, ""),
      "street1" => array("", 0, ""),
      "street2" => array("", 0, ""),
      "city" => array("", 0, ""),
      "state" => array("", 0, ""),
      "postal" => array("", 0, ""),
      "phone1" => array(__("Contact Phone:"), 2, ""),
      "phone2" => array("", 0, ""),
      "email" => array(__("Contact Email:"), 1, ""),
      "website" => array("", 0, "")
   );
   $publiccontact = array(
      "name1" => array(__("Contact Person:"), 2, ""),
      "name2" => array("", 0, ""),
      "street1" => array(__("Contact Address:"), 2, ""),
      "street2" => array("", 0, ""),
      "city" => array(__("City:"), 2, ""),
      "state" => array(__("State:"), 2, ""),
      "postal" => array(__("Zip:"), 2, ""),
      "phone1" => array(__("Contact Phone:"), 2, ""),
      "phone2" => array("", 0, ""),
      "email" => array(__("Contact Email:"), 1, ""),
      "website" => array("", 0, "")
   );
   $secondcontact = array(
      "name1" => array(__("Second Contact Name:"), 2, ""),
      "name2" => array("", 0, ""),
      "street1" => array("", 0, ""),
      "street2" => array("", 0, ""),
      "city" => array("", 0, ""),
      "state" => array("", 0, ""),
      "postal" => array("", 0, ""),
      "phone1" => array(__("Second Contact Phone:"), 2, ""),
      "phone2" => array("", 0, ""),
      "email" => array(__("Second Contact Email:"), 1, ""),
      "website" => array("", 0, "")
   );
   $registrationcontact = array(
      "name1" => array(__("First Name:"), 2, ""),
      "name2" => array(__("Last Name:"), 2, ""),
      "street1" => array(__("Address:"), 2, ""),
      "street2" => array("&nbsp;", 1, ""),
      "city" => array(__("City:"), 2, ""),
      "state" => array(__("State:"), 2, ""),
      "postal" => array(__("Zip:"), 2, ""),
      "phone1" => array(__("Phone:"), 1, ""),
      "phone2" => array("", 0, ""),
      "email" => array(__("Email:"), 1, ""),
      "website" => array("", 0, "")
   );

   $data[] = array("meta_name" => __("Co-Sponsor"), "meta_description" => "", "meta_type" => "contact", "meta_parent" => "0", "internal_scheduled" => "1", "external_scheduled" => "0", "internal_unscheduled" => "0", "external_unscheduled" => "0", "value" => serialize($cosponsor), "status" => "1");
   $data[] = array("meta_name" => __("Staff Contact"), "meta_description" => "", "meta_type" => "contact", "meta_parent" => "0", "internal_scheduled" => 1, "external_scheduled" => 0, "internal_unscheduled" => 0, "external_unscheduled" => 0, "value" => serialize($staffcontact), "status" => "1");
   $data[] = array("meta_name" => __("Public Contact"), "meta_description" => "", "meta_type" => "contact", "meta_parent" => "0", "internal_scheduled" => 0, "external_scheduled" => 1, "internal_unscheduled" => 0, "external_unscheduled" => 0, "value" => serialize($publiccontact), "status" => "1");
   $data[] = array("meta_name" => __("Second Contact"), "meta_description" => "", "meta_type" => "contact", "meta_parent" => "0", "internal_scheduled" => 0, "external_scheduled" => 1, "internal_unscheduled" => 0, "external_unscheduled" => 0, "value" => serialize($secondcontact), "status" => "1");

   $data[] = array("meta_name" => __("Group Name"), "meta_description" => "", "meta_type" => "textinput", "meta_parent" => "0", "internal_scheduled" => 0, "external_scheduled" => 2, "internal_unscheduled" => 0, "external_unscheduled" => 0, "value" => 'a:1:{s:11:"input_label";s:11:"Group Name:";}', "status" => "1");

   $data[] = array("meta_name" => __("Registration Contact"), "meta_description" => "", "meta_type" => "contact", "meta_parent" => "0", "internal_scheduled" => 0, "external_scheduled" => 0, "internal_unscheduled" => 0, "external_unscheduled" => 0, "value" => serialize($registrationcontact), "status" => "1");

   $types = array("meta_name" => "text", "meta_description" => "text",
      "meta_type" => "text", "meta_parent" => "text",
      "internal_scheduled" => "integer", "external_scheduled" => "integer",
      "internal_unscheduled" => "integer", "external_unscheduled" => "integer",
      "value" => "clob", "status" => "text");
   pem_add_rows("meta", $data, $types);
} // END pem_populate_meta

function pem_populate_presenters($install_seed = false)
{
   $presenter = __("Presenter:");
   $instructor = __("Instructor:");
   $special_guest = __("Special Guest:");
   $moderator = __("Moderator:");
   $entertainer = __("Entertainer:");
   $mc = __("Master of Ceremonies:");

   $data[] = array("presenter_type" => $presenter, "internal_scheduled" => 1, "external_scheduled" => 1, "internal_unscheduled" => 0, "external_unscheduled" => 0, "status" => "1");
   if ($install_seed)
   {
      $data[] = array("presenter_type" => $instructor, "internal_scheduled" => 1, "external_scheduled" => 1, "internal_unscheduled" => 0, "external_unscheduled" => 0, "status" => "1");
      $data[] = array("presenter_type" => $special_guest, "internal_scheduled" => 1, "external_scheduled" => 1, "internal_unscheduled" => 0, "external_unscheduled" => 0, "status" => "1");
      $data[] = array("presenter_type" => $moderator, "internal_scheduled" => 1, "external_scheduled" => 1, "internal_unscheduled" => 0, "external_unscheduled" => 0, "status" => "1");
      $data[] = array("presenter_type" => $entertainer, "internal_scheduled" => 1, "external_scheduled" => 1, "internal_unscheduled" => 0, "external_unscheduled" => 0, "status" => "1");
      $data[] = array("presenter_type" => $mc, "internal_scheduled" => 1, "external_scheduled" => 1, "internal_unscheduled" => 0, "external_unscheduled" => 0, "status" => "1");
   }
   $types = array("presenter_type" => "text",
      "internal_scheduled" => "boolean", "external_scheduled" => "boolean",
      "internal_unscheduled" => "boolean", "external_unscheduled" => "boolean",
      "status" => "text");
   pem_add_rows("presenters", $data, $types);
} // END pem_populate_presenters


function pem_populate_scheduling_profiles()
{
   $default = array(
      "limit_book_range" => false,
      "book_range_type" => "", // between/advance
      "book_begin" => "", // year
      "book_end" => "", // year
      "book_advance" => "", // months
      "restrict_to_open" => true,
      "open_begin_0" => "12:00:00",
      "open_end_0" => "17:00:00",
      "open_begin_1" => "10:00:00",
      "open_end_1" => "21:00:00",
      "open_begin_2" => "10:00:00",
      "open_end_2" => "21:00:00",
      "open_begin_3" => "10:00:00",
      "open_end_3" => "21:00:00",
      "open_begin_4" => "10:00:00",
      "open_end_4" => "21:00:00",
      "open_begin_5" => "10:00:00",
      "open_end_5" => "20:00:00",
      "open_begin_6" => "10:00:00",
      "open_end_6" => "18:00:00",
      "default_buffer_time" => 0.5,
      "buffer_min" => false,
      "buffer_overlap" => false,
      "buffer_inline" => false,
      "default_setup_time" => 0,
      "setup_min" => false,
      "setup_inline" => false
    );
   // Periods are a future feature, stubbed off for now.
   // "enable_periods" => false,
   // "default_period_length" => 30,

   $data[] = array("profile_name" => __("Default"), "date_begin" => "2000-01-01", "date_end" => "2050-01-01", "description" => __("Default scheduling profile."), "profile" => serialize($default), "status" => "1");
   $types = array("profile_name" => "text", "date_begin" => "date", "date_end" => "date",
      "description" => "text", "profile" => "clob", "status" => "text");
   pem_add_rows("scheduling_profiles", $data, $types);
} // END pem_populate_scheduling_profiles

function pem_populate_settings($pem_url, $admin_email, $admin_name = "", $pem_title = "", $pem_owner = "")
{
   if (empty($admin_name)) $admin_name = __("PEM Administrator");
   if (empty($pem_title)) $pem_title = "phxEventManager";
   // General Information
   $data[] = array("name" => "pem_title", "value" => $pem_title);
   $data[] = array("name" => "pem_owner", "value" => $pem_owner);
   $data[] = array("name" => "title_img", "value" => "");
   $data[] = array("name" => "admin_name", "value" => $admin_name);
   $data[] = array("name" => "admin_email", "value" => $admin_email);
   $data[] = array("name" => "pem_url", "value" => $pem_url);
   $data[] = array("name" => "content_charset", "value" => "UTF-8");
  // Date & Time Formats
   $data[] = array("name" => "time_format", "value" => "g:i a.");
   $data[] = array("name" => "date_format", "value" => "F j, Y");
   $data[] = array("name" => "week_begin", "value" => 0);
   $data[] = array("name" => "minute_increment", "value" => 15);
   $data[] = array("name" => "view_week_number", "value" => 0);
   $data[] = array("name" => "display_duration", "value" => 12);
   // User Account Requirements
   $data[] = array("name" => "user_login_min", "value" => 5);
   $data[] = array("name" => "user_pass_min", "value" => 6);
   // Interface Defaults
   $data[] = array("name" => "pem_theme", "value" => "red");
   $data[] = array("name" => "default_view", "value" => "month-cal");
   // Field Defaults
   $data[] = array("name" => "default_phone", "value" => "");
   $data[] = array("name" => "default_email", "value" => "");
   $data[] = array("name" => "default_city", "value" => "");
   $data[] = array("name" => "state_select", "value" => true);
   $data[] = array("name" => "default_state", "value" => "");
   // Registration Settings
   $data[] = array("name" => "reg_contact_meta", "value" => 12);
   $data[] = array("name" => "reg_email", "value" => $admin_email);
   $data[] = array("name" => "reg_subject", "value" => "[Event Registration]");
   $data[] = array("name" => "reg_onreg_notify", "value" => 1);       // send email confirmation of event info on registration
   $data[] = array("name" => "reg_onreg_msg", "value" => __("You have been registered to attend the event below.  If you have any other questions, please contact...."));
   $data[] = array("name" => "reg_onreg_waitmsg", "value" => __("You have been added to the waitlist for the event below.  If you have any other questions, please contact...."));
   $data[] = array("name" => "reg_waitlist_notify", "value" => 1);
   $data[] = array("name" => "reg_waitlist_msg", "value" => __("A registration opening has become available for the event below.  Your waitlist entry has been automatically converted, and you are now registered for the event.  If you can no longer attend the event or have any other questions, please contact...."));
   $data[] = array("name" => "reg_onchange_notify", "value" => 1);
   $data[] = array("name" => "reg_onchange_msg", "value" => __("Changes have been made to the event below to which you are currently registered.  If you have any other questions, please contact...."));
   $data[] = array("name" => "reg_remind_when1", "value" => 3);  // days before event to send reminder
   $data[] = array("name" => "reg_remind_when2", "value" => 14); // days before event to send reminder
   $data[] = array("name" => "reg_remind_msg", "value" => __("This is a reminder message sent for the event below. If you have any other questions, please contact...."));

   for ($i = 0; $i < count($data); $i++)
   {
      $data[$i]["value"] = serialize($data[$i]["value"]);
   }

   $types = array("name" => "text", "value" => "clob");
   pem_add_rows("settings", $data, $types);
} // END pem_populate_settings

function pem_populate_spaces()
{
   $first_floor_schedule = 'a:22:{s:15:"custom_schedule";s:1:"1";s:18:"time_before_open_0";d:0;s:19:"time_after_closed_0";d:1.5;s:21:"start_before_closed_0";d:1;s:18:"time_before_open_1";d:2;s:19:"time_after_closed_1";d:1.5;s:21:"start_before_closed_1";d:1;s:18:"time_before_open_2";d:2;s:19:"time_after_closed_2";d:1.5;s:21:"start_before_closed_2";d:1;s:18:"time_before_open_3";d:2;s:19:"time_after_closed_3";d:1.5;s:21:"start_before_closed_3";d:1;s:18:"time_before_open_4";d:2;s:19:"time_after_closed_4";d:1.5;s:21:"start_before_closed_4";d:1;s:18:"time_before_open_5";d:2;s:19:"time_after_closed_5";d:1.5;s:21:"start_before_closed_5";d:1;s:18:"time_before_open_6";d:1;s:19:"time_after_closed_6";d:1.5;s:21:"start_before_closed_6";d:1;}';
   $second_floor_schedule = 'a:22:{s:15:"custom_schedule";s:1:"1";s:18:"time_before_open_0";d:0;s:19:"time_after_closed_0";d:0;s:21:"start_before_closed_0";d:1;s:18:"time_before_open_1";d:2;s:19:"time_after_closed_1";d:0;s:21:"start_before_closed_1";d:1;s:18:"time_before_open_2";d:2;s:19:"time_after_closed_2";d:0;s:21:"start_before_closed_2";d:1;s:18:"time_before_open_3";d:2;s:19:"time_after_closed_3";d:0;s:21:"start_before_closed_3";d:1;s:18:"time_before_open_4";d:2;s:19:"time_after_closed_4";d:0;s:21:"start_before_closed_4";d:1;s:18:"time_before_open_5";d:2;s:19:"time_after_closed_5";d:0;s:21:"start_before_closed_5";d:1;s:18:"time_before_open_6";d:1;s:19:"time_after_closed_6";d:0;s:21:"start_before_closed_6";d:1;}';

   $data[] = array("space_name" => "Meeting Room A", "space_name_short" => "Room A", "space_description" => "37' x 40', Room, Rooms A, B, and C can be reserved together", "space_popup" => "room-a.php", "space_contact" => "", "area" => 1, "supply_profile" => 2, "optional_supplies" => 4, "capacity" => 200, "show_boxes" => 6, "scheduling_profile" => $first_floor_schedule, "show_day_view" => 1, "internal_scheduled" => 1, "external_scheduled" => 1, "internal_unscheduled" => 0, "external_unscheduled" => 0, "status" => "1");
   $data[] = array("space_name" => "Meeting Room B", "space_name_short" => "Room B", "space_description" => "28' x 12', Room, Rooms A, B, and C can be reserved together", "space_popup" => "room-b.php", "space_contact" => "", "area" => 1, "supply_profile" => 3, "optional_supplies" => 4, "capacity" => 26, "show_boxes" => 8, "scheduling_profile" => $first_floor_schedule, "show_day_view" => 1, "internal_scheduled" => 1, "external_scheduled" => 1, "internal_unscheduled" => 0, "external_unscheduled" => 0, "status" => "1");
   $data[] = array("space_name" => "Meeting Room C", "space_name_short" => "Room C", "space_description" => "13' x 12', Room, Rooms A, B, and C can be reserved together", "space_popup" => "room-c.php", "space_contact" => "", "area" => 1, "supply_profile" => "", "optional_supplies" => "", "capacity" => 10, "show_boxes" => 10, "scheduling_profile" => $first_floor_schedule, "show_day_view" => 1, "internal_scheduled" => 1, "external_scheduled" => 1, "internal_unscheduled" => 0, "external_unscheduled" => 0, "status" => "1");
   $data[] = array("space_name" => "Meeting Room D (2nd Floor)", "space_name_short" => "Room D", "space_description" => "19' x 28' Room", "space_popup" => "", "space_contact" => "room-d.php", "area" => 1, "supply_profile" => "", "optional_supplies" => "", "capacity" => 24, "show_boxes" => 1, "scheduling_profile" => $second_floor_schedule, "show_day_view" => 1, "internal_scheduled" => 1, "external_scheduled" => 1, "internal_unscheduled" => 0, "external_unscheduled" => 0, "status" => "1");
   $data[] = array("space_name" => "Meeting Room E (2nd Floor)", "space_name_short" => "Room E", "space_description" => "16' x 18' Room", "space_popup" => "", "space_contact" => "room-e.php", "area" => 1, "supply_profile" => "", "optional_supplies" => "", "capacity" => 24, "show_boxes" => 0, "scheduling_profile" => $second_floor_schedule, "show_day_view" => 1, "internal_scheduled" => 1, "external_scheduled" => 1, "internal_unscheduled" => 0, "external_unscheduled" => 0, "status" => "1");
   $data[] = array("space_name" => "Large Display", "space_name_short" => "Lg Display", "space_description" => "Large display case by mosaic entrance.", "space_popup" => "", "space_contact" => "", "area" => 2, "supply_profile" => "", "optional_supplies" => "", "capacity" => "", "show_boxes" => 1, "scheduling_profile" => "", "show_day_view" => 0, "internal_scheduled" => 0, "external_scheduled" => 0, "internal_unscheduled" => 1, "external_unscheduled" => 0, "status" => "1");
   $data[] = array("space_name" => "Small Display", "space_name_short" => "Sm Display", "space_description" => "", "space_popup" => "", "space_contact" => "", "area" => 2, "supply_profile" => "", "optional_supplies" => "", "capacity" => "", "show_boxes" => 0, "scheduling_profile" => "", "show_day_view" => 0, "internal_scheduled" => 0, "external_scheduled" => 0, "internal_unscheduled" => 1, "external_unscheduled" => 0, "status" => "1");

   $types = array("space_name" => "text", "space_description" => "text",
      "space_popup" => "text", "space_contact" => "text", "area" => "integer",
      "supply_profile" => "integer", "optional_supplies" => "integer",
      "capacity" => "integer", "show_boxes" => "boolean", "scheduling_profile" => "clob",
      "internal_scheduled" => "boolean", "external_scheduled" => "boolean",
      "internal_unscheduled" => "boolean", "external_unscheduled" => "boolean",
      "status" => "text");
   pem_add_rows("spaces", $data, $types);
} // END pem_populate_spaces

function pem_populate_supplies()
{
   $data[] = array("supply_name" => __("Chairs"), "internal_scheduled" => 1, "external_scheduled" => 1, "internal_unscheduled" => 0, "external_unscheduled" => 0, "status" => 1);
   $data[] = array("supply_name" => __("Computer"), "internal_scheduled" => 1, "external_scheduled" => 1, "internal_unscheduled" => 0, "external_unscheduled" => 0, "status" => 1);
   $data[] = array("supply_name" => __("Conference Table"), "internal_scheduled" => 1, "external_scheduled" => 1, "internal_unscheduled" => 0, "external_unscheduled" => 0, "status" => 1);
   $data[] = array("supply_name" => __("Easel"), "internal_scheduled" => 1, "external_scheduled" => 1, "internal_unscheduled" => 0, "external_unscheduled" => 0, "status" => 1);
   $data[] = array("supply_name" => __("Flip Chart"), "internal_scheduled" => 1, "external_scheduled" => 1, "internal_unscheduled" => 0, "external_unscheduled" => 0, "status" => 1);
   $data[] = array("supply_name" => __("Podium"), "internal_scheduled" => 1, "external_scheduled" => 1, "internal_unscheduled" => 0, "external_unscheduled" => 0, "status" => 1);
   $data[] = array("supply_name" => __("Presentation Podium with Microphone"), "internal_scheduled" => 1, "external_scheduled" => 1, "internal_unscheduled" => 0, "external_unscheduled" => 0, "status" => 1);
   $data[] = array("supply_name" => __("Table - 3' x 6'"), "internal_scheduled" => 1, "external_scheduled" => 1, "internal_unscheduled" => 0, "external_unscheduled" => 0, "status" => 1);
   $data[] = array("supply_name" => __("Table - Round 5'"), "internal_scheduled" => 1, "external_scheduled" => 1, "internal_unscheduled" => 0, "external_unscheduled" => 0, "status" => 1);
   $data[] = array("supply_name" => __("TV with VCR and DVD Player"), "internal_scheduled" => 1, "external_scheduled" => 1, "internal_unscheduled" => 0, "external_unscheduled" => 0, "status" => 1);
   $types = array("supply_name" => "text", "internal_scheduled" => "boolean", "external_scheduled" => "boolean", "internal_unscheduled" => "boolean", "external_unscheduled" => "boolean", "status" => "text");
   pem_add_rows("supplies", $data, $types);
} // END pem_populate_supplies

function pem_populate_supply_profiles()
{
   $data[] = array("profile_name" => __("Lab Default"), "description" => "", "profile" => "a:4:{i:1;i:20;i:2;i:20;i:5;i:1;i:7;i:1;}", "status" => "1");
   $data[] = array("profile_name" => __("Room A Default"), "description" => __("Room A is a medium-size conference room.  The standard setup is 16 chairs around 6 tables together in the center of the room. 10 extra chairs are set around the perimeter of the room."), "profile" => "a:2:{i:1;i:26;i:8;i:6;}", "status" => "1");
   $data[] = array("profile_name" => __("Room B Default"), "description" => __("Room B is a small conference room.  The standard setup is 8 chairs around a round table and 2 extra chairs."), "profile" => "a:2:{i:1;i:10;i:9;i:1;}", "status" => "1");
   $data[] = array("profile_name" => __("Room Options"), "description" => __("Optional equipment is available but must be reserved in advance.  The room must be reset to the standard setup."), "profile" => "a:3:{i:4;i:1;i:5;i:1;i:10;i:1;}", "status" => "1");
   $types = array("profile_name" => "text", "description" => "text", "profile" => "clob", "status" => "text");
   pem_add_rows("supply_profiles", $data, $types);
} // END pem_populate_supply_profiles


function pem_populate_views()
{
   $day_calendar = __("Day Calendar");
   $day_list = __("Day List");
   $week_calendar = __("Week Calendar");
   $week_list = __("Week List");
   $month_calendar = __("Month Calendar");
   $month_list = __("Month List");
   $year_calendar = __("Year Calendar");
   $year_list = __("Year List");

   $data[] = array("view_name" => $day_calendar, "show_event_name" => true,
      "event_name_length" => 0, "show_time_begin" => true,
      "show_time_end" => true, "show_area" => false, "show_space" => false,
      "show_category" => false, "show_image" => false, "max_listings" => 0,
      "highlight_today" => true, "show_minical" => false, "minical_format" => 1,
      "minical_size" => 0, "minical_highlight_today" => true,
      "show_internal_scheduled" => true, "show_external_scheduled" => true,
      "show_internal_unscheduled" => false,
      "show_external_unscheduled" => false, "show_category_box" => false,
      "show_area_box" => false, "show_space_box" => true,
      "category_box_format" => 0, "area_box_format" => 1,
      "space_box_format" => 1, "status" => "1");
   $data[] = array("view_name" => $day_list, "show_event_name" => true,
      "event_name_length" => 12, "show_time_begin" => true,
      "show_time_end" => true, "show_area" => false, "show_space" => false,
      "show_category" => true, "show_image" => false, "max_listings" => 0,
      "highlight_today" => false, "show_minical" => false, "minical_format" => 1,
      "minical_size" => 0, "minical_highlight_today" => false,
      "show_internal_scheduled" => true, "show_external_scheduled" => true,
      "show_internal_unscheduled" => false,
      "show_external_unscheduled" => false, "show_category_box" => false,
      "show_area_box" => false, "show_space_box" => true,
      "category_box_format" => 0, "area_box_format" => 1,
      "space_box_format" => 1, "status" => "1");
   $data[] = array("view_name" => $week_calendar, "show_event_name" => true,
      "event_name_length" => 0, "show_time_begin" => true,
      "show_time_end" => true, "show_area" => false, "show_space" => false,
      "show_category" => false, "show_image" => false, "max_listings" => 0,
      "highlight_today" => true, "show_minical" => false, "minical_format" => 1,
      "minical_size" => 0, "minical_highlight_today" => true,
      "show_internal_scheduled" => true, "show_external_scheduled" => true,
      "show_internal_unscheduled" => false,
      "show_external_unscheduled" => false, "show_category_box" => false,
      "show_area_box" => false, "show_space_box" => true,
      "category_box_format" => 0, "area_box_format" => 1,
      "space_box_format" => 1, "status" => "1");
   $data[] = array("view_name" => $week_list, "show_event_name" => true,
      "event_name_length" => 0, "show_time_begin" => true,
      "show_time_end" => true, "show_area" => false, "show_space" => true,
      "show_category" => false, "show_image" => true, "max_listings" => 0,
      "highlight_today" => false, "show_minical" => false, "minical_format" => 1,
      "minical_size" => 0, "minical_highlight_today" => true,
      "show_internal_scheduled" => true, "show_external_scheduled" => true,
      "show_internal_unscheduled" => false,
      "show_external_unscheduled" => false, "show_category_box" => false,
      "show_area_box" => false, "show_space_box" => true,
      "category_box_format" => 0, "area_box_format" => 1,
      "space_box_format" => 1, "status" => "1");
   $data[] = array("view_name" => $month_calendar, "show_event_name" => true,
      "event_name_length" => 30, "show_time_begin" => true,
      "show_time_end" => false, "show_area" => false, "show_space" => false,
      "show_category" => false, "show_image" => false, "max_listings" => 20,
      "highlight_today" => true, "show_minical" => true, "minical_format" => 0,
      "minical_size" => 0, "minical_highlight_today" => true,
      "show_internal_scheduled" => true, "show_external_scheduled" => true,
      "show_internal_unscheduled" => true,
      "show_external_unscheduled" => true, "show_category_box" => true,
      "show_area_box" => false, "show_space_box" => true,
      "category_box_format" => 0, "area_box_format" => 0,
      "space_box_format" => 1, "status" => "1");
   $data[] = array("view_name" => $month_list, "show_event_name" => true,
      "event_name_length" => 0, "show_time_begin" => true,
      "show_time_end" => true, "show_area" => false, "show_space" => true,
      "show_category" => false, "show_image" => true, "max_listings" => 30,
      "highlight_today" => false, "show_minical" => true, "minical_format" => 1,
      "minical_size" => 0, "minical_highlight_today" => true,
      "show_internal_scheduled" => true, "show_external_scheduled" => true,
      "show_internal_unscheduled" => true,
      "show_external_unscheduled" => true, "show_category_box" => true,
      "show_area_box" => false, "show_space_box" => true,
      "category_box_format" => 0, "area_box_format" => 1,
      "space_box_format" => 1, "status" => "1");
   $data[] = array("view_name" => $year_calendar, "show_event_name" => true,
      "event_name_length" => 0, "show_time_begin" => true,
      "show_time_end" => true, "show_area" => false, "show_space" => true,
      "show_category" => false, "show_image" => false, "max_listings" => 10,
      "highlight_today" => true, "show_minical" => false, "minical_format" => 1,
      "minical_size" => false, "minical_highlight_today" => true,
      "show_internal_scheduled" => true, "show_external_scheduled" => true,
      "show_internal_unscheduled" => false,
      "show_external_unscheduled" => false, "show_category_box" => false,
      "show_area_box" => false, "show_space_box" => true,
      "category_box_format" => 0, "area_box_format" => 1,
      "space_box_format" => 1, "status" => "1");
   $data[] = array("view_name" => $year_list, "show_event_name" => true,
      "event_name_length" => 0, "show_time_begin" => true,
      "show_time_end" => true, "show_area" => false, "show_space" => true,
      "show_category" => false, "show_image" => false, "max_listings" => 10,
      "highlight_today" => false, "show_minical" => false, "minical_format" => 1,
      "minical_size" => 0, "minical_highlight_today" => true,
      "show_internal_scheduled" => true, "show_external_scheduled" => true,
      "show_internal_unscheduled" => true,
      "show_external_unscheduled" => true, "show_category_box" => true,
      "show_area_box" => true, "show_space_box" => true,
      "category_box_format" => 1, "area_box_format" => 1,
      "space_box_format" => 1, "status" => "1");

   $types = array("view_name" => "text", "show_event_name" => "boolean",
      "event_name_length" => "integer", "show_time_begin" => "boolean",
      "show_time_end" => "boolean", "show_area" => "boolean",
      "show_space" => "boolean", "show_category" => "boolean",
      "show_image" => "boolean", "max_listings" => "integer",
      "highlight_today" => "boolean", "show_minical" => "boolean",
      "minical_format" => "integer", "minical_size" => "integer",
      "minical_highlight_today" => "boolean", "show_unscheduled" => "boolean",
      "show_category_box" => "boolean", "show_area_box" => "boolean",
      "show_space_box" => "boolean", "category_box_format" => "integer",
      "area_box_format" => "integer", "space_box_format" => "integer",
      "status" => "text");
   pem_add_rows("views", $data, $types);
} // END pem_populate_views


/*
Resources:
intevent - Internal Events
extevent - External Events
intunsched - Internal Side Box
extunsched - External Side Box
regs - Registrations
reports - Reports
restypes - Resource Types
users - Users
backend - Admin Backend

Access Keys:
0 - Anon View
1 - Anon Submit
2 - Anon Edit All
3 - Anon Approve All
4 - Anon Delete All
5 - Admin View
6 - Admin Submit
7 - Admin Edit Own
8 - Admin Edit All
9 - Admin Approve Own
10 - Admin Approve Others
11 - Admin Approve All
12 - Admin Delete Own
13 - Admin Delete All
*/
function pem_populate_users($admin_email)
{
   // Set up admin and public users
   $random_password = substr(md5(uniqid(microtime())), 0, 6);
   //$random_password = md5("password");
   $nicename_array = explode("@", $admin_email);
   $nicename = $nicename_array[0];

   $anonymous_users = __("Anonymous Users");
   auth_add_user("admin", md5($random_password), $nicename, $admin_email, 1, 1);
   auth_add_user("public", "", $anonymous_users, "", 2, 1);
   return $random_password;
} // END pem_populate_users


// echos a simple form submission script.
function pem_install_submit($label = "")
{
   if (empty($label)) $label = __("Install phxEventManager &raquo;");

   $ret = '<ul class="installsubmit">';
   $ret .= '<li><a href="#" onclick="document.setupform.submit();"><span>' . $label . '</span></a></li>';
   $ret .= '</ul>' . "\n";
   echo $ret;
}


?>