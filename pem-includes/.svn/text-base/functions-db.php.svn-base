<?php
/* ========================== FILE INFORMATION =================================
phxEventManager :: functions-db.php

Database management and abstraction functions. phxEventManager uses the
PEAR:MDB2 database abstraction package for all backend operations.  Database
software selection and other MDB2 options can be configured in the
pem-config.php file.
============================================================================= */

function mdb2_connect($dsn, $options = "", $function = "factory")
{
   if (empty($dsn)) global $dsn; // try to find viable connection settings;

   if ($function == "factory")
   {
      if (isset($options)) $mdb2 = MDB2::factory($dsn, $options);
      else $mdb2 = MDB2::factory($dsn);
   }
   elseif ($function == "singleton")
   {
      if (isset($options)) $mdb2 = MDB2::singleton($dsn, $options);
      else $mdb2 = MDB2::singleton($dsn);
   }
   else
   {
      if (isset($options)) $mdb2 = MDB2::connect($dsn, $options);
      else $mdb2 = MDB2::connect($dsn);
   }
   if (PEAR::isError($mdb2)) PEAR_error($mdb2);
   else return $mdb2;
}

function mdb2_disconnect($mdb2)
{
   $mdb2->disconnect();
}

// returns single row from table matching key/value pair
function pem_get_row($key, $value, $table = "settings")
{
   global $table_prefix, $pemdb;

   $fine_value = (is_numeric($value)) ? $value : "'" . $value . "'";
   $query = "SELECT * FROM " . $table_prefix . $table . " WHERE " . $key . " = " . $fine_value;
   $is_atomic = (!isset($pemdb)) ? true : false;
   if ($is_atomic)
   {
      global $dsn;
      global $options;
      $pemdb =& mdb2_connect($dsn, $options, "connect");
   }
   $res = $pemdb->query($query);
   if ($is_atomic)
   {
      mdb2_disconnect($pemdb);
   }
   while ($row = $res->fetchRow(MDB2_FETCHMODE_ASSOC))
   {
      return $row;
   }
   return false;
} // END pem_get_row


   // while (($one = $res->fetchOne()))
   // {
  //      echo $one . "\n";
  //  }

   // while (($row = $res->fetchRow())) // Assuming MDB2's default fetchmode is MDB2_FETCHMODE_ORDERED
//   {
//      echo $row[0] . "\n";
//   }



// returns all rows from table matching key/value pair(s)
// $where is an array with "key => value" used to build the query
function pem_get_rows($table, $where = "", $operator = "AND", $orderby = "")
{
   global $table_prefix, $pemdb;

   $fine_value = (is_numeric($value)) ? $value : "'" . $value . "'";
   $query = "SELECT * FROM " . $table_prefix . $table;
   if (!empty($where))
   {
      $query .= " WHERE";
      $keys = array_keys($where);
      for ($i = 0; $i < count($keys); $i++)
      {
         if (is_array($where[$keys[$i]]))
         {
            $compare = $where[$keys[$i]][0];
            $value = (is_numeric($where[$keys[$i]][1])) ? $where[$keys[$i]][1] : "'" . $where[$keys[$i]][1] . "'";
         }
         else
         {
            $compare = "=";
            $value = (is_numeric($where[$keys[$i]])) ? $where[$keys[$i]] : "'" . $where[$keys[$i]] . "'";
         }
         $query .= " " . $keys[$i] . " " . $compare . " " . $value;
         if ($i != count($keys) - 1) $query .= " " . $operator;
      }
   }
   if (!empty($orderby))
   {
      $query .= " ORDER BY";
      if (is_array($orderby))
      {
         for ($i = 0; $i < count($orderby); $i++)
         {
            $query .= " " . $orderby[$i];
            if ($i != count($orderby) - 1) $query .= ",";
         }
      }
      else
      {
         $query .= " " . $orderby;
      }
   }
   // echo "QUERY: $query <br />"; // Debugging line
   $is_atomic = (!isset($pemdb)) ? true : false;
   if ($is_atomic)
   {
      global $dsn, $options;
      $pemdb =& mdb2_connect($dsn, $options, "connect");
   }
   $res = $pemdb->query($query);
   while ($row = $res->fetchRow(MDB2_FETCHMODE_ASSOC))
   {
      $ret[] = $row;
   }
   if ($is_atomic)
   {
      mdb2_disconnect($pemdb);
   }
   return $ret;
} // END pem_get_rows


