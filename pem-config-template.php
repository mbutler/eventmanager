<?php
/* ========================== FILE INFORMATION =================================
phxEventManager :: pem-config-template.php

These options must be reviewed and customized by the user prior to installation.
Rename or copy this file to "pem-config.php" once changes are compelte.
============================================================================= */
if (!defined("ABSPATH")) define("ABSPATH", dirname(__FILE__)."/");
if (!defined("PEMINC")) define("PEMINC", "pem-includes");

// ========================= DATABASE SETTINGS ================================
require_once "MDB2.php";

$dsn = array(
   "phptype"  => "mysql",               // The type of database, MDB2 options are listed below.
   "username" => "pemuser",             // The username of your database account
   "password" => "pempass",             // The corresponding account password
   "hostspec" => "localhost",           // The hostname of the database server; "localhost" will work in most cases
   "database" => "phxeventmanager",     // The database to use, the account above must have permissions

// SSL options are not fully configured.  The fields below are here as placholders.
   // "key"      => "client-key.pem",   //
   // "cert"     => "client-cert.pem",  // These DSN varibles are only needed if
   // "ca"       => "cacert.pem",       // you plan on using SSL with PEM.
   // "capath"   => "/path/to/ca/dir",  //
   // "cipher"   => "AES",              //
);
// $options = array(               // The ssl option must be set to true to use
//    "ssl" => true,               // SSL
// );

/* =============================================================================
MDB2 Database options include:
fbsql    -> FrontBase
ibase    -> InterBase (requires PHP 5)
mssql    -> Microsoft SQL Server (NOT for Sybase. Compile PHP --with-mssql)
mysql    -> MySQL
mysqli   -> MySQL (supports new authentication protocol) (requires PHP 5)
oci8     -> Oracle 7/8/9
pgsql    -> PostgreSQL
querysim -> QuerySim
sqlite   -> SQLite
============================================================================= */


/* =============================================================================
To prevent conflict with any data tht may already exist in your database, PEM
can add a prefix to its table names.  This feature can also be used to install
multiple copies of PEM within one database.  Each install must use a unique
prefix containing only letters, numbers, or the underscore character.  Using a
prefix will not cause problems in an otherwise empty database.
============================================================================= */
$table_prefix  = "pem_";


/* ================ DEVELOPMENT AND TROUBLESHOOTING ============================
Errors are rendered with more friendly but often less technically informative
information by default.  Set this variable to true to enable verbose error
reporting from MDB2.
============================================================================= */
$debug = true;


/* ========================= LANGUAGE SETTINGS ================================
The following variable alows you to define the default language to use with PEM.
PEM uses the GNU Gettext (http://www.gnu.org/software/gettext/gettext.html)
localization framework.  PEM includes a number language alternatives....
http://codex.wordpress.org/Translating_WordPress
============================================================================= */
define ("PEMLANG", "");  // en_US is default


/* ==================== AUTHENTICATION SETTINGS ================================
The following options let you configure how authentication is handled by
phxEventManager.  The methods defined by the $auth array will track the users'
sessions and can provide caching to speed up application response and cater to
users' interests by remembering preferences.
============================================================================= */

// Method used to get and keep the user ID.
// Valid options: php, cookie, http, ip, host
$auth["session"] = "php";

// Session information cache method
// Valid options: php, cookie
$auth["cache"] = "php";

// User login and password validation
// Valid options: db, pop3, imap, ldap, nis, nw, ext, config, none
$auth["type"] = "db";

?>