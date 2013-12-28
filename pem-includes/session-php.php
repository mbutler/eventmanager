<?php
/* ========================== FILE INFORMATION =================================
phxEventManager :: session-php.php

Use PHP built-in sessions handling
To use this authentication scheme, set in pem-config.php:
   $auth["session"]  = "php";
============================================================================= */

session_start();

/*
login_redirect();
auth_redirect($resource, $acessreq);

if (!userAuthorized())
{
    printLoginForm('/admin/');

   exit();
   }
*/


//   $action = pem_cache_get("current_action");
// if (pem_cache_isset("current_action")) switch(pem_cache_get("current_action"))
if (isset($a)) switch($a)
{
   case "login":
      // Handeled in the header file
      break;
   case "logout":
      pem_logout();
      break;
   case "dologin":
      switch(true)
      {
      case (empty($new_user_login)):
         $login_error = __("You must submit a valid user login to continue.");
         break;
      case (empty($new_user_password)):
         $login_error = __("You must submit a valid password to continue.");
         break;
      default:
         $result = auth_validate_user($new_user_login, $new_user_password);
         switch(true)
         {
         case ($result == "validuser"):
            pem_set_login($new_user_login);
            pem_set_pass($new_user_password);
            auth_update_user_activity($new_user_login, $new_user_password);
            break;
         case ($result == "inactive"):
            $login_error = __("Your account exists but is inactive.  Contact your administrator for assistance.");
            break;
         case ($result == "badpass"):
             $login_error = __("You've submitted and invalid password. Please check your information and try again.");
            break;
         case ($result == "deleted"):
            $login_error = __("Your account was recently deleted.  Contact your administrator for assistance.");
            break;
         default:
            $login_error = __("The account could not be found. Please check your information and try again.");
         }
      }
      break;



}






if (isset($action) && ($Action == "SetName")) {
   /* First make sure the password is valid */
   if (($NewUserName != "") && (!authValidateUser($NewUserName, $NewUserPassword))) {
    print_header(0, 0, 0, 0);
    echo '<div class="bodycontent">';
    echo '<div class="entrybox">';
    echo '<div class="entrytitle">'.__('unknown_user').'</div>';
    echo '</div>';
    printLoginForm($TargetURL);
    echo '</div>';
    exit();
    }

   if (isset($_SESSION)) {
    $_SESSION["UserName"] = $NewUserName;
    $_SESSION["UserPassword"] = $NewUserPassword;
    }
   else {
    global $HTTP_SESSION_VARS;
    $HTTP_SESSION_VARS["UserName"] = $NewUserName;
    $HTTP_SESSION_VARS["UserPassword"] = $NewUserPassword;
    }

   header ("Location: $TargetURL"); /* Redirect browser to initial page */
   /* Note HTTP 1.1 mandates an absolute URL. Most modern browsers support relative URLs,
   * which allows to work around problems with DNS inconsistencies in the server name.
   * Anyway, if the browser cannot redirect automatically, the manual link below will work.
   */
   print_header(0, 0, 0, 0);
   echo '<div class="bodycontent">';
   echo '<div class="entrybox">';
   echo '<p>Please click <a href="'.$TargetURL.'">here</a> if you\'re not redirected automatically to the page you requested.</p>' . "\n";
   echo '</div>';
   echo '</div>';
   echo "</body></html>\n";
   exit();
   }

/* Target of the form with sets the URL argument "Action=QueryName".
 * Will eventually return to URL argument "TargetURL=whatever".
 */
if (isset($Action) && ($Action == "QueryName")) {
   print_header(0, 0, 0, 0);
   printLoginForm($TargetURL);
   exit();
   }


// Echos a log in/log off button.
function pem_echo_login($target = "", $loc = "header")
{
   $login = pem_get_login();
   if (empty($target))
   {
      global $REDIRECT_URL;
      $target = $REDIRECT_URL;
   }
   if ($loc == "header")
   {
      $form = "loginbuttonform";
      $class = "headersubmit";
   }
   else
   {
      $form = "loginbuttonform2";
      $class = "formsubmit";
   }
   if (!$login)
   {

      pem_form_begin(array("nameid" => $form, "action" => $target, "class" => $class));
      pem_hidden_input(array("name" => "a", "value" => "login"));
      pem_form_submit($form, __("Log In"), true);
      pem_form_end();
   }
   else
   {
      pem_form_begin(array("nameid" => $form, "action" => $target, "class" => $class));
      pem_hidden_input(array("name" => "a", "value" => "logout"));
      $centerthis = ($loc != "header") ? 1 : 0;
      pem_form_submit($form, __("Log Out"), $centerthis);
      if ($loc == "header")
      {
         echo "&nbsp;";
         $access_key = array("Edit Own", "Approve Own", "Delete Own");
         $provide_account_link = pem_user_authorized("Manage Users", $access_key);
         if ($provide_account_link) echo '<a href="" class="nobutton">';
         echo $login;
         if ($provide_account_link) echo '</a>';
      }
      pem_form_end();
   }
}

// Display the login form.
function pem_echo_login_form($error = "", $login = "", $pass = "")
{
   global $REDIRECT_URL, $header_complete;

   if (!isset($header_complete)) pem_simple_header();
   pem_error_list($error);

   pem_fieldset_begin(__("Submit the form below to continue"));
   pem_form_begin(array("nameid" => "loginform", "action" => $REDIRECT_URL, "class" => "loginform"));
   pem_hidden_input(array("name" => "a", "value" => "dologin"));

   pem_field_label(array("default" => __("Login: "), "for" => "new_user_login"));
   pem_text_input(array("nameid" => "new_user_login", "value" => $login, "size" => 25, "maxlength" => 60));
   echo "<br />";
   pem_field_label(array("default" => __("Password: "), "for" => "new_user_password"));
   pem_password_input(array("nameid" => "new_user_password", "value" => $pass, "size" => 25, "maxlength" => 64));
   echo "<br />";
   pem_form_submit("loginform", __("Account Log In") . ' &raquo;');
   echo '<input type="submit" class="hidden" />';
   pem_form_end();
   pem_fieldset_end();

   include ABSPATH . PEMINC . "/footer.php";
   exit;

   /*
   echo '<div class="buttons">';
   echo '<table cellspacing="0" cellpadding="0" border="0"><tr><td><img src="/images/button-left.gif" alt="left"><td><td class="button">';
   if (isset($HTTP_REFERER))
    echo '<a href="'.$HTTP_REFERER.'">'.__("Return to the previous page") . '</a>';
   else
    echo '<a href="javascript:history.back();">'.__("Return to the previous page") . '</a>';
   echo '</td><td><img src="/images/button-right.gif" alt="right"></td></tr></table>';
   echo '</div>';

   echo '<br /><br /></div>';
   */
}

// Request for login/password
function pem_get_auth()
{
   echo "<p>".__("You do not have rights to this area.  Contact your administrator to remedy this.")."</p>\n";
   echo_login_form();
}

// Returns the current cached value for the user login
function pem_get_login()
{
   $login = pem_cache_get("login");
   return (empty($login)) ? false : $login;
}

// Returns the current cached value for the user password
function pem_get_pass()
{
   $pass = pem_cache_get("pass");
   return (empty($pass)) ? false : $pass;
}

// Sets the provided login value in the cache
function pem_set_login($login)
{
   pem_cache_set("login", $login);
}

// Sets the provided password value in the cache
function pem_set_pass($pass)
{
   pem_cache_set("pass", $pass);
}

function pem_logout()
{
   pem_cache_flush("login");
   pem_cache_flush("pass");
}



?>