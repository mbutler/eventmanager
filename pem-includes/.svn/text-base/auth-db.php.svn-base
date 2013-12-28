<?php
/* ========================== FILE INFORMATION =================================
phxEventManager :: auth-db.php

Wrapper functions used to authenticate users from a table in the PEM database.
This authentication scheme is used when $auth["type"] is set to "db" in
pem-config.php.
============================================================================= */


/* session-php.inc and session-cookie.inc will add a link to the user list
   in the logon box, if the value $user_list_link is set. */
// $user_list_link = "edit-users.php";



// Adds an entry in the user table
// returns id of the updated entry
function auth_add_user($login, $pass, $name, $email, $profile, $status = 0)
{
   MDB2::loadFile("Date"); // load Date helper class

   $data = array(
     "user_login" => $login,
     "user_pass" => $pass,
     //"user_pass" => md5($pass);,
     "user_nicename" => $name,
     "user_email" => $email,
     "user_registered" => MDB2_Date::mdbNow(),
     // "user_registered" => pem_date("Y-m-d H:i:s"),  // MDB2 should provide a more universal timestamp
     "user_profile" => $profile,
     "status" => $status,
   );
   $types = array(
     "user_login" => "text",
     "user_pass" => "text",
     "user_nicename" => "text",
     "user_email" => "text",
     "user_registered" => "timestamp",
     "user_profile" => "integer",
     "status" => "integer",
   );
   return pem_add_row("users", $data, $types);
} // END auth_add_user


// Returns keyed array of user information matching provided login or id
// If neither login or id provided, will default to session account
function auth_get_user($id = "", $login = "")
{
   global $table_prefix;
   MDB2::loadFile("Date"); // load Date helper class

   if (empty($id) AND empty($login))
   {
      $login = pem_get_login();
      if (empty($login)) return false;
   }
   elseif (!is_numeric($id))
   {
      $login = $id;
      $id = 0;
   }
   if (!empty($login))
   {
      $login = strtolower($login);
      $res = pem_get_row("user_login", $login, "users");
   }
   else
   {
      $res = pem_get_row("id", $id, "users");
   }
   $res["user_registered"] = MDB2_Date::mdbstamp2Unix($res["user_registered"]);
   $res["user_activity"] = (empty($res["user_activity"])) ? "" : MDB2_Date::mdbstamp2Unix($res["user_activity"]);
   $res2 = pem_get_row("id", $res["user_profile"], "access_profiles");
   $res["user_access"] = unserialize($res2["profile"]);

   //echo "profile is: " . $res["user_profile"] . "<br />";
   //echo "access is: <pre>";
   //print_r($ret["access"]);
   //echo "</pre>";

   return $res;
} // END auth_get_user


function auth_get_user_id($login)
{
   global $table_prefix;

   $res = pem_get_row("user_login", $login, "users");
   $id = $res["id"];

   return $id;
} // END auth_get_user_id


// Retrives all entries in the user table
// returns a 2dim keyed array of the data
function auth_get_users()
{
   MDB2::loadFile("Date"); // load Date helper class

   $where = array("status" => array("!=", "2"));
   $user_list = pem_get_rows("users", $where, "", "user_login");
   for ($i = 0; $i < count($user_list); $i++)
   {
      $user_list[$i]["user_registered"] = MDB2_Date::mdbstamp2Unix($user_list[$i]["user_registered"]);
      $user_list[$i]["user_activity"] = (empty($user_list[$i]["user_activity"])) ? "" : MDB2_Date::mdbstamp2Unix($user_list[$i]["user_activity"]);
      $res = pem_get_row("id", $user_list[$i]["user_profile"], "access_profiles");
      $user_list[$i]["user_access"] = unserialize($res["profile"]);
   }
   return $user_list;
} // END auth_get_users

// Adds an entry in the user table
// returns id of the updated entry
function auth_update_user($id, $login, $pass, $name, $email, $profile, $status)
{
   $data = array(
     "user_login" => $login,
     "user_nicename" => $name,
     "user_email" => $email,
     "user_profile" => $profile,
     "status" => $status,
   );
   if (!empty($pass)) $data["user_pass"] = md5($pass);
   $where = array(
     "id" => $id,
   );
   return pem_update_row("users", $data, $where);
} // END auth_update_user

// Updates the status field with new setting
// returns id of the updated entry
function auth_update_user_status($id, $status)
{
   $data = array(
     "status" => $status,
   );
   $where = array(
     "id" => $id,
   );
   return pem_update_row("users", $data, $where);
} // END auth_update_user_status

