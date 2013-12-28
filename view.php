<?php
/* ========================== FILE INFORMATION =================================
phxEventManager :: view.php

Controlling file for viewing event information.  Content using the generic
event navigation and header is called with include files.  Other event content
is provided with a redirect where custom headers are required.
============================================================================= */

$pagetitle = "View Event";
$navigation = "event";
// $page_access_requirement = "View Event";
// $cache_set = array("current_navigation" => "meta");
include_once "pem-includes/header.php";

switch(true)
{
   case (pem_cache_get("current_eview") == "event"):
      include_once "event.php";
      break;
   case (pem_cache_get("current_eview") == "edit"):
      header("Location: edit-event.php");
      break;
   case (pem_cache_get("current_eview") == "dates"):
      include_once "dates.php";
      break;
   case (pem_cache_get("current_eview") == "add"):
      header("Location: add-date.php");
      break;
   case (pem_cache_get("current_eview") == "regs"):
      include_once "registration.php";
      break;
}

include ABSPATH . PEMINC . "/footer.php";
?>