// executes preformatted sql statement
// ? expected in $sql to match items in $values string
function pem_exec_sql($sql, $values = "")
{
   global $pemdb;

/*
   echo "SQL: $sql <br />"; // Debug lines
   echo "Values: ";
   print_r($values);
   echo "<br />";
*/

   $is_atomic = (!isset($pemdb)) ? true : false;
   if ($is_atomic)
   {
      global $dsn, $options;
      $pemdb =& mdb2_connect($dsn, $options, "connect");
   }
//   older coding deprecated with MDB2 2.4.1
//   $sql_prep = $pemdb->prepare($sql);
//   if (PEAR::isError($sql_prep)) PEAR_error($sql_prep);
//   $res = $sql_prep->execute($values);
   if (empty($values))
   {
      $res = $pemdb->query($sql);
   }
   else
   {
      $sql_prep = $pemdb->prepare($sql);
      if (PEAR::isError($sql_prep)) PEAR_error($sql_prep);
      $res = $sql_prep->execute($values);
   }

   if (PEAR::isError($res)) PEAR_error($res);
   while ($row = $res->fetchRow(MDB2_FETCHMODE_ASSOC))
   {
      $ret[] = $row;
   }
   if ($is_atomic)
   {
      mdb2_disconnect($pemdb);
   }
   return $ret;
} // END pem_exec_sql


//==== THIS FUNCTION DOES NOT WORK, SAVED FOR CODE REFERENCE ===============
// returns all rows from (possibly joined) table(s) matching key/value pair(s)
// table.var expected for where/order use
// $tables can be single value or an array of values
// aliases may be assigned through the $table variables
// $where is an array with "key => value" used to build the query
function pem_get_rows_join($tables, $where = "", $operator = "AND", $orderby = "")
{
   global $table_prefix, $pemdb;

   $fine_value = (is_numeric($value)) ? $value : "'" . $value . "'";
   $query = "SELECT * FROM ";
   if (is_array($tables))
   {
      $last = count($tables);
      for ($i = 0; $i < $last; $i++)
      {
         $query .= $table_prefix . $tables[$i];
         if ($i < $last-1) $query .= ", ";
      }
   }
   else
   {
      $query .= $table_prefix . $tables;
   }
   if (!empty($where))
   {
      $query .= " WHERE";
      $keys = array_keys($where);
      for ($i = 0; $i < count($keys); $i++)
      {
         if (is_array($where[$keys[$i]]))
         {
            $compare = $where[$keys[$i]][0];
            $value = (is_numeric($where[$keys[$i]][1])) ? $where[$keys[$i]][1] : "'" . $where[$keys[$i]][1] . "'";
         }
         else
         {
            $compare = "=";
            $value = (is_numeric($where[$keys[$i]])) ? $where[$keys[$i]] : "'" . $where[$keys[$i]] . "'";
         }
         $query .= " " . $keys[$i] . " " . $compare . " " . $value;
         if ($i != count($keys) - 1) $query .= " " . $operator;
      }
   }
   if (!empty($orderby))
   {
      $query .= " ORDER BY";
      if (is_array($orderby))
      {
         for ($i = 0; $i < count($orderby); $i++)
         {
            $query .= " " . $orderby[$i];
            if ($i != count($orderby) - 1) $query .= ",";
         }
      }
      else
      {
         $query .= " " . $orderby;
      }
   }
   echo "QUERY: $query <br />"; // Debugging line
   $is_atomic = (!isset($pemdb)) ? true : false;
   if ($is_atomic)
   {
      global $dsn, $options;
      $pemdb =& mdb2_connect($dsn, $options, "connect");
   }
   $res = $pemdb->query($query);
   while ($row = $res->fetchRow(MDB2_FETCHMODE_ASSOC))
   {
      $ret[] = $row;
   }
   if ($is_atomic)
   {
      mdb2_disconnect($pemdb);
   }
   return $ret;
} // END pem_get_rows_join