// Sets the status field to deleted
function auth_delete_user($id)
{
   $where = array("id" => $id);
   pem_delete_recycle("users", $where);
} // END auth_delete_user


// Updates the activity field with current timestamp
// returns id of the updated entry
function auth_update_user_activity($login, $pass)
{
   MDB2::loadFile("Date"); // load Date helper class

   $data = array("user_activity" => MDB2_date::mdbNow());
   $where = array("user_login" => $login, "user_pass" => md5($pass));
   return pem_update_row("users", $data, $where);
} // END auth_update_user_activity

// Checks if the specified username/password pair are valid
// Returns false if the pair are invalid or do not exist
function auth_validate_user($login, $pass)
{
   $login = strtolower($login);
   $pass = md5($pass);
   $where = array("user_login" => $login, "user_pass" => $pass, "status" => 1);
   $res = pem_get_count("users", $where);
   if ($res > 0) return "validuser";
   else
   {
      $where = array("user_login" => $login);
      $res = pem_get_count("users", $where);
      if ($res > 0)
      {
         $where = array("user_login" => $login, "user_pass" => $pass, "status" => 0);
         $res = pem_get_count("users", $where);
         if ($res > 0) return "inactive";
         $where = array("user_login" => $login, "status" => 1);
         $res = pem_get_count("users", $where);
         if ($res > 0) return "badpass";
         $where = array("user_login" => $login, "user_pass" => $pass, "status" => 2);
         $res = pem_get_count("users", $where);
         if ($res > 0) return "deleted";
         return false;
      }
      else return false;
   }
} // END auth_validate_user

// Checks if the specified username has been used
// returns true/false
function auth_user_exists($login)
{
   $login = strtolower($login);
   $checkfor = array("user_login" => $login);
   $res = pem_get_count("users", $checkfor);
   if ($res > 0) return true;
   else return false;
} // END auth_user_exists


// checks the $access array for the $resource[$name] => $access_key pair
function auth_check_access($access, $resource)
{
   global $auth_keys;

/*
echo "<br />=======ACCESS====<br /><pre>";
print_r($access);
echo "</pre><br />==================<br />";

echo "<br />======RESOURCE=======<br /><pre>";
print_r($resource);
echo "</pre><br />==================<br />";
*/

   $auth_key_codes = array_flip($auth_keys);
   $access_granted = false;
   if (!empty($access))
   {
      if (isset($access["admin"]) AND $access["admin"]) $access_granted = true;
      else foreach ($resource AS $name => $access_key)
      {
         if (!is_array($access_key))
         {
            if ($access_key === true AND isset($access[$name])) $access_granted = true;
            if (isset($access[$name]) AND in_array($auth_key_codes[$access_key], $access[$name])) $access_granted = true;
         }
         else
         {
            foreach ($access_key AS $akey) if (isset($access[$name]) AND in_array($auth_key_codes[$akey], $access[$name])) $access_granted = true;
         }
      }
   }
   return $access_granted;
} // END auth_check_access

/*
   case (!is_array($resource) AND isset($access[$resource])):
      if ($key === true) $access_granted = true;
      elseif (!is_array($key) AND in_array($auth_key_codes[$key], $access[$resource])) $access_granted = true;
      elseif (is_array($key))
      {
         for ($i = 0; $i < count($key); $i++)
         {
            if (in_array($auth_key_codes[$key[$i]], $access[$resource])) $access_granted = true;
         }
      }
      break;
   case (is_array($resource)):
      for ($i = 0; $i < count($resource); $i++)
      {
         if (!is_array($key) AND array_key_exists($resource[$i], $access) AND ($key === true OR in_array($auth_key_codes[$key], $access[$resource[$i]])))
         {
            $access_granted = true;
         }
         elseif (is_array($key))
         {
            for ($j = 0; $j < count($key); $j++)
            {
               if (array_key_exists($resource[$i], $access) AND in_array($auth_key_codes[$key[$j]], $access[$resource[$i]]))
               {
                  $access_granted = true;
               }
            }
         }
      }
      break;

    */











