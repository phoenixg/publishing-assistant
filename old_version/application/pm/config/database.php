<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the "Database Connection"
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|	['hostname'] The hostname of your database server.
|	['username'] The username used to connect to the database
|	['password'] The password used to connect to the database
|	['database'] The name of the database you want to connect to
|	['dbdriver'] The database type. ie: mysql.  Currently supported:
		 mysql, mysqli, postgre, odbc, mssql, sqlite, oci8
|	['dbprefix'] You can add an optional prefix, which will be added
|		 to the table name when using the  Active Record class
|	['pconnect'] TRUE/FALSE - Whether to use a persistent connection
|	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|	['cache_on'] TRUE/FALSE - Enables/disables query caching
|	['cachedir'] The path to the folder where cache files should be stored
|	['char_set'] The character set used in communicating with the database
|	['dbcollat'] The character collation used in communicating with the database
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the "default" group).
|
| The $active_record variables lets you determine whether or not to load
| the active record class
*/

$active_group = "default";
$active_record = TRUE;

/*
$db['default']['hostname'] = "localhost";
$db['default']['username'] = "root";
$db['default']['password'] = "1234";
$db['default']['database'] = "contentstore";
*/
/*
$db['default']['hostname'] = "198.151.217.248";
$db['default']['username'] = "meta_user";
$db['default']['password'] = "$5Mrewrik";
$db['default']['database'] = "sci_meta";
*/

$db['default']['hostname'] = "aaasphp01.aaas.org";
$db['default']['username'] = "test_user";
$db['default']['password'] = "science";
$db['default']['database'] = "sci-meta-dev";



$db['default']['dbdriver'] = "mysql";
$db['default']['dbprefix'] = "";
$db['default']['pconnect'] = TRUE;
$db['default']['db_debug'] = TRUE;
$db['default']['cache_on'] = FALSE;
$db['default']['cachedir'] = "";
$db['default']['char_set'] = "utf8";
$db['default']['dbcollat'] = "utf8_general_ci";

//config connection

$db['alert']['hostname'] = "aaasphp01.aaas.org";
$db['alert']['username'] = "test_user";
$db['alert']['password'] = "science";
$db['alert']['database'] = "sci-meta-dev";



$db['alert']['dbdriver'] = "mysql";
$db['alert']['dbprefix'] = "";
$db['alert']['pconnect'] = TRUE;
$db['alert']['db_debug'] = TRUE;
$db['alert']['cache_on'] = FALSE;
$db['alert']['cachedir'] = "";
$db['alert']['char_set'] = "utf8";
$db['alert']['dbcollat'] = "utf8_general_ci";


/* End of file database.php */
/* Location: ./system/application/config/database.php */