// returns count of rows matching key/value pair(s) in $where
// $where is an array with "key => value" used to build the query
function pem_get_count($table, $where = "", $operator = "AND")
{
   global $table_prefix, $pemdb;

   $fine_value = (is_numeric($value)) ? $value : "'" . $value . "'";
   $query = "SELECT count(*) FROM " . $table_prefix . $table;
   if (!empty($where))
   {
      $query .= " WHERE";
      $keys = array_keys($where);
      for ($i = 0; $i < count($keys); $i++)
      {
         if (is_array($where[$keys[$i]]))
         {
            $compare = $where[$keys[$i]][0];
            $value = (is_numeric($where[$keys[$i]][1])) ? $where[$keys[$i]][1] : "'" . $where[$keys[$i]][1] . "'";
         }
         else
         {
            $compare = "=";
            $value = (is_numeric($where[$keys[$i]])) ? $where[$keys[$i]] : "'" . $where[$keys[$i]] . "'";
         }
         $query .= " " . $keys[$i] . " " . $compare . " " . $value;
         if ($i != count($keys) - 1) $query .= " " . $operator;
      }
   }
   $is_atomic = (!isset($pemdb)) ? true : false;
   if ($is_atomic)
   {
      global $dsn;
      global $options;
      $pemdb =& mdb2_connect($dsn, $options, "connect");
   }
   $res = $pemdb->queryOne($query);
   if ($is_atomic)
   {
      mdb2_disconnect($pemdb);
   }
   // echo "<br />" . $query . "<br />Count: " . $res;

   return ($res > 0) ? $res : false;
} // END pem_get_count


// returns the single column value matching key/value pair(s) in $where
// $where is an array with "key => value" used to build the query
function pem_get_value($table, $field, $where = "", $operator = "AND")
{
   global $table_prefix, $pemdb;

   $fine_value = (is_numeric($value)) ? $value : "'" . $value . "'";
   $query = "SELECT $field FROM " . $table_prefix . $table;
   if (!empty($where))
   {
      $query .= " WHERE";
      $keys = array_keys($where);
      for ($i = 0; $i < count($keys); $i++)
      {
         if (is_array($where[$keys[$i]]))
         {
            $compare = $where[$keys[$i]][0];
            $value = (is_numeric($where[$keys[$i]][1])) ? $where[$keys[$i]][1] : "'" . $where[$keys[$i]][1] . "'";
         }
         else
         {
            $compare = "=";
            $value = (is_numeric($where[$keys[$i]])) ? $where[$keys[$i]] : "'" . $where[$keys[$i]] . "'";
         }
         $query .= " " . $keys[$i] . " " . $compare . " " . $value;
         if ($i != count($keys) - 1) $query .= " " . $operator;
      }
   }
   $is_atomic = (!isset($pemdb)) ? true : false;
   if ($is_atomic)
   {
      global $dsn;
      global $options;
      $pemdb =& mdb2_connect($dsn, $options, "connect");
   }
   $res = $pemdb->queryOne($query);
   if ($is_atomic)
   {
      mdb2_disconnect($pemdb);
   }
   // echo "<br />" . $query . "<br />Count: " . $res;

   return $res;
} // END pem_get_value