// Collects the group memberships for a given user into a simple array
// Returns a two-dimensional array of resources and access keys for the user
function authGetUserGroups($login)
{
   global $table_prefix;

   $query = "SELECT user_access FROM " . $table_prefix . "user WHERE user_login='$login'";

   $pemdb =& mdb2_connect($dsn, $options, "connect");
   if (PEAR::isError($pemdb)) die($pemdb->getMessage()); // check for error
   $res = $pemdb->query($query);
   mdb2_disconnect($pemdb);

   $row = sql_row($res, 0);
   sql_free($res);

   $ret = convertGroupString($row[0]);
   return $ret;
}


/** authCheckUserGroup()
 * -------------------------------------------------------
 * FUNCTION DESCRIPTION
 * -------------------------------------------------------
 * Determines the users access level with a group check
 *
 * $user - The user name
 * $gid - The id of the group level that's being checked
 *
 * -------------------------------------------------------
 * FUNCTION RETURNS
 * -------------------------------------------------------
 *   TRUE if user is in the group
 *   FALSE if not or if user not logged in
 */
function authCheckUserGroup($user, $gid) {
   // User not logged in, user not in the group
   if(!isset($user))
     return FALSE;

   // Check if the $gid user is can modify
   $groups = authGetUserGroups($user);
   if (in_array($gid, $groups))
    return TRUE;
   else
    return FALSE;
   }



/** authGetUserList()
 * -------------------------------------------------------
 * FUNCTION DESCRIPTION
 * -------------------------------------------------------
 * Collects the user information into a simple array
 * -------------------------------------------------------
 * FUNCTION RETURNS
 * -------------------------------------------------------
 * array of variables matching the user table
 * error = failed query
*/
function authGetUserList() {
   global $tbl_user;

   $sql = "SELECT * FROM $tbl_user ORDER BY username";
   $res = sql_query($sql);
   if (!$res) fatal_error(0, sql_error());

   $nmatch = sql_count($res);
   if ($nmatch == 0) {
    echo "<p>" . __("No matching entries found.") . "</p>\n";
    sql_free($res);
    }
   else {
    for ($i = 0; ($row = sql_row_keyed($res, $i)); $i++)
     $ret[$i] = $row;
    }
   // sql_free($res);
   return $ret;
   }

/** authGetGroupList()
 * -------------------------------------------------------
 * FUNCTION DESCRIPTION
 * -------------------------------------------------------
 * Collects the group information into a simple array
 * -------------------------------------------------------
 * FUNCTION RETURNS
 * -------------------------------------------------------
 * array of variables matching the group table
 * error = failed query
*/
function authGetGroupList() {
   global $tbl_user_group;

   $sql = "SELECT * FROM $tbl_user_group";
   $res = sql_query($sql);
   if (!$res) fatal_error(0, sql_error());

   $nmatch = sql_count($res);
   if ($nmatch == 0) {
    echo "<p>" . __("No matching entries found.") . "</p>\n";
    sql_free($res);
    }
   else {
    for ($i = 0; ($row = sql_row_keyed($res, $i)); $i++)
     $ret[$i] = $row;
    }
   // sql_free($res);
   return $ret;
   }





/** authEditUser()
 * -------------------------------------------------------
 * FUNCTION DESCRIPTION
 * -------------------------------------------------------
 * Updates an entry in the user table based upon the given id
 * -------------------------------------------------------
 * FUNCTION RETURNS
 * -------------------------------------------------------
 * id of the updated entry
 * error = failed query
*/
function authEditUser($id, $username, $password, $gids, $name, $email) {
   global $tbl_user;

   $entryupdated = TRUE;
   $sql = "UPDATE $tbl_user SET username = '$username', group_ids = '$gids', name = '$name', email = '$email'";
   if (!empty($password))
    $sql .= ", password = '$password'";
   $sql .= " WHERE id = $id";
   if (sql_command($sql) < 0) $entryupdated = FALSE;
   if (!$entryupdated) fatal_error(0, sql_error());
   return $id;
   }


/** authDeleteUser()
 * -------------------------------------------------------
 * FUNCTION DESCRIPTION
 * -------------------------------------------------------
 * Deletes an entry in the user table based upon the given id
 * -------------------------------------------------------
 * FUNCTION RETURNS
 * -------------------------------------------------------
 * error = failed query
*/
function authDeleteUser($id) {
   global $tbl_user;

   $entrydeleted = TRUE;
   $sql = "DELETE FROM $tbl_user WHERE id = $id";
   if (sql_command($sql) < 0) $entrydeleted = FALSE;
   if (!$entrydeleted) fatal_error(0, sql_error());
   }



?>