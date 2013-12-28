<?php
// load Date helper class
// MDB2::loadFile('Date');
// $stmt->execute(array('name' => 'hello', 'date' => MDB2_Date::mdbToday()));

$pem_installing = true;
$pagetitle = "Installation";
include_once "../pem-includes/header.php";
include_once ABSPATH . PEMINC . "/functions-install.php";

$current_error = "";
$ownerisadmin = (isset($install_name)) ? true : false;
if (isset($s))
{
   if (empty($install_email)) $current_error = __("Please type your email address.");
   elseif (!is_email($install_email)) $current_error = __("Please check your email address.  It does not appear to be a valid format.");
}
if (!isset($s))
{
   echo '<p>' . __("Welcome to phxEventManager.  The install process is very easy.  Simply complete and submit the form below.  The title value will be displayed on every page at the top, and a valid email address required.") . '</p>' . "\n";
   // You may want to peruse the <a href="%s">ReadMe documentation</a> at your leisure.'), '../readme.html');
}
elseif (!empty($current_error))
{
   echo '<p>' . __("A problem has been detected during the instal process. Please review the error(s) listed below and remedy the problem before continuing.") . '</p>' . "\n";
   echo '<div class="error">' . $current_error . '</div><br />' . "\n";
}
if (!isset($s) OR !empty($current_error))
{
   pem_form_begin(array("nameid" => "setupform", "action" => "install.php?s=f", "class" => "setupform"));
   pem_field_label(array("default" => __("Calendar Title:"), "for" => "install_title"));
   pem_text_input(array("name" => "install_title", "value" => $install_title, "size" => 25, "maxlength" => 25));
   echo '<br />' . "\n";
   pem_field_label(array("default" => __("Owner Name:"), "for" => "install_owner"));
   pem_text_input(array("name" => "install_owner", "value" => $install_owner, "size" => 40, "maxlength" => 50));
   echo '<br />' . "\n";
   pem_field_label(array("default" => __("Your Email:"), "for" => "install_email"));
   pem_text_input(array("name" => "install_email", "value" => $install_email, "size" => 25, "maxlength" => 25));
   echo '<br />' . "\n";
   echo '<div style="margin:0 0 0 20px;">' . "\n";
   pem_checkbox(array("name" => "install_seed", "status" => $install_seed));
   pem_field_label(array("default" => __("Seed the new install with example content?"), "for" => "install_seed"));
   echo '<br />' . "\n";
   pem_checkbox(array("name" => "install_name", "status" => $ownerisadmin));
   pem_field_label(array("default" => __("Use Owner Name as the Administrator Name?"), "for" => "install_name"));
   echo '<br />&nbsp;&nbsp;&nbsp;&nbsp;';
   _e("(for system messages and email correspondence)");
   echo '</div>' . "\n";

   echo '<br />' . "\n";
   pem_install_submit();
   pem_form_end();
}
else
{
   $admin_name = ($ownerisadmin) ? $install_owner : "";
   $admin_url = str_replace("install.php", "", $REDIRECT_URL);
   $install_url = str_replace("pem-admin/", "", $admin_url);

   echo '<h1>' . __("Finished!") . '</h1>' . "\n";
   echo '<p>' . __("Your phxEventManager system is now installed.") . '</p>' . "\n";

   flush();

   $pemdb =& mdb2_connect($dsn, $options, "factory");
   if (PEAR::isError($pemdb)) die($pemdb->getMessage()); // check for error
   pem_make_tables();

   pem_populate_settings($install_url, $install_email, $admin_name, $install_title, $install_owner);
   $random_password = pem_populate_users($install_email);
   pem_populate_access_profiles($install_seed);
   pem_populate_categories($install_seed);
   pem_populate_presenters($install_seed);
   pem_populate_scheduling_profiles();
   pem_populate_views();
   pem_populate_field_behavior();
   pem_populate_field_order();
   if ($install_seed)
   {
      pem_populate_areas();
      pem_populate_meta();
      pem_populate_spaces();
      pem_populate_supplies();
      pem_populate_supply_profiles();
   }
   mdb2_disconnect($pemdb);

   $message_headers = 'From: "' . $install_title . '" <pem@' . $_SERVER['SERVER_NAME'] . '>';
   $message = sprintf(__("phxEventManager has been successfully set up at:

   %1\$s

You can log in to the administrator account with the following information:

Username: admin
Password: %2\$s

I hope you enjoy your event management system. Thanks!
--Kevin Hatch
phxEventManager.com
   "), $install_url, $random_password);

   @pem_mail($admin_email, __("New phxEventManager"), $message, $message_headers);

   // pem_cache_flush();

   printf(__('Now you can <a href="%1$s">log in</a> to the system using the information:'), $admin_url);
   echo "<br />\n";
   echo '<div class="indent">' . "\n";
   _e("Admin Login:");
   echo " <b>admin</b><br />\n";
   _e("Admin Password:");
   echo " <b>$random_password</b><br />\n";
   _e("Admin URL:");
   echo ' <a href="' . $admin_url . '">' . $admin_url . '</a><br />' . "\n";
   echo "</div>\n<p>";
   _e("Please note that password carefully.  It can be changed once you log in, but it was generated randomly, and if you lose it you will have to manually reset it in the database yourself or completely re-install phxEventManager.");
   echo '</p>' . "\n";
}

include ABSPATH . PEMINC . "/footer.php";
?>