// Adds single row to given table
// $data is an array with key => value
// $types is an array with key => format
function pem_add_row($table, $data, $types)
{
   global $pemdb, $table_prefix;

   $query = "INSERT INTO " . $table_prefix . $table;
   $keys = array_keys($data);
   $qa = "";
   $qb = "";
   for ($i = 0; $i < count($keys); $i++)
   {
      $qa .= $keys[$i];
      $qb .= ":" . $keys[$i];
      if ($i != count($keys) - 1)
      {
         $qa .= ", ";
         $qb .= ", ";
      }
   }
   $query .= " (" . $qa . ") VALUES (" . $qb . ")";
   unset($qa);
   unset($qb);
   $is_atomic = (!isset($pemdb)) ? true : false;
   // echo "QUERY: $query <br />"; // Debugging line
   if ($is_atomic)
   {
      global $dsn;
      global $options;
      $pemdb =& mdb2_connect($dsn, $options, "connect");
   }
   $statement = $pemdb->prepare($query, $types, MDB2_PREPARE_MANIP);
   if (PEAR::isError($statement)) PEAR_error($statement);
   $affected = $statement->execute($data);
   if (PEAR::isError($affected)) PEAR_error($affected);
   $insert_id = $pemdb->lastInsertID($table, "id");
   if ($is_atomic)
   {
      mdb2_disconnect($pemdb);
   }
   return $insert_id;
} // END pem_add_row

// Adds multiple rows to given table
// $data is a 2dim array with [] = key => value
// $types is an array with key => format
function pem_add_rows($table, $data, $types)
{
   global $pemdb, $table_prefix;

   $query = "INSERT INTO " . $table_prefix . $table;
   $keys = array_keys($data[0]);
   $qa = "";
   $qb = "";
   for ($i = 0; $i < count($keys); $i++)
   {
      $qa .= $keys[$i];
      $qb .= ":" . $keys[$i];
      if ($i != count($keys) - 1)
      {
         $qa .= ", ";
         $qb .= ", ";
      }
   }
   $query .= " (" . $qa . ") VALUES (" . $qb . ")";

   unset($qa);
   unset($qb);
   $is_atomic = (!isset($pemdb)) ? true : false;
   if ($is_atomic)
   {
      global $dsn;
      global $options;
      $pemdb =& mdb2_connect($dsn, $options, "connect");
   }
   $statement = $pemdb->prepare($query, $types, MDB2_PREPARE_MANIP);
   if (PEAR::isError($statement)) PEAR_error($statement);
   for($i=0;$i<count($data);$i++)
   {
      $affected = $statement->execute($data[$i]);
      if (PEAR::isError($affected)) PEAR_error($affected);
   }
   if ($is_atomic)
   {
      mdb2_disconnect($pemdb);
   }
   return true;
} // END pem_add_rows

// Updates single row in a given table
// $data is an array with key => value
// $where is an array with "key => value" used to build the query
function pem_update_row($table, $data, $where, $operator = "and")
{
   global $pemdb, $table_prefix;

   $query = "UPDATE " . $table_prefix . $table . " SET ";
   $keys = array_keys($data);
   for ($i = 0; $i < count($keys); $i++)
   {
     $dataprep = ($data[$keys[$i]] === "") ? "''" :  $pemdb->quote($data[$keys[$i]], $types[$keys[$i]]);
     $query .= $keys[$i] . " = " . $dataprep;
      if ($i != count($keys) - 1)
      {
         $query .= ", ";
      }
   }
   $query .= " WHERE";
   $keys = array_keys($where);
   for ($i = 0; $i < count($keys); $i++)
   {
      $dataprep = (is_numeric($where[$keys[$i]])) ? $where[$keys[$i]] : $pemdb->quote($where[$keys[$i]], "text");
      $query .= " " . $keys[$i] . " = " . $dataprep;
      if ($i != count($keys) - 1) $query .= " " . $operator;
   }
   // echo "QUERY: $query <br />"; // Debugging line
   $is_atomic = (!isset($pemdb)) ? true : false;
   if ($is_atomic)
   {
      global $dsn;
      global $options;
      $pemdb =& mdb2_connect($dsn, $options, "connect");
   }

//   older coding deprecated with MDB2 2.4.1
//   $statement = $pemdb->prepare($query, $types, MDB2_PREPARE_MANIP);
//   if (PEAR::isError($statement)) PEAR_error($statement);
//   $affected = $statement->execute($data);
//   if (PEAR::isError($affected)) PEAR_error($affected);

   $statement = $pemdb->query($query);
   if (PEAR::isError($statement)) PEAR_error($statement);

   $update_id = $pemdb->lastInsertID($table, "id");


   if ($is_atomic)
   {
      mdb2_disconnect($pemdb);
   }
   return $update_id;
} // END pem_update_row

