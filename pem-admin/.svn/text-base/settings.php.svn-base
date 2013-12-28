<?php
/* ========================== FILE INFORMATION ================================= 
phxEventManager :: settings.php

This file provides web-based administration of main phxEventManager settings.
============================================================================= */

$pagetitle = "Settings Administration";
$navigation = "administration";
$cache_set = array("current_navigation" => "backend");
include_once "../pem-includes/header.php";

$current_settings = pem_get_settings();
echo_settings_form($current_settings);

include ABSPATH . PEMINC . "/footer.php";

// $settings is a keyed array containing all values needed by the form.
function echo_settings_form($data, $error = "")
{
   pem_fieldset_begin(__("Interface Defaults"));

   pem_form_submit("settingsform");
   pem_fieldset_end();

   pem_form_submit("settingsform");
   pem_fieldset_end();

   pem_fieldset_begin(__("Field Defaults"));
}

?>