// Deletes one or more in a given table
// $pairs is an array with "key => value" used to build the query
function pem_delete_perm($table, $pairs, $operator = "and")
{
   global $pemdb, $table_prefix;

   $query = "DELETE FROM " . $table_prefix . $table . " WHERE";
   $keys = array_keys($pairs);
   for ($i = 0; $i < count($keys); $i++)
   {
      $value = (is_numeric($pairs[$keys[$i]])) ? $pairs[$keys[$i]] : "'" . $pairs[$keys[$i]] . "'";
      $query .= " " . $keys[$i] . " = " . $value;
      if ($i != count($keys) - 1) $query .= " " . $operator;
   }
   $is_atomic = (!isset($pemdb)) ? true : false;
   if ($is_atomic)
   {
      global $dsn;
      global $options;
      $pemdb =& mdb2_connect($dsn, $options, "connect");
   }
   $statement = $pemdb->prepare($query, $types, MDB2_PREPARE_MANIP);
   if (PEAR::isError($statement)) PEAR_error($statement);
   $affected = $statement->execute($data);
   if (PEAR::isError($affected)) PEAR_error($affected);
   $update_id = $pemdb->lastInsertID($table, "id");
   if ($is_atomic)
   {
      mdb2_disconnect($pemdb);
   }
   return $update_id;
} // END pem_delete_perm

// "Deletes" one or more in a given table by setting status to deleted
// $pairs is an array with "key => value" used to build the query
function pem_delete_recycle($table, $pairs, $operator = "and")
{
   global $pemdb, $table_prefix;

   $query = "UPDATE " . $table_prefix . $table . " SET status = '2' WHERE";
   $keys = array_keys($pairs);
   for ($i = 0; $i < count($keys); $i++)
   {
      $value = (is_numeric($pairs[$keys[$i]])) ? $pairs[$keys[$i]] : "'" . $pairs[$keys[$i]] . "'";
      $query .= " " . $keys[$i] . " = " . $value;
      if ($i != count($keys) - 1) $query .= " " . $operator;
   }
   $is_atomic = (!isset($pemdb)) ? true : false;
   if ($is_atomic)
   {
      global $dsn;
      global $options;
      $pemdb =& mdb2_connect($dsn, $options, "connect");
   }
   $statement = $pemdb->prepare($query, $types, MDB2_PREPARE_MANIP);
   if (PEAR::isError($statement)) PEAR_error($statement);
   $affected = $statement->execute($data);
   if (PEAR::isError($affected)) PEAR_error($affected);
   $update_id = $pemdb->lastInsertID($table, "id");
   if ($is_atomic)
   {
      mdb2_disconnect($pemdb);
   }
   return $update_id;
} // END pem_delete_perm



// =============================================================================
// ======================== ERROR MANAGEMENT ===================================
// =============================================================================


function PEAR_error($obj)
{
   global $debug;
   $debug = true;
   if (isset($debug) AND $debug)
   {
      pem_die($obj->getMessage() . ', ' . $obj->getDebugInfo());
   }
   else
   {
      pem_die("An error has occured during a database operation.  Please report the problem to your system administrator for resolution.");
   }
}


?>
